
      @php
          $orders=\Illuminate\Support\Facades\Cache::rememberForever('orders',function (){
       return   \Illuminate\Support\Facades\DB::table('course_user')->select(['course_user.*','users.name','courses.name as course'])
              ->where('status','=','closed')
              ->join('users','course_user.user_id','=','users.id')
              ->join('courses','course_user.course_id','=','courses.id')
              ->orderByDesc('course_user.created_at')->get();
      }) ;
          @endphp

<div class="page-wrapper">
    <!-- Header -->
    <header class="main-header " id="header">
        <nav class="navbar navbar-static-top navbar-expand-lg">
            <!-- Sidebar toggle button -->
            <button id="sidebar-toggler" class="sidebar-toggle">
                <span class="sr-only">Toggle navigation</span>
            </button>
            <!-- search form  as margin-->
            <div class="search-form d-none d-lg-inline-block"></div>

            <div class="navbar-right ">
                <ul class="nav navbar-nav">
                    <li class="dropdown user-menu">
                        <button href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                            <span class="d-none d-lg-inline-block">@lang('dashboard.language')</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                <li>
                                    <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                         @if ($localeCode=='ar')
                                              <span class="flag-icon flag-icon-eg h4" title="العربيه"></span>
                                              @else
                                               <span class="flag-icon flag-icon-us h4" title="English"></span>
                                         @endif
                                         <span>
                                          {{ $properties['native'] }}
                                       </span>

                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="dropdown notifications-menu">
                        <button class="dropdown-toggle" data-toggle="dropdown">
                            <i class="mdi mdi-bell-outline "></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li class="dropdown-header">@lang('dashboard.notification_first') {{count($orders)}} @lang('dashboard.notification_last')</li>
                           @foreach($orders as $order)
                                <li>
                                    <a href="{{route('orders.index')}}">
                                        <i class="mdi mdi-cart-plus"></i>
{{--                                        {{ucfirst($order->name).'  Ordered New Course '.$order->course}}--}}
                                        {{substr(ucfirst($order->name).'  Ordered New Course ',0,30).'..'}}
                                        <span class=" font-size-12 d-inline-block float-right"><i class="mdi mdi-clock-outline"></i> {{substr($order->created_at,0,10)}}</span>
                                    </a>
                                </li>
                               @endforeach
                            <li class="dropdown-footer">
                                <a class="text-center" href="{{route('orders.index')}}"> @lang('dashboard.view_all') </a>
                            </li>
                        </ul>
                    </li>
                    <li class="right-sidebar-in right-sidebar-2-menu">
                        <i class="mdi mdi-settings mdi-spin"></i>
                    </li>
                    <!-- User Account -->
                    <li class="dropdown user-menu">
                        <button href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                             <img src="{{route('UPLOADED.FILES',['admins_avatars',auth('webadmin')->user()->avatar,null,null])}}" class="user-image" alt="User Image" width="40px" height="40px" />
                            <span class="d-none d-lg-inline-block">{{auth('webadmin')->user()->name}}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <!-- User image -->
                            <li class="dropdown-header">
                                <img src="{{route('UPLOADED.FILES',['admins_avatars',auth('webadmin')->user()->avatar,null,null])}}" width="50px;" height="50px" />
                                <div class="d-inline-block">
                                    {{auth('webadmin')->user()->name}} <small class="pt-1">{{auth('webadmin')->user()->email}}</small>
                                </div>
                            </li>

                            <li>
                                <a href="{{route('admin.show.profile',auth('webadmin')->user()->id)}}">
                                    <i class="mdi mdi-account"></i> @lang('dashboard.profile')
                                </a>
                            </li>
                            <li>
                                <a href="/"> <i class="mdi mdi-web"></i> WebSite </a>
                            </li>
{{--                            <li class="right-sidebar-in">--}}
{{--                                <a href="javascript:0"> <i class="mdi mdi-settings"></i> @lang('dashboard.setting') </a>--}}
{{--                            </li>--}}
                            <form method="post" action="{{route('DoLogout')}}" id="logout-form">@csrf</form>
                            <li class="dropdown-footer">
                                <a href=""# onclick="document.getElementById('logout-form').submit();return false;"> <i class="mdi mdi-logout"></i> @lang('dashboard.logout') </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>


    </header>

