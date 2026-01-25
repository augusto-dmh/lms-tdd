<?php

namespace App\Console\Commands;

use App\Models\Course;
use Illuminate\Console\Command;
use Twitter;

class TweetAboutCourseReleaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tweet-about-course-release-command {courseId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tweet about a course release.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $course = Course::findOrFail($this->argument('courseId'));

        Twitter::tweet("I just released $course->title 🎉 Check it out on " . route('pages.course-details', $course));
    }
}
