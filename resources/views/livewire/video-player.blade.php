<div>
    <iframe src="https://player.vimeo.com/video/{{ $video->vimeo_id }}" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    <h3>{{ $video->title }}</h3>
    <p>{{ $video->description }} ({{ $video->getReadableDuration() }})</p>
</div>
