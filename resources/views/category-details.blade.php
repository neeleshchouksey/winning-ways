@extends('app')
@section('content')

    <div class="new-deal-wrapper newnew-chan-delas">

        <div class="container">


            <div class="single-coupon-wrapper new-single-coupon-wrapper">

                    <div class="row">
                        <div class="col-md-3 col-xs-12">
                            <div class="single-coupon-thumb">
                                <img src="{{URL::to('/')}}/public/storage/category-images/{{$category->image}}"
                                     alt="Coupon" class="img-responsive">
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="single-coupon-content">
                                <h5>{{$category->category_name}}</h5>
                                {!! $category->description !!}

                            </div>
                        </div>
                        {{--                        <div class="col-md-3 col-xs-12">--}}
                        {{--                            <div class="single-coupon-button">--}}
                        {{--                                <a class="btn btn-brand btn-lg btn-block" type="button" data-toggle="modal" data-target="#coupon-deal">Get Deal</a>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                    </div>

            </div>
            <div class="new-single-coupon-wrapper">
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <h3 class="section-heading">Description of Work and Services</h3></div>

            </div>
            </div>


            <div class="row">

                <div id="" class="newnew-chan">
                    @if(sizeof($subcategories)>0)
                        @foreach($subcategories as $c)
                            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="item deal-item">
                                    <div class="deal-thumb">
                                        <a href="{{URL::to('/')}}/subcategory/{{$c->id}}">
                                        <img src="{{ asset('/public/')}}/storage/sub-category-images/{{$c->image}}"
                                             alt=""
                                             class="img-responsive"/>
{{--                                        <div class="deal-badge">--}}
{{--                                            87%--}}
{{--                                        </div>--}}
                                        </a>

                                    </div>
                                    <div class="deal-content">
                                        <h6>
                                            <a href="{{URL::to('/')}}/subcategory/{{$c->id}}">{{$c->sub_category_name}}</a>
                                        </h6>
                                        <span>  <a href="{{URL::to('/')}}/subcategory/{{$c->id}}">{{substr(strip_tags($c->description),0,80)}}...</a> </span>
                                        {{--                                    <div class="deal-content-bottom">--}}
                                        {{--                                        <p><i class="fa fa-clock-o"></i> 0 days, 0h Remaining</p>--}}
                                        {{--                                        <a href="javascript:void(0)" class="btn btn-sm">Get It</a>--}}
                                        {{--                                    </div>--}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <h3 class="text-center">No Data Found</h3>

                    @endif
                </div>

            </div>

        </div>
    </div>

@endsection
