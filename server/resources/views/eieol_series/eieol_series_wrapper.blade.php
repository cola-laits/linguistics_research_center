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

<!-- the css on the following tags is so modal popups will work correctly.  Otherwise they open at the top of the page. -->

<div  style="position: fixed;top: 0px;left: 0px;right: 0px;bottom: 0px;">
	<iframe src="/admin2/eieol_series" width="100%" onLoad="calcHeight();" height="1px" id="the_iframe" style="height: 100%; width: 100%;"></iframe>
</div>