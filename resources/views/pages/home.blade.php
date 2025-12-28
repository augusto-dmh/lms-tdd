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
