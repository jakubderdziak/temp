<?php

namespace Derdziak\ProductBundle\Form\Handler;

use Derdziak\Context\ProductFormContext;
use Derdziak\ProductBundle\Entity\Product;
use Derdziak\ProductBundle\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductFormHandler
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(FormFactoryInterface $formFactory, EntityManagerInterface $entityManager)
    {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @param ProductFormContext $context
     */
    public function createNew(Request $request, ProductFormContext $context)
    {
        $product = new Product();

        $form = $this->formFactory->create(ProductType::class, $product);
        //$form->handleRequest($request);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($product);
            $this->entityManager->flush();

            $context->setProduct($product);

            return;
        }

        $context->setErrors($this->getErrorMessages($form));
    }

    /**
     * @param FormInterface $form
     * @return array
     */
    private function getErrorMessages(FormInterface $form)
    {
        $errors = [];

        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }
}