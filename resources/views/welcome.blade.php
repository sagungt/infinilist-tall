@extends('layouts.app')

@section('content')
    <div class="flex flex-col min-h-screen pt-12 bg-gray-50 sm:px-6 lg:px-8">
        <h1 class="text-6xl font-extrabold italic pt-20 text-center">Every content is special</h1>
        <div class="flex flex-col md:flex-row lg:flex-row justify-center w-full pt-20 gap-x-10">
            <div class="w-full md:w-[15%] lg:w-[15%] flex flex-col items-end justify-start mt-10 static md:sticky lg:sticky md:top-10 lg:top-10">
                <div class="w-full justify-center flex flex-row md:flex-col lg:flex-col gap-5 static md:sticky lg:sticky md:top-14 lg:top-14 py-5">
                    <a href="#">
                        <h2 class="text-2xl font-bold text-center md:text-right lg:text-right">Popular</h2>
                    </a>
                    <a href="#">
                        <h2 class="text-2xl font-bold text-center md:text-right lg:text-right">Latest</h2>
                    </a>
                </div>
            </div>
            <div class="flex flex-col justify-around w-full flex-1 px-2">
                <div class="space-y-4 mb-10">
                    @auth
                        <div class="flex flex-row gap-x-4">
                            <a href="{{ route('post.add') }}" class="px-4 py-2 rounded-lg hover:bg-slate-800 bg-slate-900 transition-all text-white font-xl font-bold">
                                + Create New Post
                            </a>
                            <a href="{{ route('series.add') }}" class="px-4 py-2 rounded-lg hover:bg-slate-800 bg-slate-900 transition-all text-white font-xl font-bold">
                                + Create New Series
                            </a>
                        </div>
                    @endauth
                    <livewire:cards.list-post>
                </div>
            </div>
        </div>
    </div>
@endsection
