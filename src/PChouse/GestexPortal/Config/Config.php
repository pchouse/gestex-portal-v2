<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\Config;

use JetBrains\PhpStorm\ArrayShape;
use PChouse\GestexPortal\Di\Inject;

#[Inject]
class Config implements IConfig
{

    /**
     * Define id app is in debug mode or not
     *
     * @var bool
     */
    protected bool $debugMode;

    /**
     * config properties values
     *
     * @var array
     */
    protected array $ini = [];

    /**
     * @throws \Exception
     */
    public function __construct(protected IParseIniFile $parseIniFile)
    {

        $this->ini = \array_change_key_case(
            $this->parseIniFile->getConfigOptions()
        );

        $debug = \strtolower(
            $this->ini["debug"] ?? throw new \Exception("Debug not defined in gestex.properties")
        );

        $this->debugMode = match ($debug) {
            "1", "on", "true", "yes" => true,
            default                  => false,
        };
    }

    /**
     * If is debug mode or not
     */
    public function isDebugMode(): bool
    {
        return $this->debugMode;
    }

    /**
     * Get Twig environment options
     *
     * @return array
     */
    #[ArrayShape(
        [
            'cache'            => "false|string",
            'debug'            => "bool",
            'charset'          => "string",
            3                  => "mixed",
            4                  => "mixed",
            'strict_variables' =>
                "false",
            6                  => "mixed",
            7                  => "mixed"
        ]
    )]
    public function getTwigEnvironmentOptions(): array
    {
        return [
            'cache'            => $this->isDebugMode() ? false : GESTEX_CACHE_TWIG,
            'debug'            => $this->isDebugMode(),
            'charset'          => 'utf-8',
            /**
             * When developing with Twig, it's useful to recompile the template
             * whenever the source code changes. If you don't provide a value
             * for the auto_reload option, it will be determined
             * automatically based on the debug value.
             *
             */
            //'auto_reload'=>true,
            /**
             * If set to false, Twig will silently ignore invalid variables
             * (variables and or attributes/methods that do not exist) and
             * replace them with a null value. When set to true,
             * Twig throws an exception instead (default to false).
             */
            'strict_variables' => false,
            /**
             * Sets the default auto-escaping strategy
             * (name, html, js, css, url, html_attr, or a PHP callback that
             * takes the template "filename" and returns the escaping strategy
             * to use -- the callback cannot be a function name to avoid
             * collision with built-in escaping strategies);
             * set it too false to disable auto-escaping.
             * The name escaping strategy determines the escaping strategy
             * to use for a template based on the template filename extension
             * (this strategy does not incur any overhead at runtime as
             * auto-escaping is done at compilation time.)
             */
            //autoescape => '',

            /**
             * A flag that indicates which optimizations to apply
             * (default to -1 -- all optimizations are enabled; set it to 0 to disable).
             */
            //'optimizations' => -1,
        ];
    }

    /**
     * Get the SMTP host
     *
     * @return string
     */
    public function getSmtpHost(): string
    {
        return $this->ini["smtp_host"] ?? "";
    }

    /**
     * Get the smpt server port
     *
     * @return int
     */
    public function getSmtpPort(): int
    {
        return \intval($this->ini["smtp_port"] ?? 0);
    }

    /**
     * Get the SMTP Authorization type
     *
     * @return string
     */
    public function getSmtpAuthType(): string
    {
        return $this->ini["smtp_auth_type"];
    }

    /**
     * Get the SMTP user
     *
     * @return string
     */
    public function getSmtpUser(): string
    {
        return $this->ini["smtp_user"];
    }

    /**
     * Get the SMTP password
     *
     * @return string
     */
    public function getSmtpPassword(): string
    {
        return $this->ini["smtp_password"];
    }

    /**
     * Get the SMTP password
     *
     * @return string|null
     */
    public function getSmtpSsl(): ?string
    {
        return \in_array($this->ini["smtp_ssl"] ?? null, ["ssl", "tls"]) ?
            $this->ini["smtp_ssl"] : null;
    }

    /**
     * Get the SMTP from address
     *
     * @return string
     */
    public function getSmtpFromAddress(): string
    {
        return $this->ini["smtp_from_address"] ?? "";
    }

    /**
     * Get the SMTP address form name
     *
     * @return string
     */
    public function getSmtpFromName(): string
    {
        return $this->ini["smtp_from_name"] ?? "";
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getApiUrl(): string
    {
        return $this->ini["api_url"] ?? throw new \Exception("API URL not defined in gestex.properties");
    }
}
