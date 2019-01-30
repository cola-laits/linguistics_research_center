<?php

class PageController extends BaseController {	
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$pages = Page::all();
        return View::make('page.page_index', ['pages' => $pages]);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('page.page_form', ['action' => 'Create',]);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

    $returned_page = DB::transaction(function() {
  
            $page = new Page;
            $page->slug = Input::get('slug');
            $page->name = Input::get('name');
            $page->content  = Input::get('content');
            //$page->created_by = Auth::page()->pagename;
            //$page->updated_by = Auth::page()->pagename;
            $page->save();    
            return $page;
            
    }); //end transaction
            
    Session::flash('message', "'".$returned_page->slug . '" has been created');
    return Redirect::to('/admin2/page/' . $returned_page->id . '/edit');
    
  
  }


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$page = Page::find($id);
		return View::make('page.page_form', [ 'page' => $page, 
											  'action' => 'Edit','slug'=> $page->slug]);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

 		  $returned_page = DB::transaction(function($id) use ($id) {
 		  
				$page = Page::find($id);
				//$page->slug = Input::get('slug');
				$page->name = Input::get('name');
				$page->content  = Input::get('content');			
				$page->save();
				
				return $page;
			}); //end transaction
			
			Session::flash('message', '"'.$returned_page->slug. '" has been updated');
			return Redirect::to('/admin2/page/' . $id . '/edit');

	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$page = Page::find($id);
		
		Page::destroy($id);
		Session::flash('message', 'Page has been deleted');
		return Redirect::to('/admin2/page');
	}

}