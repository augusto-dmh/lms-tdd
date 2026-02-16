<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    public readonly string $title;

    public function __construct(
        public ?string $pageTitle = null,
        public ?string $contentTitle = null,
    ) {
        $this->title = match(true) {
            (bool) $contentTitle => "$contentTitle - " . config('app.name'),
            (bool) $pageTitle    => config('app.name') . " - $pageTitle",
            default              => config('app.name'),
        };
    }
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.guest');
    }
}
