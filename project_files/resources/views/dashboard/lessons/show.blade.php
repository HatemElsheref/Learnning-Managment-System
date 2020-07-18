@extends('dashboard.layouts.app')

@section('title') {{$lesson->name}}   Details @endsection
@push('scripts')

    <script src="{{Dashboard_Assets()}}/plugins/data-tables/jquery.datatables.min.js"></script>
    <script src="{{Dashboard_Assets()}}/plugins/data-tables/datatables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script>
        jQuery(document).ready(function() {
            function addInput(model,position,val) {
                $(document).ready(function() {
                    $("<input>").attr({
                        type:"hidden",
                        // name:"lessons_id[]",
                        name:model+"_id[]",
                        value:val
                        // }).appendTo("#delete-all-selected-check-box");
                    }).appendTo(position);
                }) ;

            }
            //for photo start
            let datatable_photo=$('#data-table-photo').DataTable({
                lengthMenu: [ [5,10, 25, 50, -1], [5,10, 25, 50, "All"] ],
                deferRender: false,
                columnDefs: [
                    {orderable: false,targets:[0,4,5]}
                ]
                ,dom: '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">'
            });

            new $.fn.dataTable.Buttons( datatable_photo, {

                buttons: [
                    @IFACAN('delete_files')
                    {
                        text:"Delete Selected",
                        className:'btn btn-sm btn-danger ml-2',
                        action: function (e, dt, node, config) {
                            let ids = [];
                            $('.photo-select').each(function(index, value) {
                                if ($(this).is(":checked")) {
                                    ids.push($(this).val());
                                    addInput('lesson_file','#delete-all-selected-check-box-for-files',$(this).val());
                                }
                            });
                            if (ids.length===0){
                                SweetAlert('please select at least one row');
                            }else{
                                RemoveElement('delete-all-selected-check-box-for-files');
                            }
                        }
                    }
                    @ENDACAN
                    @IFACAN('create_files')
                    ,{
                        text: "Upload New File",
                        className:"btn btn-sm btn-info",
                        action: function (e, dt, node, config) {
                                     coursePartModalForStore();
                        }
                    }
                    @ENDACAN
                ]});
            datatable_photo.buttons(0,null).container().appendTo('#exportsControllersPhotos',datatable_photo.table().container());
            // for photo end
        });

    </script>
    <script>
        function coursePartModalForStore() {
            $('#lesson-file-operation-title').html('Add New Attached File');
            let route='{{route('files.store')}}';
            $('#upload-lesson-file-form').attr('action',route);
            $('#file_method_type').val('post');
            $('#file_name').val('');
            $('#status-5').prop('checked',false);
            $('#cloud-btn').prop('checked',false);
            $('#local-btn').prop('checked',false);
            $('#file_link').val('').removeAttr('name').prop('disabled',true);    //link refer to cloud and file refer to browse
            $('#file_file').val('').removeAttr('name').prop('disabled',true);    //link refer to cloud and file refer to browse
            $('#ok').addClass('mdi-reload mdi-spin');
            $('#label').attr('hidden',true);

            $('#lesson-file').modal('show');
        }
        function coursePartModalForUpdate(id,name,status,hosting,path) {
            $('#lesson-file-operation-title').html('Edit Attached File');
            let route='{{url('/')}}'+'/'+'{{app()->getLocale()}}'+'/dashboard/lesson/files/'+id;
            $('#upload-lesson-file-form').attr('action',route);
            $('#file_name').val(name);
            $('#file_method_type').val('put');
            $('#ok').addClass('mdi-reload mdi-spin');
            $('#label').attr('hidden',true);
            $('#file_file').val('');
            if (status==1){
                $('#status-5').prop('checked',true);
            } else{
                $('#status-5').prop('checked',false);
            }
            if (hosting==='local'){
                $('#cloud-btn').prop('checked',false);
                $('#drive-btn').prop('checked',false);
                $('#local-btn').prop('checked',true);
                $('#file_file').attr('name','file').prop('disabled',false);
                $('#file_link').prop('disabled',true).removeAttr('name').val('');
            }   else if(hosting==='drive'){
                $('#drive-btn').prop('checked',true);
                $('#cloud-btn').prop('checked',false);
                $('#local-btn').prop('checked',false);
                $('#file_link').val(path).attr('name','file').prop('disabled',false);    //link refer to cloud and file refer to browse
                $('#file_file').prop('disabled',true).removeAttr('name');
            }
        else{
                $('#cloud-btn').prop('checked',true);
                $('#local-btn').prop('checked',false);
                $('#drive-btn').prop('checked',false);
                $('#file_link').val(path).attr('name','file').prop('disabled',false);    //link refer to cloud and file refer to browse
                $('#file_file').prop('disabled',true).removeAttr('name');
            }

            $('#lesson-file').modal('show');
        }

    </script>
    <script>
        $("input[name='hosting']").change(function () {
            if ($(this).val()==='local'){
                $('#file_file').attr('name','file').prop('disabled',false);
                $('#file_link').prop('disabled',true).removeAttr('name');
            }else{
                $('#file_link').attr('name','file').prop('disabled',false);    //link refer to cloud and file refer to browse
                $('#file_file').prop('disabled',true).removeAttr('name');
            }

        });
    </script>
