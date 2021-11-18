<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\FileTrait;

class ComplianceCleanUp extends AbstractAppDelegate
{
    use FileTrait;
    const APPID = 'a4b1f073-fc20-477f-a804-1aa206938c42';

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    { 
        
        $filterParams = array();
        $filterParams['filter'][0]['filter']['filters'][] = array('field' => 'entity_name', 'operator' => 'eq', 'value' => 'Compliance');
        $data['filterParams'] = $filterParams;
        $filterParams['filter'][0]['skip'] = $data['skip'];
        $filterParams['filter'][0]['take'] = $data['take'];

        $files = $this->getFileList($data, $filterParams);
        //print_r($files); die;
        //echo count($files['data']);die;
        $ff = [];
        foreach($files['data'] as $k=>$eachfile){
            //print_r($k);
            $fileData = json_decode($eachfile['data'], true);
            $fileId = $eachfile['uuid'];
            //print_r($fileData);
            $ff[$k] = $eachfile['name'];


            $fileData['driverTypeJson'] = array (
                0 => 
                array (
                  'label' => 'Pickup & Delivery',
                  'value' => 'pickupDelivery',
                ),
                1 => 
                array (
                  'label' => 'Fleet Line Haul',
                  'value' => 'fleetLineHaul',
                ),
                2 => 
                array (
                  'label' => 'Area Service Provider',
                  'value' => 'areaServiceProvider',
                ),
                3 => 
                array (
                  'label' => 'Service Provider',
                  'value' => 'serviceProvider',
                ),
                4 => 
                array (
                  'label' => 'RSP',
                  'value' => 'rsp',
                ),
            );
            $fileData['facilityJson'] = array (
                0 => 
                array (
                  'label' => '<b>Bay Region:</b> Concord – CCR',
                  'value' => 'bBayRegionBConcordCcr',
                ),
                1 => 
                array (
                  'label' => 'Hayward – HAY',
                  'value' => 'haywardHay',
                ),
                2 => 
                array (
                  'label' => 'San Jose – SJC',
                  'value' => 'sanJoseSjc',
                ),
                3 => 
                array (
                  'label' => 'San Francisco – SFO',
                  'value' => 'sanFranciscoSfo',
                ),
                4 => 
                array (
                  'label' => '<b>Central Region:</b> Bakersfield – BFL',
                  'value' => 'bCentralRegionBBakersfieldBfl',
                ),
                5 => 
                array (
                  'label' => 'Fresno – FAT',
                  'value' => 'fresnoFat',
                ),
                6 => 
                array (
                  'label' => 'Monterey – MRY',
                  'value' => 'montereyMry',
                ),
                7 => 
                array (
                  'label' => 'Petaluma – PET',
                  'value' => 'petalumaPet',
                ),
                8 => 
                array (
                  'label' => 'Sacramento – SAC',
                  'value' => 'sacramentoSac',
                ),
                9 => 
                array (
                  'label' => 'Santa Maria – SMX',
                  'value' => 'santaMariaSmx',
                ),
                10 => 
                array (
                  'label' => 'Stockton – STK',
                  'value' => 'stocktonStk',
                ),
                11 => 
                array (
                  'label' => 'Visalia - VIS',
                  'value' => 'visaliaVis',
                ),
                12 => 
                array (
                  'label' => '<b>Pacific Region:</b> Commerce – COM',
                  'value' => 'bPacificRegionBCommerceCom',
                ),
                13 => 
                array (
                  'label' => 'Los Angeles – LAX',
                  'value' => 'losAngelesLax',
                ),
                14 => 
                array (
                  'label' => 'Orange – ORG',
                  'value' => 'orangeOrg',
                ),
                15 => 
                array (
                  'label' => 'San Diego – SAN',
                  'value' => 'sanDiegoSan',
                ),
                16 => 
                array (
                  'label' => '<b>Coast Region:</b> Burbank – BUR',
                  'value' => 'bCoastRegionBBurbankBur',
                ),
                17 => 
                array (
                  'label' => 'Ontario – ONT',
                  'value' => 'ontarioOnt',
                ),
                18 => 
                array (
                  'label' => 'Redland - RDL',
                  'value' => 'redlandRdl',
                ),
                19 => 
                array (
                  'label' => '<b>Southwest Region:</b> Buckeye – BKY',
                  'value' => 'bSouthwestRegionBBuckeyeBky',
                ),
                20 => 
                array (
                  'label' => 'Hebron – CVG',
                  'value' => 'hebronCvg',
                ),
                21 => 
                array (
                  'label' => 'Denver – DIA',
                  'value' => 'denverDia',
                ),
                22 => 
                array (
                  'label' => 'Phoenix – PHX',
                  'value' => 'phoenixPhx',
                ),
                23 => 
                array (
                  'label' => 'Reno – RNO',
                  'value' => 'renoRno',
                ),
                24 => 
                array (
                  'label' => 'Tucson – TUC',
                  'value' => 'tucsonTuc',
                ),
                25 => 
                array (
                  'label' => 'Utah – UTA',
                  'value' => 'utahUta',
                ),
                26 => 
                array (
                  'label' => 'Las Vegas – VEG',
                  'value' => 'lasVegasVeg',
                ),
                27 => 
                array (
                  'label' => '<b>Northwest Region:</b> Boise – BOI',
                  'value' => 'bNorthwestRegionBBoiseBoi',
                ),
                28 => 
                array (
                  'label' => 'Seattle – SEA',
                  'value' => 'seattleSea',
                ),
                29 => 
                array (
                  'label' => 'Tacoma – TAC',
                  'value' => 'tacomaTac',
                ),
                30 => 
                array (
                  'label' => 'Vancouver – VAN',
                  'value' => 'vancouverVan',
                ),
                31 => 
                array (
                  'label' => 'Stokane – GEG',
                  'value' => 'stokaneGeg',
                ),
                32 => 
                array (
                  'label' => '<b>EMS:</b> Sioux Falls – FSD',
                  'value' => 'bEmsBSiouxFallsFsd',
                ),
                33 => 
                array (
                  'label' => 'Minneapolis – MSP',
                  'value' => 'minneapolisMsp',
                ),
                34 => 
                array (
                  'label' => 'Omaha – OMA',
                  'value' => 'omahaOma',
                ),
                35 => 
                array (
                  'label' => 'Lincoln – LIN',
                  'value' => 'lincolnLin',
                )
            );
            $a = array_column($fileData['facilityJson'], 'label','value');
            $fileData['facilityName'] = '';
            if(array_key_exists($fileData['pleaseSelectTheFacility'], $a))
                $fileData['facilityName'] = $a[$fileData['pleaseSelectTheFacility']];
            
            $b = array_column($fileData['driverTypeJson'], 'label','value');
            $fileData['driverName'] = '';
            if(array_key_exists($fileData['pleaseSelectDriverType'], $b))
                $fileData['driverName'] = $b[$fileData['pleaseSelectDriverType']];
            
            //print_r($fileData);die;
            $this->saveFile($fileData, $fileId);
            
        }
        //print_r($ff);die;
    }
}