<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bungalow;

class ChambreController extends Controller
{
    
    public function index()
    {
        return view('chambres.index');
    }
    
    public function create()
    {
        $bungalowsMer = Bungalow::where('typeBungalow', 'mer')->get();
        $bungalowsJardin = Bungalow::where('typeBungalow', 'jardin')->get();
        return view('chambres.create', compact('bungalowsMer', 'bungalowsJardin'));


    }
}
