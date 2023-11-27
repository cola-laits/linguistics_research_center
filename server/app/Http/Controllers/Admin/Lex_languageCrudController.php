<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Lex_languageRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\LexLanguageSubFamily;

/**
 * Class Lex_languageCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class Lex_languageCrudController extends CrudController
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
        CRUD::setModel(\App\Models\LexLanguage::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lex_language');
        CRUD::setEntityNameStrings('Lex Language', 'Lex Languages');
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

        CRUD::column('name')->type('text');
        CRUD::column('order')->type('number');
        CRUD::column('language_sub_family')->type('relationship')
            ->attribute('family_sub_family')
            ->searchLogic(function ($query, $column, $searchTerm) {
                $query->orWhereHas('language_sub_family', function ($query) use ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%');
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
        CRUD::setValidation(Lex_languageRequest::class);

        CRUD::field('name')->type('text');
        CRUD::field('order')->type('number');
        CRUD::field('abbr')->type('text');
        CRUD::field('aka')->type('text');
        CRUD::field('language_sub_family')->type('select')->model(LexLanguageSubFamily::class)->attribute('family_sub_family');
        CRUD::field('override_family')->type('text')
            ->hint('This is for the reflex page. This value will show instead of the Family that this Language belongs to.');
        CRUD::field('description')->type('wysiwyg');

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
