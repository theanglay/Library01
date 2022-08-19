<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BorrowRequest;
use App\Models\Book;
use App\Models\Member;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BorrowCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BorrowCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Borrow::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/borrow');
        CRUD::setEntityNameStrings('borrow', 'borrows');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('borrow_date');
        CRUD::column('return_date');
//        CRUD::column('member');

        $this->crud->addColumn([
            'name' => 'member_id',
            'label' => "Member ID", // Table column heading
            'type' => "select",
            'model' => Member::class,
//            'name' => 'book_status', // the column that contains the ID of that connected entity;
            'entity' => 'MM', // the method that defines the relationship in your Model
            'attribute' => 'member', // foreign key attribute that is shown to user

        ]);
        $this->crud->addColumn([
            'name' => 'booK_id',
            'label' => "Book", // Table column heading
            'type' => "select",
//          'name' => 'book_status', // the column that contains the ID of that connected entity;
            'entity' => 'books', // the method that defines the relationship in your Model
            'attribute' => 'book_tile', // foreign key attribute that is shown to user
            'model' => Statusbook::class,
        ]);
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
        CRUD::setValidation(BorrowRequest::class);

        $this->crud->addField([ // select_from_array
            'name' => 'member_id',
            'label' => "Member ID",
            'entity' => 'MM',
            'type' => 'select2',
            'model' => Member::class,
            'attribute' => 'member',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
        ]);
        $this->crud->addField([ // select_from_array
            'name' => 'book_id',
            'label' => "Select Book",
            'type' => 'select2',
            'entity' => 'books',
            'model' => Book::class,
            'attribute' => 'book_tile',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
        ]);
        $this->crud->addField([ // select_from_array
            'name'  => ['borrow_date', 'return_date'], // db columns for start_date & end_date
            'label' => 'Event Date Borrow',
            'type'  => 'date_range',

            // OPTIONALS
            // default values for start_date & end_date
            'default'            => ['2022-8-28 01:01', '2022-12-05 02:00'],
            // options sent to daterangepicker.js
            'date_range_options' => [
                'drops' => 'auto', // can be one of [down/up/auto]
                'timePicker' => true,
                'locale' => ['format' => 'DD/MM/YYYY HH:mm']
            ]
        ]);

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
}
