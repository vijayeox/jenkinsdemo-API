<?php
namespace Oxzion\AppDelegate;

abstract class FileDelegate implements AppDelegate
{
	use UserContextTrait;
	use FileTrait;
}
