<?php

namespace App;

enum ProductAbilities: string
{
    case PRODUCT_CREATE = 'product:create';
    case PRODUCT_UPDATE = 'product:update';
    case PRODUCT_UPDATE_ANY = 'product:update-any';
    case PRODUCT_DELETE = 'product:delete';
    case PRODUCT_DELETE_ANY = 'product:delete-any';
    case PRODUCT_CREATE_DENY = 'product:create:deny';
    case PRODUCT_UPDATE_DENY = 'product:update:deny';
    case PRODUCT_DELETE_DENY = 'product:delete:deny';
    case PRODUCT_UPDATE_ANY_DENY = 'product:update-any:deny';
    case PRODUCT_DELETE_ANY_DENY = 'product:delete-any:deny';
}
