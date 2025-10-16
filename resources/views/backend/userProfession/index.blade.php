@extends('backend.master')
@section('title') Bank System | Profession List @endsection
@section('styles')
@endsection
@section('content')

<div id="content" class="content filter_3">
    <main>

        <div class="align-items-center p-2">
            <h3 class="breadcump-header">Profession <span class="divider"></span></h3>
            <span class="nav_indicator"><a href="{{route('home')}}" class="breadcump-text">Home</a> > <span class="breadcump-active"> List</span></span>
        </div>

        <!-- table section -->
        <div class="container-fluid">
            <section class="row">
                <div class="col-lg-12 table_wrap hide_scrollbar table_data">
                    <div class="card-body card" style="padding-bottom: 5px;">

                        <div class="card-content custom-card-content-for-datatable pb-3 pt-2">
                            <h2 class="card-title">Profession List</h2>
                            <div class="float-right justify-content-end">
                                <a class="custom-datatable-add-btn"
                                    href="#" data-bs-toggle="modal" data-bs-target="#addProfessionData">
                                    <i class="fa fa-plus"></i> Add Profession
                                </a>
                            </div>
                        </div>

                        <table id="example" class="table table-bordered table-striped table-responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Profession Name</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userProfessionData as $item)
                                    @if(isset($item) && $item != null)
                                        <tr>
                                           
                                            <td>
                                                <div class="row_title">
                                                    <strong><a href="#">{{$item->profession_name}}</a></strong>
                                                </div>
                                                <div class="row-actions">
                                                    <span><a href="#" data-bs-toggle="modal" data-bs-target="#update{{$item->id}}">Edit </a> | </span>
                                                    <span>
                                                        <a href="{{route('profession.destroy', $item->id)}}">
                                                            <span class="trash">Trash</span> 
                                                        </a>
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <b>Date: </b>{{Carbon\Carbon::parse($item->create_at)->format('d-m-Y')}}
                                            </td>
                                            
                                        </tr>

                                        <!-- {{-- //To update the Profession data... --}} -->
                                        <div class="modal fade" id="update{{$item->id}}" tabindex="-1" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered mw-850px">
                                                <div class="modal-content rounded">
                                                    <div class="modal-header border-0 justify-content-between">
                                                        <h2 class="modal_head">Update Profession</h2>
                                                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                                            <span class="svg-icon svg-icon-1">
                                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                                                        transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                                                        fill="currentColor"></rect>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                                                        <form class="gy-1 pt-75" action="{{route('profession.update', $item->id)}}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('put')

                                                            <div class="row mt-4">
                                                            
                                                                <div class="col-md-12 mb-3">
                                                                    <div class="single_input">
                                                                        <label for="profession_name" class="fw-bolder mb-1">Profession Name <span
                                                                                class="text-danger custom-required-font">(Required)</span></label>
                                                                        <input onblur="inputValidator(this.value,'warnTextComapny','text')" placeholder="Profession Name"
                                                                            class="form-control" type="text" name="profession_name" id="profession_name"
                                                                            value="{{$item->profession_name}}" required>
                                                                        <span id="warnTextComapny" class="text-danger warn">Profession is required</span>
                                                            
                                                                        @error('profession_name')
                                                                        <span class=text-danger>{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                        
                                                            <div class="text-end mt-4">
                                                                <button type="submit" id="kt_modal_new_target_submit" class="btn btn-primary">Update</button>
                                                                <button type="reset" id="kt_modal_new_target_cancel" data-bs-dismiss="modal"
                                                                    class="btn btn-light me-3">Cancel</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Profession Name</th>
                                    <th>Created</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </section>
        </div>


        {{-- //To create a new Profession... --}}
        <div class="modal fade" id="addProfessionData" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-850px">
                <div class="modal-content rounded">
                    <div class="modal-header border-0 justify-content-between">
                        <h2 class="modal_head">Add Profession</h2>
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                            <span class="svg-icon svg-icon-1">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                        transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                        fill="currentColor"></rect>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                        <form class="gy-1 pt-75" action="{{route('profession.store')}}" method="POST" enctype="multipart/form-data">

                            @csrf
                            <div class="row mt-4">
                                <div class="col-md-12 mb-3 custom-select2-dropdown">
                                    <div class="single_input">
                                        <label for="profession_name" class="fw-bolder mb-1">Profession Name <span
                                                class="text-danger custom-required-font">(Required)</span></label>
                                        <input onblur="inputValidator(this.value,'warnTextName','text')" placeholder="Profession Name"
                                            class="form-control" type="text" name="profession_name" id="profession_name" required>
                                        <span id="warnTextName" class="text-danger warn">Profession is required</span>
                            
                                        @error('profession_name')
                                        <span class=text-danger>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" id="kt_modal_new_target_submit" class="btn btn-primary">Submit</button>
                                <button type="reset" id="kt_modal_new_target_cancel" data-bs-dismiss="modal"
                                    class="btn btn-light me-3">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

@endsection