@extends('dashboard.layouts.app')

@section('title'){{$course->name}}  Course Details @endsection

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

            let datatable=$('.lessons-table').DataTable({
                lengthMenu: [ [5,10, 25, 50, -1], [5,10, 25, 50, "All"] ],
                deferRender: false,
                columnDefs: [
                    {orderable: false, targets: [0,3,5]}
                ]
                ,dom: '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">'
            });

            new $.fn.dataTable.Buttons( datatable, {

                buttons: [
                    @IFACAN('create_lessons')
                    {
                        text: "Add New Lesson",
                        className:"btn btn-sm btn-primary",
                        action: function (e, dt, node, config) {
                            document.location.href='{{route('lesson.create',$course->id)}}';
                        }
                    }    @ENDACAN
                    @IFACAN('create_parts')
                    , {
                        text: "Add New Course Parts",
                        className: "btn btn-sm btn-primary",
                        action: function (e, dt, node, config) {
                            {{--document.location.href = '{{route('lesson.create',$course->id)}}';--}}
                            coursePartModalForStore('Add New Course Part','post');
                        }
                    }  @ENDACAN
                ]});
            datatable.buttons(0,null).container().appendTo('#exportsControllers',datatable.table().container());
        });

    </script>
    <script>
        function coursePartModalForUpdate(title,id,name,method) {
            let action='{{url('/')}}'+'/'+'{{app()->getLocale()}}'+'/dashboard/course/part/delete/'+id;
            $('#remove_part').attr('action',action);
            $('#course-part-operation-title').html(title);
            let route='{{url('/')}}'+'/'+'{{app()->getLocale()}}'+'/dashboard/course/part/update/'+id;
            $('#update-course-part-form').attr('action',route);
            $('#method_type').val(method);
            $('#part_name').attr('value',name);
            $('#remove-part-btn').attr('hidden',false);
            $('#course-part-modal').modal('show');
        }
        function coursePartModalForStore(title,method) {
            $('#course-part-operation-title').html(title);
            let route='{{url('/')}}'+'/'+'{{app()->getLocale()}}'+'/dashboard/course/part/store';
            $('#update-course-part-form').attr('action',route);
            $('#method_type').val(method);
            $('#part_name').attr('value','');
            $('#remove-part-btn').attr('hidden',true);
            $('#course-part-modal').modal('show');
        }
    </script>
