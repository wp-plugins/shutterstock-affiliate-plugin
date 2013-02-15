(function($)
{  
  var Shutterstock_AP_Dialog = {    
    init: function()
    {
      var t = this;
      this.dialog = $('#shutterstock_ap_shortcode_dialog');
      this.cancel = $('#sap_bd_cancel');
      this.insert = $('#sap_bd_insert');            
                        
      QTags.addButton('shutterstock_ap_button', 'shutterstock', function() { t.open(); });
      this.insert.bind('click', function() { t.update(); });
      this.cancel.bind('click', function() { t.close(); });
    },
    isMCE: function()
    {
      return tinyMCEPopup && ( ed = tinyMCEPopup.editor ) && ! ed.isHidden();
    },        
    open: function()
    {      
      if (!wpActiveEditor) return;
      this.textarea = $('#'+wpActiveEditor).get(0);
      
      if (!this.dialog.data('wpdialog'))
      {
        this.dialog.wpdialog(
        {
          title: Shutterstock_AP_data.text.add_shortcode,
          width: 500,
          height: 'auto',
          modal: true,
          dialogClass: 'wp-dialog',
          zIndex: 300000
        });        
      }

      this.dialog.wpdialog('open');
    },    
    close: function()
    {
      if (this.isMCE())
      {
        tinyMCEPopup.editor.focus();
        tinyMCEPopup.close();
      }
      else
      {        
        this.dialog.wpdialog('close');
        this.textarea.focus();
      }           
    },
    genHTML: function()
    {
      var html = '[shutterstock';
      
      var keywords = $('#sap_bd_keywords').val();
      if (keywords.length > 0)
        html+= ' keywords="' + keywords + '"'; 

      if ($('#sap_bd_auto_keywords').attr('checked'))
        html+= ' auto=1';             
      
      var creator_id = $('#sap_bd_creator_id').val();
      if (creator_id.length > 0)
        html+= ' creator="' + creator_id + '"';       
      
      html+= ' order=' + $('#sap_bd_sort_order').val();

      html+= ' type=' + $('#sap_bd_images_type').val();
      
      html+= ' images=' + $('#sap_bd_number_images').val();

      html+= ' thumbsize=' + $('#sap_bd_thumbnail_size').val();

      html+= ' detail=' + $('#sap_bd_image_detail').val();
      
      if ($('#sap_bd_show_extra_search').attr('checked'))
        html+= ' search=1';             

      if ($('#sap_bd_show_header').attr('checked'))
        html+= ' header=1';             

      if ($('#sap_bd_auto_scroll').attr('checked'))
        html+= ' autoscroll=1';             

      if ($('#sap_bd_show_paging').attr('checked'))
        html+= ' paging=1';             

      if ($('#sap_bd_swap_keycustom').attr('checked'))
        html+= ' swapkc=1';             

      if ($('#sap_bd_show_keywords').attr('checked'))
        html+= ' showkeywords=1';             

      if ($('#sap_bd_show_customcontent').attr('checked'))
        html+= ' customcontent=1';             

      if ($('#sap_bd_show_more').attr('checked'))
        html+= ' more=1';             

      if ($('#sap_bd_show_logo').attr('checked'))
        html+= ' logo=1';

      if ($('#sap_bd_show_tooltip').attr('checked'))
        html+= ' tooltips=1';
        
      html+= ' results=' + $('#sap_bd_image_results').val();      
        
      html+= ']';

      return html;    
    },
    update: function()
    {
      if (this.isMCE())
        this.mceUpdate();
      else
        this.htmlUpdate();
    },                
    htmlUpdate : function()
    {
      var html, start, end, cursor, textarea = this.textarea;
      if (!textarea) return;
      
      html = this.genHTML();
      
      var range = null;       
      if (!this.isMCE() && document.selection)
      {
        textarea.focus();
        range = document.selection.createRange();
      } 

      // Insert HTML
      // W3C
			if (typeof textarea.selectionStart !== 'undefined')
      {
        start = textarea.selectionStart;
        end = textarea.selectionEnd;
        selection = textarea.value.substring(start, end);
        cursor = start + html.length;

        textarea.value = textarea.value.substring(0, start)
                       + html
                       + textarea.value.substring(end, textarea.value.length);

        // Update cursor position
        textarea.selectionStart = textarea.selectionEnd = cursor;
      }
      else
      if (document.selection && range) // IE
      {
        textarea.focus();
        range.text = html; //+ range.text;
        range.moveToBookmark(range.getBookmark());
        range.select();
        range = null;
      }

      this.close();
      textarea.focus();
    },
    mceUpdate : function()
    {
      var ed = tinyMCEPopup.editor, html = this.genHTML();
      
      tinyMCEPopup.execCommand("mceBeginUndoLevel");
      ed.selection.setContent(html);
      tinyMCEPopup.execCommand("mceEndUndoLevel");      
      this.close();
      ed.focus();
    },
  }

  $(document).ready(function() { Shutterstock_AP_Dialog.init(); });
})(jQuery);