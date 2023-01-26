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
                <th class="border-0">Country</th>
                <th class="border-0">Price Point Id</th>
                <th class="border-0">Amount</th>
                <th class="border-0">Price</th>
                <th class="border-0">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $ppi)
            <tr>
                <td><p class="text-primary fw-bold">{{ $loop->iteration }}</p> </td>
                <td>{{ $ppi->country->currency .' - '. $ppi->country->country }}</td>
                <td>{{ $ppi->price_point }}</td>
                <td>{{ $ppi->amount }}</td>
                <td>{{ $ppi->price }}</td>
                <td>
                    <button data-bs-toggle="modal" data-bs-target="#add" onclick="update({{ $ppi }})"
                        class="btn btn-sm btn-info">Update</button>
                    
                    <button data-bs-toggle="modal" data-bs-target="#delete-{{ $loop->iteration }}" class="btn btn-sm btn-warning">Delete</button>
                    <div class="modal fade bs-example-modal-sm" id="delete-{{ $loop->iteration }}" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="h6 modal-title" id="modal-title">Delete Price Point</h2>
                                </div>
                                <form id="url" action="{{ route('cms.pricepoint.delete') }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <div class="modal-body row">
                                        <h4>Are you sure delete this Price Point?</h4>
                                        <p>Name : {{ $ppi->price_point }}</p>
                                        <input type="hidden" name="id" value="{{ $ppi->id }}">
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
                <h2 class="h6 modal-title" id="modal-title-form">Add Price Point</h2>
            </div>
            <form id="url" action="{{ route('cms.pricepoint.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div id="methods">
                    
                </div>
                <div class="modal-body row">
                    <div class="col-md-12 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Country</label>
                        <select class="form-select" name="country" id="country" aria-label="Default select example" required>
                            <option selected>Select Country</option>
                            @foreach ($country as $co)
                                <option value="{{ $co->currency }}">{{  $co->currency .' - '. $co->country }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Price Point ID</label>
                        <input type="text" name="price_point" value="{{ old('price_point') }}" class="form-control" id="price_point"
                            placeholder="Price Point ID" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Default Amount</label>
                        <input type="text" name="amount" value="{{ old('amount') }}" class="form-control" id="amount"
                            placeholder="Amount" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Default Price</label>
                        <input type="text" name="price" value="{{ old('price') }}" class="form-control" id="price"
                            placeholder="Price" required>
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
        $('#url').attr('action', "{{ route('cms.price.update') }}");
        method = '<input id="mtd" type="hidden" name="_method" value="PATCH">'
        id = `<input id="id" type="hidden" name="id" value="${data.id}">`
        $('#methods').append(method);
        $('#methods').append(id);

        $('#price_point').val(data.price_point)
        $('#country').val(data.country_id)
        $('#amount').val(data.amount)
        $('#price').val(data.price)

        $('#modal-title-form').html('Update Price Point')
        $('#btn-modal-form').html('Update')
    }
    function add() {
        $('#methods').empty()
        $('#url').attr('action', "{{ route('cms.pricepoint.store') }}");
        $('#price_point').val('')
        $('#country').val()
        $('#amount').val()
        $('#price').val()
        $('#modal-title-form').html('Add Price Point')
        $('#btn-modal-form').html('Create')
    }
    function delet(data) {
        $('#id').val(data.id)
        $('#nm-app').html(data.name)
    }
</script>


@endsection

@extends('cms.addons.script')