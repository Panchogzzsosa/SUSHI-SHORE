<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuración de la API de OpenTable
define('OPENTABLE_API_KEY', 'TU_API_KEY_AQUI');
define('OPENTABLE_RESTAURANT_ID', 'TU_RESTAURANT_ID_AQUI');
define('OPENTABLE_API_URL', 'https://platform.opentable.com/api/v2/');

// Función para hacer peticiones a la API de OpenTable
function callOpenTableAPI($endpoint, $method = 'GET', $data = null) {
    $curl = curl_init();
    
    $url = OPENTABLE_API_URL . $endpoint;
    $headers = [
        'Authorization: Bearer ' . OPENTABLE_API_KEY,
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $headers,
    ]);
    
    if ($data && ($method === 'POST' || $method === 'PUT')) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        return ['error' => $err];
    }
    
    return json_decode($response, true);
}

// Función para verificar disponibilidad
function checkAvailability($date, $time, $partySize) {
    $endpoint = 'restaurants/' . OPENTABLE_RESTAURANT_ID . '/availability';
    $params = [
        'date' => $date,
        'time' => $time,
        'party_size' => $partySize
    ];
    
    return callOpenTableAPI($endpoint . '?' . http_build_query($params));
}

// Función para crear una reserva
function createReservation($data) {
    $endpoint = 'restaurants/' . OPENTABLE_RESTAURANT_ID . '/reservations';
    return callOpenTableAPI($endpoint, 'POST', $data);
}

// Función para cancelar una reserva
function cancelReservation($reservationId) {
    $endpoint = 'restaurants/' . OPENTABLE_RESTAURANT_ID . '/reservations/' . $reservationId;
    return callOpenTableAPI($endpoint, 'DELETE');
} 