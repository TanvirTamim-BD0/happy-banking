@extends('frontend.master')
@section('title') Transfer Revert @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container ">

    <form action="{{route('webuser.transfer-revert-filter')}}" method="post" autocomplete="off" class="needs-validation card" style="margin-bottom: 13px;">
        @csrf
        <h3 class="text-center heading_text pb-3">Filter Transaction</h3>

        <div class="form-group custom-form-group">
        <label class="custom-form-label" for="email">Transaction Id <span class="custom-danger">(requierd)</span></label>
        <div class="single_input ">
            <i class="fa-solid fa-credit-card"></i>
            <input name="transaction_id" autocomplete="off" type="number" placeholder="Transaction Id" class="form-control" required>
        </div>
        </div>

        <div class="condition mt-3">
            <button type="submit" class="primary_btn mb-0">Filter</button>
        </div>
    </form>

</div>

@endsection
