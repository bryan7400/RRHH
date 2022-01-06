<?php
$csrf = set_csrf();
//var_dump($niveles_academicos);
?>
<form id="form_materia" enctype="multipart/form-data">
  <div class="modal fade" id="modal_materia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><span id="titulo_gestion"></span> Materia</h5>
          <a href="#" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </a>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="control-group col-6 col-sm-6 col-lg-6">
              <label class="control-label">Nombre Materia: </label>
              <div class="controls">
                <input type="hidden" name="<?= $csrf; ?>">
                <input id="id_materia" name="id_materia" type="hidden" class="form-control">
                <input id="nombre_materia" name="nombre_materia" type="text" class="form-control" placeholder="Ej: Matematicas">
              </div>
            </div>

            <div class="control-group  col-6 col-sm-6 col-lg-6">
              <label class="control-label">Codigo: </label>
              <div class="controls">
                <input id="codigo_materia" name="codigo_materia" type="text" class="form-control" placeholder="Ej: MAT">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="control-group col-6 col-sm-6 col-lg-6">
              <label class="control-label">Descripción: </label>
              <div class="controls">
                <input id="descripcion" name="descripcion" type="text" class="form-control">
              </div>
            </div>

            <div class="control-group col-6 col-sm-6 col-lg-6">
              <label class="control-label">Color: </label>
              <div class="controls">
                <!--<input id="color" name="color" type="text" class="form-control">-->
                <input type="hidden" id="color" name="color" class="form-control" value="#b8f7c5">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6"> <label class="control-label">Niveles Academicos: </label>
              <div class="custom-control custom-checkbox">
                <?php foreach ($niveles_academicos as $value) : ?>
                  <div class="col-12 col-sm-12 col-lg-12 pt-1">
                    <input type="checkbox" class="custom-control-input" id="<?= $value['id_nivel_academico']; ?>" name="nivel_academico[<?= $value['id_nivel_academico']; ?>]" value="<?= $value['id_nivel_academico']; ?>">
                    <label class="custom-control-label" for="<?= $value['id_nivel_academico']; ?>"> <?= $value['nombre_nivel']; ?></label>
                  </div>
                <?php endforeach ?>
              </div>
            </div>

            <div class="col-6">
              <label class="control-label">Campo </label>
              <div class="col-12 col-sm-12 col-lg-12 pt-1">
                <select name="id_campo" id="id_campo" class="form-control">
                  <?php foreach ($campos_academicos as $value) : ?>
                    <option value="<?= $value['id_campo']; ?>" title="<?= $value['descripcion_campo']; ?>"><?= $value['nombre_campo']; ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>

          </div>
          <div class="row">

            <div class="col-6">
              <label class="control-label">Orden </label>
              <div class="col-12 col-sm-12 col-lg-12 pt-1">
                <input type="text" id="orden" name="orden" class="form-control" value="0">
              </div>
            </div>

            <div class="col-6">
              <label class="control-label">Icono </label>
              <div class="alert alert-primary" role="alert">
                Se recomienda que la imagen que seleccione tenga un pixel de 25x25px y en formato PNG
              </div>
              <div class="col-12 col-sm-12 col-lg-12 pt-1">
                <img id="icono" style="max-width:100%;width:auto;height:auto;"><br />
                <input id="inputFile1" name="inputFile1" type="file">
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary pull-right" id="btn_nuevo">Registrar</button>
          <button type="submit" class="btn btn-primary pull-right" id="btn_editar">Editar</button>
        </div>
      </div>
    </div>
  </div>
</form>

<script>
  function init() {
    var inputFile = document.getElementById('inputFile1');
    inputFile.addEventListener('change', mostrarImagen, false);
  }

  function mostrarImagen(event) {
    var file = event.target.files[0];
    var reader = new FileReader();
    reader.onload = function(event) {
      var img = document.getElementById('icono');
      img.src = event.target.result;
    }
    reader.readAsDataURL(file);
  }

  window.addEventListener('load', init, false);


  var imagen = [];

  function revisarImagen(input, num) {
    var id_preview = input.getAttribute("id") + "_preview";
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onloadend = function(e) {
        var id_preview_text = "#" + id_preview;
        var base64image = e.target.result;
        $("body").append("<canvas id='tempCanvas' width='800' height='800' style='display:none'></canvas>");
        var canvas = document.getElementById("tempCanvas");
        var ctx = canvas.getContext("2d");
        var cw = canvas.width;
        var ch = canvas.height;
        var maxW = 800;
        var maxH = 800;
        var img = new Image;
        img.src = this.result;
        img.onload = function() {
          var iw = img.width;
          var ih = img.height;
          var scale = Math.min((maxW / iw), (maxH / ih));
          var iwScaled = iw * scale;
          var ihScaled = ih * scale;
          canvas.width = iwScaled;
          canvas.height = ihScaled;
          ctx.drawImage(img, 0, 0, iwScaled, ihScaled);
          base64image = canvas.toDataURL("image/jpeg");
          $(id_preview_text).attr('src', base64image).width(250).height(157);
          imagen[num] = base64image;
          $("#tempCanvas").remove();
        }
      };
      reader.readAsDataURL(input.files[0]);
    }

  }

  $('#color').minicolors({
    theme: 'bootstrap'
  });

  $("#form_materia").validate({
    rules: {
      nombre_materia: {
        required: true
      },
      nivel_academico: {
        required: true
      },
      id_campo: {
        required: true
      }
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: highlight,
    unhighlight: unhighlight,
    messages: {
      nombre_materia: "Debe ingresar el nombre de materia.",
      descripcion: "Debe ingresar una descripción.",
      id_campo: "Debe ingresar un campo al cual pertenece."
    },
    //una ves validado guardamos los datos en la DB
    submitHandler: function(form) {
      // var datos = $("#form_materia").serialize();
      var form_data = new FormData($("#form_materia")[0]);
      //alert(datos);
      $.ajax({

        type: 'POST',
        url: "?/s-materia/guardar",
        data: form_data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'text',        
        success: function(resp) {
          cont = 0;
          console.log(resp);
          switch (resp) {
            case '2':
              //dataTable.ajax.reload();
              listar_materias();
              $("#modal_materia").modal("hide");
              alertify.success('Se registro el materia correctamente');
              break;
            case '1':
              //dataTable.ajax.reload();
              listar_materias();
              $("#modal_materia").modal("hide");
              alertify.success('Se editó el materia correctamente');
              break;

              case '3':
              //dataTable.ajax.reload();
              // listar_materias();
              // $("#modal_materia").modal("hide");
              alertify.warning('El archivo icono no es conpatible');
              break;
          }
          //pruebaa();
        }

      });

    }
  })
</script>