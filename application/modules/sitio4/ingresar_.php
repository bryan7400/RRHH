<?php

// Obtiene la cadena csrf
$csrf = set_csrf();

?>
<?php require_once show_template('header-site'); ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title" data-header="true">
            <span class="glyphicon glyphicon-lock"></span>
            <strong>Ingresar</strong>
          </h3>
        </div>
        <div class="panel-body">
          <?php if ($message = get_notification()) : ?>
          <div class="alert alert-<?= $message['type']; ?>">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong><?= $message['title']; ?></strong>
            <p><?= $message['content']; ?></p>
          </div>
          <?php endif ?>
          <form method="post" action="?/<?= site; ?>/autenticar" class="margin-none">
            <input type="hidden" name="<?= $csrf; ?>">
            <div class="form-group">
              <input type="hidden" name="locale" value="">
              <input type="text" name="username" class="form-control" placeholder="Nombre de usuario" autocomplete="off" autofocus="autofocus">
            </div>
            <div class="form-group">
              <input type="password" name="password" class="form-control" placeholder="Contraseña" autocomplete="off">
            </div>
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="remember">
                  <span>Recuérdame</span>
                </label>
              </div>
            </div>
            <button type="submit" class="btn btn-primary">
              <span class="glyphicon glyphicon-share-alt"></span>
              <span>Ingresar</span>
            </button>
            <button type="reset" class="btn btn-default">
              <span class="glyphicon glyphicon-refresh"></span>
              <span>Restablecer</span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once show_template('footer-site'); ?>

