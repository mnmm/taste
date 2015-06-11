<?php

class CreateCircleTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->prepareTheDatabase();
        Route::enableFilters();
    }

    public function testTheEndpointIsProtected()
    {
        $this->areWeTalkingToStrangersOn('POST', '/api/circles/new');
    }

    public function testMissingParameters()
    {
        $this->checkForMissingParametersOn('POST', '/api/circles/new');
    }

    public function testWellFormedButNotValid()
    {
        $parameters = array('name' => '  '); // empty name
        $this->prepAothServer('POST', $parameters);
        $response = $this->call('POST', '/api/circles/new', $parameters);
        $data = json_decode($response->getContent());

        $this->assertFalse($response->isOk());
        $this->assertResponseStatus(400);  // Bad Request
        $this->assertCount(1, $data->messages); // the client is told why

        /* test that duplicate cicle names for the same user is rejected */
        $duplicateName = "Friends";
        $circle = Circle::newFrom($duplicateName, 1);

        $parameters = array('name' => $duplicateName);
        $this->prepAothServer('POST', $parameters);
        $response = $this->call('POST', '/api/circles/new', $parameters);
        $data = json_decode($response->getContent());

        $this->assertFalse($response->isOk());
        $this->assertResponseStatus(400);  // Bad Request
        $this->assertCount(1, $data->messages); // the client is told why
    }

    public function testAddValidNewCircle()
    {
        $newCircleName = 'Buddies';
        $parameters = array('name' => $newCircleName);
        $this->prepAothServer('POST', $parameters);
        $response = $this->call('POST', '/api/circles/new', $parameters);
        $data = json_decode($response->getContent());
        
        /* test the response */
        $this->assertTrue($response->isOk());
        $this->assertResponseStatus(200);
        $this->assertObjectHasAttribute('id', $data);
        $this->assertEquals($newCircleName, $data->name);

        /* test if the circle was actually added */
        $owner = Circle::getCircleOwner($data->id);
        $this->assertEquals(1, $owner);
    }

}
