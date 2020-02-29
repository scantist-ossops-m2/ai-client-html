<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2015-2020
 * @package Client
 * @subpackage Html
 */


namespace Aimeos\Client\Html\Catalog\Filter\Attribute;


/**
 * Default implementation of catalog attribute filter section in HTML client.
 *
 * @package Client
 * @subpackage Html
 */
class Standard
	extends \Aimeos\Client\Html\Common\Client\Factory\Base
	implements \Aimeos\Client\Html\Common\Client\Factory\Iface
{
	/** client/html/catalog/filter/attribute/standard/subparts
	 * List of HTML sub-clients rendered within the catalog filter attribute section
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
	private $subPartPath = 'client/html/catalog/filter/attribute/standard/subparts';
	private $subPartNames = [];


	/**
	 * Returns the HTML code for insertion into the body.
	 *
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @return string HTML code
	 */
	public function getBody( string $uid = '' ) : string
	{
		$view = $this->getView();

		$html = '';
		foreach( $this->getSubClients() as $subclient ) {
			$html .= $subclient->setView( $view )->getBody( $uid );
		}
		$view->attributeBody = $html;

		/** client/html/catalog/filter/attribute/standard/template-body
		 * Relative path to the HTML body template of the catalog filter attribute client.
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
		 * @see client/html/catalog/filter/attribute/standard/template-header
		 */
		$tplconf = 'client/html/catalog/filter/attribute/standard/template-body';
		$default = 'catalog/filter/attribute-body-standard';

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
		/** client/html/catalog/filter/attribute/decorators/excludes
		 * Excludes decorators added by the "common" option from the catalog filter attribute html client
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
		 *  client/html/catalog/filter/attribute/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Client\Html\Common\Decorator\*") added via
		 * "client/html/common/decorators/default" to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2015.08
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/catalog/filter/attribute/decorators/global
		 * @see client/html/catalog/filter/attribute/decorators/local
		 */

		/** client/html/catalog/filter/attribute/decorators/global
		 * Adds a list of globally available decorators only to the catalog filter attribute html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Client\Html\Common\Decorator\*") around the html client.
		 *
		 *  client/html/catalog/filter/attribute/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Client\Html\Common\Decorator\Decorator1" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2015.08
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/catalog/filter/attribute/decorators/excludes
		 * @see client/html/catalog/filter/attribute/decorators/local
		 */

		/** client/html/catalog/filter/attribute/decorators/local
		 * Adds a list of local decorators only to the catalog filter attribute html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Client\Html\Catalog\Decorator\*") around the html client.
		 *
		 *  client/html/catalog/filter/attribute/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Client\Html\Catalog\Decorator\Decorator2" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2015.08
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/catalog/filter/attribute/decorators/excludes
		 * @see client/html/catalog/filter/attribute/decorators/global
		 */

		return $this->createSubClient( 'catalog/filter/attribute/' . $type, $name );
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
	public function addData( \Aimeos\MW\View\Iface $view, array &$tags = [], string &$expire = null ) : \Aimeos\MW\View\Iface
	{
		$attrMap = [];

		/** client/html/catalog/filter/attribute/types-option
		 * List of attribute types whose IDs should be used in a global "OR" condition
		 *
		 * The attribute section in the catalog filter component can display all
		 * attributes a visitor can use to filter the listed products to those that
		 * contains one or more attributes.
		 *
		 * This configuration setting lists the attribute types where at least one of
		 * all attributes must be referenced by the found products. Only one attribute
		 * of all listed attributes types (whatever matches) in enough. This setting is
		 * different from "client/html/catalog/filter/attribute/types-oneof" because
		 * it's not limited within the same attribute type
		 *
		 * @param array List of attribute type codes
		 * @since 2016.10
		 * @category User
		 * @category Developer
		 * @see client/html/catalog/filter/attribute/types
		 * @see client/html/catalog/filter/attribute/types-oneof
		 */
		$options = $view->config( 'client/html/catalog/filter/attribute/types-option', [] );

		/** client/html/catalog/filter/attribute/types-oneof
		 * List of attribute types whose values should be used in a type specific "OR" condition
		 *
		 * The attribute section in the catalog filter component can display all
		 * attributes a visitor can use to filter the listed products to those that
		 * contains one or more attributes.
		 *
		 * This configuration setting lists the attribute types where at least one of
		 * the attributes within the same attribute type must be referenced by the found
		 * products.
		 *
		 * @param array List of attribute type codes
		 * @since 2016.10
		 * @category User
		 * @category Developer
		 * @see client/html/catalog/filter/attribute/types
		 * @see client/html/catalog/filter/attribute/types-option
		 */
		$oneof = $view->config( 'client/html/catalog/filter/attribute/types-oneof', [] );

		/** client/html/catalog/filter/attribute/types
		 * List of attribute types that should be displayed in this order in the catalog filter
		 *
		 * The attribute section in the catalog filter component can display
		 * all attributes a visitor can use to reduce the listed products
		 * to those that contains one or more attributes. By default, all
		 * available attributes will be displayed and ordered by their
		 * attribute type.
		 *
		 * With this setting, you can limit the attribute types to only thoses
		 * whose names are part of the setting value. Furthermore, a particular
		 * order for the attribute types can be enforced that is different
		 * from the standard order.
		 *
		 * @param array List of attribute type codes
		 * @since 2015.05
		 * @category User
		 * @category Developer
		 * @see client/html/catalog/filter/attribute/domains
		 * @see client/html/catalog/filter/attribute/types-oneof
		 * @see client/html/catalog/filter/attribute/types-option
		 */
		$attrTypes = $view->config( 'client/html/catalog/filter/attribute/types', [] );
		$attrTypes = ( !is_array( $attrTypes ) ? explode( ',', $attrTypes ) : $attrTypes );

		/** client/html/catalog/filter/attribute/domains
		 * List of domain names whose items should be fetched with the filter attributes
		 *
		 * The templates rendering the attributes in the catalog filter usually
		 * add the images and texts associated to each item. If you want to
		 * display additional content, you can configure your own list of
		 * domains (attribute, media, price, product, text, etc. are domains)
		 * whose items are fetched from the storage. Please keep in mind that
		 * the more domains you add to the configuration, the more time is
		 * required for fetching the content!
		 *
		 * @param array List of domain item names
		 * @since 2015.05
		 * @category Developer
		 * @see client/html/catalog/filter/attribute/types
		 */
		$domains = $view->config( 'client/html/catalog/filter/attribute/domains', array( 'text', 'media' ) );

		$attributes = \Aimeos\Controller\Frontend::create( $this->getContext(), 'attribute' )
			->uses( $domains )->type( $attrTypes )->sort( 'position' )->slice( 0, 10000 )->search();

		// Delete cache when attributes are added or deleted even in "tag-all" mode
		$this->addMetaItems( $attributes, $expire, $tags, ['attribute'] );


		$params = $this->getClientParams( $view->param() );

		$attrIds = array_filter( $view->param( 'f_attrid', [] ) );
		$oneIds = array_filter( $view->param( 'f_oneid', [] ) );
		$optIds = array_filter( $view->param( 'f_optid', [] ) );

		foreach( $attributes as $id => $item )
		{
			if( ( $key = array_search( $id, $attrIds ) ) !== null )
			{
				$item = $item->set( 'checked', true );
				unset( $params[$key] );
			}
			elseif( ( $key = array_search( $id, $oneIds ) ) !== null )
			{
				$item = $item->set( 'checked', true );
				unset( $params[$key] );
			}
			elseif( ( $key = array_search( $id, $optIds ) ) !== null )
			{
				$item = $item->set( 'checked', true );
				unset( $params[$key] );
			}

			$type = $item->getType();
			$fparams = $this->getFormParams( $type, $oneof, $options );
			$attrMap[$item->getType()][$id] = $item->set( 'params', $params )->set( 'formparam', $fparams );
		}


		unset( $params['f_attrid'], $params['f_oneid'], $params['f_optid'] );

		$view->attributeResetParams = $params;
		$view->attributeMap = $this->sort( $attrMap, $attrTypes );

		return parent::addData( $view, $tags, $expire );
	}


	/**
	 * Returns the form parameter names for the given attribute type
	 *
	 * @param string $type Attribute type code
	 * @param array $oneof List of attribute type codes for one of several filter
	 * @param array $options List of attribute type codes for optional filter
	 * @return array Ordered list of form parameter names
	 */
	protected function getFormParams( string $type, array $oneof, array $options ) : array
	{
		if( in_array( $type, $oneof ) ) {
			return ['f_oneid', $type, ''];
		}

		if( in_array( $type, $options ) ) {
			return ['f_optid', ''];
		}

		return ['f_attrid', ''];
	}


	/**
	 * Sorts the attribute types according to the configured order
	 *
	 * @param array $attrMap Associative list of attribute types as keys and attribute items as values
	 * @param array $attrTypes List of attribute type names
	 * @return array Sorted associative list of attribute types and attribute items
	 */
	protected function sort( array $attrMap, array $attrTypes ) : array
	{
		$map = [];

		foreach( $attrTypes as $type )
		{
			if( isset( $attrMap[$type] ) ) {
				$map[$type] = $attrMap[$type];
			}
		}

		return !empty( $map ) ? $map : $attrMap;
	}
}
