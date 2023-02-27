<?php
/**
 * Freshmail plugin for Craft CMS 3.x
 *
 * Connect your freshmail account to Craft CMS.
 *
 * @link      https://pleodigital.com/
 * @copyright Copyright (c) 2019 Pleo Digital
 */

namespace pleodigitalfreshmail\freshmail\controllers;

use yii\base\Exception;
use pleodigitalfreshmail\freshmail\Freshmail;
use Craft;
use craft\web\Controller;

/**
 * Freshmail Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your plugin’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    Pleo Digital
 * @package   Freshmail
 * @since     1.0.0
 */
class FreshmailController extends Controller
{

	private $strApiSecret   = null;
    private $strApiKey      = null;
    private $res    = null;
    private $rawResponse = null;
    private $httpCode    = null;
    private $errors = array();
    private $contentType = 'application/json';

    const host   = 'https://api.freshmail.com/';
    const prefix = 'rest/';

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected array|bool|int $allowAnonymous = ['index', 'ajax'];

    // Public Methods
    // =========================================================================

    /**
     * Handle a request going to our plugin's index action URL,
     * e.g.: actions/freshmail/freshmail
     *
     * @return mixed
     */
    public function actionIndex()
    {
        
        $request = Craft :: $app -> getRequest();
        $plugin = Freshmail :: getInstance();
        $settings = $plugin -> getSettings();
        
		$this -> setApiKey( $settings-> apiKey );
        $this -> setApiSecret( $settings-> apiSecretKey );
        
        $addEmailArray = array(
			'email' => $request -> getBodyParam('freshmailEmail'),
			'list' => $request -> getBodyParam('freshmailListId'),
			'state' => 1
        );

        try {
		    $response = $this -> doRequest('subscriber/add', $addEmailArray );

            if( isset( $response[ 'errors' ] ) ) {
                Craft :: $app -> getSession() -> setError( Craft :: t( 'freshmail' , 'error_' . $response[ 'errors' ][ 0 ][ 'code' ] ) );
            } else {
                Craft :: $app -> getSession() -> setNotice( Craft :: t( 'freshmail' , "Subscriber added") );
            }

		} catch (Exception $e) {
            Craft :: $app -> getSession() -> setError( Craft :: t( 'freshmail' , 'Connection with freshmail went wrong. Check plugin settings.' ));
		}

    }
    public function actionAjax()
    {
        $request = Craft :: $app -> getRequest();
        $plugin = Freshmail :: getInstance();
        $settings = $plugin -> getSettings();

		$this -> setApiKey( $settings-> apiKey );
        $this -> setApiSecret( $settings-> apiSecretKey );

        $addEmailArray = array(
			'email' => $request -> getBodyParam('freshmailEmail'),
			'list' => $request -> getBodyParam('freshmailListId'),
			'state' => 1
        );
        try {
		    $response = $this -> doRequest('subscriber/add', $addEmailArray );
		    return $response;
		} catch (Exception $e) {
            return 'Connection with freshmail went wrong. Check plugin settings.';
		}

    }
    
    private function doRequest( $strUrl, $arrParams = array(), $boolRawResponse = false )
    {

        if ( empty($arrParams) ) {
            $strPostData = '';
        } elseif ( $this -> contentType == 'application/json' ) {
            $strPostData = json_encode( $arrParams );
        } elseif ( !empty( $arrParams ) ) {
            $strPostData = http_build_query( $arrParams );
        }

        $strSign = sha1( $this->strApiKey . '/' . self::prefix . $strUrl . $strPostData . $this->strApiSecret );

        $arrHeaders = array();
        $arrHeaders[] = 'X-Rest-ApiKey: ' . $this->strApiKey;
        $arrHeaders[] = 'X-Rest-ApiSign: ' . $strSign;

        if ($this->contentType) {
            $arrHeaders[] = 'Content-Type: '.$this->contentType;
        }

        $resCurl = curl_init( self::host . self::prefix . $strUrl );
        curl_setopt( $resCurl, CURLOPT_HTTPHEADER, $arrHeaders );
        curl_setopt( $resCurl, CURLOPT_HEADER, true);
        curl_setopt( $resCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $resCurl, CURLOPT_SSL_VERIFYPEER, false);

        if ($strPostData) {
            curl_setopt( $resCurl, CURLOPT_POST, true );
            curl_setopt( $resCurl, CURLOPT_POSTFIELDS, $strPostData );
        }

        $this -> rawResponse = curl_exec( $resCurl );
        $this -> httpCode = curl_getinfo( $resCurl, CURLINFO_HTTP_CODE );

        if ($boolRawResponse) {
            return $this -> rawResponse;
        }

        $this -> _getResponseFromHeaders($resCurl);

        if ( $this -> httpCode != 200 ) {
            $this -> errors = $this -> res['errors'];
            if ( is_array( $this -> errors ) ) {
                foreach ( $this -> errors as $arrError ) {
                //    echo '<pre>'.print_r( $arrError ,TRUE) . '</pre>';
                }
            }
        }

        if ( is_array( $this -> res ) == false ) {
            throw new Exception('Connection error - curl error message: '.curl_error($resCurl).' ('.curl_errno($resCurl).')');
        }

        return $this -> res;

    }

    private function _getResponseFromHeaders($resCurl)
    {
        $header_size = curl_getinfo($resCurl, CURLINFO_HEADER_SIZE);
        $header = substr($this->rawResponse, 0, $header_size);
        $TypePatern = '/Content-Type:\s*([a-z-Z\/]*)\s/';
        preg_match($TypePatern, $header, $responseType);
        if(sizeof($responseType) > 0 && strtolower($responseType[1]) == 'application/zip') {
            $filePatern = '/filename\=\"([a-zA-Z0-9\.]+)\"/';
            preg_match($filePatern, $header, $fileName);
            file_put_contents(self::defaultFilePath.$fileName[1], substr($this->rawResponse, $header_size));
            $this->res = array('path' =>self::defaultFilePath.$fileName[1]);
        } else {
            $this->res = json_decode( substr($this->rawResponse, $header_size), true );
        }
        return $this->res;
    }

    private function getHttpCode()
    {
        return $this->httpCode;
    }

    private function setApiSecret( $strSectret = '' )
    {
        $this->strApiSecret = $strSectret;
        return $this;
    }

    private function setApiKey ( $strKey = '' )
    {
        $this->strApiKey = $strKey;
        return $this;
    }

}
