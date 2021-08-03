<?php
namespace Oxzion\Prehire\Foley;

use Oxzion\Prehire\PrehireInterface;
use Oxzion\InvalidParameterException;
use Prehire\Service\PrehireService;

class PrehireImpl implements PrehireInterface
{

    private $prehireService;
    public function __construct(PrehireService $prehireService)
    {
        $this->prehireService = $prehireService;
    }

    public function executeProcess($data)
    {
        $requestType = $applicantId = null;
        if(isset($data['request']['requesttype'])) {
            $requestType = $data['request']['requesttype'];
        } 
        elseif(isset($data['order_confirmation']['request']['AuthorizationForm'])) {
            $requestType = 'DTAuthFormDissemination';
        } 
        else {
            throw new InvalidParameterException('Incorrect Request Provided');
        }
        switch($requestType) {
            case 'MVRStatus':
                $this->getMVRUpdate($data['request']);
                $applicantId = $data['request']['driver_applicant']['id'];
                break;
            case 'CH':
                $this->getClearingHouseUpdate($data['request']);
                $applicantId = $data['request']['driver_applicant']['id'];
                break;
            case 'DrugTestStatus':
                $this->getDrugTestResultUpdate($data['request']);
                $applicantId = $data['request']['driver_applicant']['id'];
                break;
            case 'BGCStatus':
                $this->getBackgroundCheckUpdate($data['request']);
                $applicantId = $data['request']['driver_applicant']['id'];
                break;
            case 'DTAuthFormDissemination':
                $this->getDrugTestOrderConfirmationAndAuthForm($data['order_confirmation']);
                $applicantId = $data['order_confirmation']['driver_applicant']['id'];
                break;
            default:
                throw new InvalidParameterException('Incorrect Request Type '.$requestType);
        }
        $dataToSave['request'] = json_encode($data);
        $dataToSave['implementation'] = $data['implementation'];
        $dataToSave['request_type'] = $requestType;
        $dataToSave['referenceId'] = $applicantId;
        $this->prehireService->createRequest($dataToSave);
    }

    private function getMVRUpdate($data)
    {
        $this->checkDriverApplicant($data);
        $this->checkOrderStatus($data);
    }

    private function getDrugTestOrderConfirmationAndAuthForm($data)
    {
        $this->checkDriverApplicant($data);
        if(isset($data['request'])) {
            if(isset($data['request']['order_status'])) {
                if($data['request']['order_status'] != 'Ordered') {
                    throw new InvalidParameterException('Order status specified is not a known value');
                }
            } else {
                throw new InvalidParameterException('Order status has not been specified');
            }
            if(!isset($data['request']['ordered_date'])) {
                throw new InvalidParameterException('Ordered Date has not been specified');
            }
            if(!isset($data['request']['expiration_date'])) {
                throw new InvalidParameterException('Expiration Date has not been specified');
            }
            if(!isset($data['request']['OrderReferenceId'])) {
                throw new InvalidParameterException('Order Reference ID has not been specified');
            }
            if(!isset($data['request']['AuthorizationForm'])) {
                throw new InvalidParameterException('Authorization Form has not been specified');
            }
        } else {
            throw new InvalidParameterException('Request details have not been specified');
        }
    }

