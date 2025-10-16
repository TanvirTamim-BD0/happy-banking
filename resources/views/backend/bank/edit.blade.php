@extends('backend.master')
@section('title') Bank System | Bank Update @endsection
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
                        <h3>Bank Update<span class="divider"> |</span></h3><span class="nav_indicator"><a
                                href="./index.html">Home</a> > Update</span>
                    </div>
                </div>
                <div class="card_wrap">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="card-body card p-3">
                                <form class="passShowForm" action="{{route('banks.update', $singleBankData->id)}}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('put')

                                    <div class="row">
                                        <div class="col-md-6 mb-2 custom-select2-dropdown">
                                            <label for="bank_type" class="fw-bolder mb-1">Select Bank Type <span
                                                    class="text-danger custom-required-font">(Required)</span></label>
                                            <select id="bank_type" class="form-control w-100" name="bank_type" required>
                                                <option value="" selected disabled>Select Bank Type</option>
                                                <option value="State-owned commercial banks (SOCBs)"  
                                                    {{ $singleBankData->bank_type == "State-owned commercial banks (SOCBs)" ? 'selected' : '' }}>State-owned commercial banks (SOCBs)</option>
                                                <option value="Specialized banks (SDBs)" 
                                                    {{ $singleBankData->bank_type == "Specialized banks (SDBs)" ? 'selected' : '' }}>Specialized banks (SDBs)</option>
                                                <option value="Private commercial banks (PCBs)" 
                                                    {{ $singleBankData->bank_type == "Private commercial banks (PCBs)" ? 'selected' : '' }}>Private commercial banks (PCBs)</option>
                                                <option value="Islami Shariah Based PCBs" 
                                                    {{ $singleBankData->bank_type == "Islami Shariah Based PCBs" ? 'selected' : '' }}>Islami Shariah Based PCBs</option>
                                                <option value="Foreign commercial banks (FCBs)" 
                                                    {{ $singleBankData->bank_type == "Foreign commercial banks (FCBs)" ? 'selected' : '' }}>Foreign commercial banks (FCBs)</option>
                                                <option value="Non-scheduled banks" 
                                                    {{ $singleBankData->bank_type == "Non-scheduled banks" ? 'selected' : '' }}>Non-scheduled banks</option>
                                            </select>
                                        
                                            @error('bank_type')
                                            <span class=text-danger>{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <div class="single_input">
                                                <label for="bank_name" class="fw-bolder mb-1">Bank Name <span
                                                        class="text-danger custom-required-font">(Required)</span></label>
                                                <input onblur="inputValidator(this.value,'warnTextName','text')"
                                                    placeholder="Bank Name" class="form-control" type="text"
                                                    name="bank_name" id="bank_name" value="{{$singleBankData->bank_name}}" required>
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
                                                @if(isset($singleBankData->image) && $singleBankData->image != null)
                                                    <img id="uploadPreview" src="{{asset('backend/uploads/bankImage/'.$singleBankData->image)}}" />
                                                @else
                                                    <img id="uploadPreview" src="{{asset('backend')}}/assets/images/img_preview.png" />
                                                @endif
                                                <input id="uploadImage" type="file" name="image" onchange="PreviewImage('uploadImage','uploadPreview');" />
                                                <i onclick="cancelPreview('uploadPreview')" class="fa-sharp fa-solid fa-xmark cross_mark"></i>
                                            </div>
                                        
                                            @error('image')
                                            <span class=text-danger>{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-12 mb-2 mt-3">
                                            <div class="single_input">
                                                <input class="btn btn-primary" type="submit" value="Update">
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