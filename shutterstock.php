<?php
/*
Plugin Name: Shutterstock Affiliate Plugin
Plugin URI: http://www.gutewolke.com/shutterstock
Description: The Shutterstock Affiliate Plugin is designed to help affiliates earn money by introducing new customers to Shutterstock. Use this plugin to easily show search results from Shutterstock Images on a WordPress search results page, or on any post or page within your Wordpress site. When customers follow links from those images and make a purchase at Shutterstock, you’ll earn highly competitive, 20% commissions—up to $200 per successful referral. You must be a member of the <a href="http://affiliate.shutterstock.com/" title="Shutterstock Affiliate Program" target="_blank">Shutterstock Affiliate program</a> and have a Shutterstock API key to use this plugin.

Version: 1.0
Author: Shutterstock, GuteWolke and wpdevelop
Author URI: http://www.gutewolke.com
Tested WordPress Versions: 3.1
*/


// <editor-fold defaultstate="collapsed" desc=" T O D O : & Changelog lists ">
/*
-----------------------------------------------
T O D O   List:
-----------------------------------------------

1. Creation of plugin framework with interfaces - 2 days.
2. Creation settings page (only saving info and web page at WordPress admin panel) - 1 day
3. Connection to API from settings page - 4 hours
4. Frontend search page hooks, for integration of search results - 4 hours (We need to define at search.php file some elements, which is exist by default, its mean that we are know for sure that some div with specific ID is exist there, or we need to define at settings page the id of this element, otherwise its not very clear will be where is insert search images results !!! )
5. Search requests to plugin API with diferent parameters  - 1 day
6. Connection point 5. to point 4 - showing search results at search page. - 2 days
7. Showing of selected image page - 2 days  (is it have to be new page or ajax showing at the same page ?)
8. Showing search results for new shortcodes inside of posts or pages - 1 day

( 1 day = 8 hours )


Frontend on Wordpress Blog or Page

    * Show Shutterstock image search results using the standard Wordpress search box (No need of adding the Code manually into the Search.php - this must work automatically)
    * Show the images in addition to, or in place of, the regular Wordpress search results 
    * When shown in addition to regular search results, place the images above or below (Editable in Settings)
    * Sort the image results (by sorting like the API gives you)
    * Show real-time search results (Pull via Ajax from API)  Where Ajax based search results have to be ?
    * Include the Shutterstock image hover on the search results pages 
    * Provide a direct buy option at the results page with affiliate link of the WordPress Blogger - it will forward the user to the detail page of the image (or will add image to basket at the Shutterstock Website)  How the affilitae link is look like ?
    * Show the image detail page with prices and information regarding the licence etc. (like we already have it in Fotolia)
    * Show image search results directly within a blog post (with the [keyword= ] option)


Backend in Wordpress Admin

Use the same design from the Microstock Photo Plugin for the Admin page.

    * User can select image types to show: photos, vectors, illustrations, textures, all types or a combination of types (Checkboxes)
    * User can choose the number of images which are shown per page Main Features 
    * User can choose to show large or small thumbnails 
    * User can add his own API Key to monitor the results and automatically generates the affiliate (or use a standard API Key)links on all the photos/illustrations 
    * The plugin uses standard style definitions (h1, h2, etc) to automatically match the style of the blog's own template - (Do not use own CSS for the plugin which overrides the standard CSS if possible)

 * ---------------------------------------------------------------------------------------------------------------------------------------
*/
// </editor-fold>

    // Die if direct access to file
    if (   (! isset( $_POST['ajax_shutter_action'] ) ) && (!function_exists ('get_option')  )     ) {
        die('You do not have permission to direct access to this file !!!');
    }

    // A J A X /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if ( ( isset( $_POST['ajax_shutter_action'] ) )   ) {
        require_once( dirname(__FILE__) . '/../../../wp-load.php' );
        @header('Content-Type: text/html; charset=' . get_option('blog_charset'));
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //   D e f i n e     S T A T I C              //////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if (!defined('WP_SHUTTER_DEBUG_MODE'))    define('WP_SHUTTER_DEBUG_MODE',  false );
    if (!defined('WPDEV_SHUTTER_FILE'))       define('WPDEV_SHUTTER_FILE',  __FILE__ );

    if (!defined('WP_CONTENT_DIR'))      define('WP_CONTENT_DIR', ABSPATH . 'wp-content');                   // Z:\home\test.wpdevelop.com\www/wp-content
    if (!defined('WP_CONTENT_URL'))      define('WP_CONTENT_URL', site_url() . '/wp-content');    // http://test.wpdevelop.com/wp-content
    if (!defined('WP_PLUGIN_DIR'))       define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');               // Z:\home\test.wpdevelop.com\www/wp-content/plugins
    if (!defined('WP_PLUGIN_URL'))       define('WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins');               // http://test.wpdevelop.com/wp-content/plugins
    if (!defined('WPDEV_SHUTTER_PLUGIN_FILENAME'))  define('WPDEV_SHUTTER_PLUGIN_FILENAME',  basename( __FILE__ ) );              // menu-compouser.php
    if (!defined('WPDEV_SHUTTER_PLUGIN_DIRNAME'))   define('WPDEV_SHUTTER_PLUGIN_DIRNAME',  plugin_basename(dirname(__FILE__)) ); // menu-compouser
    if (!defined('WPDEV_SHUTTER_PLUGIN_DIR')) define('WPDEV_SHUTTER_PLUGIN_DIR', WP_PLUGIN_DIR.'/'.WPDEV_SHUTTER_PLUGIN_DIRNAME ); // Z:\home\test.wpdevelop.com\www/wp-content/plugins/menu-compouser
    if (!defined('WPDEV_SHUTTER_PLUGIN_URL')) define('WPDEV_SHUTTER_PLUGIN_URL', WP_PLUGIN_URL.'/'.WPDEV_SHUTTER_PLUGIN_DIRNAME ); // http://test.wpdevelop.com/wp-content/plugins/menu-compouser


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //   L O A D   F I L E S                      //////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    if (file_exists(WPDEV_SHUTTER_PLUGIN_DIR. '/lib/wpdev-shutter-functions.php')) {     // S u p p o r t    f u n c t i o n s
        require_once(WPDEV_SHUTTER_PLUGIN_DIR. '/lib/wpdev-shutter-functions.php' ); }

    if (file_exists(WPDEV_SHUTTER_PLUGIN_DIR. '/lib/wpdev-shutter-class.php'))           // C L A S S    B o o k i n g
        { require_once(WPDEV_SHUTTER_PLUGIN_DIR. '/lib/wpdev-shutter-class.php' ); }

         
    //  T R A N S L A T I O N S   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if (defined('WP_ADMIN')) if (WP_ADMIN === true) load_SHUTTER_Translation();
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    //    A J A X     R e s p o n d e r     // RUN if Ajax //
    if (file_exists(WPDEV_SHUTTER_PLUGIN_DIR. '/lib/wpdev-shutter-ajax.php'))  { require_once(WPDEV_SHUTTER_PLUGIN_DIR. '/lib/wpdev-shutter-ajax.php' ); }

    // RUN //
    $wpdev_shutter = new wpdev_shutter(); 
?>