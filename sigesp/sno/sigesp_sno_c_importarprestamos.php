<?php
class sigesp_sno_c_importarprestamos
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_prestamo;
	var $io_personalnomina;
	var $io_sno;
	var $ls_codemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_importarprestamos()
	{		
	    
		global $ruta;
							
		if(!$ruta){$this->ruta = '../';}							
		else{$this->ruta = $ruta;}
		
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
		require_once("sigesp_sno_c_prestamo.php");
		$this->io_prestamo= new sigesp_sno_c_prestamo();
		require_once("sigesp_sno_c_personalnomina.php");
		$this->io_personalnomina= new sigesp_sno_c_personalnomina();
		require_once("sigesp_sno.php");
		$this->io_sno=new sigesp_sno();
		require_once("../shared/class_folder/class_fecha.php");
		$this->io_fecha=new class_fecha();		
		require_once("../shared/class_folder/evaluate_formula.php");
		$this->io_eval=new evaluate_formula();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
		require_once("sigesp_sno_c_constantes.php");
		$this->io_constantep = new sigesp_sno_c_constantes();
		require_once("sigesp_sno_c_conceptopersonal.php");
		$this->io_conceptop = new sigesp_sno_c_conceptopersonal();
		require_once("sigesp_sno_c_concepto.php");
		$this->io_concepto = new sigesp_sno_c_concepto();
		require_once($this->ruta."shared/class_folder/class_logs.php");					
		$this->logs = new logs();
	}// end function sigesp_sno_c_importarprestamos
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sno_p_impexpdato)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Mar�a Beatriz Unda
		// Fecha Creaci�n: 27/02/2009 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_prestamo);
		unset($this->io_personalnomina);
        unset($this->ls_codemp);
        unset($this->ls_codnom);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	
	function uf_crear_archivo($as_ruta,&$ao_archivo,&$as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_crear_archivo
		//		   Access: private
		//	    Arguments: as_ruta // Ruta donde se debe crear el archivo
		//	    		   ao_archivo // conexi�n del archivo que se desea crear
		//	    		   as_tipo // tipo de archivo que se quiere crear
		// 	      Returns: lb_valido True si se creo el archivo � False si no se creo
		//	  Description: Funcion que crea un archivo de texto dada una ruta 
		//	   Creado Por: Ing. Mar�a Beatriz Unda
		// Fecha Creaci�n: 27/02/2009 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$ls_nombrearchivo=$as_ruta.'/Conceptos_Exportados_'.$ls_peractnom.'.txt';
		$as_tipo="C";
		if (file_exists("$ls_nombrearchivo"))
		{
			unlink ("$ls_nombrearchivo");//Borrar el archivo de texto existente para crearlo nuevo.
			$ao_archivo=@fopen("$ls_nombrearchivo","a+");
		}
		else
		{
			$ao_archivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
		}
		if (file_exists("$ls_nombrearchivo")===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Importar Prestamos M�TODO->uf_crear_archivo ERROR->No Se pudo crear el archivo."); 
		}		
		return $lb_valido;
	}// end function uf_crear_archivo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importardatos($as_arctxt,$as_codarch,&$ao_title,&$ao_campos,&$ai_nrofilas,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importardatos
		//		   Access: public (sigesp_sno_p_impexpdato)
		//	    Arguments: as_arctxt  // Archivo txt que se desea importar
		//				   as_codarch // C�digo de Archivo
		//				   ao_title // Arreglo de Titulos
		//				   ao_campos // Arreglo de Campos
		//				   ai_nrofilas // N�mero de Filas
		//	    		   aa_seguridad  // arreglo de las variables de seguridad
		// 	      Returns: lb_valido True si se import� correctamente la informaci�n al sistema � False si hubo alg�n error
		//	  Description: Funcion que importa la informaci�n de un txt al sistema
		//	   Creado Por: Ing. Mar�a Beatriz Unda
		// Fecha Creaci�n: 28/03/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$ls_nombrearchivo=$as_arctxt;
		$lb_valido=$this->uf_abrir_archivo($ls_nombrearchivo,$lo_archivo);
		$li_totrows=0;
		$lo_object="";
		if($lb_valido)
		{
			$lb_valido=$this->uf_load_configuracion_campos($as_codarch,&$li_totrows,&$lo_object);
			if($lb_valido)
			{
				$lb_valido=$this->uf_load_archivotxt_campos($lo_archivo,$li_totrows,$lo_object,&$ao_title,&$ao_campos,&$ai_nrofilas);
			}
			unset($lo_archivo);
		}
		if($lb_valido)
		{
			$this->io_mensajes->message("La informaci�n fue Importada.");
		}
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("Ocurrio un error al importar la informaci�n");
		}
		return $lb_valido;
	}// end function uf_importardatos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_abrir_archivo($as_nombrearchivo,&$ao_archivo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_abrir_archivo
		//		   Access: private
		//	    Arguments: as_nombrearchivo // Ruta donde se debe abrir el archivo
		//	    		   ao_archivo // conexi�n del archivo que se desea abrir
		// 	      Returns: lb_valido True si se abrio el archivo � False si no se abrio
		//	  Description: Funcion que abre un archivo de texto dada una ruta 
		//	   Creado Por: Ing. Mar�a Beatriz Unda
		// Fecha Creaci�n: 28/03/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if (file_exists("$as_nombrearchivo"))
		{
			$ao_archivo=@file("$as_nombrearchivo");
		}
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Importar Prestamos M�TODO->uf_abrir_archivo ERROR->el archivo no existe."); 
		}
		return $lb_valido;
	}// end function uf_abrir_archivo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_configuracion_campos($as_codarch,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_configuracion_campos
		//		   Access: privates
		//	    Arguments: as_codarch  // c�digo del archivo txt
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar � False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de un archivo txt
		//	   Creado Por: Ing. Mar�a Beatriz Unda
		// Fecha Creaci�n: 12/11/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codarch, codcam, descam, inicam, loncam, edicam, clacam, actcam, tabrelcam, iterelcam, cricam, tipcam ".
				"  FROM sno_archivotxtcampo".
				" WHERE sno_archivotxtcampo.codemp='".$this->ls_codemp."'".	
				" AND codarch = '".$as_codarch."' ".	
				" ORDER BY sno_archivotxtcampo.codcam,inicam "; 

		$rs_data=$this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Archivo txt M�TODO->uf_load_configuracion_campos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;			
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$li_codcam=$row["codcam"];
				$ls_descam=$row["descam"];
				$li_inicam=$row["inicam"];
				$li_loncam=$row["loncam"];
				$ls_cricam=$row["cricam"];
				$ls_edicam=$row["edicam"];
				$ls_clacam=$row["clacam"];
				$ls_actcam=$row["actcam"];
				$ls_tabrelcam=$row["tabrelcam"];
				$ls_iterelcam=$row["iterelcam"];
				$ls_tipcam=$row["tipcam"];
				$ao_object["codcam"][$ai_totrows]=$li_codcam;
				$ao_object["descam"][$ai_totrows]=$ls_descam;
				$ao_object["inicam"][$ai_totrows]=$li_inicam;
				$ao_object["loncam"][$ai_totrows]=$li_loncam;
				$ao_object["cricam"][$ai_totrows]=$ls_cricam;
				$ao_object["edicam"][$ai_totrows]=$ls_edicam;
				$ao_object["clacam"][$ai_totrows]=$ls_clacam;
				$ao_object["actcam"][$ai_totrows]=$ls_actcam;
				$ao_object["tabrelcam"][$ai_totrows]=$ls_tabrelcam;
				$ao_object["iterelcam"][$ai_totrows]=$ls_iterelcam;
				$ao_object["tipcam"][$ai_totrows]=$ls_tipcam;
				
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_configuracion_campos
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_archivotxt_campos($ao_archivo,$ai_totrows,$ao_object,&$ao_title,&$ao_campos,&$ai_nrofilas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_archivotxt_campos
		//		   Access: private
		//	    Arguments: ao_archivo // conexi�n del archivo que se desea leer
		//	    		   ai_totrows  // Total de filas del arreglo de campos
		//	    		   ao_object  // arreglo de campos
		//				   ao_title // Arreglo de Titulos
		//				   ao_campos // Arreglo de Campos
		//				   ai_nrofilas // N�mero de Filas
		// 	      Returns: lb_valido True si se abrio el archivo � False si no se abrio
		//	  Description: Funcion que carga un archivo txt seg�n la ruta y la configuraci�n dada
		//	   Creado Por: Ing. Mar�a Beatriz Unda
		// Fecha Creaci�n: 28/03/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_fila=0;
		$li_total=count($ao_archivo);
		for($li_i=0;($li_i<$li_total);$li_i++)
		{
			$li_fila++;
			for($li_z=1;($li_z<=$ai_totrows);$li_z++)
			{
				$li_codcam=$ao_object["codcam"][$li_z];
				$ls_descam=$ao_object["descam"][$li_z];
				$li_inicam=$ao_object["inicam"][$li_z];
				$li_loncam=$ao_object["loncam"][$li_z];				
				$ls_tabrelcam=$ao_object["tabrelcam"][$li_z];
				$ls_iterelcam=$ao_object["iterelcam"][$li_z];
				$ls_tipcam=$ao_object["tipcam"][$li_z];
				$ao_title[$li_z]=$ls_descam;
				$ls_readonly="readonly";
				$ls_formato="onKeyUp='javascript: ue_validarcomillas(this);'";
				
				if($ls_tipcam=="N")
				{
					$ls_campo=number_format($ls_campo,2,".","");
				}
				
				$ls_campo=substr($ao_archivo[$li_i],$li_inicam,$li_loncam);
				if($ls_tipcam=="N")
				{
					$ls_campo=number_format($ls_campo,2,",",".");
					$ls_formato="onKeyPress=return(ue_formatonumero(this,'.',',',event)) style='text-align:right'";
					$li_loncam=15;
				}
				else if($ls_tipcam=="E")
				{
					$ls_campo=intval($ls_campo);
					$ls_formato=" style='text-align:right'";
				}
				$ao_campos[$li_fila][$li_z]="<input name=txtcampo".$li_fila.$li_z." type=text id=txtcampo".$li_fila.$li_z." class=sin-borde maxlength=".$li_loncam." value='".$ls_campo."' ".$ls_formato." ".$ls_readonly.">".
										   "<input type=hidden name=txttipcam".$li_fila.$li_z." id=txttipcam".$li_fila.$li_z." value='".$ls_tipcam."'>".
										 	"<input type=hidden name=txttabrelcam".$li_fila.$li_z." id=txttabrelcam".$li_fila.$li_z." value='".$ls_tabrelcam."'>".
										 	"<input type=hidden name=txtiterelcam".$li_fila.$li_z." id=txtiterelcam".$li_fila.$li_z." value='".$ls_iterelcam."'>";
							
			}
			$ao_title[$li_z]=" ";
			$ao_campos[$li_fila][$li_z]="<input type=checkbox name=chksel".$li_fila.$li_z." id=chksel".$li_fila.$li_z." value=1 style=width:15px;height:15px checked>";		
		}
		$ai_nrofilas=$li_i;
		return $lb_valido;
	}// end function uf_importar_data
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesarimportardatos($as_codarch,$ai_nrofilas,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesarimportardatos
		//		   Access: public (sigesp_sno_p_impexpdato)
		//	    Arguments: as_codarch // C�digo de Archivo
		//				   as_codcons // C�digo de la constantes
		//				   ai_nrofilas // total de filas 
		//	    		   aa_seguridad  // arreglo de las variables de seguridad
		// 	      Returns: lb_valido True si se import� correctamente la informaci�n al sistema � False si hubo alg�n error
		//	  Description: Funcion que importa la informaci�n de un txt al sistema
		//	   Creado Por: Ing. Mar�a Beatriz Unda
		// Fecha Creaci�n: 27/02/2009 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$li_totrows=0;
		$lo_object="";
		if($lb_valido)
		{
			$lb_valido=$this->uf_load_configuracion_campos($as_codarch,&$li_totrows,&$lo_object);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_load_personalprestamo($ai_nrofilas,$li_totrows,$aa_seguridad);
		}
		if($lb_valido)
		{
			$this->io_mensajes->message("Los prestamos fueron insertados.");
		}
		else
		{
			$this->io_mensajes->message("Ocurrio un error al procesar los prestamos");
		}
		return $lb_valido;
	}// end function uf_procesarimportardatos
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_personalprestamo($ai_nrofilas,$ai_totrow,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_personalprestamo
		//		   Access: private
		//	    Arguments: as_codcons // C�digo de la constantes
		//				   ai_nrofilas // Nro de filas a actualizar
		//				   ai_totrow // total de filas 
		//	    		   aa_seguridad  // arreglo de las variables de seguridad
		// 	      Returns: lb_valido True si actualiz� correctamente � falso si ocurro alg�n error
		//	  Description: Funcion que actualiza el valor de una constante seg�n lo cargado en los txt
		//	   Creado Por: Ing. Mar�a Beatriz Unda
		// Fecha Creaci�n: 27/02/2009 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codper="";
		$ls_codtippre="";
		$li_numpre="";
		$ls_codconc="";
		$ls_stapre="1";
		$li_monpre="";
		$li_numcuopre="";
		$ls_perinipre="";
		$li_monamopre=0;
		$ls_fecpre="";
		$ls_tipcuopre="0";
		$li_moncuo=0;
		$li_sueper=0;
		$ls_obsrecpre="";
		$lb_ok=true;
		$lb_ok2=true;
		
		$ls_nombrearchivo="sno/txt/general/errores_importar_prestamos.txt";
		$this->nombrearchivo = "sno/txt/general/errores_importar_prestamos.txt";
		$this->logs->borrar_archivo($ls_nombrearchivo);
		
		$total_registros = 0;
		$total_registros_del = 0;
		$total_monto = 0;
		$no_existe = 0;
		$errores = 0;
		$codigos_per = '';
		$codigos_act = '';
		$codigos_tippre = '';
		$codigos_conc = '';
		$entro=0;
		
		
		for($li_i=1;($li_i<=$ai_nrofilas);$li_i++)
		{
			$li_fin=$ai_totrow+1;
			$li_chksel=$_POST["chksel".$li_i.$li_fin];
			if($li_chksel==1)
			{
				for($li_z=1;($li_z<=$ai_totrow);$li_z++)
				{
					$ls_campo=$_POST["txtcampo".$li_i.$li_z];
					$ls_tabrelcam=$_POST["txttabrelcam".$li_i.$li_z];
					$ls_iterelcam=$_POST["txtiterelcam".$li_i.$li_z];
					$ls_tipcam=$_POST["txttipcam".$li_i.$li_z];
					
					if($ls_tipcam=="N")
					{
						$ls_campo=str_replace(".","",$ls_campo);
						$ls_campo=str_replace(",",".",$ls_campo);
					}					
									
					if($ls_iterelcam=="monpre")
					{
						$li_monpre=$ls_campo;
						
					}
					if($ls_iterelcam=="numcuopre")
					{
						$li_numcuopre=$ls_campo;
						
					}
					if($ls_iterelcam=="fecpre")
					{
						$ls_fecpre=substr($ls_campo,0,4).'-'.substr($ls_campo,5,2).'-'.substr($ls_campo,8,2);
						
					}
					if($ls_iterelcam=="obsrecpre")
					{
						$ls_obsrecpre=$ls_campo;
						
					}	
					if($ls_iterelcam=="eliminar")
					{
						$ls_eliminar=$ls_campo;
						switch((integer)$ls_eliminar){
							
							case 0:
								$ls_eliminar='no';
							break;
							
							case 1:
								$ls_eliminar='si';
							break;
						
						}
						
						
					}				
					if($ls_iterelcam=="codper")
					{
						$ls_codper=$ls_campo;
						$ls_codper=str_pad($ls_codper,10,0,STR_PAD_LEFT);
						$lb_existe=$this->uf_buscar_personalnomina($ls_codper,$li_sueper);
						if(!$lb_existe)	
						{   $lb_ok2=false;
							$ls_cadena="La persona ".$ls_codper." no existe en la nomina ".$this->ls_codnom;					
							$this->logs->sislog($ls_cadena,$ls_nombrearchivo);
							if($li_i==1){$codigos_per = $ls_codper;}
							else{$codigos_per = $ls_codper.','.$codigos_per;}
							$no_existe ++;							
							
						}
					}
					if($ls_iterelcam=="codtippre")
					{
						$ls_codtippre=$ls_campo;
						$ls_codtippre=str_pad($ls_codtippre,10,0,STR_PAD_LEFT);
						$lb_existe=$this->uf_buscar_tipoprestamo($ls_codtippre);
						if(!$lb_existe)	
						{   $lb_ok2=false;
							$ls_cadena="El tipo de prestamo ".$ls_codtippre." no existe en la nomina ".$this->ls_codnom;			
							$this->logs->sislog($ls_cadena,$ls_nombrearchivo);
							if($li_i==1){$codigos_tippre = $ls_codtippre;}
							else{$codigos_tippre = $ls_codtippre.','.$codigos_tippre;}	
							
						}
					}
					if($ls_iterelcam=="codconc")
					{					
						
						$ls_codconc=$ls_campo;
						$ls_codconc=str_pad($ls_codconc,10,0,STR_PAD_LEFT);
						$lb_existe=$this->uf_buscar_concepto($ls_codconc);
						if(!$lb_existe)	
						{   $lb_ok2=false;
							$ls_cadena="El concepto ".$ls_codconc." no existe en la nomina ".$this->ls_codnom;					
							$this->logs->sislog($ls_cadena,$ls_nombrearchivo);
							if($li_i==1){$codigos_conc = $ls_codconc;}
							else{$codigos_conc = $ls_codconc.','.$codigos_conc;}	
							
						}
					}
					
				}
				
				
				if(($ls_codper=="")||($ls_codtippre=="")||($ls_codconc=="")||($li_monpre=="")||
				   ($li_numcuopre=="")||(fecpre==""))
				{
					$ls_cadena="Debe llenar todos los campos: \r\n";	
					$ls_cadena=$ls_cadena."Codigo de Persona: ".$ls_codper."\r\n";	
					$ls_cadena=$ls_cadena."Codigo Tipo de Prestamo: ".$ls_codtippre."\r\n";	
					$ls_cadena=$ls_cadena."Codigo Concepto: ".$ls_codconc."\r\n";
					$ls_cadena=$ls_cadena."Monto del Prestamo: ".$li_monpre."\r\n";	
					$ls_cadena=$ls_cadena."Numero de Cuotas: ".$li_numcuopre."\r\n";	
					$ls_cadena=$ls_cadena."Fecha del Prestamo: ".$ls_fecpre."\r\n\r\n";					
					$this->logs->sislog($ls_cadena,$ls_nombrearchivo);					
					$lb_ok=false;
				}
				
				
				
				if(($lb_ok==true)&&($lb_ok2==true))
				{
					//$entro++;
					//echo $li_monpre.'<br>';
					$li_moncuo=round($li_monpre/$li_numcuopre,2);
					$li_numpre=$this->uf_buscar_numero_prestamo_personal($ls_codper);
					$ls_perinipre=$this->uf_buscar_periodo_prestamo($ls_fecpre);
					$ld_fecdesper=$_SESSION["la_nomina"]["fecdesper"];
		        	$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
					
					$datos['eliminar']	= $ls_eliminar;			
					$datos['codper'] = $ls_codper;
					$datos['codtippre'] = $ls_codtippre;
					$datos['numpre'] = $li_numpre;
					$datos['codconc'] = $ls_codconc;
					$datos['stapre'] = $ls_stapre;
					$datos['monpre'] = $li_monpre;
					$datos['numcuopre'] = $li_numcuopre;
					$datos['perinipre'] = $ls_perinipre;
					$datos['monamopre'] = $li_monamopre;
					$datos['fecdesper'] = $ld_fecdesper;
					$datos['fechasper'] = $ld_fechasper;
					$datos['sueper'] = $li_sueper;
					$datos['moncuo'] = $li_moncuo;
					$datos['tipcuopre'] = $ls_tipcuopre;
					$datos['fecpre'] = $ls_fecpre;
					$datos['obsrecpre'] = $ls_obsrecpre;
					$datos['nombrearchivo'] = $ls_nombrearchivo;
									
					$lb_valido=$this->uf_procesar_prestamo($datos,$aa_seguridad);
					
					
					if($lb_valido){
						$total_registros = $total_registros + 1;
						$total_monto = $total_monto + $datos['monpre'];
						
						if($datos['eliminar']=='si'){						
								$total_registros_del = $total_registros_del + 1;
						}					
					
					}
					else{
							$errores++;
							if($li_i==1){$codigos_act = $datos['codper'];}
							else{$codigos_act = $datos['codper'].','.$codigos_act;}											
					}
					
														 
				}
				
				
			}
		}
		//print $entro;
		
		$total_monto=number_format($total_monto,2,",",".");
		$mensaje = 'Se procesaron '.$total_registros.' registros por un monto total de: '.$total_monto.' Bs.';
		
		$textox = $mensaje;
		if($no_existe>0 or $errores>0){
				$texto = $mensaje;
				$mensaje=$mensaje."' + ".'"\n"'." + ".'"\n"'.
						  " + '*** ".$no_existe." personas no fueron encontradas en nomina. *** ' + ".
						  '"\n"'.
						  " + '*** ".$errores." personas con error en actualizaci�n. *** ' + ".
						  '"\n"'.
						  " + '*** Consulte el log de errores para verificar las c�dulas. ***' + ".
						  '"\n"'.
						  " + ".'"\n"'.
						  " + ' Ruta: ".$ls_nombrearchivo;
				
				$texto = "\r\n"."\r\n". 
						 ' *** '.$no_existe.' personas no fueron encontradas en nomina. *** '."\r\n".
						 ' *** '.$errores.' personas con error en actualizaci�n. *** '."\r\n";
						 
				$texto2 = 'C�dulas no encontradas: '."\r\n".$codigos_per."\r\n";
				$texto3 = 'C�dulas con error en actualizaci�n: '."\r\n".$codigos_act."\r\n";
						  
				$this->logs->sislog($texto,$ls_nombrearchivo);						
				$this->logs->sislog($texto2,$ls_nombrearchivo);
				$this->logs->sislog($texto3,$ls_nombrearchivo);							  
						
		}
		if($total_registros_del>0){
			$textox=$textox."\r\n".$total_registros_del." prestamos fueron eliminados.  ";
			$this->logs->sislog($textox,$ls_nombrearchivo);	
			$mensaje=$mensaje."' + ".'"\n"'." + ".'"\n"'.
					  " + ' >> ".$total_registros_del." prestamos fueron eliminados.  ";
		}
		$this->io_mensajes->message($mensaje);
		
		
		return $lb_valido;
	}// end function uf_load_personalprestamo
	//-----------------------------------------------------------------------------------------------------------------------------------		
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_buscar_personalnomina($as_codper,&$ai_sueper)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_personalnomina
		//		   Access: private
		//	    Arguments: as_codper // c�digo del personal		
		//	      Returns: lb_valido 
		//	  Description: Funcion que verifica que un personal este en la tabla sno_personalnomina.
		//	   Creado Por: Ing. Mar�a Beatriz Unda
		// Fecha Creaci�n: 27/02/2009						Fecha �ltima Modificaci�n : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ai_sueper=0;
		$ls_sql="  SELECT codper, sueper ".
			"  FROM sno_personalnomina ".
			"  WHERE codemp ='".$this->ls_codemp."' ".
			"   AND codnom = '".$this->ls_codnom."' ".		
			"   AND codper='".$as_codper."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Prestamos M�TODO->uf_buscar_personalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=true;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
				$ai_sueper=$row["sueper"];
				
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
}//end function uf_buscar_personalnomina
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_buscar_tipoprestamo($as_codtippre)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_tipoprestamo
		//		   Access: private
		//	    Arguments: as_codtippre // c�digo del tipo de prestamo		
		//	      Returns: lb_valido 
		//	  Description: Funcion que verifica que un tipo de prestamos este en la tabla sno_tipoprestamo.
		//	   Creado Por: Ing. Mar�a Beatriz Unda
		// Fecha Creaci�n: 27/02/2009						Fecha �ltima Modificaci�n : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		
		$ls_sql="  SELECT codtippre ".
			"  FROM sno_tipoprestamo ".
			"  WHERE codemp ='".$this->ls_codemp."' ".
			"   AND codnom = '".$this->ls_codnom."' ".		
			"   AND codtippre='".$as_codtippre."' "; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Prestamos M�TODO->uf_buscar_tipoprestamo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=true;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
				
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
}//end function uf_buscar_tipoprestamo
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_buscar_concepto($as_codconc)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_concepto
		//		   Access: private
		//	    Arguments: as_codconc// c�digo del concepto
		//	      Returns: lb_valido 
		//	  Description: Funcion que verifica que un tipo de prestamos este en la tabla sno_concepto.
		//	   Creado Por: Ing. Mar�a Beatriz Unda
		// Fecha Creaci�n: 27/02/2009						Fecha �ltima Modificaci�n : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		
		$ls_sql="  SELECT codconc ".
			"  FROM sno_concepto ".
			"  WHERE codemp ='".$this->ls_codemp."' ".
			"   AND codnom = '".$this->ls_codnom."' ".		
			"   AND codconc='".$as_codconc."' ";  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Prestamos M�TODO->uf_buscar_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=true;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
				
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
}//end function uf_buscar_concepto
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	function uf_insert_prestamo($as_codper,$as_codtippre,$ai_numpre,$as_codconc,$ai_stapre,$ai_monpre,$ai_numcuopre,$as_perinipre,
								$ai_monamopre,$ad_fecdesper,$ad_fechasper,$ai_sueper,$ai_moncuo,$as_tipcuopre,
								$ad_fecpre,$as_obsrecpre,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_prestamo
		//		   Access: private (uf_guardar) 
		//	    Arguments: as_codper  // C�digo del Personal
		//				   as_codtippre  // C�digo del tipo de Prestamo
		//				   ai_numpre  // N�mero Correlativo del Prestamo
		//				   as_codconc  // C�digo del Concepto
		//				   ai_stapre  // Estatus del Prestamo
		//				   ai_monpre  // Monto del Prestamo
		//				   ai_numcuopre  // N�mero de Cuotas
		//				   as_perinipre  // Per�odo Inicial
		//				   ai_monamopre  // Monto Amortizado 
		//				   ad_fecdesper  // Fecha Desde Periodo de Inicio del Prestamo
		//				   ad_fechasper  // Fecha Hasta Periodo de Inicio del Prestamo
		//				   ai_sueper  // sueldo del personal
		//				   ai_moncuo  // Monto de la cuota mensual
		//				   as_configuracion  // Configuraci�n del prestamo si es por monto � por cuota
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert � False si hubo error en el insert
		//	  Description: Funcion que inserta el prestamo del personal
		//	   Creado Por: Ing. Mar�a Beatriz Unda
		// Fecha Creaci�n: 27/02/2009 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_prestamos(codemp,codnom,codper,codtippre,numpre,codconc,stapre,monpre,numcuopre,perinipre,monamopre,fecpre,tipcuopre,obsrecpre)".
				"VALUES('".$this->ls_codemp."','".$this->ls_codnom."','".$as_codper."','".$as_codtippre."',".$ai_numpre.",'".$as_codconc."',".
				" ".$ai_stapre.",".$ai_monpre.",".$ai_numcuopre.",'".$as_perinipre."',".$ai_monamopre.",'".$ad_fecpre."','".$as_tipcuopre."','".$as_obsrecpre."')";
		$this->io_sql->begin_transaction();
		//echo $ls_sql.'<br>';
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Importar Prestamos M�TODO->uf_insert_prestamo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_mensajes->message("ERROR-> Revise el archivo de errores.");
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insert� el Prestamo nro ".$ai_numpre." de tipo ".$as_codtippre." del personal ".
							 "".$as_codper." asociado a la n�mina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			if($lb_valido)
			{	
				$ls_configuracion=trim($this->io_sno->uf_select_config("SNO","CONFIG","CONFIGURACION_PRESTAMO","CUOTAS","C"));
			
				$lb_valido = $this->io_prestamo->uf_generar_cuotas($as_codper,$as_codtippre,$ai_numpre,$ai_monpre,$ai_numcuopre,$as_perinipre,$ad_fecdesper,
							  			$ad_fechasper,$ai_sueper,$ai_moncuo,$ls_configuracion,$as_tipcuopre,$aa_seguridad);
			}
			if($lb_valido)
			{
				$lb_valido = $this->io_prestamo->io_cuota->uf_verificar_integridadcuota($as_codper,$as_codtippre,$ai_numpre);
			}
			if($lb_valido)
			{	
				$this->io_mensajes->message("El prestamo fue registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("ERROR-> Error al registrar el prestamo.");
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_prestamo	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_numero_prestamo_personal($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_numero_prestamo_personal
		//		   Access: private
		//	    Arguments: as_codper  // c�digo de personal
		//	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que trae el n�mero del prestamo del personal
		//	   Creado Por: Ing. Mar�a Beatriz Unda
		// Fecha Creaci�n: 26/02/2009 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT MAX(numpre) AS numero ".
				"  FROM sno_prestamos ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom = '".$this->ls_codnom."' ".		
				"   AND codper='".$as_codper."' ";
				
		$lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
		if ($lb_hay)
		$li_numpre= $la_datos["numero"][0]+1;
		return $li_numpre;
	}// end function uf_buscar_numero_prestamo_personal
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_periodo_prestamo($ad_fecha)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_periodo_prestamo
		//		   Access: private
		//	    Arguments: ad_fecha  // fecha del prestamo
		//	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que trae el periodo inicial del prestamo
		//	   Creado Por: Ing. Mar�a Beatriz Unda
		// Fecha Creaci�n: 26/02/2009 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_codperi="000";
		$ls_sql="SELECT codperi ".
				"  FROM sno_periodo ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom = '".$this->ls_codnom."' ".		
				"   AND '".$ad_fecha."' BETWEEN fecdesper AND  fechasper "; 
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Prestamos M�TODO->uf_buscar_periodo_prestamo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=true;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codperi=$row["codperi"];
				
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $ls_codperi;
	}// end function uf_buscar_periodo_prestamo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	
	function uf_procesar_prestamo($datos,$aa_seguridad)
	{
		
		
		$existex = $this->io_prestamo->uf_validar_prestamos_iguales($datos);
		
		if($existex){
								
				$ls_cadena = ' *** ERROR: Ya tiene cuotas de un prestamo con este concepto en el per�odo actual. No ser� actualizado. codper: '.$datos['codper'].' codconc: '.$datos['codconc'].
							 '    fecha: '.$datos['fecpre'].'    monto: '.$datos['monpre'];
				$this->logs->sislog($ls_cadena,$datos['nombrearchivo']);
				return false;							
		}
		
		$lb_valido=true;		
		$existe = $this->io_prestamo->uf_validar_prestamo($datos);
		
		
		if($datos['eliminar']=='si' and $existe<1){							
				$ls_cadena = ' *** ERROR: El prestamo no existe para esta persona. No ser� eliminado. codper: '.$datos['codper'].' codconc: '.$datos['codconc'].
							 '    fecha: '.$datos['fecpre'].'    monto: '.$datos['monpre'];
				$this->logs->sislog($ls_cadena,$datos['nombrearchivo']);
				return false;							
		}
		
		//SI EL PRESTAMO EXISTE LO ELIMINAMOS Y VOLVEMOS A CARGARLO
		if($existe){
						
				//SI EL PRESTAMO ESTA REPETIDO VARIAS VECES SE ELIMINA RECURSIVAMENTE
				$this->aa_seguridad = $aa_seguridad;
				$resp = $this->eliminar_prestamos($datos);				
				if($resp===false){return false;}
				if($datos['eliminar']=='si' and $resp===true){return true;}
				$existe=0;
		}
		
		if(!$existe){	
			
				$ls_sql="INSERT INTO sno_prestamos(codemp,codnom,codper,codtippre,numpre,codconc,stapre,monpre,numcuopre,perinipre,monamopre,fecpre,tipcuopre,obsrecpre)".
						"VALUES('".$this->ls_codemp."','".$this->ls_codnom."','".$datos['codper']."','".$datos['codtippre']."',".$datos['numpre'].",'".$datos['codconc']."',".
						" ".$datos['stapre'].",".$datos['monpre'].",".$datos['numcuopre'].",'".$datos['perinipre']."',".$datos['monamopre'].",'".$datos['fecpre']."','".
						$datos['tipcuopre']."','".$datos['obsrecpre']."')";
		}
		else{			
						
				return false;		
		}
				
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$texto = "CLASE->sigesp_sno_c_importarprestamos M�TODO->uf_procesar_prestamo ERROR->".$this->io_sql->message; 
			$this->io_mensajes->message($texto);
			$this->logs->sislog($texto,$datos['nombrearchivo']);
			$this->io_sql->rollback();
		}
		else
		{

			$lb_valido=true;
			if($lb_valido)
			{	
				$ls_configuracion=trim($this->io_sno->uf_select_config("SNO","CONFIG","CONFIGURACION_PRESTAMO","CUOTAS","C"));
			  
				$lb_valido = $this->io_prestamo->uf_generar_cuotas($datos['codper'],$datos['codtippre'],$datos['numpre'],$datos['monpre'],
				    											   $datos['numcuopre'],$datos['perinipre'],$datos['fecdesper'],$datos['fechasper'],
							  									   $datos['sueper'],$datos['moncuo'],$ls_configuracion,$datos['tipcuopre'],$aa_seguridad,$datos['nombrearchivo']);
			}
			
			if($lb_valido)
			{
				 
				$lb_valido = $this->io_prestamo->io_cuota->uf_verificar_integridadcuota($datos['codper'],$datos['codtippre'],$datos['numpre']);
			}
			else{
			
				$texto = "*** ERROR: Al generar cuotas. codper: ".$datos['codper']."  codconc: ".$datos['codtippre'];				
				$this->logs->sislog($texto,$datos['nombrearchivo']);
				return false;
			}
			if($lb_valido)
			{	
				$texto = "El prestamo fue registrado. codper: ".$datos['codper']."  codconc: ".$datos['codtippre'];
				//$this->io_mensajes->message($texto);
				$this->logs->sislog($texto,$datos['nombrearchivo']);
				$this->io_sql->commit();
				return true;
			}
			else
			{
				$lb_valido=false;
				$texto = "*** ERROR:  Error al comprobar la integridad del prestamo. codper: ".$datos['codper']."  codconc: ".$datos['codtippre'];
				$this->io_mensajes->message($texto);
				$this->logs->sislog($texto,$datos['nombrearchivo']);
				$this->io_sql->rollback();
				return false;
			}
		}
		return $lb_valido;
	}// end function uf_procesar_prestamo	
	
	
	
	function eliminar_prestamos($datos=array()){
					
					$this->io_prestamo->cuotascancel=0;
					$existe = $this->io_prestamo->uf_validar_prestamo($datos);
					if($existe>=1){					   
						while($existe>0){
							$this->io_prestamo->mostrar_mensaje=false;
							$valido = $this->io_prestamo->uf_delete($datos['codper'],$datos['codtippre'],$this->io_prestamo->rs_data->fields['numpre'],$aa_seguridad);
							$this->io_prestamo->mostrar_mensaje=true;
							
							if($this->io_prestamo->cuotascancel){
										$ls_cadena = 'No se puede eliminar el prestamo. Ya existen cuotas canceladas codper: '.$datos['codper'].'   codconc: '.$datos['codconc'].'   n�mero: '.$this->io_prestamo->rs_data->fields['numpre'];
										$this->logs->sislog($ls_cadena,$datos['nombrearchivo']);
										if($resul===false){return false;}
										return true;
									
							}
							
							if(!$valido){
									
									$ls_cadena = 'Error eliminando el prestamo codper: '.$datos['codper'].'   codconc: '.$datos['codconc'].'   n�mero: '.$this->io_prestamo->rs_data->fields['numpre'];
									$this->logs->sislog($ls_cadena,$datos['nombrearchivo']);
									return false;
							
							}								
							$ls_cadena = 'El prestamo fu� eliminado codper: '.$datos['codper'].'   codconc: '.$datos['codconc'].'   n�mero: '.$this->io_prestamo->rs_data->fields['numpre'];
							$this->logs->sislog($ls_cadena,$datos['nombrearchivo']);
							
							$existe = $this->io_prestamo->uf_validar_prestamo($datos);
							
						}
						return true;
				}
				return true;				
	
	}
	
}
?>
