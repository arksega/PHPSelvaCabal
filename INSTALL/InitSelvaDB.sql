-- MySQL dump 10.17  Distrib 10.3.17-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: Selva
-- ------------------------------------------------------
-- Server version	10.3.17-MariaDB

/* Assuming that you invoked MariaDB with the following command line:
     mysql -p < InitSelvaDB.sql
*/

CREATE DATABASE `Selva` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

USE Selva;

SET FOREIGN_KEY_CHECKS=0;

--
-- Table structure for table `Familias`
--

DROP TABLE IF EXISTS `Familias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Familias` (
  `FamiliaID` int(11) NOT NULL AUTO_INCREMENT,
  `Familia` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`FamiliaID`),
  UNIQUE KEY `Familia` (`Familia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Familias`
--

LOCK TABLES `Familias` WRITE;
/*!40000 ALTER TABLE `Familias` DISABLE KEYS */;
INSERT INTO `Familias` VALUES ( 1, 'Do NOT DELETE this record'),
(NULL,'acanthaceae'),(NULL,'aizoaceae'),(NULL,'altingiaceae'),(NULL,'amaranthaceae'),
(NULL,'amaryllidaceae'),(NULL,'anacampserotaceae'),(NULL,'anacardiaceae'),
(NULL,'annonaceae'),(NULL,'apocynaceae'),(NULL,'araceae'),(NULL,'araliaceae'),
(NULL,'arecaceae'),(NULL,'asparagaceae'),(NULL,'aspleniaceae'),(NULL,'begoniaceae'),
(NULL,'bignoniaceae'),(NULL,'blechnaceae'),(NULL,'bromeliaceae'),(NULL,'burseraceae'),
(NULL,'cactaceae'),(NULL,'cannaceae'),(NULL,'caricaceae'),(NULL,'caryophyllaceae'),
(NULL,'clusiaceae'),(NULL,'combretaceae'),(NULL,'commelinaceae'),(NULL,'compositae'),
(NULL,'convolvulaceae'),(NULL,'crassulaceae'),(NULL,'cucurbitaceae'),
(NULL,'cupressaceae'),(NULL,'cyatheaceae'),(NULL,'cycadaceae'),(NULL,'cyperaceae'),
(NULL,'dicksoniaceae'),(NULL,'didiereaceae'),(NULL,'dioscoreaceae'),
(NULL,'euphorbiaceae'),(NULL,'fouquieriaceae'),(NULL,'geraniaceae'),
(NULL,'ginkgoaceae'),(NULL,'heliconiaceae'),(NULL,'iridaceae'),(NULL,'juncaceae'),
(NULL,'lamiaceae'),(NULL,'lauraceae'),(NULL,'leguminosae'),(NULL,'liliaceae'),
(NULL,'lomariopsidaceae'),(NULL,'loranthaceae'),(NULL,'lycopodiaceae'),
(NULL,'lythraceae'),(NULL,'magnoliaceae'),(NULL,'malvaceae'),(NULL,'marantaceae'),
(NULL,'melastomataceae'),(NULL,'meliaceae'),(NULL,'moraceae'),(NULL,'moringaceae'),
(NULL,'musaceae'),(NULL,'myrtaceae'),(NULL,'nyctaginaceae'),(NULL,'oleaceae'),
(NULL,'onagraceae'),(NULL,'orchidaceae'),(NULL,'pandanaceae'),(NULL,'papaveraceae'),
(NULL,'passifloraceae'),(NULL,'pinaceae'),(NULL,'piperaceae'),(NULL,'poaceae'),
(NULL,'podocarpaceae'),(NULL,'polygonaceae'),(NULL,'portulacaceae'),(NULL,'proteaceae'),
(NULL,'rosaceae'),(NULL,'rubiaceae'),(NULL,'rutaceae'),(NULL,'salicaceae'),
(NULL,'sapindaceae'),(NULL,'solanaceae'),(NULL,'strelitziaceae'),(NULL,'typhaceae'),
(NULL,'urticaceae'),(NULL,'verbenaceae'),(NULL,'vitaceae'),(NULL,'xanthorrhoeaceae'),
(NULL,'zamiaceae'),(NULL,'zingiberaceae');
/*!40000 ALTER TABLE `Familias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Fotos`
--

DROP TABLE IF EXISTS `Fotos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Fotos` (
  `FotoID` int(11) NOT NULL AUTO_INCREMENT,
  `NombreID` int(11) NOT NULL DEFAULT 0,
  `Direccion` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`FotoID`),
  KEY `NombreID` (`NombreID`),
  CONSTRAINT `Fotos_ibfk_1` FOREIGN KEY (`NombreID`) REFERENCES `Nombres` (`NombreID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Fotos`
--

LOCK TABLES `Fotos` WRITE;
/*!40000 ALTER TABLE `Fotos` DISABLE KEYS */;
INSERT INTO `Fotos` VALUES (1,1,'Do NOT DELETE this record');
/*!40000 ALTER TABLE `Fotos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Logs`
--

DROP TABLE IF EXISTS `Logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Logs` (
  `LogID` int(9) NOT NULL AUTO_INCREMENT,
  `Tiempo` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `UsuarioID` int(4) NOT NULL DEFAULT 0,
  `Query` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`LogID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Logs`
--

LOCK TABLES `Logs` WRITE;
/*!40000 ALTER TABLE `Logs` DISABLE KEYS */;
INSERT INTO `Logs` VALUES (1,CURDATE(),1,'Run SelvaDB Init');
/*!40000 ALTER TABLE `Logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Nombres`
--

DROP TABLE IF EXISTS `Nombres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Nombres` (
  `NombreID` int(11) NOT NULL AUTO_INCREMENT,
  `FamiliaID` int(11) NOT NULL DEFAULT 0,
  `Nombre` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `Fecha` date DEFAULT NULL,
  `ProveedorID` int(3) DEFAULT NULL,
  `Precio` float(10,2) DEFAULT NULL,
  PRIMARY KEY (`NombreID`),
  UNIQUE KEY `Nombre` (`Nombre`),
  KEY `FamiliaID` (`FamiliaID`),
  KEY `ProveedorID` (`ProveedorID`),
  CONSTRAINT `Nombres_ibfk_1` FOREIGN KEY (`FamiliaID`) REFERENCES `Familias` (`FamiliaID`),
  CONSTRAINT `Nombres_ibfk_2` FOREIGN KEY (`ProveedorID`) REFERENCES `Proveedores` (`ProveedorID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Nombres`
--

LOCK TABLES `Nombres` WRITE;
/*!40000 ALTER TABLE `Nombres` DISABLE KEYS */;
INSERT INTO `Nombres` VALUES (1,1,'Selecciona un Nombre',CURDATE(),1,0.00);
/*!40000 ALTER TABLE `Nombres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `NombresVulgares`
--

DROP TABLE IF EXISTS `NombresVulgares`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `NombresVulgares` (
  `NombreVulgarID` int(11) NOT NULL AUTO_INCREMENT,
  `NombreID` int(11) NOT NULL DEFAULT 0,
  `NombreVulgar` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`NombreVulgarID`),
  KEY `NombreID` (`NombreID`),
  CONSTRAINT `NombresVulgares_ibfk_1` FOREIGN KEY (`NombreID`) REFERENCES `Nombres` (`NombreID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Notas`
--

DROP TABLE IF EXISTS `Notas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Notas` (
  `NotaID` int(11) NOT NULL AUTO_INCREMENT,
  `NombreID` int(11) NOT NULL,
  `Nota` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`NotaID`),
  KEY `NombreID` (`NombreID`),
  CONSTRAINT `Notas_ibfk_1` FOREIGN KEY (`NombreID`) REFERENCES `Nombres` (`NombreID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Proveedores`
--

DROP TABLE IF EXISTS `Proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Proveedores` (
  `ProveedorID` int(3) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(95) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `RFC` varchar(15) COLLATE utf8_unicode_ci DEFAULT '',
  `Direccion` varchar(75) COLLATE utf8_unicode_ci DEFAULT '',
  `Colonia` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `Ciudad` varchar(25) COLLATE utf8_unicode_ci DEFAULT '',
  `Estado` varchar(40) COLLATE utf8_unicode_ci DEFAULT '',
  `Pais` varchar(25) COLLATE utf8_unicode_ci DEFAULT '',
  `CP` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `Tel1` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `Tel2` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `URL` varchar(75) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Desactivado` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `Observaciones` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Contacto1Nombre` varchar(75) COLLATE utf8_unicode_ci DEFAULT '',
  `Contacto1Tel` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `Contacto1Cel` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `Contacto1EMail` varchar(75) COLLATE utf8_unicode_ci DEFAULT '',
  `Contacto2Nombre` varchar(75) COLLATE utf8_unicode_ci DEFAULT '',
  `Contacto2Tel` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `Contacto2Cel` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `Contacto2EMail` varchar(75) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`ProveedorID`),
  UNIQUE KEY `Nombre` (`Nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Proveedores`
--

LOCK TABLES `Proveedores` WRITE;
/*!40000 ALTER TABLE `Proveedores` DISABLE KEYS */;
INSERT INTO `Proveedores` VALUES (1,'DO NOT DELETE this record','','','','','','','','','','','','','','','','','','','','');
/*!40000 ALTER TABLE `Proveedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Ubicaciones`
--

DROP TABLE IF EXISTS `Ubicaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Ubicaciones` (
  `UbiID` int(11) NOT NULL AUTO_INCREMENT,
  `NombreID` int(11) NOT NULL,
  `Ubicacion` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`UbiID`),
  KEY `NombreID` (`NombreID`),
  CONSTRAINT `Ubicaciones_ibfk_1` FOREIGN KEY (`NombreID`) REFERENCES `Nombres` (`NombreID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Ubicaciones`
--

LOCK TABLES `Ubicaciones` WRITE;
/*!40000 ALTER TABLE `Ubicaciones` DISABLE KEYS */;
INSERT INTO `Ubicaciones` VALUES (1,1,'NO DELETE');
/*!40000 ALTER TABLE `Ubicaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Usuarios`
--

DROP TABLE IF EXISTS `Usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Usuarios` (
  `UID` int(5) NOT NULL AUTO_INCREMENT,
  `ApellidoPaterno` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ApellidoMaterno` varchar(25) COLLATE utf8_unicode_ci DEFAULT '',
  `Nombres` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Login` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `PWD` varchar(70) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Fecha` date NOT NULL DEFAULT '0000-00-00',
  `Nivel` enum('Admin','Capturista','Almacenista','Consulta','Instalador','Vendedor','PedOrdInv') COLLATE utf8_unicode_ci DEFAULT NULL,
  `Deshabilitado` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  PRIMARY KEY (`UID`),
  UNIQUE KEY `Login` (`Login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Usuarios`
--

LOCK TABLES `Usuarios` WRITE;
/*!40000 ALTER TABLE `Usuarios` DISABLE KEYS */;
INSERT INTO `Usuarios` VALUES (1,'Admin','','System','admin','$2y$10$0d9zUrayaHj5ch2pJ5wn7uvGaEHefyGTUFiw6xs42h6qckAFM5Oq2',CURDATE(),'Admin','N');
/*!40000 ALTER TABLE `Usuarios` ENABLE KEYS */;
UNLOCK TABLES;

SET FOREIGN_KEY_CHECKS=1;