@endpush
@section('content')
    <div class="content-wrapper">
        <div class="content">
            <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between __web-inspector-hide-shortcut__">
                <div>
                    <h1>
                        {{ucfirst(strtolower($lesson->part->course->name))}}
                        <span class="mdi mdi-arrow-right-circle text-primary"></span>
                        {{$lesson->name}} </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('course.index')}}">Courses</a></li>
                            <li class="breadcrumb-item"><a href="{{route('course.show',$lesson->part->course->id)}}">            {{ucfirst(strtolower($lesson->part->course->name))}} </a></li>
                            <li class="breadcrumb-item" aria-current="page">{{$lesson->name}} </li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="javascript:0" class="media  btn btn-sm btn-outline-warning" data-toggle="modal" data-target="#lesson-model-{{$lesson->id}}">Lesson Details</a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    @include('dashboard.layouts.validation_error')
                </div>
            </div>
            <div class="row">
                <div class="col-12 ">
                    <div class="card card-default">
                        <div class="card-header card-header-border-bottom d-flex justify-content-between">
                            <h2>
                                <span class="mdi mdi-file"></span> Lesson Attached Files Table
                            </h2>
                            <div id="exportsControllersPhotos">
                            </div>
                        </div>

                        <div class="card-body">
                            @IFACAN('delete_files')
                            <form method="post" action="{{route('lesson.file.multi.delete')}}" id="delete-all-selected-check-box-for-files">
                                @csrf
                                @method('delete')
                            </form>
                            @ENDACAN
                            <table id="data-table-photo" class="table nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    <th>
                                        <div class="custom-control custom-checkbox ">
                                            @IFACAN('delete_files')
                                            <input type="hidden" id="state_photo" value="off">
                                            <input type="checkbox"  class="custom-control-input" id="select-all-photos" onclick="selectOrUnselectAllPhotos()">
                                            @ENDACAN
                                            <label class="custom-control-label" for="select-all-photos"></label>
                                        </div>
                                    </th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>isFree</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($lesson->files as $file)
                                    <tr>
                                        <td>
                                            <div class='custom-control custom-checkbox mb-3'>
                                                @IFACAN('delete_files')
                                                <input type='checkbox'  class='custom-control-input photo-select' name='file_id[]' value='{{$file->id}}' id='file-{{$file->id}}'>
                                                @ENDACAN
                                                <label class='custom-control-label' for='file-{{$file->id}}'></label>
                                            </div>
                                        </td>
                                        <td>{{$file->id}}</td>
                                        <td>{{$file->name}}</td>
                                        <td>
                                            @if($file->hosting=='cloud')
                                                <a href="{{route('download.cloud.attached.files',[base64_encode($file->path),$file->name])}}" >
                                                    <i class="mdi mdi-download"></i>
                                                </a>
                                                {{strtoupper($file->type)}}
                                                @elseif($file->hosting=='drive')
                                                <a href="{{$file->path}}"  target="_blank">
                                                    <i class="mdi mdi-download"></i>
                                                </a>
                                                Drive File
                                                @else
                                                <a href="{{route('download.local.attached.files',[$lesson->part->course->id,$file->path,$file->name])}}" >
                                                    <i class="mdi mdi-download"></i>
                                                </a>
{{--                                                {{strtoupper(getMimeType($file->type))}}--}}
                                                {{strtoupper(explode('.',$file->path)[1])}}
                                            @endif

                                        </td>
                                        <td>
                                            @if($file->isFree)
                                                <span class="mdi mdi-checkbox-marked-circle text-success mdi-24px"></span>
                                            @else
                                                <span class="mdi mdi-close-circle text-danger mdi-24px"></span>
                                            @endif
                                        </td>
                                        <td>
                                            @IFACAN('delete_files')
                                            <button class='btn btn-sm btn-outline-danger' onclick="RemoveElement('remove-file-{{$file->id}}')">Remove</button>
                                            @ENDACAN
                                            @IFACAN('update_files')
                                            <button class='btn btn-sm btn-outline-success' onclick="coursePartModalForUpdate('{{$file->id}}','{{$file->name}}','{{$file->isFree}}','{{$file->hosting}}','{{$file->path}}')">Edit</button>
                                            @ENDACAN
                                            @IFACAN('delete_files')
                                            <form method='post' action='{{route('files.destroy',$file->id)}}' id='remove-file-{{$file->id}}'>
                                                @csrf
                                                @method('delete')
                                            </form>
                                            @ENDACAN
                                        </td>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <div class="modal fade" id="lesson-model-{{$lesson->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header justify-content-end border-bottom-0">
                        @IFACAN('update_lessons')
                        <a type="button" class="btn-edit-icon" href="{{route('lesson.edit',$lesson->id)}}">
                            <i class="mdi mdi-pencil"></i>
                        </a>
                        @ENDACAN
                        <button type="button" class="btn-close-icon" data-dismiss="modal" aria-label="Close" >
                            <i class="mdi mdi-close" onclick="closeModel()"></i>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="row no-gutters">
                            <div class="col-md-6">
                                <div class="profile-content-left px-4">
                                    <div class="card text-center widget-profile px-0 border-0">

