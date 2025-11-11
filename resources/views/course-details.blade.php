<h2>{{ $course->title }}</h2>

<p>{{ $course->description }}</p>
<p>{{ $course->tagline }}</p>

<p>{{ count($course->videos) }} videos</p>

<ul>
    @foreach($course->learnings as $learning)
        <li>{{ $learning }}</li>
    @endforeach
    <li></li>
</ul>

<img src="{{ asset("images/$course->image_name") }}" alt='Thumbnail of the course "{{ $course->title }}"'>
