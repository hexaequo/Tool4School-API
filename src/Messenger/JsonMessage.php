<?php


namespace App\Messenger;


class JsonMessage
{
    private string $id;

    private string $jsonData;

    public function __construct(string $id, array $data)
    {
        $this->id = $id;
        $this->data = json_encode($data);
    }

    public function getId() {
        return $this->id;
    }

    public function getJsonData() {
        return $this->jsonData;
    }

    public function getData() {
        return json_decode($this->jsonData,true);
    }
}
