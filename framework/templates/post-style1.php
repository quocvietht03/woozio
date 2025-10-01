<?php
$post_id = get_the_ID();

?>
<article <?php post_class('bt-post'); ?>>
  <div class="bt-post--inner">
    <?php echo woozio_post_cover_featured_render($args['image-size']); ?>
    <div class="bt-post--content">
      <?php
      echo woozio_post_meta_render();
      echo woozio_post_title_render();
      ?>
    </div>

  </div>
</article>