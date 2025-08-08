
export interface AuthResponse {
    access_token: string;
    user:         User;
}

export interface RegisterRequest {
    email:                 string;
    password:              string;
    password_confirmation: string;
    name:                  string;
}

export interface User {
    id:         string;
    name:       string;
    email:      string;
    created_at: Date;
    updated_at: Date;
}

export interface Task {
    id:          string;
    name:        string;
    description: string;
    completed:   boolean;
    due_date:    Date;
    user:        User;
    category:    Category;
    created_at:  Date;
    updated_at:  Date;
}

export interface Category {
    id:         string;
    name:       string;
    created_at: Date;
    updated_at: Date;
    email?:     string;
}

export interface TaskData {
    name:              string;
    description:       string;
    due_date:          ?string;
    completed:         boolean;
    category_name: string;
}