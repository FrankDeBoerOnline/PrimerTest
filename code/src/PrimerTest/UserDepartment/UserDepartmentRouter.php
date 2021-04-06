<?php

namespace PrimerTest\UserDepartment;

use FrankDeBoerOnline\Routing\AbstractRouter;
use PrimerTest\Department\Department;
use PrimerTest\User\User;

class UserDepartmentRouter extends AbstractRouter
{

    public function getUserDepartmentList()
    {
        try {

            $userId = (int)$this->getRequest()->get('user_id');
            $departmentId = (int)$this->getRequest()->get('department_id');
            $resultData = UserDepartment::getUserDepartments(User::find($userId), Department::find($departmentId));

            return $this->respondJson([ 'result' => $resultData ]);

        } catch(\Exception $e) {
            // For debug purposes we will for now just return the internal error
            return $this->respondJsonError($e->getMessage());
        }
    }

    

}