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
    <h3>Role : {{ $role->role }}</h3>
    <table class="table table-centered table-nowrap mb-0 rounded">
        <thead class="thead-light">
            <tr>
                <th class="border-0 rounded-start">#</th>
                <th class="border-0">Navigation</th>
                <th class="border-0">Access</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $nav)
                <tr>
                    <td><p class="text-primary fw-bold">{{ $loop->iteration }}</p> </td>
                    <td>
                        {{ $nav->navigation }}
                    </td>
                    <td>
                        <div class="align-middle text-center text-sm">
                            <div class="form-check form-switch">
                                    <input onclick="access({{ $nav }})" class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" 
                                    {{ \App\Models\user_access::where('nav_id', $nav->nav_id)->where('role_id', $role->role_id)->first() ? 'checked' : '' }}>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script>
   function access(data){

         // Call ajax for pass data to other place
        $.ajax({
                type: 'POST',
                url: '/administrator/user-access/checked',
                data:{
                    _token: '{{ csrf_token() }}',
                    role: <?= $role->role_id ?>,
                    nav: data.nav_id,
                    name: data.navigation
                },
            })
            .done(function(data){
                alert(data)
            })
   }
</script>

@endsection