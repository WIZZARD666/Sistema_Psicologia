-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2025 at 03:43 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistema_psicologia`
--

-- --------------------------------------------------------

--
-- Table structure for table `cita`
--

CREATE TABLE `cita` (
  `id_cita` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `descripcion` text NOT NULL,
  `color` varchar(50) NOT NULL,
  `textColor` varchar(50) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cita`
--

INSERT INTO `cita` (`id_cita`, `id_paciente`, `title`, `descripcion`, `color`, `textColor`, `start`, `end`) VALUES
(2, 3, 'Esgrima', 'Se va a relai', '#e3d1ff', '#0d6efd', '2025-11-08 00:00:00', '2025-11-09 00:00:00'),
(4, 3, 'aasasassa', 'saasasas', '#6610f2', '#0d6efd', '2025-11-08 01:01:00', '2025-11-09 02:00:00'),
(5, 18, 'assasasa', 'assasasa', '#6610f2', '#0d6efd', '2025-11-07 00:00:00', '2025-11-08 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `historia`
--

CREATE TABLE `historia` (
  `id_historia` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `sintomas` text NOT NULL,
  `otrosintomas` varchar(100) NOT NULL,
  `convives` varchar(150) NOT NULL,
  `cambiar` varchar(100) NOT NULL,
  `conflicto` varchar(100) NOT NULL,
  `trabajar` varchar(100) NOT NULL,
  `alcohol` enum('si','no') NOT NULL,
  `alcofrecuencia` varchar(100) NOT NULL,
  `fumas` enum('si','no') NOT NULL,
  `fumafrecuencia` varchar(50) NOT NULL,
  `consumir` enum('si','no') NOT NULL,
  `consufrecuencia` varchar(50) NOT NULL,
  `rutina` varchar(150) NOT NULL,
  `acudir` enum('no','psicologo','psiquiatra','otro') NOT NULL,
  `tratamiento` varchar(200) NOT NULL,
  `finalizar` varchar(200) NOT NULL,
  `significativo` varchar(150) NOT NULL,
  `PersonaSigni` varchar(100) NOT NULL,
  `PodriaAyudar` varchar(100) NOT NULL,
  `ConseguirTerapia` varchar(100) NOT NULL,
  `compromiso` int(10) NOT NULL,
  `TiempoDurara` varchar(50) NOT NULL,
  `considerar` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `historia`
--

INSERT INTO `historia` (`id_historia`, `id_paciente`, `sintomas`, `otrosintomas`, `convives`, `cambiar`, `conflicto`, `trabajar`, `alcohol`, `alcofrecuencia`, `fumas`, `fumafrecuencia`, `consumir`, `consufrecuencia`, `rutina`, `acudir`, `tratamiento`, `finalizar`, `significativo`, `PersonaSigni`, `PodriaAyudar`, `ConseguirTerapia`, `compromiso`, `TiempoDurara`, `considerar`) VALUES
(2, 5, 'Culpa', 'estres', 'sola', 'si', 'con mi perro', 'si', 'no', '', 'no', 'no', 'no', 'no', 'duermo 5 horas', 'psicologo', 'cognitivo', 'no por falta de recursos', '', 'mi familia', 'todos', 'ok', 6, '7dia', 'ok');

-- --------------------------------------------------------

--
-- Table structure for table `paciente`
--

CREATE TABLE `paciente` (
  `id_paciente` int(11) NOT NULL,
  `nombre` varchar(15) NOT NULL,
  `apellido` varchar(15) NOT NULL,
  `cedula` varchar(15) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `genero` varchar(25) NOT NULL,
  `email` varchar(30) NOT NULL,
  `id_ubicacion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paciente`
--

INSERT INTO `paciente` (`id_paciente`, `nombre`, `apellido`, `cedula`, `telefono`, `fecha_nacimiento`, `genero`, `email`, `id_ubicacion`) VALUES
(2, 'Luiss', 'Rodriguez', '9555514', '04245755088', '2011-12-16', 'femenino', 'lennymosq@gmail.com', 2),
(3, 'Juan', 'Gimenez', '10846157', '04146758795', '2011-12-02', 'masculino', 'yahir@gmail.com', 3),
(5, 'Stephi', 'Q', '24567078', '04245676708', '1995-10-04', 'femenino', 'steoiheredia@gmai.com', 5),
(6, 'Shikamaru', 'Prince', '12345678', '04266525036', '2011-12-15', 'masculino', 'carlosgonzalez@mail.com', 6),
(8, 'Shikamaru', 'Prince', '12345678', '04266525036', '2011-12-15', 'masculino', 'carlosgonzalez@mail.com', 9),
(10, 'Shikamaru', 'Prince', '12345678', '04266525036', '2011-12-15', 'masculino', 'carlosgonzalez@mail.com', 11),
(18, 'Shikamaru', 'Princea', '26428841', '2189219', '2011-12-23', 'masculino', 'yahir@gmail.com', 19),
(24, 'Shikamaru', 'prince', '123456789', '04266525036', '2025-11-01', 'masculino', 'yahir@gmail.com', 25);

-- --------------------------------------------------------

--
-- Table structure for table `paciente_test`
--

CREATE TABLE `paciente_test` (
  `id_paciente_test` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `id_test_confianza` int(11) DEFAULT NULL,
  `id_test_importancia` int(11) DEFAULT NULL,
  `id_test_poms` int(11) DEFAULT NULL,
  `fecha` date NOT NULL
) ;

--
-- Dumping data for table `paciente_test`
--

INSERT INTO `paciente_test` (`id_paciente_test`, `id_paciente`, `id_test_confianza`, `id_test_importancia`, `id_test_poms`, `fecha`) VALUES
(3, 2, 2, NULL, NULL, '2025-11-10'),
(4, 3, 3, NULL, NULL, '2025-11-10'),
(5, 3, NULL, 2, NULL, '2025-11-17');

-- --------------------------------------------------------

--
-- Table structure for table `personal`
--

CREATE TABLE `personal` (
  `id_personal` int(11) NOT NULL,
  `cedula` varchar(12) NOT NULL,
  `nombre` varchar(32) NOT NULL,
  `apellido` varchar(32) NOT NULL,
  `telefono` varchar(14) NOT NULL,
  `direccion` varchar(20) NOT NULL,
  `rol` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personal`
--

INSERT INTO `personal` (`id_personal`, `cedula`, `nombre`, `apellido`, `telefono`, `direccion`, `rol`, `password`) VALUES
(1, '31574454', 'Yahir', 'Rivero', '04266525036', 'Carrera  entre calle', 'Secretaria', '[object Object]'),
(2, '32271095', 'Camila', 'Toro', '04145744109', 'Cerrajones', 'Secretaria', 'Camila.10'),
(7, '24567078', 'Stephanie', 'Quintero', '04245676708', 'residencia yupa', 'Doctor', 'Stephanie4.'),
(9, '95555141', 'yahir', 'Rivero', '04266525036', 'Carrera  entre calle', 'Doctor', 'Yahir.06');

-- --------------------------------------------------------

--
-- Table structure for table `test_confianza`
--

CREATE TABLE `test_confianza` (
  `id_test_confianza` int(11) NOT NULL,
  `edad` int(11) DEFAULT NULL,
  `id_paciente` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `respuestas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`respuestas`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_confianza`
--

INSERT INTO `test_confianza` (`id_test_confianza`, `edad`, `id_paciente`, `fecha`, `respuestas`) VALUES
(2, NULL, 2, '2025-11-10', '{\n    \"1\": 3,\n    \"2\": 2,\n    \"3\": 3,\n    \"4\": 1,\n    \"5\": 1,\n    \"6\": 1,\n    \"7\": 1,\n    \"8\": 1,\n    \"9\": 1,\n    \"10\": 1\n}'),
(3, NULL, 3, '2025-11-17', '{\"1\":1,\"2\":3,\"3\":1,\"4\":1,\"5\":1,\"6\":1,\"7\":1,\"8\":1,\"9\":1,\"10\":1}');

-- --------------------------------------------------------

--
-- Table structure for table `test_importancia`
--

CREATE TABLE `test_importancia` (
  `id_test_importancia` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `edad` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `parte1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`parte1`)),
  `parte2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`parte2`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_importancia`
--

INSERT INTO `test_importancia` (`id_test_importancia`, `id_paciente`, `edad`, `fecha`, `parte1`, `parte2`) VALUES
(2, 3, NULL, '2025-11-17', '{\n    \"1\": 1,\n    \"2\": 1,\n    \"3\": 1,\n    \"4\": 1,\n    \"5\": 1,\n    \"6\": 1,\n    \"7\": 1,\n    \"8\": 1,\n    \"9\": 1,\n    \"10\": 1,\n    \"11\": 1,\n    \"12\": 2,\n    \"13\": 2,\n    \"14\": 2,\n    \"15\": 3,\n    \"16\": 2,\n    \"17\": 2\n}', '{\n    \"18\": 1,\n    \"19\": 1,\n    \"20\": 1,\n    \"21\": 1,\n    \"22\": 1,\n    \"23\": 1,\n    \"24\": 1,\n    \"25\": 1,\n    \"26\": 1,\n    \"27\": 1,\n    \"28\": 1,\n    \"29\": 1,\n    \"30\": 1,\n    \"31\": 1,\n    \"32\": 1,\n    \"33\": 1,\n    \"34\": 1\n}');

-- --------------------------------------------------------

--
-- Table structure for table `test_poms`
--

CREATE TABLE `test_poms` (
  `id_test_poms` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `deporte` varchar(100) DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `respuestas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`respuestas`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tratamientos`
--

CREATE TABLE `tratamientos` (
  `id_tratamiento` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `fecha_creacion` date NOT NULL,
  `diagnostico_descripcion` varchar(100) NOT NULL,
  `tratamiento_tipo` varchar(100) NOT NULL,
  `estado_actual` varchar(20) NOT NULL,
  `observaciones` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tratamientos`
--

INSERT INTO `tratamientos` (`id_tratamiento`, `id_paciente`, `fecha_creacion`, `diagnostico_descripcion`, `tratamiento_tipo`, `estado_actual`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-11-10', 'ya pues que ', 'Hay que correre', 'pausado', 'saassaassasa', '2025-11-10 11:55:55', '2025-11-10 11:55:55'),
(2, 5, '2025-11-17', 'nada bien todo mal ', 'cognitivo', 'inicial', 'se va a merida en diciembre', '2025-11-17 20:14:31', '2025-11-17 20:14:31'),
(3, 5, '2025-11-17', 'uuuuuuuuuuu', 'jjjjjjj', 'inicial', '', '2025-11-17 20:14:52', '2025-11-17 20:14:52');

-- --------------------------------------------------------

--
-- Table structure for table `ubicacion`
--

CREATE TABLE `ubicacion` (
  `id_ubicacion` int(11) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `pais` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ubicacion`
--

INSERT INTO `ubicacion` (`id_ubicacion`, `ciudad`, `pais`) VALUES
(1, 'Barquisimeto', 'Lara'),
(2, 'Barquisimeto', 'Venezuela'),
(3, 'Barquisimeto', 'Venezuela'),
(4, 'Maracay', 'Estados unidos '),
(5, 'Barquisimeto', 'Venezuela'),
(6, 'Venezuela', 'Colombia'),
(7, 'Venezuela', 'Colombia'),
(8, 'Venezuela', 'Colombia'),
(9, 'Venezuela', 'Colombia'),
(10, 'Venezuela', 'Colombia'),
(11, 'Venezuela', 'Colombia'),
(12, 'Venezuela', 'Colombia'),
(13, 'Yyyyyyy', 'Yyyyyy'),
(14, 'Venezuela', 'Colombia'),
(15, 'Maracay', 'Salkdhsal'),
(16, 'Barquisime', 'Estados unidos '),
(17, 'Maracay', 'Salkdhsal'),
(18, 'Maracay', 'Salkdhsal'),
(19, 'Barquisime', 'Colombia'),
(20, 'Barquisimeto', 'salkdhsal'),
(21, 'Barquisime', 'Venezuela'),
(22, 'Barquisime', 'venezuela'),
(23, 'Barquisimeto', 'Venezuela'),
(24, 'Venezuela', 'venezuela'),
(25, 'Caracas', 'Estados unidos ');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cita`
--
ALTER TABLE `cita`
  ADD PRIMARY KEY (`id_cita`),
  ADD KEY `id_paciente` (`id_paciente`);

--
-- Indexes for table `historia`
--
ALTER TABLE `historia`
  ADD PRIMARY KEY (`id_historia`),
  ADD KEY `id_paciente` (`id_paciente`);

--
-- Indexes for table `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`id_paciente`),
  ADD UNIQUE KEY `id_ubicacion` (`id_ubicacion`);

--
-- Indexes for table `paciente_test`
--
ALTER TABLE `paciente_test`
  ADD PRIMARY KEY (`id_paciente_test`),
  ADD KEY `id_paciente` (`id_paciente`),
  ADD KEY `fk_id_test_importancia_cascade` (`id_test_importancia`),
  ADD KEY `fk_id_test_poms_cascade` (`id_test_poms`),
  ADD KEY `fk_id_test_confianza_cascade` (`id_test_confianza`);

--
-- Indexes for table `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`id_personal`);

--
-- Indexes for table `test_confianza`
--
ALTER TABLE `test_confianza`
  ADD PRIMARY KEY (`id_test_confianza`),
  ADD KEY `id_paciente` (`id_paciente`);

--
-- Indexes for table `test_importancia`
--
ALTER TABLE `test_importancia`
  ADD PRIMARY KEY (`id_test_importancia`),
  ADD KEY `id_paciente` (`id_paciente`);

--
-- Indexes for table `test_poms`
--
ALTER TABLE `test_poms`
  ADD PRIMARY KEY (`id_test_poms`),
  ADD KEY `id_paciente` (`id_paciente`);

--
-- Indexes for table `tratamientos`
--
ALTER TABLE `tratamientos`
  ADD PRIMARY KEY (`id_tratamiento`);

--
-- Indexes for table `ubicacion`
--
ALTER TABLE `ubicacion`
  ADD PRIMARY KEY (`id_ubicacion`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cita`
--
ALTER TABLE `cita`
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `historia`
--
ALTER TABLE `historia`
  MODIFY `id_historia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `paciente`
--
ALTER TABLE `paciente`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `paciente_test`
--
ALTER TABLE `paciente_test`
  MODIFY `id_paciente_test` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal`
--
ALTER TABLE `personal`
  MODIFY `id_personal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `test_confianza`
--
ALTER TABLE `test_confianza`
  MODIFY `id_test_confianza` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `test_importancia`
--
ALTER TABLE `test_importancia`
  MODIFY `id_test_importancia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `test_poms`
--
ALTER TABLE `test_poms`
  MODIFY `id_test_poms` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tratamientos`
--
ALTER TABLE `tratamientos`
  MODIFY `id_tratamiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ubicacion`
--
ALTER TABLE `ubicacion`
  MODIFY `id_ubicacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cita`
--
ALTER TABLE `cita`
  ADD CONSTRAINT `cita_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id_paciente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `historia`
--
ALTER TABLE `historia`
  ADD CONSTRAINT `historia_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id_paciente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `paciente`
--
ALTER TABLE `paciente`
  ADD CONSTRAINT `fk_ubicacion` FOREIGN KEY (`id_ubicacion`) REFERENCES `ubicacion` (`id_ubicacion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `paciente_test`
--
ALTER TABLE `paciente_test`
  ADD CONSTRAINT `fk_id_test_confianza_cascade` FOREIGN KEY (`id_test_confianza`) REFERENCES `test_confianza` (`id_test_confianza`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_id_test_importancia_cascade` FOREIGN KEY (`id_test_importancia`) REFERENCES `test_importancia` (`id_test_importancia`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_id_test_poms_cascade` FOREIGN KEY (`id_test_poms`) REFERENCES `test_poms` (`id_test_poms`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `paciente_test_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id_paciente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `paciente_test_ibfk_2` FOREIGN KEY (`id_test_confianza`) REFERENCES `test_confianza` (`id_test_confianza`) ON UPDATE CASCADE,
  ADD CONSTRAINT `paciente_test_ibfk_4` FOREIGN KEY (`id_test_poms`) REFERENCES `test_poms` (`id_test_poms`) ON UPDATE CASCADE;

--
-- Constraints for table `test_confianza`
--
ALTER TABLE `test_confianza`
  ADD CONSTRAINT `test_confianza_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id_paciente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `test_importancia`
--
ALTER TABLE `test_importancia`
  ADD CONSTRAINT `test_importancia_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id_paciente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `test_poms`
--
ALTER TABLE `test_poms`
  ADD CONSTRAINT `test_poms_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id_paciente`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
