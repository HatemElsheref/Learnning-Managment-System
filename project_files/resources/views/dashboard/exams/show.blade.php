@extends('dashboard.layouts.app')

@section('title') {{$course->name}}  Course Details @endsection

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
                        name:model+"_id[]",
                        value:val
                    }).appendTo(position);
                }) ;

            }
            //for exams start
            let datatable_exam=$('#data-table-exams').DataTable({
                lengthMenu: [ [5,10, 25, 50, -1], [5,10, 25, 50, "All"] ],
                deferRender: false,
                columnDefs: [
                    {orderable: false,targets:[0,7]}
                ]
                ,dom: '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">'
            });

            new $.fn.dataTable.Buttons( datatable_exam, {

                buttons: [
                    @IFACAN('delete_exams')
                    {
                        text:"Delete Selected",
                        className:'btn btn-sm btn-danger ml-2',
                        action: function (e, dt, node, config) {
                            let ids = [];
                            $('.exam-select').each(function(index, value) {
                                if ($(this).is(":checked")) {
                                    ids.push($(this).val());
                                    addInput('exams','#delete-all-selected-check-box-for-exams',$(this).val());
                                }
                            });
                            if (ids.length===0){
                                SweetAlert('please select at least one row');
                            }else{
                                RemoveElement('delete-all-selected-check-box-for-exams');
                            }
                        }
                    }               @ENDACAN
                    @IFACAN('create_exams')
                    ,{
                        text: "Add New Exam",
                        className:"btn btn-sm btn-primary",
                        action: function (e, dt, node, config) {
                            document.location.href='{{route('exams.create',$course->id)}}';
                        }
                    }
                    @ENDACAN
                ]});
            datatable_exam.buttons(0,null).container().appendTo('#exportsControllersExams',datatable_exam.table().container());
            // for exams end
        });

    </script>
