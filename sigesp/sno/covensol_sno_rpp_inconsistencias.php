<?php 

session_start();   
header("Pragma: public");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);

ini_set('memory_limit','2048M');
ini_set('max_execution_time','0');

function error($tipo){

		switch($tipo){				
			case 'tamaño_fila':
				$mensaje = 'ERROR DE TABLA: El tamaño de la fila es mas grande que el de la página.';
				break;		
		}
		
		 print "<script language=JavaScript>";
		 print "alert('".$mensaje."');";
		 print "close();";		
		 print "</script>";	
		 exit();

}

function columna($id,$dato,$prop,$textalign='L',$valign='T'){
	
	global $pdf,$margen_tabla,$ancho,$altura;
	// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false,opciones_adicionales)
	$pdf->MultiCell($ancho[$id], $prop['alturamaxima'], utf8_encode($dato), 1, $textalign, 1, 0, '', '', true, 0, true, false, 0, $valign,false,$prop);
	if($pdf->saltopag){return false;}
	$altura[$id]=$pdf->altura_celda;
	
	return $altura;

}

function procesar_fila($datos){
		
		global $pdf,$margen_tabla,$ancho;
		
		$altura_pagina = $pdf->getPageHeight();
		$prop['alturamaxima']=3;
		$prop['check_pagebreak']=0;
		
		$pdf->startMultipleTransaction();								
		$prop['alturamaxima'] = fila($datos,$prop);
		if($prop['alturamaxima']>$altura_pagina){error('tamaño_fila');}
		$salto = $pdf->saltopag;									
		$pdf = $pdf->rollbackMultipleTransaction();
						
		if($salto){
			$pdf->AddPage();
			encabezado();
			$pdf->startMultipleTransaction();											
			$prop['alturamaxima'] = fila($datos,$prop);													
			$pdf = $pdf->rollbackMultipleTransaction();
			$salto = 0;		
		}
		
		
		$pdf->startMultipleTransaction();
		$pagina = $pdf->getPage();
		
		fila($datos,$prop);
		
		$pagina2 = $pdf->getPage();				
		if($pagina!=$pagina2){
			
				$pdf = $pdf->rollbackMultipleTransaction();									
				$pdf->AddPage();
				encabezado();
				fila($datos,$prop);											
		}	
		else{					  
														
				$pdf->commitMultipleTransaction();
		} 

}

	
$ruta = '../../';

include("../class_folder/covensol_cor_c_sno.php");
$objsno = new covensol_cor_c_sno();
	
require_once('../../shared/tcpdf_new/config/lang/ita.php');
require_once('../../shared/tcpdf_new/tcpdf_rep.php');  
//error_reporting(E_ALL);
//set_time_limit(18000);


$parametros = array();
//$objsno->io_conexiones->decodificar_get();
$parametros = $objsno->io_conexiones->asignar_get();

$datos_encabezado = '<p  style="text-align:rigth;"><b> '.'FECHA Y HORA: </b>'.date('d/m/Y').' - '.date('H:i').'<br/><b>BD: </b>'.$_SESSION['ls_database'].'<br/><b>USUARIO: </b>'.$_SESSION['la_logusr'].'</p>';

$pdf = new TCPDFREP('LANDSCAPE', PDF_UNIT, 'LETTER', true, 'UTF-8', false);
$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
$pdf->setHeaderFont(array('', '', 8));
$pdf->SetHeaderData('../../shared/imagebank/'.$_SESSION["ls_logo"], $_SESSION["ls_width"], '', $datos_encabezado);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);		
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setPrintHeader(true);
//$pdf->setTextoFooter(utf8_encode(''));

$pdf->AddPage();


$titulo = '<b>REPORTE:</b> RELACIÓN DE INCONSISTENCIAS DE NÓMINA ';	


		
$pdf->SetFont('helvetica', '', 10);
$tit_rep = '<p  style="text-align:center;">'.$titulo.'</p>';
$pdf->writeHTML($tit_rep, true, false, false, false, '');


$pdf->SetFont('helvetica', '', 8);

$pdf->Ln();
$pdf->Ln();


$pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(200, 200, 200))); 


$ancho[1] = 20;
$ancho[2] = 100;
$ancho[3] = 100;


$ancho_total = array_sum($ancho);
$margen_tabla=1;	
$mensaje="";
$param['tamaño_letra']=6;


		
function encabezado_tit($cantidad){		
												
		global $pdf,$margen_tabla,$ancho_total;
		//$pdf->Bookmark(utf8_encode($opciones['desuniadm']), 0, 0);
		$pdf->SetFillColor(255,255,255);
		$pdf->Cell($margen_tabla, 3,"", 0,0,'C',1);
		$pdf->SetFont('helvetica', 'B', 7);
		$pdf->SetTextColor(0,0,150);
		//$pdf->SetFillColor(220, 230, 250);
		$pdf->color_fondo('#DEEBF7');
		
		//Cell( $w, $h, $txt, $border,$ln,$align, $fill,$link,$stretch,$ignore_min_height)
		$pdf->Cell($ancho_total, 3,'ERRORES EN ESTRUCTURA PRESUP. DE  LAS UNIDADES ADMINISTRATIVAS:', 1,0,'C',1);
		$pdf->Ln();
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(0);
		$pdf->SetFont('helvetica', '', 5);
}




