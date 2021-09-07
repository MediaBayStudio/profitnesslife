<?php

/*
	Template name: index
*/

get_header();

foreach ( $GLOBALS['sections'] as $section ) {
	require 'template-parts/' . $section['acf_fc_layout'] . '.php';
}

get_footer();