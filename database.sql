DROP DATABASE IF EXISTS store_computem;
CREATE DATABASE IF NOT EXISTS store_computem DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish2_ci;

CREATE TABLE permiso_tmp (
  id SMALLINT UNSIGNED AUTO_INCREMENT,
  nombre VARCHAR(150),
  PRIMARY KEY(id)
);

CREATE TABLE permiso (
  id INT UNSIGNED AUTO_INCREMENT,
  permiso SMALLINT UNSIGNED,
  user VARCHAR(30),
  estado ENUM('s','n'),
  PRIMARY KEY(id),
  FOREIGN KEY(permiso) REFERENCES permiso_tmp(id)
);

CREATE TABLE usuario (
	user VARCHAR(30),
	password VARCHAR(50),
	estado ENUM('s','n'),
	nombre VARCHAR(70),
    correo VARCHAR(60),
	tipo ENUM('admin','vendedor'),
    PRIMARY KEY(user)
);

CREATE TABLE cliente (
    documento CHAR(11),
    nombre VARCHAR(50),
    apellido VARCHAR(50),
    direccion VARCHAR(50),
    telefono CHAR(12),
    estado ENUM('s','n'),
    PRIMARY KEY(documento)
);

CREATE TABLE inventario (
    id INT UNSIGNED AUTO_INCREMENT,
    nombre_producto VARCHAR(50),
    descripcion VARCHAR(150),
    valor_unitario FLOAT(10,2),
    cantidad MEDIUMINT UNSIGNED,
    estado ENUM('s','n'),
    PRIMARY KEY(id)
);

CREATE TABLE item_venta (
    id INT UNSIGNED AUTO_INCREMENT,
    fecha DATE,
    user_vendedor VARCHAR(30),
    doc_cliente CHAR(11),
    total FLOAT(10,2),
    estado ENUM('s','n'),
    PRIMARY KEY(id),
    FOREIGN KEY(user_vendedor) REFERENCES usuario(user),
    FOREIGN KEY(doc_cliente) REFERENCES cliente(documento)
);

CREATE TABLE item_detalle_venta (
    id INT UNSIGNED,
    producto INT UNSIGNED,
    cantidad SMALLINT UNSIGNED,
    precio FLOAT(10,2),
    subtotal FLOAT(10,2),
    PRIMARY KEY(id),
    FOREIGN KEY(producto) REFERENCES inventario(id)
);

INSERT INTO `permiso_tmp` (`nombre`) 
VALUES ('Modulo facturacion'), ('Modulo inventario'), ('Modulo clientes'), ('Modulo usuarios'), ('Modulo reportes'), 
('Modulo permisos'), ('Modulo vendedor'), ('Modulo administrador');