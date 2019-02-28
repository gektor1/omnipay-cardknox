<?php

namespace Omnipay\Cardknox\Message;

/**
 * Cardknox  Purchase Request
 */
class PurchaseRequest extends AuthorizeRequest {

    public function getData() {

        if (!is_null($this->getCard())) {
            $this->validate('amount', 'card');
            $this->getCard()->validate();
            $this->action = 'cc:sale';
        } elseif (!is_null($this->getBankAccount())) {
            $this->validate('amount', 'bankAccount');
            $this->action = 'check:sale';
        }

        $data = $this->getBaseData();

        $data['xIP'] = $this->getClientIp();

        if (!is_null($this->getCard())) {
            $data['xCardNum'] = $this->getCard()->getNumber();
            $data['xExp'] = $this->getCard()->getExpiryDate('my');
            $data['xCVV'] = $this->getCard()->getCvv();
        } elseif (!is_null($this->getBankAccount())) {
            $data['xRouting'] = $this->getBankAccount()->getRoutingNumber();
            $data['xAccount'] = $this->getBankAccount()->getAccountNumber();
            $data['xName'] = $this->getBankAccount()->getName();
        }

        return array_merge($data, $this->getBillingData());
    }

}
