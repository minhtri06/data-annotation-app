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
        $sample = Service::createSample($sample_body);
        return response(['sample' => $sample], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $user = auth()->user();
        $query_options = Validation::show($request);
        $response = Service::getSampleById($id, $user, $query_options);
        return response($response);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        ['sample_texts' => $sample_texts] = Validation::update($request);
        $sample = Service::updateSampleById($id, $sample_texts);
        return response(['sample' => $sample]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Service::deleteSampleById($id);
        return response([], 204);
    }

    public function annotateSample(string $id, Request $request)
    {
        $annotation_body =  Validation::annotateSample($request);
        $user = auth()->user();
        $sample = Service::annotateSample($id, $annotation_body, $user);
        return $sample;
    }
}
