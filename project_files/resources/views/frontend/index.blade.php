@extends('frontend.layouts.app')

@section('navbar')

         @include('frontend.layouts.navbarv2')

     @endsection

@push('css_before')

    <link rel="stylesheet" href="{{frontend()}}css/video-local.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf"crossorigin="anonymous"/>
    <link href="{{frontend()}}css/blog.css" rel="stylesheet">
@endpush
@push('css_after')
    <link href="{{frontend()}}css/elsheref.css" rel="stylesheet">
    <style>
        .hero_single.version_1{
            background: url('{{frontend()}}home.jpg')   no-repeat ;
        }
        .info{
            position: absolute;
            bottom: 5px;
        }

        .rating .star-active{

            color: #ffc107;!important;
            background-color: transparent;
        }
        .rating .star-not-active{

            color: #ddd;!important;
            background-color: transparent;
        }
    </style>
@endpush


@section('content')

    <main>
        <section class="hero_single version_1" id="home-cover">
            <div class="wrapper">
                <div class="container">
                    <h3>Find Your Favourite Coures!</h3>
                    <p>Check Ratings of Businesses, Read Reviews &amp; Buy</p>
                    <div class="row justify-content-center">
                        <div class="col-lg-9">
                            <form method="get" action="{{route('course.search')}}">
                                <div class="row no-gutters custom-search-input-2">
                                    <div class="col-lg-7">
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="q"  placeholder="What are you looking for...">
                                            <i class="icon_search"></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <select class="wide" name="university">
                                            <option value="all">All Universities</option>
                                            @foreach($universities as $university)
                                                <option value="{{$university->slug}}">{{$university->name}}</option>
                                                @endforeach

                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <input type="submit" value="Search">
                                    </div>
                                </div>
                                <!-- /row -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /hero_single -->

        <div class="container margin_60_35">

            <div class="main_title_3">
                <h2>Top Courses</h2>
                <p>Cum doctus civibus efficiantur in imperdiet deterruisset.</p>
                <a href="all-categories.html">View all</a>
            </div>
            <div class="row">

                <div class="col-lg-12">
                    <div class="row">
                        @foreach($recent_courses as $course )
                        <div class="col-md-6 col-lg-4 wow bounceInLeft">
                            <article class="blog">
                                <figure class="course_figure" style="height: 180px;">
                                    <a href="{{route('course.details',$course->slug)}}">
                                        <img class="img-responsive img-fluid" src="{{route('storage',['courses_files',$course->photo,$course->id,'photos'])}}" alt="{{$course->meta_title}}">
                                        <div class="preview"><span>Read more</span>
                                        </div>
                                        <div class="info">

                                            @if($course->price==0)
                                                <small class="badge badge-primary" style="font-size: 20px">    Free     </small>
                                            @else
                                                {{--                                                    <small class="badge badge-warning course_price" style="font-size: 20px">     {{round($course->price,3).' '.DefaultCurrency()}}        </small>--}}
                                                <small class="badge badge-warning" style="font-size: 20px">     {{round($course->price,3).' '.DefaultCurrency()}}        </small>
                                            @endif

                                        </div>
                                    </a>

                                </figure>
                                <div class="post_info">
                                    <small class="d-block"><i class=" icon-briefcase-3"></i> {{$course->department->university->name}} - {{$course->department->name}}</small>
                                    <small ><i class=" icon-calendar"></i> {{$course->created_at->format('d M.Y')}}</small>
                                    <h2 class="d-block course-name"><a href="{{route('course.details',$course->slug)}}">{{$course->name}}</a></h2>
                                    <p style="margin-top:-10px" >{!! substr($course->description,0,description)  !!}</p>

                                    <small class="d-block" style="margin-top:15px!important;margin-bottom: 5px">
                                        <small ><i class=" icon-user"></i> {{count($course->users)}}</small>
                                        <span class="rating" style="float: right ">
                                                @php
                                                    $course_rate=CalculateCourseRate($course);
                                                @endphp
                                            @for($i=1;$i<=5;$i++)
                                                @if($i<=$course_rate[0])
                                                    <i class="icon_star star-active"></i>
                                                    @continue;
                                                @elseif(($i-$course_rate[0])<1 and ($i-$course_rate[0])>0)
                                                    <i class="icon-star-half-alt star-active"></i>
                                                    @continue;
                                                @else
                                                    <i class="icon-star-empty star-not-active"></i>
                                                    @continue;
                                                @endif
                                            @endfor
                                        </span>
                                    </small>

                                    <ul class="course-footer">
                                        <li>
                                            <div class="thumb"><img src="http://localhost:8080/frontend/assets/images/user.png" alt=""></div>
                                            <span>
                                                    {{$course->instructor->name}}
                                                </span>
                                        </li>
                                        <li style="margin-top: -10px">
                                            <a href="{{route('course.details',$course->slug)}}" class="btn_1 small">Read more</a>
                                        </li>


                                    </ul>
                                </div>
                            </article>
                            <!-- /article -->
                        </div>
                        <!-- /col -->
                           @endforeach
                    </div>
                    <!-- /row -->



                </div>
            </div>
        </div>
        <!-- /container -->

        <div class="bg_color_1">
            <div class="container margin_60">
                <div class="main_title_3">
                    <h2>Latest Posts</h2>
                    <p>Cum doctus civibus efficiantur in imperdiet deterruisset.</p>
                    <a href="{{route('blog')}}">View all</a>
                </div>

                <div id="reccomended" class="owl-carousel owl-theme">



                        @foreach($recent_posts as $post )
                        <div class="item">
                            <article class="blog">
                                <figure style="height: 200px">
                                    <a href="{{route('blog.post',$post->slug)}}"><img src="{{route('storage',['posts_photos',$post->photo,null,null])}}" alt="{{$post->meta_title}}">
                                        <div class="preview"><span>Read more</span></div>
                                    </a>
                                </figure>
                                <div class="post_info">
                                    <small>{{$post->category->name}} - {{$post->created_at->format('d.m.Y')}}</small>
                                    <h2 @if($post->dir=='rtl') class="arabic-dir" @endif><a href="{{route('blog.post',$post->slug)}}">{{$post->title}}</a></h2>
                                    <p @if($post->dir=='rtl') class="arabic-dir" @endif>
                                        {{substr($post->description,0,80)}}
                                    </p>
                                    <ul>
                                        <li>
                                            <div class="thumb"><img src="{{route('storage',['instructors_avatars',$post->instructor->photo,null,null])}}" alt="{{$post->instructor->name}} Avatar"></div> {{$post->instructor->name}}
                                        </li>
                                        <li><i class="ti-comment"></i> No Comments</li>
                                    </ul>
                                </div>
                            </article>
                            <!-- /article -->
                      </div>
                        @endforeach


                </div>
                <!-- /carousel -->
            </div>


        </div>



        <div class="container margin_60_35">
            <div class="main_title_3">
                <h2>Latest Projects</h2>
                <p>Cum doctus civibus efficiantur in imperdiet deterruisset.</p>
                <a href="{{route('projects')}}">View all</a>
            </div>
            <div class="row justify-content-center">
                @foreach($projects as $project)
                    <div class="col-lg-4 col-sm-6">
                        <a href="{{route('project',$project->id)}}" class="grid_item">
                            <figure >
                                <img class="img-responsive img-fluid"  src="{{route('storage',['projects_photos',$project->photo,$project->id,null])}}" alt="">
                                {{--                                <img class="img-responsive img-fluid"  src="{{frontend()}}images/slide_2.jpg" alt="">--}}
                                <div class="info">

                                    <em>   <small>{{$project->type}}</small></em>
                                    <h3>{{$project->name}}</h3>
                                </div>
                            </figure>
                        </a>
                    </div>
                    <!-- /grid_item -->
                @endforeach

            </div>
            <!-- /row -->
        </div>
        <!-- /container -->

        <div class="bg_color_1">
            <div class="container margin_60">
                <div class="main_title_3">
                    <h2>Feedback</h2>
                    <p>Samples Of Our Clients Feedback</p>

                </div>

                <div id="feedback" class="owl-carousel owl-theme">

                    @foreach($feedbackImages_Videos as $feedback)
                    @if($feedback->type=='video')
                            <div class="item">
                                <article class="blog">
                                    <div class="my_video" type="video/mp4" style="width: 100%;height: 100%">
                                        <video poster="{{frontend()}}images/tmp.jpg"  src="{{route('storage',['feedback',$feedback->feedback,'videos',null])}}"  style="width: 100%;border: 0.5px solid;" playsinline id="screen">
                                        </video>
                                    </div>
                                    <div class="post_info">
                                        <small><i class=" icon-briefcase-3"></i> {{ucfirst($feedback->university)}} - {{ucfirst($feedback->department)}}</small>
                                        <small class="d-block"><i class=" icon-calendar"></i> {{date('d M.Y', strtotime($feedback->created_at))}}</small>

                                        <ul>
                                            <li class="client-feedback">
                                               {{$feedback->name}}
                                            </li>
                                            <li>
                                              {{$feedback->country}}
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                                <!-- /article -->
                            </div>
                        @elseif($feedback->type=='audio')
                            <div class="item">
                                <article class="blog">
                                    <div class="my_audio" type="video/mp3" style="width: 100%;height: 100%">
                                        <video  poster="{{frontend()}}images/feedback.png"  src="{{route('storage',['feedback',$feedback->feedback,'audios',null])}}"  style="width: 100%;border: 0.5px solid;" playsinline >
                                        </video>
                                    </div>
                                    <div class="post_info">
                                        <small><i class=" icon-briefcase-3"></i> {{ucfirst($feedback->university)}} - {{ucfirst($feedback->department)}}</small>
                                        <small class="d-block"><i class=" icon-calendar"></i> {{date('d M.Y', strtotime($feedback->created_at))}}</small>

                                        <ul>
                                            <li class="client-feedback">
                                                {{$feedback->name}}
                                            </li>
                                            <li>
                                                {{$feedback->country}}
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                                <!-- /article -->
                            </div>
                        @else
                            <div class="item">
                                <article class="blog">
                                    <figure class="course_figure">
                                        <a href="#" onclick="return false;"><img src="{{route('storage',['feedback',$feedback->feedback,'images',null])}}" alt="">
                                            <div class="preview"><span>Read more</span></div>
                                        </a>
                                    </figure>
                                    <div class="post_info">
                                        <small><i class=" icon-briefcase-3"></i> {{ucfirst($feedback->university)}} - {{ucfirst($feedback->department)}}</small>
                                        <small class="d-block"><i class=" icon-calendar"></i> {{date('d M.Y', strtotime($feedback->created_at))}}</small>

                                        <ul>
                                            <li class="client-feedback">
                                                {{$feedback->name}}
                                            </li>
                                            <li>
                                                {{$feedback->country}}
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                                <!-- /article -->
                            </div>
                        @endif
                    @endforeach



                </div>
                <!-- /carousel -->
            </div>
            <!-- /container -->
        </div>
        <!-- /bg_color_1 -->


    </main>
    <!-- /main -->

@endsection

@push('js')
    <script>
        $('#feedback').owlCarousel({
            center: true,
            items: 3,
            loop: false,
            margin: 0,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },
                767: {
                    items: 2
                },
                1000: {
                    items: 3
                },
                1400: {
                    items: 3
                }
            }
        });
    </script>
    <script src="{{frontend()}}js/video-local.js"></script>
    <script>
        $(".my_video").RTOP_VideoPlayer({
            autoPlay:false,
            showTimer:true,
            showSoundControl:true,
            showFullScreen:true,
            keyboardControls: true,
        });
        $(".my_audio").RTOP_VideoPlayer({
            autoPlay:false,
            showTimer:true,
            showSoundControl:true,
            showFullScreen:true,
            keyboardControls: true,
        });

    </script>
    <script>
        $(document).ready(function () {
            let window_height = $(window).height();
            // $(".hero_single.version_1").css({
            $("#home-cover").css({
                height:window_height+'px'
        });

        });
    </script>
@endpush

