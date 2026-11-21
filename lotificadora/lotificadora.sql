-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 21-11-2026 a las 18:36:51
-- Versión del servidor: 12.3.3-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `lotificadora`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `dpi` varchar(13) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre`, `dpi`, `telefono`) VALUES
(1, 'Maria Lopez', '1234567890101', '55555555'),
(2, 'jose maria ', '1123232443', '12345678'),
(3, 'sofia lopez', '455564666', '3456578'),
(4, 'Jose  Urrutia', '1224436678', '45650893'),
(5, 'jose david', '456758643', '12345678'),
(6, 'vinicio peña', '2233464', '12334456');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuotas`
--

CREATE TABLE `cuotas` (
  `id_cuota` int(11) NOT NULL,
  `id_financiamiento` int(11) DEFAULT NULL,
  `numero_cuota` int(11) DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `monto_cuota` decimal(10,2) DEFAULT NULL,
  `saldo_cuota` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `cuotas`
--

INSERT INTO `cuotas` (`id_cuota`, `id_financiamiento`, `numero_cuota`, `fecha_vencimiento`, `monto_cuota`, `saldo_cuota`) VALUES
(1, 1, 1, '2026-12-21', 500.00, 500.00),
(2, 1, 2, '2027-01-21', 500.00, 500.00),
(3, 1, 3, '2027-02-21', 500.00, 500.00),
(4, 1, 4, '2027-03-21', 500.00, 500.00),
(5, 2, 1, '2026-12-21', 125.00, 125.00),
(6, 2, 2, '2027-01-21', 125.00, 125.00),
(7, 2, 3, '2027-02-21', 125.00, 125.00),
(8, 2, 4, '2027-03-21', 125.00, 125.00),
(9, 2, 5, '2027-04-21', 125.00, 125.00),
(10, 2, 6, '2027-05-21', 125.00, 125.00),
(11, 2, 7, '2027-06-21', 125.00, 125.00),
(12, 2, 8, '2027-07-21', 125.00, 125.00),
(13, 2, 9, '2027-08-21', 125.00, 125.00),
(14, 2, 10, '2027-09-21', 125.00, 125.00),
(15, 2, 11, '2027-10-21', 125.00, 125.00),
(16, 2, 12, '2027-11-21', 125.00, 125.00),
(17, 3, 1, '2026-12-21', 217.39, 217.39),
(18, 3, 2, '2027-01-21', 217.39, 217.39),
(19, 3, 3, '2027-02-21', 217.39, 217.39),
(20, 3, 4, '2027-03-21', 217.39, 217.39),
(21, 3, 5, '2027-04-21', 217.39, 217.39),
(22, 3, 6, '2027-05-21', 217.39, 217.39),
(23, 3, 7, '2027-06-21', 217.39, 217.39),
(24, 3, 8, '2027-07-21', 217.39, 217.39),
(25, 3, 9, '2027-08-21', 217.39, 217.39),
(26, 3, 10, '2027-09-21', 217.39, 217.39),
(27, 3, 11, '2027-10-21', 217.39, 217.39),
(28, 3, 12, '2027-11-21', 217.39, 217.39),
(29, 3, 13, '2027-12-21', 217.39, 217.39),
(30, 3, 14, '2028-01-21', 217.39, 217.39),
(31, 3, 15, '2028-02-21', 217.39, 217.39),
(32, 3, 16, '2028-03-21', 217.39, 217.39),
(33, 3, 17, '2028-04-21', 217.39, 217.39),
(34, 3, 18, '2028-05-21', 217.39, 217.39),
(35, 3, 19, '2028-06-21', 217.39, 217.39),
(36, 3, 20, '2028-07-21', 217.39, 217.39),
(37, 3, 21, '2028-08-21', 217.39, 217.39),
(38, 3, 22, '2028-09-21', 217.39, 217.39),
(39, 3, 23, '2028-10-21', 217.42, 217.42),
(40, 4, 1, '2026-12-21', 190.00, 190.00),
(41, 4, 2, '2027-01-21', 190.00, 190.00),
(42, 4, 3, '2027-02-21', 190.00, 190.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pago`
--

CREATE TABLE `detalle_pago` (
  `id_detalle` int(11) NOT NULL,
  `id_pago` int(11) DEFAULT NULL,
  `id_cuota` int(11) DEFAULT NULL,
  `monto_aplicado` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `financiamientos`
--

CREATE TABLE `financiamientos` (
  `id_financiamiento` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_lote` int(11) DEFAULT NULL,
  `monto_financiado` decimal(10,2) DEFAULT NULL,
  `plazo_meses` int(11) DEFAULT NULL,
  `tasa_interes` decimal(5,2) DEFAULT NULL,
  `saldo_pendiente` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `financiamientos`
--

INSERT INTO `financiamientos` (`id_financiamiento`, `id_cliente`, `id_lote`, `monto_financiado`, `plazo_meses`, `tasa_interes`, `saldo_pendiente`) VALUES
(1, 1, 1, 2000.00, 4, 0.00, 0.00),
(2, 2, 3, 1500.00, 12, 0.00, 1500.00),
(3, 4, 8, 5000.00, 23, 0.00, 5000.00),
(4, 5, 7, 570.00, 3, 0.00, 570.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lotes`
--

CREATE TABLE `lotes` (
  `id_lote` int(11) NOT NULL,
  `id_proyecto` int(11) DEFAULT NULL,
  `numero_lote` varchar(20) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `lotes`
--

INSERT INTO `lotes` (`id_lote`, `id_proyecto`, `numero_lote`, `precio`) VALUES
(1, 1, 'Lote 12', 2000.00),
(2, 2, '12', 1400.00),
(3, 2, '12', 1400.00),
(4, 2, '12', 1400.00),
(5, 2, '12', 1400.00),
(6, 2, '12', 1400.00),
(7, 3, '23', 1500.00),
(8, 3, '56', 5000.00),
(9, 3, '34', 1500.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id_pago` int(11) NOT NULL,
  `id_financiamiento` int(11) DEFAULT NULL,
  `fecha_pago` timestamp NULL DEFAULT current_timestamp(),
  `tipo_pago` varchar(20) DEFAULT NULL,
  `monto_total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id_pago`, `id_financiamiento`, `fecha_pago`, `tipo_pago`, `monto_total`) VALUES
(1, 1, '2026-11-21 16:40:08', 'MESES', 500.00),
(2, 1, '2026-11-21 16:46:17', 'CAPITAL', 500.00),
(3, 1, '2026-11-21 16:46:29', 'MESES', 500.00),
(4, 1, '2026-11-21 16:46:36', 'MESES', 500.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `id_proyecto` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `ubicacion` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `proyectos`
--

INSERT INTO `proyectos` (`id_proyecto`, `nombre`, `ubicacion`) VALUES
(1, 'Las Marias', 'Zacapa'),
(2, 'casa de dos niveles', 'zacapa'),
(3, 'lotificacion la bella', 'chiquimula');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `cuotas`
--
ALTER TABLE `cuotas`
  ADD PRIMARY KEY (`id_cuota`),
  ADD KEY `id_financiamiento` (`id_financiamiento`);

--
-- Indices de la tabla `detalle_pago`
--
ALTER TABLE `detalle_pago`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_pago` (`id_pago`),
  ADD KEY `id_cuota` (`id_cuota`);

--
-- Indices de la tabla `financiamientos`
--
ALTER TABLE `financiamientos`
  ADD PRIMARY KEY (`id_financiamiento`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_lote` (`id_lote`);

--
-- Indices de la tabla `lotes`
--
ALTER TABLE `lotes`
  ADD PRIMARY KEY (`id_lote`),
  ADD KEY `id_proyecto` (`id_proyecto`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_financiamiento` (`id_financiamiento`);

--
-- Indices de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`id_proyecto`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `cuotas`
--
ALTER TABLE `cuotas`
  MODIFY `id_cuota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `detalle_pago`
--
ALTER TABLE `detalle_pago`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `financiamientos`
--
ALTER TABLE `financiamientos`
  MODIFY `id_financiamiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `lotes`
--
ALTER TABLE `lotes`
  MODIFY `id_lote` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `id_proyecto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cuotas`
--
ALTER TABLE `cuotas`
  ADD CONSTRAINT `1` FOREIGN KEY (`id_financiamiento`) REFERENCES `financiamientos` (`id_financiamiento`);

--
-- Filtros para la tabla `detalle_pago`
--
ALTER TABLE `detalle_pago`
  ADD CONSTRAINT `1` FOREIGN KEY (`id_pago`) REFERENCES `pagos` (`id_pago`),
  ADD CONSTRAINT `2` FOREIGN KEY (`id_cuota`) REFERENCES `cuotas` (`id_cuota`);

--
-- Filtros para la tabla `financiamientos`
--
ALTER TABLE `financiamientos`
  ADD CONSTRAINT `1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `2` FOREIGN KEY (`id_lote`) REFERENCES `lotes` (`id_lote`);

--
-- Filtros para la tabla `lotes`
--
ALTER TABLE `lotes`
  ADD CONSTRAINT `1` FOREIGN KEY (`id_proyecto`) REFERENCES `proyectos` (`id_proyecto`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `1` FOREIGN KEY (`id_financiamiento`) REFERENCES `financiamientos` (`id_financiamiento`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
