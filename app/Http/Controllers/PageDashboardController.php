<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $purchasedCourses = Auth::user()->courses;

        return view('dashboard', compact('purchasedCourses'));
    }
}
