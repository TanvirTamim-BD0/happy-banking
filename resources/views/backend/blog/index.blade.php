@extends('backend.master')
@section('title') Bank System | Blog List @endsection
@section('styles')
@endsection
@section('content')

<div id="content" class="content filter_3">
    <main>

        <div class="align-items-center p-2">
            <h3 class="breadcump-header">Blog <span class="divider"></span></h3>
            <span class="nav_indicator"><a href="{{route('home')}}" class="breadcump-text">Home</a> > <span class="breadcump-active"> List</span></span>
        </div>

        <!-- table section -->
        <div class="container-fluid">
            <section class="row">
                <div class="col-lg-12 table_wrap hide_scrollbar table_data">
                    <div class="card-body card" style="padding-bottom: 5px;">

                        <div class="card-content custom-card-content-for-datatable pb-3 pt-2">
                            <h2 class="card-title">Blog List</h2>
                            <div class="float-right justify-content-end">
                                <a class="custom-datatable-add-btn"
                                    href="{{route('blog.create')}}">
                                    <i class="fa fa-plus"></i> Add Blog
                                </a>
                            </div>
                        </div>

                        <table id="example" class="table table-bordered table-striped table-responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Category</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($blogData as $item)
                                    @if(isset($item) && $item != null)
                                        <tr>
                                            <td>
                                                @if(isset($item->image) && $item->image != null)
                                                <img class="custom-datatable-image" src="{{asset('backend/uploads/blog/'.$item->image)}}" />
                                                @else
                                                <img class="custom-datatable-image" src="{{asset('backend')}}/assets/images/img_preview.png" />
                                                @endif
                                            </td>

                                            <td>
                                                {{$item->blogCategoryData->blog_category_name}}
                                            </td>
                                            <td>
                                                <div class="row_title">
                                                    <strong><a href="#">{{$item->title}}</a></strong>
                                                </div>
                                                <div class="row-actions">
                                                    <span><a href="{{route('blog.edit', $item->id)}}">Edit </a> | </span>
                                                    <span>
                                                        <a href="{{route('blog.destroy', $item->id)}}">
                                                            <span class="trash">Trash</span> 
                                                        </a>
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                {{$item->solid_description}}
                                            </td>
                                        </tr>

                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Image</th>
                                    <th>Category</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </section>
        </div>


    </main>
</div>

@endsection