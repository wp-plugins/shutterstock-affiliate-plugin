<?php
/*
Plugin Name: Shutterstock Affiliate Plugin
Plugin URI: http://idenio.com/plugins/shutterstock-affiliate-plugin/
Description: The Shutterstock Affiliate Plugin is designed to help affiliates earn money by introducing new customers to Shutterstock. Use this plugin to easily show search results from Shutterstock Images on a WordPress search results page, or on any post or page within your Wordpress site. When customers follow links from those images and make a purchase at Shutterstock, you’ll earn highly competitive, 20% commissions—up to $300 on qualifying purchases. You must be a member of the <a href="http://affiliate.shutterstock.com/" title="Shutterstock Affiliate Program" target="_blank">Shutterstock Affiliate program</a> and have a Shutterstock API key to use this plugin.
Author: Idenio GmbH for Shutterstock, Inc.
Version: 2.0.4
Author URI: http://idenio.com/plugins/shutterstock-affiliate-plugin/
Text Domain: shutterstock_ap
*/

require_once dirname(__FILE__).'/shutterstock_ap_api.php';  
require_once dirname(__FILE__).'/shutterstock_ap_widget.php';  

class Shutterstock_AP
{
  // localization domain
  const ld = "shutterstock_ap";
  
  // version of the plugin
  const version = '2.0.4';
  
  public $plugin_url;
  public $plugin_path;
  
  public $theme_colors;
  public $loader_colors;
   
  // sort order
  public $sort_order = array('popular', 'newest', 'oldest', 'random', 'relevance');
  
  // thumbnail sizes
  public $thumbnail_sizes = array(
              array('100', '75'),
              array('150', '112'),
              array('400', '300')
            );
  
  // languages
  public $languages = array();
  public $iso_to_lang = array();
  
  // default options
  public $default_options = array();

  // counters
  private $shortcode_counter = 0;
  private $action_counter = 0;

