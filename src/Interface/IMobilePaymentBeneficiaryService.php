<?php

namespace App\Interface;

use App\Document\MobilePaymentBeneficiary;

interface IMobilePaymentBeneficiaryService
{
    public function getAll();
    public function create(MobilePaymentBeneficiary $beneficiary);
}
