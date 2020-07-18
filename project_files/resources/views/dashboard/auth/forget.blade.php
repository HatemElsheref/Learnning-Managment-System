
<!DOCTYPE html>
<html lang="en">
<head>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="Sleek Dashboard - Free Bootstrap 4 Admin Dashboard Template and UI Kit. It is very powerful bootstrap admin dashboard, which allows you to build products like admin panels, content management systems and CRMs etc.">


        <title>@lang('dashboard.forget_page')</title>

        <!-- GOOGLE FONTS -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500" rel="stylesheet" />
        <link href="https://cdn.materialdesignicons.com/4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />


        <!-- PLUGINS CSS STYLE -->
        <link href="{{Dashboard_Assets()}}/plugins/nprogress/nprogress.css" rel="stylesheet" />



        <!-- SLEEK CSS -->
        <link id="sleek-css" rel="stylesheet" href="{{Dashboard_Assets()}}/css/sleek.css" />

        <!-- FAVICON -->
        <link href="{{Dashboard_Assets()}}/img/favicon.png" rel="shortcut icon" />



        <!--
          HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries
        -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script src="{{Dashboard_Assets()}}/plugins/nprogress/nprogress.js"></script>
    </head>

</head>
<body class="" id="body">
<div class="container d-flex flex-column justify-content-between vh-100">
    <div class="row justify-content-center mt-5">
        <div class="col-xl-5 col-lg-6 col-md-10" >
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="app-brand">
                        <a href="/index.html" style="{{CurrentLanguage()=='ar'?'float:right;width:auto;padding-right:10px':''}}">
                            @if(CurrentLanguage()=='ar')
                                <span class="brand-name" style="float:right;width:auto;padding-right:20px">@lang('dashboard.site_name')</span>
                            @endif
                            <svg class="brand-icon" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid" width="30" height="33"
                                 viewBox="0 0 30 33">
                                <g fill="none" fill-rule="evenodd">
                                    <path class="logo-fill-blue" fill="#7DBCFF" d="M0 4v25l8 4V0zM22 4v25l8 4V0z" />
                                    <path class="logo-fill-white" fill="#FFF" d="M11 4v25l8 4V0z" />
                                </g>
                            </svg>
                            @if(CurrentLanguage()=='en')
                                <span class="brand-name">@lang('dashboard.site_name')</span>
                            @endif
                        </a>
                    </div>
                </div>
                <div class="card-body p-5">

                    <h4 class="text-dark mb-3" style="{{CurrentLanguage()=='ar'?'text-align:end;':''}}">@lang('dashboard.password_recovery')</h4>
                    @if(session()->has('email_not_found'))

                        @if(CurrentLanguage()=='en')
                            <span class="text-{{session('type')}}">
                            <i class=" mdi mdi-flash-circle"></i>
                             {{__('dashboard.'.session()->get('email_not_found'))}}
                            </span>
                        @else
                            <span class="text-{{session('type')}}" style="text-align:end;display:block;margin-bottom:0px">
                                {{__('dashboard.'.session()->get('email_not_found'))}} <i class=" mdi mdi-flash-circle"></i>
                            </span>
                        @endif

                    @endif
                    <form action="{{route('DoForgetPassword')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-12 mb-4" >
                                <input style="{{CurrentLanguage()=='ar'?'text-align:end;padding-right:35px':''}}" type="email" class="form-control input-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  id="email" aria-describedby="emailHelp" placeholder="@lang('dashboard.enter_email')">
                                @error('email')
                                <span class="invalid-feedback" style="{{CurrentLanguage()=='ar'?'text-align:end':''}}" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-lg btn-primary btn-block mb-4">@lang('dashboard.send')</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright pl-0">
        <p class="text-center">&copy; 2018 Copyright Reserved by
            <a class="text-primary" href="https://www.facebook.com/hatem.elsheref.73" target="_blank">ELSHEREF</a>.
        </p>
    </div>
</div>

</body>
</html>
