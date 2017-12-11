<?php

namespace Derdziak\ProductBundle\Tests\Controller;

use Derdziak\ProductBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testCreateNewProduct()
    {
        $client = static::createClient();

        $crawler = $client->request(
            'POST',
            '/product/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['sku' => 'SKU','name' => 'Product', 'price' => ['iso' => 'PLN', 'value' => '10.31']])
        );

        $this->assertEquals(
            201,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );

        $products = $this->em->getRepository(Product::class)
            ->findAll();

        $this->assertCount(1, $products);

        //Simple isolation
        $this->em->remove($products[0]);
        $this->em->flush();
    }

    /**
     * @dataProvider productValidationDataProvider
     * @param array $productArray
     */
    public function testProductValidation(array $productArray)
    {
        $client = static::createClient();

        $crawler = $client->request(
            'POST',
            '/product/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($productArray)
        );

        $this->assertEquals(
            400,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );

        $products = $this->em->getRepository(Product::class)
            ->findAll();

        $this->assertCount(0, $products);
    }

    /**
     * @return array
     */
    public function productValidationDataProvider()
    {
        return [
            'no_sku' => [['name' => 'Product', 'price' => ['iso' => 'PLN', 'value' => '10.31']]],
            'no_name' => [['sku' => 'sku', 'price' => ['iso' => 'PLN', 'value' => '10.31']]],
            'no_price' => [['sku' => 'sku','name' => 'Product']],
            'no_iso' => [['sku' => 'sku','name' => 'Product', 'price' => ['value' => '10.31']]],
            'wrong_iso' => [['sku' => 'sku','name' => 'Product', 'price' => ['iso' => 'A1B', 'value' => '10.31']]],
            'no_value' => [['sku' => 'sku','name' => 'Product', 'price' => ['iso' => 'PLN']]],
            'wrong_value' => [['sku' => 'sku','name' => 'Product', 'price' => ['iso' => 'PLN', 'value' => 'ten']]],
        ];
    }
}
