<?php

namespace classes;
use classes\Ap2;
use classes\Hosts;

class Vhm {

    private $_conf = array();    
    private $ap2 = null;    
    private $hosts = null;

    public function __construct(array $config = []) {
        
        if(sizeof($config) == 0){
            return false;
        }
        
        $this->_conf['server_name'] = $config['server_name'];
        
        # Config document root
        $this->_conf['document_root'] = $config['document_root'];
        
        # Config ip address
        $this->_conf['ip_address'] = $config['ip_address'];
        
        # Config port
        $this->_conf['port'] = $config['port'];        
    }
    
    public function generate(){

        # Check document root property
        if(!array_key_exists('document_root', $this->_conf)){
            throw new \Exception("--- Error: document root not found ---\n");
        }
        
        $this->ap2 = new Ap2();

        $this->ap2->setParams(array(            
                'server_name' => $this->_conf['server_name'], 
                'document_root' => $this->_conf['document_root'],
                'port' => $this->_conf['port']
        ));

        if($this->ap2->serverExist()){
            $this->ap2->createServerBackup();
        }
        
        $this->ap2->update(); 
        
        $this->hosts = new Hosts();

        $this->hosts->setParams(array(            
                'server_name' => $this->_conf['server_name'], 
                'ip_address' => $this->_conf['ip_address'],
                'port' => $this->_conf['port']
        ));
                
        $this->hosts->update();
        
        echo "Finish process ok.\n";
    }

    public function remove(){
        
        $this->ap2 = new Ap2();

        $this->ap2->remove($this->_conf['server_name']); 
        
        $this->hosts = new Hosts();
                
        $this->hosts->remove($this->_conf['server_name']);
        
        echo "Finish process ok.\n";        
    }

    public function listHosts(){
        
        $this->hosts = new Hosts();        
        $this->hosts->listHosts();        
    }
}
