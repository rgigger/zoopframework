/*
 * Ext JS Library 1.1 Beta 2
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://www.extjs.com/license
 */


Ext.LayoutManager = function(container, config){
    Ext.LayoutManager.superclass.constructor.call(this);
    this.el = Ext.get(container);
    
    if(this.el.dom == document.body && Ext.isIE && !config.allowScroll){
        document.body.scroll = "no";
    }else if(this.el.dom != document.body && this.el.getStyle('position') == 'static'){
        this.el.position('relative');
    }
    this.id = this.el.id;
    this.el.addClass("x-layout-container");
    
    this.monitorWindowResize = true;
    this.regions = {};
    this.addEvents({
        
        "layout" : true,
        
        "regionresized" : true,
        
        "regioncollapsed" : true,
        
        "regionexpanded" : true
    });
    this.updating = false;
    Ext.EventManager.onWindowResize(this.onWindowResize, this, true);
};

Ext.extend(Ext.LayoutManager, Ext.util.Observable, {
    
    isUpdating : function(){
        return this.updating; 
    },
    
    
    beginUpdate : function(){
        this.updating = true;    
    },
    
    
    endUpdate : function(noLayout){
        this.updating = false;
        if(!noLayout){
            this.layout();
        }    
    },
    
    layout: function(){
        
    },
    
    onRegionResized : function(region, newSize){
        this.fireEvent("regionresized", region, newSize);
        this.layout();
    },
    
    onRegionCollapsed : function(region){
        this.fireEvent("regioncollapsed", region);
    },
    
    onRegionExpanded : function(region){
        this.fireEvent("regionexpanded", region);
    },
        
    
    getViewSize : function(){
        var size;
        if(this.el.dom != document.body){
            size = this.el.getSize();
        }else{
            size = {width: Ext.lib.Dom.getViewWidth(), height: Ext.lib.Dom.getViewHeight()};
        }
        size.width -= this.el.getBorderWidth("lr")-this.el.getPadding("lr");
        size.height -= this.el.getBorderWidth("tb")-this.el.getPadding("tb");
        return size;
    },
    
    
    getEl : function(){
        return this.el;
    },
    
    
    getRegion : function(target){
        return this.regions[target.toLowerCase()];
    },
    
    onWindowResize : function(){
        if(this.monitorWindowResize){
            this.layout();
        }
    }
});

Ext.BorderLayout = function(container, config){
    config = config || {};
    Ext.BorderLayout.superclass.constructor.call(this, container, config);
    this.factory = config.factory || Ext.BorderLayout.RegionFactory;
    for(var i = 0, len = this.factory.validRegions.length; i < len; i++) {
    	var target = this.factory.validRegions[i];
    	if(config[target]){
    	    this.addRegion(target, config[target]);
    	}
    }
};

Ext.extend(Ext.BorderLayout, Ext.LayoutManager, {
    
    addRegion : function(target, config){
        if(!this.regions[target]){
            var r = this.factory.create(target, this, config);
    	    this.bindRegion(target, r);
        }
        return this.regions[target];
    },

    
    bindRegion : function(name, r){
        this.regions[name] = r;
        r.on("visibilitychange", this.layout, this);
        r.on("paneladded", this.layout, this);
        r.on("panelremoved", this.layout, this);
        r.on("invalidated", this.layout, this);
        r.on("resized", this.onRegionResized, this);
        r.on("collapsed", this.onRegionCollapsed, this);
        r.on("expanded", this.onRegionExpanded, this);
    },
    
    
    layout : function(){
        if(this.updating) return;
        var size = this.getViewSize();
        var w = size.width, h = size.height;
        var centerW = w, centerH = h, centerY = 0, centerX = 0;
        
        
        var rs = this.regions;
        var n = rs["north"], s = rs["south"], west = rs["west"], e = rs["east"], c = rs["center"];
        
            
        
        if(n && n.isVisible()){
            var b = n.getBox();
            var m = n.getMargins();
            b.width = w - (m.left+m.right);
            b.x = m.left;
            b.y = m.top;
            centerY = b.height + b.y + m.bottom;
            centerH -= centerY;
            n.updateBox(this.safeBox(b));
        }
        if(s && s.isVisible()){
            var b = s.getBox();
            var m = s.getMargins();
            b.width = w - (m.left+m.right);
            b.x = m.left;
            var totalHeight = (b.height + m.top + m.bottom);
            b.y = h - totalHeight + m.top;
            centerH -= totalHeight;
            s.updateBox(this.safeBox(b));
        }
        if(west && west.isVisible()){
            var b = west.getBox();
            var m = west.getMargins();
            b.height = centerH - (m.top+m.bottom);
            b.x = m.left;
            b.y = centerY + m.top;
            var totalWidth = (b.width + m.left + m.right);
            centerX += totalWidth;
            centerW -= totalWidth;
            west.updateBox(this.safeBox(b));
        }
        if(e && e.isVisible()){
            var b = e.getBox();
            var m = e.getMargins();
            b.height = centerH - (m.top+m.bottom);
            var totalWidth = (b.width + m.left + m.right);
            b.x = w - totalWidth + m.left;
            b.y = centerY + m.top;
            centerW -= totalWidth;
            e.updateBox(this.safeBox(b));
        }
        if(c){
            var m = c.getMargins();
            var centerBox = {
                x: centerX + m.left,
                y: centerY + m.top,
                width: centerW - (m.left+m.right),
                height: centerH - (m.top+m.bottom)
            };
            
                
            
            c.updateBox(this.safeBox(centerBox));
        }
        this.el.repaint();
        this.fireEvent("layout", this);
    },
    
    safeBox : function(box){
        box.width = Math.max(0, box.width);
        box.height = Math.max(0, box.height);
        return box;
    },
    
    
    add : function(target, panel){
        target = target.toLowerCase();
        return this.regions[target].add(panel);
    },
    
    
    remove : function(target, panel){
        target = target.toLowerCase();
        return this.regions[target].remove(panel);
    },
    
    
    findPanel : function(panelId){
        var rs = this.regions;
        for(var target in rs){
            if(typeof rs[target] != "function"){
                var p = rs[target].getPanel(panelId);
                if(p){
                    return p;
                }
            }
        }
        return null;
    },
    
    
    showPanel : function(panelId) {
      var rs = this.regions;
      for(var target in rs){
         var r = rs[target];
         if(typeof r != "function"){
            if(r.hasPanel(panelId)){
               return r.showPanel(panelId);
            }
         }
      }
      return null;
   },
   
   
    restoreState : function(provider){
        if(!provider){
            provider = Ext.state.Manager;
        }
        var sm = new Ext.LayoutStateManager();
        sm.init(this, provider);
    },


    batchAdd : function(regions){
        this.beginUpdate();
        for(var rname in regions){
            var lr = this.regions[rname];
            if(lr){
                this.addTypedPanels(lr, regions[rname]);
            }
        }
        this.endUpdate();
    },

    
    addTypedPanels : function(lr, ps){
        if(typeof ps == 'string'){
            lr.add(new Ext.ContentPanel(ps));
        }
        else if(ps instanceof Array){
            for(var i =0, len = ps.length; i < len; i++){
                this.addTypedPanels(lr, ps[i]);
            }
        }
        else if(!ps.events){ 
            var el = ps.el;
            delete ps.el; 
            lr.add(new Ext.ContentPanel(el || Ext.id(), ps));
        }
        else {  
            lr.add(ps);
        }
    }
});

