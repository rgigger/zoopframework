/*
 * Ext JS Library 1.1 Beta 2
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://www.extjs.com/license
 */


Ext.data.Record=function(_1,id){this.id=(id||id===0)?id:++Ext.data.Record.AUTO_ID;this.data=_1;};Ext.data.Record.create=function(o){var f=function(){f.superclass.constructor.apply(this,arguments);};Ext.extend(f,Ext.data.Record);var p=f.prototype;p.fields=new Ext.util.MixedCollection(false,function(_6){return _6.name;});for(var i=0,_8=o.length;i<_8;i++){p.fields.add(new Ext.data.Field(o[i]));}f.getField=function(_9){return p.fields.get(_9);};return f;};Ext.data.Record.AUTO_ID=1000;Ext.data.Record.EDIT="edit";Ext.data.Record.REJECT="reject";Ext.data.Record.COMMIT="commit";Ext.data.Record.prototype={dirty:false,editing:false,error:null,modified:null,join:function(_a){this.store=_a;},set:function(_b,_c){if(this.data[_b]==_c){return;}this.dirty=true;if(!this.modified){this.modified={};}if(typeof this.modified[_b]=="undefined"){this.modified[_b]=this.data[_b];}this.data[_b]=_c;if(!this.editing){this.store.afterEdit(this);}},get:function(_d){return this.data[_d];},beginEdit:function(){this.editing=true;this.modified={};},cancelEdit:function(){this.editing=false;delete this.modified;},endEdit:function(){this.editing=false;if(this.dirty&&this.store){this.store.afterEdit(this);}},reject:function(){var m=this.modified;for(var n in m){if(typeof m[n]!="function"){this.data[n]=m[n];}}this.dirty=false;delete this.modified;this.editing=false;if(this.store){this.store.afterReject(this);}},commit:function(){this.dirty=false;delete this.modified;this.editing=false;if(this.store){this.store.afterCommit(this);}},hasError:function(){return this.error!=null;},clearError:function(){this.error=null;}};