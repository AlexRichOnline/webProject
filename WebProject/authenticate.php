 <?php
/* Author: Alex Richardson
 * Date: Oct 2, 2018
 * This file uses HTTP Authentication to restrict access to certain webpages within the site
 * The login creditials are defined below as constants.
 *
*/

  define('ADMIN_LOGIN','alex');

  define('ADMIN_PASSWORD','keepcoding');


  if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])

      || ($_SERVER['PHP_AUTH_USER'] != ADMIN_LOGIN)

      || ($_SERVER['PHP_AUTH_PW'] != ADMIN_PASSWORD)) {

    header('HTTP/1.1 401 Unauthorized');

    header('WWW-Authenticate: Basic realm="Our Blog"');

    exit("Access Denied: Username and password required.");

  }
  
?>