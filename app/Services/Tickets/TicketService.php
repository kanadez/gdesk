<?php

namespace App\Services\Tickets;

use App\Filters\Tickets\TicketsFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Exceptions\Auth\AuthException;
use App\Models\User;
use App\Models\TicketTg;
use App\Models\TicketClosed;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Filters\Users\UsersFilter;
use phpDocumentor\Reflection\Types\Object_;
use Illuminate\Support\Facades\Http;
use App\Services\Paginator;
use App\Services\Tickets\TicketsApiFaker;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TicketService
{


    public function __construct()
    {

    }


    /**
     * @param Request $request
     * @return array
     */
    public function getTickets(Request $request): array
    {
        // TODO в критерии
        $filter = TicketsFilter::filter($request);
        $tickets = DB::select("
            SELECT
                (SELECT MAX(i.id)
                        FROM brovpn_v2.tickets_tg i
                        WHERE i.chat_id = t.chat_id AND i.is_response = 0
                        GROUP BY i.chat_id) AS last_user_ticket_id,
                t.chat_id,
                t.text AS first_message,
                u.user_name,
                u.first_name,
                u.last_name,
                u.lang,
                u.state,
                u.block_bot_at,
                tc.last_ticket_id as tc_last_ticket_id,
                (SELECT MAX(i.created_at)
                    FROM brovpn_v2.tickets_tg i
                    WHERE i.chat_id = t.chat_id
                    GROUP BY i.chat_id) AS last_message_created_at,
                (SELECT i.is_response
                    FROM brovpn_v2.tickets_tg i
                    WHERE i.chat_id = t.chat_id
                    ORDER BY created_at DESC LIMIT 1) AS last_message_is_response,
                (SELECT ut.tariff_id
                    FROM brovpn_manager.users_tariffs ut
                    WHERE ut.user_type = 'telegram'
                      AND ut.user_id = t.from_id
                    ORDER BY ut.until_at DESC
                    LIMIT 1) as tariff_name,
                (SELECT DATE_FORMAT(ut.until_at, '%Y-%m-%d')
                    FROM brovpn_manager.users_tariffs ut
                    WHERE ut.user_type = 'telegram'
                      AND ut.user_id = t.from_id
                    ORDER BY ut.until_at DESC
                    LIMIT 1) as tariff_until_at,
                (SELECT ut.is_ended
                    FROM brovpn_manager.users_tariffs ut
                    WHERE ut.user_type = 'telegram'
                      AND ut.user_id = t.from_id
                    ORDER BY ut.until_at DESC
                    LIMIT 1) as tariff_is_ended
            FROM brovpn_v2.tickets_tg t
            LEFT JOIN brovpn_v2.users_tg u ON t.from_id = u.id
            LEFT JOIN brovpn_v2.tickets_closed tc ON t.chat_id = tc.chat_id
            WHERE t.id IN (
                SELECT MIN(id)
                FROM brovpn_v2.tickets_tg
                GROUP BY chat_id)
            AND (tc.last_ticket_id IS NULL
                OR (SELECT MAX(i.id)
                    FROM brovpn_v2.tickets_tg i
                    WHERE i.chat_id = t.chat_id AND i.is_response = 0
                    GROUP BY i.chat_id) != tc.last_ticket_id)

            {$filter}

            ORDER BY last_user_ticket_id DESC");

        $per_page = $request->input('per_page', config('app.default_per_page'));
        $tickets = Paginator::paginate($tickets, $per_page);

        return ['total' => $tickets->count(), 'tickets' => $tickets];
    }

    public function getTariffs()
    {
        $foo = DB::connection('mysql2')->select('SELECT * FROM users_tariffs');
        dd($foo);
    }


    /**
     * @param int $id
     * @return array[]
     */
    public function getTicket(int $id): array
    {
        // TODO в критерии
        $ticket_data_array = DB::select('
            SELECT
                (SELECT MAX(i.id)
                        FROM tickets_tg i
                        WHERE i.chat_id = t.chat_id AND i.is_response = 0
                        GROUP BY i.chat_id) AS last_user_ticket_id,
                t.chat_id,
                t.text AS first_message,
                u.user_name,
                u.first_name,
                u.last_name,
                u.block_bot_at,
                (SELECT MAX(i.created_at)
                    FROM tickets_tg i
                    WHERE i.chat_id = t.chat_id
                    GROUP BY i.chat_id) AS last_message_created_at,
                (SELECT i.is_response
                    FROM tickets_tg i
                    WHERE i.chat_id = t.chat_id
                    ORDER BY created_at DESC LIMIT 1) AS last_message_is_response
            FROM tickets_tg t
            LEFT JOIN users_tg u ON t.from_id = u.id
            WHERE t.id = ?', [$id]);
        $ticket_data = reset($ticket_data_array);
        $ticket = TicketTg::find($id);

        if (env('APP_ENV') == 'production') {
            $response = Http::get("http://127.0.0.1:8303/gw/v1/telegram/support/{$ticket->chat_id}");

            if ($response->status() === 200) {
                $response_body = json_decode($response->body());
                $messages = $response_body->messages;

                return ['data' => $ticket_data, 'messages' => $messages];
            } else {
                return ['error' => 'Api connection error'];
            }
        } else {
            return ['data' => $ticket_data, 'messages' => TicketsApiFaker::getFakeApiResponse()->messages];
        }


    }

    /**
     * @param int $ticket_id
     * @param string $message
     * @param UploadedFile|null $image
     * @return array[]
     */
    public function sendReply(int $ticket_id, string $message = null, UploadedFile $image = null): array
    {
        if (env('APP_ENV') != 'production') {
            return [
                'success' => true
            ];
        }

        $headers = [];
        $payload = [];
        $payload['text'] = $message ?? '';

        if (empty($image)) {
            $headers['Content-Type'] = 'application/json';
            $response = Http::withHeaders($headers)
                ->post("http://127.0.0.1:8303/gw/v1/telegram/support/ticket/{$ticket_id}", $payload);
        } else {
            $headers['Content-Type'] = 'multipart/form-data';
            $response = Http::withHeaders($headers)
                ->attach('media', file_get_contents($image->getRealPath()), "{$image->getFilename()}.png")
                ->post("http://127.0.0.1:8303/gw/v1/telegram/support/ticket/{$ticket_id}", $payload);
        }

        if ($response->status() === 200) {
            return [
                'success' => true
            ];
        } else {
            return [
                'success' => false,
                'error' => [
                    'code' => $response->status(),
                    'message' => json_decode($response->body())
                ]
            ];
        }

    }

    public function closeTicket(int $id): bool
    {
        // TODO в критерии
        $ticket_data_array = DB::select('
            SELECT
                (SELECT MAX(i.id)
                        FROM tickets_tg i
                        WHERE i.chat_id = t.chat_id AND i.is_response = 0
                        GROUP BY i.chat_id) AS last_user_ticket_id,
                t.chat_id,
                t.text AS first_message,
                u.user_name,
                u.first_name,
                u.last_name,
                u.block_bot_at,
                (SELECT MAX(i.created_at)
                    FROM tickets_tg i
                    WHERE i.chat_id = t.chat_id
                    GROUP BY i.chat_id) AS last_message_created_at,
                (SELECT i.is_response
                    FROM tickets_tg i
                    WHERE i.chat_id = t.chat_id
                    ORDER BY created_at DESC LIMIT 1) AS last_message_is_response
            FROM tickets_tg t
            LEFT JOIN users_tg u ON t.from_id = u.id
            WHERE t.id = ?', [$id]);
        $ticket_data = reset($ticket_data_array);

        $existing_closed_ticket = TicketClosed::where('chat_id', $ticket_data->chat_id)->first();

        // TODO это тоже можно в отдельный класс, создание закрытого тикета. Уточнить в архитектуре.
        if (empty($existing_closed_ticket)) {
            $new_closed_ticket = new TicketClosed();
            $new_closed_ticket->chat_id = $ticket_data->chat_id;
            $new_closed_ticket->last_ticket_id = $ticket_data->last_user_ticket_id;
            return $new_closed_ticket->save();
        } else {
            $existing_closed_ticket->last_ticket_id = $ticket_data->last_user_ticket_id;
            return $existing_closed_ticket->save();
        }
    }

}
