@extends('frontend.layouts.app')

@section('navbar')

    @include('frontend.layouts.navbarv2')

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
        .arabic-dir{
            text-align: right;
        }
        .box_feat_2{
            background: #051d4d url('{{frontend()}}img/pattern_2.svg') top center repeat !important;
            color: #fff;
        }

    </style>
@endpush


@section('content')

    <main>
        <section class="hero_single general">
            <div class="wrapper">
                <div class="container">
                    <h1>{{app}} Articles</h1>
                    <p>Vanno helps grow your business using customer reviews</p>
                </div>
            </div>
        </section>
        <!-- /hero_single -->

        {{--        <div class="container margin_60_35" style="margin-left: 5px;margin-right: 5px">--}}
        <div class="container margin_60_35" >
            <div class="row">
                <div class="col-lg-9">
                    <div class="row">
                        @forelse($articles as $article)
                            <div class="col-md-5">
                                <article class="blog">
                                    <figure style="height: 200px">
                                        <a href="{{route('article',$article->slug)}}"><img src="{{route('storage',['courses_files',$article->photo,$article->course_id,'photos'])}}" alt="{{$article->meta_title}}">
                                            <div class="preview"><span>Read more</span></div>
                                        </a>
                                    </figure>
                                    <div class="post_info">
                                        <small>{{$article->course->name}} - {{$article->created_at->format('d.m.Y')}}</small>
                                        <h2 @if($article->dir=='rtl') class="arabic-dir" @endif><a href="{{route('article',$article->slug)}}">{{$article->title}}</a></h2>
                                      <p @if(strlen(substr($article->subtitle,0,description))<=40) style="height: 45px;" @endif>
                                          {{substr($article->subtitle,0,description)}}
                                      </p>
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
                            <!-- /col -->
                        @empty
                            <div class="container margin_60_35">
                                <div class="row">
                                    <div class="col-lg-8">

                                        <h4 style="font-family: inherit; color: #555">

                                            No Posts Founded</h4>

                                    </div>
                                    <!-- /col -->

                                </div>
                                <!-- /row -->
                            </div>
                        @endforelse

                    </div>
                    <!-- /row -->


                    <div class="pagination__wrapper add_bottom_30">

                        {!! $articles->render() !!}
                    </div>
                </div>
                <!-- /col -->

                <aside class="col-lg-3">
                    <div class="widget search_blog">
                        <form action="{{route('article.search')}}">
                            <div class="form-group">
                                <input type="text" name="q" id="search" class="form-control" placeholder="Search..">
                                <span><input type="submit" value="Search"></span>
                            </div>
                        </form>
                    </div>
                    <!-- /widget -->
                    <div class="widget">
                        <div class="widget-title">
                            <h4>Latest Articles</h4>
                        </div>
                        <ul class="comments-list">
                            @foreach($recent_course_articles as $article)
                                <li>
                                    <div class="alignleft">
                                        <a href="{{route('article',$article->slug)}}"><img src="{{route('storage',['courses_files',$article->photo,$article->course_id,'photos'])}}" alt="{{$article->meta_title}}"></a>
                                    </div>
                                    <small>{{$article->course->name}} - {{$article->created_at->format('d.m.Y')}}</small>
                                    <h3><a href="{{route('article',$article->slug)}}" title="{{$article->meta_title}}">{{$article->title}}...</a></h3>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- /widget -->
                </aside>
                <!-- /aside -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </main>

@endsection

@push('js')

@endpush
