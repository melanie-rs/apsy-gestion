-- =====================================================================
-- Cómo usarlo en XAMPP:
--   phpMyAdmin → pestaña "Importar" → elegir este archivo → Continuar.
--   (El script crea la base "apsy" y todas las tablas con datos de prueba.)
--
-- El script se puede volver a ejecutar: borra las tablas y las recrea.
-- ¡Cuidado!: al volver a ejecutarlo se pierden los datos cargados.
-- =====================================================================

-- Indica que este archivo está en UTF-8 (evita que "Lucía" se guarde como "LucÃ­a")
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS apsy
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE apsy;

-- ---------------------------------------------------------------------
-- Borrado previo (primero la tabla "hija", después las "padres")
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS turnos;
DROP TABLE IF EXISTS servicios;
DROP TABLE IF EXISTS profesionales;
DROP TABLE IF EXISTS clientes;
DROP TABLE IF EXISTS usuarios;

-- ---------------------------------------------------------------------
-- 1) USUARIOS: quienes pueden ingresar al sistema (login)
-- ---------------------------------------------------------------------
CREATE TABLE usuarios (
    id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    usuario        VARCHAR(50)  NOT NULL,
    password_hash  VARCHAR(255) NOT NULL,          -- resultado de password_hash()
    nombre         VARCHAR(60)  NOT NULL,
    apellido       VARCHAR(60)  NOT NULL,
    rol            VARCHAR(30)  NOT NULL DEFAULT 'Administradora',
    PRIMARY KEY (id),
    UNIQUE KEY uq_usuarios_usuario (usuario)        -- no puede haber dos iguales
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 2) CLIENTES (baja lógica con "activo")
-- ---------------------------------------------------------------------
CREATE TABLE clientes (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre     VARCHAR(60)  NOT NULL,
    apellido   VARCHAR(60)  NOT NULL,
    telefono   CHAR(10)     NOT NULL,               -- solo 10 dígitos, sin espacios ni guiones
    activo     TINYINT(1)   NOT NULL DEFAULT 1,     -- 1 = visible, 0 = dado de baja
    creado_en  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_clientes_apellido_nombre (apellido, nombre),   -- acelera ordenar y buscar
    KEY idx_clientes_telefono (telefono)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 3) PROFESIONALES (baja lógica con "activo")
-- ---------------------------------------------------------------------
CREATE TABLE profesionales (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre        VARCHAR(60)  NOT NULL,
    apellido      VARCHAR(60)  NOT NULL,
    especialidad  VARCHAR(60)  NOT NULL,
    activo        TINYINT(1)   NOT NULL DEFAULT 1,
    creado_en     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_profesionales_apellido_nombre (apellido, nombre)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 4) SERVICIOS: catálogo fijo cargado por script (sin pantalla de gestión)
-- ---------------------------------------------------------------------
CREATE TABLE servicios (
    id            INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    nombre        VARCHAR(80)       NOT NULL,
    duracion_min  SMALLINT UNSIGNED NOT NULL,       -- duración en minutos
    PRIMARY KEY (id),
    UNIQUE KEY uq_servicios_nombre (nombre),
    CONSTRAINT chk_servicios_duracion CHECK (duracion_min > 0)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 5) TURNOS: une un cliente + un profesional + un servicio en un día y hora
