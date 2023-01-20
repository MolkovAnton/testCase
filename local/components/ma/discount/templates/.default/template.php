<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
?>
<div class="discount__container" id="discountContainer">
    <div>
        <div class="discount__title"><?=Loc::getMessage('MA_DISCOUNT.TITLE')?></div>
        <div class="discount__info"><?=Loc::getMessage('MA_DISCOUNT.GET_DISCOUNT')?></div>
        <div class="discount__button_container">
            <button type="button" class="discount__button" name="getDiscount"><?=Loc::getMessage('MA_DISCOUNT.BUTTON_GET')?></button>
        </div>
    </div>
    <div>
        <input type="text" class="discount__check_field" name="checkField">
        <button type="button" class="discount__button" name="checkDiscount"><?=Loc::getMessage('MA_DISCOUNT.BUTTON_CHECK')?></button>
    </div>
</div>
<script>
    BX.ready(() => {
        let discount = new Discount({
            componentName: '<?=$component->GetName()?>',
            container: 'discountContainer',
            params: <?=CUtil::PhpToJSObject($arParams)?>,
        });
    });
</script>