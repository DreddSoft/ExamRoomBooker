-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-03-2025 a las 20:51:37
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
-- Base de datos: `examroombooker`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaturas`
--

CREATE TABLE `asignaturas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignaturas`
--

INSERT INTO `asignaturas` (`id`, `nombre`) VALUES
(1, 'Biología'),
(2, 'Química'),
(3, 'Matemáticas I'),
(4, 'Álgebra'),
(5, 'Lengua Española'),
(6, 'Literatura Universal'),
(7, 'Bases De Datos'),
(8, 'Entorno Servidor'),
(9, 'Entorno Cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaturasprofesores`
--

CREATE TABLE `asignaturasprofesores` (
  `idProfesor` int(11) NOT NULL,
  `idAsignatura` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignaturasprofesores`
--

INSERT INTO `asignaturasprofesores` (`idProfesor`, `idAsignatura`) VALUES
(6, 5),
(6, 6),
(7, 1),
(7, 3),
(8, 7),
(8, 8),
(10, 7),
(10, 8),
(10, 9),
(11, 1),
(11, 3),
(12, 2),
(12, 4),
(13, 5),
(13, 6),
(14, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `passw` varchar(255) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `ape1` varchar(100) NOT NULL,
  `ape2` varchar(100) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `email` varchar(200) NOT NULL,
  `admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`id`, `usuario`, `passw`, `nombre`, `ape1`, `ape2`, `activo`, `email`, `admin`) VALUES
(6, 'SERGIO', '1234', 'Sergio', 'Hernández', 'Ortega', 1, 'sergio.hernandez@example.com', 0),
(7, 'BEATRIZ', '1234', 'Beatriz', 'Díaz', 'Morales', 1, 'beatriz.diaz@example.com', 0),
(8, 'nsaltor', '$2y$10$R0KDdhmxa8r1oRf49c1jpeyl1.7XXHBUSnHD/WemYeI7Yv2d8Cbh2', 'Noemi', 'Salobreña', 'Torres', 1, 'nsaltor759@g.educaand.es', 1),
(10, 'abontar', '$2y$10$EocWyLzS9TumADlnOqUu6./RRkAsPpMH5CBWrU4mknDzeXyvKoHWC', 'Andres', 'Bonilla', 'Tardio', 1, 'abontar033@g.educaand.es', 1),
(11, 'dvilvil', '$2y$10$UrR875.VdcT.Kx6qVyFjVudMuUcORJNAh1tM9LNl3Py7UzDIqyfYq', 'David', 'Villena', 'Villena', 1, 'dvilvil388@g.educaand.es', 1),
(12, 'ajimvil', '$2y$10$ziLkxjgK5t1YUwsDNqVur.TMorw0biNChdXLPScBcWkqD4ymFz05u', 'Adrian', 'Jimenez', 'Villena', 1, 'ajimvil713@g.educaand.es', 1),
(13, 'ilopjim', '$2y$10$26vbR1Mz5FLXqnyQrxkk5.4nnvwDRBRtKMVePT2.7c1LbcfSYgkMq', 'Ivan', 'Lopez', 'Jimenez', 1, 'ilopjim3107@g.educaand.es', 1),
(14, 'fgutram', '$2y$10$6rtHOVFdRd9LIGlbnVCUfeoZxZZKa7hG7OPezKG9W/RTgz47bXZJm', 'Francisco Daniel', 'Gutierrez', 'Ramos', 1, 'fgutram@g.educaand.es', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `idProfesor` int(11) NOT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `numAlumnos` int(11) DEFAULT NULL,
  `clase` varchar(50) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `idAsignatura` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `idProfesor`, `descripcion`, `numAlumnos`, `clase`, `fecha`, `idAsignatura`) VALUES
