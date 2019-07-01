<?php
class sigesp_snorh_c_deudaanterior
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
	function sigesp_snorh_c_deudaanterior()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_deudaanterior
		//		   Access: public (sigesp_snorh_d_deudaanterior)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 15/11/2010 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
		$this->io_seguridad=new sigesp_c_seguridad();
		require_once("class_folder/class_funciones_nomina.php");
		$this->io_fun_nomina=new class_funciones_nomina();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		
	}// end function sigesp_snorh_c_deudaanterior
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_deudaanterior)
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
	function uf_load_deudaanterior($as_codper,&$ad_feccordeu,&$ai_monpreant,&$ai_monint,&$ai_monant)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_deudaanterior
		//		   Access: public (sigesp_snorh_d_deudaanterior)
		//	    Arguments: as_codper  // C�digo del Personal
		//				   ad_feccordeu  // Fecha de Corte
		//				   ai_monpreant  // Monto de Prestaci�n Antiguedad
		//				   ai_monint  //  Monto Intereses
		//				   ai_monant  // Monto antiguedad
		//	      Returns: lb_valido True si existe � False si no existe
		//	  Description: Funcion que busca la Deuda Anterior si est� definido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 15/11/2010  								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT feccordeu, monpreant, monint, monant ".
				"  FROM sno_deudaanterior ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Deuda Anterior M�TODO->uf_load_deudaanterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while(!$rs_data->EOF)
			{
				$ad_feccordeu=$this->io_funciones->uf_convertirfecmostrar($rs_data->fields["feccordeu"]);
				$ai_monpreant=number_format($rs_data->fields["monpreant"],2,",",".");
				$ai_monint=number_format($rs_data->fields["monint"],2,",",".");
				$ai_monant=number_format($rs_data->fields["monant"],2,",",".");
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
    }// end function uf_load_deudaanterior
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_deudaanterior($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_deudaanterior
		//		   Access: private
		//   	Arguments: as_codper  // C�digo del Personal
		//	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que verifica si la Deuda Anterior est� registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 15/11/2010 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codper ".
				"  FROM sno_deudaanterior ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Deuda Anterior M�TODO->uf_select_deudaanterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_deudaanterior
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_fideiperiodo($as_codper,$ad_feccordeu)
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
		$ls_anio=substr($ad_feccordeu,0,4);
		$ls_mes=intval(substr($ad_feccordeu,5,2));
		$ad_feccordeu=$ls_anio."-".$ls_mes."-01";
		$ls_sql="SELECT codper ".
				"  FROM sno_fideiperiodo ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND $campo = '".$ad_fecsue."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Deuda Anterior M�TODO->uf_select_fideiperiodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	function uf_insert_deudaanterior($as_codper,$ad_feccordeu,$ai_monpreant,$ai_monint,$ai_monant,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_deudaanterior
		//		   Access: private
		//	    Arguments: as_codper  // C�digo del Personal
		//				   ad_feccordeu  // Fecha de Corte
		//				   ai_monpreant  // Monto de Prestaci�n Antiguedad
		//				   ai_monint  //  Monto Intereses
		//				   ai_monant  // Monto antiguedad
		//	      Returns: lb_valido True si se ejecuto el insert � False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla de Deuda Anterior
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 15/11/2010 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_deudaanterior ".
				"(codemp,codper,feccordeu,monpreant,monint,monant)VALUES".
				"('".$this->ls_codemp."','".$as_codper."','".$ad_feccordeu."',".$ai_monpreant.",".$ai_monint.",".$ai_monant.")";
       	$this->io_sql->begin_transaction();
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Deuda Anterior M�TODO->uf_insert_deudaanterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insert� el Deuda Anterior ".$as_codfid." asociado al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Deuda Anterior fue registrada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Deuda Anterior M�TODO->uf_insert_deudaanterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_deudaanterior
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_deudaanterior($as_codper,$ad_feccordeu,$ai_monpreant,$ai_monint,$ai_monant,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_deudaanterior
		//		   Access: private
		//	    Arguments: as_codper  // C�digo del Personal
		//				   ad_feccordeu  // Fecha de Corte
		//				   ai_monpreant  // Monto de Prestaci�n Antiguedad
		//				   ai_monint  //  Monto Intereses
		//				   ai_monant  // Monto antiguedad
		//	      Returns: lb_valido True si se ejecuto el update � False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla de Deuda Anterior
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 15/11/2010 								Fecha �ltima Modificaci�n : 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_deudaanterior ".
				"   SET feccordeu='".$ad_feccordeu."', ".
				"       monpreant=".$ai_monpreant.", ".
				"		monint=".$ai_monint.", ".
				"		monant=".$ai_monant." ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'"; 
       	$this->io_sql->begin_transaction();
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Deuda Anterior M�TODO->uf_update_deudaanterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualiz� la Deuda Anterior asociado al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Deuda Anterior fue Actualizada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Deuda Anterior M�TODO->uf_update_deudaanterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_deudaanterior
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_codper,$ad_feccordeu,$ai_monpreant,$ai_monint,$ai_monant,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_deudaanterior)
		//	    Arguments: as_codper  // C�digo del Personal
		//				   ad_feccordeu  // Fecha de Corte
		//				   ai_monpreant  // Monto de Prestaci�n Antiguedad
		//				   ai_monint  //  Monto Intereses
		//				   ai_monant  // Monto antiguedad
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el grabar � False si hubo error en el grabar
		//	  Description: Funcion que graba en la tabla de Deuda Anterior
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 15/11/2010 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$ad_feccordeu=$this->io_funciones->uf_convertirdatetobd($ad_feccordeu);
		$ai_monpreant=str_replace(".","",$ai_monpreant);
		$ai_monpreant=str_replace(",",".",$ai_monpreant);				
		$ai_monint=str_replace(".","",$ai_monint);
		$ai_monint=str_replace(",",".",$ai_monint);				
		$ai_monant=str_replace(".","",$ai_monant);
		$ai_monant=str_replace(",",".",$ai_monant);				
		$lb_valido=$this->uf_select_fideiperiodo($as_codper,$ad_feccordeu);
		if($lb_valido)
		{
			$this->io_mensajes->message("Ya se calculo la Prestaci�n antiguedad para la fecha de Corte de la Deuda Anterior.");
		}
		else
		{
			if($this->uf_select_deudaanterior($as_codper)===false)
			{
				$lb_valido=$this->uf_insert_deudaanterior($as_codper,$ad_feccordeu,$ai_monpreant,$ai_monint,$ai_monant,$aa_seguridad);
			}
			else
			{
				$lb_valido=$this->uf_update_deudaanterior($as_codper,$ad_feccordeu,$ai_monpreant,$ai_monint,$ai_monant,$aa_seguridad);
			}
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_deudaanterior($as_codper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_deudaanterior
		//		   Access: public (sigesp_snorh_d_deudaanterior)
		//	    Arguments: as_codper  // C�digo del Personal
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete � False si hubo error en el delete
		//	  Description: Funcion que elimina en la tabla de Deuda Anterior
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 15/11/2010 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fecsue=$this->io_funciones->uf_convertirdatetobd($ad_fecsue);
		$ls_sql="DELETE ".
				"  FROM sno_deudaanterior ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Deuda Anterior M�TODO->uf_delete_deudaanterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Elimin� la Deuda Anterior asociado al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("LA Deuda Anterior fue Eliminada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Deuda Anterior M�TODO->uf_delete_deudaanterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
    }// end function uf_delete_deudaanterior
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>