Ext.BorderLayout.create = function(config, targetEl){
    var layout = new Ext.BorderLayout(targetEl || document.body, config);
    layout.beginUpdate();
    var regions = Ext.BorderLayout.RegionFactory.validRegions;
    for(var j = 0, jlen = regions.length; j < jlen; j++){
        var lr = regions[j];
        if(layout.regions[lr] && config[lr].panels){
            var r = layout.regions[lr];
            var ps = config[lr].panels;
            layout.addTypedPanels(r, ps);
        }
    }
    layout.endUpdate();
    return layout;
};

Ext.BorderLayout.RegionFactory = {
    validRegions : ["north","south","east","west","center"],
    
    create : function(target, mgr, config){
        target = target.toLowerCase();
        if(config.lightweight || config.basic){
            return new Ext.BasicLayoutRegion(mgr, config, target);
        }
        switch(target){
            case "north":
                return new Ext.NorthLayoutRegion(mgr, config);
            case "south":
                return new Ext.SouthLayoutRegion(mgr, config);
            case "east":
                return new Ext.EastLayoutRegion(mgr, config);
            case "west":
                return new Ext.WestLayoutRegion(mgr, config);
            case "center":
                return new Ext.CenterLayoutRegion(mgr, config);
        }
        throw 'Layout region "'+target+'" not supported.';
    }
};

Ext.BasicLayoutRegion = function(mgr, config, pos, skipConfig){
    this.mgr = mgr;
    this.position  = pos;
    this.events = {
        
        "beforeremove" : true,
        
        "invalidated" : true,
        
        "visibilitychange" : true,
        
        "paneladded" : true,
        
        "panelremoved" : true,
        
        "collapsed" : true,
        
        "expanded" : true,
        
        "slideshow" : true,
        
        "slidehide" : true,
        
        "panelactivated" : true,
        
        "resized" : true
    };
    
    this.panels = new Ext.util.MixedCollection();
    this.panels.getKey = this.getPanelId.createDelegate(this);
    this.box = null;
    this.activePanel = null;
    if(skipConfig !== true){
        this.applyConfig(config);
    }
};

Ext.extend(Ext.BasicLayoutRegion, Ext.util.Observable, {
    getPanelId : function(p){
        return p.getId();
    },
    
    applyConfig : function(config){
        this.margins = config.margins || this.margins || {top: 0, left: 0, right:0, bottom: 0};
        this.config = config;
    },
    
    
    resizeTo : function(newSize){
        var el = this.el ? this.el :
                 (this.activePanel ? this.activePanel.getEl() : null);
        if(el){
            switch(this.position){
                case "east":
                case "west":
                    el.setWidth(newSize);
                    this.fireEvent("resized", this, newSize);
                break;
                case "north":
                case "south":
                    el.setHeight(newSize);
                    this.fireEvent("resized", this, newSize);
                break;                
            }
        }
    },
    
    getBox : function(){
        return this.activePanel ? this.activePanel.getEl().getBox(false, true) : null;
    },
    
    getMargins : function(){
        return this.margins;
    },
    
    updateBox : function(box){
        this.box = box;
        var el = this.activePanel.getEl();
        el.dom.style.left = box.x + "px";
        el.dom.style.top = box.y + "px";
        this.activePanel.setSize(box.width, box.height);
    },
    
    
    getEl : function(){
        return this.activePanel;
    },
    
    
    isVisible : function(){
        return this.activePanel ? true : false;
    },
    
    setActivePanel : function(panel){
        panel = this.getPanel(panel);
        if(this.activePanel && this.activePanel != panel){
            this.activePanel.setActiveState(false);
            this.activePanel.getEl().setLeftTop(-10000,-10000);
        }
        this.activePanel = panel;
        panel.setActiveState(true);
        if(this.box){
            panel.setSize(this.box.width, this.box.height);
        }
        this.fireEvent("panelactivated", this, panel);
        this.fireEvent("invalidated");
    },
    
    
    showPanel : function(panel){
        if(panel = this.getPanel(panel)){
            this.setActivePanel(panel);
        }
        return panel;
    },
    
    
    getActivePanel : function(){
        return this.activePanel;
    },
    
    
    add : function(panel){
        if(arguments.length > 1){
            for(var i = 0, len = arguments.length; i < len; i++) {
            	this.add(arguments[i]);
            }
            return null;
        }
        if(this.hasPanel(panel)){
            this.showPanel(panel);
            return panel;
        }
        var el = panel.getEl();
        if(el.dom.parentNode != this.mgr.el.dom){
            this.mgr.el.dom.appendChild(el.dom);
        }
        if(panel.setRegion){
            panel.setRegion(this);
        }
        this.panels.add(panel);
        el.setStyle("position", "absolute");
        if(!panel.background){
            this.setActivePanel(panel);
            if(this.config.initialSize && this.panels.getCount()==1){
                this.resizeTo(this.config.initialSize);
            }
        }
        this.fireEvent("paneladded", this, panel);
        return panel;
    },
    
    
    hasPanel : function(panel){
        if(typeof panel == "object"){ 
            panel = panel.getId();
        }
        return this.getPanel(panel) ? true : false;
    },
    
    
    remove : function(panel, preservePanel){
        panel = this.getPanel(panel);
        if(!panel){
            return null;
        }
        var e = {};
        this.fireEvent("beforeremove", this, panel, e);
        if(e.cancel === true){
            return null;
        }
        var panelId = panel.getId();
        this.panels.removeKey(panelId);
        return panel;
    },
    
    
    getPanel : function(id){
        if(typeof id == "object"){ 
            return id;
        }
        return this.panels.get(id);
    },
    
    
    getPosition: function(){
        return this.position;    
    }
});

Ext.LayoutRegion = function(mgr, config, pos){
    Ext.LayoutRegion.superclass.constructor.call(this, mgr, config, pos, true);
    var dh = Ext.DomHelper;
    
    this.el = dh.append(mgr.el.dom, {tag: "div", cls: "x-layout-panel x-layout-panel-" + this.position}, true);
    

    this.titleEl = dh.append(this.el.dom, {tag: "div", unselectable: "on", cls: "x-unselectable x-layout-panel-hd x-layout-title-"+this.position, children:[
        {tag: "span", cls: "x-unselectable x-layout-panel-hd-text", unselectable: "on", html: "&#160;"},
        {tag: "div", cls: "x-unselectable x-layout-panel-hd-tools", unselectable: "on"}
    ]}, true);
    this.titleEl.enableDisplayMode();
    
    this.titleTextEl = this.titleEl.dom.firstChild;
    this.tools = Ext.get(this.titleEl.dom.childNodes[1], true);
    this.closeBtn = this.createTool(this.tools.dom, "x-layout-close");
    this.closeBtn.enableDisplayMode();
    this.closeBtn.on("click", this.closeClicked, this);
    this.closeBtn.hide();

    this.createBody(config);
    this.visible = true;
    this.collapsed = false;

    if(config.hideWhenEmpty){
        this.hide();
        this.on("paneladded", this.validateVisibility, this);
        this.on("panelremoved", this.validateVisibility, this);
    }
    this.applyConfig(config);
};

