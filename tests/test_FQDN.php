<?php

use Etsy\Opsweekly\FQDN;

class getFQDNtest extends PHPUnit_Framework_TestCase
{
    protected $domain = 'given.example';

    /**
     * @expectedException PHPUnit_Framework_Error_Warning
     */
    public function testInvalidArgumentsWarning()
    {
        $fqdn = new FQDN(1, 'not a regex', 'not a replacement');
    }

    public function testUsesGivenFQDN()
    {
        $fqdn = new FQDN($this->domain);

        $this->assertSame($this->domain, $fqdn->getFQDN());
    }

    public function testToStringIsEquivalent()
    {
        $fqdn = new FQDN($this->domain);

        $this->assertSame((string)$fqdn, $fqdn->getFQDN());
    }

    public function testDoesNotReplaceWithoutDevFQDN()
    {
        $fqdn = new FQDN($this->domain, null, '\0');

        $this->assertSame($this->domain, $fqdn->getFQDN());
    }

    public function testDoesNotReplaceWithoutProdFQDN()
    {
        $fqdn = new FQDN($this->domain, '/.+/', null);

        $this->assertSame($this->domain, $fqdn->getFQDN());
    }

    public function falsinessProvider()
    {
        return [
            [ '/.*/', null ],
            [ '/.*/', false ],
            [ '/.*/', '' ],
            [ null, '\0' ],
            [ false, '\0' ],
            [ '', '\0' ]
        ];
    }

    /**
     * @dataProvider falsinessProvider
     */
    public function testDoesNotReplaceIfEitherIsFalsey($dev, $prod)
    {
        $fqdn = new FQDN($this->domain, $dev, $prod);

        $this->assertSame($this->domain, $fqdn->getFQDN());
    }

    public function replacementProvider()
    {
        // this test is dependent upon this structure,
        // so make sure it stays the same!

        $this->domain = 'given.example';

        return [

            // identity
            [ $this->domain, '/.+/', '$0', $this->domain ],

            // simple replacement
            [ $this->domain, '/(\w+)\.example/', '$1.subdomain.example', 'given.subdomain.example' ],

            // and now for something completely different
            [ $this->domain, '/.+/', 'other.example', 'other.example' ]
        ];
    }

    /**
     * @dataProvider replacementProvider
     */
    public function testReplacement($specified_fqdn, $dev, $prod, $expected)
    {
        $fqdn = new FQDN($specified_fqdn, $dev, $prod);

        $this->assertSame($expected, $fqdn->getFQDN());
    }
}
