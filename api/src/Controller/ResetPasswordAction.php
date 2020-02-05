<?php
/**
 * Created by PhpStorm.
 * User: Tahina
 * Date: 04/12/2019
 * Time: 13:35
 */

namespace App\Controller;


use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordAction
{
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var JWTTokenManagerInterface
     */
    private $JWTTokenManager;

    public function __construct(
        ValidatorInterface $validator,
        UserPasswordEncoderInterface $userPasswordEncoder,
        EntityManagerInterface $entityManager,
        JWTTokenManagerInterface $JWTTokenManager
    )
    {
        $this->validator = $validator;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->entityManager = $entityManager;
        $this->JWTTokenManager = $JWTTokenManager;
    }

    public function __invoke(User $data)
    {
        //$reset = new ResetPasswordAction();
        // $reset();

        $this->validator->validate($data);

        $data->setPassword(
            $this->userPasswordEncoder->encodePassword(
                $data, $data->getNewPassword()
            )
        );

        // After password change, old tokens are still valid
        $data->setPasswordChangeDate(time());

        $this->entityManager->flush();
        $token = $this->JWTTokenManager->create($data);

        return new JsonResponse(['token'=> $token]);
    }
}