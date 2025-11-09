<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class PageHomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $courses = Course::query()
            ->released()
            ->orderBy('released_at', 'desc')
            ->get();

        return view('home', compact('courses'));
    }
}
