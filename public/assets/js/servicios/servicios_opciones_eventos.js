jQuery(function($) {	
	
	var tipo_evento;
	var startDate;
	var stopDate;
	var Contenido_HTML;
	var id_general;
	var bloque;
	
	// ***************************************************
	// CUANDO CAMBIA LA HORA EN UN FORMULARIO
	// ***************************************************
	jQuery("#formulario_servicio").on("change", ".tiempo", function() {
		seccion = jQuery(this).closest("section").attr("id");
						
		id_selected = jQuery(this).attr("id");
		id_selected = id_selected.split('_');
		id_selected = id_selected[1];
				
		var hora 	= jQuery("#" + seccion + " #hora_" 		+ id_selected).val();
		var minuto 	= jQuery("#" + seccion + " #minuto_" 	+ id_selected).val();
		var gmt 	= jQuery("#" + seccion + " #gmt_" 		+ id_selected).val();
		
		var hora_final = String(hora +":"+ minuto +" "+ gmt) ;
			
		jQuery("#" + seccion + " #resultado_hora_" + id_selected).val(hora_final);
	
	});
	// ***************************************************
	
	jQuery("#formulario_servicio").on("change", ".tiempo_evento", function() {
	
		id_selected = jQuery(this).attr("id");
		elementos	= id_selected.split('_');
		horario 	= elementos[0];
		id_selected = elementos[1];
				
		var hora 	= jQuery("#" + horario + "_hora" 	).val();
		var minuto 	= jQuery("#" + horario + "_minuto"	).val();
		var gmt 	= jQuery("#" + horario + "_gmt" 	).val();
		
		var hora_final = String(hora +":"+ minuto +" "+ gmt) ;
		
		jQuery("#" + horario  + "_horario").val(hora_final);
		
	});
	
	
	$.datepicker.regional['es'] = {
	    closeText: 'Cerrar',
	    prevText: '&#x3c;',
	    nextText: '&#x3e;',
	    currentText: 'Hoy',
	    monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
	    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
	    dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
	    dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
	    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
	    weekHeader: 'Sm',
	    dateFormat: 'yy-mm-dd',
	    firstDay: 1,
	    isRTL: false,
	    showMonthAfterYear: false,
	    yearSuffix: '',
	    minDate: 0
	};
	
	$.datepicker.setDefaults($.datepicker.regional['es']);
    
    
    // ***************************************************
	// REVISA QUE TIPO DE EVENTO FUE SELECCIONADO
	// ***************************************************
	jQuery('input[type=radio][name=tipo-evento]').change(function() {
        
        jQuery("#date_from").val("");
        jQuery("#date_to").val("");
        
        startDate = "";
		stopDate = "";
   	     
        if (this.value == 'tipo-evento-corrido') {
			jQuery(".horario_evento").show();
			tipo_evento = "tipo-evento-corrido";
        }
        
        else if (this.value == 'tipo-evento-cupos') {
            jQuery(".horario_evento").hide();
            tipo_evento = "tipo-evento-cupos";
        }
        
        validarCampos();
    });
	// ***************************************************
    
	// ***************************************************
	// CONSIGO LA FECHA INICIO
	// ***************************************************
    $( "#date_from" ).datepicker({
		minDate: 0,
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1,
		onClose: function( selectedDate ) {
			$( "#date_to" ).datepicker( "option", "minDate", selectedDate );
			
			startDate = selectedDate;
			validarCampos();
		}
    });
	// ***************************************************
	
	// ***************************************************
	// CONSIGO LA FECHA FIN
	// ***************************************************
    $( "#date_to" ).datepicker({
		minDate: 0,
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1,
		onClose: function( selectedDate ) {
			$( "#date_from" ).datepicker( "option", "maxDate", selectedDate );
			
			stopDate = selectedDate;
			validarCampos();
		}     
    });	
	// ***************************************************
	
	function validarCampos() {
		
		jQuery(".frm-row.contenedor_dias").empty();	
				
		if (!startDate || !stopDate || !tipo_evento ) {
			return;
		}
				
		getDates(startDate, stopDate);
		
	}
	
	// ***************************************************
	// CONTIGO EL RANGO DE FEHCAS DE FECHA INICIO A FECHA FIN
	// ***************************************************
	function getDates(startDate, stopDate) {
				
		id_general = 1;
		bloque = 1;
		
		var daysOfRange = [];
		var inicio 	= startDate.split('-');
		var fin		= stopDate.split('-');

		var inicio 	= new Date(inicio[0] , (inicio[1] - 1 ), inicio[2] );
		var fin 	= new Date(fin[0] , (fin[1] - 1) , fin[2] );

		for ( var d = inicio ; d <= fin ; d.setDate(d.getDate() + 1) ) {
		    daysOfRange.push(new Date(d) );
		}
				
		if (tipo_evento == "tipo-evento-cupos") {	
			cargar_cupos_dia ( daysOfRange );
		} else if (tipo_evento == 'tipo-evento-corrido') { 
			cargar_cupos_evento_corrido( daysOfRange );
		}
    };	
    // ***************************************************
	
	
	function cargar_cupos_evento_corrido ( daysOfRange ){
		
		
			
	}
	// ***************************************************
	
	// ***************************************************
	// CREA LA LISTA DE LOS DIAS AL SELECCIONAR EL CALENDARIO
	// ***************************************************
	function cargar_cupos_dia ( daysOfRange ){
		
		var mes = "";
		var fecha = "";
		var Contenido_HTML = "";
			
		jQuery.each(daysOfRange, function(index, value) {
			
			mes = ( this.getMonth() + 1 );
			
			if (mes < 10) {
				mes = "0" + mes;
			}
			
			fecha = this.getFullYear() + "-" + mes + "-" + this.getDate(); 
			
			Contenido_HTML += 	"<div class='section colm colm12'>";
			
			Contenido_HTML += 		"<label class='option block'>";
			Contenido_HTML += 			"<input type='checkbox' id='bloque_"+fecha+"' class='bloque_dia' name='bloques_dias["+fecha+"][activo]' checked />";
			Contenido_HTML +=			"<span class='checkbox'></span>" + fecha ;
			Contenido_HTML +=		"</label>";
			
			Contenido_HTML +=		"<section id='seccion_bloque_"+fecha+"' class='seccion_bloque_dia'>";
			
			Contenido_HTML +=		"</section>";
			
			Contenido_HTML += 	"</div>";
		});
		
		jQuery(".frm-row.contenedor_dias").html(Contenido_HTML);	
		
		jQuery( ".seccion_bloque_dia" ).each(function( index ) {
			agregar_bloque(this.id);
		});
	}
	// ***************************************************
	
	
	// ***************************************************
	// AGREGAR BLOQUE
	// ***************************************************
	function agregar_bloque( nombre_seccion ) {	
		var bloques = jQuery("#" + nombre_seccion + " > .bloque_hora").length ; 
		
		bloque = bloques + 1;
		
		jQuery("#"+nombre_seccion).append("<div class='bloque_hora' id='bloque_"+bloque+"'></div>");
		jQuery( "#bloque_" + bloque ).append( bloque_servicio ( nombre_seccion ) );
	};
	// ***************************************************
		
	function bloque_servicio ( nombre_seccion ) {				
		var id_nombre_seccion = "#"+nombre_seccion;
		var bloque_id = id_nombre_seccion + " #bloque_" + bloque;
		
		var fila 	= nombre_seccion + "_fila_" + id_general
		var id_fila = id_nombre_seccion + "_fila_" + id_general ;
		
		var id_result = bloque_id + "_result_select_" + id_general;	
		
		jQuery(bloque_id).append("<div class='frm-row' id='"+fila+"'></div>");
		
		
		var fecha_bloque = nombre_seccion.split('_');
		fecha_bloque = fecha_bloque[2];
			
		/* AGREGAR BLOQUE DE HORA DISPONIBLES */			
		var id_seccion = nombre_seccion + "_hora_select_" + bloque;
		Contenido_HTML = 	"<div class='section colm colm6'>";
		Contenido_HTML += 		"<label for='disponibilidad' class='field-label'>Hora de inicio<em> * </em></label>";
		Contenido_HTML += 		"<label for='"+bloque+"' class='field select input_hora'>";
		Contenido_HTML += 			"<select name='et_meta_hora' class='tiempo' id='hora_" + bloque + "'></select>";
		Contenido_HTML += 			"<i class='arrow double'></i>"
		Contenido_HTML += 		"</label>"
		
		/* AGREGAR BLOQUE DE MINUTOS DISPONIBLES */			
		var id_seccion = nombre_seccion + "_minuto_select_" + bloque;
		Contenido_HTML += 		"<label for='"+bloque+"' class='field select input_hora'>";
		Contenido_HTML += 			"<select name='et_meta_minuto' class='tiempo' id='minuto_" + bloque + "'></select>";
		Contenido_HTML += 			"<i class='arrow double'></i>"
		Contenido_HTML += 		"</label>"
		
		/* AGREGAR BLOQUE DE GMT DISPONIBLES */			
		var id_seccion = nombre_seccion + "_gmt_select_" + bloque;
		Contenido_HTML += 		"<label for='"+bloque+"' class='field select input_hora'>";
		Contenido_HTML += 			"<select name='et_meta_gmt' class='tiempo' id='gmt_" + bloque + "'></select>";
		Contenido_HTML += 			"<i class='arrow double'></i>";
		Contenido_HTML += 		"</label>"
						
		/* AGREGAR HIDDEN FIELD */
		Contenido_HTML += 		"<input type='hidden' name='bloques_dias["+ fecha_bloque +"][" + bloque + "][et_meta_hora_inicio]' id='resultado_hora_" + bloque + "' value='01:00 AM' />";
		Contenido_HTML += 	"</div>";
		jQuery(id_fila).append(Contenido_HTML);
		
		/* AGREGAR DROP DOWN HORAS */
		for ( var i = 1; i < 13; i++ ) {
				var horas = i; 				
				if (horas < 10) {horas   = "0"+horas;}
				jQuery(id_nombre_seccion + " #hora_"+bloque).append("<option value='"+horas+"'>"+horas+"</option>");
			}
		/* AGREGAR DROP DOWN HORAS */
		
		/* AGREGAR DROP DOWN MINUTOS */
		for ( var i = 0; i < 12; i++ ) {
				var minutos = i * 5; 				
				if (minutos < 10) {minutos = "0"+minutos;}
				jQuery( id_nombre_seccion + " #minuto_" + bloque ).append("<option value='"+minutos+"'>"+minutos+"</option>");
			}
		/* AGREGAR DROP DOWN MINUTOS */
		
		/* AGREGAR DROP DOWN MINUTOS */
			jQuery( id_nombre_seccion + " #gmt_"+ bloque ).append("<option value='AM'>AM</option>");
			jQuery( id_nombre_seccion + " #gmt_"+ bloque ).append("<option value='PM'>PM</option>");
		/* AGREGAR DROP DOWN GMT */	
		
		/* AGREGA DURACION */
		var id_seccion = nombre_seccion + "_duracion_select_" + id_general;
		Contenido_HTML = 	"<div class='section colm colm3'>";
		Contenido_HTML += 		"<label for='bloques_dias["+ fecha_bloque +"][" + bloque + "][et_meta_duracion]' class='field-label'>Duraci&oacute;n <em> * </em> </label>";
		Contenido_HTML += 		"<label class='field select'>";
		Contenido_HTML += 			"<select name='bloques_dias["+ fecha_bloque +"][" + bloque + "][et_meta_duracion]' id='" + id_seccion + "'></select>";
		Contenido_HTML += 			"<i class='arrow double'></i>";
		Contenido_HTML += 		"</label>";
		Contenido_HTML += 	"</div>";
		
		jQuery(id_fila).append(Contenido_HTML);
											
		for ( var i = 1; i < 21; i++ ) {
			
			var result = "";
			
			var minutos = i * 15; 
			var horas = Math.floor(minutos / 60);
			
			if (minutos % 60 != 0)
		 		var cuarto_hora = (minutos % 60) + " minutos";
		 	else 
		 		cuarto_hora = "";
			
			if (horas < 1) 
				result = minutos + " minutos" ; 
				
			if (horas == 1) 
				result = horas + " hora " + cuarto_hora;
		
			else if (horas > 1) 
			 	result = horas + " horas " + cuarto_hora;
		
			jQuery("#"+id_seccion).append("<option value='"+minutos+"'>"+result+"</option>");
		}
		/* AGREGA DURACION */									
		
		/* AGREGA CANTIDAD DE CUPOS */
		var id_seccion = bloque_id + "_duracion_select_" + id_general;
		Contenido_HTML = 	"<div class='section colm colm2'>  ";
		Contenido_HTML += 		"<label for='bloques_dias["+ fecha_bloque +"][" + bloque + "][et_meta_cupos]' class='field-label'>Cupos <em> * </em> </label>";
		Contenido_HTML += 		"<label for='bloques_dias["+ fecha_bloque +"][" + bloque + "][et_meta_cupos]' class='field prepend-icon'>";
		Contenido_HTML += 			"<input type='number' min='1' name='bloques_dias["+ fecha_bloque +"][" + bloque + "][et_meta_cupos]' id='et_meta_cupos' class='gui-input cantidad_cupos required' placeholder=''>"
		Contenido_HTML += 			"<label for='cupos' class='field-icon'>"
		Contenido_HTML += 				"<i class='fa fa-ticket'></i>";
		Contenido_HTML += 			"</label>";
		Contenido_HTML += 		"</label>";
		Contenido_HTML += 	"</div>";
		jQuery(id_fila).append(Contenido_HTML);
		
		/* AGREGA CANTIDAD DE CUPOS */
						
		Contenido_HTML = 	"<div class='section botones' id='botones_" + bloque + "'>";
		Contenido_HTML += 		"<span id='"+bloque+"' class='eliminar_bloque'></span>";
		Contenido_HTML +=		"<span id='"+bloque+"' class='agregar_bloque'></span>";
		Contenido_HTML +=	"</div>";						
		jQuery(bloque_id).append(Contenido_HTML);
		
		Contenido_HTML = "";
		id_general++;
	
	}
	
	jQuery("#formulario_servicio").on("click", ".agregar_bloque" ,function(){
		seccion = jQuery(this).closest("section").attr("id");
		agregar_bloque( seccion );
	});

	jQuery("#formulario_servicio").on("click", ".eliminar_bloque" ,function(){
		seccion = jQuery(this).closest("section").attr("id");
		seccion_borrar = "#"+ seccion + " #bloque_" + this.id;
	
		jQuery(seccion_borrar).remove();
	});
	
	
	jQuery("#formulario_servicio").on("click", ".bloque_dia" ,function(){
		
		var nombre_seccion = "seccion_" + this.id ;
		
		/* SELECCIONE CLICK EN EL DIA */								    
	    if (jQuery(this).is(':checked')) {
			agregar_bloque( nombre_seccion );
		 	}
	    else {
		    jQuery("#"+nombre_seccion).empty();
	    } ;
		
	});
	
	
	jQuery("#et_meta_precio").numeric();
	jQuery(".cantidad_cupos").numeric();
	
	jQuery('form').on('focus', 'input[type=number]', function (e) {
		jQuery(this).on('mousewheel.disableScroll', function (e) {
			e.preventDefault()
		})
	});

	jQuery('form').on('blur', 'input[type=number]', function (e) {
		jQuery(this).off('mousewheel.disableScroll')
	});
	
	jQuery('#formulario_servicio').validate({
		errorClass: "state-error",
		validClass: "state-success",
    	errorElement: "em", 
    
		rules: {					
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
								
			posttext: {
				required: true,
				minlength: 30
			}												              
		}, // end rules
		
		messages: {					
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

	
});