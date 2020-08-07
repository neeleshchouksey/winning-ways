<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Work Portal</title>

    <meta name="description" content="simple description for your site"/>
    <meta name="keywords" content="keyword1, keyword2"/>
    <meta name="author" content="Warkportal"/>

    <!-- twitter card starts from here, if you don't need remove this section -->
    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:site" content="@yourtwitterusername"/>
    <meta name="twitter:creator" content="@yourtwitterusername"/>
    <meta name="twitter:url" content="http://yourdomain.com/"/>
    <meta name="twitter:title" content="Your home page title, max 140 char"/> <!-- maximum 140 char -->
    <meta name="twitter:description" content="Your site description, maximum 140 char "/> <!-- maximum 140 char -->
    <meta name="twitter:image"
          content="assets/img/twittercardimg/twittercard-144-144.png"/>  <!-- when you post this page url in twitter , this image will be shown -->
    <!-- twitter card ends here -->


@if(isset($blog->name))
    <meta property="og:title" content="{{$blog->name}}">
    <meta property="og:image" content="{{ asset('/public/')}}/storage/blog-images/{{$blog->image}}">
@endif
    <!-- facebook open graph starts from here, if you don't need then delete open graph related  -->
{{--    <meta property="og:title" content="Your home page title"/>--}}

{{--    <meta property="og:locale" content="en_US"/>--}}
{{--    <meta property="og:site_name" content="Your site name here"/>--}}
    <!--meta property="fb:admins" content="" /-->  <!-- use this if you have  -->
{{--    <meta property="og:type" content="website"/> --}}
    <!-- 'article' for single page  -->
{{--    <meta property="og:image"--}}
{{--          content="{{ asset('/public/')}}/assets/img/opengraph/fbphoto-476-476.png"/> <!-- when you post this page url in facebook , this image will be shown -->--}}
    <!-- facebook open graph ends here -->

    <!-- desktop bookmark -->
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('/public/')}}/assets/img/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <script>
        var APP_URL = "{{URL::to('/')}}";
        var AUTH_USER = "{{Auth::user()}}";
    </script>

    <!-- icons & favicons -->
    <link rel="shortcut icon" type="image/x-icon"   href="{{ asset('/public/')}}/assets/img/favicon/faviconas.ico"/>  <!-- this icon shows in browser toolbar -->



    <!--[if lt IE 9]>
    <script src="//css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- GOOGLE FONT -->
    <link href="http://fonts.googleapis.com/css?family=Cabin:400,700%7CUbuntu:300,400,700" rel="stylesheet" />

    <!-- BOOTSTRAP CSS -->
    <link rel="stylesheet" href="{{ asset('/public/')}}/assets/vendor/bootstrap/css/bootstrap.min.css" media="all" />


    <!--owl-->
    <link rel="stylesheet" href="{{ asset('/public/')}}/assets/vendor/owl-carousel/dist/assets/owl.carousel.min.css" media="all" />
    <link rel="stylesheet" href="{{ asset('/public/')}}/assets/vendor/owl-carousel/dist/assets/owl.theme.default.min.css" media="all" />

    <!-- FONT AWESOME CSS -->
    <link rel="stylesheet" href="{{ asset('/public/')}}/assets/vendor/font-awesome/css/font-awesome.min.css" media="all" />

    <!-- linearicons -->
    <link rel="stylesheet" href="{{ asset('/public/')}}/assets/vendor/linearicons/webfont/style.css" media="all" />

    <!-- animate.css -->
    <link rel="stylesheet" href="{{ asset('/public/')}}/assets/vendor/animate/animate.min.css" media="all" />

    <!-- flatpickr -->
    <link rel="stylesheet" href="{{ asset('/public/')}}/assets/vendor/flatpickr/flatpickr.min.css" media="all" />

    <!-- lity -->
    <link rel="stylesheet" href="{{ asset('/public/')}}/assets/vendor/lity/lity.min.css" media="all" />

    <!-- Bootstrap Slider -->
    <link rel="stylesheet" href="{{ asset('/public/')}}/assets/vendor/bootstrap-slider/css/bootstrap-slider.min.css" media="all" />

    <link rel=stylesheet href='./node_modules/nouislider/distribute/nouislider.min.css' media=all />


    <!-- CUSTOM  CSS  -->
    <link id="cbx-style" rel="stylesheet" href="{{ asset('/public/')}}/assets/css/style-default.css" media="all" />
    <link id="cbx-style" rel="stylesheet" href="{{ asset('/public/')}}/assets/css/costam.css" media="all" />
    <link id="cbx-style" rel="stylesheet" href="{{ asset('/public/')}}/assets/css/custom.css" media="all" />

    <!-- MODERNIZER  -->
    <script src="{{ asset('/public/')}}/assets/vendor/modernizr/modernizr.min.js"></script>


