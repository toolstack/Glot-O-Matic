jQuery(document).ready( function() {
	var gp_rpb_footer = jQuery('#gp-footer');
	var gp_rpb_footer_content = gp_rpb_footer.html();
	
	gp_rpb_footer_content = gp_rpb_footer_content.replace( /(.*--GP_RPB_MARKER--)/g, '<span style="display:none">' );
	
	gp_rpb_footer.html( gp_rpb_footer_content );
	
	
});