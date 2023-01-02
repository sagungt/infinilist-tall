<nav class="bg-white border-gray-200 px-2 sm:px-4 py-1.5 fixed w-full z-[9999]">
    <div class="container flex flex-wrap items-center justify-between mx-auto">
      <a href="http://localhost:8000" class="flex items-center p-2 rounded-md bg-slate-800">
          <span class="self-center text-sm font-semibold whitespace-nowrap text-white">InfiniList</span>
      </a>
      <div class="flex md:order-2">
          @guest
            <a href="{{ route('login') }}" type="button" class="text-slate-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-3 md:mr-0">Login</a>
            <a href="{{ route('register') }}" type="button" class="text-slate-900 border border-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-3 md:mr-0">Register</a>
          @endguest
          @auth
            <div class="flex items-center md:order-2">
              <button type="button" class="flex mr-3 text-sm bg-gray-800 rounded-full md:mr-0 focus:ring-4 focus:ring-gray-300" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                <span class="sr-only">Open user menu</span>
                @php
                  $profile_url = App\Models\Attachment::query()->where('kind', 'USER')->where('parent_id', auth()->user()->id)->first()
                @endphp
                @if ($profile_url == null)
                  <div class="h-8 w-8 rounded-full bg-slate-900 text-white text-sm flex items-center justify-center">{{ strtoupper(auth()->user()->name[0]) }}</div>
                @else  
                  <img class="w-8 h-8 rounded-full" src="{{ $profile_url->path }}" alt="{{ auth()->user()->name }}">
                @endif
              </button>
              <!-- Dropdown menu -->
              <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow" id="user-dropdown">
                <a href="{{ route('profile.show') }}">
                  <div class="px-4 py-3">
                    <span class="block text-sm text-gray-900">{{ auth()->user()->name }}</span>
                    <span class="block text-sm font-medium text-gray-500 truncate">{{ '@'.auth()->user()->username }}</span>
                  </div>
                </a>
                <ul class="py-1" aria-labelledby="user-menu-button">
                  <li>
                    <a href="{{ route('profile.post.list') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Posts</a>
                  </li>
                  <li>
                    <a href="{{ route('profile.series.list') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Series</a>
                  </li>
                  <li>
                    <a href="{{ route('profile.favorite.list') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Favorites</a>
                  </li>
                  <li>
                    <a href="{{ route('post.add') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Create New Post</a>
                  </li>
                  <li>
                    <a href="{{ route('series.add') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Create New Series</a>
                  </li>
                  <li>
                    <a href="{{ route('shortener.list') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Shortener</a>
                  </li>
                  <li>
                    <form action="{{ route('logout') }}" method="post">
                      @csrf
                      <button type="submit" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full font-bold">Sign out</button>
                    </form>
                  </li>
                </ul>
              </div>
            </div>
          @endauth
          <button data-collapse-toggle="navbar-cta" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200" aria-controls="navbar-cta" aria-expanded="false" id="navbar-button">
            <span class="sr-only">Open main menu</span>
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
          </button>
      </div>
      <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-cta">
        <ul class="flex flex-col p-4 mt-4 border border-gray-100 rounded-lg md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium md:border-0 md:bg-white">
          <li>
            <a href="http://localhost:8000" class="block py-2 pl-3 pr-4 text-white bg-slate-700 rounded md:bg-transparent md:text-slate-900 md:p-0 dark:text-white" aria-current="page">Home</a>
          </li>
          {{-- <li>
            <a href="http://localhost:8000/explore" class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-slate-900 md:p-0">Explore</a>
          </li> --}}
          <li>
            <a href="{{ route('post.list') }}" class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-slate-900 md:p-0">Posts</a>
          </li>
          <li>
            <a href="{{ route('series.list') }}" class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-slate-900 md:p-0">Series</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>