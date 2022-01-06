<?php



/**

 * FunctionPHP - Framework Functional PHP

 * 

 * @package  FunctionPHP

 * @author   Wilfredo Nina <wilnicho@hotmail.com>

 */



// Importa la libreria para generar el reporte

require_once libraries . '/tcpdf/tcpdf.php';



// Define tamanos y fuentes

$font_name_main = 'roboto';

$font_name_data = 'roboto';

$font_size_main = 12;

$font_size_data = 10;



// Define los estilos

$style = '<style>table {background-color: #fff;border: 1.5px solid #000;} th {background-color: #fff;border: 0.5px solid #000;} td {background-color: #fff;border-left: 1px solid #444;border-right: 1px solid #444;}.first th, .first td {border-top: 1px solid #444;}.last th, .last td {border-bottom: 1px solid #444;}.even th, .even td {background-color: #fff;}.odd th, .odd td {background-color: #eee;}.left {text-align: right;width: 40%;}.right {text-align: left;width: 60%;}img {border: 0px solid #444;height: 100px;text-align: center;}</style>';



// Define variables globales

define('nombre', escape($_institution['nombre']));

define('logo_color', escape($_institution['logo_color']));

define('lema', escape($_institution['lema']));

define('informacion', escape($_institution['informacion']));

define('fecha', date(escape($_institution['formato'])) . ' ' . date('H:i:s'));

define('razon_social', escape($_institution['razon_social']));

define('nit', escape($_institution['nit']));

define('nombre_dominio', escape($_institution['nombre_dominio']));

define('propietario', escape($_institution['propietario']));

define('departamento', escape($_institution['departamento']));

define('ciudad', escape($_institution['ciudad']));

define('direccion', escape($_institution['direccion']));

define('telefono', escape($_institution['telefono']));

define('celular', escape($_institution['celular']));

define('correo', escape($_institution['correo']));





// Define longitudes

define('margin_left', 30);

define('margin_right', 30);

define('margin_top', 75);

define('margin_bottom', 45);

define('margin_header', 0);

define('margin_footer', 0);



// Extiende la clase tcpdf para crear header y footer

class MYPDF extends TCPDF {

	public function Header() {

		$logotipo = (logo_color != '') ?  files.'/'.nombre_dominio. '/logos/'.logo_color : files.'/logo-defecto-institucion.png';

		$this->Ln(20);

		$this->SetFont('roboto', 'B', 12);

		//$pdf->SetFont('times', 'BI', 12);

		$this->Cell(0, 10, nombre, 0, true, 'C', 0, '', 1);

		$this->SetFont('roboto', '', 9);

		$this->Cell(0, 10, lema, 0, true, 'C', false, '', 0, false, '', '');

		$this->Cell(0, 10, razon_social, 0, true, 'C', false, '', 0, false, 'T', 'M');

		$this->Cell(0, 10, propietario, 0, true, 'C', false, '', 0, false, 'T', 'M');

		$this->Cell(0, 10, direccion, 0, true, 'C', false, '', 0, false, 'T', 'M');

		$this->Cell(0, 10, fecha, 0, true, 'R', false, '', 0, false, 'T', 'M');

		$this->Image($logotipo, margin_left, 40, '', 40, 'jpg', '', 'T', false, 200, '', false, false, 0, false, false, false);

	}

	

	public function Footer() {

		$this->SetY(5 - margin_bottom);

		$this->SetFont('roboto', '', 6);

		$length = ($this->getPageWidth() - margin_left - margin_right) / 2;

		$number = $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages();

		$this->Cell($length, 15, 'Página ' . $number, 'T', false, 'L', 0, '', 0, false, 'T', 'M');

		$this->Cell(0, 9, 'Telefonos : '.telefono.' - '.celular, 'T', 0.5, 'R', false, '', 0, true, '', '');

		$this->Cell(0, 9, 'Correo electronico : '.correo, 0 ,0.5, 'R', false, '', 0, true, '', '');

		$this->Cell(0, 9, departamento.' - '.ciudad, 0, 0.5, 'R', false, '', 0, true, '', '');

	}

}



// Instancia el documento pdf

$pdf = new MYPDF('P', 'pt', 'LETTER', true, 'UTF-8', false);



// Asigna la informacion al documento

$pdf->SetCreator(name_autor);

$pdf->SetAuthor(name_autor);

$pdf->SetTitle($_institution['nombre']);

$pdf->SetSubject($_institution['propietario']);

$pdf->SetKeywords($_institution['sigla']);



// Asignamos margenes

$pdf->SetMargins(margin_left, margin_top, margin_right);

$pdf->SetHeaderMargin(margin_header);

$pdf->SetFooterMargin(margin_footer);



?>