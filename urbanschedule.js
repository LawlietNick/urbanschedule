(function() {
    tinymce.PluginManager.add('urban_weekschedule_tc_button', function( editor, url ) {
        editor.addButton( 'urban_weekschedule_tc_button', {
            title: 'My test button',
            type: 'menubutton',
            icon: 'icon urban-weekschedule-icon',
            menu: [
                {
                    text: 'Lisää tunti',
                    onclick: function() {
                        editor.windowManager.open( {
                            title: 'Lisää uusi tunti',
                            body: [{
                                type: 'textbox',
                                name: 'nimi',
                                label: 'Tunnin nimi'
                            },
                            {
                                type: 'textbox',
                                name: 'aloitusaika',
                                label: 'Tunnin aloitusaika'
                            },
                            {
                                type: 'textbox',
                                name: 'lopetusaika',
                                label: 'Tunnin lopetusaika'
                            },
                            {
                                type: 'textbox',
                                name: 'kuvaus',
                                label: 'Tunnin kuvaus'
                            }],
                            onsubmit: function( e ) {
                                editor.insertContent( '[tunti nimi="' + e.data.nimi + '" aloitusaika="' + e.data.aloitusaika + '" lopetusaika="' + e.data.lopetusaika + '" kuvaus="' + e.data.kuvaus + '"]' );
                            }
                        });
                    }
                }
           ]
        });
    });
})();


