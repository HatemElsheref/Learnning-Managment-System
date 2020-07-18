@extends('dashboard.layouts.app')
@section('title')
    Edit Exam
@endsection
@push('scripts')
    <script>
        $("input[name='hosting']").change(function () {
            if ($(this).val()==='local'){
                $('#exam_file').attr('name','exam').prop('disabled',false);
                $('#exam_link').prop('disabled',true).removeAttr('name');
                $('#exam_drive').prop('disabled',true).removeAttr('name');
            }else if($(this).val()==='drive'){
                $('#exam_drive').attr('name','exam').prop('disabled',false);
                $('#exam_file').prop('disabled',true).removeAttr('name');
                $('#exam_link').prop('disabled',true).removeAttr('name');
            }
            else{
                $('#exam_link').attr('name','exam').prop('disabled',false);
                $('#exam_file').prop('disabled',true).removeAttr('name');
                $('#exam_drive').prop('disabled',true).removeAttr('name');
            }

        });
    </script>
    <script>
        $('#status').click(function() {
            if ($(this).is(':checked')) {
                $('#users').prop('disabled',true) ;
            } else{
                $('#users').prop('disabled',false) ;
            }
        });
    </script>
@endpush
@section('content')

    <div class="content-wrapper">
        <div class="content">
            <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between __web-inspector-hide-shortcut__">
                <div>
                    <h1>Exams</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('course.index')}}">Courses</a></li>
                            <li class="breadcrumb-item"><a href="{{route('course.show',$courseFile->course->id)}}">{{ucfirst(strtolower($courseFile->course->name))}}</a></li>
                            <li class="breadcrumb-item"><a href="{{route('exams.show',$courseFile->course->id)}}">Exams</a></li>
                            <li class="breadcrumb-item" aria-current="page">Edit Exam</li>
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
                            <form action="{{route('exams.update',$courseFile->id)}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <label for="exam_type">Exam Type</label>
                                    <select class="form-control" id="exam_type" name="exam_type" >
                                        <option selected disabled>select Exam</option>
                                        @foreach(getExamsType() as $exam)
                                            <option value="{{strtolower($exam)}}"  @if($courseFile->type == strtolower($exam))  selected @endif>
                                                {{strtoupper($exam)}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="term">Term</label>
                                    <select class="form-control" id="term" name="term" >
                                        <option selected disabled>select Exam</option>
                                        @foreach(getTerms() as $term)
                                            <option value="{{strtolower($term)}}"  @if($courseFile->term == strtolower($term))  selected @endif>
                                                {{strtoupper($term)}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="year">Year</label>
                                    <select class="form-control" id="year" name="year" >
                                        <option selected disabled>select Year</option>
                                        @foreach(getYears() as $year)
                                            <option value="{{$year}}"   @if($courseFile->year == strtolower($year))  selected @endif>
                                                {{$year}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
{{--                                <div class="form-group row ">--}}
{{--                                    <label for="exam" class="col-sm-4 col-lg-2 col-form-label">Lesson Attached File</label>--}}
{{--                                    <div class="col-sm-12 col-lg-12">--}}
{{--                                        <div class="custom-file mb-1">--}}
{{--                                            <input type="file" class="custom-file-input" id="exam" name="exam">--}}
{{--                                            <label class="custom-file-label" for="exam">Choose file...</label>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                --}}
                                <div class="form-group" style="margin-bottom: unset">
                                    <label for="hosting">File Hosting</label>
                                    <ul class="list-unstyled list-inline">
                                        <li class="d-inline-block mr-3 ">
                                            <label class="control control-radio">Cloud Storage
                                                <input type="radio"  value="cloud" name="hosting" @if('cloud'==$courseFile->hosting) checked @endif>
                                                <div class="control-indicator"></div>
                                            </label>
                                        </li>
                                        <li class="d-inline-block mr-3 ">
                                            <label class="control control-radio"> Drive
                                                <input type="radio"  id="drive-btn"  value="drive" name="hosting" @if('drive'==$courseFile->hosting) checked @endif>
                                                <div class="control-indicator"></div>
                                            </label>
                                        </li>
                                        <li class="d-inline-block mr-3">
                                            <label class="control control-radio">Local Storage
                                                <input type="radio" value="local" name="hosting" @if('local'==$courseFile->hosting) checked @endif>
                                                <div class="control-indicator"></div>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                                <div class="form-group row ">
                                    <label  class="col-sm-6 col-form-label">Lesson Attached File</label>
                                    <div class="col-sm-12 col-lg-12 row">
                                        <div class="col-sm-12 col-md-4 ">
                                            <input type="text" class="form-control" id="exam_link"  placeholder="Enter File  Path" @if('local'==$courseFile->hosting or 'drive'==$courseFile->hosting ) disabled value="" @else name="exam" value="{{$courseFile->path}}" @endif>
                                        </div>
                                        <div class="col-sm-12 col-md-4 ">
                                            <input type="text" class="form-control" id="exam_drive" name="exam"  placeholder="Enter Drive Url" @if('local'==$courseFile->hosting  or  'cloud'==$courseFile->hosting) disabled value="" @else name="exam" value="{{$courseFile->path}}" @endif>
                                        </div>
                                        <div class="col-sm-12 col-md-4 ">
                                            <div class="custom-file mb-1">
                                                <input type="file" class="custom-file-input"  id="exam_file"  @if('cloud'==$courseFile->hosting or 'drive'==$courseFile->hosting) disabled value="" @else name="exam" value="{{$courseFile->path}}" @endif>
                                                <label class="custom-file-label" for="exam_file">Choose file...</label>
                                            </div>
                                            <span class="mdi mdi-check-outline mdi-reload mdi-spin text-success mdi-24px" id="ok"></span>
                                            <span hidden id="label">  File Uploaded </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <ul class="list-unstyled list-inline">
                                        <li class="d-inline-block mr-3">
                                            <label class="control control-checkbox">Global <small class="text-danger">allow to all</small>
                                                <input type="checkbox" id="status" name="status" value="opened" @if($courseFile->status=='opened') checked @endif>
                                                <div class="control-indicator"></div>
                                            </label>
                                        </li>
                                    </ul>
                                </div>


                                <div class="form-group">
                                    <label for="users">Allowed To Users</label>
                                    <div class="form-group" >
                                        <select class=" js-example-basic-multiple form-control " name="users[]" multiple id="users" @if($courseFile->status=='opened') disabled @endif>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}" @if(in_array($user->id,(array)json_decode($courseFile->shared))) selected @endif>{{$user->email}}</option>
                                            @endforeach
                                        </select>
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

                $("#exam_file").change(function() {

                    readURL(this);
                });
            </script>
    @endpush
@endsection


