<ul class="side-nav">
	<li>
		<label>
			<img src="http://www.utexas.edu/cola/centers/lrc/images/dieboldsm.jpg" alt="A. Richard Diebold Center for Indo-European Language and Culture" border="2" />
		</label>
	</li>
</ul>
<div style="clear:both;"></div>
<ul class="side-nav">
	<li class="office"><label>Semantic Fields</label></li>
	@foreach($alpha_cats as $cat)
		<li>
			{{ HTML::link('lex_semantic_category/' . $cat->id, $cat->text, array('title' => $cat->text . ' and subcategories thereof' )) }}
		</li>
	@endforeach
</ul>