-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 03, 2025 at 04:34 PM
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
  `id_psicologo` int(11) NOT NULL,
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

INSERT INTO `cita` (`id_cita`, `id_paciente`, `id_psicologo`, `title`, `descripcion`, `color`, `textColor`, `start`, `end`) VALUES
(16, 21, 0, 'DEHT NOTE', 'yap', '#6610f2', '#0d6efd', '2025-08-16 17:14:00', '2025-08-16 06:44:00'),
(17, 31, 0, 'Linkind Park', 'hola como estas ', '#6610f2', '#0d6efd', '2025-08-16 17:55:00', '2025-08-16 21:55:00'),
(19, 31, 0, 'aaA', 'aaAAasa', '#6610f2', '#0d6efd', '2025-08-23 18:03:00', '2025-08-23 21:03:00'),
(20, 31, 0, 'Cama', 'venta', '#000000', '#ffffff', '2025-08-31 15:28:00', '2025-08-31 16:28:00'),
(26, 21, 0, 'elfurusial', 'assaassa', '#6610f2', '#0d6efd', '2025-08-15 00:00:00', '2025-08-16 00:00:00'),
(27, 21, 0, 'cacacaca', 'cacacaca', '#6610f2', '#0d6efd', '2025-08-22 00:00:00', '2025-08-22 03:00:00'),
(28, 21, 0, 'Cita', 'Es algo muy mall', '#ffffff', '#0d6efd', '2025-08-25 00:00:00', '2025-08-27 04:00:00'),
(31, 21, 0, 'Juan', 'Cita para ver el proceso', '#6610f2', '#0d6efd', '2025-09-05 00:00:00', '2025-09-06 00:00:00'),
(33, 63, 0, 'Boxeo', 'Mal', '#cab2f0', '#0d6efd', '2025-09-18 00:00:00', '2025-09-21 00:00:00'),
(34, 66, 0, 'Partidos', 'Barcelona madria', '#6610f2', '#0d6efd', '2025-10-05 01:56:00', '2025-10-05 03:00:00'),
(36, 66, 0, 'yaya', 'yaya', '#6610f2', '#0d6efd', '2025-10-05 00:00:00', '2025-10-06 00:00:00'),
(37, 21, 0, 'Hoa', 'Dia de las madres', '#6610f2', '#0d6efd', '2025-10-06 10:00:00', '2025-10-07 00:00:00'),
(38, 60, 0, 'sadasd', 'sadsad', '#6610f2', '#0d6efd', '2025-10-06 00:00:00', '2025-10-07 00:00:00'),
(39, 21, 0, 'sadsasda', 'sadsadsda', '#6610f2', '#0d6efd', '2025-10-27 00:00:00', '2025-10-27 00:00:00'),
(40, 54, 0, 'saddas', 'sdadsaasd', '#6610f2', '#0d6efd', '2025-10-27 00:00:00', '2025-10-28 00:00:00'),
(41, 54, 0, 'sadsa', 'sadsda', '#6610f2', '#0d6efd', '2025-10-28 00:00:00', '2025-10-29 00:00:00'),
(42, 54, 0, 'Yahir', 'JAJAJAJ', '#6610f2', '#0d6efd', '2025-10-29 02:00:00', '2025-10-29 03:00:00'),
(43, 31, 0, 'sasa', 'assa', '#6610f2', '#0d6efd', '2025-10-07 00:00:00', '2025-10-08 00:00:00'),
(45, 31, 0, 'adda', 'adda', '#6610f2', '#0d6efd', '2025-10-06 10:00:00', '2025-10-07 00:00:00'),
(46, 21, 0, 'asas', 'asasas', '#6610f2', '#0d6efd', '2025-10-08 00:00:00', '2025-10-09 00:00:00'),
(48, 60, 0, 'yayayay', 'yayayyayay', '#6610f2', '#0d6efd', '2025-10-09 00:00:00', '2025-10-10 00:00:00'),
(49, 63, 0, 'asasasas', 'assaasas', '#c0a3f0', '#dde4ee', '2025-10-09 10:00:00', '2025-10-11 00:00:00'),
(50, 64, 0, 'sasdasdasd', 'asdsadsda', '#6610f2', '#0d6efd', '2025-10-14 00:00:00', '2025-10-15 00:00:00'),
(51, 63, 0, 'sasasaas', 'asasasas', '#6610f2', '#0d6efd', '2025-10-30 00:00:00', '2025-10-31 00:00:00'),
(54, 31, 0, 'asa', 'asa', '#6610f2', '#0d6efd', '2025-10-31 09:00:00', '2025-10-31 10:00:00'),
(56, 31, 0, 'daadad', 'addada', '#6610f2', '#0d6efd', '2025-10-31 00:00:00', '2025-11-01 00:00:00');

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
(20, 54, 'Tensión,Taquicardia', 'Yahir', 'Yahir', 'Yahir', 'Yahir', 'Yahir', 'si', 'Yahir', 'si', 'Yahir', 'si', 'Yahir', 'Yahir', 'psicologo', 'Yahir', 'Yahir', 'Yahir', 'Yahir', 'Yahir', 'Yahir', 9, 'Yahir', 'Yahir');

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
  `password` varchar(15) NOT NULL,
  `foto` text DEFAULT NULL,
  `id_ubicacion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paciente`
