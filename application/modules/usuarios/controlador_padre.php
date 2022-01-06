<?php
    $retorno = array();
    $dataTable = array();

    //private $schema = 'sisgp';
    $inicio_defecto = 0;
    $filas_defecto = 10;
    $db;

    function procesar_data_table()
    {
        /*$busqueda = get('search')['value'];
        $columnasBusqueda = get('columnas_busqueda');*/

        if(!empty($busqueda) && !empty($columnasBusqueda)) {
            $dataTable['busqueda'] = $busqueda;
            $dataTable['columnasBusqueda'] = $columnasBusqueda;
        } else {
            $dataTable['busqueda'] = null;
            $dataTable['columnasBusqueda'] = null;
        }

        $indiceOrdenar = get('order')[0]['column'];
        $direccionOrdenar = get('order')[0]['dir'];
        $columna = $this->input->get('columns')[$indiceOrdenar]['data'];

        if(!empty($columna) && $columna != 'null') {
            $this->dataTable['orden'] =  $columna;
            $this->dataTable['direccion'] =  $direccionOrdenar;
        } else {
            $this->dataTable['orden'] =  null;
            $this->dataTable['direccion'] = null;
        }


        $inicio = $this->input->get('start');
        $longitud = $this->input->get('length');
        if( $inicio == 0 || !empty($inicio) ) {
            $this->dataTable['inicio'] = $inicio;
        } else {
            $this->dataTable['inicio'] = null;
        }
        if( !empty($longitud) ) {
            $this->dataTable['longitud'] =  $longitud;
        } else {
            $this->dataTable['longitud'] =  null;
        }

        $this->dataTable['bandera'] =  $this->input->get('draw');
        $this->retorno['draw'] = $this->input->get('draw');
    }

    function procesar_retorno($resultado)
    {
        if(!empty($resultado['datos'])) {
            $this->retorno['recordsTotal'] = $resultado['total'];
            $this->retorno['recordsFiltered'] = $resultado['total'];
            $this->retorno['data'] = $resultado['datos'];
        } else {
            $this->retorno['recordsTotal'] = 0;
            $this->retorno['recordsFiltered'] = 0;
            $this->retorno['data'] = array();
        }
    }

?>