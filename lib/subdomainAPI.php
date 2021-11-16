<?php


namespace lib;

require __DIR__ . '/clientAPI.php';
use lib\clientAPI as client;

/**
 * Sample API Client.
 *
 * A simple API Client for communicating with an external API endpoint.
 */

class subdomainAPI
{

    /**
     * Register a domain.
     *
     * @param array $data common module parameters
     *
     * @return array
     *
     */
    public function RegisterDomain($data)
    {
        // user defined configuration values
        $apiToken = $data['API_Token'];

        /**
         * Domainname is required
         */

        // registration parameters
        $sld = $data['sld'];
        $tld = $data['tld']; // zB 'co.de'

        // Build post data
        $postfields = array(
            'domain' => $sld . $tld,
            'years' => $data['regperiod'] ?? 1, //Minimum 1 year will be used, if no data is transferred

            'registrant' => array(
                'firstname' => $data['registrant']['firstname'] ?? null,
                'lastname' => $data['registrant']['lastname'] ?? null,
                'companyname' => $data['registrant']['companyname'] ?? null,
                'email' => $data['registrant']['email'],
                'address1' => $data['registrant']['address1'] ?? null,
                'address2' => $data['registrant']['address2'] ?? null,
                'city' => $data['registrant']['city'] ?? null,
                'state' => $data['registrant']['state'] ?? null,
                'postcode' => $data['registrant']['postcode'] ?? null,
                'country' => $data['registrant']['country'] ?? null,
                'phone' => $data['registrant']['phone'] ?? null,
            )
        );
        // Admin,tech,billing information are optional.
        if(array_key_exists('admin', $data)) {
            array_push($postfields, ([
                'admin' => array(
                    'firstname' => $data['admin']['firstname'] ?? null,
                    'lastname' => $data['admin']['lastname'] ?? null,
                    'companyname' => $data['admin']['companyname'] ?? null,
                    'email' => $data['admin']['email'],
                    'address1' => $data['admin']['address1'] ?? null,
                    'address2' => $data['admin']['address2'] ?? null,
                    'city' => $data['admin']['city'] ?? null,
                    'state' => $data['admin']['state'] ?? null,
                    'postcode' => $data['admin']['postcode'] ?? null,
                    'country' => $data['admin']['country'] ?? null,
                    'phone' => $data['admin']['phone'] ?? null,
                )
            ]));
        }
        if(array_key_exists('tech', $data)) {
            array_push($postfields, ([
                'tech' => array(
                    'firstname' => $data['tech']['firstname'] ?? null,
                    'lastname' => $data['tech']['lastname'] ?? null,
                    'companyname' => $data['tech']['companyname'] ?? null,
                    'email' => $data['tech']['email'],
                    'address1' => $data['tech']['address1'] ?? null,
                    'address2' => $data['tech']['address2'] ?? null,
                    'city' => $data['tech']['city'] ?? null,
                    'state' => $data['tech']['state'] ?? null,
                    'postcode' => $data['tech']['postcode'] ?? null,
                    'country' => $data['tech']['country'] ?? null,
                    'phone' => $data['tech']['phone'] ?? null,
                )
            ]));
        }
        if(array_key_exists('billing', $data)) {
            array_push($postfields, ([
                'billing' => array(
                    'firstname' => $data['billing']['firstname'] ?? null,
                    'lastname' => $data['billing']['lastname'] ?? null,
                    'companyname' => $data['billing']['companyname'] ?? null,
                    'email' => $data['billing']['email'] ?? null,
                    'address1' => $data['billing']['address1'] ?? null,
                    'address2' => $data['billing']['address2'] ?? null,
                    'city' => $data['billing']['city'] ?? null,
                    'state' => $data['billing']['state'] ?? null,
                    'postcode' => $data['billing']['postcode'] ?? null,
                    'country' => $data['billing']['country'] ?? null,
                    'phone' => $data['billing']['phone'] ?? null,
                )
            ]));
        }
        // Nameserver
        if(array_key_exists('nameservers', $data)) {
            array_push($postfields, ([
                'nameservers' => array(
                    'ns1' => $data['nameservers']['ns1'] ?? null,
                    'ns2' => $data['nameservers']['ns2'] ?? null,
                    'ns3' => $data['nameservers']['ns3'] ?? null,
                    'ns4' => $data['nameservers']['ns4'] ?? null,
                    'ns5' => $data['nameservers']['ns5'] ?? null,
                )
            ]));
        }


        try {
            $api = new client($apiToken);
            return $api->post('subdomains', $postfields);



        } catch (\Exception $e) {
            return array(
                'error' => $e->getMessage(),
            );
        }
    }

