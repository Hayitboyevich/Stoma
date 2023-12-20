<?php

namespace common\components;

class AccessRule extends \yii\filters\AccessRule
{

    /**
     * @inheritdoc
     */
    protected function matchRole($user)
    {
        if (empty($this->roles)) {
            return true;
        }
        $auth = \Yii::$app->authManager;
        $permissions = $auth->getPermissionsByRole($user->identity->role);

        foreach ($this->roles as $role) {
            if ($role === '?') {
                if ($user->getIsGuest()) {
                    return true;
                }
            } elseif ($role === '@') {
                if (!$user->getIsGuest()) {
                    return true;
                }
            } elseif ($role === '*') {
                return true;
            } elseif (!$user->getIsGuest() && isset($permissions[$role])) {
                return true;
            }
        }
        return false;
    }
}
