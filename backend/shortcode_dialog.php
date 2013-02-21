<div id="shutterstock_ap_shortcode_dialog">
  <table class="sap_bd_table">
    <tbody>
      <tr>
        <th><label for="sap_bd_keywords"><?php esc_html_e('Keywords', self::ld); ?></label></th>
        <td>
          <input id="sap_bd_keywords" name="sap_bd_keywords" size="40" type="text" value="" title="<?php esc_attr_e('Leave blank for auto search by words in the post.', self::ld); ?>" />
          <br />
          <input type="checkbox" onclick="document.getElementById('sap_bd_keywords').disabled = this.checked;" name="sap_bd_auto_keywords" id="sap_bd_auto_keywords" />
          <label for="sap_bd_auto_keywords"><?php esc_html_e('Search keywords in the content', self::ld); ?></label>          
        </td>
      </tr>
      
      <tr>
        <th><label for="sap_bd_creator_id"><?php esc_html_e('Result by creator ID', self::ld); ?></label></th>
        <td><input id="sap_bd_creator_id" name="sap_bd_creator_id" size="10" type="text" value="" title="<?php esc_attr_e("Simply leave blank if you don't want restrict search result by a creator ID.", self::ld); ?>" /></td>
      </tr>

      <tr>
        <th><label for="sap_bd_sort_order"><?php esc_html_e('Default sort order', self::ld); ?></label></th>
        <td>
          <select name="sap_bd_sort_order" id="sap_bd_sort_order" title="<?php esc_attr_e('Select your default sort order. The user can change this while browsing the results.', self::ld); ?>">
            <option value="0" selected><?php _e('Popular', self::ld); ?></option>
            <option value="1"><?php _e('New', self::ld); ?></option>
            <option value="2"><?php _e('Random', self::ld); ?></option>
            <option value="3"><?php _e('Relevant', self::ld); ?></option>
          </select>
        </td>
      </tr>

      <tr>
        <th valign="top"><label for="sap_bd_images_type"><?php _e('Show image types', self::ld); ?></label></th>
        <td>
          <select name="sap_bd_images_type" id="sap_bd_images_type">
            <option value="0" selected><?php esc_html_e('All', self::ld); ?></option>
            <option value="1"><?php esc_html_e('Photos', self::ld); ?></option>
            <option value="2"><?php esc_html_e('Illustrations', self::ld); ?></option>
            <option value="3"><?php esc_html_e('Vectors', self::ld); ?></option>
          </select>
        </td>
      </tr>

      <tr>
        <th><label for="sap_bd_number_images"><?php esc_html_e('Images per page', self::ld); ?></label></th>
        <td>
          <select name="sap_bd_number_images" id="sap_bd_number_images" title="<?php esc_attr_e('Select the maximum numbers of images per page.', self::ld); ?>">
            <option value="1"><?php esc_html_e('1', self::ld); ?></option>
            <option value="2"><?php esc_html_e('2', self::ld); ?></option>
            <option value="3" selected><?php esc_html_e('3', self::ld); ?></option>
            <option value="5"><?php esc_html_e('5', self::ld); ?></option>
            <option value="6"><?php esc_html_e('6', self::ld); ?></option>
            <option value="10"><?php esc_html_e('10', self::ld); ?></option>
            <option value="15"><?php esc_html_e('15', self::ld); ?></option>
            <option value="25"><?php esc_html_e('25', self::ld); ?></option>
            <option value="50"><?php esc_html_e('50', self::ld); ?></option>
            <option value="75"><?php esc_html_e('75', self::ld); ?></option>
          </select>        
        </td>
      </tr>
      
      <tr>
        <th><label for="sap_bd_thumbnail_size"><?php _e('Thumbnails size', self::ld); ?></label></th>
        <td>
          <select name="sap_bd_thumbnail_size" id="sap_bd_thumbnail_size" title="<?php esc_attr_e('Select the size of the thumbnail you want to show.', self::ld); ?>">
            <option value="0"><?php _e('Small', self::ld); ?></option>
            <option value="1" selected><?php _e('Large', self::ld); ?></option>
          </select>
        </td>
      </tr>

      <tr>
        <th scope="row"><label for="sap_bd_image_detail"><?php _e('Show image detail', self::ld); ?></label></th>
        <td>
          <select name="sap_bd_image_detail" id="sap_bd_image_detail" title="<?php echo esc_attr_e('Select where it can show detailed content about image.', self::ld); ?>">
            <option value="0" selected><?php _e('at shutterstock.com', self::ld); ?></option>
            <option value="1"><?php _e('on page', self::ld); ?></option>
            <option value="2"><?php _e('via popup window', self::ld); ?></option>
          </select>
        </td>
      </tr>
      
      <tr>
        <th scope="row"><label for="sap_bd_image_results"><?php _e('Image Results from Plugin Search', self::ld); ?></label></th>
        <td>
          <select name="sap_bd_image_results" id="sap_bd_image_results">
            <option value="0"><?php _e('Show only Images', self::ld); ?></option>
            <option value="1"><?php _e('Show Posts and Images', self::ld); ?></option>
          </select>
        </td>
      </tr>
      
      <tr>
        <th valign="top"><label for="sap_bd_search"><?php esc_html_e('Search result options', self::ld); ?></label></th>
        <td>
          <input type="checkbox" name="sap_bd_show_logo" id="sap_bd_show_logo" value="1" /> <label for="sap_bd_show_logo"><?php _e('Show Shutterstock logo on image results page', self::ld); ?></label><br /> 
          <input type="checkbox" name="sap_bd_show_tooltip" id="sap_bd_show_tooltip" value="1" /> <label for="sap_bd_show_tooltip"><?php _e('Show large preview when mouseover image', self::ld); ?></label><br />
          <input type="checkbox" name="sap_bd_show_extra_search" id="sap_bd_show_extra_search" value="1" checked /> <label for="sap_bd_show_extra_search"><?php esc_html_e('Show extra search field on image results page', self::ld); ?></label><br /> 
          <input type="checkbox" name="sap_bd_show_header" id="sap_bd_show_header" value="1" /> <label for="sap_bd_show_header"><?php esc_html_e('Show header at the search results', self::ld); ?></label><br /> 
          <input type="checkbox" name="sap_bd_auto_scroll" id="sap_bd_auto_scroll" value="1" /> <label for="sap_bd_auto_scroll"><?php esc_html_e('Should it scroll down to results automatically?', self::ld); ?></label><br />           
          <input type="checkbox" name="sap_bd_show_paging" id="sap_bd_show_paging" value="1" /> <label for="sap_bd_show_paging"><?php esc_html_e('Show pagination', self::ld); ?></label><br />           
          <input type="checkbox" name="sap_bd_swap_keycustom" id="sap_bd_swap_keycustom" value="1" /> <label for="sap_bd_swap_keycustom"><?php _e('Swap keywords and custom content - show keywords below the custom content', self::ld); ?></label><br /> 
          <input type="checkbox" name="sap_bd_show_keywords" id="sap_bd_show_keywords" value="1" /> <label for="sap_bd_show_keywords"><?php _e('Show keywords below the image detail', self::ld); ?></label><br /> 
          <input type="checkbox" name="sap_bd_show_customcontent" id="sap_bd_show_customcontent" value="1" /> <label for="sap_bd_show_customcontent"><?php _e('Show custom content below the image detail', self::ld); ?></label><br /> 
          <input type="checkbox" name="sap_bd_show_more" id="sap_bd_show_more" value="1" checked /> <label for="sap_bd_show_more"><?php esc_html_e('Show link with more results', self::ld); ?></label><br />           
        </td>
      </tr>      
    </tbody>
  </table>

  <div class="sap_bd_buttonbox">
    <a class="button sap_bd_cancel" href="#" id="sap_bd_cancel" onclick="return false;"><?php esc_html_e('Cancel', self::ld); ?></a>
		<input type="submit" value="<?php esc_attr_e('Insert', self::ld); ?>" class="button-primary" id="sap_bd_insert" name="sap_bd_insert">
	</div>      
</div>