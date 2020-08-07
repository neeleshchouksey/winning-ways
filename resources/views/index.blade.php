@extends('app')
@section('content')
    <div class="deal-coupon-slider-wrapper">
        <div class="row">
            <div id="deal-coupon-slider" class="owl-carousel owl-theme slider-list">
                @foreach($section1 as $s1)
                    <div class="item">
                        <div class="hero-area bg--img slider-img-div">
                            <a href="{{$s1->link}}">
                                <img src="{{URL::to('/')}}/public/storage/slider-images/{{$s1->image}}">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="new-deal-wrapper">

        <div class="container">

            <div class="row">

                <div class="col-xs-8 col-md-10">
                    <h3 class="section-heading">Top Work & Services</h3>
                </div>
                <div class="col-md-2 col-xs-4">
                    <div class="view-all">
                        <a href="{{URL::to('/')}}/categories">View All</a>
                    </div>
                </div>

            </div>

            <div class="row">

                <div id="" class="new-deal-list">
                    @foreach($categories as $c)
                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                            <div class="item deal-item">
                                    <div class="deal-thumb">
                                        <a href="{{URL::to('/')}}/subcategory/{{$c->id}}">
                                        <img src="{{ asset('/public/')}}/storage/sub-category-images/{{$c->image}}" alt=""
                                             class="img-responsive"/>
{{--                                        <div class="deal-badge">--}}
{{--                                            87%--}}
{{--                                        </div>--}}
                                        </a>
                                    </div>
                                    <div class="deal-content">
                                        <h6>  <a href="{{URL::to('/')}}/subcategory/{{$c->id}}">{{substr($c->sub_category_name,0,20)}}</a></h6>
                                        <span>  <a href="{{URL::to('/')}}/subcategory/{{$c->id}}">{{substr(strip_tags($c->description),0,80)}}...</a> </span>
                                        {{--                                    <div class="deal-content-bottom">--}}
                                        {{--                                        <p><i class="fa fa-clock-o"></i> 0 days, 0h Remaining</p>--}}
                                        {{--                                        <a href="javascript:void(0)" class="btn btn-sm">Get It</a>--}}
                                        {{--                                    </div>--}}
                                    </div>
                            </div>

                        </div>
                    @endforeach
                </div>

            </div>

        </div>
    </div>

    <div class="how-we-works-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="section-heading">How It Works</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="single-how-we-works text-center">
                        <div class="how-we-works-thumb">
                            <img src="{{ asset('/public/')}}/assets/img/how-we-works/coupon.png" alt="How We Works"
                                 class="img-responsive"/>
                        </div>
                        <h6>{{$howwework->title1}}</h6>
                        <p>{{$howwework->description1}}</p>
                        {{--                        <a href="#" class="btn btn-brand">Get This</a>--}}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="single-how-we-works text-center">
                        <div class="how-we-works-thumb">
                            <img src="{{ asset('/public/')}}/assets/img/how-we-works/deal.png" alt="How We Works"
                                 class="img-responsive"/>
                        </div>
                        <h6>{{$howwework->title2}}</h6>
                        <p>{{$howwework->description2}}</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="single-how-we-works text-center">
                        <div class="how-we-works-thumb">
                            <img src="{{ asset('/public/')}}/assets/img/how-we-works/voucher.png" alt="How We Works"
                                 class="img-responsive"/>
                        </div>
                        <h6>{{$howwework->title3}}</h6>
                        <p>{{$howwework->description3}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="popular-deal-wrapper">

        <div class="container">

            <div class="row">

                <div class="col-md-10 col-xs-8">
                    <h3 class="section-heading">Popular Categories</h3>
                </div>
                <div class="col-md-2 col-xs-4">
                    <div class="view-all">
                        <a href="{{URL::to('/')}}/categories">View All</a>
                    </div>
                </div>

            </div>

            <div class="row">

                <div id="" class="new-deal-list">

                    @foreach($categories2 as $c)
                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                            <div class="item deal-item">
                                    <div class="deal-thumb">
                                        <a href="{{URL::to('/')}}/subcategory/{{$c->id}}">
                                        <img src="{{ asset('/public/')}}/storage/sub-category-images/{{$c->image}}" alt=""
                                             class="img-responsive"/>
{{--                                        <div class="deal-badge">--}}
{{--                                            87%--}}
{{--                                        </div>--}}
                                        </a>

                                    </div>
                                    <div class="deal-content">
                                        <h6><a href="{{URL::to('/')}}/subcategory/{{$c->id}}">{{substr($c->sub_category_name,0,20)}}</a></h6>
                                        <span><a href="{{URL::to('/')}}/subcategory/{{$c->id}}">{{substr(strip_tags($c->description),0,80)}}...</a> </span>
                                        {{--                                    <div class="deal-content-bottom">--}}
                                        {{--                                        <p><i class="fa fa-clock-o"></i> 0 days, 0h Remaining</p>--}}
                                        {{--                                        <a href="javascript:void(0)" class="btn btn-sm">Get It</a>--}}
                                        {{--                                    </div>--}}
                                    </div>
                            </div>
                        </div>
                    @endforeach

                </div>

            </div>

        </div>
    </div>

    <div class="get-to-know-us-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="get-to-know-us-video">
                        {{--                        <img src="{{ asset('/public/')}}/assets/img/video/video_bg.jpg" alt="Video"--}}
                        {{--                             class="img-responsive img-rounded"/>--}}
                        {{--                        <a href="http://www.youtube.com/watch?v=XSGBVzeBUbk" data-lity><i class="fa fa-play"></i></a>--}}
                        <div class="new-iframe-a">
                            <iframe src="{{$getToKnow->link}}" frameborder="0"
                                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="get-to-know-us-content">
                        <h6>Get To know Us</h6>
                        <p>{{$getToKnow->description}}</p>
                        <a href="{{$getToKnow->read_more_link}}" class="btn btn-brand">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="how-we-works-wrapper new-how-we-works-wrapper">
        <div class="container">
            {{--            <div class="row">--}}
            {{--                <div class="col-xs-12">--}}
            {{--                    <h2 class="section-heading">How We Works</h2>--}}
            {{--                </div>--}}
            {{--            </div>--}}
            <div class="row">
                <div class="col-md-4">
                    <div class="single-how-we-works text-justify">

                        <h6>Our Vision</h6>
                        <p>{{$aboutus->vision}}</p>
                        {{--                        <a href="#" class="btn btn-brand">Get This</a>--}}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="single-how-we-works text-justify">

                        <h6>Our Mission</h6>
                        <p>{{$aboutus->mission}}</p>
                        {{--                        <a href="#" class="btn btn-brand">Get This</a>--}}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="single-how-we-works text-justify">

                        <h6>Our Values</h6>
                        <p>{{$aboutus->aim}}</p>
                        {{--                        <a href="#" class="btn btn-brand">Get This</a>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection
