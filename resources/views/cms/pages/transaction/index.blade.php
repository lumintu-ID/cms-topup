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
<div class="table-responsive" id="appVue">
    <table class="table table-centered table-nowrap mb-0 rounded">
        <thead class="thead-light">
            <tr>
                <th class="border-0 rounded-start">#</th>
                <th class="border-0">email</th>
                <th class="border-0">BILL FOR</th>
                <th class="border-0">Total</th>
                <th class="border-0">status</th>
                <th class="border-0">#</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(item,index) in transaction">
                <td>@{{ index + 1 }}</td>
                <td>@{{ item.email }}</td>
                <td>@{{ item.product_name }}</td>
                <td>@{{ item.amount }}</td>
                <td>
                    <span v-if="item.status == 1 " class="fw-bold text-warning">Due</span>
                    <span v-else-if="item.status == 2 " class="fw-bold text-success">Paid</span>
                    <span v-else class="fw-bold text-danger">Cancelled</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>


<script src="/build/assets/app.61f518c6.js"></script>

<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    var vueDataTransaction = new Vue({
            el: "#appVue",
            data: {
                transaction: []
            },
            mounted() {
                this.getData();
            },
            methods: {
                getData: function() {
                    let url = "{{ url('/administrator/transaction') }}";

                    axios.get(url)
                        .then(resp => {
                            this.transaction = resp.data.data;
                        })
                        .catch(err => {
                            console.log(err);
                            alert('error');
                        })
                }
            }
        })
</script>

<script>

    function dateFormat() {
        const today = new Date();
	    const yyyy = today.getFullYear();
        let mm = today.getMonth() + 1; // Months start at 0!
        let dd = today.getDate();

        if (dd < 10) dd = '0' + dd;
        if (mm < 10) mm = '0' + mm;

        const formattedToday = dd + '/' + mm + '/' + yyyy;

        return formattedToday
    }


    window.Echo.channel("messages").listen("Transaction", (event) => {
        const notyf = new Notyf({
            position: {
                x: 'right',
                y: 'top',
            },
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
        vueDataTransaction.getData();
    });
</script>


@endsection