(function() {
    tinymce.create('tinymce.plugins.FuwariEmoji', {
        init: function(editor, url) {
            editor.addButton('fuwari_emoji', {
                type: 'menubutton',
                text: '表情',
                icon: false,
                menu: (function() {
                    var emojiList = editor.settings.fuwari_emoji_list;
                    var items = [];
                    
                    for (var emoji in emojiList) {
                        items.push({
                            text: emoji + ' ' + emojiList[emoji],
                            onclick: (function(e) {
                                return function() {
                                    editor.insertContent(' ' + e + ' ');
                                };
                            })(emoji)
                        });
                    }
                    
                    return items;
                })()
            });
        },
        createControl: function(n, cm) {
            return null;
        },
    });
    
    tinymce.PluginManager.add('fuwari_emoji', tinymce.plugins.FuwariEmoji);
})();