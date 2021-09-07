<?php

/*
	Template name: questionnaire
*/

get_header();

$user_id = get_current_user_id();

// Заполнена анкета или нет
$questionnaire_complete = get_field( 'questionnaire_complete', $user_id ) ?>

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

get_footer();