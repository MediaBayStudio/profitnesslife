<?php
function popup_content( $args ) {
  global $template_directory_uri, $exclude_gluten ?>
  <div class="popup__text-block">
    <span class="popup__text-block-title"><?php echo $args['title'] ?></span> <?php
    foreach ( $args['tags'] as $tag => $value ) :
      if ( $tag === 'p' || $tag === 'p2' || $tag === 'p3' ) : ?>
        <p class="popup__descr"><?php echo $value ?></p> <?php
      endif;
      if ( $tag === 'recommend' ) : ?>
        <span class="popup__recommend lazy" data-src="#"><?php echo $value ?></span> <?php
      endif;
      if ( $tag === 'images' || $tag === 'images2' ) :
        foreach ( $value as $img ) :
          if ( is_array( $img ) ) :
            $alt = $args['tags']['images']['alt'] ?: $img['alt'] ?>
            <picture class="popup__pic">
              <source type="image/webp" srcset="<?php echo $template_directory_uri . $img['webp'] ?>">
              <img src="<?php echo $template_directory_uri . $img['url'] ?>" alt="<?php echo $alt ?>" class="popup__img">
            </picture> <?php
          endif;
        endforeach;
      endif;
      if ( $tag === 'ul' ) : ?>
        <ul class="popup__ul"> <?php
          foreach ( $value as $list_item ) : ?>
            <li class="popup__list-item"><?php echo $list_item ?></li> <?php
          endforeach ?>
        </ul> <?php
      endif;
    endforeach; ?>
  </div> <?php
}