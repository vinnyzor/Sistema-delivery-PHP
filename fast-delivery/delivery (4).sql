-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11-Jul-2023 às 22:44
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
-- Estrutura da tabela `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `nome_admin` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `color_perfil` varchar(7) DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Extraindo dados da tabela `admins`
--

INSERT INTO `admins` (`id`, `nome_admin`, `email`, `senha`, `telefone`, `color_perfil`, `foto_perfil`, `last_login`) VALUES
(1, 'vinicius', 'viniciusmendes@gmail.com', 'vinicius', '6199885934', NULL, NULL, '2023-07-11 08:53:22');

-- --------------------------------------------------------

--
-- Estrutura da tabela `chamados`
--

CREATE TABLE `chamados` (
  `id` int(11) NOT NULL,
  `assunto` varchar(255) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `status` enum('Aberto','Encerrado') DEFAULT NULL,
  `data_encerramento` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `chamados`
--

INSERT INTO `chamados` (`id`, `assunto`, `descricao`, `status`, `data_encerramento`) VALUES
(1, 'gfdg', 'fdgfdg', 'Aberto', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `chats`
--

CREATE TABLE `chats` (
  `id` int(11) NOT NULL,
  `id_de` int(11) DEFAULT NULL,
  `id_para` int(11) DEFAULT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `chats`
--

INSERT INTO `chats` (`id`, `id_de`, `id_para`, `lastupdate`) VALUES
(72, 3, 1, '2023-07-11 13:03:09'),
(73, 2, 1, '2023-07-07 03:14:48');

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
  `data_hora` datetime DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'aguardando_entregador',
  `taxa_entrega_entregador` decimal(10,2) DEFAULT NULL,
  `taxa_entrega` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `entregas`
--

INSERT INTO `entregas` (`id`, `restaurante_id`, `endereco_cliente`, `distancia`, `data_hora`, `status`, `taxa_entrega_entregador`, `taxa_entrega`) VALUES
(104, 2, '', '5.30', '2023-07-11 08:33:30', 'aguardando_entregador', '12.69', '12.94'),
(105, 2, '', '5.20', '2023-07-11 08:33:43', 'aguardando_entregador', '12.58', '12.83'),
(106, 2, '', '5.20', '2023-07-10 08:33:44', 'aguardando_entregador', '12.58', '12.83'),
(107, 3, '', '0.70', '2023-07-11 08:35:31', 'aguardando_entregador', '7.75', '8.00'),
(108, 3, '', '1.80', '2023-08-11 08:35:37', 'aguardando_entregador', '8.73', '8.98'),
(109, 3, '', '1.00', '2023-07-07 09:07:31', 'aguardando_entregador', '7.75', '8.00'),
(110, 3, '', '1.00', '2023-07-11 09:07:31', 'aguardando_entregador', '7.75', '8.00'),
(111, 3, '', '1.00', '2023-05-11 09:07:31', 'aguardando_entregador', '7.75', '8.00'),
(112, 3, '', '1.00', '2023-05-11 09:07:32', 'aguardando_entregador', '7.75', '8.00'),
(113, 3, '', '2.30', '2023-07-11 10:37:03', 'aguardando_entregador', '9.28', '9.53'),
(114, 3, '', '2.40', '2023-07-11 11:10:33', 'aguardando_entregador', '9.50', '9.75');

-- --------------------------------------------------------

--
-- Estrutura da tabela `financas`
--

CREATE TABLE `financas` (
  `id` int(11) NOT NULL,
  `restaurante_id` int(11) NOT NULL,
  `divida` decimal(10,2) NOT NULL,
  `valor_devido` decimal(10,2) NOT NULL,
  `dia_divida` date NOT NULL,
  `status_divida` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `financas`
--

INSERT INTO `financas` (`id`, `restaurante_id`, `divida`, `valor_devido`, `dia_divida`, `status_divida`) VALUES
(5, 2, '25.77', '25.77', '2023-07-11', 'em_aberto'),
(6, 3, '43.28', '43.28', '2023-07-11', 'em_aberto'),
(7, 2, '12.83', '12.83', '2023-07-10', 'em_aberto'),
(8, 3, '8.00', '8.00', '2023-07-07', 'em_aberto');

-- --------------------------------------------------------

--
-- Estrutura da tabela `horarios`
--

CREATE TABLE `horarios` (
  `id` int(11) NOT NULL,
  `dia_semana` varchar(50) NOT NULL,
  `horario_abertura` time NOT NULL,
  `horario_fechamento` time NOT NULL,
  `id_restaurante` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Extraindo dados da tabela `horarios`
--

INSERT INTO `horarios` (`id`, `dia_semana`, `horario_abertura`, `horario_fechamento`, `id_restaurante`) VALUES
(1, 'Segunda-feira', '22:28:00', '00:28:00', 3),
(2, 'Terca-feira', '23:58:00', '01:58:00', 2),
(5, 'Sexta-feira', '11:10:00', '14:10:00', 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `mensagens`
--

CREATE TABLE `mensagens` (
  `id` int(11) NOT NULL,
  `id_de` int(11) DEFAULT NULL,
  `id_chat` int(11) DEFAULT NULL,
  `mensagem` text DEFAULT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp(),
  `lido` tinyint(1) DEFAULT 0,
  `id_restaurante` int(11) DEFAULT NULL,
  `id_de_admin` int(1) NOT NULL DEFAULT 0,
  `data_atualizacao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `mensagens`
