<?php

namespace App\Models;

use App\Connnections\DB;
use App\Errors\DB\ConnectionError;
use App\Errors\DB\DuplicateKey;
use App\Errors\DB\InsufficentData;
use App\Errors\DB\NotFound;
use App\Errors\General\BadRequest;
use App\Errors\General\InternalServerError;
use Exception;
use Fuel\Validation\Validator;
use PDO;

class User
{
    private $id = null;
    private $username;
    private $display_name;
    private $email;
    private $photo_url;
    private $provider;
    private $provider_id;
    private $isAdmin;

    private PDO $db;

    public function __construct()
    {
        $this->db = DB::db();
        if (!$this->db) {
            throw new ConnectionError();
        }
    }

    /**
     * Validate the inputs for appropriate content
     *
     * @param stirng username The username of the user
     * @param string display_name The display name of the user
     * @param string photo_url The photo_url of the user
     * @param stirng email The email address of the user
     **/
    public static function validate(string $username, string $display_name, string $photo_url, string | null $email = null): bool
    {
        $v = new Validator;

        $v
            ->addField("username", "username")

            ->required()
            ->minLength(3)
            ->maxLength(15)

            ->addField("display_name", "display_name")

            ->required()
            ->minLength(4)
            ->maxLength(100)

            ->addField("photo_url", "photo_url")
          
            ->required()
            ->url()

            ->addField("email", "email")

            ->email();

        $data = array(
          'username' => $username,
          'display_name' => $display_name,
          'photo_url' => $photo_url,
          'email' => $email
        );

        $result = $v->run($data);
        /* var_dump($result->getErrors()); */

        return $result->isValid();
    }

    /**
     * validate the username
     *
     * @param  stirng username The username of the user
     * @return bool
     **/
    public static function validateUsername(string $username): bool
    {
        $v = new Validator;
        $v->addField("username", "username")->required()->minLength(3)->maxLength(15);
        $result = $v->run(array('username' => $username));

        return $result->isValid();
    }

    /**
     * Check wether the username of the given user is already occupied in the system
     *
     * @param  string usernaem The username of the user
     * @return bool
     **/
    public function checkUsername(string $username): bool
    {
        try {
            $isValid = self::validateUsername($username);
            if (!$isValid) {
                return false;
            }

            $stmt = $this->db->prepare("SELECT username FROM users WHERE username=?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            if (!$user) {
                return true;
            }

            return false;
        } catch (Exception $e) {
            throw new InternalServerError(message: $e->getMessage());
        }
    }

    /**
     * Get all the details of the user
     *
     * @param ?string id The ID of the user that needs to be fetched
     * @param ?stirng username The username of the user that needs to be fetched
     **/
    public function fetchUser(?string $id = null, ?string $username = null): void
    {
        if ($username == null && $id == null) {
            throw new InsufficentData(message: "the username or the id of the user should be provided");
        }

        $stmt = null;
        $user = null;

        if ($username == null) {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE id=?");
            $stmt->execute([$id]);
            $user = $stmt->fetch();
            if (!$user) {
                throw new NotFound();
            }
        } else {
            $isValid = self::validateUsername($username);
            if (!$isValid) {
                throw new BadRequest();
            }

            $stmt = $this->db->prepare("SELECT * FROM users WHERE username=?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            if (!$user) {
                throw new NotFound();
            }
        }       

        $this->id = $user['id'];
        $this->username = $user['username'];
        $this->display_name = $user['display_name'];
        $this->photo_url = $user['photo_url'];
        $this->email = $user['email'];
        $this->provider = $user['provider'];
        $this->provider = $user['provider_id'];
        $this->isAdmin = $user['is_admin'];

        return;

    }

    /**
     * Set user has the ability to inser a new user into the database
     *
     * @param string username The username of the user
     * @param string display_name The display name of the user
     * @param string photo_url The photo URL of the user
     * @param string provider The name of the provider
     * @param int provider_id The ID of the provider
     * @param ?string email The email of the user
     **/
    public function setUser(string $username, string $display_name, string $photo_url, string $provider, int $provider_id, ?string $email = null): void
    {
        if (!self::validate($username, $display_name, $photo_url, $email)) {
            throw new BadRequest();
        }

        try {
            $stmt = $this->db->prepare("INSERT INTO users (username, display_name, photo_url, email, provider, provider_id) VALUES (:username, :display_name, :photo_url, :email, :provider, :provider_id)");
            $stmt->execute(
                [
                ':username' => $username,
                ':display_name' => $display_name,
                ':photo_url' => $photo_url,
                ':email' => $email,
                ':provider' => $provider,
                ':provider_id' => $provider_id
                ]
            );

            $this->username = $username;
            $this->email = $email ? $email : null;
            $this->display_name = $display_name;
            $this->photo_url = $photo_url;
        } catch (Exception $e) {
            if ($e->getCode() == 23505) {
                throw new DuplicateKey(message: $e->getMessage());
            }

            throw new InternalServerError(message: $e->getMessage());
        }
    }

    /**
     * Check wether the user of the given username is admin or not
     *
     * @return bool
     **/
    public function getAdminStatus(string $username): bool
    {
        $stmt = $this->db->prepare("SELECT is_admin FROM users WHERE username=?");
        $stmt->execute([$username]);
        $result = $stmt->fetch();
        if (!$result) {
            return false;
        }

        return $result['is_admin'];
    }

    /**
     * Get the ID of the user
     *
     * @return string
     **/
    public function getID(): string | null
    {
        return $this->id;
    }

    /**
     * Get the username of the user
     *
     * @return string
     **/
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Get the display name of the user
     *
     * @return string
     **/
    public function getDisplayName(): string
    {
        return $this->username;
    }


    /**
     * Get the photo URL of the user
     *
     * @return string
     **/
    public function getPhotoURL(): string
    {
        return $this->photo_url;
    }

    /**
     * Get the email address of the user
     *
     * @return string | null
     **/
    public function getEmail(): string | null
    {
        return $this->email ? $this->email : null;
    }

    /**
     * Get the provider that the user has used to login
     *
     * @return string
     **/
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * Get the provider ID given by the provider to the user upon login
     *
     * @return int
     **/
    public function getProviderID(): int
    {
        return $this->provider_id;
    }

    /**
     * Get wether the user is a admin or not
     *
     * @return bool
     **/
    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }
}
