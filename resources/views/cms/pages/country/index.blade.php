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
                <th class="border-0 rounded-start">#</th>
                <th class="border-0">Currency</th>
                <th class="border-0">Country</th>
                <th class="border-0">#</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $country)
                <tr>
                    <td><p class="text-primary fw-bold">{{ $loop->iteration }}</p> </td>
                    <td>{{ $country->currency }}</td>
                    <td>{{ $country->country }}</td>
                    <td>
                        <button data-bs-toggle="modal" data-bs-target="#add" onclick="update({{ $country }})"
                            class="btn btn-sm btn-info">Update</button>
                        
                        <button data-bs-toggle="modal" data-bs-target="#delete-{{ $loop->iteration }}" class="btn btn-sm btn-warning">Delete</button>
                        <div class="modal fade bs-example-modal-sm" id="delete-{{ $loop->iteration }}" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="h6 modal-title" id="modal-title">Delete Counrty</h2>
                                    </div>
                                    <form id="url" action="{{ route('cms.country.delete') }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <div class="modal-body row">
                                            <h4>Are you sure delete this Country?</h4>
                                            <p>{{ $country->country }}</p>
                                            <input type="hidden" name="id" value="{{ $country->country_id }}">
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
                <h2 class="h6 modal-title" id="modal-title">Add Country</h2>
            </div>
            <form id="url" action="{{ route('cms.country.store') }}" method="post">
                @csrf
                <div id="methods">
                    
                </div>
                <div class="modal-body row">
                    <div class="col-md-12 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Currency</label>
                        <input type="text" name="currency" value="{{ old('currency') }}" class="form-control" id="currency"
                            placeholder="currency" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Country</label>
                        <input type="text" name="country" value="{{ old('country') }}" class="form-control" id="country"
                            placeholder="country" required>
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
        $('#url').attr('action', "{{ route('cms.country.update') }}");
        method = '<input id="mtd" type="hidden" name="_method" value="PATCH">'
        id = `<input id="id" type="hidden" name="id" value="${data.country_id}">`
        $('#methods').append(method);
        $('#methods').append(id);
        $('#modal-title').html('Update country')
        $('#btn-modal').html('Update')
        $('#currency').val(data.currency)
        $('#country').val(data.country)
    }
    function add() {
        $('#methods').empty()
        $('#url').attr('action', "{{ route('cms.country.store') }}");
        $('#currency').val('')
        $('#country').val('')
        $('#modal-title').html('Create country')
        $('#btn-modal').html('Create')
    }
    function delet(data) {
        $('#id').val(data.id)
        $('#nm-app').html(data.name)
    }
</script>


@endsection