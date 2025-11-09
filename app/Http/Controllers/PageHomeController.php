<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class PageHomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $courses = Course::query()
            ->whereNotNull('released_at')
            ->orderBy('released_at', 'desc')
            ->get();

        return view('home', compact('courses'));
    }
}
