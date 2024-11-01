<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

#[ODM\Document(collection: 'beneficiaries')]
class MobilePaymentBeneficiary
{
    #[ODM\Id]
    private ?string $id = null;

    #[ODM\Field]
    #[Assert\NotBlank]
    private string $document;

    #[ODM\Field]
    #[Assert\NotBlank]
    private string $numberPhone;

    #[ODM\Field]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 25)]
    private string $alias;

    #[ODM\Field]
    #[Assert\NotBlank]
    private string $bank;

    #[ODM\Field]
    private bool $status = true;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getDocument(): string
    {
        return $this->document;
    }
    public function setDocument(string $document): self
    {
        $this->document = $document;
        return $this;
    }

    public function getNumberPhone(): string
    {
        return $this->numberPhone;
    }
    public function setNumberPhone(string $numberPhone): self
    {
        $this->numberPhone = $numberPhone;
        return $this;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }
    public function setAlias(string $alias): self
    {
        $this->alias = $alias;
        return $this;
    }

    public function getBank(): string
    {
        return $this->bank;
    }
    public function setBank(string $bank): self
    {
        $this->bank = $bank;
        return $this;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }
    public function setStatus(bool $status): self
    {
        $this->status = $status;
        return $this;
    }
}
