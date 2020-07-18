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
        .company_listing .company_info figure img{
            width: 100%;
        }
        .course_price{
            position: absolute;
            top: 77px;
            left: 1px;
            z-index: 11;
            height: 20px;
            text-align: center;
            font-size: 15px!important;
        }
    </style>
@endpush


@section('content')
    <main>
        <div id="results">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-lg-3 col-md-4 col-10">
{{--                        <h1><strong>{{$total}}</strong> result for "All Universities"</h1>--}}
                        <h1>Search In <strong>{{$total}}</strong> Of Total Courses</h1>

                    </div>
                    <div class="col-xl-5 col-md-6 col-2">
                        <a href="#0" class="search_mob btn_search_mobile"></a> <!-- /open search panel -->
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
                                        @foreach($departments as $department)
                                            <li>
                                                <label class="container_check">{{$department->name}} <small>{{count($department->courses)}}</small>
                                                    <input type="checkbox" class="filter-department" name="departments[]" value="{{$department->id}}">
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
                    @forelse($courses as $course)
                    <div class="row">
                        <div class="company_listing">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="company_info" style="width: 620px">
                                        <div class="info">

                                            @if($course->price==0)
                                                <small class="badge badge-primary course_price" style="font-size: 20px">    Free     </small>
                                            @else
                                                <small class="badge badge-warning course_price" style="font-size: 20px">     {{round($course->price,3).' '.DefaultCurrency()}}        </small>
                                            @endif

                                        </div>
                                        <figure><a href="{{route('course.details',$course->slug)}}">
                                                <img  class="img-responsive img-fluid" src="{{route('storage',['courses_files',$course->photo,$course->id,'photos'])}}" alt="{{$course->meta_title}}">
                                            </a></figure>
{{--                                        <h3>{{$course->name}}</h3>--}}
                                        <h3>   {{strlen($course->name)>32?substr($course->name,0,32):$course->name}}</h3>
                                        <p>{!! substr($course->description,0,description)  !!}</p>
                                        <small class="d-block"><i class=" icon-briefcase-3"></i> {{$course->department->university->name}} - {{$course->department->name}}</small>
                                        <small ><i class=" icon-calendar"></i> {{$course->created_at->format('d M.Y')}}</small>
                                        <small ><i class=" icon-user"></i> {{count($course->users)}}</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center float-lg-right">
                                        <span class="rating">
                                            <strong>Based on {{count($course->rates)}} reviews</strong>
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
                                        <a href="{{route('course.details',$course->slug)}}" class="btn_1 small">Read more</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /row -->
                    @empty
                        <h5 style="margin-left: 15px !important;">No Courses Founded ..</h5>
                    @endforelse

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

@endpush
