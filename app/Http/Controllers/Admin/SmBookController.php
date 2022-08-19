<?php

namespace App\Http\Controllers;

use App\SmBook;
use App\SmBooklanguages;
use App\SmBookStatus;
use App\SmFaculty;
use App\SmNotification;
use App\SmStaff;
use App\SmStudent;
use App\SmSubject;
use App\SmVisitorLibrary;
use App\tableList;
use App\SmBookIssue;
use App\ApiBaseMethod;
use App\SmBookCategory;
use App\SmLibraryMember;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SmBookController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
    }

    public function index(Request $request)
    {

        try {
            $books = DB::table('sm_books')
                ->leftjoin('sm_subjects', 'sm_books.subject_id', '=', 'sm_subjects.id')
                ->leftjoin('sm_book_categories', 'sm_books.book_category_id', '=', 'sm_book_categories.id')
                ->select('sm_books.*', 'sm_subjects.subject_name', 'sm_book_categories.category_name')
                ->where('sm_books.school_id', Auth::user()->school_id)
                ->get();
            $max_books = SmBook::where('school_id', Auth::user()->school_id)->max('book_number');
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($books, null);
            }
            return view('backEnd.library.bookList', compact('books','max_books'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function addBook(Request $request)
    {

        try {
            $categories = SmBookCategory::where('school_id', Auth::user()->school_id)->get();
            $booklanguage = SmBooklanguages::where('school_id', Auth::user()->school_id)->get();
            $bookstatus = SmBookStatus::where('school_id', Auth::user()->school_id)->get();
            $subjects = SmSubject::where('school_id', Auth::user()->school_id)->get();
            $max_books = SmBook::where('school_id', Auth::user()->school_id)->max('book_number');
            $faculty = SmFaculty::where('active_status', 1)->get();


            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['categories'] = $categories->toArray();
                $data['subjects'] = $subjects->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.library.addBook', compact('categories','bookstatus', 'subjects','max_books','booklanguage','faculty'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function saveBookData(Request $request)
    {
        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'book_title' => "required|max:200",
                'book_category_id' => "required",
                'user_id' => "required"
//                'quantity' => "sometimes|nullable|integer|min:0",
//                'book_price' => "sometimes|nullable|integer|min:0"
            ]);
        } else {
            $validator = Validator::make($input, [
                'book_title' => "required|max:200",
                'book_category_id' => "required",
//                'quantity' => "sometimes|nullable|integer|min:0",
//                'book_price' => "sometimes|nullable|integer|min:0",
            ]);
        }

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        try {

            $user = Auth()->user();

            if ($user) {
                $user_id = $user->id;
            } else {
                $user_id = $request->user_id;
            }
            $photos = "";
                if ($request->file('photos') != "") {
                $file = $request->file('photos');
                $photos = 'photos-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/library/photos/', $photos);
                $photos = 'public/uploads/library/photos/' . $photos;
            }


            $books = new SmBook();
            $books->book_title = $request->book_title;
            $books->book_category_id = $request->book_category_id;
            $books->photos = $photos;
            $books->book_number = $request->book_number;
            $books->owner_name = $request->owner_name;
            $books->donner_name = $request->donner_name;
            $books->doi = $request->doi;
            $books->remark = $request->remark;
            $books->book_language_id = $request->book_language_id;
            $books->isbn_no = $request->isbn_no;
            $books->book_status = $request->book_status;
            $books->publisher_name = $request->publisher_name;
            $books->author_name = $request->author_name;
            $books->faculty_id = $request->faculty_id;
            $books->book_status = $request->book_status;
            $books->year_publisher = $request->year_publisher;
            if (@$request->subject) {
                $books->subject_id = $request->subject;
            }
            $books->rack_number = $request->rack_number;
            if (@$request->quantity != "") {
                $books->quantity = $request->quantity;
            }
            if (@$request->book_price != "") {
                $books->book_price = $request->book_price;
            }
            $books->details = $request->details;
            $books->post_date = date('Y-m-d');
            $books->created_by = $user_id;
            $books->school_id = Auth::user()->school_id;

            $results = $books->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($results) {
                    return ApiBaseMethod::sendResponse(null, 'New Book has been added successfully.');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($results) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('book-list');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function editBook(Request $request, $id)
    {


        try {
            $editData = SmBook::find($id);
            $categories = SmBookCategory::where('school_id', Auth::user()->school_id)->get();
            $booklanguage = SmBooklanguages::where('school_id', Auth::user()->school_id)->get();
            $bookstatus  = SmBookStatus::where('school_id', Auth::user()->school_id)->get();
            $subjects = SmSubject::where('school_id', Auth::user()->school_id)->get();
            $faculty = SmFaculty::where('active_status',1)->get();
            $max_books = SmBook::where('school_id', Auth::user()->school_id)->max('book_number');

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['editData'] = $editData->toArray();
                $data['categories'] = $categories->toArray();
                $data['subjects'] = $subjects->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.library.addBook', compact('editData','bookstatus', 'categories', 'subjects','max_books','booklanguage','faculty'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateBookData(Request $request, $id)
    {
//dd($request->all());

        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'book_title' => "required",
                'book_category_id' => "required",
                'user_id' => "required"
//                'quantity' => "sometimes|nullable|integer|min:0"
//                'book_price' => "sometimes|nullable|integer|min:0"
            ]);
        } else {
            $validator = Validator::make($input, [
                'book_title' => "required",
                'quantity' => "sometimes|nullable|integer|min:0",
                'book_category_id' => "required"
//                'book_price' => "sometimes|nullable|integer|min:0"
            ]);
        }

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {

            $user = Auth()->user();

            if ($user) {
                $user_id = $user->id;
            } else {
                $user_id = $request->user_id;
            }
            $photos = "";
            if ($request->file('photos') != "") {
                $file = $request->file('photos');
                $photos = 'photos-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/library/photos/', $photos);
                $photos = 'public/uploads/library/photos/' . $photos;
            }
            $books = SmBook::find($id);
            $books->book_title = $request->book_title;
            $books->book_category_id = $request->book_category_id;
            $books->book_number = $request->book_number;
            $books->isbn_no = $request->isbn_no;
            $books->photos = $photos;
            $books->remark = $request->remark;
            $books->book_language_id = $request->book_language_id;
            $books->publisher_name = $request->publisher_name;
            $books->owner_name = $request->owner_name;
            $books->donner_name = $request->donner_name;
            $books->faculty_id = $request->faculty_id;
            $books->author_name = $request->author_name;
            $books->year_publisher = $request->year_publisher;
            $books->doi = $request->doi;
            if (@$request->subject) {
                $books->subject_id = $request->subject;
            }
            $books->rack_number = $request->rack_number;
            if (@$request->quantity != "") {
                $books->quantity = $request->quantity;
            }
            if (@$request->book_price != "") {
                $books->book_price = $request->book_price;
            }
            $books->details = $request->details;
            $books->post_date = date('Y-m-d');
            $books->updated_by = $user_id;
            $results = $books->update();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($results) {
                    return ApiBaseMethod::sendResponse(null, 'Book Data has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($results) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('book-list');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteBookView(Request $request, $id)
    {

        try {
            $title = "Are you sure to detete this Book?";
            $url = url('delete-book/' . $id);
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($id, null);
            }
            return view('backEnd.modal.delete', compact('id', 'title', 'url'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteBook(Request $request, $id)
    {

        $tables = \App\tableList::getTableList('book_id', $id);

        try {
            if ($tables == null) {
                $result = SmBook::destroy($id);
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            } else {
                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            }
        } catch (\Illuminate\Database\QueryException $e) {

            $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
            Toastr::error($msg, 'Failed');
            return redirect()->back();
        }
    }

    public function memberList(Request $request)
    {

        try {
            $activeMembers = SmVisitorLibrary::all();
//            $activeMembers = SmLibraryMember::where('school_id', Auth::user()->school_id)->where('active_status', '=', 1)->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($activeMembers, null);
            }
            return view('backEnd.library.memberLists', compact('activeMembers'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function issueBooks(Request $request, $member_type, $student_id)
    {
//        try {

            $notification_id = $request->sm_book_id;
            if($notification_id !=null){
                $sm_book_issue_notification = SmNotification::find($notification_id);
                if($sm_book_issue_notification != null){
                    $sm_book_issue_notification->is_read = 1;
                    $sm_book_issue_notification->save();
                }
            }
            $memberDetails = SmVisitorLibrary::where('student_id',$student_id)->first();
//            $memberDetails = SmLibraryMember::where('school_id', Auth::user()->school_id)->where('student_staff_id', '=', $student_staff_id)->first();

            if ($member_type == 2) {

                $getMemberDetails = SmStudent::select('full_name','email','mobile','student_photo')->where('school_id', Auth::user()->school_id)->where('id', '=', $student_id)->first();
            } else {
                $getMemberDetails = SmStaff::select('full_name', 'email', 'mobile', 'staff_photo')->where('school_id', Auth::user()->school_id)->where('id', '=', $student_id)->first();
            }
            $current_date = Carbon::now();
            $current_date;

            $books = SmBook::where('school_id', Auth::user()->school_id)->get();
            $totalIssuedBooks = SmBookIssue::where('school_id', Auth::user()->school_id)->where('member_id', '=', $student_id)->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['memberDetails'] = $memberDetails->toArray();
                $data['books'] = $books->toArray();
                $data['totalIssuedBooks'] = $totalIssuedBooks->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.library.issueBooks', compact('memberDetails', 'books', 'getMemberDetails', 'totalIssuedBooks','current_date'));
//        } catch (\Exception $e) {
//            Toastr::error('Operation Failed', 'Failed');
//            return redirect()->back();
//        }
    }

    public function saveIssueBookData(Request $request)
    {
        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'book_id' => "required",
                'due_date' => "required",
                'user_id' => "required"
            ]);
        } else {
            $validator = Validator::make($input, [
                'book_id' => "required",
                'due_date' => "required"
            ]);
        }

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
//        try {
            $user = Auth()->user();

            if ($user) {
                $user_id = $user->id;
            } else {
                $user_id = $request->login_id;
            }
            $bookIssue = new SmBookIssue();
            $bookIssue->book_id = $request->book_id;
            $bookIssue->member_id = $request->member_id;
            $bookIssue->given_date = date('Y-m-d');
            $bookIssue->due_date = date('Y-m-d', strtotime($request->due_date));
            $bookIssue->issue_status = 'I';
            $bookIssue->school_id = Auth::user()->school_id;
            $bookIssue->created_by = $user_id;
            $results = $bookIssue->save();
            $bookIssue->toArray();

//            $roles = DB::table('infix_roles')->get();
//            $users = User::where('role_id',$roles)->get();

            $notification = new SmNotification();
            $notification->message = "Return Books";
//            $notification->user_id = $users->id;
//            $notification->role_id = $roles->id;
            $notification->created_by = Auth::user()->id;
            $notification->due_date = $bookIssue->due_date;
            $notification->book_issue_id = $bookIssue->id;
            $notification->url = "issue-books/".$bookIssue->book_id."/".$bookIssue->member_id."?"."sm_book_id=".$notification->id;
            $notification->save();
            if ($notification->save()){
                $notification->url = "issue-books/".$bookIssue->book_id."/".$bookIssue->member_id."?"."sm_book_id=".$notification->id;
                $notification->update();
            }

            if ($results) {
                $books = SmBook::find($request->book_id);
                $books->quantity = $books->quantity - 1;
                $result = $books->update();

                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendResponse(null, 'Book Issued  successfully');
                }
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {

                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }

                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
//        } catch (\Exception $e) {
//            Toastr::error('Operation Failed', 'Failed');
//            return redirect()->back();
//        }
    }

    public function returnBookView(Request $request, $issue_book_id)
    {

        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($issue_book_id, null);
            }
            return view('backEnd.library.returnBookView', compact('issue_book_id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function returnBook(Request $request, $issue_book_id)
    {
        try {
            $user = Auth()->user();
            if ($user) {
                $updated_by = $user->id;
            } else {
                $updated_by = $request->updated_by;
            }
            $return = SmBookIssue::find($issue_book_id);
            $return->issue_status = "R";
            $return->updated_by = $updated_by;
            $results = $return->update();

            if ($results) {

                $books_id = SmBookIssue::select('book_id')->where('school_id', Auth::user()->school_id)->where('id', $issue_book_id)->first();
                $books = SmBook::find($books_id->book_id);
                $books->quantity = $books->quantity + 1;
                $result = $books->update();

                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendResponse(null, 'Book has been Returned  successfully');
                }
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {

                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function allIssuedBook(Request $request)
    {
        try {
            $books = SmBook::select('id', 'book_title')->where('school_id', Auth::user()->school_id)->where('active_status', 1)->get();
            $subjects = SmSubject::select('id', 'subject_name')->where('school_id', Auth::user()->school_id)->where('active_status', 1)->get();

            $issueBooks = DB::table('sm_book_issues')
                ->join('sm_books', 'sm_book_issues.book_id', '=', 'sm_books.id')
                ->join('sm_library_members', 'sm_book_issues.member_id', '=', 'sm_library_members.id')
                ->join('sm_subjects', 'sm_subjects.id', '=', 'sm_books.subject_id')
                ->where('sm_books.school_id', Auth::user()->school_id)
                ->get();


            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['books'] = $books->toArray();
                $data['subjects'] = $subjects->toArray();
                $data['issueBooks'] = $issueBooks;
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.library.allIssuedBook', compact('books', 'subjects', 'issueBooks'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function searchIssuedBook(Request $request)
    {
//        dd($request->all());
//        try {
        $book_id = $request->book_id;
        $book_number = $request->book_number;
        $subject_id = $request->subject_id;
        $issueBooks = SmBookIssue::query();
        $issueBooks->where('active_status', 1);

    if(!empty($book_id and $subject_id and $book_number)){
        $issueBooks = DB::table('sm_book_issues')
            ->join('sm_books', 'sm_book_issues.book_id', '=', 'sm_books.id')
            ->join('sm_library_members', 'sm_book_issues.member_id', '=', 'sm_library_members.id')
            ->join('sm_subjects', 'sm_subjects.id', '=', 'sm_books.subject_id')
            ->where('sm_books.school_id', Auth::user()->school_id)
            ->where('sm_books.id', $book_id)
            ->where('sm_books.book_number', $book_number)
            ->where('sm_books.subject_id', $subject_id)
            ->get();
//        dd('hello');
    }
    elseif (!empty($book_id and $book_number)) {
        $issueBooks = DB::table('sm_book_issues')
            ->join('sm_books', 'sm_book_issues.book_id', '=', 'sm_books.id')
            ->join('sm_library_members', 'sm_book_issues.member_id', '=', 'sm_library_members.id')
            ->join('sm_subjects', 'sm_subjects.id', '=', 'sm_books.subject_id')
            ->where('sm_books.school_id', Auth::user()->school_id)
            ->where('sm_books.id',$book_id)
            ->where('sm_books.book_number',$book_number)
            ->get();
    }
    elseif(!empty($subject_id and $book_id)){
        $issueBooks = DB::table('sm_book_issues')
            ->join('sm_books', 'sm_book_issues.book_id', '=', 'sm_books.id')
            ->join('sm_library_members', 'sm_book_issues.member_id', '=', 'sm_library_members.id')
            ->join('sm_subjects', 'sm_subjects.id', '=', 'sm_books.subject_id')
            ->where('sm_books.school_id', Auth::user()->school_id)
            ->where('sm_books.id',$book_id)
            ->where('sm_books.subject_id',$subject_id)
            ->get();
    }
    elseif(!empty($subject_id and $book_number)) {
        $issueBooks = DB::table('sm_book_issues')
            ->join('sm_books', 'sm_book_issues.book_id', '=', 'sm_books.id')
            ->join('sm_library_members', 'sm_book_issues.member_id', '=', 'sm_library_members.id')
            ->join('sm_subjects', 'sm_subjects.id', '=', 'sm_books.subject_id')
            ->where('sm_books.school_id', Auth::user()->school_id)
            ->where('sm_books.book_number',$book_number)
            ->where('sm_books.subject_id',$subject_id)
            ->get();
    }
    elseif (!empty($book_id)) {
        $issueBooks = DB::table('sm_book_issues')
            ->join('sm_books', 'sm_book_issues.book_id', '=', 'sm_books.id')
            ->join('sm_library_members', 'sm_book_issues.member_id', '=', 'sm_library_members.id')
            ->join('sm_subjects', 'sm_subjects.id', '=', 'sm_books.subject_id')
            ->where('sm_books.school_id', Auth::user()->school_id)
            ->where('sm_books.id', $book_id)
            ->get();
    }
    elseif(!empty($book_number)) {
        $issueBooks = DB::table('sm_book_issues')
            ->join('sm_books', 'sm_book_issues.book_id', '=', 'sm_books.id')
            ->join('sm_library_members', 'sm_book_issues.member_id', '=', 'sm_library_members.id')
            ->join('sm_subjects', 'sm_subjects.id', '=', 'sm_books.subject_id')
            ->where('sm_books.school_id', Auth::user()->school_id)
            ->where('sm_books.book_number', $book_number)
            ->get();
    }
    elseif(!empty($subject_id)) {
        $issueBooks = DB::table('sm_book_issues')
            ->join('sm_books', 'sm_book_issues.book_id', '=', 'sm_books.id')
            ->join('sm_library_members', 'sm_book_issues.member_id', '=', 'sm_library_members.id')
            ->join('sm_subjects', 'sm_subjects.id', '=', 'sm_books.subject_id')
            ->where('sm_books.school_id', Auth::user()->school_id)
            ->where('sm_books.subject_id', $subject_id)
            ->get();
    }

//        dd($subject_id);

//            $query = '';
//            if (!empty($request->book_id)) {
//                $query = "AND sm_books.id = '$book_id'";
//            }
//            if (!empty($request->book_number)) {
//                $query .= "AND sm_books.book_number = '$request->book_number;'";
//            }
//
//            if (!empty($request->subject_id)) {
//                $query .= "AND b.subject_id = '$request->subject_id'";
//            }
//
//            $query .= " AND b.subject_id = " . Auth::user()->school_id;
//
//            $query2='';
//            if ($book_id != null){
//                $query2 = " AND i.book_id = " . $book_id;
//
//            }
//
//            $issueBooks = DB::select(DB::raw("SELECT i.*, b.book_title, b.book_number,
//                    b.isbn_no, b.author_name, m.member_type, m.student_staff_id, s.subject_name
//                    FROM sm_book_issues i
//                    LEFT JOIN sm_books b ON i.book_id = b.id
//                    LEFT JOIN sm_library_members m ON i.member_id = m.student_staff_id
//                    LEFT JOIN sm_subjects s ON b.subject_id = s.id
//                    WHERE i.issue_status = 'I'
//                    {$query}.
//                    {$query2}
//                    "));
            $books = SmBook::select('id', 'book_title')
                ->where('school_id', Auth::user()->school_id)
                ->where('active_status', 1)
                ->get();


            $subjects = SmSubject::select('id', 'subject_name')->where('school_id', Auth::user()->school_id)->where('active_status', 1)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['book_id'] = $book_id;
                $data['book_number'] = $book_number;
                $data['subject_id'] = $subject_id;
                $data['books'] = $books->toArray();
                $data['subjects'] = $subjects->toArray();
                $data['issueBooks'] = $issueBooks;
                return ApiBaseMethod::sendResponse($data, null);
            }
//        dd($book_number);
        return view('backEnd.library.allIssuedBook', compact('issueBooks', 'books', 'subjects', 'book_id', 'book_number', 'subject_id'));
//        } catch (\Exception $e) {
//            Toastr::error('Operation Failed', 'Failed');
//            return redirect()->back();
//        }
    }

    public static function pp($data)
    {

        echo "<pre>";
        print_r($data);
        exit;
    }

    public function bookListApi(Request $request)
    {

        try {
            $books = DB::table('sm_books')
                ->join('sm_subjects', 'sm_books.subject', '=', 'sm_subjects.id')
                ->where('sm_books.school_id', Auth::user()->school_id)
                ->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($books, null);
            }

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function AjaxSelectBooks(Request $request){
//        dd($request->all());
        $category_id = $request->id;
        $books = SmBook::where('book_category_id',$category_id)->get();

        return response()->json($books);

    }
    public function BookReport(){
        try{
                $categories = SmBookCategory::get();
                $book_issues = SmBookIssue::all();
                return view('backEnd.library.book_report',compact('categories','book_issues'));

        }catch (\Exception $e){
            Toastr::error('Operation Failed','Faile');
            return redirect()->back();
        }
    }
    public function BookReportSearch(Request $request){
//        try{
                $book_category_id = $request->book_category_id;
                $book_id = $request->book_id;
                if ($book_id != null){
                    $book_issues = SmBookIssue::where('book_id',$book_id)->get();
                }else{
                    $book_issues = SmBookIssue::get();
                }
                $categories = SmBookCategory::get();

                return view('backEnd.library.book_report',compact('book_id','book_category_id','categories','book_issues'));


//        }catch (\Exception $e){
//            Toastr::error('Operation Failed','Faile');
//            return redirect()->back();
//        }
    }
}