<?php
class sigesp_snorh_c_personal
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_fun_nomina;
	var $io_personalnomina;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_personal()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_personal
		//		   Access: public (sigesp_snorh_d_personal)
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
		$this->io_seguridad= new sigesp_c_seguridad();
		require_once("class_folder/class_funciones_nomina.php");
		$this->io_fun_nomina=new class_funciones_nomina();
		require_once("sigesp_sno_c_personalnomina.php");
		$this->io_personalnomina=new sigesp_sno_c_personalnomina();
		require_once("sigesp_snorh_c_asignacioncargo.php");
		$this->io_asignacioncargo=new sigesp_snorh_c_asignacioncargo();
		require_once("sigesp_sno.php");
		$this->io_sno=new sigesp_sno();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		
	}// end function sigesp_snorh_c_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_personal)
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
		unset($this->io_fun_nomina);
		unset($this->io_personalnomina);
        unset($this->ls_codemp);
        
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_personal($as_campo,$as_valor)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_personal
		//		   Access: public (sigesp_snorh_d_profesion, uf_guardar)
		//	    Arguments: as_campo  // Campo que se quiere filtrar
		//			  	   as_valor  // Valor por el cual se filtra
		//	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que verifica si existe un personal asociado a ese campo con ese valor
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT ".$as_campo." FROM sno_personal ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND ".$as_campo."='".$as_valor."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Personal M�TODO->uf_select_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_personal
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_personal($as_codper,$as_cedper,$as_nomper,$as_apeper,$as_dirper,$ad_fecnacper,$as_edocivper, 
								$as_telhabper,$as_telmovper,$as_sexper,$ai_estaper,$ai_pesper,$as_codpro,$as_nivacaper,$as_catper,
								$as_cajahoper,$ai_numhijper,$as_contraper,$ai_tipvivper,$as_tenvivper,$ai_monpagvivper,$as_cuecajahoper,
		 						$as_cuelphper,$as_cuefidper,$ad_fecingadmpubper,$ad_fecingper, $ai_anoservpreper,$ad_fecegrper,
								$as_codpai,$as_codest,$as_codmun,$as_codpar,$as_cauegrper,$as_obsegrper,$as_cedbenper,$as_obsper,
								$as_nomfot,$as_nacper,$as_coreleper,$as_cenmedper,$as_turper,$as_horper,$as_hcmper,$as_tipsanper,
								$as_codcom,$as_codran,$as_numexpper,$ai_incluirbeneficiario,$as_cuentacontable,$as_codpainac,
								$as_codestnac,$as_codtippersss,$ad_fecreingper,$ad_fecjubper,$as_codunivipladin,$as_enviorec,
								$as_fecleypen,$as_codcausa,$as_situacion, $ad_fecsitu, $la_talcamper, $la_talpanper, $la_talzapper,
								$ai_anoservprecont, $ai_anoservprefijo,$as_codorg,$ai_porcajahoper, $as_codger, $ai_anoperobr,$as_carantper,
								$as_rifper,$aa_seguridad,$messerprev='0')
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_personal
		//		   Access: private
		//	    Arguments: as_codper  // C�digo de Personal     as_cedper // C�dula                  as_nomper // Nombre
		//				   as_apeper // Apellido                as_dirper // Direcci�n               ad_fecnacper // Fecha Nacimiento
		//				   as_edocivper // Estado Civil         as_telhabper // Tel�fono Habitaci�n  as_telmovper // Tel�fono M�vil
		//				   as_sexper // Sexo                    ai_estaper // Estatura               ai_pesper // Peso
		//                 as_codpro // Profesi�n               as_nivacaper // Nivel Acad�mico      as_catper // Categor�a
		//                 as_cajahoper // tiene Caja de Ahorro ai_numhijper // N�mero de Hijos      as_contraper // Conyuge trabaja
		//                 ai_tipvivper // Tipo de vivienda     as_tenvivper // Tenencia Vivienda    ai_monpagvivper // Monto Pagado Vivienda
		//				   as_cuecajahoper // Cta Caja Ahorro   as_cuelphper // Cta Ley Pol�tica     as_cuefidper // Cuenta Fideicomiso
		//                 ad_fecingadmpubper // Fecha Ing Adm  ad_fecingper // Fecha Ing Ins        ai_anoservpreper // A�o de servicio Previo
		//				   ad_fecegrper // Fecha Egreso Ins     as_codpai // Pais                    as_codest // Estado
		//				   as_codmun // Municipio               as_codpar // Parroquia               as_cauegrper // Causa Egreso Personal
		//                 as_obsegrper // Observaci�n Egreso   as_cedbenper // c�dula Beneficiario  as_obsper // Observaci�n
		//				   as_nomfot // Ruta Foto			   as_nacper // nacionalidad del personal as_coreleper // Correo Electr�nico
		//				   as_cenmedper // Centro m�dico del IVSS
		//				   as_turper // Turno del Personal      as_horper // Horario del Personal	 as_hcmper // Si el personal tiene hcm
		//				   as_tipsanper // Tipo de Sangre de Personal
		//				   as_codcom // C�digo de Componente    as_codran // C�digo de Rango		 as_numexpper // N�mero de Expediente
		//				   ai_incluirbeneficiario // Incluir Personal Como beneficiario
		//				   as_cuentacontable // Cuenta contable del beneficiario
		//				   as_codpainac // C�digo de Pa�s de Nacimiento as_codestnac // C�digo de Estado de Nacimiento
		//                 ad_fecreingper // Fecha de reingreso 
		//				   ad_fecjubper   // Fecha de Jubilaci�n
		//				   as_codunivipladin  // C�digo de Unidad Vipladin
		//                 as_enviorec // modo de envio del recibo de pago
		//				   as_codcausa // codigo de la causa de retiro
		//				   ad_fecsitu // fecha de la situaci�n del pensionado
		//                 ai_anoservprecont // a�os de servicios previos como contratados
		//                 as_codorg // c�digo del organigrama
		//                 ai_porcajahoper// porcentaje de la caja de ahorro
		//                 as_carantper// cargo original de la persona
		//	      Returns: lb_valido True si se ejecuto el insert � False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_personal el registro del personal asociado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_personal".
				"(codemp,codper,cedper,nomper,apeper,dirper,fecnacper,edocivper,telhabper,telmovper,sexper,estaper,pesper,codpro,nivacaper,".
				"catper,cajahoper,numhijper,contraper,tipvivper,tenvivper,monpagvivper,cuecajahoper,cuelphper,cuefidper,fecingadmpubper,".
				"fecingper,anoservpreper,fecegrper,codpai,codest,codmun,codpar,cauegrper,obsegrper,cedbenper,obsper,fotper,estper,".
				"porisrper,nacper,coreleper,cenmedper,turper,horper,hcmper,tipsanper,codcom,codran,numexpper,codpainac,codestnac,".
				"codtippersss,fecreingper,fecjubper,codunivipladin, enviorec, fecleypen,codcausa, situacion, fecsitu, ".
				" talcamper, talpanper, talzapper, anoservprecont, anoservprefijo,codorg,porcajahoper, codger,anoperobr,carantper,rifper,".
				"messervpreper)VALUES".
				"('".$this->ls_codemp."','".$as_codper."','".$as_cedper."','".$as_nomper."','".$as_apeper."','".$as_dirper."',".
				"'".$ad_fecnacper."','".$as_edocivper."','".$as_telhabper."','".$as_telmovper."','".$as_sexper."',".$ai_estaper.",".
				"".$ai_pesper.",'".$as_codpro."','".$as_nivacaper."','".$as_catper."','".$as_cajahoper."',".$ai_numhijper.",'".$as_contraper."',".
				"'".$ai_tipvivper."','".$as_tenvivper."',".$ai_monpagvivper.",'".$as_cuecajahoper."','".$as_cuelphper."','".$as_cuefidper."',".
				"'".$ad_fecingadmpubper."','".$ad_fecingper."',".$ai_anoservpreper.",'".$ad_fecegrper."','".$as_codpai."','".$as_codest."',".
				"'".$as_codmun."','".$as_codpar."','".$as_cauegrper."','".$as_obsegrper."','".$as_cedbenper."','".$as_obsper."','".$as_nomfot."',".
				"'1',0.00,'".$as_nacper."','".$as_coreleper."','".$as_cenmedper."','".$as_turper."','".$as_horper."','".$as_hcmper."', ".
				"'".$as_tipsanper."','".$as_codcom."','".$as_codran."','".$as_numexpper."','".$as_codpainac."','".$as_codestnac."',".
				"'".$as_codtippersss."','".$ad_fecreingper."','".$ad_fecjubper."','".$as_codunivipladin."','".$as_enviorec."','".$as_fecleypen."',".
				"'".$as_codcausa."','".$as_situacion."','".$ad_fecsitu."','".$la_talcamper."','".$la_talpanper."',".$la_talzapper.",'".$ai_anoservprecont."',".
				"'".$ai_anoservprefijo."','".$as_codorg."',".$ai_porcajahoper.", '".$as_codger."', ".$ai_anoperobr.",'".$as_carantper."','".$as_rifper."',".
				"".$messerprev.")";
				
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Personal M�TODO->uf_insert_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insert� el Personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{
				$lb_valido=$this->uf_insert_isrpersonal($as_codper,$aa_seguridad);
			}
			if($lb_valido)
			{
				if($ai_incluirbeneficiario=="1")
				{
					if($as_cuentacontable!="")
					{
						$lb_valido=$this->uf_insert_beneficiario($as_cedper,$as_nomper,$as_apeper,$as_dirper,$as_telhabper,$as_telmovper,
																 $as_coreleper,$as_cuentacontable,$as_codpai,$as_codest,$as_codmun,$as_codpar,
																 $as_nacper,$aa_seguridad);
					}
					else
					{
						
						$lb_valido=false;
						$this->io_mensajes->message("Ocurrio un error al registar el personal como Beneficiario Verifique los datos.");
					}
				}
			}
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Personal fue Registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Personal M�TODO->uf_insert_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_isrpersonal($as_codper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_isrpersonal
		//		   Access: private
		//	    Arguments: as_codper  // C�digo de Personal
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert � False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_isr
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_index=1;(($li_index<13)&&($lb_valido));++$li_index)
		{
			$ls_codimp= $li_index."";
			if (strlen($ls_codimp)==1)
			{
				$ls_codimp="0".$ls_codimp;
			}
			$ls_sql="INSERT INTO sno_personalisr ".
					"(codemp,codper,codisr,porisr)VALUES('".$this->ls_codemp."','".$as_codper."','".$ls_codimp."',0.00)";
			$li_row=$this->io_sql->execute($ls_sql);
			if ($li_row===false)
			{
				$lb_valido=false;
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insert� el %ISR del mes".$ls_codimp." asociado al personal ".$as_codper;
				$lb_valido=$this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////					
			}
		
		}
		if($lb_valido===false)
		{
        	$this->io_mensajes->message("CLASE->Personal M�TODO->uf_insert_isrpersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
	}// end function uf_insert_isrpersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_personal($as_codper,$as_cedper,$as_nomper,$as_apeper,$as_dirper,$ad_fecnacper,$as_edocivper, 
								$as_telhabper,$as_telmovper,$as_sexper,$ai_estaper,$ai_pesper,$as_codpro,$as_nivacaper,$as_catper,
								$as_cajahoper,$ai_numhijper,$as_contraper,$ai_tipvivper,$as_tenvivper,$ai_monpagvivper,$as_cuecajahoper,
		 						$as_cuelphper,$as_cuefidper,$ad_fecingadmpubper,$ad_fecingper, $ai_anoservpreper,$ad_fecegrper,
								$as_codpai,$as_codest,$as_codmun,$as_codpar,$as_cauegrper,$as_obsegrper,$as_cedbenper,$as_obsper,
								$as_nacper,$as_coreleper,$as_nomfot,$as_cenmedper,$as_turper,$as_horper,$as_hcmper,$as_tipsanper,
								$as_codcom,$as_codran,$as_numexpper,$as_codpainac,$as_codestnac,$as_codtippersss,$ad_fecreingper,
								$ad_fecjubper,$as_codunivipladin,$as_enviorec,$as_fecleypen,$as_codcausa,$as_situacion,
								$ad_fecsitu, $la_talcamper, $la_talpanper, $la_talzapper, $ai_anoservprecont,
								$ai_anoservprefijo,$as_codorg,$ai_porcajahoper, $as_codger,$ai_anoperobr,$as_carantper,
								$as_rifper,$ai_incluirbeneficiario,$as_cuentacontable,$aa_seguridad,$messerprev='0')
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_personal
		//		   Access: private
		//	    Arguments: as_codper  // C�digo de Personal     as_cedper // C�dula                  as_nomper // Nombre
		//				   as_apeper // Apellido                as_dirper // Direcci�n               ad_fecnacper // Fecha Nacimiento
		//				   as_edocivper // Estado Civil         as_telhabper // Tel�fono Habitaci�n  as_telmovper // Tel�fono M�vil
		//				   as_sexper // Sexo                    ai_estaper // Estatura               ai_pesper // Peso
		//                 as_codpro // Profesi�n               as_nivacaper // Nivel Acad�mico      as_catper // Categor�a
		//                 as_cajahoper // tiene Caja de Ahorro ai_numhijper // N�mero de Hijos      as_contraper // Conyuge trabaja
		//                 ai_tipvivper // Tipo de vivienda     as_tenvivper // Tenencia Vivienda    ai_monpagvivper // Monto Pagado Vivienda
		//				   as_cuecajahoper // Cta Caja Ahorro   as_cuelphper // Cta Ley Pol�tica     as_cuefidper // Cuenta Fideicomiso
		//                 ad_fecingadmpubper // Fecha Ing Adm  ad_fecingper // Fecha Ing Ins        ai_anoservpreper // A�o de servicio Previo
		//				   ad_fecegrper // Fecha Egreso Ins     as_codpai // Pais                    as_codest // Estado
		//				   as_codmun // Municipio               as_codpar // Parroquia               as_cauegrper // Causa Egreso Personal
		//                 as_obsegrper // Observaci�n Egreso   as_cedbenper // c�dula Beneficiario  as_obsper // Observaci�n
		//				   as_nomfot // Ruta Foto			   as_nacper // nacionalidad del personal as_coreleper // Correo Electr�nico
		//				   as_cenmedper // Centro m�dico del IVSS
		//				   as_turper // Turno del Personal      as_horper // Horario del Personal
		//				   as_tipsanper // Tipo de Sangre de Personal
		//				   as_codcom // C�digo de Componente    as_codran // C�digo de Rango		 as_numexpper // N�mero de Expediente
		//				   as_codpainac // C�digo de Pa�s de Nacimiento as_codestnac // C�digo de Estado de Nacimiento
		//                 ad_fecreingper // Fecha de reingreso 
		//				   ad_fecjubper   // Fecha de Jubilaci�n
		//				   as_codunivipladin  // C�digo de Unidad Vipladin
		//                 as_enviorec // modo de envio del recibo de pago
		//				   as_codcausa // codigo de la causa de egreso
		//				   ad_fecsitu  // fecha en de la situaci�n del pensionado
		//                 ai_anoservprecont // a�os previos como contratado
		//                 as_codorg // c�digo del organigrama
		//                 ai_porcajahoper // porcentaje de  la caja de ahorro
 		//	      Returns: lb_valido True si se ejecuto el update � False si hubo error en el update as_hcmper // Si el personal tiene hcm
		//	  Description: Funcion que actualiza el personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true; 
		$ls_sqlfot="";
		if($as_nomfot!="")
		{
			$ls_sqlfot=", fotper='".$as_nomfot."' ";
		}
		$ls_sql="UPDATE sno_personal ".
				"SET cedper='".$as_cedper."', ".
				"	 nomper='".$as_nomper."', ".
				"	 apeper='".$as_apeper."', ".
				"	 dirper='".$as_dirper."', ".
				"	 fecnacper='".$ad_fecnacper."', ".
				"	 edocivper='".$as_edocivper."', ".
				"	 telhabper='".$as_telhabper."', ".
				"	 telmovper='".$as_telmovper."', ".
				"	 sexper='".$as_sexper."', ".
				"	 estaper=".$ai_estaper.", ".
				"	 pesper=".$ai_pesper.", ".
				"	 codpro='".$as_codpro."', ".
				"	 nivacaper='".$as_nivacaper."', ".
				"	 catper='".$as_catper."', ".
				"	 cajahoper='".$as_cajahoper."', ".
				"	 numhijper=".$ai_numhijper.", ".
				"	 contraper='".$as_contraper."', ".
				"	 tipvivper=".$ai_tipvivper.", ".
				"	 tenvivper='".$as_tenvivper."', ".
				"	 monpagvivper=".$ai_monpagvivper.", ".
				"	 cuecajahoper='".$as_cuecajahoper."', ".
				"	 cuelphper='".$as_cuelphper."', ".
				"	 cuefidper='".$as_cuefidper."', ".
				"	 fecingadmpubper='".$ad_fecingadmpubper."', ".
				"	 fecingper='".$ad_fecingper."', ".
				"	 anoservpreper=".$ai_anoservpreper.", ".
				"	 fecegrper='".$ad_fecegrper."', ".
				"	 codpai='".$as_codpai."', ".
				"	 cauegrper='".$as_cauegrper."', ".
				"	 codest='".$as_codest."', ".
				"	 codmun='".$as_codmun."', ".
				"	 codpar='".$as_codpar."', ".
				"	 obsegrper='".$as_obsegrper."', ".
				"	 cedbenper='".$as_cedbenper."', ".
				"	 obsper='".$as_obsper."', ".
				"	 nacper='".$as_nacper."', ".
				"	 coreleper='".$as_coreleper."', ".
				"	 cenmedper ='".$as_cenmedper."', ".
				"	 turper = '".$as_turper."', ".
				"	 horper = '".$as_horper."', ".
				"	 hcmper = '".$as_hcmper."', ".
				"	 tipsanper = '".$as_tipsanper."', ".
				"	 codcom = '".$as_codcom."', ".
				"	 codran = '".$as_codran."', ".
				"	 numexpper = '".$as_numexpper."', ".
				"	 codpainac = '".$as_codpainac."', ".
				"	 codestnac = '".$as_codestnac."' ,".
				"    codtippersss = '".$as_codtippersss."', ".
				"    fecreingper = '".$ad_fecreingper."', ".
				"    fecjubper = '".$ad_fecjubper."', ".
				"    codunivipladin = '".$as_codunivipladin."',  ".
				"    enviorec = '".$as_enviorec."', ".
				"    fecleypen='".$as_fecleypen."', ".
				"    codcausa='".$as_codcausa."', ".
				"    situacion='".$as_situacion."', ".
				"    fecsitu='".$ad_fecsitu."', ".
				"    talcamper='".$la_talcamper."', ".
				"    talpanper='".$la_talpanper."', ".
				"	 talzapper=".$la_talzapper.", ".
				"	 anoservprecont='".$ai_anoservprecont."', ".	
				"	 anoservprefijo='".$ai_anoservprefijo."', ".	
				"	 codorg='".$as_codorg."', ".
				"	 codger='".$as_codger."', ".
				"    porcajahoper = ".$ai_porcajahoper.", ".
				"    anoperobr = ".$ai_anoperobr.", ".
				"    carantper = '".$as_carantper."', ".
				"    rifper = '".$as_rifper."', ".								
				"    messervpreper = ".$messerprev." ".												
				$ls_sqlfot.  
				"WHERE codemp='".$this->ls_codemp."'".
				"  AND codper='".$as_codper."'"; 
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Personal M�TODO->uf_update_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualiz� el personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{
				if($ai_incluirbeneficiario=="1")
				{
					if($as_cuentacontable!="")
					{
						$lb_valido=$this->uf_update_beneficiario($as_cedper,$as_nomper,$as_apeper,$as_dirper,$as_telhabper,$as_telmovper,
																 $as_coreleper,$as_codpai,$as_codest,$as_codmun,$as_codpar,
																 $aa_seguridad);
					}
					else
					{
						
						$lb_valido=false;
						$this->io_mensajes->message("Ocurrio un error al registar el personal como Beneficiario Verifique los datos.");
					}
				}
			}
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Personal fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Personal M�TODO->uf_update_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codper,$as_cedper,$as_nomper,$as_apeper,$as_dirper,$ad_fecnacper,$as_edocivper, 
						$as_telhabper,$as_telmovper,$as_sexper,$ai_estaper,$ai_pesper,$as_codpro,$as_nivacaper,$as_catper,
						$as_cajahoper,$ai_numhijper,$as_contraper,$ai_tipvivper,$as_tenvivper,$ai_monpagvivper,$as_cuecajahoper,
						$as_cuelphper,$as_cuefidper,$ad_fecingadmpubper,$ad_fecingper, $ai_anoservpreper,$ad_fecegrper,
						$as_codpai,$as_codest,$as_codmun,$as_codpar,$as_cauegrper,$as_obsegrper,$as_cedbenper,$as_obsper,$as_nomfot,
						$as_nacper,$as_coreleper,$as_cenmedper,$as_turper,$as_horper,$as_hcmper,$as_tipsanper,$as_codcom,$as_codran,
						$as_numexpper,$as_codpainac,$as_codestnac,$as_codtippersss,$ad_fecreingper,$ad_fecjubper,$as_codunivipladin,$as_enviorec,$as_fecleypen,$as_codcausa,$as_situacion, $ad_fecsitu,
						$la_talcamper, $la_talpanper, $la_talzapper, $ai_anoservprecont, $ai_anoservprefijo, $as_codorg, $ai_porcajahoper,
						$as_codger, $ai_anoperobr, $as_carantper, $aa_datos, $aa_seguridad,$messerprev='0')
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_personal)
		//	    Arguments: as_codper  // C�digo de Personal     as_cedper // C�dula                  as_nomper // Nombre
		//				   as_apeper // Apellido                as_dirper // Direcci�n               ad_fecnacper // Fecha Nacimiento
		//				   as_edocivper // Estado Civil         as_telhabper // Tel�fono Habitaci�n  as_telmovper // Tel�fono M�vil
		//				   as_sexper // Sexo                    ai_estaper // Estatura               ai_pesper // Peso
		//                 as_codpro // Profesi�n               as_nivacaper // Nivel Acad�mico      as_catper // Categor�a
		//                 as_cajahoper // tiene Caja de Ahorro ai_numhijper // N�mero de Hijos      as_contraper // Conyuge trabaja
		//                 ai_tipvivper // Tipo de vivienda     as_tenvivper // Tenencia Vivienda    ai_monpagvivper // Monto Pagado Vivienda
		//				   as_cuecajahoper // Cta Caja Ahorro   as_cuelphper // Cta Ley Pol�tica     as_cuefidper // Cuenta Fideicomiso
		//                 ad_fecingadmpubper // Fecha Ing Adm  ad_fecingper // Fecha Ing Ins        ai_anoservpreper // A�o de servicio Previo
		//				   ad_fecegrper // Fecha Egreso Ins     as_codpai // Pais                    as_codest // Estado
		//				   as_codmun // Municipio               as_codpar // Parroquia               as_cauegrper // Causa Egreso Personal
		//                 as_obsegrper // Observaci�n Egreso   as_cedbenper // c�dula Beneficiario  as_obsper // Observaci�n
		//				   as_nomfot // Ruta Foto			   as_nacper // nacionalidad del personal as_coreleper // Correo Electr�nico
		//				   as_cenmedper // Centro m�dico del IVSS
		//				   as_turper // Turno del Personal      as_horper // Horario del Personal     as_hcmper // si el personal tiene HCM
		//				   as_tipsanper // Tipo de Sangre de Personal
		//				   as_codcom // C�digo de Componente    as_codran // C�digo de Rango          as_numexpper // N�mero de Expediente
		//				   as_codpainac // C�digo de Pa�s de Nacimiento as_codestnac // C�digo de Estado de Nacimiento
		//                 ad_fecreingper // Fecha de reingreso 
		//				   ad_fecjubper   // Fecha de Jubilaci�n
		//				   as_codunivipladin  // C�digo de Unidad Vipladin
		//                 as_enviorec // modo de envio del recibo de pago
		//				   as_codcausa // codigo de la causa de retiro
		//                 ad_fecsitu // fecha de la situaci�n del pensionado 
		//                 ai_anoservprecont  // a�os de servicios previso como contratados
		//                 as_codorg // c�digo del organigrama
		//                 ai_porcajahoper // porcentaje de la caja de ahorro
		//                 as_codger // c�digo de la gerencia
		//					ai_anoperobr // a�os de servicio como personal obrero
		//	      Returns: lb_valido True si se ejecuto el guardar � False si hubo error en el guardar
		//	  Description: Funcion que almacena el personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false; 
		$ad_fecnacper=$this->io_funciones->uf_convertirdatetobd($ad_fecnacper);
		$ad_fecingadmpubper=$this->io_funciones->uf_convertirdatetobd($ad_fecingadmpubper);
		$ad_fecingper=$this->io_funciones->uf_convertirdatetobd($ad_fecingper);
		$ad_fecegrper=$this->io_funciones->uf_convertirdatetobd($ad_fecegrper);
		$ad_fecreingper=$this->io_funciones->uf_convertirdatetobd($ad_fecreingper);
		$ad_fecjubper=$this->io_funciones->uf_convertirdatetobd($ad_fecjubper);
		if ($as_fecleypen!="dd/mm/aaaa")
		{ 
			$as_fecleypen=$this->io_funciones->uf_convertirdatetobd($as_fecleypen);
		}
		else
		{
			$as_fecleypen="1900-01-01";
		}
		
		if ($ad_fecsitu!="dd/mm/aaaa")
		{ 
			$ad_fecsitu=$this->io_funciones->uf_convertirdatetobd($ad_fecsitu);
		}
		else
		{
			$ad_fecsitu="1900-01-01";
		}
		$ai_estaper=str_replace(".","",$ai_estaper);
		$ai_estaper=str_replace(",",".",$ai_estaper);
		$ai_pesper=str_replace(".","",$ai_pesper);
		$ai_pesper=str_replace(",",".",$ai_pesper);
		$ai_monpagvivper=str_replace(".","",$ai_monpagvivper);
		$ai_monpagvivper=str_replace(",",".",$ai_monpagvivper);		
		$ai_porcajahoper=str_replace(".","",$ai_porcajahoper);
		$ai_porcajahoper=str_replace(",",".",$ai_porcajahoper);		
		$la_talzapper=str_replace(",",".",$la_talzapper);
		$as_rifper       = trim($aa_datos["tipperrif"].'-'.$aa_datos["numpririf"].'-'.$aa_datos["numterrif"]);
		if ($as_codorg=='')
		{
			$as_codorg='----------'; 
		}
		
		if ($as_codger=='')
		{
			$as_codger='----------'; 
		}
		
		switch($as_existe)
		{
			case "FALSE":
				if($this->uf_select_personal("codper",$as_codper)===false)
				{
					if($this->uf_select_personal("cedper",$as_cedper)===false)
					{
						$lb_valido=true;
						$li_incluirbeneficiario=trim($this->io_sno->uf_select_config("SNO","CONFIG","INCLUIR_A_BENEFICIARIO","0","I"));
						$ls_cuentacontable=trim($this->io_sno->uf_select_config("SNO","CONFIG","CUENTA_CONTABLE_BENEFICIARIO","","C"));
						if(($li_incluirbeneficiario=="1")&&($ls_cuentacontable==""))
						{
							$this->io_mensajes->message("ERROR->Debe Colocar la Cuenta Contable en Mantenimiento->Configuraci�n para poder incluir el personal como beneficiario.");
							$this->io_mensajes->message("ERROR->El Personal no fue registrado.");
							$lb_valido=false;
						}
						if($lb_valido)
						{
							$lb_valido=$this->uf_insert_personal($as_codper,$as_cedper,$as_nomper,$as_apeper,$as_dirper,
																 $ad_fecnacper,$as_edocivper,$as_telhabper,$as_telmovper,$as_sexper,
																 $ai_estaper,$ai_pesper,$as_codpro,$as_nivacaper,$as_catper,
																 $as_cajahoper,$ai_numhijper,$as_contraper,$ai_tipvivper,$as_tenvivper,
																 $ai_monpagvivper,$as_cuecajahoper,$as_cuelphper,$as_cuefidper,
																 $ad_fecingadmpubper,$ad_fecingper, $ai_anoservpreper,$ad_fecegrper,
																 $as_codpai,$as_codest,$as_codmun,$as_codpar,$as_cauegrper,$as_obsegrper,
																 $as_cedbenper,$as_obsper,$as_nomfot,$as_nacper,$as_coreleper,$as_cenmedper,
																 $as_turper,$as_horper,$as_hcmper,$as_tipsanper,$as_codcom,$as_codran,
																 $as_numexpper,$li_incluirbeneficiario,$ls_cuentacontable,$as_codpainac,
																 $as_codestnac,$as_codtippersss,$ad_fecreingper,$ad_fecjubper,$as_codunivipladin,
																 $as_enviorec,$as_fecleypen, $as_codcausa, $as_situacion, 
																 $ad_fecsitu,$la_talcamper, $la_talpanper, $la_talzapper,
																 $ai_anoservprecont, $ai_anoservprefijo,$as_codorg, $ai_porcajahoper,$as_codger,
																 $ai_anoperobr,$as_carantper,$as_rifper,$aa_seguridad,$messerprev);
						}
						
					}
					else
					{
						$this->io_mensajes->message("La C�dula ya existe, no lo puede incluir.");
					}
				}
				else
				{
					$this->io_mensajes->message("El C�digo ya existe, no lo puede incluir.");
				}
				break;
				
			case "TRUE":
				if(($this->uf_select_personal("codper",$as_codper)))
				{
					$li_incluirbeneficiario=trim($this->io_sno->uf_select_config("SNO","CONFIG","INCLUIR_A_BENEFICIARIO","0","I"));
					$ls_cuentacontable=trim($this->io_sno->uf_select_config("SNO","CONFIG","CUENTA_CONTABLE_BENEFICIARIO","","C"));
						if(($li_incluirbeneficiario=="1")&&($ls_cuentacontable==""))
						{
							$this->io_mensajes->message("ERROR->Debe Colocar la Cuenta Contable en Mantenimiento->Configuraci�n para poder incluir el personal como beneficiario.");
							$this->io_mensajes->message("ERROR->El Personal no fue registrado.");
							$lb_valido=false;
						}
					$lb_valido=$this->uf_update_personal($as_codper,$as_cedper,$as_nomper,$as_apeper,$as_dirper,$ad_fecnacper,$as_edocivper, 
														 $as_telhabper,$as_telmovper,$as_sexper,$ai_estaper,$ai_pesper,$as_codpro,$as_nivacaper,
														 $as_catper,$as_cajahoper,$ai_numhijper,$as_contraper,$ai_tipvivper,$as_tenvivper,$ai_monpagvivper,
														 $as_cuecajahoper,$as_cuelphper,$as_cuefidper,$ad_fecingadmpubper,$ad_fecingper,$ai_anoservpreper,
														 $ad_fecegrper,$as_codpai,$as_codest,$as_codmun,$as_codpar,$as_cauegrper,$as_obsegrper,$as_cedbenper,
														 $as_obsper,$as_nacper,$as_coreleper,$as_nomfot,$as_cenmedper,$as_turper,$as_horper,$as_hcmper,
														 $as_tipsanper,$as_codcom,$as_codran,$as_numexpper,$as_codpainac,$as_codestnac,$as_codtippersss,
														 $ad_fecreingper,$ad_fecjubper,$as_codunivipladin,$as_enviorec,
														 $as_fecleypen, $as_codcausa, $as_situacion, $ad_fecsitu,
														 $la_talcamper, $la_talpanper, $la_talzapper, $ai_anoservprecont,
														 $ai_anoservprefijo,$as_codorg,$ai_porcajahoper,$as_codger,$ai_anoperobr,$as_carantper,
														 $as_rifper,$li_incluirbeneficiario,$ls_cuentacontable,$aa_seguridad,$messerprev);
				}
				else
				{
					$this->io_mensajes->message("El Personal no existe, no lo puede actualizar.");
				}
				break;
		}		
		
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_personal(&$as_codper,&$as_existe,&$as_cedper,&$as_nomper,&$as_apeper,&$as_dirper,&$ad_fecnacper,&$as_edocivper, 
							  &$as_telhabper,&$as_telmovper,&$as_sexper,&$ai_estaper,&$ai_pesper,&$as_codpro,&$as_nivacaper,&$as_catper,
							  &$as_cajahoper,&$ai_numhijper,&$as_contraper,&$as_tipvivper,&$as_tenvivper,&$ai_monpagvivper,&$as_cuecajahoper,
							  &$as_cuelphper,&$as_cuefidper,&$ad_fecingadmpubper,&$ad_fecingper, &$ai_anoservpreper,&$ad_fecegrper,
							  &$as_codpai,&$as_codest,&$as_codmun,&$as_codpar,&$as_cauegrper,&$as_obsegrper,&$as_cedbenper,&$as_obsper,&$as_estper,
						      &$as_despro,&$as_despai,&$as_desest,&$as_desmun,&$as_despar,&$as_nomfot,&$as_nacper,&$as_coreleper,&$as_cenmedper,
							  &$as_turper,&$as_horper,&$as_hcmper,&$as_tipsanper,&$as_codcom,&$as_codran,&$as_descom,&$as_desran,&$as_numexpper,
							  &$as_codpainac,&$as_codestnac,&$as_despainac,&$as_desestnac,&$ad_fecreingper,&$ad_fecjubper,&$as_codunivipladin,
							  &$as_denunivipladin,&$as_enviorec, &$as_fecleypen, &$as_codcausa,&$as_situacion, &$ad_fecsitu,
							  &$la_talcamper, &$la_talpanper, &$la_talzapper, &$ai_anoservprecont, &$ai_anoservprefijo,
							  &$ls_cauegrper2, &$as_codtippersss, &$as_dentippersss,&$as_codorg, &$as_desorg,&$ai_porcajahoper,&$as_codger,
							  &$as_denger,&$ai_anoperobr, &$as_carantper,&$as_tipperrif,&$as_numpririf,&$as_numterrif,&$messerprev)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_personal
		//		   Access: public (sigesp_snorh_d_personal)
		//	    Arguments: as_codper  // C�digo de Personal     as_cedper // C�dula                  as_nomper // Nombre
		//				   as_apeper // Apellido                as_dirper // Direcci�n               ad_fecnacper // Fecha Nacimiento
		//				   as_edocivper // Estado Civil         as_telhabper // Tel�fono Habitaci�n  as_telmovper // Tel�fono M�vil
		//				   as_sexper // Sexo                    ai_estaper // Estatura               ai_pesper // Peso
		//                 as_codpro // Profesi�n               as_nivacaper // Nivel Acad�mico      as_catper // Categor�a
		//                 as_cajahoper // tiene Caja de Ahorro ai_numhijper // N�mero de Hijos      as_contraper // Conyuge trabaja
		//                 ai_tipvivper // Tipo de vivienda     as_tenvivper // Tenencia Vivienda    ai_monpagvivper // Monto Pagado Vivienda
		//				   as_cuecajahoper // Cta Caja Ahorro   as_cuelphper // Cta Ley Pol�tica     as_cuefidper // Cuenta Fideicomiso
		//                 ad_fecingadmpubper // Fecha Ing Adm  ad_fecingper // Fecha Ing Ins        ai_anoservpreper // A�o de servicio Previo
		//				   ad_fecegrper // Fecha Egreso Ins     as_codpai // Pais                    as_codest // Estado
		//				   as_codmun // Municipio               as_codpar // Parroquia               as_cauegrper // Causa Egreso Personal
		//                 as_obsegrper // Observaci�n Egreso   as_cedbenper // c�dula Beneficiario  as_obsper // Observaci�n
		//				   as_nomfot // Ruta Foto			   as_nacper // nacionalidad del personal as_coreleper // Correo Electr�nico
		//				   as_turper // Turno del Personal      as_horper // Horario del Personal     as_hcmper // si el personal tiene hcm
		//				   as_codcom // C�digo de Componente    as_codran // C�digo de Rango
		//				   as_descom // Descripcion de Componente    as_desran // Descripcion de Rango as_numexpper // N�mero de Expediente del Personal
		//                 ad_fecreingper // Fecha de reingreso 
		//				   ad_fecjubper   // Fecha de Jubilaci�n
		//				   as_codunivipladin  // C�digo de Unidad Vipladin
		//                 $as_enviorec // modo de envio del recibo de pago
		//				   as_codcausa // codigo de la causa del retiro
		//                 ad_fecsitu // fecha de la situaci�n del pensionado
		//				   as_codorg // c�digo del nivel del organigrama
		//                 as_desorg // descripcion del nivel del  organigrama
		//                 ai_porcajahoper // porcentaje de la caja de ahorro
		//	      Returns: lb_valido True si se ejecuto el select � False si hubo error en el select
		//	  Description: Funcion que obtiene un  personal en espec�fico
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.dirper, ".
				"		sno_personal.fecnacper, sno_personal.edocivper, sno_personal.telhabper, sno_personal.telmovper, ".
				"		sno_personal.sexper, sno_personal.estaper, sno_personal.pesper, sno_personal.codpro, sno_personal.nivacaper, ".
				"		sno_personal.catper, sno_personal.cajahoper, sno_personal.numhijper, sno_personal.contraper, ".
				"		sno_personal.tipvivper, sno_personal.tenvivper, sno_personal.monpagvivper, sno_personal.ingbrumen, ".
				"		sno_personal.cuecajahoper, sno_personal.cuelphper, sno_personal.cuefidper, sno_personal.fecingadmpubper, ".
				"		sno_personal.vacper, sno_personal.porisrper, sno_personal.fecingper, sno_personal.anoservpreper, sno_personal.hcmper, ".
				"		sno_personal.cedbenper, sno_personal.fecegrper, sno_personal.estper, sno_personal.fotper, sno_personal.codpai, ".
				"		sno_personal.codest, sno_personal.codmun, sno_personal.codpar, sno_personal.obsper, sno_personal.cauegrper, ".
				"		sno_personal.obsegrper, sno_personal.nacper, sno_personal.coreleper, sno_personal.cenmedper, sno_personal.turper, ".
				"		sno_personal.horper, sno_profesion.despro, sigesp_pais.despai, sigesp_estados.desest, sigesp_municipio.denmun, ".
				"		sigesp_parroquia.denpar, sno_personal.tipsanper, sno_personal.codcom, sno_personal.codran, sno_personal.numexpper, ".
				"		sno_personal.codpainac, sno_personal.codestnac, sno_personal.fecreingper, sno_personal.fecjubper, sno_personal.codunivipladin, sno_personal.situacion, sno_personal.fecsitu, sno_personal.talcamper, sno_personal.talzapper, ".
				"       sno_personal.talpanper, sno_personal.codorg, sno_personal.codger, ".
				"		sno_personal.enviorec, sno_personal.fecleypen, sno_personal.codcausa, sno_personal.codtippersss, ". 
				"       sno_personal.porcajahoper, sno_personal.anoperobr, sno_personal.carantper,  sno_personal.rifper,sno_personal.messervpreper, ".
				"       (SELECT desorg FROM srh_organigrama ".
				"         WHERE srh_organigrama.codemp = sno_personal.codemp ".
				"           AND srh_organigrama.codorg = sno_personal.codorg ) AS desorg, ".				
				"       (SELECT denger FROM srh_gerencia ".
				"         WHERE srh_gerencia.codemp = sno_personal.codemp ".
				"           AND srh_gerencia.codger = sno_personal.codger ) AS denger, ".				
				"		(SELECT denunivipladin FROM srh_unidadvipladin ".
				"		  WHERE srh_unidadvipladin.codemp = sno_personal.codemp ".
				"			AND srh_unidadvipladin.codunivipladin = sno_personal.codunivipladin ) AS denunivipladin, ".
				"		(SELECT descom FROM sno_componente ".
				"		  WHERE sno_componente.codemp = sno_personal.codemp ".
				"			AND sno_componente.codcom = sno_personal.codcom ) AS descom, ".
				"		(SELECT desran FROM sno_rango ".
				"		  WHERE sno_rango.codemp = sno_personal.codemp ".
				"			AND sno_rango.codcom = sno_personal.codcom ".
				"			AND sno_rango.codran = sno_personal.codran) AS desran, ".
				"		(SELECT dentippersss FROM sno_tipopersonalsss ".
				"		  WHERE sno_tipopersonalsss.codemp = sno_personal.codemp ".
				"			AND sno_tipopersonalsss.codtippersss = sno_personal.codtippersss ) AS dentippersss, ".
				"		(SELECT despai FROM sigesp_pais ".
				"		  WHERE sigesp_pais.codpai = sno_personal.codpainac ) AS despainac, ".
				"		(SELECT desest FROM sigesp_estados ".
				"		  WHERE sigesp_estados.codpai = sno_personal.codpainac ".
				"			AND sigesp_estados.codest = sno_personal.codestnac ) AS desestnac ".
				"  FROM sno_personal, sno_profesion, sigesp_pais, sigesp_estados, sigesp_municipio, sigesp_parroquia ".
				" WHERE sno_personal.codemp='".$this->ls_codemp."' ".
				"   AND sno_personal.codper='".$as_codper."' ".
				"	AND sno_profesion.codemp = sno_personal.codemp ".
				"   AND sno_profesion.codpro = sno_personal.codpro ".
				"   AND sigesp_pais.codpai = sno_personal.codpai ".
				"   AND sigesp_estados.codpai = sno_personal.codpai ".
				"   AND sigesp_estados.codest = sno_personal.codest ".
				"   AND sigesp_municipio.codpai = sno_personal.codpai ".
				"   AND sigesp_municipio.codest = sno_personal.codest ".
				"   AND sigesp_municipio.codmun = sno_personal.codmun ".
				"   AND sigesp_parroquia.codpai = sno_personal.codpai ".
				"   AND sigesp_parroquia.codest = sno_personal.codest ".
				"   AND sigesp_parroquia.codmun = sno_personal.codmun ".
				"   AND sigesp_parroquia.codpar = sno_personal.codpar "; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Personal M�TODO->uf_load_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while(!$rs_data->EOF)
			{
				$as_codper=$rs_data->fields["codper"];
				$as_cedper=$rs_data->fields["cedper"];
				$as_nomper=$rs_data->fields["nomper"];
				$as_apeper=$rs_data->fields["apeper"];				
				$as_dirper=$rs_data->fields["dirper"];				
				$ad_fecnacper=$this->io_funciones->uf_convertirfecmostrar($rs_data->fields["fecnacper"]);				
				$as_edocivper=$rs_data->fields["edocivper"];			
				$as_telhabper=$rs_data->fields["telhabper"];				
				$as_telmovper=$rs_data->fields["telmovper"];				
				$as_sexper=$rs_data->fields["sexper"];			
				$as_nacper=$rs_data->fields["nacper"];
				$ai_estaper=$rs_data->fields["estaper"];			
				$ai_estaper=$this->io_fun_nomina->uf_formatonumerico($ai_estaper);
				$ai_pesper=$rs_data->fields["pesper"];			
				$ai_pesper=$this->io_fun_nomina->uf_formatonumerico($ai_pesper);
				$as_codpro=$rs_data->fields["codpro"];	
				$as_nivacaper=$rs_data->fields["nivacaper"];
				$as_codpai=$rs_data->fields["codpai"];	
				$as_codest=$rs_data->fields["codest"];	
				$as_codmun=$rs_data->fields["codmun"];	
				$as_codpar=$rs_data->fields["codpar"];	
				$as_catper=$rs_data->fields["catper"];	
				$as_cedbenper=$rs_data->fields["cedbenper"];	
				$ai_numhijper=$rs_data->fields["numhijper"];	
				$as_obsper=$rs_data->fields["obsper"];	
				$as_contraper=$rs_data->fields["contraper"];			
				$as_tipvivper=$rs_data->fields["tipvivper"];	
				$as_tenvivper=$rs_data->fields["tenvivper"];	
				$ai_monpagvivper=$rs_data->fields["monpagvivper"];	
				$ai_monpagvivper=$this->io_fun_nomina->uf_formatonumerico($ai_monpagvivper);				
				$as_cuecajahoper=$rs_data->fields["cuecajahoper"];	
				$as_cuelphper=$rs_data->fields["cuelphper"];	
				$as_cuefidper=$rs_data->fields["cuefidper"];	
				$as_cajahoper=$rs_data->fields["cajahoper"];
				$ad_fecingadmpubper=$this->io_funciones->uf_convertirfecmostrar($rs_data->fields["fecingadmpubper"]);							
				$ai_anoservpreper=$rs_data->fields["anoservpreper"];	
				$ai_anoservprefijo=$this->uf_select_anotrabajoantfijo($as_codper);
				$ai_anoservprecont=$this->uf_select_anotrabajoantcontratado($as_codper);	
				$ad_fecingper=$this->io_funciones->uf_convertirfecmostrar($rs_data->fields["fecingper"]);				
				$ad_fecegrper=$this->io_funciones->uf_convertirfecmostrar($rs_data->fields["fecegrper"]);				
				$as_cauegrper=$rs_data->fields["cauegrper"];			
				$as_obsegrper=$rs_data->fields["obsegrper"];	
				$as_existe="TRUE";			
				$as_estper=$rs_data->fields["estper"];
				$as_codtippersss=$rs_data->fields["codtippersss"];
				$as_dentippersss=$rs_data->fields["dentippersss"];
				$as_codpainac=$rs_data->fields["codpainac"];
				$as_codestnac=$rs_data->fields["codestnac"];
				$as_despainac=$rs_data->fields["despainac"];
				$as_desestnac=$rs_data->fields["desestnac"];
				$as_rifper=$rs_data->fields["rifper"];
				$as_tipperrif = substr($as_rifper,0,1);//Tipo Persona RIF.(J=Juridico,G=Gubernamental,V=Natural Venezolano,E=Natural Extranjero).
				$as_numpririf = substr($as_rifper,2,8);//N�mero Principal del RIF, 8 D�gitos (0-9).
				$as_numterrif = substr($as_rifper,11,1);//N�mero Terminal  del RIF, 1 D�gitos (0-9).
				$as_situacion=$rs_data->fields["situacion"];
				switch ($as_estper)
				{
					case "0":
						$as_estper="PRE INGRESO";
						break;
					
					case "1":
						$as_estper="ACTIVO";
						break;
						
					case "2":
						$as_estper="N/A";
						break;
						
					case "3":
						$as_estper="EGRESADO";
						break;
				}
				$as_despro=$rs_data->fields["despro"];
				$as_despai=$rs_data->fields["despai"];
				$as_desest=$rs_data->fields["desest"];
				$as_desmun=$rs_data->fields["denmun"];
				$as_despar=$rs_data->fields["denpar"];
				$as_nomfot=$rs_data->fields["fotper"];
				$as_coreleper=$rs_data->fields["coreleper"];
				$as_cenmedper=$rs_data->fields["cenmedper"];
				$as_turper=$rs_data->fields["turper"];
				$as_horper=$rs_data->fields["horper"];
				$as_hcmper=$rs_data->fields["hcmper"];
				$as_tipsanper=$rs_data->fields["tipsanper"];
				$as_codcom=$rs_data->fields["codcom"];
				$as_codran=$rs_data->fields["codran"];
				$as_descom=$rs_data->fields["descom"];
				$as_desran=$rs_data->fields["desran"];
				$as_numexpper=$rs_data->fields["numexpper"];
				$ad_fecreingper=$rs_data->fields["fecreingper"];
				$ad_fecjubper=$rs_data->fields["fecjubper"];
				$as_codunivipladin=$rs_data->fields["codunivipladin"];
				$as_denunivipladin=$rs_data->fields["denunivipladin"];	
				$as_enviorec=$rs_data->fields["enviorec"];				
				$as_fecleypen=$this->io_funciones->uf_convertirfecmostrar($rs_data->fields["fecleypen"]);	
				$as_codcausa=$rs_data->fields["codcausa"];
				$ad_fecsitu=$this->io_funciones->uf_convertirfecmostrar($rs_data->fields["fecsitu"]);
				$la_talcamper=$rs_data->fields["talcamper"];
				$la_talpanper=$rs_data->fields["talpanper"];
				$la_talzapper=$rs_data->fields["talzapper"];
				$as_codorg=$rs_data->fields["codorg"];			
				$as_desorg=$rs_data->fields["desorg"];
				$as_codger=$rs_data->fields["codger"];			
				$as_denger=$rs_data->fields["denger"];
				$ai_anoperobr=$rs_data->fields["anoperobr"];
				$ai_porcajahoper=$rs_data->fields["porcajahoper"];
				$as_carantper=$rs_data->fields["carantper"];
				$ai_porcajahoper=$this->io_fun_nomina->uf_formatonumerico($ai_porcajahoper);	
				if ($as_cauegrper=="N")
				{
					$ls_cauegrper2="Ninguno";
				}
				if ($as_cauegrper=="D")
				{
					$ls_cauegrper2="Despedido";
				}
				if ($as_cauegrper=="1")
				{
					$ls_cauegrper2="Despedido 102";
				}
				if ($as_cauegrper=="2")
				{
					$ls_cauegrper2="Despedido 125";
				}
				if ($as_cauegrper=="P")
				{
					$ls_cauegrper2="Pensionado";
				}
				if ($as_cauegrper=="R")
				{
					$ls_cauegrper2="Renuncia";
				}
				if ($as_cauegrper=="T")
				{
					$ls_cauegrper2="Traslado";
				}
				if ($as_cauegrper=="J")
				{
					$ls_cauegrper2="Jubilado";
				}
				if ($as_cauegrper=="F")
				{
					$ls_cauegrper2="Fallecido";
				}	
				$rs_data->MoveNext();				
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_upload($as_nomfot,$as_tipfot,$as_tamfot,$as_nomtemfot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_upload
		//		   Access: public (sigesp_snorh_d_personal)
		//	    Arguments: as_nomfot  // Nombre Foto
		//				   as_tipfot  // Tipo Foto
		//				   as_tamfot  // Tama�o Foto
		//				   as_nomtemfot  // Nombre Temporal
		//	      Returns: as_nomfot sale vacia si da un error y con el mismo valor si se subio correctamente
		//	  Description: Funcion que sube una foto al servidor
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_tipfot=strtolower($as_tipfot);
		
		if ($as_nomfot!="")
		{
			if (!((strpos($as_tipfot, "gif") || strpos($as_tipfot, "jpeg") || strpos($as_tipfot, "png")) && ($as_tamfot < 900000))) 
			{ 
				$as_nomfot="";
				$this->io_mensajes->message("El archivo de la foto no es v�lido.");
			}
			else
			{ 
				if (!((move_uploaded_file($as_nomtemfot, "fotospersonal/".$as_nomfot))))
				{
					$as_nomfot="";
		        	$this->io_mensajes->message("CLASE->Personal M�TODO->uf_upload ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
			}
		}
		return $as_nomfot;	
    }
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_personalestatus($as_codper,$as_estper,$ad_fecegrper,$as_cauegrper,$as_obsegrper,$as_codcausa,
	                                   $ai_implementarcodunirac,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function : uf_update_personalestatus
		//		   Access : public (sigesp_snorh_p_personalcambioestatus)
		//	    Arguments : as_codper  // C�digo de Personal
		//				    as_estper  // Estatus de Personal
		//				    ad_fecegrper  // Fecha de Egreso
		//				    as_cauegrper  // Causa de egreso
		//				    as_obsegrper // Observaci�n del Egreso	
		//                  ai_implementarcodunirac	// variable que indica si utiliza  c�digo �nico de RAC
		//	      Returns : $lb_valido True si se ejecuto el cambio � False si hubo error al ejecuatr el cambio
		//	  Description : Funcion que actualiza el estatus del personal
		//				    esta funci�n es llamada de la pantalla sigesp_snorh_p_personalcambioestatus.php	
		//	   Creado Por : Ing. Yesenia Moreno
		// Fecha Creaci�n : 14/02/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fecegrper=$this->io_funciones->uf_convertirdatetobd($ad_fecegrper);
		$ls_estpernom="";
		$ls_sql="UPDATE sno_personal ".
				"   SET estper='".$as_estper."', ".
				"		fecegrper='".$ad_fecegrper."', ".
				"		cauegrper='".$as_cauegrper."', ".
				"		obsegrper='".$as_obsegrper."', ".
				"       codcausa='".$as_codcausa."'    ".
				" WHERE codemp='".$this->ls_codemp."'".
				"	AND codper='".$as_codper."'";

		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Personal M�TODO->uf_update_personalestatus ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			if (($ai_implementarcodunirac=="1")&&($as_estper=="1"))//cuando vuelve a estar activo el personal
			{
				$lb_valido=$this->uf_select_codigo_unico_rac_personal($as_codper,$aa_seguridad);
				
			}
			if (($as_estper=="3")&&($lb_valido))//cuando se egresa al personal
			{
					$lb_valido=$this->io_asignacioncargo->uf_update_estatus_codigo_unico_rac($as_codper,'0',$aa_seguridad);
			}
			if ($lb_valido)
			{
				switch ($as_estper)
				{
					case "0": //Pre-Ingreso en n�mina lo coloco como No Asignado
						$ls_estpernom="0";
						break;
	
					case "1": //Activo en n�mina lo coloco como Activo
						$ls_estpernom="1";
						break;
	
					case "2": //No Asignado en n�mina lo coloco como No Asignado
						$ls_estpernom="0";
						break;
	
					case "3": //Egresado en n�mina lo coloco como Egresado
						$ls_estpernom="3";
						break;
					
					case "4": //Remoci�n
						$ls_estpernom="3";
						break;
					
					case "5": //Retiro
						$ls_estpernom="3";
						break;
					
					case "6": //Destituci�n
						$ls_estpernom="3";
						break;
				}
				if($ls_estpernom!="")
				{
					$lb_valido=$this->io_personalnomina->uf_update_estatus($as_codper,$ls_estpernom,$ad_fecegrper,$as_obsegrper,"1",$aa_seguridad);
				}
				if($lb_valido)
				{	
					$this->io_mensajes->message("El Personal fue Actualizado.");
					$this->io_sql->commit();
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="UPDATE";
					$ls_descripcion ="Cambi� el Estatus del personal ".$as_codper." Estatus ".$as_estper;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Personal M�TODO->uf_update_personalestatus ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_sql->rollback();
				}
			}
		}
		return $lb_valido;
	}	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_codigopersonal($as_cedper,&$as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_codigopersonal
		//		   Access: public (sigesp_sno_c_impexpdato)
		//	    Arguments: as_cedper  // C�dula del personal
		//			  	   as_codper  // c�digo del personal
		//	      Returns: lb_valido True si el select no tuvo errores � False si hubo error
		//	  Description: Funcion que obtiene el c�digo de personal dada una c�dula
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 28/03/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codper FROM sno_personal ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND cedper='".$as_cedper."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Personal M�TODO->uf_load_codigopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if(!$rs_data->EOF)
			{
				$as_codper=$rs_data->fields["codper"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_codigopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_cambioid($as_codper,$as_codnue,$as_obscodnue,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_cambioid
		//		   Access: public (sigesp_snorh_p_personalcambioid)
		//	    Arguments: as_codper  // C�digo del personal
		//			  	   as_codnue  // Nuevo C�digo del personal
		//			  	   as_obscodnue  // Observaci�n por medio del cual se esta cambiando el c�digo del personal
		//			  	   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si el proceso se ejecut� con �xito � False si hubo error
		//	  Description: Funcion que obtiene que cambia el c�digo del personal por uno nuevo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 30/03/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		if($this->uf_select_personal("codper",$as_codper)===false)
		{// Verificamos que el c�digo viejo exista en la BD
			$lb_valido=false;
			$this->io_mensajes->message("El C�digo ".$as_codper." No existe. No se puede modificar.");
		}
		if(($this->uf_select_personal("codper",$as_codnue))&&($lb_valido))
		{// Verificamos que le c�digo nuevo no exista en la BD
			$lb_valido=false;
			$this->io_mensajes->message("El C�digo ".$as_codnue." Existe. No se puede asociar a este personal.");
		}
		if($lb_valido)
		{// Insertamos todos los registros con el nuevo c�digo
			$lb_valido=$this->uf_insert_registro($as_codper,$as_codnue);
		}
		if($lb_valido)
		{// eliminamos todos los registros del viejo c�digo
			$lb_valido=$this->uf_delete_registro($as_codper);
		}
		if($lb_valido) 
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Cambi� el c�digo del personal ".$as_codper." al c�digo ".$as_codnue." observaci�n ".$as_obscodnue;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////				
		}
		if($lb_valido)
		{	
			$this->io_mensajes->message("El C�digo del personal fue cambiado.");
			$this->io_sql->commit();
		}
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("Ocurrio un error al Cambiar el c�digo del personal."); 
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_procesar_cambioid
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_repararid($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_repararid
		//		   Access: public (sigesp_snorh_p_personalcambioid)
		//	    Arguments: aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si el proceso se ejecut� con �xito � False si hubo error
		//	  Description: Funcion que obtiene que cambia el c�digo del personal por uno nuevo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 16/03/2010 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;

       	$ls_sql="SELECT codper ".
				"  FROM sno_personal ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND length(codper)<10".
				" ORDER BY codper ";
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
        	$this->io_mensajes->message("CLASE->Prestamo M�TODO->uf_select_prestamo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
       	}
       	else
       	{
			$as_obscodnue="CAMBIO DE C�DIGO DE PERSONAL POR QUE TEN�A UNA LONGITUD ERRADA.";
			$ls_descripcion="";
			$ls_mensaje="";
			while(!$rs_data->EOF)
         	{
				$this->io_sql->begin_transaction();
				$ls_codper=$rs_data->fields["codper"];
				$ls_codnue=str_pad(trim($ls_codper),10,'0',0);
				if($this->uf_select_personal("codper",$ls_codper)===false)
				{// Verificamos que el c�digo viejo exista en la BD
					$lb_valido=false;
					$ls_mensaje = $ls_mensaje.'El C�digo: '.$ls_codper.' No existe. No se puede modificar.\n';
				}
				if(($this->uf_select_personal("codper",$ls_codnue))&&($lb_valido))
				{// Verificamos que le c�digo nuevo no exista en la BD
					$lb_valido=false;
					$ls_mensaje = $ls_mensaje.'El C�digo: '.$ls_codnue.' existe. No se puede asociar a este personal.\n';
				}
				if($lb_valido)
				{// Insertamos todos los registros con el nuevo c�digo
					$lb_valido=$this->uf_insert_registro($ls_codper,$ls_codnue);
				}
				if($lb_valido)
				{// eliminamos todos los registros del viejo c�digo
					$lb_valido=$this->uf_delete_registro($ls_codper);
				}
				if($lb_valido) 
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_descripcion =$ls_descripcion."Cambi� el c�digo del personal ".$ls_codper." al c�digo ".$ls_codnue." observaci�n ".$as_obscodnue;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////				
					$this->io_sql->commit();
				}
				else
				{
					$this->io_sql->rollback();
				}
           		$rs_data->MoveNext();
         	}
			$this->io_sql->free_result($rs_data);	
       	}
		if($lb_valido) 
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////				
		}
		if($lb_valido)
		{	
			$this->io_mensajes->message("Los C�digos del personal fue cambiado.");
		}
		else
		{
			if($ls_mensaje!="")
			{
				$ls_mensaje=' PERSONAL QUE NO SE PUDO CAMBIAR EL ID  \n\n  '.$ls_mensaje;
				$this->io_mensajes->message($ls_mensaje);
			}
		}
		return $lb_valido;
	}// end function uf_procesar_cambioid
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_registro($as_codper,$as_codnue)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_registro
		//		   Access: private
		//	    Arguments: as_codper  // C�digo del personal
		//			  	   as_codnue  // Nuevo C�digo del personal
		//	      Returns: lb_valido True si se ejecutaron los insert � False si hubo error en los insert
		//	  Description: Funcion que inserta todos los registros del personal con el nuevo c�digo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 30/03/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($lb_valido)
		{// Insertamos en Personal
			$ls_sql="INSERT INTO sno_personal (codemp, codper, cedper, nomper, apeper, dirper, fecnacper, edocivper, telhabper, ".
					"			 telmovper, sexper, estaper, pesper, codpro, nivacaper, catper, cajahoper, numhijper, contraper, ".
					"			 tipvivper, tenvivper, monpagvivper, ingbrumen, cuecajahoper, cuelphper, cuefidper, fecingadmpubper, ".
					"			 vacper, porisrper, fecingper, anoservpreper, cedbenper, fecegrper, estper, fotper, codpai, codest, ".
					"			 codmun, codpar, obsper, cauegrper, obsegrper, nacper, coreleper, cenmedper, turper, horper, hcmper, ".
					"			 tipsanper, codcom, codran, numexpper, codpainac, codestnac, codtippersss, fecreingper, fecjubper, ".
					"			 codunivipladin, enviorec, fecleypen, codcausa, situacion, fecsitu,talcamper,talzapper,talpanper, ".
					"			 anoservprecont,  anoservprefijo, codorg, porcajahoper, anoperobr, codger, carantper, rifper,messervpreper, ".
					"			 gerantper, tipperant) ".
					"     SELECT codemp, '".$as_codnue."' as codper, cedper, nomper, apeper, dirper, fecnacper, edocivper, telhabper, ".
					"			 telmovper, sexper, estaper, pesper, codpro, nivacaper, catper, cajahoper, numhijper, contraper, ".
					"			 tipvivper, tenvivper, monpagvivper, ingbrumen, cuecajahoper, cuelphper, cuefidper, fecingadmpubper, ".
					"			 vacper, porisrper, fecingper, anoservpreper, cedbenper, fecegrper, estper, fotper, codpai, codest, ".
					"			 codmun, codpar, obsper, cauegrper, obsegrper, nacper, coreleper, cenmedper, turper, horper, hcmper, ".
					"			 tipsanper, codcom, codran, numexpper, codpainac, codestnac, codtippersss, fecreingper, fecjubper, ".
					"			 codunivipladin, enviorec, fecleypen, codcausa, situacion, fecsitu,talcamper,talzapper,talpanper, ".
					"            anoservprecont,  anoservprefijo, codorg, porcajahoper, anoperobr, codger, carantper, rifper,messervpreper, ".
					"			 gerantper, tipperant ".
					"       FROM sno_personal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los Familiares del personal
			$ls_sql="INSERT INTO sno_familiar (codemp, codper, cedfam, nomfam, apefam, sexfam, fecnacfam, nexfam, estfam, hcmfam, hcfam, hijesp, ".
					"			 estbonjug, cedula) ".
					"     SELECT codemp, '".$as_codnue."' as  codper, cedfam, nomfam, apefam, sexfam, fecnacfam, nexfam, estfam, hcmfam, hcfam, ".
					"			 hijesp, estbonjug, cedula ".
					"       FROM sno_familiar ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos las Deducciones de los Familiares del personal
			$ls_sql="INSERT INTO sno_familiardeduccion (codemp, codper, cedfam, codtipded, coddettipded) ".
					"     SELECT codemp, '".$as_codnue."' as  codper, cedfam, codtipded, coddettipded ".
					"       FROM sno_familiardeduccion ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los Trabajos Anteriores del personal
			$ls_sql="INSERT INTO sno_trabajoanterior (codemp, codper, codtraant, emptraant, ultcartraant, ultsuetraant, ".
					"			 fecingtraant, fecrettraant, emppubtraant, codded, anolab, meslab, dialab) ".
					"     SELECT codemp, '".$as_codnue."' as codper, codtraant, emptraant, ultcartraant, ultsuetraant, ".
					"			 fecingtraant, fecrettraant, emppubtraant, codded, anolab, meslab, dialab ".
					"       FROM sno_trabajoanterior ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los Estudios Realizados del personal
			$ls_sql="INSERT INTO sno_estudiorealizado (codemp, codper, codestrea, tipestrea, insestrea, desestrea, titestrea, calestrea, ".
					"			 fecgraestrea, escval, feciniact, fecfinact, soladi, aprestrea, anoaprestrea, horestrea) ".
					"     SELECT codemp, '".$as_codnue."' as codper, codestrea, tipestrea, insestrea, desestrea, titestrea, calestrea, ".
					"            fecgraestrea, escval, feciniact, fecfinact, soladi, aprestrea, anoaprestrea, horestrea ".
					"       FROM sno_estudiorealizado ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos el ISR por meses del personal
			$ls_sql="INSERT INTO sno_personalisr (codemp, codper, codisr, porisr, codconret) ".
					"     SELECT codemp, '".$as_codnue."' as codper, codisr, porisr, codconret ".
					"       FROM sno_personalisr ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los permisos del personal
			$ls_sql="INSERT INTO sno_permiso (codemp, codper, numper, feciniper, fecfinper, numdiaper, afevacper, tipper, obsper, remper, tothorper) ".
					"     SELECT codemp, '".$as_codnue."' as codper, numper, feciniper, fecfinper, numdiaper, afevacper, tipper, obsper, remper, tothorper ".
					"       FROM sno_permiso ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos el fideicomiso del personal
			$ls_sql="INSERT INTO sno_fideicomiso (codemp, codper, codfid, ficfid, ubifid, cuefid, fecingfid, capfid, capantcom, fecconpreant, ".
					"			 conpreant, porintcap, scg_cuentafid,scg_cuentaintfid) ".
					"     SELECT codemp, '".$as_codnue."' as  codper, codfid, ficfid, ubifid, cuefid, fecingfid, capfid, capantcom, fecconpreant, ".
					"			 conpreant, porintcap, scg_cuentafid,scg_cuentaintfid ".
					"       FROM sno_fideicomiso ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos el beneficiario del personal
			$ls_sql="INSERT INTO sno_beneficiario (codemp, codper, codben, cedben, nomben, apeben, dirben, telben, tipben, nomcheben, ".
					"			 porpagben, monpagben, codban, ctaban, sc_cuenta, forpagben, nacben, tipcueben, nexben, cedaut, numexpben) ".
					"     SELECT codemp, '".$as_codnue."' as  codper, codben, cedben, nomben, apeben, dirben, telben, tipben, nomcheben, ".
					"			 porpagben, monpagben, codban, ctaban, sc_cuenta, forpagben, nacben, tipcueben, nexben, cedaut, numexpben  ".
					"       FROM sno_beneficiario ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos las vacaciones del personal
			$ls_sql="INSERT INTO sno_vacacpersonal (codemp, codper, codvac, fecvenvac, fecdisvac, fecreivac, diavac, stavac, ".
					"			 sueintbonvac, sueintvac, diabonvac, obsvac, diapenvac, persalvac, peringvac, dianorvac, quisalvac, ".
					"			 quireivac, diaadivac, diaadibon, diafer, sabdom, periodo_1, cod_1, nro_dias_1, Monto_1, periodo_2, ".
					"			 cod_2, nro_dias_2, Monto_2, periodo_3, cod_3, nro_dias_3, Monto_3, periodo_4, cod_4, nro_dias_4, ".
					"			 Monto_4, periodo_5, cod_5, nro_dias_5, Monto_5, diapag, pagcan, diapervac, pagpersal, calpagvac, profueper) ".
					"     SELECT codemp, '".$as_codnue."' as codper, codvac, fecvenvac, fecdisvac, fecreivac, diavac, stavac, ".
					"			 sueintbonvac, sueintvac, diabonvac, obsvac, diapenvac, persalvac, peringvac, dianorvac, quisalvac, ".
					"			 quireivac, diaadivac, diaadibon, diafer, sabdom, periodo_1, cod_1, nro_dias_1, Monto_1, periodo_2, ".
					"			 cod_2, nro_dias_2, Monto_2, periodo_3, cod_3, nro_dias_3, Monto_3, periodo_4, cod_4, nro_dias_4, ".
					"			 Monto_4, periodo_5, cod_5, nro_dias_5, Monto_5, diapag, pagcan, diapervac, pagpersal, calpagvac, profueper ".
					"       FROM sno_vacacpersonal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos el fideicomiso por per�odo del personal
			$ls_sql="INSERT INTO sno_fideiperiodo (codemp, codnom, codper, anocurper, mescurper, bonvacper, bonfinper, sueintper, apoper, ".
					"			 bonextper, diafid, diaadi) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, anocurper, mescurper, bonvacper, bonfinper, sueintper, apoper, ".
					"			 bonextper, diafid, diaadi ".
					"       FROM sno_fideiperiodo ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los intereses del fideicomiso por per�odo del personal
			$ls_sql="INSERT INTO sno_fideiperiodointereses (codemp, codnom, codper, anocurper, mescurper, monant, porint, monint, monantacu, moncap) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, anocurper, mescurper, monant, porint, monint, monantacu, moncap ".
					"       FROM sno_fideiperiodointereses ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los sueldos historicos del personal
			$ls_sql="INSERT INTO sno_sueldoshistoricos (codemp, codper, fecsue, suebas, sueint, sueprodia) ".
					"     SELECT codemp,'".$as_codnue."' as codper, fecsue, suebas, sueint, sueprodia ".
					"       FROM sno_sueldoshistoricos ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos las deudas anteriores del personal
			$ls_sql="INSERT INTO sno_deudaanterior (codemp, codper, feccordeu, monpreant, monint, monant) ".
					"     SELECT codemp,'".$as_codnue."' as codper, feccordeu, monpreant, monint, monant ".
					"       FROM sno_deudaanterior ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los anticipos del personal
			$ls_sql="INSERT INTO sno_anticipoprestaciones (codemp, codper, codant, fecant, monpreant, monantant, porant, monant, motant, obsant) ".
					"     SELECT codemp,'".$as_codnue."' as codper, codant, fecant, monpreant, monantant, porant, monant, motant, obsant ".
					"       FROM sno_anticipoprestaciones ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos el afiliado a ipasme  del personal
			$ls_sql="INSERT INTO sno_ipasme_afiliado (codemp, codper, tiptraafi, coddep, actlabafi, tipafiafi, codban, cuebanafi, ".
					"			 tipcueafi, codent, codmun, codloc, urbafi, aveafi, nomresafi, pisafi, zonafi, numresafi) ".
					"     SELECT codemp, '".$as_codnue."' as codper, tiptraafi, coddep, actlabafi, tipafiafi, codban, cuebanafi, ".
					"			 tipcueafi, codent, codmun, codloc, urbafi, aveafi, nomresafi, pisafi, zonafi, numresafi ".
					"       FROM sno_ipasme_afiliado ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos el beneficiario de ipasme  del personal
			$ls_sql="INSERT INTO sno_ipasme_beneficiario (codemp, codper, codben, cedben, tiptraben, codpare, nacben, prinomben, ".
					"			 segnomben, priapeben, segapeben, sexben, fecnacben, estcivben, fecfalben, codban, numcueben, tipcueben) ".
					"     SELECT codemp, '".$as_codnue."' as codper, codben, cedben, tiptraben, codpare, nacben, prinomben, ".
					"			 segnomben, priapeben, segapeben, sexben, fecnacben, estcivben, fecfalben, codban, numcueben, tipcueben ".
					"       FROM sno_ipasme_beneficiario ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos las guarderias del personal
			$ls_sql="INSERT INTO sno_guarderias (codemp, codper, codguar, nomper, monto, cedbene, nombene) ".
					"     SELECT codemp, '".$as_codnue."' as codper, codguar, nomper, monto, cedbene, nombene ".
					"       FROM sno_guarderias ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos el personal jubilado
			$ls_sql="INSERT INTO sno_jubilados (codemp, codper, nomper, prirem, subtot, porpen, monpen, ultrem, fecvida, tipjub) ".
					"     SELECT codemp, '".$as_codnue."' as codper, nomper, prirem, subtot, porpen, monpen, ultrem, fecvida, tipjub ".
					"       FROM sno_jubilados ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos las deducciones  del personal
			$ls_sql="INSERT INTO sno_personaldeduccion (codemp, codper, codtipded, coddettipded) ".
					"     SELECT codemp, '".$as_codnue."' as codper, codtipded, coddettipded ".
					"       FROM sno_personaldeduccion ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos el personal n�mina
			$ls_sql="INSERT INTO sno_personalnomina (codemp, codnom, codper, codsubnom, codasicar, codtab, codgra, codpas, sueper, ".
					"			 horper, minorguniadm, ofiuniadm, uniuniadm, depuniadm, prouniadm, pagbanper, codban, codcueban, ".
					"			 tipcuebanper, codcar, fecingper, staper, cueaboper, fecculcontr, codded, codtipper, quivacper, ".
					"			 codtabvac, sueintper, pagefeper, sueproper, codage, fecegrper, fecsusper, cauegrper, codescdoc, ".
					"			 codcladoc, codubifis, tipcestic, conjub, catjub, codclavia, codunirac, pagtaqper, grado, descasicar, ".
					"			 coddep, salnorper, estencper, obsrecper) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, codsubnom, codasicar, codtab, codgra, codpas, sueper, ".
					"			 horper, minorguniadm, ofiuniadm, uniuniadm, depuniadm, prouniadm, pagbanper, codban, codcueban, ".
					"			 tipcuebanper, codcar, fecingper, staper, cueaboper, fecculcontr, codded, codtipper, quivacper, ".
					"			 codtabvac, sueintper, pagefeper, sueproper, codage, fecegrper, fecsusper, cauegrper, codescdoc, ".
					"			 codcladoc, codubifis, tipcestic, conjub, catjub, codclavia, codunirac, pagtaqper, grado, descasicar, ".
					"			 coddep, salnorper, estencper, obsrecper ".
					"       FROM sno_personalnomina ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos las pensiones del personal n�mina
			$ls_sql="INSERT INTO sno_personalpension (codemp, codnom, codper, suebasper, pritraper, pridesper, prianoserper, ".
					"            prinoascper, priespper, priproper, subtotper, porpenper, monpenper, tipjub, fecvid, prirem, segrem) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, suebasper, pritraper, pridesper, prianoserper, ".
					"            prinoascper, priespper, priproper, subtotper, porpenper, monpenper, tipjub, fecvid, prirem, segrem ".
					"       FROM sno_personalpension ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos las constantes del personal n�mina
			$ls_sql="INSERT INTO sno_constantepersonal (codemp, codnom, codper, codcons, moncon, montopcon) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, codcons, moncon, montopcon ".
					"       FROM sno_constantepersonal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los conceptos del personal n�mina
			$ls_sql="INSERT INTO sno_conceptopersonal (codemp, codnom, codper, codconc, aplcon, valcon, acuemp, acuiniemp, acupat, acuinipat) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, codconc, aplcon, valcon, acuemp, acuiniemp, acupat, acuinipat ".
					"       FROM sno_conceptopersonal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los proyectos del personal n�mina
			$ls_sql="INSERT INTO sno_proyectopersonal (codemp, codnom, codproy, codper, totdiaper, totdiames, pordiames) ".
					"     SELECT codemp, codnom, codproy, '".$as_codnue."' as codper, totdiaper, totdiames, pordiames ".
					"       FROM sno_proyectopersonal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los prestamos del personal n�mina
			$ls_sql="INSERT INTO sno_prestamos (codemp, codnom, codper, numpre, codtippre, codconc, monpre, numcuopre, perinipre, ".
					"			 monamopre, stapre, fecpre, obsrecpre, obssuspre, tipcuopre) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, numpre, codtippre, codconc, monpre, numcuopre, perinipre, ".
					"			 monamopre, stapre, fecpre, obsrecpre, obssuspre, tipcuopre ".
					"       FROM sno_prestamos ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los prestamos per�odo del personal n�mina
			$ls_sql="INSERT INTO sno_prestamosperiodo (codemp, codnom, codper, numpre, codtippre, numcuo, percob, feciniper, fecfinper, moncuo, estcuo) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, numpre, codtippre, numcuo, percob, feciniper, fecfinper, moncuo, estcuo ".
					"       FROM sno_prestamosperiodo ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los prestamos amortizado del personal n�mina
			$ls_sql="INSERT INTO sno_prestamosamortizado (codemp, codnom, codper, numpre, codtippre, numamo, peramo, fecamo, monamo, desamo) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, numpre, codtippre, numamo, peramo, fecamo, monamo, desamo ".
					"       FROM sno_prestamosamortizado ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los prestamos amortizado del personal n�mina
			$ls_sql="INSERT INTO sno_encargaduria (codemp, codenc, codnom, tipenc, fecinienc, fecfinenc, codper, codperenc, codnomperenc, ".
  					"			 estenc, obsenc, estsuspernom) ".
					"     SELECT codemp, codenc, codnom, tipenc, fecinienc, fecfinenc, '".$as_codnue."' as codper, codperenc, codnomperenc, ".
  					"			 estenc, obsenc, estsuspernom ".
					"       FROM sno_encargaduria ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los prestamos amortizado del personal n�mina
			$ls_sql="INSERT INTO sno_encargaduria (codemp, codenc, codnom, tipenc, fecinienc, fecfinenc, codper, codperenc, codnomperenc, ".
  					"			 estenc, obsenc, estsuspernom) ".
					"     SELECT codemp, codenc, codnom, tipenc, fecinienc, fecfinenc, codper, '".$as_codnue."' as  codperenc, codnomperenc, ".
  					"			 estenc, obsenc, estsuspernom ".
					"       FROM sno_encargaduria ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codperenc='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos las primas docentes del personal n�mina
			$ls_sql="INSERT INTO sno_primadocentepersonal (codemp, codper, codnom, codpridoc) ".
					"     SELECT codemp, '".$as_codnue."' as codper, codnom, codpridoc ".
					"       FROM sno_primadocentepersonal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos las recepciones del personal n�mina
			$ls_sql="INSERT INTO sno_rd (codemp, codnom, codperi, codper, codtipdoc, sc_cuenta, debhab, monpagper, estcon) ".
					"     SELECT codemp, codnom, codperi, '".$as_codnue."' as codper, codtipdoc, sc_cuenta, debhab, monpagper, estcon ".
					"       FROM sno_rd ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos la pren�mina del personal n�mina
			$ls_sql="INSERT INTO sno_prenomina (codemp, codnom, codper, codperi, codconc, tipprenom, valprenom, valhis) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, codperi, codconc, tipprenom, valprenom, valhis ".
					"       FROM sno_prenomina ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos la salida del personal n�mina
			$ls_sql="INSERT INTO sno_salida (codemp, codnom, codperi, codper, codconc, tipsal, valsal, monacusal, salsal, priquisal, segquisal) ".
					"     SELECT codemp, codnom, codperi, '".$as_codnue."' as codper, codconc, tipsal, valsal, monacusal, salsal, priquisal, segquisal ".
					"       FROM sno_salida ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos el resumen del personal n�mina
			$ls_sql="INSERT INTO sno_resumen (codemp, codnom, codperi, codper, asires, dedres, apoempres, apopatres, priquires, ".
					"			 segquires, monnetres, notres) ".
					"     SELECT codemp, codnom, codperi, '".$as_codnue."' as  codper, asires, dedres, apoempres, apopatres, priquires, ".
					"			 segquires, monnetres, notres ".
					"       FROM sno_resumen ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos hist�rico del personal n�mina
			$ls_sql="INSERT INTO sno_hpersonalnomina (codemp, codnom, anocur, codperi, codasicar, codper, codsubnom, codtab, codgra, ".
					"			 codpas, sueper, horper, minorguniadm, ofiuniadm, uniuniadm, depuniadm, prouniadm, pagbanper, codban, ".
					"			 codcueban, tipcuebanper, codcar, fecingper, staper, cueaboper, fecculcontr, codded, codtipper, quivacper, ".
					"			 codtabvac, sueintper, pagefeper, sueproper, codage, fecegrper, fecsusper, cauegrper, codescdoc, codcladoc, ".
					"			 codubifis, tipcestic, conjub, catjub, codclavia, descasicar, coddep, salnorper, estencper, obsrecper) ".
					"     SELECT codemp, codnom, anocur, codperi, codasicar, '".$as_codnue."' as codper, codsubnom, codtab, codgra, ".
					"			 codpas, sueper, horper, minorguniadm, ofiuniadm, uniuniadm, depuniadm, prouniadm, pagbanper, codban, ".
					"			 codcueban, tipcuebanper, codcar, fecingper, staper, cueaboper, fecculcontr, codded, codtipper, quivacper, ".
					"			 codtabvac, sueintper, pagefeper, sueproper, codage, fecegrper, fecsusper, cauegrper, codescdoc, codcladoc, ".
					"			 codubifis, tipcestic, conjub, catjub, codclavia, descasicar, coddep, salnorper, estencper, obsrecper ".
					"       FROM sno_hpersonalnomina ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos las pensiones del personal n�mina
			$ls_sql="INSERT INTO sno_hpersonalpension (codemp, codnom, anocur, codperi, codper, suebasper, pritraper, pridesper, prianoserper, ".
					"            prinoascper, priespper, priproper, subtotper, porpenper, monpenper, tipjub, fecvid, prirem, segrem) ".
					"     SELECT codemp, codnom,  anocur, codperi, '".$as_codnue."' as codper, suebasper, pritraper, pridesper, prianoserper, ".
					"            prinoascper, priespper, priproper, subtotper, porpenper, monpenper, tipjub, fecvid, prirem, segrem ".
					"       FROM sno_hpersonalpension ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos hist�rico de las constantes personal n�mina
			$ls_sql="INSERT INTO sno_hconstantepersonal (codemp, codnom, codper, anocur, codperi, codcons, moncon, montopcon) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, anocur, codperi, codcons, moncon, montopcon ".
					"       FROM sno_hconstantepersonal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos hist�rico de los conceptos personal n�mina
			$ls_sql="INSERT INTO sno_hconceptopersonal (codemp, codnom, codper, anocur, codperi, codconc, aplcon, valcon, acuemp, acuiniemp, ".
					"			 acupat, acuinipat) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, anocur, codperi, codconc, aplcon, valcon, acuemp, acuiniemp, ".
					"			 acupat, acuinipat ".
					"       FROM sno_hconceptopersonal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos hist�rico de los proyectos personal n�mina
			$ls_sql="INSERT INTO sno_hproyectopersonal (codemp, codnom, anocur, codperi, codproy, codper, totdiaper, totdiames, pordiames) ".
					"     SELECT codemp, codnom, anocur, codperi, codproy, '".$as_codnue."' as codper, totdiaper, totdiames, pordiames ".
					"       FROM sno_hproyectopersonal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos hist�rico de los prestamos personal n�mina
			$ls_sql="INSERT INTO sno_hprestamos (codemp, codnom, codper, anocur, codperi, numpre, codtippre, codconc, monpre, numcuopre, perinipre, ".
					"			 monamopre, stapre, fecpre, obsrecpre, obssuspre, tipcuopre) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, anocur, codperi, numpre, codtippre, codconc, monpre, numcuopre, perinipre, ".
					"			 monamopre, stapre, fecpre, obsrecpre, obssuspre, tipcuopre ".
					"       FROM sno_hprestamos ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos hist�rico de los prestamos per�odo personal n�mina
			$ls_sql="INSERT INTO sno_hprestamosperiodo (codemp, codnom, codper, anocur, codperi, numpre, codtippre, numcuo, percob, feciniper, ".
					"			 fecfinper, moncuo, estcuo) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, anocur, codperi, numpre, codtippre, numcuo, percob, feciniper, ".
					"			 fecfinper, moncuo, estcuo ".
					"       FROM sno_hprestamosperiodo ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos hist�rico de los prestamos amortizado personal n�mina
			$ls_sql="INSERT INTO sno_hprestamosamortizado (codemp, codnom, codper, numpre, codtippre, anocur, codperi, numamo, peramo, fecamo, ".
					"			 monamo, desamo) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, numpre, codtippre, anocur, codperi, numamo, peramo, fecamo, monamo, ".
					"			 desamo ".
					"       FROM sno_hprestamosamortizado ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos las primas docentes del personal n�mina
			$ls_sql="INSERT INTO sno_hprimadocentepersonal (codemp, codper, anocur, codperi, codnom, codpridoc) ".
					"     SELECT codemp, '".$as_codnue."' as codper, anocur, codperi, codnom, codpridoc ".
					"       FROM sno_hprimadocentepersonal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los prestamos amortizado del personal n�mina
			$ls_sql="INSERT INTO sno_hencargaduria (codemp, anocur, codperi, codenc, codnom, tipenc, fecinienc, fecfinenc, codper, codperenc,".
  					"			 codnomperenc, estenc, obsenc, estsuspernom) ".
					"     SELECT codemp, anocur, codperi, codenc, codnom, tipenc, fecinienc, fecfinenc, '".$as_codnue."' as codper, codperenc,".
  					"			 codnomperenc, estenc, obsenc, estsuspernom ".
					"       FROM sno_hencargaduria ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los prestamos amortizado del personal n�mina
			$ls_sql="INSERT INTO sno_hencargaduria (codemp, anocur, codperi, codenc, codnom, tipenc, fecinienc, fecfinenc, codper, codperenc, ".
  					"			 codnomperenc, estenc, obsenc, estsuspernom) ".
					"     SELECT codemp, anocur, codperi, codenc, codnom, tipenc, fecinienc, fecfinenc, codper, '".$as_codnue."' as  codperenc, ".
  					"			 codnomperenc, estenc, obsenc, estsuspernom ".
					"       FROM sno_hencargaduria ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codperenc='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos hist�rico de la pren�mina personal n�mina
			$ls_sql="INSERT INTO sno_hprenomina (codemp, codnom, codper, anocur, codperi, codconc, tipprenom, valprenom, valhis) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, anocur, codperi, codconc, tipprenom, valprenom, valhis ".
					"       FROM sno_hprenomina ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos hist�rico de la salida personal n�mina
			$ls_sql="INSERT INTO sno_hsalida (codemp, codnom, codper, anocur, codperi, codconc, tipsal, valsal, monacusal, salsal, priquisal, segquisal) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, anocur, codperi, codconc, tipsal, valsal, monacusal, salsal, priquisal, segquisal ".
					"       FROM sno_hsalida ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos hist�rico del resumen personal n�mina
			$ls_sql="INSERT INTO sno_hresumen (codemp, codnom, codper, anocur, codperi, asires, dedres, apoempres, apopatres, priquires, segquires, monnetres, notres) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, anocur, codperi, asires, dedres, apoempres, apopatres, priquires, segquires, monnetres, notres ".
					"       FROM sno_hresumen ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos hist�rico del resumen personal n�mina
			$ls_sql="INSERT INTO sno_hvacacpersonal (codemp, codnom, anocur, codperi, codper, codvac, fecvenvac, fecdisvac, fecreivac, ".
					"			 diavac, stavac, sueintbonvac, sueintvac, diabonvac, obsvac, diapenvac, persalvac, peringvac, dianorvac, ".
					"			 quisalvac, quireivac, diaadivac, diaadibon, diafer, sabdom, periodo_1, cod_1, nro_dias_1, Monto_1, ".
					"			 periodo_2, cod_2, nro_dias_2, Monto_2, periodo_3, cod_3, nro_dias_3, Monto_3, periodo_4, cod_4, nro_dias_4, ".
					"			 Monto_4, periodo_5, cod_5, nro_dias_5, Monto_5, diapag, pagcan, diapervac, pagpersal, calpagvac, profueper) ".
					"     SELECT codemp, codnom, anocur, codperi, '".$as_codnue."' as codper, codvac, fecvenvac, fecdisvac, fecreivac, ".
					"			 diavac, stavac, sueintbonvac, sueintvac, diabonvac, obsvac, diapenvac, persalvac, peringvac, dianorvac, ".
					"			 quisalvac, quireivac, diaadivac, diaadibon, diafer, sabdom, periodo_1, cod_1, nro_dias_1, Monto_1, ".
					"			 periodo_2, cod_2, nro_dias_2, Monto_2, periodo_3, cod_3, nro_dias_3, Monto_3, periodo_4, cod_4, nro_dias_4, ".
					"			 Monto_4, periodo_5, cod_5, nro_dias_5, Monto_5, diapag, pagcan, diapervac, pagpersal, calpagvac, profueper ".
					"       FROM sno_hvacacpersonal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		return $lb_valido;
	}// end function uf_insert_registro
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_registro($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_registro
		//		   Access: private
		//	    Arguments: as_codper  // C�digo del personal
		//	      Returns: lb_valido True si se ejecutaron los delete � False si hubo error en los delete
		//	  Description: Funcion que elimina todos los registros del personal con el viejo c�digo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 30/03/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($lb_valido)
		{// eliminamos hist�rico del resumen personal n�mina
			$ls_sql="DELETE ".
					"  FROM sno_hvacacpersonal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos hist�rico del resumen personal n�mina
			$ls_sql="DELETE ".
					"  FROM sno_hresumen ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos hist�rico de la salida personal n�mina
			$ls_sql="DELETE ".
					"  FROM sno_hsalida ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos hist�rico de la pren�mina personal n�mina
			$ls_sql="DELETE ".
					"  FROM sno_hprenomina ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos hist�rico de los prestamos per�odo personal n�mina
			$ls_sql="DELETE ".
					"  FROM sno_hprestamosamortizado ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos hist�rico de los prestamos per�odo personal n�mina
			$ls_sql="DELETE ".
					"  FROM sno_hprestamosperiodo ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos hist�rico de los prestamos personal n�mina
			$ls_sql="DELETE ".
					"  FROM sno_hprestamos ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos hist�rico de los proyectos personal n�mina
			$ls_sql="DELETE ".
					"  FROM sno_hproyectopersonal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos hist�rico de los conceptos personal n�mina
			$ls_sql="DELETE ".
					"  FROM sno_hconceptopersonal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos hist�rico de las constantes personal n�mina
			$ls_sql="DELETE ".
					"  FROM sno_hconstantepersonal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos hist�rico de las pensiones personal n�mina
			$ls_sql="DELETE ".
					"  FROM sno_hpersonalpension ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos hist�rico del personal n�mina
			$ls_sql="DELETE ".
					"  FROM sno_hpersonalnomina ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos las recepciones de documento
			$ls_sql="DELETE ".
					"  FROM sno_rd ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos la salida del personal n�mina
			$ls_sql="DELETE ".
					"  FROM sno_salida ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos el resumen del personal n�mina
			$ls_sql="DELETE ".
					"  FROM sno_resumen ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos la pren�mina del personal n�mina
			$ls_sql="DELETE ".
					"  FROM sno_prenomina ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los prestamos per�odo del personal n�mina
			$ls_sql="DELETE ".
					"  FROM sno_prestamosamortizado ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los prestamos per�odo del personal n�mina
			$ls_sql="DELETE ".
					"  FROM sno_prestamosperiodo ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los prestamos del personal n�mina
			$ls_sql="DELETE ".
					"  FROM sno_prestamos ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los conceptos del personal n�mina
			$ls_sql="DELETE ".
					"  FROM sno_conceptopersonal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos las constantes del personal n�mina
			$ls_sql="DELETE ".
					"  FROM sno_constantepersonal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los proyectos del personal n�mina
			$ls_sql="DELETE ".
					"  FROM sno_proyectopersonal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos las pensiones personal n�mina
			$ls_sql="DELETE ".
					"  FROM sno_personalpension ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos el personal n�mina
			$ls_sql="DELETE ".
					"  FROM sno_personalnomina ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos el fideicomiso por per�odo del personal
			$ls_sql="DELETE ".
					"  FROM sno_fideiperiodo ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los beneficiarios al ipasme del personal
			$ls_sql="DELETE ".
					"  FROM sno_ipasme_beneficiario ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los afiliados al ipasme del personal
			$ls_sql="DELETE ".
					"  FROM sno_ipasme_afiliado ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos las vacaciones del personal
			$ls_sql="DELETE ".
					"  FROM sno_vacacpersonal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los anticipos  del personal
			$ls_sql="DELETE ".
					"  FROM sno_anticipoprestaciones ".
					"  WHERE codemp='".$this->ls_codemp."'".
					"    AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos la Deuda Anterior del personal
			$ls_sql="DELETE ".
					"  FROM sno_deudaanterior ".
					"  WHERE codemp='".$this->ls_codemp."'".
					"    AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los sueldos historicos del personal
			$ls_sql="DELETE ".
					"  FROM sno_sueldoshistoricos ".
					"  WHERE codemp='".$this->ls_codemp."'".
					"    AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los intereses del fideicomiso del personal
			$ls_sql="DELETE ".
					"  FROM sno_fideiperiodointereses ".
					"  WHERE codemp='".$this->ls_codemp."'".
					"    AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos el fideicomiso del personal
			$ls_sql="DELETE ".
					"  FROM sno_fideicomiso ".
					"  WHERE codemp='".$this->ls_codemp."'".
					"    AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los permisos del personal
			$ls_sql="DELETE ".
					"  FROM sno_permiso ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos el ISR por meses del personal
			$ls_sql="DELETE ".
					"  FROM sno_personalisr ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los Estudios Realizados del personal
			$ls_sql="DELETE ".
					"  FROM sno_estudiorealizado ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los Trabajos Anteriores del personal
			$ls_sql="DELETE ".
					"  FROM sno_trabajoanterior ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los Familiares del personal
			$ls_sql="DELETE ".
					"  FROM sno_familiar ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los Beneficiarios del personal
			$ls_sql="DELETE ".
					"  FROM sno_beneficiario ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos en Personal
			$ls_sql="DELETE ".
					"  FROM sno_personal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		return $lb_valido;
	}// end function uf_delete_registro
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_sql($as_sql)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_sql
		//		   Access: private
		//	    Arguments: as_sql  // Sentencia SQL que se quiere ejecutar
		//	      Returns: lb_valido True si se ejecuto el sql � False si hubo error en el sql
		//	  Description: Funcion que ejecuta un sql
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 30/03/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_row=$this->io_sql->execute($as_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Personal M�TODO->uf_procesar_sql ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
	}// end function uf_procesar_sql
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_fechaingreso($as_codper,&$ad_fecingper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_fechaingreso
		//		   Access: public (sigesp_snorh_c_fideicomiso)
		//	    Arguments: as_codper  // C�digo del personal
		//			  	   ad_fecingper  // Fecha de Ingreso Personal
		//	      Returns: lb_valido True si el select no tuvo errores � False si hubo error
		//	  Description: Funcion que obtiene el c�digo la fecha de ingreso a la instituci�n
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 12/04/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$ls_sql="SELECT fecconpreant, conpreant ".
				"  FROM sno_fideicomiso ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Personal M�TODO->uf_load_fechaingreso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if(!$rs_data->EOF)
			{
				$ld_fecconpreant=$rs_data->fields["fecconpreant"];	
				if(strtotime($ld_fecconpreant)<=strtotime("1997-06-19"))
				{
					$ld_fecconpreant="1997-06-19";
				}			
				if ($row["conpreant"]=='1')	
				{
					$ad_fecingper=$ld_fecconpreant;
				}
			}
			if ($ad_fecingper=='')
			{
				$ls_sql="SELECT fecingper ".
						"  FROM sno_personal ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codper='".$as_codper."'";
				$rs_data=$this->io_sql->select($ls_sql);
				if($rs_data===false)
				{
					$this->io_mensajes->message("CLASE->Personal M�TODO->uf_load_fechaingreso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$lb_valido=false;
				}
				else
				{
					if(!$rs_data->EOF)
					{
						$ad_fecingper=$rs_data->fields["fecingper"];
						if(strtotime($ad_fecingper)<=strtotime("1997-06-19"))
						{
							$ad_fecingper="1997-06-19";
						}
					}	
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		if ($ad_fecingper=='')
		{
	       	$this->io_mensajes->message("No se pudo obtener la fecha de ingreso del personal ".$as_codper.". Favor Verifique la misma "); 
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_load_fechaingreso
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_beneficiario($as_cedper,$as_nomper,$as_apeper,$as_dirper,$as_telhabper,$as_telmovper,$as_coreleper,
									$as_cuentacontable,$as_codpai,$as_codest,$as_codmun,$as_codpar,$as_nacper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_beneficiario
		//		   Access: private
		//	    Arguments: as_cedper  // C�dula del personal
		//			  	   as_nomper  // Nombre del Personal
		//			  	   as_apeper  // Apellido del Personal
		//			  	   as_dirper  // Direcci�n del Personal
		//			  	   as_telhabper  // Tel�fono de Habitaci�n del Personal
		//			  	   as_telmovper  // Tel�fono M�vil del Personal
		//			  	   as_coreleper  // Correo del Personal
		//			  	   as_cuentacontable  // Cuenta Contable
		//			  	   as_codpai  // C�digo del Pa�s
		//			  	   as_codest  // C�digo del Estado
		//			  	   as_codmun  // C�digo del Municipio
		//			  	   as_codpar  // C�digo del Parroquia
		//			  	   as_nacper  // Naconalidad
		//			  	   aa_seguridad  // Arreglo de las Variables de Seguridad
		//	      Returns: lb_valido True si el select no tuvo errores � False si hubo error
		//	  Description: Funcion que inserta el personal como beneficiario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 02/08/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_tipconben="F";
		$ls_bansigcof='---';
		$ls_codban='---';
		$ls_ctaban="";	
		$ls_sql="SELECT ced_bene ".
				"  FROM rpc_beneficiario ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND ced_bene='".$as_cedper."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Personal M�TODO->uf_insert_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if(!$rs_data->EOF)
			{
        		$this->io_mensajes->message("La C�dula del personal ya existe como beneficiario"); 
			}
			else
			{
				$ls_sql="INSERT INTO rpc_beneficiario(codemp, ced_bene, nombene, apebene, dirbene, telbene, celbene, email, sc_cuenta, ".
						"codpai,codest,codmun,codpar,nacben,tipconben,codbansig,codban,ctaban,fecregben) VALUES ('".$this->ls_codemp."', ".
						"'".$as_cedper."', '".$as_nomper."', '".$as_apeper."', '".$as_dirper."', '".$as_telhabper."', '".$as_telmovper."', ".
						"'".$as_coreleper."', '".$as_cuentacontable."', '".$as_codpai."', '".$as_codest."', '".$as_codmun."', '".$as_codpar."', ".
						"'".$as_nacper."','".$ls_tipconben."','".$ls_bansigcof."','".$ls_codban."','".$ls_ctaban."','".date('Y-m-d')."') ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Personal M�TODO->uf_insert_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insert� el Personal ".$as_cedper." como un beneficiario";
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				 }	  	
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;	
	}// end function uf_insert_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_beneficiario($as_cedper,$as_nomper,$as_apeper,$as_dirper,$as_telhabper,$as_telmovper,$as_coreleper,
									$as_codpai,$as_codest,$as_codmun,$as_codpar,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_beneficiario
		//		   Access: private
		//	    Arguments: as_codper  // C�digo del Personal
		//				   as_codben  // C�digo del Beneficiario
		//				   as_cedben  // Cedula del Beneficiario
		//				   as_nomben  // Nombre del Beneficiario
		//				   as_apeben  // Apellido del Beneficiario
		//				   as_dirben  // Direccion del Beneficiario
		//				   as_telben  // Telefono del Beneficiario
		//				   as_tipben  // Tipo de beneficiario
		//				   as_nomcheben  // Nombre del cheque del Beneficiario
		//				   ai_porpagben //  Porcentaje de pago del Beneficiario
		//				   ai_monpagben //  Monto del pago  del Beneficiario
		//				   as_codban //  C�digo de Banco
		//				   as_ctaban //  Cuenta de Banco
		//				   as_forpagben  // Forma de Pago del Beneficiario
		//				   as_nacben  // Nacionalidad del Beneficiario
		//				   as_tipcueben  // Tipo de Cuenta del Beneficiario
		//                 as_nexben  // parentesco del beneficiario con el trabajador
		//				   as_cedaut  // cedula del autorizado
		//                 as_numexpben // numero de expdiente del beneficiario
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update � False si hubo error en el update
		//	  Description: Funcion que actualiza el beneficiario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 07/11/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE rpc_beneficiario ".
				"   SET nombene='".$as_nomper."', ".
				"		apebene='".$as_apeper."', ".
				"		dirbene='".$as_dirper."', ".
				"		telbene='".$as_telhabper."', ".
				"		celbene='".$as_telmovper."', ".
				"		email='".$as_coreleper."', ".
				"		codpai='".$as_codpai."', ".
				"		codest='".$as_codest."', ".
				"		codmun='".$as_codmun."', ".
				"		codpar='".$as_codpar."' ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND ced_bene='".$as_cedper."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Beneficiario M�TODO->uf_update_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			if($lb_valido)
			{	
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
	        	$this->io_mensajes->message("CLASE->Beneficiario M�TODO->uf_update_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_periodo($as_codper)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_periodo
		//		   Access: private
		//	    Arguments: 
		//	      Returns: $codperi, devuelve el codigo del maximo periodo calculado a una persona
		//	  Description: 
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creaci�n: 01/09/2008								Fecha �ltima Modificaci�n : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$codperi="";
		$ls_sql="SELECT MAX(sno_hresumen.codperi) AS codperi ".
		        "  FROM sno_hresumen ".
		        " WHERE sno_hresumen.codemp='".$this->ls_codemp."' ".
				"   AND sno_hresumen.codper='".$as_codper."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Personal M�TODO->uf_buscar_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 			
		}
		else
		{
			if(!$rs_data->EOF)
			{
				$codperi=$rs_data->fields["codperi"];				
			}
			$this->io_sql->free_result($rs_data);
		}
     	return $codperi;
	}// fin de uf_buscar_periodo
	//------------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------
    function uf_buscar_nomina($as_codperi,$as_codper)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_nomina
		//		   Access: private
		//	    Arguments: 
		//	      Returns: $codnom, devuelve el codigo del maxima nomina calculado a una persona
		//	  Description: 
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creaci�n: 01/09/2008								Fecha �ltima Modificaci�n : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$codnom="";
		$ls_sql="SELECT MAX(sno_hresumen.codnom) AS codnom ".
		        "  FROM sno_hresumen ".
				" WHERE sno_hresumen.codemp='".$this->ls_codemp."'".
				"   AND sno_hresumen.codper='".$as_codper."'".
				"   AND sno_hresumen.codperi='".$as_codperi."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Personal M�TODO->uf_buscar_nomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 			
		}
		else
		{
			if(!$rs_data->EOF)
			{
				$codnom=$rs_data->fields["codnom"];				
			}
			$this->io_sql->free_result($rs_data);
		}
     	return $codnom;
	}// fin de uf_buscar_nomina
	//------------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------
    function uf_buscar_fecha_periodo($as_codper)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_fecha_periodo
		//		   Access: private
		//	    Arguments: 
		//	      Returns: $codnom, devuelve el codigo del maxima nomina calculado a una persona
		//	  Description: 
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creaci�n: 01/09/2008								Fecha �ltima Modificaci�n : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_fechaperi="";
		$as_codperi=$this->uf_buscar_periodo($as_codper);
		$as_codnom=$this->uf_buscar_nomina($as_codperi, $as_codper);
		if (($as_codperi!="")&&($as_codnom!=""))
		{
			$ls_sql="SELECT sno_hperiodo.fechasper ".
					"  FROM sno_hperiodo ".
					" WHERE sno_hperiodo.codemp='".$this->ls_codemp."'".
					"   AND sno_hperiodo.codperi='".$as_codperi."' ". 
					"   AND sno_hperiodo.codnom='".$as_codnom."'"; 
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Personal M�TODO->uf_buscar_fecha_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 			
			}
			else
			{
				if(!$rs_data->EOF)
				{
					$as_fechaperi=$rs_data->fields["fechasper"];				
				}
				$this->io_sql->free_result($rs_data);
			}
		}
     	return $as_fechaperi;
	}// fin de uf_buscar_fecha_periodo
	//------------------------------------------------------------------------------------------------------------------------------------
	 function uf_buscar_fecha_periodo_inicio($as_codper)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_fecha_periodo_inicio
		//		   Access: private
		//	    Arguments: 
		//	      Returns: $codnom, devuelve el codigo del maxima nomina calculado a una persona
		//	  Description: 
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creaci�n: 01/09/2008								Fecha �ltima Modificaci�n : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_fechaperi="";
		$as_codperi=$this->uf_buscar_periodo($as_codper);
		$as_codnom=$this->uf_buscar_nomina($as_codperi, $as_codper);
		if (($as_codperi!="")&&($as_codnom!=""))
		{
			$ls_sql="SELECT sno_hperiodo.fecdesper ".
					"  FROM sno_hperiodo ".
					" WHERE sno_hperiodo.codemp='".$this->ls_codemp."'".
					"   AND sno_hperiodo.codperi='".$as_codperi."' ". 
					"   AND sno_hperiodo.codnom='".$as_codnom."'"; 
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Personal M�TODO->uf_buscar_fecha_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 			
			}
			else
			{
				if(!$rs_data->EOF)
				{
					$as_fechaperi=$rs_data->fields["fecdesper"];				
				}
				$this->io_sql->free_result($rs_data);
			}
		}
     	return $as_fechaperi;
	}// fin de uf_buscar_fecha_periodo_inicio
	//------------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_personal_nomina($as_codper)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_buscar_personal_nomina
		//		   Access: private
		//	    Arguments: 
		//	      Returns: $codperi, devuelve el codigo del maximo periodo calculado a una persona
		//	  Description: 
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creaci�n: 02/09/2008								Fecha �ltima Modificaci�n : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$codper="";
		$ls_sql="SELECT DISTINCT(codper) as codper ".
		        "  FROM sno_resumen ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codper='".$as_codper."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Personal M�TODO->uf_buscar_personal_nomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 			
		}
		else
		{
			if(!$rs_data->EOF)
			{
				$codper=$rs_data->fields["codper"];				
			}
			$this->io_sql->free_result($rs_data);
		}
     	return $codper;
	}// fin de uf_buscar_periodo
	//------------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------  
    function uf_select_anotrabajoantfijo($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_anotrabajoantfijo
		//		   Access: public (sigesp_snorh_d_trabajoanterior)
		//	    Arguments: as_codper // C�digo de Personal				       
		//	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que busca los a�os de trabajo previos como fijo
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="";	
		$anofijo=0;	
		  $ls_sql="SELECT SUM(anolab) as anolab FROM sno_trabajoanterior ".
				  " WHERE codemp='".$this->ls_codemp."' ". 
				  "   AND codper='".$as_codper."' ".
				  "   AND emppubtraant='1' ".
				  "   AND (codded='100' OR codded='200') ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Personal M�TODO->uf_select_anotrabajoantfijo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$rs_data->EOF)
			{
				$anofijo=$rs_data->fields["anolab"]; 
			}
			if ($anofijo=="")
			{
				$anofijo=0;
			}	
			$this->io_sql->free_result($rs_data);
		}
		return $anofijo;
	}// end function uf_select_trabajoanterior
	//------------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------
    function uf_select_anotrabajoantcontratado($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_anotrabajoantcontratado
		//		   Access: public (sigesp_snorh_d_trabajoanterior)
		//	    Arguments: as_codper // C�digo de Personal				       
		//	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que busca los a�os de trabajo previos como fijo
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="";	
		$anocont=0;	
		$ls_sql="SELECT SUM(anolab) as anolab ".
				"  FROM sno_trabajoanterior ".
			    " WHERE codemp='".$this->ls_codemp."' ". 
			    "   AND codper='".$as_codper."' ".
			    "   AND emppubtraant='1' ".
			    "	AND (codded='300') "; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Personal M�TODO->uf_select_anotrabajoantfijo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$rs_data->EOF)
			{
				$anocont=$rs_data->fields["anolab"]; 
			}
			if ($anocont=="")
			{
				$anocont=0;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $anocont;
	}//uf_select_anotrabajoantcontratado
	//------------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_codigo_unico_rac_personal($as_codper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_codigo_unico_rac
		//		   Access: private
		//	    Arguments: as_codper // c�digo del personal
		//	      Returns: lb_existe True si se encontro � False si no se encontr�
		//	  Description: Funcion que selecciona el c�digo unico de rac asociado a la persona para verificar si esta en uso.
		//	   Creado Por: Ing. Mar�a Beatriz Unda
		// Fecha Creaci�n: 03/11/2008 								Fecha �ltima Modificaci�n : 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;	
		$as_codasicar="";
		$ls_sql="SELECT codunirac ".
				"  FROM sno_personalnomina ".
				" WHERE codemp='".$this->ls_codemp."'".				
				"   AND codper='".$as_codper."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Personal M�TODO->uf_select_codigo_unico_rac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while((!$rs_data->EOF))
			{
				$ls_codunirac=$rs_data->fields["codunirac"];
				$ls_estcodunirac=$this->io_asignacioncargo->uf_select_estatus_codigo_unico_rac($ls_codunirac);
				if ($ls_estcodunirac=='1')
				{
					$lb_valido=$this->uf_update_codigo_unico_rac_personal($as_codper,$aa_seguridad);
					$this->io_mensajes->message("El c�digo �nico de RAC ".$ls_codunirac." asociado al personal ".$as_codper." est� en uso, por lo cual debe asignarle un nuevo c�digo.");
					
				}
				else
				{
					$lb_valido=$this->io_asignacioncargo->uf_update_estatus_codigo_unico_rac($as_codper,'1',$aa_seguridad);
				}
				$rs_data->MoveNext();
			}
			
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_select_codigo_unico_rac
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------	
    function uf_update_codigo_unico_rac_personal($as_codper,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_codigo_unico_rac_personal
		//		   Access: private
		//	    Arguments: as_codper // c�digo del personal
		//                 as_valor  // valor para actualizar
		//	      Returns: lb_valido True si se ejecuto el delete � False si hubo error en el delete
		//	  Description: Funcion que actualiza el  c�digo �nico de rac de la tabla sno_personalnomina
		//                 al egeresar un personal      
		//	   Creado Por: Ing. Mar�a Beatriz Unda
		// Fecha Creaci�n: 03/11/2008 								Fecha �ltima Modificaci�n : 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_personalnomina ".
				"   SET codunirac='' ".
				"   WHERE codemp='".$this->ls_codemp."' ".								
				"    AND  codper='".$as_codper."' ";
					
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Personal M�TODO->uf_update_codigo_unico_rac_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualiz� el c�digo �nico de RAC asociados al personal ".$as_codper;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_update_codigo_unico_rac_personal
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>