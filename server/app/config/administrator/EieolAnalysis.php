<?php

/**
 * Eieol Analysis model config
*/

return array(

		'title' => 'Analysis',
		'single' => 'Analysis',
		'model' => 'EieolAnalysis',

		'columns' => array(
				'analysis' => array(
						'title' => 'Analysis',
				),
				'updated_by' => array(
						'title' => 'Updated By',
				),
				'updated_at' => array(
						'title' => 'Updated Time',
				),
		),
		
		'filters' => array(
				'analysis' => array(
						'title' => 'Analysis',
				),
				'updated_by' => array(
						'title' => 'Updated By',
				),
				'updated_at' => array(
						'title' => 'Updated Time',
				),
		),
		
		'sort' => array(
				'field' => 'analysis',
				'direction' => 'asc',
		),

		'edit_fields' => array(
				'analysis' => array(
						'title' => 'Analysis',
						'type' => 'text',
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
				'analysis' => 'required',

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