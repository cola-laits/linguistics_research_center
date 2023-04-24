<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Lex_semantic_fieldRequest;
use App\Models\LexSemanticCategory;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class Lex_semantic_fieldCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class Lex_semantic_fieldCrudController extends CrudController
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
        CRUD::setModel(\App\Models\LexSemanticField::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lex_semantic_field');
        CRUD::setEntityNameStrings('Lex Semantic Field', 'Lex Semantic Fields');
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

        CRUD::column('text');
        CRUD::column('number');
        CRUD::column('abbr');
        CRUD::column('semantic_category')->type('relationship')->attribute('lex_text');

        CRUD::addFilter([
            'name'  => 'semantic_category',
            'type'  => 'select2',
            'label' => 'Semantic Category',
        ], function () {
            return LexSemanticCategory::orderBy('text')->get()->mapWithKeys(function ($item, $key) {
                return [$item->id => $item->lex_text];
            })->toArray();
        }, function ($value) {
            $this->crud->addClause('where', 'semantic_category_id', $value);
        });

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
        CRUD::setValidation(Lex_semantic_fieldRequest::class);

        //CRUD::setFromDb(); // fields

        CRUD::field('text');
        CRUD::field('number');
        CRUD::field('abbr');
        CRUD::field('semantic_category')->type('select')->attribute('lex_text');

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
