
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
    <link href="{{frontend()}}css/custom.css" rel="stylesheet">
    <style>
        #login_bg, #register_bg{
            background: #ccc url('{{frontend()}}images/bg_register.jpg') center center no-repeat fixed;
        }
        #login figure, #register figure{
            margin: -45px -45px 0 -45px;
        }
        .space{
            margin-top: 70px;
        }
        .custom-icon{
            top: 5px!important;
            left: 8px!important;
        }
    </style>
</head>

<body id="register_bg">

<nav id="menu" class="fake_menu"></nav>

<div id="login">
    <aside>
        <figure>
            <a href="/"><img src="{{frontend()}}img/logo_sticky.svg" width="140" height="35" alt="" class="logo_sticky"></a>
        </figure>
{{--        <div class="access_social">--}}
{{--            <a href="#0" class="social_bt facebook">Register with Facebook</a>--}}
{{--            <a href="#0" class="social_bt google">Register with Google</a>--}}
{{--        </div>--}}
{{--        <div class="divider"><span>Or</span></div>--}}
        <form method="POST" autocomplete="off" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <input class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus  type="text" placeholder="Name">
                <i class="ti-user"></i>
                @error('name')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>
            <div class="form-group">
                <input type="email" placeholder="Email"  id="email"  class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                <i class="icon_mail_alt"></i>
                @error('email')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>
            <div class="form-group">
                <input class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" required  type="text" placeholder="Address">
                <i class=" icon-address-1 custom-icon"></i>
                @error('address')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>
            <div class="form-group">
                <input class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required  type="text" placeholder="Phone">
                <i class=" icon-mobile custom-icon"></i>
                @error('phone')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>
            <div class="form-group ">
                <select class="form-control @error('country') is-invalid @enderror" name="country" value="{{ old('country') }}" required >
                    <option selected disabled>Select Country</option>
                    @foreach($countries as $country)
                        <option  value="{{$country->Name}}" @if(old('country')==$country->id) selected @endif>{{$country->Name}}</option>
                        @endforeach

                </select>

                @error('country')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>
            <div class="form-group">
                <select class="form-control @error('department_id') is-invalid @enderror" id="university" >
                    <option selected disabled>Select University</option>
                    @foreach($universities as $university)
                        <option  value="{{$university->id}}">{{$university->name}}</option>
                    @endforeach

                </select>
                @error('department_id')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>
            <div class="form-group">
                <select class="form-control @error('department_id') is-invalid @enderror" name="department_id" disabled id="departments">
                    <option selected disabled >Select Department</option>
                </select>
                @error('department_id')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>
            <div class="form-group">
                <input id="password1" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required  placeholder="Password">
                <i class="icon_lock_alt"></i>
                @error('password')
                <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <input type="password" id="password2" class="form-control" name="password_confirmation" required  placeholder="Confirm Password">
                <i class="icon_lock_alt"></i>
            </div>
            <div id="pass-info" class="clearfix"></div>

            <button type="submit" class="btn_1 rounded full-width" >
                Register Now!
            </button>
            <div class="text-center add_top_10">Already have an acccount?
                <strong><a href="{{route('login')}}">Sign In</a></strong></div>
        </form>
        <div class="copy">© 2018 Vanno</div>
    </aside>
</div>
<!-- /login -->

<!-- COMMON SCRIPTS -->
<script src="{{frontend()}}js/common_scripts.js"></script>
<script src="{{frontend()}}js/functions.js"></script>
<script src="{{frontend()}}assets/validate.js"></script>

<!-- SPECIFIC SCRIPTS -->
<script src="{{frontend()}}js/pw_strenght.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
                 <script>
                $('#university').on('change',function () {
                    let university=$(this).val();
                    let items="";
                    $.ajax({
                        method:'post',
                        url:'{{route('register.departments')}}',
                        datType:'application/json',
                        data:{university_id:university},
                        success:function (response) {
                            for (let dep=0; dep<response.departments.length;dep++){
                                items+="<option value='"+response.departments[dep].id+"'>"+response.departments[dep].name+"</option>";
                            }
                            $('#departments').prop('disabled',false).html(items);
                        }
                    });


                });

                 </script>
</body>
</html>
