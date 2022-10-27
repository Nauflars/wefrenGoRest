<?php

namespace App\Users\Infrastructure\Controller;

use App\Users\Domain\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ApiController
{
	protected function validatePage(Request $request) 
	{
        if (!$request->query->get('page') || !is_numeric($request->query->get('page'))) 
        {
        	$message = [
                "field" => 'page',
                "message" => 'is invalid'
            ];
           throw new HttpException( Response::HTTP_UNPROCESSABLE_ENTITY, 
            json_encode($message));
        }
	}
    protected function validateUser(ValidatorInterface $validator, User $user)
    {
        $errors = $validator->validate($user);
        if (count($errors) > 0) 
        {
            throw new HttpException( Response::HTTP_UNPROCESSABLE_ENTITY, $this->createErrorMessage($errors));
        }
    }

    private function createErrorMessage(ConstraintViolationListInterface $violations): string
    {
        $errors = [];
        foreach ($violations as $violation) 
        {
            $errors[] = [
                "field" => $violation->getPropertyPath(),
                "message" => $violation->getMessage()
            ];
        }
 
        return json_encode($errors);
    }
    
    protected function transformJsonBody(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) 
        {
            return null;
        }

        if ($data === null) 
        {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }
}