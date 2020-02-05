<?php
/**
 * Created by PhpStorm.
 * User: Tahina
 * Date: 04/12/2019
 * Time: 19:40
 */

namespace App\Security;


use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserConfirmationService
{

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
        )
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function confirmUser(string $confirmationToken)
    {
        $user = $this->userRepository->findBy(
          ['confirmationToken' => $confirmationToken]
        );

        // User was NOT found by confirmation token
        if(!$user){
            throw new NotFoundHttpException();
        }

        $user->setEnabled(true);
        $user->setConfirmationToken(null);
        $user->entityManager->flush();
    }
}