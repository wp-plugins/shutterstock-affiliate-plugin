<div class="shutterstock_img_details">
  <?php if ($image_detail == 1) { ?>
    <div class="shutterstock_ap_div_back_button"><a href="" onclick="return false;" class="small shutterstock-awesome shutterstock_ap_button_color shutterstock_ap_back_button"><?php _e('&laquo; Back', self::ld); ?></a></div>
  <?php } ?>
  <a href="<?php echo $_POST['aff_link']; ?>"<?php echo ($settings['open_links']?' target="_blank"':''); ?>>
    <h3><?php echo $r['description']; ?></h3>
  </a>
  <div class="shutterstock_author">
    <h4>&copy; <?php echo $r['submitter']; ?></h4>  
  </div>
  <div class="shutterstock_detail-image">
    <a href="<?php echo $_POST['aff_link']; ?>"<?php echo ($settings['open_links']?' target="_blank"':''); ?>>
      <img src="<?php echo $r['sizes']['preview']['url']; ?>" alt="<?php echo esc_attr($r['description']); ?>" />
    </a>
  </div>
  <div class="shutterstock_buy-now">
    <a href="<?php echo $_POST['aff_link']; ?>"<?php echo ($settings['open_links']?' target="_blank"':''); ?> class="xlarge shutterstock-awesome shutterstock_ap_button_color" target="_self">Buy now</a>
  </div>
  <br />          
  <?php
  if ($swap_keycustom)
  {
    if ($show_customcontent)
      require_once 'detail_customcontent.php';
  }
  else
    if ($show_keywords)
      require_once 'detail_keywords.php';
  ?>  
  <?php if ($image_detail == 1) { ?>
    <div class="shutterstock_ap_div_back_button"><a href="" onclick="return false;" class="small shutterstock-awesome shutterstock_ap_button_color shutterstock_ap_back_button"><?php _e('&laquo; Back', self::ld); ?></a></div>
    <br />
  <?php } ?>
  <?php
  if ($swap_keycustom)
  {
    if ($show_keywords)
      require_once 'detail_keywords.php';
  }
  else
    if ($show_customcontent)
      require_once 'detail_customcontent.php';
  ?>
</div>