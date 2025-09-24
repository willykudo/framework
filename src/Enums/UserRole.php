<?php 

namespace WillyFramework\src\Enums;

enum UserRole: string {
    case User = 'user';
    case Admin = 'admin';
    case Employee = 'employee'; 
}