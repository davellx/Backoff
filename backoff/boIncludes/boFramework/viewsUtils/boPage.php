<?php

use boCore\modules\boModules;

/**
* Page
* static class that is used to put a little makup on the views
*
* @package views
* @author Davel_x
* @copyright Copyright (c) 2007
* @version $Id$
* @access public
*/
class boPage
{

	/**
	* @var string nav bar style
	* @static
	*/
	public static $navBarStyle = 'bootstrap';

	/**
	* @var string nav bar request name for the page number
	* @static
	*/
	public static $navPageName = 'p';

	/**
	* @var string nav bar request name for the table Order
	* @static
	*/
	public static $navOrderName = 'order';

	/**
	* boPage::Page()
	*
	* Constructor, useless
	*/
	public function boPage()
	{
	}

	/**
	* boPage::echoBaseMeta()
	*
	* includes the basic Meta, the file 'meta.php' situated in the default views directory
	*/
	public static function echoBaseMeta($scriptList=array(),$cssList=array())
	{
		include(VIEWSPATH . 'meta.php');
	}

	/**
	* boPage::echoStartStructure()
	*
	* includes the begining of the basic structure, the file 'start.php' situated in the default views directory
	*/
	public static function echoStartStructure()
	{
		include(VIEWSPATH . 'start.php');
	}

	/**
	* boPage::echoEndStructure()
	*
	* includes the end of the basic structure, the file 'end.php' situated in the default views directory
	*/
	public static function echoEndStructure()
	{
		include(VIEWSPATH . 'end.php');
	}

	/**
	* boPage::getScript()
	*
	* echoes one or more scripts tags to include the common js of the application
	*
	* currently the javascripts included automatically are :
	* - default : the default page where you can put all the scripts you want for all the pages
	* - htmleditor : currently tinyMce
	* - prototype : the common js library
	* - scriptaculous : same as prototype (note : you MUST add prototype before this one)
	* - swfobject : very useful for flashfiles, this is version 1.4 for now
	*
	* It's possible to add/delete those core script by modifying the file 'scripts.xml' in the 'js' directory of VIEWSPATH
	*
	* @link http://script.aculo.us/
	* @link http://www.prototypejs.org/
	* @link http://blog.deconcept.com/swfobject/
	* @param stric $scriptnames contains the name of the scripts to include, separated by a pipe '|'
	*/
	public static function echoScript($scriptnames)
	{
		$scriptnames = explode('|',$scriptnames);
		$scriptXML = simplexml_load_file(VIEWSPATH.'js/scripts.xml');
                $listScripts = array();
		foreach($scriptXML->script as $script){
                    $listScripts[(string)$script['name']] = (string)$script['path'];
		}
                foreach($scriptnames as $script){
                    if(!empty($script)){
                        if(array_key_exists($script,$listScripts)){
                            $script = $listScripts[$script];
                            if(strpos($script,'https://') !== 0 && strpos($script,'http://') !== 0 && strpos($script,'//') !== 0){
                                $script = VIEWSPATH.'/js/'.$script;
                            }
                        }
                        ?>
                        <script language="javascript" type="text/javascript" src="<?=$script?>" rel="forceLoad"></script>
                        <?php
                    }
                }
	}

	/**
	* boPage::getCurrentLink()
	*
	* used to get the link of the present page
	*
	* @param string $argumentsAVirer put off some arguments that are transmitted via the url, separated by the pipe char : '|'
	* 									'logout', 'install' and 'uninstall' are automatically put off
	* @return string link to the present... page
	*/
	public static function getCurrentLink($args = '',$actionvirer = false,$amperstand='&amp;')
	{
		$arguments = explode("&", $_SERVER["QUERY_STRING"]);
		$argsArray = array();
		$nbreAvirer = count($arguments);
		for($i = 0; $i < $nbreAvirer; $i++)
		{
			if($arguments[$i]!=''){
				$temp = explode('=',$arguments[$i]);
				$argsArray[$temp[0]] = $temp[1];
			}
		}
		//
		if($actionvirer)
			$args = $args . '|logout';
		$args = explode("|", $args);
		$args = array_flip($args);
		//
		$keyArray = array();
		if(!$actionvirer){
				$keyArray = array_intersect_key ($argsArray,$args);
		}else{
				$keyArray = array_diff_key ($argsArray,$args);
		}
		//
		$return_query = '';
		foreach($keyArray as $key => $value)
		{
			$return_query .= $amperstand.$key.'='.$argsArray[$key];
		}
		//$return_query = implode('&amp;', $arguments);
		$suite = '';
		if(isset($_SERVER["PATH_INFO"])){
			$suite = $_SERVER["PATH_INFO"];
		}
		return BACKURL.$suite.'?'.$return_query;
	}

