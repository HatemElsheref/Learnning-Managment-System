
@extends('dashboard.layouts.app')
@section('title')
    Edit Project
@endsection

@section('content')

    @push('scripts')
        <script src='https://cloud.tinymce.com/stable/tinymce.min.js'></script>
        <script>
            var editor_config = {
                selector: 'textarea#editor1',
                height:400,
                statusbar: false,
                theme: 'modern',
                plugins: [
                    "advlist autolink lists   charmap print preview hr  pagebreak",
                    "searchreplace wordcount visualblocks visualchars  fullscreen",
                    "  nonbreaking save  contextmenu directionality",
                    "emoticons  paste textcolor colorpicker textpattern"
                ],
                toolbar: " undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",

            };
            tinymce.init(editor_config);

        </script>
    @endpush


    <div class="content-wrapper">
        <div class="content">
            <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between __web-inspector-hide-shortcut__">
                <div>
                    <h1>Projects</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('project.index')}}">Projects</a></li>
                            <li class="breadcrumb-item" aria-current="page">Edit Project</li>
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
                            <form action="{{route('project.update',$project->id)}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <label for="name">Project Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{$project->name}}" placeholder="Enter Project Name">
                                </div>
                                <div class="form-group">
                                    <label for="type">Project Type</label>
                                    <input type="text" class="form-control" id="type" name="type" value="{{$project->type}}" placeholder="Enter Project Type">
                                </div>
                                <div class="form-group">
                                    <label for="link">Project Link</label>
                                    <input type="text" class="form-control" id="link" name="link" value="{{$project->link}}" placeholder="Enter Project Link">
                                </div>
                                <div class="form-group row ">
                                    <label for="photo" class="col-sm-4 col-lg-2 col-form-label">Project Photo</label>
                                    <div class="col-sm-11 col-lg-11">
                                        <div class="custom-file mb-1">
                                            <input type="file" class="custom-file-input" id="photo" name="photo">
                                            <label class="custom-file-label" for="coverImage">Choose file...</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-1 col-lg-1">
                                        <img id="tmp_image" class="img-circle" onclick="document.getElementById('photo').click();" style="width:50px;height: 50px;" src="{{route('UPLOADED.FILES',['projects_photos',$project->photo,$project->id,null])}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="editor1">Project Description</label>
                                    <textarea id="editor1" name="description">{!! $project->description !!}</textarea>
                                </div>
                                <div class="form-group">
                                    <ul class="list-unstyled list-inline">
                                        <li class="d-inline-block mr-3">
                                            <label class="control control-checkbox">Published
                                                <input type="checkbox" name="status" value="published" @if($project->status) checked @endif>
                                                <div class="control-indicator"></div>
                                            </label>
                                        </li>
                                    </ul>
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


