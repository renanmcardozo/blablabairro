CREATE DATABASE Blablabairro;
USE Blablabairro;

-- Tabela de Usuários
CREATE TABLE usuario (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    cpf CHAR(11) UNIQUE,
    nascimento DATE,
    telefone VARCHAR(15),
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('Administrador', 'Usuário Comum', 'Prestador de Serviços') DEFAULT 'Usuário Comum',
    imagemDocumento BLOB NOT NULL,
    imagemEndereco BLOB NOT NULL,
    cep CHAR(9) NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    numero CHAR(6),
    complemento VARCHAR(100),
    bairro VARCHAR(255) NOT NULL,
    cidade VARCHAR(255) NOT NULL,
    status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Informações Gerais (mais flexível para adicionar informações)
CREATE TABLE informacoes (
    id_info INT PRIMARY KEY AUTO_INCREMENT,
    categoria VARCHAR(50) NOT NULL,
    titulo VARCHAR(100),
    descricao TEXT
);

-- Tabela de Denúncias (para registrar denúncias realizadas pelos usuários)
CREATE TABLE denuncias (
    id_denuncia INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    tipo_denuncia ENUM('Lixo', 'Barulho', 'Estrutural', 'Invasão ou Abandono', 'Fiscalização', 'Informações') NOT NULL,
    descricao TEXT NOT NULL,
    foto BLOB NULL,
    video BLOB NULL,
    endereco_denuncia VARCHAR(300),
    dia_hora DATETIME,
    declaracao BOOLEAN DEFAULT TRUE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Aberta', 'Em Andamento', 'Concluída') DEFAULT 'Aberta',
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

-- Tabela de Serviços (com campos adicionais para detalhes de serviço)
CREATE TABLE servicos (
    id_servicos INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    tipo_servico ENUM('Coleta de Óleo', 'Coleta de Reciclados', 'Entulho', 'Poda/Retirada de Resíduos', 'Sugestões', 'Informações') NOT NULL,
    quantidade INT,
    endereco_servico VARCHAR(300),
    data_servico DATETIME,
    status ENUM('Aberta', 'Em Andamento', 'Concluída') DEFAULT 'Aberta',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);



-- Tabela de Usuários
INSERT INTO usuario (nome, email, cpf, nascimento, telefone, senha, tipo, imagemDocumento, imagemEndereco, cep, endereco, numero, complemento, bairro, cidade, status)
VALUES 
('João Silva', 'joao.silva@email.com', '12345678901', '1985-06-15', '11999999999', 'senha123', 'Usuário Comum', 'documento1', 'endereco1', '12345-678', 'Rua A', '100', 'Apto 101', 'Centro', 'São Paulo', 'Ativo'),
('Maria Souza', 'maria.souza@email.com', '98765432100', '1990-10-25', '11988888888', 'senha456', 'Prestador de Serviços', 'documento2', 'endereco2', '23456-789', 'Rua B', '200', NULL, 'Bairro B', 'Rio de Janeiro', 'Inativo');

INSERT INTO usuario (nome, email, cpf, nascimento, telefone, senha, tipo, imagemDocumento, imagemEndereco, cep, endereco, numero, complemento, bairro, cidade, status)
VALUES 
('Thiago', 't@t', '12345678902', '1985-06-15', '11999999999', '123', 'Usuário Comum', 'documento1', 'endereco1', '12345-678', 'Rua A', '100', 'Apto 101', 'Centro', 'São Paulo', 'Ativo');

-- Tabela de Informações Gerais
INSERT INTO informacoes (categoria, titulo, descricao)
VALUES 
('Dicas de Segurança', 'Como proteger sua casa', 'Mantenha sempre a porta trancada e não compartilhe chaves.'),
('Eventos Locais', 'Feira de Artesanato', 'Acontece todos os sábados na praça central.'),
('Notícias', 'Aumento na coleta de recicláveis', 'Prefeitura anuncia novos pontos de coleta.');

-- Tabela de Denúncias
INSERT INTO denuncias (id_usuario, tipo_denuncia, descricao, foto, video, endereco_denuncia, dia_hora, declaracao, status)
VALUES 
(1, 'Lixo', 'Grande acúmulo de lixo na praça central.', NULL, NULL, 'Praça Central, Bairro A', '2024-11-01 10:30:00', TRUE, 'Aberta'),
(2, 'Barulho', 'Som alto vindo de festas nas noites de sexta-feira.', NULL, NULL, 'Rua C, Bairro B', '2024-11-02 22:00:00', TRUE, 'Em Andamento');

-- Tabela de Serviços
INSERT INTO servicos (id_usuario, tipo_servico, quantidade, endereco_servico, data_servico)
VALUES 
(1, 'Coleta de Óleo', 5, 'Rua A, 100, Centro, São Paulo', '2024-11-05 09:00:00'),
(2, 'Poda/Retirada de Resíduos', 1, 'Rua B, 200, Bairro B, Rio de Janeiro', '2024-11-06 10:00:00');




