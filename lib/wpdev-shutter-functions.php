<?php

    if (!function_exists ('debuge')) {
        function debuge() {
            $numargs = func_num_args();
            $var = func_get_args();
            $makeexit = is_bool($var[count($var)-1])?$var[count($var)-1]:false;
            echo "<div style='text-align:left;background:#ffffff;border: 1px dashed #ff9933;font-size:11px;line-height:15px;font-family:'Lucida Grande',Verdana,Arial,'Bitstream Vera Sans',sans-serif;'><pre style='white-space:pre-wrap;font:10px Verdana;line-height:16px;'>";
            print_r ( $var );
            echo "</pre></div>";
            if ($makeexit) {
                echo '<div style="font-size:18px;float:right;">' . get_num_queries(). '/'  . timer_stop(0, 3) . 'qps</div>';
                exit;
            }
        }
    }

function is_shutter_login_correct(){

            $username = get_option('shutterstock_login');
            $password = get_option('shutterstock_pasword');
            $url = 'http://api.shutterstock.com/test/echo.json?is_login=OK';
            // Authorization.
            $context = stream_context_create(array(
                'http' => array(
                    'header'  => "Authorization: Basic " . base64_encode("$username:$password")
                )
            ));
            $output = @file_get_contents($url, false, $context);

            if ($output !== false)  return true;
            else                    return false;
}


