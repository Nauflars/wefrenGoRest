<?php 
namespace App\Users\Application;

use App\Users\Domain\Repository\UserRepositoryInterface;
use App\Users\Infrastructure\Repository\UserRepository;
use App\Users\Domain\Entity\User;
use Symfony\Component\HttpClient\HttpClient;

class FindUser
{
	private UserRepositoryInterface $userRepository;
	
	public function __construct() 
	{
		$this->userRepository = new UserRepository(HttpClient::create());
	}

	public function findUser($id): User
	{
		return $this->userRepository->find($id);
	}

	public function transform(User $user): array
    {
        return [
                'id'    => (int) $user->getId(),
                'name' => (string) $user->getName(),
                'email' => (string) $user->getEmail(),
                'gender' => (string) $user->getGender(),
                'status' => (string) $user->getStatus(),
        ];
    }
 
}
