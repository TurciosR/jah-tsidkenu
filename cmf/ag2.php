<?php
include '_conexion.php';

echo "<table>";
echo "<tr> <td>SKU</td> <td>id_producto</td> <td>id_presentacion</td> <td>Producto</td> <td>Existencias</td> <td>PRECIO1</td><td>PRECIO2</td> </tr>";
$sql  = _query("SELECT * FROM producto LEFT join stock on stock.id_producto=producto.id_producto WHERE id_categoria in ( 1,3) ");
while($row = _fetch_array($sql))
{
  $presentacion = _fetch_array(_query("SELECT presentacion_producto.id_presentacion, MAX(presentacion_producto.unidad) as unidad FROM presentacion_producto WHERE presentacion_producto.id_producto=$row[id_producto]"));
  $existencias  = round($row["stock"]/$presentacion["unidad"]);

  $precios = _query("SELECT precio FROM presentacion_producto_precio where id_presentacion= $presentacion[id_presentacion] LIMIT 2");

  $p1=0;
  $p2=0;
  $i=0;
  while($rp = _fetch_array($precios))
  {
    if($i==0)
    {
      $p1=$rp["precio"];
    }
    else
    {
      $p2=$rp["precio"];
    }
    $i++;
  }
  echo "<tr> <td>$row[ci]</td> <td>$row[id_producto]</td> <td>$presentacion[id_presentacion]</td> <td>$row[descripcion]</td> <td>$existencias</td> <td>$p1</td> <td>$p2</td> </tr>";
}
echo "</table>";
 ?>
