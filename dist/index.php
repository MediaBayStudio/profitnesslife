<?php

/*
	Template name: index
*/

get_header();

foreach ( $GLOBALS['sections'] as $section ) {
	if ( $section['is_visible'] ) {
		$section_id = $section['id'] ? ' id="' . $section['id'] . '"' : '';
		require 'template-parts/' . $section['acf_fc_layout'] . '.php';
	}
}

get_footer();