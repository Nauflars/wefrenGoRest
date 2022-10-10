<?php

namespace App\Users\Infrastructure\Controller;

use App\Users\Domain\Entity\User;
use App\Users\Application\FindUser;
use App\Users\Application\CreateUser;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController
{
    /**
     * @Route("/user/{id}", name="find_user", methods="GET")
     */
    public function findUser($id, FindUser $FindUser): JsonResponse
    {
        $user = $FindUser->findUser($id);
        $data = $FindUser->transform($user);
        return new JsonResponse($data, Response::HTTP_OK, []);
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
        $response = $createUser->transform($userResponse);

        return new JsonResponse($response, Response::HTTP_CREATED , []);
    }

    private function validateUser(ValidatorInterface $validator, User $user)
    {
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            throw new HttpException( Response::HTTP_UNPROCESSABLE_ENTITY, $this->createErrorMessage($errors));
        }
    }
    private function createErrorMessage(ConstraintViolationListInterface $violations): string
    {
        $errors = [];
        foreach ($violations as $violation) {
            $errors[] = [
                "field" => $violation->getPropertyPath(),
                "message" => $violation->getMessage()
            ];
        }
 
        return json_encode($errors);
    }
    private function transformJsonBody(\Symfony\Component\HttpFoundation\Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }
}

