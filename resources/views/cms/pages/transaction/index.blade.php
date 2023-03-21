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

{{-- @if (Session::has('message'))
@if (Session::get('alert-info', 'success'))
<div class="alert alert-success" role="alert">
    {{ Session::get('message') }}
</div>
@else
<div class="alert alert-danger" role="alert">
    {{ Session::get('message') }}
</div>
@endif
@endif --}}

<div>
    <strong>Check Status Transaction</strong>
    <form id="invoiceForm" class="input-form input-group">
        <input class="tokenize" type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="text" placeholder="Input Invoice" name="invoice" id="inpInvoice">
        <button class="btn btn-block btn-gray-800" id="btnCheckInvoice">Check</button>
    </form>
</div>

<hr>



<div class="row">

    <form action="" method="get" id="invoiceForm" class="input-form input-group">
        @csrf
        <div class="col-md-2">
            <div class="col ">
                <label class="col-form-label label-align">Filter By Game </label>
                <select class="form-select" name="game_list" aria-label="Default select example">
                    <option selected>Select Game</option>
                    @foreach ($game as $ga)
                    <option value="{{ $ga->id }}">{{ $ga->game_title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <div class="col ">
                <label class="col-form-label label-align">Filter By Status </label>
                <select class="form-select" name="status" aria-label="Default select example">
                    <option selected>Select Status Transaction</option>
                    <option value="0">Pending</option>
                    <option value="1">Success</option>
                    <option value="2">Failed</option>
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <label class="col-form-label label-align">Filter By Date </label>
            <div>
                <div class=" d-flex">
                    <input id="birthday" name="start_date" class="date-picker form-control" placeholder="dd-mm-yyyy"
                        type="date" onfocus="this.type='date'" onmouseover="this.type='date'"
                        onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)">
                    <input id="birthday" name="end_date" class="date-picker form-control" placeholder="dd-mm-yyyy"
                        type="date" onfocus="this.type='date'" onmouseover="this.type='date'"
                        onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)">
                    <script>
                        function timeFunctionLong(input) {
                                setTimeout(function() {
                                    input.type = 'text';
                                }, 60000);
                            }
                    </script>
                    <button type="submit" class="btn btn-block btn-gray-800" id="btnCheckInvoice">Fillter</button>
                </div>
            </div>
        </div>
    </form>
</div>


<hr>


<div class="table-responsive">

    <table id="myTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Email</th>
                <th>Bill For</th>
                <th>Payment</th>
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
                <td>{{ $data->game_title }}</td>
                <td>{{ $data->name_channel }}</td>
                <td>{{ $data->total_price }}</td>
                <td>
                    @if ($data->status == 0)
                    <span class="fw-bold text-warning">Due</span>
                    @elseif($data->status == 1)
                    <span class="fw-bold text-success">Paid</span>
                    @else
                    <span class="fw-bold text-danger">Fail</span>
                    @endif
                </td>
                <td>{{ $data->created_at }}</td>
                <td>
                    <button data-bs-toggle="modal" data-bs-target="#Detail" onclick="Detail({{ $data }})"
                        class="btn btn-sm btn-info">Detail</button>
                    <form action="{{ route('cms.transaction.check') }}" method="post">
                        @csrf
                        <input type="hidden" value="{{ $data->invoice }}" name="invoice">
                        <button type="submit" class="btn btn-block btn-gray-800">Check</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


<!-- Modal Detail -->
<div class="modal fade" id="Detail" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="h6 modal-title" id="modal-title-form">Detail Transaction</h2>
                <div class="text-center" id="INV">
                    INVOICE : INV-58IVmQGiIzpT
                </div>
            </div>

            <div class="modal-body row">

                <div class="row row-cols-1 row-cols-sm-2 py-2">
                    <div class="col-6"> Game : </div>
                    <div class="col-6 text-end" id="GAME"></div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 py-2 ">
                    <div class="col-6"> User ID : </div>
                    <div class="col-6 text-end" id="USERID"> </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 py-2">
                    <div class="col-6"> Amount : </div>
                    <div class="col-6 text-end" id="AMOUNT">

                    </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 py-2">
                    <div class="col-6"> PPI :</div>
                    <div class="col-6 text-end" id="PPI"> </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 py-2">
                    <div class="col-6"> Method Payment : </div>
                    <div class="col-6 text-end" id="PAYMENT"> </div>
                </div>

                <hr>

                <div class="text-center">
                    <strong>Paid Status</strong>
                </div>

                <div class="row row-cols-1 row-cols-sm-2 py-2">
                    <div class="col-6"> Paid Date : </div>
                    <div class="col-6 text-end" id="PAID-DATE"> </div>
                </div>

                <div class="row row-cols-1 row-cols-sm-2 py-2">
                    <div class="col-6"> Status : </div>
                    <div class="col-6 text-end" id="STATUS"> </div>
                </div>

                <div class="row row-cols-1 row-cols-sm-2 py-2">
                    <div class="col-6"> Total Payment : </div>
                    <div class="col-6 text-end" id="TTLPRICE"> </div>
                </div>


            </div>
            <div class="modal-footer">
                <input id="btn-modal-form" type="submit" value="Create" class="btn btn-secondary">
                <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End of Modal Content -->


<!-- Modal -->
<div class="modal fade" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"> INV-XXXXXXX </h4>
            </div>
            <div class="modal-body" id="getCode" style="overflow-x: scroll;">

            </div>
        </div>
    </div>
</div>



<script src="/build/assets/app.61f518c6.js"></script>

<script>
    function dateToYMD(date) {
        var strArray=['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        var d = date.getDate();
        var m = strArray[date.getMonth()];
        var y = date.getFullYear();
        return '' + (d <= 9 ? '0' + d : d) + '-' + m + '-' + y;
    }

    function Detail(data) {
        // console.log(data);
        $('#INV').html(data.invoice)
        $('#GAME').html(data.game_title)
        $('#USERID').html(data.id_player)
        $('#PAYMENT').html(data.name_channel)
        $('#TTLPRICE').html(data.total_price)
        $('#PPI').html(data.PPI)
        $('#AMOUNT').html(data.amount)
        $('#PAID-DATE').html((data.status == 1) ? dateToYMD(new Date(data.paid_time)) : '')

        let status
        if (data.status == 0) {
            status = 'Due' 
        }else if(data.status == 1){
            status = 'Success'
        }else{
            status = 'Failed'
        }

        $('#STATUS').html(status)
    }


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
            message: 'Transaction in '+ dateFormat()
        });
       
        console.log(data);
    });
</script>

<script>
    $('#btnCheckInvoice').click(function (e) {
        const inv = $('#inpInvoice').val();
        e.preventDefault();

        $(this).html('Checking....');
      
        $.ajax({
            data: $('#invoiceForm').serialize(),
            url: "{{ route('cms.transaction.check') }}",
            type: "POST",
            success: function(resp){
                $("#getCode").html(resp);
                $('#myModalLabel').html(inv)
                console.log(inv)
                console.log(resp)
                jQuery("#getCodeModal").modal('show');
                
            },
            error: function (e) {
                console.log('Error:', e);
            }
        });

        $(this).html('Check');
    });

   
</script>

@endsection

@extends('cms.addons.script')