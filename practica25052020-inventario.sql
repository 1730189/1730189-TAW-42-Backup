-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 15-06-2020 a las 03:39:41
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `practica25052020-inventario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id_category` int(11) NOT NULL AUTO_INCREMENT,
  `name_category` varchar(255) NOT NULL,
  `description_category` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`id_category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `categories`
--

INSERT INTO `categories` (`id_category`, `name_category`, `description_category`, `date_added`) VALUES
(1, 'Categoria Uno', 'Texto as', '0000-00-00 00:00:00'),
(2, 'Categoria Dos', 'Texto Dos', '0000-00-00 00:00:00'),
(3, 'Categoria Tres', 'Texto Tres', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial`
--

CREATE TABLE IF NOT EXISTS `historial` (
  `id_historial` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `note` varchar(255) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id_historial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id_product` int(11) NOT NULL AUTO_INCREMENT,
  `code_product` char(20) NOT NULL,
  `name_product` char(255) NOT NULL,
  `price_product` double NOT NULL,
  `stock` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  PRIMARY KEY (`id_product`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=10 ;

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`id_product`, `code_product`, `name_product`, `price_product`, `stock`, `id_category`) VALUES
(6, '001', 'Nombre Uno', 200, 20, 1),
(7, '002', 'Nombre Dos', 250, 15, 2),
(8, '003', 'Nombre Tres', 500, 30, 3),
(9, '003', 'Nombre Tres', 500, 30, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `user_name` varchar(64) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_email` varchar(64) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`user_id`, `firstname`, `lastname`, `user_name`, `user_password`, `user_email`, `date_added`) VALUES
(1, 'Erick', 'Aguilar', 'admin', 'admin', 'admin@admin', '2020-06-09 20:40:26');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
