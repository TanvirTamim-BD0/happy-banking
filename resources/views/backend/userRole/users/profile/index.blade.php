@extends('backend.master')
@section('title') Bank System | Profile @endsection
@section('styles')
@endsection
@section('content')

<div class="content">
    <main>

        <div class="align-items-center p-2">
            <h3 class="breadcump-header">Profile <span class="divider"></span></h3>
            <span class="nav_indicator"><a href="{{route('home')}}" class="breadcump-text">Home</a> > <span
                    class="breadcump-active"> Profile</span></span>
        </div>

        <div class="container-fluid">


            <div class="row">
                @include('backend.userRole.users.profile.sidebar')
                <div class="col-md-9 mb-2">

                    @include('backend.userRole.users.profile.menu')

                    <div class="card">
                        <div class="card-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                            <section class="row">
                                <div class="col-lg-12 table_wrap hide_scrollbar table_data">
                                    <div class="card-body card" style="padding-bottom: 5px;">
                            
                                        <div class="card-content custom-card-content-for-datatable pb-3 pt-2">
                                            <h2 class="card-title">User Activity List</h2>
                                            <div class="float-right justify-content-end">
                            
                                            </div>
                                        </div>
                            
                                        <table id="example" class="table table-bordered table-striped table-responsive" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Start Time</th>
                                                    <th>End Time</th>
                                                    <th>Total Click</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($singleUserActivity as $item)
                                                @if(isset($item) && $item != null)
                                                <tr>
                            
                                                    <td>
                                                        <div class="row_title">
                                                            <strong>Date: <span class="text-primary">{{\Carbon\Carbon::parse($item->date)->format('d-m-Y')}}</span></strong>
                                                        </div>
                                                    </td>
                            
                                                    <td>
                                                        <b>Start At: </b> <span class="text-primary">{{$item->start_time}}</span>
                                                        
                                                    </td>
                                                    <td>
                                                        <b>End At: </b> <span class="text-primary">{{$item->end_time}}</span>
                                                    </td>
                            
                                                    <td>
                                                        <b>Total Hit: </b><span class="text-danger fw-bolder">
                                                            @if($item->total_hit > 0)
                                                            {{$item->total_hit}} Time
                                                            @else
                                                            0 Time
                                                            @endif
                                                        </span>
                                                    </td>
                            
                                                </tr>
                            
                                                @endif
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Start Time</th>
                                                    <th>End Time</th>
                                                    <th>Total Click</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
</div>


@endsection