    <section class="carousel-slider-main text-center border-top border-bottom bg-white">
        <div class="owl-carousel owl-carousel-slider">

            <div class="item">
                <a href="javascript:void(0)"><img class="img-fluid lozad" data-src="{{ asset('public/web/img/slider/slider7.jpg') }}" alt="COVID-19 Slide"></a>
            </div>

            <div class="item">
                <a href="javascript:void(0)"><img class="img-fluid lozad" data-src="{{ asset('public/web/img/slider/slider6.jpg') }}" alt="COVID-19 Slide"></a>
            </div>

            {{-- <div class="item">
               <a href="javascript:void(0)"><img class="img-fluid lozad" data-src="{{ asset('public/web/img/slider/slider5.jpg') }}" alt="COVID-19 Slide"></a>
        </div> --}}

        <div class="item">
            <a href="javascript:void(0)"><img class="img-fluid lozad" data-src="{{ asset('public/web/img/slider/slider1.jpg') }}" alt="First slide"></a>
        </div>

        <div class="item">
            <a href="javascript:void(0)"><img class="img-fluid lozad" data-src="{{ asset('public/web/img/slider/slider3.jpg') }}" alt="First slide"></a>
        </div>

        {{-- <div class="item">
            <a href="javascript:void(0)"><img class="img-fluid" src="{{ asset('public/web/img/slider/slider2.jpg') }}" alt=" slide"></a>
        </div> --}}

        <div class="item">
            <a href="javascript:void(0)"><img class="img-fluid lozad" data-src="{{ asset('public/web/img/slider/slider4.jpg') }}" alt=" slide"></a>
        </div>
        </div>
    </section>
    <section class="top-category section-padding">
        {{-- <div class="container">
            <div class="owl-carousel owl-carousel-category">
              @foreach($mini_slider_product as $item)
               <div class="item">
                  <div class="category-item">
                     <a href="{{ url('product/view')}}/{{ $item->id}}">
        @if($item->picture !='')
        <img class="img-fluid lozad" data-src="{{ asset('public/upload/product/') }}/{{ $item->picture}}" alt="{{ $item->name}}">
        @else
        <img class="img-fluid lozad" data-src="{{ asset('public/upload/product/') }}/default.jpg" alt="">
        @endif
        <h6> {{ $item->name}}</h6>
        </a>
        </div>
        </div>
        @endforeach
        </div>
        </div> --}}

        <div class="container">
            <div class="owl-carousel owl-carousel-category">
                @foreach($feature_product as $fitem)
                <div class="item">
                    <div class="category-item">
                        <a href="{{ url('product/list')}}/{{ $fitem->id}}">
                            @if($fitem->icon !='')
                            <img class="img-fluid lozad" data-src="{{ asset('public/upload/category/') }}/{{ $fitem->icon}}" alt="{{ $fitem->name}}">
                            @elseif($fitem->picture !='')
                            <img class="img-fluid lozad" data-src="{{ asset('public/upload/product/') }}/{{ $fitem->picture}}" alt="{{ $fitem->name}}">
                            @else
                            <img class="img-fluid lozad" data-src="{{ asset('public/upload/product/') }}/default.jpg" alt="">
                            @endif
                            <h6> {{ $fitem->name}}</h6>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </section>
