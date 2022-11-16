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
                <th class="border-0">Thumbnail</th>
                <th class="border-0">Name</th>
                <th class="border-0">Category</th>
                <th class="border-0">Country</th>
                <th class="border-0">#</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $pay)
            <tr>
                <td><p class="text-primary fw-bold">{{ $loop->iteration }}</p> </td>
                <td>
                    <img src="{{ url('/image/'.$pay->logo_channel) }}" alt="thumbnail" style="width: 100px">
                </td>
                <td>
                    {{ $pay->name_channel }}
                </td>
                <td>{{ $pay->category->category }}</td>
                <td>
                   {{ $pay->country->country .' - '. $pay->country->currency }}
                </td>
                <td>
                    <button data-bs-toggle="modal" data-bs-target="#add" onclick="update({{ $pay }})"
                        class="btn btn-sm btn-info">Update</button>
                    
                    <button data-bs-toggle="modal" data-bs-target="#delete-{{ $loop->iteration }}" class="btn btn-sm btn-warning">Delete</button>
                    <div class="modal fade bs-example-modal-sm" id="delete-{{ $loop->iteration }}" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="h6 modal-title" id="modal-title">Delete Payment</h2>
                                </div>
                                <form id="url" action="{{ route('cms.payment.delete') }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <div class="modal-body row">
                                        <h4>Are you sure delete this Payment?</h4>
                                        <p>Name : {{ $pay->name_channel }}</p>
                                        <img src="{{ url('/image/'.$pay->logo_channel) }}" alt="thumbnail" style="width: 100px">
                                        <input type="hidden" name="id" value="{{ $pay->payment_id }}">
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
                <h2 class="h6 modal-title" id="modal-title">Add Payment</h2>
            </div>
            <form id="url" action="{{ route('cms.payment.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div id="methods">
                    
                </div>
                <div class="modal-body row">
                  
                    <div class="col-md-12 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Thumbnail</label>
                        <div id="thumb" class="text-center">
                        </div>
                        <input type="file" name="thumbnail" value="{{ old('thumbnail') }}" class="form-control" id="thumbnail"
                            placeholder="thumbnail" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="my-1 me-2" for="country">Select Country</label>
                        <select class="form-select" name="country" id="country" aria-label="Default select example" required>
                            <option selected>Select Country</option>
                            @foreach ($country as $co)
                                <option value="{{ $co->country_id }}">{{ $co->country }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="my-1 me-2" for="category">Select Category</label>
                        <select class="form-select" name="category" id="category" aria-label="Default select example" required>
                            <option selected>Select Category</option>
                            @foreach ($category as $cat)
                                <option value="{{ $cat->category_id }}">{{ $cat->category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="name"
                            placeholder="name" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Channel ID</label>
                        <input type="text" name="channel_id" value="{{ old('channel_id') }}" class="form-control" id="channel_id"
                            placeholder="channel_id" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Url</label>
                        <input type="url" name="url" value="{{ old('url') }}" class="form-control" id="url-payment"
                            placeholder="url" required>
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
        $('#thumb').empty()
        $('#thumbnail').removeAttr('required');

        $('#url').attr('action', "{{ route('cms.payment.update') }}");
        method = '<input id="mtd" type="hidden" name="_method" value="PATCH">'
        id = `<input id="id" type="hidden" name="id" value="${data.payment_id}">`
        thumb = `<img src="{{ url('/image') }}/${data.logo_channel}" alt="thumbnail" style="width: 100px">`
        $('#methods').append(method);
        $('#methods').append(id);

        $('#thumb').append(thumb);
        $('#name').val(data.name_channel)
        $('#channel_id').val(data.channel_id)
        $('#country').val(data.country_id)
        $('#url-payment').val(data.url)
     
        $('#category').val(data.category_id)
        
        $('#modal-title').html('Update Payment')
        $('#btn-modal').html('Update')
    }
    function add() {
        $('#thumbnail').attr('required', 'required');
        $('#thumb').empty()
        $('#methods').empty()
        $('#url').attr('action', "{{ route('cms.payment.store') }}");
        $('#name').val('')
        $('#channel_id').val('')
        $('#country').val('')
        $('#country').val('Select Country')
        $('#country').prop('disabled', false)
        $('#category').val('')
        $('#url-payment').val('')
        $('#category').val('Select Category')
        $('#category').prop('disabled', false)
        $('#modal-title').html('Create Payment')
        $('#btn-modal').html('Create')
    }
    function delet(data) {
        $('#id').val(data.id)
        $('#nm-app').html(data.name)
    }
</script>


@endsection