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
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data    = $data;

        if ($this->getHash() !== $this->calculateHash()) {
            throw new InvalidResponseException('Invalid hash');
        }

        if ($this->request->getTestMode() !== $this->getTestMode()) {
            throw new InvalidResponseException('Invalid test mode');
        }
    }

    public function isSuccessful()
    {
        return false;
    }
}
