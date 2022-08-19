@extends('backEnd.master')
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('lang.book_report')</h1>
                <div class="bc-pages">
                    <a href="{{url('dashboard')}}">@lang('lang.dashboard')</a>
                    <a href="#">@lang('lang.library')</a>
                    <a href="#">@lang('lang.book_report')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            @if(in_array(309, App\GlobalVariable::GlobarModuleLinks()) || Auth::user()->role_id == 1 )
                <div class="row">
                    <div class="col-lg-8 col-md-6">
                        <div class="main-title">
                            <h3 class="mb-30 ">@lang('lang.select_criteria')</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="white-box">
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'url' => 'book-report-search', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="input-effect sm2_mb_20 md_mb_20">
                                        <select class="niceSelect w-100 bb form-control" id="select_category" name="book_category_id">
                                            <option value=""
                                                    data-display="@lang('lang.select_category')">@lang('lang.select_category')</option>
                                            @foreach($categories as $cat)
                                                <option value="{{$cat->id}}">{{$cat->category_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="input-effect sm2_mb_20 md_mb_20" id="select_book_div">
                                        <select class="niceSelect w-100 bb form-control" id="select_book" name="book_id">
                                            <option data-display="@lang('lang.select_book')"
                                                    value="">@lang('lang.select_book')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mt-20 text-right">
                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                        <span class="ti-search pr-2"></span>
                                        @lang('lang.search')
                                    </button>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
                <div class="row mt-40">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-4 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-0">@lang('lang.book_report')</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>@lang('lang.book_title')</th>
                                        <th>@lang('lang.book_no')</th>
                                        <th>@lang('lang.isbn_no')</th>
                                        <th>@lang('lang.author')</th>
                                        <th>@lang('lang.subject')</th>
                                        <th>@lang('lang.total')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $rows = $book_issues->groupBy('book_id');
                                        $total = 0;
                                    @endphp
                                    @foreach($rows as $key => $value)
                                            @php
                                                $books = \App\SmBook::find($key);
                                                $subject = \App\SmBookCategory::find(@$books->book_category_id);
                                                $total += count($value);
                                            @endphp
                                            <tr>
                                                <td>{{optional($books)->book_title}}</td>
                                                <td>{{optional($books)->book_number}}</td>
                                                <td>{{optional($books)->isbn_no}}</td>
                                                <td>{{optional($books)->author_name}}</td>
                                                <td>{{optional($subject)->category_name}}</td>
                                                <td>{{count($value)}}</td>
                                            </tr>
                                    @endforeach
                                    </tbody>
                                    <tr>
                                        <th colspan="4"></th>
                                        <th style="color: orange;font-size: 25px">@lang('total')</th>
                                        <th style="color: orange;font-size: 25px">{{$total}}</th>
                                    </tr>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection