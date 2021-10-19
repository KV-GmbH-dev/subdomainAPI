# Subdomain.com Sample API-Client #

## Summary ##

This API Client is a standalone PHP solution that may be implemented or be used as an example to easily connect with our backend.

The clientAPI class is mainly for establishing a connection, receiving and processing the response while the subdomainAPI class forms the body for the requests with given array.


## Tests ##

The script RegisterComplete.php includes a sample array to send with requests and every available method.
Once you got an API key, you can run the script to get a first response.

Please contact us for the necessary API test key.

## Example ##

Register:

```bash
use lib\subdomainAPI as api;

// Minimum registration data
$data = ([
'API_Token' => '',  // You have to enter the API Key here
'sld' => 'example',
'tld' => '.co.de'
]);

$api = new api;

$result = $api->RegisterDomain($data);
```

Please see RegisterComplete.php located in /tests for information.