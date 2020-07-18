

<!--
====================================
——— LEFT SIDEBAR WITH FOOTER
=====================================
-->
<aside class="left-sidebar bg-sidebar">


    <div id="sidebar" class="sidebar sidebar-with-footer sidebar-block-non" >
        <!-- Aplication Brand -->
        <div class="app-brand">
            <a href="{{route('dashboard')}}" title="Shefoo Dashboard">
                <svg
                    class="brand-icon"
                    xmlns="http://www.w3.org/2000/svg"
                    preserveAspectRatio="xMidYMid"
                    width="30"
                    height="33"
                    viewBox="0 0 30 33"
                >
                    <g fill="none" fill-rule="evenodd">
                        <path
                            class="logo-fill-blue"
                            fill="#7DBCFF"
                            d="M0 4v25l8 4V0zM22 4v25l8 4V0z"
                        />
                </svg>
                <span class="brand-name text-truncate">@lang('dashboard.site_name')</span>
            </a>
        </div>
        <!-- begin sidebar scrollbar -->
        <div class="sidebar-scrollbar">

            <!-- sidebar menu -->
            <ul class="nav sidebar-inner" id="sidebar-menu">
                <li class="active">
                    <a class="sidenav-item-link" href="{{route('dashboard')}}">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span class="nav-text active">@lang('dashboard.dashboard')</span>
                    </a>
                </li>
                <li >
                    <a class="sidenav-item-link"  href="{{route('admin.index')}}">
                        <i class="mdi mdi-shield-key"></i>
                        <span class="nav-text">@lang('dashboard.admins')</span>
                    </a>
                </li>
                <li >
                    <a class="sidenav-item-link"  href="{{route('university.index')}}">
                        <i class="mdi mdi-bank"></i>
                        <span class="nav-text">University</span>
                    </a>
                </li>
                <li >
                    <a class="sidenav-item-link"  href="{{route('department.index')}}">
                        <i class="mdi mdi-view-grid"></i>
                        <span class="nav-text">Departments</span>
                    </a>
                </li>
                <li>
                    <a class="sidenav-item-link" href="{{route('instructor.index')}}">
                        <i class="mdi mdi-account-network"></i>
                        <span class="nav-text">Instructors</span>
                    </a>
                </li>
                <li>
                    <a class="sidenav-item-link" href="{{route('course.index')}}">
                        <i class="mdi mdi-play-circle"></i>
                        <span class="nav-text">Courses</span>
                    </a>
                </li>
                <li  class="has-sub" >

                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#general-articles"
                       aria-expanded="false" aria-controls="app">
                        <i class="mdi mdi-pencil-box-outline"></i>
                        <span class="nav-text">General Articles</span> <b class="caret"></b>
                    </a>

                    <ul  class="collapse"  id="general-articles"
                         data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li ><a class="sidenav-item-link" href="{{route('category.index')}}"><span class="nav-text">Categories</span></a></li>
                            <li ><a class="sidenav-item-link" href="{{route('post.index')}}"><span class="nav-text">Posts</span></a></li>
                            <li ><a class="sidenav-item-link" href="{{route('tag.index')}}"><span class="nav-text">Tags</span></a></li>
                        </div>
                    </ul>
                </li>
                <li>
                    <a class="sidenav-item-link" href="{{route('orders.index')}}">
                        <i class="mdi mdi-cart"></i>
                        <span class="nav-text">Orders</span>
                    </a>
                </li>
                <li >
                    <a class="sidenav-item-link" href="{{route('students.index')}}">
                        <i class="mdi mdi-account-group-outline"></i>
                        <span class="nav-text">Students </span>
                    </a>
                </li>

                <li>
                    <a class="sidenav-item-link" href="{{route('project.index')}}">
                        <i class="mdi mdi-trophy-outline"></i>
                        <span class="nav-text">Projects</span>
                    </a>
                </li>
                <li>
                    <a class="sidenav-item-link" href="{{route('feedback.index')}}">
                        <i class="mdi mdi-heart-multiple"></i>
                        <span class="nav-text">Feedback</span>
                    </a>
                </li>


            </ul>
        </div>

{{--        <div class="sidebar-footer">--}}
{{--            <hr class="separator mb-0 mt-0" />--}}
{{--            <div class="sidebar-footer-content" style="padding-top: 0px">--}}
{{--                <h6 class="text-uppercase">--}}
{{--                    Settings <span class="float-right">40%</span>--}}
{{--                </h6>--}}
{{--                <div class="progress progress-xs">--}}
{{--                    <div class="progress-bar active" style="width: 40%;" role="progressbar"></div>--}}

{{--                </div>--}}
{{--        </div>--}}
{{--    </div>--}}
</aside>



