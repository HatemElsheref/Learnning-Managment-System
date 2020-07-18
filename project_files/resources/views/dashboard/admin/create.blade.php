@extends('dashboard.layouts.app')
@section('title')@lang('dashboard.create_admin')@endsection

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
                            <li class="breadcrumb-item" aria-current="page">@lang('dashboard.add_new_admin')</li>
                        </ol>
                    </nav>
                </div>
                <div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card card-default">
{{--                        <div class="card-header card-header-border-bottom">--}}
{{--                            <h2>@lang('dashboard.add_new_admin')</h2>--}}
{{--                        </div>--}}
                        <div class="card-body">
                            @include('dashboard.layouts.error')
                            <form action="{{route('admin.store')}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                <div class="form-group">
                                    <label for="name">@lang('dashboard.admin_name')</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}" placeholder="@lang('dashboard.enter') @lang('dashboard.admin_name')">
                                </div>
                                <div class="form-group">
                                    <label for="email">@lang('dashboard.admin_email')</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{old('email')}}" placeholder="@lang('dashboard.enter') @lang('dashboard.admin_email')">
{{--                                    <span class="mt-2 d-block">We'll never share your email with anyone else.</span>--}}
                                </div>
                                <div class="form-group">
                                    <label for="phone">@lang('dashboard.admin_phone')</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{old('phone')}}" placeholder="@lang('dashboard.enter') @lang('dashboard.admin_phone')">
                                </div>
                                <div class="form-group ">
                                    <label>@lang('dashboard.gender')</label>
                                    <label class="control control-radio">@lang('dashboard.male')
                                        <input type="radio" name="gender" value="male" @if(old('gender')=='male') checked="checked" @endif>
                                        <div class="control-indicator"></div>
                                    </label>
                                        <label class="control control-radio">@lang('dashboard.female')
                                            <input type="radio" name="gender" value="female" @if(old('gender')=='female') checked="checked" @endif>
                                            <div class="control-indicator"></div>
                                        </label>
                                </div>
                                <div class="form-group">
                                    <label for="password">@lang('dashboard.admin_password')</label>
                                    <input type="password" class="form-control" id="password" name="password"  placeholder="@lang('dashboard.enter') @lang('dashboard.admin_password')">
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">@lang('dashboard.admin_password_confirmation')</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="@lang('dashboard.enter') @lang('dashboard.admin_password_confirmation')">
                                </div>
                                <div class="form-group">
                                    <label for="user_avatar">@lang('dashboard.admin_avatar')</label>
                                    <input type="file" class="form-control-file" id="user_avatar" hidden name="avatar">
                                    <button class="btn  btn-primary" style="display:block" onclick="document.getElementById('user_avatar').click();return false;">@lang('dashboard.upload')</button>
                                </div>
                                <div class="col-sm-1 col-lg-1">
                                    <img id="tmp_image" class="img-circle" onclick="document.getElementById('user_avatar').click();" style="width:50px;height: 50px;" src="{{frontend()}}images/tmp.jpg">
                                </div>
                                <div class="form-group">
                                    <label for="permissions">@lang('dashboard.admin_permissions')</label>
                                    <table class="table table-hover table-responsive permission-table" id="permissions">
                                        <thead class="thead-light first-main-color">
                                        <?php
                                        $Models=config('permissions.Models');
                                        $Maps=config('permissions.Maps');
                                        ?>
                                        <tr>
                                            <th scope="col"  style="color:#fff !important;background-color:#4c84ff">@lang('dashboard.models')</th>
                                            @foreach($Maps as $char=>$value)
                                                <th scope="col"  style="color:#fff !important;background-color:#4c84ff" >@lang('dashboard.'.$value)</th>
                                            @endforeach

                                        </tr>
                                        </thead>
                                        <tbody>


                                        @foreach($Models as $model=>$values)
                                            <tr>
                                                <td style="margin-bottom: 10px" >
                                                @if($model=='admins')
                                                      SuperVisor
                                                       @else
                                                    {{ucfirst($model)}}
                                                    @endif
                                                </td>

                                                @foreach($Maps as $char=>$value)
                                                    @if(!in_array($char,$values))
                                                        <td>
                                                            <div class="custom-control custom-checkbox mb-3">
                                                                <input type="checkbox" disabled class="custom-control-input">
                                                                <label class="custom-control-label"> </label>
                                                            </div>
                                                        </td>
                                                    @else

                                                        <td>
                                                            <div class="custom-control custom-checkbox mb-3">
                                                                <input type="checkbox"  class="custom-control-input" id="{{strtolower($value)}}_{{strtolower($model)}}" name="permissions[]" value="{{strtolower($value).'_'.strtolower($model)}}" @if(in_array(strtolower($value).'_'.strtolower($model),(array)old('permissions'))) checked @endif>
                                                                <label class="custom-control-label" for="{{strtolower($value)}}_{{strtolower($model)}}"></label>
                                                            </div>
                                                        </td>
                                                    @endif
                                                @endforeach

                                            </tr>

                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-footer pt-4 pt-5 mt-4 border-top">
                                    <button type="submit" class="btn btn-primary btn-default">@lang('dashboard.save')</button>

                                </div>
                            </form>
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
