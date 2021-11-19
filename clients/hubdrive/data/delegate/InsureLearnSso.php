<?php

use Oxzion\AppDelegate\InsuranceTrait;

class InsureLearnSso
{
    use InsuranceTrait;

    public function execute(array $data)
    {
        $this->setInsuranceConfig([
            "client" => "InsureLearn"
        ]);

        return ['SSOLink' => $this->insuranceService->getSsoLink($data['email'], 'Welcome2Hdol!')];
    }
}
