<?php
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";		
		print "</script>";		
	}
	ini_set('memory_limit','256M');
	ini_set('max_execution_time','0');

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo,$ai_tipo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Arreglo de las variables de seguridad
		//	    		   as_desnom // Arreglo de las variables de seguridad
		//    Description: funci�n que guarda la seguridad de quien gener� el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Gener� el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_recibopago.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_hrecibopago.php",$ls_descripcion,$ls_codnom);
		}
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina1($as_titulo,$as_desnom,$as_periodo,$ad_fecdesper,$ad_fechasper,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina1
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_desnom // Descripci�n de la n�mina
		//	    		   as_periodo // Descripci�n del per�odo
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->addText(487,768,'7',$ad_fecdesper);
		$io_pdf->addText(487,757,'7',$ad_fechasper);
		$io_pdf->addText(513,746,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	}// end function uf_print_encabezado_pagina1
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina2($as_titulo,$as_desnom,$as_periodo,$ad_fecdesper,$ad_fechasper,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_desnom // Descripci�n de la n�mina
		//	    		   as_periodo // Descripci�n del per�odo
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->addText(487,375,'7',$ad_fecdesper);
		$io_pdf->addText(487,364,'7',$ad_fechasper);
		$io_pdf->addText(513,353,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera1($as_cedper,$as_nomper,$as_descar,$as_dirper,$as_tipper,$as_sueper,$ad_fecingper,$as_codcueban,$io_cabecera,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera1
		//		   Access: private 
		//	    Arguments: as_cedper // C�dula del personal
		//	    		   as_nomper // Nombre del personal
		//	    		   as_descar // Decripci�n del cargo
		//	    		   io_cabecera // objeto cabecera
		//	    		   io_pdf // Objeto PDF
		//    Description: funci�n que imprime la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_descar1 =substr($as_descar,0,100);
		$io_pdf->addText(80,710,'7',$as_nomper);
		$io_pdf->addText(80,697,'7',$as_dirper);
		$io_pdf->addText(80,685,'7',$as_descar1);
		$io_pdf->addText(80,673,'7',$as_tipper);
		
		
		$io_pdf->addText(472,710,'7',$as_sueper);
		$io_pdf->addText(472,697,'7',$ad_fecingper);
		$io_pdf->addText(472,685,'7',$as_codcueban);
		$io_pdf->addText(472,673,'7',$as_cedper);
		
		
		$io_pdf->saveState();
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera2($as_cedper,$as_nomper,$as_descar,$as_dirper,$as_tipper,$as_sueper,$ad_fecingper,$as_codcueban,$io_cabecera,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_cedper // C�dula del personal
		//	    		   as_nomper // Nombre del personal
		//	    		   as_descar // Decripci�n del cargo
		//	    		   io_cabecera // objeto cabecera
		//	    		   io_pdf // Objeto PDF
		//    Description: funci�n que imprime la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_descar1 =substr($as_descar,0,100);
		$io_pdf->addText(80,312,'7',$as_nomper);
		$io_pdf->addText(80,299,'7',$as_dirper);
		$io_pdf->addText(80,287,'7',$as_descar1);
		$io_pdf->addText(80,275,'7',$as_tipper);
		
		
		$io_pdf->addText(472,312,'7',$as_sueper);
		$io_pdf->addText(472,299,'7',$ad_fecingper);
		$io_pdf->addText(472,287,'7',$as_codcueban);
		$io_pdf->addText(472,275,'7',$as_cedper);
		
		
		$io_pdf->saveState();
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(645);
		$la_columna=array('codcon'=>'',
						  'denomasig'=>'',
						  'valorasig'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 7,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xPos'=>239, // Orientaci�n de la tabla
						 'cols'=>array('codcon'=>array('justification'=>'left','width'=>100),    // Justificaci�n y ancho de la columna
						 			   'denomasig'=>array('justification'=>'left','width'=>200), // Justificaci�n y ancho de la columna
						 			   'valorasig'=>array('justification'=>'right','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle2($la_data2,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$io_pdf->ezSetY(615);
		$la_columna=array('codcon'=>'',
						  'denomdedu'=>'',
						  'valordedu'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 7,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xPos'=>289, // Orientaci�n de la tabla
						 'cols'=>array('codcon'=>array('justification'=>'left','width'=>100),
						 			   'denomdedu'=>array('justification'=>'left','width'=>200), // Justificaci�n y ancho de la columna
						 			   'valordedu'=>array('justification'=>'right','width'=>200))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data2,$la_columna,'',$la_config);
		
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_detalle3($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(245);
		$la_columna=array('codcon'=>'',
						  'denomasig'=>'',
						  'valorasig'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 7,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xPos'=>239, // Orientaci�n de la tabla
						 'cols'=>array('codcon'=>array('justification'=>'left','width'=>100),    // Justificaci�n y ancho de la columna
						 			   'denomasig'=>array('justification'=>'left','width'=>200), // Justificaci�n y ancho de la columna
						 			   'valorasig'=>array('justification'=>'right','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	
//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_detalle4($la_data2,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('codcon'=>'',
						  'denomdedu'=>'',
						  'valordedu'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 7,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xPos'=>289, // Orientaci�n de la tabla
						 'cols'=>array('codcon'=>array('justification'=>'left','width'=>100),
						 			   'denomdedu'=>array('justification'=>'left','width'=>200), // Justificaci�n y ancho de la columna
						 			   'valordedu'=>array('justification'=>'right','width'=>200))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data2,$la_columna,'',$la_config);
		
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera1($ai_toting,$ai_totded,$ai_totnet,$as_codcueban,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera1
		//		   Access: private 
		//	    Arguments: ai_toting // Total Ingresos
		//	   			   ai_totded // Total Deducciones
		//	   			   ai_totnet // Total Neto
		//	   			   as_codcueban // Codigo cuenta bancaria
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el fin de la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares, $io_monedabsf, $ls_tiporeporte;
		$io_pdf->addText(400,453,'8',$ai_toting);
		$io_pdf->addText(510,453,'8',$ai_totded);
		$io_pdf->addText(500,428,'8',$ai_totnet);
		$io_piepagina=$io_pdf->openObject(); // Creamos el objeto pie de p�gina
		$io_pdf->saveState();
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_piepagina,'all');
		$io_pdf->stopObject($io_piepagina); // Detener el objeto pie de p�gina
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera2($ai_toting,$ai_totded,$ai_totnet,$as_codcueban,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera
		//		   Access: private 
		//	    Arguments: ai_toting // Total Ingresos
		//	   			   ai_totded // Total Deducciones
		//	   			   ai_totnet // Total Neto
		//	   			   as_codcueban // Codigo cuenta bancaria
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el fin de la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares, $io_monedabsf, $ls_tiporeporte;
		$io_pdf->addText(400,62,'8',$ai_toting);
		$io_pdf->addText(510,62,'8',$ai_totded);
		$io_pdf->addText(500,32,'8',$ai_totnet);
		$io_piepagina=$io_pdf->openObject(); // Creamos el objeto pie de p�gina
		$io_pdf->saveState();
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_piepagina,'all');
		$io_pdf->stopObject($io_piepagina); // Detener el objeto pie de p�gina
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	$ls_tiporeporte="0";
	$ls_bolivares ="Bs.";
	if($_SESSION["la_nomina"]["tiponomina"]=="NORMAL")
	{
		require_once("sigesp_sno_class_report.php");
		$io_report=new sigesp_sno_class_report();
		$li_tipo=1;
	}
	if($_SESSION["la_nomina"]["tiponomina"]=="HISTORICA")
	{
		require_once("sigesp_sno_class_report_historico.php");
		$io_report=new sigesp_sno_class_report_historico();
		$li_tipo=2;
	}	
	require_once("../../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
	$io_monedabsf=new sigesp_c_reconvertir_monedabsf();				
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$li_adelanto=$_SESSION["la_nomina"]["adenom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$ls_titulo="<b>COMPROBANTE DE PAGO</b>";
	$ls_periodo="Periodo: <b>".$ls_peractnom."</b> del <b>".$ld_fecdesper."</b> al <b>".$ld_fechasper."</b>";
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_coduniadm=$io_fun_nomina->uf_obtenervalor_get("coduniadm","");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_conceptop2=$io_fun_nomina->uf_obtenervalor_get("conceptop2","");
	$ls_conceptoreporte=$io_fun_nomina->uf_obtenervalor_get("conceptoreporte","");
	$ls_tituloconcepto=$io_fun_nomina->uf_obtenervalor_get("tituloconcepto","");
	$ls_quincena=$io_fun_nomina->uf_obtenervalor_get("quincena","-");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_codubifis=$io_fun_nomina->uf_obtenervalor_get("codubifis","");
	$ls_codpai=$io_fun_nomina->uf_obtenervalor_get("codpai","");
	$ls_codest=$io_fun_nomina->uf_obtenervalor_get("codest","");
	$ls_codmun=$io_fun_nomina->uf_obtenervalor_get("codmun","");
	$ls_codpar=$io_fun_nomina->uf_obtenervalor_get("codpar","");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_recibopago_personal($ls_codperdes,$ls_codperhas,$ls_coduniadm,$ls_conceptocero,$ls_conceptop2,
													  $ls_conceptoreporte,$ls_codubifis,$ls_codpai,$ls_codest,$ls_codmun,$ls_codpar,
													  $ls_subnomdes,$ls_subnomhas,$ls_orden); // Cargar el DS con los datos de la cabecera del reporte
	}
	if(($lb_valido==false) || ($io_report->rs_data->RecordCount()==0)) // Existe alg�n error � no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3,1,1,2); // Configuraci�n de los margenes en cent�metros
		uf_print_encabezado_pagina1($ls_titulo,$ls_desnom,$ls_periodo,$ld_fecdesper,$ld_fechasper,$io_pdf); // Imprimimos el encabezado de la p�gina
		uf_print_encabezado_pagina2($ls_titulo,$ls_desnom,$ls_periodo,$ld_fecdesper,$ld_fechasper,$io_pdf); // Imprimimos el encabezado de la p�gina
		$li_totrow=$io_report->rs_data->RecordCount();
		$li_reg=1;
		$li_i=1;
		while((!$io_report->rs_data->EOF)&&($lb_valido))
		{
			$li_toting=0;
			$li_totded=0;			
			$ls_codper=$io_report->rs_data->fields["codper"];
			$ls_cedper=$io_report->rs_data->fields["cedper"];
			$ls_dirper=$io_report->rs_data->fields["dirper"];
			$ls_tipper=$io_report->rs_data->fields["destipper"];
			$ls_sueper=$io_report->rs_data->fields["sueper"];
			$ld_fecingper=$io_report->rs_data->fields["fecingper"];
			$ld_fecingper=$io_funciones->uf_convertirfecmostrar($ld_fecingper);	
			$ls_codcueban=$io_report->rs_data->fields["codcueban"];			
			$ls_nomper=$io_report->rs_data->fields["apeper"].", ".$io_report->rs_data->fields["nomper"];
			$ls_descar=$io_report->rs_data->fields["descar"];
			$ls_codcueban=$io_report->rs_data->fields["codcueban"];
			$li_total=$io_report->rs_data->fields["total"];
			$io_cabecera=$io_pdf->openObject(); // Creamos el objeto cabecera
			if($li_reg==1)
			{
				uf_print_cabecera1($ls_cedper,$ls_nomper,$ls_descar,$ls_dirper,$ls_tipper,$ls_sueper,$ld_fecingper,$ls_codcueban,$io_cabecera,$io_pdf); // Imprimimos la cabecera del registro
			}
			else
			{
				uf_print_cabecera2($ls_cedper,$ls_nomper,$ls_descar,$ls_dirper,$ls_tipper,$ls_sueper,$ld_fecingper,$ls_codcueban,$io_cabecera,$io_pdf); // Imprimimos la cabecera del registro
			}

			$lb_valido=$io_report->uf_recibopago_conceptopersonal($ls_codper,$ls_conceptocero,$ls_conceptop2,
																  $ls_conceptoreporte,$ls_tituloconcepto,$ls_quincena); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->rs_data_detalle->RecordCount();
				$li_asig=0;
				$li_dedu=0;				
				if($li_adelanto==1)// Utiliza el adelanto de quincena
				{					
					switch($ls_quincena)
					{
						case "1": // primera quincena;
							$li_asig=$li_asig+1;
							$ls_codconc="----------";
							$ls_nomcon="ADELANTO 1ra QUINCENA";
							$li_valsal=round($li_total/2,2);
							$li_toting=$li_toting+$li_valsal;
							$li_valsal=$io_fun_nomina->uf_formatonumerico($li_valsal);
							$la_data_a[$li_asig]=array('codcon'=>$ls_codconc,'denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
							break;
							
						case "2": // segunda quincena;
							while(!$io_report->rs_data_detalle->EOF)
							{
								$ls_tipsal=rtrim($io_report->rs_data_detalle->fields["tipsal"]);
								if(($ls_tipsal=="A") || ($ls_tipsal=="V1") || ($ls_tipsal=="V2") || ($ls_tipsal=="R")) // Buscamos las asignaciones
								{
									$li_asig=$li_asig+1;									
									$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
									$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
									if ($ls_tipsal!="R")
									{
										$li_toting=$li_toting+abs($io_report->rs_data_detalle->fields["valsal"]);
									}									
									$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data_detalle->fields["valsal"]));
									$la_data_a[$li_asig]=array('codcon'=>$ls_codconc,'denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
								}
								else // Buscamos las deducciones y aportes
								{
									$li_dedu=$li_dedu+1;									
									$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
									$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
									$li_totded=$li_totded+abs($io_report->rs_data_detalle->fields["valsal"]);
									$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data_detalle->fields["valsal"]));
									$la_data_d[$li_dedu]=array('codcon'=>$ls_codconc,'denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
								}
								$io_report->rs_data_detalle->MoveNext();
							}
							$li_dedu=$li_dedu+1;
							$ls_codconc="----------";
							$ls_nomcon="ADELANTO 1ra QUINCENA";
							$li_valsal=round($li_total/2,2);
							$li_totded=$li_totded+$li_valsal;
							$li_valsal=$io_fun_nomina->uf_formatonumerico($li_valsal);
							$la_data_d[$li_dedu]=array('codcon'=>$ls_codconc,'denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
							break;
							
						case "3": // Mes Completo;						
							while(!$io_report->rs_data_detalle->EOF)
							{
								$ls_tipsal=rtrim($io_report->rs_data_detalle->fields["tipsal"]);
								if(($ls_tipsal=="A") || ($ls_tipsal=="V1") || ($ls_tipsal=="V2") || ($ls_tipsal=="R")) // Buscamos las asignaciones
								{
									$li_asig=$li_asig+1;									
									$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
									$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
									if ($ls_tipsal!="R")
									{
										$li_toting=$li_toting+abs($io_report->rs_data_detalle->fields["valsal"]);
									}									
									$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data_detalle->fields["valsal"]));
									$la_data_a[$li_asig]=array('codcon'=>$ls_codconc,'denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
								}
								else // Buscamos las deducciones y aportes
								{
									$li_dedu=$li_dedu+1;									
									$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
									$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
									$li_totded=$li_totded+abs($io_report->rs_data_detalle->fields["valsal"]);
									$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data_detalle->fields["valsal"]));
									$la_data_d[$li_dedu]=array('codcon'=>$ls_codconc,'denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
								}
								$io_report->rs_data_detalle->MoveNext();
							}
							break;
					}
				}
				else// No utiliza adelanto de quincena
				{					
					while(!$io_report->rs_data_detalle->EOF)
					{					
						$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
						$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
						$li_valsal=abs($io_report->rs_data_detalle->fields["valsal"]);
						$ls_tipsal=rtrim($io_report->rs_data_detalle->fields["tipsal"]);
						if(($ls_tipsal=="A") || ($ls_tipsal=="V1") || ($ls_tipsal=="V2") || ($ls_tipsal=="R")) // Buscamos las asignaciones
						{
							$li_asig=$li_asig+1;							
							$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
							$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
							if ($ls_tipsal!="R")
							{								
								$li_toting=$li_toting+abs($io_report->rs_data_detalle->fields["valsal"]);
							}							
							$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data_detalle->fields["valsal"]));
							$la_data_a[$li_asig]=array('codcon'=>$ls_codconc,'denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
						}
						else // Buscamos las deducciones y aportes
						{
							$li_dedu=$li_dedu+1;							
							$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
							$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
							$li_totded=$li_totded+abs($io_report->rs_data_detalle->fields["valsal"]);
							$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data_detalle->fields["valsal"]));
							$la_data_d[$li_dedu]=array('codcon'=>$ls_codconc,'denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
						}
						$io_report->rs_data_detalle->MoveNext();
					}
				}
				if($li_asig<=$li_dedu)
				{
					$li_total=$li_dedu;
				}
				else
				{
					$li_total=$li_asig;
				}				
				for($li_s=1;$li_s<=$li_total;$li_s++) 
				{
					$la_valoresasig["codcon"]="";
					$la_valoresasig["denomasig"]="";
					$la_valoresasig["valorasig"]="";
					$la_valoresdeduc["codcon"]="";
					$la_valoresdeduc["denomdedu"]="";
					$la_valoresdeduc["valordedu"]="";
					if($li_s<=$li_asig)
					{
						$la_valoresasig["codcon"]=$la_data_a[$li_s]["codcon"];
						$la_valoresasig["denomasig"]=$la_data_a[$li_s]["denominacion"];
						$la_valoresasig["valorasig"]=$la_data_a[$li_s]["valor"];
					}
					if($li_s<=$li_dedu)
					{
						$la_valoresdeduc["codcon"]=$la_data_d[$li_s]["codcon"];
						$la_valoresdeduc["denomdedu"]=$la_data_d[$li_s]["denominacion"];
						$la_valoresdeduc["valordedu"]=$la_data_d[$li_s]["valor"];
					}
					$la_data[$li_s]=$la_valoresasig;
					$la_data2[$li_s]=$la_valoresdeduc;
				}
				$li_totnet=$li_toting-$li_totded;
				$li_toting=$io_fun_nomina->uf_formatonumerico($li_toting);
				$li_totded=$io_fun_nomina->uf_formatonumerico($li_totded);
				$li_totnet=$io_fun_nomina->uf_formatonumerico($li_totnet);
				if($li_reg==1)
				{
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				    uf_print_detalle2($la_data2,$io_pdf); // Imprimimos el detalle 
					uf_print_pie_cabecera1($li_toting,$li_totded,$li_totnet,$ls_codcueban,$io_pdf); // Imprimimos pie de la cabecera
				}
				else
				{
					uf_print_detalle3($la_data,$io_pdf); // Imprimimos el detalle 
				    uf_print_detalle4($la_data2,$io_pdf); // Imprimimos el detalle 
					uf_print_pie_cabecera2($li_toting,$li_totded,$li_totnet,$ls_codcueban,$io_pdf); // Imprimimos pie de la cabecera
				}
				unset($la_data_a);
				unset($la_data_d);
				unset($la_data);
				$io_pdf->stopObject($io_cabecera); // Detener el objeto cabecera
				if(($li_i<$li_totrow)&&($li_reg==2))
				{
					$io_pdf->ezNewPage(); // Insertar una nueva p�gina
					$li_reg=1;
				}
				else
				{
					$li_reg=2;
				}
			}
			$li_i++;
			$io_report->rs_data->MoveNext();
		}
		if($lb_valido) // Si no ocurrio ning�n error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresi�n de los n�meros de p�gina
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo alg�n error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 