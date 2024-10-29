<?php

declare(strict_types=1);

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'beneficiaries')]
class MobilePaymentBeneficiary
{
    #[ODM\Id]
    public ?string $id = null;

    #[ODM\Field]
    public string $document;

    #[ODM\Field]
    public string $numberPhone;

    #[ODM\Field]
    public string $alias;

    #[ODM\Field]
    public string $bank;
}
