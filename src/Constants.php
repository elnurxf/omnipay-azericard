
<?php

namespace Omnipay\AzeriCard;

class Constants
{
    // Transaction types
    const TRTYPE_PURCHASE = '1';
    const TRTYPE_COMPLETE_SALE = '21';
    const TRTYPE_REFUND = '22';
    const TRTYPE_STATUS = '90';
    
    // Response codes
    const ACTION_SUCCESS = '0';
    const ACTION_FAILURE = '1';
    
    // Currency
    const CURRENCY_AZN = 'AZN';
    
    // Country
    const COUNTRY_AZ = 'AZ';
    
    // Timezone
    const TIMEZONE_AZ = '+4';
    
    // Language
    const LANG_EN = 'EN';
    const LANG_AZ = 'AZ';
}
