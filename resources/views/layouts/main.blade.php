<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/build.css')}}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<script type="module" src=""></script>

<nav class="bg-white border-gray-200 dark:bg-gray-900">
    <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl p-4">
        <a href="{{url('')}}" class="flex items-center">
            <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Articles</span>
        </a>
        <button id="auth" class="text-sm  text-blue-600 dark:text-blue-500 hover:underline"></button>
    </div>
    </div>
</nav>
<script type="module" src="{{asset('axios/dist/axios.min.js')}}"></script>

@yield('register')
@yield('login')
@yield('articles')

<script type="module" src="{{asset('js/app.js')}}">
</script>
</body>
</html>
