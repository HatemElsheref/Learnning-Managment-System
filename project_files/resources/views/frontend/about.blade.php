@extends('frontend.layouts.app')
@section('navbar')

    @include('frontend.layouts.navbarv2')

@endsection
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
                    <h1>About Vanno</h1>
                    <p>Vanno helps grow your business using customer reviews</p>
                </div>
            </div>
        </section>
        <!-- /hero_single -->

        <div class="container margin_80">
            <div class="row d-flex align-items-center">
                <div class="col-lg-6">
                    <img alt="" src="{{frontend()}}img/office_2.jpg" class="img-fluid rounded">
                </div>
                <div class="col-lg-6 pl-lg-5 pt-4">
                    <h2>Passion Drive Us</h2>
                    <p class="lead">Dolor detraxit duo in, ei sea dicit reformidans. Mel te accumsan patrioque referrentur. Has causae perfecto ut, ex choro assueverit eum. Qui omnium cetero expetenda no, detracto vivendum corrumpit cu duo.</p>
                    <p class="lead">Sed ne prompta insolens mediocrem.</p>
                    <p class="lead"><em>Mark Twain CEO</em></p>
                </div>
            </div>
        </div>
        <!-- /container -->

        <div class="bg_color_1">
            <div class="container margin_80">
                <div class="row d-flex align-items-center">
                    <div class="col-lg-6 pl-lg-5 order-lg-last">
                        <img alt="" src="{{frontend()}}img/office_3.jpg" class="img-fluid rounded">
                    </div>
                    <div class="col-lg-6 pt-4 order-lg-first">
                        <h2>Succes is our GOAL!</h2>
                        <p class="lead">Vis at partem hendrerit, his te facete tacimates concludaturque, duo ex fabulas menandri. Idque saperet assentior mea an. Nisl copiosae reformidans duo ea, no doming elaboraret sed.</p>
                        <p class="lead">Quod exerci torquatos id sit, ne vix officiis consetetur. Te viris corpora voluptaria mea, hendrerit prodesset no cum.</p>
                    </div>
                </div>
            </div>
            <!-- /container -->
        </div>
        <!-- /bg_color_1 -->

        <div class="values">
            <div class="wrapper">
                <div class="container">
                    <div class="main_title_2">
                        <h2>Our Values</h2>
                        <p>Cum doctus civibus efficiantur in imperdiet deterruisset.</p>
                    </div>
                    <div class="row justify-content-center" style="min-height: 210px">
                        <div class="col-lg-5">
                            <div class="nav flex-column" role="tablist" aria-orientation="vertical">
                                <a class="nav-link active" id="tab-1" data-toggle="tab" href="#tab-1-content" role="tab" aria-controls="tab-1-content" aria-selected="true">Helps consumers and companies</a>
                                <a class="nav-link" id="tab-2" data-toggle="tab" href="#tab-2-content" role="tab" aria-controls="tab-2-content" aria-selected="false">Shoppers and retailers benefits</a>
                                <a class="nav-link" id="tab-3" data-toggle="tab" href="#tab-3-content" role="tab" aria-controls="tab-3-content" aria-selected="false">Making e-commerce so divers</a>
                                <a class="nav-link" id="tab-3" data-toggle="tab" href="#tab-4-content" role="tab" aria-controls="tab-4-content" aria-selected="false">Assess their service daily</a>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="tab-1-content" role="tabpanel" aria-labelledby="tab-1">
                                    <p class="lead">Vis at partem hendrerit, his te facete tacimates concludaturque, duo ex fabulas menandri. Idque saperet assentior mea an. Nisl copiosae reformidans duo ea, no doming elaboraret sed. Malorum cotidieque an cum.</p>
                                </div>
                                <div class="tab-pane fade" id="tab-2-content" role="tabpanel" aria-labelledby="tab-2">
                                    <p class="lead">Sed ne prompta insolens mediocrem, omnium fierent sed an, quod vivendo mel in. Argumentum honestatis ad mel, cu vis quot utroque. Nemore percipit no has. Mollis tincidunt his ex, dolore inimicus no cum.</p>
                                </div>
                                <div class="tab-pane fade" id="tab-3-content" role="tabpanel" aria-labelledby="tab-3">
                                    <p class="lead">Quod exerci torquatos id sit, ne vix officiis consetetur. Te viris corpora voluptaria mea, hendrerit prodesset no cum. Has tota semper alterum ne, qui te suas sensibus. Duo persius sensibus ne, choro soluta adolescens vim te, sale scripta ex his.</p>
                                </div>
                                <div class="tab-pane fade" id="tab-4-content" role="tabpanel" aria-labelledby="tab-4">
                                    <p class="lead">Sumo periculis inciderint ius ex. Sit te fierent probatus delicata, id mel nonumy consul oporteat. Agam tractatos te eam, iisque vulputate moderatius cu sit. Oratio complectitur contentiones nam ut, at legere maiorum fierent duo. Mel ea vero aliquid.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /values -->

        <div class="container margin_80_55">
            <div class="main_title_2">
                <h2>Our Instructors</h2>
                <p>Cum doctus civibus efficiantur in imperdiet deterruisset.</p>
            </div>
            <div id="carousel" class="owl-carousel owl-theme">
                @foreach($instructors as $instructor)
                    <div class="item">
                        <a href="#0">
                            <div class="title">
                                <h4>{{ucfirst($instructor->name)}}<em>{{strtoupper($instructor->title)}}</em></h4>
                            </div><img src="{{route('storage',['instructors_avatars',$instructor->photo,null,null])}}" style="height: 300px">
                        </a>
                    </div>
                    @endforeach
            </div>
            <!-- /carousel -->
        </div>
        <!--/container-->

    </main>
    <!--/main-->

@endsection
