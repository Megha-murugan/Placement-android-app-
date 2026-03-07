-- Create Database
CREATE DATABASE IF NOT EXISTS job_portal;
USE job_portal;

-- Students Table
CREATE TABLE IF NOT EXISTS students (
    s_id INT AUTO_INCREMENT PRIMARY KEY,
    s_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    college VARCHAR(255) NOT NULL,
    degree VARCHAR(255) NOT NULL,
    skills TEXT,
    resume TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Jobs Table
CREATE TABLE IF NOT EXISTS jobs (
    j_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    company VARCHAR(255) NOT NULL,
    location VARCHAR(100) NOT NULL,
    type ENUM('Full-time', 'Part-time', 'Internship', 'Contract') NOT NULL,
    salary VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    skills TEXT,
    posted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Applications Table (named 'result' as per your ER diagram)
CREATE TABLE IF NOT EXISTS result (
    r_id INT AUTO_INCREMENT PRIMARY KEY,
    j_id INT NOT NULL,
    s_id INT NOT NULL,
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (j_id) REFERENCES jobs(j_id) ON DELETE CASCADE,
    FOREIGN KEY (s_id) REFERENCES students(s_id) ON DELETE CASCADE
);

-- Admin Table (for admin credentials)
CREATE TABLE IF NOT EXISTS admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Saved Jobs Table (for favorites)
CREATE TABLE IF NOT EXISTS saved_jobs (
    saved_id INT AUTO_INCREMENT PRIMARY KEY,
    s_id INT NOT NULL,
    j_id INT NOT NULL,
    saved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (s_id) REFERENCES students(s_id) ON DELETE CASCADE,
    FOREIGN KEY (j_id) REFERENCES jobs(j_id) ON DELETE CASCADE,
    UNIQUE KEY unique_save (s_id, j_id)
);

-- Insert default admin
INSERT INTO admin (email, password) VALUES 
('admin@jobhub.com', 'admin123');

-- Insert some demo jobs
INSERT INTO jobs (title, company, location, type, salary, description, skills) VALUES
('Frontend Developer', 'TechCorp', 'Remote', 'Full-time', '$60k - $80k', 
'We are looking for a talented Frontend Developer to join our team. You will work on building modern web applications using React and other cutting-edge technologies.',
'React, JavaScript, CSS, HTML'),

('UI/UX Designer', 'Creative Studio', 'Mumbai', 'Full-time', '$50k - $70k',
'Join our design team to create beautiful and intuitive user experiences. Work closely with developers and product managers.',
'Figma, Adobe XD, Prototyping'),

('Backend Developer Intern', 'StartupXYZ', 'Bangalore', 'Internship', '$15k - $20k',
'Learn and grow with our backend team. Work on real projects using Node.js and databases.',
'Node.js, MongoDB, APIs'),

('Full Stack Developer', 'WebSolutions Inc', 'Pune', 'Full-time', '$70k - $90k',
'Build end-to-end web applications. Work with both frontend and backend technologies.',
'JavaScript, React, Node.js, SQL'),

('Data Analyst', 'DataCorp', 'Delhi', 'Contract', '$40k - $60k',
'Analyze data and create insights. Work with large datasets and visualization tools.',
'Python, SQL, Tableau, Excel');