
<?php

		include_once("Connections/sygescol_ano_anterior.php");
		$roles_con_permiso = array('99', '6', '1', '2', '5', '8', '13', '3');
		include_once("inc.configuracion.php");
		include_once("inc.validasesion.php");
		include_once("inc.funciones.php");
		include_once("conexion.php");
		include_once("includes/clases/Periodo.php");
		include_once("includes/clases/Asignatura_Nota_1.php");
		include_once("includes/clases/Area_Nota_1.php");
		require_once('includes/clases/Sygescol_Varios.php');
		require_once('includes/clases/Escala_Valorativa.php');
		include_once('inc.funciones.php');
		include_once('mensajes_sygescol.php');
		
		$link = conectarse();
		mysql_select_db($database_sygescol,$link);
?>
<html>
<head>
</head>
<script type="text/javascript">

function validaFormulario(formulario)
{
	if(formulario.edad.value == '')
	{
		alert("Por Favor Ingrese La Fecha de Nacimiento del Estudiante");
		formulario.edad.focus();
		return false;
	}

}
</script>

<?php

if(isset($_POST['Verificar']))
{
	$FechaNacimiento = calcula_edad($_POST['edad']);

	if(!empty($_POST['grado']))
	{
		$sql_conf_sygescol = "SELECT * FROM conf_sygescol WHERE conf_id = 255";

		$sel_conf_sygescol = mysql_query($sql_conf_sygescol,$sygescol);

		$Array_conf_sygescol = mysql_fetch_array($sel_conf_sygescol);

		$valor_param=explode('$', $Array_conf_sygescol['conf_valor']);

		if($_POST['grado'] == 1)
		{
			$valor_edad = explode(',', $valor_param[1]);

				if($FechaNacimiento < $valor_edad[2])
				{
					echo "<script>location.href='formulario_inscripcion.php?cupo_acc_id=$_POST[numero]'</script>";
				}
				else
				{
					echo "<script>location.href='formulario_validar_fecha.php?cupo_acc_id=$_POST[numero]&grado_base=$_POST[grado]&fail=error'</script>";
				}
		}
		else if($_POST['grado'] == 2)
		{

			$valor_edad = explode(',', $valor_param[2]);

				if($FechaNacimiento < $valor_edad[3])
				{
					echo "<script>location.href='formulario_inscripcion.php?cupo_acc_id=$_POST[numero]'</script>";
				}
				else
				{
					echo "<script>location.href='formulario_validar_fecha.php?cupo_acc_id=$_POST[numero]&grado_base=$_POST[grado]&fail=error'</script>";
				}

		}
		else  if($_POST['grado'] == 3)
		{
			$valor_edad = explode(',', $valor_param[3]);

				if($FechaNacimiento < $valor_edad[4])
				{
					echo "<script>location.href='formulario_inscripcion.php?cupo_acc_id=$_POST[numero]'</script>";
				}
				else
				{
					echo "<script>location.href='formulario_validar_fecha.php?cupo_acc_id=$_POST[numero]&grado_base=$_POST[grado]&fail=error'</script>";
				}
				

		}
		else if($_POST['grado'] == 4)
		{
			$valor_edad = explode(',', $valor_param[4]);

				if($FechaNacimiento < $valor_edad[5])
				{
					echo "<script>location.href='formulario_inscripcion.php?cupo_acc_id=$_POST[numero]'</script>";
				}
				else
				{
					echo "<script>location.href='formulario_validar_fecha.php?cupo_acc_id=$_POST[numero]&grado_base=$_POST[grado]&fail=error'</script>";
				}
		}
		else
		{
			$valor_edad = explode(',', $valor_param[5]);

				if($FechaNacimiento < $valor_edad[6])
				{
					echo "<script>location.href='formulario_inscripcion.php?cupo_acc_id=$_POST[numero]'</script>";
				}
				else
				{
					echo "<script>location.href='formulario_validar_fecha.php?cupo_acc_id=$_POST[numero]&grado_base=$_POST[grado]&fail=error'</script>";
				}
		}

	}
	else
	{
		// $Sql = "SELECT * FROM cupos_acceso WHERE cupos_acceso_id = $_POST[numero]";
		// $sel = mysql_query($Sql,$link);

			if($FechaNacimiento >= 5)
			{
				echo "<script>location.href='formulario_inscripcion.php?cupo_acc_id=$_POST[numero]'</script>";
			}
			else
			{
				echo "<script>location.href='formulario_validar_fecha.php?cupo_acc_id=$_POST[numero]&fail=error'</script>";
			}
	}

}

?>
<body>
<div id="ValidarForm">
<table align="center">
<form onsubmit="return validaFormulario(this)" method="post" action="formulario_validar_fecha.php">
<tr>
<th>
INGRESE LA FECHA DE NACIMIENTO DEL ESTUDIANTE
</th>
</tr>
<tr>
<td align="center"><input type="date" name="edad"  id="edad"  /></td>
</tr>
<tr>
<td><input type="hidden" name="numero" id="numero" value="<?php echo $_GET[cupo_acc_id]; ?>" /></td>
<td><input type="hidden" name="grado" id="grado" value="<?php echo $_GET[grado_base]; ?>" /></td>
</tr>
<tr>
<td align="center"><input type="submit" value="VERIFICAR" name="Verificar" id="Verificar"></td>
</tr>
</form>
</table>
</div>
<div id="header" >
<img src="header.PNG" title="SYGESCOL" class="footer" width="100%" height="22%">
</div>
<div id="imgcolegio">
<img src="images/escudo.gif" width="100%" height="100%">
</div>
<div id="sygescol">
<img src="images/ivhorsnet.gif" width="100%" height="100%">
</div>
<div id="footer">
<img src="footer.png" title="SYGESCOL" class="footer" width="100%">
</div>
</body>
</html>
<?php
if($_GET['fail'] == 'error')
{
	MostrarMensaje();
}
?>

