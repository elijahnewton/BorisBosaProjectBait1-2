CREATE DATABASE hostel_booking;
USE hostel_booking;

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    gender ENUM('Male','Female','Other') NOT NULL,
    registration_number VARCHAR(50) UNIQUE NOT NULL,
    course VARCHAR(100),
    year_of_study INT,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    email VARCHAR(100)
);

INSERT INTO admins (username, password, full_name, email) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@hostel.com');
-- password is "password"

CREATE TABLE hostels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type ENUM('Boys','Girls','Mixed') NOT NULL,
    address TEXT,
    total_rooms INT DEFAULT 0,
    description TEXT
);

CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hostel_id INT NOT NULL,
    room_number VARCHAR(20) NOT NULL,
    floor INT NOT NULL,
    room_type ENUM('Single','Double','Triple','Dormitory') NOT NULL,
    has_ac BOOLEAN DEFAULT FALSE,
    capacity INT NOT NULL,
    current_occupancy INT DEFAULT 0,
    price_per_month DECIMAL(10,2) NOT NULL,
    status ENUM('Available','Partially Available','Full','Maintenance') DEFAULT 'Available',
    FOREIGN KEY (hostel_id) REFERENCES hostels(id) ON DELETE CASCADE,
    UNIQUE KEY unique_room_per_hostel (hostel_id, room_number)
);

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    room_id INT NOT NULL,
    booking_date DATE NOT NULL,
    expected_checkin DATE NOT NULL,
    status ENUM('Pending','Approved','Rejected','Cancelled','Completed') DEFAULT 'Pending',
    payment_status ENUM('Unpaid','Paid','Refunded') DEFAULT 'Unpaid',
    remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);

CREATE TABLE complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    booking_id INT NULL,
    subject VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('Open','In Progress','Resolved') DEFAULT 'Open',
    admin_remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL
);

-- Insert some sample hostels and rooms
INSERT INTO hostels (name, type, address, total_rooms, description) VALUES
('Alpha House', 'Boys', 'Block A, Campus', 10, 'Modern boys hostel with all facilities'),
('Beta House', 'Girls', 'Block B, Campus', 12, 'Secure and comfortable girls hostel'),
('Gamma House', 'Mixed', 'Block C, Campus', 8, 'Co-ed hostel for senior students');

INSERT INTO rooms (hostel_id, room_number, floor, room_type, has_ac, capacity, current_occupancy, price_per_month, status) VALUES
(1, '101', 1, 'Double', TRUE, 2, 0, 5000, 'Available'),
(1, '102', 1, 'Single', FALSE, 1, 0, 3500, 'Available'),
(1, '201', 2, 'Triple', TRUE, 3, 1, 4500, 'Partially Available'),
(2, '101', 1, 'Double', FALSE, 2, 2, 4000, 'Full'),
(2, '102', 1, 'Single', TRUE, 1, 1, 6000, 'Full'),
(3, '101', 1, 'Dormitory', FALSE, 6, 2, 2000, 'Partially Available');
