<script type="text/javascript">
function calcHeight()
{
	//find the height of the internal page
	var the_height=
	document.getElementById('the_iframe').contentWindow.
	document.body.scrollHeight;

	//change the height of the iframe
	document.getElementById('the_iframe').height=
	the_height;
}
</script>
<iframe src="/admin2/eieol_language" width="100%" onLoad="calcHeight();" height="1px" id="the_iframe"></iframe>