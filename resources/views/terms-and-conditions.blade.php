@extends('app')
@section('content')

<div class="terms-and-conditions-a">
  <div class="container">
      <div class="row">
          <div class="col-md-12 col-xs-12">
              <h2 class="section-heading">Terms and Condition</h2>
          </div>
      </div>
      <div class="tm-c-content">{!! $res->description !!}</div>
  </div>
</div>
@endsection
