/*
 * Ext JS Library 1.1 Beta 2
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://www.extjs.com/license
 */


Ext.UpdateManager.defaults.indicatorText="<div class=\"loading-indicator\">Nalagam...</div>";if(Ext.View){Ext.View.prototype.emptyText="";}if(Ext.grid.Grid){Ext.grid.Grid.prototype.ddText="{0} izbranih vrstic";}if(Ext.TabPanelItem){Ext.TabPanelItem.prototype.closeText="Zapri zavihek";}if(Ext.form.Field){Ext.form.Field.prototype.invalidText="Neveljavna vrednost";}if(Ext.LoadMask){Ext.LoadMask.prototype.msg="Nalagam...";}Date.monthNames=["Januar","Februar","Marec","April","Maj","Junij","Julij","Avgust","September","Oktober","November","December"];Date.dayNames=["Nedelja","Ponedeljek","Torek","Sreda","\xc4\u0152etrtek","Petek","Sobota"];if(Ext.MessageBox){Ext.MessageBox.buttonText={ok:"V redu",cancel:"Prekli\xc4\ufffdi",yes:"Da",no:"Ne"};}if(Ext.util.Format){Ext.util.Format.date=function(v,_2){if(!v){return"";}if(!(v instanceof Date)){v=new Date(Date.parse(v));}return v.dateFormat(_2||"d.m.Y");};}if(Ext.DatePicker){Ext.apply(Ext.DatePicker.prototype,{todayText:"Danes",minText:"Navedeni datum je pred spodnjim datumom",maxText:"Navedeni datum je za zgornjim datumom",disabledDaysText:"",disabledDatesText:"",monthNames:Date.monthNames,dayNames:Date.dayNames,nextText:"Naslednji mesec (Control+Desno)",prevText:"Prej\xc5\xa1nji mesec (Control+Levo)",monthYearText:"Izberite mesec (Control+Gor/Dol za premik let)",todayTip:"{0} (Preslednica)",format:"d.m.y",startDay:1});}if(Ext.PagingToolbar){Ext.apply(Ext.PagingToolbar.prototype,{beforePageText:"Stran",afterPageText:"od {0}",firstText:"Prva stran",prevText:"Prej\xc5\xa1nja stran",nextText:"Naslednja stran",lastText:"Zadnja stran",refreshText:"Osve\xc5\xbei",displayMsg:"Prikazujem {0} - {1} od {2}",emptyMsg:"Ni podatkov za prikaz"});}if(Ext.form.TextField){Ext.apply(Ext.form.TextField.prototype,{minLengthText:"Minimalna dol\xc5\xbeina tega polja je {0}",maxLengthText:"Maksimalna dol\xc5\xbeina tega polja je {0}",blankText:"To polje je obvezno",regexText:"",emptyText:null});}if(Ext.form.NumberField){Ext.apply(Ext.form.NumberField.prototype,{minText:"Minimalna vrednost tega polja je {0}",maxText:"Maksimalna vrednost tega polja je {0}",nanText:"{0} ni veljavna \xc5\xa1tevilka"});}if(Ext.form.DateField){Ext.apply(Ext.form.DateField.prototype,{disabledDaysText:"Onemogo\xc4\ufffden",disabledDatesText:"Onemogo\xc4\ufffden",minText:"Datum mora biti po {0}",maxText:"Datum mora biti pred {0}",invalidText:"{0} ni veljaven datum - mora biti v tem formatu {1}",format:"d.m.y"});}if(Ext.form.ComboBox){Ext.apply(Ext.form.ComboBox.prototype,{loadingText:"Nalagam...",valueNotFoundText:undefined});}if(Ext.form.VTypes){Ext.apply(Ext.form.VTypes,{emailText:"To polje je e-mail naslov formata \"ime@domena.si\"",urlText:"To polje je URL naslov formata \"http:/"+"/www.domena.si\"",alphaText:"To polje lahko vsebuje samo \xc4\ufffdrke in _",alphanumText:"To polje lahko vsebuje samo \xc4\ufffdrke, \xc5\xa1tevilke in _"});}if(Ext.grid.GridView){Ext.apply(Ext.grid.GridView.prototype,{sortAscText:"Sortiraj nara\xc5\xa1\xc4\ufffdajo\xc4\ufffde",sortDescText:"Sortiraj padajo\xc4\ufffde",lockText:"Zakleni stolpec",unlockText:"Odkleni stolpec",columnsText:"Stolpci"});}if(Ext.grid.PropertyColumnModel){Ext.apply(Ext.grid.PropertyColumnModel.prototype,{nameText:"Ime",valueText:"Vrednost",dateFormat:"j.m.Y"});}if(Ext.SplitLayoutRegion){Ext.apply(Ext.SplitLayoutRegion.prototype,{splitTip:"Potegni za raz\xc5\xa1iritev.",collapsibleSplitTip:"Potegni za raz\xc5\xa1iritev. Dvojni klik, \xc4\ufffde \xc5\xbeelite skriti."});}