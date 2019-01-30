<?php

/**
 * Lex Language model config
*/

return array(

		'title' => 'Language',
		'single' => 'Language',
		'model' => 'LexLanguage',

		'columns' => array(
				'name' => array(
						'title' => 'Name',
				),
				'language_sub_family' => array(
						'title' => 'Family->Sub Family',
						'relationship'=> 'language_sub_family.language_family',
						'select' => 'CONCAT((:table).name, "->", language_sub_family_lex_language_sub_family.name)'
				),
				'order' => array(
						'title' => 'Order',
				),
				'abbr' => array(
						'title' => 'Abbr',
				),
				'aka' => array(
						'title' => 'AKA',
				),
				'override_family' => array(
						'title' => 'Override Family',
				),
				'updated_by' => array(
						'title' => 'Updated By',
				),
				'updated_at' => array(
						'title' => 'Updated Time',
				),
		),
		
		'filters' => array(
				'name' => array(
						'title' => 'Name',
				),
				'order' => array(
						'title' => 'Order',
				),
				'abbr' => array(
						'title' => 'Abbr',
				),
				'aka' => array(
						'title' => 'AKA',
				),
				'Override Family' => array(
						'title' => 'Override Family',
				),
				'updated_by' => array(
						'title' => 'Updated By',
				),
				'updated_at' => array(
						'title' => 'Updated Time',
				),
		),
		
		'sort' => array(
				'field' => 'name',
				'direction' => 'asc',
		),

		'edit_fields' => array(
				'name' => array(
						'title' => 'Name',
						'type' => 'wysiwyg',
				),
				'language_sub_family' => array(
						'title' => 'Family->Sub Family',
						'type' => 'relationship',
						'name_field' => 'family_sub_family',
						'options_sort_field' => 'name'
				),
				'order' => array(
						'title' => 'Order',
						'type' => 'number',
						'thousands_separator' => '',
				),
				'abbr' => array(
						'title' => 'Abbr',
						'type' => 'text',
				),
				'aka' => array(
						'title' => 'AKA',
						'type' => 'text',
				),
				'override_family' => array(
						'title' => 'Override Family',
						'type' => 'text',
						'description' => 'This is for the reflex page.  This value will show instead of the Family that this Language belongs to'
				),
				'custom_sort' => array(
						'title' => 'Custom Sort',
						'type' => 'textarea',
						'description' => "This is used to set the sort order for the lex_lang_reflexes page and should be a comma separated 
										  list of characters in the order they should be sorter.  Do not use unicode code points, just paste in unicode characters.
										  Example: A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,π,q,r,s,t,u,v,w,x,y,z
										  If character aren't separated by a comma, they are considered equal. In the next example, p,P and π are considered the same.
										  Example: aAÄ,bB,cC,dD,eE,fF,gG,hH,iI,Jj,Kk,Ll,Mm,Nn,Oo,Ppπ,Qq,Rr,Ss,Tt,Uu,Vv,Ww,Xx,Yy,Zz"
				),
				'updated_by' => array(
						'title' => 'Updated By',
						'editable' => false
				),
				'updated_at' => array(
						'title' => 'Updated Time',
						'editable' => false,
				),
				'created_by' => array(
						'title' => 'Created By',
						'editable' => false
				),
				'created_at' => array(
						'title' => 'Created Time',
						'editable' => false,
				),
		),
		
		'rules' => array(
				'name' => 'required|unique:lex_language',
				'order' => 'required',
				'abbr' => 'required|unique:lex_language',
				'custom_sort' => 'required',
				'sub_family_id' => 'required',

		),
		
		'messages' => array(
				'sub_family_id.required' => 'The sub family field is required.'
		),
		
		'action_permissions'=> array(
				'delete' => function($model){
					return false;
				},
				'view' => function($model){
					return Auth::user()->isAdmin();
				},
				'update' => function($model){
					return Auth::user()->isAdmin();
				},
				'create' => function($model){
					return Auth::user()->isAdmin();
				}
		),
		
		'form_width' => 600,
);