-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-05-2019 a las 01:21:09
-- Versión del servidor: 5.6.17
-- Versión de PHP: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `academico`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arc_archivo`
--

CREATE TABLE IF NOT EXISTS `arc_archivo` (
  `id_archivo` int(11) NOT NULL AUTO_INCREMENT,
  `inscripcion_id` varchar(50) NOT NULL,
  `estado` varchar(50) NOT NULL,
  PRIMARY KEY (`id_archivo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `arc_archivo`
--

INSERT INTO `arc_archivo` (`id_archivo`, `inscripcion_id`, `estado`) VALUES
(1, '25', '1'),
(2, '26', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arc_citaciones`
--

CREATE TABLE IF NOT EXISTS `arc_citaciones` (
  `id_citacion` int(11) NOT NULL AUTO_INCREMENT,
  `motivo` varchar(45) DEFAULT NULL,
  `fecha_envio` varchar(45) DEFAULT NULL,
  `fecha_asistencia` varchar(45) DEFAULT NULL,
  `profesor_materia_id` int(11) NOT NULL,
  `archivo_id` int(11) NOT NULL,
  PRIMARY KEY (`id_citacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `arc_citaciones`
--

INSERT INTO `arc_citaciones` (`id_citacion`, `motivo`, `fecha_envio`, `fecha_asistencia`, `profesor_materia_id`, `archivo_id`) VALUES
(1, 'reunión de padres de familia', '2019-05-14', '2019-05-23', 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arc_felicitaciones`
--

CREATE TABLE IF NOT EXISTS `arc_felicitaciones` (
  `id_felicitaciones` int(11) NOT NULL AUTO_INCREMENT,
  `profesor_materia_id` int(11) NOT NULL,
  `motivo` varchar(100) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `fecha_felicitacion` varchar(50) NOT NULL,
  `archivo_id` int(11) NOT NULL,
  PRIMARY KEY (`id_felicitaciones`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `arc_felicitaciones`
--

INSERT INTO `arc_felicitaciones` (`id_felicitaciones`, `profesor_materia_id`, `motivo`, `descripcion`, `fecha_felicitacion`, `archivo_id`) VALUES
(1, 1, 'bien echo', 'felicitacion', '2019-05-14', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arc_sanciones`
--

CREATE TABLE IF NOT EXISTS `arc_sanciones` (
  `id_sancion` int(11) NOT NULL AUTO_INCREMENT,
  `motivo` varchar(45) DEFAULT NULL,
  `fecha_sancion` varchar(45) DEFAULT NULL,
  `dias_suspencion` varchar(45) DEFAULT NULL,
  `traer_tutor` char(2) DEFAULT NULL,
  `fecha_traer_tutor` varchar(45) DEFAULT NULL,
  `asistio_tutor` char(2) DEFAULT NULL,
  `fecha_asistio_tutor` varchar(45) DEFAULT NULL,
  `profesor_materia_id` int(11) NOT NULL,
  `archivo_id` int(11) NOT NULL,
  PRIMARY KEY (`id_sancion`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Volcado de datos para la tabla `arc_sanciones`
--

INSERT INTO `arc_sanciones` (`id_sancion`, `motivo`, `fecha_sancion`, `dias_suspencion`, `traer_tutor`, `fecha_traer_tutor`, `asistio_tutor`, `fecha_asistio_tutor`, `profesor_materia_id`, `archivo_id`) VALUES
(30, 'Por peleas ', '2019-05-04', '5', '0', '2019-05-04', '0', '2019-05-04', 0, 4),
(31, 'Por pelea en el juego de piedra papel tijera', '2019-05-04', '5', '1', '2019-05-04', '0', '2019-05-04', 2, 4),
(32, 'suspendido por golpear a sus compañeros', '2019-05-14', '2', '1', '2019-05-14', '0', '2019-05-14', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asi_asistencia`
--

CREATE TABLE IF NOT EXISTS `asi_asistencia` (
  `id_asistencia` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_asistencia` datetime NOT NULL,
  `contador_ingreso` int(11) DEFAULT NULL,
  `contador_salida` int(11) DEFAULT NULL,
  `estudiante_id` int(11) NOT NULL,
  `registro_asistencia_id` int(11) NOT NULL,
  PRIMARY KEY (`id_asistencia`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asi_dias_feriados`
--

CREATE TABLE IF NOT EXISTS `asi_dias_feriados` (
  `id_dias_feriados` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_inicio` date NOT NULL,
  `fecha_final` date NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `gestion_id` int(11) NOT NULL,
  `estado` varchar(5) NOT NULL,
  PRIMARY KEY (`id_dias_feriados`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `asi_dias_feriados`
--

INSERT INTO `asi_dias_feriados` (`id_dias_feriados`, `fecha_inicio`, `fecha_final`, `descripcion`, `gestion_id`, `estado`) VALUES
(1, '2019-05-01', '2019-05-01', 'prueba solo una fecha', 1, 'A'),
(2, '2019-05-03', '2019-05-18', 'prueba con dos fechas', 1, 'A'),
(3, '2019-03-04', '2019-03-05', 'carnavales', 1, 'A'),
(5, '2019-04-19', '2019-04-19', 'viernes santo', 1, 'I'),
(6, '2019-04-19', '2019-04-19', 'Viernes Santo', 1, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asi_fecha_ocaiones`
--

CREATE TABLE IF NOT EXISTS `asi_fecha_ocaiones` (
  `id_fecha_ocaciones` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `tipo_ocacion` int(11) DEFAULT NULL,
  `hora_ingreso` time DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  PRIMARY KEY (`id_fecha_ocaciones`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asi_horarios`
--

CREATE TABLE IF NOT EXISTS `asi_horarios` (
  `id_horario` int(11) NOT NULL AUTO_INCREMENT,
  `dias` varchar(100) NOT NULL,
  `entrada` time NOT NULL,
  `salida` time NOT NULL,
  `tolerancia` time NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `gestion_id` int(11) NOT NULL,
  PRIMARY KEY (`id_horario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asi_hora_registro`
--

CREATE TABLE IF NOT EXISTS `asi_hora_registro` (
  `id_hora_registro` int(11) NOT NULL AUTO_INCREMENT,
  `ingreso_desde` time DEFAULT NULL,
  `ingreso` time DEFAULT NULL,
  `ingreso_tolerancia` int(11) DEFAULT NULL,
  `salida` varchar(45) DEFAULT NULL,
  `salida_hasta` varchar(45) DEFAULT NULL,
  `registro_asistencia_id` int(11) NOT NULL,
  PRIMARY KEY (`id_hora_registro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asi_registro_asistencia`
--

CREATE TABLE IF NOT EXISTS `asi_registro_asistencia` (
  `id_registro_asistencia` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_asistencia` date DEFAULT NULL,
  `fecha_ocaiones_id` int(11) NOT NULL,
  `archivo_id` int(11) NOT NULL,
  PRIMARY KEY (`id_registro_asistencia`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cal_actividad_detalle_ext`
--

CREATE TABLE IF NOT EXISTS `cal_actividad_detalle_ext` (
  `id_actividad_detalle_ext` int(11) NOT NULL,
  `actividad_id` int(11) NOT NULL,
  `actividad_detalle_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cal_actividad_fecha`
--

CREATE TABLE IF NOT EXISTS `cal_actividad_fecha` (
  `id_actividad_fecha` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_presentacion` date NOT NULL,
  `concluido` char(2) NOT NULL,
  `actividad_id` int(11) NOT NULL,
  `nota_area_calificacion_id` int(11) NOT NULL,
  PRIMARY KEY (`id_actividad_fecha`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `cal_actividad_fecha`
--

INSERT INTO `cal_actividad_fecha` (`id_actividad_fecha`, `fecha_presentacion`, `concluido`, `actividad_id`, `nota_area_calificacion_id`) VALUES
(1, '2019-03-19', 'N', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cal_actividad_materia_modo_area`
--

CREATE TABLE IF NOT EXISTS `cal_actividad_materia_modo_area` (
  `id_actividad_materia_modo_area` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_actividad` varchar(100) NOT NULL,
  `descripcion_actividad` varchar(150) NOT NULL,
  `fecha_presentacion` date NOT NULL,
  `confirmado` char(2) NOT NULL,
  `estado` char(2) NOT NULL,
  `modo_calificacion_area_calificacion_id` int(11) NOT NULL,
  `aula_paralelo_profesor_materia_id` int(11) NOT NULL,
  PRIMARY KEY (`id_actividad_materia_modo_area`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=120 ;

--
-- Volcado de datos para la tabla `cal_actividad_materia_modo_area`
--

INSERT INTO `cal_actividad_materia_modo_area` (`id_actividad_materia_modo_area`, `nombre_actividad`, `descripcion_actividad`, `fecha_presentacion`, `confirmado`, `estado`, `modo_calificacion_area_calificacion_id`, `aula_paralelo_profesor_materia_id`) VALUES
(26, 'lectura', '', '2019-03-29', 'S', 'A', 1, 6),
(27, 'escritura', '', '2019-03-29', 'N', 'A', 1, 6),
(28, 'prueba', '', '2019-04-01', 'S', 'A', 1, 6),
(29, 'prueba 2', '', '2019-04-01', 'S', 'A', 1, 6),
(30, 'ad', '', '2019-04-04', 'N', 'A', 1, 6),
(31, 'asd', 'descripcion de asd', '2019-04-22', 'S', 'A', 1, 6),
(32, 'seer1', '', '2019-04-23', 'S', 'A', 1, 5),
(33, 'sabeer1', '', '2019-04-23', 'S', 'A', 2, 5),
(34, 'ser2', '', '2019-04-24', 'N', 'A', 2, 5),
(35, 'ser3', '', '2019-04-23', 'S', 'A', 2, 5),
(36, 'hacer', '', '2019-04-23', 'S', 'A', 3, 5),
(37, 'decidir', '', '2019-04-23', 'S', 'A', 4, 5),
(38, 'auto', '', '2019-04-23', 'S', 'A', 5, 5),
(39, 'comportamiento', '', '2019-04-29', 'N', 'A', 1, 6),
(40, 'Ser 1', '', '2019-05-03', 'S', 'A', 1, 6),
(41, 'ser3', '', '2019-05-03', 'S', 'A', 1, 6),
(42, 'Ser 4', '', '2019-05-04', 'N', 'A', 1, 6),
(43, 'Saber 1', '', '2019-05-03', 'S', 'A', 2, 6),
(44, 'Saber 3', '', '2019-05-03', 'S', 'A', 2, 6),
(45, 'Hacer 1', '', '2019-05-03', 'S', 'A', 3, 6),
(46, 'hacer 3', '', '2019-05-03', 'S', 'A', 3, 6),
(47, 'Decidir 1', '', '2019-05-03', 'S', 'A', 4, 6),
(48, 'Auto Evaluacion 1', '', '2019-05-03', 'S', 'A', 5, 6),
(49, 'Auto Evaluacion 2', '', '2019-05-03', 'N', 'A', 5, 6),
(50, '45', '', '2019-05-03', 'N', 'A', 5, 6),
(51, 'ser  1', '', '2019-05-03', 'S', 'A', 6, 6),
(52, 'ser 2', '', '2019-05-04', 'N', 'A', 6, 6),
(53, 'Saber 1', '', '2019-05-03', 'S', 'A', 7, 6),
(54, 'Saber 3', '', '2019-05-03', 'S', 'A', 7, 6),
(55, 'Hacer 1', '', '2019-05-03', 'S', 'A', 8, 6),
(56, 'hacer 2', '', '2019-05-03', 'S', 'A', 8, 6),
(57, 'Decidir 1', '', '2019-05-03', 'S', 'A', 9, 6),
(58, 'Decidir 2', '', '2019-05-03', 'S', 'A', 9, 6),
(59, 'Auto Evaluacion 1', '', '2019-05-03', 'S', 'A', 10, 6),
(60, 'Auto Evaluacion 2', '', '2019-05-03', 'S', 'A', 10, 6),
(61, 'ser 1', '', '2019-05-03', 'S', 'A', 11, 6),
(62, 'ser 2', '', '2019-05-03', 'S', 'A', 11, 6),
(63, 'Hacer 1', '', '2019-05-03', 'S', 'A', 15, 6),
(64, 'Hacer 2', '', '2019-05-03', 'S', 'A', 15, 6),
(65, 'Decidir 1', '', '2019-05-03', 'S', 'A', 16, 6),
(66, 'Decidir 2', '', '2019-05-03', 'S', 'A', 16, 6),
(67, 'Auto Evaluacion 1', '', '2019-05-03', 'S', 'A', 17, 6),
(68, 'Auto Evaluacion 2', '', '2019-05-03', 'S', 'A', 17, 6),
(69, 'ser  1', '', '2019-05-03', 'S', 'A', 18, 6),
(70, 'ser 2', '', '2019-05-03', 'S', 'A', 18, 6),
(71, 'Saber 1', '', '2019-05-03', 'S', 'A', 19, 6),
(72, 'Saber 2', '', '2019-05-03', 'N', 'A', 19, 6),
(73, 'Hacer 1', '', '2019-05-03', 'S', 'A', 20, 6),
(74, 'hacer 2', '', '2019-05-03', 'S', 'A', 20, 6),
(75, 'Decidir 1', '', '2019-05-03', 'S', 'A', 21, 6),
(76, 'Decidir 2', '', '2019-05-03', 'S', 'A', 21, 6),
(77, 'Auto Evaluacion 1', '', '2019-05-03', 'S', 'A', 22, 6),
(78, 'Auto Evaluacion 2', '', '2019-05-03', 'S', 'A', 22, 6),
(79, 'Ser 1', '', '2019-05-03', 'S', 'A', 1, 5),
(80, 'ser 2', '', '2019-05-03', 'S', 'A', 1, 5),
(81, 'Saber 1', '', '2019-05-03', 'S', 'A', 2, 5),
(82, 'Saber 2', '', '2019-05-03', 'N', 'A', 2, 5),
(83, 'Hacer 1', '', '2019-05-03', 'S', 'A', 3, 5),
(84, 'hacer 2', '', '2019-05-03', 'N', 'A', 3, 5),
(85, 'Decidir 1', '', '2019-05-03', 'S', 'A', 4, 5),
(86, 'Decidir 2', '', '2019-05-03', 'S', 'A', 4, 5),
(87, 'Auto Evaluacion 1', '', '2019-05-03', 'S', 'A', 5, 5),
(88, 'Auto Evaluacion 2', '', '2019-05-03', 'S', 'A', 5, 5),
(89, 'ser  1', '', '2019-05-03', 'S', 'A', 11, 5),
(90, 'ser 2', '', '2019-05-03', 'S', 'A', 11, 5),
(91, 'Hacer 1', '', '2019-05-03', 'S', 'A', 15, 5),
(92, 'Hacer 2', '', '2019-05-03', 'S', 'A', 15, 5),
(93, 'Decidir 1', '', '2019-05-03', 'S', 'A', 16, 5),
(94, 'Decidir 2', '', '2019-05-03', 'S', 'A', 16, 5),
(95, 'Auto Evaluacion 1', '', '2019-05-03', 'S', 'A', 17, 5),
(96, 'Auto Evaluacion 2', '', '2019-05-03', 'S', 'A', 17, 5),
(97, 'Saber 1', '', '2019-05-03', 'N', 'A', 2, 5),
(98, 'ser  1', '', '2019-05-03', 'S', 'A', 6, 5),
(99, 'ser 2', '', '2019-05-03', 'S', 'A', 6, 5),
(100, 'ser 2', '', '2019-05-03', 'N', 'I', 6, 5),
(101, 'Saber 1', '', '2019-05-03', 'S', 'A', 7, 5),
(102, 'Saber 2', '', '2019-05-03', 'S', 'A', 7, 5),
(103, 'Hacer 1', '', '2019-05-03', 'S', 'A', 8, 5),
(104, 'hacer 2', '', '2019-05-03', 'S', 'A', 8, 5),
(105, 'Decidir 1', '', '2019-05-03', 'S', 'A', 9, 5),
(106, 'Decidir 2', '', '2019-05-03', 'S', 'A', 9, 5),
(107, 'Auto Evaluacion 1', '', '2019-05-03', 'S', 'A', 10, 5),
(108, 'Auto Evaluacion 2', '', '2019-05-03', 'S', 'A', 10, 5),
(109, 'ser  1', '', '2019-05-03', 'S', 'A', 18, 5),
(110, 'ser  1', '', '2019-05-03', 'N', 'I', 18, 5),
(111, 'ser 2', '', '2019-05-03', 'S', 'A', 18, 5),
(112, 'Saber 1', '', '2019-05-03', 'S', 'A', 19, 5),
(113, 'Saber 2', '', '2019-05-03', 'S', 'A', 19, 5),
(114, 'Hacer 1', '', '2019-05-03', 'S', 'A', 20, 5),
(115, 'hacer 2', '', '2019-05-03', 'S', 'A', 20, 5),
(116, 'Decidir 1', '', '2019-05-03', 'S', 'A', 21, 5),
(117, 'Decidir 2', '', '2019-05-03', 'S', 'A', 21, 5),
(118, 'Auto Evaluacion 1', '', '2019-05-03', 'S', 'A', 22, 5),
(119, 'Auto Evaluacion 2', '', '2019-05-03', 'S', 'A', 22, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cal_area_calificacion`
--

CREATE TABLE IF NOT EXISTS `cal_area_calificacion` (
  `id_area_calificacion` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) NOT NULL,
  `ponderado` int(11) NOT NULL,
  `gestion_id` int(11) NOT NULL,
  PRIMARY KEY (`id_area_calificacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `cal_area_calificacion`
--

INSERT INTO `cal_area_calificacion` (`id_area_calificacion`, `descripcion`, `ponderado`, `gestion_id`) VALUES
(1, 'SER', 15, 1),
(2, 'SABER', 30, 1),
(3, 'HACER', 30, 1),
(4, 'DECIDIR', 15, 1),
(5, 'AUTO EVALUACION', 10, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cal_detalle_nota_area_calificacion`
--

CREATE TABLE IF NOT EXISTS `cal_detalle_nota_area_calificacion` (
  `id_detalle_nota_area_calificacion` int(11) NOT NULL AUTO_INCREMENT,
  `nota` int(11) NOT NULL,
  `estado` char(2) NOT NULL,
  `fecha_registro` date NOT NULL,
  `fecha_modificacion` date NOT NULL,
  `actividad_id` int(11) NOT NULL,
  `actividad_fecha_id` int(11) NOT NULL,
  PRIMARY KEY (`id_detalle_nota_area_calificacion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cal_estudiante_actividad_nota`
--

CREATE TABLE IF NOT EXISTS `cal_estudiante_actividad_nota` (
  `id_estudiante_actividad_nota` int(11) NOT NULL AUTO_INCREMENT,
  `nota` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `actividad_materia_modo_area_id` int(11) NOT NULL,
  PRIMARY KEY (`id_estudiante_actividad_nota`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=145 ;

--
-- Volcado de datos para la tabla `cal_estudiante_actividad_nota`
--

INSERT INTO `cal_estudiante_actividad_nota` (`id_estudiante_actividad_nota`, `nota`, `estudiante_id`, `actividad_materia_modo_area_id`) VALUES
(1, 78, 4, 40),
(2, 89, 5, 40),
(3, 0, 4, 41),
(4, 0, 5, 41),
(5, 78, 4, 43),
(6, 78, 5, 43),
(7, 65, 4, 44),
(8, 66, 5, 44),
(9, 89, 4, 45),
(10, 90, 5, 45),
(11, 75, 4, 46),
(12, 76, 5, 46),
(13, 100, 4, 47),
(14, 100, 5, 47),
(15, 78, 4, 48),
(16, 0, 5, 48),
(17, 23, 4, 51),
(18, 43, 5, 51),
(19, 89, 4, 53),
(20, 98, 5, 53),
(21, 0, 4, 54),
(22, 78, 5, 54),
(23, 20, 4, 55),
(24, 20, 5, 55),
(25, 0, 4, 56),
(26, 0, 5, 56),
(27, 89, 4, 57),
(28, 89, 5, 57),
(29, 56, 4, 58),
(30, 65, 5, 58),
(31, 78, 4, 59),
(32, 98, 5, 59),
(33, 100, 4, 60),
(34, 100, 5, 60),
(35, 78, 4, 61),
(36, 87, 5, 61),
(37, 89, 4, 62),
(38, 78, 5, 62),
(39, 78, 4, 63),
(40, 45, 5, 63),
(41, 100, 4, 64),
(42, 100, 5, 64),
(43, 89, 4, 65),
(44, 78, 5, 65),
(45, 78, 4, 66),
(46, 98, 5, 66),
(47, 80, 4, 67),
(48, 70, 5, 67),
(49, 89, 4, 68),
(50, 78, 5, 68),
(51, 78, 4, 69),
(52, 98, 5, 69),
(53, 65, 4, 70),
(54, 65, 5, 70),
(55, 78, 4, 71),
(56, 56, 5, 71),
(57, 78, 4, 73),
(58, 98, 5, 73),
(59, 75, 4, 74),
(60, 58, 5, 74),
(61, 78, 4, 75),
(62, 87, 5, 75),
(63, 79, 4, 76),
(64, 97, 5, 76),
(65, 45, 4, 77),
(66, 45, 5, 77),
(67, 86, 4, 78),
(68, 68, 5, 78),
(69, 78, 4, 79),
(70, 98, 5, 79),
(71, 78, 4, 80),
(72, 58, 5, 80),
(73, 78, 4, 81),
(74, 89, 5, 81),
(75, 78, 4, 83),
(76, 54, 5, 83),
(77, 45, 4, 85),
(78, 65, 5, 85),
(79, 89, 4, 86),
(80, 68, 5, 86),
(81, 0, 4, 87),
(82, 100, 5, 87),
(83, 89, 4, 88),
(84, 98, 5, 88),
(85, 78, 4, 89),
(86, 87, 5, 89),
(87, 68, 4, 90),
(88, 86, 5, 90),
(89, 78, 4, 91),
(90, 89, 5, 91),
(91, 89, 4, 92),
(92, 95, 5, 92),
(93, 78, 4, 93),
(94, 75, 5, 93),
(95, 65, 4, 94),
(96, 78, 5, 94),
(97, 100, 4, 95),
(98, 90, 5, 95),
(99, 100, 4, 95),
(100, 90, 5, 95),
(101, 90, 4, 96),
(102, 86, 5, 96),
(103, 78, 4, 98),
(104, 75, 5, 98),
(105, 85, 4, 99),
(106, 85, 4, 99),
(107, 57, 5, 99),
(108, 57, 5, 99),
(109, 78, 4, 101),
(110, 58, 5, 101),
(111, 89, 4, 102),
(112, 98, 5, 102),
(113, 89, 4, 103),
(114, 77, 5, 103),
(115, 69, 4, 104),
(116, 68, 5, 104),
(117, 78, 4, 105),
(118, 89, 5, 105),
(119, 68, 4, 106),
(120, 78, 5, 106),
(121, 78, 4, 107),
(122, 87, 5, 107),
(123, 68, 4, 108),
(124, 59, 5, 108),
(125, 78, 4, 109),
(126, 54, 5, 109),
(127, 89, 4, 111),
(128, 87, 5, 111),
(129, 78, 4, 112),
(130, 89, 5, 112),
(131, 79, 4, 113),
(132, 58, 5, 113),
(133, 78, 4, 114),
(134, 68, 5, 114),
(135, 87, 4, 115),
(136, 65, 5, 115),
(137, 75, 4, 116),
(138, 85, 5, 116),
(139, 65, 4, 117),
(140, 67, 5, 117),
(141, 89, 4, 118),
(142, 87, 5, 118),
(143, 75, 4, 119),
(144, 75, 5, 119);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cal_modo_calificacion`
--

CREATE TABLE IF NOT EXISTS `cal_modo_calificacion` (
  `id_modo_calificacion` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_inicio` date NOT NULL,
  `fecha_final` date NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `gestion_id` int(11) NOT NULL,
  PRIMARY KEY (`id_modo_calificacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `cal_modo_calificacion`
--

INSERT INTO `cal_modo_calificacion` (`id_modo_calificacion`, `fecha_inicio`, `fecha_final`, `descripcion`, `gestion_id`) VALUES
(1, '2019-02-04', '2019-04-30', '1er Bimestre', 1),
(2, '2019-05-01', '2019-06-30', '2do Bimestre', 1),
(3, '2019-07-01', '2019-08-30', '3er Bimestre', 1),
(4, '2019-09-02', '2019-11-29', '4to Bimestre', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cal_modo_calificacion_area_calificaion`
--

CREATE TABLE IF NOT EXISTS `cal_modo_calificacion_area_calificaion` (
  `id_modo_calificacion_area_calificacion` int(11) NOT NULL AUTO_INCREMENT,
  `modo_calificacion_id` int(11) NOT NULL,
  `area_calificacion_id` int(11) NOT NULL,
  PRIMARY KEY (`id_modo_calificacion_area_calificacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Volcado de datos para la tabla `cal_modo_calificacion_area_calificaion`
--

INSERT INTO `cal_modo_calificacion_area_calificaion` (`id_modo_calificacion_area_calificacion`, `modo_calificacion_id`, `area_calificacion_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 2, 1),
(7, 2, 2),
(8, 2, 3),
(9, 2, 4),
(10, 2, 5),
(11, 3, 1),
(12, 3, 2),
(15, 3, 3),
(16, 3, 4),
(17, 3, 5),
(18, 4, 1),
(19, 4, 2),
(20, 4, 3),
(21, 4, 4),
(22, 4, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo`
--

CREATE TABLE IF NOT EXISTS `catalogo` (
  `id_catalogo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_catalogo` varchar(50) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `codigo` int(11) NOT NULL,
  PRIMARY KEY (`id_catalogo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `catalogo`
--

INSERT INTO `catalogo` (`id_catalogo`, `nombre_catalogo`, `descripcion`, `codigo`) VALUES
(1, 'Tipo de Documento', '', 1),
(2, 'Roles', 'aqui se encuentran todos los usuarios', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo_detalle`
--

CREATE TABLE IF NOT EXISTS `catalogo_detalle` (
  `id_catalogo_detalle` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_catalogo_detalle` varchar(50) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `codigo` int(11) NOT NULL,
  `catalogo_id` int(11) NOT NULL,
  PRIMARY KEY (`id_catalogo_detalle`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `catalogo_detalle`
--

INSERT INTO `catalogo_detalle` (`id_catalogo_detalle`, `nombre_catalogo_detalle`, `descripcion`, `codigo`, `catalogo_id`) VALUES
(1, 'CI', '', 1, 1),
(2, 'Pasaporte', '', 2, 1),
(3, 'CI extranjero', '', 3, 1),
(4, 'Tutor', 'responsable del estudiante', 1, 2),
(5, 'Estudiante', '', 2, 2),
(6, 'Profesor', '', 3, 2),
(7, 'Administrativo', 'puede manejar la administración del sistema', 4, 2),
(8, 'Días de Trabajo', 'Se define los días a trabajar', 5, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ins_aula`
--

CREATE TABLE IF NOT EXISTS `ins_aula` (
  `id_aula` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_aula` varchar(10) DEFAULT NULL,
  `descripcion` varchar(100) NOT NULL,
  `nivel_academico_id` int(11) NOT NULL,
  PRIMARY KEY (`id_aula`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Volcado de datos para la tabla `ins_aula`
--

INSERT INTO `ins_aula` (`id_aula`, `nombre_aula`, `descripcion`, `nivel_academico_id`) VALUES
(1, '1ro', 'Primaria', 2),
(2, '1ro', 'Secundaria', 3),
(3, '2do', 'Primaria', 2),
(4, '2do', 'Secundaria', 3),
(5, '3ro', 'Primaria', 2),
(6, '3ro', 'Secundaria', 3),
(7, '4to', 'Primaria', 2),
(8, '4to', 'Secundaria', 3),
(9, '5to', 'Primaria', 2),
(10, '5to', 'Secundaria', 3),
(11, 'Pre Kinder', 'pre kinder', 1),
(12, 'Kinder', 'kinder', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ins_aula_paralelo`
--

CREATE TABLE IF NOT EXISTS `ins_aula_paralelo` (
  `id_aula_paralelo` int(11) NOT NULL AUTO_INCREMENT,
  `aula_id` int(11) NOT NULL,
  `paralelo_id` int(11) NOT NULL,
  `capacidad` int(11) NOT NULL,
  PRIMARY KEY (`id_aula_paralelo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Volcado de datos para la tabla `ins_aula_paralelo`
--

INSERT INTO `ins_aula_paralelo` (`id_aula_paralelo`, `aula_id`, `paralelo_id`, `capacidad`) VALUES
(1, 1, 1, 30),
(2, 1, 2, 35),
(3, 3, 1, 30),
(4, 3, 2, 30),
(5, 5, 1, 30),
(6, 5, 2, 32),
(7, 7, 1, 30),
(8, 7, 2, 30),
(9, 9, 1, 30),
(10, 9, 2, 30),
(11, 11, 1, 30),
(12, 11, 2, 30),
(13, 12, 1, 35),
(14, 12, 2, 35);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ins_estudiante`
--

CREATE TABLE IF NOT EXISTS `ins_estudiante` (
  `id_estudiante` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_estudiante` varchar(45) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `aula_paralelo_id` int(11) NOT NULL,
  PRIMARY KEY (`id_estudiante`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Volcado de datos para la tabla `ins_estudiante`
--

INSERT INTO `ins_estudiante` (`id_estudiante`, `codigo_estudiante`, `persona_id`, `aula_paralelo_id`) VALUES
(1, 'E1', 1, 13),
(2, 'E2', 4, 1),
(3, 'E3', 6, 1),
(4, 'E4', 8, 4),
(5, 'E5', 10, 4),
(6, 'E6', 11, 4),
(7, 'E7', 28, 4),
(8, 'E8', 29, 5),
(9, 'E9', 30, 5),
(10, 'E10', 31, 5),
(11, 'E11', 32, 5),
(12, 'E12', 32, 7),
(13, 'E13', 34, 7),
(14, 'E14', 35, 7),
(15, 'E15', 36, 7),
(16, 'E16', 37, 7),
(17, 'E17', 38, 7),
(18, 'E18', 39, 1),
(19, 'E19', 46, 2),
(20, 'E20', 47, 2),
(21, 'E21', 48, 2),
(22, 'E22', 49, 3),
(23, 'E23', 50, 3),
(24, 'E24', 51, 6),
(25, 'E25', 52, 8),
(26, 'E26', 53, 8),
(27, 'E26', 54, 8),
(28, 'E27', 55, 9),
(29, 'E28', 56, 9),
(30, 'E29', 57, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ins_estudiante_familiar`
--

CREATE TABLE IF NOT EXISTS `ins_estudiante_familiar` (
  `id_estudiante_familiar` int(11) NOT NULL AUTO_INCREMENT,
  `familiar_id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `tutor` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_estudiante_familiar`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=86 ;

--
-- Volcado de datos para la tabla `ins_estudiante_familiar`
--

INSERT INTO `ins_estudiante_familiar` (`id_estudiante_familiar`, `familiar_id`, `estudiante_id`, `tutor`) VALUES
(56, 1, 1, 1),
(57, 1, 2, 1),
(58, 3, 3, 1),
(59, 4, 4, 1),
(60, 5, 5, 1),
(61, 6, 7, 1),
(62, 7, 8, 1),
(63, 8, 9, 1),
(64, 9, 10, 1),
(65, 10, 11, 1),
(66, 10, 12, 1),
(67, 1, 13, 1),
(68, 11, 14, 1),
(69, 6, 15, 1),
(70, 2, 16, 1),
(71, 2, 17, 1),
(72, 12, 18, 1),
(73, 13, 19, 1),
(75, 15, 20, 1),
(76, 16, 21, 1),
(77, 17, 22, 1),
(78, 16, 23, 1),
(79, 17, 24, 1),
(80, 19, 25, 1),
(81, 20, 26, 1),
(82, 24, 27, 1),
(83, 19, 28, 1),
(84, 20, 29, 1),
(85, 24, 30, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ins_familiar`
--

CREATE TABLE IF NOT EXISTS `ins_familiar` (
  `id_familiar` int(11) NOT NULL AUTO_INCREMENT,
  `profesion` varchar(100) NOT NULL,
  `direccion_oficina` varchar(45) NOT NULL,
  `telefono_oficina` varchar(45) NOT NULL,
  `persona_id` int(11) NOT NULL,
  PRIMARY KEY (`id_familiar`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Volcado de datos para la tabla `ins_familiar`
--

INSERT INTO `ins_familiar` (`id_familiar`, `profesion`, `direccion_oficina`, `telefono_oficina`, `persona_id`) VALUES
(1, 'Abogado', 'Almirante Graund y General Gonzales Nro 78', '7845812', 2),
(2, 'arquitecto', 'el alto', '2808456', 5),
(3, 'Diseñador Gráfico', 'Almirante Graund y General Gonzales Nro 78', '', 7),
(4, 'Contador', '', '', 9),
(5, 'Informatico', '', '', 12),
(6, 'Abogado', 'calle 5 calacoto obrajes', '', 22),
(7, 'Medico', 'Mariscal Santa Cruz esquina Sagarnada', '', 23),
(8, 'Arquitecto', '', '', 24),
(9, 'Decorador de Interiores', '', '', 25),
(10, 'Administrador de Empresas', '', '', 26),
(11, 'Contador', '', '', 27),
(12, 'Cajero', '', '', 40),
(13, 'Asesor de creditos', '', '', 41),
(14, 'Informatico', '', '', 42),
(15, 'Ingeniero Comercial', '', '', 43),
(16, 'Abogado', '', '', 44),
(17, 'Profesor', '', '', 45),
(19, 'profesor', 'a', '', 65),
(20, 'abogado', '', '7845', 66),
(24, 'asesor', 'Zona Villa dolores calle 3', '', 70);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ins_gestion`
--

CREATE TABLE IF NOT EXISTS `ins_gestion` (
  `id_gestion` int(11) NOT NULL AUTO_INCREMENT,
  `gestion` int(11) NOT NULL,
  `inicio_gestion` date NOT NULL,
  `final_gestion` date NOT NULL,
  `inicio_vacaciones` date NOT NULL,
  `final_vacaciones` date NOT NULL,
  PRIMARY KEY (`id_gestion`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `ins_gestion`
--

INSERT INTO `ins_gestion` (`id_gestion`, `gestion`, `inicio_gestion`, `final_gestion`, `inicio_vacaciones`, `final_vacaciones`) VALUES
(1, 2019, '2019-02-04', '2019-11-29', '2019-06-03', '2019-06-21'),
(2, 2018, '2018-02-05', '2018-11-30', '2018-06-04', '2018-06-22'),
(6, 2020, '2019-02-04', '2019-12-02', '0000-00-00', '0000-00-00'),
(7, 2022, '2019-02-05', '2019-12-03', '0000-00-00', '0000-00-00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ins_inscripcion`
--

CREATE TABLE IF NOT EXISTS `ins_inscripcion` (
  `id_inscripcion` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_inscripcion` datetime DEFAULT NULL,
  `aula_paralelo_id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `tipo_estudiante_id` int(11) NOT NULL,
  `nivel_academico_id` int(11) NOT NULL,
  `gestion_id` int(11) NOT NULL,
  PRIMARY KEY (`id_inscripcion`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=71 ;

--
-- Volcado de datos para la tabla `ins_inscripcion`
--

INSERT INTO `ins_inscripcion` (`id_inscripcion`, `fecha_inscripcion`, `aula_paralelo_id`, `tutor_id`, `estudiante_id`, `tipo_estudiante_id`, `nivel_academico_id`, `gestion_id`) VALUES
(41, '2019-05-08 18:53:07', 13, 1, 1, 10, 1, 1),
(42, '2019-05-08 19:01:55', 1, 1, 2, 10, 2, 1),
(43, '2019-05-08 19:02:37', 1, 3, 3, 10, 2, 1),
(44, '2019-05-09 09:27:57', 4, 4, 4, 10, 2, 1),
(45, '2019-05-09 09:28:36', 4, 5, 5, 10, 2, 1),
(46, '2019-05-09 09:56:17', 4, 6, 7, 10, 2, 1),
(47, '2019-05-09 09:56:50', 5, 7, 8, 10, 2, 1),
(48, '2019-05-09 09:58:12', 5, 8, 9, 10, 2, 1),
(49, '2019-05-09 09:59:07', 5, 9, 10, 10, 2, 1),
(50, '2019-05-09 10:00:07', 5, 10, 11, 10, 2, 1),
(51, '2019-05-09 10:00:44', 7, 10, 12, 10, 2, 1),
(52, '2019-05-09 10:01:23', 7, 1, 13, 10, 2, 1),
(53, '2019-05-09 10:02:44', 7, 11, 14, 10, 2, 1),
(54, '2019-05-09 10:03:28', 7, 6, 15, 11, 2, 1),
(55, '2019-05-09 10:04:11', 7, 2, 16, 10, 2, 1),
(56, '2019-05-09 10:04:34', 7, 2, 17, 10, 2, 1),
(57, '2019-05-09 10:05:41', 1, 12, 18, 10, 2, 1),
(58, '2019-05-09 10:06:33', 1, 13, 19, 10, 2, 1),
(59, '2019-05-09 10:08:19', 2, 13, 19, 10, 2, 1),
(60, '2019-05-09 10:09:30', 2, 15, 20, 10, 2, 1),
(61, '2019-05-09 10:09:51', 2, 16, 21, 10, 2, 1),
(62, '2019-05-09 10:10:33', 3, 17, 22, 10, 2, 1),
(63, '2019-05-09 10:11:57', 3, 16, 23, 10, 2, 1),
(64, '2019-05-09 10:13:03', 6, 17, 24, 10, 2, 1),
(65, '2019-05-09 10:14:04', 8, 19, 25, 10, 2, 1),
(66, '2019-05-09 10:14:37', 8, 20, 26, 10, 2, 1),
(67, '2019-05-09 10:15:02', 8, 24, 27, 10, 2, 1),
(68, '2019-05-09 10:15:29', 9, 19, 28, 10, 2, 1),
(69, '2019-05-09 10:16:10', 9, 20, 29, 10, 2, 1),
(70, '2019-05-09 10:17:02', 13, 24, 30, 10, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ins_nivel_academico`
--

CREATE TABLE IF NOT EXISTS `ins_nivel_academico` (
  `id_nivel_academico` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_nivel` varchar(50) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `gestion_id` int(11) NOT NULL,
  PRIMARY KEY (`id_nivel_academico`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `ins_nivel_academico`
--

INSERT INTO `ins_nivel_academico` (`id_nivel_academico`, `nombre_nivel`, `descripcion`, `fecha_registro`, `gestion_id`) VALUES
(1, 'Inicial', 'Kinder y Pre kinder', '2019-05-02 09:52:11', 1),
(2, 'Primaria', 'de 1ro a 6to', '2019-05-02 11:45:44', 1),
(3, 'Secundaria', 'de 1ro a 6to', '2019-05-02 11:46:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ins_paralelo`
--

CREATE TABLE IF NOT EXISTS `ins_paralelo` (
  `id_paralelo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_paralelo` varchar(50) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  PRIMARY KEY (`id_paralelo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `ins_paralelo`
--

INSERT INTO `ins_paralelo` (`id_paralelo`, `nombre_paralelo`, `descripcion`) VALUES
(1, 'A', ''),
(2, 'B', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ins_tipo_estudiante`
--

CREATE TABLE IF NOT EXISTS `ins_tipo_estudiante` (
  `id_tipo_estudiante` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_tipo_estudiante` varchar(50) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `gestion_id` int(11) NOT NULL,
  PRIMARY KEY (`id_tipo_estudiante`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Volcado de datos para la tabla `ins_tipo_estudiante`
--

INSERT INTO `ins_tipo_estudiante` (`id_tipo_estudiante`, `nombre_tipo_estudiante`, `descripcion`, `fecha_registro`, `gestion_id`) VALUES
(9, 'Nuevo', 'Hace referencia a un nuevo estudiante', '2019-05-02 11:11:15', 1),
(10, 'Antiguo', 'Hace referencia a un estudiante antiguo', '2019-05-02 11:13:32', 1),
(11, 'Becado', 'Hace referencia a un estudiante becado', '2019-05-02 11:14:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ins_tutor`
--

CREATE TABLE IF NOT EXISTS `ins_tutor` (
  `id_tutor` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(100) NOT NULL,
  `familiar_id` int(11) NOT NULL,
  PRIMARY KEY (`id_tutor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `int_asistencia_estudiante_materia`
--

CREATE TABLE IF NOT EXISTS `int_asistencia_estudiante_materia` (
  `id_asistencia_estudiante_materia` int(11) NOT NULL AUTO_INCREMENT,
  `estudiante_id` int(11) NOT NULL,
  `json_asistencia` text NOT NULL,
  `profesor_materia_id` int(11) NOT NULL,
  `modo_calificacion_area_calificacion_id` int(11) NOT NULL,
  PRIMARY KEY (`id_asistencia_estudiante_materia`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Volcado de datos para la tabla `int_asistencia_estudiante_materia`
--

INSERT INTO `int_asistencia_estudiante_materia` (`id_asistencia_estudiante_materia`, `estudiante_id`, `json_asistencia`, `profesor_materia_id`, `modo_calificacion_area_calificacion_id`) VALUES
(1, 8, '2019-05-02,1@', 2, 0),
(2, 10, '2019-05-02,1@', 2, 0),
(3, 12, '2019-05-02,1@', 2, 0),
(4, 14, '2019-05-02,1@', 2, 0),
(5, 16, '2019-05-02,1@', 2, 0),
(6, 18, '2019-05-02,1@', 2, 0),
(7, 4, '2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@', 1, 0),
(8, 5, '2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@2019-05-03,1@', 1, 0),
(9, 4, '2019-05-04,1@', 2, 0),
(10, 5, '2019-05-04,1@', 2, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `int_aula_paralelo_profesor_materia`
--

CREATE TABLE IF NOT EXISTS `int_aula_paralelo_profesor_materia` (
  `id_aula_paralelo_profesor_materia` int(11) NOT NULL AUTO_INCREMENT,
  `aula_paralelo_id` int(11) NOT NULL,
  `profesor_materia_id` int(11) NOT NULL,
  PRIMARY KEY (`id_aula_paralelo_profesor_materia`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

--
-- Volcado de datos para la tabla `int_aula_paralelo_profesor_materia`
--

INSERT INTO `int_aula_paralelo_profesor_materia` (`id_aula_paralelo_profesor_materia`, `aula_paralelo_id`, `profesor_materia_id`) VALUES
(1, 1, 8),
(2, 1, 4),
(3, 1, 3),
(4, 1, 7),
(5, 1, 2),
(6, 1, 1),
(7, 2, 8),
(8, 2, 4),
(9, 9, 5),
(10, 9, 9),
(11, 9, 1),
(12, 9, 11),
(13, 3, 4),
(14, 3, 4),
(15, 3, 7),
(16, 3, 7),
(17, 3, 1),
(18, 3, 10),
(19, 3, 11),
(20, 3, 5),
(21, 8, 5),
(22, 8, 8),
(23, 8, 4),
(24, 8, 3),
(25, 8, 1),
(26, 8, 7),
(27, 8, 6),
(28, 8, 2),
(30, 8, 10),
(31, 8, 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `int_nota_area_calificacion`
--

CREATE TABLE IF NOT EXISTS `int_nota_area_calificacion` (
  `id_nota_area_calificacion` int(11) NOT NULL AUTO_INCREMENT,
  `modo_calificacion_area_calificacion_id` int(11) NOT NULL,
  `asistencia_estudiante_materia_id` int(11) NOT NULL,
  PRIMARY KEY (`id_nota_area_calificacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=104 ;

--
-- Volcado de datos para la tabla `int_nota_area_calificacion`
--

INSERT INTO `int_nota_area_calificacion` (`id_nota_area_calificacion`, `modo_calificacion_area_calificacion_id`, `asistencia_estudiante_materia_id`) VALUES
(100, 1, 41),
(101, 1, 37),
(102, 1, 38),
(103, 1, 39);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pen_pensiones`
--

CREATE TABLE IF NOT EXISTS `pen_pensiones` (
  `id_pensiones` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_pension` varchar(50) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `mora_dia` decimal(10,2) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_final` date NOT NULL,
  `tipo_estudiante_id` int(11) NOT NULL,
  `nivel_academico_id` int(11) NOT NULL,
  `gestion_id` int(11) NOT NULL,
  PRIMARY KEY (`id_pensiones`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Volcado de datos para la tabla `pen_pensiones`
--

INSERT INTO `pen_pensiones` (`id_pensiones`, `nombre_pension`, `descripcion`, `monto`, `mora_dia`, `fecha_inicio`, `fecha_final`, `tipo_estudiante_id`, `nivel_academico_id`, `gestion_id`) VALUES
(1, 'Matricula gestion 2019 ', '', '300.00', '5.00', '2019-01-02', '2019-05-02', 10, 2, 1),
(2, 'Mensualidad de Enero', '', '300.00', '2.00', '2019-01-29', '2019-02-08', 10, 2, 1),
(3, 'Mensualidad Febrero', '', '300.00', '1.00', '2019-02-27', '2019-03-08', 10, 2, 1),
(4, 'Mensualidad Marzo', '', '300.00', '1.00', '2019-03-29', '2019-04-05', 10, 2, 1),
(5, 'Mensualidad Abril', '', '300.00', '1.00', '2019-04-29', '2019-05-03', 10, 2, 1),
(6, 'Mensualidad Mayo', '', '300.00', '1.00', '2019-05-31', '2019-06-07', 10, 2, 1),
(7, 'Matrícula gestión 2019', '', '500.00', '4.00', '2019-05-01', '2019-05-10', 10, 1, 1),
(8, 'Mensualidad mes de mayo', '', '300.00', '2.00', '2019-05-01', '2019-05-31', 10, 1, 1),
(9, 'Mensualidad mes de Junio', '', '300.00', '2.00', '2019-06-01', '2019-06-30', 10, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pen_pensiones_estudiante`
--

CREATE TABLE IF NOT EXISTS `pen_pensiones_estudiante` (
  `id_pensiones_estudiante` int(11) NOT NULL AUTO_INCREMENT,
  `pension_id` int(11) NOT NULL,
  `inscripcion_id` int(11) NOT NULL,
  `cancelado` char(5) NOT NULL,
  `fecha_cancelado` date NOT NULL,
  PRIMARY KEY (`id_pensiones_estudiante`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Volcado de datos para la tabla `pen_pensiones_estudiante`
--

INSERT INTO `pen_pensiones_estudiante` (`id_pensiones_estudiante`, `pension_id`, `inscripcion_id`, `cancelado`, `fecha_cancelado`) VALUES
(18, 7, 70, 'S', '2019-05-15'),
(19, 8, 70, 'N', '0000-00-00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pen_pensiones_estudiante_detalle`
--

CREATE TABLE IF NOT EXISTS `pen_pensiones_estudiante_detalle` (
  `id_pensiones_estudiante_detalle` int(11) NOT NULL AUTO_INCREMENT,
  `pensiones_estudiante_id` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `a_cuenta` decimal(10,2) NOT NULL,
  `saldo` decimal(10,2) NOT NULL,
  `familiar_id` int(11) NOT NULL,
  PRIMARY KEY (`id_pensiones_estudiante_detalle`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Volcado de datos para la tabla `pen_pensiones_estudiante_detalle`
--

INSERT INTO `pen_pensiones_estudiante_detalle` (`id_pensiones_estudiante_detalle`, `pensiones_estudiante_id`, `monto`, `a_cuenta`, `saldo`, `familiar_id`) VALUES
(20, 18, '520.00', '300.00', '220.00', 24),
(21, 18, '520.00', '220.00', '0.00', 24),
(22, 19, '300.00', '100.00', '200.00', 24);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pro_aspectos`
--

CREATE TABLE IF NOT EXISTS `pro_aspectos` (
  `id_aspectos` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_aspecto` varchar(45) DEFAULT NULL,
  `descripcion` varchar(45) NOT NULL,
  `puntuacion` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_aspectos`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pro_aspectos_trabajos`
--

CREATE TABLE IF NOT EXISTS `pro_aspectos_trabajos` (
  `aspectos_id` int(11) NOT NULL,
  `trabajos_id` int(11) NOT NULL,
  PRIMARY KEY (`aspectos_id`,`trabajos_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pro_clase`
--

CREATE TABLE IF NOT EXISTS `pro_clase` (
  `id_clase` int(11) NOT NULL AUTO_INCREMENT,
  `horario_id` int(11) NOT NULL,
  `profesor_id` int(11) NOT NULL,
  `trabajos_id` int(11) NOT NULL,
  `aula_id` int(11) NOT NULL,
  PRIMARY KEY (`id_clase`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pro_horarios`
--

CREATE TABLE IF NOT EXISTS `pro_horarios` (
  `id_horario` int(11) NOT NULL AUTO_INCREMENT,
  `hora_inicio` varchar(45) DEFAULT NULL,
  `hora_final` varchar(45) DEFAULT NULL,
  `descripcion` varchar(100) NOT NULL,
  PRIMARY KEY (`id_horario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `pro_horarios`
--

INSERT INTO `pro_horarios` (`id_horario`, `hora_inicio`, `hora_final`, `descripcion`) VALUES
(1, '08:05', '08:40', ''),
(2, '08:40', '09:20', ''),
(3, '09:20', '10:00', ''),
(4, '10:00', '10:40', ''),
(5, '10:40', '11:00', 'Recreo'),
(6, '11:00', '11:40', ''),
(7, '11:40', '12:20', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pro_materia`
--

CREATE TABLE IF NOT EXISTS `pro_materia` (
  `id_materia` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_materia` varchar(300) DEFAULT NULL,
  `descripcion` varchar(45) NOT NULL,
  PRIMARY KEY (`id_materia`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Volcado de datos para la tabla `pro_materia`
--

INSERT INTO `pro_materia` (`id_materia`, `nombre_materia`, `descripcion`) VALUES
(1, 'Comunicación y Lenguaje: Lenguas castellana y originaria', ''),
(2, 'Matemática', ''),
(3, 'Ciencias Sociales', ''),
(4, ' Ciencias Naturales: Biología - Geografía', ''),
(5, 'Educación Musical', ''),
(6, 'Educación Física y Deportes', ''),
(7, 'Artes Plásticas y Visuales', ''),
(8, 'Cosmovisiones, Filosofía y Psicología', ''),
(9, 'Técnica Tecnología General', ''),
(10, 'Valores, Espiritualidad y religiones', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pro_profesor`
--

CREATE TABLE IF NOT EXISTS `pro_profesor` (
  `id_profesor` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_profesor` varchar(45) DEFAULT NULL,
  `persona_id` int(11) NOT NULL,
  PRIMARY KEY (`id_profesor`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Volcado de datos para la tabla `pro_profesor`
--

INSERT INTO `pro_profesor` (`id_profesor`, `codigo_profesor`, `persona_id`) VALUES
(1, 'P1', 3),
(2, 'P2', 13),
(3, 'P3', 14),
(4, 'P4', 15),
(5, 'P5', 16),
(6, 'P6', 17),
(7, 'P7', 18),
(8, 'P8', 19),
(9, 'P9', 19),
(10, 'P10', 20),
(11, 'P11', 21);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pro_profesor_agenda_evento`
--

CREATE TABLE IF NOT EXISTS `pro_profesor_agenda_evento` (
  `id_profesor_agenda_evento` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_inicio` datetime NOT NULL,
  `fecha_final` datetime NOT NULL,
  `nombre_evento` varchar(100) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `color` varchar(50) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `roles` varchar(70) NOT NULL,
  PRIMARY KEY (`id_profesor_agenda_evento`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `pro_profesor_agenda_evento`
--

INSERT INTO `pro_profesor_agenda_evento` (`id_profesor_agenda_evento`, `fecha_inicio`, `fecha_final`, `nombre_evento`, `descripcion`, `color`, `usuario_id`, `roles`) VALUES
(1, '2019-05-02 09:00:00', '2019-05-02 10:30:00', 'reunion de padres de familia', '', '#008000', 3, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pro_profesor_materia`
--

CREATE TABLE IF NOT EXISTS `pro_profesor_materia` (
  `id_profesor_materia` int(11) NOT NULL,
  `profesor_id` int(11) NOT NULL,
  `materia_id` int(11) NOT NULL,
  PRIMARY KEY (`profesor_id`,`materia_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pro_profesor_materia`
--

INSERT INTO `pro_profesor_materia` (`id_profesor_materia`, `profesor_id`, `materia_id`) VALUES
(1, 1, 2),
(2, 1, 8),
(3, 2, 5),
(4, 3, 1),
(5, 4, 3),
(6, 5, 4),
(7, 6, 5),
(8, 7, 6),
(9, 8, 7),
(10, 9, 8),
(11, 10, 9),
(12, 11, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pro_trabajos`
--

CREATE TABLE IF NOT EXISTS `pro_trabajos` (
  `id_trabajos` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_trabajo` varchar(45) DEFAULT NULL,
  `descripcion` varchar(45) NOT NULL,
  PRIMARY KEY (`id_trabajos`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usu_correos`
--

CREATE TABLE IF NOT EXISTS `usu_correos` (
  `id_correos` int(11) NOT NULL AUTO_INCREMENT,
  `correo_electronico` varchar(50) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `persona_id` int(11) NOT NULL,
  PRIMARY KEY (`id_correos`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `usu_correos`
--

INSERT INTO `usu_correos` (`id_correos`, `correo_electronico`, `descripcion`, `persona_id`) VALUES
(1, 'juancito.pinto@gmail.com', ' ', 1),
(2, 'juan.pinto@hotmail.com', ' ', 2),
(3, 'martha.aguirre@yahoo.es', ' ', 3),
(4, 'juan.pinto@gmail.com', ' ', 2),
(5, 'martha2@gmail.com', NULL, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usu_persona`
--

CREATE TABLE IF NOT EXISTS `usu_persona` (
  `id_persona` int(11) NOT NULL AUTO_INCREMENT,
  `nombres` varchar(40) DEFAULT NULL,
  `primer_apellido` varchar(30) DEFAULT NULL,
  `segundo_apellido` varchar(30) NOT NULL,
  `tipo_documento` int(11) DEFAULT NULL,
  `numero_documento` int(11) DEFAULT NULL,
  `complemento` varchar(10) NOT NULL,
  `genero` char(2) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  PRIMARY KEY (`id_persona`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=71 ;

--
-- Volcado de datos para la tabla `usu_persona`
--

INSERT INTO `usu_persona` (`id_persona`, `nombres`, `primer_apellido`, `segundo_apellido`, `tipo_documento`, `numero_documento`, `complemento`, `genero`, `fecha_nacimiento`) VALUES
(1, 'juancito', 'pinto', 'limachi', 1, 7485152, '', 'v', '2010-02-05'),
(2, 'Juan', 'Pinto', 'Mamani', 2, 7485125, '', 'v', '1990-02-12'),
(3, 'Martha', 'Aguirre', 'Alarcon', 3, 7485121, '', 'm', '1985-02-13'),
(4, 'Luicito', 'Mendoza', 'Ticona', 1, 6895874, '', 'v', '2010-02-14'),
(5, 'Luis', 'Mendoza', 'Flores', 1, 2641523, '', 'v', '2010-02-10'),
(6, 'Fabio', 'Choque', 'Mamani', 1, 7894842, '', 'v', '2010-02-19'),
(7, 'Fabio Daniel', 'Choque', 'Mendez', 2, 7481254, '', 'v', '1980-02-23'),
(8, 'Victorcito', 'Quispe', 'Flores', 3, 9587452, '', 'v', '2010-02-26'),
(9, 'Victor', 'Quispe', 'Alarcon', 3, 12541523, '', 'v', '1986-02-22'),
(10, 'Jesucito', 'Machicado', '', 1, 4567878, '', 'v', '2010-02-23'),
(11, 'Juana', 'Machicado', '', 1, 4785211, '', 'm', '2009-02-01'),
(12, 'Jesus', 'Machicado', 'Miranda', 1, 3695865, '', 'v', '1990-02-28'),
(13, 'Martin', 'Tola', 'Condori', 1, 4587485, '', 'v', '1980-02-21'),
(14, 'Luis Daniel', 'Alavi', 'Chipana', 1, 458451, '', 'v', '2011-03-05'),
(15, 'Miguel Adrian', 'Conde', 'Illatardo', 1, 784585, '', 'v', '2011-03-13'),
(16, 'Fabian', 'Condori', 'Yamir', 1, 56859412, '', 'v', '2011-03-18'),
(17, 'Nicolas Jhonathan', 'Gallardo', 'Sanchez', 1, 1234565, '', 'v', '2011-03-12'),
(18, 'Dolly', 'Huanca', 'Mamani', 1, 7845852, '', 'm', '2011-03-19'),
(19, 'Narda Gloria', 'Illanes', 'Mamani', 1, 1362589, '', 'm', '2011-03-20'),
(20, 'Rodrigo Adrian', 'Machicado', 'Ayllon', 1, 7784521, '', 'v', '2011-03-08'),
(21, 'Janet Isabela', 'Maldonado', 'Copa', 1, 7447785, '', 'm', '2011-03-13'),
(22, 'Nataly Massiel', 'Ali', 'Mendoza', 1, 6963652, '', 'm', '1985-02-13'),
(23, 'Araceli Fiorela', 'Aliaga', 'Santalla', 1, 9852741, '', 'm', '1990-02-12'),
(24, 'Yara', 'Alurralde ', 'Marien', 1, 7585458, '', 'm', '1985-02-15'),
(25, 'Nathalia Isabella', 'Antezana', 'Alcoba', 1, 7887147, '', 'm', '1986-02-25'),
(26, 'Martina', 'Aviles', 'Villapando', 1, 1369652, '', 'm', '1985-02-18'),
(27, 'Ignacio', 'Calderon', 'Vera', 1, 7896856, '', 'v', '1990-03-06'),
(28, 'Jhoel Omar', 'Antezana', 'Alcoba', 1, 7845963, '', 'v', '2010-02-07'),
(29, 'Yoel Fabricio', 'Arce', 'Raslan', 1, 3696956, '', 'v', '2011-03-13'),
(30, 'Itzel', 'Ayaviri', 'Sanjines', 1, 7845856, '', 'm', '2010-02-09'),
(31, 'Carolina Alejandra', 'Berdeja', 'Pinto', 1, 6985859, '', 'm', '2010-02-17'),
(32, 'Julian', 'Blacutt', 'Troche', 1, 8756896, '', 'v', '2010-02-02'),
(33, 'Gabriela Mecedes', 'Bohrt', 'Sanchez', 1, 4578898, '1cd', 'm', '2010-04-10'),
(34, 'Sergio Mateo', 'Borda', 'Calderon', 1, 1245856, '', 'v', '2010-08-07'),
(35, 'Mateo Gabriel', 'Claros', 'Finot', 1, 5896966, '1rf', 'v', '2010-05-10'),
(36, 'Valeria Alejandra', 'De Alencar', 'Beltran', 1, 2552363, '', 'm', '2011-02-07'),
(37, 'Luis Miguel', 'Delgadillo', 'Oblitas', 1, 5652522, '', 'v', '2011-04-13'),
(38, 'Josefina', 'España', 'Flores', 1, 7485744, '', 'm', '2010-02-09'),
(39, 'Luis Santiago', 'Franck', 'Osorio', 1, 7414412, '1ew', 'v', '2011-03-18'),
(40, 'Malena Liz', 'Canedo', 'Camacho', 1, 7484845, '', 'm', '2010-02-07'),
(41, 'Alejandro', 'Centellas de la Galvez', 'Murillo', 1, 4545855, '1er', 'v', '2010-02-10'),
(42, 'Avril Alexis', 'Chumacero', 'Salvatierra', 1, 3633236, '', 'm', '2010-01-04'),
(43, 'Narel Antonela', 'Guzman', 'Beltran', 1, 4111425, '', 'm', '2011-03-13'),
(44, 'Cecilia Gabriela', 'Guzman', 'Perez', 1, 4574111, '', 'm', '2010-02-07'),
(45, 'Dafne Camila', 'Illanes', 'Flores', 1, 3365252, '', 'm', '2011-03-13'),
(46, 'Canela Isabel', 'Garcia', 'Balderrama', 1, 7845458, '', 'm', '2010-01-04'),
(47, 'Gael Iker', 'Garcia ', 'Fernandez', 1, 2352623, '', 'v', '2010-02-10'),
(48, 'Adrian Alberto', 'Guzman', 'Miranda', 1, 8520363, '', 'v', '2010-02-09'),
(49, 'Lucas', 'Herbas', 'Loureiro', 1, 4520010, '1tg', 'v', '2010-02-10'),
(50, 'Grecia Cristal', 'Labardenz', 'Arias', 2, 2, '', 'm', '2010-02-07'),
(51, 'Oscar Eduardo', 'Lopez', 'Vargas', 1, 7401203, '', 'v', '2010-02-10'),
(52, 'Ricardo Antonio', 'Marquez', 'Uriona', 1, 3202630, '', 'v', '2010-02-07'),
(53, 'Jose Andrez', 'Martinez', 'Alarcon', 1, 5225556, '', 'v', '2011-03-13'),
(54, 'Adriana Micaela', 'Mendoza', 'Gutierrez', 1, 1245122, '', 'm', '2010-02-12'),
(55, 'Gabriel', 'Peñaranda ', 'Diez', 1, 3698526, '', 'v', '2011-03-13'),
(56, 'Dharma Alejandra', 'Quiroga', 'Torrez', 1, 5263125, '', 'M', '2010-01-04'),
(57, 'Sebastian Andres', 'Ramos', 'Navarro', 1, 1244125, '', 'v', '2010-02-10'),
(58, 'maria', 'mamani', 'quispe', 1, 6852553, '', 'M', '1990-02-12'),
(65, 'alfredoa', 'alanocaa', 'huancaa', 1, 7485745, '', 'v', '2019-04-04'),
(66, 'miguel', 'zeballos', 'lopez', 2, 69774151, '', 'v', '1998-02-13'),
(70, 'Dante', 'Zeballos', 'Gutierrez', 1, 7845498, 'BO', 'v', '2019-05-06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usu_telefonos`
--

CREATE TABLE IF NOT EXISTS `usu_telefonos` (
  `id_telefono` int(11) NOT NULL AUTO_INCREMENT,
  `numero` varchar(20) DEFAULT NULL,
  `descripcion` varchar(100) NOT NULL,
  `persona_id` int(11) NOT NULL,
  PRIMARY KEY (`id_telefono`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `usu_telefonos`
--

INSERT INTO `usu_telefonos` (`id_telefono`, `numero`, `descripcion`, `persona_id`) VALUES
(1, '74185741', '', 1),
(2, '78512456', '', 2),
(3, '65897458', '', 3),
(4, '78412541', '', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usu_usuario`
--

CREATE TABLE IF NOT EXISTS `usu_usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_usuario` varchar(40) DEFAULT NULL,
  `contrasena` varchar(45) DEFAULT NULL,
  `rol_id` int(11) DEFAULT NULL,
  `persona_id` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Volcado de datos para la tabla `usu_usuario`
--

INSERT INTO `usu_usuario` (`id_usuario`, `nombre_usuario`, `contrasena`, `rol_id`, `persona_id`) VALUES
(1, 'juancito.pinto', '7c4a8d09ca3762af61e59520943dc26494f8941b', 5, 1),
(2, 'juan.pinto', 'ba3da472cb1a59f523b87f74c4e42c860c2aa5d0', 4, 2),
(3, 'martha', 'ba3da472cb1a59f523b87f74c4e42c860c2aa5d0', 6, 3),
(4, 'luicito.medoza', '7c4a8d09ca3762af61e59520943dc26494f8941b', 5, 4),
(5, 'luis.mendoza', 'ba3da472cb1a59f523b87f74c4e42c860c2aa5d0', 4, 5),
(6, 'fabio.choque', '7c4a8d09ca3762af61e59520943dc26494f8941b', 5, 6),
(7, 'fabiod.choque', 'ba3da472cb1a59f523b87f74c4e42c860c2aa5d0', 4, 7),
(8, 'victorcito.quispe', '7c4a8d09ca3762af61e59520943dc26494f8941b', 5, 8),
(9, 'victor.quispe', 'ba3da472cb1a59f523b87f74c4e42c860c2aa5d0', 4, 9),
(10, 'luis', 'ba3da472cb1a59f523b87f74c4e42c860c2aa5d0', 6, 14),
(11, 'maria', 'ba3da472cb1a59f523b87f74c4e42c860c2aa5d0', 7, 58);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_actividad_materia_modo_area`
--
CREATE TABLE IF NOT EXISTS `vista_actividad_materia_modo_area` (
`id_actividad_materia_modo_area` int(11)
,`nombre_actividad` varchar(100)
,`descripcion_actividad` varchar(150)
,`fecha_presentacion` date
,`confirmado` char(2)
,`estado` char(2)
,`aula_paralelo_profesor_materia_id` int(11)
,`aula_paralelo_id` int(11)
,`nombre_aula` varchar(10)
,`nombre_paralelo` varchar(50)
,`profesor_materia_id` int(11)
,`nombre_materia` varchar(300)
,`id_modo_calificacion_area_calificacion` int(11)
,`modo_calificacion_id` int(11)
,`descripcion_modo` varchar(100)
,`area_calificacion_id` int(11)
,`descripcion_area` varchar(100)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_archivo`
--
CREATE TABLE IF NOT EXISTS `vista_archivo` (
`id_archivo` int(11)
,`inscripcion_id` varchar(50)
,`aula_paralelo_id` int(11)
,`nombre_aula_paralelo` varchar(61)
,`tutor_id` int(11)
,`nombre_familiar` varchar(102)
,`estudiante_id` int(11)
,`nombre_estudiante` varchar(102)
,`gestion_id` int(11)
,`gestion` int(11)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_area_calificacion`
--
CREATE TABLE IF NOT EXISTS `vista_area_calificacion` (
`id_area_calificacion` int(11)
,`descripcion` varchar(100)
,`ponderado` int(11)
,`id_gestion` int(11)
,`gestion` int(11)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_aula_paralelo`
--
CREATE TABLE IF NOT EXISTS `vista_aula_paralelo` (
`id_aula_paralelo` int(11)
,`id_aula` int(11)
,`nombre_aula` varchar(10)
,`id_paralelo` int(11)
,`nombre_paralelo` varchar(50)
,`descripcion` varchar(100)
,`capacidad` int(11)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_aula_paralelo_profesor_materia`
--
CREATE TABLE IF NOT EXISTS `vista_aula_paralelo_profesor_materia` (
`id_aula_paralelo_profesor_materia` int(11)
,`id_aula_paralelo` int(11)
,`id_profesor_materia` int(11)
,`id_profesor` int(11)
,`nombre_aula` varchar(10)
,`nombre_paralelo` varchar(50)
,`nombre_materia` varchar(300)
,`nombre_profesor` varchar(102)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_calificaciones_modo_area`
--
CREATE TABLE IF NOT EXISTS `vista_calificaciones_modo_area` (
`id_actividad_materia_modo_area` int(11)
,`nombre_actividad` varchar(100)
,`descripcion_actividad` varchar(150)
,`nota` int(11)
,`confirmado` char(2)
,`estado` char(2)
,`id_aula_paralelo_profesor_materia` int(11)
,`id_aula_paralelo` int(11)
,`id_profesor_materia` int(11)
,`id_profesor` int(11)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_calificacion_area`
--
CREATE TABLE IF NOT EXISTS `vista_calificacion_area` (
`id_modo_calificacion_area_calificacion` int(11)
,`id_modo_calificacion` int(11)
,`descripcion_modo_calificacion` varchar(100)
,`id_area_calificacion` int(11)
,`descripcion_area_calificacion` varchar(100)
,`ponderado` int(11)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_detalle_calificacion_area`
--
CREATE TABLE IF NOT EXISTS `vista_detalle_calificacion_area` (
`id_nota_area_calificacion` int(11)
,`modo_calificacion_area_calificacion_id` int(11)
,`id_estudiante` int(11)
,`nombre_estudiante` varchar(102)
,`id_profesor_materia` int(11)
,`id_modo_calificacion_area_calificacion` int(11)
,`ponderado` int(11)
,`id_detalle_nota_area_calificacion` int(11)
,`nota` int(11)
,`estado` char(2)
,`fecha_registro` date
,`fecha_modificacion` date
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_dias_feriados`
--
CREATE TABLE IF NOT EXISTS `vista_dias_feriados` (
`id_dias_feriados` int(11)
,`fecha_inicio` date
,`fecha_final` date
,`descripcion` varchar(100)
,`gestion_id` int(11)
,`gestion` int(11)
,`estado` varchar(5)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_estudiantes`
--
CREATE TABLE IF NOT EXISTS `vista_estudiantes` (
`id_estudiante` int(11)
,`codigo_estudiante` varchar(45)
,`nombre_estudiante` varchar(102)
,`numero_documento` varchar(22)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_estudiante_actividad_nota`
--
CREATE TABLE IF NOT EXISTS `vista_estudiante_actividad_nota` (
`id_estudiante_actividad_nota` int(11)
,`nota` int(11)
,`estudiante_id` int(11)
,`nombre_estudiante` varchar(102)
,`id_actividad_materia_modo_area` int(11)
,`nombre_actividad` varchar(100)
,`fecha_presentacion` date
,`aula_paralelo_profesor_materia_id` int(11)
,`aula_paralelo_id` int(11)
,`nombre_aula` varchar(10)
,`profesor_materia_id` int(11)
,`id_modo_calificacion_area_calificacion` int(11)
,`modo_calificacion_id` int(11)
,`area_calificacion_id` int(11)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_estudiante_aula`
--
CREATE TABLE IF NOT EXISTS `vista_estudiante_aula` (
`id_aula_paralelo` int(11)
,`id_estudiante` int(11)
,`nombre_estudiante` varchar(102)
,`nombre_aula` varchar(10)
,`nombre_paralelo` varchar(50)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_estudiante_familiar`
--
CREATE TABLE IF NOT EXISTS `vista_estudiante_familiar` (
`id_estudiante_familiar` int(11)
,`id_familiar` int(11)
,`nombre_familiar` varchar(102)
,`numero_documento` int(11)
,`profesion` varchar(100)
,`direccion_oficina` varchar(45)
,`telefono_oficina` varchar(45)
,`id_estudiante` int(11)
,`tutor` tinyint(1)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_familiar`
--
CREATE TABLE IF NOT EXISTS `vista_familiar` (
`id_familiar` int(11)
,`nombre_familiar` varchar(102)
,`numero_documento` int(11)
,`profesion` varchar(100)
,`direccion_oficina` varchar(45)
,`telefono_oficina` varchar(45)
,`persona_id` int(11)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_historial_asistencia`
--
CREATE TABLE IF NOT EXISTS `vista_historial_asistencia` (
`id_asistencia_estudiante_materia` int(11)
,`id_estudiante` int(11)
,`nombre_estudiante` varchar(102)
,`id_profesor_materia` int(11)
,`nombre_materia` varchar(300)
,`json_asistencia` text
,`id_profesor` int(11)
,`nombre_profesor` varchar(102)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_inscripciones`
--
CREATE TABLE IF NOT EXISTS `vista_inscripciones` (
`id_inscripcion` int(11)
,`aula_paralelo_id` int(11)
,`nombre_aula` varchar(10)
,`nombre_paralelo` varchar(50)
,`tutor_id` int(11)
,`nombre_familiar` varchar(102)
,`estudiante_id` int(11)
,`nombre_estudiante` varchar(102)
,`tipo_estudiante_id` int(11)
,`nombre_tipo_estudiante` varchar(50)
,`nivel_academico_id` int(11)
,`nombre_nivel` varchar(50)
,`gestion_id` int(11)
,`gestion` int(11)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_lista_estudiantes`
--
CREATE TABLE IF NOT EXISTS `vista_lista_estudiantes` (
`id_estudiante` int(11)
,`nombre_estudiante` varchar(102)
,`numero_documento` varchar(22)
,`nombre_nivel` varchar(50)
,`nombre_tipo_estudiante` varchar(50)
,`aula_paralelo_id` int(11)
,`nombre_aula_paralelo` varchar(61)
,`gestion_id` int(11)
,`gestion` int(11)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_modo_calificacion`
--
CREATE TABLE IF NOT EXISTS `vista_modo_calificacion` (
`id_modo_calificacion` int(11)
,`descripcion` varchar(100)
,`fecha_inicio` date
,`fecha_final` date
,`id_gestion` int(11)
,`gestion` int(11)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_modo_calificaion_area_calificacion`
--
CREATE TABLE IF NOT EXISTS `vista_modo_calificaion_area_calificacion` (
`id_modo_calificacion_area_calificacion` int(11)
,`id_modo_calificacion` int(11)
,`descripcion_modo_calificacion` varchar(100)
,`id_area_calificacion` int(11)
,`descripcion_area_calificacion` varchar(100)
,`ponderado` int(11)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_nivel_academico`
--
CREATE TABLE IF NOT EXISTS `vista_nivel_academico` (
`id_nivel_academico` int(11)
,`nombre_nivel` varchar(50)
,`descripcion` varchar(100)
,`fecha_registro` datetime
,`gestion_id` int(11)
,`gestion` int(11)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_pensiones`
--
CREATE TABLE IF NOT EXISTS `vista_pensiones` (
`id_pensiones` int(11)
,`nivel_academico_id` int(11)
,`nombre_nivel` varchar(50)
,`tipo_estudiante_id` int(11)
,`nombre_tipo_estudiante` varchar(50)
,`nombre_pension` varchar(50)
,`descripcion` varchar(100)
,`monto` decimal(10,2)
,`mora_dia` decimal(10,2)
,`fecha_inicio` date
,`fecha_final` date
,`gestion_id` int(11)
,`gestion` int(11)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_pensiones_estudiante`
--
CREATE TABLE IF NOT EXISTS `vista_pensiones_estudiante` (
`id_pensiones_estudiante` int(11)
,`pension_id` int(11)
,`inscripcion_id` int(11)
,`estudiante_id` int(11)
,`cancelado` char(5)
,`fecha_cancelado` date
,`gestion_id` int(11)
,`gestion` int(11)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_pensiones_total`
--
CREATE TABLE IF NOT EXISTS `vista_pensiones_total` (
`id_pensiones_estudiante_detalle` int(11)
,`pensiones_estudiante_id` int(11)
,`nombre_pension` varchar(50)
,`monto` decimal(10,2)
,`mora_dia` decimal(10,2)
,`suma_acuenta` decimal(32,2)
,`fecha_inicio` date
,`fecha_final` date
,`estudiante_id` int(11)
,`pension_id` int(11)
,`cancelado` char(5)
,`fecha_cancelado` date
,`familiar_id` int(11)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_profesores`
--
CREATE TABLE IF NOT EXISTS `vista_profesores` (
`id_profesor` int(11)
,`codigo_profesor` varchar(45)
,`id_persona` int(11)
,`nombres` varchar(40)
,`apellidos` varchar(61)
,`nombre_catalogo_detalle` varchar(50)
,`numero_documento` varchar(22)
,`fecha_nacimiento` date
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_profesor_materia`
--
CREATE TABLE IF NOT EXISTS `vista_profesor_materia` (
`id_profesor_materia` int(11)
,`id_profesor` int(11)
,`codigo_profesor` varchar(45)
,`nombre_profesor` varchar(102)
,`materia_id` int(11)
,`nombre_materia` varchar(300)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_tipo_estudiante`
--
CREATE TABLE IF NOT EXISTS `vista_tipo_estudiante` (
`id_tipo_estudiante` int(11)
,`nombre_tipo_estudiante` varchar(50)
,`descripcion` varchar(100)
,`fecha_registro` datetime
,`gestion_id` int(11)
,`gestion` int(11)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_usuario_rol`
--
CREATE TABLE IF NOT EXISTS `vista_usuario_rol` (
`id_usuario` int(11)
,`nombre_usuario` varchar(40)
,`contrasena` varchar(45)
,`id_persona` int(11)
,`nombre_completo` varchar(102)
,`rol_id` int(11)
,`nombre_catalogo_detalle` varchar(50)
);
-- --------------------------------------------------------

--
-- Estructura para la vista `vista_actividad_materia_modo_area`
--
DROP TABLE IF EXISTS `vista_actividad_materia_modo_area`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_actividad_materia_modo_area` AS select `am_ma`.`id_actividad_materia_modo_area` AS `id_actividad_materia_modo_area`,`am_ma`.`nombre_actividad` AS `nombre_actividad`,`am_ma`.`descripcion_actividad` AS `descripcion_actividad`,`am_ma`.`fecha_presentacion` AS `fecha_presentacion`,`am_ma`.`confirmado` AS `confirmado`,`am_ma`.`estado` AS `estado`,`am_ma`.`aula_paralelo_profesor_materia_id` AS `aula_paralelo_profesor_materia_id`,`aul_par`.`id_aula_paralelo` AS `aula_paralelo_id`,`aul_par`.`nombre_aula` AS `nombre_aula`,`aul_par`.`nombre_paralelo` AS `nombre_paralelo`,`pro_mat`.`id_profesor_materia` AS `profesor_materia_id`,`pro_mat`.`nombre_materia` AS `nombre_materia`,`mc_ac`.`id_modo_calificacion_area_calificacion` AS `id_modo_calificacion_area_calificacion`,`mod_cal`.`id_modo_calificacion` AS `modo_calificacion_id`,`mod_cal`.`descripcion` AS `descripcion_modo`,`are_cal`.`id_area_calificacion` AS `area_calificacion_id`,`are_cal`.`descripcion` AS `descripcion_area` from ((((((`cal_actividad_materia_modo_area` `am_ma` join `vista_modo_calificaion_area_calificacion` `mc_ac` on((`mc_ac`.`id_modo_calificacion_area_calificacion` = `am_ma`.`modo_calificacion_area_calificacion_id`))) join `cal_area_calificacion` `are_cal` on((`are_cal`.`id_area_calificacion` = `mc_ac`.`id_area_calificacion`))) join `cal_modo_calificacion` `mod_cal` on((`mod_cal`.`id_modo_calificacion` = `mc_ac`.`id_modo_calificacion`))) join `int_aula_paralelo_profesor_materia` `ap_pm` on((`ap_pm`.`id_aula_paralelo_profesor_materia` = `am_ma`.`aula_paralelo_profesor_materia_id`))) join `vista_aula_paralelo` `aul_par` on((`aul_par`.`id_aula_paralelo` = `ap_pm`.`aula_paralelo_id`))) join `vista_profesor_materia` `pro_mat` on((`pro_mat`.`id_profesor_materia` = `ap_pm`.`profesor_materia_id`))) order by `aul_par`.`id_aula_paralelo`,`pro_mat`.`id_profesor_materia`,`mod_cal`.`id_modo_calificacion`,`are_cal`.`id_area_calificacion`;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_archivo`
--
DROP TABLE IF EXISTS `vista_archivo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_archivo` AS select `arc`.`id_archivo` AS `id_archivo`,`arc`.`inscripcion_id` AS `inscripcion_id`,`ins`.`aula_paralelo_id` AS `aula_paralelo_id`,concat(`ins`.`nombre_aula`,' ',`ins`.`nombre_paralelo`) AS `nombre_aula_paralelo`,`ins`.`tutor_id` AS `tutor_id`,`ins`.`nombre_familiar` AS `nombre_familiar`,`ins`.`estudiante_id` AS `estudiante_id`,`ins`.`nombre_estudiante` AS `nombre_estudiante`,`ins`.`gestion_id` AS `gestion_id`,`ins`.`gestion` AS `gestion` from (`arc_archivo` `arc` join `vista_inscripciones` `ins`);

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_area_calificacion`
--
DROP TABLE IF EXISTS `vista_area_calificacion`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_area_calificacion` AS select `are_cal`.`id_area_calificacion` AS `id_area_calificacion`,`are_cal`.`descripcion` AS `descripcion`,`are_cal`.`ponderado` AS `ponderado`,`ges`.`id_gestion` AS `id_gestion`,`ges`.`gestion` AS `gestion` from (`cal_area_calificacion` `are_cal` join `ins_gestion` `ges` on((`ges`.`id_gestion` = `are_cal`.`gestion_id`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_aula_paralelo`
--
DROP TABLE IF EXISTS `vista_aula_paralelo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_aula_paralelo` AS select `aul_par`.`id_aula_paralelo` AS `id_aula_paralelo`,`aul`.`id_aula` AS `id_aula`,`aul`.`nombre_aula` AS `nombre_aula`,`par`.`id_paralelo` AS `id_paralelo`,`par`.`nombre_paralelo` AS `nombre_paralelo`,`aul`.`descripcion` AS `descripcion`,`aul_par`.`capacidad` AS `capacidad` from ((`ins_aula_paralelo` `aul_par` join `ins_aula` `aul` on((`aul`.`id_aula` = `aul_par`.`aula_id`))) join `ins_paralelo` `par` on((`par`.`id_paralelo` = `aul_par`.`paralelo_id`))) order by `aul`.`nombre_aula`;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_aula_paralelo_profesor_materia`
--
DROP TABLE IF EXISTS `vista_aula_paralelo_profesor_materia`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_aula_paralelo_profesor_materia` AS select `ap_pm`.`id_aula_paralelo_profesor_materia` AS `id_aula_paralelo_profesor_materia`,`aul_par`.`id_aula_paralelo` AS `id_aula_paralelo`,`pro_mat`.`id_profesor_materia` AS `id_profesor_materia`,`pro`.`id_profesor` AS `id_profesor`,`aul`.`nombre_aula` AS `nombre_aula`,`par`.`nombre_paralelo` AS `nombre_paralelo`,`mat`.`nombre_materia` AS `nombre_materia`,concat(`per`.`nombres`,' ',`per`.`primer_apellido`,' ',`per`.`segundo_apellido`) AS `nombre_profesor` from (((((((`int_aula_paralelo_profesor_materia` `ap_pm` join `ins_aula_paralelo` `aul_par` on((`aul_par`.`id_aula_paralelo` = `ap_pm`.`aula_paralelo_id`))) join `ins_aula` `aul` on((`aul`.`id_aula` = `aul_par`.`aula_id`))) join `ins_paralelo` `par` on((`par`.`id_paralelo` = `aul_par`.`paralelo_id`))) join `pro_profesor_materia` `pro_mat` on((`pro_mat`.`id_profesor_materia` = `ap_pm`.`profesor_materia_id`))) join `pro_profesor` `pro` on((`pro`.`id_profesor` = `pro_mat`.`profesor_id`))) join `usu_persona` `per` on((`per`.`id_persona` = `pro`.`persona_id`))) join `pro_materia` `mat` on((`mat`.`id_materia` = `pro_mat`.`materia_id`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_calificaciones_modo_area`
--
DROP TABLE IF EXISTS `vista_calificaciones_modo_area`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_calificaciones_modo_area` AS select `am_ma`.`id_actividad_materia_modo_area` AS `id_actividad_materia_modo_area`,`am_ma`.`nombre_actividad` AS `nombre_actividad`,`am_ma`.`descripcion_actividad` AS `descripcion_actividad`,`est_act_not`.`nota` AS `nota`,`am_ma`.`confirmado` AS `confirmado`,`am_ma`.`estado` AS `estado`,`ap_pm`.`id_aula_paralelo_profesor_materia` AS `id_aula_paralelo_profesor_materia`,`ap_pm`.`id_aula_paralelo` AS `id_aula_paralelo`,`ap_pm`.`id_profesor_materia` AS `id_profesor_materia`,`ap_pm`.`id_profesor` AS `id_profesor` from ((`cal_actividad_materia_modo_area` `am_ma` join `cal_estudiante_actividad_nota` `est_act_not` on((`est_act_not`.`actividad_materia_modo_area_id` = `am_ma`.`id_actividad_materia_modo_area`))) join `vista_aula_paralelo_profesor_materia` `ap_pm` on((`ap_pm`.`id_aula_paralelo_profesor_materia` = `am_ma`.`aula_paralelo_profesor_materia_id`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_calificacion_area`
--
DROP TABLE IF EXISTS `vista_calificacion_area`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_calificacion_area` AS select `mc_ac`.`id_modo_calificacion_area_calificacion` AS `id_modo_calificacion_area_calificacion`,`mod_cal`.`id_modo_calificacion` AS `id_modo_calificacion`,`mod_cal`.`descripcion` AS `descripcion_modo_calificacion`,`are_cal`.`id_area_calificacion` AS `id_area_calificacion`,`are_cal`.`descripcion` AS `descripcion_area_calificacion`,`are_cal`.`ponderado` AS `ponderado` from ((`cal_modo_calificacion_area_calificaion` `mc_ac` join `cal_area_calificacion` `are_cal` on((`are_cal`.`id_area_calificacion` = `mc_ac`.`area_calificacion_id`))) join `cal_modo_calificacion` `mod_cal` on((`mod_cal`.`id_modo_calificacion` = `mc_ac`.`modo_calificacion_id`))) order by `mod_cal`.`id_modo_calificacion`;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_detalle_calificacion_area`
--
DROP TABLE IF EXISTS `vista_detalle_calificacion_area`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_detalle_calificacion_area` AS select `not_are_cal`.`id_nota_area_calificacion` AS `id_nota_area_calificacion`,`not_are_cal`.`modo_calificacion_area_calificacion_id` AS `modo_calificacion_area_calificacion_id`,`his_asi`.`id_estudiante` AS `id_estudiante`,`his_asi`.`nombre_estudiante` AS `nombre_estudiante`,`his_asi`.`id_profesor_materia` AS `id_profesor_materia`,`mc_ac`.`id_modo_calificacion_area_calificacion` AS `id_modo_calificacion_area_calificacion`,`mc_ac`.`ponderado` AS `ponderado`,`dn_ac`.`id_detalle_nota_area_calificacion` AS `id_detalle_nota_area_calificacion`,`dn_ac`.`nota` AS `nota`,`dn_ac`.`estado` AS `estado`,`dn_ac`.`fecha_registro` AS `fecha_registro`,`dn_ac`.`fecha_modificacion` AS `fecha_modificacion` from ((((`int_nota_area_calificacion` `not_are_cal` join `vista_historial_asistencia` `his_asi` on((`his_asi`.`id_asistencia_estudiante_materia` = `not_are_cal`.`asistencia_estudiante_materia_id`))) join `vista_modo_calificaion_area_calificacion` `mc_ac` on((`mc_ac`.`id_modo_calificacion_area_calificacion` = `not_are_cal`.`modo_calificacion_area_calificacion_id`))) join `cal_actividad_fecha` `act_fec` on((`act_fec`.`nota_area_calificacion_id` = `not_are_cal`.`id_nota_area_calificacion`))) join `cal_detalle_nota_area_calificacion` `dn_ac` on((`dn_ac`.`actividad_fecha_id` = `act_fec`.`id_actividad_fecha`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_dias_feriados`
--
DROP TABLE IF EXISTS `vista_dias_feriados`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_dias_feriados` AS select `dia_fer`.`id_dias_feriados` AS `id_dias_feriados`,`dia_fer`.`fecha_inicio` AS `fecha_inicio`,`dia_fer`.`fecha_final` AS `fecha_final`,`dia_fer`.`descripcion` AS `descripcion`,`dia_fer`.`gestion_id` AS `gestion_id`,`ges`.`gestion` AS `gestion`,`dia_fer`.`estado` AS `estado` from (`asi_dias_feriados` `dia_fer` left join `ins_gestion` `ges` on((`ges`.`id_gestion` = `dia_fer`.`gestion_id`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_estudiantes`
--
DROP TABLE IF EXISTS `vista_estudiantes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_estudiantes` AS select `est`.`id_estudiante` AS `id_estudiante`,`est`.`codigo_estudiante` AS `codigo_estudiante`,concat(`per`.`nombres`,' ',`per`.`primer_apellido`,' ',`per`.`segundo_apellido`) AS `nombre_estudiante`,concat(`per`.`numero_documento`,' ',`per`.`complemento`) AS `numero_documento` from (`ins_estudiante` `est` join `usu_persona` `per` on((`per`.`id_persona` = `est`.`persona_id`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_estudiante_actividad_nota`
--
DROP TABLE IF EXISTS `vista_estudiante_actividad_nota`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_estudiante_actividad_nota` AS select `est_act_not`.`id_estudiante_actividad_nota` AS `id_estudiante_actividad_nota`,`est_act_not`.`nota` AS `nota`,`est_act_not`.`estudiante_id` AS `estudiante_id`,concat(`per`.`nombres`,' ',`per`.`primer_apellido`,' ',`per`.`segundo_apellido`) AS `nombre_estudiante`,`am_ma`.`id_actividad_materia_modo_area` AS `id_actividad_materia_modo_area`,`am_ma`.`nombre_actividad` AS `nombre_actividad`,`am_ma`.`fecha_presentacion` AS `fecha_presentacion`,`am_ma`.`aula_paralelo_profesor_materia_id` AS `aula_paralelo_profesor_materia_id`,`am_ma`.`aula_paralelo_id` AS `aula_paralelo_id`,`am_ma`.`nombre_aula` AS `nombre_aula`,`am_ma`.`profesor_materia_id` AS `profesor_materia_id`,`am_ma`.`id_modo_calificacion_area_calificacion` AS `id_modo_calificacion_area_calificacion`,`am_ma`.`modo_calificacion_id` AS `modo_calificacion_id`,`am_ma`.`area_calificacion_id` AS `area_calificacion_id` from (((`cal_estudiante_actividad_nota` `est_act_not` join `vista_actividad_materia_modo_area` `am_ma` on((`am_ma`.`id_actividad_materia_modo_area` = `est_act_not`.`actividad_materia_modo_area_id`))) join `ins_estudiante` `est` on((`est`.`id_estudiante` = `est_act_not`.`estudiante_id`))) join `usu_persona` `per` on((`per`.`id_persona` = `est`.`persona_id`))) order by `am_ma`.`aula_paralelo_id`,`am_ma`.`profesor_materia_id`,`am_ma`.`modo_calificacion_id`,`am_ma`.`area_calificacion_id`;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_estudiante_aula`
--
DROP TABLE IF EXISTS `vista_estudiante_aula`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_estudiante_aula` AS select `aul_par`.`id_aula_paralelo` AS `id_aula_paralelo`,`est`.`id_estudiante` AS `id_estudiante`,concat(`per`.`nombres`,' ',`per`.`primer_apellido`,' ',`per`.`segundo_apellido`) AS `nombre_estudiante`,`aul`.`nombre_aula` AS `nombre_aula`,`par`.`nombre_paralelo` AS `nombre_paralelo` from ((((`ins_estudiante` `est` join `usu_persona` `per` on((`per`.`id_persona` = `est`.`persona_id`))) join `ins_aula_paralelo` `aul_par` on((`aul_par`.`id_aula_paralelo` = `est`.`aula_paralelo_id`))) join `ins_aula` `aul` on((`aul`.`id_aula` = `aul_par`.`aula_id`))) join `ins_paralelo` `par` on((`par`.`id_paralelo` = `aul_par`.`paralelo_id`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_estudiante_familiar`
--
DROP TABLE IF EXISTS `vista_estudiante_familiar`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_estudiante_familiar` AS select `est_fam`.`id_estudiante_familiar` AS `id_estudiante_familiar`,`fam`.`id_familiar` AS `id_familiar`,concat(`per`.`nombres`,' ',`per`.`primer_apellido`,' ',`per`.`segundo_apellido`) AS `nombre_familiar`,`per`.`numero_documento` AS `numero_documento`,`fam`.`profesion` AS `profesion`,`fam`.`direccion_oficina` AS `direccion_oficina`,`fam`.`telefono_oficina` AS `telefono_oficina`,`est`.`id_estudiante` AS `id_estudiante`,`est_fam`.`tutor` AS `tutor` from ((((`ins_estudiante_familiar` `est_fam` join `ins_estudiante` `est` on((`est`.`id_estudiante` = `est_fam`.`estudiante_id`))) join `usu_persona` `per2` on((`per2`.`id_persona` = `est`.`persona_id`))) join `ins_familiar` `fam` on((`fam`.`id_familiar` = `est_fam`.`familiar_id`))) join `usu_persona` `per` on((`per`.`id_persona` = `fam`.`persona_id`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_familiar`
--
DROP TABLE IF EXISTS `vista_familiar`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_familiar` AS select `fam`.`id_familiar` AS `id_familiar`,concat(`per`.`nombres`,' ',`per`.`primer_apellido`,' ',`per`.`segundo_apellido`) AS `nombre_familiar`,`per`.`numero_documento` AS `numero_documento`,`fam`.`profesion` AS `profesion`,`fam`.`direccion_oficina` AS `direccion_oficina`,`fam`.`telefono_oficina` AS `telefono_oficina`,`fam`.`persona_id` AS `persona_id` from (`ins_familiar` `fam` join `usu_persona` `per` on((`per`.`id_persona` = `fam`.`persona_id`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_historial_asistencia`
--
DROP TABLE IF EXISTS `vista_historial_asistencia`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_historial_asistencia` AS select `asi_est_mat`.`id_asistencia_estudiante_materia` AS `id_asistencia_estudiante_materia`,`est`.`id_estudiante` AS `id_estudiante`,concat(`per`.`nombres`,' ',`per`.`primer_apellido`,' ',`per`.`segundo_apellido`) AS `nombre_estudiante`,`pro_mat`.`id_profesor_materia` AS `id_profesor_materia`,`mat`.`nombre_materia` AS `nombre_materia`,`asi_est_mat`.`json_asistencia` AS `json_asistencia`,`pro`.`id_profesor` AS `id_profesor`,concat(`per2`.`nombres`,' ',`per2`.`primer_apellido`,' ',`per2`.`segundo_apellido`) AS `nombre_profesor` from ((((((`int_asistencia_estudiante_materia` `asi_est_mat` join `ins_estudiante` `est` on((`est`.`id_estudiante` = `asi_est_mat`.`estudiante_id`))) join `usu_persona` `per` on((`per`.`id_persona` = `est`.`persona_id`))) join `pro_profesor_materia` `pro_mat` on((`pro_mat`.`id_profesor_materia` = `asi_est_mat`.`profesor_materia_id`))) join `pro_profesor` `pro` on((`pro`.`id_profesor` = `pro_mat`.`profesor_id`))) join `usu_persona` `per2` on((`per2`.`id_persona` = `pro`.`persona_id`))) join `pro_materia` `mat` on((`mat`.`id_materia` = `pro_mat`.`materia_id`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_inscripciones`
--
DROP TABLE IF EXISTS `vista_inscripciones`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_inscripciones` AS select `ins`.`id_inscripcion` AS `id_inscripcion`,`ins`.`aula_paralelo_id` AS `aula_paralelo_id`,`aul_par`.`nombre_aula` AS `nombre_aula`,`aul_par`.`nombre_paralelo` AS `nombre_paralelo`,`ins`.`tutor_id` AS `tutor_id`,`fam`.`nombre_familiar` AS `nombre_familiar`,`ins`.`estudiante_id` AS `estudiante_id`,`est`.`nombre_estudiante` AS `nombre_estudiante`,`ins`.`tipo_estudiante_id` AS `tipo_estudiante_id`,`tip_est`.`nombre_tipo_estudiante` AS `nombre_tipo_estudiante`,`ins`.`nivel_academico_id` AS `nivel_academico_id`,`niv_aca`.`nombre_nivel` AS `nombre_nivel`,`ins`.`gestion_id` AS `gestion_id`,`ges`.`gestion` AS `gestion` from ((((((`ins_inscripcion` `ins` join `vista_aula_paralelo` `aul_par` on((`aul_par`.`id_aula_paralelo` = `ins`.`aula_paralelo_id`))) join `vista_familiar` `fam` on((`fam`.`id_familiar` = `ins`.`tutor_id`))) join `vista_estudiantes` `est` on((`est`.`id_estudiante` = `ins`.`estudiante_id`))) join `vista_tipo_estudiante` `tip_est` on((`tip_est`.`id_tipo_estudiante` = `ins`.`tipo_estudiante_id`))) join `vista_nivel_academico` `niv_aca` on((`niv_aca`.`id_nivel_academico` = `ins`.`nivel_academico_id`))) join `ins_gestion` `ges` on((`ges`.`id_gestion` = `ins`.`gestion_id`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_lista_estudiantes`
--
DROP TABLE IF EXISTS `vista_lista_estudiantes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_lista_estudiantes` AS select `est`.`id_estudiante` AS `id_estudiante`,`ins`.`nombre_estudiante` AS `nombre_estudiante`,`est`.`numero_documento` AS `numero_documento`,`ins`.`nombre_nivel` AS `nombre_nivel`,`ins`.`nombre_tipo_estudiante` AS `nombre_tipo_estudiante`,`ins`.`aula_paralelo_id` AS `aula_paralelo_id`,concat(`ins`.`nombre_aula`,' ',`ins`.`nombre_paralelo`) AS `nombre_aula_paralelo`,`ins`.`gestion_id` AS `gestion_id`,`ins`.`gestion` AS `gestion` from (`vista_estudiantes` `est` join `vista_inscripciones` `ins` on((`ins`.`estudiante_id` = `est`.`id_estudiante`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_modo_calificacion`
--
DROP TABLE IF EXISTS `vista_modo_calificacion`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_modo_calificacion` AS select `mod_cal`.`id_modo_calificacion` AS `id_modo_calificacion`,`mod_cal`.`descripcion` AS `descripcion`,`mod_cal`.`fecha_inicio` AS `fecha_inicio`,`mod_cal`.`fecha_final` AS `fecha_final`,`ges`.`id_gestion` AS `id_gestion`,`ges`.`gestion` AS `gestion` from (`cal_modo_calificacion` `mod_cal` join `ins_gestion` `ges` on((`ges`.`id_gestion` = `mod_cal`.`gestion_id`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_modo_calificaion_area_calificacion`
--
DROP TABLE IF EXISTS `vista_modo_calificaion_area_calificacion`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_modo_calificaion_area_calificacion` AS select `mc_ac`.`id_modo_calificacion_area_calificacion` AS `id_modo_calificacion_area_calificacion`,`mod_cal`.`id_modo_calificacion` AS `id_modo_calificacion`,`mod_cal`.`descripcion` AS `descripcion_modo_calificacion`,`are_cal`.`id_area_calificacion` AS `id_area_calificacion`,`are_cal`.`descripcion` AS `descripcion_area_calificacion`,`are_cal`.`ponderado` AS `ponderado` from ((`cal_modo_calificacion_area_calificaion` `mc_ac` join `cal_area_calificacion` `are_cal` on((`are_cal`.`id_area_calificacion` = `mc_ac`.`area_calificacion_id`))) join `cal_modo_calificacion` `mod_cal` on((`mod_cal`.`id_modo_calificacion` = `mc_ac`.`modo_calificacion_id`))) order by `mod_cal`.`id_modo_calificacion`;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_nivel_academico`
--
DROP TABLE IF EXISTS `vista_nivel_academico`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_nivel_academico` AS select `niv_aca`.`id_nivel_academico` AS `id_nivel_academico`,`niv_aca`.`nombre_nivel` AS `nombre_nivel`,`niv_aca`.`descripcion` AS `descripcion`,`niv_aca`.`fecha_registro` AS `fecha_registro`,`niv_aca`.`gestion_id` AS `gestion_id`,`ges`.`gestion` AS `gestion` from (`ins_nivel_academico` `niv_aca` join `ins_gestion` `ges` on((`ges`.`id_gestion` = `niv_aca`.`gestion_id`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_pensiones`
--
DROP TABLE IF EXISTS `vista_pensiones`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_pensiones` AS select `pen`.`id_pensiones` AS `id_pensiones`,`pen`.`nivel_academico_id` AS `nivel_academico_id`,`niv_aca`.`nombre_nivel` AS `nombre_nivel`,`pen`.`tipo_estudiante_id` AS `tipo_estudiante_id`,`tip_est`.`nombre_tipo_estudiante` AS `nombre_tipo_estudiante`,`pen`.`nombre_pension` AS `nombre_pension`,`pen`.`descripcion` AS `descripcion`,`pen`.`monto` AS `monto`,`pen`.`mora_dia` AS `mora_dia`,`pen`.`fecha_inicio` AS `fecha_inicio`,`pen`.`fecha_final` AS `fecha_final`,`pen`.`gestion_id` AS `gestion_id`,`ges`.`gestion` AS `gestion` from (((`pen_pensiones` `pen` join `ins_tipo_estudiante` `tip_est` on((`tip_est`.`id_tipo_estudiante` = `pen`.`tipo_estudiante_id`))) join `ins_nivel_academico` `niv_aca` on((`niv_aca`.`id_nivel_academico` = `pen`.`nivel_academico_id`))) join `ins_gestion` `ges` on((`ges`.`id_gestion` = `pen`.`gestion_id`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_pensiones_estudiante`
--
DROP TABLE IF EXISTS `vista_pensiones_estudiante`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_pensiones_estudiante` AS select `pen_est`.`id_pensiones_estudiante` AS `id_pensiones_estudiante`,`pen_est`.`pension_id` AS `pension_id`,`pen_est`.`inscripcion_id` AS `inscripcion_id`,`ins`.`estudiante_id` AS `estudiante_id`,`pen_est`.`cancelado` AS `cancelado`,`pen_est`.`fecha_cancelado` AS `fecha_cancelado`,`ins`.`gestion_id` AS `gestion_id`,`ins`.`gestion` AS `gestion` from ((`pen_pensiones_estudiante` `pen_est` join `vista_pensiones` `pen` on((`pen`.`id_pensiones` = `pen_est`.`pension_id`))) join `vista_inscripciones` `ins` on((`ins`.`id_inscripcion` = `pen_est`.`inscripcion_id`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_pensiones_total`
--
DROP TABLE IF EXISTS `vista_pensiones_total`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_pensiones_total` AS select `pen_est_det`.`id_pensiones_estudiante_detalle` AS `id_pensiones_estudiante_detalle`,`pen_est_det`.`pensiones_estudiante_id` AS `pensiones_estudiante_id`,`pen`.`nombre_pension` AS `nombre_pension`,`pen`.`monto` AS `monto`,`pen`.`mora_dia` AS `mora_dia`,sum(`pen_est_det`.`a_cuenta`) AS `suma_acuenta`,`pen`.`fecha_inicio` AS `fecha_inicio`,`pen`.`fecha_final` AS `fecha_final`,`pen_est`.`estudiante_id` AS `estudiante_id`,`pen_est`.`pension_id` AS `pension_id`,`pen_est`.`cancelado` AS `cancelado`,`pen_est`.`fecha_cancelado` AS `fecha_cancelado`,`pen_est_det`.`familiar_id` AS `familiar_id` from ((`vista_pensiones_estudiante` `pen_est` join `pen_pensiones_estudiante_detalle` `pen_est_det` on((`pen_est`.`id_pensiones_estudiante` = `pen_est_det`.`pensiones_estudiante_id`))) join `vista_pensiones` `pen` on((`pen`.`id_pensiones` = `pen_est`.`pension_id`))) group by `pen_est_det`.`pensiones_estudiante_id`;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_profesores`
--
DROP TABLE IF EXISTS `vista_profesores`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_profesores` AS select `pro`.`id_profesor` AS `id_profesor`,`pro`.`codigo_profesor` AS `codigo_profesor`,`per`.`id_persona` AS `id_persona`,`per`.`nombres` AS `nombres`,concat(`per`.`primer_apellido`,' ',`per`.`segundo_apellido`) AS `apellidos`,`cat_det`.`nombre_catalogo_detalle` AS `nombre_catalogo_detalle`,concat(`per`.`numero_documento`,' ',`per`.`complemento`) AS `numero_documento`,`per`.`fecha_nacimiento` AS `fecha_nacimiento` from (((`pro_profesor` `pro` join `usu_persona` `per` on((`per`.`id_persona` = `pro`.`persona_id`))) join `catalogo_detalle` `cat_det` on((`cat_det`.`id_catalogo_detalle` = `per`.`tipo_documento`))) join `catalogo` `cat` on((`cat`.`id_catalogo` = `cat_det`.`catalogo_id`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_profesor_materia`
--
DROP TABLE IF EXISTS `vista_profesor_materia`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_profesor_materia` AS select `pro_ma`.`id_profesor_materia` AS `id_profesor_materia`,`pro`.`id_profesor` AS `id_profesor`,`pro`.`codigo_profesor` AS `codigo_profesor`,concat(`per`.`nombres`,' ',`per`.`primer_apellido`,' ',`per`.`segundo_apellido`) AS `nombre_profesor`,`pro_ma`.`materia_id` AS `materia_id`,`mat`.`nombre_materia` AS `nombre_materia` from (((`pro_profesor_materia` `pro_ma` join `pro_profesor` `pro` on((`pro`.`id_profesor` = `pro_ma`.`profesor_id`))) join `usu_persona` `per` on((`per`.`id_persona` = `pro`.`persona_id`))) join `pro_materia` `mat` on((`mat`.`id_materia` = `pro_ma`.`materia_id`))) order by `mat`.`nombre_materia`;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_tipo_estudiante`
--
DROP TABLE IF EXISTS `vista_tipo_estudiante`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_tipo_estudiante` AS select `tip_est`.`id_tipo_estudiante` AS `id_tipo_estudiante`,`tip_est`.`nombre_tipo_estudiante` AS `nombre_tipo_estudiante`,`tip_est`.`descripcion` AS `descripcion`,`tip_est`.`fecha_registro` AS `fecha_registro`,`tip_est`.`gestion_id` AS `gestion_id`,`ges`.`gestion` AS `gestion` from (`ins_tipo_estudiante` `tip_est` join `ins_gestion` `ges` on((`ges`.`id_gestion` = `tip_est`.`gestion_id`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_usuario_rol`
--
DROP TABLE IF EXISTS `vista_usuario_rol`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_usuario_rol` AS select `usu`.`id_usuario` AS `id_usuario`,`usu`.`nombre_usuario` AS `nombre_usuario`,`usu`.`contrasena` AS `contrasena`,`per`.`id_persona` AS `id_persona`,concat(`per`.`nombres`,' ',`per`.`primer_apellido`,' ',`per`.`segundo_apellido`) AS `nombre_completo`,`usu`.`rol_id` AS `rol_id`,`cat_det`.`nombre_catalogo_detalle` AS `nombre_catalogo_detalle` from ((`usu_usuario` `usu` join `usu_persona` `per` on((`per`.`id_persona` = `usu`.`persona_id`))) join `catalogo_detalle` `cat_det` on((`cat_det`.`id_catalogo_detalle` = `usu`.`rol_id`)));

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
