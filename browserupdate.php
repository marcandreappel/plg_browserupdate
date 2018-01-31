<?php
/**
 * @package     Joomla.plugin
 * @subpackage  System.browserupdate
 *
 * @author      Marc-André Appel <marc-andre@hybride-conseil.fr>
 * @copyright   2018 © SARL Hybride Conseil
 * @license     LGPL-3.0; http://opensource.org/licenses/LGPL-3.0
 */

defined('_JEXEC') or die;

/**
 * Class PlgSystemBrowserUpdate
 *
 * @since       3.2
 */
class plgSystemBrowserupdate extends JPlugin
{
	/**
	 * @var     boolean
	 * @since   3.1
	 */
	protected $autoloadLanguage = true;

	public function onBeforeCompileHead()
	{
		$document = JFactory::getDocument();

		if (strcmp(substr(JURI::base(), -15), "/administrator/")!=0)	// Appliquer seulement au frontend.
		{
			$ie_edge = $this->params->get('hc-browser-update-ie-edge-version');
			$firefox = $this->params->get('hc-browser-update-firefox-version');
			$opera = $this->params->get('hc-browser-update-opera-version');
			$safari = $this->params->get('hc-browser-update-safari-version');
			$chrome = $this->params->get('hc-browser-update-chrome-version');

			$insecure = ($this->params->get('hc-browser-update-insecure-versions')) ? ',insecure:true' : '';
			$unsupported = ($this->params->get('hc-browser-update-unsupported-versions')) ? ',unsupported:true' : '';
			$mobile = ($this->params->get('hc-browser-update-mobile-browsers')) ? '' : ',mobile:false';
			switch ($this->params->get('hc-browser-update-style')) {
				case 'bottom':
					$style = ',style:"bottom"';
					break;
				case 'corner':
					$style = ',style:"corner"';
					break;
				case 'top':
				default:
					$style = '';
			}

			ob_start();
			echo <<<EOT
var \$buoop = {notify:{i:$ie_edge,f:$firefox,o:$opera,s:$safari,c:$chrome}$insecure $unsupported $mobile $style,api:5}; 
function \$buo_f(){ 
 var e = document.createElement("script"); 
 e.src = "//browser-update.org/update.min.js"; 
 document.body.appendChild(e);
};
try {document.addEventListener("DOMContentLoaded", \$buo_f,false)}
catch(e){window.attachEvent("onload", \$buo_f)}

EOT;

			$js = ob_get_contents();

			$document->addScriptDeclaration($js);
			ob_end_clean();
		}
	}
}