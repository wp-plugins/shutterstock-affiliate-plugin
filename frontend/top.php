<?php

$url = 'http://www.shutterstock.com/cat.mhtml?searchterm='.urlencode($search).'&search_group=&lang='.$language.'&search_source=search_form';
if ($main_aff_link)
  $url = $main_aff_link.urlencode($url);

if ($show_header)
{
?>
<h4>
  <?php _e('Found:', self::ld); ?> <?php echo number_format($r['count'], 0, '', ','); ?>
  <b><?php echo $search; ?></b>
  <?php 
  if ($images_type == 0)
    _e('Stock Photos, Illustrations, Vectors at', self::ld);
  else if ($images_type == 1)
    _e('Stock Photos at', self::ld);
  else if ($images_type == 2)
    _e('Ilustrations at', self::ld);
  else if ($images_type == 3)
    _e('Vectors at', self::ld);
  ?>
  <a href="<?php echo $url; ?>" target="_blank"><?php _e('Shutterstock', self::ld); ?></a>
</h4>
<?php
}
?>

<?php
if ($show_extra_search)
{
?>
<div class="shutterstock_searchform shutterstock_ap_border_color">
  <form action="<?php echo get_bloginfo('siteurl'); ?>" id="searchformi" method="get" role="search">
    <input type="hidden" name="creator" value="<?php echo esc_attr($creator_id); ?>" />
    <?php echo ($only_img || $image_results == 0)?'<input type="hidden" name="img" value="" />':''; ?>
    <div>
      <input type="text" id="si" name="s" onfocus="this.value = '';" value="<?php echo htmlentities($search); ?>" />
      <input class="large shutterstock-awesome shutterstock_ap_button_color" onclick="return document.getElementById('si').value.length > 0" type="submit" value="<?php esc_attr_e('Search', self::ld); ?>" />
      <?php
      if ($show_logo) {
      ?>
        <a href="<?php echo $url; ?>" target="_blank"><img class="shutterstock_ap_search_logo" border="0" src="<?php echo $this->plugin_url; ?>/frontend/images/shutterstock-logo.png" /></a>
      <?php
      }
      ?>
    </div>
  </form>  
</div>
<?php
}
?>

<?php
if ($show_header)
{
?>
<div id="shutterstock_image-sort">
  <div class="shutterstock_select">
    <label for="sap_sort_order"><?php _e('View:', self::ld); ?></label>
    <select name="sap_sort_order" class="sap_sort_order" id="sap_sort_order">
      <option value="0"<?php echo ($order == 0?' selected':''); ?>><?php _e('Popular', self::ld); ?></option>
      <option value="1"<?php echo ($order == 1?' selected':''); ?>><?php _e('Newest', self::ld); ?></option>
      <option value="2"<?php echo ($order == 2?' selected':''); ?>><?php _e('Oldest', self::ld); ?></option>
      <option value="3"<?php echo ($order == 3?' selected':''); ?>><?php _e('Random', self::ld); ?></option>
      <option value="3"<?php echo ($order == 4?' selected':''); ?>><?php _e('Relevance', self::ld); ?></option>
    </select>
  </div>
</div>
<?php
}
?>

<?php
if ($show_paging)
{
  require $this->plugin_path.'/frontend/paging.php';
}        
?>

<div class="<?php echo $is_widget?'shutterstock_main_container_widget':'shutterstock_main_container'; ?>">
  <div class="shutterstock_sub_container">