-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-12-2019 a las 16:35:43
-- Versión del servidor: 10.4.6-MariaDB
-- Versión de PHP: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `domino`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estatusjuego`
--

CREATE TABLE `estatusjuego` (
  `id` int(11) NOT NULL,
  `estado` char(12) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `estatusjuego`
--

INSERT INTO `estatusjuego` (`id`, `estado`) VALUES
(1, 'Finalizado'),
(2, 'No comenzado'),
(3, 'En Proceso');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juegos`
--

CREATE TABLE `juegos` (
  `id` int(11) NOT NULL,
  `id_usuario` char(13) COLLATE utf8_spanish2_ci NOT NULL,
  `id_invitado` char(13) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `ganador` char(13) COLLATE utf8_spanish2_ci NOT NULL,
  `id_estatus` int(11) NOT NULL,
  `puntos` int(11) DEFAULT NULL,
  `secuencia` char(100) COLLATE utf8_spanish2_ci NOT NULL,
  `fichas1` char(25) COLLATE utf8_spanish2_ci NOT NULL,
  `fichas2` char(25) COLLATE utf8_spanish2_ci NOT NULL,
  `comienza` char(13) COLLATE utf8_spanish2_ci NOT NULL,
  `turno` char(13) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `juegos`
--

INSERT INTO `juegos` (`id`, `id_usuario`, `id_invitado`, `fecha`, `ganador`, `id_estatus`, `puntos`, `secuencia`, `fichas1`, `fichas2`, `comienza`, `turno`) VALUES
(1, 'Anima2000', 'JuanGallo', '2018-12-03 06:36:32', 'JuanGallo', 1, 4, '0:2 2:6 6:6 6:5 5:5 5:2 2:1 1:1 1:5 5:3 3:6 6:4 4:2 2:3', '2:2', '', 'JuanGallo', ''),
(2, 'Anima2000', 'JuanGallo', '2018-12-03 12:23:43', 'JuanGallo', 1, 8, '4:5 5:6 6:2 2:2 2:5 5:5 5:3 3:6 6:6 6:1 1:0 0:0 0:6', '3:5', '', 'Anima2000', ''),
(3, 'PilloVeloz', 'Anima2000', '0000-00-00 00:00:00', '', 2, 0, '', '', '', '', ''),
(4, 'PilloVeloz', 'JuanGallo', '2018-12-03 15:27:43', '', 3, 0, '4:5 5:6 6:2 2:2 2:5 5:5 5:1 1:2 2:0 0:5 5:3 3:6 6:4 4:2 2:3', '3:3', '0:0 1:0', 'PilloVeloz', 'JuanGallo'),
(5, 'angel2020', 'SiempreSucio', '2018-12-02 06:36:32', 'angel2020', 1, 7, '0:2 2:6 6:6 6:5 5:5 5:2 2:1 1:1 1:5 5:3 3:6 6:4 4:2 2:3', '', '3:4', 'angel2020', ''),
(6, 'GatoVolador', 'angel2020', '2018-12-01 08:38:43', 'angel2020', 1, 8, '1:5 5:6 6:3 3:3 3:5 5:5 5:2 2:6 6:6 6:1 1:0 0:0 0:6', '3:5', '', 'GatoVolador', ''),
(7, 'angel2020', 'PilloVeloz', '0000-00-00 00:00:00', '', 2, 0, '', '', '', '', ''),
(8, 'JuanGallo', 'Anima2000', '2018-12-03 15:27:43', '', 3, 0, '4:5 5:6 6:6 6:2 2:2 2:5 5:5 5:1 1:1 1:2 2:0 0:5 5:3 3:3 3:5 6:4 4:2 2:3', '3:0', '0:6 1:0 1:4', 'JuanGallo', 'JuanGallo'),
(9, 'SiempreSucio', 'Fede2019', '2019-12-02 06:36:32', 'Fede2019', 1, 8, '0:0 0:4 4:4 4:1 1:0 0:2 2:6 6:6 6:5 5:5 5:2 2:1 1:1 1:5 5:3 3:6 6:4 4:2 2:3', '1:3 2:2', '', 'SiempreSucio', ''),
(10, 'GatoVolador', 'Fede2019', '0000-00-00 00:00:00', '', 2, 0, '', '', '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `Usuario` char(13) COLLATE utf8_spanish2_ci NOT NULL,
  `Clave` char(45) COLLATE utf8_spanish2_ci NOT NULL,
  `Nombre` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `Apellidos` char(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`Usuario`, `Clave`, `Nombre`, `Apellidos`) VALUES
('angel2020', '*FB7A787BD488276A0F6862859CDB718E0F93F76B', 'Jimena', 'Perez Velarde'),
('Anima2000', '*CF9FFF75D79F632A5B94A7DC0A7F0A8EF3FDD616', 'Alma', 'Mendoza Bueno'),
('Fede2019', '*DBE8783D2DF67544FD2677CADBC658F65F5276B7', 'Federico', 'García Lorca'),
('GatoVolador', '*85638086DBE6BD2217A35219EB26C47F8B8769FA', 'Maria Angeles', 'Bedolla'),
('JuanGallo', '*38DCDE3D087B2824EA7F79756D718F09A57586B4', 'Juan', 'Figueroa'),
('PilloVeloz', '*7A60B41BB630AAC97D457A7FF84A620038820A05', 'Guadalupe', 'Jimenez Z.'),
('SiempreSucio', '*490D4CD943E87A5D3F69B959C7A45C41228D4BCC', 'Miguel', 'Duarte K.');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estatusjuego`
--
ALTER TABLE `estatusjuego`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `juegos`
--
ALTER TABLE `juegos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_invitado` (`id_invitado`),
  ADD KEY `estatus` (`id_estatus`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`Usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `estatusjuego`
--
ALTER TABLE `estatusjuego`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `juegos`
--
ALTER TABLE `juegos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `juegos`
--
ALTER TABLE `juegos`
  ADD CONSTRAINT `dueño` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`Usuario`) ON UPDATE CASCADE,
  ADD CONSTRAINT `estatus` FOREIGN KEY (`id_estatus`) REFERENCES `estatusjuego` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `invitado` FOREIGN KEY (`id_invitado`) REFERENCES `usuarios` (`Usuario`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
