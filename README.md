# Lawyer Directory Platform

> A beginner-friendly full-stack web project for learning PHP, MySQL, HTML, CSS, and JavaScript.

---

## 📋 Table of Contents

1. [What is this Project?](#what-is-this-project)
2. [Technologies Used](#technologies-used)
3. [Project Structure](#project-structure)
4. [Features](#features)
5. [Installation Guide](#installation-guide)
6. [Database Setup](#database-setup)
7. [How to Use](#how-to-use)
8. [Understanding the Code](#understanding-the-code)
9. [Customization Ideas](#customization-ideas)
10. [Troubleshooting](#troubleshooting)

---

## 🎯 What is this Project?

This is a **Lawyer Directory and Rating Platform** - a website where:

- **Users** can search for lawyers, view their profiles, and leave reviews with star ratings
- **Lawyers** can submit their profiles to be listed in the directory
- **Admins** can approve lawyer listings and manage reviews

This project is designed as a **learning resource** for beginner web developers who want to understand how full-stack web applications work.

---

## 🛠️ Technologies Used

This project uses only **basic, beginner-friendly technologies**:

| Technology | Purpose |
|------------|---------|
| **HTML** | Structure of web pages |
| **CSS** | Styling and layout |
| **JavaScript** | Interactive features (no frameworks!) |
| **PHP** | Server-side processing |
| **MySQL** | Database to store information |
| **XAMPP** | Local server environment |

**No frameworks!** Everything is built from scratch so you can understand how it all works.

---

## 📁 Project Structure

```
lawyer-directory/
│
├── admin/                      # Admin panel files
│   └── admin.php              # Admin dashboard
│
├── css/                        # Stylesheets
│   └── style.css              # Main stylesheet
│
├── images/                     # Image uploads folder
│   └── default-avatar.jpg     # Default profile picture
│
├── includes/                   # Reusable PHP components
│   ├── database.php           # Database connection
│   ├── header.php             # Page header + navigation
│   └── footer.php             # Page footer
│
├── js/                         # JavaScript files
│   └── script.js              # Main JavaScript file
│
├── database.sql                # Database schema + sample data
├── index.php                   # Homepage
├── lawyers.php                 # Lawyer listing page
├── lawyer-profile.php          # Individual lawyer profile
├── add-lawyer.php              # Form to submit lawyer profile
├── submit-review.php           # Process review submissions
└── README.md                   # This file!
```

---

## ✨ Features

### For Users:
- ✅ Search lawyers by name or specialization
- ✅ Browse lawyers in a responsive grid
- ✅ View detailed lawyer profiles
- ✅ See average ratings and read reviews
- ✅ Submit star ratings (1-5) and written reviews

### For Lawyers:
- ✅ Submit profile with photo, contact info, and experience
- ✅ Profiles require admin approval before going live

### For Admins:
- ✅ Approve pending lawyer listings
- ✅ Delete inappropriate reviews
- ✅ View platform statistics

---

## 🚀 Installation Guide

### Step 1: Install XAMPP

XAMPP is a free software that gives you a local server environment.

1. **Download XAMPP** from: https://www.apachefriends.org/
2. **Install it** on your computer
3. **Start Apache and MySQL** using the XAMPP Control Panel

```
┌─────────────────────────────────────────┐
│  XAMPP Control Panel                    │
├─────────────────────────────────────────┤
│  [Start] Apache    ✓ Running            │
│  [Start] MySQL     ✓ Running            │
└─────────────────────────────────────────┘
```

### Step 2: Download This Project

1. **Download** all the project files
2. **Copy** the entire `lawyer-directory` folder
3. **Paste** it into your XAMPP `htdocs` folder:
   - **Windows:** `C:\xampp\htdocs\`
   - **Mac:** `/Applications/XAMPP/htdocs/`
   - **Linux:** `/opt/lampp/htdocs/`

Your folder structure should look like:
```
htdocs/
└── lawyer-directory/
    ├── admin/
    ├── css/
    ├── images/
    ├── includes/
    ├── js/
    ├── database.sql
    ├── index.php
    └── ...
```

### Step 3: Create the Database

1. **Open your browser** and go to: `http://localhost/phpmyadmin`
2. **Click "New"** on the left sidebar to create a database
3. **Enter database name:** `lawyer_directory`
4. **Click "Create"**

### Step 4: Import the Database Tables

1. **Click on** the `lawyer_directory` database you just created
2. **Click the "SQL" tab** at the top
3. **Open** the `database.sql` file from this project
4. **Copy all the SQL code** from that file
5. **Paste it** into the SQL text box in phpMyAdmin
6. **Click "Go"** to execute

✅ **Success!** Your database is now set up with tables and sample data.

---

## 🗄️ Database Structure

### Tables Created:

#### 1. `lawyers` Table
Stores lawyer profile information.

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT | Unique identifier (auto-increments) |
| `name` | VARCHAR | Lawyer's full name |
| `photo` | VARCHAR | Path to profile photo |
| `specialization` | VARCHAR | Area of law (e.g., "Criminal Law") |
| `experience` | INT | Years of experience |
| `phone` | VARCHAR | Contact phone number |
| `email` | VARCHAR | Contact email address |
| `status` | TINYINT | 0=pending, 1=approved |
| `created_at` | TIMESTAMP | When profile was submitted |

#### 2. `reviews` Table
Stores user reviews and ratings.

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT | Unique identifier |
| `lawyer_id` | INT | Links to the lawyer being reviewed |
| `user_name` | VARCHAR | Name of reviewer |
| `rating` | INT | Star rating (1-5) |
| `comment` | TEXT | Review text |
| `created_at` | TIMESTAMP | When review was submitted |

#### 3. `admins` Table
Stores admin login credentials.

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT | Unique identifier |
| `username` | VARCHAR | Admin username |
| `password` | VARCHAR | Admin password |
| `created_at` | TIMESTAMP | When account was created |

---

## 🎮 How to Use

### Access the Website

Open your browser and go to:
```
http://localhost/lawyer-directory/
```

### Default Admin Login

To access the admin panel:
- **URL:** `http://localhost/lawyer-directory/admin/admin.php`
- **Username:** `admin`
- **Password:** `admin123`

⚠️ **Important:** Change this password in a real application!

### Test the Features

1. **Browse Lawyers:** Visit the homepage or click "Find Lawyers"
2. **View Profile:** Click "View Profile" on any lawyer card
3. **Leave a Review:** On a profile page, scroll down and fill out the review form
4. **Add a Lawyer:** Click "List Your Practice" and submit the form
5. **Admin Approval:** Log in as admin and approve the new lawyer
6. **Delete Review:** As admin, delete any inappropriate reviews

---

## 📚 Understanding the Code

### How Data Flows Through the Application

```
User submits form → PHP processes → Saves to MySQL → PHP fetches → Displays on page
```

### Key Concepts Explained

#### 1. Database Connection (`includes/database.php`)

```php
// This creates a connection to MySQL using PDO
$pdo = new PDO("mysql:host=localhost;dbname=lawyer_directory", "root", "");
```

**What is PDO?**
- PDO = PHP Data Objects
- It's a secure way to interact with databases
- It prevents SQL injection when used with prepared statements

#### 2. Fetching Data Example

```php
// Prepare a SQL query
$stmt = $pdo->prepare("SELECT * FROM lawyers WHERE id = :id");

// Execute with parameters (prevents SQL injection!)
$stmt->execute(['id' => $lawyerId]);

// Get the result
$lawyer = $stmt->fetch();
```

#### 3. Displaying Data Safely

```php
// Always use htmlspecialchars() when displaying user data!
echo htmlspecialchars($lawyer['name']);
```

**Why?** This prevents hackers from injecting malicious JavaScript code.

#### 4. The POST/Redirect/GET Pattern

In `submit-review.php`:
```php
// 1. Process the form submission (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save to database...
    
    // 2. Redirect to another page
    header("Location: lawyer-profile.php?id=$lawyerId");
    exit;
}
```

**Why?** This prevents the "form resubmission" warning when users refresh the page.

#### 5. File Uploads

In `add-lawyer.php`:
```php
// Check if file was uploaded
if ($_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    // Move file from temp location to images folder
    move_uploaded_file($_FILES['photo']['tmp_name'], 'images/' . $filename);
}
```

---

## 🎨 Customization Ideas

Want to make this project your own? Here are some ideas:

### Easy Modifications

1. **Change the Colors**
   - Edit `css/style.css`
   - Look for `:root` at the top to change the color scheme

2. **Add More Specializations**
   - Edit the form in `add-lawyer.php`
   - Change the specialization input to a dropdown

3. **Add Lawyer Location**
   - Add a `location` column to the lawyers table
   - Update the form and display pages

### Medium Difficulty

4. **Add User Registration**
   - Create a users table
   - Add login/logout functionality
   - Track which user wrote each review

5. **Add Lawyer Categories**
   - Create a categories table
   - Allow filtering by category

6. **Add Review Likes**
   - Add a likes system for helpful reviews

### Advanced Challenges

7. **Email Notifications**
   - Send email when a lawyer is approved
   - Use PHP's `mail()` function or PHPMailer

8. **Search Autocomplete**
   - Use AJAX to show suggestions as users type

9. **Add Pagination**
   - Show 10 lawyers per page with "Next/Previous" buttons

---

## 🔧 Troubleshooting

### Problem: "Database connection failed"

**Solution:**
1. Make sure XAMPP MySQL is running
2. Check your database credentials in `includes/database.php`
3. Verify the database `lawyer_directory` exists in phpMyAdmin

### Problem: "Page not found" or 404 error

**Solution:**
1. Make sure the folder is named `lawyer-directory` (not `lawyer-directory-main`)
2. Check that it's in the correct `htdocs` folder
3. Try accessing: `http://localhost/lawyer-directory/index.php`

### Problem: Images not showing up

**Solution:**
1. Make sure the `images` folder exists and is writable
2. Check that `default-avatar.jpg` exists in the images folder
3. For uploaded photos, check file permissions

### Problem: "Access denied" for database

**Solution:**
1. Open `includes/database.php`
2. Check the username and password:
   ```php
   $username = 'root';  // Default XAMPP username
   $password = '';      // Default XAMPP password (empty)
   ```

### Problem: Reviews not saving

**Solution:**
1. Check that the `reviews` table was created properly
2. Make sure all required fields are filled
3. Check PHP error logs in XAMPP

---

## 📖 Learning Resources

Want to learn more? Here are some free resources:

### PHP
- [PHP Official Documentation](https://www.php.net/docs.php)
- [W3Schools PHP Tutorial](https://www.w3schools.com/php/)

### MySQL
- [MySQL Tutorial](https://www.mysqltutorial.org/)
- [SQLZoo Interactive Tutorial](https://sqlzoo.net/)

### HTML & CSS
- [MDN Web Docs](https://developer.mozilla.org/)
- [freeCodeCamp](https://www.freecodecamp.org/)

### JavaScript
- [JavaScript.info](https://javascript.info/)
- [Eloquent JavaScript (Free Book)](https://eloquentjavascript.net/)

---

## ⚠️ Security Notes

This project is for **learning purposes**. Before using it in production:

1. **Hash all passwords** using `password_hash()`
2. **Use HTTPS** for secure connections
3. **Validate all inputs** more strictly
4. **Add CSRF protection** tokens to forms
5. **Set proper file upload limits** and validate file types
6. **Use environment variables** for database credentials

---

## 📝 License

This project is open source and free to use for educational purposes.

---

## 🤝 Contributing

Found a bug or want to improve this project? Contributions are welcome!

Some ways to help:
- Report bugs or issues
- Suggest new features
- Improve documentation
- Add more beginner-friendly comments

---

## 💬 Questions?

If you have questions about this project:

1. Read through the code comments - they're detailed!
2. Check the troubleshooting section above
3. Search online for the specific error message
4. Ask in web development forums

---

## 🎉 Congratulations!

You've set up your first full-stack web application! 

**What you've learned:**
- How to connect PHP to a MySQL database
- How to create, read, update, and delete data (CRUD)
- How to handle file uploads
- How to create a responsive layout with CSS
- How to use JavaScript for interactivity
- How sessions work in PHP

**Keep building and happy coding!** 🚀

---

*Last updated: 2024*
*Created for educational purposes*