--

INSERT INTO `paciente` (`id_paciente`, `nombre`, `apellido`, `cedula`, `telefono`, `fecha_nacimiento`, `genero`, `email`, `password`, `foto`, `id_ubicacion`) VALUES
(21, 'Pedro', 'González', '16234567', '5841412345', '1990-01-26', 'femenino', 'carlosgonzalez@mail.com', '', NULL, 7),
(31, 'Jose', 'Perez', '12177382', '04146758795', '2011-12-09', 'masculino', 'yahir@gmail.com', '', NULL, 17),
(54, 'Carlos', 'Nara', '12177382', '5841412345', '2011-12-22', 'masculino', 'yahir@gmail.com', '', NULL, 46),
(60, 'Mama', 'Gonzálezz', '11599453', '04266525036', '2011-12-15', 'masculino', 'carlosgonzalez@mail.com', '', NULL, 52),
(63, 'Yahir', 'Rivero', '16234567', '04266525036', '2011-12-23', 'masculino', 'yahir@gmail.com', '', NULL, 55),
(64, 'Carlos', 'González', '12177382', '5841412345', '2011-12-15', 'masculino', 'carlosgonzalez@mail.com', '', '12177382.png', 56),
(66, 'Juan', 'Gimenez', '32292010', '04145308765', '2007-06-13', 'masculino', 'juangimenez@gmail.com', '', NULL, 58),
(67, 'Shikamaru', 'Prince', '31574454', '04266525038', '2011-12-31', 'masculino', 'yahirmos@gmail.com', '', NULL, 59),
(69, 'Shikamaru', 'Prince', '12345678', '04266525036', '2011-12-06', 'masculino', 'mama@gmail.com', '', NULL, 61),
(71, 'Shikamaru', 'Nara', '12177382', '04266525036', '2011-12-24', 'masculino', 'carlosgonzalez@mail.com', '', NULL, 63),
(72, 'Camila', 'Toro', '32271095', '04266525036', '2007-10-11', 'femenino', 'camilatoro@gmail.com', '', NULL, 64),
(73, 'Yahir', 'Asas', '112122121', '211212121', '2011-12-09', 'femenino', 'carlosgonzalez@mail.com', '', NULL, 65);

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
(9, 67, 41, NULL, NULL, '2025-11-02'),
(10, 63, 42, NULL, NULL, '2025-11-02'),
(11, 60, NULL, NULL, 17, '2025-11-02'),
(12, 54, NULL, 12, NULL, '2025-11-02'),
(13, 63, NULL, NULL, 18, '2025-11-02');

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
  `rol` varchar(20) NOT NULL,
  `direccion` varchar(11) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personal`
--

INSERT INTO `personal` (`id_personal`, `cedula`, `nombre`, `apellido`, `telefono`, `rol`, `direccion`, `password`) VALUES
(8, '31574454', 'Wilden', 'Naraa', '04266525036', 'null', 'Doctor', ''),
(9, '123456783', 'Shikamaru', 'Rivero', '1313131313', 'Secretaria', 'saddasas@gm', ''),
(10, '12321212', 'sasa', 'González', '5841412345', 'Doctor', 'saddasas@gm', '12345'),
(12, '13131313', 'Carlos', 'González', '5841412345', 'Secretaria', 'aaaaa', '131313'),
(13, '11111111', 'Shikamaru', 'González', '5841412345', 'Doctor', 'adad', '12345678'),
(14, '21212121', 'Mama', 'Perez', '21212121', 'Doctor', 'asas', '$2y$10$Hz9bEdwdNqIkuZYybLIDYuMHAK9Jmu4NpD2URFQbvM9L1h8gsHfBa'),
(15, '31313131', 'Carlos', 'Nara', '5841412345', 'Secretaria', 'Doctor', 'Adrian.10'),
(16, '21345342', 'Laura', 'Peña', '04146758795', 'Secretaria', 'Barrio Unio', '$2y$10$Fu0UMfQvV3MSXj9tVJrS8uVTvZOvW4nou3lfq68ujQ.P/1/R9oONe'),
(17, '12249177', 'Jenny', 'Mosquera', '04149510472', 'Secretaria', 'Anthena', '$2y$10$k/GHo0nobpkUbaLTWXw34errvWr89uQ1Eik92qTwn0FPwpJtImln2'),
(18, '32271095', 'Camila', 'Toro', '04149510472', 'Doctor', 'Cerrajones', 'Camila.11'),
(20, '32065363', 'Isaac', 'Morales', '04245755088', 'Doctor', 'Villa', 'Im.32065363');

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
(41, NULL, 67, '2025-11-02', '{\"1\":3,\"2\":1,\"3\":1,\"4\":1,\"5\":1,\"6\":1,\"7\":1,\"8\":1,\"9\":1,\"10\":1}'),
(42, NULL, 63, '2025-11-02', '{\n    \"1\": 3,\n    \"2\": 1,\n    \"3\": 2,\n    \"4\": 1,\n    \"5\": 1,\n    \"6\": 1,\n    \"7\": 1,\n    \"8\": 1,\n    \"9\": 1,\n    \"10\": 1\n}');

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
(12, 54, NULL, '2025-11-02', '{\n    \"1\": 6,\n    \"2\": 5,\n    \"3\": 4,\n    \"4\": 3,\n    \"5\": 2,\n    \"6\": 1,\n    \"7\": 1,\n    \"8\": 1,\n    \"9\": 1,\n    \"10\": 1,\n    \"11\": 1,\n    \"12\": 1,\n    \"13\": 1,\n    \"14\": 1,\n    \"15\": 1,\n    \"16\": 1,\n    \"17\": 1\n}', '{\n    \"18\": 1,\n    \"19\": 1,\n    \"20\": 1,\n    \"21\": 1,\n    \"22\": 1,\n    \"23\": 1,\n    \"24\": 1,\n    \"25\": 1,\n    \"26\": 1,\n    \"27\": 1,\n    \"28\": 1,\n    \"29\": 1,\n    \"30\": 1,\n    \"31\": 1,\n    \"32\": 1,\n    \"33\": 1,\n    \"34\": 1\n}');

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

--
-- Dumping data for table `test_poms`
--

INSERT INTO `test_poms` (`id_test_poms`, `id_paciente`, `fecha`, `deporte`, `edad`, `respuestas`) VALUES
(17, 60, '2025-11-03', 'Holaa', NULL, '{\"1\":3,\"2\":2,\"3\":2,\"4\":2,\"5\":2,\"6\":0,\"7\":0,\"8\":0,\"9\":0,\"10\":0,\"11\":0,\"12\":0,\"13\":0,\"14\":0,\"15\":0,\"16\":0,\"17\":0,\"18\":0,\"19\":0,\"20\":0,\"21\":0,\"22\":0,\"23\":0,\"24\":0,\"25\":0,\"26\":0,\"27\":0,\"28\":0,\"29\":0,\"30\":0,\"31\":0,\"32\":0,\"33\":0,\"34\":0,\"35\":0,\"36\":0,\"37\":0,\"38\":0,\"39\":0,\"40\":0,\"41\":0,\"42\":0,\"43\":0,\"44\":0,\"45\":0,\"46\":0,\"47\":0,\"48\":0,\"49\":0,\"50\":0,\"51\":0,\"52\":0,\"53\":0,\"54\":0,\"55\":0,\"56\":0,\"57\":0,\"58\":0,\"59\":0,\"60\":0,\"61\":2,\"62\":2,\"63\":2,\"64\":2,\"65\":2}'),
(18, 63, '2025-11-02', 'Futbol', NULL, '{\n    \"1\": 4,\n    \"2\": 4,\n    \"3\": 4,\n    \"4\": 4,\n    \"5\": 4,\n    \"6\": 0,\n    \"7\": 0,\n    \"8\": 0,\n    \"9\": 0,\n    \"10\": 0,\n    \"11\": 0,\n    \"12\": 0,\n    \"13\": 0,\n    \"14\": 0,\n    \"15\": 0,\n    \"16\": 0,\n    \"17\": 0,\n    \"18\": 0,\n    \"19\": 0,\n    \"20\": 0,\n    \"21\": 0,\n    \"22\": 0,\n    \"23\": 0,\n    \"24\": 0,\n    \"25\": 0,\n    \"26\": 0,\n    \"27\": 0,\n    \"28\": 0,\n    \"29\": 0,\n    \"30\": 0,\n    \"31\": 0,\n    \"32\": 0,\n    \"33\": 0,\n    \"34\": 0,\n    \"35\": 0,\n    \"36\": 0,\n    \"37\": 0,\n    \"38\": 0,\n    \"39\": 0,\n    \"40\": 0,\n    \"41\": 0,\n    \"42\": 0,\n    \"43\": 0,\n    \"44\": 0,\n    \"45\": 0,\n    \"46\": 0,\n    \"47\": 0,\n    \"48\": 0,\n    \"49\": 0,\n    \"50\": 0,\n    \"51\": 0,\n    \"52\": 0,\n    \"53\": 0,\n    \"54\": 0,\n    \"55\": 0,\n    \"56\": 0,\n    \"57\": 0,\n    \"58\": 0,\n    \"59\": 0,\n    \"60\": 0,\n    \"61\": 0,\n    \"62\": 0,\n    \"63\": 0,\n    \"64\": 0,\n    \"65\": 0\n}');

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
(1, 0, '2025-06-05', 'hola como estas ', 'malo', 'inicial', 'muy malas', '2025-06-19 18:56:38', '2025-06-19 18:56:38'),
(10, 1, '2025-06-12', 'lik', 'hkjhk', 'inicial', 'PATO', '2025-06-08 18:14:49', '2025-06-08 18:14:49'),
(29, 12, '2025-06-18', 'xddd', 'geometri', 'inicial', 'nkukkaoilqhs', '2025-06-09 11:11:46', '2025-06-09 11:11:46'),
(34, 9, '2025-06-17', 'ygjbk', 'Vfhg', 'seguimiento', 'ningunax', '2025-06-12 16:03:34', '2025-06-12 16:03:34'),
(36, 22, '2024-03-07', 'khukjzhnkjxn', 'Malo', 'en_progreso', 'alioziajxoiwjxojs', '2025-06-27 14:24:09', '2025-06-27 14:24:09'),
(37, 53, '2025-09-22', 'assaasasas', 'assaasasas', 'inicial', 'asasas', '2025-09-22 00:48:23', '2025-09-22 00:48:23'),
(39, 66, '2025-10-06', 'muy malo', 'Malo', 'inicial', 'mal', '2025-10-06 15:11:06', '2025-10-06 15:11:06'),
(40, 31, '2025-11-02', 'sasasasasa', 'Hay que correr', 'seguimiento', 'sasasasasa', '2025-11-02 22:12:37', '2025-11-02 22:12:37'),
(41, 63, '2025-11-02', 'aassaasasas', 'assaasasas', 'pausado', 'aassaasas', '2025-11-02 22:13:26', '2025-11-02 22:13:26'),
(42, 31, '2025-11-03', 'saassasasa', 'Hay que correr', 'finalizado', 'asasassaas', '2025-11-02 22:13:47', '2025-11-02 22:13:47'),
(43, 69, '2025-11-04', 'asdasdadas', 'Hay que correr', 'seguimiento', 'asdsadadasd', '2025-11-02 22:14:15', '2025-11-02 22:14:15'),
(44, 64, '2025-11-05', 'sadsdaasdasd', 'saaddaasduuu', 'inicial', 'asdsdasaddsaasd', '2025-11-02 22:14:30', '2025-11-02 22:14:30');

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
(1, 'Barquisimeto', 'Venezuela'),
(2, 'Barquisimeto', 'Venezuela'),
(3, 'Barquisimeto', 'Venezuela'),
(4, 'Barquisimeto', 'Venezuela'),
(5, 'Barquisimeto', 'Venezuela'),
(6, 'Lara', 'Calombia'),
(7, 'Maracay', 'Venezuela'),
(9, 'Barquisimeto', 'Venezuela'),
(10, 'Barquisimeto', 'Venezuela'),
(11, 'Barquisimeto', 'Venezuela'),
(12, 'Barquisimeto', 'Venezuela'),
(13, 'Barquisimeto', 'Venezuela'),
(14, 'Barquisimeto', 'Venezuela'),
(15, 'Barquisimeto', 'Venezuela'),
(16, 'Barquisimeto', 'Venezuela'),
(17, 'Barquisimeto', 'Venezuela'),
(18, 'Barquisimeto', 'Venezuela'),
(19, 'Barquisimeto', 'Venezuela'),
(20, 'Barquisimeto', 'Venezuela'),
(21, 'Barquisimeto', 'Venezuela'),
(22, 'Ciudad Bolivar', 'Venezuela'),
(24, 'Maracay', 'Venezuela'),
(25, 'Maracay', 'Venezuela'),
(26, 'Caracas', 'Venezuela'),
(32, 'Barquisimeto', 'Venezuela'),
(33, 'Barquisimeto', 'Venezuela'),
(34, 'Barquisimeto', 'Venezuela'),
(35, 'Barquisimeto', 'Venezuela'),
(36, 'Barquisimeto', 'Venezuela'),
(37, 'Barquisimeto', 'Venezuela'),
(38, 'Barquisimeto', 'Venezuela'),
(39, 'Barquisimeto', 'Venezuela'),
(40, 'Barquisimeto', 'Venezuela'),
(41, 'Barquisimeto', 'Venezuela'),
(42, 'Valencia', 'Venezuela'),
(43, 'Valencia', 'Venezuela'),
(44, 'Valencia', 'Venezuela'),
(45, 'Gato', 'Venezuela'),
(46, 'Valencia', 'Vebeco'),
(47, 'Valencia', 'Vebeco'),
(48, 'Valencia', 'Vebeco'),
(49, 'Valencia', 'Venezuela'),
(50, 'Gato', 'Venezuela'),
(51, 'Valencia', 'Venezuela'),
(52, 'Ciudad Bolivar', 'Vebeco'),
(53, 'Ciudad Bolivar', 'Italia'),
(54, 'Ciudad Bolivar', 'Italia'),
(55, 'Gato', 'Italia'),
(56, 'Barquisimeto', 'Venezuela'),
(57, 'Barquisimeto', 'Venezuela'),
(58, 'Barquisimeto', 'Venezuela'),
(59, 'Barquisime', 'Estados unidos '),
(60, 'Barquisimeto', 'Estados unidos '),
(61, 'Barquisimeto', 'Peru'),
(62, 'Barquisimeto', 'Venezuela'),
(63, 'Venezia', 'Venezuela'),
(64, 'Barquisimeto', 'Venezuela'),
(65, 'Venezia', 'Venezuela');

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
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `historia`
--
ALTER TABLE `historia`
  MODIFY `id_historia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `paciente`
--
ALTER TABLE `paciente`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `paciente_test`
--
ALTER TABLE `paciente_test`
  MODIFY `id_paciente_test` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal`
--
ALTER TABLE `personal`
  MODIFY `id_personal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `test_confianza`
--
ALTER TABLE `test_confianza`
  MODIFY `id_test_confianza` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `test_importancia`
--
ALTER TABLE `test_importancia`
  MODIFY `id_test_importancia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `test_poms`
--
ALTER TABLE `test_poms`
  MODIFY `id_test_poms` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tratamientos`
--
ALTER TABLE `tratamientos`
  MODIFY `id_tratamiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `ubicacion`
--
ALTER TABLE `ubicacion`
  MODIFY `id_ubicacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

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
