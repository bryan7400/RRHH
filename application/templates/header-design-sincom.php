<?php

// Obtiene el menu de herramientas
$_herramientas = json_decode(@file_get_contents(storages . '/herramientas.json'), true);

// Obtiene los menus
$_menus = $db->select('m.*, p.archivos')->from('sys_permisos p')->join('sys_menus m', 'p.menu_id = m.id_menu')->where('p.rol_id', $_SESSION[user]['rol_id'])->where('m.id_menu != ', 0)->order_by('m.orden', 'asc')->fetch();

// Construye la barra de menus
$_menus = construir_menu_horizontal($_menus); 
 
?> 
<!DOCTYPE html> 
<html lang="es">  
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="mobile-web-app-capable" content="yes">
<title><?= $_institution["sigla"]; ?></title> 

<link rel="icon" type="image/png" href="<?= $_institution["icono"]; ?>">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/bootstrap/css/bootstrap.min.css">
<!--estilos de menu-->
<link rel="stylesheet" href="assets/themes/concept/assets/libs/css/style.css">
<!--iconos-->
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
<!--datatables-->
<link rel="stylesheet" href="<?= css; ?>/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/datatables/css/dataTables.bootstrap4.css"> 

<!--selectize-->
<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">

<!--<link rel="stylesheet" href="assets/themes/concept/assets/vendor/bootstrap/css/educheck.css">-->
<!--color de algunas cosas-->
<link rel="stylesheet" href="<?= css; ?>/educheck.css"> 

<link rel="stylesheet" href="assets/themes/concept/assets/vendor/datatables/css/buttons.bootstrap4.css">
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/datatables/css/fixedHeader.bootstrap4.css">
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/datatables/css/select.bootstrap4.css">

<link rel="stylesheet" href="assets/themes/concept/assets/vendor/jquery-gritter/css/jquery.gritter.css">
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/alertify/css/alertify.min.css">
<link href="assets/themes/concept/assets/vendor/fonts/circular-std/style.css" rel="stylesheet"> 
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/charts/chartist-bundle/chartist.css">  
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/charts/morris-bundle/morris.css">  
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css"> 
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/fonts/flag-icon-css/flag-icon.min.css">
    
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/fonts/simple-line-icons/css/simple-line-icons.css">
<!--<link rel="stylesheet" href="assets/themes/concept/assets/vendor/charts/c3charts/c3.css"> -->
<link rel="stylesheet" href='assets/themes/concept/assets/vendor/full-calendar/css/fullcalendar.css' rel='stylesheet' />
<link rel="stylesheet" href='assets/themes/concept/assets/vendor/full-calendar/css/fullcalendar.print.css' rel='stylesheet' media='print' />

<!--usados-->
<script src="<?= js; ?>/jquery.min.js"></script>
<script src="<?= js; ?>/bootstrap.min.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/jquery.maskedinput.min.js"></script>
<!--motificaciones-->
<!--<script src="<?= js; ?>/push.min.js"></script>-->

<!-- <script src="assets/themes/concept/assets/vendor/alertify/js/alertify.min.js"></script> -->
<!-- <script src="assets/themes/concept/assets/vendor/datatables/js/data-table.js"></script>-->
<script src="assets/themes/concept/assets/vendor/jquery/jquery.validate.js"></script>
<script src="assets/themes/concept/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
<script src="assets/themes/concept/assets/vendor/jquery/jquery.dataTables.min.js"></script>


