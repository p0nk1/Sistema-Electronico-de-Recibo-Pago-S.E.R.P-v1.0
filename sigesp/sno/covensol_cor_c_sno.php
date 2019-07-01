<?php
if($ruta==''){$ruta="../";}
require_once($ruta.'srh/covensol/clases/covensol_cor_c_srh.php');
class covensol_cor_c_sno extends covensol_cor_c_srh {

	function covensol_cor_c_sno($propiedades=array()){		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Function: correspondencia
		// Access: public (covensol_cor_c_sno)
		// Description: Constructor de la Clase
		// Creado Por: Lic. Edgar A. Quintero
		// Fecha Creación: 05/02/2012 								
		// Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ruta;
		
		if($ruta==''){$ruta="../../";}		
		require_once($ruta."shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once($ruta."shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once($ruta."shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();
		require_once($ruta."shared/class_folder/sigesp_conexiones.php");
		$this->io_conexiones=new conexiones();
		require_once($ruta."shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once($ruta."shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();				
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		
		if($propiedades['habilitar_json_lib']){
			require_once($ruta.'shared/class_folder/JSON.php');
			$this->json = new JSON();
		}	
		$this->postgres_ilike = '';
		if($_SESSION["ls_gestor"] == 'POSTGRES'){$this->postgres_ilike = 'I';}
		
	}
	
	function ConsultaErroresUnidadAdm($param=array()){
	
			$campos = " * ";
			$criteriosql='';
			$criterio="";
			$param['criterio'] = $param['criterio']?$param['criterio']:'por_listado';
								   
			$query_rs = "  SELECT ua.minorguniadm||ua.ofiuniadm||ua.uniuniadm||ua.depuniadm||ua.prouniadm AS coduniadm,ua.desuniadm,
								   ua.codestpro1||ua.codestpro2||ua.codestpro3||ua.codestpro4||ua.codestpro5 AS spg_ua,
								   p5.codestpro1||p5.codestpro2||p5.codestpro3||p5.codestpro4||p5.codestpro5 AS spg_pg,
								   ua.estcla AS estcla_ua,p5.estcla AS estcla_pg,
								   ua.codestpro1,ua.codestpro2,ua.codestpro3,ua.codestpro4,ua.codestpro5
							FROM sno_unidadadmin ua
							LEFT JOIN spg_ep5 p5 ON p5.codemp = ua.codemp 
												 AND p5.codestpro1 = ua.codestpro1 
												 AND p5.codestpro2 = ua.codestpro2
												 AND p5.codestpro3 = ua.codestpro3
												 AND p5.codestpro4 = ua.codestpro4
												 AND p5.codestpro5 = ua.codestpro5
												 AND p5.estcla = ua.estcla ";			
			
			//echo $query_rs.'<br>';
			$clase = get_class($this);
			$metodo = 'ConsultaErroresUnidadAdm';
			$param['arreglo'] = 'arreglo';
			$param['ajax'] = '0';
			$param['imprimir'] = '1';	
			$msj = '<b>CLASE:</b> '.$clase.' <br><b>METODO:</b> '.$metodo;	
			$respuesta = $this->io_conexiones->conexion($query_rs,$param,$msj);	
			return $respuesta;
	
	
	}
	
	function FormatearEstructuraPresup($param=array()){
				
							
				$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
				$ls_incio1=25-$ls_loncodestpro1;
				$presup['codestpro1']=substr($param['codestpro1'],$ls_incio1,$ls_loncodestpro1);
				
				$ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
				$ls_incio2=25-$ls_loncodestpro2;
				$presup['codestpro2']=substr($param['codestpro2'],$ls_incio2,$ls_loncodestpro2);
				
				$ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
				$ls_incio3=25-$ls_loncodestpro3;
				$presup['codestpro3']=substr($param['codestpro3'],$ls_incio3,$ls_loncodestpro3);
				
				$ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
				$ls_incio4=25-$ls_loncodestpro4;
				$presup['codestpro4']=substr($param['codestpro4'],$ls_incio4,$ls_loncodestpro4);
				
				$ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
				$ls_incio5=25-$ls_loncodestpro5;
				$presup['codestpro5']=substr($param['codestpro5'],$ls_incio5,$ls_loncodestpro5);
				
				$presup['programatica'] = $presup['codestpro1'].'-'.$presup['codestpro2'].'-'.$presup['codestpro3'];
				if($_SESSION["la_empresa"]["estmodest"]==2){
					$presup['programatica'] .= '-'.$presup['codestpro4'].'-'.$presup['codestpro5'];
				}
				
				return $presup;
	
	}
	
	
	
	
}//////////////////////////////////////////////////////////////******* FIN CLASE covensol_cor_c_sno *******/////////////////////////////////////////////////////////


?>
