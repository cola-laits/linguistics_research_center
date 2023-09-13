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
        CRUD::column('language')->label('Language')->type('relationship')->attribute('name');

        $this->crud->addFilter(
            ['type'=>'select2_ajax', 'name'=>'reflex', 'label'=>'Reflex', 'method'=>'POST',
                'select_attribute'=>'entries'],
            backpack_url('lex_reflex/fetch/entries'),
            function($value) {
                $this->crud->addClause('where','entries','like', "%$value%");
            }
        );

        $this->crud->addFilter(
            ['type'=>'select2_ajax', 'name'=>'etyma', 'label'=>'Etymon', 'method'=>'POST',
                'select_attribute'=>'etymon'],
            backpack_url('lex_etyma/fetch/entry'),
            function($value) {
                $etyma_ids = LexEtyma::where('entry', 'like', "%$value%")->pluck('id')->toArray();
                $reflex_ids = LexEtymaReflex::whereIn('etyma_id', $etyma_ids)->pluck('reflex_id')->toArray();
                $this->crud->addClause('whereIn','id', $reflex_ids);
            }
        );

        $this->crud->addFilter(
            ['type'=>'select2_ajax', 'name'=>'gloss', 'label'=>'Gloss', 'method'=>'POST',
                'select_attribute'=>'gloss'],
            backpack_url('lex_reflex/fetch/gloss'),
            function($value) {
                $this->crud->addClause('where','gloss','like', "%$value%");
            }
        );

        $this->crud->addFilter(
            ['type'=>'select2', 'name'=>'language', 'label'=>'Language',],
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
                $reflex_ids = LexReflexPartOfSpeech::where('text',$value)->get()->pluck('id')->toArray();
                $reflex_id_csv = implode(',',$reflex_ids);
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

        //CRUD::setFromDb(); // fields
        CRUD::field('gloss')->type('text');
        CRUD::field('language_id')->label('Language')->type('relationship')->attribute('name');
        CRUD::field('lang_attribute')->type('text');
        CRUD::field('class_attribute')->type('text');

        CRUD::field('sources')->type('select2_multiple')->attribute('code')->pivot(true);

        CRUD::field('entries')->type('table')
            ->entity_singular('entry')
            ->columns(['text'=>'Text']);

//        CRUD::field('parts_of_speech')->type('table')...

        // for now, show them that the extra data is there without letting them break it
        CRUD::field('extra_data')->type('custom_html')->value("'Extra Data' is freeform info that may depend on which specific lexicon you're talking about.  This is a temporary placeholder which will eventually display that data.");
//        CRUD::field('extra_data')->type('repeatable')->attributes(['readonly'=>'readonly'])
//            ->hint("'Extra Data' is freeform info that may depend on which lexicon you're talking about.  The above is a temporary placeholder.");

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

    public function fetchEntries()
    {
        $table = 'lex_reflex';
        $field = 'entries';
        $data = $this->fetchTableLookupByDistinctField($table, $field);
        $result = $data->map(function ($j) { return json_decode($j); })->flatten()->pluck('text');
        return $result;
    }

    public function fetchGloss()
    {
        $table = 'lex_reflex';
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
