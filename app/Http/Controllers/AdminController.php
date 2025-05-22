<?php

namespace App\Http\Controllers;

use App\Models\Attendance;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('layout.admin.index');
        return response()->json(Attendance::with('user')->orderBy('created_at', 'desc')->get());
    }
}
