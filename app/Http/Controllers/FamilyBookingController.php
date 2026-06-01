<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\FamilyBooking;
use App\Models\Hotel;
use App\Models\RoomCategory;
use App\Models\Category;
use App\Models\BookedRoom;
use App\Exports\FamilyBookingExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Illuminate\Support\Facades\DB;
use App\Models\Aanchal;
use Illuminate\Support\Facades\Http;
use App\Exports\FamilyMemberExport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;








class FamilyBookingController extends Controller
{

public function checkAadhar(Request $request)
{
    $aadhar = $request->aadhar_number;

    $exists = DB::table('family_booking')
                ->where('aadhar_number', $aadhar)
                ->whereIn('status', ['pending', 'completed'])
                ->exists() ||

              DB::table('family_members')
                ->where('aadhar_number', $aadhar)
                ->whereIn('status', ['pending', 'completed'])
                ->exists() ||

              DB::table('group_bookings')
                ->where('aadhar_number', $aadhar)
                ->whereIn('status', ['pending', 'completed'])
                ->exists() ||

              DB::table('group_members')
                ->where('aadhar_number', $aadhar)
                ->whereIn('status', ['pending', 'completed'])
                ->exists() ||

              DB::table('forms')
                ->where('aadhar_number', $aadhar)
                ->whereIn('status', ['pending', 'completed'])
                ->exists();

    return response()->json(['exists' => $exists]);
}

/**
 * ✅ Parse Excel file and return data
 */
public function parseExcel(Request $request)
{
    try {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:5120'
        ]);

        $file = $request->file('excel_file');
        
        // Load the file using PhpSpreadsheet
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        
        // Get all data
        $data = $sheet->toArray();
        
        // Assuming first row is header
        if (count($data) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'एक्सेल फाइल खाली है या डेटा नहीं है।'
            ]);
        }

        $headers = $data[0];

        // Map Excel columns to form fields
        $fieldMapping = [
            'नाम' => 'name',
            'Name' => 'name',
            'नाम (Name)' => 'name',
            
            'पिता/पति का नाम' => 'father_name',
            'Father Name' => 'father_name',
            'पिता/पति का नाम (Father/Husband Name)' => 'father_name',
            
            'उम्र' => 'age',
            'Age' => 'age',
            'उम्र (Age)' => 'age',
            
            'मोबाइल नंबर' => 'phone',
            'Phone' => 'phone',
            'मोबाइल नंबर (Phone)' => 'phone',
            
            'MID' => 'mid',
            
            'आधार नंबर' => 'aadhar_number',
            'Aadhar' => 'aadhar_number',
            'आधार नंबर (Aadhar)' => 'aadhar_number',
            
            'शहर' => 'city',
            'City' => 'city',
            'शहर (City)' => 'city',
            
            'राज्य' => 'state',
            'State' => 'state',
            'राज्य (State)' => 'state',
            
            'अंचल' => 'aanchal',
            'Aanchal' => 'aanchal',
            'अंचल (Aanchal)' => 'aanchal',
            
            'आने का वाहन' => 'travel_type',
            'Travel Type' => 'travel_type',
            'आने का वाहन (Travel Type)' => 'travel_type',
            
            'आगमन की दिनांक' => 'check_in_date',
            'Check In Date' => 'check_in_date',
            'आगमन की दिनांक (Check In Date)' => 'check_in_date',
            
            'आगमन का समय' => 'check_in_time',
            'Check In Time' => 'check_in_time',
            'आगमन का समय (Check In Time)' => 'check_in_time',
            
            'प्रस्थान की दिनांक' => 'check_out_date',
            'Check Out Date' => 'check_out_date',
            'प्रस्थान की दिनांक (Check Out Date)' => 'check_out_date',
            
            'प्रस्थान का समय' => 'check_out_time',
            'Check Out Time' => 'check_out_time',
            'प्रस्थान का समय (Check Out Time)' => 'check_out_time',
            
            'रिमार्क' => 'remark',
            'Remark' => 'remark',
            'रिमार्क (Remark)' => 'remark',

            'कुल व्यक्ति' => 'total_persons',
            'Total Persons' => 'total_persons',
            'कुल व्यक्ति (Total Persons)' => 'total_persons',
        ];

        // Process ALL rows (not just first one)
        $allParsedData = [];
        $errorRows = [];
        
        for ($rowIndex = 1; $rowIndex < count($data); $rowIndex++) {
            $row = $data[$rowIndex];
            
            // Skip empty rows
            if (array_filter($row) == array()) {
                continue;
            }
            
            $parsedData = [];
            
            foreach ($headers as $index => $header) {
                $value = $row[$index] ?? null;
                
                if (!$value) continue;
                
                // Find the field name from mapping
                $fieldName = $fieldMapping[$header] ?? null;
                
                if ($fieldName) {
                    $parsedData[$fieldName] = $value;
                }
            }

            // Validate required fields
            $requiredFields = ['name', 'father_name', 'age', 'phone'];
            $missing = array_filter($requiredFields, function($field) use ($parsedData) {
                return empty($parsedData[$field]);
            });

            if (!empty($missing)) {
                $errorRows[] = [
                    'row' => $rowIndex + 1,
                    'message' => 'आवश्यक फील्ड गायब: ' . implode(', ', $missing)
                ];
                continue;
            }

            $allParsedData[] = $parsedData;
        }

        if (empty($allParsedData)) {
            return response()->json([
                'success' => false,
                'message' => 'कोई वैध डेटा नहीं मिला। ' . (count($errorRows) > 0 ? 'त्रुटियाँ देखें।' : '')
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $allParsedData,
            'total_rows' => count($allParsedData),
            'error_rows' => $errorRows,
            'message' => count($allParsedData) . ' पंक्तियाँ सफलतापूर्वक पार्स हुईं।'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'एक्सेल पार्सिंग त्रुटि: ' . $e->getMessage()
        ]);
    }
}