--

INSERT INTO `mensagens` (`id`, `id_de`, `id_chat`, `mensagem`, `data`, `lido`, `id_restaurante`, `id_de_admin`, `data_atualizacao`) VALUES
(543, 3, 72, 'ola', '2023-07-07 02:03:08', 0, 3, 0, NULL),
(544, 3, 72, 'ola', '2023-07-07 02:03:14', 1, 3, 1, NULL),
(545, 2, 73, 'quero atencao poxa', '2023-07-07 02:03:36', 0, 2, 0, NULL),
(546, 2, 73, 'fsdfds', '2023-07-07 02:31:36', 1, 2, 1, NULL),
(547, 2, 73, 'testando', '2023-07-07 02:40:52', 1, 2, 1, NULL),
(548, 2, 73, 'fdfdsfs', '2023-07-07 02:40:55', 1, 2, 1, NULL),
(549, 2, 73, 'testando notificaçoes', '2023-07-07 02:43:19', 1, 2, 1, NULL),
(550, 2, 73, 'gdfgfd', '2023-07-07 02:46:13', 1, 2, 1, NULL),
(551, 2, 73, 'fdsfds', '2023-07-07 02:46:30', 1, 2, 1, NULL),
(552, 2, 73, 'fdsfds', '2023-07-07 02:46:46', 1, 2, 1, NULL),
(553, 2, 73, 'fdsfsd', '2023-07-07 02:47:49', 1, 2, 1, NULL),
(554, 2, 73, 'FDSFDS', '2023-07-07 02:48:16', 1, 2, 1, NULL),
(555, 2, 73, 'fsdfds', '2023-07-07 02:52:16', 1, 2, 1, NULL),
(556, 2, 73, 'fdsfds', '2023-07-07 02:52:24', 1, 2, 1, NULL),
(557, 2, 73, 'dasdas', '2023-07-07 02:57:24', 1, 2, 1, NULL),
(558, 2, 73, 'fdsf', '2023-07-07 02:59:51', 1, 2, 1, NULL),
(559, 2, 73, 'gsdfgfdsgdf', '2023-07-07 03:09:58', 1, 2, 1, NULL),
(560, 2, 73, 'fdsfds', '2023-07-07 03:10:13', 1, 2, 1, NULL),
(561, 2, 73, 'fsdfsdfsd', '2023-07-07 03:14:31', 1, 2, 1, NULL),
(562, 2, 73, 'fdsfdsf', '2023-07-07 03:14:48', 0, 2, 0, NULL),
(563, 2, 73, 'gfdgfd', '2023-07-07 03:15:14', 1, 2, 1, NULL),
(564, 2, 73, 'hgfhgfhgfhfghfg', '2023-07-07 03:15:41', 1, 2, 1, NULL),
(565, 2, 73, 'uuuu', '2023-07-07 03:15:44', 1, 2, 1, NULL),
(566, 2, 73, 'uuuuuu', '2023-07-07 03:15:48', 1, 2, 1, NULL),
(567, 2, 73, 'uu', '2023-07-07 03:16:07', 1, 2, 1, NULL),
(568, 2, 73, 'uyu', '2023-07-07 03:16:09', 1, 2, 1, NULL),
(569, 2, 73, 'uyu', '2023-07-07 03:16:17', 1, 2, 1, NULL),
(570, 2, 73, 'uyu', '2023-07-07 03:16:19', 1, 2, 1, NULL),
(571, 2, 73, 'uu', '2023-07-07 03:16:23', 1, 2, 1, NULL),
(572, 2, 73, 'hgfhgfh', '2023-07-07 04:15:04', 1, 2, 1, NULL),
(573, 2, 73, 'fdsfds', '2023-07-11 11:48:25', 1, 2, 1, NULL),
(574, 3, 72, 'çlkçlkç', '2023-07-11 11:49:05', 1, 3, 1, NULL),
(575, 3, 72, 'çlkçlk', '2023-07-11 11:49:06', 1, 3, 1, NULL),
(576, 3, 72, 'çlkçlkç', '2023-07-11 11:49:07', 1, 3, 1, NULL),
(577, 3, 72, 'fjhgj', '2023-07-11 11:49:08', 1, 3, 1, NULL),
(578, 3, 72, 'olá qual a duvifda que eu tenho entao??', '2023-07-11 11:49:22', 0, 3, 0, NULL),
(579, 3, 72, 'ENTAO', '2023-07-11 13:03:06', 0, 3, 0, NULL),
(580, 3, 72, 'GOSTARIA DE SABER', '2023-07-11 13:03:09', 0, 3, 0, NULL);

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
  `color_perfil` varchar(7) DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `latitude` varchar(100) DEFAULT NULL,
  `longitude` varchar(100) DEFAULT NULL,
  `status` enum('pendente','aprovado') NOT NULL DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Extraindo dados da tabela `restaurantes`
