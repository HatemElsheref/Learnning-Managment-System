
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="VANNO - Premium directory consumer reviews and listings template by Ansonika">
    <meta name="author" content="Ansonika">
    <title>VANNO | Premium directory consumer reviews and listings template by Ansonika.</title>

    <!-- Favicons-->
    <link rel="shortcut icon" href="{{frontend()}}img/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" type="image/x-icon" href="{{frontend()}}img/apple-touch-icon-57x57-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="{{frontend()}}img/apple-touch-icon-72x72-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="{{frontend()}}img/apple-touch-icon-114x114-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="{{frontend()}}img/apple-touch-icon-144x144-precomposed.png">

    <!-- GOOGLE WEB FONT -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

    <!-- BASE CSS -->
    <link href="{{frontend()}}css/bootstrap.min.css" rel="stylesheet">
    <link href="{{frontend()}}css/style.css" rel="stylesheet">
    <link href="{{frontend()}}css/vendors.css" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href={{frontend()}}css/custom.css" rel="stylesheet">
                           <style>
                               #login_bg, #register_bg{
                                   background: #ccc url('{{frontend()}}images/bg_login.jpg') center center no-repeat fixed;
                               }
                               #login figure, #register figure{
                                   margin: -45px -45px 0 -45px;
                               }
                               .space{
                                   margin-top: 120px;
                               }
                           </style>
</head>

<body id="login_bg">

<nav id="menu" class="fake_menu"></nav>

<div id="preloader">
    <div data-loader="circle-side"></div>
</div>
<!-- End Preload -->

<div id="login">
    <aside>
        <figure>
            <a href="/"><img src="{{frontend()}}img/logo_sticky.svg" width="140" height="35" alt="" class="logo_sticky"></a>
        </figure>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="space"></div>
            <div class="form-group">
                <input id="email" type="email"  placeholder="Email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                <i class="icon_mail_alt"></i>
                @error('email')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>
            <div class="form-group">
                <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                <i class="icon_lock_alt"></i>

                @error('password')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>
            <div class="clearfix add_bottom_30">
                <div class="checkboxes float-left">
                    <label class="container_check">Remember me
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                        <span class="checkmark"></span>
                    </label>
                </div>
                @if (Route::has('password.request'))
                    <div class="float-right ">
                    <a  id="forgot" class="btn btn-link" href="{{ route('password.request') }}">
                        Forgot Password?
                    </a>
                    </div>
                @endif

            </div>

            <button type="submit" class="btn_1 rounded full-width">
                Login to Vanno
            </button>
            <div class="text-center add_top_10">New to Vanno? <strong><a href="{{route('register')}}">Sign up!</a></strong></div>
        </form>
        <div class="copy">© 2018 Vanno</div>
    </aside>
</div>
<!-- /login -->

<!-- COMMON SCRIPTS -->
<script src="{{frontend()}}js/common_scripts.js"></script>
<script src="{{frontend()}}js/functions.js"></script>
<script src="{{frontend()}}assets/validate.js"></script>

</body>
</html>
