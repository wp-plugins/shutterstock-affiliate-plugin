<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', Shutterstock_AP::ld); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_id('keywords'); ?>"><?php _e('Keywords', Shutterstock_AP::ld); ?></label> 
	<input title="<?php _e('Search these keywords', Shutterstock_AP::ld); ?>"<?php echo $auto_keywords?' disabled':''; ?> class="widefat" id="<?php echo $this->get_field_id('keywords'); ?>" name="<?php echo $this->get_field_name('keywords'); ?>" type="text" value="<?php echo esc_attr($keywords); ?>" />
  <div style="margin-left: 10px; margin-top: 2px;">
    <input type="checkbox"<?php echo $auto_keywords?' checked':''; ?> onclick="document.getElementById('<?php echo $this->get_field_id('keywords'); ?>').disabled = this.checked;" name="<?php echo $this->get_field_name('auto_keywords'); ?>" id="<?php echo $this->get_field_id('auto_keywords'); ?>" />
    <label for="<?php echo $this->get_field_id('auto_keywords'); ?>"><?php esc_html_e('Search keywords in the content', Shutterstock_AP::ld); ?></label>
  </div>
</p>

<p>
	<label for="<?php echo $this->get_field_id('creator_id'); ?>"><?php _e('Show result by creator ID', Shutterstock_AP::ld); ?></label> 
	<input title="<?php _e("Simply leave blank if you don't want restrict search result by a creator ID.", Shutterstock_AP::ld); ?>" class="widefat" id="<?php echo $this->get_field_id('creator_id'); ?>" name="<?php echo $this->get_field_name('creator_id'); ?>" type="text" value="<?php echo esc_attr($creator_id); ?>" />
</p>

<p>
	<label for="<?php echo $this->get_field_id('number_images'); ?>"><?php _e('Images per page', Shutterstock_AP::ld); ?></label>
  <select title="<?php _e('Select the maximum numbers of images per page.', Shutterstock_AP::ld); ?>" id="<?php echo $this->get_field_id('number_images'); ?>" name="<?php echo $this->get_field_name('number_images'); ?>">
    <?php
    for($i=1;$i<=30;$i++)
    {
      if (150 % $i != 0) continue;
    ?>  
      <option value="<?php echo $i; ?>"<?php echo $number_images == $i?' selected':''; ?>><?php echo $i; ?></option>
    <?php
    }
    ?>
  </select> 
</p>

<p>
  <label for="<?php echo $this->get_field_id('image_results'); ?>"><?php _e('Image Results from Plugin Search', Shutterstock_AP::ld); ?></label>
  <select name="<?php echo $this->get_field_name('image_results'); ?>" id="<?php echo $this->get_field_id('image_results'); ?>">
    <option value="0"<?php echo ($image_results == 0?' selected':''); ?>><?php _e('Show only Images', Shutterstock_AP::ld); ?></option>
    <option value="1"<?php echo ($image_results == 1?' selected':''); ?>><?php _e('Show Posts and Images', Shutterstock_AP::ld); ?></option>
  </select>
</p>

<p>
  <input type="checkbox"<?php echo $show_logo?' checked':''; ?> name="<?php echo $this->get_field_name('show_logo'); ?>" id="<?php echo $this->get_field_id('show_logo'); ?>" />
  <label for="<?php echo $this->get_field_id('show_logo'); ?>"><?php _e('Show Shutterstock logo', Shutterstock_AP::ld); ?></label><br />
                
  <input type="checkbox"<?php echo $show_tooltip?' checked':''; ?> name="<?php echo $this->get_field_name('show_tooltip'); ?>" id="<?php echo $this->get_field_id('show_tooltip'); ?>" />
  <label for="<?php echo $this->get_field_id('show_tooltip'); ?>"><?php _e('Show preview on image hover', Shutterstock_AP::ld); ?></label><br />
                                
  <input type="checkbox"<?php echo $show_search?' checked':''; ?> name="<?php echo $this->get_field_name('show_search'); ?>" id="<?php echo $this->get_field_id('show_search'); ?>" />
  <label for="<?php echo $this->get_field_id('show_search'); ?>"><?php _e('Show search field', Shutterstock_AP::ld); ?></label><br />

  <input type="checkbox"<?php echo $show_header?' checked':''; ?> name="<?php echo $this->get_field_name('show_header'); ?>" id="<?php echo $this->get_field_id('show_header'); ?>" />
  <label for="<?php echo $this->get_field_id('show_header'); ?>"><?php _e('Show header', Shutterstock_AP::ld); ?></label><br />

  <input type="checkbox"<?php echo $show_paging?' checked':''; ?> name="<?php echo $this->get_field_name('show_paging'); ?>" id="<?php echo $this->get_field_id('show_paging'); ?>" />
  <label for="<?php echo $this->get_field_id('show_paging'); ?>"><?php _e('Show paging', Shutterstock_AP::ld); ?></label><br />

  <input type="checkbox"<?php echo $show_more?' checked':''; ?> name="<?php echo $this->get_field_name('show_more'); ?>" id="<?php echo $this->get_field_id('show_more'); ?>" />
  <label for="<?php echo $this->get_field_id('show_more'); ?>"><?php _e('Show link more results', Shutterstock_AP::ld); ?></label><br />
</p>