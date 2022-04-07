
	$(document).ready(function() {
		$(".select").select2();
    $(".selec").select2();
		$("#venta").numeric({negative:false,decimalPlaces:2});
		$("#ingreso").numeric({negative:false,decimalPlaces:2});
    $("#venta").focus();
    generar();
	});

  $(document).on('change', '#idsucursal', function(event) {
    generar();
  });
  $(document).on('change', '#inicio', function(event) {
    generar();
  });
  $(document).on('change', '#fin', function(event) {
    generar();
  });


  function generar() {
    var url = base_url+"Libreta/get_data";
    dataTable = $('#editable2').DataTable().destroy()
    dataTable = $('#editable2').DataTable({
      "pageLength": 50,
      "serverSide": true,
      "order": [[1, "asc"]],
      "ajax": {
        url: url,
        type: 'POST',
        data:{
          id_sucursal: $("#idsucursal").val(),
          ini: $("#inicio").val(),
          fin: $("#fin").val(),
        }
      },
      "language": {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
          "sFirst":    "Primero",
          "sLast":     "Último",
          "sNext":     "Siguiente",
          "sPrevious": "Anterior"
        },
        "oAria": {
          "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
          "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
      },
    }); // End of DataTable
  }
  $(document).on('keydown', '#venta', function(event) {
    if (event.key=="Enter"&&$(this).val()!="")
    {
      $("#ingreso").focus();
    }
  });
  $(document).on('keydown', '#ingreso', function(event) {
    if (event.key=="Enter"&&$(this).val()!="")
    {
      $("#sucursal").select2("open");
    }
  });
  $(document).on('select2:close', '.select', function(event) {
    $("#concepto").focus();
  });

  $(document).on('click', '#guardar', function(event) {
    $(this).attr("disabled","disabled");
    var errors = false;
    var error_array = [];
    var venta  = $("#venta").val();
    var ingreso = $("#ingreso").val();
    var fecha = $("#fecha1").val();
    var concepto = $("#concepto").val();
    var sucursal = $("#sucursal").val();
    if (venta=="")
    {
      errors = true;
      error_array.push('Ingrese venta o un cero');
    }
    if (ingreso=="")
    {
      errors = true;
      error_array.push('Ingrese monto de ingreso o un cero');
    }
    if (fecha=="")
    {
      errors = true;
      error_array.push('Ingrese fecha');
    }
    if (concepto=="")
    {
      errors = true;
      error_array.push('Ingrese concepto');
    }

    if (errors == false)
    {
      $("#venta").val("");
      $("#ingreso").val("");
      $("#concepto").val("");
      $.ajax({
        url: base_url+"Libreta/agregar",
        type: 'POST',
        dataType: 'json',
        data: {
          venta: venta,
          ingreso: ingreso,
          sucursal: sucursal,
          concepto: concepto,
          fecha: fecha,
        },
        success:  function(xdatos)
        {
          $("#guardar").removeAttr('disabled');
          $("#guardar").show('slow/400/fast', function() {
          });
          $(".loader").hide('slow/400/fast', function() {
          });
          display_notify(xdatos.typeinfo,xdatos.msg);
          generar();
        }
      })
    }
    else
    {
      display_notify("Error",error_array.join(",<br>"));
      $("#guardar").removeAttr('disabled');
    }

  });

  $(document).on("click", ".elim", function()
  {
    var id = $(this).attr("id");
    swal({
        title: "Desea eliminar este registro",
        text: "Usted no podra deshacer este cambio",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, Eliminar",
        cancelButtonText: "No, Cerrar",
        closeOnConfirm: true
      },
      function() {
        $.ajax({
          type: "POST",
          url: base_url+"Libreta/borrar",
          data: "id=" + id,
          dataType: "JSON",
          success: function(datax) {
            if (datax.typeinfo == "success" || datax.typeinfo == "Success") {
              setTimeout("location.reload();", 1000);
            }
            display_notify(datax.typeinfo, datax.msg);
          }
        });
      });
  });

	$(document).on("click", ".edit", function()
	{
		var id = $(this).attr("id");

		$("#editar").removeAttr('disabled');

		$("#guardar").attr("disabled","disabled");

		$.ajax({
			url: base_url+"Libreta/getitem",
			type: 'POST',
			dataType: 'json',
			data: {
				id: id,
			},
			success:  function(xdatos)
			{

					$("#venta").val(xdatos.venta);
					$("#ingreso").val(xdatos.ingreso);
					$("#concepto").val(xdatos.descripcion);
					$("#fecha1").val(xdatos.fecha);
					$("#sucursal").val(xdatos.id_sucursal);
					$("#sucursal").trigger("change");
					$("#idl").val(xdatos.id);
					$("#venta").focus();
					document.body.scrollTop = 0; // For Safari
  				document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera

			}
		})

		$('#editar').unbind('click').click(function(){
			$('#editar').attr("disabled","disabled");
			var errors = false;
			var error_array = [];
			var venta  = $("#venta").val();
			var ingreso = $("#ingreso").val();
			var fecha = $("#fecha1").val();
			var concepto = $("#concepto").val();
			var sucursal = $("#sucursal").val();
			var idl = $("#idl").val();
			if (venta=="")
			{
				errors = true;
				error_array.push('Ingrese venta o un cero');
			}
			if (ingreso=="")
			{
				errors = true;
				error_array.push('Ingrese monto de ingreso o un cero');
			}
			if (fecha=="")
			{
				errors = true;
				error_array.push('Ingrese fecha');
			}
			if (concepto=="")
			{
				errors = true;
				error_array.push('Ingrese concepto m');
			}

			if (errors == false)
			{
				$("#venta").val("");
				$("#ingreso").val("");
				$("#concepto").val("");
				$.ajax({
					url: base_url+"Libreta/editar",
					type: 'POST',
					dataType: 'json',
					data: {
						venta: venta,
						ingreso: ingreso,
						sucursal: sucursal,
						concepto: concepto,
						fecha: fecha,
						idl: idl,
					},
					success:  function(xdatos)
					{
						setTimeout(500);
						display_notify(xdatos.typeinfo,xdatos.msg);
						generar();
						//$("#editar").removeAttr('disabled');
						$("#guardar").removeAttr('disabled');

					}
				})
			}
			else
			{
					display_notify("Error",error_array.join(",<br>"));
					$("#editar").removeAttr('disabled');

			}

		});

	});
