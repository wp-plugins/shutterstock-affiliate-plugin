(function()
{
  tinymce.create('tinymce.plugins.shutterstock_ap_button_dialog',
  {    
    init: function(ed, url)
    {      
      ed.addCommand('shutterstock_ap_shortcode_dialog', function()
      {
        var win = ed.windowManager.open(
        {
          id: 'shutterstock_ap_shortcode_dialog',
          width: 500,
          height: "auto",
          wpDialog: true,
          title: Shutterstock_AP_data.text.add_shortcode
        },
        {
          plugin_url: url
        });                       
      });    
             
      ed.addButton('shutterstock_ap_button',
      {
        title: Shutterstock_AP_data.text.add_shortcode,
        image: url + '/images/mce-icon.png',
        cmd: 'shutterstock_ap_shortcode_dialog'
      });
    },
    createControl: function(n, cm)
    {
      return null;
    },
    getInfo: function()
    {      
      return {
        longname: Shutterstock_AP_data.text.long_name,
        author: 'Shutterstock AP Team',
        authorurl: '',
        infourl: '',
        version: Shutterstock_AP_data.version
      };
    }     
  });
  
  tinymce.PluginManager.add('shutterstock_ap_button_dialog', tinymce.plugins.shutterstock_ap_button_dialog);
})();