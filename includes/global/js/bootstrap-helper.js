/**
* bootstrap-formhelpers.js v2.3.0 by @vincentlamanna
* Copyright 2013 Vincent Lamanna
* http://www.apache.org/licenses/LICENSE-2.0
*/
if(!jQuery)throw new Error("Bootstrap Form Helpers requires jQuery");

BFHMonthsList=["Ocak","Şubat","Mart","Nisan","Mayıs","Haziran","Temmuz","Ağustos","Eylül","Ekim","Kasım","Aralık"],

BFHDaysList=["PAZ","PZT","SAL","ÇAR","PER","CUM","CMT"],

BFHDayOfWeekStart=1,

BFHPhoneFormatList={AF:"+93 0dd ddd dddd",AL:"+355 0dd ddd ddd",DZ:"+213 0ddd dd dd dd",AS:"+1 (ddd) ddd-dddd",AD:"+376 ddddddddd",AO:"+244 ddd ddd ddd",AI:"+1 (ddd) ddd-dddd",AQ:"+672 ddddddddd",AG:"+1 (ddd) ddd-dddd",AR:"+54 ddddddddd",AM:"+374 0dd dddddd",AW:"+297 ddd dddd",AU:"+61 ddd ddd ddd",AT:"+43 0dddd ddddddddd",AZ:"+994 ddddddddd",BH:"+973 ddddddddd",BD:"+880 ddddddddd",BB:"+1 ddddddddd",BY:"+375 ddddddddd",BE:"+32 ddddddddd",BZ:"+501 ddddddddd",BJ:"+229 ddddddddd",BM:"+1 (ddd) ddd-dddd",BT:"+975 ddddddddd",BO:"+591 ddddddddd",BA:"+387 ddddddddd",BW:"+267 ddddddddd",BV:"+0 ddddddddd",BR:"+55 ddddddddd",IO:"+0 ddddddddd",VG:"+1 (ddd) ddd-dddd",BN:"+673 ddddddddd",BG:"+359 ddddddddd",BF:"+226 ddddddddd",BI:"+257 ddddddddd",CI:"+225 ddddddddd",KH:"+855 ddddddddd",CM:"+237 ddddddddd",CA:"+1 (ddd) ddd-dddd",CV:"+238 ddddddddd",KY:"+1 (ddd) ddd-dddd",CF:"+236 ddddddddd",TD:"+235 ddddddddd",CL:"+56 ddddddddd",CN:"+86 ddddddddd",CX:"+61 ddddddddd",CC:"+61 ddddddddd",CO:"+57 ddddddddd",KM:"+269 ddddddddd",CG:"+242 ddddddddd",CK:"+682 ddddddddd",CR:"+506 ddddddddd",HR:"+385 ddddddddd",CU:"+53 ddddddddd",CY:"+357 ddddddddd",CZ:"+420 ddddddddd",CD:"+243 ddddddddd",DK:"+45 ddddddddd",DJ:"+253 ddddddddd",DM:"+1 (ddd) ddd-dddd",DO:"+1 (ddd) ddd-dddd",TL:"+670 ddddddddd",EC:"+593 ddddddddd",EG:"+20 ddddddddd",SV:"+503 ddddddddd",GQ:"+240 ddddddddd",ER:"+291 ddddddddd",EE:"+372 ddddddddd",ET:"+251 ddddddddd",FO:"+298 ddddddddd",FK:"+500 ddddddddd",FJ:"+679 ddddddddd",FI:"+358 ddddddddd",MK:"+389 ddddddddd",FR:"+33 d dd dd dd dd",GF:"+594 ddddddddd",PF:"+689 ddddddddd",TF:"+262 ddddddddd",GA:"+241 ddddddddd",GE:"+995 ddddddddd",DE:"+49 ddddddddd",GH:"+233 ddddddddd",GI:"+350 ddddddddd",GR:"+30 ddddddddd",GL:"+299 ddddddddd",GD:"+1 (ddd) ddd-dddd",GP:"+590 ddddddddd",GU:"+1 (ddd) ddd-dddd",GT:"+502 ddddddddd",GN:"+224 ddddddddd",GW:"+245 ddddddddd",GY:"+592 ddddddddd",HT:"+509 ddddddddd",HM:"+0 ddddddddd",HN:"+504 ddddddddd",HK:"+852 ddddddddd",HU:"+36 ddddddddd",IS:"+354 ddddddddd",IN:"+91 ddddddddd",ID:"+62 ddddddddd",IR:"+98 ddddddddd",IQ:"+964 ddddddddd",IE:"+353 ddddddddd",IL:"+972 ddddddddd",IT:"+39 ddddddddd",JM:"+1 (ddd) ddd-dddd",JP:"+81 ddddddddd",JO:"+962 ddddddddd",KZ:"+7 ddddddddd",KE:"+254 ddddddddd",KI:"+686 ddddddddd",KW:"+965 ddddddddd",KG:"+996 ddddddddd",LA:"+856 ddddddddd",LV:"+371 ddddddddd",LB:"+961 ddddddddd",LS:"+266 ddddddddd",LR:"+231 ddddddddd",LY:"+218 ddddddddd",LI:"+423 ddddddddd",LT:"+370 ddddddddd",LU:"+352 ddddddddd",MO:"+853 ddddddddd",MG:"+261 ddddddddd",MW:"+265 ddddddddd",MY:"+60 ddddddddd",MV:"+960 ddddddddd",ML:"+223 ddddddddd",MT:"+356 ddddddddd",MH:"+692 ddddddddd",MQ:"+596 ddddddddd",MR:"+222 ddddddddd",MU:"+230 ddddddddd",YT:"+262 ddddddddd",MX:"+52 ddddddddd",FM:"+691 ddddddddd",MD:"+373 ddddddddd",MC:"+377 ddddddddd",MN:"+976 ddddddddd",MS:"+1 (ddd) ddd-dddd",MA:"+212 ddddddddd",MZ:"+258 ddddddddd",MM:"+95 ddddddddd",NA:"+264 ddddddddd",NR:"+674 ddddddddd",NP:"+977 ddddddddd",NL:"+31 ddddddddd",AN:"+599 ddddddddd",NC:"+687 ddddddddd",NZ:"+64 ddddddddd",NI:"+505 ddddddddd",NE:"+227 ddddddddd",NG:"+234 ddddddddd",NU:"+683 ddddddddd",NF:"+672 ddddddddd",KP:"+850 ddddddddd",MP:"+1 (ddd) ddd-dddd",NO:"+47 ddddddddd",OM:"+968 ddddddddd",PK:"+92 ddddddddd",PW:"+680 ddddddddd",PA:"+507 ddddddddd",PG:"+675 ddddddddd",PY:"+595 ddddddddd",PE:"+51 ddddddddd",PH:"+63 ddddddddd",PN:"+870 ddddddddd",PL:"+48 ddddddddd",PT:"+351 ddddddddd",PR:"+1 (ddd) ddd-dddd",QA:"+974 ddddddddd",RE:"+262 ddddddddd",RO:"+40 ddddddddd",RU:"+7 ddddddddd",RW:"+250 ddddddddd",ST:"+239 ddddddddd",SH:"+290 ddddddddd",KN:"+1 (ddd) ddd-dddd",LC:"+1 (ddd) ddd-dddd",PM:"+508 ddddddddd",VC:"+1 (ddd) ddd-dddd",WS:"+685 ddddddddd",SM:"+378 ddddddddd",SA:"+966 ddddddddd",SN:"+221 ddddddddd",SC:"+248 ddddddddd",SL:"+232 ddddddddd",SG:"+65 ddddddddd",SK:"+421 ddddddddd",SI:"+386 ddddddddd",SB:"+677 ddddddddd",SO:"+252 ddddddddd",ZA:"+27 ddddddddd",GS:"+0 ddddddddd",KR:"+82 ddddddddd",ES:"+34 ddddddddd",LK:"+94 ddddddddd",SD:"+249 ddddddddd",SR:"+597 ddddddddd",SJ:"+0 ddddddddd",SZ:"+268 ddddddddd",SE:"+46 ddddddddd",CH:"+41 ddddddddd",SY:"+963 ddddddddd",TW:"+886 ddddddddd",TJ:"+992 ddddddddd",TZ:"+255 ddddddddd",TH:"+66 ddddddddd",BS:"+1 (ddd) ddd-dddd",GM:"+220 ddddddddd",TG:"+228 ddddddddd",TK:"+690 ddddddddd",TO:"+676 ddddddddd",TT:"+1 (ddd) ddd-dddd",TN:"+216 ddddddddd",TR:"+90 ddddddddd",TM:"+993 ddddddddd",TC:"+1 (ddd) ddd-dddd",TV:"+688 ddddddddd",VI:"+1 (ddd) ddd-dddd",UG:"+256 ddddddddd",UA:"+380 ddddddddd",AE:"+971 ddddddddd",GB:"+44 (ddd) dddd dddd",US:"+1 (ddd) ddd-dddd",UM:"+0 ddddddddd",UY:"+598 ddddddddd",UZ:"+998 ddddddddd",VU:"+678 ddddddddd",VA:"+39 ddddddddd",VE:"+58 ddddddddd",VN:"+84 ddddddddd",WF:"+681 ddddddddd",EH:"+0 ddddddddd",YE:"+967 ddddddddd",YU:"+0 ddddddddd",ZM:"+260 ddddddddd",ZW:"+263 ddddddddd"},

