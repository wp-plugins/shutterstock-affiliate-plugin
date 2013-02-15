<?php
class Shutterstock_AP_Widget extends WP_Widget
{
  protected $p;

  function __construct()
  {
    global $shutterstock_ap;
    $this->p = &$shutterstock_ap; 
  
    parent::WP_Widget(__class__, __('Shutterstock Widget', Shutterstock_AP::ld), array(
                        'description' => __('Shutterstock Affiliate Plugin - Widget', Shutterstock_AP::ld)
                      ));
  }
  
  function widget($args, $instance)        
  {
    $loader_id = $this->id.'_loader';
  
    // load settings
    $api = get_option('Shutterstock_AP_api', false);
    $settings = get_option('Shutterstock_AP_settings', $this->p->default_options);
                                   
    if (($api == false)||($settings == false)) return;      
    if (!$api['is_valid']) return;
                
    global $wpdb, $post;      
    extract($args);      
    
    $title = apply_filters('widget_title', $instance['title']);
    $keywords = isset($instance['keywords'])?$instance['keywords']:'';
    $auto_keywords = isset($instance['auto_keywords'])?$instance['auto_keywords']:false; 
    $creator_id = isset($instance['creator_id'])?$instance['creator_id']:'';
    $number_images = isset($instance['number_images'])?$instance['number_images']:3;
    $show_search = isset($instance['show_search'])?$instance['show_search']:false;
    $show_header = isset($instance['show_header'])?$instance['show_header']:false;
    $show_paging = isset($instance['show_paging'])?$instance['show_paging']:false;
    $show_more = isset($instance['show_more'])?$instance['show_more']:false;
    $show_logo = isset($instance['show_logo'])?$instance['show_logo']:false;
    $show_tooltip = isset($instance['show_tooltip'])?$instance['show_tooltip']:false;
    $image_results = isset($instance['image_results'])?$instance['image_results']:0;
    
    echo $before_widget;

    if ($title)
      echo $before_title.$title.$after_title;
    
    if (!$keywords || $auto_keywords)
      $keywords = @implode(',', $this->p->analyze($post->post_title));              

    require $this->p->plugin_path.'/frontend/widget.php';
            
    echo $after_widget;
  }

  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['keywords'] = strip_tags($new_instance['keywords']);
    $instance['auto_keywords'] = $new_instance['auto_keywords']; 
    $instance['creator_id'] = strip_tags($new_instance['creator_id']); 
    $instance['number_images'] = $new_instance['number_images']; 
    $instance['show_search'] = $new_instance['show_search']; 
    $instance['show_header'] = $new_instance['show_header']; 
    $instance['show_paging'] = $new_instance['show_paging']; 
    $instance['show_more'] = $new_instance['show_more']; 
    $instance['show_logo'] = $new_instance['show_logo']; 
    $instance['show_tooltip'] = $new_instance['show_tooltip'];
    $instance['image_results'] = $new_instance['image_results'];
    return $instance;
  }
  
  function form($instance)
  {
    if ($instance)
    {
      $title = esc_attr($instance['title']);
      $keywords = $instance['keywords'];
      $auto_keywords = $instance['auto_keywords'];
      $creator_id = $instance['creator_id'];
      $number_images = $instance['number_images'];
      $show_search = $instance['show_search'];
      $show_header = $instance['show_header'];
      $show_paging = $instance['show_paging'];
      $show_more = $instance['show_more'];
      $show_logo = $instance['show_logo'];
      $show_tooltip = $instance['show_tooltip'];
      $image_results = $instance['image_results'];
    }
    else
    {
      $title = '';
      $keywords = '';
      $auto_keywords = true;
      $creator_id = '';
      $number_images = 3;
      $show_search = true;
      $show_header = false;
      $show_paging = false;
      $show_more = true;
      $show_logo = false;
      $show_tooltip = false;
      $image_results = 0;
    }
    
    require $this->p->plugin_path.'/backend/widget_form.php';      
  }    
}
