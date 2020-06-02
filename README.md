ZOTTO PAYMENT SYSTEM API CONNECTOR

```bash
composer require sch-group/zotto-sdk
```
Create transaction 
```
       $config = new ZottoConfig($config['host'], $config['merchant_key'], $config['merchant_secret']);
       $client = new ZottoConnector($config);
       
        $transaction = new Transaction(
                   rand(0, 10000000) . "test",
                   100,
                   'EUR',
                   'https://www.google.com',
                   'https://www.google.com',
                   'https://www.google.com',
                   'https://www.google.com',
                   'https://www.google.com',
                   rand(0, 10000000) . "test",
                   Transaction::CARD_REDIRECT_TYPE
        );
       
       $html = $lient->generateTransactionUrl($transaction);
