<?php

namespace Krve\Inmobile\Exceptions;

use Exception;

class GatewayErrorException extends Exception
{
    public const ERROR_CODES = [
        3 => 'SentButNoReports',
        2 => 'Delivered',
        1 => 'AwaitingOperator',
        0 => 'New',
        -1 => 'UndeliverableMessage',
        -2 => 'MsisdnBlacklistedByOperator',
        -3 => 'InvalidMobileNumber',
        -4 => 'CountryNotAvailable',
        -5 => 'DeliveryTimeout',
        -6 => 'NotDeliveredRemovedFromGateway',
        -8 => 'InsufficientFunds',
        -9 => 'AuthorizeFailed',
        -10 => 'CaptureFailed',
        -11 => 'OverchargeDonationLimitExceeded',
        -12 => 'OverchargeTypeNotActivated',
        -13 => 'OverchargeSettingsNotValid',
        -14 => 'SenderNameBlocked',
        -15 => 'RouteNotAvailable',
        -16 => 'RefundNotAvailable',
        -17 => 'RefundNotPossibleForMessage',
        -18 => 'RefundFailed',
        -19 => 'RefundNotPossibleForPendingMessage',
        -20 => 'RefundNotPossibleForFailedMessage',
        -21 => 'RefundFailedAlreadyRefunded',
        -80 => 'ImportedFromOtherSystem',
        -99 => 'SubmitFailed',
        -100 => 'CommunicationError',
        -101 => 'UnknownId',
        -102 => 'Cancelled',
        -103 => 'UnknownErrorProcessingMessage',
        -104 => 'UnknownIdAtOperator',
        -105 => 'MsisdnBlacklistedOnAccount',
        -201 => 'SuspiciousSmsContent',
        -202 => 'AccountDeactivated',

    ];

    protected string|int $inmobileErrorCode;

    public function __construct($message, $inmobileErrorCode)
    {
        parent::__construct($message, 0);

        $this->inmobileErrorCode = $inmobileErrorCode;
    }

    public static function fromCode(int $code): GatewayErrorException
    {
        if (! in_array(needle: $code, haystack: array_keys(self::ERROR_CODES))) {
            return new self('ERROR: unknown response from inmobile. Response code was ' . $code, $code);
        }

        return new self(sprintf('Inmobile error: %s', self::ERROR_CODES[$code]), $code);
    }

    public function getInmobileErrorCode(): string|int
    {
        return $this->inmobileErrorCode;
    }

}
