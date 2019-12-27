<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/container/v1/cluster_service.proto

namespace Google\Cloud\Container\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * NodePoolAutoscaling contains information required by cluster autoscaler to
 * adjust the size of the node pool to the current cluster usage.
 *
 * Generated from protobuf message <code>google.container.v1.NodePoolAutoscaling</code>
 */
class NodePoolAutoscaling extends \Google\Protobuf\Internal\Message
{
    /**
     * Is autoscaling enabled for this node pool.
     *
     * Generated from protobuf field <code>bool enabled = 1;</code>
     */
    private $enabled = false;
    /**
     * Minimum number of nodes in the NodePool. Must be >= 1 and <=
     * max_node_count.
     *
     * Generated from protobuf field <code>int32 min_node_count = 2;</code>
     */
    private $min_node_count = 0;
    /**
     * Maximum number of nodes in the NodePool. Must be >= min_node_count. There
     * has to enough quota to scale up the cluster.
     *
     * Generated from protobuf field <code>int32 max_node_count = 3;</code>
     */
    private $max_node_count = 0;

    public function __construct() {
        \GPBMetadata\Google\Container\V1\ClusterService::initOnce();
        parent::__construct();
    }

    /**
     * Is autoscaling enabled for this node pool.
     *
     * Generated from protobuf field <code>bool enabled = 1;</code>
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Is autoscaling enabled for this node pool.
     *
     * Generated from protobuf field <code>bool enabled = 1;</code>
     * @param bool $var
     * @return $this
     */
    public function setEnabled($var)
    {
        GPBUtil::checkBool($var);
        $this->enabled = $var;

        return $this;
    }

    /**
     * Minimum number of nodes in the NodePool. Must be >= 1 and <=
     * max_node_count.
     *
     * Generated from protobuf field <code>int32 min_node_count = 2;</code>
     * @return int
     */
    public function getMinNodeCount()
    {
        return $this->min_node_count;
    }

    /**
     * Minimum number of nodes in the NodePool. Must be >= 1 and <=
     * max_node_count.
     *
     * Generated from protobuf field <code>int32 min_node_count = 2;</code>
     * @param int $var
     * @return $this
     */
    public function setMinNodeCount($var)
    {
        GPBUtil::checkInt32($var);
        $this->min_node_count = $var;

        return $this;
    }

    /**
     * Maximum number of nodes in the NodePool. Must be >= min_node_count. There
     * has to enough quota to scale up the cluster.
     *
     * Generated from protobuf field <code>int32 max_node_count = 3;</code>
     * @return int
     */
    public function getMaxNodeCount()
    {
        return $this->max_node_count;
    }

    /**
     * Maximum number of nodes in the NodePool. Must be >= min_node_count. There
     * has to enough quota to scale up the cluster.
     *
     * Generated from protobuf field <code>int32 max_node_count = 3;</code>
     * @param int $var
     * @return $this
     */
    public function setMaxNodeCount($var)
    {
        GPBUtil::checkInt32($var);
        $this->max_node_count = $var;

        return $this;
    }

}

