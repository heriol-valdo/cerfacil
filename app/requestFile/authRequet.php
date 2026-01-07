<?php
require __DIR__ . '/../vendor/autoload.php';
use GuzzleHttp\Client;

function sendRequest($data, $head, $uri, $typeOfRequest)
{

    $client = new Client(['verify' => false]);

    $url = 'https://cerfa.heriolvaldo.com/api/' . $uri;

    try {
        $response = $client->$typeOfRequest($url, [
            'form_params' => $data,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'token' => $head,
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


function sendRequests($data, $head, $uri, $typeOfRequest)
{

    $client = new Client(['verify' => false]);

    $url = 'https://cerfa.heriolvaldo.com/api/' . $uri;

    try {
        $response = $client->$typeOfRequest($url, [
            'multipart' => $data,
            'headers' => [
                'token' => $head
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

function request($type, $uri, $data)
{
    $result = sendRequest($data, $_SESSION['userToken'], $uri, $type);
    $result = json_decode($result);
    $erreur = "";

    if(isset($result->erreur)){
        $erreur = $result->erreur;
        return $erreur;
    }

    return $result;
}