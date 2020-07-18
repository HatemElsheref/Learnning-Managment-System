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
    <link rel="stylesheet" href="{{frontend()}}css/video-local.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf"crossorigin="anonymous"/>
@endpush
@push('css_after')
    <link href="{{frontend()}}css/blog.css" rel="stylesheet">

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
    </style>
@endpush


@section('content')
    <main>


        <div class="reviews_summary">
            <div class="wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8">
                            <figure>
                                <img  src="{{route('storage',['courses_files',$course->photo,$course->id,'photos'])}}" alt="{{$course->name}} Intro Photo ">
                            </figure>
                            <small>{{ucfirst($course->department->university->name)}} - {{$course->department->name}}</small>
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

                    </div>
                </div>
                <!-- /container -->
            </div>
        </div>
        <!-- /reviews_summary -->

        <div class="container margin_60_35">
            <div class="row">
                <div class="col-lg-8">
                    <div class="singlepost">
                        <figure>
                            <img  style="height: 500px;width: 100%;" alt="Course Main Image" class="img-fluid" src="{{route('storage',['courses_files',$course->photo,$course->id,'photos'])}}"></figure>
                        <h2>Course Description</h2>

                        <!-- /post meta -->
                        <div class="post-content">
                            <div class="dropcaps">
                                <p>
                                    {!! $course->description!!}
                                </p>
                            </div>
                        </div>
                        <!-- /post -->
                    </div>
                    <!-- /single-post -->
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
                          @if($course->price==0 or $user_has_this_course==true)
                            <div class="box_general write_review">
                                <h1>Rate Course</h1>
                                <form method="post" autocomplete="off" action="{{route('rate.course')}}">
                                    @csrf
                                    <input type="hidden" name="course_id" value="{{$course->id}}">
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
                                <h1>Rate Course</h1>
                                <form method="post" autocomplete="off" action="{{route('rate.course.update',$user_rate->id)}}">
                                    @csrf
                                    @method('put')
                                    <input type="hidden" name="course_id" value="{{$course->id}}">
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
                <div class="col-lg-4">
                    <div class="box_general company_info">
                           @if($course->intro=='local')
                        <div id="my_video" type="video/mp4" style="width: 100%;height: 100%">
                            <video  oncontextmenu="return false;" src="{{route('storage',['courses_files',$course->video,$course->id,null])}}" poster="{{route('storage',['courses_files',$course->photo,$course->id,'photos'])}}"  style="width: 100%;border: 0.5px solid;" playsinline id="screen">
                            </video>
                        </div>
                               @else
                            <div class="v-2"></div>
                        @endif
                        <br>


                        <hr>
                        <p>
                            <strong>
                                <i class="icon-home-1"></i> {{ucfirst($course->department->university->name)}}
                            </strong>
                        </p>
                        <p>
                            <strong>
                                <i class="icon-layout"></i> {{ucfirst($course->department->name)}}
                            </strong>
                        </p>
                        <p>
                            <strong>
                                <i class="icon-user"></i> {{ucfirst($course->instructor->name)}}
                            </strong>
                        </p>
                        <p>
                            <strong>
                                @if($course->price==0)
                                    <i class="icon-dollar-1"></i> <span class="badge badge-primary">Free</span>
                                @else
                                    <i class="icon-dollar-1"></i> <span class="badge badge-warning">{{$course->price.' '.DefaultCurrency()}}</span>
                                @endif
                            </strong>
                        </p>
                        <p>
                            <strong>
                                <i class="icon-play-circled"></i>
                                <?php $lessons=0;$files=0; ?>
                                @foreach($course->parts as $part)
                                    @php
                                    $lessons+=count($part->lessons);
                                    foreach ($part->lessons as $lesson)
                                    $files+=count($lesson->files);
                                    @endphp
                                @endforeach
                                {{$lessons}}    Lessons
                            </strong>
                        </p>
                               <p>
                                   <strong>
                                       <i class="icon-doc-text-inv"></i>   {{$files}} Attached Files
                                   </strong>
                               </p>
                        <p>
                            <strong>
                                <i class="icon-doc-text-1"></i> {{count($course->files)}} Exams
                            </strong>
                        </p>
                               <p>
                                   <strong>
                                       <i class="icon-edit"></i> {{count($course->articles)}} Articles
                                   </strong>
                               </p>
                               <p>
                                   <strong>
                                       <i class="icon-group"></i> {{count($course->users)}} students
                                   </strong>
                               </p>
                        <p>
                            <strong>
                                <i class="icon-calendar"></i>   {{$course->created_at->format('d M Y')}}
                            </strong>
                        </p>
                        <p>

                        </p>
                        <div class="text-center mb-3">
                            <a href="{{route('course.lessons',$course->slug)}}"  class="btn_1 small">
                                @if(checkIfUserHasThisCourse($course)==false)
                                    Enroll Now
                                    @else
                                    Open
                                @endif
                            </a>

                        </div>
                    </div>
                </div>
            </div>

            <!-- /row -->
        </div>
        <!-- /container -->
        <div class="bg_color_1">
            <div class="container margin_60">
                <div class="main_title_3">
                    <h2>Latest Articles</h2>
                    <p>Cum doctus civibus efficiantur in imperdiet deterruisset.</p>
                    <a href="{{route('articles',$course->slug)}}">View all</a>
                </div>

                <div id="reccomended" class="owl-carousel owl-theme">
                    @forelse($course->articles as $article)
                        <div class="item">
                            <article class="blog">
                                <figure style="height: 200px">
                                    <a href="{{route('article',$article->slug)}}"><img src="{{route('storage',['courses_files',$article->photo,$article->course_id,'photos'])}}" alt="{{$article->meta_title}}">
                                        <div class="preview"><span>Read more</span></div>
                                    </a>
                                </figure>
                                <div class="post_info">
                                    <small>{{$article->course->name}} - {{$article->created_at->format('d.m.Y')}}</small>
                                    <h2 @if($article->dir=='rtl') class="arabic-dir" @endif><a href="{{route('article',$article->slug)}}">{{$article->title}}</a></h2>
                                    <p>{{substr($article->subtitle,0,description)}}</p>
                                    <ul>
                                        <li>
                                            <div class="thumb"><img src="{{route('storage',['instructors_avatars',$article->instructor->photo,null,null])}}" alt="{{$article->instructor->name}} Avatar"></div> {{$article->instructor->name}}
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



    </main>
    <!--/main-->
@endsection

@push('js')

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="{{frontend()}}js/video-local.js"></script>
    <script>
        $("#my_video").RTOP_VideoPlayer({
            autoPlay:false,
            showTimer:true,
            showSoundControl:true,
            showFullScreen:true,
            keyboardControls: false,
        });
    </script>

    <script src="{{frontend()}}js/tinaciousFluidVid.min.js"></script>
    <script>
        $('.v-2').tinaciousFluidVid({type: 'youtube',
            // id: 'YGglqs8YstU'});
            id: '{{$course->video}}'});
    </script>
    <script>
        $('#loadmore').click(function () {
            $('#spinner').addClass('icon-spin5  animate-spin');
            let skip=$(this).data('value');
            let course='{{$course->id}}';
            setTimeout(function () {
                loadMore(course,skip);
                $('#spinner').removeClass('icon-spin5  animate-spin');
            }, 500);


        });
        function loadMore(courseID,skip)   {
            $.ajax({
                url:'{{route('more.rate.course')}}',
                method:'GET',
                data:{
                    course_id:courseID,
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