BFHTimePickerDelimiter=":",

BFHTimePickerModes={am:"AM",pm:"PM"},


/**
* Color Picker
*/
+function ($) {

  'use strict';
  
  
  /* COLORPICKER CLASS DEFINITION
  * ========================= */

  var toggle = '[data-toggle=bfh-colorpicker]',
	  BFHColorPicker = function (element, options) {
		this.options = $.extend({}, $.fn.bfhcolorpicker.defaults, options);
		this.$element = $(element);

		this.initPopover();
	  };

  BFHColorPicker.prototype = {

	constructor: BFHColorPicker,

	initPalette: function() {
	  var $canvas,
		  context,
		  gradient;
		  
	  $canvas = this.$element.find('canvas');
	  context = $canvas[0].getContext('2d');
	  
	  gradient = context.createLinearGradient(0, 0, $canvas.width(), 0);
	  
	  gradient.addColorStop(0,    'rgb(255, 255, 255)');
	  gradient.addColorStop(0.1,  'rgb(255,   0,   0)');
	  gradient.addColorStop(0.25, 'rgb(255,   0, 255)');
	  gradient.addColorStop(0.4,  'rgb(0,     0, 255)');
	  gradient.addColorStop(0.55, 'rgb(0,   255, 255)');
	  gradient.addColorStop(0.7,  'rgb(0,   255,   0)');
	  gradient.addColorStop(0.85, 'rgb(255, 255,   0)');
	  gradient.addColorStop(1,    'rgb(255,   0,   0)');
	  
	  context.fillStyle = gradient;
	  context.fillRect(0, 0, context.canvas.width, context.canvas.height);
	  
	  gradient = context.createLinearGradient(0, 0, 0, $canvas.height());
	  gradient.addColorStop(0,   'rgba(255, 255, 255, 1)');
	  gradient.addColorStop(0.5, 'rgba(255, 255, 255, 0)');
	  gradient.addColorStop(0.5, 'rgba(0,     0,   0, 0)');
	  gradient.addColorStop(1,   'rgba(0,     0,   0, 1)');
	  
	  context.fillStyle = gradient;
	  context.fillRect(0, 0, context.canvas.width, context.canvas.height);
	},
	
	initPopover: function() {
	  var iconLeft,
		  iconRight;

	  iconLeft = '';
	  iconRight = '';
	  if (this.options.align === 'right') {
		iconRight = '<span class="input-group-addon"><span class="bfh-colorpicker-icon"></span></span>';
	  } else {
		iconLeft = '<span class="input-group-addon"><span class="bfh-colorpicker-icon"></span></span>';
	  }

	  this.$element.html(
		'<div class="input-group bfh-colorpicker-toggle" data-toggle="bfh-colorpicker">' +
		iconLeft +
		'<input type="text" name="' + this.options.name + '" class="' + this.options.input + '" placeholder="' + this.options.placeholder + '" readonly>' +
		iconRight +
		'</div>' +
		'<div class="bfh-colorpicker-popover">' +
		'<canvas class="bfh-colorpicker-palette" width="384" height="256"></canvas>' +
		'</div>'
	  );

	  this.$element
		.on('click.bfhcolorpicker.data-api touchstart.bfhcolorpicker.data-api', toggle, BFHColorPicker.prototype.toggle)
		.on('mousedown.bfhcolorpicker.data-api', 'canvas', BFHColorPicker.prototype.mouseDown)
		.on('click.bfhcolorpicker.data-api touchstart.bfhcolorpicker.data-api', '.bfh-colorpicker-popover', function() { return false; });

	  this.initPalette();
	  
	  this.$element.val(this.options.color);
	},
	
	updateVal: function(positionX, positionY) {
	  var $canvas,
		  context,
		  colorX,
		  colorY,
		  snappiness,
		  imageData,
		  newColor;
	  
	  snappiness = 5;
	  
	  $canvas = this.$element.find('canvas');
	  context = $canvas[0].getContext('2d');
	  
	  colorX = positionX - $canvas.offset().left;
	  colorY = positionY - $canvas.offset().top;
	  
	  colorX = Math.round(colorX / snappiness) * snappiness;
	  colorY = Math.round(colorY / snappiness) * snappiness;
	  
	  if (colorX < 0) {
		colorX = 0;
	  }
	  if (colorX >= $canvas.width()) {
		colorX = $canvas.width() - 1;
	  }
	  
	  if (colorY < 0) {
		colorY = 0;
	  }
	  if (colorY > $canvas.height()) {
		colorY = $canvas.height();
	  }
	  
	  imageData = context.getImageData(colorX, colorY, 1, 1);
	  newColor = rgbToHex(imageData.data[0], imageData.data[1], imageData.data[2]);
	  
	  if (newColor !== this.$element.val()) {
		this.$element.val(newColor);
		
		this.$element.trigger('change.bfhcolorpicker');
	  }
	},
	
	mouseDown: function(e) {
	  var $this,
		  $parent;
	  
	  $this = $(this);
	  $parent = getParent($this);
	  
	  $(document)
		.on('mousemove.bfhcolorpicker.data-api', {colorpicker: $parent}, BFHColorPicker.prototype.mouseMove)
		.one('mouseup.bfhcolorpicker.data-api', {colorpicker: $parent}, BFHColorPicker.prototype.mouseUp);
	},
	
	mouseMove: function(e) {
	  var $this;
	  
	  $this = e.data.colorpicker;
	  
	  $this.data('bfhcolorpicker').updateVal(e.pageX, e.pageY);
	},
	
	mouseUp: function(e) {
	  var $this;
	  
	  $this = e.data.colorpicker;
	  
	  $this.data('bfhcolorpicker').updateVal(e.pageX, e.pageY);
	  
	  $(document).off('mousemove.bfhcolorpicker.data-api');
	  
	  if ($this.data('bfhcolorpicker').options.close === true) {
		clearMenus();
	  }
	},

	toggle: function (e) {
	  var $this,
		  $parent,
		  isActive;

	  $this = $(this);
	  $parent = getParent($this);

	  if ($parent.is('.disabled') || $parent.attr('disabled') !== undefined) {
		return true;
	  }

	  isActive = $parent.hasClass('open');

	  clearMenus();

	  if (!isActive) {
		$parent.trigger(e = $.Event('show.bfhcolorpicker'));

		if (e.isDefaultPrevented()) {
		  return true;
		}

		$parent
		  .toggleClass('open')
		  .trigger('shown.bfhcolorpicker');

		$this.focus();
	  }

	  return false;
	}
  };
  
  function componentToHex(c) {
	var hex = c.toString(16);
	return hex.length === 1 ? '0' + hex : hex;
  }

  function rgbToHex(r, g, b) {
	return '#' + componentToHex(r) + componentToHex(g) + componentToHex(b);
  }
	  
  function clearMenus() {
	var $parent;

	$(toggle).each(function (e) {
	  $parent = getParent($(this));

	  if (!$parent.hasClass('open')) {
		return true;
	  }

	  $parent.trigger(e = $.Event('hide.bfhcolorpicker'));

	  if (e.isDefaultPrevented()) {
		return true;
	  }

	  $parent
		.removeClass('open')
		.trigger('hidden.bfhcolorpicker');
	});
  }

  function getParent($this) {
	return $this.closest('.bfh-colorpicker');
  }
  
  
  /* COLORPICKER PLUGIN DEFINITION
   * ========================== */

  var old = $.fn.bfhcolorpicker;

  $.fn.bfhcolorpicker = function (option) {
	return this.each(function () {
	  var $this,
		  data,
		  options;

	  $this = $(this);
	  data = $this.data('bfhcolorpicker');
	  options = typeof option === 'object' && option;
	  this.type = 'bfhcolorpicker';

	  if (!data) {
		$this.data('bfhcolorpicker', (data = new BFHColorPicker(this, options)));
	  }
	  if (typeof option === 'string') {
		data[option].call($this);
	  }
	});
  };

  $.fn.bfhcolorpicker.Constructor = BFHColorPicker;

  $.fn.bfhcolorpicker.defaults = {
	align: 'left',
	input: 'form-control',
	placeholder: '',
	name: '',
	color: '#000000',
	close: true
  };
  
  
  /* COLORPICKER NO CONFLICT
   * ========================== */

  $.fn.bfhcolorpicker.noConflict = function () {
	$.fn.bfhcolorpicker = old;
	return this;
  };
  
  
  /* COLORPICKER VALHOOKS
   * ========================== */

  var origHook;
  if ($.valHooks.div){
	origHook = $.valHooks.div;
  }
  $.valHooks.div = {
	get: function(el) {
	  if ($(el).hasClass('bfh-colorpicker')) {
		return $(el).find('input[type="text"]').val();
	  } else if (origHook) {
		return origHook.get(el);
	  }
	},
	set: function(el, val) {
	  if ($(el).hasClass('bfh-colorpicker')) {
		$(el).find('.bfh-colorpicker-icon').css('background-color', val);
		$(el).find('input[type="text"]').val(val);
	  } else if (origHook) {
		return origHook.set(el,val);
	  }
	}
  };
  
  
  /* COLORPICKER DATA-API
   * ============== */

  $(document).ready( function () {
	$('div.bfh-colorpicker').each(function () {
	  var $colorpicker;

	  $colorpicker = $(this);

	  $colorpicker.bfhcolorpicker($colorpicker.data());
	});
  });
  
  
  /* APPLY TO STANDARD COLORPICKER ELEMENTS
   * =================================== */

  $(document)
	.on('click.bfhcolorpicker.data-api', clearMenus);

}(window.jQuery),

