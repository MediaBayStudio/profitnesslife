<?php
function php_path_join( $base, ...$paths ) {
    $path = '';
    $trim_symbols = '/ \\';
    $base = str_replace( ['\\', '/'], DIRECTORY_SEPARATOR, $base );
    
    foreach ( $paths as $p ) {
        $path .= DIRECTORY_SEPARATOR . trim( $p, $trim_symbols );
    }
    
    return rtrim( $base, $trim_symbols ) . $path;
}