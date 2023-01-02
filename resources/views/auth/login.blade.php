@extends('layouts.app')

@section('content')
    <div class="flex flex-col justify-center min-h-screen py-12 bg-gray-50 sm:px-6 lg:px-8">
		
        <div class="flex items-center justify-center md:w-1/2 lg:w-1/2 w-full px-5">
            <div class="flex flex-col justify-end md:w-full lg:w-1/2 w-full">
								<h1 class="text-4xl font-extrabold mb-10">Login</h1>
                <div class="space-y-6">
									@error('error')
										<div class="bg-red-100 rounded-md p-4">
											<span class="text-red-600 text-sm">{{ $message }}</span>
										</div>
									@enderror
									@if (session('success'))
										<div class="bg-teal-100 rounded-md p-4">
											<span class="text-teal-500 text-sm">{{ session('success') }}</span>
										</div>
									@endif
                    <form action="{{ route('process.login') }}" method="post" class="w-full">
                      	@csrf
											<div class="mb-6">
											<label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
											<input type="email" id="email" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="your@email.com" name="email" required value="{{ old('email') }}">
											</div>
											<div class="mb-6">
											<label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
											<input type="password" id="password" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" name="password" placeholder="Password" required>
											</div>
											<button type="submit" class="text-white bg-slate-900 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
