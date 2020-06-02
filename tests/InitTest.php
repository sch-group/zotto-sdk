<?php

namespace SchGroup\Zotto\Tests;

use Matomo\Ini\IniReader;
use PHPUnit\Framework\TestCase;
use SchGroup\Zotto\Components\Transaction;
use SchGroup\Zotto\ZottoConnection\ZottoConfig;
use SchGroup\Zotto\ZottoConnection\ZottoConnector;

class InitTest extends TestCase
{
    /**
     * @var ZottoConnector $client
     */
    protected $client;



    /**
     * InitTest constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     * @throws \Matomo\Ini\IniReadingException
     */
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $reader = new IniReader();
        $config = $reader->readFile(__DIR__ . '/config.ini');
        $weldPayConfig = new ZottoConfig($config['host'], $config['merchant_key'], $config['merchant_secret']);
        $this->client = new ZottoConnector($weldPayConfig);

    }

    /**
     * @return mixed
     * @throws \SchGroup\Zotto\Exceptions\ZottoCallMethodCallException
     */
    protected function createInvoice()
    {
        $transaction = new Transaction(
            rand(0, 10000000) . "kek",
            100,
            'EUR',
            'https://www.google.com',
            'https://www.google.com',
            'https://www.google.com',
            'https://www.google.com',
            'https://www.google.com/',
            rand(0, 10000000) . "kek",
            Transaction::CARD_REDIRECT_TYPE
        );

        return $this->client->generateTransactionUrl($transaction);
    }

}