<title>Educheck</title>
<!-- jquery 3.3.1 -->
<script src="<?= js; ?>/bootbox.min.js"></script>
<script src="assets/themes/concept/assets/vendor/jquery/jquery.dataFilters.min.js"></script>
<script src="assets/themes/concept/assets/vendor/jquery-gritter/js/jquery.gritter.min.js"></script>
<script src="assets/themes/concept/assets/vendor/alertify/js/alertify.min.js"></script>
<!--traduccion de data table-->
<script src="assets/themes/concept/assets/vendor/educheck/js/educheck.js"></script>
<script src="assets/themes/concept/assets/vendor/datatables/js/data-table.js"></script>
<script src="assets/themes/concept/assets/vendor/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="assets/themes/concept/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
<script src="assets/themes/concept/assets/vendor/slimscroll/jquery.slimscroll.js"></script> 
<script src="assets/themes/concept/assets/libs/js/main-js.js"></script>
<script src="assets/themes/concept/assets/vendor/popover/matrix.popover.js"></script>
<script>
    function btnbars(){
         //nav-left-sidebar= contenido todo el menu
         //navbar-collapse=texto de menu
         //alert('aqui');
         //$('.nav-left-sidebar').hasClass('sidebar-oculto');
         if($('.nav-left-sidebar').hasClass('sidebar-oculto')){
             
             //alert('tien oculto');
             $('.nav-left-sidebar').removeClass('sidebar-oculto').addClass('sidebar-visto');
             $('.dashboard-wrapper').css('margin-left','264px');
 
         }else{
             $('.nav-left-sidebar').removeClass('sidebar-laterial').addClass('sidebar-oculto');
            // alert('nooo oculto');
             $('.dashboard-wrapper').css('margin-left','0');
         }
    }   
 </script>
 <!--menu oculta boton-->
 <style>
     .sidebar-oculto {
        position: fixed;
        width: 0!important;
         }
    .sidebar-visto {
    width: 233px;
     }
     
     /*contenudo de menu*/
     .navbarNone{ 
        display: none; 
     }
     .show{
         display: flex; 
     }
     /*el boton nuevio*/
     .btnhidemenu{
        display: none;
     }
      @media (min-width: 992px){
      /* #navbarNav{ 
        display: block!important; 
     }*/
     }
  
     /*activado cuando es mayor a 772*/
     @media (min-width: 772px){
         
         .btnhidemenu{
        display: block;
     }
         .navbar-toggler{
          display: none;
         }    
            #navbarNav{
         /*flex-basis: 100%;
        -ms-flex-positive: 1;
        flex-grow: 1;
        -ms-flex-align: center;
        align-items: center;*/
            display: flex;
        -ms-flex-preferred-size: auto;
        flex-basis: auto;
        /*background: red; */
         }
     }
