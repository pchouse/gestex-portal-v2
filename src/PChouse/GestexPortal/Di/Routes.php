<?php
declare(strict_types=1);

use PChouse\GestexPortal\Di\Bind;
use PChouse\GestexPortal\Di\Scope;
use PChouse\GestexPortal\MVC\Candidato\CandidatoController;
use PChouse\GestexPortal\MVC\Candidato\ICandidatoController;
use PChouse\GestexPortal\MVC\Dashboard\DashboardController;
use PChouse\GestexPortal\MVC\Dashboard\IDashboardController;
use PChouse\GestexPortal\MVC\Escola\EscolaController;
use PChouse\GestexPortal\MVC\Escola\IEscolaController;
use PChouse\GestexPortal\MVC\Exame\ExameController;
use PChouse\GestexPortal\MVC\Exame\IExameController;
use PChouse\GestexPortal\MVC\Facturacao\FacturacaoController;
use PChouse\GestexPortal\MVC\Facturacao\IFacturacaoController;
use PChouse\GestexPortal\MVC\FacturacaoDetalhe\FacturacaoDetalheController;
use PChouse\GestexPortal\MVC\FacturacaoDetalhe\IFacturacaoDetalheController;
use PChouse\GestexPortal\MVC\Licenca\ILicencaController;
use PChouse\GestexPortal\MVC\Licenca\LicencaController;
use PChouse\GestexPortal\MVC\Login\ILoginController;
use PChouse\GestexPortal\MVC\Login\LoginController;
use PChouse\GestexPortal\MVC\Start\IStartController;
use PChouse\GestexPortal\MVC\Start\StartController;

return [
    new Bind(Scope::ROUTE, ILoginController::class, LoginController::class),
    new Bind(Scope::ROUTE, IStartController::class, StartController::class),
    new Bind(Scope::ROUTE, IEscolaController::class, EscolaController::class),
    new Bind(Scope::ROUTE, IFacturacaoController::class, FacturacaoController::class),
    new Bind(Scope::ROUTE, IFacturacaoDetalheController::class, FacturacaoDetalheController::class),
    new Bind(Scope::ROUTE, ICandidatoController::class, CandidatoController::class),
    new Bind(Scope::ROUTE, IExameController::class, ExameController::class),
    new Bind(Scope::ROUTE, ILicencaController::class, LicencaController::class),
    new Bind(Scope::ROUTE, IDashboardController::class, DashboardController::class),
];
