<div id="<?php echo $this->id; ?>_div">  
</div>

<script>
jQuery(document).ready(function()
{
  new Shutterstock_AP(
            {
              'element_id': '<?php echo $this->id; ?>_div',
              'show_method': 0,
              'loader_html': Shutterstock_AP_data.loader,
              'search_text': '<?php echo addcslashes($keywords, "'"); ?>',
              'number_images': <?php echo $number_images; ?>,
              'creator_id': '<?php echo addcslashes($creator_id, "'"); ?>',
              'show_extra_search': <?php echo $show_search?1:0; ?>,
              'show_header': <?php echo $show_header?1:0; ?>,
              'show_paging': <?php echo $show_paging?1:0; ?>,
              'show_more': <?php echo $show_more?1:0; ?>,
              'show_logo': <?php echo $show_logo?1:0; ?>,
              'show_tooltip': <?php echo $show_tooltip?1:0; ?>,
              'auto_scroll': 0,
              'order': parseInt(Shutterstock_AP_data.order),                                      
              'is_widget': true,   
              'images_type': parseInt(Shutterstock_AP_data.images_type),
              'thumb_size': parseInt(Shutterstock_AP_data.thumb_size),
              'image_detail': parseInt(Shutterstock_AP_data.image_detail),
              'swap_keycustom': parseInt(Shutterstock_AP_data.show_keycustom),
              'show_keywords': parseInt(Shutterstock_AP_data.show_keywords),
              'show_customcontent': parseInt(Shutterstock_AP_data.show_customcontent),
              'image_results': <?php echo (int)$image_results; ?>
            });            
});
</script>