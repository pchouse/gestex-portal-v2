<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\Helpers;

use Laminas\Mime\Mime;
use Laminas\Mime\Part;

class PdfMimePart extends Part
{

    public function __construct()
    {
        parent::__construct();
        $this->setType("application/pdf");
        $this->disposition = Mime::DISPOSITION_ATTACHMENT;
        $this->encoding    = Mime::ENCODING_BASE64;
    }
}
