@extends('cms.layouts.index')

@extends('cms.addons.css')

@section('content')

<a href="{{ route('cms.pricepoint.add') }}" class="btn btn-block btn-gray-800 mb-3" >Add</a>

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
    <table id="myTable" class="table table-centered table-nowrap mb-0 rounded">
        <thead class="thead-light">
            <tr>
                <th class="border-0 rounded-start">No</th>
                <th class="border-0">Game</th>
                <th class="border-0">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($game as $game)
            <tr>
                <td><p class="text-primary fw-bold">{{ $loop->iteration }}</p> </td>
                <td>{{ $game->game_title }}</td>
                <td>
                    <a class="btn btn-sm btn-info" href="{{ route('cms.pricepoint.list', $game->id) }}">Price Point List</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>




@endsection

@extends('cms.addons.script')