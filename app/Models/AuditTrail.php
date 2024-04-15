<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    use HasFactory;

    const USER_SYSTEM     = NULL;
    const USER_ADMIN      = Users::TYPE_ADMIN;  
    const USER_ASSISTANTLIBRARIAN  = Users::TYPE_ASSISTANTLIBRARIAN;    
    const USER_LIBRARIAN = Users::TYPE_LIBRARIAN;
    const USER_STUDENT    = Users::TYPE_STUDENT;

    const MODULE_ACCOUNT     = 0;
    const MODULE_THESIS      = 1;
    const MODULE_AUTHORS     = 2;
    const MODULE_KEYWORDS    = 3;
    const MODULE_CATEGORY    = 4;
    const MODULE_DATABASE    = 5;


    const ACTION_CREATE = 0;
    const ACTION_UPDATE = 1;
    const ACTION_DELETE = 2;
    const ACTION_ENABLE = 3;
    const ACTION_DISABLE = 4;
}
