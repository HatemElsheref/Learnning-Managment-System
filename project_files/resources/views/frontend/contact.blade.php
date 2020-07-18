@extends('frontend.layouts.app')
@section('navbar')

    @include('frontend.layouts.navbarv2')

@endsection
@push('css_after')
    <style>
        .hero_single.general{
            background: #051d4d url('{{frontend()}}img/pattern_2.svg') top center repeat !important;
        }
    </style>
@endpush
@section('content')
    <main>
        <section class="hero_single general">
            <div class="wrapper">
                <div class="container">
                    <h1>Get in Touch with Vanno</h1>
                    <p>Vanno helps grow your business using customer reviews</p>
                </div>
            </div>
        </section>
        <!-- /hero_single -->

            <div class="container margin_tabs">
                <div id="tabs" class="tabs">
                    <nav>
                        <ul>
                            <li><a href="#section-1"><i class="pe-7s-help1"></i>Questions<em>Omnis justo gloriatur et sit</em></a></li>

                            <li><a href="#section-2"><i class="pe-7s-help2"></i>Support<em>Quo corrumpit euripidis</em></a></li>
                        </ul>
                    </nav>
                    <div class="content">
                        <section id="section-1">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <div id="message-contact"></div>
                                    <form method="post" action="assets/contact.php" id="contactform" autocomplete="off">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group required">
                                                    <input class="form-control" type="email" id="email_contact" name="email_contact" placeholder="Email">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /row -->
                                        <div class="form-group required">
                                            <textarea class="form-control" id="message_contact" name="message_contact" style="height:150px;" placeholder="Message"></textarea>
                                        </div>
                                        <div class="form-group add_top_30 text-center">
                                            <input type="submit" value="Submit" class="btn_1 rounded" id="submit-contact">
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /row -->
                        </section>
                        <!-- /section -->
                      <section id="section-1">
                          <div class="row justify-content-center">
                              <div class="col-lg-8">
                                  <div class="mb-4"></div>
                                  <div id="message-support"></div>
                                  <div class="row">
                                      <div class="col-md-4 text-center">
                                          <p class="mb-4">
                                              <span class="ti-home d-block h4" style="color: #3578fa;"></span>
                                              <span style="color: #3578fa;">Shebin-ElKom Menoufia Egypt</span>
                                          </p>
                                      </div>
                                      <div class="col-md-4 text-center">
                                          <p class="mb-4">
                                              <span class="ti-headphone-alt d-block h4" style="color: #3578fa;"></span>
                                              <a href="#" style="color: #3578fa;">+1 232 3235 324</a>
                                          </p>
                                      </div>
                                      <div class="col-md-4 text-center">
                                          <p class="mb-0">
                                              <span class="ti-email d-block h4" style="color: #3578fa;"></span>
                                              <a href="#" style="color: #3578fa;">support@mufix.org</a>
                                          </p>
                                      </div>
                                  </div>
                                  <!-- /row -->
                                  <div class="row">
                                      <div class="col-md-12 text-center">
                                          <div class="follow_us">
                                              <h5>Follow Us</h5>
                                              <ul>
                                                  <li><a href="#0"><i class="ti-facebook"></i></a></li>
                                                  <li><a href="#0"><i class="ti-twitter-alt"></i></a></li>
                                                  <li><a href="#0"><i class="ti-google"></i></a></li>
                                                  <li><a href="#0"><i class="ti-instagram"></i></a></li>
                                              </ul>
                                          </div>
                                      </div>
                                  </div>
                                  <!-- /row -->
                              </div>
                          </div>
                      </section>
                        <!-- /row -->
                        <!-- /section -->
                    </div>
                    <!-- /content -->
                </div>
                <!-- /tabs -->
            </div>
            <!-- /container -->

    </main>
    <!-- /main -->


@endsection
@push('js')
    <script src="{{frontend()}}js/tabs.js"></script>
    <script>new CBPFWTabs( document.getElementById( 'tabs' ) );</script>
@endpush
