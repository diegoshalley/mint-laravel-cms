<?php

namespace App\Enums;

enum PublishingStatus: string
{
    case Draft = 'draft';
    case InReview = 'in_review';
    case Approved = 'approved';
    case Scheduled = 'scheduled';
    case Published = 'published';
    case Archived = 'archived';
}