  function __construct()
  {              
    // paths
    $this->plugin_url = WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__));
    $this->plugin_path = WP_PLUGIN_DIR.'/'.dirname(plugin_basename(__FILE__));
    
    add_action('plugins_loaded', array(&$this, 'plugins_loaded'));
    
    // define default options
    $this->default_options = array(
                  'language' => 0,
                  'show_images' => 1,
                  'images_div' => 'content',
                  'creator_id' => '',
                  'sort_order' => 0,
                  'images_type' => 0,
                  'number_images' => 15,
                  'thumbnail_size' => 1,
                  'show_extra_search' => 1,
                  'show_boxes' => 0,
                  'show_header' => 0,
                  'swap_keycustom' => 0,
                  'show_keywords' => 0,
                  'show_customcontent' => 0,
                  'auto_scroll' => 0,
                  'nothing_found' => 0,
                  'nothing_found_keywords' => '',
                  'open_links' => 1,
                  'theme_color' => 0,
                  'theme_color_custom' => '#609030',
                  'loader_color' => 4,
                  'image_detail' => 0,
                  'loader_text' => '',
                  'loader_text_show' => 0,
                  'custom_content' => '',
                  'show_logo' => 1,
                  'show_tooltip' => 0,
                  'image_results' => 0,
                  'cache_time' => 0
              );
    
    $this->cache_times = array(
          array('time' => 0, 'name' => __('Disable Cache', self::ld)),
          array('time' => 3600, 'name' => __('1 hour', self::ld)),
          array('time' => 43200, 'name' => __('12 hours', self::ld)),
          array('time' => 86400, 'name' => __('1 day', self::ld)),
          array('time' => 259200, 'name' => __('3 days', self::ld)),
          array('time' => 604800, 'name' => __('1 week', self::ld)),
          array('time' => 1209600, 'name' => __('2 weeks', self::ld))
        );
    
    // theme colors
    $this->theme_colors = array(
         array(__('Shutterstock'), '#DC241F'),
         array(__('Dark-Green'), '#00650f'),
         array(__('Light-Blue'), '#007D9A'),
         array(__('Deep-Blue'), '#184e89'),
         array(__('Magenta'), '#630030'),
         array(__('Red'), '#872300'),
         array(__('Orange'), '#D45500'),
         array(__('Yellow'), '#e8871f'),
         array(__('Dark-Grey'), '#555555'),
         array(__('Custom Color'), '')           
        );
        
    // loader colors
    $this->loader_colors = array(
         array(__('Shutterstock'), 'image_93906.gif'),
         array(__('Light-Blue'), 'image_93907.gif'),
         array(__('Deep-Blue'), 'image_93909.gif'),
         array(__('Magenta'), 'image_93911.gif'),
         array(__('Red'), 'image_93910.gif'),
         array(__('Yellow'), 'image_93913.gif'),
         array(__('Black'), 'image_93914.gif'),
         array(__('White'), 'image_93915.gif')
        );
        
    // language definition
    $this->languages = array(
              0 => array('code' => '', 'name' => __('Auto', self::ld)),                  
              1 => array('code' => 'en', 'name' => __('English', self::ld)),                  
              2 => array('code' => 'fr', 'name' => __('French', self::ld)),                  
              3 => array('code' => 'de', 'name' => __('German', self::ld)),                  
              4 => array('code' => 'nl', 'name' => __('Dutch', self::ld)),
              5 => array('code' => 'es', 'name' => __('Spanish', self::ld)),                  
              6 => array('code' => 'it', 'name' => __('Italian', self::ld)),                  
              7 => array('code' => 'pt', 'name' => __('Portuguese', self::ld)),                  
              8 => array('code' => 'jp', 'name' => __('Japanese', self::ld)),                  
              9 => array('code' => 'ru', 'name' => __('Russian', self::ld)),                  
              10 => array('code' => 'zh', 'name' => __('Chinese', self::ld)),
          );
          
    $this->iso_to_lang = array(
              'fr' => 2, 'en-us' => 1, 'en' => 1, 'en-uk' => 1, 'de' => 3, 'es' => 5, 
              'it' => 6, 'pt' => 7, 'pt-br' => 7, 'jp' => 8, 'ru' => 9, 'zh' => 10
          );            
    

    if (is_admin())
    {
      add_action('wp_ajax_'.__class__.'_admin_action', array(&$this, 'action_admin')); // admin ajax actions 
      add_action('admin_menu', array(&$this, 'menu')); // add menu
      add_action('init', array(&$this, 'init')); // initialization 
    }
    else
    {              
      add_action('wp_print_scripts', array(&$this, 'add_js')); // add scripts        
      add_action('wp_print_styles', array(&$this, 'add_css')); // add css styles
      add_action('wp_head', array(&$this, 'add_css_head')); // add custom css to the head        
      add_action('wp_footer', array(&$this, 'add_search_script'));
      add_shortcode('shutterstock', array(&$this, 'shortcode')); // shortcode
      add_action('shutterstock_search', array(&$this, 'add_search_action')); // add search action
    }

    add_action('wp_ajax_'.__class__.'_action', array(&$this, 'action')); // frontend ajax actions
    add_action('wp_ajax_nopriv_'.__class__.'_action', array(&$this, 'action')); // frontend ajax actions
                              
    // activation and uninstall hook
    register_activation_hook(__FILE__, array(&$this, 'activation'));
    register_uninstall_hook(__FILE__, array(__class__, 'uninstall'));
    
    // register widget
    add_action('widgets_init', array(&$this, 'widget'));
  }
  
  function widget()
  {
    register_widget(__class__.'_widget');
  }
  
  // on activation
  function activation()
  {
    add_option(__class__.'_api', array(
                              'api_username' => '',
                              'api_key' => '',
                              'affiliate_id' => '',
                              'is_valid' => false,
                              'subid1' => '%domain%',
                              'subid2' => '%search%',
                              'subid3' => '%id%'));
 
    add_option(__class__.'_boxes', array());
    add_option(__class__.'_settings', $this->default_options);      
  }

  // uninstall
  static function uninstall()
  {
    delete_option(__class__.'_api');
    delete_option(__class__.'_boxes');
    delete_option(__class__.'_settings');
  }
  
  public function plugins_loaded()
  {
    // load text domain
    load_plugin_textdomain(self::ld, false, $this->plugin_path.'/languages/');
  }
    
  // init action
  function init()
  {
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
      return;
    
    add_action("admin_enqueue_scripts", array(&$this, 'add_admin_scripts_edit'));
    add_action('admin_footer-post.php', array(&$this, 'admin_footer_edit'));
    add_action('admin_footer-post-new.php', array(&$this, 'admin_footer_edit'));

    // register filters to add button into tinyMCE
    if (get_user_option('rich_editing') == 'true')
    {
      add_filter('mce_external_plugins', array(&$this, 'mce_plugin'));
      add_filter('mce_buttons', array(&$this, 'mce_buttons'));
    }    
  }
  
  // add mce button
  function mce_buttons($buttons)
  {
    array_push($buttons, '|', 'shutterstock_ap_button');
    return $buttons;
  }
  
  // set mce plugin
  function mce_plugin($plugin_array)
  {
    $plugin_array['shutterstock_ap_button_dialog'] = $this->plugin_url.'/backend/mce_plugin.js';
    return $plugin_array;
  }
      
  // add css into post/page edit
  function add_admin_scripts_edit($hook)
  {
    if ($hook != 'post.php' && $hook != 'post-new.php')
      return;
    
    wp_enqueue_style(__class__.'_button_dialog', $this->plugin_url.'/backend/shortcode_dialog.css', array(), self::version, 'all');      
    
    wp_enqueue_script(__class__, $this->plugin_url.'/backend/shortcode_dialog.js', array('jquery'), self::version);          
    wp_localize_script(__class__, __class__.'_data',
          array(
            'version' => self::version,
            'text' => array(
                'add_shortcode' => __('Add Shutterstock Shortcode', self::ld),
                'long_name' => __('Shutterstock AP shortcode dialog', self::ld)                
              )
            ));        
  }
  
  // admin footer for post.php and post-new.php
  function admin_footer_edit()
  {
    require_once $this->plugin_path.'/backend/shortcode_dialog.php';    
  }        
          
  // admin menu item
  function menu()
  {
    $hook = add_menu_page(__('Shutterstock'), __('Shutterstock'), 'manage_options', __class__, array(&$this, 'main_menu'), $this->plugin_url.'/backend/images/menu-icon.png');
    add_action("admin_print_styles-$hook", array(&$this, 'add_admin_css'));                             
    add_action("admin_print_scripts-$hook", array(&$this, 'add_admin_js'));                             
  }
                          
  // add styles to the admin area
  function add_admin_css()
  {
    wp_enqueue_style(__class__.'_styles', $this->plugin_url.'/backend/styles.css', array(), self::version, 'all');
    wp_enqueue_style(__class__.'_miniColors', $this->plugin_url.'/3rdparty/jquery.miniColors/jquery.miniColors.css', array(), self::version, 'all'); 
  }
  
  // add scripts
  function add_admin_js()
  {
    wp_enqueue_script('jquery');
    wp_enqueue_script(__class__.'_miniColors', $this->plugin_url.'/3rdparty/jquery.miniColors/jquery.miniColors.min.js', array('jquery'), self::version);          
    wp_enqueue_script(__class__, $this->plugin_url.'/backend/shutterstock_ap.js', array('jquery'), self::version);          
    wp_localize_script(__class__, __class__.'_data',
          array(
            'action_admin' => admin_url('admin-ajax.php?action='.__class__.'_admin_action'),
            'plugin_url' => $this->plugin_url,
            'text' => array(
                'button_save' => __('Save', self::ld),
                'button_saving' => __('Saving', self::ld)                  
              )
            ));          
  }
  
  
  // AJAX actions for admin env.
  function action_admin()
  {
    header("Content-Type: application/json");
    $action = isset($_POST['a'])?$_POST['a']:false;

    switch($action)
    {
      // save/check ShutterStock API and save affiliate ID
      case 'save_api':
        $api = new Shutterstock_AP_API($_POST['api_username'], $_POST['api_key']);
        $is_valid = false;
        try
        {
          $data = $api->get('test/echo', array('testing' => 'testing'), false);

          if ($data['testing'] == 'testing')
            $is_valid = true;                            
          else
            echo json_encode(array('status' => 2));                        
        }
        catch(Shutterstock_AP_API_Exception $e)
        {            
          echo json_encode(array('status' => 2));            
        }
                
        if ($is_valid)
          echo json_encode(array('status' => 1));
        
        update_option(__class__.'_api', array(
              'api_username' => $_POST['api_username'],
              'api_key' => $_POST['api_key'],              
              'affiliate_id' => $_POST['affiliate_id'],
              'is_valid' => $is_valid,
              'subid1' => $_POST['subid1'],
              'subid2' => $_POST['subid2'],
              'subid3' => $_POST['subid3']
            ));          
        break;
       
      // save settings          
      case 'save_settings':
        update_option(__class__.'_settings', array(
                  'language' => isset($_POST['language'])?$_POST['language']:0,
                  'show_images' => isset($_POST['show_images'])?$_POST['show_images']:0,
                  'images_div' => isset($_POST['images_div'])?$_POST['images_div']:'content',
                  'creator_id' => isset($_POST['creator_id'])?$_POST['creator_id']:'',
                  'sort_order' => isset($_POST['sort_order'])?$_POST['sort_order']:0,
                  'images_type' => isset($_POST['images_type'])?$_POST['images_type']:0,
                  'number_images' => isset($_POST['number_images'])?$_POST['number_images']:3,
                  'thumbnail_size' => isset($_POST['thumbnail_size'])?$_POST['thumbnail_size']:1,
                  'show_extra_search' => isset($_POST['show_extra_search'])?$_POST['show_extra_search']:1,
                  'show_boxes' => isset($_POST['show_boxes'])?$_POST['show_boxes']:0,
                  'show_header' => isset($_POST['show_header'])?$_POST['show_header']:1,
                  'auto_scroll' => isset($_POST['auto_scroll'])?$_POST['auto_scroll']:1,
                  'swap_keycustom' => isset($_POST['swap_keycustom'])?$_POST['swap_keycustom']:0,
                  'show_keywords' => isset($_POST['show_keywords'])?$_POST['show_keywords']:1,
                  'show_customcontent' => isset($_POST['show_customcontent'])?$_POST['show_customcontent']:0,
                  'nothing_found' => isset($_POST['nothing_found'])?$_POST['nothing_found']:0,
                  'nothing_found_keywords' => isset($_POST['nothing_found_keywords'])?$_POST['nothing_found_keywords']:'',
                  'open_links' => isset($_POST['open_links'])?$_POST['open_links']:0,
                  'theme_color' => isset($_POST['theme_color'])?$_POST['theme_color']:0,
                  'theme_color_custom' => isset($_POST['theme_color_custom'])?$_POST['theme_color_custom']:'#609030',
                  'loader_color' => isset($_POST['loader_color'])?$_POST['loader_color']:4,          
                  'image_detail' => isset($_POST['image_detail'])?$_POST['image_detail']:0,                                                            
                  'loader_text' => isset($_POST['loader_text'])?$_POST['loader_text']:'',                                                            
                  'loader_text_show' => isset($_POST['loader_text_show'])?$_POST['loader_text_show']:0,
                  'custom_content' => isset($_POST['custom_content'])?$_POST['custom_content']:'',
                  'show_logo' => isset($_POST['show_logo'])?$_POST['show_logo']:0,
                  'show_tooltip' => isset($_POST['show_tooltip'])?$_POST['show_tooltip']:0,
                  'image_results' => isset($_POST['image_results'])?$_POST['image_results']:0,
                  'cache_time' => isset($_POST['cache_time'])?$_POST['cache_time']:0
                ));          
        echo json_encode(array('status' => 1));                        
        break;
    
      // update statuses of boxes
      case 'update_boxes':
        update_option(__class__.'_boxes', $_POST['boxes']);
        echo json_encode(array('status' => 1));
        break;
    }
              
    exit();
  }
  
  function hover_color($color)
  {      
    $level = array(0x2C, 0x34, 0x26);
    $c = strlen($color) == 7?1:0;
    $p = array();      
    for($i=$c, $n=6+$c;$i<$n;$i++)
    {
      if (($i-$c) % 2 == 0)
      {
        $col = hexdec(($color[$i].$color[$i+1])) - $level[($i-$c)/2];
        if ($col < 0) $col = 0;
        $ca = dechex($col);                    
        $p[] = (strlen($ca) == 1?'0':'').$ca;
      }
    }
    return '#'.implode('', $p);
  }
                                
  // main menu of this plugin
  function main_menu()
  {
    require_once $this->plugin_path.'/backend/header.php';
          
    // theme colors for JS
    $theme_colors = array();
    reset($this->theme_colors);
    while(list(, $color) = @each($this->theme_colors))
      $theme_colors[] = "'".$color[1]."'";
    
    // loader colors for JS
    $loader_colors = array();
    reset($this->loader_colors);
    while(list(, $loader) = @each($this->loader_colors))
      $loader_colors[] = "'".$loader[1]."'";

    $api = get_option(__class__.'_api', array());
    $boxes = get_option(__class__.'_boxes', array());
    $settings = get_option(__class__.'_settings', $this->default_options);
    
    require_once $this->plugin_path.'/backend/settings.php';
  }
                
  protected function short_str($t, $size)
  {
    if (mb_strlen($t, 'UTF-8') > $size)
      $t = mb_substr($t, 0, $size, 'UTF-8').'...';  

    return $t;
  }
  
  
  // add scripts to the frontend
  function add_js()
  {    
    wp_enqueue_script('jquery');
    wp_enqueue_script(__class__.'_stickytooltip', $this->plugin_url.'/3rdparty/stickytooltip/stickytooltip.js', array('jquery'), self::version);          
    wp_enqueue_script(__class__.'_colorbox', $this->plugin_url.'/3rdparty/colorbox/jquery.colorbox-min.js', array('jquery'), '1.3.19');                           
  
    $api = get_option(__class__.'_api', false);
    $settings = get_option(__class__.'_settings', $this->default_options);

    if (($api == false)||($settings == false)) return;      
    if (!$api['is_valid']) return;
      
    $loader_img = '<img src="'.$this->plugin_url.'/frontend/loaders/'.$this->loader_colors[$settings['loader_color']][1].'" />';
    if ($settings['loader_text'])
    {
      switch($settings['loader_text_show'])
      {
        case 1: // bottom
          $loader = '<table class="shutterstock_ap_loader_table"><tr><td valign="middle">'.$loader_img.'</td></tr><tr><td>'.esc_html($settings['loader_text']).'</td></tr></table>';
          break;
        case 2: // left
          $loader = '<table class="shutterstock_ap_loader_table"><tr><td>'.esc_html($settings['loader_text']).'</td><td valign="middle">'.$loader_img.'</td></tr></table>';
          break;
        case 3: //right
          $loader = '<table class="shutterstock_ap_loader_table"><tr><td valign="middle">'.$loader_img.'</td><td>'.esc_html($settings['loader_text']).'</td></tr></table>';
          break;
        default: // top
          $loader = '<table class="shutterstock_ap_loader_table"><tr><td>'.esc_html($settings['loader_text']).'</td></tr><tr><td valign="middle">'.$loader_img.'</td></tr></table>';              
      }                    
    }
    else
      $loader = '<table class="shutterstock_ap_loader_table"><tr><td>'.$loader_img.'</td></tr></table>';

    wp_enqueue_script(__class__, $this->plugin_url.'/frontend/shutterstock_ap.js', array('jquery'), self::version);          
    wp_localize_script(__class__, __class__.'_data',
          array(
            'action' => admin_url('admin-ajax.php?action='.__class__.'_action'),
            'plugin_url' => $this->plugin_url,
            'loader' => $loader,
            'element_id' => $settings['images_div'],
            'show_method' => $settings['show_images'],
            'auto_scroll' => $settings['auto_scroll'],
            'creator_id' => $settings['creator_id'],               
            'search' => isset($_GET['s'])?$_GET['s']:'',
            'number_images' => $settings['number_images'],
            'order' => $settings['sort_order'],
            'show_extra_search' => $settings['show_extra_search'],
            'show_header' => $settings['show_header'],
            'images_type' => $settings['images_type'],
            'thumb_size' => $settings['thumbnail_size'],
            'page' => 1, 
            'image_detail' => $settings['image_detail'],
            'swap_keycustom' => $settings['swap_keycustom'],
            'show_keywords' => $settings['show_keywords'],
            'show_customcontent' => $settings['show_customcontent'],              
            'show_logo' => $settings['show_logo'],
            'show_tooltip' => $settings['show_tooltip'],
            'image_results' => $settings['image_results'],
            'text' => array(
                        'ajax_error' => 'Cannot load content from the server.'
                  )
            ));                                
  }
  
  // add styles to the frontend
  function add_css()
  {
    wp_enqueue_style(__class__.'_stickytooltip', $this->plugin_url.'/3rdparty/stickytooltip/stickytooltip.css', array(), self::version, 'all');    
    wp_enqueue_style(__class__.'_colorbox', $this->plugin_url.'/3rdparty/colorbox/colorbox.css', array(), '1.3.19', 'all');    
    wp_enqueue_style(__class__.'_styles', $this->plugin_url.'/frontend/styles.css', array(), self::version, 'all');    
  }
  
  // add custom CSS styles to the head
  function add_css_head()
  {
    $api = get_option(__class__.'_api', false);
    $settings = get_option(__class__.'_settings', $this->default_options);

    if (($api == false)||($settings == false)) return;      
    if (!$api['is_valid']) return;
    
    require_once $this->plugin_path.'/frontend/custom_styles.php';          
  }
  
  function add_search_script()
  {
    if (isset($_GET['s']))
    {
      echo "
      <script>
      jQuery(document).ready(function()
      {
        new Shutterstock_AP(
                    {
                      'element_id': Shutterstock_AP_data.element_id,
                      'show_method': Shutterstock_AP_data.show_method,
                      'loader_html': Shutterstock_AP_data.loader,
                      'search_text': Shutterstock_AP_data.search,
                      'number_images': Shutterstock_AP_data.number_images,
                      'creator_id': ".(isset($_GET['creator'])?"'".addcslashes($_GET['creator'], "'")."'":'Shutterstock_AP_data.creator_id').",
                      'show_extra_search': parseInt(Shutterstock_AP_data.show_extra_search),
                      'show_header': parseInt(Shutterstock_AP_data.show_header),
                      'auto_scroll': parseInt(Shutterstock_AP_data.auto_scroll),
                      'order': parseInt(Shutterstock_AP_data.order),
                      'is_widget': false,
                      'only_img': ".(isset($_GET['img'])?'true':'false').",
                      'images_type': parseInt(Shutterstock_AP_data.images_type),                        
                      'thumb_size': parseInt(Shutterstock_AP_data.thumb_size),                        
                      'image_detail': parseInt(Shutterstock_AP_data.image_detail),
                      'swap_keycustom': parseInt(Shutterstock_AP_data.swap_keycustom),
                      'show_keywords': parseInt(Shutterstock_AP_data.show_keywords),
                      'show_customcontent': parseInt(Shutterstock_AP_data.show_customcontent),
                      'show_logo': parseInt(Shutterstock_AP_data.show_logo),
                      'show_tooltip': parseInt(Shutterstock_AP_data.show_tooltip),
                      'image_results': parseInt(Shutterstock_AP_data.image_results)
                    });
      });
      </script>
      ";        
    }
  }
  
  // add search function via action
  function add_search_action()
  {
    $api = get_option(__class__.'_api', false);
    $settings = get_option(__class__.'_settings', $this->default_options);
          
    if (($api == false)||($settings == false)) return;      
    if (!$api['is_valid']) return;

    $id = 'shutterstock_ap_search_action_'.$this->action_counter;
    $this->action_counter++;              

    echo "
      <div id=\"".$id."\"></div>
      <script>
      jQuery(document).ready(function()
      {
        new Shutterstock_AP(
                    {
                      'element_id': '".$id."',
                      'show_method': Shutterstock_AP_data.show_method,
                      'loader_html': Shutterstock_AP_data.loader,
                      'search_text': Shutterstock_AP_data.search,
                      'number_images': Shutterstock_AP_data.number_images,
                      'creator_id': Shutterstock_AP_data.creator_id,
                      'show_extra_search': parseInt(Shutterstock_AP_data.show_extra_search),
                      'show_header': parseInt(Shutterstock_AP_data.show_header),
                      'auto_scroll': parseInt(Shutterstock_AP_data.auto_scroll),
                      'order': parseInt(Shutterstock_AP_data.order),
                      'is_widget': false,
                      'images_type': parseInt(Shutterstock_AP_data.images_type),
                      'thumb_size': parseInt(Shutterstock_AP_data.thumb_size),
                      'image_detail': parseInt(Shutterstock_AP_data.image_detail),                         
                      'swap_keycustom': parseInt(Shutterstock_AP_data.swap_keycustom),
                      'show_keywords': parseInt(Shutterstock_AP_data.show_keywords),
                      'show_customcontent': parseInt(Shutterstock_AP_data.show_customcontent),
                      'show_logo': parseInt(Shutterstock_AP_data.show_logo),
                      'show_tooltip': parseInt(Shutterstock_AP_data.show_tooltip),
                      'image_results': parseInt(Shutterstock_AP_data.image_results)
                    });
      });
      </script>
      ";            
  }    
  
  // AJAX actions from frontend
  function action()
  {
    $api = get_option(__class__.'_api', false);
    $settings = get_option(__class__.'_settings', $this->default_options);
          
    if (($api == false)||($settings == false)) exit;      
    if (!$api['is_valid']) exit;
    
    // auto detect language
    if ($settings['language'] == 0)
    {
      $language = 1;
      $lang = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
      $lang = strtolower(trim($lang[0]));
      if ($lang)
      {
        if (isset($this->iso_to_lang[$lang]))
        {
          $language = $this->iso_to_lang[$lang];
        }
        else
        {
          $lang = explode('-', $lang);
          $lang = $lang[0];
          if (isset($this->iso_to_lang[$lang]))
          {
            $language = $this->iso_to_lang[$lang];
          }            
        }
      }
    }
    else
      $language = $settings['language'];
    
    $language = $this->languages[$language]['code'];
    
    
    // Shutterstock API    
    $sa = new Shutterstock_AP_API($api['api_username'], $api['api_key'], isset($settings['cache_time'])?$this->cache_times[$settings['cache_time']]['time']:86400);
    
    // show detail
    if (isset($_POST['detail'])&&($_POST['detail']))
    {
      $image_detail = isset($_POST['image_detail'])?(int)$_POST['image_detail']:$settings['image_detail'];
      
      if (isset($_POST['is_widget']) && $_POST['is_widget'] && $image_detail != 0)
        $image_detail = 2;

      $swap_keycustom = isset($_POST['swap_keycustom'])?$_POST['swap_keycustom']:false;
      $show_keywords = isset($_POST['show_keywords'])?$_POST['show_keywords']:false;
      $show_customcontent = isset($_POST['show_customcontent'])?$_POST['show_customcontent']:false;
            
      try
      {
        $r = $sa->get('images/'.$_POST['detail'], array('language' => $language));
      }
      catch(Shutterstock_AP_API_Exception $e)
      {
        require_once $this->plugin_path.'/frontend/error.php';
        exit;            
      }
      
      require_once $this->plugin_path.'/frontend/detail.php';
    }
    else
    if (isset($_POST['search']))
    {
      $search = isset($_POST['search'])?$_POST['search']:'';
      $order = isset($_POST['order'])?$_POST['order']:'';
      $page = isset($_POST['page'])?$_POST['page']:1;
      $number_images = isset($_POST['number_images'])?$_POST['number_images']:3;
      $is_widget = isset($_POST['is_widget'])?$_POST['is_widget']:false;
      $element_id = isset($_POST['element_id'])?$_POST['element_id']:'content';
      $creator_id = isset($_POST['creator_id'])?$_POST['creator_id']:false;
      $show_extra_search = isset($_POST['show_extra_search'])?$_POST['show_extra_search']:true;
      $show_header = isset($_POST['show_header'])?$_POST['show_header']:true;
      $show_paging = isset($_POST['show_paging'])?$_POST['show_paging']:true;
      $show_more = isset($_POST['show_more'])?$_POST['show_more']:false;
      $only_img = isset($_POST['only_img'])?$_POST['only_img']:false;
      $images_type = isset($_POST['images_type'])?$_POST['images_type']:false;                         
      $thumb_size = isset($_POST['thumb_size'])?$_POST['thumb_size']:1;
      $image_detail = isset($_POST['image_detail'])?$_POST['image_detail']:$settings['image_detail'];
      $show_logo = isset($_POST['show_logo'])?$_POST['show_logo']:false;                         
      $show_tooltip = isset($_POST['show_tooltip'])?$_POST['show_tooltip']:false;
      $image_results = isset($_POST['image_results'])?$_POST['image_results']:0;
      
      if ($page < 1) $page = 1;                
      
      // filter
      switch($images_type)
      {
        case 1:
          $filter = 'photos';
          break;
        case 2:
          $filter = 'illustrations';
          break;
        case 3:
          $filter = 'vectors';
          break;
        default:
          $filter = 'all';
      }
            
      if ($_POST['search'])
      {
        $query = array(
                    'searchterm' => urlencode($_POST['search']),
                    'language' => $language,
                    'sort_method' => $this->sort_order[$order],
                    'safesearch' => 1,
                    'search_group' => $filter,
                    'page_number' => ceil($number_images*$page / 150) - 1
                  );
        
        if ($creator_id)
          $query['submitter_id'] = (int)$creator_id;
        
        try
        {
          $r = $sa->get('images/search', $query);
        } 
        catch(Shutterstock_AP_API_Exception $e)
        {
          require_once $this->plugin_path.'/frontend/error.php';
          exit;            
        }
      }
      else
        $r = array('count' => 0);

      if (!isset($r['count']) || $r['count'] == 0) // no result
      {
        
        switch($settings['nothing_found'])
        {
          case 0: // show nothing            
            break;
          case 1: // show images by keyword
            if ($settings['nothing_found_keywords'] && !isset($_POST['nothing_found_action']))
            {
              $_POST['search'] = $settings['nothing_found_keywords'];
              $_POST['nothing_found_action'] = true;
              $this->action();
            }
            break;
        }       
        exit;                  
      }
      
      $n_items = $r['count'];
      $per_page = $number_images;
      $max_page = ceil($n_items/$per_page);
      if ($page > $max_page) $page = 1;
      
      $subids = array();
      if (isset($api['subid1']) && $api['subid1'])
        $subids[0] = $api['subid1'];

      if (isset($api['subid2']) && $api['subid2'])
        $subids[1] = $api['subid2'];        

      if (isset($api['subid3']) && $api['subid3'])
        $subids[2] = $api['subid3'];
        
      $subids_str = array();
      
      foreach($subids as $index => $subid)
        $subids_str[] = 'subid'.($index+1).'='.urlencode(str_replace(array('%domain%', '%search%', '%id%'), array($_SERVER['SERVER_NAME'], $_POST['search'], ''), $subid));
        
      $subids_str = implode('&', $subids_str);
      if ($api['affiliate_id'])
        $main_aff_link = 'http://shutterstock.7eer.net/c/'.$api['affiliate_id'].'/43068/1305?'.$subids_str.(strlen($subids_str) > 0?'&':'').'u=';
      else
        $main_aff_link = '';
            
      require_once $this->plugin_path.'/frontend/top.php';
      
      $c = 0;
      $tooltips = array();
      $results = array_slice($r['results'], ($page-1)*$number_images - (ceil($number_images*$page / 150) - 1)*150, $number_images);
       
      while(list(, $item) = @each($results))
      {
        $subids_str = array();
        
        foreach($subids as $index => $subid)
          $subids_str[] = 'subid'.($index+1).'='.urlencode(str_replace(array('%domain%', '%search%', '%id%'), array($_SERVER['SERVER_NAME'], $_POST['search'], $item['photo_id']), $subid));
          
        $subids_str = implode('&', $subids_str);      
        
        if ($api['affiliate_id'])        
          $item['affiliation_link'] = 'http://shutterstock.7eer.net/c/'.$api['affiliate_id'].'/43068/1305?'.$subids_str.(strlen($subids_str) > 0?'&':'').'u='.urlencode($item['web_url']);
        else
          $item['affiliation_link'] = $item['web_url'];

        $tooltips[] = array(
              'title' => $item['description'],
              'image_url' => $item['preview']['url'],
              'image_width' => $item['preview']['width'],
              'image_height' => $item['preview']['height']
            );                     
        require $this->plugin_path.'/frontend/image.php';
        $c++;
      }
      
      if ($show_tooltip)
        require_once $this->plugin_path.'/frontend/tooltips.php';

      if ($show_paging)
        require $this->plugin_path.'/frontend/paging.php';

      require_once $this->plugin_path.'/frontend/bottom.php';        
    }            
    exit; 
  }
  
  // analyze text (only count occurence of words)
  function analyze($text)
  {
    $text = mb_strtolower(strip_tags(strip_shortcodes($text)));
          
    $words = preg_split('/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/', $text, -1, PREG_SPLIT_NO_EMPTY);
    $counts = array_count_values($words);
    arsort($counts);
    
    // get 3 words
    $selected = array();
    reset($counts);
    $c = 0;
    while((list($word, $num) = @each($counts)) && ($c < 3))
    {
      // min. is 3 characters
      if (mb_strlen($word) > 3)
      {
        $selected[] = $word;
        $c++;
      }        
    }
    
    if (count($selected) == 0) $selected[] = 'test';            
    
    return $selected;            
  }
      
  // shortcode which user can embed into post/page    
  function shortcode($atts)
  {
    global $post;          
  
    $api = get_option(__class__.'_api', false);
    $settings = get_option(__class__.'_settings', $this->default_options);
          
    if (($api == false)||($settings == false)) exit;      
    if (!$api['is_valid']) exit;
  
    if (isset($atts['auto']) && $atts['auto']) $auto = $atts['auto'];
    else $auto = false;

    if (isset($atts['keywords']) && $atts['keywords'] && !$auto) $keywords = $atts['keywords'];
    else $keywords = @implode(',', $this->analyze($post->post_title)); 
    
    if (isset($atts['creator']) && $atts['creator']) $creator_id = $atts['creator'];
    else $creator_id = 0;

    if (isset($atts['order'])) $order = (int)$atts['order'];
    else $order = 3;
    
    if (isset($atts['type'])) $type = (int)$atts['type'];
    else $type = 0;
    
    if (isset($atts['images']) && $atts['images']) $images = (int)$atts['images'];
    else $images = 3;

    if (isset($atts['thumbsize'])) $thumb_size = (int)$atts['thumbsize'];
    else $thumb_size = 2;

    if (isset($atts['detail'])) $image_detail = (int)$atts['detail'];
    else $image_detail = 1;
    
    if (isset($atts['search'])) $search = (int)$atts['search']?1:0;
    else $search = 0;

    if (isset($atts['header'])) $header = (int)$atts['header']?1:0;
    else $header = 0;

    if (isset($atts['autoscroll'])) $autoscroll = (int)$atts['autoscroll']?1:0;
    else $autoscroll = 0;

    if (isset($atts['paging'])) $show_paging = (int)$atts['paging']?1:0;
    else $show_paging = 0;
    
    if (isset($atts['swapkc'])) $swap_keycustom = (int)$atts['swapkc']?1:0;
    else $swap_keycustom = 0;

    if (isset($atts['showkeywords'])) $show_keywords = (int)$atts['showkeywords']?1:0;
    else $show_keywords = 0;

    if (isset($atts['customcontent'])) $show_customcontent = (int)$atts['customcontent']?1:0;
    else $show_customcontent = 0;
          
    if (isset($atts['more'])) $show_more = (int)$atts['more']?1:0;
    else $show_more = 0;

    if (isset($atts['logo'])) $show_logo = (int)$atts['logo']?1:0;
    else $show_logo = 0;

    if (isset($atts['tooltips'])) $show_tooltip = (int)$atts['tooltips']?1:0;
    else $show_tooltip = 0;

    if (isset($atts['results'])) $image_results = (int)$atts['results']?1:0;
    else $image_results = 0;
    
    $id = 'shutterstock_ap_shortcode_'.$this->shortcode_counter;
    $this->shortcode_counter++;              
    return "
      <div id=\"".$id."\"></div>
      <script>
      jQuery(document).ready(function()
      {
        var var_shutterstock_ap_search = new Shutterstock_AP(
                    {
                      'element_id': '".$id."',
                      'show_method': 0,
                      'loader_html': Shutterstock_AP_data.loader,
                      'search_text': '".addcslashes($keywords, "'")."',
                      'number_images': ".$images.",
                      'creator_id': '".addcslashes($creator_id, "'")."',
                      'show_extra_search': ".$search.",
                      'show_header': ".$header.",
                      'auto_scroll': ".$autoscroll.",
                      'show_paging': ".$show_paging.",
                      'show_more': ".$show_more.",
                      'order': ".$order.",                        
                      'is_widget': false,
                      'images_type': ".$type.",
                      'thumb_size': ".$thumb_size.",
                      'image_detail': ".$image_detail.",
                      'swap_keycustom': ".$swap_keycustom.",
                      'show_keywords': ".$show_keywords.",
                      'show_customcontent': ".$show_customcontent.",
                      'show_logo': ".$show_logo.",
                      'show_tooltip': ".$show_tooltip.",
                      'image_results': ".(int)$image_results."
                    });                                            
      });
      </script>
      ";
  }               
}

$shutterstock_ap = new Shutterstock_AP();

?>