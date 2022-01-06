$(function () {

   // $('#antecesor_id').selectize({
   //     create: true,
   //     createOnBlur: false,
   //     maxOptions: 7,
   //     persist: false,
   //     onInitialize: function () {
   //         $('#antecesor_id').show().addClass('selectize-translate');
   //     },
   //     onChange: function () {
   //         $('#antecesor_id').trigger('blur');
   //     },
   //     onBlur: function () {
   //         $('#antecesor_id').trigger('blur');
   //     }
   // });

   // $('form:first').on('reset', function () {
   //     $('#antecesor_id').get(0).selectize.clear();
   // });
   });

   // $.validator.setDefaults({
   //     submitHandler: function() {
   //         alert("submitted!");
   //     }
   // });

   $().ready(function() { 
       // validate the comment form when it is submitted
       //$("#form-menu").validate();

       // validate signup form on keyup and submit
       $("#form-menu").validate({
           rules: {
               menu: "required",
               icono: "required",
           },
           messages: {
               menu: "Debe ingresar el nombre del menú",
               icono: "Debe ingresar un ícono representativo",
           }
       });
   //     $("#form_modifica_tproyecto").validate({
   //       rules: {
   //           //m_nivel_estudio: {required: true},
   //           m_grupo_proyecto: {required: true},
   //           m_clasificacion:{required: true},
   //           m_nombre: {required: true}
   //       },
   //       errorClass: "help-inline",
   //       errorElement: "span",
   //       highlight: highlight,
   //       unhighlight: unhighlight,
   //       messages: {
   //           //m_nivel_estudio: "Debe seleccionar un nivel de estudio.",
   //           m_grupo_proyecto: "Debe seleccionar un grupo proyecto.",
   //           m_clasificación: "Debe seleccionar una clasificación.",
   //           m_nombre: "Debe ingresar un nombre."
   //       },
   //       //una ves validado guardamos los datos en la DB
   //       submitHandler: function (form) {
   //           modificar_tproyecto();
   //       }
   //   });
       // propose username by combining first- and lastname
       // $("#username").focus(function() {
       //     var menu = $("#menu").val();
       //     //var lastname = $("#lastname").val();
       //     if (menu && lastname && !this.value) {
       //         this.value = menu + "." + lastname;
       //     }
       // }); 
       
   });
