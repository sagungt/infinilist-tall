@extends('layouts.app')

@section('content')
<div class="container mx-auto min-h-screen flex flex-col justify-center gap-5">

    <div class="w-full md:w-[80%] lg:w-1/2 bg-white border border-gray-200 rounded-lg shadow-md mx-auto">
		<div class="flex flex-col items-center py-5">
			@if ($user->profile_url == null)
				<div class="w-24 h-24 bg-slate-900 rounded-full"></div>
			@else
				<img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{ $user->profile_url->path }}" alt="{{ $user->name }}"/>
			@endif
			<h5 class="mb-1 text-2xl my-4 font-medium text-gray-900">{{ $user->name }}</h5>
			<span class="text-sm text-gray-500">{{ $user->username }}</span>
			<span class="text-md text-gray-700">{{ $user->email }}</span>
			<div class="flex no-tailwindcss-base p-10">
				<p>
					{!! $user->bio !!}
				</p>
			</div>
		</div>
    </div>
</div>
@endsection