	/**
	* boPage::getNavBar()
	*
	* Useful function used to generate HTML code for a nav bar
	*
	* you can change the type of bar by modifying boPage::$navBarStyle :
	* - 'simple' creates basic nav arrows (first, previous, next, end) with a 'current/total' indicator, this is the default value
	* - 'select' creates an HTML ComboBox with automatic redirect, requires javascript
  C:\wamp\www\dealer_avisdemamans\www\backoff\boModules\avisProduit\_info.php (1 hits)
	Line 15: 		'submenu'=>array('selection','categorieProduit','avis','reference'),

	* - 'allpages' creates a list of all pages number with basic nav arrows
	*
	* you can aldo change the var used to transmit the page number to the script by modifying boPage::$navPageName
	* the default value is 'p'
	*
	* @param integer $total the number total of elements
	* @param integer $current the current page
	* @return string the HTML code for the nav bar
	*/
	public static function getNavBar($total, $current)
	{
		$returnCode = '';
		$location = boPage::getCurrentLink('p|procedure',true);
		switch (boPage::$navBarStyle)
		{
		case 'select':
			$returnCode .= "<select onchange=\"javascript:window.location='" . $location . "&amp;" . boPage::$navPageName . "='+this.value;\">";
			for($i = 0;$i < $total;$i++)
			{
				$selec = ($i == $current)?'selected="selected"':'';
				$returnCode .= '<option value="' . $i . '" ' . $selec . '>' . ($i + 1) . '</option>' . "\r\n";
			}
			$returnCode .= '</select>';
			break;
		case 'allpages':
			if ($total > 1)
			{
				// echo 'test';
				if ($current != 0)
				{
					$returnCode .= '<a href="' . $location . '&amp;' . boPage::$navPageName . '=' . ($current-1) . '"><<</a> ';
				}
				else
				{
					$returnCode .= '<<';
				}
				for($i = 0;$i < $total;$i++)
				{
					if ($i != $current)
					{
						$returnCode .= '<a href="' . $location . '&amp;' . boPage::$navPageName . '=' . ($i) . '">' . ($i + 1) . '</a>';
					}
					else
					{
						$returnCode .= '<span>' . ($i + 1) . '</span>';
					}
				}
				if ($current != $total)
				{
					$returnCode .= ' <a href="' . $location . '&amp;' . boPage::$navPageName . '=' . ($current + 1) . '">>></a>';
				}
				else
				{
					$returnCode .= '>>';
				}
			}
			break;
		case 'bootstrap':
			$returnCode .= '<div class="pagination"><ul>';
			if ($total > 1)
			{
				$returnCode .= '<li';
				if($current == 0){
					$returnCode .= ' class="active" ';
				}
				$returnCode .= '><a href="'.$location.'&amp;'.boPage::$navPageName.'='.($current-1).'">«</a></li>';
				$points = true;
				for($i = 0;$i < $total;$i++)
				{
					if($i > 1 && $i < ($total-2) && $i != $current && $i != $current+1 && $i != $current-1){
						if($points){
							$returnCode .= '<li class="active" ><a href="'.$location.'&amp;'.boPage::$navPageName.'='.$i.'">...</a></li>';
							$points = false;
						}
					}
					else{
						$returnCode .= '<li';
						if($i == $current){
							$points = true;
							$returnCode .= ' class="active" ';
						}
						$returnCode .= '><a href="' . $location . '&amp;' . boPage::$navPageName . '=' . ($i) . '">' . ($i + 1) . '</a></li>';
					}
				}
				$returnCode .= '<li';
				if($current == $total - 1){
					$returnCode .= ' class="active" ';
				}
				$returnCode .= '><a href="' . $location . '&amp;' . boPage::$navPageName . '=' . ($current + 1) . '">»</a></li>';
			}
			$returnCode .= '</ul></div>';
			break;
		case 'simple':
			if ($current != 0)
			{
				$returnCode .= '<a href="' . $location;
				$returnCode .= '&amp;' . boPage::$navPageName . '=0">&lt;&lt;</a>&nbsp;';

				$returnCode .= '<a href="' .$location;
				$returnCode .= '&amp;' . boPage::$navPageName . '='.($current-1).'">&lt;</a>';
			}

			$returnCode .= '<span> Page '. ($current+1) . '/' . ($total) . ' </span>';

			if ($current != $total - 1)
			{
				$returnCode .= '<a href="' . $location;
				$returnCode .= '&amp;' . boPage::$navPageName . '='.($current+1).'">&gt;</a>&nbsp;';
				
				$returnCode .= '<a href="' . $location;
				$returnCode .= '&amp;' . boPage::$navPageName . '=' . ($total-1) . '">&gt;&gt;</a>';

			}
			break;
		case 'verysimple':
		default:
			if($total > 1){
				if($current > 0){
					$returnCode .= '<a href="'.$location.'&amp;p='.($current - 1).'">&nbsp;&lt;&nbsp;</a>';
				}
				$returnCode .= 'Page '.($current + 1).'/'.$total."\r\n";
				if($current < ($total - 1)){
					$returnCode .= '<a href="'.$location.'&amp;p='.($current + 1).'">&nbsp;&gt;&nbsp;</a>';
				}
			}
		}
		// echo $returnCode;
		return $returnCode;
	}

