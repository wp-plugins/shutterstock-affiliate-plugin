var Shutterstock_AP = function(theme_colors, loader_colors)
{
  var self = this;
  this.theme_colors = theme_colors;
  this.loader_colors = loader_colors;
  this.input_api_username = jQuery('#sap_api_username');
  this.input_api_key = jQuery('#sap_api_key');
  this.input_affiliate_id = jQuery('#sap_affiliate_id');
  this.input_subid1 = jQuery('#sap_subid1');
  this.input_subid2 = jQuery('#sap_subid2');
  this.input_subid3 = jQuery('#sap_subid3');
  this.button_save_api = jQuery('#sap_save_api');
  this.save_loader_api = jQuery('#sap_save_loader_api');
  this.button_save_settings = jQuery('#sap_save_settings');
  this.save_loader_settings = jQuery('#sap_save_loader_settings');
  
  this.select_nothing_found = jQuery('#sap_nothing_found');
  this.input_nothing_found_option_k = jQuery('#sap_nothing_found_option_k');
  
  this.select_theme_color = jQuery('#sap_theme_color');
  this.input_theme_color_sample = jQuery('#sap_theme_color_sample');
  this.input_theme_color_custom = jQuery('#sap_theme_color_custom');
  
  this.select_loader_color = jQuery('#sap_loader_color');
  this.image_loader_color = jQuery('#sap_loader_color_img');
  this.editor = null;
  
  this.init = function()
  {
    tinyMCE.onAddEditor.add(function(mgr, ed)
    {
      self.editor = ed;
      
      ed.onKeyUp.add(function(ed, e) {
        self.button_save_settings.attr('disabled', false);        
      });

      ed.onChange.add(function(ed, e) {
        self.button_save_settings.attr('disabled', false);        
      });
    });
  }
  
  // update statuses of boxes
  this.update_boxes = function()
  {
    var bs = new Object();
    jQuery('#poststuff').find('.postbox').each(function()
    {
      bs[jQuery(this).attr('id')] = jQuery(this).hasClass('sap_closed')?1:0;      
    });    
    
    jQuery.post(Shutterstock_AP_data.action_admin,
      {
        a: 'update_boxes',
        boxes: bs
      }, function(r) { });  
  }
  
  // show/hide functionality for metaboxes
  jQuery('.handlediv').bind('click', function()
  {
    var box = jQuery(this).parent();
    if (box.hasClass('sap_closed'))
    {
      box.find('.inside').hide();      
      box.removeClass('sap_closed');    
      box.find('.inside').fadeIn(400);
      self.update_boxes();      
    }
    else
    {
      box.find('.inside').fadeOut(400, function()
      {
        box.addClass('sap_closed');
        self.update_boxes();      
      });
    }    
  });
  
  // save&verify key
  self.button_save_api.bind('click', function()
  {
    var button = jQuery(this); 
    button.attr('disabled', true);
    self.save_loader_api.show();    
    jQuery.post(Shutterstock_AP_data.action_admin,
      {
        a: 'save_api', 
        affiliate_id: self.input_affiliate_id.val(),
        api_username: self.input_api_username.val(),
        api_key: self.input_api_key.val(),
        subid1: self.input_subid1.val(),
        subid2: self.input_subid2.val(),
        subid3: self.input_subid3.val()
      }, function(r)
    {      
      if (r.status == 1)
      {
        self.input_api_username.removeClass('sap_input_red').addClass('sap_input_green');
        self.input_api_key.removeClass('sap_input_red').addClass('sap_input_green');
      }
      else
      {
        self.input_api_username.removeClass('sap_input_green').addClass('sap_input_red');      
        self.input_api_key.removeClass('sap_input_green').addClass('sap_input_red');      
      }                

      self.save_loader_api.hide();
    });
  });
    
  self.input_api_username.bind('change keyup', function()
  {
    self.input_api_username.removeClass('sap_input_green').removeClass('sap_input_red');      
  });

  self.input_api_key.bind('change keyup', function()
  {
    self.input_api_key.removeClass('sap_input_green').removeClass('sap_input_red');      
  });
    
  // save settings
  self.button_save_settings.bind('click', function()
  {
    var button = jQuery(this); 
    button.attr('disabled', true);
    self.save_loader_settings.show();
    
    if (self.editor != null)
      self.editor.save();
      
    jQuery.post(Shutterstock_AP_data.action_admin,
      {
        a: 'save_settings',
        language: jQuery('#sap_language').val(),
        show_images: jQuery('#sap_show_images').val(),
        images_div: jQuery('#sap_images_div').val(),
        creator_id: jQuery('#sap_creator_id').val(),
        sort_order: jQuery('#sap_sort_order').val(),
        images_type: jQuery('#sap_images_type').val(),
        number_images: jQuery('#sap_number_images').val(),
        thumbnail_size: jQuery('#sap_thumbnail_size').val(),
        show_extra_search: jQuery('#sap_show_extra_search').attr('checked')?1:0,
        show_boxes: jQuery('#sap_show_boxes').attr('checked')?1:0,
        show_header: jQuery('#sap_show_header').attr('checked')?1:0,
        auto_scroll: jQuery('#sap_auto_scroll').attr('checked')?1:0,
        swap_keycustom: jQuery('#sap_swap_keycustom').attr('checked')?1:0,
        show_keywords: jQuery('#sap_show_keywords').attr('checked')?1:0,
        show_customcontent: jQuery('#sap_show_customcontent').attr('checked')?1:0,
        nothing_found: jQuery('#sap_nothing_found').val(),
        nothing_found_keywords: jQuery('#sap_nothing_found_keywords').val(),
        open_links: jQuery('#sap_open_links').val(),
        theme_color: jQuery('#sap_theme_color').val(),
        theme_color_custom: jQuery('#sap_theme_color_custom').val(),
        loader_color: jQuery('#sap_loader_color').val(),                 
        image_detail: jQuery('#sap_image_detail').val(),                                 
        loader_text: jQuery('#sap_loader_text').val(),
        loader_text_show: jQuery('#sap_loader_text_show').val(),
        custom_content: jQuery('#sapcustomcontent').val(),
        show_logo: jQuery('#sap_show_logo').attr('checked')?1:0,
        show_tooltip: jQuery('#sap_show_tooltip').attr('checked')?1:0,
        image_results: jQuery('#sap_image_results').val(),
        cache_time: jQuery('#sap_cache_time').val()
      }, function(r)
    {
      self.save_loader_settings.hide();
    });  
  });
  
  
  // search result if nothing found
  self.select_nothing_found.bind('change', function()
  {
    if (jQuery(this).val() == 1)
    {
      self.input_nothing_found_option_k.fadeIn(400);
    }
    else
    {
      self.input_nothing_found_option_k.fadeOut(400);    
    }
  });
  
  // theme color
  self.select_theme_color.bind('change', function()
  {
    var color = jQuery(this).val();
    if (color < 9)
    {
      self.input_theme_color_sample.show();
      self.input_theme_color_custom.hide();
      self.input_theme_color_sample.css('background-color', self.theme_colors[color]).val(self.theme_colors[color]);
    }
    else
    {
      self.input_theme_color_sample.hide();
      self.input_theme_color_custom.show();
    }
  });
  
  self.input_theme_color_custom.bind('change keyup', function()
  {
    jQuery(this).css('background-color', jQuery(this).val());
  });
  
  self.input_theme_color_custom.miniColors(
    {
      change: function(hex, rgb)
      {
        jQuery(this).css('background-color', jQuery(this).val());        
        self.button_save_settings.attr('disabled', false);
      }
    });


  // loader color
  self.select_loader_color.bind('change', function()
  {
    self.image_loader_color.attr('src', Shutterstock_AP_data.plugin_url + '/frontend/loaders/' + self.loader_colors[jQuery(this).val()]);
  });
  
  
  jQuery('#sap_box_settings select').bind('change', function()
  {
    self.button_save_settings.attr('disabled', false);
  });

  jQuery('#sap_box_settings input').bind('change keyup', function()
  {
    self.button_save_settings.attr('disabled', false);
  });

  jQuery('#sap_box_search_settings select').bind('change', function()
  {
    self.button_save_settings.attr('disabled', false);
  });

  jQuery('#sap_box_search_settings input').bind('change keyup', function()
  {
    self.button_save_settings.attr('disabled', false);
  });
  
  jQuery('#sap_box_shutterstock_api input').bind('change keyup', function()
  {
    self.button_save_api.attr('disabled', false);
  });
  
  jQuery('#sapcustomcontent').live('change keyup', function()
  {
    self.button_save_settings.attr('disabled', false);
  });

  this.init();
}