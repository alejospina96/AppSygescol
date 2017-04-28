var url_pagina = "http://www.sistemasivhorsnet.com/ModeloMaster/";
function volver()
{
    window.location.href = "sygescol_online.html";
}
function function_periodo(per_id,funcion)
{
    if (funcion == 1) 
    {
        carga_consolidado(per_id);
    }
}
function cargar_estudiantes_varios(grupo_id,funcion)
{

    carga_estudiantes_grupo(grupo_id,funcion);

}
function cargar_estudiantes_function(matri_id,funcion)
{
    if (funcion == 1) 
    {
        carga_citacion(matri_id);
    }
    if (funcion == 2) 
    {
        carga_acudientes(matri_id);
    }
    if (funcion == 3) 
    {
        carga_citaciones_hechas(matri_id);
    }
    if (funcion == 4) 
    {
        carga_interfaz_observador(matri_id);
    }
    if (funcion == 5) 
    {
        carga_consulta_observador(matri_id);
    }
}
function consulta_estudiante()
{
    $(".contenedor").css("display","");
    $("#carga").css("display","none");
    $(".contenedor").css("margin","0");
    $(".contenedor").attr("id","consulta_estudiante");
    $("#consulta_estudiante").html("<br><br><br><br><center><h2>Consulta de Estudiantes<br></h2><br><br><br><div class='select-style'><select id='tipo_busqueda_estudiante' name='tipo_busqueda_estudiante' onchange='estudiantes(this.value);'><option value=''>...</option><option value='1'>Por Grupo</option><option value='2'>Por Estudiante</option></select></div><br><br><br><br><div id='container_estudiante'></div></center>");
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
    $(".perspective").removeClass("modalview");
}
function estudiantes(consulta)
{
    if (consulta == 1) 
    {
        jQuery.ajax({
            method: "POST",
            url: url_pagina+"reconocimiento_pae/php/consultas.php",
            data: "carga_grupo=1&usuario="+localStorage.getItem("username")+"&rol="+localStorage.getItem("rol"),
            beforeSend: function(){
                $("#carga").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
            },
            success: function(a){
                $("#carga").html('');
                $("#container_estudiante").html(a);
            },
            error: function(a){
                $('#container_estudiante').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
            }

        })
    }
    else if (consulta == 2)
    {
        $("#consulta_estudiante").html("<div><form action='' onsubmit='carga_estudiante_individual(); return false;'><br><br><center><table><tr><td><h2>Consulta Individual de Estudiantes</h2></td></tr></center><tr><td><center><input type='text' placeholder='Primer Apellido' class='username' id='primer_apellido' onkeypress='return soloLetras(event)' ></center></td></tr><tr><td><center><input type='text' placeholder='Segundo Apellido' class='username' id='segundo_apellido' onkeypress='return soloLetras(event)'></center><tr><td><center><input type='text' placeholder='Primer Nombre' class='username' id='primer_nombre' onkeypress='return soloLetras(event)'></center></td></tr></td></tr><tr><td><center><input type='text' placeholder='Segundo Nombre' class='username' id='segundo_nombre' onkeypress='return soloLetras(event)'></center></td></tr><tr><td><center><input type='text' placeholder='Documento de Identidad' class='username' id='documento_identidad' onkeypress='return justNumbers(event);'></center></td></tr></table><center><br><br><button type='submit' name='guardar' class='boton'>Consultar</button></center><center></form></div>");
    }
}
function mostrar_estudiantes(id_grupo)
{
    jQuery.ajax({
        method: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "id_grupo="+id_grupo,
        beforeSend: function(){
            $(".contenedor").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
        },
        success: function(a){
            $(".contenedor").html(a);
            swiper();
        },
        error: function(a){
            $('.contenedor').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }

    })
}
function carga_estudiante_individual()
{
    if ($("#primer_apellido").val() == "" && $("#segundo_apellido").val() == "" && $("#primer_nombre").val() == "" && $("#segundo_nombre").val() == "" && $("#documento_identidad").val() == "") 
    {
        $("#carga").css("display","");
        $("#carga").html("<center><br><img src='"+url_pagina+"reconocimiento_pae/assets/img/info.png'><br><h3>Debe llenar por lo menos un campo</h3></center>");
    }
    else
    {

        $("#carga").css("display","none");
        $(".contenedor").css("margin","0");
        $(".contenedor").attr("id","fin_consulta");
        $("#fin_consulta").css("display","");
        jQuery.ajax({
            type: "POST",
            url: url_pagina+"reconocimiento_pae/php/consultas.php",
            data: "consulta_total=1&usuario="+localStorage.getItem("username")+"&apellido1="+$("#primer_apellido").val()+"&apellido2="+$("#segundo_apellido").val()+"&nombre1="+$("#primer_nombre").val()+"&nombre2="+$("#segundo_nombre").val()+"&numero_documento="+$("#documento_identidad").val(),
            beforeSend: function(){
                $("#fin_consulta").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
            },
            success: function(a){
                $("#fin_consulta").html(a);
                swiper();
            },
            error: function(a){
                $('#fin_consulta').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
            }
        })
    }

}
function swiper()
{
    var swiper = new Swiper('.swiper-container', {
        paginationClickable: true
    });
}
function vigencia_periodos()
{

    $("#carga").css("display","none");
    $(".contenedor").css("display","");
    $(".contenedor").attr("id","vigencia_periodos");
    $("#vigencia_periodos").html("<br><br><br><br><center><h2>Vigencia de Periodos<br></h2><br><br><br><div id='container_niveles'></div>");
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
    carga_niveles();
}
function carga_niveles()
{
    jQuery.ajax({

        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "carga_niveles=1&usuario="+localStorage.getItem("username")+"&rol="+localStorage.getItem("rol"),

        beforeSend: function(){
            $("#container_niveles").html('<img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"/><br><p class="tmf">Cargando Información</p>');
        },
        success: function(a){
            $("#container_niveles").html(a);
        },
        error: function(a){
            $('#container_niveles').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}
function carga_vigencia(valor)
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "id_nivel="+valor,
        beforeSend: function(){
            $("#container_niveles").html('<img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"/><br><p class="tmf">Cargando Información</p>');
        },
        success: function(a){
            $("#container_niveles").html(a);
            swiper();
        },
        error: function(a){
            $('#container_niveles').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}
function director_grupo()
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "director_grupo="+localStorage.getItem("username"),
        beforeSend: function(){
            $(".contenedor").html('<br><center><p class="tmf">Cargando Información</p></center>');
        },
        success: function(a){
            if (a == 1)
            {
                $("#planilla_comportamiento").css("display","");
            }
            else
            {
                $("#planilla_comportamiento").css("display","none");
            }
        },
        error: function(a){
            $('#planilla_comportamiento').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}

function planilla_pgu()
{
    $("#carga").css("display","none");
    $(".contenedor").css("display","");
    $(".contenedor").attr("id","planilla_pgu");
    $("#planilla_pgu").html("<br><br><br><br><center><h2>Planilla P.G.U<br></h2><br><center>(para poder visualizar la P.G.U, debe tener instalado un lector PDF en su Dispositivo Móvil)</center><br><br><br><div id='container_pgu'></div><br><br><br><div id='asignaturas'></div><br><br><br><div id='periodos'></div><br><br><br><div id='boton'></div></center>");
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");

    jQuery.ajax({

        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "docente_pgu="+localStorage.getItem("username"),

        beforeSend: function(){
            $("#container_pgu").html('<br><center><p class="tmf">Cargando Información</p></center>');
        },
        success: function(a){
            $("#container_pgu").html(a);

        },
        error: function(a){
            $('#container_pgu').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}


function carga_periodos(cga_id,grupo_id)
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "carga_periodos=1&grupo_id="+grupo_id+"&cga_id="+cga_id+"&usuario="+localStorage.getItem("username"),
        beforeSend: function(){
            $("#periodos").attr("class","");
            $("#periodos").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
        },
        success: function(a){
            if (a != "") 
            {
                $("#periodos").attr("class","select-style");
                $("#periodos").html(a);
            }
            else
            {
                $("#periodos").attr("class","");
                $("#periodos").html("");
            }
        },
        error: function(a){
            $('#periodos').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}
function carga_asignaturas(grupo_id)
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "carga_asig_grupos=1&grupo_id="+grupo_id+"&docente="+localStorage.getItem("username"),
        beforeSend: function(){
            $("#asignaturas").attr("class","");
            $("#asignaturas").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Asignaturas</p></center>');
        },
        success: function(a){
            if (a != "") 
            {
                $("#asignaturas").attr("class","select-style");
                $("#asignaturas").html(a);
            }
            else
            {
                $("#asignaturas").attr("class","");
                $("#asignaturas").html("");
            }
        },
        error: function(a){
            $('#asignaturas').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}

function mostrar_planilla(per_id,grupo_id,cga_id)
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"planilla_actual_pdf.php?per_id="+per_id+"&grupo_id="+grupo_id+"&cga_id="+cga_id+"&ver_planilla=1&app=1",
        data: "docente="+localStorage.getItem("username"),
        beforeSend: function(){
            $("#boton").attr("class","");
            $("#boton").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
        },
        success: function(a){
            $("#boton").html("<a href='"+url_pagina+"PGU/"+a+"'><button class='boton'>Descargar Archivo </button></a>");
        },
        error: function(a){
            $('#boton').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}

function planilla_comportamiento()
{
    $("#carga").css("display","none");
    $(".contenedor").css("display","");
    $(".contenedor").attr("id","planilla_comportamiento");
    $("#planilla_comportamiento").html("<br><br><br><br><center><h2>Planilla de Comportamiento<br></h2><br><br><br><div id='select_comportamiento'></div><br><br><div id='per_comportamiento'></div>");
    select_comportamiento();
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
}
function select_comportamiento()
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "grupos_director="+localStorage.getItem("username"),
        beforeSend: function(){
            $("#select_comportamiento").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Grupos</p></center>');
        },
        success: function(a){
            $("#select_comportamiento").attr("class","select-style");
            $("#select_comportamiento").html(a);
        },
        error: function(a){
            $('#select_comportamiento').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}
function carga_periodo_comport(grupo_id)
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "carga_periodo_comport="+grupo_id,
        beforeSend: function(){
            $("#per_comportamiento").attr("class","");
            $("#per_comportamiento").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
        },
        success: function(a){
            if (a != "") 
            {
                $("#per_comportamiento").attr("class","select-style");
                $("#per_comportamiento").html(a);
            }
            else
            {
                $("#per_comportamiento").attr("class","");
                $("#per_comportamiento").html("");
            }
        },
        error: function(a){
            $('#per_comportamiento').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}
function carga_planilla_comport()
{
    $(".contenedor").attr("id","carga_planilla_comport");
    var grupo_id = $("#grupos").val();
    var per_id = $("#periodo_id_comport").val();

    $("#carga_planilla_comport").html("<br><br><br><br><center><div id='carga_estudiantes_comport'></div></center>");
    carga_estudiantes_comport(grupo_id,per_id);
}
function carga_estudiantes_comport(grupo_id,per_id)
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "grupo_id="+grupo_id+"&per_id="+per_id+"&ver_comportamiento=1",
        beforeSend: function(){
            $("#carga_estudiantes_comport").html('<center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
        },
        success: function(a){
            $("#carga_estudiantes_comport").html(a);
            swiper();
        },
        error: function(a){
            $('#carga_estudiantes_comport').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })

}
function borrar_placeholder(matri_id)
{
    var nombre_caja = '#observacion_'+matri_id;
    if ($(nombre_caja).html() == "Escribe la observacion aqui") 
    {
        $(nombre_caja).html("");
        $(nombre_caja).focus();
    }
}

function borrar_otros_placeholder(nombre_caja)
{
    var nombre_caja = '#'+nombre_caja.id;
    if ($(nombre_caja).html() == "Motivo" || $(nombre_caja).html() == "Preguntar Por" || $(nombre_caja).html() == "Lugar de Citación" || $(nombre_caja).html() == "Hechos" || $(nombre_caja).html() == "Descargos" || $(nombre_caja).html() == "Orientación" || $(nombre_caja).html() == "Felicitaciones") 
    {
        $(nombre_caja).html("");
        $(nombre_caja).focus();
    }
}
function cambiar_comport(matri_id)
{
    $("#tr_comport_"+matri_id).css("display","");
    var checkbox = '#guardar_'+matri_id;
    var observacion = decodeURIComponent($('#observacion_'+matri_id).html());
    var desempenno = $('#escala_nacional_'+matri_id).val();
    var per_id = $("#id_periodo").val();
    var buscar=" "
    var obs_final = observacion.replace(new RegExp(buscar,"g") ,"_");
    var datos = "guardar_comportamiento=1&usu_login="+localStorage.getItem("foto_final_conductor")+"&desempenno="+desempenno+"&periodo="+per_id+"&matri_id="+matri_id+"&observacion="+obs_final;


    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: datos,
        dataType: "HTML",
        beforeSend: function(){

            $("#guarda_comport_"+matri_id).html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/progress2.gif" alt="logo sygescol" width="25%"><p class="tmf">Guardando</p></center>');
        },
        success: function(a){
            if (a != 1) 
            {
              $("#guarda_comport_"+matri_id).html('<img src="'+url_pagina+'reconocimiento_pae/assets/img/success.png" width="10%"><br><h1 class="titulod">Datos guardados correctamente</h1>');  
          }
          else
          {
            $("#guarda_comport_"+matri_id).html('<img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png" width="10%"><br><h1 class="titulod">Los datos no se guardaron</h1>'); 
        }
    },
    error: function(a){
        $('#guarda_comport_'+matri_id).html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="10%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
    }
})
// }
}

function trabajo_en_linea()
{
    $("#carga").css("display","none");
    $(".contenedor").css("display","");
    $(".contenedor").attr("id","trabajo_en_linea");
    $("#trabajo_en_linea").html("trabajo_en_linea");
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
}
function consolidado_acumulado()
{
    $("#carga").css("display","none");
    $(".contenedor").css("display","");
    $(".contenedor").attr("id","consolidado_acumulado");
    $("#consolidado_acumulado").html("<br><br><br><br><center><h2>Consolidado Acumulado<br></h2><br><center>(para poder visualizar el consolidado, debe tener instalado un lector PDF en su Dispositivo Móvil)</center><br><br><br><div class='select-style'><select id='tipo_consolidado' onchange='mostrar_grupos()'><option value='0'>...</option><option value='1'>Por Asignaturas</option><option value='2'>Por Áreas</option></select></div><br><br><br><div id='grupos'></div><br><br><br><div id='periodos'></div><br><br><br><div id='boton'></div></center>");
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
}
function mostrar_grupos()
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "docente_consolidado="+localStorage.getItem("username"),
        beforeSend: function(){
            $("#grupos").html('<br><center><p class="tmf">Cargando Información</p></center>');
        },
        success: function(a){
            $("#grupos").html(a);

        },
        error: function(a){
            $('#grupos').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}
function carga_periodos_varios(grupo_id)
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "carga_periodos_varios="+grupo_id,
        beforeSend: function(){
            $("#periodos").attr("class","");
            $("#periodos").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
        },
        success: function(a){
            if (a != "") 
            {
                $("#periodos").attr("class","select-style");
                $("#periodos").html(a);
            }
            else
            {
                $("#periodos").attr("class","");
                $("#periodos").html("");
            }
        },
        error: function(a){
            $('#periodos').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}
function carga_grupos_varios(valor)
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "carga_grupos_varios="+localStorage.getItem("username")+"&funcion="+valor,
        beforeSend: function(){
            $("#grupos").attr("class","");
            $("#grupos").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
        },
        success: function(a){
            if (a != "") 
            {
                $("#grupos").attr("class","select-style");
                $("#grupos").html(a);
            }
            else
            {
                $("#grupos").attr("class","");
                $("#grupos").html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png" width="10%"><br><h2 class="titulod">No hay Grupos en su Carga Académica</h2></center><br>');
            }
        },
        error: function(a){
            $('#grupos').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}

function carga_estudiantes_grupo(grupo_id,funcion)
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "carga_estudiantes_grupo="+grupo_id+"&funcion="+funcion,
        beforeSend: function(){
            $("#estudiantes").attr("class","");
            $("#estudiantes").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
        },
        success: function(a){
            if (a != "") 
            {
                $("#estudiantes").attr("class","select-style");
                $("#estudiantes").html(a);
            }
            else
            {
                $("#estudiantes").html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png" width="10%"><br><h2 class="titulod">No hay Estudiantes en este Grupo</h2></center><br>');
            }
        },
        error: function(a){
            $('#estudiantes').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}

function carga_citacion(matri_id)
{

    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "carga_estudiante_matri="+matri_id,
        beforeSend: function(){
            $("#citacion_acudientes").attr("class","");
            $("#citacion_acudientes").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
        },
        success: function(a){
            if (a != "") 
            {
                $("#citacion_acudientes").addClass("contenedor");
                $("#citacion_acudientes").html("<input type='hidden' id='matri_id' value='"+matri_id+"'><br><br><br><center><h2>Citación a Acudientes de " + a + " <br></h2><br><br><br><table class='tabla' style='text-align: center' border='2'><tr><td colspan='2' class='celda'><br><div id='motivo' dir='auto' contenteditable='true' class='obser' onclick='borrar_otros_placeholder(motivo);'>Motivo</div><br></td></tr><tr><td class='celda' >Fecha de Citación</td><td class='celda'>Hora de Citación</td></tr><tr><td><input type='text' style='width:200px;' id='fecha_citacion' readonly onclick='agregar_perspectiva();'><div id='fecha_citacion'></div><br></td><td><input type='text' style='width:200px;' id='hora_citacion' readonly><br></td></tr><tr><td colspan='2'><br><div id='pregunta' dir='auto' contenteditable='true' class='obser' onclick='borrar_otros_placeholder(pregunta);'>Preguntar Por</div><br></td></tr><tr><td colspan='2'><br><div id='lugar' dir='auto' contenteditable='true' class='obser' onclick='borrar_otros_placeholder(lugar);'>Lugar de Citación</div><br></td></tr></table><button class='boton' onclick='generar_citacion();'>Generar Citación</button><div id='otros'></div></center>");
                calendario_soat();
                hora();
            }
            else
            {
                $("#citacion_acudientes").html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png" width="10%"><br><h2 class="titulod">Hubo un error cargando al Estudiante</h2></center><br>');
            }
        },
        error: function(a){
            $('#citacion_acudientes').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })

}

function generar_citacion()
{
    var hora_citacion = $("#hora_citacion").val();
    var fecha_citacion = $("#fecha_citacion").val();
    var motivo = $("#motivo").html();
    var pregunta = $("#pregunta").html();
    var lugar = $("#lugar").html();
    var matri_id = $("#matri_id").val();

    if (hora_citacion == "" || fecha_citacion == "" || motivo == "Motivo" || pregunta == "Preguntar Por" || lugar == "Lugar de Citación") 
    {
        $("#otros").html("<center><br><img src='"+url_pagina+"reconocimiento_pae/assets/img/info.png'><br><h3>Debe llenar todos los campos</h3></center>");
    }
    else
    {
        jQuery.ajax({
            type: "POST",
            url: url_pagina+"reconocimiento_pae/php/consultas.php",
            data: "guardar_citacion=1&hora_citacion="+hora_citacion+"&fecha_citacion="+fecha_citacion+"&motivo="+motivo+"&pregunta="+pregunta+"&lugar="+lugar+"&matri_id="+matri_id+"&perfil="+localStorage.getItem("username"),
            beforeSend: function(){
                $("#citacion_acudientes").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Guardando Información</p></center>');
            },
            success: function(a){
                if (a != 1) 
                {
                    $("#citacion_acudientes").html('<br><br><center><img src="'+url_pagina+'reconocimiento_pae/assets/img/success.png" width="10%"><br><h2 class="titulod">Datos guardados Correctamente</h2></center><br>');
                }
                else
                {
                    $("#citacion_acudientes").html('<br><br><center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png" width="10%"><br><h2 class="titulod">Los datos no se guardaron</h2></center><br>');
                }
            },
            error: function(a){
                $('#citacion_acudientes').html('<br><br><center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
            }
        })
    }
}

function carga_consolidado(per_id)
{
    var tipo_consolidado = $("#tipo_consolidado").val();
    var grupo_id = $("#grupos_select").val();


    if (tipo_consolidado == 1) 
    {
        $("#boton").html("<a href='"+url_pagina+"consolidado_acumulado_1_excel.php?grupo_id="+grupo_id+"&per_id="+per_id+"&app=1'><button class='boton'>Generar Consolidado</button></a>");
    }
    else if (tipo_consolidado == 2) 
    {
        $("#boton").html("<a href='"+url_pagina+"consolidado_areas.pdf.php?grupo_id="+grupo_id+"&per_id="+per_id+"&app=1'><button class='boton'>Generar Consolidado</button></a>");
    }
}



function pendientes_docente()
{
    $("#carga").css("display","none");
    $(".contenedor").css("display","");
    $(".contenedor").attr("id","pendientes_docente");

    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "cargar_pendientes_docente="+localStorage.getItem("username"),
        beforeSend: function(){
            $("#pendientes_docente").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Pendientes</p></center>');
        },
        success: function(a){
            if (a != "") 
            {
                $("#pendientes_docente").html("<br><br>"+a);
            }
            else
            {
                $("#pendientes_docente").html("No tiene Pendientes");
            }
        },
        error: function(a){
            $('#pendientes_docente').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })

    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
}
function registro_asistencia()
{
    window.open(url_pagina+'reconocimiento_asistencia/index.php', '_system', 'location=no');
}
function consulta_asistencia()
{
    $("#carga").css("display","none");
    $(".contenedor").css("display","");
    $(".contenedor").attr("id","consulta_asistencia");
    $("#consulta_asistencia").html("consulta_asistencia");
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
}
function citacion_acudientes()
{
    $("#carga").css("display","none");
    $(".contenedor").css("display","");
    $(".contenedor").attr("id","citacion_acudientes");
    $("#citacion_acudientes").html("<br><br><br><center><h2>Citación a Acudientes<br></h2><br><br><br><div id='grupos'></div><br><br><br><div id='estudiantes'></div>");
    carga_grupos_varios(1);
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
}
function atencion_padres()
{
    $("#carga").css("display","none");
    $(".contenedor").css("display","");
    $(".contenedor").attr("id","atencion_padres");
    $("#atencion_padres").html("<br><br><br><center><h2>Atención a Padres de Familia<br></h2><br><br><div id='pendientes_atencion'></div>");
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "cargar_pendientes_citacion=1&usuario="+localStorage.getItem("username"),
        beforesend: function(){
            $("#pendientes_atencion").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Datos</p></center>');
        },
        success: function(a){
            $("#pendientes_atencion").html(a);
            swiper();
        },
        error: function(a){
            $('#pendientes_atencion').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }  
    })
}
function datos_acudiente()
{
    $("#carga").css("display","none");
    $(".contenedor").css("display","");
    $(".contenedor").attr("id","datos_acudiente");
    $("#datos_acudiente").html("<br><br><br><center><h2>Consulta de Acudientes<br></h2><br><br><br><div id='grupos'></div><br><br><br><div id='estudiantes'></div>");
    carga_grupos_varios(2);
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
}
function carga_acudientes(matri_id)
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "matricula_datos_acu="+matri_id,
        beforeSend: function(){
            $("#datos_acudiente").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Datos</p></center>');
        },
        success: function(a){
            $("#datos_acudiente").html("<br><br>"+a);
            swiper();
        },
        error: function(a){
            $('#datos_acudiente').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }

    })
}
function consulta_citacion_acudientes()
{
    $("#carga").css("display","none");
    $(".contenedor").css("display","");
    $(".contenedor").attr("id","consulta_citacion_acudientes");
    $("#consulta_citacion_acudientes").html("<br><br><br><center><h2>Consulta de Citación a Acudientes<br></h2><br><br><br><div id='grupos'></div><br><br><br><div id='estudiantes'></div>");
    carga_grupos_varios(3);
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
}

