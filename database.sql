-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS sushi_delight;
USE sushi_delight;

-- Tabla de reservas
CREATE TABLE IF NOT EXISTS reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    personas INT NOT NULL,
    notas TEXT,
    opentable_id VARCHAR(100),
    estado ENUM('pendiente', 'confirmada', 'cancelada') DEFAULT 'pendiente',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de usuarios administradores
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'manager') DEFAULT 'manager',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Índices para optimizar búsquedas
CREATE INDEX idx_fecha_hora ON reservas(fecha, hora);
CREATE INDEX idx_tipo_servicio ON reservas(tipo_servicio);
CREATE INDEX idx_estado ON reservas(estado);

-- Insertar usuario administrador por defecto
INSERT INTO usuarios (nombre, email, password, rol) VALUES 
('Administrador', 'admin@sushidelight.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- La contraseña es 'password'

-- Crear trigger para verificar disponibilidad antes de insertar
DELIMITER //
CREATE TRIGGER verificar_disponibilidad BEFORE INSERT ON reservas
FOR EACH ROW
BEGIN
    DECLARE reservas_existentes INT;
    
    SELECT COUNT(*) INTO reservas_existentes
    FROM reservas
    WHERE fecha = NEW.fecha 
    AND hora = NEW.hora 
    AND tipo_servicio = NEW.tipo_servicio
    AND estado != 'cancelada';
    
    IF reservas_existentes > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Ya existe una reserva para este horario';
    END IF;
END//
DELIMITER ; 