    private function getClearingHouseUpdate($data)
    {
        if(isset($data['Account'])) {
            if(!isset($data['Account']['FoleyAccountCode'])) {
                throw new InvalidParameterException('Foley Account Code has not been specified');
            }
            if(!isset($data['Account']['AccountName'])) {
                throw new InvalidParameterException('Account Name has not been specified');
            }
            if(!isset($data['Account']['DOTNumber'])) {
                throw new InvalidParameterException('DOT Number has not been specified');
            }
            if(!isset($data['Account']['FoleyAccountID'])) {
                throw new InvalidParameterException('Foley Account ID has not been specified');
            }
        } else {
            throw new InvalidParameterException('Account details have not been specified');
        }
        $this->checkDriverApplicant($data);
        if(isset($data['Query'])) {
            if(!isset($data['Query']['QueryID'])) {
                throw new InvalidParameterException('Query ID has not been specified');
            }
            if(!isset($data['Query']['APIOrderID'])) {
                throw new InvalidParameterException('API Order ID has not been specified');
            }
            if(!isset($data['Query']['FirstName'])) {
                throw new InvalidParameterException('First Name has not been specified');
            }
            if(!isset($data['Query']['LastName'])) {
                throw new InvalidParameterException('Last Name has not been specified');
            }
            if(!isset($data['Query']['DriversLicenseNumber'])) {
                throw new InvalidParameterException('Drivers Licence number has not been specified');
            }
            if(!isset($data['Query']['LicenseStateofIssue'])) {
                throw new InvalidParameterException('License State of Issue has not been specified');
            }
            if(isset($data['Query']['QueryType'])) {
                if($data['Query']['QueryType'] != 'Pre-Employment') {
                    throw new InvalidParameterException('Query Type specified is not a known value');
                }
            } else {
                throw new InvalidParameterException('Query Type has not been specified');
            }
            if(isset($data['Query']['QueryStatus'])) {
                if($data['Query']['QueryStatus'] != 'Awaiting Processing' && 
                $data['Query']['QueryStatus'] != 'Driver not verified' && 
                $data['Query']['QueryStatus'] != 'Pending driver consent' && 
                $data['Query']['QueryStatus'] != 'Consent Refused' && 
                $data['Query']['QueryStatus'] != 'Completed' ) {
                    throw new InvalidParameterException('Query Status specified is not a known value');
                }
                if($data['Query']['QueryStatus'] == 'Completed') {
                    if(isset($data['Query']['QueryResult'])) {
                        if($data['Query']['QueryResult'] != 'Driver Not prohibited' && 
                        $data['Query']['QueryResult'] != 'Driver prohibited') {
                            throw new InvalidParameterException('Query Status specified is not a known value');
                        } 
                    }
                    else {
                        throw new InvalidParameterException('Query Result has not been specified');
                    }
                }
            } else {
                throw new InvalidParameterException('Query Status has not been specified');
            }
            if(!isset($data['Query']['OrderReferenceID'])) {
                throw new InvalidParameterException('Last Name has not been specified');
            }
        } else {
            throw new InvalidParameterException('Query have not been specified');
        }
    }

    private function getDrugTestResultUpdate($data)
    {
        $this->checkDriverApplicant($data);
        $this->checkOrderStatus($data);
    }

    private function getBackgroundCheckUpdate($data)
    {
        $this->checkDriverApplicant($data);
        if(isset($data['order_status'])) {
            if($data['order_status'] != 'Error' && 
            $data['order_status'] != 'Open' && 
            $data['order_status'] != 'In-Process' && 
            $data['order_status'] != 'In-Process w/Report' && 
            $data['order_status'] != 'Complete') {
                throw new InvalidParameterException('Order status specified is not a known value');                
            }
        } else {
            throw new InvalidParameterException('Order Status has not been specified');
        }
    }

    private function checkDriverApplicant($data) 
    {
        if(isset($data['driver_applicant'])) {
            if(!isset($data['driver_applicant']['id'])) {
                throw new InvalidParameterException('Driver applicant\'s id has not been specified');
            } 
            if(!isset($data['driver_applicant']['report_id'])) {
                throw new InvalidParameterException('Driver applicant\'s report has not been specified');
            } 
        } else {
            throw new InvalidParameterException('Driver applicant data has not been specified');
        }
    }

    private function checkOrderStatus($data) 
    {
        if(isset($data['order_status'])) {
            if($data['order_status'] != 'In-Process' && $data['order_status'] != 'Complete') {
                throw new InvalidParameterException('Order status specified is not a known value');
            }
        } else {
            throw new InvalidParameterException('Order status has not been specified');
        }
    }

}
