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
use App\Users\Domain\Dto\Response\Transformer\UserResponseDtoTransformer;

class UserController extends ApiController
{
    private UserResponseDtoTransformer $userResponseDtoTransformer;

    public function __construct(UserResponseDtoTransformer $userResponseDtoTransformer)
    {
        $this->userResponseDtoTransformer = $userResponseDtoTransformer;
    }

    /**
     * @Route("/user/{id}", name="find_user", methods="GET")
     */
    public function findUser($id, FindUser $findUser): JsonResponse
    {
        $user = $findUser->findUser($id);
        $userDTO = $this->userResponseDtoTransformer->transformFromObject($user);
        return new JsonResponse($userDTO, Response::HTTP_OK, []);
    }

    /**
     * @Route("/user", name="find_all_user", methods="GET")
     */
    public function findAllUser(Request $request, FindAllUser $findAllUser): JsonResponse
    {
        $this->validatePage($request);

        $page = $request->query->get('page');
        $users = $findAllUser->findAllUser($page);
        $usersDTO = $this->userResponseDtoTransformer->transformFromObjects($users);
        return new JsonResponse($usersDTO, Response::HTTP_OK, []);
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

        $usercreated = $createUser->createUser($user);
        $response = $this->userResponseDtoTransformer->transformFromObject($usercreated);

        return new JsonResponse($response, Response::HTTP_CREATED , []);
    }

}

