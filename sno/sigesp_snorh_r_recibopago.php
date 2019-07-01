<?php
    session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	/*if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../index.php'";
		print "</script>";
	}*/

	//$ls_logusr=$_SESSION["la_logusr"];


	//require_once("sigesp_sessiones.php");
	require_once("class_folder/class_funciones_nomina.php");
	//$io_fun_nomina=new class_funciones_nomina();
	//$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_r_recibopago.php",$ls_permisos,$la_seguridad,$la_permisos);


	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("sigesp_sno.php");
	//$io_sno=new sigesp_sno();
	//$ls_recibo=$io_sno->uf_select_config("SNR","REPORTE","RECIBO_PAGO","sigesp_snorh_rpp_recibopago.php","C");
	unset($io_sno);


$cedula_rp=$_SESSION['cedula_rp'];
$la_logusr=$_SESSION['la_logusr'];
$bienvenido=$_SESSION['bienvenido'];
$anocurso=$_POST["anocurso"];
//print_r($_POST);
include '../database.php';

$ls_operacion=$_POST["operacion"];//echo $ls_operacion;

if($ls_operacion=="CAMBIO_BD")
{
	if($anocurso=="2015")

	{
		$conn = pg_connect("user=".DB_USER." password=".DB_PASS." port=".DB_PORT." dbname=".DB_NAME3." host=".DB_HOST);

		$sql = "SELECT a.codnom,a.codper,b.nomper,b.apeper,c.desnom
		FROM sno_personalnomina a,sno_personal b, sno_thnomina c where b.cedper='$cedula_rp' and a.codper=b.codper and c.codnom=a.codnom order by a.codnom ASC";


      $sql = pg_query($conn, $sql);

	}

	if($anocurso=="2016")

	{
		$conn = pg_connect("user=".DB_USER." password=".DB_PASS." port=".DB_PORT." dbname=".DB_NAME2." host=".DB_HOST);

		$sql = "SELECT a.codnom,a.codper,b.nomper,b.apeper,c.desnom
		FROM sno_personalnomina a,sno_personal b, sno_thnomina c where b.cedper='$cedula_rp' and a.codper=b.codper and c.codnom=a.codnom order by a.codnom ASC";


      $sql = pg_query($conn, $sql);
	}

	if($anocurso=="2017")

	{
		$conn = pg_connect("user=".DB_USER." password=".DB_PASS." port=".DB_PORT." dbname=".DB_NAME1." host=".DB_HOST);

		$sql = "SELECT a.codnom,a.codper,b.nomper,b.apeper,c.desnom
		FROM sno_personalnomina a,sno_personal b, sno_thnomina c where b.cedper='$cedula_rp' and a.codper=b.codper and c.codnom=a.codnom order by a.codnom ASC";


      $sql = pg_query($conn, $sql);
	}

	if($anocurso=="2018_RECONVERSION")

	{
		$conn = pg_connect("user=".DB_USER." password=".DB_PASS." port=".DB_PORT." dbname=".DB_NAME4." host=".DB_HOST);

		$sql = "SELECT a.codnom,a.codper,b.nomper,b.apeper,c.desnom
		FROM sno_personalnomina a,sno_personal b, sno_thnomina c where b.cedper='$cedula_rp' and a.codper=b.codper and c.codnom=a.codnom order by a.codnom ASC";


      $sql = pg_query($conn, $sql);
	}


	if($anocurso=="2018")

	{
		$conn = pg_connect("user=".DB_USER." password=".DB_PASS." port=".DB_PORT." dbname=".DB_NAME." host=".DB_HOST);

		$sql = "SELECT a.codnom,a.codper,b.nomper,b.apeper,c.desnom
		FROM sno_personalnomina a,sno_personal b, sno_thnomina c where b.cedper='$cedula_rp' and a.codper=b.codper and c.codnom=a.codnom order by a.codnom ASC";


      $sql = pg_query($conn, $sql);
	}

	if($anocurso=="2019")

	{
		$conn = pg_connect("user=".DB_USER." password=".DB_PASS." port=".DB_PORT." dbname=".DB_NAME." host=".DB_HOST);

		$sql = "SELECT a.codnom,a.codper,b.nomper,b.apeper,c.desnom
		FROM sno_personalnomina a,sno_personal b, sno_thnomina c where b.cedper='$cedula_rp' and a.codper=b.codper and c.codnom=a.codnom order by a.codnom ASC";


      $sql = pg_query($conn, $sql);
	}

}
else
{

	$conn = pg_connect("user=".DB_USER." password=".DB_PASS." port=".DB_PORT." dbname=".DB_NAME." host=".DB_HOST);
	$sql = "SELECT a.codnom,a.codper,b.nomper,b.apeper,c.desnom
		FROM sno_personalnomina a,sno_personal b, sno_thnomina c where b.cedper='$cedula_rp' and a.codper=b.codper and c.codnom=a.codnom order by a.codnom ASC";


      $sql = pg_query($conn, $sql);


}

        $r=$sql;   //devuele true o false

		if ($r)
		{
			//si retorna data

			//echo "Helloooooooooooooooo";
			$filas = pg_num_rows($r);
			for ($i = 1; $i <= $filas; $i++)
				$arr[] = pg_fetch_array($r, NULL,  PGSQL_ASSOC);
				/*echo $arr;*/

		}else
			echo "error consulta";