function encabezado($opciones=array()){		
												
		global $pdf,$margen_tabla,$ancho,$evaluar;
		$pdf->SetFillColor(255,255,255);
		$pdf->Cell($margen_tabla, 3,"", 0,0,'C',1);
		$pdf->SetFont('helvetica', 'B', 7);
		$pdf->SetTextColor(255,255,255);
		$pdf->SetFillColor(0, 0, 150);
		
		$pdf->Cell($ancho[1], 3,"CODUNIADM", 1,0,'C',1);							  
		$pdf->Cell($ancho[2], 3,"DENOMINACIÓN", 1,0,'C',1);
		$pdf->Cell($ancho[3], 3,'PROGRAMÁTICA', 1,0,'C',1);
											
		$pdf->Ln();
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(0);
		$pdf->SetFont('helvetica', '', 7);
}



function fila($datos=array(),$prop=array()){		
												
		global $pdf,$margen_tabla,$ancho,$objsno;
		
		$prop['alturamaxima']=($prop['alturamaxima'])?$prop['alturamaxima']:3;
		$alturamax=0;		
		
		$pdf->SetFillColor(255,255,255);							
		$pdf->MultiCell($margen_tabla, $prop['alturamaxima'], '', 0, 'J', 1, 0, '', '', true, 0, false, false, 0, 'T');							
		$pdf->SetFont('helvetica', '', 7);		
		$pdf->color_fondo($objsno->color_row);
		$pdf->color_letra('000000');
		
		$estructura = $objsno->FormatearEstructuraPresup($datos);
										
		$altura = columna(1,$datos['coduniadm'],$prop,'C'); if($altura===false){return;}
		$altura = columna(2,$datos['desuniadm'],$prop,'L'); if($altura===false){return;}
		$altura = columna(3,'('.$datos['estcla_ua'].')'.$estructura['programatica'].' <b><font color="#990000"> - NO EXISTE LA ESTRUCTURA! </font></b>',$prop,'L'); if($altura===false){return;}
								
		$pdf->Ln();
		
		$alturamax=max($altura);
		return $alturamax;
					
}
		

$totdias=0;
$objsno->color_row = 'FFFFFF';
$result = $objsno->ConsultaErroresUnidadAdm($parametros);
encabezado_tit($result['rs']->RecordCount());
encabezado();

foreach($result['rs'] as $datos){
	 //UBICA LAS UNIDADES ADMINISTRATIVAS QUE NO TIENEN CORRELATIVO EN LA ESTRUCTURA PRESUPUESTARIA
	 if($datos['spg_ua'] and !$datos['spg_pg']){				
		 $datos['nro']=$i;
		 procesar_fila($datos);
	 }
				
}

$pdf->Ln(3);








$pdf->SetFont('helvetica', '', 8);

if($parametros['codper']){	
	$tot = '<p  style="text-align:left;"><b><font size="8" color="#000066">Total Días de Reposo:</font></b> '.$totdias.'</p>';
	$pdf->writeHTML($tot, true, false, false, false, '');
}
//$pdf->SetMargins(25, 10, PDF_MARGIN_RIGHT);
$anchox[0] = 160;
$anchox[1] = 680;
$tabla1 = '<p  style="text-align:center;"><br/><br/><br/><br/><br/>
			     	<table cellspacing="0" cellpadding="1" border="1" align="center">							
							<tr bgcolor="#DDDDDD" color="#000099" height="6"  >
								<td align="center" width="'.$anchox[0].'"><font size="6"><b> FECHA </b></font></td>
								<td align="center" width="'.$anchox[1].'"><font size="6"><b> RECIBIDO POR </b></font></td>									
							</tr>							
							<tr bgcolor="#FFFFFF" >
								<td width="'.$anchox[0].'" align="center"><br/><br/><br/>___ / ___ / ___ <br/></td>	
								<td width="'.$anchox[1].'" align="center"><br/><br/><br/><br/></td>						
							</tr>
					</table>
				</p>';




$pdf->startMultipleTransaction();
$pagina = $pdf->getPage();

$pdf->writeHTML(utf8_encode($tabla1), true, false, false, false, '');

$pagina2 = $pdf->getPage();				
if($pagina!=$pagina2){
	
		$pdf = $pdf->rollbackMultipleTransaction();									
		$pdf->AddPage();
		$pdf->writeHTML(utf8_encode($tabla1), true, false, false, false, '');										
}	
else{					  
												
		$pdf->commitMultipleTransaction();
} 


$pdf->Output('reporte_inconsistencias_nomina.pdf', 'I');























?>