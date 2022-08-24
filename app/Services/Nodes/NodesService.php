<?php

namespace App\Services\Nodes;

use App\Filters\Tickets\TicketsFilter;
use App\Services\Nodes\NodesApiFaker;
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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

class NodesService
{

    public function __construct()
    {

    }


    /**
     * @param Request $request
     * @return array|string[]
     */
    public function getNodes(Request $request): array
    {

        if (env('APP_ENV') == 'production') {
            $response = Http::get("http://127.0.0.1:8301/gw/v1/nodes");

            if ($response->status() === 200) {
                $response_body = json_decode($response->body());
                $nodes = $response_body->nodes;

                return ['nodes' => $nodes];
            } else {
                return ['error' => 'Api connection error'];
            }
        } else {
            return ['nodes' => NodesApiFaker::getFakeApiResponse()->nodes];
        }


        // TODO сделать пагинацию
        /*$per_page = $request->input('per_page', config('app.default_per_page'));
        $tickets = Paginator::paginate($tickets, $per_page);

        return ['total' => $tickets->count(), 'tickets' => $tickets];*/
    }


    /**
     * @param int $id
     * @return array|bool[]
     */
    public function getNode(int $id): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getNodesCountries(): array
    {
        $response = Http::get("http://country.io/names.json");

        if ($response->status() === 200) {
            $countries = json_decode($response->body());
            $countries_as_array = (array) $countries;
            asort($countries_as_array);

            return ['countries' => $countries_as_array];
        } else {
            return ['error' => 'Api connection error'];
        }
    }

    /**
     * @param array $data
     * @return array|bool[]
     */
    public function storeNodeData(array $data): array
    {
        if (env('APP_ENV') != 'production') {
            return [
                'success' => true
            ];
        }

        $headers = [];
        $payload = $data;

        $headers['Content-Type'] = 'application/json';
        $response = Http::withHeaders($headers)
            ->post("http://127.0.0.1:8301/gw/v1/nodes", $payload);

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

    /**
     * @param array $data
     * @return array|bool[]
     */
    public function updateNodeData(array $data): array
    {
        if (env('APP_ENV') != 'production') {
            return [
                'success' => true
            ];
        }

        $headers = [];
        $payload = $data;
        $node_id = $data['id'];

        $headers['Content-Type'] = 'application/json';
        $response = Http::withHeaders($headers)
            ->patch("http://127.0.0.1:8301/gw/v1/nodes/{$node_id}", $payload);

        /*$data = json_encode($payload);
        $url = "http://127.0.0.1:8301/gw/v1/nodes/{$node_id}";
        //$url = "http://httpbin.org/anything/{$node_id}";
        $headers = array('Content-Type: application/json');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($curl);
        curl_close($curl);
        dd($data, curl_error($curl), curl_getinfo($curl), $curl, $response);
        */

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

}
