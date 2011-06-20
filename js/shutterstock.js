//<![CDATA[
function ajaxSearch(  search_term, search_page_num, sort_metod ){

    // Show loader icon
    document.getElementById('ajax_working').innerHTML = '<div style="height:28px;width:100%;text-align:center;margin:15px auto;"><img src="'+wpdev_shutter_plugin_url+'/img/'+wpdev_shutter_loader_img+'"></div>';
    /**/
    if ( document.getElementById('image_sort_type') != null) {
        sort_metod = document.getElementById('image_sort_type').value;
    }/**/
    

        jShutterDev.ajax({                                           // Start Ajax Sending
            url: wpdev_shutter_plugin_url+ '/' + wpdev_shutter_plugin_filename,
            type:'POST',
            success: function (data, textStatus){if( textStatus == 'success') {  jShutterDev('#ajax_respond_insert' ).html( data ) ; document.getElementById('ajax_working').innerHTML = '';} },
            error:function (XMLHttpRequest, textStatus, errorThrown){window.status = 'Ajax sending Error status:'+ textStatus;alert(XMLHttpRequest.status + ' ' + XMLHttpRequest.statusText);},
            // beforeSend: someFunction,
            data:{
                ajax_shutter_action : 'SHOW_SEARCH_IMAGES',
                search_term: search_term,
                search_page: search_page_num,
                sort_metod:sort_metod
            }
        });

    
}
//]]>



//<![CDATA[
function load_shutterstock_details(photo_id) {
    // Show loader icon
    document.getElementById('ajax_working').innerHTML = '<div style="height:28px;width:100%;text-align:center;margin:15px auto;"><img src="'+wpdev_shutter_plugin_url+'/img/'+wpdev_shutter_loader_img+'"></div>';


    jShutterDev.ajax({                                           // Start Ajax Sending
        url: wpdev_shutter_plugin_url+ '/' + wpdev_shutter_plugin_filename,
        type:'POST',
        success: function (data, textStatus){if( textStatus == 'success') {  jShutterDev('#ajax_image_details_insert' ).html( data ) ; document.getElementById('ajax_working').innerHTML = '';} },
        error:function (XMLHttpRequest, textStatus, errorThrown){window.status = 'Ajax sending Error status:'+ textStatus;alert(XMLHttpRequest.status + ' ' + XMLHttpRequest.statusText);},
        // beforeSend: someFunction,
        data:{
            ajax_shutter_action : 'SHOW_IMAGE_DETAILS',
            image_id: photo_id
        }
    });
}
//]]>


    // Scroll to script
    function makeScroll(object_name) {
        if (shutterstock_is_scroll) {
            var targetOffset = jShutterDev( object_name ).offset().top;
            jShutterDev('html,body').animate({scrollTop: targetOffset}, 1000);
        }
    }


this.imagePreview = function(){
	/* CONFIG */

		xOffset = 10;
		yOffset = 30;

		// these 2 variable determine popup's distance from the cursor
		// you might want to adjust to get the right result

	/* END CONFIG */
	jShutterDev("a.shutterstock_preview_link").hover(function(e){
		this.t = this.title;
		this.title = "";
		var c = (this.t != "") ? "<br/>" + this.t : "";
		jShutterDev("body").append("<p id='shutterstock_preview'><img src='"+ this.hreflang +"' alt='Image preview' />"+ c +"</p>");
		jShutterDev("#shutterstock_preview")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px")
                },
                function(){
                    this.title = this.t;
                    jShutterDev("#shutterstock_preview").remove();
        });
	jShutterDev("a.shutterstock_preview_link").mousemove(function(e){
		jShutterDev("#shutterstock_preview")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px");
	});
};



//<![CDATA[
function verify_window_opening(us_id,  window_id ){

        var is_closed = 0;

        if (jShutterDev('#' + window_id ).hasClass('closed') == true){
            jShutterDev('#' + window_id ).removeClass('closed');
        } else {
            jShutterDev('#' + window_id ).addClass('closed');
            is_closed = 1;
        }


        jShutterDev.ajax({                                           // Start Ajax Sending
                url: wpdev_shutter_plugin_url+ '/' + wpdev_shutter_plugin_filename,
                type:'POST',
                success: function (data, textStatus){if( textStatus == 'success')   jShutterDev('#ajax_working').html( data );},
                error:function (XMLHttpRequest, textStatus, errorThrown){window.status = 'Ajax sending Error status:'+ textStatus;alert(XMLHttpRequest.status + ' ' + XMLHttpRequest.statusText);},
                // beforeSend: someFunction,
                data:{
                    ajax_shutter_action : 'USER_SAVE_WINDOW_STATE',
                    user_id: us_id ,
                    window: window_id,
                    is_closed: is_closed
                }
        });

}
//]]>
