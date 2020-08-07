@extends('app')
@section('content')

    <div class="blog-detal-aj-new">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="section-heading">Blog Details</h2>
                </div>
            </div>
            <div class="row">
                <h3 class="hidden-md hidden-lg hidden-xl">{{$blog->name}}</h3>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="blog-thumb-aj">
                        <a href="javascript:void(0);">
                            <img src="{{ asset('/public/')}}/storage/blog-images/{{$blog->image}}" alt="Blog" class="img-responsive img-rounded"/>
                        </a>

                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="blog-thumb-aj">
                        <iframe width="100%" height="362px" src="{{$blog->video}}" frameborder="0"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <h3 class="hidden-xs hidden-sm">{{$blog->name}}</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="blog-content">
                        <div class="blog-tags">
                            <ul>
{{--                                <li class="blog-author"><a href="#"><i class="fa fa-user" aria-hidden="true"></i>Adam Smith</a></li>--}}
                                <li class="blog-date"><a href="#"><i class="fa fa-clock-o" aria-hidden="true"></i>{{date("d/m/Y h:iA",strtotime($blog->created_at))}}</a></li>
                            </ul>
                        </div>
                       {!! $blog->description !!}
                    </div>
                </div>
            </div>
            <div id="share-buttons">
                <!-- Facebook --> <a href="https://www.facebook.com/sharer.php?u={{URL::to('/')}}/blog-details/{{$blog->id}}" target="_blank"><img height="50px" width="50px" src="https://4.bp.blogspot.com/-raFYZvIFUV0/UwNI2ek6i3I/AAAAAAAAGSA/zs-kwq0q58E/s1600/facebook.png" alt="Facebook" /></a>
                <!-- Twitter --> <a href="https://twitter.com/share?url={{URL::to('/')}}/blog-details/{{$blog->id}}" target="_blank"><img height="50px" width="50px" src="https://4.bp.blogspot.com/--ISQEurz3aE/UwNI4hDaQMI/AAAAAAAAGS4/ZAgmPiM9Xpk/s1600/twitter.png" alt="Twitter" /></a>
                <!-- LinkedIn --> <a href="https://www.linkedin.com/shareArticle?mini=true&url={{URL::to('/')}}/blog-details/{{$blog->id}}" target="_blank"><img height="50px" width="50px" src="https://2.bp.blogspot.com/-3_cATk7Wlho/UwNI3eoTTLI/AAAAAAAAGSQ/Y8cpq6S-SeQ/s1600/linkedin.png" alt="LinkedIn" /></a>
                <!-- whatsapp --><a target="_blank" href="https://web.whatsapp.com/send?text={{URL::to('/')}}/blog-details/{{$blog->id}}" data-action="share/whatsapp/share" target="_blank"><img src="https://www.stickpng.com/assets/images/580b57fcd9996e24bc43c543.png"  height="50px" width="50px"/></a>

            </div>
        </div>
    </div>


@endsection
