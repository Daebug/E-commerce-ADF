# 🛍️ Ardeur De France - E-Commerce Platform

A comprehensive French-themed e-commerce platform built with PHP, MySQL, and modern web technologies. This full-stack application provides a complete online shopping experience with customer and admin functionalities.

## ✨ Features

### 🛒 Customer Features
- **User Authentication**: Secure login and registration system with session management
- **Product Catalog**: Browse and search through product collections
- **Shopping Cart**: Add, remove, and modify items in cart
- **Checkout Process**: Complete order placement with payment integration
- **PayPal Integration**: Secure payment processing using PayPal sandbox
- **Order Tracking**: View order status and history
- **User Profile**: Manage personal information and order history
- **Real-time Chat**: Customer support chat functionality
- **Responsive Design**: Mobile-friendly interface

### 👨‍💼 Admin Features
- **Admin Dashboard**: Comprehensive product and order management
- **Product Management**: Add, update, and delete products (CRUD operations)
- **Order Management**: Track and update order status
- **User Management**: Monitor user accounts and activities
- **Inventory Control**: Manage product stock and availability

### 🔧 Technical Features
- **Session Management**: Secure user sessions and authentication
- **Database Integration**: MySQL database with normalized structure
- **Email Integration**: PHPMailer for transactional emails
- **Search Functionality**: Product search with real-time results
- **File Upload**: Product image management system
- **Status Tracking**: Order status updates and notifications

## 🏗️ Project Structure

```
E-commerce-ADF/
├── ArdeurDeFrance-FrontEnd/          # Frontend application
│   ├── admin-side/                   # Admin panel
│   │   └── admin.php                 # Admin dashboard
│   ├── CSS-Files/                    # Stylesheets
│   ├── JS-Scripts/                   # JavaScript files
│   │   ├── admin-js/                 # Admin-specific scripts
│   │   ├── BackgroundProcess.js      # Background operations
│   │   ├── nav.js                    # Navigation functionality
│   │   └── profile.js                # Profile management
│   ├── profile-page/                 # User profile section
│   ├── Source-Files/                 # Static assets and images
│   ├── MainForm.php                  # Main homepage
│   ├── LogInSignUpForm.php           # Authentication pages
│   ├── ProductView.php               # Product details page
│   ├── ViewCart.php                  # Shopping cart page
│   ├── Checkout.php                  # Checkout process
│   ├── ViewOrder.php                 # Order tracking
│   ├── Chat.php                      # Customer support chat
│   ├── crud.php                      # CRUD operations interface
│   └── WebForm.php                   # General form handling
├── process/                          # Backend processing
│   ├── PHPMailer-master/             # Email library
│   ├── config.php                    # PayPal configuration
│   ├── additem.php                   # Add products to cart
│   ├── addtocart.php                 # Cart management
│   ├── checkoutprocess.php           # Payment processing
│   ├── login.php                     # User authentication
│   ├── register.php                  # User registration
│   ├── placeOrder.php                # Order placement
│   ├── search.php                    # Product search
│   ├── updatestatus.php              # Order status updates
│   └── [other processing scripts]
├── connection/                       # Database configuration
│   └── connection.php                # MySQL connection
└── image/                           # Image assets
    └── product-image/               # Product photos
```

## 🚀 Getting Started

### Prerequisites
- **PHP 7.4+** with the following extensions:
  - mysqli
  - session
  - json
- **MySQL 5.7+** or **MariaDB 10.2+**
- **Apache** or **Nginx** web server
- **Composer** (for dependency management)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/E-commerce-ADF.git
   cd E-commerce-ADF
   ```

2. **Set up the database**
   - Create a MySQL database named `ardeur_de_france`
   - Import the database schema (SQL file should be provided separately)
   - Update database credentials in `connection/connection.php`

3. **Configure PayPal**
   - Update PayPal credentials in `process/config.php`
   - Set your PayPal sandbox/production settings
   - Configure return and cancel URLs

4. **Configure web server**
   - Point your web server document root to the project directory
   - Ensure PHP has write permissions for image uploads
   - Enable URL rewriting if needed

5. **Set up email configuration**
   - Configure SMTP settings in PHPMailer
   - Update email credentials for order notifications

### Configuration

#### Database Connection
Edit `connection/connection.php`:
```php
<?php
$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "ardeur_de_france";
?>
```

#### PayPal Configuration
Edit `process/config.php` with your PayPal credentials:
```php
define('PAYPAL_ID', 'your-paypal-business-email');
define('PAYPAL_SANDBOX', TRUE); // Set to FALSE for production
```

## 💻 Usage

### For Customers
1. **Registration/Login**: Access the platform via `LogInSignUpForm.php`
2. **Browse Products**: Explore the catalog on the main page
3. **Add to Cart**: Select products and add them to your shopping cart
4. **Checkout**: Complete your purchase using the secure checkout process
5. **Track Orders**: Monitor your order status in the user profile

### For Administrators
1. **Admin Access**: Navigate to `/admin-side/admin.php`
2. **Product Management**: Add, edit, or remove products from the catalog
3. **Order Management**: Update order statuses and track fulfillment
4. **User Management**: Monitor customer accounts and activities

## 🔑 Key Components

### Authentication System
- Secure session-based authentication
- Password hashing and validation
- Session timeout and security measures

### Shopping Cart
- Persistent cart across sessions
- Real-time cart updates
- Quantity management

### Payment Integration
- PayPal payment processing
- Order confirmation system
- Payment status tracking

### Admin Panel
- Comprehensive product management
- Order fulfillment tracking
- User account oversight

## 🛡️ Security Features

- **SQL Injection Protection**: Prepared statements and input validation
- **Session Security**: Secure session management and timeout
- **Authentication**: Protected admin areas and user-specific content
- **Input Sanitization**: XSS prevention and data validation
- **File Upload Security**: Secure image upload handling

## 🎨 Frontend Technologies

- **HTML5 & CSS3**: Modern, responsive design
- **JavaScript/jQuery**: Interactive user interface
- **Font Awesome**: Icon library for enhanced UI
- **Responsive Design**: Mobile-first approach

## 📧 Email Integration

- **PHPMailer**: Professional email sending capability
- **Order Notifications**: Automated order confirmation emails
- **Status Updates**: Email notifications for order status changes

## 🔄 API Endpoints

The application includes various processing scripts that handle:
- User authentication (`process/login.php`, `process/register.php`)
- Cart management (`process/addtocart.php`, `process/removeCartItem.php`)
- Order processing (`process/placeOrder.php`, `process/checkoutprocess.php`)
- Product search (`process/search.php`)
- Status updates (`process/updatestatus.php`)

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -am 'Add new feature'`)
4. Push to the branch (`git push origin feature/new-feature`)
5. Create a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support and questions:
- Create an issue in the GitHub repository
- Contact the development team
- Check the documentation in the `process/PHPMailer-master/` directory

## 🔮 Future Enhancements

- **Multi-language Support**: Expand beyond French theme
- **Advanced Analytics**: Sales and user behavior tracking
- **Mobile App**: Native mobile application
- **Social Integration**: Social media login and sharing
- **Advanced Search**: Filters and category-based search
- **Wishlist Feature**: Save products for later
- **Review System**: Customer product reviews and ratings

---

**Ardeur De France** - Bringing French elegance to e-commerce! 🇫🇷