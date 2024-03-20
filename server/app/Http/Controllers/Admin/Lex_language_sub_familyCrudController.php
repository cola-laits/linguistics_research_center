<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Lex_language_sub_familyRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\LexLanguageFamily;

/**
 * Class Lex_language_sub_familyCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class Lex_language_sub_familyCrudController extends CrudController
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
        CRUD::setModel(\App\Models\LexLanguageSubFamily::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lex_language_sub_family');
        CRUD::setEntityNameStrings('Lex Language Sub Family', 'Lex Language Sub Families');
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

        CRUD::column('name')->type('text')
            ->searchLogic(function ($query, $column, $searchTerm) {
                $query->orWhereRaw("JSON_EXTRACT(name, '$.en') like ? collate utf8mb4_unicode_ci", ['%'.$searchTerm.'%']);
            });
        CRUD::column('order')->type('number');
        CRUD::column('language_family')->type('relationship')
            ->searchLogic(function ($query, $column, $searchTerm) {
                $query->orWhereHas('language_family', function ($query) use ($searchTerm) {
                    $query->whereRaw("JSON_EXTRACT(name, '$.en') like ? collate utf8mb4_unicode_ci", ['%'.$searchTerm.'%']);
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
        CRUD::setValidation(Lex_language_sub_familyRequest::class);

        //CRUD::setFromDb(); // fields
        CRUD::field('name')->type('text');
        CRUD::field('order')->type('number');
        CRUD::field('language_family')->type('select')->model(LexLanguageFamily::class)->attribute('name');

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
