<ul id="top_menu">

    @auth('web')


        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <li>
            <a class="login" href="{{ route('logout') }}"  id="sign-in"
               title="Log Out"  onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>
        </li>
    @endauth
    @guest
            <li><a href="{{route('register')}}" class="btn_top company">Register</a></li>
            <li><a href="{{route('login')}}" class="btn_top ">Login</a></li>
    @endguest

</ul>
<!-- /top_menu -->
<a href="#menu" class="btn_mobile">
    <div class="hamburger hamburger--spin" id="hamburger">
        <div class="hamburger-box">
            <div class="hamburger-inner"></div>
        </div>
    </div>
</a>
<nav id="menu" class="main-menu">
    <ul>
        <li><span><a href="{{route('index')}}">Home</a></span></li>
        <li><span><a href="{{route('courses')}}">Courses</a></span></li>
        <li><span><a href="#0">Universities</a></span>
            <ul>
                @foreach($universities as $university)
                    <li>
                    <span><a href="{{route('university',$university->slug)}}">{{$university->name}}</a></span>
                    <ul>
                        @foreach($university->departments as $department)
                            <li><a href="{{route('university.department',$department->slug)}}">{{$department->name}}</a></li>
                            @endforeach
                    </ul>
                </li>
                    @endforeach
            </ul>
        </li>
        <li><span><a href="#0">Articles Categories</a></span>
            <ul>
              @foreach($categories as $category)
                    <li><a href="{{route('blog.category',$category->name)}}">{{$category->name}}</a></li>
                @endforeach
            </ul>
        </li>
        <li><span><a href="{{route('blog')}}">Blog</a></span></li>
        <li><span><a href="{{route('projects')}}">Projects</a></span></li>
        <li><span><a href="{{route('services')}}">Services</a></span></li>
        <li><span><a href="{{route('about')}}">About</a></span></li>
        <li><span><a href="{{route('contact')}}">Contact</a></span></li>
        @auth('web')
        <li><span><a href="{{route('user.profile')}}">
                   <i class="icon-user"></i> {{explode(' ',auth('web')->user()->name)[0]}}</a></span></li>
         @endauth
    </ul>
</nav>
