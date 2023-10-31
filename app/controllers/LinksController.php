<?php

namespace App\Controllers;

use App\Librairies\Database;
use App\Models\Link;
use App\Models\LinkModel;

class LinksController
{
    public static array $errors = [];
    public static bool $isOk = false;


    public function getAll()
    {
        $linkModel = new LinkModel();
        $linkModel->db = new Database();

        $links = $linkModel->getAll();

        include __DIR__ . '/../views/links.php';
    }

    public function insert()
    {
        $linkModel = new LinkModel();
        $linkModel->db = new Database();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $titleLink = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
            $urlLink = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_URL);
            var_dump($titleLink);
            var_dump($urlLink);

            if (empty($titleLink)) {
                self::$errors['titleLink'] = 'Veuillez obligatoirement entrer un nom de lien';
            } else {
                self::$isOk = preg_match('#^([A-Z ]|[a-z])[a-z]*(-)?[a-z]+$#', $titleLink);
                if (!self::$isOk) {
                    self::$errors['titleLink'] = 'Le nom du lien n\'est pas valide';
                }
            }
            if (self::$isOk) {
                $titleLink = ucwords($titleLink);
                var_dump('🎇test');
                $link = $linkModel->create($titleLink, $urlLink);
            }
        }

        $errors = isset(self::$errors['titleLink']) ? self::$errors['titleLink'] : '';

        include __DIR__ . '/../views/links.php';
    }
}