</style>
</head>
<body>
    <!-- ============================================================== -->
    <!-- main wrapper -->
    <!-- ============================================================== -->
    <div class="dashboard-main-wrapper">
        <!-- ============================================================== -->
        <!-- navbar -->
        <!-- ============================================================== -->
        
        <div class="dashboard-header">
            <nav class="navbar navbar-expand-lg bg-white fixed-top">
               <span onclick="btnbars()" class="btnhidemenu"><i class="btn fa fa-bars" style="font-size:22px"></i> </span>
               
               <button class="navbar-toggler b-none" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="btn fa fa-bars" style="font-size:22px"></span>
                </button>
                    
                    
                <!--<a href="<?= index_private; ?>" class="navbar-brand">-->
                       
                <!--        <img src="<?= imgs . '/'.$_institution["logo_color"]; ?>" height="30" style="margin-top: -5px;">-->
                <!--</a>-->
                <a href="<?= index_private; ?>" class="navbar-brand">
                       
                       <img src="<?= imgs . '/'.$_institution["logo_color"]; ?>" alt="Image" class="img-fluid" width="40px" height="50px"> <font color="black" size="4px"> <?= $_institution["nombre_corto"]; ?></font>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse " id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto navbar-right-top">
                        <li class="nav-item">
                            <div id="custom-search" class="top-search-bar">
                                <input class="form-control" type="text" placeholder="Buscar...">
                            </div>
                        </li>
                        <li class="nav-item dropdown notification">
                            <a class="nav-link nav-icons" href="#" id="navbarDropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-fw fa-envelope"></i> <span class="aca ponemos la clase indasdicator"></span></a>
                            <ul class="dropdown-menu dropdown-menu-right notification-dropdown">
                                <li>
                                    <div class="notification-title"> Mensajes</div>
                                    <div class="notification-list">
                                        <div class="list-group">
                                            <a href="#" class="list-group-item list-group-item-action active">
                                                <div class="notification-info">
                                                    <div class="notification-list-user-img"><img src="assets/imgs/avatar.jpg" alt="" class="user-avatar-md rounded-circle"></div>
                                                    <div class="notification-list-user-block"><span class="notification-list-user-name">Jeremy Rakestraw</span>accepted your invitation to join the team.
                                                        <div class="notification-date">2 min ago</div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="#" class="list-group-item list-group-item-action">
                                                <div class="notification-info">
                                                    <div class="notification-list-user-img"><img src="assets/imgs/avatar.jpg" alt="" class="user-avatar-md rounded-circle"></div>
                                                    <div class="notification-list-user-block"><span class="notification-list-user-name">John Abraham </span>is now following you
                                                        <div class="notification-date">2 days ago</div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="#" class="list-group-item list-group-item-action">
                                                <div class="notification-info">
                                                    <div class="notification-list-user-img"><img src="assets/imgs/avatar.jpg" alt="" class="user-avatar-md rounded-circle"></div>
                                                    <div class="notification-list-user-block"><span class="notification-list-user-name">Monaan Pechi</span> is watching your main repository
                                                        <div class="notification-date">2 min ago</div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="#" class="list-group-item list-group-item-action">
                                                <div class="notification-info">
                                                    <div class="notification-list-user-img"><img src="assets/imgs/avatar.jpg" alt="" class="user-avatar-md rounded-circle"></div>
                                                    <div class="notification-list-user-block"><span class="notification-list-user-name">Jessica Caruso</span>accepted your invitation to join the team.
                                                        <div class="notification-date">2 min ago</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="list-footer"> <a href="#">Ver Todos los mensajes</a></div>
                                </li>
                            </ul>
                        </li>

                        <!-- -->
                        <li class="nav-item dropdown notification" >
                          <a class="nav-link nav-icons" href="#" id="navbarDropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="leermensajes()"><i class="fas fa-fw fa-bell"></i> <span class="indicator"><b></b></span></a>
                          <ul class="dropdown-menu dropdown-menu-right notification-dropdown">
                              <li>
                                  <div class="notification-title">Notificaciones</div>
                                  <div class="notification-list">
                                      <div class="list-group">
                                          <a href="#" class="list-group-item list-group-item-action active">
                                              <div class="notification-info">
                                                  <div class="notification-list-user-img"><img src="assets/imgs/avatar.jpg" alt="" class="user-avatar-md rounded-circle"></div>
                                                  <div class="notification-list-user-block"><span class="notification-list-user-name"><button onclick="crearbdfirebase()">Crear Base de datos en firebase</button></span> 
                                                      <div class="notification-date"></div>
                                                  </div>
                                              </div>
                                          </a>
                                         
                                      </div>
                                  </div>
                              </li>
                              <li>
                                <div class="list-footer"> <a href="#">Ver todas las Notificaciones</a></div>
                              </li>
                          </ul>
                      </li>
                        <!--- Usuario logueado -->
                        <li class="nav-item dropdown connection">
                            <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?= ($_user['avatar'] == '') ? imgs . '/avatar.jpg' : files . '/profiles/' . $_user['avatar']; ?>" alt="" class="user-avatar-md rounded-circle"><i> <?= escape($_user['username']); ?></i></a>
                            <ul class="dropdown-menu dropdown-menu-right connection-dropdown">

                                <li class="connection-list">
                                    <div class="row">
                                        <div class="nav-user-info col-sm-12 col-12">
                                            <div align="center">
                                                <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?= ($_user['avatar'] == '') ? imgs . '/avatar.jpg' : files . '/profiles/' . $_user['avatar']; ?>" width="100" height="100" alt="foto" style="width:100px;moz-border-radius:30%;khtml-border-radius:30%;o-border-radius:30%;webkit-border-radius:30%;ms-border-radius:50%;border-radius:50%;"> </a>
                                                <h5 class="mb-0 text-white nav-user-name"><?= escape($_user['username']); ?></h5>
                                                <span class="status"></span><span class="">En linea</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <a class="dropdown-item" href="#"></a>
                                        <a class="dropdown-item" href="?/perfil/mostrar"><i class="fas fa-user mr-2"></i>Mi Perfil</a>
                                        <a class="dropdown-item" href="#"><i class="fas fa-cog mr-2"></i>Configuraciones</a>
                                        <a class="dropdown-item" href="?/<?= site; ?>/salir"><i class="fas fa-power-off mr-2"></i>Cerrar sesion</a>
                                    </div>
                                </li>                                     
                            </ul>
                        </li>                        
                    </ul>
                </div>
            </nav>
        </div>
        <!-- ============================================================== -->
        <!-- end navbar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- left sidebar -->
        <!-- ============================================================== -->
        <div class="nav-left-sidebar sidebar-dark">
            <div class="menu-list">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <!--<a class="d-xl-none d-lg-none" href="#">Menú</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>-->
                    <div class="collapse navbar-collapse navbarNone" id="navbarNav">
                        <ul class="navbar-nav flex-column">
                            <li class="nav-divider">
                                Menú <span class="badge badge-warning">GESTIÓN <?= $_gestion['gestion']; ?></span>
                            </li>
                            
                            <li class="nav-item">
                            	<?php if (environment == 'development' && $_herramientas && ($_user['rol_id'] == 1 || $_user['rol_id'] == 11|| $_user['rol_id'] == 12)  && $_user['visible'] == 'n') : ?>
                                <a class="nav-link active" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-0" aria-controls="submenu-1">
                                <i class="fa fa-fw fa-user-circle"></i>Desarrollo 
                                <span class="badge badge-success">6</span>
                                </a>
                                <div id="submenu-0" class="collapse submenu" style="">
                                    <ul class="nav flex-column">
                                    	<?php foreach ($_herramientas as $_herramienta) : ?>
                                        <li class="nav-item">
                                            <a class="nav-link" href="<?= $_herramienta['ruta']; ?>"><?= $_herramienta['menu']; ?></a>
                                        </li>
                                        <?php endforeach ?> 
                                    </ul>
                                </div>
                            </li>
                            <?php endif ?> 
							<?= $_menus; ?> 
                        </ul>
                    </div>
                </nav>
            </div>
        </div>



        <!-- ============================================================== -->
        <!-- end left sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->
        <div class="dashboard-wrapper">
            <div class="dashboard-ecommerce">
                <div class="container-fluid dashboard-content ">
   <!--notificaciones educheck-->
 <style>
     .indicator2 b{
        /*top: 2.5em; */
        color: #fff;
         font-size: .8em;
     }.indicator2{
         width: 1em!important;
        height: 1em!important; 
         top: 0.2em!important; 
        right: 1em!important; 
         display: flex!important; 
        align-items: center!important; 
        justify-content: center!important; 
     }                   
