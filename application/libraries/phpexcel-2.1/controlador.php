<?php
    //require_once libraries . ("phpexcel-2.1/PHPExcel.php");
    require_once libraries . '/phpexcel-2.1/PHPExcel.php';
    
	function excel_iniciar($archivo) {
        $ruta = libraries . '/phpexcel-2.1/plantillas/';
        $ruta_archivo = $ruta . $archivo;

        //-------------------- CARGAR LIBRERIA PHPEXCEL
        // incrementar el tamaño de memoria disponible para PHP
        //ini_set('memory_limit',$memoria);	//64M 128M 256M 512M

        //cargar la librería TCPDF
        //$this->load->library('Libreria_phpexcel');

        //-------------------- CREAR Y CONFIGURAR UN OBJETO PHPEXCEL
        //crear un nuevo objeto PHPEXCEL
        $objPHPExcel = new PHPExcel();

        //leer una plantilla Excel
        $objReader = PHPExcel_IOFactory::createReader('Excel5');	//Excel5, Excel2007
        //$objReader = new PHPExcel_Reader_Excel2007();
        //$objReader->setIncludeCharts(TRUE);
        $objPHPExcel = $objReader->load($ruta_archivo);

        //-------------------------------------------------- HOJA 1 - PROYECTOS MMAYA
        //seleccionar una hoja
        $objPHPExcel->setActiveSheetIndex(0);	//la primera Hoja de excel se numera como 0

        return $objPHPExcel;
    }
function excel_iniciar2($archivo) {
        $ruta = libraries . '/phpexcel-2.1/plantillas/';
        $ruta_archivo = $ruta . $archivo;
        $objPHPExcel = new PHPExcel();
   //actual 2005
   // $objPHPExcel=PHPExcel_IOFactory::load($ruta_archivo);	 
    //
     $objReader = PHPExcel_IOFactory::createReader('Excel2007');	//Excel5, Excel2007
       //$objReader = new PHPExcel_Reader_Excel2007();
       // $objReader->setIncludeCharts(TRUE);
        $objPHPExcel = $objReader->load($ruta_archivo);
    
    $objPHPExcel->setActiveSheetIndex(0);	//la primera Hoja de excel se  
  
        return $objPHPExcel;
    }

    function excel_finalizar2(&$objPHPExcel, $nombreDescarga) {
        
        $archivoTemporal = tmpfile();
        
        $metaDatas = stream_get_meta_data($archivoTemporal);
        
        $ruta_archivo = $metaDatas['uri'];
        
        //exportar a Excel5 (.xls) o Excel2007 (.xlsx)
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); //Excel5, Excel2007
        
        $objWriter->setIncludeCharts(TRUE);
        $objWriter->save($ruta_archivo);
        //var_dump($objWriter);die;


        $objPHPExcel->disconnectWorksheets();
        unset($objPHPExcel);

        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename='.$nombreDescarga);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($ruta_archivo));
        ob_clean();
        flush();
        readfile($ruta_archivo);
        fclose($archivoTemporal);
        
    }


    function excel_finalizar(&$objPHPExcel, $nombreDescarga) {
        
        $archivoTemporal = tmpfile();
        
        $metaDatas = stream_get_meta_data($archivoTemporal);
        
        $ruta_archivo = $metaDatas['uri'];
        
        //exportar a Excel5 (.xls) o Excel2007 (.xlsx)
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); //Excel5, Excel2007
        
        $objWriter->setIncludeCharts(TRUE);
        $objWriter->save($ruta_archivo);
        //var_dump($objWriter);die;


        $objPHPExcel->disconnectWorksheets();
        unset($objPHPExcel);

        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename='.$nombreDescarga);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($ruta_archivo));
        ob_clean();
        flush();
        readfile($ruta_archivo);
        fclose($archivoTemporal);
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        /* Creando archivo temporal */
        /*$archivoTemporal = tmpfile();
        $metaDatas = stream_get_meta_data($archivoTemporal);
        $ruta_archivo = $metaDatas['uri'];


        //exportar a Excel5 (.xls) o Excel2007 (.xlsx)
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->setIncludeCharts(TRUE);
        $ruta_archivo ="../../reportes/profesor/";
        //$objWriter->save("../../reportes/profesor/".$nombreDescarga);
        $objWriter->save($ruta_archivo.$nombreDescarga);
        //$objWriter->save('php://output');

        $objPHPExcel->disconnectWorksheets();*/
        //unset($objPHPExcel);

        /*header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename='.$nombreDescarga);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($ruta_archivo));
        ob_clean();
        flush();
        readfile($ruta_archivo);
        fclose($archivoTemporal);*/
    }

?>