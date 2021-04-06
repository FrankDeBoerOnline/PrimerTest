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

            $resultData = [];
            foreach(UserDepartment::getUserDepartments(User::find($userId), Department::find($departmentId)) as $userDepartment) {
                $resultData[] = $userDepartment->toJSON();
            }

            return $this->respondJson([ 'result' => $resultData ]);

        } catch(\Exception $e) {
            // For debug purposes we will for now just return the internal error
            return $this->respondJsonError($e->getMessage());
        }
    }

    public function postUserDepartmentCreate()
    {
        try {

            $userId = (int)$this->getRequest()->get('user_id');
            $departmentId = (int)$this->getRequest()->get('department_id');
            $user = User::find($userId);
            $department = Department::find($departmentId);

            if(!$user || !$department) {
                $this->respondJsonError('Unable to create UserDepartment');
            }

            $userDepartment = UserDepartment::findUserDepartment($user, $department);
            if(!$userDepartment) {
                $userDepartment = new UserDepartment($user, $department);
                $userDepartmentId = $userDepartment->persist();
                $userDepartment = UserDepartment::find($userDepartmentId);
            }

            return $this->respondJson([ 'result' => $userDepartment->toJSON()]);

        } catch(\Exception $e) {
            // For debug purposes we will for now just return the internal error
            return $this->respondJsonError($e->getMessage());
        }
    }

    public function postUserDepartmentDelete()
    {
        try {

            $userId = (int)$this->getRequest()->get('user_id');
            $departmentId = (int)$this->getRequest()->get('department_id');
            $user = User::find($userId);
            $department = Department::find($departmentId);

            if(!$user || !$department) {
                $this->respondJsonError('Unable to delete connection');
            }

            $userDepartment = UserDepartment::findUserDepartment($user, $department);
            if($userDepartment) {
                $userDepartment->delete();
                return $this->respondJson([ 'result' => $userDepartment->toJSON()]);
            }

            return $this->respondJsonError('Connection not found');

        } catch(\Exception $e) {
            // For debug purposes we will for now just return the internal error
            return $this->respondJsonError($e->getMessage());
        }
    }

}