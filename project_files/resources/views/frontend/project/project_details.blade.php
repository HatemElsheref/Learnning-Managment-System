@extends('frontend.layouts.app')

@section('navbar')

    @include('frontend.layouts.navbarv2')

@endsection


@push('css_before')

@endpush
@push('css_after')
    <style>
        .hero_single.office{
            background: #051d4d url('{{frontend()}}img/pattern_2.svg') top center repeat !important;
        }
    </style>

@endpush


@section('content')

    <main>
        <section class="hero_single office">
            <div class="wrapper">
                <div class="container">
                    <h1> {{$project->name}}</h1>
                    <p>Vanno helps grow your business using customer reviews</p>
                </div>
            </div>
        </section>
        <!-- /hero_single -->


        <div class="bg_color_1">
            <div class="container margin_80">
                <div class="row d-flex align-items-center">
                    <div class="col-lg-6 pl-lg-5 order-lg-last">
                        <img alt="" src="{{route('storage',['projects_photos',$project->photo,$project->id,null])}}" class="img-fluid rounded">
                    </div>
                    <div class="col-lg-6 pt-4 order-lg-first">
                        <h2>GOAL!</h2>
                        <p class="lead">
                        {!! $project->description  !!}
                        </p>
                        <p>
                        <a href="@if(!empty($project->link)) {{$project->link}} @else # @endif">
                            <span class=" icon-unlink "></span> Project Demo
                        </a>
                        </p>
                    </div>
                </div>
            </div>
            <!-- /container -->
        </div>
        <!-- /bg_color_1 -->

        <div class="container margin_80_55">
            <div class="main_title_2">
                <h2>Other Photos</h2>
                <p>Cum doctus civibus efficiantur in imperdiet deterruisset.</p>
            </div>
            <div id="carousel" class="owl-carousel owl-theme">
               @foreach($project_photos as $photo)
                    <div class="item">
                        <figure style="height: 200px">
                            <img style="height: 100%" src="{{route('storage',['projects_photos',$photo->path,$photo->parent_id,'photos'])}}" alt="">

                        </figure>
                    </div>
                @endforeach
            </div>
            <!-- /carousel -->
        </div>
        <!--/container-->

    </main>
    <!--/main-->

@endsection

@push('js')

@endpush
