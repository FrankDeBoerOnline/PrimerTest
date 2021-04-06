<?php

namespace PrimerTest\User;

use FrankDeBoerOnline\Common\Encrypt\Password\Password;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseError;
use FrankDeBoerOnline\Routing\AbstractRouter;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserRouter extends AbstractRouter
{

    /**
     * @return JsonResponse
     */
    public function getUser()
    {
        try {
            $userId = (int)$this->getRequest()->get('user_id');
            if($userId && $user = User::find($userId)) {
                return $this->respondJson(['result' => $user->toJSON()]);
            }

            return $this->respondJsonError('User not found');

        } catch (\Exception $e) {
            // For debug purposes we will for now just return the internal error
            return $this->respondJsonError($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     */
    public function getUserList()
    {
        try {
            $resultData = [];
            $userRecordSet = new UserRecordSet();
            if($userRecordSet->execute()) {
                while($user = $userRecordSet->fetch()) {
                    $resultData[] = $user->toJSON();
                }
            }

            return $this->respondJson([ 'result' => $resultData]);

        } catch (\Exception $e) {
            // For debug purposes we will for now just return the internal error
            return $this->respondJsonError($e->getMessage());
        }
    }

    public function postUserCreate()
    {
        try {

            $name = (string)$this->getRequest()->get('name');
            $email = (string)$this->getRequest()->get('email');
            $password = (string)$this->getRequest()->get('password');

            $user = new User($name, $email);
            try {
                $userId = $user->persist();
                $user = User::find($userId);
                $user->setPassword(Password::generateHash($password));
                $user->persist(); // Update
                return $this->respondJson([ 'result' => $user->toJSON()]);

            } catch (DatabaseError $e) {
                return $this->respondJsonError("User could not be created");
            }

        } catch (\Exception $e) {
            // For debug purposes we will for now just return the internal error
            return $this->respondJsonError($e->getMessage());
        }
    }

    public function postUserUpdate()
    {
        try {

            $userId = (int)$this->getRequest()->get('user_id');
            $name = (string)$this->getRequest()->get('name');
            $email = (string)$this->getRequest()->get('email');

            $user = User::find($userId);
            if(!$user) {
                return $this->respondJsonError("User not found");
            }

            try {
                $user->setName($name);
                $user->setEmail($email);
                $user->persist();
                return $this->respondJson([ 'result' => $user->toJSON()]);

            } catch (DatabaseError $e) {
                return $this->respondJsonError("User could not be updated");
            }

        } catch (\Exception $e) {
            // For debug purposes we will for now just return the internal error
            return $this->respondJsonError($e->getMessage());
        }
    }

    public function postUserDelete()
    {
        try {

            $userId = (int)$this->getRequest()->get('user_id');

            $user = User::find($userId);
            if(!$user) {
                return $this->respondJsonError("User not found");
            }

            try {
                $user->delete();
                return $this->respondJson([ 'result' => $user->toJSON()]);

            } catch (DatabaseError $e) {
                return $this->respondJsonError("User could not be deleted");
            }

        } catch (\Exception $e) {
            // For debug purposes we will for now just return the internal error
            return $this->respondJsonError($e->getMessage());
        }
    }

    public function postUserLogin()
    {
        try {

            $userEmail = $this->getRequest()->get('email');
            $userPassword = $this->getRequest()->get('password');

            $user = User::findBy('email', $userEmail);
            if(!$user) {
                return $this->respondJsonError("User not found");
            }

            if(Password::validate($userPassword, $user->getPassword())) {
                // TODO: Here we can do whatever to make sure the login stays
                return $this->respondJson(['result' => $user->toJSON()]); //Success
            }

            return $this->respondJsonError("Unable to login");

        } catch (\Exception $e) {
            // For debug purposes we will for now just return the internal error
            return $this->respondJsonError($e->getMessage());
        }
    }

    public function postUserPasswordReset()
    {
        try {

            $userId = (int)$this->getRequest()->get('user_id');

            $user = User::find($userId);
            if(!$user) {
                return $this->respondJsonError("User not found");
            }

            try {
                $tempPass = Password::generateTemporaryPassword();
                $user->setPassword(Password::generateHash($tempPass));
                $user->persist();

                // TODO: Mail the user or something
                // Here

                return $this->respondJson([ 'result' => $user->toJSON()]);

            } catch (DatabaseError $e) {
                return $this->respondJsonError("User password could not be updated");
            }

        } catch (\Exception $e) {
            // For debug purposes we will for now just return the internal error
            return $this->respondJsonError($e->getMessage());
        }
    }

    public function postUserPasswordChange()
    {
        try {

            $userId = (int)$this->getRequest()->get('user_id');
            $oldPassword = (string)$this->getRequest()->get('old_password');
            $newPassword = (string)$this->getRequest()->get('new_password');

            $user = User::find($userId);
            if(!$user) {
                return $this->respondJsonError("User not found");
            }

            if(!Password::validate($oldPassword, $user->getPassword())) {
                return $this->respondJsonError("Old password does not match");
            }

            try {
                $user->setPassword(Password::generateHash($newPassword));
                $user->persist();
                return $this->respondJson([ 'result' => $user->toJSON()]);

            } catch (DatabaseError $e) {
                return $this->respondJsonError("User password could not be updated");
            }

        } catch (\Exception $e) {
            // For debug purposes we will for now just return the internal error
            return $this->respondJsonError($e->getMessage());
        }
    }

}