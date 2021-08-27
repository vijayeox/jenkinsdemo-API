<?php

use Oxzion\AppDelegate\HttpClientTrait;
use Oxzion\AppDelegate\HTTPMethod;
use Oxzion\AppDelegate\FileTrait;

class TriggerTemplateService
{

    use HttpClientTrait;
    use FileTrait;
    protected $ExcelTemplateMapperServiceURL = "https://postman-echo.com/post";

    public function execute(array $data)
    {
        $filterParams = array(
            [
                "filter" => array(
                    "logic" => "and",
                    "filters" => array([
                        "field" => "status",
                        'operator' => 'eq',
                        'value' => 'Processing'
                    ]),
                )
            ]
        );
        $files = $this->getFileList($data, $filterParams);
        if ($files["total"] > 0) {
            return array("service_status" => "Template Generation is in progress");
        }

        $filterParams = array(
            [
                "filter" => array(
                    "logic" => "and",
                    "filters" => array([
                        "field" => "status",
                        'operator' => 'eq',
                        'value' => 'Queued'
                    ]),
                ),
                "sort" => array(["field" => "date_created", "dir" => "asc"]),
                "skip" => 0,
                "take" => 1
            ]
        );
        $files = $this->getFileList($data, $filterParams);
        if ($files["total"] == 0) {
            return array("service_status" => "No new files to be Processed");
        }
        // print_r(json_decode($files["data"][0]["documents"], true));
        // exit;
        $response = $this->makeRequest(
            HTTPMethod::POSTMULTIPART,
            $this->ExcelTemplateMapperServiceURL,
            [
                "username" => "123",
                "password" => "password"
            ],
            null,
            [json_decode($files["data"][0]["documents"], true)[0]["fullPath"]]
        );
        print_r(($response));
        exit;
        return array();
    }
}
