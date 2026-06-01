<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller
{
   public function create()
{
    $allNews = \App\Models\News::latest()->paginate(3); 
    return view('admin.news.create', compact('allNews'));
}
public function toggle($id)
{
    $news = \App\Models\News::findOrFail($id);
    $news->is_active = !$news->is_active;
    $news->save();

    return redirect()->back()->with('success', 'स्थिति को सफलतापूर्वक बदला गया!');
}


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['title', 'content']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $filename = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/news'), $filename);
            $data['image'] = 'uploads/news/' . $filename;
        }

        News::create($data);

        return redirect()->route('admin.news.create')->with('success', 'समाचार सफलतापूर्वक जोड़ा गया!');
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);
        $allNews = News::latest()->paginate(3);
        return view('admin.news.create', compact('news', 'allNews'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $news = News::findOrFail($id);
        $news->title = $request->title;
        $news->content = $request->content;
        $news->is_active = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($news->image && file_exists(public_path($news->image))) {
                unlink(public_path($news->image));
            }
            $filename = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/news'), $filename);
            $news->image = 'uploads/news/' . $filename;
        }

        $news->save();

        return redirect()->route('admin.news.create')->with('success', 'समाचार सफलतापूर्वक अपडेट किया गया!');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);

        // Delete image file if exists
        if ($news->image && file_exists(public_path($news->image))) {
            unlink(public_path($news->image));
        }

        $news->delete();

        return redirect()->route('admin.news.create')->with('success', 'समाचार सफलतापूर्वक हटा दिया गया!');
    }
}

