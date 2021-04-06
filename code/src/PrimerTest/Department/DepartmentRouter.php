<?php

namespace PrimerTest\Department;

use FrankDeBoerOnline\Common\Persist\Error\DatabaseError;
use FrankDeBoerOnline\Routing\AbstractRouter;
use Symfony\Component\HttpFoundation\JsonResponse;

class DepartmentRouter extends AbstractRouter
{

    /**
     * @return JsonResponse
     */
    public function getDepartment()
    {
        try {
            $departmentId = (int)$this->getRequest()->get('department_id');
            if($departmentId && $department = Department::find($departmentId)) {
                return $this->respondJson(['result' => $department->toJSON()]);
            }

            return $this->respondJsonError('Department not found');

        } catch (\Exception $e) {
            // For debug purposes we will for now just return the internal error
            return $this->respondJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     */
    public function getDepartmentList()
    {
        try {
            $resultData = [];
            $departmentRecordSet = new DepartmentRecordSet();
            if($departmentRecordSet->execute()) {
                while($department = $departmentRecordSet->fetch()) {
                    $resultData[] = $department->toJSON();
                }
            }

            return $this->respondJson([ 'result' => $resultData]);

        } catch (\Exception $e) {
            // For debug purposes we will for now just return the internal error
            return $this->respondJsonError($e->getMessage());
        }
    }

    public function postDepartmentCreate()
    {
        try {

            $name = (string)$this->getRequest()->get('name');
            $description = (string)$this->getRequest()->get('description');

            $department = new Department($name, $description);
            try {
                $departmentId = $department->persist();
                $department = Department::find($departmentId);
                return $this->respondJson([ 'result' => $department->toJSON()]);

            } catch (DatabaseError $e) {
                return $this->respondJsonError("Department could not be created");
            }

        } catch (\Exception $e) {
            // For debug purposes we will for now just return the internal error
            return $this->respondJsonError($e->getMessage());
        }
    }

    public function postDepartmentUpdate()
    {
        try {

            $departmentId = (int)$this->getRequest()->get('department_id');
            $name = (string)$this->getRequest()->get('name');
            $description = (string)$this->getRequest()->get('description');

            $department = Department::find($departmentId);
            if(!$department) {
                return $this->respondJsonError("Department not found");
            }

            try {
                $department->setName($name);
                $department->setDescription($description);
                $department->persist();
                return $this->respondJson([ 'result' => $department->toJSON()]);

            } catch (DatabaseError $e) {
                return $this->respondJsonError("Department could not be updated");
            }

        } catch (\Exception $e) {
            // For debug purposes we will for now just return the internal error
            return $this->respondJsonError($e->getMessage());
        }
    }

    public function postDepartmentDelete()
    {
        try {

            $departmentId = (int)$this->getRequest()->get('department_id');

            $department = Department::find($departmentId);
            if(!$department) {
                return $this->respondJsonError("Department not found");
            }

            try {
                $department->delete();
                return $this->respondJson([ 'result' => $department->toJSON()]);

            } catch (DatabaseError $e) {
                return $this->respondJsonError("Department could not be deleted");
            }

        } catch (\Exception $e) {
            // For debug purposes we will for now just return the internal error
            return $this->respondJsonError($e->getMessage());
        }
    }

}