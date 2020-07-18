@extends('frontend.layouts.app')
@section('navbar')

    @include('frontend.layouts.navbarv1')

@endsection


@push('css_before')
    <link href="{{frontend()}}css/blog.css" rel="stylesheet">
@endpush
@push('css_after')
    <link rel="stylesheet" href="{{frontend()}}css/video-local.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf"crossorigin="anonymous"/>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <link href="{{frontend()}}css/toasty.css" rel="stylesheet">
    <link href="{{frontend()}}css/elsheref.css" rel="stylesheet">
    <style>
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
        .reviews_summary figure img{
            height: 100%;
        }
        .not-allowed-lesson{
            /*background-color: #f8f8f8;*/
            background-color: #cacaca;
            padding: 10px;
            display: block;
            margin-bottom: 5px;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            -ms-border-radius: 3px;
            border-radius: 3px;
            position: relative;
            font-weight: 500;
            color: #555;

        }
        .all_categories ul li .not-allowed-lesson strong {
            min-width: 35px;
            background-color: #fff;
            color: #999;
            font-size: 14px;
            font-size: 0.875rem;
            line-height: 1;
            padding: 6px;
            display: inline-block;
            margin-right: 10px;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            -ms-border-radius: 3px;
            border-radius: 3px;
        }
        .all_categories ul li a:after {
            font-family: 'ElegantIcons';
            content: "\24";
            right: 15px;
            top: 15px;
            position: absolute;
            color: #3578fa;
        }
        .all_categories ul li a:hover {
            background-color: #3578fa;
            color: #fff;
        }
        .all_categories ul li a:hover:after {
            color: #fff;
        }
        @media (max-width: 991px) {
            .all_categories ul li {
                float: none;
                width: 100%;
                margin: 0;
            }
        }
        .rtopVideoPlayerWrapper .rtopVideoPlayer .rtopVideoHolder:before{
            height: 100%;
        }
        .all_categories{
            height: 300px!important;
        }
        .order-btn{
            padding:  20px 0px;
        }
        .get-files{
            float: right;
        }
    </style>
@endpush