function carga_citaciones_hechas(matri_id)
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "matricula_datos_citacion="+matri_id+"&perfil="+localStorage.getItem("username"),
        beforeSend: function(){
            $("#consulta_citacion_acudientes").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Datos</p></center>');
        },
        success: function(a){
            $("#consulta_citacion_acudientes").html("<br><br>"+a);
            swiper();
        },
        error: function(a){
            $('#consulta_citacion_acudientes').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }

    })
}

function actualizar_citaciones(id_citacion)
{
    var motivo = $("#motivo_"+id_citacion).html();
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "datos_actualizar_citacion="+id_citacion+"&motivo="+motivo,
        beforeSend: function(){
            $("#consulta_citacion_acudientes").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Datos</p></center>');
        },
        success: function(a){
            $("#consulta_citacion_acudientes").html('<br><br><br><br><center><img src="'+url_pagina+'reconocimiento_pae/assets/img/success.png"/ width="10%"><br><h1 class="titulod">Datos actualizados Correctamente</h1></center>');
            setTimeout(function(){
                carga_citaciones_hechas(a);
            }, 1500);
        },
        error: function(a){
            $('#consulta_citacion_acudientes').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }

    })
}
function borrar_citaciones(id_citacion)
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "datos_borrar_citacion="+id_citacion,
        beforeSend: function(){
            $("#consulta_citacion_acudientes").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Datos</p></center>');
        },
        success: function(a){
            $("#consulta_citacion_acudientes").html('<br><br><br><br><center><img src="'+url_pagina+'reconocimiento_pae/assets/img/success.png"/ width="10%"><br><h1 class="titulod">Datos Borrados Correctamente</h1></center>');
            setTimeout(function(){
                carga_citaciones_hechas(a);
            }, 1500);
        },
        error: function(a){
            $('#consulta_citacion_acudientes').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }

    })
}

