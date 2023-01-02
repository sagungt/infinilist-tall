<div class="pt-20 min-h-screen">
    {{-- @dd($shorteners) --}}
    <div class="mx-auto gap-y-5 flex flex-col w-full px-2 md:w-[80%] lg:w-2/3 justify-center mb-10">
        <h1 class="font-extrabold text-5xl">My Shortener</h1>
        @if (session('success'))
            <div class="bg-teal-100 rounded-md p-4">
                <span class="text-teal-500 text-sm">{{ session('success') }}</span>
            </div>
        @endif
        <a href="{{ route('shortener.add') }}" class="px-4 py-2 rounded-lg bg-slate-800 text-white font-xl font-bold w-fit">
            + Create New Shortener
        </a>
        <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="py-3 px-6">
                            Alias
                        </th>
                        <th scope="col" class="py-3 px-6">
                            Target
                        </th>
                        <th scope="col" class="py-3 px-6">
                            Visits
                        </th>
                        <th scope="col" class="py-3 px-6">
                            <span class="sr-only">Action</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($shorteners as $shortener)
                        <tr class="bg-white border-bhover:bg-gray-50">
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                <a href="http://localhost:8000/s/{{ $shortener['alias'] }}">{{ $shortener['alias'] }}</a>
                            </th>
                            <td class="py-4 px-6">
                                <a href="{{ $shortener['target'] }}">{{ $shortener['target'] }}</a>
                            </td>
                            <td class="py-4 px-6 text-center">
                                0
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('shortener.edit', ['id' => $shortener['id']]) }}" class="font-medium text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('shortener.destroy', ['id' => $shortener['id']]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="font-medium text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-white border-bhover:bg-gray-50">
                            <td class="py-4 px-6 text-center" colspan="4">No Shortener yet :(</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
