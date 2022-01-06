function transicion(mensaje){
	$.blockUI({
	    css: {
	        border: 'none',
	        padding: '15px',
	        backgroundColor: '#000',
	        '-webkit-border-radius': '10px',
	        '-moz-border-radius': '10px',
	        opacity: .5,
	        color: '#fff'
	    },
	    message: "<h1>"+mensaje+"</h1>"
	});
}

function transicionSalir(){
	$.unblockUI();
}