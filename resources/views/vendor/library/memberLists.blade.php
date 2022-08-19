@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
  <div class="container-fluid">
    <div class="row justify-content-between">
      <h1>@lang('lang.issue_books')</h1>
      <div class="bc-pages">
        <a href="{{url('dashboard')}}">@lang('lang.dashboard')</a>
        <a href="#">@lang('lang.library')</a>
        <a href="{{url('member-list')}}">@lang('lang.member') @lang('lang.list')</a>
      </div>
    </div>
  </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
  <div class="container-fluid p-0">

    <div class="row mt-40">
      <div class="col-lg-12">
        @include('backEnd.partials.alertMessage')
        <div class="row">
         <div class="col-lg-12">
          <table id="table_id" class="display school-table" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th width="15%">@lang('lang.member') @lang('lang.id')</th>
                <th width="15%">@lang('lang.full_name')</th>
                <th width="15%">@lang('lang.member_type')</th>
                <th width="15%">@lang('lang.phone')</th>
                <th width="15%">@lang('lang.major')</th>
                <th width="15%">@lang('lang.action')</th>
              </tr>
            </thead>

            <tbody>
               @foreach($activeMembers as $value)
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
{{--                <td>{{$value->member_ud_id}}</td>--}}

                {{-- @if($value->member_type == 2)
                @php
                $getMemberDetail =
                App\SmBook::getMemberDetails($value->student_staff_id);
                @endphp
                @else

                @php
                $getMemberDetail =
                App\SmBook::getMemberStaffsDetails($value->student_staff_id);
                @endphp
                @endif --}}
                <td>

                  @if($value->member_type == '2')
                      {{$value->full_name != ""? $value->full_name:''}}
{{--                    {{dd($value)}}--}}
                  @else
                      {{$value->staffDetails != ""? $value->staffDetails->full_name:''}}
                  @endif

                </td>

                <td>{{@$value->memberTypes->name}}</td>
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
                <td>
                  <?php
                  if ($value->member_type == '2'){
                    if(!empty(@$student_name->major)) { echo @$student_name->major->major_name; }
                  }
                  elseif($value->member_type == '4'){
                    if(!empty(@$staff_name->position)) { echo @$staff_name->position; }
                    }
                  ?>
                </td>
                <td>
                  <div class="dropdown">
                    <a class="primary-btn fix-gr-bg" href="{{url('issue-books/'.$value->member_type.'/'.$value->student_id)}}">@lang('lang.issue_return_Book')</a>
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
  </section>
  @endsection
