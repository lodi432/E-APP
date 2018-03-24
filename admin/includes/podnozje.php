</div>

<br><br><br>
<footer class ="text-center" id="footer">&copy; All rights reserved </footer>


<script>
function get_child_options(){
	var parentID = jQuery('#parent').val();
	jQuery.ajax({
	     url: '/EcomApp/admin/parsers/child_categories.php',
	     type: 'POST',
	     data: {parentID : parentID},
	     success: function(data){
	        jQuery('#child').html(data);
	     },
	     error: function(){alert("Something went wrong with the child options.")},
	});

}
jQuery('select[name="parent"]').change(get_child_options);

</script>
</body>
