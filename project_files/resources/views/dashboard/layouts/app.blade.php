@include('dashboard.layouts.header')

@include('dashboard.layouts.sidebar')


@include('dashboard.layouts.navbar')

@include('sweetalert::alert')

@yield('content')
@include('dashboard.layouts.footer')
