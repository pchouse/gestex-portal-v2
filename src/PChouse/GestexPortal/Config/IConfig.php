<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\Config;

interface IConfig
{
    const CENTRO_ALVARA = 130;

    /**
     * If is debug mode or not
     */
    public function isDebugMode(): bool;

    /**
     * Get Twig environment options
     *
     * @return array
     */
    public function getTwigEnvironmentOptions(): array;

    /**
     * Get the SMTP host
     *
     * @return string
     */
    public function getSmtpHost(): string;

    /**
     * Get the smpt server port
     *
     * @return int
     */
    public function getSmtpPort(): int;

    /**
     * Get the SMTP Authorization type
     *
     * @return string
     */
    public function getSmtpAuthType(): string;

    /**
     * Get the SMTP user
     *
     * @return string
     */
    public function getSmtpUser(): string;

    /**
     * Get the SMTP password
     *
     * @return string
     */
    public function getSmtpPassword(): string;

    /**
     * Get the SMTP password
     *
     * @return string|null
     */
    public function getSmtpSsl(): ?string;

    /**
     * Get the SMTP from address
     *
     * @return string
     */
    public function getSmtpFromAddress(): string;

    /**
     * Get the SMTP address form name
     *
     * @return string
     */
    public function getSmtpFromName(): string;

    public function getApiUrl(): string;
}
