@extends('backEnd.master')
@section('mainContent')
<style type="text/css">
    #selectStaffsDiv, .forStudentWrapper{
        display: none;
    }
</style>
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.add') @lang('lang.visitor')</h1>
            <div class="bc-pages">
                <a href="{{url('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.library')</a>
                <a href="#">@lang('lang.add') @lang('lang.visitor')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($editData))
         @if(in_array(309, App\GlobalVariable::GlobarModuleLinks()) || Auth::user()->role_id == 1 )

{{--        <div class="row">--}}
{{--            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">--}}
{{--                <a href="{{url('library-member')}}" class="primary-btn small fix-gr-bg">--}}
{{--                    <span class="ti-plus pr-2"></span>--}}
{{--                    @lang('lang.add')--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        </div>--}}
        @endif
        @endif
        <div class="row">
             
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">@if(isset($editData))
                                    @lang('lang.edit')
                                @else
                                    @lang('lang.add')
                                @endif
                                @lang('lang.visitor')
                            </h3>
                        </div>
                        @if(isset($editData))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'url' => 'visitor-list/'.$editData->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                        @else
                        @if(in_array(309, App\GlobalVariable::GlobarModuleLinks()) || Auth::user()->role_id == 1 )
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'url' => 'visitor-list',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    @if(session()->has('message-success'))
                                    <div class="alert alert-success">
                                        {{ session()->get('message-success') }}
                                    </div>
                                    @elseif(session()->has('message-danger'))
                                    <div class="alert alert-danger">
                                        {{ session()->get('message-danger') }}
                                    </div>
                                    @endif

                                    {{--  @if ($errors->any())
                                        <div class="alert alert-danger">
                                            @foreach ($errors->all() as $error)
                                                {{ $error }}
                                            @endforeach
                                        </div>
                                    @endif  --}}
                                        <div class="col-lg-12 mb-30">
                                            <select class="niceSelect w-100 bb form-control{{ $errors->has('member_type') ? ' is-invalid' : '' }}" name="member_type" id="member_type1">
                                                <option data-display=" @lang('lang.member_type') *" value="">@lang('lang.member_type') *</option>
                                                @foreach($roles as $value)
                                                    @if(isset($editData))
                                                        <option value="{{$value->id}}" {{$value->id == $editData->role_id? 'selected':''}}>{{$value->full_name}}</option>
                                                    @else
                                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @if ($errors->has('member_type'))
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('member_type') }}</strong>
                                            </span>
                                            @endif
                                        </div>

                                        <div class="forStudentWrapper col-lg-12 mb-30">
                                            <select class="niceSelect w-100 bb form-control{{ $errors->has('student_name') ? ' is-invalid' : '' }}" name="student_id" id="">
                                                <option data-display=" @lang('lang.select_student') *" value="">@lang('lang.select_student') *</option>
                                                @foreach($students as $value)
                                                    @if(isset($editData))
                                                        <option value="{{$value->student_id}}" {{$value->student_id == $editData->student_id? 'selected':''}}>{{$value->Student->full_name}} || {{$value->Student->roll_no}}</option>
                                                    @else
                                                        <option value="{{$value->student_id}}">{{$value->Student->full_name}} || {{$value->Student->roll_no}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @if ($errors->has('student_id'))
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('student_id') }}</strong>
                                            </span>
                                            @endif
                                        </div>

                                        <div class="col-lg-12 mb-30" id="selectStaffsDiv">
                                            <select class="niceSelect w-100 bb form-control{{ $errors->has('staff_id') ? ' is-invalid' : '' }}" name="staff" id="selectStaffs">
                                                <option data-display="@lang('lang.name') *" value="">@lang('lang.name') *</option>

                                                @if(isset($staffsByRole))
                                                    @foreach($staffsByRole as $value)
                                                        <option value="{{$value->id}}" {{$value->id == $editData->id? 'selected':''}}>{{$value->full_name}}</option>
                                                    @endforeach
                                                @else

                                                @endif
                                            </select>
                                        </div>

