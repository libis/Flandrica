<?php head(array('title' => 'Contact', 'bodyid'=>'items','bodyclass' => 'show')); ?>
<div class="clearfix"></div>

<div id="style_three">
    <div id="wrapper" class="cf">
    	<div id="container">
            <div id="content">
            	<div id="main" class="padding-left-20 padding-right-20">
				<h1><?php echo __("Contact us");//echo settings('simple_contact_form_contact_page_title'); ?></h1>

					<div id="simple-contact">
						<div id="form-instructions">
							<?php //echo get_option('simple_contact_form_contact_page_instructions'); // HTML ?>
						</div>
						<?php echo flash(); ?>
						<form name="contact_form" id="contact-form"  method="post" enctype="multipart/form-data" accept-charset="utf-8">

					        <fieldset>

					        <div class="field">
							<?php
							    echo $this->formLabel('name', __('Name:'));
							    echo $this->formText('name', $name, array('class'=>'textinput')); ?>
							</div>

					        <div class="field">
					            <?php
					            echo $this->formLabel('email',__('Email:'));
							    echo $this->formText('email', $email, array('class'=>'textinput'));  ?>
					        </div>

					        <div class="field">
							<?php
								if($_GET['onderwerp'])
									$value = $_GET['onderwerp'];
								else
									$value = __('Ask extra information');

								$title = new Zend_Form_Element_Select('onderwerp');
                                                                $title->setLabel(__('Subject'))
					        			//hier een $_GET['onderwerp']
					        			->setValue($value)
					        	   	  	->setMultiOptions(array(
							    		__("Ask extra information") => __("Ask extra information"),
							    		__("Report missing data") => __("Report missing data"),
							    		__("Report copyright problem") => __("Report copyright problem"),
							    		__("Question about an object") => __("Question about an object"),
                                                                        __("Report problems") => __("Report problems"),
							    		__("Other") => __("Other")
							    		))
					              		->setRequired(true)->addValidator('NotEmpty', true);
					        	echo $title;
					        ?>
					        </div>
                                                                                                       
                                                <?php
                                                $collections = get_collections();
                                                if($collections):
                                                    asort($collections);
                                                ?>        
                                                <div class="field">
							<?php
								if($_GET['instelling'])
									$value = $_GET['instelling'];
								else
									$value = __('Algemeen');
                                                                
                                                                $options = array();
                                                                foreach ($collections as $collection):
                                                                    $options[$collection->name] = $collection->name;
                                                                endforeach;
                                                                $options["Algemeen"] = __("Algemeen");
                                                                
								$title = new Zend_Form_Element_Select('instelling');
                                                                $title->setLabel(__('Instelling'))
					        			//hier een $_GET['onderwerp']
					        			->setValue($value)
					        	   	  	->setMultiOptions($options)				              		->setRequired(true)->addValidator('NotEmpty', true);
					        	echo $title;
					        ?>
					        </div>
                                                <?php endif;?>            
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
							  <?php echo $this->formSubmit('send', __("Send")); ?>
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