</head>
<body>



<div class="cbx-container" id="user-app">

    <!-- SITE CONTENT -->

    <!-- Header Part Start -->
    <header class="cbx-header">
        <!-- Header Top Part Start -->
        <div class="cbx-header-top">
            <div class="container">
                <div class="row">
                    <div class="col-md-2 col-sm-4 col-xs-7">
                        <!-- Header Top Brand Part Start -->
                        <div class="cbx-brand">
                            <a href="{{URL::to('/')}}">
                                <img src="{{ asset('/public/')}}/assets/img/logo/workportal-logo.png" alt="Warkportal" class="img-responsive" />
                            </a>
                        </div>
                        <!-- Header Top Brand Part End -->
                    </div>

                    <div class="col-md-4 col-sm-6 col-xs-5 pull-right">

                        <div class="cbx-header-top-search-btn">


                            <!-- Header Top Search Part Start -->
                        {{--                            <div class="cbx-search">--}}
                        {{--                                <a id="showSearchBar" class="btn btn-default btn-lg btn-brand" role="button" data-toggle="collapse" href="#searchBar"><i class="glyphicon glyphicon-search"></i></a>--}}

                        {{--                            </div>--}}
                        <!-- Header Top Search Part End -->

                            {{--                            <!-- Header Top Submit Coupon Part Start -->--}}
                            <div class="cbx-coupon-submit-btn">
                                <a class="btn btn-sub" href="{{URL::to('/')}}/work-post">
                                    <div class=" hidden-xs">SUBMIT WORK</div>
                                    <div class=" hidden-md  hidden-lg hidden-sm">SUBMIT WORK</div>
                                </a>
                            </div>
                            <!-- Header Top Submit Coupon Part End -->

                        </div>

                    </div>
                </div>

            </div>

        </div>
    {{--        <div id="searchBar" class="collapse">--}}
    {{--            <div class="container">--}}
    {{--                <div class="row">--}}
    {{--                    <div class="col-xs-12">--}}
    {{--                        <div class="searchbar-wrapper">--}}
    {{--                            <form class="navbar-form" role="search">--}}
    {{--                                <div class="input-group">--}}
    {{--                                    <input autocomplete="off" type="text" class="form-control" placeholder="Search" name="q">--}}
    {{--                                    <div class="input-group-btn">--}}
    {{--                                        <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </form>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    <!-- Header Top Part End -->

        <!-- Header Bottom Part Start -->
        <div class="cbx-header-bottom">
            <div class="container">
                <div class="row">
                    <nav class="navbar navbar-default">

                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed pull-left" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                Menu
                                <!--<span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>-->
                            </button>
                            <div class="hidden-lg hidden-md hidden-sm pull-right mobile-signin-btn">
                                @if(!Auth::user())
                                    <a class="btn btn-brand" href="{{URL::to('/')}}/signin">Login/Signup</a>
                                @else
                                    <ul class="nav navbar-nav float-right ">

                                        <li class="dropdown">
                                            <a href="javascript:void(0)" class="new-class-changzz">{{Auth::user()->name}}<span class="caret"></span></a>
                                            <ul class="new-class-chang">
                                                <li><a href="{{URL::to('/')}}/packages">My Profile</a></li>
                                                <li><a href="{{URL::to('/')}}/my-account">My Account</a></li>
                                                <li><a href="{{URL::to('/')}}/debit-requests">Debit Requests</a></li>
                                                <li><a href="{{URL::to('/')}}/my-works">My Work and Service</a></li>
                                                <li><a href="{{URL::to('/')}}/my-claimed-works">My Claimed Work and Service</a></li>
                                                <li><a href="{{URL::to('/')}}/work-post">Submit Work and Service</a></li>
                                                <li><a href="{{URL::to('/')}}/claim-work">Claim Work and Service</a></li>
                                                <li><a href="{{URL::to('/')}}/logout">Logout</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav">
                                <li><a href="{{URL::to('/')}}">Home</a></li>
                                <li><a href="{{URL::to('/')}}/about-us">About</a></li>
                                <li class="dropdown">
                                    <?php $online = get_services(2); ?>
                                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"
                                       role="button" aria-haspopup="true" aria-expanded="false">Work and Services <span
                                            class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        @foreach($online as $o)
                                            <li><a href="{{URL::to('/')}}/category/{{$o->id}}">{{$o->category_name}}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>

                                <li><a href="{{URL::to('/')}}/achievers">Achievers</a></li>
                                <li><a href="{{URL::to('/')}}/packages">Profile</a></li>
                                <li><a href="{{URL::to('/')}}/contact-us">Contact Us</a></li>

                            </ul>
                            @if(!Auth::user())
                                <ul class="nav navbar-nav navbar-right hidden-xs">
                                    <li><a class="btn btn-brand" href="{{URL::to('/')}}/signin">Login/Signup</a></li>
                                </ul>
                            @else
                                <ul class="nav navbar-nav float-right new-togel">

                                    <li class="dropdown">
                                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{Auth::user()->name}}<span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{URL::to('/')}}/packages">My Profile</a></li>
                                            <li><a href="{{URL::to('/')}}/my-account">My Account</a></li>
                                            <li><a href="{{URL::to('/')}}/debit-requests">Debit Requests</a></li>
                                            <li><a href="{{URL::to('/')}}/my-works">My Work and Service</a></li>
                                            <li><a href="{{URL::to('/')}}/my-claimed-works">My Claimed Work and Service</a></li>
                                            <li><a href="{{URL::to('/')}}/work-post">Submit Work and Service</a></li>
                                            <li><a href="{{URL::to('/')}}/claim-work">Claim Work and Service</a></li>
                                            <li><a href="{{URL::to('/')}}/logout">Logout</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            @endif
                        </div><!-- /.navbar-collapse -->

                    </nav>
                </div>

            </div>
        </div>
        <!-- Header Bottom Part End -->

    </header>
    <!-- Header Part End -->


    <!-- Slider Section Start -->

