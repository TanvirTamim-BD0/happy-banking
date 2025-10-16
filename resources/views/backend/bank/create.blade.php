@extends('backend.master')
@section('title') Bank System | Bank Create @endsection
@section('styles')
<style>
</style>
@endsection
@section('content')


<div class="content tools">
    <main>
        <section>
            <div class="container-fluid mt-3">

                <div class="top_post d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center">
                        <h3>Bank Create<span class="divider"> |</span></h3><span class="nav_indicator"><a
                                href="./index.html">Home</a> > Create</span>
                    </div>
                </div>
                <div class="card_wrap">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="card-body card p-3">
                                <form class="passShowForm" action="{{route('banks.store')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-2 custom-select2-dropdown">
                                            <label for="bank_type" class="fw-bolder mb-1">Select Bank Type <span class="text-danger custom-required-font">(Required)</span></label>
                                            <select id="bank_type" class="form-control w-100" name="bank_type" required>
                                                <option value="" selected disabled>Select Bank Type</option>
                                                <option value="State-owned commercial banks (SOCBs)">State-owned commercial banks (SOCBs)</option>
                                                <option value="Specialized banks (SDBs)">Specialized banks (SDBs)</option>
                                                <option value="Private commercial banks (PCBs)">Private commercial banks (PCBs)</option>
                                                <option value="Islami Shariah Based PCBs">Islami Shariah Based PCBs</option>
                                                <option value="Foreign commercial banks (FCBs)">Foreign commercial banks (FCBs)</option>
                                                <option value="Non-scheduled banks">Non-scheduled banks</option>
                                            </select>

                                            @error('bank_type')
                                            <span class=text-danger>{{ $message }}</span>
                                            @enderror
                                        </div>
                                       
                                        <div class="col-md-6 mb-2">
                                            <div class="single_input">
                                                <label for="bank_name" class="fw-bolder mb-1">Bank Name <span class="text-danger custom-required-font">(Required)</span></label>
                                                <input onblur="inputValidator(this.value,'warnTextName','text')" placeholder="Bank Name" class="form-control" type="text"
                                                    name="bank_name" id="bank_name" required>
                                                <span id="warnTextName" class="text-danger warn">Name is required</span>

                                                @error('bank_name')
                                                <span class=text-danger>{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-12 mb-2">
                                            <label for="uploadImage" class="fw-bolder mb-1">Bank Image <span
                                                    class="text-danger custom-required-font">(Required)</span></label>
                                            <div class="select_imgWith_preview">
                                                <img id="uploadPreview" src="{{asset('backend')}}/assets/images/img_preview.png" />
                                                <input id="uploadImage" type="file" name="image" onchange="PreviewImage('uploadImage','uploadPreview');" />
                                                <i onclick="cancelPreview('uploadPreview')" class="fa-sharp fa-solid fa-xmark cross_mark"></i>
                                            </div>

                                            @error('image')
                                            <span class=text-danger>{{ $message }}</span>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-12 mb-2 mt-3">
                                            <div class="single_input">
                                                <input class="btn btn-primary" type="submit" value="Submit">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#bank_type').select2();
    });
</script>
@endsection