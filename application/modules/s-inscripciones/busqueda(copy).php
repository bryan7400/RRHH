<?php

    //$estudiantes = $db->select('z.*')->from('vista_estudiantes z')->order_by('z.id_estudiante', 'asc')->fetch(); echo json_encode($estudiantes);
    $gestion = $_gestion['id_gestion'];

    $consulta ="SELECT p.foto, CONCAT(z.primer_apellido,' ',z.segundo_apellido,' ',z.nombres) nombre_completo, e.codigo_estudiante, p.numero_documento, 
    CONCAT(z.nombre_aula,' ',z.nombre_paralelo,' ',z.nombre_nivel) curso, z.nombre_tipo_estudiante, p.genero, e.id_estudiante, f.*,ii.estado_inscripcion, ii.usuario_registro, u.username
    FROM vista_inscripciones z
    INNER JOIN ins_estudiante e ON z.estudiante_id=e.id_estudiante
    INNER JOIN ins_inscripcion ii ON ii.estudiante_id=e.id_estudiante
    INNER JOIN sys_users u ON u.id_user=ii.usuario_registro
    INNER JOIN sys_persona p ON e.persona_id=p.id_persona
    LEFT JOIN 
    (SELECT GROUP_CONCAT( CONCAT(pp.nombres,' ', pp.primer_apellido,' ', pp.segundo_apellido) SEPARATOR ' | ') AS nombres_familiar,
    GROUP_CONCAT(f.telefono_oficina SEPARATOR ' | ') AS contacto, ef.estudiante_id
    FROM ins_familiar f 
    INNER JOIN sys_persona pp ON f.persona_id=pp.id_persona
    INNER JOIN ins_estudiante_familiar ef ON ef.familiar_id=f.id_familiar  AND f.estado = 'A'
    GROUP BY ef.estudiante_id
    ) f ON e.id_estudiante=f.estudiante_id
    WHERE z.gestion_id=$gestion and ii.estado = 'A'
    ORDER BY z.id_inscripcion DESC";
    $inscritos = $db->query($consulta)->fetch();
    //$inscritos = $db->query("SELECT * FROM ins_datos_estudiante")->fetch(); 
    // echo ("<pre>");
    // var_dump($inscritos);
    // echo ("</pre>");
    // die;
    echo json_encode($inscritos);
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