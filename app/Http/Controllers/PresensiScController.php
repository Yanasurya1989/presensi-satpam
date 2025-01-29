<?php

namespace App\Http\Controllers;

use App\Models\PresensiSc;
use Illuminate\Http\Request;

class PresensiScController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('layout.Presensi.sc');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(PresensiSc $presensiSc)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PresensiSc $presensiSc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PresensiSc $presensiSc)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PresensiSc $presensiSc)
    {
        //
    }
}
