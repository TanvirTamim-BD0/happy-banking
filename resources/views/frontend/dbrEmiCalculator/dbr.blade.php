@extends('frontend.master')
@section('title') DBR Calculator @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container">

    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <form class="card mb-3" action="#"
                method="#">
                @csrf
                <h4 class="text-center heading_text">Debt Burden Ratio Calculator</h4>
                <h6 class="text-center pb-3">Work With Total Credit Card Limit</h6>
                <div class="form-group custom-form-group">
                <label class="custom-form-label" for="salary">Salary <span class="custom-danger">(requierd)</span></label>
                <div class="single_input" id="salary_div">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                    <input onblur="SalaryInput()" name="salary" type="number" id="salary" placeholder="Enter Salary"  required min="0" max="999999999">
                </div>
                    <div id="is_salary_blank" class="text-danger mt-1">
                    </div>
                </div>

                <div class="form-group custom-form-group">
                <label class="custom-form-label" for="loan_emi">Loan EMI <span class="custom-danger ">(requierd)</span></label>
                <div class="single_input" id="loan_emi_div">
                    <i class="fa-solid fa-sack-dollar"></i>
                    <input onblur="LoanEMIInput()" name="loan_emi" type="number" id="loan_emi" placeholder="Enter EMI" min="0" max="999999999">
                </div>
                <div id="is_loan_emi_blank" class="text-danger mt-1">
                </div>
                </div>

                <div class="form-group custom-form-group">
                <label class="custom-form-label" for="credit_card_limit">Credit Card Limit<span class="custom-danger">(requierd)</span></label>
                <div class="single_input" id="credit_card_limit_div">
                    <i class="fa-solid fa-dollar-sign"></i>
                    <input onblur="creditCardLimit()" name="credit_card_limit" type="number" id="credit_card_limit" placeholder="Enter Credit Card Limit" min="0" max="999999999">
                </div>
                <div id="is_credit_card_limit_blank" class="text-danger mt-1">
                </div>
                </div>
                

    
                <div class="result  bg-success rounded-2  mb-2 " id="dbrResult">
                    {{-- <p class="px-2 py-3 text-white text-center fs-2">DBR : 37%</p> --}}
                </div>
                <span onclick="dbrCalculate()" class="primary_btn mb-1 cursor-pointer">DBR Calculate</span>
            </form>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>

          
        function dbrCalculate(){
            // $salary = Number($('#salary').val());
            // $loan_emi = Number($('#loan_emi').val());
            // $credit_card_limit = Number($('#credit_card_limit').val());

            $salary = $('#salary').val();
            $loan_emi = $('#loan_emi').val();
            $credit_card_limit = $('#credit_card_limit').val();


            if($salary != ''){
                if($salary > 0 && $salary <= 9999999){
                    $salary = Number($('#salary').val());
                    $('#salary_div').css('border', '1px solid green');
                    $('#is_salary_blank').text('');
                    if($loan_emi != ''){
                        if($loan_emi >= 0 && $loan_emi <= 99999999){
                            $loan_emi = Number($('#loan_emi').val());
                            $('#loan_emi_div').css('border', '1px solid green');
                            $('#is_loan_emi_blank').text('');
                            if($credit_card_limit != ''){
                                if($credit_card_limit >= 0 && $credit_card_limit <= 99999999){
                                    $credit_card_limit = Number($('#credit_card_limit').val());
                                    $('#credit_card_limit_div').css('border', '1px solid green');
                                    $('#is_credit_card_limit_blank').text('');
                                    $calculate1 = (($credit_card_limit * 5) / 100);
                                    $calculate2 = (($loan_emi)+ $calculate1);
                                    $calculate3 = (($calculate2)/ $salary) *100;
                                    $('#dbrResult').html('<p class="px-2 py-3 text-white text-center fs-2">DBR : '+$calculate3.toFixed(2)+'%'+'</p>')
                                }else{
                                    $('#credit_card_limit_div').css('border', '1px solid red');
                                    $('#is_credit_card_limit_blank').text('Credit Card Limit should be 0 to 99999999');
                                }
                            }else{
                                $('#credit_card_limit_div').css('border', '1px solid red');
                                $('#is_credit_card_limit_blank').text('Credit Card Limit Blank');
                            }
                        }else{
                            $('#loan_emi_div').css('border', '1px solid red');
                            $('#is_loan_emi_blank').text('Loan EMI should be 0 to 99999999');
                        }
                    }else{
                        $('#loan_emi_div').css('border', '1px solid red');
                        $('#is_loan_emi_blank').text('Loan EMI Blank');
                    }
                }else{
                    $('#salary_div').css('border', '1px solid red');
                    $('#is_salary_blank').text('Salary should be 1 to 9999999');
                }
            }else{
                $('#salary_div').css('border', '1px solid red');
                $('#is_salary_blank').text('Salary Blank');

            }
        }

        function SalaryInput(){
            $salary = $('#salary').val();
            if($salary != ''){
                $('#salary_div').css('border', '1px solid green');
                $('#is_salary_blank').text('');
            }else{
                $('#salary_div').css('border', '1px solid red');
                $('#is_salary_blank').text('Salary Blank');
            }
        }

        function LoanEMIInput(){
            $loan_emi = $('#loan_emi').val();
            if($loan_emi != ''){
                $('#loan_emi_div').css('border', '1px solid green');
                $('#is_loan_emi_blank').text('');
            }else{
                $('#loan_emi_div').css('border', '1px solid red');
                $('#is_loan_emi_blank').text('Loan EMI Blank');
            }
        }

        function creditCardLimit(){
            $credit_card_limit = $('#credit_card_limit').val();
            if($credit_card_limit != ''){
                $('#credit_card_limit_div').css('border', '1px solid green');
                $('#is_credit_card_limit_blank').text('');
            }else{
                $('#credit_card_limit_div').css('border', '1px solid red');
                $('#is_credit_card_limit_blank').text('Credit Card Limit Blank');
            }
        }

 </script>
@endsection()