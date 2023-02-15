<?php

namespace App\Message;

class NewsNotification
{
    private string $title;
    private string $description;
    private string $image;
    private string $date;

    /**
     * @param string $title
     * @param string $description
     * @param string $image
     * @param string $date
     */
    public function __construct(string $title, string $description, string $image, string $date)
    {
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }
}