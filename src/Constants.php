<?php

namespace Omnipay\AzeriCard;

class Constants
{
    // Transaction types
    const TRTYPE_PRE_AUTH      = '0';
    const TRTYPE_PURCHASE      = '1';
    const TRTYPE_COMPLETE_SALE = '21';
    const TRTYPE_REFUND        = '22';
    const TRTYPE_VOID          = '24';
    const TRTYPE_STATUS        = '90';

    // Response codes
    const ACTION_SUCCESS = '0';
    const ACTION_FAILURE = '1';

    // Common currencies
    const CURRENCY_AZN = 'AZN';
    const CURRENCY_USD = 'USD';
    const CURRENCY_EUR = 'EUR';

    // Common countries
    const COUNTRY_AZERBAIJAN = 'AZ';
    const COUNTRY_TURKEY     = 'TR';

    // Common languages
    const LANG_AZERBAIJANI = 'az';
    const LANG_ENGLISH     = 'en';
    const LANG_RUSSIAN     = 'ru';
    const LANG_TURKISH     = 'tr';

    // Legacy language constants for backwards compatibility
    const LANG_EN = 'en';
    const LANG_AZ = 'az';

    // Timezone offsets
    const GMT_AZERBAIJAN = '+4';
    const GMT_TURKEY     = '+3';
    const GMT_UTC        = '+0';

    // Legacy timezone constants for backwards compatibility
    const TIMEZONE_AZ = '+4';
}
