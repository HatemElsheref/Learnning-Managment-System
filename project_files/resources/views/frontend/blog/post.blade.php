@extends('frontend.layouts.app')
@push('meta')
    <?php
    $keywords='';
    foreach ( (array)json_decode($post->meta_keywords) as $keyword){
        $keywords.=$keyword.',';
    }
    $keywords= trim($keywords,',');
    ?>
    @include('meta::manager', [
'title'         =>$post->meta_title,
'description'   => $post->meta_description,
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
                    <h1>{{app}} Blog</h1>
                    <p>Vanno helps grow your business using customer reviews</p>
                </div>
            </div>
        </section>
        <!-- /hero_single -->

        <div class="container margin_60_35">
            <div class="row">
                <div class="col-lg-9">
                    <div class="singlepost">
                        <figure><img alt="" class="img-fluid" style="max-width: unset;width: 100%" src="{{route('storage',['posts_photos',$post->photo,null,null])}}"></figure>
                        <h2 @if($post->dir=='rtl') class="arabic-dir" @endif>{{$post->title}}</h2>
                        <div class="postmeta">
                            <ul>
                                <li><a href="{{route('blog.category',$post->category->name)}}"><i class="ti-folder"></i> {{$post->category->name}}</a></li>
                                <li><a href="{{route('blog.date',$post->created_at->format('d-m-Y'))}}"><i class="ti-calendar"></i> {{$post->created_at->format('d/m/Y')}}</a></li>
                                <li class="text-primary"><i class="ti-user"></i> {{$post->instructor->name}}</li>
                                <li><i class="ti-comment"></i> (0) No Comments</li>
                            </ul>
                        </div>
                        <!-- /post meta -->
                        <div class="post-content">
                         <p>
                             {!! $post->content !!}
                         </p>
                        </div>
                        <!-- /post -->
                    </div>
                    <!-- /single-post -->


                </div>
                <!-- /col -->

                <aside class="col-lg-3">
                    <div class="widget search_blog">
                        <form action="{{route('blog.search')}}">
                            <div class="form-group">
                                <input type="text" name="q" id="search" class="form-control" placeholder="Search..">
                                <span><input type="submit" value="Search"></span>
                            </div>
                        </form>
                    </div>
                    <!-- /widget -->
                    <div class="widget">
                        <div class="widget-title">
                            <h4>Related Post</h4>
                        </div>
                        <ul class="comments-list">
                            @foreach($posts as $post)
                                <li>
                                    <div class="alignleft">
                                        <a href="{{route('blog.post',$post->slug)}}"><img src="{{route('storage',['posts_photos',$post->photo,null,null])}}" alt="{{$post->meta_title}}"></a>
                                    </div>
                                    <small>{{$post->category->name}} - {{$post->created_at->format('d.m.Y')}}</small>
                                    <h3><a href="{{route('blog.post',$post->slug)}}" title="{{$post->meta_title}}">{{$post->title}}...</a></h3>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- /widget -->
                    <div class="widget">
                        <div class="widget-title">
                            <h4>Categories</h4>
                        </div>
                        <ul class="cats">
                            @foreach($categories as $category)
                                <li><a href="{{route('blog.category',$category->name)}}">{{$category->name}} <span>({{count($category->posts)}})</span></a></li>
                            @endforeach

                        </ul>
                    </div>
                    <!-- /widget -->
                    <div class="widget">
                        <div class="widget-title">
                            <h4>Post Tags</h4>
                        </div>
                        <div class="tags">
                            @foreach($post->tags as $tag)
                                <a href="{{route('blog.tag',$tag->name)}}">{{$tag->name}}</a>
                            @endforeach
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
                <h2>More Posts</h2>
                <p>Cum doctus civibus efficiantur in imperdiet deterruisset.</p>
                <a href="{{route('blog')}}">View all</a>
            </div>

            <div id="reccomended" class="owl-carousel owl-theme">


              @foreach($random as $post)
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

    </main>
    <!--/main-->

@endsection

@push('js')

@endpush
