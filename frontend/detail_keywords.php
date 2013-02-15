  <div class="shutterstock_keywords shutterstock_ap_border_color"><?php _e('Keywords:', self::ld); ?>
  <?php
    $k = $r['keywords'];
    reset($k);
    $keywords = array();    
    while(list(, $keyword) = @each($k))
    {
      $keywords[] = '<a href="'.site_url().'/?s='.urlencode($keyword).'">'.ucfirst($keyword).'</a>';      
    }
    echo implode(', ', $keywords);
  ?> 
  </div>