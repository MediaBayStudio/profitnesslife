<?php

/*
	Template name: pay
*/

get_header( 'account' ) ?>
<style>
	#page-wrapper::before {
		width: 100% !important;
		border-radius: 0 !important;
	}
	.header-account {
		background: #fff !important;
	}
</style>
<section class="container account-page-wrapper"> <?php
	#require 'template-parts/side-menu.php' ?>

	<section class="account-page-sections"> <?php
		foreach ( $GLOBALS['sections'] as $section ) {
			if ( $section['is_visible'] ) {
				$section_id = $section['id'] ? ' id="' . $section['id'] . '"' : '';
				require 'template-parts/' . $section['acf_fc_layout'] . '.php';
			}
		} ?>
	</section>
</section>
<div id="fake-scrollbar"></div> <?php
      wp_footer() ?>
    </div>
  </body>
</html> <?php
 // get_footer();