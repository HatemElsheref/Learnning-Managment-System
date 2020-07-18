@extends('dashboard.layouts.app')

@section('title') All Courses Of {{$courses[0]->instructor->name }} @endsection
@push('scripts')
    <script src="{{Dashboard_Assets()}}/plugins/data-tables/jquery.datatables.min.js"
            xmlns="http://www.w3.org/1999/html"></script>
    <script src="{{Dashboard_Assets()}}/plugins/data-tables/datatables.bootstrap4.min.js"></script>
    @if(ReportsFeaturesMode())
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js "></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js "></script>
    @endif
    <script>
        @if(!ReportsFeaturesMode())
        jQuery(document).ready(function() {
            $('#data-table').DataTable({
                lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                deferRender: false,
                columnDefs: [
                    {orderable: false, targets: [0,9]}
                ]
                ,dom: '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">'
            });
        });
        @endif

        @if(ReportsFeaturesMode())
        jQuery(document).ready(function() {
            function addInput(val) {
                $(document).ready(function() {
                    $("<input>").attr({
                        type:"hidden",
                        name:"courses_id[]",
                        value:val
                    }).appendTo("#delete-all-selected-check-box");
                }) ;

            }
            let datatable=$('#data-table').DataTable({
                lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                deferRender: false,
                columnDefs: [
                    // {orderable: false, targets: [0,1,10]}
                    {orderable: false, targets: [0,1,9]}
                ]
                // ,dom: '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">'
            });

            new $.fn.dataTable.Buttons( datatable, {

                buttons: [
                    {
                        extend: 'print',
                        className: 'btn btn-sm btn-secondary ml-2',
                        exportOptions: {
                            // columns: [  2, 3,4,5,6,7,8,9]
                            columns: [  2, 3,4,5,6,7,8]
                        }
                    }
                    ,{
                        extend: 'excelHtml5',
                        className:'btn btn-sm btn-warning ml-2',
                        exportOptions: {
                            // columns: [  2, 3,4,5,6,7,8,9]
                            columns: [  2, 3,4,5,6,7,8]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        alignment: 'center',
                        className:'btn btn-sm btn-danger ml-2',
                        exportOptions: {
                            // columns: [ 2, 3,4,5,6,7,8,9]
                            columns: [ 2, 3,4,5,6,7,8]
                        }
                    }
                    , {
                        extend: 'csv',
                        className:'btn btn-sm btn-success ml-2',
                        exportOptions: {
                            // columns: [2, 3,4,5,6,7,8,9]
                            columns: [2, 3,4,5,6,7,8]
                        }
                    }
                    @IFACAN('delete_courses')
                    , {
                        text:"Delete Selected",
                        className:'btn btn-sm btn-danger ml-2',
                        action: function (e, dt, node, config) {
                            let ids = [];
                            $('.user-select').each(function(index, value) {
                                if ($(this).is(":checked")) {
                                    ids.push($(this).val());
                                    addInput($(this).val());
                                }
                            });
                            if (ids.length===0){
                                SweetAlert('please select at least one row');
                            }else{
                                RemoveElement('delete-all-selected-check-box');
                            }
                        }
                    } @ENDACAN
                ]});
            datatable.buttons(0,null).container().appendTo('#exportsControllers',datatable.table().container());

        });
        @endif
    </script>
@endpush
@section('content')
    <div class="content-wrapper">
        <div class="content">
            @include('dashboard.layouts.validation_error')
            <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between __web-inspector-hide-shortcut__">
                <div>
                    <h1>{{$courses[0]->instructor->name }}  Courses</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('instructor.index')}}">Instructor</a></li>
                            <li class="breadcrumb-item" aria-current="page">Courses</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    @IFACAN('create_courses')
                    <a class="btn btn-sm btn-primary" href="{{route('course.create')}}">Add New Course</a>
                    @ENDACAN
                </div>
            </div>

            <div class="row">
                <div class="col-12 ">
                    <div class="card card-default">
                        <div class="card-header card-header-border-bottom d-flex justify-content-between">
                            <h2>
                                <span class="mdi mdi mdi-play-circle"></span> Courses Table
                            </h2>
                            <div id="exportsControllers"> </div>
                        </div>

                        <div class="card-body">
                            @IFACAN('delete_courses')
                            <form method="post" action="{{route('course.multi.delete')}}" id="delete-all-selected-check-box">
                                @csrf
                                @method('delete')
                            </form>
                            @ENDACAN
                            <table id="data-table" class="table nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    @if(ReportsFeaturesMode())
                                        <th>
                                            <div class="custom-control custom-checkbox ">
                                                @IFACAN('delete_courses')
                                                <input type="hidden" id="state" value="off">
                                                <input type="checkbox"  class="custom-control-input" id="select-all-rows" onclick="selectOrUnselectAll()">
                                                @ENDACAN
                                                <label class="custom-control-label" for="select-all-rows"></label>
                                            </div>
                                        </th>
                                    @endif
                                    <th class="not-sortable">Photo</th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Price</th>
                                    <th>University</th>
                                    <th>Dep</th>
{{--                                    <th>Instructor</th>--}}
                                    <th>#</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($courses as $item)
                                    <tr>
                                        @if(ReportsFeaturesMode())
                                            <td>
                                                <div class='custom-control custom-checkbox mb-3'>
                                                    @IFACAN('delete_courses')
                                                    <input type='checkbox'  class='custom-control-input user-select' name='courses[]' value='{{$item->id}}' id='category-{{$item->id}}'>
                                                    @ENDACAN
                                                    <label class='custom-control-label' for='category-{{$item->id}}'></label>

                                                </div>
                                            </td>
                                        @endif
                                        <td>
                                            <img class='rounded-circle w-45' style='margin-top: 0px;padding-top:0px;height: 45px' src='{{ROUTE('UPLOADED.FILES',['courses_files',$item->photo,$item->id,'photos'])}}' alt='course image'>
                                        </td>
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->code}}</td>
                                        <td>
                                            @if($item->price==0)
                                                <span class="badge badge-success">Free</span>
                                            @else
                                                <span class="badge badge-warning">{{$item->price.' '.DefaultCurrency()}}</span>
                                            @endif

                                        </td>
                                        <td>{{$item->department->university->name}}</td>
                                        <td>{{$item->department->name}}</td>
{{--                                        <td>{{$item->instructor->name}}</td>--}}
                                        <td>
                                            @if($item->price==0)
                                                #
                                            @else
                                                5
                                            @endif
                                        </td>
                                        <td>
                                            @IFACAN('delete_courses')
                                            <button class='btn btn-sm btn-outline-danger' onclick="RemoveElement('remove-course-{{$item->id}}')">Remove</button>
                                            @ENDACAN
                                            @IFACAN('update_courses')
                                            <a class='btn btn-sm btn-outline-success' href="{{route('course.edit',$item->id)}}">Edit</a>
                                            @ENDACAN
                                            <button class='btn btn-sm btn-outline-warning' onclick="OpenMedia('model-{{$item->id}}')">View </button>
                                            @IFACAN('create_photos')
                                            <a class='btn btn-sm btn-outline-info' href="{{route('upload.form',['course',$item->id])}}">Upload</a>
                                            @ENDACAN
                                            <a class='btn btn-sm btn-outline-primary' href="{{route('course.show',$item->id)}}">Manage</a>
                                            @IFACAN('delete_courses')
                                            <form method='post' action='{{route('course.destroy',$item->id)}}' id='remove-course-{{$item->id}}'>
                                                @csrf
                                                @method('delete')
                                            </form>
                                            @ENDACAN
                                            <div class="modal fade" id="model-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header justify-content-end border-bottom-0">
                                                            <button type="button" class="btn-close-icon" data-dismiss="modal" aria-label="Close" >
                                                                <i class="mdi mdi-close" onclick="closeModel()"></i>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body pt-0">
                                                            <div class="row no-gutters">
                                                                <div class="col-md-6">
                                                                    <div class="profile-content-left px-4">
                                                                        <div class="card text-center widget-profile px-0 border-0">
                                                                            <video  controls style="height: 200px;border-radius: 15px" id="video-model-{{$item->id}}" >
                                                                                <source class='rounded-circle w-45'src='{{ROUTE('UPLOADED.FILES',['courses_files',$item->video,$item->id,null])}}'  alt="Course Intro Video" >
                                                                            </video>
                                                                            <div class="card-body">
                                                                                <h4 class="py-2 text-dark">Course Name
                                                                                    <span class="text-danger"> {{$item->name}}</span>
                                                                                </h4>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="contact-info px-4">
                                                                        <h4 class="text-dark mb-1">Course Details</h4>
                                                                        <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Name</span> <span >{{$item->name}}</span>
                                                                        <br>
                                                                        <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Code</span> <span>{{$item->code}}</span>
                                                                        <br>
                                                                        <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Department</span><span>{{$item->department->university->name.' University /'.$item->department->name}} Department</span>
                                                                        <br>
                                                                        <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Instructor</span><span>{{$item->instructor->name}}</span>
                                                                        <br>
                                                                        <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Slug</span> <span>{{$item->slug}}</span>
                                                                        <br>
                                                                        <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Price</span> <span >{{($item->price==0)?'Free':$item->price.' '.DefaultCurrency()}}</span>
                                                                        <p class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Description</p>
                                                                        <span>{!! $item->description !!}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

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




@endsection
