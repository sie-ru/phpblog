<?php

declare(strict_types = 1);

namespace Blog;

use PDO;

class PostMapper 
{

    private PDO $pdo;

    // connect to database using PDO
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    
    // get url for single post
    public function getUrlKey(string $urlKey):  ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM post WHERE url_key = :url_key');
        $statement->execute([
            'url_key' => $urlKey
        ]);

        $result = $statement->fetchAll();
        return array_shift($result);
    }

    // get post from database
    public function getPost(): ?array 
    {
        $statement = $this->pdo->prepare('SELECT * FROM `post` ORDER BY `published_date` DESC');
        $statement->execute();

        return $statement->fetchAll();
    }

}