@section('content')
    <main class="margin_main_container">

        <div class="reviews_summary">
            <div class="wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8">
                            <figure>
                                <img  src="{{route('storage',['courses_files',$course->photo,$course->id,'photos'])}}" alt="{{$course->name}} Intro Photo ">
                            </figure>
                            <small>{{ucfirst($course->department->university->name)}} - {{$course->department->name}}</small>
                            <small class="d-block text-white"><i class="icon-dollar-1"></i> {{$course->price}} {{DefaultCurrency()}}</small>
                            <h1>{{ucfirst($course->name)}}</h1>

                            <span class="rating">
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
                                   <em>{{round($course_rate[0],1).' / 5.00'}} - based on {{$course_rate[1]}} reviews</em>
                            </span>
                        </div>
                          <div class="col-lg-4">
                              <small class="d-block"><i class="icon-user"></i> {{ucfirst($course->instructor->title).' '.ucfirst($course->instructor->name)}} </small>
                              <small class="d-block"><i class="icon-th"></i> {{count($course->parts)}} Parts</small>
                              <small class="d-block"><i class=" icon-doc-text-inv"></i> {{count($course->files)}} Exams</small>
                              <small class="d-block"><i class="icon-group"></i> {{count($course->users)}} Students</small>
                              <?php $lessons=0;$files=0; ?>
                              @foreach($course->parts as $part)
                                  @php
                                      $lessons+=count($part->lessons);
                                      foreach ($part->lessons as $lesson)
                                      $files+=count($lesson->files);
                                  @endphp
                              @endforeach

                              <small class="d-block"><i class="icon-play-circled"></i>   {{$lessons}}    Lessons</small>
                              <small class="d-block"><i class="icon-doc"></i>   {{$files}}    Files</small>


                          </div>
                    </div>
                </div>
                <!-- /container -->
            </div>
        </div>
        <!-- /reviews_summary -->


        <div class="bg_color_1">
            <div class="container ">

                  <div class="main_title_3 text-center order-btn">
                      @if(!$course->isFree())   {{--course is paid--}}
                      @if(auth('web')->check())
                          @php
                              $state=getStudentState($course->id);
                              @endphp
                          @if($state=='closed')   {{--user already enrolled in the course--}}
                          <button onclick="return false" disabled class="btn_1 small mb-1">
                              <i class="icon-spin3 animate-spin"></i> Pending ..
                          </button>
                          @elseif($state=='opened')
                          @else
                              <form method="post" action="{{route('buy')}}">
                                  @csrf
                                  <input type="hidden" name="course_id" value="{{$course->id}}">
                                  <button type="submit" class="btn_1 small mb-1">
                                      Buy Course
                                  </button>
                              </form>
                          @endif
                      @else
                          <form method="post" action="{{route('buy')}}">
                              @csrf
                              <input type="hidden" name="course_id" value="{{$course->id}}">
                              <button type="submit" class="btn_1 small mb-1">
                                  Buy Course
                              </button>
                          </form>
                      @endif

                      @endif
                  </div>


                <div class="row ">
                    <div class="col-sm-12  col-xl-12" >
                        <!--                        <div class="col-sm-12 " >-->
                        <!-- HTML5 VIDEO TAG -->
                        <div id="my_video" type="video/mp4" style="width: 100%;height: 100%">
                            <video  oncontextmenu="return false;"   style="width: 100%;border: 0.5px solid;" playsinline id="screen">

                            </video>

                        </div>

                        <!-- LAZY LOAD (preferred way for quicker page load)-->
                        <!--                    <div id="my_video" data-type="video/mp4" data-poster="includes/poster.jpg" ></div>-->
                    </div>
                    <div class="col-sm-12 col-xl-6" >
                        <!--                        <div class="col-sm-12 " >-->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page"><i class="icon-play-circled"></i> Lessons</li>
                            </ol>
                        </nav>
                        <div class="all_categories clearfix " style="height: 225px;overflow: auto;overflow-x: hidden" id="videos">
                            <ul  style="width: 100%;margin: 0px">
                                @php $count1=0;$count2=0;@endphp
                                @foreach($course->parts as $part)
                                    @php $count1++;$count2=0;@endphp
                                    <li>
                                        <small class="text-white badge badge-secondary">Part {{$part->name}}</small>
                                    </li>
                                    @foreach($part->lessons as $lesson)
                                        @if(CheckLessonState($course,$lesson))
                                            <li style="width: 100%" >
                                                <a href="{{route('storage.secured',[$course->id,$lesson->id])}}" class="justify-content-sm-between">
                                                    <strong>{{$count1}}.{{$count2++}}</strong> <i class="icon-play-circled"></i> <small class="badge badge-danger">{{CalculateDuration($course->id,$lesson->video)}} min</small> {{$lesson->name}}
                                                </a>
                                            </li>
                                            @else
                                            <li style="width: 100%" >
                                                <span class="not-allowed-lesson justify-content-sm-between" disabled onclick="return false">
                                                    <strong disabled>{{$count1}}.{{$count2++}}</strong> <i disabled class="icon-play-circled"></i> <small class="badge badge-danger"> 00.00 min</small> {{$lesson->name}}
                                                      <button class="btn btn-sm get-files"><i class="icon-cloud-2"></i></button>
                                                </span>
                                            </li>
                                            @endif

                                        @endforeach
                                @endforeach
                            </ul>
                        </div>

                    </div>
                    <div class="col-sm-12 col-xl-6" >
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page"><i class="icon-doc-text-1"></i> Attached Files</li>
                            </ol>
                            <div class="card-body">
                                <ul class="cat_nav">
                                    <li><a href="#payment" class=""><i class="icon_document_alt"></i>2017</a></li>
                                    <li><a href="#tips" class=""><i class="icon_document_alt"></i>2018</a></li>
                                    <li><a href="#reccomendations" class="active"><i class="icon_document_alt"></i>2019</a></li>
                                    <li><a href="#terms" class=""><i class="icon_document_alt"></i>2020</a></li>
                                    <li><a href="#booking" class=""><i class="icon_document_alt"></i>2022</a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>


                </div>
            </div>
        </div>


        <!-- /container -->
        <div class="container margin_60_35" style="transform: none;">
            <div class="row" style="transform: none;">

                <div class="col-lg-8">

                    <div class="review_card">
                        <div class="row">
                            <div class="col-md-2 user_info">
                                <figure><img src="img/avatar7.jpg" alt=""></figure>
                                <h5>Lukas</h5>
                            </div>
                            <div class="col-md-10 review_content">
                                <div class="clearfix add_bottom_15">
                                        <span class="rating">
                                            <i class="icon_star"></i>
                                            <i class="icon_star"></i>
                                            <i class="icon_star"></i>
                                            <i class="icon_star"></i>
                                            <i class="icon_star"></i>
                                            <em>5.00/5.00</em></span>
                                    <em>Published 54 minutes ago</em>
                                </div>
                                <h4>"Avesome Experience"</h4>
                                <p>Eos tollit ancillae ea, lorem consulatu qui ne, eu eros eirmod scaevola sea. Et nec tantas accusamus salutatus, sit commodo veritus te, erat legere fabulas has ut. Rebum laudem cum ea, ius essent fuisset ut. Viderer petentium cu his. Tollit molestie suscipiantur his et.</p>

                            </div>
                        </div>
                        <!-- /row -->
                    </div>
                    <div class="box_general write_review">
                        <h1>Rate Course</h1>
                        <div class="rating_submit">
                            <div class="form-group">
                                <label class="d-block">Overall rating</label>
                                <span class="rating">
								<input type="radio" class="rating-input" id="5_star" name="rating-input" value="5 Stars"><label for="5_star" class="rating-star"></label>
								<input type="radio" class="rating-input" id="4_star" name="rating-input" value="4 Stars"><label for="4_star" class="rating-star"></label>
								<input type="radio" class="rating-input" id="3_star" name="rating-input" value="3 Stars"><label for="3_star" class="rating-star"></label>
								<input type="radio" class="rating-input" id="2_star" name="rating-input" value="2 Stars"><label for="2_star" class="rating-star"></label>
								<input type="radio" class="rating-input" id="1_star" name="rating-input" value="1 Star"><label for="1_star" class="rating-star"></label>
							</span>
                            </div>
                        </div>
                        <!-- /rating_submit -->
                        <div class="form-group">
                            <label>Title of your review</label>
                            <input class="form-control" type="text" placeholder="If you could say it in one sentence, what would you say?">
                        </div>
                        <div class="form-group">
                            <label>Your review</label>
                            <textarea class="form-control" style="height: 80px;" placeholder="Write your review to help others learn about this online business"></textarea>
                        </div>


                        <a href="confirm.html" class="btn_1">Submit review</a>
                    </div>
                </div>
                <!-- /col -->
                <div class="col-lg-4">



                    <div role="tablist" class="add_bottom_45 accordion_2" id="tips1">
                        @foreach($exams as $exam)

                            <div class="card">
                                <div class="card-header" role="tab">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" href="#exam_{{$exam[0]['id']}}" aria-expanded="false" class="collapsed"><i class="indicator ti-plus"></i> {{strtoupper($exam[0]['type'])}} Exams</a>
                                    </h5>
                                </div>

                                <div id="exam_{{$exam[0]['id']}}" class="collapse" role="tabpanel" data-parent="#tips1" style="">
                                    <div class="card-body">
                                        <ul class="cat_nav">
                                            @foreach($exam as $item)

                                            <li>
                                                <a href="{{route('storageFiles',[$item['course_id'],$item['path'],ucfirst($item['type']).'_'.ucfirst($item['term']).'_'.$item['year']])}}" ><i class="icon_document_alt"></i>
                                                    {{ucfirst($item['term']).'  -  '.$item['year']}}
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- /card -->
                            @endforeach

                    </div>
                </div>
            </div>

            <!-- /row -->
        </div>


















        <div class="container margin_60_35">
            <div class="row">
                <div class="col-lg-8">
{{--                    @php $last_num_to_skip=0;@endphp--}}
{{--                    @forelse($rates as $rate)--}}
{{--                        <div class="review_card">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-md-2 user_info">--}}
{{--                                    <figure><img  src="{{frontend()}}images/user.png" alt="User Avatar"></figure>--}}
{{--                                    <h5>{{$rate->user->name}}</h5>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-10 review_content">--}}
{{--                                    <div class="clearfix add_bottom_15">--}}

{{--                                        <span class="rating">--}}
{{--                                                @for($i=1;$i<=5;$i++)--}}
{{--                                                @if($i<=$rate->rate)--}}
{{--                                                    <i class="icon-star star-active"></i>--}}
{{--                                                @else--}}
{{--                                                    <i class="icon-star-empty star-not-active"></i>--}}
{{--                                                @endif--}}
{{--                                            @endfor--}}
{{--                                            <em>{{$rate->rate}} / 5.00</em></span>--}}
{{--                                        <em>Published at {{$rate->created_at->format('d-m-Y')}}</em>--}}
{{--                                    </div>--}}
{{--                                    <h4>"{{$rate->title}}"</h4>--}}
{{--                                    <p>--}}
{{--                                        {{$rate->content}}--}}
{{--                                    </p>--}}

{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <!-- /row -->--}}
{{--                        </div>--}}
{{--                        @php $last_num_to_skip++;@endphp--}}
{{--                    @empty--}}
{{--                        <span>--}}
{{--                                  <h6>  No Review Founded ..</h6>--}}
{{--                              </span>--}}
{{--                    @endforelse--}}
{{--                    <span id="more_rates_container"></span>--}}
{{--                    @if(count($rates)!=0)--}}
{{--                        <div  class="text-center mb-3">--}}
{{--                            <button class="btn btn-secondary" id="loadmore" data-value="{{$last_num_to_skip}}">--}}
{{--                                <i id="spinner"></i>--}}
{{--                                Load More--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                    @auth('web')--}}
{{--                        @if(!$user_rate)--}}
{{--                            @if($course->price==0 or $user_has_this_course==true)--}}
{{--                                <div class="box_general write_review">--}}
{{--                                    <h1>Rate Course</h1>--}}
{{--                                    <form method="post" autocomplete="off" action="{{route('rate.course')}}">--}}
{{--                                        @csrf--}}
{{--                                        <input type="hidden" name="course_id" value="{{$course->id}}">--}}
{{--                                        <div class="rating_submit">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label class="d-block">Overall rating</label>--}}
{{--                                                <span class="rating">--}}
{{--								<input type="radio" class="rating-input" id="5_star" name="rate" value="5"><label for="5_star" class="rating-star"></label>--}}
{{--								<input type="radio" class="rating-input" id="4_star" name="rate" value="4"><label for="4_star" class="rating-star"></label>--}}
{{--								<input type="radio" class="rating-input" id="3_star" name="rate" value="3"><label for="3_star" class="rating-star"></label>--}}
{{--								<input type="radio" class="rating-input" id="2_star" name="rate" value="2"><label for="2_star" class="rating-star"></label>--}}
{{--								<input type="radio" class="rating-input" id="1_star" name="rate" value="1"><label for="1_star" class="rating-star"></label>--}}
{{--							</span>--}}
{{--                                            </div>--}}
{{--                                            @error('rating-input')--}}
{{--                                            <span class="text-danger" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                            @enderror--}}
{{--                                            @error('course_id')--}}
{{--                                            <span class="d-block text-danger" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                        <!-- /rating_submit -->--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label>Title of your review</label>--}}
{{--                                            <input class="form-control @error('title') is-invalid @enderror" name="title" type="text" placeholder="If you could say it in one sentence, what would you say?" value="{{old('title')}}">--}}
{{--                                            @error('title')--}}
{{--                                            <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label>Your review</label>--}}
{{--                                            <textarea class="form-control @error('content') is-invalid @enderror" name="content" style="height: 80px;" placeholder="Write your review to help others learn about this online business">{{old('content')}}</textarea>--}}
{{--                                            @error('content')--}}
{{--                                            <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                        <input type="submit" class="btn_1" value="Submit review">--}}
{{--                                    </form>--}}
{{--                                </div>                      <!-- for add  review -->--}}
{{--                            @endif--}}
{{--                        @else--}}
{{--                            <div class="box_general write_review">--}}
{{--                                <h1>Rate Course</h1>--}}
{{--                                <form method="post" autocomplete="off" action="{{route('rate.course.update',$user_rate->id)}}">--}}
{{--                                    @csrf--}}
{{--                                    @method('put')--}}
{{--                                    <input type="hidden" name="course_id" value="{{$course->id}}">--}}
{{--                                    <div class="rating_submit">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label class="d-block">Overall rating</label>--}}
{{--                                            <span class="rating">--}}
{{--								<input type="radio" @if($user_rate->rate==5) checked @endif class="rating-input" id="5_star" name="rate" value="5"><label for="5_star" class="rating-star"></label>--}}
{{--								<input type="radio" @if($user_rate->rate==4) checked @endif class="rating-input" id="4_star" name="rate" value="4"><label for="4_star" class="rating-star"></label>--}}
{{--								<input type="radio" @if($user_rate->rate==3) checked @endif class="rating-input" id="3_star" name="rate" value="3"><label for="3_star" class="rating-star"></label>--}}
{{--								<input type="radio" @if($user_rate->rate==2) checked @endif class="rating-input" id="2_star" name="rate" value="2"><label for="2_star" class="rating-star"></label>--}}
{{--								<input type="radio" @if($user_rate->rate==1) checked @endif class="rating-input" id="1_star" name="rate" value="1"><label for="1_star" class="rating-star"></label>--}}
{{--							</span>--}}
{{--                                        </div>--}}
{{--                                        @error('rating-input')--}}
{{--                                        <span class="text-danger" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                        @enderror--}}
{{--                                        @error('course_id')--}}
{{--                                        <span class="d-block text-danger" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
{{--                                    <!-- /rating_submit -->--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label>Title of your review</label>--}}
{{--                                        <input class="form-control @error('title') is-invalid @enderror" name="title" type="text" placeholder="If you could say it in one sentence, what would you say?" value="{{$user_rate->title}}">--}}
{{--                                        @error('title')--}}
{{--                                        <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label>Your review</label>--}}
{{--                                        <textarea class="form-control @error('content') is-invalid @enderror" name="content" style="height: 80px;" placeholder="Write your review to help others learn about this online business">{{$user_rate->content}}</textarea>--}}
{{--                                        @error('content')--}}
{{--                                        <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
{{--                                    <input type="submit" class="btn btn-success" value="Update review">--}}
{{--                                </form>--}}
{{--                            </div>         <!-- for update old review -->--}}
{{--                        @endif--}}
{{--                    @endauth--}}
                </div>
                <!-- /col -->

                <div class="col-lg-4">
                </div>
            </div>
            <!-- /row -->
        </div>




















    </main>
    <!-- /main -->
@endsection

@push('js')
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="{{frontend()}}js/toasty.js" > </script>
{{--    <script src="{{frontend()}}js/toast_config.js" > </script>--}}
    <script src="{{frontend()}}js/video-local.js"></script>
    <script>
        var links;
        $(document).ready(function() {
            let  all=document.getElementById('videos');

            links=all.getElementsByTagName('a');
            for(let i=0;i<links.length;i++){
                links[i].onclick=handler;
            }
        });
    </script>
    <script>
        var flag=0;
        function handler(e) {

            e.preventDefault();
            let videoTarget=this.getAttribute('href');
            // let video=document.querySelector('#my_video'); //for div
            // video.setAttribute('data-video',videoTarget);     //for div
            let video=document.querySelector('#screen'); //for video
            video.setAttribute('src',videoTarget);     //for video
            $("#my_video").RTOP_VideoPlayer({
                autoPlay:true,
                showTimer:true,
                showSoundControl:true,
                showFullScreen:true,
                keyboardControls: true,
            });

            video.load();
            video.play();


            let anchors=document.getElementById('videos').getElementsByTagName('a');
            for(let i=0;i<anchors.length;i++){
                anchors[i].children[1].setAttribute('class','icon-play-circled');
            }
            this.children[1].setAttribute('class','icon-pause-2');

            var options = {
                // STRING: main class name used to styling each toast message with CSS:
                // .... IMPORTANT NOTE:
                // .... if you change this, the configuration consider that you´re
                // .... re-stylized the plug-in and default toast styles, including CSS3 transitions are lost.
                classname: "toast",
                // STRING: name of the CSS transition that will be used to show and hide all toast by default:
                transition: "slideDownUpFade",
                // BOOLEAN: specifies the way in which the toasts will be inserted in the HTML code:
                // .... Set to BOOLEAN TRUE and the toast messages will be inserted before those already generated toasts.
                // .... Set to BOOLEAN FALSE otherwise.
                insertBefore: true,
                // INTEGER: duration that the toast will be displayed in milliseconds:
                // .... Default value is set to 4000 (4 seconds).
                // .... If it set to 0, the duration for each toast is calculated by text-message length.
                duration:3000,
                // BOOLEAN: enable or disable toast sounds:
                // .... Set to BOOLEAN TRUE  - to enable toast sounds.
                // .... Set to BOOLEAN FALSE - otherwise.
                // NOTE: this is not supported by mobile devices.
                enableSounds: true,
                // BOOLEAN: enable or disable auto hiding on toast messages:
                // .... Set to BOOLEAN TRUE  - to enable auto hiding.
                // .... Set to BOOLEAN FALSE - disable auto hiding. Instead the user must click on toast message to close it.
                autoClose: true,
                // BOOLEAN: enable or disable the progressbar:
                // .... Set to BOOLEAN TRUE  - enable the progressbar only if the autoClose option value is set to BOOLEAN TRUE.
                // .... Set to BOOLEAN FALSE - disable the progressbar.
                progressBar: true,
                // IMPORTANT: mobile browsers does not support this feature!
                // Yep, support custom sounds for each toast message when are shown if the
                // enableSounds option value is set to BOOLEAN TRUE:
                // NOTE: the paths must point from the project's root folder.
                sounds: {
                    // path to sound for informational message:
                    info: "{{frontend()}}sounds/info/1.mp3",
                    // path to sound for successfull message:
                    success: "{{frontend()}}sounds/success/1.mp3",
                    // path to sound for warn message:
                    warning: "{{frontend()}}sounds/warning/1.mp3",
                    // path to sound for error message:
                    error: "{{frontend()}}sounds/error/1.mp3",
                },

                // callback:
                // onShow function will be fired when a toast message appears.
                onShow: function (type) {},

                // callback:
                // onHide function will be fired when a toast message disappears.
                onHide: function (type) {},

                // the placement where prepend the toast container:
                prependTo: document.body.childNodes[0]
            };

// more js code...
            if (flag===0){
                var myToast = new Toasty(options);
                myToast.error("Please Enable The Sounds To Hear The Lesson");
                flag=1;
            }

        }

    </script>


@endpush
