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
                    @if ($data->status == 0)
                        <span class="fw-bold text-warning">Due</span>
                    @elseif($data->status == 1)
                        <span class="fw-bold text-success">Paid</span>
                    @else
                        <span class="fw-bold text-danger">Cancelled</span>
                    @endif 
                </td>
                <td>{{ $data->created_at }}</td>
                <td>
                    <button data-bs-toggle="modal" data-bs-target="#Detail" onclick="Detail({{ $data }})"
                    class="btn btn-sm btn-info">Detail</button>     
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
                        <div class="col-6 text-end" id="PPI">  </div>
                      </div>
                      <div class="row row-cols-1 row-cols-sm-2 py-2">
                        <div class="col-6"> Method Payment : </div>
                        <div class="col-6 text-end" id="PAYMENT"> </div>
                      </div>
                     
                      <hr>

                      <div class="row row-cols-1 row-cols-sm-2 py-2">
                        <div class="col-6"> Paid Date : </div>
                        <div class="col-6 text-end">  </div>
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



<script src="/build/assets/app.61f518c6.js"></script>

<script>


    function Detail(data) {
        console.log(data);
        $('#INV').html(data.invoice)
        $('#GAME').html(data.game.game_title)
        $('#USERID').html(data.id_Player)
        $('#PAYMENT').html(data.payment.name_channel)
        $('#TTLPRICE').html(data.total_price)
        $('#PPI').html(data.pricepoint.price_point)
        $('#AMOUNT').html(data.amount)

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
            message: 'Transaction From '+event.message.email+ ' in '+ dateFormat()
        });
       
        console.log(data);
    });
</script>


@endsection

@extends('cms.addons.script')