@extends('frontend.layouts.app')
@push('meta')
    <?php
    $keywords='';
    foreach ( (array)json_decode($article->meta_keywords) as $keyword){
        $keywords.=$keyword.',';
    }
    $keywords= trim($keywords,',');
    ?>
    @include('meta::manager', [
'title'         =>$article->meta_title,
'description'   => $article->meta_description,
'keywords'   =>  $keywords  ])
@endpush
@section('navbar')

    @include('frontend.layouts.navbarv2')

@endsection

@push('css_before')
    <!-- SPECIFIC CSS -->
    <link href="{{frontend()}}css/blog.css" rel="stylesheet">
@endpush
@push('css_after')
         <style>
             .arabic-dir{
                 text-align: right;
             }
         </style>
@endpush


@section('content')
    <main>
        <section class="hero_single general">
            <div class="wrapper">
                <div class="container">
                    <h1>{{app}} Article</h1>
                    <p>Vanno helps grow your business using customer reviews</p>
                </div>
            </div>
        </section>
        <!-- /hero_single -->

        <div class="container margin_60_35">
            <div class="row">
                <div class="col-lg-9">
                    <div class="singlepost">
                        <figure><img alt="{{$article->title}}" class="img-fluid" style="max-width: unset;width: 100%" src="{{route('storage',['courses_files',$article->photo,$article->course_id,'photos'])}}"></figure>
                        <h2 @if($article->dir=='rtl') class="arabic-dir" @endif>{{$article->title}}</h2>
                        <div class="postmeta">
                            <ul>
                                <li><a href="{{route('articles',$article->course->slug)}}"><i class="ti-folder"></i> {{$article->course->name}}</a></li>
                                <li><a href="{{route('article.date',$article->created_at->format('d-m-Y'))}}"><i class="ti-calendar"></i> {{$article->created_at->format('d/m/Y')}}</a></li>
                                <li class="text-primary"><i class="ti-user"></i> {{$article->instructor->name}}</li>
                                <li><i class="ti-comment"></i> (0) No Comments</li>
                            </ul>
                        </div>
                        <!-- /post meta -->
                        <div class="post-content">
                         <p>
                             {!! $article->content !!}
                         </p>
                        </div>
                        <!-- /post -->
                    </div>
                    <!-- /single-post -->


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
                            <h4>Related Articles</h4>
                        </div>
                        <ul class="comments-list">
                            @foreach($articles as $art)
                                <li>
                                    <div class="alignleft">
                                        <a href="{{route('article',$art->slug)}}"><img src="{{route('storage',['courses_files',$art->photo,$art->course_id,'photos'])}}" alt="{{$art->meta_title}}"></a>
                                    </div>
                                    <small>{{$art->course->name}} - {{$art->created_at->format('d.m.Y')}}</small>
                                    <h3><a href="{{route('article',$art->slug)}}" title="{{$art->meta_title}}">{{$art->title}}...</a></h3>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- /widget -->

                    <div class="widget">
                        <div class="widget-title">
                            <h4>Article Tags</h4>
                        </div>
                        <div class="tags">
                            @if($article->tags!=null)
                                @foreach((array) json_decode($article->tags) as $t)
                                    <a href="#">{{$t}}</a>
                                @endforeach
                            @endif

                        </div>
                    </div>
                    <!-- /widget -->
                </aside>
                <!-- /aside -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->

        <div class="container margin_60">
            <div class="main_title_3">
                <h2>More Articles </h2>
                <p>More Related Articles To {{$article->course->department->name}} Department</p>
                <a href="{{route('articles.department',$article->course->department->slug)}}">View all</a>
            </div>

            <div id="reccomended" class="owl-carousel owl-theme">

                @foreach($random as $item)
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
                                <p @if(strlen(substr($article->subtitle,0,description))<=40) style="height: 45px;" @endif>
                                    {{substr($item->subtitle,0,description)}}.</p>
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
            <!-- /carousel -->
        </div>

    </main>
    <!--/main-->

@endsection

@push('js')

@endpush