Ext.extend(Ext.LayoutRegion, Ext.BasicLayoutRegion, {

    createBody : function(){
        
        this.bodyEl = this.el.createChild({tag: "div", cls: "x-layout-panel-body"});
    },

    applyConfig : function(c){
        if(c.collapsible && this.position != "center" && !this.collapsedEl){
            var dh = Ext.DomHelper;
            if(c.titlebar !== false){
                this.collapseBtn = this.createTool(this.tools.dom, "x-layout-collapse-"+this.position);
                this.collapseBtn.on("click", this.collapse, this);
                this.collapseBtn.enableDisplayMode();

                if(c.showPin === true || this.showPin){
                    this.stickBtn = this.createTool(this.tools.dom, "x-layout-stick");
                    this.stickBtn.enableDisplayMode();
                    this.stickBtn.on("click", this.expand, this);
                    this.stickBtn.hide();
                }
            }
            
            this.collapsedEl = dh.append(this.mgr.el.dom, {cls: "x-layout-collapsed x-layout-collapsed-"+this.position, children:[
                {cls: "x-layout-collapsed-tools", children:[{cls: "x-layout-ctools-inner"}]}
            ]}, true);
            if(c.floatable !== false){
               this.collapsedEl.addClassOnOver("x-layout-collapsed-over");
               this.collapsedEl.on("click", this.collapseClick, this);
            }

            if(c.collapsedTitle && (this.position == "north" || this.position== "south")) {
                this.collapsedTitleTextEl = dh.append(this.collapsedEl.dom, {tag: "div", cls: "x-unselectable x-layout-panel-hd-text",
                   id: "message", unselectable: "on", style:{"float":"left"}});
               this.collapsedTitleTextEl.innerHTML = c.collapsedTitle;
             }
            this.expandBtn = this.createTool(this.collapsedEl.dom.firstChild.firstChild, "x-layout-expand-"+this.position);
            this.expandBtn.on("click", this.expand, this);
        }
        if(this.collapseBtn){
            this.collapseBtn.setVisible(c.collapsible == true);
        }
        this.cmargins = c.cmargins || this.cmargins ||
                         (this.position == "west" || this.position == "east" ?
                             {top: 0, left: 2, right:2, bottom: 0} :
                             {top: 2, left: 0, right:0, bottom: 2});
        this.margins = c.margins || this.margins || {top: 0, left: 0, right:0, bottom: 0};
        this.bottomTabs = c.tabPosition != "top";
        this.autoScroll = c.autoScroll || false;
        if(this.autoScroll){
            this.bodyEl.setStyle("overflow", "auto");
        }else{
            this.bodyEl.setStyle("overflow", "hidden");
        }
        
            if((!c.titlebar && !c.title) || c.titlebar === false){
                this.titleEl.hide();
            }else{
                this.titleEl.show();
                if(c.title){
                    this.titleTextEl.innerHTML = c.title;
                }
            }
        
        this.duration = c.duration || .30;
        this.slideDuration = c.slideDuration || .45;
        this.config = c;
        if(c.collapsed){
            this.collapse(true);
        }
        if(c.hidden){
            this.hide();
        }
    },
    
    isVisible : function(){
        return this.visible;
    },

    
    setCollapsedTitle : function(title){
        title = title || "&#160;";
        if(this.collapsedTitleTextEl){
            this.collapsedTitleTextEl.innerHTML = title;
        }
    },

    getBox : function(){
        var b;
        if(!this.collapsed){
            b = this.el.getBox(false, true);
        }else{
            b = this.collapsedEl.getBox(false, true);
        }
        return b;
    },

    getMargins : function(){
        return this.collapsed ? this.cmargins : this.margins;
    },

    highlight : function(){
        this.el.addClass("x-layout-panel-dragover");
    },

    unhighlight : function(){
        this.el.removeClass("x-layout-panel-dragover");
    },

    updateBox : function(box){
        this.box = box;
        if(!this.collapsed){
            this.el.dom.style.left = box.x + "px";
            this.el.dom.style.top = box.y + "px";
            this.updateBody(box.width, box.height);
        }else{
            this.collapsedEl.dom.style.left = box.x + "px";
            this.collapsedEl.dom.style.top = box.y + "px";
            this.collapsedEl.setSize(box.width, box.height);
        }
        if(this.tabs){
            this.tabs.autoSizeTabs();
        }
    },

    updateBody : function(w, h){
        if(w !== null){
            this.el.setWidth(w);
            w -= this.el.getBorderWidth("rl");
            if(this.config.adjustments){
                w += this.config.adjustments[0];
            }
        }
        if(h !== null){
            this.el.setHeight(h);
            h = this.titleEl && this.titleEl.isDisplayed() ? h - (this.titleEl.getHeight()||0) : h;
            h -= this.el.getBorderWidth("tb");
            if(this.config.adjustments){
                h += this.config.adjustments[1];
            }
            this.bodyEl.setHeight(h);
            if(this.tabs){
                h = this.tabs.syncHeight(h);
            }
        }
        if(this.panelSize){
            w = w !== null ? w : this.panelSize.width;
            h = h !== null ? h : this.panelSize.height;
        }
        if(this.activePanel){
            var el = this.activePanel.getEl();
            w = w !== null ? w : el.getWidth();
            h = h !== null ? h : el.getHeight();
            this.panelSize = {width: w, height: h};
            this.activePanel.setSize(w, h);
        }
        if(Ext.isIE && this.tabs){
            this.tabs.el.repaint();
        }
    },

    
    getEl : function(){
        return this.el;
    },

    
    hide : function(){
        if(!this.collapsed){
            this.el.dom.style.left = "-2000px";
            this.el.hide();
        }else{
            this.collapsedEl.dom.style.left = "-2000px";
            this.collapsedEl.hide();
        }
        this.visible = false;
        this.fireEvent("visibilitychange", this, false);
    },

    
    show : function(){
        if(!this.collapsed){
            this.el.show();
        }else{
            this.collapsedEl.show();
        }
        this.visible = true;
        this.fireEvent("visibilitychange", this, true);
    },

    closeClicked : function(){
        if(this.activePanel){
            this.remove(this.activePanel);
        }
    },

    collapseClick : function(e){
        if(this.isSlid){
           e.stopPropagation();
           this.slideIn();
        }else{
           e.stopPropagation();
           this.slideOut();
        }
    },

    
    collapse : function(skipAnim){
        if(this.collapsed) return;
        this.collapsed = true;
        if(this.split){
            this.split.el.hide();
        }
        if(this.config.animate && skipAnim !== true){
            this.fireEvent("invalidated", this);
            this.animateCollapse();
        }else{
            this.el.setLocation(-20000,-20000);
            this.el.hide();
            this.collapsedEl.show();
            this.fireEvent("collapsed", this);
            this.fireEvent("invalidated", this);
        }
    },

    animateCollapse : function(){
        
    },

    
    expand : function(e, skipAnim){
        if(e) e.stopPropagation();
        if(!this.collapsed || this.el.hasActiveFx()) return;
        if(this.isSlid){
            this.afterSlideIn();
            skipAnim = true;
        }
        this.collapsed = false;
        if(this.config.animate && skipAnim !== true){
            this.animateExpand();
        }else{
            this.el.show();
            if(this.split){
                this.split.el.show();
            }
            this.collapsedEl.setLocation(-2000,-2000);
            this.collapsedEl.hide();
            this.fireEvent("invalidated", this);
            this.fireEvent("expanded", this);
        }
    },

    animateExpand : function(){
        
    },

    initTabs : function(){
        this.bodyEl.setStyle("overflow", "hidden");
        var ts = new Ext.TabPanel(this.bodyEl.dom, {
            tabPosition: this.bottomTabs ? 'bottom' : 'top',
            disableTooltips: this.config.disableTabTips
        });
        if(this.config.hideTabs){
            ts.stripWrap.setDisplayed(false);
        }
        this.tabs = ts;
        ts.resizeTabs = this.config.resizeTabs === true;
        ts.minTabWidth = this.config.minTabWidth || 40;
        ts.maxTabWidth = this.config.maxTabWidth || 250;
        ts.preferredTabWidth = this.config.preferredTabWidth || 150;
        ts.monitorResize = false;
        ts.bodyEl.setStyle("overflow", this.config.autoScroll ? "auto" : "hidden");
        ts.bodyEl.addClass('x-layout-tabs-body');
        this.panels.each(this.initPanelAsTab, this);
    },

    initPanelAsTab : function(panel){
        var ti = this.tabs.addTab(panel.getEl().id, panel.getTitle(), null,
                    this.config.closeOnTab && panel.isClosable());
        if(panel.tabTip !== undefined){
            ti.setTooltip(panel.tabTip);
        }
        ti.on("activate", function(){
              this.setActivePanel(panel);
        }, this);
        if(this.config.closeOnTab){
            ti.on("beforeclose", function(t, e){
                e.cancel = true;
                this.remove(panel);
            }, this);
        }
        return ti;
    },

    updatePanelTitle : function(panel, title){
        if(this.activePanel == panel){
            this.updateTitle(title);
        }
        if(this.tabs){
            var ti = this.tabs.getTab(panel.getEl().id);
            ti.setText(title);
            if(panel.tabTip !== undefined){
                ti.setTooltip(panel.tabTip);
            }
        }
    },

    updateTitle : function(title){
        if(this.titleTextEl && !this.config.title){
            this.titleTextEl.innerHTML = (typeof title != "undefined" && title.length > 0 ? title : "&#160;");
        }
    },

    setActivePanel : function(panel){
        panel = this.getPanel(panel);
        if(this.activePanel && this.activePanel != panel){
            this.activePanel.setActiveState(false);
        }
        this.activePanel = panel;
        panel.setActiveState(true);
        if(this.panelSize){
            panel.setSize(this.panelSize.width, this.panelSize.height);
        }
        if(this.closeBtn){
            this.closeBtn.setVisible(!this.config.closeOnTab && !this.isSlid && panel.isClosable());
        }
        this.updateTitle(panel.getTitle());
        if(this.tabs){
            this.fireEvent("invalidated", this);
        }
        this.fireEvent("panelactivated", this, panel);
    },

    
    showPanel : function(panel){
        if(panel = this.getPanel(panel)){
            if(this.tabs){
                var tab = this.tabs.getTab(panel.getEl().id);
                if(tab.isHidden()){
                    this.tabs.unhideTab(tab.id);
                }
                tab.activate();
            }else{
                this.setActivePanel(panel);
            }
        }
        return panel;
    },

    
    getActivePanel : function(){
        return this.activePanel;
    },

    validateVisibility : function(){
        if(this.panels.getCount() < 1){
            this.updateTitle("&#160;");
            this.closeBtn.hide();
            this.hide();
        }else{
            if(!this.isVisible()){
                this.show();
            }
        }
    },

    
    add : function(panel){
        if(arguments.length > 1){
            for(var i = 0, len = arguments.length; i < len; i++) {
            	this.add(arguments[i]);
            }
            return null;
        }
        if(this.hasPanel(panel)){
            this.showPanel(panel);
            return panel;
        }
        panel.setRegion(this);
        this.panels.add(panel);
        if(this.panels.getCount() == 1 && !this.config.alwaysShowTabs){
            this.bodyEl.dom.appendChild(panel.getEl().dom);
            if(panel.background !== true){
                this.setActivePanel(panel);
            }
            this.fireEvent("paneladded", this, panel);
            return panel;
        }
        if(!this.tabs){
            this.initTabs();
        }else{
            this.initPanelAsTab(panel);
        }
        if(panel.background !== true){
            this.tabs.activate(panel.getEl().id);
        }
        this.fireEvent("paneladded", this, panel);
        return panel;
    },

    
    hidePanel : function(panel){
        if(this.tabs && (panel = this.getPanel(panel))){
            this.tabs.hideTab(panel.getEl().id);
        }
    },

    
    unhidePanel : function(panel){
        if(this.tabs && (panel = this.getPanel(panel))){
            this.tabs.unhideTab(panel.getEl().id);
        }
    },

    clearPanels : function(){
        while(this.panels.getCount() > 0){
             this.remove(this.panels.first());
        }
    },

    
    remove : function(panel, preservePanel){
        panel = this.getPanel(panel);
        if(!panel){
            return null;
        }
        var e = {};
        this.fireEvent("beforeremove", this, panel, e);
        if(e.cancel === true){
            return null;
        }
        preservePanel = (typeof preservePanel != "undefined" ? preservePanel : (this.config.preservePanels === true || panel.preserve === true));
        var panelId = panel.getId();
        this.panels.removeKey(panelId);
        if(preservePanel){
            document.body.appendChild(panel.getEl().dom);
        }
        if(this.tabs){
            this.tabs.removeTab(panel.getEl().id);
        }else if (!preservePanel){
            this.bodyEl.dom.removeChild(panel.getEl().dom);
        }
        if(this.panels.getCount() == 1 && this.tabs && !this.config.alwaysShowTabs){
            var p = this.panels.first();
            var tempEl = document.createElement("div"); 
            tempEl.appendChild(p.getEl().dom);
            this.bodyEl.update("");
            this.bodyEl.dom.appendChild(p.getEl().dom);
            tempEl = null;
            this.updateTitle(p.getTitle());
            this.tabs = null;
            this.bodyEl.setStyle("overflow", this.config.autoScroll ? "auto" : "hidden");
            this.setActivePanel(p);
        }
        panel.setRegion(null);
        if(this.activePanel == panel){
            this.activePanel = null;
        }
        if(this.config.autoDestroy !== false && preservePanel !== true){
            try{panel.destroy();}catch(e){}
        }
        this.fireEvent("panelremoved", this, panel);
        return panel;
    },

    
    getTabs : function(){
        return this.tabs;
    },

    createTool : function(parentEl, className){
        var btn = Ext.DomHelper.append(parentEl, {tag: "div", cls: "x-layout-tools-button",
            children: [{tag: "div", cls: "x-layout-tools-button-inner " + className, html: "&#160;"}]}, true);
        btn.addClassOnOver("x-layout-tools-button-over");
        return btn;
    }
});

