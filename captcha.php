<?php
/**
 * Brincando com captcha
 *
 * @see http://www.botecodigital.info/php/criando-um-captcha-em-php/
 * @see Leia mais em: Captcha Simples com PHP http://www.devmedia.com.br/captcha-simples-com-php/17444
 *
 *
 * Drop shadow 
 * @see http://www.experts-exchange.com/Web_Development/Web_Languages-Standards/PHP/Q_26607008.html
 *
 * @author Eric S. Lucinger Ruiz <eu@ericruiz.com.br>
 * @version 28-ago-2013
 */
class Captcha
{
    // diretório onde as fontes estão
    CONST FONTS_PATH = './fonts/';
    
    public $palavra;
    public $largura;
    public $altura;
    public $tamanho_fonte;
    public $quantidade_letras;
    
    private $_imagem;
    private $_fonts;
    
    public function __construct($palavra = '', $largura = 400, $altura = 200, $tamanho_fonte = 50, $quantidade_letras = 6) 
    {
        if (!extension_loaded('gd') || !function_exists('gd_info')) {
            // PHP GD não está instalado!
            die('Error');
        }
        
        if (!is_readable(self::FONTS_PATH)) {
            // não foi possível ler o diretório das fontes!
            die('Error');
        }
        
        $this->palavra = $palavra;
        $this->largura = $largura;
        $this->altura = $altura;
        $this->tamanho_fonte = $tamanho_fonte;
        $this->quantidade_letras = (strlen($palavra)) ? strlen($palavra) : $quantidade_letras;
        
        $this->_fonts = $this->_getFonts();
    }
    
    public function getImage()
    {
        // define a largura e a altura da imagem
        $this->_imagem = imagecreate($this->largura, $this->altura);
        
        // define a cor branca
        $branco = imagecolorallocatealpha($this->_imagem,244,244,244,127); 
       
        // define a palavra conforme a quantidade de letras definidas no parametro $quantidade_letras
        if (!$this->palavra) {
            $this->palavra = $this->randomizeWord($this->quantidade_letras);
        }
        
        // numero de letras varia entre 70~100% de $quantidade_letras
        // $quantidade_letras = rand(($quantidade_letras*70)/100, $quantidade_letras);
        
        
        // para cada letra da palava
        for ($i = 1; $i <= $this->quantidade_letras; $i++) {
            // sorteia uma das fontes (mesma fonte para palavra toda)
            $_fonte = (!isset($_fonte)) 
                    ? self::FONTS_PATH . $this->_fonts[rand(0, count($this->_fonts)-1)]
                    : $_fonte;
            
            // sorteia uma cor
            $corFonte = imagecolorallocate($this->_imagem, rand(0,255), rand(0,255), rand(0,255));
            
            // atribui as letras a imagem
            imagettftext($this->_imagem,
                        // tamanho da fonte varia entre 75~125% de $tamanho_fonte
                        rand(($this->tamanho_fonte*75)/100, ($this->tamanho_fonte*125)/100),
                        rand(-50,50), ($this->tamanho_fonte*$i),
                        ($this->tamanho_fonte + 60 ), $corFonte, $_fonte,
                        substr($this->palavra, ($i-1), 1));
        }
        
        /* Shadow **
           imagealphablending($this->_imagem, true);
           imagefilter($this->_imagem, IMG_FILTER_GAUSSIAN_BLUR);
           imagefilter($this->_imagem, IMG_FILTER_GAUSSIAN_BLUR);
           imagefilter($this->_imagem, IMG_FILTER_GAUSSIAN_BLUR);
        */
        
        // gera a imagem
        ob_start();
        imagepng($this->_imagem);
        $contents = ob_get_contents();
        ob_end_clean();
        
        // limpa a imagem da memoria
        imagedestroy($this->_imagem);
        return base64_encode($contents);
    }

    public function randomizeWord($length = 7, $chars = null)
    {
        $chars = ($chars == null)
               ? 'AaBbCcDdEeFfGgHhIiJj'
               . 'KkLlMmNnPpQqRrSsTtUu'
               . 'VvYyXxWwZz0123456789'
               : $chars;
               
        return substr(str_shuffle($chars), 0, $length);
    }

    private function _getFonts()
    {
        $fonts = array();
        foreach (scandir('./fonts') as $font) {
            if ($font != '.' && $font != '..') {
               $fonts[] = $font;
            }
        }
        
        if (count($fonts) == 0) {
            // Nenhuma fonte encontrada no diretório!
            die('Erro');
        }
        
        return $fonts;
    }
}
