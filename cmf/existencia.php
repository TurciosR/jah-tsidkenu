<?php
include_once '_conexion.php';

$sql=_query("SELECT id_producto,descripcion,ci FROM producto WHERE id_categoria=1");

?>
<table>
<?php
while ($row=_fetch_array($sql)) {
  $id_producto=$row['id_producto'];
  $descripcion=$row['descripcion'];
  $ci=$row['ci'];
  $precio=0;
  $unidad=1;
  $existencias=0;

  $sql2=_query("SELECT id_presentacion,unidad from presentacion_producto WHERE id_producto=$id_producto ORDER BY unidad DESC limit 1");

  echo _error();
  while ($row2=_fetch_array($sql2)) {
    // code...
    $id_presentacion=$row2['id_presentacion'];
    $unidad=$row2['unidad'];

    $sql3=_query("SELECT precio FROM presentacion_producto_precio WHERE id_presentacion=$id_presentacion AND desde=0");

    while ($row3=_fetch_array($sql3)) {
      // code...
      $precio=$row3['precio'];
    }

  }

  $sqls=_query("SELECT stock FROM stock WHERE id_producto=$id_producto");
  while ($rows=_fetch_array($sqls)) {
    // code...
    $existencias=$rows['stock'];
  }

  $existencias= round(($existencias/$unidad),2);

  ?>
  <tr>
    <td><?php echo $ci; ?></td>
    <td><?php echo number_format($existencias,2,".",""); ?></td>
    <td><?php echo $precio; ?></td>
    <td>E08B0320</td>
  </tr>
  <?php
}

?>

</table>
<?php
 ?>
