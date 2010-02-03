/*
 *
 * Copyright (c) 2009 C. F., Wong (<a href="http://cloudgen.w0ng.hk">Cloudgen Examplet Store</a>)
 * Licensed under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * See details in: <a href="http://cloudgen.w0ng.hk/javascript/javascript.php">Javascript Examplet</a>
 *
 */
(function($){
	var ExpandableTableList=[],className="ExpandableTable";
	function ExpandableTable(target,maxRow){
		if(target) this.init(target,maxRow);
	}
	ExpandableTable.prototype.init=function(target,maxRow){
		ExpandableTableList.push(this);
		this.target=$(target).data(className,this);
		this.maxRow=maxRow;
		this.seed=Math.round(Math.random()*10000);
		this.onAddRow=[];
		return this
	};
	ExpandableTable.prototype.live=function(){
		if (!this.goLive){
			var t=this;
			this.update();
			$(".addRow"+this.seed)
			.live("click",function(){
				var newRow=t.addRow();
			});
			$(".delRow"+this.seed)
			.live("click",function(){
				var o=$(this).closest("tr")
				.clone();
				$(this)
				.closest("tr")
				.remove();
				$(".addRow"+t.seed)
				.attr("disabled",false);
				t.update();
				if(t.deleteCallBack && $.isFunction(t.deleteCallBack)) 
					t.deleteCallBack(o);
			});
			$(".autoAdd"+this.seed)
			.live("keyup",function(){
				if((this.nodeName.toLowerCase()=="textarea" && $(this).html()!="") ||
				(this.nodeName.toLowerCase()=="textarea" && $(this).val()!="") ||
				(this.nodeName.toLowerCase()=="input" && $(this).val()!="")) t.addRow();
			});
			this.goLive=true;
		}
		return this
	};
	ExpandableTable.prototype.updateRowNumber=function(){
		if(this.rowNumColumn){
		this.target.find("."+this.rowNumColumn).each(function(i,v){
				if(i+1!=parseInt($(this).text())) $(this).text(i+1)
			});
		}
		return this
	};
	ExpandableTable.prototype.updateInputBoxName=function(){
		$(".delRow"+this.seed).closest("tr").each(function(i,v){
			var n=i+1;
			$("input,textarea",this).each(function(i,v){
				if($(this).attr("name")!=""){
					var newName=$(this).attr("name").replace(/\d+$/,"")+n;
					$(this).attr("name",newName);
				}
			});
		});
	}
	ExpandableTable.prototype.updateOddRowCSS=function(){
		if(this.oddRowCSS){
			this.target.find("."+this.oddRowCSS).removeClass(this.oddRowCSS);
			this.target.find("tr:odd").addClass(this.oddRowCSS);
		}
		return this
	};
	ExpandableTable.prototype.updateEvenRowCSS=function(){
		if(this.evenRowCSS){
			this.target.find("."+this.evenRowCSS).removeClass(this.evenRowCSS);
			this.target.find("tr:even").addClass(this.evenRowCSS);
		}
		return this
	};
	ExpandableTable.prototype.update=function(){
		var t=this;
		this.delRowButtons=$(".delRow"+this.seed,this.target);
		if(this.delRowButtons.size()==1)
			this.delRowButtons.hide();
		else {
			if(this.autoAddRow)
				this.delRowButtons.not(":last").show();
			else
				this.delRowButtons.show();
		}
		if(this.autoAddRow) {
			this.target.find(".autoAdd"+this.seed).removeClass("autoAdd"+t.seed);
			this.target
			.find(".delRow"+this.seed+":last")
			.closest("tr")
			.find("input,textarea")
			.addClass("autoAdd"+this.seed);
		}
		if(this.inputBoxAutoNumber) this.updateInputBoxName();
		if(this.displayRowCountTo) 
			$("."+this.displayRowCountTo).val(
				$(".delRow"+this.seed).closest("tr").size()
			);
		this.updateRowNumber()
		.updateOddRowCSS()
		.updateEvenRowCSS();
		return this
	};
	ExpandableTable.prototype.addRow=function(){
		var newRow;
		if(!this.maxRow || (this.maxRow && $(".delRow"+this.seed).size()<this.maxRow)){
			this.delRowButtons.show();
			var lastRow=$(".delRow"+this.seed+":last",this.target).closest("tr");
			this.newRow=newRow=lastRow.clone();
			newRow.find("input:text").val("");
			newRow.find("input:hidden").not(".delRow"+this.seed).not(".addRow"+this.seed).val("");
			newRow.find("textarea").text("");
			if(this.autoAddRow) newRow.find(".delRow"+this.seed).hide();
			newRow.insertAfter(lastRow);
			if(this.ignoreClass && this.ignoreClass!=""){
				newRow.find("."+this.ignoreClass).each(function(){
					if(this.nodeName.toLowerCase()=="input" &&
						($(this).attr("type").toLowerCase()=="text"
						|| $(this).attr("type").toLowerCase()=="hidden"
					)) $(this).val("");
					else if(this.nodeName.toLowerCase()=="td") $(this).html(" ");
					else if($(this).html()!="") $(this).text("");
				});
			}
			if(this.maxRow && $(".delRow"+this.seed).size()>=this.maxRow) 
				$(".addRow"+this.seed).attr("disabled",true);
			this.target.find(".delRow"+this.seed+":first").closest("tr").find("*").each(function(i,v){
				if($(this).data("init")) $.each($(this).data("init"),function(j,v){
					this(newRow.find("*").eq(i)[0]);
				});
			});
		}
		this.update();
		if(this.addCallBack && $.isFunction(this.addCallBack))
			this.addCallBack(newRow);
		return newRow
	};
	$.fn.btnAddRow=$.fn.tableAutoAddRow=function(options,func){
		var callBack;
		if (typeof options=="object")
			callBack=(func && $.isFunction(func)) ? func :null; 
		else
			callBack=(options && $.isFunction(options)) ? options :null; 
		options=$.extend({maxRow:null,ignoreClass:null,rowNumColumn:null,autoAddRow:false,oddRowCSS:null,evenRowCSS:null,inputBoxAutoNumber:false,displayRowCountTo:null},options);
		this.each(function(){
			var tbl=(this.nodeName.toLowerCase()=="table")?$(this):$(this).closest("table"),etbl;
			if(tbl.size()>0){
				if(typeof tbl.data(className)==="undefined"){
					etbl=new ExpandableTable(tbl,options.maxRow);
					if(this.nodeName.toLowerCase()!="table")
						$(this)
							.addClass("addRow"+etbl.seed)
							.data(className,etbl);
				}else{
					if(this.nodeName.toLowerCase()!="table")
						$(this)
							.addClass("addRow"+tbl.data(className).seed)
							.data(className,tbl.data(className));
				}
				if($(this).data(className)) {
					etbl=$(this).data(className);
				} 
				etbl.update().addCallBack=callBack;
				etbl.maxRow=options.maxRow;
				etbl.maxRow=options.maxRow;
				etbl.ignoreClass=options.ignoreClass;
				etbl.rowNumColumn=options.rowNumColumn;
				etbl.oddRowCSS=options.oddRowCSS;
				etbl.evenRowCSS=options.evenRowCSS;
				etbl.autoAddRow=options.autoAddRow;
				etbl.inputBoxAutoNumber=options.inputBoxAutoNumber;
				etbl.displayRowCountTo=options.displayRowCountTo;
			};
		});
		for(var i=0;i<ExpandableTableList.length;i++){
			if(!ExpandableTableList[i].goLive){
				ExpandableTableList[i].live();
			}
		}
	};
	$.fn.btnDelRow=function(func){
		var callBack=(func && $.isFunction(func)) ? func :null; 
		this.each(function(){
			var tbl=$(this).hide().closest("table");
			if(tbl.size()>0){
				if(typeof tbl.data(className)==="undefined"){
					var etbl=new ExpandableTable(tbl);
					$(this)
						.addClass("delRow"+etbl.seed)
						.data(className,etbl);
				}else{
					$(this)
						.addClass("delRow"+tbl.data(className).seed)
						.data(className,tbl.data(className));
				}
				if($(this).data(className)) {
					$(this)
					.data(className)
					.update().deleteCallBack=callBack;
				}
			}
		});
		for(var i=0;i<ExpandableTableList.length;i++){
			if(!ExpandableTableList[i].goLive){
				ExpandableTableList[i].live();
			}
		}
	};
})(jQuery);