@endpush
@section('content')
    <div class="content-wrapper">
        <div class="content">
            <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between __web-inspector-hide-shortcut__">
                <div>
                    <h1>{{ucfirst(strtolower($course->name))}}  Course</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('course.index')}}">Courses</a></li>
                            <li class="breadcrumb-item"><a href="{{route('course.show',$course->id)}}">{{ucfirst(strtolower($course->name))}}</a></li>
                            <li class="breadcrumb-item" aria-current="page">Exams </li>

                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="javascript:0" class="media  btn btn-sm btn-outline-warning" data-toggle="modal" data-target="#course-model-{{$course->id}}">Course Details</a>

                </div>
            </div>
            <div class="row">
                <div class="col-12 ">
                    <div class="card card-default">
                        <div class="card-header card-header-border-bottom d-flex justify-content-between">
                            <h2>
                                <span class="mdi mdi-file-compare"></span> Course Exams
                            </h2>
                            <div id="exportsControllersExams">
                            </div>
                        </div>

                        <div class="card-body">
                            @IFACAN('delete_exams')
                            <form method="post" action="{{route('exams.multi.delete')}}" id="delete-all-selected-check-box-for-exams">
                                @csrf
                                @method('delete')
                            </form>
                                @ENDACAN
                            <table id="data-table-exams" class="table nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            @IFACAN('delete_exams')
                                            <input type="hidden" id="state_exam" value="off">
                                            <input type="checkbox"  class="custom-control-input" id="select-all-exams" onclick="selectOrUnselectAllExams()">
                                            @ENDACAN
                                            <label class="custom-control-label" for="select-all-exams"></label>
                                        </div>
                                    </th>
                                    <th>ID</th>
                                    <th>Exam Type</th>
                                    <th>Term</th>
                                    <th>Year</th>
                                    <th>Download</th>
                                    <th>Global</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($course->files as $file)
                                    <tr>
                                        <td>
                                            <div class='custom-control custom-checkbox mb-3'>
                                                @IFACAN('delete_exams')
                                                <input type='checkbox'  class='custom-control-input exam-select' name='file_id[]' value='{{$file->id}}' id='file-{{$file->id}}'>
                                                @ENDACAN
                                                <label class='custom-control-label' for='file-{{$file->id}}'></label>
                                            </div>
                                            </td>
                                            <td>{{$file->id}}</td>
                                        <td>{{strtoupper($file->type)}}</td>
                                        <td>{{strtoupper($file->term)}}</td>
                                        <td>{{$file->year}}</td>
                                        <td>
                                            @if($file->hosting=='cloud')
{{--                                                <a  href="{{$file->path}}">--}}
                                                <a  href="{{route('download.cloud.exams',[base64_encode($file->path),$course->name.'_'.strtoupper($file->type).'_'.strtoupper($file->term).'_'.$file->year])}}">
                                                    <i class="mdi mdi-download"></i>
                                                </a>
                                                @elseif($file->hosting=='drive')
                                                <a  href="{{$file->path}}" target="_blank">
                                                    <i class="mdi mdi-download"></i>
                                                </a>
                                                Drive File
                                            @else
                                                <a href="{{route('download.local.exams',[$course->id,$file->path,$course->code.'_'.strtoupper($file->type).'_'.strtoupper($file->term).'_'.$file->year])}}">
                                                    <i class="mdi mdi-download"></i>
                                                </a>
                                                @endif
                                        </td>
                                        <td>
                                            @if($file->status=='opened')
                                                <span class="mdi mdi-checkbox-marked-circle text-success mdi-24px"></span>
                                            @else
                                                <span class="mdi mdi-close-circle text-danger mdi-24px"></span>
                                            @endif
                                        </td>
                                        <td>
                                            @IFACAN('delete_exams')
                                            <button class='btn btn-sm btn-outline-danger' onclick="RemoveElement('remove-file-{{$file->id}}')">Remove</button>
                                            @ENDACAN
                                            @IFACAN('update_exams')
                                            <a class='btn btn-sm btn-outline-success' href="{{route('exams.edit',$file->id)}}">Edit</a>
                                            @ENDACAN
                                            @IFACAN('delete_exams')
                                            <form method='post' action='{{route('exams.destroy',$file->id)}}' id='remove-file-{{$file->id}}'>
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




        <div class="modal fade" id="course-model-{{$course->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header justify-content-end border-bottom-0">
                        @IFACAN('update_courses')
                        <a type="button" class="btn-edit-icon" href="{{route('course.edit',$course->id)}}">
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
                                        <video  controls style="height: 200px;border-radius: 15px" id="video-model-{{$course->id}}" >
                                            <source class='rounded-circle w-45' src="{{route('UPLOADED.FILES',['courses_files',$course->video,$course->id,null])}}"  alt="Course Intro Video" >
                                        </video>
                                        <div class="card-body">
                                            <img class='rounded-circle w-45' style='margin-top: 0px;padding-top:0px;height: 45px' src='{{route('UPLOADED.FILES',['courses_files',$course->photo,$course->id,'photos'])}}' alt='course additional  image'>
                                            <h4 class="py-2 text-dark">Course Name
                                                <span class="text-danger"> {{$course->name}}</span>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="contact-info px-4">
                                    <h4 class="text-dark mb-1">Course Details</h4>
                                    <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Name</span> <span >{{$course->name}}</span>
                                    <br>
                                    <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Code</span> <span>{{$course->code}}</span>
                                    <br>
                                    <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Department</span><span>{{$course->department->university->name.' University /'.$course->department->name}} Department</span>
                                    <br>
                                    <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Instructor</span><span>{{$course->instructor->name}}</span>
                                    <br>
                                    <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Slug</span> <span>{{$course->slug}}</span>
                                    <br>
                                    <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Price</span> <span >{{($course->price==0)?'Free':$course->price.' '.DefaultCurrency()}}</span>
                                    <p class="text-dark font-weight-medium pt-4 mb-2">
                                        <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Description</p>
                                    <span>{!! $course->description !!}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>




@endsection
