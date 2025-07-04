<?php

namespace Omnipay\AzeriCard;

/**
 * Defines constants used throughout the AzeriCard Omnipay integration.
 */
final class Constants
{
    /**
     * Azerbaijani Manat currency code.
     */
    public const CURRENCY_AZN = '944';

    /**
     * Transaction type for pre-authorization (block funds, not captured).
     */
    public const TRTYPE_PRE_AUTH = '1';

    /**
     * Transaction type for sale/purchase (authorize + capture).
     */
    public const TRTYPE_SALE = '0';

    /**
     * Transaction type for refund.
     */
    public const TRTYPE_REFUND = '24';

    /**
     * Transaction type for reversal (void/cancel).
     */
    public const TRTYPE_VOID = '22';

    /**
     * Transaction type for status inquiry.
     */
    public const TRTYPE_STATUS = '31';

    /**
     * Default MAC key index (typically 0 unless bank assigns otherwise).
     */
    public const MAC_KEY_INDEX = 0;

    /**
     * Test endpoint URL.
     */
    public const TEST_ENDPOINT = 'https://testmpi.3dsecure.az/cgi-bin/cgi_link';

    /**
     * Production endpoint URL.
     */
    public const PRODUCTION_ENDPOINT = 'https://mpi.3dsecure.az/cgi-bin/cgi_link';

    // Add other constants as required by your integration.
}
