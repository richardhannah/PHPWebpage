<?php
class Webpage extends DOMImplementation {
    private $docType;
	private $dom;
	private $html;
	private $head;
	private $title;
	private $body;
	
	
	
	
	
  
	function Webpage(){
		$this->docType = $this->createDocumentType( 'html', '', '' );
		$this->dom = $this->createDocument ( '', 'html', $this->docType );
		$rootNodes = $this->dom->getElementsByTagName('html');

		foreach($rootNodes as $node){
		$this->html = $node;
		}
		
		
		$this->head = $this->dom->createElement ( 'head' );
		$this->html->appendChild($this->head);
		
		$this->body = $this->dom->createElement ( 'body' );
		$this->html->appendChild($this->body);
		
		$this->title = $this->dom->createElement ( 'title' );
		$this->head->appendChild($this->title);
		
		
		
		
		$this->dom->formatOutput = TRUE;
		$this->dom->preserveWhiteSpace = FALSE; 
	}
  
  
	//DOMDocument::getElementById doesn't play nicely with HTML5 - this function works around it.
	function getElementById($id)
	{
		$xpath = new DOMXPath($this->dom);
		return $xpath->query("//*[@id='$id']")->item(0);
    }
	
	public function addFragmentFromFile($fileName,$parent){
	
		$handle = fopen($fileName, "r");
		$contents = fread($handle, filesize($fileName));
		fclose($handle);
	
		$frag = $this->dom->createDocumentFragment();
		$frag->appendXML($contents);
		
		switch($parent){
			case 'head':
			$findHead = $this->dom->getElementsByTagName($parent);
				foreach($findHead as $headnode){
					$parentElement = $headnode;
					}
			
			break;
			case 'body':
			$findBody = $this->dom->getElementsByTagName($parent);
			foreach($findBody as $bodynode){
					$parentElement = $bodynode;
					}
			break;
			default:
			$parentElement = $this->getElementById($parent);
			}
		
		$parentElement->appendChild($frag);
		
	
	}
	
	public function addStyleSheet($stylesheetURL){
		
	$this->addElement("head","link",Array(
		"rel"=> "stylesheet",
		"type"=> "text/css",
		"href"=> $stylesheetURL));
	
	}
	
	
	
	
	public function addElement($parent,$tag,$attrs=null){
		$parentElement = "";
		$element = $this->dom->appendChild(new DOMElement($tag));
			if($attrs != null){
				while ($attribute = current($attrs)) {
				$element->setAttribute(key($attrs),$attrs[key($attrs)]);
				next($attrs);
				}
			}
			
			switch($parent){
			case 'head':
			$findHead = $this->dom->getElementsByTagName($parent);
				foreach($findHead as $headnode){
					$parentElement = $headnode;
					}
			
			break;
			case 'body':
			$findBody = $this->dom->getElementsByTagName($parent);
			foreach($findBody as $bodynode){
					$parentElement = $bodynode;
					}
			case 'title':
			$findBody = $this->dom->getElementsByTagName($parent);
			foreach($findBody as $titlenode){
					$parentElement = $titlenode;
					}
			break;
			default:
			$parentElement = $this->getElementById($parent);
			}
		
		$parentElement->appendChild($element);
	}
	
	public function setElementText($elementId,$text){
	
		if($elementId == 'title'){
			$findBody = $this->dom->getElementsByTagName($elementId);
			foreach($findBody as $titlenode){
					$titlenode->nodeValue=$text;
					}
			
			
		}
		else{
			$element = $this->getElementById($elementId);
			$element->nodeValue=$text;
		}
	}
	
  
    public function display () {
		echo $this->dom->saveHTML();
    }
}
?>