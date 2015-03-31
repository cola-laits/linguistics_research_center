<ul class="menu">
	<li class="first">Semantic Fields</li>
	@foreach($alpha_cats as $cat)
		<li>
			{{ HTML::link('lex_semantic_category/' . $cat->id, $cat->text, array('title' => $cat->text . ' and subcategories thereof' )) }}
		</li>
	@endforeach
	
</ul>
<br />