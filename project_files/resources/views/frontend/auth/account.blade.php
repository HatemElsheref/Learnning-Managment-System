@extends('frontend.layouts.app')

@section('navbar')

    @include('frontend.layouts.navbarv1')

@endsection

@push('css_before')
@endpush
@push('css_after')
@endpush


@section('content')

    <main class="margin_main_container">
        <div class="user_summary">
            <div class="wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <figure>
                                <img src="{{route('storage',['users',auth('web')->user()->photo])}}">
                            </figure>
                            <h1>{{auth('web')->user()->name}}</h1>
                            <span class="d-block">{{auth('web')->user()->department->university->name}}</span>
                            <span class="d-block">{{auth('web')->user()->department->name}}</span>
                            <span class="d-block">{{auth('web')->user()->country}}</span>

                        </div>
                        <div class="col-md-6">
                            <ul>
                                <li>
                                    <strong>{{$reviews}}</strong>
                                    <a href="#0" class="tooltips" data-toggle="tooltip" data-placement="bottom" title="Reviews written by you"><i class="icon_star"></i> Reviews</a>
                                </li>
                                <li>
                                    <strong>{{$my_courses}}</strong>
                                    <a href="#0" class="tooltips" data-toggle="tooltip" data-placement="bottom" title="Number of Courses You Enrolled In"><i class="icon-play-circled"></i>Courses</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /container -->
            </div>
        </div>
        <!-- /user_summary -->
        <div class="container margin_60_35">
            <div class="row">
                <div class="col-lg-8">
                    <div class="settings_panel">
                        <h3>Personal settings</h3>
                        <hr>

                        @if($errors->any())
                          @foreach($errors->all() as $error)
                              <div class="alert alert-danger">
                                  <i class="icon-error"></i>  {{$error}}
                              </div>
                              @endforeach
                        @endif
                        @if(session()->has('result'))
                                <div class="alert alert-success">
                                    <i class="icon-ok"></i> {{session()->get('result')}}
                                </div>
                        @endif
                        <form method="post" action="{{route('user.profile.update')}}" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                        <div class="form-group">
                            <label>Edit Full name</label>
                            <input class="form-control" type="text" name="name" placeholder="Enter Your Name" value="{{auth('web')->user()->name}}">
                        </div>
                        <div class="form-group">
                            <label>Edit Email</label>
                            <input class="form-control" type="email" name="email" placeholder="Enter Your Email" value="{{auth('web')->user()->email}}">
                        </div>
                        <div class="form-group">
                            <label>Edit Photo</label>
                            <div class="fileupload"><input type="file" name="photo" accept="image/*"></div>
                        </div>
                            <div class="form-group">
                                <label>Edit Phone</label>
                                <input class="form-control" type="text" name="phone" placeholder="Enter Your Phone" value="{{auth('web')->user()->phone}}">
                            </div>
                            <div class="form-group">
                                <label>Edit Address</label>
                                <input class="form-control" type="text" name="address" placeholder="Enter Your Address" value="{{auth('web')->user()->address}}">
                            </div>

                        <div class="form-group">
                            <label>Edit Country</label>
                            <select class="form-control" name="country" required >
                                <option selected disabled>Select Country</option>
                                @foreach($countries as $country)
                                    <option  value="{{$country->Name}}" @if(auth('web')->user()->country==$country->Name) selected @endif>{{$country->Name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Edit University</label>
                            <select class="form-control" id="university" >
                                <option  >Select University</option>
                                @foreach($universities as $university)
                                    <option  value="{{$university->id}}" @if(auth('web')->user()->department->university->id) selected @endif>{{$university->name}}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="department_id"  id="departments">
                                <option value="{{auth('web')->user()->department->id}}" >{{auth('web')->user()->department->name}}</option>
                            </select>
                        </div>
                        <p class="text-right">
                            <button class="btn_1 small add_top_15" type="submit">Save personal info</button>
                        </p>
                        </form>
                    <!-- /settings_panel -->
                    <div class="settings_panel">
                        <h3>Change password</h3>
                        <hr>
                        <form method="post" action="{{route('user.profile.reset.password')}}" >
                            @csrf
                            @method('put')
                        <div class="form-group">
                            <label>Current Password</label>
                            <input class="form-control" name="old_password" type="password" id="password">
                        </div>
                        <div class="form-group">
                            <label>New Password</label>
                            <input class="form-control" name="password" type="password" id="password1">
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input class="form-control" name="password_confirmation" type="password" id="password2">
                        </div>
                        <div id="pass-info" class="clearfix"></div>
                            <p class="text-right">
                                <button class="btn_1 small add_top_15" type="submit">Save password</button>
                            </p>
                        </form>
                    </div>
                    <!-- /settings_panel -->
                </div>
                </div>
                    <!-- /col -->

                <div class="col-lg-4" id="sidebar">
                    <div class="box_general">
                        Orders
                    @foreach($orders as $order)
                            <div class="mb-3">
                                <span>{{$order->course.'    '.date('d.M.Y',strtotime($order->created_at))}}</span>
                                <button onclick="document.getElementById('cancel-order-form-{{$order->id}}').submit();return false;" class="btn btn-sm btn-danger" style="float: right;">Cancel order</button>
                                <form action="{{route('cancel.order',$order->id)}}" method="post" id="cancel-order-form-{{$order->id}}">@csrf</form>
                                <span class="clearfix"></span>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </main>
    <!--/main-->
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <!-- SPECIFIC SCRIPTS -->
    <script src="{{frontend()}}js/pw_strenght.js"></script>
    <script>
        $('#university').on('change',function () {
            let university=$(this).val();
            let items="";
            $.ajax({
                method:'post',
                url:'{{route('profile.departments')}}',
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
@endpush



