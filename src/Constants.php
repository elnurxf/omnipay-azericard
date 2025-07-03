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

    // Response field names
    const FIELD_ACTION        = 'ACTION';
    const FIELD_RC            = 'RC';
    const FIELD_APPROVAL      = 'APPROVAL';
    const FIELD_RRN           = 'RRN';
    const FIELD_INT_REF       = 'INT_REF';
    const FIELD_TRTYPE        = 'TRTYPE';
    const FIELD_AMOUNT        = 'AMOUNT';
    const FIELD_CURRENCY      = 'CURRENCY';
    const FIELD_ORDER         = 'ORDER';
    const FIELD_TIMESTAMP     = 'TIMESTAMP';
    const FIELD_NONCE         = 'NONCE';
    const FIELD_P_SIGN        = 'P_SIGN';
    const FIELD_DESC          = 'DESC';
    const FIELD_TERMINAL      = 'TERMINAL';
    const FIELD_MAC_KEY_INDEX = 'MAC_KEY_INDEX';
    const FIELD_RRN           = 'RRN';
    const FIELD_INT_REF       = 'INT_REF';

    // Field length limits
    const MAX_LENGTH_AMOUNT     = 12;
    const MAX_LENGTH_CURRENCY   = 3;
    const MAX_LENGTH_ORDER      = 32;
    const MIN_LENGTH_ORDER      = 6;
    const MAX_LENGTH_DESC       = 50;
    const MAX_LENGTH_MERCH_NAME = 50;
    const MAX_LENGTH_MERCH_URL  = 250;
    const MAX_LENGTH_EMAIL      = 80;
    const MAX_LENGTH_COUNTRY    = 2;
    const MAX_LENGTH_LANG       = 2;
    const MAX_LENGTH_NAME       = 45;
    const MIN_LENGTH_NAME       = 2;
    const MAX_LENGTH_M_INFO     = 35000;
}
