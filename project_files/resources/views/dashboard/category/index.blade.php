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
        /*function addInput(val) {
            $(document).ready(function() {
                $("<input>").attr({
                    type:"hidden",
                    name:"categories_id[]",
                    value:val
                }).appendTo("#delete-all-selected-check-box");
            }) ;

        }   */
        function addInput(model,position,val) {
            $(document).ready(function() {
                $("<input>").attr({
                    type:"hidden",
                    name:model+"_id[]",
                    value:val
                }).appendTo(position);
            }) ;

        }
        jQuery(document).ready(function() {

            //for photo start
        let datatable_photo=$('#data-table-photo').DataTable({
            lengthMenu: [ [5,10, 25, 50, -1], [5,10, 25, 50, "All"] ],
            deferRender: false,
            columnDefs: [
                {orderable: false,targets:[0,1,2,3,4]}
            ]
            ,dom: '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">'
        });

        new $.fn.dataTable.Buttons( datatable_photo, {

            buttons: [
                {
                    text:"Delete Selected",
                    className:'btn btn-sm btn-danger ml-2',
                    action: function (e, dt, node, config) {
                        let ids = [];
                        $('.photo-select').each(function(index, value) {
                            if ($(this).is(":checked")) {
                                ids.push($(this).val());
                                addInput('photos','#delete-all-selected-check-box-for-photos',$(this).val());
                            }
                        });
                        if (ids.length===0){
                            SweetAlert('please select at least one row');
                        }else{
                            RemoveElement('delete-all-selected-check-box-for-photos');
                        }
                    }
                }
            ]});
        datatable_photo.buttons(0,null).container().appendTo('#exportsControllersPhotos',datatable_photo.table().container());
        // for photo end
        });




        @if(!ReportsFeaturesMode())
        jQuery(document).ready(function() {
          $('#data-table').DataTable({
                lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                deferRender: false,
                columnDefs: [
                    {orderable: false, targets: [0,3]}
                ]
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
                    {orderable: false, targets: [0,1,4]}
                ]
                // ,dom: '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">'
            });

            new $.fn.dataTable.Buttons( datatable, {

                buttons: [
                    {
                        extend: 'print',
                        className: 'btn btn-sm btn-primary ml-2',
                        exportOptions: {
                            columns: [  2, 3]
                        }
                    }
                    ,{
                        extend: 'excelHtml5',
                        className:'btn btn-sm btn-primary ml-2',
                        exportOptions: {
                            columns: [  2, 3]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        alignment: 'center',
                        className:'btn btn-sm btn-primary ml-2',
                        exportOptions: {
                            columns: [ 2, 3]
                        }
                    }
                    , {
                        extend: 'csv',
                        className:'btn btn-sm btn-primary ml-2',
                        exportOptions: {
                            columns: [2, 3]
                        }
                    }
                    , {
                        text:"Delete Selected",
                        className:'btn btn-sm btn-danger ml-2',
                        action: function (e, dt, node, config) {
                            let ids = [];
                            $('.user-select').each(function(index, value) {
                                if ($(this).is(":checked")) {
                                    ids.push($(this).val());
                                    addInput('categories','#delete-all-selected-check-box',$(this).val());
                                }
                            });
                            if (ids.length===0){
                                SweetAlert('please select at least one row');
                            }else{
                                RemoveElement('delete-all-selected-check-box');
                            }
                        }
                    }
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
                    <a class="btn btn-sm btn-primary" href="{{route('category.create')}}">Add New Category</a>
                </div>
            </div>

            <div class="row">
                <div class="col-12 ">
                    <div class="card card-default">
                        <div class="card-header card-header-border-bottom d-flex justify-content-between">
                            <h2>
                                <span class="mdi mdi-cube-send"></span> Categories Table
                            </h2>

                            <div id="exportsControllers">

                            </div>
                        </div>

                        <div class="card-body">
                            <form method="post" action="{{route('category.multi.delete')}}" id="delete-all-selected-check-box">
                                @csrf
                                @method('delete')
                            </form>
                            <table id="data-table" class="table nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    @if(ReportsFeaturesMode())
                                        <th>
                                            <div class="custom-control custom-checkbox ">
                                                <input type="hidden" id="state" value="off">
                                                <input type="checkbox"  class="custom-control-input" id="select-all-rows" onclick="selectOrUnselectAll()">
                                                <label class="custom-control-label" for="select-all-rows"></label>
                                            </div>
                                        </th>
                                    @endif
                                    <th class="not-sortable">Photo</th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($categories as $item)
                                    <tr>
                                        @if(ReportsFeaturesMode())
                                            <td>
                                                <div class='custom-control custom-checkbox mb-3'>
                                                    <input type='checkbox'  class='custom-control-input user-select' name='categories[]' value='{{$item->id}}' id='category-{{$item->id}}'>
                                                    <label class='custom-control-label' for='category-{{$item->id}}'></label>
                                                </div>
                                            </td>
                                        @endif
                                        <td>
                                            <img class='rounded-circle w-45' style='margin-top: 0px;padding-top:0px;height: 45px' src='{{route('UPLOADED.FILES',['categories_photos',$item->photo,$item->id,'photos'])}}' alt='course main image'>
                                        </td>
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>
                                            <button class='btn btn-sm btn-outline-danger' onclick="RemoveElement('remove-category-{{$item->id}}')">Remove</button>
                                            <a class='btn btn-sm btn-outline-success' href="{{route('category.edit',$item->id)}}">Edit</a>
                                            <button class='btn btn-sm btn-outline-primary' onclick="OpenMedia('model-{{$item->id}}')">View Details</button>
                                            <a class='btn btn-sm btn-outline-info' href="{{route('upload.form',['category',$item->id])}}">Upload </a>
                                        </td>
                                        <form method='post' action='{{route('category.destroy',$item->id)}}' id='remove-category-{{$item->id}}'>
                                            @csrf
                                            @method('delete')
                                        </form>
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
                                                                                <source class='rounded-circle w-45'  src='{{route('UPLOADED.FILES',['categories_photos',$item->video,$item->id,'videos'])}}'  alt="Category Video" >
                                                                            </video>
                                                                            <div class="card-body">
                                                                                <h4 class="py-2 text-dark">{{$item->name}}</h4>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="contact-info px-4">
                                                                        <h4 class="text-dark mb-1">Category Details</h4>
                                                                        <p class="text-dark font-weight-medium pt-4 mb-2">{!! $item->description !!}</p>
                                                                        <p id="category-description"></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12 ">
                    <div class="card card-default">
                        <div class="card-header card-header-border-bottom d-flex justify-content-between">
                            <h2>
                                <span class="mdi mdi-folder-multiple-image"></span> All Categories Photos Table
                            </h2>
                            <div id="exportsControllersPhotos">
                            </div>
                        </div>

                        <div class="card-body">
                            <form method="post" action="{{route('upload.multi.delete')}}" id="delete-all-selected-check-box-for-photos">
                                @csrf
                                @method('delete')
                            </form>
                            <table id="data-table-photo" class="table nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    <th>
                                        <div class="custom-control custom-checkbox ">
                                            <input type="hidden" id="state_photo" value="off">
                                            <input type="checkbox"  class="custom-control-input" id="select-all-photos" onclick="selectOrUnselectAllPhotos()">
                                            <label class="custom-control-label" for="select-all-photos"></label>
                                        </div>
                                    </th>
                                    <th class="not-sortable">Photo</th>
                                    <th>ID</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($photos as $photo)
                                    <tr>
                                        <td>
                                            <div class='custom-control custom-checkbox mb-3'>
                                                <input type='checkbox'  class='custom-control-input photo-select' name='photos_id[]' value='{{$photo->id}}' id='photo-{{$photo->id}}'>
                                                <label class='custom-control-label' for='photo-{{$photo->id}}'></label>
                                            </div>
                                        </td>
                                        <td>
                                            <img class='rounded-circle w-45' style='margin-top: 0px;padding-top:0px;height: 45px' src='{{route('UPLOADED.FILES',['categories_photos',$photo->path,$photo->parent_id,'photos'])}}' alt='course additional  image'>
                                        </td>
                                        <td>{{$photo->id}}</td>
                                        <td>
                                            @if($photo->status)
                                                <span class="mdi mdi-checkbox-marked-circle text-success mdi-24px"></span>
                                            @else
                                                <span class="mdi mdi-close-circle text-danger mdi-24px"></span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class='btn btn-sm btn-outline-danger' onclick="RemoveElement('remove-photo-{{$photo->id}}')">Remove</button>
                                            <button class='btn btn-sm btn-outline-success' onclick="document.getElementById('update-photo-status-{{$photo->id}}').submit();">Edit Photo Status</button>
                                        </td>
                                        <form method='post' action='{{route('upload.destroy',$photo->id)}}' id='remove-photo-{{$photo->id}}'>
                                            @csrf
                                            @method('delete')
                                        </form>
                                        <form method='post' action='{{route('upload.update.status',$photo->id)}}' id='update-photo-status-{{$photo->id}}'>
                                            @csrf
                                            @method('put')
                                        </form>
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
