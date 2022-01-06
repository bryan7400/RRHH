<?php
    $id_gestion = $_gestion['id_gestion'];
    //$pensiones = $db->select('z.*')->from('vista_pensiones z')->where('z.gestion_id',1)->order_by('z.id_pensiones', 'asc')->fetch();
    //$rutas = $db->select('z.*')->from('gon_rutas z')->where('z.gon_rutas')->order_by('z.id_ruta', 'asc')->fetch();
    $rutas = $db->query("SELECT r.id_ruta,r.nombre, r.descripcion,CONCAT(p.nombres,' ',p.primer_apellido) AS conductor, r.id_ruta
					 FROM gon_gondolas AS g 
					 INNER JOIN gon_conductor_gondola AS cg ON cg.gondola_id = g.id_gondola	
					 INNER JOIN gon_rutas AS r ON r.id_ruta = g.ruta_id 
					 INNER JOIN gon_conductor AS c ON c.id_conductor = cg.conductor_id 
					 INNER JOIN sys_persona AS p ON p.id_persona=c.persona_id 
					 WHERE g.estado = 1")->fetch();
   
    echo json_encode($rutas);  
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