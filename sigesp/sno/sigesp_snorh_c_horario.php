<?php
class sigesp_snorh_c_horario
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_personal;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_horario()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_horario
		//		   Access: public (sigesp_snorh_d_profesion)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
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
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_snorh_c_horario
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_profesion)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_horario($as_codhor)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_horario
		//		   Access: private
 		//	    Arguments: as_codhor  // c�digo de horario
		//	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que verifica si el horario est� registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codhor FROM sno_horario ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codhor='".$as_codhor."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Horario M�TODO->uf_select_horario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_horario
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_horario($as_codhor,$as_denhor,$as_tiphor,$as_horini,$as_horfin,$ai_horlab,$ai_hordes,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_horario
		//		   Access: private
		//	    Arguments: as_codhor  // c�digo del Horario
		//				   as_denhor  // Denominaci�n del horario
		//				   as_tiphor  // tipo de Horario 
		//				   as_horini  // hora de inicio
		//				   as_horfin  // hora de finalizacion
		//				   ai_horlab  // horas laboradas
		//				   ai_hordes  // horas de descanso
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert � False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_Horario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_horario(codemp,codhor,denhor,tiphor,horini,horfin,horlab,hordes) VALUES ".
				"('".$this->ls_codemp."','".$as_codhor."','".$as_denhor."','".$as_tiphor."','".$as_horini."','".$as_horfin."',".
				" ".$ai_horlab.",".$ai_hordes.")";
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Horario M�TODO->uf_insert_horario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Insert� el Horario ".$as_codhor;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Horario fue Registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Horario M�TODO->uf_insert_horario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_horario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_horario($as_codhor,$as_denhor,$as_tiphor,$as_horini,$as_horfin,$ai_horlab,$ai_hordes,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//    	 Function: uf_update_horario
		//		   Access: private
		//	    Arguments: as_codhor  // c�digo del Horario
		//				   as_denhor  // Denominaci�n del horario
		//				   as_tiphor  // tipo de Horario 
		//				   as_horini  // hora de inicio
		//				   as_horfin  // hora de finalizacion
		//				   ai_horlab  // horas laboradas
		//				   ai_hordes  // horas de descanso
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update � False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_Horario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_horario ".
				"   SET denhor='".$as_denhor."', ".
				"       tiphor='".$as_tiphor."', ".
				"       horini='".$as_horini."', ".
				"       horfin='".$as_horfin."', ".
				"       horlab='".$ai_horlab."', ".
				"       hordes='".$ai_hordes."'  ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codhor='".$as_codhor."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Horario M�TODO->uf_update_horario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			////////////////////////////////         SEGURIDAD               //////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualiz� el Horario ".$as_codhor;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Horario fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Horario M�TODO->uf_update_horario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_horario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codhor,$as_denhor,$as_tiphor,$as_horini,$as_horfin,$ai_horlab,$ai_hordes,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_profesion)
		//	    Arguments: as_codhor  // c�digo del Horario
		//				   as_denhor  // Denominaci�n del horario
		//				   as_tiphor  // tipo de Horario 
		//				   as_horini  // hora de inicio
		//				   as_horfin  // hora de finalizacion
		//				   ai_horlab  // horas laboradas
		//				   ai_hordes  // horas de descanso
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar � False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sno_Horario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_horario($as_codhor)===false)
				{
					$lb_valido=$this->uf_insert_horario($as_codhor,$as_denhor,$as_tiphor,$as_horini,$as_horfin,$ai_horlab,$ai_hordes,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("el Horario ya existe, no lo puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_horario($as_codhor)))
				{
					$lb_valido=$this->uf_update_horario($as_codhor,$as_denhor,$as_tiphor,$as_horini,$as_horfin,$ai_horlab,$ai_hordes,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("el Horario no existe, no lo puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_horario($as_codhor,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_profesion
		//		   Access: public (sigesp_snorh_d_horario)
		//	    Arguments: as_codhor  // c�digo del Horario
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete � False si hubo error en el delete
		//	  Description: Funcion que elimina de la tabla sno_Horario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        if ($this->verificar_horario($as_codhor)===false)   
		{
			$ls_sql="DELETE FROM sno_horario ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codhor='".$as_codhor."'";
        	$this->io_sql->begin_transaction();
		   	$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Horario M�TODO->uf_delete_horario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Elimin� el Horario ".$as_codhor;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{	
					$this->io_mensajes->message("La Horario fue Eliminado.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
        			$this->io_mensajes->message("CLASE->Horario M�TODO->uf_delete_profesion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_sql->rollback();
				}
			}
		} 
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("No se puede eliminar el Horario, hay hojas de tiempo relacionado a este.");
		}       
		return $lb_valido;
    }// end function uf_delete_profesion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function verificar_horario($as_codhor)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: verificar_horario
		//		   Access: private
 		//	    Arguments: as_codhor  // c�digo de horario
		//	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que verifica si el horario est� registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codhor FROM sno_hojatiempo ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codhor='".$as_codhor."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Horario M�TODO->verificar_horario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function verificar_horario
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>