<?php
use GuzzleHttp\Client;
require_once('./vendor/autoload.php');

class DbAuth {

    public static function  sendRequest($data,$uri,$typeOfRequest){

        $client = new Client(['verify'=>false]);
        
        $url = 'https://apierp.lgx-creation.fr/'.$uri;
        
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