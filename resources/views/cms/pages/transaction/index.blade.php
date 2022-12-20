@extends('cms.layouts.index')

@extends('cms.addons.css')

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

    <table id="myTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Email</th>
                <th>Bill For</th>
                <th>Total</th>
                <th>Status</th>
                <th>Transaction</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $data)
            <tr>
                <td>{{ $data->invoice }}</td>
                <td>{{ $data->email }}</td>
                <td>{{ $data->game->game_title }}</td>
                <td>{{ $data->total_price }}</td>
                <td>
                    @if ($data->status == 1)
                        <span class="fw-bold text-warning">Due</span>
                    @elseif($data->status == 2)
                        <span class="fw-bold text-success">Paid</span>
                    @else
                        <span class="fw-bold text-danger">Cancelled</span>
                    @endif 
                </td>
                <td>{{ $data->created_at }}</td>
                <td>
                    
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>






<script src="/build/assets/app.61f518c6.js"></script>

<script>

    var data = 0;

    function dateFormat() {
        today = new Date();
        
	    const yyyy = today.getFullYear();
        let mm = today.getMonth() + 1; // Months start at 0!
        let dd = today.getDate();

        if (dd < 10) dd = '0' + dd;
        if (mm < 10) mm = '0' + mm;

        const formattedToday = dd + '/' + mm + '/' + yyyy;

        return formattedToday
    }


    window.Echo.channel("messages").listen("Transaction", (event) => {


        data += 1;

        const notyf = new Notyf({
            position: {
                x: 'right',
                y: 'top',
            },
            autoHideDelay: 5000,
            types: [
                {
                    type: 'info',
                    background: 'blue',
                    icon: {
                        className: 'fas fa-info-circle',
                        tagName: 'span',
                        color: '#fff'
                    },
                    dismissible: false
                }
            ]
        });

        notyf.open({
            type: 'info',
            message: 'Transaction From '+event.message.email+ ' in '+ dateFormat()
        });
       
        console.log(data);
    });
</script>


@endsection

@extends('cms.addons.script')