@extends(backpack_view('blank'))
@section('header')
    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">All issuded book</span>
            <small> </small>

        </h2>
    </section>
@endsection
@section('content')

<table class="table">

  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Book Title</th>
      <th scope="col">Member ID</th>
      <th scope="col">Borrow Date</th>
      <th scope="col">Return Date</th>
        <th scope="col">Number of days</th>
      <th scope="col">issue/return</th>
    </tr>
  </thead>
  <tbody>
  @foreach($totalIssuedBooks as $value)
      @foreach($books as $b)
    <tr>
      <td>{{$value->id}}</td>
      <td>{{$value->books->book_tile}}</td>
      <td>{{$value->member_id}}</td>
      <td>{{$value->borrow_date}}</td>
      <td>{{$value->return_date}}</td>
      <td>@php
          $diff = abs(strtotime($value->return_date) - strtotime($value->borrow_date))/86400;

          @endphp
          <p style="color: #00a65a;margin-left: 50px">{{$diff}}</p>
      </td>
      <td>
          @if($value->issue_status=='I')
{{--              <button type="submit" class="button-33 btn-danger" role="button">Issue</button>--}}
              <a title="Return Book"data-bs-target="#exampleModal" data-bs-toggle="modal" href="{{url('return-book-view/'.$value->id)}}" class="button-33 btn-danger primary-btn fix-gr-bg">issue</a>
           @endif
          @if($value->issue_status=='R')
              <button class="button-33 " disabled role="button">returned</button>
          @endif
      </td>
    </tr>
      @endforeach
  @endforeach
  </tbody>
</table>
@endsection
<style>
    /* CSS */
    .button-33 {
        background-color: #c2fbd7;
        border-radius: 100px;
        box-shadow: rgba(44, 187, 99, .2) 0 -25px 18px -14px inset,rgba(44, 187, 99, .15) 0 1px 2px,rgba(44, 187, 99, .15) 0 2px 4px,rgba(44, 187, 99, .15) 0 4px 8px,rgba(44, 187, 99, .15) 0 8px 16px,rgba(44, 187, 99, .15) 0 16px 32px;
        color: #2c1f80;
        cursor: pointer;
        display: inline-block;
        font-family: 'DynaPuff', cursive;
        padding: 7px 20px;
        text-align: center;
        text-decoration: none;
        transition: all 250ms;
        border: 0;
        font-size: 16px;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
    }

    .button-33:hover {
        box-shadow: rgba(44,187,99,.35) 0 -25px 18px -14px inset,rgba(44,187,99,.25) 0 1px 2px,rgba(44,187,99,.25) 0 2px 4px,rgba(44,187,99,.25) 0 4px 8px,rgba(44,187,99,.25) 0 8px 16px,rgba(44,187,99,.25) 0 16px 32px;
        transform: scale(1.05) rotate(-1deg);
    }
</style>
<style>
    @import url('https://fonts.googleapis.com/css2?family=DynaPuff&display=swap');
</style>

