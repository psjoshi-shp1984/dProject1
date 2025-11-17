<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    // List all sliders
    public function index()
    {
        $sliders = \App\Models\Slider::orderBy('id', 'desc')->paginate(10);
        return view('sliders.index', compact('sliders'));
    }

    // Show create form
    public function create()
    {
        return view('sliders.create');
    }

    // Store new slider
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'required|image',
            'link' => 'nullable|url',
            'type' => 'nullable|string|max:50',
            'status' => 'required|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
              $data['image'] = $request->file('image')->store('uploads/sliders', 'public');
                //$data['image'] = $request->file('image')->store('sliders', 'public');
        }

        Slider::create($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider created successfully.');
    }
    public function checkSortOrder(Request $request)
    {
         $sortOrder = $request->input('sort_order');
        $id = $request->input('id');
        if (!empty($id)) {
            // Check if another record (not this one) has the same sort_order
            $exists = \App\Models\Slider::where('sort_order', $sortOrder)
                        ->where('id', '!=', $id)
                        ->exists();
        } else {
            // If creating a new record, check all records
            $exists = \App\Models\Slider::where('sort_order', $sortOrder)->exists();
        }
       
        return response()->json(['valid' => !$exists]);
    }


    // Show edit form
    public function edit(Slider $slider)
    {
        return view('sliders.edit', compact('slider'));
    }

    // Update existing slider
    public function update(Request $request, Slider $slider)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image',
            'link' => 'nullable|url',
            'type' => 'nullable|string|max:50',
            'status' => 'required|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($slider->image) {
                Storage::disk('public')->delete($slider->image);
            }
           
             $data['image'] = $request->file('image')->store('uploads/sliders', 'public');
        }

        $slider->update($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider updated successfully.');
    }

    // Soft delete slider
    public function destroy(Slider $slider)
    {
        $slider->delete();
        return redirect()->route('admin.sliders.index')->with('success', 'Slider deleted successfully.');
    }

    // Restore soft deleted slider
    public function restore($id)
    {
        $slider = Slider::withTrashed()->findOrFail($id);
        $slider->restore();
        return redirect()->route('admin.sliders.index')->with('success', 'Slider restored successfully.');
    }

    // Permanently delete slider
    // public function forceDelete($id)
    // {
    //     $slider = Slider::withTrashed()->findOrFail($id);
    //     if ($slider->image) {
    //         Storage::disk('public')->delete($slider->image);
    //     }
    //     $slider->forceDelete();
    //     return redirect()->route('admin.sliders.index')->with('success', 'Slider permanently deleted.');
    // }
}
