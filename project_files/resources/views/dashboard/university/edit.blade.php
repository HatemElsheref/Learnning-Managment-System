@extends('dashboard.layouts.app')
@section('title')
    Edit University
@endsection

@section('content')

    @push('scripts')
        <script src="{{Dashboard_Assets()}}/js/editor/ckeditor.js"></script>
        <script>CKEDITOR.replace('editor1',{height:"100px"}); </script>
    @endpush


    <div class="content-wrapper">
        <div class="content">
            <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between __web-inspector-hide-shortcut__">
                <div>
                    <h1>Universities</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashbooard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('university.index')}}">University</a></li>
                            <li class="breadcrumb-item" aria-current="page">Edit University</li>
                        </ol>
                    </nav>
                </div>
                <div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card card-default">
                        <div class="card-body">
                            @include('dashboard.layouts.validation_error')
                            <form action="{{route('university.update',$university->id)}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <label for="name">University Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{$university->name}}" placeholder="Enter University Name">
                                </div>
                                <div class="form-group">
                                    <label for="address">University Address</label>
                                    <input type="text" class="form-control" id="address" name="address" value="{{$university->address}}" placeholder="Enter University Address">
                                </div>
                                <div class="form-group">
                                    <label for="phone">University Description</label>
                                    <textarea id="editor1" name="description">{!! $university->description !!}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="address">University Slug</label>
                                    <input type="text" class="form-control" id="slug" name="slug" value="{{$university->slug}}" placeholder="Enter University Slug">
                                </div>
                                <div class="form-group row ">
                                    <label for="photo" class="col-sm-4 col-lg-2 col-form-label">University Photo</label>
                                    <div class="col-sm-11 col-lg-11">
                                        <div class="custom-file mb-1">
                                            <input type="file" class="custom-file-input" id="photo" name="photo">
                                            <label class="custom-file-label" for="coverImage">Choose file...</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-1 col-lg-1">
                                        <img id="tmp_image" class="img-circle" onclick="document.getElementById('photo').click();" style="width:50px;height: 50px;" src="{{route('UPLOADED.FILES',['universities_avatars',$university->photo])}}">
                                    </div>
                                </div>

                                <div class="form-footer pt-4 pt-2 mt-2 border-top">
                                    <button type="submit" class="btn btn-success btn-default">Save</button>

                                </div>
                            </form>
                        </div>
                    </div>

                </div>
{{--                <div class="col-3">--}}
{{--                    <div class="card card-default">--}}
{{--                        <div class="card-header card-header-border-bottom">--}}
{{--                            <h2>--}}
{{--                                <i class="mdi mdi-image"></i>--}}
{{--                                University Image</h2>--}}
{{--                        </div>--}}
{{--                        <div class="card-body">--}}
{{--                            <img class='rounded-circle w-45' style='margin-top: 0px;padding-top:0px;height:200px;width: 200px;' src='{{route('UPLOADED.FILES',['universities_avatars',$university->photo])}}' alt='university image'>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
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

                $("#photo").change(function() {
                    readURL(this);
                });
            </script>
    @endpush
@endsection


