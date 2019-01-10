<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018
 */


namespace Aimeos\Controller\Frontend\Supplier\Decorator;


class BaseTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $stub;


	protected function setUp()
	{
		$this->context = \TestHelperFrontend::getContext();

		$this->stub = $this->getMockBuilder( \Aimeos\Controller\Frontend\Supplier\Standard::class )
			->disableOriginalConstructor()
			->getMock();

		$this->object = $this->getMockBuilder( \Aimeos\Controller\Frontend\Supplier\Decorator\Base::class )
			->setConstructorArgs( [$this->stub, $this->context] )
			->getMockForAbstractClass();
	}


	protected function tearDown()
	{
		unset( $this->context, $this->object, $this->stub );
	}


	public function testConstructException()
	{
		$stub = $this->getMockBuilder( \Aimeos\Controller\Frontend\Iface::class )->getMock();

		$this->setExpectedException( \Aimeos\MW\Common\Exception::class );

		$this->getMockBuilder( \Aimeos\Controller\Frontend\Supplier\Decorator\Base::class )
			->setConstructorArgs( [$stub, $this->context] )
			->getMockForAbstractClass();
	}


	public function testCall()
	{
		$stub = $this->getMockBuilder( \Aimeos\Controller\Frontend\Supplier\Standard::class )
			->disableOriginalConstructor()
			->setMethods( ['invalid'] )
			->getMock();

		$object = $this->getMockBuilder( \Aimeos\Controller\Frontend\Supplier\Decorator\Base::class )
			->setConstructorArgs( [$stub, $this->context] )
			->getMockForAbstractClass();

		$stub->expects( $this->once() )->method( 'invalid' )->will( $this->returnValue( true ) );

		$this->assertTrue( $object->invalid() );
	}


	public function testCompare()
	{
		$expected = \Aimeos\Controller\Frontend\Supplier\Iface::class;

		$this->stub->expects( $this->once() )->method( 'compare' )
			->will( $this->returnValue( $this->stub ) );

		$this->assertInstanceOf( $expected, $this->object->compare( '==', 'supplier.status', 1 ) );
	}


	public function testGet()
	{
		$item = \Aimeos\MShop::create( $this->context, 'supplier' )->createItem();
		$expected = \Aimeos\MShop\Supplier\Item\Iface::class;

		$this->stub->expects( $this->once() )->method( 'get' )
			->will( $this->returnValue( $item ) );

		$this->assertInstanceOf( $expected, $this->object->get( 1 ) );
	}


	public function testFind()
	{
		$item = \Aimeos\MShop::create( $this->context, 'supplier' )->createItem();
		$expected = \Aimeos\MShop\Supplier\Item\Iface::class;

		$this->stub->expects( $this->once() )->method( 'find' )
			->will( $this->returnValue( $item ) );

		$this->assertInstanceOf( $expected, $this->object->find( 'test' ) );
	}


	public function testParse()
	{
		$expected = \Aimeos\Controller\Frontend\Supplier\Iface::class;

		$this->stub->expects( $this->once() )->method( 'parse' )
			->will( $this->returnValue( $this->stub ) );

		$this->assertInstanceOf( $expected, $this->object->parse( [] ) );
	}


	public function testSearch()
	{
		$item = \Aimeos\MShop::create( $this->context, 'supplier' )->createItem();

		$this->stub->expects( $this->once() )->method( 'search' )
			->will( $this->returnValue( [$item] ) );

		$this->assertEquals( [$item], $this->object->search() );
	}


	public function testSlice()
	{
		$expected = \Aimeos\Controller\Frontend\Supplier\Iface::class;

		$this->stub->expects( $this->once() )->method( 'slice' )
			->will( $this->returnValue( $this->stub ) );

		$this->assertInstanceOf( $expected, $this->object->slice( 0, 100 ) );
	}


	public function testSort()
	{
		$expected = \Aimeos\Controller\Frontend\Supplier\Iface::class;

		$this->stub->expects( $this->once() )->method( 'sort' )
			->will( $this->returnValue( $this->stub ) );

		$this->assertInstanceOf( $expected, $this->object->sort( 'supplier.label' ) );
	}


	public function testGetController()
	{
		$result = $this->access( 'getController' )->invokeArgs( $this->object, [] );

		$this->assertSame( $this->stub, $result );
	}


	protected function access( $name )
	{
		$class = new \ReflectionClass( \Aimeos\Controller\Frontend\Supplier\Decorator\Base::class );
		$method = $class->getMethod( $name );
		$method->setAccessible( true );

		return $method;
	}
}
