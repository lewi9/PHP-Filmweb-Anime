<?php $articles = \App\Models\Article::all(); ?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("You're logged in!") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <b>{{ __("News") }}</b>
                </div>
            </div>
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                @foreach($articles as $article)
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <b>{{ __($article->title) }}</b>
                        <img src="{{URL::asset('/images/'. $article->photo)}}" alt="Anime Pic" height="200" width="200">
                        <div>
                            {{__($article->text)}}
                            <div>Likes: {{__($article->likes)}}
                                Dislikes: {{__($article->dislikes)}}</div>
                            @if (Auth::user())
                            <x-primary-button class="ml-3">
                                {{ __('Like') }}
                            </x-primary-button>
                            <x-primary-button class="ml-3">
                                {{ __('Dislike') }}
                            </x-primary-button>
                            @endif
                        </div>
                    </div>
                @endforeach
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
