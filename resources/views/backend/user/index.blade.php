@extends('backend.master')
@section('title') Bank System | User List @endsection
@section('styles')
@endsection
@section('content')

<div id="content" class="content filter_3">
    <main>

        <div class="align-items-center p-2">
            <h3 class="breadcump-header">User <span class="divider"></span></h3>
            <span class="nav_indicator"><a href="{{route('home')}}" class="breadcump-text">Home</a> > <span class="breadcump-active"> List</span></span>
        </div>

        <!-- table section -->
        <div class="container-fluid">
            <section class="row">
                <div class="col-lg-12 table_wrap hide_scrollbar table_data">
                    <div class="card-body card" style="padding-bottom: 5px;">

                        <div class="card-content custom-card-content-for-datatable pb-3 pt-2">
                            <h2 class="card-title">User List</h2>
                            <div class="float-right justify-content-end">
                                
                            </div>
                        </div>

                        <table id="example" class="table table-bordered table-striped table-responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>OTP</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userData as $item)
                                    @if(isset($item) && $item != null)
                                        <tr>
                                           
                                            <td>
                                                <div class="row_title">
                                                    <strong><a href="#">{{$item->name}}</a></strong>
                                                </div>
                                                <div class="row-actions">

                                                    <span>
                                                    	@if($item->status == true)
                                                        <a href="{{route('user-inactive', $item->id)}}">
                                                            <span class="trash">Suspend</span> 
                                                        </a>
                                                        @else
                                                        <a href="{{route('user-active', $item->id)}}">
                                                            <span class="trash">Active</span> 
                                                        </a>
                                                        @endif
                                                    </span>
                                                </div>
                                            </td>

                                            <td>{{$item->email}}</td>
                                            <td>{{$item->verify_code}}</td>

                                            <td>
                                            	@if($item->status == 1)
                                            	Active
                                            	@else
                                            	Suspend
                                            	@endif
                                            </td>
                                            
                                        </tr>

                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
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