--

INSERT INTO `restaurantes` (`id`, `nome_estabelecimento`, `cnpj`, `endereco`, `email`, `senha`, `telefone`, `color_perfil`, `foto_perfil`, `latitude`, `longitude`, `status`) VALUES
(2, 'Cachorrinho Dogs', '123', 'quadra 3', 'vinny@gmail.com', 'vini', '3455233', NULL, 'images_perfil/dog-puppy-on-garden-royalty-free-image-1586966191.jpg', '-15.616061', '-47.685356', 'aprovado'),
(3, 'Eba+ Açaí', '9437437', 'Quadra 4 Conjunto B Casa 16 - Vila Buritis', 'viniciusmendesdesousa@gmail.com', 'vinicius', '61996511758', NULL, 'images_perfil/64a0cd3ad4b3e.png', '-15.624018', '-47.645631', 'aprovado');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `restaurante_id` int(11) DEFAULT NULL,
  `assunto` varchar(255) DEFAULT NULL,
  `mensagem` text DEFAULT NULL,
  `resposta` text DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `data_abertura` timestamp NOT NULL DEFAULT current_timestamp(),
  `prioridade` varchar(20) DEFAULT NULL,
  `data_resposta` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tickets`
--

INSERT INTO `tickets` (`id`, `restaurante_id`, `assunto`, `mensagem`, `resposta`, `status`, `data_abertura`, `prioridade`, `data_resposta`) VALUES
(29, 3, 'test', 'test', 'respondendo sua duvida', 'Respondido', '2023-07-05 14:03:45', 'Alta', '2023-07-05 19:03:59'),
(30, 3, 'gdfgfd', 'gfdgfd', NULL, 'Aguardando Resposta', '2023-07-05 14:04:21', 'Baixa', NULL),
(31, 3, '', '', NULL, 'Aguardando Resposta', '2023-07-05 14:04:41', 'Média', NULL),
(32, 3, 'ytrytr', 'ytrytrytr', NULL, 'Aguardando Resposta', '2023-07-05 14:04:47', 'Média', NULL),
(33, 2, 'encontrei um bug', 'djiashdasdhasvfasvfafasfd', 'sdfdsafgdsgfdsgfdghjgfasf', 'Respondido', '2023-07-07 00:23:05', 'Alta', '2023-07-07 05:24:45'),
(34, 2, 'dffsd', 'fdsfdsf', NULL, 'Aguardando Resposta', '2023-07-07 00:23:39', 'Média', NULL),
(35, 2, 'rewrewr', 'fdsgsfgsdfg', 'dasdasdas', 'Respondido', '2023-07-07 00:23:43', 'Baixa', '2023-07-07 02:09:58');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `chamados`
--
ALTER TABLE `chamados`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

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
-- Índices para tabela `financas`
--
ALTER TABLE `financas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `horarios`
--
ALTER TABLE `horarios`
  ADD KEY `fk_restaurantes_horarios` (`id_restaurante`);

--
-- Índices para tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_restaurante` (`id_restaurante`);

--
-- Índices para tabela `restaurantes`
--
ALTER TABLE `restaurantes`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurante_id` (`restaurante_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `chamados`
--
ALTER TABLE `chamados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT de tabela `entregadores`
--
ALTER TABLE `entregadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `entregas`
--
ALTER TABLE `entregas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT de tabela `financas`
--
ALTER TABLE `financas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `mensagens`
--
ALTER TABLE `mensagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=581;

--
-- AUTO_INCREMENT de tabela `restaurantes`
--
ALTER TABLE `restaurantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `entregas`
--
ALTER TABLE `entregas`
  ADD CONSTRAINT `entregas_ibfk_1` FOREIGN KEY (`restaurante_id`) REFERENCES `restaurantes` (`id`);

--
-- Limitadores para a tabela `horarios`
--
ALTER TABLE `horarios`
  ADD CONSTRAINT `fk_restaurantes_horarios` FOREIGN KEY (`id_restaurante`) REFERENCES `restaurantes` (`id`);

--
-- Limitadores para a tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD CONSTRAINT `fk_restaurante` FOREIGN KEY (`id_restaurante`) REFERENCES `restaurantes` (`id`);

--
-- Limitadores para a tabela `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`restaurante_id`) REFERENCES `restaurantes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
