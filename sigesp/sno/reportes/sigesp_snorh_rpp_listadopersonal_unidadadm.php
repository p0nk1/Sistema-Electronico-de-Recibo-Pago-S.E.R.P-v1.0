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
	function uf_insert_seguridad($as_titulo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//    Description: funci�n que guarda la seguridad de quien gener� el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 14/08/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_unidadadministrativa.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_titulo2,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 14/08/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,755,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,540,11,$as_titulo); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(10,$as_titulo2);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,520,10,$as_titulo2); // Agregar el t�tulo
		$io_pdf->addText(712,560,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(718,553,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera(&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 14/08/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSety(510);
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(50,495,700,$io_pdf->getFontHeight(12));
        $io_pdf->setColor(0,0,0);
		$la_data[1]=array('codigo'=>'<b>C�digo</b>',
						  'nombre'=>'<b>Apellidos y Nombres</b>',
						  'unidadadm'=>'<b>Unidad Administrativa</b>',
						  'desubifis'=>'<b>Ubicaci�n F�sica</b>',
						  'despai'=>'<b>Pa�s</b>',
						  'desest'=>'<b>Estado</b>',
						  'denmun'=>'<b>Municipio</b>',
						  'denpar'=>'<b>Parroquia</b>');
		$la_columna=array('codigo'=>'',
						  'nombre'=>'',
						  'unidadadm'=>'',
						  'desubifis'=>'',
						  'despai'=>'',
						  'desest'=>'',
						  'denmun'=>'',
						  'denpar'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos' => 405,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>55), // Justificaci�n y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>175), // Justificaci�n y ancho de la columna
						 			   'unidadadm'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la columna
						 			   'desubifis'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la columna
						 			   'despai'=>array('justification'=>'center','width'=>60), // Justificaci�n y ancho de la columna
						 			   'desest'=>array('justification'=>'center','width'=>70), // Justificaci�n y ancho de la columna
						 			   'denmun'=>array('justification'=>'center','width'=>70), // Justificaci�n y ancho de la columna
						 			   'denpar'=>array('justification'=>'center','width'=>70))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 14/08/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('codigo'=>'<b>C�digo</b>',
						  'nombre'=>'<b>Apellidos y Nombres</b>',
						  'unidadadm'=>'<b>Unidad Administrativa</b>',
						  'desubifis'=>'<b>Ubicaci�n F�sica</b>',
						  'despai'=>'<b>Pa�s</b>',
						  'desest'=>'<b>Estado</b>',
						  'denmun'=>'<b>Municipio</b>',
						  'denpar'=>'<b>Parroquia</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos' => 405,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>55), // Justificaci�n y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>175), // Justificaci�n y ancho de la columna
						 			   'unidadadm'=>array('justification'=>'left','width'=>100), // Justificaci�n y ancho de la columna
						 			   'desubifis'=>array('justification'=>'left','width'=>100), // Justificaci�n y ancho de la columna
						 			   'despai'=>array('justification'=>'left','width'=>60), // Justificaci�n y ancho de la columna
						 			   'desest'=>array('justification'=>'left','width'=>70), // Justificaci�n y ancho de la columna
						 			   'denmun'=>array('justification'=>'left','width'=>70), // Justificaci�n y ancho de la columna
						 			   'denpar'=>array('justification'=>'left','width'=>70))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
	$ls_titulo="<b>Listado de Personal</b>";
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	$ls_coduniadmdes=$io_fun_nomina->uf_obtenervalor_get("coduniadmdes","");
	$ls_coduniadmhas=$io_fun_nomina->uf_obtenervalor_get("coduniadmhas","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_activo=$io_fun_nomina->uf_obtenervalor_get("activo","");
	$ls_egresado=$io_fun_nomina->uf_obtenervalor_get("egresado","");
	$ls_causaegreso=$io_fun_nomina->uf_obtenervalor_get("causaegreso","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_activono=$io_fun_nomina->uf_obtenervalor_get("activono","");
	$ls_vacacionesno=$io_fun_nomina->uf_obtenervalor_get("vacacionesno","");
	$ls_suspendidono=$io_fun_nomina->uf_obtenervalor_get("suspendidono","");
	$ls_egresadono=$io_fun_nomina->uf_obtenervalor_get("egresadono","");
	$ls_masculino=$io_fun_nomina->uf_obtenervalor_get("masculino","");
	$ls_femenino=$io_fun_nomina->uf_obtenervalor_get("femenino","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$ls_titulo2="";
	if(($ls_coduniadmdes!="") && ($ls_coduniadmhas!=""))
	{
		$ls_titulo2="Unidad Administrativa Desde:<b>".$ls_coduniadmdes."</b> Hasta:<b>".$ls_coduniadmhas."</b> ";
	}
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_listadopersonalunidadadm_personal($ls_codnomdes,$ls_codnomhas,$ls_codperdes,$ls_codperhas,
																	$ls_activo,$ls_egresado,$ls_causaegreso,$ls_activono,
																	$ls_vacacionesno,$ls_suspendidono,$ls_egresadono,$ls_masculino,
																	$ls_femenino,$ls_coduniadmdes,$ls_coduniadmhas,$ls_orden); // Obtenemos el detalle del reporte
	}
	if($lb_valido==false) // Existe alg�n error � no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		//error_reporting(E_ALL);
		//set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(4.1,2.5,3,3); // Configuraci�n de los margenes en cent�metros
		uf_print_encabezado_pagina($ls_titulo,$ls_titulo2,$io_pdf); // Imprimimos el encabezado de la p�gina
		$io_pdf->ezStartPageNumbers(750,50,10,'','',1); // Insertar el n�mero de p�gina
		uf_print_cabecera($io_pdf);
		$li_i=1;
		while (!$io_report->rs_data->EOF)
		{
			$ls_codper=$io_report->rs_data->fields["codper"];
			$ls_nomper=$io_report->rs_data->fields["apeper"].", ".$io_report->rs_data->fields["nomper"];
			$ls_desuniadm=$io_report->rs_data->fields["desuniadm"];
			$ls_desubifis=$io_report->rs_data->fields["desubifis"];
			$ls_despai=$io_report->rs_data->fields["despai"];
			$ls_desest=$io_report->rs_data->fields["desest"];
			$ls_denmun=$io_report->rs_data->fields["denmun"];
			$ls_denpar=$io_report->rs_data->fields["denpar"];
			$la_data[$li_i]=array('codigo'=>$ls_codper,'nombre'=>$ls_nomper,'unidadadm'=>$ls_desuniadm,'desubifis'=>$ls_desubifis,
								  'despai'=>$ls_despai,'desest'=>$ls_desest,'denmun'=>$ls_denmun,'denpar'=>$ls_denpar);
			$li_i++;
			$io_report->rs_data->MoveNext();
		}
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		unset($la_data);			
		$io_report->DS->resetds("codper");
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