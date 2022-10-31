@extends('cms.layouts.index')

@section('content')

<div>
    <p>Wellcome, {{ Auth::user()->name }}</p>
    <div>
        email : {{ Auth::user()->email }}
        <br>
        Time : {{ date(now()) }}
    </div>
</div>

@endsection