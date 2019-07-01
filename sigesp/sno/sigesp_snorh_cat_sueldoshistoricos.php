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
   function uf_print($as_codper)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codper  // Código de Personal
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/11/2010 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=125>Fecha Sueldo</td>";
		print "<td width=125>Sueldo Base</td>";
		print "<td width=125>Sueldo Integral</td>";
		print "<td width=125>Sueldo Promedio Diario</td>";
		print "</tr>";
		$ls_sql="SELECT fecsue,suebas,sueint,sueprodia, codded, codtipper, ".
				"		(SELECT desded ".
				"		   FROM sno_dedicacion ".
				"         WHERE sno_sueldoshistoricos.codemp = sno_dedicacion.codemp ".
				"			AND sno_sueldoshistoricos.codded = sno_dedicacion.codded) AS desded, ".
				"		(SELECT destipper ".
				"		   FROM sno_tipopersonal ".
				"         WHERE sno_sueldoshistoricos.codemp = sno_tipopersonal.codemp ".
				"			AND sno_sueldoshistoricos.codded = sno_tipopersonal.codded ".
				"			AND sno_sueldoshistoricos.codtipper = sno_tipopersonal.codtipper) AS destipper ".
				"  FROM sno_sueldoshistoricos ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codper='".$as_codper."'".
				" ORDER BY fecsue";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while(!$rs_data->EOF)
			{
				$ld_fecsue=$io_funciones->uf_convertirfecmostrar($rs_data->fields["fecsue"]);
				$li_suebas=number_format($rs_data->fields["suebas"],2,",",".");				
				$li_sueint=number_format($rs_data->fields["sueint"],2,",",".");					
				$li_sueprodia=number_format($rs_data->fields["sueprodia"],2,",",".");	
				$ls_codded=$rs_data->fields["codded"];	
				$ls_desded=$rs_data->fields["desded"];	
				$ls_codtipper=$rs_data->fields["codtipper"];	
				$ls_destipper=$rs_data->fields["destipper"];	
				print "<tr class=celdas-blancas>";
				print "<td><a href=\"javascript: aceptar('$ld_fecsue','$li_suebas','$li_sueint','$li_sueprodia',".
					  "'$ls_codded','$ls_desded','$ls_codtipper','$ls_destipper');\">".$ld_fecsue."</a></td>";
				print "<td>".$li_suebas."</td>";
				print "<td>".$li_sueint."</td>";
				print "<td>".$li_sueprodia."</td>";
				print "</tr>";		
				$rs_data->MoveNext();	
			}
			$io_sql->free_result($rs_data);
		}
		print "</table>";
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Sueldos Hist&oacute;ricos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style>
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Sueldos Hist&oacute;ricos </td>
    </tr>
  </table>
<br>
<br>
<?php
	$ls_codper=$_GET["codper"];
	uf_print($ls_codper);
?>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(ld_fecsue,li_suebas,li_sueint,li_sueprodia,ls_codded,ls_desded,ls_codtipper,ls_destipper)
{
	opener.document.form1.cmbmes.disabled=true;
	opener.document.form1.cmbano.disabled=true;
	opener.document.form1.txtfecsue.value=ld_fecsue;
	opener.document.form1.txtfecsue.readOnly=true;
    opener.document.form1.txtsuebas.value=li_suebas;
	opener.document.form1.txtsueint.value=li_sueint;
    opener.document.form1.txtsueprodia.value=li_sueprodia;
    opener.document.form1.txtcodded.value=ls_codded;
    opener.document.form1.txtdesded.value=ls_desded;
    opener.document.form1.txtcodtipper.value=ls_codtipper;
    opener.document.form1.txtdestipper.value=ls_destipper;
	opener.document.form1.existe.value="TRUE";		
	close();
}
</script>
</html>
