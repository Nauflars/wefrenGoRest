<?php

namespace App\Tests\Users\Infrastructure\Repository;

use PHPUnit\Framework\TestCase;
use App\Users\Infrastructure\Repository\UserRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use App\Users\Domain\Entity\User;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;

class UserRepositoryUnitTest extends TestCase
{
    private UserRepository $userRepository;
    public function testFind(): void
    {    
        $expectedResponseData = [
            'id' => 12345,
            'name' => 'testName',
            'email' => 'testEmail@email.com',
            'gender' => 'male',
            'status' => 'active'

        ];
        $mockResponseJson = json_encode($expectedResponseData, JSON_THROW_ON_ERROR);
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => Response::HTTP_OK,
            'response_headers' => ['Content-Type: application/json'],
        ]);
        $httpClient = new MockHttpClient($mockResponse );

        $this->userRepository = new UserRepository($httpClient);
        $user = $this->userRepository->find(100);

        self::assertSame('GET', $mockResponse->getRequestMethod());
        self::assertContains(
            'Content-Type: application/json',
            $mockResponse->getRequestOptions()['headers']
        );
        self::assertSame($_ENV['BASE_URL'] .'/users/100', $mockResponse->getRequestUrl());
        self::assertSame('testName', $user->getName());
        self::assertSame('testEmail@email.com', $user->getEmail());
        self::assertSame('male', $user->getGender());
        self::assertSame('active', $user->getStatus());
    }


     public function testFindAll(): void
    {    
        $expectedResponseData = [
            [
                'id' => 1,
                'name' => 'user1',
                'email' => 'userEmail1@email.com',
                'gender' => 'male',
                'status' => 'active'
            ],
            [
                'id' => 2,
                'name' => 'user2',
                'email' => 'userEmail2@email.com',
                'gender' => 'female',
                'status' => 'inactive'
            ]
        ];
        $mockResponseJson = json_encode($expectedResponseData, JSON_THROW_ON_ERROR);
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => Response::HTTP_OK,
            'response_headers' => ['Content-Type: application/json'],
        ]);
        $httpClient = new MockHttpClient($mockResponse );

        $this->userRepository = new UserRepository($httpClient);
        $users = $this->userRepository->findAll(1);

        self::assertSame('GET', $mockResponse->getRequestMethod());
        self::assertContains(
            'Content-Type: application/json',
            $mockResponse->getRequestOptions()['headers']
        );
        self::assertSame($_ENV['BASE_URL'] .'/users?page=1', $mockResponse->getRequestUrl());
        foreach($users as $user) 
        {
            if($user->getId() == 1) 
            {
                self::assertSame('user1', $user->getName());
                self::assertSame('userEmail1@email.com', $user->getEmail());
                self::assertSame('male', $user->getGender());
                self::assertSame('active', $user->getStatus());
            }
             if($user->getId() == 2) 
            {
                self::assertSame('user2', $user->getName());
                self::assertSame('userEmail2@email.com', $user->getEmail());
                self::assertSame('female', $user->getGender());
                self::assertSame('inactive', $user->getStatus());
            }
        }
       
    }
    public function testFindKO(): void
    {    
        $expectedResponseData = [
            'message' => 'Resource not found',
        ];
        $mockResponseJson = json_encode($expectedResponseData, JSON_THROW_ON_ERROR);
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => Response::HTTP_NOT_FOUND,
            'response_headers' => ['Content-Type: application/json'],
        ]);
        $httpClient = new MockHttpClient($mockResponse);

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage("Resource not found");

        $this->userRepository = new UserRepository($httpClient);
        $user = $this->userRepository->find(0);
    }

    public function testSave(): void 
    {
         $expectedResponseData = [
            'id' => 12345,
            'name' => 'testName',
            'email' => 'testEmail@email.com',
            'gender' => 'male',
            'status' => 'active'
        ];
        $mockResponseJson = json_encode($expectedResponseData, JSON_THROW_ON_ERROR);
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => Response::HTTP_CREATED,
            'response_headers' => ['Content-Type: application/json'],
        ]);
        $httpClient = new MockHttpClient($mockResponse);

        $this->userRepository = new UserRepository($httpClient);
        $user = new User() ;
        $user->setName('testName');
        $user->setEmail('testEmail@email.com');
        $user->setGender('male');
        $user->setStatus('active');

        $userResponse = $this->userRepository->save($user);

        self::assertSame('POST', $mockResponse->getRequestMethod());
        self::assertContains(
            'Content-Type: application/json',
            $mockResponse->getRequestOptions()['headers']
        );
        self::assertSame($_ENV['BASE_URL'] .'/users', $mockResponse->getRequestUrl());
        self::assertSame(12345, $userResponse->getId());
        self::assertSame('testName', $userResponse->getName());
        self::assertSame('testEmail@email.com', $userResponse->getEmail());
        self::assertSame('male', $userResponse->getGender());
        self::assertSame('active', $userResponse->getStatus());
    }

    public function testSaveInvalidEmail(): void 
    {
         $expectedResponseData = [
            [  
                "field"=> "email",
                "message"=> "is invalid"
            ]
        ];
        $mockResponseJson = json_encode($expectedResponseData, JSON_THROW_ON_ERROR);
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'response_headers' => ['Content-Type: application/json'],
        ]);
        $httpClient = new MockHttpClient($mockResponse);
        $this->userRepository = new UserRepository($httpClient);
        $user = new User() ;
        $user->setName('testName');
        $user->setEmail('testEmail');
        $user->setGender('male');
        $user->setStatus('active');

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('[{"field":"email","message":"is invalid"}]');
        $userResponse = $this->userRepository->save($user);
    }

    public function testSaveInvalidName(): void 
    {
         $expectedResponseData = [
            [  
                "field"=> "gender",
                "message"=> "can't be blank, can be male of female"
            ]
        ];
        $mockResponseJson = json_encode($expectedResponseData, JSON_THROW_ON_ERROR);
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => 422,
            'response_headers' => ['Content-Type: application/json'],
        ]);
        $httpClient = new MockHttpClient($mockResponse);
        $this->userRepository = new UserRepository($httpClient);
        $user = new User() ;
        $user->setName('testName');
        $user->setEmail('testEmail');
        $user->setGender('male');
        $user->setStatus('active');

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('[{"field":"gender","message":"can\'t be blank, can be male of female"}]');
        $userResponse = $this->userRepository->save($user);
    }
}