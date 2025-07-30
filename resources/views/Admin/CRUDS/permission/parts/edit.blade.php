@extends('Admin.layouts.inc.app')

@section('title')
    {{helperTrans('admin.Edit Permission')}}
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="d-flex flex-column-fluid">
            <div class="container-fluid">
                <div class="card card-custom">
                    <div class="card-header flex-wrap py-5">
                        <div class="card-title">
                            <h3 class="card-label">{{helperTrans('admin.Edit Permission')}}
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.permissions.update', $row->id)}}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{helperTrans('admin.Name')}} <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{$row->name}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{helperTrans('admin.Guard Name')}} <span class="text-danger">*</span></label>
                                        <input type="text" name="guard_name" class="form-control" value="{{$row->guard_name}}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">{{helperTrans('admin.Save')}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