/**
* Date Picker
*/
+function ($) {

  'use strict';


  /* BFHDATEPICKER CLASS DEFINITION
   * ========================= */

  var toggle = '[data-toggle=bfh-datepicker]',
	  BFHDatePicker = function (element, options) {
		this.options = $.extend({}, $.fn.bfhdatepicker.defaults, options);
		this.$element = $(element);

		this.initCalendar();
	  };

  BFHDatePicker.prototype = {

	constructor: BFHDatePicker,

	setDate: function() {
	  var date,
		  today,
		  format;

	  date = this.options.date;
	  format = this.options.format;

	  if (date === '' || date === 'today' || date === undefined) {
		today = new Date();

		if (date === 'today') {
		  this.$element.val(formatDate(format, today.getMonth(), today.getFullYear(), today.getDate()));
		}

		this.$element.data('month', today.getMonth());
		this.$element.data('year', today.getFullYear());
	  } else {
		this.$element.val(date);
		this.$element.data('month', Number(getDatePart(format, date, 'm') - 1));
		this.$element.data('year', Number(getDatePart(format, date, 'y')));
	  }
	},

	setDateLimit: function(date, limitPrefix) {
	  var today,
		  format;

	  format = this.options.format;

	  if (date !== '') {
		this.$element.data(limitPrefix + 'limit', true);

		if (date === 'today') {
		  today = new Date();

		  this.$element.data(limitPrefix + 'day', today.getDate());
		  this.$element.data(limitPrefix + 'month', today.getMonth());
		  this.$element.data(limitPrefix + 'year', today.getFullYear());
		} else {
		  this.$element.data(limitPrefix + 'day', Number(getDatePart(format, date, 'd')));
		  this.$element.data(limitPrefix + 'month', Number(getDatePart(format, date, 'm') - 1));
		  this.$element.data(limitPrefix + 'year', Number(getDatePart(format, date, 'y')));
		}
	  } else {
		this.$element.data(limitPrefix + 'limit', false);
	  }
	},

	initCalendar: function() {
	  var iconLeft,
		  iconRight,
		  iconAddon;

	  iconLeft = '';
	  iconRight = '';
	  iconAddon = '';
	  if (this.options.icon !== '') {
		if (this.options.align === 'right') {
		  iconRight = '<span class="input-group-addon"><i class="' + this.options.icon + '"></i></span>';
		} else {
		  iconLeft = '<span class="input-group-addon"><i class="' + this.options.icon + '"></i></span>';
		}
		iconAddon = 'input-group';
	  }

	  this.$element.html(
		'<div class="' + iconAddon + ' bfh-datepicker-toggle" data-toggle="bfh-datepicker">' +
		iconLeft +
		'<input type="text" name="' + this.options.name + '" class="' + this.options.input + '" placeholder="' + this.options.placeholder + '" readonly>' +
		iconRight +
		'</div>' +
		'<div class="bfh-datepicker-calendar">' +
		'<table class="calendar table table-bordered">' +
		'<thead>' +
		'<tr class="months-header">' +
		'<th class="month" colspan="4">' +
		'<a class="previous" href="#"><i class="glyphicon glyphicon-chevron-left"></i></a>' +
		'<span></span>' +
		'<a class="next" href="#"><i class="glyphicon glyphicon-chevron-right"></i></a>' +
		'</th>' +
		'<th class="year" colspan="3">' +
		'<a class="previous" href="#"><i class="glyphicon glyphicon-chevron-left"></i></a>' +
		'<span></span>' +
		'<a class="next" href="#"><i class="glyphicon glyphicon-chevron-right"></i></a>' +
		'</th>' +
		'</tr>' +
		'<tr class="days-header">' +
		'</tr>' +
		'</thead>' +
		'<tbody>' +
		'</tbody>' +
		'</table>' +
		'</div>'
	  );

	  this.$element
		.on('click.bfhdatepicker.data-api touchstart.bfhdatepicker.data-api', toggle, BFHDatePicker.prototype.toggle)
		.on('click.bfhdatepicker.data-api touchstart.bfhdatepicker.data-api', '.bfh-datepicker-calendar > table.calendar .month > .previous', BFHDatePicker.prototype.previousMonth)
		.on('click.bfhdatepicker.data-api touchstart.bfhdatepicker.data-api', '.bfh-datepicker-calendar > table.calendar .month > .next', BFHDatePicker.prototype.nextMonth)
		.on('click.bfhdatepicker.data-api touchstart.bfhdatepicker.data-api', '.bfh-datepicker-calendar > table.calendar .year > .previous', BFHDatePicker.prototype.previousYear)
		.on('click.bfhdatepicker.data-api touchstart.bfhdatepicker.data-api', '.bfh-datepicker-calendar > table.calendar .year > .next', BFHDatePicker.prototype.nextYear)
		.on('click.bfhdatepicker.data-api touchstart.bfhdatepicker.data-api', '.bfh-datepicker-calendar > table.calendar td:not(.off)', BFHDatePicker.prototype.select)
		.on('click.bfhdatepicker.data-api touchstart.bfhdatepicker.data-api', '.bfh-datepicker-calendar > table.calendar', function() { return false; });

	  this.setDate();
	  this.setDateLimit(this.options.min, 'lower');
	  this.setDateLimit(this.options.max, 'higher');

	  this.updateCalendar();
	},

	updateCalendarHeader: function($calendar, month, year) {
	  var $daysHeader,
		  day;

	  $calendar.find('table > thead > tr > th.month > span').text(BFHMonthsList[month]);
	  $calendar.find('table > thead > tr > th.year > span').text(year);

	  $daysHeader = $calendar.find('table > thead > tr.days-header');
	  $daysHeader.html('');
	  for (day=BFHDayOfWeekStart; day < BFHDaysList.length; day=day+1) {
		$daysHeader.append('<th>' + BFHDaysList[day] + '</th>');
	  }
	  for (day=0; day < BFHDayOfWeekStart; day=day+1) {
		$daysHeader.append('<th>' + BFHDaysList[day] + '</th>');
	  }
	},

	checkMinDate: function(day, month, year) {
	  var lowerlimit,
		  lowerday,
		  lowermonth,
		  loweryear;

	  lowerlimit = this.$element.data('lowerlimit');

	  if (lowerlimit === true) {
		lowerday = this.$element.data('lowerday');
		lowermonth = this.$element.data('lowermonth');
		loweryear = this.$element.data('loweryear');

		if ((day < lowerday && month === lowermonth && year === loweryear) || (month < lowermonth && year === loweryear) || (year < loweryear)) {
		  return true;
		}
	  }

	  return false;
	},

	checkMaxDate: function(day, month, year) {
	  var higherlimit,
		  higherday,
		  highermonth,
		  higheryear;

	  higherlimit = this.$element.data('higherlimit');

	  if (higherlimit === true) {
		higherday = this.$element.data('higherday');
		highermonth = this.$element.data('highermonth');
		higheryear = this.$element.data('higheryear');

		if ((day > higherday && month === highermonth && year === higheryear) || (month > highermonth && year === higheryear) || (year > higheryear)) {
		  return true;
		}
	  }

	  return false;
	},

	checkToday: function(day, month, year) {
	  var today;

	  today = new Date();

	  if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
		return true;
	  }

	  return false;
	},

	updateCalendarDays: function($calendar, month, year) {
	  var $days,
		  numDaysPreviousMonth,
		  numDaysCurrentMonth,
		  firstDay,
		  lastDay,
		  row,
		  day;

	  $days = $calendar.find('table > tbody').html('');
	  numDaysPreviousMonth = getNumDaysInMonth(month, year);
	  numDaysCurrentMonth = getNumDaysInMonth(month + 1, year);
	  firstDay = getDayOfWeek(month, year, 1);
	  lastDay = getDayOfWeek(month, year, numDaysCurrentMonth);

	  row = '';
	  for (day=0; day < (firstDay - BFHDayOfWeekStart + 7) % 7; day=day+1) {
		row += '<td class="off">' + (numDaysPreviousMonth - (firstDay - BFHDayOfWeekStart + 7) % 7 + day + 1) + '</td>';
	  }

	  for (day=1; day <= numDaysCurrentMonth; day=day+1) {
		if (this.checkMinDate(day, month, year)) {
		  row += '<td data-day="' + day + '" class="off">' + day + '</td>';
		} else if (this.checkMaxDate(day, month, year)) {
		  row += '<td data-day="' + day + '" class="off">' + day + '</td>';
		} else if (this.checkToday(day, month, year)) {
		  row += '<td data-day="' + day + '" class="today">' + day + '</td>';
		} else {
		  row += '<td data-day="' + day + '">' + day + '</td>';
		}
		if (getDayOfWeek(month, year, day) === (6 + BFHDayOfWeekStart) % 7) {
		  $days.append('<tr>' + row + '</tr>');
		  row = '';
		}
	  }

	  for (day=1; day <= (7 - ((lastDay + 1 - BFHDayOfWeekStart + 7) % 7)) % 7 + 1; day=day+1) {
		row += '<td class="off">' + day + '</td>';
		if (day === (7 - ((lastDay + 1 - BFHDayOfWeekStart + 7) % 7)) % 7) {
		  $days.append('<tr>' + row + '</tr>');
		}
	  }
	},

	updateCalendar: function () {
	  var $calendar,
		  month,
		  year;

	  $calendar = this.$element.find('.bfh-datepicker-calendar');
	  month = this.$element.data('month');
	  year = this.$element.data('year');

	  this.updateCalendarHeader($calendar, month, year);
	  this.updateCalendarDays($calendar, month, year);
	},

	previousMonth: function () {
	  var $this,
		  $parent,
		  $datePicker;

	  $this = $(this);
	  $parent = getParent($this);

	  if (Number($parent.data('month')) === 0) {
		$parent.data('month', 11);
		$parent.data('year', Number($parent.data('year')) - 1);
	  } else {
		$parent.data('month', Number($parent.data('month')) - 1);
	  }

	  $datePicker = $parent.data('bfhdatepicker');
	  $datePicker.updateCalendar();

	  return false;
	},

	nextMonth: function () {
	  var $this,
		  $parent,
		  $datePicker;

	  $this = $(this);
	  $parent = getParent($this);

	  if (Number($parent.data('month')) === 11) {
		$parent.data('month', 0);
		$parent.data('year', Number($parent.data('year')) + 1);
	  } else {
		$parent.data('month', Number($parent.data('month')) + 1);
	  }

	  $datePicker = $parent.data('bfhdatepicker');
	  $datePicker.updateCalendar();

	  return false;
	},

	previousYear: function () {
	  var $this,
		  $parent,
		  $datePicker;

	  $this = $(this);
	  $parent = getParent($this);

	  $parent.data('year', Number($parent.data('year')) - 1);

	  $datePicker = $parent.data('bfhdatepicker');
	  $datePicker.updateCalendar();

	  return false;
	},

	nextYear: function () {
	  var $this,
		  $parent,
		  $datePicker;

	  $this = $(this);
	  $parent = getParent($this);

	  $parent.data('year', Number($parent.data('year')) + 1);

	  $datePicker = $parent.data('bfhdatepicker');
	  $datePicker.updateCalendar();

	  return false;
	},

	select: function (e) {
	  var $this,
		  $parent,
		  $datePicker,
		  month,
		  year,
		  day;

	  $this = $(this);

	  e.preventDefault();
	  e.stopPropagation();

	  $parent = getParent($this);
	  $datePicker = $parent.data('bfhdatepicker');
	  month = $parent.data('month');
	  year = $parent.data('year');
	  day = $this.data('day');

	  $parent.val(formatDate($datePicker.options.format, month, year, day));
	  $parent.trigger('change.bfhdatepicker');

	  if ($datePicker.options.close === true) {
		clearMenus();
	  }
	},

	toggle: function (e) {
	  var $this,
		  $parent,
		  isActive;

	  $this = $(this);
	  $parent = getParent($this);

	  if ($parent.is('.disabled') || $parent.attr('disabled') !== undefined) {
		return true;
	  }

	  isActive = $parent.hasClass('open');

	  clearMenus();

	  if (!isActive) {
		$parent.trigger(e = $.Event('show.bfhdatepicker'));

		if (e.isDefaultPrevented()) {
		  return true;
		}

		$parent
		  .toggleClass('open')
		  .trigger('shown.bfhdatepicker');

		$this.focus();
	  }

	  return false;
	}
  };

  function getNumDaysInMonth(month, year) {
	return new Date(year, month, 0).getDate();
  }

  function getDayOfWeek(month, year, day) {
	return new Date(year, month, day).getDay();
  }

  function formatDate(format, month, year, day) {
	month += 1;
	month = String(month);
	day = String(day);

	if (month.length === 1) {
	  month = '0' + month;
	}
	if (day.length === 1) {
	  day = '0' + day;
	}

	return format.replace('m', month).replace('y', year).replace('d', day);
  }

  function getDatePart(format, date, part) {
	var partPositions,
		partPosition,
		parts;

	partPositions = [
	  {'part': 'm', 'position': format.indexOf('m')},
	  {'part': 'y', 'position': format.indexOf('y')},
	  {'part': 'd', 'position': format.indexOf('d')}
	];

	partPositions.sort(function(a, b) {return a.position - b.position;});

	parts = date.match(/(\d+)/g);

	for (partPosition in partPositions) {
	  if (partPositions.hasOwnProperty(partPosition)) {
		if (partPositions[partPosition].part === part) {
		  return Number(parts[partPosition]).toString();
		}
	  }
	}
  }

  function clearMenus() {
	var $parent;

	$(toggle).each(function (e) {
	  $parent = getParent($(this));

	  if (!$parent.hasClass('open')) {
		return true;
	  }

	  $parent.trigger(e = $.Event('hide.bfhdatepicker'));

	  if (e.isDefaultPrevented()) {
		return true;
	  }

	  $parent
		.removeClass('open')
		.trigger('hidden.bfhdatepicker');
	});
  }

  function getParent($this) {
	return $this.closest('.bfh-datepicker');
  }


  /* DATEPICKER PLUGIN DEFINITION
   * ========================== */

  var old = $.fn.bfhdatepicker;

  $.fn.bfhdatepicker = function (option) {
	return this.each(function () {
	  var $this,
		  data,
		  options;

	  $this = $(this);
	  data = $this.data('bfhdatepicker');
	  options = typeof option === 'object' && option;
	  this.type = 'bfhdatepicker';

	  if (!data) {
		$this.data('bfhdatepicker', (data = new BFHDatePicker(this, options)));
	  }
	  if (typeof option === 'string') {
		data[option].call($this);
	  }
	});
  };

  $.fn.bfhdatepicker.Constructor = BFHDatePicker;

  $.fn.bfhdatepicker.defaults = {
	icon: 'glyphicon glyphicon-calendar',
	align: 'left',
	input: 'form-control',
	placeholder: '',
	name: '',
	date: 'today',
	format: 'm/d/y',
	min: '',
	max: '',
	close: true
  };


  /* DATEPICKER NO CONFLICT
   * ========================== */

  $.fn.bfhdatepicker.noConflict = function () {
	$.fn.bfhdatepicker = old;
	return this;
  };


  /* DATEPICKER VALHOOKS
   * ========================== */

  var origHook;
  if ($.valHooks.div){
	origHook = $.valHooks.div;
  }
  $.valHooks.div = {
	get: function(el) {
	  if ($(el).hasClass('bfh-datepicker')) {
		return $(el).find('input[type="text"]').val();
	  } else if (origHook) {
		return origHook.get(el);
	  }
	},
	set: function(el, val) {
	  if ($(el).hasClass('bfh-datepicker')) {
		$(el).find('input[type="text"]').val(val);
	  } else if (origHook) {
		return origHook.set(el,val);
	  }
	}
  };


  /* DATEPICKER DATA-API
   * ============== */

  $(document).ready( function () {
	$('div.bfh-datepicker').each(function () {
	  var $datepicker;

	  $datepicker = $(this);

	  $datepicker.bfhdatepicker($datepicker.data());
	});
  });


  /* APPLY TO STANDARD DATEPICKER ELEMENTS
   * =================================== */

  $(document)
	.on('click.bfhdatepicker.data-api', clearMenus);

}(window.jQuery),

