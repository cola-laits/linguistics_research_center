<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Eieol_languageRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class Eieol_languageCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class Eieol_languageCrudController extends CrudController
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
        CRUD::setModel(\App\Models\EieolLanguage::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/eieol_language');
        CRUD::setEntityNameStrings('Eieol Language', 'Eieol Languages');
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

        CRUD::column('language')->type('string');
        CRUD::column('lang_attribute')->type('string');

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
        CRUD::setValidation(Eieol_languageRequest::class);

        //CRUD::setFromDb(); // fields
        CRUD::addField([
            'name'=>'language',
            'type'=>'text',
            'label'=>'Language',
        ]);
        CRUD::addField([
            'name'=>'lang_attribute',
            'type'=>'text',
            'label'=>'Lang Attribute',
            'hint'=>'This can be added to span tags in lessons that use this language - &lt;span lang="xxx"&gt;',
        ]);
        CRUD::addField([
            'name'=>'custom_keyboard_layout',
            'type'=>'textarea',
            'label'=>'Custom Keyboard Layout',
            'hint'=>"This should be a list of characters (either in unicode code points or pasted in) <br> Example: 'Â','Ä','Å','\u042f', '\u03da', '\u03db', '\u03c0' "
        ]);
        CRUD::addField([
            'name'=>'substitutions',
            'type'=>'textarea',
            'label'=>'Substitutions',
            'hint'=>"This is used for sorting. If there are characters that should be treated differently when sorting, enter them here.<br>Separate substituions by commas. Use x>y notation.<br>Example: If you enter Ѽ>Отъ, the every occurrence of Ѽ will be replaced with Отъ before sorting.<br>Do not use unicode code points, just paste in unicode characters."
        ]);
        CRUD::addField([
            'name'=>'custom_sort',
            'type'=>'textarea',
            'label'=>'Custom Sort',
            'hint'=>
'This should be a comma separated list of characters in the order the Gloss and Dictionary should be sorted.<br>'.
'Do not use unicode code points, just paste in unicode characters.<br>'.
'Example: A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,π,q,r,s,t,u,v,w,x,y,z<br>'.
'If two characters should be considered equal, place an equal sign between them. <br>'.
'In the next example, "a" and "A" are considered the same, "b" and "B" are considered the same, etc. Also "p","P" and "π" are considered the same. Further, "L", "l" and "ll" are the same. In other words, "ll" is treated as a single character.<br>'.
'Example: a=A,b=B,c=C,d=D,e=E,f=F,g=G,h=H,i=I,J=j,K=k,L=l=ll,M=m,N=n,O=o,P=p=π,Q=q,R=r,S=s,T=t,U=u,V=v,W=w,X=x,Y=y,Z=z <br>'
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
