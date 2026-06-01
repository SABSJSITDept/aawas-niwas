<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\BookedRoom;
use App\Models\Form;
use App\Models\FamilyBooking;
use App\Models\FamilyMember;
use App\Models\GroupBooking;
use App\Models\GroupMember;

class AutoCheckoutBookings extends Command
{
    protected $signature = 'booking:auto-checkout';
    protected $description = 'Automatically checks out expired bookings for VIP, Family, and Group types';

    public function handle()
    {
        $today = Carbon::today()->toDateString();

        // ✅ 1. Handle VIP checkouts
        $vipBookings = Form::whereDate('check_out_date', '<=', $today)
            ->where('status', '!=', 'checkout')
            ->get();

        foreach ($vipBookings as $vip) {
            BookedRoom::where('booking_id', $vip->id)->where('booking_type', 'vip')->delete();
            Form::where('id', $vip->id)->update(['status' => 'checkout']);
            $this->info("✅ VIP #{$vip->id} checked out.");
        }

        // ✅ 2. Handle Family checkouts
        $familyBookings = FamilyBooking::whereDate('check_out_date', '<=', $today)
            ->where('status', '!=', 'checkout')
            ->get();

        foreach ($familyBookings as $family) {
            BookedRoom::where('booking_id', $family->id)->where('booking_type', 'family')->delete();
            FamilyBooking::where('id', $family->id)->update(['status' => 'checkout']);
            FamilyMember::where('family_id', $family->id)->update(['status' => 'checkout']);
            $this->info("✅ Family #{$family->id} checked out.");
        }

        // ✅ 3. Handle Group checkouts
        $groupBookings = GroupBooking::whereDate('check_out_date', '<=', $today)
            ->where('status', '!=', 'checkout')
            ->get();

        foreach ($groupBookings as $group) {
            BookedRoom::where('booking_id', $group->id)->where('booking_type', 'group')->delete();
            GroupBooking::where('id', $group->id)->update(['status' => 'checkout']);
            GroupMember::where('group_booking_id', $group->id)->update(['status' => 'checkout']);
            $this->info("✅ Group #{$group->id} checked out.");
        }

        $this->info('✅ All expired bookings processed successfully.');
    }
}
