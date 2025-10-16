@extends('backend.master')
@section('content')
@section('title') Role Edit @endsection
@section('roles') active @endsection
@section('roles.index') active @endsection
@section('styles')
@endsection
@section('content')


<!--begin::Toolbar-->
<div class="toolbar py-5 py-lg-15" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column me-3">
            <!--begin::Title-->
            <h1 class="d-flex text-white fw-bold my-1 fs-3">Role</h1>
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
                <li class="breadcrumb-item text-white opacity-75">Role</li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item">
                    <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item text-white opacity-75">Role Update</li>
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
                        <h2>New Role Create.</h2>
                    </div>
                    <!--end::Search-->
                </div>
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                        <!--begin::Add user-->
                        @can('role-create')
                            <a class="btn btn-secondary" href="{{ route('roles.index') }}">
                                Role List
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

                <form action="{{ route('roles.update', $role->id) }}" method="post" enctype="multipart/form-data">
					@method('PATCH')
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-5">
                                <label for="name" class="form-label">Role Name <span
                                        class=" text-danger">(required)</span> </label>
                                <input type="text" class="form-control"
                                    value="{{$role->name}}" id="name" placeholder="Role Name"
                                    required disabled>
								
									<input type="hidden" name="name" value="{{$role->name}}">

                                @error('name')
                                    <span class=text-danger>{{ $message }}</span>
                                @enderror

                            </div>
                        </div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="name">Permissions</label>
								<div class="form-check mt-5 mb-5">
									<input type="checkbox" class="form-check-input" id="checkPermissionAll"
									value="1" {{ App\Models\User::roleHasPermissions($role, $allPermissions) ? 'checked' : '' }}
									onclick="checkPermissionByGroup('allInput', this)">
									<label class="form-check-label" for="checkPermissionAll">All</label>
								</div>
								<hr class="mt-5" style="margin-bottom: 45px;">
								@php $i = 1; @endphp
								@foreach ($permissionGroups as $group)
								<div class="row allInput mt-5">
									@php
										$permissions = App\Models\User::getpermissionsByGroupName($group->name);
										$j = 1;
									@endphp
									
									<div class="col-3">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" id="{{ $i }}Management"
											value="{{ $group->name }}"
											onclick="checkPermissionByGroup('role-{{$i}}-management-checkbox', this)"
											{{ App\Models\User::roleHasPermissions($role, $permissions) ? 'checked' : '' }}>
											<label class="form-check-label" for="{{ $i }}Management">{{ Str::title($group->name) }}</label>
										</div>
									</div>
									<div class="col-9 role-{{ $i }}-management-checkbox">
										
										@foreach ($permissions as $permission)
										<div class="form-check mb-4">
											<input type="checkbox" class="form-check-input"
											onclick="checkSinglePermission('role-{{ $i }}-management-checkbox', '{{ $i }}Management',
											{{ count($permissions) }})" name="permissions[]"
											{{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
											id="checkPermission{{ $permission->id }}" value="{{ $permission->name }}">
											<label class="form-check-label" for="checkPermission{{ $permission->id }}">{{ $permission->name }}</label>
										</div>
										@php  $j++; @endphp
										@endforeach
										<br>
									</div>
								</div>
								@php  $i++; @endphp
								@endforeach
							</div>
						</div>
					</div>


                    <div class="row">
                        <div class="col-6">
                            <input type="submit" class="btn text-white" value="Update" style="background-color: #2F4F4F">
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

@section('scripts')
    @include('backend.userRole.roles.partial.script')
@endsection
