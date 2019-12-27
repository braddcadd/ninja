<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/vision/v1/text_annotation.proto

namespace Google\Cloud\Vision\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Detected start or end of a structural component.
 *
 * Generated from protobuf message <code>google.cloud.vision.v1.TextAnnotation.DetectedBreak</code>
 */
class TextAnnotation_DetectedBreak extends \Google\Protobuf\Internal\Message
{
    /**
     * Detected break type.
     *
     * Generated from protobuf field <code>.google.cloud.vision.v1.TextAnnotation.DetectedBreak.BreakType type = 1;</code>
     */
    private $type = 0;
    /**
     * True if break prepends the element.
     *
     * Generated from protobuf field <code>bool is_prefix = 2;</code>
     */
    private $is_prefix = false;

    public function __construct() {
        \GPBMetadata\Google\Cloud\Vision\V1\TextAnnotation::initOnce();
        parent::__construct();
    }

    /**
     * Detected break type.
     *
     * Generated from protobuf field <code>.google.cloud.vision.v1.TextAnnotation.DetectedBreak.BreakType type = 1;</code>
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Detected break type.
     *
     * Generated from protobuf field <code>.google.cloud.vision.v1.TextAnnotation.DetectedBreak.BreakType type = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setType($var)
    {
        GPBUtil::checkEnum($var, \Google\Cloud\Vision\V1\TextAnnotation_DetectedBreak_BreakType::class);
        $this->type = $var;

        return $this;
    }

    /**
     * True if break prepends the element.
     *
     * Generated from protobuf field <code>bool is_prefix = 2;</code>
     * @return bool
     */
    public function getIsPrefix()
    {
        return $this->is_prefix;
    }

    /**
     * True if break prepends the element.
     *
     * Generated from protobuf field <code>bool is_prefix = 2;</code>
     * @param bool $var
     * @return $this
     */
    public function setIsPrefix($var)
    {
        GPBUtil::checkBool($var);
        $this->is_prefix = $var;

        return $this;
    }

}

