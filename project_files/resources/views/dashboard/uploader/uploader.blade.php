
@extends('dashboard.layouts.app')
@section('title')
    Uploader Manager
@endsection

@section('content')

    <div class="content-wrapper">
        <div class="content">
            <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between __web-inspector-hide-shortcut__">
                <div>
                    <h1>Uploader Manager</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Upload New File</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    @if($model=='course')
                        <a class="btn btn-sm btn-primary" href="{{url('en/dashboard/'.$model.'/photos/'.$id)}}">Go Back</a>
                        @else
                        <a class="btn btn-sm btn-primary" href="{{url('en/dashboard/'.$model.'/'.$id)}}">Go Back</a>
                    @endif

                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card card-default">
                        <div class="card-body">
                            @include('dashboard.layouts.validation_error')
                            <form action="{{route('upload',[$model,$id])}}" method="post" class="dropzone dz-clickable" enctype="multipart/form-data">
                                @csrf
                                <div class="dz-default dz-message">
                                    <input type="hidden" id="model" value="{{$model}}">
                                    <input type="hidden" id="id" value="{{$id}}">
                                    <button class="dz-button" type="button">Drop files here to upload</button>
                                </div>
                            </form>
                            @push('scripts')
                                <script type="text/javascript">
                                    Dropzone.options.dropzone =
                                        {
                                            maxFilesize: 12,
                                            renameFile: function(file) {
                                                var dt = new Date();
                                                var time = dt.getTime();
                                                return time+file.name;
                                            },
                                            // acceptedFiles: ".jpeg,.jpg,.png,.gif,pdf,doc,docs",
                                            acceptedFiles: ".jpeg,.jpg,.png,.gif",
                                            addRemoveLinks: true,
                                            timeout: 5000,
                                            success: function(file, response)
                                            {
                                                console.log(response);
                                            },
                                            error: function(file, response)
                                            {
                                                return false;
                                            }
                                        };
                                </script>
                                @endpush
                        </div>
                    </div>

                </div>


            </div>

        </div>

@endsection


