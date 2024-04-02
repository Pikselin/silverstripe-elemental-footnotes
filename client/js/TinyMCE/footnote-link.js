tinymce.PluginManager.add('footnotelink', function (editor, url) {
    editor.ui.registry.addButton('footnotelink', {
        text: 'Footnote link',
        tooltip: 'Add a link to a footnote',
        icon: 'save',
        onAction: function () {
            // Open window
            editor.windowManager.open({
                title: 'Footnote link',
              body: {
                type: 'panel',
                items: [
                            {type: 'input', name: 'ID', label: 'ID of footnote'},
                            {type: 'input', name: 'LinkText', label: 'Link title'},
                ]
              },
              buttons: [
                {
                  type: 'cancel',
                  text: 'Close'
                },
                {
                  type: 'submit',
                  text: 'Save',
                  primary: true
                }
              ],                
        onsubmit: function (api) {
            var data = api.getData();
            editor.insertContent('<a class="footnote-link" href="#footnote-item-' + data.ID + '">'+ data.LinkText+'</a>');
        }
            });
        }
    });
    // Adds a menu item to the tools menu
    editor.ui.registry.addMenuItem('footnotelink', {
        text: 'Footnote link',
        context: 'tools',
        onclick: function () {
            // Open window with a specific url
            editor.windowManager.open({
                title: 'TinyMCE site',
                url: 'https://www.tinymce.com',
                width: 800,
                height: 600,
            });
        }
    });
    return {
        getMetadata: function () {
            return  {
                name: "Footnote anchor plugin",
                url: "https://www.pikselin.com"
            };
        }
    };
});
