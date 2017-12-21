<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class MediaControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLandingPage()
    {
        $this->get('/');
        $this->assertResponseOk();
    }

    /**
     * Tests the JSON header
     */
    public function testJsonHeader()
    {
        $mediaController = new \App\Http\Controllers\MediaController();
//        $data = $mediaController->getPersonsMedia('steven.fitzgerald');
//        $this->assertArrayHasKey('success', $data);
//        $this->assertArrayHasKey('status', $data);
//        $this->assertrrayHasKey('api', $data);
        $this->assertArrayHasKey('version', $data);
        $this->assertArrayHasKey('collection', $data);
        $this->assertArrayHasKey('count', $data);
        $this->assertArrayHasKey('media', $data);
    }

    /**
     * Tests the JSON body
     */
    public function testFullJsonBody()
    {
        $mediaController = new \App\Http\Controllers\MediaController();
        $data = $mediaController->getPersonsMedia('steven.fitzgerald');
        $this->assertEquals('2', $data['count']);
        $this->assertContains('audio', $data['media'][0]['audio']);
        $this->assertContains('avatar', $data['media'][0]['avatar']);
    }


    /**
     * Tests the a broken body.
     */
    public function testIncompleteJsonBody()
    {
        $mediaController = new \App\Http\Controllers\MediaController();
        $data = $mediaController->getPersonsMedia('lol.lol');
        $this->assertNotContains('audio', $data['media'][0]);
        $this->assertNotContains('avatar', $data['media'][0]);
    }
}
