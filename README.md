# Blood Donation Management System

A comprehensive PHP-based web application designed to manage blood donation requests and connect donors with those in need. The system provides a transparent and accessible platform for patients, hospitals, and blood donors to efficiently coordinate blood donations.

## 🩸 Features

### For Donors
- **User Registration & Verification**: Secure registration process with admin verification
- **Profile Management**: Complete donor profile with medical information
- **Donation History**: Track past donations and eligibility status
- **Request Matching**: View blood requests matching donor's type and location
- **Availability Control**: Toggle availability status for donations
- **Real-time Notifications**: Get notified about urgent blood requests

### For Requesters
- **Blood Request Forms**: Easy-to-use forms for posting blood requirements
- **Urgency Levels**: Set priority levels (Low, Medium, High, Emergency)
- **Donor Search**: Search and filter donors by blood type and location
- **Direct Communication**: Contact donors directly via phone
- **Request Management**: Track and update request status

### For Administrators
- **Comprehensive Dashboard**: Overview of system statistics and activities
- **Donor Verification**: Approve/reject donor registrations
- **Request Management**: Monitor and manage blood requests
- **Donation Tracking**: Record and track completed donations
- **System Reports**: Generate various reports and analytics
- **User Management**: Manage donor and admin accounts

### General Features
- **Blood Type Compatibility**: Built-in compatibility checking system
- **Location-based Search**: Find donors and requests by city/state
- **Responsive Design**: Mobile-friendly interface using Bootstrap 5
- **Security Features**: SQL injection prevention, XSS protection, CSRF tokens
- **Data Validation**: Comprehensive input validation and sanitization

## 🛠️ Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Framework**: Bootstrap 5.1.3
- **Icons**: Font Awesome 6.0
- **Server**: Apache (XAMPP recommended)

## 📋 Prerequisites

- XAMPP/WAMP/LAMP with PHP 7.4+ and MySQL 5.7+
- Web browser (Chrome, Firefox, Safari, Edge)
- Text editor/IDE (optional, for customization)

## ⚡ Quick Installation

### Step 1: Download and Setup
1. Download or clone this repository
2. Extract the files to your XAMPP `htdocs` directory
3. Rename the folder to `blood-donation` (or any preferred name)

### Step 2: Database Setup
1. Start XAMPP and ensure Apache and MySQL services are running
2. Open phpMyAdmin (http://localhost/phpmyadmin)
3. Create a new database named `blood_donation_db`
4. Import the SQL file: `sql/blood_donation_db.sql`

### Step 3: Configuration
1. Open `includes/config.php`
2. Update database credentials if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'blood_donation_db');
   ```
3. Update the site URL:
   ```php
   define('SITE_URL', 'http://localhost/blood-donation/');
   ```

### Step 4: File Permissions
Ensure the following directories have write permissions:
- `assets/images/profiles/` (for profile pictures)
- `logs/` (for error logging)

## 🚀 Usage

### Accessing the System

1. **Main Website**: http://localhost/blood-donation/
2. **Admin Panel**: http://localhost/blood-donation/admin/
3. **Default Admin Credentials**:
   - Username: `admin`
   - Password: `password`

### For New Donors
1. Visit the main website
2. Click "Register" in the navigation menu
3. Fill out the donor registration form
4. Wait for admin verification (check email for updates)
5. Once verified, log in to access the donor dashboard

### For Blood Requests
1. Click "Request Blood" in the navigation menu
2. Fill out the blood request form with patient details
3. Submit the request
4. Use "Find Donors" to search for compatible donors
5. Contact donors directly using provided phone numbers

### For Administrators
1. Access the admin panel: http://localhost/blood-donation/admin/
2. Log in with admin credentials
3. Verify pending donor registrations
4. Monitor blood requests and system activities
5. Generate reports and manage the system

## 📊 Database Schema

### Main Tables
- **admin**: Administrator accounts
- **users**: Donor information and profiles
- **blood_requests**: Blood requirement requests
- **donations**: Completed donation records
- **contact_messages**: Contact form submissions
- **blood_inventory**: Blood bank inventory (optional)

### Key Relationships
- Donors can have multiple donations
- Blood requests can be linked to donations
- Admin manages user verification

## 🔧 Customization

### Changing Colors/Theme
Edit `assets/css/style.css` and modify the CSS variables:
```css
:root {
    --blood-red: #dc3545;
    --blood-red-dark: #c82333;
    --blood-red-light: #f5c6cb;
}
```

### Adding New Features
1. Create new PHP files in the appropriate directory
2. Include the configuration: `require_once 'includes/config.php';`
3. Use the header and footer includes for consistent layout
4. Follow the existing code structure and security practices

### Email Configuration
To enable email notifications, configure SMTP settings in `includes/config.php`:
```php
// Add email configuration
define('SMTP_HOST', 'your-smtp-server.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@domain.com');
define('SMTP_PASSWORD', 'your-password');
```

## 🔐 Security Features

- **SQL Injection Prevention**: Prepared statements with PDO
- **XSS Protection**: Input sanitization and output escaping
- **CSRF Protection**: CSRF tokens for forms
- **Session Security**: Secure session configuration
- **Password Hashing**: BCrypt password hashing
- **Input Validation**: Server-side validation for all inputs
- **File Upload Security**: Restricted file types and sizes

## 🐛 Troubleshooting

### Common Issues

**Database Connection Error**
- Check if MySQL service is running
- Verify database credentials in `config.php`
- Ensure the database exists and is properly imported

**Permission Denied Errors**
- Set proper write permissions for upload directories
- Check file ownership and permissions

**Page Not Found (404)**
- Verify the site URL in `config.php`
- Check if .htaccess rules are properly configured

**CSS/JS Not Loading**
- Check the SITE_URL configuration
- Verify file paths in header.php

## 📱 Mobile Responsiveness

The system is fully responsive and works on:
- Desktop computers
- Tablets
- Mobile phones
- All modern web browsers

## 🧪 Testing

### Test Accounts
The system includes sample data for testing:

**Sample Donors**:
- Username: `john_doe`, Password: `password`
- Username: `jane_smith`, Password: `password`

**Admin Account**:
- Username: `admin`, Password: `password`

### Testing Checklist
- [ ] Donor registration and verification
- [ ] Login/logout functionality
- [ ] Blood request submission
- [ ] Donor search and filtering
- [ ] Admin panel access and functions
- [ ] Profile management
- [ ] Mobile responsiveness

## 📈 Future Enhancements

- Email/SMS notifications
- GPS-based location services
- Blood bank inventory management
- Appointment scheduling system
- Mobile app development
- API integration
- Real-time chat system
- Social media integration

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This project is open source and available under the MIT License.

## 📞 Support

For support and questions:
- Email: admin@bloodbank.com
- Create an issue in the repository
- Check the FAQ section in the contact page

## 🙏 Acknowledgments

- Bootstrap team for the excellent CSS framework
- Font Awesome for the icon library
- PHP community for comprehensive documentation
- All contributors and testers

---

**Made with ❤️ for saving lives through blood donation**

*Every drop counts. Every donor matters. Every life saved is priceless.*