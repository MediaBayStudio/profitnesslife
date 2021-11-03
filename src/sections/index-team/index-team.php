<section class="index-team container sect" id="team">
  <h2 class="index-team__title sect-title"><?php echo $section['title'] ?></h2>
  <div class="index-team__team lazy" data-src="#"> <?php
    foreach ( $section['team'] as $person ) : ?>
      <div class="index-team__person">
        <picture class="index-team__person-pic lazy"> <?php
          $webp = get_post_meta( $person['img']['id'], 'webp' )[0];
          if ( $webp ) : ?>
            <source type="image/webp" srcset="#" data-srcset="<?php echo $upload_baseurl . $webp ?>"> <?php
          endif ?>
          <img src="#" data-src="<?php echo $person['img']['url'] ?>" alt="<?php echo $person['name'] ?>" class="index-team__person-img">
        </picture>
        <div class="index-team__person-info">
          <span class="index-team__person-name"><?php echo $person['name'] ?></span>
          <p class="index-team__person-role"><?php echo $person['role'] ?></p> <?php
            foreach ( $person['props'] as $prop ) : ?>
              <div class="index-team__person-prop">
                <span class="index-team__person-prop-title"><?php echo $prop['title'] ?></span> <?php
                if ( $prop['view'] === 'list' ) : ?>
                  <ul class="index-team__person-prop-list"> <?php
                    foreach ( $prop['list'] as $li ) : ?>
                      <li class="index-team__person-prop-list-item"><?php echo $li['li'] ?></li> <?php
                    endforeach ?>
                  </ul> <?php
                elseif ( $prop['view'] === 'text' ) : ?>
                  <p class="index-team__person-prop-descr"><?php echo $prop['text'] ?></p> <?php
                elseif ( $prop['view'] === 'files' ) : ?>
                  <ul class="index-team__person-prop-files"> <?php
                    foreach ( $prop['files'] as $file ) : ?>
                      <li class="index-team__person-prop-file">
                        <a href="<?php echo $file['file']['url'] ?>" target="_blank" class="index-team__person-prop-file-link">
                          <img src="#" alt="#" data-src="<?php echo $template_directory_uri . '/img/' . str_replace( '/', '-', $file['file']['mime_type'] ) ?>.svg" class="index-team__person-prop-file-icon lazy">
                          <span class="index-team__person-prop-file-title"><?php echo $file['file']['title'] ?></span>
                        </a>
                      </li> <?php
                    endforeach ?>
                  </ul> <?php
                endif ?>
              </div> <?php
            endforeach ?>
        </div>
      </div> <?php
    endforeach ?>
    <div class="index-team__nav"></div>
  </div>
</section>