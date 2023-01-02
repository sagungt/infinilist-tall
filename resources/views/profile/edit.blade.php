@extends('layouts.app')

@section('content')
    <div class="flex flex-col justify-center min-h-screen py-12 bg-gray-50 sm:px-6 lg:px-8">
        <div class="flex items-center justify-center w-full lg:px-96 px-4">
            <div class="flex flex-col justify-center w-full">
                <div class="space-y-6">
                  <h1 class="text-4xl font-bold pt-10">Your Profile</h1>
                    <form action="{{ route('process.register') }}" method="post" class="w-full">
                      	@csrf
                        
                        <div class="mb-6">
                          <div class="flex flex-row w-full gap-x-5 items-center">
                            <div class="w-20 h-20 bg-slate-900 rounded-full"></div>
                            <div class="flex flex-col w-auto flex-1">
                              <label for="photo_url" class="block mb-2 text-sm font-medium text-gray-900">Photo</label>
                              <input type="file" id="photo_url" name="photo_url" class="text-sm text-grey-500 file:mr-5 file:py-2 file:px-6 border border-gray-300 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-slate-700 hover:file:cursor-pointer hover:file:bg-slate-300 hover:file:text-slate-700 w-full bg-white p-1.5 rounded-lg " />
                            </div>
                          </div>
                        </div>

                        <div class="mb-6">
                          <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Fullname</label>
                          <input type="text" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Your Name" name="name" required>
                        </div>

                        <div class="mb-6">
                          <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username</label>
                          <input type="text" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Username" name="username" required>
                        </div>

                        <div class="mb-6">
                          <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                          <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="your@email.com" name="email" required>
                        </div>

                        <div class="mb-6">
                          <label for="bio" class="block mb-2 text-sm font-medium text-gray-900">Bio</label>
                          <textarea id="bio" rows="4" class="block p-2.5 w-full max-w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 no-tailwindcss-base" placeholder="Your post here..." name="bio"></textarea>
                        </div>

                        <div class="mb-6">
                          <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                          <input type="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Password" name="password" required>
                        </div>

                        <div class="mb-6">
                          <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Password Confirmation</label>
                          <input type="password" id="password_confirmation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Password Confirmation" name="password_confirmation" required>
                        </div>

                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
  document.addEventListener('DOMContentLoaded', function() {
    ClassicEditor
      .create(document.querySelector('#bio'))
      .catch(error => {
          console.error(error);
      });
  });
</script>
