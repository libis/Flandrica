<?php head(array('title' => 'Contact', 'bodyid'=>'items','bodyclass' => 'show')); ?>
<div class="clearfix"></div>

<div id="style_three">
    <div id="wrapper" class="cf">
    	<div id="container">
            <div id="content">
            	<div id="main" class="padding-left-20 padding-right-20">
				<h1><?php echo settings('simple_contact_form_contact_page_title'); ?></h1>

					<div id="simple-contact">
						<div id="form-instructions">
							<?php //echo get_option('simple_contact_form_contact_page_instructions'); // HTML ?>
						</div>
						<?php echo flash(); ?>
						<form name="contact_form" id="contact-form"  method="post" enctype="multipart/form-data" accept-charset="utf-8">

					        <fieldset>

					        <div class="field">
							<?php
							    echo $this->formLabel('name', 'Naam gebruiker: ');
							    echo $this->formText('name', $name, array('class'=>'textinput')); ?>
							</div>

					        <div class="field">
					            <?php
					            echo $this->formLabel('email', 'E-mail gebruiker: ');
							    echo $this->formText('email', $email, array('class'=>'textinput'));  ?>
					        </div>

					        <div class="field">
							<?php
								if($_GET['onderwerp'])
									$value = $_GET['onderwerp'];
								else
									$value = 'Vraag naar bijkomende informatie';

								$title = new Zend_Form_Element_Select('onderwerp');
					        	$title->setLabel('Onderwerp')
					        			//hier een $_GET['onderwerp']
					        			->setValue($value)
					        	   	  	->setMultiOptions(array(
							    		'Melden lacune' => 'Melden lacune',
							    		'Vraag naar bijkomende informatie' => 'Vraag naar bijkomende informatie',
							    		'Melden copyrightprobleem' => 'Melden copyrightprobleem',
							    		'Vraag stellen over object' => 'Vraag stellen over object',
							   			'Problemen melden' => 'Problemen melden',
							    		'Overig' => 'Overig'
							    		))
					              		->setRequired(true)->addValidator('NotEmpty', true);
					        	echo $title;
					        ?>
					        </div>

							<div class="field">
							  <?php
							  	echo "<br>";
							    //echo $this->formLabel('message', 'Berichttekst: ')."<br>";
							    echo $this->formTextarea('message', $_GET['message'], array('class'=>'textinput')); ?>
							</div>

							</fieldset>

							<fieldset>
							<br>
							<div class="field">
							  <?php echo $captcha; ?>
							</div>
							<br>
							<div class="field">
							  <?php echo $this->formSubmit('send', 'Verstuur'); ?>
							</div>

						    </fieldset>
						</form>
						<br>
					</div>
			</div>
			</div>
			<div class="clearfix">&nbsp;</div>
		</div>
	</div>
	</div>
</div>
<?php foot(); ?>