<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2019-2021
 * @package Client
 * @subpackage Html
 */


namespace Aimeos\Client\Html\Basket\Bulk;


/**
 * Default implementation of bulk order HTML client.
 *
 * @package Client
 * @subpackage Html
 */
class Standard
	extends \Aimeos\Client\Html\Basket\Base
	implements \Aimeos\Client\Html\Common\Client\Factory\Iface
{
	/** client/html/basket/bulk/subparts
	 * List of HTML sub-clients rendered within the bulk order section
	 *
	 * The output of the frontend is composed of the code generated by the HTML
	 * clients. Each HTML client can consist of serveral (or none) sub-clients
	 * that are responsible for rendering certain sub-parts of the output. The
	 * sub-clients can contain HTML clients themselves and therefore a
	 * hierarchical tree of HTML clients is composed. Each HTML client creates
	 * the output that is placed inside the container of its parent.
	 *
	 * At first, always the HTML code generated by the parent is printed, then
	 * the HTML code of its sub-clients. The order of the HTML sub-clients
	 * determines the order of the output of these sub-clients inside the parent
	 * container. If the configured list of clients is
	 *
	 *  array( "subclient1", "subclient2" )
	 *
	 * you can easily change the order of the output by reordering the subparts:
	 *
	 *  client/html/<clients>/subparts = array( "subclient1", "subclient2" )
	 *
	 * You can also remove one or more parts if they shouldn't be rendered:
	 *
	 *  client/html/<clients>/subparts = array( "subclient1" )
	 *
	 * As the clients only generates structural HTML, the layout defined via CSS
	 * should support adding, removing or reordering content by a fluid like
	 * design.
	 *
	 * @param array List of sub-client names
	 * @since 2019.10
	 * @category Developer
	 */
	private $subPartPath = 'client/html/basket/bulk/subparts';
	private $subPartNames = [];
	private $view;


	/**
	 * Returns the HTML code for insertion into the body.
	 *
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @return string HTML code
	 */
	public function getBody( string $uid = '' ) : string
	{
		$context = $this->getContext();
		$site = $context->getLocale()->getSiteId();
		$view = $this->getView();

		/** client/html/basket/bulk
		 * All parameters defined for the bulk order component and its subparts
		 *
		 * This returns all settings related to the bulk order component.
		 * Please refer to the single settings for details.
		 *
		 * @param array Associative list of name/value settings
		 * @category Developer
		 * @see client/html/basket#bulk
		 */
		$config = $context->getConfig()->get( 'client/html/basket/bulk', [] );
		$key = $this->getParamHash( [], $uid . $site . ':basket:bulk-body', $config );

		if( ( $html = $this->getBasketCached( $key ) ) === null )
		{
			try
			{
				if( !isset( $this->view ) ) {
					$view = $this->view = $this->getObject()->data( $view );
				}

				$output = '';
				foreach( $this->getSubClients() as $subclient ) {
					$output .= $subclient->setView( $view )->getBody( $uid );
				}
				$view->bulkBody = $output;
			}
			catch( \Aimeos\Client\Html\Exception $e )
			{
				$error = array( $context->translate( 'client', $e->getMessage() ) );
				$view->bulkErrorList = array_merge( $view->get( 'bulkErrorList', [] ), $error );
			}
			catch( \Aimeos\Controller\Frontend\Exception $e )
			{
				$error = array( $context->translate( 'controller/frontend', $e->getMessage() ) );
				$view->bulkErrorList = array_merge( $view->get( 'bulkErrorList', [] ), $error );
			}
			catch( \Aimeos\MShop\Exception $e )
			{
				$error = array( $context->translate( 'mshop', $e->getMessage() ) );
				$view->bulkErrorList = array_merge( $view->get( 'bulkErrorList', [] ), $error );
			}
			catch( \Exception $e )
			{
				$error = array( $context->translate( 'client', 'A non-recoverable error occured' ) );
				$view->bulkErrorList = array_merge( $view->get( 'bulkErrorList', [] ), $error );
				$this->logException( $e );
			}

			/** client/html/basket/bulk/template-body
			 * Relative path to the HTML body template of the bulk order client.
			 *
			 * The template file contains the HTML code and processing instructions
			 * to generate the result shown in the body of the frontend. The
			 * configuration string is the path to the template file relative
			 * to the templates directory (usually in client/html/templates).
			 *
			 * You can overwrite the template file configuration in extensions and
			 * provide alternative templates. These alternative templates should be
			 * named like the default one but with the string "standard" replaced by
			 * an unique name. You may use the name of your project for this. If
			 * you've implemented an alternative client class as well, "standard"
			 * should be replaced by the name of the new class.
			 *
			 * @param string Relative path to the template creating code for the HTML page body
			 * @since 2019.10
			 * @category Developer
			 * @see client/html/basket/bulk/template-header
			 */
			$tplconf = 'client/html/basket/bulk/template-body';
			$default = 'basket/bulk/body-standard';

			$html = $view->render( $view->config( $tplconf, $default ) );
			$this->setBasketCached( $key, $html );
		}
		else
		{
			$html = $this->modifyBody( $html, $uid );
		}

		return $html;
	}


	/**
	 * Returns the HTML string for insertion into the header.
	 *
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @return string|null String including HTML tags for the header on error
	 */
	public function getHeader( string $uid = '' ) : ?string
	{
		$context = $this->getContext();
		$site = $context->getLocale()->getSiteId();
		$view = $this->getView();

		$config = $context->getConfig()->get( 'client/html/basket/bulk', [] );
		$key = $this->getParamHash( [], $uid . $site . ':basket:bulk-header', $config );

		if( ( $html = $this->getBasketCached( $key ) ) === null )
		{
			try
			{
				if( !isset( $this->view ) ) {
					$view = $this->view = $this->getObject()->data( $view );
				}

				$output = '';
				foreach( $this->getSubClients() as $subclient ) {
					$output .= $subclient->setView( $view )->getHeader( $uid );
				}
				$view->bulkHeader = $output;

				/** client/html/basket/bulk/template-header
				 * Relative path to the HTML header template of the bulk order client.
				 *
				 * The template file contains the HTML code and processing instructions
				 * to generate the HTML code that is inserted into the HTML page header
				 * of the rendered page in the frontend. The configuration string is the
				 * path to the template file relative to the templates directory (usually
				 * in client/html/templates).
				 *
				 * You can overwrite the template file configuration in extensions and
				 * provide alternative templates. These alternative templates should be
				 * named like the default one but with the string "standard" replaced by
				 * an unique name. You may use the name of your project for this. If
				 * you've implemented an alternative client class as well, "standard"
				 * should be replaced by the name of the new class.
				 *
				 * @param string Relative path to the template creating code for the HTML page head
				 * @since 2019.10
				 * @category Developer
				 * @see client/html/basket/bulk/template-body
				 */
				$tplconf = 'client/html/basket/bulk/template-header';
				$default = 'basket/bulk/header-standard';

				$html = $view->render( $view->config( $tplconf, $default ) );
				$this->setBasketCached( $key, $html );
			}
			catch( \Exception $e )
			{
				$this->logException( $e );
			}
		}
		else
		{
			$html = $this->modifyHeader( $html, $uid );
		}

		return $html;
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return \Aimeos\Client\Html\Iface Sub-client object
	 */
	public function getSubClient( string $type, string $name = null ) : \Aimeos\Client\Html\Iface
	{
		/** client/html/basket/bulk/decorators/excludes
		 * Excludes decorators added by the "common" option from the bulk order html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "client/html/common/decorators/default" before they are wrapped
		 * around the html client.
		 *
		 *  client/html/basket/bulk/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Client\Html\Common\Decorator\*") added via
		 * "client/html/common/decorators/default" to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2019.10
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/basket/bulk/decorators/global
		 * @see client/html/basket/bulk/decorators/local
		 */

		/** client/html/basket/bulk/decorators/global
		 * Adds a list of globally available decorators only to the bulk order html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Client\Html\Common\Decorator\*") around the html client.
		 *
		 *  client/html/basket/bulk/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Client\Html\Common\Decorator\Decorator1" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2019.10
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/basket/bulk/decorators/excludes
		 * @see client/html/basket/bulk/decorators/local
		 */

		/** client/html/basket/bulk/decorators/local
		 * Adds a list of local decorators only to the bulk order html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Client\Html\Basket\Decorator\*") around the html client.
		 *
		 *  client/html/basket/bulk/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Client\Html\Basket\Decorator\Decorator2" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2019.10
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/basket/bulk/decorators/excludes
		 * @see client/html/basket/bulk/decorators/global
		 */

		return $this->createSubClient( 'basket/bulk/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of HTML client names
	 */
	protected function getSubClientNames() : array
	{
		return $this->getContext()->getConfig()->get( $this->subPartPath, $this->subPartNames );
	}
}
