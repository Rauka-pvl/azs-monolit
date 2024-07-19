<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $token = auth()->user()->createToken('auth_token')->plainTextToken;
        return view('report', compact('token'));
    }
}
