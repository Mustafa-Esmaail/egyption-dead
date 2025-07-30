@extends('Admin.layouts.inc.app')

@section('title')
{{helperTrans('admin.Edit Award Redeem Request')}}
@endsection

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title">
                        <h3 class="card-label">{{helperTrans('admin.Edit Award Redeem Request')}}
                        </h3>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('award-redeem-requests.update', $data['request']->id)}}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{helperTrans('admin.User')}} <span class="text-danger">*</span></label>
                                    <select name="user_id" class="form-control" required>
                                        <option value="">{{helperTrans('admin.Choose')}}</option>
                                        @foreach($data['users'] as $user)
                                        <option value="{{$user->id}}" {{$data['request']->user_id == $user->id ?
                                            'selected' : ''}}>
                                            {{$user->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{helperTrans('admin.Award')}} <span class="text-danger">*</span></label>
                                    <select name="award_id" class="form-control" required>
                                        <option value="">{{helperTrans('admin.Choose')}}</option>
                                        @foreach($data['awards'] as $award)
                                        <option value="{{$award->id}}" {{$data['request']->award_id == $award->id ?
                                            'selected' : ''}}>
                                            {{$award->title}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{helperTrans('admin.User Points')}} <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="user_points" class="form-control"
                                        value="{{$data['request']->user_points}}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{helperTrans('admin.Status')}} <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control" required>
                                        <option value="pending" {{$data['request']->status == 'pending' ? 'selected' :
                                            ''}}>
                                            {{helperTrans('admin.Pending')}}
                                        </option>
                                        <option value="approved" {{$data['request']->status == 'approved' ? 'selected' :
                                            ''}}>
                                            {{helperTrans('admin.Approved')}}
                                        </option>
                                        <option value="rejected" {{$data['request']->status == 'rejected' ? 'selected' :
                                            ''}}>
                                            {{helperTrans('admin.Rejected')}}
                                        </option>
                                    </select>
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