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

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//    Description: funci�n que guarda la seguridad de quien gener� el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 03/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_prestacionantiguedad.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_desnom,$as_anocurper,$as_desmesperdes,$as_desmesperhas,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_desnom // Descripci�n de la n�mina
		//	    		   as_anocurper // A�o en curso
		//	    		   as_desmesper // Mes en curso
		//	    		   io_pdf // Instancia de objetso pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 03/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(trim($as_desmesperdes)==trim($as_desmesperhas))
		{
			$ls_mes=" Mes: ".$as_desmesperdes;
		}
		else
		{
			$ls_mes=" Meses: ".$as_desmesperdes." - ".$as_desmesperhas;
		}
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el t�tulo
		$ls_periodo="A�o: ".$as_anocurper." ".$ls_mes;
		$li_tm=$io_pdf->getTextWidth(11,$ls_periodo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,720,11,$ls_periodo); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(11,$as_desnom);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,710,11,$as_desnom); // Agregar el t�tulo
		$io_pdf->addText(512,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(518,743,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera(&$io_pdf,$as_sueint)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 27/07/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($as_sueint=="")
		{
			$as_sueint="Sueldo";
		}
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSety(700);
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(24,682,555,$io_pdf->getFontHeight(15));
        $io_pdf->setColor(0,0,0);
		$la_data[1]=array('periodo'=>'<b>Periodo</b>',
						  'cedula'=>'<b>C�dula</b>',
						  'nombre'=>'<b>Apellidos y Nombres</b>',
						  'fechaing'=>'<b>Fecha de Ingreso</b>',
						  'sueldointegral'=>'<b>'.$as_sueint.'</b>',
						  'bonovacacional'=>'<b>Vacacional</b>',
						  'bonofin'=>'<b>Fin A�o</b>',
						  'diaadi'=>'<b>Dia Adicional</b>',
						  'prisoc'=>'<b>Prima Social</b>',
						  'aporte'=>'<b>Monto a Depositar</b>');
		$la_columna=array('periodo'=>'',
						  'cedula'=>'',
						  'nombre'=>'',
						  'fechaing'=>'',
						  'sueldointegral'=>'',
						  'bonovacacional'=>'',
						  'bonofin'=>'',
						  'diaadi'=>'',
						  'prisoc'=>'',
						  'aporte'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('periodo'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>160), // Justificaci�n y ancho de la columna
						 			   'fechaing'=>array('justification'=>'center','width'=>50), // Justificaci�n y ancho de la columna
									   'sueldointegral'=>array('justification'=>'center','width'=>50), // Justificaci�n y ancho de la columna
						 			   'bonovacacional'=>array('justification'=>'center','width'=>45), // Justificaci�n y ancho de la columna
						 			   'bonofin'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna
									   'diaadi'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna
						 			   'prisoc'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna
									   'aporte'=>array('justification'=>'center','width'=>50))); // Justificaci�n y ancho de la columna
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
		// Fecha Creaci�n: 06/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('periodo'=>'',
						  'cedula'=>'',
						  'nombre'=>'',
						  'fechaing'=>'',
						  'sueldointegral'=>'',
						  'bonovacacional'=>'',
						  'bonofin'=>'',
						  'diaadi'=>'',
						  'prisoc'=>'',
						  'aporte'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('periodo'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>160), // Justificaci�n y ancho de la columna
									   'fechaing'=>array('justification'=>'center','width'=>50), // Justificaci�n y ancho de la columna
						 			   'sueldointegral'=>array('justification'=>'center','width'=>50), // Justificaci�n y ancho de la columna
						 			   'bonovacacional'=>array('justification'=>'center','width'=>45), // Justificaci�n y ancho de la columna
						 			   'bonofin'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna
									   'diaadi'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna
						 			   'prisoc'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna
									   'aporte'=>array('justification'=>'center','width'=>50))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 06/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('total'=>'',
						  'sueldointegral'=>'',
						  'bonovacacional'=>'',
						  'bonofin'=>'',
						  'diaadi'=>'',
						  'prisoc'=>'',
						  'aporte'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>290), // Justificaci�n y ancho de la columna
						 			   'sueldointegral'=>array('justification'=>'right','width'=>50), // Justificaci�n y ancho de la columna
						 			   'bonovacacional'=>array('justification'=>'right','width'=>45), // Justificaci�n y ancho de la columna
						 			   'bonofin'=>array('justification'=>'right','width'=>40), // Justificaci�n y ancho de la columna
						 			   'diaadi'=>array('justification'=>'right','width'=>40), // Justificaci�n y ancho de la columna
									   'prisoc'=>array('justification'=>'right','width'=>40), // Justificaci�n y ancho de la columna
									   'aporte'=>array('justification'=>'right','width'=>50))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_totales
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
	$ls_titulo="<b>Listado de Prestaci�n de Antiguedad</b>";
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_desnomdes=$io_fun_nomina->uf_obtenervalor_get("desnomdes","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	$ls_desnomhas=$io_fun_nomina->uf_obtenervalor_get("desnomhas","");
	$ls_anocurperdes=$io_fun_nomina->uf_obtenervalor_get("anocurperdes","");
	$ls_mescurperdes=$io_fun_nomina->uf_obtenervalor_get("mescurperdes","");
	$ls_desmesperdes=$io_fun_nomina->uf_obtenervalor_get("desmesperdes","");
	$ls_anocurperhas=$io_fun_nomina->uf_obtenervalor_get("anocurperhas","");
	$ls_mescurperhas=$io_fun_nomina->uf_obtenervalor_get("mescurperhas","");
	$ls_desmesperhas=$io_fun_nomina->uf_obtenervalor_get("desmesperhas","");
	$ls_sueint=$io_fun_nomina->uf_obtenervalor_get("sueint","");
	$ls_tiporeporte=0;
	$ls_perides=$io_fun_nomina->uf_obtenervalor_get("perides","");
	$ls_perihas=$io_fun_nomina->uf_obtenervalor_get("perihas","");
	if ($ls_codnomdes==$ls_codnomhas)
	{
		$ls_desnom=$ls_desnomdes;
	}
	else
	{
		$ls_desnom=$ls_desnomdes." - ".$ls_desnomhas;
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_prestacionantiguedad_personal($ls_codnomdes,$ls_codnomhas,$ls_anocurperdes,$ls_mescurperdes,$ls_anocurperhas,$ls_mescurperhas); // Obtenemos el detalle del reporte
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
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.9,2.5,3,3); // Configuraci�n de los margenes en cent�metros
		uf_print_encabezado_pagina($ls_titulo,$ls_desnom,$ls_anocurperdes,$ls_desmesperdes,$ls_desmesperhas,$io_pdf); // Imprimimos el encabezado de la p�gina
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el n�mero de p�gina
		$li_totalsueintper=0;
		$li_totalbonvacper=0;
		$li_totalbonfinper=0;
		$li_totalapoper=0;
		$li_totrow=$io_report->DS->getRowCount("cedper");
		uf_print_cabecera($io_pdf,$ls_sueint);
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$li_numper=str_pad($li_i,2,"0",0);
			$ls_cedper=$io_report->DS->data["cedper"][$li_i];
			$ls_codper=$io_report->DS->data["codper"][$li_i];
			$ls_nomper=$io_report->DS->data["apeper"][$li_i].", ".$io_report->DS->data["nomper"][$li_i];
			$ls_periodo=str_pad($io_report->DS->data["mescurper"][$li_i],2,"0",0)." - ".$io_report->DS->data["anocurper"][$li_i];
			$ld_fecingper=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecingper"][$li_i]);
			$ls_diaadi=$io_report->DS->data["diaadi"][$li_i];
			$ls_valconcprimasoc=$io_report->uf_buscar_conceptos_personal_fideicomiso($ls_codper,$ls_codnomdes,$ls_anocurperdes,$ls_perides,$ls_perihas);
			$ls_valconcprimasoc=$io_fun_nomina->uf_formatonumerico($ls_valconcprimasoc);
			$li_totalsueintper=$li_totalsueintper+$io_report->DS->data["sueintper"][$li_i];
			$li_totalbonvacper=$li_totalbonvacper+$io_report->DS->data["bonvacper"][$li_i];
			$li_totalbonfinper=$li_totalbonfinper+$io_report->DS->data["bonfinper"][$li_i];
			$li_totalapoper=$li_totalapoper+$io_report->DS->data["apoper"][$li_i];
			$li_sueintper=$io_fun_nomina->uf_formatonumerico($io_report->DS->data["sueintper"][$li_i]);
			$li_bonvacper=$io_fun_nomina->uf_formatonumerico($io_report->DS->data["bonvacper"][$li_i]);
			$li_bonfinper=$io_fun_nomina->uf_formatonumerico($io_report->DS->data["bonfinper"][$li_i]);
			$li_apoper=$io_fun_nomina->uf_formatonumerico($io_report->DS->data["apoper"][$li_i]);
			$la_data[$li_i]=array('periodo'=>$ls_periodo,'cedula'=>$ls_cedper,'nombre'=>$ls_nomper,'sueldointegral'=>$li_sueintper,
							'bonovacacional'=>$li_bonvacper,'bonofin'=>$li_bonfinper,'aporte'=>$li_apoper,'fechaing'=>$ld_fecingper,
							'diaadi'=>$ls_diaadi,'prisoc'=>$ls_valconcprimasoc);
		}
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		unset($la_data);
		$li_totalsueintper=$io_fun_nomina->uf_formatonumerico($li_totalsueintper);
		$li_totalbonvacper=$io_fun_nomina->uf_formatonumerico($li_totalbonvacper);
		$li_totalbonfinper=$io_fun_nomina->uf_formatonumerico($li_totalbonfinper);
		$li_totalapoper=$io_fun_nomina->uf_formatonumerico($li_totalapoper);
		if($ls_tiporeporte==1)
		{
			$ls_titulobs="Bs.F.";
		}
		else
		{
			$ls_titulobs="Bs.";
		}
		$la_data[1]=array('total'=>'<b>Total '.$ls_titulobs.'</b>','sueldointegral'=>$li_totalsueintper,'bonovacacional'=>$li_totalbonvacper,
						  'bonofin'=>$li_totalbonfinper,'aporte'=>$li_totalapoper,'diaadi'=>'','prisoc'=>'');
		uf_print_totales($la_data,$io_pdf); // Imprimimos el detalle 
		unset($la_data);
		$io_report->DS->resetds("cedper");
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