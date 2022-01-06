<?php

    var_dump('gnjfkg');exit();

    // Obtiene todos los pagos realizados
    $cobros = $db->query("SELECT pg.*, sp.id_persona, CONCAT(sp.nombres, sp.primer_apellido, sp.segundo_apellido) nombre_empleado
    FROM pen_pensiones_estudiante_general pg
    INNER JOIN sys_users su ON pg.usuario_registro = su.id_user
    INNER JOIN sys_persona sp ON su.persona_id = sp.id_persona
    ORDER BY pg.fecha_general ASC")->fetch();
    var_dump($cobros);exit();
    echo json_encode($cobros);

    function seleccionarActivos($entidad,$inicio, $filas, $ordenar, $direccion, $busqueda, $columnasBusqueda, $restricciones)
    {
        list($buscar, $busqueda) = $this->configurar_buscar($busqueda, $columnasBusqueda);
        list($ordenar, $direccion) = $this->configurar_ordenar($entidad, $ordenar, $direccion);
        $paginado = $this->configurar_pagianacion($inicio, $filas);

        $select = "\nSELECT * FROM ".$this->schema . '.' . $entidad;

        $where = $this->configurar_where($busqueda, $columnasBusqueda, $buscar, $restricciones);
        $orderBy = "\n ORDER BY $ordenar $direccion";
        $limitOffset = "";
        if ($paginado) {
            $limitOffset = " LIMIT $inicio OFFSET $filas";
        }
        if (isset($restricciones) && sizeof($restricciones>0)) {
            $condiciones = array();
            foreach($restricciones as $columna => $valor) {
                array_push($condiciones, $valor);
            }
            $result = $this->db->query($select . $where . $orderBy . $limitOffset, $condiciones);
        }

        else {
            $result = $this->db->query($select . $where . $orderBy . $limitOffset);
        }
        return $result->result_array();

    }

    function contarActivos($entidad, $busqueda, $columnasBusqueda, $restricciones)
    {
        list($buscar, $busqueda) = configurar_buscar($busqueda, $columnasBusqueda);
        $select = "\nSELECT COUNT(*) as cantidad FROM ".schema . '.' . $entidad;
        $where = configurar_where($busqueda, $columnasBusqueda, $buscar , $restricciones);
        if (isset($restricciones) && sizeof($restricciones>0)) {
            $condiciones = array();
            foreach($restricciones as $columna => $valor) {
                array_push($condiciones, $valor);
            }
            $result = $this->db->query($select . $where, $condiciones);
        } else {
            $result = $this->db->query($select . $where);
        }


        return $result->row()->cantidad;
    }


    function listar_todo_entidad($entidad, $params, $restricciones = array()) {
        $resultado = array();

        $resultado['datos'] = seleccionarActivos($entidad,
            $params['longitud'],$params['inicio'],
            $params["orden"],$params["direccion"],
            $params["busqueda"],$params["columnasBusqueda"],
            $restricciones
        );

        $resultado['total'] = contarActivos($entidad, $params["busqueda"],$params["columnasBusqueda"], $restricciones);
        return $resultado;
    }

    function listar_todo($params, $restricciones = array()) {
        $resultado = array();
        $resultado['datos'] = seleccionarActivos(getEntidad(),
            $params['longitud'],$params['inicio'],
            $params["orden"],$params["direccion"],
            $params["busqueda"],$params["columnasBusqueda"],
            $restricciones
        );
        $resultado['total'] = contarActivos(getEntidad(), $params["busqueda"],$params["columnasBusqueda"], $restricciones);
        return $resultado;
    }

?>