Ext.SplitLayoutRegion = function(mgr, config, pos, cursor){
    this.cursor = cursor;
    Ext.SplitLayoutRegion.superclass.constructor.call(this, mgr, config, pos);
};

Ext.extend(Ext.SplitLayoutRegion, Ext.LayoutRegion, {
    splitTip : "Drag to resize.",
    collapsibleSplitTip : "Drag to resize. Double click to hide.",
    useSplitTips : false,

    applyConfig : function(config){
        Ext.SplitLayoutRegion.superclass.applyConfig.call(this, config);
        if(config.split){
            if(!this.split){
                var splitEl = Ext.DomHelper.append(this.mgr.el.dom, 
                        {tag: "div", id: this.el.id + "-split", cls: "x-layout-split x-layout-split-"+this.position, html: "&#160;"});
                
                this.split = new Ext.SplitBar(splitEl, this.el, this.orientation);
                this.split.on("moved", this.onSplitMove, this);
                this.split.useShim = config.useShim === true;
                this.split.getMaximumSize = this[this.position == 'north' || this.position == 'south' ? 'getVMaxSize' : 'getHMaxSize'].createDelegate(this);
                if(this.useSplitTips){
                    this.split.el.dom.title = config.collapsible ? this.collapsibleSplitTip : this.splitTip;
                }
                if(config.collapsible){
                    this.split.el.on("dblclick", this.collapse,  this);
                }
            }
            if(typeof config.minSize != "undefined"){
                this.split.minSize = config.minSize;
            }
            if(typeof config.maxSize != "undefined"){
                this.split.maxSize = config.maxSize;
            }
            if(config.hideWhenEmpty || config.hidden){
                this.hideSplitter();
            }
        }
    },

    getHMaxSize : function(){
         var cmax = this.config.maxSize || 10000;
         var center = this.mgr.getRegion("center");
         return Math.min(cmax, (this.el.getWidth()+center.getEl().getWidth())-center.getMinWidth());
    },

    getVMaxSize : function(){
         var cmax = this.config.maxSize || 10000;
         var center = this.mgr.getRegion("center");
         return Math.min(cmax, (this.el.getHeight()+center.getEl().getHeight())-center.getMinHeight());
    },

    onSplitMove : function(split, newSize){
        this.fireEvent("resized", this, newSize);
    },
    
    
    getSplitBar : function(){
        return this.split;
    },
    
    hide : function(){
        this.hideSplitter();
        Ext.SplitLayoutRegion.superclass.hide.call(this);
    },

    hideSplitter : function(){
        if(this.split){
            this.split.el.setLocation(-2000,-2000);
            this.split.el.hide();
        }
    },

    show : function(){
        if(this.split){
            this.split.el.show();
        }
        Ext.SplitLayoutRegion.superclass.show.call(this);
    },
    
    beforeSlide: function(){
        if(Ext.isGecko){
            this.bodyEl.clip();
            if(this.tabs) this.tabs.bodyEl.clip();
            if(this.activePanel){
                this.activePanel.getEl().clip();
                
                if(this.activePanel.beforeSlide){
                    this.activePanel.beforeSlide();
                }
            }
        }
    },
    
    afterSlide : function(){
        if(Ext.isGecko){
            this.bodyEl.unclip();
            if(this.tabs) this.tabs.bodyEl.unclip();
            if(this.activePanel){
                this.activePanel.getEl().unclip();
                if(this.activePanel.afterSlide){
                    this.activePanel.afterSlide();
                }
            }
        }
    },

    initAutoHide : function(){
        if(this.autoHide !== false){
            if(!this.autoHideHd){
                var st = new Ext.util.DelayedTask(this.slideIn, this);
                this.autoHideHd = {
                    "mouseout": function(e){
                        if(!e.within(this.el, true)){
                            st.delay(500);
                        }
                    },
                    "mouseover" : function(e){
                        st.cancel();
                    },
                    scope : this
                };
            }
            this.el.on(this.autoHideHd);
        }
    },

    clearAutoHide : function(){
        if(this.autoHide !== false){
            this.el.un("mouseout", this.autoHideHd.mouseout);
            this.el.un("mouseover", this.autoHideHd.mouseover);
        }
    },

    clearMonitor : function(){
        Ext.get(document).un("click", this.slideInIf, this);
    },

    
    slideOut : function(){
        if(this.isSlid || this.el.hasActiveFx()){
            return;
        }
        this.isSlid = true;
        if(this.collapseBtn){
            this.collapseBtn.hide();
        }
        this.closeBtnState = this.closeBtn.getStyle('display');
        this.closeBtn.hide();
        if(this.stickBtn){
            this.stickBtn.show();
        }
        this.el.show();
        this.el.alignTo(this.collapsedEl, this.getCollapseAnchor());
        this.beforeSlide();
        this.el.setStyle("z-index", 10001);
        this.el.slideIn(this.getSlideAnchor(), {
            callback: function(){
                this.afterSlide();
                this.initAutoHide();
                Ext.get(document).on("click", this.slideInIf, this);
                this.fireEvent("slideshow", this);
            },
            scope: this,
            block: true
        });
    },

    afterSlideIn : function(){
        this.clearAutoHide();
        this.isSlid = false;
        this.clearMonitor();
        this.el.setStyle("z-index", "");
        if(this.collapseBtn){
            this.collapseBtn.show();
        }
        this.closeBtn.setStyle('display', this.closeBtnState);
        if(this.stickBtn){
            this.stickBtn.hide();
        }
        this.fireEvent("slidehide", this);
    },

    slideIn : function(cb){
        if(!this.isSlid || this.el.hasActiveFx()){
            Ext.callback(cb);
            return;
        }
        this.isSlid = false;
        this.beforeSlide();
        this.el.slideOut(this.getSlideAnchor(), {
            callback: function(){
                this.el.setLeftTop(-10000, -10000);
                this.afterSlide();
                this.afterSlideIn();
                Ext.callback(cb);
            },
            scope: this,
            block: true
        });
    },
    
    slideInIf : function(e){
        if(!e.within(this.el)){
            this.slideIn();
        }
    },

    animateCollapse : function(){
        this.beforeSlide();
        this.el.setStyle("z-index", 20000);
        var anchor = this.getSlideAnchor();
        this.el.slideOut(anchor, {
            callback : function(){
                this.el.setStyle("z-index", "");
                this.collapsedEl.slideIn(anchor, {duration:.3});
                this.afterSlide();
                this.el.setLocation(-10000,-10000);
                this.el.hide();
                this.fireEvent("collapsed", this);
            },
            scope: this,
            block: true
        });
    },

    animateExpand : function(){
        this.beforeSlide();
        this.el.alignTo(this.collapsedEl, this.getCollapseAnchor(), this.getExpandAdj());
        this.el.setStyle("z-index", 20000);
        this.collapsedEl.hide({
            duration:.1
        });
        this.el.slideIn(this.getSlideAnchor(), {
            callback : function(){
                this.el.setStyle("z-index", "");
                this.afterSlide();
                if(this.split){
                    this.split.el.show();
                }
                this.fireEvent("invalidated", this);
                this.fireEvent("expanded", this);
            },
            scope: this,
            block: true
        });
    },

    anchors : {
        "west" : "left",
        "east" : "right",
        "north" : "top",
        "south" : "bottom"
    },

    sanchors : {
        "west" : "l",
        "east" : "r",
        "north" : "t",
        "south" : "b"
    },

    canchors : {
        "west" : "tl-tr",
        "east" : "tr-tl",
        "north" : "tl-bl",
        "south" : "bl-tl"
    },

    getAnchor : function(){
        return this.anchors[this.position];
    },

    getCollapseAnchor : function(){
        return this.canchors[this.position];
    },

    getSlideAnchor : function(){
        return this.sanchors[this.position];
    },

    getAlignAdj : function(){
        var cm = this.cmargins;
        switch(this.position){
            case "west":
                return [0, 0];
            break;
            case "east":
                return [0, 0];
            break;
            case "north":
                return [0, 0];
            break;
            case "south":
                return [0, 0];
            break;
        }
    },

    getExpandAdj : function(){
        var c = this.collapsedEl, cm = this.cmargins;
        switch(this.position){
            case "west":
                return [-(cm.right+c.getWidth()+cm.left), 0];
            break;
            case "east":
                return [cm.right+c.getWidth()+cm.left, 0];
            break;
            case "north":
                return [0, -(cm.top+cm.bottom+c.getHeight())];
            break;
            case "south":
                return [0, cm.top+cm.bottom+c.getHeight()];
            break;
        }
    }
});

