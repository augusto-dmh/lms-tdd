<x-dynamic-component :component="$layout">
    <ul>
        @foreach($courses as $course)
            <li>{{ $course->title }}</li>
            <li>{{ $course->description }}</li>
        @endforeach
    </ul>
</x-dynamic-component>
