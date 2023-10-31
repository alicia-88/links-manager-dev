<?php

declare(strict_types=1);

namespace App\Models;

use App\Librairies\Database;

class Link
{
    public string $title;
    public int $link_id;
    public string $url;

    public function setType(string $title)
    {
        return $this->title = $title;
    }
    public function setUrl(string $url)
    {
        return $this->url = $url;
    }
}

class LinkModel
{
    public Database $db;

    public function getAll(): array
    {
        $links = $this->db->find('links', Link::class);
        return $links;
    }
    public function create(string $title, string $url)
    {
        var_dump($title, $url);
        $link = $this->db->insert('links', ['title', 'url'], [$title, $url]);
        var_dump($link);
        return $link;
    }
}
