@extends('frontend.layouts.app')
@section('navbar')

    @include('frontend.layouts.navbarv2')

@endsection
@push('css_after')
    <style>
        .hero_single.version_company{
            background: #051d4d url('{{frontend()}}img/pattern_2.svg') top center repeat !important;
        }
    </style>
@endpush
@section('content')

<main>
    <section class="hero_single version_company">
        <div class="wrapper">
            <div class="container">
                <h3>The Power<br>of your most passionate customers</h3>
                <p>Vanno helps grow your business using customer reviews!</p>
            </div>
        </div>
    </section>
    <!-- /hero_single -->

    <div class="bg_color_1">
        <div class="container margin_60_35">
            <div class="row">
                <div class="col-lg-4">
                    <div class="box_feat">
                        <i class="pe-7s-speaker"></i>
                        <h3><strong>30</strong> thousand<em>reviews seen every month</em></h3>
                        <p>Over 30 thousand review impressions every month</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="box_feat">
                        <i class="pe-7s-flag"></i>
                        <h3><strong>5</strong> thousand<em>real reviews per month</em></h3>
                        <p>Over 5 thousand reviews posted every month</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="box_feat">
                        <i class="pe-7s-rocket"></i>
                        <h3><strong>1</strong> thousand<em>have a great return</em></h3>
                        <p>Over 1 thousand companies increase their business</p>
                    </div>
                </div>
            </div>
            <!-- /row -->

        </div>
        <!-- /container -->
    </div>
    <!-- /bg_color_1 -->

    <div class="feat_blocks">
        <div class="container-fluid h-100">
            <div class="row h-100 justify-content-center align-items-center">
                <div class="col-md-6 p-0">
                    <div class="block_1"><img src="{{frontend()}}img/company_info_graphic_1.svg" alt="" class="img-fluid"></div>
                </div>
                <div class="col-md-6 p-0">
                    <div class="block_2">
                        <h3>Increase conversions with the power of your customers</h3>
                        <p>Mucius doctus constituto pri at, ne cetero postulant pro. At vix utinam corpora, ea oblique moderatius usu. Vix id viris consul honestatis, an constituto deterruisset consectetuer pro.</p>
                        <a href="pricing.html" class="btn_1">View Pricing Plans</a>
                    </div>
                </div>

            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /feat_blocks -->

    <div class="bg_color_1">
        <div class="container margin_60_35">
            <div class="main_title_2">
                <h2>Features</h2>
                <p>Cum doctus civibus efficiantur in imperdiet deterruisset.</p>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="box_feat_2">
                        <h3><i class="pe-7s-graph3"></i>Analytics Tools</h3>
                        <p><strong>Mucius doctus constituto pri at.</strong> At vix utinam corpora, ea oblique moderatius usu. Vix id viris consul honestatis, an constituto deterruisset consectetuer pro quo corrumpit euripidis.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box_feat_2">
                        <h3><i class="pe-7s-share"></i>Social Integration</h3>
                        <p><strong>Mucius doctus constituto pri at.</strong> At vix utinam corpora, ea oblique moderatius usu. Vix id viris consul honestatis, an constituto deterruisset consectetuer pro quo corrumpit euripidis.</p>
                    </div>
                </div>
            </div>
            <!-- /row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="box_feat_2">
                        <h3><i class="pe-7s-target"></i>Targeted Consumers</h3>
                        <p><strong>Mucius doctus constituto pri at.</strong> At vix utinam corpora, ea oblique moderatius usu. Vix id viris consul honestatis, an constituto deterruisset consectetuer pro quo corrumpit euripidis.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box_feat_2">
                        <h3><i class="pe-7s-help2"></i>Awesome Support</h3>
                        <p><strong>Mucius doctus constituto pri at.</strong> At vix utinam corpora, ea oblique moderatius usu. Vix id viris consul honestatis, an constituto deterruisset consectetuer pro quo corrumpit euripidis.</p>
                    </div>
                </div>
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /bg_color_1 -->

    <div class="call_section_2">
        <div class="wrapper">
            <div class="container">
                <h3>Get started now with Vanno...improve your business.</h3>
                <a class="btn_1 medium" href="pricing.html">Join Vanno Now!</a>
            </div>
        </div>
    </div>
    <!-- /call_section_2 -->

</main>
<!-- /main -->
@endsection
@push('js')
    <script src="{{frontend()}}js/tabs.js"></script>
    <script>new CBPFWTabs( document.getElementById( 'tabs' ) );</script>
@endpush
