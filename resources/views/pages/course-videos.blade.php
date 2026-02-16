<x-app-layout :content-title="$video->title">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Videos') }}
        </h2>
    </x-slot>

    <livewire:video-player :video="$video" />
</x-app-layout>