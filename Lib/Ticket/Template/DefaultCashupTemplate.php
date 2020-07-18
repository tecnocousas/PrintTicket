<?php

namespace FacturaScripts\Plugins\PrintTicket\Lib\Ticket\Template;

use FacturaScripts\Dinamic\Lib\Ticket\Data\Cashup;
use FacturaScripts\Dinamic\Lib\Ticket\Data\Company;

/**
 * 
 */
class DefaultCashupTemplate extends CashupTemplate
{

    public function __construct($width = '50')
    {
        parent::__construct($width);
    }

    protected function buildHead()
    {
        $this->printer->lineBreak();

        $this->printer->lineSplitter();
        $this->printer->text($this->company->getName(), true, true);
        $this->printer->bigText($this->company->getAddress(), true, true);

        if ($this->company->getPhone()) {
            $this->printer->text('TEL: ' . $this->company->getPhone(), true, true);
        }

        $this->printer->text($this->company->getVatID(), true, true);
        $this->printer->LineSplitter('=');
    }

    protected function buildMain()
    {
        $this->printer->text($this->cashup->getDate(), true, true);
        $this->printer->lineSplitter('=');

        $this->printer->columnText('DOCUMENTO','TOTAL');
        $this->printer->lineSplitter('=');

        foreach ($this->cashup->getOperations() as $operation) {
            $this->printer->text($operation->getId(), true);
            $this->printer->columnText($operation->getCode(), $operation->getAmount());
        }

        $this->printer->columnText('SALDO INICIAL', $this->cashup->getInitialAmount());

        $this->printer->lineSplitter('=');          
        $this->printer->columnText('TOTAL ESPERADO', $this->cashup->getSpectedTotal());
        $this->printer->columnText('TOTAL CONTADO:', $this->cashup->getTotal());
    }

    public function buildTicket(Cashup $cashup, Company $company, bool $cut = true, bool $open = true): string
    {
        $this->company = $company;
        $this->cashup = $cashup;

        $this->buildHead();
        $this->buildMain();

        $this->printer->lineBreak();
        $this->openDrawerCommand($open);
        $this->cutPapperCommand($cut);

        return $this->printer->output();
    }
}
