<?php
declare(strict_types=1);

use GuzzleHttp\Client;
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;
use Laminas\Mail\Header\HeaderLocator;
use Laminas\Mail\Message;
use Laminas\Mail\Transport\Smtp;
use Laminas\Mail\Transport\SmtpOptions;
use Laminas\Validator\EmailAddress;
use PChouse\GestexPortal\Config\Config;
use PChouse\GestexPortal\Config\IConfig;
use PChouse\GestexPortal\Config\IParseIniFile;
use PChouse\GestexPortal\Config\ParseIniFile;
use PChouse\GestexPortal\Di\Bind;
use PChouse\GestexPortal\Di\Container;
use PChouse\GestexPortal\Di\Scope;
use PChouse\GestexPortal\Helpers\DateProvider;
use PChouse\GestexPortal\Helpers\Globals;
use PChouse\GestexPortal\Helpers\HtmlMimePart;
use PChouse\GestexPortal\Helpers\IDateProvider;
use PChouse\GestexPortal\Helpers\IGlobals;
use PChouse\GestexPortal\Helpers\IPdf;
use PChouse\GestexPortal\Helpers\IRequest;
use PChouse\GestexPortal\Helpers\IResponse;
use PChouse\GestexPortal\Helpers\IResponseHttpHeader;
use PChouse\GestexPortal\Helpers\IRouter;
use PChouse\GestexPortal\Helpers\ISession;
use PChouse\GestexPortal\Helpers\Pdf;
use PChouse\GestexPortal\Helpers\PdfMimePart;
use PChouse\GestexPortal\Helpers\Request;
use PChouse\GestexPortal\Helpers\Response;
use PChouse\GestexPortal\Helpers\ResponseHttpHeader;
use PChouse\GestexPortal\Helpers\Router;
use PChouse\GestexPortal\Helpers\Session;
use PChouse\GestexPortal\Helpers\Util;
use PChouse\GestexPortal\MVC\Candidato\CandidatoModel;
use PChouse\GestexPortal\MVC\Candidato\CandidatoView;
use PChouse\GestexPortal\MVC\Candidato\ICandidatoModel;
use PChouse\GestexPortal\MVC\Candidato\ICandidatoView;
use PChouse\GestexPortal\MVC\Dashboard\DashboardModel;
use PChouse\GestexPortal\MVC\Dashboard\DashboardView;
use PChouse\GestexPortal\MVC\Dashboard\IDashboardModel;
use PChouse\GestexPortal\MVC\Dashboard\IDashboardView;
use PChouse\GestexPortal\MVC\Escola\Escola;
use PChouse\GestexPortal\MVC\Escola\EscolaModel;
use PChouse\GestexPortal\MVC\Escola\EscolaView;
use PChouse\GestexPortal\MVC\Escola\IEscola;
use PChouse\GestexPortal\MVC\Escola\IEscolaModel;
use PChouse\GestexPortal\MVC\Escola\IEscolaView;
use PChouse\GestexPortal\MVC\Exame\ExameHelper;
use PChouse\GestexPortal\MVC\Exame\ExameModel;
use PChouse\GestexPortal\MVC\Exame\ExameView;
use PChouse\GestexPortal\MVC\Exame\IExameHelper;
use PChouse\GestexPortal\MVC\Exame\IExameModel;
use PChouse\GestexPortal\MVC\Exame\IExameView;
use PChouse\GestexPortal\MVC\Facturacao\FacturacaoModel;
use PChouse\GestexPortal\MVC\Facturacao\FacturacaoView;
use PChouse\GestexPortal\MVC\Facturacao\IFacturacaoModel;
use PChouse\GestexPortal\MVC\Facturacao\IFacturacaoView;
use PChouse\GestexPortal\MVC\FacturacaoDetalhe\FacturacaoDetalheModel;
use PChouse\GestexPortal\MVC\FacturacaoDetalhe\FacturacaoDetalheView;
use PChouse\GestexPortal\MVC\FacturacaoDetalhe\IFacturacaoDetalheModel;
use PChouse\GestexPortal\MVC\FacturacaoDetalhe\IFacturacaoDetalheView;
use PChouse\GestexPortal\MVC\Licenca\ILicencaModel;
use PChouse\GestexPortal\MVC\Licenca\ILicencaView;
use PChouse\GestexPortal\MVC\Licenca\LicencaModel;
use PChouse\GestexPortal\MVC\Licenca\LicencaView;
use PChouse\GestexPortal\MVC\Login\ILogin;
use PChouse\GestexPortal\MVC\Login\ILoginModel;
use PChouse\GestexPortal\MVC\Login\ILoginView;
use PChouse\GestexPortal\MVC\Login\Login;
use PChouse\GestexPortal\MVC\Login\LoginModel;
use PChouse\GestexPortal\MVC\Login\LoginView;
use PChouse\GestexPortal\MVC\Start\IStart;
use PChouse\GestexPortal\MVC\Start\IStartModel;
use PChouse\GestexPortal\MVC\Start\IStartView;
use PChouse\GestexPortal\MVC\Start\Start;
use PChouse\GestexPortal\MVC\Start\StartModel;
use PChouse\GestexPortal\MVC\Start\StartView;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return [

    // Singletons
    new Bind(Scope::SINGLETON, IDateProvider::class, DateProvider::class),
    new Bind(Scope::SINGLETON, IGlobals::class, Globals::class),
    new Bind(Scope::SINGLETON, IRequest::class, Request::class),
    new Bind(Scope::SINGLETON, ISession::class, Session::class),
    new Bind(Scope::SINGLETON, IRouter::class, Router::class),
    new Bind(Scope::SINGLETON, IResponseHttpHeader::class, ResponseHttpHeader::class),
    new Bind(Scope::SINGLETON, IParseIniFile::class, ParseIniFile::class),
    new Bind(Scope::SINGLETON, IConfig::class, Config::class),
    new Bind(Scope::SINGLETON, IResponse::class, Response::class),
    new Bind(Scope::TRANSIENT, IPdf::class, Pdf::class),

    new Bind(
        Scope::SINGLETON,
        EmailAddress::class,
        fn() => (new EmailAddress())->setAllow(Laminas\Validator\Hostname::ALLOW_DNS)->useDomainCheck(false)
    ),
    new Bind(
        Scope::SINGLETON,
        ComputerPasswordGenerator::class,
        fn() => (new ComputerPasswordGenerator())
            ->setOptionValue(ComputerPasswordGenerator::OPTION_UPPER_CASE, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_LOWER_CASE, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_NUMBERS, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_SYMBOLS, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_LENGTH, 9)
    ),

    // Providers
    new Bind(
        Scope::PROVIDES,
        Client::class,
        function () {
            $headers                 = [];
            $headers["Accept"]       = "application/json";
            $headers["Content-Type"] = "application/json";
            if (null !== $bearer = Container::get(ISession::class)->getString("token")) {
                $headers["Authorization"] = "Bearer " . $bearer;
            }

            return new Client(
                [
                    "base_uri" => Container::get(IConfig::class)->getApiUrl(),
                    "headers"  => $headers
                ]
            );
        }
    ),


    new Bind(
        Scope::PROVIDES,
        FilesystemLoader::class,
        fn() => new FilesystemLoader(GESTEX_MAIN_HTML)
    ),

    new Bind(
        Scope::PROVIDES,
        Environment::class,
        fn() => new Environment(
            Container::get(FilesystemLoader::class),
            Container::get(IConfig::class)->getTwigEnvironmentOptions()
        )
    ),

    new Bind(
        Scope::PROVIDES,
        Smtp::class,
        function () {
            $config  = Container::get(IConfig::class);
            $options = new SmtpOptions();
            $options->setConnectionClass($config->getSmtpAuthType());
            $options->setHost($config->getSmtpHost());
            $options->setPort($config->getSmtpPort());
            $options->setConnectionConfig(
                [
                    'username' => $config->getSmtpUser(),
                    'password' => $config->getSmtpPassword(),
                    'ssl'      => \in_array($config->getSmtpSsl(), ['ssl', 'tls']) ? $config->getSmtpSsl() : null
                ]
            );
            $smtp = new Smtp();
            $smtp->setOptions($options);
            return $smtp;
        }
    ),

    new Bind(
        Scope::PROVIDES,
        Message::class,
        function () {
            $config  = Container::get(IConfig::class);
            $message = new Message();

            $name = Util::isNullOrEmpty($config->getSmtpFromName()) ?
                null : $config->getSmtpFromName();

            $message->setFrom($config->getSmtpFromAddress(), $name);
            $message->setSender($config->getSmtpFromAddress(), $name);
            $header = new HeaderLocator();
            $header->add('Content-Type', 'multipart/related');
            $message->getHeaders()->setHeaderLocator($header);
            return $message;
        }
    ),

    new Bind(
        Scope::PROVIDES,
        HtmlMimePart::class,
        fn() => new HtmlMimePart()
    ),

    new Bind(
        Scope::PROVIDES,
        PdfMimePart::class,
        fn() => new PdfMimePart()
    ),

    //*************************************** MVC ************************************************//

    //Start
    new Bind(Scope::SINGLETON, IStartModel::class, StartModel::class),
    new Bind(Scope::SINGLETON, IStartView::class, StartView::class),
    new Bind(Scope::TRANSIENT, IStart::class, Start::class),

    //Login
    new Bind(Scope::SINGLETON, ILoginModel::class, LoginModel::class),
    new Bind(Scope::SINGLETON, ILoginView::class, LoginView::class),
    new Bind(Scope::TRANSIENT, ILogin::class, Login::class),

    //Escola
    new Bind(Scope::SINGLETON, IEscolaModel::class, EscolaModel::class),
    new Bind(Scope::SINGLETON, IEscolaView::class, EscolaView::class),
    new Bind(Scope::TRANSIENT, IEscola::class, Escola::class),

    //Faturacao
    new Bind(Scope::SINGLETON, IFacturacaoModel::class, FacturacaoModel::class),
    new Bind(Scope::SINGLETON, IFacturacaoView::class, FacturacaoView::class),

    //Faturacao
    new Bind(Scope::SINGLETON, IFacturacaoDetalheModel::class, FacturacaoDetalheModel::class),
    new Bind(Scope::SINGLETON, IFacturacaoDetalheView::class, FacturacaoDetalheView::class),

    //Candidato
    new Bind(Scope::SINGLETON, ICandidatoModel::class, CandidatoModel::class),
    new Bind(Scope::SINGLETON, ICandidatoView::class, CandidatoView::class),

    //Exame
    new Bind(Scope::SINGLETON, IExameModel::class, ExameModel::class),
    new Bind(Scope::SINGLETON, IExameView::class, ExameView::class),
    new Bind(Scope::SINGLETON, IExameHelper::class, ExameHelper::class),

    //Licenca
    new Bind(Scope::SINGLETON, ILicencaModel::class, LicencaModel::class),
    new Bind(Scope::SINGLETON, ILicencaView::class, LicencaView::class),

    //Dashboard
    new Bind(Scope::SINGLETON, IDashboardModel::class, DashboardModel::class),
    new Bind(Scope::SINGLETON, IDashboardView::class, DashboardView::class),

];
