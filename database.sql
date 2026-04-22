-- =====================================================
-- LAWYER DIRECTORY DATABASE SCHEMA
-- =====================================================
-- This SQL file creates all the tables needed for the 
-- Lawyer Rating and Directory Platform
-- 
-- HOW TO USE:
-- 1. Open phpMyAdmin (http://localhost/phpmyadmin)
-- 2. Create a new database called "lawyer_directory"
-- 3. Click on the database, then go to "SQL" tab
-- 4. Copy and paste this entire file content
-- 5. Click "Go" to execute
-- =====================================================

-- -----------------------------------------------------
-- Table: lawyers
-- Purpose: Store all lawyer profile information
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS lawyers (
    -- Primary key - unique ID for each lawyer (auto-increments)
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Lawyer's full name
    name VARCHAR(255) NOT NULL,
    
    -- Path to the lawyer's profile photo (stored in images folder)
    photo VARCHAR(255) DEFAULT 'default-avatar.jpg',
    
    -- Area of law they specialize in (e.g., "Criminal Law", "Family Law")
    specialization VARCHAR(100) NOT NULL,
    
    -- Years of experience in the legal field
    experience INT DEFAULT 0,
    
    -- Contact phone number
    phone VARCHAR(20),
    
    -- Contact email address
    email VARCHAR(255),
    
    -- Status: 0 = pending approval, 1 = approved and visible
    status TINYINT DEFAULT 0,
    
    -- Timestamp when the record was created
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------------------------------
-- Table: reviews
-- Purpose: Store user reviews and ratings for lawyers
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS reviews (
    -- Primary key - unique ID for each review
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Foreign key - links to the lawyer being reviewed
    -- This connects to the 'id' column in the 'lawyers' table
    lawyer_id INT NOT NULL,
    
    -- Name of the person leaving the review
    user_name VARCHAR(100) NOT NULL,
    
    -- Star rating: 1 to 5 (5 being the best)
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    
    -- The actual review text/comment
    comment TEXT,
    
    -- Timestamp when the review was submitted
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Set up the foreign key relationship
    -- This ensures that if a lawyer is deleted, their reviews are also deleted
    FOREIGN KEY (lawyer_id) REFERENCES lawyers(id) ON DELETE CASCADE
);

-- -----------------------------------------------------
-- Table: admins
-- Purpose: Store admin login credentials
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS admins (
    -- Primary key - unique ID for each admin
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Admin username for login
    username VARCHAR(50) NOT NULL UNIQUE,
    
    -- Admin password (stored as plain text for this beginner project)
    -- In real projects, you should ALWAYS hash passwords!
    password VARCHAR(255) NOT NULL,
    
    -- Timestamp when the admin account was created
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- INSERT SAMPLE DATA
-- =====================================================

-- Insert a default admin account
-- Username: admin
-- Password: admin123
-- You should change this after first login!
INSERT INTO admins (username, password) VALUES ('admin', 'admin123');

-- Insert sample lawyers (approved)
INSERT INTO lawyers (name, photo, specialization, experience, phone, email, status) VALUES
('John Smith', 'lawyer1.jpg', 'Criminal Law', 15, '555-0101', 'john.smith@lawfirm.com', 1),
('Sarah Johnson', 'lawyer2.jpg', 'Family Law', 10, '555-0102', 'sarah.j@lawfirm.com', 1),
('Michael Chen', 'lawyer3.jpg', 'Corporate Law', 20, '555-0103', 'm.chen@lawfirm.com', 1),
('Emily Davis', 'lawyer4.jpg', 'Personal Injury', 8, '555-0104', 'emily.davis@lawfirm.com', 1),
('Robert Wilson', 'lawyer5.jpg', 'Real Estate Law', 12, '555-0105', 'r.wilson@lawfirm.com', 1),
('Lisa Anderson', 'lawyer6.jpg', 'Immigration Law', 7, '555-0106', 'lisa.a@lawfirm.com', 1);

-- Insert sample reviews
INSERT INTO reviews (lawyer_id, user_name, rating, comment) VALUES
(1, 'Alice Thompson', 5, 'John was excellent! He helped me win my case and was very professional throughout.'),
(1, 'Bob Martinez', 4, 'Great lawyer, very knowledgeable. Communication could have been a bit better.'),
(2, 'Carol White', 5, 'Sarah made my divorce process so much easier. Highly recommend!'),
(2, 'David Brown', 5, 'Very compassionate and understanding. She really cares about her clients.'),
(3, 'Eva Garcia', 4, 'Michael helped with our company merger. Very thorough and professional.'),
(4, 'Frank Lee', 5, 'Emily got me a great settlement. She fought hard for my rights.'),
(5, 'Grace Taylor', 4, 'Robert handled our property purchase smoothly. Good experience overall.'),
(6, 'Henry Clark', 5, 'Lisa helped me with my visa application. She knows immigration law very well!');

-- =====================================================
-- END OF DATABASE SCHEMA
-- =====================================================
