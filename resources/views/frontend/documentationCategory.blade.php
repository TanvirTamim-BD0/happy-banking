@extends('frontend.master')
@section('content')
@section('styles')
@endsection

<div class="container h-100">
    <div class="row">
        @foreach ($docsCategoryData as $item)
            @if(isset($item) && $item != null)
                <div class="col-6 p-2">
                    <a href="{{route('webuser.documentation-category-wise-documentation', $item->id)}}">
                        <div class="content card p-0">

                            <img class="card-img-top"
                                src="{{ $item->image ?  asset('backend/uploads/documentationCategoryImage/thumbnail/'.$item->image) : asset('frontend/images/description_card.jpg') }}"
                                alt="Card image cap">

                            <div class="card-body py-2 px-2">
                                <h5 class="card-title mb-0 text-dark">{{ $item->blog_category_name }}</h5>
                                <p class="card-text"> {{$item->documentation_category_name}}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
        @endforeach
    </div>
</div>

@endsection