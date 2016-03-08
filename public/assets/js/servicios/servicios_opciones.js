jQuery(function($) {	

	$('#formulario_servicio').validate({		
		/* @validation states + elements 
		------------------------------------------- */
		errorClass: "state-error",
		validClass: "state-success",
		errorElement: "em",
		onkeyup: false,
		onclick: false,	
		
		rules: {					
			
			nombre_servicio : {
				required: true
			},
			
			et_meta_tipo: {
				required: true
			},
			
			et_meta_precio: {
				required: true
			},
			
			'post-title': {
				required: true
			},
			
			et_meta_hora_inicio: {
				required: true
			},
			
			et_meta_cupos: {
				required: true
			},
			
			'servicios-categoria': {
				required: true
			},
			
			imagen_destacada:{
				required:true,
				extension:"jpeg|jpg|png"
			},								
			
			posttext: {
				required: true,
				minlength: 30
			}												              
		}, // end rules
		
		messages: {					
			nombre_servicio: {
                required: 'Indique el nombre de su servicio'
            },
						
			et_meta_tipo: {
                required: 'Indique que tipo de servicio es'
            },
            
            et_meta_precio: {
				required: 'Indique el costo de este servicio'
			},
			
			'post-title': {
				required: 'Indique el nombre del servicio'
			},
			
			et_meta_hora_inicio: {
				required: 'Por favor defina la hora a la que inicia este servicio'
			},
			
			et_meta_cupos: {
				required: 'Por favor defina el cupo maximo de usuarios por evento'
			},
			
			'servicios-categoria': {
				required: 'Por favor selecciona la categoría a la que pertenece tu servicio'
			},
			
			imagen_destacada:{
				required:'Necesitas una imagen para tu servicio',
				extension:'Lo sentimos, la extensión de su imagen no está permitida'
			},	
						
			posttext: {
				required: 'Recuerda dar una breve descripci&oacute;n de este servicio',
				minlength: 'La descripci&oacute;n debe tener m&aacute;s de 30 caracteres'
			}												                   
		}, // end messages 
		
		highlight: function(element, errorClass, validClass) {
            $(element).closest('.field').addClass(errorClass).removeClass(validClass);
        }, // end highlight
                                
        unhighlight: function(element, errorClass, validClass) {
            $(element).closest('.field').removeClass(errorClass).addClass(validClass);
        }, // end unhighlight

        errorPlacement: function(error, element) {
            if (element.is(":radio") || element.is(":checkbox")) {
                element.closest('.option-group').after(error);
            } else {
                error.insertAfter(element.parent());
            }
        } // end error placement  
	}); // end validate
	
	function check_editor() {
	    var regX = /(<([^>]+)>)/ig;
	    htmlcon = jQuery('#description_ifr').contents().find("body").html();	
		
		char =  htmlcon.replace(regX, "");
	
		// check character limit
	
		if(char.length < 140) {
			alert("La descripción de tu servicio debe tener menos de 140 caracteres");
			jQuery('#error_descripcion').html("La descripción no puede estar vacia");	
			jQuery('#error_descripcion').show();
			
			return false;
		
		} else {
			
			return true;
		}
	    
		return false;
	}
	

});