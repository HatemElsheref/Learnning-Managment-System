@extends('dashboard.layouts.app')
@section('title')
    Add New Article
@endsection

@section('content')

    @push('scripts')
        <script src='https://cloud.tinymce.com/stable/tinymce.min.js'></script>
        <script>
            var editor_config = {
                selector: 'textarea#editor1',
                path_absolute :  "{{ url('/') }}/en/dashboard/",
                convert_urls: true,
                height:400,
                statusbar: false,
                theme: 'modern',
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor colorpicker textpattern"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
                relative_urls: false,
                file_browser_callback : function(field_name, url, type, win) {
                    var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                    var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

                    var cmsURL = editor_config.path_absolute +'laravel-filemanager?field_name=' + field_name;
                    if (type == 'image') {
                        cmsURL = cmsURL + "&type=Images";
                    } else {
                        cmsURL = cmsURL + "&type=Files";
                    }

                    tinyMCE.activeEditor.windowManager.open({
                        file : cmsURL,
                        title : 'Filemanager',
                        width : x * 0.8,
                        height : y * 0.8,
                        resizable : "yes",
                        close_previous : "no"
                    });}};
            tinymce.init(editor_config);

        </script>
    @endpush


    <div class="content-wrapper">
        <div class="content">
            <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between __web-inspector-hide-shortcut__">
                <div>
                    <h1>Course Articles</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('course.index')}}">Courses</a></li>
                            <li class="breadcrumb-item"><a href="{{route('course.show',$course->id)}}">{{ucfirst(strtolower($course->name))}}</a></li>
                            <li class="breadcrumb-item"><a href="{{route('course.article.show',$course->id)}}">Articles</a></li>
                            <li class="breadcrumb-item" aria-current="page">Add New Article</li>
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
                            <form action="{{route('course.article.store')}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                <input type="hidden" name="course_id" value="{{$course->id}}">
                                <div class="form-group">
                                    <label for="title">Article Title</label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{old('title')}}" placeholder="Enter Article Title">
                                </div>
                                <div class="form-group">
                                    <label for="subtitle">Article SubTitle</label>
                                    <input type="text"  class="form-control" id="subtitle" name="subtitle" value="{{old('subtitle')}}" placeholder="Enter Article Sub Title at least 80">
                                </div>
                                <div class="form-group">
                                    <label for="slug">Article Slug</label>
                                    <input type="text" class="form-control" id="slug" name="slug" value="{{old('slug')}}" placeholder="Enter Article Slug">
                                </div>
                                <div class="form-group">
                                    <label for="dir">Direction</label>
                                    <div class="form-group" >
                                        <select class=" form-control " name="dir" id="dir">
                                            <option disabled selected>Select Direction</option>

                                            <option value="ltr" @if(old('dir')=='ltr') selected @endif>LTR</option>
                                            <option value="rtl" @if(old('dir')=='rtl') selected @endif>RTL</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label for="photo" class="col-sm-4 col-lg-2 col-form-label">Article Photo</label>
                                    <div class="col-sm-11 col-lg-11">
                                        <div class="custom-file mb-1">
                                            <input type="file" class="custom-file-input" id="photo" name="photo">
                                            <label class="custom-file-label" for="coverImage">Choose file...</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-1 col-lg-1">
                                        <img id="tmp_image" class="img-circle" onclick="document.getElementById('photo').click();" style="width:50px;height: 50px;" src="{{frontend()}}images/tmp.jpg">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tags">Tags</label>
                                    <div class="form-group" >
                                        <select class="key-words form-control " name="tags[]" multiple id="tags">
                                            @foreach((array)old('tags') as $tag)
                                                <option value="{{$tag}}" selected>{{$tag}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @push('scripts')
                                        <script>
                                            $(".key-words").select2({
                                                tags: true,
                                                tokenSeparators: [","]
                                            });
                                        </script>
                                    @endpush
                                </div>
                                <div class="form-group">
                                    <label for="editor1">Article Content</label>
                                    <textarea id="editor1" name="content">{!! old('content') !!}</textarea>
                                </div>
                                <div class="form-group">
                                <h1>
                                    <label for="meta_title">For Meta Tags</label>
                                </h1>
                                </div>
                                <div class="form-group">
                                    <label for="meta_title">Title</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{old('meta_title')}}" placeholder="Enter Article Meta Title">
                                </div>

                                <div class="form-group">
                                    <label for="meta_keywords">Keywords</label>
                                    <div class="form-group" >
                                        <select class="key-words form-control " name="meta_keywords[]" multiple id="meta_keywords">
                                            @foreach((array)old('meta_keywords') as $keyword)
                                                <option value="{{$keyword}}" selected>{{$keyword}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @push('scripts')
                                        <script>
                                            $(".key-words").select2({
                                                tags: true,
                                                tokenSeparators: [","]
                                            });
                                        </script>
                                    @endpush
                                </div>
                                <div class="form-group">
                                    <label for="meta_description">Description</label>
                                    <input type="text" class="form-control" id="meta_description" name="meta_description" value="{{old('meta_description')}}" placeholder="Enter Article Meta Description">
                                </div>


                                <div class="form-footer pt-4 pt-2 mt-2 border-top">
                                    <button type="submit" class="btn btn-primary btn-default">Save</button>

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


