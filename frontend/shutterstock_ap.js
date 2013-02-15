var Shutterstock_AP = function(options)
{
  var self = this;
  
  options = jQuery.extend(true,
        {
          'element_id': 'content',
          'show_method': 1,
          'order': 0,
          'loader_html': '',
          'search_text': 'test',
          'number_images': 15,
          'creator_id': 0,
          'show_extra_search': 1,
          'show_header': 0,
          'auto_scroll': 1,
          'show_paging': 1,
          'show_more': 0,
          'is_widget': false,
          'only_img': false,
          'images_type': 0,
          'thumb_size': 1,
          'image_detail': 0,
          'swap_keycustom': 1,
          'show_keywords': 0,
          'show_customcontent': 0,
          'show_logo': 1,
          'show_tooltip': 0,
          'image_results': 0
        }, options);

  this.options = options;
  
  this.init = function()
  {    
    self.element = jQuery('#' + self.options.element_id).length > 0?jQuery('#' + self.options.element_id):jQuery('#content');

    self.container = jQuery('<div></div>').attr('id', self.options.element_id + '_shutterstock_container');
    self.loader_el = jQuery('<div></div>').attr('id', self.options.element_id + '_shutterstock_loader').html(self.options.loader_html);
    self.content_el = jQuery('<div></div>').attr('id', self.options.element_id + '_shutterstock_content');
    self.content_detail_el = jQuery('<div></div>').attr('id', self.options.element_id + '_shutterstock_detail').hide();

    self.container.append(self.loader_el).append(self.content_el).append(self.content_detail_el);
  
    if (self.options.show_method == 0 || self.options.only_img) // replace
      self.element.html(self.container);
    else
    if (self.options.show_method == 1) // above
      self.element.prepend(self.container);
    else
      self.element.append(self.container);    
    
    self.search(self.options.search_text, self.options.order, 1);
  }
  
  this.loader = function(status)
  {
    if (status == true)
      self.loader_el.show();
    else
      self.loader_el.hide();    
  }

  this.search = function(text, order, page)
  {    
    self.loader(true);
    jQuery.post(Shutterstock_AP_data.action, 
      {
        'search': text,
        'order': order,
        'page': page,
        'number_images': self.options.number_images,
        'is_widget': self.options.is_widget?1:0,
        'element_id': self.options.element_id,
        'creator_id': self.options.creator_id,
        'show_extra_search': self.options.show_extra_search?1:0,
        'show_header': self.options.show_header?1:0,
        'show_paging': self.options.show_paging?1:0,
        'show_more': self.options.show_more?1:0,
        'only_img': self.options.only_img?1:0,
        'image_detail': self.options.image_detail,
        'images_type': self.options.images_type,
        'thumb_size': self.options.thumb_size,
        'show_logo': self.options.show_logo?1:0,
        'show_tooltip': self.options.show_tooltip?1:0,
        'image_results': self.options.image_results?1:0
      },
    function(r)
    {
      jQuery('#'+self.element_id+'_shutterstock_tooltips').remove();
      self.loader(false);
            
      if (jQuery.trim(r) == '')
      {      
      }
      else
      {
        self.content_el.html(r);
        self.rebind();
        
        if (self.options.show_tooltip)
          stickytooltip.init("data-tooltip-"+self.options.element_id, self.options.element_id+"_shutterstock_tooltips");                        
            
        // scroll top
        if ((self.options.auto_scroll == 1) && (!self.options.is_widget))
        {
          var top_position = self.content_el.offset().top;
          jQuery('html,body').animate({scrollTop: top_position}, 1000);
        }
      }      
    });
  }
  
  this.detail = function(id, link)
  {
    if ((self.options.image_detail == 2) || (self.options.is_widget && self.options.image_detail != 0))
    {  
      jQuery.colorbox({
        href: Shutterstock_AP_data.action,
        transition: 'fade',
        fixed: true,
        data:
        { 
          'detail': id, 
          'aff_link': link, 
          'is_widget': self.options.is_widget,
          'image_detail': self.options.image_detail,
          'swap_keycustom': self.options.swap_keycustom,
          'show_keywords': self.options.show_keywords,
          'show_customcontent': self.options.show_customcontent
        },
        xhrError: Shutterstock_AP_data.text.ajax_error,
        width: 800,
        height: '90%'
      });
    }
    else
    if (self.options.image_detail == 1)
    {
      self.loader(true);
      jQuery.post(Shutterstock_AP_data.action,
      {
        'detail': id, 
        'aff_link': link,
        'image_detail': self.options.image_detail,
        'swap_keycustom': self.options.swap_keycustom,
        'show_keywords': self.options.show_keywords,
        'show_customcontent': self.options.show_customcontent
      }, function(r)
      {
        self.loader(false);
        self.content_el.hide();
        self.content_detail_el.html(r).show();      
        self.rebind();      

        // scroll top
        var top_position = self.content_detail_el.offset().top;
        jQuery('html,body').animate({scrollTop: top_position}, 1000);
      });
    }
  }

  this.rebind = function()
  {
    self.container.find('.shutterstock_ap_page').bind('click', function()
    {
      var page = jQuery(this).attr('href');
      self.search(self.options.search_text, self.options.order, page);  
      return false;    
    });
    
    self.container.find('.sap_sort_order').bind('change', function()
    {
      var order = jQuery(this).val();
      self.options.order = order;
      self.search(self.options.search_text, order, 1);    
    });
    
    self.container.find('.shutterstock_ap_detail').bind('click', function()
    {
      if (self.options.image_detail == 0)
        return true;
        
      self.detail(jQuery(this).attr('href'), jQuery(this).attr('rel'));
      return false;    
    });
    
    self.container.find('.shutterstock_ap_back_button').bind('click', function()
    {
      self.content_detail_el.hide();
      self.content_el.show();
    
      // scroll top
      var top_position = self.content_el.offset().top;
      jQuery('html,body').animate({scrollTop: top_position}, 1000);
    });  
  }
                   
  this.init();
}