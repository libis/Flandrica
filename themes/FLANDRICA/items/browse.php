<?php
/*Zend_Session::start();
if(!Zend_Registry::isRegistered('session'))
{
	$session = new Zend_Session_Namespace('zoek');
	Zend_Registry::set('session', $session);
	//$logger->log(print_r($session), Zend_Log::INFO);
}
*/
$pageTitle = __('Browse Items');
head(array('title'=>$pageTitle,'bodyid'=>'items','bodyclass' => 'browse'));
/*
//set style
if($_GET['style']){
	$style=$_GET['style'];
}
else{
	if($_GET['tags'])
		$style='table';
	else
		$style='list';
}*/
?><div class="clearfix"></div>
 <div id="style_two">
            <div id="wrapper" class="cf">
                <div id="container">
                    <div id="content">
                        <div id="main" class="padding-left-20 padding-right-20 article">
                            <h1>Sorry</h1>    
                        <p>Deze pagina bestaat niet langer.</p>
                        <p>Klik <a href="http://www.flandrica.be/solr-search/results/?solrfacet=">hier</a>
                          om verder te gaan naar de juiste pagina.</p>

                        <div class="clearfix">&nbsp;</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<?php foot(); ?>