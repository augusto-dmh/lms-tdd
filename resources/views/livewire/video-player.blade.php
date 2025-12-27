<div>
    <iframe src="https://player.vimeo.com/video/{{ $video->vimeo_id }}" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    <h3>{{ $video->title }}</h3>
    <p>{{ $video->description }} ({{ $video->getReadableDuration() }})</p>
    @if($video->alreadyWatchedByCurrentUser())
        <button wire:click="markVideoAsNotCompleted">Mark as not completed</button>
    @else
        <button wire:click="markVideoAsCompleted">Mark as completed</button>
    @endif

    <ul>
        @foreach($courseVideos as $courseVideo)
            <li>
                @if($this->video->id !== $courseVideo->id)
                    <a href="{{ route('page.course-videos', $courseVideo) }}">{{ $courseVideo->title }}</a>
                @else
                    {{ $courseVideo->title }}
                @endif
            </li>
        @endforeach
    </ul>
</div>
