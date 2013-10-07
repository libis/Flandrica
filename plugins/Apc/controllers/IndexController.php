<?php
class Apc_IndexController extends Omeka_Controller_Action
{
    public function indexAction(){
       
       apc_clear_cache();
       apc_clear_cache('user');
       apc_clear_cache('opcode');
       echo json_encode(array('APC Clear Cache' => true));

       exit;
    }
}
