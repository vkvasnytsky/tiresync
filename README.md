tiresync
========

Access to TireSYNC REST API using PHP and pure JavaScript

Access to the TireSync API is available via HTTP on port 80 to api.tiresync.com
as HTTP (v1.1) GET requests to the server. Parameters to API calls can be
included within the URI of the GET request itself, separated by slashes ("/").
So for example, the following would open a HTTP session to the server and send
a request with 5 parameters ("myparam1", "myparam2", ..., "myparam5"):

telnet api.tiresync.com 80

GET /myparam1/myparam2/myparam3/myparam4/myparam5 HTTP/1.1 Host: api.tiresync.com

1111-1111-1111-1111 is free API key to access DB of 2006 year, I also use a base64
encoded localhost as a domain name for testing purposes