<?php 

function rupiah($number, $currency){
	if ($currency == "IDR") {
        $result = number_format($number,2,',','.');
	    return $result;
    };
	
    return $number;
}
 
?>


@extends('cms.layouts.index')

@extends('cms.addons.css')

@section('content')


<form action="{{ route('cms.price.store') }}" method="post">
    @csrf
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <label for="exampleFormControlInput1" class="form-label">Select Game *</label>
            <select class="form-select" name="game" aria-label="Default select example" required>
                <option selected>Select Game </option>
                @foreach ($game as $game)
                    <option value="{{ $game->id }}">{{ $game->game_title }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 mb-3">
            <label for="exampleFormControlInput1" class="form-label">Select Payment *</label>
            <select class="form-select" name="payment"  aria-label="Default select example" required>
                <option selected>Select Payment</option>
                @foreach ($payment as $payment)
                    <option value="{{ $payment->payment_id }}">{{ $payment->name_channel }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 mb-3">
            <label for="exampleFormControlInput1" class="form-label">Name Currency *</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                placeholder="Name" required>
        </div>
        <div class="col-md-3 mb-3 d-flex align-items-end">
            <button type="submit" class="btn btn-outline-success form-control">Save</button>
        </div>
    </div>
   
    <div id="dynamicAddRemove">
    	<div class="row mb-2 p-3" style="border: solid black 1px">
        
            <div class="col-md-4 mb-3">
                <label for="exampleFormControlInput1" class="form-label">Select Price Point ID *</label>
                <select class="form-select ppi" name="ppi[0]" aria-label="Default select example" required>
                    <option selected>Select PPI</option>
                    @foreach ($ppi as $pricePoint)
                        <option value="{{ $pricePoint->id }}">{{ $pricePoint->price_point }}</option>
                    @endforeach
                </select>
            </div>
    

            <div class="col-md-4 mb-3">
                <label for="exampleFormControlInput1" class="form-label">Amount </label>
                <input type="number" name="amount[0]" value="{{ old('amount') }}" class="form-control amount"
                    placeholder="amount">
            </div>

            <div class="col-md-4 mb-3">
                <label for="exampleFormControlInput1" class="form-label">Price</label>
                <input type="number" name="price[0]" value="{{ old('price') }}" class="form-control"
                    placeholder="price">
            </div>

            <div class="col-md-4 mb-3 d-flex align-items-end">
                <input type="button" value="Add Subject" id="dynamic-ar" class="btn btn-outline-primary form-control">
            </div>
       </div>
    </div>
</form>

<script type="text/javascript">

    var ppi = <?= $ppi ?>;

    var i = 0;
    $("#dynamic-ar").click(function () {
        ++i;
        $("#dynamicAddRemove").append(`
        <div class="row mb-2 p-3" style="border: solid black 1px">
        
                <div class="col-md-4 mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Select Price Point ID</label>
                    <select class="form-select" name="ppi[`+ i +`]" aria-label="Default select example" required>
                        <option selected>Select PPI</option>
                        @foreach ($ppi as $pricePoint)
                            <option value="{{ $pricePoint->id }}">{{ $pricePoint->price_point }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Amount</label>
                    <input type="number" name="amount[`+ i +`]"  class="form-control"
                        placeholder="amount" >
                </div>

                <div class="col-md-4 mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Price</label>
                    <input type="number" name="price[`+ i +`]" class="form-control"
                        placeholder="price" >
                </div>

                <div class="col-md-4 mb-3 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger remove-input-field form-control">Delete</button>
                </div>
        </div>
        `);
    });
    $(document).on('click', '.remove-input-field', function () {
        $(this).parents('.row').remove();
    });

</script>

@endsection

@extends('cms.addons.script')