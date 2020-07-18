@extends('frontend.layouts.app')

@push('meta')
    <?php
    $keywords='';
    foreach ( (array)json_decode($course->meta_keywords) as $keyword){
        $keywords.=$keyword.',';
    }
    $keywords= trim($keywords,',');
    ?>
    @include('meta::manager', [
'title'         =>$course->meta_title,
'description'   => $course->meta_description,
'keywords'   =>  $keywords  ])
@endpush
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
                            <small class="d-block"><i class="icon-th"></i> {{ucfirst($lesson->part->name)}} Part</small>
                            <small class="d-block"><i class=" icon-list-alt"></i> {{ucfirst($lesson->name)}} </small>
                            <small class="d-block"><i class="icon-group"></i> {{count($course->users)}} Students</small>

                            <small class="d-block"><i class="icon-doc"></i>   {{count($lesson->files)}}    Files</small>
                            <small class="d-block">    <span class="rating">
                                    @for($i=1;$i<=5;$i++)
                                        @if($i<=$lesson_rate[0])
                                            <i class="icon_star star-active"></i>
                                            @continue;
                                        @elseif(($i-$lesson_rate[0])<1 and ($i-$lesson_rate[0])>0)
                                            <i class="icon-star-half-alt star-active"></i>
                                            @continue;
                                        @else
                                            <i class="icon-star-empty star-not-active"></i>
                                            @continue;
                                        @endif
                                    @endfor
                                   <em>{{round($lesson_rate[0],1).' / 5.00'}} - based on {{$lesson_rate[1]}} reviews</em>
                            </span></small>



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
                    @php $status=checkIfUserHasThisCourse($course); @endphp
                    @if($course->isFree())
                        {{-- Do No Thing --}}
                    @else
                        @if(auth('web')->check())
                            {{-- User  Loged In --}}
                            {{--                            @if(checkIfUserHasThisCourse($course)=='opened')--}}
                            @if($status=='opened')
                                {{-- User  Enrolled  In This Course And Paid  The Course Cost =>nothing --}}
                                {{--                                @elseif(checkIfUserHasThisCourse($course)=='closed')--}}
                            @elseif($status=='closed')
                                <div class="alert alert-warning">Course Pending Until Admin Approve You</div>

                                {{-- User  Enrolled  In This Course And Not Paid  The Course Cost  =>pending --}}
                                <button onclick="return false" disabled class="btn_1 small mb-1 d-block" style="width: 100%;height: 40px;">
                                    <i class="icon-spin3 animate-spin"></i> Pending ..
                                </button>
                            @else
                                <div class="alert alert-danger">Course Is Paid You Must Buy It First</div>
                                {{-- User  Not Enrolled  In This Course =>buy --}}
                                <form method="post" action="{{route('buy')}}">
                                    @csrf
                                    <input type="hidden" name="course_id" value="{{$course->id}}">
                                    <button type="submit" class="btn_1 small mb-1 d-block" style="width: 100%;height: 40px;">
                                        Buy Course
                                    </button>
                                </form>
                            @endif
                        @else
                            <div class="alert alert-danger">Course Is Paid You Must Buy It First</div>
                            {{-- User  Is A Visitor =>buy--}}
                            <form method="post" action="{{route('buy')}}">
                                @csrf
                                <input type="hidden" name="course_id" value="{{$course->id}}">
                                <button type="submit" class="btn_1 small mb-1 d-block" style="width: 100%;height: 40px;">
                                    Buy Course
                                </button>
                            </form>
                        @endif
                    @endif
                </div>


                <div class="row ">
                    <div class="col-sm-12  col-xl-12" >
                        <div class="col-sm-12 " >

                            @if($lesson->type=='youtube')
                                <div class="v-2"></div>
                            @elseif($lesson->type=='cloud')
                                <div id="my_video" type="video/mp4" style="width: 100%;height: 100%"  >

                                    <video  oncontextmenu="return false;"    style="width: 100%;/*border: 0.5px solid;*/" playsinline id="screen"
                                            src="{{$video}}" poster="{{route('storage',['courses_files',$course->photo,$course->id,'photos'])}}"></video>
                                </div>
                                @elseif($lesson->type=='drive')
                                @if($video=='#')
                                    <div>

                                        <img style="height: 100%;width: 100%;"  src="{{route('storage',['courses_files',$course->photo,$course->id,'photos'])}}" alt="{{$course->name}} Intro Photo ">
                                    </div>

                                @else
                                    <iframe src="{{$video=='#'?'':$video}}" style="width: 100%;height: 500px" >

                                    </iframe>
                                @endif


                                @else
                            <!-- HTML5 VIDEO TAG -->
                                <div id="my_video" type="video/mp4" style="width: 100%;height: 100%"  >

                                    <video  oncontextmenu="return false;"    style="width: 100%;border: 0.5px solid;" playsinline id="screen"
                                            src="{{route('course.video.response',[$course->id,$lesson->id])}}" poster="{{route('storage',['courses_files',$course->photo,$course->id,'photos'])}}"></video>
                                </div>
                            @endif
                        </div>
{{--                        <div class=" col-xl-6" >--}}
                        <div class="col-sm-12 " >
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
                                        @foreach($part->lessons as $item)
                                            @if($course->isFree())
                                                {{-- If Course Is Free Show All Lessons  --}}
                                                <li style="width: 100%" >
                                                    <a href="{{route('course.video',[$course->id,$item->id])}}" class="justify-content-sm-between">
                                                        <strong>{{$count1}}.{{$count2++}}</strong> <i class="icon-play-circled"></i>
                                                        {{--                                                    <small class="badge badge-danger">{{CalculateDuration($course->id,$item->video)}} min</small> --}}
                                                        {{$item->name}}
                                                    </a>
                                                </li>
                                            @else
                                                @if($item->status)
                                                    {{-- If Lesson Is Free  Show This Lesson Only --}}
                                                    <li style="width: 100%" >
                                                        <a href="{{route('course.video',[$course->id,$item->id])}}" class="justify-content-sm-between">
                                                            <strong>{{$count1}}.{{$count2++}}</strong> <i class="icon-play-circled"></i>
                                                            {{--                                                        <small class="badge badge-danger">{{CalculateDuration($course->id,$item->video)}} min</small>--}}
                                                            {{$item->name}}
                                                        </a>
                                                    </li>
                                                @else
                                                    @if(auth('web')->check())
                                                        {{--                                                         @if(checkIfUserHasThisCourse($course)=='opened')--}}
                                                        @if($status=='opened')
                                                            <li style="width: 100%" >
                                                                <a href="{{route('course.video',[$course->id,$item->id])}}" class="justify-content-sm-between">
                                                                    <strong>{{$count1}}.{{$count2++}}</strong> <i class="icon-play-circled"></i>
                                                                    {{--                                                                <small class="badge badge-danger">{{CalculateDuration($course->id,$item->video)}} min</small> --}}
                                                                    {{$item->name}}
                                                                </a>
                                                            </li>
                                                        @else
                                                            <li style="width: 100%" >
                                                                <a href="{{route('course.video',[$course->id,$item->id])}}" class="justify-content-sm-between">
                                                                    <strong>{{$count1}}.{{$count2++}}</strong> <i class="icon-play-circled"></i>
                                                                    {{--                                                                <small class="badge badge-danger">{{CalculateDuration($course->id,$item->video)}} min</small> --}}
                                                                    {{$item->name}}   <code class="badge badge-sm badge-danger">(paid)</code>
                                                                </a>
                                                            </li>

                                                        @endif
                                                    @else
                                                        <li style="width: 100%" >
                                                            <a href="{{route('course.video',[$course->id,$item->id])}}" class="justify-content-sm-between">
                                                                <strong>{{$count1}}.{{$count2++}}</strong> <i class="icon-play-circled"></i>
                                                                {{--                                                                <small class="badge badge-danger">{{CalculateDuration($course->id,$item->video)}} min</small> --}}
                                                                {{$item->name}}                 <code class="badge badge-sm badge-danger">(paid)</code>
                                                            </a>
                                                        </li>
                                                        {{--                                                    <li style="width: 100%" >--}}
                                                        {{--                                                        <span class="not-allowed-lesson justify-content-sm-between" disabled onclick="return false">--}}
                                                        {{--                                                            <strong disabled>{{$count1}}.{{$count2++}}</strong> <i disabled class="icon-play-circled"></i>--}}
                                                        {{--                                                            <small class="badge badge-danger"> 00.00 min</small> --}}
                                                        {{--                                                            {{$lesson->name}}--}}
                                                        {{--                                                              <button class="btn btn-sm get-files"><i class="icon-cloud-2"></i></button>--}}
                                                        {{--                                                        </span>--}}
                                                        {{--                                                    </li>--}}
                                                    @endif
                                                @endif
                                            @endif
                                        @endforeach
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-12" >
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active" aria-current="page"><i class="icon-doc-text-1"></i> Attached Files</li>
                                </ol>
                                <div class="card-body">
                                    <ul class="cat_nav">
                                        @foreach($lesson->files as $slide)
                                            @if($course->isFree())
                                                <li><a @if($slide->hosting=='drive') target="_blank" @endif href="{{route('storageFiles.slides',[$course->id,$slide->id])}}" class="" ><i class="icon_document_alt"></i>{{strtoupper($slide->name)}}</a></li>
                                                @else
                                                @if($slide->isFree)
                                                    <li><a @if($slide->hosting=='drive') target="_blank" @endif href="{{route('storageFiles.slides',[$course->id,$slide->id])}}" class="" ><i class="icon_document_alt"></i>{{strtoupper($slide->name)}}</a></li>
                                                    @else
                                                    @if(auth('web')->check())
                                                        @if(checkIfUserHasThisCourse($course)=='opened')

                                                            <li><a @if($slide->hosting=='drive') target="_blank" @endif href="{{route('storageFiles.slides',[$course->id,$slide->id])}}" class="" ><i class="icon_document_alt"></i>{{strtoupper($slide->name)}}</a></li>
                                                        @else
                                                            <li><a class="text-dark"><i class="icon_document_alt"></i>{{strtoupper($slide->name)}}<code class="text-danger"> (paid) </code></a></li>
                                                        @endif
                                                    @else
{{--                                                        <li><span><i class="icon_document_alt"></i>{{strtoupper($slide->name)}}</span></li>--}}
                                                        <li><a class="text-dark"><i class="icon_document_alt"></i>{{strtoupper($slide->name)}}<code class="text-danger"> (paid) </code></a></li>
                                                    @endif
                                                @endif
                                            @endif
                                            @endforeach
                                    </ul>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <div class="container margin_60_35" style="transform: none;">
            <div class="row" style="transform: none;">
                <div class="main_title_3">
                    <h2>Lesson Reviews</h2>
                    <p>Cum doctus civibus efficiantur in imperdiet deterruisset.</p>
                </div>
                <div class="col-lg-8">
                    @php $last_num_to_skip=0;@endphp
                    @forelse($rates as $rate)
                        <div class="review_card">
                            <div class="row">
                                <div class="col-md-2 user_info">
                                    <figure><img  src="{{frontend()}}images/user.png" alt="User Avatar"></figure>
                                    <h5>{{$rate->user->name}}</h5>
                                </div>
                                <div class="col-md-10 review_content">
                                    <div class="clearfix add_bottom_15">

                                        <span class="rating">
                                                @for($i=1;$i<=5;$i++)
                                                @if($i<=$rate->rate)
                                                    <i class="icon-star star-active"></i>
                                                @else
                                                    <i class="icon-star-empty star-not-active"></i>
                                                @endif
                                            @endfor
                                            <em>{{$rate->rate}} / 5.00</em></span>
                                        <em>Published at {{$rate->created_at->format('d-m-Y')}}</em>
                                    </div>
                                    <h4>"{{$rate->title}}"</h4>
                                    <p>
                                        {{$rate->content}}
                                    </p>

                                </div>
                            </div>
                            <!-- /row -->
                        </div>
                        @php $last_num_to_skip++;@endphp
                    @empty
                        <span>
                                  <h6>  No Review Founded ..</h6>
                              </span>
                    @endforelse
                    <span id="more_rates_container"></span>
                    @if(count($rates)!=0)
                        <div  class="text-center mb-3">
                            <button class="btn btn-secondary" id="loadmore" data-value="{{$last_num_to_skip}}">
                                <i id="spinner"></i>
                                Load More
                            </button>
                        </div>
                    @endif
                    @auth('web')
                        @if(!$user_rate)
                            @if($course->isFree()  or $status=='opened'or $lesson->status==true)
                                <div class="box_general write_review">
                                    <h1>Rate Lesson</h1>
                                    <form method="post" autocomplete="off" action="{{route('rate.lesson')}}">
                                        @csrf
                                        <input type="hidden" name="lesson_id" value="{{$lesson->id}}">
                                        <div class="rating_submit">
                                            <div class="form-group">
                                                <label class="d-block">Overall rating</label>
                                                <span class="rating">
								<input type="radio" class="rating-input" id="5_star" name="rate" value="5"><label for="5_star" class="rating-star"></label>
								<input type="radio" class="rating-input" id="4_star" name="rate" value="4"><label for="4_star" class="rating-star"></label>
								<input type="radio" class="rating-input" id="3_star" name="rate" value="3"><label for="3_star" class="rating-star"></label>
								<input type="radio" class="rating-input" id="2_star" name="rate" value="2"><label for="2_star" class="rating-star"></label>
								<input type="radio" class="rating-input" id="1_star" name="rate" value="1"><label for="1_star" class="rating-star"></label>
							</span>
                                            </div>
                                            @error('rating-input')
                                            <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                            @error('course_id')
                                            <span class="d-block text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                        <!-- /rating_submit -->
                                        <div class="form-group">
                                            <label>Title of your review</label>
                                            <input class="form-control @error('title') is-invalid @enderror" name="title" type="text" placeholder="If you could say it in one sentence, what would you say?" value="{{old('title')}}">
                                            @error('title')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Your review</label>
                                            <textarea class="form-control @error('content') is-invalid @enderror" name="content" style="height: 80px;" placeholder="Write your review to help others learn about this online business">{{old('content')}}</textarea>
                                            @error('content')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                        <input type="submit" class="btn_1" value="Submit review">
                                    </form>
                                </div>                      <!-- for add  review -->
                            @endif
                        @else
                            <div class="box_general write_review">
                                <h1>Rate Lesson</h1>
                                <form method="post" autocomplete="off" action="{{route('rate.lesson.update',$user_rate->id)}}">
                                    @csrf
                                    @method('put')
                                    <input type="hidden" name="lesson_id" value="{{$lesson->id}}">
                                    <div class="rating_submit">
                                        <div class="form-group">
                                            <label class="d-block">Overall rating</label>
                                            <span class="rating">
								<input type="radio" @if($user_rate->rate==5) checked @endif class="rating-input" id="5_star" name="rate" value="5"><label for="5_star" class="rating-star"></label>
								<input type="radio" @if($user_rate->rate==4) checked @endif class="rating-input" id="4_star" name="rate" value="4"><label for="4_star" class="rating-star"></label>
								<input type="radio" @if($user_rate->rate==3) checked @endif class="rating-input" id="3_star" name="rate" value="3"><label for="3_star" class="rating-star"></label>
								<input type="radio" @if($user_rate->rate==2) checked @endif class="rating-input" id="2_star" name="rate" value="2"><label for="2_star" class="rating-star"></label>
								<input type="radio" @if($user_rate->rate==1) checked @endif class="rating-input" id="1_star" name="rate" value="1"><label for="1_star" class="rating-star"></label>
							</span>
                                        </div>
                                        @error('rating-input')
                                        <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                        @error('course_id')
                                        <span class="d-block text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <!-- /rating_submit -->
                                    <div class="form-group">
                                        <label>Title of your review</label>
                                        <input class="form-control @error('title') is-invalid @enderror" name="title" type="text" placeholder="If you could say it in one sentence, what would you say?" value="{{$user_rate->title}}">
                                        @error('title')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Your review</label>
                                        <textarea class="form-control @error('content') is-invalid @enderror" name="content" style="height: 80px;" placeholder="Write your review to help others learn about this online business">{{$user_rate->content}}</textarea>
                                        @error('content')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <input type="submit" class="btn btn-success" value="Update review">
                                </form>
                            </div>         <!-- for update old review -->
                        @endif
                    @endauth
                </div>
                <!-- /col -->
                @auth('web')
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

                                                        @if($item['hosting']=='drive')
                                                            <a  href="{{$item['path']}}" target="_blank">
                                                                {{ucfirst($course->code).' - '.ucfirst($item['term']).'  -  '.$item['year']}}
                                                            </a>
                                                        @else
                                                            <a href="{{route('storage.exam',[$item['course_id'],$item['id']])}}" ><i class="icon_document_alt"></i>
                                                                {{ucfirst($course->code).' - '.ucfirst($item['term']).'  -  '.$item['year']}}
                                                            </a>
                                                        @endif

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
                @endauth

            </div>

            <!-- /row -->
        </div>
    </main>
    <!-- /main -->
