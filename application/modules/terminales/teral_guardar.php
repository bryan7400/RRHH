<?php
// Verifica la peticion post
if (is_post()) {
	// Verifica la cadena csrf
	if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos
		if (isset($_POST['categoria']) && isset($_POST['codigo_terminal']) && isset($_POST['referencia_maxima']) && isset($_POST['referencia_minima']) && isset($_POST['valor_moneda'])) {

			$moneda = $db->select('id_moneda')->from('gen_monedas')->where('principal','S')->fetch_first(); 

			$arrayCategoria = explode('|', clear($_POST['categoria']));

			if (!isset($arrayCategoria[1])) {
				$categoria = $arrayCategoria[0];
			
				$categoria = array(
					'categoria'   => $categoria,
					'descripcion' => ''
				);

				$id_categoria = $db->insert('pel_categorias', $categoria);
			} else {
				$id_categoria = $arrayCategoria[1];
			}

			// Obtiene los datos de la terminal
			$id_terminal       = (isset($_POST['id_terminal'])) ? clear($_POST['id_terminal']) : 0;
			$categoria_id      = $id_categoria;
			$moneda_id         = $moneda['id_moneda'];
			$codigo_terminal   = clear($_POST['codigo_terminal']);
			$referencia_maxima = clear($_POST['referencia_maxima']);
			$referencia_minima = clear($_POST['referencia_minima']);
			$valor_moneda      = clear($_POST['valor_moneda']);

			//Obtiene datos del almacen
			$almacen      = clear($_POST['almacen']);
			$direccion    = clear($_POST['direccion']);
			$departamento = clear($_POST['departamento']);
			$descripcion  = '';
			$principal    = 'N';
			$telefono     = clear($_POST['telefono']);
			$encargado    = clear($_POST['encargado']);
			$porcentaje   = clear($_POST['porcentaje']);
			
			$almacen = array(
				'almacen'      => $almacen,
				'direccion'    => $direccion,
				'departamento' => $departamento,
				'descripcion'  => $descripcion,
				'principal'    => $principal,
				'telefono'     => $telefono,
				'encargado'    => $encargado,
				'porcentaje'   => $porcentaje,
				'tipo_almacen' => 'tienda'
			);
			
			// Verifica si es creacion o modificacion
			if ($id_terminal > 0) {

				$term = $db->select('*')->from('pel_terminales')->where('id_terminal',$id_terminal)->fetch_first();
				// Instancia la terminal
				$terminal = array(
					'categoria_id'      => $categoria_id,
					'moneda_id'         => $moneda_id,
					'almacen_id'        => $term['almacen_id'],
					'codigo_terminal'   => $codigo_terminal,
					'referencia_maxima' => $referencia_maxima,
					'referencia_minima' => $referencia_minima,
					'valor_moneda'      => $valor_moneda
				);
				
				// Modifica la terminal
				$db->where('id_terminal', $id_terminal)->update('pel_terminales', $terminal);
				$db->where('id_almacen', $term['almacen_id'])->update('pel_almacenes', $almacen);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso'  => date('H:i:s'),
					'proceso'       => 'u',
					'nivel'         => 'l',
					'detalle'       => 'Se modificó la terminal con identificador número ' . $id_terminal . '.',
					'direccion'     => $_location,
					'usuario_id'    => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
				// Redirecciona la pagina
				redirect('?/terminales/ver/' . $id_terminal);
			} else {
				// Crea la terminal
				$id_almacen = $db->insert('pel_almacenes', $almacen);
				
				// Instancia la terminal
				$terminal = array(
					'categoria_id'      => $categoria_id,
					'moneda_id'         => $moneda_id,
					'almacen_id'        => $id_almacen,
					'codigo_terminal'   => $codigo_terminal,
					'referencia_maxima' => $referencia_maxima,
					'referencia_minima' => $referencia_minima,
					'valor_moneda'      => $valor_moneda
				);

				$id_terminal = $db->insert('pel_terminales', $terminal);

				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó la terminal con identificador número ' . $id_terminal . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				
				// Redirecciona la pagina
				redirect('?/terminales/listar');
			}
		} else {
			echo "error";
			// // Error 400
			// require_once bad_request();
			// exit;
		}
	} else {
		// Redirecciona la pagina
		redirect('?/terminales/listar');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>