function registro_anotaciones()
{
    $("#carga").css("display","none");
    $(".contenedor").css("display","");
    $(".contenedor").attr("id","registro_anotaciones");
    $("#registro_anotaciones").html("<br><br><br><center><h2>Observador del Alumno<br></h2><br><br><br><div class='select-style'><select id='tipo_obse' onchange='cargar_grupos_obse(this.value);'><option>...</option><option value='1'>Observación</option><option value='2'>Felicitación</option></select></div><br><br><div id='grupos'></div><br><br><br><div id='estudiantes'></div>");
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
}
function cargar_grupos_obse(valor)
{
    if (valor != "") 
    {
        carga_grupos_varios(4);
    }

}

function carga_interfaz_observador(matri_id)
{
    var tipo_observacion = $("#tipo_obse").val();
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "carga_estudiante_matri="+matri_id,
        beforeSend: function(){
            $("#registro_anotaciones").attr("class","");
            $("#registro_anotaciones").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
        },
        success: function(a){
            if (a != "") 
            {
                $("#registro_anotaciones").addClass("contenedor");
                if (tipo_observacion == 1) {
                    $("#registro_anotaciones").html("<br><br><br><center><h2>Registro de Observaciones para "+a+"<br></h2><table class='tabla' style='text-align: center' border='1'><tr><td colspan='2' class='celda'><br><div dir='auto' contenteditable='true' class='obser' width='400px;' id='hechos' onclick='borrar_otros_placeholder(hechos);'>Hechos</div><br></td></tr><tr><td colspan='2' class='celda'><br><div dir='auto' contenteditable='true' class='obser' width='400px;' id='descargos' onclick='borrar_otros_placeholder(descargos);'>Descargos</div><br></td></tr><tr><td colspan='2' class='celda'><br><div dir='auto' contenteditable='true' class='obser' width='400px;' id='orientacion' onclick='borrar_otros_placeholder(orientacion);'>Orientación</div><br></td></tr><tr><td><button onclick='guardar_observacion("+matri_id+");'>Guardar</button></td><td><button onclick='limpiar_observacion();'>Reiniciar</button></td></tr></table><br><br><div id='informacion'></div>");
                }
                else if(tipo_observacion == 2){
                    $("#registro_anotaciones").html("<br><br><br><center><h2>Registro de Felicitaciones para "+a+"<br></h2><table class='tabla' style='text-align: center' border='1'><tr><td colspan='2' class='celda'><br><div dir='auto' contenteditable='true' class='obser' width='400px;' id='felicitaciones' onclick='borrar_otros_placeholder(felicitaciones);'>Felicitaciones</div><br></td></tr><tr><td><button onclick='guardar_felicitacion("+matri_id+");'>Guardar</button></td><td><button onclick='limpiar_felicitacion();'>Reiniciar</button></td></tr></table><br><br><div id='informacion'></div>");   
                }
            }
            else
            {
                $("#registro_anotaciones").html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png" width="10%"><br><h2 class="titulod">Hubo un error cargando al Estudiante</h2></center><br>');
            }
        },
        error: function(a){
            $('#registro_anotaciones').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })

}

function limpiar_observacion()
{
    $("#hechos").html("Hechos");
    $("#descargos").html("Descargos");
    $("#orientacion").html("Orientación");
}

function limpiar_felicitacion()
{
    $("#felicitaciones").html("Felicitaciones");
}

