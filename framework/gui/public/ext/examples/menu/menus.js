/*
 * Ext JS Library 1.1 Beta 2
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://www.extjs.com/license
 */

Ext.onReady(function(){
    Ext.QuickTips.init();

    // Menus can be prebuilt and passed by reference
    var dateMenu = new Ext.menu.DateMenu({
        handler : function(dp, date){
            Ext.example.msg('Date Selected', 'You chose {0}.', date.format('M j, Y'));
        }
    });

    // Menus can be prebuilt and passed by reference
    var colorMenu = new Ext.menu.ColorMenu({
        handler : function(cm, color){
            Ext.example.msg('Color Selected', 'You chose {0}.', color);
        }
    });

    var menu = new Ext.menu.Menu({
        id: 'mainMenu',
        items: [
            new Ext.menu.CheckItem({
                text: 'I like Ext',
                checked: true,
                checkHandler: onItemCheck
            }),
            new Ext.menu.CheckItem({
                text: 'Ext for jQuery',
                checked: true,
                checkHandler: onItemCheck
            }),
            new Ext.menu.CheckItem({
                text: 'I donated!',
                checked:false,
                checkHandler: onItemCheck
            }), '-', {
                text: 'Radio Options',
                menu: {        // <-- submenu by nested config object
                    items: [
                        // stick any markup in a menu
                        '<b class="menu-title">Choose a Theme</b>',
                        new Ext.menu.CheckItem({
                            text: 'Aero Glass',
                            checked: true,
                            group: 'theme',
                            checkHandler: onItemCheck
                        }),
                        new Ext.menu.CheckItem({
                            text: 'Vista Black',
                            group: 'theme',
                            checkHandler: onItemCheck
                        }),
                        new Ext.menu.CheckItem({
                            text: 'Gray Theme',
                            group: 'theme',
                            checkHandler: onItemCheck
                        }),
                        new Ext.menu.CheckItem({
                            text: 'Default Theme',
                            group: 'theme',
                            checkHandler: onItemCheck
                        })
                    ]
                }
            },{
                text: 'Choose a Date',
                cls: 'calendar',
                menu: dateMenu // <-- submenu by reference
            },{
                text: 'Choose a Color',
                menu: colorMenu // <-- submenu by reference
            }
        ]
    });

    var tb = new Ext.Toolbar('toolbar');
    tb.add({
            cls: 'x-btn-text-icon bmenu', // icon and text class
            text:'Button w/ Menu',
            menu: menu  // assign menu by instance
        }, 
        new Ext.Toolbar.MenuButton({
            text: 'Split Button',
            handler: onButtonClick,
            tooltip: {text:'This is a QuickTip with autoHide set to false and a title', title:'Tip Title', autoHide:false},
            cls: 'x-btn-text-icon blist',
            // Menus can be built/referenced by using nested menu config objects
            menu : {items: [
                        {text: '<b>Bold</b>', handler: onItemClick},
                        {text: '<i>Italic</i>', handler: onItemClick},
                        {text: '<u>Underline</u>', handler: onItemClick}, '-',{
                        text: 'Pick a Color', handler: onItemClick, menu: {
                        items: [
                                new Ext.menu.ColorItem({selectHandler:function(cp, color){
                                    Ext.example.msg('Color Selected', 'You chose {0}.', color);
                                }}), '-',
                                {text:'More Colors...', handler:onItemClick}
                        ]
                    }},
                    {text: 'Extellent!', handler: onItemClick}
                ]}
        }), '-', {
        text: 'Toggle Me',
        enableToggle: true,
        toggleHandler: onItemToggle,
        pressed: true
    });

    menu.addSeparator();
    // Menus have a rich api for
    // adding and removing elements dynamically
    var item = menu.add({
        text: 'Dynamically added Item'
    });
    // items support full Observable API
    item.on('click', onItemClick);

    // items can easily be looked up
    menu.add({
        text: 'Disabled Item',
        id: 'disableMe'  // <-- Items can also have an id for easy lookup
        // disabled: true   <-- allowed but for sake of example we use long way below
    });
    // access items by id or index
    menu.items.get('disableMe').disable();

    // They can also be referenced by id in or components
    tb.add('-', {
        icon: 'list-items.gif', // icons can also be specified inline
        cls: 'x-btn-icon',
        tooltip: '<b>Quick Tips</b><br/>Icon only button with tooltip'
    }, '-');

    // add a combobox to the toolbar
    var store = new Ext.data.SimpleStore({
        fields: ['abbr', 'state'],
        data : Ext.exampledata.states // from states.js
    });
    var combo = new Ext.form.ComboBox({
        store: store,
        displayField:'state',
        typeAhead: true,
        mode: 'local',
        triggerAction: 'all',
        emptyText:'Select a state...',
        selectOnFocus:true,
        width:135
    });
    tb.addField(combo);

    // functions to display feedback
    function onButtonClick(btn){
        Ext.example.msg('Button Click','You clicked the "{0}" button.', btn.text);
    }

    function onItemClick(item){
        Ext.example.msg('Menu Click', 'You clicked the "{0}" menu item.', item.text);
    }

    function onItemCheck(item, checked){
        Ext.example.msg('Item Check', 'You {1} the "{0}" menu item.', item.text, checked ? 'checked' : 'unchecked');
    }

    function onItemToggle(item, pressed){
        Ext.example.msg('Button Toggled', 'Button "{0}" was toggled to {1}.', item.text, pressed);
    }
});