<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\Helpers;

use Laminas\Mime\Mime;
use Laminas\Mime\Part;

class HtmlMimePart extends Part
{

    public function __construct()
    {
        parent::__construct();
        $this->setType(Mime::TYPE_HTML);
        $this->setCharset("utf-8");
        $this->setEncoding(Mime::ENCODING_QUOTEDPRINTABLE);
    }
}
