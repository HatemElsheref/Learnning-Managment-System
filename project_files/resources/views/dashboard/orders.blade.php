@extends('dashboard.layouts.app')

@section('title') All Orders @endsection
@push('scripts')

    <script src="{{Dashboard_Assets()}}/plugins/data-tables/jquery.datatables.min.js"></script>
    <script src="{{Dashboard_Assets()}}/plugins/data-tables/datatables.bootstrap4.min.js"></script>
    <script>
        function deleteSelected() {
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
        function addInput(val) {
            $(document).ready(function() {
                $("<input>").attr({
                    type:"hidden",
                    name:"orders_id[]",
                    value:val
                }).appendTo("#delete-all-selected-check-box");
            }) ;

        }
        jQuery(document).ready(function() {



            let datatable=$('#data-table').DataTable({
                lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                deferRender: false,
                columnDefs: [
                    {orderable: false, targets: [0,7,8]}
                ]
                // ,dom: '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">'
            });
        });

    </script>
@endpush
@section('content')
    <div class="content-wrapper">
        <div class="content">
            @include('dashboard.layouts.validation_error')
            <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between __web-inspector-hide-shortcut__">
                <div>
                    <h1>Orders</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">All Orders</li>
                        </ol>
                    </nav>
                </div>
                <div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 ">
                    <div class="card card-default">
                        <div class="card-header card-header-border-bottom d-flex justify-content-between">
                            <h2>
                                <span class="mdi mdi-cart"></span> Orders Table
                            </h2>

                            <div id="exportsControllers">
                                @IFACAN('delete_course_orders')
                                     <button class="btn btn-sm btn-danger ml-2" onclick="deleteSelected();">Delete Selected </button>
                                @ENDACAN
                            </div>
                        </div>

                        <div class="card-body">
                            @IFACAN('delete_course_orders')
                            <form method="post" action="{{route('orders.multi.delete')}}" id="delete-all-selected-check-box">
                                @csrf
                                @method('delete')
                            </form>
                            @ENDACAN
                            <table id="data-table" class="table nowrap" style="width:100%">
                                <thead>
                                <tr>
                                        <th>
                                            <div class="custom-control custom-checkbox ">
                                                @IFACAN('delete_course_orders')
                                                <input type="hidden" id="state" value="off">
                                                <input type="checkbox"  class="custom-control-input" id="select-all-rows" onclick="selectOrUnselectAll()">
                                                @ENDACAN
                                                <label class="custom-control-label" for="select-all-rows"></label>
                                            </div>
                                        </th>
                                    <th>ID</th>
                                    <th>Student</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Course</th>
                                    <th>State</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $item)
                                    <tr>
                                            <td>
                                                <div class='custom-control custom-checkbox mb-3'>
                                                    @IFACAN('delete_course_orders')
                                                    <input type='checkbox'  class='custom-control-input user-select' name='departments[]' value='{{$item->id}}' id='department-{{$item->id}}'>
                                                    @ENDACAN
                                                    <label class='custom-control-label' for='department-{{$item->id}}'></label>
                                                </div>
                                            </td>
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->email}}</td>
                                        <td>{{$item->phone}}</td>
                                        <td>{{$item->address}}</td>
                                        <td>{{$item->course}}</td>
                                        <td>
                                            @if($item->status=='closed')
                                                <span class="badge badge-danger">Closed</span>
                                                @else
                                                <span class="badge badge-primary">Opened</span>
                                                @endif
                                        </td>
                                        <td>
                                            @IFACAN('delete_course_orders')
                                            <button class='btn btn-sm btn-outline-danger' onclick="RemoveElement('remove-department-{{$item->id}}')">Remove</button>
                                            @ENDACAN
                                            @IFACAN('update_course_orders')

                                            <form method='post' action='{{route('orders.approve',$item->id)}}'>
                                                @csrf
                                                @method('put')
                                                @if($item->status=='closed')
                                                <button  type="submit" class='btn btn-sm btn-outline-success' >Approve</button>
                                                    @else
                                                    <button  type="submit" class='btn btn-sm btn-outline-warning' >Cancel</button>
                                                @endif
                                            </form>

                                            @ENDACAN
                                        </td>
                                        @IFACAN('delete_course_orders')
                                        <form method='post' action='{{route('orders.destroy',$item->id)}}' id='remove-department-{{$item->id}}'>
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
