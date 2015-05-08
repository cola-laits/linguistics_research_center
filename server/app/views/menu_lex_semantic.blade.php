<ul class="side-nav">
	<li class="office"><a href="#"><h2>Semantic Fields</h2></a></li>
	@foreach($alpha_cats as $cat)
		<li>
			{{ HTML::link('lex_semantic_category/' . $cat->id, $cat->text, array('title' => $cat->text . ' and subcategories thereof' )) }}
		</li>
	@endforeach
</ul>