/**
* Number picker
*/
+function ($) {

  'use strict';


  /* NUMBER CLASS DEFINITION
   * ====================== */

  var BFHNumber = function (element, options) {
	this.options = $.extend({}, $.fn.bfhnumber.defaults, options);
	this.$element = $(element);

	this.initInput();
  };

  BFHNumber.prototype = {

	constructor: BFHNumber,

	initInput: function() {
	  var value;
	  
	  if (this.options.buttons === true) {
		this.$element.wrap('<div class="input-group"></div>');
		this.$element.parent().append('<span class="input-group-addon bfh-number-btn inc"><span class="glyphicon glyphicon-chevron-up"></span></span>');
		this.$element.parent().append('<span class="input-group-addon bfh-number-btn dec"><span class="glyphicon glyphicon-chevron-down"></span></span>');
	  }
	  
	  this.$element.on('change.bfhnumber.data-api', BFHNumber.prototype.change);
		
	  if (this.options.keyboard === true) {
		this.$element.on('keydown.bfhnumber.data-api', BFHNumber.prototype.keydown);
	  }
	  
	  if (this.options.buttons === true) {
		this.$element.parent()
		  .on('mousedown.bfhnumber.data-api', '.inc', BFHNumber.prototype.btninc)
		  .on('mousedown.bfhnumber.data-api', '.dec', BFHNumber.prototype.btndec);
	  }
	  
	  this.formatNumber();
	},
	
	keydown: function(e) {
	  var $this;
	  
	  $this = $(this).data('bfhnumber');
	  
	  if ($this.$element.is('.disabled') || $this.$element.attr('disabled') !== undefined) {
		return true;
	  }
	  
	  switch (e.which) {
		case 38:
		  $this.increment();
		  break;
		case 40:
		  $this.decrement();
		  break;
		default:
	  }
	  
	  return true;
	},
	
	mouseup: function(e) {
	  var $this,
		  timer,
		  interval;
	  
	  $this = e.data.btn;
	  timer = $this.$element.data('timer');
	  interval = $this.$element.data('interval');
	  
	  clearTimeout(timer);
	  clearInterval(interval);
	},
	
	btninc: function() {
	  var $this,
		  timer;
	  
	  $this = $(this).parent().find('.bfh-number').data('bfhnumber');
	  
	  if ($this.$element.is('.disabled') || $this.$element.attr('disabled') !== undefined) {
		return true;
	  }
	  
	  $this.increment();
	  
	  timer = setTimeout(function() {
		var interval;
		interval = setInterval(function() {
		  $this.increment();
		}, 80);
		$this.$element.data('interval', interval);
	  }, 750);
	  $this.$element.data('timer', timer);
	  
	  $(document).one('mouseup', {btn: $this}, BFHNumber.prototype.mouseup);
	  
	  return true;
	},
	
	btndec: function() {
	  var $this,
		  timer;
	  
	  $this = $(this).parent().find('.bfh-number').data('bfhnumber');
	  
	  if ($this.$element.is('.disabled') || $this.$element.attr('disabled') !== undefined) {
		return true;
	  }
	  
	  $this.decrement();
	  
	  timer = setTimeout(function() {
		var interval;
		interval = setInterval(function() {
		  $this.decrement();
		}, 80);
		$this.$element.data('interval', interval);
	  }, 750);
	  $this.$element.data('timer', timer);
	  
	  $(document).one('mouseup', {btn: $this}, BFHNumber.prototype.mouseup);
	  
	  return true;
	},
	
	change: function() {
	  var $this;

	  $this = $(this).data('bfhnumber');

	  if ($this.$element.is('.disabled') || $this.$element.attr('disabled') !== undefined) {
		return true;
	  }

	  $this.formatNumber();

	  return true;
	},
	
	increment: function() {
	  var value;
	  
	  value = this.getValue();
	  
	  value = value + 1;
	  
	  this.$element.val(value).change();
	},
	
	decrement: function() {
	  var value;
	  
	  value = this.getValue();
	  
	  value = value - 1;
	  
	  this.$element.val(value).change();
	},
	
	getValue: function() {
	  var value;
	  
	  value = this.$element.val();
	  if (value !== '-1') {
		value = String(value).replace(/\D/g, '');
	  }
	  if (String(value).length === 0) {
		value = this.options.min;
	  }
	  
	  return parseInt(value);
	},
	
	formatNumber: function() {
	  var value,
		  maxLength,
		  length,
		  zero;
	  
	  value = this.getValue();
	  
	  if (value > this.options.max) {
		if (this.options.wrap === true) {
		  value = this.options.min;
		} else {
		  value = this.options.max;
		}
	  }
	  
	  if (value < this.options.min) {
		if (this.options.wrap === true) {
		  value = this.options.max;
		} else {
		  value = this.options.min;
		}
	  }
	  
	  if (this.options.zeros === true) {
		maxLength = String(this.options.max).length;
		length = String(value).length;
		for (zero=length; zero < maxLength; zero = zero + 1) {
		  value = '0' + value;
		}
	  }
	  
	  if (value !== this.$element.val()) {
		this.$element.val(value);
	  }
	}

  };

  /* NUMBER PLUGIN DEFINITION
   * ======================= */

  var old = $.fn.bfhnumber;

  $.fn.bfhnumber = function (option) {
	return this.each(function () {
	  var $this,
		  data,
		  options;

	  $this = $(this);
	  data = $this.data('bfhnumber');
	  options = typeof option === 'object' && option;

	  if (!data) {
		$this.data('bfhnumber', (data = new BFHNumber(this, options)));
	  }
	  if (typeof option === 'string') {
		data[option].call($this);
	  }
	});
  };

  $.fn.bfhnumber.Constructor = BFHNumber;

  $.fn.bfhnumber.defaults = {
	min: 0,
	max: 9999,
	zeros: false,
	keyboard: true,
	buttons: true,
	wrap: false
  };


  /* NUMBER NO CONFLICT
   * ========================== */

  $.fn.bfhnumber.noConflict = function () {
	$.fn.bfhnumber = old;
	return this;
  };


  /* NUMBER DATA-API
   * ============== */

  $(document).ready( function () {
	$('form input[type="text"].bfh-number, form input[type="number"].bfh-number').each(function () {
	  var $number;

	  $number = $(this);

	  $number.bfhnumber($number.data());
	});
  });


  /* APPLY TO STANDARD NUMBER ELEMENTS
   * =================================== */


}(window.jQuery),

