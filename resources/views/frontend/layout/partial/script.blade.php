<!-- javascript -->
<script src="{{asset('frontend')}}/js/jquery-3.6.4.min.js"></script>
<script src="{{asset('frontend')}}/js/script.js"></script>
<script src="{{asset('frontend')}}/js/bootstrap.js"></script>
<script src="{{asset('frontend')}}/js/select2.min.js"></script>
<script src="{{asset('frontend')}}/js/flatpickr.min.js"></script>
<script>
    flatpickr("#billing_date", {
        dateFormat: "d-m-Y",
        allowInput: true
      });

      flatpickr("input[type=time]", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i K",
      });
</script>

<script>
    flatpickr("#last_payment_date", {
        dateFormat: "d-m-Y",
        allowInput: true
      });

      flatpickr("input[type=time]", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i K",
      });
</script>

<!--end::Custom Javascript-->
<script src="{{asset('frontend')}}/js/toastr.min.js"></script>
{!! Toastr::message() !!}

<script>
  //To get beneficiary bank data...
    $(document).ready(function() {
        var url = "{{ route('webuser.user-activity-total-hit-increase') }}";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'get',
            url: url,
            success: function (data) {
                //
                console.log('asd');
            }

        });
    
    });
</script>