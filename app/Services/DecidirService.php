<?php
namespace App\Services;

use Decidir\Connector;

class DecidirService
{
    protected $connector;

    public function __construct()
    {
        $keysData = [
            'public_key' => env('DECIDIR_PUBLIC_KEY'),
            'secret_key' => env('DECIDIR_SECRET_KEY'),
        ];
        $ambient = env('DECIDIR_AMBIENT');

        $this->connector = new Connector($keysData, $ambient);
    }

    public function healthCheck()
    {
        try {
            $response = $this->connector->healthcheck()->getStatus();
            return [
                'name' => $response->getName(),
                'version' => $response->getVersion(),
                'build_time' => $response->getBuildTime(),
            ];
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al conectar con la API'], 500);
        }
    }
}