</style>
<script src="https://www.gstatic.com/firebasejs/7.2.3/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.3/firebase-firestore.js"></script>


<!-- <input type="text" id="cc_msg_new" value="0"/>-->
 <input type="hidden" value="<?=$_user['persona_id']?>" id="inp_id_user"> 
 <input type="hidden" value="<?=$_user['rol_id']?>" id="inp_rol_user"> 

 <!-- <script>
//instrucciones:  anadir funcion en: <a class="nav-link nav-icons" href="#" id="navbarDropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="leermensajes()">...
//crear boton de inicio en  <span class="notification-list-user-name"><button onclick="crearbdfirebase()">Crear Base de datos en firebase</button></span>
//crear b y asegurarse ue sea el unico indicator: <span class="indicator"><b></b></span>
     //iniciar();//iniciar ajax
var idfirebase='';  
var idusuario='';  
firebase.initializeApp({
  apiKey: "AIzaSyAehJwq_n1Yk34Hq-Tg8ip0pUdvA_Iu96Y",
  authDomain: "checkquino-3ce70.firebaseapp.com",
  projectId: "checkquino-3ce70"
});

var db = firebase.firestore();

 db.collection("users").onSnapshot((querySnapshot) => {
    querySnapshot.forEach((doc) => {
        console.log('::::::FOREACH onSnapshot:::::::');
        $('.notification-list').html('<div class=" text-center"><b>Sin mensajes disponibles</b></div>');
        
      //$('.notification-list').html(''); 
        idfirebase=`${doc.id}`;//id para todo el sistema
        rol_id=`${doc.data().rol_id}`;//id para todo el sistema
        titulo=`${doc.data().titulo}`;//id para todo el sistema
        desc=`${doc.data().desc}`;//id para todo el sistema
        fecha=`${doc.data().fecha}`;//id para todo el sistema
        personas_id=`${doc.data().personas_id}`;//id para todo el sistema
        //alert('rol'+rol_id);
        iniciar(rol_id,titulo,desc,fecha,personas_id);//listar notificaciones1 manda consulta son solo de sus usuario
        //nooo se cargara aunque no se para el usuario $('.notification-list').prepend('<div class="list-group"> <a href="#" class="list-group-item list-group-item-action active"> <div console.dir(obj);lass="notification-info"> <div class="notification-list-user-img"><img src="assets/imgs/avatar-2.jpg" alt="" class="user-avatar-md rounded-circle"></div> <div class="notification-list-user-block"><span class="notification-list-user-name">'+`${doc.data().titulo}`+'</span>'+`${doc.data().desc}`+' <div class="notification-date">'+`${doc.data().fecha}`+'</div> </div>  </div>  </a>  </div>'); 
    });
});

 //en caso de inicio sin datos iniciales en firebase
