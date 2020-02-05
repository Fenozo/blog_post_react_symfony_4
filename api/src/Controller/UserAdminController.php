<?php
/**
 * Created by PhpStorm.
 * User: Tahina
 * Date: 05/12/2019
 * Time: 07:55
 */

namespace App\Controller;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserAdminController extends BaseAdminController
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * UserAdminController constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param ObjectManager $manager
     */
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        ObjectManager $manager
        )
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->manager = $manager;
    }

    /**
     * @param User $entity
     */
    protected function persistEntity($entity)
    {
        $this->encodeUserPassword($entity);

        $this->save($entity);

        return $this->redirectToBackendHomepage();
    }

    /**
     * @param User $entity
     */
    protected function updateEntity($entity)
    {
        $this->encodeUserPassword($entity);

        $this->save($entity);

        return $this->redirectToBackendHomepage();
    }

    /**
     * @param User $entity
     */
    protected function encodeUserPassword($entity): void
    {
        $user = $this->getDoctrine()->getRepository(User::class)
            ->findOneBy(['id'=> $entity->getId()]);

        // $user not instance of User
        if(!$user instanceof User){

            $entity->setPassword(
                $this->passwordEncoder->encodePassword(
                    $entity,
                    $entity->getPassword()
                )
            );
        } else if($user->getPassword() != $entity->getPassword()){
            // $user was instance of User
            $entity->setPassword(
                $this->passwordEncoder->encodePassword(
                    $entity,
                    $entity->getPassword()
                )
            );
        }
    }

    /**
     * @param object $entity
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function removeEntity($entity)
    {
        if(!$entity instanceof User){
            return;
        }

        $this->manager->remove($entity);
        $this->manager->flush();

        return $this->redirectToBackendHomepage();
    }

    protected function save($entity)
    {
        if (!$entity instanceof User) {
            return;
        }

        $this->manager->persist($entity);
        
        return $this->manager->flush();

    }


}