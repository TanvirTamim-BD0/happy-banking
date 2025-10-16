@extends('backend.master')
@section('title') User Create @endsection
@section('styles')
<style>
</style>
@endsection
@section('content')

<!--begin::Toolbar-->
<!--begin::Container-->
<div class="toolbar py-5 py-lg-15" id="kt_toolbar">
    <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column me-3">
            <!--begin::Title-->
            <h1 class="d-flex text-white fw-bold my-1 fs-3">User</h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-1">
                <!--begin::Item-->
                <li class="breadcrumb-item text-white opacity-75">
                    <a href="{{route('home')}}" class="text-white text-hover-primary">Home</a>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item">
                    <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item text-white opacity-75">User</li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item">
                    <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item text-white opacity-75">User Create</li>
                <!--end::Item-->
            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
        <!--begin::Actions-->
        <div class="d-flex align-items-center py-3 py-md-1">
            <!--begin::Button-->
            <a href="{{ route('home') }}" data-theme="light"
                class="btn btn-custom btn-active-white btn-flex btn-color-white btn-active-color-primary fw-bold custom-back-home"><i
                    class="fas fa-arrow-left "></i> Back To Home</a>
            <!--end::Button-->
        </div>
        <!--end::Actions-->
    </div>
    <!--end::Container-->
</div>
<!--end::Toolbar-->

<!--begin::Container-->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <h2>New User Create.</h2>
                    </div>
                    <!--end::Search-->
                </div>
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                        <!--begin::Add user-->
                        @can('user-create')
                            <a class="btn btn-secondary" href="{{ route('users.index') }}">
                                User List 
							<i class="fas fa-users" style="padding-left: 10px;"></i></a>
                        @endcan
                        <!--end::Add user-->
                    </div>
                    <!--end::Toolbar-->
                    <!--begin::Group actions-->
                    <div class="d-flex justify-content-end align-items-center d-none"
                        data-kt-user-table-toolbar="selected">
                        <div class="fw-bold me-5">
                            <span class="me-2" data-kt-user-table-select="selected_count"></span>Selected</div>
                        <button type="button" class="btn btn-danger" data-kt-user-table-select="delete_selected">Delete
                            Selected</button>
                    </div>
                    <!--end::Group actions-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->


            <!--begin::Card body-->
            <div class="card-body py-4">

                <form action="{{ route('users.store') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="name" class="form-label">User Name <span
                                        class=" text-danger">(required)</span> </label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ old('name') }}" id="name"
                                    placeholder="User Name" required>

                                @error('name')
                                    <span class=text-danger>{{ $message }}</span>
                                @enderror

                            </div>
                        </div>
                        
						<div class="col-md-6">
                            <div class="mb-5">
                                <label for="email" class="form-label">User Email <span
                                        class=" text-danger">(required)</span> </label>
                                <input type="email" class="form-control" name="email"
                                    value="{{ old('email') }}" id="email"
                                    placeholder="User Email" required>

                                @error('email')
                                    <span class=text-danger>{{ $message }}</span>
                                @enderror

                            </div>
                        </div>
						
						<div class="col-md-6">
                            <div class="mb-5">
                                <label for="mobile" class="form-label">User Phone <span
                                        class=" text-danger">(required)</span> </label>
                                <input type="text" class="form-control" name="mobile"
                                    value="{{ old('mobile') }}" id="mobile"
                                    placeholder="User Phone" required>

                                @error('mobile')
                                    <span class=text-danger>{{ $message }}</span>
                                @enderror

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="role" class="form-label">User Role <span
                                        class=" text-danger">(required)</span> </label>
                                <select class="form-select" name="roles" data-control="select2"
                                    data-placeholder="Select Role" required>
                                    <option value="" selected>Select Role</option>
                                    @foreach($roles as $item)
                                        <option value="{{ $item->id }}">{{ Str::title($item->name) }}</option
                                            required>
                                    @endforeach
                                </select>

                                @error('role')
                                    <span class=text-danger>{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="password" class="form-label">Password <span
                                        class=" text-danger">(required)</span> </label>
                                <input type="password" class="form-control" name="password"
                                    value="{{ old('password') }}" id="password"
                                    placeholder="Enter Password" required>

                                @error('password')
                                    <span class=text-danger>{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
						<div class="col-md-6">
                            <div class="mb-5">
                                <label for="password_confirmation" class="form-label">Confirm Password <span
                                        class=" text-danger">(required)</span> </label>
                                <input type="password" class="form-control" name="password_confirmation"
                                    value="{{ old('password_confirmation') }}" id="password_confirmation"
                                    placeholder="Confirm Passowrd" required>

                                @error('password_confirmation')
                                    <span class=text-danger>{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

						<div class="col-md-9">
                            <div class="mb-5">
                                <label for="description" class="form-label">Product Details</label>
                                <textarea type="text" class="form-control" name="product_details"
                                    value="{{ old('product_details') }}" id="description"
                                    placeholder="Enter Product Details" cols="10" rows="7"></textarea>

                                @error('product_details')
                                    <span class=text-danger>{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

						<div class="col-md-3 text-right float-right">
                                <div class="fv-row mb-5">
                                    <!--begin::Label-->
                                    <label class="d-block fs-6 mb-2">Product Image </label>
                                    <!--end::Label-->
                                    <!--begin::Image input-->
                                    <div class="image-input image-input-outline" data-kt-image-input="true"
                                        style="background-image: url('../backend/assets/media/svg/avatars/blank.svg')">
                                        <!--begin::Preview existing avatar-->
                                        <div class="image-input-wrapper w-125px h-125px"
                                            style="background-image: url(../backend/assets/media/avatars/Html5.png);">
                                        </div>
                                        <!--end::Preview existing avatar-->
                                        <!--begin::Label-->
                                        <label
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Image">
                                            <i class="bi bi-pencil-fill fs-7"></i>
                                            <!--begin::Inputs-->
                                            <input type="file" name="image" accept=".png, .jpg, .jpeg" />
                                            <input type="hidden" name="avatar_remove" />
                                            <!--end::Inputs-->
                                        </label>

                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                            title="Cancel avatar">
                                            <i class="bi bi-x fs-2"></i>

                                            <span
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                title="Remove avatar">
                                                <i class="bi bi-x fs-2"></i>
                                            </span>

                                    </div>

                                    <div class="form-text">Allowed file types: png, jpg, jpeg.</div>

                                    @error('product_image')
                                        <span class=text-danger>{{ $message }}</span>
                                    @enderror
                                </div>
                        </div>

                        

                        

                    </div>


                    <div class="row">
                        <div class="col-6">
                            <input type="submit" class="btn text-white" style="background-color: #2F4F4F">
                        </div>
                    </div>

                </form>

            </div>
            <!--end::Card body-->


        </div>
        <!--end::Card-->
    </div>
    <!--end::Post-->
</div>
<!--end::Container-->

@endsection
