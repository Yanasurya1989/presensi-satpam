<?php

namespace App\Http\Controllers;

use App\Models\Rekap;
use App\Models\User;
use Illuminate\Http\Request;

class Rekapcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rekap = User::all();
        return view('layout.admin.rekap_admin', compact('rekap'));
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
    public function show(Rekap $rekap)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rekap $rekap)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rekap $rekap)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rekap $rekap)
    {
        //
    }
}
