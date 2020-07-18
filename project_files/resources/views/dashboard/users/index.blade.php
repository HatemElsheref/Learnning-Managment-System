@extends('dashboard.layouts.app')

@section('title') All Students @endsection
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
                        name:"students_id[]",
                        value:val
                    }).appendTo("#delete-all-selected-check-box");
                }) ;

            }
            let datatable=$('#data-table').DataTable({
                lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                deferRender: false,
                columnDefs: [
                    {orderable: false, targets: [0,1,9,10]}
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
                            columns: [  2, 3,4,5,6,7,8]
                        }
                    }
                    ,{
                        extend: 'excelHtml5',
                        className:'btn btn-sm btn-warning ml-2',
                        exportOptions: {
                            columns: [  2, 3,4,5,6,7,8]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        alignment: 'center',
                        className:'btn btn-sm btn-danger ml-2',
                        exportOptions: {
                            columns: [  2, 3,4,5,6,7,8]
                        }
                    }
                    , {
                        extend: 'csv',
                        className:'btn btn-sm btn-success ml-2',
                        exportOptions: {
                            columns: [  2, 3,4,5,6,7,8]
                        }
                    }
                    @IFACAN('delete_users')
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
                    <h1>Students</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Students</li>
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
                                <span class="mdi mdi-account-group-outline"></span> Students Table
                            </h2>

                            <div id="exportsControllers">

                            </div>
                        </div>

                        <div class="card-body">
                            @IFACAN('delete_users')
                            <form method="post" action="{{route('students.multi.delete')}}" id="delete-all-selected-check-box">
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
                                                @IFACAN('delete_users')
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
                                    <th>Country</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>university</th>
                                    <th>Department</th>
                                    <th>Courses</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        @if(ReportsFeaturesMode())
                                            <td>
                                                <div class='custom-control custom-checkbox mb-3'>
                                                    @IFACAN('delete_users')
                                                    <input type='checkbox'  class='custom-control-input user-select' name='instructors[]' value='{{$user->id}}' id='department-{{$user->id}}'>
                                                    @ENDACAN
                                                    <label class='custom-control-label' for='department-{{$user->id}}'></label>
                                                </div>
                                            </td>
                                        @endif
                                        <td>
                                            <img class='rounded-circle w-45' style='margin-top: 0px;padding-top:0px;height: 45px' src='{{route('UPLOADED.FILES',['users',$user->photo])}}' alt='user image'>
                                        </td>
                                        <td>{{$user->id}}</td>
                                        <td>{{explode(' ',$user->name)[0]}}</td>
                                        <td>{{$user->country}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->phone}}</td>
                                        <td>{{$user->department->university->name}}</td>
                                        <td>{{$user->department->name}}</td>
                                        <td>
                                            @php
                                            $data=[];
                                                          foreach ($user->courses as $course){

                                                              array_push($data,$course->code);
                                                          }
                                            $data=json_encode($data);
                                            $data=str_replace('"','',$data);
                                            @endphp
                                            <span class="badge badge-sm badge-primary" data-toggle="tooltip"  data-original-title="{{$data}}">Courses</span>
                                        </td>
                                        <td>
                                            @IFACAN('delete_users')
                                            <button class='btn btn-sm btn-outline-danger' onclick="RemoveElement('remove-student-{{$user->id}}')">Remove</button>
                                            @ENDACAN
                                            @IFACAN('update_users')
                                            <form method='post' action='{{route('students.update',$user->id)}}'>
                                                @csrf
                                                @method('put')
                                                @if(!$user->isBlocked)
                                                    <button type="submit" class='btn btn-sm btn-outline-success'>Block</button>
                                                @else
                                                    <button type="submit" class='btn btn-sm btn-outline-success'>Un Block</button>
                                                @endif

                                            </form>
                                            @ENDACAN
                                        </td>
                                        @IFACAN('delete_users')
                                        <form method='post' action='{{route('students.destroy',$user->id)}}' id='remove-student-{{$user->id}}'>
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