Ext.CenterLayoutRegion = function(mgr, config){
    Ext.CenterLayoutRegion.superclass.constructor.call(this, mgr, config, "center");
    this.visible = true;
    this.minWidth = config.minWidth || 20;
    this.minHeight = config.minHeight || 20;
};

Ext.extend(Ext.CenterLayoutRegion, Ext.LayoutRegion, {
    hide : function(){
        
    },
    
    show : function(){
        
    },
    
    getMinWidth: function(){
        return this.minWidth;
    },
    
    getMinHeight: function(){
        return this.minHeight;
    }
});


Ext.NorthLayoutRegion = function(mgr, config){
    Ext.NorthLayoutRegion.superclass.constructor.call(this, mgr, config, "north", "n-resize");
    if(this.split){
        this.split.placement = Ext.SplitBar.TOP;
        this.split.orientation = Ext.SplitBar.VERTICAL;
        this.split.el.addClass("x-layout-split-v");
    }
    var size = config.initialSize || config.height;
    if(typeof size != "undefined"){
        this.el.setHeight(size);
    }
};
Ext.extend(Ext.NorthLayoutRegion, Ext.SplitLayoutRegion, {
    orientation: Ext.SplitBar.VERTICAL,
    getBox : function(){
        if(this.collapsed){
            return this.collapsedEl.getBox();
        }
        var box = this.el.getBox();
        if(this.split){
            box.height += this.split.el.getHeight();
        }
        return box;
    },
    
    updateBox : function(box){
        if(this.split && !this.collapsed){
            box.height -= this.split.el.getHeight();
            this.split.el.setLeft(box.x);
            this.split.el.setTop(box.y+box.height);
            this.split.el.setWidth(box.width);
        }
        if(this.collapsed){
            this.updateBody(box.width, null);
        }
        Ext.NorthLayoutRegion.superclass.updateBox.call(this, box);
    }
});

