/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.6.12 : Database - maternidad
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`maternidad` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `maternidad`;

/*Table structure for table `s_perfil_privilegio` */

DROP TABLE IF EXISTS `s_perfil_privilegio`;

CREATE TABLE `s_perfil_privilegio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_perfil` int(11) DEFAULT NULL,
  `cod_submodulo` int(11) DEFAULT NULL,
  `agregar` tinyint(1) DEFAULT '0',
  `modificar` tinyint(1) DEFAULT '0',
  `eliminar` tinyint(1) DEFAULT '0',
  `consultar` tinyint(1) DEFAULT '0',
  `imprimir` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `codigo_perfil` (`codigo_perfil`),
  KEY `cod_submodulo` (`cod_submodulo`),
  CONSTRAINT `s_perfil_privilegio_ibfk_1` FOREIGN KEY (`codigo_perfil`) REFERENCES `s_perfil` (`codigo_perfil`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `s_perfil_privilegio_ibfk_2` FOREIGN KEY (`cod_submodulo`) REFERENCES `s_sub_modulo` (`cod_submodulo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

/*Data for the table `s_perfil_privilegio` */

insert  into `s_perfil_privilegio`(`id`,`codigo_perfil`,`cod_submodulo`,`agregar`,`modificar`,`eliminar`,`consultar`,`imprimir`) values (1,1,1,0,0,0,0,0),(2,1,2,0,0,0,0,0),(3,1,3,0,0,0,0,0),(4,1,4,0,0,0,0,0),(5,1,5,0,0,0,0,0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
