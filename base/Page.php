<?php

	class Page{
		var $Title;
		var $Keyword;
		var $Content;
		
		function Set($title, $keyword, $content){
			$this->Title = $title;
			$this->Keyword = $keyword;
			$this->Content = $content;
		}
		
		function Get(){
			echo '<p>'.$this->Title.'</p>';
		}
		
		function SetTitle($title){
			$this->Title = $title;
		}
		
		function GetTitle(){
			return $this->Title;
		}
		
		function SetKeyword($keyword){
			$this->Keyword = $keyword;
		}
		
		function GetKeyword(){
			return $this->Keyword;
		}
		
		function SetContent($content){
			$this->Content = $content;
		}
		
		function GetContent(){
			return $this->Content;
		}
	}

?>