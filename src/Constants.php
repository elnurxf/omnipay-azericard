<?php

namespace Omnipay\AzeriCard;

/**
 * Constants for AzeriCard integration.
 */
final class Constants
{
                                              // --- Transaction Types (TRTYPE) ---
    public const TRTYPE_PRE_AUTH      = '1';  // 3D Secure Pre-Authorization (auth)
    public const TRTYPE_SALE          = '0';  // Purchase/Sale
    public const TRTYPE_COMPLETE_AUTH = '21'; // Completion of pre-auth
    public const TRTYPE_REFUND        = '22'; // Refund / Reversal
    public const TRTYPE_VOID          = '24'; // Offline reversal / Void
    public const TRTYPE_STATUS        = '90'; // Status inquiry

    // --- Endpoints ---
    public const TEST_ENDPOINT = 'https://testmpi.3dsecure.az/cgi-bin/cgi_link';
    public const PROD_ENDPOINT = 'https://mpi.3dsecure.az/cgi-bin/cgi_link';

                                       // --- Default Currency ---
    public const CURRENCY_AZN = '944'; // ISO numeric code for Azerbaijani Manat

    // --- Field Lengths ---
    public const ORDER_MIN_LENGTH    = 6;
    public const ORDER_MAX_LENGTH    = 32;
    public const MERCH_NAME_MAX      = 50;
    public const MERCH_URL_MAX       = 250;
    public const DESC_MAX            = 50;
    public const EMAIL_MAX           = 80;
    public const COUNTRY_CODE_LENGTH = 2;
    public const TIMESTAMP_LENGTH    = 14;
    public const NONCE_MIN_LENGTH    = 8;
    public const NONCE_MAX_LENGTH    = 32;
    public const LANG_LENGTH         = 2;
    public const P_SIGN_MAX          = 256;
    public const NAME_MAX            = 45;
    public const M_INFO_MAX          = 35000;

    // --- MAC Key Index ---
    public const MAC_KEY_INDEX = 0;

    /**
     * This class should not be instantiated.
     */
    private function __construct()
    {
    }
}
