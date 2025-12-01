CREATE TABLE setor(
  cd_setor SERIAL PRIMARY KEY,
  nm_setor VARCHAR(50) NOT NULL
);

CREATE TABLE cargo(
  cd_cargo SERIAL PRIMARY KEY,
  nm_cargo VARCHAR(100) NOT NULL
);

-- Categoria de Produto
CREATE TABLE produto_categoria (
  cd_categoria SERIAL,
  nm_categoria VARCHAR(50) NOT NULL,
  CONSTRAINT pk_cd_categoria PRIMARY KEY (cd_categoria)
);

CREATE TABLE endereco(
  cd_endereco    SERIAL,
  nm_cidade      TEXT,
  nm_uf          VARCHAR(2),
  nm_bairro      TEXT,
  nm_logradouro  VARCHAR(100) NOT NULL,
  nr_endereco    INTEGER,
  nr_cep         TEXT,
  ds_complemento VARCHAR(100),
  CONSTRAINT pk_cd_endereco PRIMARY KEY (cd_endereco)
);

--USUARIOS SERAO OS OPERADORES DO SISTEMA (CLIENTE NAO PRECISA LOGAR)
CREATE TABLE usuario(
  cd_pessoa  SERIAL,
  nm_pessoa  VARCHAR(100) NOT NULL,
  ds_apelido VARCHAR(30) NOT NULL,
  nr_cpf_cnpj VARCHAR(14) NOT NULL,
  ds_email   VARCHAR(30) NOT NULL,
  ds_senha   VARCHAR(20) NOT NULL,
  dt_cadastro DATE DEFAULT CURRENT_DATE,
  ds_cargo TEXT,
  -- E MAIS ALGUM CAMPO CARACTERISTICO DE UM CADASTRO DE USUARIO (dt_validade_senha e etc)
  CONSTRAINT pk_cd_pessoa PRIMARY KEY (cd_pessoa)
);

CREATE TABLE mesa(
  cd_mesa SERIAL,
  nm_mesa VARCHAR(25),
  CONSTRAINT pk_cd_mesa PRIMARY KEY (cd_mesa)
);

--PRODUTO É O PEDIDO EM SI (Ex.: Frango a Parmeggiana)
CREATE TABLE produto(
  cd_produto          SERIAL,
  cd_categoria        INT,
  nm_produto          VARCHAR(50)   NOT NULL,
  vl_valor            NUMERIC(15,2) NOT NULL,
  ds_produto          VARCHAR(100),
  dt_cadastro         DATE DEFAULT CURRENT_DATE,
  cd_usuario_cadastro INT,
  img_b64             TEXT,
  img_mime            VARCHAR(80),
  CONSTRAINT pk_cd_produto          PRIMARY KEY (cd_produto),
  CONSTRAINT fk_cd_categoria        FOREIGN KEY (cd_categoria)        REFERENCES produto_categoria (cd_categoria) ON DELETE RESTRICT,
  CONSTRAINT fk_cd_usuario_cadastro FOREIGN KEY (cd_usuario_cadastro) REFERENCES usuario           (cd_pessoa)    ON DELETE RESTRICT
);

--ADICIONAL É ALGUM EXTRA (Ex.: Porção de Fritas, Bife, Arroz)
CREATE TABLE adicional(
  cd_adicional SERIAL,
  cd_categoria INT,
  nm_adicional VARCHAR(50)   NOT NULL,
  vl_adicional NUMERIC(15,2) NOT NULL,
  ds_adicional VARCHAR(255),
  img_b64      TEXT,
  img_mime     VARCHAR(80),
  CONSTRAINT pk_cd_adicional PRIMARY KEY (cd_adicional),
  CONSTRAINT fk_cd_categoria FOREIGN KEY (cd_categoria) REFERENCES produto_categoria (cd_categoria) ON DELETE RESTRICT
);

CREATE TABLE pedido(
  cd_pedido SERIAL,
  cd_mesa   INT,
  vl_pedido NUMERIC(15,2) NOT NULL,
  dt_pedido TIMESTAMP DEFAULT NOW(),
  id_status INT CHECK (id_status IN(0,1,2,3)) NOT NULL, -- 'Em Aberto', 'Preparando', 'Servido', 'Concluido'
  ds_observacao TEXT,
  ds_asaas_customer_id TEXT,
  ds_asaas_payment_id TEXT,
  ds_asaas_return TEXT,
  CONSTRAINT pk_cd_pedido PRIMARY KEY (cd_pedido),
  CONSTRAINT fk_cd_mesa_pedido FOREIGN KEY (cd_mesa) REFERENCES mesa (cd_mesa) ON DELETE RESTRICT
);

CREATE TABLE itens_pedido(
  cd_item_pedido SERIAL,
  cd_pedido  INT,
  cd_produto INT,
  qt_produto INT,
  CONSTRAINT pk_cd_item_pedido PRIMARY KEY (cd_item_pedido),
  CONSTRAINT fk_cd_pedido  FOREIGN KEY (cd_pedido)  REFERENCES pedido  (cd_pedido)  ON DELETE CASCADE,
  CONSTRAINT fk_cd_produto FOREIGN KEY (cd_produto) REFERENCES produto (cd_produto) ON DELETE CASCADE
);

CREATE TABLE adicionais_pedido(
  cd_item_pedido INT,
  cd_adicional   INT,
  CONSTRAINT pk_adicionais_pedido PRIMARY KEY (cd_item_pedido, cd_adicional),
  CONSTRAINT fk_item_pedido       FOREIGN KEY (cd_item_pedido) REFERENCES itens_pedido (cd_item_pedido) ON DELETE CASCADE,
  CONSTRAINT fk_cd_adicional      FOREIGN KEY (cd_adicional)  REFERENCES adicional (cd_adicional) ON DELETE CASCADE
);

-- INSERT INTO pedido (vl_pedido, id_status) VALUES (5, 0);