@extends('backend.master')
@section('title') Bank System | Note List @endsection
@section('styles')
@endsection
@section('content')

<div id="content" class="content filter_3">
    <main>

        <div class="align-items-center p-2">
            <h3 class="breadcump-header">Note <span class="divider"></span></h3>
            <span class="nav_indicator"><a href="{{route('home')}}" class="breadcump-text">Home</a> > <span class="breadcump-active"> List</span></span>
        </div>

        <!-- table section -->
        <div class="container-fluid">
            <section class="row">
                <div class="col-lg-12 table_wrap hide_scrollbar table_data">
                    <div class="card-body card" style="padding-bottom: 5px;">
                        <div class="card-content custom-card-content-for-datatable pb-3 pt-2">
                            <h2 class="card-title">Note List</h2>
                            <div class="float-right justify-content-end">
                                <a class="custom-datatable-add-btn"
                                    href="{{route('frontend-note.create')}}">
                                    <i class="fa fa-plus"></i> Add Note
                                </a>
                            </div>
                        </div>

                        <table id="example" class="table table-bordered table-striped table-responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Description Type</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($frontendNotedata as $item)
                                    @if(isset($item) && $item != null)
                                        <tr>
                                            <td>
                                                {{ $item->description_type }}                                                
                                            </td>
                                            <td>
                                                <div class="row_title">
                                                    <strong><a href="#">{!! $item->solid_description !!}</a></strong>
                                                </div>
                                                <div class="row-actions">
                                                    <span><a href="{{route('frontend-note.edit', $item->id)}}">Edit </a> | </span>
                                                    <span>
                                                        <a href="{{route('frontend-note.destroy', $item->id)}}">
                                                            <span class="trash">Trash</span> 
                                                        </a>
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>

                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Description Type</th>
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