@extends('layouts.main')

@section('register')
    <div class="mx-auto max-w-2xl w-3/4 sm:mt-4 mt-8">
        <div id="alert" class="invisible mb-4" role="alert">
            <div class="bg-red-500 text-white font-bold rounded-t px-4 py-2">
                Ошибка
            </div>
            <div id="alerts" class="border border-t-0 border-red-400 rounded-b bg-red-100 px-4 py-3 text-red-700">

            </div>
        </div>
        <form id="login-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col">
            <div class="mb-4">
                <label class="block text-grey-darker text-sm font-bold mb-2" for="username">
                    Email
                </label>
                <input name="email" id="email"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker" type="text"
                       placeholder="Email">
            </div>
            <div class="mb-4">
                <label class="block text-grey-darker text-sm font-bold mb-2" for="password">
                    Password
                </label>
                <input name="password" id="password" autocomplete="on"
                       class="shadow appearance-none border border-red rounded w-full py-2 px-3 text-grey-darker mb-3"
                       type="password" placeholder="******************">
            </div>
            <div class="mb-6">
                <label class="block text-grey-darker text-sm font-bold mb-2" for="password">
                    Password confirmation
                </label>
                <input name="password_confirmation" id="password_confirmation" autocomplete="on"
                       class="shadow appearance-none border border-red rounded w-full py-2 px-3 text-grey-darker mb-3"
                       type="password" placeholder="******************">
            </div>
            <div class="flex items-center justify-between">
                <button id="button" class="bg-blue-500 hover:bg-blue-dark text-white font-bold py-2 px-4 rounded"
                        type="button">
                    Sign Up
                </button>
            </div>
        </form>
    </div>

    <script type="module" src="{{asset('js/register.js')}}"></script>
@endsection