{{--                                            @if(YoutubeLessons())--}}
                                            @if($lesson->type=='youtube')
                                                <iframe style="height: 200px;border-radius: 15px"  src="https://www.youtube.com/embed/{{$lesson->video}}?controls=0" frameborder="0" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        @elseif($lesson->type=='cloud')
                                            <video  controls style="height: 200px;border-radius: 15px" id="video-model-{{$lesson->part->course->id}}" >
                                            <source class='rounded-circle w-45' src="{{$lesson->video}}"  alt="Course Lesson Video" >
                                            </video>
                                        @else
                                                <video  controls style="height: 200px;border-radius: 15px" id="video-model-{{$lesson->part->course->id}}" >
                                                    <source class='rounded-circle w-45' src="{{route('UPLOADED.FILES',['courses_files',$lesson->video,$lesson->part->course->id,null])}}"  alt="Course Lesson Video" >
                                                </video>
                                            @endif
                                        <div class="card-body">
                                            <h4 class="py-2 text-dark">Lesson Name
                                                <span class="text-danger"> {{$lesson->name}}</span>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="contact-info px-4">
                                    <h4 class="text-dark mb-1">Lesson Details</h4>
                                    <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Name </span> <span > {{$lesson->name}}</span>
                                    <br>
                                    <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Part </span> <span> {{$lesson->part->name}}</span>
                                    <br>
                                    <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Course </span><span> {{$lesson->part->course->name}}</span>
                                    <br>
                                    <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Instructor </span><span> {{$lesson->part->course->instructor->name}}</span>
                                    <br>
                                    <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Files </span> <span> {{count($lesson->files)}}</span>
                                    <br>
                                    <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Status </span> <span > {{($lesson->status)?'Published':'Pending'}}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade " id="lesson-file" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormTitle" aria-modal="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="lesson-file-operation-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form method="post" id="upload-lesson-file-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" id="file_method_type" value="post">
                            <div class="form-group">
                                <input type="hidden" name="lesson_id" value="{{$lesson->id}}">
                                <label for="file_name">File Name</label>
                                <input type="text" name="file_name" class="form-control" id="file_name" a placeholder="Enter File Name">
                            </div>
{{--                            <div class="form-group row ">--}}
{{--                                <label for="file" class="col-sm-12  col-form-label">Lesson Attached File</label>--}}
{{--                                <div class="col-sm-12 col-lg-12">--}}
{{--                                    <div class="custom-file mb-1">--}}
{{--                                        <input type="file" class="custom-file-input" id="file" name="file">--}}
{{--                                        <label class="custom-file-label" for="file">Choose file...</label>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="form-group" style="margin-bottom: unset">
                                <label for="hosting">File Hosting</label>
                                <ul class="list-unstyled list-inline">
                                    <li class="d-inline-block mr-3 ">
                                        <label class="control control-radio">Cloud Storage
                                            <input type="radio"  value="cloud" name="hosting" id="cloud-btn">
                                            <div class="control-indicator"></div>
                                        </label>
                                    </li>
                                    <li class="d-inline-block mr-3 ">
                                        <label class="control control-radio"> Drive
                                            <input type="radio"  id="drive-btn"  value="drive" name="hosting" @if($lesson->type=='drive') checked @endif>
                                            <div class="control-indicator"></div>
                                        </label>
                                    </li>
                                    <li class="d-inline-block mr-3">
                                        <label class="control control-radio">Local Storage
                                            <input type="radio" value="local" name="hosting" id="local-btn">
                                            <div class="control-indicator"></div>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                            <div class="form-group row ">
                                <label  class="col-sm-6 col-form-label">Lesson Attached File</label>
                                <div class="col-sm-12 col-lg-12 row">
                                    <div class="col-sm-12 col-md-12 mb-2">
                                        <input type="text" class="form-control" id="file_link" value="{{old('file')}}" placeholder="Enter File  Path">
                                    </div>
                                    <div class="col-sm-12 col-md-12">
                                        <div class="custom-file mb-1">
                                            <input type="file" class="custom-file-input"  id="file_file" name="file">
                                            <label class="custom-file-label" for="file_file">Choose file...</label>
                                        </div>
                                        <span class="mdi mdi-check-outline mdi-reload mdi-spin text-success mdi-24px" id="ok"></span>
                                        <span hidden id="label">  File Uploaded </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="form-check ">
                                    <input id="status-5" class="checkbox-custom form-check-input"  name="status" value="published" type="checkbox" @if(old('status')) checked @endif>
                                    <label for="status-5" class="checkbox-custom-label form-check-label disable-checked">Is Free</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
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

                $("#file_file").change(function() {

                    readURL(this);
                });
            </script>
    @endpush

@endsection
