<?php

namespace App\Users\Infrastructure\Controller;

use App\Users\Domain\Entity\User;
use App\Users\Application\FindUser;
use App\Users\Application\FindAllUser;
use App\Users\Application\CreateUser;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;

class UserController extends ApiController
{
    /**
     * @Route("/user/{id}", name="find_user", methods="GET")
     */
    public function findUser($id, FindUser $findUser, CacheInterface  $userCache): JsonResponse
    {
        $user = $userCache->get($id, function (ItemInterface $item) 
            use ($id, $findUser) {
            $item->expiresAfter(3600);
            $user = $findUser->findUser($id);
            return $user;
        });

        $userArray = $this->transform($user);
        return new JsonResponse($userArray, Response::HTTP_OK, []);

       /* $user = $findUser->findUser($id);
        $userArray = $this->transform($user);
        return new JsonResponse($userArray, Response::HTTP_OK, []);*/
    }

    /**
     * @Route("/user", name="find_all_user", methods="GET")
     */
    public function findAllUser(Request $request, FindAllUser $findAllUser): JsonResponse
    {
        $this->validatePage($request);

        $page = $request->query->get('page');
        $users = $findAllUser->findAllUser($page);
        $usersArray = $this->transformAll($users);
        return new JsonResponse($usersArray, Response::HTTP_OK, []);
    }

    /**
     * @Route("/user", name="create_user", methods="POST")
     */
    public function createUser(Request $request, CreateUser $createUser, ValidatorInterface $validator): JsonResponse
    {
        $request = $this->transformJsonBody($request);

        $user = new User();
        $user->setName($request->get('name'));
        $user->setEmail($request->get('email'));
        $user->setGender($request->get('gender'));
        $user->setStatus($request->get('status'));

        $this->validateUser($validator, $user);

        $userResponse = $createUser->createUser($user);
        $response = $this->transform($userResponse);

        return new JsonResponse($response, Response::HTTP_CREATED , []);
    }

}

