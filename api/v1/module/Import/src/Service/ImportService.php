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
        if (!isset($data['index'])) {
            throw new Exception('Index Not Specified');
        } else {
            $index = $data['index'];
        }
        $index = (substr($index, -6) != "_index") ? $index . '_index' : $index;
        $file = fopen($fileName, "r");
        $header = fgetcsv($file);
        $params = array();
        $i = 0;
        try {
            while (($data = fgetcsv($file, 1000, ",")) !== false) {
                $idx = 0;
                $body = array();
                foreach ($header as $col) {
                    if (strtoupper($data[$idx]) == 'NULL') {
                        $data[$idx] = '';
                    }
                    if (is_numeric($data[$idx])) {
                        $body[$col] = (float) $data[$idx];
                    } else {
                        $body[$col] = $data[$idx];
                    }
                    $idx++;
                }
                $params[] = $body;
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
            if (!empty($params)) {
                $this->messageProducer->sendQueue(json_encode(array('index' => $index, 'body' => $params, 'operation' => 'Bulk', 'type' => '_doc')), 'elastic');
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;

        }
        return $i;
    }

}
