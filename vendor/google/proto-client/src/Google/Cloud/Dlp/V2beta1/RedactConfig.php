<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/privacy/dlp/v2beta1/dlp.proto

namespace Google\Cloud\Dlp\V2beta1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Redact a given value. For example, if used with an `InfoTypeTransformation`
 * transforming PHONE_NUMBER, and input 'My phone number is 206-555-0123', the
 * output would be 'My phone number is '.
 *
 * Generated from protobuf message <code>google.privacy.dlp.v2beta1.RedactConfig</code>
 */
class RedactConfig extends \Google\Protobuf\Internal\Message
{

    public function __construct() {
        \GPBMetadata\Google\Privacy\Dlp\V2Beta1\Dlp::initOnce();
        parent::__construct();
    }

}

