<?php
namespace Etsy\Opsweekly;

/**
 * Represent the preferred FQDN for this request, including optional
 * transformations.
 *
 * For development purposes, you may specify a dev_fqdn regex pattern
 * and a prod_fqdn replacement pattern, to modify the FQDN.
 * The replacement only occurs if both are set and non-falsey.
 */
class FQDN
{
    /**
     * The post-replacement FQDN.
     */
    protected $fqdn;


    /**
     * Create a FQDN-determining object.
     *
     * Replacement will only occur if both the dev and prod fqdns are truthy.
     *
     * @param string $fqdn      The original FQDN. For accuracy,
     *                          use $_SERVER['HTTP_HOST'], or override it.
     * @param string $dev_fqdn  The regex to use to match  the dev FQDN.
     * @param string $prod_fqdn The replacement to use for the prod FQDN.
     *
     * @emits E_ERROR or E_WARNING depending upon failure of the preg_replace.
     */
    public function __construct($fqdn = null, $dev_fqdn = null, $prod_fqdn = null)
    {
        if ($dev_fqdn && $prod_fqdn) {
            $this->fqdn = preg_replace($dev_fqdn, $prod_fqdn, $fqdn);
        } else {
            $this->fqdn = $fqdn;
        }
    }

    /**
     * Get the FQDN to use for the current request.
     */
    public function __toString()
    {
        return $this->fqdn;
    }

    /**
     * Get the FQDN to use for the current request.
     */
    public function getFQDN()
    {
        return (string)$this;
    }
}
