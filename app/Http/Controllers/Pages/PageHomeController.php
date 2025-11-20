<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
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

        return view('pages.home', compact('courses'));
    }
}
