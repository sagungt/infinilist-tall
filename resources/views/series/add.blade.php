@extends('layouts.app')

@section('content')
	<div class="flex flex-col min-h-screen py-12 bg-gray-50">
		<div class="container mx-auto">
      <h1 class="text-4xl font-extrabold my-10">Create New Series</h1>
      <div class="flex flex-col">
        <form action="" method="post" class="w-full" x-data="postFields">
          @csrf

          <div class="mb-6">
            <label for="title" class="block mb-2 text-sm font-medium text-gray-900">Title</label>
            <input type="text" id="title" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-white" placeholder="Post title" name="title" required>
          </div>

          <div class="mb-6">
            <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
            <textarea id="description" rows="4" class="block p-2.5 w-full max-w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 no-tailwindcss-base" placeholder="Series description here..." name="description"></textarea>
          </div>

          <div id="posts">

          </div>

          <div class="mb-6">
            <button x-on:click="add()" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">+ Add</button>
          </div>

          <div class="flex flex-col items-start mb-6" x-data="{ active: false, url: '' }">
            <div class="flex">
              <div class="flex items-center h-5">
                <input id="shorten" type="checkbox" x-model="active" value="" class="w-4 h-4 bg-gray-50 rounded border border-gray-300 focus:ring-3 focus:ring-blue-300" x-on:click="active = !active" required name="shorten">
              </div>
              <label for="shorten" class="ml-2 text-sm font-medium text-gray-900">Shorten</label>
            </div>
            <div class="w-full mt-5" x-show="active" x-cloak>
              <label for="url" class="block mb-2 text-sm font-medium text-gray-900">Url</label>
              <div class="relative w-full">
                <input x-model="url" type="url" id="url" class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-white rounded-r-lg border-l-gray-100 border-l-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-tl-lg rounded-bl-lg" placeholder="Custom URL" required>
                <button type="button" class="absolute top-0 right-0 p-2.5 text-sm font-medium text-white bg-blue-700 rounded-r-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300" x-on:click="url = rand(5)">Random</button>
              </div>
              <div class="mt-1 text-sm text-gray-500" id="preview_url" x-text="'{{ url('/') }}/s/' + url"></div>
            </div>
          </div>

          <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Publish</button>
        </form>
      </div>
    </div>
  </div>
@endsection

<script>
  function randomUrl() {
    const url = rand(5);
    document.querySelector('#url').value = url;
  }
  function rand(length) {
    let result = '';
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
    const charactersLength = characters.length;
    for (let i = 0; i < length; i++) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
  }
  document.addEventListener('alpine:init', () => {
    Alpine.data('postFields', () => ({
      counter: 0,

      add() {
        this.counter += 1;
        const postsElement = document.querySelector('#posts');
        const element = `
        <div class="mb-6" x-data="{ el: $el, index: ${this.counter} }">
          <label for="post-${this.counter}" class="block mb-2 text-sm font-medium text-gray-900">Post ${this.counter}</label>
          <div class="flex flex-row gap-x-2">
            <select id="post-${this.counter}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" name="post[]">
              <option selected>Choose a post</option>
              <option value="US">United States</option>
              <option value="CA">Canada</option>
              <option value="FR">France</option>
              <option value="DE">Germany</option>
            </select>
            <button :disabled="index !== counter" :class="index !== counter ? 'opacity-60' : ''" x-on:click="remove(el)" type="button" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-auto px-5 py-2.5 text-center"> - </button>
          </div>
        </div>
        `;
        const temp = document.createElement('div');
        postsElement.innerHTML += element;
      },
      remove(el) {
        this.counter -= 1;
        el.remove();
      },
    }));
  });
</script>