    /**
     * Renew a domain.
     *
     * Attempt to renew/extend a domain for a given number of years.
     *
     * @param array $data common module parameters
     *
     * @return array
     *
     */

    public function RenewDomain($data)
    {
        // user defined configuration values
        $apiToken = $data['API_Token'];


        // registration parameters
        $sld = $data['sld'];
        $tld = $data['tld'];

        // Build post data.
        $postfields = array(
            'domain' => $sld . $tld,
            'years' => $data['regperiod'] ?? 1,
        );

        try {
            $api = new client($apiToken);
            $api->patch('subdomains', $postfields);


            return array(
                'success' => true,
            );

        } catch (\Exception $e) {
            return array(
                'error' => $e->getMessage(),
            );
        }
    }


    /**
     * Fetch current nameservers.
     *
     * This function should return an array of nameservers for a given domain.
     *
     * @param array $data common module parameters
     *
     * @return array
     *
     */

    public function GetNameservers($data)
    {
        // user defined configuration values
        $apiToken = $data['API_Token'];

        // domain parameters
        $sld = $data['sld'];
        $tld = $data['tld'];

        // Build post data
        $postfields = array(
            'domain' => $sld . $tld,
        );

        try {
            $api = new client($apiToken);
            $api->get('subdomains/' . $postfields["domain"], $postfields);

            $domainInformation = $api->getFromResponse('DomainInformation');

            return array(
                'ns1' => $domainInformation['ns1'],
                'ns2' => $domainInformation['ns2'],
                'ns3' => $domainInformation['ns3'],
                'ns4' => $domainInformation['ns4'],
                'ns5' => $domainInformation['ns5'],
            );

        } catch (\Exception $e) {
            return array(
                'error' => $e->getMessage(),
            );
        }
    }

    /**
     * Save nameserver changes.
     *
     * This function should submit a change of nameservers request to the
     * domain registrar.
     *
     * @param array $data common module parameters
     *
     * @return array
     *
     */
    public function SaveNameservers($data)
    {
        // user defined configuration values
        $apiToken = $data['API_Token'];

        // domain parameters
        $sld = $data['sld'];
        $tld = $data['tld'];

        // Build post data
        $postfields = array(
            'domain' => $sld . $tld,
            'nameservers' => array(
                'ns1' => $data['nameservers']['ns1'] ?? null,
                'ns2' => $data['nameservers']['ns2'] ?? null,
                'ns3' => $data['nameservers']['ns3'] ?? null,
                'ns4' => $data['nameservers']['ns4'] ?? null,
                'ns5' => $data['nameservers']['ns5'] ?? null,
            )
        );

        try {
            $api = new client($apiToken);
            return $api->patch('subdomains', $postfields);


        } catch (\Exception $e) {
            return array(
                'error' => $e->getMessage(),
            );
        }
    }

    /**
     * Get the current WHOIS Contact Information.
     *
     * Should return a multi-level array of the contacts and name/address
     * fields that be modified.
     *
     * @param array $data common module parameters
     *
     * @return array
     * @see https://developers.whmcs.com/domain-registrars/module-parameters/
     *
     */

