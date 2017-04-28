<?php
class ParametrosGenerales
{
	// private $localhost='localhost';
	// private $usuario='ietsagra_familia';
	// private $contraseña='sagrada2009';
	// private $bd='ietsagra_sygescol2017';
	private $consecutivo;
	private $arrayParametros= array();
	private $conexion;

	public function creaConexion()
 	{
 		// $conexion=mysqli_connect($this->localhost , $this->usuario , $this->contraseña , $this->bd)or die('Error al Conectar con la Base de Datos');
 		include("conb.php");
 		$this->conexion=$conexion;
 		return $conexion;
 	}

 	public function ejecutarImpresionParametros($persiana,$conexion,$consecutivoNuevo,$id)
 	{
 		// echo '<script>alert("entro a IMPRIMIR")</script>';
 		$consultaImprimirDatos='select conf_id,conf_nom_ver,conf_descri from conf_sygescol as conf join encabezado_parametros as enca on enca.id_param=conf.conf_id where conf_id='.$id.' and categoriat='.$persiana." ";
 		$resultadoQuery=mysqli_query($conexion,$consultaImprimirDatos)or die("Error al Consultar los datos del parametros nuevo".mysqli_error());
 		$cantDatosTraidos=mysqli_num_rows($resultadoQuery);
 		$this->consecutivo=$consecutivoNuevo;
 		if($cantDatosTraidos>=1)
 		{
 			while($informacionParametro=mysqli_fetch_array($resultadoQuery))
 			{
 				$this->consecutivo++;

 		?>
 		<tr>
 <td class="formulario"   id="Consecutivo<?php echo $this->consecutivo; ?>" ><strong> <?php echo $this->consecutivo; ?></strong></td>
<td class="formulario"  id="Parametro<?php echo $informacionParametro['conf_id']; ?>" ><strong><?php echo $informacionParametro['conf_id'] ?></strong></td>
<td valign="top"><strong>
<div class="container_demohrvszv_caja_tipo_param">
<div class="accordion_example2wqzx">
<div class="accordion_inwerds">
<div class="acc_headerfgd"><strong>Tipo de Par&aacute;metro</strong></div>
<div class="acc_contentsaponk">
<div class="grevdaiolxx_caja_tipo_param">
<div class="sin_resaltado">
<div id="conf_nom_ver">
<div  class="textarea"align="justify" id="conf_nom_ver-<?php echo $informacionParametro['conf_id'] ?>"><?php echo $informacionParametro['conf_nom_ver']; ?></div></div>
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
      <div  align="justify" class="textarea " id="conf_descri-<?php echo $informacionParametro['conf_id'] ?>" style="width: 90%;" align="justify">
      <?php echo $informacionParametro['conf_descri']; ?>
      </div>
		</div>			</div>
				</div>
			</div>
		</div>
</div>
 </td>
         				
 		<?php      
 	        }
 		}
 	}

 	public function returnConsecutivo()
 	{	
 		return $this->consecutivo;
 	}

 	

    public function devuelvaParametros($posicion)
    {
    	return $this->arrayParametros[$posicion];
    }

 	public function returnCantidadParametrosPersiana($conexion,$persianaNueva)
    {
    	$consultaCantidadParametrosPersiana='select conf_id,conf_nom_ver,conf_descri from conf_sygescol  join encabezado_parametros on id_param=conf_id where caracteristica="NUEVA" and categoriat='.$persianaNueva;
 		$resultadoQuery1=mysqli_query($conexion,$consultaCantidadParametrosPersiana)or die("Error al Consultar los datos del parametros nuevo".mysqli_error() );
 		$cantParametros=mysqli_num_rows($resultadoQuery1);
 		///alamacena en un array los parametros que hay dentro de la persiana
 		$cont=0;
 		while($datosParametros=mysqli_fetch_array($resultadoQuery1))
 		{
 			$this->arrayParametros[$cont]=$datosParametros['conf_id'];
 			$cont++;
 		}
 		return $cantParametros;
    }

 	public function informacionParametro($conexion,$id)
 	{
 		$consultaImprimirDatos='select * from conf_sygescol as conf join encabezado_parametros as enca on enca.id_param=conf.conf_id where conf_id='.$id;
 		$valoresParametro=mysqli_query($conexion,$consultaImprimirDatos);
 		$valoresParametro=mysqli_fetch_array($valoresParametro);
 		return $valoresParametro;
 	}

 	public function ejecutarConsulta($sql_consulta)
 	{
 		// $consulta=mysqli_query($this->conexion,$sql_consulta);
 		//return $consulta;
 	}


}

?>