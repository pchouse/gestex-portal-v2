<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Login;

use GuzzleHttp\Client;
use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Container;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\Helpers\Util;
use PChouse\GestexPortal\MVC\AModel;

#[Inject]
#[Autowired]
class LoginModel extends AModel implements ILoginModel
{

    /**
     * Login from backoffice
     */
    const LOGIN_BKO = 1;

    /**
     * Login from catalog
     */
    const LOGIN_CAT = 2;

    /**
     * Login error wrong username or passsword
     */
    const LG_ERROR_CRED = 1;

    /**
     * Login error user not active
     */
    const LG_ERROR_NOT_ACT = 2;

    /**
     * Login error user must change password
     */
    const LG_ERROR_CHANGE_PWD = 4;

    public function __construct()
    {
        parent::__construct();
    }


    public function getMvcObjectName(): string
    {
        return ILogin::class;
    }

    public function getIdColumnName(): string
    {
        return "";
    }

    public function getTableName(): string
    {
        return "";
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return \PChouse\GestexPortal\MVC\Escola\IEscola
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function authenticate(string $username, string $password): void
    {

        $jsonBody     = \json_encode([
            "username" => $username,
            "password" => $password
        ]);

        $response = Container::get(Client::class)->post(
            "Escola/Login",
            [ "body" =>  $jsonBody]
        );

        if ($response->getStatusCode() !== 200) {
            throw new \Exception($response->getBody()->getContents());
        }

        $responseBody = $response->getBody()->getContents();
        $stdClassResponse = \json_decode($responseBody, false);

        if ((string)$stdClassResponse->status === "OK" &&
            Util::isNotNullOrEmpty((string)$stdClassResponse->instance->token)
        ) {
            $this->session->setUseAsLoggedIn();
            $this->session->putString("username", $username);
            $this->session->putString("password", $password);
            $this->session->putString("token", (string)$stdClassResponse->instance->token);
            $this->session->putString(
                "expiares",
                \substr((string)$stdClassResponse->instance->expiares, 0, 19)
            );
            return;
        }

        if ($stdClassResponse->error !== null) {
            throw new \Exception((string)$stdClassResponse->error->description);
        }

        throw new \Exception($responseBody);
    }
}
