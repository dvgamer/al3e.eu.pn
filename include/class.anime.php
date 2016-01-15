<?php
class Anime
{
	protected $iFolderName;
	public $iPathName = "";
	public $Drop = false; 	// !!Drop, !#Raw
	public $Name = array("en"=>NULL);
	public $Episode = array("en"=>NULL);
	public $Fansub = array();
	public $Sizes = 0;
	public $Items = 0;
	public $ChapterNow = 0;
	public $ChapterEnd = 0;
	public $Type = NULL;			 // TV Series, OVA, OAD, ONA, Movie, SP
	public $Release = "TV";		  // SD, PAL, NTSC, Blu-ray  [PAL=4:3, NTSC=16:9 (<720p), SD=VCD]
	public $Resolution = "720p";	 // , 720p, 1080p  
	public $Audio = array("JPN");		// JPN, THA, ENG
	public $Subtitle = array("THA");		// THA, KOR, JPN, ENG, NONE
	public $Year = 0;
	public $Season = 0;	// Spring, Summer, Autumn, Winter

	public function __construct($path)
	{
		$path = str_replace("\\\\", "", str_replace("/", "", $path));		
		$this->iPathName = $path;
		while(substr($path, strlen($path)-1,1)=="\\") { $path = substr($path, 0, strlen($path)-1); }
		$subFolder = explode("\\", $path);
		$this->iFolderName = trim($subFolder[count($subFolder)-1]);
		// Check in Directory for Database
		
		$iAnime = opendir($this->iPathName);
		while(($iList = readdir($iAnime))!==false)
		{
			if(is_file($this->iPathName.$iList)) {
				$this->Sizes += (float)filesize($this->iPathName.$iList);
				$this->Items++;
				$iChar = 0;
				$iFansub = "";
				$iFound = false;
				while($iChar<strlen($iList))
				{
					$chkchr = substr($iList, $iChar, 1);
					if(ord(strtolower($chkchr))==ord('[')) {
						$iFound = true;
						$iChar++;
					} elseif(ord(strtolower($chkchr))==ord(']')) {
						$iFound = false;
						$iDifferent = true;
						foreach($this->Fansub as $name) {
							if(strtolower($name)===strtolower(trim($iFansub))) $iDifferent = false;
						}
						if($iDifferent) $this->Fansub[] = trim($iFansub);
						break;
					}
					if($iFound) {
						$chkchr = substr($iList, $iChar, 1);	
						$iFansub .= $chkchr;				
					}
					$iChar++;
				}
			}
		}
		sort($this->Fansub);
		
		$iChar = 0;
		$iEpisode = false;
		$iDataName = "";
		// Read a Name Folder for Database
		while($iChar<strlen($this->iFolderName))
		{
			$iType = 0;
			$chk = substr($this->iFolderName, $iChar, 1);
			if(ord(strtolower($chk))==ord('(') || ord(strtolower($chk))==ord('[') || ord(strtolower($chk))==ord('{')) {
				$iType = $iChar;
				if(ord(strtolower($chk))==ord('(')) {	
					while($iType<strlen($this->iFolderName)) {	
						$iType++;					
						$chkType = substr($this->iFolderName, $iType, 1);
						if(ord($chkType)==ord(')')) {
							// Database Insert (Type)
							$this->Type = substr($this->iFolderName, ($iChar+1), ($iType-$iChar-1));							
							$iChar = $iType;
							break;
						}						
					}
				} elseif(ord(strtolower($chk))==ord('[')) {					
					while($iType<strlen($this->iFolderName)) {
						$iType++;						
						$chkType = substr($this->iFolderName, $iType, 1);
						if(ord($chkType)==ord(']')) {
							$chkCharFirst = substr($this->iFolderName, ($iChar+1), 1);
							$chkCharLast = substr($this->iFolderName, ($iType-1), 1);
							if(ord($chkCharFirst)>=ord(0) && ord($chkCharFirst)<=ord(9) && (ord($chkCharLast)==ord('#') || (ord($chkCharLast)>=ord(0) && ord($chkCharLast)<=ord(9)))) {
								// Database Insert [Ongoing!End]
								list($this->ChapterNow, $this->ChapterEnd) = explode("!", substr($this->iFolderName, ($iChar+1), ($iType-$iChar-1)));
								$iChar = $iType;
								break;
							} else {
								// Database Insert [$Release $Resolution.$Lang1-$Lang2!$Sub1-$Sub2]
								list($tmpVideo, $tmpLanguage) = explode(".", substr($this->iFolderName, ($iChar+1), ($iType-$iChar-1)));
								list($this->Release, $this->Resolution) = explode(" ", $tmpVideo);
																
								if(!$tmpLanguage) {
									list($tmpAudio, $tmpSubtitle) = explode("!", $tmpVideo);
									if(!$tmpSubtitle)
									{
										if($tmpAudio!="RAW") $tmpSubtitle = "THA"; else $tmpSubtitle = "NONE";
										$tmpAudio = "JPN";
									}
								} else {									
									list($tmpAudio, $tmpSubtitle) = explode("!", $tmpLanguage);
									if($tmpAudio=="RAW") {
										$tmpAudio = "JPN";
										$tmpSubtitle = "NONE";
									}
								}	
								if(!$this->Resolution)
								{	
									if(!$this->Release || ((int)$this->Release)<1) {										
										$this->Resolution = "720p";
									} else {
										$this->Resolution = $this->Release;
										$this->Release = "TV";
									}
									if($this->Release=="Blu-ray") $this->Resolution = "1080p";
								}
								
								$this->Audio = explode("-", $tmpAudio);
								$this->Subtitle = explode("-", $tmpSubtitle);
								$iChar = $iType;
								break;
							}
						}
					}
				} elseif(ord(strtolower($chk))==ord('{')) {
					while($iType<strlen($this->iFolderName)) {
						$iType++;
						$chkType = substr($this->iFolderName, $iType, 1);
						if(ord($chkType)==ord('}')) {
							$chkChar = substr($this->iFolderName, ($iChar+1), 1);
							if(ord($chkChar)>=ord(0) && ord($chkChar)<=ord(9)) {
								// Database Insert {$Year-$Season}
								list($this->Year, $this->Season) = explode("-", substr($this->iFolderName, ($iChar+1), ($iType-$iChar-1)));
								$this->Season = ((int)$this->Season);
								$this->Year = ((int)$this->Year);
								$iChar = $iType;
								break;
							} else {
								if(!$iEpisode) {
									$this->Name['th'] = iconv("tis-620","utf-8",substr($this->iFolderName, ($iChar+1), ($iType-$iChar-1)));
								} else {
									$this->Episode['th'] = iconv("tis-620","utf-8",substr($this->iFolderName, ($iChar+1), ($iType-$iChar-1)));
								}
								$iChar = $iType;
								break;
							}
						}
					}
				}
				
			} else {
				$iDataName .= $chk;
				if($chk===chr(149)) $iEpisode = true;
			}
			$iChar++;
		}
		if($this->Type) $this->Release = "DVD";

		// Name Anime Splin
		$Symbol = substr($iDataName, 0, 1);		
		if(ord($Symbol)==ord("!") || ord($Symbol)==ord("#")) {
			$iDataName = substr($iDataName, 1, strlen($iDataName));
			if(ord($Symbol)==ord("!")) $this->Drop = true;
		}
		list($this->Name['en'], $this->Episode['en']) = explode(chr(149), $iDataName);
		if(isset($this->Name['en'])) $this->Name['en'] = trim($this->Name['en']);
		if(isset($this->Episode['en'])) $this->Episode['en'] = trim($this->Episode['en']);
	}
	