function guardar_observacion(matri_id)
{
    var hechos = $("#hechos").html();
    var descargos = $("#descargos").html();
    var orientacion = $("#orientacion").html();
    if (hechos == "Hechos" || descargos == "Descargos" || orientacion == "Orientación" || hechos == "" || descargos == "" || orientacion == "")
    {
        $("#informacion").html("<center><br><img src='"+url_pagina+"reconocimiento_pae/assets/img/info.png'><br><h3>Debe llenar todos los campos</h3></center>");
    }
    else
    {
        jQuery.ajax({
            type: "POST",
            url: url_pagina+"reconocimiento_pae/php/consultas.php",
            data: "guardar_observacion=1&matri_id="+matri_id+"&hechos="+hechos+"&descargos="+descargos+"&orientacion="+orientacion+"&perfil="+localStorage.getItem("username")+"&rol="+localStorage.getItem("rol"),
            beforeSend: function(){
                $("#registro_anotaciones").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Guardando Datos</p></center>');
            },
            success: function(a){
                if (a != 1) 
                {
                    $("#registro_anotaciones").html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/success.png" width="10%"><br><h2 class="titulod">Los datos se guardaron correctamente</h2></center><br>');
                }
                else
                {
                    $("#registro_anotaciones").html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png" width="10%"><br><h2 class="titulod">Los datos no se guardaron</h2></center><br>');
                }
            },
            error: function(a){
                $('#registro_anotaciones').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
            }
        })
    }
}
function guardar_felicitacion(matri_id)
{
    var felicitaciones = $("#felicitaciones").html();

    if (felicitaciones == "Felicitaciones" || felicitaciones == "")
    {
        $("#informacion").html("<center><br><img src='"+url_pagina+"reconocimiento_pae/assets/img/info.png'><br><h3>Debe llenar todos los campos</h3></center>");
    }
    else
    {
        jQuery.ajax({
            type: "POST",
            url: url_pagina+"reconocimiento_pae/php/consultas.php",
            data: "guardar_felicitacion=1&matri_id="+matri_id+"&felicitaciones="+felicitaciones+"&perfil="+localStorage.getItem("username")+"&rol="+localStorage.getItem("rol"),
            beforeSend: function(){
                $("#registro_anotaciones").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Guardando Datos</p></center>');
            },
            success: function(a){
                if (a != 1) 
                {
                    $("#registro_anotaciones").html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/success.png" width="10%"><br><h2 class="titulod">Los datos se guardaron correctamente</h2></center><br>');
                }
                else
                {
                    $("#registro_anotaciones").html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png" width="10%"><br><h2 class="titulod">Los datos no se guardaron</h2></center><br>');
                }
            },
            error: function(a){
                $('#registro_anotaciones').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
            }
        })
    }
}
function consulta_observador()
{
    $("#carga").css("display","none");
    $(".contenedor").css("display","");
    $(".contenedor").attr("id","consulta_observador");
    $("#consulta_observador").html("<br><br><br><center><h2>Consulta del Observador<br></h2><br><br><br><div id='grupos'></div><br><br><br><div id='estudiantes'></div>");
    carga_grupos_varios(5);
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
}
function carga_consulta_observador(matri_id)
{

    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "consulta_observador=1&matri_id="+matri_id,
        beforeSend: function(){
            $("#consulta_observador").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Datos</p></center>');
        },
        success: function(a){
            $("#consulta_observador").html("<br><br><br>"+a);
            swiper();
        },
        error: function(a){
            $('#consulta_observador').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}
function prestamo_recursos()
{
    $("#carga").css("display","none");
    $(".contenedor").css("display","");
    $(".contenedor").attr("id","prestamo_recursos");
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "prestamo_recursos=1&perfil="+localStorage.getItem("username"),
        beforeSend: function(){
            $("#prestamo_recursos").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Datos</p></center>');
        },
        success: function(a){
            $("#prestamo_recursos").html(a);
            swiper();
        },
        error: function(a){
            $('#prestamo_recursos').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}
function solicitar_recurso(id_recurso)
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "consulta_recurso=1&id_recurso="+id_recurso,
        beforeSend: function(){
            $("#prestamo_recursos").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Guardando Datos</p></center>');
        },
        success: function(a){
            $("#prestamo_recursos").html("<br><br><center><h2>Solicitud de Préstamo de "+a+"<br></h2><br><table class='tabla' border='1' style='text-align:center;'><tr><td class='celda'>Fecha de Retiro</td><td class='celda'><input type='text' id='fecha_retiro' readonly style='width:200px;' onclick='agregar_perspectiva();'><div id='fecha_retiro'></div><br></td></tr><tr><td class='celda'>Hora de Retiro</td><td class='celda'><input type='text' id='hora_retiro' readonly style='width:200px;'><br></td></tr><tr><td class='celda'>Disponibles</td><td class='celda'><div id='disponibilidad'></div></td></tr><tr><td class='celda'>Cantidad a Separar</td><td class='celda'><div id='div_cantidad'></div><br></td></tr><tr><td class='celda'>Fecha de Devolución</td><td class='celda'><input type='text' id='fecha_devolucion' readonly style='width:200px;' onclick='agregar_perspectiva();'><div id='fecha_devolucion'><br></td></tr><tr><td class='celda'>Hora de Devolución</td><td class='celda'><input type='text' id='hora_devolucion' readonly style='width:200px;'><br></td></tr><tr><td colspan='2' class='celda'><button class='boton' onclick='guardar_recurso("+id_recurso+");'>Solicitar Recurso</button></td></tr><tr><td colspan='2' class='celda'><div id='complemento'></div></td></tr></table></center>");
            calendario_soat();
            hora();
            disponibilidad(id_recurso);
        },
        error: function(a){
            $('#prestamo_recursos').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}
function disponibilidad(id_recurso)
{
    jQuery.ajax({    
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "consulta_disponibilidad_recurso=1&id_recurso="+id_recurso,
        beforeSend: function(){
            $("#disponibilidad").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Datos</p></center>');
        },
        success: function(a){
            $("#disponibilidad").html(a);
            $("#div_cantidad").html("<input type='number' style='width:200px;' min='0' max='"+a+"' id='cantidad' onkeyup='validar("+a+");' onkeypress='return justNumbers(event);'>");
        },
        error: function(a){
            $('#disponibilidad').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}
function validar(valor)
{
    var valor_digitado = $("#cantidad").val();
    if (valor_digitado > valor) 
    {
        $("#cantidad").val("");   
    }
}

function justNumbers(e)
{
    var keynum = window.event ? window.event.keyCode : e.which;
    if ((keynum == 8) || (keynum == 46))
        return true;
    return /\d/.test(String.fromCharCode(keynum));
}

function guardar_recurso(id_recurso)
{
    var fecha_retiro = $("#fecha_retiro").val();
    var hora_retiro = $("#hora_retiro").val();
    var cantidad_separar = $("#cantidad").val();
    var disponibles = $("#disponibilidad").html();
    var fecha_devolucion = $("#fecha_devolucion").val();
    var hora_devolucion = $("#hora_devolucion").val();

    if (cantidad_separar > disponibles) 
    {
        $("#complemento").html("<center><br><img src='"+url_pagina+"reconocimiento_pae/assets/img/info.png'><br><h3>La cantidad a separar es mayor a la disponible</h3></center>");
    }
    else if (fecha_retiro == "" || hora_retiro == "" || cantidad_separar == "" || fecha_devolucion == "" || hora_devolucion == "")
    {
        $("#complemento").html("<center><br><img src='"+url_pagina+"reconocimiento_pae/assets/img/info.png'><br><h3>Debe llenar todos los campos</h3></center>");
    }
    else
    {
        jQuery.ajax({    
            type: "POST",
            url: url_pagina+"reconocimiento_pae/php/consultas.php",
            data: "guardar_recurso=1&id_recurso="+id_recurso+"&fecha_retiro="+fecha_retiro+"&hora_retiro="+hora_retiro+"&cantidad_separar="+cantidad_separar+"&fecha_devolucion="+fecha_devolucion+"&hora_devolucion="+hora_devolucion+"&perfil="+localStorage.getItem("username"),
            beforeSend: function(){
                $("#prestamo_recursos").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Guardando Datos</p></center>');
            },
            success: function(a){
                if (a != 1) 
                {
                    $("#prestamo_recursos").html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/success.png"/ width="7%"><br><h3 class="titulod">Datos guardados Correctamente</h3></center>');
                }
                else{
                    $("#prestamo_recursos").html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Los datos no se guardaron</h3></center>');
                }

            },
            error: function(a){
                $('#prestamo_recursos').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
            }
        })
    }
}
function pendientes_recursos()
{
    $("#carga").css("display","none");
    $(".contenedor").css("display","");
    $(".contenedor").attr("id","pendientes_recursos");
    jQuery.ajax({    
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "cargar_pendientes_recursos=1&perfil="+localStorage.getItem("username"),
        beforeSend: function(){
            $("#pendientes_recursos").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Datos</p></center>');
        },
        success: function(a){

            $("#pendientes_recursos").html(a);

        },
        error: function(a){
            $('#pendientes_recursos').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
    update_not_recursos();
}

function update_not_recursos()
{
    jQuery.ajax({    
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "update_not_recursos=1&usuario="+localStorage.getItem("username"),
        success: function(a){
            ocultar_mensaje();
        }
    })
}

function consulta_beneficiario()
{
    $("#carga").css("display","none");
    $(".contenedor").css("display","");
    $(".contenedor").attr("id","consulta_beneficiario");
    $("#consulta_beneficiario").html("consulta_beneficiario");
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
}
function control_abordaje()
{
    window.open(url_pagina+'reconocimiento_abordaje/index.php', '_system', 'location=no');
}

function registro_vehiculo()
{
    $("#carga").css("display","none");
    $(".contenedor").css("display","");
    $(".contenedor").attr("id","registro_vehiculo");
    $("#registro_vehiculo").html("<div class='swiper-container'><div class='swiper-wrapper'><div class='swiper-slide'><table><tr><td><h2>Registro de Vehículos</h2></td></tr><tr><td><input type='text' placeholder='Placa' class='username' id='placa' onkeypress='mensaje_ayuda(this); return soloLetrasv2(event);'></td></tr><tr><td><input type='text' placeholder='Vehículo' class='username' id='vehiculo' onkeypress='mensaje_ayuda(this); return soloLetrasv(event);'></td></tr><tr><td><input type='text' placeholder='Modelo' class='username' id='modelo' onkeypress='mensaje_ayuda(this); return soloLetrasv(event);'></td></tr><tr><td><input type='text' placeholder='Capacidad de Pasajeros' class='username' id='capacidad_pasajeros'  onkeypress='mensaje_ayuda(this); return justNumbers(event);'></td></tr><tr><td><br><br><div id='flecha_derecha'></div></td></tr></table></div><div class='swiper-slide'><table><tr><td><h2>Revisión del Vehículo</h2></td></tr><tr><td><input type='text' placeholder='Fecha Vencimiento SOAT' id='fecha_soat' readonly onclick='agregar_perspectiva();'><div id='fecha_soat'></div></td></tr><tr><td><input type='text' placeholder='Revisión Vehicular Mecánica y Gases' id='vencimiento_mecanica' readonly onclick='agregar_perspectiva();'><div id='vencimiento_mecanica'></div></td></tr><tr><td><button onclick='guardar_vehiculo();' name='guardar' class='boton'>Guardar vehículo</button></td></tr><tr><td><div id='error_guardar'></div></td></tr></table></div></div></div><br><br><br>");
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
    swiper();
    calendario_soat();
}
function mensaje_ayuda(variable)
{
    var id_input = variable.id;
    var placa = $("#placa").val();
    var vehiculo = $("#vehiculo").val();
    var modelo = $("#modelo").val();
    var capacidad_pasajeros = $("#capacidad_pasajeros").val();

    if (id_input == "placa") 
    {
        if ( vehiculo != "" && modelo != "" && capacidad_pasajeros != "") 
        {
            $("#flecha_derecha").html("<center><br><img src='"+url_pagina+"reconocimiento_pae/img/flecha_derecha.png' width='100'><br><h3>Desliza para Continuar</h3></center>");
        }
    }
    else if (id_input == "vehiculo") 
    {
        if (placa != "" && modelo != "" && capacidad_pasajeros != "") 
        {
            $("#flecha_derecha").html("<center><br><img src='"+url_pagina+"reconocimiento_pae/img/flecha_derecha.png' width='100'><br><h3>Desliza para Continuar</h3></center>");
        }
    }
    else if (id_input == "modelo") 
    {
        if (placa != "" && vehiculo != "" && capacidad_pasajeros != "") 
        {
            $("#flecha_derecha").html("<center><br><img src='"+url_pagina+"reconocimiento_pae/img/flecha_derecha.png' width='100'><br><h3>Desliza para Continuar</h3></center>");
        }
    }
    else if (id_input == "capacidad_pasajeros") 
    {
        if (placa != "" && vehiculo != "" && modelo != "") 
        {
            $("#flecha_derecha").html("<center><br><img src='"+url_pagina+"reconocimiento_pae/img/flecha_derecha.png' width='100'><br><h3>Desliza para Continuar</h3></center>");
        }
    }
}

function guardar_vehiculo()
{
    var placa = $("#placa").val();
    var vehiculo = $("#vehiculo").val();
    var modelo = $("#modelo").val();
    var capacidad_pasajeros = $("#capacidad_pasajeros").val();
    var vencimiento_soat = $("#fecha_soat").val();
    var vencimiento_mecanica = $("#vencimiento_mecanica").val();

    if (placa == "" || vehiculo == "" || modelo == "" || capacidad_pasajeros == "" || vencimiento_soat == "" || vencimiento_mecanica == "" ) 
    {
        $("#error_guardar").html("<center><br><img src='"+url_pagina+"reconocimiento_pae/assets/img/info.png'><br><h3>Debe llenar todos los campos</h3></center>");
    }
    else
    {
        $("#error_guardar").css("display","none");
        jQuery.ajax({
            type: "POST",
            url: url_pagina+"reconocimiento_pae/php/consultas.php",
            data: "subir_vehiculo=1&placa="+placa+"&vehiculo="+vehiculo+"&modelo="+modelo+"&capacidad_pasajeros="+capacidad_pasajeros+"&vencimiento_soat="+vencimiento_soat+"&vencimiento_mecanica="+vencimiento_mecanica,
            beforeSend: function(){
                $("#registro_vehiculo").html('<br><br><br><br><center><img src="'+url_pagina+'reconocimiento_pae/assets/img/progress2.gif"/ width="50%"><br><h1 class="titulod">GUARDANDO</h1></center>');
            },
            success: function(a){
                if (a != 1) 
                {
                    $("#registro_vehiculo").html('<br><br><br><br><center><img src="'+url_pagina+'reconocimiento_pae/assets/img/success.png" width="10%"><br><h1 class="titulod">DATOS GUARDADOS CORRECTAMENTE</h1></center>');
                }
                else{
                    $("#registro_vehiculo").html('<br><br><br><br><center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="10%"><br><h1 class="titulod">LOS DATOS NO SE GUARDARON</h1></center>');
                }
            },
            error: function(a){
                $('#registro_vehiculo').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
            }
        })
    }

}
function registro_conductor()
{
    $(".contenedor").css("display","");
    $("#carga").css("display","none");
    $(".contenedor").css("margin","0");
    $(".contenedor").attr("id","registro_conductor");
    $("#registro_conductor").html("<div class='swiper-container'><div class='swiper-wrapper'><div class='swiper-slide'><table><tr><td><h2>Registro de Conductor</h2></td></tr><tr><td><input type='text' placeholder='Primer Apellido' class='username' id='primer_apellido' onkeypress='primer_mensaje(this); return soloLetras(event);'></td></tr><tr><td><input type='text' placeholder='Segundo Apellido' class='username' id='segundo_apellido' onkeypress='primer_mensaje(this); return soloLetras(event)'></td></tr><tr><td><input type='text' placeholder='Primer Nombre' class='username' id='primer_nombre' onkeypress='primer_mensaje(this); return soloLetras(event)'></td></tr><tr><td><input type='text' placeholder='Segundo Nombre' class='username' id='segundo_nombre' onkeypress='primer_mensaje(this); return soloLetras(event)'></td></tr><tr><td><input type='text' placeholder='Documento de Identidad' class='username' id='documento_identidad' onkeypress='primer_mensaje(this); return justNumbers(event);'></td></tr><tr><td><div id='primer_mensaje'></div></td></tr></table></div><div class='swiper-slide'><table><tr><td><h2>Registro de Conductor</h2></td></tr><tr><td><input type='text' placeholder='Fecha Vencimiento de la Licencia' readonly id='vencimiento_licencia' onclick='agregar_perspectiva();'><div id='vencimiento_licencia' ></div><div id='segundo_mensaje'></div></td></tr></table></div><div class='swiper-slide'><table><tr><td><h2>Foto del Conductor</h2></td></tr><tr><td><br><br><br><div id='cargar_modo'></div></td></tr><tr><td><button onclick='guardar_conductor();' name='guardar' class='boton'>Guardar conductor</button></td></tr></table></div></div></div><div id='error_conductor'></div>");
    $("#cargar_modo").html("<center><div class='box' onclick='tomar_foto();'><input class='inputfile inputfile-4' /><label for='file-5'><figure><svg width='20' height='17' viewBox='0 0 20 17'><span class='icon-instagram icono_subir'></span></svg></figure></label></div><br><br><img id='fotoLocal' src='' width='100px' height='100px'></center>");


    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
    swiper();
    calendario_soat();
}
function segundo_mensaje()
{
    $("#segundo_mensaje").html("<center><br><img src='"+url_pagina+"reconocimiento_pae/img/flecha_derecha.png' width='100'><br><h3>Desliza para Continuar</h3></center>");
}
function primer_mensaje(input)
{
    var id_input = input.id;
    var primer_apellido = $("#primer_apellido").val();
    var segundo_apellido = $("#segundo_apellido").val();
    var primer_nombre = $("#primer_nombre").val();
    var segundo_nombre = $("#segundo_nombre").val();
    var documento_identidad = $("#documento_identidad").val();

    if (id_input == "primer_apellido") 
    {
        if ( primer_nombre != "" && documento_identidad != "") 
        {
            $("#primer_mensaje").html("<center><br><img src='"+url_pagina+"reconocimiento_pae/img/flecha_derecha.png' width='100'><br><h3>Desliza para Continuar</h3></center>");
        }
    }
    else if (id_input == "segundo_apellido") 
    {
        if (primer_apellido != "" && primer_nombre != "" && documento_identidad != "") 
        {
            $("#primer_mensaje").html("<center><br><img src='"+url_pagina+"reconocimiento_pae/img/flecha_derecha.png' width='100'><br><h3>Desliza para Continuar</h3></center>");
        }
    }
    else if (id_input == "primer_nombre") 
    {
        if (primer_apellido != ""  && documento_identidad != "") 
        {
            $("#primer_mensaje").html("<center><br><img src='"+url_pagina+"reconocimiento_pae/img/flecha_derecha.png' width='100'><br><h3>Desliza para Continuar</h3></center>");
        }
    }
    else if (id_input == "segundo_nombre")
    {
        if (primer_apellido != "" && primer_nombre != "" && documento_identidad != "") 
        {
            $("#primer_mensaje").html("<center><br><img src='"+url_pagina+"reconocimiento_pae/img/flecha_derecha.png' width='100'><br><h3>Desliza para Continuar</h3></center>");
        }
    }
    else if (id_input == "documento_identidad") 
    {
        if (primer_apellido != ""  && primer_nombre != "" ) 
        {
            $("#primer_mensaje").html("<center><br><img src='"+url_pagina+"reconocimiento_pae/img/flecha_derecha.png' width='100'><br><h3>Desliza para Continuar</h3></center>");
        }
    }
}
function calendario_soat()
{
    jQuery(function ($) {
        $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: ' nextText:Sig>',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
            'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié;', 'Juv', 'Vie', 'Sáb'],
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
            weekHeader: 'Sm',
            dateFormat: 'yy-mm-dd',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['es']);
    });
    $(function () {
        $.datepicker.setDefaults($.datepicker.regional["es"]);
        $("#fecha_soat").datepicker({
            firstDay: 1,
            changeMonth: true,
            changeYear: true,
            yearRange: "2010:2050",
            onSelect: function (date) {
                $("#fecha_soat").html(date);
                quitar_perspectiva();
            },
        });
    });
    $(function () {
        $.datepicker.setDefaults($.datepicker.regional["es"]);
        $("#vencimiento_mecanica").datepicker({
            firstDay: 1,
            changeMonth: true,
            changeYear: true,
            yearRange: "2010:2050",
            onSelect: function (date) {
                $("#vencimiento_mecanica").html(date);
                quitar_perspectiva();
            },
        });
    });
    $(function () {
        $.datepicker.setDefaults($.datepicker.regional["es"]);
        $("#vencimiento_licencia").datepicker({
            firstDay: 1,
            changeMonth: true,
            changeYear: true,
            yearRange: "2010:2050",
            onSelect: function (date) {
                $("#vencimiento_licencia").html(date);
                segundo_mensaje();
                quitar_perspectiva();
            },
        });
    });
    $(function () {
        $.datepicker.setDefaults($.datepicker.regional["es"]);
        $("#fecha_inicio").datepicker({
            firstDay: 1,
            changeMonth: true,
            changeYear: true,
            yearRange: "2010:2050",
            onSelect: function (date) {
                $("#fecha_inicio").html(date);
                segundo_mensaje();
                quitar_perspectiva();
            },
        });
    });
    $(function () {
        $.datepicker.setDefaults($.datepicker.regional["es"]);
        $("#fecha_citacion").datepicker({
            firstDay: 1,
            changeMonth: true,
            changeYear: true,
            yearRange: "2010:2050",
            onSelect: function (date) {
                $("#fecha_citacion").html(date);
                segundo_mensaje();
                quitar_perspectiva();
            },
        });
    });
    $(function () {
        $.datepicker.setDefaults($.datepicker.regional["es"]);
        $("#fecha_retiro").datepicker({
            firstDay: 1,
            changeMonth: true,
            changeYear: true,
            yearRange: "2010:2050",
            onSelect: function (date) {
                $("#fecha_retiro").html(date);
                segundo_mensaje();
                quitar_perspectiva();
            },
        });
    });
    $(function () {
        $.datepicker.setDefaults($.datepicker.regional["es"]);
        $("#fecha_devolucion").datepicker({
            firstDay: 1,
            changeMonth: true,
            changeYear: true,
            yearRange: "2010:2050",
            onSelect: function (date) {
                $("#fecha_devolucion").html(date);
                segundo_mensaje();
                quitar_perspectiva();
            },
        });
    });
}
function hora()
{
    $('#hora_citacion').timepicki({
     overflow_minutes:true,
     step_size_minutes:5}); 
    $('#hora_retiro').timepicki({
     overflow_minutes:true,
     step_size_minutes:5}); 
    $('#hora_devolucion').timepicki({
     overflow_minutes:true,
     step_size_minutes:5}); 
}

function agregar_perspectiva()
{
    $(".perspective").addClass("modalview");
}
function quitar_perspectiva()
{
    $(".perspective").removeClass("modalview");
}


function guardar_conductor()
{
    var primer_apellido = $("#primer_apellido").val();
    var segundo_apellido = $("#segundo_apellido").val();
    var primer_nombre = $("#primer_nombre").val();
    var segundo_nombre = $("#segundo_nombre").val();
    var documento_identidad = $("#documento_identidad").val();
    var vencimiento_licencia = $("#vencimiento_licencia").val();
    var foto_conductor = localStorage.getItem("foto_final_conductor");

    if (primer_apellido == "" || primer_nombre == "" || documento_identidad == "" || vencimiento_licencia == "" ||  foto_conductor == null) 
    {
        $("#error_conductor").html("<center><br><img src='"+url_pagina+"reconocimiento_pae/assets/img/info.png'><br><h3>Debe llenar todos los campos requeridos </h3></center>");
    }
    else{
        subirImagen(localStorage.getItem("foto_final_conductor"));
    }
}
function consulta_conductor()
{
    $("#carga").css("display","none");
    $(".contenedor").css("display","");
    $(".contenedor").attr("id","consulta_conductor");
    $("#consulta_conductor").html("<div><form action='' onsubmit='carga_conductor(); return false;'><br><br><center><table><tr><td><h2>Consulta de Conductores</h2></td></tr></center><tr><td><center><input type='text' placeholder='Primer Apellido' class='username' id='primer_apellido' onkeypress='return soloLetras(event)' ></center></td></tr><tr><td><center><input type='text' placeholder='Segundo Apellido' class='username' id='segundo_apellido' onkeypress='return soloLetras(event)'></center><tr><td><center><input type='text' placeholder='Primer Nombre' class='username' id='primer_nombre' onkeypress='return soloLetras(event)'></center></td></tr></td></tr><tr><td><center><input type='text' placeholder='Segundo Nombre' class='username' id='segundo_nombre' onkeypress='return soloLetras(event)'></center></td></tr><tr><td><center><input type='text' placeholder='Documento de Identidad' class='username' id='documento_identidad' onkeypress='return justNumbers(event);'></center></td></tr><tr><td><center><input type='text' placeholder='Placa' class='username' id='placa'></center></td></tr></table><center><br><br><button type='submit' name='guardar' class='boton'>Consultar</button></center><center></form></div>");
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
}
function carga_conductor()
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "consulta_conductor=1&primer_apellido="+$("#primer_apellido").val()+"&segundo_apellido="+$("#segundo_apellido").val()+"&primer_nombre="+$("#primer_nombre").val()+"&segundo_nombre="+$("#segundo_nombre").val()+"&documento_identidad="+$("#documento_identidad").val()+"&placa="+$("#placa").val(),
        beforeSend: function(){
            $("#consulta_conductor").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
        },
        success: function(a){
            $("#consulta_conductor").html(a);
            swiper();
        },
        error: function(a){
            $('#consulta_conductor').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}
function actualizar_conductor()
{
    $("#carga").css("display","none");
    $(".contenedor").css("display","");
    $(".contenedor").attr("id","actualizar_conductor");
    $("#actualizar_conductor").html("<div><form action='' onsubmit='actualizacion_conductor(); return false;'><br><br><center><table><tr><td><h2>Actualización datos de Conductor</h2></td></tr></center><tr><td><center><input type='text' placeholder='Documento de Identidad' class='username' id='documento_identidad' onkeypress='return justNumbers(event)' ></center></td></tr></table><center><br><br><button type='submit' name='guardar' class='boton'>Actualizar</button></center><center></form></div><div id='alertas'></div>");
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
}

function actualizacion_conductor()
{
    var documento_identidad = $("#documento_identidad").val();
    if (documento_identidad == "")
    {
        $("#alertas").css("display","");
        $("#alertas").html("<center><br><img src='"+url_pagina+"reconocimiento_pae/assets/img/info.png'><br><h3>Debe ingresar el documento de identidad</h3></center>");
    }
    else
    {
        $("#alertas").css("display","none");
        jQuery.ajax({
            type: "POST",
            url: url_pagina+"reconocimiento_pae/php/consultas.php",
            data: "actualizar_conductor=1&documento_identidad="+documento_identidad,
            beforeSend: function(){
                $("#actualizar_conductor").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
            },
            success: function(a){
                $("#actualizar_conductor").html(a);
                calendario_soat();
            },
            error: function(a){
                $('#actualizar_conductor').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
            }
        })
    }
}

function final_actualizacion_conductor()
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "final_actualizacion_conductor=1&primer_apellido="+$("#primer_apellido").val()+"&segundo_apellido="+$("#segundo_apellido").val()+"&primer_nombre="+$("#primer_nombre").val()+"&segundo_nombre="+$("#segundo_nombre").val()+"&vencimiento_licencia="+$("#vencimiento_licencia").val()+"&documento_identidad="+$("#documento_conductor").val(),
        beforeSend: function(){
            $("#actualizar_conductor").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Actualizar Información</p></center>');
        },
        success: function(a){
            if (a == 1) 
            {
                $("#actualizar_conductor").html('<br><br><br><br><center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="10%"><br><h1 class="titulod">Los datos no se actualizaron</h1></center>');
            }
            else
            {
                $("#actualizar_conductor").html('<br><br><br><br><center><img src="'+url_pagina+'reconocimiento_pae/assets/img/success.png"/ width="10%"><br><h1 class="titulod">Datos actualizados Correctamente</h1></center>');
            }
        },
        error: function(a){
            $('#actualizar_conductor').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}
function actualizar_vehiculo()
{
    $("#carga").css("display","none");
    $(".contenedor").css("display","");
    $(".contenedor").attr("id","actualizar_vehiculo");
    $("#actualizar_vehiculo").html("<div><form action='' onsubmit='actualizacion_vehiculo(); return false;'><br><br><center><table><tr><td><h2>Actualización datos de Vehiculo</h2></td></tr></center><tr><td><center><input type='text' placeholder='Placa' class='username' id='placa' ></center></td></tr></table><center><br><br><button type='submit' name='guardar' class='boton'>Actualizar</button></center><center></form></div><div id='alertas'></div>");
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
}
function actualizacion_vehiculo()
{
    var placa = $("#placa").val();
    if (placa == "")
    {
        $("#alertas").css("display","");
        $("#alertas").html("<center><br><img src='"+url_pagina+"reconocimiento_pae/assets/img/info.png'><br><h3>Debe ingresar la placa</h3></center>");
    }
    else
    {
        $("#alertas").css("display","none");
        jQuery.ajax({
            type: "POST",
            url: url_pagina+"reconocimiento_pae/php/consultas.php",
            data: "actualizar_vehiculo=1&placa="+placa,
            beforeSend: function(){
                $("#actualizar_vehiculo").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
            },
            success: function(a){
                $("#actualizar_vehiculo").html(a);
                calendario_soat();
            },
            error: function(a){
                $('#actualizar_vehiculo').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
            }
        })
    }
}
function final_actualizacion_vehiculo()
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "final_actualizacion_vehiculo=1&vencimiento_soat="+$("#fecha_soat").val()+"&vencimiento_mecanica="+$("#vencimiento_mecanica").val()+"&placa="+$("#placa").val(),
        beforeSend: function(){
            $("#actualizar_vehiculo").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Actualizar Información</p></center>');
        },
        success: function(a){
            if (a == 1) 
            {
                $("#actualizar_vehiculo").html('<br><br><br><br><center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="10%"><br><h1 class="titulod">Los datos no se actualizaron</h1></center>');
            }
            else
            {
                $("#actualizar_vehiculo").html('<br><br><br><br><center><img src="'+url_pagina+'reconocimiento_pae/assets/img/success.png"/ width="10%"><br><h1 class="titulod">Datos actualizados Correctamente</h1></center>');
            }
        },
        error: function(a){
            $('#actualizar_vehiculo').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}

function pendientes_conductor()
{
    $("#carga").css("display","none");
    $(".contenedor").css("display","");
    $(".contenedor").attr("id","pendientes_conductor");
    $("#pendientes_conductor").html("<div class='swiper-container'><div class='swiper-wrapper'><div class='swiper-slide'><br><br><center><table><tr><td><h2>Alerta de Pendientes de Conductores</h2></td></tr></table><br><div id='mostrar_pendientes_conductor'></div></center></div><div class='swiper-slide'><br><br><center><table><tr><td><h2>Alerta de Pendientes de Vehiculos</h2></td></tr></table><br><div id='mostrar_pendientes_vehiculos'></div></center></div></div></div>");
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
    swiper();
    mostrar_pendientes_conductor();
    mostrar_pendientes_vehiculos();
}
function redireccionar_pendientes()
{
    ocultar_mensaje();
    localStorage.setItem("redireccion_pendientes","1");
    window.location.href = "transporte_escolar.html";
}

function redireccionar_pendientes_prestamo()
{
    ocultar_mensaje();
    localStorage.setItem("redireccion_pendientes","2");
    window.location.href = "recursos_institucionales.html";
}
function pendientes()
{
    if (localStorage.getItem("redireccion_pendientes") != "")
    {
        if (localStorage.getItem("redireccion_pendientes") == 1) 
        {
            localStorage.removeItem("redireccion_pendientes");
            pendientes_conductor();
        }
        if (localStorage.getItem("redireccion_pendientes") == 2) 
        {
            localStorage.removeItem("redireccion_pendientes");
            pendientes_recursos();
        }
    }
}
function mostrar_pendientes_conductor()
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "mostrar_pendientes_conductor=1",
        beforeSend: function(){
            $("#mostrar_pendientes_conductor").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
        },
        success: function(a){
            $("#mostrar_pendientes_conductor").html(a);
        },
        error: function(a){
            $('#mostrar_pendientes_conductor').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}
function mostrar_pendientes_vehiculos()
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "mostrar_pendientes_vehiculos=1",
        beforeSend: function(){
            $("#mostrar_pendientes_vehiculos").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
        },
        success: function(a){
            $("#mostrar_pendientes_vehiculos").html(a);
        },
        error: function(a){
            $('#mostrar_pendientes_vehiculos').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}
function tomar_foto()
{
    navigator.camera.getPicture(onSuccess, onFail, { quality: 50,
        destinationType: Camera.DestinationType.FILE_URI  });

    function onSuccess(imageURI) {
        var image = document.getElementById('fotoLocal');
        image.src = imageURI; 
        localStorage.setItem("foto_final_conductor",imageURI);
    }

    function onFail(message) {
        alert('Failed because: ' + message);
    }

}
function subirImagen(fileURL) {   
    var options = new FileUploadOptions();
    options.fileKey = "imagen";
    options.fileName = fileURL.substr(fileURL.lastIndexOf('/') + 1);
    var ft = new FileTransfer();
    ft.upload(fileURL, encodeURI(url_pagina+"/reconocimiento_pae/php/upload.php"), uploadSuccess, uploadFail, options);
}
function uploadSuccess(r) {
    ocultar_mensaje(); 
    var primer_apellido = $("#primer_apellido").val();
    var segundo_apellido = $("#segundo_apellido").val();
    var primer_nombre = $("#primer_nombre").val();
    var segundo_nombre = $("#segundo_nombre").val();
    var documento_identidad = $("#documento_identidad").val();
    var vencimiento_licencia = $("#vencimiento_licencia").val();
    var foto_conductor = r.response;
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "subir_conductor="+1+"&primer_apellido="+primer_apellido+"&segundo_apellido="+segundo_apellido+"&primer_nombre="+primer_nombre+"&segundo_nombre="+segundo_nombre+"&documento_identidad="+documento_identidad+"&vencimiento_licencia="+vencimiento_licencia+"&url_foto="+foto_conductor,
        beforeSend: function(){
            $("#registro_conductor").html('<br><br><br><br><center><img src="'+url_pagina+'reconocimiento_pae/assets/img/progress2.gif"/ width="50%"><br><h1 class="titulod">GUARDANDO</h1></center>');
        },
        success: function(a){
            if (a != 1) 
            {
                $("#registro_conductor").html('<br><br><br><br><center><img src="'+url_pagina+'reconocimiento_pae/assets/img/success.png" width="10%"><br><h1 class="titulod">DATOS GUARDADOS CORRECTAMENTE</h1></center>');
                localStorage.removeItem("foto_final_conductor");
            }
            else
            {
                $("#registro_conductor").html('<br><br><br><br><center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="10%"><br><h1 class="titulod">LOS DATOS NO SE GUARDARON</h1></center>');
            }
        },
        error: function(a){
            $('#registro_conductor').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }

    })
}
function uploadFail(error) {
// alert("An error has occurred: Code = " + error.code+ " upload error source " + error.source+" upload error target " + error.target);
mostrar_mensaje();
}
function mostrar_mensaje(){
    $( "#pendientes_transporte" ).fadeIn( "slow" );
}
function ocultar_mensaje(){
    $( ".error" ).fadeOut( "slow" );
    $( ".error").css("display","none");
}


function asignar_vehiculo(documento_identidad)
{
    $("#contenedor").css("display","none");
    $("#vehiculos_disponibles").css("display","");
// $("#vehiculos_disponibles").html(documento_identidad+"<button onclick='mostrar_contenedor()'>prueba</button>");
jQuery.ajax({
    type: "POST",
    url: url_pagina+"reconocimiento_pae/php/consultas.php",
    data: "mostrar_vehiculos=1&documento_conductor="+documento_identidad,
    beforeSend: function(){
        $("#vehiculos_disponibles").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
    },
    success: function(a){
        $("#vehiculos_disponibles").html(a);
        swiper();
    },
    error: function(a){
        $('#vehiculos_disponibles').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
    }
})
}
function mostrar_sedes(documento_conductor,id_placa)
{
    var placa = $("#"+id_placa).val();
    $("#mostrar_sedes").css("display","");
    $("#contenedor_vehiculo").css("display","none");
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "consulta_sedes=1&placa="+placa+"&documento_conductor="+documento_conductor,
        beforeSend: function(){
            $("#mostrar_sedes").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
        },
        success: function(a){
        // $(".icon-menu").css("display","none");
        $("#mostrar_sedes").html(a);
        swiper();
        calendario_soat();
    },
    error: function(a){
        $('#mostrar_sedes').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
    }
})
}
function ver_sedes(id)
{
    $("#contenedor").css("display","none");
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "ver_sedes=1&id_asignacion="+id,
        beforeSend: function(){
            $("#vehiculos_disponibles").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
        },
        success: function(a){
            $("#vehiculos_disponibles").html(a);
            $("#vehiculos_disponibles").css("display","");
        },
        error: function(a){
            $('#vehiculos_disponibles').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}
function volver_consulta()
{
    $("#vehiculos_disponibles").css("display","none");
    $("#contenedor").css("display","");
}
function sede_seleccionada(sede_consecutivo,id)
{
    var checkbox = '#'+sede_consecutivo;

    if( $(checkbox).prop('checked') == true) {
        jQuery.ajax({
            type: "POST",
            url: url_pagina+"reconocimiento_pae/php/consultas.php",
            data: "insertar_sede=1&sede_consecutivo="+sede_consecutivo+"&id="+id,
            beforeSend: function(){
                $("#carga_sede").html('<img src="'+url_pagina+'reconocimiento_pae/assets/img/progress2.gif"/><br><p class="tmf">Actualizando</p>');
            },
            success: function(a){
                if (a == 1){
                    $("#carga_sede").html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="5%"><br><h1 class="titulod">Los datos no se actualizaron</h1></center>');
                }
                else{
                    $("#carga_sede").css("display","none");
                }
            },
            error: function(a){
                $('#carga_sede').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
            }
        })
    }
    else{
        jQuery.ajax({
            type: "POST",
            url: url_pagina+"reconocimiento_pae/php/consultas.php",
            data: "eliminar_sede=1&sede_consecutivo="+sede_consecutivo+"&id="+id,
            beforeSend: function(){
                $("#carga_sede").html('<img src="'+url_pagina+'reconocimiento_pae/assets/img/progress2.gif"/><br><p class="tmf">Actualizando</p>');
            },
            success: function(a){
                if (a == 1){
                    $("#carga_sede").css("display","");
                    $("#carga_sede").html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="15%"><br><h1 class="titulod">Los datos no se actualizaron</h1></center>');
                }
                else{
                    $("#carga_sede").css("display","none");
                }
            },
            error: function(a){
                $('#carga_sede').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
            }
        })
    }
}
function estudiantes_beneficiarios()
{
    $("#carga").css("display","none");
    $(".contenedor").css("display","");
    $(".contenedor").attr("id","estudiantes_beneficiarios");
    $("#estudiantes_beneficiarios").html("<center><br><h2>SELECCIONE UNA SEDE</h2><br><br><br><div id='sede_asig'></div></center>");
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
    sede_asig();
}

function sede_asig()
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "sede_asig=1",
        beforeSend: function(){
            $("#sede_asig").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
        },
        success: function(a){

            $("#sede_asig").html(a);
        },
        error: function(a){
            $('#sede_asig').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }

    })
}