Ext.SouthLayoutRegion = function(mgr, config){
    Ext.SouthLayoutRegion.superclass.constructor.call(this, mgr, config, "south", "s-resize");
    if(this.split){
        this.split.placement = Ext.SplitBar.BOTTOM;
        this.split.orientation = Ext.SplitBar.VERTICAL;
        this.split.el.addClass("x-layout-split-v");
    }
    var size = config.initialSize || config.height;
    if(typeof size != "undefined"){
        this.el.setHeight(size);
    }
};
Ext.extend(Ext.SouthLayoutRegion, Ext.SplitLayoutRegion, {
    orientation: Ext.SplitBar.VERTICAL,
    getBox : function(){
        if(this.collapsed){
            return this.collapsedEl.getBox();
        }
        var box = this.el.getBox();
        if(this.split){
            var sh = this.split.el.getHeight();
            box.height += sh;
            box.y -= sh;
        }
        return box;
    },
    
    updateBox : function(box){
        if(this.split && !this.collapsed){
            var sh = this.split.el.getHeight();
            box.height -= sh;
            box.y += sh;
            this.split.el.setLeft(box.x);
            this.split.el.setTop(box.y-sh);
            this.split.el.setWidth(box.width);
        }
        if(this.collapsed){
            this.updateBody(box.width, null);
        }
        Ext.SouthLayoutRegion.superclass.updateBox.call(this, box);
    }
});

Ext.EastLayoutRegion = function(mgr, config){
    Ext.EastLayoutRegion.superclass.constructor.call(this, mgr, config, "east", "e-resize");
    if(this.split){
        this.split.placement = Ext.SplitBar.RIGHT;
        this.split.orientation = Ext.SplitBar.HORIZONTAL;
        this.split.el.addClass("x-layout-split-h");
    }
    var size = config.initialSize || config.width;
    if(typeof size != "undefined"){
        this.el.setWidth(size);
    }
};
Ext.extend(Ext.EastLayoutRegion, Ext.SplitLayoutRegion, {
    orientation: Ext.SplitBar.HORIZONTAL,
    getBox : function(){
        if(this.collapsed){
            return this.collapsedEl.getBox();
        }
        var box = this.el.getBox();
        if(this.split){
            var sw = this.split.el.getWidth();
            box.width += sw;
            box.x -= sw;
        }
        return box;
    },

    updateBox : function(box){
        if(this.split && !this.collapsed){
            var sw = this.split.el.getWidth();
            box.width -= sw;
            this.split.el.setLeft(box.x);
            this.split.el.setTop(box.y);
            this.split.el.setHeight(box.height);
            box.x += sw;
        }
        if(this.collapsed){
            this.updateBody(null, box.height);
        }
        Ext.EastLayoutRegion.superclass.updateBox.call(this, box);
    }
});

