<?php


namespace App\Messenger;


class ArrayMessage
{
    private ?\DateTime $sentAt = null;
    private ?\DateTime $startedAt = null;
    private ?\DateTime $endedAt = null;
    private ?\DateTime $receivedAt = null;

    private ?array $authenticationResponse = null;
    private ?string $bearer = null;

    public static function createMessage(string $id, array $data) {
        return new ArrayMessage($id, $data);
    }

    public static function createAuthenticatedMessage(string $id, array $data, string $bearer) {
        $message =  ArrayMessage::createMessage($id,$data);
        $message->setBearer($bearer);
        $message->setAuthenticationResponse(['code'=>'202']);
    }

    private function __construct(private string $id, private array $data){
        if($this->sentAt == null) $this->setSentAt(new \DateTime());
    }

    public function getId() {
        return $this->id;
    }

    public function getData() {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function getSentAt(): ?\DateTime
    {
        return $this->sentAt;
    }

    public function setSentAt(?\DateTime $sentAt): void
    {
        $this->sentAt = $sentAt;
    }

    public function getStartedAt(): ?\DateTime
    {
        return $this->startedAt;
    }

    public function setStartedAt(?\DateTime $startedAt): void
    {
        $this->startedAt = $startedAt;
    }

    public function getEndedAt(): ?\DateTime
    {
        return $this->endedAt;
    }

    public function setEndedAt(?\DateTime $endedAt): void
    {
        $this->endedAt = $endedAt;
    }

    public function getReceivedAt(): ?\DateTime
    {
        return $this->receivedAt;
    }

    public function setReceivedAt(?\DateTime $receivedAt): void
    {
        $this->receivedAt = $receivedAt;
    }

    public function isEnded(): bool {
        return $this->receivedAt !== null;
    }

    public function getAuthenticationResponse(): ?array
    {
        return $this->authenticationResponse;
    }

    public function setAuthenticationResponse(?array $authenticationResponse): void
    {
        $this->authenticationResponse = $authenticationResponse;
    }

    public function getBearer(): ?string
    {
        return $this->bearer;
    }

    public function setBearer(?string $bearer): void
    {
        $this->bearer = $bearer;
    }
}
