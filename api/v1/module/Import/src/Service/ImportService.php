<?php
namespace Import\Service;

use Exception;
use Oxzion\Service\AbstractService;
use Oxzion\Utils\FileUtils;

class ImportService extends AbstractService
{
    private $indexer;
    private $messageProducer;
    /**
     * @ignore __construct
     */
    public function __construct($config, $indexer, $messageProducer)
    {
        parent::__construct($config, null);
        $this->indexer = $indexer;
        $this->messageProducer = $messageProducer;
    }

    /**
     * createUpload
     *
     * Upload files from Front End and store it in temp Folder
     *
     *  @param files Array of files to upload
     *  @return JSON array of filenames
     */
    public function upload($data, $files)
    {
        $result = [];
        $type = 'elastic';
        $count = 0;
        try {
            if (isset($data['type'])) {
                $type = strtolower($data['type']);
            }
            foreach ($files as $file) {
                $fileSaved = $this->saveFile($file);
                if ($type == 'elastic') {
                    $count = $this->importToElastic($fileSaved, $data);
                }
                unlink($fileSaved);
                $result[] = ['file' => $file['name'], 'recordsSent' => $count, 'message' => 'Records Sent to ActiveMQ for Indexing'];
            }
            return ($result);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /**
     * @ignore constructAttachment
     */
    public function saveFile($file)
    {
        $baseFolder = $this->config['UPLOAD_FOLDER'] . 'tmp/';
        $fileName = FileUtils::storeFile($file, $baseFolder);
        return ($baseFolder . $fileName);
    }

    public function importToElastic($fileName, $data)
    {
        $type = array();
        if (!isset($data['index'])) {
            throw new Exception('Index Not Specified');
        } else {
            $index = $data['index'];
        }
        $index = (substr($index, -6) != "_index") ? $index . '_index' : $index;
        $file = fopen($fileName, "r");
        $header = fgetcsv($file);
        foreach($header as $key=>$headercol) {
            $colarray = explode(":",$headercol);
            if (isset($colarray['1'])) {                
                $header[$key] = $colarray[0];
                $type[$colarray[0]]=$colarray[1];
            }
        }
        $params = array();
        // $finalArray = array();
        $i = 0;
        try {
            while (($data = fgetcsv($file, 0, ",")) !== false) {
                $idx = 0;
                $body = array();
                foreach ($header as $col) {
                    if (strtoupper($data[$idx]) == 'NULL') {
                        $data[$idx] = '';
                    }
                    if (isset($type[$col])) {
                        switch ($type[$col]) {
                            case "numeric":
                                $body[$col] = (float) $data[$idx];    
                                break;
                            case "text":
                                $body[$col] = (string) $data[$idx];    
                                break;
                            case "date":
                                $body[$col] = date("Y/m/d",strtotime($data[$idx])); 
                                break;
                            default:                               
                                $body[$col] = $data[$idx]; 
                          }
                    } else {
                        if (is_numeric($data[$idx])) {
                            $body[$col] = (float) $data[$idx];
                        } else {
                            $body[$col] = $data[$idx];
                        }
                    }
                    $idx++;
                    // print_r($data);
                }
                $params[] = $body;
                // $finalArray[] = $body;
                // Every 1000 documents stop and send the bulk request
                if ($i % 1000 == 0) {
                    $this->messageProducer->sendQueue(json_encode(array('index' => $index, 'body' => $params, 'operation' => 'Bulk', 'type' => '_doc')), 'elastic');
                    // erase the old bulk request
                    $params = [];

                    // unset the bulk response when you are done to save memory
                    unset($responses);
                }
                $i++;
            }
            // print_r($finalArray);exit;
            if (!empty($params)) {
                $this->messageProducer->sendQueue(json_encode(array('index' => $index, 'body' => $params, 'operation' => 'Bulk', 'type' => '_doc')), 'elastic');
            }
        } catch (Exception $e) {
            throw $e;

        }
        return $i;
    }

}