function crearbdfirebase(){
    
       //if(ini<1){
         alert('CREANDO NUEVO');
         //CREAR a firestore//:::::::::::::::::::::::
        db.collection("users").add({
            titulo: 'titulo',
            desc: 'descripcion',
            fecha: 'fecha',
            rol_id: 'rol_id',
            id_comunicado:'id_comunicado',
            personas_id:'1'
        })
        .then(function(docRef) {
            console.log("EXITO Document CREADO   ID: ", docRef.id); 
        })
        .catch(function(error) {
            console.error("Error adding document: ", error);
        }); 
 
     //}else{
     //   alert('NO CREAR NUEVO');
     //}   
        
}
//al momento de editar o crear comunicado se llama esta funcion
 function notificarfire(titulo,descripcion,fecha,rol_id,id_comunicado,personas_id){
//:::::listar:::::::::
   //db.collection("users").onSnapshot((querySnapshot) => {
   // querySnapshot.forEach((doc) => {
       // console.log(`${doc.id} => ${doc.data().first}`);
    
     //idfirebase=`${doc.data().usuarios}`;   
         //EDITAR:::::::::::::::::::::
     //var idfirebase de header-design.php
     //alert(idfirebase+'actualisar en crear');
         var washingtonRef=  db.collection("users").doc(idfirebase);//`${doc.id}`);
         return washingtonRef.update({
            titulo:titulo,
            desc: descripcion, 
            rol_id: rol_id, 
            personas_id: personas_id, 
            fecha: fecha, 
            id_comunicado: id_comunicado
         }).then(function(){
             console.log('Editado succesfull firebase'); 
             
         }).catch(function(error){
             console.log('error',error);
         }); //:::::::::::::::::::::::::::::::
        
  
    //}); });   
 
 }
  
 //lista las notificaciones en html 

