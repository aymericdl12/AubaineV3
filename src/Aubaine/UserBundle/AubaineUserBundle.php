<?php

namespace Aubaine\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AubaineUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
