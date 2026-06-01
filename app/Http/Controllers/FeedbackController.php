<?php

// app/Http/Controllers/FeedbackController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\FeedbackConfirmation;
use App\Mail\FeedbackNotification;

class FeedbackController extends Controller
{
    public function showForm()
    {
        return view('feedback');
    }

  public function submitForm(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:100',
        'email' => 'required|email',
        'phone'   => 'required|digits:10',
        'message' => 'required|string',
    ]);

     $feedback = Feedback::create($request->all());

     // Send confirmation email to user
     try {
         Mail::to($feedback->email)->send(new FeedbackConfirmation($feedback));
     } catch (\Exception $e) {
         // Log error but don't fail the request
         Log::error('Failed to send feedback confirmation email: ' . $e->getMessage());
     }

     // Send notification email to admin
     try {
         $adminEmail = env('ADMIN_EMAIL', 'admin@chaturmas.com');
         Mail::to($adminEmail)->send(new FeedbackNotification($feedback));
     } catch (\Exception $e) {
         // Log error but don't fail the request
         Log::error('Failed to send feedback notification email: ' . $e->getMessage());
     }

    return back()->with('success', 'आपका फीडबैक सफलतापूर्वक सेव हो गया है! पुष्टिकरण ईमेल आपके ईमेल पर भेजा गया है।');
}

public function index()
{
    $feedbacks = Feedback::latest()->paginate(10); // Paginated
    return view('admin.feedback.index', compact('feedbacks'));
}

}

