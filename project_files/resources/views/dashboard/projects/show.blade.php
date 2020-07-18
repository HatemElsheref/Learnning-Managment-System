@extends('dashboard.layouts.app')

@section('title') {{$project->name}}   @endsection

@push('scripts')

    <script src="{{Dashboard_Assets()}}/plugins/data-tables/jquery.datatables.min.js"></script>
    <script src="{{Dashboard_Assets()}}/plugins/data-tables/datatables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script>
        jQuery(document).ready(function() {
            function addInput(model, position, val) {
                $(document).ready(function () {
                    $("<input>").attr({
                        type: "hidden",
                        // name:"lessons_id[]",
                        name: model + "_id[]",
                        value: val
                        // }).appendTo("#delete-all-selected-check-box");
                    }).appendTo(position);
                });

            }

            //for photo start
            let datatable_photo = $('#data-table-photo').DataTable({
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                deferRender: false,
                columnDefs: [
                    {orderable: false, targets: [0, 1, 3, 4]}
                ]
                ,
                dom: '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">'
            });

            new $.fn.dataTable.Buttons(datatable_photo, {

                    buttons: [
                        @IFACAN('delete_photos')
                        {
                            text: "Delete Selected",
                            className: 'btn btn-sm btn-danger ml-2',
                            action: function (e, dt, node, config) {
                                let ids = [];
                                $('.photo-select').each(function (index, value) {
                                    if ($(this).is(":checked")) {
                                        ids.push($(this).val());
                                        addInput('photos', '#delete-all-selected-check-box-for-photos', $(this).val());
                                    }
                                });
                                if (ids.length === 0) {
                                    SweetAlert('please select at least one row');
                                } else {
                                    RemoveElement('delete-all-selected-check-box-for-photos');
                                }
                            }
                        }
                        @ENDACAN
                        @IFACAN('create_photos')
                        , {
                            text: "Upload New Photo",
                            className: "btn btn-sm btn-info",
                            action: function (e, dt, node, config) {
                                document.location.href = '{{route('upload.form',['project',$project->id])}}';
                            }
                        }
                        @ENDACAN
                    ]
                }
            );
            datatable_photo.buttons(0, null).container().appendTo('#exportsControllersPhotos', datatable_photo.table().container());
            // for photo end
        });
    </script>
@endpush
@section('content')
    <div class="content-wrapper">
        <div class="content">
            <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between __web-inspector-hide-shortcut__">
                <div>
                    <h1>{{ucfirst(strtolower($project->name))}}  Project</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('project.index')}}">Projects</a></li>
                            <li class="breadcrumb-item" aria-current="page">Photos </li>

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
                                <span class="mdi mdi-folder-multiple-image"></span> Project Photos Table
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
                                            <img class='rounded-circle w-45' style='margin-top: 0px;padding-top:0px;height: 45px' src='{{route('UPLOADED.FILES',['projects_photos',$photo->path,$project->id,'photos'])}}' alt='project additional  image'>
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
                                            @IFACAN('delete_photos')
                                            <button class='btn btn-sm btn-outline-danger' onclick="RemoveElement('remove-photo-{{$photo->id}}')">Remove</button>
                                            @ENDACAN
                                            @IFACAN('update_photos')
                                            <button class='btn btn-sm btn-outline-success' onclick="document.getElementById('update-photo-status-{{$photo->id}}').submit();">Edit Photo Status</button>
                                            @ENDACAN
                                        </td>
                                        @IFACAN('delete_photos')
                                        <form method='post' action='{{route('upload.destroy',$photo->id)}}' id='remove-photo-{{$photo->id}}'>
                                            @csrf
                                            @method('delete')
                                        </form>
                                        @ENDACAN
                                        @IFACAN('update_photos')
                                        <form method='post' action='{{route('upload.update.status',$photo->id)}}' id='update-photo-status-{{$photo->id}}'>
                                            @csrf
                                            @method('put')
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
