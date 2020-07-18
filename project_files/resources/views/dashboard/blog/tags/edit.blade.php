@extends('dashboard.layouts.app')
@section('title')
   Edit Tag
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content">
            <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between __web-inspector-hide-shortcut__">
                <div>
                    <h1>Tags</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('tag.index')}}">Tags</a></li>
                            <li class="breadcrumb-item" aria-current="page">Edit Tag</li>
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
                            <form action="{{route('tag.update',$tag->id)}}" method="post"  autocomplete="off">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <label for="name">Tag Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{$tag->name}}" placeholder="Enter Tag Name">
                                </div>
                                <div class="form-footer pt-4 pt-2 mt-2 border-top">
                                    <button type="submit" class="btn btn-success btn-default">Edit</button>

                                </div>
                            </form>
                        </div>
                    </div>

                </div>


            </div>

        </div>

@endsection