function sedes_asignadas(sede_consecutivo)
{
    $("#sede_asig").html(sede_consecutivo);
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "sedes_asignadas=1&sede_consecutivo="+sede_consecutivo,
        beforeSend: function(){
            $("#sede_asig").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
        },
        success: function(a){
            $("#estudiantes_beneficiarios").html("<center><div id='sede_asig'></div></center>");
            $("#sede_asig").html(a);
        },
        error: function(a){
            $('#estudiantes_beneficiarios').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}

function select_vehiculo()
{
    var placa="ninguna";
    var sede_consecutivo = $("#sede_consecutivo").val();
    var nombre=document.getElementsByName("selector");
    for(var i=0;i<nombre.length;i++)
    {
        if(nombre[i].checked)
            placa=nombre[i].value;
    }
    if (placa != "ninguna") 
    {
        jQuery.ajax({
            type: "POST",
            url: url_pagina+"reconocimiento_pae/php/consultas.php",
            data: "mostrar_estudiantes_sede=1&placa="+placa+"&sede_consecutivo="+sede_consecutivo,
            beforeSend: function(){
                $("#sede_asig").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
            },
            success: function(a){
                $("#sede_asig").html(a);
            },
            error: function(a){
                $('#sede_asig').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
            }
        })
    }
}

function actualizar_vehiculo_estudiante(matri_id)
{
    var placa = $("#placa").val();

    var checkbox = '#'+matri_id;
    if( $(checkbox).prop('checked') == true) 
    {
        jQuery.ajax({
            type: "POST",
            url: url_pagina+"reconocimiento_pae/php/consultas.php",
            data: "actualizar_vehiculo_estudiante=1&placa="+placa+"&matri_id="+matri_id+"&tipo=1",
            beforeSend: function(){
                $("#carga_dato").html('<img src="'+url_pagina+'reconocimiento_pae/assets/img/progress2.gif"/><br><p class="tmf">Actualizando</p>');
            },
            success: function(a){
                if (a == 1) 
                {
                    $("#carga_dato").html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="8%"><br><p class="tmf">Los datos no se actualizaron</p></center>');
                }
                else
                {
                    $("#carga_dato").html('');
                }
            },
            error: function(a){
                $('#carga_dato').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
            }
        })
    }
    else{
        jQuery.ajax({
            type: "POST",
            url: url_pagina+"reconocimiento_pae/php/consultas.php",
            data: "actualizar_vehiculo_estudiante=1&matri_id="+matri_id+"&tipo=2",
            beforeSend: function(){
                $("#carga_dato").html('<img src="'+url_pagina+'reconocimiento_pae/assets/img/progress2.gif"/><br><p class="tmf">Actualizando</p>');
            },
            success: function(a){
                if (a == 1) 
                {
                    $("#carga_dato").html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="8%"><br><p class="tmf">Los datos no se actualizaron</p></center>');
                }
                else
                {
                    $("#carga_dato").html('');
                }
            },
            error: function(a){
                $('#carga_dato').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
            }
        })
    }
}

/////Beneficiarios pae
function consulta_beneficiario()
{
    // $("#carga").css("display","none");
    // $(".contenedor").css("display","");
    // $(".contenedor").attr("id","consulta_beneficiario");
    // $("#consulta_beneficiario").html("consulta_beneficiario");
    // $(".perspective").removeClass("animate");
    // $(".perspective").removeClass("modalview");

    $(".contenedor").css("display","");
    $("#carga").css("display","none");
    $(".contenedor").css("margin","0");
    $(".contenedor").attr("id","consulta_estudiante");
    $("#consulta_estudiante").html("<br><br><br><br><center><h2>Consulta de Benficiarios Pae<br></h2><br><br><br><div class='select-style'><select id='tipo_busqueda_estudiante' name='tipo_busqueda_estudiante' onchange='consulta_estudiantes_beneficiario_por(this.value);'><option value=''>...</option><option value='1'>Por Grupo</option><option value='2'>Por Estudiante</option><option value='3'>Todos</option></select></div><br><br><br><br><div id='container_estudiante'></div></center>");
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
    $(".perspective").removeClass("modalview");
}

////Imprime Select
function consulta_estudiantes_beneficiario_por(consulta)
{
     if (consulta == 1) 
    {
        jQuery.ajax({
            method: "POST",
            url: url_pagina+"reconocimiento_pae/php/consultas.php",
            data: "carga_grupo_estudiante_beneficiario=1&usuario="+localStorage.getItem("username")+"&rol="+localStorage.getItem("rol")+"&tipo_busqueda=beneficiario_pae&grupo=si",
            beforeSend: function(){
                $("#carga").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
            },
            success: function(a){
                $("#carga").html('');
                $("#container_estudiante").html(a);
            },
            error: function(a){
                $('#container_estudiante').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
            }

        })
    }
    else if (consulta == 2)
    {
        $("#consulta_estudiante").html("<div><form action='' onsubmit='carga_estudiante_individual(); return false;'><br><br><center><table><tr><td><h2>Consulta Individual de Estudiantes</h2></td></tr></center><tr><td><center><input type='text' placeholder='Primer Apellido' class='username' id='primer_apellido' onkeypress='return soloLetras(event)' ></center></td></tr><tr><td><center><input type='text' placeholder='Segundo Apellido' class='username' id='segundo_apellido' onkeypress='return soloLetras(event)'></center><tr><td><center><input type='text' placeholder='Primer Nombre' class='username' id='primer_nombre' onkeypress='return soloLetras(event)'></center></td></tr></td></tr><tr><td><center><input type='text' placeholder='Segundo Nombre' class='username' id='segundo_nombre' onkeypress='return soloLetras(event)'></center></td></tr><tr><td><center><input type='text' placeholder='Documento de Identidad' class='username' id='documento_identidad' onkeypress='return justNumbers(event);'></center></td></tr></table><center><br><br><button type='submit' name='guardar' class='boton'>Consultar</button></center><center></form></div>");
    }
    else if (consulta == 3)
    {
         jQuery.ajax({
            method: "POST",
            url: url_pagina+"reconocimiento_pae/php/consultas.php",
            data: "carga_grupo_estudiante_beneficiario=1&usuario="+localStorage.getItem("username")+"&rol="+localStorage.getItem("rol")+"&tipo_busqueda=beneficiario_pae&grupo=no",
            beforeSend: function(){
                $("#carga").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
            },
            success: function(a){
                $("#carga").html('');
                $("#container_estudiante").html(a);
            },
            error: function(a){
                $('#container_estudiante').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
            }

        })
    }

}

function mostrar_estudiantes_beneficiarios_pae(id_grupo)
{
     jQuery.ajax({
       method: "POST",
            url: url_pagina+"reconocimiento_pae/php/consultas.php",
            data: "id_grupo_beneficiario="+id_grupo+"&tipo_beneficiario=beneficiario_pae",
        beforeSend: function(){
            $(".contenedor").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
        },
        success: function(a){
            $(".contenedor").html(a);
            swiper();
        },
        error: function(a){
            $('.contenedor').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }

    })
}


function subir_fecha(id)
{
    var fecha = $("#fecha_inicio").val();
    var documento_conductor = $("#documento_conductor").val();

    if (fecha == "") 
    {
        $("#terminar_proceso").html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="15%"><br><h1 class="titulod">Debe Ingresar la Fecha de Inicio</h1></center>');
    }
    else
    {
        jQuery.ajax({
            type: "POST",
            url: url_pagina+"reconocimiento_pae/php/consultas.php",
            data: "subir_fecha=1&fecha="+fecha+"&id="+id,
            beforeSend: function(){
                $("#terminar_proceso").html('<img src="'+url_pagina+'reconocimiento_pae/assets/img/progress2.gif"/><br><p class="tmf">Guardando</p>');
            },
            success: function(a){
                if (a == 1) 
                {
                    $("#terminar_proceso").html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="25%"><br><h1 class="titulod">Los datos no se actualizaron</h1></center>');
                }
                else
                {
                    $("#terminar_proceso").css("display","none");
                    $("#contenedor_total").css("display","none");
                    $("#"+documento_conductor).css("display","none");
                    $("#contenedor").css("display","");
                }
            },
            error: function(a){
                $('#terminar_proceso').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
            }
        })
    }
}
function consulta_vehiculos()
{
    $("#carga").css("display","none");
    $(".contenedor").css("display","");
    $(".contenedor").attr("id","consulta_vehiculos");
    $("#consulta_vehiculos").html("<div><form action='' onsubmit='carga_vehiculos(); return false;'><br><br><center><table><tr><td><h2>Consulta de Vehículos</h2></td></tr></center><tr><td><center><input type='text' placeholder='Placa' class='username' id='placa'></center></td></tr><tr><td><center><input type='text' placeholder='Vehículo' class='username' id='vehiculo'></center><tr><td><center><input type='text' placeholder='Modelo' class='username' id='modelo' onkeypress='return justNumbers(event)'></center></td></tr></td></tr><tr><td><center><input type='text' placeholder='Capacidad de Pasajeros' class='username' id='capacidad_pasajeros' onkeypress='return justNumbers(event)'></center></td></tr><tr><td><center><input type='text' placeholder='Fecha de vencimiento del SOAT' class='username' id='fecha_soat' readonly onclick='agregar_perspectiva();'><div id='fecha_soat'></div></center></td></tr><tr><td><center><input type='text' placeholder='Revisión Vehicular Mecánica y Gases' class='username' id='vencimiento_mecanica' readonly onclick='agregar_perspectiva();'><div id='vencimiento_mecanica'></div></center></td></tr></table><center><br><br><button type='submit' name='guardar' class='boton'>Consultar</button></center><center></form></div>");
    $(".perspective").removeClass("animate");
    $(".perspective").removeClass("modalview");
    calendario_soat();
}

function carga_vehiculos()
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "carga_vehiculos=1&placa="+$("#placa").val()+"&vehiculo="+$("#vehiculo").val()+"&modelo="+$("#modelo").val()+"&capacidad_pasajeros="+$("#capacidad_pasajeros").val()+"&vencimiento_soat="+$("#fecha_soat").val()+"&vencimiento_mecanica="+$("#vencimiento_mecanica").val(),
        beforeSend: function(){
            $("#consulta_vehiculos").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
        },
        success: function(a){
            $("#consulta_vehiculos").html(a);
            swiper();
        },
        error: function(a){
            $('#consulta_vehiculos').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}

function agregar_sedes(id_asignacion)
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "agregar_sedes=1&id_asignacion="+id_asignacion,
        beforeSend: function(){
            $("#sedes_asignadas").html('<br><center><img src="'+url_pagina+'reconocimiento_pae/img/carga_logo.gif" alt="logo sygescol" width="25%"><p class="tmf">Cargando Información</p></center>');
        },
        success: function(a){
            $("#sedes_asignadas").html(a);
        },
        error: function(a){
            $('#sedes_asignadas').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
        }
    })
}

