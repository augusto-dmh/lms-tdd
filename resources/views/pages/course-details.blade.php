<h2>{{ $course->title }}</h2>

<p>{{ $course->description }}</p>
<p>{{ $course->tagline }}</p>

<p>{{ $course->videos_count }} videos</p>

<ul>
    @foreach($course->learnings as $learning)
        <li>{{ $learning }}</li>
    @endforeach
    <li></li>
</ul>

<img src="{{ asset("images/$course->image_name") }}" alt='Thumbnail of the course "{{ $course->title }}"'>

<script src="https://cdn.paddle.com/paddle/paddle.js"></script>
<script type="text/javascript">
	Paddle.Setup({ vendor: {{ config('services.paddle.vendor_id') }} });
</script>
<a href="#!" class="paddle_button" data-product="{{ $course->paddle_product_id }}">Buy Now!</a>