Ext.WestLayoutRegion = function(mgr, config){
    Ext.WestLayoutRegion.superclass.constructor.call(this, mgr, config, "west", "w-resize");
    if(this.split){
        this.split.placement = Ext.SplitBar.LEFT;
        this.split.orientation = Ext.SplitBar.HORIZONTAL;
        this.split.el.addClass("x-layout-split-h");
    }
    var size = config.initialSize || config.width;
    if(typeof size != "undefined"){
        this.el.setWidth(size);
    }
};
Ext.extend(Ext.WestLayoutRegion, Ext.SplitLayoutRegion, {
    orientation: Ext.SplitBar.HORIZONTAL,
    getBox : function(){
        if(this.collapsed){
            return this.collapsedEl.getBox();
        }
        var box = this.el.getBox();
        if(this.split){
            box.width += this.split.el.getWidth();
        }
        return box;
    },
    
    updateBox : function(box){
        if(this.split && !this.collapsed){
            var sw = this.split.el.getWidth();
            box.width -= sw;
            this.split.el.setLeft(box.x+box.width);
            this.split.el.setTop(box.y);
            this.split.el.setHeight(box.height);
        }
        if(this.collapsed){
            this.updateBody(null, box.height);
        }
        Ext.WestLayoutRegion.superclass.updateBox.call(this, box);
    }
});


Ext.LayoutStateManager = function(layout){
     
     this.state = {
        north: {},
        south: {},
        east: {},
        west: {}       
    };
};

Ext.LayoutStateManager.prototype = {
    init : function(layout, provider){
        this.provider = provider;
        var state = provider.get(layout.id+"-layout-state");
        if(state){
            var wasUpdating = layout.isUpdating();
            if(!wasUpdating){
                layout.beginUpdate();
            }
            for(var key in state){
                if(typeof state[key] != "function"){
                    var rstate = state[key];
                    var r = layout.getRegion(key);
                    if(r && rstate){
                        if(rstate.size){
                            r.resizeTo(rstate.size);
                        }
                        if(rstate.collapsed == true){
                            r.collapse(true);
                        }else{
                            r.expand(null, true);
                        }
                    }
                }
            }
            if(!wasUpdating){
                layout.endUpdate();
            }
            this.state = state; 
        }
        this.layout = layout;
        layout.on("regionresized", this.onRegionResized, this);
        layout.on("regioncollapsed", this.onRegionCollapsed, this);
        layout.on("regionexpanded", this.onRegionExpanded, this);
    },
    
    storeState : function(){
        this.provider.set(this.layout.id+"-layout-state", this.state);
    },
    
    onRegionResized : function(region, newSize){
        this.state[region.getPosition()].size = newSize;
        this.storeState();
    },
    
    onRegionCollapsed : function(region){
        this.state[region.getPosition()].collapsed = true;
        this.storeState();
    },
    
    onRegionExpanded : function(region){
        this.state[region.getPosition()].collapsed = false;
        this.storeState();
    }
};

Ext.ContentPanel = function(el, config, content){
    if(el.autoCreate){
        config = el;
        el = Ext.id();
    }
    this.el = Ext.get(el);
    if(!this.el && config && config.autoCreate){
        if(typeof config.autoCreate == "object"){
            if(!config.autoCreate.id){
                config.autoCreate.id = config.id||el;
            }
            this.el = Ext.DomHelper.append(document.body,
                        config.autoCreate, true);
        }else{
            this.el = Ext.DomHelper.append(document.body,
                        {tag: "div", cls: "x-layout-inactive-content", id: config.id||el}, true);
        }
    }
    this.closable = false;
    this.loaded = false;
    this.active = false;
    if(typeof config == "string"){
        this.title = config;
    }else{
        Ext.apply(this, config);
    }
    if(this.resizeEl){
        this.resizeEl = Ext.get(this.resizeEl, true);
    }else{
        this.resizeEl = this.el;
    }
    this.addEvents({
        
        "activate" : true,
        
        "deactivate" : true,

        
        "resize" : true
    });
    if(this.autoScroll){
        this.resizeEl.setStyle("overflow", "auto");
    }
    content = content || this.content;
    if(content){
        this.setContent(content);
    }
    if(config && config.url){
        this.setUrl(this.url, this.params, this.loadOnce);
    }
    Ext.ContentPanel.superclass.constructor.call(this);
};

Ext.extend(Ext.ContentPanel, Ext.util.Observable, {
    tabTip:'',
    setRegion : function(region){
        this.region = region;
        if(region){
           this.el.replaceClass("x-layout-inactive-content", "x-layout-active-content");
        }else{
           this.el.replaceClass("x-layout-active-content", "x-layout-inactive-content");
        } 
    },
    
    
    getToolbar : function(){
        return this.toolbar;
    },
    
    setActiveState : function(active){
        this.active = active;
        if(!active){
            this.fireEvent("deactivate", this);
        }else{
            this.fireEvent("activate", this);
        }
    },
    
    setContent : function(content, loadScripts){
        this.el.update(content, loadScripts);
    },

    ignoreResize : function(w, h){
        if(this.lastSize && this.lastSize.width == w && this.lastSize.height == h){
            return true;
        }else{
            this.lastSize = {width: w, height: h};
            return false;
        }
    },
    
    getUpdateManager : function(){
        return this.el.getUpdateManager();
    },
     
    load : function(){
        var um = this.el.getUpdateManager();
        um.update.apply(um, arguments);
        return this;
    },


    
    setUrl : function(url, params, loadOnce){
        if(this.refreshDelegate){
            this.removeListener("activate", this.refreshDelegate);
        }
        this.refreshDelegate = this._handleRefresh.createDelegate(this, [url, params, loadOnce]);
        this.on("activate", this.refreshDelegate);
        return this.el.getUpdateManager();
    },
    
    _handleRefresh : function(url, params, loadOnce){
        if(!loadOnce || !this.loaded){
            var updater = this.el.getUpdateManager();
            updater.update(url, params, this._setLoaded.createDelegate(this));
        }
    },
    
    _setLoaded : function(){
        this.loaded = true;
    }, 
    
    
    getId : function(){
        return this.el.id;
    },
    
    
    getEl : function(){
        return this.el;
    },
    
    adjustForComponents : function(width, height){
        if(this.resizeEl != this.el){
            width -= this.el.getFrameWidth('lr');
            height -= this.el.getFrameWidth('tb');
        }
        if(this.toolbar){
            var te = this.toolbar.getEl();
            height -= te.getHeight();
            te.setWidth(width);
        }
        if(this.adjustments){
            width += this.adjustments[0];
            height += this.adjustments[1];
        }
        return {"width": width, "height": height};
    },
    
    setSize : function(width, height){
        if(this.fitToFrame && !this.ignoreResize(width, height)){
            if(this.fitContainer && this.resizeEl != this.el){
                this.el.setSize(width, height);
            }
            var size = this.adjustForComponents(width, height);
            this.resizeEl.setSize(this.autoWidth ? "auto" : size.width, this.autoHeight ? "auto" : size.height);
            this.fireEvent('resize', this, size.width, size.height);
        }
    },
    
    
    getTitle : function(){
        return this.title;
    },
    
    
    setTitle : function(title){
        this.title = title;
        if(this.region){
            this.region.updatePanelTitle(this, title);
        }
    },
    
    
    isClosable : function(){
        return this.closable;
    },
    
    beforeSlide : function(){
        this.el.clip();
        this.resizeEl.clip();
    },
    
    afterSlide : function(){
        this.el.unclip();
        this.resizeEl.unclip();
    },
    
    
    refresh : function(){
        if(this.refreshDelegate){
           this.loaded = false;
           this.refreshDelegate();
        }
    },
    
    
    destroy : function(){
        this.el.removeAllListeners();
        var tempEl = document.createElement("span");
        tempEl.appendChild(this.el.dom);
        tempEl.innerHTML = "";
        this.el.remove();
        this.el = null;
    }
});


