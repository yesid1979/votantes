<?php
require 'conexion.php';
$id=$_GET['codigo'];
$sql = "SELECT t1.num_orden,t1.cod_barra,t1.nom_fuid,t3.nom_categoria FROM detalle_fuid t1 
INNER JOIN fuid t2 on t1.id_fuid=t2.id_fuid 
INNER JOIN categoria t3 ON t3.id_categoria=t2.cod_categoria
where t1.cod_barra is not null and t1.id_detalle='$id'";
$mysqli->set_charset('utf8');
$resultado = $mysqli->query($sql);
if($resultado->num_rows > 0 )
{
  while ($row = $resultado->fetch_assoc())
  {
    $num_proceso=$row['num_orden'];
    $cod_barra=$row['cod_barra'];
    $accion= strtoupper($row['nom_categoria']);
    $nom_fuid=$row['nom_fuid'];
  }
?>
<script language="javascript"> 
function imprimir() 
{ 
   	if ((navigator.appName == "Netscape"))
	{ 
   	  window.print() ;  
    }
	else
	{
     	var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>'; 
        document.body.insertAdjacentHTML('beforeEnd', WebBrowser); WebBrowser1.ExecWB(6,-1); WebBrowser1.outerHTML = "";
    }
}
</script > 
<style>
<!--
.logo{position: absolute; top: 0px; left: 0px; }
.linea0{position: absolute; top: 5px; left: 72px; }
.linea1{position: absolute; top: 9px; left: 72px; }
.linea2{position: absolute; top: 22px; left: 72px;}
.linea3{position: absolute; top: 34px; left: 72px;}
.linea4{position: absolute; top: 61px; left: 3px; }
.linea5{position: absolute; top: 70px; left: 3px; }
.linea6{position: absolute; top: 81px; left: 3px; }
.linea7{position: absolute; top: 92px; left: 3px; }
.linea8{position: absolute; top: 103px; left: 3px; }
.linea9{position: absolute; top: 115px; left: 3px; }
header, footer, nav, aside {
  display: none;
}
-->
</style>

<body onload="imprimir();">

<div class="logo">
<img src="imagenes/logoalcaldia.jpg" width="70" height="60"  border="0" align="absmiddle" />
</div>

<div class="linea0">
<span style="font-size:10px;color:#000000;font-family:Arial;" >
<b>    No. PROCESO. <?php echo $num_proceso; ?></b>
</span>
</div>

<div class="linea1">
<span style="font-size:10px;color:#000000;font-family:Arial;" >
<b><p><?php echo $nom_fuid; ?></p></b>
</span>
</div>

<div class="linea2">
<span style="font-size:10px;color:#000000;font-family:Arial;" >
<b><p><?php echo $accion; ?></p></b>
</span>
</div>

<!--div class="linea3">
<span style="font-size:10px;color:#000000;font-family:Arial;" >
<b><p>Fecha Radicado 28/07/2017 12:00:49</p></b>
</span>
</div>

<div class="linea4">
<span style="font-size:8px;color:#000000;font-family:Arial;" >
Usuario Radicador YESID.PIEDRAHITA</span>
</div>

<div class="linea4">
<span style="font-size:8px;color:#000000;font-family:Arial;" >
<p style="position:absolute; left:165px; top: -8px;">Folios:</p>
</span>
</div>

<div class="linea5">
<span style="font-size:8px;color:#000000;font-family:Arial;" >
Destino DEPARTAMENTO ADMINISTRATIVO DE GESTION JURIDICA PUBLICA</span>
</div>

<div class="linea6">
<span style="font-size:8px;color:#000000;font-family:Arial;" >
Remitente (FUN) Despacho Direccion  - 4121010</span>
</div>

<div class="linea7">
<span style="font-size:8px;color:#000000;font-family:Arial;" >
Visite Nuestra Pagina - http://www.cali.gov.co
</span>
</div>

<div class="linea8">
<span style="font-size:8px;color:#000000;font-family:Arial;" >
Santiago de Cali (Valle del Cauca) Cam Torre Alcaldia, Linea 195
</span>
</div-->

<div class="linea4">
<img src="barcode.php?text=<?php echo $cod_barra; ?>&size=30&orientation=horizontal&codetype=Code39&print=true&sizefactor=1" /> 
</div>

</body>
<?php
}
else
{
  print_r('No hay resultados para mostrar');
} 
?>
