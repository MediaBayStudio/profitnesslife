<?php

/*
	Template name: account
*/

get_header();

if ( is_user_logged_in() ) { ?>
	<section class="container account-page-wrapper"> <?php

		require 'template-parts/side-menu.php' ?>

		<section class="account-page-sections"> <?php
			foreach ( $GLOBALS['sections'] as $section ) {
				if ( $section['is_visible'] ) {
					$section_id = $section['id'] ? ' id="' . $section['id'] . '"' : '';
					require 'template-parts/' . $section['acf_fc_layout'] . '.php';
				}
			} ?>
		</section>
	</section> <?php
} else {
	rcl_include_template( 'form-sign.php' );
}

get_footer();