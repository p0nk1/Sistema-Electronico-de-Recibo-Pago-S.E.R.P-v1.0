<?php
session_start();               
	$DB_HOST='192.168.0.90';                 
	$DB_USER='sametsis';                     
	$DB_PASS='+s4b1dur14+';                      
	$DB_NAME3='db_saberytrabajo_2015'; 
	$DB_NAME2='db_saberytrabajo_2016';            
	$DB_NAME1='db_saberytrabajo_2017';            
	$DB_NAME4='db_saberytrabajo_2018_reconversion';
  $DB_NAME5='db_saberytrabajo_2019';
	$DB_NAME='db_saberytrabajo_2018';
	$DB_PORT='5432';
	

$_SESSION=array ( 'ls_nombrelogico' => 'UNES_2011', 'ls_database' => $DB_NAME, 
 'ls_hostname' => $DB_HOST, 'ls_login' => $DB_USER, 'ls_password' => $DB_PASS, 
 'ls_gestor' => 'POSTGRES', 'ls_port' => $DB_PORT, 'ls_width' => '70', 
 'ls_height' => '70', 'ls_logo' => 'logo.jpg', 'gi_posicion' => '1', 'ls_firma'=>'firma.jpg', 'gi_posicion'=>'2', 'la_empresa' => array ( 'codemp' => '0001', 
 'nombre' => 'FUNDACION GRAN MISION SABER Y TRABAJO', 'titulo' => 'GMSYT', 'sigemp' => 'SIGESP', 
 'direccion' => 'AV. PRINCIPAL DE BOLEITA NORTE, FINAL CALLE MIRAIMA, EDF. GRAN MISION SABER Y TRABAJO', 'telemp' => '', 
 'faxemp' => '', 'email' => 'talentohumano@gmsaberytrabajo.gob.ve', 'website' => 'gmsaberytrabajo.gob.ve', 'm01' => '1', 
 'm02' => '0', 'm03' => '0', 'm04' => '0', 'm05' => '0', 'm06' => '0', 'm07' => '0', 'm08' => '0', 
 'm09' => '0', 'm10' => '0', 'm11' => '0', 'm12' => '0', 'periodo' => '2012-01-01', 'vali_nivel' => '4', 'esttipcont' => '1', 
 'formpre' => '9-99-99-99-99', 'formcont' => '9-9-9-99-99-99-999', 'formplan' => '9-9-9-99-99-99-99', 
 'formspi' => '999-99-99-99-99-99-99', 'activo' => '1', 'pasivo' => '2', 'ingreso' => '5', 'gasto' => '6', 
 'resultado' => '3', 'capital' => '3', 'c_resultad' => '32502010101001', 'c_resultan' => '32501010101001',
  'orden_d' => '1', 'orden_h' => '2', 'soc_gastos' => '402,404,407', 'soc_servic' => '403,404,407',
   'gerente' => NULL, 'jefe_compr' => NULL, 'activo_h' => '11', 'pasivo_h' => '22', 'resultado_h' => '12', 'ingreso_f' => 
   '1', 'gasto_f' => '2', 'ingreso_p' => '3', 'gasto_p' => '4', 'logo' => 'N/A', 'numniv' => '3', 'nomestpro1' => 
   'Proyecto y/o Acciones Centralizadas', 'nomestpro2' => 'Acciones Especificas', 'nomestpro3' => 'Otros.', 'nomestpro4' => '',
    'nomestpro5' => '', 'estvaltra' => '1', 'rifemp' => 'G-20007599-6', 'nitemp' => '', 'estemp' => 'DISTRITO CAPITAL', 'ciuemp' => 'CARACAS', 
    'zonpos' => '1010', 'estmodape' => '0', 'estdesiva' => '0', 'estprecom' => '0', 'estmodsepsoc' => NULL, 'codorgsig' => '', 'socbieser' => '1',
     'estmodest' => '1', 'salinipro' => '0', 'salinieje' => '0', 'numordcom' => '0', 'numordser' => '0', 'numsolpag' => '0', 'nomorgads' => '', 
     'numlicemp' => '0000000000000000000000000', 'modageret' => 'B', 'nomres' => 'GMSYT', 'concomiva' => '000001', 'cedben' => '', 'nomben' => '',
      'scctaben' => '', 'estmodiva' => '1', 'activo_t' => '', 'pasivo_t' => '', 'resultado_t' => '', 'c_financiera' => '', 'c_fiscal' => '', 
      'diacadche' => '', 'codasiona' => '', 'loncodestpro1' => '6', 'loncodestpro2' => '6', 'loncodestpro3' => '5', 'loncodestpro4' => '0', 
      'loncodestpro5' => '0', 'candeccon' => NULL, 'tipconmon' => NULL, 'redconmon' => NULL, 'conrecdoc' => '0', 'estvaldis' => '1', 'nroivss' => '', 
      'nomrep' => '', 'cedrep' => '', 'telfrep' => '', 'cargorep' => '', 'estretiva' => 'C', 'clactacon' => '0', 'estempcon' => '0', 'codaltemp' => ' ',
       'basdatcon' => '', 'estcamemp' => '0', 'estparsindis' => '1', 'estciespg' => '0', 'estciespi' => '0', 'basdatcmp' => '', 'confinstr' => 'N', 
       'estintcred' => '0', 'estciescg' => '0', 'estvalspg' => '0', 'ctaspgrec' => NULL, 'ctaspgced' => NULL, 'estmodpartsep' => '0', 'estmodpartsoc' => '0',
        'estmanant' => '0', 'estpreing' => '0', 'concommun' => '000001', 'confiva' => 'P', 'casconmov' => '0', 'estmodprog' => '0', 'confi_ch' => '0',
         'dirvirtual' => 'sigesp_ipsfa', 'ctaresact' => '', 'ctaresant' => '', 'estvaldisfin' => 'N', 'dedconproben' => '0', 'estaprsep' => ' ', 
         'sujpasesp' => '0', 'bloanu' => '1', ), 'sigesp_sitioweb' => 'sigesp_ipsfa', 'sigesp_servidor' => $DB_HOST, 'sigesp_usuario' => $DB_USER, 
         'sigesp_clave' => $DB_PASS, 'sigesp_basedatos' => $DB_NAME, 'sigesp_gestor' => 'POSTGRES', 'sigesp_servidor_apr' => $DB_HOST, 'sigesp_usuario_apr' => 'postgres',
          'sigesp_clave_apr' => $DB_PASS, 'sigesp_basedatos_apr' => $DB_NAME, 'sigesp_gestor_apr' => 'POSTGRES', 'la_cedusu' => '123456', 
          'la_nomusu' => $nomper_rp, 'la_apeusu' => $apeper_rp, 'la_codusu' => 'recibopago ', 'la_pasusu' => 'E10ADC3949BA59ABBE56E057F20F883E', 
          'la_logusr' => 'recibopago', 'la_permisos' => -1, 'la_ususeg' => 'recibopago ', 'la_tipo_usugrup' => 'U',
       'la_sistema' => array ( 'sistema' => 'SNO', ), 'cedula_rp' => $_SESSION['cedula_rp'], 'ls_database2' => $DB_NAME2, 'ls_database1' => $DB_NAME1, 'ls_database3' => $DB_NAME3, 'ls_database4' => $DB_NAME4, 'ls_database5' => $DB_NAME5); 

?>
