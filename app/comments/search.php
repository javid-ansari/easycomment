<?php
header('Content-Type: text/html; charset=utf-8');

define('SOLR_SERVER_HOSTNAME', '192.168.18.8');
define('SOLR_SERVER_PORT', '8984');
define('SOLR_SERVER_USERNAME', '');
define('SOLR_SERVER_PASSWORD', '');
define('SOLR_PATH', 'solr/comments');

$options = array
(
		'hostname' => SOLR_SERVER_HOSTNAME,
		'login'    => SOLR_SERVER_USERNAME,
		'password' => SOLR_SERVER_PASSWORD,
		'port'     => SOLR_SERVER_PORT,
		'path'     => SOLR_PATH
);

$content_ref = isset($_GET['contentId']) ? $_GET['contentId'] : '';
$limit = 10;

$client = new SolrClient($options);

$query = new SolrQuery();

$qry = isset($_GET['q']) && !empty($_GET['q']) ? $_GET['q'] : '*:*';

if (get_magic_quotes_gpc() == 1)
{
	$qry = stripslashes($qry);
	$content_ref = stripslashes($content_ref);
}


$query->setQuery($qry);

$query->addFilterQuery('content_ref:'.$content_ref.'');


$query->setStart(0);

$query->setRows(50);

//$query->addField('cat')->addField('features')->addField('id')->addField('timestamp');
try{
	$query_response = $client->query($query)->getResponse();	
}catch(Exception $e){
	die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
}

?>
<html>
  <head>
    <title>PHP Solr Client Example</title>
  </head>
  <body>
    <form  accept-charset="utf-8" method="get">
      <label for="q">Search:</label>
      <input id="q" name="q" type="text" value="<?php echo htmlspecialchars($qry, ENT_QUOTES, 'utf-8'); ?>"/>
      <label for="content_ref">Content Ref:</label>
      <input id="contentId" name="contentId" type="text" value="<?php echo htmlspecialchars($content_ref, ENT_QUOTES, 'utf-8'); ?>"/>
      <input type="submit"/>
    </form>
<?php

// display results
if ($query_response)
{
	
  $total = (int) $query_response->response->numFound;
  $start = min(1, $total);
  $end = min($limit, $total);
?>
    <div>Results <?php echo $start; ?> - <?php echo $end;?> of <?php echo $total; ?>:</div>
    <ol>
<?php
  // iterate result documents
  foreach ($query_response->response->docs as $doc)
  {
?>
      <li>
        <table style="border: 1px solid black; text-align: left">
<?php
    // iterate document fields / values
    foreach ($doc as $field => $value)
    {
?>
          <tr>
            <th><?php echo htmlspecialchars($field, ENT_NOQUOTES, 'utf-8'); ?></th>
            <td><?php echo htmlspecialchars($value, ENT_NOQUOTES, 'utf-8'); ?></td>
          </tr>
<?php
    }
?>
        </table>
      </li>
<?php
  }
?>
    </ol>
<?php

}
?>
  </body>
</html>
Using an Alternative HTTP 
