<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\RoomFeaturesExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;


class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }
    public function showRoomFeaturePage()
{
    $hotels = \App\Models\HotelDetails::all();
    return view('admin.rooms.room_features', compact('hotels'));
}

// To return room + feature data
public function hotelRoomFeatures($hotelId)
{
    $features = \App\Models\RoomFeatures::where('hotel_id', $hotelId)->get();
    return response()->json($features);
}
public function showRoomFeatureOnlyPage()
{
    $hotels = \App\Models\HotelDetails::all();
    return view('admin.rooms.only_features', compact('hotels'));
}

public function exportRoomFeatures($hotelId)
{
    $hotel = \App\Models\HotelDetails::find($hotelId);
    $fileName = 'RoomFeatures_' . ($hotel->hotel_name ?? 'Hotel') . '.xlsx';

    return Excel::download(new RoomFeaturesExport($hotelId), $fileName, ExcelFormat::XLSX);
}


}