/**
* Phone format list
*/
+function ($) {

  'use strict';


  /* PHONE CLASS DEFINITION
   * ====================== */

  var BFHPhone = function (element, options) {
	this.options = $.extend({}, $.fn.bfhphone.defaults, options);
	this.$element = $(element);

	if (this.$element.is('input[type="text"]') || this.$element.is('input[type="tel"]')) {
	  this.addFormatter();
	}

	if (this.$element.is('span')) {
	  this.displayFormatter();
	}
  };

  BFHPhone.prototype = {

	constructor: BFHPhone,

	addFormatter: function() {
	  var $country;

	  if (this.options.country !== '') {
		$country = $(document).find('#' + this.options.country);

		if ($country.length !== 0) {
		  this.options.format = BFHPhoneFormatList[$country.val()];
		  $country.on('change', {phone: this}, this.changeCountry);
		} else {
		  this.options.format = BFHPhoneFormatList[this.options.country];
		}
	  }
	  
	  this.$element.on('keyup.bfhphone.data-api', BFHPhone.prototype.change);

	  this.loadFormatter();
	},

	loadFormatter: function () {
	  var formattedNumber;

	  formattedNumber = formatNumber(this.options.format, this.$element.val());

	  this.$element.val(formattedNumber);
	},

	displayFormatter: function () {
	  var formattedNumber;

	  if (this.options.country !== '') {
		this.options.format = BFHPhoneFormatList[this.options.country];
	  }

	  formattedNumber = formatNumber(this.options.format, this.options.number);

	  this.$element.html(formattedNumber);
	},

	changeCountry: function (e) {
	  var $this,
		  $phone;

	  $this = $(this);
	  $phone = e.data.phone;

	  $phone.$element.val(String($phone.$element.val()).replace(/\+\d*/g, ''));
	  $phone.options.format = BFHPhoneFormatList[$this.val()];

	  $phone.loadFormatter();
	},

	change: function(e) {
	  var $this,
		  cursorPosition,
		  cursorEnd,
		  formattedNumber;

	  $this = $(this).data('bfhphone');

	  if ($this.$element.is('.disabled') || $this.$element.attr('disabled') !== undefined) {
		return true;
	  }

	  cursorPosition = getCursorPosition($this.$element[0]);

	  cursorEnd = false;
	  if (cursorPosition === $this.$element.val().length) {
		cursorEnd = true;
	  }
	  
	  if (e.which === 8 && $this.options.format.charAt($this.$element.val().length) !== 'd') {
		$this.$element.val(String($this.$element.val()).substring(0, $this.$element.val().length - 1));
	  }

	  formattedNumber = formatNumber($this.options.format, $this.$element.val());
	  
	  if (formattedNumber === $this.$element.val()) {
		return true;
	  }
	  
	  $this.$element.val(formattedNumber);

	  if (cursorEnd) {
		cursorPosition = $this.$element.val().length;
	  }

	  setCursorPosition($this.$element[0], cursorPosition);

	  return true;
	}

  };

  function formatNumber(format, number) {
	var formattedNumber,
		indexFormat,
		indexNumber,
		lastCharacter;

	formattedNumber = '';
	number = String(number).replace(/\D/g, '');

	for (indexFormat = 0, indexNumber = 0; indexFormat < format.length; indexFormat = indexFormat + 1) {
	  if (/\d/g.test(format.charAt(indexFormat))) {
		if (format.charAt(indexFormat) === number.charAt(indexNumber)) {
		  formattedNumber += number.charAt(indexNumber);
		  indexNumber = indexNumber + 1;
		} else {
		  formattedNumber += format.charAt(indexFormat);
		}
	  } else if (format.charAt(indexFormat) !== 'd') {
		if (number.charAt(indexNumber) !== '' || format.charAt(indexFormat) === '+') {
		  formattedNumber += format.charAt(indexFormat);
		}
	  } else {
		if (number.charAt(indexNumber) === '') {
		  formattedNumber += '';
		} else {
		  formattedNumber += number.charAt(indexNumber);
		  indexNumber = indexNumber + 1;
		}
	  }
	}
	
	lastCharacter = format.charAt(formattedNumber.length);
	if (lastCharacter !== 'd') {
	  formattedNumber += lastCharacter;
	}

	return formattedNumber;
  }

  function getCursorPosition($element) {
	var position = 0,
		selection;

	if (document.selection) {
	  // IE Support
	  $element.focus();
	  selection = document.selection.createRange();
	  selection.moveStart ('character', -$element.value.length);
	  position = selection.text.length;
	} else if ($element.selectionStart || $element.selectionStart === 0) {
	  position = $element.selectionStart;
	}

	return position;
  }

  function setCursorPosition($element, position) {
	var selection;

	if (document.selection) {
	  // IE Support
	  $element.focus ();
	  selection = document.selection.createRange();
	  selection.moveStart ('character', -$element.value.length);
	  selection.moveStart ('character', position);
	  selection.moveEnd ('character', 0);
	  selection.select ();
	} else if ($element.selectionStart || $element.selectionStart === 0) {
	  $element.selectionStart = position;
	  $element.selectionEnd = position;
	  $element.focus ();
	}
  }

  /* PHONE PLUGIN DEFINITION
   * ======================= */

  var old = $.fn.bfhphone;

  $.fn.bfhphone = function (option) {
	return this.each(function () {
	  var $this,
		  data,
		  options;

	  $this = $(this);
	  data = $this.data('bfhphone');
	  options = typeof option === 'object' && option;

	  if (!data) {
		$this.data('bfhphone', (data = new BFHPhone(this, options)));
	  }
	  if (typeof option === 'string') {
		data[option].call($this);
	  }
	});
  };

  $.fn.bfhphone.Constructor = BFHPhone;

  $.fn.bfhphone.defaults = {
	format: '',
	number: '',
	country: ''
  };


  /* PHONE NO CONFLICT
   * ========================== */

  $.fn.bfhphone.noConflict = function () {
	$.fn.bfhphone = old;
	return this;
  };


  /* PHONE DATA-API
   * ============== */

  $(document).ready( function () {
	$('form input[type="text"].bfh-phone, form input[type="tel"].bfh-phone, span.bfh-phone').each(function () {
	  var $phone;

	  $phone = $(this);

	  $phone.bfhphone($phone.data());
	});
  });

}(window.jQuery),

