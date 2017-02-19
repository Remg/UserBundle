<?php

namespace Remg\UserBundle\Util;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordUpdater
{
	private $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder)
	{
		$this->encoder = $encoder;
	}

	public function updatePassword(UserInterface $user)
	{
		$plainPassword = $user->getPlainPassword();

		if (0 === strlen($plainPassword)) {
			return;
		}

		$password = $this->encodePassword($user, $plainPassword);

		$user->setPassword($password);
		$user->setPlainPassword(null);
		$user->eraseCredentials();
	}

	private function encodePassword($user, $plainPassword)
	{
		return $this->encoder->encodePassword($user, $plainPassword);
	}
}