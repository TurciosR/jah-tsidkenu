-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-07-2022 a las 00:48:26
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
-- Estructura de tabla para la tabla `trabajos_taller_detalle`
--

CREATE TABLE `trabajos_taller_detalle` (
  `id_detalle` int(11) NOT NULL,
  `id_trabajo_taller` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_color` int(11) NOT NULL,
  `costo` decimal(10,4) NOT NULL,
  `precio` decimal(10,4) NOT NULL,
  `descuento` decimal(10,4) NOT NULL,
  `precio_fin` decimal(10,4) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `subtotal` decimal(10,4) NOT NULL,
  `condicion` varchar(50) NOT NULL,
  `garantia` int(11) NOT NULL,
  `id_stock` int(11) NOT NULL,
  `tipo_prod` int(1) NOT NULL COMMENT 'valor:0- producto; valor:1-servicio',
  `id_precio_producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `trabajos_taller_detalle`
--
ALTER TABLE `trabajos_taller_detalle`
  ADD PRIMARY KEY (`id_detalle`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `trabajos_taller_detalle`
--
ALTER TABLE `trabajos_taller_detalle`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