/**
 * ✅ Download Excel Template
 */
public function downloadExcelTemplate()
{
    try {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Family Booking Template');
        
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(20);
        $sheet->getColumnDimension('L')->setWidth(15);
        $sheet->getColumnDimension('M')->setWidth(20);
        $sheet->getColumnDimension('N')->setWidth(30);
        
        // Create header row with styling
        $headers = [
            'नाम (Name)',
            'पिता/पति का नाम (Father/Husband Name)',
            'उम्र (Age)',
            'मोबाइल नंबर (Phone)',
            'कुल व्यक्ति (Total Persons)',
            'शहर (City)',
            'राज्य (State)',
            'अंचल (Aanchal)',
            'आने का वाहन (Travel Type)',
            'आगमन की दिनांक (Check In Date)',
            'आगमन का समय (Check In Time)',
            'प्रस्थान की दिनांक (Check Out Date)',
            'प्रस्थान का समय (Check Out Time)',
            'रिमार्क (Remark)'
        ];
        
        foreach ($headers as $col => $header) {
            $cell = $sheet->getCellByColumnAndRow($col + 1, 1);
            $cell->setValue($header);
            
            // Style header
            $cell->getStyle()->getFont()->setBold(true);
            $cell->getStyle()->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
            $cell->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $cell->getStyle()->getFill()->getStartColor()->setARGB('FF4472C4');
            $cell->getStyle()->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $cell->getStyle()->getAlignment()->setWrapText(true);
        }
        
        // Add sample data row
        $sampleData = [
            'राज कुमार',                    // नाम
            'मोहन प्रसाद',                  // पिता/पति का नाम
            '35',                            // उम्र
            '9876543210',                    // मोबाइल नंबर
            '4',                             // कुल व्यक्ति
            'जयपुर',                         // शहर
            'राजस्थान',                     // राज्य
            'नोखा',                          // अंचल
            'Train',                         // आने का वाहन
            '2026-07-05',                    // आगमन की दिनांक
            '10:30',                         // आगमन का समय
            '2026-07-08',                    // प्रस्थान की दिनांक
            '14:00',                         // प्रस्थान का समय
            'कोई विशेष टिप्पणी नहीं'          // रिमार्क
        ];
        
        foreach ($sampleData as $col => $value) {
            $cell = $sheet->getCellByColumnAndRow($col + 1, 2);
            $cell->setValue($value);
            $cell->getStyle()->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        }
        
        // Freeze header row
        $sheet->freezePane('A2');
        
        // Create Excel file
        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'excel_template');
        $writer->save($tempFile);
        
        return response()->download($tempFile, 'Family_Booking_Template.xlsx')->deleteFileAfterSend(true);
        
    } catch (\Exception $e) {
        return back()->with('error', 'टेम्पलेट डाउनलोड करने में त्रुटि: ' . $e->getMessage());
    }
}

   public function export()
{
    return Excel::download(new FamilyBookingExport, 'family_bookings.xlsx', ExcelFormat::XLSX);
}

