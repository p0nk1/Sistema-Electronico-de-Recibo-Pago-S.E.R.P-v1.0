<?php
class sigesp_snorh_c_sueldoshistoricos
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
	function sigesp_snorh_c_sueldoshistoricos()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_sueldoshistoricos
		//		   Access: public (sigesp_snorh_d_sueldoshistoricos)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 15/11/2010 								Fecha �ltima Modificaci�n : 
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
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		
	}// end function sigesp_snorh_c_sueldoshistoricos
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_sueldoshistoricos)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 15/11/2010 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fun_nomina);
        unset($this->ls_codemp);
        
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_sueldoshistorios($as_codper,$ad_fecsue)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_sueldoshistorios
		//		   Access: private
		//   	Arguments: as_codper  // C�digo del Personal
		//	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que verifica si el Sueldo Historico est� registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 15/11/2010 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codper ".
				"  FROM sno_sueldoshistoricos ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND fecsue='".$ad_fecsue."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Sueldos Hist�ricos M�TODO->uf_select_sueldoshistorios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_sueldoshistorios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_fideiperiodo($as_codper,$ad_fecsue)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_fideiperiodo
		//		   Access: private
		//   	Arguments: as_codper  // C�digo del Personal
		//	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que verifica si hay fideicomiso para este periodo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 15/11/2010 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$campo = $this->io_conexion->Concat("sno_fideiperiodo.anocurper","'-'","sno_fideiperiodo.mescurper","'-01'");
		$ls_anio=substr($ad_fecsue,0,4);
		$ls_mes=intval(substr($ad_fecsue,5,2));
		$ad_fecsue=$ls_anio."-".$ls_mes."-01";
		$ls_sql="SELECT codper ".
				"  FROM sno_fideiperiodo ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND $campo = '".$ad_fecsue."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Sueldos Hist�ricos M�TODO->uf_select_fideiperiodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_fideiperiodo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_sueldoshistorios($as_codper,$ad_fecsue,$ai_suebas,$ai_sueint,$ai_sueprodia,$as_codded,$as_codtipper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_sueldoshistorios
		//		   Access: private
		//	    Arguments: as_codper  // C�digo del Personal
		//				   ad_fecsue  // Fecha del sueldo
		//				   ai_suebas  // Sueldo Base
		//				   ai_sueint  // Sueldo Integral
		//				   ai_sueprodia  // Sueldo Promedio Diario
		//	      Returns: lb_valido True si se ejecuto el insert � False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla de Sueldos Hist�ricos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 15/11/2010 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_sueldoshistoricos ".
				"(codemp,codper,fecsue,suebas,sueint,sueprodia,codded,codtipper)VALUES".
				"('".$this->ls_codemp."','".$as_codper."','".$ad_fecsue."',".$ai_suebas.",".$ai_sueint.",".$ai_sueprodia.",".
				" '".$as_codded."','".$as_codtipper."')";
       	$this->io_sql->begin_transaction();
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Sueldos Hist�ricos M�TODO->uf_insert_sueldoshistorios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insert� el Sueldos Hist�ricos ".$as_codfid." asociado al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Sueldos Hist�ricos fue registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Sueldos Hist�ricos M�TODO->uf_insert_sueldoshistorios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_sueldoshistorios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_sueldoshistorios($as_codper,$ad_fecsue,$ai_suebas,$ai_sueint,$ai_sueprodia,$as_codded,$as_codtipper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_sueldoshistorios
		//		   Access: private
		//	    Arguments: as_codper  // C�digo del Personal
		//				   ad_fecsue  // Fecha del sueldo
		//				   ai_suebas  // Sueldo Base
		//				   ai_sueint  // Sueldo Integral
		//				   ai_sueprodia  // Sueldo Promedio Diario
		//	      Returns: lb_valido True si se ejecuto el update � False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla de Sueldos Hist�ricos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 15/11/2010 								Fecha �ltima Modificaci�n : 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_sueldoshistoricos ".
				"   SET suebas=".$ai_suebas.", ".
				"		sueint=".$ai_sueint.", ".
				"		sueprodia=".$ai_sueprodia.", ".
				"		codded='".$as_codded."', ".
				"		codtipper='".$as_codtipper."' ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"	AND fecsue='".$ad_fecsue."'"; 
       	$this->io_sql->begin_transaction();
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Sueldos Hist�ricos M�TODO->uf_update_sueldoshistorios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualiz� el Sueldos Hist�ricos asociado al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Sueldos Hist�ricos fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Sueldos Hist�ricos M�TODO->uf_update_sueldoshistorios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_sueldoshistorios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_codper,$ad_fecsue,$ai_suebas,$ai_sueint,$ai_sueprodia,$as_codded,$as_codtipper,$as_existe,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_fideicomiso)
		//	    Arguments: as_codper  // C�digo del Personal
		//				   ad_fecsue  // Fecha del sueldo
		//				   ai_suebas  // Sueldo Base
		//				   ai_sueint  // Sueldo Integral
		//				   ai_sueprodia  // Sueldo Promedio Diario
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el grabar � False si hubo error en el grabar
		//	  Description: Funcion que graba en la tabla de Sueldos Hist�ricos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 15/11/2010 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$ad_fecsue=$this->io_funciones->uf_convertirdatetobd($ad_fecsue);
		$ai_suebas=str_replace(".","",$ai_suebas);
		$ai_suebas=str_replace(",",".",$ai_suebas);				
		$ai_sueint=str_replace(".","",$ai_sueint);
		$ai_sueint=str_replace(",",".",$ai_sueint);				
		$ai_sueprodia=str_replace(".","",$ai_sueprodia);
		$ai_sueprodia=str_replace(",",".",$ai_sueprodia);				
		$lb_valido=false;
		$lb_valido=$this->uf_select_fideiperiodo($as_codper,$ad_fecsue);
		if($lb_valido)
		{
			$this->io_mensajes->message("Ya se calculo la Prestaci�n antiguedad para esta fecha.");
		}
		else
		{
			switch ($as_existe)
			{
				case "FALSE":
					if($this->uf_select_sueldoshistorios($as_codper,$ad_fecsue)===false)
					{
							$lb_valido=$this->uf_insert_sueldoshistorios($as_codper,$ad_fecsue,$ai_suebas,$ai_sueint,$ai_sueprodia,$as_codded,
																		 $as_codtipper,$aa_seguridad);
					}
					else
					{
						$this->io_mensajes->message("El Sueldo Hist�rico ya existe, no lo puede incluir.");
					}
					break;
								
				case "TRUE":
					if(($this->uf_select_sueldoshistorios($as_codper,$ad_fecsue)))
					{
						$lb_valido=$this->uf_update_sueldoshistorios($as_codper,$ad_fecsue,$ai_suebas,$ai_sueint,$ai_sueprodia,$as_codded,
																	 $as_codtipper,$aa_seguridad);
					}
					else
					{
						$this->io_mensajes->message("El Sueldo Hist�rico no existe, no lo puede actualizar.");
					}
					break;
			}		
		}	
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_sueldoshistorios($as_codper,$ad_fecsue,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_sueldoshistorios
		//		   Access: public (sigesp_snorh_d_sueldoshistorios)
		//	    Arguments: as_codper  // C�digo del Personal
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete � False si hubo error en el delete
		//	  Description: Funcion que elimina en la tabla de Sueldos Hist�ricos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 15/11/2010 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fecsue=$this->io_funciones->uf_convertirdatetobd($ad_fecsue);
		$lb_valido=$this->uf_select_fideiperiodo($as_codper,$ad_fecsue);
		if($lb_valido)
		{
			$this->io_mensajes->message("Ya se calculo la Prestaci�n antiguedad para esta fecha. No se pueden eliminar los sueldos historicos.");
		}
		else
		{
			$ls_sql="DELETE ".
					"  FROM sno_sueldoshistoricos ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'".
					"   AND fecsue='".$ad_fecsue."'";
			$this->io_sql->begin_transaction();
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Sueldos Hist�ricos M�TODO->uf_delete_sueldoshistorios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Elimin� el Sueldos Hist�ricos asociado al personal ".$as_codper;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{	
					$this->io_mensajes->message("El Sueldos Hist�ricos fue Eliminado.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Sueldos Hist�ricos M�TODO->uf_delete_sueldoshistorios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_sql->rollback();
				}
			}
		}
		return $lb_valido;
    }// end function uf_delete_sueldoshistorios
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>