<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>{{ $title ?? 'LearnHub' }}</title>@vite(['resources/css/app.css','resources/js/app.js'])</head><body class="bg-slate-50 text-slate-900 antialiased"><header class="bg-white border-b"><nav class="max-w-6xl mx-auto px-5 py-4 flex justify-between"><a class="font-bold text-xl text-indigo-700" href="{{ route('courses.index') }}">LearnHub</a><div class="flex gap-5"><a href="{{ route('courses.index') }}">Courses</a>@auth<a href="{{ route('dashboard') }}">My learning</a><form method="POST" action="{{ route('logout') }}">@csrf<button>Sign out</button></form>@else<a href="{{ route('login') }}">Sign in</a><a href="{{ route('register') }}">Join free</a>@endauth</div></nav></header><main class="max-w-6xl mx-auto px-5 py-10">
@if(session('success'))
<div role="status" class="mb-5 rounded bg-green-100 p-3 text-green-900">{{ session('success') }}</div>
@endif
@if($errors->any())
<div class="mb-5 rounded bg-red-100 p-3 text-red-900">{{ $errors->first() }}</div>
@endif

@yield('content')
</main></body></html>