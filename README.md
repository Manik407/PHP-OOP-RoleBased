⭐ Role-Based User Management System (PHP OOP + PDO + MySQL)

A secure, modular, and fully role-based User Management System built using PHP OOP, PDO, and MySQL.
The project demonstrates clean architecture, reusable classes, prepared statements, CSRF protection, password hashing, and admin/user role separation.

🚀 Features
🔐 Authentication & Security

Secure login system using password_hash + password_verify
Session-based authentication with session_regenerate_id
CSRF protection on all POST forms
Prepared statements (PDO) for SQL injection prevention
Role-based access control (RBAC):
Admin: Full CRUD access
User: Can only edit own profile

👤 User Management (CRUD)
Add new users (Admin only)
Edit user details
Update password (hashed)
Delete users (Admin only)
Prevent self-delete for safety
View all users in a responsive table

🗂 Clean MVC-like Structure
Models → Data structure (User model)
Repositories → Database interaction (UserRepository)
Controllers → Business logic (UserController, AuthController)
Auth → Login, logout, CSRF token manager
Public → Pages/UI with Bootstrap styling

🎨 Modern UI
Fully responsive UI
Custom CSS + Bootstrap
Smooth UX with clean layout

🛠 Installation & Setup
1️⃣ Clone the Repository
2️⃣ Configure Database
3️⃣ Update DB Credentials (if needed)
4️⃣ Start XAMPP Server
5️⃣ Access the Project http://localhost/OOP-RoleBased/public/

🔑 First-Time Setup (Initial Admin Creation)
If no users exist in the database:
Visiting /public/register.php will allow you to create the first admin.
After this, registration becomes admin-only.

🧩 Role Explanation
👑 Admin:
Add users
Edit any user
Delete any user except self
View all users
Assign roles

👤 User:
Edit own profile
Change own password
Cannot add or delete users
Cannot access admin pages

📸ScreenShots:

Login Page:
<img width="1919" height="964" alt="Login Page" src="https://github.com/user-attachments/assets/6172f523-8c7f-4068-b591-4e9481b7825d" />

Admin Dashboard:
<img width="1919" height="965" alt="Admin Dashboard" src="https://github.com/user-attachments/assets/2879515a-283c-40b7-8a11-424bd4169356" />

Add User Page:
<img width="1919" height="968" alt="Add User" src="https://github.com/user-attachments/assets/57960cdd-3c8d-40f1-bbe5-3646a203a0fa" />

Edit User Page:
<img width="1919" height="962" alt="Edit User" src="https://github.com/user-attachments/assets/52c09690-40f1-4ece-990e-1f4309c352d9" />

Delete User Page:
<img width="1919" height="968" alt="Delete User" src="https://github.com/user-attachments/assets/c2f7fb96-355c-40b6-b5af-5e6f8254239e" />

User's Dashboard:
<img width="1919" height="964" alt="User Dashboard" src="https://github.com/user-attachments/assets/e6d02a0b-b7cb-4b91-a161-b40cc495f6f9" />

🤝 Contributing
Feel free to submit issues or pull requests to improve the project.

📜 License
This project is open-source and free to use for learning purposes.