@yield('content')
<!-- Top Stores Lists End -->


    <div class="top-stores-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <h2 class="section-heading">Our Own Business</h2>
                </div>

            </div>
<?php $section5 = get_business_slider(); ?>
            <!-- Top Store List Logo Start -->
            <div class="row">
                <div id="new-deal-carousel" class="owl-carousel owl-theme new-stor">
                    @foreach($section5 as $s5)
                        <div class="single-top-store">
                            <a href="{{$s5->link}}">
                                <img src="{{URL::to('/')}}/public/storage/slider-images/{{$s5->image}}">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- Top Store List Logo End -->
        </div>
    </div>

    <!-- Footer Part Start -->
    <footer class="cbx-footer">

        <!-- Footer Top Part Start -->
        <div class="cbx-footer-top">
            <div class="container">
                <div class="row">

                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <!-- Footer widget about Part Start -->
                        <div class="widget widget-about">
                            <div class="widget-brand">
                                <a href="javascript:void(0);">
                                    <img src="{{ asset('/public/')}}/assets/img/logo/workportal-logo1.png"
                                         alt="Warkportal" class="img-responsive"/>
                                </a>
                            </div>
                            <p>The work portal shows the path of employment and development to every person with our
                                ethical values and also increases the business of business associates.</p>
{{--                            <div class="widget-social">--}}
{{--                                <ul>--}}
{{--                                    <li>--}}
{{--                                        <a href="javascript:void(0);">--}}
{{--                                            <i class="fa fa-facebook"></i>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="javascript:void(0);">--}}
{{--                                            <i class="fa fa-twitter"></i>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="javascript:void(0);">--}}
{{--                                            <i class="fa fa-linkedin"></i>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="javascript:void(0);">--}}
{{--                                            <i class="fa fa-vimeo"></i>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
                        </div>
                        <!-- Footer widget about Part End -->
                    </div>

                    <div class="col-md-2 col-sm-6 col-xs-6">
                        <!-- Footer widget listings Part Start -->
                        <div class="widget widget-listings">
                            <h2>Quick links</h2>
                            <ul>
                                <li>
                                    <a href="{{URL::to('/')}}/about-us">About us</a>
                                </li>
                                <li>
                                    <a href="{{URL::to('/')}}/contact-us">Contact us</a>
                                </li>
                                <li>
                                    <a href="{{URL::to('/')}}/terms-and-conditions">Terms and Conditions</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">Press</a>
                                </li>
                                <li>
                                    <a href="{{URL::to('/')}}/privacy-and-cookie-policy">Privacy policies</a>
                                </li>


                            </ul>
                        </div>
                        <!-- Footer widget listings Part End -->
                    </div>

                    <div class="col-md-2 col-sm-6 col-xs-6">
                        <!-- Footer widget listings Part Start -->
                        <div class="widget widget-listings">
                            <h2>Quick link</h2>
                            <ul>
                                <li>
                                    <a href="{{URL::to('/')}}/privacy-and-cookie-policy"> Cookie Policy</a>
                                </li>
                                <li>
                                    <a href="{{URL::to('/')}}/careers">Careers</a>
                                </li>
                                <li>
                                    <a href="{{URL::to('/')}}/blogs">Blog</a>
                                </li>
                                <li>
                                    <a href="{{URL::to('/')}}/faqs">FAQ</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">Logo Guidelines</a>
                                </li>
                                <li>

                                </li>


                            </ul>
                        </div>
                        <!-- Footer widget listings Part End -->
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <!-- Footer widget newsletter Part Start -->
                        <div class="widget widget-newsletter">
                            <div class="widget widget-listings">
                                <h2>Social Media</h2>
                                <ul>
                                    <li>
                                        <a href="https://www.facebook.com/WorkPortal.in">Facebook</a>
                                    </li>
                                    <li>
                                        <a href="https://www.instagram.com/workportal.in">Instagram</a>
                                    </li>
                                    <li>
                                        <a href="https://twitter.com/itsworkportal">Twitter</a>
                                    </li>
                                    <li>
                                        <a href="https://www.youtube.com/channel/UCZpwgQyfV4CNjduZHHRWVCg">Youtube</a>
                                    </li>


                                </ul>
                            </div>
{{--                            <div class="widget-newsletter-area">--}}
                                {{--                                <form id="cbx-subscribe-form" class="navbar-form" role="search">--}}
                                {{--                                    <div class="input-group">--}}
                                {{--                                        <input type="email" id="subscribe" required class="form-control" placeholder="Your Email" name="email">--}}
                                {{--                                        <div class="input-group-btn">--}}
                                {{--                                            <button class="btn btn-default" type="submit"><i class="fa fa-send-o"></i></button>--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}
                                {{--                                </form>--}}
                                {{--                                <p>It you want to connect with us please subcribe by your email.</p>--}}
                                {{--                                <h5>We Accept:</h5>--}}
{{--                                <ul class="payment-gateways">--}}
{{--                                    <li><a href="javascript:void(0);"><img--}}
{{--                                                src="{{ asset('/public/')}}/assets/img/payment_gateway/amex.png"--}}
{{--                                                alt="amex"/></a></li>--}}
{{--                                    <li><a href="javascript:void(0);"><img--}}
{{--                                                src="{{ asset('/public/')}}/assets/img/payment_gateway/mastercard.png"--}}
{{--                                                alt="mastercard"/></a></li>--}}
{{--                                    <li><a href="javascript:void(0);"><img--}}
{{--                                                src="{{ asset('/public/')}}/assets/img/payment_gateway/paypal.png"--}}
{{--                                                alt="paypal"/></a></li>--}}
{{--                                    <li><a href="javascript:void(0);"><img--}}
{{--                                                src="{{ asset('/public/')}}/assets/img/payment_gateway/visa.png"--}}
{{--                                                alt="visa"/></a></li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
                        </div>
                        <!-- Footer widget newsletter Part End -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer Top Part End -->

        <!-- Footer Bottom Part Start -->
        <div class="cbx-footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="copyright text-center">
                            <p>© 2020 Copyright Workportal. All Rights reserved.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer Bottom Part End -->


    </footer>
    <!-- Footer Part End -->

    <!-- Scroll to Top Start -->
    <a href="javascript:void(0);" class="scrollToTop">
        <i class="fa fa-arrow-up"></i>
    </a>
    <!-- Scroll to Top End -->


    <!-- //SITE CONTENT END -->

</div>


<!-- Modal -->
<div id="coupon-code" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Special Flash Sale</h4>
            </div>
            <div class="modal-body">
                <div class="coupon-modal-content">
                    <div class="row">
                        <div class="col-md-5 col-sm-5 col-xs-12">
                            <div class="single-coupon-thumb">
                                <img src="{{ asset('/public/')}}/assets/img/deal/deal12.jpg" alt="Coupon" class="img-thumbnail img-responsive">
                            </div>
                        </div>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                            <p>COPY THIS CODE AND USE AT CHECKOUT</p>
                            <div class="input-group">
                                <input type="text" class="form-control" autocomplete="off" readonly="" value="HFRESH10">
                                <div class="input-group-btn">
                                    <button class="clipboard btn btn-default" data-clipboard-text="HFRESH10"><i class="fa fa-clipboard" aria-hidden="true"></i> Copy to Clipboard</button>
                                </div>
                            </div>
                            <a class="btn btn-brand pull-right" href="javascript:void(0);">Go To Store</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <img src="{{ asset('/public/')}}/assets/img/slider/mini_add.jpg" alt="Coupon" class="img-responsive">
            </div>
        </div>

    </div>
</div>

<!-- Modal -->
<div id="coupon-printable" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Special Flash Sale</h4>
            </div>
            <div class="modal-body">
                <div class="coupon-modal-content">
                    <div class="row">
                        <div class="col-md-5 col-sm-5 col-xs-12">
                            <div class="single-coupon-thumb">
                                <img src="{{ asset('/public/')}}/assets/img/deal/deal12.jpg" alt="Coupon" class="img-thumbnail img-responsive">
                            </div>
                        </div>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                            <p>PRINT THIS COUPON AND REDEEM IT IN-STORE</p>
                            <a class="btn btn-brand" href="javascript:void(0);">Print Now</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <img src="{{ asset('/public/')}}/assets/img/slider/mini_add.jpg" alt="Coupon" class="img-responsive">
            </div>
        </div>

    </div>
</div>

<!-- Modal -->
<div id="coupon-deal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Special Flash Sale</h4>
            </div>
            <div class="modal-body">
                <div class="coupon-modal-content">
                    <div class="row">
                        <div class="col-md-5 col-sm-5 col-xs-12">
                            <div class="single-coupon-thumb">
                                <img src="{{ asset('/public/')}}/assets/img/deal/deal12.jpg" alt="Coupon" class="img-thumbnail img-responsive">
                            </div>
                        </div>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                            <p>DEAL ACTIVATED, NO COUPON CODE REQUIRED!</p>
                            <a class="btn btn-brand" href="javascript:void(0);">Go To Store</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <img src="{{ asset('/public/')}}/assets/img/slider/mini_add.jpg" alt="Coupon" class="img-responsive">
            </div>
        </div>

    </div>
</div>



<!-- SITE SCRIPT  -->

<!-- jquery -->
<script src="{{ asset('/public/')}}/assets/vendor/jquery/jquery-1.11.3.min.js"></script>

<!-- BOOTSTRAP JS -->
<script src="{{ asset('/public/')}}/assets/vendor/bootstrap/js/bootstrap.min.js"></script>

<!-- if load google maps then load this api -->
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyChihC--Jb_QURoXd2MugyC53cDQjrV2MY"></script>

<!-- load if our contact form or email subscribe options is used -->
<script src="{{ asset('/public/')}}/assets/vendor/validation/jquery.validate.js"></script>

<!--owl-->
<script src="{{ asset('/public/')}}/assets/vendor/owl-carousel/dist/owl.carousel.min.js"></script>

<!-- clipboard.js -->
<script src="{{ asset('/public/')}}/assets/vendor/clipboard.js/clipboard.min.js"></script>

<!-- flatpickr -->
<script src="{{ asset('/public/')}}/assets/vendor/flatpickr/flatpickr.js"></script>

<!-- lity -->
<script src="{{ asset('/public/')}}/assets/vendor/lity/lity.min.js"></script>

<!-- Bootstrap Slider -->
<script src="{{ asset('/public/')}}/assets/vendor/bootstrap-slider/bootstrap-slider.min.js"></script>

<script src="{{ asset('/public/')}}/assets/js/theme.js"></script>
<script src='./node_modules/nouislider/distribute/nouislider.min.js'></script>

<!-- CUSTOM SCRIPT  -->
<script src="{{ asset('/public/')}}/assets/js/custom.js"></script>

<script src="{{ asset('/public/js/app.js') }}"></script>

</body>

</html>
