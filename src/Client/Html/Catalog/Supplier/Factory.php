<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018-2022
 * @package Client
 * @subpackage Html
 */


namespace Aimeos\Client\Html\Catalog\Supplier;


/**
 * Factory for supplier part in catalog for HTML clients.
 *
 * @package Client
 * @subpackage Html
 */
class Factory
	extends \Aimeos\Client\Html\Common\Factory\Base
	implements \Aimeos\Client\Html\Common\Factory\Iface
{
	/**
	 * Creates a supplier client object.
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Shop context instance with necessary objects
	 * @param string|null $name Client name (default: "Standard")
	 * @return \Aimeos\Client\Html\Iface Supplier part implementing \Aimeos\Client\Html\Iface
	 * @throws \Aimeos\Client\Html\Exception If requested client implementation couldn't be found or initialisation fails
	 */
	public static function create( \Aimeos\MShop\Context\Item\Iface $context, string $name = null ) : \Aimeos\Client\Html\Iface
	{
		/** client/html/catalog/supplier/name
		 * Class name of the used catalog supplier client implementation
		 *
		 * Each default HTML client can be replace by an alternative imlementation.
		 * To use this implementation, you have to set the last part of the class
		 * name as configuration value so the client factory knows which class it
		 * has to instantiate.
		 *
		 * For example, if the name of the default class is
		 *
		 *  \Aimeos\Client\Html\Catalog\Supplier\Standard
		 *
		 * and you want to replace it with your own version named
		 *
		 *  \Aimeos\Client\Html\Catalog\Supplier\Mysupplier
		 *
		 * then you have to set the this configuration option:
		 *
		 *  client/html/catalog/supplier/name = Mysupplier
		 *
		 * The value is the last part of your own class name and it's case sensitive,
		 * so take care that the configuration value is exactly named like the last
		 * part of the class name.
		 *
		 * The allowed characters of the class name are A-Z, a-z and 0-9. No other
		 * characters are possible! You should always start the last part of the class
		 * name with an upper case character and continue only with lower case characters
		 * or numbers. Avoid chamel case names like "MySupplier"!
		 *
		 * @param string Last part of the class name
		 * @since 2018.04
		 */
		if( $name === null ) {
			$name = $context->config()->get( 'client/html/catalog/supplier/name', 'Standard' );
		}

		$iface = '\\Aimeos\\Client\\Html\\Iface';
		$classname = '\\Aimeos\\Client\\Html\\Catalog\\Supplier\\' . $name;

		if( ctype_alnum( $name ) === false ) {
			throw new \Aimeos\Client\Html\Exception( sprintf( 'Invalid characters in class name "%1$s"', $classname ) );
		}

		$client = self::createClient( $context, $classname, $iface );
		$client = self::addClientDecorators( $context, $client, 'catalog/supplier' );

		return $client->setObject( $client );
	}
}