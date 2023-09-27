<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Lex_etymaRequest;
use App\Models\LexLexicon;
use App\Models\LexReflex;
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
        CRUD::setModel(\App\Models\LexEtyma::class);
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

        CRUD::column('lexicon_id')->type('select')->attribute('name');
        CRUD::column('entry')->label('Etyma')->type('text');
        CRUD::column('gloss')->type('text');
        CRUD::column('old_id')->label('Old Id')->type('number');
        CRUD::column('order')->type('number');
        CRUD::column('page_number')->type('text');

        CRUD::filter('lexicon_id')
            ->type('dropdown')
            ->values(LexLexicon::all()->pluck('name', 'id')->toArray())
            ->whenActive(function($value) {
                CRUD::addClause('where','lexicon_id',$value);
            });

        CRUD::filter('entry')
            ->label('Etyma')
            ->type('text')
            ->whenActive(function($value) {
                CRUD::addClause('where','entry','like', "%$value%");
            });


        CRUD::filter('gloss')
            ->label('Gloss')
            ->type('text')
            ->whenActive(function($value) {
                CRUD::addClause('where','gloss','like', "%$value%");
            });

        CRUD::filter('reflex')
            ->label('Reflex')
            ->type('text')
            ->whenActive(function($value) {
                CRUD::addClause('whereHas','reflexes', function($query) use ($value) {
                    return $query->where('entries','like',"%$value%");
                });
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
        CRUD::setValidation(Lex_etymaRequest::class);

        CRUD::field('lexicon_id')->type('select');
        CRUD::field('old_id')->label('Old Id')->type('number');
        CRUD::field('order')->type('number');
        CRUD::field('page_number')->type('text');
        CRUD::field('entry')->label('Etyma')->type('text');
        CRUD::field('gloss')->type('text');
        CRUD::field('cross_references')->type('select2_multiple')->model('App\Models\LexEtyma')->attribute('entry')->pivot(true);
        CRUD::field('semantic_fields')->type('select2_multiple')->model('App\Models\LexSemanticField')->attribute('text')->pivot(true);
        CRUD::field('reflexes')->type('relationship')->attribute('langNameEntriesGloss')->pivot(true)->ajax(true);

        CRUD::field('extra_data')
            ->type('json')
            ->view_namespace('json-field-for-backpack::fields')
            ->modes(['form','tree','code'])
            ->default([])
            ->hint("'Extra Data' is freeform info that may vary between lexicons.");

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
            'model'=>LexReflex::class,
            'searchable_attributes'=>[],
            'query' => function($model) {
                $search = request()->input('q') ?? false;
                if ($search) {
                    return $model->whereRaw('CONCAT(`gloss`," ",`entries`) LIKE "%' . $search . '%"');
                } else {
                    return $model;
                }
            },
        ]);
    }

    public function fetchEntry()
    {
        $table = 'lex_etyma';
        $field = 'entry';
        return $this->fetchTableLookupByDistinctField($table, $field);
    }

    public function fetchGloss()
    {
        $table = 'lex_etyma';
        $field = 'gloss';
        return $this->fetchTableLookupByDistinctField($table, $field);
    }

    protected function fetchTableLookupByDistinctField($table, $field)
    {
        $search_string = request()->input('q');
        $query = \DB::table($table)
            ->where($field,'like','%'.$search_string.'%')
            ->distinct()
            ->orderBy($field)
            ->pluck($field);
        return $query;
    }
}
