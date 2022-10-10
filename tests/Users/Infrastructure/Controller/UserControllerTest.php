<?php

namespace App\Tests\Users\Infrastructure\Controller;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends ApiTestCase
{
    private $user;
    public function testFindUserOK(): void
    {
        $response = static::createClient()->request('GET', '/user/2000');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testFindUserKO(): void
    {
        $response = static::createClient()->request('GET', '/user/0');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
    }

   /*  public function testCreateUserOK(): void 
    {
       $response = static::createClient()->request(
            'POST',
            '/user',[
                'json' => [
                    'name' => 'testCreate',
                    'email' => 'testCreate9@example.com',
                    'gender' => 'male',
                    'status' => 'active',
                ],
                'headers' => [
                    'Content-Type' => 'application/json', 
                ]
            ]
        );
        $this->assertResponseIsSuccessful();
        
    }*/

        public function testCreateUserKO(): void 
    {
        $response = static::createClient()->request(
            'POST',
            '/user',[
                'json' => [
                    'name' => 'testCreate',
                    'email' => 'testCreate',
                    'gender' => 'male',
                    'status' => 'active',
                ],
                'headers' => [
                    'Content-Type' => 'application/json', 
                ]
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
    }
}
