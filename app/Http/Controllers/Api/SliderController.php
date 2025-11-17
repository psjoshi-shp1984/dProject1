<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      
        try {
            $sliders = Slider::all();
            if ($sliders->isNotEmpty()) {
                return response()->json([
                    'status' => true,
                    'data' => $sliders
                ], 200);
            }else
            {
                return response()->json([
                    'status' => false,
                    'message' => 'No records found.',
                    'data' => []
                ], 404);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch sliders.',
                'error' =>"SQLSTATE[42S02]: Base table or view not found: 1146 Table 'test.sliders' doesn't exist", // Optional: remove in production for security
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
