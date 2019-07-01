<?php
class sigesp_sno_c_importardefiniciones
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_importardefiniciones()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_importardefiniciones
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 22/02/2006 								Fecha �ltima Modificaci�n : 
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
		$this->io_seguridad= new sigesp_c_seguridad();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
		
	}// end function sigesp_sno_c_importardefiniciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sno_d_cargo)
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
        unset($this->ls_codnom);
       
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_existeregistro($as_sql)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_existeregistro
		//		   Access: private
		//      Arguments: as_sql  // sentencia SQL
		//	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que busca si existe un registro
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 23/02/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$rs_data=$this->io_sql->select($as_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_existeregistro ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_existeregistro
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_nomina($as_codnom,&$aa_personaldisp,&$aa_conceptodisp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_nomina
		//		   Access: public (sigesp_sno_p_importardefiniciones.php)
		//	    Arguments: as_codnom  // C�digo de N�mina
		//				   aa_personaldisp  // Personal Disponible
		//				   aa_conceptodisp  // Concepto Disponible
		//	      Returns: $lb_valido True si se ejecuto el select � False si hubo error en el select
		//	  Description: Funci�n que obtiene las personas y los conceptos que se encuentran en la n�mina Seleccionada
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 22/02/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_personal.codper, sno_personal.nomper, sno_personal.apeper ".
				"  FROM sno_personal, sno_personalnomina ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$as_codnom."' ".
				"   AND sno_personal.estper='1'".
				"   AND sno_personalnomina.staper<>'3' ".
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"   AND sno_personal.codper = sno_personalnomina.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_load_nomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$li_i=0;
			while(!$rs_data->EOF)
			{
				$aa_personaldisp["codper"][$li_i]=$rs_data->fields["codper"];
				$aa_personaldisp["nomper"][$li_i]=$rs_data->fields["apeper"].", ".$rs_data->fields["nomper"];
				$li_i=$li_i+1;
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);		
		}
		if($lb_valido)
		{
			$ls_sql="SELECT codconc, nomcon ".
					"  FROM sno_concepto ".
					" WHERE sno_concepto.codemp='".$this->ls_codemp."' ".
					"   AND sno_concepto.codnom='".$as_codnom."' ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_load_nomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$lb_valido=false;
			}
			else
			{
				$li_i=0;
				while(!$rs_data->EOF)
				{
					$aa_conceptodisp["codconc"][$li_i]=$rs_data->fields["codconc"];
					$aa_conceptodisp["nomcon"][$li_i]=$rs_data->fields["nomcon"];
					$li_i=$li_i+1;
					$rs_data->MoveNext();
				}
				$this->io_sql->free_result($rs_data);		
			}
		}
		return $lb_valido;
	}// end function uf_load_nomina
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importardefiniciones($as_codnombus,$aa_personalsele,$ai_totper,$aa_conceptosele,$ai_totcon,$as_codcar,
	                                 $as_codasicar,$as_codtab,$as_codpas,$as_codgra,$ai_sueper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importardefiniciones
		//		   Access: public (sigesp_sno_p_importardefiniciones.php)
		//	    Arguments: as_codnombus  // C�digo de N�mina donde se va a importar la informaci�n
		//				   aa_personalsele  // Personal Seleccionado que se va a importar
		//				   ai_totper  // total del personal selecionado
		//				   aa_conceptosele  // Concepto Seleccionado que se va a importar
		//				   ai_totcon  // total de conceptos selecionado
		//				   as_codcar  // c�digo de Cargo selecionado
		//				   as_codasicar  // C�digo de Asignaci�n de Cargo
		//				   as_codtab  // C�digo de Tabulador
		//				   as_codpas  // C�digo de Paso
		//				   as_codgra  // c�digo de Grado
		//				   ai_sueper  // sueldo seg�n la asiganci�n de cargo
		//				   aa_seguridad // arreglo de seguridad
		//	      Returns: lb_valido True si se ejecuto el importar completo � False si hubo error en el importar
		//	  Description: Funci�n que importa toda la informaci�n referente a Tablas, grado, cargo, asignaci�n de cargo, subn�mina
		//				   que el personal seleccionado tiene asociado. y las constantes que el concepto selecionado tiene asociado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 23/02/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		// Importar la informaci�n referente al personal seleccionado		
		for($li_i=0;(($li_i<$ai_totper)&&($lb_valido));$li_i++) 
		{
			$ls_codper=$aa_personalsele[$li_i];
			$lb_valido=$this->uf_importar_tabla($as_codnombus,$ls_codper);
			if($lb_valido)
			{
				$lb_valido=$this->uf_importar_cargo($as_codnombus,$ls_codper);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_importar_asignacioncargo($as_codnombus,$ls_codper);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_importar_subnomina($as_codnombus,$ls_codper);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_importar_personal($as_codnombus,$ls_codper,$as_codcar,$as_codasicar,$as_codtab,
	                                 				   $as_codpas,$as_codgra,$ai_sueper);
			}
			// Importar la informaci�n referente a las primas docentes
			$lb_valido=$this->uf_importar_primadocente($as_codnombus,$ls_codper,true);
			//
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Import� la informaci�n relacionada (Tabla, Grado, Cargo, Asignaci�n Cargo, Subn�mina, personal) ".
								 " del personal ".$ls_codper. " de la n�mina ".$as_codnombus." a la n�mina ".$this->ls_codnom." ";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}
		// Importar la informaci�n referente al concepto seleccionado
		for($li_i=0;(($li_i<$ai_totcon)&&($lb_valido));$li_i++) 
		{
			$ls_codconc=$aa_conceptosele[$li_i];
			$lb_valido=$this->uf_importar_concepto($as_codnombus,$ls_codconc,true);
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Import� la informaci�n relacionada (conceptos, constantes ) ".
								 " del concepto ".$ls_codconc. " de la n�mina ".$as_codnombus." a la n�mina ".$this->ls_codnom." ";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}
		
		if($lb_valido)
		{
			$this->io_mensajes->message("La Informaci�n fue Importada.");
			$this->io_sql->commit();
		}
		else
		{
			$this->io_mensajes->message("Ocurrio un error al importar la informaci�n.");
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_importardefiniciones
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importardefiniciones_lote($as_codnombus,$aa_personalsele,$ai_totper,$aa_conceptosele,$ai_totcon,$as_codcar,
	                                 	  $as_codasicar,$as_codtab,$as_codpas,$as_codgra,$ai_sueper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importardefiniciones_lote
		//		   Access: public (sigesp_sno_p_importardefiniciones.php)
		//	    Arguments: as_codnombus  // C�digo de N�mina donde se va a importar la informaci�n
		//				   aa_personalsele  // Personal Seleccionado que se va a importar
		//				   ai_totper  // total del personal selecionado
		//				   aa_conceptosele  // Concepto Seleccionado que se va a importar
		//				   ai_totcon  // total de conceptos selecionado
		//				   as_codcar  // c�digo de Cargo selecionado
		//				   as_codasicar  // C�digo de Asignaci�n de Cargo
		//				   as_codtab  // C�digo de Tabulador
		//				   as_codpas  // C�digo de Paso
		//				   as_codgra  // c�digo de Grado
		//				   ai_sueper  // sueldo seg�n la asiganci�n de cargo
		//				   aa_seguridad // arreglo de seguridad
		//	      Returns: lb_valido True si se ejecuto el importar completo � False si hubo error en el importar
		//	  Description: Funci�n que importa toda la informaci�n referente a Tablas, grado, cargo, asignaci�n de cargo, subn�mina
		//				   que la n�mina fuente tiene asociado, el personal seleccionado, las constantes que la n�mina fuente 
		//				   tiene asociado y el concepto selecionado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 23/02/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		// Importar la informaci�n referente al personal
		$lb_valido=$this->uf_importar_tabla($as_codnombus,"");
		if($lb_valido)
		{
			$lb_valido=$this->uf_importar_cargo($as_codnombus,"");
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_importar_asignacioncargo($as_codnombus,"");
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_importar_subnomina($as_codnombus,"");
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Import� toda la informaci�n de Tabla, Grado, Cargo, Asignaci�n Cargo, Subn�mina ".
							 "de la n�mina ".$as_codnombus." a la n�mina ".$this->ls_codnom." ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		for($li_i=0;(($li_i<$ai_totper)&&($lb_valido));$li_i++) 
		{
			$ls_codper=$aa_personalsele[$li_i];
			$lb_valido=$this->uf_importar_personal($as_codnombus,$ls_codper,$as_codcar,$as_codasicar,$as_codtab,$as_codpas,
	                                 	  		   $as_codgra,$ai_sueper);
			if($lb_valido)
			{			
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Import� la informaci�n del personal ".$ls_codper. " de la n�mina ".$as_codnombus." a la n�mina ".$this->ls_codnom." ";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}
		// Importar la informaci�n referente al concepto seleccionado
		if($ai_totcon>0)
		{		
			if($lb_valido)
			{
				$lb_valido=$this->uf_importar_constantes($as_codnombus,"");
			}
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Import� Toda la informaci�n de constantes de la n�mina ".$as_codnombus." a la n�mina ".$this->ls_codnom." ";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}
		for($li_i=0;(($li_i<$ai_totcon)&&($lb_valido));$li_i++) // Importo la informaci�n referente al concepto
		{
			$ls_codconc=$aa_conceptosele[$li_i];
			$lb_valido=$this->uf_importar_concepto($as_codnombus,$ls_codconc,false);
			if($lb_valido)
			{			
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Import� la informaci�n relacionada del concepto ".$ls_codconc. " de la n�mina ".$as_codnombus." a la n�mina ".$this->ls_codnom." ";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}
		if($lb_valido)
		{
			$this->io_mensajes->message("La Informaci�n fue Importada.");
			$this->io_sql->commit();
		}
		else
		{
			$this->io_mensajes->message("Ocurrio un error al importar la informaci�n.");
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_importardefiniciones_lote
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importar_tabla($as_codnombus,$as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importar_tabla
		//		   Access: private
		//      Arguments: as_codnombus  // C�digo de N�mina a buscar
		//      		   as_codper  // C�digo de personal
		//	      Returns:	$lb_valido True si se import� la tabla correctamente � False si fall�
		//	  Description: Funcion que busca la informaci�n de la tabla del personal y la inserta en la n�mina actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 23/02/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($as_codper<>"")
		{
			$ls_sql="SELECT sno_tabulador.codtab, sno_tabulador.destab,sno_tabulador.maxpasgra,sno_tabulador.tabmed ".
					"  FROM sno_personalnomina, sno_tabulador ".
					" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					"   AND sno_personalnomina.codnom='".$as_codnombus."' ".
					"   AND sno_personalnomina.codper='".$as_codper."' ".
					"   AND sno_personalnomina.codemp = sno_tabulador.codemp ".
					"   AND sno_personalnomina.codnom = sno_tabulador.codnom ".				
					"   AND sno_personalnomina.codtab = sno_tabulador.codtab ";
		}
		else
		{
			$ls_sql="SELECT codtab, destab, maxpasgra, tabmed ".
					"  FROM sno_tabulador ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$as_codnombus."' ";		
		}
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_importar_tabla ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codtab=$rs_data->fields["codtab"];
				$ls_destab=$rs_data->fields["destab"];
				$li_maxpasgra=$rs_data->fields["maxpasgra"];
				$li_tabmed=$rs_data->fields["tabmed"];
				$ls_sql="SELECT codtab ".
						"  FROM sno_tabulador ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codtab='".$ls_codtab."'";
				if(!$this->uf_existeregistro($ls_sql))
				{
					$ls_sql="INSERT INTO sno_tabulador(codemp,codnom,codtab,destab,maxpasgra,tabmed)VALUES('".$this->ls_codemp."','".$this->ls_codnom."',".
							"'".$ls_codtab."','".$ls_destab."',".$li_maxpasgra.",".$li_tabmed.")";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_importar_tabla ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_importar_grado($as_codnombus,$ls_codtab);
					}					
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_importar_tabla
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importar_grado($as_codnombus,$as_codtab)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importar_grado
		//		   Access: private
		//      Arguments: as_codnombus  // C�digo de N�mina a buscar
		//      		   as_codtab  // C�digo de tabla
		//	      Returns:	$lb_valido True si se import� el grado correctamente � False si fall�
		//	  Description: Funcion que busca la informaci�n de los grados de la tabla y la inserta en la n�mina actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 23/02/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_grado.codgra, sno_grado.codpas, sno_grado.monsalgra, sno_grado.moncomgra, sno_grado.aniodes, sno_grado.aniohas ".
				"  FROM sno_tabulador, sno_grado ".
				" WHERE sno_tabulador.codemp='".$this->ls_codemp."' ".
				"   AND sno_tabulador.codnom='".$as_codnombus."' ".
				"   AND sno_tabulador.codtab='".$as_codtab."' ".
				"   AND sno_tabulador.codemp = sno_grado.codemp ".
				"   AND sno_tabulador.codnom = sno_grado.codnom ".				
				"   AND sno_tabulador.codtab = sno_grado.codtab ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_importar_grado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codgra=$rs_data->fields["codgra"];
				$ls_codpas=$rs_data->fields["codpas"];
				$li_monsalgra=$rs_data->fields["monsalgra"];
				$li_moncomgra=$rs_data->fields["moncomgra"];
				$li_aniodes=$rs_data->fields["aniodes"];
				$li_aniohas=$rs_data->fields["aniohas"];
				$ls_sql="SELECT codgra ".
						"  FROM sno_grado ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codtab='".$as_codtab."'".
						"   AND codgra='".$ls_codgra."'".
						"   AND codpas='".$ls_codpas."'";
						
				if(!$this->uf_existeregistro($ls_sql))
				{
					$ls_sql="INSERT INTO sno_grado(codemp,codnom,codtab,codgra,codpas,monsalgra,moncomgra,aniodes,aniohas)VALUES('".$this->ls_codemp."',".
							"'".$this->ls_codnom."','".$as_codtab."','".$ls_codgra."','".$ls_codpas."',".$li_monsalgra.",".$li_moncomgra.",".$li_aniodes.",".$li_aniohas.")";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_importar_grado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_importar_tabla
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importar_cargo($as_codnombus,$as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importar_cargo
		//		   Access: private
		//      Arguments: as_codnombus  // C�digo de N�mina a buscar
		//      		   as_codper  // C�digo de personal
		//	      Returns:	$lb_valido True si se import� el cargo correctamente � False si fall�
		//	  Description: Funcion que busca la informaci�n del cargo del personal y lo inserta en la n�mina actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 23/02/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($as_codper<>"")
		{
			$ls_sql="SELECT sno_cargo.codcar, sno_cargo.descar ".
					"  FROM sno_personalnomina, sno_cargo ".
					" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					"   AND sno_personalnomina.codnom='".$as_codnombus."' ".
					"   AND sno_personalnomina.codper='".$as_codper."' ".
					"   AND sno_personalnomina.codemp = sno_cargo.codemp ".
					"   AND sno_personalnomina.codnom = sno_cargo.codnom ".				
					"   AND sno_personalnomina.codcar = sno_cargo.codcar ";
		}
		else
		{
			$ls_sql="SELECT codcar, descar  ".
					"  FROM sno_cargo ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$as_codnombus."' ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_importar_cargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcar=$rs_data->fields["codcar"];
				$ls_descar=$rs_data->fields["descar"];
				$ls_sql="SELECT codcar ".
						"  FROM sno_cargo ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codcar='".$ls_codcar."'";
						
				if(!$this->uf_existeregistro($ls_sql))
				{
					$ls_sql="INSERT INTO sno_cargo(codemp,codnom,codcar,descar)VALUES('".$this->ls_codemp."','".$this->ls_codnom."',".
							"'".$ls_codcar."','".$ls_descar."')";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_importar_cargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_importar_cargo
	//-----------------------------------------------------------------------------------------------------------------------------------
   
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_insert_grado($codtab,$codpas,$codgra)
	{	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_grado
		//		   Access: private
		//      Arguments:
		//	      Returns: 
		//	  Description: funci�n que busca los tabuladores y si no existelos inserta
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creaci�n: 28/01/2009 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= " SELECT codemp, codnom, codtab, codpas, codgra, monsalgra, moncomgra, aniodes, aniohas".
				 "   FROM sno_grado ".
				 "  WHERE codemp='".$this->ls_codemp."'".
				 "    AND codnom='".$this->ls_codnom."'".
				 "    AND codtab='".$codtab."'".
				 "    AND codpas='".$codpas."'".
				 "    AND codgra='".$codgra."'"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_insert_grado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}	
		else
		{
			if($rs_data->EOF)
			{
				$ls_sql=" INSERT INTO sno_grado(codemp, codnom, codtab, codpas, codgra, monsalgra, moncomgra, ".
                        " aniodes, aniohas) VALUES ('".$this->ls_codemp."', '".$this->ls_codnom."', 
						'".$codtab."', '".$codpas."', '".$codgra."',0,0,0,0);";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_insert_grado(insert) ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
			}// fin del if			
		}// fin del else
		return 	$lb_valido;
	}// uf_insert_tabulador	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importar_asignacioncargo($as_codnombus,$as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importar_asignacioncargo
		//		   Access: private
		//      Arguments: as_codnombus  // C�digo de N�mina a buscar
		//      		   as_codper  // C�digo de personal
		//	      Returns:	$lb_valido True si se import� la asignaci�n de cargo correctamente � False si fall�
		//	  Description: Funcion que busca la informaci�n de la asignaci�n de cargo del personal y lo inserta en la n�mina actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 23/02/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($as_codper<>"")
		{
			$ls_sql="SELECT sno_asignacioncargo.codasicar, sno_asignacioncargo.denasicar, sno_asignacioncargo.claasicar, sno_asignacioncargo.codtab, ".
					"		sno_asignacioncargo.codpas, sno_asignacioncargo.codgra, sno_asignacioncargo.codded, sno_asignacioncargo.codtipper, ".
					"		sno_asignacioncargo.numvacasicar, sno_asignacioncargo.numocuasicar, sno_asignacioncargo.codestpro1, ".
					"		sno_asignacioncargo.codestpro2, sno_asignacioncargo.codestpro3, sno_asignacioncargo.codestpro4, sno_asignacioncargo.codestpro5, ".
					" 		sno_asignacioncargo.minorguniadm, sno_asignacioncargo.ofiuniadm, sno_asignacioncargo.uniuniadm, sno_asignacioncargo.depuniadm, ".
					"		sno_asignacioncargo.prouniadm, sno_asignacioncargo.estcla	".
					"  FROM sno_personalnomina, sno_asignacioncargo ".
					" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					"   AND sno_personalnomina.codnom='".$as_codnombus."' ".
					"   AND sno_personalnomina.codper='".$as_codper."' ".
					"   AND sno_personalnomina.codemp = sno_asignacioncargo.codemp ".
					"   AND sno_personalnomina.codnom = sno_asignacioncargo.codnom ".				
					"   AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar ";
		}
		else
		{
			$ls_sql="SELECT codasicar, denasicar, claasicar, minorguniadm, ofiuniadm, uniuniadm, depuniadm, prouniadm, codtab, ".
					"       codpas, codgra, codded, codtipper, numvacasicar, numocuasicar, codestpro1, codestpro2, codestpro3, ".
					"		codestpro4, codestpro5, estcla ".
					"  FROM sno_asignacioncargo ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$as_codnombus."' ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_importar_asignacioncargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codasicar=$rs_data->fields["codasicar"];
				$ls_denasicar=$rs_data->fields["denasicar"];
				$ls_minorguniadm=$rs_data->fields["minorguniadm"];
				$ls_ofiuniadm=$rs_data->fields["ofiuniadm"];
				$ls_uniuniadm=$rs_data->fields["uniuniadm"];
				$ls_depuniadm=$rs_data->fields["depuniadm"];
				$ls_prouniadm=$rs_data->fields["prouniadm"];
				$ls_claasicar=$rs_data->fields["claasicar"];
				$ls_estcla=$rs_data->fields["estcla"];
				$ls_codtab=$rs_data->fields["codtab"];
				$ls_codpas=$rs_data->fields["codpas"];
				$ls_codgra=$rs_data->fields["codgra"];
				$lb_valido=$this->uf_insert_grado($ls_codtab,$ls_codpas,$ls_codgra);
				$ls_codded=$rs_data->fields["codded"];
				$ls_codtipper=$rs_data->fields["codtipper"];		
				$li_numvacasicar=$rs_data->fields["numvacasicar"];
				$li_numocuasicar=$rs_data->fields["numocuasicar"];
				$ls_codestpro1=$rs_data->fields["codestpro1"];
				$ls_codestpro2=$rs_data->fields["codestpro2"];
				$ls_codestpro3=$rs_data->fields["codestpro3"];
				$ls_codestpro4=$rs_data->fields["codestpro4"];
				$ls_codestpro5=$rs_data->fields["codestpro5"];
				$ls_sql="SELECT codasicar ".
						"  FROM sno_asignacioncargo ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codasicar='".$ls_codasicar."'";
				if(!$this->uf_existeregistro($ls_sql))
				{
					$ls_sql="INSERT INTO sno_asignacioncargo".
							"(codemp,codnom,codasicar,denasicar,minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm,claasicar,codtab,codpas,codgra,".
							"codded,codtipper,numvacasicar,numocuasicar,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla)".
							"VALUES('".$this->ls_codemp."','".$this->ls_codnom."','".$ls_codasicar."','".$ls_denasicar."','".$ls_minorguniadm."',".
							"'".$ls_ofiuniadm."','".$ls_uniuniadm."','".$ls_depuniadm."','".$ls_prouniadm."','".$ls_claasicar."','".$ls_codtab."',".
							"'".$ls_codpas."','".$ls_codgra."','".$ls_codded."','".$ls_codtipper."',".$li_numvacasicar.",".$li_numocuasicar.",".
							"'".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_estcla."')";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_importar_asignacioncargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_importar_asignacioncargo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importar_subnomina($as_codnombus,$as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importar_subnomina
		//		   Access: private
		//      Arguments: as_codnombus  // C�digo de N�mina a buscar
		//      		   as_codper  // C�digo de personal
		//	      Returns:	$lb_valido True si se import� la subn�mina correctamente � False si fall�
		//	  Description: Funcion que busca la informaci�n de la subn�mina del personal y lo inserta en la n�mina actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 23/02/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_i=0;
		if($as_codper<>"")
		{
			$ls_sql="SELECT sno_subnomina.codsubnom, sno_subnomina.dessubnom ".
					"  FROM sno_personalnomina, sno_subnomina ".
					" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					"   AND sno_personalnomina.codnom='".$as_codnombus."' ".
					"   AND sno_personalnomina.codper='".$as_codper."' ".
					"   AND sno_personalnomina.codemp = sno_subnomina.codemp ".
					"   AND sno_personalnomina.codnom = sno_subnomina.codnom ".				
					"   AND sno_personalnomina.codsubnom = sno_subnomina.codsubnom ";
		}
		else
		{
			$ls_sql="SELECT codsubnom, dessubnom ".
					"  FROM sno_subnomina ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$as_codnombus."' ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_importar_subnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codsubnom=$rs_data->fields["codsubnom"];
				$ls_dessubnom=$rs_data->fields["dessubnom"];
				$ls_sql="SELECT codsubnom ".
						"  FROM sno_subnomina ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codsubnom='".$ls_codsubnom."'";
						
				if(!$this->uf_existeregistro($ls_sql))
				{
					$li_i=1;
					$ls_sql="INSERT INTO sno_subnomina(codemp,codnom,codsubnom,dessubnom)VALUES('".$this->ls_codemp."','".$this->ls_codnom."',".
							"'".$ls_codsubnom."','".$ls_dessubnom."')";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_importar_subnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
				}
				$rs_data->MoveNext();
			}
			if(($lb_valido)&&($li_i==1))
			{
				$lb_valido=$this->uf_update_nomina($as_codnombus);
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_importar_subnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_nomina($as_codnombus)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_nomina
		//		   Access: private
		//      Arguments: as_codnombus  // C�digo de N�mina a buscar
		//	      Returns: lb_valido True si se import� la subn�mina correctamente � False si fall�
		//	  Description: Funcion que actualiza que la n�mina actual tenga subn�mina � no
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 24/02/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_nomina ".
				"   SET subnom = 1 ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_update_nomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
	}// end function uf_update_nomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importar_personal($as_codnombus,$as_codper,$as_codcar,$as_codasicar,$as_codtab,$as_codpas,$as_codgra,$ai_sueper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function : uf_importar_personal
		//		   Access : private
		//      Arguments : as_codnombus  // C�digo de N�mina a buscar
		//      			as_codper  // C�digo de personal
		//				    as_codcar  // c�digo de Cargo selecionado
		//				    as_codasicar  // C�digo de Asignaci�n de Cargo
		//				    as_codtab  // C�digo de Tabulador
		//				    as_codpas  // C�digo de Paso
		//				    as_codgra  // c�digo de Grado
		//				    ai_sueper  // sueldo seg�n la asiganci�n de cargo
		//	      Returns :	$lb_valido True si se import� el personal correctamente � False si fall�
		//	  Description : Funcion que busca la informaci�n del personal y lo inserta en la n�mina actual
		//	   Creado Por : Ing. Yesenia Moreno
		// Fecha Creaci�n : 23/02/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codper, codsubnom, codasicar, codtab, codgra, codpas, sueper, horper, minorguniadm, ofiuniadm, uniuniadm, ".
				"		depuniadm, prouniadm, pagbanper, codban, codcueban, tipcuebanper, codcar, fecingper, staper, cueaboper, ".
				"		fecculcontr, codded, codtipper, quivacper, codtabvac, sueintper, pagefeper, sueproper, codage, fecegrper, ".
				"		fecsusper, cauegrper, codescdoc, codcladoc, codubifis, tipcestic, conjub, catjub, codclavia, ".
				"		codunirac, fecascper, pagtaqper, grado, descasicar,coddep,salnorper,estencper,obsrecper ".
				"  FROM sno_personalnomina ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$as_codnombus."' ".
				"   AND sno_personalnomina.codper='".$as_codper."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_importar_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codper=$rs_data->fields["codper"];
				$ls_codsubnom=$rs_data->fields["codsubnom"];
				$ls_codcar=$as_codcar;
				if($as_codcar=="0000000000")
				{
					$ls_codcar=$rs_data->fields["codcar"];
				}
				$ls_codasicar=$as_codasicar;
				
				if (array_key_exists('session_activa',$_SESSION))
				{	
					if($as_codasicar=="0000000000")
					{
						$ls_codasicar=$rs_data->fields["codasicar"];
					}
				}
				else
				{
					if($as_codasicar=="0000000")
					{
						$ls_codasicar=$rs_data->fields["codasicar"];
					}
				}	
				
				$ls_codtab=$as_codtab;
				if($as_codtab=="00000000000000000000")
				{
					$ls_codtab=$rs_data->fields["codtab"];
				}				
				$ls_codgra=$as_codgra;
				if($as_codgra=="00")
				{
					$ls_codgra=$rs_data->fields["codgra"];
				}
				$ls_codpas=$as_codpas;
				if($as_codpas=="00")
				{
					$ls_codpas=$rs_data->fields["codpas"];
				}
				$li_sueper=$ai_sueper;
				$li_sueper=str_replace(".","",$li_sueper);
				$li_sueper=str_replace(",",".",$li_sueper);
				if($ai_sueper=="0")
				{
					$li_sueper=$rs_data->fields["sueper"];
				}
				$li_horper=$rs_data->fields["horper"];			
				$ls_minorguniadm=$rs_data->fields["minorguniadm"];			
				$ls_ofiuniadm=$rs_data->fields["ofiuniadm"];			
				$ls_uniuniadm=$rs_data->fields["uniuniadm"];			
				$ls_depuniadm=$rs_data->fields["depuniadm"];			
				$ls_prouniadm=$rs_data->fields["prouniadm"];			
				$li_pagbanper=$rs_data->fields["pagbanper"];
				$ls_codban=$rs_data->fields["codban"];
				$ls_codcueban=$rs_data->fields["codcueban"];
				$ls_tipcuebanper=$rs_data->fields["tipcuebanper"];
				$ld_fecingper=$rs_data->fields["fecingper"];				
				$ls_estper=$rs_data->fields["staper"];
				$ls_cueaboper=$rs_data->fields["cueaboper"];
				$ld_fecculcontr=$rs_data->fields["fecculcontr"];
				$ls_codded=$rs_data->fields["codded"];
				$ls_codtipper=$rs_data->fields["codtipper"];
				$ls_codtabvac=$rs_data->fields["codtabvac"];
				$li_sueintper=$rs_data->fields["sueintper"];
				$li_salnorper=$rs_data->fields["salnorper"];			
				$li_pagefeper=$rs_data->fields["pagefeper"];
				$li_sueproper=$rs_data->fields["sueproper"];			
				$ls_codage=$rs_data->fields["codage"];
				$ld_fecegrper=$rs_data->fields["fecegrper"];
				if($ld_fecegrper=="")
				{
					$ld_fecegrper="1900-01-01";
				}
				$ld_fecsusper=$rs_data->fields["fecsusper"];				
				if($ld_fecsusper=="")
				{
					$ld_fecsusper="1900-01-01";
				}
				$ls_cauegrper=$rs_data->fields["cauegrper"];
				$ls_codescdoc=$rs_data->fields["codescdoc"];
				$ls_codcladoc=$rs_data->fields["codcladoc"];
				$ls_codubifis=$rs_data->fields["codubifis"];
				$ls_tipcestic=$rs_data->fields["tipcestic"];
				$ls_quivacper=$rs_data->fields["quivacper"];
				$ls_conjub=$rs_data->fields["conjub"];
				$ls_catjub=$rs_data->fields["catjub"];
				$ls_codclavia=$rs_data->fields["codclavia"];
				$ls_codunirac=$rs_data->fields["codunirac"];
				$ld_fecascper=$rs_data->fields["fecascper"];
				$li_pagtaqper=$rs_data->fields["pagtaqper"];
				$ls_grado=$rs_data->fields["grado"];
				$ls_descasicar=$rs_data->fields["descasicar"];
				$ls_coddep=$rs_data->fields["coddep"];
				$ls_estencper=$rs_data->fields["estencper"];
				$ls_obsrecper=$rs_data->fields["obsrecper"];
				$ls_sql="SELECT codper ".
						"  FROM sno_personalnomina ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codper='".$ls_codper."'";
				if(!$this->uf_existeregistro($ls_sql))
				{
					$ls_sql="INSERT INTO sno_personalnomina(codemp,codnom,codper,codsubnom,codtab,codasicar,codgra,codpas,sueper,horper,".
							"minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm,pagbanper,codban,codcueban,tipcuebanper,codcar,fecingper,".
							"staper,cueaboper,fecculcontr,codded,codtipper,quivacper,codtabvac,sueintper,pagefeper,sueproper,codage,fecegrper,".
							"fecsusper,cauegrper,codescdoc,codcladoc,codubifis,tipcestic,conjub,catjub,codclavia,codunirac,fecascper, pagtaqper, ".
							"grado, descasicar,salnorper,coddep, estencper,obsrecper)VALUES".
							"('".$this->ls_codemp."','".$this->ls_codnom."',".
							"'".$ls_codper."','".$ls_codsubnom."','".$ls_codtab."','".$ls_codasicar."','".$ls_codgra."','".$ls_codpas."',".
							"".$li_sueper.",".$li_horper.",'".$ls_minorguniadm."','".$ls_ofiuniadm."','".$ls_uniuniadm."','".$ls_depuniadm."',".
							"'".$ls_prouniadm."',".$li_pagbanper.",'".$ls_codban."','".$ls_codcueban."','".$ls_tipcuebanper."','".$ls_codcar."',".
							"'".$ld_fecingper."','".$ls_estper."','".$ls_cueaboper."','".$ld_fecculcontr."','".$ls_codded."','".$ls_codtipper."',".
							"'".$ls_quivacper."','".$ls_codtabvac."',".$li_sueintper.",".$li_pagefeper.",".$li_sueproper.",'".$ls_codage."',".
							"'".$ld_fecegrper."','".$ld_fecsusper."','".$ls_cauegrper."','".$ls_codescdoc."','".$ls_codcladoc."','".$ls_codubifis."',".
							"'".$ls_tipcestic."','".$ls_conjub."','".$ls_catjub."','".$ls_codclavia."','".$ls_codunirac."','".$ld_fecascper."',".
							"".$li_pagtaqper.",'".$ls_grado."','".$ls_descasicar."',".$li_salnorper.",'".$ls_coddep."','".$ls_estencper."','".$ls_obsrecper."')";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_importar_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_conceptopersonal($ls_codper);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_constantepersonal($ls_codper);
					}
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_importar_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importar_constantes($as_codnombus,$as_codcons)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importar_constantes
		//		   Access: private
		//      Arguments: as_codnombus  // C�digo de N�mina a buscar
		//      		   as_codcons  // C�digo de la constante
		//	      Returns: lb_valido True si se import� la constante correctamente � False si fall�
		//	  Description: Funcion que busca la informaci�n de la constante de la n�mina seleccionada y la inserta en la n�mina actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 24/02/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($as_codcons<>"")
		{
			$ls_sql="SELECT codemp, codnom, codcons, nomcon, unicon, equcon, topcon, valcon, reicon, tipnumcon,conespseg, ".
			        " esttopmod, conperenc ".
					"  FROM sno_constante ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$as_codnombus."' ".
					"   AND codcons='".$as_codcons."' ";
		}
		else
		{
			$ls_sql="SELECT codemp, codnom, codcons, nomcon, unicon, equcon, topcon, valcon, reicon, tipnumcon,conespseg, ". 
			        " esttopmod, conperenc ".
					"  FROM sno_constante ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$as_codnombus."' ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_importar_constantes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcons=$rs_data->fields["codcons"];
				$ls_nomcon=$rs_data->fields["nomcon"];
				$ls_unicon=$rs_data->fields["unicon"];
				$li_equcon=$rs_data->fields["equcon"];
				$li_topcon=$rs_data->fields["topcon"];
				$li_valcon=$rs_data->fields["valcon"];
				$li_reicon=$rs_data->fields["reicon"];
				$ls_tipnumcon=$rs_data->fields["tipnumcon"];
				$ls_conespseg=$rs_data->fields["conespseg"];
				$ls_esttopmod=$rs_data->fields["esttopmod"];
				$ls_perenc=$rs_data->fields["conperenc"];
				$ls_sql="SELECT codcons ".
						"  FROM sno_constante ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codcons='".$ls_codcons."'";
						
				if(!$this->uf_existeregistro($ls_sql))
				{
					$ls_sql="INSERT INTO sno_constante(codemp,codnom,codcons,nomcon,unicon, equcon,topcon, valcon, ".
					        " reicon,tipnumcon,conespseg,esttopmod,conperenc) VALUES(".
							"'".$this->ls_codemp."','".$this->ls_codnom."','".$ls_codcons."','".$ls_nomcon."','".$ls_unicon."', ".
							" ".$li_equcon.",".
							"".$li_topcon.",".$li_valcon.",".$li_reicon.",'".$ls_tipnumcon."','".$ls_conespseg."', ".
							" '".$ls_esttopmod."', '".$ls_perenc."' )";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_importar_constantes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_importar_constantespersonal($as_codnombus,$ls_codcons);
					}
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_importar_constantes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importar_constantespersonal($as_codnombus,$as_codcons)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importar_constantespersonal
		//		   Access: private
		//      Arguments: as_codnombus  // C�digo de N�mina a buscar
		//      		   as_codcons  // C�digo de la constante
		//	      Returns: lb_valido True si se import� las constante personal correctamente � False si fall�
		//	  Description: Funcion que busca la informaci�n de la constante personal de la n�mina seleccionada y la inserta en la n�mina actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 24/02/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$ls_sql="	DELETE FROM sno_constantepersonal WHERE codnom = '".$this->ls_codnom."' AND codemp = '".$this->ls_codemp."';
					INSERT INTO sno_constantepersonal(codemp, codnom, codper, codcons, moncon)
					SELECT pn.codemp,'".$this->ls_codnom."',codper,codcons,valcon
					  FROM sno_personalnomina pn
					INNER JOIN sno_constante c ON c.codemp = pn.codemp AND c.codnom = pn.codnom
					 WHERE pn.codemp = '".$this->ls_codemp."' 
					AND c.codnom = '".$this->ls_codnom."' 
					ORDER BY codcons; ";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false){
			$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_importar_constantespersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		
		
		return true;
		/*
		else
		{
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codper=$rs_data->fields["codper"];
				$li_moncon=$rs_data->fields["moncon"];
				$li_topcon=$rs_data->fields["montopcon"];
				$ls_sql="SELECT codcons ".
						"  FROM sno_constantepersonal ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codcons='".$as_codcons."'".
						"   AND codper='".$ls_codper."'";
						
				if(!$this->uf_existeregistro($ls_sql))
				{
					$ls_sql="INSERT INTO sno_constantepersonal(codemp,codnom,codper,codcons,moncon,montopcon)VALUES('".$this->ls_codemp."',".
							"'".$this->ls_codnom."','".$ls_codper."','".$as_codcons."',".$li_moncon.",".$li_topcon.")";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_importar_constantespersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	*/
	}// end function uf_importar_constantespersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importar_concepto($as_codnombus,$as_codconc,$ab_impcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importar_concepto
		//		   Access: private
		//      Arguments: as_codnombus  // C�digo de N�mina a buscar
		//      		   as_codconc  // C�digo del concepto
		//      		   ab_impcon  // valor que me indica si voy a importar las constantes relacionadas � si ya se importaron
		//	      Returns: lb_valido True si se import� el concepto correctamente � False si fall�
		//	  Description: Funcion que busca la informaci�n del concepto seleccionado y lo inserta en la n�mina actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 24/02/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codconc, nomcon, titcon, sigcon, forcon, glocon, acumaxcon, valmincon, valmaxcon, concon, cueprecon, ".
				"		cueconcon, aplisrcon, sueintcon, intprocon, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, ".
				"		forpatcon, cueprepatcon, cueconpatcon, titretempcon, ".
				"		titretpatcon, valminpatcon, valmaxpatcon, codprov, cedben, conprenom, sueintvaccon, aplarccon, conprocon, ".
				"		estcla, intingcon, spi_cuenta, poringcon,aplidiasadd ".
				"  FROM sno_concepto ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$as_codnombus."' ".
				"   AND codconc='".$as_codconc."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_importar_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codconc=$rs_data->fields["codconc"];
				$ls_nomcon=$rs_data->fields["nomcon"];
				$ls_titcon=$rs_data->fields["titcon"];
				$ls_forcon=$rs_data->fields["forcon"];
				$li_acumaxcon=$rs_data->fields["acumaxcon"];
				$li_valmincon=$rs_data->fields["valmincon"];
				$li_valmaxcon=$rs_data->fields["valmaxcon"];
				$ls_concon=$rs_data->fields["concon"];
				$ls_cueprecon=$rs_data->fields["cueprecon"];
				$ls_cueconcon=$rs_data->fields["cueconcon"];
				$ls_codestpro1=$rs_data->fields["codestpro1"];
				$ls_codestpro2=$rs_data->fields["codestpro2"];
				$ls_codestpro3=$rs_data->fields["codestpro3"];
				$ls_codestpro4=$rs_data->fields["codestpro4"];
				$ls_codestpro5=$rs_data->fields["codestpro5"];
				$ls_sigcon=$rs_data->fields["sigcon"];
				$ls_glocon=$rs_data->fields["glocon"];
				$ls_aplisrcon=$rs_data->fields["aplisrcon"];
				$ls_sueintcon=$rs_data->fields["sueintcon"];
				$ls_intprocon=$rs_data->fields["intprocon"];
				$ls_forpatcon=$rs_data->fields["forpatcon"];
				$ls_cueprepatcon=$rs_data->fields["cueprepatcon"];
				$ls_cueconpatcon=$rs_data->fields["cueconpatcon"];
				$ls_titretempcon=$rs_data->fields["titretempcon"];
				$ls_titretpatcon=$rs_data->fields["titretpatcon"];
				$li_valminpatcon=$rs_data->fields["valminpatcon"];
				$li_valmaxpatcon=$rs_data->fields["valmaxpatcon"];
				$li_conprenom=$rs_data->fields["conprenom"];
				$li_sueintvaccon=$rs_data->fields["sueintvaccon"];
				$ls_codprov=$rs_data->fields["codprov"];
				$ls_cedben=$rs_data->fields["cedben"];
				$ls_conprocon=$rs_data->fields["conprocon"];
				$ls_estcla=$rs_data->fields["estcla"];
				$li_intingcon=$rs_data->fields["intingcon"];
				$ls_spicuenta=$rs_data->fields["spi_cuenta"];
				$li_poringcon=$rs_data->fields["poringcon"];
				$li_aplidiasadd=$rs_data->fields["aplidiasadd"];
				if(trim($li_aplidiasadd)=="")
				{
					$li_aplidiasadd=0;
				}
				$ls_sql="SELECT codconc ".
						"  FROM sno_concepto ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codconc='".$ls_codconc."'";
				if(!$this->uf_existeregistro($ls_sql))
				{
					$ls_sql="INSERT INTO sno_concepto(codemp,codnom,codconc,nomcon,titcon,forcon,acumaxcon,valmincon,valmaxcon,concon,cueprecon,".
							"cueconcon,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,sigcon,glocon,aplisrcon,sueintcon,intprocon,".
							"forpatcon,cueprepatcon,cueconpatcon,titretempcon,titretpatcon,valminpatcon,valmaxpatcon,conprenom,sueintvaccon,".
							"codprov,cedben,conprocon,estcla, intingcon, spi_cuenta, poringcon,aplidiasadd)VALUES".
							"('".$this->ls_codemp."','".$this->ls_codnom."','".$ls_codconc."',".
							"'".$ls_nomcon."','".$ls_titcon."','".$ls_forcon."',".$li_acumaxcon.",".$li_valmincon.",".$li_valmaxcon.",'".$ls_concon."',".
							"'".$ls_cueprecon."','".$ls_cueconcon."','".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."',".
							"'".$ls_codestpro5."','".$ls_sigcon."','".$ls_glocon."','".$ls_aplisrcon."','".$ls_sueintcon."','".$ls_intprocon."',".
							"'".$ls_forpatcon."','".$ls_cueprepatcon."','".$ls_cueconpatcon."','".$ls_titretempcon."','".$ls_titretpatcon."',".
							"".$li_valminpatcon.",".$li_valmaxpatcon.",".$li_conprenom.",".$li_sueintvaccon.",'".$ls_codprov."','".$ls_cedben."',".
							"'".$ls_conprocon."','".$ls_estcla."',".$li_intingcon.",'".$ls_spicuenta."',".$li_poringcon.",".$li_aplidiasadd.")";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_importar_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_importar_conceptopersonal($as_codnombus,$ls_codconc);
					}
					if($lb_valido)// se importan los conceptos que tiene asociado este concepto
					{
						$la_valores="";
						$lb_valido=$this->uf_select_constantesconcepto("CN[",$ls_forcon,$la_valores);
						if(!empty($la_valores))
						{
							$li_total=count($la_valores);
							for($li_i=1;(($li_i<=$li_total)&&($lb_valido));$li_i++)
							{
								$ls_codconc=$la_valores[$li_i];
								$lb_valido=$this->uf_importar_concepto($as_codnombus,$ls_codconc,$ab_impcon);
							}
						}
					}
					if(($lb_valido)&&($ab_impcon))// si solo se van a importar las constantes que tiene asociada este concepto
					{
						$la_valores="";
						$lb_valido=$this->uf_select_constantesconcepto("CT[",$ls_forcon,$la_valores);
						if(!empty($la_valores))
						{
							$li_total=count($la_valores);
							for($li_i=1;(($li_i<=$li_total)&&($lb_valido));$li_i++)
							{
								$ls_codcons=$la_valores[$li_i];
								$lb_valido=$this->uf_importar_constantes($as_codnombus,$ls_codcons);
							}
						}
					}
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_importar_concepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importar_primadocente($as_codnombus,$as_codper,$ab_impcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importar_primadocente
		//		   Access: private
		//      Arguments: as_codnombus  // C�digo de N�mina a buscar
		//      		   ab_impcon  // valor que me indica si voy a importar las constantes relacionadas � si ya se importaron
		//	      Returns: lb_valido True si se import� el concepto correctamente � False si fall�
		//	  Description: Funcion que busca la informaci�n del concepto seleccionado y lo inserta en la n�mina actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 24/02/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codper, codnom, codpridoc ".
				"  FROM sno_primadocentepersonal ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$as_codnombus."' ".
				"   AND codper='".$as_codper."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_importar_primadocente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codper=$rs_data->fields["codper"];
				$ls_codpridoc=$rs_data->fields["codpridoc"];
				$ls_sql="SELECT * ".
						"  FROM sno_primadocentepersonal ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codper='".$ls_codper."'".
						"	AND codpridoc='".$ls_codpridoc."'";
				if(!$this->uf_existeregistro($ls_sql))
				{
					$ls_sql="INSERT INTO sno_primadocentepersonal(codemp, codper, codnom, codpridoc)
							VALUES('".$this->ls_codemp."','".$ls_codper."','".$this->ls_codnom."','".$ls_codpridoc."')";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_importar_primadocente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_importar_primadocente
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	function uf_importar_conceptopersonal($as_codnombus,$as_codconc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importar_conceptopersonal
		//		   Access: private
		//      Arguments: as_codnombus  // C�digo de N�mina a buscar
		//      		   as_codconc  // C�digo del concepto
		//	      Returns: lb_valido True si se import� el concepto personal correctamente � False si fall�
		//	  Description: Funcion que busca la informaci�n del concepto personal de la n�mina seleccionada y la inserta en la n�mina actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 24/02/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$ls_sql= "	DELETE FROM sno_conceptopersonal WHERE codnom = '".$this->ls_codnom."' AND codemp = '".$this->ls_codemp."' AND codconc = '".$as_codconc."';
					INSERT INTO sno_conceptopersonal(codemp,codnom,codper,codconc,aplcon,valcon,acuemp,acuiniemp,acupat,acuinipat)
					SELECT pn.codemp,'".$this->ls_codnom."',codper,codconc,0,0,0,0,0,0
					  FROM sno_personalnomina pn
					INNER JOIN sno_concepto c ON c.codemp = pn.codemp AND c.codnom = pn.codnom
					 WHERE pn.codemp = '".$this->ls_codemp."' 
					AND c.codnom = '".$this->ls_codnom."' 
 					AND c.codconc = '".$as_codconc."'
					ORDER BY codconc;";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false){
			$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_importar_conceptopersonal select ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		
		return true;
		/*
		else
		{
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codper=$rs_data->fields["codper"];
				$ls_aplcon=$rs_data->fields["aplcon"];
				$li_valcon=$rs_data->fields["valcon"];
				$ls_sql="SELECT codconc ".
						"  FROM sno_conceptopersonal ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codconc='".$as_codconc."'".
						"   AND codper='".$ls_codper."'";
				if(!$this->uf_existeregistro($ls_sql))
				{
					$ls_sql="INSERT INTO sno_conceptopersonal(codemp,codnom,codper,codconc,aplcon,valcon,acuemp,acuiniemp,acupat,acuinipat)".
							"VALUES('".$this->ls_codemp."','".$this->ls_codnom."','".$ls_codper."','".$as_codconc."','".$ls_aplcon."',".
							"".$li_valcon.",0,0,0,0)";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_importar_conceptopersonal insert ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
		*/
	}// end function uf_importar_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_constantesconcepto($as_exp,$as_formula,&$aa_valores)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_constantesconcepto
		//		   Access: private
		//	    Arguments: as_exp // Expresi�n que me identifica que tipo de valor se va a buscar
		//				   as_formula // f�rmula del concepto
		//				   aa_valores // arreglo de todas los valores obtenidos
		//	      Returns: lb_valido True si se obtiene correctamente las constantes � False si hubo error 
		//	  Description: funci�n que dado una formula obtiene los c�digos de las constantes y conceptos requeridos por este concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 01/03/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_formula=trim($as_formula);
		$li_pos=strpos($as_formula,$as_exp);
		$li_total=0;
		if($li_pos===false)
		{
			$li_pos=-1;
		}
		while (($li_pos>=0)&&($lb_valido))
		{
			$li=$li_pos;
			$ls_valor="";
			while (($li<strlen($as_formula))&&(substr($as_formula,$li,1)<>"]"))
			{
				$li=$li+1;
			}
			if($li==0)
			{
				$lb_valido=false;
				$li_pos=-1;
				break;
			}
			$ls_token=substr($as_formula,(strlen($as_exp)+$li_pos),($li-strlen($as_exp)-$li_pos));
			switch ($as_exp)
			{
				case "CN["://Valor de Concepto
					$ls_token=str_pad($ls_token,10,"0",0);
					$ls_valor=$ls_token;
					break;

				case "CT["://Valor de Constante
					$ls_token=str_pad($ls_token,10,"0",0);
					$ls_valor=$ls_token;
					break;
			}
			if($lb_valido)
			{
				$ls_token=substr($as_formula,$li_pos,$li-$li_pos+1);
				$as_formula=str_replace($ls_token,$ls_valor,$as_formula);
				if(strlen($as_formula)>$li_pos)
				{
					$li_pos=strpos($as_formula,$as_exp,$li_pos);
					if($li_pos===false)
					{
						$li_pos=-1;
					}				
				}
				else
				{
					$li_pos=-1;
				}
				if($ls_valor!="")
				{
					$li_total=$li_total+1;
					$aa_valores[$li_total]=$ls_valor;
				}				
			}
		}
		return $lb_valido;
	}// end function uf_select_constantesconcepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_conceptopersonal($as_codper)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_conceptopersonal
		//		   Access: private
		//	    Arguments: as_codper  // c�digo de personal
		//	      Returns: lb_valido True si se ejecuto el insert � False si hubo error en el insert
		//	  Description: Funci�n que graba los conceptos a personal n�mina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 11/08/2006 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codconc ".
				"  FROM sno_concepto ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_insert_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codconc=$rs_data->fields["codconc"];
				$ls_sql="INSERT INTO sno_conceptopersonal(codemp,codnom,codper,codconc,aplcon,valcon,acuemp,acuiniemp,acupat,acuinipat)".
						"VALUES('".$this->ls_codemp."','".$this->ls_codnom."','".$as_codper."','".$ls_codconc."',1,0,0,0,0,0)";
	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_insert_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// end function uf_insert_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_constantepersonal($as_codper)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_constantepersonal
		//		   Access: private
		//	    Arguments: as_codper  // c�digo de personal
		//	      Returns: lb_valido True si se ejecuto el insert � False si hubo error en el insert
		//	  Description: Funci�n que graba las constantes a personal n�mina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codcons,valcon,topcon ".
				"  FROM sno_constante ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_insert_constantepersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codcons=$rs_data->fields["codcons"];
				$li_valcon=$rs_data->fields["valcon"];
				$li_topcon=$rs_data->fields["topcon"];
				
				$ls_sql="INSERT INTO sno_constantepersonal(codemp,codnom,codper,codcons,moncon,montopcon)".
						"VALUES('".$this->ls_codemp."','".$this->ls_codnom."','".$as_codper."','".$ls_codcons."','".$li_valcon."',".$li_topcon.")";

				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Personal N�mina M�TODO->uf_insert_constantepersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// end function uf_insert_constantepersonal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_ocupados($as_codnom,$as_codasicar,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_ocupados
		//		   Access: private
		//	    Arguments: as_codnom  // c�digo de N�mina
		//				   as_codasicar // C�digo de la Asignaci�n de Cargo	
		//	      Returns: lb_valido True si se ejecuto el update � False si hubo error en el update
		//	  Description: Funci�n que le suma � le resta a el n�mero de puestos ocupados en la asignaci�n de cargo a la n�mina correspondiente
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 15/10/2007 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_asignacioncargo ".
				"   SET numocuasicar= (SELECT COUNT(codper) ".
				"						 FROM sno_personalnomina ".
				"                       WHERE codemp='".$this->ls_codemp."' ".
				"                         AND codnom='".$as_codnom."' ".
				"                         AND codasicar='".$as_codasicar."') ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"  AND codnom='".$as_codnom."'".
				"  AND codasicar='".$as_codasicar."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Importar Definiciones M�TODO->uf_update_ocupados ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Actualiz� el n�mero de puestos ocupados a la asignaci�n de cargo  asociado a la n�mina ".$as_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_update_ocupados
	//-----------------------------------------------------------------------------------------------------------------------------------	

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>
