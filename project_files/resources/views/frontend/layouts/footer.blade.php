
<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <a data-toggle="collapse" data-target="#collapse_ft_1" aria-expanded="false" aria-controls="collapse_ft_1" class="collapse_bt_mobile">
                    <h3>Quick Links</h3>
                    <div class="circle-plus closed">
                        <div class="horizontal"></div>
                        <div class="vertical"></div>
                    </div>
                </a>
                <div class="collapse show" id="collapse_ft_1">
                    <ul class="links">
                        <li><a href="{{route('index')}}">Home</a></li>
                        <li><a href="{{route('courses')}}">Courses</a></li>
                        <li><a href="{{route('projects')}}">Projects</a></li>
                        <li><a href="{{route('services')}}">Services</a></li>
                        <li><a href="{{route('about')}}">About us</a></li>
                        <li><a href="{{route('contact')}}">Contacts</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <a data-toggle="collapse" data-target="#collapse_ft_2" aria-expanded="false" aria-controls="collapse_ft_2" class="collapse_bt_mobile">
                    <h3>Categories</h3>
                    <div class="circle-plus closed">
                        <div class="horizontal"></div>
                        <div class="vertical"></div>
                    </div>
                </a>
                <div class="collapse show" id="collapse_ft_2">
                    <ul class="links">
                        @php $count=0; @endphp
                        @foreach($categories as $category)
                            @php $count++; @endphp
                            <li><a href="{{route('blog.category',$category->name)}}">{{ucfirst($category->name)}}</a></li>
                            @if($count==6)
                                @break
                            @endif
                            @endforeach
                        <li><a href="{{route('blog')}}">View all</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <a data-toggle="collapse" data-target="#collapse_ft_3" aria-expanded="false" aria-controls="collapse_ft_3" class="collapse_bt_mobile">
                    <h3>Contacts</h3>
                    <div class="circle-plus closed">
                        <div class="horizontal"></div>
                        <div class="vertical"></div>
                    </div>
                </a>
                <div class="collapse show" id="collapse_ft_3">
                    <ul class="contacts">
                        <li><i class="ti-home"></i>97845 Baker st. 567<br>Los Angeles - US</li>
                        <li><i class="ti-headphone-alt"></i>+61 23 8093 3400</li>
                        <li><i class="ti-email"></i><a href="#0">info@domain.com</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <a data-toggle="collapse" data-target="#collapse_ft_4" aria-expanded="false" aria-controls="collapse_ft_4" class="collapse_bt_mobile">
                    <div class="circle-plus closed">
                        <div class="horizontal"></div>
                        <div class="vertical"></div>
                    </div>
                    <h3>Follow Us</h3>
                </a>
                <div class="collapse show" id="collapse_ft_4">

                    <div class="follow_us">

                        <ul>
                            <li><a href="#0"><i class="ti-facebook"></i></a></li>
                            <li><a href="#0"><i class="ti-twitter-alt"></i></a></li>
                            <li><a href="#0"><i class="ti-google"></i></a></li>
                            <li><a href="#0"><i class="ti-instagram"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- /row-->
        <hr>
{{--                      <p>Powered By <i class="icon-heart text-danger"></i><a href="https://www.facebook.com/hatem.elsheref.73" targrt="_blank">Hatem Mohamed Elsheref</a></p>--}}
                      <p>Developed By <i class="icon-heart text-danger"></i><a href="https://www.facebook.com/hatem.elsheref.73" target="_blank">Hatem Mohamed Elsheref</a></p>
    </div>
</footer>
<!--/footer-->
</div>
<!-- page -->


<div id="toTop"></div><!-- Back to top button -->

<!-- COMMON SCRIPTS -->
<script src="{{frontend()}}js/common_scripts.js"></script>
<script src="{{frontend()}}js/functions.js"></script>
<script src="{{frontend()}}assets/validate.js"></script>
<script src="{{frontend()}}js/wow.js"></script>
      <script>
          wow = new WOW(
              {
                  boxClass:     'wow',      // default
                  animateClass: 'animated', // default
                  offset:       0,          // default
                  mobile:       true,       // default
                  live:         true        // default
              }
          );
          wow.init();
      </script>

@stack('js')

</body>
</html>
