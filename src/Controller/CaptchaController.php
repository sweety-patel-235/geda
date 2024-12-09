<?php
/**
 * captcha/src/Controller/CaptchaController.php
 * @author     Arvind Kumar
 * @link       https://captcha.inimisttech.com
 * @copyright  Copyright © 2019 https://inimisttech.com
 * @version 1.1 - Tested with Cakephp 3.8.x
 */

namespace App\Controller;

use App\Controller\AppController;

/**
 * Captcha Controller
 *
 */
class CaptchaController extends AppController
{
    public function initialize()
    {
      parent::initialize();
      //$this->loadComponent('Captcha.Captcha');
    }
    public function create()	{
        $captcha_code = '';
$captcha_image_height = 50;
$captcha_image_width = 130;
$total_characters_on_image = 6;

//The characters that can be used in the CAPTCHA code.
//avoid all confusing characters and numbers (For example: l, 1 and i)
$possible_captcha_letters = 'bcdfghjkmnpqrstvwxyz23456789';
$captcha_font  =URL_HTTP.'/img/droidsans.ttf';

$random_captcha_dots = 50;
$random_captcha_lines = 25;
$captcha_text_color = "0x142864";
$captcha_noise_color = "0x142864";


$count = 0;
while ($count < $total_characters_on_image) { 
$captcha_code .= substr(
    $possible_captcha_letters,
    mt_rand(0, strlen($possible_captcha_letters)-1),
    1);
$count++;
}

$captcha_font_size = $captcha_image_height * 0.65;
$captcha_image = @imagecreate(
    $captcha_image_width,
    $captcha_image_height
    );

/* setting the background, text and noise colours here */
$background_color = imagecolorallocate(
    $captcha_image,
    255,
    255,
    255
    );

$array_text_color = $this->hextorgb($captcha_text_color);
$captcha_text_color = imagecolorallocate(
    $captcha_image,
    $array_text_color['red'],
    $array_text_color['green'],
    $array_text_color['blue']
    );

$array_noise_color = $this->hextorgb($captcha_noise_color);
$image_noise_color = imagecolorallocate(
    $captcha_image,
    $array_noise_color['red'],
    $array_noise_color['green'],
    $array_noise_color['blue']
    );

/* Generate random dots in background of the captcha image */
for( $count=0; $count<$random_captcha_dots; $count++ ) {
imagefilledellipse(
    $captcha_image,
    mt_rand(0,$captcha_image_width),
    mt_rand(0,$captcha_image_height),
    2,
    3,
    $image_noise_color
    );
}

/* Generate random lines in background of the captcha image */
for( $count=0; $count<$random_captcha_lines; $count++ ) {
imageline(
    $captcha_image,
    mt_rand(0,$captcha_image_width),
    mt_rand(0,$captcha_image_height),
    mt_rand(0,$captcha_image_width),
    mt_rand(0,$captcha_image_height),
    $image_noise_color
    );
}

/* Create a text box and add 6 captcha letters code in it */
$text_box = imagettfbbox(
    $captcha_font_size,
    0,
    $captcha_font,
    $captcha_code
    ); 
$x = ($captcha_image_width - $text_box[4])/2;
$y = ($captcha_image_height - $text_box[5])/2;
imagettftext(
    $captcha_image,
    $captcha_font_size,
    0,
    $x,
    $y,
    $captcha_text_color,
    $captcha_font,
    $captcha_code
    );

/* Show captcha image in the html page */
// defining the image type to be shown in browser widow
header('Content-Type: image/jpeg'); 
imagejpeg($captcha_image); //showing the image
imagedestroy($captcha_image); //destroying the image instance
$_SESSION['captcha'] = $captcha_code;
      //  $this->loadComponent('Captcha.Captcha'); //or load on the fly!
       // $this->layout='ajax';
      //  $this->viewBuilder()->setLayout('ajax');
      //  $data = $this->Captcha->generate();
        pr($data);
        /*if(!$data['error'])
        {
            return $this->response->withType('jpg')->withStringBody($data['data']);
        }*/
        //set error
        exit;
    }
    public function hextorgb ($hexstring){
  $integar = hexdec($hexstring);
  return array("red" => 0xFF & ($integar >> 0x10),
               "green" => 0xFF & ($integar >> 0x8),
               "blue" => 0xFF & $integar);
               }
}
