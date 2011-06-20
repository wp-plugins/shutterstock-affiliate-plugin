<?php

if ( isset( $_POST['ajax_shutter_action'] ) ) {

    wpdev_shutter_ajax_responder();
    die;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    A J A X     R e s p o n d e r     Real Ajax with jWPDev sender     ///////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function wpdev_shutter_ajax_responder() {

    load_SHUTTER_Translation();
    $action = $_POST['ajax_shutter_action'];

    switch ( $action ) :

        case  'SHOW_SEARCH_IMAGES':

            shutter_show_search_results($_POST['search_term'], $_POST['search_page'], $_POST['sort_metod']);

            break;

        default:

        case 'SHOW_IMAGE_DETAILS':


            shutter_show_image_details($_POST['image_id']);

            break;

        case 'USER_SAVE_WINDOW_STATE':
            update_user_option($_POST['user_id'],'shutterstock_win_' . $_POST['window'] ,$_POST['is_closed']);
            die();
            break;

        endswitch;

}

?>