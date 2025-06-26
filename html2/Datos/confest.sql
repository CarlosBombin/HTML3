-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-06-2025 a las 21:14:35
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `confest`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `orden` smallint(15) DEFAULT NULL,
  `plazas` int(200) NOT NULL,
  `lugar` varchar(45) NOT NULL,
  `fecha` date NOT NULL,
  `idEvento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`id`, `nombre`, `descripcion`, `orden`, `plazas`, `lugar`, `fecha`, `idEvento`) VALUES
(8, 'Pierce Brosnan', 'Pierce Brosnan', 1, 1000, 'Pierce Brosnan', '2025-07-11', 11),
(11, 'Decatlon', 'Diez pruebes', 1, 1000, 'Minos', '2025-07-04', 13),
(12, 'El canto de las sirenas', 'Pos el canto de las sirenas', 1, 1000, 'Pekin', '2025-07-04', 12),
(14, 'Flapendo Flapendo', 'Flapendo', 1, 200, 'Flapendo', '2025-07-11', 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `idTipoEvento` int(11) NOT NULL,
  `plazas` smallint(15) NOT NULL,
  `precio` float DEFAULT NULL,
  `lugar` varchar(45) DEFAULT NULL,
  `fInicio` date DEFAULT NULL,
  `fFinal` date DEFAULT NULL,
  `usuarios_id` int(11) NOT NULL,
  `imagen` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`id`, `nombre`, `descripcion`, `idTipoEvento`, `plazas`, `precio`, `lugar`, `fInicio`, `fFinal`, `usuarios_id`, `imagen`) VALUES
(11, 'Felipe', 'Cine', 3, 1000, 200, 'Korea', '2025-07-10', '2025-10-24', 4, 'missing.png'),
(12, 'Pedro Almodovar', 'Jose Juanjo', 1, 540, 1.5, 'Filipinas', '2025-07-04', '2025-07-19', 4, 'missing.png'),
(13, 'Evento Perseo', 'Es una carrera en pelotas', 4, 1000, 0, 'Atenas', '2025-06-27', '2025-07-12', 7, 'missing.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` int(11) NOT NULL,
  `datos` text NOT NULL,
  `fecha` datetime NOT NULL,
  `idUsuarioEnviado` int(11) NOT NULL,
  `idUsuarioRecibido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuarios_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `pagado` varchar(45) NOT NULL,
  `fecha` date DEFAULT NULL,
  `idUsuario` int(11) NOT NULL,
  `idActividad` int(11) NOT NULL,
  `idEvento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `rango` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `rango`) VALUES
(3, 'admin'),
(2, 'promoter'),
(1, 'user');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiposdeeventos`
--

CREATE TABLE `tiposdeeventos` (
  `id` int(11) NOT NULL,
  `tipo` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `tiposdeeventos`
--

INSERT INTO `tiposdeeventos` (`id`, `tipo`) VALUES
(3, 'cine'),
(1, 'concierto'),
(5, 'exposición'),
(4, 'prueba deportiva'),
(2, 'stand up');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellidos` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(100) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `direccion` varchar(45) DEFAULT NULL,
  `localidad` varchar(45) DEFAULT NULL,
  `codigoPostal` varchar(10) DEFAULT NULL,
  `nTarjeta` varchar(16) DEFAULT NULL,
  `fCaducidad` date DEFAULT NULL,
  `CCV` varchar(3) DEFAULT NULL,
  `saldo` float DEFAULT NULL,
  `idRol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `email`, `password`, `telefono`, `direccion`, `localidad`, `codigoPostal`, `nTarjeta`, `fCaducidad`, `CCV`, `saldo`, `idRol`) VALUES
(3, 'User', 'User', 'user@user.com', '$2y$10$MHnxVvJ3TypI.JREaNplOejGKZhYWIirjnqVdwhiH/fD4bEpH0W96', '944944944', 'C\\Sierra de Verguerez nº300', 'Valladolid', '47008', '1111222233334444', '2025-12-01', '333', 0, 1),
(4, 'promotor', 'promotor', 'promotor@promotor.com', '$2y$10$on5dbcowm1z4KiUnwnF36.mIph2vpJireMpXkXWXGmSvwCyxElWqS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2),
(5, 'Admin', 'Admin', 'admin@admin.com', '$2y$10$HjTo9qUbtSVsStFqFLypRuuWvTfJy3W4efJQhtelc7wbfrn1S73jm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 3),
(6, 'Carlos', 'Bombin', 'cbomdelhie@gmail.com', '$2y$10$StaPWISN4qRAZI5vrp6P0.q.uOPjM2xcJpKP7HT3qckTFzUvnMI2K', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1),
(7, 'Perseo', 'Promotor', 'perseo@perseo.com', '$2y$10$nUlfjDVuxVPq.0ixc6tgjOHwxckwDLgaSUvBbzVO8aCHszRXPCPji', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2),
(8, 'Luis Berto', 'Rodriguez', 'lb@rodriguez.com', '$2y$10$ipnKormKujRAY5shVST85eqa77MGlAiSBs.cV/1mYknTdH15PuMAC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_actividades_eventos1_idx` (`idEvento`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_eventos_TiposDeEventos1_idx` (`idTipoEvento`),
  ADD KEY `fk_eventos_usuarios1_idx` (`usuarios_id`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_notificaciones_usuarios1_idx` (`idUsuarioEnviado`),
  ADD KEY `fk_notificaciones_usuarios2_idx` (`idUsuarioRecibido`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_pedidos_usuarios1_idx` (`usuarios_id`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_reserva_usuarios1_idx` (`idUsuario`),
  ADD KEY `fk_reserva_actividades1_idx` (`idActividad`),
  ADD KEY `fk_reserva_eventos1_idx` (`idEvento`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `rango_UNIQUE` (`rango`);

--
-- Indices de la tabla `tiposdeeventos`
--
ALTER TABLE `tiposdeeventos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `tipo_UNIQUE` (`tipo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_usuario_cargo_idx` (`idRol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tiposdeeventos`
--
ALTER TABLE `tiposdeeventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD CONSTRAINT `fk_actividades_eventos1` FOREIGN KEY (`idEvento`) REFERENCES `eventos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `fk_eventos_TiposDeEventos1` FOREIGN KEY (`idTipoEvento`) REFERENCES `tiposdeeventos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_eventos_usuarios1` FOREIGN KEY (`usuarios_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `fk_notificaciones_usuarios1` FOREIGN KEY (`idUsuarioEnviado`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_notificaciones_usuarios2` FOREIGN KEY (`idUsuarioRecibido`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedidos_usuarios1` FOREIGN KEY (`usuarios_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `fk_reserva_actividades1` FOREIGN KEY (`idActividad`) REFERENCES `actividades` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_reserva_eventos1` FOREIGN KEY (`idEvento`) REFERENCES `eventos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_reserva_usuarios1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuario_cargo` FOREIGN KEY (`idRol`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
