/*CSS Menu*/
( function( $ ) {
$( document ).ready(function() {
$('#cssmenu').prepend('<div id="indicatorContainer"><div id="pIndicator"><div id="cIndicator"></div></div></div>');
	var activeElement = $('#cssmenu>ul>li:first');

	$('#cssmenu>ul>li').each(function() {
		if ($(this).hasClass('active')) {
			activeElement = $(this);
		}
	});


	var posLeft = activeElement.position().left;
	var elementWidth = activeElement.width();
	posLeft = posLeft + elementWidth/2 -6;
	if (activeElement.hasClass('has-sub')) {
		posLeft -= 6;
	}

	$('#cssmenu #pIndicator').css('left', posLeft);
	var element, leftPos, indicator = $('#cssmenu pIndicator');
	
	$("#cssmenu>ul>li").hover(function() {
		element = $(this);
		var w = element.width();
		if ($(this).hasClass('has-sub'))
		{
			leftPos = element.position().left + w/2 - 12;
		}
		else {
			leftPos = element.position().left + w/2 - 6;
		}

		$('#cssmenu #pIndicator').css('left', leftPos);
	}
	, function() {
		$('#cssmenu #pIndicator').css('left', posLeft);
	});

	$('#cssmenu>ul').prepend('<li id="menu-button"><a>Menu</a></li>');
	$( "#menu-button" ).click(function(){
			if ($(this).parent().hasClass('open')) {
				$(this).parent().removeClass('open');
			}
			else {
				$(this).parent().addClass('open');
			}
		});
});
});

/*Basic Admin Actions*/
function checkAll( n, fldName ) {
  if (!fldName) {
	 fldName = 'cb';
  }
	var f = document.adminForm;
	var c = f.toggle.checked;
	var n2 = 0;
	for (i=0; i < n; i++) {
		cb = eval( 'f.' + fldName + '' + i );
		if (cb) {
			cb.checked = c;
			n2++;
		}
	}
	if (c) {
		document.adminForm.boxchecked.value = n2;
	} else {
		document.adminForm.boxchecked.value = 0;
	}
}

function isChecked(isitchecked){
	if (isitchecked == true){
		document.adminForm.boxchecked.value++;
	}
	else {
		document.adminForm.boxchecked.value--;
	}
}

function submitbutton(pressbutton) {
	submitform(pressbutton);
}

function submitform(pressbutton){
	document.adminForm.task.value=pressbutton;
	try {
		document.adminForm.onsubmit();
		}
	catch(e){}
	document.adminForm.submit();
}

// LTrim(string) : Returns a copy of a string without leading spaces.
function ltrim(str)
{
   var whitespace = new String(" \t\n\r");
   var s = new String(str);
   if (whitespace.indexOf(s.charAt(0)) != -1) {
	  var j=0, i = s.length;
	  while (j < i && whitespace.indexOf(s.charAt(j)) != -1)
		 j++;
	  s = s.substring(j, i);
   }
   return s;
}

//RTrim(string) : Returns a copy of a string without trailing spaces.
function rtrim(str)
{
   var whitespace = new String(" \t\n\r");
   var s = new String(str);
   if (whitespace.indexOf(s.charAt(s.length-1)) != -1) {
	  var i = s.length - 1;       // Get length of string
	  while (i >= 0 && whitespace.indexOf(s.charAt(i)) != -1)
		 i--;
	  s = s.substring(0, i+1);
   }
   return s;
}

// Trim(string) : Returns a copy of a string without leading or trailing spaces
function trim(str) {
   return rtrim(ltrim(str));
}

function saveorder( n ) {
	checkAll_button( n );
}

//needed by saveorder function
function checkAll_button( n ) {
	for ( var j = 0; j <= n; j++ ) {
		box = eval( "document.adminForm.cb" + j );
		if ( box ) {
			if ( box.checked == false ) {
				box.checked = true;
			}
		} else {
			alert("You cannot change the order of items, as an item in the list is `Checked Out`");
			return;
		}
	}
	submitform('saveorder');
}
		