    public function GetContactDetails($data)
    {
        // user defined configuration values
        $apiToken = $data['API_Token'];

        // domain parameters
        $sld = $data['sld'];
        $tld = $data['tld'];

        // Build post data
        $postfields = array(
            'domain' => $sld . $tld,
        );

        try {
            $api = new client($apiToken);
            $api->get('subdomains/' . $postfields["domain"], $postfields);

            return array(
                'registrant' => array(
                    'firstname' => $api->getFromResponse('DomainInformation.registrant.firstname'),
                    'lastname' => $api->getFromResponse('DomainInformation.registrant.lastname'),
                    'companyname' => $api->getFromResponse('DomainInformation.registrant.companyname'),
                    'email' => $api->getFromResponse('DomainInformation.registrant.email'),
                    'address1' => $api->getFromResponse('DomainInformation.registrant.address1'),
                    'address2' => $api->getFromResponse('DomainInformation.registrant.address2'),
                    'city' => $api->getFromResponse('DomainInformation.registrant.city'),
                    'state' => $api->getFromResponse('DomainInformation.registrant.state'),
                    'postcode' => $api->getFromResponse('DomainInformation.registrant.postcode'),
                    'country' => $api->getFromResponse('DomainInformation.registrant.country'),
                    'phone' => $api->getFromResponse('DomainInformation.registrant.phone'),
                ),
                'tech' => array(
                    'First Name' => $api->getFromResponse('DomainInformation.tech.firstname'),
                    'Last Name' => $api->getFromResponse('DomainInformation.tech.lastname'),
                    'Company Name' => $api->getFromResponse('DomainInformation.tech.companyname'),
                    'Email Address' => $api->getFromResponse('DomainInformation.tech.email'),
                    'Address 1' => $api->getFromResponse('DomainInformation.tech.address1'),
                    'Address 2' => $api->getFromResponse('DomainInformation.tech.address2'),
                    'City' => $api->getFromResponse('DomainInformation.tech.city'),
                    'State' => $api->getFromResponse('DomainInformation.tech.state'),
                    'Postcode' => $api->getFromResponse('DomainInformation.tech.postcode'),
                    'Country' => $api->getFromResponse('DomainInformation.tech.country'),
                    'Phone Number' => $api->getFromResponse('DomainInformation.tech.phone'),
                    'Fax Number' => $api->getFromResponse('DomainInformation.tech.fax'),
                ),
                'billing' => array(
                    'First Name' => $api->getFromResponse('DomainInformation.billing.firstname'),
                    'Last Name' => $api->getFromResponse('DomainInformation.billing.lastname'),
                    'Company Name' => $api->getFromResponse('DomainInformation.billing.companyname'),
                    'Email Address' => $api->getFromResponse('DomainInformation.billing.email'),
                    'Address 1' => $api->getFromResponse('DomainInformation.billing.address1'),
                    'Address 2' => $api->getFromResponse('DomainInformation.billing.address2'),
                    'City' => $api->getFromResponse('DomainInformation.billing.city'),
                    'State' => $api->getFromResponse('DomainInformation.billing.state'),
                    'Postcode' => $api->getFromResponse('DomainInformation.billing.postcode'),
                    'Country' => $api->getFromResponse('DomainInformation.billing.country'),
                    'Phone Number' => $api->getFromResponse('DomainInformation.billing.phone'),
                    'Fax Number' => $api->getFromResponse('DomainInformation.billing.fax'),
                ),
                'admin' => array(
                    'First Name' => $api->getFromResponse('DomainInformation.admin.firstname'),
                    'Last Name' => $api->getFromResponse('DomainInformation.admin.lastname'),
                    'Company Name' => $api->getFromResponse('DomainInformation.admin.companyname'),
                    'Email Address' => $api->getFromResponse('DomainInformation.admin.email'),
                    'Address 1' => $api->getFromResponse('DomainInformation.admin.address1'),
                    'Address 2' => $api->getFromResponse('DomainInformation.admin.address2'),
                    'City' => $api->getFromResponse('DomainInformation.admin.city'),
                    'State' => $api->getFromResponse('DomainInformation.admin.state'),
                    'Postcode' => $api->getFromResponse('DomainInformation.admin.postcode'),
                    'Country' => $api->getFromResponse('DomainInformation.admin.country'),
                    'Phone Number' => $api->getFromResponse('DomainInformation.admin.phone'),
                    'Fax Number' => $api->getFromResponse('DomainInformation.admin.fax'),
                ),
            );

        } catch (\Exception $e) {
            return array(
                'error' => $e->getMessage(),
            );
        }
    }

