<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class FormulirController extends Controller
{
    public function index(): View
    {
        return view('formulir-pendaftaran.student-formulir-pendaftaran');
    }
}
