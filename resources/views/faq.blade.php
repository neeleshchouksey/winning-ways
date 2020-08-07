@extends('app')
@section('content')

    <div class="faq-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <h2 class="section-heading">Frequently Asked Questions</h2>
                </div>
            </div>
            <div class="faq-content">
                @foreach($faq as $k=>$v)
                    <div class="faq-name">
                        <div><b>Que {{$k+1}} {{$v->question}}</b></div>
                        <div>Ans {{$k+1}} {{$v->answer}}</div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
