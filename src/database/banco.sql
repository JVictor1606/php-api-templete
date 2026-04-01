CREATE DATABASE IF NOT EXISTS `api_template_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `api_template_db`;

CREATE TABLE IF NOT EXISTS `usuario_tb` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Senha de todos os usuarios: 123456
INSERT INTO `usuario_tb` (`nome`, `email`, `senha`) VALUES
('Joao Silva', 'joao@email.com', '$2y$12$ag2YNmeFb7CZT/Kstc6Wt.2LScIuLJe4xcQrOSQq9gIjDtuBI0pkC'),
('Maria Santos', 'maria@email.com', '$2y$12$ag2YNmeFb7CZT/Kstc6Wt.2LScIuLJe4xcQrOSQq9gIjDtuBI0pkC'),
('Pedro Oliveira', 'pedro@email.com', '$2y$12$ag2YNmeFb7CZT/Kstc6Wt.2LScIuLJe4xcQrOSQq9gIjDtuBI0pkC'),
('Ana Costa', 'ana@email.com', '$2y$12$ag2YNmeFb7CZT/Kstc6Wt.2LScIuLJe4xcQrOSQq9gIjDtuBI0pkC'),
('Carlos Souza', 'carlos@email.com', '$2y$12$ag2YNmeFb7CZT/Kstc6Wt.2LScIuLJe4xcQrOSQq9gIjDtuBI0pkC');
