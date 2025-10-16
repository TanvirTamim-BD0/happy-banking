@extends('backend.master')
@section('title') Bank System | Mobile Wallet Create @endsection
@section('styles')
<style>
</style>
@endsection
@section('content')


<div class="content tools">
    <main>
        <section>
            <div class="container-fluid mt-3">

                <div class="align-items-center pb-2 pt-2">
                    <h3 class="breadcump-header">Mobile Wallet <span class="divider"></span></h3>
                    <span class="nav_indicator"><a href="{{route('home')}}" class="breadcump-text">Home</a> > <span
                            class="breadcump-active"> Create</span></span>
                </div>


                <div class="card_wrap">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="card-body card p-3">
                                <div class="card-content custom-card-content-for-datatable pb-5 pt-2">
                                    <h2 class="card-title">Mobile Wallet Create</h2>
                                    <div class="float-right justify-content-end">
                                        <a class="custom-datatable-add-btn" href="{{route('mobile-wallet.index')}}">
                                            <i class="fa fa-list"></i> Wallet List
                                        </a>
                                    </div>
                                </div>

                                <form class="passShowForm" action="{{route('mobile-wallet.store')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-2 custom-select2-dropdown">
                                            <div class="single_input">
                                                <label for="mobile_wallet_name" class="fw-bolder mb-1">Mobile Wallet Name <span
                                                        class="text-danger custom-required-font">(Required)</span></label>
                                                <input onblur="inputValidator(this.value,'warnTextName','text')" placeholder="Wallet Name" class="form-control"
                                                    type="text" name="mobile_wallet_name" id="mobile_wallet_name" required>
                                                <span id="warnTextName" class="text-danger warn">Name is required</span>
                                            
                                                @error('mobile_wallet_name')
                                                <span class=text-danger>{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                       
                                        <div class="col-md-6 mb-2">
                                            <div class="single_input">
                                                <label for="parent_company" class="fw-bolder mb-1">Parent Comapny Name <span class="text-danger custom-required-font">(Required)</span></label>
                                                <input onblur="inputValidator(this.value,'warnTextComapny','text')" placeholder="Parent Comapny" class="form-control" type="text"
                                                    name="parent_company" id="parent_company" required>
                                                <span id="warnTextComapny" class="text-danger warn">Company is required</span>

                                                @error('parent_company')
                                                <span class=text-danger>{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-12 mb-2">
                                            <label for="uploadImage" class="fw-bolder mb-1">Wallet Image <span
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
@endsection()