@endsection

@push('js')


    @if($lesson->type=='youtube')
        <script src="{{frontend()}}js/tinaciousFluidVid.min.js"></script>
        <script>
            $('.v-2').tinaciousFluidVid({type: 'youtube',
                // id: 'YGglqs8YstU'});
                id: '{{$video}}'});
        </script>
        @else
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script src="{{frontend()}}js/video-local.js"></script>
        <script>
            $(document).ready(function() {

                $("#my_video").RTOP_VideoPlayer({
                    autoPlay:false,
                    showTimer:true,
                    showSoundControl:true,
                    showFullScreen:true,
                    keyboardControls: true,
                });
            });
        </script>
        @endif

    <script>
        $('#loadmore').click(function () {
            $('#spinner').addClass('icon-spin5  animate-spin');
            let skip=$(this).data('value');
            let lesson='{{$lesson->id}}';
            setTimeout(function () {
                loadMore(lesson,skip);
                $('#spinner').removeClass('icon-spin5  animate-spin');
            }, 500);


        });
        function loadMore(lessonID,skip)   {
            $.ajax({
                url:'{{route('more.rate.lesson')}}',
                method:'GET',
                data:{
                    lesson_id:lessonID,
                    skip:skip
                },
                success:function (response) {

                    for (var i=0;i<response.rates.length;i++){
                        $('#more_rates_container').append( createReviewCard(response.rates[i]));
                    }
                    if (response.final===false) {
                        let newSkip=skip+4;
                        $('#spinner').removeClass('  animate-spin');
                        $('#loadmore').data('value',newSkip);
                    } else{
                        $('#loadmore').remove();
                    }

                }

            });
        }

        function createReviewCard(data){
            let review_card=document.createElement('div');
            review_card.setAttribute('class','review_card');
            let row=document.createElement('div');
            row.setAttribute('class','row');
            let user_info=document.createElement('div');
            user_info.setAttribute('class',' col-md-2  user_info');
            let figure=document.createElement('figure');
            let img=document.createElement('img');
            let name=document.createElement('h5');
            let review_content=document.createElement('div');
            review_content.setAttribute('class','col-md-10 review_content');
            let clear_fix=document.createElement('div');
            clear_fix.setAttribute('class','clearfix add_bottom_15');
            let rate_span=document.createElement('span');
            rate_span.setAttribute('class','rating');
            let total=document.createElement('em');
            let date=document.createElement('em');
            let title=document.createElement('h4');
            let content=document.createElement('p');

            content.innerHTML=data.content;
            title.innerHTML=data.title;
            total.innerHTML=data.rate+' /5.00';
            let year=data.created_at.substr(0,4);
            let month=data.created_at.substr(5,2);
            let day=data.created_at.substr(8,2);
            date.innerHTML="Published at  "+day+"-"+month+"-"+year;
            for(let i=1;i<=data.rate;i++){
                let active_star=document.createElement('i');
                active_star.setAttribute('class','icon-star star-active');
                rate_span.append(active_star);
            }
            for(let k=1;k<=5-data.rate;k++){
                let not_active_star=document.createElement('i');
                not_active_star.setAttribute('class','icon-star-empty star-not-active');
                rate_span.append(not_active_star);
            }
            clear_fix.append(rate_span);
            clear_fix.append(date);
            review_content.append(clear_fix);
            review_content.append(title);
            review_content.append(content);
            img.src="{{frontend()}}images/user.png";
            figure.append(img);
            name.innerHTML=data.name;
            user_info.append(figure);
            user_info.append(name);
            row.append(user_info);
            row.append(review_content);
            review_card.append(row);
            // console.log(review_card);
            return review_card;
        }
    </script>
@endpush
