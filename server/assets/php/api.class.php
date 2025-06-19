<?php

class API {
    const string GET = "GET";
    const string POST = "POST";
    const string PUT = "PUT";
    const string DELETE = "DELETE";
    const string PATCH = "PATCH";
    const string NONE = "NONE";

    public function __construct(
        protected array $AllowedMethodsArr
    ) {
//        if (!$this->isValidHttpRequest()) {
//            $this->setHttpStatusCode();
//            $this->return("Invalid Request: `{$this->getHttpMethod()}`");
//        }
    }

    protected function isValidHttpRequest(): bool {
        return in_array($this->getHttpMethod(), $this->AllowedMethodsArr);
    }

    public function setHttpStatusCode(int $StatusCodeInt = 400): bool {
        return http_response_code($StatusCodeInt);
    }
    public function getHttpMethod(): string {
        return $_SERVER["REQUEST_METHOD"] ?? self::NONE;
    }
    public function return(mixed $DataMix): never {
        header("Content-Type: application/json; charset=utf-8");
        $DataMix = match (gettype($DataMix)) {
            "string", "integer", "double" => ["data" => $DataMix],
            default => $DataMix
        };
        echo json_encode($DataMix);
        die();
    }
}