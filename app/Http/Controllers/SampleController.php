<?php

namespace App\Http\Controllers;

use App\Models\Sample;
use App\Services\SampleService as Service;
use App\Validation\SampleValidation as Validation;
use Illuminate\Http\Request;

class SampleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query_options = Validation::index($request);
        $samples = Service::getSamples($query_options, $user);
        return response(['samples' => $samples]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $sample_body = Validation::store($request);
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
