<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MemberRequest;
use App\Models\Statusbook;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MemberCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MemberCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Member::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/member');
        CRUD::setEntityNameStrings('member', 'members');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */

    protected function setupListOperation()
    {
        $this->crud->addColumn([
            'name'  => 'member',
            'label' =>'Member id',
        ]);
        $this->crud->addColumn([
            'name'  => 'first_name',
            'label' => 'First Name',
        ]);
        $this->crud->addColumn([
            'name'  => 'last_name',
            'label' => 'Last Name',
        ]);
        $this->crud->addColumn([
            'name'  => 'gender',
            'label' => 'Gender',
        ]);
        $this->crud->addColumn([
            'name'  => 'phone',
            'label' => 'Phone Number',
        ]);
        $this->crud->addColumn([
            'name'            => 'image',
            'type'            => 'image',
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
        CRUD::setValidation(MemberRequest::class);

        $this->crud->addField([
            'name'      => 'first_name',
            'label'     => "First name",
            'type'      => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 ,border-radius:20px'
        ],]);
        $this->crud->addField([
            'name'      => 'last_name',
            'label'     => "Last name",
            'type'      => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 ,border-radius:20px'
        ],]);
        $this->crud->addField([
            'name'      => 'member',
            'label'     => "Member ID",
            'type'      => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 ,border-radius:20px'
            ],
        ]);
        $this->crud->addField([
            'name'      => 'gender',
            'label'     => "Gender",
            'type'      => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 ,border-radius:20px'
        ],]);
        $this->crud->addField([
            'name'      => 'phone',
            'label'     => "Phone",
            'type'      => 'number',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 ,border-radius:20px'
        ],]);
        $this->crud->addField([
            'label' => "Profile",
            'name' => "image",
            'type' => 'image',
            'upload' => true,
            'crop' => true, // set to true to allow cropping, false to disable
//        'aspect_ratio' => 1, // omit or set to 0 to allow any aspect ratio
            // 'disk' => 's3_bucket', // in case you need to show images from a different disk
            // 'prefix' => 'uploads/images/profile_pictures/' // in case your db value is only the file name (no path), you can use this to prepend your path to the image src (in HTML), before it's shown to the user;
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
