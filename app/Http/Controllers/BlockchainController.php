<?php

namespace App\Http\Controllers;

use App\Address;
use BlockCypher\Api\TX;
use BlockCypher\Api\WebHook;
use BlockCypher\Client\AddressClient;
use BlockCypher\Client\TXClient;
use Illuminate\Http\Request;

use BlockCypher\Auth\SimpleTokenCredential;
use BlockCypher\Rest\ApiContext;


class BlockchainController extends Controller
{
    public $token = '3c85a32ecd0d432a8e315b4438629b4a';
    public $arr_tokens = [
        'df27a82220344ebf976fb80a6f8ac59c',
        '72b4252b707140248508f723ccab4c9d',
        '0b9a9c0c38e34359a1a204dcc9b5b8ff',
        '3c85a32ecd0d432a8e315b4438629b4a',
        'ebfdb4bac29747d9b1649fd9526d7ebd',

        '0223acd2268d4052bb03b3deec2d7ace',
        '87676e73c7d549c78fe1417c12abbe6f',
        '570e1248d5f043fda6be8059c942a9bf',
        '49ba5a45c1354444aa8bf632c7e82fb2',
        'f92898fa80d8488eb15e567d2ae33065',

        'ee3855473ffb458cb619d385c5771379',
        'a88d37e2f9624565b91bcb0119ca7d25',
        '1110fff5fd9e451ea0b7cce42c48ccfe',
        '5e8cab58d4ba497c87dcb7904615163d',
        '7a40ac2ee42c4a239a7d32a4ea857b5d',

        '4f6b325d2bba4e63a50f4578e5782793',
        '653d29ecdd644edd9b1717cf1fb2e384',
        '1e6061d6fa524bdd9c9bf988bdbd08c3',
        '430c9b730b384153a02bd757e404dc97',
        '323a575a2a8d4b55bb3f8f5c2304d85b',

        'e60a5e1d62d34bc7bf052fb2d7fdd1b9',
        'fe0d9a855c014502b6bb207262c8915e',
        'a9c33037894d446abb3fab8396d8b1db',
        '42a17bf7e3174d1e8e067180c5d39af5',
        '0e84a407844d4b698adbc15bf4c623f1',

        'fa114dad4d4948aa8885bb715b69ba59',
        'be95af4162e0482abca3d0070ac82844',
        '0232ba9fc5fc4242817b01d2dd996c76',
        '1e8e4e1901b2460dbd72075b47a2acda',
        'a9bc4b84f2d24c6ebe810657ac61425f',

        'f8a96f09b1604ba488b713a6d60bf0d1',
        'd6ba04fa33b14a01911ab61b48bd71e2',
        '8e0f1f258b4c484baf0b81856006f478',
        '13a288a8539548e6a0b4ec2491f54d18',
        '28603cfaf2874224af3769378dfad66d',

        'd15475a19ec94444a4b2337dea85f392',
        '9f96f785807f4ff7b1a2f159bfd5d11d',
        '3df128fa712d4da1ae17b9f105c58252',
        'e6bc21ded29a40b6bb25879738244293',
        'ddfc4a2ece42414a91db70ba119982d4',

        '059ec1c3cc3b4b88b1dfc56fdad629b6',
        '27b4c8c02af3480b83e4c78e84688dc8',
        '28f8e52a0b2a470cb363aa301d113ab7',
        'ef66b486606948acb5f69b20b18ea742',
        '60d5817ac8b04a3789fbe7c6921c7cb4',

        '98ea034d4f714c5bbb89d895e958e910',
        '986db21c41384867b4cc605587eef921',
        'f0ca5cc16d864b04a2714b89e1bb3f8a',
        'cfd38191a28d4fc59a027de6bccda821',
        '57d090ba1e1b4e5490712a0e17785666',
    ];

    public function blockchain()
    {
        dd(5);
        $tx = new TX();

        // Tx inputs
        $input = new \BlockCypher\Api\TXInput();
//        $input->addAddress('0x3C71d5b68534182D1Bd80B2AaF7dB9C83e326Fd4');
        $input->addAddress($this->get_address($this->token));//плательщик
        $tx->addInput($input);
// Tx outputs
        $output = new \BlockCypher\Api\TXOutput();
//        $output->addAddress('0xAF39017f85CBd48fF427D50b6c9A11e40b76D1f4');
        $output->addAddress($this->get_address($this->get_address($this->token)));//получатель
        $tx->addOutput($output);
// Tx amount
        $output->setValue(1000); // Satoshis

        $txClient = new TXClient($this->get_api_context('main', 'btc'));
        $txSkeleton = $txClient->create($tx);
        dd($txSkeleton);

    }

    public function fill_addresses()
    {
        set_time_limit(0);
        foreach ($this->arr_tokens as $token)
        {
            for ($i = 0; $i < 200; $i++)
            {
                $addressKeyChain = $this->get_address($token);
                Address::create([
                    'private' => $addressKeyChain->getPrivate(),
                    'public' => $addressKeyChain->getPublic(),
                    'address' => $addressKeyChain->getAddress(),
                    'wif' => $addressKeyChain->getWif(),
                    'currency' => 'dash',
                    'chain' => 'main',
                ]);
            }
        }
    }

    public function get_address($token)
    {
        $addressClient = new AddressClient($this->get_api_context('main', 'dash', $token));
        $addressKeyChain = $addressClient->generateAddress();

//        dd($addressKeyChain->getPrivate());

        return $addressKeyChain;
    }

    private function get_api_context($chain = 'main', $coin = 'btc', $token)
    {
        return $apiContext = ApiContext::create(
            $chain, $coin, 'v1',
            new SimpleTokenCredential($token),
            array('log.LogEnabled' => true, 'log.FileName' => 'BlockCypher.log', 'log.LogLevel' => 'DEBUG')
        );
    }

    public function get_all_webhooks()
    {
        $webHookClient = new \BlockCypher\Client\WebHookClient($this->get_api_context('main', 'btc', $this->token));
        $webHooks = $webHookClient->getAll();
        dd($webHooks);
    }

    public function delete_webhook($id)
    {
        $webHookClient = new \BlockCypher\Client\WebHookClient($this->get_api_context('main', 'btc', $this->token));
        $webHookClient->delete($id);
        dd('deleted');
    }

    public function webhook()
    {
        $txClient = new TXClient($this->get_api_context('main', 'btc', $this->token));
        $transaction = $txClient->get('f6f71371b4658ad1a76868e2e61064fd63513a19d3a0e95ba7402d8c120c2dfc');
        dd($transaction);
//        $this->get_all_webhooks();
        $webHook = new WebHook();
        $webHook->setUrl("https://requestb.in/tajk8mta?uniqid=" . uniqid());
        $webHook->setEvent('new-block');

        $webHookClient = new \BlockCypher\Client\WebHookClient($this->get_api_context('main', 'btc', $this->token));
        $webHookClient->create($webHook);
    }

}
