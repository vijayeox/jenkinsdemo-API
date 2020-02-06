<?php
/**
* Document Api
*/
namespace App\Controller;

use Document\Service\DocumentService;
use Oxzion\Utils\Query;
use Oxzion\ValidationException;
use Zend\InputFilter\Input;
use Oxzion\Controller\AbstractApiControllerHelper;

/**
 * Document Controller
 */
class DocumentController extends AbstractApiControllerHelper
{
    /**
    * @ignore __construct
    */
    private $config;
    public function __construct($config)
    {
        $this->setIdentifierName('document');
        $this->config = $config;
    }
    public function head($id = null)
    {
        return true;
    }
    /**
    * Create Document API
    * @api
    * @link /Document
    * @method POST
    */
    public function create($data)
    {
        return $this->getInvalidMethod();
    }
    /**
    * GET Document API
    * @api
    * @link /Document
    * @method GET
    * @param $id ID of Document to Delete
    * @return array $data
    * <code>
    * {
    *  integer id,
    *  string file_name,
    *  integer extension,
    *  string uuid,
    *  string type,
    *  dateTime path Full Path of File,
    * }
    * </code>
    * @return array Returns a JSON Response with Status Code and Created Document.
    */
    public function get($id)
    {
        $params = array_merge($this->extractPostData(), $this->params()->fromRoute());
        $attachment_location = $this->config['APP_DOCUMENT_FOLDER'].$params['orgId']."/".$params['fileId']."/".$params['document'];
        if(isset($params['folder'])){
            $attachment_location = $this->config['APP_DOCUMENT_FOLDER'].$params['orgId']."/".$params['fileId']."/".$params['folder']."/".$params['document'];
        }
        if (file_exists($attachment_location)) {
            header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
            header("Cache-Control: public"); // needed for internet explorer
            header("Content-Transfer-Encoding: Binary");
            header("Content-Length:".filesize($attachment_location));
            header("Content-Disposition: attachment; filename=".$params['document']);
            readfile($attachment_location);
            die();        
        } else {
            die("Error: File not found.");
        }
    }
    /**
    * GET List Document API
    * @api
    * @link /Document
    * @method GET
    * @return Error Response Array
    */
    public function getList()
    {
        return $this->getInvalidMethod();
    }
}
