<?php

namespace App;

enum ProductPermissions: string
{
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
}
