<?php
$roles_con_permiso = array('99','5', '6', '2', '8', '1');
include_once("inc.configuracion.php");
include_once("inc.validasesion.php");
include_once("inc.funciones.php");
include_once("conexion.php");
extract($_POST);
extract($_GET);
//Conexión a la base de datos
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

if(isset($_POST['filtrar']))
{
	$_SESSION['jornada'] = $_POST['jornada'];
	$_SESSION['sede_consecutivo'] = $_POST['sede_consecutivo'];
}
if(isset($_POST['quitar_filtro']))
{
	$_SESSION['jornada'] = '';
	$_SESSION['sede_consecutivo'] = '';
}

//Esta función me actualiza los codigos de los estudiantes que penrenezcan al grupo
function generarCodigo($grupo)
{
	global $link;
	
	$grupo_id = $grupo;
	
	//Busco los datos del grupo
	$sql_datos_grupo = "SELECT v_grupos.gao_codigo, v_grupos.grupo_codigo, v_grupos.jornada_codigo
	FROM v_grupos
	WHERE v_grupos.grupo_id = $grupo_id";
	$resultado_datos_grupo = mysql_query($sql_datos_grupo, $link) or die(mysql_error());
	$row_datos_grupo = mysql_fetch_assoc($resultado_datos_grupo);
	
	//Consulto las matriculas pertenecientes a ese grupo
	$sql_max_codigo = "SELECT matricula.matri_id,alumno.alumno_id FROM matricula,alumno WHERE grupo_id = $grupo_id and alumno.alumno_id = matricula.alumno_id  AND matri_estado = 0 order by alumno.alumno_ape1,alumno.alumno_ape2,alumno.alumno_nom1,alumno.alumno_nom2";
	$resultado_max_codigo = mysql_query($sql_max_codigo, $link) or die(mysql_error());
	$num_rows_matricula = mysql_num_rows($resultado_max_codigo);

	$num_lista=1;
	//Ponemos el codigo del estudiante
	while ($row_max_codigo = mysql_fetch_assoc($resultado_max_codigo))
	{	
		$matri_id = $row_max_codigo['matri_id'];
		$num_lista = str_pad($num_lista, 2, "0", STR_PAD_LEFT);
		$codigo = $row_datos_grupo['gao_codigo'] . $row_datos_grupo['grupo_codigo'] . $num_lista . $row_datos_grupo['jornada_codigo'];
		
		$sql_upd_matricula = "UPDATE matricula SET matri_codigo = '$codigo' WHERE matri_id = $matri_id";
		$resultado_upd_matricula = mysql_query($sql_upd_matricula, $link) or die(mysql_error());
		
		$num_lista++;
	}
}

//Eliminar un grupos
if((isset($eeellas))&&($eeellas!=''))
{ 	
	//Consulto los datos del grupo a eliminar
	$sql_datos_grupo = "SELECT g.i, gao.ba as grado, guo.b as grupo, jraa.b as jornada FROM 
	g, gao, guo, jraa WHERE g.b = gao.i AND g.a = guo.i AND gao.g = jraa.i AND g.i = $gg";
	$resultado_datos_grupo = mysql_query($sql_datos_grupo, $link) or die("No se pudo consultar los datos del grupo");
	$datos_grupo = mysql_fetch_assoc($resultado_datos_grupo);

	$del1=mysql_query("delete from g where i = $gg",$link);
	$cxx=mysql_query("select i from cg where b = $gg",$link);
	$fxx=mysql_fetch_array($cxx);
	$del2=mysql_query("delete from cg where b = $gg",$link);
	$del3=mysql_query("delete from cga where b = $fxx[0]",$link);
	$del4=mysql_query("DELETE FROM grado_profundi WHERE id_grupo=$gg",$link);

	auditoria('', '', '', 8, '', '',"Se elimino el grupo $datos_grupo[grado]-$datos_grupo[grupo] de la jornada $datos_grupo[jornada]", $database_sygescol, $link);
	header("location: grados_y_grupos.php?opcion=listo");
	exit;
}

//Crear un nuevo grupo
if((isset($crear))&&($crear!=''))
{ 
	$nuevog = $nngg+1;
	$sql_ins7 = "insert into g (i, b, a) values ('','$ggr','$sig_grupo')";
	$ins7=mysql_query($sql_ins7,$link) or die("No se pudo ingresar el grupo " . $sql_ins7);

	$cg7=mysql_query("select max(i) from g",$link) or die('No se pudo consultar el max grupo');

	$fcg7=mysql_fetch_array($cg7);

	$cgg7=mysql_query("select b, semestre, a from gao where i = $ggr",$link) or die("No se pudo consultar b de gao");

	$fcgg7=mysql_fetch_array($cgg7);
	
	$modalidad = 1;
	if($fcgg7['a'] == 21 || $fcgg7['a'] == 22 || $fcgg7['a'] == 23 || $fcgg7['a'] == 24 || $fcgg7['a'] == 25 || $fcgg7['a'] == 26)
	{
		$modalidad = 2;
	}
	
	//Definimos el año del grupo
	if($fcgg7['semestre'] == 2)
	{
		if($fcgg7['a'] == 25 || $fcgg7['a'] == 26)
		{
			$anyo_grupo = $_SESSION['lectivo'];
		}
		else
		{
			//Toca definir el año del grupo dentro de la edición de este, ya q puede ser un grupo q empezo el año anterior, o
			//q empieza en el segundo semestre del año
			$anyo_grupo = '0';
		}
	}
	else
	{
		$anyo_grupo = $_SESSION['lectivo'];
	}

	$cj7=mysql_query("select i from cljraa where b = 1 and a = $jo",$link) or die("NO se pudo consultar la jornada del colegio");

	$fcj7=mysql_fetch_array($cj7);

	$sql_inccg7 = "insert into cg values ('','$fcg7[0]','$fcj7[0]','','','','','','','$ggr', ".$fcgg7['semestre'].", $modalidad, '$anyo_grupo','".$_POST['modalidadmedia_id']."')";
	$inscg7=mysql_query($sql_inccg7,$link) or die ("no se pudo crear el registro en cg " . mysql_error() . ' ' . $sql_inccg7);

	$cggn=mysql_query("select max(b) from cg",$link) or die("No se pudo consultar max id cg");

	$fgnn=mysql_fetch_array($cggn);

	$sql_cg77 = "select distinct(a) from cga, v_grupos where n = $_GET[ggr] AND cga.n = v_grupos.gao_id ";
	
	$cg77=mysql_query($sql_cg77,$link) or die ("No se pudo consultar las asignaturas que se dictan en el grado");

	while($fcg77=mysql_fetch_array($cg77))
	{
		$cg43=mysql_query("select i,a from aintrs where i = $fcg77[0]",$link) or die ("No se pudo consultar el id y la abreviatura de la asignatura");
		$fcg43=mysql_fetch_array($cg43);
		$cod = $fcg43[1];
		
		$sql_insnueva = "insert into cga (b, a, e, n) 
						values ('$fgnn[0]','$fcg43[0]','$cod','$ggr')";
		
		$insnueva=mysql_query($sql_insnueva,$link) or die ("No se pudo insertar la relacion del grupo con las asignaturas".$sql_insnueva);
	}
	
	header("location: grados_y_grupos.php?opcion=listo");
	exit;
}

