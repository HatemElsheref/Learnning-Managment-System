@extends('dashboard.layouts.app')

@section('title') {{$course->name}}  Course Details @endsection

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
                        // name:"lessons_id[]",
                        name:model+"_id[]",
                        value:val
                        // }).appendTo("#delete-all-selected-check-box");
                    }).appendTo(position);
                }) ;

            }
            //for photo start
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
                    @IFACAN('delete_article')

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
                    }    @ENDACAN
                    @IFACAN('create_article')
                    ,{
                        text: "Add New Article",
                        className:"btn btn-sm btn-primary",
                        action: function (e, dt, node, config) {
                            document.location.href='{{route('course.article.create',$course->id)}}';
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
                    <h1>{{ucfirst(strtolower($course->name))}}  Course</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('course.index')}}">Courses</a></li>
                            <li class="breadcrumb-item"><a href="{{route('course.show',$course->id)}}">{{ucfirst(strtolower($course->name))}}</a></li>
                            <li class="breadcrumb-item" aria-current="page">Articles </li>

                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="javascript:0" class="media  btn btn-sm btn-outline-warning" data-toggle="modal" data-target="#course-model-{{$course->id}}">Course Details</a>

                </div>
            </div>
            <div class="row">
                <div class="col-12 ">
                    <div class="card card-default">
                        <div class="card-header card-header-border-bottom d-flex justify-content-between">
                            <h2>
                                <span class="mdi mdi-lead-pencil  "></span> Course Articles Table
                            </h2>
                            <div id="exportsControllersPhotos">
                            </div>
                        </div>

                        <div class="card-body">
                            @IFACAN('delete_article')
                            <form method="post" action="{{route('course.article.multi.delete')}}" id="delete-all-selected-check-box-for-photos">
                                @csrf
                                @method('delete')
                            </form>
                            @ENDACAN
                            <table id="data-table-photo" class="table nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    <th>
                                        <div class="custom-control custom-checkbox ">
                                            @IFACAN('delete_article')
                                            <input type="hidden" id="state_photo" value="off">
                                            <input type="checkbox"  class="custom-control-input" id="select-all-photos" onclick="selectOrUnselectAllPhotos()">
                                            @ENDACAN
                                            <label class="custom-control-label" for="select-all-photos"></label>
                                        </div>
                                    </th>
                                    <th class="not-sortable">Photo</th>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Instructor</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($articles as $article)
                                    <tr>
                                        <td>
                                            <div class='custom-control custom-checkbox mb-3'>
                                                @IFACAN('delete_article')
                                                <input type='checkbox'  class='custom-control-input photo-select' name='photos_id[]' value='{{$article->id}}' id='photo-{{$article->id}}'>
                                                @ENDACAN
                                                <label class='custom-control-label' for='photo-{{$article->id}}'></label>
                                            </div>
                                        </td>
                                        <td>
                                            <img class='rounded-circle w-45' style='margin-top: 0px;padding-top:0px;height: 45px' src='{{route('UPLOADED.FILES',['courses_files',$article->photo,$course->id,'photos'])}}' alt='course article main  image'>
                                        </td>
                                        <td>{{$article->id}}</td>
                                        <td>{{$article->title}}</td>
                                        <td>{{$article->slug}}</td>
                                        <td>{{$article->course->instructor->name}}</td>
                                        <td>
                                            @IFACAN('delete_article')
                                            <button class='btn btn-sm btn-outline-danger' onclick="RemoveElement('remove-photo-{{$article->id}}')">Remove</button>
                                            @ENDACAN
                                            @IFACAN('update_article')
                                            <a class='btn btn-sm btn-outline-success' href="{{route('course.article.edit',$article->id)}}">Edit</a>
                                            @ENDACAN
                                        </td>
                                        @IFACAN('delete_article')
                                        <form method='post' action='{{route('course.article.destroy',$article->id)}}' id='remove-photo-{{$article->id}}'>
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




        <div class="modal fade" id="course-model-{{$course->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header justify-content-end border-bottom-0">
                        @IFACAN('update_courses')
                        <a type="button" class="btn-edit-icon" href="{{route('course.edit',$course->id)}}">
                            <i class="mdi mdi-pencil"></i>
                        </a>
                        @ENDACAN
                        <button type="button" class="btn-close-icon" data-dismiss="modal" aria-label="Close" >
                            <i class="mdi mdi-close" onclick="closeModel()"></i>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="row no-gutters">
                            <div class="col-md-6">
                                <div class="profile-content-left px-4">
                                    <div class="card text-center widget-profile px-0 border-0">
                                        <video  controls style="height: 200px;border-radius: 15px" id="video-model-{{$course->id}}" >
                                            <source class='rounded-circle w-45' src="{{route('UPLOADED.FILES',['courses_files',$course->video,$course->id,null])}}"  alt="Course Intro Video" >
                                        </video>
                                        <div class="card-body">
                                            <img class='rounded-circle w-45' style='margin-top: 0px;padding-top:0px;height: 45px' src='{{route('UPLOADED.FILES',['courses_files',$course->photo,$course->id,'photos'])}}' alt='course additional  image'>
                                            <h4 class="py-2 text-dark">Course Name
                                                <span class="text-danger"> {{$course->name}}</span>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="contact-info px-4">
                                    <h4 class="text-dark mb-1">Course Details</h4>
                                    <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Name</span> <span >{{$course->name}}</span>
                                    <br>
                                    <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Code</span> <span>{{$course->code}}</span>
                                    <br>
                                    <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Department</span><span>{{$course->department->university->name.' University /'.$course->department->name}} Department</span>
                                    <br>
                                    <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Instructor</span><span>{{$course->instructor->name}}</span>
                                    <br>
                                    <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Slug</span> <span>{{$course->slug}}</span>
                                    <br>
                                    <span class="text-dark font-weight-medium pt-4 mb-2">
                                                                            <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Price</span> <span >{{($course->price==0)?'Free':$course->price.' '.DefaultCurrency()}}</span>
                                    <p class="text-dark font-weight-medium pt-4 mb-2">
                                        <span class="mdi mdi-chevron-right-box mdi-18px text-danger"></span> Description</p>
                                    <span>{!! $course->description !!}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>




@endsection
