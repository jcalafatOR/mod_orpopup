<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2019 - 2020 openROOM. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$orPopUp_image = $params->get('pop-image-file');
$orPopUp_imageParam = !empty($params->get('pop-image-alt')) ? ' alt="'.$params->get('pop-image-alt').'" ' : "";
$orPopUp_imageParam .= !empty($params->get('pop-image-css')) ? ' class="'.$params->get('pop-image-css').'" ' : "";

$orPopUp_Metodo = $params->get('popup-metodo') == 0 ? ' mod-attr="time" ' : ' mod-attr="posy" ';
$orPopUp_Metodo .= empty($params->get('popup-metodo-valor')) ? ' mod-attrval="0" ' : ' mod-attrval="'.$params->get('popup-metodo-valor').'" ';

$orPopUp_cookies = !empty($params->get('pop-cookie-name')) ? 'rel-cookie="'.$params->get('pop-cookie-name').'" ' : 'rel-cookie="popup-default-'.$module->id.'" ';
$orPopUp_cookies .= !empty($params->get('pop-cookie-value')) ? 'rel-cookieval="'.$params->get('pop-cookie-value').'" ' : 'rel-cookieval="popup-default-value-'.$module->id.'" ';
$orPopUp_cookies .= !empty($params->get('pop-cookie-time')) ? 'rel-cookietime="'.$params->get('pop-cookie-time').'" ' : 'rel-cookietime="0" ';
$orPopUp_cookies .= $params->get('popup-desktop') == 1 ? 'rel-desk="1" ': 'rel-desk="0" ';
$orPopUp_cookies .= $params->get('popup-mobile') == 1 ? 'rel-mob="1" ': 'rel-mob="0" ';
if($params->get('pop-botton') == 1)
{
	$orPopUp_ButtonTxt = !empty($params->get('pop-botton-text')) ? $params->get('pop-botton-text') : 'Más información';
	$orPopUp_ButtonCss = !empty($params->get('pop-botton-css')) ? $params->get('pop-botton-css') : '';
	$orPopUp_ButtonTarget = !empty($params->get('pop-botton-target')) ? 'target="'.$params->get('pop-botton-target').'" ' : '';
	$orPopUp_ButtonUrl = !empty($params->get('pop-botton-url')) ? ' href="'.$params->get('pop-botton-url').'"' : ' href="#"';
}

$orVideoPopUp_activador = $params->get('activador') == 1 ? "openVideoPopUp ": "";

$orTemplate_xml = JFactory::getXML(JPATH_SITE .'/templates/openroom/templateDetails.xml');
$orTemplate_version = orTemplate_v4popup((string)$orTemplate_xml->version);


