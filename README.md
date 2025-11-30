# PHP-Dynamic-Blog-Content-Management-System

A full-stack web application built with PHP and MySQL featuring a responsive blog frontend and a powerful admin dashboard for content management.
📋 Project Overview

This project is a complete Blog and Content Management System developed as an academic assignment for Georgian College's Web Development course. The application allows users to view blog posts on a responsive frontend while providing administrators with full CRUD (Create, Read, Update, Delete) capabilities through a secure backend interface.

    📝 Repository Note: This repository contains the complete source code as submitted for the academic assignment. The original development was conducted in a private academic repository with full commit history. This public version is structured for portfolio review and demonstrates the final delivered codebase.

🚀 Features
Frontend Blog

    Responsive Design: Fully responsive layout that works on desktop, tablet, and mobile devices

    Blog Post Display: Clean, readable presentation of blog articles

    Category Filtering: Organize and filter posts by categories

    Search Functionality: Find specific content across all blog posts

Admin Dashboard (CMS)

    User Authentication: Secure login system for administrators

    Post Management: Create, edit, update, and delete blog posts

    Category Management: Organize content with customizable categories

    Media Handling: Image upload and management capabilities

    User Management: Admin user account controls

Security & Validation

    SQL Injection Protection: Prepared statements and parameterized queries

    XSS Prevention: Input sanitization and output escaping

    Form Validation: Comprehensive server-side and client-side validation

    Session Management: Secure user authentication and authorization

🛠️ Technology Stack

    Backend: PHP (Procedural)

    Database: MySQL

    Frontend: HTML5, CSS3, JavaScript

    Security: Prepared Statements, Input Sanitization, Password Hashing

    Server: Apache/XAMPP Environment

🗄️ Database Schema

The application uses a relational database with the following main tables:

    users: Admin user accounts and authentication

    posts: Blog post content, metadata, and relationships

🔧 Installation & Setup
Prerequisites

    PHP 7.4 or higher

    MySQL 5.7 or higher

    Apache Web Server

    XAMPP/WAMP/MAMP stack recommended

Quick Start

    Clone this repository to your web server directory

    Import the database schema from php_final_project_sql

    Update database credentials in /classes/Database.php

    Access the application via your web browser

🎯 Key Technical Achievements

    Full CRUD Implementation: Complete Create, Read, Update, Delete operations for all major entities

    Security-First Approach: Comprehensive protection against common web vulnerabilities

    Responsive Design: Mobile-first approach ensuring accessibility across all devices

    Database Optimization: Efficient query design and proper indexing

    Modular Architecture: Organized codebase with separation of concerns

🔒 Security Features

    Password hashing using password_hash()

    Prepared statements for all database queries

    Input validation and sanitization

    XSS prevention through output escaping

    Session management and authentication

    File upload security restrictions

📞 Contact & Links

    Portfolio: https://charlescoolportfolio.onrender.com/

    Email: chimecharles23@gmail.com
