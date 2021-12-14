<?php

/*
	Template name: index
*/

get_header();

$csv_array = csv_to_array( $template_directory . '/213.csv' );

function csv_to_array( $filename='', $delimiter=',' ) {
  if( !file_exists($filename) || !is_readable($filename) )
      return false;

  $header = null;
  $data = [];

  if ( ($handle = fopen( $filename, 'r' )) !== false ) {
    while ( ($row = fgetcsv( $handle, 1000, $delimiter )) !== false ) {
      if ( !$header ) {
        $header = $row;
      } else {
        $data[] = array_combine( $header, $row );
      }
    }
    fclose( $handle );
  }
  return $data;
}

$dish = $csv_array[0];

$ingredients = preg_split( '/\n/', $dish['Ингредиенты'] );

echo '<h2>Ингредиенты:</h2>';
foreach ( $ingredients as $key => $value ) {
  $ingredient_data = explode( '/', $value );
  $ingredients[ $key ] = [
    'name' => $ingredient_data[0],
    'count' => $ingredient_data[1],
    'units' => $ingredient_data[2]
  ];

  echo "<p><span>{$ingredient_data[0]}</span> <span>{$ingredient_data[1]}</span> <span>{$ingredient_data[2]}</span></p>";
}

// var_dump( $ingredients );

foreach ( $GLOBALS['sections'] as $section ) {
	if ( $section['is_visible'] ) {
		$section_id = $section['sect_id'] ? ' id="' . $section['sect_id'] . '"' : '';
		require 'template-parts/' . $section['acf_fc_layout'] . '.php';
	}
}

get_footer();