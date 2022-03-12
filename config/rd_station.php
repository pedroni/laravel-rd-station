<?php
// config for Pedroni/RdStation

return [
    'base_url' => env('RD_STATION_BASE_URL', 'https://api.rd.services'),
    'client_id' => env('RD_STATION_CLIENT_ID'),
    'client_secret' => env('RD_STATION_CLIENT_SECRET'),
    'redirect_path' => env('RD_STATION_REDIRECT_PATH', 'rd-station/oauth/callback'),
];
