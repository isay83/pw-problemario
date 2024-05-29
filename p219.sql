
-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 06-12-2018 a las 08:56:29
-- Versión del servidor: 10.1.37-MariaDB-0+deb9u1
-- Versión de PHP: 7.0.30-0+deb9u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `PagoBD_PagoServ_`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Estatus`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Facturas`
--

CREATE TABLE IF NOT EXISTS `Facturas` (
`id` int(11) NOT NULL,
  `id_Cliente` char(13) COLLATE utf8_spanish_ci NOT NULL,
  `id_Servicio` int(11) NOT NULL,
  `Monto` float NOT NULL,
  `fecha_Emision` date NOT NULL,
  `fecha_Vencimiento` date NOT NULL,
  `fecha_Pago` date DEFAULT NULL,
  `id_FormaPago` int(11) DEFAULT NULL,
  `Ref_Bancaria` char(16) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
--
-- Volcado de datos para la tabla `Facturas`
--

INSERT INTO `Facturas` (`id`, `id_Cliente`, `id_Servicio`, `Monto`, `fecha_Emision`, `fecha_Vencimiento`, `fecha_Pago`, `id_FormaPago`, `Ref_Bancaria`) VALUES
(1, 'JuanGallo', 3, 136.89, '2018-11-01', '2018-11-15', '2018-11-12', 2, '5448812303426024'),
(2, 'GatoVolador', 4, 458.69, '2018-11-15', '2018-11-30', '2018-11-29', 1, ''),
(4, 'angel2020', 3, 278.69, '2018-11-09', '2018-11-17', '0000-00-00', 4, ''),
(5, 'JuanGallo', 5, 458.96, '2018-11-07', '2018-11-20', '0000-00-00', 4, ''),
(6, 'GatoVolador', 1, 742.36, '2018-11-08', '2018-11-20','2018-11-29', 2, '4716864179098724'),
(7, 'PilloVeloz', 6, 250.8, '2018-12-01', '2018-12-15', '0000-00-00', 4, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `BD_PagoServ_`
--

CREATE TABLE `BD_PagoServ_` (
  `id` int(11) NOT NULL,
  `Nombre` char(20) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `BD_PagoServ_`
--

INSERT INTO `BD_PagoServ_` (`id`, `Nombre`) VALUES
(1, 'Electricidad'),
(2, 'Gas natural'),
(3, 'Agua'),
(4, 'Predial'),
(5, 'TV Satelital'),
(6, 'Telefonía celular');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Tipo_Pago`
--

CREATE TABLE `Tipo_Pago` (
  `id` int(11) NOT NULL,
  `Nombre` char(15) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `Tipo_Pago`
--

INSERT INTO `Tipo_Pago` (`id`, `Nombre`) VALUES
(1, 'Efectivo'),
(2, 'Tarjeta'),
(3, 'Transferencia'),
(4, 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE `Usuarios` (
  `Usuario` char(13) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `Clave` char(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `Nombre` char(30) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `Apellidos` char(30) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `Usuarios`
--

INSERT INTO `Usuarios` (`Usuario`, `Clave`, `Nombre`, `Apellidos`) VALUES
('angel2020', '*FB7A787BD488276A0F6862859CDB718E0F93F76B', 'Jimena', 'Perez Velarde'),
('Anima2000', '*CF9FFF75D79F632A5B94A7DC0A7F0A8EF3FDD616', 'Alma', 'Mendoza Bueno'),
('GatoVolador', '*85638086DBE6BD2217A35219EB26C47F8B8769FA', 'Maria Angeles', 'Bedolla'),
('JuanGallo', '*38DCDE3D087B2824EA7F79756D718F09A57586B4', 'Juan', 'Figueroa'),
('PilloVeloz', '*7A60B41BB630AAC97D457A7FF84A620038820A05', 'Guadalupe', 'Jimenez Z.'),
('SiempreSucio', '*490D4CD943E87A5D3F69B959C7A45C41228D4BCC', 'Miguel', 'Duarte K.');

--
-- Índices para tablas volcadas
--
--
-- Indices de la tabla `Facturas`
--
ALTER TABLE `Facturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_Servicio` (`id_Servicio`),
  ADD KEY `id_FormaPago` (`id_FormaPago`),
  ADD KEY `id_Cliente` (`id_Cliente`);

--
-- Indices de la tabla `BD_PagoServ_`
--
ALTER TABLE `BD_PagoServ_`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Tipo_Pago`
--
ALTER TABLE `Tipo_Pago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`Usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--


-- AUTO_INCREMENT de la tabla `Facturas`
--
ALTER TABLE `Facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `BD_PagoServ_`
--
ALTER TABLE `BD_PagoServ_`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `Tipo_Pago`
--
ALTER TABLE `Tipo_Pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Facturas`
--
ALTER TABLE `Facturas`
  ADD CONSTRAINT `Facturas_ibfk_1` FOREIGN KEY (`id_Servicio`) REFERENCES `BD_PagoServ_` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Facturas_ibfk_2` FOREIGN KEY (`id_FormaPago`) REFERENCES `Tipo_Pago` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Facturas_ibfk_3` FOREIGN KEY (`id_Cliente`) REFERENCES `Usuarios` (`Usuario`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
