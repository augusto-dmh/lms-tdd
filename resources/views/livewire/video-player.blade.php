<div>
    <iframe src="https://player.vimeo.com/video/{{ $video->vimeo_id }}" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    <h3>{{ $video->title }}</h3>
    <p>{{ $video->description }} ({{ $video->getReadableDuration() }})</p>

    <ul>
        @foreach($courseVideos as $courseVideo)
            <li><a href="{{ route('page.course-videos', $courseVideo) }}">{{ $courseVideo->title }}</a></li>
        @endforeach
    </ul>
</div>
