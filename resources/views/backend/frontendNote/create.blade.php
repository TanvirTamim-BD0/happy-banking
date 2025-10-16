@extends('backend.master')
@section('title') Bank System | Note Create @endsection
@section('styles')
@endsection
@section('content')

<div class="content">
  <div class="container-fluid">
    <div class="top_post d-flex align-items-center justify-content-between mb-3">
      <div class="d-flex align-items-center">
        <h3>Add New Note<span class="divider"> |</span></h3><span class="nav_indicator"><a href="./index.html">Home</a>
          > Add New Note</span>
      </div>
    </div>

    <form action="{{route('frontend-note.store')}}" method="post">
      @csrf
      <div class="row">
        <div class="col-md-9 mb-3">

          <!-- <form action="" class="mb-3"> -->
          <div class="text_input mb-3">
            <div class="form-group">
              <label class="h5" for="description">Add new Note</label>
              <textarea name="description" class="form-control" id="description" cols="30" rows="10"></textarea>
              <span id="warnTextDesc" class="text-danger warn">Description is required</span>
            </div>
          </div>
          <!-- </form> -->

        </div> <!-- col-md-8 end-->

        <div class="col-md-3 mb-3">

          <div class="card custom-frontend-note-publish-card">
            <div onclick="toggleCard('card_body_x')" class="card-header">
              <div class="d-flex align-items-center justify-content-between cursor-pointer">
                <h5 class="dash-title">Publish</h5>
                <button class="border-0 custom-publish-button-icon"><span class="dropdown-toggle"></span></button>
              </div>
            </div>
            <div id="card_body_x" class="card-body">

              <div class="row">

                <div class="col-md-12 mb-3 custom-select2-dropdown">
                  <label for="description_type" class="fw-bolder mb-1">Select Category <span
                      class="text-danger custom-required-font">(Required)</span></label>
                  <select id="description_type" class="form-control w-100" name="description_type" required>

                    <option value="" selected disabled>Select NoteType</option>

                    @foreach($getArrayData as $item)
                    @if(isset($item) && $item != null)
                    <option value="{{$item}}">{{$item}}</option>
                    @endif
                    @endforeach

                  </select>

                  @error('description_type')
                  <span class=text-danger>{{ $message }}</span>
                  @enderror
                </div>


                <div style="background-color: rgb(0 0 0 / 3%);" class="card-footer px-0 text-center">
                  <button onclick="return validate();" type="submit" class="table_button add btn w-75 mt-3">Publish</button>
                </div>

              </div>
            </div>
          </div>

        </div>

      </div>

    </form>
  </div>
  <!--row end-->

</div>
</main>
</div>


@endsection

@section('scripts')
<script>
  $(document).ready(function() {
        $('#type').select2();
        $('#description_type').select2();
    });
</script>


<script>
  function validate() {
        if ((tinymce.EditorManager.get('description').getContent()) == '') {
            document.getElementById('warnTextDesc').classList.add('active')
            return false;
        }
      }
</script>
@endsection