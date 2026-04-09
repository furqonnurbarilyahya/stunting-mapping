<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $regions = Region::all();

        return response()->json([
            'success' => true,
            'message' => 'List data wilayah',
            'data' => $regions
        ]);
    }
}
