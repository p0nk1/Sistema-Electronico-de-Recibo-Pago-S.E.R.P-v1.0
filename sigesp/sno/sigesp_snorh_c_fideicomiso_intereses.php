<?php
class sigesp_snorh_c_fideicomiso_intereses
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_fun_nomina;
	var $io_fideiconfigurable;
	var $io_personal;
	var $io_sno;
	var $ls_codemp;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_fideicomiso_intereses()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_fideicomiso_intereses
		//		   Access: public (sigesp_snorh_d_fideicomiso)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/10/2010 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$this->io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($this->io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad=new sigesp_c_seguridad();
		require_once("class_folder/class_funciones_nomina.php");
		$this->io_fun_nomina=new class_funciones_nomina();
		require_once("sigesp_snorh_c_fideiconfigurable.php");
		$this->io_fideiconfigurable=new sigesp_snorh_c_fideiconfigurable();
		require_once("sigesp_snorh_c_personal.php");
		$this->io_personal=new sigesp_snorh_c_personal();
		require_once("sigesp_sno.php");
		$this->io_sno=new sigesp_sno();
		$this->DS=new class_datastore();		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_snorh_c_fideicomiso_intereses
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_fideicomiso)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/10/2010 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fun_nomina);
		unset($this->io_fideiconfigurable);
		unset($this->io_personal);
		unset($this->io_sno);
        unset($this->ls_codemp);
        
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_nomina($ai_anocurper,$as_mescurper,&$aa_nominas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_nomina
		//		   Access: public (sigesp_snorh_p_fideicomiso_intereses.php)
		//	    Arguments: aa_nominas  // arreglo de Nóminas 
		//	      Returns: lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Función que obtiene las nóminas creadas en el sistema
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/10/2010 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio = "";
		if($ai_anocurper!='' && $as_mescurper!='')
		{
			$ls_criterio = "  AND sno_fideiperiodo.anocurper = '".$ai_anocurper."' ".
						   "  AND sno_fideiperiodo.mescurper = ".$as_mescurper." ";

		}
		$ls_sql="SELECT sno_nomina.codnom, MAX(sno_nomina.desnom) AS desnom ".
				"  FROM sno_nomina ".
				" INNER JOIN sno_fideiperiodo ".
				"   ON sno_nomina.codemp='".$this->ls_codemp."' ".
				"  AND sno_nomina.espnom = '0' ".
				$ls_criterio.
				"  AND sno_fideiperiodo.codemp = sno_nomina.codemp ".
				"  AND sno_fideiperiodo.codnom = sno_nomina.codnom ".
				"GROUP BY sno_nomina.codnom";
				"ORDER BY sno_nomina.codnom";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_load_nomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$li_i=0;
			while(!$rs_data->EOF)
			{
				$aa_nominas["codnom"][$li_i]=$rs_data->fields["codnom"];
				$aa_nominas["desnom"][$li_i]=$rs_data->fields["desnom"];
				$li_i=$li_i+1;
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// end function uf_load_nomina
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_fideicomiso_intereses($ai_anocurper,$as_mescurper,$aa_nominas,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_fideicomiso_intereses
		//		   Access: public (sigesp_snorh_p_fideicomiso_intereses.php)
		//	    Arguments: ai_anocurper  // año en curso seleccionado
		//	    		   as_mescurper  // mes en curso seleccionado
		//	    		   aa_nominas  // arreglo de Nóminas seleccionadas
		//	    		   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el proceso ó False si hubo error en el proceso
		//	  Description: Función que obtiene los intereses del fideicomiso
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/10/2010 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
       	$this->io_sql->begin_transaction();
		if($this->uf_verificar_fideiperiodointereses($ai_anocurper,$as_mescurper,$aa_nominas)==false)
		{
			$this->io_mensajes->message("Debe Calcular los Meses Anteriores.");
			$lb_valido=false;
		}
		if($this->uf_verificar_fideiperiodointereses_posteriores($ai_anocurper,$as_mescurper))
		{
			$this->io_mensajes->message("Los Meses Posteriores ya están calculados. No se pueden Calcular los intereses.");
			$lb_valido=false;
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_delete_fideiperiodointereses($ai_anocurper,$as_mescurper,$aa_nominas);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_procesar_personal_intereses($ai_anocurper,$as_mescurper,$aa_nominas,$ld_fecgen);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_generar_data_contabilizacion($ai_anocurper,$as_mescurper,$aa_nominas,$ld_fecgen);
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Procesó el Interes del Fideicomiso asociado al Año ".$ai_anocurper." Mes ".$as_mescurper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		if($lb_valido)
		{	
			$this->io_mensajes->message("El Interés del Fideicomiso fue procesado.");
			$this->io_sql->commit();
		}
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("Ocurrio un error al procesar el interés del fideicomiso."); 
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_procesar_fideicomiso_intereses
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_fideiperiodointereses($as_anocurper,$ai_mescurper,$aa_nominas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_fideiperiodointereses
		//		   Access: private
		//	    Arguments: as_codnom  // Código de Nómina
		//	    		   as_codper  // Código de Personal
		//	    		   as_anocurper  // año en curso fideicomiso
		//	    		   ai_mescurper  // mes en curso fideicomiso
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el interes de fideiperiodo existe
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/10/2010 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$li_totnom=count($aa_nominas);
		for($li_i=0;$li_i<$li_totnom;$li_i++)
		{
			if($li_i==0)
			{
				$ls_codnom=" AND ((codnom='".$aa_nominas[$li_i]."')";
			}
			else
			{
				$ls_codnom=$ls_codnom." OR (codnom='".$aa_nominas[$li_i]."')";
			}
		}
		$ls_codnom=$ls_codnom.") ";
		if($ai_mescurper==1)
		{
			$ai_mescurper=12;
			$as_anocurper=intval($as_anocurper)-1;
		}
		else
		{
			$ai_mescurper=$ai_mescurper-1;
		}
		$ls_sql="SELECT codnom ".
				"  FROM sno_fideiperiodo ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND anocurper='".$as_anocurper."' ".
				"   AND mescurper=".$ai_mescurper." ".
				$ls_codnom.
				" GROUP BY codnom ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Fideicomiso Intereses MÉTODO->uf_verificar_fideiperiodointereses ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if($rs_data->EOF)
			{
				$lb_existe=true; // coloco true por que como no existe el fideicomiso no hay problema
			}
			else
			{
				while ((!$rs_data->EOF) && ($lb_existe))
				{
					$as_codnom = $rs_data->fields["codnom"]; 
					$ls_sql="SELECT codper ".
							"  FROM sno_fideiperiodointereses ".
							" WHERE codemp='".$this->ls_codemp."' ".
							"   AND codnom='".$as_codnom."' ".
							"   AND anocurper='".$as_anocurper."' ".
							"   AND mescurper=".$ai_mescurper." ";
					$rs_detalle=$this->io_sql->select($ls_sql);
					if($rs_detalle===false)
					{
						$this->io_mensajes->message("CLASE->Fideicomiso Intereses MÉTODO->uf_verificar_fideiperiodointereses ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
						$lb_existe=false;
					}
					else
					{
						if($rs_detalle->EOF)
						{
							$lb_existe=false;
						}
					}
					$rs_data->MoveNext();
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}// end function uf_verificar_fideiperiodointereses
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_fideiperiodointereses_posteriores($as_anocurper,$ai_mescurper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_fideiperiodointereses_posteriores
		//		   Access: private
		//	    Arguments: as_codnom  // Código de Nómina
		//	    		   as_codper  // Código de Personal
		//	    		   as_anocurper  // año en curso fideicomiso
		//	    		   ai_mescurper  // mes en curso fideicomiso
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el interes de fideiperiodo existe
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/10/2010 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		if($ai_mescurper==12)
		{
			$ai_mescurper=1;
			$as_anocurper=intval($as_anocurper)+1;
		}
		else
		{
			$ai_mescurper=$ai_mescurper+1;
		}
		$ls_sql="SELECT codper ".
				"  FROM sno_fideiperiodointereses ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND anocurper='".$as_anocurper."' ".
				"   AND mescurper=".$ai_mescurper." ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Fideicomiso Intereses MÉTODO->uf_verificar_fideiperiodointereses_posteriores ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$rs_data->EOF)
			{
				$lb_existe=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}// end function uf_verificar_fideiperiodointereses_posteriores
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_fideiperiodointereses($ai_anocurper,$as_mescurper,$aa_nominas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_fideiperiodointereses
		//		   Access: private
		//	    Arguments: ai_anocurper  // año en curso seleccionado
		//	    		   as_mescurper  // mes en curso seleccionado
		//	    		   aa_nominas  // arreglo de Nóminas seleccionadas
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina en la tabla de fideiperiodointereses
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/10/2010 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_totnom=count($aa_nominas);
		for($li_i=0;$li_i<$li_totnom;$li_i++)
		{
			if($li_i==0)
			{
				$ls_codnom=" AND ((codnom='".$aa_nominas[$li_i]."')";
			}
			else
			{
				$ls_codnom=$ls_codnom." OR (codnom='".$aa_nominas[$li_i]."')";
			}
		}
		$ls_codnom=$ls_codnom.") ";
		$ls_sql="DELETE ".
				"  FROM sno_fideiperiodointereses ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND anocurper='".$ai_anocurper."'".
				"   AND mescurper=".$as_mescurper." ".
				$ls_codnom;
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_delete_fideiperiodointereses ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
    }// end function uf_delete_fideiperiodointereses
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_personal_intereses($ai_anocurper,$as_mescurper,$aa_nominas,$ad_fecgen)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_personal_intereses
		//		   Access: public 
		//	    Arguments: ai_anocurper // año en curso del periodo
		//	    		   as_mescurper // mes en curso del período
		//	    		   aa_nominas // arreglo de Nóminas 
		//	    		   ad_fecgen // fecha a generar el fideicomiso
		//	      Returns: lb_valido True si se ejecuto el proceso de fideicomiso ó False si hubo error en el proceso
		//	  Description: Función que procesa los intereses del fideicomiso a todas las personas que están en las nóminas seleccionadas
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/10/2010 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($as_mescurper==1)
		{
			$ai_mes=12;
			$as_anio=intval($ai_anocurper)-1;
		}
		else
		{
			$ai_mes=$as_mescurper-1;
			$as_anio=intval($ai_anocurper);
		}
		$li_totnom=count($aa_nominas);
		for($li_i=0;$li_i<$li_totnom;$li_i++)
		{
			if($li_i==0)
			{
				$ls_codnom=" AND ((codnom='".$aa_nominas[$li_i]."')";
			}
			else
			{
				$ls_codnom=$ls_codnom." OR (codnom='".$aa_nominas[$li_i]."')";
			}
		}
		$ls_codnom=$ls_codnom.") ";

		$ls_criterio="";
		$li_calintper=trim($this->io_sno->uf_select_config("SNO","NOMINA","CALCULO_INT_PERSONAL_CONF","0","I"));
		if($li_calintper=="1")
		{
			$ls_criterio="  AND codper IN (SELECT codper FROM sno_fideicomiso WHERE codemp = '".$this->ls_codemp."' AND calintfid='1' )";
		}
		$ls_sql="SELECT codnom, codper, apoper, ".
				"		(SELECT montasint ".
				"		  FROM sno_fideiintereses ".
				"        WHERE codemp = '".$this->ls_codemp."' ".
				"          AND anoint = '".$ai_anocurper."' ".
				"          AND mesint = ".$as_mescurper.") AS montasint, ".
				"		(SELECT moncap ".
				"		  FROM sno_fideiperiodointereses ".
				"        WHERE sno_fideiperiodointereses.codemp = '".$this->ls_codemp."' ".
				"          AND sno_fideiperiodointereses.anocurper = '".$as_anio."' ".
				"          AND sno_fideiperiodointereses.mescurper = ".$ai_mes."".
				"          AND sno_fideiperiodointereses.codemp = sno_fideiperiodo.codemp  ".
				"          AND sno_fideiperiodointereses.codnom = sno_fideiperiodo.codnom  ".
				"          AND sno_fideiperiodointereses.codper = sno_fideiperiodo.codper ) AS moncap, ".
				"		(SELECT (monpreant+monint) ".
				"		  FROM sno_deudaanterior ".
				"        WHERE sno_deudaanterior.codemp = '".$this->ls_codemp."' ".
				"          AND sno_deudaanterior.feccordeu <= '".$as_anio."-".str_pad($ai_mes,2,"0",0)."-01' ".
				"          AND sno_deudaanterior.codemp = sno_fideiperiodo.codemp  ".
				"          AND sno_deudaanterior.codper = sno_fideiperiodo.codper ) AS deudaanterior ".
				"  FROM sno_fideiperiodo ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				$ls_codnom.
				"	AND anocurper = '".$ai_anocurper."' ".
				"	AND mescurper = ".$as_mescurper." ".
				$ls_criterio.
				" ORDER BY codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Fideicomiso Intereses MÉTODO->uf_procesar_personal_intereses ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$li_nprocesados=0;
			$li_montasint=number_format($rs_data->fields["montasint"],2,".","");
			if($li_montasint<=0)
			{
				$lb_valido=false;
				$this->io_mensajes->message("No esta definida la tasa de interés para este Año y Mes."); 
			}
			$li_montasint=number_format($li_montasint/100,4,".","");
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$li_nprocesados++;
				$ls_codper=$rs_data->fields["codper"];
				$ls_codnom=$rs_data->fields["codnom"];
				$li_apoper=number_format($rs_data->fields["apoper"],2,".","");
				$li_moncap=number_format($rs_data->fields["moncap"],2,".","");
				if($li_moncap==0)
				{
					$li_moncap=number_format($rs_data->fields["deudaanterior"],2,".","");
				}
				$li_monint=number_format($li_monint,2,".","");
				$li_monantacu = ($li_apoper+$li_moncap);
				$li_monint=(($li_monantacu*$li_montasint)/365)*30;
				$li_monint=number_format($li_monint,2,".","");
				$li_moncap = number_format(($li_monantacu + $li_monint),2,".","");
				if($this->uf_select_fideiperiodointereses($ls_codnom,$ls_codper,$ai_anocurper,$as_mescurper)==false)
				{
					$ls_sql="INSERT INTO sno_fideiperiodointereses ".
							"(codemp,codnom,codper,anocurper,mescurper,monantacu,monant,porint,monint,moncap)VALUES ".
							"('".$this->ls_codemp."','".$ls_codnom."','".$ls_codper."','".$ai_anocurper."',".$as_mescurper.",".
							"".$li_monantacu.",".$li_apoper.",".$li_montasint.",".$li_monint.",".$li_moncap.")";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Fideicomiso Intereses MÉTODO->uf_procesar_personal_intereses ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);	
			if($li_nprocesados==0)	
			{
				$this->io_mensajes->message("No hay personal para procesar."); 
			}
		}
		return $lb_valido;
	}// end function uf_procesar_personal_intereses
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_fideiperiodointereses($as_codnom,$as_codper,$as_anocurper,$ai_mescurper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_fideiperiodointereses
		//		   Access: private
		//	    Arguments: as_codnom  // Código de Nómina
		//	    		   as_codper  // Código de Personal
		//	    		   as_anocurper  // año en curso fideicomiso
		//	    		   ai_mescurper  // mes en curso fideicomiso
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el interes de fideiperiodo existe
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/10/2010 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codper ".
				"  FROM sno_fideiperiodointereses ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$as_codnom."' ".
				"   AND codper='".$as_codper."' ".
				"   AND anocurper='".$as_anocurper."' ".
				"   AND mescurper=".$ai_mescurper." ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Fideicomiso Intereses MÉTODO->uf_select_fideiperiodointereses ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if($rs_data->EOF)
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}// end function uf_select_fideiperiodointereses
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_fideiperiodointereses($ai_anocurper,$as_mescurper,$aa_nominas,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_fideiperiodointereses
		//		   Access: public (sigesp_snorh_p_fideicomiso_intereses)
		//	    Arguments: ai_anocurper  // código de la tabla de vacacion
		//				   as_mescurper  // total de filas del detalle
		//				   aa_nominas  // objetos del detalle
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//                 as_sueint  // denominación del sueldo integral
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene el interes del fideicomiso del período
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/10/2010 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_totnom=count($aa_nominas);
		for($li_i=0;$li_i<$li_totnom;$li_i++)
		{
			if($li_i==0)
			{
				$ls_codnom=" AND ((sno_fideiperiodointereses.codnom='".$aa_nominas[$li_i]."')";
			}
			else
			{
				$ls_codnom=$ls_codnom." OR (sno_fideiperiodointereses.codnom='".$aa_nominas[$li_i]."')";
			}
		}
		$ls_codnom=$ls_codnom.") ";
		$ls_sql="SELECT sno_fideiperiodointereses.codemp, sno_fideiperiodointereses.codnom, sno_fideiperiodointereses.codper, sno_fideiperiodointereses.anocurper, ".
				"	    sno_fideiperiodointereses.mescurper, sno_fideiperiodointereses.monant, sno_fideiperiodointereses.porint, sno_fideiperiodointereses.monint, ".
				"		sno_fideiperiodointereses.monantacu, sno_fideiperiodointereses.moncap, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper ".
				"  FROM sno_fideiperiodointereses, sno_personal ".
				" WHERE sno_fideiperiodointereses.codemp='".$this->ls_codemp."' ".
				"   AND sno_fideiperiodointereses.anocurper='".$ai_anocurper."'".
				"   AND sno_fideiperiodointereses.mescurper=".$as_mescurper." ".
				$ls_codnom.
				"   AND sno_fideiperiodointereses.codemp=sno_personal.codemp ".
				"   AND sno_fideiperiodointereses.codper=sno_personal.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Fideicomiso Intereses MÉTODO->uf_load_fideiperiodointereses ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while(!$rs_data->EOF)
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codper=$rs_data->fields["codper"];
				$ls_cedper=$rs_data->fields["cedper"];
				$ls_nomper=$rs_data->fields["apeper"].", ".$rs_data->fields["nomper"];
				$li_monant=$this->io_fun_nomina->uf_formatonumerico($rs_data->fields["monant"]);
				$li_monantacu=$this->io_fun_nomina->uf_formatonumerico($rs_data->fields["monantacu"]);
				$li_moncap=$this->io_fun_nomina->uf_formatonumerico($rs_data->fields["moncap"]);
				$li_porint=$this->io_fun_nomina->uf_formatonumerico($rs_data->fields["porint"]*100);
				$li_monint=$this->io_fun_nomina->uf_formatonumerico($rs_data->fields["monint"]);

				$ao_object[$ai_totrows][1]="<div align='center'>".$ls_codper."</div>";
				$ao_object[$ai_totrows][2]="<div align='center'>".$ls_cedper."</div>";
				$ao_object[$ai_totrows][3]="<div align='left'>".$ls_nomper."</div>";
				$ao_object[$ai_totrows][4]="<div align='right'>".$li_monant."</div>";
				$ao_object[$ai_totrows][5]="<div align='right'>".$li_monantacu."</div>";
				$ao_object[$ai_totrows][6]="<div align='right'>".$li_porint."</div>";
				$ao_object[$ai_totrows][7]="<div align='right'>".$li_monint."</div>";
				$ao_object[$ai_totrows][8]="<div align='right'>".$li_moncap."</div>";
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_fideiperiodointereses
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_fideiperiodo_intereses($ai_anocurper,$as_mescurper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_fideiperiodo_intereses
		//		   Access: private
		//	    Arguments: ai_anocurper  // año en curso seleccionado
		//	    		   as_mescurper  // mes en curso seleccionado
		//	    		   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina en la tabla de intereses de fideiperiodo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/10/2010 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		if($this->uf_verificar_fideiperiodointereses_posteriores($ai_anocurper,$as_mescurper))
		{
			$this->io_mensajes->message("Los Meses Posteriores ya están calculados. No se pueden eliminar los intereses");
			$lb_valido=false;
		}
		if ($lb_valido)
		{
			$ls_sql="DELETE ".
					"  FROM sno_fideiperiodointereses ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND anocurper='".$ai_anocurper."'".
					"   AND mescurper=".$as_mescurper."";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Fideicomiso Intereses MÉTODO->uf_delete_fideiperiodo_intereses ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		if ($lb_valido)
		{
			$ls_comprobante=$this->io_conexion->Concat($ai_anocurper,"'-'",'codnom',"'-'",'codperi',"'-K'");
			$ls_sql="DELETE ".
					"  FROM sno_dt_spg ".
					" WHERE sno_dt_spg.codemp='".$this->ls_codemp."'".
					"   AND sno_dt_spg.tipnom='K'".
					"   AND sno_dt_spg.estatus=0".
					"	AND sno_dt_spg.codcom IN (SELECT ".$ls_comprobante." ".
					"								FROM sno_periodo ".
					" 							   WHERE sno_periodo.codemp='".$this->ls_codemp."'".
					"   						     AND SUBSTR(cast(sno_periodo.fecdesper as char(10)),1,4)='".$ai_anocurper."' ".	
					"   							 AND SUBSTR(cast(sno_periodo.fecdesper as char(10)),6,2)='".str_pad($as_mescurper,2,"0",0)."') ";	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Fideicomiso Intereses MÉTODO->uf_delete_fideiperiodo_intereses ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		if ($lb_valido)
		{
			$ls_sql="DELETE ".
					"  FROM sno_dt_scg ".
					" WHERE sno_dt_scg.codemp='".$this->ls_codemp."'".
					"   AND sno_dt_scg.tipnom='K'".
					"   AND sno_dt_scg.estatus=0".
					"	AND sno_dt_scg.codcom IN (SELECT ".$ls_comprobante." ".
					"								FROM sno_periodo ".
					" 							   WHERE sno_periodo.codemp='".$this->ls_codemp."'".
					"   						     AND SUBSTR(cast(sno_periodo.fecdesper as char(10)),1,4)='".$ai_anocurper."' ".	
					"   							 AND SUBSTR(cast(sno_periodo.fecdesper as char(10)),6,2)='".str_pad($as_mescurper,2,"0",0)."') ";	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Fideicomiso Intereses MÉTODO->uf_delete_fideiperiodo_intereses ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		if ($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Interes del Fideicomiso asociado al Año ".$ai_anocurper." Mes ".$as_mescurper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		if($lb_valido)
		{	
			$this->io_mensajes->message("El Interes del Fideicomiso fue Eliminado.");
			$this->io_sql->commit();
		}
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("Ocurrio un error al eliminar los intereses."); 
			$this->io_sql->rollback();
		}
		return $lb_valido;
    }// end function uf_delete_fideiperiodointereses
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_generar_data_contabilizacion($ai_anocurper,$as_mescurper,$aa_nominas,$ad_fecgen)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_generar_data_contabilizacion 
		//	    Arguments: ai_anocurper  //  Año en curso
		//	    		   as_mescurper  //  Mes 
		//	    		   aa_nominas  //  Arreglo de Nóminas
		//	    		   ad_fecgen  //  Fecha en que se genro el fideicomiso
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de generar la data para la contabilización de los intereses de fideicomiso
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 31/05/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$li_totnom=count($aa_nominas);
		for($li_i=0;($li_i<$li_totnom)&&($lb_valido);$li_i++)
		{
			$ls_codnom=$aa_nominas[$li_i];
			$ls_operacion="OCP";
			$ls_tipodestino="B";
			$ls_codpro="----------";
			$ls_codben="";
			$li_genrecdoc="0";
			$li_tipdoc="";
			$ls_anocurnom="";
			$ls_desnom="";
			$lb_valido=$this->load_nomina($ls_codnom,$ai_anocurper,$as_mescurper,&$ls_desnom,&$ls_anocurnom,&$ls_codben);
			$ls_comprobante=$ls_anocurnom."-".$ls_codnom."-".str_pad($as_mescurper,3,"0",0)."-K"; // Comprobante de Fideicomiso
			$ls_descripcion=$ls_desnom." INTERESES PRESTACIÓN ANTIGUEDAD - MES ".$as_mescurper." del Año ".$ai_anocurper; // Descripción de Conceptos
			// Obtenemos la configuración de la contabilización del Fideicomiso
			$lb_valido=$this->uf_load_configuracion_contabilizacion($ls_codben);
			if($lb_valido)
			{	// eliminamos la contabilización anterior 
				$lb_valido=$this->uf_delete_contabilizacion($ls_comprobante);
				
			}														
			if($lb_valido)
			{ // insertamos la contabilización de presupuesto de conceptos
				$lb_valido=$this->uf_contabilizar_conceptos_spg($ls_codnom,$as_mescurper,$ls_comprobante,$ls_operacion,$ls_codpro,$ls_codben,$ls_tipodestino,
																$ls_descripcion,$li_genrecdoc,$li_tipdoc,$ls_anocurnom);
			}
			if($lb_valido)
			{// insertamos la contabilización de contabilidad de conceptos
				if($ls_operacion!="O")// Si es compromete no genero detalles contables
				{
					$lb_valido=$this->uf_contabilizar_conceptos_scg($ls_codnom,$as_mescurper,$ls_comprobante,$ls_operacion,$ls_codpro,$ls_codben,$ls_tipodestino,
																	$ls_descripcion,$li_genrecdoc,$li_tipdoc,$ls_cuentapasivo,$ls_anocurnom);
				}
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_verificar_contabilizacion($ls_comprobante); // Nómina
			}
		}
		// Se coloca en true por que a pesar de que esta data no se genere de debe crear el fideicomiso
	   	$lb_valido=true;
		return  $lb_valido;    
	}// end function uf_generar_data_contabilizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function load_nomina($as_codnom,$ai_anocurper,$as_mes,&$as_desnom,&$as_anocurnom,&$as_codbenfid)
	{
		/////////////////////////////////////////////////////////////////////////////////
		//	     Function: load_nomina
		//	    Arguments: as_codnom// codigo de la nomina
		//                 ai_anocurper // Año en curso del fideicomiso
		//                 as_mes // Mes en curso del fideicomiso
		//                 as_desnom // denominacion de la nomina
		//                 as_anocurnom  //  año en curso
		//	  			   as_confidnom  //  Modo de Contabilización
		//	    		   as_codbenfid  //  Código del Beneficiario
		//	      Returns: True si hizo el select correctamente o False en caso contrario
		//	  Description: Funcion que me devuelve los datos de  la nomina
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 21/10/2010
		//////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT desnom, anocurnom, codbenfid ".
			    "  FROM sno_hnomina ".
				"  INNER JOIN sno_hperiodo ".
				"    ON sno_hnomina.codemp = sno_hperiodo.codemp ".
				"	AND sno_hnomina.codnom = sno_hperiodo.codnom ".
				"	AND sno_hnomina.anocurnom = sno_hperiodo.anocur ".
				"	AND sno_hnomina.peractnom = sno_hperiodo.codperi ".
			    " WHERE sno_hnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_hnomina.codnom='".$as_codnom."' ".
				"   AND sno_hnomina.anocurnom='".$ai_anocurper."' ".
				"   AND SUBSTR(cast(fecdesper as char(10)),6,2)='".$as_mes."'";
	   $rs_data=$this->io_sql->select($ls_sql);
	   if($rs_data===false)
	   {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Nómina MÉTODO->load_nomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
	   }
	   else
	   {
		   if(!$rs_data->EOF)
		   {
				$as_desnom=$rs_data->fields["desnom"];
				$as_anocurnom=$rs_data->fields["anocurnom"];
				$as_codbenfid=$rs_data->fields["codbenfid"];
		   }
	   }
	   return $lb_valido;   
	}// end function load_nomina
	//-----------------------------------------------------------------------------------------------------------------------------------	 

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_configuracion_contabilizacion(&$as_codben)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_configuracion_contabilizacion 
		//	    Arguments: as_codben  // código de beneficiario
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que busca los datos de la configuración de la contabilización de los Intereses de Prestación Antiguedad
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 21/10/2010
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$li_parametros=$this->io_sno->uf_select_config("SNO","CONFIG","CONTA GLOBAL","0","I");
		switch($li_parametros)
		{
			case 0: // La contabilización es global
				$as_codben=trim($this->io_sno->uf_select_config("SNO","NOMINA","DESTINO FIDEICOMISO","----------","C"));
		}
		if(trim($as_codben)=="")
		{
			$lb_valido=false;
			$this->io_mensajes->message("ERROR-> Debe Seleccionar un Beneficiario. ");
		}
		return  $lb_valido;    
	}// end function uf_load_configuracion_contabilizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_contabilizacion($as_comprobante)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_contabilizacion
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina la contabilización de la Prestación Antiguedad
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 20/10/2010
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_dt_spg ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codcom='".$as_comprobante."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Fideicomiso Intereses MÉTODO->uf_delete_contabilizacion_spg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		if($lb_valido)
		{
			$ls_sql="DELETE ".
					"  FROM sno_dt_scg ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codcom='".$as_comprobante."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Fideicomiso Intereses MÉTODO->uf_delete_contabilizacion_scg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}		
		}
		return $lb_valido;
    }// end function uf_delete_contabilizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contabilizar_conceptos_spg($as_codnom,$as_mescurper,$as_comprobante,$as_operacion,$as_codpro,$as_codben,$as_tipodestino,
										   $as_descripcion,$ai_genrecdoc,$ai_tipdoc,$as_anocurnom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contabilizar_conceptos_spg 
		//	    Arguments: as_codnom  //  Código de Nómina
		//	    		   as_mescurper  //  Mes
		//	    		   as_comprobante  //  Código de Comprobante
		//	    		   as_operacion  //  Operación de la contabilización
		//	    		   as_codpro  //  codigo del proveedor
		//	    		   as_codben  //  codigo del beneficiario
		//	    		   as_tipodestino  //  Tipo de destino de contabiliación
		//	    		   as_descripcion  //  descripción del comprobante
		//	    		   ai_genrecdoc  //  Generar recepción de documento
		//	    		   ai_tipdoc  //  Generar Tipo de documento
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización del Interes Fideicomiso
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 22/10/2010
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$ls_tipnom="K"; // tipo de contabilización
		$ls_sql="SELECT MAX(sno_fideiperiodointereses.monint) AS total, MAX(spg_cuentas.spg_cuenta) AS spg_cuenta, ".
				"       sno_hunidadadmin.estcla, sno_hunidadadmin.codestpro1, sno_hunidadadmin.codestpro2, ".
				"		sno_hunidadadmin.codestpro3, sno_hunidadadmin.codestpro4,sno_hunidadadmin.codestpro5 ".
				"  FROM sno_fideiperiodointereses  ".
				" INNER JOIN (sno_hpersonalnomina ".
				" 		INNER JOIN sno_hperiodo ".
				"    		ON sno_hpersonalnomina.codemp=sno_hperiodo.codemp ".
				"   	   AND sno_hpersonalnomina.codnom=sno_hperiodo.codnom ".
				"   	   AND sno_hpersonalnomina.anocur=sno_hperiodo.anocur ".
				"   	   AND sno_hpersonalnomina.codperi=sno_hperiodo.codperi) ".
				"    ON sno_fideiperiodointereses.codemp=sno_hpersonalnomina.codemp ".
				"   AND sno_fideiperiodointereses.codnom=sno_hpersonalnomina.codnom ".
				"   AND sno_fideiperiodointereses.anocurper=sno_hpersonalnomina.anocur ".
				"   AND sno_fideiperiodointereses.codper=sno_hpersonalnomina.codper ".
				" INNER JOIN sno_fideiconfigurable ".
				"    ON sno_hpersonalnomina.codemp=sno_fideiconfigurable.codemp ".
				"   AND sno_hpersonalnomina.anocur=sno_fideiconfigurable.anocurfid ". 
				"   AND sno_hpersonalnomina.codded=sno_fideiconfigurable.codded ". 
				"   AND sno_hpersonalnomina.codtipper=sno_fideiconfigurable.codtipper ".
				" INNER JOIN sno_hunidadadmin ".
				"    ON sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				" INNER JOIN spg_cuentas ".
				"    ON sno_fideiconfigurable.codemp=spg_cuentas.codemp ".
				"   AND sno_fideiconfigurable.cueprefid=spg_cuentas.spg_cuenta ".
				" WHERE sno_fideiperiodointereses.codemp='".$this->ls_codemp."' ".
				"   AND sno_fideiperiodointereses.codnom='".$as_codnom."' ".
				"   AND sno_fideiperiodointereses.anocurper='".$as_anocurnom."' ".
				"   AND sno_fideiperiodointereses.mescurper='".$as_mescurper."' ".
				"   AND sno_fideiperiodointereses.monint>0 ".
				"   AND SUBSTR(cast(sno_hperiodo.fecdesper as char(10)),6,2)='".$as_mescurper."' ".
				" GROUP BY sno_fideiperiodointereses.codper, sno_hunidadadmin.codestpro1, sno_hunidadadmin.codestpro2, ".
				"		sno_hunidadadmin.codestpro3, sno_hunidadadmin.codestpro4,sno_hunidadadmin.codestpro5, sno_hunidadadmin.estcla  ". 
				" ORDER BY sno_hunidadadmin.codestpro1, sno_hunidadadmin.codestpro2, ".
				"		sno_hunidadadmin.codestpro3, sno_hunidadadmin.codestpro4,sno_hunidadadmin.codestpro5, sno_hunidadadmin.estcla";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Fideicomiso Intereses MÉTODO->uf_contabilizar_conceptos_spg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
				$li_totrow=$this->DS->getRowCount("spg_cuenta");
				$this->DS->group_by(array('0'=>'codestpro1','1'=>'codestpro2','2'=>'codestpro3','3'=>'codestpro4','4'=>'codestpro5','5'=>'estcla','6'=>'spg_cuenta'),array('0'=>'total'),'total');
				$li_totrow=$this->DS->getRowCount("spg_cuenta");
				for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
				{
					$ls_codestpro1=$this->DS->data["codestpro1"][$li_i];
					$ls_codestpro2=$this->DS->data["codestpro2"][$li_i];
					$ls_codestpro3=$this->DS->data["codestpro3"][$li_i];
					$ls_codestpro4=$this->DS->data["codestpro4"][$li_i];
					$ls_codestpro5=$this->DS->data["codestpro5"][$li_i];
					$ls_estcla=$this->DS->data["estcla"][$li_i];
					$ls_cueprecon=$this->DS->data["spg_cuenta"][$li_i];
					$li_total=round($this->DS->data["total"][$li_i],2);
					$ls_codconc="0000000001";
					$ls_codcomapo="0000000001";
					$lb_valido=$this->uf_insert_contabilizacion_spg($as_comprobante,$as_operacion,$as_codpro,$as_codben,$as_tipodestino,
																	$as_descripcion,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
																	$ls_codestpro5,$ls_estcla,$ls_cueprecon,$li_total,$ls_tipnom,$ls_codconc,$ai_genrecdoc,
																	$ai_tipdoc,0,0,$ls_codcomapo,$as_codnom,$as_mescurper);
				}
				$this->DS->resetds("spg_cuenta");
			}
			$this->io_sql->free_result($rs_data);
		}		
		return  $lb_valido;    
	}// end function uf_contabilizar_conceptos_spg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_contabilizacion_spg($as_codcom,$as_operacionnomina,$as_codpro,$as_codben,$as_tipodestino,$as_descripcion,
									 	   $as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,
										   $as_cueprecon,$ai_monto,$as_tipnom,$as_codconc,$ai_genrecdoc,$ai_tipdoc,
										   $ai_gennotdeb,$ai_genvou,$as_codcomapo,$as_codnom,$as_mescurper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_contabilizacion_spg
		//		   Access: private
		//	    Arguments: as_codcom  //  Código de Comprobante
		//	    		   as_operacionnomina  //  Operación de la contabilización
		//	    		   as_codpro  //  codigo del proveedor
		//	    		   as_codben  //  codigo del beneficiario
		//	    		   as_tipodestino  //  Tipo de destino de contabiliación
		//	    		   as_descripcion  //  descripción del comprobante
		//	    		   as_programatica  //  Programática
		//	    		   as_cueprecon  //  cuenta presupuestaria
		//	    		   ai_monto  //  monto total
		//	    		   as_tipnom  //  Tipo de contabilizacion si es de nómina ó de aporte
		//			       as_codconc // código del concepto
		//	    		   ai_genrecdoc  //  Generar recepción de documento
		//	    		   ai_tipdoc  //  Generar Tipo de documento
		//	    		   ai_gennotdeb  //  generar nota de débito
		//	    		   ai_genvou  //  generar número de voucher
		//	    		   as_codcomapo  //  Código del comprobante de aporte
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el total des las cuentas presupuestarias
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_estatus=0; // No contabilizado
		$ls_sql="INSERT INTO sno_dt_spg(codemp,codnom,codperi,codcom,tipnom,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,".
				"spg_cuenta,operacion,codconc,cod_pro,ced_bene,tipo_destino,descripcion,monto,estatus,estrd,codtipdoc,estnumvou,".
				"estnotdeb,codcomapo,estcla,codfuefin) VALUES ('".$this->ls_codemp."','".$as_codnom."','".str_pad($as_mescurper,3,"0",0)."','".$as_codcom."',".
				"'".$as_tipnom."','".$as_codestpro1."','".$as_codestpro2."','".$as_codestpro3."','".$as_codestpro4."','".$as_codestpro5."',".
				"'".$as_cueprecon."','".$as_operacionnomina."','".$as_codconc."','".$as_codpro."','".$as_codben."','".$as_tipodestino."',".
				"'".$as_descripcion."',".$ai_monto.",".$li_estatus.",".$ai_genrecdoc.",'".$ai_tipdoc."',".$ai_genvou.",".$ai_gennotdeb.",".
				"'".$as_codcomapo."','".$as_estcla."','--')";	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Fideicomiso MÉTODO->uf_insert_contabilizacion_spg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
	}// end function uf_insert_contabilizacion_spg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contabilizar_conceptos_scg($as_codnom,$as_mescurper,$as_comprobante,$as_operacion,$as_codpro,$as_codben,$as_tipodestino,
										   $as_descripcion,$ai_genrecdoc,$ai_tipdoc,$as_cuentapasivo,$as_anocurnom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contabilizar_conceptos_scg 
		//	    Arguments: as_codnom  //  Código de Nómina
		//	    		   as_mescurper  //  Mes
		//	    		   as_comprobante  //  Código de Comprobante
		//	    		   as_operacion  //  Operación de la contabilización
		//	    		   as_codpro  //  codigo del proveedor
		//	    		   as_codben  //  codigo del beneficiario
		//	    		   as_tipodestino  //  Tipo de destino de contabiliación
		//	    		   as_descripcion  //  descripción del comprobante
		//	    		   ai_genrecdoc  //  Generar recepción de documento
		//	    		   ai_tipdoc  //  Generar Tipo de documento
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 09/05/2008
		///////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_tipnom="K";
		$ls_sql="SELECT sno_fideiperiodointereses.codper, MAX(sno_fideiperiodointereses.monint) AS total, MAX(scg_cuentas.sc_cuenta) AS sc_cuenta, CAST('D' AS char(1)) as operacion ".
			"  FROM sno_fideiperiodointereses  ".
			" INNER JOIN (sno_hpersonalnomina ".
			" 		INNER JOIN sno_hperiodo ".
			"    		ON sno_hpersonalnomina.codemp=sno_hperiodo.codemp ".
			"   	   AND sno_hpersonalnomina.codnom=sno_hperiodo.codnom ".
			"   	   AND sno_hpersonalnomina.anocur=sno_hperiodo.anocur ".
			"   	   AND sno_hpersonalnomina.codperi=sno_hperiodo.codperi) ".
			"    ON sno_fideiperiodointereses.codemp=sno_hpersonalnomina.codemp ".
			"   AND sno_fideiperiodointereses.codnom=sno_hpersonalnomina.codnom ".
			"   AND sno_fideiperiodointereses.anocurper=sno_hpersonalnomina.anocur ".
			"   AND sno_fideiperiodointereses.codper=sno_hpersonalnomina.codper ".
			" INNER JOIN sno_fideiconfigurable ".
			"    ON sno_hpersonalnomina.codemp=sno_fideiconfigurable.codemp ".
			"   AND sno_hpersonalnomina.anocur=sno_fideiconfigurable.anocurfid ". 
			"   AND sno_hpersonalnomina.codded=sno_fideiconfigurable.codded ". 
			"   AND sno_hpersonalnomina.codtipper=sno_fideiconfigurable.codtipper ".
			" INNER JOIN sno_hunidadadmin ".
			"    ON sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
			"   AND sno_hpersonalnomina.codnom = sno_hunidadadmin.codnom ".
			"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
			"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
			"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
			"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
			"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
			"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
			"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
			" INNER JOIN spg_cuentas ".
			"    ON sno_fideiconfigurable.codemp=spg_cuentas.codemp ".
			"   AND sno_fideiconfigurable.cueprefid=spg_cuentas.spg_cuenta ".
			" INNER JOIN scg_cuentas ".
			"    ON spg_cuentas.codemp=scg_cuentas.codemp ".
			"   AND spg_cuentas.sc_cuenta=scg_cuentas.sc_cuenta ".
			" WHERE sno_fideiperiodointereses.codemp='".$this->ls_codemp."' ".
			"   AND sno_fideiperiodointereses.codnom='".$as_codnom."' ".
			"   AND sno_fideiperiodointereses.anocurper='".$as_anocurnom."' ".
			"   AND sno_fideiperiodointereses.mescurper='".$as_mescurper."' ".
			"   AND sno_fideiperiodointereses.monint>0 ".
			"   AND SUBSTR(cast(sno_hperiodo.fecdesper as char(10)),6,2)='".$as_mescurper."' ".
			" GROUP BY sno_fideiperiodointereses.codper ".
			" UNION ".
			"SELECT sno_fideiperiodointereses.codper, MAX(sno_fideiperiodointereses.monint) AS total, MAX(sno_fideicomiso.scg_cuentaintfid) AS sc_cuenta, CAST('H' AS char(1)) as operacion ".
			"  FROM sno_fideiperiodointereses  ".
			" INNER JOIN sno_hperiodo ".
			"    ON sno_hperiodo.codemp=sno_fideiperiodointereses.codemp ".
			"   AND sno_hperiodo.codnom=sno_fideiperiodointereses.codnom ".
			" INNER JOIN sno_hnomina ".
			"    ON sno_hperiodo.codemp=sno_hnomina.codemp ".
			"   AND sno_hperiodo.codnom=sno_hnomina.codnom ".
			"   AND sno_hperiodo.anocur=sno_hnomina.anocurnom ".
			"   AND sno_hperiodo.codperi=sno_hnomina.peractnom ".
			" INNER JOIN sno_fideicomiso ".
			"    ON sno_fideiperiodointereses.codemp=sno_fideicomiso.codemp ".
			"   AND sno_fideiperiodointereses.codper=sno_fideicomiso.codper ".
			" WHERE sno_fideiperiodointereses.codemp='".$this->ls_codemp."' ".
			"   AND sno_fideiperiodointereses.codnom='".$as_codnom."' ".
			"   AND sno_fideiperiodointereses.anocurper='".$as_anocurnom."' ".
			"   AND sno_fideiperiodointereses.mescurper='".$as_mescurper."' ".
			"   AND SUBSTR(cast(sno_hperiodo.fecdesper as char(10)),6,2)='".$as_mescurper."' ".
			"   AND sno_fideiperiodointereses.monint>0 ".
			" GROUP BY sno_fideiperiodointereses.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Fideicomiso Intereses MÉTODO->uf_contabilizar_conceptos_scg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
				$this->DS->group_by(array('0'=>'sc_cuenta','1'=>'operacion'),array('0'=>'total'),'total');
				$li_totrow=$this->DS->getRowCount("sc_cuenta");
				for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
				{
					$ls_cuenta=$this->DS->data["sc_cuenta"][$li_i];
					$ls_operacion=$this->DS->data["operacion"][$li_i];
					$li_total=abs(round($this->DS->data["total"][$li_i],2));
					$ls_codconc="0000000001";
					$ls_codcomapo="0000000001";
					$lb_valido=$this->uf_insert_contabilizacion_scg($as_comprobante,$as_codpro,$as_codben,$as_tipodestino,$as_descripcion,
																	$ls_cuenta,$ls_operacion,$li_total,$ls_tipnom,$ls_codconc,
																	$ai_genrecdoc,$ai_tipdoc,0,0,$ls_codcomapo,$as_codnom,$as_mescurper);
				}
				$this->DS->resetds("sc_cuenta");
			}
			$this->io_sql->free_result($rs_data);
		}	
		return $lb_valido;	  
	}// end function uf_contabilizar_conceptos_scg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_contabilizacion_scg($as_codcom,$as_codpro,$as_codben,$as_tipodestino,$as_descripcion,$as_cuenta,$as_operacion,
									 	   $ai_monto,$as_tipnom,$as_codconc,$ai_genrecdoc,$ai_tipdoc,$ai_gennotdeb,$ai_genvou,$as_codcomapo,
										   $as_codnom,$as_mescurper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_contabilizacion_scg
		//		   Access: private
		//	    Arguments: as_codcom  //  Código de Comprobante
		//	    		   as_operacionnomina  //  Operación de la contabilización
		//	    		   as_codpro  //  codigo del proveedor
		//	    		   as_codben  //  codigo del beneficiario
		//	    		   as_tipodestino  //  Tipo de destino de contabiliación
		//	    		   as_descripcion  //  descripción del comprobante
		//	    		   as_programatica  //  Programática
		//	    		   as_cueprecon  //  cuenta presupuestaria
		//	    		   ai_monto  //  monto total
		//	    		   as_tipnom  //  Tipo de contabilización es aporte ó de conceptos
		//	    		   ai_genrecdoc  //  Generar recepción de documento
		//	    		   as_codconc  //  Código de concepto
		//	    		   ai_tipdoc  //  Generar Tipo de documento
		//	    		   as_codcomapo  //  Código del comprobante de aporte
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el total des las cuentas presupuestarias
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/10/2010 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_estatus=0; // No contabilizado
		$ls_sql="INSERT INTO sno_dt_scg(codemp,codnom,codperi,codcom,tipnom,sc_cuenta,debhab,codconc,cod_pro,ced_bene,tipo_destino,".
				"descripcion,monto,estatus,estrd,codtipdoc,estnumvou,estnotdeb,codcomapo) VALUES ('".$this->ls_codemp."','".$as_codnom."',".
				"'".str_pad($as_mescurper,3,"0",0)."','".$as_codcom."','".$as_tipnom."','".$as_cuenta."','".$as_operacion."','".$as_codconc."',".
				"'".$as_codpro."','".$as_codben."','".$as_tipodestino."','".$as_descripcion."',".$ai_monto.",".$li_estatus.",".
				"'".$ai_genrecdoc."','".$ai_tipdoc."','".$ai_genvou."','".$ai_gennotdeb."','".$as_codcomapo."')";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Fideicomiso Intereses MÉTODO->uf_insert_contabilizacion_scg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
	}// end function uf_insert_contabilizacion_scg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_contabilizacion($as_codcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_contabilizacion 
		//	    Arguments: as_codcom  //  Código de Comprobante
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de verificar que lo mismo que esta por el debe tambien este por el haber en contabilidad
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 20/10/2010
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$ls_sql="SELECT debhab, sum(monto) as total ".
				"  FROM sno_dt_scg ".
				" WHERE codcom = '".$as_codcom."' ".
				" GROUP BY debhab ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Fideicomiso Intereses MÉTODO->uf_verificar_contabilizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$li_debe=0;
			$li_haber=0;
			while(!$rs_data->EOF)
			{
				$li_operacion=$rs_data->fields["debhab"];
				if($li_operacion=="D")
				{
					$li_debe=number_format($rs_data->fields["total"],2,".","");
				}
				else
				{
					$li_haber=number_format($rs_data->fields["total"],2,".","");
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
			if($li_debe!=$li_haber)
			{
				$lb_valido=false;
				$this->io_mensajes->message("Los Monto en la Contabilización de Prestación Antiguedad no cuadran. Debe=".$this->io_fun_nomina->uf_formatonumerico($li_debe)." Haber ".$this->io_fun_nomina->uf_formatonumerico($li_haber).". Verifique la información ");
			}
		}		
		return  $lb_valido;    
	}// end function uf_verificar_contabilizacion
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>