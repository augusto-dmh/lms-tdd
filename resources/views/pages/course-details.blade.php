<x-guest-layout :content-title="$course->title">
    @push('meta')
        <meta name="description" content="{{ $course->description }}">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ route('pages.course-details', $course) }}">
        <meta property="og:title" content="{{ $course->title }}">
        <meta property="og:description" content="{{ $course->description }}">
        <meta property="og:image" content="{{ asset("images/{$course->image_name}") }}">

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
    @endpush

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
            const authenticatedUserEmail = @json(auth()->user()?->email);

            const checkoutOptions = {
                items: [
                    {
                        priceId: @json($course->paddle_price_id),
                        quantity: 1
                    }
                ],
                customData: {
                    user: {
                        email: authenticatedUserEmail
                    }
                }
            };

            if (authenticatedUserEmail) {
                checkoutOptions.customer = {
                    email: authenticatedUserEmail
                };
            }

            Paddle.Checkout.open(checkoutOptions);
        }
    </script>
    <a href="#" onclick="openCheckout()">Buy Now!</a>
</x-guest-layout>