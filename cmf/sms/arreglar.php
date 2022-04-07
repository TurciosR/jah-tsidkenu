<?php
include '../_conexion.php';
$sql = _query("SELECT id_sms, telefono as numero, mensaje, lote, hora FROM sms");

while($row=_fetch_array($sql))
{
  $text="";
  if (strpos($row['mensaje'],"AM")) {

    $text= explode("AM",$row['mensaje']);

    $text[0]=trim(substr($text[0],0,-6));
    echo $text[0]." ".hora($row['hora']).$text[1]."<br>";
  }
  else {
    if (strpos($row['mensaje'],"PM")) {
      $text= explode("PM",$row['mensaje']);
      $text[0]=trim(substr($text[0],0,-6));
      echo $text[0]." ".hora($row['hora']).$text[1]."<br>";

    }
    else {
      echo $row['mensaje']."<br>";
    }
  }
}
 ?>
