@extends('frontend.layouts.app')

    @push('meta')
        <?php
        $keywords='';
        foreach ( (array)json_decode($department->meta_keywords) as $keyword){
            $keywords.=$keyword.',';
        }
        $keywords= trim($keywords,',');
        ?>
        @include('meta::manager', [
    'title'         =>$department->meta_title,
    'description'   => $department->meta_description,
    'keywords'   =>  $keywords  ])
    @endpush

@section('navbar')

    @include('frontend.layouts.navbarv2')

@endsection


@push('css_before')
    <link href="{{frontend()}}css/blog.css" rel="stylesheet">
@endpush
@push('css_after')
    <style>
        .hero_single.office{
            background: #051d4d url('{{frontend()}}img/pattern_2.svg') top center repeat !important;
        }
        a.grid_item .info small{
            background-color: #ffc107;
            color: #000;
        }
        .rating{
            margin-bottom: 10px;
        }
        .rating .star-active{

            color: #ffc107;!important;
            background-color: transparent;
        }
        .rating .star-not-active{

            color: #ddd;!important;
            background-color: transparent;
        }
        .info{
            position: absolute;
            bottom: 10px;
            left: -3px;
        }
        .course-name{
            margin-bottom: -25px;
        }
              .course-footer{
                  margin-top: 15px!important;
              }
    </style>
@endpush


@section('content')
    <main>
        <section class="hero_single office">
            <div class="wrapper">
                <div class="container">
                    <h1>About {{$department->name}}</h1>
                    <p>Vanno helps grow your business using customer reviews</p>
                </div>
            </div>
        </section>
        <!-- /hero_single -->


        <div class="bg_color_1">
            <div class="container margin_80">
                <div class="row d-flex align-items-center">
                    <div class="col-lg-6 pl-lg-5 order-lg-last">
                        <img alt="" src="{{route('storage',['departments_avatars',$department->photo,null,null])}}" class="img-fluid rounded">
                    </div>
                    <div class="col-lg-6 pt-4 order-lg-first">
                        <h2>Details!</h2>
                        <strong class="d-block"> <span class="icon-ok text-success"></span> {{$department->name}}</strong>
                        <strong class="d-block"> <span class="icon-ok text-success"></span> {{$courses}} Courses </strong>
                        <strong class="d-block"> <span class="icon-ok text-success"></span> {{$articles}} Articles </strong>
                        <strong class="d-block"> <span class="icon-ok text-success"></span> {{$exams}} Exams </strong>
                        <strong class="d-block"> <span class="icon-ok text-success"></span> {{$files}} Files </strong>
                    </div>
                </div>
            </div>
            <!-- /container -->
        </div>
        <!-- /bg_color_1 -->


        <div class="bg_color_1">
            <div class="container margin_60">
                <div class="main_title_3">
                    <h2>Latest Articles</h2>
                    <p>Cum doctus civibus efficiantur in imperdiet deterruisset.</p>
                </div>

                <div id="reccomended" class="owl-carousel owl-theme">

                    @foreach($Articles as $item)
                        <div class="item">
                            <article class="blog" >
                                <figure style="height: 180px;">
                                    <a href="{{route('article',$item->slug)}}"><img src="{{route('storage',['courses_files',$item->photo,$item->course_id,'photos'])}}" alt="">
                                        <div class="preview"><span>Read more</span></div>
                                    </a>
                                </figure>
                                <div class="post_info">
                                    <small>{{$item->course->name}} - {{$item->created_at->format('d M. Y')}}</small>
                                    <h2><a href="{{route('article',$item->slug)}}">{{$item->title}}</a></h2>
                                    <p>{{substr($item->subtitle,0,description)}}.</p>
                                    <ul>
                                        <li>
                                            <div class="thumb"><img src="{{route('storage',['instructors_avatars',$item->instructor->photo])}}" alt="{{$item->instructor->photo}} Avatar" title="{{$item->instructor->photo}} Avatar"></div>
                                            {{$item->instructor->name}}
                                        </li>
                                        <li><i class="ti-comment"></i>No Comments</li>
                                    </ul>
                                </div>
                            </article>
                            <!-- /article -->
                        </div>
                    @endforeach



                </div>
                @if(count($Articles)==0)
                    <h5>No Articles Founded ..</h5>
            @endif

                <!-- /carousel -->
            </div>


        </div>

        <div class="container margin_60_35">

            <div class="main_title_3">
                <h2>Latest Courses</h2>
                <p>Cum doctus civibus efficiantur in imperdiet deterruisset.</p>
                @if(count($Courses)>0)
                    <a href="{{route('courses')}}">View all</a>
                @endif

            </div>
            <div class="row">

                <div class="col-lg-12">
                    <div class="row">
                        @forelse($Courses as $course)
                            <div class="col-md-6 col-lg-4 wow bounceInLeft" style="visibility: visible;">
                                <article class="blog">
                                    <figure class="course_figure" style="height: 180px;">
                                        <a href="{{route('course.details',$course->slug)}}">
                                            <img class="img-fluid"  src="{{route('storage',['courses_files',$course->photo,$course->id,'photos'])}}" alt="{{$course->meta_title}}">
                                            <div class="preview"><span>Read more</span>
                                            </div>
                                            <div class="info">

                                                @if($course->price==0)
                                                    <small class="badge badge-primary course_price" style="font-size: 20px">    Free     </small>
                                                @else
                                                    <small class="badge badge-warning course_price" style="font-size: 20px">     {{round($course->price,3).' '.DefaultCurrency()}}        </small>
                                                @endif

                                            </div>
                                        </a>

                                    </figure>
                                    <div class="post_info">
                                        <small class="d-block"><i class=" icon-briefcase-3"></i> {{$course->department->university->name}} - {{$course->department->name}}</small>
                                        <small ><i class=" icon-calendar"></i> {{$course->created_at->format('d M. Y')}}</small>
                                        <h2 class="d-block course-name"><a href="{{route('course.details',$course->slug)}}">{{$course->name}}</a></h2>
                                         <p >{!! substr($course->description,0,description)  !!}...</p>

                                        <small class="d-block" style="margin-top:40px!important;">
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
                                                <div class="thumb"><img src="{{route('storage',['instructors_avatars',$course->instructor->photo,null,null])}}" alt="{{$course->instructor->name}} Avatar"></div>
                                                <span>{{$course->instructor->name}}</span>
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
                            @empty
                            <h5 style="margin-left: 15px !important;">No Courses Founded ..</h5>
                        @endforelse

                    </div>
                    <!-- /row -->



                </div>
            </div>
        </div>



    </main>
    <!--/main-->
@endsection

@push('js')

@endpush
