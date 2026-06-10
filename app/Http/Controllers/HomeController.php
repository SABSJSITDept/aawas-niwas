<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\News;

class HomeController extends Controller
{
    public function index()
    {
        $news = News::where('is_active', true)->latest()->take(5)->get();
        $medicalHelplines = \App\Models\Helpline::where('status', true)->where('is_home_medical', true)->get();
        return view('home', compact('news', 'medicalHelplines'));
    }
}
