<?php

if (   (! isset( $_GET['some_parameter'] ) ) && (!function_exists ('get_option')  )  )  { die('You do not have permission to direct access to this file !!!'); }

if (!class_exists('wpdev_shutter')) {
    class wpdev_shutter {


        // <editor-fold defaultstate="collapsed" desc="  C O N S T R U C T O R  &  P r o p e r t i e s ">

            function wpdev_shutter() {

                // Create admin menu
                add_action('admin_menu', array(&$this, 'add_new_admin_menu'));


                // Client side print JSS
                add_action('wp_head',array(&$this, 'js__client'));

                // S H O R T C O D E - shutter
                add_shortcode('shutterstock', array(&$this, 'shutterstock_shortcode'));
                //add_shortcode('shutterstock_search', array(&$this, 'shutterstock_search_shortcode'));

                add_action('shutterstock_search',array(&$this, 'shutterstock_in_search_action'));

                // Install / Uninstall
                register_activation_hook( WPDEV_SHUTTER_FILE, array(&$this,'wpdev_shutter_activate' ));
                register_deactivation_hook( WPDEV_SHUTTER_FILE, array(&$this,'wpdev_shutter_deactivate' ));

    /*

                // Add custom buttons
                add_action( 'init', array(&$this,'add_custom_buttons') );
                add_action( 'admin_head', array(&$this,'insert_wpdev_button'));

                add_action( 'admin_footer', array(&$this,'print_js_at_footer') );


                // Add settings link at the plugin page
                add_filter('plugin_action_links', array(&$this, 'plugin_links'), 10, 2 );

                // Add widjet
                // add_action( 'init', array(&$this,'add_shutter_widjet') );


                if ( ( strpos($_SERVER['REQUEST_URI'],'wpdev-shutter.phpwpdev-shutter')!==false) &&
                        ( strpos($_SERVER['REQUEST_URI'],'wpdev-shutter.phpwpdev-shutter-reservation')===false )
                ) {
                    if (defined('WP_ADMIN')) if (WP_ADMIN === true) wp_enqueue_script( 'jquery-ui-dialog' );
                    wp_enqueue_style(  'wpdev-bk-jquery-ui', WPDEV_SHUTTER_PLUGIN_URL. '/css/jquery-ui.css', array(), 'wpdev-bk', 'screen' );
                }
    /**/
             }

        // </editor-fold>


        // <editor-fold defaultstate="collapsed" desc=" S u p p o r t  Functions  for ADMIN MENU   ">
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // ADMIN MENU SECTIONS  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // Add Menu page
        function add_new_admin_menu() {

            //$users_roles = array(get_SHUTTER_option( 'shutter_user_role_shutter' ), get_SHUTTER_option( 'shutter_user_role_addshutter' ), get_SHUTTER_option( 'shutter_user_role_settings' ) );
            $users_roles = array('contributor');
//            $users_roles = array('administrator');

            for ($i = 0 ; $i < count($users_roles) ; $i++) {
                if ( $users_roles[$i] == 'administrator' )  $users_roles[$i] = 'activate_plugins';
                if ( $users_roles[$i] == 'editor' )         $users_roles[$i] = 'publish_pages';
                if ( $users_roles[$i] == 'author' )         $users_roles[$i] = 'publish_posts';
                if ( $users_roles[$i] == 'contributor' )    $users_roles[$i] = 'edit_posts';
                if ( $users_roles[$i] == 'subscriber')      $users_roles[$i] = 'read';
            }

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // M A I N     P L U G I N    P A G E
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                      //    Page title                           ,     Menu  title
            $pagehook1 = add_menu_page( __('Shutterstock photo', 'wpdev-shutter'), __('Shutterstock', 'wpdev-shutter'), $users_roles[0],
                    WPDEV_SHUTTER_FILE . 'wpdev-shutter',
                    array(&$this, 'on_show_shutter_page_main'),
                    WPDEV_SHUTTER_PLUGIN_URL . '/img/favicon.ico'  );

            add_action("admin_print_scripts-" . $pagehook1 , array( &$this, 'add_js_only_4_plugin_admin_page'));
        }

        // For showing menu page
        function on_show_shutter_page_main() {
            $this->on_show_page_adminmenu('wpdev-shutter','/img/shutter-48x48.png', __('Shutterstock settings', 'wpdev-shutter'),1);
        }

        // Show HEAD of admin menu page
        function on_show_page_adminmenu($html_id, $icon, $title, $content_type) {
            ?>
            <div id="<?php echo $html_id; ?>-general" class="wrap shutterpage shutterstcok_settings_page">
            <?php
            if ($content_type == 3 )
                echo '<div class="icon32" style="margin:5px 40px 10px 10px;"><img src="'.  WPDEV_SHUTTER_PLUGIN_URL . 'shutterstock-settings.gif' .'"><br /></div>' ;
            else
                echo '<div class="icon32" style="margin:15px 15px 10px 10px;"><img src="'. WPDEV_SHUTTER_PLUGIN_URL . '/img/shutterstock-settings.gif' .'"><br /></div>' ; ?>

            <h2><?php echo $title; ?></h2>
            <?php
            switch ($content_type) {
                case 1: $this->settings_shutter_page();
                    break;
                default: break;
            } ?>
            </div>
            <?php
        }
        

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // </editor-fold>


        // <editor-fold defaultstate="collapsed" desc="  S u p p o r t  Functions  for   JS  &   CSS   Printing">
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //    J S    &   C S S     F I L E S     &     V a r i a b l e s
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                // add hook for printing scripts only at this plugin page
                function add_js_only_4_plugin_admin_page() {
                    // Write inline scripts and CSS at HEAD
                    add_action('admin_head', array(&$this, 'js__admin' ), 1);
                }

                // HEAD for ADMIN page
                function js__admin() {
                    $this->print_js_css();
                }

                // HEAD for Client side
                function js__client(){
                    
                    load_SHUTTER_Translation();
                    $this->print_js_css(0);
                }

        // </editor-fold>



                

        // <editor-fold defaultstate="collapsed" desc="  S E T T I N G S       C O N T E N T   ">
        
                // Show General Settings CONTENT of Plugin
                function settings_shutter_page(){

                    // Save settings
                    if ( isset($_POST['login_name_shutterstock']) ) {

                          update_option( 'shutterstock_login'   , $_POST['login_name_shutterstock']);
                          update_option( 'shutterstock_pasword' , $_POST['login_password_shutterstock']);

                          update_option( 'shutterstock_sort_method' , $_POST['image_sort_type'] );
                          update_option( 'shutterstock_search_group' , $_POST['search_withing'] );

                          update_option( 'shutterstock_search_container_id' , $_POST['search_container_id'] );
                          update_option( 'shutterstock_image_place' , $_POST['image_place'] );
                          update_option( 'shutterstock_num_per_page' , $_POST['number_per_page'] );
                          update_option( 'shutterstock_thumbnails_size' , $_POST['thumbnails_size'] );
                          update_option( 'shutterstock_alternative_affiliate_id' , $_POST['alternative_affiliate_id'] );

                          //if (isset($_POST['shutterstock_safesearch'])) update_option('shutterstock_safesearch', 'On');
                          //else                                          update_option('shutterstock_safesearch', 'Off');


                          if (isset($_POST['shutterstock_is_show_search_field_in_detail'])) update_option('shutterstock_is_show_search_field_in_detail', 'On');
                          else                                                              update_option('shutterstock_is_show_search_field_in_detail', 'Off');

                          if (isset($_POST['shutterstock_is_show_img_caption'])) update_option('shutterstock_is_show_img_caption', 'On');
                          else                                          update_option('shutterstock_is_show_img_caption', 'Off');
                          
                          if (isset($_POST['shutterstock_is_show_buttons'])) update_option('shutterstock_is_show_buttons', 'On');
                          else                                          update_option('shutterstock_is_show_buttons', 'Off');
                          
                          if (isset($_POST['shutterstock_is_show_box'])) update_option('shutterstock_is_show_box', 'On');
                          else                                          update_option('shutterstock_is_show_box', 'Off');

                          update_option( 'shutterstock_open_link_in' , $_POST['shutterstock_open_link_in'] );
                          update_option( 'shutterstock_theme_color' , $_POST['shutterstock_theme_color'] );
                          update_option( 'shutterstock_loader_color' , $_POST['shutterstock_loader_color'] );
                          update_option( 'shutterstock_theme_color_custom' , $_POST['shutterstock_theme_color_custom'] );

                          if (isset($_POST['shutterstock_is_show_header'])) update_option('shutterstock_is_show_header', 'On');
                          else                                         update_option('shutterstock_is_show_header', 'Off');
                          if (isset($_POST['shutterstock_is_scroll'])) update_option('shutterstock_is_scroll', 'On');
                          else                                         update_option('shutterstock_is_scroll', 'Off');


                          }


                    $login_name_shutterstock = get_option('shutterstock_login');
                    $login_password_shutterstock = get_option('shutterstock_pasword');

                    $image_sort_type = get_option( 'shutterstock_sort_method' );
                    $search_withing = get_option( 'shutterstock_search_group' );

                    $search_container_id = get_option( 'shutterstock_search_container_id' );
                    $image_place = get_option( 'shutterstock_image_place' );
                    $number_per_page = get_option( 'shutterstock_num_per_page' );
                    $thumbnails_size = get_option( 'shutterstock_thumbnails_size' );
                    $alternative_affiliate_id = get_option( 'shutterstock_alternative_affiliate_id' );
                    //$shutterstock_safesearch = get_option( 'shutterstock_safesearch' );
                    $shutterstock_is_show_img_caption = get_option( 'shutterstock_is_show_img_caption' );
                    $shutterstock_is_show_buttons = get_option( 'shutterstock_is_show_buttons' );
                    $shutterstock_is_show_search_field_in_detail = get_option('shutterstock_is_show_search_field_in_detail');
                    $shutterstock_is_show_box = get_option( 'shutterstock_is_show_box' );
                    $shutterstock_is_scroll = get_option( 'shutterstock_is_scroll' );
                    $shutterstock_is_show_header = get_option( 'shutterstock_is_show_header' );
                    $shutterstock_open_link_in = get_option('shutterstock_open_link_in');
                    $shutterstock_theme_color = get_option('shutterstock_theme_color');
                    $shutterstock_theme_color_custom = get_option('shutterstock_theme_color_custom');
                    $shutterstock_loader_color = get_option('shutterstock_loader_color');
                    //debuge($shutterstock_loader_color);
                    ?>

                <div class="clear" style="height:0px;"></div>
                <div id="ajax_working"></div>
                <div id="poststuff" class="metabox-holder">




                    <form  name="post_option" action="" method="post" id="post_option" >

                    <div style="width:70%;margin-right:20px;float: left;">

                        <div class='meta-box'>
                          <div <?php $my_close_open_win_id = 'shutterstock_meta_settings_1'; ?>
                              id="<?php echo $my_close_open_win_id; ?>"
                              class="postbox <?php if ( '1' == get_user_option( 'shutterstock_win_' . $my_close_open_win_id ) ) echo 'closed'; ?>" >
                              <div title="<?php _e('Click to toggle','shutterstock'); ?>" class="handlediv"
                                   onclick="javascript:verify_window_opening(<?php $user = wp_get_current_user(); if (isset( $user->ID )){ echo $user->ID;} else {echo '0'; }; ?>, '<?php echo $my_close_open_win_id; ?>');"><br></div>
                                <h3 class='hndle'><span><?php _e('Shutterstock-API', 'shutterstock'); ?></span></h3> <div class="inside">


                                <table class="form-table"><tbody>
                                        <tr valign="top">
                                            <th scope="row"><label for="login_name_shutterstock" ><?php _e('API Login', 'shutterstock'); ?>:</label></th>
                                            <td><input autocomplete="off" id="login_name_shutterstock" class="regular-text code" type="text" size="45" value="<?php echo $login_name_shutterstock; ?>" name="login_name_shutterstock"/>
                                                <span class="description"><?php _e('Type your API login for Shutterstock', 'shutterstock');?>.</span>
                                            </td>
                                        </tr>

                                        <tr valign="top">
                                            <th scope="row"><label for="login_password_shutterstock" ><?php _e('API Key', 'shutterstock'); ?>:</label></th>
                                            <td style="padding:2px 10px;"><input autocomplete="off" id="login_password_shutterstock" class="regular-text" type="password" size="45" value="<?php echo $login_password_shutterstock; ?>" name="login_password_shutterstock"/>
                                                <span class="description"><?php _e('Type your API key for shutterstock', 'shutterstock');?>.</span>
                                            </td>
                                        </tr>
                                        <?php
                                        if ( (! empty($login_name_shutterstock)) && (! empty($login_password_shutterstock)) )
                                        if ( is_shutter_login_correct() ) { ?>
                                        <tr> <th colspan="2" class="shutterstock_login_pass"> <?php _e('Login is PASS', 'shutterstock');?> </th> </tr>
                                        <?php } else { ?>
                                        <tr> <th colspan="2" class="shutterstock_login_failed"> <?php _e('Login is FAILED', 'shutterstock');?> </th> </tr>
                                        <?php } ?>


                                        <tr valign="top">
                                            <th scope="row"><label for="alternative_affiliate_id" ><?php _e('Referrer Code', 'shutterstock'); ?>:</label></th>
                                            <td style="padding-top:7px;"><input id="alternative_affiliate_id" class="regular-text" type="text" size="45" value="<?php echo $alternative_affiliate_id; ?>" name="alternative_affiliate_id"  style="width:150px;"/>
                                                 <span class="description"><?php _e('Type your Referrer Code', 'shutterstock');?>.</span>
                                            </td>
                                        </tr>


                                    </tbody>
                                </table>

                        </div> </div> </div>


                        <div class='meta-box' >
                          <div <?php $my_close_open_win_id = 'shutterstock_meta_settings_2'; ?>
                              id="<?php echo $my_close_open_win_id; ?>"
                              class="postbox <?php if ( '1' == get_user_option( 'shutterstock_win_' . $my_close_open_win_id ) ) echo 'closed'; ?>" >
                              <div title="<?php _e('Click to toggle','shutterstock'); ?>" class="handlediv"
                                   onclick="javascript:verify_window_opening(<?php $user = wp_get_current_user(); if (isset( $user->ID )){ echo $user->ID;} else {echo '0'; }; ?>, '<?php echo $my_close_open_win_id; ?>');"><br></div>
                                <h3 class='hndle'><span><?php _e('Configuration', 'shutterstock'); ?></span></h3> <div class="inside">


                                <table class="form-table"><tbody>

                                        <tr valign="top">
                                            <th scope="row"><label for="image_place" ><?php _e('Show images', 'shutterstock'); ?>:</label></th>
                                            <td>
                                                <select id="image_place" name="image_place" style="width:150px;">
                                                    <option <?php if($image_place == 'replace') echo "selected"; ?> value="replace"><?php _e('in a place', 'shutterstock');?></option>
                                                    <option <?php if($image_place == 'above') echo "selected"; ?> value="above"><?php _e('above', 'shutterstock');?></option>
                                                    <option <?php if($image_place == 'below') echo "selected"; ?> value="below"><?php _e('below', 'shutterstock');?></option>
                                                </select>&nbsp;
                                                <span class="description" style="font-weight:bold;"><?php _e('of search results', 'shutterstock');?>.</span>&nbsp;&nbsp;
                                                <span class="description"><?php _e('Select your default type of showing search results', 'shutterstock');?>.</span>
                                            </td>
                                        </tr>


                                        <tr valign="top">
                                            <th scope="row"><label for="search_container_id" ><?php _e('ID of search container', 'shutterstock'); ?>:</label></th>
                                            <td style="padding-top:7px;"><input id="search_container_id" class="regular-text" type="text" size="45" value="<?php echo $search_container_id; ?>" name="search_container_id"   style="width:150px;" />
                                                 <span class="description"><?php _e('Type HTML ID of search container, where is search results.', 'shutterstock');?>.</span>
                                            </td>
                                        </tr>
                                        <?php /** ?>
                                        <tr valign="top">
                                            <th scope="row"><label for="shutterstock_safesearch" ><?php _e('Safe searh', 'shutterstock'); ?>:</label></th>
                                            <td><input id="shutterstock_safesearch" type="checkbox" <?php if ($shutterstock_safesearch == 'On') echo "checked"; ?>  value="<?php echo $shutterstock_safesearch; ?>" name="shutterstock_safesearch"/>
                                                <span class="description"><?php _e('Check it if you want to use safe search. Only images suitable for all ages should be returned', 'shutterstock');?></span>
                                            </td>
                                        </tr>
                                        <?php /**/ ?>

                                        <tr valign="top">
                                            <th scope="row"><label for="image_sort_type" ><?php _e('Sort the image results', 'shutterstock'); ?>:</label></th>
                                            <td>
                                                <select id="image_sort_type" name="image_sort_type" style="width:150px;">
                                                    <option <?php if($image_sort_type == 'popular') echo "selected"; ?> value="popular"><?php _e('popular', 'shutterstock');?></option>
                                                    <option <?php if($image_sort_type == 'newest') echo "selected"; ?> value="newest"><?php _e('newest', 'shutterstock');?></option>
                                                    <option <?php if($image_sort_type == 'oldest') echo "selected"; ?> value="oldest"><?php _e('oldest', 'shutterstock');?></option>
                                                    <option <?php if($image_sort_type == 'random') echo "selected"; ?> value="random"><?php _e('random', 'shutterstock');?></option>
                                                </select>
                                                <span class="description"><?php _e('Select your default type sort image results', 'shutterstock');?>.</span>
                                            </td>
                                        </tr>


                                        <tr valign="top">
                                            <th scope="row"><label for="search_withing" ><?php _e('Search images withing', 'shutterstock'); ?>:</label></th>
                                            <td>
                                                <select id="search_withing" name="search_withing" style="width:150px;">
                                                    <option <?php if($search_withing == 'all') echo "selected"; ?> value="all"><?php _e('all', 'shutterstock');?></option>
                                                    <option <?php if($search_withing == 'photos') echo "selected"; ?> value="photos"><?php _e('photos', 'shutterstock');?></option>
                                                    <option <?php if($search_withing == 'illustrations') echo "selected"; ?> value="illustrations"><?php _e('illustrations', 'shutterstock');?></option>
                                                    <option <?php if($search_withing == 'vectors') echo "selected"; ?> value="vectors"><?php _e('vectors', 'shutterstock');?></option>
                                                </select>
                                                <span class="description"><?php _e('Select a media type to search within', 'shutterstock');?>.</span>
                                            </td>
                                        </tr>


                                        <tr valign="top">
                                            <th scope="row"><label for="number_per_page" ><?php _e('Number of images per page', 'shutterstock'); ?>:</label></th>
                                            <td>
                                                <select  id="number_per_page" name="number_per_page" style="width:150px;">
                                                    <option <?php if($number_per_page == 'row') echo "selected"; ?> value="row"><?php _e('One row','shutterstock');?></option>
                                                    <option <?php if($number_per_page == '3') echo "selected"; ?> value="3">3</option>
                                                    <option <?php if($number_per_page == '5') echo "selected"; ?> value="5">5</option>
                                                    <option <?php if($number_per_page == '6') echo "selected"; ?> value="6">6</option>
                                                    <option <?php if($number_per_page == '10') echo "selected"; ?> value="10">10</option>
                                                    <option <?php if($number_per_page == '15') echo "selected"; ?> value="15">15</option>
                                                    <option <?php if($number_per_page == '25') echo "selected"; ?> value="25">25</option>
                                                    <option <?php if($number_per_page == '50') echo "selected"; ?> value="50">50</option>
                                                    <option <?php if($number_per_page == '75') echo "selected"; ?> value="75">75</option>
                                                    <option <?php if($number_per_page == '150') echo "selected"; ?> value="150">150</option>
                                                </select>
                                                <span class="description"><?php _e('Select a number of images per page', 'shutterstock');?>.</span>
                                            </td>
                                        </tr>


                                        <tr valign="top">
                                            <th scope="row"><label for="thumbnails_size" ><?php _e('Thumbnails size', 'shutterstock'); ?>:</label></th>
                                            <td>
                                                <select id="thumbnails_size" name="thumbnails_size" style="width:150px;">
                                                    <option <?php if($thumbnails_size == 'small') echo "selected"; ?> value="small"><?php _e('small', 'shutterstock');?></option>
                                                    <option <?php if($thumbnails_size == 'large') echo "selected"; ?> value="large"><?php _e('large', 'shutterstock');?></option>
                                                </select>
                                                <span class="description"><?php _e('Select a media type to search within', 'shutterstock');?>.</span>
                                            </td>
                                        </tr>


                                        <tr valign="top">
                                            <th scope="row"><label for="shutterstock_is_show_search_field_in_detail" ><?php _e('Show search field', 'shutterstock'); ?>:</label></th>
                                            <td><input id="shutterstock_is_show_search_field_in_detail" type="checkbox" <?php if ($shutterstock_is_show_search_field_in_detail == 'On') echo "checked"; ?>  value="<?php echo $shutterstock_is_show_search_field_in_detail; ?>" name="shutterstock_is_show_search_field_in_detail"/>
                                                <span class="description"><?php _e('Check it if you want to show search field results on details image page', 'shutterstock');?></span>
                                            </td>
                                        </tr>

                                        <tr valign="top">
                                            <th scope="row"><label for="shutterstock_is_show_img_caption" ><?php _e('Show image caption', 'shutterstock'); ?>:</label></th>
                                            <td><input id="shutterstock_is_show_img_caption" type="checkbox" <?php if ($shutterstock_is_show_img_caption == 'On') echo "checked"; ?>  value="<?php echo $shutterstock_is_show_img_caption; ?>" name="shutterstock_is_show_img_caption"/>
                                                <span class="description"><?php _e('Check it if you want to show captions of images in search results', 'shutterstock');?></span>
                                            </td>
                                        </tr>
                                                                        <tr valign="top">
                                            <th scope="row"><label for="shutterstock_is_show_buttons" ><?php _e('Show buttons', 'shutterstock'); ?>:</label></th>
                                            <td><input id="shutterstock_is_show_buttons" type="checkbox" <?php if ($shutterstock_is_show_buttons == 'On') echo "checked"; ?>  value="<?php echo $shutterstock_is_show_buttons; ?>" name="shutterstock_is_show_buttons"/>
                                                <span class="description"><?php _e('Check it if you want to show buttons below the images in search results', 'shutterstock');?></span>
                                            </td>
                                        </tr>

                                                                        <tr valign="top">
                                            <th scope="row"><label for="shutterstock_is_show_box" ><?php _e('Show box', 'shutterstock'); ?>:</label></th>
                                            <td><input id="shutterstock_is_show_box" type="checkbox" <?php if ($shutterstock_is_show_box == 'On') echo "checked"; ?>  value="<?php echo $shutterstock_is_show_box; ?>" name="shutterstock_is_show_box"/>
                                                <span class="description"><?php _e('Check it if you want to show boxes around the search results', 'shutterstock');?></span>
                                            </td>
                                        </tr>

                                                                        <tr valign="top">
                                            <th scope="row"><label for="shutterstock_is_show_header" ><?php _e('Show header', 'shutterstock'); ?>:</label></th>
                                            <td><input id="shutterstock_is_show_header" type="checkbox" <?php if ($shutterstock_is_show_header == 'On') echo "checked"; ?>  value="<?php echo $shutterstock_is_show_header; ?>" name="shutterstock_is_show_header"/>
                                                <span class="description"><?php _e('Check it if you want to show header at the search results', 'shutterstock');?></span>
                                            </td>
                                        </tr>


                                                                        <tr valign="top">
                                            <th scope="row"><label for="shutterstock_is_scroll" ><?php _e('Auto Scroll On/Off', 'shutterstock'); ?>:</label></th>
                                            <td><input id="shutterstock_is_scroll" type="checkbox" <?php if ($shutterstock_is_scroll == 'On') echo "checked"; ?>  value="<?php echo $shutterstock_is_scroll; ?>" name="shutterstock_is_scroll"/>
                                                <span class="description"><?php _e('Check it if you want to set Auto Scroll', 'shutterstock');?></span>
                                            </td>
                                        </tr>


                                        <tr valign="top">
                                            <th scope="row"><label for="shutterstock_open_link_in" ><?php _e('Open Shutterstock page', 'shutterstock'); ?>:</label></th>
                                            <td>
                                                <select id="shutterstock_open_link_in" name="shutterstock_open_link_in" style="width:150px;">
                                                    <option <?php if($shutterstock_open_link_in == '_self') echo "selected"; ?> value="_self"><?php _e('at same page', 'shutterstock');?></option>
                                                    <option <?php if($shutterstock_open_link_in == '_blank') echo "selected"; ?> value="_blank"><?php _e('at new page', 'shutterstock');?></option>
                                                </select>
                                                <span class="description"><?php _e('Select, where to open Shutterstock outgoing links', 'shutterstock');?>.</span>
                                            </td>
                                        </tr>




                                        <tr valign="top">
                                            <th scope="row"><label for="shutterstock_theme_color" ><?php _e('Theme color', 'shutterstock'); ?>:</label></th>
                                            <td>
                                                <select  id="shutterstock_theme_color" name="shutterstock_theme_color" style="width:150px;float:left;margin:3px 10px 0px 0px;"
                                                         onchange="javascript:if(this.value == 'custom') {document.getElementById('shutterstock_theme_color_custom').style.display='block'; document.getElementById('shutterstock_theme_color_box').style.display='none';} else {document.getElementById('shutterstock_theme_color_custom').style.display='none'; /*document.getElementById('shutterstock_theme_color_box').style.backgroundColor='#'+this.options[selectedIndex].value;/**/  document.getElementById('shutterstock_theme_color_box').setAttribute('class', 'shutterstock_pages_active col'+this.options[selectedIndex].value);  document.getElementById('shutterstock_theme_color_box').style.display='block'; } " >
                                                    <option value=""><?php _e('Please Select', 'shutterstock'); ?></option>
                                                    <option <?php if($shutterstock_theme_color == '609030') echo "selected"; ?> value="609030"><?php _e('Shutterstock Green', 'shutterstock'); ?></option>
                                                    <option <?php if($shutterstock_theme_color == '00650f') echo "selected"; ?> value="00650f"><?php _e('Dark-Green', 'shutterstock'); ?></option>
                                                    <option <?php if($shutterstock_theme_color == '007D9A') echo "selected"; ?> value="007D9A"><?php _e('Light-Blue', 'shutterstock'); ?></option>
                                                    <option <?php if($shutterstock_theme_color == '184e89') echo "selected"; ?> value="184e89"><?php _e('Deep-Blue', 'shutterstock'); ?></option>
                                                    <option <?php if($shutterstock_theme_color == '630030') echo "selected"; ?> value="630030"><?php _e('Magenta', 'shutterstock'); ?></option>
                                                    <option <?php if($shutterstock_theme_color == '872300') echo "selected"; ?> value="872300"><?php _e('Red', 'shutterstock'); ?></option>
                                                    <option <?php if($shutterstock_theme_color == 'D45500') echo "selected"; ?> value="D45500"><?php _e('Orange', 'shutterstock'); ?></option>
                                                    <option <?php if($shutterstock_theme_color == 'e8871f') echo "selected"; ?> value="e8871f"><?php _e('Yellow', 'shutterstock'); ?></option>
                                                    <option <?php if($shutterstock_theme_color == '555') echo "selected"; ?> value="555"><?php _e('Dark-Grey', 'shutterstock'); ?></option>
                                                    <option <?php if($shutterstock_theme_color == 'custom') echo "selected"; ?> value="custom"><?php _e('Custom', 'shutterstock'); ?></option>
                                                </select>

                                                <input class="color"  id="shutterstock_theme_color_custom" name="shutterstock_theme_color_custom" value="<?php echo $shutterstock_theme_color_custom; ?>"
                                                     style="float:left;margin-right:10px; width:140px !important;height:22px !important;padding:0px !important;<?php if ($shutterstock_theme_color != 'custom')  { echo "display:none;";} ?>  "
                                                       />
                                                <?php
                                                    $shutterstock_theme_color_class = 'col' . $shutterstock_theme_color;
                                                ?>

                                                       <div id="shutterstock_theme_color_box" class="shutterstock_pages_active <?php echo $shutterstock_theme_color_class; ?>" style="height:28px;width:28px; float:left;margin:0 10px 0 0;padding:0px; <?php if ($shutterstock_theme_color == 'custom')  { echo "display:none;";} else { /*echo "background-color:#".$shutterstock_theme_color.";";/**/} ?> "></div>

                                                <span class="description"><?php _e('Select a color of the theme at search results', 'shutterstock');?>.</span>
                                            </td>
                                        </tr>

                                        <?php  // Ajax loader ?>
                                         <tr valign="top">
                                            <th scope="row"><label for="shutterstock_loader_color" ><?php _e('Loader color', 'shutterstock'); ?>:</label></th>
                                            <td>
                                                <select  id="shutterstock_loader_color" name="shutterstock_loader_color" style="width:150px;float:left;margin:3px 10px 0px 0px;"
                                                         onchange="javascript:document.getElementById('shutterstock_loader_color_img').style.backgroundImage='url(<?php echo WPDEV_SHUTTER_PLUGIN_URL; ?>/img/image_'+this.options[selectedIndex].value+'.gif)'; document.getElementById('shutterstock_loader_color_img').style.display='block';" >
                                                    <option value=""><?php _e('Please Select', 'shutterstock'); ?></option>
                                                    <option <?php if($shutterstock_loader_color == '93904') echo "selected"; ?> value="93904"><?php _e('Shutterstock Green', 'shutterstock'); ?></option>
                                                    <option <?php if($shutterstock_loader_color == '93906') echo "selected"; ?> value="93906"><?php _e('Dark-Green', 'shutterstock'); ?></option>
                                                    <option <?php if($shutterstock_loader_color == '93907') echo "selected"; ?> value="93907"><?php _e('Light-Blue', 'shutterstock'); ?></option>
                                                    <option <?php if($shutterstock_loader_color == '93909') echo "selected"; ?> value="93909"><?php _e('Deep-Blue', 'shutterstock'); ?></option>
                                                    <option <?php if($shutterstock_loader_color == '93911') echo "selected"; ?> value="93911"><?php _e('Magenta', 'shutterstock'); ?></option>
                                                    <option <?php if($shutterstock_loader_color == '93910') echo "selected"; ?> value="93910"><?php _e('Red', 'shutterstock'); ?></option>                                            											<option <?php if($shutterstock_loader_color == '93912') echo "selected"; ?> value="93912"><?php _e('Orange', 'shutterstock'); ?></option>
                                                    <option <?php if($shutterstock_loader_color == '93913') echo "selected"; ?> value="93913"><?php _e('Yellow', 'shutterstock'); ?></option>
                                                    <option <?php if($shutterstock_loader_color == '93914') echo "selected"; ?> value="93914"><?php _e('Black', 'shutterstock'); ?></option>
                                                    <option <?php if($shutterstock_loader_color == '93915') echo "selected"; ?> value="93915"><?php _e('White', 'shutterstock'); ?></option>
                                                </select>
                                                <div id="shutterstock_loader_color_img" style="float:left; <?php if (isset($shutterstock_loader_color)) { echo "display:block;";} else { echo "display:none;";} ?> width:28px; height:28px; margin-right:10px; background-image: url(<?php echo WPDEV_SHUTTER_PLUGIN_URL; ?>/img/image_<?php echo $shutterstock_loader_color; ?>.gif);"></div>

                                                <span class="description"><?php _e('Select a color of the ajax loader at search results', 'shutterstock');?>.</span>
                                            </td>
                                        </tr>

                                </table>

                        </div> </div> </div>

                    </div>
                    <div style="float:left;width:28%">

                        <div class='meta-box'>
                          <div <?php $my_close_open_win_id = 'shutterstock_meta_settings_3'; ?>
                              id="<?php echo $my_close_open_win_id; ?>"
                              class="postbox <?php if ( '1' == get_user_option( 'shutterstock_win_' . $my_close_open_win_id ) ) echo 'closed'; ?>" >
                              <div title="<?php _e('Click to toggle','shutterstock'); ?>" class="handlediv"
                                   onclick="javascript:verify_window_opening(<?php $user = wp_get_current_user(); if (isset( $user->ID )){ echo $user->ID;} else {echo '0'; }; ?>, '<?php echo $my_close_open_win_id; ?>');"><br></div>
                                <h3 class='hndle'><span><?php _e('Help', 'shutterstock'); ?></span></h3> <div class="inside">

                                    <div>

                                        <?php _e('<p>Do you need help to get this plugin work?</p>
<p><strong>Please check following ressources: </strong></p>
<p>- Visit the <a href="http://www.gutewolke.com/shutterstock/" target="_blank">Shutterstock WordPress Plugin Website<br />
</a>- Check the <a href="http://www.gutewolke.com/shutterstock/installation" target="_blank">detailed installation instruction<br />
</a>- See <a href="http://www.gutewolke.com/shutterstock/how-it-works" target="_blank">how the plugin works<br />
</a>- Find a solution <a href="http://www.gutewolke.com/shutterstock/faqs" target="_blank">in the FAQs<br />
</a>- Finally <a href="http://www.gutewolke.com/shutterstock/contact-support" target="_blank">contact the support team</a></p>', 'shutterstock'); ?>

                                    </div>

                        </div> </div> </div>
                        
                        

                    </div>
                    
                    
                    <div style="float:left;width:28%">

                        <div class='meta-box'>
                          <div <?php $my_close_open_win_id = 'shutterstock_meta_settings_4'; ?>
                              id="<?php echo $my_close_open_win_id; ?>"
                              class="postbox <?php if ( '1' == get_user_option( 'shutterstock_win_' . $my_close_open_win_id ) ) echo 'closed'; ?>" >
                              <div title="<?php _e('Click to toggle','shutterstock'); ?>" class="handlediv"
                                   onclick="javascript:verify_window_opening(<?php $user = wp_get_current_user(); if (isset( $user->ID )){ echo $user->ID;} else {echo '0'; }; ?>, '<?php echo $my_close_open_win_id; ?>');"><br></div>
                                <h3 class='hndle'><span><?php _e('Did you know?', 'shutterstock'); ?></span></h3> <div class="inside">

                                    <div>

                                        <?php _e('<p>You can add Shutterstock images into every post or page on your WordPress Blog, not just on the search results page.</p>
<p><strong>How do i add images into posts or pages?</strong></p>
<p>It is very easy, all you need to do is to add the following Shortcode into the place where you want to have the images results:<br /><br /><center>[shutterstock keywords="dog cat"]</center><br /><br />Now replace the "dog cat" with the keyword(s) you want. Just keep in mind that the images will get pulled in real time from Shutterstock, so it may slow down the post or page where you are using it at.', 'shutterstock'); ?>

                                    </div>

                        </div> </div> </div>
                        
                        

                    </div>

                    <div class="clear" style="height:20px;"></div>
                    <input class="button-primary" type="submit" value="<?php _e('Save Changes', 'shutterstock'); ?>" name="Submit"  />
                    <div class="clear" style="height:20px;"></div>
                    </form>




                </div>
                <?php 
                }

        // </editor-fold>
        
        // <editor-fold defaultstate="collapsed" desc="  J S    &    C S S   ">

                // Print     J a v a S cr i p t   &    C S S    scripts for admin and client side.
                function print_js_css($is_admin =1 ) {

                    if (! ( $is_admin))  wp_print_scripts('jquery');
                    // wp_print_scripts('jquery-ui-core');

                    //   J a v a S c r i p t
                    ?> <!-- Start Shutterstock Scripts --> <?php
                    ?> <script  type="text/javascript">
                            var jShutterDev = jQuery.noConflict();
                            var wpdev_shutter_plugin_url = '<?php echo WPDEV_SHUTTER_PLUGIN_URL; ?>';
                            var wpdev_shutter_plugin_filename = '<?php echo WPDEV_SHUTTER_PLUGIN_FILENAME ; ?>';
                            <?php $shutterstock_is_scroll = get_option( 'shutterstock_is_scroll' );
                            if ($shutterstock_is_scroll == 'Off') {
                                echo " var shutterstock_is_scroll = false; ";
                            } else {
                                echo " var shutterstock_is_scroll = true; ";
                            }
                            ?>
                            var wpdev_shutter_loader_img = 'ajax-loader.gif';
                            <?php
                                   $img_color = get_option('shutterstock_loader_color');
                                   if (! empty($img_color))
                                    echo " wpdev_shutter_loader_img = 'image_".$img_color.".gif'; ";
                            ?>
                        <?php if ( ( ! $is_admin ) && ( isset($_GET['s']) ) ) { ?>

                            jShutterDev(document).ready(function(){

                                <?php $search_container_id = get_option( 'shutterstock_search_container_id' ); ?>
                                var my_img_containter = '<?php echo $search_container_id; ?>' ;

                                if (document.getElementById( my_img_containter ) != null ) {
                                    my_img_containter = '#' + my_img_containter ;
                                } else if (document.getElementById('content') != null ) {
                                    my_img_containter = '#content';
                                } else {
                                    my_img_containter = '.entry-content';
                                }

                                if ( document.getElementById('shutter_content_bottom') == null ) { // If we already do not have here shortcode then proceed
                                    
                                    <?php $image_place = get_option( 'shutterstock_image_place' ); ?>
                                    <?php if ($image_place == 'above') { ?>
                                        jShutterDev(my_img_containter).html('<div class="shutter_content" id="shutter_content_bottom"><div id="ajax_working"></div><div id="ajax_respond_insert"></div><div id="ajax_image_details_insert"></div></div>'
                                                                                + jShutterDev(my_img_containter).html()
                                                                               );
                                    <?php } elseif ($image_place == 'below') { ?>
                                        jShutterDev(my_img_containter).html( jShutterDev(my_img_containter+'').html()  +
                                                                             '<div class="shutter_content" id="shutter_content_bottom"><div id="ajax_working"></div><div id="ajax_respond_insert"></div><div id="ajax_image_details_insert"></div></div>'
                                                                           );
                                    <?php } else { // replace ?>
                                        jShutterDev(my_img_containter).html('<div class="shutter_content" id="shutter_content_bottom"><div id="ajax_working"></div><div id="ajax_respond_insert"></div><div id="ajax_image_details_insert"></div></div>');
                                    <?php } ?>

                                    ajaxSearch('<?php echo $_GET['s']; ?>', 1, '<?php echo get_option( 'shutterstock_sort_method' ); ?>');
                                }

                            });

                        <?php } ?>

                        
                        </script> <?php

                    do_action('wpdev_shutterstock_js_define_variables');

                     ?> <script type="text/javascript" src="<?php echo WPDEV_SHUTTER_PLUGIN_URL; ?>/js/shutterstock.js"></script> <?php /**/
                     if ( $is_admin ) { ?> <script type="text/javascript" src="<?php echo WPDEV_SHUTTER_PLUGIN_URL; ?>/js/jscolor/jscolor.js"></script> <?php }
                     
                    do_action('wpdev_shutterstock_js_write_files');

                    ?> <!-- End Shutterstock Scripts --> <?php

                    //    C S S
                    if ($is_admin) { ?> <link href="<?php echo WPDEV_SHUTTER_PLUGIN_URL; ?>/css/admin.css"  rel="stylesheet" type="text/css" /> <?php }
                               { ?> <link href="<?php echo WPDEV_SHUTTER_PLUGIN_URL; ?>/css/client.css" rel="stylesheet" type="text/css" /> <?php }

                    if ( (! $is_admin) && ( get_option( 'shutterstock_theme_color') == 'custom') ){

                    // Convert to darker color for mouse over buttons
                    $my_rgb_array = shutter_stock_html2rgb(get_option( 'shutterstock_theme_color_custom'));
                    $my_rgb_array[0] = $my_rgb_array[0]-20; if ($my_rgb_array[0] < 0 ) { $my_rgb_array[0] = 0 ;}
                    $my_rgb_array[1] = $my_rgb_array[1]-20; if ($my_rgb_array[1] < 0 ) { $my_rgb_array[1] = 0 ;}
                    $my_rgb_array[2] = $my_rgb_array[2]-20; if ($my_rgb_array[2] < 0 ) { $my_rgb_array[2] = 0 ;}
                    $my_rgb_hex = shutter_stock_rgb2html($my_rgb_array[0],$my_rgb_array[1],$my_rgb_array[2]);

                    //debuge($my_rgb_hex, $my_rgb_array);
                    ?>
                    <style type="text/css">

                        .colcustom.shutterstock-awesome, .colcustom.shutterstock-awesome:visited { <?php echo 'background-color: #', get_option( 'shutterstock_theme_color_custom'), '!important;'; ?> }
                        .colcustom.shutterstock-awesome:hover { <?php echo 'background-color: ', $my_rgb_hex , '!important;'; ?> }
                            .colcustom.shutterstock_img_container, .colcustom#shutterstock_searchform, .colcustom.shutterstock_pages_container a, .colcustom.shutterstock_keywords, #ajax_image_details_insert .colcustom.shutterstock_detail_table td, #ajax_image_details_insert .colcustom.shutterstock_detail_table th {<?php echo 'border-color: ', $my_rgb_hex , '!important;'; ?>}
                            .colcustom.shutterstock_pages_active {<?php echo 'border-color: ', $my_rgb_hex , '!important; background-color: ', $my_rgb_hex , '!important;'; ?>}
                            .colcustom.shutterstock_searchform {<?php echo 'border-color: ', $my_rgb_hex , '!important'; ?>}
               		</style>
                    <?php
                    }
                }

        // </editor-fold>


        // <editor-fold defaultstate="collapsed" desc="  S H O R T C O D E S   &   H O O K S   ">

                // Short code  [shutterstock keywords="dog cat"] - for search images inside of posts or pages
                function shutterstock_shortcode($attr) {

                    $return_string = $keywords = '';


                    if ( isset( $attr['keywords'] ) ) { $keywords = $attr['keywords'];  }


                    $return_string = '<div class="shutter_content" id="shutter_content_bottom"><div id="ajax_working"></div><div id="ajax_respond_insert"></div><div id="ajax_image_details_insert"></div></div>';
                    $return_string .= '<script  type="text/javascript"> ';
                    $return_string .= '        var jShutterDev = jQuery.noConflict();  ';
                    $return_string .= '        var wpdev_shutter_plugin_url = "' . WPDEV_SHUTTER_PLUGIN_URL .'";  ';
                    $return_string .= '        var wpdev_shutter_plugin_filename = "' . WPDEV_SHUTTER_PLUGIN_FILENAME .'";  ';


                    $return_string .= '        jShutterDev(document).ready(function(){ ';
                    $return_string .= '            ajaxSearch("'. $keywords .'", 1, "'. get_option( 'shutterstock_sort_method' ) .'"); ';
                    $return_string .= '        });  ';

                    $return_string .= ' </script>  ';

                    return $return_string;

                }


                // Execution search, if was setted HOOK do_action('shutterstock_search');  for search action inside of search.php file
                function shutterstock_in_search_action(){

                    $keywords = '';
                    if ( isset( $_GET['s'] ) ) { $keywords = $_GET['s'];  }

                    ?>
                    <div class="shutter_content" id="shutter_content_bottom"><div id="ajax_working"></div><div id="ajax_respond_insert"></div><div id="ajax_image_details_insert"></div></div>
                    <script  type="text/javascript">
                            var jShutterDev = jQuery.noConflict();
                            var wpdev_shutter_plugin_url = "<?php echo WPDEV_SHUTTER_PLUGIN_URL; ?>";
                            var wpdev_shutter_plugin_filename = "<?php echo WPDEV_SHUTTER_PLUGIN_FILENAME; ?>";

                            jShutterDev(document).ready(function(){
                                ajaxSearch("<?php echo $keywords; ?>", 1, "<?php echo get_option( 'shutterstock_sort_method' ); ?>");
                            });
                     </script>
                    <?php
                }

        // </editor-fold>


        // <editor-fold defaultstate="collapsed" desc="  ACTIVATE & DEACTIVATE   ">
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // ACTIVATION & DEACTIVATION  /////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // Activation of script
        function wpdev_shutter_activate() {

              add_option('shutterstock_login'   , '');
              add_option('shutterstock_pasword' , '');
              add_option( 'shutterstock_alternative_affiliate_id' , '' );

              add_option( 'shutterstock_sort_method' , 'popular' );
              add_option( 'shutterstock_search_group' , 'all' );
              add_option( 'shutterstock_safesearch', 'On' );
                  add_option( 'shutterstock_theme_color_custom' ,'' );

              add_option( 'shutterstock_is_show_img_caption', 'Off' );
              add_option( 'shutterstock_is_show_buttons', 'On' );
              add_option( 'shutterstock_is_show_search_field_in_detail', 'On' );
              add_option( 'shutterstock_is_show_box', 'Off' );
              add_option( 'shutterstock_is_scroll', 'On' );
                  add_option( 'shutterstock_is_show_header', 'On' );

              add_option( 'shutterstock_open_link_in', '_self');

              add_option( 'shutterstock_search_container_id' , 'content' );
              add_option( 'shutterstock_image_place' , 'replace' );
              add_option( 'shutterstock_num_per_page' , '6' );
              add_option( 'shutterstock_thumbnails_size' , 'large' );
              add_option( 'shutterstock_theme_color','609030');
              add_option( 'shutterstock_loader_color','93904');
        }


        // Deactivation of script
        function wpdev_shutter_deactivate() {

                delete_option('shutterstock_login'   , '');
                delete_option('shutterstock_pasword' , '');

                delete_option( 'shutterstock_sort_method' );
                delete_option( 'shutterstock_search_group' );
                delete_option( 'shutterstock_safesearch' );
                delete_option( 'shutterstock_theme_color_custom' );

                delete_option( 'shutterstock_is_show_img_caption' );
                delete_option( 'shutterstock_is_show_buttons' );
                delete_option( 'shutterstock_is_show_search_field_in_detail' );
                delete_option( 'shutterstock_is_show_box' );
                delete_option( 'shutterstock_is_scroll' );
                delete_option( 'shutterstock_is_show_header' );

                delete_option( 'shutterstock_open_link_in');

                delete_option( 'shutterstock_search_container_id' );
                delete_option( 'shutterstock_image_place' );
                delete_option( 'shutterstock_num_per_page' );
                delete_option( 'shutterstock_thumbnails_size' );
                delete_option( 'shutterstock_alternative_affiliate_id' );
                delete_option( 'shutterstock_theme_color');
                delete_option( 'shutterstock_loader_color');
        }
        // </editor-fold>

    }
}

?>