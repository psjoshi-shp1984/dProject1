<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;


class NewsController extends Controller
{
    public function index()
    {
       
        $news = News::orderBy('order_no')->paginate(10);
        return view('news.index', compact('news'));
    }

    public function create()
    {
        return view('news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'    => 'required|max:255',
            'content'  => 'nullable|string',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status'   => 'required|in:active,inactive',
            'order_no' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('news', 'public');
        }

        News::create($validated);

        return redirect()->route('admin.news.index')->with('success', 'News Added!');
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);
        return view('news.edit', compact('news'));
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);
        
        $validated = $request->validate([
            'title'    => 'required|max:255',
            'content'  => 'nullable|string',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'   => 'required|in:active,inactive',
            'order_no' => 'nullable|integer',
        ]);
      
       if ($request->hasFile('image')) {
            $image = $request->file('image');
            $uniqueName = uniqid('news_') . '.' . $image->getClientOriginalExtension(); 
            $validated['image'] = $image->storeAs('uploads/news', $uniqueName, 'public');
           // $data['image'] = $request->file('image')->store('uploads/staticpages', 'public');
        }


        $news->update($validated);

        return redirect()->route('admin.news.index')->with('success', 'News Updated!');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        
        if ($news->image) {
            \Storage::disk('public')->delete($news->image);
        }
        $news->delete();
        
        return redirect()->route('admin.news.index')->with('success', 'News Deleted!');
    }
}

