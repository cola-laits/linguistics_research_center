<ul class="side-nav">
    <li>
        <label>
            <img src="/images/dieboldsm.jpg" alt="A. Richard Diebold Center for Indo-European Language and Culture" />
        </label>
    </li>
</ul>
<div style="clear:both;"></div>
<ul class="side-nav">
    <li class="office"><label>Semantic Fields</label></li>
    @foreach($alpha_cats as $cat)
        <li>
            <a href="/lex/semantic/category/{{$cat->abbr}}" title="{{$cat->text}} and subcategories thereof">{{$cat->text}}</a>
        </li>
    @endforeach
</ul>
