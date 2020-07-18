@extends('dashboard.layouts.app')

@section('title')
    @lang('dashboard.index_admin')
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content">

            <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between __web-inspector-hide-shortcut__">
                <div>
                    <h1>@lang('dashboard.admins')</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 breadcrumb-inverse">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><span class="mdi mdi-home"></span></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">@lang('dashboard.dashboard')</a></li>
                            <li class="breadcrumb-item" aria-current="page">@lang('dashboard.admins')</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    @IFACAN('create_admins')
                    <a class="btn btn-sm btn-primary" href="{{route('admin.create')}}">Add New Staff</a>
                    @ENDACAN
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <!-- Recent Order Table -->
                    <div class="card card-table-border-none" id="recent-orders">
                        {{--<div class="card-header justify-content-between">
                            <h2>Staff Table <span class="badge badge-pill badge-sm badge-info">{{count($staffs)}}</span></h2>
                         <a class="btn btn-sm btn-primary" href="{{route('admin.create')}}">Add New Staff</a>
                        </div>  --}}
                        <div class="card-body pt-0 pb-5">
                            <table class="table card-table table-responsive table-responsive-large" style="width:100%">
                                <thead>
                                <tr>
                                    <th>Staff ID</th>
                                    <th>Staff Avatar</th>
                                    <th>Staff Name</th>
                                    <th>Staff Email</th>
                                    <th>Staff Gender</th>
                                    <th>Staff Phone</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($staffs as $staff)
                                    <tr>
                                        <td class="text-dark">{{$staff->id}}</td>
                                        <td>
                                            <div class="media">
                                                <div class="media-image mr-3 rounded-circle">
                                                    <img class="rounded-circle w-45"  src="{{route('UPLOADED.FILES',['admins_avatars',$staff->avatar,null,null])}}" alt="user image" width="100%" height="100%" style="width: 45px;height: 45px;" >
                                                </div>

                                            </div>
                                        </td>
                                        <td class="text-dark">{{$staff->name}}</td>
                                        <td class="text-dark">{{$staff->email}}</td>
                                        <td class="text-dark">{{ucfirst($staff->gender)}}</td>
                                        <td class="text-dark">{{$staff->phone}}</td>
                                        <td class="text-right">
                                            <div class="dropdown show d-inline-block widget-dropdown">
                                                <a class="dropdown-toggle icon-burger-mini" href="" role="button" id="dropdown-recent-order1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static"></a>
                                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-recent-order1">
                                                    @IFACAN('read_admins')
                                                    <li class="dropdown-item"><a href="{{route('admin.show',$staff->id)}}">View</a></li>
                                                    @ENDACAN
                                                    @IFACAN('update_admins')
                                                    <li class="dropdown-item"><a href="{{route('admin.edit',$staff->id)}}">Edit</a></li>
                                                    @ENDACAN
                                                    @IFACAN('delete_admins')
                                                    <li class="dropdown-item"><a href="#" onclick="RemoveElement('remove-admin-{{$staff->id}}')">Remove</a></li>
                                                    @ENDACAN
                                                    <form id="remove-admin-{{$staff->id}}" method="post" action="{{route('admin.destroy',$staff->id)}}">
                                                        @csrf @method('delete')
                                                    </form>
                                                </ul>
                                            </div>
                                        </td>
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
