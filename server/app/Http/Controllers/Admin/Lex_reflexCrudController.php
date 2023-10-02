<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Lex_reflexRequest;
use App\Models\LexEtyma;
use App\Models\LexEtymaReflex;
use App\Models\LexLanguage;
use App\Models\LexPartOfSpeech;
use App\Models\LexReflexPartOfSpeech;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class Lex_reflexCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class Lex_reflexCrudController extends CrudController
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
        CRUD::setModel(\App\Models\LexReflex::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lex_reflex');
        CRUD::setEntityNameStrings('Lex Reflex', 'Lex Reflexes');
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
        CRUD::column('entries')->label('Reflexes')->type('model_function')->function_name('getEntriesCSV')
            ->searchLogic(function($query, $column, $searchTerm) {
                // would be better to actually parse the JSON in the DB, but we'll need MySQL 8 for that.
                // assume a full-text search on the JSON is good enough for now.
                $query->orWhere('entries', 'like', '%'.$searchTerm.'%');
            });
        CRUD::column('gloss')->type('text');
        CRUD::column('language')->label('Language')->type('relationship')->attribute('name')
            ->searchLogic(function ($query, $column, $searchTerm) {
                $query->orWhereHas('language', function ($query) use ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%');
                });
            });

        CRUD::filter('reflex')
            ->type('text')
            ->label('Reflex')
            ->whenActive(function($value) {
                $this->crud->addClause('where','entries','like', "%$value%");
            });

        CRUD::filter('gloss')
            ->type('text')
            ->label('Gloss')
            ->whenActive(function($value) {
                $this->crud->addClause('where','gloss','like', "%$value%");
            });

        $this->crud->addFilter(
            ['type'=>'select2_multiple', 'name'=>'language', 'label'=>'Language',],
            function() {
                return LexLanguage::orderBy('name')->get()->mapWithKeys(function ($item, $key) {
                    return [$item->id => $item->name];
                })->toArray();
            },
            function($value) {
                $this->crud->addClause('where','language_id', $value);
            }
        );

        $this->crud->addFilter(
            ['type'=>'text', 'name'=>'part_of_speech', 'label'=>'Part of Speech',],
            false,
            function($value) {
                $reflex_ids = LexReflexPartOfSpeech::where('text',$value)->get()->pluck('reflex_id')->toArray();
                $this->crud->addClause('whereIn','id', $reflex_ids);
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
        CRUD::setValidation(Lex_reflexRequest::class);

        CRUD::field('gloss')->type('text');
        CRUD::field('language_id')->label('Language')->type('relationship')->attribute('name');
        CRUD::field('lang_attribute')->type('text');

        CRUD::field('sources')->type('select2_multiple')->attribute('code')->pivot(true);

        CRUD::field('entries')->type('table')
            ->entity_singular('entry')
            ->columns(['text'=>'Text']);

        CRUD::field('parts_of_speech')->type('relationship')
            ->subfields([
                ['name'=>'text', 'label'=>'Part of Speech', 'wrapper'=>['class'=>'form-group col-md-9']],
                ['name'=>'order', 'label'=>'Order', 'wrapper'=>['class'=>'form-group col-md-3']],
            ]);

        CRUD::field('cross_references')
            ->type('relationship')
            //->subfields([
            //    ['name'=>'relationship', 'type'=>'text', 'label'=>'Relationship'],
            //])
            ->ajax(true);

        CRUD::field('extra_data')
            ->type('json')
            ->view_namespace('json-field-for-backpack::fields')
            ->modes(['form','tree','code'])
            ->default([])
            ->hint("'Extra Data' is freeform info that may vary between lexicons.");

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

    public function fetchCrossReferences()
    {
        return $this->fetch(\App\Models\LexReflex::class);
    }
}
