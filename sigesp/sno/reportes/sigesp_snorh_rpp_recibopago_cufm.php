<?PHP
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
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Arreglo de las variables de seguridad
		//	    		   as_desnom // Arreglo de las variables de seguridad
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/09/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_descripcion="Generó el Reporte Consolidado ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_recibopago.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera1($as_cedper,$as_nomper,$as_descar,$ls_fechacob,&$io_cabecera,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera1
		//		   Access: private 
		//	    Arguments: as_cedper // Cédula del personal
		//	    		   as_nomper // Nombre del personal
		//	    		   as_descar // Decripción del cargo
		//	    		   io_cabecera // objeto cabecera
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0,0);
		$ls_fecha=date("d/m/Y");
		$io_pdf->addText(40,430,'6','COLEGIO UNIVERSITARIO FRANCISCO DE MIRANDA SERIAL: 9902SN3249');
		$io_pdf->ezSetY(750);
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('nombre'=>'', 'cedula'=>'', 'cargo'=>'Fecha:  '.$ls_fecha, 'fechapag' => '' ),
					   array('nombre'=>'', 'cedula'=>'', 'cargo'=>'', 'fechapag' => '' ),
					   array('nombre'=>'', 'cedula'=>'', 'cargo'=>'', 'fechapag' => '' ));
		$la_columna=array('nombre'=>'','cedula'=>'','cargo'=>'','fechapag' =>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xPos'=>350, // Orientación de la tabla
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'cargo'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
									   'fechapag'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('nombre'=>$as_nomper, 'cedula'=>$as_cedper, 'cargo'=>substr($as_descar,0,100),'fechapag' => $ls_fechacob));
		$la_columna=array('nombre'=>'','cedula'=>'','cargo'=>'','fechapag'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>330, // Orientación de la tabla
						 'rowGap'=>0.5,
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>238), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>115), // Justificación y ancho de la columna
						 			   'cargo'=>array('justification'=>'center','width'=>112), // Justificación y ancho de la columna
									   'fechapag'=>array('justification'=>'center','width'=>112))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$io_pdf->ezSetY(675);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera2($as_cedper,$as_nomper,$as_descar,$ls_fechacob,&$io_cabecera,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_cedper // Cédula del personal
		//	    		   as_nomper // Nombre del personal
		//	    		   as_descar // Decripción del cargo
		//	    		   io_cabecera // objeto cabecera
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0,0);
		$ls_fecha=date("d/m/Y");
		$io_pdf->addText(40,20,'6','COLEGIO UNIVERSITARIO FRANCISCO DE MIRANDA SERIAL: 9902SN3249');
		$io_pdf->ezSetY(338);
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('nombre'=>'', 'cedula'=>'', 'cargo'=>'Fecha:  '.$ls_fecha, 'fechapag' => '' ),
					   array('nombre'=>'', 'cedula'=>'', 'cargo'=>'', 'fechapag' => '' ),
					   array('nombre'=>'', 'cedula'=>'', 'cargo'=>'', 'fechapag' => '' ));
		$la_columna=array('nombre'=>'','cedula'=>'','cargo'=>'','fechapag' =>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xPos'=>350, // Orientación de la tabla
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'cargo'=>array('justification'=>'left','width'=>100), // Justificación y ancho de la columna
									   'fechapag'=>array('justification'=>'left','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('nombre'=>$as_nomper, 'cedula'=>$as_cedper, 'cargo'=>substr($as_descar,0,100),'fechapag' => $ls_fechacob));
		$la_columna=array('nombre'=>'','cedula'=>'','cargo'=>'','fechapag'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>330, // Orientación de la tabla
						 'rowGap'=>0.5,
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>238), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>115), // Justificación y ancho de la columna
						 			   'cargo'=>array('justification'=>'center','width'=>112), // Justificación y ancho de la columna
									   'fechapag'=>array('justification'=>'center','width'=>112))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$io_pdf->ezSetY(263);
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
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('denominacion'=>'','asignacion'=>'','deduccion'=>'','blanco'=>'' );
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>330, // Orientación de la tabla
						 'cols'=>array('denominacion'=>array('justification'=>'left','width'=>238), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>115), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>112), // Justificación y ancho de la columna
									   'blanco'=>array('justification'=>'right','width'=>112))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
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
		//    Description: función que imprime el fin de la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares, $io_monedabsf, $ls_tiporeporte;
		
		$io_piepagina=$io_pdf->openObject(); // Creamos el objeto pie de página
		$io_pdf->saveState();
		$io_pdf->ezSety(430);
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('denomasig'=>'', 'valorasig'=>$ai_toting, 'denomdedu'=>$ai_totded,'valordedu'=>$ai_totnet));
		$la_columna=array('denomasig'=>'<b>DENOMINACIÓN</b>',
						  'valorasig'=>'<b>ASIGNACIÓN</b>',
						  'denomdedu'=>'<b>DENOMINACIÓN</b>',
						  'valordedu'=>'<b>DEDUCCIÓN</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>330, // Orientación de la tabla
						 'cols'=>array('denomasig'=>array('justification'=>'center','width'=>238), // Justificación y ancho de la columna
						 			   'valorasig'=>array('justification'=>'right','width'=>115), // Justificación y ancho de la columna
						 			   'denomdedu'=>array('justification'=>'right','width'=>112), // Justificación y ancho de la columna
						 			   'valordedu'=>array('justification'=>'right','width'=>112))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_piepagina,'all');
		$io_pdf->stopObject($io_piepagina); // Detener el objeto pie de página
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
		//    Description: función que imprime el fin de la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares, $io_monedabsf, $ls_tiporeporte;
		
		$io_piepagina=$io_pdf->openObject(); // Creamos el objeto pie de página
		$io_pdf->saveState();
		$io_pdf->ezSety(20);
		$la_data=array(array('denomasig'=>'', 'valorasig'=>$ai_toting, 'denomdedu'=>$ai_totded,'valordedu'=>$ai_totnet));
		$la_columna=array('denomasig'=>'<b>DENOMINACIÓN</b>',
						  'valorasig'=>'<b>ASIGNACIÓN</b>',
						  'denomdedu'=>'<b>DENOMINACIÓN</b>',
						  'valordedu'=>'<b>DEDUCCIÓN</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>330, // Orientación de la tabla
						 'cols'=>array('denomasig'=>array('justification'=>'center','width'=>238), // Justificación y ancho de la columna
						 			   'valorasig'=>array('justification'=>'right','width'=>115), // Justificación y ancho de la columna
						 			   'denomdedu'=>array('justification'=>'right','width'=>112), // Justificación y ancho de la columna
						 			   'valordedu'=>array('justification'=>'right','width'=>112))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_piepagina,'all');
		$io_pdf->stopObject($io_piepagina); // Detener el objeto pie de página
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	$ls_tiporeporte="0";
	$ls_bolivares="";
	if (array_key_exists("tiporeporte",$_GET))
	{
		$ls_tiporeporte=$_GET["tiporeporte"];
	}
	switch($ls_tiporeporte)
	{
		case "0":
			require_once("sigesp_snorh_class_report.php");
			$io_report=new sigesp_snorh_class_report();
			$ls_bolivares ="Bs.";
			break;

		case "1":
			require_once("sigesp_snorh_class_reportbsf.php");
			$io_report=new sigesp_snorh_class_reportbsf();
			$ls_bolivares ="Bs.F.";
			break;
	}
	require_once("../../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
	$io_monedabsf=new sigesp_c_reconvertir_monedabsf();				
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_descripcion="";
	$ls_tipnom=$io_fun_nomina->uf_obtenervalor_get("tipnom","");
	$ls_codnom=$io_fun_nomina->uf_obtenervalor_get("codnom","");
	$ls_desnom="<b>".$io_fun_nomina->uf_obtenervalor_get("desnom","")."</b>";
	$ls_codperides=$io_fun_nomina->uf_obtenervalor_get("codperides","");
	$ls_codperihas=$io_fun_nomina->uf_obtenervalor_get("codperihas","");
	$ld_fecdesper=$io_fun_nomina->uf_obtenervalor_get("fecdesper","");
	$ld_fechasper=$io_fun_nomina->uf_obtenervalor_get("fechasper","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_coduniadm=$io_fun_nomina->uf_obtenervalor_get("coduniadm","");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_conceptop2=$io_fun_nomina->uf_obtenervalor_get("conceptop2","");
	$ls_conceptoreporte=$io_fun_nomina->uf_obtenervalor_get("conceptoreporte","");
	$ls_tituloconcepto=$io_fun_nomina->uf_obtenervalor_get("tituloconcepto","");
	$ls_quincena=$io_fun_nomina->uf_obtenervalor_get("quincena","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("codsubnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("codsubnomhas","");
	$ls_consolidar=$io_fun_nomina->uf_obtenervalor_get("consolidar","0");
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>COMPROBANTE DE PAGO</b>";
	$ls_periodo="";
	if($ls_consolidar=="1")
	{
		$ls_periodo="Periodos: <b>".$ls_codperides." - ".$ls_codperihas."</b> del <b>".$ld_fecdesper."</b> al <b>".$ld_fechasper."</b>";
	}
	$ls_quincena=3;
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_recibopago_personal($ls_codnom,$ld_fecdesper,$ld_fechasper,$ls_codperdes,$ls_codperhas,$ls_coduniadm,
													  $ls_conceptocero,$ls_conceptop2,$ls_conceptoreporte,$ls_subnomdes,$ls_subnomhas,
													  $ls_consolidar,$ls_orden); // Cargar el DS con los datos de la cabecera del reporte*/
	}
	if($lb_valido==false) // Existe algún error ó no hay registros
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
		$io_pdf->ezSetCmMargins(1,0.2,0.2,1); // Configuración de los margenes en centímetros
		$li_totrow=$io_report->rs_data->RecordCount();
		$li_reg=1;
		$li_i=1;
		while((!$io_report->rs_data->EOF)&&($lb_valido))
		{
			$li_toting=0;
			$li_totded=0;
			$ls_codper=$io_report->rs_data->fields["codper"];
			$ls_cedper=$io_report->rs_data->fields["cedper"];
			$ls_nomper=$io_report->rs_data->fields["apeper"].", ".$io_report->rs_data->fields["nomper"];
			$ls_descar=$io_report->rs_data->fields["descar"];
			$ls_codcueban=$io_report->rs_data->fields["codcueban"];
			$li_total=$io_report->rs_data->fields["total"];
			$li_adelanto=$io_report->rs_data->fields["adenom"];
			$li_racnom=$io_report->rs_data->fields["racnom"];
			if($li_racnom==1)
			{
				$ls_descar=$io_report->rs_data->fields["denasicar"];
			}
			$io_cabecera=$io_pdf->openObject(); // Creamos el objeto cabecera
			if($li_reg==1)
			{
				uf_print_cabecera1($ls_cedper,$ls_nomper,$ls_descar,$ld_fechasper,&$io_cabecera,&$io_pdf); // Imprimimos la cabecera del registro
			}
			else
			{
				uf_print_cabecera2($ls_cedper,$ls_nomper,$ls_descar,$ld_fechasper,&$io_cabecera,&$io_pdf); // Imprimimos la cabecera del registro
			}
			$ls_codperi="";
			if($ls_consolidar=="0")
			{
				$ls_codperi=$io_report->rs_data->fields["codperi"];
				$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($io_report->rs_data->fields["fecdesper"]);
				$ld_fechasper=$io_funciones->uf_convertirfecmostrar($io_report->rs_data->fields["fechasper"]);
				$ls_periodo="Periodo: <b>".$ls_codperi."</b> del <b>".$ld_fecdesper."</b> al <b>".$ld_fechasper."</b>";
			}
			$lb_valido=$io_report->uf_recibopago_conceptopersonal($ls_codnom,$ld_fecdesper,$ld_fechasper,$ls_codper,
																  $ls_conceptocero,$ls_conceptop2,$ls_conceptoreporte,
																  $ls_tituloconcepto,$ls_codperi); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->DS_detalle->getRowCount("codconc");
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
							$la_data_a[$li_asig]=array('denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
							break;
							
						case "2": // segunda quincena;
							for($li_s=1;$li_s<=$li_totrow_det;$li_s++) 
							{
								$ls_tipsal=rtrim($io_report->DS_detalle->data["tipsal"][$li_s]);
								if(($ls_tipsal=="A") || ($ls_tipsal=="V1") || ($ls_tipsal=="V2") || ($ls_tipsal=="R")) // Buscamos las asignaciones
								{
									$li_asig=$li_asig+1;
									$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_s];
									$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_s];
									$li_toting=$li_toting+abs($io_report->DS_detalle->data["valsal"][$li_s]);
									$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valsal"][$li_s]));
									$la_data_a[$li_asig]=array('denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
								}
								else // Buscamos las deducciones y aportes
								{
									$li_dedu=$li_dedu+1;
									$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_s];
									$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_s];
									$li_totded=$li_totded+abs($io_report->DS_detalle->data["valsal"][$li_s]);
									$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valsal"][$li_s]));
									$la_data_d[$li_dedu]=array('denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
								}
							}
							$li_dedu=$li_dedu+1;
							$ls_codconc="----------";
							$ls_nomcon="ADELANTO 1ra QUINCENA";
							$li_valsal=round($li_total/2,2);
							$li_totded=$li_totded+$li_valsal;
							$li_valsal=$io_fun_nomina->uf_formatonumerico($li_valsal);
							$la_data_d[$li_dedu]=array('denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
							break;
							
						case "3": // Mes Completo;
							for($li_s=1;$li_s<=$li_totrow_det;$li_s++) 
							{
								$ls_tipsal=rtrim($io_report->DS_detalle->data["tipsal"][$li_s]);
								if(($ls_tipsal=="A") || ($ls_tipsal=="V1") || ($ls_tipsal=="V2") || ($ls_tipsal=="R")) // Buscamos las asignaciones
								{
									$li_asig=$li_asig+1;
									$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_s];
									$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_s];
									$li_toting=$li_toting+abs($io_report->DS_detalle->data["valsal"][$li_s]);
									$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valsal"][$li_s]));
									$la_data_a[$li_asig]=array('denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
								}
								else // Buscamos las deducciones y aportes
								{
									$li_dedu=$li_dedu+1;
									$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_s];
									$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_s];
									$li_totded=$li_totded+abs($io_report->DS_detalle->data["valsal"][$li_s]);
									$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valsal"][$li_s]));
									$la_data_d[$li_dedu]=array('denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
								}
							}
							break;
					}
				}
				else// No utiliza adelanto de quincena
				{
					for($li_s=1;$li_s<=$li_totrow_det;$li_s++) 
					{
						$ls_tipsal=rtrim($io_report->DS_detalle->data["tipsal"][$li_s]);
						if(($ls_tipsal=="A") || ($ls_tipsal=="V1") || ($ls_tipsal=="V2") || ($ls_tipsal=="R")) // Buscamos las asignaciones
						{
							$li_asig=$li_asig+1;
							$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_s];
							$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_s];
							$li_toting=$li_toting+abs($io_report->DS_detalle->data["valsal"][$li_s]);
							$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valsal"][$li_s]));
							$la_data_a[$li_asig]=array('denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
						}
						else // Buscamos las deducciones y aportes
						{
							$li_dedu=$li_dedu+1;
							$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_s];
							$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_s];
							$li_totded=$li_totded+abs($io_report->DS_detalle->data["valsal"][$li_s]);
							$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valsal"][$li_s]));
							$la_data_d[$li_dedu]=array('denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
						}
					}
				}
				$li_count=0;
				$li_total=20;
				$li_iniasig=21;	
				if ($li_asig<=$li_total)
				{
					$li_total=$li_asig;
					$li_iniasig=$li_asig+1;	
				}
				for($li_s=1;$li_s<=$li_total;$li_s++) 
				{
					$li_count++;
					$la_data[$li_count]=array('denominacion'=>$la_data_a[$li_s]["denominacion"],'asignacion'=>$la_data_a[$li_s]["valor"], 'deduccion'=>'', 'blanco'=>'');
				}
				$li_total=20-$li_total;
				$li_inideduc=$li_total+1;
				if ($li_dedu<=$li_total)
				{
					$li_total=$li_dedu;
					$li_inideduc=$li_dedu+1;
				}
				for($li_s=1;$li_s<=$li_total;$li_s++) 
				{
					$li_count++;
					$la_data[$li_count]=array('denominacion'=>$la_data_d[$li_s]["denominacion"],'asignacion'=>'', 'deduccion'=>$la_data_d[$li_s]["valor"], 'blanco'=>'');
				}
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				$li_totnet=$li_toting-$li_totded;
				$li_toting=$io_fun_nomina->uf_formatonumerico($li_toting);
				$li_totded=$io_fun_nomina->uf_formatonumerico($li_totded);
				$li_totnet=$io_fun_nomina->uf_formatonumerico($li_totnet);
				if($li_reg==1)
				{
					uf_print_pie_cabecera1($li_toting,$li_totded,$li_totnet,$ls_codcueban,$io_pdf); // Imprimimos pie de la cabecera
				}
				else
				{
					uf_print_pie_cabecera2($li_toting,$li_totded,$li_totnet,$ls_codcueban,$io_pdf); // Imprimimos pie de la cabecera
				}				
				unset($la_data);
				$io_pdf->stopObject($io_cabecera); // Detener el objeto cabecera
				if(($li_i<$li_totrow)&&($li_reg==2))
				{
					$io_pdf->ezNewPage(); // Insertar una nueva página
					$li_reg=1;
				}
				else
				{
					$li_reg=2;
				}
				if(($li_asig+$li_dedu)>20)
				{
					$io_cabecera=$io_pdf->openObject(); // Creamos el objeto cabecera
					if($li_reg==1)
					{
						uf_print_cabecera1($ls_cedper,$ls_nomper,$ls_codper,$ld_fechasper,$io_cabecera,$io_pdf); // Imprimimos la cabecera del registro
					}
					else
					{
						uf_print_cabecera2($ls_cedper,$ls_nomper,$ls_codper,$ld_fechasper,$io_cabecera,$io_pdf); // Imprimimos la cabecera del registro
					}
					$li_count=0;
					for($li_s=$li_iniasig;$li_s<=$li_asig;$li_s++) 
					{
						$li_count++;
						$la_data[$li_count]=array('denominacion'=>$la_data_a[$li_s]["denominacion"],'asignacion'=>$la_data_a[$li_s]["valor"], 'deduccion'=>'', 'blanco'=>'');
					}
					for($li_s=$li_inideduc;$li_s<=$li_dedu;$li_s++) 
					{
						$li_count++;
						$la_data[$li_count]=array('denominacion'=>$la_data_d[$li_s]["denominacion"],'asignacion'=>'', 'deduccion'=>$la_data_d[$li_s]["valor"], 'blanco'=>'');
					}
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					if($li_reg==1)
					{
						uf_print_pie_cabecera1($li_toting,$li_totded,$li_totnet,$ls_codcueban,$io_pdf); // Imprimimos pie de la cabecera
					}
					else
					{
						uf_print_pie_cabecera2($li_toting,$li_totded,$li_totnet,$ls_codcueban,$io_pdf); // Imprimimos pie de la cabecera
					}				
					unset($la_data);
					$io_pdf->stopObject($io_cabecera); // Detener el objeto cabecera
					if(($li_i<$li_totrow)&&($li_reg==2))
					{
						$io_pdf->ezNewPage(); // Insertar una nueva página
						$li_reg=1;
					}
					else
					{
						$li_reg=2;
					}
				}
				unset($la_data_a);
				unset($la_data_d);
			}
			$li_i++;
			$io_report->rs_data->MoveNext();
		}
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algún error
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