public function index(Request $request)
{
    $query = FamilyBooking::with(['cityName', 'stateName', 'aanchalName']);

    // Apply search filters
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('phone', 'like', "%$search%");
        });
    }

    if ($request->filled('aadhar_number')) {
        $query->where('aadhar_number', 'like', "%{$request->aadhar_number}%");
    }

    if ($request->filled('city')) {
        $city = \App\Models\City::where('city_name', 'like', '%' . $request->city . '%')->first();
        if ($city) {
            $query->where('city', $city->city_id);
        } else {
            $query->where('city', 0);
        }
    }

    if ($request->filled('travel_type')) {
        $query->where('travel_type', $request->travel_type);
    }

    if ($request->filled('check_in_date')) {
        $query->where('check_in_date', $request->check_in_date);
    }

    if ($request->filled('check_out_date')) {
        $query->where('check_out_date', $request->check_out_date);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('aanchal')) {
        $query->where('aanchal', $request->aanchal); 
    }

    // ✅ New Filter: is_veer_parivar
    if ($request->filled('is_veer_parivar')) {
        $query->where('is_veer_parivar', $request->is_veer_parivar);
    }

    // ✅ New Filter: ID with minus 100 logic
    if ($request->filled('id')) {
        $filteredId = (int)$request->id - 100;
        $query->where('id', $filteredId);
    }

    // Paginate and transform to attach room data
    $bookings = $query->orderByDesc('id')->paginate(7);

    $bookings->getCollection()->transform(function ($booking) {
        $rooms = \App\Models\BookedRoom::where('booking_id', $booking->id)
                           ->where('booking_type', 'family')
                           ->get();

        $booking->rooms_allotted = $rooms->isNotEmpty();
        $booking->hotel_name = optional($rooms->first()?->hotel)->hotel_name ?? null;
        $booking->room_numbers = $rooms->pluck('room_number')->toArray();

        return $booking;
    });

    $aanchals = Aanchal::orderBy('display_order')->get();

    return view('family-booking.list', compact('bookings', 'aanchals'));
}







    public function create()
    {
        return view('family-booking.create');
    }

    /**
     * ✅ Display Excel Upload Page for Admin
     */
    public function excelUploadPage()
    {
        return view('admin.family-booking.excel-upload');
    }

    /**
     * ✅ Save Excel Data to Database (Admin Panel) - Handles Multiple Records
     */
    public function saveExcelData(Request $request)
    {
        try {
            // Check if data is array (multiple records) or single record
            $records = $request->input('records') ? json_decode($request->input('records'), true) : null;
            
            if (!$records || !is_array($records) || empty($records)) {
                return response()->json([
                    'success' => false,
                    'message' => 'कोई वैध डेटा नहीं मिला।'
                ], 422);
            }

            $savedBookings = [];
            $failedRows = [];
            
            foreach ($records as $index => $recordData) {
                try {
                    // Validate each record
                    $validated = [
                        'name'               => $recordData['name'] ?? null,
                        'father_name'        => $recordData['father_name'] ?? null,
                        'phone'              => $recordData['phone'] ?? null,
                        'age'                => $recordData['age'] ?? null,
                        'city'               => $recordData['city'] ?? null,
                        'state'              => $recordData['state'] ?? null,
                        'aanchal'            => $recordData['aanchal'] ?? null,
                        'travel_type'        => $recordData['travel_type'] ?? null,
                        'check_in_date'      => $recordData['check_in_date'] ?? null,
                        'check_out_date'     => $recordData['check_out_date'] ?? null,
                        'check_in_time'      => $recordData['check_in_time'] ?? null,
                        'check_out_time'     => $recordData['check_out_time'] ?? null,
                        'remark'             => $recordData['remark'] ?? null,
                    ];

                    // Check required fields
                    if (!$validated['name'] || !$validated['father_name'] || !$validated['phone'] || !$validated['age']) {
                        $failedRows[] = array_merge($validated, [
                            'row' => $index + 1,
                            'reason' => 'आवश्यक फील्ड गायब हैं (नाम, पिता/पति का नाम, फोन, या उम्र)'
                        ]);
                        continue;
                    }

                    // Format phone number
                    $validated['phone'] = (string)$validated['phone'];
                    if (strlen($validated['phone']) != 10 || !is_numeric($validated['phone'])) {
                        $failedRows[] = array_merge($validated, [
                            'row' => $index + 1,
                            'reason' => 'अमान्य फोन नंबर - बिल्कुल 10 अंक होने चाहिए'
                        ]);
                        continue;
                    }

                    // Format age
                    $validated['age'] = (int)$validated['age'];
                    if ($validated['age'] < 10 || $validated['age'] > 150) {
                        $failedRows[] = array_merge($validated, [
                            'row' => $index + 1,
                            'reason' => 'अमान्य उम्र - 10 से 150 के बीच होनी चाहिए'
                        ]);
                        continue;
                    }

                    // Prepare data for database
                    $data = $validated;
                    $data['family_coming'] = 0;
                    $data['total_persons'] = max(1, (int)($recordData['total_persons'] ?? 1));
                    $data['booking_type'] = 'family';
                    $data['status'] = 'pending';

                    // Create booking entry
                    $booking = FamilyBooking::create($data);

                    // Generate custom booking ID
                    $customBookingId = 'F-' . (100 + $booking->id);
                    $booking->update(['booking_id' => $customBookingId]);

                    // Try to send SMS
                    try {
                        $date = now()->format('d-m-Y');
                        $message = "JJ, JGR Booking ID: $customBookingId Date $date Your registration has been successfully completed. SABSJS";
                        $this->sendSms($validated['phone'], $message);
                    } catch (\Exception $e) {
                        Log::warning('SMS failed for phone: ' . $validated['phone']);
                    }

                    $savedBookings[] = [
                        'id' => $booking->id,
                        'booking_id' => $customBookingId,
                        'name' => $validated['name']
                    ];

                } catch (\Exception $e) {
                    $failedRows[] = array_merge($recordData, [
                        'row' => $index + 1,
                        'reason' => $e->getMessage()
                    ]);
                    continue;
                }
            }

            if (count($savedBookings) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'कोई भी रिकॉर्ड सहेजा नहीं जा सका।',
                    'failed_rows' => $failedRows
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => count($savedBookings) . ' डेटा सफलतापूर्वक सहेज दिए गए हैं।',
                'saved_count' => count($savedBookings),
                'failed_count' => count($failedRows),
                'saved_bookings' => $savedBookings,
                'failed_rows' => $failedRows,
                'redirect' => route('family-booking.index')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'डेटा सहेजते समय त्रुटि: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * ✅ Download Failed Rows as Excel
     */
    public function downloadFailedRows(Request $request)
    {
        try {
            $failedRows = $request->input('failed_rows');
            
            if (!$failedRows || !is_array($failedRows) || empty($failedRows)) {
                return response()->json([
                    'success' => false,
                    'message' => 'कोई विफल पंक्ति नहीं मिली।'
                ], 400);
            }

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Failed Records');
            
            // Set column widths
            $colWidths = [
                'A' => 10,  // #
                'B' => 20,  // Name
                'C' => 25,  // Father Name
                'D' => 10,  // Age
                'E' => 15,  // Phone
                'F' => 15,  // MID
                'G' => 15,  // Aadhar
                'H' => 20,  // City
                'I' => 20,  // State
                'J' => 20,  // Aanchal
                'K' => 20,  // Travel Type
                'L' => 15,  // Check In Date
                'M' => 20,  // Check In Time
                'N' => 20,  // Check Out Date
                'O' => 20,  // Check Out Time
                'P' => 30   // Reason
            ];
            
            foreach ($colWidths as $col => $width) {
                $sheet->getColumnDimension($col)->setWidth($width);
            }

            // Headers
            $headers = [
                'क्रमांक (#)',
                'नाम (Name)',
                'पिता/पति का नाम (Father/Husband Name)',
                'उम्र (Age)',
                'मोबाइल नंबर (Phone)',
                'MID',
                'आधार नंबर (Aadhar)',
                'शहर (City)',
                'राज्य (State)',
                'अंचल (Aanchal)',
                'आने का वाहन (Travel Type)',
                'आगमन की दिनांक (Check In Date)',
                'आगमन का समय (Check In Time)',
                'प्रस्थान की दिनांक (Check Out Date)',
                'प्रस्थान का समय (Check Out Time)',
                'विफलता का कारण (Reason)'
            ];

            // Add header row
            foreach ($headers as $col => $header) {
                $cell = $sheet->getCellByColumnAndRow($col + 1, 1);
                $cell->setValue($header);
                
                $cell->getStyle()->getFont()->setBold(true);
                $cell->getStyle()->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
                $cell->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $cell->getStyle()->getFill()->getStartColor()->setARGB('FFC65911');
                $cell->getStyle()->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $cell->getStyle()->getAlignment()->setWrapText(true);
            }

            // Add data rows
            foreach ($failedRows as $rowIdx => $record) {
                $rowNum = $rowIdx + 2;
                
                // Ensure all values are strings to avoid issues
                $rowData = [
                    $record['row'] ?? ($rowIdx + 1),
                    $record['name'] ?? '',
                    $record['father_name'] ?? '',
                    $record['age'] ?? '',
                    $record['phone'] ?? '',
                    $record['mid'] ?? '',
                    $record['aadhar_number'] ?? '',
                    $record['city'] ?? '',
                    $record['state'] ?? '',
                    $record['aanchal'] ?? '',
                    $record['travel_type'] ?? '',
                    $record['check_in_date'] ?? '',
                    $record['check_in_time'] ?? '',
                    $record['check_out_date'] ?? '',
                    $record['check_out_time'] ?? '',
                    $record['reason'] ?? 'अज्ञात कारण'
                ];
                
                foreach ($rowData as $colIdx => $value) {
                    $cell = $sheet->getCellByColumnAndRow($colIdx + 1, $rowNum);
                    $cell->setValue($value);
                    $cell->getStyle()->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $cell->getStyle()->getAlignment()->setWrapText(true);
                    $cell->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $cell->getStyle()->getFill()->getStartColor()->setARGB('FFFDE7D9');
                }
            }

            // Freeze header row
            $sheet->freezePane('A2');

            // Create Excel file and send as response
            $writer = new Xlsx($spreadsheet);
            $tempFile = tempnam(sys_get_temp_dir(), 'failed_records_');
            $writer->save($tempFile);

            $filename = 'Failed_Entries_' . date('d-m-Y_H-i-s') . '.xlsx';
            
            // Return file download
            return response()->download($tempFile, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Failed rows download error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Excel डाउनलोड करने में त्रुटि: ' . $e->getMessage()
            ], 500);
        }
    }
      
    /**
     * ✅ Store new booking
     */
public function store(Request $request)
{  
    $request->validate([
        'name'               => 'required|string|max:255',
        'father_name'        => 'required|string|max:255',
        'phone'              => 'required|digits:10',
        'age'                => 'required|digits:2',
        'aadhar_number'      => 'nullable|string|size:12|unique:family_booking,aadhar_number',
        'mid'                => 'nullable|string|max:12',
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
    ]);
    
    // Add total_persons field
    $data = $request->all();
    $data['total_persons'] = ($request->no_of_people ?? 0) + 1;

    // Create booking entry first
    $booking = FamilyBooking::create($data);

    // ➕ Generate custom booking ID
    $customBookingId = 'F-' . (100 + $booking->id);

    // ➕ Save it into database
    $booking->update(['booking_id' => $customBookingId]);

    // Save family members if applicable
    if ($request->family_coming == "1" && $request->has('family_members')) {
        foreach ($request->family_members as $member) {
            $booking->familyMembers()->create([
                'name'          => $member['name'],
                'father_name'   => $member['father_name'],
                'mobile'        => $member['mobile'],
                'age'           => $member['age'],
                'gender'        => $member['gender'] ?? null,
                'aadhar_number' => $member['aadhar_number'],
            ]);
        }
    }

    $date = now()->format('d-m-Y');

    // SMS message with custom booking ID
    $message = "JJ, JGR Booking ID: $customBookingId Date $date Your registration has been successfully completed. SABSJS";

    $this->sendSMS($request->phone, $message);

    return response()->json([
        'success' => true,
        'redirect' => route('family.thankyou', ['id' => $booking->id]),
    ]);
}



private function sendSms($number, $message, $dlttempid = 1007932841660700100)
{
    // Optional PEID and other hardcoded values
    $user     = "JainSangh";
    $password = "Jain@12";
    $senderid = "ABSJHO";
    $channel  = "trans";
    $dcs      = "0";
    $flashsms = "0";
    $route    = "4";
    $peid     = "1001071123690830532";

    // Build parameter array
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
        'DLTTemplateId' => $dlttempid, // optional or null
    ];

    $query = http_build_query($params);
    $url = "http://www.bulksms.saakshisoftware.in/api/mt/SendSMS?" . $query; 
    // Initialize CURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}




    /**
     * ✅ Show edit form (edit.blade.php)
     */
    public function edit($id)
    {
        $booking = FamilyBooking::findOrFail($id);
        return view('family-booking.edit', compact('booking'));
    }

    /**
     * ✅ Update booking
     */
public function update(Request $request, $id)
{
    $request->validate([
        'name'              => 'required|string|max:255',
        'father_name'       => 'required|string|max:255',
        'phone'             => 'required|digits:10',
        'aadhar_number'     => 'required|string|size:12|unique:family_booking,aadhar_number,' . $id,
        'travel_type'       => 'required|string|max:255',
        'check_in_date'     => 'required|date',
        'check_in_time'     => 'required',
        'check_out_date'    => 'required|date|after_or_equal:check_in_date',
        'check_out_time'    => 'required',
        'family_coming'     => 'required|in:0,1',
        // family fields are optional
        'no_of_people'      => 'nullable|integer|min:0|max:10',
        'total_persons'     => 'nullable|integer|min:1|max:11',
        'remark'            => 'nullable|string|max:1000',
    ]);

    $data = $request->all();

    // 👇 Set values when Family Coming = NO
    if ($request->input('family_coming') == '0') {
        $data['no_of_people'] = null;
        $data['no_of_children'] = null;
        $data['total_male'] = null;
        $data['total_female'] = null;
        $data['total_persons'] = 1; // Only head
    }

    $booking = FamilyBooking::findOrFail($id);
    $booking->update($data);

    return redirect()->route('family-booking.index')->with('success', 'Booking Updated Successfully!');
}


    /**
     * ✅ Delete booking
     */
    public function destroy($id)
{
    // Delete all family members associated with this booking
    \App\Models\FamilyMember::where('family_id', $id)->delete();

    // Delete the family booking
    \App\Models\FamilyBooking::findOrFail($id)->delete();

    return redirect()->route('family-booking.index')->with('success', 'Booking and associated members deleted successfully!');
}


    public function checkout($id)
{
    // 1. Delete booked room entries for this booking
    BookedRoom::where('booking_id', $id)
              ->where('booking_type', 'family')
              ->delete();

    // 2. Update status of booking
    FamilyBooking::where('id', $id)->update(['status' => 'checkout']);

    // 3. Also update family members status
    \App\Models\FamilyMember::where('family_id', $id)->update(['status' => 'checkout']);

    return redirect()->back()->with('success', 'Check-out successful. Room removed and status updated.');
}


public function exportFamilyWithMembers()
{
    return Excel::download(new FamilyMemberExport, 'family-members-full.xlsx', ExcelFormat::XLSX);
}



}
