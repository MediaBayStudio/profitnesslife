<section class="reviews-sect container sect"<?php echo $section_id ?>>
  <h2 class="reviews-sect__title sect-title"><?php echo $section['title'] ?></h2>
  <div class="reviews-sect__reviews lazy" data-src="#"> <?php
    $reviews = $section['by_default'] === false ? $section['reviews'] : get_posts( [
      'post_type' => 'review',
      'numberposts' => -1,
      'order' => 'ASC'
    ] );
    foreach ( $reviews as $review ) :
      $review_fields = get_fields( $review ) ?>
      <div class="reviews-sect__review review">
        <a href="https://www.instagram.com/<?php echo $review_fields['author_insta'] ?>" target="_blank" class="review__author">
          <picture class="review__author-pic lazy">
            <source type="image/webp" srcset="#" data-srcset="<?php echo $upload_baseurl . get_post_meta( $review_fields['author_img']['ID'] )['webp'][0] ?>"> <?php
              $alt = $review_fields['author_img']['alt'] ?: 'Автор отзыва' ?>
            <img src="#" alt="<?php echo $alt ?>" data-src="<?php echo $review_fields['author_img']['url'] ?>" class="review__author-img">
          </picture>
          <div class="review__author-text">
            <span class="review__author-name"><?php echo $review_fields['author_name'] ?></span>
            <span class="review__author-insta">&#64;<?php echo $review_fields['author_insta'] ?></span>
          </div>
        </a>
        <div class="review__photos">
          <div class="review__before">
            <picture class="review__before-pic lazy"> <?php
              $alt = $review_fields['image_before']['alt'] ?: 'Фото до' ?>
              <source type="image/webp" srcset="#" data-srcset="<?php echo $upload_baseurl . get_post_meta( $review_fields['image_before']['ID'] )['webp'][0] ?>">
              <img src="#" alt="<?php echo $alt ?>" data-src="<?php echo $review_fields['image_before']['url'] ?>" class="review__before-img">
            </picture>
            <span>До</span>
            <span class="review__before-weight"><?php echo $review_fields['weight_before'] ?> кг</span>
          </div>
          <div class="review__after">
            <picture class="review__after-pic lazy"> <?php
              $alt = $review_fields['image_after']['alt'] ?: 'Фото после' ?>
              <source type="image/webp" srcset="#" data-srcset="<?php echo $upload_baseurl . get_post_meta( $review_fields['image_after']['ID'] )['webp'][0] ?>">
              <img src="#" alt="<?php echo $alt ?>" data-src="<?php echo $review_fields['image_after']['url'] ?>" class="review__after-img">
            </picture>
            <span>После</span>
            <span class="review__after-weight"><?php echo $review_fields['weight_after'] ?> кг</span>
          </div>
        </div>
        <p class="review__text lazy" data-src="#"><?php echo $review_fields['text'] ?></p>
      </div> <?php
    endforeach ?>
    <div class="reviews-sect__nav"></div>
  </div>
</section> <?php
unset( $alt );