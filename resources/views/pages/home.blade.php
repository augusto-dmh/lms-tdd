<x-guest-layout page-title="Home">
    @push('meta')
        <meta name="description" content="{{ config('app.name') }} is the leading learning platform for Laravel developers.">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ route('pages.home') }}">
        <meta property="og:title" content="{{ config('app.name') }}">
        <meta property="og:description" content="{{ config('app.name') }} is the leading learning platform for Laravel developers.">
        <meta property="og:image" content="{{ asset('images/social.png') }}">

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
    @endpush

    @guest
        <a href="{{ route('login') }}">Login</a>
    @else
        <form method="post" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Log out</button>
        </form>
    @endguest

    <ul>
        @foreach($courses as $course)
            <a href="{{ route('pages.course-details', $course) }}">
                <li>{{ $course->title }}</li>
            </a>
            <li>{{ $course->description }}</li>
        @endforeach
    </ul>
</x-guest-layout>