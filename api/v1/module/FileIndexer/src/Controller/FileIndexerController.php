<?php
namespace FileIndexer\Controller;

    use Zend\Log\Logger;
    use Oxzion\Controller\AbstractApiControllerHelper;
    use Oxzion\ValidationException;
    use Zend\Db\Adapter\AdapterInterface;
    use Oxzion\Utils\RestClient;
    use FileIndexer\Service\FileIndexerService;

    class FileIndexerController extends AbstractApiControllerHelper
    {
        private $fileIndexerService;
        protected $log;

        /**
        * @ignore __construct
        */
        public function __construct(FileIndexerService $fileIndexerService, Logger $log)
        {
            $this->fileIndexerService = $fileIndexerService;
            $this->log = $log;
        }

        public function IndexAction() {
            $params = $this->extractPostData();
        }
    }
