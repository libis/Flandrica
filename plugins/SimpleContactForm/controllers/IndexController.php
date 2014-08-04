<?php
class SimpleContactForm_IndexController extends Omeka_Controller_Action
{
	public function indexAction()
	{
	    $name = isset($_POST['name']) ? $_POST['name'] : '';

		$email = isset($_POST['email']) ? $_POST['email'] : '';;
		$message = isset($_POST['message']) ? $_POST['message'] : '';;
                
                $onderwerp = isset($_POST['onderwerp']) ? $_POST['onderwerp'] : '';;
                $instelling = isset($_POST['instelling']) ? $_POST['instelling'] : '';;

	    $captchaObj = $this->_setupCaptcha();

	    if ($this->getRequest()->isPost()) {
    		// If the form submission is valid, then send out the email
    		if ($this->_validateFormSubmission($captchaObj)) {
				$this->sendEmailNotification($_POST['email'], $_POST['name'], $_POST['message'],$onderwerp,$instelling);
	            $this->redirect->gotoRoute(array(), 'simple_contact_form_thankyou');
    		}
	    }

	    // Render the HTML for the captcha itself.
	    // Pass this a blank Zend_View b/c ZF forces it.
		if ($captchaObj) {
		    $captcha = $captchaObj->render(new Zend_View);
		} else {
		    $captcha = '';
		}

		$this->view->assign(compact('name','email','message', 'captcha'));
	}

	public function thankyouAction()
	{

	}

	protected function _validateFormSubmission($captcha = null)
	{
	    $valid = true;
	    $msg = $this->getRequest()->getPost('message');
	    $email = $this->getRequest()->getPost('email');
	    // ZF ReCaptcha ignores the 1st arg.
	   /* if ($captcha and !$captcha->isValid('foo', $_POST)) {
            $this->flashError('Your CAPTCHA submission was invalid, please try again.');
            $valid = false;
	    } else if (!Zend_Validate::is($email, 'EmailAddress')) {
            $this->flashError('Please enter a valid email address.');
            $valid = false;
	    } else if (empty($msg)) {
            $this->flashError('Please enter a message.');
            $valid = false;
	    }*/

	    return $valid;
	}

    protected function _setupCaptcha()
    {
        return Omeka_Captcha::getCaptcha();
    }

	protected function sendEmailNotification($formEmail, $formName, $formMessage,$onderwerp,$instelling) {

        if(empty($onderwerp)):
            $onderwerp = get_option('site_title') . ' - ' . get_option('simple_contact_form_admin_notification_email_subject');
        endif;    
        
        if($instelling = 'Algemeen'):
            $forwardToEmail = get_option('simple_contact_form_forward_to_email');
        else:
            $forwardToEmail = get_option('simple_contact_form_mail-'.$instelling);
        endif;
        
        //if still empty send to default address
        if(!$forwardToEmail):
             $forwardToEmail = get_option('simple_contact_form_forward_to_email');
        endif;
		         
        $bcc = get_option('simple_contact_form_bcc');

        if (!empty($forwardToEmail)) {
            $mail = new Zend_Mail();
            $mail->setBodyText(get_option('simple_contact_form_admin_notification_email_message_header') . "\n\n" . $formMessage);
            $mail->setFrom($formEmail, $formName);
            $mail->addTo($forwardToEmail);
            $mail->addBcc($bcc);
            $mail->setSubject(get_option('site_title') . ' - ' . get_option('simple_contact_form_admin_notification_email_subject'));
            $mail->send();
        }

        //notify the user who sent the message
        $replyToEmail = get_option('simple_contact_form_reply_from_email');
        if (!empty($replyToEmail)) {
            $mail = new Zend_Mail();
            $mail->setBodyText(get_option('simple_contact_form_user_notification_email_message_header') . "\n\n" . $formMessage);
            $mail->setFrom($replyToEmail);
            $mail->addTo($formEmail, $formName);
            $mail->setSubject(get_option('site_title') . ' - ' . get_option('simple_contact_form_user_notification_email_subject'));
            $mail->send();
        }
	}
}
