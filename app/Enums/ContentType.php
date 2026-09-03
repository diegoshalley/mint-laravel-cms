<?php

namespace App\Enums;

enum ContentType: string
{
    case Page = 'page';
    case News = 'news';
    case PressRelease = 'press_release';
    case PublicNotice = 'public_notice';
    case Speech = 'speech';
    case Alert = 'alert';
}

