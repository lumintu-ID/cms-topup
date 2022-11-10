@extends('cms.layouts.index')

@section('content')


@if ($errors->any())
    <div class="p-3">
        @foreach ($errors->all() as $error)
            {{-- <div class="alert alert-danger" style="color: white">{{ $error }}</div> --}}
            <div class="alert alert-primary alert-dismissible text-white" role="alert">
                <span class="text-sm">{{ $error }}</span>
                <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
        @endforeach
    </div>
@endif

@if (Session::has('message'))
    @if (Session::get('alert-info', 'success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('message') }}
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            {{ Session::get('message') }}
        </div>
    @endif
@endif


<div class="table-responsive">
    <table class="table table-centered table-nowrap mb-0 rounded">
        <thead class="thead-light">
            <tr>
                <th class="border-0 rounded-start">#</th>
                <th class="border-0">email</th>
                <th class="border-0">Game</th>
                <th class="border-0">Payment</th>
                <th class="border-0">status</th>
                <th class="border-0">#</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
</div>

<script>
    function delet(data) {
        $('#id').val(data.id)
        $('#nm-app').html(data.name)
    }
</script>


@endsection