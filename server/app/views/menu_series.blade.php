<ul class="menu">
	<li class="first">Lessons</li>	
	
	@foreach($lesson_menu as $lesson_menu_item)
        <li>{{ HTML::link('lesson/' . $lesson_menu_item->id, str_replace(' Online', '', $lesson_menu_item->menu_name), array('title' => $lesson_menu_item->expanded_title )) }} </li>
    @endforeach
</ul>
<br />