# Maxim Sea Food - Food Delivery Backend

> Production-ready food delivery platform backend with WordPress integration and custom APIs for mobile applications.

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![WordPress](https://img.shields.io/badge/WordPress-Integration-blue.svg)](https://wordpress.org)

## 📋 About

Backend system for Maxim Sea Food mobile application, providing RESTful APIs for menu management, order processing, and delivery tracking. The system integrates with an existing WordPress website while serving native mobile applications.

**Project Type:** Food Delivery Backend API  
**Industry:** Food & Beverage  
**Platform:** iOS Mobile Application  
**Role:** Backend Developer

**App Store:** [Download on App Store](https://apps.apple.com/id/app/maxim-sea-food/id6754839815)

## ✨ Features

### Core Features
- 📱 Production-ready RESTful APIs for mobile app
- 🍽️ Menu management (categories, items, pricing, availability)
- 📦 Complete order processing workflow
- 🚚 Delivery tracking and management
- 🔔 Status notifications throughout order lifecycle

### WordPress Integration
- 🔄 Custom Laravel backend integrated with existing WordPress website
- 🌐 Seamless data synchronization between platforms
- 📝 Custom API endpoints for mobile app consumption

### Admin Dashboard
- 📊 Real-time order monitoring using DataTables library
- 📈 Comprehensive reporting and analytics
- 🎯 Order management interface

### Security & Performance
- 🛡️ Input validation and request sanitization
- ⚡ Rate limiting for API protection
- 🔒 JWT authentication and hardened access controls
- 🚀 Optimized database queries for high-volume concurrent orders during peak hours

## 🛠️ Tech Stack

- **Framework:** Laravel
- **Language:** PHP
- **Database:** MySQL
- **CMS Integration:** WordPress
- **Admin UI:** DataTables
- **Authentication:** JWT
- **Mobile Platform:** iOS (Native App)

## 🚀 Installation

### Prerequisites
- PHP >= 8.1
- Composer
- MySQL
- WordPress installation

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/ahmedesmaelgamal/maxim-seafood-api.git
   cd maxim-seafood-api
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_DATABASE=maxim_seafood
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run migrations**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Start server**
   ```bash
   php artisan serve
   ```

## 📚 API Documentation

### Authentication
```http
POST /api/auth/login
POST /api/auth/register
GET  /api/auth/profile
```

### Menu Management
```http
GET    /api/menu/categories
GET    /api/menu/items
GET    /api/menu/items/{id}
POST   /api/admin/menu/items
PUT    /api/admin/menu/items/{id}
DELETE /api/admin/menu/items/{id}
```

### Order Processing
```http
POST /api/orders
GET  /api/orders
GET  /api/orders/{id}
PUT  /api/orders/{id}/status
```

### Delivery Management
```http
GET  /api/deliveries
POST /api/deliveries/{id}/assign
PUT  /api/deliveries/{id}/complete
```

## 📝 License

Proprietary project - Portfolio demonstration purposes only.

## 👤 Contact

**Ahmed Ismail Gamal**

- 📧 ahmedesmaelgamal@gmail.com
- 💼 [LinkedIn](https://linkedin.com/in/ahmed-esmael-gamal-9b4179204)
- 🐙 [@ahmedesmaelgamal](https://github.com/ahmedesmaelgamal)
