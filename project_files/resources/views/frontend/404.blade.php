       @extends('frontend.layouts.app')

       @section('navbar')

           @endsection
       @section('content')
           <main>
               <section class="hero_single general">
                   <div class="wrapper">
                       <div class="container">

                               <img src="{{frontend()}}img/404.svg" alt="" class="img-fluid">
                               <div class="row mt-5">
                                   <div class="col-sm-3"></div>
                                   <div class="col-sm-6">
                                       <a href="/" class="btn btn-lg btn-primary form-control">
                                           Go Home
                                       </a>
                                   </div>

                               </div>


                       </div>
                   </div>
               </section>
               <!-- /hero_single -->
           </main>
       @Endsection
<div id="page" class="theia-exception">

    <header class="header menu_fixed">
        @include('frontend.layouts.logo')

        @include('frontend.layouts.links')
    </header>
    <!-- /header -->

