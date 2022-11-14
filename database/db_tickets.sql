-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-10-2022 a las 17:06:49
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_tickets`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `airlines`
--

CREATE TABLE `airlines` (
  `id` int(11) NOT NULL,
  `country` varchar(100) NOT NULL,
  `airline` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `airlines`
--

INSERT INTO `airlines` (`id`, `country`, `airline`) VALUES
(1, 'Argentina ', 'Aerolíneas Argentinas '),
(2, 'Argentina ', 'Flybondi'),
(3, 'Mexico', 'Aeroméxico'),
(4, 'Francia', 'Air France'),
(5, 'Estados Unidos', 'American Airlines'),
(6, 'Colombia', 'Avianca'),
(7, 'Reino Unido', 'British Airways '),
(8, 'España', 'Iberia'),
(9, 'Países Bajos', 'KLM');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trips`
--

CREATE TABLE `trips` (
  `id` int(11) NOT NULL,
  `date` varchar(20) NOT NULL,
  `passengers` int(11) NOT NULL,
  `placeOfDeparture` varchar(100) NOT NULL,
  `placeOfDestination` varchar(100) NOT NULL,
  `price` float NOT NULL,
  `airline` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `trips`
--

INSERT INTO `trips` (`id`, `date`, `passengers`, `placeOfDeparture`, `placeOfDestination`, `price`, `airline`) VALUES
(1, '25/10/22', 25, 'Buenos Aires (ARG)', 'Rio de Janeiro (BRA)', 62.234, 1),
(2, '27/10/22', 10, 'Paris (FRA)', 'Buenos Aires (ARG)', 103.567, 2),
(3, '28/10/22', 6, 'Rio de Janeiro (BRA)', 'Londres (UK)', 144.523, 3),
(4, '01/11/22', 33, 'Buenos Aires (ARG)', 'Bogotá (COL) ', 56.423, 4),
(5, '02/11/22', 46, 'Ámsterdam (NL)', 'Madrid (ESP)', 95.641, 5),
(6, '02/11/22', 12, 'New York (USA)', 'Miami (USA)', 45.708, 6),
(7, '03/11/22', 15, 'Buenos Aires (ARG)', 'Doha (QAT)', 123.457, 7),
(8, '23/11/22', 26, 'Buenos Aires (ARG)', 'Santiago de Chile (CL)', 62.543, 8),
(9, '15/11/22', 5, 'Madrid (ESP)', 'Roma (ITA)', 85.423, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `email`, `password`) VALUES
(1, 'admin@admin.com', '$2y$10$qGLpRIWcLHqGhPWim2dEDec9GZFpSXfPM3am8VcS5dYu3NoGfavFq'),
(2, 'admin@admin', '$2y$10$IwSwugZEq89DhVnB.5LWZO2qW2yhZij5La3G9/NcjKX5Rt9.dvpCG');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `airlines`
--
ALTER TABLE `airlines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `airline` (`airline`);

--
-- Indices de la tabla `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `airline` (`airline`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `airlines`
--
ALTER TABLE `airlines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `trips_ibfk_1` FOREIGN KEY (`airline`) REFERENCES `airlines` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
