<?php
/**
 * Session Helper provides access to the Session in the Views.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Helper
 * @since         CakePHP(tm) v 1.1.7.3328
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

//App::uses('AppHelper', 'View/Helper');
//App::uses('CakeSession', 'Model/Datasource');
namespace App\Controller\Component;

use Cake\Controller\Component;
/**
 * Session Helper.
 *
 * Session reading from the view.
 *
 * @package       Cake.View.Helper
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/session.html
 */
class SessionComponent extends Component {

/**
 * Used to read a session values set in a controller for a key or return values for all keys.
 *
 * In your view: `$this->Session->read('Controller.sessKey');`
 * Calling the method without a param will return all session vars
 *
 * @param string $name the name of the session key you want to read
 * @return mixed values from the session vars
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/session.html#SessionHelper::read
 */
	public function read($name = null) {
		$session = $this->request->Session();
		return $session->read($name);
		//return CakeSession::read($name);
	}
/**
 * Used to read a session values set in a controller for a key or return values for all keys.
 *
 * In your view: `$this->Session->read('Controller.sessKey');`
 * Calling the method without a param will return all session vars
 *
 * @param string $name the name of the session key you want to read
 * @return mixed values from the session vars
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/session.html#SessionHelper::read
 */
	public function write($name = null,$value = null) {

		$session = $this->request->Session();
		$session->write($name,$value);
		return true;
		//return CakeSession::write($name,$value);
	}

/**
 * Used to check is a session key has been set
 *
 * In your view: `$this->Session->check('Controller.sessKey');`
 *
 * @param string $name
 * @return boolean
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/session.html#SessionHelper::check
 */
	public function check($name) {
		$session = $this->request->Session();
		return $session->check($name);
		//return CakeSession::check($name);
	}

/**
 * Returns last error encountered in a session
 *
 * In your view: `$this->Session->error();`
 *
 * @return string last error
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/session.html#displaying-notifcations-or-flash-messages
 */
	public function error() {
		return CakeSession::error();
	}

/**
 * Used to render the message set in Controller::Session::setFlash()
 *
 * In your view: $this->Session->flash('somekey');
 * Will default to flash if no param is passed
 *
 * You can pass additional information into the flash message generation.  This allows you
 * to consolidate all the parameters for a given type of flash message into the view.
 *
 * {{{
 * echo $this->Session->flash('flash', array('params' => array('class' => 'new-flash')));
 * }}}
 *
 * The above would generate a flash message with a custom class name. Using $attrs['params'] you
 * can pass additional data into the element rendering that will be made available as local variables
 * when the element is rendered:
 *
 * {{{
 * echo $this->Session->flash('flash', array('params' => array('name' => $user['User']['name'])));
 * }}}
 *
 * This would pass the current user's name into the flash message, so you could create personalized
 * messages without the controller needing access to that data.
 *
 * Lastly you can choose the element that is rendered when creating the flash message. Using
 * custom elements allows you to fully customize how flash messages are generated.
 *
 * {{{
 * echo $this->Session->flash('flash', array('element' => 'my_custom_element'));
 * }}}
 *
 * If you want to use an element from a plugin for rendering your flash message you can do that using the
 * plugin param:
 *
 * {{{
 * echo $this->Session->flash('flash', array(
 *		'element' => 'my_custom_element',
 *		'params' => array('plugin' => 'my_plugin')
 * ));
 * }}}
 *
 * @param string $key The [Message.]key you are rendering in the view.
 * @param array $attrs Additional attributes to use for the creation of this flash message.
 *    Supports the 'params', and 'element' keys that are used in the helper.
 * @return string
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/session.html#SessionHelper::flash
 */
	public function flash($key = 'flash', $attrs = array()) {
		$out = false;

		if (CakeSession::check('Message.' . $key)) {
			$flash = CakeSession::read('Message.' . $key);
			$message = $flash['message'];
			unset($flash['message']);

			if (!empty($attrs)) {
				$flash = array_merge($flash, $attrs);
			}

			if ($flash['element'] == 'default') {
				$class		= "alert-error";
				$icon		= "icon-warning";
				$msgtype	= "Error";
				if (!empty($flash['params']['class'])) {
					$class = $flash['params']['class'];
				}
				if (!empty($flash['params']['icon'])) {
					$icon = str_replace("-sign","",$flash['params']['icon']);
				}
				if (!empty($flash['params']['msgtype'])) {
					$msgtype = $flash['params']['msgtype'];
				}
				$key='flash';
				$out = '<div id="fleshMessage">
						<div class="alert '.$class.'">
							<table width="100%" height="100%">
							<tr>
								<td width="30" valign="middle" align="center">
								<img src="'.IMAGE_URL.'icons/25x25/dark/'.$icon.'.png"></td>
								<td valign="middle"><strong>'.$msgtype.':</strong> '.$message.'</td>
								<td width="15" valign="middle">
									<!--<a href="#" class="close"><img src="'.IMAGE_URL.'icons/25x25/dark/icon-close.png"></a>-->
								</td>	
							</tr>
							</table>
						</div>
						</div>';
				//$out = '<div id="' . $key . 'Message" class="' . $class . ' '.$key.'Message'. '">' . $message . '</div>';
			} elseif ($flash['element'] == '' || $flash['element'] == null) {
				$out = $message;
			} else {
				$options = array();
				if (isset($flash['params']['plugin'])) {
					$options['plugin'] = $flash['params']['plugin'];
				}
				$tmpVars = $flash['params'];
				$tmpVars['message'] = $message;
				$out = $this->_View->element($flash['element'], $tmpVars, $options);
			}
			CakeSession::delete('Message.' . $key);
		}
		return $out;
	}

/**
 * Used to check is a session is valid in a view
 *
 * @return boolean
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/session.html#SessionHelper::valid
 */
	public function valid() {
		return CakeSession::valid();
	}

	/*
	*/
	public function destroy(){
		$session = $this->request->Session();
		$session->destroy();
		return	true;
	}

	/*delter session */
	public function delete($key){
		$session = $this->request->Session();
		if($this->check($key)){
			$session->delete($key);
		}
		return	true;
	}

}
