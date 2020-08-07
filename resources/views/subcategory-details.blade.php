@extends('app')
@section('content')

    <div class="new-deal-wrapper newnew-chan-delas">

        <div class="container">


            <div class="single-coupon-wrapper">
                <div class="container">
                    <div class="row"><h3>{{$subcategory->sub_category_name}}</h3></div>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="single-coupon-thumb">
                                <img src="{{URL::to('/')}}/public/storage/sub-category-images/{{$subcategory->image}}" alt="Coupon" class="img-responsive">
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="single-coupon-content">
                                {!! $subcategory->description !!}

                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="single-coupon-content">
                                {!! $subcategory->description2 !!}

                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="single-coupon-thumb">
                                <img src="{{URL::to('/')}}/public/storage/sub-category-images/{{$subcategory->image2}}" alt="Coupon" class="img-responsive">
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="single-coupon-thumb">
                                <img src="{{URL::to('/')}}/public/storage/sub-category-images/{{$subcategory->image3}}" alt="Coupon" class="img-responsive">
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="single-coupon-content">
                                {!! $subcategory->description3 !!}

                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="single-coupon-content">
                                {!! $subcategory->description4 !!}

                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="single-coupon-thumb">
                                <img src="{{URL::to('/')}}/public/storage/sub-category-images/{{$subcategory->image4}}" alt="Coupon" class="img-responsive">
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="single-coupon-wrapper">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="section-heading th-section-heading">Videos under this subcategory</h2></div>
            </div>
                <div class="row">

                    <div id="" class="newnew-chan">
                        @if(sizeof($videos)>0)
                            @foreach($videos as $v)
                                <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4" >
                                    <div class="new-ifram-a-n">
                                        <iframe class="video-width"  src="{{$v->link}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        <h5><b>{{$v->title}}</b></h5>
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
    </div>

@endsection