	public static function Season($data)
	{
		$result = "Unknow";
		switch($data)
		{
			case 0: $result = "Unknow"; break;
			case 1: $result = "Spring"; break;
			case 2: $result = "Summer"; break;
			case 3: $result = "Autumn"; break;
			case 4: $result = "Winter"; break;
			case "Unknow": $result = 0; break;
			case "Spring": $result = 1; break;
			case "Summer": $result = 2; break;
			case "Autumn": $result = 3; break;
			case "Winter": $result = 4; break;
		}
		return $result;
	}
	public static function Language($data = array())
	{
		$iString = "";
		foreach($data as $key=>$value) {
			switch($value) {
				case "THA": $iString .= "Thai"; break;
				case "ENG": $iString .= "English"; break;
				case "JPN": $iString .= "Japan"; break;
				case "KOR": $iString .= "Korea"; break;
				case "NONE": $iString .= "None"; break;
			}
			if($key<(count($data)-1)) $iString .= ", ";
		}
		return $iString;
	}
	public static function Size($bytes)
	{
		$iUnit = array(" Bytes"," KB"," MB"," GB"," TB");
		$iSize = $bytes;
		$iIndex = 0;
		while($iSize>1024) {
			$iSize = ($iSize / 1024);		
			$iIndex++;			
		}
		
		return round($iSize,2).$iUnit[$iIndex];
	}
}
?>