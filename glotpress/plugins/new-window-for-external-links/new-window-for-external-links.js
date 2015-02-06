jQuery(document).ready( function() {
	var wefel_root = location.protocol + '//' + location.host;

	jQuery('a').each( function() {
		if( this.href.indexOf( wefel_root ) == -1 && this.href.indexOf( 'javascript') == -1 )
			{
			this.target = "_blank";
			}
	});
});