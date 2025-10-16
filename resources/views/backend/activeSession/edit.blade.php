@extends('backend.master')
@section('title') Bank System | Active Session Update @endsection
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
                        <h3>Active Session Update<span class="divider"> |</span></h3><span class="nav_indicator"><a
                                href="./index.html">Home</a> > Update</span>
                    </div>
                </div>
                <div class="card_wrap">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="card-body card p-3">
                                <form class="passShowForm" action="{{route('active-session.update', $singleActiveSessionData->id)}}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <div class="single_input">
                                                <input onblur="inputValidator(this.value,'sessionText','text')"
                                                    placeholder="Bank Name" class="form-control" type="text"
                                                    name="session_name" id="session_name" value="{{$singleActiveSessionData->session_name}}" required>
                                                <span id="sessionText" class="text-danger warn">Session is required</span>

                                                @error('session_name')
                                                <span class=text-danger>{{ $message }}</span>
                                                @enderror
                                            </div>
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