#  Job Portal - Complete Setup Guide

# Prerequisites
1. **XAMPP** (or WAMP/MAMP) - Includes Apache, MySQL, PHP
2. A web browser
3. Text editor (VS Code, Notepad++, etc.)

---

# Step-by-Step Installation

# Step 1: Install XAMPP
1. Download XAMPP from [https://www.apachefriends.org](https://www.apachefriends.org)
2. Install it (default location: `C:\xampp`)
3. Open XAMPP Control Panel
4. Start **Apache** and **MySQL** modules

---

# Step 2: Setup Database

1. Open browser and go to: `http://localhost/phpmyadmin`
2. Click on "SQL" tab
3. Copy and paste the entire **database.sql** file content
4. Click "Go" to execute
5. You should see "job_portal" database created with 5 tables

**Verify Tables Created:**
- students
- jobs
- result (applications)
- admin
- saved_jobs

---

# Step 3: Setup Project Files

1. Navigate to: `C:\xampp\htdocs\`
2. Create a new folder: `job_portal`
3. Place all your files in this folder:

```
C:\xampp\htdocs\job_portal\
├── index.html (your main HTML file)
├── config.php
├── student_signup.php
├── student_login.php
├── admin_login.php
├── get_jobs.php
├── post_job.php
├── delete_job.php
├── apply_job.php
├── get_applications.php
├── save_job.php
└── get_student_data.php
```

---

# Step 4: Update config.php (if needed)

Open `config.php` and verify these settings:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Empty for default XAMPP
define('DB_NAME', 'job_portal');
```

---

# Step 5: Test Your Setup

1. Open browser
2. Go to: `http://localhost/job_portal/index.html`
3. You should see the Job Portal welcome page!

---

# Testing the Application

# Test Student Registration:
1. Click "Student" button
2. Click "Don't have an account? Sign Up"
3. Fill the form and submit
4. Should redirect to Student Dashboard automatically

# Test Student Login:
1. Use the email and password you just created
2. Click "Login"
3. Should see your dashboard with jobs

# Test Admin Login:
```
Email: admin@jobhub.com
Password: admin123
```

# Test Database Connection:
Go to: `http://localhost/job_portal/get_jobs.php`

You should see JSON response with demo jobs:
```json
{
  "success": true,
  "message": "Jobs retrieved successfully",
  "data": [...]
}
```

---

# File Descriptions

| File                   | Purpose |
|------------------------|---------------|
| `database.sql`         | Creates database schema with tables |
| `config.php`           | Database connection configuration |
| `student_signup.php`   | Handles student registration |
| `student_login.php`    | Handles student authentication |
| `admin_login.php`      | Handles admin authentication |
| `get_jobs.php`         | Fetches all jobs from database |
| `post_job.php`         | Admin posts new job |
| `delete_job.php`       | Admin deletes job |
| `apply_job.php`        | Student applies for job |
| `get_applications.php` | Fetch applications for jobs |
| `save_job.php`         | Save/unsave jobs (favorites) |
| `get_student_data.php` | Get student's saved/applied jobs |

---

##  Database Structure

### students table
- `s_id` - Primary key
- `s_name` - Student name
- `email` - Unique email
- `password` - Hashed password
- `phone` - Contact number
- `college` - College/University name
- `degree` - Degree program
- `skills` - Comma-separated skills
- `resume` - Resume text/link
- `created_at` - Registration timestamp

# jobs table
- `j_id` - Primary key
- `title` - Job title
- `company` - Company name
- `location` - Job location
- `type` - Full-time/Part-time/Internship/Contract
- `salary` - Salary range
- `description` - Job description
- `skills` - Required skills
- `posted_at` - Post timestamp

# result table (applications)
- `r_id` - Primary key
- `j_id` - Foreign key to jobs
- `s_id` - Foreign key to students
- `applied_at` - Application timestamp

# admin table
- `admin_id` - Primary key
- `email` - Admin email
- `password` - Admin password

# saved_jobs table
- `saved_id` - Primary key
- `s_id` - Foreign key to students
- `j_id` - Foreign key to jobs
- `saved_at` - Save timestamp

---

# Common Issues & Solutions

# Issue 1: Can't connect to database
**Solution:** 
- Make sure MySQL is running in XAMPP
- Check config.php credentials
- Verify database name is "job_portal"

# Issue 2: Page shows blank
**Solution:**
- Check if Apache is running
- Make sure files are in `htdocs/job_portal/`
- Check browser console for errors (F12)

# Issue 3: "Access denied for user 'root'"
**Solution:**
- In config.php, set `DB_PASS` to empty string: `''`
- Or set your MySQL password if you've changed it

# Issue 4: PHP files download instead of executing
**Solution:**
- Apache module is not running
- Start Apache in XAMPP Control Panel

# Issue 5: CORS errors in console
**Solution:**
- Make sure you're accessing via `http://localhost/` not `file://`
- CORS headers are already added in PHP files

---

# Current Features (HTML + localStorage)

The current HTML file works with **localStorage** (browser storage) for quick testing. This is perfect for:
-  Immediate testing without setup
-  Understanding the UI/UX
-  Demonstrating features
-  Quick submission if time is limited

---

# Integrating with MySQL (Advanced)

To connect the HTML file with MySQL PHP backend, you'll need to modify the JavaScript to make AJAX calls to PHP files instead of using localStorage.

**Example modification for student signup:**
```javascript
function studentSignup(e) {
    e.preventDefault();
    
    const formData = new FormData();
    formData.append('name', document.getElementById('studentName').value);
    formData.append('email', document.getElementById('studentEmail').value);
    formData.append('password', document.getElementById('studentPassword').value);
    // ... add other fields
    
    fetch('http://localhost/job_portal/student_signup.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentUser = { ...data.data, role: 'student' };
            alert(data.message);
            showStudentDashboard();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}
```

---

# Submission Checklist

For the project submission, include:

-  **index.html** - Main application file
-  **database.sql** - Database schema
-  **All PHP files** - Backend API files
-  **config.php** - Database configuration
-  **README.md** - This documentation
-  **Screenshots** - Of working application
-  **ER Diagram** -  hand-drawn diagram (photo)

---

# Project Features Summary

# Student Features:
1.  Registration with full details
2.  Login authentication
3.  View all available jobs
4.  Search and filter jobs
5.  Save jobs as favorites 
6.  Apply for jobs (resume sent to database)
7.  Track applied jobs
8.  View saved jobs

# Admin Features:
1.  Secure login (hardcoded credentials)
2.  Post new jobs
3.  Delete jobs
4.  View all applications
5.  See applicant details
6.  Download/view student resumes
7.  Dashboard with statistics

---

#  Quick Test Commands

**Test database connection:**
```
http://localhost/job_portal/get_jobs.php
```

**Check if PHP is working:**
```
http://localhost/job_portal/config.php
```

---