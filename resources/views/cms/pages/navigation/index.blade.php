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
                <th class="border-0">Navigation</th>
                <th class="border-0">Url</th>
                <th class="border-0">Status</th>
                <th class="border-0">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $navigation)
                <tr>
                    <td><p class="text-primary fw-bold">{{ $loop->iteration }}</p> </td>
                    <td>{{ $navigation->navigation }}</td>
                    <td>{{ $navigation->url }}</td>
                    <td>
                        @if ($navigation->is_active == 1)
                            <span class="badge bg-success">Enable</span>
                        @else
                            <span class="badge bg-primary">Disable</span>
                        @endif
                    </td>
                    <td>
                        @if ($navigation->is_active == 1)
                            <a href="{{ route('cms.navigation.status', $navigation->nav_id) }}" class="btn btn-sm btn-primary">Disabled</a>
                        @else
                            <a href="{{ route('cms.navigation.status', $navigation->nav_id) }}" class="btn btn-sm btn-success">Enabled</a>
                        @endif
                        <button data-bs-toggle="modal" data-bs-target="#add" onclick="update({{ $navigation }})"
                            class="btn btn-sm btn-info">Update</button>
                        
                        <button data-bs-toggle="modal" data-bs-target="#delete-{{ $loop->iteration }}" class="btn btn-sm btn-warning">Delete</button>
                        <div class="modal fade bs-example-modal-sm" id="delete-{{ $loop->iteration }}" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="h6 modal-title" id="modal-title">Add Navigation</h2>
                                    </div>
                                    <form id="url" action="{{ route('cms.navigation.delete') }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <div class="modal-body row">
                                            <h4>Are you sure delete this post?</h4>
                                            <p>{{ $navigation->navigation }}</p>
                                            <input type="hidden" name="id" value="{{ $navigation->nav_id }}">
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
                <h2 class="h6 modal-title" id="modal-title-form">Add Navigation</h2>
            </div>
            <form id="url" action="{{ route('cms.navigation.store') }}" method="post">
                @csrf
                <div id="methods">
                    
                </div>
                <div class="modal-body row">
                    <div class="col-md-12 mb-3">
                        <label class="my-1 me-2" for="country">Select Label</label>
                        <select class="form-select" name="label" id="label" aria-label="Default select example" required>
                            <option selected="">Select Label</option>
                            <option value="1">Home</option>
                            <option value="2">Config</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="name"
                            placeholder="navigation" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Url</label>
                        <input type="text" class="form-control" name="url" value="{{ old('url') }}"
                            id="url-navigation" placeholder="/example-link" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">icon</label>
                        <input type="text" class="form-control" name="icon" value="{{ old('icon') }}"
                            id="icon" placeholder="icon" required>
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
        $('#url').attr('action', "{{ route('cms.navigation.update') }}");
        method = '<input id="mtd" type="hidden" name="_method" value="PATCH">'
        id = `<input id="id" type="hidden" name="id" value="${data.nav_id}">`
        $('#methods').append(method);
        $('#methods').append(id);
        $('#modal-title-form').html('Update Navigation')
        $('#btn-modal-form').html('Update')
        $('#name').val(data.navigation)
        $('#icon').val(data.icon)
        $('#url-navigation').val(data.url)
        $('#label').val(data.id_label)
    }
    function add() {
        $('#methods').empty()
        $('#url').attr('action', "{{ route('cms.navigation.store') }}");
        $('#name').val('')
        $('#icon').val('')
        $('#url-navigation').val('')
        $('#label').val('')
        $('#label').val('Select Label')
        $('#label').prop('disabled', false)
        $('#modal-title-form').html('Create Navigation')
        $('#btn-modal-form').html('Create')
    }
    function delet(data) {
        $('#id').val(data.id)
        $('#nm-app').html(data.name)
    }
</script>


@endsection

@extends('cms.addons.script')