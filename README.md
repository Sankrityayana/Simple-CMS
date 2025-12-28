# 📄 Simple CMS - Content Management System

A lightweight, user-friendly Content Management System for creating, editing, and managing web pages with SEO capabilities. Built with PHP and MySQL featuring a modern dark theme interface.

## ✨ Features

### Page Management
- **Create Pages**: Add new pages with rich content support
- **Edit Pages**: Update existing page content, titles, and metadata
- **Delete Pages**: Remove pages with confirmation
- **View Pages**: Preview pages with formatted content display
- **Auto-generated Slugs**: SEO-friendly URLs created automatically from page titles

### Content Features
- **HTML Content Support**: Write content with HTML formatting
- **Draft/Published Status**: Control page visibility with status management
- **Author Attribution**: Track page authors
- **Timestamps**: Automatic creation and update time tracking

### SEO Optimization
- **Meta Descriptions**: Add SEO-friendly descriptions for each page
- **Meta Keywords**: Define keywords for search engine optimization
- **SEO-friendly Slugs**: Clean URLs based on page titles

### User Interface
- **Dark Theme**: Modern dark color scheme with multicolor accents
- **No Gradients**: Clean solid color design
- **Responsive Layout**: Works on all devices (desktop, tablet, mobile)
- **Intuitive Navigation**: Easy-to-use interface with clear action buttons
- **Real-time Statistics**: Dashboard showing total, published, and draft pages
- **Color-coded Status**: Visual indicators for page status

## 🎨 Design

### Dark Theme Color Palette
- **Primary Background**: Deep navy (#0f172a)
- **Secondary Background**: Slate (#1e293b)
- **Accent Colors**:
  - 🔵 Blue (#3b82f6) - Primary actions
  - 🟢 Green (#10b981) - Success/Published
  - 🟠 Orange (#f59e0b) - Draft status
  - 🔴 Red (#ef4444) - Delete actions
  - 🟣 Purple (#8b5cf6) - Additional accents

### Design Principles
- Solid colors only (no gradients)
- High contrast for readability
- Consistent spacing and shadows
- Smooth transitions and hover effects

## 📋 Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache Web Server
- XAMPP (recommended)

### Setup Instructions

1. **Start XAMPP**
   ```
   - Start Apache and MySQL services
   - Ensure MySQL is running on port 3307
   ```

2. **Import Database**
   ```
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Import database.sql file
   - Database 'simple_cms' will be created automatically
   ```

3. **Deploy Files**
   ```
   - Place all files in htdocs/simple-cms directory
   - Ensure proper file permissions
   ```

4. **Access Application**
   ```
   Open browser: http://localhost/simple-cms
   ```

5. **Start Using**
   ```
   - System includes 5 sample pages
   - Click "Add Page" to create new content
   - Edit or delete existing pages as needed
   ```

## 💡 Usage Guide

### Creating a New Page

1. Click "➕ Add Page" in navigation
2. Fill in required fields:
   - **Page Title**: Enter your page title (slug auto-generated)
   - **Page Content**: Write content (HTML supported)
   - **Status**: Choose Draft or Published
   - **Author**: Enter author name
3. Optional SEO fields:
   - **Meta Description**: 150-160 characters recommended
   - **Meta Keywords**: Comma-separated keywords
4. Click "💾 Save Page"

### Editing a Page

1. From the pages list, click "✏️ Edit" on desired page
2. Modify any fields as needed
3. Click "💾 Update Page" to save changes
4. Current slug will be regenerated if title changes

### Viewing a Page

1. Click "👁️ View" to see full page preview
2. View formatted content with SEO information
3. Quick access to edit from view page

### Deleting a Page

1. Click "🗑️ Delete" button on page card
2. Confirm deletion in popup dialog
3. Page will be permanently removed

### Page Status Management

- **Draft**: Page is saved but not publicly visible
- **Published**: Page is live and visible to users

## 🛠️ Technical Details

### Database Structure

**Pages Table**
```sql
- id (Primary Key)
- title (VARCHAR 255)
- slug (VARCHAR 255, UNIQUE)
- content (TEXT)
- meta_description (VARCHAR 500)
- meta_keywords (VARCHAR 255)
- status (ENUM: 'published', 'draft')
- author (VARCHAR 100)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

### File Structure
```
simple-cms/
├── database.sql       # Database schema and sample data
├── config.php        # Database configuration
├── index.php         # Main application logic and views
├── style.css         # Dark theme styling
└── README.md         # Documentation
```

### Security Features
- PDO prepared statements (prevents SQL injection)
- Input sanitization with htmlspecialchars()
- Unique slug constraints
- Error handling with try-catch blocks

### Key Functions
- **generateSlug()**: Converts page titles to URL-friendly slugs
- Auto-lowercase conversion
- Special character replacement
- Duplicate slug prevention

## 🔧 Configuration

### Database Settings (config.php)
```php
$host = 'localhost:3307';
$dbname = 'simple_cms';
$username = 'root';
$password = '';
```

Modify these settings based on your environment.

## 📊 Sample Data

The system includes 5 pre-loaded sample pages:
1. **Home** - Published homepage content
2. **About Us** - Company information (Published)
3. **Services** - Service listing (Published)
4. **Contact** - Contact information (Published)
5. **Privacy Policy** - Draft privacy policy page

## 🎯 Use Cases

- Personal blogs
- Small business websites
- Documentation sites
- Portfolio websites
- Landing pages
- Corporate websites
- Educational content

## 🚀 Features Coming Soon

- User authentication system
- Image upload for content
- Category/tag system
- Search functionality
- Bulk operations
- Page templates
- Revision history

## 💻 Browser Support

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Opera (latest)

## 📝 Development Notes

### Slug Generation Logic
- Converts title to lowercase
- Replaces non-alphanumeric characters with hyphens
- Removes consecutive hyphens
- Trims leading/trailing hyphens

### Status Management
- Draft pages are stored but marked as not visible
- Published pages are marked as live content
- Status can be changed at any time during editing

## 🐛 Troubleshooting

**Issue**: Database connection error
- **Solution**: Check MySQL is running on port 3307
- Verify database credentials in config.php

**Issue**: Slug already exists error
- **Solution**: Page titles must be unique
- Modify the title slightly to generate different slug

**Issue**: Pages not displaying
- **Solution**: Check database has been imported
- Verify file permissions are correct

## 👤 Author

**Created by Sankrityayana**

## 📄 License

Free to use for personal and commercial projects.

---

*Simple CMS - Making content management easy and accessible*
