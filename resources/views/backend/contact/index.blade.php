@extends('backend.master')
@section('title') Contact List @endsection
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
            <h1 class="d-flex text-white fw-bold my-1 fs-3">Contact</h1>
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
                <li class="breadcrumb-item text-white opacity-75">Contact</li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item">
                    <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item text-white opacity-75">Contact List</li>
                <!--end::Item-->
            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
        <!--begin::Actions-->
        <div class="d-flex align-items-center py-3 py-md-1">
            <!--begin::Button-->
            <a href="{{route('home')}}" data-theme="light"
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
        <div class="card card-flush">
            <!--begin::Card header-->
            <div class="card-header mt-5">
                <!--begin::Card title-->
                <div class="card-title flex-column">
                    <h3 class="fw-bold mb-1">Contact List</h3>
                    <div class="fs-6 text-gray-400">Total {{$contactData->count()}} transfer report so far</div>
                </div>
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar my-1">
                    <!--begin::Select-->
                    <div class="me-6 my-1">
                        <select id="kt_filter_year" name="year" data-control="select2" data-hide-search="true"
                            class="d-none w-125px form-select form-select-solid form-select-sm">
                        </select>
                    </div>
                    <!--end::Select-->
                    <!--begin::Select-->
                    <div class="me-4 my-1">
                        <select id="kt_filter_orders" name="orders" data-control="select2" data-hide-search="true"
                            class="d-none w-125px form-select form-select-solid form-select-sm">
                        </select>
                    </div>
                    <!--end::Select-->
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                        <span class="svg-icon svg-icon-3 position-absolute ms-3">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                    transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                <path
                                    d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                    fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <input type="text" id="kt_filter_search"
                            class="form-control form-control-solid form-select-sm w-150px ps-9"
                            placeholder="Search Order" />
                    </div>
                    <!--end::Search-->
                </div>
                <!--begin::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->


            <div class="card-body pt-0">
                <!--begin::Table container-->
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table id="kt_profile_overview_table"
                        class="table table-row-bordered table-row-dashed gy-4 align-middle fw-bold">
                        <!--begin::Head-->
                        <thead class="fs-7 text-gray-400 text-uppercase">
                            <tr>
                                <th class="min-w-25px first-row text-center">Serial</th>
                                <th class="min-w-125px">PersonData</th>
                                <th class="min-w-125px">Description</th>
                                <th class="text-end min-w-100px">Actions</th>
                            </tr>
                        </thead>
                        <!--end::Head-->
                        <!--begin::Body-->
                        <tbody class="fs-6">
                            @foreach ($contactData as $key => $item)
                            @if(isset($item) && $item != null)
                            <tr class="p-0 m-0 {{$loop->iteration % 2 == 0 ? 'bg-light-warning': ''}}">
                                <!--begin::Role=-->

                                <td class="pt-1 pb-1 pr-1 pl-1">
                                    <span class="text-dark fw-bold">{{$loop->iteration}}</span>
                                </td>

                                <td class="pt-1 pb-1 pr-1 pl-1">
                                    Name: <span class="text-dark">{{$item->name}}</span><br>
                                    Email: <span class="text-dark">{{$item->email}}</span><br>
                                    Phone: <span class="text-dark">{{$item->phone}}</span><br>
                                </td>

                                <td class="pt-1 pb-1 pr-1 pl-1">
                                    Subject: <span class="text-dark">{{ $item->subject }}</span><br>
                                    Query: <span class="text-dark">{{ Str::limit($item->description, 100) }}</span><br>
                                </td>

                                <!--begin::Action=-->
                                <td class="pt-1 pb-1 pr-1 pl-1 text-end custom-action-block ">
                                    <a href="#" class="btn btn-light btn-active-light-primary btn-sm"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                        <span class="svg-icon svg-icon-5 m-0">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                    fill="currentColor" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                    </a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                        data-kt-menu="true">

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            @can('blog-create')
                                            <form method="POST" action="{{ route('blog.destroy', $item->id) }}"
                                                class="menu-link px-3">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" title="delete" class="bg-transparent border-0">
                                                    <i class="fas fa-trash text-danger"></i> <span>Delete</span>
                                                </button>

                                            </form>
                                            @endcan
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                </td>
                                <!--end::Action=-->
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                        <!--end::Body-->
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Table container-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Post-->
</div>
<!--end::Container-->

@endsection