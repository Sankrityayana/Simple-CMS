-- Create Database
CREATE DATABASE IF NOT EXISTS simple_cms;
USE simple_cms;

-- Pages Table
CREATE TABLE IF NOT EXISTS pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content TEXT,
    meta_description VARCHAR(500),
    meta_keywords VARCHAR(255),
    status ENUM('published', 'draft') DEFAULT 'draft',
    author VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample Pages
INSERT INTO pages (title, slug, content, meta_description, meta_keywords, status, author) VALUES
('Home', 'home', '<h2>Welcome to Our Website</h2><p>This is the home page content. You can edit this page to customize your website homepage.</p><p>Our CMS makes it easy to manage your content with a simple and intuitive interface.</p>', 'Welcome to our website homepage', 'home, welcome, cms', 'published', 'Admin'),
('About Us', 'about-us', '<h2>About Our Company</h2><p>We are a leading company in our industry with years of experience.</p><p>Our mission is to provide excellent service and innovative solutions to our clients.</p>', 'Learn more about our company and mission', 'about, company, mission', 'published', 'Admin'),
('Services', 'services', '<h2>Our Services</h2><ul><li>Web Development</li><li>Mobile Apps</li><li>Digital Marketing</li><li>SEO Optimization</li></ul><p>We offer comprehensive digital solutions tailored to your needs.</p>', 'Explore our professional services', 'services, web development, apps', 'published', 'Admin'),
('Contact', 'contact', '<h2>Get In Touch</h2><p>We would love to hear from you! Contact us for any inquiries.</p><p><strong>Email:</strong> info@example.com</p><p><strong>Phone:</strong> (555) 123-4567</p>', 'Contact us for inquiries and support', 'contact, email, phone', 'published', 'Admin'),
('Privacy Policy', 'privacy-policy', '<h2>Privacy Policy</h2><p>This page is currently being updated. Check back soon for our complete privacy policy.</p>', 'Our privacy policy and data protection', 'privacy, policy, data', 'draft', 'Admin');
