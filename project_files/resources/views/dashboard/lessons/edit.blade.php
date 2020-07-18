
 @extends('dashboard.layouts.app')
        @section('title')
            Edit Lesson  {{$lesson->part->course->name}}  Course
        @endsection
 @push('scripts')
     <script>
         $("input[name='hosting']").change(function () {
             if ($(this).val()==='local'){
                 $('#video_file').attr('name','video').prop('disabled',false);
                 $('#video_id').prop('disabled',true).removeAttr('name');
                 $('#video_link').prop('disabled',true).removeAttr('name');
                 $('#video_drive').prop('disabled',true).removeAttr('name');

             }else if($(this).val()==='youtube'){
                 $('#video_id').attr('name','video').prop('disabled',false);
                 $('#video_file').prop('disabled',true).removeAttr('name');
                 $('#video_link').prop('disabled',true).removeAttr('name');
                 $('#video_drive').prop('disabled',true).removeAttr('name');

             }
             else if($(this).val()==='drive'){
                 $('#video_drive').attr('name','video').prop('disabled',false);
                 $('#video_file').prop('disabled',true).removeAttr('name');
                 $('#video_link').prop('disabled',true).removeAttr('name');
                 $('#video_id').prop('disabled',true).removeAttr('name');

             }else{
                 $('#video_link').attr('name','video').prop('disabled',false);
                 $('#video_file').prop('disabled',true).removeAttr('name');
                 $('#video_id').prop('disabled',true).removeAttr('name');
                 $('#video_drive').prop('disabled',true).removeAttr('name');

             }

         });
     </script>
 @endpush
        @section('content')

            <div class="content-wrapper">
                <div class="content">
                    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between __web-inspector-hide-shortcut__">
                        <div>
                            <h1>Lessons</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb p-0 breadcrumb-inverse">
                                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('course.index')}}">Courses</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('course.show',$lesson->part->course->id)}}">{{ucfirst(strtolower($lesson->part->course->name))}}</a></li>
                                    <li class="breadcrumb-item" aria-current="page">Edit Lesson</li>
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
                                    <form action="{{route('lesson.update',$lesson->id)}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        @method('put')
                                        <input type="hidden" name="course_id" value="{{$lesson->part->course->id}}">
                                        <div class="form-group">
                                            <label for="part">Part</label>
                                            <select class="form-control" id="part" name="part_id" >
                                                <option selected disabled>select Part</option>
                                                @foreach($parts as $part)
                                                    <option value="{{$part->id}}" @if($lesson->part_id== $part->id)  selected @endif>
                                                        {{$part->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="name">Lesson Name</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{$lesson->name}}" placeholder="Enter Lesson Name">
                                        </div>



{{--                                        @if($lesson->part->course->hosting=='local')--}}
{{--                                            <div class="form-group row "  id="local-video">--}}
{{--                                                <label for="local" class="col-sm-4 col-lg-2 col-form-label">Lesson Video</label>--}}
{{--                                                <div class="col-sm-12 col-lg-12">--}}
{{--                                                    <div class="custom-file mb-1">--}}
{{--                                                        <input type="file" class="custom-file-input" id="local" name="local">--}}
{{--                                                        <label class="custom-file-label" for="local">Choose file...</label>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        @else--}}
{{--                                            <div class="form-group" id="link-video">--}}
{{--                                                <label for="link">Lesson Video</label>--}}
{{--                                                <input type="text" class="form-control" id="link" name="link" value="{{$lesson->video}}" placeholder="Enter Video Path">--}}
{{--                                            </div>--}}
{{--                                        @endif--}}

{{--                                        <div class="form-group row ">--}}
{{--                                            <label for="file" class="col-sm-4 col-lg-2 col-form-label">Lesson Attached File</label>--}}
{{--                                            <div class="col-sm-12 col-lg-12">--}}
{{--                                                <div class="custom-file mb-1">--}}
{{--                                                    <input type="file" class="custom-file-input" id="file" name="file">--}}
{{--                                                    <label class="custom-file-label" for="file">Choose file...</label>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                        <div class="form-group" style="margin-bottom: unset">
                                            <label for="hosting">Course Intro</label>
                                            <ul class="list-unstyled list-inline">
                                                <li class="d-inline-block mr-3 ">
                                                    <label class="control control-radio">Youtube
                                                        <input type="radio"  value="youtube" name="hosting" @if($lesson->type=='youtube') checked @endif >
                                                        <div class="control-indicator"></div>
                                                    </label>
                                                </li>
                                                <li class="d-inline-block mr-3 ">
                                                    <label class="control control-radio">Cloud Storage
                                                        <input type="radio"  value="cloud" name="hosting" @if($lesson->type=='cloud') checked @endif>
                                                        <div class="control-indicator"></div>
                                                    </label>
                                                </li>
                                                <li class="d-inline-block mr-3 ">
                                                    <label class="control control-radio"> Drive
                                                        <input type="radio"  value="drive" name="hosting" @if($lesson->type=='drive') checked @endif>
                                                        <div class="control-indicator"></div>
                                                    </label>
                                                </li>
                                                <li class="d-inline-block mr-3">
                                                    <label class="control control-radio">Local Storage
                                                        <input type="radio" value="local" name="hosting" @if($lesson->type=='local') checked @endif>
                                                        <div class="control-indicator"></div>
                                                    </label>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="form-group row ">
                                            <label  class="col-sm-6 col-form-label">Lesson Hosting</label>
                                            <div class="col-sm-12 col-lg-12 row">
                                                <div class="col-sm-12 col-md-3 ">
                                                    <input type="text" class="form-control" id="video_id" placeholder="Enter Intro Video Path" @if($lesson->type=='cloud' or $lesson->type=='local' or $lesson->type=='drive') disabled  value="" @else  name="video" value="{{$lesson->video}}"  @endif>
                                                </div>
                                                <div class="col-sm-12 col-md-3 ">
                                                    <input type="text" class="form-control" id="video_link"  placeholder="Enter Intro Video Path" @if($lesson->type=='youtube' or $lesson->type=='local' or $lesson->type=='drive') disabled  value="" @else name="video" value="{{$lesson->video}}" @endif>
                                                </div>
                                                <div class="col-sm-12 col-md-3 ">
                                                    <input type="text" class="form-control" id="video_drive"  placeholder="Enter Drive Url" @if($lesson->type=='youtube' or $lesson->type=='local' or $lesson->type=='cloud') disabled  value="" @else name="video" value="{{$lesson->video}}" @endif>
                                                </div>
                                                <div class="col-sm-12 col-md-3 ">

                                                    <div class="custom-file mb-1">
                                                        <input type="file" class="custom-file-input"  id="video_file" @if($lesson->type=='cloud' or $lesson->type=='youtube' or $lesson->type=='drive') disabled  @else  name="video"  @endif>
                                                        <label class="custom-file-label" for="video_file">Choose file...</label>
                                                    </div>
                                                    <span class="mdi mdi-check-outline mdi-reload mdi-spin text-success mdi-24px" id="ok"></span>
                                                    <span hidden id="label">  File Uploaded </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <div class="form-check ">
                                                <input id="status-5" class="checkbox-custom form-check-input" name="status" value="published" type="checkbox" @if($lesson->status) checked @endif>
                                                <label for="status-5" class="checkbox-custom-label form-check-label disable-checked">Is Free</label>
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

                                    // $('#tmp_image').attr('src', e.target.result);
                                    $('#ok').removeClass('mdi-reload mdi-spin');
                                    $('#label').attr('hidden',false);
                                }

                                reader.readAsDataURL(input.files[0]); // convert to base64 string
                            }
                        }

                        $("#video_file").change(function() {

                            readURL(this);
                        });
                    </script>
            @endpush
@endsection


