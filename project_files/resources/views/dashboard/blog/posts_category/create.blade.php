@extends('dashboard.layouts.app')
@section('title')
    Add New Category
@endsection

@section('content')
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


