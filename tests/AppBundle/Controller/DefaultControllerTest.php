<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use GuzzleHttp\Client;

class DefaultControllerTest extends WebTestCase
{
    public function testRecipeByIdIndex()
    {
        $client = new Client();
        $result = $client->request('GET', 'http://api.gusto.local/app_dev.php/recipe/2');

        $this->assertEquals(
		    200,
		    $result->getStatusCode()
        );
        
        $this->assertTrue(
		    $result->hasHeader('Content-Type'),
		    '"Content-Type" header missing'
        );
        
        $this->assertEquals(
		    'application/json',
		    $result->getHeader('Content-Type')[0],
		    '"Content-Type" header should be "application/json"'
		);

        $content = json_decode($result->getBody()->getContents());
		
		$this->assertTrue(
		    is_array($content),
		    'Respnse content should be of array type'
		);
    }

    public function testRecipeByCusineIndex()
    {
        $client = new Client();
        $result = $client->request('GET', 'http://api.gusto.local/recipe/cuisine/asian');

        $this->assertEquals(
		    200,
		    $result->getStatusCode()
        );
        
        $this->assertTrue(
		    $result->hasHeader('Content-Type'),
		    '"Content-Type" header missing'
        );
        
        $this->assertEquals(
		    'application/json',
		    $result->getHeader('Content-Type')[0],
		    '"Content-Type" header should be of type "application/json"'
        );

        $content = json_decode($result->getBody()->getContents());
		
		$this->assertTrue(
		    is_array($content),
		    'Respnse content should be of array type'
		);
    }
}
