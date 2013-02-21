<?php
echo '<style type="text/css">';
$color = $settings['theme_color']<9?$this->theme_colors[$settings['theme_color']][1]:$settings['theme_color_custom'];
$background = $color;
$border = $color;
$background_hover = $this->hover_color($color);
?>

#cboxLoadingGraphic
{
  background:url('<?php echo $this->plugin_url.'/frontend/loaders/'.$this->loader_colors[$settings['loader_color']][1]; ?>') no-repeat center center;
}

.shutterstock_ap_active_page_color
{
  background-color: <?php echo $background; ?> !important;
  border-color: <?php echo $border; ?> !important;  
}

.shutterstock_ap_border_color
{
  border-color: <?php echo $border; ?> !important;  
}

.shutterstock_ap_button_color, .shutterstock_ap_button_color:visited
{
  background-color: <?php echo $background; ?> !important;
}

.shutterstock_ap_button_color:hover
{
  background-color: <?php echo $background_hover; ?> !important;
}

<?php
if ($settings['show_boxes'])
{
?>
.shutterstock_img_container, .shutterstock_pages_container a
{
	border-width: 2px;
	border-style: solid;  
}
<?php
}
?>
<?php    
echo '</style>';
?>