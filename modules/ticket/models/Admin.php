<?php
namespace app\modules\ticket\models;


use yii\base\BaseObject;
use yii\web\IdentityInterface;

class Admin extends BaseObject implements IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;

    private static $users;
    private static function initializeUsers()
    {
        self::$users = [
        '2001' => [
            'id' => '2001',
                'username' => $_ENV['TICKET_MANAGER_USERNAME'],
                'password' => $_ENV['TICKET_MANAGER_PASSWORD'],
                'authKey' => $_ENV['TICKET_MANAGER_AUTH_KEY'],
                'accessToken' => $_ENV['TICKET_MANAGER_ACCESS_TOKEN'],
        ],
    ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        if (self::$users === null) {
            self::initializeUsers();
        }
        return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        if (self::$users === null) {
            self::initializeUsers();
        }
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        if (self::$users === null) {
            self::initializeUsers();
        }
        foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
