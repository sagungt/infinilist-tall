@extends('layouts.app')

@section('content')
    <div class="flex flex-col justify-center py-12 bg-gray-50 sm:px-6 lg:px-8">
        <div class="absolute top-0 right-0 mt-4 mr-4">
            @if (Route::has('login'))
                <div class="space-x-4">
                    @auth
                        <a
                            href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="text-sm text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150"
                        >
                            Log out
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>

        <div class="flex items-center justify-center w-full lg:px-96 px-4">
            <div class="flex flex-col justify-center w-full mt-20">
                <div class="space-y-6">
                    <h1 class="text-4xl font-extrabold">Create New Account</h1>
                    <form action="{{ route('process.register') }}" method="post" class="w-full">
                      	@csrf
						  <div class="mb-6">
							<label for="name" class="block mb-2 text-sm font-medium text-gray-900">Fullname</label>
							<input type="text" id="name" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 " placeholder="Your Name" name="name" required value="{{ old('name') }}">
                            @error('name')
                                <span class="text-xs text-red-600">{{ $message }}</span>
                            @enderror
						  </div>
						  <div class="mb-6">
							<label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username</label>
							<input type="text" id="username" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Username" name="username" required value="{{ old('username') }}">
                            @error('username')
                                <span class="text-xs text-red-600">{{ $message }}</span>
                            @enderror
						  </div>
						  <div class="mb-6">
							<label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
							<input type="email" id="email" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="your@email.com" name="email" required value="{{ old('email') }}">
                            @error('email')
                                <span class="text-xs text-red-600">{{ $message }}</span>
                            @enderror
						  </div>
						  <div class="mb-6">
							<label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
							<input type="password" id="password" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Password" name="password" required>
                            @error('password')
                                <span class="text-xs text-red-600">{{ $message }}</span>
                            @enderror
						  </div>
						  <div class="mb-6">
							<label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Password Confirmation</label>
							<input type="password" id="password_confirmation" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Password Confirmation" name="password_confirmation" required>
						  </div>
						  <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
