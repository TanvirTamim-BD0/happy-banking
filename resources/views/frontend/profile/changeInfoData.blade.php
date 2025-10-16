@extends('frontend.master')
@section('title') User Basic Info Update @endsection
@section('styles')
@endsection
@section('content')

<div class="container h-100">
    <form method="POST" action="{{route('webuser.change-info-update')}}" autocomplete="off"
        class="needs-validation card" novalidate enctype="multipart/form-data" autocomplete="off"
        style="margin-bottom: 13px;">
        @csrf

        <h3 class="text-center heading_text pb-3">Change Info</h3>

        <div class="form-group custom-form-group">
            <label class="custom-form-label" for="name">Name <span class="custom-danger">(requierd)</span></label>
            <div class="single_input">
                <i class="fa-solid fa-user"></i>
                <input autocomplete="off" class="form-control" type="text" placeholder="Name" value="{{Auth::user()->name}}"
                    name="name" id="name">

                @error('name')
                <span class="custom-text-danger custom-text-danger-position">{{$message}}</span>
                @enderror
            </div>
        </div>

        <div class="form-group custom-form-group">
            <label class="custom-form-label" for="email">Email <span class="custom-danger">(requierd)</span></label>
            <div class="single_input">
                <i class="fa-solid fa-envelope"></i>
                <input autocomplete="off" type="email" placeholder="Email" name="email" id="email"
                    value="{{Auth::user()->email}}" class="form-control">

                @error('email')
                <span class="custom-text-danger custom-text-danger-position">{{$message}}</span>
                @enderror
            </div>
        </div>

        @php
        //To get all the profession data...
        $userProfessionData = App\Models\User::getProfessionData();
        @endphp

        <div class="form-group custom-form-group">
            <label class="custom-form-label" for="profession">Profession <span class="custom-danger">(requierd)</span></label>
            <div class="single_input custom-select2-dropdown">
                <i class="fa-solid fa-briefcase"></i>
                <select autocomplete="off" name="profession_id" id="profession" required class="form-control">
                    <option value="" selected disabled>Select Profession</option>
                    @foreach ($userProfessionData as $item)
                    @if(isset($item) && $item != null)
                    <option value="{{$item->id}}" {{$item->id == Auth::user()->profession_id ? 'selected' : ''}}>{{$item->profession_name}}</option>
                    @endif
                    @endforeach
                </select>

                @error('profession')
                <span class="custom-text-danger custom-text-danger-position">{{$message}}</span>
                @enderror
            </div>
        </div>


        <div class="form-group custom-form-group">
            <label class="custom-form-label" for="gender">Gender <span class="custom-danger">(requierd)</span></label>
            <div class="single_input d-flex align-items-center gap-2 gender">
                <input type="text" name="gender" id="genderValue" value="{{Auth::user()->gender}}">
                <a onclick="genderToggle('male_btn','female_btn','male_btn')" id="male_btn" 
                class="primary_btn d-flex align-items-center justify-content-center m-0
                    {{Auth::user()->gender == 'male' ? 'active' : ''}}
                    " for="male">
                    <i class="fa-solid fa-mars"></i>Male
                </a>

                <a onclick="genderToggle('male_btn','female_btn','female_btn')" id="female_btn"
                    class="primary_btn d-flex align-items-center justify-content-center m-0
                    {{Auth::user()->gender == 'female' ? 'active' : ''}}
                    " for="female">
                    <i class="fa-solid fa-venus"></i>Female
                </a>

                @error('gender')
                <span class="custom-text-danger custom-text-danger-position">{{$message}}</span>
                @enderror
            </div>
        </div>

        <div class="form-group custom-form-group">
            <label class="custom-form-label" for="address">Address <span class="custom-danger">(requierd)</span></label>
            <div class="single_input">
                <i class="fa-sharp fa-solid fa-location-dot"></i>
                <input name="address" autocomplete="off" type="text" id="address" placeholder="Address" value="{{Auth::user()->address}}"
                    class="form-control">

                @error('address')
                <span class="custom-text-danger custom-text-danger-position">{{$message}}</span>
                @enderror
            </div>
        </div>

        <div class="condition mt-3">
            <button type="submit" class="primary_btn">Update</button>
        </div>
    </form>

</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#profession').select2();
    });

    //To change user gender...
    function genderToggle(male,female,selected){
        const array = [male,female];
        const selectedElement = document.getElementById(selected);
        const gender = document.getElementById('genderValue');
        gender.value = ''
        array.forEach((gender) => {
            const element = document.getElementById(gender)
            element.classList.remove('active')
        })
        selectedElement.classList.add('active')
        if(selected === 'male_btn'){
            $("#genderValue").val('male');
        }else{
            $("#genderValue").val('female');
        }
    }
</script>
@endsection()