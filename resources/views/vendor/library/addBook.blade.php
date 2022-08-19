@extends('backEnd.master')
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('lang.manage') @lang('lang.book')</h1>
                <div class="bc-pages">
                    <a href="{{url('dashboard')}}">@lang('lang.dashboard')</a>
                    <a href="#">@lang('lang.library')</a>
                    @if(isset($editData))
                        <a href="#">@lang('lang.edit_book')</a>
                    @else
                        <a href="#">@lang('lang.add_book')</a>
                    @endif

                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
          <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-6">
                    <div class="main-title ">
                        <h3 class="mb-30">
                            @if(isset($editData))
                                @lang('lang.edit')
                            @else
                                @lang('lang.add')
                            @endif
                            @lang('lang.book')</h3>
                    </div>
                </div>
            </div>
            @if(isset($editData))
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'url' => 'update-book-data/'.$editData->id, 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
            @else
            @if(in_array(300, App\GlobalVariable::GlobarModuleLinks()) || Auth::user()->role_id == 1 )
       
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'url' => 'save-book-data',
                'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
            @endif
            @endif

            <div class="row">
                <div class="col-lg-12">
                    @include('backEnd.partials.alertMessage')
                    <div class="white-box">
                        <div class="">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <div class="row mb-30">
                                <div class="col-lg-3">
                                    <div class="input-effect sm2_mb_20 md_mb_20">
                                        <input
                                            class="primary-input form-control{{ $errors->has('book_title') ? ' is-invalid' : '' }}"
                                            type="text" name="book_title" autocomplete="off"
                                            value="{{isset($editData)? $editData->book_title :(old('book_title')!=''? old('book_title'):'')}}">
                                        <label>@lang('lang.book_title') <span>*</span> </label>
                                        <span class="focus-border"></span>
                                        @if ($errors->has('book_title'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('book_title') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="input-effect sm2_mb_20 md_mb_20">
                                        <select
                                            class="niceSelect w-100 bb form-control{{ $errors->has('book_category_id') ? ' is-invalid' : '' }}"
                                            name="book_category_id" id="book_category_id">
                                            <option data-display="@lang('lang.select_book_category') *"
                                                    value="">@lang('lang.select')</option>
                                            @foreach($categories as $key=>$value)
                                                @if(isset($editData))
                                                    <option
                                                        value="{{$value->id}}" {{$value->id == $editData->book_category_id? 'selected':''}}>{{$value->category_name}}</option>
                                                @else
                                                    <option
                                                        value="{{$value->id}}" {{old('book_category_id')!=''? (old('book_category_id') == $value->id? 'selected':''):''}} >{{$value->category_name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <span class="focus-border"></span>
                                        @if ($errors->has('book_category_id'))
                                            <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('book_category_id') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="input-effect sm2_mb_20 md_mb_20">
                                        <select
                                            class="niceSelect w-100 bb form-control{{ $errors->has('subject') ? ' is-invalid' : '' }}"
                                            name="subject" id="subject">
                                            <option data-display="@lang('lang.select_subjects')"
                                                    value="">@lang('lang.select')</option>
                                            @foreach($subjects as $key=>$value)
                                                @if(isset($editData))
                                                    <option value="{{$value->id}}" {{$value->id == $editData->subject_id? 'selected':''}}>{{$value->subject_name}}</option>
                                                    @else
                                                    <option value="{{$value->id}}" {{old('subject')!=''? (old('subject') == $value->id? 'selected':''):''}} >{{$value->subject_name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <span class="focus-border"></span>
                                        @if ($errors->has('subject'))
                                            <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('subject') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="input-effect sm2_mb_20 md_mb_20">
                                        <input
                                            class="primary-input form-control{{ $errors->has('type') ? ' is-invalid' : '' }}"
                                            type="text" name="book_number" autocomplete="off"
                                            value="{{$max_books >0? $max_books + 1 : ""}}">
                                        <label>@lang('lang.book') @lang('lang.code')</label>
                                        <span class="focus-border"></span>
                                        @if ($errors->has('book_number'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('book_number') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>


                            </div>

                            <div class="row mb-30">
                                <div class="col-lg-3">
                                    <div class="input-effect sm2_mb_20 md_mb_20">
                                        <input
                                            class="primary-input form-control{{ $errors->has('isbn_no') ? ' is-invalid' : '' }}"
                                            type="number" name="isbn_no" autocomplete="off"
                                            value="{{isset($editData)? $editData->isbn_no: old('isbn_no')}}">
                                        <label>@lang('lang.isbn') @lang('lang.no')</label>
                                        <span class="focus-border"></span>
                                        @if ($errors->has('isbn_no'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('isbn_no') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-effect sm2_mb_20 md_mb_20">
                                        <input
                                            class="primary-input form-control{{ $errors->has('publisher_name') ? ' is-invalid' : '' }}"
                                            type="text" name="publisher_name" autocomplete="off"
                                            value="{{isset($editData)? $editData->publisher_name: old('publisher_name')}}">
                                        <label>@lang('lang.publisher') @lang('lang.name')</label>
                                        <span class="focus-border"></span>
                                        @if ($errors->has('publisher_name'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('publisher_name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="input-effect sm2_mb_20 md_mb_20">
                                        <input
                                            class="primary-input form-control{{ $errors->has('author_name') ? ' is-invalid' : '' }}"
                                            type="text" name="author_name" autocomplete="off"
                                            value="{{isset($editData)? $editData->author_name: old('author_name')}}">
                                        <label>@lang('lang.author_name')</label>
                                        <span class="focus-border"></span>
                                        @if ($errors->has('author_name'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('author_name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-effect sm2_mb_20 md_mb_20">
                                        <input
                                                class="primary-input form-control{{ $errors->has('owner_name') ? ' is-invalid' : '' }}"
                                                type="text" name="owner_name" autocomplete="off"
                                                value="{{isset($editData)? $editData->owner_name: old('owner_name')}}">
                                        <label>@lang('lang.owner_book')</label>
                                        <span class="focus-border"></span>
                                        @if ($errors->has('owner_name'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('owner_name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-30">
                                <div class="col-lg-3">
                                    <div class="input-effect sm2_mb_20 md_mb_20">
                                        <input
                                                class="primary-input form-control{{ $errors->has('donner_name') ? ' is-invalid' : '' }}"
                                                type="text" name="donner_name" autocomplete="off"
                                                value="{{isset($editData)? $editData->donner_name: old('donner_name')}}">
                                        <label>@lang('lang.donner_name')</label>
                                        <span class="focus-border"></span>
                                        @if ($errors->has('donner_name'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('donner_name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-effect sm2_mb_20 md_mb_20">
                                        <input
                                            class="primary-input form-control{{ $errors->has('rack_number') ? ' is-invalid' : '' }}"
                                            type="text" name="rack_number" autocomplete="off"
                                            value="{{isset($editData)? $editData->rack_number: old('rack_number')}}">
                                        <label>@lang('lang.ISSN')</label>
                                        <span class="focus-border"></span>
                                        @if ($errors->has('rack_number'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('rack_number') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-effect sm2_mb_20 md_mb_20">
                                        <input
                                                class="primary-input form-control{{ $errors->has('doi') ? ' is-invalid' : '' }}"
                                                type="text" name="doi" autocomplete="off"
                                                value="{{isset($editData)? $editData->doi: old('doi')}}">
                                        <label>@lang('lang.doi')</label>
                                        <span class="focus-border"></span>
                                        @if ($errors->has('doi'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('doi') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="input-effect sm2_mb_20 md_mb_20">
                                                <input class="primary-input" type="text" id="placeholderFileFourName" placeholder="photo"
                                                       readonly="">
                                                <span class="focus-border"></span>
                                                @if ($errors->has('file'))
                                                    <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ @$errors->first('file') }}</strong>
                                                        </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="primary-btn-small-input" type="button">
                                                <label class="primary-btn small fix-gr-bg" for="photos" style="font-family: 'Poppins', sans-serif">@lang('lang.browse')</label>
                                                <input type="file" class="d-none" name="photos" id="photos">
                                            </button>
                                        </div>
                                    </div>
                                </div>

 

                            </div>

                            <div class="row mb-30">

                                <div class="col-lg-3">
                                    <div class="input-effect sm2_mb_20 md_mb_20">
                                        <input
                                            class="primary-input form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}"
                                            type="number" min="0" name="quantity" autocomplete="off"
                                            value="{{isset($editData)? $editData->quantity : old('quantity')}}">
                                        <label>@lang('lang.quantity')</label>
                                        <span class="focus-border"></span>
                                        @if ($errors->has('quantity'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('quantity') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="input-effect sm2_mb_20 md_mb_20">
                                        <input
                                            class="primary-input form-control{{ $errors->has('book_price') ? ' is-invalid' : '' }}"
                                            type="number" min="0" name="book_price" autocomplete="off"
                                            value="{{isset($editData)? $editData->book_price : old('book_price')}}">
                                        <label>@lang('lang.book_price')</label>
                                        <span class="focus-border"></span>
                                        @if ($errors->has('book_price'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('book_price') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-effect sm2_mb_20 md_mb_20">
                                        <input
                                                class="primary-input form-control{{ $errors->has('remark') ? ' is-invalid' : '' }}"
                                                type="text" min="0" name="remark" autocomplete="off"
                                                value="{{isset($editData)? $editData->remark : old('remark')}}">
                                        <label>@lang('lang.remark')</label>
                                        <span class="focus-border"></span>
                                        @if ($errors->has('remark'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('remark') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-effect sm2_mb_20 md_mb_20">
                                        <select
                                                class="niceSelect w-100 bb form-control{{ $errors->has('book_language_id') ? ' is-invalid' : '' }}"
                                                name="book_language_id" id="book_language_id">
                                            <option data-display="@lang('lang.select_book_language')"
                                                    value="">@lang('lang.select')</option>
                                            @foreach($booklanguage as $key=>$value)
                                                @if(isset($editData))
                                                    <option
                                                            value="{{$value->id}}" {{$value->id == $editData->book_language_id? 'selected':''}}>{{$value->language}}</option>
                                                @else
                                                    <option
                                                            value="{{$value->id}}" {{old('book_language_id')!=''? (old('book_language_id') == $value->id? 'selected':''):''}} >{{$value->language}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <span class="focus-border"></span>
                                        @if ($errors->has('book_language_id'))
                                            <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('book_language_id') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row md-20">
                            <div class="col-lg-3">
                                <div class="input-effect sm2_mb_20 md_mb_20">
                                    <select
                                            class="niceSelect w-100 bb form-control{{ $errors->has('faculty_id') ? ' is-invalid' : '' }}"
                                            name="faculty_id" id="faculty_id">
                                        <option data-display="@lang('lang.select_faculty')"
                                                value="">@lang('lang.select')</option>
                                        @foreach($faculty as $key=>$value)
                                            @if(isset($editData))
                                                <option value="{{$value->id}}" {{$value->id == $editData->faculty_id? 'selected':''}}>{{$value->faculty_name}}</option>
                                            @else
                                                <option value="{{$value->id}}" {{old('faculty')!=''? (old('faculty') == $value->id? 'selected':''):''}} >{{$value->faculty_name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="focus-border"></span>
                                    @if ($errors->has('faculty_id'))
                                        <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('faculty_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                                <div class="col-lg-3">
                                    <div class="no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="input-effect sm2_mb_20 md_mb_20">
                                                <input class="primary-input form-control{{ $errors->has('year_publisher') ? ' is-invalid' : '' }}"
                                                       id="startDate" type="text"
                                                       name="year_publisher" value="{{isset($editData)? $editData->year_publisher : old('year_publisher')}}"
{{--                                                       name="year_publisher" value="{{date('m/d/Y', strtotime($editData->year_publisher))}}"--}}
                                                       autocomplete="off">
                                                <label>@lang('lang.publisher_date')</label>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('year_publisher'))
                                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('year_publisher') }}</strong>
                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-effect sm2_mb_20 md_mb_20">
                                        <select
                                                class="niceSelect w-100 bb form-control{{ $errors->has('book_status') ? ' is-invalid' : '' }}"
                                                name="book_status" id="book_status">
                                            <option data-display="@lang('lang.select_book_status')"
                                                    value="">@lang('lang.select')</option>
                                            @foreach($bookstatus as $key=>$value)
                                                @if(isset($editData))
                                                    <option value="{{$value->id}}" {{$value->id == $editData->book_status? 'selected':''}}>{{$value->book_status}}</option>
                                                @else
                                                    <option value="{{$value->id}}" {{old('book_status')!=''? (old('book_status') == $value->id? 'selected':''):''}} >{{$value->book_status}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <span class="focus-border"></span>
                                        @if ($errors->has('book_status'))
                                            <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('book_status') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-4">
                                    <div class="input-effect sm2_mb_20 md_mb_20">
                                        <textarea class="primary-input form-control" cols="0" rows="4" name="details"
                                                  id="details">{{isset($editData) ? $editData->details : old('details')}}</textarea>
                                        <label>@lang('lang.description') <span></span> </label>
                                        <span class="focus-border textarea"></span>

                                    </div>
                                </div>
                        </div>
                          @php 
                              $tooltip = "";
                              if(in_array(300, App\GlobalVariable::GlobarModuleLinks()) || Auth::user()->role_id == 1 ){
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

                                    @lang('lang.book')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
        </div>
      
    </section>
@endsection