	/**
	 * boPage::getSelectContent()
	 *
	 * return the content of a <select>
	 *
	 */
	public static function getSelectContent($cols, $currentCol){
		$output = '';
		foreach($cols as $key=>$col){
			if($key==$currentCol){
				$output .= '<option selected="selected" value="'.$key.'">'.$col.'</option>'."\r\n";
			}else{
				$output .= '<option value="'.$key.'">'.$col.'</option>'."\r\n";
			}
		}
		$output .= "</tr>\r\n";
		return $output;
	}

	/**
	 * boPage::echoTableHeader()
	 *
	 * Generates HTML code for the thead or tfoot part of a table with links for ordering the table
	 *
	 * You can change the name of the var used to transmit the idname of the column to order by modifying boPage::$navOrderName
	 * the default value is 'order'
	 *
	 * @param array $cols list of the column name in an associative array like 'idname' => 'label name'
	 * @param string $currentCol idname of the current column
	 * @param array $notOrdered idnames of the columns that doesn't need to be ordered
	 * @return string the HTML code fot the header
	 */
	public static function getTableHeader($cols,$currentCol=NULL,$notOrdered=NULL){
		$output = "<tr>\r\n";
		foreach($cols as $key=>$col){
			if($key==$currentCol){
				$output .= "<th><span>$col</span></th>\r\n";
			}else if (is_null($notOrdered) || in_array($key,$notOrdered)){
				$output .= "<th>$col</th>\r\n";
			}else{
				$output .= "<th>$col <a href=\"".boPage::getCurrentLink('action')."&amp;order=".$key."\">Λ</a> <a href=\"".boPage::getCurrentLink('action')."&amp;order=".$key." DESC\">V</a></th>\r\n";
			}
		}
		$output .= "</tr>\r\n";
		return $output;
	}
        
        public static function echoTopMenu(){
            //global $modules;
            $linkString = BACKURL;//boPage::getCurrentLink($argumentsAVirer);
            ?>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?=BACKURL?>"><?=SITENAME?></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="<?=SITEURL?>home/accesDirect" target="_blank">Voir le site <i class="fa fa-external-link"></i></a></li>
            <?php if(isset($_SESSION['boLogin'])): ?>
            <li><a href="'.$linkString.'?&amp;logout=1">Se déconnecter <i class="fa fa-power-off"></i></a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>                        
            <?php
        }
        
	/**
	 * boPage::echoMenu()
	 *
	 * Generates a simple menu (2 levels if needed) with the links to every module, the id of the HTML list is 'menu'
	 *
	 * @param string $argumentsAVirer contains the elements that will be taken of the link (see the boPage::getCurrentLink method)
	 * 					'action' is automatically wiped out
	 * @param boolean $subMenu generate the Sub menu or not.
	 */
	public static function echoMenu(){
		global $modules;
                if(isset($_SESSION['boLogin']) &&  isset($modules->list)){
                    $currentModule = boModules::$actions['module'];
                    $linkString = BACKURL;//boPage::getCurrentLink($argumentsAVirer);
                ?>
                <ul id="main-menu-left" class="nav nav-sidebar">
                    <?php foreach($modules->list as $module): ?>
                        <?php if($_SESSION['boLevel'] > $module->access && $module->visible == true):?>
                        <li class="<?php if($currentModule == $module->id){echo "active";}?>"><a href="<?=$linkString.$module->id?>"><i class="fa fa-chevron-right"></i> <?=$module->menuname?></a></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                        <!--
                    <li>
                        <a href="'.$linkString.'?&amp;logout=1">Se déconnecter <i class="fa fa-power-off"></i></a>
                    </li>
                        -->
                </ul>
                <?php
                }
	}

	/**
	 * boPage::echoDefaultPage()
	 *
	 * Gets the default page easily
	 *
	 * @return
	 */
	public static function defaultPage(){
		require VIEWSPATH . 'default.php';
		die();
	}

}
