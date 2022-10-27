<?php

namespace App\Users\Domain\Dto\Response\Transformer;

use App\Users\Domain\Dto\Response\UserResponseDto;
use App\Users\Domain\Entity\User;

class UserResponseDtoTransformer extends AbstractResponseDtoTransformer
{
    /**
     * @param User $user
     *
     * @return UserResponseDto
     */
    public function transformFromObject($user): UserResponseDto
    {
        if (!$user instanceof User) {
           // throw new Exception();
        }

        $dto = new UserResponseDto();
        $dto->id = $user->getId();
        $dto->name = $user->getName();
        $dto->email = $user->getEmail();
        $dto->gender = $user->getGender();
        $dto->status = $user->getStatus();

        return $dto;
    }
}
