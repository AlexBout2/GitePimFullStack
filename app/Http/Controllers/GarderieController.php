<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GarderieController extends Controller
{
    public function index()
{
    return view('garderie.index');
}
}
