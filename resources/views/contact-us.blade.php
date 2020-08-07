@extends('app')
@section('content')


    <div class="contact-wrapper">
        <div class="contact-wrapper-inner">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <contact-us></contact-us>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="call-to-action-wrapper">

        <div class="container">

            <div class="row">

                <div class="col-xs-12">

                    <div class="call-to-action-content">
                        <p>Download <a href="#">CouponZ</a> Apps For <br>
                            Get Coupon Everywhere!</p>
                    </div>

                </div>

            </div>

            <div class="row">

                <div class="col-xs-12">

                    <div class="call-to-action-button text-center">

                        <ul>
                            <!--
                                <li class="apple-store">
                                    <a href="#">
                                        <i class="fa fa-apple"></i>
                                        <div class="cta-btn-content">
                                            Download <span>From App Store</span>
                                        </div>
                                    </a>
                                </li> -->
                            <li class="google-play">
                                <a href="#">
                                    <i class="fa fa-android"></i>
                                    <div class="cta-btn-content">
                                        Download <span>From Play Store</span>
                                    </div>
                                </a>
                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection
