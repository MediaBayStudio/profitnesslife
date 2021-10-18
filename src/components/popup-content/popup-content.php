<?php
function popup_content( $args ) {
  global $template_directory_uri, $exclude_gluten ?>
  <div class="popup__text-block">
    <span class="popup__text-block-title"><?php echo $args['title'] ?></span> <?php
    foreach ( $args['tags'] as $tag => $value ) :
      if ( $tag === 'p' || $tag === 'p2' ) : ?>
        <p class="popup__descr"><?php echo $value ?></p> <?php
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
    endforeach; ?>
  </div> <?php
}