@extends('backEnd.master')
@section('mainContent')
    <section class="sms-breadcrumb mb-50 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('lang.book_list')</h1>
                <div class="bc-pages">
                    <a href="{{url('dashboard')}}">@lang('lang.dashboard')</a>
                    <a href="#">@lang('lang.library')</a>
                    <a href="#">@lang('lang.book_list')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row mt-50">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table_id" class="display school-table mt-sm-20 mt-md-30" cellspacing="0"
                                   width="100%">

                                <thead>
                                @if(session()->has('message-success') != "" ||
                                    session()->get('message-danger') != "")
                                    <tr>
                                        <td colspan="10">
                                            @if(session()->has('message-success'))
                                                <div class="alert alert-success">
                                                    {{ session()->get('message-success') }}
                                                </div>
                                            @elseif(session()->has('message-danger'))
                                                <div class="alert alert-danger">
                                                    {{ session()->get('message-danger') }}
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    {{--                                <th>@lang('lang.sl')</th>--}}
                                    <th>@lang('lang.book_title')</th>
                                    <th>@lang('lang.author_name')</th>
                                    <th>@lang('lang.publisher_year')</th>
                                    <th>@lang('lang.publisher')</th>
                                    <th>@lang('lang.book') @lang('lang.no')</th>
                                    <th>@lang('lang.isbn') @lang('lang.no')</th>
                                    <th>@lang('lang.category')</th>
                                    <th>@lang('lang.owner_name')</th>
                                    <th>@lang('lang.donner_name')</th>
                                    <th>@lang('lang.quantity')</th>
                                    <th>@lang('lang.price')</th>
                                    <th>@lang('lang.doi')</th>
                                    <th>@lang('lang.remarks')</th>
                                    <th>@lang('lang.photos')</th>
                                    <th>@lang('lang.action')</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php $count=1; @endphp
                                @foreach($books as $value)
                                    <tr>
                                        {{--                                <td>{{$count++}}</td>--}}
                                        <td>{{$value->book_title}} </td>
                                        <td>{{$value->author_name}}</td>
                                        <td>{{$value->year_publisher}}</td>
                                        <td>{{$value->publisher_name}}</td>
                                        <td>{{$value->book_number}}</td>
                                        <td>{{$value->isbn_no}}</td>
                                        <td>
                                            @if(!empty($value->book_category_id))
                                                {{(@$value->book_category_id != "")? $value->category_name:'' }}
                                            @endif
                                        </td>
                                        {{-- <td>
                                        @if(!empty($value->subject_id))
                                            {{(@$value->subject_id != "")? $value->subject_name:'' }}
                                        @endif
                                        </td> --}}
                                        <td>{{$value->owner_name}}</td>
                                        <td>{{$value->donner_name}}</td>
                                        <td>{{$value->quantity}}</td>
                                        <td>{{$value->book_price}}</td>
                                        <td>{{$value->doi}}</td>
                                        @if($value->remark != null)
                                            <td style="color: whitesmoke;color: red !important;">{{$value->remark}}</td>
                                        @else
                                            <td>{{$value->remark}}</td>
                                        @endif

                                        <td><a href="{{asset($value->photos)}}">
<!--                                                --><?php
//                                                    $photos = pathinfo($value->photos);
//
//                                                   $extension = $photos['extension'];
//                                                   // dd($extension);
//
//                                                ?>
{{--                                                @if($extension == 'pdf')--}}
{{--                                                    <img src="{{'public/uploads/library/photos/download.jpg'}}" width="60px" height="50px">--}}
{{--                                                @else--}}
                                                    <img src="{{asset($value->photos)}}" width="60px" height="50px">
{{--                                                @endif--}}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn dropdown-toggle"
                                                        data-toggle="dropdown">
                                                    @lang('lang.select')
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    @if(in_array(302, App\GlobalVariable::GlobarModuleLinks()) || Auth::user()->role_id == 1 )
                                                        <a class="dropdown-item"
                                                           href="{{url('edit-book/'.$value->id)}}">@lang('lang.edit')</a>
                                                    @endif
                                                    @if(in_array(303, App\GlobalVariable::GlobarModuleLinks()) || Auth::user()->role_id == 1 )
                                                        <a class="deleteUrl dropdown-item" data-modal-size="modal-md"
                                                           title="Delete Book"
                                                           href="{{url('delete-book-view/'.$value->id   )}}">@lang('lang.delete')</a>
                                                    @endif
                                                </div>
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
