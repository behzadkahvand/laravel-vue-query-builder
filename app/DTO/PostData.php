<?php

namespace App\DTO;

class PostData extends BaseDTO
{
    private string $id;

    private ?string $title;

    private ?string $content;

    private ?int $views;

    private ?int $timestamp;

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setViews(?int $views): self
    {
        $this->views = $views;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setTimestamp(?int $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }
}
