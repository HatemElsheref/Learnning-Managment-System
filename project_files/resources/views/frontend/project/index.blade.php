@extends('frontend.layouts.app')

@section('navbar')

    @include('frontend.layouts.navbarv2')

@endsection


@push('css_before')

@endpush
@push('css_after')

@endpush


@section('content')
    <main>

        <section class="hero_single general">
            <div class="wrapper">
                <div class="container">
                    <h1>{{app}} Projects</h1>
                    <p>Vanno helps grow your business using customer reviews</p>
                </div>
            </div>
        </section>
        <!-- /hero_single -->
        <div class="container margin_60_35">
            <div class="main_title_3">
                <h2>Our Projects</h2>
                <p>Cum doctus civibus efficiantur in imperdiet deterruisset.</p>

            </div>
            <div class="row justify-content-center">
                @foreach($projects as $project)
                    <div class="col-lg-4 col-sm-6">
                        <a href="{{route('project',$project->id)}}" class="grid_item">
                            <figure >
                                <img class="img-responsive img-fluid"  src="{{route('storage',['projects_photos',$project->photo,$project->id,null])}}" alt="">
{{--                                <img class="img-responsive img-fluid"  src="{{frontend()}}images/slide_2.jpg" alt="">--}}
                                <div class="info">

                                    <em>   <small>{{$project->type}}</small></em>
                                    <h3>{{$project->name}}</h3>
                                </div>
                            </figure>
                        </a>
                    </div>
                    <!-- /grid_item -->
                    @endforeach

            </div>
            <!-- /row -->
            <div class="pagination__wrapper add_bottom_30">

                {!! $projects->render() !!}
            </div>
        </div>
        <!-- /container -->



    </main>
    <!-- /main -->

@endsection

@push('js')
    <script>
        $(document).ready(function () {
            let window_height = $(window).height();
            $(".hero_single.version_1").css({
                height:window_height+'px'
            });

        });
    </script>
@endpush
