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
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_ubicacionfisica.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_limpiarvariables
		//	  Description: Funci�n que limpia todas las variables necesarias en la p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 18/03/2006 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codubifis,$ls_desubifis,$ls_codpai,$ls_despai,$ls_codest,$ls_desest,$ls_codmun,$ls_desmun;
		global $ls_codpar,$ls_despar,$ls_dirubifis,$ls_existe,$ls_operacion,$io_fun_nomina;
		
		$ls_codubifis="";
		$ls_desubifis="";
		$ls_codpai="";
		$ls_despai="";
		$ls_codest="";
		$ls_desest="";
		$ls_codmun="";
		$ls_desmun="";
		$ls_codpar="";
		$ls_despar="";
		$ls_dirubifis="";
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Funci�n que carga todas las variables necesarias en la p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 18/03/2006 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codubifis, $ls_desubifis,$ls_codpai,$ls_despai,$ls_codest,$ls_desest,$ls_codmun,$ls_desmun;
		global $ls_codpar,$ls_despar,$ls_dirubifis;
		
		$ls_codubifis=$_POST["txtcodubifis"];
		$ls_desubifis=$_POST["txtdesubifis"];
		$ls_codpai=$_POST["txtcodpai"];
		$ls_despai=$_POST["txtdespai"];
		$ls_codest=$_POST["txtcodest"];
		$ls_desest=$_POST["txtdesest"];
		$ls_codmun=$_POST["txtcodmun"];
		$ls_desmun=$_POST["txtdesmun"];
		$ls_codpar=$_POST["txtcodpar"];
		$ls_despar=$_POST["txtdespar"];
		$ls_dirubifis=$_POST["txtdirubifis"];
   }
   //--------------------------------------------------------------
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
<title >Definici&oacute;n de Ubicaci&oacute;n F&iacute;sica</title>
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
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<?php 
	require_once("sigesp_snorh_c_ubicacionfisica.php");
	$io_ubicacionfisica=new sigesp_snorh_c_ubicacionfisica();
	uf_limpiarvariables();	
	switch ($ls_operacion) 
	{
		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_ubicacionfisica->uf_guardar($ls_existe,$ls_codubifis,$ls_desubifis,$ls_codpai,$ls_codest,$ls_codmun,
													   $ls_codpar,$ls_dirubifis,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_ubicacionfisica->uf_delete_ubicacionfisica($ls_codubifis,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			break;
	}
	$io_ubicacionfisica->uf_destructor();
	unset($io_ubicacionfisica);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de N�mina</td>
			<td width="346" bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
        </table>
	 </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif"  title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20" border="0"></a></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td>
      <p>&nbsp;</p>
      <table width="550" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="2" class="titulo-ventana">Definici&oacute;n de Ubicaci&oacute;n F&iacute;sica </td>
        </tr>
        <tr>
          <td width="132" height="22">&nbsp;</td>
          <td width="412">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo</div></td>
          <td><div align="left">
            <input name="txtcodubifis" type="text" id="txtcodubifis" size="7" maxlength="4" value="<?php print $ls_codubifis;?>" onKeyUp="" onBlur="javascript: ue_rellenarcampo(this,4);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Denominaci&oacute;n</div></td>
          <td><div align="left">
            <input name="txtdesubifis" type="text" id="txtdesubifis" size="60" maxlength="100" value="<?php print $ls_desubifis;?>" onKeyUp="ue_validarcomillas(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"> <div align="right">Pais</div></td>
          <td height="22"><div align="left">
            <input name="txtcodpai" type="text" id="txtcodpai" value="<?php print $ls_codpai;?>" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarpais();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdespai" type="text" class="sin-borde" id="txtdespai" value="<?php print $ls_despai;?>" size="60" maxlength="50" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Estado</div></td>
          <td height="22"><div align="left">
            <input name="txtcodest" type="text" id="txcodest" value="<?php print $ls_codest;?>" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarestado();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdesest" type="text" class="sin-borde" id="txtdesest" value="<?php print $ls_desest;?>" size="60" maxlength="50" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Municipio</div></td>
          <td height="22"><div align="left">
            <input name="txtcodmun" type="text" id="txtcodmun" value="<?php print $ls_codmun;?>" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarmunicipio();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdesmun" type="text" class="sin-borde" id="txtdesmun" value="<?php print $ls_desmun;?>" size="60" maxlength="50" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Parroquia</div></td>
          <td height="22"><div align="left">
            <input name="txtcodpar" type="text" id="txtcodpar" value="<?php print $ls_codpar;?>" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarparroquia();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdespar" type="text" class="sin-borde" id="txtdespar" value="<?php print $ls_despar;?>" size="60" maxlength="50" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Direcci&oacute;n</div></td>
          <td height="22"><div align="left">
            <label>
            <input name="txtdirubifis" type="text" id="txtdirubifis" value="<?php print $ls_dirubifis;?>" size="70" maxlength="200" onKeyUp="ue_validarcomillas(this);">
            </label>
          </div></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td height="22"><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"></td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.existe.value="FALSE";		
		f.action="sigesp_snorh_d_ubicacionfisica.php";
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		codubifis = ue_validarvacio(f.txtcodubifis.value);
		desubifis = ue_validarvacio(f.txtdesubifis.value);
		if ((codubifis!="")&&(desubifis!=""))
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_snorh_d_ubicacionfisica.php";
			f.submit();
		}
		else
		{
			alert("Debe llenar todos los datos.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		if(f.existe.value=="TRUE")
		{
			codubifis = ue_validarvacio(f.txtcodubifis.value);
			if (codubifis!="")
			{
				if(confirm("�Desea eliminar el Registro actual?"))
				{
					f.operacion.value="ELIMINAR";
					f.action="sigesp_snorh_d_ubicacionfisica.php";
					f.submit();
				}
			}
			else
			{
				alert("Debe buscar el registro a eliminar.");
			}
		}
		else
		{
			alert("Debe buscar el registro a eliminar.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_cerrar()
{
	location.href = "sigespwindow_blank.php";
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_snorh_cat_ubicacionfisica.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_buscarpais()
{
	window.open("sigesp_snorh_cat_pais.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarestado()
{
	f=document.form1;
	codpai=ue_validarvacio(f.txtcodpai.value);
	if(codpai!="")
	{
		window.open("sigesp_snorh_cat_estado.php?codpai="+codpai+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un pais.");
	}
}

function ue_buscarmunicipio()
{
	f=document.form1;
	codpai=ue_validarvacio(f.txtcodpai.value);
	codest=ue_validarvacio(f.txtcodest.value);
	if((codpai!="")||(codest!=""))
	{
		window.open("sigesp_snorh_cat_municipio.php?codpai="+codpai+"&codest="+codest+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un pais y un estado.");
	}
}

function ue_buscarparroquia()
{
	f=document.form1;
	codpai=ue_validarvacio(f.txtcodpai.value);
	codest=ue_validarvacio(f.txtcodest.value);
	codmun=ue_validarvacio(f.txtcodmun.value);
	if((codpai!="")||(codest!="")||(codmun!=""))
	{
		window.open("sigesp_snorh_cat_parroquia.php?codpai="+codpai+"&codest="+codest+"&codmun="+codmun+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un pais, un estado y un municipio.");
	}
}

function ue_ayuda()
{
	width=(screen.width);
	height=(screen.height);
	//window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/sigesp_hlp_snr_ubicacionfisica.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
}
</script> 
</html>