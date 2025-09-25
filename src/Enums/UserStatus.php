<?php

namespace WillyFramework\src\Enums;

enum UserStatus: string {
    case Active = 'active';
    case Inactive = 'inactive';
    case Banned = 'banned';
}