/*mosmsg*/
$(document).ready(function(){
	$("#message").dialog({
		autoOpen: true,
		draggable: false,
		minWidth: 150,
		minHeight: 30,
		show: {
			effect: "bounce",
			duration: 200
		},
		hide: {
			effect: "fade",
			duration: 100
			
		},
		open: function(event, ui) {
			setTimeout(function(){
				$('#message').dialog('close');                
			}, 3000);
}
	});
});

/*Hakkında window*/
$(document).ready(function() {
	$("#dialog").dialog({
	  autoOpen: false,
	  minWidth: 500,
	  minHeight: 200,
	  show: {
		effect: "blind",
		duration: 300
	  },
	  hide: {
		effect: "fade",
		duration: 500
	  }
	});
 
	$( "#opener" ).click(function() {
	  $( "#dialog" ).dialog( "open" );
	});
});

//şifremi unuttum penceresi
$(document).ready(function() {
	$("#forgotpass").dialog({
	  autoOpen: false,
	  minWidth: 500,
	  minHeight: 200,
	  show: {
		effect: "blind",
		duration: 300
	  },
	  hide: {
		effect: "fade",
		duration: 500
	  }
	});
 
	$( "#forgot" ).click(function() {
	  $( "#forgotpass" ).dialog( "open" );
	});
});

//hesap aktivasyon penceresi
$(document).ready(function() {
	$("#activation").dialog({
	  autoOpen: false,
	  minWidth: 500,
	  minHeight: 200,
	  show: {
		effect: "blind",
		duration: 300
	  },
	  hide: {
		effect: "fade",
		duration: 500
	  }
	});
 
	$( "#activ" ).click(function() {
	  $( "#activation" ).dialog( "open" );
	});
});

//profil resmi yükleme penceresi
$(document).ready(function() {
	$("#imagechange").dialog({
	  autoOpen: false,
	  minWidth: 500,
	  minHeight: 200,
	  show: {
		effect: "blind",
		duration: 300
	  },
	  hide: {
		effect: "fade",
		duration: 500
	  }
	});
 
	$( "#changeimg" ).click(function() {
	  $( "#imagechange" ).dialog( "open" );
	});
});

//parola değiştirme penceresi
$(document).ready(function() {
	$("#passchange").dialog({
	  autoOpen: false,
	  minWidth: 500,
	  minHeight: 200,
	  show: {
		effect: "blind",
		duration: 300
	  },
	  hide: {
		effect: "fade",
		duration: 500
	  }
	});
 
	$( "#changepass" ).click(function() {
	  $( "#passchange" ).dialog( "open" );
	});
});

