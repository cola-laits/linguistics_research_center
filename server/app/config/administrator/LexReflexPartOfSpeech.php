<?php

/**
 * Lex Reflex Part of Speech model config
*/

return array(

		'title' => 'Reflex -> Part Of Speech',
		'single' => 'Reflex -> Part Of Speech',
		'model' => 'LexReflexPartOfSpeech',

		'columns' => array(
				'reflex' => array(
						'title' => 'Reflex',
						'relationship'=> 'reflex.language',
						'select' => 'CONCAT((:table).name, " -> ", reflex_lex_reflex.gloss)'
				),
				'text' => array(
						'title' => 'Text',
				),
				'order' => array(
						'title' => 'Order',
				),
				'updated_by' => array(
						'title' => 'Updated By',
				),
				'updated_at' => array(
						'title' => 'Updated Time',
				),
		),
		
		'filters' => array(
				'text' => array(
						'title' => 'Text',
				),
				'order' => array(
						'title' => 'Order',
				),
				'updated_by' => array(
						'title' => 'Updated By',
				),
				'updated_at' => array(
						'title' => 'Updated Time',
				),
		),
		
		'sort' => array(
				'field' => 'text',
				'direction' => 'asc',
		),

		'edit_fields' => array(
				'text' => array(
						'title' => 'Text',
						'type' => 'text',
						'description' => "This must match entries in Lexicon Parts of Speech.  
										  You can join multiple ones together with a period.  
										  Test the public page after you update this.  
										  If this doesn't match the Parts of Speech, you'll get an error.",
				),
				'order' => array(
						'title' => 'Order',
						'type' => 'number',
						'thousands_separator' => '',
				),				
				'reflex' => array(
						'title' => 'Reflex',
						'type' => 'relationship',
						'name_field' => 'reflex_lister',
						'autocomplete' => true,	
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
				'text' => 'required',
				'order' => 'required',
				'reflex' => 'required',
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
);