@extends('layouts.main')

@section('articles')
    <ul id="articles" class="list-disc mx-auto max-w-2xl w-3/4">
    </ul>

    <script type="module" src="{{asset('js/articles.js')}}"></script>
@endsection