(6, 6, 'Estudio de literatura universal: Shakespeare', 18, 'Aula 105', '2025-02-19', 6),
(7, 7, 'Creación de bases de datos relacionales', 26, 'Aula Informática 1', '2025-02-20', 7),
(8, 8, 'Configuración de un servidor web', 24, 'Aula Informática 2', '2025-02-20', 8),
(10, 10, 'Práctica avanzada de bases de datos', 27, 'Aula Informática 1', '2025-02-21', 7),
(11, 11, 'Implementación de APIs en entorno servidor', 21, 'Aula Informática 2', '2025-02-22', 8),
(12, 12, 'Diseño de interfaces en entorno cliente', 29, 'Aula Informática 3', '2025-02-22', 9),
(20, 6, 'Seminario de Álgebra', 22, 'Aula 105', '2025-03-06', 4),
(21, 7, 'Práctica de Base de Datos', 12, 'Sala de Computo', '2025-03-07', 7),
(22, 8, 'Clase especial de Lengua', 25, 'Aula 106', '2025-03-08', 5),
(25, 11, 'Práctica Entorno Servidor', 20, 'Sala de Computo', '2025-03-11', 8),
(26, 12, 'Taller de Literatura', 15, 'Aula 109', '2025-03-12', 6),
(27, 13, 'Presentación de proyectos', 18, 'Aula 110', '2025-03-13', 9),
(28, 14, 'Simulacro de examen', 25, 'Aula 111', '2025-03-14', 3),
(34, 6, 'Clase de repaso', 22, 'Aula 116', '2025-03-20', 6),
(35, 7, 'Evaluación de proyectos', 12, 'Sala de Computo', '2025-03-21', 9),
(36, 8, 'Conferencia especial', 25, 'Aula Magna', '2025-03-22', 1),
(39, 11, 'Simulación de prueba', 20, 'Aula 119', '2025-03-25', 7),
(40, 12, 'Proyecto final', 15, 'Aula 120', '2025-03-26', 9),
(41, 13, 'Trabajo en grupo', 18, 'Aula 121', '2025-03-27', 2),
(42, 14, 'Clase práctica', 25, 'Aula 122', '2025-03-28', 4),
(45, 10, 'Examen de Base de Datos', 33, 'Informatica 1', '2025-02-27', 1),
(46, 10, 'examen de Entorno Servidor', 44, 'Informatica 2', '2025-02-27', 1),
(47, 10, 'examen de Entorno Servidor', 44, 'Informatica 2', '2025-02-27', 1),
(48, 10, 'examen de Entorno Servidor', 44, 'Informatica 2', '2025-02-27', 1),
(49, 10, 'Ex. Entorno Cliente', 33, 'Informatica 2', '2025-02-27', 1),
(50, 10, 'Examen de JavaScript', 33, 'Informatica 3', '2025-02-27', 1),
(51, 10, 'Examen de Tablas', 30, 'Aula 3', '2025-02-27', 1),
(52, 10, 'Examen de MER', 22, 'Aula 4', '2025-02-28', 1),
(53, 10, 'Refuerzo', 21, 'Refuerzo II', '2025-02-28', 7),
(54, 10, 'Clase para Javieres', 33, 'Retraso II', '2025-02-28', 7),
(60, 10, 'Prueba 5', 22, 'Prueba 5', '2025-03-05', 7),
(63, 10, 'Prueba 8 - Cambiada', 22, 'Prueba 8', '2025-03-07', 9),
(64, 10, 'Prueba 9', 22, 'Prueba 9', '2025-03-07', 7),
(65, 10, 'Examen de MERE', 33, 'DAW 1º', '2025-03-10', 7),
(68, 10, 'Examen MERE', 20, 'Informatica 1', '2025-03-04', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservasturnos`
--

CREATE TABLE `reservasturnos` (
  `idReserva` int(11) NOT NULL,
  `idTurno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservasturnos`
--

INSERT INTO `reservasturnos` (`idReserva`, `idTurno`) VALUES
(6, 2),
(7, 1),
(8, 3),
(8, 4),
(8, 5),
(10, 3),
(10, 4),
(10, 5),
(11, 1),
(11, 2),
(11, 3),
(12, 4),
(20, 3),
(21, 4),
(22, 5),
(25, 3),
(25, 4),
(26, 5),
(26, 6),
(27, 1),
(28, 2),
(34, 4),
(34, 5),
(35, 5),
(35, 6),
(36, 1),
(36, 2),
(39, 5),
(39, 6),
(40, 2),
(40, 3),
(41, 1),
(41, 2),
(42, 3),
(42, 4),
(45, 1),
(49, 1),
(49, 2),
(49, 3),
(50, 1),
(51, 2),
(51, 3),
(51, 4),
(52, 1),
(52, 2),
(53, 2),
(54, 1),
(54, 2),
(54, 3),
(54, 4),
(54, 5),
(54, 6),
(60, 5),
(63, 4),
(63, 5),
(64, 4),
(64, 5),
(64, 6),
(65, 1),
(65, 2),
(65, 3),
(68, 1),
(68, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

CREATE TABLE `turnos` (
  `id` int(11) NOT NULL,
  `horario` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `turnos`
--

INSERT INTO `turnos` (`id`, `horario`) VALUES
(1, '08:30 - 09:30'),
(2, '09:30 - 10:30'),
(3, '10:30 - 11:30'),
(4, '12:00 - 13:00'),
(5, '13:00 - 14:00'),
(6, '14:00 - 15:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `asignaturasprofesores`
--
ALTER TABLE `asignaturasprofesores`
  ADD PRIMARY KEY (`idProfesor`,`idAsignatura`),
  ADD KEY `idAsignatura` (`idAsignatura`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idProfesor` (`idProfesor`),
  ADD KEY `idAsignatura` (`idAsignatura`);

--
-- Indices de la tabla `reservasturnos`
--
ALTER TABLE `reservasturnos`
  ADD PRIMARY KEY (`idReserva`,`idTurno`),
  ADD KEY `idTurno` (`idTurno`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `profesores`
--
ALTER TABLE `profesores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignaturasprofesores`
--
ALTER TABLE `asignaturasprofesores`
  ADD CONSTRAINT `asignaturasprofesores_ibfk_1` FOREIGN KEY (`idProfesor`) REFERENCES `profesores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `asignaturasprofesores_ibfk_2` FOREIGN KEY (`idAsignatura`) REFERENCES `asignaturas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`idProfesor`) REFERENCES `profesores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`idAsignatura`) REFERENCES `asignaturas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reservasturnos`
--
ALTER TABLE `reservasturnos`
  ADD CONSTRAINT `reservasturnos_ibfk_1` FOREIGN KEY (`idReserva`) REFERENCES `reservas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservasturnos_ibfk_2` FOREIGN KEY (`idTurno`) REFERENCES `turnos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
