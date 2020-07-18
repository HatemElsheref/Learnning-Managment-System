
@extends('dashboard.layouts.app')
@section('title')
    Add New Course
@endsection
@push('styles')
    <link rel="stylesheet" href="{{asset('dashboard/form-wizzard')}}/style.css">
    @if(app()->getLocale()=='ar')
        <style>
            .multisteps-form__progress-btn:after{
                left: calc(50% - 13px / 2);
            }
            .wizard-height{
                height: 561px!important;
            }
        </style>
    @endif
@endpush

@push('scripts')
    <script src="{{asset('dashboard/form-wizzard')}}/script.js"></script>
    <script>
        $(document).ready(function() {
            var wrapper = $(".container1");
            $(".add_form_field").click(function(e) {
                e.preventDefault();
                $(wrapper).append('<div><input type="text" class="form-control" name="parts[]"/>' +
                    '<a href="#" class="delete"><i class="mdi mdi-tag-remove  mdi-18px text-danger"></i></a></div>'); //add input box
            });

            $(wrapper).on("click", ".delete", function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
            })
        });
    </script>
@endpush


@section('content')

    @push('scripts')
        <script src='https://cloud.tinymce.com/stable/tinymce.min.js'></script>
{{--<script src="https://cdn.tiny.cloud/1/4b2rd40qpdelgfmi3oofgocpnxs28fndbr734f0c12ctqmk0/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>--}}
        <script>
            var editor_config = {
                selector: 'textarea#desc',
                path_absolute :  "{{ url('/') }}/en/dashboard/",
                convert_urls: true,
                height:150,
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
                // placeholder:'Enter Course Description At Least 80 Characters',
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
        <script type="text/javascript">
            function addOption(name,id) {
                elements="";
                $('#departments').append("<option value="+id+">"+name+"</option>");
            }
            $('#universities').on('change',function () {
                let id=$(this).val();
                $.ajax({
                    url:'{{route('university.departments.get')}}',
                    method:'get',
                    data:{
                        _token:'{{csrf_token()}}',
                        id:id
                    },
                    success:function (response) {
                        let elements="<option value='0'>Select Department</option>";
                        for (var i = 0; i < response.length; i++) {
                            var obj = response[i];
                            elements+="<option value="+obj.id+">"+obj.name+"</option>";
                        }
                        $('#departments').html(elements);
                        $('#departments').attr('disabled',false);
                    }
                })
            });
            function checkPrice(){
                if ($('#price_free').is(':checked')){
                    $('#price').removeAttr('name');
                    $('#price').attr('disabled','disabled');
                } else{
                    $('#price').removeAttr('disabled');
                    $('#price').attr('name','price');
                }
            }
        </script>
        <script>
            $("input[name='intro']").change(function () {
                if ($(this).val()=='local'){
                    $('#video_file').attr('name','video').prop('disabled',false);
                    $('#video_id').prop('disabled',true).removeAttr('name');
                }else{

                    $('#video_id').attr('name','video').prop('disabled',false);
                    $('#video_file').prop('disabled',true).removeAttr('name');
                }

            });
        </script>
    @endpush
    <div class="content-wrapper">
        <div class="content">
            <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between __web-inspector-hide-shortcut__">
                <div>
                    <h1>Courses</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('course.index')}}">Courses</a></li>
                            <li class="breadcrumb-item" aria-current="page">Add New Course</li>
                        </ol>
                    </nav>
                </div>
                <div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="multisteps-form__progress">
                        <button class="multisteps-form__progress-btn js-active" type="button" title="User Info">Course Info</button>
                        <button class="multisteps-form__progress-btn" type="button" title="Address">Course Details</button>
                        <button class="multisteps-form__progress-btn" type="button" title="Order Info">Course Show</button>
                        <button class="multisteps-form__progress-btn" type="button" title="Comments">Course Lesson Parts  </button>
                    </div>
                </div>

                <div class="col-12 wizard-height">
                    @include('dashboard.layouts.validation_error')

                    <form   class="multisteps-form__form wizard" action="{{route('course.store')}}" method="post" enctype="multipart/form-data" autocomplete="off" >
                    @csrf
                    <!--single form panel-->
                        <div class="multisteps-form__panel shadow p-4 rounded bg-white js-active wizard-height" data-animation="nono" style="height: 450px" >
                            <div class="multisteps-form__content">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="name">Course Name</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}" placeholder="Enter Course Name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="code">Course Code</label>
                                            <input type="text" class="form-control" id="code" name="code" value="{{old('code')}}" placeholder="Enter Course Code">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="row">
                                            <div class="col-sm-9">
                                                <div class="form-group">
                                                    <label for="price">Course Price</label>
                                                    <input type="number" class="form-control" id="price" step="1"  min="0" name="price" value="{{old('price')}}" placeholder="Enter Course Price">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="custom-control custom-checkbox mb-3">
                                                    <input type="checkbox"  class="custom-control-input" id="price_free"   onclick="checkPrice()">
                                                    <label class="custom-control-label" for="price_free">Free</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="editor1">Course Description</label>
                                    <textarea id="desc" name="description">
                                        {!! old('description') !!}
                                    </textarea>
                                </div>


                                <div class="button-row d-flex mt-4" style="position: relative;bottom: 10px">
                                    <button class="btn btn-primary ml-auto js-btn-next " type="button" title="Next">Next</button>
                                </div>
                            </div>
                        </div>
                        <!--single form panel-->
                        <div class="multisteps-form__panel shadow p-4 rounded bg-white wizard-height" data-animation="none" style="height: 450px;">
                            <div class="multisteps-form__content">

                                <div class="form-group">
                                    <label for="instructor">Instructor</label>
                                    <select class="form-control" id="instructor" name="instructor_id">
                                        <option selected disabled>select Instructor</option>
                                        @foreach($instructors as $instructor)
                                            <option value="{{$instructor->id}}" @if(old('instructor_id')==$instructor->id) selected @endif>
                                                {{$instructor->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>                                                                                                  <!-- scaleOut none nono-->
                                <div class="form-group">
                                    <label for="universities">University</label>
                                    <select class="form-control" id="universities" name="university_id" >
                                        <option selected disabled>select University</option>
                                        @foreach($universities as $university)
                                            <option value="{{$university->id}}">
                                                {{$university->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="departments">Department</label>
                                    <select class="form-control" id="departments" name="department_id"  disabled>

                                    </select>
                                </div>

                                <div class="button-row d-flex mt-4" style="position: relative;bottom: 15px">
                                    <button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
                                    <button class="btn btn-primary ml-auto js-btn-next" type="button" title="Next">Next</button>
                                </div>
                            </div>
                        </div>
                        <!--single form panel-->
                        <div class="multisteps-form__panel shadow p-4 rounded bg-white wizard-height" data-animation="nono" style="height: 450px;">
                            <div class="multisteps-form__content">
                                <div class="form-group">
                                    <label for="slug">Course Slug</label>
                                    <input type="text" class="form-control" id="slug" name="slug" value="{{old('slug')}}" placeholder="Enter Course Slug">
                                </div>
                                <div class="form-group row ">
                                    <label for="photo" class="col-sm-6 col-form-label">Course Photo</label>

                                    <div class="col-sm-11 col-lg-11">
                                        <div class="custom-file mb-1">
                                            <input type="file" class="custom-file-input" id="photo" name="photo"  accept="image/jpeg,image/jpg, image/png">
                                            <label class="custom-file-label" for="coverImage">Choose file...</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-1 col-lg-1">
                                            <img id="tmp_image" class="img-circle" onclick="document.getElementById('avatar').click();" style="width:50px;height: 50px;" src="{{frontend()}}images/tmp.jpg">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom: unset">
                                    <label for="intro">Course Intro</label>
                                    <ul class="list-unstyled list-inline">
                                        <li class="d-inline-block mr-3 ">
                                            <label class="control control-radio">Youtube
                                                <input type="radio"  value="youtube" name="intro">
                                                <div class="control-indicator"></div>
                                            </label>
                                        </li>
                                        <li class="d-inline-block mr-3">
                                            <label class="control control-radio">Local Storage
                                                <input type="radio" value="local" name="intro">
                                                <div class="control-indicator"></div>
                                            </label>

                                        </li>
                                    </ul>
                                </div>

                                <div class="form-group row ">
                                    <label  class="col-sm-6 col-form-label">Course Video</label>
                                    <div class="col-sm-12 col-lg-12 row">
                                            <div class="col-sm-12 col-md-6 ">
                                                <input type="text" class="form-control" id="video_id" value="{{old('video')}}" placeholder="Enter Intro Video Path">
                                            </div>
                                            <div class="col-sm-9 col-md-4 ">

                                            <div class="custom-file mb-1">
                                                <input type="file" class="custom-file-input"  id="video_file" name="video">
                                                <label class="custom-file-label" for="video_file">Choose file...</label>
                                            </div>

                                        </div>
                                        <div class="col-sm-3 col-md-2 ">
                                            <span class="mdi mdi-check-outline mdi-reload mdi-spin text-success mdi-24px" id="ok1"></span>
                                            <span hidden id="label1">  File Uploaded </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="button-row d-flex mt-4 col-12" style="position:relative;bottom: 33px;">
                                        <button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
                                        <button class="btn btn-primary ml-auto js-btn-next" type="button" title="Next">Next</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--single form panel-->
                        <div class="multisteps-form__panel shadow p-4 rounded bg-white wizard-height" data-animation="nono" style="height: auto;">
                            <h3 class="multisteps-form__title">
                                <button class=" btn btn-sm btn-secondary add_form_field" style="margin-bottom: 10px">Add New Field</button>
                            </h3>
                            <div class="multisteps-form__content">


                                <div class="container1">

                                </div>
                                <div class="button-row d-flex mt-4" >
                                    <button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
                                    <button type="submit" class="btn btn-primary btn-default ml-auto">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
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
        @push('scripts')
            <script>
                function readVideo(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function(e) {

                            // $('#tmp_image').attr('src', e.target.result);
                            $('#ok1').removeClass('mdi-reload mdi-spin');
                            $('#label1').attr('hidden',false);
                        }

                        reader.readAsDataURL(input.files[0]); // convert to base64 string
                    }
                }

                $("#video_file").change(function() {

                    readVideo(this);
                });
            </script>
    @endpush
@endsection
