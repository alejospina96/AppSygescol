<?php
$roles_con_permiso = array('99', '6');
include_once("inc.configuracion.php");
include_once("inc.validasesion.php");
include_once("inc.funciones.php");
include_once("conexion.php");
//Conexi&oacute;n a la base de datos
$link = conectarse();
mysql_select_db($database_sygescol,$link);
$total2validar = substr($_SERVER['PHP_SELF'], 14, 1000);
$sel_modulos1validar = "SELECT m_p_a.perfil_id perfil, m_p_a.mod_id, m_s.mod_link archivo
FROM modulos_sygescol m_s
JOIN modulos_perfil_accesos m_p_a ON ( m_s.mod_id = m_p_a.mod_id) where m_p_a.perfil_id = '".$_SESSION['perfil_id']."' AND m_s.mod_link =  '$total2validar' ";
$sql_modulos1validar= mysql_query($sel_modulos1validar, $link)or die(mysql_error());
$num_modulos1validar = mysql_num_rows($sql_modulos1validar);
//$rows_datos1 = mysql_fetch_array($sql_modulos1);
if($num_modulos1validar == 0)
{
?>
<script type="text/javascript">
	 window.location.href='bloquear_sygescol.php';
</script>
<?php
}
$selSuperior = "SELECT * FROM escala_nacional WHERE esca_nac_letra = 'S'";
$sqlSuperior = mysql_query($selSuperior, $link);
$rowSuperior = mysql_fetch_array($sqlSuperior);
$notaSupMin = substr($rowSuperior['esca_nac_min'],0,3);
$notaSupMax = substr($rowSuperior['esca_nac_max'],0,3);
$selAlto = "SELECT * FROM escala_nacional WHERE esca_nac_letra = 'A'";
$sqlAlto = mysql_query($selAlto, $link);
$rowAlto = mysql_fetch_array($sqlAlto);
$notaAltoMin = substr($rowAlto['esca_nac_min'],0,3);
$notaAltoMax = substr($rowAlto['esca_nac_max'],0,3);
$selBasico = "SELECT * FROM escala_nacional WHERE esca_nac_letra = 'Bs'";
$sqlBasico = mysql_query($selBasico, $link);
$rowBasico = mysql_fetch_array($sqlBasico);
$notaBasicoMin = substr($rowBasico['esca_nac_min'],0,3);
$notaBasicoMax = substr($rowBasico['esca_nac_max'],0,3);
$selBajo = "SELECT * FROM escala_nacional WHERE esca_nac_letra = 'Bj'";
$sqlBajo = mysql_query($selBajo, $link);
$rowBajo = mysql_fetch_array($sqlBajo);
$notaBajoMin = substr($rowBajo['esca_nac_min'],0,3);
$notaBajoMax = substr($rowBajo['esca_nac_max'],0,3);
function ConsultarTextoCriterio($nombre)
{
	global $link, $database_sygescol;
	mysql_select_db($database_sygescol,$link);
	$descripcion = '';
	$sel = "SELECT * FROM criterios_promocion_texto WHERE criterio_nombre='".$nombre."'";
	$sql = mysql_query($sel, $link);
	if(mysql_num_rows($sql)>0){
		$row = mysql_fetch_assoc($sql);
		$descripcion = $row['criterio_texto'];
	}
	return $descripcion;
}
$query_ano_sistema = "SELECT `year`.b FROM `year`";
$ano_sistema = mysql_query($query_ano_sistema, $sygescol) or die(mysql_error());
$row_ano_sistema = mysql_fetch_assoc($ano_sistema);
$totalRows_ano_sistema = mysql_num_rows($ano_sistema);
if (!function_exists("GetSQLValueString")) {
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
	{
	  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
	  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);
	  switch ($theType) {
	    case "text":
	      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
	      break;
	    case "long":
	    case "int":
	      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
	      break;
	    case "double":
	      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
	      break;
	    case "date":
	      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
	      break;
	    case "defined":
	      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue; break;
	  }
	  return $theValue; }
 }
if(isset($_POST['actualizar']))
 {
 	$admin_transporte = $_POST['admin_transporte'];
$count_admin_transporte = count($admin_transporte);
for ($i=0; $i < $count_admin_transporte ; $i++) 
{ 
$final_admin_transporte .= $admin_transporte[$i].",";
}
$sql_transporte_ant = "SELECT * FROM conf_sygescol WHERE conf_id = 254";
$sel_transporte_ant = mysql_query($sql_transporte_ant,$link);
$rows_transporte_ant = mysql_fetch_assoc($sel_transporte_ant);
if ($rows_transporte_ant['conf_valor'] != "") 
{
$explode = explode("$", $rows_transporte_ant['conf_valor']);
$doc_ant = $explode[1];
if ($doc_ant != "")
{
$guardar_transporte = $_POST['aplicatransporte']."$".$doc_ant.$final_admin_transporte;
}
}
else
{
$guardar_transporte = $_POST['aplicatransporte']."$".$final_admin_transporte;
}


//////////////////////////////////////PAE
$admin_pae_am = $_POST['docente_am'];
$count_admin_pae_am = count($admin_pae_am);
for ($i=0; $i < $count_admin_pae_am ; $i++) 
{ 
$final_admin_pae_am .= $admin_pae_am[$i].",";
}
$sql_admin_pae_am_ant = "SELECT * FROM conf_sygescol WHERE conf_id = 251";
$sel_admin_pae_am_ant = mysql_query($sql_admin_pae_am_ant,$link);
$rows_admin_pae_am_ant = mysql_fetch_assoc($sel_admin_pae_am_ant);
if ($rows_admin_pae_am_ant['conf_valor'] != "") 
{
$explode = explode("=", $rows_admin_pae_am_ant['conf_valor']);
$doc_ant = $explode[2];
$explode = explode("$", $doc_ant);
$doc_ant = $explode[0];
if ($doc_ant != "")
{
$guardar_admin_pae_am = $doc_ant.$final_admin_pae_am;
}
else
{
$guardar_admin_pae_am = $final_admin_pae_am;
}
}
else
{
$guardar_admin_pae_am = $final_admin_pae_am;
}
$admin_pae_al = $_POST['docente_al'];
$count_admin_pae_al = count($admin_pae_al);
for ($i=0; $i < $count_admin_pae_al ; $i++) 
{ 
$final_admin_pae_al .= $admin_pae_al[$i].",";
}
$sql_admin_pae_al_ant = "SELECT * FROM conf_sygescol WHERE conf_id = 251";
$sel_admin_pae_al_ant = mysql_query($sql_admin_pae_al_ant,$link);
$rows_admin_pae_al_ant = mysql_fetch_assoc($sel_admin_pae_al_ant);
if ($rows_admin_pae_al_ant['conf_valor'] != "") 
{
$explode = explode("=", $rows_admin_pae_al_ant['conf_valor']);
$doc_ant = $explode[2];
$explode = explode("$", $doc_ant);
$doc_ant = $explode[1];
if ($doc_ant != "")
{
$guardar_admin_pae_al = $doc_ant.$final_admin_pae_al;
}
else
{
$guardar_admin_pae_al = $final_admin_pae_al;
}
}
else
{
$guardar_admin_pae_al = $final_admin_pae_al;
}


$admin_pae_pm = $_POST['docente_pm'];
$count_admin_pae_pm = count($admin_pae_pm);
for ($i=0; $i < $count_admin_pae_pm ; $i++) 
{ 
$final_admin_pae_pm .= $admin_pae_pm[$i].",";
}
$sql_admin_pae_pm_ant = "SELECT * FROM conf_sygescol WHERE conf_id = 251";
$sel_admin_pae_pm_ant = mysql_query($sql_admin_pae_pm_ant,$link);
$rows_admin_pae_pm_ant = mysql_fetch_assoc($sel_admin_pae_pm_ant);
if ($rows_admin_pae_pm_ant['conf_valor'] != "") 
{
$explode = explode("=", $rows_admin_pae_pm_ant['conf_valor']);
$doc_ant = $explode[2];
$explode = explode("$", $doc_ant);
$doc_ant = $explode[2];
if ($doc_ant != "")
{
$guardar_admin_pae_pm = $doc_ant.$final_admin_pae_pm;
}
else
{
$guardar_admin_pae_pm = $final_admin_pae_pm;
}
}
else
{
$guardar_admin_pae_pm = $final_admin_pae_pm;
}
$hora_inicio = $_POST['hora_inicio'];
$hora_fin = $_POST['hora_fin'];
$lunes_a_viernes = $_POST['diaspae1'];
$sabado = $_POST['diaspae2'];
$domingo = $_POST['diaspae3'];

if ($lunes_a_viernes != "") 
{
$lunes_a_viernes = "S";
}
if ($sabado != "") 
{
$sabado = "S";
}
if ($domingo != "") 
{
$domingo = "S";
}

$final_pae = "=".$_POST['aplicapae']."=".$guardar_admin_pae_am."$".$guardar_admin_pae_al."$".$guardar_admin_pae_pm."=".$hora_inicio."$".$hora_fin."=".$lunes_a_viernes."$".$sabado."$".$domingo;



$selected_radio = $_POST['colores'];
$selected_radiob = $_POST['coloresb'];
$selected_radioc = $_POST['coloresc'];
$selected_radiod = $_POST['coloresd'];
$selected_radioe = $_POST['colorese'];
$selected_radiof = $_POST['coloresf'];
$selected_radiog = $_POST['coloresg'];
$selected_radioh = $_POST['coloresh'];
$selected_radioi = $_POST['coloresi'];
$selected_radioj = $_POST['coloresj'];
$selected_radiok = $_POST['coloresk'];
$selected_radiol = $_POST['coloresl'];
$selected_radiox = $_POST['coloresx'];
$selected_radioy = $_POST['coloresy'];
$selected_radiovv = $_POST['coloresvv'];
$selected_radioww = $_POST['coloresww'];
$selected_radioppo = $_POST['coloresppo'];

//PAE
//APLICA SI O NO
$paeparamentriz1 = $_POST['aplicapae'];
//am
$ant_am=$_POST['gruposVar02_static'];
$paeparamentriz2=$_POST['gruposVar01'].$_POST['gruposVar02'];
//admu
$ant_ad=$_POST['gruposVar04_static'];
$paeparamentriz3=$_POST['gruposVar03'].$_POST['gruposVar04'];
//pm
$ant_pm=$_POST['gruposVar06_static'];
$paeparamentriz4=$_POST['gruposVar05'].$_POST['gruposVar06'];
//h1
$paeparamentriz5=$_POST['hora1'];
//h2
$paeparamentriz6=$_POST['hora2'];

//dias
$paeparamentriz7=$_POST['diaspae1'];
$paeparamentriz8=$_POST['diaspae2'];
$paeparamentriz9=$_POST['diaspae3'];
if ($paeparamentriz7=="LV") {
$paeparamentriz7="S";
}
else {
$paeparamentriz7="N";
}

if ($paeparamentriz8=="S") {
$paeparamentriz8="S";
}
else {
$paeparamentriz8="N";
}
if ($paeparamentriz9=="D") {
$paeparamentriz9="S";
}
else {
$paeparamentriz9="N";
}
if ($ant_am!="") {
$ant_am=$ant_am.",";	
}
if ($ant_ad!="") {
$ant_ad=$ant_ad.",";	
}
if ($ant_pm!="") {
$ant_pm=$ant_pm.",";	
}
//RECONOCIMIENTO DE VOZ
$rv1=$_POST["rv1"];
$rv2=$_POST["rv2"];
$rv3=$_POST["rv3"];
$rv4=$_POST["rv4"];
$rv5=$_POST["rv5"];
$rv6=$_POST["rv6"];
//INTERACCION
$t_interaccion = $_POST['tirv']; // tv  v
$t_estructura = $_POST['testruc']; // t1 t2
$t_fines = $_POST['tfines']; // f1 f2
if ($t_interaccion == 'tv') {
$t_interaccionf="tv";
}
if ($t_interaccion == 'v') {
$t_interaccionf="v";
}
if ($t_estructura == 't1') {
$t_estructuraf="t1";
}
if ($t_estructura == 't2') {
$t_estructuraf="t2";
}
if ($t_fines == 'fs') {
$t_finesf="f1";
}
if ($t_fines == 'fn') {
$t_finesf="f2";
}
$rv_final= array($rv1,$rv2,$rv3,$rv4,$rv5,$rv6,$t_interaccionf,$t_estructuraf,$t_finesf);
$rv_final2 = implode("$", $rv_final);
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol SET conf_valor = '$rv_final2' WHERE conf_id = 243";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar eerwerzzzzl adic");

if ($selected_radioppo == 'rojoppo') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '#64BD63' WHERE id = 18;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radioppo == 'naranjappo') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '' WHERE id = 18;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radio == 'rojo') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '#64BD63' WHERE id = 1;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radio == 'naranja') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '' WHERE id = 1;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radiob == 'rojob') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '#64BD63' WHERE id = 2;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radiob == 'naranjab') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '' WHERE id = 2;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radioc == 'rojoc') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '#64BD63' WHERE id = 3;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radioc == 'naranjac') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '' WHERE id = 3;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radiod == 'rojod') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '#64BD63' WHERE id = 4;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radiod == 'naranjad') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '' WHERE id = 4;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radioe == 'rojoe') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '#64BD63' WHERE id = 5;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radioe == 'naranjae') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '' WHERE id = 5;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radiof == 'rojof') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '#64BD63' WHERE id = 6;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radiof == 'naranjaf') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '' WHERE id = 6;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radiog == 'rojog') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '#64BD63' WHERE id = 7;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radiog == 'naranjag') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '' WHERE id = 7;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radioh == 'rojoh') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '#64BD63' WHERE id = 8;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radioh == 'naranjah') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '' WHERE id = 8;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radioi == 'rojoi') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '#64BD63' WHERE id = 9;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radioi == 'naranjai') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '' WHERE id = 9;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radioj == 'rojoj') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '#64BD63' WHERE id = 10;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radioj == 'naranjaj') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '' WHERE id = 10;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radiok == 'rojok') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '#64BD63' WHERE id = 11;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radiok == 'naranjak') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '' WHERE id = 11;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radiol == 'rojol') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '#64BD63' WHERE id = 12;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radiol == 'naranjal') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '' WHERE id = 12;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radiox == 'rojox') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '#64BD63' WHERE id = 13;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radiox == 'naranjax') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '' WHERE id = 13;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radioy == 'rojoy') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '#64BD63' WHERE id = 14;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radioy == 'naranjay') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '' WHERE id = 14;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radiovv == 'rojovv') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '#64BD63' WHERE id = 15;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radiovv == 'naranjavv') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '' WHERE id = 15;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radioww == 'rojoww') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '#64BD63' WHERE id = 16;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
if ($selected_radioww == 'naranjaww') {
global $database_sygescol, $sygescol;
$update_adic="UPDATE conf_sygescol_adic SET valor = '' WHERE id = 16;";
$sql_update=mysql_query($update_adic, $sygescol)or die("No se pudo Modificar el adic");
}
	mysql_select_db($database_sygescol, $sygescol);
	$query_configuracion = "SELECT conf_sygescol.conf_id, conf_sygescol.conf_nombre, conf_sygescol.conf_valor, conf_sygescol.conf_descri, conf_sygescol.conf_nom_ver, encabezado_parametros.titulo
								FROM conf_sygescol  INNER JOIN encabezado_parametros on encabezado_parametros.id_param=conf_sygescol.conf_id
							WHERE conf_sygescol.conf_estado = 0
								AND conf_sygescol.conf_id IN (14, 16, 17, 18,170, 19, 20, 50,152,65,93,95,96,100,109,68,73,99,123,124,115,161,162,14,67,76,87,111,127,132,141,149,163,56,71,88,89,92,94,97,110,102,169,117,154,157,70,122,160,108,112,113,116,121,129,120,135,150,155,75,90,91,101,103,107,118,119,130,131,133,134,138,139,104,105,114,140,158,128,153,159,136,151,156,164,142,143,144,145,146,147,166,168,165,66,137,167,223,240,241,251,255,256,257,236,235)  ORDER BY encabezado_parametros.id_orden ";
	$configuracion = mysql_query($query_configuracion, $sygescol) or die(mysql_error());
	$row_configuracion = mysql_fetch_assoc($configuracion);
	$totalRows_configuracion = mysql_num_rows($configuracion);
	$sel_actas="SELECT * FROM actas_impresion WHERE tipo_dato='PARAMETROS_GENERALES'";
	$sql_actas=mysql_query($sel_actas, $sygescol)or die("No se Pudo Consultar las Actas.");
	$num_actas=mysql_num_rows($sql_actas);
	do
	{
		if($row_configuracion['conf_id'] == 76){
			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 89){ // MODULO HOMOLOGACION
			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']]."$".$_POST[$row_configuracion['conf_nombre']."_estado"]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 90){ // FAMILIAS EN ACCION
			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']]."$".$_POST[$row_configuracion['conf_nombre']."_nivel"]."$".$_POST[$row_configuracion['conf_nombre']."_estado"]."$".$_POST[$row_configuracion['conf_nombre']."_demo"]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 91){  // DOCUMENTOS LEGALES
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']].",".$_POST['cons_estu'].",".$_POST['cons_matri'].",".$_POST['cons_fam'].",".$_POST['cons_cajas'].",".$_POST['cons_paz'].",".$_POST['cons_info'].",".$_POST['cons_obs'].",".$_POST['cons_ret'].",".$_POST['cons_retno'].",".$_POST['car_papel'].",".$_POST['gen_acu'].",".$_POST['gen_ins'].",".$_POST['gen_pac'].",".$_POST['cer_actual'].",".$_POST['gen_estu'].",".$_POST["primeroA_".$row_configuracion['conf_nombre']].",".$_POST["segundoA_".$row_configuracion['conf_nombre']].",".$_POST["terceroA_".$row_configuracion['conf_nombre']].",".$_POST["cuartoA_".$row_configuracion['conf_nombre']].",".$_POST["quintoA_".$row_configuracion['conf_nombre']].",".$_POST["sextoA_".$row_configuracion['conf_nombre']].",".$_POST["septimoA_".$row_configuracion['conf_nombre']].",".$_POST["octavoA_".$row_configuracion['conf_nombre']].",".$_POST["novenoA_".$row_configuracion['conf_nombre']].",".$_POST["decimoA_".$row_configuracion['conf_nombre']].",".$_POST["onceA_".$row_configuracion['conf_nombre']].",".$_POST["doceA_".$row_configuracion['conf_nombre']].",".$_POST["treceA_".$row_configuracion['conf_nombre']].",".$_POST["catorceA_".$row_configuracion['conf_nombre']].",".$_POST["quinceA_".$row_configuracion['conf_nombre']].",".$_POST['cer_anterior'].",".$_POST["onceE_".$row_configuracion['conf_nombre']].",".$_POST["unavez_".$row_configuracion['conf_nombre']].",".$_POST["confoto_".$row_configuracion['conf_nombre']]."' WHERE conf_id=91";

				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
				$selDocumentoAutorizado = "SELECT * FROM documentos_legales WHERE parametro=1";
				$sqlDocumentoAutorizado = mysql_query($selDocumentoAutorizado, $link)or die(mysql_error());
				while($rowDocumentoAutorizado = mysql_fetch_array($sqlDocumentoAutorizado)){
					$sql_upd_configuracion = "UPDATE documentos_legales SET autoriza = '".$_POST["autoriza_".$row_configuracion['conf_nombre'].$rowDocumentoAutorizado['docu_legal_id']]."',firma = '".$_POST["firma_".$row_configuracion['conf_nombre'].$rowDocumentoAutorizado['docu_legal_id']]."'  WHERE docu_legal_id LIKE '".$rowDocumentoAutorizado['docu_legal_id']."'";
					$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
				}
		}elseif($row_configuracion['conf_id'] == 120){ // PROMOCION ANTICIPADA
      $sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST["criterio120_".$row_configuracion['conf_nombre']]."$".$_POST['nota_minima_120']."$".$_POST['aplica_nota_comportamiento']."$".$_POST['nota_comportamiento']."$".$_POST['aplica_asistencia']."$".$_POST['porcentaje_asistencia']."$".$_POST['no_negativos']."$".$_POST['num_periodo']."$".$_POST['estado_120']."$".$_POST['periodo_fecha_inicio_120']."$".$_POST['periodo_fecha_final_120']."$".$_POST['prueba_I']."$".$_POST['obtener_V']."$".$_POST['calificacion_P']."$".$_POST['certificados_E']."$".$_POST["valorDesempate2_".$row_configuracion['conf_nombre']]."$".$_POST["valorDesempate3_".$row_configuracion['conf_nombre']]."$".$_POST['E1_']."$".$_POST['E2_']."$".$_POST['E3_']."$".$_POST['E4_']."$".$_POST['E5_']."$".$_POST['E6_']."$".$_POST['E7_']."$".$_POST['E8_']."$".$_POST['E9_']."$".$_POST['E10_']."$".$_POST['E11_']."$".$_POST['E12_']."$".$_POST['aplica_nota_comportamiento2']."$".$_POST['aplica_nota_comportamiento3']."$".$_POST["calificacion_A_".$row_configuracion['conf_nombre']]."$".$_POST['primero_S']."$".$_POST['Segundo_T']."$".$_POST['Tercero_C']."$".$_POST['Cuarto_Q']."$".$_POST['Quinto_S']."$".$_POST['Sexto_S']."$".$_POST['Semptimo_O']."$".$_POST['Octavo_N']."$".$_POST['Noveno_D']."$".$_POST['Decimo_O']."$".$_POST['valorNota_']."$".$_POST['aplica_promoanti_120']."$".$_POST['transicion_P']."$".$_POST['periodo_fecha_inicio_120planilla']."$".$_POST['periodo_fecha_final_120planilla']."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";  
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
			
		}elseif($row_configuracion['conf_id'] == 121){ // PROMOCION ANTICIPADA REPROBADOS 121
			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST['aplica_promoanti_121']."$".$_POST['nota_minima_121']."$".$_POST['aplica_nota_comportamiento_121']."$".$_POST['nota_comportamiento_121']."$".$_POST['aplica_asistencia_121']."$".$_POST['porcentaje_asistencia_121']."$".$_POST['no_negativos_121']."$".$_POST['aplica_periodo_121']."$".$_POST['periodo_fecha_inicio_1211']."$".$_POST['periodo_fecha_final_1211']."$".$_POST['estado_121']."$".$_POST['aDirectiva']."$".$_POST['paraQueAreas']."$".$_POST['areasReprobadas']."$".$_POST['todasAreas']."$".$_POST['demasAreas']."$".$_POST['areas_R']."$".$_POST['todas_A']."$".$_POST['demas_A']."$".$_POST['aDirectiva2']."$".$_POST['aplica_promoanti_1211']."$".$_POST['no_negativos_1211']."$".$_POST['aplica_asistencia_1211']."$".$_POST['periodo_fecha_inicio_121']."$".$_POST['periodo_fecha_final_121']."$".$_POST['periodo_fecha_inicio_1211_2']."$".$_POST['periodo_fecha_final_1211_2']."$".$_POST['periodo_fecha_inicio_1211_2_1']."$".$_POST['periodo_fecha_final_1211_2_1']."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 124){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']]."$".$_POST["0_".$row_configuracion['conf_nombre']]."$".$_POST["1_".$row_configuracion['conf_nombre']]."$".$_POST["2_".$row_configuracion['conf_nombre']]."$".$_POST["3_".$row_configuracion['conf_nombre']]."$".$_POST["4_".$row_configuracion['conf_nombre']]."$".$_POST["5_".$row_configuracion['conf_nombre']]."$".$_POST["6_".$row_configuracion['conf_nombre']]."$".$_POST["7_".$row_configuracion['conf_nombre']]."$".$_POST["8_".$row_configuracion['conf_nombre']]."$".$_POST["9_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 223){ // PROMOCION ANTICIPADA REPROBADOS 121
			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST["criterio_".$row_configuracion['conf_nombre']]."$".$_POST['areas_R_1']."$".$_POST['areas_R_2']."$".$_POST['hora_cita_1']."$".$_POST['hora_cita_2']."$".$_POST['hora_citaa_1']."$".$_POST['hora_citaa_2']."$".$_POST['areas_B_1']."$".$_POST['areas_B_2']."$".$_POST['hora_citaaa_1']."$".$_POST['hora_citaaa_2']."$".$_POST['hora_citaaaa_1']."$".$_POST['hora_citaaaa_2']."$".$_POST['areas_B_3']."$".$_POST['hora_citaaa_2']."$".$_POST['hora_citaaa_3']."$".$_POST['hora_citaaaa_2']."$".$_POST['hora_citaaaa_3']."' WHERE conf_id = 223";    
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 122){ // Rotacion del horario 122 valorasndnc_
		// $sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST['valorasndnc_1']."$".$_POST['valorasndnc_2']."$".$_POST['valorasndnc_3']."$".$_POST['valorasndnc_4']."$".$_POST['valorasndnc_5']."$".$_POST['valorasndnc_6']."$".$_POST['valorasndnc_7']."$".$_POST['valorasndnc_8']."$".$_POST['valorasndnc_9']."$".$_POST['valorasndnc_10']."$".$_POST['valorasndnc_11']."$".$_POST['valorasndnc_12']."$".$_POST['valorasndnc_13']."$".$_POST['valorasndnc_14']."$".$_POST['valorasndnc_15']."$".$_POST['valorasndnc_16']."$".$_POST['valorasndnc_17']."$".$_POST['valorasndnc_18']."$".$_POST['valorasndnc_19']."$".$_POST['valorasndnc_20']."$".$_POST['plsvr_11']."$".$_POST['plsvr_12']."$".$_POST['plsvr_13']."$".$_POST['plsvr_14']."$".$_POST['plsvr_15']."$".$_POST['plsvr_16']."$".$_POST['plsvr_17']."$".$_POST['plsvr_18']."$".$_POST['plsvr_19']."$".$_POST['plsvr_110'].$_POST['plsvr_111']."$".$_POST['plsvr_112'].$_POST['plsvr_113']."$".$_POST['plsvr_114'].$_POST['plsvr_115']."$".$_POST['plsvr_116']."$".$_POST['plsvr_117']."$".$_POST['plsvr_118']."$".$_POST['plsvr_119']."$".$_POST['plsvr_120']."$".$_POST['shrscedysa_11']."$".$_POST['shrscedysa_12']."$".$_POST['shrscedysa_13']."$".$_POST['shrscedysa_14']."$".$_POST['shrscedysa_15']."$".$_POST['shrscedysa_16'].$_POST['shrscedysa_17']."$".$_POST['shrscedysa_18'].$_POST['shrscedysa_19']."$".$_POST['shrscedysa_110'].$_POST['shrscedysa_111']."$".$_POST['shrscedysa_112'].$_POST['shrscedysa_113']."$".$_POST['shrscedysa_114']."$".$_POST['shrscedysa_115']."$".$_POST['shrscedysa_116']."$".$_POST['shrscedysa_117']."$".$_POST['shrscedysa_118']."$".$_POST['shrscedysa_119']."$".$_POST['shrscedysa_120']."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
	$sql_upd_configuracion="update conf_sygescol set conf_valor='"
.$_POST['valorasndnc_1']."$".$_POST['plsvr_11']."$".$_POST['plsvr_21']."$".$_POST['plsvr_31']."$".$_POST['plsvr_41']."$"
.$_POST['plsvr_51']."$".$_POST['shrscedysa_11']."$".$_POST['shrscedysa_21']."$".$_POST['shrscedysa_31']."$"
.$_POST['shrscedysa_41']."$".$_POST['shrscedysa_51']."$"

.$_POST['valorasndnc_2']."$".$_POST['plsvr_12']."$".$_POST['plsvr_22']."$".$_POST['plsvr_32']."$"
.$_POST['plsvr_42']."$".$_POST['plsvr_52']."$".$_POST['shrscedysa_12']."$".$_POST['shrscedysa_22']."$"
.$_POST['shrscedysa_32']."$".$_POST['shrscedysa_42']."$".$_POST['shrscedysa_52']."$"

.$_POST['valorasndnc_3']."$".$_POST['plsvr_13']."$".$_POST['plsvr_23']."$".$_POST['plsvr_33']."$"
.$_POST['plsvr_43']."$".$_POST['plsvr_53']."$".$_POST['shrscedysa_13']."$".$_POST['shrscedysa_23']."$"
.$_POST['shrscedysa_33']."$".$_POST['shrscedysa_43']."$".$_POST['shrscedysa_53']."$"

.$_POST['valorasndnc_4']."$".$_POST['plsvr_14']."$".$_POST['plsvr_24']."$".$_POST['plsvr_34']."$"
.$_POST['plsvr_44']."$".$_POST['plsvr_54']."$".$_POST['shrscedysa_14']."$".$_POST['shrscedysa_24']."$"
.$_POST['shrscedysa_34']."$".$_POST['shrscedysa_44']."$".$_POST['shrscedysa_54']."$"

.$_POST['valorasndnc_5']."$".$_POST['plsvr_15']."$".$_POST['plsvr_25']."$".$_POST['plsvr_35']."$"
.$_POST['plsvr_45']."$".$_POST['plsvr_55']."$".$_POST['shrscedysa_15']."$".$_POST['shrscedysa_25']."$".$_POST['shrscedysa_35']."$"
.$_POST['shrscedysa_45']."$".$_POST['shrscedysa_55']."' where conf_id=122";

			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] ==94){
			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']].",".$_POST['pla0'].",".$_POST['pla1'].",".$_POST['pla2'].",".$_POST['pla3'].",".$_POST['pla4'].",".$_POST['pla5'].",".$_POST['pla6'].",".$_POST['pla7'].",".$_POST['pla8']."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 101){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST["certificado_".$row_configuracion['conf_nombre']].",".$_POST["valCertificado_".$row_configuracion['conf_nombre']]."@".$_POST["constancia_".$row_configuracion['conf_nombre']].",".$_POST["valConstancia_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 105){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST["select_foto_carne_acu_".$row_configuracion['conf_nombre']]."$".$_POST["a_".$row_configuracion['conf_nombre']]."$".$_POST["b_".$row_configuracion['conf_nombre']]."$".$_POST["c_".$row_configuracion['conf_nombre']]."$".$_POST["d_".$row_configuracion['conf_nombre']]."$".$_POST["e_".$row_configuracion['conf_nombre']]."$".$_POST["f_".$row_configuracion['conf_nombre']]."$".$_POST["g_".$row_configuracion['conf_nombre']]."$".$_POST["h_".$row_configuracion['conf_nombre']]."$".$_POST["i_".$row_configuracion['conf_nombre']]."$".$_POST["j_".$row_configuracion['conf_nombre']]."$".$_POST["k_".$row_configuracion['conf_nombre']]."$".$_POST["l_".$row_configuracion['conf_nombre']]."$".$_POST["m_".$row_configuracion['conf_nombre']]."$".$_POST["n_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 108){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor =
				'".$_POST["1_".$row_configuracion['conf_nombre']]."$".$_POST["2_".$row_configuracion['conf_nombre']]."$".$_POST["3_".$row_configuracion['conf_nombre']]."$".$_POST["4_".$row_configuracion['conf_nombre']]."$".$_POST["5_".$row_configuracion['conf_nombre']]."$".$_POST["6_".$row_configuracion['conf_nombre']]."$".$_POST["7_".$row_configuracion['conf_nombre']]."$".$_POST["8_".$row_configuracion['conf_nombre']]."$".$_POST["9_".$row_configuracion['conf_nombre']]."$".$_POST["10_".$row_configuracion['conf_nombre']]."$".$_POST["11_".$row_configuracion['conf_nombre']]."$".$_POST["12_".$row_configuracion['conf_nombre']]."$".$_POST["13_".$row_configuracion['conf_nombre']]."$".$_POST["14_".$row_configuracion['conf_nombre']]."$' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 117){
			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']]."$".$_POST["cS_".$row_configuracion['conf_nombre']]."$".$_POST["cA_".$row_configuracion['conf_nombre']]."$".$_POST["cBs_".$row_configuracion['conf_nombre']]."$".$_POST["cBj_".$row_configuracion['conf_nombre']]."$".$_POST["cSs_".$row_configuracion['conf_nombre']]."$".$_POST["cAa_".$row_configuracion['conf_nombre']]."$".$_POST["cBsb_".$row_configuracion['conf_nombre']]."$".$_POST["cBjj_".$row_configuracion['conf_nombre']]."$".$_POST["pgul1"]."$".$_POST["pgul2"]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
			
			
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
			if($_POST[$row_configuracion['conf_nombre']]=='S'){
				$query_upd_conf = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']]."' WHERE conf_nombre = 'sis_cal_ref'";
				$upd_conf = mysql_query($query_upd_conf, $sygescol) or die(mysql_error());
			}
		}
		elseif ($row_configuracion['conf_id'] == 257) {
			$periodos_final = $_POST['mod_camb_per_1'].",".$_POST['mod_camb_per_2'].",".$_POST['mod_camb_per_3'].",".$_POST['mod_camb_per_4'];
			$valores_final = $_POST['param_cambio_nota']."$".$_POST['mod_cam_area']."$".$_POST['mod_cam_asig']."$".$periodos_final;
			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '$valores_final' WHERE conf_id = 257";
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");

		}
		elseif($row_configuracion['conf_id'] == 236){
			// echo "<script>alert('REASIGNACION NORMAL= ".$_POST["reasignacion_".$row_configuracion['conf_nombre'].""]."  Y REASIGNACION2= ".$_POST["reasignacion2_".$row_configuracion['conf_nombre'].""]."')</script>";
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '$".$_POST["reasignacion_".$row_configuracion['conf_nombre'].""]."$".$_POST["reasignacion2_".$row_configuracion['conf_nombre'].""]."' WHERE conf_id=236";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}
////////////////////////////////////////////////////////////////////////////////////////////////camilo////////////////////////////////////////////////////////////////////////////////////////////////
		elseif($row_configuracion['conf_id'] == 235){
			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']]."$".$_POST["cS_".$row_configuracion['conf_nombre']]."$".$_POST["cA_".$row_configuracion['conf_nombre']]."$".$_POST["cBs_".$row_configuracion['conf_nombre']]."$".$_POST["cBj_".$row_configuracion['conf_nombre']]."$".$_POST["cBjj_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
			if($_POST[$row_configuracion['conf_nombre']]=='S'){
				$query_upd_conf = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']]."' WHERE conf_nombre = 'sis_cal_ref'";
				$upd_conf = mysql_query($query_upd_conf, $sygescol) or die(mysql_error());
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////camilo////////////////////////////////////////////////////////////////////////////////////////////////
		elseif($row_configuracion['conf_id'] == 98){
				if($_POST["valor_".$row_configuracion['conf_nombre']] == "N"){
					$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor =
					'$".$_POST["valor_".$row_configuracion['conf_nombre']]."$".$_POST["PRE_".$row_configuracion['conf_nombre']]."$".$_POST["BP_".$row_configuracion['conf_nombre']]."$".$_POST["BS_".$row_configuracion['conf_nombre']]."$".$_POST["ME_".$row_configuracion['conf_nombre']]."$".$_POST["FC_".$row_configuracion['conf_nombre']]."$".$_POST["CI_".$row_configuracion['conf_nombre']]."$".$_POST["a_".$row_configuracion['conf_nombre']]."$".$_POST["b_".$row_configuracion['conf_nombre']]."$".$_POST["c_".$row_configuracion['conf_nombre']]."$".$_POST["d_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
					$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
					$sql_upd_configuracion1 = "UPDATE conf_sygescol SET c =
					'".$_POST["valor_".$row_configuracion['conf_nombre']]."'";
$upd_configuracion1 = mysql_query($sql_upd_configuracion1, $sygescol) or die("No se pudo actualizar los parametros del sistema");
				}else if($_POST["valor_".$row_configuracion['conf_nombre']] == "A"){
					$selGrados = "SELECT * FROM gao GROUP BY ba ORDER BY a";
					$sqlGrados = mysql_query($selGrados, $link);
					while ($rowGrados = mysql_fetch_array($sqlGrados)) {
						$valorInsertar .= "$".$_POST["grado98_".$rowGrados['a']]."$";
					}
					$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor =
					'$".$_POST["valor_".$row_configuracion['conf_nombre']]."$".$_POST["a_".$row_configuracion['conf_nombre']]."$".$_POST["b_".$row_configuracion['conf_nombre']]."$".$_POST["c_".$row_configuracion['conf_nombre']]."$".$_POST["d_".$row_configuracion['conf_nombre']]."$".$_POST["e_".$row_configuracion['conf_nombre']].$valorInsertar."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
					$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
	$sql_upd_configuracion1 = "UPDATE conf_sygescol SET c =
					'".$_POST["valor_".$row_configuracion['conf_nombre']]."'";
$upd_configuracion1 = mysql_query($sql_upd_configuracion1, $sygescol) or die("No se pudo actualizar los parametros del sistema");
				}
				else if($_POST["valor_".$row_configuracion['conf_nombre']] == "B"){
					$selGrados = "SELECT * FROM gao GROUP BY ba ORDER BY a";
					$sqlGrados = mysql_query($selGrados, $link);
					while ($rowGrados = mysql_fetch_array($sqlGrados)) {
						$valorInsertar .= "$".$_POST["grado98_".$rowGrados['a']]."$";
					}
					$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor =
					'$".$_POST["valor_".$row_configuracion['conf_nombre']]."$".$_POST["a_".$row_configuracion['conf_nombre']]."$".$_POST["b_".$row_configuracion['conf_nombre']]."$".$_POST["c_".$row_configuracion['conf_nombre']]."$".$_POST["d_".$row_configuracion['conf_nombre']]."$".$_POST["e_".$row_configuracion['conf_nombre']].$valorInsertar."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
					$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
	$sql_upd_configuracion1 = "UPDATE conf_sygescol SET c ='".$_POST["valor_".$row_configuracion['conf_nombre']]."'";
$upd_configuracion1 = mysql_query($sql_upd_configuracion1, $sygescol) or die("No se pudo actualizar los parametros del sistema");
				}
else if($_POST["valor_".$row_configuracion['conf_nombre']] == "G"){
					$selGrados = "SELECT * FROM gao GROUP BY ba ORDER BY a";
					$sqlGrados = mysql_query($selGrados, $link);
					while ($rowGrados = mysql_fetch_array($sqlGrados)) {
						$valorInsertar .= "$".$_POST["grado98_".$rowGrados['a']]."$";
					}
					$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor =
					'$".$_POST["valor_".$row_configuracion['conf_nombre']]."$".$_POST["a_".$row_configuracion['conf_nombre']]."$".$_POST["b_".$row_configuracion['conf_nombre']]."$".$_POST["c_".$row_configuracion['conf_nombre']]."$".$_POST["d_".$row_configuracion['conf_nombre']]."$".$_POST["e_".$row_configuracion['conf_nombre']].$valorInsertar."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
					$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
	$sql_upd_configuracion1 = "UPDATE conf_sygescol SET c ='".$_POST["valor_".$row_configuracion['conf_nombre']]."'";
$upd_configuracion1 = mysql_query($sql_upd_configuracion1, $sygescol) or die("No se pudo actualizar los parametros del sistema");
				}
		}elseif($row_configuracion['conf_id'] == 132){
				if($_POST["valor_".$row_configuracion['conf_nombre']] == "N"){
					$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor =
					'$".$_POST["valor_".$row_configuracion['conf_nombre']]."$".$_POST["1_".$row_configuracion['conf_nombre']]."$".$_POST["2_".$row_configuracion['conf_nombre']]."$".$_POST["3_".$row_configuracion['conf_nombre']]."$".$_POST["4_".$row_configuracion['conf_nombre']]."$".$_POST["5_".$row_configuracion['conf_nombre']]."$".$_POST["6_".$row_configuracion['conf_nombre']]."$".$_POST["7_".$row_configuracion['conf_nombre']]."$".$_POST["8_".$row_configuracion['conf_nombre']]."$".$_POST["FC_".$row_configuracion['conf_nombre']]."$".$_POST["a_".$row_configuracion['conf_nombre']]."$".$_POST["b_".$row_configuracion['conf_nombre']]."$".$_POST["c_".$row_configuracion['conf_nombre']]."$".$_POST["d_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
					$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
				}else{
					$selGrados = "SELECT * FROM gao GROUP BY ba ORDER BY a";
					$sqlGrados = mysql_query($selGrados, $link);
					while ($rowGrados = mysql_fetch_array($sqlGrados)) {
						$valorInsertar .= "$".$_POST["grado132_".$rowGrados['a']]."$";
					}
					$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor =
					'$".$_POST["valor_".$row_configuracion['conf_nombre']].$valorInsertar."$".$_POST["a_".$row_configuracion['conf_nombre']]."$".$_POST["b_".$row_configuracion['conf_nombre']]."$".$_POST["c_".$row_configuracion['conf_nombre']]."$".$_POST["d_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
					$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
				}
		}elseif($row_configuracion['conf_id'] == 133){
					$link = conectarse();
				mysql_select_db($database_sygescol,$link);
				$sel_consulta_areas = "SELECT * FROM aes ";
				$sql_consulta_areas = mysql_query($sel_consulta_areas, $link);
					$sel_consulta_areas2 = "SELECT * FROM aes ";
				$sql_consulta_areas2 = mysql_query($sel_consulta_areas2, $link);
				$sel_consulta_areas3 = "SELECT * FROM aes ";
				$sql_consulta_areas3 = mysql_query($sel_consulta_areas3, $link);
				$sel_consulta_areas4 = "SELECT * FROM aes ";
				$sql_consulta_areas4 = mysql_query($sel_consulta_areas4, $link);
				$sel_consulta_areas5 = "SELECT * FROM aes ";
				$sql_consulta_areas5 = mysql_query($sel_consulta_areas5, $link);
						//Recorremos consulta Basica primaria
						while ($row_consulta_areas = mysql_fetch_assoc($sql_consulta_areas)) {
							$valorInsertar_area .= $_POST["area_".$row_consulta_areas['i']]."_".$row_consulta_areas['i']."$";
							//echo $valorInsertar_area.'<br>';
						}
									//Recorremos consulta Basica Secundaria
						while ($row_consulta_areas2 = mysql_fetch_assoc($sql_consulta_areas2)) {
							$valorInsertar_area2 .= $_POST["area2_".$row_consulta_areas2['i']]."_".$row_consulta_areas2['i']."$";
						/*	echo $valorInsertar_area2.'<br>';
							echo "string";*/
						}
						while ($row_consulta_areas3 = mysql_fetch_assoc($sql_consulta_areas3)) {
							$valorInsertar_area3 .= $_POST["area3_".$row_consulta_areas3['i']]."_".$row_consulta_areas3['i']."$";
						//	echo $valorInsertar_area3.'<br>';
						}
									//Recorremos consulta Media Once
						while ($row_consulta_areas4 = mysql_fetch_assoc($sql_consulta_areas4)) {
							$valorInsertar_area4 .= $_POST["area4_".$row_consulta_areas4['i']]."_".$row_consulta_areas4['i']."$";
							//echo $valorInsertar_area4.'<br>';
						}
									//Recorremos consulta Ciclos
						while ($row_consulta_areas5 = mysql_fetch_assoc($sql_consulta_areas5)) {
							$valorInsertar_area5 .= $_POST["area5_".$row_consulta_areas5['i']]."_".$row_consulta_areas5['i']."$";
						//	echo $valorInsertar_area5.'<br>';
						}
			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST["valorDesempate_".$row_configuracion['conf_nombre']]."/".$valorInsertar_area."/".$valorInsertar_area2."/".$valorInsertar_area3."/".$valorInsertar_area4."/".$valorInsertar_area5."@".$_POST["constancia_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
			//echo $sql_upd_configuracion;
$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
			$sql_upd_configuracion1 = "UPDATE conf_sygescol SET c = '".$_POST['parametro67133']."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
$upd_configuracion1 = mysql_query($sql_upd_configuracion1, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 129){ //DM29
			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST["valor"]."$".$_POST["valorAsignaturas2_".$row_configuracion['conf_nombre']]."$".$_POST["valorAsignaturas_".$row_configuracion['conf_nombre']]."$".$_POST["valorEspecifico"]."$".$_POST["valorEspecifico2"]."$".$_POST["valorEspecifico3"]."$".$_POST["input_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
			//$notas = $_POST['notaDm'];
			/*if (strlen($notas) == 1) {
				$updDm = "UPDATE confDm29 SET notaMin = '1', notaMax = '$_POST[notaVDm]', estado = '1', areas='$_POST[valAreasPerdidas]' WHERE id='$_POST[dm_29]'";
				$updateDm = mysql_query($updDm, $link)or die(mysql_error());
				$updDm2 = "UPDATE confDm29 SET estado = '0', areas='$_POST[valAreasPerdidas]' WHERE id!='$_POST[dm_29]'";
				$updateDm2 = mysql_query($updDm2, $link)or die(mysql_error());
			}else{
				$valor = explode(',',$notas);
				$updDm = "UPDATE confDm29 SET notaMin = '$valor[0]', notaMax = '$valor[1]', estado = '1', areas='$_POST[valAreasPerdidas]' WHERE id='$_POST[dm_29]'";
				$updateDm = mysql_query($updDm, $link)or die(mysql_error());
				$updDm2 = "UPDATE confDm29 SET estado = '0', areas='$_POST[valAreasPerdidas]' WHERE id!='$_POST[dm_29]'";
				$updateDm2 = mysql_query($updDm2, $link)or die(mysql_error());
			}*/

			if ($_POST["valorAsignaturas2_".$row_configuracion['conf_nombre']] == "a") {
				$valor_maximo = $notaSupMax;
				$valor_minimo = $notaSupMin;
			}
			if ($_POST["valorAsignaturas2_".$row_configuracion['conf_nombre']] == "b") {
				$valor_maximo = $notaSupMax;
				$valor_minimo = $notaBajoMin;
			}
			if ($_POST["valorAsignaturas2_".$row_configuracion['conf_nombre']] == "c") {
				$valor_maximo = $notaAltoMax;
				$valor_minimo = $notaAltoMin;
			}
			if ($_POST["valorAsignaturas2_".$row_configuracion['conf_nombre']] == "d") {
				$valor_maximo = $notaAltoMax;
				$valor_minimo = $notaBajoMin;
			}
			if ($_POST["valorAsignaturas2_".$row_configuracion['conf_nombre']] == "e") {
				$valor_maximo = $notaBasicoMax;
				$valor_minimo = $notaBasicoMin;
			}
			if ($_POST["valorAsignaturas2_".$row_configuracion['conf_nombre']] == "f") {
				$valor_maximo = $notaBasicoMax;
				$valor_minimo = $notaBajoMin;
			}
			if ($_POST["valorAsignaturas2_".$row_configuracion['conf_nombre']] == "g") {
				$valor_maximo = $notaBajoMax;
				$valor_minimo = $notaBajoMin;
			}
			if ($_POST["valorAsignaturas2_".$row_configuracion['conf_nombre']] == "h") {
				$valor_maximo = $_POST["valorEspecifico2"];
				$valor_minimo = $_POST["valorEspecifico2"];
			}
			if ($_POST["valorAsignaturas_".$row_configuracion['conf_nombre']] == "i") {
				$valor_max_tec = $notaSupMax;
				$valor_min_tec = $notaSupMin;
			}
			if ($_POST["valorAsignaturas_".$row_configuracion['conf_nombre']] == "j") {
				$valor_max_tec = $notaSupMax;
				$valor_min_tec = $notaBajoMin;
			}
			if ($_POST["valorAsignaturas_".$row_configuracion['conf_nombre']] == "k") {
				$valor_max_tec = $notaAltoMax;
				$valor_min_tec = $notaAltoMin;
			}
			if ($_POST["valorAsignaturas_".$row_configuracion['conf_nombre']] == "l") {
				$valor_max_tec = $notaAltoMax;
				$valor_min_tec = $notaBajoMin;
			}
			if ($_POST["valorAsignaturas_".$row_configuracion['conf_nombre']] == "m") {
				$valor_max_tec = $notaBasicoMax;
				$valor_min_tec = $notaBasicoMin;
			}
			if ($_POST["valorAsignaturas_".$row_configuracion['conf_nombre']] == "n") {
				$valor_max_tec = $notaBasicoMax;
				$valor_min_tec = $notaBajoMin;
			}
			if ($_POST["valorAsignaturas_".$row_configuracion['conf_nombre']] == "o") {
				$valor_max_tec = $notaBajoMax;
				$valor_min_tec = $notaBajoMin;
			}
			if ($_POST["valorAsignaturas_".$row_configuracion['conf_nombre']] == "p") {
				$valor_max_tec = $_POST["valorEspecifico"];
				$valor_min_tec = $_POST["valorEspecifico"];
			}

			$areas = $_POST["valorEspecifico3"];

			if ($areas == "") 
			{
			$areas = 0;
			}

			$upd_dm_est = "UPDATE confDm29 SET estado = '0', notaMin = '0', notaMax = '0', areas = '0' WHERE id = 1";
			$upd_dm = mysql_query($upd_dm_est, $link)or die(mysql_error());
			$upd_dm_est = "UPDATE confDm29 SET estado = '0', notaMin = '0', notaMax = '0', areas = '0' WHERE id = 2";
			$upd_dm = mysql_query($upd_dm_est, $link)or die(mysql_error());
			$upd_dm_est = "UPDATE confDm29 SET estado = '0', notaMin = '0', notaMax = '0', areas = '0' WHERE id = 3";
			$upd_dm = mysql_query($upd_dm_est, $link)or die(mysql_error());
			$upd_dm_est = "UPDATE confDm29Tec SET estado = '0', notaMin = '0', notaMax = '0', areas = '0' WHERE id = 1";
			$upd_dm = mysql_query($upd_dm_est, $link)or die(mysql_error());
			$upd_dm_est = "UPDATE confDm29Tec SET estado = '0', notaMin = '0', notaMax = '0', areas = '0' WHERE id = 2";
			$upd_dm = mysql_query($upd_dm_est, $link)or die(mysql_error());
			$upd_dm_est = "UPDATE confDm29Tec SET estado = '0', notaMin = '0', notaMax = '0', areas = '0' WHERE id = 3";
			$upd_dm = mysql_query($upd_dm_est, $link)or die(mysql_error());
			if ($_POST["input_".$row_configuracion['conf_nombre']] == 17) {
			$updDm = "UPDATE confDm29 SET notaMin = '".$valor_minimo."', notaMax = '".$valor_maximo."', estado = '1', areas='".$areas."' WHERE id = 1";
			$updDmTec = "UPDATE confDm29Tec SET notaMin = '".$valor_min_tec."', notaMax = '".$valor_max_tec."', estado = '1', areas='".$areas."' WHERE id = 1";
			}
			if ($_POST["input_".$row_configuracion['conf_nombre']] == 18) {
			$updDm = "UPDATE confDm29 SET notaMin = '".$valor_minimo."', notaMax = '".$valor_maximo."', estado = '1', areas='".$areas."' WHERE id = 2";
			$updDmTec = "UPDATE confDm29Tec SET notaMin = '".$valor_min_tec."', notaMax = '".$valor_max_tec."', estado = '1', areas='".$areas."' WHERE id = 2";
			}
			if ($_POST["input_".$row_configuracion['conf_nombre']] == 19) {
			$updDm = "UPDATE confDm29 SET notaMin = '".$valor_minimo."', notaMax = '".$valor_maximo."', estado = '1', areas='".$areas."' WHERE id = 3";
			$updDmTec = "UPDATE confDm29Tec SET notaMin = '".$valor_min_tec."', notaMax = '".$valor_max_tec."', estado = '1', areas='".$areas."' WHERE id = 3";
			}
			if ($updDm != "") {
				$updateDm = mysql_query($updDm, $link)or die(mysql_error().$updDm);
			}
			if ($updDmTec != "") {
				$updateDmTec = mysql_query($updDmTec, $link)or die(mysql_error().$updDmTec);
			}
			
		}elseif($row_configuracion['conf_id'] == 113){ // EVALUACION INSTITUCION
			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']]."$".$_POST['periodo_fecha_inicio_113']."$".$_POST['periodo_fecha_final_113']."$' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 130 || $row_configuracion['conf_id'] == 131 || $row_configuracion['conf_id'] == 134 || $row_configuracion['conf_id'] == 138 ){ // EVALUACION INSTITUCION
			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 102){
			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']]."$".$_POST["cS_".$row_configuracion['conf_nombre']]."$".$_POST["cA_".$row_configuracion['conf_nombre']]."$".$_POST["cBs_".$row_configuracion['conf_nombre']]."$".$_POST["cBj_".$row_configuracion['conf_nombre']]."$".$_POST["cBjj_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
			if($_POST[$row_configuracion['conf_nombre']]=='S'){
				$query_upd_conf = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']]."' WHERE conf_nombre = 'sis_cal_ref'";
				$upd_conf = mysql_query($query_upd_conf, $sygescol) or die(mysql_error());
			}
					
////////////////////////////////////////////////////////////////////////////////////////////////camilo////////////////////////////////////////////////////////////////////////////////////////////////
		}elseif($row_configuracion['conf_id'] == 135){ // EVALUACION INSTITUCION
			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '$".$_POST["a_".$row_configuracion['conf_nombre']]."$".$_POST["b_".$row_configuracion['conf_nombre']]."$".$_POST["c_".$row_configuracion['conf_nombre']]."$".$_POST["d_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 119){ // hoja de vida parametro 43
			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '$".$_POST["docentes"]."$".$_POST["docentes_D"]."$".$_POST["administrativos"]."$".$_POST["estudiantes"]."$".$_POST["controlBoletines".$row_configuracion['conf_nombre']]."$".$_POST[$row_configuracion['conf_nombre']."_fecha_1"]."$".$_POST[$row_configuracion['conf_nombre']."_fecha_2"]."$".$_POST[$row_configuracion['conf_nombre']."_fecha_3"]."$".$_POST[$row_configuracion['conf_nombre']."_fecha_4"]."$".$_POST[$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 75){ // hoja de vida parametro 43
			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '$".$_POST["docentes"]."$".$_POST["docentes_D"]."$".$_POST["administrativos"]."$".$_POST["estudiantes"]."$".$_POST["controlBoletines".$row_configuracion['conf_nombre']]."$".$_POST[$row_configuracion['conf_nombre']."_fecha_1"]."$".$_POST[$row_configuracion['conf_nombre']."_fecha_2"]."$".$_POST[$row_configuracion['conf_nombre']."_fecha_3"]."$".$_POST[$row_configuracion['conf_nombre']."_fecha_4"]."$".$_POST[$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 136){
			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST["a_".$row_configuracion['conf_nombre']]."$".$_POST["conc_".$row_configuracion['conf_nombre']]."$".$_POST["cond_".$row_configuracion['conf_nombre']]."$".$_POST["cone_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 137){
			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST["a_".$row_configuracion['conf_nombre']]."$".$_POST["b_".$row_configuracion['conf_nombre']]."$".$_POST["c_".$row_configuracion['conf_nombre']]."$".$_POST["d_".$row_configuracion['conf_nombre']]."$".$_POST["e_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 139){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor =
				'$".$_POST["1_".$row_configuracion['conf_nombre']]."$".$_POST["2_".$row_configuracion['conf_nombre']]."$".$_POST["3_".$row_configuracion['conf_nombre']]."$".$_POST["4_".$row_configuracion['conf_nombre']]."$".$_POST["5_".$row_configuracion['conf_nombre']]."$".$_POST["6_".$row_configuracion['conf_nombre']]."$".$_POST["7_".$row_configuracion['conf_nombre']]."$".$_POST["8_".$row_configuracion['conf_nombre']]."$".$_POST["11_".$row_configuracion['conf_nombre']]."$".$_POST["12_".$row_configuracion['conf_nombre']]."$".$_POST["13_".$row_configuracion['conf_nombre']]."$".$_POST["14_".$row_configuracion['conf_nombre']]."$".$_POST["15_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 141){
			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST["1_".$row_configuracion['conf_nombre']]."$".$_POST["2_".$row_configuracion['conf_nombre']]."$".$_POST["3_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 142 || $row_configuracion['conf_id'] == 143){

				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor =
				'$".$_POST["a_".$row_configuracion['conf_nombre']]."$".$_POST["b_".$row_configuracion['conf_nombre']]."$".$_POST["c_".$row_configuracion['conf_nombre']]."$".$_POST["d_".$row_configuracion['conf_nombre']]."$".$_POST["e_".$row_configuracion['conf_nombre']]."$".$_POST["f_".$row_configuracion['conf_nombre']]."$".$_POST["g_".$row_configuracion['conf_nombre']]."$".$_POST["h_".$row_configuracion['conf_nombre']]."$".$_POST["i_".$row_configuracion['conf_nombre']]."$".$_POST["j_".$row_configuracion['conf_nombre']]."$".$_POST["k_".$row_configuracion['conf_nombre']]."$".$_POST["l_".$row_configuracion['conf_nombre']]."$".$_POST["m_".$row_configuracion['conf_nombre']]."$".$_POST["n_".$row_configuracion['conf_nombre']]."$".$_POST["ñ_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 144){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor =
				'$".$_POST["a_".$row_configuracion['conf_nombre']]."$".$_POST["b_".$row_configuracion['conf_nombre']]."$".$_POST["c_".$row_configuracion['conf_nombre']]."$".$_POST["d_".$row_configuracion['conf_nombre']]."$".$_POST["e_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 145 || $row_configuracion['conf_id'] == 146 || $row_configuracion['conf_id'] == 147){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor =
				'$".$_POST["a_".$row_configuracion['conf_nombre']]."$".$_POST["b_".$row_configuracion['conf_nombre']]."$".$_POST["c_".$row_configuracion['conf_nombre']]."$".$_POST["d_".$row_configuracion['conf_nombre']]."$".$_POST["e_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 148){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor =
				'".$_POST["2_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 149){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = 
				'$".$_POST["valorRepruebaIna_".$row_configuracion['conf_nombre']]."$".$_POST["inasistencia_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 167){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']]."$".$_POST["valorplanirecu_".$row_configuracion['conf_nombre']]."$".$_POST["reasignacionRepro3_".$row_configuracion['conf_nombre']]."$".$_POST["cS_".$row_configuracion['conf_nombre']]."$".$_POST["cA_".$row_configuracion['conf_nombre']]."$".$_POST["cBs_".$row_configuracion['conf_nombre']]."$".$_POST["cBj_".$row_configuracion['conf_nombre']]."$".$_POST["cBjj_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 111){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 127){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 140){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '$".$_POST["bloqueo_foto_detallado_".$row_configuracion['conf_nombre']]."$".$_POST["bloqueo_huella_detallado_".$row_configuracion['conf_nombre']]."$".$_POST["bloqueo_firma_detallado_".$row_configuracion['conf_nombre']]."$".$_POST["bloqueo_carne_detallado_".$row_configuracion['conf_nombre']]."$".$_POST["bloqueo_foto_resumen_".$row_configuracion['conf_nombre']]."$".$_POST["bloqueo_huella_resumen_".$row_configuracion['conf_nombre']]."$".$_POST["bloqueo_firma_resumen_".$row_configuracion['conf_nombre']]."$".$_POST["bloqueo_carne_resumen_".$row_configuracion['conf_nombre']]."$".$_POST["bloqueo_foto_resumen1_".$row_configuracion['conf_nombre']]."$".$_POST["bloqueo_huella_resumen1_".$row_configuracion['conf_nombre']]."$".$_POST["bloqueo_firma_resumen1_".$row_configuracion['conf_nombre']]."$".$_POST["bloqueo_carne_resumen1_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 150){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST["criterio_".$row_configuracion['conf_nombre']]."$".$_POST["criterio2_".$row_configuracion['conf_nombre']]."$".$_POST["criterio3_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}
		elseif($row_configuracion['conf_id'] == 156){
				//ajuste lmgh
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '$".$_POST["planilla_prom_ant1_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant2_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant3_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant4_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant5_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant6_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant7_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant8_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant9_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant10_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant11_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant12_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant13_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant14_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant15_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant16_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant17_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant18_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant19_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant20_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant21_".$row_configuracion['conf_nombre']].

				                                                                "$".$_POST["planilla_prom_ant22_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant23_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant24_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant25_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant26_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant27_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant28_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant29_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant30_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant31_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant32_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant33_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant34_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant35_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant36_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant37_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant38_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant39_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant40_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant41_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant42_".$row_configuracion['conf_nombre']].

				                                                                "$".$_POST["planilla_prom_ant43_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant44_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant45_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant46_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant47_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant48_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant49_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant50_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant51_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant52_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant53_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant54_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant55_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant56_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant57_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant58_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant59_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant60_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant61_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant62_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant63_".$row_configuracion['conf_nombre']].

				                                                                "$".$_POST["planilla_prom_ant64_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant65_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant66_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant67_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant68_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant69_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant70_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant71_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant72_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant73_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant74_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant75_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant76_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant77_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant78_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant79_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant80_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant81_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant82_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant83_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant84_".$row_configuracion['conf_nombre']].

				                                                                "$".$_POST["planilla_prom_ant85_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant86_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant87_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant88_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant89_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant90_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant91_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant92_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant93_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant94_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant95_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant96_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant97_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant98_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant99_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant100_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant101_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant102_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant103_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant104_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
		
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}
		elseif($row_configuracion['conf_id'] == 162){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']]."$".$_POST['param_si']."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}
		elseif($row_configuracion['conf_id'] == 168){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST["criterio_".$row_configuracion['conf_nombre']]."$".$_POST["criterio2_".$row_configuracion['conf_nombre']]."$".$_POST["criterio3_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 240){
if ($_POST["reasignacion_".$row_configuracion['conf_nombre']] == 'S') {
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST["reasignacion_".$row_configuracion['conf_nombre']]."$".$_POST["radio_semana_numero"]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
}
else{
	$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST["reasignacion_".$row_configuracion['conf_nombre']]."$".""."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
}
		}elseif($row_configuracion['conf_id'] == 153){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST["certificado_".$row_configuracion['conf_nombre']].",".$_POST["valCertificado_".$row_configuracion['conf_nombre']]."@".$_POST["constancia_".$row_configuracion['conf_nombre']].",".$_POST["valConstancia_".$row_configuracion['conf_nombre']]."@".$_POST["pali_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 154){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']]."$".$_POST["valorplanirecu_".$row_configuracion['conf_nombre']]."$".$_POST["reasignacionRepro3_".$row_configuracion['conf_nombre']]."$".$_POST["cS_".$row_configuracion['conf_nombre']]."$".$_POST["cA_".$row_configuracion['conf_nombre']]."$".$_POST["cBs_".$row_configuracion['conf_nombre']]."$".$_POST["cBj_".$row_configuracion['conf_nombre']]."$".$_POST["cBjj_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 155){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']]."$".$_POST["valorplanirecu_".$row_configuracion['conf_nombre']]."$".$_POST["reasignacionRepro3_".$row_configuracion['conf_nombre']]."$".$_POST["cS_".$row_configuracion['conf_nombre']]."$".$_POST["cA_".$row_configuracion['conf_nombre']]."$".$_POST["cBs_".$row_configuracion['conf_nombre']]."$".$_POST["cBj_".$row_configuracion['conf_nombre']]."$".$_POST["cBjj_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 157){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '$".$_POST["planilla_prom_ant1_ind_1"]."$".$_POST["planilla_prom_ant1_ind_2"]."$".$_POST["planilla_prom_ant1_ind_3"]."$".$_POST["planilla_prom_ant1_ind_4"]."$".$_POST["planilla_prom_ant1_ind_5"]."$".$_POST["planilla_prom_ant1_ind_6"]."$".$_POST["planilla_prom_ant1_ind_7"]."$".$_POST["planilla_prom_ant1_ind_8"]."$".$_POST["planilla_prom_ant1_ind_9"]."$".$_POST["planilla_prom_ant10_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant11_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant12_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant13_ind_".$row_configuracion['conf_nombre']]."
                                          $".$_POST["planilla_prom_ant14_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant15_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant16_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant17_ind_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";	
                $upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar el parametro 81 del sistema");
               $sel_param_159 = "SELECT conf_valor FROM conf_sygescol WHERE conf_id = '157'";
	           $sql_param_159 = mysql_query($sel_param_159,$link);
	           $row_configuracion = mysql_fetch_array($sql_param_159);
               $num_configuracion = mysql_num_rows($sql_param_159);
		$array_parametro = explode("$",$row_configuracion['conf_valor']);/*
*/
		$proyecion_cupos_ind_1 = $array_parametro[1];
		$proyecion_cupos_ind_2 = $array_parametro[2];
		$proyecion_cupos_ind_3 = $array_parametro[3];
		$proyecion_cupos_ind_4 = $array_parametro[4];
		$proyecion_cupos_ind_5 = $array_parametro[5];
		$proyecion_cupos_ind_6 = $array_parametro[6];
        $proyecion_cupos_ind_7 = $array_parametro[7];
		$proyecion_cupos_ind_8 = $array_parametro[8];
		$proyecion_cupos_ind_9 = $array_parametro[9];
        $proyecion_cupos_ind_10 = $array_parametro[10];
		$proyecion_cupos_ind_11 = $array_parametro[11];
		$proyecion_cupos_ind_12 = $array_parametro[12];
        $proyecion_cupos_ind_13 = $array_parametro[13];
        $proyecion_cupos_ind_14 = $array_parametro[14];
		$proyecion_cupos_ind_15 = $array_parametro[15];
        $proyecion_cupos_ind_16 = $array_parametro[16];
        $proyecion_cupos_ind_17 = $array_parametro[17];
/*
print_r($array_parametro);
*/
        //a1
 $sel_param_1591 = "SELECT conf_obl_texto FROM config_planilla_oblig WHERE  conf_obl_id = '3'";
	           $sql_param_1591 = mysql_query($sel_param_1591,$link);
	           $row_configuracion1 = mysql_fetch_array($sql_param_1591);
               $num_configuracion1 = mysql_num_rows($sql_param_1591);
         //a2
 $sel_param_15911 = "SELECT conf_obl_texto FROM config_planilla_oblig WHERE  conf_obl_id = '4'";
	           $sql_param_15911 = mysql_query($sel_param_15911,$link);
	           $row_configuracion11 = mysql_fetch_array($sql_param_15911);
               $num_configuracion11 = mysql_num_rows($sql_param_15911);
              //a3
 $sel_param_159111 = "SELECT conf_obl_texto FROM config_planilla_oblig WHERE  conf_obl_id = '5'";
	           $sql_param_159111 = mysql_query($sel_param_159111,$link);
	           $row_configuracion111 = mysql_fetch_array($sql_param_159111);
               $num_configuracion111 = mysql_num_rows($sql_param_159111);
// ACCIONES 
 $sel_param_1591234 = "SELECT conf_obl_texto FROM config_planilla_oblig WHERE  conf_obl_id = '24'";
	           $sql_param_1591234 = mysql_query($sel_param_1591234,$link);
	           $row_configuracion1234 = mysql_fetch_array($sql_param_1591234);
               $num_configuracion1234 = mysql_num_rows($sql_param_1591234);
         //a2
 $sel_param_159112345 = "SELECT conf_obl_texto FROM config_planilla_oblig WHERE  conf_obl_id = '26'";
	           $sql_param_159112345 = mysql_query($sel_param_159112345,$link);
	           $row_configuracion112345 = mysql_fetch_array($sql_param_159112345);
               $num_configuracion112345 = mysql_num_rows($sql_param_159112345);
              //a3
 $sel_param_1591112344587543 = "SELECT conf_obl_texto FROM config_planilla_oblig WHERE  conf_obl_id = '25'";
	           $sql_param_1591112344587543 = mysql_query($sel_param_1591112344587543,$link);
	           $row_configuracion1112344587543 = mysql_fetch_array($sql_param_1591112344587543);
               $num_configuracion1112344587543 = mysql_num_rows($sql_param_1591112344587543);

 $sel_param_159111234496345 = "SELECT conf_obl_texto FROM config_planilla_oblig WHERE  conf_obl_id = '16'";
	           $sql_param_159111234459634 = mysql_query($sel_param_159111234496345,$link);
	           $row_configuracion111234459634 = mysql_fetch_array($sql_param_159111234459634);
               $num_configuracion111234459634 = mysql_num_rows($sql_param_159111234459634);

 $sel_param_159111234450011 = "SELECT conf_obl_texto FROM config_planilla_oblig WHERE  conf_obl_id = '17'";
	           $sql_param_159111234450011 = mysql_query($sel_param_159111234450011,$link);
	           $row_configuracion111234450011 = mysql_fetch_array($sql_param_159111234450011);
               $num_configuracion111234450011 = mysql_num_rows($sql_param_159111234450011);

 $sel_param_159111234459995 = "SELECT conf_obl_texto FROM config_planilla_oblig WHERE  conf_obl_id = '18'";
	           $sql_param_159111234459995 = mysql_query($sel_param_159111234459995,$link);
	           $row_configuracion111234459995 = mysql_fetch_array($sql_param_159111234459995);
               $num_configuracion111234459995 = mysql_num_rows($sql_param_159111234459995);
        
        $query_valida_2p = "SELECT * FROM subproceso_evaluacion WHERE subproeva_cod LIKE '".$row_configuracion1['conf_obl_texto']."'";
	$resultado_valida_2p = mysql_query($query_valida_2p, $link) or die(mysql_error());
    $rows_param_2p=mysql_fetch_array($resultado_valida_2p);
    $num_con_bol_2p = mysql_num_rows($resultado_valida_2p);
$query_valida2p = "UPDATE subproceso_evaluacion SET orden = '".$array_parametro[1]."' WHERE subproeva_cod LIKE '".$row_configuracion1['conf_obl_texto']."' ";   
$resultado_valida2p = mysql_query($query_valida2p, $link) or die(mysql_error());
    $rows_param=mysql_fetch_array($resultado_valida2p);
 $query_valida_2p1 = "SELECT * FROM subproceso_evaluacion WHERE subproeva_cod LIKE '".$row_configuracion11['conf_obl_texto']."'";
	$resultado_valida_2p1 = mysql_query($query_valida_2p1, $link) or die(mysql_error());
    $rows_param_2p1=mysql_fetch_array($resultado_valida_2p1);
    $num_con_bol_2p1 = mysql_num_rows($resultado_valida_2p1);
$query_valida2p1 = "UPDATE subproceso_evaluacion SET orden = '".$array_parametro[4]."' WHERE subproeva_cod LIKE '".$row_configuracion11['conf_obl_texto']."' ";   
$resultado_valida2p1 = mysql_query($query_valida2p1, $link) or die(mysql_error());
    $rows_param1=mysql_fetch_array($resultado_valida2p1);
     $query_valida_2p2 = "SELECT * FROM subproceso_evaluacion WHERE subproeva_cod LIKE '".$row_configuracion111['conf_obl_texto']."'";
	$resultado_valida_2p2 = mysql_query($query_valida_2p2, $link) or die(mysql_error());
    $rows_param_2p2=mysql_fetch_array($resultado_valida_2p2);
    $num_con_bol_2p2 = mysql_num_rows($resultado_valida_2p2);
$query_valida2p2 = "UPDATE subproceso_evaluacion SET orden = '".$array_parametro[7]."' WHERE subproeva_cod LIKE '".$row_configuracion111['conf_obl_texto']."' ";   
$resultado_valida2p2 = mysql_query($query_valida2p2, $link) or die(mysql_error());
    $rows_param2=mysql_fetch_array($resultado_valida2p2);
// ACTUALIZAR ORDEN DE ACCIONES EN LA PGU
     $query_valida_2p2 = "SELECT * FROM accion_evaluacion WHERE aceva_desc LIKE '".$row_configuracion1234['conf_obl_texto']."'";
	$resultado_valida_2p2 = mysql_query($query_valida_2p2, $link) or die(mysql_error());
    $rows_param_2p2=mysql_fetch_array($resultado_valida_2p2);
    $num_con_bol_2p2 = mysql_num_rows($resultado_valida_2p2);
$query_valida2p2 = "UPDATE accion_evaluacion SET ordenes = '".$array_parametro[3]."' WHERE aceva_desc LIKE '".$row_configuracion1234['conf_obl_texto']."' ";   
$resultado_valida2p2 = mysql_query($query_valida2p2, $link) or die(mysql_error());
    $rows_param222=mysql_fetch_array($resultado_valida2p2);
    // ACTUALIZAR ORDEN DE ACCIONES EN LA PGU
     $query_valida_2p21221 = "SELECT * FROM accion_evaluacion WHERE aceva_desc LIKE '".$row_configuracion112345['conf_obl_texto']."'";
	$resultado_valida_2p21221 = mysql_query($query_valida_2p21221, $link) or die(mysql_error());
    $rows_param_2p21221=mysql_fetch_array($resultado_valida_2p21221);
    $num_con_bol_2p21221 = mysql_num_rows($resultado_valida_2p21221);
$query_valida2p2998 = "UPDATE accion_evaluacion SET ordenes = '".$array_parametro[2]."' WHERE aceva_desc LIKE '".$row_configuracion112345['conf_obl_texto']."' ";   
$resultado_valida2p2998 = mysql_query($query_valida2p2998, $link) or die(mysql_error());
    $rows_param2998=mysql_fetch_array($resultado_valida2p2998);
    // ACTUALIZAR ORDEN DE ACCIONES EN LA PGU
     $query_valida_2p212211177 = "SELECT * FROM accion_evaluacion WHERE aceva_desc LIKE '".$row_configuracion1112344587543['conf_obl_texto']."'";
	$resultado_valida_2p212211177 = mysql_query($query_valida_2p212211177, $link) or die(mysql_error());
    $rows_param_2p212211177=mysql_fetch_array($resultado_valida_2p212211177);
    $num_con_bol_2p212211177 = mysql_num_rows($resultado_valida_2p212211177);
$query_valida2p29989955 = "UPDATE accion_evaluacion SET ordenes = '".$array_parametro[5]."' WHERE aceva_desc LIKE '".$row_configuracion1112344587543['conf_obl_texto']."' ";   
$resultado_valida2p29989955 = mysql_query($query_valida2p29989955, $link) or die(mysql_error());
    $rows_param29989955=mysql_fetch_array($resultado_valida2p29989955);
    // ACTUALIZAR ORDEN DE ACCIONES EN LA PGU
     $query_valida_2p212211177114433 = "SELECT * FROM accion_evaluacion WHERE aceva_desc LIKE '".$row_configuracion111234459634['conf_obl_texto']."'";
	$resultado_valida_2p212211177114433 = mysql_query($query_valida_2p212211177114433, $link) or die(mysql_error());
    $rows_param_2p212211177114433=mysql_fetch_array($resultado_valida_2p212211177114433);
    $num_con_bol_2p212211177114433 = mysql_num_rows($resultado_valida_2p212211177114433);
$query_valida2p29989955114433 = "UPDATE accion_evaluacion SET ordenes = '".$array_parametro[9]."' WHERE aceva_desc LIKE '".$row_configuracion111234459634['conf_obl_texto']."' ";   
$resultado_valida2p29989955114433 = mysql_query($query_valida2p29989955114433, $link) or die(mysql_error());
    $rows_param29989951144335=mysql_fetch_array($resultado_valida2p29989955114433);
    // ACTUALIZAR ORDEN DE ACCIONES EN LA PGU
     $query_valida_2p2122111771144339966 = "SELECT * FROM accion_evaluacion WHERE aceva_desc LIKE '".$row_configuracion111234450011['conf_obl_texto']."'";
	$resultado_valida_2p2122111771144339966 = mysql_query($query_valida_2p2122111771144339966, $link) or die(mysql_error());
    $rows_param_2p2122111771144339966=mysql_fetch_array($resultado_valida_2p2122111771144339966);
    $num_con_bol_2p2122111771144339966 = mysql_num_rows($resultado_valida_2p2122111771144339966);
$query_valida2p299899551144339966 = "UPDATE accion_evaluacion SET ordenes = '".$array_parametro[8]."' WHERE aceva_desc LIKE '".$row_configuracion111234450011['conf_obl_texto']."' ";   
$resultado_valida2p299899551144339966 = mysql_query($query_valida2p299899551144339966, $link) or die(mysql_error());
    $rows_param299899511443359966=mysql_fetch_array($resultado_valida2p299899551144339966);
    // ACTUALIZAR ORDEN DE ACCIONES EN LA PGU
     $query_valida_2p212211177114433996666 = "SELECT * FROM accion_evaluacion WHERE aceva_desc LIKE '".$row_configuracion111234459995['conf_obl_texto']."'";
	$resultado_valida_2p212211177114433996666 = mysql_query($query_valida_2p212211177114433996666, $link) or die(mysql_error());
    $rows_param_2p212211177114433996666=mysql_fetch_array($resultado_valida_2p212211177114433996666);
    $num_con_bol_2p212211177114433996666 = mysql_num_rows($resultado_valida_2p212211177114433996666);
$query_valida2p29989955114433996666 = "UPDATE accion_evaluacion SET ordenes = '".$array_parametro[7]."' WHERE aceva_desc LIKE '".$row_configuracion111234459995['conf_obl_texto']."' ";   
$resultado_valida2p29989955114433996666 = mysql_query($query_valida2p29989955114433996666, $link) or die(mysql_error());
    $rows_param29989951144335996666=mysql_fetch_array($resultado_valida2p29989955114433996666);

		}elseif($row_configuracion['conf_id'] == 159){
			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST["aplica_reconsideracion"]."$".$_POST["habilitacion_proceso_anio".$row_configuracion['conf_nombre']]."$".$_POST["habilitacion_proceso_area".$row_configuracion['conf_nombre']]."$".$_POST["habilitacion_proceso_".$row_configuracion['conf_nombre']]."$".$_POST["habilitacion_proceso_certificado".$row_configuracion['conf_nombre']]."$".$_POST["asignatura1_"]."$".$_POST["asignatura2_"]."$".$_POST["asignatura3_"]."$".$_POST["asignatura4_"]."$".$_POST["asignatura5_"]."$".$_POST["asignatura6_"]."$".$_POST["asignatura7_"]."$".$_POST["asignatura8_"]."$".$_POST["asignatura9_"]."$".$_POST["asignatura10_"]."$".$_POST["asignatura11_"]."$".$_POST["asignatura12_"]."$".$_POST["calificacion_minima"]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 165){
$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '$".$_POST["coord1"]."$".$_POST["coord2"]."$".$_POST["coord3"]."' WHERE conf_id = 165";	
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
			}elseif($row_configuracion['conf_id'] == 241){
$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '$".$_POST["planilla_prom_ant1_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant2_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant3_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant4_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant5_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant6_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant7_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant8_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant9_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant10_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant11_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant12_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant13_ind_".$row_configuracion['conf_nombre']]."
                                          $".$_POST["planilla_prom_ant14_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant15_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant16_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant17_ind_".$row_configuracion['conf_nombre']]."' WHERE conf_id = 241";	
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
			}elseif($row_configuracion['conf_id'] == 255){

				$array_nombres = array('1' => "primero", '2' => "segundo", '3' => "tercero", '4' => "cuarto", '5' => 'quinto' );


				for ($a=1; $a <=5 ; $a++) { 
					for ($i=7; $i <=17 ; $i++) { 
					$array_total[$a] .= $_POST[$array_nombres[$a]."_".$i].",";
					}
				}

				$aplica = $_POST['aplica_extraedad'];

				$cont_array_total = count($array_total);

				for ($i=1; $i <= $cont_array_total ; $i++) { 
					$valor .= $array_total[$i]."$";
				}

				if ($aplica == "N") {
				$fin_guardar = $aplica."$";
				}
				else{
				$fin_guardar = $aplica."$".$valor;
			}

			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '$fin_guardar'  WHERE conf_id = 255";	
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
			}
			elseif ($row_configuracion['conf_id'] == 256) {
			$valor = $_POST["param_matricula_foto"];
			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '$valor'  WHERE conf_id = 256";	
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
			}

				elseif($row_configuracion['conf_id'] == 160){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '$".$_POST["planilla_prom_ant1_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant2_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant3_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant4_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant5_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant6_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant7_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant8_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant9_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant10_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant11_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant12_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant13_ind_".$row_configuracion['conf_nombre']]."
                                          $".$_POST["planilla_prom_ant14_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant15_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant16_ind_".$row_configuracion['conf_nombre']]."$".$_POST["planilla_prom_ant17_ind_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
                $upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar el parametro 81 del sistema");
               $sel_param_159 = "SELECT conf_valor FROM conf_sygescol WHERE conf_id = '160'";
	           $sql_param_159 = mysql_query($sel_param_159,$link);
	           $row_configuracion = mysql_fetch_array($sql_param_159);
               $num_configuracion = mysql_num_rows($sql_param_159);
	//echo $row_configuracion['conf_valor'];
	$array_parametro = explode("$",$row_configuracion['conf_valor']);
		//Consultamos el tope maximo de los estudiantes (Zona Urbana)
		$proyecion_cupos_ind_1 = $array_parametro[1];
		$proyecion_cupos_ind_2 = $array_parametro[2];
		$proyecion_cupos_ind_3 = $array_parametro[3];
		$proyecion_cupos_ind_4 = $array_parametro[4];
		$proyecion_cupos_ind_5 = $array_parametro[5];
		$proyecion_cupos_ind_6 = $array_parametro[6];
        $proyecion_cupos_ind_7 = $array_parametro[7];
		$proyecion_cupos_ind_8 = $array_parametro[8];
		$proyecion_cupos_ind_9 = $array_parametro[9];
        $proyecion_cupos_ind_10 = $array_parametro[10];
		$proyecion_cupos_ind_11 = $array_parametro[11];
		$proyecion_cupos_ind_12 = $array_parametro[12];
        $proyecion_cupos_ind_13 = $array_parametro[13];
        $proyecion_cupos_ind_14 = $array_parametro[14];
		$proyecion_cupos_ind_15 = $array_parametro[15];
        $proyecion_cupos_ind_16 = $array_parametro[16];
        $proyecion_cupos_ind_17 = $array_parametro[17];
//BASICA PRIMARIA
$update_nivel_2="UPDATE `periodo_academicos` SET `ind_mortalidad_repro` = '".$proyecion_cupos_ind_1."' WHERE `periodo_academicos`.`nivel` = 2 AND `periodo_academicos`.`id_grado` = 1;";
$sql_update=mysql_query($update_nivel_2, $sygescol)or die("No se pudo Modificar el nivel 2");
$update_nivel_2="UPDATE `periodo_academicos` SET `ind_mortalidad_repro` = '".$proyecion_cupos_ind_2."' WHERE `periodo_academicos`.`nivel` = 2 AND `periodo_academicos`.`id_grado` = 2;";
$sql_update=mysql_query($update_nivel_2, $sygescol)or die("No se pudo Modificar el nivel 2");
$update_nivel_2="UPDATE `periodo_academicos` SET `ind_mortalidad_repro` = '".$proyecion_cupos_ind_3."' WHERE `periodo_academicos`.`nivel` = 2 AND `periodo_academicos`.`id_grado` = 3;";
$sql_update=mysql_query($update_nivel_2, $sygescol)or die("No se pudo Modificar el nivel 2");
$update_nivel_2="UPDATE `periodo_academicos` SET `ind_mortalidad_repro` = '".$proyecion_cupos_ind_4."' WHERE `periodo_academicos`.`nivel` = 2 AND `periodo_academicos`.`id_grado` = 4;";
$sql_update=mysql_query($update_nivel_2, $sygescol)or die("No se pudo Modificar el nivel 2");
$update_nivel_2="UPDATE `periodo_academicos` SET `ind_mortalidad_repro` = '".$proyecion_cupos_ind_5."' WHERE `periodo_academicos`.`nivel` = 2 AND `periodo_academicos`.`id_grado` = 5;";
$sql_update=mysql_query($update_nivel_2, $sygescol)or die("No se pudo Modificar el nivel 2");
//BASICA SECUNDARIA
$update_nivel_3="UPDATE `periodo_academicos` SET `ind_mortalidad_repro` = '".$proyecion_cupos_ind_6."' WHERE `periodo_academicos`.`nivel` =3 AND `periodo_academicos`.`id_grado` = 6;";
$sql_update=mysql_query($update_nivel_3, $sygescol)or die("No se pudo Modificar el nivel 3");
$update_nivel_3="UPDATE `periodo_academicos` SET `ind_mortalidad_repro` = '".$proyecion_cupos_ind_7."' WHERE `periodo_academicos`.`nivel` =3 AND `periodo_academicos`.`id_grado` = 7;";
$sql_update=mysql_query($update_nivel_3, $sygescol)or die("No se pudo Modificar el nivel 3");
$update_nivel_3="UPDATE `periodo_academicos` SET `ind_mortalidad_repro` = '".$proyecion_cupos_ind_8."' WHERE `periodo_academicos`.`nivel` =3 AND `periodo_academicos`.`id_grado` = 8;";
$sql_update=mysql_query($update_nivel_3, $sygescol)or die("No se pudo Modificar el nivel 3");
$update_nivel_3="UPDATE `periodo_academicos` SET `ind_mortalidad_repro` = '".$proyecion_cupos_ind_9."' WHERE `periodo_academicos`.`nivel` =3 AND `periodo_academicos`.`id_grado` = 9;";
$sql_update=mysql_query($update_nivel_3, $sygescol)or die("No se pudo Modificar el nivel 3");
//MEDIA
$update_nivel_4="UPDATE `periodo_academicos` SET `ind_mortalidad_repro` = '".$proyecion_cupos_ind_10."' WHERE `periodo_academicos`.`nivel` =4 AND `periodo_academicos`.`id_grado` = 10;";
$sql_update=mysql_query($update_nivel_4, $sygescol)or die("No se pudo Modificar el nivel 4");
$update_nivel_5="UPDATE `periodo_academicos` SET `ind_mortalidad_repro` = '".$proyecion_cupos_ind_11."' WHERE `periodo_academicos`.`nivel` =5 AND `periodo_academicos`.`id_grado` = 11;";
$sql_update=mysql_query($update_nivel_5, $sygescol)or die("No se pudo Modificar el nivel 5");
//CICLOS BASIA
$update_nivel_6="UPDATE `periodo_academicos` SET `ind_mortalidad_repro` = '".$proyecion_cupos_ind_12."' WHERE `periodo_academicos`.`nivel` =6 AND `periodo_academicos`.`id_grado` = 21;";
$sql_update=mysql_query($update_nivel_6, $sygescol)or die("No se pudo Modificar el nivel 6");
$update_nivel_6="UPDATE `periodo_academicos` SET `ind_mortalidad_repro` = '".$proyecion_cupos_ind_13."' WHERE `periodo_academicos`.`nivel` =6 AND `periodo_academicos`.`id_grado` = 22;";
$sql_update=mysql_query($update_nivel_6, $sygescol)or die("No se pudo Modificar el nivel 6");
$update_nivel_7="UPDATE `periodo_academicos` SET `ind_mortalidad_repro` = '".$proyecion_cupos_ind_14."' WHERE `periodo_academicos`.`nivel` =7 AND `periodo_academicos`.`id_grado` = 23;";
$sql_update=mysql_query($update_nivel_7, $sygescol)or die("No se pudo Modificar el nivel 7");
$update_nivel_7="UPDATE `periodo_academicos` SET `ind_mortalidad_repro` = '".$proyecion_cupos_ind_15."' WHERE `periodo_academicos`.`nivel` =7 AND `periodo_academicos`.`id_grado` = 24;";
$sql_update=mysql_query($update_nivel_7, $sygescol)or die("No se pudo Modificar el nivel 7");
//CICLOS MEDIA
$update_nivel_8="UPDATE `periodo_academicos` SET `ind_mortalidad_repro` = '".$proyecion_cupos_ind_16."' WHERE `periodo_academicos`.`nivel` =8 AND `periodo_academicos`.`id_grado` = 25;";
$sql_update=mysql_query($update_nivel_8, $sygescol)or die("No se pudo Modificar el nivel 8");
$update_nivel_8="UPDATE `periodo_academicos` SET `ind_mortalidad_repro` = '".$proyecion_cupos_ind_17."' WHERE `periodo_academicos`.`nivel` =8 AND `periodo_academicos`.`id_grado` = 26;";
$sql_update=mysql_query($update_nivel_8, $sygescol)or die("No se pudo Modificar el nivel 8");
        }
		elseif($row_configuracion['conf_id'] == 68){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '.".$_POST[$row_configuracion['conf_nombre']].",".$_POST["G_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 66){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']]."$".$_POST['areas_Obligatorias_']."$".$_POST['areas_Tecnicas_']."$".$_POST['valorEspecifico_D1']."$".$_POST['valorEspecifico_H1']."$".$_POST['valorEspecifico_D2']."$".$_POST['valorEspecifico_H2']."$".$_POST['valorEspecifico_D3']."$".$_POST['valorEspecifico_H3']."$".$_POST['valorEspecifico_D4']."$".$_POST['valorEspecifico_H4']."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
               $sel_param_66 = "SELECT conf_valor FROM conf_sygescol WHERE conf_id = '66'";
	           $sql_param_66 = mysql_query($sel_param_66,$link);
	           $row_configuracion = mysql_fetch_array($sql_param_66);
               $num_configuracion = mysql_num_rows($sql_param_66);
               $sel_esca = "SELECT * FROM escala_nacional_area_tecnica ";
	           $sql_esca = mysql_query($sel_esca,$link);
	           $row_configuracion_esca = mysql_fetch_array($sql_esca);
               $num_configuracion_esca = mysql_num_rows($sql_esca);
	    $array_parametro = explode("$",$row_configuracion['conf_valor']);
		//Consulta los valores para la escala
		$proyecion_escala_D1 = $array_parametro[3];
		$proyecion_escala_H1 = $array_parametro[4];
		$proyecion_escala_D2 = $array_parametro[5];
		$proyecion_escala_H2 = $array_parametro[6];
		$proyecion_escala_D3 = $array_parametro[7];
		$proyecion_escala_H3 = $array_parametro[8];
		$proyecion_escala_D4 = $array_parametro[9];
		$proyecion_escala_H4 = $array_parametro[10];
		if($num_configuracion_esca > 0){
		if($proyecion_escala_D1 > 0){
               $update_superior_min ="UPDATE `escala_nacional_area_tecnica` SET `esca_nac_min` = '".$proyecion_escala_D1."' WHERE `escala_nacional_area_tecnica`.`esca_nac_id` = 1;";
               $sql_update = mysql_query($update_superior_min, $sygescol)or die("No se pudo Modificar 1");
		}
		if($proyecion_escala_H1 > 0){
               $update_superior_max ="UPDATE `escala_nacional_area_tecnica` SET `esca_nac_max` = '".$proyecion_escala_H1."' WHERE `escala_nacional_area_tecnica`.`esca_nac_id` = 1;";
               $sql_update = mysql_query($update_superior_max, $sygescol)or die("No se pudo Modificar 1");
		}
		if($proyecion_escala_D2 > 0){
               $update_alto_min ="UPDATE `escala_nacional_area_tecnica` SET `esca_nac_min` = '".$proyecion_escala_D2."' WHERE `escala_nacional_area_tecnica`.`esca_nac_id` = 2;";
               $sql_update = mysql_query($update_alto_min, $sygescol)or die("No se pudo Modificar 2");
		}
		if($proyecion_escala_H2 > 0){
               $update_alto_max ="UPDATE `escala_nacional_area_tecnica` SET `esca_nac_max` = '".$proyecion_escala_H2."' WHERE `escala_nacional_area_tecnica`.`esca_nac_id` = 2;";
               $sql_update = mysql_query($update_alto_max, $sygescol)or die("No se pudo Modificar 2");
		}
		if($proyecion_escala_D3 > 0){
               $update_basico_min ="UPDATE `escala_nacional_area_tecnica` SET `esca_nac_min` = '".$proyecion_escala_D3."' WHERE `escala_nacional_area_tecnica`.`esca_nac_id` = 3;";
               $sql_update = mysql_query($update_basico_min, $sygescol)or die("No se pudo Modificar el nivel 3");
		}
		if($proyecion_escala_H3 > 0){
               $update_basico_max ="UPDATE `escala_nacional_area_tecnica` SET `esca_nac_max` = '".$proyecion_escala_H3."' WHERE `escala_nacional_area_tecnica`.`esca_nac_id` = 3;";
               $sql_update = mysql_query($update_basico_max, $sygescol)or die("No se pudo Modificar el nivel 3");
		}
		if($proyecion_escala_D4 > 0){
               $update_bajo_min ="UPDATE `escala_nacional_area_tecnica` SET `esca_nac_min` = '".$proyecion_escala_D4."' WHERE `escala_nacional_area_tecnica`.`esca_nac_id` = 4;";
               $sql_update = mysql_query($update_bajo_min, $sygescol)or die("No se pudo Modificar el nivel 3");
		}
		if($proyecion_escala_H4 > 0){
               $update_bajo_max ="UPDATE `escala_nacional_area_tecnica` SET `esca_nac_max` = '".$proyecion_escala_H4."' WHERE `escala_nacional_area_tecnica`.`esca_nac_id` = 4;";
               $sql_update = mysql_query($update_bajo_max, $sygescol)or die("No se pudo Modificar el nivel 3");
		    }
	     }
		}elseif($row_configuracion['conf_id'] == 67){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST["P_".$row_configuracion['conf_nombre']]."$".$_POST["S_".$row_configuracion['conf_nombre']]."$".$_POST["T_".$row_configuracion['conf_nombre']]."$".$_POST["C_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 70){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST["tra_".$row_configuracion['conf_nombre']]."$".$_POST["cicP_".$row_configuracion['conf_nombre']]."$".$_POST["cicB_".$row_configuracion['conf_nombre']]."$".$_POST["cicM_".$row_configuracion['conf_nombre']]."$".$_POST["gru_".$row_configuracion['conf_nombre']]."$".$_POST["nee_".$row_configuracion['conf_nombre']]."$".$_POST["ace_".$row_configuracion['conf_nombre']]."$".$_POST["pfc_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 92){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']]."$".$_POST['areas_Obligatorias92_']."$".$_POST['areas_Tecnicas92_']."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 73){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']]."$".$_POST["0_".$row_configuracion['conf_nombre']]."$".$_POST["1_".$row_configuracion['conf_nombre']]."$".$_POST["2_".$row_configuracion['conf_nombre']]."$".$_POST["3_".$row_configuracion['conf_nombre']]."$".$_POST["4_".$row_configuracion['conf_nombre']]."$".$_POST["5_".$row_configuracion['conf_nombre']]."$".$_POST["6_".$row_configuracion['conf_nombre']]."$".$_POST["7_".$row_configuracion['conf_nombre']]."$".$_POST["8_".$row_configuracion['conf_nombre']]."$".$_POST["9_".$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 14){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST["cAcc_".$row_configuracion['conf_nombre']]."$".$_POST["cPer_".$row_configuracion['conf_nombre']]."$".$_POST["per1"]."$".$_POST["per2"]."$".$_POST["per3"]."$".$_POST["per4"]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}elseif($row_configuracion['conf_id'] == 118){
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST["cAcc_".$row_configuracion['conf_nombre']]."$".$_POST["cPer_".$row_configuracion['conf_nombre']]."$".$_POST["per1"]."$".$_POST["per2"]."$".$_POST["per3"]."$".$_POST["per4"]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}else{
				$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$_POST[$row_configuracion['conf_nombre']]."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
				$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
				$sel_consultar="SELECT * FROM actas_impresion WHERE acta_fecha=CURDATE() AND tipo_dato='PARAMETROS_GENERALES' AND tipo_id='".$row_configuracion['conf_nombre']."'";
				$sql_consultar=mysql_query($sel_consultar, $sygescol)or die("No se pudo Consultar el Acta");
				$num_consultar=mysql_num_rows($sql_consultar);
				if($num_consultar==0){
					$update="INSERT INTO actas_impresion (tipo_dato, tipo_id, acta_fecha, tipo_contenido, tipo_valor)
								VALUES ('PARAMETROS_GENERALES', '".$row_configuracion['conf_nombre']."',CURDATE(), '".$row_configuracion['conf_descri']."', '".$_POST[$row_configuracion['conf_nombre']]."')";
					$sql_update=mysql_query($update, $sygescol)or die("No se pudo Modificar los datos del Acta1".$update);
				}else{
					$update="UPDATE actas_impresion
									SET tipo_contenido='".$row_configuracion['conf_descri']."', tipo_valor = '".$_POST[$row_configuracion['conf_nombre']]."'
							WHERE acta_fecha=CURDATE() AND tipo_dato='PARAMETROS_GENERALES' AND tipo_id='".$row_configuracion['conf_nombre']."'";
					$sql_update=mysql_query($update, $sygescol)or die("No se pudo Modificar los datos del Acta2");
				}
		}
       ////////////////////////////AREA GUARDAR INFORMACION DE NUEVOS PARAMETROS /////////////////////////////////////////
		/////  INFORMACION DE LOS NUEVOS PARAMETROS ////
		///////UPDATE PARAMETRO 244  USO DE LA APP ////////
		$sqlGuardarInformacion='update conf_sygescol set conf_valor="'.$_POST['usoApp244'].'" where conf_id=244';
		$sql_valor=mysql_query($sqlGuardarInformacion,$sygescol)or die("Error al actualizar datos del parametro 244".mysql_error());
		////////////UPDATE  PARAMETRO 245 /////////////////////
		$sql_update_configuracion="UPDATE conf_sygescol SET conf_estado =".$_POST['check_generacion_horarios']." WHERE conf_id=245";
		$upd_configuracion = mysql_query($sql_update_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");

		///////////

     
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
global $database_sygescol, $sygescol;
$update_adicyynorepita="UPDATE conf_sygescol SET conf_valor = '$final_pae' WHERE conf_id = 251";
$sql_updatejjnorepita=mysql_query($update_adicyynorepita, $sygescol)or die("No se pudo Modificar el adic");


$sel_transporte = "UPDATE conf_sygescol SET conf_valor = '$guardar_transporte' WHERE conf_id = 254";
$sql_transporte = mysql_query($sel_transporte, $link) or die("No se pudo Modificar el transporte");
		
	}while($row_configuracion = mysql_fetch_assoc($configuracion));
	header("Location: $_SERVER[PHP_SELF]?listo=ok");

}
mysql_select_db($database_sygescol, $sygescol);
$query_configuracion = "SELECT conf_sygescol.conf_id, conf_sygescol.conf_nombre, conf_sygescol.conf_valor, conf_sygescol.conf_descri, conf_sygescol.conf_nom_ver,encabezado_parametros.titulo
							FROM conf_sygescol INNER JOIN encabezado_parametros on encabezado_parametros.id_param=conf_sygescol.conf_id
						WHERE conf_sygescol.conf_estado = 0
						AND conf_sygescol.conf_id IN (152,65,93,95,96,100,109,66) ORDER BY encabezado_parametros.id_orden ";
$configuracion = mysql_query($query_configuracion, $sygescol) or die(mysql_error());
$row_configuracion = mysql_fetch_assoc($configuracion);
$totalRows_configuracion = mysql_num_rows($configuracion);
mysql_select_db($database_sygescol, $sygescol);
$query_usuarios = "SELECT usuarios.id, usuarios.username FROM usuarios ORDER BY usuarios.username";
$usuarios = mysql_query($query_usuarios, $sygescol) or die(mysql_error());
$row_usuarios = mysql_fetch_assoc($usuarios);
$totalRows_usuarios = mysql_num_rows($usuarios);
function cargarAnosSygescol($anno){
	global $database_sygescol, $sygescol;
	$datos_bd = explode("_", $database_sygescol);
	$dts_nom = $datos_bd[1];
	$nombre = '';
	//echo count($dts_nom);
	for($i=0; $i<strlen($dts_nom); $i++){
		if(!is_numeric($dts_nom[$i])){
			$nombre .= $dts_nom[$i];
		}
	}
	$array_annos = array();
	$recorrer = 1;
	while($recorrer == 1){
		$dat_syg = $datos_bd[0].'_'.$nombre.$anno;
		if(!(mysql_select_db($dat_syg,$sygescol)))
		{
			$recorrer = 0;
		}else{
			$array_annos["CER".$anno] = "Certificado de Estudio a&ntilde;o ".$anno;
		}
		$anno--;
	}
	return $array_annos;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $nombre_sistema; ?></title>
<script type="text/javascript" src="js/mootools.js"></script>
<script src="includes/cssmenus2/js/cssmenus.js" type="text/javascript"></script>
<script type="text/javascript" src="js/utilidades.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="js/calendario/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="js/calendario/calendar.js"></script>
<script type="text/javascript" src="js/calendario/lang/calendar-es.js"></script>
<script type="text/javascript" src="js/calendario/calendar-setup.js"></script>
<link href="css/basico.css" rel="stylesheet" type="text/css">
<link href="includes/cssmenus2/skins/viorange/horizontal.css" rel="stylesheet" type="text/css" />
<!-- MI JS PARA EDITAR -->
<link href="js/jquery/jquery-ui.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery/jquery-1.4.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery-ui.js"></script>
<link href="js/jquery/mobiscroll-2.1-beta.custom.min.css" rel="stylesheet" type="text/css" />
<script src="js/jquery/mobiscroll-2.1-beta.custom.min.js" type="text/javascript"></script>
<link href="js/jquery/js_select/select2.css" rel="stylesheet"/>
<script src="js/jquery/js_select/select2.js"></script>
<script type="text/javascript" src="js/jquery.jeditable.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script type="text/javascript">
var jqNc = jQuery.noConflict();
jqNc(document).ready(function() {
	jqNc(".sele_mul").select2({
		placeholder: 'Seleccione uno...'
	});
});
function ActivaCampo(padre,hijo)
{
	switch(padre)
	{
		case 'aplica_promoanti_120':
			if(jqNc("#"+padre).val() == "0"){
				jqNc("#"+hijo).attr('disabled', 'disabled')
				jqNc("#"+hijo).attr('value', '');
			}
			else{
				jqNc("#"+hijo).removeAttr('disabled');
			}
		break;
		case 'aplica_nota_comportamiento':
			if(jqNc("#"+padre).val() == "N"){
				jqNc("#"+hijo).attr('disabled', 'disabled')
				jqNc("#"+hijo).attr('value', '');
			}
			else{
				jqNc("#"+hijo).removeAttr('disabled');
			}
		break;
		case 'aplica_asistencia':
			if(jqNc("#"+padre).val() == "N"){
				jqNc("#"+hijo).attr('disabled', 'disabled')
				jqNc("#"+hijo).attr('value', '');
			}
			else{
				jqNc("#"+hijo).removeAttr('disabled');
			}
		break;
			/*	case 'areasReprobadas':
			if(jqNc("#"+padre).val() == "N"){
				jqNc("#"+hijo).attr('disabled', 'disabled')
				jqNc("#"+hijo).attr('value', '');
			}
			else{
				jqNc("#"+hijo).removeAttr('disabled');
			}
		break;
			case 'todasAreas':
			if(jqNc("#"+padre).val() == "N"){
				jqNc("#"+hijo).attr('disabled', 'disabled')
				jqNc("#"+hijo).attr('value', '');
			}
			else{
				jqNc("#"+hijo).removeAttr('disabled');
			}
		break;
				case 'demasAreas':
			if(jqNc("#"+padre).val() == "N"){
				jqNc("#"+hijo).attr('disabled', 'disabled')
				jqNc("#"+hijo).attr('value', '');
			}
			else{
				jqNc("#"+hijo).removeAttr('disabled');
			}
		break;*/
	}
}
</script>
<script type="text/javascript" src="scripts/jHtmlArea-0.7.5.js"></script>
<link rel="Stylesheet" type="text/css" href="style/jHtmlArea.css" />
<script type="text/javascript" src="scripts/jHtmlArea.ColorPickerMenu-0.7.0.js"></script>
<link rel="Stylesheet" type="text/css" href="style/jHtmlArea.ColorPickerMenu.css" />
<script type="text/javascript" src="js/js.js"></script>
<!-- FIN JS -->
<script type="text/javascript">
	var BPCN,BPCS,BPEA,BPEE,BPEF,BPER,BPHM,BPMT,BPFL,BPCP,BPOB,BPIN;
	var BSCN,BSCS,BSEA,BSEE,BSEF,BSER,BSHM,BSMT,BSFL,BSCP,BSOB,BSIN;
	var MDCN,MDCS,MDEA,MDEE,MDEF,MDER,MDHM,MDMT,MDFL,MDCP,MDOB,MDIN;
	var MOCN,MOCS,MOEA,MOEE,MOEF,MOER,MOHM,MOMT,MOFL,MOCP,MOOB,MOIN;
	function cambioNivel(valorSelect){
			/*if (valorSelect.selectedIndex == 0){
					var input = document.getElementById("CN_desempate_puesto_estu").value;
					alert("A seleccionado Basica Primaria");
					alert(input);
					}
			if (valorSelect.selectedIndex == 1){
					var input2 = document.getElementById("CN_desempate_puesto_estu").value;
					alert("A seleccionado Basica Secundaria");
					alert(input2);
					}
			if (valorSelect.selectedIndex == 2){
					alert("A seleccionado Media Decimo");
			}
			if (valorSelect.selectedIndex == 3){
		alert("A seleccionado Media Once");
			}*/
		if (valorSelect == 'BP' || valorSelect == 'BS'){
			var arregloMuestra = ["CN","CS","EA","EE","EF","ER","HM","MT","OB","IN"];
			var arregloOculta  = ["FL","CP"];
		}else{
			var arregloMuestra =  ["CN","CS","EA","EE","EF","ER","HM","MT","FL","CP","OB","IN"];
			var arregloOculta  =  "";
		}
		for(var i = 0; i <= arregloMuestra.length - 1; i++){
			document.getElementById(arregloMuestra[i]+"_desempate_puesto_estu").disabled = false;
		};
		if (valorSelect == 'BP' || valorSelect == 'BS')	{
			for(var j = 0; j <= arregloOculta.length - 1; j++){
				document.getElementById(arregloOculta[j]+"_desempate_puesto_estu").disabled = true;
			}
		};
	}
	function cambiaPuesto(valor, nombre, propiedad){
		var nivelEdu;
		if (valor != " "){
			var materias = ["CN","CS","EA","EE","EF","ER","HM","MT","FL","CP","OB","IN"];
			for (var i = 0; i <= 12; i++){
				if(document.getElementById(materias[i]+"_"+nombre).value  == valor && materias[i] != propiedad){
					document.getElementById(propiedad+"_"+nombre).value = "";
				}else{
					//nivelEdu = document.getElementById("nivelesEducacion").value;
					//nivelEdu+materias[i] = "Juan";
				}
			}
		}
	}
	function justNumbers(e)
{
var keynum = window.event ? window.event.keyCode : e.which;
if ((keynum == 8) || (keynum == 46))
return true;
return /\d/.test(String.fromCharCode(keynum));
}
</script>
<script>
	/*cambio de color acordiones*/
    function cambiar_fondo_acor1() {
                obj = document.getElementById('parametros_promocion');
                obj.style.backgroundColor = (obj.style.backgroundColor == '#8E8E8E') ? 'none' : '#8E8E8E';
            }
    function cambiar_fondo_acor2() {
                obj = document.getElementById('parametros_matriculas');
                obj.style.backgroundColor = (obj.style.backgroundColor == '#8E8E8E') ? 'none' : '#8E8E8E';
            }
    function cambiar_fondo_acor3() {
                obj = document.getElementById('parametros_inasistencias');
                obj.style.backgroundColor = (obj.style.backgroundColor == '#8E8E8E') ? 'none' : '#8E8E8E';
            }
    function cambiar_fondo_acor4() {
                obj = document.getElementById('parametros_control_calificaciones');
                obj.style.backgroundColor = (obj.style.backgroundColor == '#8E8E8E') ? 'none' : '#8E8E8E';
            }
    function cambiar_fondo_acor5() {
                obj = document.getElementById('parametros_horarios');
                obj.style.backgroundColor = (obj.style.backgroundColor == '#8E8E8E') ? 'none' : '#8E8E8E';
            }
    function cambiar_fondo_acor6() {
                obj = document.getElementById('parametros_promocion_estudiantes');
                obj.style.backgroundColor = (obj.style.backgroundColor == '#8E8E8E') ? 'none' : '#8E8E8E';
            }
    function cambiar_fondo_acor7() {
                obj = document.getElementById('parametros_constancias_certificados');
                obj.style.backgroundColor = (obj.style.backgroundColor == '#8E8E8E') ? 'none' : '#8E8E8E';
            }
    function cambiar_fondo_acor8() {
                obj = document.getElementById('parametros_acudientes');
                obj.style.backgroundColor = (obj.style.backgroundColor == '#8E8E8E') ? 'none' : '#8E8E8E';
            }
    function cambiar_fondo_acor9() {
                obj = document.getElementById('parametros_fotografica');
                obj.style.backgroundColor = (obj.style.backgroundColor == '#8E8E8E') ? 'none' : '#8E8E8E';
            }
    function cambiar_fondo_acor10() {
                obj = document.getElementById('parametros_modulos_nuevos');
                obj.style.backgroundColor = (obj.style.backgroundColor == '#8E8E8E') ? 'none' : '#8E8E8E';
            }
    function cambiar_fondo_acor11() {
                obj = document.getElementById('parametros_vigencia_tiempos');
                obj.style.backgroundColor = (obj.style.backgroundColor == '#8E8E8E') ? 'none' : '#8E8E8E';
            }
    function cambiar_fondo_acor12() {
                obj = document.getElementById('parametros_automatizacion_sistema');
                obj.style.backgroundColor = (obj.style.backgroundColor == '#8E8E8E') ? 'none' : '#8E8E8E';
            }
                function cambiar_fondo_acor13() {
                obj = document.getElementById('parametros_integrantes');
                obj.style.backgroundColor = (obj.style.backgroundColor == '#8E8E8E') ? 'none' : '#8E8E8E';
            }
                function cambiar_fondo_acor14() {
                obj = document.getElementById('parametros_definir_aspectos_plan_estudio');
                obj.style.backgroundColor = (obj.style.backgroundColor == '#8E8E8E') ? 'none' : '#8E8E8E';
            }
                function cambiar_fondo_acor15() {
                obj = document.getElementById('parametros_cierre_año');
                obj.style.backgroundColor = (obj.style.backgroundColor == '#8E8E8E') ? 'none' : '#8E8E8E';
            }
</script>
<style>
	.busqm{
	top: 130px;
	width: 800px;
	height: 100px;
	left: 9.5%;
	background-color: transparent;
	position: absolute;
	}
</style>
<link href="js/jquery/jquery-ui.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<link rel="stylesheet" type="text/css" href="sweetalert2.css">
<script src="sweetalert2.min.js"></script>
<style>
.sweet-alert {
    background-color: white;
}
</style>
<!-- BARRA DE PROGRESO ACEVEDO -->
<style type="text/css" media="screen">
	.circle {
    background-color: rgba(0,0,0,0);
    border: 5px solid rgba(0,183,229,0.9);
    opacity: .9;
    border-right: 5px solid rgba(0,0,0,0);
    border-left: 5px solid rgba(0,0,0,0);
    border-radius: 100px;
    box-shadow: 0 0 35px #2187e7;
    width: 100px;
    height: 100px;
    margin: 0 auto;
    -moz-animation: spinPulse 1s infinite ease-in-out;
    -webkit-animation: spinPulse 1s infinite linear;
}
.circle1 {
    background-color: rgba(0,0,0,0);
    border: 5px solid rgba(0,183,229,0.9);
    opacity: .9;
    border-left: 5px solid rgba(0,0,0,0);
    border-right: 5px solid rgba(0,0,0,0);
    border-radius: 100px;
    box-shadow: 0 0 15px #2187e7;
    width: 80px;
    height: 80px;
    margin: -49px auto;
    position: relative;
    top: -50px;
    -moz-animation: spinoffPulse 1s infinite linear;
    -webkit-animation: spinoffPulse 1s infinite linear;
}
@-moz-keyframes spinPulse {
    0% {
        -moz-transform: rotate(160deg);
        opacity: 0;
        box-shadow: 0 0 1px #2187e7;
    }
    50% {
        -moz-transform: rotate(145deg);
        opacity: 1;
    }
    100% {
        -moz-transform: rotate(-320deg);
        opacity: 0;
    };
}
@-moz-keyframes spinoffPulse {
    0% {
        -moz-transform: rotate(0deg);
    }
    100% {
        -moz-transform: rotate(360deg);
    };
}
@-webkit-keyframes spinPulse {
    0% {
        -webkit-transform: rotate(160deg);
        opacity: 0;
        box-shadow: 0 0 1px #2187e7;
    }
    50% {
        -webkit-transform: rotate(145deg);
        opacity: 1;
    }
    100% {
        -webkit-transform: rotate(-320deg);
        opacity: 0;
    };
}
@-webkit-keyframes spinoffPulse {
    0% {
        -webkit-transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
    };
}
@keyframes blur{
  from{
      text-shadow:0px 0px 5px #058DB1,
      0px 0px 5px #058DB1, 
      0px 0px 20px #058DB1,
      0px 0px 20px #058DB1,
      0px 0px 50px #058DB1,
      0px 0px 50px #058DB1,
      0px 0px 80px #7B96B8;
  }
}
</style>
</head>
<!-- BARRA DE PROGRESO ACEVEDO -->
<body onmouseover="validar15141212()"> 
<!-- A continuación, creamos el DIV con el aviso -->
<div id='aviso' style='text-align:center;margin:0px auto;position:fixed;left:0px;top:0px;height:100%;width:100%;background-image:url(fondoprogreso.gif);border-color:#000000;z-index:9999;'><br><br><br><br><br><br><br><br><br><br><br><br>
<div class="circle"></div>
<div class="circle1"></div>
<br><br><br>
 <span style='font-family:Verdana;font-size:25px;font-weight:bold;color:#fff;font-weight: bold;animation:blur 1s 2 ease-out ;
  '>Cargando datos<br> Espere un momento</span></div>
<!-- ESCONDER BLOQUE ACEVEDO-->
<script type='text/javascript'>
/*ocultarcarga=jQuery.noConflict();
setTimeout(function(){ocultarcarga("#aviso").fadeOut(1500);});*/
function ocultarcajacas14(){
	ocultarcarga=jQuery.noConflict();
setTimeout(function(){ocultarcarga("#aviso").fadeOut(100);},4500)
}
ocultarcajacas14(); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
<!-- BARRA DE PROGRESO ACEVEDO -->
<?php
include_once("inc.header.php");
?>
<table align="center" width="auto" class="centro" cellpadding="10">
<tr><td></td>
</tr>
<tr>
<th scope="col" colspan="1" class="centro">PAR&Aacute;METROS GENERALES </th>
<tr>
<td>

</td>
</tr>
</tr >
<tr >
<td id='super_contenedor'>
<br />
<br />
<br />
<?php
include ("conb.php");$registros=mysqli_query($conexion,"select * from conf_sygescol_adic where id=1")or die("Problemas en la Consulta".mysqli_error());while ($reg=mysqli_fetch_array($registros)){$coloracord=$reg['valor'];}
?>
<form id="formprinpargen" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" name="grado_grupo" lang="es" onclick="return validaFormulario(this)">
<div class="container_demohrvszv_caja_1">
		<div class="accordion_example2wqzx_caja_2">
			<div class="accordion_inwerds_caja_3">
				<div class="acc_headerfgd_caja_titulo" id="parametros_promocion" style="background-color: <?php echo $coloracord ?>"><strong>1. PAR&Aacute;METROS PARA ESTABLECER CRITERIOS DE EVALUACI&Oacute;N Y PROMOCI&Oacute;N</strong><br /><center><input type="radio" value="rojo" name="colores">Si&nbsp;&nbsp;<input type="radio" value="naranja" name="colores">No</div></center>
				<div class="acc_contentsaponk_caja_4">
					<div class="grevdaiolxx_caja_5">
					<table   width="50%" class="centro" cellpadding="10"  border="1">
<tr>
	<th class="formulario"  style="background: #3399FF;" >Consecutivo </th>
	<th class="formulario"  style="background: #3399FF;" >Id Par&aacute;metro </th>
	<th class="formulario" >Tipo de Par&aacute;metro</th>
    <th class="formulario" >Detalle del Par&aacute;metro</th>
	<th class="formulario">Selecci&oacute;n</th>
	</tr>
	<?php
	$consecutivo=0;
	do
	{
		$consecutivo++;
	?>
	<td class="formulario"   id="Consecutivo<?php echo $consecutivo; ?>" ><strong> <?php echo $consecutivo; ?></strong></td>
	<td class="formulario"  id="Parametro<?php echo $row_configuracion['conf_id']; ?>" ><strong><?php echo $row_configuracion['conf_id'] ?></strong></td>
<td valign="top"><strong>
<div class="container_demohrvszv_caja_tipo_param">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Tipo de Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx_caja_tipo_param">
<div class="sin_resaltado">
<div id="conf_nom_ver">
<div  class="textarea"align="justify" id="conf_nom_ver-<?php echo $row_configuracion['conf_id'] ?>"><?php echo $row_configuracion['conf_nom_ver']; ?></div></div>
</div></div></div></div></div></div>
<style type="text/css">
	.sin_resaltado{
outline: none;
}
</style>
</strong>
</td>
      <td valign="top" width="80%">
     <div class="container_demohrvszv" >
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd"  id="cgasvf"><strong>Detalle Par&aacute;metro</strong></div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
<div id="conf_descri">
      <div  align="justify" class="textarea " id="conf_descri-<?php echo $row_configuracion['conf_id'] ?>" style="width: 90%;" align="justify">
      <?php echo $row_configuracion['conf_descri']; ?>
      </div>
		</div>			</div>
				</div>
			</div>
		</div>
</div>
 </td>
	<td align="center">
	<?php
	switch($row_configuracion['conf_id'])
	{
			case 65:
		$estado = '';
				if(strpos($row_configuracion['conf_valor'],"$")>0)
				{
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
					$estado = $array_parametro[1];
				}
				else
					$parametro = $row_configuracion['conf_valor'];
				?>
			 <table  width="90%" >
				 <tr>
				 <td><b>Aplica</b>
				 <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
				  	<a href="criterios_evaluacion.php" target="_blank" style="color:#3399FF">Ir a criterios de evaluacion</a>
				  </td>
				 </tr>
			</table>
<?php
		break;
	case 152:
		$estado = '';
				if(strpos($row_configuracion['conf_valor'],"$")>0)
				{
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
					$estado = $array_parametro[1];
				}
				else
					$parametro = $row_configuracion['conf_valor'];
				?>
			 <table  width="90%" >
				 <tr>
				 <td><b>Aplica</b>
				 <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
				  	<a href="proceso_evaluacion.php" target="_blank" style="color:#3399FF">Ir a procesos de evaluacion</a>
				  </td>
				 </tr>
			</table>
<?php
		break;
	case 93:
		$estado = '';
				if(strpos($row_configuracion['conf_valor'],"$")>0)
				{
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
					$estado = $array_parametro[1];
				}
				else
					$parametro = $row_configuracion['conf_valor'];
				?>
			 <table  width="90%" >
				 <tr>
				 <td><b>Aplica</b>
				 <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
				  	  	<a href="criterios_promocion.php" target="_blank" style="color:#3399FF">Ir a criterios de promocion</a>
				  </td>
				 </tr>
			</table>
<?php
		break;
//aca va el caso 95,96,100,109,
		case 95:
				$estado = '';
				if(strpos($row_configuracion['conf_valor'],"$")>0)
				{
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
					$estado = $array_parametro[1];
				}
				else
					$parametro = $row_configuracion['conf_valor'];
				?>
			 <table  width="90%" >
				 <tr>
				 <td><b>Aplica</b>
				 <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
				  	<div class="cajaparametrogenerales"><a href="configuracion_periodos_numero.php" target="_blank" style="color:#3399FF">Ir a numero de periodos academicos</a></div>
					<style>
				.cajaparametrogenerales {
						    width: 300px;
						    float: right;
						}
					</style>
				  </td>
				 </tr>
			</table>
					<?php
		break;
		case 96:
				$estado = '';
				if(strpos($row_configuracion['conf_valor'],"$")>0)
				{
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
					$estado = $array_parametro[1];
				}
				else
					$parametro = $row_configuracion['conf_valor'];
				?>
			 <table  width="90%" >
				 <tr>
				 <td><b>Aplica</b>
				 <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
				  		<div class="cajaparametrogenerales"><a href="periodos_estudiante.php" target="_blank" style="color:#3399FF">Ir de fechas de periodos academicos</a></div>
				  </td>
				 </tr>
			</table>
					<?php
		break;
case 100:
				$estado = '';
				if(strpos($row_configuracion['conf_valor'],"$")>0)
				{
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
					$estado = $array_parametro[1];
				}
				else
					$parametro = $row_configuracion['conf_valor'];
				?>
			 <table  width="90%" >
				 <tr>
				 <td><b>Aplica</b>
				 <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
				  	<div class="cajaparametrogenerales"><a href="configuracion_comportamiento.php" target="_blank" style="color:#3399FF">Ir a configuracion de comportamiento</a></div>
				  </td>
				 </tr>
			</table>
					<?php
		break;
		case 109:
				$estado = '';
				if(strpos($row_configuracion['conf_valor'],"$")>0)
				{
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
					$estado = $array_parametro[1];
				}
				else
					$parametro = $row_configuracion['conf_valor'];
				?>
			 <table  width="90%" >
				 <tr>
				 <td><b>Aplica</b>
				 <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
				  	<a href="configuracion_boletines.php" target="_blank" style="color:#3399FF">Ir a configuracion de boletines</a>
				  </td>
				 </tr>
			</table>
					<?php
		break;
	case 66: //Ingresar notas despues del cierre de areas
		?>
			<?php
			$estado = '';
			if(strpos($row_configuracion['conf_valor'],"$")>0)
			{
				$array_parametro = explode("$",$row_configuracion['conf_valor']);
				$parametro = $array_parametro[0];
				$areasObligatorias = $array_parametro[1];
				$areasTecnicas = $array_parametro[2];
				$valorEspecifico_D1 = $array_parametro[3];
				$valorEspecifico_H1 = $array_parametro[4];
				$valorEspecifico_D2 = $array_parametro[5];
				$valorEspecifico_H2 = $array_parametro[6];
				$valorEspecifico_D3 = $array_parametro[7];
				$valorEspecifico_H3 = $array_parametro[8];
				$valorEspecifico_D4 = $array_parametro[9];
				$valorEspecifico_H4 = $array_parametro[10];
			}
			else
				$parametro = $row_configuracion['conf_valor'];
		?>
		<br><br>
		<table >
	        		<tr><b>Aplica</b>
				  	  <select class="sele_mul op" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>" onclick="validar4()">
							<option value="S" <?php if (!(strcmp("S", $parametro['conf_valor']))) {echo "selected=\"selected\"";} ?>>Si</option>
							<option value="N" <?php if (!(strcmp("N", $parametro['conf_valor']))) {echo "selected=\"selected\"";} ?>>No</option>
						  </select>
                	</tr>
</div>
</div>
</div>
</div>
	</div>
<script>
function validar4(){
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="S")
{
 document.getElementById("parametro44_1").disabled = false;
document.getElementById("parametro44_2").disabled = false;
document.getElementById("valorEspecifico_D1").disabled = false;
document.getElementById("valorEspecifico_D2").disabled = false;
document.getElementById("valorEspecifico_D3").disabled = false;
document.getElementById("valorEspecifico_D4").disabled = false;
document.getElementById("valorEspecifico_H1").disabled = false;
document.getElementById("valorEspecifico_H2").disabled = false;
document.getElementById("valorEspecifico_H3").disabled = false;
document.getElementById("valorEspecifico_H4").disabled = false;
}
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="N")
{
document.getElementById("parametro44_1").disabled = true;
document.getElementById("parametro44_2").disabled = true;
document.getElementById("valorEspecifico_D1").disabled = true;
document.getElementById("valorEspecifico_D2").disabled = true;
document.getElementById("valorEspecifico_D3").disabled = true;
document.getElementById("valorEspecifico_D4").disabled = true;
document.getElementById("valorEspecifico_H1").disabled = true;
document.getElementById("valorEspecifico_H2").disabled = true;
document.getElementById("valorEspecifico_H3").disabled = true;
document.getElementById("valorEspecifico_H4").disabled = true;
}
}
	addEvent('load', validar4); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
		<br>
		<br>
        <br><hr>
		<br>
		<td>
<div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
     <div class="accordion_inwerds">
        <div class="acc_headerfgd"><strong>&Iacute;tem</strong> </div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
			<table  border="1" style="border:1px solid black">
			<tr >
				<td style="text-align:center;">Para las Asignaturas de las areas fundamentales obligatorias</td>
				<td><input type="text"style="width:50px;" onkeypress="return justNumbers(event);" id="parametro44_1"value="<?php echo $areasObligatorias; ?>" name="areas_Obligatorias_" class="parametro4"></td>
			</tr>
			<tr>
				<td style="text-align:center;">Para asignaturas para areas tecnicas</td>
				<td><input type="text"style="width:50px;"  onkeypress="return justNumbers(event);" id="parametro44_2"value="<?php echo $areasTecnicas; ?>" name="areas_Tecnicas_" class="parametro4"></td>
			</tr>
		</table>
	</td>
		</table>
</div></div></div></div></div>
<a href="./tablak.php"
        onclick="return hs.htmlExpand(this, {
            contentId: 'my-content',
            objectType: 'iframe',
            objectWidth: 650,
            objectHeight: 450} )"
        class="highslide">
   Escala
</a>
<div class="highslide-html-content"
        id="my-content" style="width: 650px">
    <div style="text-align: right">
       <a href="#" onclick="return hs.close(this)">
          Close
       </a>
    </div>    
    <div class="highslide-body"></div>
</div>
		<?php
		break;
	}
	?>
	</td>
	</tr>
	<?php
	}while($row_configuracion = mysql_fetch_assoc($configuracion));
	?>
<?php
///Instanciacion de clases para el llamado de los parametros
include 'classParametrosGenerales.php';
$objetoParametros1=new ParametrosGenerales();  ///Instancio el Objeto ParametroGenerales
$conexionBd=$objetoParametros1->creaConexion();  ///llamo la funcion creaConexion
$numPersiana=1; 
$cantidadParametros=$objetoParametros1 -> returnCantidadParametrosPersiana($conexionBd,$numPersiana); //Retorna la cantidad de parametros que hay dentro de la persiana
$cant=0;
while($cant<$cantidadParametros)  //Mientras haya Parametros Entre al while
{
	 	$valor_id_parametro_individual=$objetoParametros1  -> devuelvaParametros($cant);
       $objetoParametros1->ejecutarImpresionParametros($numPersiana,$conexionBd,$consecutivo,$valor_id_parametro_individual ); //Ejecuta la impresion de los paramertros individualmente
       $consecutivo=$objetoParametros1->returnConsecutivo();    //Retorna el consecutivo
        $informacionDelParametro=$objetoParametros1  ->informacionParametro($conexionBd,$valor_id_parametro_individual);  //Informacion del parametro 
       $cant++;
       // switch($valor_id_parametro_individual)
       // {
       // }
}

?>
</table>
</div>
</div>
</div>
</div>
</div>
<?php
// esta es la tabla 2
if($totalRows_configuracion)
{
	mysql_data_seek($configuracion,0);
mysql_select_db($database_sygescol, $sygescol);
	$query_configuracion = "SELECT conf_sygescol.conf_id, conf_sygescol.conf_nombre, conf_sygescol.conf_valor, conf_sygescol.conf_descri, conf_sygescol.conf_nom_ver, encabezado_parametros.titulo
								FROM conf_sygescol  INNER JOIN encabezado_parametros on encabezado_parametros.id_param=conf_sygescol.conf_id
							WHERE conf_sygescol.conf_estado = 0
								AND conf_sygescol.conf_id IN (68,73,99,123,124,115,161,167,241,255,256)  ORDER BY encabezado_parametros.id_orden ";
	$configuracion = mysql_query($query_configuracion, $sygescol) or die(mysql_error());
	$row_configuracion = mysql_fetch_assoc($configuracion);
// aca inicia la otra tabla
}?>
<?php
include ("conb.php");$registrosb=mysqli_query($conexion,"select * from conf_sygescol_adic where id=2")or die("Problemas en la Consulta".mysqli_error());while ($regb=mysqli_fetch_array($registrosb)){$coloracordb=$regb['valor'];}
?>
<div class="container_demohrvszv_caja_1">
		<div class="accordion_example2wqzx_caja_2">
			<div class="accordion_inwerds_caja_3">
				<div class="acc_headerfgd_caja_titulo" id="parametros_matriculas" style="background-color: <?php echo $coloracordb ?>"><center><strong>2. PAR&Aacute;METROS PARA MATRICULAS</strong></center><br /><center><input type="radio" value="rojob" name="coloresb">Si&nbsp;&nbsp;<input type="radio" value="naranjab" name="coloresb">No</div></center>
				<div class="acc_contentsaponk_caja_4">
					<div class="grevdaiolxx_caja_5">
					<table  align="center" width="85%" class="centro" cellpadding="10" class="formulario"  border="1">
<tr>
	<th class="formulario"  style="background: #3399FF;" >Consecutivo </th>
	<th class="formulario"  style="background: #3399FF;" >Id Par&aacute;metro </th>
	<th class="formulario" >Tipo de Par&aacute;metro</th>
    <th class="formulario" >Detalle del Par&aacute;metro</th>
	<th class="formulario">Selecci&oacute;n</th>
	</tr>
	<?php
	do
	{
		$consecutivo++;
	?>
	<td class="formulario"   id="Consecutivo<?php echo $consecutivo; ?>" ><strong> <?php echo $consecutivo; ?></strong></td>
	<td class="formulario"  id="Parametro<?php echo $row_configuracion['conf_id']; ?>" ><strong><?php echo $row_configuracion['conf_id'] ?></strong></td>
<td valign="top"><strong>
<div class="container_demohrvszv_caja_tipo_param">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Tipo de Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx_caja_tipo_param">
<div id="conf_nom_ver">
<div  class="textarea "  align="justify" id="conf_nom_ver-<?php echo $row_configuracion['conf_id'] ?>"><?php echo $row_configuracion['conf_nom_ver']; ?></div>
</div></div></div></div></div></div>
</strong>
</td>      <td valign="top" width="80%">
     <div class="container_demohrvszv" >
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd"  id="cgasvf"><strong>Detalle Par&aacute;metro</strong></div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
<div id="conf_descri">
      <div  align="justify" class="textarea " id="conf_descri-<?php echo $row_configuracion['conf_id'] ?>" style="width: 90%;" align="justify">
      <?php echo $row_configuracion['conf_descri']; ?>
      </div>
</div>
					</div>
				</div>
			</div>
		</div>
</div>
 </td>
	<td align="center">
<?php
	switch($row_configuracion['conf_id'])
	{//este es el inicio
			case 68: //forma_ing_fallas
		?>
		<table>
		<tr>
			<td><b>Aplica</b>
			 <select class="sele_mul"  onclick="validar6()" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
				<option value="Y" <?php if (strpos($row_configuracion['conf_valor'],"Y") == true)  {echo "selected=\"selected\"";} ?>>Si</option>
				<option value="N" <?php if (strpos($row_configuracion['conf_valor'],"N") == true) {echo "selected=\"selected\"";} ?>>No</option>
			  </select>
			</td>
		</tr>
		<tr>
			<td><b>Si aplica defina grado en que se dejar&#225; al estudiante:</b></td>
		 	<td>
			  <select class="sele_mul" name="G_<?php echo $row_configuracion['conf_nombre']; ?>" id="G_<?php echo $row_configuracion['conf_nombre']; ?>">
			  <option value="0" <?php if (strpos($row_configuracion['conf_valor'],"0") == true) {echo "selected=\"selected\"";} ?>>Seleccione uno... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
				<option value="GA" <?php if (strpos($row_configuracion['conf_valor'],"GA") == true) {echo "selected=\"selected\"";} ?>>Grado No Promovido</option>
				<option value="GS" <?php if (strpos($row_configuracion['conf_valor'],"GS") == true) {echo "selected=\"selected\"";} ?>>Grado Siguiente</option>
			  </select>
			</td>
		 </tr>
		 </table>
		<script>
function validar6(){
if(document.getElementById("<?php echo $row_configuracion['conf_nombre']; ?>").value=="Y"){
document.getElementById("G_<?php echo $row_configuracion['conf_nombre']; ?>").disabled=false;
}
if(document.getElementById("<?php echo $row_configuracion['conf_nombre']; ?>").value=="N"){
document.getElementById("G_<?php echo $row_configuracion['conf_nombre']; ?>").disabled=true;
}
}
	addEvent('load', validar6); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
		<?php
		break;
/*FIN CAMBIO1*/
	case 73: //forma_ing_fallas
		$valoresP73 = explode("$", $row_configuracion['conf_valor']);
		?>
        <div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
     <div class="accordion_inwerds">
        <div class="acc_headerfgd">&Iacute;tem </div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
		<table>
	<tr><td><input onclick="validar73()" name="<?php echo $row_configuracion['conf_nombre']; ?>" <?php if ($valoresP73[0] == 3) {echo 'checked="checked"';} ?> value="3" type="radio"></td><td>Toda la informaci&oacute;n de la inscripci&oacute;n</td></tr>
			<tr><td><input onclick="validar73_1()"name="<?php echo $row_configuracion['conf_nombre']; ?>" <?php if ($valoresP73[0] == 0) {echo 'checked="checked"';} ?> value="0" type="radio"></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
			<table>
				<tr><td><input id="73_1"type="checkbox" name="0_<?php echo $row_configuracion['conf_nombre']; ?>" <?php if ($valoresP73[1] == "a") {echo 'checked="checked"';} ?> value="a"></td><td>Informaci&oacute;n de la Matricula</td></tr>
				<tr><td><input id="73_22"type="checkbox" name="1_<?php echo $row_configuracion['conf_nombre']; ?>" <?php if ($valoresP73[2] == 1) {echo 'checked="checked"';} ?> value="1"></td><td>Informaci&oacute;n B&aacute;sica Estudiante</td></tr>
				<tr><td><input id="73_3"type="checkbox" name="2_<?php echo $row_configuracion['conf_nombre']; ?>" <?php if ($valoresP73[3] == 2) {echo 'checked="checked"';} ?> value="2"></td><td>Informaci&oacute;n Localizaci&oacute;n Estudiante</td></tr>
				<tr><td><input id="73_4"type="checkbox" name="3_<?php echo $row_configuracion['conf_nombre']; ?>" <?php if ($valoresP73[4] == 3) {echo 'checked="checked"';} ?> value="3"></td><td>Estudiante Victima de Conflicto</td></tr>
				<tr><td><input id="73_5"type="checkbox" name="4_<?php echo $row_configuracion['conf_nombre']; ?>" <?php if ($valoresP73[5] == 4) {echo 'checked="checked"';} ?> value="4"></td><td>Informaci&oacute;n Salud del Estudiante</td></tr>
				<tr><td><input id="73_6"type="checkbox" name="5_<?php echo $row_configuracion['conf_nombre']; ?>" <?php if ($valoresP73[6] == 5) {echo 'checked="checked"';} ?> value="5"></td><td>Informaci&oacute;n del Acudiente</td></tr>
				<tr><td><input id="73_7"type="checkbox" name="6_<?php echo $row_configuracion['conf_nombre']; ?>" <?php if ($valoresP73[7] == 6) {echo 'checked="checked"';} ?> value="6"></td><td>Informaci&oacute;n de la madre</td></tr>
				<tr><td><input id="73_8"type="checkbox" name="7_<?php echo $row_configuracion['conf_nombre']; ?>" <?php if ($valoresP73[8] == 7) {echo 'checked="checked"';} ?> value="7"></td><td>Informaci&oacute;n del padre</td></tr>
				<tr><td><input id="73_9"type="checkbox" name="8_<?php echo $row_configuracion['conf_nombre']; ?>" <?php if ($valoresP73[9] == 8) {echo 'checked="checked"';} ?> value="8"></td><td>Cuadro acumulativo de matricula</td></tr>
				<tr><td><input id="73_10"type="checkbox" name="9_<?php echo $row_configuracion['conf_nombre']; ?>" <?php if ($valoresP73[10] == 9) {echo 'checked="checked"';} ?> value="9"></td><td>Prerrequisitos de matr&iacute;cula</td></tr>
			</table>
			</td></tr>
			<tr><td><input onclick="validar73()" name="<?php echo $row_configuracion['conf_nombre']; ?>" <?php if ($valoresP73[0] == 1) {echo 'checked="checked"';} ?> value="1" type="radio"></td><td>Cargar &#250;nicamente la renovaci&#243;n de la matr&#237;cula, lo que implica manejar un libro virtual y de todas formas imprimir anualmente.</td></tr>
			<tr><td><input onclick="validar73()"name="<?php echo $row_configuracion['conf_nombre']; ?>" <?php if ($valoresP73[0] == 2) {echo 'checked="checked"';} ?> value="2" type="radio"></td><td>Dejar el mismo modelo del a&ntilde;o anterior</td></tr>
		</table>
<script type="text/javascript">
function validar73() {
    document.getElementById("73_1").checked = false;
     document.getElementById("73_22").checked = false;
      document.getElementById("73_3").checked = false;
      document.getElementById("73_4").checked = false;
        document.getElementById("73_5").checked = false;
         document.getElementById("73_6").checked = false;
          document.getElementById("73_7").checked = false;
           document.getElementById("73_8").checked = false;
            document.getElementById("73_9").checked = false;
             document.getElementById("73_10").checked = false;
              document.getElementById("73_1").disabled = true;
			    document.getElementById("73_22").disabled = true;
			      document.getElementById("73_3").disabled = true;
			        document.getElementById("73_4").disabled = true;
       				   document.getElementById("73_5").disabled = true;
         				document.getElementById("73_6").disabled = true;
                          document.getElementById("73_7").disabled = true;
           					document.getElementById("73_8").disabled = true;
            document.getElementById("73_9").disabled = true;
             document.getElementById("73_10").disabled = true;
}
function validar73_1() {
    document.getElementById("73_1").disabled = false;
     document.getElementById("73_22").disabled = false;
      document.getElementById("73_3").disabled = false;
       document.getElementById("73_4").disabled = false;
        document.getElementById("73_5").disabled = false;
         document.getElementById("73_6").disabled = false;
          document.getElementById("73_7").disabled = false;
           document.getElementById("73_8").disabled = false;
            document.getElementById("73_9").disabled = false;
             document.getElementById("73_10").disabled = false;
}
</script>
				</div>
</div>
</div>
</div>
</div>		 <!-- <select name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>" class="sele_mul" style="width:320px;">
			<option value="0" <?php if (!(strcmp("0", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Toda la Informaci&#243;n de la Inscripci&#243;n</option>
			<option value="1" <?php if (!(strcmp("1", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Cargar &#250;nicamente la renovaci&#243;n de la matr&#237;cula, lo que implica manejar un libro virtual y de todas formas imprimir anualmente.</option>
		  </select>-->
		<?php
		break;
		//aca va el caso 99
		case 99:
				$estado = '';
				if(strpos($row_configuracion['conf_valor'],"$")>0)
				{
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
					$estado = $array_parametro[2];
				}
				else
					$parametro = $row_configuracion['conf_valor'];
				?>
			 <table  width="90%" >
				 <tr>
				 <td><b>Aplica</b>
				 <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
				  </td>
				 </tr>
			</table>
					<?php
		break;
			case 123	: //Actualizacion Hoja de vida
		?>
			 <table  width="90%" >
				 <tr>
				 <td><b>Aplica</b>
				 <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
				  </td>
				 </tr>
			</table>
		<?php
		break;
case 124: //forma_ing_fallas
		$valoresP73 = explode("$", $row_configuracion['conf_valor']);
		?>
        <div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
     <div class="accordion_inwerds">
        <div class="acc_headerfgd">&Iacute;tem </div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
		<table>
			<tr><td><input onclick="validar12494()" id="p124_1"name="<?php echo $row_configuracion['conf_nombre']; ?>" <?php if ($valoresP73[0] == 1) {echo 'checked="checked"';} ?> value="1" type="radio"></td><td>Existe RUM (Registro &Uacute;nico de Matr&iacute;cula)</td></tr>
			<tr><td><input id="p124_2"onclick="validar12494()"name="<?php echo $row_configuracion['conf_nombre']; ?>" <?php if ($valoresP73[0] == 2) {echo 'checked="checked"';} ?> value="2" type="radio"></td><td>Hacer carga masiva del RUM (Registro &Uacute;nico de Matr&iacute;cula)</td></tr>
		</table>
	</div>
</div>
</div>
</div>
</div>
<?php
break;
		// aca va el caso 115,161,162
		case 161:
		$estado = '';
				if(strpos($row_configuracion['conf_valor'],"$")>0)
				{
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
					$estado = $array_parametro[1];
				}
				else
					$parametro = $row_configuracion['conf_valor'];
				?>
			 <table  width="90%" >
				 <tr>
				 <td><b>Aplica</b>
				 <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
				  	<a href="proyeccion_cupos_decreto.php" target="_blank" style="color:#3399FF">Ir a proyecci&oacute;n de cupos</a>
				  </td>
				 </tr>
			</table>
<?php
		break;
case 241: 
$array_parametro = explode("$",$row_configuracion['conf_valor']);
		//Consultamos el tope maximo de los estudiantes (Zona Urbana)
		$proyecion_cupos_ind_1 = $array_parametro[1];
		$proyecion_cupos_ind_2 = $array_parametro[2];
		$proyecion_cupos_ind_3 = $array_parametro[3];
		$proyecion_cupos_ind_4 = $array_parametro[4];
		$proyecion_cupos_ind_5 = $array_parametro[5];
		$proyecion_cupos_ind_6 = $array_parametro[6];
		$proyecion_cupos_ind_7 = $array_parametro[7];
		$proyecion_cupos_ind_8 = $array_parametro[8];
		$proyecion_cupos_ind_9 = $array_parametro[9];
        $proyecion_cupos_ind_10 = $array_parametro[10];
		$proyecion_cupos_ind_11 = $array_parametro[11];
        $proyecion_cupos_ind_12 = $array_parametro[12];
        $proyecion_cupos_ind_13 = $array_parametro[13];
        $proyecion_cupos_ind_14 = $array_parametro[14];
        $proyecion_cupos_ind_15 = $array_parametro[15];
        $proyecion_cupos_ind_16 = $array_parametro[16];
        $proyecion_cupos_ind_17 = $array_parametro[17];
		?>
<!-- PARAMETRO 82 -->
		<div class="container_demohrvszv">
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd">&Iacute;tem</div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
		<table width="100%" class="formulario" align="center" style="float:left;">
						<th class="formulario" colspan="3"><center>SEG&Uacute;N DECRETO 3011</center></th>
			<tr>
				<th class="formulario"><center>De</center></th>
				<th class="formulario"><center>Para:</center></th>
				<th class="formulario"><center>Edad minima</center></th>
			</tr>
			<tr class="fila1">
				<td>Primero <br />a <br />Tercero</td>
				<td>Ciclo 1</td>
				<td style='text-align:center;'><input type="text" onkeypress="return justNumbers(event);" id="18241_1"name="planilla_prom_ant1_ind_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_ind_1; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
           <tr class="fila2">
					<td>Cuarto <br />a <br />Quinto</td>
				<td>Ciclo 2</td>
				<td style='text-align:center;'><input type="text" onkeypress="return justNumbers(event);" 
name="planilla_prom_ant2_ind_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_ind_2; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
           <tr class="fila1">
				<td>Sexto <br />a <br />S&eacute;ptimo</td>
				<td>Ciclo 3</td>
				<td style='text-align:center;'><input type="text" onkeypress="return justNumbers(event);" 
name="planilla_prom_ant3_ind_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_ind_3; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
           <tr class="fila2">
					<td>Octavo<br />a <br />Noveno</td>
				<td>Ciclo 4</td>
				<td style='text-align:center;'><input type="text" onkeypress="return justNumbers(event);" 
name="planilla_prom_ant4_ind_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_ind_4; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
           <tr class="fila1">
					<td>D&eacute;cimo</td>
				<td>Ciclo 5</td>
				<td style='text-align:center;'><input type="text" onkeypress="return justNumbers(event);" 
name="planilla_prom_ant5_ind_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_ind_5; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
			<tr class="fila2">
					<td>Und&eacute;cimo</td>
				<td>Ciclo 6</td>
				<td style='text-align:center;'><input type="text" onkeypress="return justNumbers(event);" 
name="planilla_prom_ant6_ind_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_ind_6; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
		</table>	
		</div>
</div>
</div>
</div>
</div>
<!-- FIN PARAMETRO 82 -->
		<?php
		break;
		case 167:
		$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$reasignacionRepro_ = $array_parametro[1];
					$reasignacionRepro2_ = $array_parametro[2];
					$reasignacionRepro4_ = $array_parametro[3];
					$reasignacionRepro5_ = $array_parametro[4];
					$reasignacionRepro6_ = $array_parametro[5];
					$reasignacionRepro7_ = $array_parametro[6];
		$valoresP43 = explode("$",$row_configuracion['conf_valor']);
			$e1111= $valoresP43[5];
	$parametro = $array_parametro[0];
?>
<div class="container_demohrvszv">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Si aplica defina:</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
		<table>
				<tr>
<td> <input id="p19_1111" type="radio" <?php if (!(strcmp("A", $array_parametro[1]))) {echo "checked=checked";} ?> value="A" name="valorplanirecu_<?php echo $row_configuracion['conf_nombre']; ?>"/> </td> <td>Con pines e inscripci&oacute;n (matricula individual y matricula por biometria)<br /><br /></td>
</tr>
<tr>
<td> <input id="p19_2222" type="radio" <?php if (!(strcmp("B", $array_parametro[1]))) {echo "checked=checked";} ?> value="B" name="valorplanirecu_<?php echo $row_configuracion['conf_nombre']; ?>"/> </td> <td> por estado academico a&ntilde;o anterior (matricula por promoci&oacute;n a&ntilde;o anterior)</td>
				</tr>
			</table>
<a href="biometrico_control.php" target="_blank" style="color:#3399FF">Ir a param&eacute;tros de Biometr&iacute;a</a>
</div>
</div>
</div>
</div>
</div>
<?php
break;
case 115:
				$estado = '';
				if(strpos($row_configuracion['conf_valor'],"$")>0)
				{
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
					$estado = $array_parametro[1];
				}
				else
					$parametro = $row_configuracion['conf_valor'];
				?>
			 <table  width="90%" >
				 <tr>
				 <td><b>Aplica</b>
				 <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select><div class="cajaparametrogenerales"><a href="grados_y_grupos.php" target="_blank" style="color:#3399FF">Ir a proyecci&oacute;n de sedes, grados y grupos </a></div>
				  </td>
				 </tr>
			</table>
					<?php
		break;

case 255:
	$sql_param_extraedad = "SELECT * FROM conf_sygescol WHERE conf_id = 255";
	$sel_param_extraedad = mysql_query($sql_param_extraedad,$link) or die("No se puede consultar el parametro de extraedad");
	$rows_param_extraedad = mysql_fetch_assoc($sel_param_extraedad);
	$valido = $rows_param_extraedad['conf_valor']; 
	$valido = explode("$",$valido);
?>
        <div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
     <div class="accordion_inwerds">
        <div class="acc_headerfgd">Rangos de preferencia para determinar si un estudiante se encuentra en Extraedad </div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
          <div>
          	<center>
          		<select class="sele_mul" name="aplica_extraedad" id="aplica_extraedad" onchange="mostrar_tabla(this.value)">
          			<option value="N" <?php if($valido[0] == "N") {echo "selected";} ?>>No</option>
          			<option value="S" <?php if($valido[0] == "S") {echo "selected";} ?>>Si</option>
          		</select>
          	</center>
          </div>
          <br /><br />
          <?php if($valido[0] == "S") {$clase_div = "";}else{$clase_div = "style='display:none;'";} ?>

          <div id="mostrar_tabla" <?php echo $clase_div ?>>
		<table class="table" border="1">
		<tr>
			<td></td>
			<td class="celda">Primero</td>
			<td class="celda">Segundo</td>
			<td class="celda">Tercero</td>
			<td class="celda">Cuarto</td>
			<td class="celda">Quinto</td>
		</tr>
<?php 

	for ($i=7; $i <=17 ; $i++) 
	{ 

	?>
	<tr>
		<td class="celda"><?php echo $i.utf8_decode(" Años") ?></td>
		<td id="td_primero_<?php echo $i ?>" onclick="cambia_color('primero','<?php echo $i ?>')" id="div_<?php echo $i ?>" class="celda <?php $explode_valido = explode(",", $valido[1]);	$count_explode_valido = count($explode_valido)-1;for ($a=0; $a < $count_explode_valido; $a++) {if ($explode_valido[$a] == $i) { echo "selected";}} ?>"><div style="display:none;"><input type="radio" id="primero_<?php echo $i ?>" name="primero_<?php echo $i ?>" value="<?php echo $i ?>" <?php $explode_valido = explode(",", $valido[1]);	$count_explode_valido = count($explode_valido)-1;for ($a=0; $a < $count_explode_valido; $a++) {if ($explode_valido[$a] == $i) { echo "checked";}} ?>/></div></td>
		<td id="td_segundo_<?php echo $i ?>" onclick="cambia_color('segundo','<?php echo $i ?>')" class="celda <?php $explode_valido = explode(",", $valido[2]);	$count_explode_valido = count($explode_valido)-1;for ($a=0; $a < $count_explode_valido; $a++) {if ($explode_valido[$a] == $i) { echo "selected";}} ?>"><div id="div_<?php echo $i ?>" style="display:none;"><input type="radio" id="segundo_<?php echo $i ?>" name="segundo_<?php echo $i ?>" value="<?php echo $i ?>" <?php $explode_valido = explode(",", $valido[2]);	$count_explode_valido = count($explode_valido)-1;for ($a=0; $a < $count_explode_valido; $a++) {if ($explode_valido[$a] == $i) { echo "checked";}} ?>/></div></td>
		<td id="td_tercero_<?php echo $i ?>" onclick="cambia_color('tercero','<?php echo $i ?>')" class="celda <?php $explode_valido = explode(",", $valido[3]);	$count_explode_valido = count($explode_valido)-1;for ($a=0; $a < $count_explode_valido; $a++) {if ($explode_valido[$a] == $i) { echo "selected";}} ?>"><div  id="div_<?php echo $i ?>" style="display:none;"><input type="radio" id="tercero_<?php echo $i ?>" name="tercero_<?php echo $i ?>" value="<?php echo $i ?>" <?php $explode_valido = explode(",", $valido[3]);	$count_explode_valido = count($explode_valido)-1;for ($a=0; $a < $count_explode_valido; $a++) {if ($explode_valido[$a] == $i) { echo "checked";}} ?>/></div></td>
		<td id="td_cuarto_<?php echo $i ?>" onclick="cambia_color('cuarto','<?php echo $i ?>')" class="celda <?php $explode_valido = explode(",", $valido[4]);	$count_explode_valido = count($explode_valido)-1;for ($a=0; $a < $count_explode_valido; $a++) {if ($explode_valido[$a] == $i) { echo "selected";}} ?>"><div  id="div_<?php echo $i ?>" style="display:none;"><input id="cuarto_<?php echo $i ?>" name="cuarto_<?php echo $i ?>" type="radio" value="<?php echo $i ?>" <?php $explode_valido = explode(",", $valido[4]);	$count_explode_valido = count($explode_valido)-1;for ($a=0; $a < $count_explode_valido; $a++) {if ($explode_valido[$a] == $i) { echo "checked";}} ?>/></div></td>
		<td id="td_quinto_<?php echo $i ?>" onclick="cambia_color('quinto','<?php echo $i ?>')" class="celda <?php $explode_valido = explode(",", $valido[5]);	$count_explode_valido = count($explode_valido)-1;for ($a=0; $a < $count_explode_valido; $a++) {if ($explode_valido[$a] == $i) { echo "selected";}} ?>"><div style="display:none;" id="div_<?php echo $i ?>"><input type="radio" id="quinto_<?php echo $i ?>" name="quinto_<?php echo $i ?>" value="<?php echo $i ?>" <?php $explode_valido = explode(",", $valido[5]);	$count_explode_valido = count($explode_valido)-1;for ($a=0; $a < $count_explode_valido; $a++) {if ($explode_valido[$a] == $i) { echo "checked";}} ?>/></div></td>
	</tr>
	<?php
	}
 ?>
		</table>
		</div>

<style>
.table{
	border-collapse:collapse;
	border-spacing:0; 
	border-color: #000;
}
.celda{
    height: 30px;
    width: 60px;
    text-align: center;
}
.selected{
	background-color: #2F75B5;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>

var j = jQuery.noConflict();

function cambia_color(nombre_caja,valor){
if (j("#td_"+nombre_caja+"_"+valor).hasClass('selected')) 
{
j("#"+nombre_caja+"_"+valor).prop("checked", "");
j("#td_"+nombre_caja+"_"+valor).removeClass("selected");
}
else
{
j("#"+nombre_caja+"_"+valor).prop("checked", "checked");
j("#td_"+nombre_caja+"_"+valor).addClass("selected");
}
}

function mostrar_tabla(valor){
	if (valor == "S") {
		j("#mostrar_tabla").css("display","");
	}
	else{
		j("#mostrar_tabla").css("display","none");
	}
}
</script>

</div>
</div>
</div>
</div>
</div>
<?php
	break;

	case 256:
	$sql_param_matricula_foto = "SELECT * FROM conf_sygescol WHERE conf_id = 256";
	$sel_param_matricula_foto = mysql_query($sql_param_matricula_foto,$link) or die("No se puede consultar el parametro de extraedad");
	$rows_param_matricula_foto = mysql_fetch_assoc($sel_param_matricula_foto);
	$valido = $rows_param_matricula_foto['conf_valor']; 
	?>
        <div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
     <div class="accordion_inwerds">
        <div class="acc_headerfgd">Aplica </div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
          <div>
          	<center>
          		<select class="sele_mul" name="param_matricula_foto" id="param_matricula_foto" onchange="mostrar_tabla(this.value)">
          			<option value="N" <?php if($valido == "N") {echo "selected";} ?>>No</option>
          			<option value="S" <?php if($valido == "S") {echo "selected";} ?>>Si</option>
          		</select>
          	</center>
          </div>
</div>
</div>
</div>
</div>
</div>
	<?php
	break;
		}// este es el fin
?>
	</td>
</tr>
<?php
}while($row_configuracion = mysql_fetch_assoc($configuracion));
?>
<?php
///Instanciacion de clases para el llamado de los parametros
// include 'ParametrosGenerales.php';
$objetoParametros2=new ParametrosGenerales();  ///Instancio el Objeto ParametroGenerales
$conexionBd=$objetoParametros2->creaConexion();  ///llamo la funcion creaConexion
$numPersiana=2;    
$cantidadParametros=$objetoParametros2 -> returnCantidadParametrosPersiana($conexionBd,$numPersiana); //Retorna la cantidad de parametros que hay dentro de la persiana
$cant=0;
while($cant<$cantidadParametros)  //Mientras haya Parametros Entre al while
{
	 	$valor_id_parametro_individual=$objetoParametros2  -> devuelvaParametros($cant);
       $objetoParametros2->ejecutarImpresionParametros($numPersiana,$conexionBd,$consecutivo,$valor_id_parametro_individual ); //Ejecuta la impresion de los paramertros individualmente
       $consecutivo=$objetoParametros2->returnConsecutivo();    //Retorna el consecutivo
        $informacionDelParametro=$objetoParametros2  ->informacionParametro($conexionBd,$valor_id_parametro_individual);  //Informacion del parametro 
       $cant++;
       // switch($valor_id_parametro_individual)
       // {
       // }
}
?>
</table>
</div>
</div>
</div>
</div>
</div>
<!-- ---------------------------------- ACORDEON PARAMETROS PARA REGISTRO DE INASISTENCIAS -------------------------------------- -->
<?php
// esta es la tabla 2
if($totalRows_configuracion)
{
	mysql_data_seek($configuracion,0);
mysql_select_db($database_sygescol, $sygescol);
	$query_configuracion = "SELECT conf_sygescol.conf_id, conf_sygescol.conf_nombre, conf_sygescol.conf_valor, conf_sygescol.conf_descri, conf_sygescol.conf_nom_ver, encabezado_parametros.titulo
								FROM conf_sygescol  INNER JOIN encabezado_parametros on encabezado_parametros.id_param=conf_sygescol.conf_id
							WHERE conf_sygescol.conf_estado = 0
								AND conf_sygescol.conf_id IN (14,67,76,87,111,127,132,141,149,163,236)  ORDER BY encabezado_parametros.id_orden ";
	$configuracion = mysql_query($query_configuracion, $sygescol) or die(mysql_error());
	$row_configuracion = mysql_fetch_assoc($configuracion);
// aca inicia la otra tabla
}?>
<?php
include ("conb.php");$registrosc=mysqli_query($conexion,"select * from conf_sygescol_adic where id=3")or die("Problemas en la Consulta".mysqli_error());while ($regc=mysqli_fetch_array($registrosc)){$coloracordc=$regc['valor'];}
?>
<div class="container_demohrvszv_caja_1">
		<div class="accordion_example2wqzx_caja_2">			<div class="accordion_inwerds_caja_3">
				<div class="acc_headerfgd_caja_titulo" id="parametros_inasistencias" style="background-color: <?php echo $coloracordc ?>"><center><strong>3. PAR&Aacute;METROS PARA REGISTRO DE INASISTENCIAS</strong></center><br /><center><input type="radio" value="rojoc" name="coloresc">Si&nbsp;&nbsp;<input type="radio" value="naranjac" name="coloresc">No</div></center>
				<div class="acc_contentsaponk_caja_4">
					<div class="grevdaiolxx_caja_5">
					<table  align="center" width="85%" class="centro" cellpadding="10" class="formulario"  border="1">
	<tr>
	<th class="formulario"  style="background: #3399FF;" >Consecutivo </th>
	<th class="formulario"  style="background: #3399FF;" >Id Par&aacute;metro </th>
	<th class="formulario" >Tipo de Par&aacute;metro</th>
    <th class="formulario" >Detalle del Par&aacute;metro</th>
	<th class="formulario">Selecci&oacute;n</th>
	</tr>
	<?php
	do
	{
		$consecutivo++;
	?>
	<td class="formulario"   id="Consecutivo<?php echo $consecutivo; ?>" ><strong> <?php echo $consecutivo; ?></strong></td>
	<td class="formulario"  id="Parametro<?php echo $row_configuracion['conf_id']; ?>" ><strong><?php echo $row_configuracion['conf_id'] ?></strong></td>
<td valign="top"><strong>
<div class="container_demohrvszv_caja_tipo_param">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Tipo de Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx_caja_tipo_param">
<div id="conf_nom_ver">
<div  class="textarea "  align="justify" id="conf_nom_ver-<?php echo $row_configuracion['conf_id'] ?>"><?php echo $row_configuracion['conf_nom_ver']; ?></div>
</div></div></div></div></div></div>
</strong>
</td>      <td valign="top" width="80%">
     <div class="container_demohrvszv" >
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd"  id="cgasvf"><strong>Detalle Par&aacute;metro</strong></div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
<div id="conf_descri">
      <div  align="justify" class="textarea " id="conf_descri-<?php echo $row_configuracion['conf_id'] ?>" style="width: 90%;" align="justify">
      <?php echo $row_configuracion['conf_descri']; ?>
      </div>
</div>
					</div>
				</div>
			</div>
		</div>
</div>
 </td>
	<td align="center">
<?php
	switch($row_configuracion['conf_id'])
	{//este es el inicio
	case 14: //forma_ing_fallas
		$valorP14 = explode("$", $row_configuracion['conf_valor']);
		//print_r($valorP14);
		?>
		<script type="text/javascript">
function validar1514() {
	if (document.getElementById('sdpmt1514').value == 'C') {
document.getElementById('pmt1514').style.display = "none";
	}
		if (document.getElementById('sdpmt1514').value != 'C') {
document.getElementById('pmt1514').style.display = "";
	}
}
addEvent('load', validar1514); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
		</script>
		<label>
		<h4>Control de Permanencia</h4>
		  <select class="sele_mul"  style="width: 70%;" name="cAcc_<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
		  	<option value="A" <?php if (!(strcmp("A", $valorP14[0]))) {echo "selected=\"selected\"";} ?>>Seleccione uno ... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
			<option value="G" <?php if (!(strcmp("G", $valorP14[0]))) {echo "selected=\"selected\"";} ?>>General</option>
			<option value="D" <?php if (!(strcmp("D", $valorP14[0]))) {echo "selected=\"selected\"";} ?>>Detallado</option>
			<option value="C" <?php if (!(strcmp("C", $valorP14[0]))) {echo "selected=\"selected\"";} ?>>Combinado</option>
		  </select>
		</label>
		<hr>
		<label>
		<h4>Control de Acceso</h4>
		  <select class="sele_mul"  style="width: 70%;" name="cPer_<?php echo $row_configuracion['conf_nombre']; ?>" id="sdpmt1514" onclick="validar1514()">
<option value="0" <?php if (!(strcmp("0", $valorP14[1]))) {echo "selected=\"selected\"";} ?>>Seleccione uno... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
		  	<option value="C" <?php if (!(strcmp("C", $valorP14[1]))) {echo "selected=\"selected\"";} ?>>No Aplica</option>
			<option value="B" <?php if (!(strcmp("B", $valorP14[1]))) {echo "selected=\"selected\"";} ?>>Control de acceso Biom&eacute;trico</option>
			<option value="A" <?php if (!(strcmp("A", $valorP14[1]))) {echo "selected=\"selected\"";} ?>>Control biom&eacute;trico con entrada y salida</option>
		  </select>
		</label>
<div id="pmt1514">
<table width="100%" style="border:1px solid #666666; margin-top:5px;">
			<tr>
					<th style="color:#FFFFFF; background-color:#3399FF;" title="Periodo <?php echo $val3[0];?>">P1.<?php echo $val3[0];?></th>
					<th style="color:#FFFFFF; background-color:#3399FF;" title="Periodo <?php echo $val3[0];?>">P2.<?php echo $val3[0];?></th>
					<th style="color:#FFFFFF; background-color:#3399FF;" title="Periodo <?php echo $val3[0];?>">P3.<?php echo $val3[0];?></th>
					<th style="color:#FFFFFF; background-color:#3399FF;" title="Periodo <?php echo $val3[0];?>">P4.<?php echo $val3[0];?></th>
			</tr>
			<tr>
				<td style="border:1px solid #CCCCCC;">
					<center><input onchange="cambiarChec()" <?php echo $disabled;?> <?php if($valorP14[2]==1 ){ echo 'checked="checked"'; }?> type="checkbox" value="1" id="per_<?php echo $val3[0];?>" name="per1" class="p"/></center>
				</td>
				<td style="border:1px solid #CCCCCC;">
					<center><input onchange="cambiarChec()" <?php echo $disabled;?> <?php if($valorP14[3]==2 ){ echo 'checked="checked"'; }?> type="checkbox" value="2" id="per_<?php echo $val3[0];?>" name="per2" class="p"/></center>
				</td>
				<td style="border:1px solid #CCCCCC;">
					<center><input onchange="cambiarChec()" <?php echo $disabled;?> <?php if($valorP14[4]==3 ){ echo 'checked="checked"'; }?> type="checkbox" value="3" id="per_<?php echo $val3[0];?>" name="per3" class="p"/></center>
				</td>
				<td style="border:1px solid #CCCCCC;">
					<center><input onchange="cambiarChec()" <?php echo $disabled;?> <?php if($valorP14[5]==4 ){ echo 'checked="checked"'; }?> type="checkbox" value="4" id="per_<?php echo $val3[0];?>" name="per4" class="p"/></center>
				</td>
			</tr>
		</table>
</div>
		<?php
		break;
case 67: //forma_ing_fallas
		$arrayFecha = explode("$", $row_configuracion['conf_valor']);
		?>
		<table border="1" style="text-align: center;">
			<tr>
				<th>1&ordm; PERIODO</th><th>2&ordm; PERIODO</th><th>3&ordm; PERIODO</th><th>4&ordm; PERIODO</th>
			</tr>
			<tr>
				<td>
					<input name="P_<?php echo $row_configuracion['conf_nombre']; ?>" id="P_<?php echo $row_configuracion['conf_nombre']; ?>" type="text" size="7" readonly="readonly" value="<?php echo $arrayFecha[0]; ?>" />
		  			<button name="a_<?php echo $row_configuracion['conf_nombre']; ?>" id="a_<?php echo $row_configuracion['conf_nombre']; ?>" value="">.</button>
				</td>
				<td>
					<input name="S_<?php echo $row_configuracion['conf_nombre']; ?>" id="S_<?php echo $row_configuracion['conf_nombre']; ?>" type="text" size="7" readonly="readonly" value="<?php echo $arrayFecha[1] ?>" />
		  			<button name="b_<?php echo $row_configuracion['conf_nombre']; ?>" id="b_<?php echo $row_configuracion['conf_nombre']; ?>" value="">.</button>
				</td>
				<td>
					<input name="T_<?php echo $row_configuracion['conf_nombre']; ?>" id="T_<?php echo $row_configuracion['conf_nombre']; ?>" type="text" size="7" readonly="readonly" value="<?php echo $arrayFecha[2] ?>" />
		  			<button name="c_<?php echo $row_configuracion['conf_nombre']; ?>" id="c_<?php echo $row_configuracion['conf_nombre']; ?>" value="">.</button>
				</td>
				<td>
					<input name="C_<?php echo $row_configuracion['conf_nombre']; ?>" id="C_<?php echo $row_configuracion['conf_nombre']; ?>" type="text" size="7" readonly="readonly" value="<?php echo $arrayFecha[3] ?>" />
		  			<button name="d_<?php echo $row_configuracion['conf_nombre']; ?>" id="d_<?php echo $row_configuracion['conf_nombre']; ?>" value="">.</button>
				</td>
			</tr>
		</table>
		<?php
		break;
case 76:
	?>
		<table width="100%" style="margin-bottom: 10px;text-align: center;">
		 	<tr>
		 		<td>
		 			<select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
						<option value="S" <?php if (!(strcmp("S", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Aplica</option>
						<option value="N" <?php if (!(strcmp("N", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>No Aplica</option>
		  			</select>
		  		</td>
		  	</tr>
	  	</table>
	  	<a href="tipo_inasistencia.php" target="_blank">Ir a la edici&oacute;n del parametro</a>
	<?php
	break;
case 87:
		?>
			<label>
			  <select name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>" class="sele_mul" style="width:320px;">
			  <option value="6" <?php if (!(strcmp("6", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>No Aplica</option>
				<option value="3" <?php if (!(strcmp("3", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Registro por Biometria Control de Entrada</option>
				<option value="1" <?php if (!(strcmp("1", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Inasistencia Registrada por el Docente</option>
				<option value="2" <?php if (!(strcmp("2", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Inasistencia Registrada por Coordinaci&oacute;n de Convivencia</option>
				<option value="7" <?php if (!(strcmp("2", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Inasistencia Registrada por por el monitor de registro por grado</option>
				<option value="4" <?php if (!(strcmp("4", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Inasistencias registradas por el auxiliar de registro de inasistencias</option>
			  </select>
			</label>
		<?php
	break;
		case 111:
		?>
		<label>
		  <select  class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
			<option value="3" <?php if (!(strcmp("3", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>No aplica &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
			<option value="0" <?php if (!(strcmp("0", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Coordinador de convivencia</option>
			<option value="1" <?php if (!(strcmp("1", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Orientador (a) Escolar</option>
			<option value="2" <?php if (!(strcmp("2", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Ambos Perfiles</option>
		  </select>
		</label>
		<?php
		break;
		case 127:
		?>
		<label>
		  <select  class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
			<option value="3" <?php if (!(strcmp("3", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>No aplica &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
			<option value="0" <?php if (!(strcmp("0", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>En la planilla de 	calificaciones</option>
			<option value="1" <?php if (!(strcmp("1", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>En la planilla de inasistencia virtual</option>
			<option value="2" <?php if (!(strcmp("2", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>No registra inasistencias para el sistema</option>
		  </select>
		</label>
		<?php
		break;
case 132:
		$selGrados = "SELECT * FROM gao GROUP BY ba ORDER BY a";
		$sqlGrados = mysql_query($selGrados, $link);
		?>
	<div class="container_demohrvszv">
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd">NIVELES</div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
		<table>
			<tr><td><input type="radio" id="valida132"onclick="validar132_1()" <?php if (strpos($row_configuracion['conf_valor'],"N") == true) {echo "checked='checked'";} ?> name="valor_<?php echo $row_configuracion['conf_nombre']; ?>" value="N"> </td>
				<th style="text-align: left;">Niveles</th>
			</tr>
			<tr><td></td><td>
				<table>
			<tr><td><input type="checkbox" id="niveles_132_1"<?php if (strpos($row_configuracion['conf_valor'],"1") == true){echo "checked='checked'";} ?>   name="1_<?php echo $row_configuracion['conf_nombre']; ?>" value="1">Preescolar</td></tr>
			<tr><td><input type="checkbox" id="niveles_132_2"<?php if (strpos($row_configuracion['conf_valor'],"2") == true) {echo "checked='checked'";} ?>   name="2_<?php echo $row_configuracion['conf_nombre']; ?>"  value="2">B&aacute;sica Primaria</td></tr>
			<tr><td><input type="checkbox" id="niveles_132_3"<?php if (strpos($row_configuracion['conf_valor'],"3") == true) {echo "checked='checked'";} ?>   name="3_<?php echo $row_configuracion['conf_nombre']; ?>"  value="3">B&aacute;sica Secundaria</td></tr>
			<tr><td><input type="checkbox" id="niveles_132_4"<?php if (strpos($row_configuracion['conf_valor'],"4") == true)  {echo "checked='checked'";} ?>  name="4_<?php echo $row_configuracion['conf_nombre']; ?>"   value="4">Media Decimo</td></tr>
			<tr><td><input type="checkbox" id="niveles_132_5"<?php if (strpos($row_configuracion['conf_valor'],"5") == true)  {echo "checked='checked'";} ?>  name="5_<?php echo $row_configuracion['conf_nombre']; ?>"   value="5">Media Once</td></tr>
			<tr><td><input type="checkbox" id="niveles_132_6"<?php if (strpos($row_configuracion['conf_valor'],"6") == true)  {echo "checked='checked'";} ?>  name="6_<?php echo $row_configuracion['conf_nombre']; ?>"   value="6">Ciclos Basica Primaria</td></tr></tr>
            <tr><td><input type="checkbox" id="niveles_132_7"<?php if (strpos($row_configuracion['conf_valor'],"7") == true)  {echo "checked='checked'";} ?>  name="7_<?php echo $row_configuracion['conf_nombre']; ?>"   value="7">Ciclos Basica Secundaria</td></tr></tr>
            <tr><td><input type="checkbox" id="niveles_132_8"<?php if (strpos($row_configuracion['conf_valor'],"8") == true)  {echo "checked='checked'";} ?>  name="8_<?php echo $row_configuracion['conf_nombre']; ?>"   value="8">Ciclos Media</td></tr></tr>
			<tr><td><input type="checkbox" id="niveles_132_9"<?php if (strpos($row_configuracion['conf_valor'],"FC") == true) {echo "checked='checked'";} ?>   name="FC_<?php echo $row_configuracion['conf_nombre']; ?>"  value="FC">Formaci&oacute;n Complementaria</td></tr></table>
		</table>
</div>
</div>
</div>
</div>
</div>
	<div class="container_demohrvszv">
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd">GRADOS</div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
		<table>
			<tr><td><input type="radio" id="valida1322"onclick="validar132()" <?php if (strpos($row_configuracion['conf_valor'],"G") == true) {echo "checked='checked'";} ?> name="valor_<?php echo $row_configuracion['conf_nombre']; ?>" value="G"> </td>
				<th style="text-align: left;">Grados</th>
			</tr>
			<tr><td></td><td>
				<table><tr>
			<?php
			$i = 0;
			$q=0;
			while ($rowGrados = mysql_fetch_array($sqlGrados)) {
$q++;
				if ($i == 2){
					?>
					<td><input type="checkbox"  id="grados_132_<?php echo $q; ?>"<?php if (strpos($row_configuracion['conf_valor'],$rowGrados['ba']) == true){echo "checked='checked'";} ?>  name="grado132_<?php echo $rowGrados['a']; ?>" value="<?php echo $rowGrados['ba'];?>"><?php echo $rowGrados['ba'];?></td></tr><tr>
					<?php
					$i = 0;
				}else{
					?>
	<td><input type="checkbox" id="grados_132_<?php echo $q; ?>"<?php if (strpos($row_configuracion['conf_valor'],$rowGrados['ba']) == true){echo "checked='checked'";} ?>  name="grado132_<?php echo $rowGrados['a']; ?>" value="<?php echo $rowGrados['ba'];?>"><?php echo $rowGrados['ba'];?></td>
					<?php
					$i++;
				}
			}//luis garcia
			?>
<script type="text/javascript">
function validar132() {
    document.getElementById("niveles_132_1").checked = false;
     document.getElementById("niveles_132_2").checked = false;
      document.getElementById("niveles_132_3").checked = false;
       document.getElementById("niveles_132_4").checked = false;
        document.getElementById("niveles_132_5").checked = false;
         document.getElementById("niveles_132_6").checked = false;
          document.getElementById("niveles_132_7").checked = false;
          document.getElementById("niveles_132_8").checked = false;
          document.getElementById("niveles_132_9").checked = false;
    document.getElementById("niveles_132_1").disabled = true;
     document.getElementById("niveles_132_2").disabled = true;
      document.getElementById("niveles_132_3").disabled = true;
       document.getElementById("niveles_132_4").disabled = true;
        document.getElementById("niveles_132_5").disabled = true;
         document.getElementById("niveles_132_6").disabled = true;
         document.getElementById("niveles_132_7").disabled = true;
         document.getElementById("niveles_132_8").disabled = true;
         document.getElementById("niveles_132_9").disabled = true;
    document.getElementById("grados_132_1").disabled = false;
     document.getElementById("grados_132_2").disabled = false;
      document.getElementById("grados_132_3").disabled = false;
       document.getElementById("grados_132_4").disabled = false;
        document.getElementById("grados_132_5").disabled = false;
         document.getElementById("grados_132_6").disabled = false;
             document.getElementById("grados_132_7").disabled = false;
     document.getElementById("grados_132_8").disabled = false;
      document.getElementById("grados_132_9").disabled = false;
       document.getElementById("grados_132_10").disabled = false;
        document.getElementById("grados_132_11").disabled = false;
         document.getElementById("grados_132_12").disabled = false;
             document.getElementById("grados_132_13").disabled = false;
     document.getElementById("grados_132_14").disabled = false;
      document.getElementById("grados_132_15").disabled = false;
       document.getElementById("grados_132_16").disabled = false;
        document.getElementById("grados_132_17").disabled = false;
         document.getElementById("grados_132_18").disabled = false;
             document.getElementById("grados_132_19").disabled = false;
     document.getElementById("grados_132_20").disabled = false;
}
function validar132_1() {
        document.getElementById("niveles_132_1").disabled = false;
     document.getElementById("niveles_132_2").disabled = false;
      document.getElementById("niveles_132_3").disabled = false;
       document.getElementById("niveles_132_4").disabled = false;
        document.getElementById("niveles_132_5").disabled = false;
         document.getElementById("niveles_132_6").disabled = false;
             document.getElementById("niveles_132_7").disabled = false;
             document.getElementById("niveles_132_8").disabled = false;
             document.getElementById("niveles_132_9").disabled = false;
    document.getElementById("grados_132_1").disabled = true;
     document.getElementById("grados_132_2").disabled = true;
      document.getElementById("grados_132_3").disabled = true;
       document.getElementById("grados_132_4").disabled = true;
        document.getElementById("grados_132_5").disabled = true;
         document.getElementById("grados_132_6").disabled = true;
             document.getElementById("grados_132_7").disabled = true;
     document.getElementById("grados_132_8").disabled = true;
      document.getElementById("grados_132_9").disabled = true;
       document.getElementById("grados_132_10").disabled = true;
        document.getElementById("grados_132_11").disabled = true;
         document.getElementById("grados_132_12").disabled = true;
             document.getElementById("grados_132_13").disabled = true;
     document.getElementById("grados_132_14").disabled = true;
      document.getElementById("grados_132_15").disabled = true;
       document.getElementById("grados_132_16").disabled = true;
        document.getElementById("grados_132_17").disabled = true;
         document.getElementById("grados_132_18").disabled = true;
             document.getElementById("grados_132_19").disabled = true;
     document.getElementById("grados_132_20").disabled = true;
    document.getElementById("grados_132_1").checked = false;
     document.getElementById("grados_132_2").checked = false;
      document.getElementById("grados_132_3").checked = false;
       document.getElementById("grados_132_4").checked = false;
        document.getElementById("grados_132_5").checked = false;
         document.getElementById("grados_132_6").checked = false;
             document.getElementById("grados_132_7").checked = false;
     document.getElementById("grados_132_8").checked = false;
      document.getElementById("grados_132_9").checked = false;
       document.getElementById("grados_132_10").checked = false;
        document.getElementById("grados_132_11").checked = false;
         document.getElementById("grados_132_12").checked = false;
             document.getElementById("grados_132_13").checked = false;
     document.getElementById("grados_132_14").checked = false;
      document.getElementById("grados_132_15").checked = false;
       document.getElementById("grados_132_16").checked = false;
        document.getElementById("grados_132_17").checked = false;
         document.getElementById("grados_132_18").checked = false;
             document.getElementById("grados_132_19").checked = false;
     document.getElementById("grados_132_20").checked = false;
}
</script>
			</tr></table></tr></td></table>
			</div>
</div>
</div>
</div>
</div>
		<?php
		break;
case 141:
		$valoresP65 = explode("$",$row_configuracion['conf_valor']);
		?>
		<table>
		<tr><td><strong>Aplica</strong></td><td>
			<select id="menu141"class="sele_mul" name="1_<?php echo $row_configuracion['conf_nombre']; ?>" onclick="validar141()">
				<option value="S" <?php if (!(strcmp("S", $valoresP65[0]))) {echo "selected=\"selected\"";} ?>>Si</option>
				<option  value="N" <?php if (!(strcmp("N", $valoresP65[0]))) {echo "selected=\"selected\"";} ?>>No</option>
			</select></td>
		</tr>
		<tr>
			<th class="fila1">Injustificadas:</th>
			<td><input id="141a"type="text" onkeypress="return justNumbers(event);" class="141"name="2_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $valoresP65[1];?>" style="border-radius: 10px; width: 25%;">%</td>
		</tr>
		<tr>
			<th class="fila1">Justificadas:</th>
			<td><input id="141b"type="text" onkeypress="return justNumbers(event);" class="141"name="3_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $valoresP65[2];?>" style="border-radius: 10px; width: 25%;" >%</td>
		</tr>
		</table>
    <script>
function validar141() {
if(document.getElementById('menu141').value=="S")
{
 document.getElementById("141a").disabled = false;
document.getElementById("141b").disabled = false;
}
if(document.getElementById('menu141').value=="N")
{
 document.getElementById("141a").disabled = true;
document.getElementById("141b").disabled = true;
}
}
	addEvent('load', validar141); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
		<?php
		break;
		case 149:
		$valoresIna = explode("$",$row_configuracion['conf_valor']);
		?>
<div class="container_demohrvszv">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Si aplica defina:</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
		<p style="text-align: left; margin:15px;">
			
			<input type="radio"  <?php if (strpos($row_configuracion['conf_valor'],"1")==true) {echo "checked='checked'";} ?> value="1" name="valorRepruebaIna_<?php echo $row_configuracion['conf_nombre']; ?>"><label>El &aacute;rea va a la lista de Nivelacion fin de a&ntilde;o, mientras <b style="color:red;">EL ESTUDIANTE</b> no acumule el n&uacute;mero total de areas establecidas <b style="color:red;">POR LA INSTITUCION</b>, para la  reprobaci&oacute;n del a&ntilde;o</label><br><br>
			<input type="radio"  <?php if (strpos($row_configuracion['conf_valor'],"2")==true) {echo "checked='checked'";} ?> value="2" name="valorRepruebaIna_<?php echo $row_configuracion['conf_nombre']; ?>"><label>El estudiante va a la lista de Reprobados, sin importar cu&aacute;ntas &aacute;reas tenga acumuladas para su reprobaci&oacute;n.</label><br><br>
			<input type="radio"  <?php if (strpos($row_configuracion['conf_valor'],"3")==true) {echo "checked='checked'";} ?> value="3" name="valorRepruebaIna_<?php echo $row_configuracion['conf_nombre']; ?>"><label>El estudiante va a la planilla de reconsideraci&oacute;n fin de a&ntilde;o, mientras no acumule el total de &aacute;reas definidas POR LA INSTITUCION para considerarlo <b style="color:red;">REPROBADO</b> </label><br><br>
			<input type="radio"  <?php if (strpos($row_configuracion['conf_valor'],"4")==true) {echo "checked='checked'";} ?> value="4" name="valorRepruebaIna_<?php echo $row_configuracion['conf_nombre']; ?>"><label>El estudiante va a la planilla de reconsideraci&oacute;n fin de a&ntilde;o, sin importar el  n&uacute;mero  total de &aacute;reas reprobadas al momento del cierre de &aacute;reas.</label><br><br>
			<label>El estudiante que repruebe por inasistencia injustificada o justificada, la nota definitiva que registrar&aacute; el sistema ser&aacute; de:</label><input type="text" style="border-radius: 10px; width: 10%;" name="inasistencia_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $valoresIna[2];?>">  
		</p></div></div></div></div></div>
		<? 
		break;
case 163:
		$estado = '';
				if(strpos($row_configuracion['conf_valor'],"$")>0)
				{
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
					$estado = $array_parametro[1];
				}
				else
					$parametro = $row_configuracion['conf_valor'];
				?>
			 <table  width="90%" >
				 <tr>
				 <td><b><strong>Aplica</strong></b>
				 <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
				  	<a href="biometrico_control.php" target="_blank" style="color:#3399FF">Ir a control biometrico</a>
				  </td>
				 </tr>
			</table>
<?php
		break;
case 236:
		$reasignacion2_ = explode("$",$row_configuracion['conf_valor']);
	// echo "<script>alert('".$row_configuracion['conf_valor']."')</script>";
		?>
	<div class="container_demohrvszv">
	<div class="accordion_example2wqzx">
	<div class="accordion_inwerds">
	<div class="acc_headerfgd"><strong>&Iacute;tem</strong></div>
	<div class="acc_contentsaponk">
	<div class="grevdaiolxx">
		<table>
		<p style="text-align: left; margin:15px;">
		<input type="radio" onclick="javascript:determinarEstadoCampos170();"<?php if (strpos($row_configuracion['conf_valor'],"1")==true) {echo "checked='checked'";} ?> value="1" name="reasignacion_<?php echo $row_configuracion['conf_nombre']; ?>" /> Retirado <br>
		<input type="radio" onclick="javascript:determinarEstadoCampos170();" <?php if (strpos($row_configuracion['conf_valor'],"2")==true) {echo "checked='checked'";} ?> value="2" name="reasignacion_<?php echo $row_configuracion['conf_nombre']; ?>" />Traslado<br>
		<input type="radio" onclick="javascript:determinarEstadoCampos170();"<?php if (strpos($row_configuracion['conf_valor'],"3")==true) {echo "checked='checked'";} ?> value="3" name="reasignacion_<?php echo $row_configuracion['conf_nombre']; ?>" />Deserto<br>
        <input type="radio" onclick="javascript:determinarEstadoCampos170();"<?php if (strpos($row_configuracion['conf_valor'],"4")==true) {echo "checked='checked'";} ?> value="4" name="reasignacion_<?php echo $row_configuracion['conf_nombre']; ?>" />No Esta Asistiendo<br> <br>
        Numero de Dias Consecutivos : <input type="text" class="p119" onkeypress="return justNumbers(event);" style="border-radius: 10px; width: 10%;" name="reasignacion2_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $reasignacion2_[2];?>"> <br>
		</p>
		</table>
		</div>
</div>
</div>
</div>
</div>
<script type="text/javascript">
function determinarEstadoCampos170(){
				const AREAS_ESPECIFICAS = "4"; // determina el valor de la opcion que se debe elegir para activar los campos que este caso es areas especificas en la solucion del parametro 159
				// obtiene el conjunto de input type radio que contienen las diferentes opciones de la seccion de areas a tener en cuenta de la solucion del parametro 159
				var opciones = document.getElementsByName( "reasignacion_<?php echo $row_configuracion['conf_nombre']; ?>" );
				for( var i = 0; i < opciones.length; i++ ){ // recorro el conjunto de input type radio que contienen las opciones
					if(opciones[i].checked == true){ // determino cual opcion esta seleccionada
						campos = document.getElementsByName("reasignacion2_<?php echo $row_configuracion['conf_nombre']; ?>"); // obtengo el conjunto de los 12 campos de las asignaturas especificas
 // termino else
					} // termino if
				} // termino for
</script>
		<?php
		break;		}// este es el fin
?>
	</td>
</tr>
<?php
}while($row_configuracion = mysql_fetch_assoc($configuracion));
?>
<?php
///Instanciacion de clases para el llamado de los parametros
// include 'ParametrosGenerales.php';
$objetoParametros3=new ParametrosGenerales();  ///Instancio el Objeto ParametroGenerales
$conexionBd=$objetoParametros3->creaConexion();  ///llamo la funcion creaConexion
$numPersiana=3;
$cantidadParametros=$objetoParametros3 -> returnCantidadParametrosPersiana($conexionBd,$numPersiana); //Retorna la cantidad de parametros que hay dentro de la persiana
$cant=0;
while($cant<$cantidadParametros)  //Mientras haya Parametros Entre al while
{
	 	$valor_id_parametro_individual=$objetoParametros3  -> devuelvaParametros($cant);
       $objetoParametros3->ejecutarImpresionParametros($numPersiana,$conexionBd,$consecutivo,$valor_id_parametro_individual ); //Ejecuta la impresion de los paramertros individualmente
       $consecutivo=$objetoParametros3->returnConsecutivo();    //Retorna el consecutivo
        $informacionDelParametro=$objetoParametros3  ->informacionParametro($conexionBd,$valor_id_parametro_individual);  //Informacion del parametro 
       $cant++;
       // switch($valor_id_parametro_individual)
       // {

       // }
}
?>
</table>
</div>
</div>
</div>
</div>
</div>
<?php
// esta es la tabla 2
if($totalRows_configuracion)
{
	mysql_data_seek($configuracion,0);
mysql_select_db($database_sygescol, $sygescol);
	$query_configuracion = "SELECT conf_sygescol.conf_id, conf_sygescol.conf_nombre, conf_sygescol.conf_valor, conf_sygescol.conf_descri, conf_sygescol.conf_nom_ver, encabezado_parametros.titulo
								FROM conf_sygescol  INNER JOIN encabezado_parametros on encabezado_parametros.id_param=conf_sygescol.conf_id
							WHERE conf_sygescol.conf_estado = 0
								AND conf_sygescol.conf_id IN (56,71,88,89,92,94,97,110,102,117,154,157,114,235,257)  ORDER BY encabezado_parametros.id_orden ";
	$configuracion = mysql_query($query_configuracion, $sygescol) or die(mysql_error());
	$row_configuracion = mysql_fetch_assoc($configuracion);
// aca inicia la otra tabla
}?>
<?php
include ("conb.php");$registrosd=mysqli_query($conexion,"select * from conf_sygescol_adic where id=4")or die("Problemas en la Consulta".mysqli_error());while ($regd=mysqli_fetch_array($registrosd)){$coloracordd=$regd['valor'];}
?>
<div class="container_demohrvszv_caja_1">
		<div class="accordion_example2wqzx_caja_2">
			<div class="accordion_inwerds_caja_3">
				<div class="acc_headerfgd_caja_titulo" id="parametros_control_calificaciones" style="background-color: <?php echo $coloracordd ?>"><center><strong>4. PAR&Aacute;METROS PARA EL CONTROL Y REGISTRO DE CALIFICACIONES</strong></center><br /><center><input type="radio" value="rojod" name="coloresd">Si&nbsp;&nbsp;<input type="radio" value="naranjad" name="coloresd">No</div></center>
				<div class="acc_contentsaponk_caja_4">
					<div class="grevdaiolxx_caja_5">
					<table  align="center" width="85%" class="centro" cellpadding="10" class="formulario"  border="1">
<tr>
	<th class="formulario"  style="background: #3399FF;" >Consecutivo </th>
	<th class="formulario"  style="background: #3399FF;" >Id Par&aacute;metro </th>
	<th class="formulario" >Tipo de Par&aacute;metro</th>
    <th class="formulario" >Detalle del Par&aacute;metro</th>
	<th class="formulario">Selecci&oacute;n</th>
	</tr>
	<?php
	do
	{
		$consecutivo++;
	?>
	<td class="formulario"   id="Consecutivo<?php echo $consecutivo; ?>" ><strong> <?php echo $consecutivo; ?></strong></td>
	<td class="formulario"  id="Parametro<?php echo $row_configuracion['conf_id']; ?>" ><strong><?php echo $row_configuracion['conf_id'] ?></strong></td>
<td valign="top"><strong>
<div class="container_demohrvszv_caja_tipo_param">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Tipo de Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx_caja_tipo_param">
<div id="conf_nom_ver">
<div  class="textarea "  align="justify" id="conf_nom_ver-<?php echo $row_configuracion['conf_id'] ?>"><?php echo $row_configuracion['conf_nom_ver']; ?></div>
</div></div></div></div></div></div>
</strong>
</td>      <td valign="top" width="80%">
     <div class="container_demohrvszv" >
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd"  id="cgasvf"><strong>Detalle Par&aacute;metro</strong></div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
<div id="conf_descri">
      <div  align="justify" class="textarea " id="conf_descri-<?php echo $row_configuracion['conf_id'] ?>" style="width: 90%;" align="justify">
      <?php echo $row_configuracion['conf_descri']; ?>
      </div>
</div>
					</div>
				</div>
			</div>
		</div>
</div>
 </td>
<td align="center">
<?php
switch($row_configuracion['conf_id'])
{
	case 114:
				$estado = '';
				if(strpos($row_configuracion['conf_valor'],"$")>0)
				{
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
					$estado = $array_parametro[1];
				}
				else
					$parametro = $row_configuracion['conf_valor'];
				?>
			 <table  width="90%" >
				 <tr>
				 <td><b>Aplica</b>
				 <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
				  </td>
				 </tr>
			</table>
					<?php
		break;
//* ------------------ 114  -----------------------------------
case 56: //Ingresar notas despues del cierre de areas
		?>
		<label>
		  Aplica<select class="sele_mul"  name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
			<option value="S" <?php if (!(strcmp("S", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Si</option>
			<option value="N" <?php if (!(strcmp("N", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>No</option>
		  </select>
		</label>
		<?php
		break;
		case 71: //Ingresar notas despues del cierre de areas
			
			$row_configuracion['conf_valor'] = trim($row_configuracion['conf_valor'], "-");
			$valores = explode("@", $row_configuracion['conf_valor']);
			$val2 = explode("-", $valores[1]);
		?>
		<script language="javascript">
			function trim(stringToTrim) {
				return stringToTrim.replace(/^\s+|\s+$/g,"");
			}
			function cambiarContenido(valor){
				if(valor == 1){
					var checkbox = $('valores').value;
					var elementos = checkbox.split(",");
					var arrays = '';
					for(i=0; i<elementos.length; i++){
						$('per_' + elementos[i]).checked=0;
						$('per_' + elementos[i]).disabled=true;
						arrays += elementos[i] + '/' + '1-';
					}
					$('<?php echo $row_configuracion['conf_nombre']; ?>').value= valor + '@' + arrays;
				}else{
					var checkbox = $('valores').value;
					var elementos = checkbox.split(",");
					var arrays = '';
					for(i=0; i<elementos.length; i++){
						$('per_' + elementos[i]).disabled=false;
						arrays += elementos[i] + '/' + '1-';
					}
					$('<?php echo $row_configuracion['conf_nombre']; ?>').value= valor + '@' + arrays;
				}
			}
			function cambiarChec(){
				var checkbox = $('valores').value;
				var elementos = checkbox.split(",");
				var arrays = '';
				for(i=0; i<elementos.length; i++){
					if($('per_' + elementos[i]).checked == 0){
						arrays += elementos[i] + '/' + '1-';
					}else{
						arrays += elementos[i] + '/' + '2-';
					}
				}
				$('<?php echo $row_configuracion['conf_nombre']; ?>').value= $('PlanillaVer').value + '@' + arrays;
			}
		</script>
		<center>Aplica: <select class="sele_mul" name="PlanillaVer" id="PlanillaVer" onclick="validar8()" onchange="cambiarContenido(this.value)">
			<option <?php if($valores[0]==1){ echo 'selected="selected"';}?> value="1">No</option>
			<option <?php if($valores[0]==2){ echo 'selected="selected"';}?> value="2">Si</option>
		</select></center>
		<table width="100%" style="border:1px solid #666666; margin-top:5px;">
			<tr>
				<?php for($i=0; $i<count($val2); $i++){
					$val3 = explode("/", $val2[$i]);
				?>
					<th style="color:#FFFFFF; background-color:#3399FF;" title="Periodo <?php echo $val3[0];?>">P.<?php echo $val3[0];?></th>
				<?php } ?>
			</tr>
			<script>
function validar8() {
if(document.getElementById('PlanillaVer').value=="2")
{
document.getElementById("per_<?php echo $val3[0];?>").disabled = false;
}
if(document.getElementById('PlanillaVer').value=="1")
{
document.getElementById("per_<?php echo $val3[0];?>").disabled = true;
}
}
  function validar8alcargar(){
        const en_las_areas_de = "2"; // determinia el valor del input type radio que representa la opcion de areas epecificas
        var opcion = "PlanillaVer"; // obtengo el valor guardado en la BD que determina la opcion que fue seleccionada y guardad
        var campos = document.getElementsByClassName("p"); // obtengo el conjunto de los 12 campos de las areas especificas
        if (opcion == en_las_areas_de){ // si el valor traido es igual al valor que identifica la opcion areas especificas
          setDisabledCampos(campos, false); // activo los 12 campos
        }else{ // si el valor tradio es diferente fue porque se selecciono otra opcion diferente a areas especificas
          setDisabledCampos(campos, true); // desactivo los 12 campos
        }
      }
</script>
			<tr>
				<?php
				$valor2='';
				$disabled='';
				if($valores[0]==1){
					$disabled='disabled="disabled"';
				}
				for($i=0; $i<count($val2); $i++){
					$val3 = explode("/", $val2[$i]);
					$valor2.=$val3[0].',';
				?>
				<td style="border:1px solid #CCCCCC;">
					<input onchange="cambiarChec()" <?php echo $disabled;?> <?php if($val3[1]==2 and $disabled==''){ echo 'checked="checked"'; }?> type="checkbox" value="<?php echo $val3[0];?>" id="per_<?php echo $val3[0];?>" name="per_<?php echo $val3[0];?>" class="p"/>
				</td>
				<?php } ?>
			</tr>
		</table>
		<input type="hidden" name="valores" id="valores" value="<?php echo trim($valor2, ",");?>" />
		<input name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>" type="hidden" size="12" value="<?php echo $row_configuracion['conf_valor']; ?>" />
		 <?php
		break;
case 88:
		$row_configuracion['conf_valor'] = trim($row_configuracion['conf_valor'], "-");
		$select = explode("@", $row_configuracion['conf_valor']);
		$valores = explode("-", $select[0]);
		$val2 = explode("-", $select[1]);

		?>
		<script language="javascript">
			function trim(stringToTrim) {
				return stringToTrim.replace(/^\s+|\s+$/g,"");
			}
			function cambiarContenido2(valor){
				var habiten = $("habilitar_en").value;
				if(valor == 1){
					var checkbox = $('valoresindice').value;
					var elementos = checkbox.split(",");
					$("habilitar_en").disabled=true;
					var arrays = '';
					for(i=0; i<elementos.length; i++){
						$('per_indice_' + elementos[i]).value="";
						$('per_indice_' + elementos[i]).disabled=true;
						arrays += elementos[i] + '/' + $('per_indice_' + elementos[i]).value +'-';
					}
					$('<?php echo $row_configuracion['conf_nombre']; ?>').value= valor + "-" + habiten + '@' + arrays;
				}else{
					var checkbox = $('valoresindice').value;
					var elementos = checkbox.split(",");
					$("habilitar_en").disabled=false;
					var arrays = '';
					for(i=0; i<elementos.length; i++){
						$('per_indice_' + elementos[i]).value = ""
						$('per_indice_' + elementos[i]).disabled=false;
						arrays += elementos[i] + '/' + $('per_indice_' + elementos[i]).value + '-';
					}
					$('<?php echo $row_configuracion['conf_nombre']; ?>').value= valor + "-" + habiten + '@' + arrays;
				}
			}
			function cambiarDatos(){
				var checkbox = $('valoresindice').value;
				var habiten = $("habilitar_en").value;
				var elementos = checkbox.split(",");
				var arrays = '';
				for(i=0; i<elementos.length; i++){
					//if($('per_' + elementos[i]).checked == 0){
						arrays += elementos[i] + '/' + $('per_indice_' + elementos[i]).value + '-';
					//}else{
					//	arrays += elementos[i] + '/' + '2-';
					//}
				}
				$('<?php echo $row_configuracion['conf_nombre']; ?>').value= $('controlindice').value + "-" + habiten + '@' + arrays;
			}
		</script>
		<?php
		$valor2='';
		$disabled='';
		if($valores[0]==1){
			$disabled='disabled="disabled"';
		}
		?>
		<center>
		<strong>Habilitar</strong>
		<select class="sele_mul" name="controlindice" id="controlindice" onchange="cambiarContenido2(this.value)">
			<option <?php if($valores[0]==1){ echo 'selected="selected"';}?> value="1">No</option>
			<option <?php if($valores[0]==2){ echo 'selected="selected"';}?> value="2">Si</option>
	</select>
	<br />
	<nobr><strong>En la Planilla de :</strong>
	<select class="sele_mul" name="habilitar_en" id="habilitar_en" <?php echo $disabled;?>  onchange="cambiarDatos()">
		<option <?php if($valores[1]==1){ echo 'selected="selected"';}?> value="1">Calificaciones</option>
		<option <?php if($valores[1]==2){ echo 'selected="selected"';}?> value="2"><?php echo $_SESSION['PLANILLA'][ 10 ]['NOMBRE_VER'];?> por Periodos</option>
	</select>
	</nobr>
	</center>
	<table width="100%" style="border:1px solid #666666; margin-top:5px;">
		<tr>
			<?php for($i=0; $i<count($val2); $i++){
				$val3 = explode("/", $val2[$i]);
			?>
					<th style="color:#FFFFFF; background-color:#3399FF;" title="Periodo <?php echo $val3[0];?>">P.<?php echo $val3[0];?></th>
			<?php } ?>
		</tr>
		<tr>
			<?php
			for($i=0; $i<count($val2); $i++){
				$val3 = explode("/", $val2[$i]);
				$valor2.=$val3[0].',';
			?>
			<td style="border:1px solid #CCCCCC;">
				<input onkeypress="return justNumbers(event);"style="border-radius: 10px;"<?php echo $disabled;?> type="text"  onchange="cambiarDatos()" size="4" value="<?php echo $val3[1];?>" id="per_indice_<?php echo $val3[0];?>" name="per_indice_<?php echo $val3[0];?>" />%
			</td>
			<?php } ?>
		</tr>
	</table>
	<input type="hidden"  onchange="cambiarDatos()" name="valoresindice" id="valoresindice" value="<?php echo trim($valor2, ",");?>" />
	<input name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>" type="hidden"  onchange="cambiarDatos()" size="12" value="<?php echo $row_configuracion['conf_valor']; ?>" />
 <?php
break;
case 89: //Modulo Homologacion Matricula Extraordinaria
?>
<?php
$estado = '';
if(strpos($row_configuracion['conf_valor'],"$")>0)
{
	$array_parametro = explode("$",$row_configuracion['conf_valor']);
	$parametro = $array_parametro[0];
	$estado = $array_parametro[1];
}
else
$parametro = $row_configuracion['conf_valor'];
?>
<table  width="90%" >
<tr>
<th><b>Aplica</b></th>
<td>
<select style="width:420px;" class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
		<option value="0" <?php if (!(strcmp("0", $parametro))) {echo "selected=\"selected\"";} ?>>Seleccione uno... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
		<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Las asignaturas con calificacion <b>DESEMPE&Ntilde;O BAJO,</b><b style="background:red;">SI</b> seran homologadas en el proceso,<b style="background:red;">Y NO</b> seran remitidas al docente responsable.</option>
		<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>Las asignaturas con calificacion <b>DESEMPE&Ntilde;O BAJO,</b><b style="background:red;">NO</b> seran homologadas en el proceso,<b style="background:red;">Y SI</b> seran remitidas al docente responsable para su correspondiente nivelacion.</option>
		<option value="L" <?php if (!(strcmp("L", $parametro))) {echo "selected=\"selected\"";} ?>>Este parametro <b>NO</b> aplica para la institucion educativa.</option>
</select>
</td>
</tr>
</table>
<?php
break;
/*-----------------------------------------------------------------------------------------CASO 92---------------------------------------------*/
	case 92: //Ingresar notas despues del cierre de areas
		?>			<?php
			$estado = '';
			if(strpos($row_configuracion['conf_valor'],"$")>0)
			{
				$array_parametro = explode("$",$row_configuracion['conf_valor']);
				$parametro = $array_parametro[0];
				$areasObligatorias = $array_parametro[1];
				$areasTecnicas = $array_parametro[2];
			}
			else
				$parametro = $row_configuracion['conf_valor'];
		?>
		<br><br>
		<table >
	        		<tr><b>Aplica</b>
				  	  <select class="sele_mul op" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>" onclick="validar92()">
							<option value="S" <?php if (!(strcmp("S", $parametro['conf_valor']))) {echo "selected=\"selected\"";} ?>>Si</option>
							<option value="N" <?php if (!(strcmp("N", $parametro['conf_valor']))) {echo "selected=\"selected\"";} ?>>No</option>
						  </select>
                	</tr>
</div>
</div>
</div>
</div>
	</div>
<script>
function validar92(){
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="S")
{
 document.getElementById("parametro92_1").disabled = false;
document.getElementById("parametro92_2").disabled = false;
}
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="N")
{
 document.getElementById("parametro92_1").disabled = true;
document.getElementById("parametro92_2").disabled = true;
}
}
	addEvent('load', validar92); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
		<br>
		<br>
        <br><hr>
		<br>
		<td>
<div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
     <div class="accordion_inwerds">
        <div class="acc_headerfgd"><strong>&Iacute;tem</strong> </div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
			<table  border="1" style="border:1px solid black">
			<tr >
				<td style="text-align:center;">Autoevaluaci&oacute;n</td>
				<td width="90%"><input type="text"style="width:94%;" id="parametro92_1"value="<?php echo $areasObligatorias; ?>" name="areas_Obligatorias92_" class="parametro4"></td>
			</tr>
			<tr>
				<td style="text-align:center;">Coevaluacion</td>
				<td width="90%"><input type="text"style="width:94%;" id="parametro92_2"value="<?php echo $areasTecnicas; ?>" name="areas_Tecnicas92_" class="parametro4"></td>
			</tr>
		</table>
	</td>
		</table>
		<?php
		break;
case 94:
$estado = '';
$array_parametro = explode(",",$row_configuracion['conf_valor']);
$parametro = $array_parametro[0];
?>
		<b>Aplica</b>
				 <select class="sele_mul" onclick="validar1999()"name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if ($parametro == 'S') {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if ($parametro == 'N') {echo "selected=\"selected\"";} ?>>No</option>
				 </select>
<div class="container_demohrvszv">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Si aplica defina:</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
		<table>
                 <tr>
			 		<td><input id="p19_1"type="radio"  <?php if (strpos($row_configuracion['conf_valor'],"0") == true) {echo "checked='checked'";} ?> value="0" name="pla0"></td>
				 	<td>Plantilla Carga masiva para descriptores de Competencias</td>
				 </tr>
 <tr>
<td>
<b>&Oacute;</b>
</td>
				 </tr>
				 <tr>
				 	<td><input id="p19_2"type="radio"  <?php if (strpos($row_configuracion['conf_valor'],"1") == true) {echo "checked='checked'";} ?> value="1" name="pla0"></td>
				 	<td>Plantilla Carga masiva para descriptores de Logros</td>
				 </tr>
				 <tr>
				 	<td><input id="p19_3"type="checkbox"  <?php if (strpos($row_configuracion['conf_valor'],"2") == true) {echo "checked='checked'";} ?> value="2" name="pla2"></td>
				 	<td>Plantilla Carga masiva para descriptores de Dimensiones de la Competencia</td>
				 </tr>
				 <tr>
				 	<td><input id="p19_4"type="checkbox"  <?php if (strpos($row_configuracion['conf_valor'],"3") == true) {echo "checked='checked'";} ?> value="3" name="pla3"></td>
				 	<td>Plantilla Carga masiva para descriptores de Indicadores de Logro</td>
				 </tr>
				 <tr>
				 	<td><input id="p19_5"type="checkbox" <?php if (strpos($row_configuracion['conf_valor'],"4") == true) {echo "checked='checked'";} ?> value="4" name="pla4"></td>
				 	<td>Plantilla Carga masiva para descriptores de Fortalezas, Debilidades y Recomendaciones</td>
				 </tr>
				 <tr>
				 	<td><input id="p19_6"type="checkbox"  <?php if (strpos($row_configuracion['conf_valor'],"5") == true) {echo "checked='checked'";} ?> value="5" name="pla5"></td>
				 	<td>Plantilla Carga masiva para descriptores de Comportamiento</td>
				 </tr>
				 <tr>
				 	<td><input id="p19_7"type="checkbox"<?php if (strpos($row_configuracion['conf_valor'],"6") == true) {echo "checked='checked'";} ?> value="6" name="pla6"></td>
				 	<td>Plantilla Carga masiva para descriptores de Desempe&ntilde;os de Preescolar</td>
				 </tr>
				  <tr>
				 	<td><input id="p19_8"type="checkbox"<?php if (strpos($row_configuracion['conf_valor'],"7") == true) {echo "checked='checked'";} ?> value="7" name="pla7"></td>
				 	<td id="p19_9">Plantilla Carga masiva para cargar el RUM (Registro &Uacute;nico de Matr&iacute;cula)</td>
				 </tr>
			</table>
</div>
</div>
</div>
</div>
</div>
<script>
function validar1999() {
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="S")
{
 document.getElementById("p19_1").disabled = false;
  document.getElementById("p19_2").disabled = false;
   document.getElementById("p19_3").disabled = false;
    document.getElementById("p19_4").disabled = false;
     document.getElementById("p19_5").disabled = false;
      document.getElementById("p19_6").disabled = false;
       document.getElementById("p19_7").disabled = false;
       document.getElementById("p19_8").disabled = false;
         document.getElementById("p19_9").disabled = false;
}
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="N")
{
  document.getElementById("p19_1").disabled = true;
  document.getElementById("p19_2").disabled = true;
   document.getElementById("p19_3").disabled = true;
    document.getElementById("p19_4").disabled = true;
     document.getElementById("p19_5").disabled = true;
      document.getElementById("p19_6").disabled = true;
       document.getElementById("p19_7").disabled = true;
        document.getElementById("p19_8").disabled = true;
         document.getElementById("p19_9").disabled = true;
}
}
addEvent('load', validar1999); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
<script type="text/javascript">
	function validar12494(){
		if(document.getElementById('p124_1').checked==true)
		{
        document.getElementById("p19_8").style.display = 'none';
                document.getElementById("p19_9").style.display = 'none';
		}
if(document.getElementById('p124_2').checked==true){
        document.getElementById("p19_8").style.display = 'inline';
                document.getElementById("p19_9").style.display = 'inline';
		}
	}
	addEvent('load', validar12494); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
<?php
break;
/*-----------------------------------------------------------------------------------------CASO 97---------------------------------------------*/
	case 97:
				$estado = '';
				if(strpos($row_configuracion['conf_valor'],"$")>0)
				{
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
					$estado = $array_parametro[1];
				}
				else
					$parametro = $row_configuracion['conf_valor'];
				?>
			 <table  width="90%" >
				 <tr>
				 <td><b>Aplica</b>
				 <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
				  </td>
				 </tr>
			</table>
					<?php
		break;
/*-----------------------------------------------------------------------------------------CASO 110---------------------------------------------*/
case 110:
				$estado = '';
				if(strpos($row_configuracion['conf_valor'],"$")>0)
				{
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
					$estado = $array_parametro[1];
				}
				else
					$parametro = $row_configuracion['conf_valor'];
				?>
			 <table  width="90%" >
				 <tr>
				 <td><b>Aplica</b>
				 <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
				  </td>
				 </tr>
			</table>
					<?php
		break;
/*-----------------------------------------------------------------------------------------CASO 102---------------------------------------------*/
case 117:
		$valoresP43 = explode("$",$row_configuracion['conf_valor']);
            $e1111= $valoresP43[5];
//print_r($valoresP43);
$query_escala_asd = "SELECT conf_valor FROM conf_sygescol where conf_nombre like 'planilla_pgu'";
$escala_asd = mysql_query($query_escala_asd, $link) or die(mysql_error());
$row_escala_asd = mysql_fetch_array($escala_asd);
		?>
			 <table  width="90%">
				 <tr>
				 <td><b>Aplica</b>
				<select class="sele_mul" onclick="validar117();validar102117()"name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
<?php
$sel_planilla = "SELECT config_planilla.* FROM config_planilla WHERE config_planilla.conf_pla_id = 9";
$sql_planilla = mysql_query($sel_planilla, $link)or die("No se pudo consultar los datos");
$row_planilla = mysql_fetch_array($sql_planilla);
if ($row_escala_asd[conf_valor]=='N'){?>
<option value="NO" <?php if (!(strcmp("N", $valoresP43[0]))) {echo "selected=\"selected\"";} ?>>No</option>
<?php
}
elseif ($row_planilla['conf_pla_valor'] > 1)
{?>
<option value="S" <?php if (!(strcmp("S", $valoresP43[0]))) {echo "selected=\"selected\"";} ?>>Si</option>
<?php
}
else
{?>
<option value="S" <?php if (!(strcmp("S", $valoresP43[0]))) {echo "selected=\"selected\"";} ?>>Si</option>
<option value="N" <?php if (!(strcmp("N", $valoresP43[0]))) {echo "selected=\"selected\"";} ?>>No</option>
<?php
}
?>	</select>
				 </td>
				 <td style="text-align: center;">
				  </td>
				 </tr>
</table>
<div id="mostrarparametro117">
<b>Este parametro no esta habilitado en los procesos de evaluacion</b>
</div>
<div id="parametro38117">
				 		<div class="container_demohrvszv">
		<!-- Accordion begin -->
		<div class="accordion_example2wqzx">
			<!-- Section 1 -->
			<div class="accordion_inwerds">
				<div class="acc_headerfgd">Si Aplica defina:</div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
<center>
				 	<table style="text-align: left;" >
				 		<tr style="text-align:center;">
<!-- INICIO PHP MIGUEL Y EDICION DE CODIGO MIGUEL-->
<?php
mysql_select_db($database_sygescol, $sygescol);
$query_escala_nacional = "SELECT escala_nacional.esca_nac_max FROM escala_nacional";
$escala_nacional = mysql_query($query_escala_nacional, $sygescol) or die(mysql_error());
$row_escala_nacional = mysql_fetch_assoc($escala_nacional);
$query_escala_nacional_minima = "SELECT escala_nacional.esca_nac_min FROM escala_nacional WHERE escala_nacional.esca_nac_id = '4'";
$escala_nacional_minima = mysql_query($query_escala_nacional_minima, $sygescol) or die(mysql_error());
$row_escala_nacional_minima = mysql_fetch_assoc($escala_nacional_minima);
?>
<!-- FIN PHP MIGUEL -->
				 			<th>Desempe&ntilde;os</th>
				 		</tr>
				<tr><td><input id="p117"type="checkbox" onchange="abilitarCamposPromedio('p117', 'p1018')" name="cS_<?php echo $row_configuracion['conf_nombre'];?>"  <?php if ($valoresP43[1]=="S")  {echo "checked='checked'";} ?>  value="S"> Superior</td>
						<td class="izq"> Nota:<input  id="p1018" title="DEBE INSERTAR NUMEROS DECIMALES" maxlength="3" onkeyup="validaFloatMAC(this, '1','<?php echo $row_escala_nacional_minima["esca_nac_min"] ?>','<?php echo $row_escala_nacional["esca_nac_max"] ?>');insertar_nota_mac(this)" onchange="validaFloatMAC(this, '1','<?php echo $row_escala_nacional_minima["esca_nac_min"] ?>','<?php echo $row_escala_nacional["esca_nac_max"] ?>');insertar_nota_mac(this)" step="0.1" max="5.0" class="determinarcampo1177" onkeypress="return justNumbers(event);"style="width:15%;"  name="cSs_<?php echo $row_configuracion['conf_nombre'];?>" value="<?php echo $valoresP43[5]; ?>" <?php if ($valoresP43[1]=="S")  {echo "";} else {echo"disabled='disabled'";} ?><?php if ($valoresP43[5]!="")  {echo "readonly='readonly'";} else {echo"";} ?> ></td>
				 		</tr>
				 		<tr><td><input id="p119"type="checkbox" onChange="abilitarCamposPromedio('p119', 'p120')" name="cA_<?php echo $row_configuracion['conf_nombre'];?>"  <?php if ($valoresP43[2]=="A")  {echo "checked='checked'";} ?>  value="A"> Alto</td>
						<td class="izq"> Nota:<input  id="p120" title="DEBE INSERTAR NUMEROS DECIMALES" maxlength="3" onkeyup="validaFloatMAC(this, '1','<?php echo $row_escala_nacional_minima["esca_nac_min"] ?>','<?php echo $row_escala_nacional["esca_nac_max"] ?>')" onchange="validaFloatMAC(this, '1','<?php echo $row_escala_nacional_minima["esca_nac_min"] ?>','<?php echo $row_escala_nacional["esca_nac_max"] ?>')" step="0.1" max="5.0" class="determinarcampo1177"onkeypress="return justNumbers(event);"style="width:15%;" name="cAa_<?php echo $row_configuracion['conf_nombre'];?>" value="<?php echo $valoresP43[6]; ?>" <?php if ($valoresP43[2]=="A")  {echo "";} else {echo"disabled='disabled'";} ?> <?php if ($valoresP43[6]!="")  {echo "readonly='readonly'";} else {echo"";} ?>></td>
				 		</tr>
				 		<tr><td><input id="p121"type="checkbox" onChange="abilitarCamposPromedio('p121', 'p122')" name="cBs_<?php echo $row_configuracion['conf_nombre'];?>" <?php if ($valoresP43[3]=="Bs") {echo "checked='checked'";} ?>  value="Bs"> Basico</td>
						<td class="izq"> Nota:<input id="p122" title="DEBE INSERTAR NUMEROS DECIMALES" maxlength="3" onkeyup="validaFloatMAC(this, '1','<?php echo $row_escala_nacional_minima["esca_nac_min"] ?>','<?php echo $row_escala_nacional["esca_nac_max"] ?>')" onchange="validaFloatMAC(this, '1','<?php echo $row_escala_nacional_minima["esca_nac_min"] ?>','<?php echo $row_escala_nacional["esca_nac_max"] ?>')" step="0.1" max="5.0" class="determinarcampo1177"onkeypress="return justNumbers(event);"style="width:15%;" name="cBsb_<?php echo $row_configuracion['conf_nombre'];?>" value="<?php echo $valoresP43[7]; ?>" <?php if ($valoresP43[3]=="Bs")  {echo "";} else {echo"disabled='disabled'";} ?> <?php if ($valoresP43[7]!="")  {echo "readonly='readonly'";} else {echo"";} ?>></td>
				 		</tr>
				 		<tr><td><input id="p123"type="checkbox" onChange="abilitarCamposPromedio('p123', 'p124')" name="cBj_<?php echo $row_configuracion['conf_nombre'];?>" <?php if ($valoresP43[4]=="Bj") {echo "checked='checked'";} ?>  value="Bj"> Bajo</td>
				 		<td class="izq"> Nota:<input id="p124" title="DEBE INSERTAR NUMEROS DECIMALES" maxlength="3" onkeyup="validaFloatMAC(this, '1.0','<?php echo $row_escala_nacional_minima["esca_nac_min"] ?>','<?php echo $row_escala_nacional["esca_nac_max"] ?>')" onchange="validaFloatMAC(this, '1','<?php echo $row_escala_nacional_minima["esca_nac_min"] ?>','<?php echo $row_escala_nacional["esca_nac_max"] ?>')" step="0.1" max="5.0" class="determinarcampo1177"onkeypress="return justNumbers(event);"style="width:15%;" name="cBjj_<?php echo $row_configuracion['conf_nombre'];?>" value="<?php echo $valoresP43[8]; ?>" <?php if ($valoresP43[4]=="Bj")  {echo "";} else {echo"disabled='disabled'";} ?> <?php if ($valoresP43[8]!="")  {echo "readonly='readonly'";} else {echo"";} ?>></td>   </tr>
				 		<tr><td><input id="pgul1"type="checkbox" name="pgul1" onclick="validar117244()"<?php if ($valoresP43[9]=="pgul1") {echo "checked='checked'";} ?>  value="pgul1"></td>
				 		<td>No permitir valores por debajo de la nota definitiva <hr /></td>   </tr>
				 	</table>
				 	</center>
				 					 		 </div>
</div>
</div>
</div>
</div>
<style>
td.izq {
    width: 400px;
    padding-left: 100px;
}
</style>
<script>
function validaFloatMAC(campo, decimales)
{
var numero = campo.value;
	longitud = numero.length;
	nota_max = parseFloat(<?php echo $row_escala_nacional["esca_nac_max"];?>);
	nota_min = parseFloat(<?php echo $row_escala_nacional_minima['esca_nac_min'];?>);
	if (nota_min=="0")
	{
	nota_min="0.1";
	}
	//Validación de que sea un numero float
	if (!/^([1-9])*[.]?[1-9]*$/.test(numero) || parseFloat(numero) > parseFloat(nota_max) || numero < parseFloat(nota_min))
	{
		campo.value = numero.substring(0,longitud - 1);
		campo.focus();
	}
	//hay que guardarla en conf_sygescol
	//Validamos que no sobrepase la cantidad de decimales establecidos
	arreglo_numero = numero.split('.');
	if(arreglo_numero.length > 1 )
	{
		if(arreglo_numero[1].length > decimales)
		{
			campo.value = numero.substring(0,longitud - 1);
			campo.focus();
		}
	}
}
</script>
	<?php if($valoresP43[4]=="Bj"){
					$ckech='checked="checked"';
					$bloq='';
				}else{
					$ckech='';
					$bloq='disabled="disabled"';
				} ?>
<!-- <?php if ($valoresP43[4]=="Bj") {echo "checked='checked'";} ?> -->
<script type="text/javascript" language="javascript">
			function abilitarCamposPromedio(campo1, campo2){
				if($(campo1).checked==false){
					$desactiva= "";
					$(campo2).disabled=true;
					$(campo2).value='';
				} else {
					$(campo2).disabled=false;
				}
			}
		</script>
			<script type="text/javascript">
function validar117() {
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="S")
{
 document.getElementById("p117").disabled = false;
  document.getElementById("p119").disabled = false;
   document.getElementById("p121").disabled = false;
    document.getElementById("p123").disabled = false;
}
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="N")
{
document.getElementById("p117").disabled = true; document.getElementById("p117").checked = false;
document.getElementById("p1018").disabled = true; document.getElementById("p1018").value = "";
  document.getElementById("p119").disabled = true; document.getElementById("p119").checked = false;
 document.getElementById("p120").disabled = true; document.getElementById("p120").value = "";
   document.getElementById("p121").disabled = true; document.getElementById("p121").checked = false;
  document.getElementById("p122").disabled = true; document.getElementById("p122").value = "";
    document.getElementById("p123").disabled = true; document.getElementById("p123").checked = false;
    document.getElementById("p124").disabled = true; document.getElementById("p124").value = "";
}
}
	addEvent('load', validar117); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
	<script>
function validar102117(){
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="S")
{
 document.getElementById("parametro38117").style.display = "";
  document.getElementById("mostrarparametro117").style.display = "none";
 
}
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="NO")
{
 document.getElementById("parametro38117").style.display = "none";
  document.getElementById("mostrarparametro117").style.display = "";
}
if (document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="N") 
{
 document.getElementById("parametro38117").style.display = "none";
  document.getElementById("mostrarparametro117").style.display = "none";
}
}
	addEvent('load', validar102117); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro	
</script>
<script type="text/javascript">
	function validar117244(){
if(document.getElementById("pgul2").checked == true){
    document.getElementById("moclcpda").style.display = "none";
        document.getElementById("conf").checked = false;
        document.getElementById("ndp301563").value = '';
    
}
if(document.getElementById('pgul3').checked == true)
{
  document.getElementById("moclcpda").style.display = "";
}
}
	addEvent('load', validar117244); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro	
</script>
		<?php
		break;
/*-----------------------------------------------
/*-----------------------------------------------------------------------------------------CASO 117---------------------------------------------*/
case 102:
		$valoresP43 = explode("$",$row_configuracion['conf_valor']);	
			$e1111= $valoresP43[5];	
		?>
			 <table  width="90%">
				 <tr>
				 <td><b>Aplica</b>
				<select class="sele_mul" onclick="validar102()" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $valoresP43[0]))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $valoresP43[0]))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
				 </td>
				 <td style="text-align: center;">
				  </td>
				 </tr>
				 		<tr><td id="p102"> <a href="recontextualizar_texto1.php" target="_blank" style="color:#3399FF">Ir a la interfaz de asignacion</a></td>   </tr>
				 	</table>
				 	</center>
			<script>
function validar102(){
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="S")
{
 document.getElementById("p102").style.display = "";
}
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="N")
{
 document.getElementById("p102").style.display = "none";
}
}
	addEvent('load', validar102); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
		<?php
		break;
////////////////////////////////////////////////////////////////////////////////////////////camilo////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		case 235:
		$valoresP433 = explode("$",$row_configuracion['conf_valor']);
			$ee1111= $valoresP433[5];
			// echo "<script>alert('')</script>";
		?>
			 <table  width="90%">
				 <tr>
				 <td><b>Aplica</b>
				<select class="sele_mul" onclick="validar169()" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $valoresP433[0]))) {echo "selected=\"selected\"";} ?>>Si</option>
			<option value="N" <?php if (!(strcmp("N", $valoresP433[0]))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
				 </td>
				 <td style="text-align: center;">
				  </td>
				 </tr>
				 		<tr><td> Porcentaje:<input id="p169"type="text" maxlength="2" class="determinarcampo1177"onkeypress="return justNumbers(event);"style="width:10%;"  name="cBjj_<?php echo $row_configuracion['conf_nombre'];?>" <?php if ($valoresP433[5]=="Bjj")?>  value="<?php echo $ee1111; ?>"></td>   </tr>
				 	</table>
				 	</center>
			<script>
function validar169(){
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="S")
{
 document.getElementById("p169").disabled = false;
}
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="N")
{
 document.getElementById("p169").disabled = true;
}
}
	addEvent('load', validar169); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
		<?php
break;
			case 154:
		$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$reasignacionRepro_ = $array_parametro[1];
					$reasignacionRepro2_ = $array_parametro[2];
					$reasignacionRepro4_ = $array_parametro[3];
					$reasignacionRepro5_ = $array_parametro[4];
					$reasignacionRepro6_ = $array_parametro[5];
					$reasignacionRepro7_ = $array_parametro[6];
		$valoresP43 = explode("$",$row_configuracion['conf_valor']);
			$e1111= $valoresP43[5];
	$parametro = $array_parametro[0];
?>
		<b>Aplica</b>
				 <select class="sele_mul" onclick="validar154()"name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>" >
<?php
mysql_select_db($database_sygescol, $sygescol);
$query_planillasemestral = "SELECT conf_sygescol.conf_valor FROM conf_sygescol WHERE conf_sygescol.conf_nombre LIKE 'planilla_reconsideracionsemestral'";
$planillasemestral = mysql_query($query_planillasemestral, $sygescol) or die(mysql_error());
$row_reconsideracionsemestral = mysql_fetch_assoc($planillasemestral);
$totalRows_semestral = mysql_num_rows($planillasemestral);
if ($row_reconsideracionsemestral['conf_valor'] == 'N') {
	$prueba2='No';
}
else
{
	$prueba2='Si';
}
?>
					<option value="<?php echo $row_reconsideracionsemestral['conf_valor']; ?>" ><?php echo $prueba2 ?></option>
				 </select>
<div class="container_demohrvszv">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Si aplica defina:</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
		<table>
				<tr>
			 		<td> <input id="p19_111" type="radio" onclick="validar1544()"<?php if (!(strcmp("A", $array_parametro[1]))) {echo "checked=checked";} ?> value="A" name="valorplanirecu_<?php echo $row_configuracion['conf_nombre']; ?>"/> </td> <td>Habilitar la planilla, Solo para casos de reprobaci&oacute;n de la asignatura Desempe&ntilde;o Bajo </td>
				</tr>
 		       <tr>
			 		<td></td><td>Valor m&aacute;ximo permitido en la planilla<input id="p19_222" type="text" style="width: 10%;"onkeypress="return justNumbers(event);"  value="<?php echo $array_parametro[2]; ?>" name="reasignacionRepro3_<?php echo $row_configuracion['conf_nombre']; ?>"/> </td>
				</tr>
				<tr>
			 		<td> <input id="p19_333" type="radio" onclick="validar15444()"<?php if (!(strcmp("B", $array_parametro[1]))) {echo "checked=checked";} ?> value="B" name="valorplanirecu_<?php echo $row_configuracion['conf_nombre']; ?>"/> </td> <td> Con <b style="color:red;">Habilitar la planilla, para los desempe&ntilde;os.</td>
				</tr>
				 </tr>
				<tr><td><input id="p19_444"type="checkbox" name="cS_<?php echo $row_configuracion['conf_nombre'];?>"  <?php if ($reasignacionRepro4_=="S")  {echo "checked='checked'";} ?>  value="S"></td> <td> Superior</td></tr>
				 		<tr><td><input id="p19_555"type="checkbox"  name="cA_<?php echo $row_configuracion['conf_nombre'];?>"  <?php if ($reasignacionRepro5_=="A")  {echo "checked='checked'";} ?>  value="A"></td> <td> Alto</td>    </tr>
				 		<tr><td><input id="p19_666"type="checkbox" name="cBs_<?php echo $row_configuracion['conf_nombre'];?>" <?php if ($reasignacionRepro6_=="Bs") {echo "checked='checked'";} ?>  value="Bs"></td> <td> Basico</td> </tr>
			</table>
</div>
</div>
</div>
</div>
</div>
<script>
function validar154() {
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="S")
{
 document.getElementById("p19_111").disabled = false;
  document.getElementById("p19_222").disabled = false;
   document.getElementById("p19_333").disabled = false;
    document.getElementById("p19_444").disabled = false;
     document.getElementById("p19_555").disabled = false;
      document.getElementById("p19_666").disabled = false;
       document.getElementById("p19_777").disabled = false;
}
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="N")
{
 document.getElementById("p19_111").disabled = true;
  document.getElementById("p19_222").disabled = true;
   document.getElementById("p19_333").disabled = true;
    document.getElementById("p19_444").disabled = true;
     document.getElementById("p19_555").disabled = true;
      document.getElementById("p19_666").disabled = true;
       document.getElementById("p19_777").disabled = true;
}
}
	addEvent('load', validar154); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
<script type="text/javascript">
function validar15444() {
 {
 	document.getElementById("p19_222").disabled = true;
 	document.getElementById("p19_444").disabled = false;
 	document.getElementById("p19_555").disabled = false;
 	document.getElementById("p19_666").disabled = false;
 	 	document.getElementById("p19_777").disabled = false;
 }
}
function validar1544() {
 {
 	document.getElementById("p19_222").disabled = false;
 	document.getElementById("p19_444").disabled = true;
 	document.getElementById("p19_555").disabled = true;
 	document.getElementById("p19_666").disabled = true;
 	 	document.getElementById("p19_777").disabled = true;
 }
}
</script>
<?php
break;
 case 157:
		$array_parametro = explode("$",$row_configuracion['conf_valor']);
		//Consultamos el tope maximo de los estudiantes (Zona Urbana)
		$proyecion_cupos_ind_0 = $array_parametro[0];
		$proyecion_cupos_ind_1 = $array_parametro[1];
		$proyecion_cupos_ind_2 = $array_parametro[2];
		$proyecion_cupos_ind_3 = $array_parametro[3];
		$proyecion_cupos_ind_4 = $array_parametro[4];
		$proyecion_cupos_ind_5 = $array_parametro[5];
		$proyecion_cupos_ind_6 = $array_parametro[6];
		$proyecion_cupos_ind_7 = $array_parametro[7];
		$proyecion_cupos_ind_8 = $array_parametro[8];
		$proyecion_cupos_ind_9 = $array_parametro[9];
        $proyecion_cupos_ind_10 = $array_parametro[10];
		$proyecion_cupos_ind_11 = $array_parametro[11];
        $proyecion_cupos_ind_12 = $array_parametro[12];
        $proyecion_cupos_ind_13 = $array_parametro[13];
        $proyecion_cupos_ind_14 = $array_parametro[14];
        $proyecion_cupos_ind_15 = $array_parametro[15];
        $proyecion_cupos_ind_16 = $array_parametro[16];
        $proyecion_cupos_ind_17 = $array_parametro[17];
//Consultamos las dimensiones o indicadores de logros predeterminados para cada colegio
$sele_accciones_eval = "SELECT * FROM config_planilla_oblig ";
$sql_acciones = mysql_query($sele_accciones_eval,$link);
?>
<div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
     <div class="accordion_inwerds">
        <div class="acc_headerfgd"><strong>Orden Procesos de Evaluacion</strong> </div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
<?php
$conta=0;
while($row_proceso_eval2113 = mysql_fetch_array($sql_acciones))	 {
$conta++;
if ($row_proceso_eval2113['conf_obl_equ'] == '1') {
?>
<table class='formulario' style='width: 100%; float: left; border-color: #0079B2;'>
<th colspan='2' id="dimensioncss" class='formulario'><center>Dimension</center></th>
<tr>
<td style='text-align:center; font-family: sans-serif;'>
<?php
echo $row_proceso_eval2113['conf_obl_texto']."<br>";
?>
<style>
#dimensioncss{
	    background-color: #0079B2;
    color: #FFFFFF;
    font-weight: bold;
    height: 25px;
    vertical-align: middle;
}
#accionescss{
	    background-color: #4BC2FB;
    color: #FFFFFF;
    font-weight: bold;
    height: 25px;
    vertical-align: middle;
}
</style>
</td>
<td style='text-align:center;'><center><input type="text" onkeypress="return justNumbers(event);" name="planilla_prom_ant1_ind_<?php echo $conta; ?>" value="<?php echo $proyecion_cupos_ind_.$array_parametro[$conta]; ?>" style="border-radius: 10px; width: 18%;float:right;" /></center></td>
</tr></table>
<?php }
$sele_proceso_eval = "SELECT * FROM config_planilla_oblig where conf_obl_cual = '".$row_proceso_eval2113['conf_obl_id']."'";
$sql_proceso_eval = mysql_query($sele_proceso_eval,$link);
	while($row_proceso_eval = mysql_fetch_array($sql_proceso_eval))	 {
$conta++;
 ?>
<?php
//echo $row_proceso_eval2113['conf_obl_texto'].'= ='.$row_proceso_eval['conf_obl_texto'];
if ($row_proceso_eval2113['conf_obl_id'] == $row_proceso_eval['conf_obl_cual']) {
	?>
	<table class='formulario' style='width: 100%; float: left;border-color:  #4BC2FB;'>
<th colspan='2'  id="accionescss" class='formulario'><center>Accion</center></th>
<tr>
<td style='text-align:center; font-family: sans-serif; 	'>
<?php
echo $row_proceso_eval['conf_obl_texto']."<br>";
?>
</td>
<td style='text-align:center;'><center><input type="text" onkeypress="return justNumbers(event);" name="planilla_prom_ant1_ind_<?php echo $conta; ?>" value="<?php echo $proyecion_cupos_ind_.$array_parametro[$conta]; ?>" style="border-radius: 10px; width: 18%;float:right;" /></center></td>
</tr>
</table>
<?php
}
 ?>
<?php
}
?>
<?php
}
 ?>
</div></div></div></div></div>
<?php
break;
case 257:
$sql_param_257 = "SELECT * FROM conf_sygescol WHERE conf_id = 257";
$sel_param_257 = mysql_query($sql_param_257,$link) or die("No se puede consultar el parametro 257");
$rows_param_257 = mysql_fetch_assoc($sel_param_257);
$explode_257 = explode("$", $rows_param_257['conf_valor']);
$valores_periodos = explode(",", $explode_257[3]);
$primer_periodo = $valores_periodos[0];
$segundo_periodo = $valores_periodos[1];
$tercer_periodo = $valores_periodos[2];
$cuarto_periodo = $valores_periodos[3];
?>
        <div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
     <div class="accordion_inwerds">
        <div class="acc_headerfgd">&Iacute;tem </div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
          <div>
          	<center>
          	<strong>Aplica:</strong>
          		<select class="sele_mul" name="param_cambio_nota" id="param_cambio_nota" onchange="mostrar_contenido(this.value)">
          			<option value="N"  <?php if($explode_257[0] == "N") {echo "selected";} ?>>No</option>
          			<option value="S"  <?php if($explode_257[0] == "S") {echo "selected";} ?>>Si</option>
          		</select>
          </center>
          	<br />
          	<script>
          	function mostrar_contenido(valor){
          		if (valor == "N") {
          			document.getElementById("contenido_cambio").style.display = "none";
          		}else{
          			document.getElementById("contenido_cambio").style.display = "";
          		}
          	}

          	</script>
          	<?php 
          	if ($explode_257[0] == "N") {
          		$display_div = "style='display:none;'";
          	}
          	else{
          		$display_div = "";
          	}
          	 ?>
          	
          	<div id="contenido_cambio" <?php echo $display_div ?>>
          	<strong>Aplica Para: </strong>
          	<table>
          	<tr>
          	<td><input type="checkbox" name="mod_cam_area" id="mod_cam_area" value="A" <?php if($explode_257[1] == "A") {echo "checked";} ?> /></td><td>&nbsp;&nbsp;&Aacute;reas</td>
          	</tr>
          	<tr>
          	<td><input type="checkbox" name="mod_cam_asig" id="mod_cam_asig" value="S" <?php if($explode_257[2] == "S") {echo "checked";} ?> /></td><td>&nbsp;&nbsp;Asignaturas</td>
          	</tr>
          	</table>
          	<br />
          	<strong>Aplica para los periodos:</strong>
          	<table>
          		<tr>
          			<td><input type="checkbox" name="mod_camb_per_1" id="mod_camb_per_1" <?php if($primer_periodo == "1") {echo "checked";} ?> value="1" /></td>&nbsp;&nbsp;<td>Primero</td>
          		</tr>
          		<tr>
          			<td><input type="checkbox" name="mod_camb_per_2" id="mod_camb_per_2" <?php if($segundo_periodo == "2") {echo "checked";} ?> value="2" /></td>&nbsp;&nbsp;<td>Segundo</td>
          		</tr>
          		<tr>
          			<td><input type="checkbox" name="mod_camb_per_3" id="mod_camb_per_3" <?php if($tercer_periodo == "3") {echo "checked";} ?> value="3" /></td>&nbsp;&nbsp;<td>Tercero</td>
          		</tr>
          		<tr>
          			<td><input type="checkbox" name="mod_camb_per_4" id="mod_camb_per_4" <?php if($cuarto_periodo == "4") {echo "checked";} ?> value="4" /></td>&nbsp;&nbsp;<td>Cuarto</td>
          		</tr>
          	</table>
          	</div>
          </div>

</div>
</div>
</div>
</div>
</div>
<?php
	break;
}
?>
	</td>
</tr>
<?php
}while($row_configuracion = mysql_fetch_assoc($configuracion));
?>
<?php
///Instanciacion de clases para el llamado de los parametros
// include 'ParametrosGenerales.php';
$objetoParametros4=new ParametrosGenerales();  ///Instancio el Objeto ParametroGenerales
$conexionBd=$objetoParametros4->creaConexion();  ///llamo la funcion creaConexion
$numPersiana=4;    
$cantidadParametros=$objetoParametros4 -> returnCantidadParametrosPersiana($conexionBd,$numPersiana); //Retorna la cantidad de parametros que hay dentro de la persiana
$cant=0;
while($cant<$cantidadParametros)  //Mientras haya Parametros Entre al while
{
	 	$valor_id_parametro_individual=$objetoParametros4  -> devuelvaParametros($cant);
       $objetoParametros4 ->ejecutarImpresionParametros($numPersiana,$conexionBd,$consecutivo,$valor_id_parametro_individual ); //Ejecuta la impresion de los paramertros individualmente
       $consecutivo=$objetoParametros4 ->returnConsecutivo();    //Retorna el consecutivo
        $informacionDelParametro=$objetoParametros4  ->informacionParametro($conexionBd,$valor_id_parametro_individual);  //Informacion del parametro 
       $cant++;
       // switch($valor_id_parametro_individual)
       // {
       // }
}
?>
</table>
</div>
</div>
</div>
</div>
</div>
<!-- ------------------------------------------ PARAMETROS HORARIOS -------------------------------------- -->
<?php
if($totalRows_configuracion)
{
	mysql_data_seek($configuracion,0);
    mysql_select_db($database_sygescol, $sygescol);
	$query_configuracion = "SELECT conf_sygescol.conf_id, conf_sygescol.conf_nombre, conf_sygescol.conf_valor, conf_sygescol.conf_descri, conf_sygescol.conf_nom_ver, encabezado_parametros.titulo
								FROM conf_sygescol  INNER JOIN encabezado_parametros on encabezado_parametros.id_param=conf_sygescol.conf_id
							WHERE conf_sygescol.conf_estado = 0
								AND conf_sygescol.conf_id IN (70,122,223)  ORDER BY encabezado_parametros.id_orden ";
	$configuracion = mysql_query($query_configuracion, $sygescol) or die(mysql_error());
	$row_configuracion = mysql_fetch_assoc($configuracion);
}
?>
<?php
include ("conb.php");$registrose=mysqli_query($conexion,"select * from conf_sygescol_adic where id=5")or die("Problemas en la Consulta".mysqli_error());while ($rege=mysqli_fetch_array($registrose)){$coloracorde=$rege['valor'];}
?>
<div class="container_demohrvszv_caja_1"  >
<div class="accordion_example2wqzx_caja_2"  >
			<div class="accordion_inwerds_caja_3"  >
				<div class="acc_headerfgd_caja_titulo"   style="background-color: <?php echo $coloracorde ?>"><center><strong>5. PAR&Aacute;METROS PARA HORARIOS</strong></center><br /><center><input type="radio" value="rojoe" name="colorese">Si&nbsp;&nbsp;<input type="radio" value="naranjae" name="colorese">No</div></center>
				<div class="acc_contentsaponk_caja_4"  >
<div class="grevdaiolxx_caja_5" >
<table  align="center"   width="auto" class="centro" cellpadding="10" class="formulario"  border="1">
	<tr>
	<th class="formulario"  style="background: #3399FF;" >Consecutivo </th>
	<th class="formulario"  style="background: #3399FF;" >Id Par&aacute;metro </th>
	<th class="formulario" >Tipo de Par&aacute;metro</th>
    <th class="formulario" >Detalle del Par&aacute;metro</th>
	<th class="formulario" id='seleccion_horario'>Selecci&oacute;n</th>
	</tr>
	<?php
	do
	{
		$consecutivo++;
	?>
	<td class="formulario"   id="Consecutivo<?php echo $consecutivo; ?>" ><strong> <?php echo $consecutivo; ?></strong></td>
	<td class="formulario"  id="Parametro<?php echo $row_configuracion['conf_id']; ?>" ><strong><?php echo $row_configuracion['conf_id'] ?></strong></td>
<td valign="top"><strong>
<div class="container_demohrvszv_caja_tipo_param" >
<div class="accordion_example2wqzx" >
<div class="accordion_inwerds" >
<div class="acc_headerfgd"><strong>Tipo de Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx_caja_tipo_param" >
<div id="conf_nom_ver">
<div  class="textarea "  align="justify" id="conf_nom_ver-<?php echo $row_configuracion['conf_id'] ?>"><?php echo $row_configuracion['conf_nom_ver']; ?></div>
</div>
</div></div></div></div></div>
</strong>
</td>      
<td valign="top" width="80%">
     <div class="container_demohrvszv" >
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd"  id="cgasvf"><strong>Detalle Par&aacute;metro</strong></div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
<div id="conf_descri">
      <div  align="justify" class="textarea " id="conf_descri-<?php echo $row_configuracion['conf_id'] ?>" style="width: 90%;" align="justify">
      <?php echo $row_configuracion['conf_descri']; ?>
      </div>
</div>
					</div>
				</div>
			</div>
		</div>
</div>
 </td>
	<td align="center">
	<?php
	switch($row_configuracion['conf_id'])
	{
			case 70: //tot_sem_clase
		$valoresP70 = explode("$", $row_configuracion['conf_valor']);
		?>
		<!-- ---------------------------------------------------------- PARAMETRO 7 ---------------------------------------------------------------------------- -->
        <div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
     <div class="accordion_inwerds">
        <div class="acc_headerfgd">&Iacute;tem </div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
<!--el contenido va aca-->
		<table>
		<tr><td><strong>Tradicional: </strong></td><td><input style="border-radius: 10px;" onkeypress="return justNumbers(event);"name="tra_<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>" type="text" size="2" value="<?php echo $valoresP70[0]; ?>" /></td></tr>
		<tr><td><strong>Ciclo Primaria:</strong></td><td><input style="border-radius: 10px;" onkeypress="return justNumbers(event);"name="cicP_<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>" type="text" size="2" value="<?php echo $valoresP70[1]; ?>" /></td></tr>
		<tr><td><strong>Ciclo Basica:</strong></td><td><input style="border-radius: 10px;" onkeypress="return justNumbers(event);"name="cicB_<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>" type="text" size="2" value="<?php echo $valoresP70[2]; ?>" /></td></tr>
		<tr><td><strong>Ciclo Media:</strong></td><td><input style="border-radius: 10px;" onkeypress="return justNumbers(event);"name="cicM_<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>" type="text" size="2" value="<?php echo $valoresP70[3]; ?>" /></td></tr>
		<tr><td><strong>Grupos Juveniles: </strong></td><td><input style="border-radius: 10px;" onkeypress="return justNumbers(event);"name="gru_<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>" type="text" size="2" value="<?php echo $valoresP70[4]; ?>" /></td></tr>
		<tr><td><strong>N.E.E.: </strong></td><td><input style="border-radius: 10px;" onkeypress="return justNumbers(event);"name="nee_<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>" type="text" size="2" value="<?php echo $valoresP70[5]; ?>" /></td></tr>
		<tr><td><strong>Aceleraci&oacute;n A.: </strong></td><td><input style="border-radius: 10px;" onkeypress="return justNumbers(event);"name="ace_<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>" type="text" size="2" value="<?php echo $valoresP70[6]; ?>" /></td></tr>
		<tr><td><strong>P.F.C.: </strong></td><td><input style="border-radius: 10px;" onkeypress="return justNumbers(event);"name="pfc_<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>" type="text" size="2" value="<?php echo $valoresP70[7]; ?>" /></td></tr>
		</table>
</div>
</div>
</div>
</div>
</div>
		<?php
		break;
      case 223:
        $array_parametro = explode("$",$row_configuracion['conf_valor']);
              $array_parametro = explode("$",$row_configuracion['conf_valor']);
        $aplica_promoanti_121 = $array_parametro[0];
        $nota_minima_121 = $array_parametro[1];
        $aplica_nota_comportamiento_121 = $array_parametro[2];
        $nota_comportamiento_121 = $array_parametro[3];
        $aplica_asistencia_121 = $array_parametro[4];
        $porcentaje_asistencia_121 = $array_parametro[5];
        $no_negativos_121 = $array_parametro[6];
        $aplica_periodo_121 = $array_parametro[7];
        $fecha_inicio_121 = $array_parametro[8];
        $fecha_final_121 = $array_parametro[9];
        $estado = $array_parametro[10];
        $paraQueAreas = $array_parametro[12];
        $areasReprobadas=$array_parametro[13];
        $todasAreas=$array_parametro[14];
        $demasAreas=$array_parametro[15];
        $areas_R=$array_parametro[16];
        $todas_A=$array_parametro[17];
        $demas_A=$array_parametro[18];
          $aplica_promoanti_1211 = $array_parametro[20];
          $no_negativos_1211 = $array_parametro[21];
          $aplica_asistencia_1211 = $array_parametro[22];
              $fecha_inicio_121_2 = $array_parametro[25];
        $fecha_final_121_2 = $array_parametro[26];
        $fecha_inicio_121_2_1 = $array_parametro[27];
        $fecha_final_121_2_1 = $array_parametro[28];
        $parametro = $row_configuracion['conf_valor'];
        //documentar//
    /*    
print_r($array_parametro);
  //documentar//
*/
     ?>
           <script>
function validar45223() {
if(document.getElementById('criterio223').value=="S"){
document.getElementById("style1").style.display = "";
document.getElementById("style2").style.display = "";
}
if(document.getElementById('criterio223').value=="N")
{
document.getElementById("style1").style.display = "none";
document.getElementById("style2").style.display = "none";
}
}
  addEvent('load', validar45223); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
<link href="js/jquery/mobiscroll-2.1-beta.custom.min.css" rel="stylesheet" type="text/css" />
<script src="js/jquery/mobiscroll-2.1-beta.custom.min.js" type="text/javascript"></script>
<script language="javascript">
	var RELO = jQuery.noConflict();
	RELO(document).ready(function() {
		RELO('#hora_cita_1').scroller({
			preset: 'time',
			theme: 'android-ics',
			display: 'modal',
			mode: 'clickpick'
		});
	});
</script>
<script language="javascript">
	var RELO = jQuery.noConflict();
	RELO(document).ready(function() {
		RELO('#hora_cita_2').scroller({
			preset: 'time',
			theme: 'android-ics',
			display: 'modal',
			mode: 'clickpick'
		});
	});
</script>
<script language="javascript">
	var RELO = jQuery.noConflict();
	RELO(document).ready(function() {
		RELO('#hora_cita_3').scroller({
			preset: 'time',
			theme: 'android-ics',
			display: 'modal',
			mode: 'clickpick'
		});
	});
</script>
<!----------------------------------------------   2  ---------------------------------------------->
<script language="javascript">
	var RELO = jQuery.noConflict();
	RELO(document).ready(function() {
		RELO('#hora_citaa_1').scroller({
			preset: 'time',
			theme: 'android-ics',
			display: 'modal',
			mode: 'clickpick'
		});
	});
</script>
<script language="javascript">
	var RELO = jQuery.noConflict();
	RELO(document).ready(function() {
		RELO('#hora_citaa_2').scroller({
			preset: 'time',
			theme: 'android-ics',
			display: 'modal',
			mode: 'clickpick'
		});
	});
</script>
<script language="javascript">
	var RELO = jQuery.noConflict();
	RELO(document).ready(function() {
		RELO('#hora_citaa_3').scroller({
			preset: 'time',
			theme: 'android-ics',
			display: 'modal',
			mode: 'clickpick'
		});
	});
</script>
<!----------------------------------------------   3  ---------------------------------------------->
<script language="javascript">
	var RELOOO = jQuery.noConflict();
	RELOOO(document).ready(function() {
		RELOOO('#hora_citaaa_1').scroller({
			preset: 'time',
			theme: 'android-ics',
			display: 'modal',
			mode: 'clickpick'
		});
	});
</script>
<script language="javascript">
	var RELOOO = jQuery.noConflict();
	RELOOO(document).ready(function() {
		RELOOO('#hora_citaaa_2').scroller({
			preset: 'time',
			theme: 'android-ics',
			display: 'modal',
			mode: 'clickpick'
		});
	});
</script>
<script language="javascript">
	var RELOOO = jQuery.noConflict();
	RELOOO(document).ready(function() {
		RELOOO('#hora_citaaa_3').scroller({
			preset: 'time',
			theme: 'android-ics',
			display: 'modal',
			mode: 'clickpick'
		});
	});
</script>
<!----------------------------------------------   4  ---------------------------------------------->
<script language="javascript">
	var RELOOOO = jQuery.noConflict();
	RELOOOO(document).ready(function() {
		RELOOOO('#hora_citaaaa_1').scroller({
			preset: 'time',
			theme: 'android-ics',
			display: 'modal',
			mode: 'clickpick'
		});
	});
</script>
<script language="javascript">
	var RELOOOO = jQuery.noConflict();
	RELOOOO(document).ready(function() {
		RELOOOO('#hora_citaaaa_2').scroller({
			preset: 'time',
			theme: 'android-ics',
			display: 'modal',
			mode: 'clickpick'
		});
	});
</script>
<script language="javascript">
	var RELOOOO = jQuery.noConflict();
	RELOOOO(document).ready(function() {
		RELOOOO('#hora_citaaaa_3').scroller({
			preset: 'time',
			theme: 'android-ics',
			display: 'modal',
			mode: 'clickpick'
		});
	});
</script>
     <table  style="border:0px solid #666666; margin-top:5px;">
     	<tr>
                    <td colspan="2">
                     <label><b>
                     Aplica: </b>
		  <select class="sele_mul" name="criterio_<?php echo $row_configuracion['conf_nombre']; ?>" id="criterio223" onclick="validar45223()">
			<option value="S" <?php if (!(strcmp("S", $aplica_promoanti_121['conf_valor']))) {echo "selected=\"selected\"";} ?>>Si</option>
			<option value="N" <?php if (!(strcmp("N", $aplica_promoanti_121['conf_valor']))) {echo "selected=\"selected\"";} ?>>No</option>
		  </select>
		</label> </tr>
     </table>
<?php
$query_sedes12 = "SELECT * 
FROM v_grados
INNER JOIN jraa ON ( v_grados.jornada_id = jraa.i ) 
WHERE gao_codigo =  'TR'
order by v_grados.jornada_id";
$sedes12 = mysql_query($query_sedes12, $link) or die(mysql_error());
 $rows12 = mysql_num_rows($sedes12);
 $query_sedes121 = "SELECT * FROM v_grados WHERE gao_codigo in (01,02,03,04,05)
group by jornada_codigo";
$sedes121 = mysql_query($query_sedes121, $link) or die(mysql_error());
 $rows121 = mysql_num_rows($sedes121);
?>
<div id="style1">
      <div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
      <div class="accordion_inwerds">
        <div class="acc_headerfgd"><strong>PREESCOLAR</strong></div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
        <table  width="90%" style="border:1px solid #666666; margin-top:5px;">
<?php 
$cp1 = 0;
$nmid = 0;
$nmid1 = 2;
$nmid11 = 4;
while($rowParametro1 = mysql_fetch_array($sedes12)){
	$nmid++;
 $cp1++;
$nmid1++;
 $nmid11++;
 ?>
 <tr>
 	<td>
 		<strong><?php echo 'Jornada '.$rowParametro1['jornada_nombre']; ?></strong>
 	</td>
 </tr>
<tr>
<td><tr>
            <td><input  id="radiop_11_<?php echo $cp1?>" name="areas_R_<?php echo $cp1?>"  type="radio" value="1" <?php if  ($array_parametro[$nmid]=='1') {echo "checked='checked'";} ?> >Atendido por docentes</td>
          </tr>
          <tr>
          <td>&nbsp;</td>
          </tr>
             <tr>
            <td><input  id="radiop_22_<?php echo $cp1?>" name="areas_R_<?php echo $cp1?>"  type="radio" value="3" <?php if ($array_parametro[$nmid]=='3')
            {echo "checked='checked'";} ?> >Atendido por Coordinador</td>
          </tr>
  <tr>
                     <td>
            <table >
              <tr style="text-align: center;" class="fila1">
                <td  colspan="2">Hora de inicio</td>
                <td  colspan="2">Hora de terminaci&oacute;n</td>
              </tr>
              <tr>
<td> <input  class="p2 form-control ac_input"  type="time"  name="hora_cita_<?php echo $cp1?>" id="hora_cita_<?php echo $cp1?>" placeholder="HORA DE LA CITACI&Oacute;N" value="<?php echo $array_parametro[$nmid1]; ?>" /></td>
<td ></td>
<td > <input  class="p2 form-control ac_input"  type="time"  name="hora_citaa_<?php echo $cp1?>" id="hora_citaa_<?php echo $cp1?>" placeholder="HORA DE LA CITACI&Oacute;N" value="<?php echo $array_parametro[$nmid11]; ?>"/></td>
<td></td>
              </tr>
  </table>
                      </td>
                  </tr>
                  <?php } ?>
         </table> </div></div></div></div></div></div>
         <br /><br /><br />
         <div id="style2">
        <div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
      <div class="accordion_inwerds">
        <div class="acc_headerfgd"><strong>B&Aacute;SICA PRIMARIA</strong></div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
        <table  width="90%" style="border:1px solid #666666; margin-top:5px;">
<?php 
$cp11 = 0;
$nmid1 = 6;
$nmid11 = 8;
$nmid111 = 10;
while($rowParametro11 = mysql_fetch_array($sedes121)){
	$nmid1++;
 $cp11++;
$nmid11++;
 $nmid111++;
 ?>
  <tr>
 	<td>
 		<strong><?php echo 'Jornada '.$rowParametro11['jornada_nombre']; ?></strong>
 	</td>
 </tr>
<tr>
<td><tr>
            <td><input  id="radiop_112_<?php echo $cp11?>" name="areas_B_<?php echo $cp11?>"  type="radio" value="1" <?php if  ($array_parametro[$nmid1]=='1') {echo "checked='checked'";} ?> >Atendido por docentes</td>
          </tr>
          <tr>
          <td>&nbsp;</td>
          </tr>
             <tr>
            <td><input  id="radiop_222_<?php echo $cp11?>" name="areas_B_<?php echo $cp11?>"  type="radio" value="3" <?php if  ($array_parametro[$nmid1]=='3') {echo "checked='checked'";} ?> >Atendido por Coordinador</td>
          </tr>
  <tr>
                     <td>
            <table >
              <tr style="text-align: center;" class="fila1">
                <td  colspan="2">Hora de inicio</td>
                <td  colspan="2">Hora de terminaci&oacute;n</td>
              </tr>
              <tr>
<td> <input  class="p2 form-control ac_input"  type="time"  name="hora_citaaa_<?php echo $cp11?>" id="hora_citaaa_<?php echo $cp11?>" placeholder="HORA DE LA CITACI&Oacute;N"value="<?php echo $array_parametro[$nmid11]; ?>"/></td>
								<td ></td>
							  	<td>  <input  class="p2 form-control ac_input"  type="time"  name="hora_citaaaa_<?php echo $cp11?>" id="hora_citaaaa_<?php echo $cp11?>" placeholder="HORA DE LA CITACI&Oacute;N" value="<?php echo $array_parametro[$nmid111]; ?>"/></td>
							  	<td></td>
              </tr>
  </table>
                      </td>
                  </tr>
                  <?php } ?>
         </table> </div></div></div></div></div></div>
    <?php
		break;
      case 122:
		$selGrados = "SELECT * FROM gao GROUP BY ba ORDER BY a";
		$sqlGrados = mysql_query($selGrados, $link);
		$parametro48122 = explode("$",$row_configuracion['conf_valor']);
/*
		print_r($parametro48122);*/
		?>
	<div class="container_demohrvszv">
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd">Item</div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
					<?php 
		$selGrados11 = "SELECT * FROM  sedes group by super_sede";
		$sqlGrados11 = mysql_query($selGrados11, $link);
		$contador146122 = 0;
		$contador246122 = -1;
		$contador346122 = 19;
		$contador446122 = 36;
		$contadorNuevo=1;
		$contadorSedes=0;
		$contadorOtro=0;
		$contadorEntraWhile=0;
		$contadorJornadas=6;
		while ($row_grados_sedes11 = mysql_fetch_array($sqlGrados11)) {
$contador146122++;
$contador246122++;
$contador346122++;
$contador446122++;
$nombre_sedem=$row_grados_sedes11['sede_nombre']; 
$nombre_sedem2 = preg_split("/[\s,]+/", $nombre_sedem);
$empresapt=$nombre_sedem2;
$ultimod=array_pop($empresapt);
$fddfd = utf8_decode('MAÑANA');
if ($ultimod==$fddfd) {
	//echo "entro";
$r1 = implode(" ", $empresapt);
$sedefinal=$r1;
}
if ($ultimod!=$fddfd) {
$sedefinal=$nombre_sedem;
}
		 ?>
		 <?php /* ?>
		 <?php if  ($array_parametro[$nmid1]=='1') {echo "checked='checked'";} ?>*/?>
		<table>
			<tr><td><input type="checkbox" id="valida132"onclick="validar132_1()" <?php if  ($parametro48122[$contadorSedes]=='1') {echo "checked='checked'";} ?> name="valorasndnc_<?php echo $contador146122;?>" value="1"> </td>
				<th style="text-align: left;color:red">Para la sede <?php echo $sedefinal; ?>
				</th>
			</tr>

							<?php 
							// echo '<script>alert("While Afuera// POSICION: '.$contador246122.'//CONF VALOR: '.$parametro48122[$contador246122].'  // NAME= valorasndnc_'.$contador146122.'" )</script>';
$selGrados1111 = "SELECT * FROM  sedes INNER JOIN v_grupos ON ( v_grupos.grupo_sede = sedes.sede_consecutivo ) where  sedes.super_sede = '".$row_grados_sedes11['super_sede']."' GROUP BY v_grupos.jornada_nombre";
$sqlGrados1111 = mysql_query($selGrados1111, $link);
$contador46122 = 0; 
$contadorOtro=0;   
$contadorNuevo=$contadorNuevo-$contadorEntraWhile;
$contadorJornadas=$contadorJornadas-$contadorEntraWhile;
$contadorEntraWhile=0;
while ($row_grados_sedes11555 = mysql_fetch_array($sqlGrados1111)) {
	$contador46122++;
	 if($contador146122!=1)   //Si la cantidad de sedes es diferente de Uno
	{
			 	if($contadorOtro==0)    //Si las cantidad de veces que entro al while es igual a cero
			 	{
			 	 $contadorNuevo=$contadorNuevo+11;   //contador para buscar cada once campos
			     $contadorJornadas=$contadorJornadas+11;  //Contador que me sirve para buscar cada once espacios en la base de datos en el campo conf_valor
			 	}		 
	}
		// echo "<script>alert('While Adentro// CHECHBOX: = ".$contadorNuevo." que me trae = ".$parametro48122[$contadorNuevo]."  ////////  POSICION RADIO: ".$contadorJornadas."/// ,  que me trae :".$parametro48122[$contadorJornadas]."  ///////  name Numero:".$contador46122.$contador146122."')</script>";
				 ?>
			<tr><td></td><td>
				<table>
			<tr><td><input type="checkbox" id="niveles_132_1"<?php if  ($parametro48122[$contadorNuevo]=='2') {echo "checked='checked'";} ?> name="plsvr_<?php echo $contador46122.$contador146122;?>" value="2">En la jornada <?php echo $row_grados_sedes11555['jornada_nombre']; ?></td></tr>
			</table>
		
			<tr><td><input type="radio" <?php if  ($parametro48122[$contadorJornadas]==3) {echo "checked='checked'";} ?> id="valida132" name="shrscedysa_<?php echo $contador46122.$contador146122;?>" value="3"> </td>
				<th style="text-align: left;">SI HAY ROTACI&Oacute;N: Se corre el d&iacute;a y sus asignaturas.</th>
			</tr>
			<tr><td></td><td>
				<table>
			<tr><td></td></tr>
			</table>
				<tr><td><input type="radio" id="valida132"<?php if  ($parametro48122[$contadorJornadas]==4) {echo "checked='checked'";} ?> name="shrscedysa_<?php echo $contador46122.$contador146122;?>" value="4"> </td>
				<th style="text-align: left;">NO HAY ROTACI&Oacute;N: El d&iacute;a siguiente conserva su horario de clases normal.</th>
			</tr>
			<tr><td></td><td>
				<table>
			<tr><td></td></tr>
			</table>
						<tr><td><input type="radio" id="valida132"<?php if  ($parametro48122[$contadorJornadas]==5) {echo "checked='checked'";} ?> name="shrscedysa_<?php echo $contador46122.$contador146122;?>" value="5"> </td>
				<th style="text-align: left;">SI HAY ROTACI&Oacute;N: El d&iacute;a y sus asignaturas rotar&aacute;n durante la vigencia de periodo.</th>
			</tr>
			<tr><td></td><td>
				<table>
			<tr><td></td></tr>
			</table>

			<?php 
			
			  $contadorNuevo++;   //Contador para contar las jornadas 
			  $contadorJornadas++;   ///Contador que sirve para ver que radio button se selecciono $parametro48122[$contadorJornadas]
			  $contadorEntraWhile++;   //Cuantas veces entra al while
			
			 $contadorOtro++;    //Para validar cuantas veces entro
		} ?>

		</table>
		<hr />
		<?php 
			$contadorSedes=$contadorSedes+11;   //para buscar cada once campos en la base de datos
		} ///Fin While Grande
		?>
</div>
</div>
</div>
</div>
</div>
<!-- <select name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>" class="sele_mul" style="width:320px;">
			<option value="0" <?php if (!(strcmp("0", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Toda la Informaci&#243;n de la Inscripci&#243;n</option>
			<option value="1" <?php if (!(strcmp("1", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Cargar &#250;nicamente la renovaci&#243;n de la matr&#237;cula, lo que implica manejar un libro virtual y de todas formas imprimir anualmente.</option>
		  </select>-->
		<?php
		break;
	}
?>
	</td>
</tr>
<?php
}while($row_configuracion = mysql_fetch_assoc($configuracion));
?>
<?php
///Instanciacion de clases para el llamado de los parametros
// include 'ParametrosGenerales.php';
$objetoParametros5=new ParametrosGenerales();  ///Instancio el Objeto ParametroGenerales
$conexionBd=$objetoParametros5->creaConexion();  ///llamo la funcion creaConexion
$numPersiana=5;    
$cantidadParametros=$objetoParametros5  -> returnCantidadParametrosPersiana($conexionBd,$numPersiana); //Retorna la cantidad de parametros que hay dentro de la persiana
$cant=0;
while($cant<$cantidadParametros)  //Mientras haya Parametros Entre al while
{
	 	$valor_id_parametro_individual=$objetoParametros5  -> devuelvaParametros($cant);
       $objetoParametros5 ->ejecutarImpresionParametros($numPersiana,$conexionBd,$consecutivo,$valor_id_parametro_individual ); //Ejecuta la impresion de los paramertros individualmente
       $consecutivo=$objetoParametros5 ->returnConsecutivo();    //Retorna el consecutivo
        $informacionDelParametro=$objetoParametros5  ->informacionParametro($conexionBd,$valor_id_parametro_individual);  //Informacion del parametro 
       $cant++;
      //Area de Impresion de la seleccion o las opciones que va a tener el parametro
       switch($valor_id_parametro_individual)
       {
        case 245:
       	?>
       	   		<th>
       		        <div class="container_demohrvszv" id='container_mas_grande' >
					    <div class="accordion_example2wqzx" id='container_medio'>
					     <div class="accordion_inwerds" id='container_pequeno'>
					        <div class="acc_headerfgd" ><strong>DIRECTRICES PARA DEFINIR LOS DESCANSO DEL HORARIO, SEG&Uacute;N TIPO </strong> </div>
					        <div class="acc_contentsaponk" id='dentro_245'>
					          <div class="grevdaiolxx" >
					          <center>
					          		&#191;Permite la generaci&oacute;n autom&aacute;tica de Horarios?
					          			<select  name='check_generacion_horarios' onchange="mostrar_link_generacion_horarios(this.value)">
					          			<option value="0" <?php if($informacionDelParametro["conf_estado"]=='0'){echo " selected ";} ?> >Si</option>
					          			<option value='1' <?php if($informacionDelParametro["conf_estado"]=='1'){echo " selected ";} ?> >No</option>
					          			</select>
					          			</center>
									<center><a href='directrices_descanso_horarios.php' id='link_directrices' target="_blank">
									Ir a Definicion Directrices Horarios
									</a></center>
					          </div>
					        </div>
					       </div>
					    </div>  
					</div>
 		</th>
	 </tr>
       	<script>
       	function mostrar_link_generacion_horarios(valor)
       	{	
       		if(valor==null)
       		{
       			valor=<?php echo $informacionDelParametro["conf_estado"]; ?>;
       		}
       	

       		if(valor=='1')
       		{
       			document.getElementById("link_directrices").style.display='none';
       		}
       		else
       		{
       			document.getElementById("link_directrices").style.display='block';
       		}
       	}
       	document.addEvent('domready',mostrar_link_generacion_horarios);
       	</script>
       	<?php
       break;
       case 248:


       $sql_informacion_asignaturas='SELECT * FROM aintrs';
       $consulta_asignaturas=mysqli_query($conexionBd,$sql_informacion_asignaturas)or die("Error al Consultar las Asignaturas".mysqli_error());
       while($row_informacion_asignaturas=mysqli_fetch_array($consulta_asignaturas))
       {
       		$opciones.='<option value="'.$row_informacion_asignaturas['i'].'">'.$row_informacion_asignaturas['b'].'</option>';
       }

       ?>	
       		       <th>
					<div class="container_demohrvszv">
					    <div class="accordion_example2wqzx">
					     <div class="accordion_inwerds">
					        <div class="acc_headerfgd"><strong>Definici&oacute;n de Asignaturas</strong> </div>
					        <div class="acc_contentsaponk">
					          <div class="grevdaiolxx">
					          	Aplica:
						          <select name='aplica_param_248' id='aplica_param248'>
							          <option value='S' <?php if ($informacionDelParametro["conf_valor"] == "S") { echo "selected=\"selected\"";} ?> >SI</option>
							          <option value='N' <?php if ($informacionDelParametro["conf_valor"] == "N") { echo "selected=\"selected\"";} ?> >NO</option>
						          </select>
						          <table>
									<tr>
										<td id='imprimirAsignaturas'>
											
										</td>
									</tr>
						          </table>
						          <div id='proceso_agregar_asignatura'>
							          <table>
							          <tr>
							            <td align='right'>
								          <input type='number' name='cantidad_horas_248' id='cantidad_horas_248' style='width:50%'></input>
					                     </td>
					                     <td>
								          <select name='select_asignaturas_param_248' id='select_asignaturas_param_248' style='width:200%'>
								          	<option value=''>Seleccione Una...</option>
								          	<?php echo $opciones; ?>
								          </select>
								         </td>
								         <td align='right'>
								          <img src='images/imagen_agregar.png' id='agregar_asignatura_248' width="30%" height="7%" onclick="agregarAsignatura()"></img>
								         </td>
							          </tr>
							          </table>
						          </div>
					          </div>
					         </div>
					        </div>
					       </div>
					    </div>  
					 </th>
					 </tr>
					 <script type="text/javascript">
					 function agregarAsignatura()
					 {
					 	// alert("Hey");
					 	if(document.getElementById('cantidad_horas_248').value=='')
					 	{
					 		alert("Por Favor ingrese la cantidad de horas");
					 	}
					 	else
					 	{
					 		if(document.getElementById('select_asignaturas_param_248').value=='')
					 		{
					 			alert("Seleccione una Asignatura");
					 		}
					 		else
					 		{	
					 			var elementSelect=document.getElementById('select_asignaturas_param_248');
					 			var valorSelect=elementSelect.options[elementSelect.selectedIndex].text;
					 			var node = document.createElement("div");                 
								var textnode = document.createTextNode(valorSelect);         
								node.appendChild(textnode);  
								document.getElementById("imprimirAsignaturas").appendChild(node); 

					 		}
					 	}

					 }
// 					                             // Append the text to <li>
					 </script>
       <?php
       break; 
       }  /////Fin Switch
}

?>
</table>
</div>
</div>
</div>
</div>
</div>
<?php
// esta es la tabla 2
if($totalRows_configuracion)
{
	mysql_data_seek($configuracion,0);
mysql_select_db($database_sygescol, $sygescol);
	$query_configuracion = "SELECT conf_sygescol.conf_id, conf_sygescol.conf_nombre, conf_sygescol.conf_valor, conf_sygescol.conf_descri, conf_sygescol.conf_nom_ver, encabezado_parametros.titulo
								FROM conf_sygescol  INNER JOIN encabezado_parametros on encabezado_parametros.id_param=conf_sygescol.conf_id
							WHERE conf_sygescol.conf_estado = 0
								AND conf_sygescol.conf_id IN (108,112,113,116,155,121,129,120,135,150)  ORDER BY encabezado_parametros.id_orden ";
	$configuracion = mysql_query($query_configuracion, $sygescol) or die(mysql_error());
	$row_configuracion = mysql_fetch_assoc($configuracion);
// aca inicia la otra tabla
}?>
<?php
include ("conb.php");$registrosf=mysqli_query($conexion,"select * from conf_sygescol_adic where id=6")or die("Problemas en la Consulta".mysqli_error());while ($regf=mysqli_fetch_array($registrosf)){$coloracordf=$regf['valor'];}
?>
<div class="container_demohrvszv_caja_1">
		<div class="accordion_example2wqzx_caja_2">
						<div class="accordion_inwerds_caja_3">
				<div class="acc_headerfgd_caja_titulo" id="parametros_promocion_estudiantes" style="background-color: <?php echo $coloracordf ?>"><center><strong>6. PAR&Aacute;METROS PARA  CASOS DE PROMOCI&Oacute;N DE ESTUDIANTES</strong></center><br /><center><input type="radio" value="rojof" name="coloresf">Si&nbsp;&nbsp;<input type="radio" value="naranjaf" name="coloresf">No</div></center>
				<div class="acc_contentsaponk_caja_4">
					<div class="grevdaiolxx_caja_5">
					<table  align="center" width="85%" class="centro" cellpadding="10" class="formulario" border="1">
<tr>
	<th class="formulario"  style="background: #3399FF;" >Consecutivo </th>
	<th class="formulario"  style="background: #3399FF;" >Id Par&aacute;metro </th>
	<th class="formulario" >Tipo de Par&aacute;metro</th>
    <th class="formulario" >Detalle del Par&aacute;metro</th>
	<th class="formulario">Selecci&oacute;n</th>
	</tr>
	<?php
	do
	{
		$consecutivo++;
	?>
	<td class="formulario"   id="Consecutivo<?php echo $consecutivo; ?>" ><strong> <?php echo $consecutivo; ?></strong></td>
	<td class="formulario"  id="Parametro<?php echo $row_configuracion['conf_id']; ?>" ><strong><?php echo $row_configuracion['conf_id'] ?></strong></td>
<td valign="top"><strong>
<div class="container_demohrvszv_caja_tipo_param">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Tipo de Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx_caja_tipo_param">
<div id="conf_nom_ver">
<div  class="textarea "  align="justify" id="conf_nom_ver-<?php echo $row_configuracion['conf_id'] ?>"><?php echo $row_configuracion['conf_nom_ver']; ?></div>
</div></div></div></div></div></div>
</strong>
</td>
      <td valign="top" width="80%">
     <div class="container_demohrvszv" >
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd"  id="cgasvf"><strong>Detalle Par&aacute;metro</strong></div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
<div id="conf_descri">
      <div  align="justify" class="textarea " id="conf_descri-<?php echo $row_configuracion['conf_id'] ?>" style="width: 90%;" align="justify">
      <?php echo $row_configuracion['conf_descri']; ?>
      </div>
</div>
					</div>
				</div>
			</div>
		</div>
</div>
 </td>
	<td align="center">
	<?php
	switch($row_configuracion['conf_id'])
	{
		case 108:
		$valoresP34 = explode("$",$row_configuracion['conf_valor']);
				$parametro108 = $array_parametro108[0];
				$areasObligatorias108 = $array_parametro108[1];
				$areasTecnicas108 = $array_parametro108[2];
		?>
		<!-- ---------------------------------------------------------------- PRIMERA TABLA -------------------------------------------------------------------- -->
<div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
      <div class="accordion_inwerds">
        <div class="acc_headerfgd">&Iacute;tem 1</div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
     <b>Aplica</b>
  <select class="sele_mul" onclick="validar32()"name="7_<?php echo $row_configuracion['conf_nombre']; ?>" id="case451081">
            <option value="S" <?php if (!(strcmp("S",  $valoresP34[6]))) {echo "selected=\"selected\"";} ?>>Si</option>
            <option value="N" <?php if (!(strcmp("N",  $valoresP34[6]))) {echo "selected=\"selected\"";} ?>>No</option>
            </select><br><br><br>
<p style="text-align: left;margin: 0px;border: solid 2px #CE6767;border-radius: 10px;padding: 12px;"><b>1.)</b> Un estudiante que <b style="color: red;" > DESPUES DEL CIERRE DE AREAS </b>, registre <b style="color: red">UN &Aacute;REA PERDIDA</b> con una valoraci&oacute;n entre
     <input type="text" onkeypress="return justNumbers(event);"
id="p32_1"max="5.0" min="1.0" style="text-align: center; border-radius: 10px;width: 45px;" name="1_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $valoresP34[0];?>"> y
     <input type="text" onkeypress="return justNumbers(event);"
 id="p32_2"max="5.0" min="1.0"style="text-align: center; border-radius: 10px;width: 45px;" name="2_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $valoresP34[1];?>">
    ,  pero el promedio
    en todas las &aacute;reas, es mayor o igual a
    <input type="text" onkeypress="return justNumbers(event);"
id="p32_3"max="5.0" min="1.0" style="text-align: center; border-radius: 10px;width: 45px;" name="13_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $valoresP34[12];?>">; el sistema cambiar&aacute; la calificaci&oacute;n del &aacute;rea reprobada, por
    <input type="text" onkeypress="return justNumbers(event);"
 id="p32_4"max="5.0" min="1.0" style="text-align: center; border-radius: 10px;width: 45px;" name="14_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $valoresP34[13];?>"> y <b>LA OBTENCION DE LA NUEVA NOTA</b> se har&aacute; <b>REDONDEANDO</b> la asignatura reprobada sin que las asignaturas promovidas del &aacute;rea se modifiquen en sus valoraciones.
    </p>
  </div>
        </div>
      </div>
      </div>
      </div>
    <script>
function validar32() {
if(document.getElementById('case451081').value=="S")
{
document.getElementById("p32_1").disabled = false;
	document.getElementById("p32_2").disabled = false;
		document.getElementById("p32_3").disabled = false;
			document.getElementById("p32_4").disabled = false;
}
if(document.getElementById('case451081').value=="N")
{
document.getElementById("p32_1").disabled = true;
	document.getElementById("p32_2").disabled = true;
		document.getElementById("p32_3").disabled = true;
			document.getElementById("p32_4").disabled = true;
}
}
addEvent('load', validar32); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
    <!-- ------------------------------ SEGUNDA TABLA -------------------------- -->
        <div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
    <!-- Section 2 -->
      <div class="accordion_inwerds">
        <div class="acc_headerfgd">&Iacute;tem 2</div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
<b>Aplica</b>
          <select class="sele_mul" onclick="validar322()"name="8_<?php echo $row_configuracion['conf_nombre']; ?>" id="case451082">
            <option value="S" <?php if (!(strcmp("S", $valoresP34[7]))) {echo "selected=\"selected\"";} ?>>Si</option>
            <option value="N" <?php if (!(strcmp("N", $valoresP34[7]))) {echo "selected=\"selected\"";} ?>>No</option>
            </select>
          <br><br><br>
    <p style="text-align: left;margin: 0px;border: solid 2px #CE6767;border-radius: 10px;padding: 12px;">
    <b>2.)</b> Un estudiante que al cierre de <b style="color: red" >SUPERACION DE DIFICULTADES FIN DE A&Ntilde;O</b>, registre <b style="color: red">UN &Aacute;REA PERDIDA</b> con una valoraci&oacute;n entre
    <input  type="text" onkeypress="return justNumbers(event);" id="p322_1" max="5.0" min="1.0" style="text-align: center; border-radius: 10px;width: 45px;" name="3_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $valoresP34[2];?>"> y
    <input type="text" onkeypress="return justNumbers(event);"
id="p322_2"  max="5.0" min="1.0" style="text-align: center; border-radius: 10px; width: 45px;" name="4_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $valoresP34[3];?>">,  pero el promedio
    en todas las &aacute;reas, es mayor o igual a
    <input type="text" onkeypress="return justNumbers(event);" id="p322_3" max="5.0" min="1.0" style="text-align: center; border-radius: 10px;width: 45px;" name="5_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $valoresP34[4];?>">; el sistema cambiar&aacute; la calificaci&oacute;n del &aacute;rea reprobada, por
    <input type="text" onkeypress="return justNumbers(event);" id="p322_4"  max="5.0" min="1.0" style="text-align: center; border-radius: 10px;width: 45px;" name="6_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $valoresP34[5];?>"> y <b>LA OBTENCION DE LA NUEVA NOTA</b> se har&aacute; <b>REDONDEANDO</b> la asignatura reprobada sin que las asignaturas promovidas del &aacute;rea se modifiquen en sus valoraciones.
   </p>
  <script>
function validar322() {
if(document.getElementById('case451082').value=="S")
{
 document.getElementById("p322_1").disabled = false;
	document.getElementById("p322_2").disabled = false;
		document.getElementById("p322_3").disabled = false;
			document.getElementById("p322_4").disabled = false;
}
if(document.getElementById('case451082').value=="N")
{
 document.getElementById("p322_1").disabled = true;
	document.getElementById("p322_2").disabled = true;
		document.getElementById("p322_3").disabled = true;
			document.getElementById("p322_4").disabled = true;
}
}
addEvent('load', validar322); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
  </div>
  </div>
  </div>
  </div>
  </div>
<div class="container_demohrvszv">
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd">Obseravion 1 y 2</div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
    <br> <br> <br>
    Si el docente registra en la planilla de <b>SUPERACION DE DIFICULTADES FIN DE A&Ntilde;O</b> (NP) no se present&oacute;, el parametro invalidar&aacute; el cambio de valoracion.
    <br><br>
    EJEMPLO:
    <table border="1">
      <tr>
        <td>&Aacute;rea /Asignatura</td>
        <td><b>Nota Cierre</b></td>
        <td><b>Nueva Nota</b></td>
      </tr>
      <tr>
        <td>Area de humanidades</td>
        <td>2.75</td>
        <td>3.0</td>
      </tr>
      <tr>
        <td>Asignatura: Lengua castellana </td>
        <td>3.50 </td>
        <td>3.5</td>
      </tr>
      <tr>
        <td>Asignatura: Ingl&eacute;s  </td>
        <td>2.00</td>
        <td>2.5</td>
      </tr>
    </table>
</div>
</div>
</div>
</div>
</div>
    <!-- ---------------------------------------- TABLA 3 ----------------------------------- -->
<div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
    <!-- Section 3 -->
      <div class="accordion_inwerds">
        <div class="acc_headerfgd">&Iacute;tem 4</div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
<b>Aplica</b>
          <select class="sele_mul" onclick="validar323()" name="12_<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
            <option value="S" <?php if (!(strcmp("S", $valoresP34[11]))) {echo "selected=\"selected\"";} ?>>Si</option>
            <option value="N" <?php if (!(strcmp("N", $valoresP34[11]))) {echo "selected=\"selected\"";} ?>>No</option>
            </select>
            <br><br><br>
    <p style="text-align: left;margin: 0px;border: solid 2px #CE6767;border-radius: 10px;padding: 12px;">
    <b>3.)</b> para estudiantes con  <b style="color: red"> 1 area perdida de la tecnica reprobada</b> despues del cierre de areas; si su promedio del a&ntilde;o es igual o mayor a
    <input type="text" onkeypress="return justNumbers(event);"
id="p323_1" max="5.0" min="1.0" style="text-align: center; border-radius: 10px;width: 45px;" name="10_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $valoresP34[9];?>"> el sistema reemplazara la calificacion del desempe&ntilde;o bajo del area reprobada por la calificacion
    <input type="text" onkeypress="return justNumbers(event);"
id="p323_2" max="5.0" min="1.0" style="text-align: center; border-radius: 10px; width: 45px;" name="11_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $valoresP34[10];?>"> y el estado academico del estudiante pasara a <b>promovido</b> al grado siguiente.
    </p>
    </div>
    </div>
    </div>
    </div>
    </div>
<script>
function validar323() {
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="S")
{
 document.getElementById("p323_1").disabled = false;
document.getElementById("p323_2").disabled = false;
document.getElementById("p323_3").disabled = false;
document.getElementById("p323_4").disabled = false;
}
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="N")
{
 document.getElementById("p323_1").disabled = true;
document.getElementById("p323_2").disabled = true;
document.getElementById("p323_3").disabled = true;
document.getElementById("p323_4").disabled = true;
}}
addEvent('load', validar323); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
 <!-- ---------------------------------------- TABLA 4 ----------------------------------- -->
        <div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
    <!-- Section 4 -->
      <div class="accordion_inwerds">
        <div class="acc_headerfgd">&Iacute;tem 5</div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
          <b>Aplica</b>
          <select class="sele_mul" name="9_<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
            <option value="S" <?php if (!(strcmp("S", $valoresP34[8]))) {echo "selected=\"selected\"";} ?>>Si</option>
            <option value="N" <?php if (!(strcmp("N", $valoresP34[8]))) {echo "selected=\"selected\"";} ?>>No</option>
            </select><br><br><br>
    <p style="text-align: left;margin: 0px;border: solid 2px #CE6767;border-radius: 10px;padding: 12px;">
    <b>4.)</b> El estudiante que durante <b style="color: red;">dos</b> (2) a&ntilde;os consecutivos, registre la <b>reprobaci&oacute;n</b> del <b style="color: red;">MISMO</b> grado, el sistema no le asignar&aacute;
    cupo para el a&ntilde;o siguiente.
    </p>
</div>
</div>
</div>
</div>
</div>
		<?php
		break;
/*----------------------------------------------------------case 112-----------------------------------------------------------------------------*/
		case 113:
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
					$fecha_inicio_113 = $array_parametro[1];
					$fecha_final_113 = $array_parametro[2];
				?>
			 <table  width="90%" >
				 <tr>
				 <td><b>Aplica</b>
				 <select class="sele_mul" onclick="validar37()"name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
				  </td>
				 </tr>
				 </table>
	<div class="container_demohrvszv">
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd">&Iacute;tem</div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
<table>                 <tr>
                   	<td>Intervalo</td>
                     <td>
						<table>
							<tr style="text-align: center;" class="fila1">
								<td  colspan="2">Fecha de Inicio</td>
								<td  colspan="2">Fecha Terminaci&oacute;n</td>
							</tr>
							<tr>
								<td><input name="periodo_fecha_inicio_113" id="periodo_fecha_inicio113" type="text" size="8" readonly="readonly" value="<?php echo $fecha_inicio_113; ?>" /></td>
								<td ><button name="pi_<?php echo $row_configuracion['conf_nombre']; ?>" id="pi_<?php echo $row_configuracion['conf_nombre']; ?>" value="">.</button></td>
							  	<td ><input name="periodo_fecha_final_113" id="periodo_fecha_final113" type="text" size="8" readonly="readonly" value="<?php echo $fecha_final_113; ?>" /></td>
							  	<td><button name="pf_<?php echo $row_configuracion['conf_nombre']; ?>" id="pf_<?php echo $row_configuracion['conf_nombre']; ?>" value="">.</button></td>
							</tr>
						</table>
                      </td>
				 </tr>
			</table>
</div>
</div>
</div>
</div>
</div>
<script>
function validar37() {
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="S")
{
 document.getElementById("pi_<?php echo $row_configuracion['conf_nombre']; ?>").disabled = false;
document.getElementById("pf_<?php echo $row_configuracion['conf_nombre']; ?>").disabled = false;
}
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="N")
{
 document.getElementById("pi_<?php echo $row_configuracion['conf_nombre']; ?>").disabled = true;
document.getElementById("pf_<?php echo $row_configuracion['conf_nombre']; ?>").disabled = true;
}
}
	addEvent('load', validar37); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
		<?php
		break;
		case 112:
				$estado = '';
				if(strpos($row_configuracion['conf_valor'],"$")>0)
				{
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
					$estado = $array_parametro[1];
				}
				else
					$parametro = $row_configuracion['conf_valor'];
				?>
			 <table  width="90%" >
				 <tr>
				 <td><b>Aplica</b>
				 <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
				  </td>
				 </tr>
			</table>
		<?php
		break;
/*----------------------------------------case 116----------------------------------------------------------------------------------------*/
		case 116:
				$estado = '';
				if(strpos($row_configuracion['conf_valor'],"$")>0)
				{
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
					$estado = $array_parametro[1];
				}
				else
					$parametro = $row_configuracion['conf_valor'];
				?>
			 <table  width="90%" >
				 <tr>
				 <td><b>Aplica</b>
				 <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
  </td>
	 </tr>
	</table>
<?php
		break;
		case 155:
		$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$reasignacionRepro_ = $array_parametro[1];
					$reasignacionRepro2_ = $array_parametro[2];
					$reasignacionRepro4_ = $array_parametro[3];
					$reasignacionRepro5_ = $array_parametro[4];
					$reasignacionRepro6_ = $array_parametro[5];
					$reasignacionRepro7_ = $array_parametro[6];
		$valoresP43 = explode("$",$row_configuracion['conf_valor']);
			$e1111= $valoresP43[5];
	$parametro = $array_parametro[0];
?>
<div class="container_demohrvszv">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Si aplica defina:</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
		<table>
				<tr>
			 		<td> <input id="pp19_111" type="radio" onclick="vvalidar1544()"<?php if (!(strcmp("A", $array_parametro[1]))) {echo "checked=checked";} ?> value="A" name="valorplanirecu_<?php echo $row_configuracion['conf_nombre']; ?>"/> </td> <td>Numero De Areas:</td>
			 		<td></td><td><input id="pp19_222" type="text" style="width: 10%;"onkeypress="return justNumbers(event);"  value="<?php echo $array_parametro[2]; ?>" name="reasignacionRepro3_<?php echo $row_configuracion['conf_nombre']; ?>"/> </td></tr>
<tr>
			 		<td> <input id="pp19_333" type="radio" onclick="vvalidar15444()"<?php if (!(strcmp("B", $array_parametro[1]))) {echo "checked=checked";} ?> value="B" name="valorplanirecu_<?php echo $row_configuracion['conf_nombre']; ?>"/> </td> <td>Todas Las Area:</td>
				</tr>
			</table>
</div>
</div>
</div>
</div>
</div>
<script>
function vvalidar15444() {
 {
 	document.getElementById("pp19_222").disabled = true;
 }
}
function vvalidar1544() {
 {
 	document.getElementById("pp19_222").disabled = false;
 }
}
</script>
<?php
break;
case 121:
				$array_parametro = explode("$",$row_configuracion['conf_valor']);
				$aplica_promoanti_121 = $array_parametro[0];
				$nota_minima_121 = $array_parametro[1];
				$aplica_nota_comportamiento_121 = $array_parametro[2];
				$nota_comportamiento_121 = $array_parametro[3];
				$aplica_asistencia_121 = $array_parametro[4];
				$porcentaje_asistencia_121 = $array_parametro[5];
				$no_negativos_121 = $array_parametro[6];
				$aplica_periodo_121 = $array_parametro[7];
				$fecha_inicio_121 = $array_parametro[8];
				$fecha_final_121 = $array_parametro[9];
				$estado = $array_parametro[10];
				$paraQueAreas = $array_parametro[12];
				$areasReprobadas=$array_parametro[13];
				$todasAreas=$array_parametro[14];
				$demasAreas=$array_parametro[15];
				$areas_R=$array_parametro[16];
				$todas_A=$array_parametro[17];
				$demas_A=$array_parametro[18];
			    $aplica_promoanti_1211 = $array_parametro[20];
			    $no_negativos_1211 = $array_parametro[21];
			    $aplica_asistencia_1211 = $array_parametro[22];
	            $fecha_inicio_121_2 = $array_parametro[25];
				$fecha_final_121_2 = $array_parametro[26];
				$fecha_inicio_121_2_1 = $array_parametro[27];
				$fecha_final_121_2_1 = $array_parametro[28];
				$parametro = $row_configuracion['conf_valor'];
	/*
	echo $row_configuracion['conf_valor']."<br/><br/>";
echo "p0 => ".$array_parametro[0]."<br>"  ;
echo "p1 => ".$array_parametro[1]."<br>"  ;
echo "p2 => ".$array_parametro[2]."<br>"  ;
echo "p3 => ".$array_parametro[3]."<br>"  ;
echo "p4 => ".$array_parametro[4]."<br>"  ;
echo "p5 => ".$array_parametro[5]."<br>"  ;
echo "p6 => ".$array_parametro[6]."<br>"  ;
echo "p7 => ".$array_parametro[7]."<br>"  ;
echo "p8 => ".$array_parametro[8]."<br>"  ;
echo "p9=> ".$array_parametro[9]."<br>"  ;
echo "p10 => ".$array_parametro[10]."<br>";
echo "p11 => ".$array_parametro[11]."<br>";
echo "p12 => ".$array_parametro[12]."<br>";
echo "p13 => ".$array_parametro[13]."<br>";
echo "p14=> ".$array_parametro[14]."<br>";
echo "p15=> ".$array_parametro[15]."<br>";
echo "p16=> ".$array_parametro[16]."<br>";
echo "p17=> ".$array_parametro[17]."<br>";
echo "p18 => ".$array_parametro[18]."<br>";
echo "p19 => ".$array_parametro[19]."<br>";
echo "p20 => ".$array_parametro[20]."<br>";
echo "p21 => ".$array_parametro[21]."<br>";
echo "p22 => ".$array_parametro[22]."<br>";
*/
		 ?>
		  <div id="epnehpep1">
		  Este parametro no esta habilitado por el parametro 135
		  </div>
		 <div id="c53121">
			<div class="container_demohrvszv">
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd"><strong> Aplica solo si se hace al final del periodo </strong></div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
        <table  width="90%" style="border:1px solid #666666; margin-top:5px;">
<tr>
                  	<td colspan="2">
                  		<select class="sele_mul" id="criterio1211" name="aDirectiva" onclick="validar491211()">
                  			<option value="T1" <?php if (strpos($row_configuracion['conf_valor'],"T1") == true)  {echo "selected=\"selected\"";} ?>>Si</option>
                  			<option value="F1" <?php if (strpos($row_configuracion['conf_valor'],"F1") == true)  {echo "selected=\"selected\"";} ?>>No</option>
                  		</select></tr>
               <tr class="fila1">
     <td colspan="2" align="center"><div align="justify" class="text" ><b>Definici&oacute;n del periodo de recolecci&oacute;n de datos a tener en cuenta para la promoci&oacute;n anticipada a estudiantes reprobados </b></div></td>
                  </tr>
                  		<tr>
                  		 <td>Intervalo</td>
                     <td>
						<table >
							<tr style="text-align: center;" class="fila1">
								<td  >Fecha de Inicio</td><td style="background:#E7F1FE;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
								<td  >Fecha Terminaci&oacute;n</td>
							</tr>
							<?php
	$select_periodo="SELECT *  FROM `periodo_academicos` limit 0,1";
	$query_periodo=mysql_query($select_periodo, $link) or die($select_periodo);
	$row_configuracionperiodos = mysql_fetch_assoc($query_periodo);
	$num_registros_periodos=mysql_num_rows($query_periodo);
							 ?>
							<tr>
			<td><input name="periodo_fecha_inicio_121" id="periodo_fecha_inicio1211" type="text" size="8" readonly="readonly" value="<?php echo $row_configuracionperiodos['inicio_ing_notas']; ?>" /></td>
<td style="background:#E7F1FE;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td ><input name="periodo_fecha_final_121" id="periodo_fecha_final1211" type="text" size="8" readonly="readonly" value="<?php echo $row_configuracionperiodos['fin_ing_notas']; ?>" /></td>
							</tr>
						</table>
                      </td>
                  </tr>
               <tr class="fila1">
                     <td colspan="2" align="center"><div align="justify" class="text" id="nota_minima_exigida_121"><b><?php echo ConsultarTextoCriterio('nota_minima_exigida_121'); ?></b></div></td>
                  </tr>
                  <tr>
                  	<td></td>
                     <td>
                     <select class="sele_mul" style="width:150px;" name="aplica_promoanti_1211" id="aplica_promoanti_1211" onchange="ActivaCampo('aplica_promoanti_121','nota_minima_121')">
                        <option value="11" <?php if (!(strcmp("11", $aplica_promoanti_1211))) {echo "selected=\"selected\"";} ?>>No aplica</option>
                        <option value="21" <?php if (!(strcmp("21", $aplica_promoanti_1211))) {echo "selected=\"selected\"";} ?>>Si aplica</option>
                      </select>
                      </td>
				 </tr>
				    <tr>
						<td></td>
				 		<td><input  id="radiop_1" name="areas_R" onclick="valida_check_param_121_1()" type="checkbox" value="1" <?php if  (strlen($areas_R)>0) {echo "checked='checked'";} ?> >Por cada &aacute;rea.</td>
				 	</tr>
	                   <tr>
                     <td></td><td><strong>&Oacute;</strong></td>
				 </tr>
				     <tr>
				      	<td></td>
						<td><input  id="radiop_2" name="demas_A" onclick="valida_check_param_121_2()" type="checkbox" value="3" <?php if (strlen($demas_A)>0) {echo "checked='checked'";} ?> >Por promedio del primer periodo.</td>
					</tr>
                 <tr>
                     <td>Calificaci&oacute;n</td>
                     <td><input onkeypress="return justNumbers(event);" style="border-radius: 10px; width: 25%;" id="nota_minima_1211" name="nota_minima_121" value=""/></td>
				 </tr>
               <tr class="fila1">
                     <td colspan="2" align="center"><div align="justify" class="text" id="calificacion_comportamiento_121"><b><?php echo ConsultarTextoCriterio('calificacion_comportamiento_121'); ?></b></div></td>
                 </tr>
  	  <tr>
                 <td>	</td>
                     <td >
                     <select class="sele_mul" name="aplica_nota_comportamiento_121" id="aplica_nota_comportamiento_121" onchange="ActivaCampo('aplica_nota_comportamiento_121','nota_comportamiento_121')">
                        <option value="S" <?php if (!(strcmp("S", $aplica_nota_comportamiento_121))) {echo "selected=\"selected\"";} ?>>Aplica</option>
                        <option value="N" <?php if (!(strcmp("N", $aplica_nota_comportamiento_121))) {echo "selected=\"selected\"";} ?>>No Aplica</option>
                      </select>
                      </td>
				 </tr>
                 <tr>
                 	<td>Calificaci&oacute;n: </td>
                     <td><input onkeypress="return justNumbers(event);" style="border-radius: 10px; width: 25%;" id="nota_comportamiento_121" name="nota_comportamiento_121" value="<?php echo $nota_comportamiento_121; ?>" /></td>
				 </tr>
				   <tr class="fila1">
                     <td colspan="2" align="center"><b><?php echo ConsultarTextoCriterio('no_negativos_121'); ?></b></td>
                 </tr>
                 <tr>
                     <td colspan="2">
                     <select class="sele_mul" name="no_negativos_1211" id="no_negativos_1211">
                        <option value="S1" <?php if (!(strcmp("S1", $no_negativos_1211))) {echo "selected=\"selected\"";} ?>>Aplica</option>
                        <option value="N1" <?php if (!(strcmp("N1", $no_negativos_1211))) {echo "selected=\"selected\"";} ?>>No aplica</option>
                      </select>
                      </td>
				 </tr>
                 <tr class="fila1">
                     <td colspan="2" align="center"><div align="justify" class="text" id="asistencia_periodo_121"><b><?php echo ConsultarTextoCriterio('asistencia_periodo_121'); ?></b></div></td>
                 </tr>
                 <tr>
                     <td></td>
                     <td>
                     <select class="sele_mul" name="aplica_asistencia_1211" id="aplica_asistencia_1211" onchange="ActivaCampo('aplica_asistencia_121','porcentaje_asistencia_121')">
                        <option value="S1" <?php if (!(strcmp("S1", $aplica_asistencia_1211))) {echo "selected=\"selected\"";} ?>>Aplica</option>
                        <option value="N1" <?php if (!(strcmp("N1", $aplica_asistencia_1211))) {echo "selected=\"selected\"";} ?>>No Aplica</option>
                      </select>
                      </td>
				 </tr>
                 <tr>
                 	<td>Porcentaje: </td>
                     <td><input onkeypress="return justNumbers(event);" style="border-radius: 10px; width: 25%;" id="porcentaje_asistencia_1211" name="porcentaje_asistencia_121" value="<?php echo $porcentaje_asistencia_121;?>" />%</td>
				 </tr>
				   <tr class="fila1">
     <td colspan="2" align="center"><div align="justify" class="text" ><b>Definici&oacute;n del periodo de tiempo para el ingreso de calificaciones en la planilla promoci&oacute;n anticipada para estudiantes reprobados  </b></div></td>
                  </tr>
  <tr>
                     <td>Intervalo</td>
                     <td>
						<table >
							<tr style="text-align: center;" class="fila1">
								<td  colspan="2">Fecha de inicio</td>
								<td  colspan="2">Fecha terminaci&oacute;n</td>
							</tr>
							<tr>
<td><input name="periodo_fecha_inicio_1211_2_1" id="periodo_fecha_inicio121_2_1" type="text" size="8" readonly="readonly" value="<?php echo $fecha_inicio_121_2_1; ?>" /></td>
<td ><button name="pi_2_1<?php echo $row_configuracion['conf_nombre']; ?>" id="pi_2_1<?php echo $row_configuracion['conf_nombre']; ?>" value="">.</button></td>
<td ><input name="periodo_fecha_final_1211_2_1" id="periodo_fecha_final121_2_1" type="text" size="8" readonly="readonly" value="<?php echo $fecha_final_121_2_1; ?>" /></td>
<td><button name="pf_2_1<?php echo $row_configuracion['conf_nombre']; ?>" id="pf_2_1<?php echo $row_configuracion['conf_nombre']; ?>" value="">.</button></td>
							</tr>
	</table>
                      </td>
                  </tr>
				 </table> </div></div></div></div></div>	<div class="container_demohrvszv">
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd"><strong>Aplica solo si se promueve antes de finalizar al periodo</strong></div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
        <table  width="90%" style="border:1px solid #666666; margin-top:5px;">
 <tr>
                  	<td colspan="2">
                  		<select class="sele_mul" id="criterio121" name="aDirectiva2" >
                  			<option value="T2" <?php if (strpos($row_configuracion['conf_valor'],"T2") == true)  {echo "selected=\"selected\"";} ?>>Si</option>
                  			<option value="F2" <?php if (strpos($row_configuracion['conf_valor'],"F2") == true)  {echo "selected=\"selected\"";} ?>>No</option>
                  		</select></tr>
  <tr class="fila1">
     <td colspan="2" align="center"><div align="justify" class="text" ><b>Definici&oacute;n del periodo de tiempo para la  recolecci&oacute;n de datos a tener en cuenta para la promoci&oacute;n anticipada a estudiantes reprobados </b></div></td>
                  </tr>
                     <td>Intervalo</td>
                     <td>
						<table >
							<tr style="text-align: center;" class="fila1">
								<td  colspan="2">Fecha de Inicio</td>
								<td  colspan="2">Fecha Terminaci&oacute;n</td>
							</tr>
							<tr>
								<td><input name="periodo_fecha_inicio_1211" id="periodo_fecha_inicio121" type="text" size="8" readonly="readonly" value="<?php echo $fecha_inicio_121; ?>" /></td>
								<td ><button name="pi_<?php echo $row_configuracion['conf_nombre']; ?>" id="pi_<?php echo $row_configuracion['conf_nombre']; ?>" value="">.</button></td>
							  	<td ><input name="periodo_fecha_final_1211" id="periodo_fecha_final121" type="text" size="8" readonly="readonly" value="<?php echo $fecha_final_121; ?>" /></td>
							  	<td><button name="pf_<?php echo $row_configuracion['conf_nombre']; ?>" id="pf_<?php echo $row_configuracion['conf_nombre']; ?>" value="">.</button></td>
							</tr>
						</table>
                      </td>
                  </tr>
                      <tr class="fila1">
                     <td colspan="2" align="center"><div align="justify" class="text" id="nota_minima_exigida_121"><b><?php echo ConsultarTextoCriterio('nota_minima_exigida_121'); ?></b></div></td>
                  </tr> <tr>
                  	<td></td>
                     <td>
                     <select class="sele_mul" style="width:150px;" name="aplica_promoanti_121" id="aplica_promoanti_121" onchange="ActivaCampo('aplica_promoanti_121','nota_minima_121')">
                        <option value="12" <?php if (!(strcmp("12", $aplica_promoanti_121))) {echo "selected=\"selected\"";} ?>>No aplica</option>
                        <option value="22" <?php if (!(strcmp("22", $aplica_promoanti_121))) {echo "selected=\"selected\"";} ?>>Si aplica</option>
                      </select>
                      </td>
				 </tr>
      <tr>
				      	<td></td>
						<td><input name="todas_A" id="checkbox_121_2" type="checkbox" value="2" <?php if  (strlen($todas_A)>0) {echo "checked='checked'";} ?> >Por &aacute;rea Reprobada.</td>
					 </tr>
     <tr>
                     <td>Calificaci&oacute;n</td>
                     <td><input onkeypress="return justNumbers(event);" style="border-radius: 10px; width: 25%;" id="nota_minima_121" name="nota_minima_121" value="<?php echo $nota_minima_121; ?>" /></td>
				 </tr>
                <tr class="fila1">
                     <td colspan="2" align="center"><b><?php echo ConsultarTextoCriterio('no_negativos_121'); ?></b></td>
                 </tr>
                 <tr>
                     <td colspan="2">
                     <select class="sele_mul" name="no_negativos_121" id="no_negativos_121">
                        <option value="S2" <?php if (!(strcmp("S2", $no_negativos_121))) {echo "selected=\"selected\"";} ?>>Aplica</option>
                        <option value="N2" <?php if (!(strcmp("N2", $no_negativos_121))) {echo "selected=\"selected\"";} ?>>No aplica</option>
                      </select>
                      </td>
				 </tr>
			   <tr class="fila1">
                     <td colspan="2" align="center"><div align="justify" class="text" id="asistencia_periodo_121"><b><?php echo ConsultarTextoCriterio('asistencia_periodo_121'); ?></b></div></td>
                 </tr>
                 <tr>
                     <td></td>
                     <td>
                     <select class="sele_mul" name="aplica_asistencia_121" id="aplica_asistencia_121" onchange="ActivaCampo('aplica_asistencia_121','porcentaje_asistencia_121')">
                        <option value="S2" <?php if (!(strcmp("S2", $aplica_asistencia_121))) {echo "selected=\"selected\"";} ?>>Aplica</option>
                        <option value="N2" <?php if (!(strcmp("N2", $aplica_asistencia_121))) {echo "selected=\"selected\"";} ?>>No Aplica</option>
                      </select>
                      </td>
				 </tr>
                 <tr>
                 	<td>Porcentaje: </td>
                     <td><input onkeypress="return justNumbers(event);" style="border-radius: 10px; width: 25%;" id="porcentaje_asistencia_121" name="porcentaje_asistencia_121" value="<?php echo $porcentaje_asistencia_121;?>" />%</td>
				 </tr>
				   <tr class="fila1">
     <td colspan="2" align="center"><div align="justify" class="text" ><b>Definici&oacute;n del periodo de tiempo para el ingreso de calificaciones en la planilla promoci&oacute;n anticipada para estudiantes reprobados  </b></div></td>
                  </tr>
       <tr>
                     <td>Intervalo</td>
                     <td>
						<table >
							<tr style="text-align: center;" class="fila1">
								<td  colspan="2">Fecha de inicio</td>
								<td  colspan="2">Fecha terminaci&oacute;n</td>
							</tr>
							<tr>
<td><input name="periodo_fecha_inicio_1211_2" id="periodo_fecha_inicio121_2" type="text" size="8" readonly="readonly" value="<?php echo $fecha_inicio_121_2; ?>" /></td>
<td ><button name="pi_2<?php echo $row_configuracion['conf_nombre']; ?>" id="pi_2<?php echo $row_configuracion['conf_nombre']; ?>" value="">.</button></td>
<td ><input name="periodo_fecha_final_1211_2" id="periodo_fecha_final121_2" type="text" size="8" readonly="readonly" value="<?php echo $fecha_final_121_2; ?>" /></td>
<td><button name="pf_2<?php echo $row_configuracion['conf_nombre']; ?>" id="pf_2<?php echo $row_configuracion['conf_nombre']; ?>" value="">.</button></td>
							</tr>
						</table>
                      </td>
                  </tr>
			</table></div></div></div></div></div></div>
<script type="text/javascript">
	function valida_check_param_121_1(){   document.getElementById('radiop_2').checked = false;}
function valida_check_param_121_2(){   document.getElementById('radiop_1').checked = false;}
</script>
      <script>
function validar491211() {
if(document.getElementById('criterio1211').value=="T1"){
document.getElementById("periodo_fecha_final121").value = "";
document.getElementById("periodo_fecha_inicio121").value = "";
document.getElementById("periodo_fecha_final1211").value = "<?php echo $row_configuracionperiodos['fin_ing_notas']; ?>";
document.getElementById("periodo_fecha_inicio1211").value = "<?php echo $row_configuracionperiodos['inicio_ing_notas']; ?>";
document.getElementById("porcentaje_asistencia_1211").value = "<?php echo $porcentaje_asistencia_121; ?>";
document.getElementById("porcentaje_asistencia_121").value = "";
document.getElementById("no_negativos_1211").disabled = false;
document.getElementById("aplica_asistencia_1211").disabled = false;
document.getElementById("checkbox_121_2").disabled = true;
document.getElementById("criterio121").value ="F2";
document.getElementById("no_negativos_121").disabled = true;
document.getElementById("nota_minima_1211").value = "<?php echo $nota_minima_121; ?>";
document.getElementById("nota_minima_121").value = "";
document.getElementById("nota_minima_121").disabled = true;
document.getElementById("nota_minima_1211").disabled = false;
document.getElementById("nota_comportamiento_121").value = "<?php echo $nota_comportamiento_121; ?>";
document.getElementById("nota_comportamiento_121").disabled = false;
document.getElementById("nota_comportamiento_121").disabled = false;
document.getElementById("porcentaje_asistencia_1211").disabled = false;
document.getElementById("porcentaje_asistencia_121").disabled = true;
document.getElementById("aplica_promoanti_121").disabled = true;
document.getElementById("aplica_promoanti_1211").disabled = false;
document.getElementById("aplica_nota_comportamiento_121").disabled = false;
document.getElementById("aplica_asistencia_121").disabled = true;
document.getElementById("radiop_1").disabled = false;
document.getElementById("radiop_2").disabled = false;
document.getElementById("pi_<?php echo $row_configuracion['conf_nombre']; ?>").disabled = true;
document.getElementById("pf_<?php echo $row_configuracion['conf_nombre']; ?>").disabled = true;
document.getElementById("pi_2<?php echo $row_configuracion['conf_nombre']; ?>").disabled = true;
document.getElementById("pf_2<?php echo $row_configuracion['conf_nombre']; ?>").disabled = true;
document.getElementById("pi_2_1<?php echo $row_configuracion['conf_nombre']; ?>").disabled = false;
document.getElementById("pf_2_1<?php echo $row_configuracion['conf_nombre']; ?>").disabled = false;
document.getElementById("periodo_fecha_final121_2").value = "";
document.getElementById("periodo_fecha_inicio121_2").value = "";
document.getElementById("periodo_fecha_final121_2_1").value = "<?php echo $fecha_final_121_2_1; ?>";
document.getElementById("periodo_fecha_inicio121_2_1").value = "<?php echo $fecha_inicio_121_2_1; ?>";
}
if(document.getElementById('criterio1211').value=="F1")
{
document.getElementById("pi_2_1<?php echo $row_configuracion['conf_nombre']; ?>").disabled = true;
document.getElementById("pf_2_1<?php echo $row_configuracion['conf_nombre']; ?>").disabled = true;
document.getElementById("periodo_fecha_final121_2_1").value = "";
document.getElementById("periodo_fecha_inicio121_2_1").value = "";
document.getElementById("periodo_fecha_final121_2").value = "<?php echo $fecha_final_121_2; ?>";
document.getElementById("periodo_fecha_inicio121_2").value = "<?php echo $fecha_inicio_121_2; ?>";
document.getElementById("periodo_fecha_inicio1211").value = "";
document.getElementById("periodo_fecha_final1211").value = "";
document.getElementById("periodo_fecha_final121").value = "<?php echo $fecha_final_121; ?>";
document.getElementById("periodo_fecha_inicio121").value = "<?php echo $fecha_inicio_121; ?>";
document.getElementById("porcentaje_asistencia_1211").value = "";
document.getElementById("porcentaje_asistencia_121").value = "<?php echo $porcentaje_asistencia_121; ?>";
document.getElementById("nota_comportamiento_121").disabled = true;
document.getElementById("nota_comportamiento_121").value = "";
document.getElementById("aplica_promoanti_1211").disabled = true;
document.getElementById("porcentaje_asistencia_121").disabled = false;
document.getElementById("no_negativos_1211").disabled = true;
document.getElementById("aplica_asistencia_1211").disabled = true;
document.getElementById("checkbox_121_2").disabled = false;
document.getElementById("criterio121").value ="T2";
document.getElementById("no_negativos_121").disabled = false;
document.getElementById("nota_minima_1211").value = "";
document.getElementById("nota_minima_121").value = "<?php echo $nota_minima_121; ?>";
document.getElementById("nota_minima_1211").disabled = true;
document.getElementById("nota_minima_121").disabled = false;
document.getElementById("nota_comportamiento_121").disabled = true;
document.getElementById("radiop_1").disabled = true;
document.getElementById("radiop_2").disabled = true;
document.getElementById("porcentaje_asistencia_1211").disabled = true;
document.getElementById("aplica_promoanti_121").disabled = false;
document.getElementById("aplica_nota_comportamiento_121").disabled = true;
document.getElementById("aplica_asistencia_121").disabled = false;
document.getElementById("pi_<?php echo $row_configuracion['conf_nombre']; ?>").disabled = false;
document.getElementById("pf_<?php echo $row_configuracion['conf_nombre']; ?>").disabled = false;
document.getElementById("pi_2<?php echo $row_configuracion['conf_nombre']; ?>").disabled = false;
document.getElementById("pf_2<?php echo $row_configuracion['conf_nombre']; ?>").disabled = false;
}
}
  addEvent('load', validar491211); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
		<?php
		break;
	case 129:
/*
<option value="S" <?php if (!(strcmp("S", $crii))) {echo "selected=\"selected\"";} ?>>Si</option>
			<option value="N" <?php if (!(strcmp("N", $crii))) {echo "selected=\"selected\"";} ?>>No</option>*/
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
	                $cri = $array_parametro[0];
					$valorEspecifico = $array_parametro[3];
					$valorEspecifico2 = $array_parametro[4];
					$valorEspecifico3 = $array_parametro[5];
		?>
		<table>
			<tr> <td></td><td><select class="sele_mul" name="valor" id="parametro50129"onclick="valida45();">
				<option value="S" <?php if (!(strcmp("S", $cri))) {echo "selected=\"selected\"";} ?>>Aplica</option>
				<option value="N" <?php if (!(strcmp("N", $cri))) {echo "selected=\"selected\"";} ?>>No Aplica</option>
			</select></td></tr>
		</table>
		<table>
				<tr>
					<td>
						<label>
							<input type="radio" id="rad51q_1"onclick="determinar1291();"<?php  if (strpos($row_configuracion['conf_valor'],"17")==true) {echo "checked=checked";} ?> value="17" name="input_<?php echo $row_configuracion['conf_nombre']; ?>" >Para el a&ntilde;o lectivo con reconsideraci&oacute;n exclusiva del docente<br>
						</label>
					</td>
				</tr>
				<tr>
					<td>
						<label>
							<input type="radio" id="rad51q_2"onclick="determinar1292();"<?php if (strpos($row_configuracion['conf_valor'],"18")==true) {echo "checked='checked'";} ?> value="18" name="input_<?php echo $row_configuracion['conf_nombre']; ?>">Para el a&ntilde;o lectivo con reconsideraci&oacute;n de la comisi&oacute;n de Evaluaci&oacute;n y Promoci&oacute;n al cierre de las &aacute;reas<br>
						</label>
					</td>
				</tr>
				<tr>
					<td>
						<label>
							<input type="radio" onclick="determinar1293();"<?php if (strpos($row_configuracion['conf_valor'],"19")==true) {echo "checked='checked'";} ?> value="19" name="input_<?php echo $row_configuracion['conf_nombre']; ?>" id="input_<?php echo $row_configuracion['conf_nombre']; ?>">Para el a&ntilde;o siguiente con planilla de actividades plan de apoyo<br>
						</label>
					</td>
				</tr>
				<tr>
					<td>
						<label>
							Total &aacute;reas perdidas para ingresar a la planilla de reconsideraci&oacute;n:</label><input onkeypress="return justNumbers(event);" id="rad51k" style="width: 5%;height: 12px;" name="valorEspecifico3" value="<?php echo $valorEspecifico3; ?>">
						</label>
					</td>
				</tr>
		</table>
			<table border="1" style="width: 370px;text-align: center;margin-top: 15px;">
				<tr><td colspan="2">Nota m&aacute;xima permitida en la planilla de reconsideraci&oacute;n para Asignaturas de las &aacute;reas Fundamentales Obligatorias.</td></tr>
				<tr><td><label>Desempe&ntilde;o Nal.</label></td><td><label>Rango num&eacute;rico</label></td></tr>
				<tr style="text-align: left;">
					<td>
						<label>
							<input type="radio" id="rad51a" <?php  if (strpos($row_configuracion['conf_valor'],"a")==true) {echo "checked=checked";} ?> value="a" name="valorAsignaturas2_<?php echo $row_configuracion['conf_nombre']; ?>" >Superior<br>
						</label>
					</td>
					<td>
						<label>
							<input type="radio" id="rad51b"<?php if (strpos($row_configuracion['conf_valor'],"b")==true) {echo "checked='checked'";} ?> value="b" name="valorAsignaturas2_<?php echo $row_configuracion['conf_nombre']; ?>">Hasta 5.0<br>
						</label>
					</td>
				</tr>
				<tr style="text-align: left;">
					<td>
						<label>
							<input type="radio" id="rad51c"<?php if (strpos($row_configuracion['conf_valor'],"c")==true) {echo "checked='checked'";} ?> value="c" name="valorAsignaturas2_<?php echo $row_configuracion['conf_nombre']; ?>">Alto<br>
						</label>
					</td>
					<td>
						<label>
							<input type="radio" id="rad51d"<?php if (strpos($row_configuracion['conf_valor'],"d")==true) {echo "checked='checked'";} ?> value="d" name="valorAsignaturas2_<?php echo $row_configuracion['conf_nombre']; ?>">Hasta 4.0<br>
						</label>
					</td>
				</tr>
				<tr style="text-align: left;">
					<td>
						<label>
							<input type="radio" id="rad51e"<?php if (strpos($row_configuracion['conf_valor'],"e")==true) {echo "checked='checked'";} ?> value="e" name="valorAsignaturas2_<?php echo $row_configuracion['conf_nombre']; ?>">Basico<br>
						</label>
					</td>
					<td>
						<label>
							<input type="radio" id="rad51f"<?php if (strpos($row_configuracion['conf_valor'],"f")==true) {echo "checked='checked'";} ?> value="f" name="valorAsignaturas2_<?php echo $row_configuracion['conf_nombre']; ?>">Hasta 3.0<br>
						</label>
					</td>
				</tr>
				<tr style="text-align: left;">
					<td>
						<label>
							<input type="radio" type="radio" id="rad51g"<?php if (strpos($row_configuracion['conf_valor'],"g")==true) {echo "checked='checked'";} ?> value="g" name="valorAsignaturas2_<?php echo $row_configuracion['conf_nombre']; ?>">Bajo<br>
						</label>
					</td>
					<td>
						<label>
							<input type="radio" <?php if (strpos($row_configuracion['conf_valor'],"h")==true) {echo "checked='checked'";} ?> value="h" name="valorAsignaturas2_<?php echo $row_configuracion['conf_nombre']; ?>" >Valor espec&iacute;fico:<br>
							<input style="width: 15%;" id="valorEspecifico2" name="valorEspecifico2" value="<?php echo $valorEspecifico2; ?>">
						</label>
					</td>
				</tr>
			</table>
			</br>
			<!--/////////NOTA MAXIMA PERMITIDA EN LA PLAMILLA DE RECONSIDERACION PARA LAS ASIGNATURAS DE LA TECNICA///////////////////// -->
				<table border="1" style="width: 370px;text-align: center;margin-top: 15px;">
				<tr><td colspan="2">Nota m&aacute;xima permitida en la planilla de reconsideraci&oacute;n para las <b>asignaturas de la t&eacute;cnica</b></td></tr>
				<tr><td><label>Desempe&ntilde;o Nal.</label></td><td><label>Rango num&eacute;rico</label></td></tr>
				<tr style="text-align: left;">
					<td>
						<label>
							<input type="radio" id="rad51i"<?php if (strpos($row_configuracion['conf_valor'],"i")==true) {echo "checked='checked'";} ?> value="i" name="valorAsignaturas_<?php echo $row_configuracion['conf_nombre']; ?>" >Superior<br>
						</label>
					</td>
					<td>
						<label>
							<input type="radio" id="rad51j"<?php if (strpos($row_configuracion['conf_valor'],"j")==true) {echo "checked='checked'";} ?> value="j" name="valorAsignaturas_<?php echo $row_configuracion['conf_nombre']; ?>">Hasta 5.0<br>
						</label>
					</td>
				</tr>
				<tr style="text-align: left;">
					<td>
						<label>
							<input type="radio" id="rad51k"<?php if (strpos($row_configuracion['conf_valor'],"k")==true) {echo "checked='checked'";} ?> value="k" name="valorAsignaturas_<?php echo $row_configuracion['conf_nombre']; ?>">Alto<br>
						</label>
					</td>
					<td>
						<label>
							<input type="radio" id="rad51l"<?php if (strpos($row_configuracion['conf_valor'],"l")==true) {echo "checked='checked'";} ?> value="l" name="valorAsignaturas_<?php echo $row_configuracion['conf_nombre']; ?>">Hasta 4.0<br>
						</label>
					</td>
				</tr>
				<tr style="text-align: left;">
					<td>
						<label>
							<input type="radio" id="rad51m"<?php if (strpos($row_configuracion['conf_valor'],"m")==true) {echo "checked='checked'";} ?> value="m" name="valorAsignaturas_<?php echo $row_configuracion['conf_nombre']; ?>">Basico<br>
						</label>
					</td>
					<td>
						<label>
							<input type="radio" id="rad51n"<?php if (strpos($row_configuracion['conf_valor'],"n")==true) {echo "checked='checked'";} ?> value="n" name="valorAsignaturas_<?php echo $row_configuracion['conf_nombre']; ?>">Hasta 3.0<br>
						</label>
					</td>
				</tr>
				<tr style="text-align: left;">
					<td>
						<label>
							<input type="radio" id="rad51o"<?php if (strpos($row_configuracion['conf_valor'],"o")==true) {echo "checked='checked'";} ?> value="o" name="valorAsignaturas_<?php echo $row_configuracion['conf_nombre']; ?>">Bajo<br>
						</label>
					</td>
					<td>
						<label>
							<input type="radio" onclick="activaAreas();"<?php if (strpos($row_configuracion['conf_valor'],"p")==true) {echo "checked='checked'";} ?> value="p" name="valorAsignaturas_<?php echo $row_configuracion['conf_nombre']; ?>">Valor espec&iacute;fico:<br>
							<input style="width: 15%;" id="valorEspecifico" name="valorEspecifico" value="<?php echo $valorEspecifico; ?>" >
						</label>
					</td>
				</tr>
			</table>
			</br>
<script>
function valida45() {
if(document.getElementById('parametro50129').value=="S")
{
 document.getElementById("rad51q_1").disabled = false;
 document.getElementById("rad51q_2").disabled = false;
  document.getElementById("input_<?php echo $row_configuracion['conf_nombre']; ?>").disabled = false;
 document.getElementById("rad51a").disabled = false;
 document.getElementById("rad51b").disabled = false;
 document.getElementById("rad51c").disabled = false;
 document.getElementById("rad51d").disabled = false;
 document.getElementById("rad51e").disabled = false;
 document.getElementById("rad51f").disabled = false;
 document.getElementById("rad51g").disabled = false;
 document.getElementById("rad51i").disabled = false;
 document.getElementById("rad51j").disabled = false;
 document.getElementById("rad51k").disabled = false;
 document.getElementById("rad51l").disabled = false;
 document.getElementById("rad51m").disabled = false;
 document.getElementById("rad51n").disabled = false;
 document.getElementById("rad51o").disabled = false;
  document.getElementById("valorEspecifico2").disabled = false;
  document.getElementById("valorEspecifico").disabled = false;
}
if(document.getElementById('parametro50129').value=="N")
{
  document.getElementById("rad51q_1").disabled = true;
 document.getElementById("rad51q_2").disabled = true;
  document.getElementById("input_<?php echo $row_configuracion['conf_nombre']; ?>").disabled = true;
 document.getElementById("rad51a").disabled = true;
 document.getElementById("rad51b").disabled = true;
 document.getElementById("rad51c").disabled = true;
 document.getElementById("rad51d").disabled = true;
 document.getElementById("rad51e").disabled = true;
 document.getElementById("rad51f").disabled = true;
 document.getElementById("rad51g").disabled = true;
 document.getElementById("rad51i").disabled = true;
 document.getElementById("rad51j").disabled = true;
 document.getElementById("rad51k").disabled = true;
 document.getElementById("rad51l").disabled = true;
 document.getElementById("rad51m").disabled = true;
 document.getElementById("rad51n").disabled = true;
 document.getElementById("rad51o").disabled = true;
 document.getElementById("valorEspecifico2").disabled = true;
 document.getElementById("valorEspecifico").disabled = true;
}
}
addEvent('load', valida45); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
<script type="text/javascript">
function determinar1291() {
    document.getElementById("rad51k").disabled = true;
}
</script>
<script type="text/javascript">
function determinar1292() {
    document.getElementById("rad51k").disabled = true;
}
</script>
<script type="text/javascript">
function determinar1293() {
    document.getElementById("rad51k").disabled = false;
}
</script>
		<?php
		break;
case 120: //REQUISITOS SIE 120
		
				$array_parametro = explode("$",$row_configuracion['conf_valor']);
			    $cri = $array_parametro[0];
				$nota_minima_120 = $array_parametro[1];
				$aplica_nota_comportamiento = $array_parametro[2];
				$nota_comportamiento = $array_parametro[3];
				$aplica_asistencia = $array_parametro[4];
				$porcentaje_asistencia = $array_parametro[5];
				$no_negativos = $array_parametro[6];				
				$num_periodo = $array_parametro[7];
				$vigenciarpI = $array_parametro[9];
				$vigenciarpF = $array_parametro[10];
				$prueba_I=$array_parametro[11];
				$obtener_V=$array_parametro[12];
				$calificacion_P=$array_parametro[13];
				$certificados_E=$array_parametro[14];
				$e1=$array_parametro[17];
				$e2=$array_parametro[18];
				$e3=$array_parametro[19];
				$e4=$array_parametro[20];
				$e5=$array_parametro[21];
				$e6=$array_parametro[22];
				$e7=$array_parametro[23];
				$e8=$array_parametro[24];
				$e9=$array_parametro[25];
				$e10=$array_parametro[26];
				$e11=$array_parametro[27];
				$e12=$array_parametro[28];
				$aplica_nota_comportamiento2 = $array_parametro[29];
				$aplica_nota_comportamiento3 = $array_parametro[30];
				$primero_S = $array_parametro[32];
				$Segundo_T = $array_parametro[33];
				$Tercero_C = $array_parametro[34];
				$Cuarto_Q = $array_parametro[35];
				$Quinto_S = $array_parametro[36];
				$Sexto_S = $array_parametro[37];
				$Semptimo_O = $array_parametro[38];
				$Octavo_N = $array_parametro[39];
				$Noveno_D = $array_parametro[40];
				$Decimo_O = $array_parametro[41];
				$valorNota = $array_parametro[42];
			    $aplica_promoanti_120_area_promedio = $array_parametro[43];
	    $transcicion_P = $array_parametro[44];
				$valorNota1 = $array_parametro[45];
			    $aplica_promoanti_120_area_promedio1 = $array_parametro[46];
				 $parametro = $row_configuracion['conf_valor'];
				 //print_r($array_parametro);
		?>
<script type="text/javascript" src="js/highslide/highslide-with-html.js"></script>
<script type="text/javascript" src="js/highslide/highslide-full.js"></script>
<link rel="stylesheet" type="text/css" href="js/highslide/highslide.css" />
<script type="text/javascript">
	hs.showCredits = false;
	hs.align = 'right';
	hs.wrapperClassName = 'draggable-header';
    hs.graphicsDir = 'js/highslide/graphics/';
    hs.outlineType = 'rounded-white';
</script>
<div id="texcpnf1">
Este parametro no esta habilitado por el parametro 135
</div>
<div id="c51120">
	<div class="container_demohrvszv">
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd">&Iacute;tem</div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
        <table  width="90%" style="border:1px solid #666666; margin-top:5px;">		
        <tr class="fila1">
	        	<td colspan="5" style="text-align: left;"><div align="justify"   id="nota_minima_exigida_120"><b>Vigencia para registro de procesos:</b></div></td>
	        </tr>
	        <tr>
	          <script>
function valida44() {
if(document.getElementById('criterio120_<?php echo $row_configuracion["conf_nombre"]; ?>').value=="S")
{
document.getElementById("bb_<?php echo $row_configuracion['conf_nombre']; ?>").disabled = false;
document.getElementById("cc_<?php echo $row_configuracion['conf_nombre']; ?>").disabled = false;
document.getElementById("nota_minima_120").disabled = false;
document.getElementById("nota_comportamiento").disabled = false;
document.getElementById("porcentaje_asistencia").disabled = false;
document.getElementById("v120").disabled = false;
document.getElementById("v121").disabled = false;
document.getElementById("v122").disabled = false;
document.getElementById("v123").disabled = false;
document.getElementById("v124").disabled = false;
document.getElementById("v125").disabled = false;
document.getElementById("v126").disabled = false;
document.getElementById("v127").disabled = false;
document.getElementById("v128").disabled = false;
document.getElementById("v129").disabled = false;
document.getElementById("v130").disabled = false;
document.getElementById("v131").disabled = false;
document.getElementById("v132").disabled = false;
document.getElementById("v133").disabled = false;
document.getElementById("aplica_promoanti_120").disabled = false;
document.getElementById("aplica_nota_comportamiento").disabled = false;
document.getElementById("no_negativos").disabled = false;
document.getElementById("aplica_asistencia").disabled = false;
document.getElementById("aplica_nota_comportamiento3").disabled = false;
document.getElementById("validarradio501201").disabled = false;
document.getElementById("validarradio501202").disabled = false;
document.getElementById("validarradio501203").disabled = false;
document.getElementById("validarradio501204").disabled = false;
document.getElementById("validarradio501205").disabled = false;
document.getElementById("validarradio501206").disabled = false;
document.getElementById("validarradio501207").disabled = false;
document.getElementById("num_periodo").disabled = false;
document.getElementsByClassName("areas").disabled = false;
}
if(document.getElementById('criterio120_<?php echo $row_configuracion["conf_nombre"]; ?>').value=="N")
{
document.getElementById("bb_<?php echo $row_configuracion['conf_nombre']; ?>").disabled = true;
document.getElementById("cc_<?php echo $row_configuracion['conf_nombre']; ?>").disabled = true;
document.getElementById("nota_minima_120").disabled = true;
document.getElementById("nota_comportamiento").disabled = true;
document.getElementById("porcentaje_asistencia").disabled = true;
document.getElementById("v120").disabled = true;
document.getElementById("v121").disabled = true;
document.getElementById("v122").disabled = true;
document.getElementById("v123").disabled = true;
document.getElementById("v124").disabled = true;
document.getElementById("v125").disabled = true;
document.getElementById("v126").disabled = true;
document.getElementById("v127").disabled = true;
document.getElementById("v128").disabled = true;
document.getElementById("v129").disabled = true;
document.getElementById("v130").disabled = true;
document.getElementById("v131").disabled = true;
document.getElementById("v132").disabled = true;
document.getElementById("v133").disabled = true;
document.getElementById("aplica_promoanti_120").disabled = true;
document.getElementById("aplica_nota_comportamiento").disabled = true;
document.getElementById("no_negativos").disabled = true;
document.getElementById("aplica_asistencia").disabled = true;
document.getElementById("aplica_nota_comportamiento3").disabled = true;
document.getElementById("obtener_V").disabled = true;
document.getElementById("validarradio501201").disabled = true;
document.getElementById("validarradio501202").disabled = true;
document.getElementById("validarradio501203").disabled = true;
document.getElementById("validarradio501204").disabled = true;
document.getElementById("validarradio501205").disabled = true;
document.getElementById("validarradio501206").disabled = true;
document.getElementById("validarradio501207").disabled = true;
document.getElementById("num_periodo").disabled = true;
document.getElementsByClassName("areas").disabled = true;
}
}
addEvent('load', valida44); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
	        	<td colspan="5">
	        		<table>
  					<td>
	        		<br><label><b>Aplica </b></label> 
				  	 <select class="sele_mul" name="criterio120_<?php echo $row_configuracion['conf_nombre']; ?>" id="criterio120_<?php echo $row_configuracion['conf_nombre']; ?>"onclick="valida44()">
			<option value="S" <?php if (!(strcmp("S", $cri['conf_valor']))) {echo "selected=\"selected\"";} ?>>Si</option>
			<option value="N" <?php if (!(strcmp("N", $cri['conf_valor']))) {echo "selected=\"selected\"";} ?>>No</option>
                </select> </td>
	        			<tr class="fila1" style="text-align: center;"><td>Desde</td><td>Hasta</td></tr>
					<tr><td><input name="periodo_fecha_inicio_120" id="periodo_fecha_inicio" type="text" size="8" readonly="readonly" value="<?php echo $vigenciarpI; ?>" />
					<button name="bb_<?php echo $row_configuracion['conf_nombre']; ?>" id="bb_<?php echo $row_configuracion['conf_nombre']; ?>" value="">.</button></td>
				  	<td><input name="periodo_fecha_final_120" id="periodo_fecha_final" type="text" size="8" readonly="readonly" value="<?php echo $vigenciarpF; ?>" />
				  	<button name="cc_<?php echo $row_configuracion['conf_nombre']; ?>" id="cc_<?php echo $row_configuracion['conf_nombre']; ?>" value="">.</button></td></tr>
	        		</table> <br>
	        	</td>
	        </tr>
                 <tr class="fila1">
                     <td colspan="5" align="center"><div align="justify"  id="nota_minima_exigida_120"><b><?php echo ConsultarTextoCriterio('nota_minima_exigida_120'); ?></b></div></td>
                  </tr>
                  <tr>
                  	<td>
                     <select class="sele_mul" style="width:150px;" name="aplica_promoanti_120" id="aplica_promoanti_120" onchange="ActivaCampo('aplica_promoanti_120','nota_minima_120')">
                        <option value="1" <?php if (!(strcmp("1", $aplica_promoanti_120_area_promedio))) {echo "selected=\"selected\"";} ?>>Por Cada &Aacute;rea</option>
                        <option value="2" <?php if (!(strcmp("2", $aplica_promoanti_120_area_promedio))) {echo "selected=\"selected\"";} ?>>Promedio Primer Periodo</option>
                        <option value="0" <?php if (!(strcmp("0", $aplica_promoanti_120_area_promedio))) {echo "selected=\"selected\"";} ?>>No Aplica</option>
                      </select>
                      </td>
				 </tr>
                 <tr>
                     <td>Calificaci&oacute;n
                     <input id="nota_minima_120" onkeypress="return justNumbers(event);"name="nota_minima_120" value="<?php echo $nota_minima_120; ?>" style="border-radius: 10px; width: 25%;" /></td>
				 </tr>
                 <tr class="fila1">
                     <td colspan="5" align="center"><div align="justify"  id="calificacion_comportamiento_120"><b><?php echo ConsultarTextoCriterio('calificacion_comportamiento_120'); ?></b></div></td>
                 </tr>
                 <tr>
                     <td>
                     <select class="sele_mul" name="aplica_nota_comportamiento" id="aplica_nota_comportamiento" onchange="ActivaCampo('aplica_nota_comportamiento','nota_comportamiento')">
                        <option value="S" <?php if (!(strcmp("S", $aplica_nota_comportamiento))) {echo "selected=\"selected\"";} ?>>Aplica</option>
                        <option value="N" <?php if (!(strcmp("N", $aplica_nota_comportamiento))) {echo "selected=\"selected\"";} ?>>No Aplica</option>
                      </select>
                      </td>
				 </tr>
                 <tr>
                 	<td> Calificaci&oacute;n: 
                     <input onkeypress="return justNumbers(event);"style="border-radius: 10px; width: 25%;" id="nota_comportamiento" name="nota_comportamiento" value="<?php echo $nota_comportamiento; ?>" /></td>
				 </tr>
                 <tr class="fila1">
                     <td colspan="5" align="center"><div align="justify"  id="asistencia_periodo_120"><b><?php echo ConsultarTextoCriterio('asistencia_periodo_120'); ?></b></div></td>
                 </tr>
                 <tr>
                     <td>
                     <select class="sele_mul" name="aplica_asistencia" id="aplica_asistencia" onchange="ActivaCampo('aplica_asistencia','porcentaje_asistencia')">
                        <option value="S" <?php if (!(strcmp("S", $aplica_asistencia))) {echo "selected=\"selected\"";} ?>>Aplica</option>
                        <option value="N" <?php if (!(strcmp("N", $aplica_asistencia))) {echo "selected=\"selected\"";} ?>>No Aplica</option>
                      </select>
                      </td>
				 </tr>
                 <tr>
                 	<td>Porcentaje:&nbsp;&nbsp;&nbsp; 
                    <input onkeypress="return justNumbers(event);"style="border-radius: 10px; width: 25%;" id="porcentaje_asistencia" name="porcentaje_asistencia" value="<?php echo $porcentaje_asistencia;?>" />%</td>
				 </tr>
                 <tr class="fila1">
                     <td colspan="5" align="center"><div align="justify"  id="no_negativos_120"><b><?php echo ConsultarTextoCriterio('no_negativos_120'); ?></b></div></td>
                 </tr>
                 <tr>
                     <td>
                     <select class="sele_mul" name="no_negativos" id="no_negativos">
                        <option value="S" <?php if (!(strcmp("S", $no_negativos))) {echo "selected=\"selected\"";} ?>>Aplica</option>
                        <option value="N" <?php if (!(strcmp("N", $no_negativos))) {echo "selected=\"selected\"";} ?>>No Aplica</option>
                      </select>
                      </td>
				 </tr>                 
                 <tr class="fila1">
  <td colspan="5" align="center"><div align="justify"  id="inicio_periodo_promocion_120"><b><?php echo ConsultarTextoCriterio('inicio_periodo_promocion_120'); ?></b></div></td>
                 </tr>
                 <tr>
                     <td>Periodo: 
                     <select class="sele_mul" name="num_periodo" id="num_periodo">
                        <option value="2" <?php if ($num_periodo == 2) {echo "selected=\"selected\"";} ?>>2</option>
                      </select>
                      </td>
				 </tr>                 
                   <tr class="fila1">
                     <td colspan="5" align="center"><div align="justify"  id="inicio_periodo_promocion_120"><b>En qu&eacute; grados se dara la promocion anticipada</b></div></td>
                 </tr>
         		<td><table>
                   <tr>
                 	<td> </td>
                 </tr>
                  <tr>
                 	<td style="width:50%;" >De Transici&oacute;n a Primero:  <td><input name="transicion_P" id="v119" type="checkbox" value="355" <?php if (strlen($transcicion_P)>0) {echo "checked='checked'";} ?>  type="checkbox"></td></td>
                 </tr>
                 <tr>
                 	<td style="width:50%;" >De Primero a Segundo :  <td><input name="primero_S" id="v120"type="checkbox" value="1" <?php if (strlen($primero_S)>0) {echo "checked='checked'";} ?>  type="checkbox"></td></td>
                 </tr>
                   <tr>
                 	<td style="width:50%;" >De Segundo a Tercero : <td><input name="Segundo_T" id="v121"type="checkbox" value="2" <?php if (strlen($Segundo_T)>0) {echo "checked='checked'";} ?> type="checkbox"></td></td>
                 </tr>
                   <tr>
                 	<td style="width:50%;" >De Tercero a Cuarto :  <td><input name="Tercero_C" id="v122"type="checkbox" value="3" <?php if (strlen($Tercero_C)>0) {echo "checked='checked'";} ?> type="checkbox"></td></td>
                 </tr>
                   <tr>
                 	<td style="width:50%;" >De Cuarto a Quinto :  <td><input name="Cuarto_Q" id="v123"type="checkbox" value="4" <?php if (strlen($Cuarto_Q)>0) {echo "checked='checked'";} ?> type="checkbox"></td></td>
                 </tr>
                   <tr>
                 	<td style="width:50%;" >De Quinto a Sexto :  <td><input name="Quinto_S" id="v124"type="checkbox" value="5" <?php if (strlen($Quinto_S)>0) {echo "checked='checked'";} ?> type="checkbox"></td></td>
                 </tr>
                   <tr>
                 	<td style="width:50%;" >De Sexto a Septimo :  <td><input name="Sexto_S" id="v125"type="checkbox" value="6" <?php if (strlen($Sexto_S)>0) {echo "checked='checked'";} ?> type="checkbox"></td></td>
                 </tr>
                   <tr>
                 	<td style="width:50%;" >De Septimo a Octavo :  <td><input name="Semptimo_O" id="v126"type="checkbox" value="7" <?php if (strlen($Semptimo_O)>0) {echo "checked='checked'";} ?> type="checkbox"></td></td>
                 </tr>
                   <tr>
                 	<td style="width:50%;" >De Octavo a Noveno : <td><input name="Octavo_N" id="v127"type="checkbox" value="8" <?php if (strlen($Octavo_N)>0) {echo "checked='checked'";} ?> type="checkbox"></td></td>
                 </tr>
                   <tr>
                 	<td style="width:20%;" >De Noveno a Decimo :  <td><input name="Noveno_D" id="v128"type="checkbox" value="9" <?php if (strlen($Noveno_D)>0) {echo "checked='checked'";} ?> type="checkbox"></td></td>
                 </tr>
                  <tr>
                 	<td style="width:20%;" >De Decimo a Once :  <td><input name="Decimo_O" id="v129"type="checkbox" value="10" <?php if (strlen($Decimo_O)>0) {echo "checked='checked'";} ?> type="checkbox"></td></td>
                 </tr>
                  <tr class="fila1">
                     <td colspan="5" align="center"><div align="justify"  id="inicio_periodo_promocion_120"><b>PRUEBA DE SUFICIENCIA</b></div></td>
                 </tr>  
                 <td>
	        		<br><label><b>Aplica </b></label> 
				  	  <select class="sele_mul" name="aplica_nota_comportamiento3" id="aplica_nota_comportamiento3" onclick="validar501202()">
                        <option value="S" <?php if (!(strcmp("S", $aplica_nota_comportamiento3))) {echo "selected=\"selected\"";} ?>>Si</option>
                        <option value="N" <?php if (!(strcmp("N", $aplica_nota_comportamiento3))) {echo "selected=\"selected\"";} ?>>No</option>
                </select> </td>
	<tr class="fila1" style="text-align: center;"><td>Desde</td><td>Hasta</td></tr>
					<tr><td><input name="periodo_fecha_inicio_120planilla" id="periodo_fecha_inicioplanilla" type="text" size="8" readonly="readonly" value="<?php echo $array_parametro[45]; ?>" />
					<button name="bbplanilla_<?php echo $row_configuracion['conf_nombre']; ?>" id="bbplanilla_<?php echo $row_configuracion['conf_nombre']; ?>" value="">.</button></td>
				  	<td><input name="periodo_fecha_final_120planilla" id="periodo_fecha_finalplanilla" type="text" size="8" readonly="readonly" value="<?php echo $array_parametro[46]; ?>" />
				  	<button name="ccplanilla_<?php echo $row_configuracion['conf_nombre']; ?>" id="ccplanilla_<?php echo $row_configuracion['conf_nombre']; ?>" value="">.</button></td></tr>
                   <tr>
                     <td><input type="radio" id="v130"<?php if (!(strcmp("1", $array_parametro[15]))) {echo "checked='checked'";} ?>onclick="javascript:determinarcampo();" value="1" name="valorDesempate2_<?php echo $row_configuracion['conf_nombre']; ?>">En todas las &aacute;reas</td>
                 </tr>
                 <tr>
                     <td><input type="radio" id="v131"<?php if (!(strcmp("2", $array_parametro[15]))) {echo "checked='checked'";} ?>onclick="javascript:determinarcampo();" value="2" name="valorDesempate2_<?php echo $row_configuracion['conf_nombre']; ?>">Solo &aacute;reas t&eacute;cnicas </td>
         </tr>
          <tr>
                     <td><input type="radio" id="v132"<?php if (!(strcmp("3", $array_parametro[15]))) {echo "checked='checked'";} ?>onclick="javascript:determinarcampo();"  value="3" name="valorDesempate2_<?php echo $row_configuracion['conf_nombre']; ?>">En las &aacute;rea de:  <br>
                      <a href='javascript:void(0)' onClick="return hs.htmlExpand(this, { headingText: 'Banco De Areas'})">Banco de Areas<img src="images/iconos/folio.gif" width="24" /></a>
								<div class='highslide-maincontent' align="center" style="width: 1053px;height: 638px;">
								<iframe scrolling="no" width="100%" height="800px" src="./select_banco_areas_parametros.php"></iframe>
								</div>
</td>
         </tr> 
       </table>
      </td>
	          <script>
function validar501202() {
if(document.getElementById('aplica_nota_comportamiento3').value=="S")
{
document.getElementById("validarradio501203").checked = false;
document.getElementById("validarradio501201").disabled = false;
document.getElementById("validarradio501202").disabled = false;
document.getElementById("operraccionnn").disabled = false;
document.getElementById("resulltaaddoo").disabled = false;
document.getElementById("obtener_V").disabled = false;
document.getElementById("v130").disabled = false;
document.getElementById("v131").disabled = false;
document.getElementById("v132").disabled = false;
document.getElementById("v133").disabled = false;
document.getElementById("p50120i1").disabled = false;
document.getElementById("p50120i2").disabled = false;
document.getElementById("p50120i3").disabled = false;
document.getElementById("p50120i4").disabled = false;
document.getElementById("p50120i5").disabled = false;
document.getElementById("p50120i6").disabled = false;
document.getElementById("p50120i7").disabled = false;
document.getElementById("p50120i8").disabled = false;
document.getElementById("p50120i9").disabled = false;
document.getElementById("p50120i10").disabled = false;
document.getElementById("p50120i11").disabled = false;
document.getElementById("p50120i12").disabled = false;
document.getElementById("p50120i1").disabled = false;
document.getElementById("p50120i2").disabled = false;
document.getElementById("p50120i3").disabled = false;
document.getElementById("p50120i4").disabled = false;
document.getElementById("p50120i5").disabled = false;
document.getElementById("p50120i6").disabled = false;
document.getElementById("p50120i7").disabled = false;
document.getElementById("p50120i8").disabled = false;
document.getElementById("p50120i9").disabled = false;
document.getElementById("p50120i10").disabled = false;
document.getElementById("p50120i11").disabled = false;
document.getElementById("p50120i12").disabled = false;
}
if(document.getElementById('aplica_nota_comportamiento3').value=="N")
{
document.getElementById("operraccionnn").disabled = true;
document.getElementById("resulltaaddoo").disabled = true;
document.getElementById("operraccionnn").value = "";
document.getElementById("resulltaaddoo").value = "";
document.getElementById("obtener_V").disabled = true;
document.getElementById("obtener_V").checked = false;
document.getElementById("validarradio501201").disabled = true;
document.getElementById("validarradio501202").disabled = true;
document.getElementById("validarradio501201").checked = false;
document.getElementById("validarradio501202").checked = false;
document.getElementById("validarradio501203").disabled = false;
document.getElementById("v133").value = "";
document.getElementById("validarradio501203").checked = true;
document.getElementById("v130").disabled = true;
document.getElementById("v131").disabled = true;
document.getElementById("v132").disabled = true;
document.getElementById("v133").disabled = true;
document.getElementById("p50120i1").disabled = true;
document.getElementById("p50120i2").disabled = true;
document.getElementById("p50120i3").disabled = true;
document.getElementById("p50120i4").disabled = true;
document.getElementById("p50120i5").disabled = true;
document.getElementById("p50120i6").disabled = true;
document.getElementById("p50120i7").disabled = true;
document.getElementById("p50120i8").disabled = true;
document.getElementById("p50120i9").disabled = true;
document.getElementById("p50120i10").disabled = true;
document.getElementById("p50120i11").disabled = true;
document.getElementById("p50120i12").disabled = true;
document.getElementById("p50120i1").disabled = true;
document.getElementById("p50120i2").disabled = true;
document.getElementById("p50120i3").disabled = true;
document.getElementById("p50120i4").disabled = true;
document.getElementById("p50120i5").disabled = true;
document.getElementById("p50120i6").disabled = true;
document.getElementById("p50120i7").disabled = true;
document.getElementById("p50120i8").disabled = true;
document.getElementById("p50120i9").disabled = true;
document.getElementById("p50120i10").disabled = true;
document.getElementById("p50120i11").disabled = true;
document.getElementById("p50120i12").disabled = true;
}
}
addEvent('load', validar501202); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
         <tr><td>
<!--PARAMETRO 44 CONSULTA CODIGO DE LAS MATERIAS-->
          <table style="width: 95%;">
          <tr>
              <div>
   <table>
     <tr><td><input type = "text"  id="p50120i1" onkeypress="return justNumbers(event);"class="areas" style="width:90%;" value="<?php echo $e1; ?>" name="E1_"></td><td><input type = "text" id="p50120i2" onkeypress="return justNumbers(event);"class="areas" style="width:90%;" value="<?php echo $e2; ?>" name="E2_"></td><td><input type = "text" id="p50120i3" onkeypress="return justNumbers(event);"class="areas" style="width:90%;" value="<?php echo $e3; ?>" name="E3_"></td><td><input type = "text" id="p50120i4" onkeypress="return justNumbers(event);"class="areas" style="width:90%;" value="<?php echo $e4; ?>" name="E4_"></td></tr>
              <tr><td><input type = "text"  id="p50120i5" onkeypress="return justNumbers(event);"class="areas" style="width:90%;" value="<?php echo $e5; ?>" name="E5_"></td><td><input type = "text" id="p50120i6" onkeypress="return justNumbers(event);"class="areas" style="width:90%;" value="<?php echo $e6; ?>" name="E6_"></td><td><input type = "text"  id="p50120i7" onkeypress="return justNumbers(event);"class="areas" style="width:90%;" value="<?php echo $e7; ?>" name="E7_"></td><td><input type = "text" id="p50120i8" onkeypress="return justNumbers(event);"class="areas" style="width:90%;" value="<?php echo $e8; ?>" name="E8_"></td></tr>
              <tr><td><input type = "text"  id="p50120i9" onkeypress="return justNumbers(event);"class="areas" style="width:90%;" value="<?php echo $e9; ?>" name="E9_"></td><td><input type = "text"  id="p50120i10" onkeypress="return justNumbers(event);"class="areas" style="width:90%;" value="<?php echo $e10; ?>" name="E10_"></td><td><input type = "text"  id="p50120i11" onkeypress="return justNumbers(event);"class="areas" style="width:90%;" value="<?php echo $e11; ?>" name="E11_"></td><td><input type = "text" id="p50120i12" onkeypress="return justNumbers(event);"class="areas" style="width:90%;" value="<?php echo $e12; ?>" name="E12_"></td></tr>
      </div>
          </p>
        </tr>
         </tr>   </table> </td></tr>
         <script type="text/javascript">
 // permite determinar el estado de los campos de la seccion asignaturas a tener en cuenta en la solucion del parametro 44
 function setDisabledCampos(campos, valor){ // obtengo el conjunto de campos (array) y el valor (false o true)
        for(var j = 0; j < campos.length; j++){ // recorro el conjunto de campos
          campos[j].disabled = valor; // le asigno el valor que me pasaron por parametro a cada campo del conjunto de campos
        }
      }
      function determinarcampo( ){
        const EN_LAS_AREAS_DE = "3"; // determina el valor de la opcion que se debe elegir para activar los campos que este caso es areas especificas en la solucion del parametro 44
        // obtiene el conjunto de input type radio que contienen las diferentes opciones de la seccion de areas a tener en cuenta de la solucion del parametro 159
        var opciones = document.getElementsByName( "valorDesempate2_<?php echo $row_configuracion['conf_nombre']; ?>" ); 
        for( var i = 0; i < opciones.length; i++ ){ // recorro el conjunto de input type radio que contienen las opciones
          if(opciones[i].checked == true){ // determino cual opcion esta seleccionada
            campos = document.getElementsByClassName("areas"); // obtengo el conjunto de los 12 campos de las asignaturas especificas
            if(opciones[i].value == EN_LAS_AREAS_DE){ // determino si la opcion seleccionada es la de en las areas de
              setDisabledCampos(campos,false); // activo los 12 campos para que ingrese las areas especificas
            }else{ // la opcion seleccionada no es la de areas especificas
              setDisabledCampos(campos,true); // desactivo los 12 campos de areas especificas
            } // termino else
          } // termino if
        } // termino for
      }
        function determinarCamposAlCargarr(){ 
        const en_las_areas_de = "3"; // determinia el valor del input type radio que representa la opcion de areas epecificas
        var opcion = "<?php echo $array_parametro[15]?>"; // obtengo el valor guardado en la BD que determina la opcion que fue seleccionada y guardad
        var campos = document.getElementsByClassName("areas"); // obtengo el conjunto de los 12 campos de las areas especificas
        if (opcion == en_las_areas_de){ // si el valor traido es igual al valor que identifica la opcion areas especificas
          setDisabledCampos(campos, false); // activo los 12 campos
        }else{ // si el valor tradio es diferente fue porque se selecciono otra opcion diferente a areas especificas
          setDisabledCampos(campos, true); // desactivo los 12 campos
        }
      }
      		function seRepiteAreaa(){
				var campos = document.getElementsByClassName("areas"); // esta variable almacena el arreglo de los campos
				// determina si se repite algun area y si es asi muestra una alerta
				for(var i = 0; i < campos.length - 1; i++){ 
					for(var j = i + 1; j < campos.length; j++){
						if(campos[i].value == campos[j].value && campos[i].value != 0){
							sweetAlert("ERROR", "Revise que en el parametro 44 no se repitan areas", "warning");
							return false;
						}
					}
				}
			}
      </script>
				 <tr>
				 	<td><input type="checkbox" id="obtener_V"name="obtener_V" type="checkbox" value="2" <?php if  (strlen($obtener_V)>0) {echo "checked='checked'";} ?>> Obtener una valoraci&oacute;n M&iacute;nima de <input id="v133"style="width: 10%;" value="<?php echo $valorNota; ?>" name="valorNota_"  > en todas las &aacute;reas del grado que est&aacute; cursando</td>
				 </tr>
				 <tr class="fila1">
                     <td colspan="5" align="center"><div align="center"  id="inicio_periodo_promocion_120"><b>PROCEDIMIENTO PARA REGISTRO DE CALIFICACIONES <br> <br>  PARA CERTIFICADOS</b></div></td>
                 </tr>
                 <tr>
				 	<td><input type="radio" id="validarradio501201"<?php if (!(strcmp("A", $array_parametro[16]))) {echo "checked=checked";} ?> value="A" name="valorDesempate3_<?php echo $row_configuracion['conf_nombre']; ?>"> El certificado de estudios para la PROMOCI&Oacute;N ANTICIPADA saldr&aacute; del promedio de las calificaciones registradas en la prueba de suficiencia y la calificaci&oacute;n registrada en el periodo anterior a la promoci&oacute;n (1er periodo). 
<!--Acevedo-->
                    <?php		
					
  						    $title = "Vista Previa";
							$url = ($row_config_planilla['conf_pla_subje'] == 1)?"images/ModProAnti/certificadoPROMANTI.png":"images/ModProAnti/certificadoPROMANTI.png";
							?>
							<a href="javascript:void(0)"  title="<?php echo $title;?>" onclick="return hs.expand(this, {src:'<?php echo $url;?>', wrapperClassName: 'borderless floating-caption', dimmingOpacity: 0.75, align: 'center'})">
							Vista Previa
							</a>
							&nbsp;&nbsp;&nbsp;&nbsp;
				  			<?php				
							$url = ($row_config_planilla['conf_pla_subje'] == 1)?"images/ModProAnti/certificadoPROMANTI.png":"images/ModProAnti/certificadoPROMANTI.png";									
						?>
<!--Acevedo-->
				 	</td>
            
                    
				 </tr>
				 <tr>
				 	<td><input type="radio" id="validarradio501202"<?php if (!(strcmp("B", $array_parametro[16]))) {echo "checked=checked";} ?> value="B" name="valorDesempate3_<?php echo $row_configuracion['conf_nombre']; ?>"> El certificado de estudios de la PROMOCI&Oacute;N ANTICIPADA saldr&aacute; con los resultados de las calificaciones obtenidas en la prueba de SUFICIENCIA y las obtenidas en las &aacute;reas fundamentales obligatorias del grado actual. 		&nbsp;		&nbsp;		&nbsp;
                   
                   <!--Acevedo-->
                    <?php		
					
  						    $title = "Vista Previa";
							$url = ($row_config_planilla['conf_pla_subje'] == 1)?"images/ModProAnti/certificadoPROMANTI2.png":"images/ModProAnti/certificadoPROMANTI2.png";
							?>
							<a href="javascript:void(0)"  title="<?php echo $title;?>" onclick="return hs.expand(this, {src:'<?php echo $url;?>', wrapperClassName: 'borderless floating-caption', dimmingOpacity: 0.75, align: 'center'})">
							Vista Previa
							</a>
							&nbsp;&nbsp;&nbsp;&nbsp;
				  			<?php				
							$url = ($row_config_planilla['conf_pla_subje'] == 1)?"images/ModProAnti/certificadoPROMANTI2.png":"images/ModProAnti/certificadoPROMANTI2.png";									
						?>
<!--Acevedo-->
				 	</td>
				 </tr>
					<tr>
				 	<td><input type="radio" id="validarradio501203"<?php if (!(strcmp("C", $array_parametro[16]))) {echo "checked=checked";} ?> value="C" name="valorDesempate3_<?php echo $row_configuracion['conf_nombre']; ?>"> El certificado de estudios de la promoci&oacute;n anticipada por m&eacute;ritos, tomar&aacute; las calificaciones del registro efectuado durante el primer periodo del a&ntilde;o lectivo escolar.
                       <!--Acevedo-->
                    <?php		
					
  						    $title = "Vista Previa";
							$url = ($row_config_planilla['conf_pla_subje'] == 1)?"images/ModProAnti/certificadoPROMANTI3.png":"images/ModProAnti/certificadoPROMANTI3.png";
							?>
							<a href="javascript:void(0)"  title="<?php echo $title;?>" onclick="return hs.expand(this, {src:'<?php echo $url;?>', wrapperClassName: 'borderless floating-caption', dimmingOpacity: 0.75, align: 'center'})">
							Vista Previa
							</a>
							&nbsp;&nbsp;&nbsp;&nbsp;
				  			<?php				
							$url = ($row_config_planilla['conf_pla_subje'] == 1)?"images/ModProAnti/certificadoPROMANTI3.png":"images/ModProAnti/certificadoPROMANTI3.png";									
						?>
<!--Acevedo-->
				 	</td>
				 </tr>
				  <tr class="fila1">
                     <td colspan="5" align="center"><div align="center"  id="inicio_periodo_promocion_120"><b>PARA CALIFICACIONES DEL PERIODO</b></div></td>
                 </tr>
				 <tr>
				 	<td><input type="radio" id="validarradio501204"<?php if (!(strcmp("A", $array_parametro[31]))) {echo "checked=checked";} ?> value="A" name="calificacion_A_<?php echo $row_configuracion['conf_nombre']; ?>"> Las calificaciones del primer periodo en el nuevo grado al cual ser&aacute; promovido el estudiante, corresponder&aacute;n a las registradas en el primer periodo del grado anterior y en el caso de no haber correspondencia de pensum, esas &aacute;reas ser&aacute;n HOMOLOGADAS tomando como base, las que se le registren en el segundo periodo del grado al cual ha sido promovido.
                        
                      <!--Acevedo-->
                    <?php		
					
  						    $title = "Vista Previa";
							$url = ($row_config_planilla['conf_pla_subje'] == 1)?"images/ModProAnti/boletinesPROMANTI.png":"images/ModProAnti/boletinesPROMANTI.png";
							?>
							<a href="javascript:void(0)"  title="<?php echo $title;?>" onclick="return hs.expand(this, {src:'<?php echo $url;?>', wrapperClassName: 'borderless floating-caption', dimmingOpacity: 0.75, align: 'center'})">
							Vista Previa
							</a>
							&nbsp;&nbsp;&nbsp;&nbsp;
				  			<?php				
							$url = ($row_config_planilla['conf_pla_subje'] == 1)?"images/ModProAnti/boletinesPROMANTI.png":"images/ModProAnti/boletinesPROMANTI.png";									
						?>
<!--Acevedo-->
				 	</td>
				 </tr>
				 <tr>
				 	<td><input type="radio" id="validarradio501205"<?php if (!(strcmp("B", $array_parametro[31]))) {echo "checked=checked";} ?> value="B" name="calificacion_A_<?php echo $row_configuracion['conf_nombre']; ?>"> Las calificaciones del primer periodo en el nuevo grado al cual ser&aacute; promovido el estudiante, corresponder&aacute;n a las registradas en el primer periodo del grado anterior.
    <!--Acevedo-->
                    <?php		
					
  						    $title = "Vista Previa";
							$url = ($row_config_planilla['conf_pla_subje'] == 1)?"images/ModProAnti/boletinesPROMANTI.png":"images/ModProAnti/boletinesPROMANTI.png";
							?>
							<a href="javascript:void(0)"  title="<?php echo $title;?>" onclick="return hs.expand(this, {src:'<?php echo $url;?>', wrapperClassName: 'borderless floating-caption', dimmingOpacity: 0.75, align: 'center'})">
							Vista Previa
							</a>
							&nbsp;&nbsp;&nbsp;&nbsp;
				  			<?php				
							$url = ($row_config_planilla['conf_pla_subje'] == 1)?"images/ModProAnti/boletinesPROMANTI.png":"images/ModProAnti/boletinesPROMANTI.png";									
						?>
<!--Acevedo-->
				 	</td>
				 </tr>
				 <tr>
				 	<td><input type="radio" id="validarradio501206"<?php if (!(strcmp("C", $array_parametro[31]))) {echo "checked=checked";} ?> value="C" name="calificacion_A_<?php echo $row_configuracion['conf_nombre']; ?>"> Las calificaciones del primer periodo en el nuevo grado al cual ser&aacute; promovido el estudiante, corresponder&aacute;n a las registradas en el primer periodo del grado anterior y en el caso de no haber correspondencia de pensum, esas &aacute;reas ser&aacute;n tomandas de la prueba de suficiencia.
                     
   <!--Acevedo-->
                    <?php		
					
  						    $title = "Vista Previa";
							$url = ($row_config_planilla['conf_pla_subje'] == 1)?"images/ModProAnti/boletinesPROMANTI.png":"images/ModProAnti/boletinesPROMANTI.png";
							?>
							<a href="javascript:void(0)"  title="<?php echo $title;?>" onclick="return hs.expand(this, {src:'<?php echo $url;?>', wrapperClassName: 'borderless floating-caption', dimmingOpacity: 0.75, align: 'center'})">
							Vista Previa
							</a>
							&nbsp;&nbsp;&nbsp;&nbsp;
				  			<?php				
							$url = ($row_config_planilla['conf_pla_subje'] == 1)?"images/ModProAnti/boletinesPROMANTI.png":"images/ModProAnti/boletinesPROMANTI.png";									
						?>
<!--Acevedo-->
				 	</td>
				 </tr>
		 <tr>
				 	<td><input type="radio" id="validarradio501207"<?php if (!(strcmp("D", $array_parametro[31]))) {echo "checked=checked";} ?> value="D" name="calificacion_A_<?php echo $row_configuracion['conf_nombre']; ?>"> Las calificaciones del primer periodo en el nuevo grado al cual ser&aacute; promovido el estudiante, corresponder&aacute;n a las registradas en el segundo periodo del nuevo grado.
                    
                   <!--Acevedo-->
                    <?php		
					
  						    $title = "Vista Previa";
							$url = ($row_config_planilla['conf_pla_subje'] == 1)?"images/ModProAnti/boletinesPROMANTI.png":"images/ModProAnti/boletinesPROMANTI.png";
							?>
							<a href="javascript:void(0)"  title="<?php echo $title;?>" onclick="return hs.expand(this, {src:'<?php echo $url;?>', wrapperClassName: 'borderless floating-caption', dimmingOpacity: 0.75, align: 'center'})">
							Vista Previa
							</a>
							&nbsp;&nbsp;&nbsp;&nbsp;
				  			<?php				
							$url = ($row_config_planilla['conf_pla_subje'] == 1)?"images/ModProAnti/boletinesPROMANTI.png":"images/ModProAnti/boletinesPROMANTI.png";									
						?>
<!--Acevedo-->
				 	</td>
				 </tr>
			 </table> 
</div>
</div>
</div>
</div>
</div>
</div>
		<?php
		break;
	case 135:
		$valoresP59 = explode("$", $row_configuracion['conf_valor']);
		?>
<!-- --------------------------------------------------------------------------------------------- PARAMETRO 57 ---------------------------------------------------- -->
	<div class="container_demohrvszv">
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd">&Iacute;tem </div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
<script type="text/javascript">
function ocultarpapm() {
if (document.getElementById('chec_papm').checked == false) {
document.getElementById("c51120").style.display = "none";
document.getElementById("texcpnf1").style.display = "";
}
if (document.getElementById('chec_papm').checked == true) {
document.getElementById("c51120").style.display = "";
document.getElementById("texcpnf1").style.display = "none";
}
}
function ocultarrga() {
if (document.getElementById('chec_rga').checked == false) {
document.getElementById("c53121").style.display = "none";
document.getElementById("epnehpep1").style.display = "";
}
if (document.getElementById('chec_rga').checked == true) {
document.getElementById("c53121").style.display = "";
document.getElementById("epnehpep1").style.display = "none";
}
}
addEvent('load', ocultarpapm); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
addEvent('load', ocultarrga); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
		<table>
			<tr><td><input type="checkbox" id="chec_papm" onclick="ocultarpapm()"<?php if (strpos($row_configuracion['conf_valor'],"a") == true)  {echo "checked=checked";} ?> value="a" name="a_<?php echo $row_configuracion['conf_nombre']; ?>"></td>
			<td><b>Promoci&oacute;n Anticipada por m&eacute;ritos</b> (ver condiciones en el par&aacute;metro No <b>52/120</b></td></tr>
			<tr><td></td>
			<td></td></tr>
			<tr><td><input type="checkbox" id="chec_rga" onclick="ocultarrga()" <?php if (strpos($row_configuracion['conf_valor'],"c") == true)  {echo "checked=checked";} ?> value="c" name="c_<?php echo $row_configuracion['conf_nombre']; ?>"></td>
			<td><b>Promoci&oacute;n Anticipada por Reprobaci&oacute;n de Grados a&ntilde;o anterior</b> disponible para estudiantes Reprobados en el a&ntilde;o inmediatamente anterior. (ver directrices en el par&aacute;metro No 53/155)</td></tr>
					<tr><td></td>
			<td></td></tr>
			<tr><td><input type="checkbox"  <?php if (strpos($row_configuracion['conf_valor'],"b") == true)  {echo "checked=checked";} ?> value="b" name="b_<?php echo $row_configuracion['conf_nombre']; ?>"></td>
			<td><b>Promoci&oacute;n anticipada para Retirados:</b> V&aacute;lido para  estudiantes que se retiran antes de finalizar el a&ntilde;o, con un abono del 75% de avance acad&eacute;mico.</td></tr>
					<tr><td></td>
			<td></td></tr>
			
			<tr><td><input type="checkbox"  <?php if (strpos($row_configuracion['conf_valor'],"d") == true)  {echo "checked=checked";} ?> value="d" name="d_<?php echo $row_configuracion['conf_nombre']; ?>"></td>
			<td><b>Promoci&oacute;n Anticipada por Casu&iacute;stica:</b>V&aacute;lido para estudiantes que se retiran antes de finalizar el a&ntilde;o, y con corte del tercer periodo debidamente finalizado, con un abono del 75% </td></tr>
		</table>
</div>
</div>
</div>
</div>
</div>
<!-- ---------------------------------------------------------------------------------------- FIN PARAMETRO 57 -------------------------------------------------------------------- -->
		<?php
		break;
		case 150: //Ingresar notas despues del cierre de areasObligatorias
		?>
			<?php
			if(strpos($row_configuracion['conf_valor'],"$")>0)
			{
				$array_parametro = explode("$",$row_configuracion['conf_valor']);
				$cri = $array_parametro[0];
				$cri2 = $array_parametro[1];
				$cri3 = $array_parametro[2];
			}
			else
				$cri = $row_configuracion['conf_valor'];
		?>
		<label>
		Aplica: 
		  <select class="sele_mul" name="criterio_<?php echo $row_configuracion['conf_nombre']; ?>" id="criterio123" onclick="validar452212353()">
			<option value="S" <?php if (!(strcmp("S", $cri['conf_valor']))) {echo "selected=\"selected\"";} ?>>Si</option>
			<option value="N" <?php if (!(strcmp("N", $cri['conf_valor']))) {echo "selected=\"selected\"";} ?>>No</option>
		  </select>
		</label>  <br><br>  <hr>
		<br><br><br>
<div id="dvhiegroj">
	                      <a id="anchor" onclick="abrir_ventana('nuevo_parametro.php')">Ver Parametro</a>
	                        
</div>
<script>
             function abrir_ventana(url) {
                 window.open(url, "_blank", 'width=1300,height=800');
                 return false;     
        }   
</script>
           <script>
function validar452212353() {
if(document.getElementById('criterio123').value=="S"){
document.getElementById("dvhiegroj").style.display = "";
}
if(document.getElementById('criterio123').value=="N"){
document.getElementById("dvhiegroj").style.display = "none";
}
}
addEvent('load', validar452212353); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
		<?php
		break;
		}// este es el fin
?>
	</td>
</tr>
<?php
}while($row_configuracion = mysql_fetch_assoc($configuracion));
?>
<?php
///Instanciacion de clases para el llamado de los parametros
// include 'ParametrosGenerales.php';
$objetoParametros6=new ParametrosGenerales();  ///Instancio el Objeto ParametroGenerales
$conexionBd=$objetoParametros6->creaConexion();  ///llamo la funcion creaConexion
$numPersiana=6;    
$cantidadParametros=$objetoParametros6  -> returnCantidadParametrosPersiana($conexionBd,$numPersiana); //Retorna la cantidad de parametros que hay dentro de la persiana
$cant=0;
while($cant<$cantidadParametros)  //Mientras haya Parametros Entre al while
{
	 	$valor_id_parametro_individual=$objetoParametros6  -> devuelvaParametros($cant);
       $objetoParametros6 ->ejecutarImpresionParametros($numPersiana,$conexionBd,$consecutivo,$valor_id_parametro_individual ); //Ejecuta la impresion de los paramertros individualmente
       $consecutivo=$objetoParametros6 ->returnConsecutivo();    //Retorna el consecutivo
        $informacionDelParametro=$objetoParametros6  ->informacionParametro($conexionBd,$valor_id_parametro_individual);  //Informacion del parametro 
       $cant++;
       // switch($valor_id_parametro_individual)
       // {
       // }
}
?>
</table>
</div>
</div>
</div>
</div>
</div>
<?php
// esta es la tabla 2
if($totalRows_configuracion)
{
	mysql_data_seek($configuracion,0);
mysql_select_db($database_sygescol, $sygescol);
	$query_configuracion = "SELECT conf_sygescol.conf_id, conf_sygescol.conf_nombre, conf_sygescol.conf_valor, conf_sygescol.conf_descri, conf_sygescol.conf_nom_ver, encabezado_parametros.titulo
								FROM conf_sygescol  INNER JOIN encabezado_parametros on encabezado_parametros.id_param=conf_sygescol.conf_id
							WHERE conf_sygescol.conf_estado = 0
								AND conf_sygescol.conf_id IN (75,90,101,103,107,118,119,130,131,133,134,138,139)  ORDER BY encabezado_parametros.id_orden ";
	$configuracion = mysql_query($query_configuracion, $sygescol) or die(mysql_error());
	$row_configuracion = mysql_fetch_assoc($configuracion);
// aca inicia la otra tabla
}?>
<?php
include ("conb.php");$registrosg=mysqli_query($conexion,"select * from conf_sygescol_adic where id=7")or die("Problemas en la Consulta".mysqli_error());while ($regg=mysqli_fetch_array($registrosg)){$coloracordg=$regg['valor'];}
?>
<div class="container_demohrvszv_caja_1">
		<div class="accordion_example2wqzx_caja_2">
			<div class="accordion_inwerds_caja_3">
				<div class="acc_headerfgd_caja_titulo" id="parametros_constancias_certificados" style="background-color: <?php echo $coloracordg ?>"><center><strong>7. PAR&Aacute;METROS PARA CONTROL DE REPORTES, CONSTANCIAS Y CERTIFICADOS</strong></center><br /><center><input type="radio" value="rojog" name="coloresg">Si&nbsp;&nbsp;<input type="radio" value="naranjag" name="coloresg">No</div></center>
				<div class="acc_contentsaponk_caja_4">
					<div class="grevdaiolxx_caja_5">
					<table  align="center" width="85%" class="centro" cellpadding="10" class="formulario" border="1">
	<tr>
	<th class="formulario"  style="background: #3399FF;" >Consecutivo </th>
	<th class="formulario"  style="background: #3399FF;" >Id Par&aacute;metro </th>
	<th class="formulario" >Tipo de Par&aacute;metro</th>
    <th class="formulario" >Detalle del Par&aacute;metro</th>
	<th class="formulario">Selecci&oacute;n</th>
	</tr>
	<?php
	do { $consecutivo++;
	?>
	<td class="formulario"   id="Consecutivo<?php echo $consecutivo; ?>" ><strong> <?php echo $consecutivo; ?></strong></td>
	<td class="formulario"  id="Parametro<?php echo $row_configuracion['conf_id']; ?>" ><strong><?php echo $row_configuracion['conf_id'] ?></strong></td>
<td valign="top"><strong>
<div class="container_demohrvszv_caja_tipo_param">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Tipo de Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx_caja_tipo_param">
<div id="conf_nom_ver">
<div  class="textarea "  align="justify" id="conf_nom_ver-<?php echo $row_configuracion['conf_id'] ?>"><?php echo $row_configuracion['conf_nom_ver']; ?></div>
</div></div></div></div></div></div>
</strong>
</td>      <td valign="top" width="80%">
     <div class="container_demohrvszv" >
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd"  id="cgasvf"><strong>Detalle Par&aacute;metro</strong></div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
<div id="conf_descri">
      <div  align="justify" class="textarea " id="conf_descri-<?php echo $row_configuracion['conf_id'] ?>" style="width: 90%;" align="justify">
      <?php echo $row_configuracion['conf_descri']; ?>
      </div>
</div>
					</div>
				</div>
			</div>
		</div>
</div>
 </td>
	<td align="center">
	<?php
	switch($row_configuracion['conf_id'])
	{
	case 75: //forma_ing_fallas
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$docentes = $array_parametro[1];
					$docentes_D = $array_parametro[2];
					$administrativos = $array_parametro[3];
					$estudiantes = $array_parametro[4];
					$_fecha_1 = $array_parametro[6];
					$_fecha_2 = $array_parametro[7];
					$_fecha_3 = $array_parametro[8];
					$_fecha_4 = $array_parametro[9];
					$parametro = $array_parametro[10];
				?>
<b>Aplica</b>
				 <select class="sele_mul" onclick="validar119()"name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
<div class="container_demohrvszv">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>&Iacute;tem</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
			 <table  width="90%" >
				 
				  <tr>
				<td><input id="p119_5" type="radio" <?php if (strpos($row_configuracion['conf_valor'],"1")==true) {echo "checked='checked'";} ?> value="1" name="controlBoletines<?php echo $row_configuracion['conf_nombre']; ?>" > Abrir la base de datos del a&ntilde;o anterior y dar permiso al docente (s), para reprobar el &aacute;rea o &aacute;reas requeridas, para cambiar el estado acad&eacute;mico al estudiante. </td>
				 </tr>
				  <tr>
				<td><input  id="p119_6" type="radio" <?php if (strpos($row_configuracion['conf_valor'],"2")==true) {echo "checked='checked'";} ?> value="2" name="controlBoletines<?php echo $row_configuracion['conf_nombre']; ?>" >Eliminar de la Base de Datos "TODA" la informaci&oacute;n acad&eacute;mica y administrativa del estudiante, para que el sistema no lo identifique como estudiante activo, en el a&ntilde;o inmediatamente  </td>
			</table>
</div>
</div>
</div>
</div>
</div>
<?php
		break;
		case 90: //MAS FAMILIAS EN ACCION
		?>
		<?php
			$estado = '';
			if(strpos($row_configuracion['conf_valor'],"$")>0)
			{
				$array_parametro = explode("$",$row_configuracion['conf_valor']);
				$parametro = $array_parametro[0];
				$nivel = $array_parametro[1];
				$estado = $array_parametro[2];
				$demo=$array_parametro[3];
			}
			else
				$parametro = $row_configuracion['conf_valor'];
		?>
			<!-- PARAMETRO 15 -->
			<table width="90%">
			 	<tr>
			 		<td><b> Aplica</b>
			 			<select class="sele_mul" onclick="validar5690()" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
							<option value="S" id="90m"<?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
							<option value="N" id="90o"<?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
			  			</select>
			  		</td>
			  	</tr>
</table>
     <div id="90">
	<div class="container_demohrvszv">
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd"><h4>Si aplica defina:</h4></div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
			<table width="90%">
				<tr>
				 	 <td><h4>Si aplica defina:</h4></td></a>
				 	<td>
						 <select id="<?php echo $row_configuracion['conf_nombre']."_ingreso"; ?>"  name="<?php echo $row_configuracion['conf_nombre']."_nivel"; ?>" onchange="myFunction(this)" class="sele_mul" style="width: 250px;">
				<option <?php echo ($nivel == '0')?'selected="selected"':''; ?>  value="0">Seleccione uno ... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
							<option <?php echo ($nivel == '1')?'selected="selected"':''; ?>  value="1">Con el sistema, Biometr&iacute;a por Control de Acceso</option>
							<option <?php echo ($nivel == '2')?'selected="selected"':''; ?> value="2">Con el registro de asistencia de la planilla virtual de asistencia</option>
							<option <?php echo ($nivel == '3')?'selected="selected"':''; ?> value="3">Con el registro de asistencia del “Auxiliar de Registro de Asistencia”.</option>
							<!--<option <?php echo ($nivel == '4')?'selected="selected"':''; ?> value="4">Las 3 Opciones Anteriores</option> -->
						</select>
							<script>
							function myFunction(coleto) {
								if (coleto.value == 1) {
									document.getElementById("<?php echo $row_configuracion['conf_nombre']."_demo"; ?>").style.display = "block";
								}else{
									document.getElementById("<?php echo $row_configuracion['conf_nombre']."_demo"; ?>").style.display = "none";
								}
							}
function validar5690() {
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="S")
{
 document.getElementById("<?php echo $row_configuracion['conf_nombre'].'_ingreso'; ?>").disabled = false;
document.getElementById("<?php echo $row_configuracion['conf_nombre'].'_demo'; ?>").disabled = false;
}
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="N")
{
 document.getElementById("<?php echo $row_configuracion['conf_nombre'].'_ingreso'; ?>").disabled = true;
document.getElementById("<?php echo $row_configuracion['conf_nombre'].'_demo'; ?>").disabled = true;
}
}
	addEvent('load', validar5690); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
					</td>
				</tr>
				</table>
	</div>
				</div>
			</div>
			</div>
	</div>
</div>
	<div class="container_demohrvszv">
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd"><b style="color: red;">NOTA IMPORTANTE:</b></div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
<table width="90%">
				<tr>
					<td colspan="2" style="border: solid 2px #CE6767;border-radius: 10px;">
						<b style="color: red;">NOTA IMPORTANTE:</b> <br><p style="line-height: 20px;"><b>CICLOS DE TIEMPO PARA LA GENERACI&Oacute;N DEL REPORTE: </b><br>
						Administre los tiempos de generaci&oacute;n del reporte, de acuerdo a lo establecido por la Alcald&iacute;a o el ente regulador que solicita esta informaci&oacute;n, ingresando las fechas correspondientes a cada ciclo, desde la ruta: <b><u>PERFIL SECRETARIA ACAD&Eacute;MICA / Sistema / tablas b&aacute;sicas / Ciclos de Familias en Acci&oacute;n.</u></b>
						<b style="color: red;"><u>TENGA EN CUENTA QUE:</u></b> Para el reporte del <b style="color: red;">PRIMER CICLO</b> de <i><b>&#8220;M&aacute;s Familias en Acci&oacute;n&#8221;</b></i>, el sistema generar&aacute; la certificaci&oacute;n, despu&eacute;s de verificar que el <b>ESTADO DE MATR&Iacute;CULA del estudiante</b> est&eacute; <b>ACTIVO</b>. Para los <b style="color: red;">ciclos siguientes</b>, verificar&aacute; que la asistencia del estudiante corresponda al <b>80%</b> del ciclo consultado.</p>
					</td>
				</tr>
			</table>
	</div>
				</div>
			</div>
			</div>
	</div>
			<!-- FIN PARAMETRO 15 -->
		<?php
		break;
			case 101:
			$variablesSep = explode("@",$row_configuracion['conf_valor']);
			$valoresCertificados = explode(",",$variablesSep[0]); // Certificados
			$valoresConstancias = explode(",",$variablesSep[1]); // Constancias
		?>
<div class="container_demohrvszv">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd">Certificados de estudio</div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
		<table >
			<tr>
				<td colspan="2"><h3><u>Certificados de estudio, iniciar en:</u></h3></td>
			</tr>
			<tr>
				<td><input type="radio" <?php if($valoresCertificados[0]==I){ echo 'checked="checked"'; }?> name="certificado_<?php echo $row_configuracion['conf_nombre']; ?>" value="I"></td>
				<td>Consecutivo al inicio  (Ej: <b style="color: red">0001</b>-CER).</td>
			</tr>
			<tr>
				<td><input type="radio" <?php if($valoresCertificados[0]==F){ echo 'checked="checked"'; }?> name="certificado_<?php echo $row_configuracion['conf_nombre']; ?>" value="F"></td>
				<td>Consecutivo al final   (Ej: CER-<b style="color: red">0001</b>).</td>
			</tr>
			</table>
			</div></div></div></div></div>
<div class="container_demohrvszv">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd">Constancias</div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
					<table>
			<tr>
				<td colspan="2"><h3><u>Constancias, iniciar en:</u></h3></td>
			</tr>
			<tr>
				<td><input type="radio" <?php if($valoresConstancias[0]==I){ echo 'checked="checked"'; }?> name="constancia_<?php echo $row_configuracion['conf_nombre']; ?>" value="I"></td>
				<td>Consecutivo al inicio  (Ej: <b style="color: red">0001</b>-CON).</td>
			</tr>
			<tr>
				<td><input type="radio" <?php if($valoresConstancias[0]==F){ echo 'checked="checked"'; }?> name="constancia_<?php echo $row_configuracion['conf_nombre']; ?>" value="F"></td>
				<td>Consecutivo al final   (Ej: CON-<b style="color: red">0001</b>).</td>
			</tr>
		</table>
		</div></div></div></div></div>
		<?php
		break;
case 103:
				$estado = '';
				if(strpos($row_configuracion['conf_valor'],"$")>0)
				{
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
					$estado = $array_parametro[1];
				}
				else
					$parametro = $row_configuracion['conf_valor'];
				?>
			 <table  width="90%" >
				 <tr>
				 <td><b>Aplica</b>
				 <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
				  </td>
				 </tr>
			</table>
					<?php
		break;
case 107:
				$estado = '';
				if(strpos($row_configuracion['conf_valor'],"$")>0)
				{
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
					$estado = $array_parametro[1];
				}
				else
					$parametro = $row_configuracion['conf_valor'];
				?>
			 <table  width="90%" >
				 <tr>
				 <td><b>Aplica</b>
				 <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
				  </td>
				 </tr>
			</table>
					<?php
		break;
case 118:
 //forma_ing_fallas
		$valorP14 = explode("$", $row_configuracion['conf_valor']);
		//print_r($valorP14);
		?>
		<script type="text/javascript">
		//Validar si aplica el parametro y muestra la informacion correspondiente
function validar15141212() {
	if (document.getElementById('sdpmt15141112').value == 'S') {
document.getElementById('pmt15142212').style.display = "";
	}
		if (document.getElementById('sdpmt15141112').value == 'C') {
document.getElementById('pmt15142212').style.display = "none";
	}
}
function validar15141212111() {
	if (document.getElementById('per11asd1').checked = true) {
document.getElementById('cfddafge').style.display = "";
	}
		if (document.getElementById('per11asd1').checked = false) {
document.getElementById('cfddafge').style.display = "none";
	}
}

addEvent('load', validar15141212111); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
		</script>
		<hr>
		<label>
		<h4>Aplica</h4>
		  <select class="sele_mul"  style="width:15%;" name="cPer_<?php echo $row_configuracion['conf_nombre']; ?>" id="sdpmt15141112" onclick="validar15141212()">
			<option  value="S" <?php if (!(strcmp("S", $valorP14[1]))) {echo "selected=\"selected\"";} ?>>SI</option>
		  	<option  value="C" <?php if (!(strcmp("C", $valorP14[1]))) {echo "selected=\"selected\"";} ?>>NO</option>
		  </select>
		</label>

<div id="pmt15142212">
<table width="100%" style="border:1px solid #666666; margin-top:5px;">
			<tr>
					<th style="color:#FFFFFF; background-color:#3399FF;" title="Periodo ">CASO 1</th>
					<th style="color:#FFFFFF; background-color:#3399FF;" title="Periodo ">CASO 2</th>
					<th style="color:#FFFFFF; background-color:#3399FF;" title="Periodo ">CASO 3</th>
			</tr>
			<tr>
				<td style="border:1px solid #CCCCCC;">
					<center><input onchange="cambiarChec()" <?php if($valorP14[2]==1 ){ echo 'checked="checked"'; }?> type="checkbox" value="1" id="sadasd" name="per1" class="p"/></center>
				</td>
				<td style="border:1px solid #CCCCCC;">
					<center><input onchange="cambiarChec()" <?php if($valorP14[3]==2 ){ echo 'checked="checked"'; }?> type="checkbox" value="2" id="sadasdas" name="per2" class="p"/></center>
				</td>
				<td style="border:1px solid #CCCCCC;">
					<center><input onclick="validar15141212111()" onchange="cambiarChec()" <?php if($valorP14[4]==3 ){ echo 'checked="checked"'; }?> type="checkbox" value="3" id="per11asd1" name="per3" class="p"/><div id="cfddafge">
				<a href="./tablaj.php"
        onclick="return hs.htmlExpand(this, {
            contentId: 'my-content',
            objectType: 'iframe',
            objectWidth: 1000,
            objectHeight: 400} )"
        class="highslide">
    Ver Parametro
</a>
<div class="highslide-html-content"
        id="my-content" style="width: 1000px;">
    <div style="text-align: right">
       <a href="#" onclick="return hs.close(this)">
          Cerrar
       </a>
    </div>    
    <div class="highslide-body"></div>
</div>
</center>
</div> 
</td>
			</tr>
		</table>
</div





				
		<?php
		break;
	
		//* ---------------------------------------------------------- caso  103  -----------------------------------------------------------------------
		//* ----------------------------------------------------------  caso 107  --------------------------------------------------------------------
				//* ---------------------------------------------------------- caso 118 -----------------------------------------------------
		case 119: //Hoja de vida
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$docentes = $array_parametro[1];
					$docentes_D = $array_parametro[2];
					$administrativos = $array_parametro[3];
					$estudiantes = $array_parametro[4];
					$_fecha_1 = $array_parametro[6];
					$_fecha_2 = $array_parametro[7];
					$_fecha_3 = $array_parametro[8];
					$_fecha_4 = $array_parametro[9];
					$parametro = $array_parametro[10];
				?>
<b>Aplica</b>
				 <select class="sele_mul" onclick="validar119()"name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
<div class="container_demohrvszv">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>&Iacute;tem</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
			 <table  width="90%" >
				 <tr>
					<td><input  id="p119_1" name="docentes" type="checkbox" value="1" <?php if (strlen($docentes)>0) {echo "checked='checked'";} ?>> Para <b>Docentes</b> a partir de :<td>
					<input name="<?php echo $row_configuracion['conf_nombre']."_fecha_1"; ?>" id="<?php echo $row_configuracion['conf_nombre']."_fecha_1"; ?>" type="text" size="7" readonly="readonly" value="<?php echo $_fecha_1; ?>" /><td>
		  			<button name="aaa_<?php echo $row_configuracion['conf_nombre']; ?>" id="aaa_<?php echo $row_configuracion['conf_nombre']; ?>" value="">.</button>
				</td></td></td>
				 </tr>
				  <tr>
				<td><input id="p119_2" name="docentes_D" type="checkbox" value="2" <?php if (strlen($docentes_D)>0) {echo "checked='checked'";} ?>> Para <b>Docentes Directivos</b> a partir de :			<td>
					<input name="<?php echo $row_configuracion['conf_nombre']."_fecha_2"; ?>" id="<?php echo $row_configuracion['conf_nombre']."_fecha_2"; ?>" type="text" size="7" readonly="readonly" value="<?php echo $_fecha_2; ?>" /><td>
		  			<button name="bbb_<?php echo $row_configuracion['conf_nombre']; ?>" id="bbb_<?php echo $row_configuracion['conf_nombre']; ?>" value="">.</button>
				</td></td></td>
				 </tr>
				  <tr>
				<td><input  id="p119_3" name="administrativos" type="checkbox" value="3" <?php if (strlen($administrativos)>0) {echo "checked='checked'";} ?>> Para <b>Administrativos</b> a partir de :	<td>
					<input name="<?php echo $row_configuracion['conf_nombre']."_fecha_3"; ?>" id="<?php echo $row_configuracion['conf_nombre']."_fecha_3"; ?>" type="text" size="7" readonly="readonly" value="<?php echo $_fecha_3; ?>" /><td>
		  			<button name="ccc_<?php echo $row_configuracion['conf_nombre']; ?>" id="ccc_<?php echo $row_configuracion['conf_nombre']; ?>" value="">.</button>
				</td></td></td>
				 </tr>
				  <tr>
				<td><input  id="p119_4" name="estudiantes" type="checkbox" value="4" <?php if (strlen($estudiantes)>0) {echo "checked='checked'";} ?>> Para <b>Estudiantes</b> a partir de :<td>
					<input name="<?php echo $row_configuracion['conf_nombre']."_fecha_4"; ?>" id="<?php echo $row_configuracion['conf_nombre']."_fecha_4"; ?>" type="text" size="7" readonly="readonly" value="<?php echo $_fecha_4; ?>" /><td>
		  			<button name="ddd_<?php echo $row_configuracion['conf_nombre']; ?>" id="ddd_<?php echo $row_configuracion['conf_nombre']; ?>" value="">.</button>
				</td></td></td>
				 </tr>
				  <tr>
				<td><input id="p119_5" type="radio" <?php if (strpos($row_configuracion['conf_valor'],"1")==true) {echo "checked='checked'";} ?> value="1" name="controlBoletines<?php echo $row_configuracion['conf_nombre']; ?>" > Con control de entrega de boletines </td>
				 </tr>
				  <tr>
				<td><input  id="p119_6" type="radio" <?php if (strpos($row_configuracion['conf_valor'],"2")==true) {echo "checked='checked'";} ?> value="2" name="controlBoletines<?php echo $row_configuracion['conf_nombre']; ?>" > Sin control de entrega de boletines </td>
			</table>
</div>
</div>
</div>
</div>
</div>
  <script>
function validar119() {
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="S")
{
document.getElementById("p119_1").disabled = false;
document.getElementById("p119_2").disabled = false;
document.getElementById("p119_3").disabled = false;
document.getElementById("p119_4").disabled = false;
document.getElementById("p119_5").disabled = false;
document.getElementById("p119_6").disabled = false;
document.getElementById("aaa_<?php echo $row_configuracion['conf_nombre']; ?>").disabled = false;
document.getElementById("bbb_<?php echo $row_configuracion['conf_nombre']; ?>").disabled = false;
document.getElementById("ccc_<?php echo $row_configuracion['conf_nombre']; ?>").disabled = false;
document.getElementById("ddd_<?php echo $row_configuracion['conf_nombre']; ?>").disabled = false;
}
if(document.getElementById("<?php echo $row_configuracion['conf_nombre']; ?>").value=="N")
{
document.getElementById("p119_1").disabled = true;
document.getElementById("p119_2").disabled = true;
document.getElementById("p119_3").disabled = true;
document.getElementById("p119_4").disabled = true;
document.getElementById("p119_5").disabled = true;
document.getElementById("p119_6").disabled = true;
 document.getElementById("aaa_<?php echo $row_configuracion['conf_nombre']; ?>").disabled = true;
	document.getElementById("bbb_<?php echo $row_configuracion['conf_nombre']; ?>").disabled = true;
		document.getElementById("ccc_<?php echo $row_configuracion['conf_nombre']; ?>").disabled = true;
			document.getElementById("ddd_<?php echo $row_configuracion['conf_nombre']; ?>").disabled = true;
}
}
	addEvent('load', validar119); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
		<?php
		break;
		case 130:
		?>
		<table><td></td> <td>
		  <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
			<option value="0" <?php if (!(strcmp("0", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Seleccione uno... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
		  	<option value="ECCF" <?php if (!(strcmp("ECCF", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Hoja tama&ntilde;o carta con copia y filigrana</option>
			<option value="PDEC" <?php if (!(strcmp("PDEC", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Dos estudiante por hoja tama&ntilde;o carta</option>
		  </select>
		</td></table>
		<?php
		break;
		case 131:
		?>
		<table><td></td> <td>
		  <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
		<option value="0" <?php if (!(strcmp("0", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Seleccione uno... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
		  	<option value="1" <?php if (!(strcmp("1", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Una hoja por estudiante</option>
			<option value="2" <?php if (!(strcmp("2", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Dos Estudiantes por hoja </option>
		  </select>
		</td></table>
		<?php
		break;
	case 133:
				//explode para Basica Primaria
     			$arrayAsignaturas = array();
     			$valoreParametro = explode("/", $row_configuracion['conf_valor']);
				$array_parametro = explode("$",$valoreParametro[1]);
$variablesSep = explode("@",$row_configuracion['conf_valor']);
			$valoresCertificados = explode(",",$variablesSep[0]); // Certificados
			$valoresConstancias = explode(",",$variablesSep[1]); // Constancias
				for ($i=0; $i <= count($array_parametro) ; $i++) {
					$valoresDetalle = explode("_", $array_parametro[$i]);
					$arrayAsignaturas[$valoresDetalle[1]] = $valoresDetalle[0];
				}
				//explode para Basica Secundaria
     			$arrayAsignaturas2 = array();
     			$valoreParametro2 = explode("/", $row_configuracion['conf_valor']);
				$array_parametro2 = explode("$",$valoreParametro2[2]);
				for ($i=0; $i <= count($array_parametro2) ; $i++) {
					$valoresDetalle2 = explode("_", $array_parametro2[$i]);
					$arrayAsignaturas2[$valoresDetalle2[1]] = $valoresDetalle2[0];
				}
				//explode para Media Decimo
     			$arrayAsignaturas3 = array();
     			$valoreParametro3 = explode("/", $row_configuracion['conf_valor']);
				$array_parametro3 = explode("$",$valoreParametro3[3]);
				for ($i=0; $i <= count($array_parametro3) ; $i++) {
					$valoresDetalle3 = explode("_", $array_parametro3[$i]);
					$arrayAsignaturas3[$valoresDetalle3[1]] = $valoresDetalle3[0];
					//ECHO '______'.$arrayAsignaturas3[$valoresDetalle3[3]] = $valoresDetalle3[0];
				}
				//explode para Media Once
     			$arrayAsignaturas4 = array();
     			$valoreParametro4 = explode("/", $row_configuracion['conf_valor']);
				$array_parametro4 = explode("$",$valoreParametro4[4]);
				for ($i=0; $i <= count($array_parametro4) ; $i++) {
					$valoresDetalle4 = explode("_", $array_parametro4[$i]);
					$arrayAsignaturas4[$valoresDetalle4[1]] = $valoresDetalle4[0];
				}
				//explode para Ciclos
     			$arrayAsignaturas5 = array();
     			$valoreParametro5 = explode("/", $row_configuracion['conf_valor']);
				$array_parametro5 = explode("$",$valoreParametro5[5]);
				for ($i=0; $i <= count($array_parametro5) ; $i++) {
					$valoresDetalle5 = explode("_", $array_parametro5[$i]);
					$arrayAsignaturas5[$valoresDetalle5[1]] = $valoresDetalle5[0];
				}
				$link = conectarse();
				mysql_select_db($database_sygescol,$link);
				$sel_consulta_areas = "SELECT * FROM aes ";
				$sql_consulta_areas = mysql_query($sel_consulta_areas, $link);
		?>
		<script type="text/javascript">
				function pagoOnChange(sel) {
				      if (sel.value=="primaria"){
				           divC = document.getElementById("basica_Primaria");
				           divC.style.display = "";
				           divT = document.getElementById("basica_Secundaria");
				           divT.style.display = "none";
				           divA = document.getElementById("media_decimo");
				           divA.style.display = "none";
				           divB = document.getElementById("media_once");
				           divB.style.display = "none";
				            divD = document.getElementById("ciclos_adultos");
				           divD.style.display = "none";
				      }else if(sel.value=="secundaria"){
				      divC = document.getElementById("basica_Primaria");
				           divC.style.display = "none";
				           divT = document.getElementById("basica_Secundaria");
				           divT.style.display = "";
				           divA = document.getElementById("media_decimo");
				           divA.style.display = "none";
				           divB = document.getElementById("media_once");
				           divB.style.display = "none";
				            divD = document.getElementById("ciclos_adultos");
				           divD.style.display = "none";
				      }else if(sel.value=="decimo"){
				       divC = document.getElementById("basica_Primaria");
				           divC.style.display = "none";
				           divT = document.getElementById("basica_Secundaria");
				           divT.style.display = "none";
				           divA = document.getElementById("media_decimo");
				           divA.style.display = "";
				           divB = document.getElementById("media_once");
				           divB.style.display = "none";
				            divD = document.getElementById("ciclos_adultos");
				           divD.style.display = "none";
				      }else if(sel.value=="once"){
				            divC = document.getElementById("basica_Primaria");
				           divC.style.display = "none";
				           divT = document.getElementById("basica_Secundaria");
				           divT.style.display = "none";
				           divA = document.getElementById("media_decimo");
				           divA.style.display = "none";
				           divB = document.getElementById("media_once");
				           divB.style.display = "";
				            divD = document.getElementById("ciclos_adultos");
				           divD.style.display = "none";
				      }else if(sel.value=="ciclos"){
				      	   divC = document.getElementById("basica_Primaria");
				           divC.style.display = "none";
				           divT = document.getElementById("basica_Secundaria");
				           divT.style.display = "none";
				           divA = document.getElementById("media_decimo");
				           divA.style.display = "none";
				           divB = document.getElementById("media_once");
				           divB.style.display = "none";
				            divD = document.getElementById("ciclos_adultos");
				           divD.style.display = "";
				      }
				}
		</script>
<style type="text/css">
.formulario55 td {
    border: solid 2px rgb(51, 153, 255);
}
.negrita{font-weight: bold;}
		</style>
		<table class="formulario55" >
			<tr>
			<td>
					<div id="scroll" style="overflow: scroll; height: 421px;">
					<table>
						<tr><td><input type="radio" id="radio1534a" <?php if($valoresCertificados[0]==I){ echo 'checked="checked"'; }?> name="certificado_<?php echo $row_configuracion['conf_nombre']; ?>" value="I"></td><td>Tener en cuenta solo el promedio (permite la duplicidad del puesto)</td></tr>
						<tr ><td><input type="radio" id="radio1533a"<?php if($valoresCertificados[0]==F){ echo 'checked="checked"'; }?> name="certificado_<?php echo $row_configuracion['conf_nombre']; ?>" value="F"><td>Incluir en el filtro el desempate con las &aacute;reas de:</td> </tr>
           <script>
function validar452231212() {
if(document.getElementById('parametro67133').value=="S"){
document.getElementById("cajap67133").style.display = "";
}
if(document.getElementById('parametro67133').value=="N")
{
document.getElementById("cajap67133").style.display = "none";
}
}
  addEvent('load', validar452231212); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
<?php 
$selDocumentoAutorizado1212 = "SELECT * FROM conf_sygescol where conf_id = 133";
$sqlDocumentoAutorizado1212 = mysql_query($selDocumentoAutorizado1212, $link)or die(mysql_error());
$row_DocumentoAutorizado1212 = mysql_fetch_array($sqlDocumentoAutorizado1212);
 ?>
				 <tr>
				 <td colspan="6"><b>Aplica</b>
				 <select class="sele_mul" name="parametro67133" id="parametro67133" onclick="validar452231212();">
					<option value="S" <?php if ($row_DocumentoAutorizado1212['c']=='S') {echo "selected='selected'";} ?>>Si</option>
					<option value="N"  <?php if ($row_DocumentoAutorizado1212['c']=='N') {echo "selected='selected'";} ?>>No</option>
				  </select>
				  </td>
				 </tr>
		
							<tr> <th colspan="2" >
								<select class="sele_mul"  onChange="pagoOnChange(this)" style="display:none;">
									<!--<option>Seleccione Uno...</option>-->
									<option value="primaria">Basica Primaria</option>
									<option value="secundaria">Basica Secundaria</option>
									<option value="decimo">Media Decimo</option>
									<option value="once">Media Once</option>
									<option value="ciclos">Ciclos</option>
								</select>
								</th>
							</tr>
						<tr>
						<?php
							//areas para Basica primaria
							echo '<tr><td colspan="2"><div id="basica_Primaria" style="display:;">';
							mysql_data_seek($sql_consulta_areas, 0);
?>
<div id="cajap67133">
<table>
<tr>
<td class="negrita">&Aacute;REAS/ASIGNATURAS</td>
	<td class="negrita">B.PRIA</td>
	<td class="negrita">B.SEC</td>
	<td class="negrita">MEDIA 10</td>
	<td class="negrita">MEDIA 11</td>
	<td class="negrita" >CICLOS</td>
</tr>
<?php
							while ($row_consulta_areas = mysql_fetch_assoc($sql_consulta_areas)) {
?>
<tr>
<td>
<?php 
echo $row_consulta_areas['b'];
?>
</td>
<td>
<?php
	
echo '<input type="text" onkeypress="return justNumbers(event);" name="area_'.$row_consulta_areas[i].'" value="'.$arrayAsignaturas[$row_consulta_areas[i]].'" style="border-radius: 10px; width: 30%;">';
?>
</td>
<td>
<?php 						
echo '<input type="text" onkeypress="return justNumbers(event);"name="area2_'.$row_consulta_areas[i].'" value="'.$arrayAsignaturas2[$row_consulta_areas[i]].'" style="border-radius: 10px; width: 30%;"><br>';
							
?>
</td>
<td>
<?php 						
echo '<input type="text" onkeypress="return justNumbers(event);"name="area3_'.$row_consulta_areas[i].'" value="'.$arrayAsignaturas3[$row_consulta_areas[i]].'" style="border-radius: 10px; width: 30%;"><br>';
							
?>
</td>
<td>
<?php 						
echo '<input type="text" onkeypress="return justNumbers(event);"name="area4_'.$row_consulta_areas[i].'" value="'.$arrayAsignaturas4[$row_consulta_areas[i]].'" style="border-radius: 10px; width: 30%;"><br>';
							
?>
</td>
<td>
<?php 						
echo '<input type="text" onkeypress="return justNumbers(event);"name="area5_'.$row_consulta_areas[i].'" value="'.$arrayAsignaturas5[$row_consulta_areas[i]].'" style="border-radius: 10px; width: 30%;"><br>';
							
?>
</td>
</tr>				
<?php 
			}
?>
</table>
</div>
		</tr>
			</table>
			</div>
			</td>
			</tr>
</table>
		<?php
		break;
		case 134:
		?>
		<table><td></td> <td>
		  <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
 <option value="0" <?php if (!(strcmp("0", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Seleccione uno... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
		  	<option value="AEC" <?php if (!(strcmp("AEC", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Antes de la encuesta de continuidad</option>
			<option value="LMA" <?php if (!(strcmp("LMA", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Al momento de legalizar la matr&iacute;cula administrativa</option>
				<option value="DTA" <?php if (!(strcmp("LMA", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Durante todo el a&ntilde;o</option>
		  </select>
		</td></table>
		<?php
		break;
		case 135:
		$valoresP59 = explode("$", $row_configuracion['conf_valor']);
		?>
	<div class="container_demohrvszv">
	<div class="accordion_example2wqzx">
	<div class="accordion_inwerds">
	<div class="acc_headerfgd"><strong>&Iacute;tem</strong> </div>
	<div class="acc_contentsaponk">
	<div class="grevdaiolxx">
		<table>
			<tr><td><input type="checkbox"  <?php if (strpos($row_configuracion['conf_valor'],"a") == true)  {echo "checked=checked";} ?> value="a" name="a_<?php echo $row_configuracion['conf_nombre']; ?>"></td>
			<td><b>Promoci&oacute;n Anticipada por m&eacute;ritos</b> (ver condiciones en el par&aacute;metro No <b>46</b></td></tr>
			<tr><td><input type="checkbox"  <?php if (strpos($row_configuracion['conf_valor'],"b") == true)  {echo "checked=checked";} ?> value="b" name="b_<?php echo $row_configuracion['conf_nombre']; ?>"></td>
			<td><b>Promoci&oacute;n anticipada para Retirados:</b> V&aacute;lido para  estudiantes que se retiran antes de finalizar el a&ntilde;o, con un abono del 75% de avance acad&eacute;mico y el certificado se genera, solo al final del a&ntilde;o.</td></tr>
			<tr><td><input type="checkbox"  <?php if (strpos($row_configuracion['conf_valor'],"c") == true)  {echo "checked=checked";} ?> value="c" name="c_<?php echo $row_configuracion['conf_nombre']; ?>"></td>
			<td><b>Promoci&oacute;n Anticipada por Reprobaci&oacute;n de Grados;</b> disponible para estudiantes Reprobados a&ntilde;o anterior.</td></tr>
			<tr><td><input type="checkbox"  <?php if (strpos($row_configuracion['conf_valor'],"d") == true)  {echo "checked=checked";} ?> value="d" name="d_<?php echo $row_configuracion['conf_nombre']; ?>"></td>
			<td><b>Promoci&oacute;n Anticipada por Casu&iacute;stica:</b> Proceso habilitado para casos de: solo al corte del tercer periodo debidamente finalizado 75%, con un abono del 75% de avance acad&eacute;mico y el certificado se genera, solo al final del a&ntilde;o.</td></tr>
		</table>
</div>
</div>
</div>
</div>
</div>
		<?php
		break;
			case 138:
		?>
		<div class="container_demohrvszv">
		<div class="accordion_example2wqzx">
		<div class="accordion_inwerds">
		<div class="acc_headerfgd"><strong>&Iacute;tem</strong></div>
		<div class="acc_contentsaponk">
		<div class="grevdaiolxx">
	<p style="text-align: left;margin:15px;">
			<b>LIBRO DE MATR&Iacute;CULAS - LIBRO FINAL DE VALORACIONES</b><br>
			Foliarlo filtrado por:<br>
			<select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" style="width: 70%; margin: 5px 0 0 15px;">
<option value="0" <?php if (!(strcmp("0", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Seleccione uno... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
				<option value="1" <?php if (!(strcmp("1", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Sedes y Jornadas en orden de llegada</option>
				<option value="2" <?php if (!(strcmp("2", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Sedes y Jornadas en orden Alfab&eacute;tico</option>
				<option value="3" <?php if (!(strcmp("3", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Grados en orden de llegada</option>
				<option value="4" <?php if (!(strcmp("4", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Grados en orden Alfab&eacute;tico</option>
				<option value="5" <?php if (!(strcmp("5", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Grupos en orden de llegada</option>
				<option value="6" <?php if (!(strcmp("6", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Grupos en orden Alfab&eacute;tico</option>
				<option value="7" <?php if (!(strcmp("7", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Grupos y por sedes y por llegada</option>
				<option value="8" <?php if (!(strcmp("8", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Grupos y por sedes alfab&eacute;ticamente</option>
			</select>
		</p>
		</div></div></div></div></div>
		<?php
		break;
			case 139:
		?>
		<div class="container_demohrvszv">
		<div class="accordion_example2wqzx">
		<div class="accordion_inwerds">
		<div class="acc_headerfgd"><strong>&Iacute;tem</strong></div>
		<div class="acc_contentsaponk">
		<div class="grevdaiolxx">
		<p style="text-align: left;margin:15px;">
			<b>LIBRO DE MATR&Iacute;CULAS - LIBRO FINAL DE VALORACIONES</b><br>
			Foliarlo filtrado por:<br><br>
				<input type="checkbox" name="1_<?php echo $row_configuracion['conf_nombre']; ?>" value="1A" <?php if (strpos($row_configuracion['conf_valor'],"1A") == true )    {echo "checked='checked'";} ?> >Nivel Preescolar<br>
				<input type="checkbox" name="2_<?php echo $row_configuracion['conf_nombre']; ?>" value="2A" <?php if (strpos($row_configuracion['conf_valor'],"2A") == true )  {echo "checked='checked'";} ?> >Nivel B&aacute;sica Primaria<br>
				<input type="checkbox" name="3_<?php echo $row_configuracion['conf_nombre']; ?>" value="3A" <?php if (strpos($row_configuracion['conf_valor'],"3A") == true )  {echo "checked='checked'";} ?> >Nivel B&aacute;sica Secundaria<br>
				<input type="checkbox" name="4_<?php echo $row_configuracion['conf_nombre']; ?>" value="4A" <?php if (strpos($row_configuracion['conf_valor'],"4A") == true )    {echo "checked='checked'";} ?> >Nivel Media Decimo<br>
                <input type="checkbox" name="5_<?php echo $row_configuracion['conf_nombre']; ?>" value="5A" <?php if (strpos($row_configuracion['conf_valor'],"5A") == true )    {echo "checked='checked'";} ?> >Nivel Media Once<br>
				<input type="checkbox" name="6_<?php echo $row_configuracion['conf_nombre']; ?>" value="6" <?php if (strpos($row_configuracion['conf_valor'],"6") == true )    {echo "checked='checked'";} ?> >Ciclos Basica Primaria<br>
                <input type="checkbox" name="7_<?php echo $row_configuracion['conf_nombre']; ?>" value="7" <?php if (strpos($row_configuracion['conf_valor'],"7") == true )    {echo "checked='checked'";} ?> >Ciclos Basica Secundaria<br>
                <input type="checkbox" name="8_<?php echo $row_configuracion['conf_nombre']; ?>" value="8" <?php if (strpos($row_configuracion['conf_valor'],"8") == true )    {echo "checked='checked'";} ?> >Ciclos Media<br>
				<input type="checkbox" name="11_<?php echo $row_configuracion['conf_nombre']; ?>" value="11" <?php if (strpos($row_configuracion['conf_valor'],"11") == true )  {echo "checked='checked'";} ?> >Modalidad Aceleraci&oacute;n del Aprendizaje<br>
				<input type="checkbox" name="12_<?php echo $row_configuracion['conf_nombre']; ?>" value="12" <?php if (strpos($row_configuracion['conf_valor'],"12") == true )  {echo "checked='checked'";} ?> >Modalidad Necesidades Educativas NEE<br>
                <input type="checkbox" name="13_<?php echo $row_configuracion['conf_nombre']; ?>" value="13" <?php if (strpos($row_configuracion['conf_valor'],"13") == true )  {echo "checked='checked'";} ?> >Grupos Juveniles Basica Primaria<br>
                <input type="checkbox" name="14_<?php echo $row_configuracion['conf_nombre']; ?>" value="14" <?php if (strpos($row_configuracion['conf_valor'],"14") == true )  {echo "checked='checked'";} ?> >Grupos Juveniles Secundaria<br>
                <input type="checkbox" name="15_<?php echo $row_configuracion['conf_nombre']; ?>" value="15" <?php if (strpos($row_configuracion['conf_valor'],"15") == true )  {echo "checked='checked'";} ?> >Grupos Juveniles Media<br>
		</p>
		</div></div></div></div></div>
		<?
		break;
		}
?>
	</td>
</tr>
<?php
}while($row_configuracion = mysql_fetch_assoc($configuracion));
?>
<?php
///Instanciacion de clases para el llamado de los parametros
// include 'ParametrosGenerales.php';
$objetoParametros7=new ParametrosGenerales();  ///Instancio el Objeto ParametroGenerales
$conexionBd=$objetoParametros7->creaConexion();  ///llamo la funcion creaConexion
$numPersiana=7;    
$cantidadParametros=$objetoParametros7  -> returnCantidadParametrosPersiana($conexionBd,$numPersiana); //Retorna la cantidad de parametros que hay dentro de la persiana
$cant=0;
while($cant<$cantidadParametros)  //Mientras haya Parametros Entre al while
{
	 	$valor_id_parametro_individual=$objetoParametros7  -> devuelvaParametros($cant);
       $objetoParametros7 ->ejecutarImpresionParametros($numPersiana,$conexionBd,$consecutivo,$valor_id_parametro_individual ); //Ejecuta la impresion de los paramertros individualmente
       $consecutivo=$objetoParametros7 ->returnConsecutivo();    //Retorna el consecutivo
        $informacionDelParametro=$objetoParametros7  ->informacionParametro($conexionBd,$valor_id_parametro_individual);  //Informacion del parametro 
       $cant++;
       // switch($valor_id_parametro_individual)
       // {
       // }
}
?>
</table>
</div>
</div>
</div>
</div>
</div>
<?php
// esta es la tabla 2
if($totalRows_configuracion)
{
	mysql_data_seek($configuracion,0);
mysql_select_db($database_sygescol, $sygescol);
	$query_configuracion = "SELECT conf_sygescol.conf_id, conf_sygescol.conf_nombre, conf_sygescol.conf_valor, conf_sygescol.conf_descri, conf_sygescol.conf_nom_ver, encabezado_parametros.titulo
								FROM conf_sygescol  INNER JOIN encabezado_parametros on encabezado_parametros.id_param=conf_sygescol.conf_id
							WHERE conf_sygescol.conf_estado = 0
								AND conf_sygescol.conf_id IN (104,91)  ORDER BY encabezado_parametros.id_orden ";
	$configuracion = mysql_query($query_configuracion, $sygescol) or die(mysql_error());
	$row_configuracion = mysql_fetch_assoc($configuracion);
}?>
<?php
include ("conb.php");$registrosh=mysqli_query($conexion,"select * from conf_sygescol_adic where id=8")or die("Problemas en la Consulta".mysqli_error());while ($regh=mysqli_fetch_array($registrosh)){$coloracordh=$regh['valor'];}
?>
<div class="container_demohrvszv_caja_1">
<div class="accordion_example2wqzx_caja_2">
			<div class="accordion_inwerds_caja_3">
				<div class="acc_headerfgd_caja_titulo" id="parametros_acudientes" style="background-color: <?php echo $coloracordh ?>"><center><strong>8. PAR&Aacute;METROS PARA INTERACCI&Oacute;N CON ACUDIENTES</strong></center><br /><center><input type="radio" value="rojoh" name="coloresh">Si&nbsp;&nbsp;<input type="radio" value="naranjah" name="coloresh">No</div></center>
				<div class="acc_contentsaponk_caja_4">
<div class="grevdaiolxx_caja_5">
					<table  align="center" width="85%" class="centro" cellpadding="10" class="formulario" border="1">
<tr>
	<th class="formulario"  style="background: #3399FF;" >Consecutivo </th>
	<th class="formulario"  style="background: #3399FF;" >Id Par&aacute;metro </th>
	<th class="formulario" >Tipo de Par&aacute;metro</th>
    <th class="formulario" >Detalle del Par&aacute;metro</th>
	<th class="formulario">Selecci&oacute;n</th>
	</tr>
	<?php
	do { $consecutivo++;
	?>
	<td class="formulario"   id="Consecutivo<?php echo $consecutivo; ?>" ><strong> <?php echo $consecutivo; ?></strong></td>
	<td class="formulario"  id="Parametro<?php echo $row_configuracion['conf_id']; ?>" ><strong><?php echo $row_configuracion['conf_id'] ?></strong></td>
<td valign="top"><strong>
<div class="container_demohrvszv_caja_tipo_param">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Tipo de Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx_caja_tipo_param">
<div id="conf_nom_ver">
<div  class="textarea "  align="justify" id="conf_nom_ver-<?php echo $row_configuracion['conf_id'] ?>"><?php echo $row_configuracion['conf_nom_ver']; ?></div>
</div></div></div></div></div></div>
</strong>
</td>      <td valign="top" width="80%">
<div class="container_demohrvszv" >
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"  id="cgasvf"><strong>Detalle Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
<div id="conf_descri">
   <div  align="justify" class="textarea " id="conf_descri-<?php echo $row_configuracion['conf_id'] ?>" style="width: 90%;" align="justify">
  <?php echo $row_configuracion['conf_descri']; ?>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
 </td>
	<td align="center">
	<?php
	switch($row_configuracion['conf_id'])
	{
		case 91://DOCUMENTOS LEGALES
			$estado = '';
			if(strpos($row_configuracion['conf_valor'],",")>0)
			{
				$array_parametro = explode(",",$row_configuracion['conf_valor']);
				//print_r($array_parametro);
				$primero = $array_parametro[2];
				$segundo = $array_parametro[3];
				$cuarto = $array_parametro[4];
				$quinto = $array_parametro[5];
				$sexto = $array_parametro[6];
				$septimo = $array_parametro[7];
				$octavo = $array_parametro[8];
				$noveno = $array_parametro[9];
				$decimo = $array_parametro[10];
				$once = $array_parametro[11];
				$doce = $array_parametro[12];
				$trece = $array_parametro[13];
				$catorce = $array_parametro[14];
				$quince = $array_parametro[15];
				$diesiseis_estu = $array_parametro[16];
				$diesiseis = $array_parametro[31];
				//desde la posicion 16 hasta la 23
				$gg = $array_parametro[42];
                $ggg = $array_parametro[43];
			}
			else
			{
				$parametro = $row_configuracion['conf_valor'];
			}
				$selDocumentoAutorizado = "SELECT * FROM documentos_legales WHERE parametro=1 and docu_legal_id NOT IN(10,11,12,13,14,2,9,18)";
				$sqlDocumentoAutorizado = mysql_query($selDocumentoAutorizado, $link)or die(mysql_error());
				?>
			 <table width="70%" >
			 <tr><td></td>
			 <td>
			 <center>
			 <select class="sele_mul" onclick="validar6691();validar66912()" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
				<option value="S,A" <?php if (substr($row_configuracion['conf_valor'],0,1)=="S") {echo "selected=\"selected\"";} ?>>Aplica</option>
				<option value="N" <?php if (substr($row_configuracion['conf_valor'],0,1)=="N") {echo "selected=\"selected\"";} ?>>No Aplica</option>
			  </select>
			  </center>
			  </td></tr>
			  <tr>
			  <td colspan="2">
			  <div id="borrarp70p1">
				<table border="1" width="100%">
				<tr>
					<th colspan="2" rowspan="2">Documento Autorizado</th>
					<th colspan="2">Firma</th>
				</tr>
				<tr>
					<th>Si</th>
					<th>No</th>
				</tr>
					<tr>
<td>Constancias de Estudio</td>
<td><input id="p6891_1" name="cons_estu" type="checkbox" value="COES" <?php if (strlen($primero)>0) {echo "checked='checked'";} ?>/></td>
<td><input id="p6891_2" type="radio" <?php if (!(strcmp("1", $array_parametro[17]))) {echo "checked=checked";} ?> value="1" name="primeroA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
<td><input id="p6891_3" type="radio" <?php if (!(strcmp("2", $array_parametro[17]))) {echo "checked=checked";} ?> value="2" name="primeroA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
</tr>
					<tr>
					<td>Constancia de Matr&iacute;cula</td>
					<td><input id="p6891_4" name="cons_matri" type="checkbox" value="COMT" <?php if (strlen($segundo)>0) {echo "checked='checked'";} ?>/></td>
					<td><input id="p6891_5" type="radio" <?php if (!(strcmp("3", $array_parametro[18]))) {echo "checked=checked";} ?> value="3" name="segundoA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					<td><input id="p6891_6" type="radio" <?php if (!(strcmp("4", $array_parametro[18]))) {echo "checked=checked";} ?> value="4" name="segundoA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					</tr>
						<tr>
					<td>Constancia de familias en acci&oacute;n</td>
					<td><input id="p6891_7" name="cons_fam" type="checkbox" value="COFA" <?php if (strlen($cuarto)>0) {echo "checked='checked'";} ?>/></td>
					<td><input id="p6891_8" type="radio" <?php if (!(strcmp("5", $array_parametro[19]))) {echo "checked=checked";} ?> value="5" name="terceroA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					<td><input id="p6891_9" type="radio" <?php if (!(strcmp("6", $array_parametro[19]))) {echo "checked=checked";} ?> value="6" name="terceroA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					</tr>
						<tr>
					<td>Constancia cajas de compensaci&oacute;n</td>
					<td><input id="p6891_10" name="cons_cajas" type="checkbox" value="COCJ" <?php if (strlen($quinto)>0) {echo "checked='checked'";} ?>/></td>
					<td><input id="p6891_11" type="radio" <?php if (!(strcmp("7", $array_parametro[20]))) {echo "checked=checked";} ?> value="7" name="cuartoA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					<td><input id="p6891_12" type="radio" <?php if (!(strcmp("8", $array_parametro[20]))) {echo "checked=checked";} ?> value="8" name="cuartoA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					</tr>
						<tr>
					<td>Paz y Salvo</td>
					<td><input id="p6891_13" name="cons_paz" type="checkbox" value="PAZ" <?php if (strlen($sexto)>0) {echo "checked='checked'";} ?>/></td>
					<td><input id="p6891_14" type="radio" <?php if (!(strcmp("9", $array_parametro[21]))) {echo "checked=checked";} ?> value="9" name="quintoA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					<td><input id="p6891_15" type="radio" <?php if (!(strcmp("10", $array_parametro[21]))) {echo "checked=checked";} ?> value="10" name="quintoA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					</tr>
					<tr>
					<td>Informe Valorativo Periodo a Periodo</td>
					<td><input id="p6891_16" name="cons_info" type="checkbox" value="INF" <?php if (strlen($septimo)>0) {echo "checked='checked'";} ?>/></td>
					<td><input id="p6891_17" type="radio" <?php if (!(strcmp("11", $array_parametro[22]))) {echo "checked=checked";} ?> value="11" name="sextoA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					<td><input id="p6891_18" type="radio" <?php if (!(strcmp("12", $array_parametro[22]))) {echo "checked=checked";} ?> value="12" name="sextoA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					</tr>
					<tr>
					<td>Observador del Alumno (Activo)</td>
					<td><input id="p6891_19" name="cons_obs" type="checkbox" value="OBS" <?php if (strlen($octavo)>0) {echo "checked='checked'";} ?>/></td>
					<td><input id="p6891_20" type="radio" <?php if (!(strcmp("13", $array_parametro[23]))) {echo "checked=checked";} ?> value="13" name="septimoA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					<td><input id="p6891_21" type="radio" <?php if (!(strcmp("14", $array_parametro[23]))) {echo "checked=checked";} ?> value="14" name="septimoA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					</tr>
					<tr>
					<td>Constancia Retiro de Estudiante</td>
					<td><input id="p6891_22" name="cons_ret" type="checkbox" value="CORE" <?php if (strlen($noveno)>0) {echo "checked='checked'";} ?>/></td>
					<td><input id="p6891_23" type="radio" <?php if (!(strcmp("15", $array_parametro[24]))) {echo "checked=checked";} ?> value="15" name="octavoA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					<td><input id="p6891_24" type="radio" <?php if (!(strcmp("16", $array_parametro[24]))) {echo "checked=checked";} ?> value="16" name="octavoA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					</tr>
					<tr>
					<td>Observador del Alumno (Retirado)</td>
					<td><input id="p6891_25" name="cons_retno" type="checkbox" value="OBSNO" <?php if (strlen($decimo)>0) {echo "checked='checked'";} ?>/></td>
					<td><input id="p6891_26" type="radio" <?php if (!(strcmp("17", $array_parametro[25]))) {echo "checked=checked";} ?> value="17" name="novenoA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					<td><input id="p6891_27" type="radio" <?php if (!(strcmp("18", $array_parametro[25]))) {echo "checked=checked";} ?> value="18" name="novenoA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					</tr>
					<tr>
					<td>Carnet de Papel</td>
					<td><input id="p6891_28" name="car_papel" type="checkbox" value="PAPEL" <?php if (strlen($once)>0) {echo "checked='checked'";} ?>/></td>
					<td><input id="p6891_29" type="radio" <?php if (!(strcmp("19", $array_parametro[26]))) {echo "checked=checked";} ?> value="19" name="decimoA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					<td><input id="p6891_30" type="radio" <?php if (!(strcmp("20", $array_parametro[26]))) {echo "checked=checked";} ?> value="20" name="decimoA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					</tr>
					<tr>
					<td>Usuario y Contrase&ntilde;a de acudientes</td>
					<td><input id="p6891_31" name="gen_acu" type="checkbox" value="ACU" <?php if (strlen($doce)>0) {echo "checked='checked'";} ?>/></td>
					<td><input id="p6891_32" type="radio" <?php if (!(strcmp("21", $array_parametro[27]))) {echo "checked=checked";} ?> value="21" name="onceA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					<td><input id="p6891_33" type="radio" <?php if (!(strcmp("22", $array_parametro[27]))) {echo "checked=checked";} ?> value="22" name="onceA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					</tr>
					<tr>
					<td>Usuario y Contrase&ntilde;a de estudiantes</td>
					<td><input id="p6891_46" name="gen_estu" type="checkbox" value="ESTU" <?php if (strlen($diesiseis_estu)>0) {echo "checked='checked'";} ?>/></td>
					<td><input id="p6891_47" type="radio" <?php if (!(strcmp("31", $array_parametro[41]))) {echo "checked=checked";} ?> value="31" name="onceE_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					<td><input id="p6891_48" type="radio" <?php if (!(strcmp("32", $array_parametro[41]))) {echo "checked=checked";} ?> value="32" name="onceE_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					</tr>
					<tr>
					<td>Formulario de inscripcion</td>
					<td><input type="checkbox" id="p6891_34"t name="gen_ins" type="checkbox" value="INS" <?php if (strlen($trece)>0) {echo "checked='checked'";} ?>/></td>
					<td><input id="p6891_35" type="radio" <?php if (!(strcmp("23", $array_parametro[28]))) {echo "checked=checked";} ?> value="23" name="doceA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					<td><input id="p6891_36" type="radio" <?php if (!(strcmp("24", $array_parametro[28]))) {echo "checked=checked";} ?> value="24" name="doceA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					</tr>
					<tr>
					<td>Pacto de convivencia</td>
					<td><input id="p6891_37" name="gen_pac" type="checkbox" value="PAC" <?php if (strlen($catorce)>0) {echo "checked='checked'";} ?>/></td>
					<td><input id="p6891_38" type="radio" <?php if (!(strcmp("25", $array_parametro[29]))) {echo "checked=checked";} ?> value="25" name="treceA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					<td><input id="p6891_39" type="radio" <?php if (!(strcmp("26", $array_parametro[29]))) {echo "checked=checked";} ?> value="26" name="treceA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					</tr>
					<tr>
					<td>Certificado parcial de estudios</td>
					<td><input id="p6891_40" name="cer_actual" type="checkbox" value="PARCER" <?php if (strlen($quince)>0) {echo "checked='checked'";} ?>/></td>
					<td><input id="p6891_41" type="radio" <?php if (!(strcmp("27", $array_parametro[30]))) {echo "checked=checked";} ?> value="27" name="catorceA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					<td><input id="p6891_42" type="radio" <?php if (!(strcmp("28", $array_parametro[30]))) {echo "checked=checked";} ?> value="28" name="catorceA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					</tr>
					<tr>
					<td>Certificado de Estudios a&ntilde;o Anterior</td>
					<td><input id="p6891_43" name="cer_anterior" type="checkbox" value="CER2016,CER2015,CER2014,CER2013,CER2012,CER2011,CER2010,CER2009,CER2008" <?php if (strlen($diesiseis)) {echo "checked='checked'";} ?>/></td>
					<td><input id="p6891_44" type="radio" <?php if (!(strcmp("29", $array_parametro[31]))) {echo "checked=checked";} ?> value="29" name="quinceA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					<td><input id="p6891_45" type="radio" <?php if (!(strcmp("30", $array_parametro[31]))) {echo "checked=checked";} ?> value="30" name="quinceA_<?php echo $row_configuracion['conf_nombre']; ?>"/></td>
					</tr>
					<tr>
				<?php
				$r91=0;
				while($rowDocumentoAutorizado = mysql_fetch_array($sqlDocumentoAutorizado)){
					$r91++;
					$checkedFirmaSi = '';
					$checkedFirmaNo = '';
                    if ($rowDocumentoAutorizado['autoriza'] == 1)
                        $checkedAutoriza = 'checked="checked"';
                    else
                        $checkedAutoriza= '';
                    if ($rowDocumentoAutorizado['firma'] == 1)
                        $checkedFirmaSi = 'checked="checked"';
                    else if ($rowDocumentoAutorizado['firma'] == 2)
                        $checkedFirmaNo= 'checked="checked"';
					?>
						<td ><?php echo $rowDocumentoAutorizado['docu_legal_nombre'];?></td>
							<td ><input id="valida6791_1_<?php echo $r91; ?>"type="checkbox" <?php echo $checkedAutoriza; ?> value="1" name="autoriza_<?php echo $row_configuracion['conf_nombre']; ?><?php echo $rowDocumentoAutorizado['docu_legal_id']; ?>"/></td>
						<td ><input id="valida6791_2_<?php echo $r91; ?>"type="radio" <?php echo $checkedFirmaSi; ?> value="1" name="firma_<?php echo $row_configuracion['conf_nombre']; ?><?php echo $rowDocumentoAutorizado['docu_legal_id']; ?>" /></td>
						<td ><input id="valida6791_3_<?php echo $r91; ?>"type="radio" <?php echo $checkedFirmaNo; ?> value="2" name="firma_<?php echo $row_configuracion['conf_nombre']; ?><?php echo $rowDocumentoAutorizado['docu_legal_id']; ?>" /></td>
					</tr>
					<?php
				}
				?>
				</table>
								<tr>
									<td >
										<a href="verificador_barcode.php" target="_blank">Verificador codigo de Barras</a>
									</td>
								</tr>
			 </td>
			</tr>

		</table>

		<!-- <table border="1" width="100%"> -->
				
					<!-- <tr align="center" > 
						<th colspan="2">Control en el carn&eacute; en papel</th>

						<?php if ($array_parametro[$nmid]=='3') {echo "checked='checked'";} ?>
					</tr> -->

					<!-- <tr>
						<td>Controlar que el carn&eacute; en papel solamente se pueda genera (1) una vez?</td>

						<td><?php
						?>
			
							<select class="sele_mul" onclick="" name="unavez_<?php echo $row_configuracion['conf_nombre']; ?>" id="unavez_<?php echo $row_configuracion['conf_nombre']; ?>">

							<option value="S" <?php if ($array_parametro[42]=='S') {echo "selected=\"selected\"";} ?>>Aplica</option>

							<option value="N" <?php if ($array_parametro[42]=='N') {echo "selected=\"selected\"";} ?>>No Aplica</option>
	
						  </select>

						</td>
					</tr>

					<tr>
						<td>Controlar que el carn&eacute; en papel, &uacute;nicamente se genere a los estudiantes que tienen fotograf&iacute;a en el sistema?</td>
						
						<td>
							
							<select class="sele_mul" onclick="" name="confoto_<?php echo $row_configuracion['conf_nombre']; ?>" id="confoto_<?php echo $row_configuracion['conf_nombre']; ?>">

							<option value="S" <?php if ($array_parametro[43]=='S') {echo "selected=\"selected\"";} ?>>Aplica</option>

							<option value="N" <?php if ($array_parametro[43]=='N') {echo "selected=\"selected\"";} ?>>No Aplica</option>

						  </select>

						</td>
					</tr> -->
			<!-- </table> -->




			 </div>
			<script>
function validar6691() {
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="S,A")
{
// Nuevo //
document.getElementById("borrarp70p1").style.display = "";
}
if(document.getElementById('<?php echo $row_configuracion["conf_nombre"]; ?>').value=="N")
{
document.getElementById("borrarp70p1").style.display = "none";
}
}
addEvent('load', validar6691); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script> 
		<?php
		break;
	case 104:
				$estado = '';
				if(strpos($row_configuracion['conf_valor'],"$")>0)
				{
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
					$estado = $array_parametro[1];
				}
				else
					$parametro = $row_configuracion['conf_valor'];
				?>
				<div class="container_demohrvszv">
			 <table  width="90%" >
				 <tr>
				 <td><b>Aplica</b>
				 <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>

				  </td>
				 </tr>
			</table> </div>
					<?php
		break;
		}// este es el fin
?>
	</td>
</tr>
<?php
}while($row_configuracion = mysql_fetch_assoc($configuracion));
?>
<?php
///Instanciacion de clases para el llamado de los parametros
// include 'ParametrosGenerales.php';
$objetoParametros8=new ParametrosGenerales();  ///Instancio el Objeto ParametroGenerales
$conexionBd=$objetoParametros8->creaConexion();  ///llamo la funcion creaConexion
$numPersiana=8;    
$cantidadParametros=$objetoParametros8  -> returnCantidadParametrosPersiana($conexionBd,$numPersiana); //Retorna la cantidad de parametros que hay dentro de la persiana
$cant=0;
while($cant<$cantidadParametros)  //Mientras haya Parametros Entre al while
{
	 	$valor_id_parametro_individual=$objetoParametros8  -> devuelvaParametros($cant);
       $objetoParametros8 ->ejecutarImpresionParametros($numPersiana,$conexionBd,$consecutivo,$valor_id_parametro_individual ); //Ejecuta la impresion de los paramertros individualmente
       $consecutivo=$objetoParametros8 ->returnConsecutivo();    //Retorna el consecutivo
        $informacionDelParametro=$objetoParametros8  ->informacionParametro($conexionBd,$valor_id_parametro_individual);  //Informacion del parametro 
       $cant++;
       // switch($valor_id_parametro_individual)
       // {
       // }
}
?>
</table>
</div>
</div>
</div>
</div>
</div>
<?php
if($totalRows_configuracion)
{
mysql_data_seek($configuracion,0);
mysql_select_db($database_sygescol, $sygescol);
	$query_configuracion = "SELECT conf_sygescol.conf_id, conf_sygescol.conf_nombre, conf_sygescol.conf_valor, conf_sygescol.conf_descri, conf_sygescol.conf_nom_ver, encabezado_parametros.titulo
								FROM conf_sygescol  INNER JOIN encabezado_parametros on encabezado_parametros.id_param=conf_sygescol.conf_id
							WHERE conf_sygescol.conf_estado = 0
								AND conf_sygescol.conf_id IN (105,140,158)  ORDER BY encabezado_parametros.id_orden ";
	$configuracion = mysql_query($query_configuracion, $sygescol) or die(mysql_error());
	$row_configuracion = mysql_fetch_assoc($configuracion);
}?>
<?php
include ("conb.php");$registrosi=mysqli_query($conexion,"select * from conf_sygescol_adic where id=9")or die("Problemas en la Consulta".mysqli_error());while ($regi=mysqli_fetch_array($registrosi)){$coloracordi=$regi['valor'];}
?>
<div class="container_demohrvszv_caja_1">
<div class="accordion_example2wqzx_caja_2">
			<div class="accordion_inwerds_caja_3">
				<div class="acc_headerfgd_caja_titulo" id="parametros_fotografica" style="background-color: <?php echo $coloracordi ?>"><center><strong>9. PAR&Aacute;METROS PARA SESI&Oacute;N FOTOGR&Aacute;FICA</strong></center><br /><center><input type="radio" value="rojoi" name="coloresi">Si&nbsp;&nbsp;<input type="radio" value="naranjai" name="coloresi">No</div></center>
				<div class="acc_contentsaponk_caja_4">
<div class="grevdaiolxx_caja_5">
<table  align="center" width="85%" class="centro" cellpadding="10" class="formulario" border="1">
<tr>
	<th class="formulario"  style="background: #3399FF;" >Consecutivo </th>
	<th class="formulario"  style="background: #3399FF;" >Id Par&aacute;metro </th>
	<th class="formulario" >Tipo de Par&aacute;metro</th>
    <th class="formulario" >Detalle del Par&aacute;metro</th>
	<th class="formulario">Selecci&oacute;n</th>
	</tr>
	<?php
	do { $consecutivo++;
	?>
	<td class="formulario"   id="Consecutivo<?php echo $consecutivo; ?>" ><strong> <?php echo $consecutivo; ?></strong></td>
	<td class="formulario"  id="Parametro<?php echo $row_configuracion['conf_id']; ?>" ><strong><?php echo $row_configuracion['conf_id'] ?></strong></td>
<td valign="top"><strong>
<div class="container_demohrvszv_caja_tipo_param">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Tipo de Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx_caja_tipo_param">
<div id="conf_nom_ver">
<div  class="textarea "  align="justify" id="conf_nom_ver-<?php echo $row_configuracion['conf_id'] ?>"><?php echo $row_configuracion['conf_nom_ver']; ?></div>
</div></div></div></div></div></div>
</strong>
</td>
      <td valign="top" width="80%">
     <div class="container_demohrvszv" >
     <div class="accordion_example2wqzx">
     <div class="accordion_inwerds">
     <div class="acc_headerfgd"  id="cgasvf"><strong>Detalle Par&aacute;metro</strong></div>
     <div class="acc_contentsaponk">
     <div class="grevdaiolxx">
<div id="conf_descri">
     <div  align="justify" class="textarea " id="conf_descri-<?php echo $row_configuracion['conf_id'] ?>" style="width: 90%;" align="justify">
      <?php echo $row_configuracion['conf_descri']; ?>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
 </td>
	<td align="center">
	<?php
	switch($row_configuracion['conf_id'])
	{
	case 105:
			$arregloF = explode("$",$row_configuracion['conf_valor']);
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
			?>
<table>
<tr>
	<td><b>Aplica</b></td>
	</tr>
<tr>
<td>
<select id = "select_foto_carne_acu" name= "select_foto_carne_acu_<?php echo $row_configuracion['conf_nombre']; ?>" class="sele_mul" onclick="validar69105();validar691052()">
<option value="si" <?php if (!(strcmp("si", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
<option value="no" <?php if (!(strcmp("no", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
</select>
</td>
</tr>
</table>
<div class="container_demohrvszv">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>&Iacute;tem</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
<table border="1" style="text-align: center;">
<tr class="fila1"><th>Documento</th><th>Activar</th><th>Desactivar</th></tr>
<tr><td>Carnet Estudiantil</td>
<td><input type="radio" id="p69105_1"<?php if($arregloF[1]==A){ echo 'checked="checked"'; }?> name="a_<?php echo $row_configuracion['conf_nombre']; ?>" value="A"></td>
<td><input type="radio" id="p69105_2"<?php if($arregloF[1]==D){ echo 'checked="checked"'; }?> name="a_<?php echo $row_configuracion['conf_nombre']; ?>" value="D"></td></tr>
<tr><td>Libro de matr&iacute;culas</td>
<td><input type="radio" id="p69105_3"<?php if($arregloF[2]==A){ echo 'checked="checked"'; }?> name="b_<?php echo $row_configuracion['conf_nombre']; ?>" value="A"></td>
<td><input type="radio" id="p69105_4"<?php if($arregloF[2]==D){ echo 'checked="checked"'; }?> name="b_<?php echo $row_configuracion['conf_nombre']; ?>" value="D"></td></tr>
<tr><td>Obs. del Alumno</td>
<td><input type="radio" id="p69105_5"<?php if($arregloF[3]==A){ echo 'checked="checked"'; }?> name="c_<?php echo $row_configuracion['conf_nombre']; ?>" value="A"></td>
<td><input type="radio" id="p69105_6"<?php if($arregloF[3]==D){ echo 'checked="checked"'; }?> name="c_<?php echo $row_configuracion['conf_nombre']; ?>" value="D"></td></tr><tr><td>Planillas Calificaciones</td>
<td><input type="radio" id="p69105_7"<?php if($arregloF[4]==A){ echo 'checked="checked"'; }?> name="d_<?php echo $row_configuracion['conf_nombre']; ?>" value="A"></td>
<td><input type="radio" id="p69105_8"<?php if($arregloF[4]==D){ echo 'checked="checked"'; }?> name="d_<?php echo $row_configuracion['conf_nombre']; ?>" value="D"></td></tr><tr><td>Plataforma Estudiante</td>
<td><input type="radio" id="p69105_9"<?php if($arregloF[5]==A){ echo 'checked="checked"'; }?> name="e_<?php echo $row_configuracion['conf_nombre']; ?>" value="A"></td>
<td><input type="radio" id="p69105_10"<?php if($arregloF[5]==D){ echo 'checked="checked"'; }?> name="e_<?php echo $row_configuracion['conf_nombre']; ?>" value="D"></td></tr>
<tr><td>Adm&oacute;n. Fotogr&aacute;fica</td>
<td><input type="radio" id="p69105_11"<?php if($arregloF[6]==A){ echo 'checked="checked"'; }?> name="f_<?php echo $row_configuracion['conf_nombre']; ?>" value="A"></td>
<td><input type="radio"id="p69105_12" <?php if($arregloF[6]==D){ echo 'checked="checked"'; }?> name="f_<?php echo $row_configuracion['conf_nombre']; ?>" value="D"></td></tr>
<tr><td>Bolet&iacute;n detallado</td>
<td><input type="radio" id="p69105_13"<?php if($arregloF[7]==A){ echo 'checked="checked"'; }?> name="g_<?php echo $row_configuracion['conf_nombre']; ?>" value="A"></td>
<td><input type="radio" id="p69105_14"<?php if($arregloF[7]==D){ echo 'checked="checked"'; }?> name="g_<?php echo $row_configuracion['conf_nombre']; ?>" value="D"></td></tr>
<tr><td>Bolet&iacute;n Resumen</td>
<td><input type="radio" id="p69105_15"<?php if($arregloF[8]==A){ echo 'checked="checked"'; }?> name="h_<?php echo $row_configuracion['conf_nombre']; ?>" value="A"></td>
<td><input type="radio" id="p69105_16"<?php if($arregloF[8]==D){ echo 'checked="checked"'; }?> name="h_<?php echo $row_configuracion['conf_nombre']; ?>" value="D"></td></tr>
<tr><td>Informe Final</td>
<td><input type="radio" id="p69105_17"<?php if($arregloF[9]==A){ echo 'checked="checked"'; }?> name="i_<?php echo $row_configuracion['conf_nombre']; ?>" value="A"></td>
<td><input type="radio" id="p69105_18"<?php if($arregloF[9]==D){ echo 'checked="checked"'; }?> name="i_<?php echo $row_configuracion['conf_nombre']; ?>" value="D"></td></tr>
<tr><td>Consulta estudiantes</td>
<td><input type="radio" id="p69105_19"<?php if($arregloF[10]==A){ echo 'checked="checked"'; }?> name="j_<?php echo $row_configuracion['conf_nombre']; ?>" value="A"></td>
<td><input type="radio" id="p69105_20"<?php if($arregloF[10]==D){ echo 'checked="checked"'; }?> name="j_<?php echo $row_configuracion['conf_nombre']; ?>" value="D"></td></tr>
<tr><td>Registro Biom&eacute;trico</td>
<td><input type="radio" id="p69105_21"<?php if($arregloF[11]==A){ echo 'checked="checked"'; }?> name="k_<?php echo $row_configuracion['conf_nombre']; ?>" value="A"></td>
<td><input type="radio" id="p69105_22"<?php if($arregloF[11]==D){ echo 'checked="checked"'; }?> name="k_<?php echo $row_configuracion['conf_nombre']; ?>" value="D"></td></tr>
<tr><td>Matr&iacute;cula por Biomet</td>
<td><input type="radio" id="p69105_23"<?php if($arregloF[12]==A){ echo 'checked="checked"'; }?> name="l_<?php echo $row_configuracion['conf_nombre']; ?>" value="A"></td>
<td><input type="radio" id="p69105_24"<?php if($arregloF[12]==D){ echo 'checked="checked"'; }?> name="l_<?php echo $row_configuracion['conf_nombre']; ?>" value="D"></td></tr>
<tr><td>Cuadro de Honor</td>
<td><input type="radio" id="p69105_25"<?php if($arregloF[13]==A){ echo 'checked="checked"'; }?> name="m_<?php echo $row_configuracion['conf_nombre']; ?>" value="A"></td>
<td><input type="radio" id="p69105_26"<?php if($arregloF[13]==D){ echo 'checked="checked"'; }?> name="m_<?php echo $row_configuracion['conf_nombre']; ?>" value="D"></td></tr>
<tr><td>Gobierno Escolar</td>
<td><input type="radio" id="p69105_27"<?php if($arregloF[14]==A){ echo 'checked="checked"'; }?> name="n_<?php echo $row_configuracion['conf_nombre']; ?>" value="A"></td>
<td><input type="radio" id="p69105_28"<?php if($arregloF[14]==D){ echo 'checked="checked"'; }?> name="n_<?php echo $row_configuracion['conf_nombre']; ?>" value="D"></td></tr>
</tr>
</table>
<script>
function validar69105() {
if(document.getElementById('select_foto_carne_acu').value=="si")
{
document.getElementById("p69105_1").disabled = false;
document.getElementById("p69105_2").disabled = false;
document.getElementById("p69105_3").disabled = false;
document.getElementById("p69105_4").disabled = false;
document.getElementById("p69105_5").disabled = false;
document.getElementById("p69105_6").disabled = false;
document.getElementById("p69105_7").disabled = false;
document.getElementById("p69105_8").disabled = false;
document.getElementById("p69105_9").disabled = false;
document.getElementById("p69105_10").disabled = false;
document.getElementById("p69105_11").disabled = false;
document.getElementById("p69105_12").disabled = false;
document.getElementById("p69105_13").disabled = false;
document.getElementById("p69105_14").disabled = false;
document.getElementById("p69105_15").disabled = false;
document.getElementById("p69105_16").disabled = false;
document.getElementById("p69105_17").disabled = false;
document.getElementById("p69105_18").disabled = false;
document.getElementById("p69105_19").disabled = false;
document.getElementById("p69105_20").disabled = false;
document.getElementById("p69105_21").disabled = false;
document.getElementById("p69105_22").disabled = false;
document.getElementById("p69105_23").disabled = false;
document.getElementById("p69105_24").disabled = false;
document.getElementById("p69105_25").disabled = false;
document.getElementById("p69105_26").disabled = false;
document.getElementById("p69105_27").disabled = false;
document.getElementById("p69105_28").disabled = false;
}
}
function validar691052() {
if(document.getElementById('select_foto_carne_acu').value=="no")
{
document.getElementById("p69105_1").disabled = true;
document.getElementById("p69105_2").disabled = true;
document.getElementById("p69105_3").disabled = true;
document.getElementById("p69105_4").disabled = true;
document.getElementById("p69105_5").disabled = true;
document.getElementById("p69105_6").disabled = true;
document.getElementById("p69105_7").disabled = true;
document.getElementById("p69105_8").disabled = true;
document.getElementById("p69105_9").disabled = true;
document.getElementById("p69105_10").disabled = true;
document.getElementById("p69105_11").disabled = true;
document.getElementById("p69105_12").disabled = true;
document.getElementById("p69105_13").disabled = true;
document.getElementById("p69105_14").disabled = true;
document.getElementById("p69105_15").disabled = true;
document.getElementById("p69105_16").disabled = true;
document.getElementById("p69105_17").disabled = true;
document.getElementById("p69105_18").disabled = true;
document.getElementById("p69105_19").disabled = true;
document.getElementById("p69105_20").disabled = true;
document.getElementById("p69105_21").disabled = true;
document.getElementById("p69105_22").disabled = true;
document.getElementById("p69105_23").disabled = true;
document.getElementById("p69105_24").disabled = true;
document.getElementById("p69105_25").disabled = true;
document.getElementById("p69105_26").disabled = true;
document.getElementById("p69105_27").disabled = true;
document.getElementById("p69105_28").disabled = true;
}
}
addEvent('load', validar69105); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
  addEvent('load', validar691052); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script></div>
</div>
</div>
</div>
</div>
			<?php
		break;
		case 140:
		$valores140 = explode("$", $row_configuracion['conf_valor']);
/*
print_r($valores140);
*/
		?>
<div class="container_demohrvszv">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Boletin Detallado</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
					<center>
		<table>
			<tr>
				<td><input  type="checkbox"  <?php if (strpos($row_configuracion['conf_valor'],"1") == true)  {echo "checked=checked";} ?> value="1" name="bloqueo_foto_detallado_<?php echo $row_configuracion['conf_nombre']; ?>" /></td><td>Foto</td>
			</tr>
			<tr>
				<td><input  id="validar67991_4"type="checkbox"  <?php if (strpos($row_configuracion['conf_valor'],"2") == true)  {echo "checked=checked";} ?> value="2" name="bloqueo_huella_detallado_<?php echo $row_configuracion['conf_nombre']; ?>" /></td><td>Huella</td>
			</tr>
			<tr>
				<td><input id="validar67991_5"type="checkbox"  <?php if (strpos($row_configuracion['conf_valor'],"3") == true)  {echo "checked=checked";} ?> value="3" name="bloqueo_firma_detallado_<?php echo $row_configuracion['conf_nombre']; ?>" /></td><td>Firma</td>
			</tr>
			<tr>
				<td><input id="validar67991_6"type="checkbox"  <?php if (strpos($row_configuracion['conf_valor'],"4") == true)  {echo "checked=checked";} ?> value="4" name="bloqueo_carne_detallado_<?php echo $row_configuracion['conf_nombre']; ?>" /></td><td>Carn&eacute;</td>
			</tr>
	</table>
</center>
</div>
</div>
</div>
</div>
</div>
<div class="container_demohrvszv">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Boletin Resumen</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
					<center>
		<table>
			<tr>
				<td><input type="checkbox"   <?php if  ($valores140[5]=='5') {echo "checked='checked'";} ?> value="5" name="bloqueo_foto_resumen_<?php echo $row_configuracion['conf_nombre']; ?>" /></td><td>Foto</td>
			</tr>
			<tr>
				<td><input type="checkbox"   <?php if  ($valores140[6]=='6') {echo "checked='checked'";} ?> value="6" name="bloqueo_huella_resumen_<?php echo $row_configuracion['conf_nombre']; ?>" /></td><td>Huella</td>
			</tr>
			<tr>
				<td><input type="checkbox"   <?php if  ($valores140[7]=='7') {echo "checked='checked'";} ?> value="7" name="bloqueo_firma_resumen_<?php echo $row_configuracion['conf_nombre']; ?>" /></td><td>Firma</td>
			</tr>
			<tr>
				<td><input type="checkbox"   <?php if  ($valores140[8]=='8') {echo "checked='checked'";} ?> value="8" name="bloqueo_carne_resumen_<?php echo $row_configuracion['conf_nombre']; ?>" /></td><td>Carn&eacute;</td>
			</tr>
		</table>
		</center>
</div>
</div>
</div>
</div>
</div>
<div class="container_demohrvszv">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Proceso registro de la matr&iacute;cula.</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
					<center>
		<table>
			<tr>
				<td><input type="checkbox"  <?php if  ($valores140[9]=='5') {echo "checked='checked'";} ?> value="5" name="bloqueo_foto_resumen1_<?php echo $row_configuracion['conf_nombre']; ?>" /></td><td>Foto</td>
			</tr>
			<tr>
				<td><input type="checkbox"  <?php if  ($valores140[10]=='6') {echo "checked='checked'";} ?> value="6" name="bloqueo_huella_resumen1_<?php echo $row_configuracion['conf_nombre']; ?>" /></td><td>Huella</td>
			</tr>
			<tr>
				<td><input type="checkbox"  <?php if  ($valores140[11]=='7') {echo "checked='checked'";} ?> value="7" name="bloqueo_firma_resumen1_<?php echo $row_configuracion['conf_nombre']; ?>" /></td><td>Firma</td>
			</tr>
			<tr>
				<td><input type="checkbox" <?php if  ($valores140[12]=='8') {echo "checked='checked'";} ?> value="8" name="bloqueo_carne_resumen1_<?php echo $row_configuracion['conf_nombre']; ?>" /></td><td>Carn&eacute;</td>
			</tr>
		</table>
		</center>
</div>
</div>
</div>
</div>
</div>
		</p>
		<?php
		break;
		case 158:
		if ($row_configuracion['conf_nombre'] !="registro_ina_escuela_nueva" ) {
		?>
		<label>
		<b>Aplica:
		  <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
		  	<option value="N" <?php if (!(strcmp("N", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>No</option>
			<option value="S" <?php if (!(strcmp("S", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Si</option>
		  </select>
		</label>
		<?php
		}
		break;
		}// este es el fin
?>
	</td>
</tr>
<?php
}while($row_configuracion = mysql_fetch_assoc($configuracion));
?>
<?php
///Instanciacion de clases para el llamado de los parametros
// include 'ParametrosGenerales.php';
$objetoParametros9=new ParametrosGenerales();  ///Instancio el Objeto ParametroGenerales
$conexionBd=$objetoParametros9->creaConexion();  ///llamo la funcion creaConexion
$numPersiana=9;    
$cantidadParametros=$objetoParametros9  -> returnCantidadParametrosPersiana($conexionBd,$numPersiana); //Retorna la cantidad de parametros que hay dentro de la persiana
$cant=0;
while($cant<$cantidadParametros)  //Mientras haya Parametros Entre al while
{
	 	$valor_id_parametro_individual=$objetoParametros9  -> devuelvaParametros($cant);
       $objetoParametros9 ->ejecutarImpresionParametros($numPersiana,$conexionBd,$consecutivo,$valor_id_parametro_individual ); //Ejecuta la impresion de los paramertros individualmente
       $consecutivo=$objetoParametros9 ->returnConsecutivo();    //Retorna el consecutivo
        $informacionDelParametro=$objetoParametros9  ->informacionParametro($conexionBd,$valor_id_parametro_individual);  //Informacion del parametro 
       $cant++;
       // switch($valor_id_parametro_individual)
       // {
       // }
}
?>
</table>
</div>
</div>
</div>
</div>
</div>
<!-- -------------------------------------------- PARAMETROS VIGENCIA DE TIEMPOS -------------------------------- -->
<?php
if($totalRows_configuracion)
{
mysql_data_seek($configuracion,0);
mysql_select_db($database_sygescol, $sygescol);
	$query_configuracion = "SELECT conf_sygescol.conf_id, conf_sygescol.conf_nombre, conf_sygescol.conf_valor, conf_sygescol.conf_descri, conf_sygescol.conf_nom_ver, encabezado_parametros.titulo
								FROM conf_sygescol  INNER JOIN encabezado_parametros on encabezado_parametros.id_param=conf_sygescol.conf_id
							WHERE conf_sygescol.conf_estado = 0
								AND conf_sygescol.conf_id IN (128,153,159)  ORDER BY encabezado_parametros.id_orden ";
	$configuracion = mysql_query($query_configuracion, $sygescol) or die(mysql_error());
	$row_configuracion = mysql_fetch_assoc($configuracion);
}
?>
<?php
include ("conb.php");$registrosj=mysqli_query($conexion,"select * from conf_sygescol_adic where id=10")or die("Problemas en la Consulta".mysqli_error());while ($regj=mysqli_fetch_array($registrosj)){$coloracordj=$regj['valor'];}
?>
<div class="container_demohrvszv_caja_1">
<div class="accordion_example2wqzx_caja_2">
			<div class="accordion_inwerds_caja_3">
				<div class="acc_headerfgd_caja_titulo" id="parametros_modulos_nuevos" style="background-color: <?php echo $coloracordj ?>"><center><strong>10. PAR&Aacute;METROS PARA ACTIVACI&Oacute;N DE M&Oacute;DULOS NUEVOS</strong></center><br /><center><input type="radio" value="rojoj" name="coloresj">Si&nbsp;&nbsp;<input type="radio" value="naranjaj" name="coloresj">No</div></center>
				<div class="acc_contentsaponk_caja_4">
<div class="grevdaiolxx_caja_5">
<table  align="center" width="85%" class="centro" cellpadding="10" class="formulario" border="1">
	<tr>
	<th class="formulario"  style="background: #3399FF;" >Consecutivo </th>
	<th class="formulario"  style="background: #3399FF;" >Id Par&aacute;metro </th>
	<th class="formulario" >Tipo de Par&aacute;metro</th>
    <th class="formulario" >Detalle del Par&aacute;metro</th>
	<th class="formulario">Selecci&oacute;n</th>
	</tr>
	<?php
	do { $consecutivo++;
	?>
	<td class="formulario"   id="Consecutivo<?php echo $consecutivo; ?>" ><strong> <?php echo $consecutivo; ?></strong></td>
	<td class="formulario"  id="Parametro<?php echo $row_configuracion['conf_id']; ?>" ><strong><?php echo $row_configuracion['conf_id'] ?></strong></td>
<td valign="top"><strong>
<div class="container_demohrvszv_caja_tipo_param">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Tipo de Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx_caja_tipo_param">
<div id="conf_nom_ver">
<div  class="textarea "  align="justify" id="conf_nom_ver-<?php echo $row_configuracion['conf_id'] ?>"><?php echo $row_configuracion['conf_nom_ver']; ?></div>
</div>
</div></div></div></div></div>
</strong>
</td>
<td valign="top" width="80%">
<div class="container_demohrvszv" >
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"  id="cgasvf"><strong>Detalle Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
<div id="conf_descri">
      <div  align="justify" class="textarea " id="conf_descri-<?php echo $row_configuracion['conf_id'] ?>" style="width: 90%;" align="justify">
 <?php echo $row_configuracion['conf_descri']; ?>
  </div>
</div>
</div>
</div>
</div>
</div>
</div>
 </td>
	<td align="center">
	<?php
	switch($row_configuracion['conf_id'])
	{
		case 128:
if ($row_configuracion['conf_nombre'] !="registro_ina_escuela_nueva" ) {
	?>
	<label>
	<b>Aplica:
  <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
  	<option value="N" <?php if (!(strcmp("N", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>No</option>
	<option value="S" <?php if (!(strcmp("S", $row_configuracion['conf_valor']))) {echo "selected=\"selected\"";} ?>>Si</option>
  </select>
</label>
	<?php
	}
		break;
		case 153:
			$variablesSep = explode("@",$row_configuracion['conf_valor']);
			$valoresCertificados = explode(",",$variablesSep[0]); // Certificados
			$valoresConstancias = explode(",",$variablesSep[1]); // Constancias
            $valoresConstancias2 = $variablesSep[2]; // Constancias
          //echo $variablesSep[0].'<br>'.$variablesSep[1].'<br>'.$variablesSep[2];
		?>
<table>	<tr><b>Aplica</b>
				  	  <select class="sele_mul op" name="pali_<?php echo $row_configuracion['conf_nombre']; ?>" id="parametro74153" onclick="validar74153()">
							<option value="S" <?php if (!(strcmp("S", $valoresConstancias2['conf_valor']))) {echo "selected=\"selected\"";} ?>>Si</option>
							<option value="N" <?php if (!(strcmp("N", $valoresConstancias2['conf_valor']))) {echo "selected=\"selected\"";} ?>>No</option>
						  </select></table>
<div class="container_demohrvszv">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd">Caso 1</div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
		<table >
<tr>
<td><input type="radio" id="radio1534" onclick="javascript:determinarEstadoCamposs153b();"<?php if($valoresCertificados[0]==I){ echo 'checked="checked"'; }?> name="certificado_<?php echo $row_configuracion['conf_nombre']; ?>" value="I"></td><td>Asignarle<input type="text" id="v73531" onkeypress="return justNumbers(event);"value="<?php echo $valoresCertificados[1];?>" name="valCertificado_<?php echo $row_configuracion['conf_nombre']; ?>" size="5">como nota de la <b style="color:red;">Autoevaluacion.</b></td></td>
			</tr>
			<tr>
				<td><input type="radio" id="radio1533"onclick="javascript:determinarEstadoCamposs1532();"<?php if($valoresCertificados[0]==F){ echo 'checked="checked"'; }?> name="certificado_<?php echo $row_configuracion['conf_nombre']; ?>" value="F"></td><td>La califica el docente.</td>
			</tr>
			</table>
			</div></div></div></div></div>
<div class="container_demohrvszv">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd">Caso 2</div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
					<table>
	<tr>
				<td><input type="radio" id="radio1532"onclick="javascript:determinarEstadoCamposs153a();"<?php if($valoresConstancias[0]==I){ echo 'checked="checked"'; }?> name="constancia_<?php echo $row_configuracion['conf_nombre']; ?>" value="I"></td><td>Asignarle<input type="text"id="v73532"value="<?php echo $valoresConstancias[1];?>" name="valConstancia_<?php echo $row_configuracion['conf_nombre']; ?>" size="5"> como nota</td>
			</tr>
			<tr>
				<td><input type="radio" id="radio1531"onclick="javascript:determinarEstadoCamposs153();"<?php if($valoresConstancias[0]==F){ echo 'checked="checked"'; }?> name="constancia_<?php echo $row_configuracion['conf_nombre']; ?>" value="F"></td><td>La califica el docente.</td>
			</tr>
		</table>
		</div></div></div></div></div>
<script type="text/javascript">
function determinarEstadoCamposs153() {
if(document.getElementById("radio1531").checked = true){
    document.getElementById("v73532").disabled = true;
}
}
function determinarEstadoCamposs153a() {
if(document.getElementById("radio1532").checked = true)
{
    document.getElementById("v73532").disabled = false;
}
}
function determinarEstadoCamposs1532() {
if(document.getElementById("radio1533").checked = true){
    document.getElementById("v73531").disabled = true;
}
}
function determinarEstadoCamposs153b() {
if(document.getElementById("radio1534").checked = true){
    document.getElementById("v73531").disabled = false;
}
}
</script>
<script>
function validar74153(){
if(document.getElementById('parametro74153').value=="S")
{
document.getElementById("radio1531").disabled = false;
document.getElementById("v73532").disabled = false;
document.getElementById("radio1532").disabled = false;
document.getElementById("radio1533").disabled = false;
document.getElementById("v73531").disabled = false;
document.getElementById("radio1534").disabled = false;
}
if(document.getElementById('parametro74153').value=="N")
{
document.getElementById("radio1531").disabled = true;
document.getElementById("v73532").disabled = true;
document.getElementById("radio1532").disabled = true;
document.getElementById("radio1533").disabled = true;
document.getElementById("v73531").disabled = true;
document.getElementById("radio1534").disabled = true;
}
}
	addEvent('load', validar74153); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
		<?php
		break;
	case 159: // en la pagina aprece como el parametro 81 y en el código como el 159.
		// permite recibir la cadena ya validad y la guarda en la base de datos
		function actualizarSolucionParametro($cadena){
			$sql_upd_configuracion = "UPDATE conf_sygescol SET conf_valor = '".$cadena."' WHERE conf_nombre LIKE '".$row_configuracion['conf_nombre']."'";
			$upd_configuracion = mysql_query($sql_upd_configuracion, $sygescol) or die("No se pudo actualizar los parametros del sistema");
		}
		// permite validar los datos de la solucion del parametro 159 y crea una cadena que ya queda lista para guardar en la base de datos
		function crearCadenaAGuardar($cadena){
			define("APLICA", 0); // representa la seccion de aplica o no aplica de la solucion del paramtero 159
        	define("NO", 0); // value del radio button de la opcion aplica de la solucion del parametro 159
			define("SI", 1); // value del radio button de la opcion aplica de la solucion del parametro 159
			define("ANIOS", 1); // representa la seccion de años a tener en cuenta de la solucion del paramtero 159
			define("AREAS", 2); // representa la seccion de las areas a tener en cuenta de la solucion del lparametro 159
			define("AREAS_ESPECIFICAS", "8"); // representa el value de la opcion de areas especificas del la seccion de areas a tener en cuenta de la solucion del parametro 159
			define("CALIFICACION", 3); // representa la seccion de calificacion exigida de la solucion del parametro 159
			define("CALIFICACION_MINIMA", "11"); // value de la opcion de calificacion especifica minima de la seccion de calificacion exigida de la solucion del parametro 159
			define("CERTIFICADO", 4); // representa la seccion de modo de elaborar el certificado de la solucion del parametro 159
			define("AREA1", 5); // representa la posicion en la que se encuentra dentro del arreglo la primer area especifica
			define("AREA12", 16); // representa la posicion en la que se encuentra dentro del arreglo la ultima area especifica
			define("CAMPO_CALIFICACION_MINIMA", 17); // determina la posicion que ocupa la calificacion minima en el arreglo
			define("MINIMO_CALIFICACION_MINIMA", 1); // determina el minimo de calificacion permitida en el campo de calificacion minima
			define("MAXIMO_CALIFICACION_MINIMA", 5); // determina el maximo de calificacion permitida en el campo de calificacion minima
			// convierte una cadena de caracteres en un arreglo, creando columnas y dividiendo la cadena cada vez que se encuentra un caracter dolar ($)
			$solucionParametro159 = explode("$",$cadena);
			$cadenaCorrecta = ""; // esta variable contendra la cadena validada la cual sera guardada en la BD
			if( $solucionParametro159[APLICA] == SI ){ // si se selecciono la opcion si en la seccion de aplica de la solucion del parametro 159
				$cadenaCorrecta = SI; // le doy a la variable el valor de 1
				if( $solucionParametro159[ANIOS] == ""){ // si no selecciono una opcion en la seccion de años a tener en cuenta en la solucion del parametro 159
					$cadenaCorrecta = NO; // le doy a la variable el valor de 0
				}else{ // si si selecciono una opcion en la seccion de años a tener en cuenta
					$cadenaCorrecta .= "$".$solucionParametro159[ANIOS]; // concateno a la varible un signo dolar y la opcion que eligio
					if($solucionParametro159[AREAS] == ""){ // si en la seccion de areas a tener en cuenta no eligio ninguna opcion
						$cadenaCorrecta = NO;	// la variable toma el valor de 0
					}else{ // si si selecciono una opcion en la seccion de areas a tener en cuenta
						$cadenaCorrecta .= "$".$solucionParametro159[AREAS]; // le concateno a la variable un signo dolar y la opcion seleccionanda
						if($solucionParametro159[CALIFICACION] == ""){ // si no escogio ninguna opcion en la seccion de calificacion exigida
							$cadenaCorrecta = NO; // la variable toma el valor de 0
						}else{ // si si toma una opcion en la seccion de calificacion a tener en cuenta
							$cadenaCorrecta .= "$".$solucionParametro159[CALIFICACION]; // le concateno a la variable el signo dolar y la opcion que eleigio
							if($solucionParametro159[CERTIFICADO] == ""){ // si no escogio ninguna opcion en la seecion de modelo de elaborar nuevo certificado
								$cadenaCorrecta = NO; // la variable toma el nombre de 0
							}else{ // si si eligio una opcion en la seccion de modelo de elaborar nueov certificado
								$cadenaCorrecta .= "$".$solucionParametro159[CERTIFICADO]; // le concateno a la variable el signo dolar y la opcion seleccionada
								if($solucionParametro159[AREAS] == AREAS_ESPECIFICAS){ // si en la seccion de areas a tener en cuanta se selecciono la opcion areas especificas
									$tieneAlgunValor = NO; // esta variable me dira si ingresaros las areas especificas o no
									for($i = AREA1; $i <= AREA12; $i++){ // recorro en el arreglo las posiciones donde se guardan las areas especificas ingresadas
										if($solucionParametro159[$i] != ""){ // verifico exiten areas especificas ingresadas
											$tieneAlgunValor++; // aumento el valor de la variable en uno
										}
									}
									if($tieneAlgunValor >= 1){ // verifico si la variable encontro que existian una o más areas especificas ingresadas
										$hayValorRepetido = NO; // esta varaible me dice si hay valores repetidos
										if($tieneAlgunValor > 1){ // si se encontraron más de una area especifica ingresada
											//determino si existen areas especificas repetidas
											for($i = AREA1; $i <= AREA12; $i++){
												for($j = $i + 1; $j <= AREA12; $j++){
													if($solucionParametro159[$i] == $solucionParametro159[$j]){
														$hayValorRepetido++; // aumento la variable cada vez que encuentre que hay areas especificas repetidas
													}
												}
											}
										}
										if($hayValorRepetido){ // si exiten areas especificas repetidas
											$cadenaCorrecta = NO; // la variable toma el valor de 0
										}else{ // si no existen areas repetidas
											for($i = AREA1; $i <= AREA12; $i++){ // recooro todos los campos del arreglo donde se encuentran las areas especificas ingresadas
												$cadenaCorrecta .= "$".$solucionParametro159[$i]; // voy concatenando cada una de las areas especificas ingresadas
											}
										}
									}else{ // si no tiene ninguna area especifica ingresada
										$cadenaCorrecta = NO; // la variable toma el valor de 0
									}
								} // termina el if que determina si en la seccion de areas a tener en cuenta se selecciono la opcion de areas especificas
								if($solucionParametro159[CALIFICACION] == CALIFICACION_MINIMA){ //si en la seccion de calificacion exigida se selecciona calificacion minima exigida especifica
									if($solucionParametro159[CAMPO_CALIFICACION_MINIMA] == ""){ // se verifica si se ingreso la calificacion minima exigida especifica
										$cadenaCorrecta = NO; // si no se ingreso la variable toma el valor de 0
									}else{ // si si ingreso la calificacion minima especifica exigida
										if($solucionParametro159[CAMPO_CALIFICACION_MINIMA] >= MINIMO_CALIFICACION_MINIMA &&
										   $solucionParametro159[CAMPO_CALIFICACION_MINIMA] <= MAXIMO_CALIFICACION_MINIMA) // se verifica si no excede el rango determinado
									   	{
											$cadenaCorrecta .= "$".$solucionParametro159[CAMPO_CALIFICACION_MINIMA]; // se concatena a la variable el signo dolar y la calificacion minima especifica que ingreso
										}else{ // si se sale del rango
											$cadenaCorrecta = NO; // la variable toma el valor de 0
										}
									}
								} // termina el if que dtermina si en la seccion de calificacion exigida se selecciono la opcion de calificacion minima
							}
						}
					}
				}
			}else if( $solucionParametro159[APLICA] == NO || $solucionParametro159[APLICA] == ""){ // si en la seccion de aplica de la solucion del parametro 159 dicen que no aplica o no seleccionan ninguna opcion
				$cadenaCorrecta = NO; // la varaible toma el valor de 0
			}
			return $cadenaCorrecta; // devuelvo la variable con la cadena validada y lista para guardar en la base de datos
		}
		// permite conectarme a la BD (cga) al campo (a) que contiene los ids de las asignaturas y retorna el arreglo con los ids sin repetirse dentro del arreglo
		/*
		function darIdsAsignaturas(){
			global $link, $database_sygescol; // obtengo la variable global link que tiene el objeto conexion a BD y el nombre de la base de BD de sygescol
			mysql_select_db($database_sygescol,$link); // determino que me conectare a la BD de sygescol
			$sel = "SELECT DISTINCT a FROM cga"; // creo la sentencia SQL de consulta jalando los ids en que estan en el campo (a) de la tabla (cga) sin traer los repetidos
			$sql = mysql_query($sel, $link); // ejecuto la sentencia en el query y los resultados los guardos en la variable ($sql)
			$arrayIdsAsignaturas; // creo un variable que contendra los datos consultados en la BD
			$i = 0; // sera utilizada como contador para utilizarse cono indice en el arreglo que contendra los elementos consultados en la BD
			while($fila = mysql_fetch_array($sql)){ // obtiene cada fila de el resultado de la consulta a la BD
				$arrayIdsAsignaturas[$i] = $fila["a"]; // obtengo cada uno de los valores de la consulta a la BD y los guardo en un arreglo
				$i++; // aumento el contador para que guarde el siguiente elemento en el siguiente campo del arreglo
			} // termino ciclo while
			return $arrayIdsAsignaturas; // retorno el arreglo con todos los elementos que fueron consultados en la BD
		}
		$idsAsignaturas = darIdsAsignaturas(); // llamo al metodo para obtener el arreglo y asignalo a una variable
		*/
		/*
		$idAsignaturaMinimo = min($idsAsignaturas);
		$idAsignaturaMaximo = max($idsAsignaturas);
		*/
		// a la varibale $row_configuracion["conf_valor"] que contiene la cadena string separada por signos: $.. le hago un explode para convertirla a arreblo
		// y de esa manera obtener los varoles serparados para elemento del parametro 159.
		$array_parametro159 = explode("$", $row_configuracion["conf_valor"]);
		$aplica_parametro159 = $array_parametro159[0]; // tome el valor de la primer posicion del arreglo que contie el value del input de type radio que determina si esta habilitado o no el paremetro 159
		$anio = $array_parametro159[1]; // tomo el valor de la segunda posicion del arreglo que contiene el value del input de type radio de los años a tener en cuenta...
		$area = $array_parametro159[2]; // tomo el valor de la tercera posicion del arreglo que contiene el value del input de type radio de las areas a tener en cuenta...
		$calificacion = $array_parametro159[3]; // hago lo mismo que arriba pero esta vez para obtener el value del input type radio de la calificacion exigida...
		$certificado = $array_parametro159[4]; // hago lo mismo pero esta vez para obtener el value del input type radio que determina el modelo de nuevo certificado...
		// si la persona eligio áreas especificas entonces esas areas se guardan en los 12 campos que en cuentras en las posiciones del arreglo acontinuacion:
		// los datos de estos campos estas guardados de la misma manera en la que leemos de izquierda a derecha
		$asignatura1_ = $array_parametro159[5]; // valor del primer campo de la primera fila
		$asignatura2_ = $array_parametro159[6]; // valor del siguiente campo a mano derecha
		$asignatura3_ = $array_parametro159[7]; // valor del siguiente campo a mano derecha
		$asignatura4_ = $array_parametro159[8]; // valor del siguiente campo a mano derecha
		$asignatura5_ = $array_parametro159[9]; // valor del primer campo de la sunda fila
		$asignatura6_ = $array_parametro159[10]; // valor del siguiente campo a mano derecha
		$asignatura7_ = $array_parametro159[11]; // valor del siguiente campo a mano derecha
		$asignatura8_ = $array_parametro159[12]; // valor del siguiente campo a mano derecha
		$asignatura9_ = $array_parametro159[13]; // valor del primer campo de la tercera fila
		$asignatura10_ = $array_parametro159[14]; // valor del siguiente campo a mano derecha
		$asignatura11_ = $array_parametro159[15]; // valor del siguiente campo a mano derecha
		$asignatura12_ = $array_parametro159[16]; // valor del siguiente campo a mano derecha
		// en la parte de la calificacion especifica si el usuario determina que va a ingresar la calificacion minima; esta se encuentra en esta poscion del arreglo
		$cal_min = $array_parametro159[17]; // tomo el valor ingresado por medio del campo de type text del arreglo
		?>
		<br>
		<p>
			<label><strong>Aplica: </strong>
				<!--<select  id="apli_param159" name= "aplica_reconsideracion" class="sele_mul">
					<option value="1" <?php //if (strcmp("1", $aplica_parametro159[0])==0) {echo "selected=\"selected\"";} ?> >Si</option>
			 		<option value="0" <?php //if (strcmp("0", $aplica_parametro159[0])==0) {echo "selected=\"selected\"";} ?> >No</option>
				</select>-->
				Si <input  type="radio" onclick="validarinputs741591()"<?php if( strcmp( $aplica_parametro159, "1" ) == 0 ) { echo "checked='checked'"; } ?>  value = "1" name = "aplica_reconsideracion" />
				No <input  type="radio" onclick="validarinputs741592()"<?php if( strcmp( $aplica_parametro159, "0" ) == 0 ) { echo "checked='checked'"; } ?>  value = "0" name = "aplica_reconsideracion" />
			</label>
		</p>
<script type="text/javascript">
function validarinputs741591(){
 document.getElementById("validar7459_1").disabled = false;
  document.getElementById("validar7459_2").disabled = false;
   document.getElementById("validar7459_3").disabled = false;
    document.getElementById("validar7459_4").disabled = false;
     document.getElementById("validar7459_5").disabled = false;
       document.getElementById("validar7459_7").disabled = false;
        document.getElementById("validar7459_8").disabled = false;
         document.getElementById("validar7459_9").disabled = false;
          document.getElementById("validar7459_10").disabled = false;
           document.getElementById("validar7459_11").disabled = false;
            document.getElementById("validar7459_12").disabled = false;
             document.getElementById("validar7459_13").disabled = false;
              document.getElementById("validar7459_14").disabled = false;
}
function validarinputs741592(){
document.getElementById("validar7459_1").disabled = true;
  document.getElementById("validar7459_2").disabled = true;
   document.getElementById("validar7459_3").disabled = true;
    document.getElementById("validar7459_4").disabled = true;
     document.getElementById("validar7459_5").disabled = true;
       document.getElementById("validar7459_7").disabled = true;
        document.getElementById("validar7459_8").disabled = true;
         document.getElementById("validar7459_9").disabled = true;
          document.getElementById("validar7459_10").disabled = true;
           document.getElementById("validar7459_11").disabled = true;
            document.getElementById("validar7459_12").disabled = true;
             document.getElementById("validar7459_13").disabled = true;
              document.getElementById("validar7459_14").disabled = true;
}
	addEvent('load', validarinputs741591); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
</script>
<div class="container_demohrvszv">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>&Iacute;tem</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
		<div id="solucion_parametro159">
			<table>
				<tr>
					<p style="text-align: left; margin:15px;"><strong>A&ntilde;os a tener en cuenta para la consulta de la habilitaci&oacute;n del proceso:</strong></p>
					<p style="text-align: left; margin:15px;">
						<input type="radio" id="validar7459_14"<?php if (strcmp($anio,"1")==0) {echo "checked='checked'";} ?> value="1" name="habilitacion_proceso_anio<?php echo $row_configuracion['conf_nombre']; ?>" />Un a&ntilde;o<br> <br>
						<input type="radio" id="validar7459_13"<?php if (strcmp($anio,"2")==0) {echo "checked='checked'";} ?> value="2" name="habilitacion_proceso_anio<?php echo $row_configuracion['conf_nombre']; ?>" />Dos a&ntilde;os<br> <br>
						<input type="radio" id="validar7459_12"<?php if (strcmp($anio,"3")==0) {echo "checked='checked'";} ?> value="3" name="habilitacion_proceso_anio<?php echo $row_configuracion['conf_nombre']; ?>" />Tres a&ntilde;os<br> <br>
						<input type="radio" id="validar7459_11"<?php if (strcmp($anio,"4")==0) {echo "checked='checked'";} ?> value="4" name="habilitacion_proceso_anio<?php echo $row_configuracion['conf_nombre']; ?>" />Cuatro a&ntilde;os<br> <br>
					</p>
				</tr>
				<tr>
					<p style="text-align: left; margin:15px;"><strong>&Aacute;reas a Tener en cuenta para la validaci&oacute;n del proceso:</strong></p>
					<p style="text-align: left; margin:15px;">
						<input type="radio" id="validar7459_10"<?php if (strcmp($area,"5")==0) {echo "checked='checked'";} ?> onclick="javascript:determinarEstadoCampos();" value="5" name="habilitacion_proceso_area<?php echo $row_configuracion['conf_nombre']; ?>" />&Aacute;reas Fundamentales Obligatorias<br> <br>
						<input type="radio" id="validar7459_9"<?php if (strcmp($area,"6")==0) {echo "checked='checked'";} ?> onclick="javascript:determinarEstadoCampos();" value="6" name="habilitacion_proceso_area<?php echo $row_configuracion['conf_nombre']; ?>" />Solo las &Aacute;reas de la T&eacute;cnica<br> <br>
						<input type="radio" id="validar7459_8"<?php if (strcmp($area,"7")==0) {echo "checked='checked'";} ?> onclick="javascript:determinarEstadoCampos();" value="7" name="habilitacion_proceso_area<?php echo $row_configuracion['conf_nombre']; ?>" />Cualquier &Aacute;rea de promoci&oacute;n<br> <br>
						<input type="radio" id="validar7459_7"<?php if (strcmp($area,"8")==0) {echo "checked='checked'";} ?> onclick="javascript:determinarEstadoCampos();" value="8" name="habilitacion_proceso_area<?php echo $row_configuracion['conf_nombre']; ?>" />&Aacute;reas espec&iacute;ficas<br> <br>
				</tr>
			</table>
 <table style="width:100%;">
<tr>
<td><center>
		<input type="text" id="operraccionn" placeholder="Materia" style="width:25%;" class="asignatura"><input type="button" value="Consultar" onclick="clcuares()" style="width:35%;" class="asignatura"><input type="text" onkeypress="return justNumbers(event);"
style="width:25%;" id="resulltaaddo" placeholder="Id Materias" class="asignatura">
</center>
</td>
 </tr>
</table>
			<div>
				<table>
				<tr><td><input type = "text" onkeypress="return justNumbers(event);" class="asignatura" style="width:98%;" value="<?php echo $asignatura1_; ?>" name="asignatura1_"></td><td><input type = "text"onkeypress="return justNumbers(event);" class="asignatura" style="width:98%;" value="<?php echo $asignatura2_; ?>" name="asignatura2_"></td><td><input type = "text" onkeypress="return justNumbers(event);"class="asignatura" style="width:98%;" value="<?php echo $asignatura3_; ?>" name="asignatura3_"></td><td><input type = "text" onkeypress="return justNumbers(event);"class="asignatura" style="width:98%;" value="<?php echo $asignatura4_; ?>" name="asignatura4_"></td></tr>
	        	<tr><td><input type = "text" onkeypress="return justNumbers(event);" class="asignatura" style="width:98%;" value="<?php echo $asignatura5_; ?>" name="asignatura5_"></td><td><input type = "text"onkeypress="return justNumbers(event);" class="asignatura" style="width:98%;" value="<?php echo $asignatura6_; ?>" name="asignatura6_"></td><td><input type = "text" onkeypress="return justNumbers(event);" class="asignatura" style="width:98%;" value="<?php echo $asignatura7_; ?>" name="asignatura7_"></td><td><input type = "text" onkeypress="return justNumbers(event);"class="asignatura" style="width:98%;" value="<?php echo $asignatura8_; ?>" name="asignatura8_"></td></tr>
	        	<tr><td><input type = "text" onkeypress="return justNumbers(event);" class="asignatura" style="width:98%;" value="<?php echo $asignatura9_; ?>" name="asignatura9_"></td><td><input type = "text"onkeypress="return justNumbers(event);"  class="asignatura" style="width:98%;" value="<?php echo $asignatura10_; ?>" name="asignatura10_"></td><td><input type = "text"  onkeypress="return justNumbers(event);"class="asignatura" style="width:98%;" value="<?php echo $asignatura11_; ?>" name="asignatura11_"></td><td><input type = "text"onkeypress="return justNumbers(event);" class="asignatura" style="width:98%;" value="<?php echo $asignatura12_; ?>" name="asignatura12_"></td></tr>
				</talbe>
			</div>
			<table>
					</p>
				</tr>
				<tr>
		<p style="text-align: left; margin:15px;"><strong>Calificaci&oacute;n exigida en el registro de las &aacute;reas pendientes para la promoci&oacute;n:</strong></p>
					<p style="text-align: left; margin:15px;">
						<input type="radio" id="validar7459_5"<?php if (strcmp($calificacion,"10")==0) {echo "checked='checked'";} ?> onclick="javascript:determinarEstadoNota();" value="10" name="habilitacion_proceso_<?php echo $row_configuracion['conf_nombre']; ?>"/>Cualquier valor dentro de la escala de b&aacute;sico en adelante<br> <br>
						<input type="radio" id="validar7459_4"<?php if (strcmp($calificacion,"11")==0) {echo "checked='checked'";} ?> onclick="javascript:determinarEstadoNota();" value="11" name="habilitacion_proceso_<?php echo $row_configuracion['conf_nombre']; ?>"/>Una calificaci&oacute;n espec&iacute;fica m&iacute;nima <input type = "text" min="1" max="5" step="0.1" style="width: 15%;"  id="nota" name="calificacion_minima" value = "<?php echo $cal_min ?>"> <br> <br>
					</p>
				</tr>
				<tr>
					<p style="text-align: left; margin:15px;"><strong>Modo de elaborar el nuevo certificado:</strong></p>
					<p style="text-align: left; margin:15px;">
						<input type="radio" id="validar7459_3"<?php if (strcmp($certificado,"12")==0) {echo "checked='checked'";} ?> value="12" name="habilitacion_proceso_certificado<?php echo $row_configuracion['conf_nombre']; ?>" />Hacer un nuevo certificado de estudios, con el nuevo estado acad&eacute;mico y con la fecha de emisi&oacute;n actual<br> <br>
						<input type="radio" id="validar7459_2"<?php if (strcmp($certificado,"13")==0) {echo "checked='checked'";} ?> value="13" name="habilitacion_proceso_certificado<?php echo $row_configuracion['conf_nombre']; ?>" />Adicionar al certificado ya emitido una nota pie de documento, con el detalle de los procesos efectuados (Proceso de superaci&oacute;n de insuficiencias acad&eacute;micas - Nota de superaci&oacute;n - Fecha - Acta - Nueva Nota) &uacute;ltimo estado acad&eacute;mico.<br> <br>
						<input type="radio" id="validar7459_1"<?php if (strcmp($certificado,"14")==0) {echo "checked='checked'";} ?> value="14" name="habilitacion_proceso_certificado<?php echo $row_configuracion['conf_nombre']; ?>" />Cargar al libro final de valoraciones<br> <br>
					</p>
				</tr>
			</table>
		</div>
</div></div></div></div></div>
<script>
function clcuares()
{
var caden_a = document.getElementById('operraccionn').value;
caden_a = caden_a.toLowerCase();
if(caden_a=="CIENCIAS ECONOMICAS" || caden_a=="ciencias economicas" || caden_a=="Ciencias Economicas" || caden_a=="ciencias económicas" || caden_a=="Ciencias Económicas" || caden_a=="CIENCIAS ECONÓMICAS")
{
var caddeena = '31';
var caddeena = caddeena.toLowerCase();
document.getElementById("resulltaaddo").value=caddeena;
}
if(caden_a=="FILOSOFIA" || caden_a=="filosofia" || caden_a=="Filosofia" || caden_a=="filosofía" || caden_a=="Filosofía" || caden_a=="FILOSOFÍA")
{
var caddeena = '43';
var caddeena = caddeena.toLowerCase();
document.getElementById("resulltaaddo").value=caddeena;
}
if(caden_a=="MATEMATICAS" || caden_a=="matematicas" || caden_a=="Matematicas" || caden_a=="matemáticas" || caden_a=="Matemáticas" || caden_a=="MATEMÁTICAS")
{
var caddeena = '29';
var caddeena = caddeena.toLowerCase();
document.getElementById("resulltaaddo").value=caddeena;
}
if(caden_a=="EDUCACION RELIGIOSA" || caden_a=="educacion religiosa" || caden_a=="Educacion Religiosa" || caden_a=="educación Religiosa" || caden_a=="Educación Religiosa" || caden_a=="EDUCACIÓN RELIGIOSA")
{
var caddeena = '27';
var caddeena = caddeena.toLowerCase();
document.getElementById("resulltaaddo").value=caddeena;
}
if(caden_a=="HUMANIDADES LENGUA CASTELLANA" || caden_a=="humanidades lengua castellana" || caden_a=="Humanidades Lengua Castellana")
{
var caddeena = '28';
var caddeena = caddeena.toLowerCase();
document.getElementById("resulltaaddo").value=caddeena;
}
if(caden_a=="EDUCACIÓN FISICA RECREACION Y DEPORTES" || caden_a=="educación fisica recreacion y deportes" || caden_a=="Educación Fisica Recreacion y Deportes" || caden_a=="educación física recreación y deportes" || caden_a=="Educación Física Recreación y Deportes" || caden_a=="EDUCACIÓN FÍSICA RECREACIÓN Y DEPORTES")
{
var caddeena = '25';
var caddeena = caddeena.toLowerCase();
document.getElementById("resulltaaddo").value=caddeena;
}
if(caden_a=="EDUCACION ETICA Y EN VALORES HUMANOS" || caden_a=="educacion etica y en valores humanos" || caden_a=="Educacion Etica y en Valores Humanos" || caden_a=="educación ética y en valores humanos" || caden_a=="Educación Ética y en Valores Humanos" || caden_a=="EDUCACIÓN ÉTICA Y EN VALORES HUMANOS")
{
var caddeena = '24';
var caddeena = caddeena.toLowerCase();
document.getElementById("resulltaaddo").value=caddeena;
}
if(caden_a=="CIENCIAS NATURALES Y EDUCACION AMBIENTAL" || caden_a=="ciencias naturales y educacion ambiental" || caden_a=="Ciencias naturales y educación ambiental" || caden_a=="ciencias naturales y educación ambiental" || caden_a=="Ciencias Naturales y Educación Ambiental" || caden_a=="CIENCIAS NATURALES Y EDUCACIÓN AMBIENTAL")
{
var caddeena = '22';
var caddeena = caddeena.toLowerCase();
document.getElementById("resulltaaddo").value=caddeena;
}
if(caden_a=="CIENCIAS SOCIALES HISTORIA GEOGRAFIA CONSTITUCIÓN POLITICA Y DEMOCRACIA" || caden_a=="ciencias sociales historia geografia constitución politica y democracia" || caden_a=="Ciencias Sociales Historia Geografia Constitución Politica y Democracia" || caden_a=="ciencias sociales historia geografía constitución política y democracia" || caden_a=="Ciencias Sociales Historia Geografía Constitución Política y Democracia" || caden_a=="CIENCIAS SOCIALES HISTORIA GEOGRAFÍA CONSTITUCIÓN POLÍTICA Y DEMOCRACIA")
{
var caddeena = '21';
var caddeena = caddeena.toLowerCase();
document.getElementById("resulltaaddo").value=caddeena;
}
if(caden_a=="CIENCIAS POLITICAS" || caden_a=="ciencias politicas" || caden_a=="Ciencias Politicas" || caden_a=="Ciencias Politicas" || caden_a=="Ciencias Políticas" || caden_a=="CIENCIAS POLÍTICAS")
{
var caddeena = '32';
var caddeena = caddeena.toLowerCase();
document.getElementById("resulltaaddo").value=caddeena;
}
if(caden_a=="TECNOLOGIA E INFORMATICA" || caden_a=="tecnologia e informatica" || caden_a=="Tecnologia e Informatica" || caden_a=="tecnología e informática" || caden_a=="Tecnología e Informática" || caden_a=="TECNOLOGÍA E INFORMÁTICA")
{
var caddeena = '33';
var caddeena = caddeena.toLowerCase();
document.getElementById("resulltaaddo").value=caddeena;
}
if(caden_a=="DIMENSION COGNITIVA" || caden_a=="dimension cognitiva" || caden_a=="Dimension Cognitiva" || caden_a=="dimensión cognitiva" || caden_a=="Dimensión Cognitiva" || caden_a=="DIMENSIÓN COGNITIVA")
{
var caddeena = '35';
var caddeena = caddeena.toLowerCase();
document.getElementById("resulltaaddo").value=caddeena;
}
if(caden_a=="DIMENSION CORPORAL" || caden_a=="dimension corporal" || caden_a=="Dimension Corporal" || caden_a=="dimensión corporal" || caden_a=="Dimensión Corporal" || caden_a=="DIMENSIÓN CORPORAL")
{
var caddeena = '36';
var caddeena = caddeena.toLowerCase();
document.getElementById("resulltaaddo").value=caddeena;
}
if(caden_a=="DIMENSION ETICA ACTITUDES Y VALORES" || caden_a=="dimension etica actitudes y valores" || caden_a=="Dimension Etica Actitudes y Valores" || caden_a=="dimensión ética actitudes y valores" || caden_a=="Dimensión Ética Actitudes y Valores" || caden_a=="DIMENSIÓN ÉTICA ACTITUDES Y VALORES")
{
var caddeena = '39';
var caddeena = caddeena.toLowerCase();
document.getElementById("resulltaaddo").value=caddeena;
}
if(caden_a=="DIMENSION ESTETICA" || caden_a=="dimension estetica" || caden_a=="Dimension Estetica" || caden_a=="dimensión estética" || caden_a=="Dimensión Estética" || caden_a=="DIMENSIÓN ESTÉTICA")
{
var caddeena = '38';
var caddeena = caddeena.toLowerCase();
document.getElementById("resulltaaddo").value=caddeena;
}
if(caden_a=="DIMENSION COMUNICATIVA" || caden_a=="dimension comunicativa" || caden_a=="Dimension Comunicativa" || caden_a=="dimensión comunicativa" || caden_a=="Dimensión Comunicativa" || caden_a=="DIMENSIÓN COMUNICATIVA")
{
var caddeena = '40';
var caddeena = caddeena.toLowerCase();
document.getElementById("resulltaaddo").value=caddeena;
}
if(caden_a=="EMPRENDIMIENTO" || caden_a=="emprendimiento" || caden_a=="Emprendimiento")
{
var caddeena = '42';
var caddeena = caddeena.toLowerCase();
document.getElementById("resulltaaddo").value=caddeena;
}
if(caden_a=="HUMANIDADES IDIOMA EXTRANJERO" || caden_a=="humanidades idioma extranjero" || caden_a=="Humanidades Idioma Extranjero")
{
var caddeena = '44';
var caddeena = caddeena.toLowerCase();
document.getElementById("resulltaaddo").value=caddeena;
}
}
</script>
		<script type="text/javascript">
			<!--
			// permite que si chequeo el input type radio (Si) entonces se habilite la solucion del parametro y si chequeo no oculto la solucion del parametro.
			// permite determinar el estado del campo que contiene la nota especifica en la solucion del parametro 159 dependiendo si activan o desactivan la opcion de de calificacion especifica
			function determinarEstadoNota(){
				// miro si esta activa la opcion calificacion especifica en la solucion del parametro 159
				var notaEspecifica = document.getElementsByName( "habilitacion_proceso_<?php echo $row_configuracion['conf_nombre']; ?>" )[1].checked;
				if( notaEspecifica == true ){ // verifico si esta seleccionada ese input type radio calificiacion especifica
					document.getElementById("nota").disabled = false; // activo el campo para que pueda ingresar la nota especifica
				}else{ // si no esta activada esa opcion de calificacion especifica
					document.getElementById("nota").disabled = true; // desactivo el campo de calificacion especifica
				}
			}
			// permite que al cargar la pagina determinar si debe activar o no el campo de calificacion especifica minima
			function determinarEstadoNotaAlCargar(){
				const CALIFICACION_MINIMA = "11"; // esta constante tiene el valor que identifica al input type radio de la opcion calificacion especifica minima de la solucion del parametro 159
				var opcion_calificacion = "<?php echo $calificacion?>"; // obtiene la opcion que se selecciono y guardo en la BD en la solucion del parametro 159 en la seccion de calificacion exigida
				if(opcion_calificacion == CALIFICACION_MINIMA){ // determino si la opcion que selecciono fue la del calificacion especifica minima
					document.getElementById("nota").disabled = false; // habilito el campo de calificacion minima
				}else{ // la opcion seleccionada no es la de calificacion especifica minima
					document.getElementById("nota").disabled = true; // deshabilito el campo de calificacion minima
				}
			}
			// Permite deshabilitar o habilitar un conjunto de campos en este caso para los 12 campos de la solucion del parametro 159 en la seccion de areas a tener en cuenta
			function setDisabledCampos(campos, valor){ // obtengo el conjunto de campos (array) y el valor (false o true)
				for(var j = 0; j < campos.length; j++){ // recorro el conjunto de campos
					campos[j].disabled = valor; // le asigno el valor que me pasaron por parametro a cada campo del conjunto de campos
				}
			}
			// permite determinar el estado de los campos de la seccion asignaturas a tener en cuenta en la solucion del parametro 159
			function determinarEstadoCampos( ){
				const AREAS_ESPECIFICAS = "8"; // determina el valor de la opcion que se debe elegir para activar los campos que este caso es areas especificas en la solucion del parametro 159
				// obtiene el conjunto de input type radio que contienen las diferentes opciones de la seccion de areas a tener en cuenta de la solucion del parametro 159
				var opciones = document.getElementsByName( "habilitacion_proceso_area<?php echo $row_configuracion['conf_nombre']; ?>" );
				for( var i = 0; i < opciones.length; i++ ){ // recorro el conjunto de input type radio que contienen las opciones
					if(opciones[i].checked == true){ // determino cual opcion esta seleccionada
						campos = document.getElementsByClassName("asignatura"); // obtengo el conjunto de los 12 campos de las asignaturas especificas
						if(opciones[i].value == AREAS_ESPECIFICAS){ // determino si la opcion seleccionada es la de areas especificas
							setDisabledCampos(campos,false); // activo los 12 campos para que ingrese las areas especificas
						}else{ // la opcion seleccionada no es la de areas especificas
							setDisabledCampos(campos,true); // desactivo los 12 campos de areas especificas
						} // termino else
					} // termino if
				} // termino for
			}
			// permite que al cargar la pagina determinar si debe habilitar o no los 12 campos de areas especificas dependiendo de los guardado en la BD anteriormente
			function determinarEstadoCamposAlCargar(){
				const areas_especificas = "8"; // determinia el valor del input type radio que representa la opcion de areas epecificas
				var opcion = "<?php echo $area?>"; // obtengo el valor guardado en la BD que determina la opcion que fue seleccionada y guardad
				var campos = document.getElementsByClassName("asignatura"); // obtengo el conjunto de los 12 campos de las areas especificas
				if (opcion == areas_especificas){ // si el valor traido es igual al valor que identifica la opcion areas especificas
					setDisabledCampos(campos, false); // activo los 12 campos
				}else{ // si el valor tradio es diferente fue porque se selecciono otra opcion diferente a areas especificas
					setDisabledCampos(campos, true); // desactivo los 12 campos
				}
			}
			// permite determinar el estados de la solucion, de los 12 campos de las areas especificas y el campo de calificacion minima al cargar la pagina
			function determinarEstados(){
				 // determina el estado de la solucion del parametro 159
				determinarEstadoCamposAlCargar(); // determina el estado de los 12 campos de las areas especificas
				determinarEstadoNotaAlCargar(); // determinar el estado del campo de la calificacion minima
			}
			// permite determinar si se repite algun area en los 12 campos donde se introducen las areas especificas
			function seRepiteArea(){
				var campos = document.getElementsByClassName("asignatura"); // esta variable almacena el arreglo de los campos
				// determina si se repite algun area y si es asi muestra una alerta
				for(var i = 0; i < campos.length - 1; i++){
					for(var j = i + 1; j < campos.length; j++){
						if(campos[i].value == campos[j].value  && campos[i].value != 0 ){
								sweetAlert("ERROR", "Revise que en el parametro 81 no se repitan areas", "warning");
							return false;
						}
					}
				}
			}
			addEvent('load', determinarEstados, false); // determino que cuando se cargue la pagina habilite o deshabilite la solkucion del parametro
			//-->
		</script>
		<?php
		break;		}// este es el fin
?>
	</td>
</tr>
<?php
}while($row_configuracion = mysql_fetch_assoc($configuracion));
?>
<?php
///Instanciacion de clases para el llamado de los parametros
// include 'ParametrosGenerales.php';
$objetoParametros10=new ParametrosGenerales();  ///Instancio el Objeto ParametroGenerales
$conexionBd=$objetoParametros10->creaConexion();  ///llamo la funcion creaConexion
$numPersiana=10;    
$cantidadParametros=$objetoParametros10  -> returnCantidadParametrosPersiana($conexionBd,$numPersiana); //Retorna la cantidad de parametros que hay dentro de la persiana
$cant=0;
while($cant<$cantidadParametros)  //Mientras haya Parametros Entre al while
{
	 	$valor_id_parametro_individual=$objetoParametros10  -> devuelvaParametros($cant);
       $objetoParametros10 ->ejecutarImpresionParametros($numPersiana,$conexionBd,$consecutivo,$valor_id_parametro_individual ); //Ejecuta la impresion de los paramertros individualmente
       $consecutivo=$objetoParametros10 ->returnConsecutivo();    //Retorna el consecutivo
        $informacionDelParametro=$objetoParametros10  ->informacionParametro($conexionBd,$valor_id_parametro_individual);  //Informacion del parametro 
       $cant++;
       // switch($valor_id_parametro_individual)
       // {
       // }
}
?>
</table>
</div>
</div>
</div>
</div>
</div>
<!---------------------------------------------- FIN PARAMETROS VIGENCIA DE TIEMPOS -------------------------------- -->
<!----------------------------------------------  PARAMETROS PROCESOS ASIGNADOS AL SISTEMA -------------------------------- -->
<?php
if($totalRows_configuracion)
{
	mysql_data_seek($configuracion,0);
mysql_select_db($database_sygescol, $sygescol);
	$query_configuracion = "SELECT conf_sygescol.conf_id, conf_sygescol.conf_nombre, conf_sygescol.conf_valor, conf_sygescol.conf_descri, conf_sygescol.conf_nom_ver, encabezado_parametros.titulo
								FROM conf_sygescol  INNER JOIN encabezado_parametros on encabezado_parametros.id_param=conf_sygescol.conf_id
							WHERE conf_sygescol.conf_estado = 0
								AND conf_sygescol.conf_id IN (136,240,164)  ORDER BY encabezado_parametros.id_orden ";
	$configuracion = mysql_query($query_configuracion, $sygescol) or die(mysql_error());
	$row_configuracion = mysql_fetch_assoc($configuracion);
// aca inicia la otra tabla
}?>
<?php
include ("conb.php");$registrosk=mysqli_query($conexion,"select * from conf_sygescol_adic where id=11")or die("Problemas en la Consulta".mysqli_error());while ($regk=mysqli_fetch_array($registrosk)){$coloracordk=$regk['valor'];}
?>
<div class="container_demohrvszv_caja_1">
		<div class="accordion_example2wqzx_caja_2">
			<div class="accordion_inwerds_caja_3">
				<div class="acc_headerfgd_caja_titulo" id="parametros_vigencia_tiempos" style="background-color: <?php echo $coloracordk ?>"><center><strong>11. PAR&Aacute;METROS PARA CONTROL VIGENCIA DE TIEMPOS</strong></center><br /><center><input type="radio" value="rojok" name="coloresk">Si&nbsp;&nbsp;<input type="radio" value="naranjak" name="coloresk">No</div></center>
				<div class="acc_contentsaponk_caja_4">
					<div class="grevdaiolxx_caja_5">
					<table  align="center" width="85%" class="centro" cellpadding="10" class="formulario" border="1">
	<tr>
	<th class="formulario"  style="background: #3399FF;" >Consecutivo </th>
	<th class="formulario"  style="background: #3399FF;" >Id Par&aacute;metro </th>
	<th class="formulario" >Tipo de Par&aacute;metro</th>
    <th class="formulario" >Detalle del Par&aacute;metro</th>
	<th class="formulario">Selecci&oacute;n</th>
	</tr>
	<?php
	do { $consecutivo++;
	?>
	<td class="formulario"   id="Consecutivo<?php echo $consecutivo; ?>" ><strong> <?php echo $consecutivo; ?></strong></td>
	<td class="formulario"  id="Parametro<?php echo $row_configuracion['conf_id']; ?>" ><strong><?php echo $row_configuracion['conf_id'] ?></strong></td>
<td valign="top"><strong>
<div class="container_demohrvszv_caja_tipo_param">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Tipo de Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx_caja_tipo_param">
<div id="conf_nom_ver">
<div  class="textarea "  align="justify" id="conf_nom_ver-<?php echo $row_configuracion['conf_id'] ?>"><?php echo $row_configuracion['conf_nom_ver']; ?></div>
</div></div></div></div></div></div>
</strong>
</td>
<td valign="top" width="80%">
     <div class="container_demohrvszv" >
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd"  id="cgasvf"><strong>Detalle Par&aacute;metro</strong></div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
<div id="conf_descri">
      <div  align="justify" class="textarea " id="conf_descri-<?php echo $row_configuracion['conf_id'] ?>" style="width: 90%;" align="justify">
      <?php echo $row_configuracion['conf_descri']; ?>
      </div>
</div>
					</div>
				</div>
			</div>
		</div>
</div>
 </td>
	<td align="center">
	<?php
	switch($row_configuracion['conf_id'])
	{
	case 136:
		$valoresP60 = explode("$", $row_configuracion['conf_valor'])
		?>
		<table onmouseover="atenuarCheckboxParametro136()"  >  <!--  Cuando pase mouse por la tabla va a verificar el select  -->
			<tr> <td></td><td><select id='selectParam136' > class="sele_mul" name="a_<?php echo $row_configuracion['conf_nombre']; ?>">
				<option value="S" <?php if (!(strcmp("S", $valoresP60[0]))) {echo "selected=\"selected\"";} ?>>Aplica</option>
				<option value="N" <?php if (!(strcmp("N", $valoresP60[0]))) {echo "selected=\"selected\"";} ?>>No Aplica</option>
			</select></td></tr>
			<tr><td></td><td><b>Si aplica defina:</b></td>
			</tr>
			<tr><td></td><td>
		<table>
			<tr><td><input type="checkbox"  id='camposParam136_1'  <?php if (strpos($row_configuracion['conf_valor'],"c") == true)  {echo "checked=checked";} ?> value="c" name="conc_<?php echo $row_configuracion['conf_nombre']; ?>"></td>
			<td><b>Con control total de acceso al sistema para el coordinador acad&eacute;mico por ausencia de cierre parcial o total de grupos</b></td></tr>
			<tr><td><input type="checkbox"  id='camposParam136_2'  <?php if (strpos($row_configuracion['conf_valor'],"d") == true)  {echo "checked=checked";} ?> value="d" name="cond_<?php echo $row_configuracion['conf_nombre']; ?>"></td>
			<td><b>Con control de acceso al siguiente periodo, para el docente</b></td></tr>
			<tr><td><input type="radio"  id='camposParam136_3'  <?php if (strpos($row_configuracion['conf_valor'],"e") == true)  {echo "checked=checked";} ?> value="e" name="cone_<?php echo $row_configuracion['conf_nombre']; ?>"></td>
			<td><b>Con control de impresion de boletines para el estudiante afectado</b> </td></tr>
			<tr><td><input type="radio"  id='camposParam136_4'  <?php if (strpos($row_configuracion['conf_valor'],"f") == true)  {echo "checked=checked";} ?> value="f" name="cone_<?php echo $row_configuracion['conf_nombre']; ?>"></td>
			<td><b>Con control de impresion de boletines para el grupo afectado</b> </td></tr>
			<tr><td><input type="radio"  id='camposParam136_5'  <?php if (strpos($row_configuracion['conf_valor'],"g") == true)  {echo "checked=checked";} ?> value="g" name="cone_<?php echo $row_configuracion['conf_nombre']; ?>"></td>
			<td><b>Permitir la impresion de boletines, asignando la identidad del docente responsable</b> </td></tr>
		</table>
			</td></tr>
		</table>
		<script>
		function atenuarCheckboxParametro136()
		{
			if(document.getElementById("selectParam136").value=='S')
			{
				document.getElementById("camposParam136_1").disabled=false;
				document.getElementById("camposParam136_2").disabled=false;
				document.getElementById("camposParam136_3").disabled=false;
				document.getElementById("camposParam136_4").disabled=false;
				document.getElementById("camposParam136_5").disabled=false;

			}
			else
			{
				document.getElementById("camposParam136_1").disabled=true;
				document.getElementById("camposParam136_2").disabled=true;
				document.getElementById("camposParam136_3").disabled=true;
				document.getElementById("camposParam136_4").disabled=true;
				document.getElementById("camposParam136_5").disabled=true;
			}
		}
		</script>
		<?php
		break;
case 240:
$array_parametro_240 = explode("$",$row_configuracion['conf_valor']);
$aplica_semana_240 = $array_parametro_240[0];
$checkbox_semana_240 = $array_parametro_240[1];
?>
	<div class="container_demohrvszv">
	<div class="accordion_example2wqzx">
	<div class="accordion_inwerds">
	<div class="acc_headerfgd"><strong>&Iacute;tem</strong></div>
	<div class="acc_contentsaponk">
	<div class="grevdaiolxx">
	<input id="rbtnmoo1"type="radio"  onclick="ocultarjohan()"<?php if ($aplica_semana_240[0]=='1') {echo "checked='checked'";} ?> value="1" name="reasignacion_<?php echo $row_configuracion['conf_nombre']; ?>" />No aplica <b>LA REASIGNACION DE GRUPOS</b> para la institucion educativa <br> <br>
		<input id="rbtnmoo2"type="radio"  onclick="ocultarjohan()" <?php if ($aplica_semana_240[0]=='2') {echo "checked='checked'";} ?> value="2" name="reasignacion_<?php echo $row_configuracion['conf_nombre']; ?>" />Permitir el cambio de curso siempre y cuando, el estudiante tenga calificaciones parciales registradas en el sistema en todas las asignaturas.<br> <br>
		<input id="rbtnmoo"type="radio" onclick="ocultarjohan()" <?php if ($aplica_semana_240[0]=='S') {echo "checked='checked'";} ?> value="S" name="reasignacion_<?php echo $row_configuracion['conf_nombre']; ?>" />Semana a partir de la cual, se exigir&aacute; registro parcial de calificaciones<br /> <br />
		<div id="pmt80240">
 <table>
<tr><td>
<img id="image_semana_1" src="imagenes_param_240/Numero/1.PNG"  width=40;></td>
<td>
<img id="image_semana_2" src="imagenes_param_240/Numero/2.PNG"  width=40;></td>
<td>
<img id="image_semana_3" src="imagenes_param_240/Numero/3.PNG"  width=40;></td>
<td>
<img id="image_semana_4" src="imagenes_param_240/Numero/4.PNG"  width=40;></td>
<td>
<img id="image_semana_5" src="imagenes_param_240/Numero/5.PNG"  width=40;></td>
</tr>
<tr>
<td><center>
<input type="radio" id="radio_imagen_240_1" <?php if ($checkbox_semana_240 == "1") {echo "checked=checked";} ?> name="radio_semana_numero" value="1" onclick="cambiar_imagen_param_semana()"></center></td>
<td><center>
<input type="radio" id="radio_imagen_240_2" <?php if ($checkbox_semana_240 == "2") {echo "checked=checked";} ?> name="radio_semana_numero" value="2" onclick="cambiar_imagen_param_semana()"></center></td>
<td><center>
<input type="radio" id="radio_imagen_240_3" <?php if ($checkbox_semana_240 == "3")  {echo "checked=checked";} ?>  name="radio_semana_numero" value="3" onclick="cambiar_imagen_param_semana()"></center></td><td><center>
<input type="radio"  id="radio_imagen_240_4" <?php if ($checkbox_semana_240 == "4") {echo "checked=checked";} ?> name="radio_semana_numero" value="4" onclick="cambiar_imagen_param_semana()"></center></td><td><center>
<input type="radio"  id="radio_imagen_240_5" <?php if ($checkbox_semana_240 == "5") {echo "checked=checked";} ?> name="radio_semana_numero" value="5" onclick="cambiar_imagen_param_semana()"></center></td>
</tr>
<tr>
<td>
<img id="image_semana_6" src="imagenes_param_240/Numero/6.PNG"  width=40;></td>
<td>
<img id="image_semana_7" src="imagenes_param_240/Numero/7.PNG"  width=40;></td>
<td>
<img id="image_semana_8" src="imagenes_param_240/Numero/8.PNG"  width=40;></td>
<td>
<img id="image_semana_9" src="imagenes_param_240/Numero/9.png"  width=40;></td>
<td>
<img id="image_semana_10" src="imagenes_param_240/Numero/10.png"  width=40;></td></tr>
<tr>
<td><center>
<input type="radio" id="radio_imagen_240_6" <?php if ($checkbox_semana_240 == "6") {echo "checked=checked";} ?>  name="radio_semana_numero" value="6" onclick="cambiar_imagen_param_semana()"></center></td><td><center>
<input type="radio" id="radio_imagen_240_7" <?php if ($checkbox_semana_240 == "7") {echo "checked=checked";} ?>  name="radio_semana_numero" value="7" onclick="cambiar_imagen_param_semana()"></center></td><td><center>
<input type="radio" id="radio_imagen_240_8" <?php if ($checkbox_semana_240 == "8") {echo "checked=checked";} ?>  name="radio_semana_numero" value="8" onclick="cambiar_imagen_param_semana()"></center></td><td><center>
<input type="radio"  id="radio_imagen_240_9" <?php if ($checkbox_semana_240 == "9") {echo "checked=checked";} ?> name="radio_semana_numero" value="9" onclick="cambiar_imagen_param_semana()"></center></td><td><center>
<input type="radio"  id="radio_imagen_240_10" <?php if ($checkbox_semana_240 == "10"){echo "checked=checked";} ?> name="radio_semana_numero" value="10" onclick="cambiar_imagen_param_semana()"></center></td></tr>
</table>
</div>
</div></div></div></div></div>
<script type="text/javascript">
	function ocultarjohan(){
if(document.getElementById('rbtnmoo').checked==true)
{
document.getElementById("pmt80240").style.display = '';
}
if(document.getElementById('rbtnmoo1').checked==true || document.getElementById('rbtnmoo2').checked==true || document.getElementById('rbtnmoo').checked==false)
{
document.getElementById("pmt80240").style.display = 'none';
}
}
addEvent('load', ocultarjohan);// activamos la funcion para que cambie las fotos
</script>
<script>
function aplica_semana_numero_f(){
if(document.getElementById('aplica_semana_numero_id').value=="N"){
document.getElementById("radio_imagen_240_1").checked = false;
document.getElementById("radio_imagen_240_2").checked = false;
document.getElementById("radio_imagen_240_3").checked = false;
document.getElementById("radio_imagen_240_4").checked = false;
document.getElementById("radio_imagen_240_5").checked = false;
document.getElementById("radio_imagen_240_6").checked = false;
document.getElementById("radio_imagen_240_7").checked = false;
document.getElementById("radio_imagen_240_8").checked = false;
document.getElementById("radio_imagen_240_9").checked = false;
document.getElementById("radio_imagen_240_10").checked = false;
document.getElementById("radio_imagen_240_1").disabled = true;
document.getElementById("radio_imagen_240_2").disabled = true;
document.getElementById("radio_imagen_240_3").disabled = true;
document.getElementById("radio_imagen_240_4").disabled = true;
document.getElementById("radio_imagen_240_5").disabled = true;
document.getElementById("radio_imagen_240_6").disabled = true;
document.getElementById("radio_imagen_240_7").disabled = true;
document.getElementById("radio_imagen_240_8").disabled = true;
document.getElementById("radio_imagen_240_9").disabled = true;
document.getElementById("radio_imagen_240_10").disabled = true;
document.getElementById("radio_imagen_240_1").value = "";
document.getElementById("radio_imagen_240_2").value = "";
document.getElementById("radio_imagen_240_3").value = "";
document.getElementById("radio_imagen_240_4").value = "";
document.getElementById("radio_imagen_240_5").value = "";
document.getElementById("radio_imagen_240_6").value = "";
document.getElementById("radio_imagen_240_7").value = "";
document.getElementById("radio_imagen_240_8").value = "";
document.getElementById("radio_imagen_240_9").value = "";
document.getElementById("radio_imagen_240_10").value = "";
document.getElementById("image_semana_1").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_2").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_3").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_4").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_5").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_6").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_7").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_8").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_9").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_10").src = "imagenes_param_240/blanco.PNG";
}
if(document.getElementById('aplica_semana_numero_id').value=="S"){
document.getElementById("image_semana_1").src = "imagenes_param_240/Numero/1.PNG";
document.getElementById("image_semana_2").src = "imagenes_param_240/Numero/2.PNG";
document.getElementById("image_semana_3").src = "imagenes_param_240/Numero/3.PNG";
document.getElementById("image_semana_4").src = "imagenes_param_240/Numero/4.PNG";
document.getElementById("image_semana_5").src = "imagenes_param_240/Numero/5.PNG";
document.getElementById("image_semana_6").src = "imagenes_param_240/Numero/6.PNG";
document.getElementById("image_semana_7").src = "imagenes_param_240/Numero/7.PNG";
document.getElementById("image_semana_8").src = "imagenes_param_240/Numero/8.PNG";
document.getElementById("image_semana_9").src = "imagenes_param_240/Numero/9.png";
document.getElementById("image_semana_10").src = "imagenes_param_240/Numero/10.png";
if(document.getElementById('radio_imagen_240_1').checked==true){
document.getElementById("image_semana_1").src = "imagenes_param_240/Numerox/1x.PNG";
document.getElementById("image_semana_2").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_3").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_4").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_5").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_6").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_7").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_8").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_9").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_10").src = "imagenes_param_240/blanco.PNG";
}
if(document.getElementById('radio_imagen_240_2').checked==true){
document.getElementById("image_semana_2").src = "imagenes_param_240/Numerox/2x.PNG";
document.getElementById("image_semana_1").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_3").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_4").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_5").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_6").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_7").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_8").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_9").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_10").src = "imagenes_param_240/blanco.PNG";
}
if(document.getElementById('radio_imagen_240_3').checked==true){
document.getElementById("image_semana_3").src = "imagenes_param_240/Numerox/3x.PNG";
document.getElementById("image_semana_1").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_2").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_4").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_5").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_6").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_7").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_8").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_9").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_10").src = "imagenes_param_240/blanco.PNG";
}
if(document.getElementById('radio_imagen_240_4').checked==true){
document.getElementById("image_semana_4").src = "imagenes_param_240/Numerox/4x.PNG";
document.getElementById("image_semana_1").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_3").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_2").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_5").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_6").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_7").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_8").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_9").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_10").src = "imagenes_param_240/blanco.PNG";
}
if(document.getElementById('radio_imagen_240_5').checked==true){
document.getElementById("image_semana_5").src = "imagenes_param_240/Numerox/5x.PNG";
document.getElementById("image_semana_1").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_3").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_4").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_2").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_6").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_7").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_8").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_9").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_10").src = "imagenes_param_240/blanco.PNG";
}
if(document.getElementById('radio_imagen_240_6').checked==true){
document.getElementById("image_semana_6").src = "imagenes_param_240/Numerox/6x.PNG";
document.getElementById("image_semana_1").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_3").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_4").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_5").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_2").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_7").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_8").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_9").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_10").src = "imagenes_param_240/blanco.PNG";
}
if(document.getElementById('radio_imagen_240_7').checked==true){
document.getElementById("image_semana_7").src = "imagenes_param_240/Numerox/7x.PNG";
document.getElementById("image_semana_1").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_3").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_4").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_5").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_6").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_2").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_8").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_9").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_10").src = "imagenes_param_240/blanco.PNG";
}
if(document.getElementById('radio_imagen_240_8').checked==true){
document.getElementById("image_semana_8").src = "imagenes_param_240/Numerox/8x.PNG";
document.getElementById("image_semana_1").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_3").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_4").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_5").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_6").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_7").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_2").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_9").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_10").src = "imagenes_param_240/blanco.PNG";
}
if(document.getElementById('radio_imagen_240_9').checked==true){
document.getElementById("image_semana_9").src = "imagenes_param_240/Numerox/9x.PNG";
document.getElementById("image_semana_1").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_3").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_4").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_5").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_6").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_7").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_8").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_2").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_10").src = "imagenes_param_240/blanco.PNG";
}
if(document.getElementById('radio_imagen_240_10').checked==true){
document.getElementById("image_semana_10").src = "imagenes_param_240/Numerox/10x.PNG";
document.getElementById("image_semana_1").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_3").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_4").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_5").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_6").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_7").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_8").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_9").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_2").src = "imagenes_param_240/blanco.PNG";
}
document.getElementById("radio_imagen_240_1").value = "1";
document.getElementById("radio_imagen_240_2").value = "2";
document.getElementById("radio_imagen_240_3").value = "3";
document.getElementById("radio_imagen_240_4").value = "4";
document.getElementById("radio_imagen_240_5").value = "5";
document.getElementById("radio_imagen_240_6").value = "6";
document.getElementById("radio_imagen_240_7").value = "7";
document.getElementById("radio_imagen_240_8").value = "8";
document.getElementById("radio_imagen_240_9").value = "9";
document.getElementById("radio_imagen_240_10").value = "10";
document.getElementById("radio_imagen_240_1").disabled = false;
document.getElementById("radio_imagen_240_2").disabled = false;
document.getElementById("radio_imagen_240_3").disabled = false;
document.getElementById("radio_imagen_240_4").disabled = false;
document.getElementById("radio_imagen_240_5").disabled = false;
document.getElementById("radio_imagen_240_6").disabled = false;
document.getElementById("radio_imagen_240_7").disabled = false;
document.getElementById("radio_imagen_240_8").disabled = false;
document.getElementById("radio_imagen_240_9").disabled = false;
document.getElementById("radio_imagen_240_10").disabled = false;
}
}
function  cambiar_imagen_param_semana(){
if(document.getElementById('radio_imagen_240_1').checked==true){
document.getElementById("image_semana_1").src = "imagenes_param_240/Numerox/1x.PNG";
document.getElementById("image_semana_2").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_3").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_4").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_5").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_6").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_7").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_8").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_9").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_10").src = "imagenes_param_240/blanco.PNG";
}
if(document.getElementById('radio_imagen_240_2').checked==true){
document.getElementById("image_semana_2").src = "imagenes_param_240/Numerox/2x.PNG";
document.getElementById("image_semana_1").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_3").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_4").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_5").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_6").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_7").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_8").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_9").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_10").src = "imagenes_param_240/blanco.PNG";
}
if(document.getElementById('radio_imagen_240_3').checked==true){
document.getElementById("image_semana_3").src = "imagenes_param_240/Numerox/3x.PNG";
document.getElementById("image_semana_1").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_2").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_4").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_5").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_6").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_7").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_8").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_9").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_10").src = "imagenes_param_240/blanco.PNG";
}
if(document.getElementById('radio_imagen_240_4').checked==true){
document.getElementById("image_semana_4").src = "imagenes_param_240/Numerox/4x.PNG";
document.getElementById("image_semana_1").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_3").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_2").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_5").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_6").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_7").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_8").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_9").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_10").src = "imagenes_param_240/blanco.PNG";
}
if(document.getElementById('radio_imagen_240_5').checked==true){
document.getElementById("image_semana_5").src = "imagenes_param_240/Numerox/5x.PNG";
document.getElementById("image_semana_1").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_3").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_4").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_2").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_6").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_7").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_8").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_9").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_10").src = "imagenes_param_240/blanco.PNG";
}
if(document.getElementById('radio_imagen_240_6').checked==true){
document.getElementById("image_semana_6").src = "imagenes_param_240/Numerox/6x.PNG";
document.getElementById("image_semana_1").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_3").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_4").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_5").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_2").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_7").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_8").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_9").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_10").src = "imagenes_param_240/blanco.PNG";
}
if(document.getElementById('radio_imagen_240_7').checked==true){
document.getElementById("image_semana_7").src = "imagenes_param_240/Numerox/7x.PNG";
document.getElementById("image_semana_1").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_3").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_4").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_5").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_6").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_2").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_8").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_9").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_10").src = "imagenes_param_240/blanco.PNG";
}
if(document.getElementById('radio_imagen_240_8').checked==true){
document.getElementById("image_semana_8").src = "imagenes_param_240/Numerox/8x.PNG";
document.getElementById("image_semana_1").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_3").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_4").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_5").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_6").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_7").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_2").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_9").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_10").src = "imagenes_param_240/blanco.PNG";
}
if(document.getElementById('radio_imagen_240_9').checked==true){
document.getElementById("image_semana_9").src = "imagenes_param_240/Numerox/9x.PNG";
document.getElementById("image_semana_1").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_3").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_4").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_5").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_6").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_7").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_8").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_2").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_10").src = "imagenes_param_240/blanco.PNG";
}
if(document.getElementById('radio_imagen_240_10').checked==true){
document.getElementById("image_semana_10").src = "imagenes_param_240/Numerox/10x.PNG";
document.getElementById("image_semana_1").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_3").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_4").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_5").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_6").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_7").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_8").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_9").src = "imagenes_param_240/blanco.PNG";
document.getElementById("image_semana_2").src = "imagenes_param_240/blanco.PNG";
}}
 addEvent('load', cambiar_imagen_param_semana);// activamos la funcion para que cambie las fotos
  addEvent('load', aplica_semana_numero_f);// activamos la funcion que activa o desactiva los radios
	</script>
<?php
break;
		Case 164:
				$estado = '';
				if(strpos($row_configuracion['conf_valor'],"$")>0)
				{
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
					$estado = $array_parametro[1];
				}
				else
					$parametro = $row_configuracion['conf_valor'];
				?>
			 <table  width="90%" >
				 <tr>
				 <td><b>Aplica</b>
				 <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
				  	<a href="asignacion_tareas.php" target="_blank" style="color:#3399FF">Ir a tareas por perfiles</a>
				  </td>
				 </tr>
			</table>
					<?php
		break;
		}// este es el fin
?>
	</td>
</tr>
<?php
}while($row_configuracion = mysql_fetch_assoc($configuracion));
?>
<?php
///Instanciacion de clases para el llamado de los parametros
// include 'ParametrosGenerales.php';
$objetoParametros11=new ParametrosGenerales();  ///Instancio el Objeto ParametroGenerales
$conexionBd=$objetoParametros11->creaConexion();  ///llamo la funcion creaConexion
$numPersiana=11;    
$cantidadParametros=$objetoParametros11  -> returnCantidadParametrosPersiana($conexionBd,$numPersiana); //Retorna la cantidad de parametros que hay dentro de la persiana
$cant=0;
while($cant<$cantidadParametros)  //Mientras haya Parametros Entre al while
{
	 	$valor_id_parametro_individual=$objetoParametros11  -> devuelvaParametros($cant);
       $objetoParametros11 ->ejecutarImpresionParametros($numPersiana,$conexionBd,$consecutivo,$valor_id_parametro_individual ); //Ejecuta la impresion de los paramertros individualmente
       $consecutivo=$objetoParametros11 ->returnConsecutivo();    //Retorna el consecutivo
        $informacionDelParametro=$objetoParametros11  ->informacionParametro($conexionBd,$valor_id_parametro_individual);  //Informacion del parametro 
       $cant++;
       // switch($valor_id_parametro_individual)
       // {
       // }
}
?>
</table>
</div>
</div>
</div>
</div>
</div>
<!---------------------------------------------- FIN PARAMETROS PROCESOS ASIGNADOS AL SISTEMA -------------------------------- -->
<!---------------------------------------------- PARAMETROS ASIGNACION INTEGRANTES -------------------------------- -->
<?php
// esta es la tabla 2
if($totalRows_configuracion)
{
	mysql_data_seek($configuracion,0);
mysql_select_db($database_sygescol, $sygescol);
	$query_configuracion = "SELECT conf_sygescol.conf_id, conf_sygescol.conf_nombre, conf_sygescol.conf_valor, conf_sygescol.conf_descri, conf_sygescol.conf_nom_ver, encabezado_parametros.titulo
								FROM conf_sygescol  INNER JOIN encabezado_parametros on encabezado_parametros.id_param=conf_sygescol.conf_id
							WHERE conf_sygescol.conf_estado = 0
								AND conf_sygescol.conf_id IN (142,143)  ORDER BY encabezado_parametros.id_orden ";
	$configuracion = mysql_query($query_configuracion, $sygescol) or die(mysql_error());
	$row_configuracion = mysql_fetch_assoc($configuracion);
// aca inicia la otra tabla
}?>
<?php
include ("conb.php");$registrosx=mysqli_query($conexion,"select * from conf_sygescol_adic where id=13")or die("Problemas en la Consulta".mysqli_error());while ($regx=mysqli_fetch_array($registrosx)){$coloracordx=$regx['valor'];}
?>
<div class="container_demohrvszv_caja_1">
		<div class="accordion_example2wqzx_caja_2">
			<div class="accordion_inwerds_caja_3">
				<div class="acc_headerfgd_caja_titulo" id="parametros_automatizacion_sistema" style="background-color: <?php echo $coloracordx ?>"><center><strong>12. PAR&Aacute;METROS PARA AUTOMATIZACI&Oacute;N DE PROCESOS ASIGNADOS AL SISTEMA</strong></center><br /><center><input type="radio" value="rojox" name="coloresx">Si&nbsp;&nbsp;<input type="radio" value="naranjax" name="coloresx">No</div></center>
				<div class="acc_contentsaponk_caja_4">
					<div class="grevdaiolxx_caja_5">
					<table  align="center" width="85%" class="centro" cellpadding="10" class="formulario" border="1">
	<tr>
	<th class="formulario"  style="background: #3399FF;" >Consecutivo </th>
	<th class="formulario"  style="background: #3399FF;" >Id Par&aacute;metro </th>
	<th class="formulario" >Tipo de Par&aacute;metro</th>
    <th class="formulario" >Detalle del Par&aacute;metro</th>
	<th class="formulario">Selecci&oacute;n</th>
	</tr>
	<?php
do { $consecutivo++;
	?>
	<td class="formulario"   id="Consecutivo<?php echo $consecutivo; ?>" ><strong> <?php echo $consecutivo; ?></strong></td>
	<td class="formulario"  id="Parametro<?php echo $row_configuracion['conf_id']; ?>" ><strong><?php echo $row_configuracion['conf_id'] ?></strong></td>
<td valign="top"><strong>
<div class="container_demohrvszv_caja_tipo_param">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Tipo de Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx_caja_tipo_param">
<div id="conf_nom_ver">
<div  class="textarea "  align="justify" id="conf_nom_ver-<?php echo $row_configuracion['conf_id'] ?>"><?php echo $row_configuracion['conf_nom_ver']; ?></div>
</div>
</div></div></div></div></div>
</strong>
</td>
 <td valign="top" width="80%">
     <div class="container_demohrvszv" >
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd"  id="cgasvf"><strong>Detalle Par&aacute;metro</strong></div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
<div id="conf_descri">
      <div  align="justify" class="textarea " id="conf_descri-<?php echo $row_configuracion['conf_id'] ?>" style="width: 90%;" align="justify">
      <?php echo $row_configuracion['conf_descri']; ?>
      </div>
</div>
					</div>
				</div>
			</div>
		</div>
</div>
 </td>
	<td align="center">
	<?php
	switch($row_configuracion['conf_id'])
	{
	case 142:
		?>
<div class="container_demohrvszv">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>&Iacute;tem 1</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
		<p style="text-align: left;margin:15px;">
				<input type="checkbox" name="a_<?php echo $row_configuracion['conf_nombre']; ?>" value="RAC" <?php if (strpos($row_configuracion['conf_valor'],"RAC") == true )    {echo "checked=checked";} ?>>Rendimiento acad&eacute;mico<br>
				<input type="checkbox" name="b_<?php echo $row_configuracion['conf_nombre']; ?>" value="NPO" <?php if (strpos($row_configuracion['conf_valor'],"NPO") == true )  {echo "checked=checked";} ?>>N&uacute;mero de puesto obtenido<br>
				<input type="checkbox" name="c_<?php echo $row_configuracion['conf_nombre']; ?>" value="RCH" <?php if (strpos($row_configuracion['conf_valor'],"RCH") == true )  {echo "checked=checked";} ?>>Registro en cuadro de honor<br>
				<input type="checkbox" name="d_<?php echo $row_configuracion['conf_nombre']; ?>" value="RI" <?php if (strpos($row_configuracion['conf_valor'],"RI") == true )    {echo "checked=checked";} ?>>Registros de Inasistencia<br>
				<input type="checkbox" name="e_<?php echo $row_configuracion['conf_nombre']; ?>" value="CDA" <?php if (strpos($row_configuracion['conf_valor'],"CDA") == true )    {echo "checked=checked";} ?>>Citaci&oacute;n de Acudientes<br>
				<input type="checkbox" name="f_<?php echo $row_configuracion['conf_nombre']; ?>" value="CCA" <?php if (strpos($row_configuracion['conf_valor'],"CCA") == true )  {echo "checked=checked";} ?>>Cumplimiento de Citaci&oacute;n del Acudiente<br>
				<input type="checkbox" name="g_<?php echo $row_configuracion['conf_nombre']; ?>" value="ARA" <?php if (strpos($row_configuracion['conf_valor'],"ARA") == true )  {echo "checked=checked";} ?>>Asistencia a reuniones del acudiente<br>
				<input type="checkbox" name="h_<?php echo $row_configuracion['conf_nombre']; ?>" value="AEPA" <?php if (strpos($row_configuracion['conf_valor'],"AEPA") == true )  {echo "checked=checked";} ?>>Asistencia escuela de padres Acudiente<br>
				<input type="checkbox" name="i_<?php echo $row_configuracion['conf_nombre']; ?>" value="RPS" <?php if (strpos($row_configuracion['conf_valor'],"RPS") == true )  {echo "checked=checked";} ?>>Resultados pruebas saber<br>
				<input type="checkbox" name="j_<?php echo $row_configuracion['conf_nombre']; ?>" value="RRA" <?php if (strpos($row_configuracion['conf_valor'],"RRA") == true )  {echo "checked=checked";} ?>>Registro de reincidencias automatizadas<br>
				<input type="checkbox" name="k_<?php echo $row_configuracion['conf_nombre']; ?>" value="RN" <?php if (strpos($row_configuracion['conf_valor'],"RN") == true )  {echo "checked=checked";} ?>>Registro de Novedades<br>
				<input type="checkbox" name="l_<?php echo $row_configuracion['conf_nombre']; ?>" value="RG" <?php if (strpos($row_configuracion['conf_valor'],"RG") == true )  {echo "checked=checked";} ?>>Reasignaciones de Grupo<br>
				<input type="checkbox" name="m_<?php echo $row_configuracion['conf_nombre']; ?>" value="RENR" <?php if (strpos($row_configuracion['conf_valor'],"RENR") == true )  {echo "checked=checked";} ?>>Resultados de nivelaciones y/o recuper<br>
				<input type="checkbox" name="n_<?php echo $row_configuracion['conf_nombre']; ?>" value="RS" <?php if (strpos($row_configuracion['conf_valor'],"RS") == true )  {echo "checked=checked";} ?>>Reportes de S.M.S.<br>
				<input type="checkbox" name="ñ_<?php echo $row_configuracion['conf_nombre']; ?>" value="RDE" <?php if (strpos($row_configuracion['conf_valor'],"RDE") == true )  {echo "checked=checked";} ?>>Reportes De E-mails<br>
		</p>
		</div>
</div>
</div>
</div>
</div>
		<?
		break;
		case 143:
		?>
<div class="container_demohrvszv">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>&Itilde;tem</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
		<p style="text-align: left;margin:15px;">
				<input type="checkbox" name="a_<?php echo $row_configuracion['conf_nombre']; ?>" value="acu_celularA" <?php if (strpos($row_configuracion['conf_valor'],"acu_celularA") == true )    {echo "checked=checked";} ?>>N&uacute;mero de Celular Del acudiente<br>
				<input type="checkbox" name="b_<?php echo $row_configuracion['conf_nombre']; ?>" value="acu_celularP" <?php if (strpos($row_configuracion['conf_valor'],"acu_celularP") == true )  {echo "checked=checked";} ?>>N&uacute;mero de Celular Del Padre<br>
				<input type="checkbox" name="c_<?php echo $row_configuracion['conf_nombre']; ?>" value="acu_celularM" <?php if (strpos($row_configuracion['conf_valor'],"acu_celularM") == true )  {echo "checked=checked";} ?>>N&uacute;mero de Celular De la Madre<br>
				<input type="checkbox" name="d_<?php echo $row_configuracion['conf_nombre']; ?>" value="acu_emailA" <?php if (strpos($row_configuracion['conf_valor'],"acu_emailA") == true )    {echo "checked=checked";} ?>>Correo Electr&oacute;nico Del acudiente<br>
				<input type="checkbox" name="e_<?php echo $row_configuracion['conf_nombre']; ?>" value="acu_emailP" <?php if (strpos($row_configuracion['conf_valor'],"acu_emailP") == true )    {echo "checked=checked";} ?>>Correo Electr&oacute;nico Del Padre<br>
				<input type="checkbox" name="f_<?php echo $row_configuracion['conf_nombre']; ?>" value="acu_emailM" <?php if (strpos($row_configuracion['conf_valor'],"acu_emailM") == true )  {echo "checked=checked";} ?>>Correo Electr&oacute;nico De la Madre<br>
				<input type="checkbox" name="g_<?php echo $row_configuracion['conf_nombre']; ?>" value="alumno_fec_nac" <?php if (strpos($row_configuracion['conf_valor'],"alumno_fec_nac") == true )  {echo "checked=checked";} ?>>Fecha de Nacimiento del Estudiante<br>
				<input type="checkbox" name="h_<?php echo $row_configuracion['conf_nombre']; ?>" value="flias_acc_ben" <?php if (strpos($row_configuracion['conf_valor'],"flias_acc_ben") == true )  {echo "checked=checked";} ?>>Beneficiario de Familias en Acci&oacute;n<br>
				<input type="checkbox" name="i_<?php echo $row_configuracion['conf_nombre']; ?>" value="sisben_id" <?php if (strpos($row_configuracion['conf_valor'],"sisben_id") == true )  {echo "checked=checked";} ?> style="display:none">
				<input type="checkbox" name="j_<?php echo $row_configuracion['conf_nombre']; ?>" value="transporte_id" <?php if (strpos($row_configuracion['conf_valor'],"transporte_id") == true )  {echo "checked=checked";} ?>>Beneficiario de Transporte Escolar<br>
				<input type="checkbox" name="k_<?php echo $row_configuracion['conf_nombre']; ?>" value="benefi_alime" <?php if (strpos($row_configuracion['conf_valor'],"benefi_alime") == true )  {echo "checked=checked";} ?>>Beneficiario de Alimentaci&oacute;n Escolar<br>
				<input type="checkbox" name="l_<?php echo $row_configuracion['conf_nombre']; ?>" value="tipo_alime" <?php if (strpos($row_configuracion['conf_valor'],"tipo_alime") == true )  {echo "checked=checked";} ?>>Tipo Alimentaci&oacute;n<br>
				<input type="checkbox" name="m_<?php echo $row_configuracion['conf_nombre']; ?>" value="estu_conf" <?php if (strpos($row_configuracion['conf_valor'],"estu_conf") == true )  {echo "checked=checked";} ?>>Estudiante v&iacute;ctima de conflicto<br>
				<input type="checkbox" name="n_<?php echo $row_configuracion['conf_nombre']; ?>" value="cuadro_Acu" <?php if (strpos($row_configuracion['conf_valor'],"cuadro_Acu") == true )  {echo "checked=checked";} ?>>Cuadro Acumulativo de Matricula<br>
				<input type="checkbox" name="ñ_<?php echo $row_configuracion['conf_nombre']; ?>" value="pre_matri" <?php if (strpos($row_configuracion['conf_valor'],"pre_matri") == true )  {echo "checked=checked";} ?> style="display:none">
		</p>
		</div></div></div></div></div>
		<?
		break;
		}// este es el fin
?>
	</td>
</tr>
<?php
}while($row_configuracion = mysql_fetch_assoc($configuracion));
?>
<?php
///Instanciacion de clases para el llamado de los parametros
// include 'ParametrosGenerales.php';
$objetoParametros12=new ParametrosGenerales();  ///Instancio el Objeto ParametroGenerales
$conexionBd=$objetoParametros12->creaConexion();  ///llamo la funcion creaConexion
$numPersiana=12;    
$cantidadParametros=$objetoParametros12  -> returnCantidadParametrosPersiana($conexionBd,$numPersiana); //Retorna la cantidad de parametros que hay dentro de la persiana
$cant=0;
while($cant<$cantidadParametros)  //Mientras haya Parametros Entre al while
{
	 	$valor_id_parametro_individual=$objetoParametros12  -> devuelvaParametros($cant);
       $objetoParametros12 ->ejecutarImpresionParametros($numPersiana,$conexionBd,$consecutivo,$valor_id_parametro_individual ); //Ejecuta la impresion de los paramertros individualmente
       $consecutivo=$objetoParametros12 ->returnConsecutivo();    //Retorna el consecutivo
        $informacionDelParametro=$objetoParametros12  ->informacionParametro($conexionBd,$valor_id_parametro_individual);  //Informacion del parametro 
       $cant++;
       // switch($valor_id_parametro_individual)
       // {
       // }
}
?>
</table>
</div>
</div>
</div>
</div>
</div>
<!---------------------------------------------- FIN PARAMETROS ASIGNACION DE INTEGRANTES -------------------------------- -->
<!----------------------------------------------  PARAMETROS ASPECTOS PLAN DE ESTUDIO -------------------------------- -->
<?php
// esta es la tabla 2
if($totalRows_configuracion)
{
	mysql_data_seek($configuracion,0);
mysql_select_db($database_sygescol, $sygescol);
	$query_configuracion = "SELECT conf_sygescol.conf_id, conf_sygescol.conf_nombre, conf_sygescol.conf_valor, conf_sygescol.conf_descri, conf_sygescol.conf_nom_ver, encabezado_parametros.titulo
								FROM conf_sygescol  INNER JOIN encabezado_parametros on encabezado_parametros.id_param=conf_sygescol.conf_id
							WHERE conf_sygescol.conf_estado = 0
								AND conf_sygescol.conf_id IN (144,145,146,147,166)  ORDER BY encabezado_parametros.id_orden ";
	$configuracion = mysql_query($query_configuracion, $sygescol) or die(mysql_error());
	$row_configuracion = mysql_fetch_assoc($configuracion);
// aca inicia la otra tabla
}?>
<?php
include ("conb.php");$registrosy=mysqli_query($conexion,"select * from conf_sygescol_adic where id=14")or die("Problemas en la Consulta".mysqli_error());while ($regy=mysqli_fetch_array($registrosy)){$coloracordy=$regy['valor'];}
?>
<div class="container_demohrvszv_caja_1">
		<div class="accordion_example2wqzx_caja_2">
			<div class="accordion_inwerds_caja_3">
				<div class="acc_headerfgd_caja_titulo" id="parametros_integrantes" style="background-color: <?php echo $coloracordy ?>"><center><strong>13. PAR&Aacute;METROS PARA ASIGNACI&Oacute;N DE INTEGRANTES</strong></center><br /><center><input type="radio" value="rojoy" name="coloresy">Si&nbsp;&nbsp;<input type="radio" value="naranjay" name="coloresy">No</div></center>
				<div class="acc_contentsaponk_caja_4">
					<div class="grevdaiolxx_caja_5">
					<table  align="center" width="85%" class="centro" cellpadding="10" class="formulario" border="1">
	<tr>
	<th class="formulario"  style="background: #3399FF;" >Consecutivo </th>
	<th class="formulario"  style="background: #3399FF;" >Id Par&aacute;metro </th>
	<th class="formulario" >Tipo de Par&aacute;metro</th>
    <th class="formulario" >Detalle del Par&aacute;metro</th>
	<th class="formulario">Selecci&oacute;n</th>
	</tr>
	<?php
do { $consecutivo++;
	?>
	<td class="formulario"   id="Consecutivo<?php echo $consecutivo; ?>" ><strong> <?php echo $consecutivo; ?></strong></td>
	<td class="formulario"  id="Parametro<?php echo $row_configuracion['conf_id']; ?>" ><strong><?php echo $row_configuracion['conf_id'] ?></strong></td>
<td valign="top"><strong>
<div class="container_demohrvszv_caja_tipo_param">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Tipo de Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx_caja_tipo_param">
<div id="conf_nom_ver">
<div  class="textarea "  align="justify" id="conf_nom_ver-<?php echo $row_configuracion['conf_id'] ?>"><?php echo $row_configuracion['conf_nom_ver']; ?></div>
</div></div></div></div></div></div>
</strong>
</td>
<td valign="top" width="80%">
<div class="container_demohrvszv" >
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"  id="cgasvf"><strong>Detalle Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
<div id="conf_descri">
      <div  align="justify" class="textarea " id="conf_descri-<?php echo $row_configuracion['conf_id'] ?>" style="width: 90%;" align="justify">
      <?php echo $row_configuracion['conf_descri']; ?>
      </div>
</div>
</div></div></div></div></div>
 </td>
	<td align="center">
	<?php
	switch($row_configuracion['conf_id'])
	{
	case 144:
		?>
<div class="container_demohrvszv">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>&Itilde;tem</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
		<p style="text-align: left;margin:15px;">
				<input type="checkbox" name="a_<?php echo $row_configuracion['conf_nombre']; ?>" value="directivosDocentes" <?php if (strpos($row_configuracion['conf_valor'],"directivosDocentes") == true )    {echo "checked=checked";} ?>>Directivos docentes<br>
				<input type="checkbox" name="b_<?php echo $row_configuracion['conf_nombre']; ?>" value="docentes" <?php if (strpos($row_configuracion['conf_valor'],"docentes") == true )  {echo "checked=checked";} ?>>Docentes<br>
				<input type="checkbox" name="c_<?php echo $row_configuracion['conf_nombre']; ?>" value="acudientes" <?php if (strpos($row_configuracion['conf_valor'],"acudientes") == true )  {echo "checked=checked";} ?>>Acudientes<br>
				<input type="checkbox" name="d_<?php echo $row_configuracion['conf_nombre']; ?>" value="estudiantes" <?php if (strpos($row_configuracion['conf_valor'],"estudiantes") == true )    {echo "checked=checked";} ?>>Estudiantes<br>
<!--
	        <input type="checkbox" name="e_<?php echo $row_configuracion['conf_nombre']; ?>" value="otro" <?php if (strpos($row_configuracion['conf_valor'],"otro") == true )    {echo "checked=checked";} ?>>Otro<br>
-->		</p>
		</div></div></div></div></div>
		<?
		break;
		case 145:
		?>
<div class="container_demohrvszv">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>&Iacute;tem</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
		<p style="text-align: left;margin:15px;">
				<input type="checkbox" name="a_<?php echo $row_configuracion['conf_nombre']; ?>" value="directivosDocentes" <?php if (strpos($row_configuracion['conf_valor'],"directivosDocentes") == true )    {echo "checked=checked";} ?>>Directivos docentes<br>
				<input type="checkbox" name="b_<?php echo $row_configuracion['conf_nombre']; ?>" value="docentes" <?php if (strpos($row_configuracion['conf_valor'],"docentes") == true )  {echo "checked=checked";} ?>>Docentes<br>
				<input type="checkbox" name="c_<?php echo $row_configuracion['conf_nombre']; ?>" value="acudientes" <?php if (strpos($row_configuracion['conf_valor'],"acudientes") == true )  {echo "checked=checked";} ?>>Acudientes<br>
				<input type="checkbox" name="d_<?php echo $row_configuracion['conf_nombre']; ?>" value="estudiantes" <?php if (strpos($row_configuracion['conf_valor'],"estudiantes") == true )    {echo "checked=checked";} ?>>Estudiantes<br>
				<input type="checkbox" name="e_<?php echo $row_configuracion['conf_nombre']; ?>" value="otro" <?php if (strpos($row_configuracion['conf_valor'],"otro") == true )    {echo "checked=checked";} ?>>Sector Productivo<br>
		</p>
</div></div></div></div></div>
		<?php
		break;
case  146:
		?>
<div class="container_demohrvszv">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>&Iacute;tem</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
		<p style="text-align: left;margin:15px;">
				<input type="checkbox" name="a_<?php echo $row_configuracion['conf_nombre']; ?>" value="directivosDocentes" <?php if (strpos($row_configuracion['conf_valor'],"directivosDocentes") == true )    {echo "checked=checked";} ?>>Directivos docentes<br>
				<input type="checkbox" name="b_<?php echo $row_configuracion['conf_nombre']; ?>" value="docentes" <?php if (strpos($row_configuracion['conf_valor'],"docentes") == true )  {echo "checked=checked";} ?>>Docentes<br>
				<input type="checkbox" name="c_<?php echo $row_configuracion['conf_nombre']; ?>" value="acudientes" <?php if (strpos($row_configuracion['conf_valor'],"acudientes") == true )  {echo "checked=checked";} ?>>Acudientes<br>
				<input type="checkbox" name="d_<?php echo $row_configuracion['conf_nombre']; ?>" value="estudiantes" <?php if (strpos($row_configuracion['conf_valor'],"estudiantes") == true )    {echo "checked=checked";} ?>>Estudiantes<br>
<!--
				<input type="checkbox" name="e_<?php echo $row_configuracion['conf_nombre']; ?>" value="otro" <?php if (strpos($row_configuracion['conf_valor'],"otro") == true )    {echo "checked=checked";} ?>>Otro<br>
-->
		</p>
</div></div></div></div></div>
		<?php
		break;
		case 147:
		?>
<div class="container_demohrvszv">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>&Iacute;tem</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
		<p style="text-align: left;margin:15px;">
				<input type="checkbox" name="a_<?php echo $row_configuracion['conf_nombre']; ?>" value="directivosDocentes" <?php if (strpos($row_configuracion['conf_valor'],"directivosDocentes") == true )    {echo "checked=checked";} ?>>Directivos docentes<br>
				<input type="checkbox" name="b_<?php echo $row_configuracion['conf_nombre']; ?>" value="docentes" <?php if (strpos($row_configuracion['conf_valor'],"docentes") == true )  {echo "checked=checked";} ?>>Docentes<br>
				<input type="checkbox" name="c_<?php echo $row_configuracion['conf_nombre']; ?>" value="acudientes" <?php if (strpos($row_configuracion['conf_valor'],"acudientes") == true )  {echo "checked=checked";} ?>>Acudientes<br>
				<input type="checkbox" name="d_<?php echo $row_configuracion['conf_nombre']; ?>" value="estudiantes" <?php if (strpos($row_configuracion['conf_valor'],"estudiantes") == true )    {echo "checked=checked";} ?>>Estudiantes<br>
<!--
				<input type="checkbox" name="e_<?php echo $row_configuracion['conf_nombre']; ?>" value="otro" <?php if (strpos($row_configuracion['conf_valor'],"otro") == true )    {echo "checked=checked";} ?>>Otro<br>
-->
		</p>
</div></div></div></div></div>
		<?php
		break;
		case 166:
			$reasignacion2_ = explode("$",$row_configuracion['conf_valor']);
				$estado = '';
				if(strpos($row_configuracion['conf_valor'],"$")>0)
				{
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
					$estado = $array_parametro[1];
				}
				else
					$parametro = $row_configuracion['conf_valor'];
				?>
			 <table  width="90%" >
				 <tr>
				 <td><b>Aplica</b>
				 <select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>">
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
				<div class="cajaparametrogenerales"><a href="gobie.php" target="_blank" style="color:#3399FF">Ir a sistematisacion de gobierno escolar</a></div>
				  </td>
				 </tr>
			</table>
		<?php
		break;
		}
?>
	</td>
</tr>
<?php
}while($row_configuracion = mysql_fetch_assoc($configuracion));
?>
<?php
///Instanciacion de clases para el llamado de los parametros
// include 'ParametrosGenerales.php';
$objetoParametros13=new ParametrosGenerales();  ///Instancio el Objeto ParametroGenerales
$conexionBd=$objetoParametros13->creaConexion();  ///llamo la funcion creaConexion
$numPersiana=13;    
$cantidadParametros=$objetoParametros13  -> returnCantidadParametrosPersiana($conexionBd,$numPersiana); //Retorna la cantidad de parametros que hay dentro de la persiana
$cant=0;
while($cant<$cantidadParametros)  //Mientras haya Parametros Entre al while
{	

			 	$valor_id_parametro_individual=$objetoParametros13  -> devuelvaParametros($cant);
       $objetoParametros13 ->ejecutarImpresionParametros($numPersiana,$conexionBd,$consecutivo,$valor_id_parametro_individual ); //Ejecuta la impresion de los paramertros individualmente
       $consecutivo=$objetoParametros13 ->returnConsecutivo();    //Retorna el consecutivo
        $informacionDelParametro=$objetoParametros13  ->informacionParametro($conexionBd,$valor_id_parametro_individual);  //Informacion del parametro 
       $cant++;
       // switch($valor_id_parametro_individual)
       // {
       // }
}

?>
</table>
</div>
</div>
</div>
</div>
</div>
<?php
if($totalRows_configuracion)
{
	mysql_data_seek($configuracion,0);
mysql_select_db($database_sygescol, $sygescol);
	$query_configuracion = "SELECT conf_sygescol.conf_id, conf_sygescol.conf_nombre, conf_sygescol.conf_valor, conf_sygescol.conf_descri, conf_sygescol.conf_nom_ver, encabezado_parametros.titulo
								FROM conf_sygescol  INNER JOIN encabezado_parametros on encabezado_parametros.id_param=conf_sygescol.conf_id
							WHERE conf_sygescol.conf_estado = 0
								AND conf_sygescol.conf_id IN (168,165)  ORDER BY encabezado_parametros.id_orden ";
	$configuracion = mysql_query($query_configuracion, $sygescol) or die(mysql_error());
	$row_configuracion = mysql_fetch_assoc($configuracion);
// aca inicia la otra tabla
}?>
<?php
include ("conb.php");$registrosvv=mysqli_query($conexion,"select * from conf_sygescol_adic where id=15")or die("Problemas en la Consulta".mysqli_error());while ($regvv=mysqli_fetch_array($registrosvv)){$coloracordvv=$regvv['valor'];}
?>
<div class="container_demohrvszv_caja_1">
<div class="accordion_example2wqzx_caja_2">
			<div class="accordion_inwerds_caja_3">
				<div class="acc_headerfgd_caja_titulo" id="parametros_definir_aspectos_plan_estudio" style="background-color: <?php echo $coloracordvv ?>"><center><strong>14. PAR&Aacute;METROS PARA DEFINIR ASPECTOS DEL PLAN DE ESTUDIOS</strong></center><br /><center><input type="radio" value="rojovv" name="coloresvv">Si&nbsp;&nbsp;<input type="radio" value="naranjavv" name="coloresvv">No</div></center>
				<div class="acc_contentsaponk_caja_4">
<div class="grevdaiolxx_caja_5">
<table  align="center" width="85%" class="centro" cellpadding="10" class="formulario" border="1">
	<tr>
	<th class="formulario"  style="background: #3399FF;" >Consecutivo </th>
	<th class="formulario"  style="background: #3399FF;" >Id Par&aacute;metro </th>
	<th class="formulario" >Tipo de Par&aacute;metro</th>
    <th class="formulario" >Detalle del Par&aacute;metro</th>
	<th class="formulario">Selecci&oacute;n</th>
	</tr>
	<?php
	do	{	$consecutivo++;
	?>
	<td class="formulario"   id="Consecutivo<?php echo $consecutivo; ?>" ><strong> <?php echo $consecutivo; ?></strong></td>
	<td class="formulario"  id="Parametro<?php echo $row_configuracion['conf_id']; ?>" ><strong><?php echo $row_configuracion['conf_id'] ?></strong></td>
<td valign="top"><strong>
<div class="container_demohrvszv_caja_tipo_param">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Tipo de Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx_caja_tipo_param">
<div id="conf_nom_ver">
<div  class="textarea "  align="justify" id="conf_nom_ver-<?php echo $row_configuracion['conf_id'] ?>"><?php echo $row_configuracion['conf_nom_ver']; ?></div>
</div></div></div></div></div></div>
</strong>
</td>
<td valign="top" width="80%">
<div class="container_demohrvszv" >
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"  id="cgasvf"><strong>Detalle Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx">
<div id="conf_descri">
      <div  align="justify" class="textarea " id="conf_descri-<?php echo $row_configuracion['conf_id'] ?>" style="width: 90%;" align="justify">
      <?php echo $row_configuracion['conf_descri']; ?>
      </div>
</div>
</div></div></div></div></div>
 </td>
	<td align="center">
	<?php
	switch($row_configuracion['conf_id'])
	{
	case 168:
		?>
			<?php
			if(strpos($row_configuracion['conf_valor'],"$")>0)
			{
				$array_parametro = explode("$",$row_configuracion['conf_valor']);
				$cri = $array_parametro[0];
				$cri2 = $array_parametro[1];
				$cri3 = $array_parametro[2];
			}
			else
				$cri = $row_configuracion['conf_valor'];
		?>
<table  width="90%" >
		 <tr>
		 <td><b>Aplica</b>
		  <select class="sele_mul" name="criterio_<?php echo $row_configuracion['conf_nombre']; ?>" id="criterio_<?php echo $row_configuracion['conf_nombre']; ?>">
			<option value="S" <?php if (!(strcmp("S", $cri['conf_valor']))) {echo "selected=\"selected\"";} ?>>Si</option>
			<option value="N" <?php if (!(strcmp("N", $cri['conf_valor']))) {echo "selected=\"selected\"";} ?>>No</option>
		  </select>
<a href="area_crear_datos.php" target="_blank" style="color:#3399FF">Ir a ingreso de areas</a><br>
</td>
</tr>
</table>
<table  width="90%" >
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</table>
		<?php
break;
		case 165:
$array_parametro = explode("$",$row_configuracion['conf_valor']);
		//Consultamos el tope maximo de los estudiantes (Zona Urbana)
		$proyecion_cupos_ind_1 = $array_parametro[1];
		$proyecion_cupos_ind_2 = $array_parametro[2];
		$proyecion_cupos_ind_3 = $array_parametro[3];
		$proyecion_cupos_ind_4 = $array_parametro[4];
		$proyecion_cupos_ind_5 = $array_parametro[5];
		$proyecion_cupos_ind_6 = $array_parametro[6];
		$proyecion_cupos_ind_7 = $array_parametro[7];
		$proyecion_cupos_ind_8 = $array_parametro[8];
		$proyecion_cupos_ind_9 = $array_parametro[9];
        $proyecion_cupos_ind_10 = $array_parametro[10];
		$proyecion_cupos_ind_11 = $array_parametro[11];
        $proyecion_cupos_ind_12 = $array_parametro[12];
        $proyecion_cupos_ind_13 = $array_parametro[13];
        $proyecion_cupos_ind_14 = $array_parametro[14];
        $proyecion_cupos_ind_15 = $array_parametro[15];
        $proyecion_cupos_ind_16 = $array_parametro[16];
        $proyecion_cupos_ind_17 = $array_parametro[17];
        /*
        print_r($array_parametro);*/
		?>
<!-- PARAMETRO 82 -->
		<div id="dvhiegroj">
	                      <a id="anchor1" href="#" >Ver Parametro</a>
	                        
</div>
<script>
	window.onload = function() {
            var anchor = document.getElementById("anchor1");         
            
            // le asociamos el evento a nuestro elemento para tener un codigo 
            // html mas limpio y manejar toda la interaccion
            // desde nuestro script
            anchor.onclick = function() {
                // una variable donde pongo la url a donde quiera ir, 
                //podria estar de mas pero asi queda mas limpio la funcion window.open()
                var url = "sedes_coordinadores_parametros.php";
                window.open(url, "_blank", 'width=1300,height=800'); 
                // el return falase es para eviar que se progrague el evento y se vaya al href de tu anchor.
                return false;
            };
        } 	
</script>
<!-- FIN PARAMETRO 82 -->
		<?php
		break;
		}// este es el fin
?>
	</td>
</tr>
<?php
}while($row_configuracion = mysql_fetch_assoc($configuracion));
?>
<?php
///Instanciacion de clases para el llamado de los parametros
// include 'ParametrosGenerales.php';
$objetoParametros14=new ParametrosGenerales();  ///Instancio el Objeto ParametroGenerales
$conexionBd=$objetoParametros14->creaConexion();  ///llamo la funcion creaConexion
$numPersiana=14;    
$cantidadParametros=$objetoParametros14  -> returnCantidadParametrosPersiana($conexionBd,$numPersiana); //Retorna la cantidad de parametros que hay dentro de la persiana
$cant=0;
while($cant<$cantidadParametros)  //Mientras haya Parametros Entre al while
{
	 	$valor_id_parametro_individual=$objetoParametros14  -> devuelvaParametros($cant);
       $objetoParametros14 ->ejecutarImpresionParametros($numPersiana,$conexionBd,$consecutivo,$valor_id_parametro_individual ); //Ejecuta la impresion de los paramertros individualmente
       $consecutivo=$objetoParametros14 ->returnConsecutivo();    //Retorna el consecutivo
        $informacionDelParametro=$objetoParametros14  ->informacionParametro($conexionBd,$valor_id_parametro_individual);  //Informacion del parametro 
       $cant++;
       // switch($valor_id_parametro_individual)
       // {
       // }
}
?>
</table>
</div>
</div>
</div>
</div>
</div>
<!---------------------------------------------- FIN PARAMETROS ASPECTOS PLAN DE ESTUDIO -------------------------------- -->
<!----------------------------------------------  PARAMETROS CIERRE AÑO -------------------------------- -->
<?php
// esta es la tabla 2
if($totalRows_configuracion)
{
	mysql_data_seek($configuracion,0);
mysql_select_db($database_sygescol, $sygescol);
	$query_configuracion = "SELECT conf_sygescol.conf_id, conf_sygescol.conf_nombre, conf_sygescol.conf_valor, conf_sygescol.conf_descri, conf_sygescol.conf_nom_ver, encabezado_parametros.titulo
								FROM conf_sygescol  INNER JOIN encabezado_parametros on encabezado_parametros.id_param=conf_sygescol.conf_id
							WHERE conf_sygescol.conf_estado = 0
								AND conf_sygescol.conf_id IN (137)  ORDER BY encabezado_parametros.id_orden ";
	$configuracion = mysql_query($query_configuracion, $sygescol) or die(mysql_error());
	$row_configuracion = mysql_fetch_assoc($configuracion);
// aca inicia la otra tabla
}?>
<?php
include ("conb.php");$registrosww=mysqli_query($conexion,"select * from conf_sygescol_adic where id=16")or die("Problemas en la Consulta".mysqli_error());while ($regww=mysqli_fetch_array($registrosww)){$coloracordww=$regww['valor'];}
?>
<div class="container_demohrvszv_caja_1">
		<div class="accordion_example2wqzx_caja_2">
			<div class="accordion_inwerds_caja_3">
				<div class="acc_headerfgd_caja_titulo" id="parametros_cierre_año" style="background-color: <?php echo $coloracordww ?>"><center><strong>15. PAR&Aacute;METROS PARA CONTROL CIERRE A&Nacute;O</strong></center><br /><center><input type="radio" value="rojoww" name="coloresww">Si&nbsp;&nbsp;<input type="radio" value="naranjaww" name="coloresww">No</div></center>
				<div class="acc_contentsaponk_caja_4">
					<div class="grevdaiolxx_caja_5">
					<table  align="center" width="85%" class="centro" cellpadding="10" class="formulario" border="1">
	<tr>
	<th class="formulario"  style="background: #3399FF;" >Consecutivo </th>
	<th class="formulario"  style="background: #3399FF;" >Id Par&aacute;metro </th>
	<th class="formulario" >Tipo de Par&aacute;metro</th>
    <th class="formulario" >Detalle del Par&aacute;metro</th>
    <th class="formulario">Selecci&oacute;n</th>
	</tr>
	<?php
do {	$consecutivo++;
	?>
	<td class="formulario"   id="Consecutivo<?php echo $consecutivo; ?>" ><strong> <?php echo $consecutivo; ?></strong></td>
	<td class="formulario"  id="Parametro<?php echo $row_configuracion['conf_id']; ?>" ><strong><?php echo $row_configuracion['conf_id'] ?></strong></td>
<td valign="top"><strong>
<div class="container_demohrvszv_caja_tipo_param">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Tipo de Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx_caja_tipo_param">
<div id="conf_nom_ver">
<div  class="textarea "  align="justify" id="conf_nom_ver-<?php echo $row_configuracion['conf_id'] ?>"><?php echo $row_configuracion['conf_nom_ver']; ?></div>
</div></div></div></div></div></div>
</strong>
</td>      <td valign="top" width="80%">
     <div class="container_demohrvszv" >
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd"  id="cgasvf"><strong>Detalle Par&aacute;metro</strong></div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
<div id="conf_descri">
      <div  align="justify" class="textarea " id="conf_descri-<?php echo $row_configuracion['conf_id'] ?>" style="width: 90%;" align="justify">
      <?php echo $row_configuracion['conf_descri']; ?>
      </div>
</div>
					</div>
				</div>
			</div>
		</div>
</div>
 </td>
	<td align="center">
	<?php
	switch($row_configuracion['conf_id'])
	{
			case 137:
		$valoresP61 = explode("$", $row_configuracion['conf_valor'])
		?>
		<!--PARAMETRO 58-->
		<div class="container_demohrvszv">
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd">&Iacute;tem 1</div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
		<p style="text-align: left;margin: 0px 15px;border: solid 2px #CE6767;border-radius: 10px;padding: 12px;">
			<b style="color: red;">1. No permitir</b> el cierre en las &aacute;reas donde falte <b>al menos un ESTUDIANTE</b> por calificar, en cualquiera de los periodos acad&eacute;micos del a&ntilde;o, en el grupo que se pretenda cerrar.
			<br><b style="color: red;">Mensaje de alerta:</b><br>
			Tiene estudiantes pendientes por calificar del <b>#Grupo# - #Asignatura# -#Periodo# - #Docente# - #Estudiante#</b>.<br>
			<select class="sele_mul" name="a_<?php echo $row_configuracion['conf_nombre']; ?>" style="width: 70%; margin: 5px 0 0 15px;">
				<option value="S" <?php if (!(strcmp("S", $valoresP61[0]))) {echo "selected=\"selected\"";} ?>>Aplica</option>
				<option value="N" <?php if (!(strcmp("N", $valoresP61[0]))) {echo "selected=\"selected\"";} ?>>No Aplica</option>
			</select>
		</p>
</div>
</div>
</div>
</div>
</div>
	<div class="container_demohrvszv">
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd">&Iacute;tem 2</div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
		<p style="text-align: left;margin: 0px 15px;border: solid 2px #CE6767;border-radius: 10px;padding: 12px;">
			<b style="color: red;">2. No permitir</b> la generaci&oacute;n de:<br>
			<label style="font-size: 11px;border: solid 1px;border-radius: 10px;font-weight: bold;">1</label> Informe Final <br>
			<label style="font-size: 11px;border: solid 1px;border-radius: 10px;font-weight: bold;">2</label> Certificados de estudio <br>
			<label style="font-size: 11px;border: solid 1px;border-radius: 10px;font-weight: bold;">3</label> Libro final de valoraciones, a aquellos estudiantes, en cuyos grupos no se haya realizado el cierre <b>Total</b> de las &aacute;reas.
			<br><b style="color: red;">Mensaje de alerta:</b><br>
			Est&aacute; pendiente por cerrar las &aacute;reas del <b>#Grupo# - #Asignatura#.</b><br>
			<select class="sele_mul" name="b_<?php echo $row_configuracion['conf_nombre']; ?>" style="width: 70%; margin: 5px 0 0 15px;">
				<option value="S" <?php if (!(strcmp("S", $valoresP61[1]))) {echo "selected=\"selected\"";} ?>>Aplica</option>
				<option value="N" <?php if (!(strcmp("N", $valoresP61[1]))) {echo "selected=\"selected\"";} ?>>No Aplica</option>
			</select>
		</p>
</div>
</div>
</div>
</div>
</div>
<div class="container_demohrvszv">
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd">&Iacute;tem 3</div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
		<p style="text-align: left;margin: 0px 15px;border: solid 2px #CE6767;border-radius: 10px;padding: 12px;">
			<b style="color: red;">3. Validar que</b> en los grupos con estudiantes pendientes por registro de calificaciones, producto del <b>proceso de matr&iacute;cula extraordinaria</b> , el sistema no permita el cierre de
			&aacute;reas, hasta tanto se haya registrado las calificaciones pendientes.<br><b style="color: red;">Mensaje de alerta:</b><br>
			Tiene estudiantes pendientes por calificar por matr&iacute;cula extraordinaria como perfil secretar&iacute;a acad&eacute;mica, en el <b>#Grupo# - #Periodos# - #Estudiante#</b><br>
	<select class="sele_mul" name="c_<?php echo $row_configuracion['conf_nombre']; ?>" style="width: 70%; margin: 5px 0 0 15px;">
				<option value="S" <?php if (!(strcmp("S", $valoresP61[2]))) {echo "selected=\"selected\"";} ?>>Aplica</option>
				<option value="N" <?php if (!(strcmp("N", $valoresP61[2]))) {echo "selected=\"selected\"";} ?>>No Aplica</option>
			</select>
		</p>
	</div>
</div>
</div>
</div>
</div>
<div class="container_demohrvszv">
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd">&Iacute;tem 4</div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
		<p style="text-align: left;margin: 0px 15px;border: solid 2px #CE6767;border-radius: 10px;padding: 12px;">
			<b style="color: red;">4. Validar que</b> en los grupos donde el sistema haya reportado al Perfil Coordinador Acad&eacute;mico alerta de pendientes por ausencia de registros del <b>DESCRIPTORES</b> en cualquiera de los periodos del a&ntilde;o y a&uacute;n est&eacute;n
			<b>PENDIENTES </b>por registrar en el sistema, no se pueda cerrar las &aacute;reas<br><b style="color: red;">Mensaje de alerta:</b><br>
			A&uacute;n existe registro de Docentes pendientes por subir a la plataforma, (descriptores de Fortalezas, Debilidades y Recomendaciones) en: <b>#Grupo#  #Periodos# #Asignatura# #Docente#.</b><br>
			<select class="sele_mul" name="d_<?php echo $row_configuracion['conf_nombre']; ?>" style="width: 70%; margin: 5px 0 0 15px;">
				<option value="S" <?php if (!(strcmp("S", $valoresP61[3]))) {echo "selected=\"selected\"";} ?>>Aplica</option>
				<option value="N" <?php if (!(strcmp("N", $valoresP61[3]))) {echo "selected=\"selected\"";} ?>>No Aplica</option>
			</select>
		</p>
	</div>
</div>
</div>
</div>
</div>
<div class="container_demohrvszv">
		<div class="accordion_example2wqzx">
			    <div class="accordion_inwerds">
				<div class="acc_headerfgd">&Iacute;tem 5</div>
				<div class="acc_contentsaponk">
				<div class="grevdaiolxx">
		<p style="text-align: left;margin: 0px 15px;border: solid 2px #CE6767;border-radius: 10px;padding: 12px;">
			<b style="color: red;">5. No generar</b> matr&iacute;culas a <b>ESTUDIANTES ANTIGUOS</b> por <b style="color: red;">Ninguno de los conceptos</b> ubicados
			en la ruta <b>&#8220;Crear matr&iacute;culas&#8221;</b> o por el sistema biom&eacute;trico, si no se ha efectuado el cierre de &aacute;reas total del a&ntilde;o o semestre
			anterior, en el grupo inmediatamente anterior de la matr&iacute;cula a efectuar.<br><b style="color: red;">Mensaje de alerta:</b><br>
			Est&aacute; pendiente por cerrar las &aacute;reas del  <b>#Grupo# - #Asignatura#</b><br>
			<select class="sele_mul" name="e_<?php echo $row_configuracion['conf_nombre']; ?>" style="width: 70%; margin: 5px 0 0 15px;">
				<option value="S" <?php if (!(strcmp("S", $valoresP61[4]))) {echo "selected=\"selected\"";} ?>>Aplica</option>
				<option value="N" <?php if (!(strcmp("N", $valoresP61[4]))) {echo "selected=\"selected\"";} ?>>No Aplica</option>
			</select>
		</p>
</div>
</div>
</div>
</div>
</div>
		<?php
		break;
}
?>
</td>
</tr>
<?php
}while($row_configuracion = mysql_fetch_assoc($configuracion));
?>
<?php
///Instanciacion de clases para el llamado de los parametros
// include 'ParametrosGenerales.php';
$objetoParametros15=new ParametrosGenerales();  ///Instancio el Objeto ParametroGenerales
$conexionBd=$objetoParametros15->creaConexion();  ///llamo la funcion creaConexion
$numPersiana=15;    
$cantidadParametros=$objetoParametros15  -> returnCantidadParametrosPersiana($conexionBd,$numPersiana); //Retorna la cantidad de parametros que hay dentro de la persiana
$cant=0;
while($cant<$cantidadParametros)  //Mientras haya Parametros Entre al while
{
	 	$valor_id_parametro_individual=$objetoParametros15  -> devuelvaParametros($cant);
       $objetoParametros15 ->ejecutarImpresionParametros($numPersiana,$conexionBd,$consecutivo,$valor_id_parametro_individual ); //Ejecuta la impresion de los paramertros individualmente
       $consecutivo=$objetoParametros15 ->returnConsecutivo();    //Retorna el consecutivo
        $informacionDelParametro=$objetoParametros15  ->informacionParametro($conexionBd,$valor_id_parametro_individual);  //Informacion del parametro 
       $cant++;
       // switch($valor_id_parametro_individual)
       // {
       // }
}
?>
</table>
</div>
</div>
</div>
</div>
</div>
<!---------------------------------------------- FIN PARAMETROS CIERRE AÑO -------------------------------- -->
<?php
// esta es la tabla 2
if($totalRows_configuracion)
{
	mysql_data_seek($configuracion,0);
mysql_select_db($database_sygescol, $sygescol);
	$query_configuracion = "SELECT conf_sygescol.conf_id, conf_sygescol.conf_nombre, conf_sygescol.conf_valor, conf_sygescol.conf_descri, conf_sygescol.conf_nom_ver, encabezado_parametros.titulo
								FROM conf_sygescol  INNER JOIN encabezado_parametros on encabezado_parametros.id_param=conf_sygescol.conf_id
							WHERE conf_sygescol.conf_estado = 0
								AND conf_sygescol.conf_id IN (162,156,160)  ORDER BY encabezado_parametros.id_orden ";
	$configuracion = mysql_query($query_configuracion, $sygescol) or die(mysql_error());
	$row_configuracion = mysql_fetch_assoc($configuracion);
// aca inicia la otra tabla
}?>
<?php
include ("conb.php");$registrosppo=mysqli_query($conexion,"select * from conf_sygescol_adic where id=18")or die("Problemas en la Consulta".mysqli_error());while ($regppo=mysqli_fetch_array($registrosppo)){$coloracordppo=$regppo['valor'];}
?>
<div class="container_demohrvszv_caja_1">
		<div class="accordion_example2wqzx_caja_2">
			<div class="accordion_inwerds_caja_3">
			<?php
$query_ano_sistema162 = "SELECT * FROM year";
$ano_sistema162 = mysql_query($query_ano_sistema162, $sygescol) or die(mysql_error());
$row_ano_sistema162 = mysql_fetch_assoc($ano_sistema162);
$totalRows_ano_sistema162 = mysql_num_rows($ano_sistema162);
$an = $row_ano_sistema162["b"];
 ?>
				<div class="acc_headerfgd_caja_titulo" id="parametros_control_calificaciones" style="background-color: <?php echo $coloracordppo ?>"><center><strong>16. PAR&Aacute;METROS PARA EL A&Ntilde;O <?php echo $an+1; ?></strong></center><br /><center><input type="radio" value="rojoppo" name="coloresppo">Si&nbsp;&nbsp;<input type="radio" value="naranjappo" name="coloresppo">No</div></center>
				<div class="acc_contentsaponk_caja_4">
					<div class="grevdaiolxx_caja_5">
					<table  align="center" width="85%" class="centro" cellpadding="10" class="formulario"  border="1">
<tr>
	<th class="formulario"  style="background: #3399FF;" >Consecutivo </th>
	<th class="formulario"  style="background: #3399FF;" >Id Par&aacute;metro </th>
	<th class="formulario" >Tipo de Par&aacute;metro</th>
    <th class="formulario" >Detalle del Par&aacute;metro</th>
	<th class="formulario">Selecci&oacute;n</th>
	</tr>
	<?php
	do
	{
		$consecutivo++;
	?>
	<td class="formulario"   id="Consecutivo<?php echo $consecutivo; ?>" ><strong> <?php echo $consecutivo; ?></strong></td>
	<td class="formulario"  id="Parametro<?php echo $row_configuracion['conf_id']; ?>" ><strong><?php echo $row_configuracion['conf_id'] ?></strong></td>
<td valign="top"><strong>
<div class="container_demohrvszv_caja_tipo_param">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Tipo de Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx_caja_tipo_param">
<div id="conf_nom_ver">
<div  class="textarea "  align="justify" id="conf_nom_ver-<?php echo $row_configuracion['conf_id'] ?>"><?php echo $row_configuracion['conf_nom_ver']; ?></div>
</div></div></div></div></div></div>
</strong>
</td>
<td valign="top" width="80%">
     <div class="container_demohrvszv" >
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd"  id="cgasvf"><strong>Detalle Par&aacute;metro</strong></div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
<div id="conf_descri">
      <div  align="justify" class="textarea " id="conf_descri-<?php echo $row_configuracion['conf_id'] ?>" style="width: 90%;" align="justify">
      <?php echo $row_configuracion['conf_descri']; ?>
      </div>
</div>
					</div>
				</div>
			</div>
		</div>
</div>
 </td>
<td align="center">
<?php
switch($row_configuracion['conf_id'])
{
		case 162:
			$estado = '';
				if(strpos($row_configuracion['conf_valor'],"$")>0)
				{
					$array_parametro = explode("$",$row_configuracion['conf_valor']);
					$parametro = $array_parametro[0];
					$estado = $array_parametro[1];
				}
				else
					$parametro = $row_configuracion['conf_valor'];
				?>

<table >
<tr>
<td><b>Aplica</b></td>
<td><select class="sele_mul" name="<?php echo $row_configuracion['conf_nombre']; ?>" id="<?php echo $row_configuracion['conf_nombre']; ?>" style="width:150%; text-align:center;" onchange="cambio_param(this.value);">
					<option value="NA" <?php if (!(strcmp("NA", $parametro))) {echo "selected=\"selected\"";} ?>>No Aplica</option>
					<option value="S" <?php if (!(strcmp("S", $parametro))) {echo "selected=\"selected\"";} ?>>Si</option>
					<option value="N" <?php if (!(strcmp("N", $parametro))) {echo "selected=\"selected\"";} ?>>No</option>
				  </select>
				  </td>
</tr>
</table>
<div id="carga_info_na"  <?php if ((strcmp("NA", $parametro))) {echo 'style="display:none;"';} ?>>
<br>
<b><?php echo utf8_decode("La matrícula para estudiantes antiguos, se realizará por la opción, matrícula por promoción para promovidos y reprobados"); ?></b>
<br>
<br><br>
</div>
<div id="carga_info_s" <?php if ((strcmp("S", $parametro))) {echo 'style="display:none;"';} ?>>
<br>
<table><tr><td><input type='radio' value='1' id='param_si' name='param_si' <?php if (!(strcmp("1", $estado))) {echo "checked=\"checked\"";} ?> onclick="mostrar_link(this.value);">&nbsp;&nbsp;<?php echo utf8_decode("Autorizado por el acudiente (biometría)"); ?></td></tr><tr><td><input type='radio' value='2' id='param_si' name='param_si' <?php if (!(strcmp("2", $estado))) {echo "checked=\"checked\"";} ?> onclick="mostrar_link(this.value);">&nbsp;&nbsp;Definido por el estudiante (Web)<div id="link_web" <?php if ((strcmp("2", $estado))) {echo 'style="display:none;"';} ?> ><br><a href='configuracion_matricula.php' target='_blank' style='color:#3399FF'>Ir a proceso de continuidad</a></div></td></tr></table>
<br>
</div>
<div id="carga_info_n" <?php if ((strcmp("N", $parametro))) {echo 'style="display:none;"';} ?>>
<br>
<table><tr><td><b><?php echo utf8_decode("El numero de inscripcion y pin de acceso, se entregará:"); ?></b></td></tr><tr><td></td></tr><tr><td><input type='radio' value='3' id='param_si' name='param_si' <?php if (!(strcmp("3", $estado))) {echo "checked=\"checked\"";} ?> onclick="mostrar_link(this.value);">&nbsp;&nbsp;<?php echo utf8_decode("En el boletín del último periodo"); ?></td></tr><tr><td><input type='radio' value='4' id='param_si' name='param_si' <?php if (!(strcmp("4", $estado))) {echo "checked=\"checked\"";} ?> onclick="mostrar_link(this.value);">&nbsp;&nbsp;En el informe final</td></tr></table>
<br>
</div>
<script>
jqnc = jQuery.noConflict();
function mostrar_link(valor)
{
if (valor == 2) 
{
jqnc("#link_web").css("display","");
}
else
{
jqnc("#link_web").css("display","none");
}
}

function cambio_param(valor)
{


if (valor == "NA")
{
jqnc("#carga_info_na").css("display","");
jqnc("#carga_info_n").css("display","none");
jqnc("#carga_info_s").css("display","none");
}
else if(valor == "S")
{
jqnc("#carga_info_s").css("display","");
jqnc("#carga_info_na").css("display","none");
jqnc("#carga_info_n").css("display","none");
}
else if(valor == "N")
{
jqnc("#carga_info_n").css("display","");
jqnc("#carga_info_s").css("display","none");
jqnc("#carga_info_na").css("display","none");
}
}
</script>


<?php
		break;
case 156:
		$sele_grado_base = "SELECT grado_base FROM v_grados GROUP by grado_base ";
		$sql_grado_base = mysql_query($sele_grado_base,$link);
		$sele_grado_base_rural = "SELECT grado_base FROM v_grados GROUP by grado_base ";
		$sql_grado_base_rural = mysql_query($sele_grado_base_rural,$link);
		
		
		$array_parametro = explode("$",$row_configuracion['conf_valor']);
		//Consultamos el tope maximo de los estudiantes (Zona Urbana)
		$proyecion_cupos_1 = $array_parametro[1];
		$proyecion_cupos_2 = $array_parametro[2];
		
		$proyecion_cupos_3 = $array_parametro[3];
		$proyecion_cupos_4 = $array_parametro[4];
		
		$proyecion_cupos_5 = $array_parametro[5];
		$proyecion_cupos_6 = $array_parametro[6];
		
		$proyecion_cupos_7 = $array_parametro[7];
		$proyecion_cupos_8 = $array_parametro[8];
		
		$proyecion_cupos_9 = $array_parametro[9];
		$proyecion_cupos_10 = $array_parametro[10];
		
		$proyecion_cupos_11 = $array_parametro[11];
		$proyecion_cupos_12 = $array_parametro[12];
		$proyecion_cupos_13 = $array_parametro[13];
		$proyecion_cupos_14 = $array_parametro[14];
		
		$proyecion_cupos_15 = $array_parametro[15];
		$proyecion_cupos_16 = $array_parametro[16];
		
		$proyecion_cupos_17 = $array_parametro[17];
		$proyecion_cupos_18 = $array_parametro[18];
		
		$proyecion_cupos_19 = $array_parametro[19];
		$proyecion_cupos_20 = $array_parametro[20];
		$proyecion_cupos_21 = $array_parametro[21];
		$proyecion_cupos_22 = $array_parametro[22];
		$proyecion_cupos_23 = $array_parametro[23];
		$proyecion_cupos_24 = $array_parametro[24];
				
		$proyecion_cupos_25 = $array_parametro[25];
		$proyecion_cupos_26 = $array_parametro[26];
		$proyecion_cupos_27 = $array_parametro[27];
		$proyecion_cupos_28 = $array_parametro[28];
		$proyecion_cupos_29 = $array_parametro[29];
		$proyecion_cupos_30 = $array_parametro[30];
		$proyecion_cupos_31 = $array_parametro[31];
		$proyecion_cupos_32 = $array_parametro[32];
		$proyecion_cupos_33 = $array_parametro[33];
		$proyecion_cupos_34 = $array_parametro[34];
		$proyecion_cupos_35 = $array_parametro[35];
		$proyecion_cupos_36 = $array_parametro[36];
		$proyecion_cupos_37 = $array_parametro[37];
		$proyecion_cupos_38 = $array_parametro[38];
		$proyecion_cupos_39 = $array_parametro[39];
		$proyecion_cupos_40 = $array_parametro[40];
		$proyecion_cupos_41 = $array_parametro[41];
		$proyecion_cupos_42 = $array_parametro[42];
		$proyecion_cupos_43 = $array_parametro[43];
		$proyecion_cupos_44 = $array_parametro[44];
		$proyecion_cupos_45 = $array_parametro[45];
		$proyecion_cupos_46 = $array_parametro[46];
		$proyecion_cupos_47 = $array_parametro[47];
		$proyecion_cupos_48 = $array_parametro[48];
		$proyecion_cupos_49 = $array_parametro[49];
		$proyecion_cupos_50 = $array_parametro[50];
		$proyecion_cupos_51 = $array_parametro[51];
		$proyecion_cupos_52 = $array_parametro[52];
		//Consultamos el tope maximo de los estudiantes (Zona rural)
		$proyecion_cupos_53 = $array_parametro[53];
		$proyecion_cupos_54 = $array_parametro[54];
		
		$proyecion_cupos_55 = $array_parametro[55];
		$proyecion_cupos_56 = $array_parametro[56];
		
		$proyecion_cupos_57 = $array_parametro[57];
		$proyecion_cupos_58 = $array_parametro[58];
		
		$proyecion_cupos_59 = $array_parametro[59];
		$proyecion_cupos_60 = $array_parametro[60];
		$proyecion_cupos_61 = $array_parametro[61];
		$proyecion_cupos_62 = $array_parametro[62];
		$proyecion_cupos_63 = $array_parametro[63];
		$proyecion_cupos_64 = $array_parametro[64];
				
		$proyecion_cupos_65 = $array_parametro[65];
		$proyecion_cupos_66 = $array_parametro[66];
		$proyecion_cupos_67 = $array_parametro[67];
		$proyecion_cupos_68 = $array_parametro[68];
		$proyecion_cupos_69 = $array_parametro[69];
		$proyecion_cupos_70 = $array_parametro[70];
		$proyecion_cupos_71 = $array_parametro[71];
		$proyecion_cupos_72 = $array_parametro[72];
		$proyecion_cupos_73 = $array_parametro[73];
		$proyecion_cupos_74 = $array_parametro[74];
		$proyecion_cupos_75 = $array_parametro[75];
		$proyecion_cupos_76 = $array_parametro[76];
		$proyecion_cupos_77 = $array_parametro[77];
		$proyecion_cupos_78 = $array_parametro[78];
		$proyecion_cupos_79 = $array_parametro[79];
		$proyecion_cupos_80 = $array_parametro[80];
		$proyecion_cupos_81 = $array_parametro[81];
		$proyecion_cupos_82 = $array_parametro[82];
		$proyecion_cupos_83 = $array_parametro[83];
		$proyecion_cupos_84 = $array_parametro[84];
		$proyecion_cupos_85 = $array_parametro[85];
		$proyecion_cupos_86 = $array_parametro[86];
		$proyecion_cupos_87 = $array_parametro[87];
		$proyecion_cupos_88 = $array_parametro[88];
		$proyecion_cupos_89 = $array_parametro[89];
		$proyecion_cupos_90 = $array_parametro[90];
		$proyecion_cupos_91 = $array_parametro[91];
		$proyecion_cupos_92 = $array_parametro[92];
		$proyecion_cupos_93 = $array_parametro[93];
		$proyecion_cupos_94 = $array_parametro[94];
		$proyecion_cupos_95 = $array_parametro[95];
		$proyecion_cupos_96 = $array_parametro[96];
		$proyecion_cupos_97 = $array_parametro[97];
		$proyecion_cupos_98 = $array_parametro[98];
		$proyecion_cupos_99 = $array_parametro[99];
		$proyecion_cupos_100 = $array_parametro[100];
		$proyecion_cupos_101 = $array_parametro[101];
		$proyecion_cupos_102 = $array_parametro[102];
		$proyecion_cupos_103 = $array_parametro[103];
		$proyecion_cupos_104 = $array_parametro[104];
		?>
	    <div class="accordion_example2wqzx">
        <div class="accordion_inwerds">
		<div class="acc_headerfgd"><strong>Tope de estudiantes zona urbana</strong> </div>
        <div class="acc_contentsaponk">
		 <div class="grevdaiolxx">
		 			<table width="100%" class="formulario" align="center">
			<tr>
				<th class="formulario" colspan="2" align="center">ZONA URBANA</th>
			</tr>
			<tr>
				<th class="formulario" align="center">Nivel</th>
			    <th class="formulario" align="center">Tope de Estudiantes</th>
			</tr>
			<?php while ($array_grado_base = mysql_fetch_assoc($sql_grado_base)) {
				//echo $array_grado_base['grado_base'].'<br>';
			?>
			<?php if ($array_grado_base['grado_base'] == 0): ?>
			
			<tr class="fila2">
				<td>Preescolar</td>
				<td align="center" ><input type="text" name="planilla_prom_ant1_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_1; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant2_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_2; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 1): ?>
			<tr class="fila1">
				<td>Primero</td>
				<td align="center" ><input type="text" name="planilla_prom_ant3_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_3; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant4_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_4; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 2): ?>
			<tr class="fila2">
				<td>Segundo</td>
				<td align="center" ><input type="text" name="planilla_prom_ant5_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_5; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant6_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_6; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 3): ?>
			<tr class="fila1">
				<td>Tercero</td>
				<td align="center" ><input type="text" name="planilla_prom_ant7_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_7; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant8_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_8; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 4): ?>
			<tr class="fila2">
				<td>Cuarto</td>
				<td align="center" ><input type="text" name="planilla_prom_ant9_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_9; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant10_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_10; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 5): ?>
			<tr class="fila1">
				<td>Quinto</td>
				<td align="center" ><input type="text" name="planilla_prom_ant11_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_11; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant12_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_12; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 6): ?>
			<tr class="fila2">
				<td>Sexto</td>
				<td align="center" ><input type="text" name="planilla_prom_ant13_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_13; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant14_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_14; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 7): ?>
			<tr class="fila1">
				<td>Septimo</td>
				<td align="center" ><input type="text" name="planilla_prom_ant15_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_15; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant16_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_16; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 8): ?>
			<tr class="fila2">
				<td>Octavo</td>
				<td align="center" ><input type="text" name="planilla_prom_ant17_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_17; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant18_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_18; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 9): ?>
			<tr class="fila1">
				<td>Noveno</td>
				<td align="center" ><input type="text" name="planilla_prom_ant19_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_19; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant20_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_20; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 10): ?>
			<tr class="fila2">
				<td>Decimo</td>
				<td align="center" ><input type="text" name="planilla_prom_ant21_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_21; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant22_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_22; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 11): ?>
			<tr class="fila1">
				<td>Once</td>
				<td align="center" ><input type="text" name="planilla_prom_ant23_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_23; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant24_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_24; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 21): ?>
			<tr class="fila2">
				<td>Ciclo 1</td>
				<td align="center" ><input type="text" name="planilla_prom_ant25_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_25; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant26_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_26; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 22): ?>
			<tr class="fila1">
				<td>Ciclo 2</td>
				<td align="center" ><input type="text" name="planilla_prom_ant27_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_27; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant28_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_28; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 23): ?>
			<tr class="fila2">
				<td>Ciclo 3</td>
				<td align="center" ><input type="text" name="planilla_prom_ant29_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_29; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant30_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_30; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 24): ?>
			<tr class="fila1">
				<td>Ciclo 4</td>
				<td align="center" ><input type="text" name="planilla_prom_ant31_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_31; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant32_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_32; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 25): ?>
			<tr class="fila2">
				<td>Ciclo 5</td>
				<td align="center" ><input type="text" name="planilla_prom_ant33_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_33; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant34_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_34; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 26): ?>
			<tr class="fila1">
				<td>Ciclo 6</td>
				<td align="center" ><input type="text" name="planilla_prom_ant35_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_35; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant36_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_36; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 37): ?>
			<tr class="fila2">
				<td>N.E.E</td>
				<td align="center" ><input type="text" name="planilla_prom_ant37_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_37; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant38_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_38; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 38): ?>
			<tr class="fila1">
				<td>Grupos Juveniles 1</td>
				<td align="center" ><input type="text" name="planilla_prom_ant39_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_39; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant40_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_40; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 39): ?>
			<tr class="fila2">
				<td>Grupos Juveniles 2</td>
				<td align="center" ><input type="text" name="planilla_prom_ant41_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_41; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant42_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_42; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 40): ?>
			<tr class="fila1">
				<td>Grupos Juveniles 3</td>
				<td align="center" ><input type="text" name="planilla_prom_ant43_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_43; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant44_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_44; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 41): ?>
			<tr class="fila2">
				<td>Grupos Juveniles 4</td>
				<td align="center" ><input type="text" name="planilla_prom_ant45_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_45; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant46_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_46; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 42): ?>
			<tr class="fila1">
				<td>Grupos Juveniles 5</td>
				<td align="center" ><input type="text" name="planilla_prom_ant47_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_47; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant48_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_48; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 43): ?>
			<tr class="fila2">
				<td>Grupos Juveniles 6</td>
				<td align="center" ><input type="text" name="planilla_prom_ant49_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_49; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant50_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_50; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base['grado_base'] == 99): ?>
			<tr class="fila1">
				<td>AA</td>
				<td align="center" ><input type="text" name="planilla_prom_ant51_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_51; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant52_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_52; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
<?php } ?>
		</table>
	</div>
	</div>
	</div>
	</div>
	</div>

			<div class="accordion_example2wqzx">
        <div class="accordion_inwerds">
		<div class="acc_headerfgd"><strong>Tope de estudiantes zona rural</strong> </div>
        <div class="acc_contentsaponk">
		 <div class="grevdaiolxx">	
		<table width="100%" class="formulario" align="center">
		<tr>
			<th class="formulario" align="center" colspan="2">ZONA RURAL</th>
		</tr>
		<tr>
			<th class="formulario" align="center">Nivel</th>
			<th class="formulario" align="center">Tope de Estudiantes</th>
		</tr>
		<?php while ($array_grado_base_rural = mysql_fetch_assoc($sql_grado_base_rural)) {
				//echo $array_grado_base['grado_base'].'<br>';
			?>
			<?php if ($array_grado_base_rural['grado_base'] == 0): ?>
			<tr class="fila2">
				<td>Preescolar</td>
				<td align="center" ><input type="text" name="planilla_prom_ant53_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_53; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant54_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_54; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 1): ?>
			<tr class="fila1">
				<td>Primero</td>
				<td align="center" ><input type="text" name="planilla_prom_ant55_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_55; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant56_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_56; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 2): ?>
			<tr class="fila2">
				<td>Segundo</td>
				<td align="center" ><input type="text" name="planilla_prom_ant57_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_57; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant58_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_58; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 3): ?>
			<tr class="fila1">
				<td>Tercero</td>
				<td align="center" ><input type="text" name="planilla_prom_ant59_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_59; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant60_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_60; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 4): ?>
			<tr class="fila2">
				<td>Cuarto</td>
				<td align="center" ><input type="text" name="planilla_prom_ant61_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_61; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant62_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_62; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 5): ?>
			<tr class="fila1">
				<td>Quinto</td>
				<td align="center" ><input type="text" name="planilla_prom_ant63_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_63; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant64_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_64; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 6): ?>
			<tr class="fila2">
				<td>Sexto</td>
				<td align="center" ><input type="text" name="planilla_prom_ant65_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_65; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant66_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_66; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 7): ?>
			<tr class="fila1">
				<td>Septimo</td>
				<td align="center" ><input type="text" name="planilla_prom_ant67_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_67; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant68_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_68; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 8): ?>
			<tr class="fila2">
				<td>Octavo</td>
				<td align="center" ><input type="text" name="planilla_prom_ant69_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_69; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant70_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_70; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 9): ?>
			<tr class="fila1">
				<td>Noveno</td>
				<td align="center" ><input type="text" name="planilla_prom_ant71_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_71; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant72_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_72; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 10): ?>
			<tr class="fila2">
				<td>Decimo</td>
				<td align="center" ><input type="text" name="planilla_prom_ant73_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_73; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant74_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_74; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 11): ?>
			<tr class="fila1">
				<td>Once</td>
				<td align="center" ><input type="text" name="planilla_prom_ant75_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_75; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant76_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_76; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 21): ?>
			<tr class="fila2">
				<td>Ciclo 1</td>
				<td align="center" ><input type="text" name="planilla_prom_ant77_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_77; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant78_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_78; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 22): ?>
			<tr class="fila1">
				<td>Ciclo 2</td>
				<td align="center" ><input type="text" name="planilla_prom_ant79_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_79; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant80_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_80; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 23): ?>
			<tr class="fila2">
				<td>Ciclo 3</td>
				<td align="center" ><input type="text" name="planilla_prom_ant81_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_81; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant82_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_82; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 24): ?>
			<tr class="fila1">
				<td>Ciclo 4</td>
				<td align="center" ><input type="text" name="planilla_prom_ant83_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_83; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant84_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_84; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 25): ?>
			<tr class="fila2">
				<td>Ciclo 5</td>
				<td align="center" ><input type="text" name="planilla_prom_ant85_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_85; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant86_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_86; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 26): ?>
			<tr class="fila1">
				<td>Ciclo 6</td>
				<td align="center" ><input type="text" name="planilla_prom_ant87_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_87; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant88_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_88; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 37): ?>
			<tr class="fila2">
				<td>N.E.E</td>
				<td align="center" ><input type="text" name="planilla_prom_ant89_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_89; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant90_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_90; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 38): ?>
			<tr class="fila1">
				<td>Grupos Juveniles 1</td>
				<td align="center" ><input type="text" name="planilla_prom_ant91_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_91; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant92_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_92; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 39): ?>
			<tr class="fila2">
				<td>Grupos Juveniles 2</td>
				<td align="center" ><input type="text" name="planilla_prom_ant93_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_93; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant94_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_94; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 40): ?>
			<tr class="fila1">
				<td>Grupos Juveniles 3</td>
				<td align="center"><input type="text" name="planilla_prom_ant95_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_95; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant96_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_96; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 41): ?>
			<tr class="fila2">
				<td>Grupos Juveniles 4</td>
				<td align="center" ><input type="text" name="planilla_prom_ant97_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_97; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant98_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_98; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 42): ?>
			<tr class="fila1">
				<td>Grupos Juveniles 5</td>
				<td align="center" ><input type="text" name="planilla_prom_ant99_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_99; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant100_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_100; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 43): ?>
			<tr class="fila2">
				<td>Grupos Juveniles 6</td>
				<td align="center" ><input type="text" name="planilla_prom_ant101_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_101; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant102_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_102; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
			<?php if ($array_grado_base_rural['grado_base'] == 99): ?>
			<tr class="fila1">
				<td>AA</td>
				<td align="center" ><input type="text" name="planilla_prom_ant103_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_103; ?>" style="border-radius: 10px; width: 18%;" />
					-<input type="text" name="planilla_prom_ant104_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_104; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
				
			<?php endif ?>
<?php } ?>

		</table>	
<!--fin ajuste lina -->
</div>
	</div>
	</div>
	</div>
	</div>

		<?php
		break;
      case 160:
	$array_parametro = explode("$",$row_configuracion['conf_valor']);
		//Consultamos el tope maximo de los estudiantes (Zona Urbana)
		$proyecion_cupos_ind_1 = $array_parametro[1];
		$proyecion_cupos_ind_2 = $array_parametro[2];
		$proyecion_cupos_ind_3 = $array_parametro[3];
		$proyecion_cupos_ind_4 = $array_parametro[4];
		$proyecion_cupos_ind_5 = $array_parametro[5];
		$proyecion_cupos_ind_6 = $array_parametro[6];
		$proyecion_cupos_ind_7 = $array_parametro[7];
		$proyecion_cupos_ind_8 = $array_parametro[8];
		$proyecion_cupos_ind_9 = $array_parametro[9];
        $proyecion_cupos_ind_10 = $array_parametro[10];
		$proyecion_cupos_ind_11 = $array_parametro[11];
        $proyecion_cupos_ind_12 = $array_parametro[12];
        $proyecion_cupos_ind_13 = $array_parametro[13];
        $proyecion_cupos_ind_14 = $array_parametro[14];
        $proyecion_cupos_ind_15 = $array_parametro[15];
        $proyecion_cupos_ind_16 = $array_parametro[16];
        $proyecion_cupos_ind_17 = $array_parametro[17];
		?>
<!-- PARAMETRO 82 -->
		<div class="container_demohrvszv">
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd">&Iacute;tem</div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
		<table width="50%" class="formulario" align="center" style="float:left;">
			<tr>
				<th class="formulario">GRADO</th>
				<th class="formulario">INDICE (%)</th>
			</tr>
			<tr class="fila1">
				<td>Primero</td>
				<td style='text-align:center;'><input type="text" onkeypress="return justNumbers(event);" name="planilla_prom_ant1_ind_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_ind_1; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
           <tr class="fila2">
				<td>Segundo</td>
				<td style='text-align:center;'><input type="text" onkeypress="return justNumbers(event);"
name="planilla_prom_ant2_ind_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_ind_2; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
           <tr class="fila1">
				<td>Tercero</td>
				<td style='text-align:center;'><input type="text" onkeypress="return justNumbers(event);"
name="planilla_prom_ant3_ind_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_ind_3; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
           <tr class="fila2">
				<td>Cuarto</td>
				<td style='text-align:center;'><input type="text" onkeypress="return justNumbers(event);"
name="planilla_prom_ant4_ind_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_ind_4; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
           <tr class="fila1">
				<td>Quinto</td>
				<td style='text-align:center;'><input type="text" onkeypress="return justNumbers(event);"
name="planilla_prom_ant5_ind_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_ind_5; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
			<tr class="fila2">
				<td>Sexto</td>
				<td style='text-align:center;'><input type="text" onkeypress="return justNumbers(event);"
name="planilla_prom_ant6_ind_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_ind_6; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
            <tr class="fila1">
				<td>Septimo</td>
				<td style='text-align:center;'><input type="text" onkeypress="return justNumbers(event);"
name="planilla_prom_ant7_ind_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_ind_7; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
            <tr class="fila2">
				<td>Octavo</td>
				<td style='text-align:center;'><input type="text" onkeypress="return justNumbers(event);"
name="planilla_prom_ant8_ind_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_ind_8; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
           <tr class="fila1">
				<td>Noveno</td>
				<td style='text-align:center;'><input type="text" onkeypress="return justNumbers(event);"
name="planilla_prom_ant9_ind_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_ind_9; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
			<tr class="fila2">
				<td>Media Decimo</td>
				<td style='text-align:center;'><input type="text" onkeypress="return justNumbers(event);"
name="planilla_prom_ant10_ind_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_ind_10; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
           <tr class="fila1">
				<td>Media Once</td>
				<td style='text-align:center;'><input type="text" onkeypress="return justNumbers(event);"
name="planilla_prom_ant11_ind_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_ind_11; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
			<tr class="fila2">
				<td>Ciclo 1 Adultos</td>
				<td style='text-align:center;'><input type="text" onkeypress="return justNumbers(event);"
name="planilla_prom_ant12_ind_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_ind_12; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
            <tr class="fila1">
				<td>Ciclo 2 Adultos</td>
				<td style='text-align:center;'><input type="text" onkeypress="return justNumbers(event);"
name="planilla_prom_ant13_ind_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_ind_13; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
            <tr class="fila2">
				<td>Ciclo 3 Adultos</td>
				<td style='text-align:center;'><input type="text" onkeypress="return justNumbers(event);"
name="planilla_prom_ant14_ind_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_ind_14; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
            <tr class="fila1">
				<td>Ciclo 4 Adultos</td>
				<td style='text-align:center;'><input type="text" onkeypress="return justNumbers(event);"
name="planilla_prom_ant15_ind_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_ind_15; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
			<tr class="fila2">
				<td>Ciclo 5 Adultos</td>
				<td style='text-align:center;'><input type="text" onkeypress="return justNumbers(event);"
name="planilla_prom_ant16_ind_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_ind_16; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
            <tr class="fila1">
				<td>Ciclo 6 Adultos</td>
				<td style='text-align:center;'><input type="text" onkeypress="return justNumbers(event);"
name="planilla_prom_ant17_ind_<?php echo $row_configuracion['conf_nombre']; ?>" value="<?php echo $proyecion_cupos_ind_17; ?>" style="border-radius: 10px; width: 18%;" /></td>
			</tr>
		</table>
		</div>
</div>
</div>
</div>
</div>
<!-- FIN PARAMETRO 82 -->
		<?php
		break;}
?>
	</td>
</tr>
<?php
}while($row_configuracion = mysql_fetch_assoc($configuracion));
?>
<?php
///Instanciacion de clases para el llamado de los parametros
// include 'ParametrosGenerales.php';
$objetoParametros16=new ParametrosGenerales();  ///Instancio el Objeto ParametroGenerales
$conexionBd=$objetoParametros16->creaConexion();  ///llamo la funcion creaConexion
$numPersiana=16;    
$cantidadParametros=$objetoParametros16  -> returnCantidadParametrosPersiana($conexionBd,$numPersiana); //Retorna la cantidad de parametros que hay dentro de la persiana
$cant=0;
while($cant<$cantidadParametros)  //Mientras haya Parametros Entre al while
{
	 	$valor_id_parametro_individual=$objetoParametros16  -> devuelvaParametros($cant);
       $objetoParametros16 ->ejecutarImpresionParametros($numPersiana,$conexionBd,$consecutivo,$valor_id_parametro_individual ); //Ejecuta la impresion de los paramertros individualmente
       $consecutivo=$objetoParametros16 ->returnConsecutivo();    //Retorna el consecutivo
        $informacionDelParametro=$objetoParametros16  ->informacionParametro($conexionBd,$valor_id_parametro_individual);  //Informacion del parametro 
       $cant++;
       // switch($valor_id_parametro_individual)
       // {
       // }
}
?>
</table>
</div>
</div>
</div>
</div>
</div>
<!---------------------------------------------- RECONOCIMIENTO DE VOZ -------------------------------- -->
<?php
// esta es la tabla 2
if($totalRows_configuracion)
{
	mysql_data_seek($configuracion,0);
mysql_select_db($database_sygescol, $sygescol);
	$query_configuracion = "SELECT conf_sygescol.conf_id, conf_sygescol.conf_nombre, conf_sygescol.conf_valor, conf_sygescol.conf_descri, conf_sygescol.conf_nom_ver, encabezado_parametros.titulo
								FROM conf_sygescol  INNER JOIN encabezado_parametros on encabezado_parametros.id_param=conf_sygescol.conf_id
							WHERE conf_sygescol.conf_estado = 0
								AND conf_sygescol.conf_id IN (243)  ORDER BY encabezado_parametros.id_orden ";
	$configuracion = mysql_query($query_configuracion, $sygescol) or die(mysql_error());
	$row_configuracion = mysql_fetch_assoc($configuracion);
// aca inicia la otra tabla
}?>
<?php
include ("conb.php");$registrosppo=mysqli_query($conexion,"select * from conf_sygescol_adic where id=18")or die("Problemas en la Consulta".mysqli_error());while ($regppo=mysqli_fetch_array($registrosppo)){$coloracordppo=$regppo['valor'];}
?>
<div class="container_demohrvszv_caja_1">
		<div class="accordion_example2wqzx_caja_2">
			<div class="accordion_inwerds_caja_3">
			<?php
$query_ano_sistema162 = "SELECT * FROM year";
$ano_sistema162 = mysql_query($query_ano_sistema162, $sygescol) or die(mysql_error());
$row_ano_sistema162 = mysql_fetch_assoc($ano_sistema162);
$totalRows_ano_sistema162 = mysql_num_rows($ano_sistema162);
$an = $row_ano_sistema162["b"];


 ?>
				<div class="acc_headerfgd_caja_titulo" id="parametros_control_calificaciones" style="background-color: <?php echo $coloracordppo ?>"><center><strong>17. PAR&Aacute;METROS PARA EL CONTROL DE DISPOSITIVOS MOVILES</strong></center><br /><center><input type="radio" value="rojoppo" name="coloresppo">Si&nbsp;&nbsp;<input type="radio" value="naranjappo" name="coloresppo">No</div></center>
				<div class="acc_contentsaponk_caja_4">
					<div class="grevdaiolxx_caja_5">
					<table  align="center" width="85%" class="centro" cellpadding="10" class="formulario"  border="1">
<tr>
	<th class="formulario"  style="background: #3399FF;" >Consecutivo </th>
	<th class="formulario"  style="background: #3399FF;" >Id Par&aacute;metro </th>
	<th class="formulario" >Tipo de Par&aacute;metro</th>
    <th class="formulario" >Detalle del Par&aacute;metro</th>
	<th class="formulario">Selecci&oacute;n</th>
	</tr>
	<?php
	do
	{
		$consecutivo++;
	?>
	<td class="formulario"   id="Consecutivo<?php echo $consecutivo; ?>" ><strong> <?php echo $consecutivo; ?></strong></td>
	<td class="formulario"  id="Parametro<?php echo $row_configuracion['conf_id']; ?>" ><strong><?php echo $row_configuracion['conf_id'] ?></strong></td>
<td valign="top"><strong>
<div class="container_demohrvszv_caja_tipo_param">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Tipo de Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx_caja_tipo_param">
<div id="conf_nom_ver">
<div  class="textarea "  align="justify" id="conf_nom_ver-<?php echo $row_configuracion['conf_id'] ?>"><?php echo $row_configuracion['conf_nom_ver']; ?></div>
</div></div></div></div></div></div>
</strong>
</td>
<td valign="top" width="80%">
     <div class="container_demohrvszv" >
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd"  id="cgasvf"><strong>Detalle Par&aacute;metro</strong></div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
<div id="conf_descri">
      <div  align="justify" class="textarea " id="conf_descri-<?php echo $row_configuracion['conf_id'] ?>" style="width: 90%;" align="justify">
      <?php echo $row_configuracion['conf_descri']; ?>
      </div>
</div>
					</div>
				</div>
			</div>
		</div>
</div>
 </td>
<td align="center">
<?php
switch($row_configuracion['conf_id'])
{
		case 243:
			?>

<?php
include("conb.php");
$salida_grados19xxz=mysqli_query($conexion,"SELECT *FROM conf_sygescol where conf_id=243")
or die ("Problemas en la Consulta2 ".mysqli_error());
while ($salida_grados29xxz=mysqli_fetch_array($salida_grados19xxz)){
$msmparametrizado=$salida_grados29xxz['conf_valor'];
}
$datosbdw=explode("$", $msmparametrizado);

?>
<div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
     <div class="accordion_inwerds">
        <div class="acc_headerfgd"><strong>Mensajes predeterminados</strong> </div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
<script>
function agregarrv1(){
	var div = document.getElementById('rv1');
div.innerHTML = div.innerHTML + 'Extra stuff';
}
</script>
<div style="text-align:center;font-weight:bolder">Estudiantes que son llamados por segunda vez en el mismo intervalo de clase y ya fue registrada la inasistencia.</div>
<textarea name="rv1" id="rv1" cols="10" rows="10" class="textarearv"><?php echo $datosbdw[0]; ?></textarea>
<br />
<div style="text-align:center;font-weight:bolder">Estudiantes llamados con datos similares.</div>
<textarea name="rv2" id="rv2" cols="10" rows="10" class="textarearv"><?php echo $datosbdw[1]; ?></textarea>
<br />
<div style="text-align:center;font-weight:bolder">Estudiantes llamados con datos incorrectos.</div>
<textarea name="rv3" id="rv3" cols="10" rows="10" class="textarearv"><?php echo $datosbdw[2]; ?></textarea>
<br />
<div style="text-align:center;font-weight:bolder">Confirmaci&oacute;n de registro exitoso</div>
<textarea name="rv4" id="r4" cols="10" rows="10" class="textarearv"><?php echo $datosbdw[3]; ?></textarea>
<style>
	
.textarearv {
	border: 1px solid #6297BC;
	width: 100%;
	height: 30px;
}
</style>
			
</div>
</div>
</div>
</div>
</div>
<div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
     <div class="accordion_inwerds">
        <div class="acc_headerfgd"><strong>Tiempo predeterminados(Segundos):</strong> </div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
<script>
function agregarrv1(){
	var div = document.getElementById('rv1');
div.innerHTML = div.innerHTML + 'Extra stuff';
}
</script>
<table border="0" width="100%">
	
	<tr>
<td>
<div style="text-align:center;font-weight:bolder">Analisis de la informacion: </div> 
</td>
</tr>
<tr>
<td>
<div style="text-align:justify"> <br /> Intervalo de tiempo que emplea el sistema para analizar la informaci&oacute;n ingresada.</div>		<br />	
</td>
</tr>
<tr>	
<td>
<center><input type="number" name="rv5" value="<?php echo $datosbdw[4]; ?>" />	</center> <br />
</td>
	</tr>
	<tr>
		<td>
		<div style="text-align:center;font-weight:bolder">Mensajes de ayuda: </div>	<br />
		<td>
		</tr>
		
		<tr>
		<td>
		<center>
			<div style="text-align:justify">Intervalo de tiempo que emplea el sistema para poner en pantalla el mensaje de ayuda.</div>
		</center><br />
		</td>
</tr>
		<tr>
		<td>
			<center>
				<input type="number" name="rv6" value="<?php echo $datosbdw[5]; ?>" />
			</center>
		</td>
	</tr>
</table>
<br />
<style>
	
.textarearv {
	border: 1px solid #6297BC;
	width: 100%;
	height: 30px;
}
</style>
			
</div>
</div>
</div>
</div>
</div>
<div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
     <div class="accordion_inwerds">
        <div class="acc_headerfgd"><strong>Tipo de interaccion con el Reconocimiento de voz</strong> </div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
<script>
function agregarrv1(){
	var div = document.getElementById('rv1');
div.innerHTML = div.innerHTML + 'Extra stuff';
}
</script>
<input type="radio" name="tirv" value="tv" <?php if ($datosbdw[6] == "tv") {echo 'checked="checked"';} ?> /><label>Touch/Voz</label>
<br />
<input type="radio" name="tirv" value="v" <?php if ($datosbdw[6] == "v") {echo 'checked="checked"';} ?> /><label> Voz</label>
<br />

<style>
	
.textarearv {
	border: 1px solid #6297BC;
	width: 100%;
	height: 30px;
}
</style>
			
</div>
</div>
</div>
</div>
</div>
<div class="container_demohrvszv" style="display:none">
    <div class="accordion_example2wqzx">
     <div class="accordion_inwerds">
        <div class="acc_headerfgd"><strong>Estructura de la informacion</strong> </div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
<script>
function agregarrv1(){
	var div = document.getElementById('rv1');
div.innerHTML = div.innerHTML + 'Extra stuff';
}
</script>
<center>
	<input type="radio" name="testruc" value="t1" <?php if ($datosbdw[7] == "t1") {echo 'checked="checked"';} ?> /><label>Tipo 1</label>
</center>
<center>
	<img src="images/tipo1.PNG" name="imgrv" alt="" />
</center>
<br />
<center>
	<input type="radio" name="testruc" value="t2" <?php if ($datosbdw[7] == "t2") {echo 'checked="checked"';} ?> /><label> Tipo 2</label>
</center>
<center>
	<img src="images/tipo2.PNG" name="imgrv" alt="" />
</center>
<br />
<style>
	.imgrv{
		width: 90%;
		height: 10%;
		margin: 0 auto;
	}
.textarearv {
	border: 1px solid #6297BC;
	width: 100%;
	height: 30px;
}
</style>
			
</div>
</div>
</div>
</div>
</div>

<div class="container_demohrvszv" style="display:none">
    <div class="accordion_example2wqzx">
     <div class="accordion_inwerds">
        <div class="acc_headerfgd"><strong>&iquest;Permitir registros los fines de semana?</strong> </div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
<script>
function agregarrv1(){
	var div = document.getElementById('rv1');
div.innerHTML = div.innerHTML + 'Extra stuff';
}
</script>
<input type="radio" name="tfines" value="fs" /><label>Si</label>
<br />
<input type="radio" name="tfines" value="fn"/><label> No</label>
<br />
<style>
	
.textarearv {
	border: 1px solid #6297BC;
	width: 100%;
	height: 30px;
}
</style>
			
</div>
</div>
</div>
</div>
</div>
	</td>		<?php 
		break;
 }
?>
	
</tr>
<?php
}while($row_configuracion = mysql_fetch_assoc($configuracion));

?>
<?php
///Instanciacion de clases para el llamado de los parametros
// include 'ParametrosGenerales.php';
$objetoParametros17=new ParametrosGenerales();  ///Instancio el Objeto ParametroGenerales
$conexionBd=$objetoParametros17->creaConexion();  ///llamo la funcion creaConexion
$numPersiana=17;  
$cantidadParametros=$objetoParametros17 -> returnCantidadParametrosPersiana($conexionBd,$numPersiana);  //Retorna la cantidad de parametros que hay dentro de la persiana
$cant=0;
while($cant<$cantidadParametros)  //Mientras haya Parametros Entre al while
{		
	 	$valor_id_parametro_individual=$objetoParametros17  -> devuelvaParametros($cant);
       $objetoParametros17 ->ejecutarImpresionParametros($numPersiana,$conexionBd,$consecutivo,$valor_id_parametro_individual ); //Ejecuta la impresion de los paramertros individualmente
       $consecutivo=$objetoParametros17->returnConsecutivo();    //Retorna el consecutivo
       $informacionDelParametro=$objetoParametros17  ->informacionParametro($conexionBd,$valor_id_parametro_individual);  //Informacion del parametro  
       $cant++;
       // echo '<script>alert("'.$valor_id_parametro_individual.'")</script>';
       //Area de Impresion de la seleccion o las opciones que va a tener el parametro
       switch($valor_id_parametro_individual)
       {
       case 244:
       include("conb.php");
       // echo '<script>alert("'.$informacionDelParametro["conf_nombre"].'")</script>';
       ?>	
       		       <th>
					<div class="container_demohrvszv">
					    <div class="accordion_example2wqzx">
					     <div class="accordion_inwerds">
					        <div class="acc_headerfgd"><strong>Aplica el Uso de la App</strong> </div>
					        <div class="acc_contentsaponk">
					          <div class="grevdaiolxx">
					          Aplica:
					          <select name='usoApp244'>
					          <option value='S' <?php if ($informacionDelParametro["conf_valor"] == "S") { echo "selected=\"selected\"";} ?> >SI</option>
					          <option value='N' <?php if ($informacionDelParametro["conf_valor"] == "N") { echo "selected=\"selected\"";} ?> >NO</option>
					          </select>
					          </div>
					         </div>
					        </div>
					       </div>
					    </div>  
					 </th>
					 </tr>
       <?php
       break; 
       }

}

?>
	
	<!-- 	</th>
		 </tr> -->
		
</table>
</div>
</div>
</div>
</div>
</div>
<!---------------------------------------------- RECONOCIMIENTO DE PAE -------------------------------- -->
<?php
// esta es la tabla 2
if($totalRows_configuracion)
{
	mysql_data_seek($configuracion,0);
mysql_select_db($database_sygescol, $sygescol);
	$query_configuracion = "SELECT conf_sygescol.conf_id, conf_sygescol.conf_nombre, conf_sygescol.conf_valor, conf_sygescol.conf_descri, conf_sygescol.conf_nom_ver, encabezado_parametros.titulo
								FROM conf_sygescol  INNER JOIN encabezado_parametros on encabezado_parametros.id_param=conf_sygescol.conf_id
							WHERE conf_sygescol.conf_estado = 0
								AND conf_sygescol.conf_id IN (251)  ORDER BY encabezado_parametros.id_orden ";
	$configuracion = mysql_query($query_configuracion, $sygescol) or die(mysql_error());
	$row_configuracion = mysql_fetch_assoc($configuracion);
// aca inicia la otra tabla
}?>
<?php
include ("conb.php");$registrosppo=mysqli_query($conexion,"select * from conf_sygescol_adic where id=18")or die("Problemas en la Consulta".mysqli_error());while ($regppo=mysqli_fetch_array($registrosppo)){$coloracordppo=$regppo['valor'];}
?>
<div class="container_demohrvszv_caja_1">
		<div class="accordion_example2wqzx_caja_2">
			<div class="accordion_inwerds_caja_3">
			<?php
$query_ano_sistema162 = "SELECT * FROM year";
$ano_sistema162 = mysql_query($query_ano_sistema162, $sygescol) or die(mysql_error());
$row_ano_sistema162 = mysql_fetch_assoc($ano_sistema162);
$totalRows_ano_sistema162 = mysql_num_rows($ano_sistema162);
$an = $row_ano_sistema162["b"];
 ?>
				<div class="acc_headerfgd_caja_titulo" id="parametros_control_calificaciones" style="background-color: <?php echo $coloracordppo ?>"><center><strong>18. PAR&Aacute;METROS DEL PAE</strong></center><br /><center><input type="radio" value="rojoppo" name="coloresppo">Si&nbsp;&nbsp;<input type="radio" value="naranjappo" name="coloresppo">No</div></center>
				<div class="acc_contentsaponk_caja_4">
					<div class="grevdaiolxx_caja_5">
					<table  align="center" width="85%" class="centro" cellpadding="10" class="formulario"  border="1">
<tr>
	<th class="formulario"  style="background: #3399FF;" >Consecutivo </th>
	<th class="formulario"  style="background: #3399FF;" >Id Par&aacute;metro </th>
	<th class="formulario" >Tipo de Par&aacute;metro</th>
    <th class="formulario" >Detalle del Par&aacute;metro</th>
	<th class="formulario">Selecci&oacute;n</th>
	</tr>
	<?php
	do
	{
		$consecutivo++;
	?>
	<td class="formulario"   id="Consecutivo<?php echo $consecutivo; ?>" ><strong> <?php echo $consecutivo; ?></strong></td>
	<td class="formulario"  id="Parametro<?php echo $row_configuracion['conf_id']; ?>" ><strong><?php echo $row_configuracion['conf_id'] ?></strong></td>
<td valign="top"><strong>
<div class="container_demohrvszv_caja_tipo_param">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Tipo de Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx_caja_tipo_param">
<div id="conf_nom_ver">
<div  class="textarea "  align="justify" id="conf_nom_ver-<?php echo $row_configuracion['conf_id'] ?>"><?php echo $row_configuracion['conf_nom_ver']; ?></div>
</div></div></div></div></div></div>
</strong>
</td>
<td valign="top" width="80%">
     <div class="container_demohrvszv" >
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd"  id="cgasvf"><strong>Detalle Par&aacute;metro</strong></div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
<div id="conf_descri">
      <div  align="justify" class="textarea " id="conf_descri-<?php echo $row_configuracion['conf_id'] ?>" style="width: 90%;" align="justify">
      <?php echo $row_configuracion['conf_descri']; ?>
      </div>
      </div>
					</div>
				</div>
			</div>
		</div>
</div>
 </td>
<td align="center">
<?php
switch($row_configuracion['conf_id'])
{
case 251:
include("conb.php");
$salida_grados19xxz=mysqli_query($conexion,"SELECT * FROM conf_sygescol where conf_id = 251")or die ("Problemas en la Consulta2 ".mysqli_error());
while ($salida_grados29xxz=mysqli_fetch_array($salida_grados19xxz))
{
$msmparametrizado=$salida_grados29xxz['conf_valor'];
}
$datosbdw4=explode("=", $msmparametrizado);

// if ((strcmp("S", $datosbdw4[0]))) {echo "selected=\"selected\"";}   
?>
			<!-- MULTIPLE -->
<script type="text/javascript" src="js/mootools/multipleselect.js"></script>
<script type='text/javascript' src='js/mootools/libs/mootoolsmore.lib.js'></script>
<link rel="stylesheet" href="js/mootools/multipleselect_demo.css">
<!--MULTIPLE -->
<script type='text/javascript'>
    document.addEvent('domready', function() {
   		  new MultipleSelect('directivos'); /*  Seleccione el docente*/
 		  new MultipleSelect('docente_am'); /*  Seleccione el docente*/
   		  new MultipleSelect('directivos1'); /*  Seleccione el docente*/
 		  new MultipleSelect('docente_al'); /*  Seleccione el docente*/
 		  new MultipleSelect('directivos11'); /*  Seleccione el docente*/
 		  new MultipleSelect('docente_pm'); /*  Seleccione el docente*/
   });
	</script>
			<style>
.textarearv {
	border: 1px solid #6297BC;
	width: 100%;
	height: 30px;
}
</style>
<script>
function agregarrv1(){
var div = document.getElementById('rv1');
div.innerHTML = div.innerHTML + 'Extra stuff';
}
</script>
<style>
.textarearv {
	border: 1px solid #6297BC;
	width: 100%;
	height: 30px;
}
</style>
<div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
     <div class="accordion_inwerds">
        <div class="acc_headerfgd"><strong>&#191;Aplica el P.A.E?</strong> </div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
<center>
<select name="aplicapae">

	<option value="S"  <?php if ($datosbdw4[1] == "S") { echo "selected=\"selected\"";} ?>>Si</option>
<option value="N"  <?php if ($datosbdw4[1] == "N") { echo "selected=\"selected\"";} ?>>No</option>
</select>
</center>
</div>
</div>
</div>
</div>
</div>
<div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
     <div class="accordion_inwerds">
        <div class="acc_headerfgd"><strong>Administradores AM</strong> </div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
<table border="0" width="100%">
	<tr>
	<td>
	<center>
<table align="center" style="text-align:center">
<?php 
$query_sedes11 = "SELECT DISTINCT id, nombre FROM admco where cargo LIKE '%rector%' ORDER BY nombre";
$sedes11 = mysql_query($query_sedes11, $link) or die(mysql_error());
$totalRows_sedes11 = mysql_num_rows($sedes11);
$query_sedes112 = "SELECT DISTINCT id, nombre FROM admco where cargo LIKE '%COORDI%' ORDER BY nombre";	
$sedes112 = mysql_query($query_sedes112, $link) or die("No se pudo consultar los integrantes");
$totalRows_sedes112 = mysql_num_rows($sedes112);
     ?>
<tr>     
      <td><b>Directivos</b></td>
      </tr>
    <tr>
      <td height="20" align="left" valign="middle" style="margin-left: 33%;"><select name="directivos_am[]" multiple="multiple" id="directivos" style=" height:150px; width:250px;">
 <center><optgroup label="Rector" >
 	<?php
 	while ($row_sedes11 = mysql_fetch_array($sedes11)) {  
 	?>
 	     <option style="width:100px;" value="<?php echo $row_sedes11['id']?>"><?php echo $row_sedes11['nombre']?></option>
 	     <?php
 	}
 		?><optgroup label="Docentes Administrativo" >	<?php
 	while ($row_sedes112 = mysql_fetch_array($sedes112)) {  
 	?>
 	     <option style="width:100px;" value="<?php echo $row_sedes112['id']?>"><?php echo $row_sedes112['nombre']?></option>
 	     <?php
 	} 
?></center>
</td>
</tr>
</table>
</center>
</td>
</tr>
<tr>
	<td>
<center>
	<table align="center"  style="text-align:center">
<?php 
$query_sedes22 = "SELECT * FROM  `dcne` ";
$sedes22 = mysql_query($query_sedes22, $link) or die(mysql_error());
$totalRows_sedes22 = mysql_num_rows($sedes22);
     ?>
     <tr>  
     <td><b>Docente :</b></td>
     </tr>
     <br />
    <tr>
<td align="center" ><select name="docente_am[]" multiple="multiple" id="docente_am">     
<?php
	$con = 0;
	while($row_sedes22 = mysql_fetch_array($sedes22))
	{
	?>
<option style="width:230px;" value="<?php echo $row_sedes22['i'] ?>"><?php echo $row_sedes22['dcne_ape1'] . " " . $row_sedes22['dcne_ape2']. " " . $row_sedes22['dcne_nom1']. " " . $row_sedes22['dcne_nom2']?></option>
          <?php
		$con++;
	}
	?>
</select>
      </td>
    </tr>
<input name="gruposVar01"  id="gruposVar01" type="hidden" value=""/>
<input name="gruposVar02"  id="gruposVar02" type="hidden" value=""/>
<?php
include("conb.php");
$registro=mysqli_query($conexion,"SELECT * FROM conf_sygescol where conf_id=251")
or die ("Problemas en la selecciona rv_analizar principal ".mysqli_error());
while ($descripwt=mysqli_fetch_array($registro)){
$registrosgen=$descripwt["conf_valor"];
}
$s2=explode("=", $registrosgen);
$hha=$s2[2];
$s3=explode("$", $hha);
$s4=$s3[0];
$s5=explode(",", $s4);
array_pop($s5);
$ppfinal=implode(",", $s5);
?>
  <input name="gruposVar02_static"  id="grupowwerrwersVar02" type="hidden" value="<?php  echo $ppfinal ?>" >
	</table>
	</center>
</table>
<br />
<a href="./admi_am.php"
        onclick="return hs.htmlExpand(this, {
            contentId: 'my-content',
            objectType: 'iframe',
            objectWidth: 650,
            objectHeight: 450} )"
        class="highslide">
  <center> Ver Administradores</center>
</a>
<div class="highslide-html-content"
        id="my-content" style="width: 650px">
    <div style="text-align: right">
       <a href="#" onclick="return hs.close(this)">
          Close
       </a>
    </div>    
    <div class="highslide-body"></div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
     <div class="accordion_inwerds">
        <div class="acc_headerfgd"><strong>Administradores Almuerzo</strong> </div>
        <div class="acc_contentsaponk">
<div class="grevdaiolxx">
<table border="0" width="100%">
	<tr>
	<td>
	<center>
<table align="center" style="text-align:center">
	<?php 
$query_sedes11 = "SELECT DISTINCT id, nombre FROM admco where cargo LIKE '%rector%' ORDER BY nombre";
$sedes11 = mysql_query($query_sedes11, $link) or die(mysql_error());
$totalRows_sedes11 = mysql_num_rows($sedes11);
$query_sedes112 = "SELECT DISTINCT id, nombre FROM admco where cargo LIKE '%COORDI%' ORDER BY nombre";	
$sedes112 = mysql_query($query_sedes112, $link) or die("No se pudo consultar los integrantes");
$totalRows_sedes112 = mysql_num_rows($sedes112);
     ?>
     <tr>     
      <td><b>Directivos</b></td>
</tr>
    <tr>
      <td height="20" align="left" valign="middle" style="margin-left: 33%;"><select name="directivos_al[]" multiple="multiple" id="directivos1" style=" height:150px; width:250px;">
 <center><optgroup label="Rector" >
 	<?php
 	while ($row_sedes11 = mysql_fetch_array($sedes11)) {  
 	?>
<option style="width:100px;" value="<?php echo $row_sedes11['id']?>"><?php echo $row_sedes11['nombre']?></option>
<?php
 	} 
 		?><optgroup label="Docentes Administrativo" >	<?php
 	while ($row_sedes112 = mysql_fetch_array($sedes112)) {  
 	?>
 	     <option style="width:100px;" value="<?php echo $row_sedes112['id']?>"><?php echo $row_sedes112['nombre']?></option>
 	     <?php
 	} 
 	?></center>
</td>
</tr>
</table>
</center>
</td>
</tr>
<tr>
	<td>
<center>
	<table align="center"  style="text-align:center">
 <?php 
$query_sedes22 = "SELECT * FROM  `dcne` ";
$sedes22 = mysql_query($query_sedes22, $link) or die(mysql_error());
$totalRows_sedes22 = mysql_num_rows($sedes22);
     ?>
     <tr>  
     <td><b>Docente :</b></td>
     </tr>
     <br />
    <tr>
      <td align="center" ><select name="docente_al[]" multiple="multiple" id="docente_al">     
<?php
	$con = 0;
	while($row_sedes22 = mysql_fetch_array($sedes22))
	{
	?>
        <option style="width:230px;" value="<?php echo $row_sedes22['i'] ?>"><?php echo $row_sedes22['dcne_ape1'] . " " . $row_sedes22['dcne_ape2']. " " . $row_sedes22['dcne_nom1']. " " . $row_sedes22['dcne_nom2']?></option>
          <?php
		$con++;
	}
	?>
      </select>
      </td>
    </tr>
  <input name="gruposVar03"  id="gruposVar03" type="hidden" value=""/>
  <input name="gruposVar04"  id="gruposVar04" type="hidden" value=""/>
<?php
include("conb.php");
$registro=mysqli_query($conexion,"SELECT * FROM conf_sygescol where conf_id=251")
or die ("Problemas en la selecciona rv_analizar principal ".mysqli_error());
while ($descripwt=mysqli_fetch_array($registro)){
$registrosgen=$descripwt["conf_valor"];
}
$s2=explode("=", $registrosgen);
$hha=$s2[2];
$s3=explode("$", $hha);
$s4=$s3[1];
$s5=explode(",", $s4);
array_pop($s5);
$ppfinal=implode(",", $s5);
?>
<input name="gruposVar04_static"  id="gruposVar02wewewewe" type="hidden"  value="<?php  echo $ppfinal ?>" >
	</table>
	</center>
</table>
<a href="./admi_al.php"
        onclick="return hs.htmlExpand(this, {
            contentId: 'my-content',
            objectType: 'iframe',
            objectWidth: 650,
            objectHeight: 450} )"
        class="highslide">
  <center> Ver Administradores</center>
</a>
<div class="highslide-html-content"
        id="my-content" style="width: 650px">
    <div style="text-align: right">
       <a href="#" onclick="return hs.close(this)">
          Close
   </a>
    </div>    
    <div class="highslide-body"></div>
</div>
<br />
<style>
.textarearv {
	border: 1px solid #6297BC;
	width: 100%;
	height: 30px;
}
</style>
</div>
</div>
</div>
</div>
</div>
<div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
     <div class="accordion_inwerds">
      <div class="acc_headerfgd"><strong>Administradores PM</strong> </div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
<table border="0" width="100%">
	<tr>
	<td>
	<center>
<table align="center" style="text-align:center">
<?php
$query_sedes11 = "SELECT DISTINCT id, nombre FROM admco where cargo LIKE '%rector%' ORDER BY nombre";
$sedes11 = mysql_query($query_sedes11, $link) or die(mysql_error());
$totalRows_sedes11 = mysql_num_rows($sedes11);
$query_sedes112 = "SELECT DISTINCT id, nombre FROM admco where cargo LIKE '%COORDI%' ORDER BY nombre";	
$sedes112 = mysql_query($query_sedes112, $link) or die("No se pudo consultar los integrantes");
$totalRows_sedes112 = mysql_num_rows($sedes112);
     ?>
     <tr>     
      <td><b>Directivos</b></td>
 </tr>
   <tr>
      <td height="20" align="left" valign="middle" style="margin-left: 33%;"><select name="directivos11[]" multiple="multiple" id="directivos11" style=" height:150px; width:250px;">
<center><optgroup label="Rector" >
<?php
 	while ($row_sedes11 = mysql_fetch_array($sedes11)) {  
?>
<option style="width:100px;" value="<?php echo $row_sedes11['id']?>"><?php echo $row_sedes11['nombre']?></option>
<?php
} 
?><optgroup label="Docentes Administrativo" >	<?php
 	while ($row_sedes112 = mysql_fetch_array($sedes112)) {  
 	?>
 	     <option style="width:100px;" value="<?php echo $row_sedes112['id']?>"><?php echo $row_sedes112['nombre']?></option>
<?php
 	} 
?></center>
</td>
</tr>
</table>
</center>
</td>
</tr>
<tr>
	<td>
<center>
	<table align="center"  style="text-align:center">
 <?php 
$query_sedes22 = "SELECT * FROM  `dcne` ";
$sedes22 = mysql_query($query_sedes22, $link) or die(mysql_error());
$totalRows_sedes22 = mysql_num_rows($sedes22);
     ?>
     <tr>  
     <td><b>Docente :</b></td>
     </tr>
     <br />
    <tr>
      <td align="center" ><select name="docente_pm[]" multiple="multiple" id="docente_pm">     
		     <?php
	$con = 0;
	while($row_sedes22 = mysql_fetch_array($sedes22))
	{

	?>
         <option style="width:230px;" value="<?php echo $row_sedes22['i'] ?>"><?php echo $row_sedes22['dcne_ape1'] . " " . $row_sedes22['dcne_ape2']. " " . $row_sedes22['dcne_nom1']. " " . $row_sedes22['dcne_nom2']?></option>
          <?php
		$con++;
	}
	?>
      </select>
      </td>
    </tr>
  <input name="gruposVar05"  id="gruposVar05" type="hidden" value=""/>
  <input name="gruposVar06"  id="gruposVar06" type="hidden" value=""/>
  <?php
include("conb.php");
$registro=mysqli_query($conexion,"SELECT * FROM conf_sygescol where conf_id=251")
or die ("Problemas en la selecciona rv_analizar principal ".mysqli_error());
while ($descripwt=mysqli_fetch_array($registro)){
$registrosgen=$descripwt["conf_valor"];
}
$s2=explode("=", $registrosgen);
$hha=$s2[2];
$s3=explode("$", $hha);
$s4=$s3[2];
$s5=explode(",", $s4);
array_pop($s5);
$ppfinal=implode(",", $s5);
?>
  <input name="gruposVar06_static"  id="gruposVar02wewee" type="hidden"  value="<?php  echo $ppfinal ?>" >
	</table>
	</center>
</table>
<br />
<a href="./admi_pm.php"
        onclick="return hs.htmlExpand(this, {
            contentId: 'my-content',
            objectType: 'iframe',
            objectWidth: 650,
            objectHeight: 450} )"
        class="highslide">
  <center> Ver Administradores</center>
</a>
<div class="highslide-html-content"
        id="my-content" style="width: 650px">
    <div style="text-align: right">
       <a href="#" onclick="return hs.close(this)">
          Close
       </a>
    </div>    
    <div class="highslide-body"></div>
</div>
<style>
.textarearv {
	border: 1px solid #6297BC;
	width: 100%;
	height: 30px;
}
</style>
</div>
</div>
</div>
</div>
</div>
<div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
     <div class="accordion_inwerds">
        <div class="acc_headerfgd"><strong>Establecer hora del Almuerzo</strong> </div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
<?php
$farc=$datosbdw4[3];
$horaspaead=explode("$", $farc);
?>
<center>
Hora Inicial: <input type="time" required name="hora_inicio" id="hora" value="<?php echo $horaspaead[0]; ?>" size="9" />
<br />
Hora Final: <input type="time" required name="hora_fin" id="hora2" value="<?php echo $horaspaead[1]; ?>" size="9" />
	</center>
<script language="javascript">
	var checkJquery = jQuery.noConflict();
	checkJquery(document).ready(function() {
		checkJquery('#hora').scroller({
			preset: 'time',
			theme: 'ios',
			display: 'bubble',       		
			mode: 'clickpick'
		});
	});
</script>	
	</center>
<script language="javascript">
	var checkJquery = jQuery.noConflict();
	checkJquery(document).ready(function() {
		checkJquery('#hora2').scroller({
			preset: 'time',
			theme: 'ios',
			display: 'bubble',       		
			mode: 'clickpick'
		});
	});
</script>	
</div>
</div>
</div>
</div>
</div>
<div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
     <div class="accordion_inwerds">
        <div class="acc_headerfgd"><strong>Establecer Dias PAE</strong> </div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
          <center>
<?php
$farc2=$datosbdw4[4];
$horaspaead2=explode("$", $farc2);
?>
<input type="checkbox" name="diaspae1" value="LV"  <?php if ($horaspaead2[0] == "S") {echo 'checked="checked"';} ?> /><label>Lunes a Viernes</label>
<br />
<input type="checkbox" name="diaspae2" value="S"  <?php if ($horaspaead2[1] == "S") {echo 'checked="checked"';} ?> /><label>S&aacute;bado</label>
<br />
<input type="checkbox" name="diaspae3" value="D"  <?php if ($horaspaead2[2] == "S") {echo 'checked="checked"';} ?> /><label>Domingo</label>
<br />
          </center>
</div>
</div>
</div>
</div>
</div>
			<?php 
		break;
   }
?>
	</td>
</tr>
<?php
}while($row_configuracion = mysql_fetch_assoc($configuracion));
?>
<?php
///Instanciacion de clases para el llamado de los parametros
// include 'ParametrosGenerales.php';
$objetoParametros18=new ParametrosGenerales();  ///Instancio el Objeto ParametroGenerales
$conexionBd=$objetoParametros18->creaConexion();  ///llamo la funcion creaConexion
$numPersiana=18;    
$cantidadParametros=$objetoParametros18  -> returnCantidadParametrosPersiana($conexionBd,$numPersiana); //Retorna la cantidad de parametros que hay dentro de la persiana
$cant=0;
while($cant<$cantidadParametros)  //Mientras haya Parametros Entre al while
{
	 	$valor_id_parametro_individual=$objetoParametros18  -> devuelvaParametros($cant);
       $objetoParametros18 ->ejecutarImpresionParametros($numPersiana,$conexionBd,$consecutivo,$valor_id_parametro_individual ); //Ejecuta la impresion de los paramertros individualmente
       $consecutivo=$objetoParametros18 ->returnConsecutivo();    //Retorna el consecutivo
        $informacionDelParametro=$objetoParametros18  ->informacionParametro($conexionBd,$valor_id_parametro_individual);  //Informacion del parametro 
       $cant++;
       // switch($valor_id_parametro_individual)
       // {
       // }
}
?>
</table>
</div>
</div>
</div>
</div>
</div>
<div class="container_demohrvszv_caja_1">
		<div class="accordion_example2wqzx_caja_2">
			<div class="accordion_inwerds_caja_3">
				<div class="acc_headerfgd_caja_titulo" style="background-color: <?php echo $coloracordppo ?>"><center><strong>19. PAR&Aacute;METROS PARA EL TRANSPORTE ESCOLAR</strong></center><br /><center><input type="radio" value="rojoppo" name="coloresppo">Si&nbsp;&nbsp;<input type="radio" value="naranjappo" name="coloresppo">No</div></center>
				<div class="acc_contentsaponk_caja_4">
					<div class="grevdaiolxx_caja_5">
					<table  align="center" width="85%" class="centro" cellpadding="10" class="formulario"  border="1">
<tr>
	<th class="formulario"  style="background: #3399FF;" >Consecutivo </th>
	<th class="formulario"  style="background: #3399FF;" >Id Par&aacute;metro </th>
	<th class="formulario" >Tipo de Par&aacute;metro</th>
    <th class="formulario" >Detalle del Par&aacute;metro</th>
	<th class="formulario">Selecci&oacute;n</th>
	</tr>
<?php
$consecutivo ++;
$sel_transporte = "SELECT * FROM conf_sygescol WHERE conf_id = 254";
$sql_transporte = mysql_query($sel_transporte,$link) or die("No se puede consultar el transporte");
$rows_transporte = mysql_fetch_assoc($sql_transporte);
$explode = explode("$", $rows_transporte['conf_valor']);

switch ($rows_transporte['conf_id']) {
	case 254:
?>
<link rel="stylesheet" href="js/mootools/multipleselect_demo.css">
<script type='text/javascript'>
   document.addEvent('domready', function() {
      new MultipleSelect('admin_transporte');
   });
</script>
	<td class="formulario"   id="Consecutivo<?php echo $consecutivo; ?>" ><strong> <?php echo $consecutivo; ?></strong></td>
	<td class="formulario"  id="Parametro<?php echo $rows_transporte['conf_id']; ?>" ><strong><?php echo $rows_transporte['conf_id'] ?></strong></td>
<td valign="top"><strong>
<div class="container_demohrvszv_caja_tipo_param">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Tipo de Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx_caja_tipo_param">
<div id="conf_nom_ver">
<div  class="textarea "  align="justify" id="conf_nom_ver-<?php echo $rows_transporte['conf_id'] ?>"><?php echo $rows_transporte['conf_nom_ver']; ?></div>
</div></div></div></div></div></div>
</strong>
</td>
<td valign="top" width="80%">
     <div class="container_demohrvszv" >
		<div class="accordion_example2wqzx">
			<div class="accordion_inwerds">
				<div class="acc_headerfgd"  id="cgasvf"><strong>Detalle Par&aacute;metro</strong></div>
				<div class="acc_contentsaponk">
					<div class="grevdaiolxx">
<div id="conf_descri">
      <div  align="justify" class="textarea " id="conf_descri-<?php echo $rows_transporte['conf_id'] ?>" style="width: 90%;" align="justify">
      <?php echo $rows_transporte['conf_descri']; ?>
      </div>
      </div>
					</div>
				</div>
			</div>
		</div>
</div>
 </td>
<td>
<div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
     <div class="accordion_inwerds">
        <div class="acc_headerfgd"><strong>&#191;Aplica el Transporte Escolar?</strong> </div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
<center>
<select name="aplicatransporte">
	<option value="S" <?php if ($explode[0] == "S") { echo "selected=\"selected\"";} ?>>Si</option>
<option value="N"<?php if ($explode[0] == "N") { echo "selected=\"selected\"";} ?>>No</option>
</select>
</center>
</div>
</div>
</div>
</div>
</div>
<div class="container_demohrvszv">
    <div class="accordion_example2wqzx">
     <div class="accordion_inwerds">
        <div class="acc_headerfgd"><strong>Administradores del Transporte Escolar</strong> </div>
        <div class="acc_contentsaponk">
          <div class="grevdaiolxx">
<center>
<table>
<tr>
<td>Docentes<br><br></td>
</tr>
<tr>
<td>
<?php 
$sel_docente = "SELECT * FROM dcne";
$sql_docente = mysql_query($sel_docente,$link) or die("No se pueden consultar los docentes");
?>
<select name="admin_transporte[]" id="admin_transporte" multiple style="postion:relative; height:150px; width:250px;">
<?php
while ($rows_docente = mysql_fetch_assoc($sql_docente)) 
{
?>
<option value="<?php echo $rows_docente['i']; ?>"><?php echo $rows_docente['dcne_ape1']." ".$rows_docente['dcne_ape2']." ".$rows_docente['dcne_nom1']." ".$rows_docente['dcne_nom2'] ?></option>
<?php
}
 ?>
 </select>
 </td>
 </tr>
 <tr>
 <td>
 <br>
<a href="admin_transporte.php"
        onclick="return hs.htmlExpand(this, {
            contentId: 'my-content',
            objectType: 'iframe',
            objectWidth: 650,
            objectHeight: 450} )"
        class="highslide">
  <center> Ver Administradores</center>
</a>
<div class="highslide-html-content"
        id="my-content" style="width: 650px">
    <div style="text-align: right">
       <a href="#" onclick="return hs.close(this)">
          Close
   </a>
    </div>    
    <div class="highslide-body"></div>
</div>
 </td>
 </tr>
 </table>
</center>
</div>
</div>
</div>
</div>
</div>
</td>
<?php
		break;
}
 ?>
<?php
///Instanciacion de clases para el llamado de los parametros
// include 'ParametrosGenerales.php';
$objetoParametros19=new ParametrosGenerales();  ///Instancio el Objeto ParametroGenerales
$conexionBd=$objetoParametros19->creaConexion();  ///llamo la funcion creaConexion
$numPersiana=19;    
$cantidadParametros=$objetoParametros19  -> returnCantidadParametrosPersiana($conexionBd,$numPersiana); //Retorna la cantidad de parametros que hay dentro de la persiana
$cant=0;
while($cant<$cantidadParametros)  //Mientras haya Parametros Entre al while
{		
	$valor_id_parametro_individual= $objetoParametros19 ->devuelvaParametros($cant);
       $objetoParametros19 ->ejecutarImpresionParametros($numPersiana,$conexionBd,$consecutivo,$valor_id_parametro_individual); //Ejecuta la impresion de los paramertros individualmente
       $consecutivo=$objetoParametros19 ->returnConsecutivo();    //Retorna el consecutivo
        $informacionDelParametro=$objetoParametros19  ->informacionParametro($conexionBd,$valor_id_parametro_individual);  //Informacion del parametro 
       $cant++;
       // switch ($valor_id_parametro_individual) {
       // 	case :
       // 	break;
       // }
}
?>
</table>
</div>
</div>
</div>
</div>
</div><br>


<div id='espacios'></div>
<tr>
<td></td>
</tr>
<tr>
<div class="busqm form-style-5" style="background-color:transparent;">
<br /><br />
<link rel="stylesheet" href="icomoon/style.css" />
 <style>
.encontrado{
text-align: center;
color: #f00;
}
/*nuevos*/
#containerbusca {
    padding: 1em 0 0;
    text-align: center;
    position: absolute;
}
#searchform {
    display: inline;
    font-size: 16px;
    border-radius: 8em;
    font-family: arial;
    border: 0.2em solid rgb(26, 107, 255);
    box-shadow: 0 0 0.3em rgb(26, 107, 255);
    padding: 1em;
    background: white;
    color: #1a6bff;
    opacity: 0.6;
}
#searchform:hover {
    display: inline;
    font-size: 16px;
    border-radius: 8em;
    font-family: arial;
    border: 0.2em solid rgb(26, 107, 255);
    box-shadow: 0 0 0.3em rgb(26, 107, 255);
    padding: 1em;
    background: white;
    color: #1a6bff;
    opacity: 1;
}
#s {
    transition: all 0.2s ease-out;
    width: 188px;
    border-radius: 0;
    box-shadow: none;
    outline: none;
    padding: 0;
    color: #1a6bff;
    font-weight: bold;
    border: 0;
    background-color: transparent;
    font-size: 20px;
    opacity: 1;
}
#s:focus {
  width: 10em;
  opacity: 1;
}
[class^="icon-"], [class*=" icon-"] {
    font-family: 'icomoon' !important;
    speak: none;
    font-style: normal;
    color: #2f79ff;
    font-weight: bold;
    font-size: 18px;
    font-variant: normal;
    text-transform: none;
    line-height: 1;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
</style>
 <div id="containerbusca">
  <label for="s">
   <span class="icon-search"></span>
  </label>
 <select id="s" name="tipo_busqueda">
  		<option value="4">Consecutivo</option>
		<option value="1">Id del par&aacute;metro</option>
		<option value="2">Tipo de par&aacute;metro</option>
		<option value="3">Detalle de par&aacute;metro</option>
	</select>
	<button name="buscar" style="display:none;">Buscar </button>
  <input type="search" value="" placeholder="Buscar..." class="" type="search" id="s" name="palabra_clave" style="border-width: 2px;padding: 4px;border-style: solid;border-color: #2f79ff;   border-radius: 9px;" />
</div>
 

<?php 
if (isset($_POST['buscar'])) 
{
?>
<script>
var jqNc = jQuery.noConflict();
palabra_clave = "<?php echo $_POST['palabra_clave']; ?>";
tipo_busqueda = "<?php echo $_POST['tipo_busqueda']; ?>";
if (tipo_busqueda == 1) 
{
busqueda = "#Parametro"+palabra_clave;
}
else if (tipo_busqueda == 2)
{
 busqueda = "div[id='conf_nom_ver']:Contains('"+palabra_clave+"')"
}
else if (tipo_busqueda == 3)
{
 busqueda = "div[id='conf_descri']:Contains('"+palabra_clave+"')"
}
else if (tipo_busqueda == 4)
{
busqueda = "#Consecutivo"+palabra_clave;
}
else
{
alert("Debe seleccionar una busqueda");
}
jqNc.expr[":"].Contains = jqNc.expr.createPseudo(function(arg) {
    return function( elem ) {
        return jqNc(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
    };
});
jqNc( busqueda ).addClass( "encontrado" );

jqNc(busqueda).parents('div').addClass( "encontrado" );

</script>
<?php
}
// if (isset($_POST['borrarparametros'])) {
// //echo '<script type="text/javascript">alert("Esta segudo de que quiere resturar los valores predeterminados");</script>';
// $selborrarparametros = "UPDATE conf_sygescol SET conf_valor = NULL WHERE conf_id IN (152,65,93,95,96,100,109,66,68,73,99,123,124,115,161,167,241,14,67,76,87,111,127,132,141,149,163,236,56,89,92,94,97,110,102,117,154,235,114,244,70,122,223,108,112,113,135,120,155,121,150,116,129,75,90,101,103,107,118,119,130,131,133,134,138,139,91,104,105,140,158,128,153,159,240,136,164,142,143,144,145,146,147,166,168,165,137,156,162,160)";
// $sqlborrarparametros = mysql_query($selborrarparametros, $link);
// /*
// header('Location: parametros_generales.php');
// /*
// $self = $_SERVER['PHP_SELF']; //Obtenemos la página en la que nos encontramos
// header("refresh:300; url=$self"); //Refrescamos cada 300 segundos
// //header('refresh:2; url=parametros_generales');*
// header("Location: parametros_generales.php"); 
// header("Location: parametros_generales.php"); 
// header("Location: parametros_generales.php"); 
// */
// }
 ?>
<br /><br />
</div>
<br>
		<td align="center" colspan="4" style="position: fixed;bottom: 0px;width: 100%; left: 0px;background: rgba(0, 0, 0, 0.3);">
			<input  name="actualizar" type="submit" id="actualizar" value="Actualizar Parametros" onclick="return seRepiteAreaa()">
			<input  type="button" name="volver" value="Volver" onclick="location.href='pag_principal.php'"/>
			<input type='button' name='crear' id='crearNuevoParam' value="Crear Nuevo Parametro"></input>
<style>
#vactod{
	background-color: #FF5252;
	color: white;
}
</style>
		</td>
	</tr>


<script type="text/javascript">
<?php
if($totalRows_configuracion)
{
	mysql_data_seek($configuracion,0);
	mysql_select_db($database_sygescol, $sygescol);
	$query_configuracion = "SELECT conf_sygescol.conf_id, conf_sygescol.conf_nombre, conf_sygescol.conf_valor, conf_sygescol.conf_descri, conf_sygescol.conf_nom_ver
								FROM conf_sygescol
							WHERE conf_sygescol.conf_estado = 0
								AND conf_sygescol.conf_id IN (14, 16, 17, 18, 19, 20, 50, 56, 57, 58, 65, 66, 67, 68, 69, 70, 71, 73, 75, 76, 87, 88,89,90,91,92,93,94,95,96,97,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124,127,128,129,130,131,132,133,134,135,136,137,138,139,140,141,142,143,144,145,146,147,148,149,150,151,152,153,154,155,156,157,158,159,160,223) Order By conf_sygescol.conf_id";
	$configuracion = mysql_query($query_configuracion, $sygescol) or die(mysql_error());
	$row_configuracion = mysql_fetch_assoc($configuracion);
}
do
{
switch($row_configuracion['conf_id'])
	{
			case 119:
		?>
		Calendar.setup({inputField : "<?php echo $row_configuracion['conf_nombre']."_fecha_1"; ?>", ifFormat : "%Y-%m-%d",	button : "aaa_<?php echo $row_configuracion['conf_nombre']; ?>"});
		Calendar.setup({inputField : "<?php echo $row_configuracion['conf_nombre']."_fecha_2"; ?>", ifFormat : "%Y-%m-%d",	button : "bbb_<?php echo $row_configuracion['conf_nombre']; ?>"});
		Calendar.setup({inputField : "<?php echo $row_configuracion['conf_nombre']."_fecha_3"; ?>", ifFormat : "%Y-%m-%d",	button : "ccc_<?php echo $row_configuracion['conf_nombre']; ?>"});
		Calendar.setup({inputField : "<?php echo $row_configuracion['conf_nombre']."_fecha_4"; ?>", ifFormat : "%Y-%m-%d",	button : "ddd_<?php echo $row_configuracion['conf_nombre']; ?>"});
		<?php
		break;
		case 15:
		?>
		Calendar.setup({inputField : "<?php echo $row_configuracion['conf_nombre']; ?>", ifFormat : "%Y-%m-%d",	button : "b_<?php echo $row_configuracion['conf_nombre']; ?>"});
		<?php
		break;
		case 16:
		?>
		Calendar.setup({inputField : "<?php echo $row_configuracion['conf_nombre']; ?>", ifFormat : "%Y-%m-%d",	button : "b_<?php echo $row_configuracion['conf_nombre']; ?>"});
		<?php
		break;
		case 17:
		?>
		Calendar.setup({inputField : "<?php echo $row_configuracion['conf_nombre']; ?>", ifFormat : "%Y-%m-%d",	button : "b_<?php echo $row_configuracion['conf_nombre']; ?>"});
		<?php
		break;
		case 67:
		?>
		Calendar.setup({inputField : "P_<?php echo $row_configuracion['conf_nombre']; ?>", ifFormat : "%Y-%m-%d",	button : "a_<?php echo $row_configuracion['conf_nombre']; ?>"});
		Calendar.setup({inputField : "S_<?php echo $row_configuracion['conf_nombre']; ?>", ifFormat : "%Y-%m-%d",	button : "b_<?php echo $row_configuracion['conf_nombre']; ?>"});
		Calendar.setup({inputField : "T_<?php echo $row_configuracion['conf_nombre']; ?>", ifFormat : "%Y-%m-%d",	button : "c_<?php echo $row_configuracion['conf_nombre']; ?>"});
		Calendar.setup({inputField : "C_<?php echo $row_configuracion['conf_nombre']; ?>", ifFormat : "%Y-%m-%d",	button : "d_<?php echo $row_configuracion['conf_nombre']; ?>"});
		<?php
		break;
		case 121:
		?>
Calendar.setup({inputField : "periodo_fecha_inicio121", ifFormat : "%Y-%m-%d",	button : "pi_<?php echo $row_configuracion['conf_nombre']; ?>"});
Calendar.setup({inputField : "periodo_fecha_final121", ifFormat : "%Y-%m-%d",	button : "pf_<?php echo $row_configuracion['conf_nombre']; ?>"});
Calendar.setup({inputField : "periodo_fecha_inicio121_2", ifFormat : "%Y-%m-%d",	button : "pi_2<?php echo $row_configuracion['conf_nombre']; ?>"});
Calendar.setup({inputField : "periodo_fecha_final121_2", ifFormat : "%Y-%m-%d",	button : "pf_2<?php echo $row_configuracion['conf_nombre']; ?>"});
Calendar.setup({inputField : "periodo_fecha_inicio121_2_1", ifFormat : "%Y-%m-%d",	button : "pi_2_1<?php echo $row_configuracion['conf_nombre']; ?>"});
Calendar.setup({inputField : "periodo_fecha_final121_2_1", ifFormat : "%Y-%m-%d",	button : "pf_2_1<?php echo $row_configuracion['conf_nombre']; ?>"});
		<?php
		break;
		case 113:
		?>
		Calendar.setup({inputField : "periodo_fecha_inicio113", ifFormat : "%Y-%m-%d",	button : "pi_<?php echo $row_configuracion['conf_nombre']; ?>"});
		Calendar.setup({inputField : "periodo_fecha_final113", ifFormat : "%Y-%m-%d",	button : "pf_<?php echo $row_configuracion['conf_nombre']; ?>"});
		<?php
		break;
		case 120:
		?>
		Calendar.setup({inputField : "periodo_fecha_inicio", ifFormat : "%Y-%m-%d",	button : "bb_<?php echo $row_configuracion['conf_nombre']; ?>", range: [<?php echo $row_ano_sistema['b']; ?>,<?php echo $row_ano_sistema['b']; ?>]});
		Calendar.setup({inputField : "periodo_fecha_final", ifFormat : "%Y-%m-%d",	button : "cc_<?php echo $row_configuracion['conf_nombre']; ?>", range: [<?php echo $row_ano_sistema['b']; ?>,<?php echo $row_ano_sistema['b']; ?>]});
		Calendar.setup({inputField : "periodo_fecha_inicioplanilla", ifFormat : "%Y-%m-%d",	button : "bbplanilla_<?php echo $row_configuracion['conf_nombre']; ?>", range: [<?php echo $row_ano_sistema['b']; ?>,<?php echo $row_ano_sistema['b']; ?>]});
		Calendar.setup({inputField : "periodo_fecha_finalplanilla", ifFormat : "%Y-%m-%d",	button : "ccplanilla_<?php echo $row_configuracion['conf_nombre']; ?>", range: [<?php echo $row_ano_sistema['b']; ?>,<?php echo $row_ano_sistema['b']; ?>]});
		<?php
		break;
	}
}while($row_configuracion = mysql_fetch_assoc($configuracion));
?>
</script>
</form>
</td>
</tr>
<tr>
<td>
<!-- CONFIGURACION DE LA INTERFACE  -->


<div id='interfaceMostrar' style="width:100%;height:auto+5px;bottom:10%;left:0%;border:1px solid #000;position:fixed;background: rgba(0, 0, 0,0.5);color:white;font-size:20px;font-family:arial;text-shadow:1px 2px 3px #000"> 

<center>
<table>
<tr><th colspan="5"><center>Creacion De Parametros  </center></th></tr>
<tr>
<th><center><label>ID parametro</label></center></th>
<th><center><label>Numero <br> Persiana</label> </center></th>
<th> <center><label>Nombre Parametro<br>dentro del Sistema</label></center></th>
<th><center><label>Tipo Parametro</label></center></th>
<th><center><label>Detalle Del parametro</label></center></th>
</tr>
<tr>
<td><input type='number' id='idParamNuevo' name='idParamNuevo' placeholder='Digite Id'  required> </input></td>
<td>
<select name='numPersiana' id='numPersiana' >
<?php
$cont=0;
while($cont<19)
{	
	$cont++;
	echo "<option value=".$cont.">".$cont."</option>";
}
?>	
</select>
</td>
<td><textarea   style='resize:none' id='nombreParam' name='nombreParam' placeholder='Nombre Del parametro dentro del Sistema' onkeyup="verificarLetras(event)" maxlength='18' required></textarea></td>
<td><textarea  style='resize:none' id='tipoParamNuevo' name='tipoParamNuevo' placeholder='Digite Tipo' required ></textarea></td>
<td><textarea  style='resize:none'  id='detalleParam' name='detalleParam' placeholder='Digite Detalle' required ></textarea></td>
</tr>
</table>
</center>
<center>
<input type='button' name='insertarParam' id='insertarParam' value='Crear' ></input> 
<input type='reset' name='cancelarParam' id='cancelarParam' value='Cancelar' onclick='limpiarInputs()'></input> 
</center>
<center><img  src='images/flecha_abajo.png' id='esconder' width='20px' height="20px"></img></center>
</div>
<?php
//cargo variable con el nombre del dominio
// $nombreDominio=;
// echo '<script>alert("'.$nombreDominio.'")</script>';
?>
<script>
///// Funcion mostrar la interface
var jqNc = jQuery.noConflict();

 jqNc(document).ready(function(){
    
 	jqNc("#interfaceMostrar").fadeOut(1);

 	jqNc('#crearNuevoParam').click(function(){
 		
 		jqNc("#interfaceMostrar").fadeIn(200);
 		jqNc("#espacios").html("<br><br><br><br><br><br><br><br><br><br>");
 		
 	});

 	jqNc('#esconder').click(function(){
 		jqNc("#espacios").html("");
 		jqNc("#interfaceMostrar").fadeOut(200);
 	});

 	jqNc("#insertarParam").click(function(){
 		var errores=verificarCreacionParametros();

 		// alert("Presiono BOTON CREAR"+errores);
 		if(errores!=true)
 		{
 			var idParam=document.getElementById("idParamNuevo").value;
 			var nombreParam=document.getElementById("nombreParam").value;
 			var tipoParam=document.getElementById("tipoParamNuevo").value;
 			var detalleParam=document.getElementById("detalleParam").value;
 			var numPersiana=document.getElementById("numPersiana").value;
            var nombreDominio=document.domain;
 			// Nueva forma de envio de informacion Ajax
 		jqNc.post('http://'+nombreDominio+'/sygescol2017/crear_nuevo_parametro.php',
 			{
 				idParam : idParam,
 				nombreParam : nombreParam,
 				tipoParam: tipoParam,
 				detalleParam:detalleParam,
 				numPersiana:numPersiana
 			},
			function(data,status)
			{
				alert(data);
			    location.reload();
			}
			);
 		}
 			
 	});

 	
 	

 });

function limpiarInputs()
{
    document.getElementById('nombreParam').value='';
	document.getElementById('idParamNuevo').value='';
	document.getElementById('tipoParamNuevo').value='';
	document.getElementById('detalleParam').value='';
}
//Variables con las cuales voy a trabajar en la validacion del capo nombreParam
// var arregloTeclasEspeciales=[8,9,16,17,20,27,33,34,35,36,37,38,39,40,45,46,144,19,126,125,145,220,91,92,93,112,113,114,115,116,117,118,119,120,121,122,123,124,125,126,126];
var cantLetras=0;   
var agregoGuion=0;
var cantBorras=0;
function verificarLetras(event)
{    
// 13 CARACTES ESPECIAL ----- ENTER
// 32 CARACTER ESPECIAL ---- ESPACIO
// 192 CARACTER ESPECIAL ----- Ñ
	var valorNombreParam=document.getElementById('nombreParam').value;  ///Saca el valor del textarea
	var letraPresionada=event.which;

	if(letraPresionada==32  )  //Si presiona la tecla Espacio
	{	
		var cantidadLetras=valorNombreParam.length;    //Cuenta la cantidad de letras 
		var valorNuevo=valorNombreParam.substr(0,cantidadLetras-1);   //extrae todas las letras menos la ultima 
		document.getElementById('nombreParam').value=valorNuevo;   //inserta el valor nuevo al textarea
	}
	else
	{	
		
		if(letraPresionada==13 || letraPresionada==192)   //Si presiona la tecla Enter
		{
			if(letraPresionada==13)
			{
		  //recoge el valor
			var valor_nuevo_sin_espacios= valorNombreParam.replace(/\n/g, "");  //remplaza los saltos de linea 
			document.getElementById("nombreParam").value=valor_nuevo_sin_espacios;   //y inserta el valor nuevo sin saltos 
		    }
			else if(letraPresionada==192)  //Si presiona la tecla Ñ O ñ
			{
			var cantidadLetras=valorNombreParam.length;    //Cuenta la cantidad de letras 
			var valorNuevo=valorNombreParam.substr(0,cantidadLetras-1);   //extrae todas las letras menos la ultima 
			document.getElementById('nombreParam').value=valorNuevo;   //inserta el valor nuevo al textarea
			}

		}
		else
		{		

				if(letraPresionada==192)  //Si presiona la tecla Ñ O ñ
				{
					var cantidadLetras=valorNombreParam.length;    //Cuenta la cantidad de letras 
					var valorNuevo=valorNombreParam.substr(0,cantidadLetras-1);   //extrae todas las letras menos la ultima 
					document.getElementById('nombreParam').value=valorNuevo;   //inserta el valor nuevo al textarea
				}
				else
				{
				
					var cant=valorNombreParam.length;
					if(cant==5 || cant==11)
					{
					document.getElementById('nombreParam').value+='_';   //Agrega el guin bajo
					}
				}
				
		}
		
	}
	
	
	
}

function verificarCreacionParametros()
{	
		var nombreParam=document.getElementById('nombreParam').value;
		var idParam=document.getElementById('idParamNuevo').value;
		var tipoParam=document.getElementById('tipoParamNuevo').value;
		var detalleParam=document.getElementById('detalleParam').value;
		var numPersiana=document.getElementById("numPersiana").value;

    if(nombreParam==''  || detalleParam=='' )
    {	
    	alert('Por Favor llene los Campos Faltantes');
    	return true;
    }
    else if(tipoParam='' || idParam=='')
    {
    	alert('Por Favor llene los Campos Faltantes');
    	return true;
    }
    else if(numPersiana='')
    {
    	alert('Por Favor llene los Campos Faltantes');
    	return true;
    }


    
}

</script>
<!--FIN CONFIGURACION INTERFACE PARA CREAR NUEVOS PARAMETROS -->

</td>
</tr>

<tr>
<th class="footer">
<?php
include_once("inc.footer.php");
mostrar_mensaje($_GET['listo']);
?>
</th>
</tr>
</table>
<style type="text/css">
.formulario55 th{
	border: solid 2px rgba(23, 18, 18, 0.08);
}
input[type="radio"]{
    display: inline-block;
    width: 19px;
    height: 19px;
    background: url(img/check_radio_sheet.png) left top no-repeat;
    margin: -1px 2px 0 0;
    vertical-align: middle;
    cursor:pointer;
}
input[type="checkbox"]{
display: inline-block;
width: 17px;
height: 19px;
background: url(img/check_radio_sheet.png) left top no-repeat;
margin: -1px 2px 0 0;
vertical-align: middle;
cursor: pointer;
}
</style>
<script type="text/javascript">
	function activaAreas(){
		var caja = document.getElementById("totAreasPerdidas");
		var radioGen = document.getElementsByName("dm_29");
		var contenido = "null";
		for(var i=0;i<radioGen.length;i++)
        {
        	 if(radioGen[i].checked){
        	 	contenido=radioGen[i].value;
        	 }
        }
		if (contenido == 3) {
			caja.style.display = "block";
		}else{
			caja.style.display = "none";
		}
	}
</script>
<style type="text/css">
    .container_demohrvszv_caja_tipo_param{
      width: 300px;
      left: 0px;
    }
    .grevdaiolxx_caja_tipo_param{
      color: black;
      text-align: justify;
      white-space:normal;
      width: 240px;
    }
  </style>
<!--ESTILOS ACORDEON-->
<style type="text/css">
    .container_demohrvszv_caja_1{
      width: 1550px;
margin: 5px;
    }
    .grevdaiolxx_caja_5{
      color: black;
      text-align: justify;
      white-space:normal;
      width:900px;
      margin-left: 10px;
    }
  </style>
<style>
.smk_Accordionqwzxasa_caja_param {
  position: relative;
  margin: 0;
  padding: 0px;
  list-style: none;
}
/**
 * --------------------------------------------------------------
 * Section
 * --------------------------------------------------------------
 */
.smk_Accordionqwzxasa_caja_param .accordion_inwerds_caja_3 {
  border: 2px solid #3399FF;
  position: relative;
  margin-top: -1px;
  overflow: hidden;
}
/**
 * --------------------------------------------------------------
 * Head
 * --------------------------------------------------------------
 */
.smk_Accordionqwzxasa_caja_param .accordion_inwerds_caja_3 .acc_headerfgd_caja_titulo {
  position: relative;
  background: #f0f6fe;
  padding: 10px;
  font-size: 14px;
  display: block;
  cursor: pointer;
}
.smk_Accordionqwzxasa_caja_param .accordion_inwerds_caja_3 .acc_headerfgd_caja_titulo .acc_icon_expand_caja_param {
  display: block;
  width: 18px;
  height: 18px;
  position: absolute;
  left: 10px;
  top: 50%;
  margin-top: -9px;
  background: url(images/desplagable_acordeon.png) center 0;
}
/**
 * --------------------------------------------------------------
 * Content
 * --------------------------------------------------------------
 */
.smk_Accordionqwzxasa_caja_param .accordion_inwerds_caja_3 .acc_contentsaponk_caja_4 {
  background: #E7F1FE;
  color: #7B7E85;
  padding: 10px 15px;
}
.smk_Accordionqwzxasa_caja_param .accordion_inwerds_caja_3 .acc_contentsaponk_caja_4 h1:first-of-type,
.smk_Accordionqwzxasa_caja_param .accordion_inwerds_caja_3 .acc_contentsaponk_caja_4 h2:first-of-type,
.smk_Accordionqwzxasa_caja_param .accordion_inwerds_caja_3 .acc_contentsaponk_caja_4 h3:first-of-type,
.smk_Accordionqwzxasa_caja_param .accordion_inwerds_caja_3 .acc_contentsaponk_caja_4 h4:first-of-type,
.smk_Accordionqwzxasa_caja_param .accordion_inwerds_caja_3 .acc_contentsaponk_caja_4 h5:first-of-type,
.smk_Accordionqwzxasa_caja_param .accordion_inwerds_caja_3 .acc_contentsaponk_caja_4 h6:first-of-type {
  margin-top: 15px;
}
/**
 * --------------------------------------------------------------
 * General
 * --------------------------------------------------------------
 */
.smk_Accordionqwzxasa_caja_param .accordion_inwerds_caja_3:first-of-type,
.smk_Accordionqwzxasa_caja_param .accordion_inwerds_caja_3:first-of-type .acc_headerfgd_caja_titulo {
  border-radius: 1px 1px 0 0;
  text-align: center;
}
.smk_Accordionqwzxasa_caja_param .accordion_inwerds_caja_3:last-of-type,
.smk_Accordionqwzxasa_caja_param .accordion_inwerds_caja_3:last-of-type .acc_contentsaponk_caja_4 {
  border-radius: 0 0 3px 3px;
}
.smk_Accordionqwzxasa_caja_param .accordion_inwerds_caja_3.acc_active_caja_param > .acc_contentsaponk_caja_4 {
  display: block;
}
.smk_Accordionqwzxasa_caja_param .accordion_inwerds_caja_3.acc_active_caja_param > .acc_headerfgd_caja_titulo {
  background:rgba(51, 153, 255, 0.5);
}
.smk_Accordionqwzxasa_caja_param .accordion_inwerds_caja_3.acc_active_caja_param > .acc_headerfgd_caja_titulo .acc_icon_expand_caja_param {
  background: url(images/desplagable_acordeon.png) center -18px;
}
.smk_Accordionqwzxasa_caja_param.acc_with_icon .accordion_inwerds_caja_3 .acc_headerfgd_caja_titulo,
.smk_Accordionqwzxasa_caja_param.acc_with_icon .accordion_inwerds_caja_3 .acc_contentsaponk_caja_4 {
  padding-left: 35px;
}
</style>
<!-- -------------------------------------------------------------- JS ACORDEON ------------------------------------------------------------------------ -->
<script type="text/javascript">
    jQuery(document).ready(function($){
      $(".accordion_example2wqzx_caja_2").smk_Accordionqwzxasa_caja_param({
        closeAble: true, //boolean
      });
    });
  </script>
  <!--smok-->
  <script>
;(function ( $ ) {
  $.fn.smk_Accordionqwzxasa_caja_param = function( options ) {
    if (this.length > 1){
      this.each(function() {
        $(this).smk_Accordionqwzxasa_caja_param(options);
      });
      return this;
    }
    // Defaults
    var settings = $.extend({
      animation:  true,
      showIcon:   true,
      closeAble:  false,
      closeOther: true,
      slideSpeed: 150,
      activeIndex: false
    }, options );
    if( $(this).data('close-able') )    settings.closeAble = $(this).data('close-able');
    if( $(this).data('animation') )     settings.animation = $(this).data('animation');
    if( $(this).data('show-icon') )     settings.showIcon = $(this).data('show-icon');
    if( $(this).data('close-other') )   settings.closeOther = $(this).data('close-other');
    if( $(this).data('slide-speed') )   settings.slideSpeed = $(this).data('slide-speed');
    if( $(this).data('active-index') )  settings.activeIndex = $(this).data('active-index');
    // Cache current instance
    // To avoid scope issues, use 'plugin' instead of 'this'
    // to reference this class from internal events and functions.
    var plugin = this;
    //"Constructor"
    var init = function() {
      plugin.createStructure();
      plugin.clickHead();
    }
    // Add .smk_Accordionqwzxasa_caja_param class
    this.createStructure = function() {
      //Add Class
      plugin.addClass('smk_Accordionqwzxasa_caja_param');
      if( settings.showIcon ){
        plugin.addClass('acc_with_icon_caja_param');
      }
      //Create sections if they were not created already
      if( plugin.find('.accordion_inwerds_caja_3').length < 1 ){
        plugin.children().addClass('accordion_inwerds_caja_3');
      }
      //Add classes to accordion head and content for each section
      plugin.find('.accordion_inwerds_caja_3').each(function(index, elem){
        var childs = $(elem).children();
        $(childs[0]).addClass('acc_headerfgd_caja_titulo');
        $(childs[1]).addClass('acc_contentsaponk_caja_4');
      });
      //Append icon
      if( settings.showIcon ){
        plugin.find('.acc_headerfgd_caja_titulo').prepend('<div class="acc_icon_expand_caja_param"></div>');
      }
      //Hide inactive
      plugin.find('.accordion_inwerds_caja_3 .acc_contentsaponk_caja_4').not('.acc_active_caja_param .acc_contentsaponk_caja_4').hide();
      //Active index
      if( settings.activeIndex === parseInt(settings.activeIndex) ){
        if(settings.activeIndex === 0){
          plugin.find('.accordion_inwerds_caja_3').addClass('acc_active_caja_param').show();
          plugin.find('.accordion_inwerds_caja_3 .acc_contentsaponk_caja_4').addClass('acc_active_caja_param').show();
        }
        else{
          plugin.find('.accordion_inwerds_caja_3').eq(settings.activeIndex - 1).addClass('acc_active_caja_param').show();
          plugin.find('.accordion_inwerds_caja_3 .acc_contentsaponk_caja_4').eq(settings.activeIndex - 1).addClass('acc_active_caja_param').show();
        }
      }
    }
    // Action when the user click accordion head
    this.clickHead = function() {
      plugin.on('click', '.acc_headerfgd_caja_titulo', function(){
        var s_parent = $(this).parent();
        if( s_parent.hasClass('acc_active_caja_param') == false ){
          if( settings.closeOther ){
            plugin.find('.acc_contentsaponk_caja_4').slideUp(settings.slideSpeed);
            plugin.find('.accordion_inwerds_caja_3').removeClass('acc_active_caja_param');
          }
        }
        if( s_parent.hasClass('acc_active_caja_param') ){
          if( false !== settings.closeAble ){
            s_parent.children('.acc_contentsaponk_caja_4').slideUp(settings.slideSpeed);
            s_parent.removeClass('acc_active_caja_param');
          }
        }
        else{
          $(this).next('.acc_contentsaponk_caja_4').slideDown(settings.slideSpeed);
          s_parent.addClass('acc_active_caja_param');
        }
      });
    }
    //"Constructor" init
    init();
    return this;
  };
}( jQuery ));
  </script>
<!--ESTILOS ACORDEON-->
<style type="text/css">
    .container_demohrvszv{
      width: 450px;
      left: 0px;
      margin:3px;
    }
    .grevdaiolxx{
      color: black;
      text-align: justify;
      white-space:normal;
      width: 390px;
    }
  </style>
<style>
.smk_Accordionqwzxasa {
  position: relative;
  margin: 0;
  padding: 0px;
  list-style: none;
}
/**
 * --------------------------------------------------------------
 * Section
 * --------------------------------------------------------------
 */
.smk_Accordionqwzxasa .accordion_inwerds {
  border: 2px solid #3399FF;
  position: relative;
  margin-top: -1px;
  overflow: hidden;
}
/**
 * --------------------------------------------------------------
 * Head
 * --------------------------------------------------------------
 */
.smk_Accordionqwzxasa .accordion_inwerds .acc_headerfgd {
  position: relative;
  background: #f0f6fe;
  padding: 10px;
  font-size: 14px;
  display: block;
  cursor: pointer;
}
.smk_Accordionqwzxasa .accordion_inwerds .acc_headerfgd .acc_icon_expand {
  display: block;
  width: 18px;
  height: 18px;
  position: absolute;
  left: 10px;
  top: 50%;
  margin-top: -9px;
  background: url(images/desplagable_acordeon.png) center 0;
}
/**
 * --------------------------------------------------------------
 * Content
 * --------------------------------------------------------------
 */
.smk_Accordionqwzxasa .accordion_inwerds .acc_contentsaponk {
  background: #E7F1FE;
  color: #7B7E85;
  padding: 10px 15px;
}
.smk_Accordionqwzxasa .accordion_inwerds .acc_contentsaponk h1:first-of-type,
.smk_Accordionqwzxasa .accordion_inwerds .acc_contentsaponk h2:first-of-type,
.smk_Accordionqwzxasa .accordion_inwerds .acc_contentsaponk h3:first-of-type,
.smk_Accordionqwzxasa .accordion_inwerds .acc_contentsaponk h4:first-of-type,
.smk_Accordionqwzxasa .accordion_inwerds .acc_contentsaponk h5:first-of-type,
.smk_Accordionqwzxasa .accordion_inwerds .acc_contentsaponk h6:first-of-type {
  margin-top: 15px;
}
/**
 * --------------------------------------------------------------
 * General
 * --------------------------------------------------------------
 */
.smk_Accordionqwzxasa .accordion_inwerds:first-of-type,
.smk_Accordionqwzxasa .accordion_inwerds:first-of-type .acc_headerfgd {
  border-radius: 1px 1px 0 0;
  text-align: center;
}
.smk_Accordionqwzxasa .accordion_inwerds:last-of-type,
.smk_Accordionqwzxasa .accordion_inwerds:last-of-type .acc_contentsaponk {
  border-radius: 0 0 3px 3px;
}
.smk_Accordionqwzxasa .accordion_inwerds.acc_active > .acc_contentsaponk {
  display: block;
}
.smk_Accordionqwzxasa .accordion_inwerds.acc_active > .acc_headerfgd {
  background:rgba(51, 153, 255, 0.5);
}
.smk_Accordionqwzxasa .accordion_inwerds.acc_active > .acc_headerfgd .acc_icon_expand {
  background: url(images/desplagable_acordeon.png) center -18px;
}
.smk_Accordionqwzxasa.acc_with_icon .accordion_inwerds .acc_headerfgd,
.smk_Accordionqwzxasa.acc_with_icon .accordion_inwerds .acc_contentsaponk {
  padding-left: 35px;
}
</style>
<!-- -------------------------------------------------------------- JS ACORDEON ------------------------------------------------------------------------ -->
<script type="text/javascript">
    jQuery(document).ready(function($){
      $(".accordion_example1").smk_Accordionqwzxasa();
      $(".accordion_example2wqzx").smk_Accordionqwzxasa({
        closeAble: true, //boolean
      });
      $(".accordion_example3").smk_Accordionqwzxasa({
        showIcon: false, //boolean
      });
      $(".accordion_example4").smk_Accordionqwzxasa({
        closeAble: true, //boolean
        closeOther: false, //boolean
      });
      $(".accordion_example5").smk_Accordionqwzxasa({closeAble: true});
      $(".accordion_example6").smk_Accordionqwzxasa();
      $(".accordion_example7").smk_Accordionqwzxasa({
        activeIndex: 2 //second section open
      });
      $(".accordion_example8, .accordion_example9").smk_Accordionqwzxasa();
    });
  </script>
  <!--smok-->
  <script>
;(function ( $ ) {
  $.fn.smk_Accordionqwzxasa = function( options ) {
    if (this.length > 1){
      this.each(function() {
        $(this).smk_Accordionqwzxasa(options);
      });
      return this;
    }
    // Defaults
    var settings = $.extend({
      animation:  true,
      showIcon:   true,
      closeAble:  false,
      closeOther: true,
      slideSpeed: 150,
      activeIndex: false
    }, options );
    if( $(this).data('close-able') )    settings.closeAble = $(this).data('close-able');
    if( $(this).data('animation') )     settings.animation = $(this).data('animation');
    if( $(this).data('show-icon') )     settings.showIcon = $(this).data('show-icon');
    if( $(this).data('close-other') )   settings.closeOther = $(this).data('close-other');
    if( $(this).data('slide-speed') )   settings.slideSpeed = $(this).data('slide-speed');
    if( $(this).data('active-index') )  settings.activeIndex = $(this).data('active-index');
    // Cache current instance
    // To avoid scope issues, use 'plugin' instead of 'this'
    // to reference this class from internal events and functions.
    var plugin = this;
    //"Constructor"
    var init = function() {
      plugin.createStructure();
      plugin.clickHead();
    }
    // Add .smk_Accordionqwzxasa class
    this.createStructure = function() {
      //Add Class
      plugin.addClass('smk_Accordionqwzxasa');
      if( settings.showIcon ){
        plugin.addClass('acc_with_icon');
      }
      //Create sections if they were not created already
      if( plugin.find('.accordion_inwerds').length < 1 ){
        plugin.children().addClass('accordion_inwerds');
      }
      //Add classes to accordion head and content for each section
      plugin.find('.accordion_inwerds').each(function(index, elem){
        var childs = $(elem).children();
        $(childs[0]).addClass('acc_headerfgd');
        $(childs[1]).addClass('acc_contentsaponk');
      });
      //Append icon
      if( settings.showIcon ){
        plugin.find('.acc_headerfgd').prepend('<div class="acc_icon_expand"></div>');
      }
      //Hide inactive
      plugin.find('.accordion_inwerds .acc_contentsaponk').not('.acc_active .acc_contentsaponk').hide();
      //Active index
      if( settings.activeIndex === parseInt(settings.activeIndex) ){
        if(settings.activeIndex === 0){
          plugin.find('.accordion_inwerds').addClass('acc_active').show();
          plugin.find('.accordion_inwerds .acc_contentsaponk').addClass('acc_active').show();
        }
        else{
          plugin.find('.accordion_inwerds').eq(settings.activeIndex - 1).addClass('acc_active').show();
          plugin.find('.accordion_inwerds .acc_contentsaponk').eq(settings.activeIndex - 1).addClass('acc_active').show();
        }
      }
    }
    // Action when the user click accordion head
    this.clickHead = function() {
      plugin.on('click', '.acc_headerfgd', function(){
        var s_parent = $(this).parent();
        if( s_parent.hasClass('acc_active') == false ){
          if( settings.closeOther ){
            plugin.find('.acc_contentsaponk').slideUp(settings.slideSpeed);
            plugin.find('.accordion_inwerds').removeClass('acc_active');
          }
        }
        if( s_parent.hasClass('acc_active') ){
          if( false !== settings.closeAble ){
            s_parent.children('.acc_contentsaponk').slideUp(settings.slideSpeed);
            s_parent.removeClass('acc_active');
          }
        }
        else{
          $(this).next('.acc_contentsaponk').slideDown(settings.slideSpeed);
          s_parent.addClass('acc_active');
        }
      });
    }
    //"Constructor" init
    init();
    return this;
  };
}( jQuery ));
  </script>
</body>
</html>
	 <style>
	 	.sweet-alert .sa-icon.sa-warning {
      border-color: #3399FF; }
    .sweet-alert .sa-icon.sa-warning .sa-line {
      background-color: #3399FF;}
    .sweet-alert h2 {
      color: #000; }
    .sweet-alert p {
    color: black;}
    .sweet-alert button {
    background-color: white;
    color:black;
}
.sweet-alert {
    background-color: white;
   }
.sweet-alert button {
    background-color: white;
    color: white;
}
   @-webkit-keyframes pulseWarning {
  0% {
    border-color:#FF9C01; }
  100% {
    border-color:#FBEE47; } }
@keyframes pulseWarning {
  0% {
    border-color:#FF9C01; }
  100% {
    border-color: #FBEE47; } }
.pulseWarning {
  -webkit-animation: pulseWarning 0.75s infinite alternate;
  animation: pulseWarning 0.75s infinite alternate; }
@-webkit-keyframes pulseWarningIns {
  0% {
    background-color: white; }
  100% {
    background-color:white; } }
@keyframes pulseWarningIns {
  0% {
    background-color: white; }
  100% {
    background-color: white; } }
</style>
<!-- Cambiar colores input -->
<style type="text/css">
input[disabled]
{
	background: rgba(178,178,178,0.2);
}
input[type="text"][disabled]
{
	background: rgba(178,178,178,0.2);
}
/*PARAMETROS GENERALES 2017 SAGRADA FAMILIA*/
</style>

