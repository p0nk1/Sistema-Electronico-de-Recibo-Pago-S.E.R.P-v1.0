<?php
    session_start();   
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_r_aportepatronal.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	$lb_valido=$io_sno->uf_crear_sessionnomina();		
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
	$ls_subnom=$_SESSION["la_nomina"]["subnom"];
	$ls_reporte=$io_sno->uf_select_config("SNO","REPORTE","LISTADO_APORTEPATRONAL","sigesp_sno_rpp_aportepatronal.php","C");
	unset($io_sno);
	$ls_nomina=$_SESSION["la_nomina"]["codnom"];
	$ls_periodo=$_SESSION["la_nomina"]["peractnom"];
	$ls_ruta="txt/aportes/".$ls_nomina."-".$ls_periodo;
	@mkdir($ls_ruta,0755);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
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
<title >Reporte Aporte Patronal</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
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

-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
	$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
	require_once("sigesp_snorh_c_metodo_aporte.php");
	$io_metodo=new sigesp_snorh_c_metodo_aporte();
	switch ($ls_operacion) 
	{
		case "GENDISK":
			$ls_concepto_lph=trim($io_metodo->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO LPH","LPHLPHLPHL","C"));
			$ls_concepto_fpj=trim($io_metodo->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO FPJ","XXXXXXXX","C"));
			$ls_concepto_fpa=trim($io_metodo->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO FPA","XXXXXXXXXX","C"));
			$ls_codconc=$_POST["txtcodconc"];
			$ls_nomcon=$_POST["txtnomcon"];
			$ls_codnomdes=$_SESSION["la_nomina"]["codnom"];
			$ls_codnomhas=$_SESSION["la_nomina"]["codnom"];
			$ls_anocur=$_SESSION["la_nomina"]["anocurnom"];
			$ls_perdes=$_SESSION["la_nomina"]["peractnom"];
			$ls_perhas=$_SESSION["la_nomina"]["peractnom"];
			$ld_fecpro=$_SESSION["la_nomina"]["fechasper"];
			$la_fpa=split(",",$ls_concepto_fpa);
			$la_fpj=split(",",$ls_concepto_fpj);
			$la_lph=split(",",$ls_concepto_lph);
			$li_total_fpa=count($la_fpa);
			$li_total_fpj=count($la_fpj);
			$li_total_lph=count($la_lph);
			// Para el Fondo de Plan de Ahorro
			for($li_i=0;$li_i<$li_total_fpa;$li_i++)
			{
				$ls_concepto_fpa=$la_fpa[$li_i];
				if($ls_codconc==$ls_concepto_fpa)
				{
					$ls_metodo_fpa=trim($io_metodo->io_sno->uf_select_config("SNO","NOMINA","METODO FPA","SIN METODO","C"));
					$lb_valido=$io_metodo->uf_listado_gendisk_nomina($la_fpa,$ls_codnomdes,$ls_codnomhas,$ls_anocur,$ls_perdes,$ls_perhas);
					if($lb_valido)
					{
						$ds_banco=$io_metodo->DS;
						$lb_valido=$io_metodo->uf_metodo_fpa($ls_ruta,$ls_metodo_fpa,$ds_banco,$ld_fecpro,
														     $ls_codconc,$ls_codnomdes,$ls_codnomhas,$ls_anocur,$ls_perdes,
															 $ls_perhas,$la_seguridad);
					}
					break;
				}
			}
			// Para el Fondo de Pensión de Jubilación
			for($li_i=0;$li_i<$li_total_fpj;$li_i++)
			{
				$ls_concepto_fpj=$la_fpj[$li_i];
				if($ls_codconc==$ls_concepto_fpj)
				{
					$ls_metodo_fpj=trim($io_metodo->io_sno->uf_select_config("SNO","CONFIG","METODO FPJ","SUELDO NORMAL","C"));
					$ls_organismo=trim($io_metodo->io_sno->uf_select_config("SNO","NOMINA","COD ORGANISMO FPJ","XXXXXXXX","C"));
					$lb_valido=$io_metodo->uf_listado_gendisk_nomina($la_fpj,$ls_codnomdes,$ls_codnomhas,$ls_anocur,$ls_perdes,$ls_perhas);
					if($lb_valido)
					{
						$ds_banco=$io_metodo->DS;
						$lb_valido=$io_metodo->uf_metodo_fpj($ls_ruta,$ls_metodo_fpj,$ls_organismo,$ds_banco,$ld_fecpro,
														     $ls_codconc,$ls_codnomdes,$ls_codnomhas,$ls_anocur,$ls_perdes,
															 $ls_perhas,$la_seguridad);
					}
					break;
				}
			}
			// Para el Ley de Política Habitacional
			for($li_i=0;$li_i<$li_total_lph;$li_i++)
			{
				$ls_concepto_lph=$la_lph[$li_i];
				if($ls_codconc==$ls_concepto_lph)
				{
					$ls_metodo_lph=rtrim($io_metodo->io_sno->uf_select_config("SNO","NOMINA","METODO LPH","SIN METODO","C"));
					$lb_valido=$io_metodo->uf_listado_gendisk_nomina($la_lph,$ls_codnomdes,$ls_codnomhas,$ls_anocur,$ls_perdes,$ls_perhas);
					if($lb_valido)
					{
						$ds_banco=$io_metodo->DS;
						$lb_valido=$io_metodo->uf_metodo_lph($ls_ruta,$ls_metodo_lph,$ds_banco,$ld_fecpro,
														     $ls_codconc,$ls_codnomdes,$ls_codnomhas,$ls_anocur,$ls_perdes,
															 $ls_perhas,$la_seguridad,"","");
					}
					break;
				}
			}
			break;
			
		default:
			$ls_codconc="";
			$ls_nomcon="";
			break;
	}
	unset($io_metodobanco);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema"><?php print $ls_desnom;?></td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><?php print $ls_desper;?></span></div></td>
			 <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
	  </table>	</td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_nomina.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript:ue_print();"><img src="../shared/imagebank/tools20/imprimir.gif" title='Imprimir' alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_gendisk();"><img src="../shared/imagebank/tools20/gendisk.jpg" title='Generar' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_descargar('<?php print $ls_ruta;?>');"><img src="../shared/imagebank/tools20/download.gif" title='Descargar' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>

<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank_nomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="650" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="600" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Reporte de Aporte Patronal </td>
        </tr>
        <tr style="display:none">
          <td height="22"><div align="right">Reporte en</div></td>
          <td colspan="3"><div align="left">
            <select name="cmbbsf" id="cmbbsf">
              <option value="0" selected>Bs.</option>
              <option value="1">Bs.F.</option>
            </select>
          </div></td>
        </tr>
<?php if($ls_subnom=='1')
{
?>
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-celdanew">Intervalo de subnomina </td>
        </tr>
        <tr>
          <td height="20"><div align="right"> Desde </div></td>
          <td height="20"><input name="txtcodsubnomdes" type="text" id="txtcodsubnomdes" size="13" maxlength="10" value="" readonly>
            <a href="javascript: ue_buscarsubnominadesde();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></td>
          <td height="20"><div align="right">Hasta </div></td>
          <td height="20"><input name="txtcodsubnomhas" type="text" id="txtcodsubnomhas" value="" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarsubnominahasta();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></td>
        </tr>
        <tr>
          <td height="20" colspan="4"><div align="center">Este filtro no sera tomado en cuenta para generar el txt</div></td>
        </tr>
<?php } 
?>
        <tr>
          <td width="151" height="22"><div align="right"> Concepto </div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodconc" type="text" id="txtcodconc" size="13" maxlength="10" value="<?php print $ls_codconc; ?>" readonly>
            <a href="javascript: ue_buscarconcepto();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtnomcon" type="text" class="sin-borde" id="txtnomcon" value="<?php print $ls_nomcon; ?>" size="40" readonly>
          </div></td>
          </tr>
        <tr>
          <td height="22" colspan="4" class="titulo-celdanew">&nbsp;</td>
          </tr>
        <tr>
          <td height="22"><div align="right">Quitar conceptos en cero</div></td>
          <td colspan="3"><div align="left">
            <input name="chkconceptocero" type="checkbox" class="sin-borde" id="chkconceptocero" value="1" checked>
          </div></td>
        </tr>
		     <tr class="titulo-celdanew">
          <td height="20" colspan="4"><div align="right" class="titulo-celdanew">Ordenado por </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo del Personal </div></td>
          <td width="173"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="1" checked>
          </div></td>
          <td width="160"><div align="right">Apellido del Personal</div></td>
          <td width="156"><div align="left">            <input name="rdborden" type="radio" class="sin-borde" value="2">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nombre del Personal</div></td>
          <td>            <div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="3">
          </div></td>
          <td><div align="right">C&eacute;dula del Personal</div></td>
          <td><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="4">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right"></div></td>
          <td colspan="3"><div align="right">
            <input name="operacion" type="hidden" id="operacion">
            <input name="reporte" type="hidden" id="reporte" value="<?php print $ls_reporte;?>">
		    <input name="subnom" type="hidden" id="subnom" value="<?php print $ls_subnom;?>">
          </div></td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);

function ue_cerrar()
{
	location.href = "sigespwindow_blank_nomina.php";
}

function ue_print()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		reporte=f.reporte.value;
		codconc=f.txtcodconc.value;
		nomcon=f.txtnomcon.value;
		tiporeporte=f.cmbbsf.value;
		subnom=f.subnom.value;
		subnomdes="";
		subnomhas="";
		if(subnom=='1')
		{
			subnomdes=f.txtcodsubnomdes.value;
			subnomhas=f.txtcodsubnomhas.value;
		}
		if(codconc!="")
		{
			conceptocero="";
			if(f.chkconceptocero.checked)
			{
				conceptocero=1;
			}
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
			if(f.rdborden[3].checked)
			{
				orden="4";
			}
			pagina="reportes/"+reporte+"?codconc="+codconc+"&orden="+orden+"&nomcon="+nomcon+"&conceptocero="+conceptocero+"&tiporeporte="+tiporeporte;
			pagina=pagina+"&subnomdes="+subnomdes+"&subnomhas="+subnomhas;			
			window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
		else
		{
			alert("Debe seleccionar un concepto.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_gendisk()
{
	f=document.form1;
	li_procesar=f.ejecutar.value;
	if(li_procesar==1)
	{	
		codconc=f.txtcodconc.value;
		if(codconc!="")
		{
			f.operacion.value="GENDISK";
			f.action="sigesp_sno_r_aportepatronal.php";
			f.submit();
		}
		else
		{
			alert("Debe seleccionar un concepto.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación");
   	}		
}

function ue_descargar(ruta)
{
	window.open("sigesp_sno_cat_directorio.php?ruta="+ruta+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarconcepto()
{
	window.open("sigesp_sno_cat_concepto.php?tipo=repapopat","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}
function ue_buscarsubnominadesde()
{
	window.open("sigesp_snorh_cat_subnomina.php?tipo=reportedesde","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarsubnominahasta()
{
	window.open("sigesp_snorh_cat_subnomina.php?tipo=reportehasta","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}
</script> 
</html>