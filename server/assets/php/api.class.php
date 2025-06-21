<?php

class API {
    const string GET = "GET";
    const string POST = "POST";
    const string PUT = "PUT";
    const string DELETE = "DELETE";
    const string PATCH = "PATCH";
    const string NONE = "NONE";

    protected array $PathInfoArr = [];
    public function __construct(
        protected array $AllowedMethodsArr
    ) {
        $PathInfoStr = $_SERVER["PATH_INFO"] ?? DIRECTORY_SEPARATOR;
        if (str_starts_with($PathInfoStr, DIRECTORY_SEPARATOR)) {
            $PathInfoStr = substr($PathInfoStr, 1);
        }
        if (str_ends_with($PathInfoStr, DIRECTORY_SEPARATOR)) {
            $PathInfoStr = substr($PathInfoStr, 0, -1);
        }
        $this->PathInfoArr = explode(DIRECTORY_SEPARATOR, $PathInfoStr);
    }

    public function validate(): bool {
        if (empty($this->PathInfoArr[0])) {
            return false;
        }
        if (empty($this->AllowedMethodsArr[$this->PathInfoArr[0]])) {
            return false;
        }
        if ($this->AllowedMethodsArr[$this->PathInfoArr[0]] !== $this->getHttpMethod()) {
            return false;
        }
        if (!function_exists($this->PathInfoArr[0])) {
            $this->return("Invalid function", 400);
        }
        return true;
    }

    public function getPathInfoIndex(int $Index) {
        return $this->PathInfoArr[$Index] ?? null;
    }

    protected function isValidHttpRequest(): bool {
        return in_array($this->getHttpMethod(), $this->AllowedMethodsArr);
    }

    public function setHttpStatusCode(int $StatusCodeInt): bool {
        return http_response_code($StatusCodeInt);
    }
    public function getHttpMethod(): string {
        return $_SERVER["REQUEST_METHOD"] ?? self::NONE;
    }
    public function return(mixed $DataMix, int $StatusCodeInt = 400): never {
        header("Content-Type: application/json; charset=utf-8");
        $this->setHttpStatusCode($StatusCodeInt);
//        $DataMix = match (gettype($DataMix)) {
//            "string", "integer", "double", "NULL", "boolean" => ["data" => $DataMix],
//            default => $DataMix
//        };
        echo json_encode([
            "data" => $DataMix
        ]);
        die();
    }

    public function run(): never {
        try {
            $this->return($this->PathInfoArr[0]($this), 200);
        } catch (Throwable $ExceptionObj) {
            $this->return([
                "message" => $ExceptionObj->getMessage(),
                "file" => $ExceptionObj->getFile(),
                "line" => $ExceptionObj->getLine(),
                "trace" => $ExceptionObj->getTrace(),
            ], 200);
        }
    }

    public function call(string $UrlStr, array $PostDataArr = [], int $TimeOutSecondsInt = 120): bool|string {
        $CurlObj = curl_init();
        curl_setopt_array($CurlObj, [
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HEADER          => false,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_AUTOREFERER     => true,
            CURLOPT_CONNECTTIMEOUT  => $TimeOutSecondsInt,
            CURLOPT_TIMEOUT         => $TimeOutSecondsInt,
            CURLOPT_POSTFIELDS      => http_build_query($PostDataArr),
            CURLOPT_POST			=> true,
            CURLOPT_HTTPAUTH		=> CURLAUTH_BASIC,
            CURLOPT_URL				=> $UrlStr,
            CURLOPT_SSL_VERIFYPEER  => false
        ]);
        $ResponseStr = curl_exec($CurlObj);
        curl_close($CurlObj);
        return $ResponseStr;
    }
}