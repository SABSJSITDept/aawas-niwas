<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use Illuminate\Support\Facades\Log;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FormsExport;
use Maatwebsite\Excel\Excel as ExcelFormat;
use App\Models\Hotel;
use App\Models\RoomCategory;
use App\Models\HotelDetails;
use App\Models\BookedRoom;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class FormController extends Controller 
{


public function showBookings()
    {
        // Assuming you're fetching bookings and hotel details
        $bookings = Form::paginate(10);
    
        // For each booking, fetch hotel details
        $hotels = HotelDetails::all();
    
        return view('admin.forms', compact('bookings', 'hotels'));
    }





   
public function index()
{
    $forms = Form::paginate(7); // Har page par 10 records
    return view('admin.forms', compact('forms'));
}



    // ✅ Show form for editing
    public function edit($id) 
    {
        $form = Form::findOrFail($id);
        return view('admin.edit_form', compact('form'));
    }

    
    
    // ✅ Store new form data (AJAX Supported)


public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|digits:10',
        'mid' => 'nullable|string|max:255',
        'aadhar_number' => 'required|digits:12|unique:forms,aadhar_number',
        'department' => 'required|string|max:255',
        'is_coming' => 'required|boolean',
    ]);

    try {
        $form = Form::create($request->all());

$customBookingId = 100 + $form->id;
        $date = now()->format('d-m-Y');
$message = "JJ,JGR BOOKING ID .$customBookingId Date .$date AAPKA REGISTRATION HO GAYA HAI AAPKE PAHUCHNE SE PAHLE AAWAS KA STHAN SUCHIT KAR DIYA JAYEGA 6375359089 DESHNOKE SABSJS";

        // Send SMS
        $this->sendSms($form->phone, $message);

        return response()->json([
            'message' => 'Form submitted successfully!',
            'data' => $form
        ], 201);

    } catch (Exception $e) {
        Log::error('Form Submission Error: ' . $e->getMessage());

        return response()->json([
            'message' => 'Something went wrong!',
        ], 500);
    }
}

private function sendSms($number, $message, $dlttempid = 1007642277503874725)
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




    public function checkDuplicate(Request $request)
{
    $exists = Form::where('aadhar_number', $request->aadhar_number)
                  ->exists();

    return response()->json(['exists' => $exists]);
}

    
public function update(Request $request, $id) 
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|digits:10',
        'mid' => 'nullable|string|max:255',
        'department' => 'required|string|max:255',
        'is_coming' => 'required|boolean',
        'stay_arrangement' => 'nullable|string|max:255', // रहने की व्यवस्था वैकल्पिक
        'travel_details' => 'nullable|string|max:1000', // यात्रा विवरण वैकल्पिक
    ]);

    $form = Form::findOrFail($id);

    // डेटा अपडेट करने के लिए चेक करें
    $updateData = $request->only(['name', 'phone', 'mid', 'department', 'is_coming']);

    // अगर Family आ रही है (is_coming = 1), तो रहने की व्यवस्था भी अपडेट करें
    if ($request->is_coming == 1) {
        $updateData['stay_arrangement'] = $request->stay_arrangement ?? null;

        // अगर संघ की व्यवस्था चुनी गई है, तो यात्रा विवरण भी अपडेट करें
        if ($request->stay_arrangement == 'संघ की व्यवस्था') {
            $updateData['travel_details'] = $request->travel_details ?? null;
        }
    }

    // अपडेट करें
    $form->update($updateData);

    return redirect()->route('forms.index')->with('success', 'Form updated successfully!');
}

    // ✅ Delete form
    public function destroy($id) 
    {
        $form = Form::findOrFail($id);
        $form->delete();

        return redirect()->route('forms.index')->with('success', 'Form deleted successfully!');
    }

    

public function exportForms()
{
    return Excel::download(new FormsExport, 'forms.xlsx', ExcelFormat::XLSX);
}

public function checkout($id)
{
    // 1. Delete booked room entries for this VIP booking
    BookedRoom::where('booking_id', $id)
              ->where('booking_type', 'vip')
              ->delete();

    // 2. Update status of the booking in forms table
    \App\Models\Form::where('id', $id)->update(['status' => 'checkout']);

    return redirect()->back()->with('success', 'VIP Check-out successful. Room removed and status updated.');
}


}