/**
* Selectbox
*/
+function ($) {

  'use strict';


  /* SELECTBOX CLASS DEFINITION
   * ========================= */

  var toggle = '[data-toggle=bfh-selectbox]',
	  BFHSelectBox = function (element, options) {
		this.options = $.extend({}, $.fn.bfhselectbox.defaults, options);
		this.$element = $(element);

		this.initSelectBox();
	  };

  BFHSelectBox.prototype = {

	constructor: BFHSelectBox,

	initSelectBox: function () {
	  var options;

	  options = '';
	  this.$element.find('div').each(function() {
		options = options + '<li><a tabindex="-1" href="#" data-option="' + $(this).data('value') + '">' + $(this).html() + '</a></li>';
	  });

	  this.$element.html(
		'<input type="hidden" name="' + this.options.name + '" value="">' +
		'<a class="bfh-selectbox-toggle ' + this.options.input + '" role="button" data-toggle="bfh-selectbox" href="#">' +
		'<span class="bfh-selectbox-option"></span>' +
		'<span class="' + this.options.icon + ' selectbox-caret"></span>' +
		'</a>' +
		'<div class="bfh-selectbox-options">' +
		'<div role="listbox">' +
		'<ul role="option">' +
		'</ul>' +
		'</div>' +
		'</div>'
	  );

	  this.$element.find('[role=option]').html(options);

	  if (this.options.filter === true) {
		this.$element.find('.bfh-selectbox-options').prepend('<div class="bfh-selectbox-filter-container"><input type="text" class="bfh-selectbox-filter form-control"></div>');
	  }

	  this.$element.val(this.options.value);

	  this.$element
		.on('click.bfhselectbox.data-api touchstart.bfhselectbox.data-api', toggle, BFHSelectBox.prototype.toggle)
		.on('keydown.bfhselectbox.data-api', toggle + ', [role=option]' , BFHSelectBox.prototype.keydown)
		.on('mouseenter.bfhselectbox.data-api', '[role=option] > li > a', BFHSelectBox.prototype.mouseenter)
		.on('click.bfhselectbox.data-api', '[role=option] > li > a', BFHSelectBox.prototype.select)
		.on('click.bfhselectbox.data-api', '.bfh-selectbox-filter', function () { return false; })
		.on('propertychange.bfhselectbox.data-api change.bfhselectbox.data-api input.bfhselectbox.data-api paste.bfhselectbox.data-api', '.bfh-selectbox-filter', BFHSelectBox.prototype.filter);
	},

	toggle: function (e) {
	  var $this,
		  $parent,
		  isActive;

	  $this = $(this);
	  $parent = getParent($this);

	  if ($parent.is('.disabled') || $parent.attr('disabled') !== undefined) {
		return true;
	  }

	  isActive = $parent.hasClass('open');

	  clearMenus();

	  if (!isActive) {
		$parent.trigger(e = $.Event('show.bfhselectbox'));

		if (e.isDefaultPrevented()) {
		  return true;
		}

		$parent
		  .toggleClass('open')
		  .trigger('shown.bfhselectbox')
		  .find('[role=option] > li > [data-option="' + $parent.val() + '"]').focus();
	  }

	  return false;
	},

	filter: function() {
	  var $this,
		  $parent,
		  $items;

	  $this = $(this);
	  $parent = getParent($this);

	  $items = $('[role=option] li a', $parent);
	  $items
		.hide()
		.filter(function() {
		  return ($(this).text().toUpperCase().indexOf($this.val().toUpperCase()) !== -1);
		})
		.show();
	},

	keydown: function (e) {
	  var $this,
		  $items,
		  $parent,
		  $subItems,
		  isActive,
		  index,
		  selectedIndex;

	  if (!/(38|40|27)/.test(e.keyCode)) {
		return true;
	  }

	  $this = $(this);

	  e.preventDefault();
	  e.stopPropagation();

	  $parent = getParent($this);
	  isActive = $parent.hasClass('open');

	  if (!isActive || (isActive && e.keyCode === 27)) {
		if (e.which === 27) {
		  $parent.find(toggle).focus();
		}

		return $this.click();
	  }

	  $items = $('[role=option] li:not(.divider) a:visible', $parent);

	  if (!$items.length) {
		return true;
	  }

	  $('body').off('mouseenter.bfh-selectbox.data-api', '[role=option] > li > a', BFHSelectBox.prototype.mouseenter);
	  index = $items.index($items.filter(':focus'));

	  if (e.keyCode === 38 && index > 0) {
		index = index - 1;
	  }

	  if (e.keyCode === 40 && index < $items.length - 1) {
		index = index + 1;
	  }

	  if (!index) {
		index = 0;
	  }

	  $items.eq(index).focus();
	  $('body').on('mouseenter.bfh-selectbox.data-api', '[role=option] > li > a', BFHSelectBox.prototype.mouseenter);
	},

	mouseenter: function () {
	  var $this;

	  $this = $(this);

	  $this.focus();
	},

	select: function (e) {
	  var $this,
		  $parent,
		  $span,
		  $input;

	  $this = $(this);

	  e.preventDefault();
	  e.stopPropagation();

	  if ($this.is('.disabled') || $this.attr('disabled') !== undefined) {
		return true;
	  }

	  $parent = getParent($this);

	  $parent.val($this.data('option'));
	  $parent.trigger('change.bfhselectbox');

	  clearMenus();
	}

  };

  function clearMenus() {
	var $parent;

	$(toggle).each(function (e) {
	  $parent = getParent($(this));

	  if (!$parent.hasClass('open')) {
		return true;
	  }

	  $parent.trigger(e = $.Event('hide.bfhselectbox'));

	  if (e.isDefaultPrevented()) {
		return true;
	  }

	  $parent
		.removeClass('open')
		.trigger('hidden.bfhselectbox');
	});
  }

  function getParent($this) {
	return $this.closest('.bfh-selectbox');
  }


  /* SELECTBOX PLUGIN DEFINITION
   * ========================== */

  var old = $.fn.bfhselectbox;

  $.fn.bfhselectbox = function (option) {
	return this.each(function () {
	  var $this,
		  data,
		  options;

	  $this = $(this);
	  data = $this.data('bfhselectbox');
	  options = typeof option === 'object' && option;
	  this.type = 'bfhselectbox';

	  if (!data) {
		$this.data('bfhselectbox', (data = new BFHSelectBox(this, options)));
	  }
	  if (typeof option === 'string') {
		data[option].call($this);
	  }
	});
  };

  $.fn.bfhselectbox.Constructor = BFHSelectBox;

  $.fn.bfhselectbox.defaults = {
	icon: 'caret',
	input: 'form-control',
	name: '',
	value: '',
	filter: false
  };


  /* SELECTBOX NO CONFLICT
   * ========================== */

  $.fn.bfhselectbox.noConflict = function () {
	$.fn.bfhselectbox = old;
	return this;
  };


  /* SELECTBOX VALHOOKS
   * ========================== */

  var origHook;
  if ($.valHooks.div){
	origHook = $.valHooks.div;
  }
  $.valHooks.div = {
	get: function(el) {
	  if ($(el).hasClass('bfh-selectbox')) {
		return $(el).find('input[type="hidden"]').val();
	  } else if (origHook) {
		return origHook.get(el);
	  }
	},
	set: function(el, val) {
	  var $el,
		  html;

	  if ($(el).hasClass('bfh-selectbox')) {

		$el = $(el);
		if ($el.find('li a[data-option=\'' + val + '\']').length > 0) {
		  html = $el.find('li a[data-option=\'' + val + '\']').html();
		} else if ($el.find('li a').length > 0) {
		  html = $el.find('li a').eq(0).html();
		} else {
		  val = '';
		  html = '';
		}

		$el.find('input[type="hidden"]').val(val);
		$el.find('.bfh-selectbox-option').html(html);
	  } else if (origHook) {
		return origHook.set(el,val);
	  }
	}
  };


  /* SELECTBOX DATA-API
   * ============== */

  $(document).ready( function () {
	$('div.bfh-selectbox').each(function () {
	  var $selectbox;

	  $selectbox = $(this);

	  $selectbox.bfhselectbox($selectbox.data());
	});
  });


  /* APPLY TO STANDARD SELECTBOX ELEMENTS
   * =================================== */

  $(document)
	.on('click.bfhselectbox.data-api', clearMenus);

}(window.jQuery),

