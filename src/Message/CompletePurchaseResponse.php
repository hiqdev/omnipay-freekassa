<?php
/**
 * FreeKassa driver for Omnipay PHP payment library
 *
 * @link      https://github.com/hiqdev/omnipay-freekassa
 * @package   omnipay-freekassa
 * @license   MIT
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace Omnipay\FreeKassa\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * FreeKassa Complete Purchase Response.
 */
class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * @var CompletePurchaseRequest|RequestInterface
     */
    protected $request;

    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data    = $data;

        if ($this->getSign() !== $this->calculateSignature()) {
            throw new InvalidResponseException('Invalid hash');
        }
    }

    public function calculateSignature()
    {
        return md5(implode(':', [
            $this->getPurse(),
            $this->getAmount(),
            $this->request->getSecretKey2(),
            $this->getTransactionId()
        ]));
    }

    public function getTransactionId()
    {
        return $this->data['MERCHANT_ORDER_ID'];
    }

    public function isSuccessful()
    {
        return true;
    }

    public function getPayer()
    {
        return $this->data['P_EMAIL'] . ' / ' . $this->getPaymentSystem();
    }

    public function getTransactionReference()
    {
        return $this->data['intid'];
    }

    public function getPurse()
    {
        return $this->data['MERCHANT_ID'];
    }

    public function getAmount()
    {
        return (string)$this->data['AMOUNT'];
    }

    public function getSign()
    {
        return $this->data['SIGN'];
    }

    public function getTime()
    {
        return $this->data['us_time'];
    }

    /**
     * @see http://www.free-kassa.ru/docs/api.php#ex_currencies
     * @return string
     */
    protected function getPaymentSystem()
    {
        $map = [
            1 => 'WebMoney WMR',
            2 => 'WebMoney WMZ',
            3 => 'WebMoney WME',
            45 => 'Яндекс.Деньги',
            60 => 'OKPAY RUB',
            61 => 'OKPAY EUR',
            62 => 'OKPAY USD',
            63 => 'QIWI кошелек',
            64 => 'Perfect Money USD',
            67 => 'VISA/MASTERCARD UAH',
            69 => 'Perfect Money EUR',
            70 => 'PayPal',
            79 => 'Альфа-банк RUR',
            80 => 'Сбербанк RUR',
            82 => 'Мобильный Платеж Мегафон',
            83 => 'Мобильный Платеж Билайн',
            84 => 'Мобильный Платеж МТС',
            87 => 'OOOPAY USD',
            94 => 'VISA/MASTERCARD RUB',
            99 => 'Терминалы России',
            106 => 'OOOPAY RUR',
            109 => 'OOOPAY EUR',
            110 => 'Промсвязьбанк',
            114 => 'PAYEER RUB',
            116 => 'Bitcoin',
            117 => 'Денежные переводы',
            118 => 'Салоны связи',
            121 => 'WMR',
            124 => 'VISA/MASTERCARD EUR',
            130 => 'WMR-bill',
            131 => 'WMZ-bill',
            132 => 'Мобильный Платеж Tele2',
            133 => 'FK WALLET RUB',
            136 => 'ADVCASH USD',
            137 => 'Мобильный Платеж МегаФон Северо-Западный филиал',
            138 => 'Мобильный Платеж МегаФон Сибирский филиал',
            139 => 'Мобильный Платеж МегаФон Кавказский филиал',
            140 => 'Мобильный Платеж МегаФон Поволжский филиал',
            141 => 'Мобильный Платеж МегаФон Уральский филиал',
            142 => 'Мобильный Платеж МегаФон Дальневосточный филиал',
            143 => 'Мобильный Платеж МегаФон Центральный филиал',
            147 => 'Litecoin',
            150 => 'ADVCASH RUB',
            153 => 'VISA/MASTERCARD+ RUB',
            154 => 'Skin pay',
            155 => 'QIWI WALLET',
            156 => 'QIWI RUB',
            157 => 'VISA UAH CASHOUT',
            158 => 'VISA/MC INT',
            159 => 'CARD P2P',
        ];

        return isset($map[$this->data['CUR_ID']])
            ? $map[$this->data['CUR_ID']]
            : '';
    }
}
