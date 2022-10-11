<?php 
namespace App\Users\Application;

use App\Users\Domain\Repository\UserRepositoryInterface;
use App\Users\Infrastructure\Repository\UserRepository;
use App\Users\Domain\Entity\User;
use Symfony\Component\HttpClient\HttpClient;

class CreateUser
{
	private UserRepositoryInterface $userRepository;
	
	public function __construct() 
	{
		$this->userRepository = new UserRepository(HttpClient::create());
	}

	public function createUser(User $user): User
	{
		return $this->userRepository->save($user);
	}
}
