<?php

class ProductTest extends TestCase
{
    /**
     * /products [GET]
     */
    public function testReturnAllProducts()
    {
        // get method for return all products
        // $this->get(url, header)
        $this->get("products", []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                "*" => [
                    'product_name',
                    'product_description',
                    'created_at',
                    'updated_at',
                    'links'
                ]
            ],
            "meta" => [
                "*" => [
                    'total',
                    'count',
                    'per_page',
                    'current_page',
                    'total_pages',
                    'links',
                ]
            ]
        ]);
    }

    /**
     * /products [POST]
     */
    public function testCreateProduct()
    {
        $request = [
            "product_name" => "Readme Note 6",
            "product_description" => "Ini adalah readmi note 6.",
        ];

        // post data test $this->post(url, request/parameter, header)
        $this->post('products', $request, []);
        // yg ingin dilihat status 200 jika selain 200 maka test dinyatakan gagal
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            ['data' =>
                [
                    'product_name',
                    'product_description',
                    'created_at',
                    'updated_at',
                    'links'
                ]
            ]    
        );
    }

    /**
     * /products/id [GET]
     * 
     */
    public function testFindOneProduct()
    {
        // mendapatkan product berdasarkan id product yg sudah ada didatabase
        $this->get('products/2', []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            ['data' =>
                [
                    'product_name',
                    'product_description',
                    'created_at',
                    'updated_at',
                    'links'
                ]
            ]    
        );
    }

    /**
     * /products/id [PUT]
     */
    public function testUpdateProductById()
    {
        $parameters = [
            'product_name' => 'Infinix Hot Note',
            'product_description' => 'Champagne Gold, 13M AF + 8M FF 4G Smartphone',
        ];
        $this->put("products/4", $parameters, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            ['data' =>
                [
                    'product_name',
                    'product_description',
                    'created_at',
                    'updated_at',
                    'links'
                ]
            ]    
        );
    }

    /**
     * /products/id [DELETE]
     */
    public function testDeleteProductById()
    {
        $this->delete("products/5", [], []);
        $this->seeStatusCode(410);
        $this->seeJsonStructure([
                'status',
                'message'
        ]);
    }
}