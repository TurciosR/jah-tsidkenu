<?php
$data = array(
	'titulo' => $titulo,
	'urljs' => 'funciones_vacaciones.js',
	'formulario' => array(
		'name'  => 'formulario',
		'id'    => 'formulario',
	),
	'campos' => array(
		'1' => array(
			'lenght'=>'6',
			'nombre'=>'Fecha de Inicio',
			'tipo' => 'text',
			'prop' => array(
				'type' => 'text',
				'name' => 'fecha_inicio',
				'id' => 'fecha_inicio',
				'class' => 'form-control datepicker',
				'placeholder' => 'Fecha de Inicio',
				'readonly'=>'',
				'value'=>$fecha1,
			),
		),
		'2' => array(
			'lenght'=>'6',
			'nombre'=>'Fecha de Fin',
			'tipo' => 'text',
			'prop' => array(
				'type'=>'text',
				'name' => 'fecha_fin',
				'id' => 'fecha_fin',
				'class' => 'form-control datepicker',
				'placeholder' => 'Fecha de Fin',
				'readonly'=>'',
				'value'=>$fecha2,
			),
		),
		'3' => array(
			'lenght'=>'6',
			'nombre'=>'Total de Dias',
			'tipo' => 'text',
			'prop' => array(
				'type'=>'text',
				'name' => 'total',
				'id' => 'total',
				'class' => 'form-control',
				'placeholder' => 'Total de Dias',
				'disabled'=>'',
			),
		),
		'4' => array(
			'lenght'=>'6',
			'nombre'=>'Total de Dias',
			'tipo' => 'text',
			'prop' => array(
				'type'=>'text',
				'name' => 'total',
				'id' => 'total',
				'class' => 'form-control',
				'placeholder' => 'Total de Dias',
				'disabled'=>'',
				),
		),
		'5' => array(
			'lenght'=>'12',
			'nombre'=>'Reemplazo',
			'tipo'=>'select',
			'prop' => array(
				'name' => 'id_reemplazo',
				'id' => 'id_reemplazo',
				'class' => 'form-control select',
				'valores'=>array(
				),
			),
		),
		'6' => array(
			'lenght'=>'12',
			'nombre'=>'Comentarios',
			'tipo'=>'textarea',
			'prop' => array(
				'name' => 'id_reemplazo',
				'id' => 'id_reemplazo',
				'class' => 'form-control',
				'cols' => '30',
				'rows' => '5',
			),
		),
	),
	'button'=>array(
		'type'=>'submit',
		'id'=>"btn_add",
		'name'=>"btn_add",
		'class'=>"btn btn-primary m-t-n-xs pull-right",
		'texto'=>'<i class="fa fa-save"></i> Guardar Solicitud'
	),
	'proccess'=>array(
		'type'=>'hidden',
		'id'=>"proccess",
		'name'=>"proccess",
		'value'=>'insert'
	),
);

