<?php require_once show_template('header-site'); ?>
<style>
  .materia{
    float: left; width: auto; background-color: rgb(81,190,120); padding: 5px 15px; color:#fff; border-radius: 2px; margin:15px 5px; cursor: pointer;
  }
  .site-section{
    padding-top: 10px;
  }
  .tably td{
    padding: 3px;
  }
  .tably td b{
    font-weight: bold;
  }
  .tably td i{
    font-size: 10px;
  }
</style>
<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

  <div class="site-wrap">

    <div class="site-mobile-menu site-navbar-target">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close mt-3">
          <span class="icon-close2 js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body"></div>
    </div>


    <div class="py-2 bg-light"> 
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-9 d-none d-lg-block">
            <a href="#" class="small mr-3"><span class="icon-question-circle-o mr-2"></span> Have a questions?</a> 
            <a href="#" class="small mr-3"><span class="icon-phone2 mr-2"></span> 10 20 123 456</a> 
            <a href="#" class="small mr-3"><span class="icon-envelope-o mr-2"></span> info@mydomain.com</a> 
          </div>
          <div class="col-lg-3 text-right">
            <a href="?/<?= site; ?>/salir-portal" class="small mr-3"><span class="icon-unlock-alt"></span> Cerrar Sesión</a>
            <!-- <a href="register.html" class="small btn btn-primary px-4 py-2 rounded-0"><span class="icon-users"></span> Register</a> -->
          </div>
        </div>
      </div>
    </div>
    <header class="site-navbar py-4 js-sticky-header site-navbar-target" role="banner">

      <div class="container">
        <div class="d-flex align-items-center">
          <div class="site-logo">
            <a href="index.html" class="d-block">
              <img src="<?= imgs . '/educheck.png'; ?>" alt="Image" class="img-fluid" width="200px" height="300px">
            </a>
          </div>
          <div class="mr-auto">
            <nav class="site-navigation position-relative text-right" role="navigation">
              <ul class="site-menu main-menu js-clone-nav mr-auto d-none d-lg-block">
                <li class="active">
                  <a href="index.html" class="nav-link text-left">Home</a>
                </li>
                <li class="has-children">
                  <a href="about.html" class="nav-link text-left">About Us</a>
                  <ul class="dropdown">
                    <li><a href="teachers.html">Our Teachers</a></li>
                    <li><a href="about.html">Our School</a></li>
                  </ul>
                </li>
                <li>
                  <a href="admissions.html" class="nav-link text-left">Admissions</a>
                </li>
                <li>
                  <a href="courses.html" class="nav-link text-left">Courses</a>
                </li>
                <li>
                    <a href="contact.html" class="nav-link text-left">Contact</a>
                  </li>
              </ul>                                                                                                                                 </ul>
            </nav>

          </div>
          <div class="ml-auto">
            <div class="social-wrap">
              <a href="#"><span class="icon-facebook"></span></a>
              <a href="#"><span class="icon-twitter"></span></a>
              <a href="#"><span class="icon-linkedin"></span></a>

              <a href="#" class="d-inline-block d-lg-none site-menu-toggle js-menu-toggle text-black"><span
                class="icon-menu h3"></span></a>
            </div>
          </div>
         
        </div>
      </div>

    </header>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="materia" onclick="openx(1);">Matematicas</div>
                <div class="materia" onclick="openx(2);">Lenguaje</div>
                <div class="materia" onclick="openx(1);">Ingles</div>
                <div class="materia" onclick="openx(2);">Fisica</div>
                <div class="materia" onclick="openx(1);">Quimica</div>
            </div>
        </div>
    </div>

        <div class="contenido" id="contenido1" style="display:none;">
            <div class="site-section">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <p>                              
                              <h2 class="section-title-underline mb-5">
                                <span>Matematicas</span>

                              </h2>
                            </p>
                            <p><strong class="text-black d-block">Profesor:</strong> Craig Daniel</p>
                            <p>
                              <strong class="text-black d-block">Horas:</strong> 8:00 am &mdash; 9:30am
                            </p>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. At itaque dolore libero corrupti! Itaque, delectus?</p>          
                        </div>
                        <div class="col-md-6">
                            <p>                              
                              <h2 class="section-title-underline mb-5">
                                <span>Ultimas Actividades</span>
                              </h2>
                            </p>
                            
                            <table class="tably">
                              <tr>
                                <td>
                                  <Img src="<?= imgs; ?>/images/foto.png"/>
                                </td>
                                <td>
                                   <b>Practica 123</b>  <i>(13/03/2020 11:10)</i>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <Img src="<?= imgs; ?>/images/musica.png"/>
                                </td>
                                <td>
                                   <b>Practica 4</b> <i>(14/03/2020 12:30)</i>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <Img src="<?= imgs; ?>/images/curso.png"/>
                                </td>
                                <td>
                                   <b>Leccion 23</b> <i>(18/03/2020 22:30)</i>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <Img src="<?= imgs; ?>/images/foto.png"/>
                                </td>
                                <td>
                                   <b>Practica 123</b> <i>(19/03/2020 12:30)</i>
                                </td>
                              </tr>
                            </table>

                        </div>    
                    </div>
                    <br/>
                    <div class="row">                           
                        <div class="col-md-4">
                              <div  style="text-align: center;">
                              <h2 class="section-title-underline">
                                <span>Contenidos</span>
                              </h2>                    
                              <br>    
                              <img src="<?= imgs; ?>/images/video.png" style="width: 100px;">
                              <br><br>
                              </div>
                              <ul class="ul-check primary list-unstyled mb-5">
                                  <li>Lorem ipsum dolor sit amet consectetur</li>
                                  <li>consectetur adipisicing  </li>
                                  <li>Sit dolor repellat esse</li>
                                  <li>Necessitatibus</li>
                                  <li>Sed necessitatibus itaque </li>
                              </ul>
                        </div>
                        <div class="col-md-4">
                              <div  style="text-align: center;">
                              <h2 class="section-title-underline">
                                <span>Archivos</span>
                              </h2>                        
                              <br>
                              <img src="<?= imgs; ?>/images/archivo.png" style="width: 100px;">
                              <br><br>
                              </div>

                              <ul class="ul-check primary list-unstyled mb-5">
                                  <li>Lorem ipsum dolor sit amet consectetur</li>
                                  <li>consectetur adipisicing  </li>
                                  <li>Sit dolor repellat esse</li>
                                  <li>Necessitatibus</li>
                                  <li>Sed necessitatibus itaque </li>
                              </ul>
                        </div>
                        <div class="col-md-4">
                              <div  style="text-align: center;">
                              <h2 class="section-title-underline">
                                <span>Examen</span>
                              </h2>                        
                              <br>
                              <img src="<?= imgs; ?>/images/test.png" style="width: 100px;">
                              <br><br>
                              </div>

                              <ul class="ul-check primary list-unstyled mb-5">
                                  <li>Lorem ipsum dolor sit amet consectetur</li>
                                  <li>consectetur adipisicing  </li>
                                  <li>Sit dolor repellat esse</li>
                                  <li>Necessitatibus</li>
                                  <li>Sed necessitatibus itaque </li>
                              </ul>
                        </div>
                    </div>


                    <br>
                    <div class="row">       
                        <div class="col-md-12">
                                                          
                              <h2 class="section-title-underline mb-5">
                                <span>Videos</span>
                              </h2>
                            
                        </div>
                    </div>
                    <div class="row">                           
                        <div class="col-md-6">
                            <p>                              
                              <div class="entry-video embed-responsive embed-responsive-16by9">
                                  <iframe width="886" height="668" src="https://drive.google.com/file/d/1QNsZoYVKG9Bk-zheFnCoF3-bI0fg2IHH/preview" allowfullscreen=""></iframe>
                              </div>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                              <div class="col-md-4">                            
                                <img src="<?= imgs; ?>/images/course_1.jpg" alt="Image" class="img-fluid" style="width:100%;">
                              </div>
                              <div class="col-md-8">                            
                                <b>Descripción:</b> Lorem Impsum 
                                <b>Fecha:</b> 12/03/2020
                              </div>
                            </div>                            
                            <br>

                            <div class="row">
                              <div class="col-md-4">                            
                                <img src="<?= imgs; ?>/images/course_2.jpg" alt="Image" class="img-fluid" style="width:100%;">
                              </div>
                              <div class="col-md-8">                            
                                <b>Descripción:</b> Lorem Impsum 
                                <b>Fecha:</b> 12/03/2020
                              </div>
                            </div>                            
                            <br>

                            <div class="row">
                              <div class="col-md-4">                            
                                <img src="<?= imgs; ?>/images/course_3.jpg" alt="Image" class="img-fluid" style="width:100%;">
                              </div>
                              <div class="col-md-8">                            
                                <b>Descripción:</b> Lorem Impsum 
                                <b>Fecha:</b> 12/03/2020
                              </div>
                            </div>                            
                            <br>
                        </div>
                    </div>
                </div>
            </div>            
        </div>  
    








        <div class="contenido" id="contenido2" style="display:none;">
            <div class="site-section">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <p>                              
                              <h2 class="section-title-underline mb-5">
                                <span>Lenguaje</span>

                              </h2>
                            </p>
                            <p><strong class="text-black d-block">Profesor:</strong> Rebert Simmons</p>
                            <p>
                              <strong class="text-black d-block">Horas:</strong> 10:00 am &mdash; 12:30am
                            </p>
                            <p>Ipsum dolor sit consectetur adipising elit. At itaque dolore corrupti! Itaque, delectus amer lorem?
                              <br>
                            Ipsum dolor sit consectetur adipising elit. At itaque dolore corrupti! Itaque, delectus amer lorem?  
                            </p>          
                        </div>
                        <div class="col-md-6">
                            <p>                              
                              <h2 class="section-title-underline mb-5">
                                <span>Ultimas Actividades</span>
                              </h2>
                            </p>
                            
                            <table class="tably">
                              <tr>
                                <td>
                                  <Img src="<?= imgs; ?>/images/curso.png"/>
                                </td>
                                <td>
                                   <b>Leccion 3</b> <i>(18/03/2020 22:30)</i>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <Img src="<?= imgs; ?>/images/foto.png"/>
                                </td>
                                <td>
                                   <b>Practica 13</b>  <i>(23/03/2020 11:10)</i>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <Img src="<?= imgs; ?>/images/musica.png"/>
                                </td>
                                <td>
                                   <b>Practica 4</b> <i>(14/03/2020 12:30)</i>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <Img src="<?= imgs; ?>/images/foto.png"/>
                                </td>
                                <td>
                                   <b>Practica 123</b> <i>(19/03/2020 12:30)</i>
                                </td>
                              </tr>
                            </table>

                        </div>    
                    </div>





                    <br/>
                    <div class="row">                           
                        <div class="col-md-4">
                              <div  style="text-align: center;">
                              <h2 class="section-title-underline">
                                <span>Contenidos</span>
                              </h2>                    
                              <br>    
                              <img src="<?= imgs; ?>/images/video.png" style="width: 100px;">
                              <br><br>
                              </div>
                              <ul class="ul-check primary list-unstyled mb-5">
                                  <li>Lorem ipsum dolor sit amet consectetur</li>
                                  <li>Necessitatibus</li>
                                  <li>Sed necessitatibus itaque </li>
                              </ul>
                        </div>
                        <div class="col-md-4">
                              <div  style="text-align: center;">
                              <h2 class="section-title-underline">
                                <span>Archivos</span>
                              </h2>                        
                              <br>
                              <img src="<?= imgs; ?>/images/archivo.png" style="width: 100px;">
                              <br><br>
                              </div>

                              <ul class="ul-check primary list-unstyled mb-5">
                                  <li>Lorem ipsum dolor sit amet consectetur</li>
                                  <li>consectetur adipisicing  </li>
                                  <li>Necessitatibus</li>
                                  <li>Sed necessitatibus itaque </li>
                              </ul>
                        </div>
                        <div class="col-md-4">
                              <div  style="text-align: center;">
                              <h2 class="section-title-underline">
                                <span>Examen</span>
                              </h2>                        
                              <br>
                              <img src="<?= imgs; ?>/images/test.png" style="width: 100px;">
                              <br><br>
                              </div>

                              <ul class="ul-check primary list-unstyled mb-5">
                                  <li>Lorem ipsum dolor sit amet consectetur</li>
                                  <li>Necessitatibus</li>
                                  <li>Sed necessitatibus itaque </li>
                                  <li>consectetur adipisicing  </li>
                                  <li>Sit dolor repellat esse</li>
                                  
                              </ul>
                        </div>
                    </div>


                    <br>
                    <div class="row">       
                        <div class="col-md-12">
                                                          
                              <h2 class="section-title-underline mb-5">
                                <span>Videos</span>
                              </h2>
                            
                        </div>
                    </div>
                    <div class="row">                           
                        <div class="col-md-6">
                            <p>                              
                              <div class="entry-video embed-responsive embed-responsive-16by9">
                                  <iframe width="886" height="668" src="https://drive.google.com/file/d/1QNsZoYVKG9Bk-zheFnCoF3-bI0fg2IHH/preview" allowfullscreen=""></iframe>
                              </div>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                              <div class="col-md-4">                            
                                <img src="<?= imgs; ?>/images/course_2.jpg" alt="Image" class="img-fluid" style="width:100%;">
                              </div>
                              <div class="col-md-8">                            
                                <b>Descripción:</b> Lorem Impsum 
                                <b>Fecha:</b> 12/03/2020
                              </div>
                            </div>                            
                            <br>

                            <div class="row">
                              <div class="col-md-4">                            
                                <img src="<?= imgs; ?>/images/course_5.jpg" alt="Image" class="img-fluid" style="width:100%;">
                              </div>
                              <div class="col-md-8">                            
                                <b>Descripción:</b> Lorem Impsum 
                                <b>Fecha:</b> 12/03/2020
                              </div>
                            </div>                            
                            <br>

                            <div class="row">
                              <div class="col-md-4">                            
                                <img src="<?= imgs; ?>/images/course_4.jpg" alt="Image" class="img-fluid" style="width:100%;">
                              </div>
                              <div class="col-md-8">                            
                                <b>Descripción:</b> Lorem Impsum 
                                <b>Fecha:</b> 12/03/2020
                              </div>
                            </div>                            
                            <br>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
<?php require_once show_template('footer-site'); ?>
<script>
$(document).ready(function(){
  $("#contenido1").css({'display':'block'});
})  
function openx(x){
    $(".contenido").fadeOut(1000);
    setTimeout(function(){
      $("#contenido"+x).stop().fadeIn(1000);
    },900)
        

    //$(".contenido").css({'display':'none'});
    //$("#contenido"+x).css({'display':'block'});
}
</script>