function shutter_show_search_results( $search_term_input, $page_number = 1 , $image_sort_type = '' ){
                
// Help -> http://www.gen-x-design.com/archives/create-a-rest-api-with-php/
// http://www.sematopia.com/2006/10/how-to-making-a-php-rest-client-to-call-rest-resources/
// http://api.shutterstock.com/images/search.html?searchterm=cat
// $username = '';
// $password = '';





            $search_term = str_replace('+', ' ', $search_term_input);

            $username = get_option('shutterstock_login');
            $password = get_option('shutterstock_pasword');

            if (empty ($image_sort_type))
                $image_sort_type = get_option( 'shutterstock_sort_method' );
            $search_withing = get_option( 'shutterstock_search_group' );

            // $image_place = get_option( 'shutterstock_image_place' );
            $number_per_page = get_option( 'shutterstock_num_per_page' );
            $thumbnails_size = get_option( 'shutterstock_thumbnails_size' );
            $alternative_affiliate_id = get_option( 'shutterstock_alternative_affiliate_id' );
            $safesearch = 1;
            //if (get_option( 'shutterstock_safesearch' ) == 'Off')   $safesearch = 0;
            //else                                                    $safesearch = 1;

            $shutterstock_is_show_img_caption = get_option( 'shutterstock_is_show_img_caption' );
            if ($shutterstock_is_show_img_caption == 'On') $shutterstock_is_show_img_caption = true;
            else $shutterstock_is_show_img_caption = false;
            
            $shutterstock_is_show_buttons = get_option( 'shutterstock_is_show_buttons' );
            if ($shutterstock_is_show_buttons == 'On') $shutterstock_is_show_buttons = true;
            else $shutterstock_is_show_buttons = false;
            
            $shutterstock_is_show_big_thumbs = get_option( 'shutterstock_thumbnails_size' );
            if ($shutterstock_is_show_big_thumbs == 'large') $shutterstock_is_show_big_thumbs = true;
            else $shutterstock_is_show_big_thumbs = false;
            
            $shutterstock_is_show_box = get_option( 'shutterstock_is_show_box' );
            if ($shutterstock_is_show_box == 'On') $shutterstock_is_show_box = true;
            else $shutterstock_is_show_box = false;

            $shutterstock_is_show_search_field_in_detail = get_option('shutterstock_is_show_search_field_in_detail');
            $shutterstock_open_link_in = get_option('shutterstock_open_link_in'); // _self , _blank
            $shutterstock_theme_color = get_option('shutterstock_theme_color');
            $shutterstock_loader_color = get_option('shutterstock_loader_color');
            $shutterstock_is_show_header = get_option( 'shutterstock_is_show_header' );


            //$number_per_page = 25;
            $page_number_original = $page_number;
            $number_per_page_original = $number_per_page;
            if ($number_per_page_original=='row') $number_per_page =150;
            $page_number = ceil($page_number * $number_per_page / 150 );


            $i_s = ($page_number_original - 1) *  $number_per_page + 1  - ( ($page_number-1)*150  );
            $i_e = ($page_number_original ) *  $number_per_page  - ( ($page_number-1)*150  )       ;
//            debuge('start', $i_s , $i_e);
            /*        :  150
             *
             * P = 7  :  25
             * S = (7-1)*25+1 = 126
             * E = 7*25       = 175
             *
             *
             */

//debuge( '$page_number',   $page_number);
//debuge( '$page_number_original',   $page_number_original);

            // Count Page number
            // $number_per_page - per page
            // 150 - from search default
      
            //$page_number = ceil($page_number / ( 150 / $number_per_page ) );
            $page_number--;


            $request_type = 'json'; //json , html,
            $base = 'http://api.shutterstock.com';
            $base .= '/images/search.' ;
            $base .= $request_type ;

            $query_string = "";
            if (   is_numeric($search_term)   )
                $params = array(
                    'submitter_id'   =>  $search_term ,
                    'page_number'  => $page_number,
                    'sort_method'  => $image_sort_type ,
                    'safesearch' => $safesearch,
                    'search_group' => $search_withing
                );
             else
                $params = array(
                    'searchterm'   =>  $search_term ,
                    'page_number'  => $page_number,
                    'sort_method'  => $image_sort_type ,
                    'safesearch' => $safesearch,
                    'search_group' => $search_withing
                );


//debuge($params);
            foreach ($params as $key => $value) {
                $query_string .= "$key=" . urlencode($value) . "&";
            }

            $url = "$base?$query_string";

            // Authorization.
            $context = stream_context_create(array(
                'http' => array(
                    'header'  => "Authorization: Basic " . base64_encode("$username:$password")
                )
            ));
//debuge($url);
            $output = @file_get_contents($url, false, $context);

            $search_result = json_decode($output);
//debuge($search_result);

/*
    [count] => 362433
    [page] => 1
    [sort_method] => popular
    [searchSrcID] =>

    [results] =>
                    [photo_id] => 46840402
                    [resource_url] => http://api.shutterstock.com/images/46840402
                    [description] => young beautiful woman with ...
                    [web_url] => http://www.shutterstock.com/pic.mhtml?id=46840402
                    [thumb_large] => stdClass Object
                        (
                            [width] => 150
                            [url] => http://thumb18.shutterstock.com/photos/thumb_large/251191/251191,1266365309,3.jpg
                            [height] => 123
                        )

                    [thumb_small] => stdClass Object
                        (
                            [width] => 100
                            [url] => http://thumb18.shutterstock.com/photos/thumb_small/251191/251191,1266365309,3.jpg
                            [img] => http://thumb18.shutterstock.com/photos/thumb_small/251191/251191,1266365309,3.jpg
                            [height] => 82
                        )
/**/
            if (  (isset($search_result->results)) && ( count($search_result->results) > 0 ) ){

                if ($search_withing == 'all') $search_withing_str = __('Stock Photos, Illustrations, Vectors', 'shutterstock');
                else if ($search_withing == 'photos') $search_withing_str = __('Stock Photos', 'shutterstock');
                else if ($search_withing == 'illustrations') $search_withing_str = __('Illustrations', 'shutterstock');
                else if ($search_withing == 'vectors') $search_withing_str = __('Vectors', 'shutterstock');
                if (! empty($alternative_affiliate_id))
                    $purchase_rid = '&rid=' . $alternative_affiliate_id ;
                else $purchase_rid = '';
if ($shutterstock_is_show_header != 'Off') {
                $images_count = number_format($search_result->count);
                echo '<h4>' . __('Found: ', 'shutterstock') . $images_count . ' <b>'. str_replace(' ',', ', $search_term). '</b> ' .$search_withing_str . ' '.__('at', 'shutterstock').' '. '<a href="http://www.shutterstock.com/cat.mhtml?searchterm='.str_replace(' ', '+', $search_term).$purchase_rid.'" target="'.$shutterstock_open_link_in.'" >Shutterstock</a>' . '</h4>';

                if ($shutterstock_is_show_search_field_in_detail == 'On') {
                    ?>  <div class="col<?php echo $shutterstock_theme_color; ?> shutterstock_searchform">
                        <form action="<?php echo site_url(); ?>" id="searchformi" method="get" role="search">
                            <div>
                              <input type="text" id="si" name="s" value="<?php echo $search_term; ?>">
                              <input class="large col<?php echo $shutterstock_theme_color; ?> shutterstock-awesome" type="submit" value="Search"></div>
                            </div>
                        </form>
                        </div>
                    <?php
                }
				
                echo '<div id="shutterstock_image-sort">';

                echo '<div class="shutterstock_select"> ';
                echo '<select id="image_sort_type" name="image_sort_type" style="width:150px;" onchange="javascript:ajaxSearch(\''. $search_term_input . '\', 1 ,  this.value );" >';
                echo '    <option '; if($image_sort_type == 'popular') echo "selected"; echo ' value="popular"> '; _e('popular', 'shutterstock'); echo ' </option>';
                echo '    <option '; if($image_sort_type == 'newest') echo "selected"; echo ' value="newest"> '; _e('newest', 'shutterstock'); echo ' </option>';
                echo '    <option '; if($image_sort_type == 'oldest') echo "selected"; echo ' value="oldest"> '; _e('oldest', 'shutterstock'); echo ' </option>';
                echo '    <option '; if($image_sort_type == 'random') echo "selected"; echo ' value="random"> '; _e('random', 'shutterstock'); echo ' </option>';
                echo '</select></div>';
                echo '<div class="shutterstock_select">'; _e('View','shutterstock'); echo ':</div>';
                echo '</div>';

}
                echo '<div id="shutterstock_top"> </div>';

                if ($number_per_page_original !='row')
                    show_pages_nums( $search_result , $search_term_input , $page_number_original);

                echo '<div class="shutterstock_main_container" ><div class="shutterstock_sub_container" >';
                $my_cnt=0;
                $my_height = 0;
                $my_width = 0;
                $alternative_affiliate_id = get_option( 'shutterstock_alternative_affiliate_id' );
                foreach ($search_result->results as $value) { $my_cnt++;
                    if ($number_per_page_original =='row') {
                        if ($my_cnt>3) break;
                    }

                    if ( ($my_cnt >= $i_s) && ($my_cnt <= $i_e) ) {
                        
                    } else {
                        continue;
                    }

                    $purchase_url = $value->web_url;
                    if (! empty($alternative_affiliate_id))
                        $purchase_url .= '&rid=' . $alternative_affiliate_id ;
                    
                    if ($thumbnails_size == 'small')
                         $my_thumb = $value->thumb_small;
                    else $my_thumb = $value->thumb_large;

                    $thumb_width = $my_thumb->width;
                    if ($thumb_width < 125) $thumb_width = 125;
                    echo '<div class="col'.$shutterstock_theme_color.' shutterstock_img_container">';
                    echo '<div class="shutterstock_img_border"  >';
                        echo '<a title="' . $value->description . '" class="shutterstock_preview_link"  hreflang="'.str_replace('/thumb_large/','/display_pic_with_logo/',$value->thumb_large->url).'" href="javascript:load_shutterstock_details('. $value->photo_id .');">';
$img_style='';
if ( ($my_thumb->width+0) > ($my_thumb->height+0) ) {
    $img_style = ' style="height:'.($my_thumb->height-15).'px; overflow:hidden;margin:0 auto;" ' ;
} else {
   $img_style = ' style="width:'.($my_thumb->width-15).'px; overflow:hidden;margin:0 auto;" ';
}

                            echo '<div '.$img_style.' ><img src="'.$my_thumb->url.'" width="'.$my_thumb->width.'"  /></div>';  // height="'.($my_thumb->height).'"
                        echo '</a><br />';


                    echo '</div>';
                    if ($shutterstock_is_show_img_caption) {
                    echo '<div class="shutterstock_caption">';
                    echo '<span style="clear:both;"/></span>';
                        echo '<a href="'. $purchase_url .'"  target="'.$shutterstock_open_link_in.'"  >';
                            echo '<span class="shutterstock_description">' . $value->description . '</span>';
                        echo '</a>';
                    echo '</div>';
                    }
                    if ($shutterstock_is_show_buttons) {
                    echo '<div class="shutterstock_buttons">';
                    echo '<span style="clear:both;"/></span>';
                        echo '<a href="javascript:load_shutterstock_details('. $value->photo_id .');" class="small col'.$shutterstock_theme_color.' shutterstock-awesome"   >'.__('Details','shutterstock').'</a> ';
                        echo '<a href="'.$purchase_url.'" class="small col'.$shutterstock_theme_color.' shutterstock-awesome"  target="'.$shutterstock_open_link_in.'" >'.__('Buy','shutterstock').'</a>';

//                        echo '<input onclick="javascript:load_shutterstock_details('. $value->photo_id .');" type="button" value="'.__('Details','shutterstock').'" />';
//                        echo '<input onclick="javascript:location.href=\''.$purchase_url.'\';" type="button" value="'.__('Buy','shutterstock').'" />';
                    echo '</div>';
                    }
                    echo '</div>';
                    if ($my_height < $my_thumb->height ) $my_height = $my_thumb->height;
                    if ($my_width < $my_thumb->width ) $my_width = $my_thumb->width;

                }
                echo '</div></div>';

                if ($number_per_page_original !='row')
                    show_pages_nums( $search_result, $search_term_input, $page_number_original );

                echo '<style type="text/css">';
                if ($shutterstock_is_show_img_caption && $shutterstock_is_show_buttons) {
                    echo ' .shutterstock_img_container { height:'.($my_height+75).'px;}';
                    echo ' .shutterstock_img_border    { height:'.($my_height).'px;}';
                } else if ($shutterstock_is_show_img_caption){
                    echo ' .shutterstock_img_container { height:'.($my_height+35).'px;}';
                	echo ' .shutterstock_img_border    { height:'.($my_height).'px;}';
                } else if ($shutterstock_is_show_buttons){
                    echo ' .shutterstock_img_container { height:'.($my_height+25).'px;}';
                	echo ' .shutterstock_img_border    { height:'.($my_height).'px;}';
                } else {
                	echo ' .shutterstock_img_container { height:'.($my_height+10).'px;}';
                	echo ' .shutterstock_img_border    { height:'.($my_height).'px;}';
                }
                if ($shutterstock_is_show_big_thumbs) {
                    echo ' .shutterstock_img_container { width:'.($my_width+20).'px;}';
                    echo ' .shutterstock_img_border    { width:'.($my_width).'px;}';
                } else {
                    echo ' .shutterstock_img_container { width:'.($my_width+20).'px;}';
                	echo ' .shutterstock_img_border    { width:'.($my_width).'px;}';
                }
                if ($shutterstock_is_show_box) {
                    echo ' .shutterstock_img_container { border-style: solid; border-width: 3px;}';
                } else {
                    echo ' .shutterstock_img_container { border-style: none;}';
                    echo ' .shutterstock_pages_container a { border-style: none;}';

                }
             /*  Modify at 15.03 from Mike Moser */
             //   echo '.shutterstock_sub_container{ width:'.(3*$my_width+200).'px;}';
                echo '</style>';
                echo '<script type="text/javascript">';
                echo 'imagePreview();';
                echo 'mytime=setTimeout(\'makeScroll("#shutterstock_top")\',500);';
                echo '</script>';
            } else {
                echo '<h2>' . 'Nothing found ' . '</h2>';
            }


          //  debuge($search_result);
            
}



