<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LexEtymaReflexRequest;
use App\LexReflex;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class LexEtymaReflexCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LexEtymaReflexCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\LexEtymaReflex::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lex_etyma_reflex');
        CRUD::setEntityNameStrings('lex etyma reflex', 'lex etyma reflexes');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::removeButton('show');

        //CRUD::setFromDb(); // columns
        CRUD::column('etyma')->type('relationship')->attribute('entry')->searchLogic(
            function ($query, $column, $searchTerm) {
                $query->orWhereHas('etyma', function($q) use ($searchTerm) {
                    $q->where('entry', 'like', '%'.$searchTerm.'%');
                });
            }
        );
        CRUD::column('reflex')->type('relationship')->attribute('langAbbrGloss')->searchLogic(
            function ($query, $column, $searchTerm) {
                $query->orWhereHas('reflex', function($q) use ($searchTerm) {
                    $q->where('lang_attribute', 'like', '%'.$searchTerm.'%')
                        ->orWhere('gloss', 'like', '%'.$searchTerm.'%');
                });
            }
        );

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
        CRUD::setValidation(LexEtymaReflexRequest::class);

        //CRUD::setFromDb(); // fields

        CRUD::field('etyma_id')->type('number');
        CRUD::field('reflex_id')->type('number');
//        CRUD::field('etyma')->type('relationship')->attribute('entry')->ajax(true);
//        CRUD::field('reflex')->type('relationship')->attribute('langAbbrGloss')->ajax(true);

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
