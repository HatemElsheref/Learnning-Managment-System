@extends('dashboard.layouts.app')
@section('title')
    Add New Feedback
@endsection

@push('scripts')
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
    @endpush
@section('content')
    <div class="content-wrapper">
        <div class="content">
            <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between __web-inspector-hide-shortcut__">
                <div>
                    <h1>Feedback</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('feedback.index')}}">Feedback</a></li>
                            <li class="breadcrumb-item" aria-current="page">Add New Feedback</li>
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
                            <form action="{{route('feedback.store')}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}" placeholder="Enter  Name">
                                </div>
                                <div class="form-group">
                                    <label for="universities">Countries</label>
                                    <select class="form-control"  name="country_id" >
                                        <option selected disabled>select Country</option>
                                        @foreach($countries as $country)
                                            <option value="{{$country->id}}" @if(old('country_id')==$country->id) selected @endif>
                                                {{$country->Name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="universities">University</label>
                                    <select class="form-control" id="universities" name="university_id" >
                                        <option selected >select University</option>
                                        @foreach($universities as $university)
                                            <option value="{{$university->id}}" @if(old('university_id')) selected @endif>
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
                                <div class="form-group">
                                    <ul class="list-unstyled list-inline">
                                        <li class="d-inline-block">
                                            <label class="control control-radio">Image
                                                <input type="radio" value="image" name="type" @if(old('type')=='image') checked @endif>
                                                <div class="control-indicator"></div>
                                            </label>
                                        </li>
{{--                                        <li class="d-inline-block mr-3 ">--}}
{{--                                            <label class="control control-radio">Audio--}}
{{--                                                <input type="radio"  value="audio" name="type"  @if(old('type')=='audio') checked @endif>--}}
{{--                                                <div class="control-indicator"></div>--}}
{{--                                            </label>--}}
{{--                                        </li>--}}
                                        <li class="d-inline-block mr-3">
                                            <label class="control control-radio">Video
                                                <input type="radio" value="video" name="type" @if(old('type')=='video') checked @endif>
                                                <div class="control-indicator"></div>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                                <div class="form-group row " >
                                    <label for="feedback" class="col-sm-4 col-lg-2 col-form-label">Feedback Source</label>
                                    <div class="col-sm-11 col-lg-11">
                                        <div class="custom-file mb-1">
                                            <input type="file" class="custom-file-input" id="feedback" name="feedback">
                                            <label class="custom-file-label" for="local">Choose file...</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-1 col-lg-1"  >
                                        <span class="mdi mdi-check-outline mdi-reload mdi-spin text-success mdi-24px" id="ok"></span>
                                     <span hidden id="label">  File Uploaded </span>
                                    </div>

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

                            // $('#tmp_image').attr('src', e.target.result);
                            $('#ok').removeClass('mdi-reload mdi-spin');
                            $('#label').attr('hidden',false);
                        }

                        reader.readAsDataURL(input.files[0]); // convert to base64 string
                    }
                }

                $("#feedback").change(function() {
                    readURL(this);
                });
            </script>
    @endpush
@endsection


