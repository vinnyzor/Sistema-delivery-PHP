-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30-Jun-2023 às 23:35
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `delivery`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `entregadores`
--

CREATE TABLE `entregadores` (
  `id` int(11) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `nivel_rank` int(11) DEFAULT NULL,
  `cpf` varchar(14) NOT NULL,
  `num_carteira_motorista` varchar(20) NOT NULL,
  `endereco` varchar(200) DEFAULT NULL,
  `imagem_perfil` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `entregas`
--

CREATE TABLE `entregas` (
  `id` int(11) NOT NULL,
  `restaurante_id` int(11) DEFAULT NULL,
  `endereco_cliente` varchar(255) DEFAULT NULL,
  `distancia` decimal(10,2) DEFAULT NULL,
  `taxa_entrega` decimal(10,2) DEFAULT NULL,
  `data_hora` datetime DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'aguardando_entregador'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `entregas`
--

INSERT INTO `entregas` (`id`, `restaurante_id`, `endereco_cliente`, `distancia`, `taxa_entrega`, `data_hora`, `status`) VALUES
(1, 1, 'rtertert', '2.30', '9.53', '2023-06-29 13:37:46', 'aguardando_entregador'),
(2, 1, 'fdsfdsf', '1.90', '9.20', '2023-06-30 14:02:41', 'em_rota'),
(3, 1, 'fdsfdsffdsf', '1.90', '9.20', '2023-06-30 14:02:42', 'aguardando_entregador'),
(4, 1, 'fdsfdsffdsffsdfds', '1.90', '9.20', '2023-06-29 14:02:43', 'aguardando_entregador'),
(5, 1, 'fdsfdsffdsffsdfdsfsdfds', '1.90', '9.20', '2023-06-30 14:02:44', 'concluida'),
(6, 1, 'quadra 7 conjunto 7b casa 15', '2.30', '9.53', '2023-06-30 14:53:06', 'aguardando_entregador'),
(7, 1, 'fsdfsd', '0.90', '8.00', '2023-06-22 15:08:33', 'aguardando_entregador'),
(8, 1, 'fsdfsd', '0.90', '8.00', '2023-06-30 15:08:57', 'aguardando_entregador'),
(9, 1, 'jgj', '1.00', '8.00', '2023-06-30 15:09:06', 'aguardando_entregador'),
(10, 1, 'hfghfg', '1.20', '8.00', '2023-04-30 15:09:06', 'aguardando_entregador'),
(11, 1, 'ssssss', '1.80', '9.09', '2023-06-30 12:13:27', 'aguardando_entregador');

-- --------------------------------------------------------

--
-- Estrutura da tabela `restaurantes`
--

CREATE TABLE `restaurantes` (
  `id` int(11) NOT NULL,
  `nome_estabelecimento` varchar(255) NOT NULL,
  `cnpj` varchar(14) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `status` enum('pendente','aprovado') NOT NULL DEFAULT 'pendente',
  `color_perfil` varchar(7) DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `latitude` varchar(100) DEFAULT NULL,
  `longitude` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Extraindo dados da tabela `restaurantes`
--

INSERT INTO `restaurantes` (`id`, `nome_estabelecimento`, `cnpj`, `endereco`, `email`, `senha`, `telefone`, `status`, `color_perfil`, `foto_perfil`, `latitude`, `longitude`) VALUES
(1, 'Eba+ Açaí1', '9437437', 'Quadra 4 Conjunto B Casa 16 - Vila Buritis', 'viniciusmendesdesousa@gmail.com', 'vinicius', '619965117581', 'aprovado', NULL, 'images_perfil/649eed31afe1f.jpg', '-15.624018', '-47.645631'),
(2, 'delivery nome', '123', 'quadra 3', 'vinny@gmail.com', 'vini', '3455233', 'aprovado', NULL, 'images_perfil/dog-puppy-on-garden-royalty-free-image-1586966191.jpg', NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `entregadores`
--
ALTER TABLE `entregadores`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `entregas`
--
ALTER TABLE `entregas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurante_id` (`restaurante_id`);

--
-- Índices para tabela `restaurantes`
--
ALTER TABLE `restaurantes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `entregadores`
--
ALTER TABLE `entregadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `entregas`
--
ALTER TABLE `entregas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `restaurantes`
--
ALTER TABLE `restaurantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `entregas`
--
ALTER TABLE `entregas`
  ADD CONSTRAINT `entregas_ibfk_1` FOREIGN KEY (`restaurante_id`) REFERENCES `restaurantes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