Ext.GridPanel = function(grid, config){
    this.wrapper = Ext.DomHelper.append(document.body, 
        {tag: "div", cls: "x-layout-grid-wrapper x-layout-inactive-content"}, true);
    this.wrapper.dom.appendChild(grid.getGridEl().dom);
    Ext.GridPanel.superclass.constructor.call(this, this.wrapper, config);
    if(this.toolbar){
        this.toolbar.el.insertBefore(this.wrapper.dom.firstChild);
    }
    grid.monitorWindowResize = false; 
    grid.autoHeight = false;
    grid.autoWidth = false;
    this.grid = grid;
    this.grid.getGridEl().replaceClass("x-layout-inactive-content", "x-layout-component-panel");
};

Ext.extend(Ext.GridPanel, Ext.ContentPanel, {
    getId : function(){
        return this.grid.id;
    },
    
    
    getGrid : function(){
        return this.grid;    
    },
    
    setSize : function(width, height){
        if(!this.ignoreResize(width, height)){
            var grid = this.grid;
            var size = this.adjustForComponents(width, height);
            grid.getGridEl().setSize(size.width, size.height);
            grid.autoSize();
        }
    },
    
    beforeSlide : function(){
        this.grid.getView().scroller.clip();
    },
    
    afterSlide : function(){
        this.grid.getView().scroller.unclip();
    },
    
    destroy : function(){
        this.grid.destroy();
        delete this.grid;
        Ext.GridPanel.superclass.destroy.call(this); 
    }
});



Ext.NestedLayoutPanel = function(layout, config){
    Ext.NestedLayoutPanel.superclass.constructor.call(this, layout.getEl(), config);
    layout.monitorWindowResize = false; 
    this.layout = layout;
    this.layout.getEl().addClass("x-layout-nested-layout");
};

Ext.extend(Ext.NestedLayoutPanel, Ext.ContentPanel, {

    setSize : function(width, height){
        if(!this.ignoreResize(width, height)){
            var size = this.adjustForComponents(width, height);
            var el = this.layout.getEl();
            el.setSize(size.width, size.height);
            var touch = el.dom.offsetWidth;
            this.layout.layout();
            
            if(Ext.isIE && !this.initialized){
                this.initialized = true;
                this.layout.layout();
            }
        }
    },
    
    
    getLayout : function(){
        return this.layout;
    }
});

Ext.ScrollPanel = function(el, config, content){
    config = config || {};
    config.fitToFrame = true;
    Ext.ScrollPanel.superclass.constructor.call(this, el, config, content);
    
    this.el.dom.style.overflow = "hidden";
    var wrap = this.el.wrap({cls: "x-scroller x-layout-inactive-content"});
    this.el.removeClass("x-layout-inactive-content");
    this.el.on("mousewheel", this.onWheel, this);

    var up = wrap.createChild({cls: "x-scroller-up", html: "&#160;"}, this.el.dom);
    var down = wrap.createChild({cls: "x-scroller-down", html: "&#160;"});
    up.unselectable(); down.unselectable();
    up.on("click", this.scrollUp, this);
    down.on("click", this.scrollDown, this);
    up.addClassOnOver("x-scroller-btn-over");
    down.addClassOnOver("x-scroller-btn-over");
    up.addClassOnClick("x-scroller-btn-click");
    down.addClassOnClick("x-scroller-btn-click");
    this.adjustments = [0, -(up.getHeight() + down.getHeight())];

    this.resizeEl = this.el;
    this.el = wrap; this.up = up; this.down = down;
};

Ext.extend(Ext.ScrollPanel, Ext.ContentPanel, {
    increment : 100,
    wheelIncrement : 5,
    scrollUp : function(){
        this.resizeEl.scroll("up", this.increment, {callback: this.afterScroll, scope: this});
    },

    scrollDown : function(){
        this.resizeEl.scroll("down", this.increment, {callback: this.afterScroll, scope: this});
    },

    afterScroll : function(){
        var el = this.resizeEl;
        var t = el.dom.scrollTop, h = el.dom.scrollHeight, ch = el.dom.clientHeight;
        this.up[t == 0 ? "addClass" : "removeClass"]("x-scroller-btn-disabled");
        this.down[h - t <= ch ? "addClass" : "removeClass"]("x-scroller-btn-disabled");
    },

    setSize : function(){
        Ext.ScrollPanel.superclass.setSize.apply(this, arguments);
        this.afterScroll();
    },

    onWheel : function(e){
        var d = e.getWheelDelta();
        this.resizeEl.dom.scrollTop -= (d*this.wheelIncrement);
        this.afterScroll();
        e.stopEvent();
    },

    setContent : function(content, loadScripts){
        this.resizeEl.update(content, loadScripts);
    }

});

Ext.ReaderLayout = function(config, renderTo){
    var c = config || {size:{}};
    Ext.ReaderLayout.superclass.constructor.call(this, renderTo || document.body, {
        north: c.north !== false ? Ext.apply({
            split:false,
            initialSize: 32,
            titlebar: false
        }, c.north) : false,
        west: c.west !== false ? Ext.apply({
            split:true,
            initialSize: 200,
            minSize: 175,
            maxSize: 400,
            titlebar: true,
            collapsible: true,
            animate: true,
            margins:{left:5,right:0,bottom:5,top:5},
            cmargins:{left:5,right:5,bottom:5,top:5}
        }, c.west) : false,
        east: c.east !== false ? Ext.apply({
            split:true,
            initialSize: 200,
            minSize: 175,
            maxSize: 400,
            titlebar: true,
            collapsible: true,
            animate: true,
            margins:{left:0,right:5,bottom:5,top:5},
            cmargins:{left:5,right:5,bottom:5,top:5}
        }, c.east) : false,
        center: Ext.apply({
            tabPosition: 'top',
            autoScroll:false,
            closeOnTab: true,
            titlebar:false,
            margins:{left:c.west!==false ? 0 : 5,right:c.east!==false ? 0 : 5,bottom:5,top:2}
        }, c.center)
    });

    this.el.addClass('x-reader');

    this.beginUpdate();

    var inner = new Ext.BorderLayout(Ext.get(document.body).createChild(), {
        south: c.preview !== false ? Ext.apply({
            split:true,
            initialSize: 200,
            minSize: 100,
            autoScroll:true,
            collapsible:true,
            titlebar: true,
            cmargins:{top:5,left:0, right:0, bottom:0}
        }, c.preview) : false,
        center: Ext.apply({
            autoScroll:false,
            titlebar:false,
            minHeight:200
        }, c.listView)
    });
    this.add('center', new Ext.NestedLayoutPanel(inner,
            Ext.apply({title: c.mainTitle || '',tabTip:''},c.innerPanelCfg)));

    this.endUpdate();

    this.regions.preview = inner.getRegion('south');
    this.regions.listView = inner.getRegion('center');
};

Ext.extend(Ext.ReaderLayout, Ext.BorderLayout);
