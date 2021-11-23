<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2021
 * @package Client
 * @subpackage Html
 */


namespace Aimeos\Client\Html\Basket\Related\Bought;


/**
 * Default implementation of related basket bought HTML client.
 *
 * @package Client
 * @subpackage Html
 */
class Standard
	extends \Aimeos\Client\Html\Basket\Base
	implements \Aimeos\Client\Html\Common\Client\Factory\Iface
{
	/** client/html/basket/related/bought/subparts
	 * List of HTML sub-clients rendered within the basket related bought section
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
	 * @since 2014.03
	 * @category Developer
	 */
	private $subPartPath = 'client/html/basket/related/bought/subparts';
	private $subPartNames = [];


	/**
	 * Returns the HTML code for insertion into the body.
	 *
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @return string HTML code
	 */
	public function body( string $uid = '' ) : string
	{
		$view = $this->view();

		$html = '';
		foreach( $this->getSubClients() as $subclient ) {
			$html .= $subclient->setView( $view )->body( $uid );
		}
		$view->boughtBody = $html;

		/** client/html/basket/related/bought/template-body
		 * Relative path to the HTML body template of the basket related bought client.
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
		 * @since 2014.03
		 * @category Developer
		 * @see client/html/basket/related/bought/template-header
		 */
		$tplconf = 'client/html/basket/related/bought/template-body';
		$default = 'basket/related/bought-body-standard';

		return $view->render( $view->config( $tplconf, $default ) );
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
		/** client/html/basket/related/bought/decorators/excludes
		 * Excludes decorators added by the "common" option from the basket related bought html client
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
		 *  client/html/basket/related/bought/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Client\Html\Common\Decorator\*") added via
		 * "client/html/common/decorators/default" to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2014.05
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/basket/related/bought/decorators/global
		 * @see client/html/basket/related/bought/decorators/local
		 */

		/** client/html/basket/related/bought/decorators/global
		 * Adds a list of globally available decorators only to the basket related bought html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Client\Html\Common\Decorator\*") around the html client.
		 *
		 *  client/html/basket/related/bought/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Client\Html\Common\Decorator\Decorator1" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2014.05
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/basket/related/bought/decorators/excludes
		 * @see client/html/basket/related/bought/decorators/local
		 */

		/** client/html/basket/related/bought/decorators/local
		 * Adds a list of local decorators only to the basket related bought html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Client\Html\Basket\Decorator\*") around the html client.
		 *
		 *  client/html/basket/related/bought/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Client\Html\Basket\Decorator\Decorator2" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2014.05
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/basket/related/bought/decorators/excludes
		 * @see client/html/basket/related/bought/decorators/global
		 */

		return $this->createSubClient( 'basket/related/bought/' . $type, $name );
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


	/**
	 * Sets the necessary parameter values in the view.
	 *
	 * @param \Aimeos\MW\View\Iface $view The view object which generates the HTML output
	 * @param array &$tags Result array for the list of tags that are associated to the output
	 * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	public function data( \Aimeos\MW\View\Iface $view, array &$tags = [], string &$expire = null ) : \Aimeos\MW\View\Iface
	{
		if( isset( $view->relatedBasket ) )
		{
			$context = $this->getContext();
			$config = $context->getConfig();

			$cntl = \Aimeos\Controller\Frontend::create( $context, 'product' );

			/** client/html/basket/related/bought/limit
			 * Number of items in the list of bought together products
			 *
			 * This option limits the number of suggested products in the
			 * list of bought together products. The suggested items are
			 * calculated using the products that are in the current basket
			 * of the customer.
			 *
			 * Note: You need to start the job controller for calculating
			 * the bought together products regularly to get up to date
			 * product suggestions.
			 *
			 * @param integer Number of products
			 * @since 2014.09
			 */
			$size = $config->get( 'client/html/basket/related/bought/limit', 6 );

			/** client/html/basket/related/bought/domains
			 * The list of domain names whose items should be available in the template for the products
			 *
			 * The templates rendering product details usually add the images,
			 * prices and texts, etc. associated to the product
			 * item. If you want to display additional or less content, you can
			 * configure your own list of domains (attribute, media, price, product,
			 * text, etc. are domains) whose items are fetched from the storage.
			 * Please keep in mind that the more domains you add to the configuration,
			 * the more time is required for fetching the content!
			 *
			 * @param array List of domain names
			 * @since 2014.09
			 * @category Developer
			 */
			$domains = $config->get( 'client/html/basket/related/bought/domains', ['text', 'price', 'media'] );
			$domains['product'] = ['bought-together'];

			/** client/html/basket/related/basket-add
			 * Display the "add to basket" button for each product item
			 *
			 * Enables the button for adding products to the basket for the related products
			 * in the basket. This works for all type of products, even for selection products
			 * with product variants and product bundles. By default, also optional attributes
			 * are displayed if they have been associated to a product.
			 *
			 * @param boolean True to display the button, false to hide it
			 * @since 2020.10
			 * @see client/html/catalog/home/basket-add
			 * @see client/html/catalog/lists/basket-add
			 * @see client/html/catalog/detail/basket-add
			 * @see client/html/catalog/product/basket-add
			 */
			if( $view->config( 'client/html/basket/related/basket-add', false ) ) {
				$domains = array_merge_recursive( $domains, ['product' => ['default'], 'attribute' => ['variant', 'custom', 'config']] );
			}

			$items = map();
			$prodIds = $this->getProductIdsFromBasket( $view->relatedBasket );

			foreach( $cntl->uses( $domains )->product( $prodIds )->search() as $prodItem )
			{
				foreach( $prodItem->getListItems( 'product', 'bought-together' ) as $listItem )
				{
					if( ( $refItem = $listItem->getRefItem() ) !== null ) {
						$items[$refItem->getId()] = $refItem->set( 'position', $listItem->getPosition() );
					}
				}
			}

			$view->boughtItems = $items->uasort( function( $a, $b ) {
				return $a->get( 'position', 0 ) <=> $b->get( 'position', 0 );
			} )->slice( 0, $size );
		}

		return parent::data( $view, $tags, $expire );
	}


	/**
	 * Returns the IDs of the products in the current basket.
	 *
	 * @param \Aimeos\MShop\Order\Item\Base\Iface $basket Basket object
	 * @return string[] List of product IDs
	 */
	protected function getProductIdsFromBasket( \Aimeos\MShop\Order\Item\Base\Iface $basket ) : array
	{
		$list = [];

		foreach( $basket->getProducts() as $orderProduct )
		{
			$list[$orderProduct->getParentProductId() ?: $orderProduct->getProductId()] = true;

			foreach( $orderProduct->getProducts() as $subProduct ) {
				$list[$subProduct->getParentProductId() ?: $subProduct->getProductId()] = true;
			}
		}

		return array_keys( $list );
	}
}
