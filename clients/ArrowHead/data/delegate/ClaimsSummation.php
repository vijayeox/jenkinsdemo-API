<?php

use Oxzion\AppDelegate\FileTrait;

class ClaimsSummation
{
    use FileTrait;

    public function execute(array $data)
    {
        $params = array(
            "entityName" => "Policy Claims",
            "assocId" => $data["assocId"],
            "orgId" => "34bf01ab-79ca-42df-8284-965d8dbf290e"
        );
        $filterParams = array(
            ["skip" => 0, "take" => 10000]
        );
        $files = $this->getFileList($params, $filterParams);
        $sumTotal = 0;

        if ($files["total"] > 0) {
            foreach ($files["data"] as $file) {
                $sumTotal += isset($file["total"]) ? floatval($file["total"]) : 0;
            }
        }
        $data["sumTotal"] = $sumTotal;
        return $data;
    }
}