@endpush
@section('content')
    <div class="content-wrapper">
        <div class="content">
            <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between __web-inspector-hide-shortcut__">
                <div>
                    <h1> {{ucfirst(strtolower($course->name))}}  Course</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('course.index')}}">Courses</a></li>
                            <li class="breadcrumb-item" aria-current="page">{{ucfirst(strtolower($course->name))}} </li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="javascript:0" class="media  btn btn-sm btn-outline-warning" data-toggle="modal" data-target="#course-model-{{$course->id}}">Course Details</a>

                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-lg-6 col-xl-2">
                    <div class="card widget-block p-4 rounded bg-primary border">
                        <div class="card-block">
                            <i class="mdi mdi-account-outline mr-4 text-white"></i>
                            @if($course->isFree())
                                <h4 class="text-white my-2"> Free </h4>
                            @else
                                <h4 class="text-white my-2"> {{$users}}</h4>
                            @endif
                            <p >
                                <a href="#list-lessons" class="text-white">
                                    <span class="mdi mdi-link"></span>
                                    Students
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-2">
                    <div class="card widget-block p-4 rounded bg-danger border">
                        <div class="card-block">
                            <i class="mdi mdi-play-circle  mr-4 text-white"></i>
                            <h4 class="text-white my-2">
                                @php
                                    $number=0;
                                    foreach($course->parts as $part){
                                    $number+=count($part->lessons);
                                    }
                                    echo $number;
                                @endphp
                            </h4>
                            <p class="text-white">
                                Lessons
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-2">
                    <div class="card widget-block p-4 rounded bg-warning border">
                        <div class="card-block">
                            <i class="mdi mdi-file-multiple mr-4 text-white"></i>
                            <h4 class="text-white my-2">
                                @php
                                    $number=0;
                                    foreach($course->parts as $part){
                                        foreach ($part->lessons as $lesson){
                                             $number+=count($lesson->files);
                                        }
                                    }
                                echo $number;
                                @endphp
                            </h4>
                            <p class="text-white">Lessons Files</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-2">
                    <div class="card widget-block p-4 rounded bg-secondary border">
                        <div class="card-block">
                            <i class="mdi mdi-file-compare mr-4 text-white"></i>
                            <h4 class="text-white my-2">
                                {{count($course->files)}}
                            </h4>
                            <a href="{{route('exams.show',$course->id)}}" class="text-white">
                                <span class="mdi mdi-link"></span>
                                <span class="text-white">Exams</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-2">
                    <div class="card widget-block p-4 rounded bg-success border">
                        <div class="card-block">
                            <i class="mdi mdi-folder-multiple-image mr-4 text-white"></i>
                            <h4 class="text-white my-2">
                                {{$photos}}
                            </h4>
                            <a href="{{route('course.photos',$course->id)}}" class="text-white">
                                <span class="mdi mdi-link"></span>
                                <span class="text-white">Photos</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-2">
                    <div class="card widget-block p-4 rounded bg-info border">
                        <div class="card-block">
                            <i class="mdi mdi-lead-pencil  mr-4 text-white"></i>
                            <h4 class="text-white my-2">
                                {{count($course->articles)}}
                            </h4>
                            <a href="{{route('course.article.show',$course->id)}}" class="text-white">
                                <span class="mdi mdi-link"></span>
                                <span class="text-white">Articles</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-12">
                    @include('dashboard.layouts.validation_error')
                </div>
            </div>
            <div class="row"  >
                @IFACAN('read_lessons')
                <div class="col-lg-12">
                    <div class="card card-default">
                        <div class="card-header card-header-border-bottom justify-content-between">

                            <h2>

                                <span class="mdi mdi-play-circle"></span>
                                Course Parts & Lessons
                            </h2>
                            <div id="exportsControllers">

                            </div>
                            @if(count($course->parts)==0)
                                <button class="btn btn-sm btn-primary" onclick=" coursePartModalForStore('Add New Course Part','post');">Add New Course Part</button>
                            @endif
                        </div>


                        <div class="card-body">
                            <div class="row ">
                                <div class="col-sm-3">
                                    <ul class="nav nav-pills nav-stacked flex-column ">
                                        @foreach($course->parts as $part)

                                            <li class="nav-item" style="height:35px">
                                                @IFACAN('update_parts')
                                                <button class="btn btn-sm btn-outline-success" onclick="coursePartModalForUpdate('Edit Course Part','{{$part->id}}','{{$part->name}}','put')">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                                @ENDACAN
                                                <a href="#part-{{$part->id}}" class="nav-link @if($loop->first) active @endif" data-toggle="tab" aria-expanded="true" style="width: 80%;float: right;padding:5px">
                                                    {{$part->name}}
                                                    <span class="badge badge-sm badge-pill badge-primary" style="float: right">
                                                    {{count($part->lessons)}}
                                                </span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="tab-content col-sm-9 ">
                                    @foreach($course->parts as $part)
                                        <div class="tab-pane fade  @if($loop->first) active show @endif" id="part-{{$part->id}}" aria-expanded="true">
                                            <div class="col-12 ">
                                                <table  class=" lessons-table table nowrap" style="width:100%">
                                                    <thead>
                                                    <tr>
                                                        <th class="not-sortable">Video</th>
                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Is Free</th>
                                                        <th>Files</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($part->lessons as $lesson)
                                                        <tr>
                                                            <td>
                                                                <a href="javascript:0" class="media " data-toggle="modal" data-target="#modal-video-lesson-{{$lesson->id}}"><span class="mdi mdi-play-circle mdi-24px"></span></a>
                                                            </td>
                                                            <td>{{$lesson->id}}</td>
                                                            <td>{{$lesson->name}}</td>
                                                            <td>
                                                                @if($lesson->isPublished())
                                                                    <span class="mdi mdi-checkbox-marked-circle text-success mdi-24px"></span>
                                                                @else
                                                                    <span class="mdi mdi-close-circle text-danger mdi-24px"></span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                {{count($lesson->files)}}
                                                            </td>
                                                            <td>
                                                                @IFACAN('delete_lessons')
                                                                <button class='btn btn-sm btn-outline-danger' onclick="RemoveElement('remove-lesson-{{$lesson->id}}')">Remove</button>
                                                                @ENDACAN
                                                                @IFACAN('update_lessons')
                                                                <a class='btn btn-sm btn-outline-success' href="{{route('lesson.edit',$lesson->id)}}">Edit</a>
                                                                @ENDACAN
                                                                @IFACAN('read_lesson_reviews')
                                                                <a class='btn btn-sm btn-outline-warning' href="{{route('lesson.reviews',$lesson->id)}}">Reviews</a>
                                                                @ENDACAN
                                                                <a class='btn btn-sm btn-outline-primary' href="{{route('lesson.show',$lesson->id)}}">Manage</a>
                                                            </td>
                                                            @IFACAN('delete_lessons')
                                                            <form method='post' action='{{route('lesson.destroy',$lesson->id)}}' id='remove-lesson-{{$lesson->id}}'>
                                                                @csrf
                                                                @method('delete')
                                                            </form>
                                                            @ENDACAN
                                                            <div class="modal fade stop-video   " id="modal-video-lesson-{{$lesson->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header justify-content-end border-bottom-0">
                                                                            <a type="button" class="btn-edit-icon" href="{{route('lesson.edit',$lesson->id)}}">
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
                                                                                            @if($lesson->type=='youtube')
                                                                                                <iframe style="height: 400px;border-radius: 15px"   class="yt-frame" src="https://www.youtube.com/embed/{{$lesson->video}}?controls=0" frameborder="0" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                                                            @elseif($lesson->type=='local')
                                                                                                <video  controls style="height: 400px;border-radius: 15px" id="video-model-{{$course->id}}" >
                                                                                                    <source class='rounded-circle w-45' src="{{route('UPLOADED.FILES',['courses_files',$lesson->video,$course->id,null])}}"  alt="Course Lesson Video" >
                                                                                                </video>
                                                                                                @elseif($lesson->type=='drive')
                                                                                                <iframe style="height: 400px;border-radius: 15px" src="{{$lesson->video}}" ></iframe>
                                                                                            @else
                                                                                                <video  controls style="height: 400px;border-radius: 15px" id="video-model-{{$course->id}}" >
                                                                                                    <source class='rounded-circle w-45' src="{{$lesson->video}}"  alt="Course Lesson Video" >
                                                                                                </video>
                                                                                            @endif
                                                                                            <div class="card-body">
                                                                                                <h4 class="py-2 text-dark">Lesson Name
                                                                                                    <span class="text-danger"> {{$lesson->name}}</span>
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

                                                        </tr>

                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade " id="course-part-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormTitle" aria-modal="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="course-part-operation-title"></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                    <form method="post" id="update-course-part-form">
                                        @csrf
                                        <input type="hidden" name="_method" id="method_type" value="post">
                                        <div class="form-group">
                                            <input type="hidden" name="course_id" value="{{$course->id}}">
                                            <label for="exampleInputEmail1">Course Part Name</label>
                                            <input type="text" name="part_name" class="form-control" id="part_name" aria-describedby="part_name" placeholder="Enter Course Part Name">
                                            <small id="part_name" class="form-text text-muted">Name Must Be Unique For One Course</small>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <form method="post" action="" id="remove_part">
                                        @csrf
                                        @method('delete')
                                    </form>
                                    @IFACAN('delete_parts')
                                    <button type="button" id="remove-part-btn" class="btn btn-danger btn-sm" onclick="RemoveElement('remove_part')">Remove Part</button>
                                    @ENDACAN
                                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                @ENDACAN

                @if(SeoFeaturesMode())
                    <div class="col-12" >
                        <div class="card card-default">
                            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                                <h2>
                                    <span class="mdi mdi-google-analytics"></span> Seo Management
                                </h2>
                            </div>
                            <div class="card-body">
                                <form action="{{route('course.seo',$course->id)}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                    @csrf
                                    @method('put')
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="slug">Slug</label>
                                                <input type="text" class="form-control" id="slug" name="slug" value="{{$course->slug}}" placeholder="Enter Course Slug">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="meta_title">Title</label>
                                                <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{$course->meta_title}}" placeholder="Enter Course Meta Title">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="meta_keywords">Keywords</label>
                                                <div class="form-group" >
                                                    <select class="key-words form-control " name="meta_keywords[]" multiple id="meta_keywords">
                                                        @foreach((array)json_decode($course->meta_keywords) as $keyword)
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
                                                <input type="text" class="form-control" id="meta_description" name="meta_description" value="{{$course->meta_description}}" placeholder="Enter Course Meta Description">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-footer pt-4 pt-2 mt-2 border-top">
                                        @IFACAN('update_courses')
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




        <div class="modal fade stop-video" id="course-model-{{$course->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
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
                                        @if ($course->intro=='local')
                                            <video  controls style="height: 200px;border-radius: 15px" id="video-model-{{$course->id}}" >
                                                <source class='rounded-circle w-45' src="{{route('UPLOADED.FILES',['courses_files',$course->video,$course->id,null])}}"  alt="Course Intro Video" >
                                            </video>
                                        @else
                                            <iframe class="yt-frame" style="height: 200px;border-radius: 15px"  src="https://www.youtube.com/embed/{{$course->video}}?rel=0&controls=0&hd=1&showinfo=0&enablejsapi=1" frameborder="0" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

                                        @endif

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
