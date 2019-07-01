<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";
	}
   //--------------------------------------------------------------
   function uf_print($as_tipo,$as_codnom,$as_codnomhas,$as_mesdesde,$as_meshasta,$as_anocurso)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function : uf_print
		//		   Access : public
		//	    Arguments : as_tipo  // Tipo de Llamada del cat�logo
		//	                as_codnom  // C�digo de N�mina
		//	                as_codnomhas  // C�digo de N�mina hasta
		//	                as_mesdesde  // Mes Desde donde se quiere filtrar
		//	                as_meshasta  // Mes Hasta donde se quiere filtrar
		//	  Description : Funci�n que obtiene e imprime los resultados de la busqueda
		//	   Creado Por : Ing. Yesenia Moreno
		// Fecha Creaci�n : 07/04/2006 								Fecha �ltima Modificaci�n :
		//////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar($as_anocurso);
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);
		require_once("../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();
		require_once("../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		//echo $ls_codemp ."</br>";
		//echo $as_codnom;
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celunes-roja>";
		print "<td width=50>Año</td>";
		print "<td width=50>Periodo</td>";
		print "<td width=200>Fecha de Inicio</td>";
		print "<td width=200>Fecha de Finalización</td>";
		print "</tr>";
		$ls_sql="SELECT sno_hperiodo.anocur, sno_hperiodo.codperi, sno_hperiodo.fecdesper, sno_hperiodo.fechasper ".
				"  FROM sno_hperiodo, sno_periodo ".
				" WHERE sno_periodo.cerper = 1 ".
				"   AND sno_hperiodo.codemp = '".$ls_codemp."' ".
				"   AND sno_hperiodo.codnom = '".$as_codnom."' ".

				"   AND sno_hperiodo.codemp = sno_periodo.codemp ".
				"   AND sno_hperiodo.codnom = sno_periodo.codnom ".
				"   AND sno_hperiodo.codperi = sno_periodo.codperi ".
				"   AND sno_periodo.fechasper+1 <= current_timestamp ";



		$ls_sql=$ls_sql." ORDER BY sno_hperiodo.anocur, sno_hperiodo.codperi ";//echo $ls_sql;
		//$ls_sql=$ls_sql." ORDER BY sno_hperiodo.codperi DESC limit 1 ";

		//echo $ls_sql;

		$rs_data=$io_sql->select($ls_sql);




		//echo $rs_data;
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message));
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_anocur=$row["anocur"];
				$ls_codperi=$row["codperi"];
				$ld_fecdesper=$io_funciones->uf_formatovalidofecha($row["fecdesper"]);
				$ld_fechasper=$io_funciones->uf_formatovalidofecha($row["fechasper"]);

				$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($ld_fecdesper);
				$ld_fechasper=$io_funciones->uf_convertirfecmostrar($ld_fechasper);

				switch ($as_tipo)
				{
					case "": // sigesp_snorh_p_seleccionarhnomina
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_anocur','$ls_codperi','$ld_fecdesper','$ld_fechasper');\">".$ls_anocur."</a></td>";
						print "<td>".$ls_codperi."</td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
						print "</tr>";
						break;

					case "mintrades": // sigesp_snorh_p_seleccionarhnomina
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepcondes('$ls_codperi');\">".$ls_anocur."</a></td>";
						print "<td>".$ls_codperi."</td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
						print "</tr>";
						break;

					case "mintrahas": // sigesp_snorh_p_seleccionarhnomina
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepconhas('$ls_codperi','$ld_fechasper');\">".$ls_anocur."</a></td>";
						print "<td>".$ls_codperi."</td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
						print "</tr>";
						break;

					case "repapopatdes": // sigesp_snorh_r_aportepatronal
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepapopatdes('$ls_codperi');\">".$ls_anocur."</a></td>";
						print "<td>".$ls_codperi."</td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
						print "</tr>";
						break;

					case "repapopathas": // sigesp_snorh_r_aportepatronal
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepapopathas('$ls_codperi','$ld_fechasper');\">".$ls_anocur."</a></td>";
						print "<td>".$ls_codperi."</td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
						print "</tr>";
						break;

					case "repcondes": // sigesp_snorh_r_conceptos
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepcondes('$ls_codperi');\">".$ls_anocur."</a></td>";
						print "<td>".$ls_codperi."</td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
						print "</tr>";
						break;

					case "repconhas": // sigesp_snorh_r_conceptos
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepconhas('$ls_codperi','$ld_fechasper');\">".$ls_anocur."</a></td>";
						print "<td>".$ls_codperi."</td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
						print "</tr>";
						break;

					case "replisbandes": // sigesp_snorh_r_listadobanco
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisbandes('$ls_codperi','$ld_fecdesper');\">".$ls_anocur."</a></td>";
						print "<td>".$ls_codperi."</td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
						print "</tr>";
						break;

					case "replisbanhas": // sigesp_snorh_r_listadobanco
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisbanhas('$ls_codperi','$ld_fechasper');\">".$ls_anocur."</a></td>";
						print "<td>".$ls_codperi."</td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
						print "</tr>";
						break;


					case "reprecpagcondes": // sigesp_snorh_r_recibopago
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreprecpagcondes('$ls_codperi','$ld_fecdesper','$ld_fechasper');\">".$ls_anocur."</a></td>";
						print "<td>".$ls_codperi."</td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
						print "</tr>";
						break;

					case "reprecpagconhas": // sigesp_snorh_r_recibopago
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreprecpagconhas('$ls_codperi','$ld_fechasper');\">".$ls_anocur."</a></td>";
						print "<td>".$ls_codperi."</td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
						print "</tr>";
						break;

					case "transferir": // sigesp_sno_p_transferirpersonal.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptartransferir('$ls_codperi', '$ld_fecdesper','$ld_fechasper', '$ls_anocur');\">".$ls_anocur."</a></td>";
						print "<td>".$ls_codperi."</td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
						print "</tr>";
						break;
				}
			}
			$io_sql->free_result($rs_data);
			//echo $io_sql;
		}
		print "</table>";
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
		unset($ls_codnom);
		unset($ld_peractnom);
