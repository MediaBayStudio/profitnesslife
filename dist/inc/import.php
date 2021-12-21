<?php

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

// Первая буква заглавная
function my_mb_ucfirst( $str ) {
  $fc = mb_strtoupper( mb_substr( $str, 0, 1 ) );
  return $fc . mb_substr( $str, 1 );
}