/*datepicker türkçe dil desteği*/
jQuery(function($){
	$.datepicker.regional['tr'] = {
		closeText: 'kapat',
		prevText: '&#x3C;geri',
		nextText: 'ileri&#x3e',
		currentText: 'bugün',
		monthNames: ['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran',
		'Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'],
		monthNamesShort: ['Oca','Şub','Mar','Nis','May','Haz',
		'Tem','Ağu','Eyl','Eki','Kas','Ara'],
		dayNames: ['Pazar','Pazartesi','Salı','Çarşamba','Perşembe','Cuma','Cumartesi'],
		dayNamesShort: ['Pz','Pt','Sa','Ça','Pe','Cu','Ct'],
		dayNamesMin: ['Pz','Pt','Sa','Ça','Pe','Cu','Ct'],
		weekHeader: 'Hf',
		dateFormat: 'dd.mm.yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['tr']);
});

/*Aranabilir dropdown listesi yapmak için*/
jQuery(function($){
	var plugin=register("searchable");
	plugin.defaults={
		maxListSize:100,
		maxMultiMatch:50,
		exactMatch:false,
		wildcards:true,
		ignoreCase:true,
		warnMultiMatch:"top {0} eşleşme ...",
		warnNoMatch:"eşleşme bulunamadı ...",
		latency:200,zIndex:"auto"};
		
		plugin.execute=function(settings,zindex){
			if($.browser.msie&&parseInt(jQuery.browser.version)<7){
				return this;
			}
			if(this.nodeName!="SELECT"||this.size>1){
				return this;
			}
			
			var self=$(this);
			var storage={index:-1,options:null};
			var idxAttr="lang";
			var enabled=false;
			$.browser.chrome=/chrome/.test(navigator.userAgent.toLowerCase());
			
			if($.browser.chrome){
				$.browser.safari=false;
			}
			if($.meta){
				settings=$.extend({},options,self.data()
				);
			}
			var wrapper=$("<div/>");
			var overlay=$("<div/>");
			var input=$("<input/>");
			var selector=$("<select/>");
			var topMatchItem=$("<option>"+settings.warnMultiMatch.replace(/\{0\}/g,settings.maxMultiMatch)+"</option>").attr("disabled","true");
			var noMatchItem=$("<option>"+settings.warnNoMatch+"</option>").attr("disabled","true");
			var selectorHelper={option:function(idx){
				return $(selector.get(0).options[idx]);
			},
			selected:function(){
				return selector.find(":selected");},
				selectedIndex:function(idx){
					if(idx>-1){selector.get(0).selectedIndex=idx;}return selector.get(0).selectedIndex;
				},
				size:function(size){
					selector.attr("size",Math.max(2,Math.min(size,20)));
				},
				reset:function(){
					if((self.get(0).selectedIndex-1)==self.data("index")){return;}
					var idx=self.get(0).selectedIndex;
					var len=self.get(0).length;var mc=Math.floor(settings.maxMultiMatch/2);
					var begin=Math.max(1,(idx-mc));
					var end=Math.min(len,Math.max(settings.maxMultiMatch,(idx+mc)));
					var si=idx-begin;selector.empty();selectorHelper.size(end-begin);
					
					for(var i=begin;i<end;i++){
						selector.append($(self.get(0).options[i]).clone().attr(idxAttr,i-1));
					}
					if(end>settings.maxMultiMatch){
						selector.append(topMatchItem);
					}
					selector.get(0).selectedIndex=si;
				}
			};
			draw();
			var suspendBlur=false;
			overlay.mouseover(function(){suspendBlur=true;});
			overlay.mouseout(function(){suspendBlur=false;});
			selector.mouseover(function(){suspendBlur=true;});
			selector.mouseout(function(){suspendBlur=false;});
			input.click(function(e){
				if(!enabled){
					enable(e,true);
				}else{
					disable(e,true);
				}
			});
			input.blur(function(e){
				if(!suspendBlur&&enabled){
					disable(e,true);
				}
			});
			self.keydown(function(e){
				if(e.keyCode!=9){
					enable(e,false,true);
				}
			});
			self.click(function(e){
				selector.focus();
			});
			selector.click(function(e){
				if(selectorHelper.selectedIndex()<0){return;}
				disable(e);
			});
			selector.focus(function(e){
				input.focus();
			});
			selector.blur(function(e){
				if(!suspendBlur){disable(e,true);}
			});
			selector.mousemove(function(e){
				if($.browser.opera&&parseFloat(jQuery.browser.version)>=9.8){return true;}
				var fs=Math.floor(parseFloat(/([0-9\.]+)px/.exec(selectorHelper.option(0).css("font-size"))));
				var fsdiff=4;
				if($.browser.opera){fsdiff=2.5;}
				if($.browser.safari||$.browser.chrome){
					fsdiff=3;
				}
				fs+=Math.round(fs/fsdiff);
				selectorHelper.selectedIndex(Math.floor((e.pageY-selector.offset().top+this.scrollTop)/fs));
			
			});
			overlay.click(function(e){
				input.click();
			});
			input.keyup(function(e){
				if(jQuery.inArray(e.keyCode,new Array(9,13,16,33,34,35,36,38,40))>-1){return true;}
				search=$.trim(input.val().toLowerCase());
				clearSearchTimer();
				timer=setTimeout(searching,settings.latency);
			});
			input.keydown(function(e){
				if(e.shiftKey||e.ctrlKey||e.altKey){return;}
				
				switch(e.keyCode){
					case 9:disable(e);moveTab(self,e.shiftKey?-1:1);break;
					case 13:disable(e);self.focus();break;
					case 27:disable(e,true);self.focus();break;
					case 33:if(selectorHelper.selectedIndex()-selector.attr("size")>0){selectorHelper.selectedIndex(selectorHelper.selectedIndex()-=selector.attr("size"));}else{selectorHelper.selectedIndex(0);}synchronize();break;
					case 34:if(selectorHelper.selectedIndex()+selector.attr("size")<selector.get(0).options.length-1){selectorHelper.selectedIndex(selectorHelper.selectedIndex()+=selector.attr("size"));}else{selectorHelper.selectedIndex(selector.get(0).options.length-1);}synchronize();break;
					case 38:if(selectorHelper.selectedIndex()>0){selectorHelper.selectedIndex(selectorHelper.selectedIndex()-1);synchronize();}break;
					case 40:if(selectorHelper.selectedIndex()<selector.get(0).options.length-1){selectorHelper.selectedIndex(selectorHelper.selectedIndex()+1);synchronize();}break;
					default:return true;
				}
				return false;
			});
			
			function draw(){
				self.css("text-decoration","none");
				self.width(self.outerWidth());
				self.height(self.outerHeight());
				wrapper.css("position","relative");
				wrapper.css("width",self.outerWidth());
				
				if($.browser.msie){
					wrapper.css("z-index",zindex);
				}
				overlay.css({
					"position":"absolute",
					"top":0,
					"left":0,
					"width":self.outerWidth(),
					"height":self.outerHeight(),
					"background-color":"#FFFFFF",
					"opacity":"0.01"
				});
				input.attr("type","text");
				input.hide();
				input.height(self.outerHeight());
				input.css({
					"position":"absolute",
					"top":0,
					"left":0,
					"margin":"0px",
					"padding":"0px",
					"outline-style":"none",
					"border-style":"solid",
					"border-bottom-style":"none",
					"border-color":"transparent",
					"background-color":"transparent"
				});
				
				var sty=new Array();
				sty.push("border-left-width");
				sty.push("border-top-width");
				sty.push("font-size");
				sty.push("font-stretch");
				sty.push("font-variant");
				sty.push("font-weight");
				sty.push("color");
				sty.push("text-align");
				sty.push("text-indent");
				sty.push("text-shadow");
				sty.push("text-transform");
				sty.push("padding-left");
				sty.push("padding-top");
				
				for(var i=0;i<sty.length;i++){
					input.css(sty[i],self.css(sty[i]));
				}
				if($.browser.msie&&parseInt(jQuery.browser.version)<8){
					input.css("padding","0px");
					input.css("padding-left","3px");
					input.css("border-left-width","2px");
					input.css("border-top-width","3px");
				}else{
					if($.browser.chrome){
						input.height(self.innerHeight());
						input.css("text-transform","none");
						input.css("padding-left",parseFloatPx(input.css("padding-left"))+3);
						input.css("padding-top",2);
					}else{
						if($.browser.safari){
							input.height(self.innerHeight());
							input.css("padding-top",2);
							input.css("padding-left",3);
							input.css("text-transform","none");
						}else{
							if($.browser.opera){
								input.height(self.innerHeight());
								var pl=parseFloatPx(self.css("padding-left"));
								input.css("padding-left",pl==1?pl+1:pl);
								input.css("padding-top",0);
							}else{
								if($.browser.mozilla){
									input.css("padding-top","0px");
									input.css("border-top","0px");
									input.css("padding-left",parseFloatPx(self.css("padding-left"))+3);
								}else{
									input.css("padding-left",parseFloatPx(self.css("padding-left"))+3);
									input.css("padding-top",parseFloatPx(self.css("padding-top"))+1);
								}
							}
						}
					}
				}
				
				var offset=parseFloatPx(self.css("padding-left"))+parseFloatPx(self.css("padding-right"))+parseFloatPx(self.css("border-left-width"))+parseFloatPx(self.css("border-left-width"))+23;
				input.width(self.outerWidth()-offset);
				var w=self.css("width");
				var ow=self.outerWidth();self.css("width","auto");
				var ow=ow>self.outerWidth()?ow:self.outerWidth();
				self.css("width",w);
				selector.hide();
				selectorHelper.size(self.get(0).length);
				selector.css({"position":"absolute","top":self.outerHeight(),"left":0,"width":ow,"border":"1px solid #333","font-weight":"normal","padding":0,"background-color":self.css("background-color"),"text-transform":self.css("text-transform")});
				var zIndex=/^\d+$/.test(self.css("z-index"))?self.css("z-index"):1;
				
				if(settings.zIndex&&/^\d+$/.test(settings.zIndex)){zIndex=settings.zIndex;}overlay.css("z-index",(zIndex).toString(10));
				input.css("z-index",(zIndex+1).toString(10));
				selector.css("z-index",(zIndex+2).toString(10));
				self.wrap(wrapper);
				self.after(overlay);
				self.after(input);
				self.after(selector);
			}
			
			function enable(e,s,v){
				if(self.attr("disabled")){return false;}self.prepend("<option />");
				if(typeof v=="undefined"){enabled=!enabled;}selectorHelper.reset();
				synchronize();
				store();
				if(s){selector.show();}input.show();
				input.focus();
				input.select();
				self.get(0).selectedIndex=0;
				
				if(typeof e!="undefined"){e.stopPropagation();}}
				
			function disable(e,rs){
				enabled=false;self.find(":first").remove();
				clearSearchTimer();
				input.hide();
				selector.hide();
				if(typeof rs!="undefined"){restore();}
				populate();
				if(typeof e!="undefined"){e.stopPropagation();}}
				
			function clearSearchTimer(){if(timer!=null){clearTimeout(timer);}}
			
			function populate(){
				if(selectorHelper.selectedIndex()<0||selectorHelper.selected().get(0).disabled){return;}self.get(0).selectedIndex=parseInt(selector.find(":selected").attr(idxAttr));
				
				self.change();
				self.data("index",new Number(self.get(0).selectedIndex));}
				
			function synchronize(){
				if(selectorHelper.selectedIndex()>-1&&!selectorHelper.selected().get(0).disabled){
					input.val(selector.find(":selected").text());
				}else{
					input.val(self.find(":selected").text());
				}
			}
			function store(){
				storage.index=selectorHelper.selectedIndex();
				storage.options=new Array();
				
				for(var i=0;i<selector.get(0).options.length;i++){
					storage.options.push(selector.get(0).options[i]);
				}
			}
			function restore(){
				selector.empty();
				for(var i=0;i<storage.options.length;i++){
					selector.append(storage.options[i]);
				}
				selectorHelper.selectedIndex(storage.index);
				selectorHelper.size(storage.options.length);
			}
			
			function moveTab(jqe,steps){
				var fields=jqe.parents("form,body").eq(0).find("button,input[type!=hidden],textarea,select");
				var index=fields.index(jqe);
				
				if(index>-1&&index+steps<fields.length&&index+steps>=0){
					fields.eq(index+steps).focus();
					return true;
				}
				return false;
			}
			function escapeRegExp(str){
				var specials=["/",".","*","+","?","|","(",")","[","]","{","}","\\","^","$"];
				var regexp=new RegExp("(\\"+specials.join("|\\")+")","g");
				return str.replace(regexp,"\\$1");
			}
			var timer=null;
			var searchCache;
			var search;
			
			function searching(){
				if(searchCache==search){
					timer=null;
					return;
				}
				var matches=0;
				searchCache=search;
				selector.hide();
				selector.empty();
				var regexp=escapeRegExp(search);
				
				if(settings.exactMatch){
					regexp="^"+regexp;
				}
				if(settings.wildcards){
					regexp=regexp.replace(/\\\*/g,".*");
					regexp=regexp.replace(/\\\?/g,".");
				}
				
				var flags;
				if(settings.ignoreCase){flags="i";}
				
				search=new RegExp(regexp,flags);
				for(var i=1;i<self.get(0).length&&matches<settings.maxMultiMatch;i++){
					if(search.length==0||search.test(self.get(0).options[i].text)){
						var opt=$(self.get(0).options[i]).clone().attr(idxAttr,i-1);
						
						if(self.data("index")==i){
							opt.text(self.data("text"));
						}
						selector.append(opt);
						matches++;
					}
				}
				if(matches>=1){
					selectorHelper.selectedIndex(0);
				}else{
					if(matches==0){
						selector.append(noMatchItem);
					}
				}
				if(matches>=settings.maxMultiMatch){
					selector.append(topMatchItem);
				}
				selectorHelper.size(matches);
				selector.show();
				timer=null;
			}
			
			function parseFloatPx(value){
				try{
					value=parseFloat(value.replace(/[\s]*px/,""));
					if(!isNaN(value)){
						return value;
					}
				}
				catch(e){}return 0;
			}
			return;
		};
		function register(nsp){
			var plugin=$[nsp]={};
			$.fn[nsp]=function(settings){settings=$.extend(plugin.defaults,settings);
			var elmSize=this.size();
			return this.each(function(index){
				plugin.execute.call(this,settings,elmSize-index);
			});
			};
			return plugin;
		}
});

$(document).ready(function() {
	$("#myselect").searchable();
});

//soner ekledi
var matched, browser;

jQuery.uaMatch = function( ua ) {
	ua = ua.toLowerCase();

	var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
		/(webkit)[ \/]([\w.]+)/.exec( ua ) ||
		/(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
		/(msie) ([\w.]+)/.exec( ua ) ||
		ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
		[];

	return {
		browser: match[ 1 ] || "",
		version: match[ 2 ] || "0"
	};
};

matched = jQuery.uaMatch( navigator.userAgent );
browser = {};

if ( matched.browser ) {
	browser[ matched.browser ] = true;
	browser.version = matched.version;
}

// Chrome is Webkit, but Webkit is also Safari.
if ( browser.chrome ) {
	browser.webkit = true;
} else if ( browser.webkit ) {
	browser.safari = true;
}

jQuery.browser = browser;

//***Taggd JS***//
/*!
 * jQuery Taggd
 * A helpful plugin that helps you adding 'tags' on images.
 *
 * License: MIT
 */

(function($) {
	'use strict';
	
	var defaults = {
		edit: true,
		
		align: {
			x: 'center',
			y: 'center'
		},

		handlers: {},

		offset: {
			left: 0,
			top: 0
		},
		
		strings: {
			save: '&#x2713;',
			delete: '&#x00D7;'
		}
	};
	
	var methods = {
		show: function() {
			var $this = $(this),
				$label = $this.next();
			
			$this.addClass('active');
			$label.addClass('show').find('input').focus();
		},
		
		hide: function() {
			var $this = $(this);
			
			$this.removeClass('active');
			$this.next().removeClass('show');
		},
		
		toggle: function() {
			var $hover = $(this).next();
			
			if($hover.hasClass('show')) {
				methods.hide.call(this);
			} else {
				methods.show.call(this);
			}
		}
	};
	
	
	/****************************************************************
	 * TAGGD
	 ****************************************************************/
	
	var Taggd = function(element, options, data) {
		var _this = this;
		
		if(options.edit) {
			options.handlers = {
				click: function() {
					_this.hide();
					methods.show.call(this);
				}
			};
		}
		
		this.element = $(element);
		this.options = $.extend(true, {}, defaults, options);
		this.data = data;
		this.initialized = false;
		
		if(!this.element.height() || !this.element.width()) {
			this.element.on('load', _this.initialize.bind(this));
		} else this.initialize();
	};
	
	
	/****************************************************************
	 * INITIALISATION
	 ****************************************************************/
	
	Taggd.prototype.initialize = function() {
		var _this = this;
		
		this.initialized = true;
		
		this.initWrapper();
		this.addDOM();
		
		if(this.options.edit) {
			this.element.on('click', function(e) {
				var poffset = $(this).parent().offset(),
					x = (e.pageX - poffset.left) / _this.element.width(),
					y = (e.pageY - poffset.top) / _this.element.height();

				_this.addData({
					x: x,
					y: y,
					text: ''
				});

				_this.show(_this.data.length - 1);
			});
		}
		
		$(window).resize(function() {
			_this.updateDOM();
		});
	};
	
	Taggd.prototype.initWrapper = function() {
		var wrapper = $('<div class="taggd-wrapper" />');
		this.element.wrap(wrapper);
		
		this.wrapper = this.element.parent('.taggd-wrapper');
	};
	
	Taggd.prototype.alterDOM = function() {
		var _this = this;
		
		this.wrapper.find('.taggd-item-hover').each(function() {
			var $e = $(this),
				
				$input = $('<input type="text" size="16" />')
					.val($e.text()),
				$button_ok = $('<button />')
					.html(_this.options.strings.save),
				$button_delete = $('<button />')
					.html(_this.options.strings.delete);
			
			$button_ok.on('click', function() {
				_this.hide();
			});
			
			$button_delete.on('click', function() {
				var x = $e.attr('data-x'),
					y = $e.attr('data-y');
				
				_this.data = $.grep(_this.data, function(v) {
					return v.x != x || v.y != y;
				});
				
				_this.addDOM();
				_this.element.triggerHandler('change');
			});
			
			$input.on('change', function() {
				var x = $e.attr('data-x'),
					y = $e.attr('data-y'),
					item = $.grep(_this.data, function(v) {
						return v.x == x && v.y == y;
					}).pop();
				
				if(item) item.text = $input.val();
				
				_this.addDOM();
				_this.element.triggerHandler('change');
			});
			
			$e.empty().append($input, $button_ok, $button_delete);
		});
		
		_this.updateDOM();
	};
	
	/****************************************************************
	 * DATA MANAGEMENT
	 ****************************************************************/
	
	Taggd.prototype.addData = function(data) {
		if($.isArray(data)) {
			this.data = $.merge(this.data, data);
		} else {
			this.data.push(data);
		}
		
		if(this.initialized) {
			this.addDOM();
			this.element.triggerHandler('change');
		}
	};
	
	Taggd.prototype.setData = function(data) {
		this.data = data;
		
		if(this.initialized) {
			this.addDOM();
		}
	};
	
	Taggd.prototype.clear = function() {
		if(!this.initialized) return;
		this.wrapper.find('.taggd-item, .taggd-item-hover').remove();
	};
	
	
	/****************************************************************
	 * EVENTS
	 ****************************************************************/
	
	Taggd.prototype.on = function(event, handler) {
		if(
			typeof event !== 'string' ||
			typeof handler !== 'function'
		) return;
		
		this.element.on(event, handler);
	};
	
	
	/****************************************************************
	 * TAGS MANAGEMENT
	 ****************************************************************/
	
	Taggd.prototype.iterateTags = function(a, yep) {
		var func;
		
		if($.isNumeric(a)) {
			func = function(i, e) { return a === i; };
		} else if(typeof a === 'string') {
			func = function(i, e) { return $(e).is(a); }
		} else if($.isArray(a)) {
			func = function(i, e) {
				var $e = $(e);
				var result = false;
				
				$.each(a, function(ai, ae) {
					if(
						i === ai ||
						e === ae ||
						$e.is(ae)
					) {
						result = true;
						return false;
					}
				});
				
				return result;
			}
		} else if(typeof a === 'object') {
			func = function(i, e) {
				var $e = $(e);
				return $e.is(a);
			};
		} else if($.isFunction(a)) {
			func = a;
		} else if(!a) {
			func = function() { return true; }
		} else return this;
		
		this.wrapper.find('.taggd-item').each(function(i, e) {
			if(typeof yep === 'function' && func.call(this, i, e)) {
				yep.call(this, i, e);
			}
		});
		
		return this;
	};
	
	Taggd.prototype.show = function(a) {
		return this.iterateTags(a, methods.show);
	};
	
	Taggd.prototype.hide = function(a) {
		return this.iterateTags(a, methods.hide);
	};
	
	Taggd.prototype.toggle = function(a) {
		return this.iterateTags(a, methods.toggle);
	};
	
	/****************************************************************
	 * CLEANING UP
	 ****************************************************************/
	
	Taggd.prototype.dispose = function() {
		this.clear();
		this.element.unwrap(this.wrapper);
	};
	
	
	/****************************************************************
	 * SEMI-PRIVATE
	 ****************************************************************/
	
	Taggd.prototype.addDOM = function() {
		var _this = this;
		
		this.clear();
		this.element.css({ height: 'auto', width: 'auto' });
		
		var height = this.element.height();
		var width = this.element.width();
		
		$.each(this.data, function(i, v) {
			var $item = $('<span />');
			var $hover;
			
			if(
				v.x > 1 && v.x % 1 === 0 &&
				v.y > 1 && v.y % 1 === 0
			) {
				v.x = v.x / width;
				v.y = v.y / height;
			}
			
			if(typeof v.attributes === 'object') {
				$item.attr(v.attributes);
			}
			
			$item.attr({
				'data-x': v.x,
				'data-y': v.y
			});
			
			$item.css('position', 'absolute');
			$item.addClass('taggd-item');
			
			_this.wrapper.append($item);
			
			if(typeof v.text === 'string' && (v.text.length > 0 || _this.options.edit)) {
				$hover = $('<span class="taggd-item-hover" style="position: absolute;" />').html(v.text);
				
				$hover.attr({
					'data-x': v.x,
					'data-y': v.y
				});
				
				_this.wrapper.append($hover);
			}
			
			if(typeof _this.options.handlers === 'object') {
				$.each(_this.options.handlers, function(event, func) {
					var handler;
					
					if(typeof func === 'string' && methods[func]) {
						handler = methods[func];
					} else if(typeof func === 'function') {
						handler = func;
					}
					
					$item.on(event, function(e) {
						if(!handler) return;
						handler.call($item, e, _this.data[i]);
					});
				});
			}
		});
		
		this.element.removeAttr('style');
		
		if(this.options.edit) {
			this.alterDOM();
		}
		
		this.updateDOM();
	};
	
	Taggd.prototype.updateDOM = function() {
		var _this = this;
		
		this.wrapper.removeAttr('style').css({
			height: this.element.height(),
			width: this.element.width()
		});
		
		this.wrapper.find('span').each(function(i, e) {
			var $el = $(e);
			
			var left = $el.attr('data-x') * _this.element.width();
			var top = $el.attr('data-y') * _this.element.height();
			
			if($el.hasClass('taggd-item')) {
				$el.css({
					left: left - $el.outerWidth(true) / 2,
					top: top - $el.outerHeight(true) / 2
				});
			} else if($el.hasClass('taggd-item-hover')) {
				if(_this.options.align.x === 'center') {
					left -= $el.outerWidth(true) / 2;
				} else if(_this.options.align.x === 'right') {
					left -= $el.outerWidth(true);
				}
				
				if(_this.options.align.y === 'center') {
					top -= $el.outerHeight(true) / 2;
				} else if(_this.options.align.y === 'bottom') {
					top -= $el.outerHeight(true);
				}
				
				$el.attr('data-align', $el.outerWidth(true));
				
				$el.css({
					left: left + _this.options.offset.left,
					top: top + _this.options.offset.top
				});
			}
		});
	};
	
	
	/****************************************************************
	 * JQUERY LINK
	 ****************************************************************/
	
	$.fn.taggd = function(options, data) {
		return new Taggd(this, options, data);
	};
})(jQuery);