/**
* Slider
*/
+function ($) {

  'use strict';


  /* BFHSLIDER CLASS DEFINITION
   * ========================= */

  var BFHSlider = function (element, options) {
		this.options = $.extend({}, $.fn.bfhslider.defaults, options);
		this.$element = $(element);
		
		this.initSlider();
	  };

  BFHSlider.prototype = {

	constructor: BFHSlider,

	initSlider: function() {
	  if (this.options.value === '') {
		this.options.value = this.options.min;
	  }
	  
	  this.$element.html(
		'<input type="hidden" name="' + this.options.name + '" value="">' +
		'<div class="bfh-slider-handle"><div class="bfh-slider-value"></div></div>'
	  );
	  
	  this.$element.find('input[type="hidden"]').val(this.options.value);
	  this.updateHandle(this.options.value);
	  
	  this.$element.on('mousedown.bfhslider.data-api', BFHSlider.prototype.mouseDown);
	},
	
	updateHandle: function(val) {
	  var positionX,
		  width,
		  left,
		  span;
		  
	  span = this.options.max - this.options.min;
	  width = this.$element.width();
	  left = this.$element.position().left;
	  
	  positionX = Math.round((val - this.options.min) * (width - 20) / span + left);
	  
	  this.$element.find('.bfh-slider-handle').css('left', positionX + 'px');
	  this.$element.find('.bfh-slider-value').text(val);
	},
	
	updateVal: function(positionX) {
	  var width,
		  left,
		  right,
		  val,
		  span;
	  
	  span = this.options.max - this.options.min;
	  width = this.$element.width();
	  left = this.$element.offset().left;
	  right = left + width;
	  
	  if (positionX < left) {
		positionX = left;
	  }
	  
	  if (positionX + 20 > right) {
		positionX = right;
	  }
	  
	  val = (positionX - left) / width;
	  val = Math.ceil(val * span + this.options.min);
	  
	  if (val === this.$element.val()) {
		return true;
	  }
	  
	  this.$element.val(val);
	  
	  this.$element.trigger('change.bfhslider');
	},
	
	mouseDown: function() {
	  var $this;
	  
	  $this = $(this);
	  
	  if ($this.is('.disabled') || $this.attr('disabled') !== undefined) {
		return true;
	  }
	  
	  $(document)
		.on('mousemove.bfhslider.data-api', {slider: $this}, BFHSlider.prototype.mouseMove)
		.one('mouseup.bfhslider.data-api', {slider: $this}, BFHSlider.prototype.mouseUp);
	},
	
	mouseMove: function(e) {
	  var $this;
	  
	  $this = e.data.slider;
	  
	  $this.data('bfhslider').updateVal(e.pageX);
	},
	
	mouseUp: function(e) {
	  var $this;
	  
	  $this = e.data.slider;
	  
	  $this.data('bfhslider').updateVal(e.pageX);
	  
	  $(document).off('mousemove.bfhslider.data-api');
	}
  };


  /* SLIDER PLUGIN DEFINITION
   * ========================== */

  var old = $.fn.bfhslider;

  $.fn.bfhslider = function (option) {
	return this.each(function () {
	  var $this,
		  data,
		  options;

	  $this = $(this);
	  data = $this.data('bfhslider');
	  options = typeof option === 'object' && option;
	  this.type = 'bfhslider';

	  if (!data) {
		$this.data('bfhslider', (data = new BFHSlider(this, options)));
	  }
	  if (typeof option === 'string') {
		data[option].call($this);
	  }
	});
  };

  $.fn.bfhslider.Constructor = BFHSlider;

  $.fn.bfhslider.defaults = {
	name: '',
	value: '',
	min: 0,
	max: 100
  };


  /* SLIDER NO CONFLICT
   * ========================== */

  $.fn.bfhslider.noConflict = function () {
	$.fn.bfhslider = old;
	return this;
  };


  /* SLIDER VALHOOKS
   * ========================== */

  var origHook;
  if ($.valHooks.div){
	origHook = $.valHooks.div;
  }
  $.valHooks.div = {
	get: function(el) {
	  if ($(el).hasClass('bfh-slider')) {
		return $(el).find('input[type="hidden"]').val();
	  } else if (origHook) {
		return origHook.get(el);
	  }
	},
	set: function(el, val) {
	  if ($(el).hasClass('bfh-slider')) {
		$(el).find('input[type="hidden"]').val(val);
		$(el).data('bfhslider').updateHandle(val);
	  } else if (origHook) {
		return origHook.set(el,val);
	  }
	}
  };


  /* SLIDER DATA-API
   * ============== */

  $(document).ready( function () {
	$('div.bfh-slider').each(function () {
	  var $slider;

	  $slider = $(this);

	  $slider.bfhslider($slider.data());
	});
  });

}(window.jQuery),

