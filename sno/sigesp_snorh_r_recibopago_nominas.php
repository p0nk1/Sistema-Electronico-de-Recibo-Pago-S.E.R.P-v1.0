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

include '../database.php';
$conn = pg_connect("user=".DB_USER." password=".DB_PASS." port=".DB_PORT." dbname=".DB_NAME." host=".DB_HOST);

      //$sql = "SELECT a.codnom,a.codper,b.nomper,b.apeper FROM sno_personalnomina a,sno_personal b where b.cedper='$cedula_rp' and a.codper=b.codper and a.codnom<'0009' order by a.codnom ASC limit 1";
	$sql = "SELECT a.codnom,a.codper,b.nomper,b.apeper FROM sno_personalnomina a,sno_personal b where b.cedper='$cedula_rp' and a.codper=b.codper order by a.codnom ASC limit 1";
      echo $sql;
      $sql = pg_query($conn, $sql);

	   while ($row = pg_fetch_array($sql))
		{
		$codnom_rp=$row["codnom"]; 
echo $codnom_rp;
		$codper_rp=$row["codper"];
		$nomper_rp=$row["nomper"];
		$apeper_rp=$row["apeper"];
		}
		if (($cedula_rp!="") or ($cedula_rp!=NULL)) 
		    {
			if  (($codper_rp=="") or ($codper_rp==NULL) or (empty($codper_rp)))  
			    {
				echo '<script language="JavaScript">alert("La cédula registrada no corresponde con algún personal!");</script>';
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
		         echo '<script language="JavaScript">alert("No ha iniciado sessión para esta pantalla !");</script>';
		         echo "<meta http-equiv='refresh' content='0;URL=../index.php'/>";
			}		
		    }



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
<title >Reporte Recibo de Pago</title>
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
<!--<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>-->
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../css/tablas.css" rel="stylesheet" type="text/css">
<link href="../css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../css/general.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img align="center" src="../img/banner_unes.jpg" width="770" height="130"></td>
  </tr>
    <td height="20"  colspan="2" class="cd-menu">
	<!--<table width="770" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>&nbsp;</tr>	
          <td width="423" height="20" align="left" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Emisión de Recibos de Pago</td>			
      </table></td>-->
  </tr> 

</table>
<form name="form1" method="post" action="">
  <table width="770"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC" class="contorno">
  <tr>
    <td height="136">

      <table width="770" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="7" class="titulo-celunes">Reporte Recibo de Pago </td>
        </tr>
        <tr style="display:none">
          <td height="20"><div align="right">Reporte en</div></td>
          <td height="20"><div align="left">
            <select name="cmbbsf" id="cmbbsf">
              <option value="0" selected>Bs.</option>
              <option value="1">Bs.F.</option>
            </select>
          </div></td>


  <tr class="formato-blanco">
    <td height="22" colspan="3"><p align="left" class="Estilo2 Estilo1"><a href="../index.php"><img title="Salir" src="../img/cerrar.jpg" alt="Grabar" width="45" height="45" border="0"></a></p></td>
    <td height="22" colspan="4"><p align="right" class="Estilo2 Estilo1"><strong>Usuario: <?php echo $nomper_rp." ".$apeper_rp ?></strong></p></td>
  </tr>

        </tr>
        <!--<tr>
          <td height="20" colspan="4" class="titulo-celunes">N&oacute;mina</td>
          </tr>-->
        <tr>
          <td height="20"><div align="right"></div></td>
          <td height="20" colspan="3"><div align="left">
            <input name="txtcodnom" type="hidden" id="txtcodnom" size="8" maxlength="4" value="<?php print $codnom_rp;?>" readonly>
            <label>
            <input name="txtdesnom" type="text" class="sin-borde" id="txtdesnom" size="50" readonly>
            </label>
            <input name="txttipnom" type="hidden" id="txttipnom">
          </div>            <div align="left"><a href="javascript: ue_buscarnominahasta();"></a></div></td>
          </tr>
        <tr>
          <td height="20"><div align="right">  </div></td> <!--Subn&oacute;mina Desde-->
          <td height="20"><input name="txtcodsubnomdes" type="hidden" id="txtcodsubnomdes" size="13" maxlength="10" value="" readonly>
            </td>
          <td height="20"><div align="right"> </div></td> <!--Subn&oacute;mina Hasta-->
          <td height="20"><input name="txtcodsubnomhas" type="hidden" id="txtcodsubnomhas" value="" size="13" maxlength="10" readonly>
            </td>
        </tr>
        <tr>
          <td height="20">&nbsp;</td>
          <td height="20"colspan="4"><div align="right">ELIJA EL PERIODO A GENERAR EL RECIBO DE PAGO </div></td>
          <td height="20">&nbsp;</td>
        </tr>
        <tr>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
        </tr>
        <tr>
          <td height="20" colspan="3"><div align="right">Seleccione el Periodo</div></td>
          <td height="20" colspan="4"><div align="left">
            <input name="txtperdes" type="text" id="txtperdes" size="6" maxlength="3" value="" readonly>
            <a href="javascript: ue_buscarperiododesde();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="periodo" width="15" height="15" border="0" id="periodo"></a>
            <input name="txtfecdesper" type="hidden" id="txtfecdesper">
