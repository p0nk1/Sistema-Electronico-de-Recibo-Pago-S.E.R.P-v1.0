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
   function uf_print($as_codper, $as_nomper)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: ad_fecfer  // Fecha del Feriado
		//				   as_nomfer  // Descripci�n del Feriado
		//	  Description: Funci�n que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
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
		print "<td width=60>C�digo</td>";
		print "<td width=440>Nombre y Apellido</td>";
		print "</tr>";
		$ls_sql="SELECT codemp, codper, nomper, prirem, subtot, porpen, monpen, ultrem, fecvida, tipjub ".
				"  FROM sno_jubilados ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND nomper like '".$as_nomper."' ".
				" ORDER BY codper";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				$ls_nomper=$row["nomper"];
				$ls_prirem=number_format($row["prirem"],2,',','.');	
				$ls_subtot=number_format($row["subtot"],2,',','.');	
				$ld_fecvida=$io_funciones->uf_convertirfecmostrar($row["fecvida"]);
				$ls_porpen=number_format($row["porpen"],2,',','.');				
				$li_monpen=number_format($row["monpen"],2,',','.');					
				$li_ultrem=number_format($row["ultrem"],2,',','.');					
				$li_tipjub=$row["tipjub"];
				print "<tr class=celdas-blancas>";
				print "<td><a href=\"javascript: aceptar('$ls_codper','$ls_nomper','$ls_prirem','$ls_subtot','$ld_fecvida',";
				print "'$ls_porpen','$li_monpen','$li_ultrem','$li_tipjub');\">".$ls_codper."</a></td>";
				print "<td>".$ls_nomper."</td>";
				print "</tr>";			
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
<title>Cat&aacute;logo de Jubilados</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Jubilados </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&eacute;dula</div></td>
        <td width="431"><div align="left">
          <input name="txtcedfam" type="text" id="txtcedfam" size="30" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td><div align="left">
          <input name="txtnomfam" type="text" id="txtnomfam" size="30" maxlength="60" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Apellido</div></td>
        <td><div align="left">
          <input name="txtapefam" type="text" id="txtapefam" size="30" maxlength="60" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();">
          <img src="../shared/imagebank/tools20/buscar.gif" title='Buscar' alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
  <br>
<?php
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_operacion =$io_fun_nomina->uf_obteneroperacion();
	if($ls_operacion=="BUSCAR")
	{
		$ls_nomper="%".$_POST["txtnomper"]."%";
		$ls_codper=$_POST["txtcodper"];
		uf_print($ls_codper,$ls_nomper);
	}
	else
	{
		$ls_codper=$_GET["codper"];
		$ls_nomper=$_GET["nomper"];
		uf_print($ls_codper,$ls_nomper);
	}
	unset($io_fun_nomina);
?>
</div>
          <input name="txtcodper" type="hidden" id="txtcodper" value="<?php print $ls_codper;?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(ls_codper,ls_nomper,ls_prirem,ls_subtot,ld_fecvida,ls_porpen,li_monpen,li_ultrem,li_tipjub)
{
	opener.document.form1.txtcodper.value=ls_codper;
    opener.document.form1.txtnomper.value=ls_nomper;
	opener.document.form1.txtprimrem.value=ls_prirem;
    opener.document.form1.txtsubtotper.value=ls_subtot;
    opener.document.form1.txtfecvid.value=ld_fecvida;
    opener.document.form1.txtporpenper.value=ls_porpen;
    opener.document.form1.txtmonpenper.value=li_monpen;
	opener.document.form1.txtsegrem.value=li_ultrem;
	opener.document.form1.cmbtipjub.value=li_tipjub;
	opener.document.form1.existe.value="TRUE";		
	close();
}

function ue_mostrar(myfield,e)
{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	if (keycode == 13)
	{
		ue_search();
		return false;
	}
	else
		return true
}

function ue_search()
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_snorh_cat_jubilados.php";
  	f.submit();
}
</script>
</html>
