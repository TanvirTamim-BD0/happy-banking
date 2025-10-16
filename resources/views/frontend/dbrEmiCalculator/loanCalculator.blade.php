@extends('frontend.master')
@section('title') Loan Calculator @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container">

    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <form class="card mb-3" action="#" method="#">
                @csrf
                <h4 class="text-center heading_text">Card Loan EMI Calculator</h4>
                <h6 class="text-center pb-3">Work With Flat Interest</h6>
                <div class="form-group custom-form-group">
                    <label class="custom-form-label" for="loan_amount">Loan Amount: <span
                            class="custom-danger">(requierd)</span></label>
                    <div class="single_input" id="loan_amount_div">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                        <input onblur="LoanAmountInput()" name="loan_amount" type="number" id="loan_amount"
                            placeholder="Enter Loan Amount" required min="0" max="999999999">
                    </div>
                    <div id="is_loan_amount_blank" class="text-danger mt-1">
                    </div>
                </div>

                <div class="form-group custom-form-group">
                    <label class="custom-form-label" for="tenor">Tenor (in Months): <span
                            class="custom-danger ">(requierd)</span></label>
                    <div class="single_input" id="tenor_div">
                        <i class="fa-solid fa-sack-dollar"></i>
                        <input onblur="tenorInput()" name="tenor" type="number" id="tenor" placeholder="Enter Tenor"
                            min="0" max="999999999">
                    </div>
                    <div id="is_tenor_blank" class="text-danger mt-1">
                    </div>
                </div>

                <div class="form-group custom-form-group">
                    <label class="custom-form-label" for="rate_of_interest">Rate of Interest:<span
                            class="custom-danger">(requierd)</span></label>
                    <div class="single_input" id="rate_of_interest_div">
                        <i class="fa-solid fa-dollar-sign"></i>
                        <input onblur="interestInput()" name="rate_of_interest" type="number" id="rate_of_interest"
                            placeholder="Enter Interest Rate" min="0" max="999999999">
                    </div>
                    <div id="is_rate_of_interest_blank" class="text-danger mt-1">
                    </div>
                </div>



                <div class="result  bg-success rounded-2  mb-2 " id="loanResult">
                </div>

                <span onclick="emiCalculate()" class="primary_btn mb-1 mt-1 cursor-pointer">Loan Calculate</span>
            </form>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
    function emiCalculate(){
            var loanAmount = parseFloat($('#loan_amount').val());
            var tenor = parseFloat($('#tenor').val());
            var rateOfInterest = parseFloat($('#rate_of_interest').val());

            if(loanAmount != ''){
                $('#loan_amount_div').css('border', '1px solid green');
                $('#is_loan_amount_blank').text('');
            }else{
                $('#loan_amount_div').css('border', '1px solid red');
                $('#is_loan_amount_blank').text('Loan Amount Field Blank');
            }
            
            if(tenor != ''){
                $('#tenor_div').css('border', '1px solid green');
                $('#is_tenor_blank').text('');
            }else{
                $('#tenor_div').css('border', '1px solid red');
                $('#is_tenor_blank').text('Tenor Field Blank');
            }

            if(rateOfInterest != ''){
                $('#rate_of_interest_div').css('border', '1px solid green');
                $('#is_rate_of_interest_blank').text('');
            }else{
                $('#rate_of_interest_div').css('border', '1px solid red');
                $('#is_rate_of_interest_blank').text('Interest Field Blank');
            }


            if(loanAmount != '' && tenor != '' && rateOfInterest != ''){
                var interestAmount = (loanAmount/100)*rateOfInterest;
                var totalPayAmount = loanAmount + interestAmount;
                var installmentPayAmount = (totalPayAmount/tenor);

                $('#loanResult').html('<p class="px-2 py-1 text-white text-left fs-5 mb-0">Installment : <span class="text-white">'+installmentPayAmount.toFixed(2)+' '+' BDT</span><br>Total Interest : <span class="text-white">'+interestAmount.toFixed(2)+''+' BDT</span><br> Total Pay : <span class="text-white">'+totalPayAmount.toFixed(2)+''+' BDT</span></p>');
            }else{
                alert('asd');
            }
        }

        function LoanAmountInput(){
            $loan_amount = $('#loan_amount').val();
            if($loan_amount != ''){
                $('#loan_amount_div').css('border', '1px solid green');
                $('#is_loan_amount_blank').text('');
            }else{
                $('#loan_amount_div').css('border', '1px solid red');
                $('#is_loan_amount_blank').text('Loan Amount Field Blank');
            }
        }

        function tenorInput(){
            $tenor = $('#tenor').val();
            if($tenor != ''){
                $('#tenor_div').css('border', '1px solid green');
                $('#is_tenor_blank').text('');
            }else{
                $('#tenor_div').css('border', '1px solid red');
                $('#is_tenor_blank').text('Tenor Field Blank');
            }
        }

        function interestInput(){
            $rate_of_interest = $('#rate_of_interest').val();
            if($rate_of_interest != ''){
                $('#rate_of_interest_div').css('border', '1px solid green');
                $('#is_rate_of_interest_blank').text('');
            }else{
                $('#rate_of_interest_div').css('border', '1px solid red');
                $('#is_rate_of_interest_blank').text('Interest Field Blank');
            }
        }

</script>
@endsection()