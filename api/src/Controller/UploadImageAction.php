<?php

namespace App\Controller;

use App\Entity\Image;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Exception\ValidatorException;

class UploadImageAction {

    private $formFactory;
    private $entityManager;
    private $validator;

    public function __construct(
        FormFactoryInterface $formFactory, 
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    )
    {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function __invoke(Request $request)
    {
        // Creact a new Image instance

        $image = new Image();

        // Validate the form
        $form = $this->formFactory->creacte(null, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the new Image entity
            $this->entityManager->persist($image);
            $this->entityManager->flush();

            $image->setFile(null);
            return $image;
        }

        // Uploading done for us in background by VichUploader

        // Throw an validator exception, that means something worng during 
        // form validation
        throw new ValidatorException(
            $this->validator->validate($image)
        );
    }
}