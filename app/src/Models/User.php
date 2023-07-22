<?php

namespace App\Models;

use App\Connnections\DB;
use App\Errors\DB\ConnectionError;
use App\Errors\DB\DuplicateKey;
use App\Errors\DB\InsufficentData;
use App\Errors\DB\NotFound;
use App\Errors\General\InternalServerError;
use Exception;
use PDO;

class User
{
    private $id = null;
    private $username;
    private $display_name;
    private $email;
    private $photo_url;

    private PDO $db;

    public function __construct()
    {
        $this->db = DB::db();
        if (!$this->db) {
            throw new ConnectionError();
        }
    }

    /**
     * Get all the details of the user
     *
     * @param ?string id The ID of the user that needs to be fetched
     * @param ?stirng username The username of the user that needs to be fetched
     **/
    public function getUser(?string $id = null, ?string $username = null): void
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
        return;

    }

    /**
     * Set user has the ability to inser a new user into the database
     *
     * @param string username The username of the user
     * @param string display_name The display name of the user
     * @param string photo_url The photo URL of the user
     * @param string email The email of the user
     **/
    public function setUser(string $username, string $display_name, string $photo_url, ?string $email): void
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO users (username, display_name, photo_url, email) VALUES (:username, :display_name, :photo_url, :email)");
            $stmt->execute(
                [
                ':username' => $username,
                ':display_name' => $display_name,
                ':photo_url' => $photo_url,
                ':email' => $email
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
     * @return string
     **/
    public function getEmail(): ?string
    {
        return $this->email ? $this->email : null;
    }
}
