@extends('dashboard.layouts.app')
@section('title')
    Edit Department
@endsection

@section('content')


    <div class="content-wrapper">
        <div class="content">
            <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between __web-inspector-hide-shortcut__">
                <div>
                    <h1>Departments</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('department.index')}}">Department</a></li>
                            <li class="breadcrumb-item" aria-current="page">Edit Department</li>
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
{{--                            <form action="{{route('department.update',$department->id)}}" method="post" enctype="multipart/form-data" autocomplete="off">--}}
                            <form action="{{route('department.update',$department->id)}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <label for="name">Department Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{$department->name}}" placeholder="Enter  Department Name">
                                </div>
                                <div class="form-group">
                                    <label for="University">University</label>
                                    <select class="form-control" id="University" name="university_id">
                                        <option selected disabled>select university</option>
                                        @foreach($universities as $university)
                                            <option value="{{$university->id}}" @if($department->university_id==$university->id) selected @endif>
                                                {{$university->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="slug">Department Slug</label>
                                    <input type="text" class="form-control" id="slug" name="slug" value="{{$department->slug}}" placeholder="Enter Department Slug">
                                </div>
                                <div class="form-group row ">
                                    <label for="photo" class="col-sm-4 col-lg-2 col-form-label">Department Photo</label>
                                    <div class="col-sm-11 col-lg-11">
                                        <div class="custom-file mb-1">
                                            <input type="file" class="custom-file-input" id="photo" name="photo">
                                            <label class="custom-file-label" for="coverImage">Choose file...</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-1 col-lg-1">
                                        <img id="tmp_image" class="img-circle" onclick="document.getElementById('photo').click();" style="width:50px;height: 50px;" src="{{route('UPLOADED.FILES',['departments_avatars',$department->photo])}}">
                                    </div>
                                </div>

                                <div class="form-footer pt-4 pt-2 mt-2 border-top">
                                    <button type="submit" class="btn btn-success btn-default">Edit</button>

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

                $("#photo").change(function() {
                    readURL(this);
                });
            </script>
    @endpush
@endsection


