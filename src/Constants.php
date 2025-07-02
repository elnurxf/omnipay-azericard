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
}