//echo "Aqui: ".print_r($arr);

		$codnom_rp=$arr[0]["codnom"];
		//echo $codnom_rp;
		$codper_rp=$arr[0]["codper"];
		$nomper_rp=$arr[0]["nomper"];
		$apeper_rp=$arr[0]["apeper"];



		if (($cedula_rp!="") or ($cedula_rp!=NULL))
		    {
			if  (($codper_rp=="") or ($codper_rp==NULL) or (empty($codper_rp)))
			    {
				echo '<script language="JavaScript">alert("La c�dula registrada no corresponde con alg�n personal!");</script>';
				echo "<meta http-equiv='refresh' content='0;URL=../index.php'/>";
		            }
		        else
			    {
                 require_once("sigesp_sessiones.php");
				if ($bienvenido==1)
				    {
		                    echo "<script>alert(\"Bienvenido, usuario ".$nomper_rp." ".$apeper_rp."\");</script>";
				    }
			    }
		    }
		else
		    {
		     if (($la_logusr=="") or ($la_logusr==NULL) or (empty($la_logusr)))
			{
		         echo '<script language="JavaScript">alert("No ha iniciado sesion para esta pantalla !");</script>';
		         echo "<meta http-equiv='refresh' content='0;URL=../index.php'/>";
			}
		    }



?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<!--BOOTSTRAP / FONTAWESOME-->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link rel="stylesheet" href="../fontawesome-5.3.1/css/all.css">

<script type="../fontawesome-5.3.1/js/all.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie
		document.onkeydown = function(){
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505;
		}
		if(window.event.keyCode == 505){
		return false;
		}
		}
	}
</script>
<title >Reporte Recibo de Pago</title>
<meta http-equiv="imagetoolbar" content="no">
<!--<style type="text/css">

body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
}

a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}


