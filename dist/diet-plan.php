<?php

/*
	Template name: diet-plan
*/

get_header( 'account' ) ?>

<section class="container account-page-wrapper"> <?php
	require 'template-parts/side-menu.php' ?>

	<section class="account-page-sections"> <?php
		echo '<p>Здесь будет план питания</p>';
		foreach ( $GLOBALS['sections'] as $section ) {
			if ( $section['is_visible'] ) {
				$section_id = $section['id'] ? ' id="' . $section['id'] . '"' : '';
				require 'template-parts/' . $section['acf_fc_layout'] . '.php';
			}
		} ?>
	</section>
</section> <?php
get_footer();