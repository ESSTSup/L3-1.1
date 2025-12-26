-- Création de la base de données
CREATE DATABASE IF NOT EXISTS medical_dashboard;
USE medical_dashboard;

-- Table des utilisateurs (assistantes médicales)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('assistant', 'admin') DEFAULT 'assistant',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des médecins
CREATE TABLE doctors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    specialty VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    status ENUM('available', 'busy', 'offline') DEFAULT 'available',
    current_patient_id INT DEFAULT NULL,
    patients_today INT DEFAULT 0,
    total_patients INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (current_patient_id) REFERENCES patients(id) ON DELETE SET NULL
);

-- Table des patients
CREATE TABLE patients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    date_of_birth DATE NOT NULL,
    gender ENUM('M', 'F') NOT NULL,
    blood_type VARCHAR(5),
    address TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    registration_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table de la file d'attente
CREATE TABLE queue (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT NOT NULL,
    doctor_id INT,
    arrival_time TIME NOT NULL,
    entry_time TIME,
    exit_time TIME,
    priority ENUM('normal', 'urgent') DEFAULT 'normal',
    reason VARCHAR(255),
    status ENUM('waiting', 'incabinet', 'completed', 'cancelled') DEFAULT 'waiting',
    created_at DATE NOT NULL,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);

-- Données initiales
-- Mot de passe: assistant123 (hashé avec password_hash)
INSERT INTO users (username, password, full_name, role) VALUES
('marie.assistant', '$2y$10$YourHashHere', 'Marie Assistante', 'assistant');

-- Médecins initiaux
INSERT INTO doctors (first_name, last_name, specialty, phone, status) VALUES
('Youssef', 'Amrani', 'Médecin Généraliste', '+213 555 100 200', 'available'),
('Samira', 'Meziani', 'Cardiologue', '+213 555 200 300', 'available'),
('Rachid', 'Boudiaf', 'Pédiatre', '+213 555 300 400', 'available');

-- Patients initiaux
INSERT INTO patients (first_name, last_name, phone, email, date_of_birth, gender, blood_type, address, status, registration_date) VALUES
('Ahmed', 'Benali', '+213 555 123 456', 'ahmed.benali@email.com', '1985-05-15', 'M', 'O+', '12 Rue Didouche Mourad, Alger', 'active', '2024-01-15'),
('Fatima', 'Cherif', '+213 555 789 012', 'fatima.cherif@email.com', '1992-08-22', 'F', 'A+', '45 Avenue de l''Indépendance, Alger', 'active', '2024-02-20');