{{--                                    <div class="col-lg-12 mb-30">--}}
{{--                                        <select class="niceSelect w-100 bb form-control{{ $errors->has('student_name') ? ' is-invalid' : '' }}" name="student_id" id="">--}}
{{--                                            <option data-display=" @lang('lang.select_student') *" value="">@lang('lang.select_student') *</option>--}}
{{--                                            @foreach($students as $value)--}}
{{--                                                @if(isset($editData))--}}
{{--                                                    <option value="{{$value->student_id}}" {{$value->student_id == $editData->student_id? 'selected':''}}>{{$value->Student->full_name}} || {{$value->Student->roll_no}}</option>--}}
{{--                                                @else--}}
{{--                                                    <option value="{{$value->student_id}}">{{$value->Student->full_name}} || {{$value->Student->roll_no}}</option>--}}
{{--                                                @endif--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                        @if ($errors->has('student_id'))--}}
{{--                                            <span class="invalid-feedback" role="alert">--}}
{{--                                                <strong>{{ $errors->first('student_id') }}</strong>--}}
{{--                                            </span>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
                                    <div class="col-lg-12 mb-30">
                                            <div class="input-effect">
                                                <input class="primary-input date form-control{{ $errors->has('date') ? ' is-invalid' : '' }} {{isset($date)? 'read-only-input': ''}}" id="startDate" type="text"
                                                       name="date" autocomplete="off" value="{{isset($editData)? $editData->date: date('d-m-Y')}}">
                                                <label for="startDate">@lang('lang.date')*</label>
                                                <span class="focus-border"></span>

                                                @if ($errors->has('date'))
                                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('date') }}</strong>
                                            </span>
                                                @endif
                                            </div>
                                    </div>
                                    <div class="col-lg-12 mb-30">
                                        <div class="input-effect">
                                            <input class="primary-input time form-control{{ @$errors->has('start_time') ? ' is-invalid' : '' }}" type="text" name="start_time" value="{{isset($editData) ? $editData->start_time : ''}}">
                                            <label>@lang('lang.start_time') *</label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('start_time'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ @$errors->first('start_time') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                    </div>
                                    <div class="col-lg-12 mb-30">
                                        <div class="input-effect">
                                            <input class="primary-input time form-control{{ @$errors->has('end_time') ? ' is-invalid' : '' }}" type="text" name="end_time" value="{{isset($editData) ? $editData->end_time : ''}}">
                                            <label>@lang('lang.end_time') *</label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('end_time'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ @$errors->first('end_time') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                    </div>
                                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">

                                </div>
                                 @php
                                  $tooltip = "";
                                  if(in_array(309, App\GlobalVariable::GlobarModuleLinks()) || Auth::user()->role_id == 1 ){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                       <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="{{$tooltip}}"id="save">
                                            <span class="ti-check"></span>

                                            @if(isset($editData))
                                                @lang('lang.update')
                                            @else
                                                @lang('lang.save')
                                            @endif
                                            @lang('lang.visitor')

                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
              <div class="row">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title">
                        <h3 class="mb-0">@lang('lang.visitor_list')</h3>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-12">
                    <table id="table_id" class="display school-table" cellspacing="0" width="100%">

                          <thead>
                            @if(session()->has('message-success-delete') != "" ||
                                session()->get('message-danger-delete') != "")
                            <tr>
                                <td colspan="6">
                                     @if(session()->has('message-success-delete'))
                                      <div class="alert alert-success">
                                          {{ session()->get('message-success-delete') }}
                                      </div>
                                    @elseif(session()->has('message-danger-delete'))
                                      <div class="alert alert-danger">
                                          {{ session()->get('message-danger-delete') }}
                                      </div>
                                    @endif
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <th>@lang('lang.visitor_id')</th>
                                <th>@lang('lang.visitor_name')</th>
                                <th>@lang('lang.date')</th>
                                <th>@lang('lang.start_time')</th>
                                <th>@lang('lang.end_time')</th>
                                <th>@lang('lang.date_of_birth')</th>
                                <th>@lang('lang.member_type')</th>
                                <th>@lang('lang.major')</th>
                                <th>@lang('lang.mobile')</th>
{{--                                <th>@lang('lang.action')</th>--}}
                            </tr>
                        </thead>

                        <tbody>
                        @if(isset($visitors))
                            @foreach($visitors as $value)
                                <tr>
                                    <td>
                                        <?php
                                        $student_name = \App\SmStudent::find($value->student_id);
                                        $parent_name = \App\SmParent::find($value->student_id);
                                        $staff_name = \App\SmStaff::find($value->student_id);
                                        if ($value->member_type == '2'){
                                            if(!empty($student_name->roll_no)) { echo $student_name->roll_no; }
                                        }elseif($value->member_type == '3'){
                                            if(!empty($parent_name->id) && !empty($parent_name->id)) { echo $parent_name->id; }
                                        }else{
                                            if(!empty($value->staffDetails) && !empty($value->staffDetails->staff_no)) { echo $value->staffDetails->staff_no; }
                                        }
                                        ?>

                                    </td>
                                    <td>
                                        <?php
                                            if ($value->member_type == '2'){
                                                if(!empty($student_name->full_name)) { echo $student_name->full_name; }
                                            }elseif($value->member_type == '3'){
                                                if(!empty($parent_name->full_name) && !empty($parent_name->fathers_name)) { echo $parent_name->fathers_name; }
                                            }else{
                                                if(!empty($value->staffDetails) && !empty($value->staffDetails->full_name)) { echo $value->staffDetails->full_name; }
                                            }
                                        ?>
                                    </td>
                                    <td>{{@$value->date}}</td>
                                    <td>{{@$value->start_time}}</td>
                                    <td>{{@$value->end_time}}</td>
                                    <td>
                                        <?php
                                        if ($value->member_type == '2'){
                                            if(!empty($student_name->date_of_birth)) { echo @$value->student->date_of_birth != ""? App\SmGeneralSettings::DateConvater($value->student->date_of_birth):''; }
                                        }elseif($value->member_type == '3'){
                                            if(!empty($parent_name->date_of_birth)) { echo @$parent_name->date_of_birth != ""? App\SmGeneralSettings::DateConvater($parent_name->date_of_birth):''; }
                                            //if(!empty($parent_name->full_name) && !empty($parent_name->fathers_name)) { echo $parent_name->fathers_name; }
                                        }else{
                                            if(!empty($value->staffDetails) && !empty($value->staffDetails->date_of_birth)) { echo @$value->staffDetails->date_of_birth != ""? App\SmGeneralSettings::DateConvater($value->staffDetails->date_of_birth):''; }
                                        }
                                        ?>
                                    </td>
                                    <td>{{!empty($value->roles)?$value->roles->name:''}}</td>
                                    <td>
                                        <?php
                                            if ($value->member_type == '2'){
                                                if(!empty(@$student_name->major)) { echo @$student_name->major->major_name; }
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if($value->member_type == '2'){
                                            if(!empty($student_name->mobile)) {   echo $student_name->mobile;}
                                        }elseif($value->member_type == '3'){
                                            if(!empty($value->parentsDetails) && !empty($value->parentsDetails->fathers_mobile)) {   echo $value->parentsDetails->fathers_mobile; }
                                        }else{
                                            if(!empty($value->staffDetails) && !empty($value->staffDetails->mobile)) {  echo $value->staffDetails->mobile; }
                                        }
                                        ?>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</div>
</section>
@endsection