function mostrar_contenedor()
{
    $("#vehiculos_disponibles").css("display","none");
    $("#contenedor").css("display","");
}
function mic_pae()
{
    window.open(url_pagina+'reconocimiento_pae/index.php', '_system', 'location=no');
}
//INICIA SYGESCOL DOCENTES REDIRECCIONAMIENTO DE ARCHIVOS
function inicio()
{
    window.location.href = "../index.html";
}
function consulta_y_registro()
{
$("#carga").css("display","none");    //ESCONDE EL ELEMENTO
window.location.href = "consulta_y_registro.html"; //REDIRECCIONA 
}
function control_asistencia()
{
$("#carga").css("display","none");   //ESCONDE EL ELEMENTO
window.location.href = "control_asistencia.html"; //REDIRECCIONA 
}
function interaccion_acudientes()
{
$("#carga").css("display","none");   //ESCONDE EL ELEMENTO
window.location.href = "interaccion_acudientes.html"; //REDIRECCIONA 
}
function observador_estudiante()
{
$("#carga").css("display","none");   //ESCONDE EL ELEMENTO
window.location.href = "observador_estudiante.html"; //REDIRECCIONA 
}
function recursos_institucionales()
{
$("#carga").css("display","none");   //ESCONDE EL ELEMENTO
window.location.href = "recursos_institucionales.html"; //REDIRECCIONA 
}
function alimentacion_escolar()
{
$("#carga").css("display","none");   //ESCONDE EL ELEMENTO
window.location.href = "alimentacion_escolar.html"; //REDIRECCIONA 
}
function transporte_escolar()
{
$("#carga").css("display","none");   //ESCONDE EL ELEMENTO
window.location.href = "transporte_escolar.html"; //REDIRECCIONA 
}
/////////////////////////////////////////////////////////
function pae()
{

    $(".wrapper").css("display","none");
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "username="+localStorage.getItem("username")+"&password="+localStorage.getItem("password"),
        beforeSend: function(){
          $('#container').html('<br><center><p class="tmf">Cargando Información</p></center>');
      },
      success: function(a){
          $(".wrapper").css("display","");
          $("#container").html("");
          $("#carga").css("display","");
          if (a == 1) 
          {
              $("#pae").css("display","");
          }
          else
          {
              $("#pae").css("display","none");
          }
      },
      error: function(a)
      {
        $('#container').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
    }            
})

    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "usertransporte="+localStorage.getItem("username"),
        beforeSend: function(){
          $(".wrapper").css("display","none");
          $('#container').html('<br><center><p class="tmf">Cargando Información</p></center>');
      },
      success: function(a){
          $(".wrapper").css("display","");
          $("#container").html("");
          $("#carga").css("display","");
          if (a == 1) 
          {
              $("#transporte").css("display","");
              div_pendientes_conductores();
          }
          else
          {
              $("#transporte").css("display","none");
          }
      },
      error: function(a)
      {
        $('#container').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
    }      
})
}
function div_pendientes_conductores()
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "div_pendientes_conductores=1",
        success: function(a){
            if (a == 1) 
            {
             mostrar_mensaje();
             navigator.notification.beep(2);
             navigator.vibrate(300);
             setTimeout(function(){
                navigator.vibrate(300);
            }, 500);
         }
     }
 })
}
//FINALIZA SYGESCOL DOCENTES
function justNumbers(e)
{
    var keynum = window.event ? window.event.keyCode : e.which;
    if ((keynum == 8) || (keynum == 46))
        return true;
    return /\d/.test(String.fromCharCode(keynum));
}
function soloLetras(e){
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
    especiales = "8-37-39-46";
    tecla_especial = false
    for(var i in especiales){
        if(key == especiales[i]){
            tecla_especial = true;
            break;
        }
    }
    if(letras.indexOf(tecla)==-1 && !tecla_especial){
        return false;
    }
}
function soloLetrasv(e){
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = "abcdefghijklmnopqrstuvwxyz-1234567890 ";
    especiales = "8-37-39-46";
    tecla_especial = false
    for(var i in especiales){
        if(key == especiales[i]){
            tecla_especial = true;
            break;
        }
    }
    if(letras.indexOf(tecla)==-1 && !tecla_especial){
        return false;
    }
}
function soloLetrasv2(e){
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = "abcdefghijklmnopqrstuvwxyz-1234567890";
    especiales = "8-37-39-46";
    tecla_especial = false
    for(var i in especiales){
        if(key == especiales[i]){
            tecla_especial = true;
            break;
        }
    }
    if(letras.indexOf(tecla)==-1 && !tecla_especial){
        return false;
    }
}
//INICIA LA PAGINA PRINCIPAL
function sygescol_online()
{
    if (localStorage.getItem("rol") == "DOCENTE") 
    {
       window.location.href = "sections_docente/sygescol_online.html"; 
   }

}
function cerrar_sesion()
{
    localStorage.removeItem("username");
    localStorage.removeItem("password");
    localStorage.removeItem("rol");
    window.location.href = "../index.html";
}

