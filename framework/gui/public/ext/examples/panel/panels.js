/*
 * Ext JS Library 2.0
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.onReady(function(){
    var p = new Ext.Panel({
        title: 'My Panel',
        collapsible:true,
        renderTo: document.body,
        width:400,
        html: Ext.example.bogusMarkup
    });
});