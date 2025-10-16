@extends('backend.master')
@section('title') Bank System | Blog Create @endsection
@section('styles')
@endsection
@section('content')

<div class="content">
  <div class="container-fluid">
    <div class="top_post d-flex align-items-center justify-content-between mb-3">
      <div class="d-flex align-items-center">
        <h3>Add New Post<span class="divider"> |</span></h3><span class="nav_indicator"><a href="./index.html">Home</a>
          > Add New Post</span>
      </div>
    </div>

    <form action="{{route('blog.store')}}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="row">
        <div class="col-md-9 mb-3">

          <!-- <form action="" class="mb-3"> -->
          <div class="text_input mb-3">
            <div class="form-group mb-2">
              <label class="h5" for="description">Title</label>
              <input onblur="inputValidator(this.value,'warnTextName','text')" required type="text" class="form-control"
                placeholder="Add title" name="title" id="title">
              <span id="warnTextName" class="text-danger warn">Title is required</span>
            </div>

            <div class="form-group">
              <label class="h5" for="description">Add new post</label>
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
                  <label for="type" class="fw-bolder mb-1">Select Type <span
                      class="text-danger custom-required-font">(Required)</span></label>
                  <select id="type" class="form-control w-100" name="type" required>

                    <option value="" selected disabled>Select Type</option>
                    <option value="frontend">Frontend Blog</option>
                    <option value="faq">Frontend Faq</option>

                  </select>

                  @error('type')
                  <span class=text-danger>{{ $message }}</span>
                  @enderror
                </div>

                <div class="col-md-12 mb-3 custom-select2-dropdown">
                  <label for="blog_category_id" class="fw-bolder mb-1">Select Blog Category <span
                      class="text-danger custom-required-font">(Required)</span></label>
                  <select id="blog_category_id" class="form-control w-100" name="blog_category_id" required>

                    <option value="" selected disabled>Select Category</option>

                    @foreach($blogCategoryData as $item)
                    @if(isset($item) && $item != null)
                    <option value="{{$item->id}}">{{$item->blog_category_name}}</option>
                    @endif
                    @endforeach

                  </select>

                  @error('blog_category_id')
                  <span class=text-danger>{{ $message }}</span>
                  @enderror
                </div>


                <div id="card_body_5" class="card-body my-2 mb-3 position-relative">
                  <div style="width:100%; height:200px" class="select_imgWith_preview">
                    <img id="uploadPreview" src="{{asset('backend')}}/assets/images/img_preview.png" />
                    <input id="uploadImage" type="file" name="image"
                      onchange="PreviewImage('uploadImage','uploadPreview');" />
                    <i onclick="cancelPreview('uploadPreview')" class="fa-sharp fa-solid fa-xmark cross_mark"></i>
                  </div>
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
        $('#blog_category_id').select2();
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