<?php
require_once 'DigitoolUrlTable.php';
/**
 * DigitoolUrl
 * @package: Omeka
 */
class DigitoolUrl extends Omeka_Record
{
    public $item_id;
    public $pid;
          
    protected function _validate()
    {
        if (empty($this->item_id)) {
            $this->addError('item_id', 'DigitoolUrl requires an item id.');
        }
    }
}
