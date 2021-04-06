<?php

namespace Tests\PrimerTest\Department;

use FrankDeBoerOnline\Common\Persist\Error\DatabaseError;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorImmutableObject;
use FrankDeBoerOnline\Routing\Error\RoutingError;
use FrankDeBoerOnline\Routing\Routing;
use PHPUnit\Framework\TestCase;
use PrimerTest\Department\Department;
use PrimerTest\Department\DepartmentRouter;
use Symfony\Component\HttpFoundation\Request;

class DepartmentRouterTest extends TestCase
{

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
     * @param string $url
     * @return Routing
     */
    private function getRoutingObject($url = '^(/|)')
    {
        try {
            return (new Routing([
                [
                    'url' => $url,
                    'class' => DepartmentRouter::class
                ]
            ]));

        } catch (RoutingError $e) {
            $this->fail('Default routing code not working: ' . $e->getMessage());
        }
    }

    public function testDepartment()
    {
        $department = $this->createDepartment('Department 1', 'Some purpose');

        $request = Request::create('/Department', 'GET', ['department_id' => $department->getId()]);
        $routing = $this->getRoutingObject();
        $departmentRouter = new DepartmentRouter($request, $routing);

        $response = $departmentRouter->handleRequest();

        // We should have at least a 200 status and a json result
        $this->assertEquals(200, $response->getStatusCode());

        $jsonContent = json_decode($response->getContent());
        $this->assertEquals((string)$jsonContent->result->department_id, (string)$department->getId());

        // Clean up
        $department->delete();
    }

    public function testDepartmentList()
    {
        $department = $this->createDepartment('Department 1', 'Some purpose');

        $request = Request::create('/Department/List');
        $routing = $this->getRoutingObject();
        $departmentRouter = new DepartmentRouter($request, $routing);

        $response = $departmentRouter->handleRequest();

        // We should have at least a 200 status and a json result
        $this->assertEquals(200, $response->getStatusCode());

        $jsonContent = json_decode($response->getContent());
        $this->assertTrue(is_array($jsonContent->result));
        $this->assertGreaterThanOrEqual(1, count($jsonContent->result));

        // Clean up
        $department->delete();
    }

    public function testDepartmentCreate()
    {
        $request = Request::create('/Department/Create', 'POST', [
            'name' => 'Department Random',
            'description' => 'Some simple description'
        ]);
        $routing = $this->getRoutingObject();
        $departmentRouter = new DepartmentRouter($request, $routing);

        $response = $departmentRouter->handleRequest();

        // We should have at least a 200 status and a json result
        $this->assertEquals(200, $response->getStatusCode());

        $jsonContent = json_decode($response->getContent());
        $this->assertGreaterThanOrEqual(1, (int)$jsonContent->result->department_id);

        // Clean up
        $department = Department::find($jsonContent->result->department_id);
        $department->delete();
    }

    public function testDepartmentUpdate()
    {
        $department = $this->createDepartment('Department 1', 'Some purpose');

        $request = Request::create('/Department/Update', 'POST', [
            'department_id' => $department->getId(),
            'name' => 'New department name',
            'description' => 'Some simple NEW description'
        ]);
        $routing = $this->getRoutingObject();
        $departmentRouter = new DepartmentRouter($request, $routing);

        $response = $departmentRouter->handleRequest();

        // We should have at least a 200 status and a json result
        $this->assertEquals(200, $response->getStatusCode());

        // Test the response contents
        $jsonContent = json_decode($response->getContent());
        $this->assertEquals((int)$department->getId(), (int)$jsonContent->result->department_id);
        $this->assertEquals('New department name', $jsonContent->result->name);
        $this->assertEquals('Some simple NEW description', $jsonContent->result->description);

        // Get the department directly from the db
        $department = Department::find($department->getId());
        $this->assertEquals($department->getName(), $jsonContent->result->name);
        $this->assertEquals($department->getDescription(), $jsonContent->result->description);

        // Clean up
        $department->delete();
    }

    public function testDepartmentDelete()
    {
        $department = $this->createDepartment('Department 1', 'Some purpose');

        $request = Request::create('/Department/Delete', 'POST', [
            'department_id' => $department->getId()
        ]);
        $routing = $this->getRoutingObject();
        $departmentRouter = new DepartmentRouter($request, $routing);

        $response = $departmentRouter->handleRequest();

        // We should have at least a 200 status and a json result
        $this->assertEquals(200, $response->getStatusCode());

        // Test the response contents
        $jsonContent = json_decode($response->getContent());
        $this->assertEquals((int)$department->getId(), (int)$jsonContent->result->department_id);

        // Get the department directly from the db
        $department = Department::find($department->getId());
        $this->assertNull($department);
    }

}