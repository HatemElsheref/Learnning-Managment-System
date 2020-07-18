@extends('dashboard.layouts.app')

@section('title') All Categories @endsection
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
                        {orderable: false, targets: [3]}
                    ]
                    // ,dom: '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">'
                });
            @endif


            @if(ReportsFeaturesMode())
            function addInput(val) {
                $(document).ready(function() {
                    $("<input>").attr({
                        type:"hidden",
                        name:"categories_id[]",
                        value:val
                    }).appendTo("#delete-all-selected-check-box");
                }) ;

            }
            let datatable=$('#data-table').DataTable({
                lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                deferRender: false,
                columnDefs: [
                    {orderable: false, targets: [0,4]}
                ]
                // ,dom: '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">'
            });
            new $.fn.dataTable.Buttons( datatable, {

                buttons: [
                    {
                        extend: 'print',
                        className: 'btn btn-sm btn-secondary ml-2',
                        exportOptions: {
                            columns: [  1,2, 3]
                        }
                    }
                    ,{
                        extend: 'excelHtml5',
                        className:'btn btn-sm btn-warning ml-2',
                        exportOptions: {
                            columns: [  1,2, 3]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        alignment: 'center',
                        className:'btn btn-sm btn-danger ml-2',
                        exportOptions: {
                            columns: [  1,2, 3]
                        }
                    }
                    , {
                        extend: 'csv',
                        className:'btn btn-sm btn-success ml-2',
                        exportOptions: {
                            columns: [  1,2, 3]
                        }
                    }
                    @IFACAN('delete_category')
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
                    <h1>Categories</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Categories</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    @IFACAN('create_category')
                    <a class="btn btn-sm btn-primary" href="{{route('category.create')}}">Add New Category</a>
                    @ENDACAN
                </div>
            </div>

            <div class="row">
                <div class="col-12 ">
                    <div class="card card-default">
                        <div class="card-header card-header-border-bottom d-flex justify-content-between">
                            <h2>
                                <span class="mdi mdi-view-grid"></span> Categories Table
                            </h2>

                            <div id="exportsControllers">

                            </div>
                        </div>

                        <div class="card-body">
                            @IFACAN('delete_category')
                            <form method="post" action="{{route('category.multi.delete')}}" id="delete-all-selected-check-box">
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
                                                @IFACAN('delete_category')
                                                <input type="hidden" id="state" value="off">
                                                <input type="checkbox"  class="custom-control-input" id="select-all-rows" onclick="selectOrUnselectAll()">
                                                @ENDACAN
                                                <label class="custom-control-label" for="select-all-rows"></label>
                                            </div>
                                        </th>
                                    @endif
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Posts</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($categories as $item)
                                    <tr>
                                        @if(ReportsFeaturesMode())
                                            <td>
                                                <div class='custom-control custom-checkbox mb-3'>
                                                    @IFACAN('delete_category')
                                                    <input type='checkbox'  class='custom-control-input user-select' name='departments[]' value='{{$item->id}}' id='department-{{$item->id}}'>
                                                    @ENDACAN
                                                    <label class='custom-control-label' for='department-{{$item->id}}'></label>
                                                </div>
                                            </td>
                                        @endif
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>{{count($item->posts)}}</td>
                                        <td>
                                            @IFACAN('delete_category')
                                            <button class='btn btn-sm btn-outline-danger' onclick="RemoveElement('remove-department-{{$item->id}}')">Remove</button>
                                            @ENDACAN
                                            @IFACAN('update_category')
                                            <a class='btn btn-sm btn-outline-success' href="{{route('category.edit',$item->id)}}">Edit</a>
                                            @ENDACAN
                                        </td>
                                        @IFACAN('delete_category')
                                        <form method='post' action='{{route('category.destroy',$item->id)}}' id='remove-department-{{$item->id}}'>
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
            </div>
        </div>






@endsection
