<?php

namespace App\Helpers;

use App\Models\Log;

final class LogHelper
{
    static public function add($action = null, $component = null, $object_id = null, $note = null)
    {
        try {
            $log = new Log([
                'action' => $action ?? 'Not defined',
                'method' => request()->method(),
                'component' => $component ?? 'default',
                'object_id' => $object_id,
                'note' => $note,
                'target' => auth()->check() ? 'user' : 'system',
                'target_id' => auth()->check() ? auth()->user()->id : null,
                'agent' => request()->userAgent(),
                'url' => request()->fullUrl(),
                'ip' => request()->getClientIp()
            ]);

            $log->save();

            return true;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}