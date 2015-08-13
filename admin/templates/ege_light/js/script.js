$(document).ready(function(){
	//Uyarı mesajı penceresi
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
	
	//Hakkında penceresi
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

function isChecked(isitchecked) {
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