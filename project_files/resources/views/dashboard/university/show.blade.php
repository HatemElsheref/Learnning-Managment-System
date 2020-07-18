@extends('dashboard.layouts.app')

@section('title') {{$university->name}} University Details @endsection
@push('scripts')

    <script src="{{Dashboard_Assets()}}/plugins/data-tables/jquery.datatables.min.js"></script>
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
        jQuery(document).ready(function() {
            function addInput(val) {
                $(document).ready(function() {
                    $("<input>").attr({
                        type:"hidden",
                        name:"departments_id[]",
                        value:val
                    }).appendTo("#delete-all-selected-check-box");
                }) ;

            }
            let datatable=$('#data-table').DataTable({
                lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                deferRender: false,
                columnDefs: [
                    {orderable: false, targets: [0,1,5]}
                ]
                // ,dom: '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">'
            });
            @if(ReportsFeaturesMode())

            new $.fn.dataTable.Buttons( datatable, {

                buttons: [
                    {
                        extend: 'print',
                        className: 'btn btn-sm btn-secondary ml-2',
                        exportOptions: {
                            columns: [  2, 3,4]
                        }
                    }
                    ,{
                        extend: 'excelHtml5',
                        className:'btn btn-sm btn-warning ml-2',
                        exportOptions: {
                            columns: [  2, 3,4]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        alignment: 'center',
                        className:'btn btn-sm btn-danger ml-2',
                        exportOptions: {
                            columns: [ 2,3,4]
                        }
                    }
                    , {
                        extend: 'csv',
                        className:'btn btn-sm btn-success ml-2',
                        exportOptions: {
                            columns: [2, 3,4 ]
                        }
                    }
                    @IFACAN('delete_departments')
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
                    }  @ENDACAN
                    @IFACAN('create_departments')
                    , {
                        text: "Add New Department",
                        className:"btn btn-sm btn-primary",
                        action: function (e, dt, node, config) {
                            document.location.href='{{route('department.create')}}';
                        }
                    }
                    @ENDACAN
                ]});
            datatable.buttons(0,null).container().appendTo('#exportsControllers',datatable.table().container());
            @endif
        });

    </script>
