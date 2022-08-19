<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BookRequest;
use App\Models\Author;
use App\Models\Category;
use App\Models\Statusbook;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BookCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BookCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Book::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/book');
        CRUD::setEntityNameStrings('book', 'books');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
//    protected function setupListOperation()
//    {
//        CRUD::column('id');
//        CRUD::column('book_tile');
//        CRUD::column('category_id');
//        CRUD::column('author');
//        CRUD::column('price');
//        CRUD::column('qty');
//        CRUD::column('publication_date');
////        CRUD::column('image');
//        $this->crud->addColumn([
//            'name' => 'image',
////            'label' => "Book", // Table column heading
//            'type' => "image",
////            'name' => 'book_status', // the column that contains the ID of that connected entity;
////            'entity' => 'book', // the method that defines the relationship in your Model
////            'attribute' => 'book_tile', // foreign key attribute that is shown to user
////            'model' => Statusbook::class,
//        ]);
//        CRUD::column('book_status');
//        CRUD::column('donner_name');
//        CRUD::column('school_id');
////
//    }
        protected function setupListOperation()
    {
//        CRUD::column('image');

//        CRUD::column('category_book');
        CRUD::column('book_tile');
        $this->crud->addColumn([ // select_from_array
            'name' => 'book_status',
            'label' => "Book status",
            'entity' => 'status',
            'type' => 'select',
            'model' => Statusbook::class,
            'attribute' => 'status_name',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
        ]);

//        CRUD::column('category_id');
//        CRUD::column('book_status');

        $this->crud->addColumn([ // select_from_array
            'name' => 'category_id',
            'label' => "Category",
            'entity' => 'category',
            'type' => 'select',
            'model' => Category::class,
            'attribute' => 'category_name',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
        ]);
        $this->crud->addColumn([ // select_from_array
            'name' => 'author',
            'label' => "Author",
            'entity' => 'Author',
            'type' => 'select',
            'model' => Author::class,
            'attribute' => 'khmer_name',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
        ]);

        CRUD::column('publication_date');
        CRUD::column('price');
        CRUD::column('qty');

        $this->crud->addColumn([
            'name' => 'image',
//            'label' => "Book", // Table column heading
            'type' => "image",
//            'name' => 'book_status', // the column that contains the ID of that connected entity;
//            'entity' => 'book', // the method that defines the relationship in your Model
//            'attribute' => 'book_tile', // foreign key attribute that is shown to user
//            'model' => Statusbook::class,
        ]);
        CRUD::column('donner_name');

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
        CRUD::setValidation(BookRequest::class);

//        CRUD::field('qty');
//        CRUD::field('category_book');

//        CRUD::field('publication_date');
//        CRUD::field('price');
//        CRUD::field('book_status');
//        CRUD::field('donner_name');
//        $this->crud->addField([
//            'name'  => 'book_tile',
//            'label' => "Book Title",
//            'type'  => 'text',
////            'tab'             => 'Tab name here',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-4'
//            ],
//        ]);

        $this->crud->addField([
            // date_range
            'name'  => 'book_tile',
            'label' => 'Book title',
            'type'  => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);
        $this->crud->addField([ // select_from_array
            'name' => 'category_id',
            'label' => "Category Book",
            'entity' => 'category',
            'type' => 'select2',
            'model' => Category::class,
            'attribute' => 'category_name',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);
        $this->crud->addField([ // select_from_array
            'name' => 'author',
            'label' => "Author",
            'entity' => 'Author',
            'type' => 'select2',
            'model' => Author::class,
            'attribute' => 'khmer_name',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);
        $this->crud->addField([ // select_from_array
            'name' => 'book_status',
            'label' => "Book Status",
            'entity' => 'status',
            'type' => 'select2',
            'model' => Statusbook::class,
            'attribute' => 'status_name',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);
        $this->crud->addField([
            'name'  => 'price',
            'label' => "Price",
            'type'  => 'number',
//            'tab'             => 'Tab name here',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);
        $this->crud->addField([
            'name'  => 'qty',
            'label' => "Quantity",
            'type'  => 'number',
//            'tab'             => 'Tab name here',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);
        $this->crud->addField([
            'name'  => 'publication_date',
            'label' => "Publication date",
            'type'  => 'date',
//            'tab'             => 'Tab name here',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);
        $this->crud->addField([
            'name'  => 'donner_name',
            'label' => "Donner Name",
            'type'  => 'text',
//            'tab'             => 'Tab name here',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);
        $this->crud->addField([
            'label' => "BooK Image",
            'name' => "image",
            'type' => 'image',
            'upload' => true,
            'crop' => true, // set to true to allow cropping, false to disable
//        'aspect_ratio' => 1, // omit or set to 0 to allow any aspect ratio
            // 'disk' => 's3_bucket', // in case you need to show images from a different disk
            // 'prefix' => 'uploads/images/profile_pictures/' // in case your db value is only the file name (no path), you can use this to prepend your path to the image src (in HTML), before it's shown to the user;
        ]);
        $this->crud->addField([
            // date_range
            'name'  => 'description',
            'label' => 'Description',
            'type'  => 'tinymce',
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
}