    /**
     * Update the WHOIS Contact Information for a given domain.
     *
     * @param array $data common module parameters
     *
     * @return array
     *
     */

    public function SaveContactDetails($data)
    {
        // user defined configuration values
        $apiToken = $data['API_Token'];

        // domain parameters
        $sld = $data['sld'];
        $tld = $data['tld'];


        // Build post data
        $postfields = array(
            'domain' => $sld . $tld,
        );

        if (array_key_exists('registrant', $data)) {
            array_push($postfields, ([
                'registrant' => array(
                    'firstname' => $data['registrant']['firstname'] ?? null,
                    'lastname' => $data['registrant']['lastname'] ?? null,
                    'companyname' => $data['registrant']['companyname'] ?? null,
                    'email' => $data['registrant']['email'],
                    'address1' => $data['registrant']['address1'] ?? null,
                    'address2' => $data['registrant']['address2'] ?? null,
                    'city' => $data['registrant']['city'] ?? null,
                    'state' => $data['registrant']['state'] ?? null,
                    'postcode' => $data['registrant']['postcode'] ?? null,
                    'country' => $data['registrant']['country'] ?? null,
                    'phone' => $data['registrant']['phone'] ?? null,
                )
            ]));
        }

        if(array_key_exists('admin', $data)) {
            array_push($postfields, ([
                'admin' => array(
                    'firstname' => $data['admin']['firstname'] ?? null,
                    'lastname' => $data['admin']['lastname'] ?? null,
                    'companyname' => $data['admin']['companyname'] ?? null,
                    'email' => $data['admin']['email'],
                    'address1' => $data['admin']['address1'] ?? null,
                    'address2' => $data['admin']['address2'] ?? null,
                    'city' => $data['admin']['city'] ?? null,
                    'state' => $data['admin']['state'] ?? null,
                    'postcode' => $data['admin']['postcode'] ?? null,
                    'country' => $data['admin']['country'] ?? null,
                    'phone' => $data['admin']['phone'] ?? null,
                )
            ]));
        }
        if(array_key_exists('tech', $data)) {
            array_push($postfields, ([
                'tech' => array(
                    'firstname' => $data['tech']['firstname'] ?? null,
                    'lastname' => $data['tech']['lastname'] ?? null,
                    'companyname' => $data['tech']['companyname'] ?? null,
                    'email' => $data['tech']['email'],
                    'address1' => $data['tech']['address1'] ?? null,
                    'address2' => $data['tech']['address2'] ?? null,
                    'city' => $data['tech']['city'] ?? null,
                    'state' => $data['tech']['state'] ?? null,
                    'postcode' => $data['tech']['postcode'] ?? null,
                    'country' => $data['tech']['country'] ?? null,
                    'phone' => $data['tech']['phone'] ?? null,
                )
            ]));
        }
        if(array_key_exists('billing', $data)) {
            array_push($postfields, ([
                'billing' => array(
                    'firstname' => $data['billing']['firstname'] ?? null,
                    'lastname' => $data['billing']['lastname'] ?? null,
                    'companyname' => $data['billing']['companyname'] ?? null,
                    'email' => $data['billing']['email'] ?? null,
                    'address1' => $data['billing']['address1'] ?? null,
                    'address2' => $data['billing']['address2'] ?? null,
                    'city' => $data['billing']['city'] ?? null,
                    'state' => $data['billing']['state'] ?? null,
                    'postcode' => $data['billing']['postcode'] ?? null,
                    'country' => $data['billing']['country'] ?? null,
                    'phone' => $data['billing']['phone'] ?? null,
                )
            ]));
        }

        try {
            $api = new client($apiToken);
            return $api->patch('subdomains', $postfields);

        } catch (\Exception $e) {
            return array(
                'error' => $e->getMessage(),
            );
        }
    }

