<?php
include_once '_conexion.php';

$arrayName = array(
  "A-SOBRE",
  "A-1",
  "A-2",
  "A-3",
  "A-4",
  "A-5",
  "A-6",
  "B-SOBRE",
  "B-1",
  "B-2",
  "B-3",
  "B-4",
  "B-5",
  "B-6",
  "E-SOBRE",
  "E-1",
  "E-2",
  "B-3",
  "B-4",
  "B-5",
  "B-6",
  "D-SOBRE",
  "D-1",
  "D-2",
  "D-3",
  "D-4",
  "D-5",
  "F-SOBRE",
  "F-1",
  "F-2",
  "F-3",
  "F-4",
  "F-5",
  "G-SOBRE",
  "G-1",
  "G-2",
  "G-3",
  "G-4",
  "G-5",
  );

  foreach ($arrayName as $key => $value) {
    echo "$value <br>";

    $estante = $value;
  	$ubicacion = 1;
  	$npos = 1;
      $sql_result= _query("SELECT * FROM estante WHERE descripcion='$estante' AND id_ubicacion='$ubicacion'");
      $row_update=_fetch_array($sql_result);
      $numrows=_num_rows($sql_result);

      $table = 'estante';
      $form_data = array (
      	'id_ubicacion' => $ubicacion,
      	'descripcion' => $estante
      );
      _begin();
      if($numrows == 0 && trim($estante)!='' && $ubicacion !="")
      {

      	$insertar = _insert($table,$form_data);
  	    if($insertar)
  	    {
  	    	$id_estante = _insert_id();
  	    	$table_aux = "posicion";
  	    	$j=0;
  	    	for($i=0; $i<$npos; $i++)
  	    	{
  	    		$posicion = $i+1;
  	    		$form_data_aux = array(
  	    			'id_ubicacion' => $ubicacion,
  	    			'id_estante' => $id_estante,
  	    			'posicion' => $posicion
  	    		);
  	    		$insert_aux = _insert($table_aux, $form_data_aux);
  	    		if($insert_aux)
  	    		{
  	    			$j++;
  	    		}
  	    	}
  	    	if($j==$npos)
  	    	{
  	    		_commit();
  		        $xdatos['typeinfo']='Success';
  		        $xdatos['msg']='Registro ingresado correctamente!';
  		        $xdatos['process']='insert';
  		    }
  		    else
  		    {
  		    	_rollback();
  		    	$xdatos['typeinfo']='Error';
  		       	$xdatos['msg']='Registro no pudo ser ingresado !';
  		       	$xdatos['process']='none';
  		    }
  	    }
  	    else
  	    {
  	    	_rollback();
  	       $xdatos['typeinfo']='Error';
  	       $xdatos['msg']='Registro no pudo ser ingresado !';
  	       $xdatos['process']='none';
  		}
      }
     	else
     	{
     		$xdatos['typeinfo']='Error';
         	$xdatos['msg']='Ya se registro un estante con estos datos!';
         	$xdatos['process']='none';
     	}
  	echo json_encode($xdatos);
  }

 ?>
