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
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
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

        CRUD::field('id')->type('text')->attributes(['readonly'=>'readonly']);

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

        // Backpack lacks something like an inline search field, which is what we really want here.
        // Barring that, create an array of the many-to-many data in the model, use that to populate the field,
        // and then unwind the array back into the model on save (in the store() and update() methods here).
        // Following example from https://cybrarist.com/programming/how-to-use-many-to-many-relationship-in-a-repeater-field-in-laravel-backpack/
        CRUD::field('crossReferencesArray')
            ->type('repeatable')
            ->label('Cross References')
            ->subfields([
                [
                    'name' => 'id', 'type' => 'text', 'label' => 'Related Reflex ID',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name' => 'description', 'type' => 'text', 'label' => 'Description', 'attribute' => 'langNameEntriesGloss',
                    'attributes' => ['readonly' => 'readonly'],
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name' => 'relationship', 'type' => 'text', 'label' => 'Relationship',
                    'wrapper' => ['class' => 'form-group col-md-6'],
                ],
            ]);

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

    public function store() {
        $response = $this->traitStore();
        $this->updateCrossReferencesArray(request()->get('crossReferencesArray'));
        return $response;
    }

    public function update() {
        $response = $this->traitUpdate();
        $this->updateCrossReferencesArray(request()->get('crossReferencesArray'));
        return $response;
    }

    protected function updateCrossReferencesArray($arr) {
        $reflex = $this->crud->getCurrentEntry();
        $reflex->cross_references()->detach();
        foreach ($arr as $item) {
            $reflex->cross_references()->attach($item['id'], ['relationship'=>$item['relationship']]);
        }
    }
}
