<?php
    //require_once("s-gestion-escolar/controlador_padre.php");

    //var_dump($_POST);die;
    /*procesar_data_table();
    var_dump(procesar_data_table());die;
    $resultado = listar_todo();
    procesar_retorno($resultado);
    json($retorno);*/

    $estudiantes = $db->query('SELECT IFNULL(COUNT(i.aula_paralelo_id),0) AS contador , ap.capacidad, IFNULL(ap.capacidad-COUNT(i.aula_paralelo_id),ap.capacidad) vacantes,
    p.nombre_paralelo, a.nombre_aula, na.nombre_nivel
    FROM ins_aula_paralelo ap 
    INNER JOIN  ins_paralelo p ON p.id_paralelo=ap.paralelo_id
    INNER JOIN  ins_aula a ON a.id_aula=ap.aula_id
    INNER JOIN  ins_nivel_academico na ON a.nivel_academico_id=na.id_nivel_academico
    INNER JOIN  ins_inscripcion i ON ap.id_aula_paralelo=i.aula_paralelo_id
    GROUP BY i.aula_paralelo_id
    ORDER BY na.id_nivel_academico, a.id_aula, p.nombre_paralelo ASC')->fetch();

    echo json_encode($estudiantes); 
    //var_dump($gestiones);die;

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