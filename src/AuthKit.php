<?php

namespace Tripteki\AuthToken;

abstract class AuthKit
{
    /**
     * Session-Token (stateful)
     *
     * @var string
     */
    const SANCTUM_AUTH_KIT = "sanctum";

    /**
     * Token (stateless)
     *
     * @var string
     */
    const JWT_AUTH_KIT = "jwt";

    /**
     * Token OAuth (stateful)
     *
     * @var string
     */
    const PASSPORT_AUTH_KIT = "passport";

    /**
     * @return string
     */
    public static function guard()
    {
        if (static::isSanctum()) {

            return "sanctum";
        }

        return "api";
    }

    /**
     * @return bool
     */
    public static function isSanctum()
    {
        $sanctumVendorName = "Laravel\Sanctum\Sanctum";
        $jwtVendorName = "Tymon\JWTAuth\JWT";
        $passportVendorName = "Laravel\Passport\Passport";

        if (class_exists($sanctumVendorName) && ! class_exists($jwtVendorName) && ! class_exists($passportVendorName)) {

            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public static function isJWT()
    {
        $sanctumVendorName = "Laravel\Sanctum\Sanctum";
        $jwtVendorName = "Tymon\JWTAuth\JWT";
        $passportVendorName = "Laravel\Passport\Passport";

        if (class_exists($jwtVendorName) && ! class_exists($sanctumVendorName) && ! class_exists($passportVendorName)) {

            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public static function isPassport()
    {
        $sanctumVendorName = "Laravel\Sanctum\Sanctum";
        $jwtVendorName = "Tymon\JWTAuth\JWT";
        $passportVendorName = "Laravel\Passport\Passport";

        if (class_exists($passportVendorName) && ! class_exists($sanctumVendorName) && ! class_exists($jwtVendorName)) {

            return true;
        }

        return false;
    }
};