//Crear un nuevo grado
if((isset($_POST['crearGrado']))&&($_POST['crearGrado'] != ''))
{ 
  if((isset($gr))&&($gr!='')&&(isset($ng))&&($ng!='')&&(isset($jo))&&($jo!='')&&(isset($tg))&&($tg!=''))
  {  
	$sql_grado = "SELECT * FROM grados WHERE id_grado = $gr";
	$resultado_grado = mysql_query($sql_grado,$link) or die ("No se pudo consultar la tabla de grados");
	$grado = mysql_fetch_array($resultado_grado);

	$cgradd=mysql_query("select i from gao where ba = '" . $grado['cod_grado'] . "' and g = $jo AND semestre = ".$_POST['semestre']."",$link);
	$fgradd=mysql_fetch_array($cgradd);
	$nfg=mysql_num_rows($cgradd);
	if($nfg>0)
	{
		$joj=mysql_query("select i,b from jraa where i = $jo",$link);
		$fjoj=mysql_fetch_array($joj);
		echo"<script>";
		echo'alert("El grado '.$g.' de la Jornada '.$fjoj[1].', ya fue ingresado al Sistema")';
		echo"</script>";	
	}
	else
	{	
		if($grado['id_grado'] < 13)
		{
			$per_con_id = 1;
		}
		else if ($grado['id_grado'] > 20 && $grado['id_grado'] < 25 && $_POST['semestre'] == 1)
		{
			$per_con_id = 2;
		}
		else if($grado['id_grado'] > 20 && $grado['id_grado'] < 25 && $_POST['semestre'] == 2)
		{
			$per_con_id = 3;
		}
		else if($grado['id_grado'] > 24 && $grado['id_grado'] < 27 && $_POST['semestre'] == 1)
		{
			$per_con_id = 5;
		}
		else if($grado['id_grado'] > 24 && $grado['id_grado'] < 27 && $_POST['semestre'] == 2)
		{
			$per_con_id = 6;
		}
		else if($grado['id_grado'] > 30 && $grado['id_grado'] < 37)
		{
			$per_con_id = 7;
		}
		else if($grado['id_grado'] > 55 && $grado['id_grado'] < 60)
		{
			$per_con_id = 1;
		}else if($grado['id_grado'] == 37)
		{
			$per_con_id = 1;
		}else if($grado['id_grado'] == 99)
		{
			$per_con_id = 1;
		}
	
		echo '';
		$ins=mysql_query("insert into gao values ('','" . $grado['cod_grado'] ."','" . $grado['nombre_grado'] ."','" . $grado['id_grado'] . "','$jo', ".$_POST['semestre'].", $per_con_id)",$link);
		$mm=mysql_query("select max(i) from gao",$link);
		$fm=mysql_fetch_array($mm);
		$m = $fm[0];

		
		$modalidad = 1;
		if($grado['id_grado'] == 21 || $grado['id_grado'] == 22 || $grado['id_grado'] == 23 || $grado['id_grado'] == 24 || $grado['id_grado'] == 25 || $grado['id_grado'] == 26)
		{	
			$modalidad = 2;
		}
		
		//Definimos el año del grupo
		if($_POST['semestre'] == 2)
		{
			if($grado['id_grado'] == 25 || $grado['id_grado'] == 26)
			{
				$anyo_grupo = $_SESSION['lectivo'];
			}
			else
			{
				//Toca definir el año del grupo dentro de la edición de este, ya q puede ser un grupo q empezo el año anterior, o
				//q empieza en el segundo semestre del año
				$anyo_grupo = '0';
			}
		}
		else
		{
			$anyo_grupo = $_SESSION['lectivo'];
		}
		if($grado['id_grado'] == 37){
			$_POST['semestre'] = $_POST['gp_nee'];
		}
		
		
		if($tg == 1)
		{
			$gg=mysql_query("select i from guo limit 0,$ng",$link);
			while($fgg=mysql_fetch_array($gg))
			{
				//for($j=0; $j<$_POST['ng'];$j++){
					$letra="SELECT guo.i FROM guo WHERE guo.i NOT IN 
					(SElECT g.a FROM g WHERE g.b IN 
					  (SELECT gao.i FROM gao WHERE gao.a = 
					   (SELECT gao.a FROM gao WHERE gao.i = $m)
					  )
					) 
					AND guo.i > 
					(SElECT MAX(g.a) FROM g WHERE g.b IN 
					 (SELECT gao.i FROM gao WHERE gao.a = 
					  (SELECT gao.a FROM gao WHERE gao.i = $m)
					 )
					)
					ORDER BY guo.i";
					$query_letra=mysql_query($letra, $link) or die ('No se pudo consultar consecutivo.');
					$row_letra=mysql_fetch_array($query_letra);
			
					$letra=$row_letra[i];
					
					if($letra==''){
						$letra=$fgg[0];
					}
					
					$ins=mysql_query("insert into g values ('','$m','$letra', '')",$link) or die ("No se pudo crear el grupo");
					$cg=mysql_query("select max(i) from g",$link) or die (mysql_error());
					$fcg=mysql_fetch_array($cg);
					$sql_cj = "select i from cljraa where b = 1 and a = $jo";
					$cj=mysql_query($sql_cj,$link) or die(mysql_error());
					$fcj=mysql_fetch_array($cj);
					$inscg=mysql_query("insert into cg values ('','$fcg[0]','$fcj[0]','','','','','','','$m',".$_POST['semestre'].",$modalidad, '$anyo_grupo','".$_POST['modalidadmedia_id']."')",$link) or die("No se pudo ingresar el dato en cg");
					
					if(isset($_POST[profu_grad]) && ($_POST[profu_grad]!='')){
						$inser_pro=mysql_query("insert into grado_profundi (id_grupo, id_profundizacion)
												VALUES ('$fcg[0]', '".$_POST[profu_grad]."')",$link);
					}
				//}
			}
		}
		else
		{
			$gg=mysql_query("select i from guo limit 10,$ng",$link);
			while($fgg=mysql_fetch_array($gg))
			{
				//for($j=0; $j<$_POST['ng'];$j++){
					$letra="SELECT guo.i FROM guo WHERE guo.i NOT IN 
					(SElECT g.a FROM g WHERE g.b IN 
					  (SELECT gao.i FROM gao WHERE gao.a = 
					   (SELECT gao.a FROM gao WHERE gao.i = $m)
					  )
					) 
					AND guo.i > 
					(SElECT MAX(g.a) FROM g WHERE g.b IN 
					 (SELECT gao.i FROM gao WHERE gao.a = 
					  (SELECT gao.a FROM gao WHERE gao.i = $m)
					 )
					)
					ORDER BY guo.i";
					$query_letra=mysql_query($letra, $link) or die ('No se pudo consultar consecutivo.');
					$row_letra=mysql_fetch_array($query_letra);
			
					$letra=$row_letra[i];
					
					if($letra==''){
						$letra=$fgg[0];
					}
					
					
					$ins=mysql_query("insert into g values ('','$m','$letra', '')",$link);
					$cg=mysql_query("select max(i) from g",$link);
					$fcg=mysql_fetch_array($cg);
					$sql_crj = "select i from cljraa where b = 1 and a = $jo";
					$cjr=mysql_query($sql_crj,$link);
					$fcjr=mysql_fetch_array($cjr);
					$sql_inscg = "insert into cg values ('','$fcg[0]','$fcjr[0]','','','','','','','$m','".$_POST['semestre']."','$modalidad', '$anyo_grupo','".$_POST['modalidadmedia_id']."')";
					$inscg=mysql_query($sql_inscg,$link)  or die("No se pudo ingresar el dato en cg".$sql_inscg);
					if(isset($_POST[profu_grad]) && ($_POST[profu_grad]!='')){
						$inser_pro=mysql_query("insert into grado_profundi (id_grupo, id_profundizacion)
												VALUES ('$fcg[0]', '".$_POST[profu_grad]."')",$link);
					}
				//}
			}
		}
		$jjj=mysql_query("select i,b from jraa where i = $jo",$link);
		$fjjj=mysql_fetch_array($jjj);
		
			
		header("location: grados_y_grupos.php?opcion=listo");
		exit;
	}
}

}

//Eliminar el grado
if ((isset($elim))&&($elim!=''))
{
	if (isset($id))
	{
		$link=conectarse();
		
		//Consulto los datos del grado a eliminar
		$sql_datos_grado = "SELECT gao.b AS grado, jraa.b as jornada FROM gao inner join jraa on (gao.g = jraa.i) WHERE gao.i = $id";
		$resultado_datos_grado = mysql_query($sql_datos_grado, $link) or die("No se pudo consultar los datos del grado");
		$datos_grado = mysql_fetch_assoc($resultado_datos_grado);
		
		$del=mysql_query("delete from gao where i = $id",$link);
		$del4=mysql_query("DELETE FROM grado_profundi WHERE id_grupo=(select g.i from g where b = $id)",$link);
		$del=mysql_query("delete from g where b	 = $id",$link);
		$del=mysql_query("delete from cg where p = $id",$link);
	
		auditoria('', '', '', 7, '', '',"Se elimino el grado $datos_grado[grado] de la jornada $datos_grado[jornada]", $sygescol, $link);
	
		header("location: grados_y_grupos.php?opcion=listo");
		exit;
	}
	else
	{
	   echo"<script>";
	   echo'alert("Esta tratando de hacer una tarea no válida")';
	   echo"</script>";
	}
}

//Actualizar datos del grupo
if((isset($_POST['env4']))&&($_POST['env4']!=''))
{
	if((isset($dg))&&($dg!=''))
	{
		$ban=0;
	}
	else
	{
		$ban=1;
	}
	
	$g = $_POST['g'];
	
	$gc=mysql_query("select i from cg where b = $g",$link);
	$fgc=mysql_fetch_array($gc);
	$sql_upd_cg = "update cg set g = '$ub', u = '$dg', e = '$mn', cg_semestre = '$semestre', cg_modalidad = '$modalidad', cg_ano = '$ano' , modalidadmedia_id = '".$_POST['modalidadmedia_id']."' where i = $fgc[0]";
	$upt=mysql_query($sql_upd_cg,$link) or die ("No se pudo actualizar cg " . $sql_upd_cg);
	
	if($_POST['nueva_letra'] != '')
	{
		$actualizar_letra_grupo = "UPDATE g SET a = $_POST[nueva_letra] WHERE i = $g";	
		$resultado_letra_grupo = mysql_query($actualizar_letra_grupo, $link) or die ("No se pudo actualizar la letra del grupo");
		
		//Actualizamos los codigos de los estudiantes pertenecientes al grupo
		generarCodigo($g);
	}
	
	if($_POST['nuevaJornada'] != '' and $_POST['jornada_cmb']!=$_POST['nuevaJornada'])
	{
		//Si van a cambiar la jornada del grupo, entonces toca:
		
		//Consultamos los datos de la jornada y grado
		$sql_jornada_y_grado = "SELECT cljraa.i as 'jornada', gao.i as 'grado'
		FROM jraa, gao, cljraa
		WHERE jraa.i = gao.g AND gao.a = (SELECT gao.a FROM gao, g
		WHERE g.b = gao.i AND g.i = $g) AND gao.i != (SELECT gao.i FROM gao, g
		WHERE g.b = gao.i AND g.i = $g) AND jraa.i = $_POST[nuevaJornada] AND cljraa.a = jraa.i";
		$resultado_jornada_y_grado = mysql_query($sql_jornada_y_grado, $link) or die("No se pudo consultar los datos de la jornada y grado");
		$datos_jornada_y_grado = mysql_fetch_array($resultado_jornada_y_grado);
		
		//Actualizamos el grado del grupo
		$update_grupo = "UPDATE g SET g.b = $datos_jornada_y_grado[grado] WHERE g.i = $g";
		$resultado_update_grupo = mysql_query($update_grupo, $link) or die("No se pudo actualizar e grado del grupo");
		
		//Actualizamos cg
		$update_cg = "UPDATE cg SET a = $datos_jornada_y_grado[jornada] WHERE cg.b = $g";
		$resultado_update_cg = mysql_query($update_cg, $link) or die("No se pudo actualizar cg");
		
		//Actualizamos aun
		$update_aun = "UPDATE matricula SET matricula.grado_id = $datos_jornada_y_grado[grado] 
						WHERE matricula.alumno_id IN (SELECT alumno_id FROM alumno) 
						AND matricula.grupo_id = $g 
						AND matricula.matri_estado=0";
		$resultado_update_aun = mysql_query($update_aun, $link) or die("No se pudo actualizar el grado de los estudiantes");
		
		//Consultamos lo docentes que orientan en el grupo
		$sql_docentes = "SELECT cga.g FROM cga, cg, jornadas_docente WHERE cga.b = cg.i AND cg.b = $g AND cga.g = id_docente AND id_jornada != $datos_jornada_y_grado[jornada]";
		$resultado_docentes = mysql_query($sql_docentes, $link) or die("No se pudo consultar los docentes del grupo");
		
		while($los_docentes = mysql_fetch_array($resultado_docentes))
		{
			$sql_jornada_docente = "INSERT INTO jornadas_docente (id_docente, id_jornada) VALUES ($los_docentes[g], $datos_jornada_y_grado[jornada])";
			$resultado_jornada_docente = mysql_query($sql_jornada_docente, $link) or die("No se pudo registrar la nueva jornada para el docente");
		}
		
		//Actualizamos los codigos de los estudiantes pertenecientes al grupo
		generarCodigo($g);
		
	}
	
	if($_POST['conse_sede'] != '' and $_POST['conse_sede']!=$_POST['sede_cmb'])
	{
		//Actualizamos el grado del grupo
		$update_grupo = "UPDATE g SET g.conse_sede = '$_POST[conse_sede]' WHERE g.i = $g";
		$resultado_update_grupo = mysql_query($update_grupo, $link) or die("No se pudo actualizar la sede del grupo");
		
		$sede = substr($_POST['conse_sede'],-2);
		$sql_upd_aun = "UPDATE matricula SET sede_id = '$sede' WHERE grupo_id = $g";
		$resultado_upd_aun = mysql_query($sql_upd_aun, $link) or die("No se pudo actualizar los datos de los estudiantes");
		
		//Ahora eliminamos el horario del grupo
		$del_horario = "DELETE FROM horario_grupo WHERE hor_grado_grupo = '$g'";
		$resultado_del_horario = mysql_query($del_horario, $link) or die("No se pudo borrar el horario del grupo");
		
		//Desasociamos el grupo del tipo de horario
		$sql_desasociar_grupo = "DELETE FROM tipo_horario_grupo WHERE grupo_id = '$g'";
		$resultado_desasociar_grupo = mysql_query($sql_desasociar_grupo, $link) or die("No se pudo desasociar el grupo");
	}

	if($ban==1)
	{
	   echo"<script>";
	   echo'alert("Datos del grupo actualizados de forma exitosa");';
	   echo "location.href='grados_y_grupos.php?listar=1';";
	   echo"</script>";
	}
	else
	{
	   echo"<script>";
//	   echo'alert("Datos del grupo actualizados de forma exitosa,\naunque no todos los campos fueron diligenciados")';
	   echo'alert("Datos del grupo actualizados de forma exitosa");';
	   echo "location.href='grados_y_grupos.php?listar=1';";
	   echo"</script>";
	}
}

if(isset($_POST['mod'])&&($_POST['mod']!='')){
	$consul_si_exis=mysql_query("SELECT * FROM grado_profundi WHERE id_profundizacion=".$_POST['profundizacion']." AND id_grupo=".$_POST['g']."", $link);
		if(mysql_num_rows($consul_si_exis)==0){
			$query="INSERT INTO grado_profundi (id_grupo, id_profundizacion)
					VALUES ('".$_POST['g']."', '".$_POST['profundizacion']."')";
			$query_ins=mysql_query($query,$link);
		}
	
	echo"<script>";
//	   echo'alert("Datos del grupo actualizados de forma exitosa,\naunque no todos los campos fueron diligenciados")';
	echo "location.href='grados_y_grupos.php?gg=".$_POST['g']."&ggr=".$_POST['ggr2']."&ggp=".$_POST['ggp2']."&edasg=1';";
	echo"</script>";
}



if(isset($_GET['elim_pro'])&&($_GET['elim_pro']!='')){
	$query="DELETE FROM grado_profundi WHERE id='".$_GET['elim_pro']."'";
	$query_del=mysql_query($query,$link);
	
	echo"<script>";
//	   echo'alert("Datos del grupo actualizados de forma exitosa,\naunque no todos los campos fueron diligenciados")';
	echo "location.href='grados_y_grupos.php?gg=".$_GET['g']."&ggr=".$_GET['ggr2']."&ggp=".$_GET['ggp2']."&edasg=1';";
	echo"</script>";
}

if(isset($_GET['cop'])&&($_GET['cop']==1)){
	
	switch($_GET['niv'])
	{
		case -2: $grado_nivel = "-2,-1,0"; break; //Nivel Preescolar
		case -1: $grado_nivel = "-2,-1,0"; break; //Nivel Preescolar
		case 0: $grado_nivel = "-2,-1,0"; break; //Nivel Preescolar
		case 1: $grado_nivel = "1,2,3,4,5"; break; //Nivel Preescolar
		case 2: $grado_nivel = "1,2,3,4,5"; break; //Nivel Preescolar
		case 3: $grado_nivel = "1,2,3,4,5"; break; //Nivel Preescolar
		case 4: $grado_nivel = "1,2,3,4,5"; break; //Nivel Preescolar
		case 5: $grado_nivel = "1,2,3,4,5"; break; //Nivel Preescolar
		
		case 21: $grado_nivel = "21,22"; break; //Nivel Preescolar
		case 22: $grado_nivel = "21,22"; break; //Nivel Basica Primaria
		
		case 6: $grado_nivel = "6,7,8,9"; break; //Nivel Preescolar
		case 7: $grado_nivel = "6,7,8,9"; break; //Nivel Preescolar
		case 8: $grado_nivel = "6,7,8,9"; break; //Nivel Preescolar
		case 9: $grado_nivel = "6,7,8,9"; break; //Nivel Preescolar
		
		case 23: $grado_nivel = "23,24"; break; //Nivel Preescolar
		case 24: $grado_nivel = "23,24"; break; //Nivel Basica Secundaria
		
		case 10: $grado_nivel = "10,11"; break; //Nivel Preescolar
		case 11: $grado_nivel = "10,11"; break; //Nivel Preescolar
		
		case 25: $grado_nivel = "25,26"; break; //Nivel Preescolar
		case 26: $grado_nivel = "25,26"; break; //Nivel Media Vocacional
		
		case 31: $grado_nivel = "31,32,33,34,35,36"; break; //Nivel Media Vocacional
		case 32: $grado_nivel = "31,32,33,34,35,36"; break; //Nivel Media Vocacional
		case 33: $grado_nivel = "31,32,33,34,35,36"; break; //Nivel Media Vocacional
		case 34: $grado_nivel = "31,32,33,34,35,36"; break; //Nivel Media Vocacional
		case 35: $grado_nivel = "31,32,33,34,35,36"; break; //Nivel Media Vocacional
		case 36: $grado_nivel = "31,32,33,34,35,36"; break; //Nivel Media Vocacional
	}
	
	$cos_niv="select * from v_grupos where grado_base IN (".$grado_nivel.") Order By grado_base";
	$query_niv=mysql_query($cos_niv, $link) or die("No se Pudo consultar el Nivel");
	
	while($rows_niv=mysql_fetch_array($query_niv)){
		$consul_si_exis=mysql_query("SELECT * FROM grado_profundi WHERE id_profundizacion=".$_GET['profun']." AND id_grupo=".$rows_niv['cg_id']."", $link);
		if(mysql_num_rows($consul_si_exis)==0){
			$query="INSERT INTO grado_profundi (id_grupo, id_profundizacion)
				VALUES ('".$rows_niv['cg_id']."', '".$_GET['profun']."')";
			$query_ins=mysql_query($query,$link);
		}
	}	
	
	echo"<script>";
//	   echo'alert("Datos del grupo actualizados de forma exitosa,\naunque no todos los campos fueron diligenciados")';
	echo "location.href='grados_y_grupos.php?gg=".$_GET['g']."&ggr=".$_GET['ggr2']."&ggp=".$_GET['ggp2']."&edasg=1';";
	echo"</script>";
}

if(isset($_GET['cop_gra'])&&($_GET['cop_gra']==1)){
	
	$cos_niv="select * from v_grupos where grado_base IN (".$_GET['gra'].") Order By grado_base";
	$query_niv=mysql_query($cos_niv, $link) or die("No se Pudo consultar el Nivel");
	
	while($rows_niv=mysql_fetch_array($query_niv)){
		$consul_si_exis=mysql_query("SELECT * FROM grado_profundi WHERE id_profundizacion=".$_GET['profun']." AND id_grupo=".$rows_niv['cg_id']."", $link);
		if(mysql_num_rows($consul_si_exis)==0){
			$query="INSERT INTO grado_profundi (id_grupo, id_profundizacion)
				VALUES ('".$rows_niv['cg_id']."', '".$_GET['profun']."')";
			$query_ins=mysql_query($query,$link);
		}
	}	
	
	echo"<script>";
//	   echo'alert("Datos del grupo actualizados de forma exitosa,\naunque no todos los campos fueron diligenciados")';
	echo "location.href='grados_y_grupos.php?gg=".$_GET['g']."&ggr=".$_GET['ggr2']."&ggp=".$_GET['ggp2']."&edasg=1';";
	echo"</script>";
}
	$mensaje=0;
	if($_SESSION['perfil_id']==2){
		$sql_datos_coordinadores = "SELECT admco.nombre, sedes.sede_consecutivo, sedes.sede_nombre, sede_coordinador.sc_id
		FROM admco, sedes, sede_coordinador, usuario
		WHERE admco_id = id 
		AND sede_consecutivo = sede_id 
		AND admco_id=usu_fk
		AND usu_id=".$_SESSION['usuario_id']."
		ORDER BY nombre, sede_nombre";
		$resultado_datos_coordinadores = mysql_query($sql_datos_coordinadores, $link) or die("No se pudo consultar las sedes de los coordinadores");
		$num_datos_coordinadores = mysql_num_rows($resultado_datos_coordinadores);
		
		while($rows=mysql_fetch_array($resultado_datos_coordinadores)){
			$sedes_arr.=$rows['sede_consecutivo'].',';
		}
		
		$sedes_arr=substr($sedes_arr,0,strlen($sedes_arr)-1);	
		
		
		if($num_datos_coordinadores==0){
			$mensaje=1;
		}	
	}
	

//Mostramos una a una las sedes de la institución	
	$sedes_arr2='1=1';
	if($sedes_arr!=''){
		$sedes_arr2=" sede_consecutivo IN ($sedes_arr) ";
	}
		
$sql_sedes = "SELECT sede_consecutivo, sede_nombre FROM sedes WHERE $sedes_arr2 ORDER BY sede_consecutivo";
$resultado_sedes = mysql_query($sql_sedes, $link) or die("No se pudo consultar las sedes2".mysql_error());
$num_sedes = mysql_num_rows($resultado_sedes);

$sql_jornadas = "SELECT jraa.b, jraa.i FROM cljraa INNER JOIN jraa ON (cljraa.a = jraa.i)";
$resultado_jornadas = mysql_query($sql_jornadas, $link) or die("No se pudo consultar los datos de la jornada");

//Consulto la sede principal
$sql_dane = "SELECT clrp.tt FROM clrp";
$resultado_dane = mysql_query($sql_dane, $link) or die("No se pudo consultar el dane");
$dane = mysql_fetch_array($resultado_dane);


$sql_sedes_items = "SELECT sede_consecutivo, sede_nombre FROM sedes ORDER BY sede_consecutivo";
$resultado_sedes_items = mysql_query($sql_sedes_items, $link) or die("No se pudo consultar las sedes".mysql_error());
$num_sedes_items = mysql_num_rows($resultado_sedes_items);
$los_items = '-1';
while($rows_items=mysql_fetch_array($resultado_sedes_items)){
	$item = $rows_items['sede_consecutivo'];
			if($item < 10)
			{
				$item = 0 . $item;
			}
			$item = $dane['tt'] .  $item;
			
			$los_items .= ",'" . $item . "'";
}
$los_items = trim($los_items,',');



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $nombre_sistema; ?></title>
<script type="text/javascript" src="js/mootools.js"></script>
<script src="includes/cssmenus2/js/cssmenus.js" type="text/javascript"></script>
<script type="text/javascript" src="js/utilidades.js"></script>
<script type="text/javascript" language="javascript">
function habilitaModalidad(obj)
{
	if(obj.value==10 || obj.value==11)
	{
		document.getElementById("modmedia").style.display="";
		document.getElementById("modalidadmedia_id").value="";
	}
	else
	{
		document.getElementById("modmedia").style.display="none";
		document.getElementById("modalidadmedia_id").value="";
	}
}
/*function verificaGrado(menu)
{
	//alert (menu.value);
	var sem = document.getElementById('semestre');
	if(menu.value == 21 || menu.value == 22 || menu.value == 23 || menu.value == 24 || menu.value == 25 || menu.value == 26)
	{
		variable = new Option("A","1");
		variable2 = new Option("B","2");
		
		sem.options[0] = variable;
		sem.options[1] = variable2;
		sem.selectedIndex = 0;
	}
	else
	{
		sem.options[0] = null;
		sem.options[1] = null;
		
		variable = new Option("Todo el Año","3");
		sem.options[0] = variable;
		sem.selectedIndex = 0;
	}
}*/
function verificaGrado(menu)
{
	//alert (menu.value);
	var sem = document.getElementById('semestre');
	document.getElementById("tr_gp_nee").style.display="none";
	if(menu.value == 21 || menu.value == 22 || menu.value == 23 || menu.value == 24 || menu.value == 25 || menu.value == 26)
	{
		variable = new Option("A","1");
		variable2 = new Option("B","2");
		
		sem.options[0] = variable;
		sem.options[1] = variable2;
		sem.selectedIndex = 0;
	}
	else if(menu.value == 37){
		document.getElementById("tr_gp_nee").style.display="";
		sel2 = document.getElementById('gp_nee');
		variable = new Option("Seleccione Uno...","");
		variable2 = new Option("Ciclo I (TR, 01, 02)","4");
		variable3 = new Option("Ciclo II (03, 04, 05)","5");
		
		sel2.options[0] = variable;
		sel2.options[1] = variable2;
		sel2.options[2] = variable3;
		sel2.selectedIndex = 0;	
		
		//alert("Si");
		sem.options[0] = null;
		sem.options[1] = null;
		
		variable = new Option("Todo el Año","3");
		sem.options[0] = variable;
		sem.selectedIndex = 0;	
		
	}else
	{
		sem.options[0] = null;
		sem.options[1] = null;
		
		variable = new Option("Todo el Año","3");
		sem.options[0] = variable;
		sem.selectedIndex = 0;
	}
}
</script>
<link href="css/basico.css" rel="stylesheet" type="text/css">
<link href="includes/cssmenus2/skins/viorange/horizontal.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="style.css" />
<script type="text/javascript" src="js/script.js"></script>

<link rel="stylesheet" href="js/SqueezeBox/assets/SqueezeBox.css" type="text/css" />
<script src="js/SqueezeBox/SqueezeBox.js" type="text/javascript"></script>

<script type="text/javascript">

window.addEvent('domready', function() {

 

	/**

	 * That CSS selector will find all <a> elements with the

	 * class boxed

	 *

	 * The example loads the options from the rel attribute

	 */

	SqueezeBox.assign($$('a.modal'), {

		parse: 'rel'

	});

 

});

</script>

</head>
<body id="cuerpo">
<?php
include_once("inc.header.php");
?>
<table align="center" width="<?php echo $ancho_plantilla; ?>" class="centro" cellpadding="10">
<tr>
<th scope="col" colspan="1" class="centro">GRADOS Y GRUPOS </th>
</tr>
<?php
if(isset($_GET['edasg']) && $_GET['edasg'] != '')
{
?>
<tr>
<td>
	<table width="800" border="0" align="center" class="formulario">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <tr>
            <th class="formulario" colspan="3">Edición del grupo   
              <?php
			  
			  //Consulto el año del sistema
			  $sql_year = "SELECT year.b FROM year";
			  $resultado_year = mysql_query($sql_year, $link) or die("No se pudo consultar el año");
			  $row_year = mysql_fetch_assoc($resultado_year);
			  
			  $sql_info_grupo = "SELECT g.i, gao.a as grado_base, gao.b as nom_gra, gao.ba as grado, gao.semestre, guo.b as grupo, jraa.i as id_jornada, jraa.b as jornada, cg_semestre, cg_ano, conse_sede FROM g, gao, guo, jraa, cg WHERE g.b = gao.i AND g.a = guo.i AND gao.g = jraa.i AND g.i = cg.b AND g.i = $_GET[gg]";
			  $resultado_info_grupo = mysql_query($sql_info_grupo, $link) or die("No se pudo consultar los datos del grupo " . $sql_info_grupo);
			  $info_grupo = mysql_fetch_array($resultado_info_grupo);
			  
			 // echo $sql_info_grupo;
			  
			  if($info_grupo['cg_semestre'] == 1)
			  {
			  	$semestre = 'SA ' . $info_grupo['cg_ano'];
			  }
			  else if($info_grupo['cg_semestre'] == 2)
			  {
			  	$semestre = 'SB ' . $info_grupo['cg_ano'];
			  }
			  
			  echo $info_grupo['grado'] . '-' . $info_grupo['grupo'] .' ' . $info_grupo['jornada'] . ' ' . $semestre;
			  ?>              </th>
          </tr>
          <tr bgcolor="#DFEBFF">
            <td width="31%" height="20" align="left" valign="middle"><strong>&nbsp;Ubicaci&oacute;n F&iacute;sica del Sal&oacute;n </strong></td>
            <td width="37%" height="20" align="left" valign="middle"><strong>&nbsp;Director de Grupo </strong></td>
            <td width="32%" height="20" align="left" valign="middle"><strong>&nbsp;Estudiante Monitor de grupo </strong></td>
          </tr>
          <?php
			$sql_gc2 = "select i from cg where b = $_GET[gg]";
			$gc2=mysql_query($sql_gc2,$link);
			$fgc2=mysql_fetch_array($gc2);
			$sql_gcc2 = "select g,u,e from cg where i = $fgc2[0]";
			$gcc2=mysql_query($sql_gcc2,$link) or die ("No se pudo consultar cg " . mysql_error() . ' ' . $sql_gcc2);
			$fgcc2=mysql_fetch_array($gcc2);

			?>
          <tr>
            <?

			  if($fgcc2[0]=='')

			{

			$sty = 'style="background-color:#FFd0d0"';

			}

			else

			{

			$sty = '';

			}

			  ?>
            <td height="38" align="left" valign="middle"><textarea name="ub" cols="25" rows="2" <? echo $sty;?>><? echo $fgcc2[0];?></textarea></td>
            <td valign="top"><?

			echo '<select name="dg" style="font-size:11">

			<option value="0" style="background-color:#FFd0d0">&nbsp;&nbsp;Seleccione un Docente</option>';

		  $cdn = mysql_query("select i, CONCAT(dcne_ape1, ' ', dcne_ape2, ' ', dcne_nom1, ' ', dcne_nom2) as b from dcne order by dcne_ape1, dcne_ape2, dcne_nom1, dcne_nom2",$link);

		  while($fdn=mysql_fetch_array($cdn))

		  {

		  if($fdn[0]==$fgcc2[1])

		{

		$sel = 'selected';

		}

		else

		{

		$sel='';

		}

		  if($fgcc2[1]=='')

			{

			$sty = 'style="background-color:#FFd0d0"';

			}

			else

			{

			$sty = '';

			}

		  echo '<option value="'.$fdn[0].'"	'.$sel.' '.$sty.'>'.$fdn[1].'</option>';

		  }

            echo'</select>';

		  	

			 ?>            </td>
            <td height="38" align="left" valign="top"><?			 

			echo '<select name="mn" style="font-size:11">

			<option value="0" style="background-color:#FFd0d0">&nbsp;&nbsp;Seleccione un Estudiante    </option>';
		  
		  $query_estudiantes = "SELECT matri_id, matri_codigo, alumno_nom1, alumno_nom2, alumno_ape1, alumno_ape2
		  FROM alumno INNER JOIN matricula ON (alumno.alumno_id = matricula.alumno_id) 
		  WHERE grupo_id = $gg AND matri_estado = 0 ORDER BY matri_codigo";
		  $cdn = mysql_query($query_estudiantes,$link) or die(mysql_error());

		  while($fdn=mysql_fetch_array($cdn))

		  {

		  if($fdn['matri_id']==$fgcc2[2])

		{

		$sel = 'selected';

		}

		else

		{

		$sel='';

		}

		  if($fgcc2[2]=='')

			{

			$sty = 'style="background-color:#FFd0d0"';

			}

			else

			{

			$sty = '';

			}

		  echo '<option value="'.$fdn['matri_id'].'"	'.$sel.' '.$sty.'>'.$fdn['matri_codigo'].'&nbsp;&nbsp;&nbsp;'.$fdn['alumno_ape1'].' '.$fdn['alumno_ape2'].' '.$fdn['alumno_nom1'].' '.$fdn['alumno_nom2'].' '.'</option>';

		  }

            echo'</select>';

		  	

			 ?></td>
          </tr>
          <tr bgcolor="#DFEBFF">
            <td width="31%" height="20" align="left" valign="middle"><strong>&nbsp; Tipo de Educaci&oacute;n</strong></td>
            <td width="37%" height="20" align="left" valign="middle"><strong>A&ntilde;o</strong></td>
            <td width="32%" height="20" align="left" valign="middle">&nbsp;<strong>Semestre</strong> </td>
          </tr>
		  <tr>
          <td><?php
			
				//Consultamos los datos del grupo
				$sql_cg_grupo = "SELECT cg_semestre, cg_modalidad, cg_ano, modalidadmedia_id FROM cg WHERE b = $_GET[gg]";
				$resultado_cg_grupo = mysql_query($sql_cg_grupo,$link) or die ("No se pudo consultar los datos del grupo " .mysql_error());
				$cg_grupo = mysql_fetch_array($resultado_cg_grupo);
		
			
				//Consultamos las modalidades de educación
				$sql_modalidades = "SELECT modalidad_id, modalidad_nombre FROM modalidades";
				$resultado_modalidades = mysql_query($sql_modalidades,$link);
				$id = 0;
				$nom = "";
				if($_SESSION['perfil_id'] == 99){
				?>
				  <select name="modalidad">
					<?php				
					while($modalidades = mysql_fetch_array($resultado_modalidades))
					{
						$selected = '';
						
						if($cg_grupo['cg_modalidad'] == $modalidades['modalidad_id'])
						{
							$selected = 'selected="selected"';
						}
					?>
						<option value="<?php echo $modalidades['modalidad_id'] ?>" <?php echo $selected  ?>><?php echo $modalidades['modalidad_nombre'] ?></option>
						<?php
					}
					?>
				  </select>
			  <?php
			  }else{
			  		while($modalidades = mysql_fetch_array($resultado_modalidades))
					{
						
						if($cg_grupo['cg_modalidad'] == $modalidades['modalidad_id'])
						{
							$id = $modalidades['modalidad_id'];
							$nom = $modalidades['modalidad_nombre'];
						}
					}
					echo $nom;
				?>			  	
			  	<input type="hidden" name="modalidad" id="modalidad" value="<?php echo $id;?>" />
			  <?php
			  }
			  ?>			  </td>
            <td>
			<?php
			if($info_grupo['semestre'] == 1 || $info_grupo['semestre'] == 3)
			{
			?>
				<input type="hidden" name="ano" id="ano" value="<?php echo $row_year['b']; ?>">
				<?php echo $row_year['b']; ?>
			<?php
			}
			else
			{
				if($info_grupo['grado_base'] != '25' && $info_grupo['grado_base'] != '26')
				{
			?>
				  <select name="ano" id="ano">
				  <option value="" <?php if (!(strcmp("", $cg_grupo['cg_ano']))) {echo "selected=\"selected\"";} ?>>Seleccione uno...</option>
				  <option value="<?php echo $row_year['b'] - 1; ?>" <?php if (!(strcmp(($row_year['b'] - 1), $cg_grupo['cg_ano']))) {echo "selected=\"selected\"";} ?>><?php echo ($row_year['b'] - 1); ?></option>
				  <option value="<?php echo $row_year['b']; ?>" <?php if (!(strcmp(($row_year['b']), $cg_grupo['cg_ano']))) {echo "selected=\"selected\"";} ?>><?php echo ($row_year['b']); ?></option>
				  </select>
			<?php
				}
				else
				{
			?>
					<input type="hidden" name="ano" id="ano" value="<?php echo $row_year['b']; ?>">
					<?php echo $row_year['b']; ?>
			<?php		
				}
			}
			?>            </td>
			
            <td>
			<select name="semestre" id="semestre">
			<?php
			  if($info_grupo['semestre'] != 2)
			  {
			  		if($info_grupo['grado_base'] != '25' && $info_grupo['grado_base'] != '26')
					{
						$selected = '';
						if($cg_grupo['cg_semestre'] == 3)
						{
							$selected = 'selected="selected"';
						}
			  ?>
 	             		<option value="3" <?php echo $selected?>>Todo el a&ntilde;o</option>
              		<?php
					}
					else
					{
						$selected = '';
						if($cg_grupo['cg_semestre'] == 1)
						{
							$selected = 'selected="selected"';
						}
					?>
              			<option value="1" <?php echo $selected?>>A</option>
              <?php
			  		}
			  	}
				else
				{
						$selected = '';
						if($cg_grupo['cg_semestre'] == 2)
						{
							$selected = 'selected="selected"';
						}
				?>		
					<option value="2" <?php echo $selected?>>B</option>
				<?php	
				}
				?>
              
            </select>		
			</td>
		  </tr>	
		  <tr bgcolor="#DFEBFF">
            <td width="31%" height="20" align="left" valign="middle"><strong>&nbsp; Cambiar Jornada </strong></td>
            <td width="37%" height="20" align="left" valign="middle"><strong>Sede</strong></td>
            <td width="32%" height="20" align="left" valign="middle"><strong>Letra del Grupo </strong></td>
          </tr>
		  <tr>
          <td>
		  <?php
		  	$sql_jornadas = "SELECT jraa.i, jraa.b
			FROM jraa, gao
			WHERE jraa.i = gao.g AND gao.a = (SELECT gao.a FROM gao, g
			WHERE g.b = gao.i AND g.i = $_GET[gg])";
			$resultado_jornadas = mysql_query($sql_jornadas, $link) or die("No se pudieron consultar las jornadas");
			$id_jor = 0;
			$nom_jor = "";
			if($_SESSION['perfil_id'] == 99){ 
		  ?>
		  <select name="nuevaJornada" id="nuevaJornada">
            <option value="">Seleccione una...</option>
            <?php
			while($las_jornadas = mysql_fetch_array($resultado_jornadas))
			{
				$select="";
				if($info_grupo['id_jornada']==$las_jornadas['i']){
					$select="selected='selected'";
				}
			?>
            <option <?php echo $select; ?> value="<?php echo $las_jornadas['i']; ?>"><?php echo $las_jornadas['b']; ?></option>
            <?php
			}
			?>
          </select>
		  <?php
			  }else{
			  		while($las_jornadas = mysql_fetch_array($resultado_jornadas))
					{
						if($info_grupo['id_jornada']==$las_jornadas['i']){
							$id_jor = $las_jornadas['i'];
							$nom_jor = $las_jornadas['b'];
						}
					}	
					
					echo $nom_jor;
				?>			  	
			  	<input type="hidden" name="nuevaJornada" id="nuevaJornada" value="<?php echo $id_jor;?>" />
			  <?php
			  }
			  ?>			</td>
          <td>
		  <?php
			$sql_dane = "SELECT clrp.tt FROM clrp";
			$resultado_dane = mysql_query($sql_dane, $link) or die("No se pudo consultar el dane");
			$dane = mysql_fetch_array($resultado_dane);
			
			$sedes_arr4="1=1";
			if($sedes_arr!=''){
				$sedes_arr4=" sede_consecutivo IN ($sedes_arr) ";
			}
	
			//Mostramos una a una las sedes de la institución	
			$sql_sedes = "SELECT sede_consecutivo, sede_nombre FROM sedes WHERE $sedes_arr4 ORDER BY sede_consecutivo";
			$resultado_sedes = mysql_query($sql_sedes, $link) or die("No se pudo consultar las sedes");
			$num_sedes = mysql_num_rows($resultado_sedes);
						
		  ?>
		  <label>
		  <?php
		  	$id_sede = 0;
			$nom_sede = "";
			if($_SESSION['perfil_id'] == 99){ 
		  ?>
            <select name="conse_sede" id="conse_sede">
			<option value="">Seleccione Sede</option>
			<?php
			while($las_sedes = mysql_fetch_array($resultado_sedes))
			{
				$item = $las_sedes['sede_consecutivo'];
				if($item < 10)
				{
					$item = 0 . $item;
				}
				$item = $dane['tt'] . $item;
				
				$select="";
				if($info_grupo['conse_sede']==$item){
					$select="selected='selected'";
				}
			?>
			<option <?php echo $select; ?>  value="<?php echo $item; ?>"><?php echo $las_sedes['sede_nombre']; ?></option>
			<?php
			}
			?>
            </select>
			<?php
			  }else{
			  		while($las_sedes = mysql_fetch_array($resultado_sedes))
					{
						$item = $las_sedes['sede_consecutivo'];
						if($item < 10)
						{
							$item = 0 . $item;
						}
						$item = $dane['tt'] . $item;
						
						$select="";
						if($info_grupo['conse_sede']==$item){
							$id_sede = $item;
							$nom_sede = $las_sedes['sede_nombre'];
						}
					}	
					
					echo $nom_sede;
				?>			  	
			  	<input type="hidden" name="conse_sede" id="conse_sede" value="<?php echo $id_sede;?>" />
			  <?php
			  }
			  ?>
			
          </label>		  </td>
          <td>
		  <?php
		  	  
						//Consultamos los latras disponibles para los grupos pertenecientes al mismo grado
						$sql_letras = "SELECT guo.i, guo.ba FROM guo
						WHERE guo.i NOT IN (SELECT guo.i
						FROM guo, g, gao
						WHERE guo.i = g.a AND g.b = gao.i AND gao.a IN (SELECT gao.a as 'grado_id'
						FROM g, gao
						WHERE g.i = $_GET[gg] AND g.b = gao.i)) ORDER BY ba";
						$resultado_letras = mysql_query($sql_letras, $link) or die ("No se pudo consultar las letras para los grupos");
						$id_le = 0;
						$nom_le = "";
						if($_SESSION['perfil_id'] == 99){		
						?>
						<select name="nueva_letra" id="nueva_letra">
						<option value="">--</option>
						<?php
									while($letras = mysql_fetch_array($resultado_letras))
									{
									?>
										<option value="<?php echo $letras[i]; ?>"><?php echo $letras[ba]; ?></option>
										<?php
									}
									?>
					  	</select>
						 <?php 
						  }else{
						  				 
			  				  //Consulto el año del sistema
							  $sql_year = "SELECT year.b FROM year";
							  $resultado_year = mysql_query($sql_year, $link) or die("No se pudo consultar el año");
							  $row_year = mysql_fetch_assoc($resultado_year);
							  
							  $sql_info_grupo = "SELECT g.i, gao.a as grado_base, gao.b as nom_gra, gao.ba as grado, gao.semestre, guo.b as grupo, jraa.i as id_jornada, jraa.b as jornada, cg_semestre, cg_ano, conse_sede FROM g, gao, guo, jraa, cg WHERE g.b = gao.i AND g.a = guo.i AND gao.g = jraa.i AND g.i = cg.b AND g.i = $_GET[gg]";
							  $resultado_info_grupo = mysql_query($sql_info_grupo, $link) or die("No se pudo consultar los datos del grupo " . $sql_info_grupo);
							  $info_grupo = mysql_fetch_array($resultado_info_grupo);
							  
							 // echo $sql_info_grupo;
							  
							  if($info_grupo['cg_semestre'] == 1)
							  {
								$semestre = 'SA ' . $info_grupo['cg_ano'];
							  }
							  else if($info_grupo['cg_semestre'] == 2)
							  {
								$semestre = 'SB ' . $info_grupo['cg_ano'];
							  }
							  
						  echo $info_grupo['grupo'];
						  ?>
					  <?php
					  }
					  ?>		
					  
					  </td>
		  </tr>
		  <tr bgcolor="#DFEBFF">
            <td width="31%" height="20" align="left" valign="middle">
			<?php
            if($info_grupo['grado_base']=='10' || $info_grupo['grado_base']=='11')
            {
            ?>
            <strong>&nbsp; Modalidad</strong>
            <?php
			}
			?>           </td>
            <td width="37%" height="20" align="left" valign="middle">			</td>
            <td width="32%" height="20" align="left" valign="middle">&nbsp;</td>
          </tr>
		  <tr>
            <td width="31%" height="20" align="left" valign="middle">
			<?php
            if($info_grupo['grado_base']=='10' || $info_grupo['grado_base']=='11')
            {
				//Consultamos las modalidades de educación
				$sql_modalidades = "SELECT modalidadmedia_id, modalidadmedia_nombre FROM modalidadmedia";
				$resultado_modalidades = mysql_query($sql_modalidades,$link);
				?>
				<select name="modalidadmedia_id">
                <option value="">Seleccione...</option>
				<?php
				while($modalidades = mysql_fetch_array($resultado_modalidades))
				{
					$seleccionado="";
					if($cg_grupo['modalidadmedia_id']==$modalidades['modalidadmedia_id'])
						$seleccionado="selected";
					?>
					<option value="<?php echo $modalidades['modalidadmedia_id'] ?>" <?php echo $seleccionado;  ?>><?php echo $modalidades['modalidadmedia_nombre'] ?></option>
					<?php
				}
				?>
				</select>
				<?php
            }
			 ?>            </td>
            <td width="37%" height="20" align="left" valign="middle">			 </td>
            <td width="32%" height="20" align="left" valign="middle">&nbsp;</td>
          </tr>
		  <tr>
		    <td height="20" colspan="3" align="left" valign="middle">
			
			 <table width="59%" border="1" align="center">
               <tr >
                 <th colspan="3" align="center" class="formulario">Profundización:
				   <?php
			$que_profundi=mysql_query("select * from profundizacion", $link);
			  ?>
			       <select name="profundizacion" id="profundizacion">
			         <option value="">Seleccione uno..</option>
			         <?php
				  while($rows_profun= mysql_fetch_array($que_profundi)){
				  ?>
			         <option value="<?php echo $rows_profun['id'];?>"><?php echo $rows_profun['nombre'];?></option>
			         <?php
				  }
				  ?>
		                 </select>
			       <label>
			 <input <?php if($mensaje==1){?> disabled="disabled" <?php } ?> type="submit" id="mod" name="mod" value="Agregar" />
			       </label></th>
                </tr>
               <tr>
                 <td  align="center" class="fila1"><strong>Num.</strong></td>
                 <td  align="center" class="fila1"><strong>Nombre</strong></td>
                 <td  align="center" class="fila1"><strong>Operaciones</strong></td>
               </tr>
			   <?php
			   $quer_prof_gru=mysql_query("select grado_profundi.id, profundizacion.nombre, grado_profundi.id_profundizacion from grado_profundi 
			   								INNER JOIN profundizacion ON (grado_profundi.id_profundizacion=profundizacion.id)
			   								where id_grupo=$_GET[gg]",$link);
			   $num_rows_pro=mysql_num_rows($quer_prof_gru);
			   $num_reg=1;
			   if($num_rows_pro!=0){
			   ?>
				   
				   <?php
				   while($rows_pro_gra=mysql_fetch_array($quer_prof_gru)){
				   ?><tr>
					 <td align="center"><?php echo $num_reg; $num_reg++;?></td>
					 <td><?php echo $rows_pro_gra['nombre'];?></td>
					 <td align="center">
						 <a href="grados_y_grupos.php?elim_pro=<?php echo $rows_pro_gra['id']; ?>&g=<?php echo $_GET[gg]; ?>&ggr2=<?php echo $_GET[ggr]; ?>&ggp2=<?php echo  $_GET[ggp]; ?>" alt="Eliminar" title="Eliminar" onclick="return confirm('Desea Eliminar la Profundización?');"><img src="images/iconos/icono.gif" alt="Eliminar" width="15" height="15" border="0" align="absmiddle" /></a>
							&nbsp;&nbsp;&nbsp;
						 <a href="add_grupo_profundizacion.php?g=<?php echo $_GET[gg]; ?>&ggr2=<?php echo $_GET[ggr]; ?>&ggp2=<?php echo  $_GET[ggp]; ?>&profun=<?php echo $rows_pro_gra['id_profundizacion']; ?>" class="modal" rel="{handler:'iframe',size:{x:885,y:430}}"><img src="images/iconos/grupo.gif" title="Copiar a los grupos" width="18" height="18" border="0" align="absmiddle" /></a>
							&nbsp;&nbsp;&nbsp;
						 <a href="grados_y_grupos.php?cop=1&niv=<?php echo $info_grupo['grado_base'];?>&profun=<?php echo $rows_pro_gra['id_profundizacion']; ?>&g=<?php echo $_GET[gg]; ?>&ggr2=<?php echo $_GET[ggr]; ?>&ggp2=<?php echo  $_GET[ggp]; ?>" onclick="return confirm('Desea Copiar a Todos los Niveles');"><img src="images/iconos/flechas.gif" title="Copiar a Todos los Niveles" width="18" height="18" border="0" align="absmiddle" /></a>					 </td>
				   </tr>
					<?php
					}
					?>
				   
			   <?php
			   }else{
			   ?>
					<tr>
					 <td colspan="3" align="center"><font color="#FF0000">No se han definido Profundizaciones.</font></td>
					</tr>
				<?php
				}
				?>
             </table>			</td>
		    </tr>
		  <tr>
		  <td colspan="3" style="color:#FF0000; font-weight:bold;" align="center">Al cambiar el grupo de sede se borrará el horario del grupo</td>
		  </tr>
          <tr>
            <td height="38" valign="middle" colspan="3" align="center">
			<input <?php if($mensaje==1){?> disabled="disabled" <?php } ?> name="env4" type="submit" id="env4" value="Editar Grupo">
			<label></label>
			<input type="button" name="volver" value="Volver" onclick="location.href='grados_y_grupos.php'"/>
			<input type="hidden" name="listar" value="1" />
            <input type="hidden" name="g" value="<? echo $_GET[gg];?>" />
            <input type="hidden" name="ggr2" value="<? echo $_GET[ggr];?>" />
            <input type="hidden" name="ggp2" value="<? echo $_GET[ggp];?>" />
			<input type="hidden" name="sede_cmb" value="<? echo $info_grupo['conse_sede'];?>" />
			<input type="hidden" name="jornada_cmb" value="<? echo $info_grupo['id_jornada'];?>" />			</td> 
          </tr>
          </form>
        </table>
</td>
</tr>
<?php
}
else
{
?>
<tr>
<td><form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="grado_grupo" id="grado_grupo" lang="es" xml:lang="es">
  <table width="600" border="0" align="center" class="formulario">
    <tr>
      <th colspan="2" class="formulario">Seleccione los siguientes datos para crear un nuevo grado  </th>
    </tr>
    <tr>
      <td width="203" height="20" align="left" valign="middle"><div align="right"><strong>&nbsp;&nbsp;Jornada:</strong></div></td>
      <td width="387" height="20" align="left" valign="middle">
	  <select name="jo" id="jo">
      <?php
		  $con = mysql_query("select a from cljraa",$link);
		  while($fil=mysql_fetch_array($con))
			{
				$conjj = mysql_query("select * from jraa where i = $fil[0]",$link);
				$filjj=mysql_fetch_array($conjj);

				if($filjj[0]==1)
				{
					echo '';
				}
				else
				{
					echo '<option value="'.$filjj[0].'">'.$filjj[1].'</option>';	
				}
			}
		  ?>
      </select>	  </td>
    </tr>
    <tr>
      <td height="20" align="left" valign="middle"><div align="right"><strong>&nbsp;&nbsp;Grado:</strong></div></td>
      <td height="20" align="left" valign="middle"><script type="text/javascript" language="JavaScript">
								/*function verificaGrado(menu)
								{
									//alert (menu.value);
									var sem = document.getElementById('semestre');
									if(menu.value == 21 || menu.value == 22 || menu.value == 23 || menu.value == 24 || menu.value == 25 || menu.value == 26)
									{
										variable = new Option("A","1");
										variable2 = new Option("B","2");
										
										sem.options[0] = variable;
										sem.options[1] = variable2;
										sem.selectedIndex = 0;
									}
									else
									{
										sem.options[0] = null;
										sem.options[1] = null;
										
										variable = new Option("Todo el Año","3");
										sem.options[0] = variable;
										sem.selectedIndex = 0;
									}
								}*/
							</script>
          <select name="gr" id="gr" onchange="verificaGrado(this)">
            <?php
			
			//Conexi&oacute;n a la base de datos
		  	$link=conectarse();
		  	$sql_grados = "SELECT * FROM grados";
			$resultado_grados = mysql_query($sql_grados,$link) or die ("No se pudo consultar la tabla de grados");
	
			while($grados = mysql_fetch_array($resultado_grados))
			{
				echo '<option value="'.$grados['id_grado'].'">'.$grados['cod_grado'].'&nbsp;&nbsp;&nbsp;'.$grados['nombre_grado'].'</option>';	
			}
			

		  ?>
          </select>      </td>
    </tr>
	<tr style="display:none;" id="tr_gp_nee">
      <td height="20" align="left" valign="middle"><div align="right"><strong>&nbsp;&nbsp;Ciclo: </strong></div></td>
      <td align="left" valign="middle">
			<select name="gp_nee" id="gp_nee">
            <option value="">Seleccione...</option>
			</select>
	  
	  </td>
    </tr>
    <tr id="modmedia" style="display:none;">
      <td height="20" align="left" valign="middle"><div align="right"><strong>&nbsp;&nbsp;Modalidad: </strong></div></td>
      <td align="left" valign="middle">
			<?php
			//Consultamos las modalidades de educación
			$sql_modalidades = "SELECT modalidadmedia_id, modalidadmedia_nombre FROM modalidadmedia";
			$resultado_modalidades = mysql_query($sql_modalidades,$link);
			?>
			<select name="modalidadmedia_id" id="modalidadmedia_id">
            <option value="">Seleccione...</option>
			<?php
			while($modalidades = mysql_fetch_array($resultado_modalidades))
			{
				?>
				<option value="<?php echo $modalidades['modalidadmedia_id'] ?>" <?php echo $selected  ?>><?php echo $modalidades['modalidadmedia_nombre'] ?></option>
				<?php
			}
			?>
			</select>		</td>
    </tr>
    <tr>
      <td height="20" align="left" valign="middle"><div align="right"><strong>&nbsp;&nbsp;Semestre: </strong></div></td>
      <td align="left" valign="middle">
	  <select name="semestre" id="semestre">
          <option value="3" selected="selected">Todo el A&ntilde;o</option>
      </select></td>
    </tr>
    <tr>
      <td height="20" align="left" valign="middle"><div align="right"><strong>&nbsp;&nbsp;N&uacute;mero de grupos:&nbsp;&nbsp;&nbsp; </strong></div></td>
      <td height="20" align="left" valign="middle"><select name="ng" id="ng">
          <?

		  $link=conectarse();

		  // C O M B O     T I P O    D O C U M E N T O ///////////////////////////

		 for($i=1;$i<21;$i++)

		{		

			echo '<option value="'.$i.'">'.$i.'</option>';

			

		}

		  ?>
      </select></td>
    </tr>
    <tr>
      <td height="20" align="left" valign="middle"><div align="right"><strong>&nbsp;&nbsp;Tipo de grupo: </strong></div></td>
      <td align="left" valign="middle"><select name="tg" id="tg">
          <option value="1">Num&eacute;rico</option>
          <option value="2">Alfab&eacute;tico</option>
      </select></td>
    </tr>
   
    <tr>
      <td height="20" align="left" valign="middle"><div align="right"><strong>&nbsp;&nbsp;Profundización: </strong></div></td>
      <td align="left" valign="middle">
	  <?php
	  	$que_profundi2=mysql_query("select * from profundizacion", $link);
	  ?>
	  <select name="profu_grad" id="profu_grad">
          <option value="">Seleccione uno..</option>
		  <?php
		  while($rows_profun2= mysql_fetch_array($que_profundi2)){
		  ?>
		  <option value="<?php echo $rows_profun2['id'];?>"><?php echo $rows_profun2['nombre'];?></option>
		  <?php
		  }
		  ?>
      </select>
	  </td>
    </tr>
	
    <tr>
      <td align="center" colspan="2"><input <?php if($mensaje==1){?> disabled="disabled" <?php } ?> name="crearGrado" type="submit" id="crearGrado" value="Crear Grado" />
	  <input type="button" name="volver" value="Volver" onclick="location.href='<?php echo archivo_padre(); ?>'"/>	  </td>
    </tr>
  </table>
</form></td>
</tr>
<tr>
<tr>
<td>
	
	<form name='filtrarJornada' id='filtrarJornada' action='grados_y_grupos.php' method='post' style='margin: 0px 0px 0px 0px;'>
	<table align="center" width="600" class="formulario" border="0" cellspacing="0">
	<tr><th colspan="6" class="formulario">Filtrar Listado</th></tr>
	<tr>
	<td><div align="right"><strong>Jornada</strong></div></td>
	<td>
	<select name='jornada' id='jornada'>
		  <option value=''>Todas las Jornadas</option>
		  <?php
		  while($jornadas = mysql_fetch_array($resultado_jornadas))
		  {
		  	$selected = '';
			if($jornadas[i] == $_SESSION[jornada])
			{
				$selected = 'selected="selected"';
			}
		  	echo "<option value='$jornadas[i]' $selected>$jornadas[b]</option>";
		  }
		  ?>
	</select>	</td>
	<td><div align="right"><strong>Sede</strong></div></td>
	<td>
		<select name="sede_consecutivo" id="sede_consecutivo">
		<option value="">Todas las Sedes</option>
		<?php if($mensaje==1){?> 
		<option value="" >No se Encontraron Sedes</option>
		 <?php }else{ ?>
		
		
		<?php
		while($la_sede = mysql_fetch_assoc($resultado_sedes))
		{
			$selected = '';
			if($la_sede['sede_consecutivo'] == $_SESSION['sede_consecutivo'])
			{
				$selected = 'selected="selected"';
			}
		?>
			<option value="<?php echo $la_sede['sede_consecutivo']; ?>" <?php echo $selected; ?>><?php echo $la_sede['sede_nombre']; ?></option>
		<?php
		}
		?>
		<?php } ?>
	  	</select>
		
		<?php
		if($num_sedes > 0)
		{
			mysql_data_seek($resultado_sedes,0);
		}
		?>	</td>
	<td>
	  <input name="filtrar" type="submit" id="filtrar" value="Filtrar" /></td>
	<td><input name="quitar_filtro" type="submit" id="quitar_filtro" value="Quitar Filtro" /></td>
	</tr>
	</table>

	</form>
	
	<?php if($mensaje==1){?> 
	<p align="center"><b><font color="#FF3333">No se Encuentran sede y jornada asociadas a su Perfil</font></b></p>
	<?php }else{?>
	<p>&nbsp;</p>
	<!--Estilos Filtro -->
	<div id="tableheader">
        	<div class="search">
                <select id="columns" onChange="sorter.search('query')"></select>
                <input type="text" id="query" onKeyUp="sorter.search('query')" />
            </div>
            <span style="padding:6px; border:1px solid #3399FF; background:#fff" class="details">
				<div>Records <span id="startrecord"></span>-<span id="endrecord"></span> of <span id="totalrecords"></span></div>
        		<div><a href="javascript:sorter.reset()">Limpiar</a></div>
        	</span>        </div>
	<!--Fin-->
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="tinytable" id="table" style="font-size:11px">
<thead> 
	<tr>
    <th class="formulario"><h3><font size="-1">Grado</font></h3></th>
	<th class="formulario">
		 <h3><font size="-1"> Jornada</font></h3>
	</th>
	<?php	
	
		$filtro_sede = '1=1';
		if($_SESSION['sede_consecutivo'] != '')
		{
			$filtro_sede = "sede_consecutivo = $_SESSION[sede_consecutivo]";
		}
		$sedes_arr3="";
		if($sedes_arr!=''){
			$sedes_arr3=" AND sede_consecutivo IN ($sedes_arr) ";
		}
		
		$sql_sedes = "SELECT sede_consecutivo, sede_nombre FROM sedes WHERE $filtro_sede $sedes_arr3 ORDER BY sede_consecutivo";
		$resultado_sedes = mysql_query($sql_sedes, $link) or die("No se pudo consultar las sedes");
		$num_sedes = mysql_num_rows($resultado_sedes);
		
		while($las_sedes = mysql_fetch_array($resultado_sedes))
		{
			$item = $las_sedes['sede_consecutivo'];
			if($item < 10)
			{
				$item = 0 . $item;
			}
		?>
	  <th nowrap="nowrap" title="<?php echo $las_sedes['sede_nombre']; ?>" class="formulario"><h3><font size="-1"><?php echo 'Sede ' . $item; ?></font></h3></th>
		<?php
		}
		?>
		<th class="formulario" nowrap="nowrap"><h3><font size="-1">Sin Sede</font></h3></th>
		</tr>
</thead>
<tbody>
		<?php
        $cont=1;
		$filtro_jornada = '1 = 1';
		if($_SESSION[jornada] != '')
		{
			$filtro_jornada = "g = $_SESSION[jornada]";
		}
$sql_con = "select * from gao WHERE $filtro_jornada order by ba asc";
		$con=mysql_query($sql_con,$link) or die (mysql_error() . ' ' . $sql_con);

		while($fil=mysql_fetch_array($con))
		{
			if($cont%2 ==0)
			{
				$bg= 'class="fila1"';
			}
			else
			{
				$bg= 'class="fila2"';
			}
			$jg=mysql_query("select b from jraa where i = $fil[4]",$link);
		    $fjg=mysql_fetch_array($jg);
			?>	
        	<tr <?php echo $bg; ?>>
          		<td nowrap="nowrap">
			  	<?php
				if($_SESSION['perfil_id'] == 6 || $_SESSION['perfil_id'] == 99)
				{
				?>
					&nbsp;&nbsp;<a href="grados_y_grupos.php?elim=1&id=<?php echo $fil[0]; ?>&listar=1" alt="Eliminar" title="Eliminar" onClick="willSubmit=confirm('Al Eliminar el grado, eliminará toda la información de grupos, director, monitor, ubicación y número de estudiantes');return willSubmit"><img src="images/iconos/icono.gif" alt="Eliminar" width="15" height="15" border="0" align="absmiddle" /></a>
				<?php	
				}

				$mostrar_ano = false;
				$datoSem='';
				if($fil['a'] == 21 || $fil['a'] == 22 || $fil['a'] == 23 || $fil['a'] == 24 || $fil['a'] == 25 || $fil['a'] == 26)
				{
					$datoSem = '&nbsp;S-';
					if($fil['semestre'] == 1)
					{
						$datoSem .= 'A';
					}
					else if($fil['semestre'] == 2)
					{
						$datoSem .= 'B';
					}
					
					if($fil['a'] == 21 || $fil['a'] == 22 || $fil['a'] == 23 || $fil['a'] == 24)
					{
						$mostrar_ano = true;
					}
				}
				?>
				&nbsp;&nbsp;<?php echo $fil[1]; ?>&nbsp;&nbsp;<?php echo $datoSem;//$fil[2]. ?>&nbsp;&nbsp;				</td>
				<td><?php echo $fjg[0]; ?></td>
				<?php
				if($num_sedes)
				{
					mysql_data_seek($resultado_sedes, 0);
				}
				
				while($las_sedes = mysql_fetch_array($resultado_sedes))
				{
					$item = $las_sedes['sede_consecutivo'];
					if($item < 10)
					{
						$item = 0 . $item;
					}
					$item = $dane['tt'] .  $item;
				?>
				<td nowrap="nowrap">
				<?php
					$sql_congg = "select g.a, g.i, cg_ano from g, guo, cg where g.b = $fil[0] AND g.a = guo.i AND g.conse_sede LIKE '$item' AND g.i = cg.b order by guo.ba";
					//echo $sql_congg ;
					$congg=mysql_query($sql_congg,$link) or die (mysql_error());
			
					$nfilgg=mysql_num_rows($congg);
					
					if($nfilgg)
					{
						$con_de_grupos = 0;
						while($filgg=mysql_fetch_array($congg))
						{
							$cguo=mysql_query("select b, i from guo where i = $filgg[0]",$link) or die ("No se pudo consultar los grupo");
							$fguo=mysql_fetch_array($cguo);
				
							  if($_SESSION['perfil_id'] == 6 || $_SESSION['perfil_id'] == 99 || $_SESSION['perfil_id'] == 5)
							  {
							  ?>  
								  &nbsp;<a href="grados_y_grupos.php?gg=<?php echo $filgg[1]; ?>&ggr=<?php echo $fil[1]; ?>&ggp=<?php echo $fguo[0]; ?>&eeellas=1" alt="Eliminar" title="Eliminar" onclick="willSubmit=confirm('Al Eliminar el grupo, eliminar&aacute; toda la informaci&oacute;n como: director, monitor, ubicaci&oacute;n y n&uacute;mero de estudiantes');return willSubmit"><img src="images/iconos/icono.gif" alt="Eliminar" width="15" height="15" border="0" align="absmiddle" /></a><a href="grados_y_grupos.php?gg=<?php echo $filgg[1]; ?>&ggr=<?php echo $fil[1]; ?>&ggp=<?php echo $fguo[0]; ?>&eeellas=1&listar=1"></a>
							  <?php	  
							  }
							///////////////////////////////////////////////////////////////////////////////////////////////
							
							//Determino si se va a mostrar el año en que inicio el grupo
							$dato_year = '';
							
							if($mostrar_ano && $filgg['cg_ano'] != 0)
							{
								$dato_year =  ' \''.substr($filgg['cg_ano'],2,2);
							}
							?>
							&nbsp;<a href="grados_y_grupos.php?gg=<?php echo $filgg[1]; ?>&ggr=<?php echo $fil[1]; ?>&ggp=<?php echo $fguo[0]; ?>&edasg=1" class="clarito"><?php echo $fil[1].$fguo[0].$dato_year; ?></a>&nbsp;&nbsp;&nbsp;
							<?php
							$con_de_grupos++;
							if($con_de_grupos %2 == 0)
							{
								echo '<br />';
							}
						}
					}
					else
					{
						$fguo[1] = 0;
					}
					
					if($fguo[1] == 0)
					{
						echo '&nbsp;';
					}
					?>			  </td>
				<?php
				}
		?>
			<td align="left" nowrap="nowrap">
			<?php
				$sql_congg = "select g.a, g.i, cg_ano from g, guo, cg where g.b = $fil[0] AND g.a = guo.i AND (g.conse_sede NOT IN ($los_items) OR g.conse_sede IS NULL) and g.i = cg.b order by guo.ba";
				$congg=mysql_query($sql_congg,$link) or die (mysql_error());
	
				$nfilgg=mysql_num_rows($congg);
				
				if($nfilgg)
				{
					$con_de_grupos = 0;
					while($filgg=mysql_fetch_array($congg))
					{
						$cguo=mysql_query("select b, i from guo where i = $filgg[0]",$link) or die ("No se pudo consultar los grupo");
						$fguo=mysql_fetch_array($cguo);

					if($_SESSION['perfil_id'] == 6 || $_SESSION['perfil_id'] == 99)
					{
					  ?>
					  	&nbsp;<a href="grados_y_grupos.php?gg=<?php echo $filgg[1]; ?>&ggr=<?php echo $fil[1]; ?>&ggp=<?php echo $fguo[0]; ?>&eeellas=1" alt="Eliminar" title="Eliminar" onclick="willSubmit=confirm('Al Eliminar el grupo, eliminar&aacute; toda la informaci&oacute;n como: director, monitor, ubicaci&oacute;n y n&uacute;mero de estudiantes');return willSubmit"><img src="images/iconos/icono.gif" alt="Eliminar" width="15" height="15" border="0" align="absmiddle" /></a>
						<?php
					  }
					  	//Determino si se va a mostrar el año en que inicio el grupo
						$dato_year = '';
							
						if($mostrar_ano && $filgg['cg_ano'] != 0)
						{
							$dato_year =  ' \''.substr($filgg['cg_ano'],2,2);
						}
					  ?>				
						&nbsp;<a href="grados_y_grupos.php?gg=<?php echo $filgg[1]; ?>&ggr=<?php echo $fil[1]; ?>&ggp=<?php echo $fguo[0]; ?>&edasg=1" class="clarito"><?php echo $fil[1].$fguo[0].$dato_year; ?></a>&nbsp;&nbsp;&nbsp;
					  <?php
					  	$con_de_grupos++;
						if($con_de_grupos %2 == 0)
						{
							echo '<br />';
						}
					}
				}
				else
				{
					$fguo[1] = 0;
				}
				
				
				//Consultamos el siguiente grupo
				$sql_letras_libres = "SELECT guo.i FROM guo WHERE guo.i NOT IN 
				(SElECT g.a FROM g WHERE g.b IN 
				  (SELECT gao.i FROM gao WHERE gao.a = 
				   (SELECT gao.a FROM gao WHERE gao.i = $fil[0])
				  )
				) 
				AND guo.i > 
				(SElECT MAX(g.a) FROM g WHERE g.b IN 
				 (SELECT gao.i FROM gao WHERE gao.a = 
				  (SELECT gao.a FROM gao WHERE gao.i = $fil[0])
				 )
				)
				ORDER BY guo.i";
				$resultado_letras_libres = mysql_query($sql_letras_libres, $link) or die("No se pudo consultar las letras " . $sql_letras_libres);
				$letras_libres = mysql_fetch_array($resultado_letras_libres);
				//echo $sql_letras_libres;
				$sig_grupo = $letras_libres['i'];
				if($sig_grupo == '')
				{
					$sig_grupo = 11;
				}
				?>
				<div>&nbsp;<a href="grados_y_grupos.php?jo=<?php echo $fil[4]; ?>&nngg=<?php echo $nfilgg; ?>&ggr=<?php echo $fil[0]; ?>&crear=1&listar=1&sig_grupo=<?php echo $sig_grupo; ?>" class="clarito">Nuevo Grupo</a></div>
			</td>
			</tr>
		<?php
		$cont++;
		}
      	?>
		<tbody>
	  </table>
	  
	  
	  <br/>
	  <table width="80%" border="0" align="center" bgcolor="#FFFFFF" style="padding:6px; border:1px solid #3399FF; background:#fff">
	  <tr>
	  <td valign="top">
		 <div id="tablefooter">
          <div id="tablenav">
            	<div>
                    <img src="images/first.gif" width="16" height="16" alt="First Page" onClick="sorter.move(-1,true)" />
                    <img src="images/previous.gif" width="16" height="16" alt="First Page" onClick="sorter.move(-1)" />
                    <img src="images/next.gif" width="16" height="16" alt="First Page" onClick="sorter.move(1)" />
                    <img src="images/last.gif" width="16" height="16" alt="Last Page" onClick="sorter.move(1,true)" />                </div>
                <div>
                	<select id="pagedropdown"></select>
				</div>
                <div>
                	<a href="javascript:sorter.showall()">Ver Todas</a>                </div>
          </div>
			<div id="tablelocation">
            	<div class="page">Pagina <span id="currentpage"></span> de <span id="totalpages"></span>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
				
				<div>
                    <select onChange="sorter.size(this.value)">
                    <option value="5">5</option>
                        <option value="10" >10</option>
                        <option value="20" selected="selected">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span>Entradas por página</span>                </div>
                
            </div>
        </div><br />
   </td></tr></table>
	<script type="text/javascript" src="js/script.js"></script>
	<script type="text/javascript">
	var sorter = new TINY.table.sorter('sorter','table',{
		headclass:'head',
		ascclass:'asc',
		descclass:'desc',
		evenclass:'evenrow',
		oddclass:'oddrow',
		evenselclass:'evenselected',
		oddselclass:'oddselected',
		paginate:true,
		size:20,
		colddid:'columns',
		currentid:'currentpage',
		totalid:'totalpages',
		startingrecid:'startrecord',
		endingrecid:'endrecord',
		totalrecid:'totalrecords',
		hoverid:'selectedrow',
		pageddid:'pagedropdown',
		navid:'tablenav',
		sortcolumn:0,
		sortdir:1,
		/*sum:[8],
		avg:[6,7,8,9],
		columns:[{index:7, format:'%', decimals:1},{index:8, format:'$', decimals:0}],*/
		init:true
	});
  </script>
  
  
	  	<br>
	<!--  	<div align="center"><a href="ajuste_g_cg_y_otros.php" title="Ajustar Datos de (g, cg y otros)">Ajustar datos de los grupos</a></div>-->
		<?php }?>
</td>
</tr>

<th class="footer">
<?php
include_once("inc.footer.php");
?>
</th>
</tr>
<?php
}
?>
</table>

</body>
</html>