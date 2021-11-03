<?php
// Меню на сайте
  add_action( 'after_setup_theme', function() {
    register_nav_menus( [
      'header_menu' =>  'Меню в шапке сайта',
      'side_menu' =>  'Боковое меню на сайте',
      // 'mobile_menu' =>  'Мобильное меню на сайте',
      'footer_menu' =>  'Меню в подвале сайта'
    ] );
  } );

// добавить класс для ссылки в меню (a)
  add_filter( 'nav_menu_link_attributes', function( $atts, $item ) {
    $atts['class'] = 'nav-link';
    $icon = get_field( 'img', $item->object_id );
    if ( $icon ) {
      $atts['data-icon'] = $icon;
    }
    // if ( is_user_logged_in() ) {
    //   $user_id = get_current_user_id();
    //   $user_query = '?user=' . $user_id;

    //   if ( $atts['href'][0] === '#' ) {
    //     $atts['href'] = $user_query . $atts['href'];
    //   } else {
    //     $atts['href'] .= $user_query;
    //   }
      
    // }
    return $atts;
  }, 10, 2);

add_filter( 'wp_nav_menu_items', function( $items, $args ) {
  if ( $args->theme_location === 'side_menu' ) {
    return preg_replace( '/(<a.*?data-icon="(.*?)">)/', '<picture class="side-menu__pic"><source srcset="$2" media="(min-width:767.98px)"><img src="#" alt="Иконка" data-src="$2" class="side-menu__img"></picture>$1', $items );
  } else {
    return $items;
  }
  
}, 10, 2 );

// задать свои классы для пунктов меню (li)
  add_filter( 'nav_menu_css_class', function( $classes, $item, $args, $depth ) {
    global $questionnaire_show;
    $container_class = $args->container_class;
    $li_class = '';

    switch ( $container_class ) {
      case 'side-menu__nav':
        $li_class = 'side-menu__nav-li';
        // if ( ($item->ID === 424 || $item->ID === 423) && !$questionnaire_show ) {
        //   $li_class .= ' disabled';
        // }
        break;
      case 'hdr__nav':
        $li_class = 'hdr__nav-li';
        break;
      case 'ftr__nav':
        $li_class = 'ftr__nav-li';
        break;
      case 'menu__nav':
        $li_class = 'menu__nav-li';
        break;
      default:
        $li_class = 'nav__li';
        break;
    }

    $classesArray = [ $li_class ];

    foreach ( $classes as $class ) {
      if ( $class === 'current-menu-item' ) {
        $classesArray[] = 'current';
      }
    }

    return $classesArray;
  }, 10, 4);

// убрать id у пунктов меню
  add_filter( 'nav_menu_item_id', function( $menu_id, $item, $args, $depth ) {
    return '';
  }, 10, 4);