$sql = $this->Utils_model->_query("SELECT CONCAT(c.nombre,' ',c.apellido) as colaborador,c.id_colaborador
FROM colaboradores as c JOIN unidades as u ON u.id_unidad=c.id_unidad
WHERE u.id_unidad=(SELECT id_unidad FROM colaboradores WHERE id_colaborador='$id_colaborador') AND c.id_colaborador!=$id_colaborador");
$fila = $this->Utils_model->_result($sql);
$assocData = array();
foreach ($fila as $row)
{
	$assocData += [ $row->id_colaborador => $row->colaborador ];
}
$data["campos"]["5"]["prop"]["valores"] = $assocData;*/
$data = array(
	'titulo' => $titulo,
	'urljs' => 'funciones_vacaciones.js',
	'formulario' => array(
		'name'  => 'formulario',
		'id'    => 'formulario',
	),
	'campos' => array(
		'1' => array(
			'lenght'=>'6',
			'nombre'=>'Fecha de Inicio',
			'tipo' => 'text',
			'prop' => array(
				'type' => 'text',
				'name' => 'fecha_inicio',
				'id' => 'fecha_inicio',
				'class' => 'form-control datepicker',
				'placeholder' => 'Fecha de Inicio',
				'readonly'=>'',
				'value'=>$fecha1,
			),
		),
		'2' => array(
			'lenght'=>'6',
			'nombre'=>'Fecha de Fin',
			'tipo' => 'text',
			'prop' => array(
				'type'=>'text',
				'name' => 'fecha_fin',
				'id' => 'fecha_fin',
				'class' => 'form-control datepicker',
				'placeholder' => 'Fecha de Fin',
				'readonly'=>'',
				'value'=>$fecha2,
			),
		),
		'3' => array(
			'lenght'=>'6',
			'nombre'=>'Reemplazo',
			'tipo'=>'select',
			'prop' => array(
				'name' => 'id_reemplazo',
				'id' => 'id_reemplazo',
				'class' => 'form-control',
				'valores'=>array(
					'small'=>'small shirt',
					'big'=>'big shirt'
				),
			),
		),
		'4' => array(
			'lenght'=>'6',
			'nombre'=>'Comentario',
			'tipo' => 'textarea',
			'prop' => array(
				'name' => 'fecha_fin',
				'id' => 'fecha_fin',
				'rows'=>'5',
				'cols'=>'30',
				'class' => 'form-control',
				'placeholder' => 'Ingrese su comentario',
			),
		),
		'5' => array(
			'lenght'=>'6',
			'nombre'=>'Seleccion un color',
			'tipo' => 'radio',
			'opciones' => array(
				'opc1'=>array(
					'type'=>'radio',
					'name'=>'optRadio',
					'id'=>'optRadio1',
					'value'=>'Opcion 1',
					'txt'=>'Opcion 1',
					'checked'=>'',
				),
				'opc2'=>array(
					'type'=>'radio',
					'name'=>'optRadio',
					'id'=>'optRadio',
					'txt'=>'Opcion 1',
					'value'=>'Opcion 2',
				),
				'opc3'=>array(
					'type'=>'radio',
					'name'=>'optRadio',
					'id'=>'optRadio3',
					'txt'=>'Opcion 1',
					'value'=>'Opcion 3',
				),
			),
		),
		'6' => array(
			'lenght'=>'6',
			'nombre'=>'Comentario',
			'tipo' => 'radio_inline',
			'opciones' => array(
				'opc1'=>array(
					'type'=>'radio',
					'name'=>'optRadio',
					'id'=>'optRadio1',
					'value'=>'Opcion 1',
					'txt'=>'Opcion 1',
					'checked'=>'',
				),
				'opc2'=>array(
					'type'=>'radio',
					'name'=>'optRadio',
					'id'=>'optRadio',
					'txt'=>'Opcion 1',
					'value'=>'Opcion 2',
				),
				'opc3'=>array(
					'type'=>'radio',
					'name'=>'optRadio',
					'id'=>'optRadio3',
					'txt'=>'Opcion 1',
					'value'=>'Opcion 3',
				),
			),
		),
		'7' => array(
			'lenght'=>'6',
			'nombre'=>'Elige',
			'tipo' => 'checkbox',
			'opciones' => array(
				'opc1'=>array(
					'type'=>'checkbox',
					'name'=>'optRadio',
					'id'=>'optRadio1',
					'value'=>'Opcion 1',
					'txt'=>'Opcion 1',
					'checked'=>'',
				),
				'opc2'=>array(
					'type'=>'checkbox',
					'name'=>'optRadio',
					'id'=>'optRadio',
					'txt'=>'Opcion 1',
					'value'=>'Opcion 2',
				),
				'opc3'=>array(
					'type'=>'checkbox',
					'name'=>'optRadio',
					'id'=>'optRadio3',
					'txt'=>'Opcion 1',
					'value'=>'Opcion 3',
				),
			),
		),
		'8' => array(
			'lenght'=>'6',
			'nombre'=>'Escoge',
			'tipo' => 'checkbox_inline',
			'opciones' => array(
				'opc1'=>array(
					'type'=>'checkbox',
					'name'=>'optRadio',
					'id'=>'optRadio1',
					'value'=>'Opcion 1',
					'txt'=>'Opcion 1',
					'checked'=>'',
				),
				'opc2'=>array(
					'type'=>'checkbox',
					'name'=>'optRadio',
					'id'=>'optRadio',
					'txt'=>'Opcion 1',
					'value'=>'Opcion 2',
				),
				'opc3'=>array(
					'type'=>'checkbox',
					'name'=>'optRadio',
					'id'=>'optRadio3',
					'txt'=>'Opcion 1',
					'value'=>'Opcion 3',
				),
			),
		),
	),
	'buton'=>array(
		'type'=>'button',
		'id'=>"btn",
		'name'=>"btn",
		'class'=>"btn",
	),

);
?>