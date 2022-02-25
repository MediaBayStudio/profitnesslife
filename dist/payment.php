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
		require 'template-parts/payment-hero.php' ?>
	</section>
</section>
<div id="fake-scrollbar"></div> <?php
      wp_footer() ?>
    </div>
  </body>
</html> <?php
 // get_footer();