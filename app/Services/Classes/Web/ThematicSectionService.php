<?php

namespace App\Services\Classes\Web;

use App\Services\BaseService;
use App\Models\ThematicSection;

final class ThematicSectionService extends BaseService
{
    static public function getByID(int $id)
    {
        return ThematicSection::where('id', '=', $id)
            ->first();
    }
}
