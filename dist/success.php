<?php
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

file_put_contents( get_template_directory() . '/file.txt', json_encode( $_POST ) );

die();