-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 10-01-2019 a las 16:13:33
-- Versión del servidor: 5.7.24-0ubuntu0.18.04.1
-- Versión de PHP: 7.2.10-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `saturno`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `getJuegoProcesos` (IN `v_producto` VARCHAR(70))  BEGIN
START TRANSACTION;
SELECT descripcionproceso,numeroProceso from juegoprocesos where identificadorJuego=(SELECT juegoProcesos from tipoproducto where alias=(SELECT tipo from producto where descripcion=(
SELECT descripcionDisenio from impresiones where descripcionImpresion=v_producto))) and numeroProceso!=0;
COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getJuegoProcesosByType` (IN `v_tipo` VARCHAR(30))  BEGIN
START TRANSACTION;
SELECT id,descripcionproceso,numeroProceso from juegoprocesos where identificadorJuego=(SELECT juegoProcesos from tipoproducto where alias=v_tipo) and numeroProceso!=0;
COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getlastProcess` (IN `v_tipo` VARCHAR(30))  BEGIN
START TRANSACTION;
SELECT descripcionproceso FROM juegoprocesos where numeroProceso=(SELECT MAX(numeroProceso) FROM juegoprocesos WHERE identificadorJuego=(SELECT juegoprocesos FROM tipoproducto WHERE tipo=v_tipo)) and identificadorJuego=(SELECT juegoprocesos FROM tipoproducto WHERE tipo=v_tipo);
COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getNodoBS` (IN `v_producto` VARCHAR(70), IN `v_noProces` INT)  BEGIN
START TRANSACTION;
SELECT id,descripcionProceso as actual,(SELECT descripcionProceso from juegoprocesos where identificadorJuego=(select juegoprocesos from tipoproducto where alias="BS") and numeroProceso=v_noProces+1 and baja=1) as siguiente,(SELECT descripcionProceso from juegoprocesos where identificadorJuego=(select juegoprocesos from tipoproducto where alias="BS") and numeroProceso=v_noProces-1 and descripcionProceso!='Caja' and descripcionProceso!='Rollo' and descripcionProceso!='Embarque' and baja=1) as anterior,numeroProceso from juegoprocesos where numeroProceso=v_noProces and 
identificadorJuego=(select juegoprocesos from tipoproducto where 
                    alias="BS") and baja=1;
COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getNodoProceso` (IN `v_producto` VARCHAR(70), IN `v_noProces` VARCHAR(50))  BEGIN
START TRANSACTION;
SELECT id,descripcionProceso as actual,(SELECT descripcionProceso from juegoprocesos where identificadorJuego=(select juegoprocesos from tipoproducto where 
                    alias=(select tipo from producto where 
                          descripcion=(select descripcionDisenio from impresiones where descripcionImpresion=v_producto))) and numeroProceso=v_noProces+1 and baja=1) as siguiente,(SELECT descripcionProceso from juegoprocesos where identificadorJuego=(select juegoprocesos from tipoproducto where alias=(select tipo from producto where descripcion=(select descripcionDisenio from impresiones where descripcionImpresion=v_producto))) and numeroProceso=v_noProces-1 and descripcionProceso!='Caja' and descripcionProceso!='Rollo' and descripcionProceso!='Embarque') as anterior,numeroProceso from juegoprocesos where numeroProceso=v_noProces and 
identificadorJuego=(select juegoprocesos from tipoproducto where 
                    alias=(select tipo from producto where 
                          descripcion=(select descripcionDisenio from impresiones where descripcionImpresion=v_producto)));
COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getNoOp` (IN `v_producto` VARCHAR(70))  BEGIN
DECLARE v_tipo varchar(30);
START TRANSACTION;
SET v_tipo=(SELECT tipo from producto where descripcion=(SELECT descripcionDisenio from impresiones where descripcionImpresion=v_producto));
IF v_tipo IS NULL THEN
SET v_tipo='BS';
END IF;
SELECT count(idLote)+1 as noop from lotes WHERE tipo=v_tipo;
COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getTipo` (IN `v_producto` VARCHAR(70))  BEGIN
DECLARE v_isnt varchar(30);
START TRANSACTION;
set v_isnt=(SELECT alias from tipoproducto where alias=(SELECT tipo from producto where descripcion=(SELECT descripcionDisenio from impresiones where descripcionImpresion=v_producto)));
if v_isnt is null then 
set v_isnt="BS";
END IF;
SELECT alias from tipoproducto where alias=v_isnt;
COMMIT;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accesos`
--

CREATE TABLE `accesos` (
  `id` int(11) NOT NULL,
  `perfil` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cdgmodulo` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `permiso` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `accesos`
--

INSERT INTO `accesos` (`id`, `perfil`, `cdgmodulo`, `permiso`) VALUES
(1, 'PF2', '10001', 'rwx'),
(2, 'PF2', '10002', 'rwx'),
(3, 'PF2', '20001', 'rwx'),
(4, 'PF2', '20002', 'rwx'),
(5, 'PF2', '20003', 'rwx'),
(6, 'PF2', '20004', 'rwx'),
(7, 'PF2', '20005', 'rwx'),
(8, 'PF2', '20006', 'rwx'),
(9, 'PF2', '20007', 'rwx'),
(10, 'PF2', '30001', 'rwx'),
(11, 'PF2', '30002', 'rwx'),
(12, 'PF2', '30003', 'rwx'),
(13, 'PF2', '30004', 'rwx'),
(14, 'PF2', '30005', 'rwx'),
(15, 'PF2', '30006', 'rwx'),
(16, 'PF2', '30007', 'rwx'),
(17, 'PF2', '40001', 'rwx'),
(18, 'PF2', '40002', 'rwx'),
(19, 'PF2', '40003', 'rwx'),
(20, 'PF2', '40004', 'rwx'),
(21, 'PF2', '40005', 'rwx'),
(22, 'PF2', '40006', 'rwx'),
(23, 'PF2', '40007', 'rwx'),
(24, 'PF2', '40008', 'rwx'),
(25, 'PF2', '40009', 'rwx'),
(26, 'PF2', '40010', 'rwx'),
(27, 'PF2', '80001', 'rwx'),
(28, 'PF2', '80002', 'rwx'),
(29, 'PF2', '80003', 'rwx'),
(30, 'PF2', '80004', 'rwx'),
(31, 'PF2', '80005', 'rwx'),
(32, 'PF1', '10001', 'rwx'),
(33, 'PF1', '10002', 'rwx'),
(34, 'PF1', '20001', 'rwx'),
(35, 'PF1', '20002', 'rwx'),
(36, 'PF1', '20003', 'rwx'),
(37, 'PF1', '20004', 'rwx'),
(38, 'PF1', '20005', 'rwx'),
(39, 'PF1', '20006', 'rwx'),
(40, 'PF1', '20007', 'rwx'),
(41, 'PF1', '30001', 'rwx'),
(42, 'PF1', '30002', 'rwx'),
(43, 'PF1', '30003', 'rwx'),
(44, 'PF1', '30004', 'rwx'),
(45, 'PF1', '30005', 'rwx'),
(46, 'PF1', '30006', 'rwx'),
(47, 'PF1', '30007', 'rwx'),
(48, 'PF1', '40001', 'rwx'),
(49, 'PF1', '40002', 'rwx'),
(50, 'PF1', '40003', 'rwx'),
(51, 'PF1', '40004', 'rwx'),
(52, 'PF1', '40005', 'rwx'),
(53, 'PF1', '40006', 'rwx'),
(54, 'PF1', '40007', 'rwx'),
(55, 'PF1', '40008', 'rwx'),
(56, 'PF1', '40009', 'rwx'),
(57, 'PF1', '40010', 'rwx'),
(58, 'PF1', '80001', 'rwx'),
(59, 'PF1', '80002', 'rwx'),
(60, 'PF1', '80003', 'rwx'),
(61, 'PF1', '80004', 'rwx'),
(62, 'PF1', '80005', 'rwx'),
(63, 'PF3', '10001', 'rwx'),
(64, 'PF3', '10002', 'rwx'),
(65, 'PF3', '20001', 'rwx'),
(66, 'PF3', '20002', 'rwx'),
(67, 'PF3', '20003', 'rwx'),
(68, 'PF3', '20004', 'rwx'),
(69, 'PF3', '20005', 'rwx'),
(70, 'PF3', '20006', 'rwx'),
(71, 'PF3', '20007', 'rwx'),
(72, 'PF3', '30001', 'rwx'),
(73, 'PF3', '30002', 'rwx'),
(74, 'PF3', '30003', 'rwx'),
(75, 'PF3', '30004', 'rwx'),
(76, 'PF3', '30005', 'rwx'),
(77, 'PF3', '30006', 'rwx'),
(78, 'PF3', '30007', 'rwx'),
(79, 'PF3', '40001', 'rwx'),
(80, 'PF3', '40002', 'rwx'),
(81, 'PF3', '40003', 'rwx'),
(82, 'PF3', '40004', 'rwx'),
(83, 'PF3', '40005', 'rwx'),
(84, 'PF3', '40006', 'rwx'),
(85, 'PF3', '40007', 'rwx'),
(86, 'PF3', '40008', 'rwx'),
(87, 'PF3', '40009', 'rwx'),
(88, 'PF3', '40010', 'rwx'),
(89, 'PF3', '80001', 'rwx'),
(90, 'PF3', '80002', 'rwx'),
(91, 'PF3', '80003', 'rwx'),
(92, 'PF3', '80004', 'rwx'),
(93, 'PF3', '80005', 'rwx'),
(94, 'PF4', '10001', 'rwx'),
(95, 'PF4', '10002', 'rwx'),
(96, 'PF4', '20001', 'rwx'),
(97, 'PF4', '20002', 'rwx'),
(98, 'PF4', '20003', 'rwx'),
(99, 'PF4', '20004', 'rwx'),
(100, 'PF4', '20005', 'rwx'),
(101, 'PF4', '20006', 'rwx'),
(102, 'PF4', '20007', 'rwx'),
(103, 'PF4', '30001', 'rwx'),
(104, 'PF4', '30002', 'rwx'),
(105, 'PF4', '30003', 'rwx'),
(106, 'PF4', '30004', 'rwx'),
(107, 'PF4', '30005', 'rwx'),
(108, 'PF4', '30006', 'rwx'),
(109, 'PF4', '30007', 'rwx'),
(110, 'PF4', '40001', 'rwx'),
(111, 'PF4', '40002', 'rwx'),
(112, 'PF4', '40003', 'rwx'),
(113, 'PF4', '40004', 'rwx'),
(114, 'PF4', '40005', 'rwx'),
(115, 'PF4', '40006', 'rwx'),
(116, 'PF4', '40007', 'rwx'),
(117, 'PF4', '40008', 'rwx'),
(118, 'PF4', '40009', 'rwx'),
(119, 'PF4', '40010', 'rwx'),
(120, 'PF4', '80001', 'rwx'),
(121, 'PF4', '80002', 'rwx'),
(122, 'PF4', '80003', 'rwx'),
(123, 'PF4', '80004', 'rwx'),
(124, 'PF4', '80005', 'rwx'),
(156, 'PF17', '10001', 'rwx'),
(157, 'PF17', '10002', 'rwx'),
(158, 'PF17', '20001', 'rwx'),
(159, 'PF17', '20002', 'rwx'),
(160, 'PF17', '20003', 'rwx'),
(161, 'PF17', '20004', 'rwx'),
(162, 'PF17', '20005', 'rwx'),
(163, 'PF17', '20006', 'rwx'),
(164, 'PF17', '20007', 'rwx'),
(165, 'PF17', '30001', 'rwx'),
(166, 'PF17', '30002', 'rwx'),
(167, 'PF17', '30003', 'rwx'),
(168, 'PF17', '30004', 'rwx'),
(169, 'PF17', '30005', 'rwx'),
(170, 'PF17', '30006', 'rwx'),
(171, 'PF17', '30007', 'rwx'),
(172, 'PF17', '40001', 'rwx'),
(173, 'PF17', '40002', 'rwx'),
(174, 'PF17', '40003', 'rwx'),
(175, 'PF17', '40004', 'rwx'),
(176, 'PF17', '40005', 'rwx'),
(177, 'PF17', '40006', 'rwx'),
(178, 'PF17', '40007', 'rwx'),
(179, 'PF17', '40008', 'rwx'),
(180, 'PF17', '40009', 'rwx'),
(181, 'PF17', '40010', 'rwx'),
(182, 'PF17', '80001', 'rwx'),
(183, 'PF17', '80002', 'rwx'),
(184, 'PF17', '80003', 'rwx'),
(185, 'PF17', '80004', 'rwx'),
(186, 'PF17', '80005', 'rwx'),
(187, 'PF18', '10001', 'rwx'),
(188, 'PF18', '10002', 'rwx'),
(189, 'PF18', '20001', 'rwx'),
(190, 'PF18', '20002', 'rwx'),
(191, 'PF18', '20003', 'rwx'),
(192, 'PF18', '20004', 'rwx'),
(193, 'PF18', '20005', 'rwx'),
(194, 'PF18', '20006', 'rwx'),
(195, 'PF18', '20007', 'rwx'),
(196, 'PF18', '30001', 'rwx'),
(197, 'PF18', '30002', 'rwx'),
(198, 'PF18', '30003', 'rwx'),
(199, 'PF18', '30004', 'rwx'),
(200, 'PF18', '30005', 'rwx'),
(201, 'PF18', '30006', 'rwx'),
(202, 'PF18', '30007', 'r--'),
(203, 'PF18', '40001', 'rwx'),
(204, 'PF18', '40002', 'rwx'),
(205, 'PF18', '40003', 'rwx'),
(206, 'PF18', '40004', 'rwx'),
(207, 'PF18', '40005', 'rwx'),
(208, 'PF18', '40006', 'rwx'),
(209, 'PF18', '40007', 'rwx'),
(210, 'PF18', '40008', 'rwx'),
(211, 'PF18', '40009', 'rwx'),
(212, 'PF18', '40010', 'rwx'),
(213, 'PF18', '80001', 'rwx'),
(214, 'PF18', '80002', 'rwx'),
(215, 'PF18', '80003', 'rwx'),
(216, 'PF18', '80004', 'rwx'),
(217, 'PF18', '80005', 'rwx'),
(218, 'PF18', '10001', 'rwx'),
(219, 'PF18', '10002', 'rwx'),
(220, 'PF18', '20001', 'rwx'),
(221, 'PF18', '20002', 'rwx'),
(222, 'PF18', '20003', 'rwx'),
(223, 'PF18', '20004', 'rwx'),
(224, 'PF18', '20005', 'rwx'),
(225, 'PF18', '20006', 'rwx'),
(226, 'PF18', '20007', 'rwx'),
(227, 'PF18', '30001', 'rwx'),
(228, 'PF18', '30002', 'rwx'),
(229, 'PF18', '30003', 'rwx'),
(230, 'PF18', '30004', 'rwx'),
(231, 'PF18', '30005', 'rwx'),
(232, 'PF18', '30006', 'rwx'),
(233, 'PF18', '30007', 'r--'),
(234, 'PF18', '40001', 'rwx'),
(235, 'PF18', '40002', 'rwx'),
(236, 'PF18', '40003', 'rwx'),
(237, 'PF18', '40004', 'rwx'),
(238, 'PF18', '40005', 'rwx'),
(239, 'PF18', '40006', 'rwx'),
(240, 'PF18', '40007', 'rwx'),
(241, 'PF18', '40008', 'rwx'),
(242, 'PF18', '40009', 'rwx'),
(243, 'PF18', '40010', 'rwx'),
(244, 'PF18', '80001', 'rwx'),
(245, 'PF18', '80002', 'rwx'),
(246, 'PF18', '80003', 'rwx'),
(247, 'PF18', '80004', 'rwx'),
(248, 'PF18', '80005', 'rwx'),
(249, 'PF19', '10001', 'r--'),
(250, 'PF19', '10002', 'r--'),
(251, 'PF19', '20001', 'r--'),
(252, 'PF19', '20002', 'r--'),
(253, 'PF19', '20003', 'r--'),
(254, 'PF19', '20004', 'r--'),
(255, 'PF19', '20005', 'r--'),
(256, 'PF19', '20006', 'r--'),
(257, 'PF19', '20007', 'r--'),
(258, 'PF19', '30001', 'r--'),
(259, 'PF19', '30002', 'r--'),
(260, 'PF19', '30003', 'r--'),
(261, 'PF19', '30004', 'r--'),
(262, 'PF19', '30005', 'r--'),
(263, 'PF19', '30006', 'r--'),
(264, 'PF19', '30007', 'r--'),
(265, 'PF19', '40001', 'r--'),
(266, 'PF19', '40002', 'r--'),
(267, 'PF19', '40003', 'r--'),
(268, 'PF19', '40004', 'r--'),
(269, 'PF19', '40005', 'r--'),
(270, 'PF19', '40006', 'r--'),
(271, 'PF19', '40007', 'r--'),
(272, 'PF19', '40008', 'r--'),
(273, 'PF19', '40009', 'r--'),
(274, 'PF19', '40010', 'r--'),
(275, 'PF19', '80001', 'r--'),
(276, 'PF19', '80002', 'r--'),
(277, 'PF19', '80003', 'r--'),
(278, 'PF19', '80004', 'r--'),
(279, 'PF19', '80005', 'r--'),
(280, 'PF22', '10001', 'r--'),
(281, 'PF22', '10002', 'r--'),
(282, 'PF22', '20001', 'r--'),
(283, 'PF22', '20002', 'r--'),
(284, 'PF22', '20003', 'r--'),
(285, 'PF22', '20004', 'rwx'),
(286, 'PF22', '20005', 'r--'),
(287, 'PF22', '20006', 'r--'),
(288, 'PF22', '20007', 'r--'),
(289, 'PF22', '30001', 'r--'),
(290, 'PF22', '30002', 'r--'),
(291, 'PF22', '30003', 'r--'),
(292, 'PF22', '30004', 'r--'),
(293, 'PF22', '30005', 'r--'),
(294, 'PF22', '30006', 'r--'),
(295, 'PF22', '30007', 'r--'),
(296, 'PF22', '40001', 'r--'),
(297, 'PF22', '40002', 'r--'),
(298, 'PF22', '40003', 'r--'),
(299, 'PF22', '40004', 'r--'),
(300, 'PF22', '40005', 'r--'),
(301, 'PF22', '40006', 'r--'),
(302, 'PF22', '40007', 'r--'),
(303, 'PF22', '40008', 'r--'),
(304, 'PF22', '40009', 'r--'),
(305, 'PF22', '40010', 'r--'),
(306, 'PF22', '80001', 'r--'),
(307, 'PF22', '80002', 'r--'),
(308, 'PF22', '80003', 'r--'),
(309, 'PF22', '80004', 'r--'),
(310, 'PF22', '80005', 'r--'),
(311, 'PF23', '10001', 'r--'),
(312, 'PF23', '10002', 'r--'),
(313, 'PF23', '20001', 'rwx'),
(314, 'PF23', '20002', 'rwx'),
(315, 'PF23', '20003', 'r--'),
(316, 'PF23', '20004', 'rw-'),
(317, 'PF23', '20005', 'rw-'),
(318, 'PF23', '20006', 'rw-'),
(319, 'PF23', '20007', 'rwx'),
(320, 'PF23', '30001', 'r--'),
(321, 'PF23', '30002', 'r--'),
(322, 'PF23', '30003', 'r--'),
(323, 'PF23', '30004', 'r--'),
(324, 'PF23', '30005', 'r--'),
(325, 'PF23', '30006', 'r--'),
(326, 'PF23', '30007', 'r--'),
(327, 'PF23', '40001', 'rwx'),
(328, 'PF23', '40002', 'rwx'),
(329, 'PF23', '40003', 'rwx'),
(330, 'PF23', '40004', 'rwx'),
(331, 'PF23', '40005', 'rwx'),
(332, 'PF23', '40006', 'rwx'),
(333, 'PF23', '40007', 'rwx'),
(334, 'PF23', '40008', 'rwx'),
(335, 'PF23', '40009', 'rwx'),
(336, 'PF23', '40010', 'rwx'),
(337, 'PF23', '80001', 'r--'),
(338, 'PF23', '80002', 'r--'),
(339, 'PF23', '80003', 'r--'),
(340, 'PF23', '80004', 'r--'),
(341, 'PF23', '80005', 'rwx'),
(373, 'PF25', '10001', 'r--'),
(374, 'PF25', '10002', 'r--'),
(375, 'PF25', '20001', 'r--'),
(376, 'PF25', '20002', 'r--'),
(377, 'PF25', '20003', 'r--'),
(378, 'PF25', '20004', 'rwx'),
(379, 'PF25', '20005', 'r--'),
(380, 'PF25', '20006', 'r--'),
(381, 'PF25', '20007', 'r--'),
(382, 'PF25', '30001', 'r--'),
(383, 'PF25', '30002', 'r--'),
(384, 'PF25', '30003', 'r--'),
(385, 'PF25', '30004', 'r--'),
(386, 'PF25', '30005', 'r--'),
(387, 'PF25', '30006', 'r--'),
(388, 'PF25', '30007', 'r--'),
(389, 'PF25', '40001', 'r--'),
(390, 'PF25', '40002', 'r--'),
(391, 'PF25', '40003', 'r--'),
(392, 'PF25', '40004', 'r--'),
(393, 'PF25', '40005', 'r--'),
(394, 'PF25', '40006', 'r--'),
(395, 'PF25', '40007', 'r--'),
(396, 'PF25', '40008', 'r--'),
(397, 'PF25', '40009', 'r--'),
(398, 'PF25', '40010', 'r--'),
(399, 'PF25', '80001', 'r--'),
(400, 'PF25', '80002', 'r--'),
(401, 'PF25', '80003', 'r--'),
(402, 'PF25', '80004', 'r--'),
(403, 'PF25', '80005', 'r--'),
(404, 'PF24', '10001', 'r--'),
(405, 'PF24', '10002', 'r--'),
(406, 'PF24', '20001', 'r--'),
(407, 'PF24', '20002', 'r--'),
(408, 'PF24', '20003', 'r--'),
(409, 'PF24', '20004', 'r--'),
(410, 'PF24', '20005', 'r--'),
(411, 'PF24', '20006', 'r--'),
(412, 'PF24', '20007', 'r--'),
(413, 'PF24', '30001', 'r--'),
(414, 'PF24', '30002', 'r--'),
(415, 'PF24', '30003', 'r--'),
(416, 'PF24', '30004', 'r--'),
(417, 'PF24', '30005', 'r--'),
(418, 'PF24', '30006', 'r--'),
(419, 'PF24', '30007', 'r--'),
(420, 'PF24', '40001', 'r--'),
(421, 'PF24', '40002', 'r--'),
(422, 'PF24', '40003', 'r--'),
(423, 'PF24', '40004', 'r--'),
(424, 'PF24', '40005', 'r--'),
(425, 'PF24', '40006', 'r--'),
(426, 'PF24', '40007', 'r--'),
(427, 'PF24', '40008', 'r--'),
(428, 'PF24', '40009', 'r--'),
(429, 'PF24', '40010', 'r--'),
(430, 'PF24', '80001', 'r--'),
(431, 'PF24', '80002', 'r--'),
(432, 'PF24', '80003', 'r--'),
(433, 'PF24', '80004', 'r--'),
(434, 'PF24', '80005', 'r--'),
(435, 'PF26', '10001', 'r--'),
(436, 'PF26', '10002', 'r--'),
(437, 'PF26', '20001', 'rwx'),
(438, 'PF26', '20002', 'rwx'),
(439, 'PF26', '20003', 'r--'),
(440, 'PF26', '20004', 'r--'),
(441, 'PF26', '20005', 'r--'),
(442, 'PF26', '20006', 'r--'),
(443, 'PF26', '20007', 'r--'),
(444, 'PF26', '30001', 'r--'),
(445, 'PF26', '30002', 'r--'),
(446, 'PF26', '30003', 'r--'),
(447, 'PF26', '30004', 'r--'),
(448, 'PF26', '30005', 'r--'),
(449, 'PF26', '30006', 'r--'),
(450, 'PF26', '30007', 'r--'),
(451, 'PF26', '40001', 'r--'),
(452, 'PF26', '40002', 'r--'),
(453, 'PF26', '40003', 'r--'),
(454, 'PF26', '40004', 'r--'),
(455, 'PF26', '40005', 'r--'),
(456, 'PF26', '40006', 'r--'),
(457, 'PF26', '40007', 'r--'),
(458, 'PF26', '40008', 'r--'),
(459, 'PF26', '40009', 'r--'),
(460, 'PF26', '40010', 'r--'),
(461, 'PF26', '80001', 'r--'),
(462, 'PF26', '80002', 'rwx'),
(463, 'PF26', '80003', 'r--'),
(464, 'PF26', '80004', 'r--'),
(465, 'PF26', '80005', 'r--'),
(466, 'PF27', '10001', 'rwx'),
(467, 'PF27', '10002', 'rwx'),
(468, 'PF27', '20001', 'rwx'),
(469, 'PF27', '20002', 'rwx'),
(470, 'PF27', '20003', 'rwx'),
(471, 'PF27', '20004', 'rwx'),
(472, 'PF27', '20005', 'rwx'),
(473, 'PF27', '20006', 'rwx'),
(474, 'PF27', '20007', 'rwx'),
(475, 'PF27', '30001', 'rwx'),
(476, 'PF27', '30002', 'rwx'),
(477, 'PF27', '30003', 'rwx'),
(478, 'PF27', '30004', 'rwx'),
(479, 'PF27', '30005', 'rwx'),
(480, 'PF27', '30006', 'rwx'),
(481, 'PF27', '30007', 'rwx'),
(482, 'PF27', '40001', 'rwx'),
(483, 'PF27', '40002', 'rwx'),
(484, 'PF27', '40003', 'rwx'),
(485, 'PF27', '40004', 'rwx'),
(486, 'PF27', '40005', 'rwx'),
(487, 'PF27', '40006', 'rwx'),
(488, 'PF27', '40007', 'rwx'),
(489, 'PF27', '40008', 'rwx'),
(490, 'PF27', '40009', 'rwx'),
(491, 'PF27', '40010', 'rwx'),
(492, 'PF27', '80001', 'r--'),
(493, 'PF27', '80002', 'r--'),
(494, 'PF27', '80003', 'r--'),
(495, 'PF27', '80004', 'r--'),
(496, 'PF27', '80005', 'r--');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `am_proveedores`
--

CREATE TABLE `am_proveedores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) CHARACTER SET latin1 NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1',
  `rfc` varchar(30) CHARACTER SET latin1 NOT NULL,
  `fechaAlta` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anillox`
--

CREATE TABLE `anillox` (
  `id` int(11) NOT NULL,
  `identificadorAnillox` varchar(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `num_lineas` int(11) NOT NULL,
  `bcm` int(11) NOT NULL,
  `proceso` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `anillox`
--

INSERT INTO `anillox` (`id`, `identificadorAnillox`, `num_lineas`, `bcm`, `proceso`, `baja`) VALUES
(5, 'AN560', 30, 234, 'impresion-flexografica', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `baja_BS`
--

CREATE TABLE `baja_BS` (
  `ID` int(11) NOT NULL,
  `codigo` varchar(40) CHARACTER SET latin1 NOT NULL,
  `tipo` varchar(40) CHARACTER SET latin1 NOT NULL,
  `longitud` double NOT NULL,
  `producto` varchar(70) CHARACTER SET latin1 NOT NULL,
  `unidades` double NOT NULL,
  `proceso` varchar(70) CHARACTER SET latin1 NOT NULL,
  `empleado` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bandaseguridad`
--

CREATE TABLE `bandaseguridad` (
  `IDBanda` int(11) NOT NULL,
  `identificador` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nombreBanda` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `anchura` int(11) NOT NULL,
  `baja` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `bandaseguridad`
--

INSERT INTO `bandaseguridad` (`IDBanda`, `identificador`, `nombreBanda`, `anchura`, `baja`) VALUES
(1, 'electroPura', 'BS electroPura', 10, 1),
(2, 'ePura', 'BS ePura', 12, 1),
(3, 'Cristal Gota', 'BS Cristal Gota', 12, 1),
(4, 'Santorini', 'BS Santorini', 12, 1),
(5, 'coffe01', 'bandacseguridadcofee', 15, 1),
(6, '01BandaValle', 'BandaSegValle', 12, 1),
(7, 'Generico Gepp', 'BS Generico Gepp', 12, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bandaspp`
--

CREATE TABLE `bandaspp` (
  `IdBSPP` int(11) NOT NULL,
  `identificadorBS` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `identificadorBSPP` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nombreBSPP` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `anchuraLaminado` float NOT NULL,
  `sustrato` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `preEmbosado` int(11) NOT NULL DEFAULT '0',
  `baja` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `bandaspp`
--

INSERT INTO `bandaspp` (`IdBSPP`, `identificadorBS`, `identificadorBSPP`, `nombreBSPP`, `anchuraLaminado`, `sustrato`, `preEmbosado`, `baja`) VALUES
(1, 'BS electroPura', 'Electropura', 'Electropura Pre-Embozado640', 160, 'Polyester embosado C20 electropura 640mm', 0, 1),
(2, 'BS ePura', 'Epura Pre-Embozado1', 'Epura Pre-Embozado640', 160, 'Polyester embosado C20 e-pura 640mm', 0, 1),
(3, 'BS ePura', 'ePura.PreE', 'Epura Pre-Embozado320', 160, 'Polyester embosado C20 e-pura 320mm', 0, 0),
(4, 'BS Cristal Gota', 'Gota PreEmbozado4', 'Gota Pre-Embozado320', 160, 'Polyester embosado C30 gota 320 mm', 0, 1),
(5, 'BS Santorini', 'Santorini3', 'Santorini Pre- Embozado320', 160, 'Polyester embosado C20 santorini 320mm', 0, 1),
(6, 'bandacseguridadcofee', '01 01 bandaseg coffe', 'banda seguridad coffe ', 16, 'Polyester embosado C30 gota 320 mm', 0, 1),
(7, 'BandaSegValle', '01234', 'BandaSegValle', 160, 'Polyester embosado C30 gota 320 mm', 0, 1),
(8, 'BS Generico Gepp', 'Generico Gepp', 'Generico Gepp Embozado170', 160, 'Pet metalizado 12 mic 400 mm', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bloquesmateriaprima`
--

CREATE TABLE `bloquesmateriaprima` (
  `idBloque` int(11) NOT NULL,
  `identificadorBloque` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nombreBloque` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sustrato` varchar(70) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `peso` float NOT NULL,
  `longitud` double NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `bloquesmateriaprima`
--

INSERT INTO `bloquesmateriaprima` (`idBloque`, `identificadorBloque`, `nombreBloque`, `sustrato`, `peso`, `longitud`, `baja`) VALUES
(1, 'PVC 450 mm IMPORTADO', 'PVC IMPORTADO', 'PVC termoencogible C40 E50/0 450mm', 911, 15720, 1),
(2, 'OC00005.17  320 mm', 'O.C. 00005.17 320', 'Polyester embosado C20 e-pura 320mm', 484, 69700, 1),
(3, 'PVC 400 mm IMPORTADO', 'PVC 400 mm CHINO', 'PVC termoencogible C40 E50/0 400mm', 340, 37332, 1),
(4, 'OC00006.17 455 mm', 'O.C. 00006.17 455 mm', 'PTG transparente C37 455 mm', 408, 13200, 1),
(5, 'OC 00017.320', 'O.C. 00017 320 mm', 'Polyester embosado C30 gota 320 mm', 172, 24000, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `id` int(11) NOT NULL,
  `dato` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `identifier` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cache`
--

INSERT INTO `cache` (`id`, `dato`, `identifier`) VALUES
(1, 'Comex', 1),
(2, 'Thinner estandar 960ml', 1),
(3, 'Thinner estandar 960ml', 1),
(4, 'BS Generico Gepp', 1),
(5, 'Bopp blanco 35 micras', 1),
(6, '030816-14.600.35-03 | 38', 1),
(7, 'impresion', 1),
(8, 'caja', 1),
(9, 'Termoencogible', 1),
(30, '1040.000', 1),
(31, 'Thinner estandar 960ml', 1),
(32, '5', 1),
(40, 'BS', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE `caja` (
  `id` int(11) NOT NULL,
  `referencia` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `producto` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `noElementos` int(11) NOT NULL,
  `piezas` decimal(9,3) NOT NULL,
  `codigo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `cdgEmbarque` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `peso` float NOT NULL,
  `cdgDev` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudad`
--

CREATE TABLE `ciudad` (
  `id` int(11) NOT NULL,
  `nombreCity` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `estado` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `ciudad`
--

INSERT INTO `ciudad` (`id`, `nombreCity`, `estado`, `baja`) VALUES
(1, 'MÃƒÂ©rida', '33', 1),
(2, 'Villahermosa', '29', 1),
(3, 'Leon', '13', 1),
(4, 'Armenia', '37', 1),
(5, 'Buenavista', '37', 1),
(6, 'GÃ©nova', '37', 1),
(7, 'Tlalnepantla', '17', 1),
(8, 'Queretaro', '25', 1),
(9, 'Silao', '13', 1),
(10, 'Salamanca', '13', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clasificacion`
--

CREATE TABLE `clasificacion` (
  `id` int(11) NOT NULL,
  `identificador` varchar(6) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `clasificacion`
--

INSERT INTO `clasificacion` (`id`, `identificador`, `nombre`, `baja`) VALUES
(1, 'ADH', 'Adhesivos', 1),
(2, 'COM', 'Componentes para tintas', 1),
(3, 'DIL', 'Diluyentes', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigosbarras`
--

CREATE TABLE `codigosbarras` (
  `id` int(11) NOT NULL,
  `codigo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `producto` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `proceso` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `lote` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `noProceso` int(20) NOT NULL,
  `noop` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1',
  `divisiones` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `codigosbarras`
--

INSERT INTO `codigosbarras` (`id`, `codigo`, `producto`, `proceso`, `lote`, `noProceso`, `noop`, `tipo`, `baja`, `divisiones`) VALUES
(1, '00000022', 'Thinner estandar 960ml', 'programado', '03081614-34', 0, '13', 'Etiqueta abierta', 1, 1),
(2, '00000023', 'Thinner estandar 960ml', 'programado', '03081614-35', 0, '14', 'Etiqueta abierta', 1, 1),
(3, '00000024', 'Thinner estandar 960ml', 'programado', '03081614-36', 0, '15', 'Etiqueta abierta', 1, 1),
(4, '00000025', 'Thinner estandar 960ml', 'programado', '03081614-55', 0, '16', 'Etiqueta abierta', 1, 1),
(5, '00000026', 'Thinner estandar 960ml', 'programado', '03081614-56', 0, '17', 'Etiqueta abierta', 1, 1),
(6, '00000027', 'Thinner estandar 960ml', 'programado', '03081614-57', 0, '18', 'Etiqueta abierta', 1, 1),
(7, '00000028', 'Thinner estandar 960ml', 'programado', '03081614-58', 0, '19', 'Etiqueta abierta', 1, 1),
(8, '00000029', 'Thinner estandar 960ml', 'programado', '03081614-59', 0, '20', 'Etiqueta abierta', 1, 1),
(9, '00000030', 'Thinner estandar 960ml', 'programado', '03081614-60', 0, '21', 'Etiqueta abierta', 1, 1),
(10, '00000031', 'Thinner estandar 960ml', 'programado', '03081614-61', 0, '22', 'Etiqueta abierta', 1, 1),
(11, '00000032', 'Thinner estandar 960ml', 'programado', '03081614-62', 0, '23', 'Etiqueta abierta', 1, 1),
(12, '00000033', 'Thinner estandar 960ml', 'programado', '03081614-63', 0, '24', 'Etiqueta abierta', 1, 1),
(13, '00000122', 'Thinner estandar 960ml', 'impresion', '03081614-34', 1, '13', 'Etiqueta abierta', 1, 1),
(14, '00000123', 'Thinner estandar 960ml', 'impresion', '03081614-35', 1, '14', 'Etiqueta abierta', 1, 1),
(15, '00000124', 'Thinner estandar 960ml', 'impresion', '03081614-36', 1, '15', 'Etiqueta abierta', 1, 1),
(16, '00000125', 'Thinner estandar 960ml', 'impresion', '03081614-55', 1, '16', 'Etiqueta abierta', 1, 1),
(17, '00000126', 'Thinner estandar 960ml', 'impresion', '03081614-56', 1, '17', 'Etiqueta abierta', 1, 1),
(18, '00000127', 'Thinner estandar 960ml', 'impresion', '03081614-57', 1, '18', 'Etiqueta abierta', 1, 1),
(19, '00000128', 'Thinner estandar 960ml', 'impresion', '03081614-58', 1, '19', 'Etiqueta abierta', 1, 1),
(20, '00000129', 'Thinner estandar 960ml', 'impresion', '03081614-59', 1, '20', 'Etiqueta abierta', 1, 1),
(21, '00000130', 'Thinner estandar 960ml', 'impresion', '03081614-60', 1, '21', 'Etiqueta abierta', 1, 1),
(22, '00000131', 'Thinner estandar 960ml', 'impresion', '03081614-61', 1, '22', 'Etiqueta abierta', 1, 1),
(23, '00000132', 'Thinner estandar 960ml', 'impresion', '03081614-62', 1, '23', 'Etiqueta abierta', 1, 1),
(24, '00000133', 'Thinner estandar 960ml', 'impresion', '03081614-63', 1, '24', 'Etiqueta abierta', 1, 1),
(25, '00000222', 'Thinner estandar 960ml', 'revision', '03081614-34', 2, '13', 'Etiqueta abierta', 1, 1),
(26, '00000224', 'Thinner estandar 960ml', 'revision', '03081614-36', 2, '15', 'Etiqueta abierta', 1, 1),
(27, '00000225', 'Thinner estandar 960ml', 'revision', '03081614-55', 2, '16', 'Etiqueta abierta', 1, 1),
(28, '00000034', 'Thinner estandar 960ml', 'programado', '03081614-64', 0, '25', 'Etiqueta abierta', 1, 1),
(29, '00000035', 'Thinner estandar 960ml', 'programado', '03081614-65', 0, '26', 'Etiqueta abierta', 1, 1),
(30, '00000036', 'Thinner estandar 960ml', 'programado', '03081614-66', 0, '27', 'Etiqueta abierta', 1, 1),
(31, '00000037', 'Thinner estandar 960ml', 'programado', '03081614-67', 0, '28', 'Etiqueta abierta', 1, 1),
(32, '00000038', 'Thinner estandar 960ml', 'programado', '03081614-68', 0, '29', 'Etiqueta abierta', 1, 1),
(33, '00000039', 'Thinner estandar 960ml', 'programado', '03081614-69', 0, '30', 'Etiqueta abierta', 1, 1),
(34, '00000040', 'Thinner estandar 960ml', 'programado', '03081614-70', 0, '31', 'Etiqueta abierta', 1, 1),
(35, '00000041', 'Thinner estandar 960ml', 'programado', '03081614-71', 0, '32', 'Etiqueta abierta', 1, 1),
(36, '00000042', 'Thinner estandar 960ml', 'programado', '03081614-72', 0, '33', 'Etiqueta abierta', 1, 1),
(37, '00000043', 'Thinner estandar 960ml', 'programado', '03081614-37', 0, '34', 'Etiqueta abierta', 1, 1),
(38, '00000226', 'Thinner estandar 960ml', 'revision', '03081614-56', 2, '17', 'Etiqueta abierta', 1, 1),
(39, '00000227', 'Thinner estandar 960ml', 'revision', '03081614-57', 2, '18', 'Etiqueta abierta', 1, 1),
(40, '00000229', 'Thinner estandar 960ml', 'revision', '03081614-59', 2, '20', 'Etiqueta abierta', 1, 1),
(41, '00000231', 'Thinner estandar 960ml', 'revision', '03081614-61', 2, '22', 'Etiqueta abierta', 1, 1),
(42, '00000232', 'Thinner estandar 960ml', 'revision', '03081614-62', 2, '23', 'Etiqueta abierta', 1, 1),
(44, '00000322', 'Thinner estandar 960ml', 'laminado', '03081614-34', 3, '13', 'Etiqueta abierta', 1, 4),
(46, '00000324', 'Thinner estandar 960ml', 'laminado', '03081614-36', 3, '15', 'Etiqueta abierta', 1, 4),
(47, '00000331', 'Thinner estandar 960ml', 'laminado', '03081614-61', 3, '22', 'Etiqueta abierta', 1, 4),
(48, '00000326', 'Thinner estandar 960ml', 'laminado', '03081614-56', 3, '17', 'Etiqueta abierta', 1, 4),
(49, '00000223', 'Thinner estandar 960ml', 'revision', '03081614-35', 2, '14', 'Etiqueta abierta', 1, 1),
(51, '00000329', 'Thinner estandar 960ml', 'laminado', '03081614-59', 3, '20', 'Etiqueta abierta', 1, 4),
(52, '00040424', 'Thinner estandar 960ml', 'refilado', '03081614-36', 4, '15-4', 'Etiqueta abierta', 1, 1),
(53, '00030424', 'Thinner estandar 960ml', 'refilado', '03081614-36', 4, '15-3', 'Etiqueta abierta', 1, 1),
(54, '00020424', 'Thinner estandar 960ml', 'refilado', '03081614-36', 4, '15-2', 'Etiqueta abierta', 1, 1),
(55, '00010424', 'Thinner estandar 960ml', 'refilado', '03081614-36', 4, '15-1', 'Etiqueta abierta', 1, 1),
(56, '00040429', 'Thinner estandar 960ml', 'refilado', '03081614-59', 4, '20-4', 'Etiqueta abierta', 1, 1),
(57, '00030429', 'Thinner estandar 960ml', 'refilado', '03081614-59', 4, '20-3', 'Etiqueta abierta', 1, 1),
(58, '00020429', 'Thinner estandar 960ml', 'refilado', '03081614-59', 4, '20-2', 'Etiqueta abierta', 1, 1),
(59, '00010429', 'Thinner estandar 960ml', 'refilado', '03081614-59', 4, '20-1', 'Etiqueta abierta', 1, 1),
(60, '00000323', 'Thinner estandar 960ml', 'laminado', '03081614-35', 3, '14', 'Etiqueta abierta', 1, 4),
(61, '00040423', 'Thinner estandar 960ml', 'refilado', '03081614-35', 4, '14-4', 'Etiqueta abierta', 1, 1),
(62, '00030423', 'Thinner estandar 960ml', 'refilado', '03081614-35', 4, '14-3', 'Etiqueta abierta', 1, 1),
(63, '00020423', 'Thinner estandar 960ml', 'refilado', '03081614-35', 4, '14-2', 'Etiqueta abierta', 1, 1),
(64, '00010423', 'Thinner estandar 960ml', 'refilado', '03081614-35', 4, '14-1', 'Etiqueta abierta', 1, 1),
(65, '00000143', 'Thinner estandar 960ml', 'impresion', '03081614-37', 1, '34', 'Etiqueta abierta', 1, 1),
(66, '00000243', 'Thinner estandar 960ml', 'revision', '03081614-37', 2, '34', 'Etiqueta abierta', 1, 1),
(67, '00000343', 'Thinner estandar 960ml', 'laminado', '03081614-37', 3, '34', 'Etiqueta abierta', 1, 4),
(68, '00040443', 'Thinner estandar 960ml', 'refilado', '03081614-37', 4, '34-4', 'Etiqueta abierta', 1, 1),
(69, '00030443', 'Thinner estandar 960ml', 'refilado', '03081614-37', 4, '34-3', 'Etiqueta abierta', 1, 1),
(70, '00020443', 'Thinner estandar 960ml', 'refilado', '03081614-37', 4, '34-2', 'Etiqueta abierta', 1, 1),
(71, '00010443', 'Thinner estandar 960ml', 'refilado', '03081614-37', 4, '34-1', 'Etiqueta abierta', 1, 1),
(72, '00040422', 'Thinner estandar 960ml', 'refilado', '03081614-34', 4, '13-4', 'Etiqueta abierta', 1, 1),
(73, '00030422', 'Thinner estandar 960ml', 'refilado', '03081614-34', 4, '13-3', 'Etiqueta abierta', 1, 1),
(74, '00020422', 'Thinner estandar 960ml', 'refilado', '03081614-34', 4, '13-2', 'Etiqueta abierta', 0, 1),
(75, '00010422', 'Thinner estandar 960ml', 'refilado', '03081614-34', 4, '13-1', 'Etiqueta abierta', 0, 1),
(76, '00000325', 'Thinner estandar 960ml', 'laminado', '03081614-55', 3, '16', 'Etiqueta abierta', 1, 4),
(77, '00040425', 'Thinner estandar 960ml', 'refilado', '03081614-55', 4, '16-4', 'Etiqueta abierta', 1, 1),
(78, '00030425', 'Thinner estandar 960ml', 'refilado', '03081614-55', 4, '16-3', 'Etiqueta abierta', 1, 1),
(79, '00020425', 'Thinner estandar 960ml', 'refilado', '03081614-55', 4, '16-2', 'Etiqueta abierta', 1, 1),
(80, '00010425', 'Thinner estandar 960ml', 'refilado', '03081614-55', 4, '16-1', 'Etiqueta abierta', 1, 1),
(81, '00000142', 'Thinner estandar 960ml', 'impresion', '03081614-72', 1, '33', 'Etiqueta abierta', 1, 1),
(82, '00000242', 'Thinner estandar 960ml', 'revision', '03081614-72', 2, '33', 'Etiqueta abierta', 1, 1),
(83, '00000342', 'Thinner estandar 960ml', 'laminado', '03081614-72', 3, '33', 'Etiqueta abierta', 1, 4),
(84, '00040442', 'Thinner estandar 960ml', 'refilado', '03081614-72', 4, '33-4', 'Etiqueta abierta', 1, 1),
(85, '00030442', 'Thinner estandar 960ml', 'refilado', '03081614-72', 4, '33-3', 'Etiqueta abierta', 1, 1),
(86, '00020442', 'Thinner estandar 960ml', 'refilado', '03081614-72', 4, '33-2', 'Etiqueta abierta', 1, 1),
(87, '00010442', 'Thinner estandar 960ml', 'refilado', '03081614-72', 4, '33-1', 'Etiqueta abierta', 1, 1),
(88, '00000228', 'Thinner estandar 960ml', 'revision', '03081614-58', 2, '19', 'Etiqueta abierta', 1, 1),
(89, '00000328', 'Thinner estandar 960ml', 'laminado', '03081614-58', 3, '19', 'Etiqueta abierta', 1, 4),
(90, '00040428', 'Thinner estandar 960ml', 'refilado', '03081614-58', 4, '19-4', 'Etiqueta abierta', 1, 1),
(91, '00030428', 'Thinner estandar 960ml', 'refilado', '03081614-58', 4, '19-3', 'Etiqueta abierta', 1, 1),
(92, '00020428', 'Thinner estandar 960ml', 'refilado', '03081614-58', 4, '19-2', 'Etiqueta abierta', 1, 1),
(93, '00010428', 'Thinner estandar 960ml', 'refilado', '03081614-58', 4, '19-1', 'Etiqueta abierta', 1, 1),
(94, '00040426', 'Thinner estandar 960ml', 'refilado', '03081614-56', 4, '17-4', 'Etiqueta abierta', 1, 1),
(95, '00030426', 'Thinner estandar 960ml', 'refilado', '03081614-56', 4, '17-3', 'Etiqueta abierta', 1, 1),
(96, '00020426', 'Thinner estandar 960ml', 'refilado', '03081614-56', 4, '17-2', 'Etiqueta abierta', 1, 1),
(97, '00010426', 'Thinner estandar 960ml', 'refilado', '03081614-56', 4, '17-1', 'Etiqueta abierta', 1, 1),
(98, '00000327', 'Thinner estandar 960ml', 'laminado', '03081614-57', 3, '18', 'Etiqueta abierta', 1, 4),
(99, '00040427', 'Thinner estandar 960ml', 'refilado', '03081614-57', 4, '18-4', 'Etiqueta abierta', 1, 1),
(100, '00030427', 'Thinner estandar 960ml', 'refilado', '03081614-57', 4, '18-3', 'Etiqueta abierta', 1, 1),
(101, '00020427', 'Thinner estandar 960ml', 'refilado', '03081614-57', 4, '18-2', 'Etiqueta abierta', 1, 1),
(102, '00010427', 'Thinner estandar 960ml', 'refilado', '03081614-57', 4, '18-1', 'Etiqueta abierta', 1, 1),
(103, '00040431', 'Thinner estandar 960ml', 'refilado', '03081614-61', 4, '22-4', 'Etiqueta abierta', 1, 1),
(104, '00030431', 'Thinner estandar 960ml', 'refilado', '03081614-61', 4, '22-3', 'Etiqueta abierta', 1, 1),
(105, '00020431', 'Thinner estandar 960ml', 'refilado', '03081614-61', 4, '22-2', 'Etiqueta abierta', 1, 1),
(106, '00010431', 'Thinner estandar 960ml', 'refilado', '03081614-61', 4, '22-1', 'Etiqueta abierta', 1, 1),
(107, '00000332', 'Thinner estandar 960ml', 'laminado', '03081614-62', 3, '23', 'Etiqueta abierta', 1, 4),
(108, '00040432', 'Thinner estandar 960ml', 'refilado', '03081614-62', 4, '23-4', 'Etiqueta abierta', 1, 1),
(109, '00030432', 'Thinner estandar 960ml', 'refilado', '03081614-62', 4, '23-3', 'Etiqueta abierta', 1, 1),
(110, '00020432', 'Thinner estandar 960ml', 'refilado', '03081614-62', 4, '23-2', 'Etiqueta abierta', 1, 1),
(111, '00010432', 'Thinner estandar 960ml', 'refilado', '03081614-62', 4, '23-1', 'Etiqueta abierta', 1, 1),
(112, '00000140', 'Thinner estandar 960ml', 'impresion', '03081614-70', 1, '31', 'Etiqueta abierta', 1, 1),
(113, '00000240', 'Thinner estandar 960ml', 'revision', '03081614-70', 2, '31', 'Etiqueta abierta', 1, 1),
(114, '00000340', 'Thinner estandar 960ml', 'laminado', '03081614-70', 3, '31', 'Etiqueta abierta', 1, 4),
(115, '00040440', 'Thinner estandar 960ml', 'refilado', '03081614-70', 4, '31-4', 'Etiqueta abierta', 1, 1),
(116, '00030440', 'Thinner estandar 960ml', 'refilado', '03081614-70', 4, '31-3', 'Etiqueta abierta', 1, 1),
(117, '00020440', 'Thinner estandar 960ml', 'refilado', '03081614-70', 4, '31-2', 'Etiqueta abierta', 1, 1),
(118, '00010440', 'Thinner estandar 960ml', 'refilado', '03081614-70', 4, '31-1', 'Etiqueta abierta', 1, 1),
(119, '0053870001', '', '', '', 0, '', '', 1, 1),
(120, '00000230', 'Thinner estandar 960ml', 'revision', '03081614-60', 2, '21', 'Etiqueta abierta', 1, 1),
(121, '00000330', 'Thinner estandar 960ml', 'laminado', '03081614-60', 3, '21', 'Etiqueta abierta', 1, 4),
(122, '00040430', 'Thinner estandar 960ml', 'refilado', '03081614-60', 4, '21-4', 'Etiqueta abierta', 1, 1),
(123, '00030430', 'Thinner estandar 960ml', 'refilado', '03081614-60', 4, '21-3', 'Etiqueta abierta', 1, 1),
(124, '00020430', 'Thinner estandar 960ml', 'refilado', '03081614-60', 4, '21-2', 'Etiqueta abierta', 1, 1),
(125, '00010430', 'Thinner estandar 960ml', 'refilado', '03081614-60', 4, '21-1', 'Etiqueta abierta', 1, 1),
(126, '00000233', 'Thinner estandar 960ml', 'revision', '03081614-63', 2, '24', 'Etiqueta abierta', 1, 1),
(127, '00000333', 'Thinner estandar 960ml', 'laminado', '03081614-63', 3, '24', 'Etiqueta abierta', 1, 4),
(128, '00040433', 'Thinner estandar 960ml', 'refilado', '03081614-63', 4, '24-4', 'Etiqueta abierta', 1, 1),
(129, '00030433', 'Thinner estandar 960ml', 'refilado', '03081614-63', 4, '24-3', 'Etiqueta abierta', 1, 1),
(130, '00020433', 'Thinner estandar 960ml', 'refilado', '03081614-63', 4, '24-2', 'Etiqueta abierta', 1, 1),
(131, '00010433', 'Thinner estandar 960ml', 'refilado', '03081614-63', 4, '24-1', 'Etiqueta abierta', 1, 1),
(132, '00000134', 'Thinner estandar 960ml', 'impresion', '03081614-64', 1, '25', 'Etiqueta abierta', 1, 1),
(133, '00000234', 'Thinner estandar 960ml', 'revision', '03081614-64', 2, '25', 'Etiqueta abierta', 1, 1),
(134, '00000334', 'Thinner estandar 960ml', 'laminado', '03081614-64', 3, '25', 'Etiqueta abierta', 1, 4),
(135, '00040434', 'Thinner estandar 960ml', 'refilado', '03081614-64', 4, '25-4', 'Etiqueta abierta', 1, 1),
(136, '00030434', 'Thinner estandar 960ml', 'refilado', '03081614-64', 4, '25-3', 'Etiqueta abierta', 1, 1),
(137, '00020434', 'Thinner estandar 960ml', 'refilado', '03081614-64', 4, '25-2', 'Etiqueta abierta', 1, 1),
(138, '00010434', 'Thinner estandar 960ml', 'refilado', '03081614-64', 4, '25-1', 'Etiqueta abierta', 1, 1),
(139, '00000139', 'Thinner estandar 960ml', 'impresion', '03081614-69', 1, '30', 'Etiqueta abierta', 1, 1),
(140, '00000239', 'Thinner estandar 960ml', 'revision', '03081614-69', 2, '30', 'Etiqueta abierta', 1, 1),
(141, '00000339', 'Thinner estandar 960ml', 'laminado', '03081614-69', 3, '30', 'Etiqueta abierta', 1, 4),
(142, '00040439', 'Thinner estandar 960ml', 'refilado', '03081614-69', 4, '30-4', 'Etiqueta abierta', 1, 1),
(143, '00030439', 'Thinner estandar 960ml', 'refilado', '03081614-69', 4, '30-3', 'Etiqueta abierta', 1, 1),
(144, '00020439', 'Thinner estandar 960ml', 'refilado', '03081614-69', 4, '30-2', 'Etiqueta abierta', 1, 1),
(145, '00010439', 'Thinner estandar 960ml', 'refilado', '03081614-69', 4, '30-1', 'Etiqueta abierta', 1, 1),
(146, '00000141', 'Thinner estandar 960ml', 'impresion', '03081614-71', 1, '32', 'Etiqueta abierta', 1, 1),
(147, '00000241', 'Thinner estandar 960ml', 'revision', '03081614-71', 2, '32', 'Etiqueta abierta', 1, 1),
(148, '00000341', 'Thinner estandar 960ml', 'laminado', '03081614-71', 3, '32', 'Etiqueta abierta', 1, 4),
(149, '00040441', 'Thinner estandar 960ml', 'refilado', '03081614-71', 4, '32-4', 'Etiqueta abierta', 1, 1),
(150, '00030441', 'Thinner estandar 960ml', 'refilado', '03081614-71', 4, '32-3', 'Etiqueta abierta', 1, 1),
(151, '00020441', 'Thinner estandar 960ml', 'refilado', '03081614-71', 4, '32-2', 'Etiqueta abierta', 1, 1),
(152, '00010441', 'Thinner estandar 960ml', 'refilado', '03081614-71', 4, '32-1', 'Etiqueta abierta', 1, 1),
(153, '00000135', 'Thinner estandar 960ml', 'impresion', '03081614-65', 1, '26', 'Etiqueta abierta', 1, 1),
(154, '00000235', 'Thinner estandar 960ml', 'revision', '03081614-65', 2, '26', 'Etiqueta abierta', 1, 1),
(155, '00000335', 'Thinner estandar 960ml', 'laminado', '03081614-65', 3, '26', 'Etiqueta abierta', 1, 4),
(156, '00040435', 'Thinner estandar 960ml', 'refilado', '03081614-65', 4, '26-4', 'Etiqueta abierta', 1, 1),
(157, '00030435', 'Thinner estandar 960ml', 'refilado', '03081614-65', 4, '26-3', 'Etiqueta abierta', 1, 1),
(158, '00020435', 'Thinner estandar 960ml', 'refilado', '03081614-65', 4, '26-2', 'Etiqueta abierta', 1, 1),
(159, '00010435', 'Thinner estandar 960ml', 'refilado', '03081614-65', 4, '26-1', 'Etiqueta abierta', 1, 1),
(160, '00000136', 'Thinner estandar 960ml', 'impresion', '03081614-66', 1, '27', 'Etiqueta abierta', 1, 1),
(161, '00000236', 'Thinner estandar 960ml', 'revision', '03081614-66', 2, '27', 'Etiqueta abierta', 1, 1),
(162, '00000336', 'Thinner estandar 960ml', 'laminado', '03081614-66', 3, '27', 'Etiqueta abierta', 1, 4),
(163, '00040436', 'Thinner estandar 960ml', 'refilado', '03081614-66', 4, '27-4', 'Etiqueta abierta', 1, 1),
(164, '00030436', 'Thinner estandar 960ml', 'refilado', '03081614-66', 4, '27-3', 'Etiqueta abierta', 1, 1),
(165, '00020436', 'Thinner estandar 960ml', 'refilado', '03081614-66', 4, '27-2', 'Etiqueta abierta', 1, 1),
(166, '00010436', 'Thinner estandar 960ml', 'refilado', '03081614-66', 4, '27-1', 'Etiqueta abierta', 1, 1),
(167, '00000137', 'Thinner estandar 960ml', 'impresion', '03081614-67', 1, '28', 'Etiqueta abierta', 1, 1),
(168, '00000237', 'Thinner estandar 960ml', 'revision', '03081614-67', 2, '28', 'Etiqueta abierta', 1, 1),
(169, '00000337', 'Thinner estandar 960ml', 'laminado', '03081614-67', 3, '28', 'Etiqueta abierta', 1, 4),
(170, '00040437', 'Thinner estandar 960ml', 'refilado', '03081614-67', 4, '28-4', 'Etiqueta abierta', 1, 1),
(171, '00030437', 'Thinner estandar 960ml', 'refilado', '03081614-67', 4, '28-3', 'Etiqueta abierta', 1, 1),
(172, '00020437', 'Thinner estandar 960ml', 'refilado', '03081614-67', 4, '28-2', 'Etiqueta abierta', 1, 1),
(173, '00010437', 'Thinner estandar 960ml', 'refilado', '03081614-67', 4, '28-1', 'Etiqueta abierta', 1, 1),
(174, '00000138', 'Thinner estandar 960ml', 'impresion', '03081614-68', 1, '29', 'Etiqueta abierta', 1, 1),
(175, '00000238', 'Thinner estandar 960ml', 'revision', '03081614-68', 2, '29', 'Etiqueta abierta', 1, 1),
(176, '00000338', 'Thinner estandar 960ml', 'laminado', '03081614-68', 3, '29', 'Etiqueta abierta', 1, 4),
(177, '00040438', 'Thinner estandar 960ml', 'refilado', '03081614-68', 4, '29-4', 'Etiqueta abierta', 1, 1),
(178, '00030438', 'Thinner estandar 960ml', 'refilado', '03081614-68', 4, '29-3', 'Etiqueta abierta', 1, 1),
(179, '00020438', 'Thinner estandar 960ml', 'refilado', '03081614-68', 4, '29-2', 'Etiqueta abierta', 1, 1),
(180, '00010438', 'Thinner estandar 960ml', 'refilado', '03081614-68', 4, '29-1', 'Etiqueta abierta', 1, 1),
(181, '00000044', 'Thinner estandar 960ml', 'programado', '03081614-1', 0, '35', 'Etiqueta abierta', 1, 1),
(182, '00000047', 'Thinner estandar 960ml', 'programado', '03081614-2', 0, '36', 'Etiqueta abierta', 1, 1),
(183, '00000048', 'Thinner estandar 960ml', 'programado', '03081614-3', 0, '37', 'Etiqueta abierta', 1, 1),
(184, '00000049', 'Thinner estandar 960ml', 'programado', '03081614-4', 0, '38', 'Etiqueta abierta', 1, 1),
(185, '00000050', 'Thinner estandar 960ml', 'programado', '03081614-5', 0, '39', 'Etiqueta abierta', 1, 1),
(186, '00000051', 'Thinner estandar 960ml', 'programado', '03081614-6', 0, '40', 'Etiqueta abierta', 1, 1),
(187, '00000052', 'Thinner estandar 960ml', 'programado', '03081614-7', 0, '41', 'Etiqueta abierta', 1, 1),
(188, '00000053', 'Thinner estandar 960ml', 'programado', '03081614-8', 0, '42', 'Etiqueta abierta', 1, 1),
(189, '00000054', 'Thinner estandar 960ml', 'programado', '03081614-9', 0, '43', 'Etiqueta abierta', 1, 1),
(190, '00000055', 'Thinner estandar 960ml', 'programado', '03081614-10', 0, '44', 'Etiqueta abierta', 1, 1),
(191, '00000056', 'Thinner estandar 960ml', 'programado', '03081614-11', 0, '45', 'Etiqueta abierta', 1, 1),
(192, '00000057', 'Thinner estandar 960ml', 'programado', '03081614-12', 0, '46', 'Etiqueta abierta', 1, 1),
(193, '00000058', 'Thinner estandar 960ml', 'programado', '03081614-13', 0, '47', 'Etiqueta abierta', 1, 1),
(194, '00000059', 'Thinner estandar 960ml', 'programado', '03081614-14', 0, '48', 'Etiqueta abierta', 1, 1),
(195, '00000060', 'Thinner estandar 960ml', 'programado', '03081614-15', 0, '49', 'Etiqueta abierta', 1, 1),
(196, '00000061', 'Thinner estandar 960ml', 'programado', '03081614-16', 0, '50', 'Etiqueta abierta', 1, 1),
(197, '00000062', 'Thinner estandar 960ml', 'programado', '03081614-17', 0, '51', 'Etiqueta abierta', 1, 1),
(198, '00000063', 'Thinner estandar 960ml', 'programado', '03081614-18', 0, '52', 'Etiqueta abierta', 1, 1),
(199, '00000064', 'Thinner estandar 960ml', 'programado', '03081614-19', 0, '53', 'Etiqueta abierta', 1, 1),
(200, '00000065', 'Thinner estandar 960ml', 'programado', '03081614-20', 0, '54', 'Etiqueta abierta', 1, 1),
(201, '00000066', 'Thinner estandar 960ml', 'programado', '03081614-21', 0, '55', 'Etiqueta abierta', 1, 1),
(202, '00000067', 'Thinner estandar 960ml', 'programado', '03081614-22', 0, '56', 'Etiqueta abierta', 1, 1),
(203, '00000068', 'Thinner estandar 960ml', 'programado', '03081614-23', 0, '57', 'Etiqueta abierta', 1, 1),
(204, '00000069', 'Thinner estandar 960ml', 'programado', '03081614-24', 0, '58', 'Etiqueta abierta', 1, 1),
(205, '00000167', 'Thinner estandar 960ml', 'impresion', '03081614-22', 1, '56', 'Etiqueta abierta', 1, 1),
(206, '00000267', 'Thinner estandar 960ml', 'revision', '03081614-22', 2, '56', 'Etiqueta abierta', 1, 1),
(207, '00000367', 'Thinner estandar 960ml', 'laminado', '03081614-22', 3, '56', 'Etiqueta abierta', 1, 4),
(208, '00040467', 'Thinner estandar 960ml', 'refilado', '03081614-22', 4, '56-4', 'Etiqueta abierta', 1, 1),
(209, '00030467', 'Thinner estandar 960ml', 'refilado', '03081614-22', 4, '56-3', 'Etiqueta abierta', 1, 1),
(210, '00020467', 'Thinner estandar 960ml', 'refilado', '03081614-22', 4, '56-2', 'Etiqueta abierta', 1, 1),
(211, '00010467', 'Thinner estandar 960ml', 'refilado', '03081614-22', 4, '56-1', 'Etiqueta abierta', 1, 1),
(212, '00000144', 'Thinner estandar 960ml', 'impresion', '03081614-1', 1, '35', 'Etiqueta abierta', 1, 1),
(213, '00000244', 'Thinner estandar 960ml', 'revision', '03081614-1', 2, '35', 'Etiqueta abierta', 1, 1),
(214, '00000344', 'Thinner estandar 960ml', 'laminado', '03081614-1', 3, '35', 'Etiqueta abierta', 1, 4),
(215, '00040444', 'Thinner estandar 960ml', 'refilado', '03081614-1', 4, '35-4', 'Etiqueta abierta', 1, 1),
(216, '00030444', 'Thinner estandar 960ml', 'refilado', '03081614-1', 4, '35-3', 'Etiqueta abierta', 1, 1),
(217, '00020444', 'Thinner estandar 960ml', 'refilado', '03081614-1', 4, '35-2', 'Etiqueta abierta', 1, 1),
(218, '00010444', 'Thinner estandar 960ml', 'refilado', '03081614-1', 4, '35-1', 'Etiqueta abierta', 1, 1),
(219, '00000147', 'Thinner estandar 960ml', 'impresion', '03081614-2', 1, '36', 'Etiqueta abierta', 1, 1),
(220, '00000247', 'Thinner estandar 960ml', 'revision', '03081614-2', 2, '36', 'Etiqueta abierta', 1, 1),
(221, '00000347', 'Thinner estandar 960ml', 'laminado', '03081614-2', 3, '36', 'Etiqueta abierta', 1, 4),
(222, '00040447', 'Thinner estandar 960ml', 'refilado', '03081614-2', 4, '36-4', 'Etiqueta abierta', 1, 1),
(223, '00030447', 'Thinner estandar 960ml', 'refilado', '03081614-2', 4, '36-3', 'Etiqueta abierta', 1, 1),
(224, '00020447', 'Thinner estandar 960ml', 'refilado', '03081614-2', 4, '36-2', 'Etiqueta abierta', 1, 1),
(225, '00010447', 'Thinner estandar 960ml', 'refilado', '03081614-2', 4, '36-1', 'Etiqueta abierta', 1, 1),
(226, '00000148', 'Thinner estandar 960ml', 'impresion', '03081614-3', 1, '37', 'Etiqueta abierta', 1, 1),
(227, '00000248', 'Thinner estandar 960ml', 'revision', '03081614-3', 2, '37', 'Etiqueta abierta', 1, 1),
(228, '00000348', 'Thinner estandar 960ml', 'laminado', '03081614-3', 3, '37', 'Etiqueta abierta', 1, 4),
(229, '00040448', 'Thinner estandar 960ml', 'refilado', '03081614-3', 4, '37-4', 'Etiqueta abierta', 1, 1),
(230, '00030448', 'Thinner estandar 960ml', 'refilado', '03081614-3', 4, '37-3', 'Etiqueta abierta', 1, 1),
(231, '00020448', 'Thinner estandar 960ml', 'refilado', '03081614-3', 4, '37-2', 'Etiqueta abierta', 1, 1),
(232, '00010448', 'Thinner estandar 960ml', 'refilado', '03081614-3', 4, '37-1', 'Etiqueta abierta', 1, 1),
(233, '00000149', 'Thinner estandar 960ml', 'impresion', '03081614-4', 1, '38', 'Etiqueta abierta', 1, 1),
(234, '00000249', 'Thinner estandar 960ml', 'revision', '03081614-4', 2, '38', 'Etiqueta abierta', 1, 1),
(235, '00000349', 'Thinner estandar 960ml', 'laminado', '03081614-4', 3, '38', 'Etiqueta abierta', 1, 4),
(236, '00040449', 'Thinner estandar 960ml', 'refilado', '03081614-4', 4, '38-4', 'Etiqueta abierta', 1, 1),
(237, '00030449', 'Thinner estandar 960ml', 'refilado', '03081614-4', 4, '38-3', 'Etiqueta abierta', 1, 1),
(238, '00020449', 'Thinner estandar 960ml', 'refilado', '03081614-4', 4, '38-2', 'Etiqueta abierta', 1, 1),
(239, '00010449', 'Thinner estandar 960ml', 'refilado', '03081614-4', 4, '38-1', 'Etiqueta abierta', 1, 1),
(240, '00000150', 'Thinner estandar 960ml', 'impresion', '03081614-5', 1, '39', 'Etiqueta abierta', 1, 1),
(241, '00000250', 'Thinner estandar 960ml', 'revision', '03081614-5', 2, '39', 'Etiqueta abierta', 1, 1),
(242, '00000350', 'Thinner estandar 960ml', 'laminado', '03081614-5', 3, '39', 'Etiqueta abierta', 1, 4),
(243, '00040450', 'Thinner estandar 960ml', 'refilado', '03081614-5', 4, '39-4', 'Etiqueta abierta', 1, 1),
(244, '00030450', 'Thinner estandar 960ml', 'refilado', '03081614-5', 4, '39-3', 'Etiqueta abierta', 1, 1),
(245, '00020450', 'Thinner estandar 960ml', 'refilado', '03081614-5', 4, '39-2', 'Etiqueta abierta', 1, 1),
(246, '00010450', 'Thinner estandar 960ml', 'refilado', '03081614-5', 4, '39-1', 'Etiqueta abierta', 1, 1),
(247, '00000151', 'Thinner estandar 960ml', 'impresion', '03081614-6', 1, '40', 'Etiqueta abierta', 1, 1),
(248, '00000251', 'Thinner estandar 960ml', 'revision', '03081614-6', 2, '40', 'Etiqueta abierta', 1, 1),
(249, '00000351', 'Thinner estandar 960ml', 'laminado', '03081614-6', 3, '40', 'Etiqueta abierta', 1, 4),
(250, '00040451', 'Thinner estandar 960ml', 'refilado', '03081614-6', 4, '40-4', 'Etiqueta abierta', 1, 1),
(251, '00030451', 'Thinner estandar 960ml', 'refilado', '03081614-6', 4, '40-3', 'Etiqueta abierta', 1, 1),
(252, '00020451', 'Thinner estandar 960ml', 'refilado', '03081614-6', 4, '40-2', 'Etiqueta abierta', 1, 1),
(253, '00010451', 'Thinner estandar 960ml', 'refilado', '03081614-6', 4, '40-1', 'Etiqueta abierta', 1, 1),
(254, '00000152', 'Thinner estandar 960ml', 'impresion', '03081614-7', 1, '41', 'Etiqueta abierta', 1, 1),
(255, '00000252', 'Thinner estandar 960ml', 'revision', '03081614-7', 2, '41', 'Etiqueta abierta', 1, 1),
(256, '00000352', 'Thinner estandar 960ml', 'laminado', '03081614-7', 3, '41', 'Etiqueta abierta', 1, 4),
(257, '00040452', 'Thinner estandar 960ml', 'refilado', '03081614-7', 4, '41-4', 'Etiqueta abierta', 1, 1),
(258, '00030452', 'Thinner estandar 960ml', 'refilado', '03081614-7', 4, '41-3', 'Etiqueta abierta', 1, 1),
(259, '00020452', 'Thinner estandar 960ml', 'refilado', '03081614-7', 4, '41-2', 'Etiqueta abierta', 1, 1),
(260, '00010452', 'Thinner estandar 960ml', 'refilado', '03081614-7', 4, '41-1', 'Etiqueta abierta', 1, 1),
(261, '00000153', 'Thinner estandar 960ml', 'impresion', '03081614-8', 1, '42', 'Etiqueta abierta', 1, 1),
(262, '00000253', 'Thinner estandar 960ml', 'revision', '03081614-8', 2, '42', 'Etiqueta abierta', 1, 1),
(263, '00000353', 'Thinner estandar 960ml', 'laminado', '03081614-8', 3, '42', 'Etiqueta abierta', 1, 4),
(264, '00040453', 'Thinner estandar 960ml', 'refilado', '03081614-8', 4, '42-4', 'Etiqueta abierta', 1, 1),
(265, '00030453', 'Thinner estandar 960ml', 'refilado', '03081614-8', 4, '42-3', 'Etiqueta abierta', 1, 1),
(266, '00020453', 'Thinner estandar 960ml', 'refilado', '03081614-8', 4, '42-2', 'Etiqueta abierta', 1, 1),
(267, '00010453', 'Thinner estandar 960ml', 'refilado', '03081614-8', 4, '42-1', 'Etiqueta abierta', 1, 1),
(268, '00000154', 'Thinner estandar 960ml', 'impresion', '03081614-9', 1, '43', 'Etiqueta abierta', 1, 1),
(269, '00000254', 'Thinner estandar 960ml', 'revision', '03081614-9', 2, '43', 'Etiqueta abierta', 1, 1),
(270, '00000354', 'Thinner estandar 960ml', 'laminado', '03081614-9', 3, '43', 'Etiqueta abierta', 1, 4),
(271, '00040454', 'Thinner estandar 960ml', 'refilado', '03081614-9', 4, '43-4', 'Etiqueta abierta', 1, 1),
(272, '00030454', 'Thinner estandar 960ml', 'refilado', '03081614-9', 4, '43-3', 'Etiqueta abierta', 1, 1),
(273, '00020454', 'Thinner estandar 960ml', 'refilado', '03081614-9', 4, '43-2', 'Etiqueta abierta', 1, 1),
(274, '00010454', 'Thinner estandar 960ml', 'refilado', '03081614-9', 4, '43-1', 'Etiqueta abierta', 1, 1),
(275, '00000155', 'Thinner estandar 960ml', 'impresion', '03081614-10', 1, '44', 'Etiqueta abierta', 1, 1),
(276, '00000255', 'Thinner estandar 960ml', 'revision', '03081614-10', 2, '44', 'Etiqueta abierta', 1, 1),
(277, '00000355', 'Thinner estandar 960ml', 'laminado', '03081614-10', 3, '44', 'Etiqueta abierta', 1, 4),
(278, '00040455', 'Thinner estandar 960ml', 'refilado', '03081614-10', 4, '44-4', 'Etiqueta abierta', 1, 1),
(279, '00030455', 'Thinner estandar 960ml', 'refilado', '03081614-10', 4, '44-3', 'Etiqueta abierta', 1, 1),
(280, '00020455', 'Thinner estandar 960ml', 'refilado', '03081614-10', 4, '44-2', 'Etiqueta abierta', 1, 1),
(281, '00010455', 'Thinner estandar 960ml', 'refilado', '03081614-10', 4, '44-1', 'Etiqueta abierta', 1, 1),
(282, '00000156', 'Thinner estandar 960ml', 'impresion', '03081614-11', 1, '45', 'Etiqueta abierta', 1, 1),
(283, '00000256', 'Thinner estandar 960ml', 'revision', '03081614-11', 2, '45', 'Etiqueta abierta', 1, 1),
(284, '00000356', 'Thinner estandar 960ml', 'laminado', '03081614-11', 3, '45', 'Etiqueta abierta', 1, 4),
(285, '00040456', 'Thinner estandar 960ml', 'refilado', '03081614-11', 4, '45-4', 'Etiqueta abierta', 1, 1),
(286, '00030456', 'Thinner estandar 960ml', 'refilado', '03081614-11', 4, '45-3', 'Etiqueta abierta', 1, 1),
(287, '00020456', 'Thinner estandar 960ml', 'refilado', '03081614-11', 4, '45-2', 'Etiqueta abierta', 1, 1),
(288, '00010456', 'Thinner estandar 960ml', 'refilado', '03081614-11', 4, '45-1', 'Etiqueta abierta', 1, 1),
(289, '00000157', 'Thinner estandar 960ml', 'impresion', '03081614-12', 1, '46', 'Etiqueta abierta', 1, 1),
(290, '00000257', 'Thinner estandar 960ml', 'revision', '03081614-12', 2, '46', 'Etiqueta abierta', 1, 1),
(291, '00000357', 'Thinner estandar 960ml', 'laminado', '03081614-12', 3, '46', 'Etiqueta abierta', 1, 4),
(292, '00040457', 'Thinner estandar 960ml', 'refilado', '03081614-12', 4, '46-4', 'Etiqueta abierta', 1, 1),
(293, '00030457', 'Thinner estandar 960ml', 'refilado', '03081614-12', 4, '46-3', 'Etiqueta abierta', 1, 1),
(294, '00020457', 'Thinner estandar 960ml', 'refilado', '03081614-12', 4, '46-2', 'Etiqueta abierta', 1, 1),
(295, '00010457', 'Thinner estandar 960ml', 'refilado', '03081614-12', 4, '46-1', 'Etiqueta abierta', 1, 1),
(296, '00000158', 'Thinner estandar 960ml', 'impresion', '03081614-13', 1, '47', 'Etiqueta abierta', 1, 1),
(297, '00000258', 'Thinner estandar 960ml', 'revision', '03081614-13', 2, '47', 'Etiqueta abierta', 1, 1),
(298, '00000358', 'Thinner estandar 960ml', 'laminado', '03081614-13', 3, '47', 'Etiqueta abierta', 1, 4),
(299, '00040458', 'Thinner estandar 960ml', 'refilado', '03081614-13', 4, '47-4', 'Etiqueta abierta', 1, 1),
(300, '00030458', 'Thinner estandar 960ml', 'refilado', '03081614-13', 4, '47-3', 'Etiqueta abierta', 1, 1),
(301, '00020458', 'Thinner estandar 960ml', 'refilado', '03081614-13', 4, '47-2', 'Etiqueta abierta', 1, 1),
(302, '00010458', 'Thinner estandar 960ml', 'refilado', '03081614-13', 4, '47-1', 'Etiqueta abierta', 1, 1),
(303, '00000159', 'Thinner estandar 960ml', 'impresion', '03081614-14', 1, '48', 'Etiqueta abierta', 1, 1),
(304, '00000259', 'Thinner estandar 960ml', 'revision', '03081614-14', 2, '48', 'Etiqueta abierta', 1, 1),
(305, '00000359', 'Thinner estandar 960ml', 'laminado', '03081614-14', 3, '48', 'Etiqueta abierta', 1, 4),
(306, '00040459', 'Thinner estandar 960ml', 'refilado', '03081614-14', 4, '48-4', 'Etiqueta abierta', 1, 1),
(307, '00030459', 'Thinner estandar 960ml', 'refilado', '03081614-14', 4, '48-3', 'Etiqueta abierta', 1, 1),
(308, '00020459', 'Thinner estandar 960ml', 'refilado', '03081614-14', 4, '48-2', 'Etiqueta abierta', 1, 1),
(309, '00010459', 'Thinner estandar 960ml', 'refilado', '03081614-14', 4, '48-1', 'Etiqueta abierta', 1, 1),
(310, '00000160', 'Thinner estandar 960ml', 'impresion', '03081614-15', 1, '49', 'Etiqueta abierta', 1, 1),
(311, '00000260', 'Thinner estandar 960ml', 'revision', '03081614-15', 2, '49', 'Etiqueta abierta', 1, 1),
(312, '00000360', 'Thinner estandar 960ml', 'laminado', '03081614-15', 3, '49', 'Etiqueta abierta', 1, 4),
(313, '00040460', 'Thinner estandar 960ml', 'refilado', '03081614-15', 4, '49-4', 'Etiqueta abierta', 1, 1),
(314, '00030460', 'Thinner estandar 960ml', 'refilado', '03081614-15', 4, '49-3', 'Etiqueta abierta', 1, 1),
(315, '00020460', 'Thinner estandar 960ml', 'refilado', '03081614-15', 4, '49-2', 'Etiqueta abierta', 1, 1),
(316, '00010460', 'Thinner estandar 960ml', 'refilado', '03081614-15', 4, '49-1', 'Etiqueta abierta', 1, 1),
(317, '00000161', 'Thinner estandar 960ml', 'impresion', '03081614-16', 1, '50', 'Etiqueta abierta', 1, 1),
(318, '00000261', 'Thinner estandar 960ml', 'revision', '03081614-16', 2, '50', 'Etiqueta abierta', 1, 1),
(319, '00000361', 'Thinner estandar 960ml', 'laminado', '03081614-16', 3, '50', 'Etiqueta abierta', 1, 4),
(320, '00040461', 'Thinner estandar 960ml', 'refilado', '03081614-16', 4, '50-4', 'Etiqueta abierta', 1, 1),
(321, '00030461', 'Thinner estandar 960ml', 'refilado', '03081614-16', 4, '50-3', 'Etiqueta abierta', 1, 1),
(322, '00020461', 'Thinner estandar 960ml', 'refilado', '03081614-16', 4, '50-2', 'Etiqueta abierta', 1, 1),
(323, '00010461', 'Thinner estandar 960ml', 'refilado', '03081614-16', 4, '50-1', 'Etiqueta abierta', 1, 1),
(324, '00000162', 'Thinner estandar 960ml', 'impresion', '03081614-17', 1, '51', 'Etiqueta abierta', 1, 1),
(325, '00000262', 'Thinner estandar 960ml', 'revision', '03081614-17', 2, '51', 'Etiqueta abierta', 1, 1),
(326, '00000362', 'Thinner estandar 960ml', 'laminado', '03081614-17', 3, '51', 'Etiqueta abierta', 1, 4),
(327, '00040462', 'Thinner estandar 960ml', 'refilado', '03081614-17', 4, '51-4', 'Etiqueta abierta', 1, 1),
(328, '00030462', 'Thinner estandar 960ml', 'refilado', '03081614-17', 4, '51-3', 'Etiqueta abierta', 1, 1),
(329, '00020462', 'Thinner estandar 960ml', 'refilado', '03081614-17', 4, '51-2', 'Etiqueta abierta', 1, 1),
(330, '00010462', 'Thinner estandar 960ml', 'refilado', '03081614-17', 4, '51-1', 'Etiqueta abierta', 1, 1),
(331, '00000163', 'Thinner estandar 960ml', 'impresion', '03081614-18', 1, '52', 'Etiqueta abierta', 1, 1),
(332, '00000263', 'Thinner estandar 960ml', 'revision', '03081614-18', 2, '52', 'Etiqueta abierta', 1, 1),
(333, '00000363', 'Thinner estandar 960ml', 'laminado', '03081614-18', 3, '52', 'Etiqueta abierta', 1, 4),
(334, '00040463', 'Thinner estandar 960ml', 'refilado', '03081614-18', 4, '52-4', 'Etiqueta abierta', 1, 1),
(335, '00030463', 'Thinner estandar 960ml', 'refilado', '03081614-18', 4, '52-3', 'Etiqueta abierta', 1, 1),
(336, '00020463', 'Thinner estandar 960ml', 'refilado', '03081614-18', 4, '52-2', 'Etiqueta abierta', 1, 1),
(337, '00010463', 'Thinner estandar 960ml', 'refilado', '03081614-18', 4, '52-1', 'Etiqueta abierta', 1, 1),
(338, '00000164', 'Thinner estandar 960ml', 'impresion', '03081614-19', 1, '53', 'Etiqueta abierta', 1, 1),
(339, '00000264', 'Thinner estandar 960ml', 'revision', '03081614-19', 2, '53', 'Etiqueta abierta', 1, 1),
(340, '00000364', 'Thinner estandar 960ml', 'laminado', '03081614-19', 3, '53', 'Etiqueta abierta', 1, 4),
(341, '00040464', 'Thinner estandar 960ml', 'refilado', '03081614-19', 4, '53-4', 'Etiqueta abierta', 1, 1),
(342, '00030464', 'Thinner estandar 960ml', 'refilado', '03081614-19', 4, '53-3', 'Etiqueta abierta', 1, 1),
(343, '00020464', 'Thinner estandar 960ml', 'refilado', '03081614-19', 4, '53-2', 'Etiqueta abierta', 1, 1),
(344, '00010464', 'Thinner estandar 960ml', 'refilado', '03081614-19', 4, '53-1', 'Etiqueta abierta', 1, 1),
(345, '00000165', 'Thinner estandar 960ml', 'impresion', '03081614-20', 1, '54', 'Etiqueta abierta', 1, 1),
(346, '00000265', 'Thinner estandar 960ml', 'revision', '03081614-20', 2, '54', 'Etiqueta abierta', 1, 1),
(347, '00000365', 'Thinner estandar 960ml', 'laminado', '03081614-20', 3, '54', 'Etiqueta abierta', 1, 4),
(348, '00040465', 'Thinner estandar 960ml', 'refilado', '03081614-20', 4, '54-4', 'Etiqueta abierta', 1, 1),
(349, '00030465', 'Thinner estandar 960ml', 'refilado', '03081614-20', 4, '54-3', 'Etiqueta abierta', 1, 1),
(350, '00020465', 'Thinner estandar 960ml', 'refilado', '03081614-20', 4, '54-2', 'Etiqueta abierta', 1, 1),
(351, '00010465', 'Thinner estandar 960ml', 'refilado', '03081614-20', 4, '54-1', 'Etiqueta abierta', 1, 1),
(352, '00000166', 'Thinner estandar 960ml', 'impresion', '03081614-21', 1, '55', 'Etiqueta abierta', 1, 1),
(353, '00000266', 'Thinner estandar 960ml', 'revision', '03081614-21', 2, '55', 'Etiqueta abierta', 1, 1),
(354, '00000366', 'Thinner estandar 960ml', 'laminado', '03081614-21', 3, '55', 'Etiqueta abierta', 1, 4),
(355, '00040466', 'Thinner estandar 960ml', 'refilado', '03081614-21', 4, '55-4', 'Etiqueta abierta', 1, 1),
(356, '00030466', 'Thinner estandar 960ml', 'refilado', '03081614-21', 4, '55-3', 'Etiqueta abierta', 1, 1),
(357, '00020466', 'Thinner estandar 960ml', 'refilado', '03081614-21', 4, '55-2', 'Etiqueta abierta', 1, 1),
(358, '00010466', 'Thinner estandar 960ml', 'refilado', '03081614-21', 4, '55-1', 'Etiqueta abierta', 1, 1),
(359, '00000168', 'Thinner estandar 960ml', 'impresion', '03081614-23', 1, '57', 'Etiqueta abierta', 1, 1),
(360, '00000268', 'Thinner estandar 960ml', 'revision', '03081614-23', 2, '57', 'Etiqueta abierta', 1, 1),
(361, '00000368', 'Thinner estandar 960ml', 'laminado', '03081614-23', 3, '57', 'Etiqueta abierta', 1, 4),
(362, '00040468', 'Thinner estandar 960ml', 'refilado', '03081614-23', 4, '57-4', 'Etiqueta abierta', 1, 1),
(363, '00030468', 'Thinner estandar 960ml', 'refilado', '03081614-23', 4, '57-3', 'Etiqueta abierta', 1, 1),
(364, '00020468', 'Thinner estandar 960ml', 'refilado', '03081614-23', 4, '57-2', 'Etiqueta abierta', 1, 1),
(365, '00010468', 'Thinner estandar 960ml', 'refilado', '03081614-23', 4, '57-1', 'Etiqueta abierta', 1, 1),
(366, '00000169', 'Thinner estandar 960ml', 'impresion', '03081614-24', 1, '58', 'Etiqueta abierta', 1, 1),
(367, '00000269', 'Thinner estandar 960ml', 'revision', '03081614-24', 2, '58', 'Etiqueta abierta', 1, 1),
(368, '00000369', 'Thinner estandar 960ml', 'laminado', '03081614-24', 3, '58', 'Etiqueta abierta', 1, 4),
(369, '00040469', 'Thinner estandar 960ml', 'refilado', '03081614-24', 4, '58-4', 'Etiqueta abierta', 1, 1),
(370, '00030469', 'Thinner estandar 960ml', 'refilado', '03081614-24', 4, '58-3', 'Etiqueta abierta', 1, 1),
(371, '00020469', 'Thinner estandar 960ml', 'refilado', '03081614-24', 4, '58-2', 'Etiqueta abierta', 1, 1),
(372, '00010469', 'Thinner estandar 960ml', 'refilado', '03081614-24', 4, '58-1', 'Etiqueta abierta', 1, 1),
(373, '00000070', 'Thinner estandar 960ml', 'programado', '03081614-25', 0, '59', 'Etiqueta abierta', 1, 1),
(374, '00000170', 'Thinner estandar 960ml', 'impresion', '03081614-25', 1, '59', 'Etiqueta abierta', 1, 1),
(375, '00000270', 'Thinner estandar 960ml', 'revision', '03081614-25', 2, '59', 'Etiqueta abierta', 1, 1),
(376, '00000370', 'Thinner estandar 960ml', 'laminado', '03081614-25', 3, '59', 'Etiqueta abierta', 1, 4),
(377, '00040470', 'Thinner estandar 960ml', 'refilado', '03081614-25', 4, '59-4', 'Etiqueta abierta', 1, 1),
(378, '00030470', 'Thinner estandar 960ml', 'refilado', '03081614-25', 4, '59-3', 'Etiqueta abierta', 0, 1),
(379, '00020470', 'Thinner estandar 960ml', 'refilado', '03081614-25', 4, '59-2', 'Etiqueta abierta', 1, 1),
(380, '00010470', 'Thinner estandar 960ml', 'refilado', '03081614-25', 4, '59-1', 'Etiqueta abierta', 1, 1),
(381, '0053870002', '', '', '', 0, '', '', 1, 1),
(382, '0053870003', '', '', '', 0, '', '', 1, 1),
(383, '00000046', 'Thinner estandar 960ml', 'programado', '03081614-53', 0, '60', 'Etiqueta abierta', 1, 1),
(384, '00000071', 'Thinner estandar 960ml', 'programado', '03081614-26', 0, '61', 'Etiqueta abierta', 1, 1),
(385, '00000072', 'Thinner estandar 960ml', 'programado', '03081614-27', 0, '62', 'Etiqueta abierta', 1, 1),
(386, '00000073', 'Thinner estandar 960ml', 'programado', '03081614-28', 0, '63', 'Etiqueta abierta', 1, 1),
(387, '00000074', 'Thinner estandar 960ml', 'programado', '03081614-29', 0, '64', 'Etiqueta abierta', 1, 1),
(388, '00000075', 'Thinner estandar 960ml', 'programado', '03081614-30', 0, '65', 'Etiqueta abierta', 1, 1),
(389, '00000076', 'Thinner estandar 960ml', 'programado', '03081614-31', 0, '66', 'Etiqueta abierta', 1, 1),
(390, '00000077', 'Thinner estandar 960ml', 'programado', '03081614-32', 0, '67', 'Etiqueta abierta', 1, 1),
(391, '00000078', 'Thinner estandar 960ml', 'programado', '03081614-33', 0, '68', 'Etiqueta abierta', 1, 1),
(392, '00000079', 'Thinner estandar 960ml', 'programado', '03081614-38', 0, '69', 'Etiqueta abierta', 1, 1),
(393, '00000080', 'Thinner estandar 960ml', 'programado', '03081614-39', 0, '70', 'Etiqueta abierta', 1, 1),
(394, '00000146', 'Thinner estandar 960ml', 'impresion', '03081614-53', 1, '60', 'Etiqueta abierta', 1, 1),
(395, '00000081', 'Thinner estandar 960ml', 'programado', '03081614-40', 0, '71', 'Etiqueta abierta', 1, 1),
(396, '00000082', 'Thinner estandar 960ml', 'programado', '03081614-41', 0, '72', 'Etiqueta abierta', 1, 1),
(397, '00000083', 'Thinner estandar 960ml', 'programado', '03081614-42', 0, '73', 'Etiqueta abierta', 1, 1),
(398, '00000181', 'Thinner estandar 960ml', 'impresion', '03081614-40', 1, '71', 'Etiqueta abierta', 1, 1),
(399, '00000281', 'Thinner estandar 960ml', 'revision', '03081614-40', 2, '71', 'Etiqueta abierta', 1, 1),
(400, '00000381', 'Thinner estandar 960ml', 'laminado', '03081614-40', 3, '71', 'Etiqueta abierta', 1, 2),
(401, '00000171', 'Thinner estandar 960ml', 'impresion', '03081614-26', 1, '61', 'Etiqueta abierta', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigos_baja`
--

CREATE TABLE `codigos_baja` (
  `ID` int(11) NOT NULL,
  `codigo` varchar(30) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `longitud` double NOT NULL,
  `producto` varchar(70) NOT NULL,
  `unidades` double NOT NULL,
  `proceso` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `confirmarprod`
--

CREATE TABLE `confirmarprod` (
  `idConfi` int(11) NOT NULL,
  `ordenConfi` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `prodConfi` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `empaqueConfi` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `cantidadConfi` decimal(9,3) NOT NULL,
  `surtido` decimal(9,3) NOT NULL,
  `referenciaConfi` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `embarqueConfi` date NOT NULL,
  `entregaConfi` date NOT NULL,
  `bajaConfi` int(1) NOT NULL DEFAULT '1',
  `enlaceEmbarque` varchar(11) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `confirmarprod`
--

INSERT INTO `confirmarprod` (`idConfi`, `ordenConfi`, `prodConfi`, `empaqueConfi`, `cantidadConfi`, `surtido`, `referenciaConfi`, `embarqueConfi`, `entregaConfi`, `bajaConfi`, `enlaceEmbarque`) VALUES
(1, '12845', 'Thinner estandar 960ml', 'rollo', '185.000', '0.000', '', '2018-06-18', '2018-06-22', 1, '0'),
(2, '12916', 'Thinner estandar 960ml', 'rollo', '1040.000', '0.000', '', '2018-06-25', '2018-06-29', 0, '0'),
(3, '12845', 'Thinner estandar 960ml', 'rollo', '185.000', '0.000', '', '2018-06-25', '2018-06-29', 1, '0'),
(4, '12845', 'Thinner estandar 960ml', 'rollo', '185.000', '0.000', '', '2018-06-25', '2018-06-29', 1, '0'),
(5, '12916', 'Thinner estandar 960ml', 'rollo', '1040.000', '0.000', '', '2018-06-25', '2018-06-29', 1, '0'),
(6, '977', 'Thinner estandar 960ml', 'rollo', '150.000', '0.000', 'nio cv', '2019-01-02', '2019-01-03', 1, '20190102001');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consumos`
--

CREATE TABLE `consumos` (
  `IDConsumo` int(11) NOT NULL,
  `subProceso` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `elemento` int(11) NOT NULL,
  `consumo` float NOT NULL,
  `producto` int(11) NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `consumos`
--

INSERT INTO `consumos` (`IDConsumo`, `subProceso`, `elemento`, `consumo`, `producto`, `baja`) VALUES
(6, 'revision', 3, 34, 5, 0),
(7, 'refilado', 0, 34, 0, 0),
(9, 'fusion', 0, 56, 0, 0),
(10, 'impresion', 12, 67, 0, 1),
(11, 'embosado', 2, 0.67, 0, 1),
(13, 'refilado', 10, 42, 5, 0),
(14, 'revision', 104, 0.2, 5, 1),
(15, 'impresion', 199, 0.2, 5, 0),
(16, 'laminado', 344, 0.56, 5, 0),
(17, 'revision', 1, 0.6, 5, 1),
(18, 'refilado', 92, 0.9, 5, 1),
(19, 'impresion', 96, 0.056, 5, 1),
(20, 'rollo', 111, 1, 5, 1),
(21, 'laminado', 134, 0.098, 5, 0),
(22, 'refilado', 384, 1.4, 5, 1),
(23, 'laminado', 380, 0.006, 5, 1),
(24, 'impresion', 12, 67, 0, 1),
(25, 'embosado', 2, 0.67, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE `departamento` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `departamento`
--

INSERT INTO `departamento` (`id`, `nombre`, `baja`) VALUES
(1, 'Calidad', 1),
(2, 'AlmacÃ©n', 1),
(3, 'RevisiÃ³n', 1),
(4, 'Refilado', 1),
(5, 'FusiÃ³n', 1),
(6, 'LogÃ­stica', 1),
(7, 'ProducciÃ³n', 1),
(8, 'TecnologÃ­as de la informaciÃ³n', 1),
(9, 'Corte', 1),
(10, 'ImpresiÃ³n', 1),
(11, 'Compras', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devoluciones`
--

CREATE TABLE `devoluciones` (
  `id` int(11) NOT NULL,
  `folio` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `fechaDev` date NOT NULL,
  `tipo` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `codigo` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `sucursal` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `producto` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `observaciones` text COLLATE utf8_unicode_ci NOT NULL,
  `idresponsable` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `elementosconsumo`
--

CREATE TABLE `elementosconsumo` (
  `idElemento` int(11) NOT NULL,
  `identificadorElemento` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nombreElemento` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `impuesto` float NOT NULL,
  `precio` float NOT NULL,
  `clasificacion1` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `clasificacion2` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `clasificacion3` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `clasificacion4` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `clave_sat` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `foto` longtext COLLATE utf8_unicode_ci NOT NULL,
  `unidad` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `elementosconsumo`
--

INSERT INTO `elementosconsumo` (`idElemento`, `identificadorElemento`, `nombreElemento`, `impuesto`, `precio`, `clasificacion1`, `clasificacion2`, `clasificacion3`, `clasificacion4`, `clave_sat`, `foto`, `unidad`, `baja`) VALUES
(1, 'ADH00121', 'ADHESIVO UV (UVH00002/COLD FOIL ADHESIVE)', 16, 0, 'ADHESIVOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(2, 'ADH002', 'UV VARNIS-ADHESIVE UVH0-0003-465U', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(3, 'ADH003', 'UV FLEXO VARNISH HIGH UV00026', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(4, 'ADH004', 'TECNO ADHESIVOS A-6319', 16, 0, 'ADHESIVOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '31201626', '', '', 1),
(5, 'ADH006', 'NOVACOTE CA12', 16, 0, 'ADHESIVOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '13111057', '', '', 1),
(6, 'ADH007', 'NOVACOTE NC 270', 16, 0, 'ADHESIVOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '13111061', '', '', 1),
(7, 'ADH009', 'CF092757 SOLVENTE BASED HEAT SEALABLE', 16, 0, 'ADHESIVOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(8, 'ADH010', 'CATALYST  C', 16, 0, 'ADHESIVOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(9, 'ADH011', 'FLEX CRAFT 19.1kg porron 5gal o 18.9lts. INACTIVO', 16, 0, 'ADHESIVOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(10, 'ADH012', 'ROBON L-100', 16, 0, 'ADHESIVOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(11, 'ADH013', 'FLEX CRAFT 19.1KG PORRON 5GAL O 18.9LTS', 16, 0, 'ADHESIVOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(12, 'ADH014', 'ADCOTE 331', 16, 0, 'ADHESIVOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(13, 'BAN001', 'CINTA MAGNETICA HI-CO27500E (ALTA)', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(14, 'BAN002', 'CINTA MAGNETICA LO-CO (BAJA)', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(15, 'BAR001', 'ARGRAFIC-BARNIZ BASE AGUA MATE', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(16, 'BOL001', 'BOLSA 35CM X 45CM', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '24111503', '', '', 1),
(17, 'BOL002', 'BOLSA PARA CORTE18 CM X 26 CM', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '24111503', '', '', 1),
(18, 'BOL004', 'BOLSA PARA CORTE 20CM  X 30CM CAL. 150', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '24111503', '', '', 1),
(19, 'BOL005', 'BOLSA BASURA 90 X 120 CAL. 300', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '24111503', '', '', 1),
(20, 'BOL006', 'BOLSA POLIPAPEL EN BOBINAS 60CMX90CM (PAQ. 25 KG)', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '24111503', '', '', 1),
(21, 'BOL007', 'PELICULA STRETCH CAL 60 18\" X 1200FT 2KILOSS (EMPLAYE)', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '24141501', '', '', 1),
(22, 'CAJ001', 'CAJA CARTÃ“N PLEGADA 32.5CMX32.5X23CM pak c/10pz', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(23, 'CAR001', 'RUEDA (TAPA) CARTON CHICA 32', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '14121506', '', '', 1),
(24, 'CAR002', 'RUEDA (TAPA) CARTON GRANDE 35 cm', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '14121506', '', '', 1),
(25, 'CAR003', 'RUEDA (TAPA)CARTON GRANDE 50CM', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '14121506', '', '', 1),
(26, 'CAR004', 'CARTÃ“N SINGLE FACE 1.68x100 METROS.', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '24112501', '', '', 1),
(27, 'CAR005', 'CORE TUBO CARTON 6\"X1MT grande chiva.', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '24141701', '', '', 1),
(28, 'CAR006', 'CORE TUBO CARTON 5.2\" 1MT sin uso', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '24141701', '', '', 1),
(29, 'CIN001', 'CINTA AMARILLA TRANSPARENTE', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(30, 'CIN002', 'CINTA CANELA TRUPER 48X150', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(31, 'CIN003', 'CINTA CONDUCTORA', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(32, 'CIN004', 'CINTA MASKING TAPE 12MM', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '31201503', '', '', 1),
(33, 'CIN006', 'CINTA MASKING TAPE 24MM', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '31201503', '', '', 1),
(34, 'CIN007', 'CINTA ROJA TRANSPARENTE 48mm', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(35, 'CIN009', 'CINTA TRANSPARENTE 48 X150', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(36, 'CIN010', 'CINTA VERDE TRANSPARENTE', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(37, 'CIN011', 'CINTA CANELA 48X150', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '31201517', '', '', 1),
(38, 'CIN012', 'CINTA NARANJA DELIMITADORA', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(39, 'CIN013', 'CINTA AUTOADHERIBLE PAPEL TEFLON', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(40, 'CIN014', 'CTA TSP. PRA LAMCION 320 X 1524 MTS. 10.84kg .020 MICRAS', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '31201512', '', '', 1),
(41, 'CIN015', 'POLIOLEFINA EZ 12\" CAL 100 C/800M DOBLE', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(42, 'CIN016', 'CINTA BLANCA 24X150', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(43, 'CO308B9501', 'CAJA EXPERMECTINA 100 ML 5.2X10X5.2CM', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '31261500', '', '', 1),
(44, 'CO308B9601', 'CAJA CATO EXPERT 100 ML 5.2X10X5.2CM', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '31261500', '', '', 1),
(45, 'CO308B9701', 'CAJA COMPLE EXPERT B 100ML 5.2X10X5.2CM', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '31261500', '', '', 1),
(46, 'CO308B98O1', 'CAJA DIPIRO EXPERT 100 ML 5.2X10X5.2CM', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '31261500', '', '', 1),
(47, 'COM001', 'MOLECULA QUINDIO  (authentix)', 16, 0, 'COMPONENTES PARA TINTAS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(48, 'COM002', 'POLVO OPTICO', 16, 0, 'COMPONENTES PARA TINTAS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '12171501', '', '', 1),
(49, 'COM003', 'TRATAMIENTO CORONA', 16, 0, 'COMPONENTES PARA TINTAS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(50, 'COR001', 'CORE 5\" x 1 MT', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '24141701', '', '', 1),
(51, 'COR002', 'CORE TUBO CARTON 3.040 X 400 X 1MT', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '24141701', '', '', 1),
(52, 'COR003', 'TUBO TOTAL KRAFT 100 X 152.8 X .394 SIERRA', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(53, 'CSTSEGURO', 'VALOR DE SEGURO', 0, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(54, 'CTSFLETE', 'VALOR FLETE', 0, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(55, 'DIL001', 'HMCO-0063-473U HMC ACE PH ADJUSTER  (FLINT)', 16, 0, 'DILUYENTES', '(Ninguna)', 'FLEXOGRAFIA', '(Ninguna)', '', '', '', 1),
(56, 'DIL002', 'EXTENDER REDUCTOR', 16, 0, 'DILUYENTES', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '', '', '', 1),
(57, 'DIL003', 'HMCO-0061-473U WHITE EXTENDER  (FLINT)', 16, 0, 'DILUYENTES', '(Ninguna)', 'FLEXOGRAFIA', '(Ninguna)', '', '', '', 1),
(58, 'DIL004', 'G16Z-029FF EXTENDER PVC FLEXO', 16, 0, 'DILUYENTES', '(Ninguna)', 'FLEXOGRAFIA', '(Ninguna)', '', '', '', 1),
(59, 'DIL005', 'G16Z-125FF SOLVENTE RETARDANTE', 16, 0, 'DILUYENTES', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '', '', '', 1),
(60, 'E0322C1101', 'MANGA TRUPER 30ML 70.5X50MM', 16, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(61, 'E0322C1102', 'MANGA TRUPER 90ML 70.5x148MM', 16, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(62, 'E0414B2401', 'CHIVACOLAPE600ML 210X95MM / 714310406PED02', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(63, 'E0420C17', 'THINNER ESTANDAR 500 ML 230x85MM', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(64, 'E0420C18', 'THINNER ESTANDAR 960 ML', 16, 221.2, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(65, 'E0630B4101', 'EGO PLANT  SABOR HERBAL AR 200ML 711210600ARV05', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(66, 'E0630B4102', 'EGO PLANTPE 200 ML 175X75 / 711210600PEZ06', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(67, 'E0630B4103', 'EGO PLANTMX 200 ML 175X75 / 711210600MXZ07', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(68, 'E0630B4201KOCO', 'KOLINA CO 200 ML 711810600COZ05', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(69, 'E0630B4301MAMZMX', 'MAGNUS MANZANA MX 200ML 712010613MXZ06', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(70, 'E0630B4401', 'BIOCROS MANZANA GTA04 712010613GTA04', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(71, 'E0630B4501KOEC', 'KOLINA EC 200 ML 711810600ECZ05', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(72, 'E0630B4801KOMX', 'KOLINA MX 200ML 711810600MXZ06', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(73, 'E0722A4602', 'CRISTAL ESTRELLA 107X70MM ETIQUETA TERMOENCOGIBLE HOLOGRAFIC', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(74, 'E0722A46024', 'CRISTAL ESTRELLA CON LEYENDA 107X70MM ETIQUETA TERMOENCOGIBL', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(75, 'E0722A4603', 'CRISTAL GOTA 107X70MM ETIQUETA TERMOENCOGIBLE HOLOGRAFICA', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(76, 'E0901B7401', 'CHIVACOLAMX600ML 210x95MM/714310406MXD04', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(77, 'E0921B75', 'CHIVACOLAUSD600ML 210x95MM/714310406USD06', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(78, 'EMB001', 'FOAM DE POLIETILENO 1/4\" 2X55m', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '13111201', '', '', 1),
(79, 'EMB003', 'RELLENO CACAHUATE DE POLIESTIRENO P/EMPAQUE', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(80, 'EMB004', 'BOLSA GRAPA METALICA', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '27112306', '', '', 1),
(81, 'EMB005', 'FLEJE NEGRO 1/2\" P/MANUAL (CAJA C/7.5 KILOSSs)', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '24112902', '', '', 1),
(82, 'EMB006', 'RAFIA DE POLIPROPILENO BLANCA 1KG', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(83, 'EMB007', 'ETIQ.AUTOADHERIBLE MED.101.6X50.8( INTERNA)', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(84, 'FLE001', 'NEGRO PROCESS HJ-440 ( TYBESA)', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BASE AGUA', '', '', '', 1),
(85, 'FLE002', 'CYAN PROCESS HJ-130 (TYBESA)', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BASE AGUA', '', '', '', 1),
(86, 'FLE003', 'AMARILLLO PROCESS LP-310 (TYBESA)', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BASE AGUA', '', '', '', 1),
(87, 'FLE004', 'AMARILLO PROCESS HJ-310 (TYBESA)', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BASE AGUA', '', '', '', 1),
(88, 'FLE005', 'MAGENTA PROCESS HJ-320 (TYBESA)', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BASE AGUA', '', '', '', 1),
(89, 'FLE006', 'VERDE 349 TSP-1043 (TYBESA)', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BASE AGUA', '', '', '', 1),
(90, 'FLE007', 'NEGRO PROCESS FLEXO UV UVF-1850 (TYBESA)', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BASE AGUA', '', '', '', 1),
(91, 'FRA001', 'FRASCO 100ML', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '24122002', '', '', 1),
(92, 'FRA002', 'FRASCO 250ML', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '24122002', '', '', 1),
(93, 'FRA003', 'FRASCO 500 ML', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '24122002', '', '', 1),
(94, 'FRA004', 'FRASCO 1000 ML', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '24122002', '', '', 1),
(95, 'FRA005', 'FRASCO 2000 ML', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '24122002', '', '', 1),
(96, 'FRA006', 'CUBETA BLANCA 4 LTS', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '24122002', '', '', 1),
(97, 'FRA007', 'BOTELLA 1 LT', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(98, 'H0322C10', 'AVA HOLOGRAMA GENERICO 22X22', 16, 0, 'HOLOGRAMA', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(99, 'H0920A5501', 'RECORD HOLOGRAMA 2017 60X60MM', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(100, 'HER005', 'SELLADOR MANUAL', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(101, 'HOL001', 'HOLOGRAMA CRISTAL ESTRELLA', 16, 0, 'HOLOGRAMA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(102, 'HOL002', 'HOLOGRAMA CRISTAL GOTA', 16, 0, 'HOLOGRAMA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(103, 'HOL003', 'HOLOGRAMA SANTORINI', 16, 0, 'HOLOGRAMA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(104, 'HOL004', 'HOLOGRAMA ELECTROPURA', 16, 0, 'HOLOGRAMA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(105, 'HOL005', 'HOLOGRAMA EPURA', 16, 0, 'HOLOGRAMA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(106, 'HOL006', 'EMBOSADO GENERICO', 16, 0, 'HOLOGRAMA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(107, 'HOL007', 'EMBOSADO NATURCOL', 16, 0, 'HOLOGRAMA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(108, 'HOL008', 'HOLOGRAMA CRISTAL ESTRELLA (KG)', 0, 0, 'HOLOGRAMA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(109, 'HOL009', 'HOLOGRAMA CRISTAL GOTA (KG)', 0, 0, 'HOLOGRAMA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(110, 'HOL010', 'HOLOGRAMA ELECTROPURA (KG)', 0, 0, 'HOLOGRAMA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(111, 'HOL011', 'HOLOGRAMA EPURA (KG)', 0, 0, 'HOLOGRAMA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(112, 'HOL012', 'HOLOGRAMA SANTORINI (KG)', 0, 0, 'HOLOGRAMA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(113, 'HOL013', 'HOLOGRAMA GENERICO', 16, 0, 'HOLOGRAMA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(114, 'HOL014', 'HOLOGRAMA SANTORINI 170MM', 0, 0, 'HOLOGRAMA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(115, 'HOL015', 'HOLOGRAMA EPURA 170MM', 0, 0, 'HOLOGRAMA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(116, 'HOL016', 'HOLOGRAMA GENERICO EN DISCO (GEPP)', 16, 0, 'HOLOGRAMA', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(117, 'INS001', 'LIJA  N.-120', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(118, 'INS002', 'LIJA N.- 80', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(119, 'INS003', 'LIJA 360MM BASE AGUA', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '27111918', '', '', 1),
(120, 'INS004', 'LIJA 400MM BASE AGUA', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(121, 'INS005', 'LIJA 600MM BASE AGUA', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '27111918', '', '', 1),
(122, 'INS006', 'NAVAJA REPUESTO CUTER LARGA', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '23151901', '', '', 1),
(123, 'INS007', 'NAVAJA PARA CUTER SK4 Corta', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(124, 'INS008', 'NAVAJA PARA RASPADOR', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '27112009', '', '', 1),
(125, 'INS009', 'NAVAJA REPUESTO BISTURI', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(126, 'INS010', 'BOLSA DE LIGAS N.-10', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(127, 'INS011', 'BOLSA DE TRAPO GRIS', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '47131501', '', '', 1),
(128, 'INS012', 'AGUA DESTILADA', 16, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(129, 'INS014', 'MUESTRAS VARIAS', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(130, 'INS015', 'CINTA SELLA ROSCAS (CINTA DE TEFLÃ“N)', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '31201500', '', '', 1),
(131, 'INS016', 'RASPADOR', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(132, 'INS017', 'ANTICONGELANTE', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(133, 'INS018', 'GRIFO MANUAL', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(134, 'INS019', 'TIRA DE PLASTICO SOSPECHOSO', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(135, 'INS020', 'TIRA DE ACERO SOSPECHOSO', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(136, 'INS021', 'ARENA PARA GATO', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(137, 'INS022', 'RUEDITAS DE CARTON', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(138, 'INS023', 'ACIDO MURIATICO', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(139, 'INS024', 'CARBONATO DE CALCIO', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(140, 'INS025', 'AGUA DESMINERALIZADA', 16, 0, 'DILUYENTES', '(Ninguna)', '(Ninguna)', '(Ninguna)', '41104213', '', '', 1),
(141, 'INS026', 'MANGUERA REFORZADA 1/2\"', 0, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(142, 'INS027', 'LAURIL SULFATO DE SODIO', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(143, 'LIM001', 'JABON PARA MANOS', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(144, 'LIM002', 'GEL PARA MANOS', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(145, 'LIM003', 'DESENGRASANTE', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(146, 'LIM004', 'LIMPIADOR PARA VIDRIOS', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(147, 'LIM005', 'AROMATIZANTE PARA BAÃ‘OS', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(148, 'LIM006', 'ESCOBAS', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(149, 'LIM007', 'MOPS', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '47131600', '', '', 1),
(150, 'LIM008', 'PAPEL HIGIENICO (BAÃ‘OS)', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(151, 'LIM009', 'AROMATIZANTE SPRAY', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(152, 'LIM010', 'AIR WICK PARA MAQUINA DISPENSADORA', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(153, 'LIM011', 'GLADE LATA DE GEL PARA BAÃ‘O', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(154, 'LIM012', 'MECHUDOS', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(155, 'LIM013', 'ACEITE PARA MOP', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(156, 'LIM014', 'SARRICIDA', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(157, 'LIM015', 'FABULOSO', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(158, 'LIM016', 'CLORALEX', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(159, 'LIM017', 'JABON EN POLVO UTIL', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(160, 'LIM018', 'BOLSA DE COFIAS', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '46181708', '', '', 1),
(161, 'LIM019', 'SUAVITEL', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(162, 'LIM020', 'JABON PARA TRASTES', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(163, 'LIM021', 'ROLLO DE TOLLAS AZUL (SERVITOALLAS)', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(164, 'LIM022', 'FIBRA VERDE SCOTCH BRITE P-96', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(165, 'LIM023', 'PINOL', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(166, 'LIM024', 'FIBRA / ESPONJA  ALMOHADILLA P-24', 16, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(167, 'LIM025', 'SARRICIDA (CORRECTO)', 0, 0, 'PRODUCTOS DE LIMPIEZA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(168, 'M0202A8401NUEVO', 'MANGA TERMOENCOGIBLE CAFE & AMARILLO', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(169, 'M0202A8501NUEVO', 'MANGA TERMOENCOGIBLE NEGRO 100%', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(170, 'M0202A8601NUEVO', 'MANGA TERMOENCOGIBLE AMARILLO 100%', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(171, 'M0202A8701NUEVO', 'MANGA TERMOENCOGIBLE ROJO & ROJO', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(172, 'M0202A8901NUEVO', 'MANGA TERMOENCOGIBLE VERDE & AMARILLO', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(173, 'M0208A9001', 'SANTORINI 97X68MM P226C 2017/Q2 CORTE', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(174, 'M0208A90011', 'SANTORINI 97X68MM P226C 2017/Q2 BOBINA', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(175, 'M0208A9002', 'SANTORINI 97X74MM P226C 2017/Q2 CORTE', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(176, 'M0208A90022', 'SANTORINI 97X74MM P226C 2017/Q2 BOBINA', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(177, 'M0208A9101', 'EPURA 97X74MM P7712 2017/Q2 CORTE', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(178, 'M0208A91011', 'EPURA 97X74MM P7712 2017/Q2 BOBINA', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(179, 'M0208A9102', 'EPURA 108X75MM P7712 2017/Q2 BOBINA', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(180, 'M0208A9201', 'ELECTROPURA 108X75MM P165C 2017/Q CORTE', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(181, 'M0208A92011', 'ELECTROPURA 108X75MM P165C 2017/Q2 BOBINA', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(182, 'M0208A9302', 'BEVI BANDA DE GARANTIA BOTELLIN 74X40MM', 16, 1.58, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(183, 'M0503C2203B', 'GENERICO 97x68MM BOBINA 2018 /Q3', 16, 117, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(184, 'M0503C2203C', 'GENERICO 97X68MM CORTE2018 /Q3', 16, 117, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(185, 'M0503C2204B', 'GENERICO 108X75MM BOBINA2018/Q3', 16, 117, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(186, 'M0503C2204C', 'GENERICO 108X75MM CORTE 2018/Q3', 16, 117, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(187, 'M0503C2205B', 'GENERICO 97X74MM BOBINA 2018/Q3', 16, 117, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(188, 'M0503C2205C', 'GENERICO 97X74MM CORTE 2018/Q3', 16, 117, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(189, 'M0609B39', 'EPURA 88X7 MM 10.1 LT P2147 2017', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(190, 'M0930B7601B', 'SANTORINI 97X68MM MINERALE 400 ML P7462 2017/Q4 BOBINA', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(191, 'M0930B7601C', 'SANTORINI 97X68MM MINERALE 400 ML P7462 2017/Q4 CORTE', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(192, 'M0930B7602B', 'SANTORINI 97X74MM MINERALE 400 ML P7462 2017/Q4 BOBINA', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(193, 'M0930B7602C', 'SANTORINI 97X74MM MINERALE 400 ML P7462 2017/Q4 CORTE', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(194, 'M0930B7701B', 'EPURA 97X74MM MINERALE 400ML P109 2017/Q4 BOBINA', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(195, 'M0930B7701C', 'EPURA 97X74MM MINERALE 400ML P109 2017/Q4 CORTE', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(196, 'M0930B7702B', 'EPURA 108X75MM MINERALE 400ML P109 2017/Q4 BOBINA', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(197, 'M0930B7801', 'ELECTROPURA 108X75MM MINERALE 400ML P334 2017/Q4 CORTE', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(198, 'M0930B7802', 'ELECTROPURA 108X75MM MINERALE 400ML P334 2017/Q4 BOBINA', 0, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(199, 'M1102B84001', 'SANTORINI 97X68MM PORTAFOLIO PWARM RED 2018/Q1 BOBINA', 16, 0, 'PTT', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(200, 'M1102B8401', 'SANTORINI 97X68MM PORTAFOLIO PWARM RED 2018/Q1 CORTE', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(201, 'M1102B8402', 'SANTORINI 97X74MM PORTAFOLIO PWARM RED 2018/Q1 CORTE', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(202, 'M1102B85001', 'EPURA 97X74MM PORTAFOLIO P2597 2018/Q1 BOBINA', 16, 119, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(203, 'M1102B8501', 'EPURA 97X74MM PORTAFOLIO P2597 2018/Q1 CORTE', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(204, 'M1102B8502', 'EPURA108X75MM PORTAFOLIO P2597 2018/Q1 BOBINA', 16, 119, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(205, 'M1102B86001', 'ELECTROPURA 108X75MM PORTAFOLIO P2395 2018/Q1 BOBINA', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(206, 'M1102B8601', 'ELECTROPURA 108X75MM PORTAFOLIO P2395 2018/Q1 CORTE', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(207, 'M1122B89', 'SANTORINI 97X74MM PORTAFOLIO PWARM RED 2018/Q1 BOBINA', 16, 119, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(208, 'M1122B90B', 'ELECTROPURA 108X75MM BENEDETTIS P368 2018/FEB-MZO BOBINA', 16, 119, 'PTT', '(Ninguna)', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(209, 'M1122B90C', 'ELECTROPURA 108X75MM BENEDETTIS P368 2018/FEB-MZO CORTE', 16, 119, 'PTT', '(Ninguna)', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(210, 'M1122B910C', 'EPURA 97X74MM BENEDETTIS P2425/FEB-MZO CORTE', 16, 119, 'PTT', '(Ninguna)', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(211, 'M1122B91B', 'EPURA 97X74MM BENEDETTIS P2425 2018/FEB-MZO BOBINA', 16, 119, 'PTT', '(Ninguna)', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(212, 'MCC001', 'MAQINA COLOCADORA DE SELLO CLAP', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(213, 'MUE001', 'TINTA GREEN CON MOLÃ‰CULA', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(214, 'MUE002', 'TINTA WARM RED CON MOLÃ‰CULA', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(215, 'MUE003', 'TINTA MAGENTA SELECCIÃ“N', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(216, 'MUE004', 'TINTA AMARILLO FRENTE', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(217, 'MUE005', 'TINTA CYAN FRENTE', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(218, 'MUE006', 'TINTA VERDE 375', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(219, 'MUE007', 'TINTA NEGRO SELEECIÃ“N', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(220, 'MUE009', 'TINTA ROSA PROCES', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(221, 'MUE010', 'TINTA MAGENTA SLEEVE', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(222, 'MUE011', 'TINTA VERDE 361', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(223, 'MUE012', 'LIMPIADOR', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(224, 'MUE013', 'SOLVEN BSED', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(225, 'MUE014', 'TINTA WARM RED', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(226, 'MUE015', 'TINTA COOL GRAY', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(227, 'MUE016', 'TINTA NEGRO SELEECIÃ“N', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(228, 'MUE017', 'TINTA ROJO 185', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(229, 'MUE018', 'TINTA PROCES BLUE', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(230, 'MUE019', 'RETARDANTE', 16, 0, 'COMPONENTES PARA TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(231, 'MUE020', 'TINTA WHITE SILVER', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(232, 'MUE022', 'TINTA NARANJA 021', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(233, 'MUE023', 'TINTA AZUL 2727', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(234, 'MUE024', 'TINTA VINO 7648', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(235, 'MUE025', 'TINTA AZUL 2925', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(236, 'MUE026', 'TINTA CYAN FRENTE', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(237, 'MUE027', 'TINTA NARANJA MEXII', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(238, 'MUE028', 'TINTA MORADO', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(239, 'MUE029', 'TINTA AZUL 072', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(240, 'MUE030', 'TINTA VIOLETA 513-C', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(241, 'MUE031', 'EXTENDER COCOA', 16, 0, 'DILUYENTES', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(242, 'MUE032', 'TINTA AZUL REFLEX', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(243, 'MUE033', 'TINTA NEGRO MANGAS', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(244, 'MUE034', 'TINTA AZUL SLEEVEN', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(245, 'MUE035', 'TINTA AMARILLO SLEEVE', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(246, 'MUE036', 'TINTA CLEAN PRINT', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(247, 'MUE037', 'TINTA VERDE BARRIL', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(248, 'MUE039', 'SOLVENTE SLOW', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(249, 'MUE040', 'TINTA AZUL SELEECCIÃ“N', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(250, 'MUE041', 'TINTA MAGENTA SELECCIÃ“N', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(251, 'MUE042', 'TINTA NARANJA BARRIL', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(252, 'MUE043', 'TINTA BLANCO SELECCIÃ“N', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(253, 'MUE044', 'BOPP BLANCO METALIZADO 600 MM MUESTRA', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(254, 'OBS001', 'ADHESIVO (BARRIL)', 16, 0, 'ADHESIVOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(255, 'OBS002', 'BARNIZ  (BARRIL)', 16, 0, 'TINTAS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(256, 'PEL002', 'POLIESTER TRANSPARENTE C/ EVIDENCIA', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(257, 'PEL003', 'POLIESTER TRANSPARENTE C/ ALTA EVIDENCIA', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(258, 'PEL004', 'POLIESTER S/ EVIDENCIA FLEXAMERICA 22CM', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(259, 'PEL005', 'POLIESTER C/EVIDENCIA 17CM', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(260, 'PEL006', 'POLIESTER TRATAMIENTO CORONA', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(261, 'PEL007', 'POLIESTER PLATA C/TRATAMIENTO CORONA 15CM', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(262, 'PEL008', 'POLIESTER CHINO C/EVIDENCIA 17CM', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(263, 'PEL010', 'POLIESTER HEXAGONOS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(264, 'PEL011', 'POLIESTER HOLOGRAMA OSTER', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(265, 'PEL012', 'POLIESTER DORADO EMBOSADO C/EVIDENCIA', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(266, 'PEL013', 'POLIESTER DORADO  C/EVIDENCIA', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(267, 'PEL014', 'POLIESTER DOREL', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(268, 'PEL015', 'POLYESTER CON BRILLOS cuadritos 680MM', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(269, 'PEL016', 'POLYESTER CON ONDAS 680 MM', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(270, 'PEL018', 'POLIESTER EMBOSADO 320MM *SANTORINI', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(271, 'PEL019', 'PETG  455 MM  50  MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(272, 'PEL021', 'PET 640 MM', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(273, 'PEL022', 'PET TRANSPARENTE 320 mm', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(274, 'PEL023', 'PETG BONDSET KLÃ–CNER PENT PLAS PVC   14 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(275, 'PEL024', 'PET TRANSPARENTE  680MM   14 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(276, 'PEL025', 'PET TRANSPARENTE 600MM CONT.#14   14 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(277, 'PEL026', 'PET PLATA 680MM  11 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(278, 'PEL027', 'BOPP TRANSPARENTE 680MM DELGADO, 20 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(279, 'PEL028', 'BOPP BLANCO 35X1370 ALTO CON #12,#13,  35 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(280, 'PEL029', 'BOPP  BLANCO/METALIZADO 600 MM CONT. 14 36 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(281, 'PEL030', 'BOPP BLANCO 600MM CONT. #14   36 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(282, 'PEL031', 'OVERLAY PLAMI CAL.2 53CM ANCHO 66 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(283, 'PEL032', 'OVERLAY CPPC 580MM CAL. 0.051 ,  66 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(284, 'PEL033', 'OVERLAY CPPC 525MM CAL. 0.051  , 66 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(285, 'PEL034', 'OVERLAY CPPC 580 MM CAL 0.076  ,  .066 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(286, 'PEL035', 'CAPLE 1200 MM (1100 KG)   18PTOS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(287, 'PEL036', 'COUCHE AUTOADHERIBLE CH.  1100 MM  106 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(288, 'PEL037', 'COUCHE AUTOADHERIBLE 32 CM 1500 mm', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(289, 'PEL041', 'PVC CH. 600MM CON. #11   0.45 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'TERMOENCOGIBLE', '(Ninguna)', '13111218', '', '', 1),
(290, 'PEL043', 'STYCKY BACK 3M E1115H18\" (GRUESO)', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(291, 'PEL049', 'BOPP PLATA 6OOMM #14  40MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(292, 'PEL050', 'LYNER SCK SILICONADO 67GSM X 17  .054MICRAS', 16, 49.3, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(293, 'PEL051', 'CARTON STARSPARK 16P 30 ANCHO 53KG  .405MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(294, 'PEL052', 'BOB. OVERLAY PARA.PRUEBA 44.60K 485MM', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(295, 'PEL053', 'BIOSEAL TSI 35mi. (AMIRA TRN)615MMLONG2000M', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(296, 'PEL065', 'PET-G 450 MM 35 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(297, 'PEL066', 'PET-G 400 MM 35 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(298, 'PEL067', 'PAPEL COUCHE BLANCO 255 MM', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(299, 'PEL068', 'CARTULINA BLANCA 140 MM', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(300, 'PEL069', 'PAPEL COUCHE AUTOADHERIBLE 140 MM', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(301, 'PEL070', 'BOPP TRANSPARENTE 600 MM 28 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(302, 'PEL071', 'PAPEL BOND BLANCO 250 MM', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(303, 'PEL072', 'PAPEL SILICONADO 175 MM', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(304, 'PEL073', 'PET PLATA 600 MM 38 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(305, 'PEL074', 'STICKY BACK AZUL 5.4EBXDE18\"X25YARDAS(0.46X23M) 0.02 ESP.', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(306, 'PEL075', 'STICKY BACK ROSA 5.2EBX 18X25YA(043X23M) ESPESOR 0.020MILE S', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(307, 'PEL076', '17342 BOPP TRANS. AUTOADHERIBLE CON BASE TRANS. 590MM 1524MT', 16, 0, 'SUSTRATOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(308, 'PEL077', '17342 BOPP TRANS. AUTOADHERIBLE CON BASE TRANS. 330MM 1524MT', 16, 0, 'SUSTRATOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(309, 'PEL078', '17232 BOPP BLANCO PERLECENTE ADHESIVO 525MM 1524MTS', 16, 0, 'SUSTRATOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(310, 'PRUEBA1', 'PRUIEBA1', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(311, 'PT004006B', 'QUINDIO 100X15 MM BOBINA', 0, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(312, 'PT0209004B', 'GENERIX HOLOGRAMA 25X15 MM BOBINA', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(313, 'PT0209006B', 'INFASA HOLOGRAMA 15X 10 MM BOBINA', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(314, 'PT0209007B', 'NATURCOL HOLOGRAMA 20X12 MM BOBINA', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(315, 'PT0209008B', 'SONOFI-AVENTIS HOLOGRAMA 22X22 MM', 0, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(316, 'PT0209009B', 'ANTIVET HOLOGRAMA 20 X 30 MM', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(317, 'PT0209010B', 'FUMIREY HOLOGRAMA 22x22 MM', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(318, 'PT039002', 'CO2 SELLO PEPSI SAN LUIS 105X120 MM S/H', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(319, 'PT039003', 'CO2 SELLO PEPSI ALTAMIRA 105X120 MM S/H', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(320, 'PT054003', 'ETIQUETAS   AUTOADHESIVAS BLANCAS CON MM 42X12XMM', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '55121600', '', '', 1),
(321, 'RAS001', 'RASQUETA 45 x 0.50 CONTRA', 16, 0, 'RASQUETA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(322, 'RAS002', 'RAS MBLADE 25X020 SOFT (FLEXO)S/FILO 50MT.CJA.', 16, 0, 'RASQUETA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(323, 'RAS003', 'RAS FLEXO 30x0.20x0.125 ESTANDAR 100MT.CJA.C/FILO FLE', 16, 0, 'RASQUETA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(324, 'RAS004', 'RAS IMPR MULTIBLADE 50x0.20/V5Â°(ROTO).100MT  CON FILO', 16, 0, 'RASQUETA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '45101612', '', '', 1),
(325, 'RAS005', 'RAS 50 X 0.15 FLEXOLIFE SIN FILO (ROTO) 100MT.', 16, 0, 'RASQUETA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(326, 'RAS006', 'RAS 50X0.15 SOFT-FLXLIFE NIQUELADA 10MT', 16, 0, 'RASQUETA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(327, 'RAS007', 'RAS 30MM X .7MM X 5M MICRO TIP 8 (NARANJA)', 16, 0, 'RASQUETA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(328, 'RAS008', 'RASQUETA 50 X .20/.125 SOFT', 16, 0, 'RASQUETA', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(329, 'REC001', 'RECY05005 W200 (LAVADOR DE RODILLOS)', 16, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(330, 'REC002', 'RECY05003 QUICK WASH PREMIUM(GARRAFON 20LTS. LIMPIADOR DE G)', 16, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(331, 'RIB001', 'RIB AWR-470 110 MM X 300 MM', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '44103100', '', '', 1),
(332, 'RIB002', 'RIB GWR-WAY 110X450MM', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '44103100', '', '', 1),
(333, 'RIB003', 'RIB TR 5050 110 MM X 50METROS', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '44103100', '', '', 1),
(334, 'RIB004', 'RIB IMPRESIÃ“N CERA  (110mmX74METROS.) resina especial', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '44103100', '', '', 1),
(335, 'RIB005', 'RIB IMPRESIÃ“N RESINA P/110Xi4 (25mmx300METROS)', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '44103100', '', '', 1),
(336, 'RIB006', 'RIB AXR9 40X300METROS', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '44103100', '', '', 1),
(337, 'RIB007', 'RIBBON 110X74 MM CERA', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '44103100', '', '', 1),
(338, 'RMC001', 'VENTOSA DE SUCCION S-PGN116', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '45101700', '', '', 1),
(339, 'RMC002', 'GENERADOR DE VACIO PISCO VUH07-U10', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '45101700', '', '', 1),
(340, 'RMC003', 'JUEGO DE NAVAJAS M-6 2 PZAS', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '27111504', '', '', 1),
(341, 'RMC004', 'CILINDRO CORTADOR  MQ3673-0004', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '45101700', '', '', 1),
(342, 'RMC005', 'KIT CONTADOR CBK-101114-12M6', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(343, 'RMC006', 'ESTRELLA PUNTEADORA SKS-7', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '45101700', '', '', 1),
(344, 'RMC007', 'FUNNEL GUIA METALICA 108X75 MM', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(345, 'RMC008', 'PANEL PLC PX-1S-14MT', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '45101700', '', '', 1),
(346, 'RMC009', 'SEGURO PARA VENTOSA', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '45101700', '', '', 1),
(347, 'RMC010', 'LAINA/LAMINA AJUSTADORA RBS M5/M6', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '45101700', '', '', 1),
(348, 'RMC011', 'KIT PUNTEADOR LASSER', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '45101700', '', '', 1),
(349, 'RMC012', 'MARIPOSA METALICA 97 MM', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(350, 'RMC013', 'MARIPOSA METALICA 108 MM', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(351, 'RMC014', 'MARIPOSA METALICA 86 MM', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(352, 'RMC015', 'VLAVULA DE AIRE NV25123-5DZ-01T', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(353, 'RMC016', 'AJUSTADOR DE SELLOS ( SKY )', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(354, 'RMC017', 'BANDA DE RETENCION RODILLO BLANCO BRS M-625', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(355, 'RMC019', 'PANTALLA MCA FX-10DU CLAP', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(356, 'RMC020', 'TRANFORMADOR TM3PSI-XTI CLAP', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(357, 'RMC021', 'KIT LASER SENSOR', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(358, 'RMC022', 'JUEGO DE RODILLOS LNMT BCO', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '82141500', '', '', 1),
(359, 'RMC023', 'MARCO BISELADO GUIA DE SELLO', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(360, 'RMC024', 'SOPORTE CON LAMINA BASE VENTOSA', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(361, 'RMC25', 'FUNNEL GUIA METALICA 97MM', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '45101700', '', '', 1),
(362, 'ROD001', 'FINGER PRINT  (calibradores)', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(363, 'ROD002', 'BONAFONT', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(364, 'ROD003', 'CUADRICULA', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(365, 'ROD004', 'GUANAJUATO', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(366, 'ROD005', 'LICORERA INDUSTRIAL DEL VALLE', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(367, 'ROD006', 'ROD006', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(368, 'ROD007', 'PEPSI PLANTA SLP', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(369, 'ROD008', 'EPURA', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(370, 'ROD009', 'EPURA', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(371, 'ROD010', 'GATORADE', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(372, 'ROD011', 'PLASTA COMPLETA', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(373, 'ROD012', 'PLASTA 2 DIVISIONES', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(374, 'ROD013', 'PLASTA 8 DIVISIONES', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(375, 'ROD014', 'PLASTA 4 DIVISIONES', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(376, 'ROD015', 'PLASTA 6 DIVISIONES', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(377, 'ROD027', 'JUNGANNS 97 X 68 MM', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(378, 'ROD031', 'CHIVACOLA 209X95 MM POR EL FRENTE', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(379, 'ROD032', 'CHIVA COLA 600 PARTE DE ATRÃS', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(380, 'ROD033', 'CHIVA COLA 600 ML N.-1 MAL', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(381, 'ROD034', 'CHIVA COLA NUEVOS AMA,MAG,NEGRO', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(382, 'ROD035', 'CHIVA COLA NUEVO JUEGO #5 REVERSO', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(383, 'ROD036', 'CHIVA COLA PLASTA NUEVO FRENTE #6 JUEGO', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(384, 'ROD037', 'CHIVA COLA 3 RODILLOS JGO#3 2 CON GOLPE EN EL FILO', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1);
INSERT INTO `elementosconsumo` (`idElemento`, `identificadorElemento`, `nombreElemento`, `impuesto`, `precio`, `clasificacion1`, `clasificacion2`, `clasificacion3`, `clasificacion4`, `clave_sat`, `foto`, `unidad`, `baja`) VALUES
(385, 'ROD040', 'SANTORINI 97X74', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(386, 'ROD041', 'SANTORINI 97X74 Q3', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(387, 'ROD043', 'EPURA 108X75 Q3', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(388, 'ROD044', 'ELECTROPURA 108X75 Q3', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(389, 'ROD045', 'SANTORINI 97X68  Q3', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(390, 'ROD046', 'LIMONADA', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(391, 'ROD047', 'BLU INGLES JUEGO#1', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(392, 'ROD048', 'BLU BOTELLAS 1.5L', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(393, 'ROD049', 'BLU DESCRIPCION EN ESPAÃ‘OL JUEGO #2', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(394, 'ROD050', 'CRISTAL GOTA 05/09/2016 NUEVO', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(395, 'ROD051', 'CRISTAL ESTRELLA 05/09/2016 NUEVO', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(396, 'ROD052', 'CRISTAL CON PLASTA 12/09/2016', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(397, 'ROD053', 'CRISTAL GOTA 12/09/2016', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(398, 'ROD054', 'CRISTAL ESTRELLA 521MM 23/09/2016 NUEVO', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(399, 'ROD055', 'CRISTAL ESTRELLA     11/10/2016 NUEVO', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(400, 'ROD056', 'CRISTAL GOTA           11/10/2016 NUEVO', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(401, 'ROD057', 'EGO 10 FRENTE', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(402, 'ROD058', '7UP BOTELLA 600ML 640MMX21M11/08/16', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(403, 'ROD059', '7UP BOTELLA 500ML 640MMX191 DIA.     15/08/16', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(404, 'ROD060', 'CARIBE COOLER DURAZNO  19/08/16 NUEVO', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(405, 'ROD061', 'CARIBE COOLER 520 TINTO  12/09/2016 NUEVO', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(406, 'ROD062', 'CARIBE COOLER DURAZNO 27/09/2016 NUEVO', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(407, 'ROD063', 'CARRIBE COOLER DURAZNO JGO. 3  28/09/2016 NUEVO', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(408, 'ROD064', 'SUAJES PERRITO PRESENTACION ACTUAL', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(409, 'ROD067', 'SANTORINI 97X68 Q2', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(410, 'ROD068', 'SANTORINI 97X74 Q2', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(411, 'ROD069', 'EPURA 97X74 Q2', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(412, 'ROD070', 'EPURA 108X75 Q2', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(413, 'ROD071', 'ELECTROPURA 108X75 Q2', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(414, 'ROD072', 'CRISTAL ESTRELLA RODILLOS C/LEYENDA', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(415, 'ROD073', 'CRISTAL GOTA', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(416, 'ROD074', 'CARIBE DURAZNO OUTLINE', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(417, 'ROD075', 'BLU 1.5 LTS', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(418, 'ROD076', 'BEVI', 16, 0, 'CILINDROS GRABADOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '60121713', '', '', 1),
(419, 'ROT001', 'G12Z-267 AMARILLO ENTONADOR ALCAN:FJ31', 16, 0, 'TINTAS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '12171703', '', '', 1),
(420, 'ROT002', 'HS1L 500213:G15Z-337RF: AZUL2727 PVC :FJ31', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(421, 'ROT003', 'AZUL 2728-C', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(422, 'ROT004', 'G15Z-429RF:PURPURA 229 P:FJ31', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(423, 'ROT005', 'G15Z-428RF:VERDE 361PVC:FJ31', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(424, 'ROT008', 'PURPURA MEDIO GIGZ-053RF', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(425, 'ROT009', 'PURPURA (2405 ROSA)', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(426, 'ROT010', 'G16Z-102RF ROJO 186 CHIV:FJ31', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BOPP BLANCO', '12171703', '', '', 1),
(427, 'ROT011', 'G16Z-103RF AZUL 295 CHIV:FJ31', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BOPP BLANCO', '12171703', '', '', 1),
(428, 'ROT012', 'G16Z-104RF GRIS 423 EGO:FJ31', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BOPP BLANCO', '12171703', '', '', 1),
(429, 'ROT013', 'G16Z-105RF ROSA 239 EGOR:FJ31', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BOPP BLANCO', '12171703', '', '', 1),
(430, 'ROT014', 'G16Z-106RF ROSA 241 EGOR:FJ31', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BOPP BLANCO', '12171703', '', '', 1),
(431, 'ROT015', 'G16Z-106RF MORADO 527 EGOR:FJ31', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BOPP BLANCO', '12171703', '', '', 1),
(432, 'ROT016', 'ROJO COCA ROTO', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BOPP BLANCO', '12171703', '', '', 1),
(433, 'ROT017', 'AZUL 7462 Q4-2017', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(434, 'RTC001', 'RESISTENCIA TUBULAR PARA TUNEL DE CALOR CLAP-6000', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '45101700', '', '', 1),
(435, 'RTC002', 'TERMOSTATO CHROMATROL SWITCH - 252 -266394', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(436, 'SOL001', 'MEZCLA 70/30 (P.E.R.: 0.860 kg)', 16, 0, 'SOLVENTES', '(Ninguna)', '(Ninguna)', '(Ninguna)', '12352400', '', '', 1),
(437, 'SOL002', 'MEZCLA R-33  (P.E.R.: 0.850 kg)', 16, 0, 'SOLVENTES', '(Ninguna)', '(Ninguna)', '(Ninguna)', '12352400', '', '', 1),
(438, 'SOL003', 'ISOPROPANOL (P.E.R.: 0.790 kg) (ALCOHOL ISOPROPILICO)', 16, 0, 'SOLVENTES', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(439, 'SOL004', 'TOLUENO  (P.E.R.: 0.866 kg)', 16, 0, 'SOLVENTES', '(Ninguna)', '(Ninguna)', '(Ninguna)', '12352005', '', '', 1),
(440, 'SOL006', 'AC-DE N-PROPILO (P.E.R.: 0.836 kg)', 16, 0, 'SOLVENTES', '(Ninguna)', '(Ninguna)', '(Ninguna)', '12191602', '', '', 1),
(441, 'SOL007', 'ACETATO DE ETILO (P.E.R.: 0.902 kg)', 16, 0, 'SOLVENTES', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(442, 'SOL008', 'ALCOHOL N-PROPANOL  (P.E.R.: 0.790 kg)', 16, 0, 'SOLVENTES', '(Ninguna)', '(Ninguna)', '(Ninguna)', '12191601', '', '', 1),
(443, 'SOL009', 'CICLOHEXANONA  (P.E.R.: 0.950 kg)', 16, 0, 'SOLVENTES', '(Ninguna)', '(Ninguna)', '(Ninguna)', '12191602', '', '', 1),
(444, 'SOL010', 'TETRAHIDROFURANO  (P.E.R.: 0.890 kg)', 16, 0, 'SOLVENTES', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(445, 'SOL011', 'MEK (P.E.R.: 0.805 kg)', 16, 0, 'SOLVENTES', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(446, 'SOL013', 'LAVADOR DE RODILLOS TECPRO Wash A230', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(447, 'SOL014', 'HUECO QUICK WASH PREMIUM LAVADOR ANILOX', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(448, 'SOL016', 'ACETONA', 16, 0, 'SOLVENTES', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(449, 'SOL017', 'XILOL', 16, 0, 'SOLVENTES', '(Ninguna)', '(Ninguna)', '(Ninguna)', '12191501', '', '', 1),
(450, 'SOL018', 'MEZCLA 80/20 (80% ALCOHOL, 20% ACETATO)', 0, 0, 'SOLVENTES', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(451, 'SOL019', '1,3 DIOXOLANE ULTRA PURO', 16, 0, 'SOLVENTES', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(452, 'SOL020', 'TOW05002 SMARTFLEX WASH PLUS', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(453, 'SOL021', 'SMARTFLEXO HIGH TECH ANILOX (LIMPIADOR) BASE SOLVENTE - UV A', 16, 0, 'INSUMOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(454, 'SOL022', 'ACETATO DE ETILO GRADO URETANO', 16, 0, 'SOLVENTES', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(455, 'SOL023', 'SULFATO DE NIQUEL SOLUCION A', 0, 0, 'SOLVENTES', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(456, 'SOL024', 'ANODOS DE NIQUEL PELLETS INCO', 0, 0, 'SOLVENTES', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(457, 'SUS001', 'POLIESTER METALIZADO ALTA EVIDENCIA  .27 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(458, 'SUS002', 'POLIESTER METALIZADO CON EVIDENCIA .27 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(459, 'SUS003', 'POLIESTER EMBOSADO 320MM* EPURA CONT.#12,#13', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(460, 'SUS005', 'POLIESTER EMBOSADO 320MM  *ELECPURA (DISEÃ‘O GRANDE)', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(461, 'SUS007', 'PVC CH. 450 MM  35 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'TERMOENCOGIBLE', '(Ninguna)', '13111218', '', '', 1),
(462, 'SUS008', 'PVC CH. 400 MM  35 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'TERMOENCOGIBLE', '(Ninguna)', '13111218', '', '', 1),
(463, 'SUS009', 'TYLEX      16.5 alto por 9.7 kg   .050 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(464, 'SUS010', 'BOPP BLANCO METALIZADO 600 MM 25 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(465, 'SUS013', 'PET-G TRANSPARENTE 425 mm', 16, 0, 'SUSTRATOS', '(Ninguna)', 'TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(466, 'SUS014', 'EMBOSADO CRISTAL GOTA 320 MM', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(467, 'SUS015', 'EMBOSADO CRISTAL ESTRELLA 320 MM', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(468, 'SUS016', 'PET-G YUNCHENG MUESTRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(469, 'SUS019', 'PET-G 455 MM  PELPLAST 45 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(470, 'SUS020', 'PET-G 400 mm 40 micras', 16, 0, 'SUSTRATOS', '(Ninguna)', 'TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(471, 'SUS021', 'BOPP BLANCO AUTOADHERIBLE 60 MICRAS 120 MM', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '13111200', '', '', 1),
(472, 'SUS022', 'PET-G 310 MM 45 MICRAS', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(473, 'SUS023', 'FKT PAPEL METALIZADO 83 GRS SIN ADHESIVO', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '14121800', '', '', 1),
(474, 'SUS024', 'PVC 373 MM 35-40 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(475, 'SUS025', 'PET PLATA 170 MM 38 MICRAS', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(476, 'SUS026', 'PVC 400 MM (REPROCESOS)', 16, 0, 'SUSTRATOS', '(Ninguna)', 'TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(477, 'SUS027', 'PVC 450 MM (REPROCESOS)', 16, 0, 'SUSTRATOS', '(Ninguna)', 'TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(478, 'SUS028', 'PET-G 455 MM (REPROCESOS)', 16, 0, 'SUSTRATOS', '(Ninguna)', 'TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(479, 'SUS029', 'PVC 601MM 35MICRAS PELPLAST', 16, 0, 'SUSTRATOS', '(Ninguna)', 'TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(480, 'SUS031', 'BOPP BLANCO 25 MICRAS 830 MM', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(481, 'SUS032', 'BOPP BLANCO 25 MICRAS 1200MM', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(482, 'SUS033', 'BOPP BLANCO 25 MICRAS 20.5CM', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(483, 'SUS034', 'BOPP BLANCO 25 MICRAS 55CM', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(484, 'SUS035', 'BOPP BLANCO 25 MICRAS 44.5CM', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(485, 'SUS037', 'BOPP BLANCO 25 MICRAS 40CM', 16, 0, 'SUSTRATOS', '(Ninguna)', 'NO TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(486, 'SUS038', 'PPT25P PELICULA DE POLIPROPILENO DE 25 MICRAS TRANSPARENTE B', 0, 0, 'SUSTRATOS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(487, 'SUS039', 'PPT-50-CP-PST PELICULA DE POLIPROPILENO DE 50 MICRAS TRANS B', 0, 0, 'SUS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(488, 'SUS30', 'PVC 667MM 35MICRAS PELPLAST', 16, 0, 'SUSTRATOS', '(Ninguna)', 'TERMOENCOGIBLE', '(Ninguna)', '', '', '', 1),
(489, 'TABMADERA', 'TABLA DE MADERA', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '24112901', '', '', 1),
(490, 'TAPAZUL', 'TAPA PLASTICO REDONDA', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '24122004', '', '', 1),
(491, 'TCC001', 'TUNEL DE CALOR CLAP', 16, 0, '(Ninguna)', 'VENTAS', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(492, 'TFL001', 'HMC ACE BW8 OPAQUE WHITE', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BASE AGUA', '', '', '', 1),
(493, 'TFL002', 'HMC3-0080-4730 PROC MAGENTA (FLINT)', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BASE AGUA', '', '', '', 1),
(494, 'TFL003', 'HMC1-0081-473U PROC YELLOW (FLINT)', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BASE AGUA', '', '', '', 1),
(495, 'TFL004', 'HMC5-0080-473U PROCESS CYAN (FLINT)', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BASE AGUA', '', '', '', 1),
(496, 'TFL005', 'HMC8-0080-473U PROCESS BLACK  (FLINT)', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BASE AGUA', '', '', '', 1),
(497, 'TFL006', 'G13Z-107F BLANCO FLEXO PVC:FJ31', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BASE SOLVENTE', '', '', '', 1),
(498, 'TFL007', 'G16Z-028FF NEGRO PVC FLEX', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BASE SOLVENTE', '12171703', '', '', 1),
(499, 'TFL008', 'G16Z-027FF CYAN PVC FLEX', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BASE SOLVENTE', '12171703', '', '', 1),
(500, 'TFL009', 'G16Z-025FF AMARILLO PVC FLEX', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BASE SOLVENTE', '12171703', '', '', 1),
(501, 'TFL010', 'G16Z-026FF MAGENTA PVC FLEX', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BASE SOLVENTE', '12171703', '', '', 1),
(502, 'TFL016', 'AMARILLO SELECCIÃ“N FLEXO (BOPP BLANCO)', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BOPP BLANCO', '12171703', '', '', 1),
(503, 'TFL017', 'NEGRO SELECCIÃ“N FLEXO (BOPP BLANCO)', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BOPP BLANCO', '12171703', '', '', 1),
(504, 'TFL018', 'MAGENTA SELECCIÃ“N FLEXO (BOPP BLANCO)', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BOPP BLANCO', '12171703', '', '', 1),
(505, 'TFL019', 'CYAN SELECCIÃ“N FLEXO (BOPP BLANCO)', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BOPP BLANCO', '12171703', '', '', 1),
(506, 'TFL020', 'EXTENDER  FLEXO (BOPP BLANCO)', 16, 0, 'DILUYENTES', '(Ninguna)', 'FLEXOGRAFIA', 'BOPP BLANCO', '12171703', '', '', 1),
(507, 'TFL021', 'AMARILLO PROCESS TSP-1488', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BASE AGUA', '12171703', '', '', 1),
(508, 'TFL022', 'MAGENTA PROCESS TSP-1489', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BASE AGUA', '12171703', '', '', 1),
(509, 'TFL023', 'CYAN PROCESS TSP-1490', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BASE AGUA', '12171703', '', '', 1),
(510, 'TFL024', 'NEGRO PROCESS TSP-1491', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BASE AGUA', '12171703', '', '', 1),
(511, 'TFL025', 'RETARDADOR LA-602', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BASE AGUA', '31211600', '', '', 1),
(512, 'TFL026', 'ESTABILIZADOR LA-601', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', 'BASE AGUA', '31211600', '', '', 1),
(513, 'TIN001', 'BARNIZ RELEASE FRENT', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(514, 'TIN002', 'BARNIZ MATE AGUA BMA-506', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', '(Ninguna)', '12171703', '', '', 1),
(515, 'TIN003', 'BARNIZ SUPER GLOSS LIBRASSS-501', 16, 0, 'TINTAS', '(Ninguna)', 'FLEXOGRAFIA', '(Ninguna)', '12171703', '', '', 1),
(516, 'TIN004', 'G16Z-086 RF:BARNIZ S/IMPRESIÃ“N F:J31', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(517, 'TIN005', 'G16Z-085 RF:PRIMER DE ANCLAJE F:J31', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(518, 'TIN006', 'BASE ROJO AMARILLENTO G11Z-003', 0, 0, 'TINTAS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(519, 'TIN007', 'BASE ROJO RUBI AZULOSA G18Z-110R', 0, 0, 'TINTAS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(520, 'TLA001', 'COCA RED LAM:FJ31 G18Z-088RL', 16, 0, 'TINTAS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(521, 'TLA002', 'COCA BLACK LAM:FJ31 G18Z-089RL', 16, 0, 'TINTAS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(522, 'TLA003', 'BLANCO BOPP ROTO LAM:FJ31 G18Z-099RL', 16, 0, 'TINTAS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(523, 'TLA004', 'SUNLAM SB ADH LX8052:PAIL25 LX-8052', 0, 0, 'TINTAS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(524, 'TMN001', 'ROJO COCA MCR:FJ31 G17Z-110RF', 16, 0, 'TINTAS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(525, 'TMN002', 'NEGRO COCA MN:FJ31 G17Z-145RF', 0, 0, 'TINTAS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(526, 'TMN003', 'CYAN CRISTAL:FJ31 G18Z-002RL', 16, 0, 'TINTAS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(527, 'TMN004', 'REFLEX CRISTAL:FJ31 G18Z-003RL', 16, 0, 'TINTAS', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(528, 'TRE009', 'AMARILLO SELECCIÃ“N (RETORNO)', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(529, 'TRE011', 'TINTA AZUL 2945 (RETORNO)', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(530, 'TRE016', 'AZUL 286 (RETORTNO)', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(531, 'TRE018', 'PLATA', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(532, 'TRE020', 'COOL GRAY 3', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(533, 'TRE022', 'CYAN SELECCIÃ“N (RETORNO)', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(534, 'TRE027', 'BARNIZ MATE', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(535, 'TRE033', 'BARNIZ DE IMPRESION', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(536, 'TRE034', 'PRIMER DE ANCLAJE', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(537, 'TRE042', 'TAMBO (A) GRIS', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(538, 'TRE043', 'AZUL TAMBO REFLEX  ( C )  lot. TAM05082016ARC', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(539, 'TRE049', 'TAMBO NEGRO SELECCIÃ“N', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(540, 'TRE050', 'TAMBO 1 ROJO', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(541, 'TRE051', 'TAMBO 2 GRIS', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(542, 'TRE052', 'TAMBO 3 ROJO MARRON', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(543, 'TRE053', 'TAMBO 6 ROJO', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(544, 'TRE054', 'TAMBO 5 ROJO', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(545, 'TRE055', 'TAMBO MAGENTA', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(546, 'TRE056', 'TAMBO 14 ROJO', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(547, 'TRE057', 'TAMBO 21 AZUL OSCURO', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(548, 'TRE058', 'TAMBO 9 MORADO', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(549, 'TRE059', 'TAMBO 13 AZUL', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(550, 'TRE060', 'TAMBO 16 AZUL', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'FLEXOGRAFIA', '(Ninguna)', '12171703', '', '', 1),
(551, 'TRE062', 'TAMBO 10 MORADO', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(552, 'TRE063', 'TAMBO 25 AZUL', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'FLEXOGRAFIA', '(Ninguna)', '12171703', '', '', 1),
(553, 'TRE064', 'TAMBO 12 VERDE', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(554, 'TRE065', 'TAMBO CYAN SELECCIÃ“N', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(555, 'TRE066', 'TAMBO 27 VERDE/AMARILLO', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(556, 'TRE067', 'TINTA BLANCO (RETORNO)', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(557, 'TRE068', 'PANTONE AMARILLO 123 (RETORNO)', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(558, 'TRE069', 'PANTONE ROJO 186 (RETORNO)', 0, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(559, 'TRE070', 'PANTONE 361 (RETORNO)', 0, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(560, 'TRE071', 'TINTA MAGENTA SELECCIÃ“N (RETORNO)', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(561, 'TRE072', 'TINTA 2425 (RETORNO)', 0, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(562, 'TRE073', 'TINTA 295 (RETORNO)', 0, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(563, 'TRE074', 'TINTA VERDE 368 (RETORNO)', 0, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(564, 'TRE075', 'NEGRO SELECCIÃ“N (RETORNO)', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(565, 'TRE076', 'TINTA WARM RED (RETORNO)', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(566, 'TRE077', 'TINTA AZUL 305 (RETORNO)', 0, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(567, 'TRE078', 'PANTONE AZUL 2935 (RETORNO)', 0, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(568, 'TRE079', 'PANTONE AZUL 2147 (RETORNO)', 0, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(569, 'TRE080', 'TINTA PURPURA 2597 (RETORNO)', 0, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(570, 'TRE081', 'TAMBO AMARILLO SELECCIÃ“N', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(571, 'TRE082', 'TAMBO BLANCO SOLVENTADO', 0, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(572, 'TRE083', 'TAMBO AZUL 2945', 0, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(573, 'TRE084', 'TAMBO NEGRO SELECCIÃ“N 2', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(574, 'TRE085', 'AMARILLO PYMAFI', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(575, 'TRE086', 'AZUL 2190 LAMINACION', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(576, 'TRE087', 'NEGRO COCA LAMINACIÃ“N', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(577, 'TRE088', 'AZUL REFLEX PARA LAMINACIÃ“N', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(578, 'TRE089', 'AMARILLO 106 PYMAFI', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(579, 'TRE090', 'AMARILLO PYMAFI', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(580, 'TRE091', 'TRATAMIENTO CORONA CON SOLVENTE', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(581, 'TRE092', 'PANTONE AMARILLO 106 (RETORNO)', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(582, 'TRE093', 'NEGRO SELECCIÃ“N PARA BOPP', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(583, 'TRE094', 'VERDE 334 CHIVACOLA', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(584, 'TRE095', 'VERDE 361 BOPP BLANCO', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(585, 'TRE096', 'AMARILLO PROCESS BOPP BLANCO', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(586, 'TRE097', 'VERDE 364 BOPP BLANCO', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(587, 'TRE098', 'MORADO 527 BOPP BLANCO', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(588, 'TRE099', 'AZUL 287 EPURA 10 LTS', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(589, 'TRE100', 'BARNIZ RELEASE CHIVACOLA', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(590, 'TRE101', 'BARNIZ ORILLA ROTO CON POLVO OPT', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(591, 'TRE102', 'AZUL REFLEX ROTO', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(592, 'TRE103', 'TAMBO 11 TINTA FLEXO', 0, 0, '(Ninguna)', '(Ninguna)', '(Ninguna)', '(Ninguna)', '', '', '', 1),
(593, 'TRE104', 'ROJO 185 (RETORNO)', 0, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '', '', '', 1),
(594, 'TRE105', 'AZUL 7712 (RETORNO)', 0, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '', '', '', 1),
(595, 'TRE106', 'PURPURA 226 (RETORNO)', 0, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '', '', '', 1),
(596, 'TRE107', 'AZUL 534 (RETORNO)', 0, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '', '', '', 1),
(597, 'TRE108', 'NARANJA 165 (RETORNO)', 0, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '', '', '', 1),
(598, 'TRE109', 'TAMBO 29 MORADO', 0, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '', '', '', 1),
(599, 'TRE110', 'TAMBO 28 AZUL OSCURO', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '', '', '', 1),
(600, 'TRE111', 'TAMBO 33 AZUL', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(601, 'TRE76', 'TINTA AZUL 294 (RETORNO)', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(602, 'TRO002', 'TAMBO PURPURA P-2395 (Q1,Q3) (MEZCLA INTERNA)', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(603, 'TRO004', 'P-1375 PYMAFI NARANJA (MEZCLA INTERNA)', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(604, 'TRO005', 'P-1255 PYMAFI (MEZCLA INTERNA)', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(605, 'TRO007', 'P-396 PYMAFI (MEZCLA INTERNA)', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(606, 'TRO008', 'P-7628 PYMAFI (MEZCLA INTERNA)', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(607, 'TRO009', 'P- 186 PYMAFI (MEZCLA INTERNA)', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(608, 'TRO014', 'NEGRO COCA', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(609, 'TRO015', 'TINTA ROTO BLANCO ROTO GBW', 16, 0, 'TINTAS DE RETORNO', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(610, 'TRO019', 'G13Z-279RF:BLANCO ALTO COF PVC : FJ31', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(611, 'TRO020', 'CYAN SELECCIÃ“N PVC ROTO', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(612, 'TRO021', 'MAGENTA SELECCIÃ“N PVC ROTO', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(613, 'TRO022', 'AMARILLO SELECCIÃ“N PVC ROTO', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(614, 'TRO023', 'NEGRO SELECCIÃ“N PVC ROTO', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(615, 'TRO024', 'G12Z-271 BASE ROJO 28 HEPTACROM:FJ31', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(616, 'TRO025', 'G14Z 130 FF BASE RODHAMINA :FJ31', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(617, 'TRO026', 'G14Z-128 FF BASE ROJO LACA :FJ31', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(618, 'TRO027', 'G14Z-129 FF BASE VIOLETA  CONC: FJ31', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(619, 'TRO028', 'G12Z-276 BASE NEGRO FLEX:FJ31', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(620, 'TRO029', 'BASE CYAN SLOW', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(621, 'TRO030', 'AZUL 534', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(622, 'TRO031', 'ROJO 180-C PVC:FJ31 (Q4,Q2)', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(623, 'TRO032', 'AZUL 2945 PVC:FJ31 (Q4,Q2, GOTA)', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(624, 'TRO034', 'TINTA PANTONE AZUL 286 (TAMBO CRISTAL ESTRELLA)', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(625, 'TRO035', 'TINTA PANTONE COOL GRAY 3 (TAMBO CRISTAL ESTRELLA)', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(626, 'TRO036', 'TINTA PANTONE AZUL 2935 (TAMBO CRISTAL ESTRELLA)', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(627, 'TRO037', 'TINTA PANTONE AZUL 294 (TAMBO CRISTAL GOTA)', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(628, 'TRO038', 'TINTA PANTONE AZUL 305 (TAMBO CRISTAL GOTA)', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(629, 'TRO040', 'BLANCO ROTO GRAFIS', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(630, 'TRO042', 'BASE MAGENTA', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(631, 'TRO048', 'PANTONE PURPURA 2395 (MEZCLA INTERNA) Q3BK', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(632, 'TRO049', 'AMARILLO PROCES ETQ:FJ31 (BOPP BLANCO ROTO)', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BOPP BLANCO', '12171703', '', '', 1),
(633, 'TRO050', 'MAGENTA PROCES ETQ:FJ31 (BOPP BLANCO ROTO)', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BOPP BLANCO', '12171703', '', '', 1),
(634, 'TRO051', 'CYAN PROCES ETQ:FJ31 (BOPP BLANCO ROTO)', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BOPP BLANCO', '12171703', '', '', 1),
(635, 'TRO052', 'NEGRO PROCES ETQ:FJ31 (BOPP BLANCO ROTO)', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BOPP BLANCO', '12171703', '', '', 1),
(636, 'TRO053', 'EXTENDER ETIQ ROTO:FJ31 (BOPP BLANCO ROTO)', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BOPP BLANCO', '12171703', '', '', 1),
(637, 'TRO057', 'BLANCO ROTO FRENTE', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(638, 'TRO058', 'BARNIZ ORILLA ROTO G17Z-150RF', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(639, 'TRO059', 'EBECRYL 350', 16, 0, 'COMPONENTES PARA TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '', '', '', 1),
(640, 'TRO060', 'BASE BLANCA CONC:FJ31', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(641, 'TRO061', 'BLANCO BARRIL', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(642, 'TRO062', 'PANTONE PURPURA 2425 Q1 2018', 0, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(643, 'TRO063', 'PANTONE VERDE 368 ELECTROPURA BENEDETIS', 0, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(644, 'TRO064', 'PANTONE WARM RED Q1 2018', 0, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(645, 'TRO065', 'MAGENTA SELCCIÃ“N EPURA BENEDETIS', 0, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '12171703', '', '', 1),
(646, 'TRO066', 'TINTA AZUL 2147 EPURA 10 LTS', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(647, 'TRO067', 'TINTA PURPURA 2597', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(648, 'TRO068', 'ROJO 185', 0, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(649, 'TRO069', 'AZUL 7712', 0, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(650, 'TRO070', 'PURPURA 226', 0, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(651, 'TRO071', 'NARANJA 165', 0, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(652, 'TRO072', 'MAGENTA CARIBE', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(653, 'TRO073', 'CYAN CARIBE', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(654, 'TRO074', 'NEGRO CARIBE', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(655, 'TRO075', 'AMARILLO CARIBE', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(656, 'TRO076', 'AZUL 286 CARIBE', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(657, 'TRO077', 'MEZCLA MAGENTA SELECCIÃ“N (GEEP)', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(658, 'TRO078', 'MEZCLA CYAN SELECCIÃ“N (GEEP)', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(659, 'TRO079', 'MEZCLA NEGRO SELECCIÃ“N (GEEP)', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(660, 'TRO080', 'MEZCLA AMARILLO SELECCIÃ“N (GEPP)', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '12171703', '', '', 1),
(661, 'TRO081', 'ROJO COMEX 187', 16, 0, 'ROT', '(Ninguna)', 'NO TERMOENCOGIBLE', 'BASE SOLVENTE', '12171703', '', '', 1),
(662, 'TRO082', 'CAFE COMEX 032', 16, 0, 'TINTAS', '(Ninguna)', 'NO TERMOENCOGIBLE', 'BASE SOLVENTE', '12171703', '', '', 1),
(663, 'TRO083', 'GRIS COMEX 400', 16, 0, 'TINTAS', '(Ninguna)', 'NO TERMOENCOGIBLE', 'BASE SOLVENTE', '12171703', '', '', 1),
(664, 'TRO084', 'CYAN COMEX', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '', '', '', 1),
(665, 'TRO085', 'TAMBO 30 AZUL CYAN', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE AGUA', '', '', '', 1),
(666, 'TRO086', 'TAMBO 31 ROJO MAGENTA', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '', '', '', 1),
(667, 'TRO087', 'EXTENDER REDUCTOR (G12Z-277)', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '', '', '', 1),
(668, 'TRO088', 'CYAN SELECCIÃ“N (G13Z-050RF)', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '', '', '', 1),
(669, 'TRO089', 'AMARILLO SELECCIÃ“N (G13Z-403RF)', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '', '', '', 1),
(670, 'TRO090', 'MAGENTA SELECCIÃ“N (G13Z-404RF)', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '', '', '', 1),
(671, 'TRO091', 'NEGRO SELECCIÃ“N (G13Z-405RF)', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '', '', '', 1),
(672, 'TRO092', 'TAMBO 32 AZUL OBSCURO', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '', '', '', 1),
(673, 'TRO093', 'P. 293 GOTA 20 LT GRUESA', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '', '', '', 1),
(674, 'TRO094', 'P. 294 GOTA 20 LTS GRUESA', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '', '', '', 1),
(675, 'TRO095', 'P. 311 GOTA 20 LTS GRUESA', 16, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', 'BASE SOLVENTE', '', '', '', 1),
(676, 'TRO096', 'EXTENDER ROTO LAM G18Z-004RL', 0, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '', '', '', 1),
(677, 'TRO097', 'G17Z-334RF BLANCO AC ROTO', 0, 0, 'TINTAS', '(Ninguna)', 'ROTOGRABADO', '(Ninguna)', '', '', 'Kilogramos', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `embarque`
--

CREATE TABLE `embarque` (
  `idEmbarque` int(11) NOT NULL,
  `numEmbarque` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `transpEmb` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `referencia` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `diaEmb` date NOT NULL,
  `observaEmb` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `bajaEmb` int(1) NOT NULL DEFAULT '1',
  `sucEmbFK` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `prodEmbFK` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `empaque` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `registrado` date NOT NULL,
  `cantidad` decimal(9,3) NOT NULL,
  `producto` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `idorden` int(11) NOT NULL,
  `cerrar` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `embarque`
--

INSERT INTO `embarque` (`idEmbarque`, `numEmbarque`, `transpEmb`, `referencia`, `diaEmb`, `observaEmb`, `bajaEmb`, `sucEmbFK`, `prodEmbFK`, `empaque`, `registrado`, `cantidad`, `producto`, `idorden`, `cerrar`) VALUES
(1, '20190102001', 'ffuytj', 'rollo', '2019-01-02', 'Sin Observaciones', 1, 'Planta Villahermosa Bepensa', 'Thinner estandar 960ml', 'rollo', '2019-01-07', '150.000', 'Thinner estandar 960ml', 6, 0),
(2, '20190105001', 'Casas', 'caja', '2019-01-05', 'Sin Observaciones', 1, 'Sucursal 1', 'Comex prueba', 'caja', '0000-00-00', '0.000', '', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empaque`
--

CREATE TABLE `empaque` (
  `id` int(11) NOT NULL,
  `nameEm` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `empaque`
--

INSERT INTO `empaque` (`id`, `nameEm`) VALUES
(1, 'caja'),
(2, 'rollo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `ID` int(11) NOT NULL,
  `numemple` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `Nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `apellido` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Telefono` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `departamento` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No asignado',
  `usuario` int(1) NOT NULL DEFAULT '0',
  `Baja` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`ID`, `numemple`, `Nombre`, `apellido`, `Telefono`, `departamento`, `usuario`, `Baja`) VALUES
(1, '028', 'Cristian Alberto', 'Valadez Lira', '4776312349', 'TecnologÃ­as de la informaciÃ³n', 1, 0),
(2, '69', 'Erik Martin', 'Castillo Araiza', '477000', 'ProducciÃ³n', 1, 1),
(3, '34', 'Juan Jose', 'Zurita', '4773456789', 'ProducciÃ³n', 1, 0),
(4, '2', 'Felipe', 'Fuentes', '0', 'ProducciÃ³n', 1, 0),
(9, '001', 'Erik', 'Castillo', '8976755', 'TecnologÃ­as de la informaciÃ³n', 0, 1),
(10, '003', 'Mauro', 'Rangel', '6754', 'ProducciÃ³n', 0, 0),
(11, '004', 'SebastiÃ¡n', 'Rodriguez', '243', 'Corte', 0, 0),
(12, '010', 'Marlenne', 'SuÃ±iga', '1234687', 'Compras', 0, 0),
(13, '011', 'John', 'Connor', '6756434', 'LogÃ­stica', 0, 0),
(14, '384', 'ÃºrsÃ©lÃ¡', 'strs', '344643', 'ProducciÃ³n', 0, 0),
(15, '033', 'Brayan', 'Ponce', '3546', 'RevisiÃ³n', 0, 0),
(16, '67', 'Esther', 'Gordillo', '57643', 'Refilado', 0, 0),
(17, '066', 'Laura Elena', 'Garcia Aguilera', '0', 'TecnologÃ­as de la informaciÃ³n', 1, 0),
(18, '058', 'Alfonso', 'Guerrero', '7724391', 'TecnologÃ­as de la informaciÃ³n', 1, 0),
(19, '0', 'Elviss', 'Pressley', '5674420', 'ProducciÃ³n', 1, 0),
(20, '007', 'James', 'Bond', '4772355875', 'ProducciÃ³n', 0, 0),
(21, '456', 'Ã©ste men', 'sno', '24', 'TecnologÃ­as de la informaciÃ³n', 0, 0),
(22, '173', 'Ana Lilia', 'RamÃ­rez SaldaÃ±a ', '0', 'Compras', 1, 1),
(23, '37 ', 'Araceli', 'Belmonte Lozano ', '044 477 113 3471', 'LogÃ­stica', 1, 1),
(24, '61', 'JosÃ© Antonio', 'RodrÃ­guez HernÃ¡ndez ', '0', 'ProducciÃ³n', 1, 1),
(25, '018', 'Luis Felipe', 'Fuentes Medina ', '0', 'ProducciÃ³n', 1, 1),
(26, '102', 'Christian Abraham', 'Mancilla Ramos', '0', 'ProducciÃ³n', 1, 1),
(27, '020', 'Sandra', 'Baeza', '0', 'Calidad', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ensambleempaques`
--

CREATE TABLE `ensambleempaques` (
  `id` int(11) NOT NULL,
  `referencia` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `producto` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `longitud` float NOT NULL,
  `piezas` decimal(9,3) NOT NULL,
  `codigo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tipoEmpaque` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `cdgEmbarque` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `cdgDev` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `ensambleempaques`
--

INSERT INTO `ensambleempaques` (`id`, `referencia`, `producto`, `longitud`, `piezas`, `codigo`, `tipoEmpaque`, `cdgEmbarque`, `cdgDev`, `baja`) VALUES
(1, 'Q1', 'Thinner estandar 960ml', 1750, '6.375', '00010422', 'rollo', '20190102001', '', 1),
(2, 'Q2', 'Thinner estandar 960ml', 1850, '6.740', '00030470', 'rollo', '20190102001', '', 1),
(3, 'Q3', 'Thinner estandar 960ml', 1850, '6.740', '00020422', 'rollo', '', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id` int(11) NOT NULL,
  `abreviatura` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nombreEstado` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `pais` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id`, `abreviatura`, `nombreEstado`, `pais`, `baja`) VALUES
(1, 'Ags.', 'Aguascalientes', 'MÃ©xico', 0),
(2, 'BC', 'Baja California', 'MÃ©xico', 0),
(3, 'BCS', 'Baja California Sur', 'MÃ©xico', 0),
(4, 'Camp.', 'Campeche', 'MÃ©xico', 0),
(5, 'Chih.', 'Chihuahua', 'MÃ©xico', 0),
(6, 'Chis.', 'Chiapas', 'MÃ©xico', 0),
(7, 'Colombia', 'Colombia', '', 0),
(8, 'Coah.', 'Coahuila de Zaragoza', 'MÃ©xico', 0),
(9, 'Col.', 'Colima', 'MÃ©xico', 0),
(10, 'DF', 'Distrito Federal', 'MÃ©xico', 0),
(11, 'Dgo.', 'Durango', 'MÃ©xico', 0),
(12, 'Gro.', 'Guerrero', 'MÃ©xico', 0),
(13, 'Gto.', 'Guanajuato', 'MÃ©xico', 1),
(14, 'Guatemala', 'Guatemala', '', 0),
(15, 'Hgo.', 'Hidalgo', 'MÃ©xico', 0),
(16, 'Jal.', 'Jalisco', 'MÃ©xico', 0),
(17, 'Mex.', 'MÃ©xico', 'MÃ©xico', 1),
(18, 'Mich.', 'MichoacÃ¡n de Ocampo', 'MÃ©xico', 0),
(19, 'Mor.', 'Morelos', 'MÃ©xico', 0),
(20, 'Nay.', 'Nayarit', 'MÃ©xico', 0),
(21, 'NL', 'Nuevo LeÃ³n', 'MÃ©xico', 0),
(22, 'Oax.', 'Oaxaca', 'MÃ©xico', 0),
(23, 'Pue.', 'Puebla', 'MÃ©xico', 0),
(24, 'Q. Roo', 'Quintana Roo', 'MÃ©xico', 0),
(25, 'Qro.', 'QuerÃ©taro', 'MÃ©xico', 1),
(26, 'Sin.', 'Sinaloa', 'MÃ©xico', 0),
(27, 'SLP', 'San Luis PotosÃƒÂ­', 'MÃ©xico', 0),
(28, 'Son.', 'Sonora', 'MÃ©xico', 0),
(29, 'Tab.', 'Tabasco', 'MÃ©xico', 1),
(30, 'Tamps.', 'Tamaulipas', 'MÃ©xico', 0),
(31, 'Tlax.', 'Tlaxcala', 'MÃ©xico', 0),
(32, 'Ver.', 'Veracruz de Ignacio de la Llave', 'MÃ©xico', 0),
(33, 'Yuc.', 'YucatÃƒÂ¡n', 'MÃ©xico', 1),
(34, 'Zac.', 'Zacatecas', 'MÃ©xico', 0),
(35, 'Sin definicion', 'Romita', 'Romita', 0),
(36, 'GTO', 'Guanajuto', 'MÃ©xico', 0),
(37, 'QUND', 'Quindio', 'Colombia', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hlogpproducto`
--

CREATE TABLE `hlogpproducto` (
  `id` int(11) NOT NULL,
  `tipo` varchar(30) NOT NULL,
  `consumo` double NOT NULL,
  `impresion` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `hlogpproducto`
--

INSERT INTO `hlogpproducto` (`id`, `tipo`, `consumo`, `impresion`) VALUES
(1, 'hologrami', 0.7, 'Mas Humano'),
(2, 'holi', 0.5, 'jajjjdfg'),
(3, 'holi', 0, 'cuindio'),
(4, 'holi', 0, 'cuindio'),
(5, 'holi', 0, 'Kindio'),
(6, 'hologrami', 0.3, 'A pruebis loko');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impresiones`
--

CREATE TABLE `impresiones` (
  `id` int(11) NOT NULL,
  `descripcionDisenio` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `anchoPelicula` float DEFAULT NULL,
  `anchoEtiqueta` float DEFAULT NULL,
  `millaresPorRollo` float DEFAULT NULL,
  `codigoImpresion` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `alturaEtiqueta` float DEFAULT NULL,
  `espacioFusion` float DEFAULT NULL,
  `porcentajeMPR` float DEFAULT NULL,
  `descripcionImpresion` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `millaresPorPaquete` float DEFAULT NULL,
  `tintas` int(11) DEFAULT NULL,
  `sustrato` varchar(70) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombreBanda` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigoCliente` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `baja` int(1) NOT NULL DEFAULT '1',
  `DisenioFK` int(11) NOT NULL,
  `metrosCubicos` float DEFAULT NULL,
  `alturaImpresion` float DEFAULT NULL,
  `espacioregistro` float NOT NULL,
  `holograma` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `impresiones`
--

INSERT INTO `impresiones` (`id`, `descripcionDisenio`, `anchoPelicula`, `anchoEtiqueta`, `millaresPorRollo`, `codigoImpresion`, `alturaEtiqueta`, `espacioFusion`, `porcentajeMPR`, `descripcionImpresion`, `millaresPorPaquete`, `tintas`, `sustrato`, `nombreBanda`, `codigoCliente`, `baja`, `DisenioFK`, `metrosCubicos`, `alturaImpresion`, `espacioregistro`, `holograma`) VALUES
(2, 'Comex', 127, NULL, 11, '903294', 274.5, NULL, 20, 'Comex prueba', 0, 1, 'PVC termoencogible C40 E50/0 400mm', '', 'Etiqueta thinner estandar 960ml', 1, 0, NULL, NULL, 19, ''),
(5, 'Comex', 127, NULL, 11, 'E0420C18-04', 274.5, NULL, 100, 'Thinner estandar 960ml', 0, 5, 'Bopp blanco 35 micras', '', 'Etiqueta thinner estandar 960ml', 1, 0, NULL, NULL, 19, ''),
(6, 'Pruebis', 190, NULL, 4, '23436', 78, NULL, 7, 'A pruebis loko', 6, 3, 'PVC termoencogible C40 E50/0 400mm', '', 'Etiqueta thinner estandar 960ml', 0, 0, NULL, NULL, 20, 'hologrami'),
(7, 'Generico Gepp', 220, 108, 7.5, 'M0503C22-01', 75, 4, 20, 'Generico Gepp 108x75mm', 0.5, 6, 'PVC termoencogible C40 E50/0 450mm', 'BS Generico Gepp', 'Banda de garantia s/hol epura 88x37mm', 1, 0, NULL, NULL, 10, ''),
(8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'juan', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, 0, '');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `impresioness`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `impresioness` (
`descripcionImpresion` varchar(50)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juegoparametros`
--

CREATE TABLE `juegoparametros` (
  `id` int(11) NOT NULL,
  `identificadorJuego` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `nombreparametro` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `numParametro` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1',
  `requerido` int(11) NOT NULL DEFAULT '0',
  `leyenda` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `placeholder` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `juegoparametros`
--

INSERT INTO `juegoparametros` (`id`, `identificadorJuego`, `nombreparametro`, `numParametro`, `baja`, `requerido`, `leyenda`, `placeholder`) VALUES
(1, 'JPMTERMOENCOGIBLE', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(2, 'JPMTERMOENCOGIBLE', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(3, 'JPMTERMOENCOGIBLE', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(4, 'JPMTERMOENCOGIBLE', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(5, 'JPMTERMOENCOGIBLE', 'tintas', 'PAM5', 1, 0, 'Tintas', 'numero de tintas'),
(6, 'JPMTERMOENCOGIBLE', 'anchoEtiqueta', 'PAM6', 1, 1, 'Ancho etiqueta', 'mm'),
(7, 'JPMTERMOENCOGIBLE', 'espacioFusion', 'PAM7', 1, 0, 'Espacio fusiÃ³n', 'mm'),
(8, 'JPMTERMOENCOGIBLE', 'millaresPorRollo', 'PAM8', 1, 1, 'Millares por rollo', 'millares'),
(9, 'JPMTERMOENCOGIBLE', 'porcentajeMPR', 'PAM9', 1, 0, '% +/- por rollo', '% +/-'),
(10, 'JPMTERMOENCOGIBLE', 'millaresPorPaquete', 'PAM10', 1, 0, 'Millares PP', 'millares '),
(18, 'JPSPoster05', 'tintas', '', 1, 1, 'Tintas', 'nÃƒÂºmero de tintas'),
(21, 'JPSVolantes07', 'tintas', '', 1, 1, 'Tintas', 'nÃƒÂºmero de tintas'),
(22, 'JPSVolantes07', 'alturaImpresion', '', 1, 1, 'Altura de la impresiÃ³n', 'mm'),
(23, 'JPSVolantes07', 'millaresPorPaquete', '', 1, 1, 'Millares PP', 'millares'),
(24, 'JPSVolantes07', 'codigoImpresion', '', 1, 1, 'codigo', 'codigo corto'),
(25, 'JPSVolantes07', 'descripcionImpresion', '', 1, 1, 'ImpresiÃƒÂ³n', 'descripciÃ³n'),
(26, 'JPSPoster05', 'codigoImpresion', '', 1, 1, 'codigo', 'codigo'),
(27, 'JPSPoster05', 'descripcionImpresion', '', 1, 1, 'DescripciÃ³n', 'C:'),
(28, 'PKPIMP', 'operador', 'PIMP1', 1, 1, 'Operador', 'nombre'),
(29, 'PKPIMP', 'maquina', 'PIMP2', 1, 1, 'Maquina', 'identificador'),
(30, 'PKPIMP', 'juegoCilindros', 'PIMP3', 1, 1, 'Juego de Cilindros', 'identificador'),
(31, 'PKPIMP', 'lote', 'C', 1, 1, 'Lote', 'referencia'),
(32, 'PKPIMP', 'total', 'PIMP5', 0, 0, '', ''),
(33, 'PKPIMP', 'producto', 'PIMP6', 0, 0, '', ''),
(34, 'PKPIMP', 'fecha', 'PIMP7', 0, 0, '', ''),
(35, 'JPSFlexografia05', 'codigoImpresion', '', 1, 1, 'CÃ³digo', 'identificador'),
(37, 'JPSFlexografia05', 'anchoPelicula', '', 1, 1, 'Ancho pelicula', 'mm'),
(38, 'JPSFlexografia05', 'alturaEtiqueta', '', 1, 1, 'Altura de etiqueta', 'mm'),
(39, 'JPSFlexografia05', 'tintas', '', 1, 1, 'NÃºmero de tintas', 'numero'),
(40, 'JPSFlexografia05', 'anchoEtiqueta', '', 1, 1, 'Ancho etiqueta', 'mm'),
(41, 'JPSFlexografia05', 'espacioFusion', '', 1, 1, 'Espacio de fusiÃ³n', 'mm'),
(42, 'JPSFlexografia05', 'millaresPorRollo', '', 1, 1, 'Millares por rollo', 'millares'),
(43, 'JPSFlexografia05', 'porcentajeMPR', '', 1, 1, '% Millares por rollo', '%+/-'),
(44, 'JPSFlexografia05', 'millaresPorPaquete', '', 1, 1, 'MPP', 'millares'),
(45, 'JPSFlexografia05', 'descripcionImpresion', '', 1, 1, 'DescripciÃ³n', 'descripciÃ³n detallada'),
(49, 'PKPFUS', 'operador', '0', 1, 1, 'Operador', 'nombre'),
(50, 'PKPFUS', 'maquina', '0', 1, 1, 'Maquina', 'identificador'),
(51, 'PKPFUS', 'bobina', 'C', 1, 1, 'Bobina', 'codigo'),
(52, 'PKPFUS', 'disco', '0', 1, 1, 'Disco(Referencia)', 'disco'),
(57, 'PKPDes', 'Lotesillo', 'C', 1, 0, 'Lote', 'haha'),
(58, 'PKPIMP', 'longitud', 'G', 1, 1, 'Longitud', 'mts'),
(59, 'PKPIMP', 'peso', 'G', 1, 1, 'Peso', 'kilogramos'),
(60, 'PKPIMP', 'noop', 'PIMP8', 0, 0, 'NOOP', 'haah'),
(61, 'PKPREF', 'operador', '0', 1, 1, 'Operador', 'nombre'),
(62, 'PKPREF', 'maquina', '0', 1, 1, 'Maquina', 'identificador'),
(63, 'PKPREF', 'lote', 'C', 1, 1, 'Lote', 'codigo'),
(64, 'PKPREF', 'longitud', 'G', 1, 1, 'Longitud', 'metros'),
(65, 'PKPREF', 'amplitud', 'G', 1, 1, 'Amplitud', 'milimetros'),
(66, 'PKPREF', 'peso', 'G', 1, 1, 'Peso', 'kilogramos'),
(67, 'PKPREF', 'noop', 'PAM2', 0, 0, 'noop', 'noop'),
(68, 'PKPFUS', 'amplitud', 'G', 1, 1, 'Amplitud', 'mm'),
(69, 'PKPFUS', 'bandera', 'G', 1, 1, 'Banderas', 'numero de banderas'),
(70, 'PKPFUS', 'longitud', 'G', 1, 1, 'Longitud', 'metros'),
(72, 'PKPREV', 'operador', '0', 1, 1, 'Operador', 'codigo'),
(73, 'PKPREV', 'maquina', '0', 1, 1, 'Maquina', 'codigo'),
(74, 'PKPREV', 'rollo', 'C', 1, 1, 'Rollo', 'codigo'),
(75, 'PKPREV', 'longitud', 'G', 1, 1, 'Longitud', 'metros'),
(76, 'PKPREV', 'peso', 'G', 1, 1, 'Peso', 'kilos'),
(77, 'PKPREV', 'bandera', 'G', 1, 1, 'Bandera', 'cantidad'),
(78, 'PKPCOR', 'operador', '0', 1, 1, 'Operador', 'CÃ³digo'),
(79, 'PKPCOR', 'maquina', '0', 1, 1, 'MÃ¡quina', 'CÃ³digo'),
(80, 'PKPCOR', 'rollo', 'C', 1, 1, 'Rollo', 'CÃ³digo'),
(81, 'PKPREF', 'unidades', '0', 0, 0, '', 'Millares'),
(82, 'JPSHOL', 'descripcionDisenio', '', 1, 1, 'Descripcion', 'DescripciÃƒÆ’Ã‚Â³n de la impre'),
(83, 'JPSHOL', 'codigoImpresion', '', 1, 1, 'CÃ³digo', 'CÃ³digo corto'),
(99, 'PKPSLIT', 'operador', '0', 1, 1, 'Empleado', 'CÃ³digo empleado'),
(100, 'PKPSLIT', 'maquina', '0', 1, 1, 'MÃ¡quina', 'CÃ³digo'),
(101, 'PKPSLIT', 'lote', 'C', 1, 1, 'Lote', 'CÃ³digo de barras'),
(102, 'PKPEMB', 'operador', '0', 1, 1, 'Empleado', 'CÃ³digo empleado'),
(103, 'PKPEMB', 'maquina', '0', 1, 1, 'MÃ¡quina', 'CÃ³digo maquina'),
(104, 'PKPEMB', 'lote', 'C', 1, 1, 'Lote', 'CÃ³digo de barras'),
(105, 'PKPEMB', 'longitud', 'G', 1, 1, 'Longitud', 'metros'),
(106, 'PKPEMB', 'amplitud', 'G', 1, 1, 'Amplitud', 'mm'),
(107, 'PKPEMB', 'peso', 'G', 1, 1, 'Peso', 'kilogramos'),
(108, 'PKPEMB', 'bandera', 'G', 1, 1, 'Banderas', 'cantidad de banderas'),
(109, 'PKPSLIT', 'longitud', 'G', 1, 1, 'Longitud', 'metros'),
(110, 'PKPSLIT', 'amplitud', 'G', 1, 1, 'Amplitud', 'cm'),
(111, 'PKPSLIT', 'peso', 'G', 1, 1, 'Peso', 'kilogramos'),
(112, 'PKPSLIT', 'bandera', 'G', 1, 1, 'Banderas', 'cantidad de banderas'),
(113, 'PKPLAM', 'operador', '0', 1, 1, 'Empleado', 'CÃ³digo empleado'),
(114, 'PKPLAM', 'maquina', '0', 1, 1, 'MÃ¡quina', 'CÃ³digo'),
(115, 'PKPLAM', 'lote', 'C', 1, 1, 'Lote/Bobina', 'CÃ³digo de barras'),
(116, 'PKPLAM', 'longitud', 'G', 1, 1, 'Longitud', 'metros'),
(117, 'PKPLAM', 'amplitud', 'G', 1, 1, 'Amplitud', 'mm'),
(118, 'PKPLAM', 'peso', 'G', 1, 1, 'Peso', 'kilogramos'),
(119, 'PKPLAM', 'bandera', 'G', 1, 1, 'Banderas', 'cantidad de banderas'),
(120, 'Predeterminados', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(121, 'Predeterminados', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(122, 'Predeterminados', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(123, 'Predeterminados', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(124, 'Predeterminados', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(125, 'Predeterminados', 'anchoEtiqueta', 'PAM6', 1, 1, 'Ancho etiqueta', 'mm'),
(126, 'Predeterminados', 'espacioFusion', 'PAM7', 1, 1, 'Espacio fusiÃ³n', 'mm'),
(127, 'Predeterminados', 'millaresPorRollo', 'PAM8', 1, 1, 'Millares por rollo', 'millares'),
(128, 'Predeterminados', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', '% +/-'),
(129, 'Predeterminados', 'millaresPorPaquete', 'PAM10', 1, 0, 'Millares PP', 'millares'),
(130, 'JPSMAnNGO11', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(131, 'JPSMAnNGO11', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(132, 'JPSMAnNGO11', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(133, 'JPSMAnNGO11', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(134, 'JPSMAnNGO11', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(135, 'JPSMAnNGO11', 'anchoEtiqueta', 'PAM6', 1, 1, 'Ancho etiqueta', 'mm'),
(136, 'JPSMAnNGO11', 'espacioFusion', 'PAM7', 1, 1, 'Espacio fusiÃ³n', 'mm'),
(137, 'JPSMAnNGO11', 'millaresPorRollo', 'PAM8', 1, 1, 'Millares por rollo', 'millares'),
(138, 'JPSMAnNGO11', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', '% +/-'),
(139, 'JPSMAnNGO11', 'millaresPorPaquete', 'PAM10', 1, 1, 'Millares PP', 'millares'),
(140, 'JPSdoritos12', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(141, 'JPSdoritos12', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(142, 'JPSdoritos12', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(143, 'JPSdoritos12', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(144, 'JPSdoritos12', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(145, 'JPSdoritos12', 'anchoEtiqueta', 'PAM6', 1, 1, 'Ancho etiqueta', 'mm'),
(146, 'JPSdoritos12', 'espacioFusion', 'PAM7', 1, 1, 'Espacio fusiÃ³n', 'mm'),
(147, 'JPSdoritos12', 'millaresPorRollo', 'PAM8', 1, 1, 'Millares por rollo', 'millares'),
(148, 'JPSdoritos12', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', '% +/-'),
(149, 'JPSdoritos12', 'millaresPorPaquete', 'PAM10', 1, 1, 'Millares PP', 'millares'),
(150, 'JPSdorielotes12', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(151, 'JPSdorielotes12', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(152, 'JPSdorielotes12', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(153, 'JPSdorielotes12', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(154, 'JPSdorielotes12', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(155, 'JPSdorielotes12', 'anchoEtiqueta', 'PAM6', 1, 1, 'Ancho etiqueta', 'mm'),
(156, 'JPSdorielotes12', 'espacioFusion', 'PAM7', 1, 1, 'Espacio fusiÃ³n', 'mm'),
(157, 'JPSdorielotes12', 'millaresPorRollo', 'PAM8', 1, 1, 'Millares por rollo', 'millares'),
(158, 'JPSdorielotes12', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', '% +/-'),
(159, 'JPSdorielotes12', 'millaresPorPaquete', 'PAM10', 1, 1, 'Millares PP', 'millares'),
(160, 'JPS191912', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(161, 'JPS191912', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(162, 'JPS191912', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(163, 'JPS191912', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(164, 'JPS191912', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(165, 'JPS191912', 'anchoEtiqueta', 'PAM6', 1, 1, 'Ancho etiqueta', 'mm'),
(166, 'JPS191912', 'espacioFusion', 'PAM7', 1, 1, 'Espacio fusiÃ³n', 'mm'),
(167, 'JPS191912', 'millaresPorRollo', 'PAM8', 1, 1, 'Millares por rollo', 'millares'),
(168, 'JPS191912', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', '% +/-'),
(169, 'JPS191912', 'millaresPorPaquete', 'PAM10', 1, 1, 'Millares PP', 'millares'),
(170, 'JPSking12', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(171, 'JPSking12', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(172, 'JPSking12', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(173, 'JPSking12', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(174, 'JPSking12', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(175, 'JPSking12', 'anchoEtiqueta', 'PAM6', 1, 1, 'Ancho etiqueta', 'mm'),
(176, 'JPSking12', 'espacioFusion', 'PAM7', 1, 1, 'Espacio fusiÃ³n', 'mm'),
(177, 'JPSking12', 'millaresPorRollo', 'PAM8', 1, 1, 'Millares por rollo', 'millares'),
(178, 'JPSking12', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', '% +/-'),
(179, 'JPSking12', 'millaresPorPaquete', 'PAM10', 1, 1, 'Millares PP', 'millares'),
(180, 'JPSke12', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(181, 'JPSke12', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(182, 'JPSke12', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(183, 'JPSke12', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(184, 'JPSke12', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(185, 'JPSke12', 'anchoEtiqueta', 'PAM6', 1, 1, 'Ancho etiqueta', 'mm'),
(186, 'JPSke12', 'espacioFusion', 'PAM7', 1, 1, 'Espacio fusiÃ³n', 'mm'),
(187, 'JPSke12', 'millaresPorRollo', 'PAM8', 1, 1, 'Millares por rollo', 'millares'),
(188, 'JPSke12', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', '% +/-'),
(189, 'JPSke12', 'millaresPorPaquete', 'PAM10', 1, 1, 'Millares PP', 'millares'),
(190, 'JPShola12', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(191, 'JPShola12', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(192, 'JPShola12', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(193, 'JPShola12', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(194, 'JPShola12', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(195, 'JPShola12', 'anchoEtiqueta', 'PAM6', 1, 1, 'Ancho etiqueta', 'mm'),
(196, 'JPShola12', 'espacioFusion', 'PAM7', 1, 1, 'Espacio fusiÃ³n', 'mm'),
(197, 'JPShola12', 'millaresPorRollo', 'PAM8', 1, 1, 'Millares por rollo', 'millares'),
(198, 'JPShola12', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', '% +/-'),
(199, 'JPShola12', 'millaresPorPaquete', 'PAM10', 1, 1, 'Millares PP', 'millares'),
(200, 'JPSfin12', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(201, 'JPSfin12', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(202, 'JPSfin12', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(203, 'JPSfin12', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(204, 'JPSfin12', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(205, 'JPSfin12', 'anchoEtiqueta', 'PAM6', 1, 1, 'Ancho etiqueta', 'mm'),
(206, 'JPSfin12', 'espacioFusion', 'PAM7', 1, 1, 'Espacio fusiÃ³n', 'mm'),
(207, 'JPSfin12', 'millaresPorRollo', 'PAM8', 1, 1, 'Millares por rollo', 'millares'),
(208, 'JPSfin12', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', '% +/-'),
(209, 'JPSfin12', 'millaresPorPaquete', 'PAM10', 1, 1, 'Millares PP', 'millares'),
(210, 'JPSdorilokos12', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(211, 'JPSdorilokos12', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(212, 'JPSdorilokos12', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(213, 'JPSdorilokos12', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(214, 'JPSdorilokos12', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(215, 'JPSdorilokos12', 'anchoEtiqueta', 'PAM6', 1, 1, 'Ancho etiqueta', 'mm'),
(216, 'JPSdorilokos12', 'espacioFusion', 'PAM7', 1, 1, 'Espacio fusiÃ³n', 'mm'),
(217, 'JPSdorilokos12', 'millaresPorRollo', 'PAM8', 1, 1, 'Millares por rollo', 'millares'),
(218, 'JPSdorilokos12', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', '% +/-'),
(219, 'JPSdorilokos12', 'millaresPorPaquete', 'PAM10', 1, 1, 'Millares PP', 'millares'),
(220, 'JPSEtiq_Adilub12', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(221, 'JPSEtiq_Adilub12', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(222, 'JPSEtiq_Adilub12', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(223, 'JPSEtiq_Adilub12', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(224, 'JPSEtiq_Adilub12', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(225, 'JPSEtiq_Adilub12', 'anchoEtiqueta', 'PAM6', 1, 1, 'Ancho etiqueta', 'mm'),
(226, 'JPSEtiq_Adilub12', 'espacioFusion', 'PAM7', 1, 1, 'Espacio fusiÃ³n', 'mm'),
(227, 'JPSEtiq_Adilub12', 'millaresPorRollo', 'PAM8', 1, 1, 'Millares por rollo', 'millares'),
(228, 'JPSEtiq_Adilub12', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', '% +/-'),
(229, 'JPSEtiq_Adilub12', 'millaresPorPaquete', 'PAM10', 1, 1, 'Millares PP', 'millares'),
(230, 'PKPIMPFL', 'operador', 'PIMPFL1', 1, 1, 'Operador', 'CÃ³digo de empleado'),
(231, 'PKPIMPFL', 'maquina', 'PIMPFL2', 1, 1, 'MÃ¡quina', 'CÃ³digo maquina'),
(232, 'PKPIMPFL', 'juegoCireles', 'PIMPFL3', 1, 1, 'Juego de cireles', 'CÃ³digo juego'),
(233, 'PKPIMPFL', 'lote', 'C', 1, 1, 'Lote', 'CÃ³digo de barras'),
(234, 'PKPIMPFL', 'suaje', 'PIMPFL5', 1, 0, 'Suaje', 'CÃ³digo suaje'),
(235, 'PKPIMPFL', 'total', 'PIMPFL6', 0, 0, '', ''),
(236, 'PKPIMPFL', 'producto', 'PIMPFL7', 0, 0, '', ''),
(237, 'PKPIMPFL', 'fecha', 'PIMPFL8', 0, 0, '', ''),
(238, 'PKPIMPFL', 'longitud', 'G', 1, 1, 'Longitud', 'mts'),
(239, 'PKPIMPFL', 'peso', 'G', 1, 1, 'Peso', 'kgs'),
(241, 'PKPIMPFL', 'noop', 'PIMPFL11', 0, 0, 'NOOP', 'noop'),
(242, 'PKPfol', 'operador', 'PIMPFL1', 1, 1, 'Operador', 'CÃ³digo de empleado'),
(243, 'PKPfol', 'maquina', 'PIMPFL2', 1, 1, 'MÃ¡quina', 'CÃ³digo de mÃƒÆ’Ã‚Â¡quina'),
(244, 'PKPfol', 'bobina', 'C', 1, 1, 'Bobina', 'CÃ³digo de barras'),
(245, 'PKPfol', 'total', 'PIMPFL6', 0, 0, '', ''),
(246, 'PKPfol', 'producto', 'PIMPFL7', 0, 0, '', ''),
(247, 'PKPfol', 'fecha', 'PIMPFL8', 0, 0, '', ''),
(248, 'PKPfol', 'longitud', 'PIMP', 0, 0, 'longitud', 'mts'),
(249, 'PKPfol', 'unidades', 'G', 1, 1, 'Piezas', 'Millares'),
(250, 'PKPfol', 'peso', 'G', 1, 1, 'Peso', 'kgs'),
(251, 'PKPfol', 'noop', 'PIMPFL11', 0, 0, 'NOOP', 'noop'),
(252, 'PKPsua', 'operador', 'PIMPFL1', 1, 1, 'Operador', 'CÃ³digo de empleado'),
(253, 'PKPsua', 'maquina', 'PIMPFL2', 1, 1, 'MÃ¡quina', 'CÃ³digo de empleado'),
(254, 'PKPsua', 'rollo', 'C', 1, 1, 'Rollo', 'CÃ³digo de barras'),
(255, 'PKPsua', 'suaje', 'PIMPFL5', 1, 1, 'Suaje', 'CÃ³digo suaje'),
(256, 'PKPsua', 'total', 'PIMPFL6', 0, 0, '', ''),
(257, 'PKPsua', 'producto', 'PIMPFL7', 0, 1, '', ''),
(258, 'PKPsua', 'fecha', 'PIMPFL8', 0, 0, '', ''),
(259, 'PKPsua', 'longitud', 'G', 1, 1, 'Longitud', 'mts'),
(260, 'PKPsua', 'peso', 'G', 1, 1, 'Peso', 'kgs'),
(261, 'PKPsua', 'noop', 'PIMPFL11', 0, 0, 'NOOP', 'noop'),
(262, 'PKPtro', 'operador', 'PIMPFL1', 1, 1, 'Operador', 'CÃ³digo de empleado'),
(263, 'PKPtro', 'maquina', 'PIMPFL2', 1, 1, 'MÃ¡quina', 'CÃ³digo de maquina'),
(264, 'PKPtro', 'rollo', 'C', 1, 1, 'Rollo', 'CÃ³digo de barras'),
(265, 'PKPtro', 'suaje', 'PIMPFL5', 1, 1, 'Suaje', 'CÃ³digo suaje'),
(266, 'PKPtro', 'total', 'PIMPFL6', 0, 0, '', ''),
(267, 'PKPtro', 'producto', 'PIMPFL7', 0, 0, '', ''),
(268, 'PKPtro', 'fecha', 'PIMPFL8', 0, 0, '', ''),
(269, 'PKPtro', 'peso', 'G', 1, 1, 'Peso', 'kgs'),
(270, 'PKPtro', 'unidades', 'G', 1, 1, 'Piezas', 'Millares'),
(271, 'PKPtro', 'noop', 'PIMPFL11', 0, 0, 'NOOP', 'noop'),
(272, 'PKPrev2', 'operador', 'PIMPFL3', 1, 1, 'Operador', 'CÃ³digo de empleado'),
(273, 'PKPrev2', 'maquina', 'PIMPFL2', 1, 1, 'MÃ¡quina', 'CÃ³digo de empleado'),
(274, 'PKPrev2', 'rollo', 'C', 1, 1, 'Rollo', 'CÃ³digo de barras'),
(275, 'PKPrev2', 'total', 'PIMPFL6', 0, 1, '', ''),
(276, 'PKPrev2', 'producto', 'PIMPFL7', 0, 1, '', ''),
(277, 'PKPrev2', 'fecha', 'PIMPFL8', 0, 1, '', ''),
(278, 'PKPrev2', 'peso', 'G', 1, 1, 'Peso', 'kgs'),
(279, 'PKPrev2', 'unidades', 'G', 1, 1, 'Piezas', 'Millares'),
(280, 'PKPrev2', 'bandera', 'G', 1, 1, 'Banderas', 'cantidad'),
(281, 'PKPrev2', 'noop', 'PIMPFL11', 0, 0, 'NOOP', 'noop'),
(282, 'JPSPB10', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(283, 'JPSPB10', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(284, 'JPSPB10', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(285, 'JPSPB10', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(286, 'JPSPB10', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(287, 'JPSPB10', 'anchoEtiqueta', 'PAM6', 1, 1, 'Ancho etiqueta', 'mm'),
(288, 'JPSPB10', 'espacioFusion', 'PAM7', 1, 1, 'Espacio fusiÃ³n', 'mm'),
(289, 'JPSPB10', 'millaresPorRollo', 'PAM8', 1, 1, 'Millares por rollo', 'millares'),
(290, 'JPSPB10', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', '% +/-'),
(291, 'JPSPB10', 'millaresPorPaquete', 'PAM10', 1, 1, 'Millares PP', 'millares'),
(292, 'JPSKnotEtiq09', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(293, 'JPSKnotEtiq09', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(294, 'JPSKnotEtiq09', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(295, 'JPSKnotEtiq09', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(296, 'JPSKnotEtiq09', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(297, 'JPSKnotEtiq09', 'anchoEtiqueta', 'PAM6', 1, 1, 'Ancho etiqueta', 'mm'),
(298, 'JPSKnotEtiq09', 'espacioFusion', 'PAM7', 1, 1, 'Espacio fusiÃ³n', 'mm'),
(299, 'JPSKnotEtiq09', 'millaresPorRollo', 'PAM8', 1, 1, 'Millares por rollo', 'millares'),
(300, 'JPSKnotEtiq09', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', '% +/-'),
(301, 'JPSKnotEtiq09', 'millaresPorPaquete', 'PAM10', 1, 1, 'Millares PP', 'millares'),
(302, 'JPSSelf-Destruct01', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(303, 'JPSSelf-Destruct01', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(304, 'JPSSelf-Destruct01', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(305, 'JPSSelf-Destruct01', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(306, 'JPSSelf-Destruct01', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(307, 'JPSSelf-Destruct01', 'anchoEtiqueta', 'PAM6', 1, 1, 'Ancho etiqueta', 'mm'),
(308, 'JPSSelf-Destruct01', 'espacioFusion', 'PAM7', 1, 1, 'Espacio fusiÃ³n', 'mm'),
(309, 'JPSSelf-Destruct01', 'millaresPorRollo', 'PAM8', 1, 1, 'Millares por rollo', 'millares'),
(310, 'JPSSelf-Destruct01', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', '% +/-'),
(311, 'JPSSelf-Destruct01', 'millaresPorPaquete', 'PAM10', 1, 1, 'Millares PP', 'millares'),
(352, 'JPSPrank08', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(353, 'JPSPrank08', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(354, 'JPSPrank08', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(355, 'JPSPrank08', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(356, 'JPSPrank08', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(357, 'JPSPrank08', 'anchoEtiqueta', 'PAM6', 1, 1, 'Ancho etiqueta', 'mm'),
(358, 'JPSPrank08', 'espacioFusion', 'PAM7', 1, 1, 'Espacio fusiÃ³n', 'mm'),
(359, 'JPSPrank08', 'millaresPorRollo', 'PAM8', 1, 1, 'Millares por rollo', 'millares'),
(360, 'JPSPrank08', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', '% +/-'),
(361, 'JPSPrank08', 'millaresPorPaquete', 'PAM10', 1, 1, 'Millares PP', 'millares'),
(362, 'JPSPrueba11', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(363, 'JPSPrueba11', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(364, 'JPSPrueba11', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(365, 'JPSPrueba11', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(366, 'JPSPrueba11', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(367, 'JPSPrueba11', 'anchoEtiqueta', 'PAM6', 1, 1, 'Ancho etiqueta', 'mm'),
(368, 'JPSPrueba11', 'espacioFusion', 'PAM7', 1, 1, 'Espacio fusiÃ³n', 'mm'),
(369, 'JPSPrueba11', 'millaresPorRollo', 'PAM8', 1, 1, 'Millares por rollo', 'millares'),
(370, 'JPSPrueba11', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', '% +/-'),
(371, 'JPSPrueba11', 'millaresPorPaquete', 'PAM10', 1, 1, 'Millares PP', 'millares'),
(372, 'PKPLAM2', 'operador', '0', 1, 1, 'Empleado', 'CÃ³digo empleado'),
(373, 'PKPLAM2', 'maquina', '0', 1, 1, 'MÃ¡quina', 'CÃ³digo'),
(374, 'PKPLAM2', 'lote', 'C', 1, 1, 'Lote/Bobina', 'CÃ³digo de barras'),
(375, 'PKPLAM2', 'longitud', 'G', 1, 1, 'Longitud', 'metros'),
(376, 'PKPLAM2', 'amplitud', 'G', 1, 1, 'Amplitud', 'mm'),
(377, 'PKPLAM2', 'peso', 'G', 1, 1, 'Peso', 'kilogramos'),
(378, 'PKPLAM2', 'bandera', 'G', 1, 1, 'Banderas', 'cantidad de banderas'),
(389, 'JPSSticker08', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(390, 'JPSSticker08', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(391, 'JPSSticker08', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(392, 'JPSSticker08', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(393, 'JPSSticker08', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(394, 'JPSSticker08', 'anchoEtiqueta', 'PAM6', 1, 1, 'Ancho etiqueta', 'mm'),
(395, 'JPSSticker08', 'espacioFusion', 'PAM7', 1, 1, 'Espacio fusiÃ³n', 'mm'),
(396, 'JPSSticker08', 'millaresPorRollo', 'PAM8', 1, 1, 'Millares por rollo', 'millares'),
(397, 'JPSSticker08', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', '% +/-'),
(398, 'JPSSticker08', 'millaresPorPaquete', 'PAM10', 1, 1, 'Millares PP', 'millares'),
(399, 'JPSTechno09', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(400, 'JPSTechno09', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(401, 'JPSTechno09', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(402, 'JPSTechno09', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(403, 'JPSTechno09', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(404, 'JPSTechno09', 'anchoEtiqueta', 'PAM6', 1, 1, 'Ancho etiqueta', 'mm'),
(405, 'JPSTechno09', 'espacioFusion', 'PAM7', 1, 1, 'Espacio fusiÃ³n', 'mm'),
(406, 'JPSTechno09', 'millaresPorRollo', 'PAM8', 1, 1, 'Millares por rollo', 'millares'),
(407, 'JPSTechno09', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', '% +/-'),
(408, 'JPSTechno09', 'millaresPorPaquete', 'PAM10', 1, 1, 'Millares PP', 'millares'),
(409, 'JPStaza02', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(410, 'JPStaza02', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(411, 'JPStaza02', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(412, 'JPStaza02', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(413, 'JPStaza02', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(414, 'JPStaza02', 'anchoEtiqueta', 'PAM6', 1, 1, 'Ancho etiqueta', 'mm'),
(415, 'JPStaza02', 'espacioFusion', 'PAM7', 1, 1, 'Espacio fusiÃ³n', 'mm'),
(416, 'JPStaza02', 'millaresPorRollo', 'PAM8', 1, 1, 'Millares por rollo', 'millares'),
(417, 'JPStaza02', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', '% +/-'),
(418, 'JPStaza02', 'millaresPorPaquete', 'PAM10', 1, 1, 'Millares PP', 'millares'),
(419, 'JPStaza02', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(420, 'JPStaza02', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(421, 'JPStaza02', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(422, 'JPStaza02', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(423, 'JPStaza02', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(424, 'JPStaza02', 'anchoEtiqueta', 'PAM6', 1, 1, 'Ancho etiqueta', 'mm'),
(425, 'JPStaza02', 'espacioFusion', 'PAM7', 1, 1, 'Espacio fusiÃ³n', 'mm'),
(426, 'JPStaza02', 'millaresPorRollo', 'PAM8', 1, 1, 'Millares por rollo', 'millares'),
(427, 'JPStaza02', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', '% +/-'),
(428, 'JPStaza02', 'millaresPorPaquete', 'PAM10', 1, 1, 'Millares PP', 'millares'),
(429, 'EtiqAbierta', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(430, 'EtiqAbierta', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(431, 'EtiqAbierta', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(432, 'EtiqAbierta', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(433, 'EtiqAbierta', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(434, 'EtiqAbierta', 'millaresPorRollo', 'PAM6', 1, 1, 'Millares por rollo', 'millares'),
(435, 'EtiqAbierta', 'millaresPorPaquete', 'PAM7', 1, 1, 'Millares PP', 'millares'),
(436, 'EtiqAbierta', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', 'Porcentaje'),
(437, 'holograma', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(438, 'holograma', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(439, 'holograma', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(440, 'holograma', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(441, 'holograma', 'millaresPorRollo', 'PAM5', 1, 1, 'Millares por rollo', 'millares'),
(442, 'holograma', 'millaresPorPaquete', 'PAM6', 1, 1, 'Millares PP', 'millares'),
(443, 'holograma', 'porcentajeMPR', 'PAM7', 1, 1, '% +/- por rollo', 'Porcentaje'),
(444, 'JPSStickersin12', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(445, 'JPSStickersin12', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(446, 'JPSStickersin12', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(447, 'JPSStickersin12', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(448, 'JPSStickersin12', 'millaresPorRollo', 'PAM5', 1, 1, 'Millares por rollo', 'millares'),
(449, 'JPSStickersin12', 'millaresPorPaquete', 'PAM6', 1, 1, 'Millares PP', 'millares'),
(450, 'JPSStickersin12', 'porcentajeMPR', 'PAM7', 1, 1, '% +/- por rollo', 'Porcentaje'),
(458, 'JPSpicker12', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(459, 'JPSpicker12', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(460, 'JPSpicker12', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(461, 'JPSpicker12', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(462, 'JPSpicker12', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(463, 'JPSpicker12', 'millaresPorRollo', 'PAM6', 1, 1, 'Millares por rollo', 'millares'),
(464, 'JPSpicker12', 'millaresPorPaquete', 'PAM7', 1, 1, 'Millares PP', 'millares'),
(465, 'JPSpicker12', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', 'Porcentaje'),
(466, 'PKPREV2', 'longitud', 'PIMPFL', 0, 0, 'longitud', 'mts'),
(467, 'JPScaribe02', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(468, 'JPScaribe02', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(469, 'JPScaribe02', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(470, 'JPScaribe02', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(471, 'JPScaribe02', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(472, 'JPScaribe02', 'millaresPorRollo', 'PAM6', 1, 1, 'Millares por rollo', 'millares'),
(473, 'JPScaribe02', 'millaresPorPaquete', 'PAM7', 1, 1, 'Millares PP', 'millares'),
(474, 'JPScaribe02', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', 'Porcentaje'),
(475, 'JPSCaballito02', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(476, 'JPSCaballito02', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(477, 'JPSCaballito02', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(478, 'JPSCaballito02', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(479, 'JPSCaballito02', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(480, 'JPSCaballito02', 'anchoEtiqueta', 'PAM6', 1, 1, 'Ancho etiqueta', 'mm'),
(481, 'JPSCaballito02', 'espacioFusion', 'PAM7', 1, 1, 'Espacio fusiÃ³n', 'mm'),
(482, 'JPSCaballito02', 'millaresPorRollo', 'PAM8', 1, 1, 'Millares por rollo', 'millares'),
(483, 'JPSCaballito02', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', '% +/-'),
(484, 'JPSCaballito02', 'millaresPorPaquete', 'PAM10', 1, 0, 'Millares PP', 'millares'),
(485, 'JPSholi02', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(486, 'JPSholi02', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(487, 'JPSholi02', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(488, 'JPSholi02', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(489, 'JPSholi02', 'millaresPorRollo', 'PAM5', 1, 1, 'Millares por rollo', 'millares'),
(490, 'JPSholi02', 'millaresPorPaquete', 'PAM6', 1, 1, 'Millares PP', 'millares'),
(491, 'JPSholi02', 'porcentajeMPR', 'PAM7', 1, 1, '% +/- por rollo', 'Porcentaje'),
(492, 'JPSnescafe10', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(493, 'JPSnescafe10', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(494, 'JPSnescafe10', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(495, 'JPSnescafe10', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(496, 'JPSnescafe10', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(497, 'JPSnescafe10', 'millaresPorRollo', 'PAM6', 1, 1, 'Millares por rollo', 'millares'),
(498, 'JPSnescafe10', 'millaresPorPaquete', 'PAM7', 1, 1, 'Millares PP', 'millares'),
(499, 'JPSnescafe10', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', 'Porcentaje'),
(500, 'JPShologrami09', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(501, 'JPShologrami09', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(502, 'JPShologrami09', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(503, 'JPShologrami09', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(504, 'JPShologrami09', 'millaresPorRollo', 'PAM5', 1, 1, 'Millares por rollo', 'millares'),
(505, 'JPShologrami09', 'millaresPorPaquete', 'PAM6', 1, 1, 'Millares PP', 'millares'),
(506, 'JPShologrami09', 'porcentajeMPR', 'PAM7', 1, 1, '% +/- por rollo', 'Porcentaje'),
(507, 'JPSQuindio 201810', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(508, 'JPSQuindio 201810', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(509, 'JPSQuindio 201810', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(510, 'JPSQuindio 201810', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(511, 'JPSQuindio 201810', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(512, 'JPSQuindio 201810', 'millaresPorRollo', 'PAM6', 1, 1, 'Millares por rollo', 'millares'),
(513, 'JPSQuindio 201810', 'millaresPorPaquete', 'PAM7', 1, 1, 'Millares PP', 'millares'),
(514, 'JPSQuindio 201810', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', 'Porcentaje'),
(515, 'Predeterminados', 'espacioregistro', 'PAM11', 1, 1, 'Espacio registro', 'mm'),
(516, 'EtiqAbierta', 'espacioregistro', 'PAM10', 1, 1, 'Espacio registro', 'mm'),
(517, 'JPSnescafe10', 'espacioregistro', 'PAM10', 1, 1, 'Espacio registro', 'mm'),
(518, 'JPSQuindio 201810', 'espacioregistro', 'PAM10', 1, 1, 'Espacio registro', 'mm'),
(519, 'JPSChiva Cola Cola09', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(520, 'JPSChiva Cola Cola09', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(521, 'JPSChiva Cola Cola09', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(522, 'JPSChiva Cola Cola09', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(523, 'JPSChiva Cola Cola09', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(524, 'JPSChiva Cola Cola09', 'millaresPorRollo', 'PAM6', 1, 1, 'Millares por rollo', 'millares'),
(525, 'JPSChiva Cola Cola09', 'millaresPorPaquete', 'PAM7', 1, 1, 'Millares PP', 'millares'),
(526, 'JPSChiva Cola Cola09', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', 'Porcentaje'),
(527, 'JPSChiva Cola Cola09', 'espacioregistro', 'PAM10', 1, 1, 'Espacio registro', 'mm'),
(528, 'JPSManga Termoencogible09', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(529, 'JPSManga Termoencogible09', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(530, 'JPSManga Termoencogible09', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(531, 'JPSManga Termoencogible09', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(532, 'JPSManga Termoencogible09', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(533, 'JPSManga Termoencogible09', 'anchoEtiqueta', 'PAM6', 1, 1, 'Ancho etiqueta', 'mm'),
(534, 'JPSManga Termoencogible09', 'espacioFusion', 'PAM7', 1, 1, 'Espacio fusiÃ³n', 'mm'),
(535, 'JPSManga Termoencogible09', 'millaresPorRollo', 'PAM8', 1, 1, 'Millares por rollo', 'millares'),
(536, 'JPSManga Termoencogible09', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', '% +/-'),
(537, 'JPSManga Termoencogible09', 'millaresPorPaquete', 'PAM10', 1, 0, 'Millares PP', 'millares'),
(538, 'JPSManga Termoencogible09', 'espacioregistro', 'PAM11', 1, 1, 'Espacio registro', 'mm'),
(539, 'JPSEtiqueta abierta12', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(540, 'JPSEtiqueta abierta12', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(541, 'JPSEtiqueta abierta12', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(542, 'JPSEtiqueta abierta12', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(543, 'JPSEtiqueta abierta12', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(544, 'JPSEtiqueta abierta12', 'millaresPorRollo', 'PAM6', 1, 1, 'Millares por rollo', 'millares'),
(545, 'JPSEtiqueta abierta12', 'millaresPorPaquete', 'PAM7', 1, 1, 'Millares PP', 'millares'),
(546, 'JPSEtiqueta abierta12', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', 'Porcentaje'),
(547, 'JPSEtiqueta abierta12', 'espacioregistro', 'PAM10', 1, 1, 'Espacio registro', 'mm'),
(548, 'JPSFail12', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(549, 'JPSFail12', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(550, 'JPSFail12', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(551, 'JPSFail12', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(552, 'JPSFail12', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(553, 'JPSFail12', 'millaresPorRollo', 'PAM6', 1, 1, 'Millares por rollo', 'millares'),
(554, 'JPSFail12', 'millaresPorPaquete', 'PAM7', 1, 1, 'Millares PP', 'millares'),
(555, 'JPSFail12', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', 'Porcentaje'),
(556, 'JPSFail12', 'espacioregistro', 'PAM10', 1, 1, 'Espacio registro', 'mm'),
(557, 'JPSPruebacorte09', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(558, 'JPSPruebacorte09', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(559, 'JPSPruebacorte09', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(560, 'JPSPruebacorte09', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(561, 'JPSPruebacorte09', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(562, 'JPSPruebacorte09', 'millaresPorRollo', 'PAM6', 1, 1, 'Millares por rollo', 'millares'),
(563, 'JPSPruebacorte09', 'millaresPorPaquete', 'PAM7', 1, 1, 'Millares PP', 'millares'),
(564, 'JPSPruebacorte09', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', 'Porcentaje'),
(565, 'JPSPruebacorte09', 'espacioregistro', 'PAM10', 1, 1, 'Espacio registro', 'mm'),
(566, 'JPSPruebacorte09', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(567, 'JPSPruebacorte09', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(568, 'JPSPruebacorte09', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(569, 'JPSPruebacorte09', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(570, 'JPSPruebacorte09', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(571, 'JPSPruebacorte09', 'millaresPorRollo', 'PAM6', 1, 1, 'Millares por rollo', 'millares'),
(572, 'JPSPruebacorte09', 'millaresPorPaquete', 'PAM7', 1, 1, 'Millares PP', 'millares'),
(573, 'JPSPruebacorte09', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', 'Porcentaje'),
(574, 'JPSPruebacorte09', 'espacioregistro', 'PAM10', 1, 1, 'Espacio registro', 'mm'),
(575, 'JPSpruebacorte09', 'codigoImpresion', 'PAM1', 1, 1, 'CÃ³digo', 'codigo de impresiÃ³n'),
(576, 'JPSpruebacorte09', 'descripcionImpresion', 'PAM2', 1, 1, 'DescripciÃ³n', 'descripciÃ³n'),
(577, 'JPSpruebacorte09', 'anchoPelicula', 'PAM3', 1, 1, 'Ancho pelicula', 'mm'),
(578, 'JPSpruebacorte09', 'alturaEtiqueta', 'PAM4', 1, 1, 'Altura etiqueta', 'mm'),
(579, 'JPSpruebacorte09', 'tintas', 'PAM5', 1, 1, 'Tintas', 'numero de tintas'),
(580, 'JPSpruebacorte09', 'millaresPorRollo', 'PAM6', 1, 1, 'Millares por rollo', 'millares'),
(581, 'JPSpruebacorte09', 'millaresPorPaquete', 'PAM7', 1, 1, 'Millares PP', 'millares'),
(582, 'JPSpruebacorte09', 'porcentajeMPR', 'PAM9', 1, 1, '% +/- por rollo', 'Porcentaje'),
(583, 'JPSpruebacorte09', 'espacioregistro', 'PAM10', 1, 1, 'Espacio registro', 'mm');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juegoprocesos`
--

CREATE TABLE `juegoprocesos` (
  `id` int(3) NOT NULL,
  `identificadorJuego` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `descripcionProceso` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `numeroProceso` int(11) NOT NULL,
  `referenciaProceso` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `tablero` int(1) NOT NULL DEFAULT '1',
  `registro` int(1) NOT NULL DEFAULT '1',
  `baja` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `juegoprocesos`
--

INSERT INTO `juegoprocesos` (`id`, `identificadorJuego`, `descripcionProceso`, `numeroProceso`, `referenciaProceso`, `tablero`, `registro`, `baja`) VALUES
(20, 'JPPManga01', 'programado', 0, 'PCSTER0', 1, 0, 1),
(21, 'JPPManga01', 'impresion', 1, 'PCSTER1', 1, 1, 1),
(22, 'JPPManga01', 'refilado', 2, 'PCSTER2', 1, 1, 1),
(23, 'JPPManga01', 'fusion', 3, 'PCSTER3', 1, 1, 1),
(24, 'JPPManga01', 'revision', 4, 'PCSTER4', 1, 1, 1),
(25, 'JPPManga01', 'corte', 5, 'PCSTER5', 1, 1, 1),
(26, 'JPPManga01', 'caja', 0, 'PCSTER6', 1, 0, 1),
(27, 'JPPManga01', 'rollo', 0, 'PCSTER7', 1, 0, 1),
(45, 'JPFlexografia05', 'programado', 0, 'PCS0', 1, 0, 1),
(46, 'JPFlexografia05', 'impresion-flexografica', 1, 'PCS1', 1, 1, 1),
(48, 'JPFlexografia05', 'fusion', 3, 'PCS3', 1, 1, 1),
(49, 'JPFlexografia05', 'revision', 4, 'PCS4', 1, 1, 1),
(50, 'JPFlexografia05', 'corte', 5, 'PCS5', 1, 1, 1),
(51, 'JPFlexografia05', 'caja', 0, 'PCS6', 1, 0, 1),
(52, 'JPFlexografia05', 'rollo', 0, 'PCS7', 1, 0, 1),
(53, 'JPFlexografia05', 'Embarque', 0, 'PCS8', 1, 0, 0),
(54, 'JPFlexografia05', 'refilado', 2, '', 1, 1, 1),
(55, 'JPPManga01', 'Embarque', 0, '', 1, 0, 0),
(59, 'JPBS08', 'programado', 0, 'PCS0', 1, 0, 1),
(61, 'JPBS08', 'refilado', 1, 'PCS2', 1, 1, 1),
(62, 'JPBS08', 'laminado', 2, 'PCS3', 1, 1, 1),
(63, 'JPBS08', 'sliteo', 3, '', 1, 1, 1),
(68, 'JPPB08', 'programado', 1, 'PCS0', 1, 1, 1),
(69, 'JPPB08', '', 2, 'PCS1', 1, 1, 1),
(70, 'JPPB08', '', 3, 'PCS2', 1, 1, 1),
(71, 'JPPB08', '', 4, 'PCS3', 1, 1, 1),
(72, 'JPPB08', '', 5, 'PCS4', 1, 1, 1),
(73, 'JPPB08', '', 6, 'PCS5', 1, 1, 1),
(74, 'JPawsda08', 'programado', 0, 'PCS0', 1, 1, 1),
(75, 'JPawsda08', 'impresion', 1, 'PCS1', 1, 1, 1),
(76, 'JPawsda08', 'laminado', 2, 'PCS2', 1, 1, 1),
(77, 'JPawsda08', 'embosado', 3, 'PCS3', 1, 1, 1),
(78, 'JPawsda08', 'revision', 4, 'PCS4', 1, 1, 1),
(79, 'JPawsda08', 'rollo', 0, 'PCS5', 1, 1, 1),
(80, 'JPawsda08', 'caja', 0, 'PCS6', 1, 1, 1),
(81, 'JPAdilub10', 'programado', 0, 'PCS0', 1, 1, 1),
(82, 'JPAdilub10', 'impresion', 1, 'PCS1', 1, 1, 1),
(83, 'JPAdilub10', 'refilado', 2, 'PCS2', 1, 1, 1),
(84, 'JPAdilub10', 'revision', 3, 'PCS3', 1, 1, 1),
(85, 'JPQuindio10', 'programado', 0, 'PCS0', 1, 1, 1),
(86, 'JPQuindio10', '', 1, 'PCS1', 1, 1, 1),
(87, 'JPQuindio10', '', 2, 'PCS2', 1, 1, 1),
(88, 'JPQuindio10', '', 3, 'PCS3', 1, 1, 1),
(89, 'JPQuindio10', '', 4, 'PCS4', 1, 1, 1),
(90, 'JPQuindio10', '', 5, 'PCS5', 1, 1, 1),
(91, 'JPQuindio10', '', 6, 'PCS6', 1, 1, 1),
(92, 'JPQuindio10', '', 7, 'PCS7', 1, 1, 1),
(93, 'JPPT11', 'programado', 0, 'PCS0', 1, 0, 1),
(94, 'JPPT11', 'caja', 0, 'PCS0', 1, 0, 1),
(95, 'JPPT11', 'rollo', 0, 'PCS0', 1, 0, 1),
(96, 'JPPT11', 'corte', 1, 'PCS1', 1, 1, 1),
(97, 'JPPT11', 'revision', 2, 'PCS2', 1, 1, 1),
(98, 'JPPT11', 'fusion', 3, 'PCS3', 1, 1, 1),
(99, 'JPPT11', 'refilado', 4, 'PCS4', 1, 1, 1),
(190, 'JPdorilokos12', 'programado', 0, 'PCS0', 1, 0, 1),
(191, 'JPdorilokos12', 'caja', 0, 'PCS0', 1, 0, 1),
(192, 'JPdorilokos12', 'rollo', 0, 'PCS0', 1, 0, 1),
(193, 'JPdorilokos12', 'impresion-flexografica', 1, 'PCS1', 1, 1, 1),
(194, 'JPdorilokos12', 'refilado', 2, 'PCS2', 1, 1, 1),
(195, 'JPdorilokos12', 'revision', 3, 'PCS3', 1, 1, 1),
(196, 'JPdorilokos12', 'foliado', 4, 'PCS4', 1, 1, 1),
(197, 'JPdorilokos12', 'revision 2', 5, 'PCS5', 1, 1, 1),
(198, 'JPEtiq_Adilub12', 'programado', 0, 'PCS0', 1, 0, 1),
(199, 'JPEtiq_Adilub12', 'caja', 0, 'PCS0', 1, 0, 1),
(200, 'JPEtiq_Adilub12', 'rollo', 0, 'PCS0', 1, 0, 1),
(201, 'JPEtiq_Adilub12', 'impresion-flexografica', 1, 'PCS1', 1, 1, 1),
(202, 'JPEtiq_Adilub12', 'refilado', 2, 'PCS2', 1, 1, 1),
(203, 'JPEtiq_Adilub12', 'revision', 3, 'PCS3', 1, 1, 1),
(204, 'JPPB10', 'programado', 0, 'PCS0', 1, 0, 1),
(205, 'JPPB10', 'caja', 0, 'PCS0', 1, 0, 1),
(206, 'JPPB10', 'rollo', 0, 'PCS0', 1, 0, 1),
(207, 'JPPB10', 'impresion', 1, 'PCS1', 1, 1, 1),
(208, 'JPPB10', 'refilado', 2, 'PCS2', 1, 1, 1),
(209, 'JPPB10', 'laminado', 3, 'PCS3', 1, 1, 1),
(210, 'JPPB10', 'fusion', 4, 'PCS4', 1, 1, 1),
(211, 'JPPB10', 'corte', 5, 'PCS5', 1, 1, 1),
(212, 'JPPB10', 'revision', 6, 'PCS6', 1, 1, 1),
(213, 'JPKnotEtiq09', 'programado', 0, 'PCS0', 1, 0, 1),
(214, 'JPKnotEtiq09', 'caja', 0, 'PCS0', 1, 0, 1),
(215, 'JPKnotEtiq09', 'rollo', 0, 'PCS0', 1, 0, 1),
(216, 'JPKnotEtiq09', 'impresion-flexografica', 1, 'PCS1', 1, 1, 1),
(217, 'JPKnotEtiq09', 'refilado', 2, 'PCS2', 1, 1, 1),
(218, 'JPKnotEtiq09', 'corte', 3, 'PCS3', 1, 1, 1),
(219, 'JPSelf-Destruct01', 'programado', 0, 'PCS0', 1, 0, 1),
(220, 'JPSelf-Destruct01', 'caja', 0, 'PCS0', 1, 0, 1),
(221, 'JPSelf-Destruct01', 'rollo', 0, 'PCS0', 1, 0, 1),
(222, 'JPSelf-Destruct01', 'impresion-flexografica', 1, 'PCS1', 1, 1, 1),
(223, 'JPSelf-Destruct01', 'troquelado', 2, 'PCS2', 1, 1, 1),
(224, 'JPSelf-Destruct01', 'foliado', 3, 'PCS3', 1, 1, 1),
(225, 'JPSelf-Destruct01', 'suajado', 4, 'PCS4', 1, 1, 1),
(259, 'JPPrank08', 'programado', 0, 'PCS0', 1, 0, 1),
(260, 'JPPrank08', 'caja', 0, 'PCS0', 1, 0, 1),
(261, 'JPPrank08', 'rollo', 0, 'PCS0', 1, 0, 1),
(262, 'JPPrank08', 'impresion-flexografica', 1, 'PCS1', 1, 1, 1),
(263, 'JPPrank08', 'refilado', 2, 'PCS2', 1, 1, 1),
(264, 'JPPrank08', 'revision', 3, 'PCS3', 1, 1, 1),
(265, 'JPPrank08', 'foliado', 4, 'PCS4', 1, 1, 1),
(266, 'JPPrank08', 'revision 2', 5, 'PCS5', 1, 1, 1),
(267, 'JPPrueba11', 'programado', 0, 'PCS0', 1, 0, 1),
(268, 'JPPrueba11', 'caja', 0, 'PCS0', 1, 0, 1),
(269, 'JPPrueba11', 'rollo', 0, 'PCS0', 1, 0, 1),
(270, 'JPPrueba11', 'impresion-flexografica', 1, 'PCS1', 1, 1, 1),
(271, 'JPPrueba11', 'refilado', 2, 'PCS2', 1, 1, 1),
(272, 'JPPrueba11', 'revision', 3, 'PCS3', 1, 1, 1),
(273, 'JPPrueba11', 'foliado', 4, 'PCS4', 1, 1, 1),
(274, 'JPPrueba11', 'revision 2', 5, 'PCS5', 1, 1, 1),
(278, 'JPSticker08', 'programado', 0, 'PCS0', 1, 0, 1),
(279, 'JPSticker08', 'caja', 0, 'PCS0', 1, 0, 1),
(280, 'JPSticker08', 'rollo', 0, 'PCS0', 1, 0, 1),
(281, 'JPSticker08', 'laminado', 1, 'PCS1', 1, 1, 1),
(282, 'JPSticker08', 'embosado', 2, 'PCS2', 1, 1, 1),
(283, 'JPSticker08', 'laminado 2', 3, 'PCS3', 1, 1, 1),
(284, 'JPTechno09', 'programado', 0, 'PCS0', 1, 0, 1),
(285, 'JPTechno09', 'caja', 0, 'PCS0', 1, 0, 1),
(286, 'JPTechno09', 'rollo', 0, 'PCS0', 1, 0, 1),
(287, 'JPTechno09', 'impresion', 1, 'PCS1', 1, 1, 1),
(288, 'JPTechno09', 'laminado', 2, 'PCS2', 1, 1, 1),
(289, 'JPTechno09', 'laminado 2', 3, 'PCS3', 1, 1, 1),
(290, 'JPtaza02', 'programado', 0, 'PCS0', 1, 0, 1),
(291, 'JPtaza02', 'caja', 0, 'PCS0', 1, 0, 1),
(292, 'JPtaza02', 'rollo', 0, 'PCS0', 1, 0, 1),
(293, 'JPtaza02', 'impresion-flexografica', 1, 'PCS1', 1, 1, 1),
(294, 'JPtaza02', 'programado', 0, 'PCS0', 1, 0, 1),
(295, 'JPtaza02', 'caja', 0, 'PCS0', 1, 0, 1),
(296, 'JPtaza02', 'rollo', 0, 'PCS0', 1, 0, 1),
(297, 'JPtaza02', 'impresion-flexografica', 1, 'PCS1', 1, 1, 1),
(298, 'JPtaza02', 'foliado', 2, 'PCS2', 1, 1, 1),
(299, 'JPtaza02', 'revision 2', 3, 'PCS3', 1, 1, 1),
(300, 'JPtaza02', 'troquelado', 4, 'PCS4', 1, 1, 1),
(301, 'JPStickersin12', 'programado', 0, 'PCS0', 1, 0, 1),
(302, 'JPStickersin12', 'caja', 0, 'PCS0', 1, 0, 1),
(303, 'JPStickersin12', 'rollo', 0, 'PCS0', 1, 0, 1),
(304, 'JPStickersin12', 'refilado', 1, 'PCS1', 1, 1, 1),
(305, 'JPStickersin12', 'revision', 2, 'PCS2', 1, 1, 1),
(306, 'JPStickersin12', 'troquelado', 3, 'PCS3', 1, 1, 1),
(307, 'JPStickersin12', 'programado', 0, 'PCS0', 1, 0, 1),
(308, 'JPStickersin12', 'caja', 0, 'PCS0', 1, 0, 1),
(309, 'JPStickersin12', 'rollo', 0, 'PCS0', 1, 0, 1),
(310, 'JPStickersin12', 'refilado', 1, 'PCS1', 1, 1, 1),
(311, 'JPStickersin12', 'revision', 2, 'PCS2', 1, 1, 1),
(312, 'JPStickersin12', 'troquelado', 3, 'PCS3', 1, 1, 1),
(313, 'JPpicker12', 'programado', 0, 'PCS0', 1, 0, 1),
(314, 'JPpicker12', 'caja', 0, 'PCS0', 1, 0, 1),
(315, 'JPpicker12', 'rollo', 0, 'PCS0', 1, 0, 1),
(316, 'JPpicker12', 'impresion', 1, 'PCS1', 1, 1, 1),
(317, 'JPpicker12', 'fusion', 2, 'PCS2', 1, 1, 1),
(318, 'JPpicker12', 'laminado', 3, 'PCS3', 1, 1, 1),
(319, 'JPpicker12', 'corte', 4, 'PCS4', 1, 1, 1),
(320, 'JPcaribe02', 'programado', 0, 'PCS0', 1, 0, 1),
(321, 'JPcaribe02', 'caja', 0, 'PCS0', 1, 0, 1),
(322, 'JPcaribe02', 'rollo', 0, 'PCS0', 1, 0, 1),
(323, 'JPcaribe02', 'impresion-flexografica', 1, 'PCS1', 1, 1, 1),
(324, 'JPcaribe02', 'refilado', 2, 'PCS2', 1, 1, 1),
(325, 'JPcaribe02', 'revision', 3, 'PCS3', 1, 1, 1),
(326, 'JPcaribe02', 'corte', 4, 'PCS4', 1, 1, 1),
(327, 'JPCaballito02', 'programado', 0, 'PCS0', 1, 0, 1),
(328, 'JPCaballito02', 'caja', 0, 'PCS0', 1, 0, 1),
(329, 'JPCaballito02', 'rollo', 0, 'PCS0', 1, 0, 1),
(330, 'JPCaballito02', 'impresion', 1, 'PCS1', 1, 1, 1),
(331, 'JPCaballito02', 'revision', 2, 'PCS2', 1, 1, 1),
(332, 'JPCaballito02', 'suajado', 3, 'PCS3', 1, 1, 1),
(333, 'JPCaballito02', 'embosado', 4, 'PCS4', 1, 1, 1),
(334, 'JPCaballito02', 'refilado', 5, 'PCS5', 1, 1, 1),
(339, 'JPholi02', 'programado', 0, 'PCS0', 1, 0, 1),
(340, 'JPholi02', 'caja', 0, 'PCS0', 1, 0, 1),
(341, 'JPholi02', 'rollo', 0, 'PCS0', 1, 0, 1),
(342, 'JPholi02', 'refilado', 1, 'PCS1', 1, 1, 1),
(343, 'JPholi02', 'foliado', 2, 'PCS2', 1, 1, 1),
(344, 'JPholi02', 'revision 2', 3, 'PCS3', 1, 1, 1),
(345, 'JPnescafe10', 'programado', 0, 'PCS0', 1, 0, 1),
(346, 'JPnescafe10', 'caja', 0, 'PCS0', 1, 0, 1),
(347, 'JPnescafe10', 'rollo', 0, 'PCS0', 1, 0, 1),
(348, 'JPnescafe10', 'impresion', 1, 'PCS1', 1, 1, 1),
(349, 'JPnescafe10', 'refilado', 2, 'PCS2', 1, 1, 1),
(350, 'JPnescafe10', 'revision', 3, 'PCS3', 1, 1, 1),
(351, 'JPnescafe10', 'corte', 4, 'PCS4', 1, 1, 1),
(352, 'JPhologrami09', 'programado', 0, 'PCS0', 1, 0, 1),
(353, 'JPhologrami09', 'caja', 0, 'PCS0', 1, 0, 1),
(354, 'JPhologrami09', 'rollo', 0, 'PCS0', 1, 0, 1),
(355, 'JPhologrami09', 'refilado', 1, 'PCS1', 1, 1, 1),
(356, 'JPhologrami09', 'suajado', 2, 'PCS2', 1, 1, 1),
(357, 'JPhologrami09', 'foliado', 3, 'PCS3', 1, 1, 1),
(358, 'JPQuindio 201810', 'programado', 0, 'PCS0', 1, 0, 1),
(359, 'JPQuindio 201810', 'caja', 0, 'PCS0', 1, 0, 1),
(360, 'JPQuindio 201810', 'rollo', 0, 'PCS0', 1, 0, 1),
(361, 'JPQuindio 201810', 'impresion-flexografica', 1, 'PCS1', 1, 1, 1),
(362, 'JPQuindio 201810', 'refilado', 2, 'PCS2', 1, 1, 1),
(363, 'JPQuindio 201810', 'revision', 3, 'PCS3', 1, 1, 1),
(364, 'JPQuindio 201810', 'foliado', 4, 'PCS4', 1, 1, 1),
(365, 'JPQuindio 201810', 'revision 2', 5, 'PCS5', 1, 1, 1),
(366, 'JPChiva cola08', 'programado', 0, 'PCS0', 1, 0, 1),
(367, 'JPChiva cola08', 'caja', 0, 'PCS0', 1, 0, 1),
(368, 'JPChiva cola08', 'rollo', 0, 'PCS0', 1, 0, 1),
(369, 'JPChiva cola08', 'impresion', 1, 'PCS1', 1, 1, 1),
(370, 'JPChiva cola08', 'refilado', 2, 'PCS2', 1, 1, 1),
(372, 'JPChiva Cola Cola09', 'programado', 0, 'PCS0', 1, 0, 1),
(373, 'JPChiva Cola Cola09', 'caja', 0, 'PCS0', 1, 0, 1),
(374, 'JPChiva Cola Cola09', 'rollo', 0, 'PCS0', 1, 0, 1),
(375, 'JPChiva Cola Cola09', 'impresion', 1, 'PCS1', 1, 1, 1),
(376, 'JPChiva Cola Cola09', 'refilado', 2, 'PCS2', 1, 1, 1),
(377, 'JPManga Termoencogible09', 'programado', 0, 'PCS0', 1, 0, 1),
(378, 'JPManga Termoencogible09', 'caja', 0, 'PCS0', 1, 0, 1),
(379, 'JPManga Termoencogible09', 'rollo', 0, 'PCS0', 1, 0, 1),
(380, 'JPManga Termoencogible09', 'impresion', 1, 'PCS1', 1, 1, 1),
(381, 'JPManga Termoencogible09', 'refilado', 2, 'PCS2', 1, 1, 1),
(382, 'JPManga Termoencogible09', 'fusion', 3, 'PCS3', 1, 1, 1),
(383, 'JPManga Termoencogible09', 'revision', 4, 'PCS4', 1, 1, 1),
(384, 'JPManga Termoencogible09', 'corte', 5, 'PCS5', 1, 1, 1),
(385, 'JPEtiqueta abierta12', 'programado', 0, 'PCS0', 1, 0, 1),
(387, 'JPEtiqueta abierta12', 'rollo', 0, 'PCS0', 1, 0, 1),
(388, 'JPEtiqueta abierta12', 'impresion', 1, 'PCS1', 1, 1, 1),
(389, 'JPEtiqueta abierta12', 'revision', 2, 'PCS2', 1, 1, 1),
(390, 'JPEtiqueta abierta12', 'laminado', 3, 'PCS3', 1, 1, 1),
(391, 'JPEtiqueta abierta12', 'refilado', 4, 'PCS4', 1, 1, 1),
(392, 'JPFail12', 'programado', 0, 'PCS0', 1, 0, 1),
(393, 'JPFail12', 'caja', 0, 'PCS0', 1, 0, 1),
(394, 'JPFail12', 'rollo', 0, 'PCS0', 1, 0, 1),
(395, 'JPFail12', 'refilado', 1, 'PCS1', 1, 1, 1),
(396, 'JPFail12', 'corte', 2, 'PCS2', 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juegoscilindros`
--

CREATE TABLE `juegoscilindros` (
  `IDCilindro` int(11) NOT NULL,
  `descripcionImpresion` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `identificadorCilindro` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `proveedor` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fechaRecepcion` date NOT NULL,
  `diametro` float NOT NULL,
  `tabla` float NOT NULL,
  `registro` float NOT NULL,
  `repAlPaso` int(11) NOT NULL,
  `repAlGiro` float NOT NULL,
  `girosGarantizados` float NOT NULL,
  `viscosidad` float DEFAULT NULL,
  `velocidad` float DEFAULT NULL,
  `presionCilindro` float DEFAULT NULL,
  `presionGoma` float DEFAULT NULL,
  `presionRasqueta` float DEFAULT NULL,
  `tolViscosidad` float DEFAULT NULL,
  `tolVelocidad` float DEFAULT NULL,
  `tolCilindro` float DEFAULT NULL,
  `tolTemperatura` float DEFAULT NULL,
  `temperatura` float DEFAULT NULL,
  `tolGoma` float DEFAULT NULL,
  `tolRasqueta` float DEFAULT NULL,
  `baja` int(11) NOT NULL DEFAULT '1',
  `alturaReal` float NOT NULL,
  `anchuraReal` float DEFAULT NULL,
  `desgaste` float NOT NULL,
  `grupo` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `juegoscilindros`
--

INSERT INTO `juegoscilindros` (`IDCilindro`, `descripcionImpresion`, `identificadorCilindro`, `proveedor`, `fechaRecepcion`, `diametro`, `tabla`, `registro`, `repAlPaso`, `repAlGiro`, `girosGarantizados`, `viscosidad`, `velocidad`, `presionCilindro`, `presionGoma`, `presionRasqueta`, `tolViscosidad`, `tolVelocidad`, `tolCilindro`, `tolTemperatura`, `temperatura`, `tolGoma`, `tolRasqueta`, `baja`, `alturaReal`, `anchuraReal`, `desgaste`, `grupo`) VALUES
(2, 'Thinner estandar 960ml', 'WR8051845', 'Importado', '2018-06-04', 175, 400, 0, 3, 2, 1000000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 274.889, 127, 0, ''),
(3, 'Comex prueba', 'HTYPLAYRIO', 'Importados', '2018-05-29', 133, 400, 0, 3, 3, 1000, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 1, 139.277, 133.333, 0, ''),
(4, 'Thinner estandar 960ml', 'WR8051838', 'Importado', '2018-06-04', 175, 400, 0, 3, 2, 1000000, 22, 90, 80, 80, 40, 4, 20, 20, 3, 40, 20, 20, 1, 274.889, 127, 0, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juegoscireles`
--

CREATE TABLE `juegoscireles` (
  `id` int(11) NOT NULL,
  `producto` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `identificadorJuego` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `num_dientes` float NOT NULL,
  `ancho_plano` float NOT NULL,
  `repeticiones` int(11) NOT NULL,
  `no_placa` int(11) NOT NULL,
  `fecha_entrega` date NOT NULL,
  `observaciones` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `alturaReal` float NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1',
  `descripcionImpresion` varchar(70) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lotes`
--

CREATE TABLE `lotes` (
  `idLote` int(11) NOT NULL,
  `bloque` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `referenciaLote` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `longitud` double NOT NULL,
  `peso` double NOT NULL,
  `tarima` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `estado` int(11) NOT NULL DEFAULT '0',
  `shower` int(11) NOT NULL DEFAULT '1',
  `noop` int(11) NOT NULL,
  `ancho` float NOT NULL,
  `espesor` float NOT NULL,
  `encogimiento` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `numeroLote` int(11) NOT NULL,
  `juegoLote` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `noLote` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `unidades` double NOT NULL,
  `anchuraBloque` double NOT NULL,
  `tipo` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `lotes`
--

INSERT INTO `lotes` (`idLote`, `bloque`, `referenciaLote`, `longitud`, `peso`, `tarima`, `estado`, `shower`, `noop`, `ancho`, `espesor`, `encogimiento`, `numeroLote`, `juegoLote`, `noLote`, `unidades`, `anchuraBloque`, `tipo`, `baja`) VALUES
(22, 'Bopp blanco 35 micras', '03081614-34', 1900, 32, '030816-14.600.35-05', 5, 0, 13, 600, 35, '0', 1, 'JLEti2018-06-131528837418', '030816-14.600.35-05 | 1', 20.735642386563, 400, 'Etiqueta abierta', 1),
(23, 'Bopp blanco 35 micras', '03081614-35', 2600, 45, '030816-14.600.35-05', 5, 0, 14, 600, 35, '0', 2, 'JLEti2018-06-131528837418', '030816-14.600.35-05 | 2', 28.375089581613, 400, 'Etiqueta abierta', 1),
(24, 'Bopp blanco 35 micras', '03081614-36', 2100, 36, '030816-14.600.35-05', 5, 0, 15, 600, 35, '0', 3, 'JLEti2018-06-131528837418', '030816-14.600.35-05 | 3', 22.918341585149, 400, 'Etiqueta abierta', 1),
(25, 'Bopp blanco 35 micras', '03081614-55', 3000, 52, '030816-14.600.35-05', 5, 0, 16, 600, 35, '0', 4, 'JLEti2018-06-131528837418', '030816-14.600.35-05 | 4', 32.740487978784, 400, 'Etiqueta abierta', 1),
(26, 'Bopp blanco 35 micras', '03081614-56', 2300, 40, '030816-14.600.35-05', 5, 0, 17, 600, 35, '0', 5, 'JLEti2018-06-131528837418', '030816-14.600.35-05 | 5', 25.101040783735, 400, 'Etiqueta abierta', 1),
(27, 'Bopp blanco 35 micras', '03081614-57', 3400, 59, '030816-14.600.35-05', 5, 0, 18, 600, 35, '0', 6, 'JLEti2018-06-131528837418', '030816-14.600.35-05 | 6', 37.105886375955, 400, 'Etiqueta abierta', 1),
(28, 'Bopp blanco 35 micras', '03081614-58', 2800, 48, '030816-14.600.35-05', 5, 0, 19, 600, 35, '0', 7, 'JLEti2018-06-131528837418', '030816-14.600.35-05 | 7', 30.557788780199, 400, 'Etiqueta abierta', 1),
(29, 'Bopp blanco 35 micras', '03081614-59', 2800, 48, '030816-14.600.35-05', 5, 0, 20, 600, 35, '0', 8, 'JLEti2018-06-131528837418', '030816-14.600.35-05 | 8', 30.557788780199, 400, 'Etiqueta abierta', 1),
(30, 'Bopp blanco 35 micras', '03081614-60', 2500, 43, '030816-14.600.35-05', 5, 0, 21, 600, 35, '0', 9, 'JLEti2018-06-131528837418', '030816-14.600.35-05 | 9', 27.28373998232, 400, 'Etiqueta abierta', 1),
(31, 'Bopp blanco 35 micras', '03081614-61', 1400, 24, '030816-14.600.35-05', 5, 0, 22, 600, 35, '0', 10, 'JLEti2018-06-131528837418', '030816-14.600.35-05 | 10', 15.278894390099, 400, 'Etiqueta abierta', 1),
(32, 'Bopp blanco 35 micras', '03081614-62', 1500, 26, '030816-14.600.35-05', 5, 0, 23, 600, 35, '0', 11, 'JLEti2018-06-131528837418', '030816-14.600.35-05 | 11', 16.370243989392, 400, 'Etiqueta abierta', 1),
(33, 'Bopp blanco 35 micras', '03081614-63', 1000, 17, '030816-14.600.35-05', 5, 0, 24, 600, 35, '0', 12, 'JLEti2018-06-131528837418', '030816-14.600.35-05 | 12', 10.913495992928, 400, 'Etiqueta abierta', 1),
(34, 'Bopp blanco 35 micras', '03081614-64', 3600, 52, '030816-14.600.35-05', 5, 0, 25, 600, 35, '0', 13, 'JLEti2018-06-131528902116', '030816-14.600.35-05 | 13', 39.288585574541, 400, 'Etiqueta abierta', 1),
(35, 'Bopp blanco 35 micras', '03081614-65', 3300, 47, '030816-14.600.35-05', 5, 0, 26, 600, 35, '0', 14, 'JLEti2018-06-131528902116', '030816-14.600.35-05 | 14', 36.014536776663, 400, 'Etiqueta abierta', 1),
(36, 'Bopp blanco 35 micras', '03081614-66', 1600, 23, '030816-14.600.35-05', 5, 0, 27, 600, 35, '0', 15, 'JLEti2018-06-131528902116', '030816-14.600.35-05 | 15', 17.461593588685, 400, 'Etiqueta abierta', 1),
(37, 'Bopp blanco 35 micras', '03081614-67', 1600, 23, '030816-14.600.35-05', 5, 0, 28, 600, 35, '0', 16, 'JLEti2018-06-131528902116', '030816-14.600.35-05 | 16', 17.461593588685, 400, 'Etiqueta abierta', 1),
(38, 'Bopp blanco 35 micras', '03081614-68', 1950, 28, '030816-14.600.35-05', 5, 0, 29, 600, 35, '0', 17, 'JLEti2018-06-131528902116', '030816-14.600.35-05 | 17', 21.28131718621, 400, 'Etiqueta abierta', 1),
(39, 'Bopp blanco 35 micras', '03081614-69', 2600, 37, '030816-14.600.35-05', 5, 0, 30, 600, 35, '0', 18, 'JLEti2018-06-131528902116', '030816-14.600.35-05 | 18', 28.375089581613, 400, 'Etiqueta abierta', 1),
(40, 'Bopp blanco 35 micras', '03081614-70', 1800, 26, '030816-14.600.35-05', 5, 0, 31, 600, 35, '0', 19, 'JLEti2018-06-131528902116', '030816-14.600.35-05 | 19', 19.64429278727, 400, 'Etiqueta abierta', 1),
(41, 'Bopp blanco 35 micras', '03081614-71', 1500, 22, '030816-14.600.35-05', 5, 0, 32, 600, 35, '0', 20, 'JLEti2018-06-131528902116', '030816-14.600.35-05 | 20', 16.370243989392, 400, 'Etiqueta abierta', 1),
(42, 'Bopp blanco 35 micras', '03081614-72', 2000, 0, '030816-14.600.35-05', 5, 0, 33, 600, 35, '0', 21, 'JLEti2018-06-131528902116', '030816-14.600.35-05 | 21', 21.826991985856, 400, 'Etiqueta abierta', 1),
(43, 'Bopp blanco 35 micras', '03081614-37', 2100, 0, '030816-14.600.35-03', 5, 0, 34, 600, 35, '0', 21, 'JLEti2018-06-131528902116', '030816-14.600.35-03 | 21', 22.918341585149, 400, 'Etiqueta abierta', 1),
(44, 'Bopp blanco 35 micras', '03081614-1', 1850, 26.5, '030816-14.600.35-01', 5, 0, 35, 600, 35, '0', 1, 'JLEti2018-06-271530120684', '030816-14.600.35-01 | 1', 20.189967586917, 400, 'Etiqueta abierta', 1),
(46, 'Bopp blanco 35 micras', '03081614-53', 1850, 26.5, '030816-14.600.35-03', 5, 0, 60, 600, 35, '0', 1, 'JLEti2018-06-271531505555', '030816-14.600.35-03 | 1', 20.189967586917, 400, 'Etiqueta abierta', 1),
(47, 'Bopp blanco 35 micras', '03081614-2', 1850, 26.5, '030816-14.600.35-01', 5, 0, 36, 600, 35, '0', 2, 'JLEti2018-06-271530120684', '030816-14.600.35-01 | 2', 20.189967586917, 400, 'Etiqueta abierta', 1),
(48, 'Bopp blanco 35 micras', '03081614-3', 1850, 26.5, '030816-14.600.35-01', 5, 0, 37, 600, 35, '0', 3, 'JLEti2018-06-271530120684', '030816-14.600.35-01 | 3', 20.189967586917, 400, 'Etiqueta abierta', 1),
(49, 'Bopp blanco 35 micras', '03081614-4', 1850, 26.5, '030816-14.600.35-01', 5, 0, 38, 600, 35, '0', 4, 'JLEti2018-06-271530120684', '030816-14.600.35-01 | 4', 20.189967586917, 400, 'Etiqueta abierta', 1),
(50, 'Bopp blanco 35 micras', '03081614-5', 1850, 26.5, '030816-14.600.35-01', 5, 0, 39, 600, 35, '0', 5, 'JLEti2018-06-271530120684', '030816-14.600.35-01 | 5', 20.189967586917, 400, 'Etiqueta abierta', 1),
(51, 'Bopp blanco 35 micras', '03081614-6', 1850, 26.5, '030816-14.600.35-01', 5, 0, 40, 600, 35, '0', 6, 'JLEti2018-06-271530120684', '030816-14.600.35-01 | 6', 20.189967586917, 400, 'Etiqueta abierta', 1),
(52, 'Bopp blanco 35 micras', '03081614-7', 1850, 26.5, '030816-14.600.35-01', 5, 0, 41, 600, 35, '0', 7, 'JLEti2018-06-271530120684', '030816-14.600.35-01 | 7', 20.189967586917, 400, 'Etiqueta abierta', 1),
(53, 'Bopp blanco 35 micras', '03081614-8', 1850, 26.5, '030816-14.600.35-01', 5, 0, 42, 600, 35, '0', 8, 'JLEti2018-06-271530120684', '030816-14.600.35-01 | 8', 20.189967586917, 400, 'Etiqueta abierta', 1),
(54, 'Bopp blanco 35 micras', '03081614-9', 1850, 26.5, '030816-14.600.35-01', 5, 0, 43, 600, 35, '0', 9, 'JLEti2018-06-271530120684', '030816-14.600.35-01 | 9', 20.189967586917, 400, 'Etiqueta abierta', 1),
(55, 'Bopp blanco 35 micras', '03081614-10', 1850, 26.5, '030816-14.600.35-01', 5, 0, 44, 600, 35, '0', 10, 'JLEti2018-06-271530120684', '030816-14.600.35-01 | 10', 20.189967586917, 400, 'Etiqueta abierta', 1),
(56, 'Bopp blanco 35 micras', '03081614-11', 1850, 26.5, '030816-14.600.35-01', 5, 0, 45, 600, 35, '0', 11, 'JLEti2018-06-271530120684', '030816-14.600.35-01 | 11', 20.189967586917, 400, 'Etiqueta abierta', 1),
(57, 'Bopp blanco 35 micras', '03081614-12', 1850, 26.5, '030816-14.600.35-01', 5, 0, 46, 600, 35, '0', 12, 'JLEti2018-06-271530120684', '030816-14.600.35-01 | 12', 20.189967586917, 400, 'Etiqueta abierta', 1),
(58, 'Bopp blanco 35 micras', '03081614-13', 1850, 26.5, '030816-14.600.35-01', 5, 0, 47, 600, 35, '0', 13, 'JLEti2018-06-271530120684', '030816-14.600.35-01 | 13', 20.189967586917, 400, 'Etiqueta abierta', 1),
(59, 'Bopp blanco 35 micras', '03081614-14', 1850, 26.5, '030816-14.600.35-01', 5, 0, 48, 600, 35, '0', 14, 'JLEti2018-06-271530120684', '030816-14.600.35-01 | 14', 20.189967586917, 400, 'Etiqueta abierta', 1),
(60, 'Bopp blanco 35 micras', '03081614-15', 1850, 26.5, '030816-14.600.35-01', 5, 0, 49, 600, 35, '0', 15, 'JLEti2018-06-271530120684', '030816-14.600.35-01 | 15', 20.189967586917, 400, 'Etiqueta abierta', 1),
(61, 'Bopp blanco 35 micras', '03081614-16', 1850, 26.5, '030816-14.600.35-01', 5, 0, 50, 600, 35, '0', 16, 'JLEti2018-06-271530120684', '030816-14.600.35-01 | 16', 20.189967586917, 400, 'Etiqueta abierta', 1),
(62, 'Bopp blanco 35 micras', '03081614-17', 1850, 26.5, '030816-14.600.35-01', 5, 0, 51, 600, 35, '0', 17, 'JLEti2018-06-271530120684', '030816-14.600.35-01 | 17', 20.189967586917, 400, 'Etiqueta abierta', 1),
(63, 'Bopp blanco 35 micras', '03081614-18', 1850, 26.5, '030816-14.600.35-01', 5, 0, 52, 600, 35, '0', 18, 'JLEti2018-06-271530120684', '030816-14.600.35-01 | 18', 20.189967586917, 400, 'Etiqueta abierta', 1),
(64, 'Bopp blanco 35 micras', '03081614-19', 1850, 26.5, '030816-14.600.35-02', 5, 0, 53, 600, 35, '0', 18, 'JLEti2018-06-271530120684', '030816-14.600.35-02 | 18', 20.189967586917, 400, 'Etiqueta abierta', 1),
(65, 'Bopp blanco 35 micras', '03081614-20', 1850, 26.5, '030816-14.600.35-02', 5, 0, 54, 600, 35, '0', 19, 'JLEti2018-06-271530120684', '030816-14.600.35-02 | 19', 20.189967586917, 400, 'Etiqueta abierta', 1),
(66, 'Bopp blanco 35 micras', '03081614-21', 1850, 26.5, '030816-14.600.35-02', 5, 0, 55, 600, 35, '0', 20, 'JLEti2018-06-271530120684', '030816-14.600.35-02 | 20', 20.189967586917, 400, 'Etiqueta abierta', 1),
(67, 'Bopp blanco 35 micras', '03081614-22', 1850, 26.5, '030816-14.600.35-02', 5, 0, 56, 600, 35, '0', 21, 'JLEti2018-06-271530120684', '030816-14.600.35-02 | 21', 20.189967586917, 400, 'Etiqueta abierta', 1),
(68, 'Bopp blanco 35 micras', '03081614-23', 1850, 26.5, '030816-14.600.35-02', 5, 0, 57, 600, 35, '0', 22, 'JLEti2018-06-271530120684', '030816-14.600.35-02 | 22', 20.189967586917, 400, 'Etiqueta abierta', 1),
(69, 'Bopp blanco 35 micras', '03081614-24', 1850, 26.5, '030816-14.600.35-02', 5, 0, 58, 600, 35, '0', 23, 'JLEti2018-06-271530120684', '030816-14.600.35-02 | 23', 20.189967586917, 400, 'Etiqueta abierta', 1),
(70, 'Bopp blanco 35 micras', '03081614-25', 1850, 26.5, '030816-14.600.35-02', 5, 0, 59, 600, 35, '0', 24, 'JLEti2018-06-271530208405', '030816-14.600.35-02 | 24', 20.189967586917, 400, 'Etiqueta abierta', 1),
(71, 'Bopp blanco 35 micras', '03081614-26', 1850, 26.5, '030816-14.600.35-02', 5, 0, 61, 600, 35, '0', 25, 'JLEti2018-07-251532527046', '030816-14.600.35-02 | 25', 20.189967586917, 400, 'Etiqueta abierta', 1),
(72, 'Bopp blanco 35 micras', '03081614-27', 1850, 26.5, '030816-14.600.35-02', 1, 0, 62, 600, 35, '0', 26, 'JLEti2018-07-251532527046', '030816-14.600.35-02 | 26', 20.189967586917, 400, 'Etiqueta abierta', 1),
(73, 'Bopp blanco 35 micras', '03081614-28', 1850, 26.5, '030816-14.600.35-02', 1, 0, 63, 600, 35, '0', 27, 'JLEti2018-07-251532527046', '030816-14.600.35-02 | 27', 20.189967586917, 400, 'Etiqueta abierta', 1),
(74, 'Bopp blanco 35 micras', '03081614-29', 1850, 26.5, '030816-14.600.35-02', 1, 0, 64, 600, 35, '0', 28, 'JLEti2018-07-251532527046', '030816-14.600.35-02 | 28', 20.189967586917, 400, 'Etiqueta abierta', 1),
(75, 'Bopp blanco 35 micras', '03081614-30', 1850, 26.5, '030816-14.600.35-02', 1, 0, 65, 600, 35, '0', 29, 'JLEti2018-07-251532527046', '030816-14.600.35-02 | 29', 20.189967586917, 400, 'Etiqueta abierta', 1),
(76, 'Bopp blanco 35 micras', '03081614-31', 1850, 26.5, '030816-14.600.35-02', 1, 0, 66, 600, 35, '0', 30, 'JLEti2018-07-251532527046', '030816-14.600.35-02 | 30', 20.189967586917, 400, 'Etiqueta abierta', 1),
(77, 'Bopp blanco 35 micras', '03081614-32', 1850, 26.5, '030816-14.600.35-02', 1, 0, 67, 600, 35, '0', 31, 'JLEti2018-07-251532527046', '030816-14.600.35-02 | 31', 20.189967586917, 400, 'Etiqueta abierta', 1),
(78, 'Bopp blanco 35 micras', '03081614-33', 1850, 26.5, '030816-14.600.35-02', 1, 0, 68, 600, 35, '0', 32, 'JLEti2018-07-251532527046', '030816-14.600.35-02 | 32', 20.189967586917, 400, 'Etiqueta abierta', 1),
(79, 'Bopp blanco 35 micras', '03081614-38', 1850, 26.5, '030816-14.600.35-02', 1, 0, 69, 600, 35, '0', 33, 'JLEti2018-08-201534775694', '030816-14.600.35-02 | 33', 20.189967586917, 400, 'Etiqueta abierta', 1),
(80, 'Bopp blanco 35 micras', '03081614-39', 1850, 26.5, '030816-14.600.35-02', 1, 0, 70, 600, 35, '0', 34, 'JLEti2018-08-201534775694', '030816-14.600.35-02 | 34', 20.189967586917, 400, 'Etiqueta abierta', 1),
(81, 'Bopp blanco 35 micras', '03081614-40', 1850, 26.5, '030816-14.600.35-02', 5, 0, 71, 600, 35, '0', 35, 'JLEti2018-10-011538418981', '030816-14.600.35-02 | 35', 20.189967586917, 400, 'Etiqueta abierta', 1),
(82, 'Bopp blanco 35 micras', '03081614-41', 1850, 26.5, '030816-14.600.35-02', 1, 0, 72, 600, 35, '0', 36, 'JLEti2018-10-011538418981', '030816-14.600.35-02 | 36', 20.189967586917, 400, 'Etiqueta abierta', 1),
(83, 'Bopp blanco 35 micras', '03081614-42', 1850, 26.5, '030816-14.600.35-03', 1, 0, 73, 600, 35, '0', 36, 'JLEti2018-10-011538418981', '030816-14.600.35-03 | 36', 20.189967586917, 400, 'Etiqueta abierta', 1),
(84, 'Bopp blanco 35 micras', '03081614-43', 1850, 26.5, '030816-14.600.35-03', 2, 0, 0, 600, 35, '0', 37, '', '', 0, 0, '', 1),
(85, 'Bopp blanco 35 micras', '03081614-44', 1850, 26.5, '030816-14.600.35-03', 2, 0, 0, 600, 35, '0', 38, '', '', 0, 0, '', 1),
(86, 'Bopp blanco 35 micras', '03081614-45', 1850, 26.5, '030816-14.600.35-03', 2, 0, 0, 600, 35, '0', 39, '', '', 0, 0, '', 1),
(87, 'Bopp blanco 35 micras', '03081614-46', 1850, 26.5, '030816-14.600.35-03', 2, 0, 0, 600, 35, '0', 40, '', '', 0, 0, '', 1),
(88, 'Bopp blanco 35 micras', '03081614-47', 1850, 26.5, '030816-14.600.35-03', 2, 0, 0, 600, 35, '0', 41, '', '', 0, 0, '', 1),
(89, 'Bopp blanco 35 micras', '03081614-48', 1850, 26.5, '030816-14.600.35-03', 2, 0, 0, 600, 35, '0', 42, '', '', 0, 0, '', 1),
(90, 'Bopp blanco 35 micras', '03081614-49', 1850, 26.5, '030816-14.600.35-03', 2, 0, 0, 600, 35, '0', 43, '', '', 0, 0, '', 1),
(91, 'Bopp blanco 35 micras', '03081614-50', 1850, 26.5, '030816-14.600.35-03', 2, 0, 0, 600, 35, '0', 44, '', '', 0, 0, '', 1),
(92, 'Bopp blanco 35 micras', '03081614-51', 1850, 26.5, '030816-14.600.35-03', 2, 0, 0, 600, 35, '0', 45, '', '', 0, 0, '', 1),
(93, 'Bopp blanco 35 micras', '03081614-52', 1850, 26.5, '030816-14.600.35-03', 2, 0, 0, 600, 35, '0', 46, '', '', 0, 0, '', 1),
(94, 'PVC termoencogible C40 E50/0 450mm', 'stelote', 1600, 34, 'sta-tarima', 2, 1, 0, 450, 0, '', 1, '', '', 0, 0, '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lotestemporales`
--

CREATE TABLE `lotestemporales` (
  `id` int(11) NOT NULL,
  `noLote` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `longitud` float NOT NULL,
  `unidades` float NOT NULL,
  `anchura` float NOT NULL,
  `peso` float NOT NULL,
  `referencia` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `lotestemporales`
--

INSERT INTO `lotestemporales` (`id`, `noLote`, `longitud`, `unidades`, `anchura`, `peso`, `referencia`, `baja`) VALUES
(1, '030816-14.600.35-03 | 37', 1850, 21.227, 400, 26.5, '03081614-43', 0),
(2, '030816-14.600.35-03 | 38', 1850, 21.227, 400, 26.5, '03081614-44', 0),
(3, '030816-14.600.35-03 | 39', 1850, 21.227, 400, 26.5, '03081614-45', 0),
(4, '030816-14.600.35-03 | 40', 1850, 21.227, 400, 26.5, '03081614-46', 0),
(5, '030816-14.600.35-03 | 41', 1850, 21.227, 400, 26.5, '03081614-47', 0),
(6, '030816-14.600.35-03 | 42', 1850, 21.227, 400, 26.5, '03081614-48', 0),
(7, '030816-14.600.35-03 | 43', 1850, 21.227, 400, 26.5, '03081614-49', 0),
(8, '030816-14.600.35-03 | 44', 1850, 21.227, 400, 26.5, '03081614-50', 0),
(9, '030816-14.600.35-03 | 45', 1850, 21.227, 400, 26.5, '03081614-51', 0),
(10, '030816-14.600.35-03 | 46', 1850, 21.227, 400, 26.5, '03081614-52', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maquinas`
--

CREATE TABLE `maquinas` (
  `idMaq` int(11) NOT NULL,
  `codigo` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `descripcionMaq` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `subproceso` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tipoProducto` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `maquinas`
--

INSERT INTO `maquinas` (`idMaq`, `codigo`, `descripcionMaq`, `subproceso`, `tipoProducto`, `baja`) VALUES
(1, 'MTIM03', 'Impresora con Huecograbado 03 (7 tintas/gris)', 'impresion', '', 1),
(2, 'MTRF01', 'Refiladora 01', 'refilado', '', 1),
(3, 'MTFU01', 'MÃ¡quina fusionadora 01', 'fusion', '', 1),
(4, 'MTRE01', 'MÃ¡quina revisadora 01', 'revision', '', 1),
(5, 'MTCO01', 'MÃ¡quina cortadora 01', 'corte', '', 1),
(6, 'MTLA01', 'MÃ¡quina laminadora', 'laminado', '', 1),
(7, 'MTSL01', 'Sliteo 01', 'sliteo', '', 1),
(9, 'MTFL01', 'Impresora con FlexografÃ­a 01', 'impresion-flexografica', '', 1),
(10, 'fds2452', 'astroboy', 'revision', '', 0),
(11, 'MTTR001', 'Troqueladora 01', 'troquelado', '', 1),
(12, 'MTSJ004', 'Suajadora 04', 'suajado', '', 1),
(13, 'MTFOL45', 'foliadora con diseÃ±o 1', 'foliado', '', 1),
(14, 'MTFL02', 'Impresora Flexografica 02', 'impresion-flexografica', '', 1),
(15, 'MTEM01', 'Embosadora 01', 'embosado', '', 1),
(16, 'MTIM05', 'Impresora', 'impresion', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `merma`
--

CREATE TABLE `merma` (
  `id` int(11) NOT NULL,
  `hora` datetime DEFAULT CURRENT_TIMESTAMP,
  `codigo` varchar(70) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unidadesIn` float DEFAULT NULL,
  `unidadesOut` float DEFAULT NULL,
  `longIn` float DEFAULT NULL,
  `longOut` float DEFAULT NULL,
  `banderas` int(11) DEFAULT NULL,
  `producto` varchar(70) COLLATE utf8_unicode_ci DEFAULT NULL,
  `proceso` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `merma`
--

INSERT INTO `merma` (`id`, `hora`, `codigo`, `unidadesIn`, `unidadesOut`, `longIn`, `longOut`, `banderas`, `producto`, `proceso`) VALUES
(1, '2018-06-12 17:03:39', '00000022', 20.7356, 20.0808, 1900, 1840, NULL, 'Thinner estandar 960ml', 'impresion'),
(2, '2018-06-12 17:08:22', '00000023', 28.3751, 28.7025, 2600, 2630, NULL, 'Thinner estandar 960ml', 'impresion'),
(3, '2018-06-12 17:10:59', '00000024', 22.9183, 22.1871, 2100, 2033, NULL, 'Thinner estandar 960ml', 'impresion'),
(4, '2018-06-12 17:15:08', '00000025', 32.7405, 33.024, 3000, 3026, NULL, 'Thinner estandar 960ml', 'impresion'),
(5, '2018-06-12 17:15:56', '00000026', 25.101, 24.9919, 2300, 2290, NULL, 'Thinner estandar 960ml', 'impresion'),
(6, '2018-06-12 17:16:38', '00000027', 37.1059, 37.597, 3400, 3445, NULL, 'Thinner estandar 960ml', 'impresion'),
(7, '2018-06-12 17:17:34', '00000028', 30.5578, 30.8306, 2800, 2825, NULL, 'Thinner estandar 960ml', 'impresion'),
(8, '2018-06-12 17:18:34', '00000029', 30.5578, 31.671, 2800, 2902, NULL, 'Thinner estandar 960ml', 'impresion'),
(9, '2018-06-12 17:19:11', '00000030', 27.2837, 24.3371, 2500, 2230, NULL, 'Thinner estandar 960ml', 'impresion'),
(10, '2018-06-12 17:19:51', '00000031', 15.2789, 17.3743, 1400, 1592, NULL, 'Thinner estandar 960ml', 'impresion'),
(11, '2018-06-12 17:20:38', '00000032', 16.3702, 19.6443, 1500, 1800, NULL, 'Thinner estandar 960ml', 'impresion'),
(12, '2018-06-12 17:21:10', '00000033', 10.9135, 22.8092, 1000, 2090, NULL, 'Thinner estandar 960ml', 'impresion'),
(13, '2018-06-13 10:33:58', '00000122', 20.0808, 19.0986, 1840, 1750, 0, 'Thinner estandar 960ml', 'revision'),
(14, '2018-06-13 10:41:18', '00000124', 22.1871, 19.6443, 2033, 1800, 1, 'Thinner estandar 960ml', 'revision'),
(15, '2018-06-13 10:44:36', '00000125', 33.024, 31.3217, 3026, 2870, 1, 'Thinner estandar 960ml', 'revision'),
(16, '2018-06-13 10:47:20', '00000126', 24.9919, 19.0986, 2290, 1750, 0, 'Thinner estandar 960ml', 'revision'),
(21, '2018-06-13 10:57:59', '00000127', 37.597, 35.4689, 3445, 3250, 2, 'Thinner estandar 960ml', 'revision'),
(22, '2018-06-13 10:59:42', '00000128', 30.8306, 21.2813, 2825, 1950, 0, 'Thinner estandar 960ml', 'revision'),
(23, '2018-06-13 11:00:43', '00000129', 31.671, 28.9208, 2902, 2650, 0, 'Thinner estandar 960ml', 'revision'),
(25, '2018-06-13 11:05:46', '00000131', 17.3743, 16.152, 1592, 1480, 1, 'Thinner estandar 960ml', 'revision'),
(26, '2018-06-13 11:06:57', '00000132', 19.6443, 18.1055, 1800, 1659, 0, 'Thinner estandar 960ml', 'revision'),
(34, '2018-06-13 11:49:06', '00000222', 19.0986, 17.461, 1750, 1700, 1, 'Thinner estandar 960ml', 'laminado'),
(36, '2018-06-13 11:57:22', '00000224', 19.6443, 19.065, 1800, 1747, 1, 'Thinner estandar 960ml', 'laminado'),
(37, '2018-06-13 11:58:46', '00000231', 16.152, 13.532, 1480, 1240, 2, 'Thinner estandar 960ml', 'laminado'),
(38, '2018-06-13 12:00:29', '00000225', 31.3217, 30.0667, 2870, 2755, 2, 'Thinner estandar 960ml', 'laminado'),
(41, '2018-06-13 12:04:13', '00000226', 19.0986, 18.552, 1750, 1700, 1, 'Thinner estandar 960ml', 'laminado'),
(42, '2018-06-13 12:23:45', '00000123', 28.7025, 26.4107, 2630, 2420, 1, 'Thinner estandar 960ml', 'revision'),
(44, '2018-06-13 12:28:03', '00000229', 28.9208, 28.7025, 2650, 2630, 1, 'Thinner estandar 960ml', 'laminado'),
(45, '2018-06-13 13:11:25', '00000324', 19.065, 19.8543, 1747, 1362.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(50, '2018-06-14 08:32:33', '00000329', 28.7025, 21.3115, 2630, 1462.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(53, '2018-06-14 09:08:56', '00000223', 26.4107, 25.9741, 2420, 2380, 2, 'Thinner estandar 960ml', 'laminado'),
(54, '2018-06-14 09:10:27', '00000323', 25.9741, 20.765, 2380, 1425, NULL, 'Thinner estandar 960ml', 'refilado'),
(55, '2018-06-14 10:16:20', '00000043', 22.9183, 23.2457, 2100, 2130, NULL, 'Thinner estandar 960ml', 'impresion'),
(56, '2018-06-14 10:17:01', '00000143', 23.2457, 22.5364, 2130, 2065, 0, 'Thinner estandar 960ml', 'revision'),
(57, '2018-06-14 10:18:18', '00000243', 22.5364, 21.2377, 2065, 1946, 0, 'Thinner estandar 960ml', 'laminado'),
(58, '2018-06-14 10:19:38', '00000343', 21.2377, 21.3115, 1946, 1462.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(59, '2018-06-14 11:34:39', '00000322', 17.461, 19.8543, 1600, 1362.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(64, '2018-06-15 12:29:01', '00000325', 30.0667, 21.3115, 2755, 1462.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(65, '2018-06-15 12:42:14', '00000042', 21.827, 21.7724, 2000, 1995, NULL, 'Thinner estandar 960ml', 'impresion'),
(66, '2018-06-15 12:51:07', '00000142', 21.7724, 19.6443, 1995, 1800, 3, 'Thinner estandar 960ml', 'revision'),
(67, '2018-06-15 12:53:48', '00000242', 19.6443, 19.6443, 1800, 1800, 3, 'Thinner estandar 960ml', 'laminado'),
(68, '2018-06-15 12:54:34', '00000342', 19.6443, 20.2186, 1800, 1387.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(71, '2018-06-19 11:18:15', '00000228', 21.2813, 21.2813, 1950, 1950, 3, 'Thinner estandar 960ml', 'laminado'),
(72, '2018-06-19 11:21:07', '00000328', 21.2813, 21.3115, 1950, 1462.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(73, '2018-06-19 11:28:09', '00000326', 18.552, 19.1257, 1700, 1312.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(74, '2018-06-19 11:30:30', '00000227', 35.4689, 35.4689, 3250, 3250, 3, 'Thinner estandar 960ml', 'laminado'),
(75, '2018-06-19 11:32:24', '00000327', 35.4689, 19.6721, 3250, 1350, NULL, 'Thinner estandar 960ml', 'refilado'),
(76, '2018-06-19 11:34:18', '00000331', 13.532, 19.1257, 1240, 1312.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(78, '2018-06-19 11:41:41', '00000232', 18.1055, 21.2813, 1659, 1950, 3, 'Thinner estandar 960ml', 'laminado'),
(79, '2018-06-19 11:42:23', '00000332', 21.2813, 21.3115, 1950, 1462.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(80, '2018-06-19 11:45:30', '00000040', 19.6443, 19.6443, 1800, 1800, NULL, 'Thinner estandar 960ml', 'impresion'),
(81, '2018-06-19 11:46:22', '00000140', 19.6443, 19.6443, 1800, 1800, 0, 'Thinner estandar 960ml', 'revision'),
(82, '2018-06-19 11:47:02', '00000240', 19.6443, 19.6443, 1800, 1800, 3, 'Thinner estandar 960ml', 'laminado'),
(83, '2018-06-19 11:47:53', '00000340', 19.6443, 19.8543, 1800, 1362.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(84, '2018-06-25 12:12:11', '00000130', 24.3371, 19.6443, 2230, 1800, 1, 'Thinner estandar 960ml', 'revision'),
(85, '2018-06-25 12:15:04', '00000230', 19.6443, 19.6443, 1800, 1800, 3, 'Thinner estandar 960ml', 'laminado'),
(86, '2018-06-25 12:16:42', '00000330', 19.6443, 19.6721, 1800, 1350, NULL, 'Thinner estandar 960ml', 'refilado'),
(87, '2018-06-25 12:18:50', '00000133', 22.809, 21.2813, 2090, 1950, 0, 'Thinner estandar 960ml', 'revision'),
(88, '2018-06-25 12:23:07', '00000233', 21.2813, 21.2813, 1950, 1950, 1, 'Thinner estandar 960ml', 'laminado'),
(89, '2018-06-25 12:24:46', '00000333', 21.2813, 21.3115, 1950, 1462.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(90, '2018-06-25 12:34:39', '00000034', 39.2886, 21.2813, 3600, 1950, NULL, 'Thinner estandar 960ml', 'impresion'),
(91, '2018-06-25 12:37:18', '00000134', 21.2813, 21.2813, 1950, 1950, 1, 'Thinner estandar 960ml', 'revision'),
(92, '2018-06-25 12:38:54', '00000234', 21.2813, 21.2813, 1950, 1950, 2, 'Thinner estandar 960ml', 'laminado'),
(93, '2018-06-25 12:39:48', '00000334', 21.2813, 21.3115, 1950, 1462.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(94, '2018-06-25 12:49:28', '00000039', 28.3751, 28.3751, 2600, 2600, NULL, 'Thinner estandar 960ml', 'impresion'),
(95, '2018-06-25 12:50:18', '00000139', 28.3751, 21.2813, 2600, 1950, 1, 'Thinner estandar 960ml', 'revision'),
(96, '2018-06-25 12:51:56', '00000239', 21.2813, 21.2813, 1950, 1950, 1, 'Thinner estandar 960ml', 'laminado'),
(97, '2018-06-25 12:52:34', '00000339', 21.2813, 20.5829, 1950, 1412.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(98, '2018-06-25 12:55:37', '00000041', 16.3702, 16.3702, 1500, 1500, NULL, 'Thinner estandar 960ml', 'impresion'),
(99, '2018-06-25 12:59:23', '00000141', 16.3702, 20.7356, 1500, 1900, 1, 'Thinner estandar 960ml', 'revision'),
(100, '2018-06-25 13:12:57', '00000241', 20.7356, 20.7356, 1900, 1900, 1, 'Thinner estandar 960ml', 'laminado'),
(101, '2018-06-25 13:14:31', '00000341', 20.7356, 20.765, 1900, 1425, NULL, 'Thinner estandar 960ml', 'refilado'),
(102, '2018-06-25 13:17:25', '00000035', 36.0145, 36.0145, 3300, 3300, NULL, 'Thinner estandar 960ml', 'impresion'),
(103, '2018-06-25 13:18:39', '00000135', 36.0145, 21.2813, 3300, 1950, 0, 'Thinner estandar 960ml', 'revision'),
(105, '2018-06-25 13:20:26', '00000235', 21.2813, 21.2813, 1950, 1950, 2, 'Thinner estandar 960ml', 'laminado'),
(106, '2018-06-25 13:21:29', '00000335', 21.2813, 20.765, 1950, 1425, NULL, 'Thinner estandar 960ml', 'refilado'),
(107, '2018-06-25 13:26:14', '00000036', 17.4616, 17.4616, 1600, 1600, NULL, 'Thinner estandar 960ml', 'impresion'),
(108, '2018-06-25 13:26:45', '00000136', 17.4616, 17.4616, 1600, 1600, 0, 'Thinner estandar 960ml', 'revision'),
(109, '2018-06-25 13:27:55', '00000236', 17.4616, 17.4616, 1600, 1600, 0, 'Thinner estandar 960ml', 'laminado'),
(110, '2018-06-25 13:28:33', '00000336', 17.4616, 19.235, 1600, 1320, NULL, 'Thinner estandar 960ml', 'refilado'),
(111, '2018-06-25 14:03:56', '00000037', 17.4616, 17.4616, 1600, 1600, NULL, 'Thinner estandar 960ml', 'impresion'),
(112, '2018-06-25 14:04:47', '00000137', 17.4616, 17.4616, 1600, 1600, 0, 'Thinner estandar 960ml', 'revision'),
(113, '2018-06-25 14:05:19', '00000237', 17.4616, 17.4616, 1600, 1600, 1, 'Thinner estandar 960ml', 'laminado'),
(114, '2018-06-25 14:06:20', '00000337', 17.4616, 19.6721, 1600, 1350, NULL, 'Thinner estandar 960ml', 'refilado'),
(115, '2018-06-25 14:09:29', '00000038', 21.2813, 21.2813, 1950, 1950, NULL, 'Thinner estandar 960ml', 'impresion'),
(116, '2018-06-25 14:10:02', '00000138', 21.2813, 21.2813, 1950, 1950, 1, 'Thinner estandar 960ml', 'revision'),
(117, '2018-06-25 14:10:44', '00000238', 21.2813, 21.2813, 1950, 1950, 2, 'Thinner estandar 960ml', 'laminado'),
(118, '2018-06-25 14:11:22', '00000338', 21.2813, 21.3115, 1950, 1462.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(119, '2018-06-27 12:43:33', '00000067', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(121, '2018-06-27 13:01:45', '00000167', 20.19, 20.19, 1850, 1850, 0, 'Thinner estandar 960ml', 'revision'),
(122, '2018-06-27 13:16:44', '00000267', 20.19, 20.19, 1850, 1850, 2, 'Thinner estandar 960ml', 'laminado'),
(123, '2018-06-27 13:19:13', '00000367', 20.19, 20.8743, 1850, 1432.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(124, '2018-06-27 13:27:55', '00000044', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(125, '2018-06-27 17:15:47', '00000144', 20.19, 20.19, 1850, 1850, 2, 'Thinner estandar 960ml', 'revision'),
(126, '2018-06-27 17:18:26', '00000244', 20.19, 20.19, 1850, 1850, 3, 'Thinner estandar 960ml', 'laminado'),
(127, '2018-06-27 17:24:20', '00000344', 20.19, 20.765, 1850, 1425, NULL, 'Thinner estandar 960ml', 'refilado'),
(128, '2018-06-27 17:29:51', '00000047', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(129, '2018-06-27 17:32:32', '00000147', 20.19, 20.19, 1850, 1850, 0, 'Thinner estandar 960ml', 'revision'),
(130, '2018-06-27 17:34:30', '00000247', 20.19, 20.19, 1850, 1850, 3, 'Thinner estandar 960ml', 'laminado'),
(131, '2018-06-27 17:35:52', '00000347', 20.19, 21.3115, 1850, 1462.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(132, '2018-06-27 17:38:59', '00000048', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(133, '2018-06-28 07:53:10', '00000148', 20.19, 20.19, 1850, 1850, 0, 'Thinner estandar 960ml', 'revision'),
(134, '2018-06-28 07:58:00', '00000248', 20.19, 20.19, 1850, 1850, 3, 'Thinner estandar 960ml', 'laminado'),
(135, '2018-06-28 08:00:28', '00000348', 20.19, 21.3115, 1850, 1462.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(136, '2018-06-28 08:07:14', '00000049', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(137, '2018-06-28 08:12:20', '00000149', 20.19, 20.19, 1850, 1850, 0, 'Thinner estandar 960ml', 'revision'),
(138, '2018-06-28 08:13:42', '00000249', 20.19, 20.19, 1850, 1850, 3, 'Thinner estandar 960ml', 'laminado'),
(139, '2018-06-28 08:15:34', '00000349', 20.19, 20.9472, 1850, 1437.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(140, '2018-06-28 08:26:16', '00000050', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(141, '2018-06-28 08:28:47', '00000150', 20.19, 20.19, 1850, 1850, 0, 'Thinner estandar 960ml', 'revision'),
(142, '2018-06-28 08:30:01', '00000250', 20.19, 20.19, 1850, 1850, 0, 'Thinner estandar 960ml', 'laminado'),
(143, '2018-06-28 08:31:45', '00000350', 20.19, 19.49, 1850, 1337.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(144, '2018-06-28 08:34:55', '00000051', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(145, '2018-06-28 08:36:45', '00000151', 20.19, 20.19, 1850, 1850, 0, 'Thinner estandar 960ml', 'revision'),
(146, '2018-06-28 08:38:05', '00000251', 20.19, 20.19, 1850, 1850, 2, 'Thinner estandar 960ml', 'laminado'),
(147, '2018-06-28 08:41:24', '00000351', 20.19, 20.7286, 1850, 1422.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(148, '2018-06-28 08:45:59', '00000052', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(149, '2018-06-28 08:47:17', '00000152', 20.19, 20.19, 1850, 1850, 2, 'Thinner estandar 960ml', 'revision'),
(150, '2018-06-28 08:51:15', '00000252', 20.19, 20.19, 1850, 1850, 3, 'Thinner estandar 960ml', 'laminado'),
(151, '2018-06-28 08:54:05', '00000352', 20.19, 20.5829, 1850, 1412.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(152, '2018-06-28 08:57:40', '00000053', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(153, '2018-06-28 08:59:46', '00000153', 20.19, 20.19, 1850, 1850, 3, 'Thinner estandar 960ml', 'revision'),
(154, '2018-06-28 09:04:23', '00000253', 20.19, 20.19, 1850, 1850, 3, 'Thinner estandar 960ml', 'laminado'),
(155, '2018-06-28 09:05:42', '00000353', 20.19, 19.1257, 1850, 1312.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(156, '2018-06-28 09:44:49', '00000054', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(157, '2018-06-28 09:46:43', '00000154', 20.19, 20.19, 1850, 1850, 0, 'Thinner estandar 960ml', 'revision'),
(158, '2018-06-28 09:48:30', '00000254', 20.19, 20.19, 1850, 1850, 3, 'Thinner estandar 960ml', 'laminado'),
(159, '2018-06-28 09:50:19', '00000354', 20.19, 19.1257, 1850, 1312.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(160, '2018-06-28 09:53:44', '00000055', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(161, '2018-06-28 09:55:10', '00000155', 20.19, 20.19, 1850, 1850, 0, 'Thinner estandar 960ml', 'revision'),
(162, '2018-06-28 09:56:37', '00000255', 20.19, 20.19, 1850, 1850, 3, 'Thinner estandar 960ml', 'laminado'),
(163, '2018-06-28 09:58:09', '00000355', 20.19, 20.9472, 1850, 1437.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(164, '2018-06-28 10:08:28', '00000056', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(165, '2018-06-28 10:09:58', '00000156', 20.19, 20.19, 1850, 1850, 0, 'Thinner estandar 960ml', 'revision'),
(166, '2018-06-28 10:11:28', '00000256', 20.19, 20.19, 1850, 1850, 3, 'Thinner estandar 960ml', 'laminado'),
(167, '2018-06-28 10:14:38', '00000356', 20.19, 21.3115, 1850, 1462.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(168, '2018-06-28 10:17:48', '00000057', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(169, '2018-06-28 10:20:24', '00000157', 20.19, 20.19, 1850, 1850, 3, 'Thinner estandar 960ml', 'revision'),
(170, '2018-06-28 10:21:50', '00000257', 20.19, 20.19, 1850, 1850, 3, 'Thinner estandar 960ml', 'laminado'),
(171, '2018-06-28 10:23:06', '00000357', 20.19, 20.765, 1850, 1425, NULL, 'Thinner estandar 960ml', 'refilado'),
(172, '2018-06-28 10:25:55', '00000058', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(173, '2018-06-28 10:27:26', '00000158', 20.19, 20.19, 1850, 1850, 3, 'Thinner estandar 960ml', 'revision'),
(174, '2018-06-28 10:29:18', '00000258', 20.19, 20.19, 1850, 1850, 3, 'Thinner estandar 960ml', 'laminado'),
(175, '2018-06-28 10:33:22', '00000358', 20.19, 21.3115, 1850, 1462.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(176, '2018-06-28 10:36:06', '00000059', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(178, '2018-06-28 10:41:28', '00000159', 20.19, 20.19, 1850, 1850, 0, 'Thinner estandar 960ml', 'revision'),
(179, '2018-06-28 10:42:55', '00000259', 20.19, 20.19, 1850, 1850, 3, 'Thinner estandar 960ml', 'laminado'),
(180, '2018-06-28 10:44:35', '00000359', 20.19, 20.1093, 1850, 1380, NULL, 'Thinner estandar 960ml', 'refilado'),
(181, '2018-06-28 10:47:40', '00000060', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(182, '2018-06-28 10:49:11', '00000160', 20.19, 20.19, 1850, 1850, 2, 'Thinner estandar 960ml', 'revision'),
(183, '2018-06-28 10:50:49', '00000260', 20.19, 20.19, 1850, 1850, 3, 'Thinner estandar 960ml', 'laminado'),
(184, '2018-06-28 10:52:44', '00000360', 20.19, 20.4372, 1850, 1402.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(185, '2018-06-28 11:01:32', '00000061', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(186, '2018-06-28 11:09:53', '00000161', 20.19, 20.19, 1850, 1850, 0, 'Thinner estandar 960ml', 'revision'),
(187, '2018-06-28 11:11:54', '00000261', 20.19, 20.19, 1850, 1850, 2, 'Thinner estandar 960ml', 'laminado'),
(188, '2018-06-28 11:13:09', '00000361', 20.19, 18.5792, 1850, 1275, NULL, 'Thinner estandar 960ml', 'refilado'),
(189, '2018-06-28 11:18:43', '00000062', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(190, '2018-06-28 11:19:56', '00000162', 20.19, 20.19, 1850, 1850, 2, 'Thinner estandar 960ml', 'revision'),
(191, '2018-06-28 11:21:24', '00000262', 20.19, 20.19, 1850, 1850, 3, 'Thinner estandar 960ml', 'laminado'),
(192, '2018-06-28 11:22:54', '00000362', 20.19, 21.3115, 1850, 1462.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(193, '2018-06-28 11:25:45', '00000063', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(194, '2018-06-28 11:29:07', '00000163', 20.19, 20.19, 1850, 1850, 2, 'Thinner estandar 960ml', 'revision'),
(195, '2018-06-28 11:32:12', '00000263', 20.19, 20.19, 1850, 1850, 3, 'Thinner estandar 960ml', 'laminado'),
(196, '2018-06-28 11:33:58', '00000363', 20.19, 20.765, 1850, 1425, NULL, 'Thinner estandar 960ml', 'refilado'),
(197, '2018-06-28 11:40:00', '00000064', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(198, '2018-06-28 11:43:34', '00000164', 20.19, 20.19, 1850, 1850, 2, 'Thinner estandar 960ml', 'revision'),
(199, '2018-06-28 11:44:51', '00000264', 20.19, 20.19, 1850, 1850, 3, 'Thinner estandar 960ml', 'laminado'),
(200, '2018-06-28 11:46:18', '00000364', 20.19, 20.2186, 1850, 1387.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(201, '2018-06-28 11:53:16', '00000065', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(202, '2018-06-28 11:55:34', '00000165', 20.19, 20.19, 1850, 1850, 1, 'Thinner estandar 960ml', 'revision'),
(203, '2018-06-28 11:57:41', '00000265', 20.19, 20.19, 1850, 1850, 2, 'Thinner estandar 960ml', 'laminado'),
(204, '2018-06-28 12:00:07', '00000365', 20.19, 21.3115, 1850, 1462.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(205, '2018-06-28 12:08:53', '00000066', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(206, '2018-06-28 12:10:22', '00000166', 20.19, 20.19, 1850, 1850, 2, 'Thinner estandar 960ml', 'revision'),
(207, '2018-06-28 12:19:02', '00000266', 20.19, 20.19, 1850, 1850, 3, 'Thinner estandar 960ml', 'laminado'),
(208, '2018-06-28 12:21:38', '00000366', 20.19, 19.49, 1850, 1337.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(209, '2018-06-28 12:24:58', '00000068', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(210, '2018-06-28 12:26:58', '00000168', 20.19, 20.19, 1850, 1850, 2, 'Thinner estandar 960ml', 'revision'),
(211, '2018-06-28 12:29:06', '00000268', 20.19, 20.19, 1850, 1850, 3, 'Thinner estandar 960ml', 'laminado'),
(212, '2018-06-28 12:31:01', '00000368', 20.19, 21.1293, 1850, 1450, NULL, 'Thinner estandar 960ml', 'refilado'),
(213, '2018-06-28 12:33:21', '00000069', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(215, '2018-06-28 12:34:51', '00000169', 20.19, 20.19, 1850, 1850, 3, 'Thinner estandar 960ml', 'revision'),
(216, '2018-06-28 12:42:45', '00000269', 20.19, 20.19, 1850, 1850, 2, 'Thinner estandar 960ml', 'laminado'),
(217, '2018-06-28 12:44:48', '00000369', 20.19, 19.4681, 1850, 1336, NULL, 'Thinner estandar 960ml', 'refilado'),
(218, '2018-06-28 12:56:17', '00000070', 20.19, 20.19, 1850, 1850, NULL, 'Thinner estandar 960ml', 'impresion'),
(219, '2018-06-28 12:58:25', '00000170', 20.19, 20.19, 1850, 1850, 2, 'Thinner estandar 960ml', 'revision'),
(220, '2018-06-28 12:59:37', '00000270', 20.19, 20.19, 1850, 1850, 3, 'Thinner estandar 960ml', 'laminado'),
(221, '2018-06-28 13:01:14', '00000370', 20.19, 20.2186, 1850, 1387.5, NULL, 'Thinner estandar 960ml', 'refilado'),
(222, '2018-07-13 13:49:43', '00000046', 20.19, 19.6443, 1850, 1800, NULL, 'Thinner estandar 960ml', 'impresion'),
(223, '2018-10-01 13:38:54', '00000081', 20.19, 18.5529, 1850, 1700, NULL, 'Thinner estandar 960ml', 'impresion'),
(226, '2018-10-01 13:50:06', '00000181', 18.5529, 18.6621, 1700, 1710, 1, 'Thinner estandar 960ml', 'revision'),
(227, '2018-10-01 13:51:27', '00000281', 18.6621, 18.4438, 1710, 1690, 1, 'Thinner estandar 960ml', 'laminado'),
(228, '2018-10-01 13:56:24', '00000381', 18.4438, NULL, 1690, NULL, NULL, 'Thinner estandar 960ml', 'refilado'),
(229, '2018-11-26 15:56:06', '00000071', 20.19, 19.6443, 1850, 1800, NULL, 'Thinner estandar 960ml', 'impresion');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo`
--

CREATE TABLE `modulo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `baja` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `modulo`
--

INSERT INTO `modulo` (`id`, `nombre`, `codigo`, `baja`) VALUES
(1, 'Empleados', '10001', 1),
(2, 'Departamentos', '10002', 1),
(3, 'Diseños', '20001', 1),
(4, 'Impresiones', '20002', 1),
(5, 'Consumos', '20003', 1),
(6, 'Juego de Cilindros', '20004', 1),
(7, 'Banda de Seguridad', '20005', 1),
(8, 'Banda de Seguridad por Proceso', '20006', 1),
(9, 'Productos Cliente', '20007', 1),
(10, 'Bloques', '30001', 1),
(11, 'Lotes', '30002', 1),
(12, 'Sustrato', '30003', 1),
(13, 'Pantones', '30004', 1),
(14, 'Elementos', '30005', 1),
(15, 'Unidades de Medida', '30006', 1),
(16, 'Explosion de Materiales', '30007', 1),
(17, 'Clientes', '40001', 1),
(18, 'Contacto Clientes', '40002', 1),
(19, 'Sucursales', '40003', 1),
(20, 'Contacto Sucursales', '40004', 1),
(21, 'Orden Compra', '40005', 1),
(22, 'Requerimientos de Orden Compra', '40006', 1),
(23, 'Confirmaciones de Orden Compra', '40007', 1),
(24, 'Embarques', '40008', 1),
(25, 'Surtido de Embarques', '40009', 1),
(26, 'Devoluciones', '40010', 1),
(27, 'Bitácora', '80001', 1),
(28, 'Cambio de Contraseña', '80002', 1),
(29, 'Agregar Usuario', '80003', 1),
(30, 'Privilegios', '80004', 1),
(31, 'Misceláneos', '80005', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordencompra`
--

CREATE TABLE `ordencompra` (
  `idorden` int(11) NOT NULL,
  `orden` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `documento` date NOT NULL,
  `recepcion` date NOT NULL,
  `bajaOrden` int(1) NOT NULL DEFAULT '1',
  `sucFK` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `ordencompra`
--

INSERT INTO `ordencompra` (`idorden`, `orden`, `documento`, `recepcion`, `bajaOrden`, `sucFK`) VALUES
(1, '12845', '2018-05-15', '2018-06-04', 1, 'Comex Queretaro'),
(2, '12916', '2018-06-01', '2018-06-04', 1, 'Comex Queretaro'),
(3, '977', '2019-01-01', '2019-01-01', 1, 'Planta Villahermosa Bepensa'),
(4, 'PRue30', '2019-01-01', '2019-01-01', 1, 'Sucursal 1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pantone`
--

CREATE TABLE `pantone` (
  `idPantone` int(11) NOT NULL,
  `descripcionPantone` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `codigoPantone` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `codigoHTML` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `state` int(1) NOT NULL DEFAULT '1',
  `baja` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `pantone`
--

INSERT INTO `pantone` (`idPantone`, `descripcionPantone`, `codigoPantone`, `codigoHTML`, `state`, `baja`) VALUES
(1, 'PANTONE 2395 C', 'C800A1', 'C800A1', 1, 1),
(2, 'PANTONE 805 C', 'FF7276', 'FF7276', 1, 1),
(4, 'Pantone white C', 'FFF', 'FFF', 1, 1),
(5, 'pantone 133 C', '6C571B', '6C571B', 1, 1),
(6, 'PANTONE 180 C', 'BE3A34', 'BE3A34', 1, 1),
(7, 'Pantone 288 C', '002D72', '002D72', 1, 1),
(8, 'PANTONE 569 C', '00816D', '00816D', 1, 1),
(9, 'PANTONE 305 C', '59CBE8', '59CBE8', 1, 1),
(10, 'PANTONE 186 C', 'C8102E', 'C8102E', 1, 1),
(11, 'PANTONE 573 C', 'B5E3D8', 'B5E3D8', 1, 1),
(12, 'PANTONE 555 C', '28724F', '28724F', 1, 1),
(13, 'PANTONE 295 C', '002855', '002855', 1, 1),
(14, 'PANTONE 659 C', '7BA4DB', '7BA4DB', 1, 1),
(15, 'PANTONE 257 C', 'C6A1CF', 'C6A1CF', 1, 1),
(16, 'PANTONE 5145 C', '9B7793', '9B7793', 1, 1),
(17, 'PANTONE 7647 C', 'A83D72', 'A83D72', 1, 1),
(18, 'PANTONE 238 C', 'E45DBF', 'E45DBF', 1, 1),
(19, 'PANTONE 494 C', 'E9A2B2', 'E9A2B2', 1, 1),
(21, 'PANTONE 191 C', 'EF426F', 'EF426F', 1, 1),
(22, 'Pantone 123 C', 'FFC72C', 'FFC72C', 1, 1),
(23, 'Pantone 361 C', '43B02A', '43B02A', 1, 1),
(24, 'Process Black C', '2D2926', '2D2926', 1, 1),
(25, 'Process Cian C', '009FDF', '009FDF', 1, 1),
(26, 'Process Magenta c', 'D40F7D', 'D40F7D', 1, 1),
(27, 'Process Yellow C', 'FEDD00', 'FEDD00', 1, 1),
(28, 'Pantone 109 C', 'FFD100', 'FFD100', 1, 1),
(29, 'Pantone 334 C', '009775', '009775', 1, 1),
(30, 'Pantone 375 C', '97D700', '97D700', 1, 1),
(31, 'Pantone 368 C', '78BE20', '78BE20', 1, 1),
(32, 'Pantone Warm Red C', 'F9423A', 'F9423A', 1, 1),
(33, 'Pantone Process Blue C', '0085CA', '0085CA', 1, 1),
(35, 'Pantone 364 C', '4A7729', '4A7729', 1, 1),
(36, 'Pantone 455 C', '695B24', '695B24', 1, 0),
(37, 'Pantone 534 C', '1B365D', '1B365D', 1, 1),
(38, 'Pantone 2945 C', '004C97', '004C97', 1, 1),
(39, 'Pantone 185 C', 'E4002B', 'E4002B', 1, 1),
(40, 'Process White C Alto C', 'FFFF', 'FFFF', 1, 1),
(41, 'Pantone 7712 C', '00859B', '00859B', 1, 1),
(42, 'Pantone 187 C', 'A6192E', 'A6192E', 1, 1),
(43, 'Pantone Red 032 C', 'EF3340', 'EF3340', 1, 1),
(44, 'Pantone 400 C', 'C4BFB6', 'C4BFB6', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pantonepcapa`
--

CREATE TABLE `pantonepcapa` (
  `idPantonePCapa` int(11) NOT NULL,
  `descripcionPantone` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `consumoPantone` float DEFAULT NULL,
  `disolvente` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigoImpresion` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` int(1) DEFAULT '1',
  `codigoCapa` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `pantonepcapa`
--

INSERT INTO `pantonepcapa` (`idPantonePCapa`, `descripcionPantone`, `consumoPantone`, `disolvente`, `codigoImpresion`, `estado`, `codigoCapa`) VALUES
(18, 'Process Black C', 0.007, '70/30', 'E0420C18-04', 1, 'CE0420C18-040'),
(19, 'Process Cian C', 0.25, '70/30', 'E0420C18-04', 1, 'CE0420C18-041'),
(20, 'Process Yellow C', 0.007, '70/30', 'E0420C18-04', 1, 'CE0420C18-042'),
(21, 'Pantone Red 032 C', 0.29, '70/30', 'E0420C18-04', 1, 'CE0420C18-043'),
(22, 'Pantone 400 C', 0.025, '70/30', 'E0420C18-04', 1, 'CE0420C18-044'),
(23, 'PANTONE 191 C', 0.07, '70/30', '23436', 1, 'C234360'),
(24, 'PANTONE 5145 C', 0.9, '70/30', '23436', 1, 'C234361'),
(25, 'Process Black C', 0.66, '70/30', '23436', 1, 'C234362'),
(26, 'PANTONE WHITE C', 0, '70/30', 'M0503C22-01', 1, 'CM0503C22-010'),
(27, 'PANTONE WHITE C', 0, '70/30', 'M0503C22-01', 1, 'CM0503C22-011'),
(28, 'PANTONE WHITE C', 0, '70/30', 'M0503C22-01', 1, 'CM0503C22-012'),
(29, 'PANTONE WHITE C', 0, '70/30', 'M0503C22-01', 1, 'CM0503C22-013'),
(30, 'PANTONE WHITE C', 0, '70/30', 'M0503C22-01', 1, 'CM0503C22-014'),
(31, 'PANTONE WHITE C', 0, '70/30', 'M0503C22-01', 1, 'CM0503C22-015');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parametros`
--

CREATE TABLE `parametros` (
  `id` int(11) NOT NULL,
  `nombreParametro` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `parametros`
--

INSERT INTO `parametros` (`id`, `nombreParametro`, `tipo`, `baja`) VALUES
(3, 'operador', 'varchar(50)', 1),
(4, 'bobina', 'varchar(50)', 1),
(5, 'disco', 'varchar(50)', 1),
(6, 'maquina', 'varchar(50)', 1),
(7, 'longitud', 'float', 1),
(8, 'peso', 'float', 1),
(9, 'noop', 'int', 1),
(10, 'lote', 'varchar(50)', 1),
(11, 'amplitud', 'float', 1),
(12, 'bandera', 'int', 1),
(13, 'cdgDisco', 'varchar(50)', 1),
(14, 'rollo', 'varchar(50)', 1),
(15, 'unidades', 'float', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `procesos`
--

CREATE TABLE `procesos` (
  `id` int(11) NOT NULL,
  `descripcionProceso` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `packParametros` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `abreviacion` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `process` int(1) NOT NULL DEFAULT '0',
  `divisiones` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `merma_p` float NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `procesos`
--

INSERT INTO `procesos` (`id`, `descripcionProceso`, `packParametros`, `abreviacion`, `process`, `divisiones`, `merma_p`, `baja`) VALUES
(2, 'refilado', 'PKPREF', 'REF', 0, '1', 0.4, 1),
(3, 'fusion', 'PKPFUS', 'FS', 0, '1', 0, 1),
(4, 'sliteo', 'PKPSLIT', 'SLT', 0, '1', 0, 1),
(5, 'revision', 'PKPREV', 'RVS', 0, '0', 0, 1),
(6, 'corte', 'PKPCOR', 'CRT', 0, '1', 0, 1),
(7, 'impresion', 'PKPIMP', 'IMP', 0, '0', 0, 1),
(8, 'laminado', 'PKPLAM', 'LAM', 0, '0', 0, 1),
(9, 'embosado', 'PKPEMB', 'EMB', 0, '0', 0, 1),
(10, 'programado', 'PKPPGM', 'PGM', 1, '0', 0, 1),
(11, 'caja', 'PKPC', 'C(Empaque)', 1, '0', 0, 1),
(12, 'rollo', 'PKPR(Empaque)', 'R(Empaque)', 1, '0', 0, 1),
(14, 'troquelado', 'PKPtro', 'TROQ', 0, '0', 0, 1),
(15, 'suajado', 'PKPsua', 'SJD', 0, '0', 0, 1),
(16, 'foliado', 'PKPfol', 'FLD', 0, '1', 0, 1),
(17, 'revision 2', 'PKPrev2', 'RV2', 0, '0', 0, 1),
(18, 'impresion-flexografica', 'PKPIMPFL', 'IMPFL', 0, '0', 0, 1),
(19, 'laminado 2', 'PKPLAM2', 'LAM2', 0, '', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `procorte`
--

CREATE TABLE `procorte` (
  `id` int(11) NOT NULL,
  `total` float NOT NULL DEFAULT '1',
  `producto` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `noop` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `unidades` float NOT NULL,
  `operador` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `maquina` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rollo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rollo_padre` int(11) NOT NULL DEFAULT '1',
  `tipo` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion`
--

CREATE TABLE `produccion` (
  `id` int(11) NOT NULL,
  `nombreProducto` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `juegoLotes` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `cantLotes` int(11) NOT NULL,
  `juegoCilindros` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `disenio` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `maquina` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `fechaProduccion` date NOT NULL,
  `tipo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `unidades` float NOT NULL,
  `suaje` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `juegoCireles` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `estado` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `produccion`
--

INSERT INTO `produccion` (`id`, `nombreProducto`, `juegoLotes`, `cantLotes`, `juegoCilindros`, `disenio`, `maquina`, `fechaProduccion`, `tipo`, `unidades`, `suaje`, `juegoCireles`, `estado`) VALUES
(3, 'Thinner estandar 960ml', 'JLEti2018-06-131528837418', 12, 'WR8051838', 'Comex', 'Impresora', '2018-06-13', 'Etiqueta abierta', 297.938, '', '', 2),
(4, 'Thinner estandar 960ml', 'JLEti2018-06-131528902116', 10, 'WR8051838', 'Comex', 'Impresora', '2018-06-13', 'Etiqueta abierta', 240.643, '', '', 2),
(5, 'Thinner estandar 960ml', 'JLEti2018-06-271530120684', 24, 'WR8051838', 'Comex', 'Impresora', '2018-06-27', 'Etiqueta abierta', 484.559, '', '', 2),
(6, 'Thinner estandar 960ml', 'JLEti2018-06-271530208405', 1, 'WR8051838', 'Comex', 'Impresora', '2018-06-27', 'Etiqueta abierta', 20.19, '', '', 2),
(7, 'Thinner estandar 960ml', 'JLEti2018-06-271531505555', 1, 'WR8051838', 'Comex', 'Impresora con Huecograbado 03 (7 tintas/gris)', '2018-06-27', 'Etiqueta abierta', 20.19, '', '', 2),
(8, 'Thinner estandar 960ml', 'JLEti2018-07-251532527046', 8, 'WR8051838', 'Comex', 'Impresora con Huecograbado 03 (7 tintas/gris)', '2018-07-25', 'Etiqueta abierta', 161.52, '', '', 2),
(9, 'Thinner estandar 960ml', 'JLEti2018-08-201534775694', 2, 'WR8051838', 'Comex', 'Impresora con Huecograbado 03 (7 tintas/gris)', '2018-08-20', 'Etiqueta abierta', 40.3799, '', '', 2),
(10, 'Thinner estandar 960ml', 'JLEti2018-10-011538418981', 3, 'WR8051838', 'Comex', 'Impresora con Huecograbado 03 (7 tintas/gris)', '2018-10-01', 'Etiqueta abierta', 60.5699, '', '', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `ID` int(11) NOT NULL,
  `codigo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `consPre` int(11) NOT NULL DEFAULT '0',
  `baja` int(1) NOT NULL DEFAULT '1',
  `cilindros` int(11) NOT NULL DEFAULT '0',
  `cireles` int(11) NOT NULL DEFAULT '0',
  `suaje` int(11) NOT NULL DEFAULT '0',
  `refil` int(11) NOT NULL,
  `fus` int(11) NOT NULL,
  `holograma` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`ID`, `codigo`, `descripcion`, `tipo`, `consPre`, `baja`, `cilindros`, `cireles`, `suaje`, `refil`, `fus`, `holograma`) VALUES
(3, '0014', 'Comex', 'Etiqueta abierta', 1, 1, 1, 0, 0, 1, 0, 0),
(5, '910', 'Pruebis', 'Etiqueta abierta', 0, 0, 1, 0, 0, 1, 0, 0),
(6, '0013', 'Generico Gepp', 'Manga Termoencogible', 1, 1, 1, 0, 0, 1, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productoscliente`
--

CREATE TABLE `productoscliente` (
  `IdProdCliente` int(11) NOT NULL,
  `IdentificadorCliente` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `productoscliente`
--

INSERT INTO `productoscliente` (`IdProdCliente`, `IdentificadorCliente`, `nombre`, `baja`) VALUES
(1, '.0014', 'Comex', 1),
(2, 'Comex-012', 'Etiqueta thinner estandar 960ml', 1),
(3, '1175071-032', 'Banda de garantia s/hol epura 88x37mm', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proembosado`
--

CREATE TABLE `proembosado` (
  `id` int(11) NOT NULL,
  `noop` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total` int(11) NOT NULL DEFAULT '1',
  `unidades` float NOT NULL,
  `producto` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `operador` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `maquina` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lote` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitud` float DEFAULT NULL,
  `amplitud` float DEFAULT NULL,
  `peso` float DEFAULT NULL,
  `bandera` int(11) DEFAULT NULL,
  `tipo` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `rollo_padre` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profoliado`
--

CREATE TABLE `profoliado` (
  `id` int(11) NOT NULL,
  `total` int(11) NOT NULL DEFAULT '1',
  `producto` varchar(70) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `operador` varchar(50) NOT NULL,
  `maquina` varchar(50) NOT NULL,
  `bobina` varchar(50) NOT NULL,
  `unidades` float NOT NULL,
  `peso` float NOT NULL,
  `longitud` float NOT NULL,
  `noop` varchar(30) NOT NULL,
  `tipo` varchar(30) NOT NULL,
  `rollo_padre` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profusion`
--

CREATE TABLE `profusion` (
  `id` int(11) NOT NULL,
  `total` float NOT NULL DEFAULT '1',
  `producto` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `operador` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `maquina` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bobina` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `disco` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitud` float NOT NULL,
  `noop` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `amplitud` float DEFAULT NULL,
  `bandera` int(11) DEFAULT NULL,
  `cdgDisco` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `unidades` float NOT NULL,
  `rollo_padre` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proimpresion`
--

CREATE TABLE `proimpresion` (
  `id` int(11) NOT NULL,
  `operador` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `maquina` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `juegoCilindros` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `lote` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `total` int(11) NOT NULL DEFAULT '1',
  `producto` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `longitud` float DEFAULT NULL,
  `peso` float DEFAULT NULL,
  `noop` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `unidades` float NOT NULL,
  `rollo_padre` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `proimpresion`
--

INSERT INTO `proimpresion` (`id`, `operador`, `maquina`, `juegoCilindros`, `lote`, `total`, `producto`, `fecha`, `longitud`, `peso`, `noop`, `tipo`, `unidades`, `rollo_padre`) VALUES
(1, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000022', 0, 'Thinner estandar 960ml', '2018-06-12 17:03:39', 1840, 18.2, '13', 'Etiqueta abierta', 20.0808, 0),
(2, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000023', 0, 'Thinner estandar 960ml', '2018-06-12 17:08:21', 2630, 26.1, '14', 'Etiqueta abierta', 28.7025, 0),
(3, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000024', 0, 'Thinner estandar 960ml', '2018-06-12 17:10:58', 2033, 20.5, '15', 'Etiqueta abierta', 22.1871, 0),
(4, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000025', 0, 'Thinner estandar 960ml', '2018-06-12 17:15:08', 3026, 30.45, '16', 'Etiqueta abierta', 33.024, 0),
(5, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000026', 0, 'Thinner estandar 960ml', '2018-06-12 17:15:56', 2290, 22.78, '17', 'Etiqueta abierta', 24.9919, 0),
(6, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000027', 0, 'Thinner estandar 960ml', '2018-06-12 17:16:38', 3445, 34.73, '18', 'Etiqueta abierta', 37.597, 0),
(7, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000028', 0, 'Thinner estandar 960ml', '2018-06-12 17:17:33', 2825, 28.48, '19', 'Etiqueta abierta', 30.8306, 0),
(8, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000029', 0, 'Thinner estandar 960ml', '2018-06-12 17:18:34', 2902, 26.26, '20', 'Etiqueta abierta', 31.671, 0),
(9, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000030', 0, 'Thinner estandar 960ml', '2018-06-12 17:19:11', 2230, 22.48, '21', 'Etiqueta abierta', 24.3371, 0),
(10, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000031', 0, 'Thinner estandar 960ml', '2018-06-12 17:19:50', 1592, 15.7, '22', 'Etiqueta abierta', 17.3743, 0),
(11, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000032', 0, 'Thinner estandar 960ml', '2018-06-12 17:20:38', 1800, 18.14, '23', 'Etiqueta abierta', 19.6443, 0),
(12, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000033', 0, 'Thinner estandar 960ml', '2018-06-12 17:21:10', 2090, 19.5, '24', 'Etiqueta abierta', 22.809, 0),
(13, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000043', 0, 'Thinner estandar 960ml', '2018-06-14 10:16:19', 2130, 30.76, '34', 'Etiqueta abierta', 23.2457, 0),
(14, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000042', 0, 'Thinner estandar 960ml', '2018-06-15 12:42:13', 1995, 30, '33', 'Etiqueta abierta', 21.7724, 0),
(15, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000040', 0, 'Thinner estandar 960ml', '2018-06-19 11:45:30', 1800, 30, '31', 'Etiqueta abierta', 19.6443, 0),
(16, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000034', 0, 'Thinner estandar 960ml', '2018-06-25 12:34:39', 1950, 28.16, '25', 'Etiqueta abierta', 21.2813, 0),
(17, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000039', 0, 'Thinner estandar 960ml', '2018-06-25 12:49:27', 2600, 37, '30', 'Etiqueta abierta', 28.3751, 0),
(18, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000041', 0, 'Thinner estandar 960ml', '2018-06-25 12:55:37', 1500, 22, '32', 'Etiqueta abierta', 16.3702, 0),
(19, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000035', 0, 'Thinner estandar 960ml', '2018-06-25 13:17:25', 3300, 47, '26', 'Etiqueta abierta', 36.0145, 0),
(20, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000036', 0, 'Thinner estandar 960ml', '2018-06-25 13:26:14', 1600, 23, '27', 'Etiqueta abierta', 17.4616, 0),
(21, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000037', 0, 'Thinner estandar 960ml', '2018-06-25 14:03:55', 1600, 23, '28', 'Etiqueta abierta', 17.4616, 0),
(22, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000038', 0, 'Thinner estandar 960ml', '2018-06-25 14:09:29', 1950, 28, '29', 'Etiqueta abierta', 21.2813, 0),
(23, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000067', 0, 'Thinner estandar 960ml', '2018-06-27 12:43:33', 1850, 26.5, '56', 'Etiqueta abierta', 20.19, 0),
(24, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000044', 0, 'Thinner estandar 960ml', '2018-06-27 13:27:55', 1850, 26.5, '35', 'Etiqueta abierta', 20.19, 0),
(25, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000047', 0, 'Thinner estandar 960ml', '2018-06-27 17:29:51', 1850, 26.5, '36', 'Etiqueta abierta', 20.19, 0),
(26, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000048', 0, 'Thinner estandar 960ml', '2018-06-27 17:38:59', 1850, 26.5, '37', 'Etiqueta abierta', 20.19, 0),
(27, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000049', 0, 'Thinner estandar 960ml', '2018-06-28 08:07:13', 1850, 26.5, '38', 'Etiqueta abierta', 20.19, 0),
(28, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000050', 0, 'Thinner estandar 960ml', '2018-06-28 08:26:16', 1850, 26.5, '39', 'Etiqueta abierta', 20.19, 0),
(29, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000051', 0, 'Thinner estandar 960ml', '2018-06-28 08:34:54', 1850, 26.5, '40', 'Etiqueta abierta', 20.19, 0),
(30, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000052', 0, 'Thinner estandar 960ml', '2018-06-28 08:45:59', 1850, 26.5, '41', 'Etiqueta abierta', 20.19, 0),
(31, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000053', 0, 'Thinner estandar 960ml', '2018-06-28 08:57:40', 1850, 26.5, '42', 'Etiqueta abierta', 20.19, 0),
(32, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000054', 0, 'Thinner estandar 960ml', '2018-06-28 09:44:49', 1850, 26.5, '43', 'Etiqueta abierta', 20.19, 0),
(33, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000055', 0, 'Thinner estandar 960ml', '2018-06-28 09:53:44', 1850, 26.5, '44', 'Etiqueta abierta', 20.19, 0),
(34, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000056', 0, 'Thinner estandar 960ml', '2018-06-28 10:08:27', 1850, 26.5, '45', 'Etiqueta abierta', 20.19, 0),
(35, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000057', 0, 'Thinner estandar 960ml', '2018-06-28 10:17:48', 1850, 26.5, '46', 'Etiqueta abierta', 20.19, 0),
(36, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000058', 0, 'Thinner estandar 960ml', '2018-06-28 10:25:55', 1850, 26.9, '47', 'Etiqueta abierta', 20.19, 0),
(37, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000059', 0, 'Thinner estandar 960ml', '2018-06-28 10:36:06', 1850, 26.5, '48', 'Etiqueta abierta', 20.19, 0),
(38, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000060', 0, 'Thinner estandar 960ml', '2018-06-28 10:47:39', 1850, 26.5, '49', 'Etiqueta abierta', 20.19, 0),
(39, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000061', 0, 'Thinner estandar 960ml', '2018-06-28 11:01:31', 1850, 26.5, '50', 'Etiqueta abierta', 20.19, 0),
(40, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000062', 0, 'Thinner estandar 960ml', '2018-06-28 11:18:43', 1850, 26.5, '51', 'Etiqueta abierta', 20.19, 0),
(41, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000063', 0, 'Thinner estandar 960ml', '2018-06-28 11:25:45', 1850, 26.5, '52', 'Etiqueta abierta', 20.19, 0),
(42, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000064', 0, 'Thinner estandar 960ml', '2018-06-28 11:40:00', 1850, 26.5, '53', 'Etiqueta abierta', 20.19, 0),
(43, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000065', 0, 'Thinner estandar 960ml', '2018-06-28 11:53:16', 1850, 26.5, '54', 'Etiqueta abierta', 20.19, 0),
(44, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000066', 0, 'Thinner estandar 960ml', '2018-06-28 12:08:53', 1850, 26.5, '55', 'Etiqueta abierta', 20.19, 0),
(45, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000068', 0, 'Thinner estandar 960ml', '2018-06-28 12:24:57', 1850, 26.5, '57', 'Etiqueta abierta', 20.19, 0),
(46, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000069', 0, 'Thinner estandar 960ml', '2018-06-28 12:33:21', 1850, 26.5, '58', 'Etiqueta abierta', 20.19, 0),
(47, 'Luis Felipe Fuentes Medina |018', 'Impresora', 'WR8051838 | Importado D.', '00000070', 0, 'Thinner estandar 960ml', '2018-06-28 12:56:16', 1850, 26.5, '59', 'Etiqueta abierta', 20.19, 0),
(48, 'John Connor|011', 'Impresora con Huecograbado 03 (7 tintas/gris)', 'WR8051838 | Importado D.', '00000046', 1, 'Thinner estandar 960ml', '2018-08-16 10:27:08', 1800, 25, '60', 'Etiqueta abierta', 19.6443, 0),
(49, 'John Connor|011', 'Impresora con Huecograbado 03 (7 tintas/gris)', 'WR8051838 | Importado D.', '00000081', 0, 'Thinner estandar 960ml', '2018-10-01 13:38:53', 1700, 21, '71', 'Etiqueta abierta', 18.5529, 0),
(50, 'John Connor|011', 'Impresora con Huecograbado 03 (7 tintas/gris)', 'WR8051838 | Importado D.', '00000071', 1, 'Thinner estandar 960ml', '2018-11-26 15:56:06', 1800, 25, '61', 'Etiqueta abierta', 19.6443, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proimpresion-flexografica`
--

CREATE TABLE `proimpresion-flexografica` (
  `id` int(11) NOT NULL,
  `operador` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `maquina` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `juegoCireles` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `suaje` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `lote` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `total` int(11) NOT NULL DEFAULT '1',
  `producto` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `longitud` float NOT NULL,
  `peso` float NOT NULL,
  `noop` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `unidades` float NOT NULL,
  `anillox` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `rollo_padre` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prolaminado`
--

CREATE TABLE `prolaminado` (
  `id` int(11) NOT NULL,
  `producto` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `noop` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `total` float NOT NULL DEFAULT '1',
  `unidades` float NOT NULL,
  `operador` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `maquina` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lote` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitud` float DEFAULT NULL,
  `amplitud` float DEFAULT NULL,
  `peso` float DEFAULT NULL,
  `tipo` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `bandera` int(11) DEFAULT NULL,
  `rollo_padre` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `prolaminado`
--

INSERT INTO `prolaminado` (`id`, `producto`, `fecha`, `noop`, `total`, `unidades`, `operador`, `maquina`, `lote`, `longitud`, `amplitud`, `peso`, `tipo`, `bandera`, `rollo_padre`) VALUES
(1, 'Thinner estandar 960ml', '2018-06-13 11:49:06', '13', 0, 17.461, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000222', 1600, 400, 15.8, 'Etiqueta abierta', 1, 0),
(3, 'Thinner estandar 960ml', '2018-06-13 11:57:22', '15', 0, 19.065, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000224', 1747, 400, 17, 'Etiqueta abierta', 1, 0),
(4, 'Thinner estandar 960ml', '2018-06-13 11:58:45', '22', 0, 13.532, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000231', 1240, 400, 12.23, 'Etiqueta abierta', 2, 0),
(5, 'Thinner estandar 960ml', '2018-06-15 12:23:33', '16', 0, 30.0667, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000225', 2755, 400, 27.74, 'Etiqueta abierta', 2, 0),
(6, 'Thinner estandar 960ml', '2018-06-13 12:04:13', '17', 0, 18.552, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000226', 1700, 400, 16.78, 'Etiqueta abierta', 1, 0),
(7, 'Thinner estandar 960ml', '2018-06-13 12:28:02', '20', 0, 28.7025, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000229', 2630, 400, 23.78, 'Etiqueta abierta', 1, 0),
(8, 'Thinner estandar 960ml', '2018-06-14 09:08:55', '14', 0, 25.9741, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000223', 2380, 400, 23.61, 'Etiqueta abierta', 2, 0),
(9, 'Thinner estandar 960ml', '2018-06-14 10:18:18', '34', 0, 21.2377, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000243', 1946, 400, 28.1, 'Etiqueta abierta', 0, 0),
(10, 'Thinner estandar 960ml', '2018-06-15 12:53:48', '33', 0, 19.6443, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000242', 1800, 400, 27.06, 'Etiqueta abierta', 3, 0),
(11, 'Thinner estandar 960ml', '2018-06-19 11:18:14', '19', 0, 21.2813, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000228', 1950, 400, 19.5, 'Etiqueta abierta', 3, 0),
(12, 'Thinner estandar 960ml', '2018-06-19 11:30:29', '18', 0, 35.4689, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000227', 3250, 400, 35, 'Etiqueta abierta', 3, 0),
(13, 'Thinner estandar 960ml', '2018-06-19 11:41:41', '23', 0, 21.2813, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000232', 1950, 400, 19.65, 'Etiqueta abierta', 3, 0),
(14, 'Thinner estandar 960ml', '2018-06-19 11:47:02', '31', 0, 19.6443, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000240', 1800, 400, 33, 'Etiqueta abierta', 3, 0),
(15, 'Thinner estandar 960ml', '2018-06-25 12:15:04', '21', 0, 19.6443, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000230', 1800, 400, 28.14, 'Etiqueta abierta', 3, 0),
(16, 'Thinner estandar 960ml', '2018-06-25 12:23:07', '24', 0, 21.2813, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000233', 1950, 400, 31, 'Etiqueta abierta', 1, 0),
(17, 'Thinner estandar 960ml', '2018-06-25 12:38:54', '25', 0, 21.2813, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000234', 1950, 400, 32.16, 'Etiqueta abierta', 2, 0),
(18, 'Thinner estandar 960ml', '2018-06-25 12:51:55', '30', 0, 21.2813, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000239', 1950, 400, 31, 'Etiqueta abierta', 1, 0),
(19, 'Thinner estandar 960ml', '2018-06-25 13:12:57', '32', 0, 20.7356, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000241', 1900, 400, 30.45, 'Etiqueta abierta', 1, 0),
(20, 'Thinner estandar 960ml', '2018-06-25 13:20:26', '26', 0, 21.2813, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000235', 1950, 400, 30, 'Etiqueta abierta', 2, 0),
(21, 'Thinner estandar 960ml', '2018-06-25 13:27:55', '27', 0, 17.4616, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000236', 1600, 400, 23, 'Etiqueta abierta', 0, 0),
(22, 'Thinner estandar 960ml', '2018-06-25 14:05:19', '28', 0, 17.4616, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000237', 1600, 400, 23, 'Etiqueta abierta', 1, 0),
(23, 'Thinner estandar 960ml', '2018-06-25 14:10:44', '29', 0, 21.2813, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000238', 1950, 400, 28, 'Etiqueta abierta', 2, 0),
(24, 'Thinner estandar 960ml', '2018-06-27 13:16:43', '56', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000267', 1850, 400, 10.33, 'Etiqueta abierta', 2, 0),
(25, 'Thinner estandar 960ml', '2018-06-27 17:18:26', '35', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000244', 1850, 400, 29, 'Etiqueta abierta', 3, 0),
(26, 'Thinner estandar 960ml', '2018-06-27 17:34:30', '36', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000247', 1850, 400, 30.45, 'Etiqueta abierta', 3, 0),
(27, 'Thinner estandar 960ml', '2018-06-28 07:58:00', '37', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000248', 1850, 400, 30.45, 'Etiqueta abierta', 3, 0),
(28, 'Thinner estandar 960ml', '2018-06-28 08:13:42', '38', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000249', 1850, 400, 30.45, 'Etiqueta abierta', 3, 0),
(29, 'Thinner estandar 960ml', '2018-06-28 08:30:01', '39', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000250', 1850, 400, 26.5, 'Etiqueta abierta', 0, 0),
(30, 'Thinner estandar 960ml', '2018-06-28 08:38:05', '40', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000251', 1850, 400, 26.5, 'Etiqueta abierta', 2, 0),
(31, 'Thinner estandar 960ml', '2018-06-28 08:51:15', '41', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000252', 1850, 400, 30.45, 'Etiqueta abierta', 3, 0),
(32, 'Thinner estandar 960ml', '2018-06-28 09:04:22', '42', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000253', 1850, 400, 30.99, 'Etiqueta abierta', 3, 0),
(33, 'Thinner estandar 960ml', '2018-06-28 09:48:30', '43', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000254', 1850, 400, 30.45, 'Etiqueta abierta', 3, 0),
(34, 'Thinner estandar 960ml', '2018-06-28 09:56:36', '44', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000255', 1850, 400, 30.45, 'Etiqueta abierta', 3, 0),
(35, 'Thinner estandar 960ml', '2018-06-28 10:11:27', '45', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000256', 1850, 400, 10.51, 'Etiqueta abierta', 3, 0),
(36, 'Thinner estandar 960ml', '2018-06-28 10:21:50', '46', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000257', 1850, 400, 30.76, 'Etiqueta abierta', 3, 0),
(37, 'Thinner estandar 960ml', '2018-06-28 10:29:18', '47', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000258', 1850, 400, 30.45, 'Etiqueta abierta', 3, 0),
(38, 'Thinner estandar 960ml', '2018-06-28 10:42:55', '48', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000259', 1850, 400, 30.45, 'Etiqueta abierta', 3, 0),
(39, 'Thinner estandar 960ml', '2018-06-28 10:50:49', '49', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000260', 1850, 400, 30.76, 'Etiqueta abierta', 3, 0),
(40, 'Thinner estandar 960ml', '2018-06-28 11:11:54', '50', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000261', 1850, 400, 30.45, 'Etiqueta abierta', 2, 0),
(41, 'Thinner estandar 960ml', '2018-06-28 11:21:23', '51', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000262', 1850, 400, 30.99, 'Etiqueta abierta', 3, 0),
(42, 'Thinner estandar 960ml', '2018-06-28 11:32:11', '52', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000263', 1850, 400, 30.75, 'Etiqueta abierta', 3, 0),
(43, 'Thinner estandar 960ml', '2018-06-28 11:44:51', '53', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000264', 1850, 400, 30.45, 'Etiqueta abierta', 3, 0),
(44, 'Thinner estandar 960ml', '2018-06-28 11:57:41', '54', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000265', 1850, 400, 30.76, 'Etiqueta abierta', 2, 0),
(45, 'Thinner estandar 960ml', '2018-06-28 12:19:02', '55', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000266', 1850, 400, 30.45, 'Etiqueta abierta', 3, 0),
(46, 'Thinner estandar 960ml', '2018-06-28 12:29:06', '57', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000268', 1850, 400, 30.99, 'Etiqueta abierta', 3, 0),
(47, 'Thinner estandar 960ml', '2018-06-28 12:42:45', '58', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000269', 1850, 400, 30, 'Etiqueta abierta', 2, 0),
(48, 'Thinner estandar 960ml', '2018-06-28 12:59:37', '59', 0, 20.19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina laminadora', '00000270', 1850, 400, 30.45, 'Etiqueta abierta', 3, 0),
(49, 'Thinner estandar 960ml', '2018-10-01 13:51:27', '71', 1, 18.4438, 'John Connor|011', 'MÃ¡quina laminadora', '00000281', 1690, 200, 4, 'Etiqueta abierta', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prolaminado 2`
--

CREATE TABLE `prolaminado 2` (
  `id` int(11) NOT NULL,
  `producto` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `noop` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `total` float NOT NULL DEFAULT '1',
  `unidades` float NOT NULL,
  `operador` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `maquina` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lote` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitud` float DEFAULT NULL,
  `amplitud` float DEFAULT NULL,
  `peso` float DEFAULT NULL,
  `tipo` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `bandera` int(11) DEFAULT NULL,
  `rollo_padre` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prorefilado`
--

CREATE TABLE `prorefilado` (
  `id` int(11) NOT NULL,
  `total` float NOT NULL DEFAULT '1',
  `producto` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `operador` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `maquina` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lote` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitud` float DEFAULT NULL,
  `amplitud` float DEFAULT NULL,
  `peso` float DEFAULT NULL,
  `noop` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `unidades` float NOT NULL,
  `rollo_padre` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `prorefilado`
--

INSERT INTO `prorefilado` (`id`, `total`, `producto`, `fecha`, `operador`, `maquina`, `lote`, `longitud`, `amplitud`, `peso`, `noop`, `tipo`, `unidades`, `rollo_padre`) VALUES
(1, 1, 'Thinner estandar 960ml', '2018-06-13 13:11:25', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000324', NULL, NULL, NULL, '15', 'Etiqueta abierta', 0, 1),
(2, 1, 'Thinner estandar 960ml', '2018-06-13 13:11:25', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000324', 1850, 127, 5.6, '15-1', 'Etiqueta abierta', 6.73953, 0),
(3, 1, 'Thinner estandar 960ml', '2018-06-13 13:11:25', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000324', 1750, 127, 5.6, '15-2', 'Etiqueta abierta', 6.37523, 0),
(4, 1, 'Thinner estandar 960ml', '2018-06-13 13:11:25', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000324', 1850, 127, 5.6, '15-3', 'Etiqueta abierta', 6.73953, 0),
(5, 1, 'Thinner estandar 960ml', '2018-06-13 13:11:25', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000324', 0, 0, 0, '15-4', 'Etiqueta abierta', 0, 0),
(6, 1, 'Thinner estandar 960ml', '2018-06-14 08:32:33', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000329', NULL, NULL, NULL, '20', 'Etiqueta abierta', 0, 1),
(7, 1, 'Thinner estandar 960ml', '2018-06-14 08:32:33', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000329', 1950, 127, 6.55, '20-1', 'Etiqueta abierta', 7.10383, 0),
(8, 1, 'Thinner estandar 960ml', '2018-06-14 08:32:33', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000329', 1950, 127, 6.55, '20-2', 'Etiqueta abierta', 7.10383, 0),
(9, 1, 'Thinner estandar 960ml', '2018-06-14 08:32:33', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000329', 1950, 127, 6.55, '20-3', 'Etiqueta abierta', 7.10383, 0),
(10, 1, 'Thinner estandar 960ml', '2018-06-14 08:32:33', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000329', 0, 0, 0, '20-4', 'Etiqueta abierta', 0, 0),
(11, 1, 'Thinner estandar 960ml', '2018-06-14 09:10:27', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000323', NULL, NULL, NULL, '14', 'Etiqueta abierta', 0, 1),
(12, 1, 'Thinner estandar 960ml', '2018-06-14 09:10:27', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000323', 1900, 127, 6.28, '14-1', 'Etiqueta abierta', 6.92168, 0),
(13, 1, 'Thinner estandar 960ml', '2018-06-14 09:10:27', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000323', 1900, 127, 6.28, '14-2', 'Etiqueta abierta', 6.92168, 0),
(14, 1, 'Thinner estandar 960ml', '2018-06-14 09:10:27', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000323', 1900, 127, 6.28, '14-3', 'Etiqueta abierta', 6.92168, 0),
(15, 1, 'Thinner estandar 960ml', '2018-06-14 09:10:27', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000323', 0, 0, 0, '14-4', 'Etiqueta abierta', 0, 0),
(16, 1, 'Thinner estandar 960ml', '2018-06-14 10:19:37', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000343', NULL, NULL, NULL, '34', 'Etiqueta abierta', 0, 1),
(17, 1, 'Thinner estandar 960ml', '2018-06-14 10:19:38', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000343', 1950, 127, 11, '34-1', 'Etiqueta abierta', 7.10383, 0),
(18, 1, 'Thinner estandar 960ml', '2018-06-14 10:19:38', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000343', 1950, 127, 11, '34-2', 'Etiqueta abierta', 7.10383, 0),
(19, 1, 'Thinner estandar 960ml', '2018-06-14 10:19:38', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000343', 1950, 127, 11, '34-3', 'Etiqueta abierta', 7.10383, 0),
(20, 1, 'Thinner estandar 960ml', '2018-06-14 10:19:38', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000343', 0, 0, 0, '34-4', 'Etiqueta abierta', 0, 0),
(21, 1, 'Thinner estandar 960ml', '2018-06-14 11:34:38', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000322', NULL, NULL, NULL, '13', 'Etiqueta abierta', 0, 1),
(22, 0, 'Thinner estandar 960ml', '2018-06-14 11:34:38', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000322', 1750, 127, 15.8, '13-1', 'Etiqueta abierta', 6.37523, 0),
(23, 0, 'Thinner estandar 960ml', '2018-06-14 11:34:38', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000322', 1850, 127, 15.8, '13-2', 'Etiqueta abierta', 6.73953, 0),
(24, 1, 'Thinner estandar 960ml', '2018-06-14 11:34:38', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000322', 1850, 127, 15.8, '13-3', 'Etiqueta abierta', 6.73953, 0),
(25, 1, 'Thinner estandar 960ml', '2018-06-14 11:34:38', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000322', 0, 0, 0, '13-4', 'Etiqueta abierta', 0, 0),
(26, 1, 'Thinner estandar 960ml', '2018-06-15 12:29:01', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000325', NULL, NULL, NULL, '16', 'Etiqueta abierta', 0, 1),
(27, 1, 'Thinner estandar 960ml', '2018-06-15 12:29:01', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000325', 1950, 127, 10, '16-1', 'Etiqueta abierta', 7.10383, 0),
(28, 1, 'Thinner estandar 960ml', '2018-06-15 12:29:01', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000325', 1950, 127, 10, '16-2', 'Etiqueta abierta', 7.10383, 0),
(29, 1, 'Thinner estandar 960ml', '2018-06-15 12:29:01', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000325', 1950, 127, 10, '16-3', 'Etiqueta abierta', 7.10383, 0),
(30, 1, 'Thinner estandar 960ml', '2018-06-15 12:29:01', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000325', 0, 0, 0, '16-4', 'Etiqueta abierta', 0, 0),
(31, 1, 'Thinner estandar 960ml', '2018-06-15 12:54:34', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000342', NULL, NULL, NULL, '33', 'Etiqueta abierta', 0, 1),
(32, 1, 'Thinner estandar 960ml', '2018-06-15 12:54:34', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000342', 1800, 127, 9.5, '33-1', 'Etiqueta abierta', 6.55738, 0),
(33, 1, 'Thinner estandar 960ml', '2018-06-15 12:54:34', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000342', 1800, 127, 9.5, '33-2', 'Etiqueta abierta', 6.55738, 0),
(34, 1, 'Thinner estandar 960ml', '2018-06-15 12:54:34', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000342', 1950, 127, 9.5, '33-3', 'Etiqueta abierta', 7.10383, 0),
(35, 1, 'Thinner estandar 960ml', '2018-06-15 12:54:34', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000342', 0, 0, 0, '33-4', 'Etiqueta abierta', 0, 0),
(36, 1, 'Thinner estandar 960ml', '2018-06-19 11:21:07', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000328', NULL, NULL, NULL, '19', 'Etiqueta abierta', 0, 1),
(37, 1, 'Thinner estandar 960ml', '2018-06-19 11:21:07', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000328', 1950, 127, 10.5, '19-1', 'Etiqueta abierta', 7.10383, 0),
(38, 1, 'Thinner estandar 960ml', '2018-06-19 11:21:07', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000328', 1950, 127, 10.5, '19-2', 'Etiqueta abierta', 7.10383, 0),
(39, 1, 'Thinner estandar 960ml', '2018-06-19 11:21:07', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000328', 1950, 127, 10.5, '19-3', 'Etiqueta abierta', 7.10383, 0),
(40, 1, 'Thinner estandar 960ml', '2018-06-19 11:21:07', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000328', 0, 0, 0, '19-4', 'Etiqueta abierta', 0, 0),
(41, 1, 'Thinner estandar 960ml', '2018-06-19 11:28:09', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000326', NULL, NULL, NULL, '17', 'Etiqueta abierta', 0, 1),
(42, 1, 'Thinner estandar 960ml', '2018-06-19 11:28:09', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000326', 1750, 127, 10, '17-1', 'Etiqueta abierta', 6.37523, 0),
(43, 1, 'Thinner estandar 960ml', '2018-06-19 11:28:09', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000326', 1750, 127, 10, '17-2', 'Etiqueta abierta', 6.37523, 0),
(44, 1, 'Thinner estandar 960ml', '2018-06-19 11:28:09', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000326', 1750, 127, 10, '17-3', 'Etiqueta abierta', 6.37523, 0),
(45, 1, 'Thinner estandar 960ml', '2018-06-19 11:28:09', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000326', 0, 0, 0, '17-4', 'Etiqueta abierta', 0, 0),
(46, 1, 'Thinner estandar 960ml', '2018-06-19 11:32:24', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000327', NULL, NULL, NULL, '18', 'Etiqueta abierta', 0, 1),
(47, 1, 'Thinner estandar 960ml', '2018-06-19 11:32:24', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000327', 1800, 127, 11.8, '18-1', 'Etiqueta abierta', 6.55738, 0),
(48, 1, 'Thinner estandar 960ml', '2018-06-19 11:32:24', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000327', 1800, 127, 11.8, '18-2', 'Etiqueta abierta', 6.55738, 0),
(49, 1, 'Thinner estandar 960ml', '2018-06-19 11:32:24', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000327', 1800, 127, 11.8, '18-3', 'Etiqueta abierta', 6.55738, 0),
(50, 1, 'Thinner estandar 960ml', '2018-06-19 11:32:24', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000327', 0, 0, 0, '18-4', 'Etiqueta abierta', 0, 0),
(51, 1, 'Thinner estandar 960ml', '2018-06-19 11:35:53', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000331', NULL, NULL, NULL, '22', 'Etiqueta abierta', 0, 1),
(52, 1, 'Thinner estandar 960ml', '2018-06-19 11:35:53', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000331', 1750, 127, 9.5, '22-1', 'Etiqueta abierta', 6.37523, 0),
(53, 1, 'Thinner estandar 960ml', '2018-06-19 11:35:53', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000331', 1750, 127, 9.5, '22-2', 'Etiqueta abierta', 6.37523, 0),
(54, 1, 'Thinner estandar 960ml', '2018-06-19 11:35:53', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000331', 1750, 127, 9.5, '22-3', 'Etiqueta abierta', 6.37523, 0),
(55, 1, 'Thinner estandar 960ml', '2018-06-19 11:35:53', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000331', 0, 0, 0, '22-4', 'Etiqueta abierta', 0, 0),
(56, 1, 'Thinner estandar 960ml', '2018-06-19 11:42:23', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000332', NULL, NULL, NULL, '23', 'Etiqueta abierta', 0, 1),
(57, 1, 'Thinner estandar 960ml', '2018-06-19 11:42:23', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000332', 1950, 127, 11.66, '23-1', 'Etiqueta abierta', 7.10383, 0),
(58, 1, 'Thinner estandar 960ml', '2018-06-19 11:42:23', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000332', 1950, 127, 11.66, '23-2', 'Etiqueta abierta', 7.10383, 0),
(59, 1, 'Thinner estandar 960ml', '2018-06-19 11:42:23', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000332', 1950, 127, 11.66, '23-3', 'Etiqueta abierta', 7.10383, 0),
(60, 1, 'Thinner estandar 960ml', '2018-06-19 11:42:23', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000332', 0, 0, 0, '23-4', 'Etiqueta abierta', 0, 0),
(61, 1, 'Thinner estandar 960ml', '2018-06-19 11:47:53', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000340', NULL, NULL, NULL, '31', 'Etiqueta abierta', 0, 1),
(62, 1, 'Thinner estandar 960ml', '2018-06-19 11:47:53', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000340', 1850, 127, 11.66, '31-1', 'Etiqueta abierta', 6.73953, 0),
(63, 1, 'Thinner estandar 960ml', '2018-06-19 11:47:53', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000340', 1850, 127, 11.66, '31-2', 'Etiqueta abierta', 6.73953, 0),
(64, 1, 'Thinner estandar 960ml', '2018-06-19 11:47:53', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000340', 1750, 127, 11.6, '31-3', 'Etiqueta abierta', 6.37523, 0),
(65, 1, 'Thinner estandar 960ml', '2018-06-19 11:47:53', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000340', 0, 0, 0, '31-4', 'Etiqueta abierta', 0, 0),
(66, 1, 'Thinner estandar 960ml', '2018-06-25 12:16:42', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000330', NULL, NULL, NULL, '21', 'Etiqueta abierta', 0, 1),
(67, 1, 'Thinner estandar 960ml', '2018-06-25 12:16:42', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000330', 1800, 127, 9.4, '21-1', 'Etiqueta abierta', 6.55738, 0),
(68, 1, 'Thinner estandar 960ml', '2018-06-25 12:16:42', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000330', 1800, 127, 9.4, '21-2', 'Etiqueta abierta', 6.55738, 0),
(69, 1, 'Thinner estandar 960ml', '2018-06-25 12:16:42', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000330', 1800, 127, 9.4, '21-3', 'Etiqueta abierta', 6.55738, 0),
(70, 1, 'Thinner estandar 960ml', '2018-06-25 12:16:42', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000330', 0, 0, 0, '21-4', 'Etiqueta abierta', 0, 0),
(71, 1, 'Thinner estandar 960ml', '2018-06-25 12:24:46', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000333', NULL, NULL, NULL, '24', 'Etiqueta abierta', 0, 1),
(72, 1, 'Thinner estandar 960ml', '2018-06-25 12:24:46', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000333', 1950, 127, 10.33, '24-1', 'Etiqueta abierta', 7.10383, 0),
(73, 1, 'Thinner estandar 960ml', '2018-06-25 12:24:46', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000333', 1950, 127, 10.33, '24-2', 'Etiqueta abierta', 7.10383, 0),
(74, 1, 'Thinner estandar 960ml', '2018-06-25 12:24:46', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000333', 1950, 127, 10.33, '24-3', 'Etiqueta abierta', 7.10383, 0),
(75, 1, 'Thinner estandar 960ml', '2018-06-25 12:24:46', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000333', 0, 0, 0, '24-4', 'Etiqueta abierta', 0, 0),
(76, 1, 'Thinner estandar 960ml', '2018-06-25 12:39:48', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000334', NULL, NULL, NULL, '25', 'Etiqueta abierta', 0, 1),
(77, 1, 'Thinner estandar 960ml', '2018-06-25 12:39:48', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000334', 1950, 127, 10.72, '25-1', 'Etiqueta abierta', 7.10383, 0),
(78, 1, 'Thinner estandar 960ml', '2018-06-25 12:39:48', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000334', 1950, 127, 10.72, '25-2', 'Etiqueta abierta', 7.10383, 0),
(79, 1, 'Thinner estandar 960ml', '2018-06-25 12:39:48', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000334', 1950, 127, 10.72, '25-3', 'Etiqueta abierta', 7.10383, 0),
(80, 1, 'Thinner estandar 960ml', '2018-06-25 12:39:48', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000334', 0, 0, 0, '25-4', 'Etiqueta abierta', 0, 0),
(81, 1, 'Thinner estandar 960ml', '2018-06-25 12:52:34', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000339', NULL, NULL, NULL, '30', 'Etiqueta abierta', 0, 1),
(82, 1, 'Thinner estandar 960ml', '2018-06-25 12:52:34', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000339', 1900, 127, 10, '30-1', 'Etiqueta abierta', 6.92168, 0),
(83, 1, 'Thinner estandar 960ml', '2018-06-25 12:52:34', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000339', 1900, 127, 10, '30-2', 'Etiqueta abierta', 6.92168, 0),
(84, 1, 'Thinner estandar 960ml', '2018-06-25 12:52:34', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000339', 1850, 127, 9.8, '30-3', 'Etiqueta abierta', 6.73953, 0),
(85, 1, 'Thinner estandar 960ml', '2018-06-25 12:52:34', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000339', 0, 0, 0, '30-4', 'Etiqueta abierta', 0, 0),
(86, 1, 'Thinner estandar 960ml', '2018-06-25 13:14:31', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000341', NULL, NULL, NULL, '32', 'Etiqueta abierta', 0, 1),
(87, 1, 'Thinner estandar 960ml', '2018-06-25 13:14:31', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000341', 1900, 127, 10.15, '32-1', 'Etiqueta abierta', 6.92168, 0),
(88, 1, 'Thinner estandar 960ml', '2018-06-25 13:14:31', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000341', 1900, 127, 10.15, '32-2', 'Etiqueta abierta', 6.92168, 0),
(89, 1, 'Thinner estandar 960ml', '2018-06-25 13:14:31', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000341', 1900, 127, 10.15, '32-3', 'Etiqueta abierta', 6.92168, 0),
(90, 1, 'Thinner estandar 960ml', '2018-06-25 13:14:31', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000341', 0, 0, 0, '32-4', 'Etiqueta abierta', 0, 0),
(91, 1, 'Thinner estandar 960ml', '2018-06-25 13:21:28', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000335', NULL, NULL, NULL, '26', 'Etiqueta abierta', 0, 1),
(92, 1, 'Thinner estandar 960ml', '2018-06-25 13:21:28', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000335', 1950, 127, 10, '26-1', 'Etiqueta abierta', 7.10383, 0),
(93, 1, 'Thinner estandar 960ml', '2018-06-25 13:21:28', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000335', 1900, 127, 9.74, '26-2', 'Etiqueta abierta', 6.92168, 0),
(94, 1, 'Thinner estandar 960ml', '2018-06-25 13:21:28', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000335', 1850, 127, 9.5, '26-3', 'Etiqueta abierta', 6.73953, 0),
(95, 1, 'Thinner estandar 960ml', '2018-06-25 13:21:28', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000335', 0, 0, 0, '26-4', 'Etiqueta abierta', 0, 0),
(96, 1, 'Thinner estandar 960ml', '2018-06-25 13:28:33', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000336', NULL, NULL, NULL, '27', 'Etiqueta abierta', 0, 1),
(97, 1, 'Thinner estandar 960ml', '2018-06-25 13:28:33', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000336', 1760, 127, 8.43, '27-1', 'Etiqueta abierta', 6.41166, 0),
(98, 1, 'Thinner estandar 960ml', '2018-06-25 13:28:33', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000336', 1760, 127, 8.43, '27-2', 'Etiqueta abierta', 6.41166, 0),
(99, 1, 'Thinner estandar 960ml', '2018-06-25 13:28:33', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000336', 1760, 127, 8.43, '27-3', 'Etiqueta abierta', 6.41166, 0),
(100, 1, 'Thinner estandar 960ml', '2018-06-25 13:28:33', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000336', 0, 0, 0, '27-4', 'Etiqueta abierta', 0, 0),
(101, 1, 'Thinner estandar 960ml', '2018-06-25 14:06:19', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000337', NULL, NULL, NULL, '28', 'Etiqueta abierta', 0, 1),
(102, 1, 'Thinner estandar 960ml', '2018-06-25 14:06:19', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000337', 1800, 127, 8.63, '28-1', 'Etiqueta abierta', 6.55738, 0),
(103, 1, 'Thinner estandar 960ml', '2018-06-25 14:06:19', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000337', 1800, 127, 8.63, '28-2', 'Etiqueta abierta', 6.55738, 0),
(104, 1, 'Thinner estandar 960ml', '2018-06-25 14:06:19', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000337', 1800, 127, 8.63, '28-3', 'Etiqueta abierta', 6.55738, 0),
(105, 1, 'Thinner estandar 960ml', '2018-06-25 14:06:19', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000337', 0, 0, 0, '28-4', 'Etiqueta abierta', 0, 0),
(106, 1, 'Thinner estandar 960ml', '2018-06-25 14:11:22', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000338', NULL, NULL, NULL, '29', 'Etiqueta abierta', 0, 1),
(107, 1, 'Thinner estandar 960ml', '2018-06-25 14:11:22', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000338', 1950, 127, 10.33, '29-1', 'Etiqueta abierta', 7.10383, 0),
(108, 1, 'Thinner estandar 960ml', '2018-06-25 14:11:22', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000338', 1950, 127, 10.33, '29-2', 'Etiqueta abierta', 7.10383, 0),
(109, 1, 'Thinner estandar 960ml', '2018-06-25 14:11:22', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000338', 1950, 127, 10.33, '29-3', 'Etiqueta abierta', 7.10383, 0),
(110, 1, 'Thinner estandar 960ml', '2018-06-25 14:11:22', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000338', 0, 0, 0, '29-4', 'Etiqueta abierta', 0, 0),
(111, 1, 'Thinner estandar 960ml', '2018-06-27 13:19:13', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000367', NULL, NULL, NULL, '56', 'Etiqueta abierta', 0, 1),
(112, 1, 'Thinner estandar 960ml', '2018-06-27 13:19:13', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000367', 1930, 127, 10.77, '56-1', 'Etiqueta abierta', 7.03097, 0),
(113, 1, 'Thinner estandar 960ml', '2018-06-27 13:19:13', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000367', 1950, 127, 10.88, '56-2', 'Etiqueta abierta', 7.10383, 0),
(114, 1, 'Thinner estandar 960ml', '2018-06-27 13:19:13', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000367', 1850, 127, 10.33, '56-3', 'Etiqueta abierta', 6.73953, 0),
(115, 1, 'Thinner estandar 960ml', '2018-06-27 13:19:13', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000367', 0, 0, 0, '56-4', 'Etiqueta abierta', 0, 0),
(116, 1, 'Thinner estandar 960ml', '2018-06-27 17:24:20', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000344', NULL, NULL, NULL, '35', 'Etiqueta abierta', 0, 1),
(117, 1, 'Thinner estandar 960ml', '2018-06-27 17:24:20', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000344', 1900, 127, 9.93, '35-1', 'Etiqueta abierta', 6.92168, 0),
(118, 1, 'Thinner estandar 960ml', '2018-06-27 17:24:20', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000344', 1900, 127, 9.93, '35-2', 'Etiqueta abierta', 6.92168, 0),
(119, 1, 'Thinner estandar 960ml', '2018-06-27 17:24:20', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000344', 1900, 127, 9.93, '35-3', 'Etiqueta abierta', 6.92168, 0),
(120, 1, 'Thinner estandar 960ml', '2018-06-27 17:24:20', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000344', 0, 0, 0, '35-4', 'Etiqueta abierta', 0, 0),
(121, 1, 'Thinner estandar 960ml', '2018-06-27 17:35:52', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000347', NULL, NULL, NULL, '36', 'Etiqueta abierta', 0, 1),
(122, 1, 'Thinner estandar 960ml', '2018-06-27 17:35:52', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000347', 1950, 127, 10.15, '36-1', 'Etiqueta abierta', 7.10383, 0),
(123, 1, 'Thinner estandar 960ml', '2018-06-27 17:35:52', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000347', 1950, 127, 10.15, '36-2', 'Etiqueta abierta', 7.10383, 0),
(124, 1, 'Thinner estandar 960ml', '2018-06-27 17:35:52', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000347', 1950, 127, 10.15, '36-3', 'Etiqueta abierta', 7.10383, 0),
(125, 1, 'Thinner estandar 960ml', '2018-06-27 17:35:52', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000347', 0, 0, 0, '36-4', 'Etiqueta abierta', 0, 0),
(126, 1, 'Thinner estandar 960ml', '2018-06-28 08:00:28', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000348', NULL, NULL, NULL, '37', 'Etiqueta abierta', 0, 1),
(127, 1, 'Thinner estandar 960ml', '2018-06-28 08:00:28', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000348', 1950, 127, 10.15, '37-1', 'Etiqueta abierta', 7.10383, 0),
(128, 1, 'Thinner estandar 960ml', '2018-06-28 08:00:28', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000348', 1950, 127, 10.15, '37-2', 'Etiqueta abierta', 7.10383, 0),
(129, 1, 'Thinner estandar 960ml', '2018-06-28 08:00:28', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000348', 1950, 127, 10.15, '37-3', 'Etiqueta abierta', 7.10383, 0),
(130, 1, 'Thinner estandar 960ml', '2018-06-28 08:00:28', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000348', 0, 0, 0, '37-4', 'Etiqueta abierta', 0, 0),
(131, 1, 'Thinner estandar 960ml', '2018-06-28 08:15:34', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000349', NULL, NULL, NULL, '38', 'Etiqueta abierta', 0, 1),
(132, 1, 'Thinner estandar 960ml', '2018-06-28 08:15:34', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000349', 1950, 127, 10.69, '38-1', 'Etiqueta abierta', 7.10383, 0),
(133, 1, 'Thinner estandar 960ml', '2018-06-28 08:15:34', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000349', 1950, 127, 10.69, '38-2', 'Etiqueta abierta', 7.10383, 0),
(134, 1, 'Thinner estandar 960ml', '2018-06-28 08:15:34', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000349', 1850, 127, 10.15, '38-3', 'Etiqueta abierta', 6.73953, 0),
(135, 1, 'Thinner estandar 960ml', '2018-06-28 08:15:34', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000349', 0, 0, 0, '38-4', 'Etiqueta abierta', 0, 0),
(136, 1, 'Thinner estandar 960ml', '2018-06-28 08:31:45', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000350', NULL, NULL, NULL, '39', 'Etiqueta abierta', 0, 1),
(137, 1, 'Thinner estandar 960ml', '2018-06-28 08:31:45', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000350', 1800, 127, 8.6, '39-1', 'Etiqueta abierta', 6.55738, 0),
(138, 1, 'Thinner estandar 960ml', '2018-06-28 08:31:45', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000350', 1800, 127, 8.6, '39-2', 'Etiqueta abierta', 6.55738, 0),
(139, 1, 'Thinner estandar 960ml', '2018-06-28 08:31:45', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000350', 1750, 127, 8.35, '39-3', 'Etiqueta abierta', 6.37523, 0),
(140, 1, 'Thinner estandar 960ml', '2018-06-28 08:31:45', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000350', 0, 0, 0, '39-4', 'Etiqueta abierta', 0, 0),
(141, 1, 'Thinner estandar 960ml', '2018-06-28 08:41:24', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000351', NULL, NULL, NULL, '40', 'Etiqueta abierta', 0, 1),
(142, 1, 'Thinner estandar 960ml', '2018-06-28 08:41:24', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000351', 1870, 127, 9.05, '40-1', 'Etiqueta abierta', 6.81239, 0),
(143, 1, 'Thinner estandar 960ml', '2018-06-28 08:41:24', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000351', 1870, 127, 9.05, '40-2', 'Etiqueta abierta', 6.81239, 0),
(144, 1, 'Thinner estandar 960ml', '2018-06-28 08:41:24', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000351', 1950, 127, 9.3, '40-3', 'Etiqueta abierta', 7.10383, 0),
(145, 1, 'Thinner estandar 960ml', '2018-06-28 08:41:24', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000351', 0, 0, 0, '40-4', 'Etiqueta abierta', 0, 0),
(146, 1, 'Thinner estandar 960ml', '2018-06-28 08:54:05', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000352', NULL, NULL, NULL, '41', 'Etiqueta abierta', 0, 1),
(147, 1, 'Thinner estandar 960ml', '2018-06-28 08:54:05', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000352', 1800, 127, 10.33, '41-1', 'Etiqueta abierta', 6.55738, 0),
(148, 1, 'Thinner estandar 960ml', '2018-06-28 08:54:05', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000352', 1900, 127, 10.33, '41-2', 'Etiqueta abierta', 6.92168, 0),
(149, 1, 'Thinner estandar 960ml', '2018-06-28 08:54:05', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000352', 1950, 127, 10.33, '41-3', 'Etiqueta abierta', 7.10383, 0),
(150, 1, 'Thinner estandar 960ml', '2018-06-28 08:54:05', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000352', 0, 0, 0, '41-4', 'Etiqueta abierta', 0, 0),
(151, 1, 'Thinner estandar 960ml', '2018-06-28 09:05:41', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000353', NULL, NULL, NULL, '42', 'Etiqueta abierta', 0, 1),
(152, 1, 'Thinner estandar 960ml', '2018-06-28 09:05:41', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000353', 1750, 127, 9.77, '42-1', 'Etiqueta abierta', 6.37523, 0),
(153, 1, 'Thinner estandar 960ml', '2018-06-28 09:05:41', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000353', 1750, 127, 9.77, '42-2', 'Etiqueta abierta', 6.37523, 0),
(154, 1, 'Thinner estandar 960ml', '2018-06-28 09:05:41', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000353', 1750, 127, 9.77, '42-3', 'Etiqueta abierta', 6.37523, 0),
(155, 1, 'Thinner estandar 960ml', '2018-06-28 09:05:41', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000353', 0, 0, 0, '42-4', 'Etiqueta abierta', 0, 0),
(156, 1, 'Thinner estandar 960ml', '2018-06-28 09:50:19', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000354', NULL, NULL, NULL, '43', 'Etiqueta abierta', 0, 1),
(157, 1, 'Thinner estandar 960ml', '2018-06-28 09:50:19', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000354', 1750, 127, 9.6, '43-1', 'Etiqueta abierta', 6.37523, 0),
(158, 1, 'Thinner estandar 960ml', '2018-06-28 09:50:19', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000354', 1750, 127, 9.6, '43-2', 'Etiqueta abierta', 6.37523, 0),
(159, 1, 'Thinner estandar 960ml', '2018-06-28 09:50:19', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000354', 1750, 127, 9.6, '43-3', 'Etiqueta abierta', 6.37523, 0),
(160, 1, 'Thinner estandar 960ml', '2018-06-28 09:50:19', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000354', 0, 0, 0, '43-4', 'Etiqueta abierta', 0, 0),
(161, 1, 'Thinner estandar 960ml', '2018-06-28 09:58:09', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000355', NULL, NULL, NULL, '44', 'Etiqueta abierta', 0, 1),
(162, 1, 'Thinner estandar 960ml', '2018-06-28 09:58:09', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000355', 1900, 127, 10.51, '44-1', 'Etiqueta abierta', 6.92168, 0),
(163, 1, 'Thinner estandar 960ml', '2018-06-28 09:58:09', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000355', 1900, 127, 10.51, '44-2', 'Etiqueta abierta', 6.92168, 0),
(164, 1, 'Thinner estandar 960ml', '2018-06-28 09:58:09', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000355', 1950, 127, 10.51, '44-3', 'Etiqueta abierta', 7.10383, 0),
(165, 1, 'Thinner estandar 960ml', '2018-06-28 09:58:09', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000355', 0, 0, 0, '44-4', 'Etiqueta abierta', 0, 0),
(166, 1, 'Thinner estandar 960ml', '2018-06-28 10:14:38', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000356', NULL, NULL, NULL, '45', 'Etiqueta abierta', 0, 1),
(167, 1, 'Thinner estandar 960ml', '2018-06-28 10:14:38', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000356', 1950, 127, 10.51, '45-1', 'Etiqueta abierta', 7.10383, 0),
(168, 1, 'Thinner estandar 960ml', '2018-06-28 10:14:38', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000356', 1950, 127, 10.51, '45-2', 'Etiqueta abierta', 7.10383, 0),
(169, 1, 'Thinner estandar 960ml', '2018-06-28 10:14:38', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000356', 1950, 127, 10.51, '45-3', 'Etiqueta abierta', 7.10383, 0),
(170, 1, 'Thinner estandar 960ml', '2018-06-28 10:14:38', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000356', 0, 0, 0, '45-4', 'Etiqueta abierta', 0, 0),
(171, 1, 'Thinner estandar 960ml', '2018-06-28 10:23:06', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000357', NULL, NULL, NULL, '46', 'Etiqueta abierta', 0, 1),
(172, 1, 'Thinner estandar 960ml', '2018-06-28 10:23:06', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000357', 1900, 127, 10.53, '46-1', 'Etiqueta abierta', 6.92168, 0),
(173, 1, 'Thinner estandar 960ml', '2018-06-28 10:23:06', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000357', 1900, 127, 10.53, '46-2', 'Etiqueta abierta', 6.92168, 0),
(174, 1, 'Thinner estandar 960ml', '2018-06-28 10:23:06', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000357', 1900, 127, 10.53, '46-3', 'Etiqueta abierta', 6.92168, 0),
(175, 1, 'Thinner estandar 960ml', '2018-06-28 10:23:06', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000357', 0, 0, 0, '46-4', 'Etiqueta abierta', 0, 0),
(176, 1, 'Thinner estandar 960ml', '2018-06-28 10:33:21', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000358', NULL, NULL, NULL, '47', 'Etiqueta abierta', 0, 1),
(177, 1, 'Thinner estandar 960ml', '2018-06-28 10:33:21', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000358', 1950, 127, 10.69, '47-1', 'Etiqueta abierta', 7.10383, 0),
(178, 1, 'Thinner estandar 960ml', '2018-06-28 10:33:21', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000358', 1950, 127, 10.69, '47-2', 'Etiqueta abierta', 7.10383, 0),
(179, 1, 'Thinner estandar 960ml', '2018-06-28 10:33:21', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000358', 1950, 127, 10.69, '47-3', 'Etiqueta abierta', 7.10383, 0),
(180, 1, 'Thinner estandar 960ml', '2018-06-28 10:33:21', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000358', 0, 0, 0, '47-4', 'Etiqueta abierta', 0, 0),
(181, 1, 'Thinner estandar 960ml', '2018-06-28 10:44:35', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000359', NULL, NULL, NULL, '48', 'Etiqueta abierta', 0, 1),
(182, 1, 'Thinner estandar 960ml', '2018-06-28 10:44:35', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000359', 1840, 127, 10, '48-1', 'Etiqueta abierta', 6.7031, 0),
(183, 1, 'Thinner estandar 960ml', '2018-06-28 10:44:35', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000359', 1840, 127, 10, '48-2', 'Etiqueta abierta', 6.7031, 0),
(184, 1, 'Thinner estandar 960ml', '2018-06-28 10:44:35', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000359', 1840, 127, 10, '48-3', 'Etiqueta abierta', 6.7031, 0),
(185, 1, 'Thinner estandar 960ml', '2018-06-28 10:44:35', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000359', 0, 0, 0, '48-4', 'Etiqueta abierta', 0, 0),
(186, 1, 'Thinner estandar 960ml', '2018-06-28 10:52:44', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000360', NULL, NULL, NULL, '49', 'Etiqueta abierta', 0, 1),
(187, 1, 'Thinner estandar 960ml', '2018-06-28 10:52:44', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000360', 1870, 127, 10.25, '49-1', 'Etiqueta abierta', 6.81239, 0),
(188, 1, 'Thinner estandar 960ml', '2018-06-28 10:52:44', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000360', 1870, 127, 10.25, '49-2', 'Etiqueta abierta', 6.81239, 0),
(189, 1, 'Thinner estandar 960ml', '2018-06-28 10:52:44', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000360', 1870, 127, 10.25, '49-3', 'Etiqueta abierta', 6.81239, 0),
(190, 1, 'Thinner estandar 960ml', '2018-06-28 10:52:44', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000360', 0, 0, 0, '49-4', 'Etiqueta abierta', 0, 0),
(191, 1, 'Thinner estandar 960ml', '2018-06-28 11:13:08', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000361', NULL, NULL, NULL, '50', 'Etiqueta abierta', 0, 1),
(192, 1, 'Thinner estandar 960ml', '2018-06-28 11:13:08', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000361', 1700, 127, 9.32, '50-1', 'Etiqueta abierta', 6.19308, 0),
(193, 1, 'Thinner estandar 960ml', '2018-06-28 11:13:08', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000361', 1700, 127, 9.32, '50-2', 'Etiqueta abierta', 6.19308, 0),
(194, 1, 'Thinner estandar 960ml', '2018-06-28 11:13:08', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000361', 1700, 127, 9.32, '50-3', 'Etiqueta abierta', 6.19308, 0),
(195, 1, 'Thinner estandar 960ml', '2018-06-28 11:13:08', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000361', 0, 0, 0, '50-4', 'Etiqueta abierta', 0, 0),
(196, 1, 'Thinner estandar 960ml', '2018-06-28 11:22:54', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000362', NULL, NULL, NULL, '51', 'Etiqueta abierta', 0, 1),
(197, 1, 'Thinner estandar 960ml', '2018-06-28 11:22:54', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000362', 1950, 127, 10.88, '51-1', 'Etiqueta abierta', 7.10383, 0),
(198, 1, 'Thinner estandar 960ml', '2018-06-28 11:22:54', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000362', 1950, 127, 10.88, '51-2', 'Etiqueta abierta', 7.10383, 0),
(199, 1, 'Thinner estandar 960ml', '2018-06-28 11:22:54', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000362', 1950, 127, 10.88, '51-3', 'Etiqueta abierta', 7.10383, 0),
(200, 1, 'Thinner estandar 960ml', '2018-06-28 11:22:54', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000362', 0, 0, 0, '51-4', 'Etiqueta abierta', 0, 0),
(201, 1, 'Thinner estandar 960ml', '2018-06-28 11:33:58', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000363', NULL, NULL, NULL, '52', 'Etiqueta abierta', 0, 1),
(202, 1, 'Thinner estandar 960ml', '2018-06-28 11:33:58', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000363', 1950, 127, 10.8, '52-1', 'Etiqueta abierta', 7.10383, 0),
(203, 1, 'Thinner estandar 960ml', '2018-06-28 11:33:58', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000363', 1950, 127, 10.8, '52-2', 'Etiqueta abierta', 7.10383, 0),
(204, 1, 'Thinner estandar 960ml', '2018-06-28 11:33:58', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000363', 1800, 127, 9.97, '52-3', 'Etiqueta abierta', 6.55738, 0),
(205, 1, 'Thinner estandar 960ml', '2018-06-28 11:33:58', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000363', 0, 0, 0, '52-4', 'Etiqueta abierta', 0, 0),
(206, 1, 'Thinner estandar 960ml', '2018-06-28 11:46:18', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000364', NULL, NULL, NULL, '53', 'Etiqueta abierta', 0, 1),
(207, 1, 'Thinner estandar 960ml', '2018-06-28 11:46:18', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000364', 1850, 127, 10.15, '53-1', 'Etiqueta abierta', 6.73953, 0),
(208, 1, 'Thinner estandar 960ml', '2018-06-28 11:46:18', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000364', 1850, 127, 10.15, '53-2', 'Etiqueta abierta', 6.73953, 0),
(209, 1, 'Thinner estandar 960ml', '2018-06-28 11:46:18', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000364', 1850, 127, 10.15, '53-3', 'Etiqueta abierta', 6.73953, 0),
(210, 1, 'Thinner estandar 960ml', '2018-06-28 11:46:18', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000364', 0, 0, 0, '53-4', 'Etiqueta abierta', 0, 0),
(211, 1, 'Thinner estandar 960ml', '2018-06-28 12:00:07', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000365', NULL, NULL, NULL, '54', 'Etiqueta abierta', 0, 1),
(212, 1, 'Thinner estandar 960ml', '2018-06-28 12:00:07', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000365', 1950, 127, 10.8, '54-1', 'Etiqueta abierta', 7.10383, 0),
(213, 1, 'Thinner estandar 960ml', '2018-06-28 12:00:07', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000365', 1950, 127, 10.8, '54-2', 'Etiqueta abierta', 7.10383, 0),
(214, 1, 'Thinner estandar 960ml', '2018-06-28 12:00:07', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000365', 1950, 127, 10.8, '54-3', 'Etiqueta abierta', 7.10383, 0),
(215, 1, 'Thinner estandar 960ml', '2018-06-28 12:00:07', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000365', 0, 0, 0, '54-4', 'Etiqueta abierta', 0, 0),
(216, 1, 'Thinner estandar 960ml', '2018-06-28 12:21:38', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000366', NULL, NULL, NULL, '55', 'Etiqueta abierta', 0, 1),
(217, 1, 'Thinner estandar 960ml', '2018-06-28 12:21:38', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000366', 1800, 127, 9.87, '55-1', 'Etiqueta abierta', 6.55738, 0),
(218, 1, 'Thinner estandar 960ml', '2018-06-28 12:21:38', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000366', 1800, 127, 9.87, '55-2', 'Etiqueta abierta', 6.55738, 0),
(219, 1, 'Thinner estandar 960ml', '2018-06-28 12:21:38', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000366', 1750, 127, 9.87, '55-3', 'Etiqueta abierta', 6.37523, 0),
(220, 1, 'Thinner estandar 960ml', '2018-06-28 12:21:38', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000366', 0, 0, 0, '55-4', 'Etiqueta abierta', 0, 0),
(221, 1, 'Thinner estandar 960ml', '2018-06-28 12:31:01', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000368', NULL, NULL, NULL, '57', 'Etiqueta abierta', 0, 1),
(222, 1, 'Thinner estandar 960ml', '2018-06-28 12:31:01', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000368', 1900, 127, 10.88, '57-1', 'Etiqueta abierta', 6.92168, 0),
(223, 1, 'Thinner estandar 960ml', '2018-06-28 12:31:01', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000368', 1950, 127, 10.88, '57-2', 'Etiqueta abierta', 7.10383, 0),
(224, 1, 'Thinner estandar 960ml', '2018-06-28 12:31:01', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000368', 1950, 127, 10.88, '57-3', 'Etiqueta abierta', 7.10383, 0),
(225, 1, 'Thinner estandar 960ml', '2018-06-28 12:31:01', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000368', 0, 0, 0, '57-4', 'Etiqueta abierta', 0, 0),
(226, 1, 'Thinner estandar 960ml', '2018-06-28 12:44:48', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000369', NULL, NULL, NULL, '58', 'Etiqueta abierta', 0, 1),
(227, 1, 'Thinner estandar 960ml', '2018-06-28 12:44:48', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000369', 1760, 127, 9, '58-1', 'Etiqueta abierta', 6.41166, 0),
(228, 1, 'Thinner estandar 960ml', '2018-06-28 12:44:48', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000369', 1784, 127, 9.1, '58-2', 'Etiqueta abierta', 6.49909, 0),
(229, 1, 'Thinner estandar 960ml', '2018-06-28 12:44:48', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000369', 1800, 127, 9.6, '58-3', 'Etiqueta abierta', 6.55738, 0),
(230, 1, 'Thinner estandar 960ml', '2018-06-28 12:44:48', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000369', 0, 0, 0, '58-4', 'Etiqueta abierta', 0, 0),
(231, 1, 'Thinner estandar 960ml', '2018-06-28 13:01:14', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000370', NULL, NULL, NULL, '59', 'Etiqueta abierta', 0, 1),
(232, 1, 'Thinner estandar 960ml', '2018-06-28 13:01:14', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000370', 1800, 127, 10.15, '59-1', 'Etiqueta abierta', 6.55738, 0),
(233, 1, 'Thinner estandar 960ml', '2018-06-28 13:01:14', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000370', 1900, 127, 10.15, '59-2', 'Etiqueta abierta', 6.92168, 0),
(234, 0, 'Thinner estandar 960ml', '2018-06-28 13:01:14', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000370', 1850, 127, 10.15, '59-3', 'Etiqueta abierta', 6.73953, 0),
(235, 1, 'Thinner estandar 960ml', '2018-06-28 13:01:14', 'Luis Felipe Fuentes Medina |018', 'Refiladora 01', '00000370', 0, 0, 0, '59-4', 'Etiqueta abierta', 0, 0),
(236, 1, 'Thinner estandar 960ml', '2018-10-01 14:54:36', 'John Connor|011', 'Refiladora 01', '00000381', NULL, NULL, NULL, '71', 'Etiqueta abierta', 0, 1),
(237, 1, 'Thinner estandar 960ml', '2018-10-01 14:54:36', 'John Connor|011', 'Refiladora 01', '00000381', NULL, NULL, NULL, '71-1', 'Etiqueta abierta', 0, 0),
(238, 1, 'Thinner estandar 960ml', '2018-10-01 14:54:36', 'John Connor|011', 'Refiladora 01', '00000381', NULL, NULL, NULL, '71-2', 'Etiqueta abierta', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prorevision`
--

CREATE TABLE `prorevision` (
  `id` int(11) NOT NULL,
  `operador` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `maquina` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `rollo` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `total` float NOT NULL DEFAULT '1',
  `producto` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `longitud` float NOT NULL,
  `noop` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `unidades` float NOT NULL,
  `peso` float DEFAULT NULL,
  `bandera` int(11) DEFAULT NULL,
  `tipo` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `rollo_padre` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `prorevision`
--

INSERT INTO `prorevision` (`id`, `operador`, `maquina`, `rollo`, `total`, `producto`, `fecha`, `longitud`, `noop`, `unidades`, `peso`, `bandera`, `tipo`, `rollo_padre`) VALUES
(1, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000122', 0, 'Thinner estandar 960ml', '2018-06-13 10:33:57', 1750, '13', 19.0986, 17.3, 0, 'Etiqueta abierta', 0),
(2, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000124', 0, 'Thinner estandar 960ml', '2018-06-13 10:41:18', 1800, '15', 19.6443, 17.6, 1, 'Etiqueta abierta', 0),
(3, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000125', 0, 'Thinner estandar 960ml', '2018-06-13 10:44:35', 2870, '16', 31.3217, 28.9, 1, 'Etiqueta abierta', 0),
(4, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000126', 0, 'Thinner estandar 960ml', '2018-06-13 10:47:19', 1750, '17', 19.0986, 17.4, 0, 'Etiqueta abierta', 0),
(5, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000127', 0, 'Thinner estandar 960ml', '2018-06-13 10:57:59', 3250, '18', 35.4689, 32.76, 2, 'Etiqueta abierta', 0),
(6, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000128', 0, 'Thinner estandar 960ml', '2018-06-19 11:17:18', 1950, '19', 21.2813, 20.5, 0, 'Etiqueta abierta', 0),
(7, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000129', 0, 'Thinner estandar 960ml', '2018-06-13 11:04:07', 2650, '20', 28.9208, 23.97, 0, 'Etiqueta abierta', 0),
(8, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000131', 0, 'Thinner estandar 960ml', '2018-06-13 11:05:46', 1480, '22', 16.152, 14.6, 1, 'Etiqueta abierta', 0),
(9, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000132', 0, 'Thinner estandar 960ml', '2018-06-13 11:06:57', 1659, '23', 18.1055, 16.71, 0, 'Etiqueta abierta', 0),
(11, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000123', 0, 'Thinner estandar 960ml', '2018-06-13 12:23:45', 2420, '14', 26.4107, 24, 1, 'Etiqueta abierta', 0),
(13, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000143', 0, 'Thinner estandar 960ml', '2018-06-14 10:17:01', 2065, '34', 22.5364, 29.82, 0, 'Etiqueta abierta', 0),
(14, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000142', 0, 'Thinner estandar 960ml', '2018-06-15 12:51:07', 1800, '33', 19.6443, 27.06, 3, 'Etiqueta abierta', 0),
(15, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000140', 0, 'Thinner estandar 960ml', '2018-06-19 11:46:22', 1800, '31', 19.6443, 30, 0, 'Etiqueta abierta', 0),
(16, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000130', 0, 'Thinner estandar 960ml', '2018-06-25 12:12:11', 1800, '21', 19.6443, 18.14, 1, 'Etiqueta abierta', 0),
(17, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000133', 0, 'Thinner estandar 960ml', '2018-06-25 12:18:50', 1950, '24', 21.2813, 18.19, 0, 'Etiqueta abierta', 0),
(18, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000134', 0, 'Thinner estandar 960ml', '2018-06-25 12:37:17', 1950, '25', 21.2813, 28.16, 1, 'Etiqueta abierta', 0),
(19, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000139', 0, 'Thinner estandar 960ml', '2018-06-25 12:50:18', 1950, '30', 21.2813, 27.75, 1, 'Etiqueta abierta', 0),
(20, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000141', 0, 'Thinner estandar 960ml', '2018-06-25 12:59:23', 1900, '32', 20.7356, 27.86, 1, 'Etiqueta abierta', 0),
(21, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000135', 0, 'Thinner estandar 960ml', '2018-06-25 13:18:39', 1950, '26', 21.2813, 9.25, 0, 'Etiqueta abierta', 0),
(22, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000136', 0, 'Thinner estandar 960ml', '2018-06-25 13:26:45', 1600, '27', 17.4616, 23, 0, 'Etiqueta abierta', 0),
(23, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000137', 0, 'Thinner estandar 960ml', '2018-06-25 14:04:47', 1600, '28', 17.4616, 23, 0, 'Etiqueta abierta', 0),
(24, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000138', 0, 'Thinner estandar 960ml', '2018-06-25 14:10:02', 1950, '29', 21.2813, 28, 1, 'Etiqueta abierta', 0),
(25, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000167', 0, 'Thinner estandar 960ml', '2018-06-27 13:01:44', 1850, '56', 20.19, 26.5, 0, 'Etiqueta abierta', 0),
(26, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000144', 0, 'Thinner estandar 960ml', '2018-06-27 17:15:47', 1850, '35', 20.19, 26.5, 2, 'Etiqueta abierta', 0),
(27, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000147', 0, 'Thinner estandar 960ml', '2018-06-27 17:32:32', 1850, '36', 20.19, 26.5, 0, 'Etiqueta abierta', 0),
(28, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000148', 0, 'Thinner estandar 960ml', '2018-06-28 07:53:10', 1850, '37', 20.19, 26.5, 0, 'Etiqueta abierta', 0),
(29, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000149', 0, 'Thinner estandar 960ml', '2018-06-28 08:12:19', 1850, '38', 20.19, 26.5, 0, 'Etiqueta abierta', 0),
(30, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000150', 0, 'Thinner estandar 960ml', '2018-06-28 08:28:46', 1850, '39', 20.19, 26.5, 0, 'Etiqueta abierta', 0),
(31, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000151', 0, 'Thinner estandar 960ml', '2018-06-28 08:36:45', 1850, '40', 20.19, 26.5, 0, 'Etiqueta abierta', 0),
(32, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000152', 0, 'Thinner estandar 960ml', '2018-06-28 08:47:17', 1850, '41', 20.19, 26.5, 2, 'Etiqueta abierta', 0),
(33, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000153', 0, 'Thinner estandar 960ml', '2018-06-28 08:59:46', 1850, '42', 20.19, 30.99, 3, 'Etiqueta abierta', 0),
(34, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000154', 0, 'Thinner estandar 960ml', '2018-06-28 09:46:43', 1850, '43', 20.19, 26.5, 0, 'Etiqueta abierta', 0),
(35, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000155', 0, 'Thinner estandar 960ml', '2018-06-28 09:55:10', 1850, '44', 20.19, 26.5, 0, 'Etiqueta abierta', 0),
(36, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000156', 0, 'Thinner estandar 960ml', '2018-06-28 10:09:58', 1850, '45', 20.19, 26.5, 0, 'Etiqueta abierta', 0),
(37, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000157', 0, 'Thinner estandar 960ml', '2018-06-28 10:20:24', 1850, '46', 20.19, 26.5, 3, 'Etiqueta abierta', 0),
(38, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000158', 0, 'Thinner estandar 960ml', '2018-06-28 10:27:26', 1850, '47', 20.19, 26.9, 3, 'Etiqueta abierta', 0),
(39, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000159', 0, 'Thinner estandar 960ml', '2018-06-28 10:41:28', 1850, '48', 20.19, 26.5, 0, 'Etiqueta abierta', 0),
(40, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000160', 0, 'Thinner estandar 960ml', '2018-06-28 10:49:11', 1850, '49', 20.19, 26.5, 2, 'Etiqueta abierta', 0),
(41, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000161', 0, 'Thinner estandar 960ml', '2018-06-28 11:09:52', 1850, '50', 20.19, 26.5, 0, 'Etiqueta abierta', 0),
(42, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000162', 0, 'Thinner estandar 960ml', '2018-06-28 11:19:56', 1850, '51', 20.19, 26.5, 2, 'Etiqueta abierta', 0),
(43, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000163', 0, 'Thinner estandar 960ml', '2018-06-28 11:29:07', 1850, '52', 20.19, 26.5, 2, 'Etiqueta abierta', 0),
(44, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000164', 0, 'Thinner estandar 960ml', '2018-06-28 11:43:34', 1850, '53', 20.19, 26.5, 2, 'Etiqueta abierta', 0),
(45, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000165', 0, 'Thinner estandar 960ml', '2018-06-28 11:55:33', 1850, '54', 20.19, 26.5, 1, 'Etiqueta abierta', 0),
(46, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000166', 0, 'Thinner estandar 960ml', '2018-06-28 12:10:21', 1850, '55', 20.19, 26.5, 2, 'Etiqueta abierta', 0),
(47, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000168', 0, 'Thinner estandar 960ml', '2018-06-28 12:26:58', 1850, '57', 20.19, 26.5, 2, 'Etiqueta abierta', 0),
(48, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000169', 0, 'Thinner estandar 960ml', '2018-06-28 12:34:50', 1850, '58', 20.19, 26.5, 3, 'Etiqueta abierta', 0),
(49, 'Luis Felipe Fuentes Medina |018', 'MÃ¡quina revisadora 01', '00000170', 0, 'Thinner estandar 960ml', '2018-06-28 12:58:24', 1850, '59', 20.19, 26.5, 2, 'Etiqueta abierta', 0),
(50, 'John Connor|011', 'MÃ¡quina revisadora 01', '00000181', 0, 'Thinner estandar 960ml', '2018-10-01 13:50:06', 1710, '71', 18.6621, 25, 1, 'Etiqueta abierta', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prorevision 2`
--

CREATE TABLE `prorevision 2` (
  `id` int(11) NOT NULL,
  `operador` varchar(70) NOT NULL,
  `maquina` varchar(70) NOT NULL,
  `rollo` varchar(70) NOT NULL,
  `total` int(11) NOT NULL DEFAULT '1',
  `producto` varchar(70) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `longitud` float NOT NULL,
  `noop` varchar(30) NOT NULL,
  `unidades` float NOT NULL,
  `peso` float NOT NULL,
  `bandera` int(11) NOT NULL,
  `tipo` varchar(30) NOT NULL,
  `rollo_padre` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prosliteo`
--

CREATE TABLE `prosliteo` (
  `id` int(11) NOT NULL,
  `total` float NOT NULL DEFAULT '1',
  `producto` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `noop` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `unidades` float NOT NULL,
  `operador` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `maquina` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lote` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitud` float DEFAULT NULL,
  `amplitud` float DEFAULT NULL,
  `peso` float DEFAULT NULL,
  `bandera` int(11) DEFAULT NULL,
  `tipo` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `rollo_padre` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prosuajado`
--

CREATE TABLE `prosuajado` (
  `id` int(11) NOT NULL,
  `total` int(11) DEFAULT '1',
  `producto` varchar(70) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `operador` varchar(50) NOT NULL,
  `maquina` varchar(50) NOT NULL,
  `rollo` varchar(50) NOT NULL,
  `suaje` varchar(30) NOT NULL,
  `longitud` float NOT NULL,
  `peso` float NOT NULL,
  `unidades` float NOT NULL,
  `tipo` varchar(30) NOT NULL,
  `noop` varchar(30) NOT NULL,
  `rollo_padre` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `protroquelado`
--

CREATE TABLE `protroquelado` (
  `id` int(11) NOT NULL,
  `total` int(11) NOT NULL DEFAULT '1',
  `producto` varchar(70) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `operador` varchar(50) NOT NULL,
  `maquina` varchar(50) NOT NULL,
  `rollo` varchar(50) NOT NULL,
  `unidades` float NOT NULL,
  `peso` float NOT NULL,
  `longitud` float NOT NULL,
  `noop` varchar(30) NOT NULL,
  `tipo` varchar(30) NOT NULL,
  `suaje` varchar(30) NOT NULL,
  `rollo_padre` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pruebas`
--

CREATE TABLE `pruebas` (
  `id` int(11) NOT NULL,
  `dato` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `entero` int(11) NOT NULL,
  `contrasenia` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte`
--

CREATE TABLE `reporte` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `accion` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `modulo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `departamento` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `reporte`
--

INSERT INTO `reporte` (`id`, `nombre`, `accion`, `modulo`, `departamento`, `registro`) VALUES
(1, 'liraTI', 'Agrego al empleado Num:  287', 'Personal', 'Recursos Humanos', '2018-01-17 10:22:24'),
(2, 'liraTI', 'Agrego al empleado Num:  069', 'Personal', 'Recursos Humanos', '2018-01-17 10:23:05'),
(3, 'castillo66', 'Agrego el diseÃƒÆ’Ã‚Â±o:  Electropura', 'DiseÃƒÆ’Ã‚Â±o', 'Productos', '2018-01-17 12:22:34'),
(4, 'castillo66', 'Agrego el diseÃƒÆ’Ã‚Â±o:  Epura', 'DiseÃƒÆ’Ã‚Â±o', 'Productos', '2018-01-17 12:23:10'),
(5, 'castillo66', 'Agrego la Banda de Seguridad:  BS electroPura', 'Banda de Seguridad', 'Productos', '2018-01-17 12:48:40'),
(6, 'castillo66', 'Agrego la Banda de Seguridad:  Electropura Pre-Embozado640', 'Banda de Seguridad Por Proceso', 'Productos', '2018-01-17 12:49:27'),
(7, 'castillo66', 'Actualizo la Banda de Seguridad: Electropura Pre-Embozado640', 'Banda de Seguridad Por Proceso', 'Productos', '2018-01-17 12:51:12'),
(8, 'castillo66', 'Agrego la Banda de Seguridad:  BS ePura', 'Banda de Seguridad', 'Productos', '2018-01-17 13:15:37'),
(9, 'castillo66', 'Agrego la Banda de Seguridad:  Epura Pre-Embozado640', 'Banda de Seguridad Por Proceso', 'Productos', '2018-01-17 13:21:30'),
(10, 'liraTI', 'Agrego el diseÃƒÆ’Ã‚Â±o:  pruebitashidita', 'DiseÃƒÆ’Ã‚Â±o', 'Productos', '2018-01-17 13:21:57'),
(11, 'castillo66', 'Agrego la Banda de Seguridad:  Epura Pre-Embozado320', 'Banda de Seguridad Por Proceso', 'Productos', '2018-01-17 13:21:57'),
(12, 'castillo66', 'Agrego el juego de cilindro:  W7110503', 'Juegos Cilindro', 'Productos', '2018-01-17 13:35:33'),
(13, 'castillo66', 'Agrego al empleado Num:  34', 'Personal', 'Recursos Humanos', '2018-01-17 13:53:14'),
(14, 'jnYei', 'Agrego la mÃƒÂ¡quina:  MTIM03', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-01-17 13:57:05'),
(15, 'castillo66', 'Agrego la mÃƒÂ¡quina:  MTRF01', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-01-18 08:57:45'),
(16, 'castillo66', 'Agrego la mÃƒÂ¡quina:  MTFU01', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-01-18 08:59:09'),
(17, 'castillo66', 'Agrego la mÃƒÂ¡quina:  MTRE01', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-01-18 08:59:33'),
(18, 'castillo66', 'Agrego la mÃƒÂ¡quina:  MTCO01', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-01-18 08:59:57'),
(19, 'castillo66', 'Agrego la mÃƒÂ¡quina:  MTLA01', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-01-18 09:00:38'),
(20, 'castillo66', 'Agrego la mÃƒÂ¡quina:  MTSL01', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-01-18 09:01:01'),
(21, 'castillo66', 'Agrego al empleado Num:  002', 'Personal', 'Recursos Humanos', '2018-01-19 08:34:13'),
(22, 'castillo66', 'Agrego el diseÃƒÆ’Ã‚Â±o:  Cristal', 'DiseÃƒÆ’Ã‚Â±o', 'Productos', '2018-01-19 08:43:15'),
(23, 'castillo66', 'Agrego la Banda de Seguridad:  BS Cristal Gota', 'Banda de Seguridad', 'Productos', '2018-01-19 08:45:52'),
(24, 'castillo66', 'Agrego la Banda de Seguridad:  Gota Pre-Embozado320', 'Banda de Seguridad Por Proceso', 'Productos', '2018-01-19 08:49:13'),
(25, 'castillo66', 'Agrego el juego de cilindro:  Z24377', 'Juegos Cilindro', 'Productos', '2018-01-19 08:57:21'),
(26, 'liraTI', 'Agrego al empleado Num:  12', 'Personal', 'Recursos Humanos', '2018-01-19 09:34:06'),
(27, 'liraTI', 'Agrego al cliente con RFC:  BBE90827ALA', 'Clientes', 'Logistica', '2018-01-19 11:16:53'),
(28, 'liraTI', 'Agrego al contacto:  DANIEL RODRIGUEZ REYNA', 'Contacto Cliente', 'Logistica', '2018-01-19 11:17:39'),
(29, 'liraTI', 'Agrego a la sucursal:  Planta Villahermosa Bepensa', 'Sucursal', 'Logistica', '2018-01-19 11:18:36'),
(30, 'liraTI', 'Agrego a la sucursal:  Planta Merida Bepensa', 'Sucursal', 'Logistica', '2018-01-19 11:28:40'),
(31, 'liraTI', 'Agrego la orden:  368368', 'Orden Compra', 'Logistica', '2018-01-19 11:29:09'),
(32, 'liraTI', 'Agrego la orden:  369125', 'Orden Compra', 'Logistica', '2018-01-19 11:29:30'),
(33, 'liraTI', 'Agrego un requerimiento a la orden de compra:  369125', 'Requerimiento Producto', 'Logistica', '2018-01-19 11:31:27'),
(34, 'liraTI', 'Agrego un requerimiento a la orden de compra:  368368', 'Requerimiento Producto', 'Logistica', '2018-01-19 11:31:45'),
(35, 'liraTI', 'Actualizo la orden: 369125', 'Orden Compra', 'Logistica', '2018-01-19 11:32:10'),
(36, 'liraTI', 'Agrego un requerimiento a la orden de compra:  369125', 'Requerimiento Producto', 'Logistica', '2018-01-19 11:40:03'),
(37, 'castillo66', 'Desactivo el diseÃƒÆ’Ã‚Â±o: pruebitashidita', 'DiseÃƒÆ’Ã‚Â±o', 'Productos', '2018-01-24 12:37:06'),
(38, 'jnYei', 'Desactivo al empleado Num: 12', 'Personal', 'Recursos Humanos', '2018-01-29 08:18:50'),
(39, 'liraTI', 'Agrego el diseÃƒÆ’Ã‚Â±o:  PruebaS', 'DiseÃƒÆ’Ã‚Â±o', 'Productos', '2018-01-30 09:04:55'),
(40, 'liraTI', 'Actualizo al empleado Num: 287', 'Personal', 'Recursos Humanos', '2018-01-31 09:21:46'),
(41, 'liraTI', 'Desactivo un requerimiento con la orden de compra: 369125', 'Requerimiento Producto', 'Logistica', '2018-01-31 09:32:52'),
(42, 'liraTI', 'Agrego un requerimiento a la orden de compra:  369125', 'Requerimiento Producto', 'Logistica', '2018-01-31 09:33:47'),
(43, 'liraTI', 'Activo al empleado Num: 12', 'Personal', 'Recursos Humanos', '2018-01-31 10:07:08'),
(44, 'liraTI', 'Activo al empleado Num: 12', 'Personal', 'Recursos Humanos', '2018-01-31 10:07:12'),
(45, 'liraTI', 'Desactivo al empleado Num: 12', 'Personal', 'Recursos Humanos', '2018-01-31 10:07:41'),
(46, 'liraTI', 'Activo al empleado Num: 12', 'Personal', 'Recursos Humanos', '2018-01-31 10:07:54'),
(47, 'liraTI', 'Activo al empleado Num: 12', 'Personal', 'Recursos Humanos', '2018-01-31 10:08:27'),
(48, 'liraTI', 'Desactivo al empleado Num: 12', 'Personal', 'Recursos Humanos', '2018-01-31 10:08:32'),
(49, 'liraTI', 'Desactivo al empleado Num: 12', 'Personal', 'Recursos Humanos', '2018-01-31 10:08:36'),
(50, 'liraTI', 'Activo al empleado Num: 12', 'Personal', 'Recursos Humanos', '2018-01-31 10:08:51'),
(51, 'liraTI', 'Activo al empleado Num: 12', 'Personal', 'Recursos Humanos', '2018-01-31 10:08:56'),
(52, 'liraTI', 'Actualizo al empleado Num: 12', 'Personal', 'Recursos Humanos', '2018-01-31 11:20:04'),
(53, 'liraTI', 'Actualizo al empleado Num: 12', 'Personal', 'Recursos Humanos', '2018-01-31 11:22:10'),
(54, 'liraTI', 'Actualizo al empleado Num: 12', 'Personal', 'Recursos Humanos', '2018-01-31 11:22:22'),
(55, 'liraTI', 'Agrego al empleado Num:  10018', 'Personal', 'Recursos Humanos', '2018-01-31 11:29:35'),
(56, 'castillo66', 'Desactivo el diseÃƒÂ±o: PruebaS', 'DiseÃƒÂ±o', 'Productos', '2018-01-31 12:04:45'),
(57, 'liraTI', 'Desactivo al cliente con RFC: BBE90827ALA', 'Clientes', 'Logistica', '2018-01-31 12:10:22'),
(58, 'liraTI', 'Activo al cliente con RFC: BBE90827ALA', 'Clientes', 'Logistica', '2018-01-31 12:10:27'),
(59, 'liraTI', 'Agrego la mÃƒÂ¡quina:  PTB', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-02-02 11:48:36'),
(60, 'liraTI', 'Desactivo la mÃƒÂ¡quina: PTB', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-02-02 11:48:41'),
(61, 'liraTI', 'Agrego la mÃƒÂ¡quina:  FOLIOPRUEBA', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-02-02 11:55:00'),
(62, 'liraTI', 'Agrego la mÃƒÂ¡quina:  FOLIOPRUEBA', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-02-02 11:55:44'),
(63, 'liraTI', 'Actualizo la mÃƒÂ¡quina: FOLIOPRUEBA', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-02-02 11:56:03'),
(64, 'liraTI', 'Desactivo la mÃƒÂ¡quina: FOLIOPRU', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-02-02 11:56:07'),
(65, 'liraTI', 'Elimino la mÃƒÂ¡quina: FOLIOPRU', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-02-02 11:58:33'),
(66, 'liraTI', 'Activo la mÃƒÂ¡quina: PTB', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-02-02 11:58:40'),
(67, 'liraTI', 'Desactivo la mÃƒÂ¡quina: PTB', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-02-02 11:58:43'),
(68, 'liraTI', 'Elimino la mÃƒÂ¡quina: PTB', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-02-02 11:58:50'),
(69, 'jnYei', 'Actualizo el juego de cilindro: W7110503', 'Juegos Cilindro', 'Productos', '2018-02-02 12:56:31'),
(70, 'jnYei', 'Actualizo el juego de cilindro: W7110503', 'Juegos Cilindro', 'Productos', '2018-02-02 12:57:04'),
(71, 'jnYei', 'Actualizo el juego de cilindro: W7110503', 'Juegos Cilindro', 'Productos', '2018-02-02 13:04:08'),
(72, 'jnYei', 'Actualizo el juego de cilindro: W7110503', 'Juegos Cilindro', 'Productos', '2018-02-02 13:04:34'),
(73, 'jnYei', 'Actualizo el juego de cilindro: W7110503', 'Juegos Cilindro', 'Productos', '2018-02-02 13:07:17'),
(74, 'jnYei', 'Actualizo el juego de cilindro: W7110503', 'Juegos Cilindro', 'Productos', '2018-02-02 13:07:27'),
(75, 'jnYei', 'Actualizo el juego de cilindro: W7110503', 'Juegos Cilindro', 'Productos', '2018-02-02 13:10:06'),
(76, 'jnYei', 'Actualizo el juego de cilindro: W7110503', 'Juegos Cilindro', 'Productos', '2018-02-02 13:10:56'),
(77, 'jnYei', 'Actualizo el juego de cilindro: W7110503', 'Juegos Cilindro', 'Productos', '2018-02-02 13:14:19'),
(78, 'liraTI', 'Agrego un requerimiento a la orden de compra:  368368', 'Requerimiento Producto', 'Logistica', '2018-02-02 13:18:20'),
(79, 'liraTI', 'Agrego el embarque:  ', 'Embarque', 'Logistica', '2018-02-02 13:20:13'),
(80, 'liraTI', 'Desactivo un requerimiento con la orden de compra: 368368', 'Requerimiento Producto', 'Logistica', '2018-02-02 13:31:10'),
(81, 'liraTI', 'Desactivo al cliente con RFC: BBE90827ALA', 'Clientes', 'Logistica', '2018-02-06 10:17:33'),
(82, 'liraTI', 'Activo al cliente con RFC: BBE90827ALA', 'Clientes', 'Logistica', '2018-02-06 10:18:06'),
(83, 'liraTI', 'Agrego un requerimiento a la orden de compra:  368368', 'Requerimiento Producto', 'Logistica', '2018-02-07 09:04:18'),
(84, 'liraTI', 'Actualizo al cliente con RFC: BBE90827ALA', 'Clientes', 'Logistica', '2018-02-08 08:46:35'),
(85, 'liraTI', 'Actualizo a la sucursal: Planta Merida Bepensa', 'Sucursal', 'Logistica', '2018-02-08 10:19:24'),
(86, 'liraTI', 'Actualizo a la sucursal: Planta Villahermosa Bepensa', 'Sucursal', 'Logistica', '2018-02-08 10:20:02'),
(87, 'liraTI', 'Actualizo a la sucursal: Planta Villahermosa Bepensa', 'Sucursal', 'Logistica', '2018-02-08 10:21:02'),
(88, 'liraTI', 'Desactivo al empleado Num: 12', 'Personal', 'Recursos Humanos', '2018-02-08 10:54:15'),
(89, 'liraTI', 'Actualizo al empleado Num: 10018', 'Personal', 'Recursos Humanos', '2018-02-08 10:54:41'),
(90, 'liraTI', 'Desactivo al empleado Num: 10018', 'Personal', 'Recursos Humanos', '2018-02-08 10:54:45'),
(91, 'liraTI', 'Elimino al empleado Num: 12', 'Personal', 'Recursos Humanos', '2018-02-08 10:54:58'),
(92, 'liraTI', 'Elimino al empleado Num: ', 'Personal', 'Recursos Humanos', '2018-02-08 10:55:01'),
(93, 'liraTI', 'Activo el diseÃƒÂ±o: pruebitashidita', 'DiseÃƒÂ±o', 'Productos', '2018-02-08 11:45:39'),
(94, 'liraTI', 'Actualizo el diseÃƒÂ±o: pruebitashidita', 'DiseÃƒÂ±o', 'Productos', '2018-02-08 11:46:13'),
(95, 'liraTI', 'Desactivo el diseÃƒÂ±o: PruebaBaja', 'DiseÃƒÂ±o', 'Productos', '2018-02-08 11:47:35'),
(96, 'castillo66', 'Agrego el diseÃƒÂ±o:  Santorini', 'DiseÃƒÂ±o', 'Productos', '2018-02-08 13:37:58'),
(97, 'castillo66', 'Agrego la Banda de Seguridad:  BS Santorini', 'Banda de Seguridad', 'Productos', '2018-02-08 13:46:31'),
(98, 'castillo66', 'Agrego la Banda de Seguridad:  Santorini Pre- Embozado320', 'Banda de Seguridad Por Proceso', 'Productos', '2018-02-08 13:49:13'),
(99, 'castillo66', 'Actualizo la Banda de Seguridad: Santorini Pre- Embozado320', 'Banda de Seguridad Por Proceso', 'Productos', '2018-02-08 13:49:25'),
(100, 'castillo66', 'Actualizo la Banda de Seguridad: Santorini Pre- Embozado320', 'Banda de Seguridad Por Proceso', 'Productos', '2018-02-08 13:49:31'),
(101, 'castillo66', 'Agrego el juego de cilindro:  W7110505', 'Juegos Cilindro', 'Productos', '2018-02-08 13:57:14'),
(102, 'liraTI', 'Agrego el embarque:  20180206001', 'Embarque', 'Logistica', '2018-02-09 12:13:40'),
(103, 'liraTI', 'Agrego el embarque:  20180207001', 'Embarque', 'Logistica', '2018-02-09 12:26:59'),
(104, 'liraTI', 'Agrego el diseÃƒÂ±o:  Adwi', 'DiseÃƒÂ±o', 'Productos', '2018-02-12 10:26:08'),
(105, 'liraTI', 'Agrego el diseÃƒÂ±o:  Pruebita', 'DiseÃƒÂ±o', 'Productos', '2018-02-12 10:51:17'),
(106, 'liraTI', 'Agrego el diseÃƒÂ±o:  manguito', 'DiseÃƒÂ±o', 'Productos', '2018-02-12 11:27:26'),
(107, 'liraTI', 'Agrego el diseÃƒÂ±o:  chileylimon', 'DiseÃƒÂ±o', 'Productos', '2018-02-12 11:28:40'),
(108, 'liraTI', 'Agrego el diseÃƒÂ±o:  epepepepe', 'DiseÃƒÂ±o', 'Productos', '2018-02-12 11:29:39'),
(109, 'castillo66', 'Agrego el diseÃƒÂ±o:  Dorielotes con ksito', 'DiseÃƒÂ±o', 'Productos', '2018-02-12 12:56:50'),
(110, 'castillo66', 'Agrego el diseÃƒÂ±o:  k-sito', 'DiseÃƒÂ±o', 'Productos', '2018-02-12 12:58:17'),
(111, 'castillo66', 'Desactivo el diseÃƒÂ±o: k-sito', 'DiseÃƒÂ±o', 'Productos', '2018-02-12 13:02:16'),
(112, 'castillo66', 'Elimino el diseÃƒÂ±o: k-sito', 'DiseÃƒÂ±o', 'Productos', '2018-02-12 13:02:24'),
(113, 'castillo66', 'Agrego el diseÃƒÂ±o:  k-esito', 'DiseÃƒÂ±o', 'Productos', '2018-02-12 13:02:37'),
(114, 'castillo66', 'Agrego el diseÃƒÂ±o:  k-esito', 'DiseÃƒÂ±o', 'Productos', '2018-02-12 13:03:00'),
(115, 'castillo66', 'Agrego el diseÃƒÂ±o:  k-sito', 'DiseÃƒÂ±o', 'Productos', '2018-02-12 13:04:19'),
(116, 'liraTI', 'Agrego el diseÃƒÂ±o:  Pruebs1', 'DiseÃƒÂ±o', 'Productos', '2018-02-13 08:31:37'),
(117, 'liraTI', 'Desactivo el embarque: 20180202001', 'Embarque', 'Logistica', '2018-02-13 12:12:45'),
(118, 'liraTI', 'Activo el embarque: 20180207001', 'Embarque', 'Logistica', '2018-02-13 12:22:16'),
(119, 'liraTI', 'Activo el embarque: 20180206001', 'Embarque', 'Logistica', '2018-02-13 12:22:26'),
(120, 'liraTI', 'Activo el embarque: 20180202001', 'Embarque', 'Logistica', '2018-02-13 12:22:29'),
(121, 'castillo66', 'Agrego la mÃƒÂ¡quina:  MTFL01', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-02-15 08:44:50'),
(122, 'castillo66', 'Agrego la mÃƒÂ¡quina:  MTFL01', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-02-15 08:46:31'),
(123, 'castillo66', 'Agrego la mÃƒÂ¡quina:  MTFL01', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-02-15 08:46:35'),
(124, 'castillo66', 'Desactivo la mÃƒÂ¡quina: MTFL01', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-02-15 08:46:52'),
(125, 'castillo66', 'Elimino la mÃƒÂ¡quina: MTFL01', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-02-15 08:46:57'),
(126, 'castillo66', 'Agrego la mÃƒÂ¡quina:  fds2452', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-02-15 08:47:06'),
(127, 'castillo66', 'Desactivo la mÃƒÂ¡quina: fds2452', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-02-15 08:47:18'),
(128, 'castillo66', 'Agrego la mÃƒÂ¡quina:  3673', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-02-15 08:48:35'),
(129, 'castillo66', 'Desactivo la mÃƒÂ¡quina: 3673', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-02-15 08:48:41'),
(130, 'castillo66', 'Elimino la mÃƒÂ¡quina: 3673', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-02-15 08:48:48'),
(131, 'castillo66', 'Actualizo el diseÃƒÂ±o: Adwi', 'DiseÃƒÂ±o', 'Productos', '2018-02-16 12:57:43'),
(132, 'castillo66', 'Agrego el diseÃƒÂ±o:  Knotted Bone', 'DiseÃƒÂ±o', 'Productos', '2018-02-21 09:13:48'),
(133, 'liraTI', 'Actualizo al empleado Num: 10018', 'Personal', 'Recursos Humanos', '2018-02-21 09:38:20'),
(134, 'liraTI', 'Desactivo al empleado Num: 10018', 'Personal', 'Recursos Humanos', '2018-02-21 09:38:46'),
(135, 'liraTI', 'Activo al empleado Num: 10018', 'Personal', 'Recursos Humanos', '2018-02-21 09:38:52'),
(136, 'liraTI', 'Desactivo al empleado Num: 10018', 'Personal', 'Recursos Humanos', '2018-02-21 09:38:54'),
(137, 'liraTI', 'Elimino al empleado Num: 10018', 'Personal', 'Recursos Humanos', '2018-02-21 09:39:10'),
(138, 'liraTI', 'Agrego al empleado Num:  2018', 'Personal', 'Recursos Humanos', '2018-02-21 09:39:56'),
(139, 'liraTI', 'Elimino al empleado Num: ', 'Personal', 'Recursos Humanos', '2018-02-21 09:39:56'),
(140, 'liraTI', 'Agrego al empleado Num:  19191', 'Personal', 'Recursos Humanos', '2018-02-21 09:41:06'),
(141, 'liraTI', 'Agrego el departamento:  Pruebita', 'Departamento', 'Recursos Humanos', '2018-02-21 09:53:29'),
(142, 'liraTI', 'Elimino el departamento: Pruebita', 'Departamento', 'Recursos Humanos', '2018-02-21 09:53:42'),
(143, 'liraTI', 'Agrego al cliente con RFC:  999', 'Clientes', 'Logistica', '2018-02-21 09:56:30'),
(144, 'liraTI', 'Actualizo al cliente con RFC: 999', 'Clientes', 'Logistica', '2018-02-21 10:09:34'),
(145, 'liraTI', 'Desactivo al cliente con RFC: 999', 'Clientes', 'Logistica', '2018-02-21 10:09:46'),
(146, 'liraTI', 'Activo al cliente con RFC: 999', 'Clientes', 'Logistica', '2018-02-21 10:09:50'),
(147, 'liraTI', 'Desactivo al cliente con RFC: 999', 'Clientes', 'Logistica', '2018-02-21 10:09:56'),
(148, 'liraTI', 'Elimino al cliente con RFC: 999', 'Clientes', 'Logistica', '2018-02-21 10:10:07'),
(149, 'liraTI', 'Agrego al contacto:  salero', 'Contacto Cliente', 'Logistica', '2018-02-21 10:15:54'),
(150, 'liraTI', 'Actualizo al contacto: salero', 'Contacto Cliente', 'Logistica', '2018-02-21 10:16:23'),
(151, 'liraTI', 'Actualizo al contacto: salerazo', 'Contacto Cliente', 'Logistica', '2018-02-21 10:17:41'),
(152, 'liraTI', 'Desactivo al contacto: salero', 'Contacto Cliente', 'Logistica', '2018-02-21 10:21:31'),
(153, 'liraTI', 'Elimino al contacto: salero', 'Contacto Cliente', 'Logistica', '2018-02-21 10:21:39'),
(154, 'liraTI', 'Agrego al contacto:  s,sa', 'Contacto Sucursal', 'Logistica', '2018-02-21 10:27:27'),
(155, 'liraTI', 'Actualizo al contacto: s,sa', 'Contacto Sucursal', 'Logistica', '2018-02-21 10:28:33'),
(156, 'liraTI', 'Agrego al contacto:  kcskjc', 'Contacto Sucursal', 'Logistica', '2018-02-21 10:29:11'),
(157, 'liraTI', 'Actualizo al contacto: kcskjc', 'Contacto Sucursal', 'Logistica', '2018-02-21 10:29:28'),
(158, 'liraTI', 'Actualizo al contacto: s,sa', 'Contacto Sucursal', 'Logistica', '2018-02-21 10:29:44'),
(159, 'liraTI', 'Actualizo al contacto: kcskjc', 'Contacto Sucursal', 'Logistica', '2018-02-21 10:31:29'),
(160, 'liraTI', 'Desactivo al contacto: kcskjc', 'Contacto Sucursal', 'Logistica', '2018-02-21 10:31:35'),
(161, 'liraTI', 'Desactivo al contacto: s,sa', 'Contacto Sucursal', 'Logistica', '2018-02-21 10:34:01'),
(162, 'liraTI', 'Elimino al contacto: kcskjc', 'Contacto Sucursal', 'Logistica', '2018-02-21 10:34:07'),
(163, 'liraTI', 'Activo al contacto: s,sa', 'Contacto Sucursal', 'Logistica', '2018-02-21 10:34:12'),
(164, 'liraTI', 'Desactivo al contacto: s,sa', 'Contacto Sucursal', 'Logistica', '2018-02-21 10:34:31'),
(165, 'liraTI', 'Desactivo al contacto: s,sa', 'Contacto Sucursal', 'Logistica', '2018-02-21 10:34:34'),
(166, 'liraTI', 'Elimino al contacto: s,sa', 'Contacto Sucursal', 'Logistica', '2018-02-21 10:34:50'),
(167, 'liraTI', 'Agrego a la sucursal:  licores german', 'Sucursal', 'Logistica', '2018-02-21 10:36:03'),
(168, 'liraTI', 'Actualizo a la sucursal: licores german', 'Sucursal', 'Logistica', '2018-02-21 10:37:25'),
(169, 'liraTI', 'Desactivo a la sucursal: licores german', 'Sucursal', 'Logistica', '2018-02-21 10:37:33'),
(170, 'liraTI', 'Activo a la sucursal: licores german', 'Sucursal', 'Logistica', '2018-02-21 10:37:38'),
(171, 'liraTI', 'Desactivo a la sucursal: licores german', 'Sucursal', 'Logistica', '2018-02-21 10:37:45'),
(172, 'liraTI', 'Elimino a la sucursal: licores german', 'Sucursal', 'Logistica', '2018-02-21 10:37:50'),
(173, 'liraTI', 'Agrego la orden:  37450', 'Orden Compra', 'Logistica', '2018-02-21 10:41:33'),
(174, 'liraTI', 'Desactivo la orden: 37450', 'Orden Compra', 'Logistica', '2018-02-21 10:41:42'),
(175, 'liraTI', 'Activo la orden: 37450', 'Orden Compra', 'Logistica', '2018-02-21 13:02:32'),
(176, 'liraTI', 'Actualizo la orden: 37450', 'Orden Compra', 'Logistica', '2018-02-21 13:02:47'),
(177, 'liraTI', 'Desactivo la orden: 37450', 'Orden Compra', 'Logistica', '2018-02-21 13:04:55'),
(178, 'liraTI', 'Elimino la orden: 37450', 'Orden Compra', 'Logistica', '2018-02-21 13:05:01'),
(179, 'liraTI', 'Agrego un requerimiento a la orden de compra:  369125', 'Requerimiento Producto', 'Logistica', '2018-02-21 13:07:36'),
(180, 'liraTI', 'Actualizo un requerimiento con la orden de compra: 369125', 'Requerimiento Producto', 'Logistica', '2018-02-21 13:07:58'),
(181, 'liraTI', 'Desactivo un requerimiento con la orden de compra: 369125', 'Requerimiento Producto', 'Logistica', '2018-02-21 13:08:14'),
(182, 'liraTI', 'Elimino una confirmaciÃƒÂ³n con la orden de compra: 369125', 'Requerimiento Producto', 'Logistica', '2018-02-21 13:25:34'),
(183, 'liraTI', 'Activo una confirmaciÃƒÂ³n con la orden de compra: 369125', 'Requerimiento Producto', 'Logistica', '2018-02-21 13:27:58'),
(184, 'liraTI', 'Desactivo una confirmaciÃƒÂ³n con la orden de compra: 369125', 'Requerimiento Producto', 'Logistica', '2018-02-21 13:28:32'),
(185, 'liraTI', 'Activo una confirmaciÃƒÂ³n con la orden de compra: 369125', 'Requerimiento Producto', 'Logistica', '2018-02-21 13:28:43'),
(186, 'liraTI', 'Desactivo una confirmaciÃƒÂ³n con la orden de compra: 369125', 'Requerimiento Producto', 'Logistica', '2018-02-21 13:29:24'),
(187, 'liraTI', 'Activo una confirmaciÃƒÂ³n con la orden de compra: 369125', 'Requerimiento Producto', 'Logistica', '2018-02-21 13:34:45'),
(188, 'liraTI', 'Desactivo una confirmaciÃƒÂ³n con la orden de compra: 369125', 'Requerimiento Producto', 'Logistica', '2018-02-21 13:35:21'),
(189, 'castillo66', 'Agrego el diseÃƒÂ±o:  Virus', 'DiseÃƒÂ±o', 'Productos', '2018-02-21 13:47:48'),
(190, 'liraTI', 'Agrego el embarque:  20181231001', 'Embarque', 'Logistica', '2018-02-22 08:45:27'),
(191, 'liraTI', 'Actualizo el embarque: 20181231001', 'Embarque', 'Logistica', '2018-02-22 08:54:24'),
(192, 'liraTI', 'Desactivo el embarque: 20181231001', 'Embarque', 'Logistica', '2018-02-22 08:54:36'),
(193, 'liraTI', 'Elimino el embarque: ', 'Embarque', 'Logistica', '2018-02-22 08:54:48'),
(194, 'liraTI', 'Activo el embarque: 20180202001', 'Embarque', 'Logistica', '2018-02-22 08:54:54'),
(195, 'liraTI', 'Desactivo el embarque: 20180202001', 'Embarque', 'Logistica', '2018-02-22 08:55:04'),
(196, 'liraTI', 'Agrego el embarque:  20181231001', 'Embarque', 'Logistica', '2018-02-22 09:18:39'),
(197, 'liraTI', 'Agrego el embarque:  20181231001', 'Embarque', 'Logistica', '2018-02-22 09:18:39'),
(198, 'liraTI', 'Agrego el embarque:  20181231001', 'Embarque', 'Logistica', '2018-02-22 09:18:46'),
(199, 'liraTI', 'Agrego el embarque:  20181231001', 'Embarque', 'Logistica', '2018-02-22 09:18:49'),
(200, 'liraTI', 'Agrego el embarque:  20181231001', 'Embarque', 'Logistica', '2018-02-22 09:19:57'),
(201, 'liraTI', 'Agrego el embarque:  20181231001', 'Embarque', 'Logistica', '2018-02-22 09:20:09'),
(202, 'liraTI', 'Agrego la orden:  Virus1000', 'Orden Compra', 'Logistica', '2018-02-22 09:21:57'),
(203, 'liraTI', 'Agrego un requerimiento a la orden de compra:  Virus1000', 'Requerimiento Producto', 'Logistica', '2018-02-22 09:22:25'),
(204, 'liraTI', 'Agrego una confirmaciÃƒÂ³n a la orden de compra:  Virus1000', 'Requerimiento Producto', 'Logistica', '2018-02-22 09:23:34'),
(205, 'liraTI', 'Agrego el embarque:  20181231001', 'Embarque', 'Logistica', '2018-02-22 09:26:26'),
(206, 'liraTI', 'Agrego el embarque:  20181231001', 'Embarque', 'Logistica', '2018-02-22 09:27:28'),
(207, 'liraTI', 'Agrego el embarque:  20181231001', 'Embarque', 'Logistica', '2018-02-22 09:28:17'),
(208, 'castillo66', 'Agrego la mÃƒÂ¡quina:  MTTR001', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-02-22 09:29:06'),
(209, 'castillo66', 'Agrego la mÃƒÂ¡quina:  MTSJ004', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-02-22 09:29:43'),
(210, 'liraTI', 'Agrego el embarque:  20181231001', 'Embarque', 'Logistica', '2018-02-22 09:30:22'),
(211, 'castillo66', 'Agrego la mÃƒÂ¡quina:  MTFOL45', 'MÃƒÂ¡quinas', 'ProducciÃƒÂ³n', '2018-02-22 09:30:22'),
(212, 'liraTI', 'Agrego el embarque:  20181231001', 'Embarque', 'Logistica', '2018-02-22 09:31:15'),
(213, 'liraTI', 'Agrego el embarque:  20181231001', 'Embarque', 'Logistica', '2018-02-22 09:31:40'),
(214, 'liraTI', 'Agrego el embarque:  20181231001', 'Embarque', 'Logistica', '2018-02-22 09:31:54'),
(215, 'liraTI', 'Agrego 5 empaques, 57.136 millares al producto:Electropura 108X75mm Portafolio P2395 2018/Q1', 'Armado de Embarque', 'Logistica', '2018-02-22 09:40:27'),
(216, 'liraTI', 'Agrego 5 empaques, 57.136 millares al embarque:20180207001', 'Armado de Embarque', 'Logistica', '2018-02-22 09:42:27'),
(217, 'liraTI', 'Agrego 5 empaques, 57.136 millares al embarque:20180207001', 'Armado de Embarque', 'Logistica', '2018-02-22 09:42:52'),
(218, 'liraTI', 'Agrego 5 empaques, 57.136 millares al embarque:20180207001', 'Armado de Embarque', 'Logistica', '2018-02-22 09:45:07'),
(219, 'liraTI', 'Agrego 0 empaques, 0 millares al embarque:20180207001', 'Armado de Embarque', 'Logistica', '2018-02-22 09:46:12'),
(220, 'liraTI', 'Agrego 5 empaques, 57.136 millares al embarque:20180207001', 'Armado de Embarque', 'Logistica', '2018-02-22 09:51:43'),
(221, 'liraTI', 'Agrego el embarque:  20181231001', 'Embarque', 'Logistica', '2018-02-22 09:59:16'),
(222, 'liraTI', 'Desactivo una confirmaciÃƒÂ³n con la orden de compra: 368368', 'Requerimiento Producto', 'Logistica', '2018-02-22 10:00:37'),
(223, 'liraTI', 'Actualizo un requerimiento con la orden de compra: 368368', 'Requerimiento Producto', 'Logistica', '2018-02-22 10:00:50'),
(224, 'liraTI', 'Agrego una confirmaciÃƒÂ³n a la orden de compra:  368368', 'Requerimiento Producto', 'Logistica', '2018-02-22 10:01:30'),
(225, 'liraTI', 'Agrego 3 empaques, 13.5 millares al embarque:20180223001', 'Armado de Embarque', 'Logistica', '2018-02-22 10:01:48'),
(226, 'liraTI', 'Agrego 5 empaques, 57.136 millares al embarque:20180207001', 'Armado de Embarque', 'Logistica', '2018-02-22 10:02:16'),
(227, 'liraTI', 'Agrego 5 empaques, 57.136 millares al embarque:20180207001', 'Armado de Embarque', 'Logistica', '2018-02-22 10:02:48'),
(228, 'liraTI', 'Agrego 3 empaques, 13.5 millares al embarque:20180223001', 'Armado de Embarque', 'Logistica', '2018-02-22 10:06:32'),
(229, 'liraTI', 'Agrego 0 empaques, 0 millares al embarque:20180223001', 'Armado de Embarque', 'Logistica', '2018-02-22 10:06:32'),
(230, 'liraTI', 'Agrego 3 empaques, 13.5 millares al embarque:20180223001', 'Armado de Embarque', 'Logistica', '2018-02-22 10:07:09'),
(231, 'liraTI', 'Agrego 5 empaques, 57.136 millares al embarque:20180207001', 'Armado de Embarque', 'Logistica', '2018-02-22 10:07:28'),
(232, 'liraTI', 'Agrego 5 empaques, 57.136 millares al embarque:20180207001', 'Armado de Embarque', 'Logistica', '2018-02-22 10:11:52'),
(233, 'liraTI', 'Retorno el empaque:  Q1, 16.975 ', 'Armado de Embarque', 'Logistica', '2018-02-22 10:11:56'),
(234, 'liraTI', 'Retorno el empaque:  Q2, 7.065 millares', 'Armado de Embarque', 'Logistica', '2018-02-22 10:12:41'),
(235, 'liraTI', 'Retorno el empaque Q3 (14.130 millares) del embarque: 20180207001', 'Armado de Embarque', 'Logistica', '2018-02-22 10:13:38'),
(236, 'liraTI', 'Activo el embarque: 20180202001', 'Embarque', 'Logistica', '2018-02-22 10:23:10'),
(237, 'liraTI', 'Actualizo el embarque: 20180202001', 'Embarque', 'Logistica', '2018-02-22 10:23:19'),
(238, 'liraTI', 'Activo una DevoluciÃƒÂ³n, Folio: 1802001, Tipo: Embarque', 'Devoluciones', 'Logistica', '2018-02-22 10:50:10'),
(239, 'liraTI', 'Desactivo una DevoluciÃƒÂ³n, Folio: 1802001, Tipo: Embarque', 'Devoluciones', 'Logistica', '2018-02-22 10:50:22'),
(240, 'liraTI', 'Elimino una DevoluciÃƒÂ³n, Folio: 1802001, Tipo: Embarque', 'Devoluciones', 'Logistica', '2018-02-22 10:52:16'),
(241, 'liraTI', 'Agrego una DevoluciÃƒÂ³n, Folio:  1802001, Tipo: Embarque', 'Devoluciones', 'Logistica', '2018-02-22 10:52:47'),
(242, 'liraTI', 'Asingo Datos de Reporte a la DevoluciÃƒÂ³n, Folio: 1802001, Tipo: Embarque', 'Devoluciones', 'Logistica', '2018-02-22 10:56:29'),
(243, 'liraTI', 'Agrego 3 empaques, 38.17 millares al embarque:20180207001', 'Armado de Embarque', 'Logistica', '2018-02-22 11:52:11'),
(244, 'liraTI', 'Retorno el empaque Q1 (16.975 millares) del embarque: 20180207001', 'Armado de Embarque', 'Logistica', '2018-02-22 11:52:16'),
(245, 'liraTI', 'Retorno el empaque Q2 (7.065 millares) del embarque: 20180207001', 'Armado de Embarque', 'Logistica', '2018-02-22 11:52:17'),
(246, 'liraTI', 'Retorno el empaque Q3 (14.130 millares) del embarque: 20180207001', 'Armado de Embarque', 'Logistica', '2018-02-22 11:52:19'),
(247, 'liraTI', 'Retorno el empaque Q4 (7.079 millares) del embarque: 20180207001', 'Armado de Embarque', 'Logistica', '2018-02-22 11:52:20'),
(248, 'liraTI', 'Retorno el empaque Q5 (11.887 millares) del embarque: 20180207001', 'Armado de Embarque', 'Logistica', '2018-02-22 11:52:22'),
(249, 'liraTI', 'Agrego 5 empaques, 57.136 millares al embarque:20180207001', 'Armado de Embarque', 'Logistica', '2018-02-22 11:52:25'),
(250, 'liraTI', 'Agrego 3 empaques, 13.5 millares al embarque:20180223001', 'Armado de Embarque', 'Logistica', '2018-02-23 09:27:53'),
(251, 'liraTI', 'Retorno el empaque C1 (3.500 millares) del embarque: 20180223001', 'Armado de Embarque', 'Logistica', '2018-02-23 09:43:38'),
(252, 'liraTI', 'Retorno el empaque C2 (2.000 millares) del embarque: 20180223001', 'Armado de Embarque', 'Logistica', '2018-02-23 09:43:40'),
(253, 'liraTI', 'Retorno el empaque C3 (8.000 millares) del embarque: 20180223001', 'Armado de Embarque', 'Logistica', '2018-02-23 09:43:41'),
(254, 'liraTI', 'Agrego el embarque:  ', 'Embarque', 'Logistica', '2018-02-23 10:29:22'),
(255, 'castillo66', 'Actualizo al empleado Num: 191', 'Personal', 'Recursos Humanos', '2018-02-23 10:37:38'),
(256, 'liraTI', 'Agrego 0 empaques, 0 millares al embarque:20180224001', 'Armado de Embarque', 'Logistica', '2018-02-23 11:07:00'),
(257, 'liraTI', 'Agrego 1 empaques, 15.201 millares al embarque:20180224001', 'Armado de Embarque', 'Logistica', '2018-02-23 11:19:13'),
(258, 'liraTI', 'Retorno el empaque Q1 (15.201 millares) del embarque: 20180224001', 'Armado de Embarque', 'Logistica', '2018-02-23 11:19:38'),
(259, 'liraTI', 'Agrego 1 empaques, 15.201 millares al embarque:20180224001', 'Armado de Embarque', 'Logistica', '2018-02-23 11:20:02'),
(260, 'liraTI', 'Retorno el empaque Q1 (15.201 millares) del embarque: 20180224001', 'Armado de Embarque', 'Logistica', '2018-02-23 11:24:37'),
(261, 'liraTI', 'Agrego 1 empaques, 15.201 millares al embarque:20180224001', 'Armado de Embarque', 'Logistica', '2018-02-23 11:24:45'),
(262, 'liraTI', 'Desactivo el diseÃƒÂ±o: epepepepe', 'DiseÃƒÂ±o', 'Productos', '2018-02-23 12:27:03'),
(263, 'liraTI', 'Activo el diseÃƒÂ±o: epepepepe', 'DiseÃƒÂ±o', 'Productos', '2018-02-23 12:27:29'),
(264, 'liraTI', 'Elimino el diseÃƒÂ±o: PruebaBaja', 'DiseÃƒÂ±o', 'Productos', '2018-02-23 12:28:29'),
(265, 'liraTI', 'Agrego un requerimiento a la orden de compra:  28RedSantorini', 'Requerimiento Producto', 'Logistica', '2018-03-01 11:50:00'),
(266, 'liraTI', 'Desactivo un requerimiento con la orden de compra: 28RedSantorini', 'Requerimiento Producto', 'Logistica', '2018-03-01 12:03:10'),
(267, 'castillo66', 'Elimino el departamento: ProducciÃƒÂ³n', 'Departamento', 'Recursos Humanos', '2018-03-06 08:56:22'),
(268, 'castillo66', 'Elimino el departamento: TecnologÃƒÂ­as de la InformaciÃƒÂ³n', 'Departamento', 'Recursos Humanos', '2018-03-06 08:56:23'),
(269, 'castillo66', 'Elimino el departamento: LogÃƒÂ­stica', 'Departamento', 'Recursos Humanos', '2018-03-06 08:56:23'),
(270, 'castillo66', 'Agrego el departamento:  ProducciÃ³n', 'Departamento', 'Recursos Humanos', '2018-03-06 08:56:48'),
(271, 'castillo66', 'Agrego el departamento:  TecnologÃ­as de la informaciÃ³n', 'Departamento', 'Recursos Humanos', '2018-03-06 08:57:00'),
(272, 'castillo66', 'Agrego el departamento:  Corte', 'Departamento', 'Recursos Humanos', '2018-03-06 08:57:04'),
(273, 'castillo66', 'Agrego el departamento:  ImpresiÃ³n', 'Departamento', 'Recursos Humanos', '2018-03-06 08:57:09'),
(274, 'castillo66', 'Agrego al empleado Num:  001', 'Personal', 'Recursos Humanos', '2018-03-06 08:59:03'),
(275, 'castillo66', 'Agrego al empleado Num:  002', 'Personal', 'Recursos Humanos', '2018-03-06 08:59:21'),
(276, 'castillo66', 'Agrego al empleado Num:  003', 'Personal', 'Recursos Humanos', '2018-03-06 08:59:41'),
(277, 'castillo66', 'Agrego al empleado Num:  004', 'Personal', 'Recursos Humanos', '2018-03-06 09:00:06'),
(278, 'castillo66', 'Agrego el departamento:  Compras', 'Departamento', 'Recursos Humanos', '2018-03-07 08:21:33'),
(279, 'castillo66', 'Agrego el departamento:  LogÃ­stica', 'Departamento', 'Recursos Humanos', '2018-03-07 08:21:48'),
(280, 'castillo66', 'Agrego el departamento:  FusiÃ³n', 'Departamento', 'Recursos Humanos', '2018-03-07 08:22:17'),
(281, 'castillo66', 'Agrego el departamento:  Refilado', 'Departamento', 'Recursos Humanos', '2018-03-07 08:22:23'),
(282, 'castillo66', 'Agrego el departamento:  RevisiÃ³n', 'Departamento', 'Recursos Humanos', '2018-03-07 08:22:41'),
(283, 'castillo66', 'Agrego al empleado Num:  010', 'Personal', 'Recursos Humanos', '2018-03-07 08:23:17'),
(284, 'castillo66', 'Agrego al empleado Num:  010', 'Personal', 'Recursos Humanos', '2018-03-07 08:23:17'),
(285, 'castillo66', 'Agrego al empleado Num:  010', 'Personal', 'Recursos Humanos', '2018-03-07 08:24:20'),
(286, 'castillo66', 'Agrego al empleado Num:  011', 'Personal', 'Recursos Humanos', '2018-03-07 08:25:19'),
(287, 'castillo66', 'Actualizo al empleado Num: 69', 'Personal', 'Recursos Humanos', '2018-03-07 08:25:34'),
(288, 'castillo66', 'Actualizo al empleado Num: 2', 'Personal', 'Recursos Humanos', '2018-03-07 08:25:47'),
(289, 'castillo66', 'Desactivo al empleado Num: 201', 'Personal', 'Recursos Humanos', '2018-03-07 08:25:51'),
(290, 'castillo66', 'Actualizo al empleado Num: 028', 'Personal', 'Recursos Humanos', '2018-03-07 08:26:02'),
(291, 'castillo66', 'Activo al empleado Num: 201', 'Personal', 'Recursos Humanos', '2018-03-07 08:26:11'),
(292, 'castillo66', 'Desactivo al empleado Num: 201', 'Personal', 'Recursos Humanos', '2018-03-07 08:26:15'),
(293, 'castillo66', 'Agrego al empleado Num:  384', 'Personal', 'Recursos Humanos', '2018-03-07 09:27:17'),
(294, 'castillo66', 'Agrego al empleado Num:  033', 'Personal', 'Recursos Humanos', '2018-03-07 13:00:40'),
(295, 'castillo66', 'Desactivo el diseÃ±o: chileylimon', 'DiseÃ±o', 'Productos', '2018-03-07 13:33:38'),
(296, 'castillo66', 'Desactivo el diseÃ±o: epepepepe', 'DiseÃ±o', 'Productos', '2018-03-07 13:33:41'),
(297, 'castillo66', 'Desactivo el diseÃ±o: Pruebita', 'DiseÃ±o', 'Productos', '2018-03-07 13:33:46'),
(298, 'castillo66', 'Desactivo el diseÃ±o: manguito', 'DiseÃ±o', 'Productos', '2018-03-07 13:33:49'),
(299, 'castillo66', 'Desactivo el diseÃ±o: k-sito', 'DiseÃ±o', 'Productos', '2018-03-07 13:33:57'),
(300, 'castillo66', 'Desactivo el diseÃ±o: Pruebs1', 'DiseÃ±o', 'Productos', '2018-03-07 13:34:03'),
(301, 'castillo66', 'Agrego el diseÃ±o:  Chivacola', 'DiseÃ±o', 'Productos', '2018-03-07 13:36:51'),
(302, 'castillo66', 'Agrego el diseÃ±o:  Bevi', 'DiseÃ±o', 'Productos', '2018-03-07 13:38:25'),
(303, 'castillo66', 'Desactivo el sustrato: PTG transparente C50 308 mm', 'Sustratos', 'Materia Prima', '2018-03-07 13:49:17'),
(304, 'castillo66', 'Desactivo el sustrato: Polyester embosado C20 e-pura 640mm', 'Sustratos', 'Materia Prima', '2018-03-07 13:58:09'),
(305, 'castillo66', 'Agrego el diseÃ±o:  Atom', 'DiseÃ±o', 'Productos', '2018-03-08 08:34:43'),
(306, 'castillo66', 'Agrego el cirel:  AT-56CIR', 'Juego de Cireles', 'Productos', '2018-03-08 08:39:07'),
(307, 'castillo66', 'Actualizo la mÃ¡quina: MTFOL45', 'MÃ¡quinas', 'ProducciÃ³n', '2018-03-08 09:10:02'),
(308, 'castillo66', 'Actualizo la mÃ¡quina: MTFL01', 'MÃ¡quinas', 'ProducciÃ³n', '2018-03-08 09:10:10'),
(309, 'castillo66', 'Actualizo el cirel:  AT-56CIR', 'Juego de Cireles', 'Productos', '2018-03-08 09:12:46'),
(310, 'castillo66', 'Agrego al empleado Num:  67', 'Personal', 'Recursos Humanos', '2018-03-08 09:32:04'),
(311, 'castillo66', 'Agrego el diseÃ±o:  Apocalipsis', 'DiseÃ±o', 'Productos', '2018-03-08 12:16:18'),
(312, 'castillo66', 'Agrego el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-08 12:34:28'),
(313, 'castillo66', 'Agrego el suaje:  suaje', 'Suaje', 'Productos', '2018-03-08 12:39:44'),
(314, 'castillo66', 'Agrego la mÃ¡quina:  MTFL02', 'MÃ¡quinas', 'ProducciÃ³n', '2018-03-08 12:49:46'),
(315, 'castillo66', 'Agrego el diseÃ±o:  Tec', 'DiseÃ±o', 'Productos', '2018-03-09 09:27:29'),
(316, 'castillo66', 'Agrego el juego de cilindro:  SACK643', 'Juegos Cilindro', 'Productos', '2018-03-09 09:31:44'),
(317, 'castillo66', 'Agrego el diseÃ±o:  Splonder', 'DiseÃ±o', 'Productos', '2018-03-09 10:33:35'),
(318, 'castillo66', 'Agrego la mÃ¡quina:  MTEM01', 'MÃ¡quinas', 'ProducciÃ³n', '2018-03-09 11:58:10'),
(319, 'castillo66', 'Actualizo el suaje:  SUJBONE1', 'Suajes', 'Productos', '2018-03-09 13:59:32'),
(320, 'castillo66', 'Agrego un consumo al diseÃ±o:  ', 'Consumos', 'Productos', '2018-03-12 08:31:06'),
(321, 'castillo66', 'Agrego un consumo al diseÃ±o:  Atom', 'Consumos', 'Productos', '2018-03-12 08:58:16'),
(322, 'castillo66', 'Actualizo el consumo del producto:  Atom', 'Consumos', 'Productos', '2018-03-12 09:00:29'),
(323, 'castillo66', 'Actualizo el elemeto:  TINESEPV405RF00', 'Elementos', 'Materia Prima', '2018-03-12 09:01:10'),
(324, 'castillo66', 'Actualizo el elemeto:  TINESEPV405RF00', 'Elementos', 'Materia Prima', '2018-03-12 09:01:18'),
(325, 'castillo66', 'Actualizo el elemeto:  TIMASEPV404RF00', 'Elementos', 'Materia Prima', '2018-03-12 09:01:28'),
(326, 'castillo66', 'Actualizo el elemeto:  TICYSEPV050RF00', 'Elementos', 'Materia Prima', '2018-03-12 09:01:34'),
(327, 'castillo66', 'Actualizo el elemeto:  TIBLACSE279RF0', 'Elementos', 'Materia Prima', '2018-03-12 09:01:43'),
(328, 'castillo66', 'Actualizo el elemeto:  TIAMSEPV403RF00', 'Elementos', 'Materia Prima', '2018-03-12 09:01:52'),
(329, 'castillo66', 'Actualizo el elemeto:  ABOPOOOO0000000', 'Elementos', 'Materia Prima', '2018-03-12 09:02:16'),
(330, 'castillo66', 'Actualizo el elemeto:  EBCJCRPL3253250', 'Elementos', 'Materia Prima', '2018-03-12 09:02:25'),
(331, 'castillo66', 'Actualizo el elemeto:  EBCRSNFA1681000', 'Elementos', 'Materia Prima', '2018-03-12 09:02:40'),
(332, 'castillo66', 'Actualizo el elemeto:  ISCNCAIM4815000', 'Elementos', 'Materia Prima', '2018-03-12 09:02:52'),
(333, 'castillo66', 'Agrego el tipo de mediciÃ³n:  PA', 'Unidades de Medida', 'Materia Prima', '2018-03-12 09:04:13'),
(334, 'castillo66', 'Actualizo el tipo de mediciÃ³n:  PA', 'Unidades de Medida', 'Materia Prima', '2018-03-12 09:04:19'),
(335, 'castillo66', 'Actualizo el tipo de mediciÃ³n:  PA', 'Unidades de Medida', 'Materia Prima', '2018-03-12 09:07:02'),
(336, 'castillo66', 'Actualizo el tipo de mediciÃ³n:  PA', 'Unidades de Medida', 'Materia Prima', '2018-03-12 09:07:08'),
(337, 'castillo66', 'Actualizo el tipo de mediciÃ³n:  PA', 'Unidades de Medida', 'Materia Prima', '2018-03-12 09:07:13'),
(338, 'castillo66', 'Actualizo el tipo de mediciÃ³n:  m3', 'Unidades de Medida', 'Materia Prima', '2018-03-12 09:07:24'),
(339, 'castillo66', 'Desactivo el tipo de mediciÃ³n: PA', 'Unidades de Medida', 'Materia Prima', '2018-03-12 09:18:53'),
(340, 'castillo66', 'Desactivo el tipo de mediciÃ³n: PA', 'Unidades de Medida', 'Materia Prima', '2018-03-12 09:22:45'),
(341, 'castillo66', 'Agrego el pantone:  Pantone 364 C', 'Pantones', 'Materia Prima', '2018-03-12 09:26:17'),
(342, 'castillo66', 'Actualizo el pantone:  Pantone 364 C', 'Pantones', 'Materia Prima', '2018-03-12 09:27:38'),
(343, 'castillo66', 'Actualizo el pantone:  Pantone 364 C', 'Pantones', 'Materia Prima', '2018-03-12 09:27:43'),
(344, 'castillo66', 'Desactivo el pantone: Pantone 364 C', 'Pantones', 'Materia Prima', '2018-03-12 09:27:49'),
(345, 'castillo66', 'Desactivo el pantone: Pantone 364 C', 'Pantones', 'Materia Prima', '2018-03-12 09:28:28'),
(346, 'castillo66', 'Desactivo el pantone: Pantone 364 C', 'Pantones', 'Materia Prima', '2018-03-12 09:28:49'),
(347, 'castillo66', 'Elimino el pantone: Pantone 364 C', 'Pantones', 'Materia Prima', '2018-03-12 09:28:54'),
(348, 'castillo66', 'Agrego el pantone:  Pantone 364 C', 'Pantones', 'Materia Prima', '2018-03-12 09:29:18'),
(349, 'castillo66', 'Agrego el elemento:  corcholatas', 'Elementos', 'Materia Prima', '2018-03-12 09:41:03'),
(350, 'castillo66', 'Desactivo el elemento: corcholatas', 'Elementos', 'Materia Prima', '2018-03-12 09:41:08'),
(351, 'castillo66', 'Desactivo el elemento: corcholatas', 'Elementos', 'Materia Prima', '2018-03-12 09:46:52'),
(352, 'castillo66', 'Actualizo el lote:  Polyester embosado C20 santorini 320mm', 'Lotes', 'Materia Prima', '2018-03-12 09:53:44'),
(353, 'castillo66', 'Agrego el departamento:  Compras', 'Departamento', 'Recursos Humanos', '2018-03-12 09:55:43'),
(354, 'castillo66', 'Desactivo el sustrato: Polyester embosado C20 electropura 320mm', 'Sustratos', 'Materia Prima', '2018-03-12 10:06:06'),
(355, 'castillo66', 'Desactivo el sustrato: Polyester embosado C20 electropura 320mm', 'Sustratos', 'Materia Prima', '2018-03-12 10:06:20'),
(356, 'castillo66', 'Desactivo el sustrato: Polyester embosado C20 electropura 320mm', 'Sustratos', 'Materia Prima', '2018-03-12 10:06:37'),
(357, 'castillo66', 'Desactivo el sustrato: Polyester embosado C20 electropura 320mm', 'Sustratos', 'Materia Prima', '2018-03-12 10:06:48'),
(358, 'castillo66', 'Desactivo el sustrato: Polyester embosado C20 electropura 320mm', 'Sustratos', 'Materia Prima', '2018-03-12 10:08:12'),
(359, 'castillo66', 'Actualizo el consumo del producto:  0', 'Consumos', 'Productos', '2018-03-12 10:26:10'),
(360, 'castillo66', 'Actualizo el consumo del producto:  0', 'Consumos', 'Productos', '2018-03-12 10:26:23'),
(361, 'liraTI', 'Agrego el embarque:  20180228001', 'Embarque', 'Logistica', '2018-03-13 09:10:36'),
(362, 'liraTI', 'Agrego 5 empaques, 54.443 millares al embarque:20180228001', 'Armado de Embarque', 'Logistica', '2018-03-13 09:11:13'),
(363, 'liraTI', 'Retorno el empaque Q1 (17.356 millares) del embarque: 20180228001', 'Armado de Embarque', 'Logistica', '2018-03-13 09:11:18'),
(364, 'liraTI', 'Retorno el empaque Q2 (14.512 millares) del embarque: 20180228001', 'Armado de Embarque', 'Logistica', '2018-03-13 09:11:19'),
(365, 'liraTI', 'Retorno el empaque Q3 (7.623 millares) del embarque: 20180228001', 'Armado de Embarque', 'Logistica', '2018-03-13 09:11:21'),
(366, 'liraTI', 'Retorno el empaque Q4 (7.373 millares) del embarque: 20180228001', 'Armado de Embarque', 'Logistica', '2018-03-13 09:11:22'),
(367, 'liraTI', 'Retorno el empaque Q5 (7.579 millares) del embarque: 20180228001', 'Armado de Embarque', 'Logistica', '2018-03-13 09:11:23'),
(368, 'liraTI', 'Agrego 5 empaques, 54.443 millares al embarque:20180228001', 'Armado de Embarque', 'Logistica', '2018-03-13 09:11:28'),
(369, 'liraTI', 'Agrego una DevoluciÃ³n, Folio:  1803001, Tipo: Embarque', 'Devoluciones', 'Logistica', '2018-03-13 09:11:52'),
(370, 'liraTI', 'Agrego el elemento:  PRUEBINSANTO11', 'Elementos', 'Materia Prima', '2018-03-13 09:26:37'),
(371, 'liraTI', 'Actualizo el elemeto:  PRUEBINSANTO', 'Elementos', 'Materia Prima', '2018-03-13 09:27:00'),
(372, 'liraTI', 'Desactivo el elemento: PRUEBIssNSANTO11', 'Elementos', 'Materia Prima', '2018-03-13 09:27:39'),
(373, 'liraTI', 'Elimino el elemento: corcholatas', 'Elementos', 'Materia Prima', '2018-03-13 09:27:59'),
(374, 'liraTI', 'Desactivo el elemento: PRUEBINSANTO', 'Elementos', 'Materia Prima', '2018-03-13 09:36:56'),
(375, 'liraTI', 'Desactivo el elemento: PRUEBINSANTO', 'Elementos', 'Materia Prima', '2018-03-13 09:39:51'),
(376, 'liraTI', 'Activo el elemento: PRUEBINSANTO', 'Elementos', 'Materia Prima', '2018-03-13 09:40:00'),
(377, 'liraTI', 'Agrego un lote al bloque:  PVC termoencogible C40 E50/0 400mm', 'Lotes', 'Materia Prima', '2018-03-13 10:07:40'),
(378, 'liraTI', 'Desactivo el lote: PVC termoencogible C40 E50/0 400mm', 'Lotes', 'Materia Prima', '2018-03-13 10:16:00'),
(379, 'liraTI', 'Desactivo el lote: PVC Cedula 40', 'Lotes', 'Materia Prima', '2018-03-13 10:21:08'),
(380, 'liraTI', 'Desactivo el lote: PVC Cedula 40', 'Lotes', 'Materia Prima', '2018-03-13 10:22:14'),
(381, 'liraTI', 'Activo el lote: PVC Cedula 40', 'Lotes', 'Materia Prima', '2018-03-13 10:22:20'),
(382, 'liraTI', 'Desactivo el lote: PVC Cedula 40', 'Lotes', 'Materia Prima', '2018-03-13 10:22:39'),
(383, 'liraTI', 'Elimino el lote: PVC Cedula 40', 'Lotes', 'Materia Prima', '2018-03-13 10:22:47'),
(384, 'liraTI', 'Desactivo el pantone: PANTONE 805 C', 'Pantones', 'Materia Prima', '2018-03-13 10:30:55'),
(385, 'liraTI', 'Activo el pantone: PANTONE 805 C', 'Pantones', 'Materia Prima', '2018-03-13 10:31:04'),
(386, 'liraTI', 'Agrego el pantone:  Pantone 36800 C', 'Pantones', 'Materia Prima', '2018-03-13 10:31:30'),
(387, 'liraTI', 'Desactivo el pantone: Pantone 36800 C', 'Pantones', 'Materia Prima', '2018-03-13 10:31:41'),
(388, 'liraTI', 'Elimino el pantone: Pantone 36800 C', 'Pantones', 'Materia Prima', '2018-03-13 10:31:51'),
(389, 'liraTI', 'Actualizo el pantone:  Pantone 364 C', 'Pantones', 'Materia Prima', '2018-03-13 10:32:09'),
(390, 'liraTI', 'Actualizo el pantone:  Pantone 364 C', 'Pantones', 'Materia Prima', '2018-03-13 10:32:18'),
(391, 'liraTI', 'Agrego el tipo de mediciÃ³n:  \"', 'Unidades de Medida', 'Materia Prima', '2018-03-13 10:33:57'),
(392, 'liraTI', 'Actualizo el tipo de mediciÃ³n:  plg', 'Unidades de Medida', 'Materia Prima', '2018-03-13 10:42:17'),
(393, 'liraTI', 'Desactivo el tipo de mediciÃ³n: plg', 'Unidades de Medida', 'Materia Prima', '2018-03-13 10:42:36'),
(394, 'liraTI', 'Agrego el tipo de mediciÃ³n:  Pulga', 'Unidades de Medida', 'Materia Prima', '2018-03-13 10:53:13'),
(395, 'liraTI', 'Activo el tipo de mediciÃ³n: PA', 'Unidades de Medida', 'Materia Prima', '2018-03-13 10:53:13'),
(396, 'liraTI', 'Desactivo el tipo de mediciÃ³n: Pulga', 'Unidades de Medida', 'Materia Prima', '2018-03-13 10:53:18'),
(397, 'liraTI', 'Activo el tipo de mediciÃ³n: Pulga', 'Unidades de Medida', 'Materia Prima', '2018-03-13 10:53:22'),
(398, 'liraTI', 'Desactivo el tipo de mediciÃ³n: Pulga', 'Unidades de Medida', 'Materia Prima', '2018-03-13 10:53:25'),
(399, 'liraTI', 'Elimino el tipo de mediciÃ³n: Pulga', 'Unidades de Medida', 'Materia Prima', '2018-03-13 10:53:29'),
(400, 'liraTI', 'Actualizo el sustrato:  Cedula 40mil', 'Sustratos', 'Materia Prima', '2018-03-13 11:07:33'),
(401, 'liraTI', 'Desactivo el sustrato: Cedula 40mil', 'Sustratos', 'Materia Prima', '2018-03-13 11:07:54'),
(402, 'liraTI', 'Desactivo el sustrato: Cedula 40mil', 'Sustratos', 'Materia Prima', '2018-03-13 11:08:18'),
(403, 'liraTI', 'Activo el sustrato: Cedula 40mil', 'Sustratos', 'Materia Prima', '2018-03-13 11:08:22'),
(404, 'liraTI', 'Activo el sustrato: Cedula 40mil', 'Sustratos', 'Materia Prima', '2018-03-13 11:08:30'),
(405, 'liraTI', 'Elimino el sustrato: Polyester embosado C20 electropura 320mm', 'Sustratos', 'Materia Prima', '2018-03-13 11:08:34'),
(406, 'liraTI', 'Desactivo el sustrato: Polyester embosado C20 electropura 640mm', 'Sustratos', 'Materia Prima', '2018-03-13 11:08:43'),
(407, 'liraTI', 'Agrego al empleado Num:  066', 'Personal', 'Recursos Humanos', '2018-03-13 12:00:38'),
(408, 'lauTI', 'Agrego al empleado Num:  066', 'Personal', 'Recursos Humanos', '2018-03-13 13:24:19'),
(409, 'lauTI', 'Agrego al empleado Num:  058', 'Personal', 'Recursos Humanos', '2018-03-13 13:27:17'),
(410, 'lauTI', 'Agrego el diseÃ±o:  probando taza', 'DiseÃ±o', 'Productos', '2018-03-13 13:58:01'),
(411, 'castillo66', 'Agrego el juego de cilindro:  San674', 'Juegos Cilindro', 'Productos', '2018-03-14 09:33:55'),
(412, 'castillo66', 'Agrego el juego de cilindro:  San674', 'Juegos Cilindro', 'Productos', '2018-03-14 09:34:25'),
(413, 'castillo66', 'Agrego el juego de cilindro:  San674', 'Juegos Cilindro', 'Productos', '2018-03-14 09:34:58'),
(414, 'castillo66', 'Agrego el juego de cilindro:  SPJUEOG', 'Juegos Cilindro', 'Productos', '2018-03-14 09:35:47'),
(415, 'castillo66', 'Agrego el juego de cilindro:  rewr56', 'Juegos Cilindro', 'Productos', '2018-03-14 09:36:13'),
(416, 'castillo66', 'Agrego el juego de cilindro:  56', 'Juegos Cilindro', 'Productos', '2018-03-14 09:36:57'),
(417, 'castillo66', 'Agrego el diseÃ±o:  stunk', 'DiseÃ±o', 'Productos', '2018-03-14 11:17:09'),
(418, 'castillo66', 'Agrego el diseÃ±o:  pinki', 'DiseÃ±o', 'Productos', '2018-03-14 11:20:21'),
(419, 'castillo66', 'Agrego el juego de cilindro:  awryara', 'Juegos Cilindro', 'Productos', '2018-03-14 11:43:59'),
(420, 'castillo66', 'Desactivo el sustrato: Cedula 40mil', 'Sustratos', 'Materia Prima', '2018-03-14 12:15:40'),
(421, 'castillo66', 'Desactivo al empleado Num: 384', 'Personal', 'Recursos Humanos', '2018-03-14 13:05:58'),
(422, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:36:07'),
(423, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:36:38'),
(424, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:37:15'),
(425, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:38:05'),
(426, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:38:10'),
(427, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:38:13'),
(428, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:38:17'),
(429, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:38:19'),
(430, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:42:02'),
(431, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:42:39'),
(432, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:43:39'),
(433, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:43:52'),
(434, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:44:06'),
(435, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:44:33'),
(436, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:45:10'),
(437, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:45:18'),
(438, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:45:42'),
(439, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:45:51'),
(440, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:45:56'),
(441, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:46:29'),
(442, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:52:02'),
(443, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:52:20'),
(444, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:52:47'),
(445, 'castillo66', 'Actualizo el cirel:  jc1520', 'Juego de Cireles', 'Productos', '2018-03-15 10:56:36');
INSERT INTO `reporte` (`id`, `nombre`, `accion`, `modulo`, `departamento`, `registro`) VALUES
(446, 'castillo66', 'Agrego el cirel:  cilis45', 'Juego de Cireles', 'Productos', '2018-03-15 11:02:26'),
(447, 'castillo66', 'Desactivo el cirel: cilis45', 'Juego de Cireles', 'Productos', '2018-03-15 11:02:34'),
(448, 'castillo66', 'Actualizo el juego de cilindro: W7110503', 'Juegos Cilindro', 'Productos', '2018-03-15 11:12:59'),
(449, 'castillo66', 'Actualizo el juego de cilindro: W7110503', 'Juegos Cilindro', 'Productos', '2018-03-15 11:13:05'),
(450, 'castillo66', 'Actualizo el juego de cilindro: W7110503', 'Juegos Cilindro', 'Productos', '2018-03-15 11:13:37'),
(451, 'castillo66', 'Actualizo el juego de cilindro: W7110503', 'Juegos Cilindro', 'Productos', '2018-03-15 11:13:40'),
(452, 'castillo66', 'Actualizo el juego de cilindro: W7110503', 'Juegos Cilindro', 'Productos', '2018-03-15 11:13:44'),
(453, 'castillo66', 'Agrego el juego de cilindro:  SAK0911', 'Juegos Cilindro', 'Productos', '2018-03-15 11:17:15'),
(454, 'castillo66', 'Agrego el juego de cilindro:  SAK0911', 'Juegos Cilindro', 'Productos', '2018-03-15 11:17:51'),
(455, 'castillo66', 'Agrego el juego de cilindro:  SAK0911', 'Juegos Cilindro', 'Productos', '2018-03-15 11:23:08'),
(456, 'castillo66', 'Agrego el juego de cilindro:  SAK0911', 'Juegos Cilindro', 'Productos', '2018-03-15 11:23:38'),
(457, 'castillo66', 'Agrego el juego de cilindro:  SAK0911', 'Juegos Cilindro', 'Productos', '2018-03-15 11:24:06'),
(458, 'castillo66', 'Agrego el juego de cilindro:  SAK0911', 'Juegos Cilindro', 'Productos', '2018-03-15 11:40:50'),
(459, 'castillo66', 'Agrego el juego de cilindro:  SAK0911', 'Juegos Cilindro', 'Productos', '2018-03-15 11:43:05'),
(460, 'castillo66', 'Actualizo el diseÃ±o: pinki', 'DiseÃ±o', 'Productos', '2018-03-15 12:42:46'),
(461, 'castillo66', 'Agrego el cirel:  SGSDF', 'Juego de Cireles', 'Productos', '2018-03-16 10:27:46'),
(462, 'castillo66', 'Agrego el diseÃ±o:  Caballito', 'DiseÃ±o', 'Productos', '2018-03-16 13:42:23'),
(463, 'castillo66', 'Actualizo el diseÃ±o: Caballito', 'DiseÃ±o', 'Productos', '2018-03-16 13:44:32'),
(464, 'castillo66', 'Agrego el diseÃ±o:  El caribe', 'DiseÃ±o', 'Productos', '2018-03-16 13:45:30'),
(465, 'castillo66', 'Agrego el diseÃ±o:  El caribe', 'DiseÃ±o', 'Productos', '2018-03-16 13:45:47'),
(466, 'castillo66', 'Agrego el diseÃ±o:  Hologram', 'DiseÃ±o', 'Productos', '2018-03-16 13:46:06'),
(467, 'castillo66', 'Actualizo el diseÃ±o: Hologram', 'DiseÃ±o', 'Productos', '2018-03-20 10:40:25'),
(468, 'castillo66', 'Actualizo al empleado Num: 191', 'Personal', 'Recursos Humanos', '2018-03-20 13:13:34'),
(469, 'castillo66', 'Actualizo al empleado Num: 34', 'Personal', 'Recursos Humanos', '2018-03-20 13:13:46'),
(470, 'castillo66', 'Agrego al empleado Num:  056', 'Personal', 'Recursos Humanos', '2018-03-21 10:48:37'),
(471, 'castillo66', 'Desactivo al empleado Num: 2', 'Personal', 'Recursos Humanos', '2018-03-21 10:48:48'),
(472, 'castillo66', 'Desactivo al empleado Num: 191', 'Personal', 'Recursos Humanos', '2018-03-21 10:49:02'),
(473, 'castillo66', 'Elimino al empleado Num: 191', 'Personal', 'Recursos Humanos', '2018-03-21 10:49:13'),
(474, 'castillo66', 'Activo al empleado Num: 201', 'Personal', 'Recursos Humanos', '2018-03-21 10:49:21'),
(475, 'castillo66', 'Desactivo al empleado Num: 201', 'Personal', 'Recursos Humanos', '2018-03-21 10:49:26'),
(476, 'castillo66', 'Elimino al empleado Num: 201', 'Personal', 'Recursos Humanos', '2018-03-21 10:49:31'),
(477, 'castillo66', 'Actualizo al empleado Num: 056', 'Personal', 'Recursos Humanos', '2018-03-21 10:49:53'),
(478, 'castillo66', 'Actualizo al empleado Num: 06', 'Personal', 'Recursos Humanos', '2018-03-21 10:50:00'),
(479, 'castillo66', 'Actualizo al empleado Num: 003', 'Personal', 'Recursos Humanos', '2018-03-21 10:50:19'),
(480, 'castillo66', 'Actualizo el diseÃ±o: Hologram', 'DiseÃ±o', 'Productos', '2018-03-21 10:54:38'),
(481, 'castillo66', 'Actualizo el diseÃ±o: Hologram', 'DiseÃ±o', 'Productos', '2018-03-21 10:54:50'),
(482, 'castillo66', 'Actualizo el diseÃ±o: Hologram', 'DiseÃ±o', 'Productos', '2018-03-21 10:56:38'),
(483, 'castillo66', 'Actualizo el diseÃ±o: Hologram', 'DiseÃ±o', 'Productos', '2018-03-21 10:57:02'),
(484, 'castillo66', 'Actualizo el diseÃ±o: El caribe', 'DiseÃ±o', 'Productos', '2018-03-21 10:57:26'),
(485, 'castillo66', 'Actualizo el sustrato:  PTG transparente C50 308 mm', 'Sustratos', 'Materia Prima', '2018-03-21 11:14:26'),
(486, 'castillo66', 'Desactivo el sustrato: Polyester embosado C20 e-pura 320mm', 'Sustratos', 'Materia Prima', '2018-03-21 11:17:13'),
(487, 'castillo66', 'Activo el sustrato: Polyester embosado C20 electropura 640mm', 'Sustratos', 'Materia Prima', '2018-03-21 11:17:21'),
(488, 'castillo66', 'Desactivo el sustrato: Polyester embosado C20 electropura 320mm', 'Sustratos', 'Materia Prima', '2018-03-21 11:17:28'),
(489, 'castillo66', 'Elimino el sustrato: Cedula 40mil', 'Sustratos', 'Materia Prima', '2018-03-21 11:17:33'),
(490, 'castillo66', 'Actualizo el lote:  7003274PV400-23', 'Lotes', 'Materia Prima', '2018-03-21 11:21:28'),
(491, 'castillo66', 'Actualizo el lote:  7003274PV400-54', 'Lotes', 'Materia Prima', '2018-03-21 11:22:32'),
(492, 'castillo66', 'Desactivo el lote: 7003274PV400-54', 'Lotes', 'Materia Prima', '2018-03-21 11:24:56'),
(493, 'castillo66', 'Elimino el lote: 7003274PV400-54', 'Lotes', 'Materia Prima', '2018-03-21 11:25:10'),
(494, 'castillo66', 'Desactivo el elemento: PRUEBINSANTO', 'Elementos', 'Materia Prima', '2018-03-21 12:09:47'),
(495, 'castillo66', 'Activo el elemento: PRUEBINSANTO', 'Elementos', 'Materia Prima', '2018-03-21 12:09:52'),
(496, 'castillo66', 'Desactivo el elemento: PRUEBINSANTO', 'Elementos', 'Materia Prima', '2018-03-21 12:09:55'),
(497, 'castillo66', 'Desactivo el elemento: ABOPOOOO0000000', 'Elementos', 'Materia Prima', '2018-03-21 12:10:00'),
(498, 'castillo66', 'Agrego el elemento:  tntwkfsef', 'Elementos', 'Materia Prima', '2018-03-21 12:15:13'),
(499, 'castillo66', 'Desactivo el elemento: tntwkfsef', 'Elementos', 'Materia Prima', '2018-03-21 12:15:17'),
(500, 'castillo66', 'Elimino el elemento: PRUEBINSANTO', 'Elementos', 'Materia Prima', '2018-03-21 12:15:26'),
(501, 'castillo66', 'Agrego el juego de cilindro:  CKVK', 'Juegos Cilindro', 'Productos', '2018-03-21 13:13:22'),
(502, 'castillo66', 'Agrego el juego de cilindro:  KCBBJ', 'Juegos Cilindro', 'Productos', '2018-03-21 13:28:28'),
(503, 'castillo66', 'Agrego el sustrato:  mfbl', 'Sustrato', 'Materia Prima', '2018-03-22 09:11:33'),
(504, 'aramirez', 'Desactivo el sustrato: a sustreit', 'Sustratos', 'Materia Prima', '2018-03-22 09:25:54'),
(505, 'aramirez', 'Elimino el sustrato: a sustreit', 'Sustratos', 'Materia Prima', '2018-03-22 09:26:00'),
(506, 'castillo66', 'Actualizo el cirel:  AT-56CIR', 'Juego de Cireles', 'Productos', '2018-03-22 11:53:52'),
(507, 'castillo66', 'Actualizo el cirel:  AT-56CIR', 'Juego de Cireles', 'Productos', '2018-03-22 12:40:32'),
(508, 'castillo66', 'Agrego el suaje:  cabsuaje', 'Suaje', 'Productos', '2018-03-22 13:04:33'),
(509, 'castillo66', 'Agrego el sustrato:  5u649ndfjg', 'Sustrato', 'Materia Prima', '2018-03-22 13:07:32'),
(510, 'castillo66', 'Desactivo el sustrato: sustrto metalizado', 'Sustratos', 'Materia Prima', '2018-03-22 13:08:02'),
(511, 'castillo66', 'Activo el sustrato: sustrto metalizado', 'Sustratos', 'Materia Prima', '2018-03-22 13:08:20'),
(512, 'aramirez', 'Desactivo el lote: 7003274PV400-92', 'Lotes', 'Materia Prima', '2018-03-22 13:49:57'),
(513, 'castillo66', 'Agrego la orden:  nose', 'Orden Compra', 'Logistica', '2018-03-23 08:11:56'),
(514, 'castillo66', 'Agrego una confirmaciÃ³n a la orden de compra:  nose', 'Requerimiento Producto', 'Logistica', '2018-03-23 08:12:48'),
(515, 'castillo66', 'Agrego una confirmaciÃ³n a la orden de compra:  nose', 'Requerimiento Producto', 'Logistica', '2018-03-23 08:13:37'),
(516, 'castillo66', 'Agrego un requerimiento a la orden de compra:  nose', 'Requerimiento Producto', 'Logistica', '2018-03-23 08:15:36'),
(517, 'castillo66', 'Agrego el embarque:  20180308001', 'Embarque', 'Logistica', '2018-03-23 08:17:32'),
(518, 'castillo66', 'Actualizo el suaje:  cabsuaje', 'Suajes', 'Productos', '2018-03-23 09:57:20'),
(519, 'castillo66', 'Actualizo el suaje:  cabsuaje', 'Suajes', 'Productos', '2018-03-23 09:58:22'),
(520, 'castillo66', 'Agrego 1 empaques, 27.272 millares al embarque:20180308001', 'Armado de Embarque', 'Logistica', '2018-03-23 10:10:30'),
(521, 'lauTI', 'Agrego el diseÃ±o:  frapuccino', 'DiseÃ±o', 'Productos', '2018-03-23 10:16:52'),
(522, 'lauTI', 'Agrego el sustrato:  sustocoffe', 'Sustrato', 'Materia Prima', '2018-03-23 10:21:18'),
(523, 'lauTI', 'Actualizo el sustrato:  sustratocoffebillante', 'Sustratos', 'Materia Prima', '2018-03-23 10:21:48'),
(524, 'lauTI', 'Agrego la Banda de Seguridad:  bandacseguridadcofee', 'Banda de Seguridad', 'Productos', '2018-03-23 10:23:26'),
(525, 'lauTI', 'Actualizo la Banda de Seguridad: bandacseguridadcofee', 'Banda de Seguridad', 'Productos', '2018-03-23 10:23:57'),
(526, 'lauTI', 'Actualizo la Banda de Seguridad: bandacseguridadcofee', 'Banda de Seguridad', 'Productos', '2018-03-23 10:24:10'),
(527, 'lauTI', 'Actualizo la Banda de Seguridad: bandacseguridadcofee', 'Banda de Seguridad', 'Productos', '2018-03-23 10:24:24'),
(528, 'lauTI', 'Agrego la Banda de Seguridad:  banda seguridad coffe ', 'Banda de Seguridad Por Proceso', 'Productos', '2018-03-23 10:41:45'),
(529, 'lauTI', 'Agrego el juego de cilindro:  01CilindroCoffe', 'Juegos Cilindro', 'Productos', '2018-03-23 11:15:12'),
(530, 'lauTI', 'Agrego el juego de cilindro:  01CilindroCoffe', 'Juegos Cilindro', 'Productos', '2018-03-23 11:17:22'),
(531, 'lauTI', 'Agrego el juego de cilindro:  01CilindroCoffe', 'Juegos Cilindro', 'Productos', '2018-03-23 11:24:25'),
(532, 'lauTI', 'Agrego un lote al bloque:  sustratocoffebillante 500mm', 'Lotes', 'Materia Prima', '2018-03-23 11:25:32'),
(533, 'lauTI', 'Actualizo el lote:  lote1', 'Lotes', 'Materia Prima', '2018-03-23 11:26:51'),
(534, 'lauTI', 'Desactivo el lote: lote1', 'Lotes', 'Materia Prima', '2018-03-23 11:27:11'),
(535, 'lauTI', 'Activo el lote: lote1', 'Lotes', 'Materia Prima', '2018-03-23 11:27:29'),
(536, 'lauTI', 'Desactivo el lote: lote1', 'Lotes', 'Materia Prima', '2018-03-23 11:27:33'),
(537, 'lauTI', 'Elimino el lote: lote1', 'Lotes', 'Materia Prima', '2018-03-23 11:27:39'),
(538, 'lauTI', 'Agrego un lote al bloque:  sustratocoffebillante 500mm', 'Lotes', 'Materia Prima', '2018-03-23 11:28:11'),
(539, 'castillo66', 'Agrego la Banda de Seguridad:  vdfbef', 'Banda de Seguridad Por Proceso', 'Productos', '2018-03-23 13:10:11'),
(540, 'castillo66', 'Agrego la Banda de Seguridad:  vdfbef', 'Banda de Seguridad Por Proceso', 'Productos', '2018-03-23 13:10:36'),
(541, 'castillo66', 'Agrego la Banda de Seguridad:  bfg4', 'Banda de Seguridad', 'Productos', '2018-03-23 13:10:53'),
(542, 'castillo66', 'Agrego el diseÃ±o:  cafesitoDisenio', 'DiseÃ±o', 'Productos', '2018-03-26 09:56:10'),
(543, 'castillo66', 'Agrego el juego de cilindro:  MX9053', 'Juegos Cilindro', 'Productos', '2018-03-26 10:05:52'),
(544, 'castillo66', 'Agrego el juego de cilindro:  JueCafe02', 'Juegos Cilindro', 'Productos', '2018-03-26 10:06:26'),
(545, 'castillo66', 'Agrego un lote al bloque:  sustratocoffebillante 500mm', 'Lotes', 'Materia Prima', '2018-03-26 10:08:30'),
(546, 'castillo66', 'Agrego la orden:  001', 'Orden Compra', 'Logistica', '2018-03-26 10:23:00'),
(547, 'castillo66', 'Agrego un requerimiento a la orden de compra:  001', 'Requerimiento Producto', 'Logistica', '2018-03-26 10:25:21'),
(548, 'castillo66', 'Agrego una confirmaciÃ³n a la orden de compra:  001', 'Requerimiento Producto', 'Logistica', '2018-03-26 10:26:05'),
(549, 'castillo66', 'Agrego una confirmaciÃ³n a la orden de compra:  001', 'Requerimiento Producto', 'Logistica', '2018-03-26 10:26:52'),
(550, 'castillo66', 'Actualizo el suaje:  SUJBONE1', 'Suajes', 'Productos', '2018-03-26 12:34:08'),
(551, 'castillo66', 'Agrego el diseÃ±o:  Leon', 'DiseÃ±o', 'Productos', '2018-03-27 08:22:43'),
(552, 'castillo66', 'Agrego un lote al bloque:  Polyester embosado C20 e-pura 640mm', 'Lotes', 'Materia Prima', '2018-03-27 08:33:23'),
(553, 'castillo66', 'Agrego el diseÃ±o:  RealMadridEti', 'DiseÃ±o', 'Productos', '2018-03-27 08:49:47'),
(554, 'castillo66', 'Agrego el diseÃ±o:  RealMadridEti', 'DiseÃ±o', 'Productos', '2018-03-27 08:50:12'),
(555, 'castillo66', 'Agrego el diseÃ±o:  BarcelonaEtiq', 'DiseÃ±o', 'Productos', '2018-03-27 12:36:51'),
(556, 'castillo66', 'Agrego el suaje:  BarcelonaSuaje', 'Suaje', 'Productos', '2018-03-27 12:46:30'),
(557, 'castillo66', 'Agrego un lote al bloque:  Polyester embosado C20 e-pura 640mm', 'Lotes', 'Materia Prima', '2018-03-27 12:47:58'),
(558, 'castillo66', 'Agrego un consumo al diseÃ±o:  BarcelonaEtiq', 'Consumos', 'Productos', '2018-03-28 11:48:14'),
(559, 'castillo66', 'Agrego la orden:  001', 'Orden Compra', 'Logistica', '2018-03-28 13:45:43'),
(560, 'castillo66', 'Agrego la orden:  02', 'Orden Compra', 'Logistica', '2018-03-28 13:45:56'),
(561, 'castillo66', 'Agrego un requerimiento a la orden de compra:  02', 'Requerimiento Producto', 'Logistica', '2018-03-28 13:46:28'),
(562, 'castillo66', 'Agrego una confirmaciÃ³n a la orden de compra:  02', 'Requerimiento Producto', 'Logistica', '2018-03-28 13:47:02'),
(563, 'castillo66', 'Agrego el embarque:  20180329001', 'Embarque', 'Logistica', '2018-03-28 13:47:50'),
(564, 'castillo66', 'Agrego 1 empaques, 4.47 millares al embarque:20180329001', 'Armado de Embarque', 'Logistica', '2018-03-28 13:48:30'),
(565, 'lauTI', 'Agrego al empleado Num:  007', 'Personal', 'Recursos Humanos', '2018-04-04 09:00:55'),
(566, 'lauTI', 'Agrego el diseÃ±o:  JugoDelValle', 'DiseÃ±o', 'Productos', '2018-04-04 09:12:05'),
(567, 'lauTI', 'Agrego la Banda de Seguridad:  BandaSegValle', 'Banda de Seguridad', 'Productos', '2018-04-04 09:33:56'),
(568, 'lauTI', 'Actualizo la Banda de Seguridad: BandaSegValle', 'Banda de Seguridad', 'Productos', '2018-04-04 09:34:29'),
(569, 'lauTI', 'Agrego un consumo al diseÃ±o:  JugoDelValle', 'Consumos', 'Productos', '2018-04-04 10:22:38'),
(570, 'lauTI', 'Agrego al cliente con RFC:  0122222', 'Clientes', 'Logistica', '2018-04-04 10:45:03'),
(571, 'lauTI', 'Agrego a la sucursal:  Sucursal 1', 'Sucursal', 'Logistica', '2018-04-04 10:46:07'),
(572, 'lauTI', 'Actualizo a la sucursal: Sucursal 1', 'Sucursal', 'Logistica', '2018-04-04 10:46:31'),
(573, 'lauTI', 'Agrego el juego de cilindro:  01JuegoCilindro', 'Juegos Cilindro', 'Productos', '2018-04-04 11:09:43'),
(574, 'lauTI', 'Agrego el juego de cilindro:  01JuegoCilindro', 'Juegos Cilindro', 'Productos', '2018-04-04 11:10:24'),
(575, 'lauTI', 'Agrego el juego de cilindro:  01JuegoCilindroJugos', 'Juegos Cilindro', 'Productos', '2018-04-04 11:12:37'),
(576, 'lauTI', 'Agrego el juego de cilindro:  01JuegoCilindroJugos', 'Juegos Cilindro', 'Productos', '2018-04-04 11:14:45'),
(577, 'lauTI', 'Agrego el juego de cilindro:  01JuegoCilindroJugos', 'Juegos Cilindro', 'Productos', '2018-04-04 11:17:56'),
(578, 'castillo66', 'Agrego el diseÃ±o:  Quindio 2018', 'DiseÃ±o', 'Productos', '2018-04-05 10:24:22'),
(579, 'castillo66', 'Agrego el sustrato:  A050418', 'Sustrato', 'Materia Prima', '2018-04-05 10:56:38'),
(580, 'castillo66', 'Agrego la Banda de Seguridad:  BandaSegValle', 'Banda de Seguridad Por Proceso', 'Productos', '2018-04-05 13:19:50'),
(581, 'castillo66', 'Agrego un lote al bloque:  Polyester embosado C30 gota 320 mm', 'Lotes', 'Materia Prima', '2018-04-05 13:21:55'),
(582, 'castillo66', 'Actualizo el juego de cilindro: 01JuegoCilindroJugos', 'Juegos Cilindro', 'Productos', '2018-04-09 08:53:51'),
(583, 'castillo66', 'Actualizo el juego de cilindro: 01JuegoCilindroJugos', 'Juegos Cilindro', 'Productos', '2018-04-09 08:58:36'),
(584, 'castillo66', 'Actualizo el juego de cilindro: 01JuegoCilindroJugos', 'Juegos Cilindro', 'Productos', '2018-04-09 09:03:11'),
(585, 'castillo66', 'Actualizo el juego de cilindro: 01JuegoCilindroJugos', 'Juegos Cilindro', 'Productos', '2018-04-09 09:04:03'),
(586, 'castillo66', 'Actualizo el juego de cilindro: 01JuegoCilindroJugos', 'Juegos Cilindro', 'Productos', '2018-04-09 09:04:33'),
(587, 'castillo66', 'Actualizo el juego de cilindro: 01JuegoCilindroJugos', 'Juegos Cilindro', 'Productos', '2018-04-09 09:04:55'),
(588, 'castillo66', 'Actualizo el juego de cilindro: 01JuegoCilindroJugos', 'Juegos Cilindro', 'Productos', '2018-04-09 09:06:01'),
(589, 'castillo66', 'Actualizo el juego de cilindro: 01JuegoCilindroJugos', 'Juegos Cilindro', 'Productos', '2018-04-09 09:06:18'),
(590, 'castillo66', 'Actualizo el juego de cilindro: 01JuegoCilindroJugos', 'Juegos Cilindro', 'Productos', '2018-04-09 09:06:43'),
(591, 'castillo66', 'Actualizo el juego de cilindro: 01JuegoCilindroJugos', 'Juegos Cilindro', 'Productos', '2018-04-09 09:07:02'),
(592, 'castillo66', 'Actualizo el juego de cilindro: 01JuegoCilindroJugos', 'Juegos Cilindro', 'Productos', '2018-04-09 09:07:08'),
(593, 'castillo66', 'Actualizo el juego de cilindro: 01JuegoCilindroJugos', 'Juegos Cilindro', 'Productos', '2018-04-09 09:08:22'),
(594, 'castillo66', 'Actualizo el juego de cilindro: 01JuegoCilindroJugos', 'Juegos Cilindro', 'Productos', '2018-04-09 09:11:10'),
(595, 'castillo66', 'Actualizo el juego de cilindro: 01JuegoCilindroJugos', 'Juegos Cilindro', 'Productos', '2018-04-09 09:11:21'),
(596, 'castillo66', 'Actualizo el juego de cilindro: 01JuegoCilindroJugos', 'Juegos Cilindro', 'Productos', '2018-04-09 09:11:38'),
(597, 'castillo66', 'Actualizo el juego de cilindro: 01JuegoCilindroJugos', 'Juegos Cilindro', 'Productos', '2018-04-09 09:13:19'),
(598, 'castillo66', 'Agrego el juego de cilindro:  THEJUG02', 'Juegos Cilindro', 'Productos', '2018-04-09 10:08:16'),
(599, 'castillo66', 'Agrego el juego de cilindro:  THEJUG02', 'Juegos Cilindro', 'Productos', '2018-04-09 10:09:34'),
(600, 'castillo66', 'Agrego el juego de cilindro:  THEJUG02', 'Juegos Cilindro', 'Productos', '2018-04-09 10:09:46'),
(601, 'castillo66', 'Agrego el juego de cilindro:  THEJUG02', 'Juegos Cilindro', 'Productos', '2018-04-09 10:10:21'),
(602, 'castillo66', 'Agrego el juego de cilindro:  THEJUG02', 'Juegos Cilindro', 'Productos', '2018-04-09 10:10:34'),
(603, 'castillo66', 'Agrego el cirel:  Menos675', 'Juego de Cireles', 'Productos', '2018-04-10 09:15:00'),
(604, 'castillo66', 'Actualizo al empleado Num: 06', 'Personal', 'Recursos Humanos', '2018-04-10 13:24:00'),
(605, 'castillo66', 'Agrego el cirel:  CIRMINUS92', 'Juego de Cireles', 'Productos', '2018-04-11 09:01:07'),
(606, 'castillo66', 'Agrego al cliente con RFC:  876543', 'Clientes', 'Logistica', '2018-04-11 09:12:43'),
(607, 'castillo66', 'Desactivo al cliente con RFC: 876543', 'Clientes', 'Logistica', '2018-04-11 09:12:54'),
(608, 'castillo66', 'Activo al cliente con RFC: 876543', 'Clientes', 'Logistica', '2018-04-11 09:12:59'),
(609, 'castillo66', 'Agrego al contacto:  Estevan', 'Contacto Cliente', 'Logistica', '2018-04-11 09:13:37'),
(610, 'castillo66', 'Desactivo al contacto: Estevan', 'Contacto Cliente', 'Logistica', '2018-04-11 09:13:42'),
(611, 'castillo66', 'Activo al contacto: Estevan', 'Contacto Cliente', 'Logistica', '2018-04-11 09:13:45'),
(612, 'castillo66', 'Actualizo al contacto: Estevan', 'Contacto Cliente', 'Logistica', '2018-04-11 09:13:59'),
(613, 'castillo66', 'Actualizo al contacto: Estevan', 'Contacto Cliente', 'Logistica', '2018-04-11 09:14:03'),
(614, 'castillo66', 'Agrego a la sucursal:  Sucursal buenavista', 'Sucursal', 'Logistica', '2018-04-11 09:15:12'),
(615, 'castillo66', 'Agrego al contacto:  Enrique Torres', 'Contacto Sucursal', 'Logistica', '2018-04-11 09:17:12'),
(616, 'castillo66', 'Desactivo al contacto: Enrique Torres', 'Contacto Sucursal', 'Logistica', '2018-04-11 09:17:17'),
(617, 'castillo66', 'Activo al contacto: Enrique Torres', 'Contacto Sucursal', 'Logistica', '2018-04-11 09:17:23'),
(618, 'castillo66', 'Actualizo al contacto: Enrique Torres', 'Contacto Sucursal', 'Logistica', '2018-04-11 09:17:38'),
(619, 'castillo66', 'Actualizo al contacto: Enrique Torres', 'Contacto Sucursal', 'Logistica', '2018-04-11 09:17:45'),
(620, 'castillo66', 'Actualizo al contacto: Enrique Torres', 'Contacto Sucursal', 'Logistica', '2018-04-11 09:17:55'),
(621, 'castillo66', 'Agrego la orden:  ORDEN01Q', 'Orden Compra', 'Logistica', '2018-04-11 09:18:44'),
(622, 'castillo66', 'Agrego un requerimiento a la orden de compra:  ORDEN01Q', 'Requerimiento Producto', 'Logistica', '2018-04-11 09:36:24'),
(623, 'castillo66', 'Agrego una confirmaciÃ³n a la orden de compra:  ORDEN01Q', 'Requerimiento Producto', 'Logistica', '2018-04-11 09:37:42'),
(624, 'castillo66', 'Agrego una confirmaciÃ³n a la orden de compra:  ORDEN01Q', 'Requerimiento Producto', 'Logistica', '2018-04-11 09:38:18'),
(625, 'castillo66', 'Agrego el embarque:  20180412001', 'Embarque', 'Logistica', '2018-04-11 09:40:43'),
(626, 'castillo66', 'Desactivo el embarque: 20180412001', 'Embarque', 'Logistica', '2018-04-11 09:41:03'),
(627, 'castillo66', 'Elimino el embarque: 20180412001', 'Embarque', 'Logistica', '2018-04-11 09:41:09'),
(628, 'castillo66', 'Agrego el embarque:  20180419001', 'Embarque', 'Logistica', '2018-04-11 09:42:10'),
(629, 'castillo66', 'Agrego el suaje:  SUJMINHUMAN01', 'Suaje', 'Productos', '2018-04-11 10:59:56'),
(630, 'castillo66', 'Desactivo el suaje: SUJMINHUMAN01', 'Suajes', 'Productos', '2018-04-11 11:01:58'),
(631, 'castillo66', 'Agrego 3 empaques, 73.597 millares al embarque:20180419001', 'Armado de Embarque', 'Logistica', '2018-04-12 11:12:59'),
(632, 'castillo66', 'Agrego una DevoluciÃ³n, Folio:  1804001, Tipo: Empaque', 'Devoluciones', 'Logistica', '2018-04-12 11:24:39'),
(633, 'castillo66', 'Asingo Datos de Reporte a la DevoluciÃ³n, Folio: 1804001, Tipo: Empaque', 'Devoluciones', 'Logistica', '2018-04-12 11:25:38'),
(634, 'castillo66', 'Retorno el empaque Q2 (8.700 millares) del embarque: 20180419001', 'Armado de Embarque', 'Logistica', '2018-04-12 12:03:41'),
(635, 'castillo66', 'Retorno el empaque Q3 (46.757 millares) del embarque: 20180419001', 'Armado de Embarque', 'Logistica', '2018-04-12 12:03:52'),
(636, 'castillo66', 'Agrego 0 empaques, 0 millares al embarque:20180419001', 'Armado de Embarque', 'Logistica', '2018-04-12 12:03:54'),
(637, 'castillo66', 'Agrego 1 empaques, 8.7 millares al embarque:20180419001', 'Armado de Embarque', 'Logistica', '2018-04-12 12:21:47'),
(638, 'castillo66', 'Agrego 2 empaques, 55.457 millares al embarque:20180419001', 'Armado de Embarque', 'Logistica', '2018-04-12 12:27:34'),
(639, 'castillo66', 'Agrego 0 empaques, 0 millares al embarque:20180419001', 'Armado de Embarque', 'Logistica', '2018-04-12 12:27:49'),
(640, 'castillo66', 'Retorno el empaque Q1 (8.700 millares) del embarque: 20180419001', 'Armado de Embarque', 'Logistica', '2018-04-12 12:27:56'),
(641, 'castillo66', 'Retorno el empaque Q3 (46.757 millares) del embarque: 20180419001', 'Armado de Embarque', 'Logistica', '2018-04-12 12:27:58'),
(642, 'castillo66', 'Agrego 0 empaques, 0 millares al embarque:20180419001', 'Armado de Embarque', 'Logistica', '2018-04-12 12:27:59'),
(643, 'castillo66', 'Agrego 0 empaques, 0 millares al embarque:20180419001', 'Armado de Embarque', 'Logistica', '2018-04-12 12:28:11'),
(644, 'castillo66', 'Agrego la mÃ¡quina:  mvkdflv', 'MÃ¡quinas', 'ProducciÃ³n', '2018-04-13 10:06:31'),
(645, 'castillo66', 'Desactivo la mÃ¡quina: mvkdflv', 'MÃ¡quinas', 'ProducciÃ³n', '2018-04-13 10:06:35'),
(646, 'castillo66', 'Agrego la mÃ¡quina:  2426', 'MÃ¡quinas', 'ProducciÃ³n', '2018-04-13 10:18:22'),
(647, 'castillo66', 'Desactivo la mÃ¡quina: 2426', 'MÃ¡quinas', 'ProducciÃ³n', '2018-04-13 10:18:26'),
(648, 'castillo66', 'Agrego la mÃ¡quina:  dghd', 'MÃ¡quinas', 'ProducciÃ³n', '2018-04-13 10:18:46'),
(649, 'castillo66', 'Desactivo la mÃ¡quina: dghd', 'MÃ¡quinas', 'ProducciÃ³n', '2018-04-13 10:18:52'),
(650, 'castillo66', 'Agrego la mÃ¡quina:  dsgs', 'MÃ¡quinas', 'ProducciÃ³n', '2018-04-13 10:53:08'),
(651, 'castillo66', 'Desactivo la mÃ¡quina: dsgs', 'MÃ¡quinas', 'ProducciÃ³n', '2018-04-13 10:53:11'),
(652, 'castillo66', 'Agrego la mÃ¡quina:  nvdjfk', 'MÃ¡quinas', 'ProducciÃ³n', '2018-04-13 11:04:01'),
(653, 'castillo66', 'Desactivo la mÃ¡quina: nvdjfk', 'MÃ¡quinas', 'ProducciÃ³n', '2018-04-13 11:04:05'),
(654, 'castillo66', 'Agrego la mÃ¡quina:  gfnkg', 'MÃ¡quinas', 'ProducciÃ³n', '2018-04-13 11:07:50'),
(655, 'castillo66', 'Desactivo la mÃ¡quina: gfnkg', 'MÃ¡quinas', 'ProducciÃ³n', '2018-04-13 11:08:12'),
(656, 'castillo66', 'Elimino la mÃ¡quina: gfnkg', 'MÃ¡quinas', 'ProducciÃ³n', '2018-04-13 11:08:18'),
(657, 'castillo66', 'Elimino la mÃ¡quina: nvdjfk', 'MÃ¡quinas', 'ProducciÃ³n', '2018-04-13 11:08:23'),
(658, 'castillo66', 'Elimino la mÃ¡quina: dsgs', 'MÃ¡quinas', 'ProducciÃ³n', '2018-04-13 11:08:27'),
(659, 'castillo66', 'Elimino la mÃ¡quina: dghd', 'MÃ¡quinas', 'ProducciÃ³n', '2018-04-13 11:08:31'),
(660, 'castillo66', 'Elimino la mÃ¡quina: 2426', 'MÃ¡quinas', 'ProducciÃ³n', '2018-04-13 11:08:36'),
(661, 'castillo66', 'Elimino la mÃ¡quina: mvkdflv', 'MÃ¡quinas', 'ProducciÃ³n', '2018-04-13 11:08:41'),
(662, 'castillo66', 'Agrego el cirel:  dghd', 'Juego de Cireles', 'Productos', '2018-04-13 13:17:57'),
(663, 'castillo66', 'Desactivo el cirel: dghd', 'Juego de Cireles', 'Productos', '2018-04-13 13:18:00'),
(664, 'castillo66', 'Elimino el cirel: dghd', 'Juego de Cireles', 'Productos', '2018-04-13 13:18:04'),
(665, 'castillo66', 'Agrego el cirel:  JC99', 'Juego de Cireles', 'Productos', '2018-04-13 13:19:45'),
(666, 'castillo66', 'Agrego el suaje:  SF009', 'Suaje', 'Productos', '2018-04-13 13:25:56'),
(667, 'castillo66', 'Agrego un lote al bloque:  bopp bco autoadherible', 'Lotes', 'Materia Prima', '2018-04-13 13:27:55'),
(668, 'castillo66', 'Agrego el diseÃ±o:  schneider', 'DiseÃ±o', 'Productos', '2018-04-17 08:33:53'),
(669, 'castillo66', 'Agrego el suaje:  Suj5', 'Suaje', 'Productos', '2018-04-18 08:23:07'),
(670, 'castillo66', 'Desactivo el suaje: Suj5', 'Suajes', 'Productos', '2018-04-18 08:23:58'),
(671, 'castillo66', 'Agrego el suaje:  sujeosd', 'Suaje', 'Productos', '2018-04-18 08:24:27'),
(672, 'castillo66', 'Agrego el suaje:  9df', 'Suaje', 'Productos', '2018-04-18 09:04:31'),
(673, 'castillo66', 'Desactivo el suaje: 9df', 'Suajes', 'Productos', '2018-04-18 09:59:48'),
(674, 'castillo66', 'Desactivo el suaje: sujeosd', 'Suajes', 'Productos', '2018-04-18 09:59:53'),
(675, 'castillo66', 'Actualizo el suaje:  SF009', 'Suajes', 'Productos', '2018-04-18 10:02:40'),
(676, 'castillo66', 'Elimino el suaje: Suj5', 'Suaje', 'Productos', '2018-04-18 10:03:10'),
(677, 'castillo66', 'Elimino el suaje: ', 'Suaje', 'Productos', '2018-04-18 10:03:12'),
(678, 'castillo66', 'Elimino el suaje: sujeosd', 'Suaje', 'Productos', '2018-04-18 10:03:14'),
(679, 'castillo66', 'Elimino el suaje: ', 'Suaje', 'Productos', '2018-04-18 10:03:16'),
(680, 'castillo66', 'Elimino el suaje: 9df', 'Suaje', 'Productos', '2018-04-18 10:03:18'),
(681, 'castillo66', 'Agrego al cliente con RFC:  GEP150516R23', 'Clientes', 'Logistica', '2018-04-21 09:04:21'),
(682, 'castillo66', 'Agrego a la sucursal:  Claveria', 'Sucursal', 'Logistica', '2018-04-21 09:07:17'),
(683, 'castillo66', 'Agrego al contacto:  Lidia GÃ³mez', 'Contacto Cliente', 'Logistica', '2018-04-21 09:08:43'),
(684, 'castillo66', 'Agrego al contacto:  Jaime Torres', 'Contacto Sucursal', 'Logistica', '2018-04-21 09:10:33'),
(685, 'castillo66', 'Agrego la orden:  45618', 'Orden Compra', 'Logistica', '2018-04-21 09:13:15'),
(686, 'castillo66', 'Agrego un requerimiento a la orden de compra:  45618', 'Requerimiento Producto', 'Logistica', '2018-04-21 09:23:11'),
(687, 'castillo66', 'Desactivo un requerimiento con la orden de compra: 45618', 'Requerimiento Producto', 'Logistica', '2018-04-21 09:32:37'),
(688, 'castillo66', 'Agrego un requerimiento a la orden de compra:  45618', 'Requerimiento Producto', 'Logistica', '2018-04-21 09:33:48'),
(689, 'castillo66', 'Agrego una confirmaciÃ³n a la orden de compra:  45618', 'Requerimiento Producto', 'Logistica', '2018-04-21 09:39:55'),
(690, 'castillo66', 'Agrego el embarque:  20180421001', 'Embarque', 'Logistica', '2018-04-21 09:51:47'),
(691, 'castillo66', 'Agrego 0 empaques, 0 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-04-21 10:05:38'),
(692, 'castillo66', 'Agrego al empleado Num:  456', 'Personal', 'Recursos Humanos', '2018-04-23 08:33:11'),
(693, 'castillo66', 'Agrego 1 empaques, 11 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 11:51:01'),
(694, 'castillo66', 'Agrego 0 empaques, 0 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 11:51:34'),
(695, 'castillo66', 'Agrego 0 empaques, 0 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 11:51:45'),
(696, 'castillo66', 'Agrego 0 empaques, 0 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 11:52:01'),
(697, 'castillo66', 'Agrego 0 empaques, 0 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 11:52:11'),
(698, 'castillo66', 'Agrego 0 empaques, 0 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 11:52:17'),
(699, 'castillo66', 'Agrego 0 empaques, 0 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 11:52:24'),
(700, 'castillo66', 'Agrego 0 empaques, 0 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 11:52:29'),
(701, 'castillo66', 'Agrego 0 empaques, 0 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 11:52:43'),
(702, 'castillo66', 'Agrego 0 empaques, 0 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 11:53:16'),
(703, 'castillo66', 'Agrego 0 empaques, 0 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 12:12:24'),
(704, 'castillo66', 'Agrego 0 empaques, 0 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 12:12:33'),
(705, 'castillo66', 'Agrego 0 empaques, 0 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 12:14:29'),
(706, 'castillo66', 'Agrego 1 empaques, 9.4 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 13:06:44'),
(707, 'castillo66', 'Retorno el empaque Q1 (11.000 millares) del embarque: 20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 13:07:20'),
(708, 'castillo66', 'Agrego 1 empaques, 11 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 13:08:00'),
(709, 'castillo66', 'Agrego 1 empaques, 8.2 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 13:09:16'),
(710, 'castillo66', 'Agrego 1 empaques, 8.2 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 13:12:53'),
(711, 'castillo66', 'Agrego 1 empaques, 8.2 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 13:13:06'),
(712, 'castillo66', 'Retorno el empaque Q1 (11.000 millares) del embarque: 20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 13:13:13'),
(713, 'castillo66', 'Agrego 1 empaques, 11 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 13:29:28'),
(714, 'castillo66', 'Retorno el empaque Q1 (11.000 millares) del embarque: 20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 13:29:31'),
(715, 'castillo66', 'Retorno el empaque Q2 (9.400 millares) del embarque: 20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 13:29:32'),
(716, 'castillo66', 'Retorno el empaque Q3 (8.200 millares) del embarque: 20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 13:29:33'),
(717, 'castillo66', 'Agrego 1 empaques, 11 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 13:30:44'),
(718, 'castillo66', 'Agrego 1 empaques, 9.4 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 13:30:50'),
(719, 'castillo66', 'Retorno el empaque Q1 (11.000 millares) del embarque: 20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 13:30:52'),
(720, 'castillo66', 'Retorno el empaque Q2 (9.400 millares) del embarque: 20180421001', 'Armado de Embarque', 'Logistica', '2018-04-23 13:30:53'),
(721, 'castillo66', 'Desactivo al empleado Num: 456', 'Personal', 'Recursos Humanos', '2018-04-24 08:23:29'),
(722, 'castillo66', 'Agrego el diseÃ±o:  Red chiva cola', 'DiseÃ±o', 'Productos', '2018-04-27 08:54:28'),
(723, 'castillo66', 'Agrego un consumo al diseÃ±o:  Chivacola', 'Consumos', 'Productos', '2018-04-27 08:58:07'),
(724, 'castillo66', 'Agrego el diseÃ±o:  Red Cola Chiva', 'DiseÃ±o', 'Productos', '2018-04-27 09:00:47'),
(725, 'castillo66', 'Agrego el diseÃ±o:  Jumex', 'DiseÃ±o', 'Productos', '2018-04-27 09:04:01'),
(726, 'castillo66', 'Agrego el diseÃ±o:  Red Cola Chiva', 'DiseÃ±o', 'Productos', '2018-04-27 09:24:53'),
(727, 'castillo66', 'Agrego el diseÃ±o:  Blue', 'DiseÃ±o', 'Productos', '2018-04-27 09:26:33'),
(728, 'castillo66', 'Agrego el juego de cilindro:  898989', 'Juegos Cilindro', 'Productos', '2018-04-27 09:31:58'),
(729, 'castillo66', 'Agrego un lote al bloque:  Polyester embosado C20 e-pura 640mm', 'Lotes', 'Materia Prima', '2018-04-27 09:36:23'),
(730, 'castillo66', 'Agrego el diseÃ±o:  Jumex', 'DiseÃ±o', 'Productos', '2018-04-27 09:55:34'),
(731, 'castillo66', 'Agrego el diseÃ±o:  Gatorade', 'DiseÃ±o', 'Productos', '2018-04-27 10:01:23'),
(732, 'castillo66', 'Agrego el juego de cilindro:  67567576', 'Juegos Cilindro', 'Productos', '2018-04-27 10:25:49'),
(733, 'castillo66', 'Agrego un lote al bloque:  PTG transparente C37 455 mm', 'Lotes', 'Materia Prima', '2018-04-27 10:26:49'),
(734, 'castillo66', 'Desactivo el diseÃ±o: Red Cola Chiva', 'DiseÃ±o', 'Productos', '2018-04-27 12:24:14'),
(735, 'castillo66', 'Elimino el diseÃ±o: Red Cola Chiva', 'DiseÃ±o', 'Productos', '2018-04-27 12:24:21'),
(736, 'castillo66', 'Elimino el diseÃ±o: Pruebita', 'DiseÃ±o', 'Productos', '2018-04-27 12:28:20'),
(737, 'castillo66', 'Elimino el diseÃ±o: chileylimon', 'DiseÃ±o', 'Productos', '2018-04-27 12:28:25'),
(738, 'aramirez', 'Desactivo el cirel: JC99', 'Juego de Cireles', 'Productos', '2018-05-03 11:40:16'),
(739, 'aramirez', 'Desactivo el suaje: SF009', 'Suajes', 'Productos', '2018-05-03 11:41:20'),
(740, 'aramirez', 'Desactivo el lote: 7003274PV400-93', 'Lotes', 'Materia Prima', '2018-05-03 11:55:13'),
(741, 'aramirez', 'Activo el lote: 7003274PV400-93', 'Lotes', 'Materia Prima', '2018-05-03 11:55:24'),
(742, 'aramirez', 'Desactivo el elemento: TINESEPV405RF00', 'Elementos', 'Materia Prima', '2018-05-03 11:58:30'),
(743, 'aramirez', 'Activo el elemento: TINESEPV405RF00', 'Elementos', 'Materia Prima', '2018-05-03 11:58:36'),
(744, 'castillo66', 'Agrego 4 empaques, 38.5 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-05-03 13:23:36'),
(745, 'castillo66', 'Agrego un lote al bloque:  PTG transparente C37 455 mm', 'Lotes', 'Materia Prima', '2018-05-08 08:37:10'),
(746, 'castillo66', 'Agrego la orden:  645342', 'Orden Compra', 'Logistica', '2018-05-08 09:28:07'),
(747, 'castillo66', 'Agrego un requerimiento a la orden de compra:  645342', 'Requerimiento Producto', 'Logistica', '2018-05-08 09:28:59'),
(748, 'castillo66', 'Agrego una confirmaciÃ³n a la orden de compra:  645342', 'Requerimiento Producto', 'Logistica', '2018-05-08 09:30:14'),
(749, 'castillo66', 'Agrego el embarque:  20180512001', 'Embarque', 'Logistica', '2018-05-08 09:31:27'),
(750, 'castillo66', 'Agrego el embarque:  20180517001', 'Embarque', 'Logistica', '2018-05-08 09:49:22'),
(751, 'castillo66', 'Agrego una confirmaciÃ³n a la orden de compra:  645342', 'Requerimiento Producto', 'Logistica', '2018-05-08 09:51:14'),
(752, 'castillo66', 'Agrego 3 empaques, 44.642 millares al embarque:20180517001', 'Armado de Embarque', 'Logistica', '2018-05-08 10:13:02'),
(753, 'castillo66', 'Agrego 9 empaques, 10 millares al embarque:20180512001', 'Armado de Embarque', 'Logistica', '2018-05-08 10:26:31'),
(754, 'castillo66', 'Agrego 3 empaques, 3.5 millares al embarque:20180512001', 'Armado de Embarque', 'Logistica', '2018-05-08 10:27:49'),
(755, 'castillo66', 'Agrego un lote al bloque:  bopp bco autoadherible', 'Lotes', 'Materia Prima', '2018-05-09 13:21:53'),
(756, 'castillo66', 'Actualizo la mÃ¡quina: MTFL01', 'MÃ¡quinas', 'ProducciÃ³n', '2018-05-10 08:30:33'),
(757, 'castillo66', 'Retorno el empaque Q4 (9.900 millares) del embarque: 20180421001', 'Armado de Embarque', 'Logistica', '2018-05-10 08:45:17'),
(758, 'castillo66', 'Agrego 1 empaques, 9.9 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-05-10 08:49:43'),
(759, 'castillo66', 'Retorno el empaque Q4 (9.900 millares) del embarque: 20180421001', 'Armado de Embarque', 'Logistica', '2018-05-10 09:05:11'),
(760, 'castillo66', 'Agrego 1 empaques, 9.9 millares al embarque:20180421001', 'Armado de Embarque', 'Logistica', '2018-05-10 09:14:03'),
(761, 'castillo66', 'Agrego el pantone:  Pantone 455 C', 'Pantones', 'Materia Prima', '2018-05-10 09:21:53'),
(762, 'castillo66', 'Desactivo el pantone: Pantone 455 C', 'Pantones', 'Materia Prima', '2018-05-10 09:22:31'),
(763, 'castillo66', 'Agrego el diseÃ±o:  Del valle', 'DiseÃ±o', 'Productos', '2018-05-10 09:28:36'),
(764, 'castillo66', 'Agrego la orden:  4569', 'Orden Compra', 'Logistica', '2018-05-10 09:59:05'),
(765, 'castillo66', 'Agrego un requerimiento a la orden de compra:  4569', 'Requerimiento Producto', 'Logistica', '2018-05-10 09:59:36'),
(766, 'castillo66', 'Agrego una confirmaciÃ³n a la orden de compra:  4569', 'Requerimiento Producto', 'Logistica', '2018-05-10 10:00:25'),
(767, 'castillo66', 'Finalizo el embarque: 20180517001', 'Armado de Embarque', 'Logistica', '2018-05-15 12:20:44'),
(768, 'castillo66', 'Abrio el embarque: 20180517001', 'Embarque', 'Logistica', '2018-05-15 12:21:04'),
(769, 'castillo66', 'Finalizo el embarque: 20180517001', 'Armado de Embarque', 'Logistica', '2018-05-15 12:21:08'),
(770, 'castillo66', 'Agrego el juego de cilindro:  ni989', 'Juegos Cilindro', 'Productos', '2018-05-18 10:12:18'),
(771, 'castillo66', 'Agrego el juego de cilindro:  ni989', 'Juegos Cilindro', 'Productos', '2018-05-18 10:16:07'),
(772, 'castillo66', 'Agrego el juego de cilindro:  ni989', 'Juegos Cilindro', 'Productos', '2018-05-18 10:20:33'),
(773, 'aramirez', 'Desactivo el lote: 7003274PV400-91', 'Lotes', 'Materia Prima', '2018-05-21 12:22:41'),
(774, 'aramirez', 'Activo el lote: 7003274PV400-91', 'Lotes', 'Materia Prima', '2018-05-21 12:22:49'),
(775, 'aramirez', 'Activo el lote: 7003274PV400-91', 'Lotes', 'Materia Prima', '2018-05-21 12:22:49'),
(776, 'castillo66', 'Agrego al empleado Num:  235', 'Personal', 'Recursos Humanos', '2018-05-21 13:31:59'),
(777, 'castillo66', 'Agrego al empleado Num:  566', 'Personal', 'Recursos Humanos', '2018-05-21 13:33:35'),
(778, 'castillo66', 'Desactivo al empleado Num: 566', 'Personal', 'Recursos Humanos', '2018-05-21 13:33:49'),
(779, 'castillo66', 'Desactivo al empleado Num: 235', 'Personal', 'Recursos Humanos', '2018-05-21 13:33:52'),
(780, 'castillo66', 'Elimino al empleado Num: 566', 'Personal', 'Recursos Humanos', '2018-05-21 13:33:56'),
(781, 'castillo66', 'Elimino al empleado Num: 235', 'Personal', 'Recursos Humanos', '2018-05-21 13:34:02'),
(782, 'castillo66', 'Agrego el diseÃ±o:  Epura2', 'DiseÃ±o', 'Productos', '2018-05-22 08:55:32'),
(783, 'castillo66', 'Agrego el pantone:  Pantone 534 C', 'Pantones', 'Materia Prima', '2018-05-22 08:58:34'),
(784, 'castillo66', 'Agrego el pantone:  Pantone 2945 C', 'Pantones', 'Materia Prima', '2018-05-22 09:01:43'),
(785, 'castillo66', 'Agrego el pantone:  Pantone 185 C', 'Pantones', 'Materia Prima', '2018-05-22 09:03:05'),
(786, 'castillo66', 'Actualizo el pantone:  Pantone white C', 'Pantones', 'Materia Prima', '2018-05-22 09:10:55'),
(787, 'castillo66', 'Agrego el pantone:  Process White C Alto C', 'Pantones', 'Materia Prima', '2018-05-22 09:11:21'),
(788, 'castillo66', 'Agrego el pantone:  Pantone 7712 C', 'Pantones', 'Materia Prima', '2018-05-22 09:12:47'),
(789, 'castillo66', 'Agrego a la sucursal:  Planta Merida', 'Sucursal', 'Logistica', '2018-05-22 09:48:40'),
(790, 'castillo66', 'Agrego la orden:  4562', 'Orden Compra', 'Logistica', '2018-05-22 09:49:09'),
(791, 'castillo66', 'Agrego un requerimiento a la orden de compra:  4562', 'Requerimiento Producto', 'Logistica', '2018-05-22 09:49:46'),
(792, 'castillo66', 'Agrego una confirmaciÃ³n a la orden de compra:  4562', 'Requerimiento Producto', 'Logistica', '2018-05-22 09:52:48'),
(793, 'castillo66', 'Agrego el juego de cilindro:  W7112905', 'Juegos Cilindro', 'Productos', '2018-05-24 08:38:05'),
(794, 'castillo66', 'Actualizo el juego de cilindro: W7112905', 'Juegos Cilindro', 'Productos', '2018-05-29 11:12:04'),
(795, 'castillo66', 'Actualizo el juego de cilindro: W7112905', 'Juegos Cilindro', 'Productos', '2018-05-29 11:14:04'),
(796, 'castillo66', 'Desactivo el lote: 7001674ES320-2', 'Lotes', 'Materia Prima', '2018-05-30 10:25:39'),
(797, 'castillo66', 'Activo el lote: 7001674ES320-2', 'Lotes', 'Materia Prima', '2018-05-30 10:25:44'),
(798, 'castillo66', 'Desactivo el lote: 7001674ES320-2', 'Lotes', 'Materia Prima', '2018-05-30 10:26:01'),
(799, 'castillo66', 'Activo el lote: 7001674ES320-2', 'Lotes', 'Materia Prima', '2018-05-30 10:26:27'),
(800, 'castillo66', 'Desactivo el lote: 7001674ES320-2', 'Lotes', 'Materia Prima', '2018-05-30 10:27:52'),
(801, 'castillo66', 'Activo el lote: 7001674ES320-2', 'Lotes', 'Materia Prima', '2018-05-30 10:28:08'),
(802, 'castillo66', 'Agrego el diseÃ±o:  fin', 'DiseÃ±o', 'Productos', '2018-06-06 10:23:37'),
(803, 'castillo66', 'Desactivo el diseÃ±o: fin', 'DiseÃ±o', 'Productos', '2018-06-06 10:23:54'),
(804, 'castillo66', 'Elimino el diseÃ±o: fin', 'DiseÃ±o', 'Productos', '2018-06-06 10:23:59'),
(805, 'castillo66', 'Desactivo al empleado Num: 0', 'Personal', 'Recursos Humanos', '2018-06-07 09:26:01'),
(806, 'castillo66', 'Desactivo al empleado Num: 058', 'Personal', 'Recursos Humanos', '2018-06-07 09:26:03'),
(807, 'castillo66', 'Desactivo al empleado Num: 066', 'Personal', 'Recursos Humanos', '2018-06-07 09:26:06'),
(808, 'castillo66', 'Desactivo al empleado Num: 67', 'Personal', 'Recursos Humanos', '2018-06-07 09:26:07'),
(809, 'castillo66', 'Desactivo al empleado Num: 033', 'Personal', 'Recursos Humanos', '2018-06-07 09:26:07'),
(810, 'castillo66', 'Desactivo al empleado Num: 007', 'Personal', 'Recursos Humanos', '2018-06-07 09:26:12'),
(811, 'castillo66', 'Desactivo al empleado Num: 003', 'Personal', 'Recursos Humanos', '2018-06-07 09:26:15'),
(812, 'castillo66', 'Desactivo al empleado Num: 34', 'Personal', 'Recursos Humanos', '2018-06-07 09:26:24'),
(813, 'castillo66', 'Desactivo al empleado Num: 028', 'Personal', 'Recursos Humanos', '2018-06-07 09:26:25'),
(814, 'castillo66', 'Desactivo al empleado Num: 011', 'Personal', 'Recursos Humanos', '2018-06-07 09:27:03'),
(815, 'castillo66', 'Desactivo al empleado Num: 010', 'Personal', 'Recursos Humanos', '2018-06-07 09:27:07'),
(816, 'castillo66', 'Desactivo al empleado Num: 004', 'Personal', 'Recursos Humanos', '2018-06-07 09:27:09'),
(817, 'castillo66', 'Agrego al empleado Num:  230', 'Personal', 'Recursos Humanos', '2018-06-07 09:29:49'),
(818, 'castillo66', 'Agrego al empleado Num:  37 ', 'Personal', 'Recursos Humanos', '2018-06-07 09:31:53'),
(819, 'castillo66', 'Actualizo al empleado Num: 230', 'Personal', 'Recursos Humanos', '2018-06-07 09:32:55'),
(820, 'castillo66', 'Agrego al empleado Num:  61', 'Personal', 'Recursos Humanos', '2018-06-07 09:34:49'),
(821, 'castillo66', 'Agrego al empleado Num:  018', 'Personal', 'Recursos Humanos', '2018-06-07 09:35:37'),
(822, 'asaldaÃ±a', 'Agrego el sustrato:  PEL030', 'Sustrato', 'Materia Prima', '2018-06-07 12:02:53'),
(823, 'alozano', 'Agrego el diseÃ±o:  Comex', 'DiseÃ±o', 'Productos', '2018-06-07 12:57:53'),
(824, 'alozano', 'Agrego el pantone:  Pantone 187 C', 'Pantones', 'Materia Prima', '2018-06-07 13:13:33'),
(825, 'alozano', 'Agrego el pantone:  Pantone Red 032 C', 'Pantones', 'Materia Prima', '2018-06-07 13:14:49'),
(826, 'alozano', 'Agrego el pantone:  Pantone 400 C', 'Pantones', 'Materia Prima', '2018-06-07 13:15:26'),
(827, 'asaldaÃ±a', 'Agrego el juego de cilindro:  WR8051838', 'Juegos Cilindro', 'Productos', '2018-06-07 16:23:24'),
(828, 'asaldaÃ±a', 'Actualizo el juego de cilindro: WR8051838', 'Juegos Cilindro', 'Productos', '2018-06-07 16:23:52'),
(829, 'asaldaÃ±a', 'Desactivo el juego de cilindro: WR8051838', 'Juegos Cilindro', 'Productos', '2018-06-07 16:25:10'),
(830, 'asaldaÃ±a', 'Agrego el juego de cilindro:  WR8051838', 'Juegos Cilindro', 'Productos', '2018-06-07 16:25:35'),
(831, 'asaldaÃ±a', 'Agrego el juego de cilindro:  WR8051838', 'Juegos Cilindro', 'Productos', '2018-06-08 08:06:23'),
(832, 'asaldaÃ±a', 'Agrego el juego de cilindro:  WR8051845', 'Juegos Cilindro', 'Productos', '2018-06-08 08:08:04'),
(833, 'asaldaÃ±a', 'Desactivo el juego de cilindro: WR8051845', 'Juegos Cilindro', 'Productos', '2018-06-08 08:08:16'),
(834, 'asaldaÃ±a', 'Agrego el juego de cilindro:  WR8051845', 'Juegos Cilindro', 'Productos', '2018-06-08 08:08:49'),
(835, 'castillo66', 'Elimino el juego de cilindro: WR8051838', 'Juegos Cilindro', 'Productos', '2018-06-08 08:26:11'),
(836, 'castillo66', 'Agrego el juego de cilindro:  HTYPLAYRIO', 'Juegos Cilindro', 'Productos', '2018-06-08 08:31:39'),
(837, 'castillo66', 'Actualizo el juego de cilindro: HTYPLAYRIO', 'Juegos Cilindro', 'Productos', '2018-06-08 08:31:56'),
(838, 'castillo66', 'Actualizo el juego de cilindro: HTYPLAYRIO', 'Juegos Cilindro', 'Productos', '2018-06-08 08:32:48'),
(839, 'castillo66', 'Actualizo el juego de cilindro: HTYPLAYRIO', 'Juegos Cilindro', 'Productos', '2018-06-08 08:33:15'),
(840, 'castillo66', 'Actualizo el juego de cilindro: HTYPLAYRIO', 'Juegos Cilindro', 'Productos', '2018-06-08 08:35:20'),
(841, 'castillo66', 'Actualizo el juego de cilindro: HTYPLAYRIO', 'Juegos Cilindro', 'Productos', '2018-06-08 08:35:53'),
(842, 'castillo66', 'Actualizo el juego de cilindro: HTYPLAYRIO', 'Juegos Cilindro', 'Productos', '2018-06-08 08:36:15'),
(843, 'castillo66', 'Actualizo el juego de cilindro: HTYPLAYRIO', 'Juegos Cilindro', 'Productos', '2018-06-08 08:37:53'),
(844, 'castillo66', 'Agrego al empleado Num:  102', 'Personal', 'Recursos Humanos', '2018-06-08 11:42:10'),
(845, 'castillo66', 'Activo el juego de cilindro: WR8051845', 'Juegos Cilindro', 'Productos', '2018-06-08 12:34:36'),
(846, 'castillo66', 'Desactivo el juego de cilindro: WR8051845', 'Juegos Cilindro', 'Productos', '2018-06-08 12:35:05'),
(847, 'alozano', 'Agrego al cliente con RFC:  MEM900806QZ5', 'Clientes', 'Logistica', '2018-06-11 10:42:24'),
(848, 'alozano', 'Agrego al contacto:  Jorge A.Trejo Villamil', 'Contacto Cliente', 'Logistica', '2018-06-11 10:43:30'),
(849, 'alozano', 'Agrego a la sucursal:  Comex Queretaro', 'Sucursal', 'Logistica', '2018-06-11 10:49:51'),
(850, 'alozano', 'Agrego la orden:  12845', 'Orden Compra', 'Logistica', '2018-06-11 10:52:42'),
(851, 'alozano', 'Agrego un requerimiento a la orden de compra:  12845', 'Requerimiento Producto', 'Logistica', '2018-06-11 10:53:21'),
(852, 'alozano', 'Agrego una confirmaciÃ³n a la orden de compra:  12845', 'Requerimiento Producto', 'Logistica', '2018-06-11 10:54:25'),
(853, 'alozano', 'Agrego la orden:  12916', 'Orden Compra', 'Logistica', '2018-06-11 10:55:07'),
(854, 'alozano', 'Agrego un requerimiento a la orden de compra:  12916', 'Requerimiento Producto', 'Logistica', '2018-06-11 10:55:36'),
(855, 'alozano', 'Agrego una confirmaciÃ³n a la orden de compra:  12916', 'Requerimiento Producto', 'Logistica', '2018-06-11 10:56:09'),
(856, 'castillo66', 'Actualizo la orden: 12916', 'Orden Compra', 'Logistica', '2018-06-11 11:04:24'),
(857, 'castillo66', 'Actualizo la orden: 12916', 'Orden Compra', 'Logistica', '2018-06-11 11:04:30'),
(858, 'alozano', 'Actualizo al cliente con RFC: MEM900806QZ5', 'Clientes', 'Logistica', '2018-06-11 13:41:53'),
(859, 'alozano', 'Desactivo una confirmaciÃ³n con la orden de compra: 12845', 'Requerimiento Producto', 'Logistica', '2018-06-11 13:47:12'),
(860, 'alozano', 'Desactivo un requerimiento con la orden de compra: 12845', 'Requerimiento Producto', 'Logistica', '2018-06-11 13:48:11'),
(861, 'alozano', 'Agrego un requerimiento a la orden de compra:  12845', 'Requerimiento Producto', 'Logistica', '2018-06-11 13:48:40'),
(862, 'alozano', 'Agrego una confirmaciÃ³n a la orden de compra:  12845', 'Requerimiento Producto', 'Logistica', '2018-06-11 13:49:18'),
(863, 'asaldaÃ±a', 'Desactivo el lote: 03081614-34', 'Lotes', 'Materia Prima', '2018-06-11 15:37:52'),
(864, 'asaldaÃ±a', 'Elimino el lote: 03081614-34', 'Lotes', 'Materia Prima', '2018-06-11 15:37:58'),
(865, 'asaldaÃ±a', 'Desactivo el lote: 03081614-35', 'Lotes', 'Materia Prima', '2018-06-11 15:37:59'),
(866, 'asaldaÃ±a', 'Desactivo el lote: 03081614-36', 'Lotes', 'Materia Prima', '2018-06-11 15:38:02'),
(867, 'asaldaÃ±a', 'Desactivo el lote: 03081614-55', 'Lotes', 'Materia Prima', '2018-06-11 15:38:04'),
(868, 'asaldaÃ±a', 'Desactivo el lote: 03081614-56', 'Lotes', 'Materia Prima', '2018-06-11 15:38:05'),
(869, 'asaldaÃ±a', 'Desactivo el lote: 03081614-57', 'Lotes', 'Materia Prima', '2018-06-11 15:38:08'),
(870, 'asaldaÃ±a', 'Desactivo el lote: 03081614-58', 'Lotes', 'Materia Prima', '2018-06-11 15:38:09'),
(871, 'asaldaÃ±a', 'Desactivo el lote: 03081614-59', 'Lotes', 'Materia Prima', '2018-06-11 15:38:11'),
(872, 'asaldaÃ±a', 'Desactivo el lote: 03081614-60', 'Lotes', 'Materia Prima', '2018-06-11 15:38:12'),
(873, 'asaldaÃ±a', 'Desactivo el lote: 03081614-61', 'Lotes', 'Materia Prima', '2018-06-11 15:38:15'),
(874, 'asaldaÃ±a', 'Desactivo el lote: 03081614-62', 'Lotes', 'Materia Prima', '2018-06-11 15:38:17'),
(875, 'asaldaÃ±a', 'Desactivo el lote: 03081614-63', 'Lotes', 'Materia Prima', '2018-06-11 15:38:18'),
(876, 'asaldaÃ±a', 'Desactivo el lote: 03081614-64', 'Lotes', 'Materia Prima', '2018-06-11 15:38:20'),
(877, 'asaldaÃ±a', 'Desactivo el lote: 03081614-65', 'Lotes', 'Materia Prima', '2018-06-11 15:38:23'),
(878, 'asaldaÃ±a', 'Desactivo el lote: 03081614-66', 'Lotes', 'Materia Prima', '2018-06-11 15:38:25'),
(879, 'asaldaÃ±a', 'Desactivo el lote: 03081614-67', 'Lotes', 'Materia Prima', '2018-06-11 15:38:27'),
(880, 'asaldaÃ±a', 'Desactivo el lote: 03081614-68', 'Lotes', 'Materia Prima', '2018-06-11 15:38:28'),
(881, 'asaldaÃ±a', 'Desactivo el lote: 03081614-69', 'Lotes', 'Materia Prima', '2018-06-11 15:38:30'),
(882, 'asaldaÃ±a', 'Desactivo el lote: 03081614-70', 'Lotes', 'Materia Prima', '2018-06-11 15:38:32'),
(883, 'asaldaÃ±a', 'Desactivo el lote: 03081614-71', 'Lotes', 'Materia Prima', '2018-06-11 15:38:33');
INSERT INTO `reporte` (`id`, `nombre`, `accion`, `modulo`, `departamento`, `registro`) VALUES
(884, 'asaldaÃ±a', 'Desactivo el lote: 03081614-72', 'Lotes', 'Materia Prima', '2018-06-11 15:38:35'),
(885, 'asaldaÃ±a', 'Elimino el lote: 03081614-35', 'Lotes', 'Materia Prima', '2018-06-11 15:38:39'),
(886, 'asaldaÃ±a', 'Elimino el lote: 03081614-36', 'Lotes', 'Materia Prima', '2018-06-11 15:38:41'),
(887, 'asaldaÃ±a', 'Elimino el lote: 03081614-55', 'Lotes', 'Materia Prima', '2018-06-11 15:38:42'),
(888, 'asaldaÃ±a', 'Elimino el lote: 03081614-56', 'Lotes', 'Materia Prima', '2018-06-11 15:38:44'),
(889, 'asaldaÃ±a', 'Elimino el lote: 03081614-57', 'Lotes', 'Materia Prima', '2018-06-11 15:38:45'),
(890, 'asaldaÃ±a', 'Elimino el lote: 03081614-58', 'Lotes', 'Materia Prima', '2018-06-11 15:38:47'),
(891, 'asaldaÃ±a', 'Elimino el lote: 03081614-59', 'Lotes', 'Materia Prima', '2018-06-11 15:38:48'),
(892, 'asaldaÃ±a', 'Elimino el lote: 03081614-60', 'Lotes', 'Materia Prima', '2018-06-11 15:38:50'),
(893, 'asaldaÃ±a', 'Elimino el lote: 03081614-61', 'Lotes', 'Materia Prima', '2018-06-11 15:38:51'),
(894, 'asaldaÃ±a', 'Elimino el lote: 03081614-62', 'Lotes', 'Materia Prima', '2018-06-11 15:38:52'),
(895, 'asaldaÃ±a', 'Elimino el lote: 03081614-63', 'Lotes', 'Materia Prima', '2018-06-11 15:38:53'),
(896, 'asaldaÃ±a', 'Elimino el lote: 03081614-64', 'Lotes', 'Materia Prima', '2018-06-11 15:38:56'),
(897, 'asaldaÃ±a', 'Elimino el lote: 03081614-65', 'Lotes', 'Materia Prima', '2018-06-11 15:38:58'),
(898, 'asaldaÃ±a', 'Elimino el lote: 03081614-66', 'Lotes', 'Materia Prima', '2018-06-11 15:38:59'),
(899, 'asaldaÃ±a', 'Elimino el lote: 03081614-67', 'Lotes', 'Materia Prima', '2018-06-11 15:39:01'),
(900, 'asaldaÃ±a', 'Elimino el lote: 03081614-68', 'Lotes', 'Materia Prima', '2018-06-11 15:39:03'),
(901, 'asaldaÃ±a', 'Elimino el lote: 03081614-69', 'Lotes', 'Materia Prima', '2018-06-11 15:39:05'),
(902, 'asaldaÃ±a', 'Elimino el lote: 03081614-70', 'Lotes', 'Materia Prima', '2018-06-11 15:39:06'),
(903, 'asaldaÃ±a', 'Elimino el lote: 03081614-71', 'Lotes', 'Materia Prima', '2018-06-11 15:39:07'),
(904, 'asaldaÃ±a', 'Elimino el lote: 03081614-72', 'Lotes', 'Materia Prima', '2018-06-11 15:39:09'),
(905, 'castillo66', 'Agrego al cliente con RFC:  01', 'Clientes', 'Logistica', '2018-06-12 11:23:36'),
(906, 'alozano', 'Desactivo una confirmaciÃ³n con la orden de compra: 12845', 'Requerimiento Producto', 'Logistica', '2018-06-12 12:00:40'),
(907, 'alozano', 'Desactivo una confirmaciÃ³n con la orden de compra: 12916', 'Requerimiento Producto', 'Logistica', '2018-06-12 12:00:48'),
(908, 'alozano', 'Desactivo un requerimiento con la orden de compra: 12845', 'Requerimiento Producto', 'Logistica', '2018-06-12 12:01:20'),
(909, 'alozano', 'Desactivo un requerimiento con la orden de compra: 12916', 'Requerimiento Producto', 'Logistica', '2018-06-12 12:01:26'),
(910, 'alozano', 'Desactivo el diseÃ±o: Comex', 'DiseÃ±o', 'Productos', '2018-06-12 12:14:09'),
(911, 'alozano', 'Elimino el diseÃ±o: Comex', 'DiseÃ±o', 'Productos', '2018-06-12 12:14:20'),
(912, 'alozano', 'Agrego el diseÃ±o:  Comex', 'DiseÃ±o', 'Productos', '2018-06-12 12:15:23'),
(913, 'alozano', 'Agrego un requerimiento a la orden de compra:  12845', 'Requerimiento Producto', 'Logistica', '2018-06-12 12:26:11'),
(914, 'alozano', 'Agrego una confirmaciÃ³n a la orden de compra:  12845', 'Requerimiento Producto', 'Logistica', '2018-06-12 12:26:41'),
(915, 'alozano', 'Agrego un requerimiento a la orden de compra:  12916', 'Requerimiento Producto', 'Logistica', '2018-06-12 12:27:14'),
(916, 'alozano', 'Agrego una confirmaciÃ³n a la orden de compra:  12916', 'Requerimiento Producto', 'Logistica', '2018-06-12 12:27:33'),
(917, 'asaldaÃ±a', 'Agrego el sustrato:  PEL026', 'Sustrato', 'Materia Prima', '2018-06-12 15:13:26'),
(918, 'asaldaÃ±a', 'Actualizo el sustrato:  Bopp blanco 35 micras', 'Sustratos', 'Materia Prima', '2018-06-12 15:14:16'),
(919, 'asaldaÃ±a', 'Actualizo el sustrato:  Bopp blanco 35 micras', 'Sustratos', 'Materia Prima', '2018-06-12 15:14:54'),
(920, 'asaldaÃ±a', 'Agrego el sustrato:  PEL030445', 'Sustrato', 'Materia Prima', '2018-06-12 15:15:35'),
(921, 'asaldaÃ±a', 'Desactivo el sustrato: PET METALIZADO 400 MM', 'Sustratos', 'Materia Prima', '2018-06-12 15:16:13'),
(922, 'asaldaÃ±a', 'Agrego el sustrato:  PEL026400', 'Sustrato', 'Materia Prima', '2018-06-12 15:17:45'),
(923, 'castillo66', 'Activo el juego de cilindro: WR8051845', 'Juegos Cilindro', 'Productos', '2018-06-12 15:30:12'),
(924, 'castillo66', 'Agrego la mÃ¡quina:  MTIM05', 'MÃ¡quinas', 'ProducciÃ³n', '2018-06-12 15:36:07'),
(925, 'castillo66', 'Agrego el juego de cilindro:  WR8051838', 'Juegos Cilindro', 'Productos', '2018-06-12 15:51:22'),
(926, 'castillo66', 'Desactivo el juego de cilindro: WR8051845', 'Juegos Cilindro', 'Productos', '2018-06-13 10:35:27'),
(927, 'castillo66', 'Activo el juego de cilindro: WR8051845', 'Juegos Cilindro', 'Productos', '2018-06-25 08:37:47'),
(928, 'castillo66', 'Desactivo el juego de cilindro: WR8051845', 'Juegos Cilindro', 'Productos', '2018-06-25 08:54:05'),
(929, 'castillo66', 'Agrego el diseÃ±o:  ResponsivePrueba', 'DiseÃ±o', 'Productos', '2018-06-25 13:42:02'),
(930, 'castillo66', 'Actualizo el diseÃ±o: ResponsivePrueba', 'DiseÃ±o', 'Productos', '2018-06-25 13:42:25'),
(931, 'castillo66', 'Desactivo el diseÃ±o: ResponsivePrueba', 'DiseÃ±o', 'Productos', '2018-06-25 13:42:57'),
(932, 'castillo66', 'Activo el diseÃ±o: ResponsivePrueba', 'DiseÃ±o', 'Productos', '2018-06-25 13:43:13'),
(933, 'castillo66', 'Desactivo el diseÃ±o: ResponsivePrueba', 'DiseÃ±o', 'Productos', '2018-06-25 13:43:16'),
(934, 'castillo66', 'Elimino el diseÃ±o: ResponsivePrueba', 'DiseÃ±o', 'Productos', '2018-06-25 13:43:22'),
(935, 'castillo66', 'Agrego al empleado Num:  890', 'Personal', 'Recursos Humanos', '2018-06-26 10:04:55'),
(936, 'castillo66', 'Desactivo al empleado Num: 890', 'Personal', 'Recursos Humanos', '2018-06-26 10:05:15'),
(937, 'castillo66', 'Activo al empleado Num: 890', 'Personal', 'Recursos Humanos', '2018-06-26 10:05:23'),
(938, 'castillo66', 'Actualizo al empleado Num: 890', 'Personal', 'Recursos Humanos', '2018-06-26 10:05:37'),
(939, 'castillo66', 'Desactivo al empleado Num: 890', 'Personal', 'Recursos Humanos', '2018-06-26 10:05:43'),
(940, 'castillo66', 'Elimino al empleado Num: 890', 'Personal', 'Recursos Humanos', '2018-06-26 10:05:49'),
(941, 'castillo66', 'Agrego el departamento:  AlmacÃ©n', 'Departamento', 'Recursos Humanos', '2018-06-26 10:18:40'),
(942, 'castillo66', 'Agrego el diseÃ±o:  Pruebis', 'DiseÃ±o', 'Productos', '2018-06-26 12:32:19'),
(943, 'castillo66', 'Actualizo el consumo del producto:  0', 'Consumos', 'Productos', '2018-06-27 08:45:27'),
(944, 'asaldaÃ±a', 'Desactivo el lote: 03081614-3', 'Lotes', 'Materia Prima', '2018-06-27 09:52:47'),
(945, 'asaldaÃ±a', 'Elimino el lote: 03081614-3', 'Lotes', 'Materia Prima', '2018-06-27 09:52:52'),
(946, 'castillo66', 'Desactivo la Banda de Seguridad: Epura Pre-Embozado320', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 10:46:46'),
(947, 'castillo66', 'Desactivo la Banda de Seguridad: Epura Pre-Embozado320', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 10:46:48'),
(948, 'castillo66', 'Desactivo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 11:43:45'),
(949, 'castillo66', 'Activo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 11:47:53'),
(950, 'castillo66', 'Desactivo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 11:52:19'),
(951, 'castillo66', 'Activo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 11:52:23'),
(952, 'castillo66', 'Activo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 11:53:43'),
(953, 'castillo66', 'Desactivo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 11:53:44'),
(954, 'castillo66', 'Desactivo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 11:54:16'),
(955, 'castillo66', 'Desactivo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 11:54:31'),
(956, 'castillo66', 'Desactivo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 11:56:30'),
(957, 'castillo66', 'Activo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 11:56:43'),
(958, 'castillo66', 'Desactivo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 11:56:47'),
(959, 'castillo66', 'Desactivo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 11:57:15'),
(960, 'castillo66', 'Desactivo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 11:57:37'),
(961, 'castillo66', 'Desactivo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 11:57:44'),
(962, 'castillo66', 'Desactivo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 11:57:48'),
(963, 'castillo66', 'Activo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 11:59:17'),
(964, 'castillo66', 'Desactivo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 11:59:18'),
(965, 'castillo66', 'Desactivo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 11:59:44'),
(966, 'castillo66', 'Desactivo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 12:00:52'),
(967, 'castillo66', 'Activo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 12:03:47'),
(968, 'castillo66', 'Desactivo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 12:03:48'),
(969, 'castillo66', 'Desactivo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 12:03:57'),
(970, 'castillo66', 'Desactivo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 12:04:43'),
(971, 'castillo66', 'Activo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 12:16:24'),
(972, 'castillo66', 'Desactivo la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 12:16:27'),
(973, 'castillo66', 'Elimino la Banda de Seguridad: Electronica', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 12:57:23'),
(974, 'castillo66', 'Desactivo la Banda de Seguridad: electrorock', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 12:59:25'),
(975, 'castillo66', 'Elimino la Banda de Seguridad: electrorock', 'Banda de Seguridad Por Proceso', 'Productos', '2018-06-27 12:59:32'),
(976, 'castillo66', 'Actualizo el diseÃ±o: Pruebis', 'DiseÃ±o', 'Productos', '2018-06-27 13:18:31'),
(977, 'castillo66', 'Actualizo el diseÃ±o: Pruebis', 'DiseÃ±o', 'Productos', '2018-06-27 13:19:26'),
(978, 'castillo66', 'Actualizo el diseÃ±o: Pruebis', 'DiseÃ±o', 'Productos', '2018-06-27 13:19:56'),
(979, 'castillo66', 'Actualizo el diseÃ±o: Pruebis', 'DiseÃ±o', 'Productos', '2018-06-27 13:20:45'),
(980, 'castillo66', 'Actualizo el diseÃ±o: Pruebis', 'DiseÃ±o', 'Productos', '2018-06-27 13:21:16'),
(981, 'castillo66', 'Actualizo el diseÃ±o: Pruebis', 'DiseÃ±o', 'Productos', '2018-06-27 13:30:33'),
(982, 'castillo66', 'Desactivo el diseÃ±o: Pruebis', 'DiseÃ±o', 'Productos', '2018-06-27 13:31:59'),
(983, 'castillo66', 'Activo el diseÃ±o: Pruebis', 'DiseÃ±o', 'Productos', '2018-06-27 13:38:47'),
(984, 'castillo66', 'Actualizo el diseÃ±o: Pruebis', 'DiseÃ±o', 'Productos', '2018-06-27 13:38:55'),
(985, 'castillo66', 'Actualizo el diseÃ±o: Pruebis', 'DiseÃ±o', 'Productos', '2018-06-27 13:39:19'),
(986, 'castillo66', 'Desactivo un consumo del diseÃ±o: 0', 'Consumos', 'Productos', '2018-06-27 13:57:24'),
(987, 'castillo66', 'Activo un consumo del diseÃ±o: 0', 'Consumos', 'Productos', '2018-06-27 13:57:33'),
(988, 'castillo66', 'Desactivo un consumo del diseÃ±o: 0', 'Consumos', 'Productos', '2018-06-27 13:57:36'),
(989, 'castillo66', 'Elimino un consumo del producto: 0', 'Consumos', 'Productos', '2018-06-27 13:57:43'),
(990, 'castillo66', 'Desactivo el diseÃ±o: Pruebis', 'DiseÃ±o', 'Productos', '2018-06-28 09:41:45'),
(991, 'castillo66', 'Agrego un lote al bloque:  PVC termoencogible C40 E50/0 450mm', 'Lotes', 'Materia Prima', '2018-06-28 10:04:55'),
(992, 'castillo66', 'Actualizo el sustrato:  sustrto metalizado', 'Sustratos', 'Materia Prima', '2018-06-28 12:00:21'),
(993, 'castillo66', 'Agrego el sustrato:  666', 'Sustrato', 'Materia Prima', '2018-06-28 12:04:21'),
(994, 'castillo66', 'Desactivo el sustrato: A sustratini', 'Sustratos', 'Materia Prima', '2018-06-28 12:04:24'),
(995, 'castillo66', 'Agrego el elemento:  ', 'Elementos', 'Materia Prima', '2018-06-29 08:43:38'),
(996, 'castillo66', 'Desactivo el elemento: ', 'Elementos', 'Materia Prima', '2018-06-29 08:43:40'),
(997, 'castillo66', 'Elimino el elemento: ', 'Elementos', 'Materia Prima', '2018-06-29 08:44:15'),
(998, 'castillo66', 'Actualizo el pantone:  Pantone 7712 C', 'Pantones', 'Materia Prima', '2018-06-29 09:18:54'),
(999, 'castillo66', 'Actualizo el pantone:  Pantone 7712 C', 'Pantones', 'Materia Prima', '2018-06-29 09:19:17'),
(1000, 'castillo66', 'Actualizo el pantone:  Pantone 7712 C', 'Pantones', 'Materia Prima', '2018-06-29 09:19:37'),
(1001, 'castillo66', 'Agrego el tipo de mediciÃ³n:  ', 'Unidades de Medida', 'Materia Prima', '2018-06-29 09:28:13'),
(1002, 'castillo66', 'Desactivo el tipo de mediciÃ³n: ', 'Unidades de Medida', 'Materia Prima', '2018-06-29 09:28:17'),
(1003, 'castillo66', 'Desactivo el tipo de mediciÃ³n: PA', 'Unidades de Medida', 'Materia Prima', '2018-06-29 09:31:09'),
(1004, 'castillo66', 'Activo el tipo de mediciÃ³n: PA', 'Unidades de Medida', 'Materia Prima', '2018-06-29 09:31:25'),
(1005, 'castillo66', 'Desactivo el tipo de mediciÃ³n: PA', 'Unidades de Medida', 'Materia Prima', '2018-06-29 09:31:27'),
(1006, 'castillo66', 'Desactivo el tipo de mediciÃ³n: PA', 'Unidades de Medida', 'Materia Prima', '2018-06-29 09:31:32'),
(1007, 'castillo66', 'Elimino el tipo de mediciÃ³n: PA', 'Unidades de Medida', 'Materia Prima', '2018-06-29 09:31:54'),
(1008, 'castillo66', 'Elimino el tipo de mediciÃ³n: ', 'Unidades de Medida', 'Materia Prima', '2018-06-29 09:32:09'),
(1009, 'castillo66', 'Desactivo al cliente con RFC: 01', 'Clientes', 'Logistica', '2018-07-02 08:31:25'),
(1010, 'castillo66', 'Desactivo al cliente con RFC: MEM900806QZ5', 'Clientes', 'Logistica', '2018-07-02 08:31:32'),
(1011, 'castillo66', 'Activo al cliente con RFC: MEM900806QZ5', 'Clientes', 'Logistica', '2018-07-02 08:31:49'),
(1012, 'castillo66', 'Activo al cliente con RFC: 01', 'Clientes', 'Logistica', '2018-07-02 08:31:55'),
(1013, 'castillo66', 'Activo el diseÃ±o: Pruebis', 'DiseÃ±o', 'Productos', '2018-07-03 13:31:38'),
(1014, 'castillo66', 'Desactivo el diseÃ±o: Pruebis', 'DiseÃ±o', 'Productos', '2018-07-03 13:31:42'),
(1015, 'castillo66', 'Activo el diseÃ±o: Pruebis', 'DiseÃ±o', 'Productos', '2018-07-12 13:20:07'),
(1016, 'castillo66', 'Desactivo el diseÃ±o: Pruebis', 'DiseÃ±o', 'Productos', '2018-07-13 13:13:50'),
(1017, 'castillo66', 'Activo el diseÃ±o: Pruebis', 'DiseÃ±o', 'Productos', '2018-07-13 13:15:29'),
(1018, 'castillo66', 'Desactivo el diseÃ±o: Pruebis', 'DiseÃ±o', 'Productos', '2018-07-13 13:16:02'),
(1019, 'castillo66', 'Agrego el elemento:  ', 'Elementos', 'Materia Prima', '2018-08-07 10:59:32'),
(1020, 'castillo66', 'Desactivo el elemento: ', 'Elementos', 'Materia Prima', '2018-08-07 11:01:36'),
(1021, 'castillo66', 'Elimino el elemento: ', 'Elementos', 'Materia Prima', '2018-08-07 11:01:45'),
(1022, 'castillo66', 'Actualizo el consumo del producto:  Comex', 'Consumos', 'Productos', '2018-08-10 12:28:37'),
(1023, 'castillo66', 'Actualizo el consumo del producto:  0', 'Consumos', 'Productos', '2018-08-10 12:28:51'),
(1024, 'core', 'Actualizo el elemeto:  TRO097', 'Elementos', 'Materia Prima', '2018-08-17 13:02:36'),
(1025, 'core', 'Actualizo el elemeto:  TRO097', 'Elementos', 'Materia Prima', '2018-08-17 13:14:54'),
(1026, 'core', 'Actualizo el elemeto:  TRO097', 'Elementos', 'Materia Prima', '2018-08-17 13:17:46'),
(1027, 'core', 'Actualizo el elemeto:  TRO097', 'Elementos', 'Materia Prima', '2018-08-17 13:31:17'),
(1028, 'core', 'Actualizo el elemeto:  ', 'Elementos', 'Materia Prima', '2018-08-17 13:43:40'),
(1029, 'core', 'Actualizo el elemeto:  ', 'Elementos', 'Materia Prima', '2018-08-17 13:45:43'),
(1030, 'core', 'Actualizo el elemeto:  ', 'Elementos', 'Materia Prima', '2018-08-17 13:47:49'),
(1031, 'core', 'Actualizo el elemeto:  ', 'Elementos', 'Materia Prima', '2018-08-17 13:50:05'),
(1032, 'core', 'Actualizo el elemeto:  ', 'Elementos', 'Materia Prima', '2018-08-17 13:52:18'),
(1033, 'core', 'Actualizo el elemeto:  ', 'Elementos', 'Materia Prima', '2018-08-17 13:52:31'),
(1034, 'core', 'Actualizo el elemeto:  ', 'Elementos', 'Materia Prima', '2018-08-17 13:53:02'),
(1035, 'core', 'Actualizo el elemeto:  ', 'Elementos', 'Materia Prima', '2018-08-17 14:00:59'),
(1036, 'core', 'Desactivo un consumo del diseÃ±o: Comex', 'Consumos', 'Productos', '2018-08-22 09:26:22'),
(1037, 'core', 'Desactivo un consumo del diseÃ±o: 0', 'Consumos', 'Productos', '2018-08-22 09:26:23'),
(1038, 'core', 'Desactivo un consumo del diseÃ±o: 0', 'Consumos', 'Productos', '2018-08-22 09:26:25'),
(1039, 'core', 'Desactivo un consumo del diseÃ±o: 0', 'Consumos', 'Productos', '2018-08-22 09:26:26'),
(1040, 'core', 'Desactivo un consumo del diseÃ±o: 0', 'Consumos', 'Productos', '2018-08-22 09:26:27'),
(1041, 'core', 'Desactivo un consumo del diseÃ±o: 0', 'Consumos', 'Productos', '2018-08-22 09:26:28'),
(1042, 'core', 'Elimino un consumo del producto: 0', 'Consumos', 'Productos', '2018-08-22 09:26:33'),
(1043, 'core', 'Elimino un consumo del producto: ', 'Consumos', 'Productos', '2018-08-22 09:26:36'),
(1044, 'core', 'Elimino un consumo del producto: 0', 'Consumos', 'Productos', '2018-08-22 09:26:39'),
(1045, 'core', 'Elimino un consumo del producto: ', 'Consumos', 'Productos', '2018-08-22 09:26:40'),
(1046, 'core', 'Elimino un consumo del producto: 0', 'Consumos', 'Productos', '2018-08-22 09:26:43'),
(1047, 'core', 'Elimino un consumo del producto: ', 'Consumos', 'Productos', '2018-08-22 09:26:44'),
(1048, 'core', 'Elimino un consumo del producto: 0', 'Consumos', 'Productos', '2018-08-22 09:26:48'),
(1049, 'core', 'Elimino un consumo del producto: ', 'Consumos', 'Productos', '2018-08-22 09:26:50'),
(1050, 'core', 'Elimino un consumo del producto: 0', 'Consumos', 'Productos', '2018-08-22 09:26:52'),
(1051, 'core', 'Elimino un consumo del producto: ', 'Consumos', 'Productos', '2018-08-22 09:26:54'),
(1052, 'core', 'Elimino un consumo del producto: 0', 'Consumos', 'Productos', '2018-08-22 09:26:55'),
(1053, 'core', 'Desactivo un consumo del diseÃ±o: Comex', 'Consumos', 'Productos', '2018-08-22 09:27:15'),
(1054, 'core', 'Desactivo un consumo del diseÃ±o: Comex', 'Consumos', 'Productos', '2018-08-22 09:27:20'),
(1055, 'core', 'Desactivo un consumo del diseÃ±o: Comex', 'Consumos', 'Productos', '2018-08-22 09:27:23'),
(1056, 'core', 'Desactivo un consumo del diseÃ±o: Comex', 'Consumos', 'Productos', '2018-08-22 09:27:26'),
(1057, 'core', 'Desactivo un consumo del diseÃ±o: Comex', 'Consumos', 'Productos', '2018-08-22 09:27:30'),
(1058, 'core', 'Agrego un consumo al diseÃ±o:  Comex', 'Consumos', 'Productos', '2018-08-22 09:28:31'),
(1059, 'core', 'Agrego un consumo ala impresiÃ³n:  ', 'Consumos', 'Productos', '2018-08-23 09:07:39'),
(1060, 'core', 'Desactivo un consumo de la impresiÃ³n: ', 'Consumos', 'Productos', '2018-08-23 09:07:43'),
(1061, 'core', 'Agrego un consumo ala impresiÃ³n:  ', 'Consumos', 'Productos', '2018-08-23 09:07:58'),
(1062, 'core', 'Desactivo un consumo de la impresiÃ³n: ', 'Consumos', 'Productos', '2018-08-23 09:08:08'),
(1063, 'core', 'Agrego un consumo ala impresiÃ³n:  Thinner estandar 960ml', 'Consumos', 'Productos', '2018-08-23 09:09:29'),
(1064, 'core', 'Desactivo un consumo de la impresiÃ³n: ', 'Consumos', 'Productos', '2018-08-23 09:17:51'),
(1065, 'core', 'Agrego un consumo ala impresiÃ³n:  Comex prueba', 'Consumos', 'Productos', '2018-08-23 09:21:18'),
(1066, 'core', 'Desactivo un consumo de la impresiÃ³n: ', 'Consumos', 'Productos', '2018-08-23 09:21:31'),
(1067, 'core', 'Agrego un consumo ala impresiÃ³n:  Comex prueba', 'Consumos', 'Productos', '2018-08-23 09:23:56'),
(1068, 'core', 'Desactivo un consumo de la impresiÃ³n: ', 'Consumos', 'Productos', '2018-08-23 09:24:12'),
(1069, 'core', 'Agrego un consumo ala impresiÃ³n:  Thinner estandar 960ml', 'Consumos', 'Productos', '2018-08-23 09:35:56'),
(1070, 'core', 'Activo un consumo de la impresiÃ³n: ', 'Consumos', 'Productos', '2018-08-23 09:37:46'),
(1071, 'core', 'Desactivo un consumo de la impresiÃ³n: ', 'Consumos', 'Productos', '2018-08-23 09:39:03'),
(1072, 'core', 'Activo un consumo de la impresiÃ³n: ', 'Consumos', 'Productos', '2018-08-23 09:39:07'),
(1073, 'core', 'Desactivo un consumo de la impresiÃ³n: ', 'Consumos', 'Productos', '2018-08-23 09:39:17'),
(1074, 'core', 'Elimino un consumo del producto: ', 'Consumos', 'Productos', '2018-08-23 09:39:24'),
(1075, 'core', 'Agrego un consumo ala impresiÃ³n:  0', 'Consumos', 'Productos', '2018-08-24 13:37:34'),
(1076, 'core', 'Agrego un consumo ala impresiÃ³n:  0', 'Consumos', 'Productos', '2018-08-24 13:40:29'),
(1077, 'core', 'Agrego un consumo ala impresiÃ³n:  0', 'Consumos', 'Productos', '2018-08-24 14:06:03'),
(1078, 'core', 'Agrego un consumo ala impresiÃ³n:  0', 'Consumos', 'Productos', '2018-08-24 14:07:25'),
(1079, 'core', 'Desactivo un consumo de la impresiÃ³n: ', 'Consumos', 'Productos', '2018-08-27 10:33:42'),
(1080, 'core', 'Desactivo un consumo de la impresiÃ³n: ', 'Consumos', 'Productos', '2018-08-27 10:33:43'),
(1081, 'core', 'Agrego un consumo ala impresiÃ³n:  5', 'Consumos', 'Productos', '2018-08-27 11:28:22'),
(1082, 'core', 'Agrego un consumo ala impresiÃ³n:  5', 'Consumos', 'Productos', '2018-08-27 11:30:46'),
(1083, 'core', 'Agrego un consumo ala impresiÃ³n:  5', 'Consumos', 'Productos', '2018-08-27 11:31:01'),
(1084, 'core', 'Agrego un consumo ala impresiÃ³n:  5', 'Consumos', 'Productos', '2018-08-27 11:31:17'),
(1085, 'core', 'Desactivo un consumo de la impresiÃ³n: ', 'Consumos', 'Productos', '2018-08-29 09:31:37'),
(1086, 'core', 'Desactivo un consumo de la impresiÃ³n: ', 'Consumos', 'Productos', '2018-08-29 09:31:39'),
(1087, 'core', 'Desactivo un consumo de la impresiÃ³n: ', 'Consumos', 'Productos', '2018-08-29 09:31:41'),
(1088, 'core', 'Desactivo un consumo de la impresiÃ³n: ', 'Consumos', 'Productos', '2018-08-29 09:31:42'),
(1089, 'core', 'Agrego un consumo ala impresiÃ³n:  5', 'Consumos', 'Productos', '2018-08-29 09:32:01'),
(1090, 'core', 'Agrego un consumo ala impresiÃ³n:  5', 'Consumos', 'Productos', '2018-08-29 09:32:17'),
(1091, 'core', 'Agrego un consumo ala impresiÃ³n:  5', 'Consumos', 'Productos', '2018-08-29 09:32:54'),
(1092, 'core', 'Agrego un consumo ala impresiÃ³n:  5', 'Consumos', 'Productos', '2018-08-29 09:34:08'),
(1093, 'core', 'Agrego un consumo ala impresiÃ³n:  5', 'Consumos', 'Productos', '2018-08-29 09:34:34'),
(1094, 'core', 'Agrego un consumo ala impresiÃ³n:  5', 'Consumos', 'Productos', '2018-08-29 09:35:11'),
(1095, 'core', 'Desactivo un consumo de la impresiÃ³n: ', 'Consumos', 'Productos', '2018-08-29 09:39:17'),
(1096, 'core', 'Agrego un consumo ala impresiÃ³n:  5', 'Consumos', 'Productos', '2018-08-29 09:39:43'),
(1097, 'core', 'Agrego el departamento:  Calidad', 'Departamento', 'Recursos Humanos', '2018-09-07 09:12:27'),
(1098, 'core', 'Agrego al empleado Num:  020', 'Personal', 'Recursos Humanos', '2018-09-07 09:12:48'),
(1099, 'core', 'Activo el diseÃ±o: Pruebis', 'DiseÃ±o', 'Productos', '2018-12-29 09:53:47'),
(1100, 'core', 'Desactivo el diseÃ±o: Pruebis', 'DiseÃ±o', 'Productos', '2018-12-29 09:54:08'),
(1101, 'core', 'Agrego el embarque:  20190102001', 'Embarque', 'Logistica', '2019-01-02 09:42:33'),
(1102, 'core', 'Agrego la orden:  977', 'Orden Compra', 'Logistica', '2019-01-02 09:43:06'),
(1103, 'core', 'Agrego un requerimiento a la orden de compra:  977', 'Requerimiento Producto', 'Logistica', '2019-01-02 09:43:29'),
(1104, 'core', 'Agrego una confirmaciÃ³n a la orden de compra:  977', 'Requerimiento Producto', 'Logistica', '2019-01-02 09:44:02'),
(1105, 'core', 'Agrego 3 empaques, 19.855 millares al embarque:20190102001', 'Armado de Embarque', 'Logistica', '2019-01-02 09:45:19'),
(1106, 'core', 'Finalizo el embarque: 20190102001', 'Armado de Embarque', 'Logistica', '2019-01-02 09:45:43'),
(1107, 'core', 'Desactivo al empleado Num: 020', 'Personal', 'Recursos Humanos', '2019-01-02 14:45:35'),
(1108, 'core', 'Activo al empleado Num: 020', 'Personal', 'Recursos Humanos', '2019-01-02 14:45:42'),
(1109, 'core', 'Abrio el embarque: 20190102001', 'Embarque', 'Logistica', '2019-01-02 15:22:38'),
(1110, 'core', 'Retorno el empaque Q1 (6.375 millares) del embarque: 20190102001', 'Armado de Embarque', 'Logistica', '2019-01-02 15:22:42'),
(1111, 'core', 'Retorno el empaque Q2 (6.740 millares) del embarque: 20190102001', 'Armado de Embarque', 'Logistica', '2019-01-02 15:22:43'),
(1112, 'core', 'Retorno el empaque Q3 (6.740 millares) del embarque: 20190102001', 'Armado de Embarque', 'Logistica', '2019-01-02 15:23:13'),
(1113, 'core', 'Agrego 3 empaques, 19.855 millares al embarque:20190102001', 'Armado de Embarque', 'Logistica', '2019-01-02 15:23:17'),
(1114, 'core', 'Finalizo el embarque: 20190102001', 'Armado de Embarque', 'Logistica', '2019-01-02 15:23:21'),
(1115, 'core', 'Agrego 0 empaques, 0 millares al embarque:20190102001', 'Armado de Embarque', 'Logistica', '2019-01-02 15:29:27'),
(1116, 'core', 'Retorno el empaque Q1 (6.375 millares) del embarque: 20190102001', 'Armado de Embarque', 'Logistica', '2019-01-02 15:29:42'),
(1117, 'core', 'Retorno el empaque Q2 (6.740 millares) del embarque: 20190102001', 'Armado de Embarque', 'Logistica', '2019-01-02 15:29:43'),
(1118, 'core', 'Retorno el empaque Q3 (6.740 millares) del embarque: 20190102001', 'Armado de Embarque', 'Logistica', '2019-01-02 15:29:43'),
(1119, 'core', 'Agrego 3 empaques, 19.855 millares al embarque:20190102001', 'Armado de Embarque', 'Logistica', '2019-01-02 15:29:45'),
(1120, 'core', 'Finalizo el embarque: 20190102001', 'Armado de Embarque', 'Logistica', '2019-01-02 15:31:58'),
(1121, 'core', 'Abrio el embarque: 20190102001', 'Embarque', 'Logistica', '2019-01-02 15:32:01'),
(1122, 'core', 'Finalizo el embarque: 20190102001', 'Armado de Embarque', 'Logistica', '2019-01-02 15:32:17'),
(1123, 'core', 'Abrio el embarque: 20190102001', 'Embarque', 'Logistica', '2019-01-02 15:34:00'),
(1124, 'core', 'Retorno el empaque Q1 (6.375 millares) del embarque: 20190102001', 'Armado de Embarque', 'Logistica', '2019-01-02 15:39:30'),
(1125, 'liraTI', 'Activo una confirmaciÃ³n con la orden de compra: 12845', 'Requerimiento Producto', 'Logistica', '2019-01-04 12:19:29'),
(1126, 'liraTI', 'Activo una confirmaciÃ³n con la orden de compra: 12845', 'Requerimiento Producto', 'Logistica', '2019-01-04 12:19:37'),
(1127, 'liraTI', 'Retorno el empaque Q2 (6.740 millares) del embarque: 20190102001', 'Armado de Embarque', 'Logistica', '2019-01-04 13:15:15'),
(1128, 'liraTI', 'Retorno el empaque Q3 (6.740 millares) del embarque: 20190102001', 'Armado de Embarque', 'Logistica', '2019-01-04 13:15:17'),
(1129, 'liraTI', 'Agrego 2 empaques, 13.115 millares al embarque:20190102001', 'Armado de Embarque', 'Logistica', '2019-01-04 13:15:32'),
(1130, 'liraTI', 'Retorno el empaque Q1 (6.375 millares) del embarque: 20190102001', 'Armado de Embarque', 'Logistica', '2019-01-04 13:17:01'),
(1131, 'liraTI', 'Retorno el empaque Q2 (6.740 millares) del embarque: 20190102001', 'Armado de Embarque', 'Logistica', '2019-01-04 13:17:03'),
(1132, 'liraTI', 'Agrego 2 empaques, 13.115 millares al embarque:20190102001', 'Armado de Embarque', 'Logistica', '2019-01-04 16:02:15'),
(1133, 'liraTI', 'Agrego el embarque:  20190105001', 'Embarque', 'Logistica', '2019-01-07 11:30:30'),
(1134, 'liraTI', 'Agrego el diseÃ±o:  Generico Gepp', 'DiseÃ±o', 'Productos', '2019-01-08 11:48:54'),
(1135, 'liraTI', 'Agrego la Banda de Seguridad:  BS Generico Gepp', 'Banda de Seguridad', 'Productos', '2019-01-08 11:51:49'),
(1136, 'liraTI', 'Actualizo el diseÃ±o: Generico Gepp', 'DiseÃ±o', 'Productos', '2019-01-08 11:54:35'),
(1137, 'liraTI', 'Actualizo el diseÃ±o: Generico Gepp', 'DiseÃ±o', 'Productos', '2019-01-08 11:54:52'),
(1138, 'core', 'Actualizo el diseÃ±o: Generico Gepp', 'DiseÃ±o', 'Productos', '2019-01-08 11:56:52'),
(1139, 'core', 'Actualizo el diseÃ±o: Generico Gepp', 'DiseÃ±o', 'Productos', '2019-01-08 11:57:31'),
(1140, 'core', 'Actualizo el diseÃ±o: Generico Gepp', 'DiseÃ±o', 'Productos', '2019-01-08 11:57:35'),
(1141, 'liraTI', 'Agrego la orden:  PRue30', 'Orden Compra', 'Logistica', '2019-01-08 13:36:19'),
(1142, 'liraTI', 'Agrego un requerimiento a la orden de compra:  PRue30', 'Requerimiento Producto', 'Logistica', '2019-01-08 13:52:18'),
(1143, 'liraTI', 'Agrego un requerimiento a la orden de compra:  PRue30', 'Requerimiento Producto', 'Logistica', '2019-01-08 14:03:04'),
(1144, 'liraTI', 'Actualizo un requerimiento con la orden de compra: PRue30', 'Requerimiento Producto', 'Logistica', '2019-01-09 09:53:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `requerimientoprod`
--

CREATE TABLE `requerimientoprod` (
  `idReq` int(11) NOT NULL,
  `cantReq` decimal(9,3) NOT NULL,
  `refeReq` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `bajaReq` int(1) NOT NULL DEFAULT '1',
  `ordenReqFK` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `prodcliReqFK` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `requerimientoprod`
--

INSERT INTO `requerimientoprod` (`idReq`, `cantReq`, `refeReq`, `bajaReq`, `ordenReqFK`, `prodcliReqFK`) VALUES
(4, '185.000', '1er entrega', 1, '12845', 'Etiqueta thinner estandar 960ml'),
(5, '1040.000', '2da entrega', 1, '12916', 'Etiqueta thinner estandar 960ml'),
(6, '300.000', 'nio cv', 1, '977', 'Etiqueta thinner estandar 960ml'),
(7, '1500.000', 'Prueba Virtual', 1, 'PRue30', 'Banda de garantia s/hol epura 88x37mm'),
(8, '484.000', 'Quitar', 1, 'PRue30', 'Etiqueta thinner estandar 960ml');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rollo`
--

CREATE TABLE `rollo` (
  `id` int(11) NOT NULL,
  `referencia` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `producto` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `noElementos` int(100) NOT NULL,
  `longitud` float NOT NULL,
  `piezas` decimal(9,3) NOT NULL,
  `codigo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `cdgEmbarque` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `peso` float NOT NULL,
  `cdgDev` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `rollo`
--

INSERT INTO `rollo` (`id`, `referencia`, `producto`, `noElementos`, `longitud`, `piezas`, `codigo`, `cdgEmbarque`, `peso`, `cdgDev`, `baja`) VALUES
(1, 'Q1', 'Thinner estandar 960ml', 1, 1750, '6.375', '0053870001', '20190102001', 45, '', 3),
(2, 'Q2', 'Thinner estandar 960ml', 1, 1850, '6.740', '0053870002', '20190102001', 4.3, '', 3),
(3, 'Q3', 'Thinner estandar 960ml', 1, 1850, '6.740', '0053870003', '', 10.5, '', 2);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `shoTableroBS`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `shoTableroBS` (
`productos` varchar(201)
,`descripcionImpresion` varchar(100)
,`id` int(11)
,`sustrato` varchar(100)
,`nombreBanda` char(0)
,`anchura` int(11)
,`alturaEtiqueta` float
,`tintas` char(0)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `showTablero`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `showTablero` (
`tipo` varchar(20)
,`productos` varchar(153)
,`descripcionImpresion` varchar(50)
,`holograma` varchar(30)
,`id` int(11)
,`sustrato` varchar(70)
,`nombreBanda` varchar(30)
,`anchoPelicula` float
,`alturaEtiqueta` float
,`tintas` int(11)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suaje`
--

CREATE TABLE `suaje` (
  `id` int(11) NOT NULL,
  `identificadorSuaje` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `proveedor` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `codigo` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `alturaImpresion` float NOT NULL,
  `anchuraImpresion` float NOT NULL,
  `piezas` int(11) NOT NULL,
  `alturaReal` float NOT NULL,
  `corteSeguridad` float NOT NULL,
  `descripcionImpresion` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `observaciones` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `reguardo` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `proceso` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sustrato`
--

CREATE TABLE `sustrato` (
  `idSustrato` int(11) NOT NULL,
  `codigoSustrato` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `descripcionSustrato` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `anchura` double NOT NULL,
  `rendimiento` double NOT NULL,
  `PreEmbosado` int(11) NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `sustrato`
--

INSERT INTO `sustrato` (`idSustrato`, `codigoSustrato`, `descripcionSustrato`, `anchura`, `rendimiento`, `PreEmbosado`, `baja`) VALUES
(1, 'SUPVTRCH3545000', 'PVC termoencogible C40 E50/0 450mm', 450, 0.8, 0, 1),
(2, 'SUBPEMEL6400000', 'Polyester embosado C20 electropura 640mm', 640, 64.5, 0, 1),
(3, 'SUBPEMEL3200000', 'Polyester embosado C20 electropura 320mm', 320, 34.35, 0, 0),
(4, 'SUBPEMEP3200000', 'Polyester embosado C20 e-pura 320mm', 320, 34.35, 0, 0),
(5, 'SUBPEMEP6400000', 'Polyester embosado C20 e-pura 640mm', 640, 64.5, 0, 1),
(6, 'SUPVTRCH3540000', 'PVC termoencogible C40 E50/0 400mm', 400, 20, 0, 1),
(7, 'SUPTGTRNC37470', 'PTG transparente C37 455 mm', 455, 0.21, 0, 1),
(8, 'SUBPEMGOT3200000', 'Polyester embosado C30 gota 320 mm', 320, 32.6, 0, 1),
(9, 'SUBPEMSA3200000', 'Polyester embosado C20 santorini 320mm', 320, 34.5, 0, 1),
(10, 'SUPTGTRNC50308', 'PTG transparente C50 308 mm', 308, 15, 0, 1),
(11, '5u649ndfjg', 'sustrto metalizado', 620, 45, 0, 1),
(12, 'sustocoffe', 'sustratocoffebillante 500mm', 500, 100, 0, 1),
(13, 'A050418', 'bopp bco autoadherible', 230, 13.8, 0, 1),
(14, 'PEL030', 'Bopp blanco 35 micras', 400, 41, 0, 1),
(15, 'PEL026', 'PET METALIZADO 400 MM', 400, 57.44, 0, 0),
(16, 'PEL030445', 'Bopp blanco 35 micras 445 MM', 445, 41, 0, 1),
(19, 'PEL026400', 'Pet metalizado 12 mic 400 mm', 400, 57.44, 0, 1),
(20, '666', 'A sustratini', 566, 34, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tablacliente`
--

CREATE TABLE `tablacliente` (
  `ID` int(11) NOT NULL,
  `rfccli` varchar(100) NOT NULL,
  `nombrecli` varchar(100) NOT NULL,
  `domiciliocli` varchar(100) NOT NULL,
  `coloniacli` varchar(100) NOT NULL,
  `ciudadcli` varchar(100) NOT NULL,
  `cpcli` int(5) NOT NULL,
  `telcli` varchar(100) NOT NULL,
  `bajacli` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla cliente para modulo de logÃƒÂ­stica';

--
-- Volcado de datos para la tabla `tablacliente`
--

INSERT INTO `tablacliente` (`ID`, `rfccli`, `nombrecli`, `domiciliocli`, `coloniacli`, `ciudadcli`, `cpcli`, `telcli`, `bajacli`) VALUES
(1, 'BBE90827ALA', 'Bepensa Bebidas SA de CV', 'Calle 7 NÃƒâ€šÃ‚Âª 96', 'Melchor Ocampo II', 'MÃƒÂ©rida, Yuc.', 97165, '01 999 9302626', 1),
(2, '0122222', 'jugos', 'Betania', 'San Felipe de Jesus', 'Leon, Gto.', 37250, '4777724391', 1),
(3, '876543', 'Quindio si para ti', 'Tenazas #123', 'Villas bellas', 'Armenia, QUND', 78453, '56786534', 1),
(4, 'GEP150516R23', 'INDUSTRIA DE REFRECOS S DE RL ', 'Recursos Hidraulicos # 8', 'Reforma', 'Tlalnepantla, Mex.', 15235, '01 55 1150345', 1),
(5, 'MEM900806QZ5', 'Mega Empack , SA de CV', 'Calle 60 # 479 ', 'Parque industrial Yucatan ', 'MÃƒÂ©rida, Yuc.', 97300, '019999822850', 1),
(6, '01', 'Imprenta Patito SA de CV', 'Babel', 'San Felipe de Jesus', 'Leon, Gto.', 37270, '01', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tablaconsuc`
--

CREATE TABLE `tablaconsuc` (
  `idconsuc` int(11) NOT NULL,
  `nombreconsuc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `puestoconsuc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `telconsuc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `movilconsuc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `emailconsuc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `bajaconsuc` int(1) NOT NULL DEFAULT '1',
  `sucFK` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `tablaconsuc`
--

INSERT INTO `tablaconsuc` (`idconsuc`, `nombreconsuc`, `puestoconsuc`, `telconsuc`, `movilconsuc`, `emailconsuc`, `bajaconsuc`, `sucFK`) VALUES
(1, 'Enrique Torres', 'Encargado TI', '56767', '997322', 'elkikelokoteprincess@gmail.com', 1, 'Sucursal buenavista'),
(2, 'Jaime Torres', 'Almacenista', '01 55 7894632', '0', 'jaime.torres@gepp.com', 1, 'Claveria');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tablacontcli`
--

CREATE TABLE `tablacontcli` (
  `idconcli` int(11) NOT NULL,
  `nombreconcli` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `puestoconcli` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `telefonoconcli` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `movilcl` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `emailconcli` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `bajaconcli` int(1) NOT NULL DEFAULT '1',
  `idcliFK` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `tablacontcli`
--

INSERT INTO `tablacontcli` (`idconcli`, `nombreconcli`, `puestoconcli`, `telefonoconcli`, `movilcl`, `emailconcli`, `bajaconcli`, `idcliFK`) VALUES
(1, 'DANIEL RODRIGUEZ REYNA', 'COMPRADOR', '01 999 930 26 03', '0', 'drodriguezr@bepensa.com', 1, 'Bepensa Bebidas SA de CV'),
(2, 'Estevan', 'Gerente', '68762757', '1324356', 'estevanius@gmail.com ', 1, 'Quindio si para ti'),
(3, 'Lidia GÃ³mez', 'Compras Jr', '01 55 7987645', '0', 'lidia.gomez@gepp.com', 1, 'INDUSTRIA DE REFRECOS S DE RL ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tablasuc`
--

CREATE TABLE `tablasuc` (
  `idsuc` int(11) NOT NULL,
  `nombresuc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `domiciliosuc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `coloniasuc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ciudadsuc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `cpsuc` int(8) NOT NULL,
  `telefonosuc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `transpsuc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `bajasuc` int(1) NOT NULL DEFAULT '1',
  `idcliFKS` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `tablasuc`
--

INSERT INTO `tablasuc` (`idsuc`, `nombresuc`, `domiciliosuc`, `coloniasuc`, `ciudadsuc`, `cpsuc`, `telefonosuc`, `transpsuc`, `bajasuc`, `idcliFKS`) VALUES
(1, 'Planta Villahermosa Bepensa', 'Av. Universidad 339', 'Fracc Framboyanes', 'Villahermosa, Tab.', 86020, '+52', 'Tres Guerras', 1, 'Bepensa Bebidas SA de CV'),
(2, 'Planta Merida Bepensa', 'Predio Rustico 13345', 'Jacinto Canek', 'MÃƒÂ©rida, Yuc.', 97227, '9999429990', 'Tres Guerras', 1, 'Bepensa Bebidas SA de CV'),
(3, 'Sucursal 1', 'Betania ', 'San Felipe de Jesus', 'Leon, Gto.', 37250, '4772355875', 'Tres guerras', 1, 'jugos'),
(4, 'Sucursal buenavista', 'vientos negros #450', 'Desastres', 'Buenavista, QUND', 136346, '75889965', 'DHL', 1, 'Quindio si para ti'),
(5, 'Claveria', 'Salvador Manca # 1456', 'DelegaciÃ³n Iztacalco Colonia Xochimanca ', 'Tlalnepantla, Mex.', 14568, '01 55 478 6936', 'Tres Guerras', 1, 'INDUSTRIA DE REFRECOS S DE RL '),
(6, 'Planta Merida', 'Calle 60 parque industrial', 'Parque industrial ', 'MÃƒÂ©rida, Yuc.', 34526, '01 999 9352014', 'Tres Guerras', 1, 'INDUSTRIA DE REFRECOS S DE RL '),
(7, 'Comex Queretaro', 'Acceso III, No. 9 entre acceso IV y ProlongaciÃ³n Bernardo Quintana', 'Parque Industrial Benito Juarez', 'Queretaro, Qro.', 76120, '01 999 9822850  7922', 'Tres Guerras', 1, 'Mega Empack , SA de CV');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoproducto`
--

CREATE TABLE `tipoproducto` (
  `id` int(11) NOT NULL,
  `tipo` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `juegoParametros` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `juegoProcesos` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `presentacion` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `tipoproducto`
--

INSERT INTO `tipoproducto` (`id`, `tipo`, `alias`, `juegoParametros`, `juegoProcesos`, `presentacion`, `baja`) VALUES
(1, 'BS', 'BS', 'JPSBS08', 'JPBS08', 'Predeterminados', 1),
(2, 'Termoencogible', 'Termoencogible', 'JPMTERMOENCOGIBLE', 'JPPManga01', 'Predeterminados', 0),
(26, 'hologrami', 'hologrami', 'JPShologrami09', 'JPhologrami09', 'holograma', 0),
(27, 'Quindio 2018', 'Quindio 2018', 'JPSQuindio 201810', 'JPQuindio 201810', 'EtiqAbierta', 0),
(28, 'Chiva cola', 'Chiva cola', 'JPSChiva cola08', 'JPChiva cola08', 'EtiqAbierta', 0),
(29, 'Chiva Cola Cola', 'Chiva Cola Cola', 'JPSChiva Cola Cola09', 'JPChiva Cola Cola09', 'EtiqAbierta', 0),
(30, 'Manga Termoencogible', 'Manga Termoencogible', 'JPSManga Termoencogible09', 'JPManga Termoencogible09', 'Predeterminados', 1),
(31, 'Etiqueta abierta', 'Etiqueta abierta', 'JPSEtiqueta abierta12', 'JPEtiqueta abierta12', 'EtiqAbierta', 1),
(32, 'Fail', 'Fail', 'JPSFail12', 'JPFail12', 'EtiqAbierta', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidades`
--

CREATE TABLE `unidades` (
  `idUnidad` int(11) NOT NULL,
  `identificadorUnidad` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nombreUnidad` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `unidades`
--

INSERT INTO `unidades` (`idUnidad`, `identificadorUnidad`, `nombreUnidad`, `baja`) VALUES
(1, 'kgs', 'Kilogramos', 1),
(3, 'lts', 'Litros', 1),
(4, 'piezas', 'piezas', 1),
(6, 'm2', 'Metro cuadrado', 1),
(7, 'm3', 'Metro cÃºbico', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) NOT NULL,
  `nombre` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `usuario` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `contrasenia` blob NOT NULL,
  `perfil` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `rol` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `contrasenia`, `perfil`, `rol`) VALUES
(1, 'Cristian Alberto', 'liraTI', 0x1b7683dbe228ed9f69a3fe7935928899, 'PF1', 'Programador'),
(2, 'Erik Martin', 'castillo66', 0x72a4e18f7d28a47a590b9c6e2f6a68bd, 'PF2', 'Programador'),
(3, 'Juan Jose', 'jnYei', 0x578df9306e13aa27c80add5fe0a87a9f, 'PF3', 'ProducciÃƒÂ³n'),
(4, 'Felipe', 'Felipe', 0x578df9306e13aa27c80add5fe0a87a9f, 'PF4', 'ProducciÃƒÂ³n'),
(6, 'Laura Elena', 'lauTI', 0x578df9306e13aa27c80add5fe0a87a9f, 'PF17', 'Jefa TI'),
(8, 'Alfonso', 'AlfonsoTI', 0x578df9306e13aa27c80add5fe0a87a9f, 'PF18', 'Programador'),
(9, 'Elviss', 'elvistek', 0xc5c2fc5ea02817d5e1bb86f20abc1261, 'PF19', 'Cantante de Rock & Roll'),
(10, 'Ana Lilia', 'asaldaÃ±a', 0x578df9306e13aa27c80add5fe0a87a9f, 'PF22', 'Jefe de Compras'),
(11, 'Araceli', 'alozano', 0x578df9306e13aa27c80add5fe0a87a9f, 'PF23', 'Jefe de Logistica'),
(13, 'Luis Felipe', 'fmedina', 0x578df9306e13aa27c80add5fe0a87a9f, 'PF25', 'Auxiliar de Produccion'),
(14, 'JosÃ© Antonio', 'jhernandez', 0x578df9306e13aa27c80add5fe0a87a9f, 'PF24', 'Gerente de Operaciones'),
(15, 'Christian Abraham', 'aramos', 0x578df9306e13aa27c80add5fe0a87a9f, 'PF26', 'Coordinador de calidad'),
(17, 'Erik Castillo', 'core', 0xbe0cc01b2d027b40e2ef73f5ceaa72de, 'PF0', 'Administrador'),
(18, 'Sandra', 'rdsandra', 0x578df9306e13aa27c80add5fe0a87a9f, 'PF27', 'Jefe de RD');

-- --------------------------------------------------------

--
-- Estructura para la vista `impresioness`
--
DROP TABLE IF EXISTS `impresioness`;

CREATE ALGORITHM=UNDEFINED DEFINER=`kronos`@`localhost` SQL SECURITY DEFINER VIEW `impresioness`  AS  select `impresiones`.`descripcionImpresion` AS `descripcionImpresion` from `impresiones` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `shoTableroBS`
--
DROP TABLE IF EXISTS `shoTableroBS`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `shoTableroBS`  AS  select concat(`bandaseguridad`.`nombreBanda`,'|',`bandaspp`.`nombreBSPP`) AS `productos`,`bandaspp`.`nombreBSPP` AS `descripcionImpresion`,`bandaspp`.`IdBSPP` AS `id`,`bandaspp`.`sustrato` AS `sustrato`,concat('') AS `nombreBanda`,`bandaseguridad`.`anchura` AS `anchura`,`bandaspp`.`anchuraLaminado` AS `alturaEtiqueta`,concat('') AS `tintas` from (`bandaseguridad` left join `bandaspp` on((`bandaseguridad`.`nombreBanda` = `bandaspp`.`identificadorBS`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `showTablero`
--
DROP TABLE IF EXISTS `showTablero`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `showTablero`  AS  select `producto`.`tipo` AS `tipo`,concat(`producto`.`descripcion`,' | ',`impresiones`.`descripcionImpresion`) AS `productos`,`impresiones`.`descripcionImpresion` AS `descripcionImpresion`,`impresiones`.`holograma` AS `holograma`,`impresiones`.`id` AS `id`,`impresiones`.`sustrato` AS `sustrato`,`impresiones`.`nombreBanda` AS `nombreBanda`,`impresiones`.`anchoPelicula` AS `anchoPelicula`,`impresiones`.`alturaEtiqueta` AS `alturaEtiqueta`,`impresiones`.`tintas` AS `tintas` from (`producto` left join `impresiones` on((`producto`.`descripcion` = `impresiones`.`descripcionDisenio`))) where ((`impresiones`.`baja` = 1) and (`producto`.`baja` = 1)) order by `impresiones`.`id` ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accesos`
--
ALTER TABLE `accesos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `am_proveedores`
--
ALTER TABLE `am_proveedores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `anillox`
--
ALTER TABLE `anillox`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identificadorAnillox` (`identificadorAnillox`);

--
-- Indices de la tabla `baja_BS`
--
ALTER TABLE `baja_BS`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `bandaseguridad`
--
ALTER TABLE `bandaseguridad`
  ADD PRIMARY KEY (`IDBanda`),
  ADD UNIQUE KEY `identificador` (`identificador`),
  ADD UNIQUE KEY `nombreBanda` (`nombreBanda`);

--
-- Indices de la tabla `bandaspp`
--
ALTER TABLE `bandaspp`
  ADD PRIMARY KEY (`IdBSPP`),
  ADD UNIQUE KEY `identificadorBSPP` (`identificadorBSPP`),
  ADD UNIQUE KEY `nombreBSPP` (`nombreBSPP`);

--
-- Indices de la tabla `bloquesmateriaprima`
--
ALTER TABLE `bloquesmateriaprima`
  ADD PRIMARY KEY (`idBloque`);

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ciudad`
--
ALTER TABLE `ciudad`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombreCity` (`nombreCity`);

--
-- Indices de la tabla `clasificacion`
--
ALTER TABLE `clasificacion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identificador` (`identificador`);

--
-- Indices de la tabla `codigosbarras`
--
ALTER TABLE `codigosbarras`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `codigo_2` (`codigo`),
  ADD KEY `producto` (`producto`),
  ADD KEY `proceso` (`proceso`),
  ADD KEY `noop` (`noop`),
  ADD KEY `id` (`id`);

--
-- Indices de la tabla `codigos_baja`
--
ALTER TABLE `codigos_baja`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `confirmarprod`
--
ALTER TABLE `confirmarprod`
  ADD PRIMARY KEY (`idConfi`);

--
-- Indices de la tabla `consumos`
--
ALTER TABLE `consumos`
  ADD PRIMARY KEY (`IDConsumo`);

--
-- Indices de la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `folio` (`folio`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `elementosconsumo`
--
ALTER TABLE `elementosconsumo`
  ADD PRIMARY KEY (`idElemento`);

--
-- Indices de la tabla `embarque`
--
ALTER TABLE `embarque`
  ADD PRIMARY KEY (`idEmbarque`),
  ADD UNIQUE KEY `numEmbarque` (`numEmbarque`),
  ADD KEY `sucEmbFK` (`sucEmbFK`);

--
-- Indices de la tabla `empaque`
--
ALTER TABLE `empaque`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `1` (`ID`),
  ADD UNIQUE KEY `numemple` (`numemple`),
  ADD KEY `ID` (`ID`),
  ADD KEY `ID_2` (`ID`);

--
-- Indices de la tabla `ensambleempaques`
--
ALTER TABLE `ensambleempaques`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombreEstado` (`nombreEstado`);

--
-- Indices de la tabla `hlogpproducto`
--
ALTER TABLE `hlogpproducto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `impresiones`
--
ALTER TABLE `impresiones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `descripcionImpresion` (`descripcionImpresion`),
  ADD UNIQUE KEY `codigoImpresion` (`codigoImpresion`),
  ADD KEY `descripcionDisenio` (`descripcionDisenio`),
  ADD KEY `descripcionImpresion_2` (`descripcionImpresion`),
  ADD KEY `codigoImpresion_2` (`codigoImpresion`),
  ADD KEY `descripcionDisenio_2` (`descripcionDisenio`);

--
-- Indices de la tabla `juegoparametros`
--
ALTER TABLE `juegoparametros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `identificadorJuego` (`identificadorJuego`),
  ADD KEY `nombreparametro` (`nombreparametro`);

--
-- Indices de la tabla `juegoprocesos`
--
ALTER TABLE `juegoprocesos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `descripcionProceso` (`descripcionProceso`),
  ADD KEY `identificadorJuego` (`identificadorJuego`);

--
-- Indices de la tabla `juegoscilindros`
--
ALTER TABLE `juegoscilindros`
  ADD PRIMARY KEY (`IDCilindro`),
  ADD UNIQUE KEY `identificadorCilindro` (`identificadorCilindro`),
  ADD KEY `identificadorCilindro_2` (`identificadorCilindro`),
  ADD KEY `descripcionImpresion` (`descripcionImpresion`);

--
-- Indices de la tabla `juegoscireles`
--
ALTER TABLE `juegoscireles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identificadorJuego` (`identificadorJuego`),
  ADD KEY `identificadorJuego_2` (`identificadorJuego`),
  ADD KEY `producto` (`producto`);

--
-- Indices de la tabla `lotes`
--
ALTER TABLE `lotes`
  ADD PRIMARY KEY (`idLote`),
  ADD UNIQUE KEY `referenciaLote` (`referenciaLote`),
  ADD KEY `idLote` (`idLote`),
  ADD KEY `referenciaLote_2` (`referenciaLote`),
  ADD KEY `bloque` (`bloque`),
  ADD KEY `noop` (`noop`),
  ADD KEY `juegoLote` (`juegoLote`);

--
-- Indices de la tabla `lotestemporales`
--
ALTER TABLE `lotestemporales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `noLote` (`noLote`),
  ADD KEY `id` (`id`),
  ADD KEY `referencia` (`referencia`);

--
-- Indices de la tabla `maquinas`
--
ALTER TABLE `maquinas`
  ADD PRIMARY KEY (`idMaq`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD UNIQUE KEY `descripcionMaq` (`descripcionMaq`),
  ADD KEY `idMaq` (`idMaq`),
  ADD KEY `codigo_2` (`codigo`),
  ADD KEY `descripcionMaq_2` (`descripcionMaq`),
  ADD KEY `subproceso` (`subproceso`),
  ADD KEY `tipoProducto` (`tipoProducto`);

--
-- Indices de la tabla `merma`
--
ALTER TABLE `merma`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `i_codigo` (`codigo`),
  ADD KEY `codigo` (`codigo`),
  ADD KEY `producto` (`producto`),
  ADD KEY `proceso` (`proceso`);

--
-- Indices de la tabla `modulo`
--
ALTER TABLE `modulo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `ordencompra`
--
ALTER TABLE `ordencompra`
  ADD PRIMARY KEY (`idorden`),
  ADD UNIQUE KEY `orden` (`orden`),
  ADD KEY `sucFK` (`sucFK`);

--
-- Indices de la tabla `pantone`
--
ALTER TABLE `pantone`
  ADD PRIMARY KEY (`idPantone`),
  ADD KEY `descripcionPantone` (`descripcionPantone`),
  ADD KEY `codigoPantone` (`codigoPantone`);

--
-- Indices de la tabla `pantonepcapa`
--
ALTER TABLE `pantonepcapa`
  ADD PRIMARY KEY (`idPantonePCapa`),
  ADD KEY `descripcionPantone` (`descripcionPantone`),
  ADD KEY `codigoImpresion` (`codigoImpresion`),
  ADD KEY `codigoCapa` (`codigoCapa`);

--
-- Indices de la tabla `parametros`
--
ALTER TABLE `parametros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `nombreParametro` (`nombreParametro`);

--
-- Indices de la tabla `procesos`
--
ALTER TABLE `procesos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `descripcionProceso` (`descripcionProceso`),
  ADD KEY `packParametros` (`packParametros`);

--
-- Indices de la tabla `procorte`
--
ALTER TABLE `procorte`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto` (`producto`),
  ADD KEY `noop` (`noop`),
  ADD KEY `operador` (`operador`),
  ADD KEY `rollo` (`rollo`),
  ADD KEY `fecha` (`fecha`),
  ADD KEY `id` (`id`);

--
-- Indices de la tabla `produccion`
--
ALTER TABLE `produccion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `juegoLotes` (`juegoLotes`),
  ADD KEY `id` (`id`),
  ADD KEY `nombreProducto` (`nombreProducto`),
  ADD KEY `juegoLotes_2` (`juegoLotes`),
  ADD KEY `fechaProduccion` (`fechaProduccion`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `descripcion` (`descripcion`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `tipo` (`tipo`),
  ADD KEY `codigo_2` (`codigo`),
  ADD KEY `descripcion_2` (`descripcion`);

--
-- Indices de la tabla `productoscliente`
--
ALTER TABLE `productoscliente`
  ADD PRIMARY KEY (`IdProdCliente`),
  ADD KEY `IdentificadorCliente` (`IdentificadorCliente`),
  ADD KEY `nombre` (`nombre`);

--
-- Indices de la tabla `proembosado`
--
ALTER TABLE `proembosado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `noop` (`noop`),
  ADD KEY `id` (`id`),
  ADD KEY `fecha` (`fecha`),
  ADD KEY `producto` (`producto`),
  ADD KEY `operador` (`operador`),
  ADD KEY `lote` (`lote`);

--
-- Indices de la tabla `profoliado`
--
ALTER TABLE `profoliado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto` (`producto`),
  ADD KEY `id` (`id`),
  ADD KEY `fecha` (`fecha`),
  ADD KEY `operador` (`operador`),
  ADD KEY `bobina` (`bobina`),
  ADD KEY `noop` (`noop`);

--
-- Indices de la tabla `profusion`
--
ALTER TABLE `profusion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto` (`producto`),
  ADD KEY `fecha` (`fecha`),
  ADD KEY `operador` (`operador`),
  ADD KEY `bobina` (`bobina`),
  ADD KEY `noop` (`noop`);

--
-- Indices de la tabla `proimpresion`
--
ALTER TABLE `proimpresion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `operador` (`operador`),
  ADD KEY `juegoCilindros` (`juegoCilindros`),
  ADD KEY `lote` (`lote`),
  ADD KEY `producto` (`producto`),
  ADD KEY `noop` (`noop`);

--
-- Indices de la tabla `proimpresion-flexografica`
--
ALTER TABLE `proimpresion-flexografica`
  ADD PRIMARY KEY (`id`),
  ADD KEY `operador` (`operador`),
  ADD KEY `producto` (`producto`),
  ADD KEY `fecha` (`fecha`),
  ADD KEY `noop` (`noop`);

--
-- Indices de la tabla `prolaminado`
--
ALTER TABLE `prolaminado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto` (`producto`),
  ADD KEY `fecha` (`fecha`),
  ADD KEY `noop` (`noop`),
  ADD KEY `operador` (`operador`),
  ADD KEY `lote` (`lote`);

--
-- Indices de la tabla `prolaminado 2`
--
ALTER TABLE `prolaminado 2`
  ADD KEY `producto` (`producto`),
  ADD KEY `fecha` (`fecha`),
  ADD KEY `noop` (`noop`),
  ADD KEY `operador` (`operador`),
  ADD KEY `lote` (`lote`);

--
-- Indices de la tabla `prorefilado`
--
ALTER TABLE `prorefilado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto` (`producto`),
  ADD KEY `fecha` (`fecha`),
  ADD KEY `operador` (`operador`),
  ADD KEY `lote` (`lote`),
  ADD KEY `noop` (`noop`);

--
-- Indices de la tabla `prorevision`
--
ALTER TABLE `prorevision`
  ADD PRIMARY KEY (`id`),
  ADD KEY `operador` (`operador`),
  ADD KEY `rollo` (`rollo`),
  ADD KEY `producto` (`producto`),
  ADD KEY `noop` (`noop`);

--
-- Indices de la tabla `prorevision 2`
--
ALTER TABLE `prorevision 2`
  ADD PRIMARY KEY (`id`),
  ADD KEY `operador` (`operador`),
  ADD KEY `rollo` (`rollo`),
  ADD KEY `producto` (`producto`),
  ADD KEY `fecha` (`fecha`),
  ADD KEY `noop` (`noop`);

--
-- Indices de la tabla `prosliteo`
--
ALTER TABLE `prosliteo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto` (`producto`),
  ADD KEY `fecha` (`fecha`),
  ADD KEY `noop` (`noop`),
  ADD KEY `operador` (`operador`),
  ADD KEY `lote` (`lote`);

--
-- Indices de la tabla `prosuajado`
--
ALTER TABLE `prosuajado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto` (`producto`),
  ADD KEY `fecha` (`fecha`),
  ADD KEY `operador` (`operador`),
  ADD KEY `rollo` (`rollo`),
  ADD KEY `noop` (`noop`);

--
-- Indices de la tabla `protroquelado`
--
ALTER TABLE `protroquelado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto` (`producto`),
  ADD KEY `fecha` (`fecha`),
  ADD KEY `operador` (`operador`),
  ADD KEY `rollo` (`rollo`),
  ADD KEY `noop` (`noop`);

--
-- Indices de la tabla `pruebas`
--
ALTER TABLE `pruebas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reporte`
--
ALTER TABLE `reporte`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `requerimientoprod`
--
ALTER TABLE `requerimientoprod`
  ADD PRIMARY KEY (`idReq`);

--
-- Indices de la tabla `rollo`
--
ALTER TABLE `rollo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `referencia` (`referencia`),
  ADD KEY `producto` (`producto`),
  ADD KEY `codigo` (`codigo`),
  ADD KEY `cdgEmbarque` (`cdgEmbarque`);

--
-- Indices de la tabla `suaje`
--
ALTER TABLE `suaje`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identificadorSuaje` (`identificadorSuaje`),
  ADD KEY `identificadorSuaje_2` (`identificadorSuaje`),
  ADD KEY `codigo` (`codigo`),
  ADD KEY `descripcionImpresion` (`descripcionImpresion`);

--
-- Indices de la tabla `sustrato`
--
ALTER TABLE `sustrato`
  ADD PRIMARY KEY (`idSustrato`),
  ADD UNIQUE KEY `descripcionSustrato` (`descripcionSustrato`),
  ADD UNIQUE KEY `codigoSustrato` (`codigoSustrato`),
  ADD KEY `descripcionSustrato_2` (`descripcionSustrato`),
  ADD KEY `codigoSustrato_2` (`codigoSustrato`);

--
-- Indices de la tabla `tablacliente`
--
ALTER TABLE `tablacliente`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `nombrecli` (`nombrecli`),
  ADD UNIQUE KEY `rfccli` (`rfccli`);

--
-- Indices de la tabla `tablaconsuc`
--
ALTER TABLE `tablaconsuc`
  ADD PRIMARY KEY (`idconsuc`),
  ADD KEY `FKCliente` (`sucFK`);

--
-- Indices de la tabla `tablacontcli`
--
ALTER TABLE `tablacontcli`
  ADD PRIMARY KEY (`idconcli`),
  ADD KEY `idcliFK` (`idcliFK`);

--
-- Indices de la tabla `tablasuc`
--
ALTER TABLE `tablasuc`
  ADD PRIMARY KEY (`idsuc`),
  ADD UNIQUE KEY `nombresuc` (`nombresuc`),
  ADD KEY `idcliFKS` (`idcliFKS`);

--
-- Indices de la tabla `tipoproducto`
--
ALTER TABLE `tipoproducto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipo` (`tipo`),
  ADD KEY `id` (`id`),
  ADD KEY `tipo_2` (`tipo`);

--
-- Indices de la tabla `unidades`
--
ALTER TABLE `unidades`
  ADD PRIMARY KEY (`idUnidad`),
  ADD KEY `identificadorUnidad` (`identificadorUnidad`),
  ADD KEY `nombreUnidad` (`nombreUnidad`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `perfil` (`perfil`),
  ADD KEY `usuario_2` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accesos`
--
ALTER TABLE `accesos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=497;
--
-- AUTO_INCREMENT de la tabla `am_proveedores`
--
ALTER TABLE `am_proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `anillox`
--
ALTER TABLE `anillox`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `baja_BS`
--
ALTER TABLE `baja_BS`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `bandaseguridad`
--
ALTER TABLE `bandaseguridad`
  MODIFY `IDBanda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `bandaspp`
--
ALTER TABLE `bandaspp`
  MODIFY `IdBSPP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT de la tabla `bloquesmateriaprima`
--
ALTER TABLE `bloquesmateriaprima`
  MODIFY `idBloque` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `cache`
--
ALTER TABLE `cache`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT de la tabla `caja`
--
ALTER TABLE `caja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `ciudad`
--
ALTER TABLE `ciudad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT de la tabla `clasificacion`
--
ALTER TABLE `clasificacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `codigosbarras`
--
ALTER TABLE `codigosbarras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=402;
--
-- AUTO_INCREMENT de la tabla `codigos_baja`
--
ALTER TABLE `codigos_baja`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `confirmarprod`
--
ALTER TABLE `confirmarprod`
  MODIFY `idConfi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `consumos`
--
ALTER TABLE `consumos`
  MODIFY `IDConsumo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT de la tabla `departamento`
--
ALTER TABLE `departamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `elementosconsumo`
--
ALTER TABLE `elementosconsumo`
  MODIFY `idElemento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=678;
--
-- AUTO_INCREMENT de la tabla `embarque`
--
ALTER TABLE `embarque`
  MODIFY `idEmbarque` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `empaque`
--
ALTER TABLE `empaque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT de la tabla `ensambleempaques`
--
ALTER TABLE `ensambleempaques`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT de la tabla `hlogpproducto`
--
ALTER TABLE `hlogpproducto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `impresiones`
--
ALTER TABLE `impresiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT de la tabla `juegoparametros`
--
ALTER TABLE `juegoparametros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=584;
--
-- AUTO_INCREMENT de la tabla `juegoprocesos`
--
ALTER TABLE `juegoprocesos`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=397;
--
-- AUTO_INCREMENT de la tabla `juegoscilindros`
--
ALTER TABLE `juegoscilindros`
  MODIFY `IDCilindro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `juegoscireles`
--
ALTER TABLE `juegoscireles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `lotes`
--
ALTER TABLE `lotes`
  MODIFY `idLote` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;
--
-- AUTO_INCREMENT de la tabla `lotestemporales`
--
ALTER TABLE `lotestemporales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT de la tabla `maquinas`
--
ALTER TABLE `maquinas`
  MODIFY `idMaq` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT de la tabla `merma`
--
ALTER TABLE `merma`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=230;
--
-- AUTO_INCREMENT de la tabla `modulo`
--
ALTER TABLE `modulo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT de la tabla `ordencompra`
--
ALTER TABLE `ordencompra`
  MODIFY `idorden` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `pantone`
--
ALTER TABLE `pantone`
  MODIFY `idPantone` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT de la tabla `pantonepcapa`
--
ALTER TABLE `pantonepcapa`
  MODIFY `idPantonePCapa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT de la tabla `parametros`
--
ALTER TABLE `parametros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT de la tabla `procesos`
--
ALTER TABLE `procesos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT de la tabla `procorte`
--
ALTER TABLE `procorte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `produccion`
--
ALTER TABLE `produccion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `productoscliente`
--
ALTER TABLE `productoscliente`
  MODIFY `IdProdCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `proembosado`
--
ALTER TABLE `proembosado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `profoliado`
--
ALTER TABLE `profoliado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `profusion`
--
ALTER TABLE `profusion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `proimpresion`
--
ALTER TABLE `proimpresion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT de la tabla `proimpresion-flexografica`
--
ALTER TABLE `proimpresion-flexografica`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `prolaminado`
--
ALTER TABLE `prolaminado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT de la tabla `prorefilado`
--
ALTER TABLE `prorefilado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=239;
--
-- AUTO_INCREMENT de la tabla `prorevision`
--
ALTER TABLE `prorevision`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT de la tabla `prorevision 2`
--
ALTER TABLE `prorevision 2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `prosliteo`
--
ALTER TABLE `prosliteo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `prosuajado`
--
ALTER TABLE `prosuajado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `protroquelado`
--
ALTER TABLE `protroquelado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `pruebas`
--
ALTER TABLE `pruebas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `reporte`
--
ALTER TABLE `reporte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1145;
--
-- AUTO_INCREMENT de la tabla `requerimientoprod`
--
ALTER TABLE `requerimientoprod`
  MODIFY `idReq` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT de la tabla `rollo`
--
ALTER TABLE `rollo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `suaje`
--
ALTER TABLE `suaje`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `sustrato`
--
ALTER TABLE `sustrato`
  MODIFY `idSustrato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT de la tabla `tablacliente`
--
ALTER TABLE `tablacliente`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `tablaconsuc`
--
ALTER TABLE `tablaconsuc`
  MODIFY `idconsuc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `tablacontcli`
--
ALTER TABLE `tablacontcli`
  MODIFY `idconcli` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `tablasuc`
--
ALTER TABLE `tablasuc`
  MODIFY `idsuc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `tipoproducto`
--
ALTER TABLE `tipoproducto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT de la tabla `unidades`
--
ALTER TABLE `unidades`
  MODIFY `idUnidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
