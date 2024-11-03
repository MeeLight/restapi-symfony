<?php

namespace App\Interface;

use App\Document\MobilePaymentBeneficiary;

interface IMobilePaymentBeneficiaryService
{
    public function getAll();
    public function getById(string $id);
    public function create(MobilePaymentBeneficiary $beneficiary);

    public function update();
}
