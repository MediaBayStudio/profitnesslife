<?php

/*
	Template name: index
*/

get_header(); ?>
<!-- <script>
	function importDB() {
		fetch('<?php #echo site_url() ?>/wp-admin/admin-ajax.php', {
      method: 'POST',
      body: 'action=import',
      headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    	}
    })
    .then(function(response) {
      if (response.ok) {
        return response.text();
      } else {
        console.log('Ошибка ' + response.status + ' (' + response.statusText + ')');
        return '';
      }
    })
    .then(function(response) {
      console.log(response);
    })
    .catch(function(err) {
      console.log(err);
    });
	}
</script>

<button onclick="importDB()" class="btn btn-green" style="width: 200px; height: 55px;">import</button> -->

<?php


// $posts = get_posts( [
//   'numberposts' => -1,
//   'post_type' => 'dish'
// ] );

// foreach ( $posts as $post ) {
//   $post_id = $post->ID;
//   $fields = get_fields( $post_id );

//   foreach ( $fields['ingredients'] as $ingredient ) {
//     $ingredient_object = $ingredient['title'];
//     if ( !$ingredient_object ) {
//       echo "<p>{$post->post_title} ID: {$post_id}</p>";
//     }
//   }
// }

foreach ( $GLOBALS['sections'] as $section ) {
	if ( $section['is_visible'] ) {
		$section_id = $section['sect_id'] ? ' id="' . $section['sect_id'] . '"' : '';
		require 'template-parts/' . $section['acf_fc_layout'] . '.php';
	}
}

get_footer();