-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tempo de Geração: 19/05/2025 às 21h48min
-- Versão do Servidor: 5.5.20
-- Versão do PHP: 5.3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `livraria`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `autor`
--

CREATE TABLE IF NOT EXISTS `autor` (
  `codautor` int(5) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `pais` varchar(50) NOT NULL,
  PRIMARY KEY (`codautor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `autor`
--

INSERT INTO `autor` (`codautor`, `nome`, `pais`) VALUES
(1, 'George Orwell', 'Índia'),
(2, 'Celeste Ng', 'EUA'),
(3, 'J.K. Rowling', 'UK'),
(4, 'J.R.R. Tolkien', 'África do Sul'),
(5, 'James C. Hunter', 'EUA'),
(6, 'William March', 'EUA'),
(7, 'Lewis Carroll', 'UK');

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE IF NOT EXISTS `categoria` (
  `codcategoria` int(5) NOT NULL,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`codcategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`codcategoria`, `nome`) VALUES
(1, 'Ficção Científica'),
(2, 'Fantasia'),
(3, 'Romance'),
(4, 'Suspense / Mistério'),
(5, 'Clássicos da Literatura'),
(6, 'Autoajuda'),
(7, 'História');

-- --------------------------------------------------------

--
-- Estrutura da tabela `editora`
--

CREATE TABLE IF NOT EXISTS `editora` (
  `codeditora` int(5) NOT NULL,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`codeditora`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `editora`
--

INSERT INTO `editora` (`codeditora`, `nome`) VALUES
(1, 'Companhia das Letras'),
(2, 'Intrínseca'),
(3, 'Rocco'),
(4, 'HarperCollins Brasil'),
(5, 'Sextante'),
(6, 'DarkSide Books'),
(7, 'Martins Fontes');

-- --------------------------------------------------------

--
-- Estrutura da tabela `livro`
--

CREATE TABLE IF NOT EXISTS `livro` (
  `codlivro` int(5) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `nrpaginas` int(4) NOT NULL,
  `ano` int(4) NOT NULL,
  `codautor` int(5) NOT NULL,
  `codcategoria` int(5) NOT NULL,
  `codeditora` int(5) NOT NULL,
  `resenha` text NOT NULL,
  `preco` float(6,2) NOT NULL,
  `foto1` varchar(500) NOT NULL,
  `foto2` varchar(500) NOT NULL,
  PRIMARY KEY (`codlivro`),
  KEY `codautor` (`codautor`),
  KEY `codcategoria` (`codcategoria`),
  KEY `codeditora` (`codeditora`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `livro`
--

INSERT INTO `livro` (`codlivro`, `titulo`, `nrpaginas`, `ano`, `codautor`, `codcategoria`, `codeditora`, `resenha`, `preco`, `foto1`, `foto2`) VALUES
(1, '1984', 416, 1949, 1, 1, 1, 'Um clássico distópico que retrata um governo totalitário e a vigilância constante sobre os cidadãos.', 49.90, 'c3659222a35ca7d17c4aa58bc73587b1.jpg', '85df27997e69d0bcbbe26efe716a39bb.jpg'),
(2, 'Pequenos Incêndios por Toda Parte', 304, 2017, 2, 3, 2, 'Um drama familiar que aborda temas como maternidade, segredos e desigualdade social.', 42.00, '6f9e14e29e0afebef58a3181e87b7b1b.jpg', '4f02f5b099c2a6abf49ba8adadae0ee6.jpg'),
(3, 'Harry Potter e a Pedra Filosofal', 223, 1997, 3, 2, 3, 'O início da saga do jovem bruxo que descobre um mundo mágico e seu destino especial.', 59.90, 'bf3688687c5d070ce2fc316db727a48d.jpg', '2b60f85115f7c06c78605fde8e4250b7.jpg'),
(4, 'O Hobbit', 320, 1937, 4, 2, 4, 'A aventura de Bilbo Bolseiro em uma jornada épica com anões para recuperar um tesouro.', 55.00, '111e9b919079fd57ae996e1e66a1c022.jpg', 'ff8315a532b1b17dd7b00f748c6bc197.jpg'),
(5, 'O Monge e o Executivo', 144, 1998, 5, 6, 5, 'Uma parábola sobre liderança servidora e valores humanos aplicados à vida e ao trabalho.', 34.90, '658a9d5bb3a6b08f07881bf6d7aa1cae.jpg', '1e7aa39b561dc103ae6f43b9da89da46.jpg'),
(6, 'Menina Má', 336, 1954, 6, 4, 6, 'Uma menina aparentemente perfeita esconde uma personalidade perturbadora e sombria.', 62.90, '6365ab48f9aa9e27f33f763d9c6da8f4.jpg', '9e25772f46949d88b2a0d1a61da4d2cd.jpg'),
(7, 'Alice no País das Maravilhas', 192, 1865, 7, 5, 7, 'A clássica jornada de Alice em um mundo fantástico e surreal repleto de personagens inesquecíveis.', 39.90, 'c10cd6394a8f5424ac526efef2d315c5.jpg', '9a82cb7e55334c1b5c6a8bd67188917c.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `codusuario` int(5) NOT NULL,
  `email` varchar(50) NOT NULL,
  `senha` varchar(20) NOT NULL,
  PRIMARY KEY (`codusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`codusuario`, `email`, `senha`) VALUES
(1, 'marco@gmail.com', '23042008');

--
-- Restrições para as tabelas dumpadas
--

--
-- Restrições para a tabela `livro`
--
ALTER TABLE `livro`
  ADD CONSTRAINT `livro_ibfk_1` FOREIGN KEY (`codautor`) REFERENCES `autor` (`codautor`),
  ADD CONSTRAINT `livro_ibfk_2` FOREIGN KEY (`codcategoria`) REFERENCES `categoria` (`codcategoria`),
  ADD CONSTRAINT `livro_ibfk_3` FOREIGN KEY (`codeditora`) REFERENCES `editora` (`codeditora`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
