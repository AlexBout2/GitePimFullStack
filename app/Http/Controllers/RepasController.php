<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RepasController extends Controller
{
    public function index()
{
    return view('repas.index');
}
}
