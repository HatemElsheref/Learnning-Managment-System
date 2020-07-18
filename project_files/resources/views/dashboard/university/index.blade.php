@extends('dashboard.layouts.app')

@section('title') All University @endsection
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

            @if(!ReportsFeaturesMode())
            jQuery(document).ready(function() {
            $('#data-table').DataTable({
                lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ]
                ,dom: '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">'
            });
            });
                @endif


            @if(ReportsFeaturesMode())
                jQuery(document).ready(function() {
                let datatable=$('#data-table').DataTable({
                    lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                    deferRender: false,
                    columnDefs: [
                        {orderable: false, targets: [0,1,7]}
                    ]
                    ,dom: '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">'
                });

            function addInput(val) {
                $(document).ready(function() {
                    $("<input>").attr({
                        type:"hidden",
                        name:"universities_id[]",
                        value:val
                    }).appendTo("#delete-all-selected-check-box");
                }) ;

            }
            new $.fn.dataTable.Buttons( datatable, {

                buttons: [
                    {
                        extend: 'print',
                        className: 'btn btn-sm btn-secondary ml-2',
                        exportOptions: {
                            columns: [  2, 3, 4,5]
                        }
                    }
                    ,{
                        extend: 'excelHtml5',
                        className:'btn btn-sm btn-warning ml-2',
                        exportOptions: {
                            columns: [  2, 3,4 ,5]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        alignment: 'center',
                        className:'btn btn-sm btn-danger ml-2',
                        exportOptions: {
                            columns: [ 2,3, 4,5 ]
                        }
                    }
                    , {
                        extend: 'csv',
                        className:'btn btn-sm btn-success ml-2',
                        exportOptions: {
                            columns: [2, 3, 4,5 ]
                        }
                    }
                    @IFACAN('delete_universities')
                    @if(DeleteMode())
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

                    @endif
                        @ENDACAN
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
                    <h1>University</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">University</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    @IFACAN('create_universities')
                    <a class="btn btn-sm btn-primary" href="{{route('university.create')}}">Add New University</a>
                    @ENDACAN
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card card-default">
                        <div class="card-header card-header-border-bottom d-flex justify-content-between">
                            <h2><span class="mdi mdi-bank"></span> Universities Table</h2>

                            <div id="exportsControllers"></div>
                        </div>

                        <div class="card-body">
                            @IFACAN('delete_universities')
                            <form method="post" action="{{route('university.multi.delete')}}" id="delete-all-selected-check-box">
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
                                                @IFACAN('delete_universities')
                                                @if(DeleteMode())
                                                <input type="hidden" id="state" value="off">
                                                <input type="checkbox"  class="custom-control-input" id="select-all-rows" onclick="selectOrUnselectAll()" >
                                               @endif
                                                @ENDACAN
                                                <label class="custom-control-label" for="select-all-rows"></label>
                                            </div>
                                        </th>
                                    @endif
                                    <th class="not-sortable">Photo</th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Description</th>
                                    <th>Departments</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($universities as $university)
                                    <tr>
                                    @if(ReportsFeaturesMode())
                                        <td>
                                            <div class='custom-control custom-checkbox mb-3'>
                                                @IFACAN('delete_universities')
                                                @if(DeleteMode())
                                                <input type='checkbox'  class='custom-control-input user-select' name='universities[]' value='{{$university->id}}' id='university-{{$university->id}}'>
                                                     @endif
                                                    @ENDACAN
                                                <label class='custom-control-label' for='university-{{$university->id}}'></label>
                                            </div>
                                        </td>
                                    @endif
                                    <td>
                                        <img class='rounded-circle w-45' style='margin-top: 0px;padding-top:0px;height: 45px' src='{{route('UPLOADED.FILES',['universities_avatars',$university->photo])}}' alt='university image'>
                                    </td>
                                    <td>{{$university->id}}</td>
                                    <td>{{$university->name}}</td>
                                    <td>{{$university->address}}</td>
                                    <td>
                                        Go To Details
{{--                                        {!! substr(ucwords($university->description),0,10) !!}--}}
                                    </td>
                                    <td>{{count($university->departments)}}</td>
                                    <td>
                                        @IFACAN('delete_universities')
                                        @if(DeleteMode())
                                            <button class='btn btn-sm btn-outline-danger' onclick="RemoveElement('remove-university-{{$university->id}}')">Remove</button>
                                        @endif
                                        @ENDACAN
                                            @IFACAN('update_universities')
                                            <a class='btn btn-sm btn-outline-success' href="{{route('university.edit',$university->id)}}">Edit</a>
                                            @ENDACAN

                                          <a class='btn btn-sm btn-outline-primary' href="{{route('university.show',$university->id)}}">Manage</a>
                                    </td>
                                        @IFACAN('delete_universities')
                                        @if(DeleteMode())
                                            <form method='post' action='{{route('university.destroy',$university->id)}}' id='remove-university-{{$university->id}}'>
                                                @csrf
                                                @method('delete')
                                            </form>
                                            @endif
                                        @ENDACAN
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
