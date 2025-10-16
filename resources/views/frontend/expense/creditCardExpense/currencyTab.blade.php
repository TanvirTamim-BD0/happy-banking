@if($creditCardData->is_dual_currency == true)
<nav>
    <div class="nav nav-tabs custom-transfer-acccount-tab gap-2 btn-no-color" id="nav-tab" role="tablist">
        <button class="text-white primary_btn m-0 nav-link active" id="nav-bdt-tab" data-bs-toggle="tab"
            data-bs-target="#nav-bdt" type="button" role="tab" aria-controls="nav-bdt" aria-selected="true" 
            onclick="changeCurrency('BDT Currency')">BDT
            Currency </button>
        <button class="text-white primary_btn m-0 nav-link" id="nav-usd-tab" data-bs-toggle="tab" data-bs-target="#nav-usd"
            type="button" role="tab" aria-controls="nav-usd" aria-selected="false" onclick="changeCurrency('USD Currency')">USD Currency </button>

    </div>
</nav>

<input type="hidden" id="currencyType" name="currency_type" value="BDT Currency">
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-bdt" role="tabpanel" aria-labelledby="nav-bdt-tab">

        <div class="form-group custom-form-group mt-2">
            <label class="custom-form-label" for="email">BDT Amount <span class="custom-danger">(requierd)</span></label>
            <div class="single_input">
                <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                <input name="amount_bdt" type="number" id="amount_bdt" placeholder="BDT Amount" step="0.01">
            </div>
        </div>

    </div>

    <div class="tab-pane fade" id="nav-usd" role="tabpanel" aria-labelledby="nav-usd-tab">

        <div class="form-group custom-form-group mt-2">
            <label class="custom-form-label" for="email">USD Amount <span class="custom-danger">(requierd)</span></label>
            <div class="single_input">
                <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                <input name="amount_usd" type="number" id="amount_usd" placeholder="USD Amount" step="0.01"
                onblur="converUSDToBDTCurrency()">
            </div>
        </div>

        <div class="form-group custom-form-group">
            <label class="custom-form-label" for="email">1 USD In BDT <span class="custom-danger">(requierd)</span></label>
            <div class="single_input ">
                <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                <input name="usd_in_bdt_rate" type="number" id="usd_in_bdt_rate" step="0.01" placeholder="1 USD In BDT"
                    onblur="converUSDToBDTCurrency()">
        
                @error('usd_in_bdt_rate')
                <span class="custom-text-danger custom-text-danger-position">{{$message}}</span>
                @enderror
            </div>
        </div>
        
        <div class="form-group custom-form-group">
            <label class="custom-form-label" for="email">Converted BDT Amount <span
                    class="custom-danger">(requierd)</span></label>
            <div class="single_input ">
                <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                <input class="custom-readonly-color" name="pay_amount_convert_bdt" type="number" id="pay_amount_convert_bdt"
                    placeholder="Converted BDT Amount" value="0.00" readonly>
        
                @error('pay_amount_convert_bdt')
                <span class="custom-text-danger custom-text-danger-position">{{$message}}</span>
                @enderror
            </div>
        </div>

    </div>
</div>
@else
<div class="form-group custom-form-group">
    <label class="custom-form-label" for="email">Amount <span class="custom-danger">(requierd)</span></label>
    <div class="single_input">
        <i class="fa-solid fa-bangladeshi-taka-sign"></i>
        <input name="amount" type="number" id="amount" placeholder="BDT Amount" step="0.01">
    </div>
</div>

@endif

<script>
    //To change currency...
    function changeCurrency(currencyType){
        $("#currencyType").val(currencyType);
    }

    //To convert usd amount to bdt amount...
    function converUSDToBDTCurrency() {
        var usdAmount = $('#amount_usd').val();
        if(usdAmount != ''){
            var BDTRate = $('#usd_in_bdt_rate').val();
            var totalBdtAmount = (usdAmount * BDTRate).toFixed(2);
            $('#pay_amount_convert_bdt').val(totalBdtAmount);
        }else{
            toastr.error('First fillup USD amount.!');
            $('#usd_in_bdt_rate').val('');
        }
    }
</script>