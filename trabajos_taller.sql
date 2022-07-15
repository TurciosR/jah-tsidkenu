-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-07-2022 a las 00:47:55
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `jah2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajos_taller`
--

CREATE TABLE `trabajos_taller` (
  `id_trabajo_taller` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `concepto` text NOT NULL,
  `indicaciones` text NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `envio` varchar(50) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `id_sucursal_despacho` int(11) NOT NULL,
  `correlativo` varchar(25) DEFAULT NULL,
  `total` decimal(10,4) NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `requiere_imei` int(11) NOT NULL,
  `imei_ingresado` int(11) NOT NULL,
  `guia` varchar(50) NOT NULL,
  `tipo_doc` tinyint(2) NOT NULL,
  `carrier` int(11) NOT NULL,
  `serie` text NOT NULL,
  `numero_impreso` text NOT NULL,
  `referencia` varchar(10) NOT NULL,
  `id_apertura` int(11) NOT NULL,
  `caja` int(11) NOT NULL,
  `total_iva` decimal(10,4) NOT NULL,
  `credito` tinyint(4) NOT NULL,
  `retencion` decimal(10,4) NOT NULL,
  `id_devolucion` int(11) NOT NULL,
  `tipo_pago` tinyint(1) NOT NULL DEFAULT 1,
  `voucher_pago` varchar(15) NOT NULL,
  `dias_credito` int(11) NOT NULL,
  `hora_fin` time NOT NULL,
  `id_venta` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `trabajos_taller`
--
ALTER TABLE `trabajos_taller`
  ADD PRIMARY KEY (`id_trabajo_taller`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `trabajos_taller`
--
ALTER TABLE `trabajos_taller`
  MODIFY `id_trabajo_taller` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