function checkCookie() {

    if (localStorage.getItem("username") == null) 
    {
        window.location.href = "../index.html";
    }
    else
    {
        if (localStorage.getItem("rol") != "DOCENTE") 
        {
            $("#inasistencias").css("display","none");
            $("#pae").css("display","none");
        }

        if (localStorage.getItem("rol") == "SOPORTE") 
        {
            $("#inasistencias").css("display","");
            $("#pae").css("display","");
        }
        $(".wrapper").css("display","none");
        jQuery.ajax({
            type: "POST",
            url: url_pagina+"reconocimiento_pae/php/valida_session.php",
            data: "usuario="+localStorage.getItem("username"),
            beforeSend: function(){
                $("#carga").html('<center><br><p class="tmf">CARGANDO INFORMACIÓN</p></center>')
            },
            success: function(a){
                if (a != 0) 
                {
                    $("#carga").css("display","none");
                    $(".wrapper").css("display","");
                    $("#container").html('<br><br><br><br><center class="tmf"><h2>Bienvenido<br>'+a+"</h2><br><br><br><div id='foto'></div><br><br><br><div id='rol_mostrar'></center>")
                }
            },
            error: function(a){
                $('#container').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
            }
        }),
        jQuery.ajax({
            type: "POST",
            url: url_pagina+"reconocimiento_pae/php/valida_session.php",
            data: "usuario_foto="+localStorage.getItem("username")+"&rol_foto="+localStorage.getItem("rol"),
            beforeSend: function(){
                $("#carga").html('<center><br><p class="tmf">CARGANDO FOTO</p></center>')
            },
            success: function(a){
                if (a != 0) 
                {
                    $("#carga").css("display","none");
                    $("#showMenu").css("display","");
                    $("#foto").html(a);
                    $("#rol_mostrar").html("<h2>"+localStorage.getItem("rol")+"</h2>");
                }
                else if(a == 0)
                {
                    $("#carga").css("display","none");
                    $("#container").html('<br><br><br><br><center class="tmf"><h2>USTED YA NO TIENE ACCESO AL SISTEMA</h2>');
                    setTimeout(function(){
                        navigator.app.exitApp();
                    }, 2000);
                }
                else
                {
                    $("#container").html('<br><br><br><br><center class="tmf"><h2>DATA NOT FOUND</h2>');
                }
            },
            error: function(a){
                $('#container').html('<center><img src="'+url_pagina+'reconocimiento_pae/assets/img/error.png"/ width="7%"><br><h3 class="titulod">Hubo un error en el procesamiento de datos, favor intente nuevamente</h3></center>');
            }
        })
    }
}
//FINALIZA LA PAGINA PRINCIPAL
setInterval(verificar_prestamo, 800);
function verificar_prestamo()
{
    jQuery.ajax({
        type: "POST",
        url: url_pagina+"reconocimiento_pae/php/consultas.php",
        data: "verificar_prestamo=1&usuario="+localStorage.getItem("username"),
        success: function(a){
            if (a == 1) {
                $("#prestamo_verificado").fadeIn( "slow" );                
            }
        }
    })
}