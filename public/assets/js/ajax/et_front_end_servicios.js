jQuery(function ( $ ) {
       	
	/* ************************************* */
	// AJAX NOTIFICAR SOLICITUD DE PRECIO
	/* ************************************* */
	jQuery(".mostrar_precio").click(function( event ) {
		
		var id_seleccionado;
		
		id_seleccionado = jQuery(this).attr("id") ;
		id_seleccionado = id_seleccionado.split('_');
		id_seleccionado = id_seleccionado[2];
						    
	    $.ajax({
	        url: ajax_object.ajax_url,
	        type: "POST",
	        //dataType: "JSON",
	        data: { 
				action: 'et_notificar_solicitud_precio',
				id_servicio: id_seleccionado
            },      
	         
	        success: function( data, textStatus, jqXHR ) { // Si todo salio bien se ejecuta esto
				
				console.log("Reportado al profesional");
			
			}
        })
        
        .fail(function( jqXHR, textStatus, errorThrown, data ) { // Si todo salio MAL se ejecuta esto
			alert('Ocurrio un error y no se pudo procesar su solicitud correctamente.');

        });
		
	});
	/* ****************************** */

    
});

	