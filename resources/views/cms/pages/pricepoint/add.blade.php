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


<form action="{{ route('cms.pricepoint.store') }}" method="post">
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
            <label for="exampleFormControlInput1" class="form-label">Select Country *</label>
            <select class="form-select" name="country" id="country"  aria-label="Default select example" required>
                <option selected>Select Payment</option>
                @foreach ($country as $co)
                    <option value="{{ $co->currency }}">{{  $co->currency .' - '. $co->country }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 mb-3 d-flex align-items-end">
            <button type="submit" class="btn btn-outline-success form-control">Save</button>
        </div>
    </div>
   
    <div id="dynamicAddRemove">
    	<div class="row mb-2 p-3" style="border: solid black 1px">

            <div class="col-md-12 mb-3">
                <label for="exampleFormControlInput1" class="form-label">Price Point ID</label>
                <input type="text" name="price_point[0]" value="{{ old('price_point') }}" class="form-control" id="price_point"
                    placeholder="Price Point ID" required>
            </div>

            <div class="col-md-12 mb-3">
                <label for="exampleFormControlInput1" class="form-label">Default Amount</label>
                <input type="text" name="amount[0]" value="{{ old('amount') }}" class="form-control" id="amount"
                    placeholder="Amount" required>
            </div>

            <div class="col-md-12 mb-3">
                <label for="exampleFormControlInput1" class="form-label">Default Price</label>
                <input type="text" name="price[0]" value="{{ old('price') }}" class="form-control" id="price"
                    placeholder="Price" required>
            </div>

            <div class="col-md-4 mb-3 d-flex align-items-end">
                <input type="button" value="Add Subject" id="dynamic-ar" class="btn btn-outline-primary form-control">
            </div>
       </div>
    </div>
</form>

<script type="text/javascript">
    var i = 0;
    $("#dynamic-ar").click(function () {
        ++i;
        $("#dynamicAddRemove").append(`
        <div class="row mb-2 p-3" style="border: solid black 1px">
        
            <div class="col-md-12 mb-3">
                <label for="exampleFormControlInput1" class="form-label">Price Point ID</label>
                <input type="text" name="price_point[`+ i +`]" value="{{ old('price_point') }}" class="form-control" id="price_point"
                    placeholder="Price Point ID" required>
            </div>

            <div class="col-md-12 mb-3">
                <label for="exampleFormControlInput1" class="form-label">Default Amount</label>
                <input type="text" name="amount[`+ i +`]" value="{{ old('amount') }}" class="form-control" id="amount"
                    placeholder="Amount" required>
            </div>

            <div class="col-md-12 mb-3">
                <label for="exampleFormControlInput1" class="form-label">Default Price</label>
                <input type="text" name="price[`+ i +`]" value="{{ old('price') }}" class="form-control" id="price"
                    placeholder="Price" required>
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