@extends('dashboard.layouts.app')

@section('title') All Posts @endsection

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
                        name:"posts_id[]",
                        value:val
                    }).appendTo(position);
                }) ;

            }
            let datatable_photo=$('#data-table-photo').DataTable({
                lengthMenu: [ [5,10, 25, 50, -1], [5,10, 25, 50, "All"] ],
                deferRender: false,
                columnDefs: [
                    {orderable: false,targets:[0,1,6]}
                ]
                ,dom: '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">'
            });

            new $.fn.dataTable.Buttons( datatable_photo, {

                buttons: [
                    @IFACAN('delete_post')
                    {
                        text:"Delete Selected",
                        className:'btn btn-sm btn-danger ml-2',
                        action: function (e, dt, node, config) {
                            let ids = [];
                            $('.photo-select').each(function(index, value) {
                                if ($(this).is(":checked")) {
                                    ids.push($(this).val());
                                    addInput('articles','#delete-all-selected-check-box-for-photos',$(this).val());
                                }
                            });
                            if (ids.length===0){
                                SweetAlert('please select at least one row');
                            }else{
                                RemoveElement('delete-all-selected-check-box-for-photos');
                            }
                        }
                    }
                    @ENDACAN
                ]});
            datatable_photo.buttons(0,null).container().appendTo('#exportsControllersPhotos',datatable_photo.table().container());
            // for photo end

        });

    </script>
@endpush
@section('content')
    <div class="content-wrapper">
        <div class="content">
            <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between __web-inspector-hide-shortcut__">
                <div>
                    <h1>All Posts</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Posts </li>

                        </ol>
                    </nav>
                </div>
                <div>
                    @IFACAN('create_post')
                    <a href="{{route('post.create')}}" class="btn btn-sm btn-primary" >Add New Post</a>
                    @ENDACAN
                </div>
            </div>
            <div class="row">
                <div class="col-12 ">
                    <div class="card card-default">
                        <div class="card-header card-header-border-bottom d-flex justify-content-between">
                            <h2>
                                <span class="mdi mdi-lead-pencil  "></span> Posts  Table
                            </h2>
                            <div id="exportsControllersPhotos">
                            </div>
                        </div>

                        <div class="card-body">
                            @IFACAN('delete_post')
                            <form method="post" action="{{route('post.multi.delete')}}" id="delete-all-selected-check-box-for-photos">
                                @csrf
                                @method('delete')
                            </form>
                            @ENDACAN
                            <table id="data-table-photo" class="table nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    <th>
                                        <div class="custom-control custom-checkbox ">
                                            @IFACAN('delete_post')
                                            <input type="hidden" id="state_photo" value="off">
                                            <input type="checkbox"  class="custom-control-input" id="select-all-photos" onclick="selectOrUnselectAllPhotos()">
                                            @ENDACAN
                                            <label class="custom-control-label" for="select-all-photos"></label>
                                        </div>
                                    </th>
                                    <th class="not-sortable">Photo</th>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Instructor</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($posts as $post)
                                    <tr>
                                        <td>
                                            <div class='custom-control custom-checkbox mb-3'>
                                                @IFACAN('delete_post')
                                                <input type='checkbox'  class='custom-control-input photo-select' name='photos_id[]' value='{{$post->id}}' id='photo-{{$post->id}}'>
                                                @ENDACAN
                                                <label class='custom-control-label' for='photo-{{$post->id}}'></label>
                                            </div>
                                        </td>
                                        <td>
                                            <img class='rounded-circle w-45' style='margin-top: 0px;padding-top:0px;height: 45px' src='{{route('UPLOADED.FILES',['posts_photos',$post->photo,null,null])}}' alt='post main  image'>
                                        </td>
                                        <td>{{$post->id}}</td>
                                        <td>{{$post->title}}</td>
                                        <td>{{$post->category->name}}</td>
                                        <td>{{$post->instructor->name}}</td>
                                        <td>
                                            @IFACAN('delete_post')
                                            <button class='btn btn-sm btn-outline-danger' onclick="RemoveElement('remove-photo-{{$post->id}}')">Remove</button>
                                            @ENDACAN
                                            @IFACAN('update_post')
                                            <a class='btn btn-sm btn-outline-success' href="{{route('post.edit',$post->id)}}">Edit</a>
                                            @ENDACAN
                                        </td>
                                        @IFACAN('delete_post')
                                        <form method='post' action='{{route('post.destroy',$post->id)}}' id='remove-photo-{{$post->id}}'>
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