@endpush
@section('content')
    <div class="content-wrapper">
        <div class="content">
            <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between __web-inspector-hide-shortcut__">
                <div>
                    <h1>{{$university->name}} University</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('university.index')}}">University</a></li>
                            <li class="breadcrumb-item" aria-current="page">{{$university->name}} Details</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="javascript:0" class="media  btn btn-sm btn-outline-warning" data-toggle="modal" data-target="#modal-contact">University Details</a>

                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <div class="card widget-block p-4 rounded bg-primary border">
                        <div class="card-block">
                            <i class="mdi mdi-account-outline mr-4 text-white"></i>
                            <h4 class="text-white my-2">{{$users}}</h4>
                            <p>Students</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <div class="card widget-block p-4 rounded bg-warning border">
                        <div class="card-block">
                            <i class="mdi mdi-view-grid mr-4 text-white"></i>
                            <h4 class="text-white my-2">{{count($university->departments)}}</h4>
                            <p>Departments</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <div class="card widget-block p-4 rounded bg-danger border">
                        <div class="card-block">
                            <i class="mdi mdi-play-circle  mr-4 text-white"></i>
                            <h4 class="text-white my-2">
                               @php
                               $count=0;
                               @endphp
                                @foreach($university->departments as $department)
                                    @php
                                        $count+=count($department->courses);
                                    @endphp
                                    @endforeach
                                {{$count}}
                            </h4>
                            <p>Courses</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 ">
                    <div class="card card-default">
                        <div class="card-header card-header-border-bottom d-flex justify-content-between">
                            <h2>
                                <span class="mdi mdi-view-grid"></span>Departments OF {{$university->name}}  University Table
                            </h2>

                            <div id="exportsControllers">
{{--                                <a class="btn btn-sm btn-primary" href="{{route('department.create')}}">Add New Department</a>--}}

                            </div>
                        </div>

                        <div class="card-body">
                            @IFACAN('delete_departments')
                            <form method="post" action="{{route('department.multi.delete')}}" id="delete-all-selected-check-box">
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
                                                @IFACAN('delete_departments')
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
                                    <th>Courses Number</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                        @foreach($university->departments as $department)
                                            <tr>
                                                @if(ReportsFeaturesMode())
                                                    <td>
                                                        <div class='custom-control custom-checkbox mb-3'>
                                                            @IFACAN('delete_departments')
                                                            <input type='checkbox'  class='custom-control-input user-select' name='departments_id[]' value='{{$department->id}}' id='department-{{$department->id}}'>
                                                            @ENDACAN
                                                            <label class='custom-control-label' for='department-{{$department->id}}'></label>
                                                        </div>
                                                    </td>
                                                @endif
                                                <td>
                                                    <img class='rounded-circle w-45' style='margin-top: 0px;padding-top:0px;height: 45px' src='{{route('UPLOADED.FILES',['departments_avatars',$department->photo])}}' alt='department image'>
                                                </td>
                                                <td>{{$department->id}}</td>
                                                <td>{{$department->name}}</td>
                                                <td>{{count($department->courses)}}</td>
                                                <td>
                                                    @IFACAN('delete_departments')
                                                    <button class='btn btn-sm btn-outline-danger' onclick="RemoveElement('remove-department-{{$department->id}}')">Remove</button>
                                                    @ENDACAN
                                                    @IFACAN('update_departments')
                                                    <a class='btn btn-sm btn-outline-success' href="{{route('department.edit',$department->id)}}">Edit</a>
                                                    @ENDACAN
                                                    <a class='btn btn-sm btn-outline-primary' href="{{route('department.show',$department->id)}}">Manage</a>
                                                </td>
                                                    @IFACAN('delete_departments')
                                                <form method='post' action='{{route('department.destroy',$department->id)}}' id='remove-department-{{$department->id}}'>
                                                    @csrf
                                                    @method('delete')
                                                </form>
                                                    @ENDACAN
                                            </tr>

                                        @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @if(SeoFeaturesMode())
                <div class="col-12" >
                    <div class="card card-default">
                        <div class="card-header card-header-border-bottom d-flex justify-content-between">
                            <h2>
                                <span class="mdi mdi-google-analytics"></span> Seo Management
                            </h2>
                        </div>
                        <div class="card-body">
                            @include('dashboard.layouts.validation_error')
                            <form action="{{route('university.seo',$university->id)}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="slug">Slug</label>
                                            <input type="text" class="form-control" id="slug" name="slug" value="{{$university->slug}}" placeholder="Enter University Slug">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="meta_title">Title</label>
                                            <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{$university->meta_title}}" placeholder="Enter University Meta Title">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="meta_keywords">Keywords</label>
                                            <div class="form-group" >
                                                <select class="key-words form-control " name="meta_keywords[]" multiple id="meta_keywords">
                                                           @foreach((array)json_decode($university->meta_keywords) as $keyword)
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
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="meta_description">Description</label>
                                            <input type="text" class="form-control" id="meta_description" name="meta_description" value="{{$university->meta_description}}" placeholder="Enter University Meta Description">
                                        </div>
                                    </div>
                                </div>





                                <div class="form-footer pt-4 pt-2 mt-2 border-top">
                                    @IFACAN('update_departments')
                                    <button type="submit" class="btn btn-success btn-default">Save</button>
                                    @ENDACAN
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                @endif
            </div>

        </div>



        <div class="modal fade" id="modal-contact" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header justify-content-end border-bottom-0">
                        @IFACAN('update_universities')
                        <a type="button" class="btn-edit-icon" href="{{route('university.edit',$university->id)}}">
                            <i class="mdi mdi-pencil"></i>
                        </a>
                        @ENDACAN
                        <button type="button" class="btn-close-icon" data-dismiss="modal" aria-label="Close">
                            <i class="mdi mdi-close"></i>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="row no-gutters">
                            <div class="col-md-6">
                                <div class="profile-content-left px-4">
                                    <div class="card text-center widget-profile px-0 border-0">
                                        <div class="card-img mx-auto rounded-circle" style="width: 200px!important;height: 200px!important;">
                                            <img src="{{route('UPLOADED.FILES',['universities_avatars',$university->photo])}}" class="mr-3 img-fluid rounded" alt="Avatar Image">
                                        </div>
                                        <div class="card-body">
                                            <h4 class="py-2 text-dark">Name: {{$university->name}}</h4>
                                        </div>
                                        <div class="d-flex justify-content-between ">
                                            <div class="text-center pb-4">
                                                <h6 class="text-dark pb-2">   {{$count}}</h6>
                                                <p>Course</p>
                                            </div>
                                            <div class="text-center pb-4">
                                                <h6 class="text-dark pb-2">{{count($university->departments)}}</h6>
                                                <p>Department</p>
                                            </div>
                                            <div class="text-center pb-4">
                                                <h6 class="text-dark pb-2">1200</h6>
                                                <p>Student</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="contact-info px-4">
                                    <h4 class="text-dark mb-1">University Details</h4>
                                    <p class="text-dark font-weight-medium pt-4 mb-2">University Address</p>
                                    <p>{{ $university->address}}</p>
                                    <p class="text-dark font-weight-medium pt-4 mb-2">University Description</p>
                                    <p>{!! $university->description !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

@endsection
