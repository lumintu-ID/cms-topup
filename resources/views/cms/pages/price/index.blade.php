@extends('cms.layouts.index')

@extends('cms.addons.css')

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
    <table id="myTable" class="table table-centered table-nowrap mb-0 rounded">
        <thead class="thead-light">
            <tr>
                <th class="border-0 rounded-start">No</th>
                <th class="border-0">Payment</th>
                <th class="border-0">Game</th>
                <th class="border-0">Price</th>
                <th class="border-0">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $price)
            <tr>
                <td><p class="text-primary fw-bold">{{ $loop->iteration }}</p> </td>
                <td>{{ $price->payment->name_channel }}</td>
                <td>
                    {{ $price->game->game_title }}
                </td>
                <td>
                   {{ $price->title_price .' - '. $price->price }}
                </td>
                <td>
                    <button data-bs-toggle="modal" data-bs-target="#add" onclick="update({{ $price }})"
                        class="btn btn-sm btn-info">Update</button>
                    
                    <button data-bs-toggle="modal" data-bs-target="#delete-{{ $loop->iteration }}" class="btn btn-sm btn-warning">Delete</button>
                    <div class="modal fade bs-example-modal-sm" id="delete-{{ $loop->iteration }}" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="h6 modal-title" id="modal-title">Delete Payment</h2>
                                </div>
                                <form id="url" action="{{ route('cms.price.delete') }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <div class="modal-body row">
                                        <h4>Are you sure delete this Payment?</h4>
                                        <p>Payment : {{ $price->payment->name_channel }}</p>
                                        <p>Name : {{ $price->title_price.' - '.$price->price }}</p>
                                        <input type="hidden" name="id" value="{{ $price->price_id }}">
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
                <h2 class="h6 modal-title" id="modal-title-form">Add Price</h2>
            </div>
            <form id="url" action="{{ route('cms.price.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div id="methods">
                    
                </div>
                <div class="modal-body row">
                    <div class="col-md-12 mb-3">
                        <label class="my-1 me-2" for="game">Select Game</label>
                        <select class="form-select" name="game" id="game" aria-label="Default select example" required>
                            <option selected>Select Game</option>
                            @foreach ($game as $game)
                                <option value="{{ $game->id }}">{{ $game->game_title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="my-1 me-2" for="payment">Select Payment</label>
                        <select class="form-select" name="payment" id="payment" aria-label="Default select example" required>
                            <option selected>Select Payment</option>
                            @foreach ($payment as $payment)
                                <option value="{{ $payment->payment_id }}">{{ $payment->name_channel }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Name</label>
                        <input type="text" name="price_name" value="{{ old('price_name') }}" class="form-control" id="price_name"
                            placeholder="price name" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Price</label>
                        <input type="number" name="price" value="{{ old('price') }}" class="form-control" id="price"
                            placeholder="price" required>
                    </div>
                   
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary" id="btn-modal-form">Create</button>
                    <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End of Modal Content -->

<script>
    function update(data) {
        $('#thumbnail').removeAttr('required');

        $('#url').attr('action', "{{ route('cms.price.update') }}");
        method = '<input id="mtd" type="hidden" name="_method" value="PATCH">'
        id = `<input id="id" type="hidden" name="id" value="${data.price_id}">`
        $('#methods').append(method);
        $('#methods').append(id);

        $('#price_name').val(data.title_price)
        $('#price').val(data.price)
        $('#payment').val(data.payment_id)
        $('#game').val(data.game_id)
        
        $('#modal-title-form').html('Update Price')
        $('#btn-modal-form').html('Update')
    }
    function add() {
        $('#methods').empty()
        $('#url').attr('action', "{{ route('cms.price.store') }}");
        $('#price_name').val('')
        $('#price').val('')
        $('#game').val('')
        $('#game').val('Select Game')
        $('#game').prop('disabled', false)
        $('#payment').val('')
        $('#payment').val('Select Payment')
        $('#payment').prop('disabled', false)
        $('#modal-title-form').html('Create Price')
        $('#btn-modal-form').html('Create')
    }
    function delet(data) {
        $('#id').val(data.id)
        $('#nm-app').html(data.name)
    }
</script>


@endsection

@extends('cms.addons.script')