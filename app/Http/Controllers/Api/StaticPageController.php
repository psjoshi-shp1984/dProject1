<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StaticPage;

class StaticPageController extends Controller
{
    public function index()
    {
        try {
            $pages = StaticPage::select(
                'id',
                'page_name',
                'page_slug',
                'image',
                'image_name',
                'image_hover_text',
                'page_descriptions',
                'status'
            )->get();

            if ($pages->isNotEmpty()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Static pages fetched successfully.',
                    'data'    => $pages
                ], 200);
            }

            return response()->json([
                'status'  => false,
                'message' => 'No static pages found.',
                'data'    => []
            ], 404);

        } catch (\Exception $e) {
            
            return response()->json([
                'status'  => false,
                'message' => 'Failed to fetch static pages. Please try again later.',
                'error'   => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }
}
