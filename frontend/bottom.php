  <?php
  if ($show_more)
  {
  ?>
    <div class="shutterstock_ap_show_more_results">
      <a href="<?php echo get_bloginfo('siteurl'); ?>/?s=<?php echo urlencode($search); ?>&creator=<?php echo $creator_id; echo $image_results == 0?'&img=':''; ?>"><?php _e('Show more results', self::ld); ?></a>
    </div>  
  <?php    
  }
  ?>
  </div>
</div>