<?php

namespace App\Users\Infrastructure\Repository;

use App\Users\Domain\Entity\User;
use App\Users\Domain\Repository\UserRepositoryInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserRepository implements UserRepositoryInterface
{
    private HttpClientInterface  $gorestClient;

    public function __construct(HttpClientInterface  $httpClient)
    {
        $this->gorestClient = $httpClient;
    }

    public function find($id) 
    {
        $url = $_ENV['BASE_URL'] . '/users/' . $id;
        $response =$this->callGorestApi($url, 'GET');

        $statusCode = $response->getStatusCode();
        if($statusCode != Response::HTTP_OK) {
            $message = $response->getContent(false);
            throw new HttpException($statusCode, $message);
        }

        $content = $response->toArray();
        $user = new User();
        $user->setId($content["id"]);
        $user->setName($content["name"]);
        $user->setEmail($content["email"]);
        $user->setGender($content["gender"]);
        $user->setStatus($content["status"]);
     
        return $user;
    }


    public function save(User $user): User
    {
         $userArray  = [
                'name' => (string) $user->getName(),
                'email' => (string) $user->getEmail(),
                'gender' => (string) $user->getGender(),
                'status' => (string) $user->getStatus(),
         ];
         $requestJson = json_encode($userArray, JSON_THROW_ON_ERROR);
         $url =  $_ENV['BASE_URL'] . '/users';
         $response =$this->callGorestApi($url, 'POST', $requestJson);

         $statusCode = $response->getStatusCode();
         if ($statusCode != Response::HTTP_CREATED ) {
                $message = $response->getContent(false);
                throw new HttpException($statusCode, $message);
         }


        $responseJson = $response->getContent();
        $responseData = json_decode($responseJson, true, 512, JSON_THROW_ON_ERROR);

        $userResponse = new User();
        $userResponse->setId($responseData["id"]);
        $userResponse->setName($responseData["name"]);
        $userResponse->setEmail($responseData["email"]);
        $userResponse->setGender($responseData["gender"]);
        $userResponse->setStatus($responseData["status"]);
     
        return $userResponse;
    }

    private function callGorestApi(string $url, string $method, string $requestJson = '') {
          try {
            $response = $this->gorestClient->request(
            $method,
            $url,
            [   
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '.$_ENV['ACCESS_TOKEN'],
                ],
                'body' => $requestJson
            ]
            );
            }catch (\Exception $e){
            }
            return $response;
    }
    public function findAll() 
    {

    }

}
