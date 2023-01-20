<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Engine\Contract\Controllerable,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Loader,
    Bitrix\Main\Type\DateTime,
    Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Engine\ActionFilter\{HttpMethod};

class DiscountComponent extends \CBitrixComponent implements Controllerable {

    protected function init()
    {
        global $USER;
        if (!$USER->isAuthorized()) {
            throw new \Exception(Loc::getMessage('MA_DISCOUNT.NOT_AUTHORIZED'));
        }
    }

    public function executeComponent() 
    {
        try {
            $this->init();	
            $this->includeComponentTemplate();
        } catch(\Exception $e) {
            ShowError($e->getMessage());
            return false;
        }
    }
    
    private function getEntityClass($code) {
        Loader::includeModule("highloadblock");
        $hlblock = HighloadBlockTable::getList([
            'filter' => ['NAME' => $code],
            'cache' => ["ttl" => 3600 * 24 * 7]
        ])->fetch();
        $entityClass = HighloadBlockTable::compileEntity($hlblock)->getDataClass();
        return $entityClass;
    }

    public function configureActions()
    {
        return [
            'checkDiscountCode' => [
                'prefilters' => [
                    new HttpMethod([HttpMethod::METHOD_POST])
                ]
            ],
            'getDiscount' => [
                'prefilters' => [
                    new HttpMethod([HttpMethod::METHOD_POST])
                ]
            ],
        ];
    }
    
    public function getDiscountAction($params) {
        global $USER;
        $entity = $this->getEntityClass($params['HLBLOCK_CODE']);
        $time = new DateTime();
        $newTime = new DateTime();
        
        $current = $entity::getList([
            'filter' => [
                'UF_USER' => $USER->getId(),
                '>UF_DATE' => $time->add('-T1H')
            ],
            'select' => ['UF_CODE', 'UF_PERCENT']
        ])->fetch();
        if ($current) {
            return ['code' => Loc::getMessage('MA_DISCOUNT.CODE').$current['UF_CODE'], 'percent' => $current['UF_PERCENT']];
        }
        
        $code = substr(md5($newTime.$USER->getId()), 0, 7);
        $percent = rand(1, 50);
        
        try {
            $result = $entity::add([
                'UF_DATE' => $newTime,
                'UF_CODE' => $code,
                'UF_PERCENT' => $percent,
                'UF_USER' => $USER->getId()
            ]);  
        } catch (\Error $e) {
            return $e->getMessage();
        }

        return $result->getErrorMessages() ? ['error' => $result->getErrorMessages()] : ['code' => Loc::getMessage('MA_DISCOUNT.CODE').$code, 'percent' => $percent];
    }

    public function checkDiscountCodeAction($code, $params)
    {
        global $USER;
        $entity = $this->getEntityClass($params['HLBLOCK_CODE']);
        $time = new DateTime();
        
        $current = $entity::getList([
            'filter' => [
                'UF_USER' => $USER->getId(),
                '>UF_DATE' => $time->add('-T3H'),
                'UF_CODE' => $code
            ],
            'select' => ['UF_CODE', 'UF_PERCENT']
        ])->fetch();

        return $current ? ['code' => Loc::getMessage('MA_DISCOUNT.CODE').$current['UF_CODE'], 'percent' => $current['UF_PERCENT']] : ['error' => [Loc::getMessage('MA_DISCOUNT.WRONG_CODE')]];
    }
}