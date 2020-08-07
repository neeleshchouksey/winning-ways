@extends('app')
@section('content')

<div class="privacy-cookie-policy">
  <div class="container">
      <div class="row">
          <div class="col-md-12 col-xs-12">
              <h2 class="section-heading">Privacy and Cookie Policy</h2>
          </div>
      </div>
      <div class="privacy-p-c">
          {!! $res->description !!}
      </div>
  </div>
</div>
@endsection