/**
* Time picker
*/
+function ($) {

  'use strict';


 /* TIMEPICKER CLASS DEFINITION
  * ========================= */

  var toggle = '[data-toggle=bfh-timepicker]',
	  BFHTimePicker = function (element, options) {
		this.options = $.extend({}, $.fn.bfhtimepicker.defaults, options);
		this.$element = $(element);

		this.initPopover();
	  };

  BFHTimePicker.prototype = {

	constructor: BFHTimePicker,

	setTime: function() {
	  var time,
		  today,
		  timeParts,
		  hours,
		  minutes,
		  mode,
		  currentMode;

	  time = this.options.time;
	  mode = '';
	  currentMode = '';
	  
	  if (time === '' || time === 'now' || time === undefined) {
		today = new Date();

		hours = today.getHours();
		minutes = today.getMinutes();
		
		if (this.options.mode === '12h') {
		  if (hours > 12) {
			hours = hours - 12;
			mode = ' ' + BFHTimePickerModes.pm;
			currentMode = 'pm';
		  } else {
			mode = ' ' + BFHTimePickerModes.am;
			currentMode = 'am';
		  }
		}
		
		if (time === 'now') {
		  this.$element.find('.bfh-timepicker-toggle > input[type="text"]').val(formatTime(hours, minutes) + mode);
		}

		this.$element.data('hour', hours);
		this.$element.data('minute', minutes);
		this.$element.data('mode', currentMode);
	  } else {
		timeParts = String(time).split(BFHTimePickerDelimiter);
		hours = timeParts[0];
		minutes = timeParts[1];
		
		if (this.options.mode === '12h') {
		  timeParts = String(minutes).split(' ');
		  minutes = timeParts[0];
		  if (timeParts[1] === BFHTimePickerModes.pm) {
			currentMode = 'pm';
		  } else {
			currentMode = 'am';
		  }
		}
		
		this.$element.find('.bfh-timepicker-toggle > input[type="text"]').val(time);
		this.$element.data('hour', hours);
		this.$element.data('minute', minutes);
		this.$element.data('mode', currentMode);
	  }
	},

	initPopover: function() {
	  var iconLeft,
		  iconRight,
		  iconAddon,
		  modeAddon,
		  modeMax;

	  iconLeft = '';
	  iconRight = '';
	  iconAddon = '';
	  if (this.options.icon !== '') {
		if (this.options.align === 'right') {
		  iconRight = '<span class="input-group-addon"><i class="' + this.options.icon + '"></i></span>';
		} else {
		  iconLeft = '<span class="input-group-addon"><i class="' + this.options.icon + '"></i></span>';
		}
		iconAddon = 'input-group';
	  }
	  
	  modeAddon = '';
	  modeMax = '23';
	  if (this.options.mode === '12h') {
		modeAddon = '<td>' +
		  '<div class="bfh-selectbox" data-input="' + this.options.input + '" data-value="am">' +
		  '<div data-value="am">' + BFHTimePickerModes.am + '</div>' +
		  '<div data-value="pm">' + BFHTimePickerModes.pm + '</div>' +
		  '</div>';
		modeMax = '11';
	  }

	  this.$element.html(
		'<div class="' + iconAddon + ' bfh-timepicker-toggle" data-toggle="bfh-timepicker">' +
		iconLeft +
		'<input type="text" name="' + this.options.name + '" class="' + this.options.input + '" placeholder="' + this.options.placeholder + '" readonly>' +
		iconRight +
		'</div>' +
		'<div class="bfh-timepicker-popover">' +
		'<table class="table">' +
		'<tbody>' +
		'<tr>' +
		'<td class="hour">' +
		'<input type="text" class="' + this.options.input + ' bfh-number"  data-min="0" data-max="' + modeMax + '" data-zeros="true" data-wrap="true">' +
		'</td>' +
		'<td class="separator">' + BFHTimePickerDelimiter + '</td>' +
		'<td class="minute">' +
		'<input type="text" class="' + this.options.input + ' bfh-number"  data-min="0" data-max="59" data-zeros="true" data-wrap="true">' +
		'</td>' +
		modeAddon +
		'</tr>' +
		'</tbody>' +
		'</table>' +
		'</div>'
	  );

	  this.$element
		.on('click.bfhtimepicker.data-api touchstart.bfhtimepicker.data-api', toggle, BFHTimePicker.prototype.toggle)
		.on('click.bfhtimepicker.data-api touchstart.bfhtimepicker.data-api', '.bfh-timepicker-popover > table', function() { return false; });

	  this.$element.find('.bfh-number').each(function () {
		var $number;

		$number = $(this);

		$number.bfhnumber($number.data());
		
		$number.on('change', BFHTimePicker.prototype.change);
	  });
	  
	  this.$element.find('.bfh-selectbox').each(function() {
		var $selectbox;

		$selectbox = $(this);

		$selectbox.bfhselectbox($selectbox.data());
		
		$selectbox.on('change.bfhselectbox', BFHTimePicker.prototype.change);
	  });
	  
	  this.setTime();

	  this.updatePopover();
	},

	updatePopover: function() {
	  var hour,
		  minute,
		  mode;

	  hour = this.$element.data('hour');
	  minute = this.$element.data('minute');
	  mode = this.$element.data('mode');

	  this.$element.find('.hour input[type=text]').val(hour).change();
	  this.$element.find('.minute input[type=text]').val(minute).change();
	  this.$element.find('.bfh-selectbox').val(mode);
	},
	
	change: function() {
	  var $this,
		  $parent,
		  $timePicker,
		  mode;

	  $this = $(this);
	  $parent = getParent($this);
	  
	  $timePicker = $parent.data('bfhtimepicker');
	  
	  if ($timePicker && $timePicker !== 'undefined') {
		mode = '';
		if ($timePicker.options.mode === '12h') {
		  mode = ' ' + BFHTimePickerModes[$parent.find('.bfh-selectbox').val()];
		}
		
		$parent.find('.bfh-timepicker-toggle > input[type="text"]').val($parent.find('.hour input[type=text]').val() + BFHTimePickerDelimiter + $parent.find('.minute input[type=text]').val() + mode);

		$parent.trigger('change.bfhtimepicker');
	  }

	  return false;
	},

	toggle: function(e) {
	  var $this,
		  $parent,
		  isActive;

	  $this = $(this);
	  $parent = getParent($this);

	  if ($parent.is('.disabled') || $parent.attr('disabled') !== undefined) {
		return true;
	  }

	  isActive = $parent.hasClass('open');

	  clearMenus();

	  if (!isActive) {
		$parent.trigger(e = $.Event('show.bfhtimepicker'));

		if (e.isDefaultPrevented()) {
		  return true;
		}

		$parent
		  .toggleClass('open')
		  .trigger('shown.bfhtimepicker');

		$this.focus();
	  }

	  return false;
	}
  };

  function formatTime(hour, minute) {
	hour = String(hour);
	if (hour.length === 1) {
	  hour = '0' + hour;
	}

	minute = String(minute);
	if (minute.length === 1) {
	  minute = '0' + minute;
	}

	return hour + BFHTimePickerDelimiter + minute;
  }
  
  function clearMenus() {
	var $parent;

	$(toggle).each(function (e) {
	  $parent = getParent($(this));

	  if (!$parent.hasClass('open')) {
		return true;
	  }

	  $parent.trigger(e = $.Event('hide.bfhtimepicker'));

	  if (e.isDefaultPrevented()) {
		return true;
	  }

	  $parent
		.removeClass('open')
		.trigger('hidden.bfhtimepicker');
	});
  }

  function getParent($this) {
	return $this.closest('.bfh-timepicker');
  }


  /* TIMEPICKER PLUGIN DEFINITION
   * ========================== */

  var old = $.fn.bfhtimepicker;

  $.fn.bfhtimepicker = function (option) {
	return this.each(function () {
	  var $this,
		  data,
		  options;

	  $this = $(this);
	  data = $this.data('bfhtimepicker');
	  options = typeof option === 'object' && option;
	  this.type = 'bfhtimepicker';

	  if (!data) {
		$this.data('bfhtimepicker', (data = new BFHTimePicker(this, options)));
	  }
	  if (typeof option === 'string') {
		data[option].call($this);
	  }
	});
  };

  $.fn.bfhtimepicker.Constructor = BFHTimePicker;

  $.fn.bfhtimepicker.defaults = {
	icon: 'glyphicon glyphicon-time',
	align: 'left',
	input: 'form-control',
	placeholder: '',
	name: '',
	time: 'now',
	mode: '24h'
  };


  /* TIMEPICKER NO CONFLICT
   * ========================== */

  $.fn.bfhtimepicker.noConflict = function () {
	$.fn.bfhtimepicker = old;
	return this;
  };


  /* TIMEPICKER VALHOOKS
   * ========================== */

  var origHook;
  if ($.valHooks.div){
	origHook = $.valHooks.div;
  }
  $.valHooks.div = {
	get: function(el) {
	  if ($(el).hasClass('bfh-timepicker')) {
		return $(el).find('.bfh-timepicker-toggle > input[type="text"]').val();
	  } else if (origHook) {
		return origHook.get(el);
	  }
	},
	set: function(el, val) {
	  var $timepicker;
	  if ($(el).hasClass('bfh-timepicker')) {
		$timepicker = $(el).data('bfhtimepicker');
		$timepicker.options.time = val;
		$timepicker.setTime();
		$timepicker.updatePopover();
	  } else if (origHook) {
		return origHook.set(el,val);
	  }
	}
  };


  /* TIMEPICKER DATA-API
   * ============== */

  $(document).ready( function () {
	$('div.bfh-timepicker').each(function () {
	  var $timepicker;

	  $timepicker = $(this);

	  $timepicker.bfhtimepicker($timepicker.data());
	});
  });


  /* APPLY TO STANDARD TIMEPICKER ELEMENTS
   * =================================== */

  $(document)
	.on('click.bfhtimepicker.data-api', clearMenus);

}(window.jQuery),

/*
  Bootstrap - File Input
  ======================

  This is meant to convert all file input tags into a set of elements that displays consistently in all browsers.

  Converts all
  <input type="file">
  into Bootstrap buttons
  <a class="btn">Browse</a>

*/
(function($) {

$.fn.bootstrapFileInput = function() {

  this.each(function(i,elem){

	var $elem = $(elem);

	// Maybe some fields don't need to be standardized.
	if (typeof $elem.attr('data-bfi-disabled') != 'undefined') {
	  return;
	}

	// Set the word to be displayed on the button
	var buttonWord = 'Browse';

	if (typeof $elem.attr('title') != 'undefined') {
	  buttonWord = $elem.attr('title');
	}

	var className = '';

	if (!!$elem.attr('class')) {
	  className = ' ' + $elem.attr('class');
	}

	// Now we're going to wrap that input field with a Bootstrap button.
	// The input will actually still be there, it will just be float above and transparent (done with the CSS).
	$elem.wrap('<a class="file-input-wrapper btn btn-default ' + className + '"></a>').parent().prepend($('<span></span>').html(buttonWord));
  })

  // After we have found all of the file inputs let's apply a listener for tracking the mouse movement.
  // This is important because the in order to give the illusion that this is a button in FF we actually need to move the button from the file input under the cursor. Ugh.
  .promise().done( function(){

	// As the cursor moves over our new Bootstrap button we need to adjust the position of the invisible file input Browse button to be under the cursor.
	// This gives us the pointer cursor that FF denies us
	$('.file-input-wrapper').mousemove(function(cursor) {

	  var input, wrapper,
		wrapperX, wrapperY,
		inputWidth, inputHeight,
		cursorX, cursorY;

	  // This wrapper element (the button surround this file input)
	  wrapper = $(this);
	  // The invisible file input element
	  input = wrapper.find("input");
	  // The left-most position of the wrapper
	  wrapperX = wrapper.offset().left;
	  // The top-most position of the wrapper
	  wrapperY = wrapper.offset().top;
	  // The with of the browsers input field
	  inputWidth= input.width();
	  // The height of the browsers input field
	  inputHeight= input.height();
	  //The position of the cursor in the wrapper
	  cursorX = cursor.pageX;
	  cursorY = cursor.pageY;

	  //The positions we are to move the invisible file input
	  // The 20 at the end is an arbitrary number of pixels that we can shift the input such that cursor is not pointing at the end of the Browse button but somewhere nearer the middle
	  moveInputX = cursorX - wrapperX - inputWidth + 20;
	  // Slides the invisible input Browse button to be positioned middle under the cursor
	  moveInputY = cursorY- wrapperY - (inputHeight/2);

	  // Apply the positioning styles to actually move the invisible file input
	  input.css({
		left:moveInputX,
		top:moveInputY
	  });
	});

	$('body').on('change', '.file-input-wrapper input[type=file]', function(){

	  var fileName;
	  fileName = $(this).val();

	  // Remove any previous file names
	  $(this).parent().next('.file-input-name').remove();
	  if (!!$(this).prop('files') && $(this).prop('files').length > 1) {
		fileName = $(this)[0].files.length+' files';
	  }
	  else {
		fileName = fileName.substring(fileName.lastIndexOf('\\') + 1, fileName.length);
	  }

	  // Don't try to show the name if there is none
	  if (!fileName) {
		return;
	  }

	  var selectedFileNamePlacement = $(this).data('filename-placement');
	  if (selectedFileNamePlacement === 'inside') {
		// Print the fileName inside
		$(this).siblings('span').html(fileName);
		$(this).attr('title', fileName);
	  } else {
		// Print the fileName aside (right after the the button)
		$(this).parent().after('<span class="file-input-name">'+fileName+'</span>');
	  }
	});

  });

};

// Add the styles before the first stylesheet
// This ensures they can be easily overridden with developer styles
var cssHtml = '<style>'+
  '.file-input-wrapper { overflow: hidden; position: relative; cursor: pointer; z-index: 1; }'+
  '.file-input-wrapper input[type=file], .file-input-wrapper input[type=file]:focus, .file-input-wrapper input[type=file]:hover { position: absolute; top: 0; left: 0; cursor: pointer; opacity: 0; filter: alpha(opacity=0); z-index: 99; outline: 0; }'+
  '.file-input-name { margin-left: 8px; }'+
  '</style>';
$('link[rel=stylesheet]').eq(0).before(cssHtml);

})(jQuery);