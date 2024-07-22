<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Base_Calls;

class LiveCallsController extends Controller
{
    public function index()
    {
        $live = Base_Calls::all();
        return response()->json([
            'status' => true,
            'data' => $live,
            'message' => "Live Calls Successfully Get.",
        ]);
    }
}