if(!$orTemplate_version){
 ?>
<script>
if(typeof orPopup_setCookie === 'undefined' )
{    
    function orPopup_setCookie(cname, cvalue, exdays)
    {
        if(exdays > 0)
        {
            var d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            var expires = "expires="+d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";";
        }
        else
        {
            document.cookie = cname + "=" + cvalue + ";";
        }
    }
}
if(typeof orPopup_getCookie === 'undefined' )
{  
    function orPopup_getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
} 
 
if(typeof orPopup_checkCookie === 'undefined' )
{  
    function orPopup_checkCookie(cname, valor)
    {
        var salida = false;
        if(orPopup_getCookie(cname) == valor){ salida = true; }
        return salida;
    }
}    
    
if(typeof orPopup_checkCookieTime === 'undefined' )
{  
    function orPopup_checkCookieTime(cname, exdays)
    {
        var salida = false;
        if(orPopup_getCookie(cname) !== "")
        { 
            salida = parseInt(orPopup_getCookie(cname)); 
        }else
        {
            orPopup_setCookie(cname, 0, exdays);
            salida = 0;
        }

        return salida;
    }
}    

if(typeof orPopup_lanzar === 'undefined' )
{  
    function orPopup_lanzar(target)
    {
        jQuery('.body').addClass('overflow-hiden');
		jQuery(target).addClass('or-fullCover');
    }
}    
        
  
   
if(typeof orPopupClose === 'undefined' ) { function orPopupClose(e) { be_closeCover(e); } }
jQuery(document).ready(function()
{
	
    jQuery('.or_popup').each(function() {
		var orPop = jQuery(this);
		var orPop_desk = orPop.attr('rel-desk');
		var orPop_mobile = orPop.attr('rel-mob');
		var orPop_cookieName = orPop.attr('rel-cookie');
		var orPop_cookieValue = orPop.attr('rel-cookieval');
		if(!orPopup_checkCookie(orPop_cookieName, orPop_cookieValue)
          && (orPop_mobile == 1 || orPop_mobile == 0 && !checkMobileSize())
          && ( (orPop_desk == 1 || orPop_desk == undefined) || orPop_desk == 0 && checkMobileSize()) )
		{
			var orPop_cookieTime = orPop.attr('rel-cookietime');
			var metodo = orPop.attr('mod-attr');
			var metodoValor = orPop.attr('mod-attrval');
            var cuentaAtras = parseInt(metodoValor - orPopup_checkCookieTime('orpopup_timeController', exdays));
            var orpopup_timeController = setInterval(function(){
                orPopup_setCookie('orpopup_timeController', orPopup_checkCookieTime('orpopup_timeController', exdays) + 1000, exdays);
            }, 1000);
			if(metodo == "time" && cuentaAtras > 0) // Time
			{
				setTimeout(function()
                { 
                    orPopup_lanzar(orPop); 
                    orPopup_setCookie(orPop_cookieName, orPop_cookieValue, orPop_cookieTime);
                    clearInterval(orpopup_timeController);
                    orPopup_setCookie('orpopup_timeController', 0, -1000);
                }, cuentaAtras);
                return false;
			}
		}
	});
	jQuery( window ).scroll(function() 
	{
        jQuery('.or_popup').each(function() {
            var orPop = jQuery(this);
            var orPop_mobile = orPop.attr('rel-mob');
            var orPop_cookieName = orPop.attr('rel-cookie');
            var orPop_cookieValue = orPop.attr('rel-cookieval');
            if(!orPopup_checkCookie(orPop_cookieName, orPop_cookieValue)
                && (orPop_mobile == 1 || orPop_mobile == 0 && !checkMobileSize())
                && ( (orPop_desk == 1 || orPop_desk == undefined) || orPop_desk == 0 && checkMobileSize()) )
            {
                var orPop_cookieTime = orPop.attr('rel-cookietime');
                var metodo = orPop.attr('mod-attr');
                var metodoValor = orPop.attr('mod-attrval');
                if(metodo != "time") //posicion
                {
                    var percent = metodoValor.includes("%");
                    var valor = parseInt(metodoValor);
                    lanzar = false;
                    if( (percent && (jQuery(window).scrollTop() >= (jQuery(document).height() - jQuery(document).height()*(valor*0.01)))) ||
                        (!percent && (jQuery(window).scrollTop() >= valor)) )
                    {
                        orPopup_lanzar(orPop);
                        orPopup_setCookie(orPop_cookieName, orPop_cookieValue, orPop_cookieTime);
                    }
                }	
            }
            
        });
	});
});
 </script>
 <?php } ?>
 
<div class="or_popup <?php echo $moduleclass_sfx; ?>" id="popup_<?php echo $module->id; ?>" <?php echo $orPopUp_cookies.$orPopUp_Metodo; ?> >
    <div class="or-modal-container">
        <div class="or-modal-header">
            <div class="or-close" rel="#popup_<?php echo $module->id; ?>" onclick="return orPopupClose('#popup_<?php echo $module->id; ?>');">
                <span class=""><i class="oricon-close"></i></span>
            </div>
        </div>
        <div class="or-modal-body">
            <?php if($params->get('pop-image') == 1){ ?>
            <div class="orPopUp-img"><img loading="lazy" src="<?php echo $orPopUp_image; ?>" <?php echo $orPopUp_imageParam; ?> /></div>
            <?php } ?>
            <div class="orPopUp-content"><?php echo $module->content; ?></div>
        </div>
        <div class="or-modal-footer">
			<?php if($params->get('pop-botton') == 1) { ?>
            <a id="orpopup-btn-<?php echo $module->id; ?>" <?php echo $orPopUp_ButtonUrl; ?> <?php echo $orPopUp_ButtonTarget; ?> class="btn <?php echo $orPopUp_ButtonCss; ?>"><?php echo $orPopUp_ButtonTxt; ?></a>
            <?php } ?>
        </div>
    </div>
</div>
