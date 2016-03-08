jQuery(document).ready(function( $ ) {
	// http://xdsoft.net/jqplugins/datetimepicker/
	
	// php_vars trae por wp_localize_script las variable declaradas en class-servicios-frontend.php
		
	jQuery.datetimepicker.setLocale('es');
	
	var logic = function( date , input ){
	var cupos_disponibles;		
		/*
		if( currentDateTime.getDay()==6 ){
			this.setOptions({
				minTime:'11:00'
			});
		} 
	
		else
			this.setOptions({
				minTime:'8:00'
			});
		*/
				
		// jQuery("#loadingmessage").show();
		
/*
		jQuery.post(ajaxurl, data, function(response) {
			jQuery("#loadingmessage").hide();
			jQuery(".lista_cupos_disponibles").html(response);
			jQuery(".Contenedor_Cupos").css("display","block");
			
		});
*/
		
		$.ajax({
	        url	: ajax_object.ajax_url,
	        type: "POST",
	        datatype:"json",
	        data: { 
				"action"				: 'et_cargar_cupos_func',
				"id_servicio"			: php_vars.id_servicio,
				"fecha_seleccionada"	: input.val(),
				"dia_seleccionado"		: date.getDay(),
				"is_old"				: php_vars.old_version
            },      
	         
	        success: function( data, textStatus, jqXHR ) { // Si todo salio bien se ejecuta esto
				
				console.log(data);
				
				cupos_disponibles = mostrar_cupos( data , input.val() );
				
				jQuery(".lista_cupos_disponibles").html( cupos_disponibles );
			
			}
        })
        
        .fail(function( jqXHR, textStatus, errorThrown, data ) { // Si todo salio MAL se ejecuta esto
			
			alert('Ocurrio un error y no se pudo procesar su solicitud correctamente.');

        });
	};
	
	jQuery("#calendario_servicio").datetimepicker({
		
		onGenerate:function( ct ){
			//jQuery(this).find('.xdsoft_date.xdsoft_weekend')
			//.addClass('xdsoft_disabled');
		},
		//weekends:['01.01.2014','02.01.2014','03.01.2014','04.01.2014','05.01.2014','06.01.2014'],

		onChangeDateTime:logic,
		timepicker	:false,
		inline		:true,
		format		:'Y-m-d',
		minDate		:'0',//yesterday is minimum date(for today use 0 or -1970/01/01)
		//maxDate	:'+1970/01/02',//tomorrow is maximum date calendar
		dayOfWeekStart: 1,
		//disabledDates: [],  		
		
	})
	
	
	function mostrar_cupos( cupos , dia_seleccionado ) {
		var et_html;
		var cupos;
		var ocupados;
		var disponible;
		var tiene_reserva;
				
		if ( cupos == 0 )
			return et_html = "<h2 class='sin_cupos'>No hay cupos disponibles</h2>";
			
		// convierto el json que recivo y hago el loop por el
		et_html = "";
		cupos = JSON.parse(cupos);
				
		$.each(cupos.bloque , function( key, obj ) {
        	tiene_reserva = false;
        	disponible = obj.et_meta_cupos;
        	
        	$.each(cupos.ocupado , function ( hora , veces_repedito ) {
	        	
	        	if ( hora == obj.et_meta_hora_inicio ) {
		        	disponible = disponible - veces_repedito;
	        	}
	        	
        	});
        	
        	$.each(cupos.reservado , function ( hora , reservado ) {
	        	
	        	if ( hora == obj.et_meta_hora_inicio && reservado == true ) {
		        	tiene_reserva = true;
	        	}
	        	
        	});
        	
        	et_html += 	"<div class='cupoDisponible' id='cupoDisponible_"+ key +"'>";
        	
        	et_html += 		"<form class='formulario_reserva_cupo' id='hacer_reserva_"+ key +"' action='"+ php_vars.url_user +"citas/' method='post' >";
        	
        	et_html +=			"<header>"+ obj.et_meta_hora_inicio +"</header>";
			et_html +=	       	"<p>Duracion: " + obj.et_meta_duracion +" minutos</p>";
			et_html +=	   		"<p>Cupos Maximo: " + obj.et_meta_cupos +"</p>";
			et_html +=	   		"<p>Cupos Disponibles: "+ disponible +"</p> ";
        	
        	
			et_html +=		    "<input type='hidden' value='"+ php_vars.id_servicio +"' id='id_servicio_"+ php_vars.id_servicio +"' name='id_servicio'>";
			et_html +=		   	"<input type='hidden' value='"+ dia_seleccionado +"' id='servicio_dia_seleccionado' class='servicio_dia_seleccionado' name='servicio_dia_seleccionado'>";
			et_html +=		   	"<input type='hidden' value='"+ obj.et_meta_hora_inicio +"' id='et_meta_hora_inicio' name='et_meta_hora_inicio'>";
			et_html +=		   	"<input type='hidden' value='' id='et_meta_close_time' name='et_meta_close_time'> ";
        	
        	if (tiene_reserva) {
	        	et_html +=		"<input disabled type='submit' value='Ya reservaste' class='button btn-morado agotado' id='boton_reservar' name='agotado'>"	
        	}
        	
        	else if (disponible < 1 ) {
				et_html +=		"<input disabled type='submit' value='Cupos agotados' class='button btn-morado agotado' id='boton_reservar' name='agotado'>"
			}
			else {
	        	et_html +=		"<input type='submit' value='reservar' class='button btn-morado' id='boton_reservar' name='is_reserve'>";
        	}
        	
        	et_html += 		"</form>";
        	
        	et_html += 	"</div>";
        	
		});
		
		return et_html;
		
	}
	
});