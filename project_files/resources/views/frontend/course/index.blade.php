@extends('frontend.layouts.app')
@section('navbar')

    @include('frontend.layouts.navbarv3')

@endsection


@push('css_before')
    <link href="{{frontend()}}css/blog.css" rel="stylesheet">
@endpush
@push('css_after')
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
        .custom-search-input-2{
            background: unset;
        }
    </style>
@endpush


@section('content')
    <main>
        <div id="results">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-lg-3 col-md-4 col-10">
                        <h1>Search In <strong>{{$total}}</strong> Of Total Courses</h1>
                    </div>
                    <div class="col-xl-5 col-md-6 col-2">
                        <a href="#0" class="search_mob btn_search_mobile"></a> <!-- /Read more search panel -->
                        <div class="row no-gutters custom-search-input-2 inner">

                            <div class="col-lg-10 ">
                                <form action="{{route('course.search')}}" id="course_search_form">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="q" placeholder="Search reviews for a company">
                                    <i class="icon_search"></i>
                                </div>
                                </form>
                            </div>
                                <div class="col-xl-2 col-lg-2">
                                    <input type="submit" value="Search" onclick="document.getElementById('course_search_form').submit()">
                                </div>


                        </div>
                    </div>
                </div>
                <!-- /row -->
                <div class="search_mob_wp">
                    <div class="custom-search-input-2">
                       <form action="{{route('course.search')}}">
                        <div class="form-group">
                            <input class="form-control" name="q" value="{{old('q')}}" type="text" placeholder="Search Course...">
                            <i class="icon_search"></i>
                        </div>
                        <input type="submit" value="Search">
                       </form>
                    </div>
                </div>
                <!-- /search_mobile -->
            </div>
            <!-- /container -->
        </div>

        <div class="container margin_60_35">
            <div class="row">
                <aside class="col-lg-3" id="sidebar">
                    <div id="filters_col">
                        <a data-toggle="collapse" href="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters" id="filters_col_bt">Filters </a>
                        <div class="collapse show" id="collapseFilters">
                            <form method="post" action="{{route('course.filter')}}">
                                @csrf
                            <div class="filter_type">
                                <h6>University</h6>
                                <ul>
                                    @foreach($universities as $university)
                                    <li>
                                        @php $total=0; @endphp
                                        @foreach ($university->departments as $department)
                                            @php $total+= count($department->courses); @endphp
                                            @endforeach
                                        <label class="container_check">{{$university->name}} <small>{{$total}}</small>
                                            <input type="checkbox" class="filter-university"  name="universities[]" value="{{$university->id}}">
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                    @endforeach

                                </ul>
                            </div>
                            <div class="filter_type">
                                <h6>Departments</h6>
                                <ul>

                                    @foreach($departments as $department1)
                                        <li>
                                            <label class="container_check">{{$department1->name}} <small>{{count($department1->courses)}}</small>
                                                <input type="checkbox" class="filter-department" name="departments[]" value="{{$department1->id}}">
                                                <span class="checkmark"></span>
                                            </label>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                                <button type="submit" class="btn_1 small" id="load-more">Filter</button>
                            </form >
                        </div>
                        <!--/collapse -->
                    </div>
                    <!--/filters col-->
                </aside>
                <!-- /aside -->

                <div class="col-lg-9">
                    <div class="row">

                        @forelse($courses as $course)

                            <div class="col-md-6 col-lg-6 col-xl-6  wow bounceInLeft" style="visibility: visible;">
                                <article class="blog">
                                    <figure class="course_figure" style="height: 180px;">
                                        <a href="{{route('course.details',$course->slug)}}">
                                            <img class="img-responsive img-fluid" src="{{route('storage',['courses_files',$course->photo,$course->id,'photos'])}}" alt="{{$course->meta_title}}" title="{{$course->name}}">
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
                                        <h2 class="d-block course-name"><a href="{{route('course.details',$course->slug)}}" title="{{$course->name}}">

                                                {{strlen($course->name)>30?substr($course->name,0,30):$course->name}}
                                            </a></h2>
{{--                                        {{dd(strlen(substr($course->description,0,description)))}}--}}
                                        <p  @if(strlen($course->description) <=48) style="height: 39px!important;" @else style="margin-top: 30px" @endif>{!! substr($course->description,0,description)  !!}</p>

                                        <small class="d-block" style="margin-top:5px!important;">
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
                        @empty
                            <h5 style="margin-left: 15px !important;">No Courses Founded ..</h5>
                        @endforelse







                    </div>
                    <!-- /row -->

                    <div class="pagination__wrapper add_bottom_30">
                       {!! $courses->render() !!}
                    </div>
                    <!-- /pagination -->

                </div>
            </div>
        </div>
        <!-- /container -->



    </main>
    <!--/main-->

@endsection

@push('js')
    <!-- Masonry Filtering -->
    <script src="{{frontend()}}js/isotope.min.js"></script>
    <script>
        $(window).on('load',function(){
            var $container = $('.isotope-wrapper');
            $container.isotope({ itemSelector: '.isotope-item', layoutMode: 'masonry' });
        });

        $('.date_filter').on( 'click', 'input', 'change', function(){
            var selector = $(this).attr('data-filter');
            $('.isotope-wrapper').isotope({ filter: selector });
        });
    </script>
    <script>

       /*
        let Uids = [];
        let Dids = [];
        $('.filter-university').on( 'change',  function(){
                  let universities=document.getElementsByClassName('filter-university');
                  Uids=[];
                  for (let i=0;i<universities.length;i++){
                      if (universities.item(i).checked){
                          Uids.push(universities.item(i).value);
                      }
                  }
        });
        $('.filter-department').on( 'change',  function(){
            let departments=document.getElementsByClassName('filter-department');
            Dids=[];
            for (let i=0;i<departments.length;i++){
                if (departments.item(i).checked){
                    Dids.push(departments.item(i).value);
                }
            }

        });
           */
    </script>

@endpush