</div></td>

            <input name="txtperhas" type="hidden" id="txtperhas" size="6" maxlength="3" value="" readonly>
            <!--<a href="javascript: ue_buscarperiodohasta();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="periodo" width="15" height="15" border="0" id="periodo"></a>-->
            <input name="txtfechasper" type="hidden" id="txtfechasper">

        </tr>
<tr><td><p>&nbsp;</p></td></tr>
  <tr class="formato-blanco">
    <td height="22" colspan="7"><div align="center">
      <input name="submit" type="submit" id="txthabdes" value="GENERAR RECIBO DE PAGO" onClick="ue_print()">
    </div></td>
  </tr> 
        <!--<tr>
          <td height="20" colspan="4" class="titulo-celunes">Intervalo de Personal </td>
          </tr>-->
        <tr>
          <td width="133" height="22"><div align="right">  </div></td>
          <td width="112"><div align="left">
            <input name="txtcodperdes" type="hidden" id="txtcodperdes" size="13" maxlength="10" value="<?php print $codper_rp;?>" readonly>
            </div></td>
          <td width="119"><div align="right"> </div></td>
          <td width="121"><div align="left">
            <input name="txtcodperhas" type="hidden" id="txtcodperhas" value="<?php print $codper_rp;?>" size="13" maxlength="10" readonly>
            </div></td>
        </tr>
        <!--<tr>
          <td height="20" colspan="4" class="titulo-celunes">&nbsp;</td>
          </tr>
        <tr>-->
          <td height="22"><div align="right"> </div></td> <!--Quitar conceptos en cero-->
          <td><div align="left">
            <input name="chkconceptocero" type="hidden" class="sin-borde" id="chkconceptocero" value="1" checked>
          </div></td>
          <td><div align="right"></div></td> <!--Mostrar Concepto P2-->
          <td><div align="left">
            <input name="chkconceptop2" type="hidden" class="sin-borde" id="chkconceptop2" value="1">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right"></div></td> <!--Incluir conceptos reporte-->
          <td><div align="left">
            <input name="chkconceptoreporte" type="hidden" class="sin-borde" id="chkconceptoreporte" value="1">
          </div></td>
          <td><div align="right"> </div></td> <!--Usar t&iacute;tulo del concepto--> 
          <td><div align="left">
            <input name="chktituloconcepto" type="hidden" class="sin-borde" id="chktituloconcepto" value="1" checked>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right"> </div></td> <!--Unidad Administrativa-->
          <td colspan="3"><div align="left">
            <input name="txtcoduniadm" type="hidden" id="txtcoduniadm" size="19" maxlength="16" readonly>
            
            <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" size="40" maxlength="30" readonly>
          </div></td>
          </tr>
        <!--<tr>
          <td height="22">&nbsp;</td>
          <td>        </tr>-->
        <!--<tr>
          <td height="20" colspan="4" class="titulo-celdanew"><div align="right" class="titulo-celunes">Ordenado por </div></td>
          </tr>-->
        <tr>
          <td height="22"><div align="right"> </div></td><!--C&oacute;digo del Personal-->
          <td colspan="3"><div align="left">
            <input name="rdborden" type="hidden" class="sin-borde" value="1" checked>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right"></div></td><!--Apellido del Personal-->
          <td colspan="3"><div align="left">
            <input name="rdborden" type="hidden" class="sin-borde" value="2">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right"></div></td><!--Nombre del Personal-->
          <td colspan="3"><div align="left">
            <input name="rdborden" type="hidden" class="sin-borde" value="3">
          </div></td>
        </tr>
        <tr>
          <!--<td height="22">&nbsp;</td>-->
          <td colspan="3"> <div align="right">
            <input name="recibo" type="hidden" id="recibo" value="<?php print $ls_recibo;?>">
			<input name="pagina" type="hidden" id="pagina">
			<input name="operacion" type="hidden" id="operacion">
          </div></td>
        </tr>
 
      </table><?php include ("../piepagina.php"); ?>
    </td>
  </tr>
</table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</form>      
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
				pagina=pagina+"&codsubnomdes="+codsubnomdes+"&codsubnomhas="+codsubnomhas;
				window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		                //f.action="../index.php";
			        //f.submit();
			}
			else
			{
				alert("El rango del personal está erroneo");
			}
		}
		else
		{
			alert("Debe seleccionar un rango de períodos.");
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

function ue_buscarperiododesde()
{
	f=document.form1;
	codnom=f.txtcodnom.value;
	if(codnom!="")
	{
		window.open("sigesp_sno_cat_hperiodo.php?tipo=reprecpagcondes&codnom="+codnom+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=200,top=200,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar una nómina.");
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
		alert("Debe seleccionar un rango de nóminas y aun período desde.");
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
		alert("Para filtrar por Subnóminas La nómina debe estar seleccionada.");
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
		alert("Debe seleccionar una subnómina desde.");
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
				alert("El rango del personal está erroneo.");
			}
		}
		else
		{
			alert("Debe seleccionar un rango de nóminas y períodos.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion.");
   	}		
}

</script> 
</html>
