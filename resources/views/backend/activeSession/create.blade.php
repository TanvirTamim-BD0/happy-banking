@extends('backend.master')
@section('title') Bank System | Active Session Create @endsection
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
                        <h3>Active Session Create<span class="divider"> |</span></h3><span class="nav_indicator"><a
                                href="./index.html">Home</a> > Create</span>
                    </div>
                </div>
                <div class="card_wrap">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="card-body card p-3">
                                <form class="passShowForm" action="{{route('active-session.store')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <div class="single_input">
                                                <input onblur="inputValidator(this.value,'sessionText','text')" placeholder="Session Name" class="form-control" type="text"
                                                    name="session_name" id="session_name" required>
                                                <span id="sessionText" class="text-danger warn">Session is required</span>

                                                @error('session_name')
                                                <span class=text-danger>{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-12 mb-2 mt-3">
                                            <div class="single_input">
                                                <input class="btn btn-primary" type="submit" value="Submit">
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