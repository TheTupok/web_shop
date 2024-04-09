<?php

namespace App\Entity\File;

abstract class File
{
    private int $id;
    private string $path;
    private string $rootPath;
    private string $name;

    abstract function getEntity();

    abstract function setEntity(?object $entity): self;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getRootPath(): ?string
    {
        return $this->rootPath;
    }

    public function setRootPath(string $rootPath): self
    {
        $this->rootPath = $rootPath;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}