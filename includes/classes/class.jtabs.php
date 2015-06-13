<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );
class jTabs {
	/*
	effect : scale / slideleft / slideright / slidetop / slidedown / none
	Tabs position: horizontal / vertical
	// Tabs horizontal position: top / bottom
	// Tabs vertical position: left / right
	// BETA: Make tabs container responsive: true / false (!!! BETA)
	// Themes available: default: '' / pws_theme_violet / pws_theme_green / pws_theme_yellow / pws_theme_gold /
	  // pws_theme_orange / pws_theme_red / pws_theme_purple / pws_theme_grey
	  
	// Right to left support: true/ false
	
	*/
	
	function jTabs($effect='none', $defaultTab=1, $containerWidth='100%', $tabsPosition='vertical',$horizontalPosition='top', $verticalPosition='left', $theme='pws_theme_grey', $rtl='false', $responsive='false') {
		
		echo "<script src='".SITEURL."/includes/js/jtabs/jtabs.min.js'></script>\n";
		$html = "<script type='text/javascript'>\n";
		$html.= "jQuery(document).ready(function($){\n";
		$html.= "$('.jtabs').pwstabs({\n";
		$html.= "effect: '".$effect."',\n";
		$html.= "defaultTab: '".$defaultTab."',\n";
		$html.= "containerWidth: '".$containerWidth."',\n";
		$html.= "tabsPosition: '".$tabsPosition."',\n";
		$html.= "horizontalPosition: '".$horizontalPosition."',\n";
		$html.= "verticalPosition: '".$verticalPosition."',\n";
		$html.= "responsive: ".$responsive.",\n";
		//$html.= "theme: '".$theme."',\n";
		$html.= "rtl: ".$rtl."\n";
		$html.= "});\n";
		$html.= "});\n";
		$html.= "</script>\n\n";
		
		echo $html;

	}
	
	function startjPane() {
		echo '<div class="jtabs">';    
	}
	
	function endjPane() {
		echo '</div>';
	}
	
	function startjTab($row) {
		echo '<div data-pws-tab="tab'.$row->id.'" data-pws-tab-name="'.$row->name.'">';
	}
	
	function endjTab() {
		echo '</div>';
	}
}
