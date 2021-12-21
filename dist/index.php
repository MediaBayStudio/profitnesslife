<?php

/*
	Template name: index
*/

get_header();

// echo '<p>Время окончания марафона ' . $finish_marathon_time . '</p>';
// echo '<p>Текущее время ' . $current_time . '</p>';

// var_dump( $current_time >= $finish_marathon_time );

// $csv_array = csv_to_array( $template_directory . '/db.csv' );

foreach ( $csv_array as $dish ) {
	$title = $dish['Название блюда'];
	$recipe = $dish['Рецепт'];
	$ingredients = str_replace( ['Соль Перец', 'Соль перец'], "Соль\nПрец", $dish['Ингредиенты'] );
	$ingredients = preg_replace( '/\s(?=$)/', '', $ingredients );
	$ingredients = str_replace( '', "\n", $ingredients );
	$ingredients = preg_split( '/\n/', $ingredients );
	$calories = $dish['ККАЛ'];
	$type = $dish['Тип'];
	$categories = explode( ',', $dish['Категории'] );
	$target = explode( ',', $dish['Цель'] );

	// echo "<h2 style='margin-top:25px'>{$title}</h2>";
	// echo "<h3>Рецепт:</h3>";
	// echo "<p>{$recipe}</p>";
	// echo "<h3>Калории:</h3>";
	// echo "<p>{$calories}</p>";
	// echo "<h3>Тип:</h3>";
	// echo "<p>{$type}</p>";
	// echo '<h3>Ингредиенты:</h3>';
	foreach ( $ingredients as $key => $value ) {
	  $ingredient_data = explode( '/', $value );
	  $ingredient_name = my_mb_ucfirst( preg_replace( '/\s(?=$)/', '', $ingredient_data[0] ) );
	  $ingredient_count = $ingredient_data[1];
	  $ingredient_units = $ingredient_data[2];
	  $ingredients_array[] = [
	    'name' => $ingredient_name,
	    'count' => $ingredient_count,
	    'units' => $ingredient_units
	  ];

	  // if ( !in_array( $ingredient_name, $ingredients_names_array ) ) {
	  // 	$ingredients_names_array[] = $ingredient_name;
	  // }

	  // if ( !in_array( $ingredient_units, $ingredients_units_array ) ) {
	  // 	$ingredients_units_array[] = $ingredient_units;
	  // }

	  // if ( !in_array( $ingredient_count, $ingredients_count_array ) ) {
	  // 	$ingredients_count_array[] = $ingredient_count;
	  // }

	  // echo "<p><span>{$ingredient_name}</span> <span>{$ingredient_count}</span> <span>{$ingredient_units}</span></p>";
	}
}

// sort( $ingredients_names_array );
// sort( $ingredients_units_array );
// sort( $ingredients_count_array );

// foreach (	$ingredients_names_array as $i ) {
	// echo "<p>$i</p>";
// }

// foreach (	$ingredients_units_array as $i ) {
	// echo "<p>$i</p>";
// }

// foreach (	$ingredients_count_array as $i ) {
	// echo "<p>$i</p>";
// }

// var_dump( $ingredients_array );

foreach ( $GLOBALS['sections'] as $section ) {
	if ( $section['is_visible'] ) {
		$section_id = $section['sect_id'] ? ' id="' . $section['sect_id'] . '"' : '';
		require 'template-parts/' . $section['acf_fc_layout'] . '.php';
	}
}

get_footer();