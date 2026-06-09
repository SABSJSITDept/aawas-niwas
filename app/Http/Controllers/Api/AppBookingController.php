<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FamilyBooking;
use App\Models\GroupBooking;
use App\Models\GroupMember;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AppBookingController extends Controller
{
    /**
     * ✅ Store Family Booking from APP
     */
    public function storeFamilyBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'               => 'required|string|max:255',
            'father_name'        => 'required|string|max:255',
            'phone'              => 'required|digits:10',
            'age'                => 'required|numeric',
            'aadhar_number'      => 'nullable|string|max:15|unique:family_booking,aadhar_number',
            'mid'                => 'nullable|string|max:15',
            'ms_name'            => 'nullable|string|max:255',
            'city'               => 'required|string|max:255',
            'state'              => 'required|string|max:255',
            'aanchal'            => 'required|string|max:255',
            'travel_type'        => 'required|string',
            'check_in_date'      => 'required|date',
            'check_out_date'     => 'required|date|after_or_equal:check_in_date',
            'check_in_time'      => 'required',
            'check_out_time'     => 'required',
            'family_coming'      => 'required|in:0,1',
            'no_of_people'       => 'nullable|integer|min:0',
            'no_of_children'     => 'nullable|integer|min:0',
            'total_male'         => 'nullable|integer|min:0',
            'total_female'       => 'nullable|integer|min:0',
            'sixty_plus_members' => 'nullable|integer|min:0',
            'sixty_plus_male'    => 'nullable|integer|min:0',
            'sixty_plus_female'  => 'nullable|integer|min:0',
            'is_veer_parivar'    => 'required|boolean',
            'veer_relation'      => 'nullable|string',
            'gender'             => 'required|in:male,female',
            'remark'             => 'nullable|string|max:1000',
            'family_members'     => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['total_persons'] = ($request->no_of_people ?? 0) + 1;
            $data['booking_type'] = 'family';
            $data['status'] = 'pending';

            // Create booking entry
            $booking = FamilyBooking::create($data);

            // Generate custom booking ID
            $customBookingId = 'F-' . (100 + $booking->id);
            $booking->update(['booking_id' => $customBookingId]);

            // Save family members
            if ($request->family_coming == "1" && $request->has('family_members') && is_array($request->family_members)) {
                foreach ($request->family_members as $member) {
                    // Assuming familyMembers() is a valid relationship on FamilyBooking
                    $booking->familyMembers()->create([
                        'name'          => $member['name'] ?? null,
                        'father_name'   => $member['father_name'] ?? null,
                        'mobile'        => $member['mobile'] ?? null,
                        'age'           => $member['age'] ?? null,
                        'gender'        => $member['gender'] ?? null,
                        'aadhar_number' => $member['aadhar_number'] ?? null,
                        'status'        => 'pending',
                    ]);
                }
            }

            DB::commit();

            // Send SMS
            $date = now()->format('d-m-Y');
            $message = "JJ, JGR Booking ID: $customBookingId Date $date Your registration has been successfully completed. SABSJS";
            $this->sendSms($request->phone, $message);

            return response()->json([
                'success' => true,
                'message' => 'Family booking created successfully',
                'data' => [
                    'id' => $booking->id,
                    'booking_id' => $customBookingId
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('API Family booking store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ Store Group Booking from APP
     */
    public function storeGroupBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'               => 'required|string|max:255',
            'father_name'        => 'required|string|max:255',
            'relationship_type'  => 'nullable|string|in:Son of,Daughter of,Wife of',
            'aadhar_number'      => 'nullable|string|max:15',
            'mid'                => 'nullable|string|max:15',
            'phone'              => 'required|string|max:15',
            'city'               => 'required|string',
            'state'              => 'required|string',
            'aanchal'            => 'required|string',
            'travel_type'        => 'required|string',
            'check_in_date'      => 'required|date',
            'check_out_date'     => 'required|date',
            'check_in_time'      => 'required',
            'check_out_time'     => 'required',
            'total_members'      => 'required|integer|min:1',
            'total_male'         => 'required|numeric|min:0',
            'total_female'       => 'required|numeric|min:0',
            'child_count'        => 'required|numeric|min:0',
            'sixty_plus_members' => 'nullable|numeric|min:0',
            'sixty_plus_male'    => 'nullable|numeric|min:0',
            'sixty_plus_female'  => 'nullable|numeric|min:0',
            'members'            => 'nullable|array',
            'remark'             => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $validatedData = $validator->validated();
            $totalPersons = $validatedData['total_members'] + 1;

            $groupBooking = GroupBooking::create([
                'name'               => $validatedData['name'],
                'father_name'        => $validatedData['father_name'],
                'relationship_type'  => $validatedData['relationship_type'] ?? null,
                'phone'              => $validatedData['phone'],
                'aadhar_number'      => $validatedData['aadhar_number'] ?? null,
                'mid'                => $validatedData['mid'] ?? null,
                'city'               => $validatedData['city'],
                'state'              => $validatedData['state'],
                'aanchal'            => $validatedData['aanchal'],
                'travel_type'        => $validatedData['travel_type'],
                'check_in_date'      => $validatedData['check_in_date'],
                'check_out_date'     => $validatedData['check_out_date'],
                'check_in_time'      => $validatedData['check_in_time'],
                'check_out_time'     => $validatedData['check_out_time'],
                'total_members'      => (int)$validatedData['total_members'],
                'total_male'         => (int)$validatedData['total_male'],
                'total_female'       => (int)$validatedData['total_female'],
                'child_count'        => (int)$validatedData['child_count'],
                'sixty_plus_members' => (int)($validatedData['sixty_plus_members'] ?? 0),
                'sixty_plus_male'    => (int)($validatedData['sixty_plus_male'] ?? 0),
                'sixty_plus_female'  => (int)($validatedData['sixty_plus_female'] ?? 0),
                'total_persons'      => $totalPersons,
                'remark'             => $validatedData['remark'] ?? null,
                'status'             => 'pending',
                'booking_type'       => 'group',
            ]);

            // Generate Custom ID
            $generatedBookingId = 'G-' . ($groupBooking->id + 100);
            $groupBooking->booking_id = $generatedBookingId;
            $groupBooking->save();

            // Save Members
            if (!empty($validatedData['members']) && is_array($validatedData['members'])) {
                foreach ($validatedData['members'] as $member) {
                    if (empty($member['name']) && empty($member['mobile_number'])) {
                        continue;
                    }
                    GroupMember::create([
                        'group_booking_id' => $groupBooking->id,
                        'name' => $member['name'] ?? null,
                        'mobile_number' => $member['mobile_number'] ?? null,
                        'status' => 'pending',
                    ]);
                }
            }

            DB::commit();

            // Send SMS
            $date = now()->format('d-m-Y');
            $message = "JJ, JGR Booking ID: $generatedBookingId Date $date Your registration has been successfully completed. SABSJS";
            $this->sendSms($validatedData['phone'], $message);

            return response()->json([
                'success' => true,
                'message' => 'Group booking created successfully',
                'data' => [
                    'id' => $groupBooking->id,
                    'booking_id' => $generatedBookingId
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('API Group booking store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send SMS Helper
     */
    private function sendSms($number, $message, $dlttempid = 1007932841660700100)
    {
        $user     = "JainSangh";
        $password = "Jain@12";
        $senderid = "ABSJHO";
        $channel  = "trans";
        $dcs      = "0";
        $flashsms = "0";
        $route    = "4";
        $peid     = "1001071123690830532";

        $params = [
            'user'          => $user,
            'password'      => $password,
            'senderid'      => $senderid,
            'channel'       => $channel,
            'DCS'           => $dcs,
            'flashsms'      => $flashsms,
            'number'        => $number,
            'text'          => $message,
            'route'         => $route,
            'PEID'          => $peid,
            'DLTTemplateId' => $dlttempid,
        ];

        $query = http_build_query($params);
        $url = "http://www.bulksms.saakshisoftware.in/api/mt/SendSMS?" . $query; 
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