--    Es la tabla central: tiene 3 claves foráneas.
-- ---------------------------------------------------------------------
CREATE TABLE turnos (
    id              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    cliente_id      INT UNSIGNED NOT NULL,
    profesional_id  INT UNSIGNED NOT NULL,
    servicio_id     INT UNSIGNED NOT NULL,
    fecha           DATE         NOT NULL,
    hora_inicio     TIME         NOT NULL,
    hora_fin        TIME         NOT NULL,          -- la calcula PHP: inicio + duración del servicio
    estado          ENUM('pendiente','confirmado','cancelado','completado')
                    NOT NULL DEFAULT 'pendiente',
    creado_en       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),

    -- Índices para las consultas más frecuentes
    KEY idx_turnos_fecha_hora (fecha, hora_inicio),                    -- agenda del día
    KEY idx_turnos_prof_fecha (profesional_id, fecha, hora_inicio),    -- chequeo de superposición
    KEY idx_turnos_cliente (cliente_id),
    KEY idx_turnos_servicio (servicio_id),

    -- Claves foráneas: RESTRICT impide borrar un cliente/profesional/servicio
    -- que tenga turnos (por eso usamos baja lógica y no DELETE).
    CONSTRAINT fk_turnos_cliente
        FOREIGN KEY (cliente_id)     REFERENCES clientes(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_turnos_profesional
        FOREIGN KEY (profesional_id) REFERENCES profesionales(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_turnos_servicio
        FOREIGN KEY (servicio_id)    REFERENCES servicios(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    CONSTRAINT chk_turnos_horas CHECK (hora_fin > hora_inicio)
) ENGINE=InnoDB;

-- =====================================================================
-- DATOS DE PRUEBA
-- =====================================================================

-- Usuario de prueba · usuario: mariana.lopez · contraseña: apsy2026
INSERT INTO usuarios (usuario, password_hash, nombre, apellido, rol) VALUES
('mariana.lopez',
 '$2y$10$4fz5zD3PcErv3EzoPnOygODyGrgu3KKQrFMiH4E0cmo1tHWk7zic.',
 'Mariana', 'López', 'Administradora');

-- Servicios (datos iniciales fijos)
INSERT INTO servicios (id, nombre, duracion_min) VALUES
(1, 'Corte y brushing', 60),
(2, 'Coloración',       120),
(3, 'Nutrición capilar', 45),
(4, 'Balayage',         180);

-- Profesionales
INSERT INTO profesionales (id, nombre, apellido, especialidad, activo) VALUES
(1, 'Camila',  'Torres', 'Corte y peinado', 1),
(2, 'Nicolás', 'Ruiz',   'Coloración',      1),
(3, 'Julieta', 'Acosta', 'Tratamientos',    1);

-- Clientes (10 activos para probar la paginación + 1 dada de baja)
INSERT INTO clientes (id, nombre, apellido, telefono, activo) VALUES
( 1, 'Lucía',      'Fernández', '1134567890', 1),
( 2, 'Martina',    'Gómez',     '1156781234', 1),
( 3, 'Sofía',      'Pérez',     '1123456789', 1),
( 4, 'Valentina',  'Díaz',      '1145678901', 1),
( 5, 'Carolina',   'Silva',     '1167890123', 1),
( 6, 'Florencia',  'Romero',    '1178901234', 1),
( 7, 'Agustina',   'Benítez',   '1189012345', 1),
( 8, 'Paula',      'Medina',    '1190123456', 1),
( 9, 'Julia',      'Herrera',   '1101234567', 1),
(10, 'Micaela',    'Sosa',      '1112345678', 1),
(11, 'Romina',     'Castro',    '1123450987', 0);   -- baja lógica: no aparece en listados

-- Turnos: usan CURDATE() para que siempre haya datos "de hoy" en el dashboard.
-- Hoy: 6 confirmados, 4 pendientes y 1 cancelado.
INSERT INTO turnos (cliente_id, profesional_id, servicio_id, fecha, hora_inicio, hora_fin, estado) VALUES
-- Camila Torres (id 1)
(1, 1, 1, CURDATE(), '09:00:00', '10:00:00', 'confirmado'),   -- Lucía · Corte y brushing
(3, 1, 1, CURDATE(), '10:30:00', '11:30:00', 'pendiente'),    -- Sofía
(8, 1, 1, CURDATE(), '12:00:00', '13:00:00', 'confirmado'),   -- Paula
(7, 1, 1, CURDATE(), '15:00:00', '16:00:00', 'pendiente'),    -- Agustina
(4, 1, 1, CURDATE(), '17:00:00', '18:00:00', 'cancelado'),    -- Valentina
-- Nicolás Ruiz (id 2)
(2, 2, 2, CURDATE(), '09:30:00', '11:30:00', 'confirmado'),   -- Martina · Coloración
(5, 2, 4, CURDATE(), '11:30:00', '14:30:00', 'pendiente'),    -- Carolina · Balayage
(6, 2, 2, CURDATE(), '15:00:00', '17:00:00', 'confirmado'),   -- Florencia · Coloración
-- Julieta Acosta (id 3)
(4, 3, 3, CURDATE(), '11:00:00', '11:45:00', 'confirmado'),   -- Valentina · Nutrición capilar
(9, 3, 3, CURDATE(), '12:00:00', '12:45:00', 'pendiente'),    -- Julia
(10,3, 3, CURDATE(), '16:00:00', '16:45:00', 'confirmado'),   -- Micaela
-- Otros días (para probar el filtro de fecha)
(1, 1, 1, CURDATE() - INTERVAL 1 DAY, '10:00:00', '11:00:00', 'completado'),
(3, 2, 2, CURDATE() + INTERVAL 1 DAY, '10:00:00', '12:00:00', 'pendiente');