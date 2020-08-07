@extends('app')
@section('content')

    <div class="new-deal-wrapper newnew-chan-delas">

        <div class="container">


            <div class="row">

                <div id="" class="newnew-chan">
                    @if(sizeof($categories)>0)
                    @foreach($categories as $c)
                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                            <div class="item deal-item">
                                <div class="deal-thumb">
                                    <a href="{{URL::to('/')}}/subcategory/{{$c->id}}">
                                    <img src="{{ asset('/public/')}}/storage/sub-category-images/{{$c->image}}" alt=""
                                         class="img-responsive"/>
{{--                                    <div class="deal-badge">--}}
{{--                                        87%--}}
{{--                                    </div>--}}
                                    </a>
                                </div>
                                <div class="deal-content">
                                    <h6><a href="{{URL::to('/')}}/subcategory/{{$c->id}}">{{substr($c->sub_category_name,0,20)}}</a></h6>
                                    <p><a href="{{URL::to('/')}}/subcategory/{{$c->id}}">{{substr(strip_tags($c->description),0,100)}}... </a></p>
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
