-- Création de la base de données
CREATE DATABASE IF NOT EXISTS patient_dashboard;
USE patient_dashboard;

-- Patients
CREATE TABLE IF NOT EXISTS patients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    date_of_birth DATE,
    blood_type VARCHAR(5),
    insurance_id VARCHAR(50),
    emergency_contact VARCHAR(100),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Docteurs
CREATE TABLE IF NOT EXISTS doctors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    specialty VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL
);

-- Rendez-vous
CREATE TABLE IF NOT EXISTS appointments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status ENUM('scheduled', 'completed', 'cancelled') DEFAULT 'scheduled',
    reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(id)
);

-- Données de test
INSERT IGNORE INTO patients (username, password, full_name, email, phone, date_of_birth, blood_type, insurance_id, emergency_contact, address) VALUES
('sarahj', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sarah maram', 'sarah@mest.com', '+213 1234567899', '1985-06-15', 'A+', 'INS123', 'mara +0557654321', '123 Main St');

INSERT INTO doctors (first_name, last_name, specialty, phone, status) VALUES
('Youssef', 'Amrani', 'Médecin Généraliste', '+213 555 100 200', 'available'),
('Samira', 'Meziani', 'Cardiologue', '+213 555 200 300', 'available'),
('Rachid', 'Boudiaf', 'Pédiatre', '+213 555 300 400', 'available');

INSERT IGNORE INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, reason) VALUES
(1, 1, '2024-12-20', '10:00:00', 'Contrôle régulier');