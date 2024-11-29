-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: blablabairro
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `administracao`
--

DROP TABLE IF EXISTS `administracao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `administracao` (
  `id_operacao` int(11) NOT NULL AUTO_INCREMENT,
  `rejeitar_cad` tinyint(1) DEFAULT NULL,
  `motivo` varchar(300) DEFAULT NULL,
  `bloquear` tinyint(1) DEFAULT NULL,
  `advertencia` tinyint(1) DEFAULT NULL,
  `data_operacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_operacao`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `administracao_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administracao`
--

LOCK TABLES `administracao` WRITE;
/*!40000 ALTER TABLE `administracao` DISABLE KEYS */;
INSERT INTO `administracao` VALUES (1,1,'Documentação incompleta',0,0,'2024-11-01 17:00:00',1),(2,0,NULL,1,1,'2024-11-02 18:30:00',2);
/*!40000 ALTER TABLE `administracao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `denuncias`
--

DROP TABLE IF EXISTS `denuncias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `denuncias` (
  `id_denuncia` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `tipo_denuncia` enum('Lixo','Barulho','Estrutural','Invasão ou Abandono','Fiscalização','Informações') NOT NULL,
  `descricao` text NOT NULL,
  `foto` blob DEFAULT NULL,
  `video` blob DEFAULT NULL,
  `endereco_denuncia` varchar(300) DEFAULT NULL,
  `dia_hora` datetime DEFAULT NULL,
  `declaracao` tinyint(1) DEFAULT 1,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Aberta','Em Andamento','Concluída') DEFAULT 'Aberta',
  PRIMARY KEY (`id_denuncia`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `denuncias_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `denuncias`
--

LOCK TABLES `denuncias` WRITE;
/*!40000 ALTER TABLE `denuncias` DISABLE KEYS */;
INSERT INTO `denuncias` VALUES (1,1,'Lixo','Grande acúmulo de lixo na praça central.',NULL,NULL,'Praça Central, Bairro A','2024-11-01 10:30:00',1,'2024-11-27 17:55:37','Aberta'),(2,2,'Barulho','Som alto vindo de festas nas noites de sexta-feira.',NULL,NULL,'Rua C, Bairro B','2024-11-02 22:00:00',1,'2024-11-27 17:55:37','Em Andamento');
/*!40000 ALTER TABLE `denuncias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `informacoes`
--

DROP TABLE IF EXISTS `informacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `informacoes` (
  `id_info` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` varchar(50) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`id_info`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `informacoes`
--

LOCK TABLES `informacoes` WRITE;
/*!40000 ALTER TABLE `informacoes` DISABLE KEYS */;
INSERT INTO `informacoes` VALUES (1,'Dicas de Segurança','Como proteger sua casa','Mantenha sempre a porta trancada e não compartilhe chaves.'),(2,'Eventos Locais','Feira de Artesanato','Acontece todos os sábados na praça central.'),(3,'Notícias','Aumento na coleta de recicláveis','Prefeitura anuncia novos pontos de coleta');
/*!40000 ALTER TABLE `informacoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `observacoes`
--

DROP TABLE IF EXISTS `observacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `observacoes` (
  `id_observacao` int(11) NOT NULL AUTO_INCREMENT,
  `observacao` text DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_observacao`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `observacoes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `observacoes`
--

LOCK TABLES `observacoes` WRITE;
/*!40000 ALTER TABLE `observacoes` DISABLE KEYS */;
INSERT INTO `observacoes` VALUES (1,'Usuário necessita atualização de documentação.',1),(2,'Reportado por comportamento inadequado em serviços.',2);
/*!40000 ALTER TABLE `observacoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicos`
--

DROP TABLE IF EXISTS `servicos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `servicos` (
  `id_servicos` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `tipo_servico` enum('Coleta de Óleo','Coleta de Reciclados','Entulho','Poda/Retirada de Resíduos','Sugestões','Informações') NOT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `endereco_servico` varchar(300) DEFAULT NULL,
  `data_servico` datetime DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Aberta','Em Andamento','Concluída') DEFAULT 'Aberta',
  PRIMARY KEY (`id_servicos`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `servicos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicos`
--

LOCK TABLES `servicos` WRITE;
/*!40000 ALTER TABLE `servicos` DISABLE KEYS */;
INSERT INTO `servicos` VALUES (1,1,'Coleta de Óleo',5,'Rua A, 100, Centro, São Paulo','2024-11-05 09:00:00','2024-11-27 17:55:41','Aberta'),(2,2,'Poda/Retirada de Resíduos',1,'Rua B, 200, Bairro B, Rio de Janeiro','2024-11-06 10:00:00','2024-11-27 17:55:41','Aberta'),(3,4,'Coleta de Óleo',2,'São Paulo','2024-11-27 16:23:00','2024-11-27 19:23:14','Em Andamento'),(4,4,'Entulho',4,'TESTE','2024-11-30 17:48:00','2024-11-27 20:48:50','Aberta'),(5,4,'Poda/Retirada de Resíduos',5,'TeStE','2024-12-23 18:53:00','2024-11-27 21:53:28','Aberta');
/*!40000 ALTER TABLE `servicos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `cpf` char(11) DEFAULT NULL,
  `nascimento` date DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('Administrador','Usuário Comum','Prestador de Serviços') DEFAULT 'Usuário Comum',
  `imagemDocumento` blob NOT NULL,
  `imagemEndereco` blob NOT NULL,
  `cep` char(9) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `numero` char(6) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `bairro` varchar(255) NOT NULL,
  `cidade` varchar(255) NOT NULL,
  `status` enum('Ativo','Inativo') DEFAULT 'Ativo',
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `cpf` (`cpf`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'João Silva','j@j','12345678901','1985-06-15','11999999999','123','Usuário Comum',_binary 'documento1',_binary 'endereco1','12345-678','Rua A','100','Apto 101','Centro','São Paulo','Ativo','2024-11-27 17:55:30'),(2,'Maria Souza','m@m','98765432100','1990-10-25','11988888888','123','Prestador de Serviços',_binary 'documento2',_binary 'endereco2','23456-789','Rua B','200',NULL,'Bairro B','Rio de Janeiro','Inativo','2024-11-27 17:55:30'),(3,'ADM','adm@adm','98765432101','2000-02-02','17222222222','adm','Administrador',_binary '߽\�\�\�',_binary '\�\��ڠ�\�','23412-987','Rua B','300',NULL,'Bairro C','São Paulo','Ativo','2024-11-27 19:01:12'),(4,'Thiago','t@t.com','12345678902','1985-06-15','11999999999','$2y$10$2ej6jrrMzqEM4/EdZ.angu7lmNN7zXn7zufKoqEo9TVLeyjzc2kWe','Prestador de Serviços',_binary 'documento1',_binary 'endereco1','12345-678','Rua A','100','Apto 101','Centro','São Paulo','Ativo','2024-11-27 18:02:31'),(5,'Teste','teste@teste','12345678903','1985-08-08','12000000000','123','Usuário Comum',_binary '\�\�',_binary '\�\�\�','34524-987','Rua D','400',NULL,'Bairro Z','São Paulo','Inativo','2024-11-28 18:26:33');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'blablabairro'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-29 15:59:45