//iniciar(idusuario);//listar notificaciones1
var cc_msg_new=0;
var leidos=0;//num mensajes que estaba
//var n_inicios=0;
function iniciar(rol_id,titulo,desc,fecha,personas_id){
    var id_usuario=$('#inp_id_user').val();
    var array_usuario =personas_id.split(",");
    
    var rol_user=$('#inp_rol_user').val();
    var array_rol =rol_id.split(",");
    //alert('array_rol:'+array_rol);
    var sino=false;//mensajes si es para usuario
    //ver si es para usuario especifico
    array_usuario.forEach(function(elemento, indice){//Indice: indice  Valor:  elemento
        console.log('arrayUsuario->'+elemento+' act:'+id_usuario);
        if(elemento==id_usuario){
          alertify.success('<span class="icon-user-following" style="color:black; background:#d7f70c;padding:0.6em;border-radius:50%;"></span> NUEVO COMUNICADO PERSONAL');
            sino=true; 
         }
    });
    //ver si es para grupo perteneciente
    array_rol.forEach(function(elemento, indice){//Indice: indice  Valor:  elemento 
        console.log('array_rol->'+elemento+' act:'+rol_user);
        if(elemento==rol_user){
          alertify.success('<span class="icon-people" style="color:#fff; background:#4cc8e0;padding:0.6em;border-radius:50%;"></span> NUEVO COMUNICADO GRUPAL');
           //alertify.success('NUEVO COMUNICADO GRUPAL');
          sino=true; 
         }
    });
    //el usuario esta en el array
    if(sino){//|| n_inicios==0){ 
     cc_msg_new=0;
     //n_inicios=1;
    //alert('Aqui no llega comunicados personales:heder design.php');
       // alert('hola nates ajax');
     $.ajax({
        url: '?/s-notificaciones/consultasnotificaciones',
        type:'POST',
        data: {accion:'listar_notificaciones'},//,
            //'id_componente':id_fila},
        dataType: 'JSON',
        success: function(resp){
          // alert(resp);
           if(resp.length>0){
               
            $('.notification-list').html('');
            for (var i = 0; i < resp.length; i++) {  
                $('.notification-list').prepend('<div class="list-group"> <a href="#" class="list-group-item list-group-item-action active"> <div console.dir(obj);lass="notification-info"> <div class="notification-list-user-img"><img src="assets/imgs/avatar-2.jpg" alt="" class="user-avatar-md rounded-circle"></div> <div class="notification-list-user-block"><span class="notification-list-user-name">'+resp[i]['nombre_evento']+'</span>'+resp[i]['descripcion']+' <div class="notification-date">'+resp[i]['fecha_registro']+'<span class="fa fa-check" style="float: right;"></span><span class="fa fa-check" style="float: right;"></span></div></div>     <div class="notification-list-user-block"> </div> </div>  </a>  </div>'); 
                 cc_msg_new++;//=resp.length; 
            }
               //$('#cc_msg_new').val(cc_msg_new);
                indicator(); 
                Notification.requestPermission(function(permission){
                    var notification = new Notification(titulo,{
                        body:desc,
                        icon:"https://bower.io/img/bower-logo.png"
                     });
                     notification.onclick = function(){
                     alert('notificacion');
                      windows.open('?/s-notificaciones/listar');
                     };

                }); 
            }
        }
     }); 
              
        }
 };  
function  leermensajes(){ 
   //$('#cc_msg_new').val();
    //alert('leer- contador'+cc_msg_new);
    leidos=cc_msg_new;
    indicator(); 
   }
function indicator(){
    //alert('in-contador'+cc_msg_new);
    var verif=cc_msg_new-leidos;//18-0/19-18/
    // alert('leidos:'+leidos+' contador:'+cc_msg_new+' icono:'+verif);
     if(verif!=0){
       $('.indicator').addClass('indicator2');
       $('.indicator').find('b').text(verif);
       $('.indicator').css('background-color','#ef172c'); 

       }else{
       $('.indicator').find('b').text('');
       $('.indicator').removeClass('indicator2',''); 
       $('.indicator').css('background-color','transparent');
      }
     
    //cc_msg_new=0;
}
     
     
     
     
/*//CREAR a firestore//:::::::::::::::::::::::
db.collection("users").add({
    titulo: "titulo",
    desc: "descripcion",
    fecha: 00/00/0000,
    usuarios: '1,2,3',
    id_comunicado:123
})
.then(function(docRef) {
    console.log("EXITO Document written with ID: ", docRef.id);
    
    
})
.catch(function(error) {
    console.error("Error adding document: ", error);
});    
    *///crear
/*
 //BORRAR:::::::::::::::::::::
 db.collection("users").doc('s6Q8Dk3bGZ9XWU5Qa2ih').delete().then(function(){
     console.log('Dpocument succesfull');
     
 }).catch(function(error){
     console.log('error');
   
 }); //:::::::::::::::::::::::::::::::
*///borrar
/*  
 //EDITAR:::::::::::::::::::::
 var washingtonRef=  db.collection("users").doc('id');
 return washingtonRef.update({
    titulo: "Ada",
    desc: "Lovelace",
    prioridad: 1815,
    usuarios: 1815
 }).then(function(){
     console.log('Dpocument succesfull');
     
 }).catch(function(error){
     console.log('error',error);
 }); //:::::::::::::::::::::::::::::::
*///editar
                  </script>
                   -->
                 
                
               
              
             
            
           
          
         
        
       
      
     
    
   