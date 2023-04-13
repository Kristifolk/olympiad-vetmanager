<?php

//namespace App\Class\Task;
//
//use GuzzleHttp\Client;
//use GuzzleHttp\Exception\GuzzleException;
//use GuzzleHttp\Psr7\Request;
//
//class AuthorizationRequest
//{
//    public function __construct(
//        public string $loginUser,
//        public string $passwordUser,
//        public Client $guzzleClient,
//    )
//    {
//    }
//
//    public function checkAuthorization(): bool
//    {
//        if ($this->loginUser == $this->getLoginAuthorizeUser()) {
//            return true;
//        }
//
//        return false;
//    }
//
////    public function getExchangeRates()
////    {
////        if ($this->exchangeRates == $this->getLoginAuthorizeUser()) {
////            return $this->exchangeRates;
////        }
////
////        $response = $this->guzzleClient->get("https://devmel.vetmanager2.ru/login.php");
////
////        $results = json_decode($response->getBody(), true);
////        return $this->exchangeRates = $results['rates'];
////    }
//
//    public function getLoginAuthorizeUser(): string
//
//    {
////        if (isset($this->exchangeRates)) {
////            return $this->exchangeRates;
////        }
//
//        $headers = [
//            'X-REST-API-KEY' => '{{31af0669fd1bcd6d145410795a6ef4f7}}'
//        ];
//        $body = '{"admission_date": "2022-12-07 12:00:00", "user_id": "1", "client_id": "1", "clinic_id": "1", "admission_length": "00:15:00"}';
//        $request = new Request('POST', 'https://devmel.vetmanager2.ru/rest/api/admission/', $headers, $body);
//        $res = $this->guzzleClient->sendAsync($request)->wait();
//        return json_decode($res->getBody(), true);


//        $response = $this->guzzleClient->get("https://devmel.vetmanager2.ru/index.php");
//        //$userLogin = json_decode($response->getBody(), true);
//        $userLogin = "admin";
//        return $userLogin;
//    }
//}