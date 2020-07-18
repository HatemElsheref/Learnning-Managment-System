@extends('dashboard.layouts.app')

@section('title') All Feedback @endsection
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
                @if(!ReportsFeaturesMode())
            let datatable1=$('#data-table').DataTable({
                    lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                    deferRender: false,
                    columnDefs: [
                        {orderable: false, targets: [5,6]}
                    ]
                    // ,dom: '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">'
                });
            @endif


            @if(ReportsFeaturesMode())
            function addInput(val) {
                $(document).ready(function() {
                    $("<input>").attr({
                        type:"hidden",
                        name:"feedback_id[]",
                        value:val
                    }).appendTo("#delete-all-selected-check-box");
                }) ;

            }
            let datatable=$('#data-table').DataTable({
                lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                deferRender: false,
                columnDefs: [
                    {orderable: false, targets: [0,6,7]}
                ]
                // ,dom: '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">'
            });
            new $.fn.dataTable.Buttons( datatable, {

                buttons: [
                    {
                        extend: 'print',
                        className: 'btn btn-sm btn-secondary ml-2',
                        exportOptions: {
                            columns: [  1,2, 3,4]
                        }
                    }
                    ,{
                        extend: 'excelHtml5',
                        className:'btn btn-sm btn-warning ml-2',
                        exportOptions: {
                            columns: [  1,2, 3,4]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        alignment: 'center',
                        className:'btn btn-sm btn-danger ml-2',
                        exportOptions: {
                            columns: [  1,2, 3,4]
                        }
                    }
                    , {
                        extend: 'csv',
                        className:'btn btn-sm btn-success ml-2',
                        exportOptions: {
                            columns: [  1,2, 3,4]
                        }
                    }
                    @IFACAN('delete_feedback')
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
            @include('dashboard.layouts.validation_error')
            <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between __web-inspector-hide-shortcut__">
                <div>
                    <h1>Feedback</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Feedback</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    @IFACAN('create_feedback')
                    <a class="btn btn-sm btn-primary" href="{{route('feedback.create')}}">Add New Feedback</a>
                    @ENDACAN
                </div>
            </div>

            <div class="row">
                <div class="col-12 ">
                    <div class="card card-default">
                        <div class="card-header card-header-border-bottom d-flex justify-content-between">
                            <h2>
                                <span class="mdi  mdi mdi-heart-multiple"></span> Feedback Table
                            </h2>

                            <div id="exportsControllers">

                            </div>
                        </div>

                        <div class="card-body">
                            @IFACAN('delete_feedback')
                            <form method="post" action="{{route('feedback.multi.delete')}}" id="delete-all-selected-check-box">
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
                                                @IFACAN('delete_feedback')
                                                <input type="hidden" id="state" value="off">
                                                <input type="checkbox"  class="custom-control-input" id="select-all-rows" onclick="selectOrUnselectAll()">
                                                @ENDACAN
                                                <label class="custom-control-label" for="select-all-rows"></label>
                                            </div>
                                        </th>
                                    @endif
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Country</th>
                                    <th>Department</th>
                                    <th>University</th>
                                    <th>Source</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($feedbacks as $item)
                                    <tr>
                                        @if(ReportsFeaturesMode())
                                            <td>
                                                <div class='custom-control custom-checkbox mb-3'>
                                                    @IFACAN('delete_feedback')
                                                    <input type='checkbox'  class='custom-control-input user-select' name='departments[]' value='{{$item->id}}' id='department-{{$item->id}}'>
                                                    @ENDACAN
                                                    <label class='custom-control-label' for='department-{{$item->id}}'></label>
                                                </div>
                                            </td>
                                        @endif
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>
                                            @foreach($countries as $country)
                                               @if($item->country_id==$country->id)
                                                    {{$country->Name}}
                                                    @break
                                                   @endif
                                                @endforeach
                                        </td>
                                        <td>{{$item->department->name}}</td>
                                        <td>{{$item->department->university->name}}</td>
                                        <td>
                                            <a href="javascript:0" class="media " data-toggle="modal" data-target="#modal-video-lesson-{{$item->id}}"><span class="mdi mdi-play-circle mdi-24px"></span></a>
                                        </td>

                                        <td>
                                            @IFACAN('delete_feedback')
                                            <button class='btn btn-sm btn-outline-danger' onclick="RemoveElement('remove-department-{{$item->id}}')">Remove</button>
                                            @ENDACAN
                                            @IFACAN('update_feedback')
                                            <a class='btn btn-sm btn-outline-success' href="{{route('feedback.edit',$item->id)}}">Edit</a>
                                            @ENDACAN
                                        </td>
                                        @IFACAN('delete_feedback')
                                        <form method='post' action='{{route('feedback.destroy',$item->id)}}' id='remove-department-{{$item->id}}'>
                                            @csrf
                                            @method('delete')
                                        </form>
                                        @ENDACAN
                                    </tr>
                                    <div class="modal fade" id="modal-video-lesson-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header justify-content-end border-bottom-0">
                                                    <a type="button" class="btn-edit-icon" href="{{route('feedback.edit',$item->id)}}">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                    <button type="button" class="btn-close-icon" data-dismiss="modal" aria-label="Close">
                                                        <i class="mdi mdi-close"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body pt-0">
                                                    <div class="row no-gutters">
                                                        <div class="col-md-12">
                                                            <div class="profile-content-left px-4">
                                                                <div class="card text-center widget-profile px-0 border-0">
                                                                    @if($item->type=='audio')
                                                                        <audio  controls style="height: 50px;margin:0px 200px;border-radius: 15px" >
                                                                            <source class='rounded-circle w-45' src="{{route('UPLOADED.FILES',['feedback',$item->feedback,'audios',null])}}"  alt="Feedback audio format" >
                                                                        </audio>
                                                                    @elseif($item->type=='video')
                                                                        <video  controls style="height: 400px;border-radius: 15px"  >
                                                                            <source class='rounded-circle w-45' src="{{route('UPLOADED.FILES',['feedback',$item->feedback,'videos',null])}}"  alt="Feedback Video format" >
                                                                        </video>
                                                                    @else

                                                                        <img style="height: 400px;border-radius: 15px" src="{{route('UPLOADED.FILES',['feedback',$item->feedback,'images',null])}}">
                                                                    @endif
                                                                    <div class="card-body">
                                                                        <h4 class="py-2 text-dark">Feedback Name
                                                                            <span class="text-danger"> {{$item->name}}</span>
                                                                        </h4>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>






@endsection
