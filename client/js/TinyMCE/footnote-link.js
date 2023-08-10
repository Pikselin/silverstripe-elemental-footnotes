tinymce.PluginManager.add('footnotelink', function (editor, url) {
    editor.addButton('footnotelink', {
        title: 'Footnote link',
        image: '/_resources/vendor/pikselin/silverstripe-elemental-footnotes/client/js/TinyMCE/footnotelink.gif',
        onclick: function () {
            // Open window
            editor.windowManager.open({
                title: 'Footnote link',
                body: [
                    {type: 'textbox', name: 'ID', label: 'ID of footnote'},
                    {type: 'textbox', name: 'LinkText', label: 'Link title'},
                ],
                onsubmit: function (e) {
                    editor.insertContent('<a class="footnote-link" href="#footnote-item-' + e.data.ID + '">'+ e.data.LinkText+'</a>');
                }
            });
        }
    });
    // Adds a menu item to the tools menu
    editor.addMenuItem('footnotelink', {
        text: 'Footnote link',
        context: 'tools',
        onclick: function () {
            // Open window with a specific url
            editor.windowManager.open({
                title: 'TinyMCE site',
                url: 'https://www.tinymce.com',
                width: 800,
                height: 600,
                buttons: [{
                        text: 'Close',
                        onclick: 'close'
                    }]
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
