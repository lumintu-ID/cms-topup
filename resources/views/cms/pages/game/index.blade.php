@extends('cms.layouts.index')

@section('content')

<button type="button" class="btn btn-block btn-gray-800 mb-3" data-bs-toggle="modal" data-bs-target="#add" onclick="add()">Add</button>


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
                <th class="border-0 rounded-start">Game ID</th>
                <th class="border-0">Cover</th>
                <th class="border-0">Game Title</th>
                <th class="border-0">#</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $game)
            <tr>
                <td><p class="text-primary fw-bold">{{ $game->game_id }}</p> </td>
                <td>
                    <img src="{{ url('/cover/'.$game->cover) }}" alt="thumbnail" style="width: 100px">
                </td>
                <td>
                    {{ $game->game_title }}
                </td>
                <td>
                    <button data-bs-toggle="modal" data-bs-target="#add" onclick="update({{ $game }})"
                        class="btn btn-sm btn-info">Update</button>
                    
                    <button data-bs-toggle="modal" data-bs-target="#delete-{{ $game->game_id }}" class="btn btn-sm btn-warning">Delete</button>
                    <div class="modal fade bs-example-modal-sm" id="delete-{{ $game->game_id }}" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="h6 modal-title" id="modal-title">Delete Payment</h2>
                                </div>
                                <form id="url" action="{{ route('cms.game.delete') }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <div class="modal-body row">
                                        <h4>Are you sure delete this Game?</h4>
                                        <p>Name : {{ $game->game_title }}</p>
                                        <input type="hidden" name="id" value="{{ $game->id }}">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-secondary" id="btn-modal">Delete</button>
                                        <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>    
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal add -->
<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="h6 modal-title" id="modal-title">Add Game</h2>
            </div>
            <form id="url" action="{{ route('cms.game.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div id="methods">
                    
                </div>
                <div class="modal-body row">
                  
                    <div class="col-md-12 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Cover</label>
                        <div id="thumb" class="text-center">
                        </div>
                        <input type="file" name="cover" value="{{ old('cover') }}" class="form-control" id="cover"
                            placeholder="thumbnail" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Game ID</label>
                        <input type="text" name="game_id" value="{{ old('game_id') }}" class="form-control" id="game_id"
                            placeholder="Game ID" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Game Title</label>
                        <input type="text" name="game_title" value="{{ old('game_title') }}" class="form-control" id="game_title"
                            placeholder="Game Title" required>
                    </div>
                   
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary" id="btn-modal">Create</button>
                    <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End of Modal Content -->

<script>
    function update(data) {
        $('#cover').removeAttr('required');

        $('#url').attr('action', "{{ route('cms.game.update') }}");
        method = '<input id="mtd" type="hidden" name="_method" value="PATCH">'
        id = `<input id="id" type="hidden" name="id" value="${data.id}">`
        thumb = `<img src="{{ url('/cover') }}/${data.cover}" alt="cover" style="width: 100px">`
        $('#methods').append(method);
        $('#methods').append(id);

        $('#thumb').append(thumb);
        $('#game_id').val(data.game_id)
        $('#game_title').val(data.game_title)
        $('#modal-title').html('Update Game')
        $('#btn-modal').html('Update')
    }
    function add() {
        $('#cover').attr('required', 'required');
        $('#thumb').empty()
        $('#methods').empty()
        $('#url').attr('action', "{{ route('cms.game.store') }}");
        $('#game_id').val('')
        $('#game_title').val('')
        $('#modal-title').html('Create Game')
        $('#btn-modal').html('Create')
    }
    function delet(data) {
        $('#id').val(data.id)
        $('#nm-app').html(data.name)
    }
</script>


@endsection