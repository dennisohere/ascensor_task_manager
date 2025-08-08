# Taskly - Task Management System

A modern, full-stack task management application built with Vue.js 3 (TypeScript) frontend and Laravel 12 backend. Taskly provides a clean, intuitive interface for managing personal tasks with features like categorization, due dates, completion tracking, and user authentication.

## 🚀 Features

### Core Functionality

- **Task Management**: Create, read, update, and delete tasks
- **Task Categorization**: Organize tasks by categories
- **Due Date Tracking**: Set and manage task deadlines
- **Completion Status**: Mark tasks as completed/pending
- **User Authentication**: Secure login and registration system
- **Real-time Filtering**: Filter tasks by category and status
- **Responsive Design**: Works seamlessly on desktop and mobile devices

### Technical Features

- **Modern UI/UX**: Clean, intuitive interface built with Tailwind CSS
- **Type Safety**: Full TypeScript support for better development experience
- **State Management**: Pinia stores for efficient state management
- **API Integration**: RESTful API with proper error handling
- **Authentication**: JWT-based authentication with Laravel Sanctum
- **Real-time Updates**: Instant UI updates without page refreshes

## 🏗️ Architecture

### Frontend (Vue.js 3 + TypeScript)

- **Framework**: Vue.js 3 with Composition API
- **Language**: TypeScript for type safety
- **State Management**: Pinia
- **Routing**: Vue Router 4
- **Styling**: Tailwind CSS
- **HTTP Client**: Axios
- **Build Tool**: Vite

### Backend (Laravel 12)

- **Framework**: Laravel 12
- **Language**: PHP 8.2+
- **Authentication**: Laravel Sanctum
- **Database**: MySQL/PostgreSQL/SQLite
- **API**: RESTful API with JSON responses
- **Validation**: Laravel Form Requests
- **Testing**: Pest PHP

## 📁 Project Structure

```
ascensor_taskly/
├── backend/                 # Laravel backend application
│   ├── app/
│   │   ├── Dtos/           # Data Transfer Objects
│   │   ├── Events/         # Event classes
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   ├── Api/    # API controllers
│   │   │   │   └── Auth/   # Authentication controllers
│   │   │   ├── Middleware/ # Custom middleware
│   │   │   ├── Requests/   # Form requests
│   │   │   └── Resources/  # API resources
│   │   ├── Models/         # Eloquent models
│   │   ├── Policies/       # Authorization policies
│   │   ├── Services/       # Business logic services
│   │   └── Providers/      # Service providers
│   ├── database/
│   │   ├── migrations/     # Database migrations
│   │   └── seeders/        # Database seeders
│   ├── routes/
│   │   └── api.php         # API routes
│   └── tests/              # PHPUnit tests
├── frontend/               # Vue.js frontend application
│   ├── src/
│   │   ├── components/     # Vue components
│   │   ├── composables/    # Vue composables
│   │   ├── router/         # Vue Router configuration
│   │   ├── stores/         # Pinia stores
│   │   ├── utils/          # Utility functions and types
│   │   └── views/          # Page components
│   ├── public/             # Static assets
│   └── package.json        # Frontend dependencies
└── README.md              # This file
```

## 🛠️ Installation & Setup

### Prerequisites

- **Node.js**: ^20.19.0 or >=22.12.0
- **PHP**: ^8.2
- **Composer**: Latest version
- **Database**: MySQL, PostgreSQL, or SQLite

### Backend Setup

1. **Clone the repository**

   ```bash
   git clone <repository-url>
   cd ascensor_taskly/backend
   ```

2. **Install PHP dependencies**

   ```bash
   composer install
   ```

3. **Environment configuration**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**

   ```bash
   # Configure your database in .env file
   php artisan migrate
   php artisan db:seed
   ```

5. **Start the development server**
   ```bash
   php artisan serve
   ```

### Frontend Setup

1. **Navigate to frontend directory**

   ```bash
   cd ../frontend
   ```

2. **Install Node.js dependencies**

   ```bash
   npm install
   ```

3. **Environment configuration**

   ```bash
   # Create .env file if needed
   # Configure API base URL
   ```

4. **Start the development server**
   ```bash
   npm run dev
   ```

## 🔧 Configuration

### Backend Configuration

1. **Database Configuration** (`.env`)

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=taskly
   DB_USERNAME=root
   DB_PASSWORD=
   ```

2. **API Configuration**
   - API routes are prefixed with `/api`
   - Authentication uses Laravel Sanctum
   - CORS is configured for frontend integration

### Frontend Configuration

1. **API Base URL** (`.env`)

   ```env
   VITE_API_BASE_URL=http://localhost:8000
   ```

2. **Development Server**
   - Default port: `5173`
   - Hot module replacement enabled
   - TypeScript compilation on save

## 📚 API Documentation

### Authentication Endpoints

#### Register User

```http
POST /api/auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```

#### Login

```http
POST /api/auth/token/create
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password"
}
```

#### Get Current User

```http
GET /api/users/me
Authorization: Bearer {token}
```

### Task Endpoints

#### Get All Tasks

```http
GET /api/me/tasks
Authorization: Bearer {token}
```

#### Create Task

```http
POST /api/me/tasks
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Complete project",
  "description": "Finish the task management system",
  "due_date": "2024-01-15T10:00:00Z",
  "category_name": "Work"
}
```

#### Update Task

```http
PUT /api/me/tasks/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Updated task name",
  "description": "Updated description",
  "completed": true,
  "category_name": "Personal"
}
```

#### Delete Task

```http
DELETE /api/me/tasks/{id}
Authorization: Bearer {token}
```

### Category Endpoints

#### Get All Categories

```http
GET /api/categories
Authorization: Bearer {token}
```



## 🔐 Security

### Authentication

- Token-based authentication with Laravel Sanctum
- Secure password hashing


### Authorization

- User-specific task access
- Policy-based authorization
- Route-level protection


