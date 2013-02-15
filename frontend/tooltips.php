<div id="<?php echo $element_id.'_shutterstock_tooltips'; ?>" class="stickytooltip">
  <div style="padding:5px">
  <?php
    $c = 0;
    foreach($tooltips as $tooltip)
    {
  ?>
      <div id="<?php echo $element_id.'_shutterstock_tooltip_'.$c; ?>" class="atip">
        <div style="overflow: hidden; width: <?php echo $tooltip['image_width']; ?>px; height: <?php echo $tooltip['image_height']-15; ?>px;">
          <img src="<?php echo $tooltip['image_url']; ?>" width="<?php echo $tooltip['image_width']; ?>" height="<?php echo $tooltip['image_height']; ?>" alt="<?php echo esc_attr($tooltip['title']); ?>" /><br />
        </div>
        <div style="float: left; width: <?php echo $tooltip['image_width'] - 20; ?>px">
          <?php echo $tooltip['title']; ?>
        </div>
        <div class="shutterstock_ap_clear"></div>
      </div>
  <?php
      $c++;
    }
  ?>
  </div>
</div>