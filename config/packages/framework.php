<?php

use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework): void {

	$framework->secret($_ENV["APP_SECRET"]);

    $framework->phpErrors()->throw($_ENV["APP_ENV"] === "dev");

};
