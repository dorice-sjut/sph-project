# AgroSphere - Modern Agriculture Platform

## Overview
AgroSphere is a comprehensive agriculture platform connecting farmers, buyers, suppliers, experts, and logistics providers across Africa.

## Features Built

### 1. Foundation
- **Design System**: Dark mode + green accent theme using Tailwind CSS v4
- **Responsive Layouts**: Mobile-first design with sidebar navigation
- **Reusable Components**: Cards, buttons, forms, navigation patterns

### 2. Authentication & Roles
- **Multi-role system**: farmer, buyer, supplier, expert, logistics, admin
- **Registration with role selection**
- **Role-based route middleware**
- **Role-specific dashboards**

### 3. Core Features
- **Landing Page**: Video background, features showcase, CTA sections
- **Marketplace**: Product browsing, search, filtering
- **Product Management**: CRUD for farmers (create, list, edit, delete)
- **Orders System**: Buyers can place orders, farmers can manage them
- **Market Prices**: Real-time price tracking with trends (Tanzania, Africa, Global)
- **Messaging**: Direct chat between users
- **User Profiles**: Avatar upload, bio, location, contact info

### 4. Dashboards by Role
- **Farmer**: Products, orders, market prices, messages
- **Buyer**: Marketplace, orders, recommendations
- **Supplier**: Inventory, orders tracking
- **Expert**: Consultations, knowledge base
- **Logistics**: Deliveries, route optimization
- **Admin**: Users, products, orders, market data, system settings

## File Structure
```
app/
  Http/
    Controllers/
      Auth/ (LoginController, RegisterController)
      DashboardController.php
      ProductController.php
      MarketplaceController.php
      OrderController.php
      MessageController.php
      MarketPriceController.php
      ProfileController.php
      AdminController.php
    Middleware/CheckRole.php
  Models/
    User.php (with role helpers and relationships)
    Product.php
    Order.php
    Message.php
    MarketPrice.php

resources/
  views/
    layouts/ (app, dashboard, landing)
    components/ (sidebar, top-nav, landing-nav, footer)
    auth/ (login, register)
    dashboard/ (farmer, buyer, supplier, expert, logistics)
    admin/ (dashboard, users, products, orders, market, settings)
    marketplace/ (index, show)
    products/ (index, create, edit)
    orders/ (farmer, buyer)
    messages/ (index, conversation)
    market_prices/ (index)
    profile/ (show, settings)

routes/web.php (comprehensive route definitions)
```

## Setup Instructions

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Database Setup
```bash
# Create database and update .env with credentials
php artisan migrate
php artisan db:seed
```

### 3. Build Assets
```bash
npm run build
```

### 4. Start Server
```bash
php artisan serve
```

## Demo Credentials
- **Farmer**: farmer@agrosphere.com / password
- **Buyer**: buyer@agrosphere.com / password
- **Supplier**: supplier@agrosphere.com / password
- **Expert**: expert@agrosphere.com / password
- **Logistics**: logistics@agrosphere.com / password
- **Admin**: admin@agrosphere.com / password

## Tech Stack
- **Backend**: Laravel 11
- **Frontend**: Blade, Tailwind CSS v4, Alpine.js
- **Database**: MySQL
- **Icons**: Google Material Symbols
- **Fonts**: Inter

## Key Design Decisions
- Dark theme with green (#10b981) as primary accent
- Card-based layout with consistent spacing
- Mobile-responsive sidebar navigation
- Smooth animations and transitions
- Single-purpose pages (no feature mixing)
