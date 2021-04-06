<?php

namespace Tests\PrimerTest\UserDepartment;

use FrankDeBoerOnline\Common\Persist\Error\DatabaseError;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorImmutableObject;
use FrankDeBoerOnline\Routing\Error\RoutingError;
use FrankDeBoerOnline\Routing\Routing;
use PHPUnit\Framework\TestCase;
use PrimerTest\Department\Department;
use PrimerTest\User\User;
use PrimerTest\User\UserRouter;
use PrimerTest\UserDepartment\UserDepartment;
use PrimerTest\UserDepartment\UserDepartmentRouter;
use Symfony\Component\HttpFoundation\Request;

class UserDepartmentTest extends TestCase
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
     * @param string $name
     * @param string $description
     * @return Department
     * @throws DatabaseError
     * @throws DatabaseErrorImmutableObject
     */
    private function createDepartment($name = '', $description = '')
    {
        $department = Department::findBy('name', $name);
        if(!$department) {
            $department = new Department($name, $description);
            $department->setId($department->persist());
        }

        return $department;
    }

    /**
     * @param User $user
     * @param Department $department
     * @return UserDepartment
     * @throws DatabaseError
     * @throws DatabaseErrorImmutableObject
     */
    private function createUserDepartment(User $user, Department $department)
    {
        $userDepartment = UserDepartment::findUserDepartment($user, $department);
        if(!$userDepartment) {
            $userDepartment = new UserDepartment($user, $department);
            $userDepartment->setId($userDepartment->persist());
        }

        return $userDepartment;
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
                    'class' => UserDepartmentRouter::class
                ]
            ]));

        } catch (RoutingError $e) {
            $this->fail('Default routing code not working: ' . $e->getMessage());
        }
    }

    private function makeRequest($url, $method = 'GET', $data = [])
    {
        $request = Request::create($url, $method, $data);
        $routing = $this->getRoutingObject();
        $userDepartmentRouter = new UserDepartmentRouter($request, $routing);
        return $response = $userDepartmentRouter->handleRequest();
    }

    public function testUserDepartmentList()
    {
        $user1 = $this->createUser("User1", "user1@primertest.com");
        $user2 = $this->createUser("User2", "user2@primertest.com");

        $department1 = $this->createDepartment("Department1", "Something");
        $department2 = $this->createDepartment("Department2", "Something");

        // Link all department to user1 and only the second department to user2
        $this->createUserDepartment($user1, $department1);
        $this->createUserDepartment($user1, $department2);
        $this->createUserDepartment($user2, $department2);

        // Get the departments for user1
        $response = $this->makeRequest('/UserDepartment/List', 'GET', ['user_id' => $user1->getId()]);
        // We should have at least a 200 status and a json result
        $this->assertEquals(200, $response->getStatusCode());
        $jsonContent = json_decode($response->getContent());
        // We should have 2 results
        $this->assertCount(2, $jsonContent->result);


        // Get the departments for user2
        $response = $this->makeRequest('/UserDepartment/List', 'GET', ['user_id' => $user2->getId()]);
        // We should have at least a 200 status and a json result
        $this->assertEquals(200, $response->getStatusCode());
        $jsonContent = json_decode($response->getContent());
        // We should have 2 results
        $this->assertCount(1, $jsonContent->result);

        // Get the users for department1
        $response = $this->makeRequest('/UserDepartment/List', 'GET', ['department_id' => $department1->getId()]);
        // We should have at least a 200 status and a json result
        $this->assertEquals(200, $response->getStatusCode());
        $jsonContent = json_decode($response->getContent());
        // We should have 2 results
        $this->assertCount(1, $jsonContent->result);

        // Get the users for department2
        $response = $this->makeRequest('/UserDepartment/List', 'GET', ['department_id' => $department2->getId()]);
        // We should have at least a 200 status and a json result
        $this->assertEquals(200, $response->getStatusCode());
        $jsonContent = json_decode($response->getContent());
        // We should have 2 results
        $this->assertCount(2, $jsonContent->result);

        // Clean up
        $user1->delete();
        $user2->delete();
        $department1->delete();
        $department2->delete();
    }


}