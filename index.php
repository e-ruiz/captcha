<?php
/**
 * Brincando com captcha
 *
 * @see http://www.botecodigital.info/php/criando-um-captcha-em-php/
 * @see Captcha Simples com PHP http://www.devmedia.com.br/captcha-simples-com-php/17444
 *
 *
 * Drop shadow 
 * @see http://www.experts-exchange.com/Web_Development/Web_Languages-Standards/PHP/Q_26607008.html
 *
 * @author Eric S. Lucinger Ruiz <eu@ericruiz.com.br>
 * @version 28-ago-2013
 */

// session_start(); // inicial a sessao
//header("Content-type: image/png"); // define o tipo do arquivo


include_once('captcha.php');


// exemplos de uso

// default
$captcha = new Captcha();
$captcha->quantidade_letras = 8;
$captcha->largura = 800;
$captcha->tamanho_fonte = 70;
$image = $captcha->getImage();

// palavra random, 500x150, fonte 30, 14 caracteres
$captcha = new Captcha(null, 420, 150, 25, 14);
//$image = $captcha->getImage();

// palavra definida, 500x180
$captcha = new Captcha('CapTCha!', 500, 180);
//$image = $captcha->getImage();

echo '<img style="border:1px solid #444;" alt="captcha" title="captcha" src="data:image/png;base64,' . $image . '" />';
?>