</style>-->
<!--<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>-->
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<!--<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../css/tablas.css" rel="stylesheet" type="text/css">
<link href="../css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../css/general.css" rel="stylesheet" type="text/css">-->
<style type="text/css">
.Estilo1 {color: #6699CC}

.bg-vinotinto{
  background:#006fba;
}
</style>

</head>
<body background="../img/">
<!--<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img align="center" src="../img/banner_gmsyt.jpg" width="770" height="130"></td>
  </tr>
    <td height="20"  colspan="2" class="cd-menu">-->
	<!--<table width="770" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>&nbsp;</tr>
          <td width="423" height="20" align="left" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Emisi�n de Recibos de Pago</td>
      </table></td>-->
  <!--</tr>

</table>-->

<div class="container">
  <div class="row mb-5">
  <div class="col-md-12">
    <img src="../img/banner_gmsyt.gif" style="width:100%; height:150px;">
  </div>
</div>

<div class="row">
<div class="col-md-3">
<a href="../index.php" style="font-size:40px; color:#006fba;"> <i class="fas fa-power-off"></i> </a>
</div>
<div class="col-md-5 mt-5">
<div class="mb-5" style="text-align:center;">
<h5 style="color:#006fba;">Elija Periodo al Generar el Recibo de Pago</h5>
</div>
<form name="form1" method="post" action="" id="sigesp_snorh_r_recibopago.php">
  <!--<table width="770"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC" class="contorno">
  <tr>
    <td height="136">

      <table width="770" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="7" class="titulo-celunes-roja">Reporte Recibo de Pago </td>
        </tr>
        <tr style="display:none">
          <td height="20"><div align="right">Reporte en</div></td>
          <td height="20"><div align="left">-->
            <select hidden ame="cmbbsf" id="cmbbsf">
              <option value="0" selected>Bs.</option>
              <option value="1">Bs.F.</option>
            </select>
          <!--</div></td>


  <tr class="formato-blanco">
    <td height="22" colspan="3"><p align="left" class="Estilo2 Estilo1"><a href="../index.php"><img title="Salir" src="../img/cerrar.jpg" alt="Grabar" width="45" height="45" border="0"></a></p></td>
    <td height="22" colspan="4"><p align="right" class="Estilo2 Estilo1"><strong>Usuario: <?php /*echo $nomper_rp." ".$apeper_rp */?></strong></p></td>
  </tr>-->

        <!--</tr>-->
        <!--<tr>
          <td height="20" colspan="4" class="titulo-celunes">N&oacute;mina</td>
          </tr>-->
        <!--<tr>
          <td height="20"><div align="right"></div></td>
          <td height="20" colspan="3"><div align="left">-->
            <!--<input name="txtcodnom" type="hidden" id="txtcodnom" size="8" maxlength="4" value="<?php /*print $codnom_rp;*/?>" readonly>-->
            <!--<label>-->
            <input hidden name="txtdesnom" type="text" class="sin-borde" id="txtdesnom" size="50" readonly>
            <!--</label>-->
            <input name="txttipnom" type="hidden" id="txttipnom">
          <!--</div>            <div align="left"><a href="javascript: ue_buscarnominahasta();"></a></div></td>
          </tr>
        <tr>
          <td height="20"><div align="right">  </div></td> --><!--Subn&oacute;mina Desde-->
          <!--<td height="20">--><input name="txtcodsubnomdes" type="hidden" id="txtcodsubnomdes" size="13" maxlength="10" value="" readonly>
            <!--</td>
          <td height="20"><div align="right"> </div></td>--> <!--Subn&oacute;mina Hasta-->
          <!--<td height="20">--><input name="txtcodsubnomhas" type="hidden" id="txtcodsubnomhas" value="" size="13" maxlength="10" readonly>
            <!--</td>
        </tr>
        <tr>
          <td height="20">&nbsp;</td>
          <td height="20"colspan="4"><div align="right">ELIJA EL PERIODO A GENERAR EL RECIBO DE PAGO </div></td>
          <td height="20">&nbsp;</td>
        </tr>
        <tr>
         <td height="20" colspan="3"><div align="right">Seleccione el a&ntilde;o</div></td>
          <td height="20" colspan="4"><div align="left">-->
          <div class="form-group row">
            <label for="textano" class="col-5 col-form-label" style="text-align:right;">Seleccione Año</label>
      <select class="custom-select custom-select-lg col-3 mr-2" name="txtano" id="txtano">
			  <option value=''></option>

			<?php if ($_POST['txtano'] == '2015')
			{echo "<option value='2015' selected>2015</option><option value='2016'>2016</option> <option value='2017' >2017</option>";}?>

			 <?php if ($_POST['txtano'] == '2016')
			{echo "<option value='2015'>2015</option><option value='2016' selected>2016</option> <option value='2017' >2017</option>";}?>

			  <?php if ($_POST['txtano'] == '2017')
			{echo "<option value='2015'>2015</option><option value='2016'>2016</option> <option value='2017' selected>2017</option>";}?>

			<?php if ($_POST['txtano'] == '2018')
			{echo "<option value='2015'>2015</option><option value='2016'>2016</option> <option value='2017' >2017</option><option value='2018' selected>2018</option>";}?>

			<?php if ($_POST['txtano'] == '2018_RECONVERSION')
			{echo "<option value='2015'>2015</option><option value='2016'>2016</option> <option value='2017' >2017</option><option value='2018_RECONVERSION' selected>2018 RECONVERSION</option>";}?>

			<?php if ($_POST['txtano'] == '2019')
			{echo "<option value='2015'>2015</option><option value='2016'>2016</option> <option value='2017' >2017</option><option value='2018_RECONVERSION' >2018_RECONVERSION</option><option value='2019' selected>2019</option>";}?>

			 <?php if ($_POST['txtano']=='')
			{echo "<option value='2015'>2015</option><option value='2016'>2016</option> <option value='2017' >2017</option><option value='2018' >2018</option><option value='2018_RECONVERSION' >2018 RECONVERSION</option><option value='2019' >2019</option>";}?>

    </select>
    <input name="submit" class="btn bg-vinotinto col-3 text-white" type="submit" id="cargarano" value="Cargar A&ntilde;o" onClick="uf_verificar_ano()"><!--</div></td>-->

    </div>
        <!--</tr>-->
        <?php if($anocurso!='') { ?>
        <!--<tr>
          <td height="20" colspan="3"><div align="right">Seleccione la Nomina</div></td>

          <td height="20" colspan="4"><div align="left">-->
          <div class="form-group row">
            <label for="txtcodnom" class="col-5 col-form-label" style="text-align:right;">Seleccione Nomina</label>
          <select class="custom-select custom-select-lg col-3" name="txtcodnom" id="txtcodnom" style="width:100px" >
			  <option ></option>

			  <?php


					//echo count($cursoIn);
					//echo "HOlaaaaaaaa";
					for ($x1=0; $x1 < count($arr); $x1++){
						echo "<option value='".$arr[$x1]['codnom']."'>".$arr[$x1]['desnom']."</option>";
					}

			  ?>
			</select>
    </div>
      <!--</div></td>
          <td height="20">&nbsp;</td>
        </tr>
        <tr>
          <td height="20" colspan="3"><div align="right">Seleccione el Periodo</div></td>
          <td height="20" colspan="4"><div align="left">-->
          <div class="form-group row">
            <label for="texperdes" class="col-5 col-form-label" style="text-align:center;">Seleccione Periodo</label>
            <input name="txtperdes" class="form-control col-3" type="text" id="txtperdes" size="6" maxlength="3" value="" readonly>
          <div class="col-4">
            <a href="javascript: ue_buscarperiododesde();" style="font-size:2rem; color:#006fba;"><i class="fas fa-search" name="periodo" id="periodo"></i></a>
          </div>
          </div>
            <input name="txtfecdesper" type="hidden" id="txtfecdesper">
<!--/div></td>-->

            <input name="txtperhas" type="hidden" id="txtperhas" size="6" maxlength="3" value="" readonly>
            <!--<a href="javascript: ue_buscarperiodohasta();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="periodo" width="15" height="15" border="0" id="periodo"></a>-->
            <input name="txtfechasper" type="hidden" id="txtfechasper">

        <!--</tr>
<tr><td><p>&nbsp;</p></td></tr>
  <tr class="formato-blanco">
    <td height="22" colspan="7"><div align="center">-->
      <div class="form-group row">
        <div class="col-5">
        </div>
      <input name="submit" class="btn bg-vinotinto text-white col-7" type="submit" id="txthabdes" value="Generar Recibo Pago" onClick="ue_print()">
    </div>
    <!--</div></td>
  </tr>--> <?php } ?>
        <!--<tr>
          <td height="20" colspan="4" class="titulo-celunes">Intervalo de Personal </td>
          </tr>-->
        <!--<tr>
          <td width="133" height="22"><div align="right">  </div></td>
          <td width="112"><div align="left">-->
            <input name="txtcodperdes" type="hidden" id="txtcodperdes" size="13" maxlength="10" value="<?php print $codper_rp;?>" readonly>
            <!--</div></td>
          <td width="119"><div align="right"> </div></td>
          <td width="121"><div align="left">-->
            <input name="txtcodperhas" type="hidden" id="txtcodperhas" value="<?php print $codper_rp;?>" size="13" maxlength="10" readonly>
          <!--</div></td>
        </tr>-->
        <!--<tr>
          <td height="20" colspan="4" class="titulo-celunes">&nbsp;</td>
          </tr>
        <tr>-->
          <!--<td height="22"><div align="right"> </div></td>--> <!--Quitar conceptos en cero-->
          <!--<td><div align="left">-->
            <input name="chkconceptocero" type="hidden" class="sin-borde" id="chkconceptocero" value="1" checked>
          <!--</div></td>
          <td><div align="right"></div></td>--> <!--Mostrar Concepto P2-->
          <!--<td><div align="left">-->
            <input name="chkconceptop2" type="hidden" class="sin-borde" id="chkconceptop2" value="1">
          <!--</div></td>
        </tr>
        <tr>-->
          <!--<td height="22"><div align="right"></div></td> --><!--Incluir conceptos reporte-->
          <!--<td><div align="left">-->
            <input name="chkconceptoreporte" type="hidden" class="sin-borde" id="chkconceptoreporte" value="1">
          <!--</div></td>
          <td><div align="right"> </div></td>--> <!--Usar t&iacute;tulo del concepto-->
          <!--<td><div align="left">-->
            <input name="chktituloconcepto" type="hidden" class="sin-borde" id="chktituloconcepto" value="1" checked>
          <!--</div></td>
        </tr>
        <tr>
          <td height="22"><div align="right"> </div></td>--> <!--Unidad Administrativa-->
          <!--<td colspan="3"><div align="left">-->
            <input name="txtcoduniadm" type="hidden" id="txtcoduniadm" size="19" maxlength="16" readonly>

            <input hidden name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" size="40" maxlength="30" readonly>
          <!--</div></td>
          </tr>-->
        <!--<tr>
          <td height="22">&nbsp;</td>
          <td>        </tr>-->
        <!--<tr>
          <td height="20" colspan="4" class="titulo-celdanew"><div align="right" class="titulo-celunes">Ordenado por </div></td>
          </tr>-->
        <!--<tr>
          <td height="22"><div align="right"> </div></td>--><!--C&oacute;digo del Personal-->
          <!--<td colspan="3"><div align="left">-->
            <input name="rdborden" type="hidden" class="sin-borde" value="1" checked>
          <!--</div></td>
        </tr>
        <tr>
          <td height="22"><div align="right"></div></td>--><!--Apellido del Personal-->
          <!--<td colspan="3"><div align="left">-->
            <input name="rdborden" type="hidden" class="sin-borde" value="2">
          <!--</div></td>
        </tr>
        <tr>
          <td height="22"><div align="right"></div></td>--><!--Nombre del Personal-->
          <!--<td colspan="3"><div align="left">-->
            <input name="rdborden" type="hidden" class="sin-borde" value="3">
          <!--</div></td>
        </tr>
        <tr>-->
          <!--<td height="22">&nbsp;</td>-->
          <!--<td colspan="3"> <div align="right">-->
            <input name="recibo" type="hidden" id="recibo" value="<?php print $ls_recibo;?>">
			<input name="pagina" type="hidden" id="pagina">
			<input name="operacion" type="hidden" id="operacion">
			<input name="anocurso" type="hidden" id="anocurso">
          <!--</div></td>
        </tr>

      </table><?php /*include ("../piepagina.php");*/ ?>
    </td>
  </tr>
</table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>-->
</form>
</div>
<div class="col-md-4">
<label style="font-size:15px;"><strong style="color:#006fba;">Usuario:</strong> <?php echo $nomper_rp." ".$apeper_rp ?></label>
</div>
</div>
</div>
</body>
<script language="javascript">
function ue_cerrar()
{
	location.href = "sigespwindow_blank.php";
}

function ue_print()
{
	f=document.form1;
	//li_imprimir=f.imprimir.value;
        li_imprimir=1;
	if(li_imprimir==1)
	{
		txtano = f.txtano.value;
		codperdes=f.txtcodperdes.value;
		codperhas=f.txtcodperhas.value;
		codsubnomdes=f.txtcodsubnomdes.value;
		codsubnomhas=f.txtcodsubnomhas.value;
		tiporeporte=f.cmbbsf.value;
		codnom=f.txtcodnom.value;
		desnom=f.txtdesnom.value;
		codperides=f.txtperdes.value;
		codperihas=f.txtperhas.value;
		fecdesper=f.txtfecdesper.value;
		fechasper=f.txtfechasper.value;
		tipnom=f.txttipnom.value;
		if((codnom!="")&&(codperides!="")&&(codperihas!=""))
		{
			if(codperdes<=codperhas)
			{
				//recibo=f.recibo.value;
				recibo="sigesp_snorh_rpp_recibopago.php";
				//recibo="sigesp_snorh_rpp_constanciatrabajo.php"; //Genera constancia de trabajo

				conceptocero="";
				conceptop2="";
				tituloconcepto="";
				conceptoreporte="";
				coduniadm=f.txtcoduniadm.value;
				if(f.rdborden[0].checked)
				{
					orden="1";
				}
				if(f.rdborden[1].checked)
				{
					orden="2";
				}
				if(f.rdborden[2].checked)
				{
					orden="3";
				}
				if(f.chkconceptocero.checked)
				{
					conceptocero=1;
				}
				if(f.chkconceptop2.checked)
				{
					conceptop2=1;
				}
				if(f.chktituloconcepto.checked)
				{
					tituloconcepto=1;
				}
				if(f.chkconceptoreporte.checked)
				{
					conceptoreporte=1;
				}
				pagina="reportes/"+recibo+"?codperdes="+codperdes+"&codperhas="+codperhas+"&conceptocero="+conceptocero+"";
				pagina=pagina+"&conceptop2="+conceptop2+"&tituloconcepto="+tituloconcepto+"&conceptoreporte="+conceptoreporte;
				pagina=pagina+"&coduniadm="+coduniadm+"&orden="+orden+"&tiporeporte="+tiporeporte+"&codnom="+codnom+"&desnom="+desnom;
				pagina=pagina+"&codperides="+codperides+"&codperihas="+codperihas+"&fecdesper="+fecdesper+"&fechasper="+fechasper;
				pagina=pagina+"&tipnom="+tipnom;
				pagina=pagina+"&codsubnomdes="+codsubnomdes+"&codsubnomhas="+codsubnomhas+"&anocurso="+txtano;
				window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		                //f.action="../index.php";
			        //f.submit();
			}
			else
			{
				alert("El rango del personal est� erroneo");
			}
		}
		else
		{
			alert("Debe seleccionar un rango de per�odos.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_buscarnomina()
{
	window.open("sigesp_snorh_cat_nomina.php?tipo=reprecpagcon","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function uf_verificar_ano()
{

	f=document.form1;
	txtano = f.txtano.value;

	if (txtano!="")
	{
		f.anocurso.value = txtano;
		f.operacion.value="CAMBIO_BD";
		f.action="sigesp_snorh_r_recibopago.php";
		f.submit();
	}
  else
	 {
	   alert("Debe seleccionar un a�o para continuar la consulta!!!");
	 }
}

function ue_buscarperiododesde()
{
	f=document.form1;
	codnom=f.txtcodnom.value;
	txtano = f.txtano.value;


	if(codnom!="")
	{
		window.open("sigesp_sno_cat_hperiodo.php?tipo=reprecpagcondes&anocurso="+txtano+"&codnom="+codnom+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=200,top=200,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar una n�mina.");
	}
}

function ue_buscarperiodohasta()
{
	f=document.form1;
	codnom=f.txtcodnom.value;
	if((codnom!="")&&(f.txtperdes.value!=""))
	{
		window.open("sigesp_sno_cat_hperiodo.php?tipo=reprecpagconhas&codnom="+codnom+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=200,top=200,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un rango de n�minas y aun per�odo desde.");
	}
}

function ue_buscarpersonaldesde()
{
	window.open("sigesp_snorh_cat_personal.php?tipo=recpagcondes","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarpersonalhasta()
{
	f=document.form1;
	if(f.txtcodperdes.value!="")
	{
		window.open("sigesp_snorh_cat_personal.php?tipo=recpagconhas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un personal desde.");
	}
}

function ue_buscaruniadm()
{
	window.open("sigesp_snorh_cat_uni_ad.php?tipo=reprecpag","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarsubnominadesde()
{
	f=document.form1;
	codnomdes=f.txtcodnom.value;
	if(codnomdes!="")
	{
		window.open("sigesp_snorh_cat_subnomina.php?tipo=reportedesde&codnom="+codnomdes,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Para filtrar por Subn�minas La n�mina debe estar seleccionada.");
	}
}

function ue_buscarsubnominahasta()
{
	f=document.form1;
	codsubnomdes=f.txtcodsubnomdes.value;
	codnomdes=f.txtcodnom.value;
	if(codsubnomdes!="")
	{
		window.open("sigesp_snorh_cat_subnomina.php?tipo=reportehasta&codnom="+codnomdes,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar una subn�mina desde.");
	}
}

function ue_enviarcorreo()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
		codperdes=f.txtcodperdes.value;
		codperhas=f.txtcodperhas.value;
		codsubnomdes=f.txtcodsubnomdes.value;
		codsubnomhas=f.txtcodsubnomhas.value;
		tiporeporte=f.cmbbsf.value;
		codnom=f.txtcodnom.value;
		desnom=f.txtdesnom.value;
		codperides=f.txtperdes.value;
		codperihas=f.txtperhas.value;
		fecdesper=f.txtfecdesper.value;
		fechasper=f.txtfechasper.value;
		tipnom=f.txttipnom.value;
		if((codnom!="")&&(codperides!="")&&(codperihas!=""))
		{
			if(codperdes<=codperhas)
			{
				recibo=f.recibo.value;
				conceptocero="";
				conceptop2="";
				tituloconcepto="";
				conceptoreporte="";
				coduniadm=f.txtcoduniadm.value;
				if(f.rdborden[0].checked)
				{
					orden="1";
				}
				if(f.rdborden[1].checked)
				{
					orden="2";
				}
				if(f.rdborden[2].checked)
				{
					orden="3";
				}
				if(f.chkconceptocero.checked)
				{
					conceptocero=1;
				}
				if(f.chkconceptop2.checked)
				{
					conceptop2=1;
				}
				if(f.chktituloconcepto.checked)
				{
					tituloconcepto=1;
				}
				if(f.chkconceptoreporte.checked)
				{
					conceptoreporte=1;
				}
				pagina="reportes/sigesp_snorh_rpp_recibopagocorreo.php?codperdes="+codperdes+"&codperhas="+codperhas+"&conceptocero="+conceptocero+"";
				pagina=pagina+"&conceptop2="+conceptop2+"&tituloconcepto="+tituloconcepto+"&conceptoreporte="+conceptoreporte;
				pagina=pagina+"&coduniadm="+coduniadm+"&orden="+orden+"&tiporeporte="+tiporeporte+"&codnom="+codnom+"&desnom="+desnom;
				pagina=pagina+"&codperides="+codperides+"&codperihas="+codperihas+"&fecdesper="+fecdesper+"&fechasper="+fechasper;
				pagina=pagina+"&tipnom="+tipnom;
				pagina=pagina+"&codsubnomdes="+codsubnomdes+"&codsubnomhas="+codsubnomhas;
alert (pagina);
				window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			}
			else
			{
				alert("El rango del personal est� erroneo.");
			}
		}
		else
		{
			alert("Debe seleccionar un rango de n�minas y per�odos.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion.");
   	}
}

</script>
</html>
