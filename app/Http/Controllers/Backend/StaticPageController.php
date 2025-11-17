<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StaticPage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class StaticPageController extends Controller
{
    public function index()
    {
        $pages = StaticPage::latest()->paginate(10);
        return view('static_pages.index', compact('pages'));
    }

    public function create()
    {
        return view('static_pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'page_name' => 'required|string|max:255',
            'page_slug' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'page_descriptions' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['page_slug'] = $request->page_slug ?: Str::slug($request->page_name);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $uniqueName = uniqid('slider_') . '.' . $image->getClientOriginalExtension(); 
            $data['image'] = $image->storeAs('uploads/sliders', $uniqueName, 'public');
           // $data['image'] = $request->file('image')->store('uploads/staticpages', 'public');
        }

        StaticPage::create($data);

        return redirect()->route('admin.static_pages.index')->with('success', 'Page created successfully!');
    }
    public function checkSlug(Request $request)
    {
        $page_slug = $request->input('page_slug');
        $id = $request->input('id');
        if (!empty($id)) {
            // Check if another record (not this one) has the same sort_order
           $exists = \App\Models\StaticPage::where('page_slug', $request->page_slug)->where('id', '!=', $id)->exists();
        } else {
            // If creating a new record, check all records
            $exists = \App\Models\StaticPage::where('page_slug', $request->page_slug)->exists();
        }

       
        return response()->json(['valid' => !$exists]);
    }
    public function edit($id)
    {
        $page = StaticPage::findOrFail($id);
        return view('static_pages.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $page = StaticPage::findOrFail($id);

        $request->validate([
            'page_name' => 'required|string|max:255',
            'page_slug' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'page_descriptions' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['page_slug'] = $request->page_slug ?: Str::slug($request->page_name);

        if ($request->hasFile('image')) {
            if ($page->image) {
                Storage::disk('public')->delete($page->image);
            }
            $image = $request->file('image');
            $uniqueName = uniqid('slider_') . '.' . $image->getClientOriginalExtension(); 
            $data['image'] = $image->storeAs('uploads/sliders', $uniqueName, 'public');
           // $data['image'] = $request->file('image')->store('uploads/staticpages', 'public');
        }

        $page->update($data);

        return redirect()->route('admin.static_pages.index')->with('success', 'Page updated successfully!');
    }

    public function destroy($id)
    {
        $page = StaticPage::findOrFail($id);
        $page->delete();
        return redirect()->route('admin.static_pages.index')->with('success', 'Page deleted successfully!');
    }
}
