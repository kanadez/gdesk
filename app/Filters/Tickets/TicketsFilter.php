<?php

namespace App\Filters\Tickets;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TicketsFilter
{

    public static function filter(Request $request): string
    {

        $request_data = $request->all();
        $filter = '';

        foreach ($request_data as $key => $data) {

            if ($data === null) continue;

            switch ($key) {
                case 'status':
                    $is_response = $data == 'responsed' ? 1 : 0;
                    $filter = "AND
                        (SELECT i.is_response
                            FROM tickets_tg i
                            WHERE i.chat_id = t.chat_id
                            ORDER BY created_at DESC
                            LIMIT 1)
                        = $is_response";
                    break;
            }
        }

        return $filter;
    }

}
