<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MemberstatusRequest;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Member;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;

/**
 * Class MemberstatusCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MemberstatusCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CloneOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkCloneOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Memberstatus::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/memberstatus');
        CRUD::setEntityNameStrings('memberstatus', 'memberstatuses');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('member_status');
//        CRUD::column('price');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(MemberstatusRequest::class);

        $this->crud->addField([
            'name'  => 'member_status',
            'label' => 'Member Status',
            'type'  => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
    public function issueBooks()
    {

//        $memberDetails = Member::where('member',$student_id)->first();
//        $memberDetails = SmLibraryMember::where('school_id', Auth::user()->school_id)->where('student_staff_id', '=', $student_staff_id)->first();
//        $getMemberDetails = SmStudent::select('full_name','email','mobile','student_photo')->where('school_id', Auth::user()->school_id)->where('id', '=', $student_id)->first();

        $getMemberDetails = Member::select('member','first_name', 'last_name', 'gender', 'image','phone')->where('school_id',1)->first();
        $books = Book::where('school_id', 1)->get();
        $totalIssuedBooks = Borrow::where('it', 1)->get();
            $data = [];
//            $data['memberDetails'] = $getMemberDetails->toArray();
            $data['books'] = $books->toArray();
            $data['totalIssuedBooks'] = $totalIssuedBooks->toArray();

        return view('all_issed_books', compact( 'books', 'getMemberDetails', 'totalIssuedBooks'));

    }
    public function returnBookView(Request $request, $issue_book_id)
    {
//        $issue_book_id=Borrow::find(id);
        $data['issue_book_id'] = $issue_book_id;
        return view('returnBookView', compact('issue_book_id'));
    }

    public function returnBook(Request $request, $issue_book_id)
    {
            $user = Auth()->user();
            if ($user) {
                $updated_by = $user->id;
            } else {
                $updated_by = $request->updated_by;
            }
            $return = Borrow::find($issue_book_id);
            $return->issue_status = "R";
//            $return->updated_by = $updated_by;
            $results = $return->update();

            if ($results) {

                $books_id = Borrow::select('book_id')->where('id', $issue_book_id)->first();
                $books = Book::find($books_id->book_id);
                $books->qty = $books->qty + 1;
                $result = $books->update();

            }
        return redirect()->back();
        }

}
