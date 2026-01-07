<?php

namespace Projet\Model;
use GuzzleHttp\Client;

class Guzzle {

    public static function  sendRequest($data,$uri,$typeOfRequest){

        $client = new Client(['verify'=>false]);
        
        $url = 'https://cerfa.heriolvaldo.com/api/'.$uri;
        
        try {
          $response = $client->$typeOfRequest($url, [
            'form_params' => $data,
            'headers' => [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'token' => isset($_SESSION['userToken'])? $_SESSION['userToken']: ''
            ]
            ]);
            
            $body = $response->getBody()->getContents();
            return $body;
        
        } catch (GuzzleHttp\Exception\ClientException $e) {
            return $e->getResponse()->getBody()->getContents();
        } catch (GuzzleHttp\Exception\ServerException $e) {
            return $e->getResponse()->getBody()->getContents();
        } catch (GuzzleHttp\Exception\RequestException $e) {
            return $e->getMessage();
        }
        
        
        }

}