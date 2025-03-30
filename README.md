# Photo Studio Management System

## Overview

The Photo Studio Management System is a web-based application designed to streamline the operations of a photo studio. It enables administrators to upload and manage images in various formats, including PNG, JPG, and TIFF. These images are intended for use in banners during events such as Ganpati festivals, elections, and other occasions. The system also facilitates secure image downloads for clients, with integrated payment processing powered by Stripe. Built using the Laravel framework, this application ensures a robust and scalable solution for photo studio management.

## Features

- **Admin Panel**: Secure login for administrators to manage the system.
- **Image Upload**: Support for uploading images in PNG, JPG, and TIFF formats.
- **Event Categorization**: Organize images based on events like Ganpati festivals, elections, etc.
- **Image Management**: View, edit, and delete uploaded images.
- **Client Access**: Clients can browse and select images for download.
- **Secure Downloads**: Implemented secure download mechanisms to protect image integrity.
- **Payment Integration**: Stripe integration for processing payments before image downloads.
- **Responsive Design**: Ensures optimal viewing on various devices.

## Technologies Used

- **Backend**: Laravel PHP Framework
- **Frontend**: Blade templating engine, HTML5, CSS3, JavaScript
- **Database**: MySQL
- **Payment Gateway**: Stripe API
- **Version Control**: Git

## Installation

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/yourusername/photo-studio-management.git

2. **Install Dependencies:**:
   ```bash
   composer install

3. **Install Dependencies:**:
   ```bash
   Duplicate the .env.example file and rename it to .env.

4. **Generate Application Key:**:
   ```bash
   php artisan key:generate

5. **Run Migrations:**:
   ```bash
   php artisan migrate

6. **Seed the Database:**:
   ```bash
  php artisan db:seed



