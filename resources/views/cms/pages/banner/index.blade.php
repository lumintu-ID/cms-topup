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
                <th class="border-0 rounded-start">#</th>
                <th class="border-0">Banner</th>
                <th class="border-0">Status</th>
                <th class="border-0">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td><p class="text-primary fw-bold">{{ $loop->iteration }}</p></td>
                    <td>
                        <img src="{{ url('/banner/'.$item->banner) }}" alt="thumbnail" style="width: 400px">
                    </td>
                    <td>
                        @if ($item->is_active == 1)
                            <span class="badge bg-success">Is Active</span>
                        @else
                            <span class="badge bg-primary">Not Active</span>
                        @endif 
                    </td>
                    <td>
                        @if ($item->is_active == 1)
                            <a href="{{ route('cms.banner.status', $item->id_banner) }}" class="btn btn-sm btn-primary">Disabled</a>
                        @else
                            <a href="{{ route('cms.banner.status', $item->id_banner) }}" class="btn btn-sm btn-success">Enabled</a>
                        @endif
                        <button data-bs-toggle="modal" data-bs-target="#add" onclick="update({{ $item }})"
                            class="btn btn-sm btn-info">Update</button>
                        
                        <button data-bs-toggle="modal" data-bs-target="#delete-{{ $loop->iteration }}" class="btn btn-sm btn-warning">Delete</button>
                        <div class="modal fade bs-example-modal-sm" id="delete-{{ $loop->iteration }}" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="h6 modal-title" id="modal-title">Delete Banner</h2>
                                    </div>
                                    <form id="url" action="{{ route('cms.banner.delete') }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <div class="modal-body row">
                                            <h4>Are you sure delete this Banner?</h4>
                                           
                                                <img src="{{ url('/banner/'.$item->banner) }}" alt="thumbnail" style="width: 100%">
                                         
                                          
                                            <input type="hidden" name="id" value="{{ $item->id_banner }}">
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
                <h2 class="h6 modal-title" id="modal-title-form">Add Banner</h2>
            </div>
            <form id="url" action="{{ route('cms.banner.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div id="methods">
                    
                </div>
                <div class="modal-body row">
                  
                    <div class="col-md-12 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Banner</label>
                        <div id="thumb" class="text-center mb-5">
                        </div>
                        <input type="file" name="banner" value="{{ old('banner') }}" class="form-control" id="banner"
                            placeholder="Banner" required>
                    </div>
                   
                </div>
                <div class="modal-footer">
                    <input id="btn-modal-form" type="submit" value="Create" class="btn btn-secondary">
                    <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End of Modal Content -->

<script>
    function update(data) {
        console.log(data)
        $('#banner').removeAttr('required');
        $('#thumb').empty()
        $('#methods').empty()
        $('#url').attr('action', "{{ route('cms.banner.update') }}");
        method = '<input id="mtd" type="hidden" name="_method" value="PATCH">'
        id = `<input id="id" type="hidden" name="id" value="${data.id_banner}">`
        thumb = `<img src="{{ url('/banner') }}/${data.banner}" alt="banner" style="width: 450px">`
        $('#methods').append(method);
        $('#methods').append(id);

        $('#thumb').append(thumb);
        $('#modal-title-form').html('Update Banner')
        $('#btn-modal-form').prop('value', 'Update');
    }
    function add() {
        $('#banner').attr('required', 'required');
        $('#thumb').empty()
        $('#methods').empty()
        $('#url').attr('action', "{{ route('cms.banner.store') }}");
        $('#modal-title-form').html('Add Banner')
        $('#btn-modal-form').prop('value', 'Create');
    }
    function delet(data) {
        $('#id').val(data.id)
        $('#nm-app').html(data.name)
    }
</script>


@endsection

@extends('cms.addons.script')