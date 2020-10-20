@extends('layouts.web')
@section("title","Categories")

@section("content")

<section class="product-items-slider section-padding">

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="breadcrumbs">
                    <p class="mb-0 text-dark" style="padding: 16px;text-align: left;font-size: 13px;">

                        <a class="text-green" href="{{ route('index') }}">হোম/</a>

                        <a class="text-dark" href="{{ route('web.categories') }}">সব ক্যাটাগরি</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($categories as $category)
            @php

                $realpath = public_path('upload/category/'.$category->icon);

                if(empty($category->icon)){
                    $icon = asset('public/default_category.jpg');
                } else if(!file_exists($realpath)){
                    $icon = asset('public/default_category.jpg');
                } else {
                    $icon = asset('public/upload/category/'.$category->icon);
                }

            @endphp

            <div class="col-sm-3">
                <a href="{{route('productList',[$category->id])}}">
                    <div class="row border ml-1 mr-1 mt-1 mb-1">
                        <div class="col-sm-8">
                            <h5 class="card-title text-center mt-4">{{$category->name}}</h5>
                        </div>
                        <div class="col-sm-4">
                            <img class="rounded-circle lozad" alt="100x100" data-src="{{ $icon }}" data-holder-rendered="true">
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>

</section>

@endsection
