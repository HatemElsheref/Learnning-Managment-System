@extends('dashboard.layouts.app')
@section('title')
    Add New Category
@endsection

@section('content')

    @push('scripts')
        <script src="{{Dashboard_Assets()}}/js/editor/ckeditor.js"></script>
        <script>CKEDITOR.replace('editor1',{height:"100px"}); </script>
    @endpush
    <div class="content-wrapper">
        <div class="content">
            <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between __web-inspector-hide-shortcut__">
                <div>
                    <h1>Categories</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('category.index')}}">Category</a></li>
                            <li class="breadcrumb-item" aria-current="page">Add New Category</li>
                        </ol>
                    </nav>
                </div>
                <div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card card-default">
                        <div class="card-body">
                            @include('dashboard.layouts.validation_error')
                            <form action="{{route('category.store')}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Category Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}" placeholder="Enter Category Name">
                                </div>
                                <div class="form-group">
                                    <label for="phone">Category Description</label>
                                    <textarea id="editor1" name="description">{!! old('description') !!}</textarea>
                                </div>
                                <div class="form-group row ">
                                    <label for="photo" class="col-sm-4 col-lg-2 col-form-label">Category Photo</label>
                                    <div class="col-sm-12 col-lg-12">
                                        <div class="custom-file mb-1">
                                            <input type="file" class="custom-file-input" id="photo" name="photo">
                                            <label class="custom-file-label" for="coverImage">Choose file...</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label for="photo" class="col-sm-4 col-lg-2 col-form-label">Category Video</label>
                                    <div class="col-sm-12 col-lg-12">
                                        <div class="custom-file mb-1">
                                            <input type="file" class="custom-file-input" id="video" name="video">
                                            <label class="custom-file-label" for="video">Choose file...</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-footer pt-4 pt-2 mt-2 border-top">
                                    <button type="submit" class="btn btn-primary btn-default">Save</button>

                                </div>
                            </form>
                        </div>
                    </div>

                </div>


            </div>

        </div>

@endsection


