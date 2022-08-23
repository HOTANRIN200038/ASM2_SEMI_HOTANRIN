<?php
/* make icons float icons even widget is not active  */
function sfsi_plus_frontFloter() {
	$sfsi_section8 = maybe_unserialize( get_option( 'sfsi_plus_section8_options', false ) );
	if( isset( $sfsi_section8['sfsi_plus_float_on_page'] ) && $sfsi_section8['sfsi_plus_float_on_page'] == "yes" ) {
		$output = '';
		ob_start();
			/* call the all icons function under sfsi_plus_widget.php file */
			echo sfsi_plus_check_visiblity(1);
		$output = ob_get_contents();
		ob_end_clean();
		echo $output;
	}
}