<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExcelController extends Controller
{
    public function excel()
    {
        echo route('excel');
    }
}
