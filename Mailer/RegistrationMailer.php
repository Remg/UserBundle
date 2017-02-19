<?php

namespace Remg\UserBundle\Mailer;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Swift_Mailer;
use Swift_Message;
use Twig_Environment;

class RegistrationMailer
{
    /**
     * @var Swift_Mailer
     */
    protected $mailer;
    /**
     * @var UrlGeneratorInterface
     */
    protected $router;
    /**
     * @var Twig_Environment
     */
    protected $twig;

	public function __construct(Swift_Mailer $mailer, UrlGeneratorInterface $router, Twig_Environment $twig)
	{
		$this->mailer = $mailer;
		$this->router = $router;
		$this->twig = $twig;
	}

    protected function sendMessage($templateName, $context, $fromEmail, $toEmail)
    {
        $context = $this->twig->mergeGlobals($context);
        $template = $this->twig->loadTemplate($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        $message = Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail);

        if (!empty($htmlBody)) {
            $message
            	->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }

        return $this->send($message);
    }

    public function send(Swift_Message $message)
    {
    	$this->mailer->send($message);
    }

	public function sendConfirmationEmail($user)
	{
        return $this->sendMessage(
        	'RemgUserBundkle:registration:email.html.twig',
        	[
        		'user' => $user,
        		'confirmation_url' => $this->getConfirmationUrl(),
        	],
        	['remi.gardien99@gmail.com' => 'Symfony Application']
        	$user->getEmail(),
        );
	}

	public function getConfirmationUrl()
	{
		return $this->router->generate(
	        'user_registration_confirm',
	        ['confirmationToken' => $user->getConfirmationToken()],
	        UrlGeneratorInterface::ABSOLUTE_URL
         );
	}
}