function shutter_show_image_details( $image_id ){


            $username = get_option('shutterstock_login');
            $password = get_option('shutterstock_pasword');

            $shutterstock_is_show_search_field_in_deatil = get_option('shutterstock_is_show_search_field_in_deatil');
            $shutterstock_open_link_in = get_option('shutterstock_open_link_in'); // _self , _blank
            $shutterstock_theme_color = get_option('shutterstock_theme_color');
            $shutterstock_is_show_box = get_option( 'shutterstock_is_show_box' );
            if ($shutterstock_is_show_box == 'On') $shutterstock_is_show_box = true;
            else $shutterstock_is_show_box = false;

            $request_type = 'json'; //json , html,

            $base = 'http://api.shutterstock.com';
            $base .= '/images/' ;
            $base .= $image_id;
            $base .= '.' . $request_type ;

            $query_string = "";

            $params = array(
                'image_id'   =>  $image_id ,
                'language'  => 'en'
            );

            foreach ($params as $key => $value) {
                $query_string .= "$key=" . urlencode($value) . "&";
            }

            $url = "$base?$query_string";

            // Authorization.
            $context = stream_context_create(array(
                'http' => array(
                    'header'  => "Authorization: Basic " . base64_encode("$username:$password")
                )
            ));
            $output = @file_get_contents($url, false, $context);

            if ($output !== false) {


                $img_detail = json_decode($output);
//debuge($img_detail);
                $alternative_affiliate_id = get_option( 'shutterstock_alternative_affiliate_id' );
                $purchase_url = $img_detail->web_url;
                if (! empty($alternative_affiliate_id))
                    $purchase_url .= '&rid=' . $alternative_affiliate_id ;

 

                echo '<div class="shutterstock_img_details" id="shutterstock_searchform">';

                    if ($shutterstock_is_show_search_field_in_deatil == 'On') {
                        ?>  <div id="shutterstock_searchformi2">
                            <form action="<?php echo site_url(); ?>" id="searchformi2" method="get" role="search">
                                <div>
                                  <input type="text" id="si2" name="s" value="">
                                  <input type="submit" value="Search" id="searchsubmiti2">
                                </div>
                            </form>
                            </div>
                            <script type="text/javascript">
                                document.getElementById('si2').value = document.getElementById('si').value;
                            </script>
                        <?php
                    }

$img_style='';
//if ( ($img_detail->sizes->small->width+0) > ($img_detail->sizes->small->height+0) ) {
    $img_style = ' style="height:'.($img_detail->sizes->small->height-40).'px; overflow:hidden;" ' ;
//} else {
//   $img_style = ' style="width:'.($img_detail->sizes->small->width-30).'px; overflow:hidden;" ';
//}
                    echo '<div id="shutterstock'.$image_id.'"><a href="'.$purchase_url.'"  target="'.$shutterstock_open_link_in.'" ><h3>' . $img_detail->description .
                            //', <a href="javascript:;" onclick="javascript:jShutterDev(\'#ajax_respond_insert\').css(\'display\',\'block\');jShutterDev(\'#ajax_image_details_insert\').css(\'display\',\'none\');ajaxSearch(\''. $img_detail->submitter_id . '\', 1, \'' . get_option( 'shutterstock_sort_method' ) .'\' );" >'.
                            '</h3></a>'.
                            '<div class="shutterstock_author"><h4> &copy; '. $img_detail->submitter .
                            //'</a>'.
                            '</h4></div></div>' ;
echo                            '<div style="text-align:left;"><a href="javascript:;" onclick="javascript:jShutterDev(\'#ajax_respond_insert\').css(\'display\',\'block\');jShutterDev(\'#ajax_image_details_insert\').css(\'display\',\'none\');" class="small col'.$shutterstock_theme_color.' shutterstock-awesome"   >'.__('&laquo; Back','shutterstock').'</a></div> ';

                    echo '<div class="shutterstock_detail-image" '.$img_style.' >'; echo '<a href="'.$purchase_url.'"  target="'.$shutterstock_open_link_in.'" >';
                    echo    '<img src="'. $img_detail->sizes->preview->url.'" />';
                    echo '</a></div>';
                    echo '<br />';
                    
                    echo '<div class="shutterstock_buy-now"><a href="'.$purchase_url.'" class="xlarge col'.$shutterstock_theme_color.' shutterstock-awesome"  target="'.$shutterstock_open_link_in.'" >'.__('Buy now','shutterstock').'</a></div>';

                    //echo '<input onclick="javascript:location.href=\''.$purchase_url.'\';" type="button" value="'.__('Buy','shutterstock').'" />';
                    echo '<br />';
                   
                    $my_keywords = '';
                    foreach ($img_detail->keywords as $value) {
                        if (! empty($value))
                            $my_keywords .= '<a href="'.site_url().'/?s='.$value.'">' .$value . '</a>, ' ;

                    }
                    if (! empty($my_keywords)) {
                        $my_keywords = substr($my_keywords, 0, -2);
						echo '<div class="col'.$shutterstock_theme_color.' shutterstock_keywords">';
                         _e('Keywords','shutterstock'); echo ': ', $my_keywords;
                        echo '</div>';
                    }

                echo '<div style="text-align:left;"><a href="javascript:;" onclick="javascript:jShutterDev(\'#ajax_respond_insert\').css(\'display\',\'block\');jShutterDev(\'#ajax_image_details_insert\').css(\'display\',\'none\');" class="small col'.$shutterstock_theme_color.' shutterstock-awesome"   >'.__('&laquo; Back','shutterstock').'</a></div> ';

                echo '</div>';


                   // echo '<a href="javascript:" class="small '.$shutterstock_theme_color.' shutterstock-awesome"    >'.__('Back','shutterstock').'</a> ';


                //echo '<input onclick="" type="button" value="'.__('Back','shutterstock').'" />';

                ?>
                    <table class="col<?php echo $shutterstock_theme_color; ?> shutterstock_detail_table">
                        <tr> <th valign="middle"  align="center" colspan="3"><?php _e('Sizes','shutterstock') ?></th> </tr>
                        <tr>
                            <td valign="middle"  width="10%"><?php _e('Small','shutterstock'); ?></td>
                            <td valign="middle"   align="center"><?php echo $img_detail->sizes->small->width, 'x',$img_detail->sizes->small->height;  ?>px</td>
                            <td valign="middle"  width="10%"><?php
                                echo '<a href="'.$purchase_url.'" class="small col'.$shutterstock_theme_color.' shutterstock-awesome"  target="'.$shutterstock_open_link_in.'" >'.__('Buy','shutterstock').'</a>';
                                /* <input style="margin:0px;"  onclick="javascript:location.href='<?php echo $purchase_url; ?>';" type="button" value="<?php _e('Buy','shutterstock'); ?>" />*/
                            ?></td>
                        </tr>
                        <tr>
                            <td valign="middle" ><?php _e('Medium','shutterstock'); ?></td>
                            <td valign="middle"  align="center"><?php echo $img_detail->sizes->medium->width, 'x',$img_detail->sizes->medium->height;  ?>px</td>
                            <td valign="middle"  width="10%"><?php
                                echo '<a href="'.$purchase_url.'" class="small col'.$shutterstock_theme_color.' shutterstock-awesome"  target="'.$shutterstock_open_link_in.'" >'.__('Buy','shutterstock').'</a>';
                                /* <input style="margin:0px;"  onclick="javascript:location.href='<?php echo $purchase_url; ?>';" type="button" value="<?php _e('Buy','shutterstock'); ?>" />*/
                            ?></td>
                        </tr>
                        <tr>
                            <td valign="middle" ><?php _e('Huge','shutterstock'); ?></td>
                            <td align="center" valign="middle" ><?php echo $img_detail->sizes->huge->width, 'x',$img_detail->sizes->huge->height;  ?>px</td>
                            <td valign="middle"  width="10%"><?php
                                echo '<a href="'.$purchase_url.'" class="small col'.$shutterstock_theme_color.' shutterstock-awesome"  target="'.$shutterstock_open_link_in.'" >'.__('Buy','shutterstock').'</a>';
                                /* <input style="margin:0px;"  onclick="javascript:location.href='<?php echo $purchase_url; ?>';" type="button" value="<?php _e('Buy','shutterstock'); ?>" />*/
                            ?></td>
                        </tr>
                    </table>
                <?php

              //  showd($img_detail);
                
                ?>  <script type="text/javascript">
                        jShutterDev('#ajax_image_details_insert').css('display','block');
                        jShutterDev('#ajax_respond_insert').css('display','none');
                        jShutterDev(document).ready(function(){
                            makeScroll('#shutterstock<?php echo $image_id; ?>');
                        });
                    </script>
                <?php
                if (! $shutterstock_is_show_box) {
                    echo '<style type="text/css">';
                    echo ' .shutterstock_keywords, .shutterstock_detail_table, #ajax_image_details_insert .shutterstock_detail_table td , #ajax_image_details_insert .shutterstock_detail_table th { border:none !important;}';
                    echo '</style>';
                }
            }
}




