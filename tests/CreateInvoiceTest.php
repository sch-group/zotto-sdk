<?php


namespace SchGroup\Zotto\Tests;



use SchGroup\Zotto\Components\Transaction;
use SchGroup\Zotto\Exceptions\ZottoCallMethodCallException;

class CreateInvoiceTest extends InitTest
{
    /**
     * @throws \SchGroup\Zotto\Exceptions\ZottoCallMethodCallException
     */
    public function testCreateSuccess()
    {
        $link = $this->createInvoice();
        print_r($link);
        $this->assertTrue(is_string($link));
    }

    /**
     * @throws ZottoCallMethodCallException
     */
    public function testFail()
    {

        $transaction = new Transaction(
            rand(0, 10000000) . "kek",
            100,
            'GBP',
            'https://www.google.com',
            'https://www.google.com/',
            'https://www.google.com/',
            'https://www.google.com/',
            'https://www.google.com/',
            rand(0, 10000000) . "kek",
            Transaction::OPEN_BANKING_REDIRECT_TYPE
        );
        $this->expectException(ZottoCallMethodCallException::class);

        $link = $this->client->generateTransactionUrl($transaction);

    }
}