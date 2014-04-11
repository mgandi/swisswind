<?php
/* @var $this Controller */

// Set default parameter for style
$defaultParams = $this->style == 'clear' ? array() : array('style'=>'dark');

// Compute classes and URLs for menus
$indexMenu = $this->action->id == 'index' ? 'class="active"' : '';
$dataMenu = $this->action->id == 'data' ? 'class="active"' : '';
$infoMenu = $this->action->id == 'info' ? 'class="active"' : '';
$clearMenu = $this->style == 'dark' ? '' : 'class="active"';
$darkMenu = $this->style == 'dark' ? 'class="active"' : '';
$indexUrl = Yii::app()->createAbsoluteUrl('site/index', $defaultParams);
$dataUrl = Yii::app()->createAbsoluteUrl('site/data', $defaultParams);
$infoUrl = Yii::app()->createAbsoluteUrl('site/info', $defaultParams);
$clearUrl = Yii::app()->createAbsoluteUrl($this->uniqueId.'/'.$this->action->id);
$darkUrl = Yii::app()->createAbsoluteUrl($this->uniqueId.'/'.$this->action->id, array('style'=>'dark'));;

// Register script
Yii::app()->clientScript->registerSCript('layout', "
$.supersized({
  
  // Functionality
  slide_interval    : 15000,    // Length between transitions
  transition        : 1,        // 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
  transition_speed  : 700,      // Speed of transition

  // Components							
  slide_links       : 'blank',  // Individual links for each slide (Options: false, 'number', 'name', 'blank')
  slides            : [
                        {image : 'images/iStock_000016603295XXXLarge.jpg'},
                        {image : 'images/Peru_Rush4.jpg'}
                      ]
});
");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

  <!-- CSS
  ================================================== -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/supersized/slideshow/css/supersized.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body class="<?php echo ($this->style == 'dark' ? 'dark' : ''); ?>">

  <header class="banner">
    <div class="affix affix-navbar" data-spy="affix" data-offset-top="40">
      <div class="container">
        <nav class="navbar <?php echo ($this->style == 'dark' ? 'navbar-default' : 'navbar-inverse'); ?>" role="navigation">
          <div class="container-fluid">
          
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="<?php echo Yii::app()->createAbsoluteUrl('site/index', $defaultParams); ?>">SwissWind</a>
            </div>
        
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            
              <ul class="nav navbar-nav navbar-right">
                <li <?php echo $indexMenu; ?>><a href="<?php echo $indexUrl ?>">Home</a></li>
                <li <?php echo $dataMenu ?>><a href="<?php echo $dataUrl ?>">Wind</a></li>
                <li <?php echo $infoMenu ?>><a href="<?php echo $infoUrl ?>">Info</a></li>
                <li <?php echo $clearMenu ?>><a href="<?php echo $clearUrl ?>">Clear</a></li>
                <li <?php echo $darkMenu ?>><a href="<?php echo $darkUrl ?>">Dark</a></li>
              </ul>
            </div>
            
          </div>
        </nav>
      </div>
    </div>
  </header>

  <div class="container content">
  
    <?php echo $content; ?>
    
  </div>
  
  <!-- JavaScript
  ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.min.js"></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/supersized/slideshow/js/supersized.3.2.7.min.js"></script>

</body>
</html>
