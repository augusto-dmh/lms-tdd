<x-mail::message>
# Thanks for purchasing "{{ $course->title }}"!

If it is your first time just click the button below and reset your password.

<x-mail::button :url="route('login')">
Login
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