    /**
     * Check Domain Availability.
     *
     * Determine if a domain or group of domains are available for
     * registration.
     *
     * @param array $data common module parameters
     * @throws Exception Upon domain availability check failure.
     */
    public function CheckAvailability($data)
    {

        $apiToken = $data['API_Token'];

        // Build post data
        $postfields = array(
            'searchTerm' => $data['sld'],
            'tldsToSearch' => $data['tldsToInclude']
        );
        try {
            $api = new client($apiToken);
            return $api->post('subdomains/availability', $postfields);

        } catch (\Exception $e) {
            return array(
                'error' => $e->getMessage(),
            );
        }
    }

    /**
     * Delete Domain.
     *
     * @param array $data common module parameters
     *
     * @return array
     *
     */
    public function RequestDelete($data)
    {
        // user defined configuration values
        $apiToken = $data['API_Token'];

        // domain parameters
        $sld = $data['sld'];
        $tld = $data['tld'];

        // Build post data
        $postfields = array(

            'domain' => $sld . $tld,
        );

        try {
            $api = new client($apiToken);
            return $api->delete('subdomains/' . $postfields["domain"], $postfields);

        } catch (\Exception $e) {
            return array(
                'error' => $e->getMessage(),
            );
        }
    }


    /**
     * Sync Domain Status & Expiration Date.
     *
     * @param array $data common module parameters
     *
     * @return array
     *
     */
    public function Sync($data)
    {
        // user defined configuration values
        $apiToken = $data['API_Token'];

        // domain parameters
        $sld = $data['sld'];
        $tld = $data['tld'];

        // Build post data
        $postfields = array(
            'domain' => $sld . $tld,
        );

        try {
            $api = new client($apiToken);
            $api->get('sync/' . $postfields['domain'], $postfields);

            return array(
                'expirydate' => $api->getFromResponse('expirydate'), // Format: YYYY-MM-DD
                'active' => (bool)$api->getFromResponse('active'), // Return true if the domain is active
                'expired' => (bool)$api->getFromResponse('expired'), // Return true if the domain has expired
                'transferredAway' => (bool)$api->getFromResponse('transferredaway'), // Return true if the domain is transferred out
            );

        } catch (\Exception $e) {
            return array(
                'error' => $e->getMessage(),
            );
        }
    }

    /**
     * Request EEP Code.
     *
     *
     * @param array $data common module parameters
     *
     * @return array
     *
     */
    public function GetEPPCode($data)
    {
        // user defined configuration values
        $apiToken = $data['API_Token'];

        // domain parameters
        $sld = $data['sld'];
        $tld = $data['tld'];

        // Build post data
        $postfields = array(
            'domain' => $sld . $tld,
        );

        try {
            $api = new client($apiToken);
            $api->post('eppcode', $postfields);

            if ($api->getFromResponse('eppcode')) {
                // If EPP Code is returned, return it for display to the end user


                return array(
                    'eppcode' => $api->getFromResponse('eppcode'),
                    'created_at' => $api->getFromResponse("created_at"),
                    'expiry_date' => $api->getFromResponse("expiry_date")
                );
            } else {
                // If EPP Code is not returned, it was sent by email, return success
                return array(
                    'success' => 'success',
                );
            }

        } catch (\Exception $e) {
            return array(
                'error' => $e->getMessage(),
            );
        }
    }
}