function show_pages_nums( $search_result , $search_term_input, $page_number_original) {

    $number_per_page = get_option( 'shutterstock_num_per_page' );
    $shutterstock_theme_color = get_option('shutterstock_theme_color');
    //$number_per_page = 25;
    $pages_count = ceil($search_result->count / $number_per_page );
    $cur_num = $page_number_original; // ($search_result->page+1);

    if ($pages_count == 1) return;

    // Here we need to get Selected page and number of pages
    /*
    COUNT == 455
    [I]tems per page = 150
    [P]ages = 4


    [I] = 75
    [P] = 7

    [I] = 50
    [P] = 10

    [I] = 25
    [P] = 19


     */
    /**/
    
    echo '<div class="col'.$shutterstock_theme_color.' shutterstock_pages_container" >';
    
    $all_pages = $all_pages_fin = array();
    // Pages nearly seleted page
    if ( ($cur_num - 2) > 0 ) $all_pages[] = $cur_num - 2;
    if ( ($cur_num - 1) > 0 ) $all_pages[] = $cur_num - 1;
    $all_pages[] = $cur_num ;
    if ( ($cur_num + 1) < $pages_count ) $all_pages[] = $cur_num + 1;
    if ( ($cur_num + 2) < $pages_count ) $all_pages[] = $cur_num + 2;

 
    // First pages
    if (! in_array(1, $all_pages)) $all_pages_fin[] = 1;
    if ( (! in_array(2, $all_pages)) && (2 < $pages_count) )  $all_pages_fin[] = 2;
    if ( (! in_array(3, $all_pages)) && (3 < $pages_count) )  $all_pages_fin[] = 3;
    if ( (! in_array(4, $all_pages)) && (4 < $pages_count) )  $all_pages_fin[] = 4;
    //if ( (! in_array(5, $all_pages)) && (5 < $pages_count) )  $all_pages_fin[] = 5;
    //if ( (! in_array(6, $all_pages)) && (6 < $pages_count) )  $all_pages_fin[] = 6;
    //if ( (! in_array(7, $all_pages)) && (7 < $pages_count) )  $all_pages_fin[] = 7;
    //if ( (! in_array(8, $all_pages)) && (8 < $pages_count) )  $all_pages_fin[] = 8;
    //if ( (! in_array(9, $all_pages)) && (9 < $pages_count) )  $all_pages_fin[] = 9;
    //if ( (! in_array(10, $all_pages)) && (10 < $pages_count) )  $all_pages_fin[] = 10;

    $is_dots_exist = false;
    // Dots, if its needed
    if (count($all_pages_fin)>0) {
        if (  ( $all_pages[0] - $all_pages_fin[ (count($all_pages_fin)-1) ] ) > 1   ) {
            $is_dots_exist = true;
        }
    }

    // Get current pages
    foreach ($all_pages as $value) {
        $all_pages_fin[] = $value;
    }
    sort($all_pages_fin);

    // Dots operation in array
    if ($is_dots_exist) {
        $all_pages_fin_temp = $all_pages_fin;
        $all_pages_fin = array();

        $previos_elemnt = 0;
        foreach ($all_pages_fin_temp as $value) {
            if ( ($previos_elemnt + 1) != $value ) $all_pages_fin[] = '...';
            $all_pages_fin[] = $value;
            $previos_elemnt = $value;
        }


    }


    // Dots if needed
    if (  (  ($pages_count-1) - $all_pages_fin[ (count($all_pages_fin)-1) ] ) > 1   ) $all_pages_fin[] = '...';

    // Last pages
    if ( (! in_array( ($pages_count-1), $all_pages)) && ( ($pages_count-1) > 0 ) ) $all_pages_fin[] = $pages_count-1;
    if ( (! in_array($pages_count, $all_pages))  ) $all_pages_fin[] = $pages_count;


    if ((count($all_pages_fin)>1) && ($cur_num>1) )
        echo '<a href="javascript:ajaxSearch(\''. $search_term_input . '\', '. ($cur_num-1) .', \'' . get_option( 'shutterstock_sort_method' ) .'\' );" > &laquo; ' , __('Prev','shutterstock') , '</a> ';
    // Show pages
    foreach ($all_pages_fin as $value) {

            if ( ( $cur_num != $value ) && ($value != '...') )
                 echo '<a href="javascript:ajaxSearch(\''. $search_term_input . '\', '. $value .', \'' . get_option( 'shutterstock_sort_method' ) .'\' );" >' , $value , '</a> ';
            else if ($value != '...')
                 echo '<span class="col'.$shutterstock_theme_color.' shutterstock_pages_active">' .$value. '</span>';
            else
                 echo '<span class="shutterstock_pages_dots">' .$value. '</span>';
    }
    if ((count($all_pages_fin)>1) && ($cur_num < $value ) )
        echo '<a href="javascript:ajaxSearch(\''. $search_term_input . '\', '. ($cur_num+1) .', \'' . get_option( 'shutterstock_sort_method' ) .'\' );" >' , __('Next','shutterstock') , ' &raquo;</a> ';
/*    for ($i = 1; $i <= ($pages_count) ; $i++) {

            if ( $cur_num != $i)
                 echo '<a href="javascript:ajaxSearch(\''. $search_term_input . '\', '.$i.', \'' . get_option( 'shutterstock_sort_method' ) .'\' );" >' , $i , '</a> ';
            else
                 echo $i , ' ';

    }
/**/
    echo '</div>';
}




    //   Load locale          //////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if (!function_exists ('load_SHUTTER_Translation')) {
    function load_SHUTTER_Translation(){
        if ( ! loadSHUTTERLocale() ) { loadSHUTTERLocale('en_US'); }
        $locale = get_locale();
        //$locale = 'fr_FR'; loadLocale($locale);                                      // Localization
        if ($locale != 'en_US') {
            global  $l10n;
            //Recheck translation files according ' sign
            if (isset($l10n['shutterstock']))
                foreach ($l10n['shutterstock']->entries as $key=>$tr_obj) {
                    $new_translation = str_replace("'","\\'",$tr_obj->translations);
                    $l10n['shutterstock']->entries[$key]->translations  = $new_translation;
                }
        }
    }
    }

    if (!function_exists ('loadSHUTTERLocale')) {
    function loadSHUTTERLocale($locale = '') { // Load locale, if not so the  load en_EN default locale from folder "languages" files like "this_file_file_name-ru_RU.po" and "this_file_file_name-ru_RU.mo"
        if ( empty( $locale ) ) $locale = get_locale();

        if ( !empty( $locale ) ) {
            //Filenames like this  "microstock-photo-ru_RU.po",   "microstock-photo-de_DE.po" at folder "languages"
            $domain = str_replace('.php','',WPDEV_SHUTTER_PLUGIN_FILENAME) ;
            $mofile = WPDEV_SHUTTER_PLUGIN_DIR  .'/languages/'.$domain.'-'.$locale.'.mo';
//debuge($locale,$mofile);
            if (file_exists($mofile)) {
//debuge($domain , $mofile);
return load_textdomain($domain , $mofile);  // Depricated
                $plugin_rel_path = WPDEV_SHUTTER_PLUGIN_DIRNAME .'/languages'  ;
//debuge($domain ,   false, $plugin_rel_path);
                return load_plugin_textdomain( $domain ,   false, $plugin_rel_path ) ;
            } else   return false;
        }
        return false;
    }
    }




    function shutter_stock_html2rgb($color)
    {
        if ($color[0] == '#')
            $color = substr($color, 1);

        if (strlen($color) == 6)
            list($r, $g, $b) = array($color[0].$color[1],
                                     $color[2].$color[3],
                                     $color[4].$color[5]);
        elseif (strlen($color) == 3)
            list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
        else
            return false;

        $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

        return array($r, $g, $b);
    }

    function shutter_stock_rgb2html($r, $g=-1, $b=-1)
    {
        if (is_array($r) && sizeof($r) == 3)
            list($r, $g, $b) = $r;

        $r = intval($r); $g = intval($g);
        $b = intval($b);

        $r = dechex($r<0?0:($r>255?255:$r));
        $g = dechex($g<0?0:($g>255?255:$g));
        $b = dechex($b<0?0:($b>255?255:$b));

        $color = (strlen($r) < 2?'0':'').$r;
        $color .= (strlen($g) < 2?'0':'').$g;
        $color .= (strlen($b) < 2?'0':'').$b;
        return '#'.$color;
    }

?>