<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Lex_etymaRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class Lex_etymaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class Lex_etymaCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\LexEtyma::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lex_etyma');
        CRUD::setEntityNameStrings('Lex Etymon', 'Lex Etyma');
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

        CRUD::column('entry')->type('text');
        CRUD::column('gloss')->type('text');
        CRUD::column('old_id')->label('Old Id')->type('number');
        CRUD::column('order')->type('number');
        CRUD::column('page_number')->type('text');

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
        CRUD::setValidation(Lex_etymaRequest::class);

        CRUD::field('old_id')->label('Old Id')->type('number');
        CRUD::field('order')->type('number');
        CRUD::field('page_number')->type('text');
        CRUD::field('entry')->type('text');
        CRUD::field('gloss')->type('text');
        CRUD::field('cross_references')->type('select2_multiple')->model('App\LexEtyma')->attribute('entry')->pivot(true);
        CRUD::field('semantic_fields')->type('select2_multiple')->model('App\LexSemanticField')->attribute('text')->pivot(true);
        CRUD::field('reflexes')->type('relationship')->attribute('langAbbrEntriesGloss')->pivot(true)->ajax(true);

        /*
        CRUD::field('reflexes')
            ->type('relationship')
            ->ajax(true)
            ->attribute('langAbbrGloss');
        */

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

    public function fetchReflexes()
    {
        return $this->fetch([
            'model' => \App\LexReflex::class,
            'searchable_attributes' => ['gloss'],
            'paginate' => 100,
        ]);
    }
}
