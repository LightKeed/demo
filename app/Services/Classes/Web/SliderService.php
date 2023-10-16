<?php

namespace App\Services\Classes\Web;

use App\Services\BaseService;
use App\Models\Slider;

final class SliderService extends BaseService
{
    public function getAll()
    {
        return Slider::orderBy('created_at', 'desc')
            ->isEnabled()
            ->paginate(30);
    }

    static public function getById(int $id)
    {
        return Slider::select('id', 'enabled')
            ->with(['slides:id,title,text_button,link_button,sort_order,enabled,slider_id,media_id',
                'slides' => function($query) {
                    $query->isEnabled()->orderBy('sort_order');
                }
            ])
            ->isEnabled()
            ->where('id', '=', $id)
            ->isEnabled()
            ->first();
    }
}
