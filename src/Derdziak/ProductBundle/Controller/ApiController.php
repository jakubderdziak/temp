<?php

namespace Derdziak\ProductBundle\Controller;

use Derdziak\Context\ProductFormContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createAction(Request $request)
    {
        $context = new ProductFormContext();

        $this->get('derdziak_product.form.product.handler')->createNew($request, $context);

        if (empty($context->getErrors())) {
            return $this->json(['id' => $context->getProduct()->getId()], 201);
        }

        return $this->json($context->getErrors(), 400);
    }
}