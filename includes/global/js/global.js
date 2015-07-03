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
	  minWidth: 350,
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
	  minWidth: 350,
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

//mesaj gönderme penceresi
$(document).ready(function() {
	$("#sendmessage").dialog({
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
 
	$( "#sendamsg" ).click(function() {
	  $( "#sendmessage" ).dialog( "open" );
	});
});

//forum yeni başlık penceresi
$(document).ready(function() {
	$("#newtopicwindow").dialog({
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
 
	$( ".newtopic" ).click(function() {
	  $( "#newtopicwindow" ).dialog( "open" );
	});
});

//forum yeni mesaj penceresi
$(document).ready(function() {
	$("#newmessagewindow").dialog({
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
 
	$( ".newmsg" ).click(function() {
	  $( "#newmessagewindow" ).dialog( "open" );
	});
});

		$(document).ready(function(){
			$('.figcaption').css('top','100%');

			$('.figure').hover(function(){
				$(this).find('.figcaption').stop().animate({'top':'0px'}, '200px', function(){});
			},function(){
				$(this).find('.figcaption').stop().animate({'top':'200px'}, '200px', function(){});
			});
		});