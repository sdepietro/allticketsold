<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Decidir\Connector;
use Log;

class PaymentController extends Controller
{

    public function healthCheck()
    {


        //return response()->json($status);
    }
	public function token(Request $request)
		{
			// Validación de los datos de entrada
			$request->validate([
				'card_number' => 'required|string',
				'card_expiration_month' => 'required|string',
				'card_expiration_year' => 'required|string',
				'card_holder_name' => 'required|string',
				'card_holder_birthday' => 'required|string',
				'card_holder_door_number' => 'required|integer',
				'security_code' => 'required|string',
			]);

			$keys_data = [
				'public_key' => env('DECIDIR_PUBLIC_KEY'),
				'secret_key' => env('DECIDIR_SECRET_KEY'),
			];
			$ambient = env('DECIDIR_AMBIENT');

			//$connector = new \Decidir\Connector($keys_data, $ambient, "", "", "SDK-PHP");
			/*$data = $request->only([
				'card_number',
				'card_expiration_month',
				'card_expiration_year',
				'card_holder_name',
				'card_holder_birthday',
				'card_holder_door_number',
				'security_code'
			]);

			$data['card_holder_identification'] = [
				'type' => 'dni',
				'number' => $request->input('card_holder_identification_number', 'default_number')
			];*/

			$connector = new \Decidir\Connector($keys_data, $ambient, "", "", "SDK-PHP");

			if (is_null($connector)) {
				Log::error('El conector no se ha inicializado correctamente.');
				//return response()->json(['error' => 'Error al inicializar el conector.'], 500);
			} else {
				Log::info('Conector inicializado correctamente:', (array)$connector); // Asegúrate de que esto no cause un error
				//return response()->json((array)$connector);
			}
			$response = $connector->healthcheck()->getStatus();
			return response()->json((array)$response);

			$data = array(
			  "card_number" => "4507990000004905",
			  "card_expiration_month" => "12",
			  "card_expiration_year" => "30",
			  "card_holder_name" => "Barb",
			  "card_holder_birthday" => "24071990",
			  "card_holder_door_number" => 505,
			  "security_code" => "123",
			  "card_holder_identification" => array(
				  "type" => "dni",
				  "number" => "29123456"));

			//$response = $connector->token()->token($data);

			//return response()->json((array)$response);

			/*$respuesta = new TokenResponse();

			$respuesta->setId($response->get('id',null));
			$respuesta->setStatus($response->get('status',null));
			$respuesta->setCardNumberLength($response->get('card_number_length', null));
			$respuesta->setDateCreated($response->get('date_created', null));
			$respuesta->setBin($response->get('bin', null));
			$respuesta->setLastFourDigits($response->get('last_four_digits', null));
			$respuesta->setSecurityCodeLength($response->get('security_code_length', null));
			$respuesta->setExpirationMonth($response->get('expiration_month', null));
			$respuesta->setExpirationYear($response->get('expiration_year', null));
			$respuesta->setDateDue($response->get('date_due', null));
			$cardHolder = $response->get('cardholder', null);
			$respuesta->setType($cardHolder['identification']['type']);
			$respuesta->setNumber($cardHolder['identification']['number']);
			$respuesta->setName($cardHolder['name']);*/

			//return response()->json((array)$respuesta);

			//$response = $connector->token()->token($data);
			//return response()->json($connector);
			// Imprime la respuesta completa
			//Log::info('Respuesta completa:', (array)$response);

			// Accede a los datos
			//$arrayResponse = (array)$response; // Convertimos la respuesta a array

			// Crear el objeto TokenResponse
			//$respuesta = new TokenResponse($arrayResponse);

			//return response()->json($arrayResponse);

			/*// Configura los datos de la respuesta
			$respuesta->setId($arrayResponse['id'] ?? null);
			$respuesta->setStatus($arrayResponse['status'] ?? null);
			$respuesta->setCardNumberLength($arrayResponse['card_number_length'] ?? null);
			$respuesta->setDateCreated($arrayResponse['date_created'] ?? null);
			$respuesta->setBin($arrayResponse['bin'] ?? null);
			$respuesta->setLastFourDigits($arrayResponse['last_four_digits'] ?? null);
			$respuesta->setSecurityCodeLength($arrayResponse['security_code_length'] ?? null);
			$respuesta->setExpirationMonth($arrayResponse['expiration_month'] ?? null);
			$respuesta->setExpirationYear($arrayResponse['expiration_year'] ?? null);
			$respuesta->setDateDue($arrayResponse['date_due'] ?? null);

			// Manejo del cardholder
			$cardHolder = $arrayResponse['cardholder'] ?? null;
			if ($cardHolder) {
				$respuesta->setType($cardHolder['identification']['type'] ?? null);
				$respuesta->setNumber($cardHolder['identification']['number'] ?? null);
				$respuesta->setName($cardHolder['name'] ?? null);
			}

			// Retorna la respuesta en formato JSON
			return response()->json($respuesta);*/
		}

public function procesarpagoAAA(Request $request) {
    // Obtener datos JSON
    $input = $request->json()->all();
	//$ip_address = $request->ip();

    // Asigna tus datos de claves aquí
   $keys_data = [
				'form_apikey' => "606af257fd924c5aa4c063cc6151bc03",
				'form_site' => "92210260",
			];
	$ambient = "prod";

    $connector = new Connector($keys_data, $ambient);
	$response = $connector->healthcheck()->getStatus();
	//return response()->json((array)$response);

    // Preparar los datos para la API de Decidir
    $data = array(
        "site" => array(
			"transaction_id" => "ALLTICKETS",
            "template" => array(
				"id" => 20817,
			)
        ),
		"customer" => array(
			"id" => "1208361527",
			"email" => "rogelioborda@hotmail.com"
			),
		"payment" => array(
					"amount" => 500,
					"currency" => "ARS",
					"payment_method_id" => 1,
					"installments" => 1,
					"payment_type" => "single"
			  ),
		"success_url" => "https://allticketdev.bigresources.com.ar/misCompras",
		"cancel_url" => "https://allticketdev.bigresources.com.ar/iniciar-sesion",
		"redirect_url" => "https://allticketdev.bigresources.com.ar/iniciar-sesion",
		"fraud_detection" => array( "send_to_cs" => false )
    );


    try {
        $response = $connector->payment()->Validate($data);

        return response()->json((array)$response);

    } catch (\Decidir\Exceptions\DecidirException $e) {
        // Capturar la excepción específica de Decidir
        return response()->json([
            'status3' => 'error',
            'message' => $e->getMessage(),
            'data' => $e->getData() ?? [] // Solo si getData() está disponible
        ]);
    }
}

public function procesarpago(Request $request) {
    // Obtener datos JSON
    $input = $request->json()->all();
	$ip_address = $input['customer']['ip_address'];

    // Asigna tus datos de claves aquí
   $keys_data = [
				'public_key' => env('DECIDIR_PUBLIC_KEY'),
				'private_key' => env('DECIDIR_SECRET_KEY'),
			];
	$ambient = env('DECIDIR_AMBIENT');

    $connector = new Connector($keys_data, $ambient);

	$total = $input['amount'] * 100;

	$installments = isset($input['installments']) && is_numeric($input['installments']) ? (int) $input['installments'] : 1;

    // Preparar los datos para la API de Decidir
    $data = array(

        "site_transaction_id" => $input['site_transaction_id'],
        "token" => $input['token'],
        "customer" => array(
            "id" => $input['customer']['id'],
            "email" => $input['customer']['email'],
            "ip_address" => $input['customer']['ip_address']
        ),
        "payment_method_id" => $input['payment_method_id'],
        "bin" => $input['bin'],
        "amount" => $total,
        "currency" => "ARS",
        "installments" => $installments,
        "description" => "Pago PCI",
        "establishment_name" => "ALLTICKETS",
        "payment_type" => "single",
		"fraud_detection" => array( "send_to_cs" => false ),
        "sub_payments" => array()
    );

	$data2 = array();

    try {
        $response = $connector->payment()->ExecutePayment($data);
        echo "<pre>"; print_r($response); die();
		$response2 = $connector->payment()->PaymentInfo($data2, $response->getId());

        // Devolver una respuesta adecuada al cliente
         return response()->json([
            'status' => $response->getStatus(),
            'transaction_id' => $response->getId(),
			'token' => $response->getToken(),
			'ip_address' => $ip_address,
            'status_details' => $response->getStatus_details()
        ]);

    } catch (\Decidir\Exceptions\DecidirException $e) {
        // Capturar la excepción específica de Decidir
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'data' => $e->getData() ?? [] // Solo si getData() está disponible
        ]);
    } catch (\Exception $e) {
        // Capturar cualquier otra excepción
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
}
}
