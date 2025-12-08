<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the FAQs.
     */
    public function index()
    {
        $faqs = Faq::orderBy('order_no', 'asc')->latest()->paginate(10);;
        return view('faqs.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new FAQ.
     */
    public function create()
    {
        return view('faqs.create');
    }

    /**
     * Store a newly created FAQ in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'page_name' => 'nullable|string|max:255',
            'question' => 'required|string',
            'answer' => 'required|string',
            'order_no' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        Faq::create($validated);
        return redirect()->route('admin.faq.index')->with('success', 'FAQ created successfully!');
    }

    /**
     * Show the form for editing the specified FAQ.
     */
    public function edit($id)
    {
        $faq = Faq::findOrFail($id);
      // echo "<pre>";print_r($faq);die;
        return view('faqs.edit', compact('faq'));
    }

    /**
     * Update the specified FAQ.
     */
    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'page_name' => 'nullable|string|max:255',
            'question' => 'required|string',
            'answer' => 'required|string',
            'order_no' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);
     //   echo "<pre>validated=";print_r($validated);die;
        $faq->update($validated);
        return redirect()->route('admin.faq.index')->with('success', 'FAQ updated successfully!');
    }

    /**
     * Remove the specified FAQ.
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faq.index')->with('success', 'FAQ deleted successfully!');
    }
}
