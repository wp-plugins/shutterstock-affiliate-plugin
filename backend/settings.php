<div id="poststuff" class="metabox-holder has-right-sidebar">
  <div id="side-info-column" class="inner-sidebar">
    <div id="sap_box_help" class="postbox<?php echo (isset($boxes['sap_box_help']) && $boxes['sap_box_help']?' sap_closed':''); ?>">
      <div class="handlediv" title="<?php _e('Click to toggle', self::ld); ?>"><br></div>
      <h3 class="hndle"><span><?php _e('Help', self::ld); ?></span></h3>
      <div class="inside">
        <?php _e('<p>Do you need help to get this plugin works?</p>
          <p><strong>Please check following resources: </strong></p>
          <p>- Visit the <a href="http://idenio.com/plugins/shutterstock-affiliate-plugin/" target="_blank">Shutterstock WordPress Plugin Website<br />
          </a>- Check the <a href="http://idenio.com/plugins/shutterstock-affiliate-plugin/installation" target="_blank">detailed installation instruction<br />
          </a>- See <a href="http://idenio.com/plugins/shutterstock-affiliate-plugin/how-it-works" target="_blank">how the plugin works<br />
          </a>- Find a solution <a href="http://idenio.com/plugins/shutterstock-affiliate-plugin/faqs" target="_blank">in the FAQs<br />
          </a>- Finally <a href="http://idenio.com/plugins/shutterstock-affiliate-plugin/contact-support" target="_blank">contact the support team</a></p>', self::ld); ?>
      </div>
    </div>

  </div>

  <div id="post-body">
    <div id="post-body-content">
      <div id="sap_box_shutterstock_api" class="postbox<?php echo (isset($boxes['sap_box_shutterstock_api']) && $boxes['sap_box_shutterstock_api']?' sap_closed':''); ?>">
        <div class="handlediv" title="<?php _e('Click to toggle', self::ld); ?>"><br></div>
        <h3 class="hndle"><span><?php _e('Shutterstock API and Affiliate Settings', self::ld); ?></span></h3>
        <div class="inside">
          <table class="form-table">
            <tr>
              <th scope="row"><label for="sap_api_username"><?php _e('API username', self::ld); ?></label></th>
              <td>
                <input<?php echo ($api['is_valid']?' class="sap_input_green"':($api['api_username']?' class="sap_input_red"':'')); ?> id="sap_api_username" size="50" maxlength="255" type="text" name="sap_api_username" title="<?php _e('Enter your Shutterstock API username.', self::ld); ?>" value="<?php echo esc_attr($api['api_username']); ?>" />                                
              </td>
            </tr>            
            <tr>
              <th scope="row"><label for="sap_api_key"><?php _e('API key', self::ld); ?></label></th>
              <td>
                <input<?php echo ($api['is_valid']?' class="sap_input_green"':($api['api_key']?' class="sap_input_red"':'')); ?> id="sap_api_key" size="50" maxlength="255" type="text" name="sap_api_key" title="<?php _e('Enter your Shutterstock API key.', self::ld); ?>" value="<?php echo esc_attr($api['api_key']); ?>" />                                
              </td>
            </tr>            
            <tr>
              <th scope="row"><label for="sap_affiliate_id"><?php _e('Affiliate ID', self::ld); ?></label></th>
              <td>
                <input id="sap_affiliate_id" size="10" maxlength="20" type="text" name="sap_affiliate_id" title="<?php _e('Enter your Affiliate ID from Shutterstock.', self::ld); ?>" value="<?php echo esc_attr($api['affiliate_id']); ?>" />
              </td>
            </tr>            
            <tr>
              <th scope="row"><label for="sap_subid1"><?php _e('SubID 1', self::ld); ?></label></th>
              <td>
                <input id="sap_subid1" size="50" type="text" name="sap_subid1" value="<?php echo esc_attr($api['subid1']); ?>" />
              </td>
            </tr>            
            <tr>
              <th scope="row"><label for="sap_subid2"><?php _e('SubID 2', self::ld); ?></label></th>
              <td>
                <input id="sap_subid2" size="50" type="text" name="sap_subid2" value="<?php echo esc_attr($api['subid2']); ?>" />
              </td>
            </tr>            
            <tr>
              <th scope="row"><label for="sap_subid3"><?php _e('SubID 3', self::ld); ?></label></th>
              <td>
                <input id="sap_subid3" size="50" type="text" name="sap_subid3" value="<?php echo esc_attr($api['subid3']); ?>" />
              </td>
            </tr>
            <tr>
              <td colspan="2">
              <i><?php printf(esc_html__('You can use marks that will be replaced: %s - domain name, %s - searched text, %s - image ID', self::ld), 
                                            '<b>%domain%</b>', '<b>%search%</b>', '<b>%id%</b>'); ?></i>
              </td>
            </tr>
          </table>
          <div class="sap_save_area">
            <span class="sap_save_loader"><img id="sap_save_loader_api" src="<?php echo $this->plugin_url; ?>/backend/images/loader.gif" width="15" height="15" /></span>
            <input class="button-primary sap_button_save" disabled id="sap_save_api" type="submit" name="save" title="<?php _e('Save', self::ld); ?>" value="<?php _e('Save', self::ld); ?>" />
          </div>
          <div class="sap_clear"></div>
        </div>
      </div>

      <div id="sap_box_settings" class="postbox<?php echo (isset($boxes['sap_box_settings']) && $boxes['sap_box_settings']?' sap_closed':''); ?>">
        <div class="handlediv" title="<?php _e('Click to toggle', self::ld); ?>"><br></div>
        <h3 class="hndle"><span><?php _e('Implementation Settings', self::ld); ?></span></h3>
        <div class="inside">
          <table class="form-table">
            <tr>
              <th scope="row"><label for="sap_language"><?php _e('Language', self::ld); ?></label></th>
              <td>
                <select name="sap_language" id="sap_language" title="<?php esc_attr_e('Language of the search result.', self::ld); ?>">
                <?php
                reset($this->languages);
                while(list($lang_id, $lang) = @each($this->languages))
                {
                ?>
                  <option value="<?php echo $lang_id; ?>"<?php echo ($settings['language'] == $lang_id?' selected':''); ?>><?php echo $lang['name']; ?></option>                
                <?php
                }
                ?>
                </select>
              </td>
            </tr>

            <tr>
              <th scope="row"><label for="sap_show_images"><?php _e('Show Images', self::ld); ?></label></th>
              <td>
                <select name="sap_show_images" id="sap_show_images" title="<?php esc_attr_e('This defines where in the search results page the Shutterstock images are "injected".', self::ld); ?>">
                  <option value="0"<?php echo ($settings['show_images'] == 0?' selected':''); ?>><?php _e('Replace', self::ld); ?></option>
                  <option value="1"<?php echo ($settings['show_images'] == 1?' selected':''); ?>><?php _e('Above', self::ld); ?></option>
                  <option value="2"<?php echo ($settings['show_images'] == 2?' selected':''); ?>><?php _e('Below', self::ld); ?></option>
                </select>
                <?php _e('the search results', self::ld); ?>                
              </td>
            </tr>

            <tr>
              <th scope="row"><label for="sap_images_div"><?php _e('Show images in "div" with ID', self::ld); ?></label></th>
              <td>
                <input id="sap_images_div" size="15" maxlength="255" type="text" name="sap_images_div" value="<?php echo esc_attr($settings['images_div']); ?>" />
              </td>
            </tr>
          </table>
          <div class="sap_clear"></div>
        </div>
      </div>
            
      <div id="sap_box_search_settings" class="postbox<?php echo (isset($boxes['sap_box_search_settings']) && $boxes['sap_box_search_settings']?' sap_closed':''); ?>">
        <div class="handlediv" title="<?php _e('Click to toggle', self::ld); ?>"><br></div>
        <h3 class="hndle"><span><?php _e('Search Result Settings', self::ld); ?></span></h3>
        <div class="inside">
          <table class="form-table">            
            <tr>
              <th scope="row"><label for="sap_creator_id"><?php _e('Show only images from creator ID', self::ld); ?></label></th>
              <td>
                <input id="sap_creator_id" size="10" maxlength="255" type="text" name="sap_creator_id" title="<?php esc_attr_e("Simply leave blank if you don't want restrict search result by a creator ID.", self::ld); ?>" value="<?php echo esc_attr($settings['creator_id']); ?>" />
              </td>
            </tr>

            <tr>
              <th scope="row"><label for="sap_cache_time"><?php _e('Cache Time', self::ld); ?></label></th>
              <td>
                <select name="sap_cache_time" id="sap_cache_time">
                <?php
                reset($this->cache_times);
                while(list($id, $cache) = @each($this->cache_times))
                {
                ?>
                  <option value="<?php echo $id; ?>"<?php echo ($settings['cache_time'] == $id?' selected':''); ?>><?php echo $cache['name']; ?></option>
                <?php
                }
                ?>
                </select>
                <br />
                <i><?php _e("ATTENTION: Enabling the cache will store additional data in your database. It's recommended to use memcache for better performance.", self::ld); ?></i>
              </td>
            </tr>

            <tr>
              <th scope="row"><label for="sap_sort_order"><?php _e('Select your default sort order', self::ld); ?></label></th>
              <td>
                <select name="sap_sort_order" id="sap_sort_order" title="<?php esc_attr_e('Select your default sort order. The user can change this while browsing the results.', self::ld); ?>">
                  <option value="0"<?php echo ($settings['sort_order'] == 0?' selected':''); ?>><?php _e('Popular', self::ld); ?></option>
                  <option value="1"<?php echo ($settings['sort_order'] == 1?' selected':''); ?>><?php _e('New', self::ld); ?></option>
                  <option value="2"<?php echo ($settings['sort_order'] == 2?' selected':''); ?>><?php _e('Random', self::ld); ?></option>
                  <option value="3"<?php echo ($settings['sort_order'] == 3?' selected':''); ?>><?php _e('Relevant', self::ld); ?></option>
                </select>
              </td>
            </tr>

            <tr>
              <th scope="row"><label for="sap_images_type"><?php _e('Show following image types', self::ld); ?></label></th>
              <td>
                <select name="sap_images_type" id="sap_images_type">
                  <option value="0"<?php echo (isset($settings['images_type']) && $settings['images_type'] == 0?' selected':''); ?>><?php esc_html_e('All', self::ld); ?></option>
                  <option value="1"<?php echo (isset($settings['images_type']) && $settings['images_type'] == 1?' selected':''); ?>><?php esc_html_e('Photos', self::ld); ?></option>
                  <option value="2"<?php echo (isset($settings['images_type']) && $settings['images_type'] == 2?' selected':''); ?>><?php esc_html_e('Illustrations', self::ld); ?></option>
                  <option value="3"<?php echo (isset($settings['images_type']) && $settings['images_type'] == 3?' selected':''); ?>><?php esc_html_e('Vectors', self::ld); ?></option>
                </select>
              </td>
            </tr>

            <tr>
              <th scope="row"><label for="sap_number_images"><?php _e('Number of images per page', self::ld); ?></label></th>
              <td>
                <select name="sap_number_images" id="sap_number_images" title="<?php esc_attr_e('Select the maximum numbers of images per page.', self::ld); ?>">
                  <option value="3"<?php echo ($settings['number_images'] == 3?' selected':''); ?>><?php _e('3', self::ld); ?></option>
                  <option value="5"<?php echo ($settings['number_images'] == 5?' selected':''); ?>><?php _e('5', self::ld); ?></option>
                  <option value="6"<?php echo ($settings['number_images'] == 6?' selected':''); ?>><?php _e('6', self::ld); ?></option>
                  <option value="10"<?php echo ($settings['number_images'] == 10?' selected':''); ?>><?php _e('10', self::ld); ?></option>
                  <option value="15"<?php echo ($settings['number_images'] == 15?' selected':''); ?>><?php _e('15', self::ld); ?></option>
                  <option value="25"<?php echo ($settings['number_images'] == 25?' selected':''); ?>><?php _e('25', self::ld); ?></option>
                  <option value="50"<?php echo ($settings['number_images'] == 50?' selected':''); ?>><?php _e('50', self::ld); ?></option>
                  <option value="64"<?php echo ($settings['number_images'] == 75?' selected':''); ?>><?php _e('75', self::ld); ?></option>
                </select>
              </td>
            </tr>

            <tr>
              <th scope="row"><label for="sap_thumbnail_size"><?php _e('Thumbnails size', self::ld); ?></label></th>
              <td>
                <select name="sap_thumbnail_size" id="sap_thumbnail_size" title="<?php esc_attr_e('Select the size of the thumbnail you want to show.', self::ld); ?>">
                  <option value="0"<?php echo ($settings['thumbnail_size'] == 0?' selected':''); ?>><?php _e('Small', self::ld); ?></option>
                  <option value="1"<?php echo ($settings['thumbnail_size'] == 1?' selected':''); ?>><?php _e('Large', self::ld); ?></option>
                </select>
              </td>
            </tr>

            <tr>
              <th scope="row"><label for="sap_image_detail"><?php _e('Show image detail', self::ld); ?></label></th>
              <td>
                <select name="sap_image_detail" id="sap_image_detail" title="<?php echo esc_attr_e('Select where it can show detailed content about image.', self::ld); ?>">
                  <option value="0"<?php echo ($settings['image_detail'] == 0?' selected':''); ?>><?php _e('at shutterstock.com', self::ld); ?></option>
                  <option value="1"<?php echo ($settings['image_detail'] == 1?' selected':''); ?>><?php _e('on page', self::ld); ?></option>
                  <option value="2"<?php echo ($settings['image_detail'] == 2?' selected':''); ?>><?php _e('via popup window', self::ld); ?></option>
                </select>
              </td>
            </tr>

            <tr>
              <th scope="row"><label for="sap_image_results"><?php _e('Image Results from Plugin Search', self::ld); ?></label></th>
              <td>
                <select name="sap_image_results" id="sap_image_results">
                  <option value="0"<?php echo ($settings['image_results'] == 0?' selected':''); ?>><?php _e('Show only Images', self::ld); ?></option>
                  <option value="1"<?php echo ($settings['image_results'] == 1?' selected':''); ?>><?php _e('Show Posts and Images', self::ld); ?></option>
                </select>
              </td>
            </tr>

            <tr>
              <th scope="row"><label for="sap_show_extra_search1"><?php _e('Search result options', self::ld); ?></label></th>
              <td>
                <input type="checkbox" name="sap_show_logo" id="sap_show_logo" value="1"<?php echo ($settings['show_logo']?' checked':''); ?>/> <label for="sap_show_logo"><?php _e('Show Shutterstock logo on image results page', self::ld); ?></label><br /> 
                <input type="checkbox" name="sap_show_tooltip" id="sap_show_tooltip" value="1"<?php echo ($settings['show_tooltip']?' checked':''); ?>/> <label for="sap_show_tooltip"><?php _e('Show large preview when mouseover image', self::ld); ?></label><br />
                <input type="checkbox" name="sap_show_extra_search" id="sap_show_extra_search" value="1"<?php echo ($settings['show_extra_search']?' checked':''); ?>/> <label for="sap_show_extra_search"><?php _e('Show extra search field on image results page', self::ld); ?></label><br /> 
                <input type="checkbox" name="sap_show_boxes" id="sap_show_boxes" value="1"<?php echo ($settings['show_boxes']?' checked':''); ?>/> <label for="sap_show_boxes"><?php _e('Show boxes around the images the search results', self::ld); ?></label><br /> 
                <input type="checkbox" name="sap_show_header" id="sap_show_header" value="1"<?php echo ($settings['show_header']?' checked':''); ?>/> <label for="sap_show_header"><?php _e('Show header at the search results', self::ld); ?></label><br /> 
                <input type="checkbox" name="sap_auto_scroll" id="sap_auto_scroll" value="1"<?php echo ($settings['auto_scroll']?' checked':''); ?>/> <label for="sap_auto_scroll"><?php _e('Should it scroll down to results automatically?', self::ld); ?></label><br /> 
                <input type="checkbox" name="sap_swap_keycustom" id="sap_swap_keycustom" value="1"<?php echo ($settings['swap_keycustom']?' checked':''); ?>/> <label for="sap_swap_keycustom"><?php _e('Swap keywords and custom content - show keywords below the custom content', self::ld); ?></label><br /> 
                <input type="checkbox" name="sap_show_keywords" id="sap_show_keywords" value="1"<?php echo ($settings['show_keywords']?' checked':''); ?>/> <label for="sap_show_keywords"><?php _e('Show keywords below the image detail', self::ld); ?></label><br /> 
                <input type="checkbox" name="sap_show_customcontent" id="sap_show_customcontent" value="1"<?php echo ($settings['show_customcontent']?' checked':''); ?>/> <label for="sap_show_customcontent"><?php _e('Show custom content below the image detail', self::ld); ?></label><br /> 
              </td>
            </tr>

            <tr>
              <th scope="row"><label for="sap_nothing_found"><?php _e('If nothing is found', self::ld); ?></label></th>
              <td>
                <select name="sap_nothing_found" id="sap_nothing_found" title="<?php esc_attr_e('Select what to show if nothing is found.', self::ld); ?>">
                  <option value="0"<?php echo ($settings['nothing_found'] == 0?' selected':''); ?>><?php _e('Show nothing', self::ld); ?></option>
                  <option value="1"<?php echo ($settings['nothing_found'] == 1?' selected':''); ?>><?php _e('Show images', self::ld); ?></option>
                </select>
                <span id="sap_nothing_found_option_k"<?php echo (($settings['nothing_found'] == 1)?'':' style="display: none;"'); ?>><?php _e('Keyword(s)', self::ld); ?> <input id="sap_nothing_found_keywords" size="20" maxlength="255" type="text" name="sap_nothing_found_keywords" title="<?php esc_attr_e('You can write comma separated keywords.', self::ld); ?>" value="<?php echo esc_attr($settings['nothing_found_keywords']); ?>" /></span>                
              </td>
            </tr>

            <tr>
              <th scope="row"><label for="sap_open_links"><?php _e('Open Links to Shutterstock', self::ld); ?></label></th>
              <td>
                <select name="sap_open_links" id="sap_open_links" title="<?php esc_attr_e('Select where the links to Shutterstock should open.', self::ld); ?>">
                  <option value="0"<?php echo ($settings['open_links'] == 0?' selected':''); ?>><?php _e('in same window', self::ld); ?></option>
                  <option value="1"<?php echo ($settings['open_links'] == 1?' selected':''); ?>><?php _e('in a new window', self::ld); ?></option>
                </select>
              </td>
            </tr>

            <tr>
              <th scope="row"><label for="sap_theme_color"><?php _e('Theme color', self::ld); ?></label></th>
              <td>
                <select name="sap_theme_color" id="sap_theme_color" title="<?php esc_attr_e('Select a color for the buttons at your search result.', self::ld); ?>">
                  <?php
                  reset($this->theme_colors);
                  $c = 0;
                  while(list(, $color) = @each($this->theme_colors))
                  {
                  ?>                
                    <option value="<?php echo $c; ?>"<?php echo ($settings['theme_color'] == $c?' selected':''); ?>><?php echo $color[0]; ?></option>
                  <?php
                    $c++;
                  }
                  ?>
                </select>
                <input type="text" style="background-color: <?php echo $this->theme_colors[$settings['theme_color']][1]; ?>;<?php echo ($settings['theme_color'] > 8?' display: none;':''); ?>" id="sap_theme_color_sample" name="sap_theme_color_sample" size="5" maxlength="7" value="<?php echo $this->theme_colors[$settings['theme_color']][1]; ?>" readonly />
                <input type="text" style="background-color: <?php echo esc_attr($settings['theme_color_custom']); ?>;<?php echo ($settings['theme_color'] < 9?' display: none;':''); ?>" id="sap_theme_color_custom" name="sap_theme_color_custom" size="5" maxlength="7" value="<?php echo esc_attr($settings['theme_color_custom']); ?>" />
              </td>
            </tr>

            <tr>
              <th scope="row"><label for="sap_loader_color"><?php _e('Loader color', self::ld); ?></label></th>
              <td>
                <select name="sap_loader_color" id="sap_loader_color" title="<?php esc_attr_e('Select a color for the loader.', self::ld); ?>">
                  <?php
                  reset($this->loader_colors);
                  $c = 0;
                  while(list(, $loader) = @each($this->loader_colors))
                  {
                  ?>                
                    <option value="<?php echo $c; ?>"<?php echo ($settings['loader_color'] == $c?' selected':''); ?>><?php echo $loader[0]; ?></option>
                  <?php
                    $c++;
                  }
                  ?>
                </select>
                <img id="sap_loader_color_img" src="<?php echo $this->plugin_url; ?>/frontend/loaders/<?php echo $this->loader_colors[$settings['loader_color']][1]; ?>" />
              </td>
            </tr>
            
            <tr>
              <th scope="row"><label for="sap_loader_text"><?php _e('Loader Text', self::ld); ?></label></th>
              <td>
                <input id="sap_loader_text" size="25" maxlength="255" type="text" name="sap_loader_text" title="<?php esc_attr_e('Loader Text', self::ld); ?>" value="<?php echo esc_attr($settings['loader_text']); ?>" />
                <label for="sap_loader_text_show"><?php esc_html_e('Position', self::ld); ?></label>
                <select name="sap_loader_text_show" id="sap_loader_text_show" title="<?php esc_attr_e('Position of loader text according to loader image.', self::ld); ?>">
                  <option value="0"<?php echo ($settings['loader_text_show'] == 0?' selected':''); ?>><?php _e('Above', self::ld); ?></option>
                  <option value="1"<?php echo ($settings['loader_text_show'] == 1?' selected':''); ?>><?php _e('Below', self::ld); ?></option>
                  <option value="2"<?php echo ($settings['loader_text_show'] == 2?' selected':''); ?>><?php _e('Left', self::ld); ?></option>
                  <option value="3"<?php echo ($settings['loader_text_show'] == 3?' selected':''); ?>><?php _e('Right', self::ld); ?></option>
                </select>
              </td>
            </tr>            
            
            <tr>
              <th scope="row"><label for="sap_custom_content"><?php _e('Custom content', self::ld); ?></label></th>
              <td>
                <div id="poststuff" class="sap_custom_content">
                  <div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea">
                    <?php the_editor(stripslashes($settings['custom_content']), 'sapcustomcontent', 'sapcustomcontent', false); ?>
                  </div>
                </div>
                <br class="sap_clear" />
              </td>
            </tr>
          </table>
          <div class="sap_save_area">
            <span class="sap_save_loader"><img id="sap_save_loader_settings" src="<?php echo $this->plugin_url; ?>/backend/images/loader.gif" width="15" height="15" /></span>
            <input class="button-primary sap_button_save" disabled id="sap_save_settings" type="submit" name="save" title="<?php _e('Save', self::ld); ?>" value="<?php _e('Save', self::ld); ?>" />
          </div>
          <div class="sap_clear"></div>
        </div>
      </div>
    </div>
  </div>
  <br class="clear">
</div>

</div>

<script language="JavaScript">
jQuery(document).ready(function()
{
  var theme_colors = new Array(<?php echo @implode(',', $theme_colors); ?>);
  var loader_colors = new Array(<?php echo @implode(',', $loader_colors); ?>);
  new Shutterstock_AP(theme_colors, loader_colors);
});
</script>