//echo $ls_sql;
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Per&iacute;odos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">

a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}

</style>
<link href="../css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../css/general.css" rel="stylesheet" type="text/css">
<link href="../css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="20" colspan="2" class="titulo-celunes-roja">Cat&aacute;logo de Per&iacute;odos </td>
    </tr>
  </table>
<br>
    <br>
<?php
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_tipo=$io_fun_nomina->uf_obtenertipo();
	$ls_anocurso=$io_fun_nomina->uf_obtenervalor_get("anocurso","");
	$ls_codnom=$io_fun_nomina->uf_obtenervalor_get("codnom","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	$ls_mesdesde=$io_fun_nomina->uf_obtenervalor_get("mesdesde","");
	$ls_meshasta=$io_fun_nomina->uf_obtenervalor_get("meshasta","");
	uf_print($ls_tipo,$ls_codnom,$ls_codnomhas,$ls_mesdesde,$ls_meshasta,$ls_anocurso);
	unset($io_fun_nomina);
?>
</div>
</form>
</body>
<script language="JavaScript">
function aceptar(anocur,codperi,fecdesper,fechasper)
{
	opener.document.form1.txtanocurnom.value=anocur;
	opener.document.form1.txtanocurnom.readOnly=true;
	opener.document.form1.txtperactnom.value=codperi;
	opener.document.form1.txtperactnom.readOnly=true;
    opener.document.form1.txtfecdesper.value=fecdesper;
	opener.document.form1.txtfecdesper.readOnly=true;
    opener.document.form1.txtfechasper.value=fechasper;
	opener.document.form1.txtfechasper.readOnly=true;
	close();
}

function aceptartransferir(codperi,fecdesper,fechasper,anocur)
{

	opener.document.form1.txtcodperi.value=codperi;
	opener.document.form1.txtcodperi.readOnly=true;
    opener.document.form1.txtfecdesper.value=fecdesper;
	opener.document.form1.txtfecdesper.readOnly=true;
    opener.document.form1.txtfechasper.value=fechasper;
	opener.document.form1.txtfechasper.readOnly=true;
	 opener.document.form1.txtanocur.value=anocur;
	opener.document.form1.txtanocur.readOnly=true;
	close();
}



function aceptarrepapopatdes(codperi)
{
	opener.document.form1.txtperdes.value=codperi;
	opener.document.form1.txtperdes.readOnly=true;
    opener.document.form1.txtperhas.value="";
	opener.document.form1.txtfecpro.value="";
	close();
}

function aceptarrepapopathas(codperi,fechasper)
{
	if(opener.document.form1.txtperdes.value<=codperi)
	{
		opener.document.form1.txtperhas.value=codperi;
		opener.document.form1.txtperhas.readOnly=true;
		opener.document.form1.txtfecpro.value=fechasper;
		close();
	}
	else
	{
		alert("Rango de periodo invalido.");
	}
}

function aceptarrepcondes(codperi)
{
	opener.document.form1.txtperdes.value=codperi;
	opener.document.form1.txtperdes.readOnly=true;
    opener.document.form1.txtperhas.value="";
	close();
}

function aceptarrepconhas(codperi,fechasper)
{
	if(opener.document.form1.txtperdes.value<=codperi)
	{
		opener.document.form1.txtperhas.value=codperi;
		opener.document.form1.txtperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango de periodo invalido.");
	}
}

function aceptarreplisbandes(codperi,fecdesper)
{
	opener.document.form1.txtperdes.value=codperi;
	opener.document.form1.txtperdes.readOnly=true;
    opener.document.form1.txtfecdesper.value=fecdesper;
    opener.document.form1.txtperhas.value="";
    opener.document.form1.txtfechasper.value="";
	close();
}

function aceptarreplisbanhas(codperi,fechasper)
{
	if(opener.document.form1.txtperdes.value<=codperi)
	{
		opener.document.form1.txtperhas.value=codperi;
		opener.document.form1.txtperhas.readOnly=true;
    	opener.document.form1.txtfechasper.value=fechasper;
		close();
	}
	else
	{
		alert("Rango de periodo invalido.");
	}
}

function aceptarreprecpagcondes(codperi,fecdesper,fechasper)
{
	opener.document.form1.txtperdes.value=codperi;
	opener.document.form1.txtperdes.readOnly=true;
    opener.document.form1.txtfecdesper.value=fecdesper;
	opener.document.form1.txtperhas.value=codperi;
	opener.document.form1.txtperhas.readOnly=true;
    opener.document.form1.txtfechasper.value=fechasper;
	close();
}

function aceptarreprecpagconhas(codperi,fechasper)
{
	if(opener.document.form1.txtperdes.value<=codperi)
	{
		opener.document.form1.txtperhas.value=codperi;
		opener.document.form1.txtperhas.readOnly=true;
    	opener.document.form1.txtfechasper.value=fechasper;
		close();
	}
	else
	{
		alert("Rango de periodo invalido.");
	}
}
</script>
</html>
