<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	
	if (isset($_GET["valor"]))
	{ $ls_valor=$_GET["valor"];	}
	else
	{ $ls_valor="";}

   //--------------------------------------------------------------
   function uf_print($as_codtab, $as_destab, $as_tipo)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codtab  // C�digo del tabla
		//				   as_destab  // Descripci�n de la tabla
		//				   as_existe  // Verifica de donde se est� llamando el cat�logo
		//	  Description: Funci�n que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
		global $ls_codnom;
		
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
		$ls_criterio="";
		if($ls_codnom!="")
		{
			$ls_criterio="    AND sno_tabulador.codnom = '".$ls_codnom."' ";
		}
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=100>C�digo</td>";
		print "<td width=200>Descripci�n</td>";
		print "<td width=200>N�mina</td>";
		print "</tr>";		
		$ls_sql="SELECT sno_tabulador.codtab, sno_tabulador.destab, sno_tabulador.maxpasgra, sno_tabulador.tabmed, sno_tabulador.codnom, sno_nomina.desnom  ".
				"  FROM sno_tabulador, sno_nomina ".
				" WHERE sno_tabulador.codemp='".$ls_codemp."'".
				"   AND sno_tabulador.codtab<>'00000000000000000000'".
				"   AND sno_tabulador.codtab like '".$as_codtab."'".
				"   AND sno_tabulador.destab like '".$as_destab."'".
				$ls_criterio.
				"   AND sno_tabulador.codnom IN (SELECT sno_nomina.codnom ".
				"  					               FROM sno_nomina, sss_permisos_internos ".
				" 					              WHERE sno_nomina.codemp='".$ls_codemp."'".
				"   				                AND sss_permisos_internos.codsis='SNO'".
				"   				                AND sss_permisos_internos.enabled=1".
				"  					                AND sss_permisos_internos.codusu='".$_SESSION["la_logusr"]."'".
				"  					                AND sno_nomina.codemp = sss_permisos_internos.codemp ".
				"   				                AND sno_nomina.codnom = sss_permisos_internos.codintper ".
				" 					              GROUP BY sno_nomina.codnom) ".
				"   AND sno_tabulador.codemp = sno_nomina.codemp ".
				"   AND sno_tabulador.codnom = sno_nomina.codnom ".
				" ORDER BY codtab ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codtab=$row["codtab"];
				$ls_destab=$row["destab"];
				$li_tabmed=$row["tabmed"];
				$li_maxpasgra=$row["maxpasgra"];
				$ls_codnom=$row["codnom"];
				$ls_desnom=$row["desnom"];
				switch ($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codtab','$ls_destab','$li_maxpasgra','$ls_codnom','$li_tabmed');\">".$ls_codtab."</a></td>";
						print "<td>".$ls_destab."</td>";
						print "<td>".$ls_desnom."</td>";
						print "</tr>";
						break;
						
					case "asignacioncargo":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar_asignacioncargo('$ls_codtab','$ls_destab');\">".$ls_codtab."</a></td>";
						print "<td>".$ls_destab."</td>";
						print "<td>".$ls_desnom."</td>";
						print "</tr>";
						break;
				}
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
		unset($ls_codnom);
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Tabulador</title>
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
	<input name="hidsrh" type="hidden" id="hidsrh" value="<?php print $ls_valor;?>">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Tabulador</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="txtcodtab" type="text" id="txtcodtab" size="30" maxlength="20" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Descripci&oacute;n</div></td>
        <td><div align="left">
          <input name="txtdestab" type="text" id="txtdestab" size="30" maxlength="100" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" title='Buscar' alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
  <br>
<?php
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_operacion =$io_fun_nomina->uf_obteneroperacion();
	$ls_tipo=$io_fun_nomina->uf_obtenertipo();
	$ls_codnom=$io_fun_nomina->uf_obtenervalor_get("codnom","");
	if($ls_operacion=="BUSCAR")
	{
		$ls_codtab="%".$_POST["txtcodtab"]."%";
		$ls_destab="%".$_POST["txtdestab"]."%";
		uf_print($ls_codtab, $ls_destab, $ls_tipo);
	}
	else
	{
		$ls_codtab="%%";
		$ls_destab="%%";
		uf_print($ls_codtab, $ls_destab, $ls_tipo);
	}	
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codtab,destab,maxpasgra,codnom,tabmed)
{
	opener.document.form1.txtcodtab.value=codtab;
	opener.document.form1.txtcodtab.readOnly=true;
    opener.document.form1.txtdestab.value=destab;
    opener.document.form1.txtmaxpasgra.value=maxpasgra;
    opener.document.form1.cmbnomina.value=codnom;
    opener.document.form1.cmbnomina.disabled=true;
    opener.document.form1.txtcodnom.value=codnom;
	opener.document.form1.existe.value="TRUE";		
	opener.document.form1.operacion.value="BUSCARDETALLE";
  opener.document.form1.chktabmed.checked=false;	  
	valor= document.form1.hidsrh.value;
	if (tabmed=='1')
	{
	  opener.document.form1.chktabmed.checked=true;	  
	}
	if (valor=='srh')
	{
	  opener.document.form1.action="sigesp_snorh_d_tabulador.php?valor=srh";	  
	}
	else
	{
	 opener.document.form1.action="sigesp_snorh_d_tabulador.php";		
	}
	 	
	opener.document.form1.submit();	
	close();
}
function aceptar_asignacioncargo(codtab,destab)
{
	opener.document.form1.txtcodtab.value=codtab;
	opener.document.form1.txtcodtab.readOnly=true;
    opener.document.form1.txtdestab.value=destab;
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
  	f.action="sigesp_snorh_cat_tabulador.php?tipo=<?php print $ls_tipo;?>";
  	f.submit();
}
</script>
</html>
