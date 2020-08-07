@extends('app')
@section('content')

    <div class="careers-sec">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <h2 class="section-heading">Requirements</h2>
            </div>
        </div>

        @if(sizeof($career))
            @foreach($career as $k=>$v)
                <div class="careers-tital">
                    <div class="careers-name"><b>Job Title: </b></div>
                    <div class="careers-conten"> {{$v->title}}</div>
                </div>
                <div class="careers-tital">
                    <div class="careers-name"><b>Job Description</b></div>
                    <div class="careers-conten"> {{$v->description}}</div>
                </div>
                <div class="careers-tital">
                    <div class="careers-name"><b>otal Posts : </b></div>
                    <div class="careers-conten"> {{$v->total_post}}</div>
                </div>

            @endforeach
        @else
            <p>No Jobs Available</p>
        @endif

    </div>
    </div>
@endsection
