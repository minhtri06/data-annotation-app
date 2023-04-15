<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MeController extends Controller
{
    public function getMyProfile(Request $request)
    {
        $user = auth()->user();
        return response(['data' => $user], 200);
    }
}
