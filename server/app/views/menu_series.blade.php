<ul class="side-nav">
	<li>
		<a class="off-canvas-submenu-call" href="#">Other Language Lessons</a>
		<ul class="side-nav">
			@foreach($lesson_menu as $lesson_menu_item)
		        <li>{{ HTML::link('eieol_lesson/' . $lesson_menu_item->id, str_replace(' Online', '', $lesson_menu_item->menu_name), array('title' => $lesson_menu_item->expanded_title )) }} </li>
		    @endforeach
	  	</ul>
	</li>
</ul> 