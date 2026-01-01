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

<img src="{{ asset("images/{$course->image_name}") }}" alt='Thumbnail of the course "{{ $course->title }}"'>

<script src="https://cdn.paddle.com/paddle/v2/paddle.js"></script>
<script type="text/javascript">
    Paddle.Environment.set("sandbox");
    Paddle.Initialize({
        token: @json(config('services.paddle.client_token')),
    });

    function openCheckout() {
        Paddle.Checkout.open({
            items: [{
                priceId: @json($course->paddle_price_id),
                quantity: 1
            }],
        });
    }
</script>
<a href="#" onclick="openCheckout()">Buy Now!</a>