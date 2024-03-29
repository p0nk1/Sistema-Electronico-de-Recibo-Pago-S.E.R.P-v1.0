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
		// Fecha Creaci�n: 16/08/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_listadopersonalcontratado.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 16/08/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,955,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=470-($li_tm/2);
		$io_pdf->addText($tm,540,16,$as_titulo); // Agregar el t�tulo
		$io_pdf->addText(912,560,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(918,553,7,date("h:i a")); // Agregar la Hora
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
		// Fecha Creaci�n: 16/08/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSety(520);
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(40,496,920,$io_pdf->getFontHeight(19));
        $io_pdf->setColor(0,0,0);
		$la_data[1]=array('codigo'=>'<b>C�digo</b>',
						  'nombre'=>'<b>Apellidos y Nombres</b>',
						  'fecha'=>'<b>Fecha Ingreso</b>',
						  'estatus'=>'<b>Estatus</b>',
						  'nomina'=>'<b>N�mina</b>',
						  'fechano'=>'<b>Fecha Ing. N�mina</b>',
						  'fecculcontr'=>'<b>Culminaci�n Contrato</b>',
						  'estatusno'=>'<b>Estatus N�mina</b>',
						  'nivel'=>'<b>Nivel Acad�mico</b>',
						  'profesion'=>'<b>Profesi�n</b>',
						  'descon'=>'<b>Componente</b>',
						  'desran'=>'<b>Rango</b>');
		$la_columna=array('codigo'=>'',
						  'nombre'=>'',
						  'fecha'=>'',
						  'estatus'=>'',
						  'nomina'=>'',
						  'fechano'=>'',
						  'fecculcontr'=>'',
						  'estatusno'=>'',
						  'nivel'=>'',
						  'profesion'=>'',
						  'descon'=>'',
						  'desran'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla				         
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>55), // Justificaci�n y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>135), // Justificaci�n y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>55), // Justificaci�n y ancho de la columna
						 			   'estatus'=>array('justification'=>'center','width'=>50), // Justificaci�n y ancho de la columna
						 			   'nomina'=>array('justification'=>'center','width'=>120), // Justificaci�n y ancho de la columna
						 			   'fechano'=>array('justification'=>'center','width'=>55), // Justificaci�n y ancho de la columna
						 			   'fecculcontr'=>array('justification'=>'center','width'=>60), // Justificaci�n y ancho de la columna
						 			   'estatusno'=>array('justification'=>'center','width'=>50), // Justificaci�n y ancho de la columna
						 			   'nivel'=>array('justification'=>'center','width'=>60), // Justificaci�n y ancho de la columna
						 			   'profesion'=>array('justification'=>'center','width'=>100),
									   'descon'=>array('justification'=>'center','width'=>90),
									   'desran'=>array('justification'=>'center','width'=>90))); // Justificaci�n y ancho de la columna
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
		// Fecha Creaci�n: 16/08/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('codigo'=>'<b>C�digo</b>',
						  'nombre'=>'<b>Apellidos y Nombres</b>',
						  'fecha'=>'<b>Fecha Ingreso</b>',
						  'estatus'=>'<b>Estatus</b>',
						  'nomina'=>'<b>N�mina</b>',
						  'fechano'=>'<b>Fecha Ing. N�mina</b>',
						  'fecculcontr'=>'<b>Culminaci�n Contrato</b>',
						  'estatusno'=>'<b>Estatus N�mina</b>',
						  'nivel'=>'<b>Nivel Acad�mico</b>',
						  'profesion'=>'<b>Profesi�n</b>',
						  'descon'=>'<b>Componente</b>',
						  'desran'=>'<b>Rango</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla				         
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>55), // Justificaci�n y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>135), // Justificaci�n y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>55), // Justificaci�n y ancho de la columna
						 			   'estatus'=>array('justification'=>'center','width'=>50), // Justificaci�n y ancho de la columna
						 			   'nomina'=>array('justification'=>'left','width'=>120), // Justificaci�n y ancho de la columna
						 			   'fechano'=>array('justification'=>'center','width'=>55), // Justificaci�n y ancho de la columna
						 			   'fecculcontr'=>array('justification'=>'center','width'=>60), // Justificaci�n y ancho de la columna
						 			   'estatusno'=>array('justification'=>'center','width'=>50), // Justificaci�n y ancho de la columna
						 			   'nivel'=>array('justification'=>'center','width'=>60), // Justificaci�n y ancho de la columna
						 			   'profesion'=>array('justification'=>'left','width'=>100),
									   'descon'=>array('justification'=>'center','width'=>90),
									   'desran'=>array('justification'=>'center','width'=>90))); // Justificaci�n y ancho de la columna
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
	$ls_titulo="<b>Listado de Personal Contratado</b>";
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
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
	$ld_fecculcontrdes=$io_fun_nomina->uf_obtenervalor_get("fecculcontrdes","");
	$ld_fecculcontrhas=$io_fun_nomina->uf_obtenervalor_get("fecculcontrhas","");
	$ld_fecculcontrdes=$io_funciones->uf_convertirdatetobd($ld_fecculcontrdes);
	$ld_fecculcontrhas=$io_funciones->uf_convertirdatetobd($ld_fecculcontrhas);
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_listadopersonalcontratado_personal($ls_codnomdes,$ls_codnomhas,$ls_codperdes,$ls_codperhas,
																	 $ls_activo,$ls_egresado,$ls_causaegreso,$ls_activono,
																	 $ls_vacacionesno,$ls_suspendidono,$ls_egresadono,$ls_masculino,
																	 $ls_femenino,$ld_fecculcontrdes,$ld_fecculcontrhas,$ls_orden); // Obtenemos el detalle del reporte
	}
	if($lb_valido==false) // Existe alg�n error � no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		//print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		//error_reporting(E_ALL);
		//set_time_limit(1800);
		/*$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(4,2.5,3,3); // Configuraci�n de los margenes en cent�metros*/
		//----------------------------------------------------------------------------------------------------
		$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.7,2.5,3,3); // Configuraci�n de los margenes en cent�metros
		//---------------------------------------------------------------------------------------------------
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la p�gina
		$io_pdf->ezStartPageNumbers(950,50,10,'','',1); // Insertar el n�mero de p�gina
		$li_totrow=$io_report->DS->getRowCount("codper");
		uf_print_cabecera($io_pdf);
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_codper=$io_report->DS->data["codper"][$li_i];
			$ls_nomper=$io_report->DS->data["apeper"][$li_i].", ".$io_report->DS->data["nomper"][$li_i];
			$ld_fecingper=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecingper"][$li_i]);
			$ld_fecculcontr=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecculcontr"][$li_i]);
			$ls_nivacaper=$io_report->DS->data["nivacaper"][$li_i];
			$ls_estper=$io_report->DS->data["estper"][$li_i];
			$ls_estnom=$io_report->DS->data["estnom"][$li_i];
			$ls_despro=$io_report->DS->data["despro"][$li_i];
			$ls_nomina=$io_report->DS->data["desnom"][$li_i];
			$ld_fechano=$io_report->DS->data["fecingnom"][$li_i];
			
			//---------------------------------------------------------------
			$ld_codcom=$io_report->DS->data["codcomponente"][$li_i];
			$ld_codran=$io_report->DS->data["codrango"][$li_i];
			$ld_descom=$io_report->DS->data["descomponente"][$li_i];
			$ld_desran=$io_report->DS->data["desrango"][$li_i];
			
			if (($ld_codcom=="null") || ($ld_codcom==""))
			{
			   $ld_codcon="----------";
			   $ld_descom="indefinido";
			}
			
			if (($ld_codran=="null") || ($ld_codran==""))
			{
			   $ld_codran="----------";
			   $ld_desran="indefinido";
			}
			//-------------------------------------------------------------
			
			if($ld_fechano!="---")
			{
				$ld_fechano=$io_funciones->uf_convertirfecmostrar($ld_fechano);
			}
			switch ($ls_estper)
			{
				case "0":
					$ls_estper="Pre-Ingreso";
					break;
				case "1":
					$ls_estper="Activo";
					break;
				case "2":
					$ls_estper="N/A";
					break;
				case "3":
					$ls_estper="Egresado";
					break;
			}
			switch ($ls_estnom)
			{
				case "0":
					$ls_estnom="N/A";
					break;
				case "1":
					$ls_estnom="Activo";
					break;
				case "2":
					$ls_estnom="Vacaciones";
					break;
				case "3":
					$ls_estnom="Egresado";
					break;
				case "4":
					$ls_estnom="Suspendido";
					break;
			}
			switch ($ls_nivacaper)
			{
				case "0":
					$ls_nivacaper="Ninguno";
					break;
				case "1":
					$ls_nivacaper="Primaria";
					break;
				case "2":
					$ls_nivacaper="Bachiller";
					break;
				case "3":
					$ls_nivacaper="T�cnico Superior";
					break;
				case "4":
					$ls_nivacaper="Universitario";
					break;
				case "5":
					$ls_nivacaper="Maestria";
					break;
				case "6":
					$ls_nivacaper="PostGrado";
					break;
				case "7":
					$ls_nivacaper="Doctorado";
					break;
			}
			$la_data[$li_i]=array('codigo'=>$ls_codper,'nombre'=>$ls_nomper,'fecha'=>$ld_fecingper,'estatus'=>$ls_estper,
								  'nomina'=>$ls_nomina,'fechano'=>$ld_fechano,'fecculcontr'=>$ld_fecculcontr,
								  'estatusno'=>$ls_estnom,'nivel'=>$ls_nivacaper,'profesion'=>$ls_despro,
								  'codcom'=>$ld_codcom,'codrang'=>$ld_codran,
								  'descon'=>$ld_descom,'desran'=>$ld_desran);
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