<?php
namespace FacturaScripts\Plugins\PrintTicket\Model;

use FacturaScripts\Core\Model\Base;
use FacturaScripts\Core\Base\DataBase\DataBaseWhere;

class TicketCustomLine extends Base\ModelClass
{
    use Base\ModelTrait;

    public $documento;
    public $idlinea;
    public $posicion;
    public $texto;

    public function cleanFromDocument($document, $position)
    {
        $lines = array();

        $sql = 'DELETE FROM ' . static::tableName() 
            . ' WHERE documento = ' . self::$dataBase->var2str($document) 
            . ' AND posicion = ' . self::$dataBase->var2str($position) . ';';

        return self::$dataBase->exec($sql);
    }

    public function getFromDocument($document, $position)
    {
        $where = [
            new DataBaseWhere('documento', $document),
            new DataBaseWhere('documento', 'general', '=', 'OR'),
            new DataBaseWhere('posicion', $position, '='),
        ];

        return $this->all($where);
    }

    public static function primaryColumn()
    {
        return 'idlinea';
    }

    public static function tableName()
    {
        return 'ticketcustomlines';
    }
}