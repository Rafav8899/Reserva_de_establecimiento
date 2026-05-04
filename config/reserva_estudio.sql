-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-05-2026 a las 02:01:02
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `reserva_estudio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `uuid` char(36) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `dni_frente` varchar(255) NOT NULL,
  `dni_dorso` varchar(255) NOT NULL,
  `gmail` varchar(255) NOT NULL,
  `celular` varchar(30) NOT NULL,
  `domicilio` varchar(255) NOT NULL,
  `residencia` varchar(100) NOT NULL,
  `nacionalidad` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `uuid`, `nombre`, `apellido`, `dni`, `dni_frente`, `dni_dorso`, `gmail`, `celular`, `domicilio`, `residencia`, `nacionalidad`, `created_at`) VALUES
(1, '8a5711ef-189f-11f1-b6ec-9cb1a525c980', 'Juan', 'Perez', '40123456', '', '', 'juanperez@gmail.com', '3764000000', 'Calle Falsa 123', 'Villa Dolores', 'Argentina', '2026-03-05 14:28:12'),
(2, '', 'Rafael', 'Sanchez', '45898656', '', '', 'sr.rafa0219@gmail.com', '3765223344', '', '', '', '2026-03-05 23:39:31'),
(3, '', 'Marto', 'Sanchez', '44555666', '', '', 'adminprincipal@admin.com', '3764123456', '', '', '', '2026-03-06 14:57:56'),
(4, '', 'Juan', 'Perro', '77888999', '', '', 'admin@ejemplo.com', '3764123456', '', '', '', '2026-03-06 15:25:08'),
(5, '', 'MAURITO', 'Perro', '77777888', '', '', 'admin@ejemplo.com', '3765223344', '', '', '', '2026-04-13 12:41:06'),
(6, '', 'JUaN', 'Gato', '12334455', '', '', 'admin@ejemplo.com', '3765223344', '', '', '', '2026-04-13 12:47:07'),
(7, '', 'Marcos', 'Perez', '55889944', '', '', 'gmail@ejemplo.com', '3764123456', 'calle falsa 789', 'Misiones', 'Argentina', '2026-04-13 13:23:11'),
(9, '', 'MauriSio', 'GatoRezAAAAA', '44222333', '/uploads/dni/dni_44222333_frente.jpg', '/uploads/dni/dni_44222333_dorso.jpg', 'gmail@ejemplo.com', '3765889911', 'calle falsa 123', 'Misiones', 'Argentina', '2026-04-16 14:49:08'),
(11, '', 'Marcos', 'Pergolini', '44222334', '/uploads/dni/dni_44222334_frente.png', '/uploads/dni/dni_44222334_dorso.png', 'maurybionico18@gmail.com', '3765889911', 'calle falsa 789', 'Misiones', 'Paraguay', '2026-04-16 15:00:46'),
(12, '', 'Markito', 'PerranDeZ', '55888999', '/uploads/dni/dni_55888999_frente.jpg', '/uploads/dni/dni_55888999_dorso.jpg', 'gmail@ejemplo.com', '3765889911', 'calle falsa 456', 'Argentina', 'Argentina', '2026-04-22 14:10:35'),
(13, '', 'Marcos', 'Sanchez', '55888998', '/uploads/dni/dni_55888998_frente.jpg', '/uploads/dni/dni_55888998_dorso.jpg', 'gmail@ejemplo.com', '3765889911', 'calle falsa 789', 'Misiones', 'Paraguay', '2026-04-22 14:18:20'),
(14, '', 'Rafael', 'Villalba Mendez', '44072476', '/uploads/dni/dni_44072476_frente.jpg', '/uploads/dni/dni_44072476_dorso.jpg', 'maurybionico18@gmail.com', '3764889992', 'calle falsa 123', 'Misiones', 'Argentina', '2026-04-29 21:16:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `uuid` char(36) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `turno` enum('mañana','tarde','noche') NOT NULL,
  `fondo` varchar(50) NOT NULL,
  `equipos` longtext DEFAULT NULL,
  `estado` enum('confirmada','cancelada') DEFAULT 'confirmada',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `uuid`, `id_cliente`, `fecha`, `turno`, `fondo`, `equipos`, `estado`, `created_at`) VALUES
(1, '80e319f9bc18aba6ee67591d2a6357c8', 9, '2026-04-16', 'mañana', '', 'Camara Canon R6, Tripode Manfrotto', 'cancelada', '2026-04-16 14:49:08'),
(3, '5e5b31c7cf35666ebe1ee416e26f39c0', 11, '2026-04-16', 'tarde', '', 'Camara Canon R6', 'confirmada', '2026-04-16 15:00:46'),
(4, '2126b45c81d4eaf13581d8dbb49a5e03', 11, '2026-04-22', 'tarde', '', 'Camara Canon R6', 'confirmada', '2026-04-22 14:08:10'),
(5, '29f69245857cecc72bbd732c30490f9c', 12, '2026-04-30', 'mañana', '', 'Camara Canon R6', 'confirmada', '2026-04-22 14:10:35'),
(6, 'f989095c52e9573d901da2e4005fb3e8', 13, '2026-04-30', 'tarde', '', 'Camara Canon R6, Softbox 60x60, Tripode Manfrotto', 'confirmada', '2026-04-22 14:18:20'),
(7, '02028658963ae58f88efd4e9b37e12e2', 9, '2026-04-24', 'tarde', '', 'Sin equipo extra', 'cancelada', '2026-04-23 15:58:57'),
(8, 'bb430644ffaa5acc8c5541becc163ac4', 11, '2026-04-28', 'mañana', '', 'Sin equipo extra, Flash Visico V', 'confirmada', '2026-04-24 18:17:15'),
(9, 'b1b1e149a750adfde5298c9165a591b1', 12, '2026-04-29', 'mañana', '', 'Sin equipo extra', 'confirmada', '2026-04-27 14:56:13'),
(10, 'cc0a18d3b2d88a937da0205347e2d32d', 12, '2026-05-06', 'mañana', '', 'Sin equipo extra', 'confirmada', '2026-04-27 14:57:33'),
(11, '9bfaa817440e49d61cde8e3ae479378a', 11, '2026-05-13', 'tarde', '', 'Sin equipo extra', 'confirmada', '2026-04-27 20:44:02'),
(12, '26ddc721d70e861e776b8a2c740b7f55', 9, '2026-05-05', 'tarde', '', 'Sin equipo extra', 'confirmada', '2026-04-27 20:48:43'),
(13, '71f9689f852b985c255c9407f815989f', 9, '2026-05-06', 'tarde', '', 'Sin equipo extra', 'confirmada', '2026-04-27 21:54:59'),
(14, '4e49b911900feba0be486726e09a6782', 14, '2026-05-29', 'tarde', '', 'Sin equipo extra', 'confirmada', '2026-04-29 21:16:18'),
(15, '720228b6457823ccdf47bfdd815bc8d4', 14, '2026-05-29', 'mañana', '', 'Sin equipo extra', 'confirmada', '2026-04-29 21:22:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `gmail` varchar(100) NOT NULL,
  `google_id` varchar(100) DEFAULT NULL,
  `contrasenia` varchar(255) DEFAULT NULL,
  `rol` enum('admin','cliente') DEFAULT 'cliente',
  `dni` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `gmail`, `google_id`, `contrasenia`, `rol`, `dni`) VALUES
(1, 'maurybionico18@gmail.com', NULL, NULL, 'admin', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD KEY `gmail` (`gmail`),
  ADD KEY `dni_2` (`dni`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD UNIQUE KEY `unique_fecha_turno` (`fecha`,`turno`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `gmail` (`gmail`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD UNIQUE KEY `google_id` (`google_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
