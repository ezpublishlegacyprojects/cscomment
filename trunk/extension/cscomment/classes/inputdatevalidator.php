<?

class inputvalid {
	
	var $inputArr;
	var $maxLenght;
	var $minLenght;
	var $lenghtError = array();
	
	function inputvalid($inputArr) {
			$this->inputArr = $inputArr;
			
			global $conf;
			$this->maxLenght = $conf['usernamemax'];
			$this->minLenght = $conf['usernamemin'];
			
			//debug_vars($this->inputArr);
	}
	
	function getInputArr(){
		return $this->inputArr;
		
	}
	
	function appendPair(array $array)
	{
		$this->inputArr = array_merge($array,$this->inputArr);
	}
	
	/**
	 * Set min max Lenghts
	 * */
	function setMinMaxLenghts($minLenght,$maxLenght){
		$this->maxLenght = $maxLenght;
		$this->minLenght = $minLenght;
	}
	
	/**
	 * Validates all data
	 * */
	function validArgs($argname = null,$validateOption = '1'){
		
		$ValOption = array(
			1 => 'addslashes',
			2 => 'addslhtmlen'
		);
		
		if ($argname)
			return $this->isValidGeneral($this->inputArr[$argname]);
		else 
		{
			foreach ((array)$this->inputArr as $key => $value)
			{
				
				if ($ValOption[$validateOption] == 'addslashes')
					$this->inputArr[$key] = addslashes($this->inputArr[$key]);
				elseif ($ValOption[$validateOption] == 'addslhtmlen')
					$this->inputArr[$key] = addslashes(htmlspecialchars($this->inputArr[$key]));
			}
		}	
	}
	
	/**
	 * Returns true if input lenght equals addshashed and htmlentities value
	 * */
	function isValidGeneral($valueName = null, $inptsearch = false){
		
		if ($inptsearch)
		{		
			if (strlen($valueName) != strlen(addslashes(htmlspecialchars($valueName))) )
			{
				return false;
			}
				return true;
		}
		else 
		{
			if (strlen($this->inputArr[$valueName]) != strlen(addslashes(htmlspecialchars($this->inputArr[$valueName]))) )
			{
				return false;
			}
				return true;
				
		}
		
	}
	
	
	function isNumber($keyName, $params = array())
	{
		if (strlen(trim($this->inputArr[$keyName])) == 0)
		{
			return false;			
		} else {
			if (is_numeric($this->inputArr[$keyName]))
				return true;
			else 
				return false;
		}
	}
	
	/**
	 * Is equal
	 * */
	function isEqual($name1,$name2){

		if ($this->inputArr[$name1] == $this->inputArr[$name2])	
			return true;
			else 
			return false;
	}
	
	/**
	 * If suplied names. then validates namnes. If not validates 
	 * All input array
	 * */
	function validateLenghts($namesArr = array() ){
		$error =  false;
		
		if ($namesArr)		
		{
			foreach ($namesArr as $name)
			{
				if ($this->validateLenghtSingle($this->inputArr[$name],$this->minLenght,$this->maxLenght) == false)
				 $this->lenghtError[] = $name;
				 $error = true;
			}
		}else 
		{

			foreach ($this->inputArr as $key=>$value)
			{
				
				if ($this->validateLenghtSingle($this->inputArr[$key],$this->minLenght,$this->maxLenght) == false)
				{	
				
					$this->lenghtError[] = $key;
					$error = true;
				}
			}
		}
		if ($error)	
			return $this->lenghtError;
			
		return false;	
	}
	
	/**
	 * Validates suplied arguments lenght
	 * */
	function validateLenghtSingle($value,$minLenght,$maxLenght){
		
		if (strlen($value) < $minLenght || strlen($value) > $maxLenght)
		{
			return false;
		}
		return true;
		
	}
	
	function isValidEmail($mailname){
		
		if (eregi("^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,4}$", $this->inputArr[$mailname]))
			return true;
			else 
			return false;
	}
	
	function isValueEqual($fieldname,$value){
		if ($this->inputArr[$fieldname] == $value)
		return true;
		else 
		return false;
	}
	
	function getInputValue($fieldname){
		return $this->inputArr[$fieldname];
	}
	
	function isCorrectPhone($fieldname)
	{
		return preg_match('/^[+][0-9]{11}+$/', $this->inputArr[$fieldname]) ? true : false;
	}
		
	function issetvalue($fieldname){
		if (isset($this->inputArr[$fieldname]) && strlen($this->inputArr[$fieldname]) > 0) return true;
		
		return false;
	}
	
	
}

?>