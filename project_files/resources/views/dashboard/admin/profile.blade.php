@extends('dashboard.layouts.app')
@section('title')@lang('dashboard.profile_admin')@endsection

@section('content')

    <div class="content-wrapper">
        <div class="content">
            <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between __web-inspector-hide-shortcut__">
                <div>
                    <h1>@lang('dashboard.admins')</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">@lang('dashboard.dashboard')</a></li>
                            <li class="breadcrumb-item"><a href="{{route('admin.index')}}">@lang('dashboard.admins')</a></li>
                            <li class="breadcrumb-item" aria-current="page">@lang('dashboard.admin_profile')</li>
                        </ol>
                    </nav>
                </div>
                <div>
                </div>
            </div>
            <div class="bg-white border rounded">
                <div class="row no-gutters">
                    <div class="col-lg-4 col-xl-3">
                        <div class="profile-content-left pt-5 pb-3 px-3 px-xl-5">
                            <div class="card text-center widget-profile px-0 border-0">
                                <div class="card-img mx-auto rounded-circle img-responsive">
                                    <img src="{{route('UPLOADED.FILES',['admins_avatars',$staff->avatar,null,null])}}" alt="user image" width="100px" height="100px">
                                </div>
                                <div class="card-body">
                                    <h4 class="py-2 text-dark">{{ucwords($staff->name)}}</h4>
                                    <p>{{ucfirst($staff->role)}}</p>
                                </div>
                            </div>
                            <hr class="w-100">
                            <div class="contact-info pt-4">
                                <h5 class="text-dark mb-1">Contact Information</h5>
                                <p class="text-dark font-weight-medium pt-4 mb-2">Email address</p>
                                <p>{{$staff->email}}</p>
                                <p class="text-dark font-weight-medium pt-4 mb-2">Phone Number</p>
                                <p>{{$staff->phone}}</p>
                                <p class="text-dark font-weight-medium pt-4 mb-2">Gender</p>
                                <p>{{ucfirst($staff->gender)}}</p>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-8 col-xl-9">
                        <div class="profile-content-right py-5">
                            <ul class="nav nav-tabs px-3 px-xl-5 nav-style-border" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="settings-tab" data-toggle="tab" href="#settings" role="tab" aria-controls="settings" aria-selected="false">Settings</a>
                                </li>
                            </ul>
                            <div class="tab-content px-3 px-xl-5" id="myTabContent">
                                <div class="tab-pane fade show active" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                                    <div class="mt-5">
                                        @include('dashboard.layouts.error')
                                        @if(auth('webadmin')->user()->id==$staff->id)
                                        <form method="post" action="{{route('admin.profile',$staff->id)}}" enctype="multipart/form-data">
                                            @csrf
                                            @method('put')
                                            @else
                                                <form >
                                                @endif

                                            <div class="row mb-2">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="name">@lang('dashboard.admin_name')</label>
                                                        <input type="text" id="name" class="form-control" name="name" value="{{$staff->name}}"   @if(auth('webadmin')->user()->id!=$staff->id) disabled @endif>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="phone">@lang('dashboard.admin_phone')</label>
                                                        <input type="text" class="form-control" id="phone"  name="phone" value="{{$staff->phone}}" @if(auth('webadmin')->user()->id!=$staff->id) disabled @endif>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group mb-4">
                                                <label for="email">@lang('dashboard.admin_email')</label>
                                                <input type="email" class="form-control" id="email" name="email" value="{{$staff->email}}" @if(auth('webadmin')->user()->id!=$staff->id) disabled @endif>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label>@lang('dashboard.gender')</label>
                                                <label class="control control-radio">@lang('dashboard.male')
                                                    <input type="radio" name="gender" value="male" @if('male'==$staff->gender) checked="checked" @endif @if(auth('webadmin')->user()->id!=$staff->id) disabled @endif>
                                                    <div class="control-indicator"></div>
                                                </label>
                                                <label class="control control-radio">@lang('dashboard.female')
                                                    <input type="radio" name="gender" value="female" @if('female'==$staff->gender) checked="checked" @endif @if(auth('webadmin')->user()->id!=$staff->id) disabled @endif>
                                                    <div class="control-indicator"></div>
                                                </label>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="user_avatar">@lang('dashboard.admin_avatar')</label>
                                                <input type="file" class="form-control-file" id="user_avatar" hidden name="avatar" @if(auth('webadmin')->user()->id!=$staff->id) disabled @endif>
                                                <button class="btn  btn-success" style="display:block" onclick="document.getElementById('user_avatar').click();return false;" @if(auth('webadmin')->user()->id!=$staff->id) disabled @endif>@lang('dashboard.upload')</button>
                                            </div>
                                                    <div class="col-sm-1 col-lg-1">
                                                        <img id="tmp_image" class="img-circle" onclick="document.getElementById('user_avatar').click();" style="width:50px;height: 50px;" src="{{route('UPLOADED.FILES',['admins_avatars',$staff->avatar,null,null])}}">
                                                    </div>

                                            <div class="form-group mb-4">
                                                <label for="password">@lang('dashboard.admin_password_old')</label>
                                                <input type="password" class="form-control" id="password" name="password_old"  placeholder="@lang('dashboard.enter') @lang('dashboard.admin_password_old')" @if(auth('webadmin')->user()->id!=$staff->id) disabled @endif>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="password">@lang('dashboard.admin_password')</label>
                                                <input type="password" class="form-control" id="password" name="password"  placeholder="@lang('dashboard.enter') @lang('dashboard.admin_password')" @if(auth('webadmin')->user()->id!=$staff->id) disabled @endif>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="password_confirmation">@lang('dashboard.admin_password_confirmation')</label>
                                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="@lang('dashboard.enter') @lang('dashboard.admin_password_confirmation')" @if(auth('webadmin')->user()->id!=$staff->id) disabled @endif>
                                            </div>
                                            @if(auth('webadmin')->user()->id==$staff->id)
                                            <div class="d-flex justify-content-end mt-5">
                                                <button type="submit" class="btn btn-success mb-2 btn-pill">Update Profile</button>
                                            </div>
                                            @endif
                                            <div class="mt-5 ">
                                                @foreach($staff->permissions as $permission)
                                                    <span class="badge badge-sm badge-info badge-pill">{{$permission->alias}}</span>
                                                @endforeach
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        @push('scripts')
            <script>
                function readURL(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            $('#tmp_image').attr('src', e.target.result);
                        }

                        reader.readAsDataURL(input.files[0]); // convert to base64 string
                    }
                }

                $("#user_avatar").change(function() {
                    readURL(this);
                });
            </script>
    @endpush
@endsection
