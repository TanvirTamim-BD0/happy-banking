
<script src="{{asset('backend')}}/assets/js/script.js"></script>
<script src="{{asset('backend')}}/assets/js/jquery.min.js"></script>
<script src="{{asset('backend')}}/assets/js/data_table/jquery.dataTables.min.js"></script>
<script src="{{asset('backend')}}/assets/js/data_table/dataTables.bootstrap5.min.js"></script>
<script src="{{asset('backend')}}/assets/js/tinymce.min.js"></script>
<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>
<script src="{{asset('backend')}}/assets/js/data_table/dataTable.js"></script>
<script src="{{asset('backend')}}/assets/js/flatpickr.min.js"></script>
<script>
    flatpickr("input[type=date]", {
        dateFormat: "Y-m-d",
      });
      flatpickr("input[type=time]", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i K",
      });
</script>

<script>
    flatpickr("#from_date", {
        dateFormat: "d-m-Y",
        allowInput: true
      });
   
      flatpickr("#to_date", {
        dateFormat: "d-m-Y",
        allowInput: true
      });
</script>


<!--end::Custom Javascript-->
<script src="{{asset('backend')}}/custom/js/toastr.min.js"></script>
{!! Toastr::message() !!}

<script src="{{asset('backend')}}/custom/js/tinymce.min.js"></script>
<script src="{{asset('backend')}}/custom/js/select2.min.js"></script>

<script>
	var editor_config = {
            path_absolute : "/",
            selector: "#description",
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "emoticons template paste textcolor colorpicker textpattern"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
            relative_urls: false,
            file_browser_callback : function(field_name, url, type, win) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

                var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
                if (type == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.open({
                    file : cmsURL,
                    title : 'Filemanager',
                    width : x * 0.8,
                    height : y * 0.8,
                    resizable : "yes",
                    close_previous : "no"
                });
            }
        };

        tinymce.init(editor_config);
</script>