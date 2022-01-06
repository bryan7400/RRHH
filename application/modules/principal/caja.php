<?php   

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica la peticion ajax 
if (is_ajax()) { 
	
	// Declaracion de variables   
    $caja_actual=0; $planilla_actual=0;

    //****************************** Suman a caja
	// Obtiene los cobros a clientes
	$cobro_cliente = $db->select('sum(z.cancelado) sum_cobro_cliente')->from('pag_cobros_clientes z')->where('z.estado','A')->fetch_first();
	// Obtiene las ventas
	$ventas = $db->select('sum(z.acuenta) sum_ventas')->from('inv_ventas_detalle z')->where('z.estado_venta_detalle','A')->fetch_first();
	// Obtiene saldo anterior de clientes
	$saldo_cliente = $db->select('sum(z.pago) sum_pago')->from('pag_saldo_cliente_detalle z')->where('z.estado','A')->fetch_first();

    //******************************* Restan a caja

	// Obtiene pagos a ganadero, transporte, carneo
	$pagos = $db->select('sum(z.cancelado) sum_pagos')->from('pag_proveedores z')->where('z.estado_pago','A')->fetch_first();
	// Obtiene gastos
	$gastos = $db->select('sum(z.monto) sum_monto')->from('otr_gasto z')->where('z.estado','A')->fetch_first();
	// Obtiene prestamos
	// $prestamos = $db->select('sum(z.total) sum_total')->from('otr_prestamo z')->where('z.estado','A')->fetch_first();
	// Obtiene saldo ganadero
	//$saldo_proveedor = $db->select('sum(z.cancelado) sum_pagos')->from('pag_proveedores_saldo_detalle z')->where('z.estado_detalle','A')->fetch_first();
	
	$saldo_proveedor = $db->select('sum(z.cancelado) sum_pagos')->from('sal_proveedores z')->where('z.estado_pago','A')->fetch_first();

    //Operaciones auxiliares para obterner caja actual
	$caja_actual=$caja_actual+$cobro_cliente['sum_cobro_cliente']+$ventas['sum_ventas']+$saldo_cliente['sum_pago']-$pagos['sum_pagos']-$gastos['sum_monto']-$saldo_proveedor['sum_pagos'];

	$numero = number_format($caja_actual, 0, '.', ',');

	//$numero = number_format(rand(0, 100000), 2, '.', ',');

	
	// Define las cabeceras
	header('Content-Type: application/json');
	
	// Devuelve los resultados
	echo json_encode($numero);
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>