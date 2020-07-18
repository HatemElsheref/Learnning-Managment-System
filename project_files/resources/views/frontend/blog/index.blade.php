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
        /*@media (min-width: 1200px)  {*/
        /*    .container {*/
        /*     max-width: 1500px;*/
        /*    }*/
        /*}*/

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

{{--        <div class="container margin_60_35" style="margin-left: 5px;margin-right: 5px">--}}
        <div class="container margin_60_35" >
            <div class="row">
                <div class="col-lg-9">
                    <div class="row">
                        @forelse($posts as $post)

                            <div class="col-md-5">
                                <article class="blog">
                                    <figure style="height: 200px">
                                        <a href="{{route('blog.post',$post->slug)}}"><img src="{{route('storage',['posts_photos',$post->photo,null,null])}}" alt="{{$post->meta_title}}">
                                            <div class="preview"><span>Read more</span></div>
                                        </a>
                                    </figure>
                                    <div class="post_info">
                                        <small>{{$post->category->name}} - {{$post->created_at->format('d.m.Y')}}</small>
                                        <h2 @if($post->dir=='rtl') class="arabic-dir" @endif>
                                            <a href="{{route('blog.post',$post->slug)}}">{{strlen($post->title)>20?substr($post->title,0,20):$post->title}}</a>
                                        </h2>
                                        <p @if($post->dir=='rtl') class="arabic-dir" @endif @if(strlen(substr($post->description,0,description))<=40) style="height: 45px;" @endif>
                                            @if($post->dir=='rtl')
                                                {{substr($post->description,0,description)}}     {{--201--}}
                                                @else
                                                {{substr($post->description,0,description)}}
                                            @endif

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

                        {!! $posts->render() !!}
                    </div>
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
                            <h4>Latest Post</h4>
                        </div>
                        <ul class="comments-list">
                            @foreach($recent_posts as $post)
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
                            <h4>Popular Tags</h4>
                        </div>
                        <div class="tags">
                            @foreach($tags as $tag)
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
    </main>

@endsection

@push('js')

@endpush
