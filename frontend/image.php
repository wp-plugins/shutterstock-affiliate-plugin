    <div class="shutterstock_img_container shutterstock_ap_border_color">
      <div class="shutterstock_img_border">
        <a data-tooltip-<?php echo $element_id; ?>="<?php echo $element_id.'_shutterstock_tooltip_'.$c; ?>" class="shutterstock_preview_link shutterstock_ap_detail" <?php echo ($image_detail > 0?'href="'.$item['photo_id'].'" onclick="return false;" rel="'.$item['affiliation_link'].'"':'href="'.$item['affiliation_link'].'"'); ?><?php echo ($settings['open_links']?' target="_blank"':''); ?>>
          <?php
            if ($thumb_size == 0) $thumb = 'thumb_small';
            else $thumb = 'thumb_large';
            
            $nwidth = $this->thumbnail_sizes[$thumb_size][0];
            $nheight = $this->thumbnail_sizes[$thumb_size][1];
            
            if ($item[$thumb]['width'] > $item[$thumb]['height'])
              $nheight = ($item[$thumb]['height'] / ($item[$thumb]['width'] / $nwidth));
            else
              $nwidth = ($item[$thumb]['width'] / ($item[$thumb]['height'] / $nheight));

            if ($nwidth > $this->thumbnail_sizes[$thumb_size][0])
            {
              $width = $this->thumbnail_sizes[$thumb_size][0];
              $height = ($item[$thumb]['height'] / ($item[$thumb]['width'] / $width));
            }
            else            
            {
              if ($nheight > $this->thumbnail_sizes[$thumb_size][1]) $nheight = $this->thumbnail_sizes[$thumb_size][1];
              $width = ($item[$thumb]['width'] / ($item[$thumb]['height'] / $nheight));
              $height = $nheight;
            }
            
            if ($width-20 > $height)
            {
              $whidden = 0;
              $hhidden = $thumb_size == 0?10:14;
            }
            else
            {
              $whidden = $thumb_size == 0?7:12;
              $hhidden = 0;
            }
            
            $width = $item[$thumb]['width'];
            $height = $item[$thumb]['height'];
            
            //echo $width.'x'.$height.' - '.$nwidth.'x'.$nheight.' - '.$item[$thumb]['width'].'x'.$item[$thumb]['height'];
          ?>
          <!--
          style="width: <?php echo $this->thumbnail_sizes[$thumb_size][0]; ?>px; height: <?php echo $this->thumbnail_sizes[$thumb_size][1]; ?>px;"
          -->
          <div style="padding-bottom: <?php echo 75 - $item[$thumb]['height']/2; ?>px;">
            <div style="margin: auto auto; overflow: hidden; width: <?php echo $width; ?>px; height: <?php echo $height; ?>px;">
              <div style="width: 500px; height: 500px; text-align: left;">
                <img src="<?php echo $item[$thumb]['url']; ?>" alt="<?php echo esc_attr($item['description']); ?>" />
              </div>
            </div>
          </div>          
        </a>
      </div>
    </div>                