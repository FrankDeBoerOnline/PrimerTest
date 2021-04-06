<?php

namespace Tests\PrimerTest\User;

use FrankDeBoerOnline\Common\Persist\Error\DatabaseError;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorImmutableObject;
use FrankDeBoerOnline\Routing\Error\RoutingError;
use FrankDeBoerOnline\Routing\Routing;
use PHPUnit\Framework\TestCase;
use PrimerTest\User\User;
use PrimerTest\User\UserRouter;
use Symfony\Component\HttpFoundation\Request;

class UserRouterTest extends TestCase
{

    /**
     * @param string $name
     * @param string $email
     * @return User
     * @throws DatabaseError
     * @throws DatabaseErrorImmutableObject
     */
    private function createUser($name = '', $email = '')
    {
        $user = User::findBy('email', $email);
        if(!$user) {
            $user = new User($name, $email);
            $user->setId($user->persist());
        }

        return $user;
    }

    /**
     * @param string $url
     * @return Routing
     */
    private function getRoutingObject($url = '^(/|)')
    {
        try {
            return (new Routing([
                [
                    'url' => $url,
                    'class' => UserRouter::class
                ]
            ]));

        } catch (RoutingError $e) {
            $this->fail('Default routing code not working: ' . $e->getMessage());
        }
    }

    public function testUser()
    {
        $user = $this->createUser('Test User', 'test@primertest.com');

        $request = Request::create('/User', 'GET', ['user_id' => $user->getId()]);
        $routing = $this->getRoutingObject();
        $userRouter = new UserRouter($request, $routing);

        $response = $userRouter->handleRequest();

        // We should have at least a 200 status and a json result
        $this->assertEquals(200, $response->getStatusCode());

        $jsonContent = json_decode($response->getContent());
        $this->assertEquals((string)$jsonContent->result->user_id, (string)$user->getId());

        // Clean up
        $user->delete();
    }

    public function testUserList()
    {
        $user = $this->createUser('Test User', 'test@primertest.com');

        $request = Request::create('/User/List');
        $routing = $this->getRoutingObject();
        $userRouter = new UserRouter($request, $routing);

        $response = $userRouter->handleRequest();

        // We should have at least a 200 status and a json result
        $this->assertEquals(200, $response->getStatusCode());

        $jsonContent = json_decode($response->getContent());
        $this->assertTrue(is_array($jsonContent->result));
        $this->assertGreaterThanOrEqual(1, count($jsonContent->result));

        // Clean up
        $user->delete();
    }

    public function testUserCreate()
    {
        $request = Request::create('/User/Create', 'POST', [
            'name' => 'SomeUserName',
            'email' => 'someuser@primertest.com',
            'password' => 'SomeInitialPassword'
        ]);
        $routing = $this->getRoutingObject();
        $userRouter = new UserRouter($request, $routing);

        $response = $userRouter->handleRequest();

        // We should have at least a 200 status and a json result
        $this->assertEquals(200, $response->getStatusCode());

        $jsonContent = json_decode($response->getContent());
        $this->assertGreaterThanOrEqual(1, (int)$jsonContent->result->user_id);

        // Clean up
        $user = User::find($jsonContent->result->user_id);
        $user->delete();
    }

    public function testUserUpdate()
    {
        $user = $this->createUser('Test User', 'test@primertest.com');

        $request = Request::create('/User/Update', 'POST', [
            'user_id' => $user->getId(),
            'name' => 'Test User Updated',
            'email' => 'newmail@primertest.com'
        ]);
        $routing = $this->getRoutingObject();
        $userRouter = new UserRouter($request, $routing);

        $response = $userRouter->handleRequest();

        // We should have at least a 200 status and a json result
        $this->assertEquals(200, $response->getStatusCode());

        // Test the response contents
        $jsonContent = json_decode($response->getContent());
        $this->assertEquals((int)$user->getId(), (int)$jsonContent->result->user_id);
        $this->assertEquals('Test User Updated', $jsonContent->result->name);
        $this->assertEquals('newmail@primertest.com', $jsonContent->result->email);

        // Get the user directly from the db
        $user = User::find($user->getId());
        $this->assertEquals($user->getName(), $jsonContent->result->name);
        $this->assertEquals($user->getEmail(), $jsonContent->result->email);

        // Clean up
        $user->delete();
    }

    public function testUserDelete()
    {
        $user = $this->createUser('Test User', 'test@primertest.com');

        $request = Request::create('/User/Delete', 'POST', [
            'user_id' => $user->getId()
        ]);
        $routing = $this->getRoutingObject();
        $userRouter = new UserRouter($request, $routing);

        $response = $userRouter->handleRequest();

        // We should have at least a 200 status and a json result
        $this->assertEquals(200, $response->getStatusCode());

        // Test the response contents
        $jsonContent = json_decode($response->getContent());
        $this->assertEquals((int)$user->getId(), (int)$jsonContent->result->user_id);

        // Get the user directly from the db
        $user = User::find($user->getId());
        $this->assertNull($user);
    }

}