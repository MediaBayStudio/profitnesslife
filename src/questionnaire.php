<?php

/*
	Template name: questionnaire
*/

get_header( 'account' );

// $user_id определяется в functions.php
// $questionnaire_complete определяется в functions.php
$questionnaire_section_class = $questionnaire_complete ? 'complete' : 'incomplete' ?>

<section class="container account-page-wrapper questionnaire-<?php echo $questionnaire_section_class ?>"> <?php

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

// get_footer();