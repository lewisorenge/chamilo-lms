<?php
/* For licensing terms, see /license.txt */

namespace ChamiloLMS\Transaction;

use Exception as Exception;
use ChamiloLMS\Transaction\Plugin\WrapperPluginInterface;
use ChamiloLMS\Transaction\Plugin\WrapException;
use ChamiloLMS\Transaction\Plugin\UnwrapException;

/**
 * Represents a group of transactions to be exchanged from/to outside chamilo.
 */
class Envelope
{
    /**
     * Flag for state data member: envelope can behave as opened.
     */
    const STATE_OPEN = 0x1;
    /**
     * Flag for state data member: envelope can behave as closed.
     */
    const STATE_CLOSED = 0x2;
    /**
     * Status for received_envelopes table: Queued to be imported.
     */
    const RECEIVED_TO_BE_IMPORTED = 1;
    /**
     * Status for received_envelopes table: already imported on the local
     * system.
     */
    const RECEIVED_IMPORTED = 2;

    /**
     * The handled ChamiloLMS\Transaction\TransactionLog objects.
     *
     * @var array
     */
    protected $transactions = array();
    /**
     * A place to store the raw envelope when it is closed.
     *
     * It follows the format 'type,origin_branch_id:content', where:
     * - 'type': the wrapper plugin machine name.
     * - 'origin_branch_id': The branch id of the branch where the blob was
     *   generated.
     * - 'content': is the real blob as generated by the plugin.
     *
     * @var string
     */
    protected $blob = NULL;
    /**
     * Current state of the envelope.
     *
     * @var integer
     */
    protected $state = 0;
    /**
     * An instance of the wrapper plugin to use with this envelope.
     *
     * @var WrapperPluginInterface
     */
    protected $wrapperPlugin;
    /**
     * The branch id where the related envelope comes from if any.
     *
     * @var int
     */
    protected $originBranchId = false;

    /**
     * Basic constructor.
     *
     * @param WrapperPluginInterface $wrapper_plugin
     *   A ChamiloLMS\WrapperPluginInterface object.
     * @param array $data
     *   Information to build the object. The supported array keys are:
     *   - 'transactions': An array of ChamiloLMS\Transaction\TransactionLog
     *     objects to include. Required if 'blob' is not passed.
     *   - 'blob': A string containing the envelope in raw form. Required if
     *     'transactions' is not passed.
     *   - 'origin_branch_id': Associated branch id if any.
     */
    public function __construct(WrapperPluginInterface $wrapper_plugin, $data)
    {
        $this->wrapperPlugin = $wrapper_plugin;
        if (empty($data['transactions']) && empty($data['blob'])) {
            throw new Exception('Cannot create a envelope without transactions neither a blob.');
        }
        if (!empty($data['transactions'])) {
            $this->transactions = $data['transactions'];
            $this->state |= self::STATE_OPEN;
        }
        if (!empty($data['blob'])) {
            $this->blob = $data['blob'];
            $this->state |= self::STATE_CLOSED;
        }
        if (!empty($data['origin_branch_id'])) {
            $this->originBranchId = $data['origin_branch_id'];
        }
    }

    /**
     * Get transactions.
     *
     * @return mixed
     *   This object transactions as array if open, or NULL if not opened.
     */
    public function getTransactions() {
        if ($this->state & self::STATE_OPEN) {
            return $this->transactions;
        }
        return null;
    }

    /**
     * Get blob.
     *
     * @return mixed
     *   Raw envelope as string if closed, or NULL if not closed.
     */
    public function getBlob() {
        if ($this->state & self::STATE_CLOSED) {
            return $this->blob;
        }
        return null;
    }

    /**
     * Get related branch id.
     *
     * @return mixed
     *   Branch id if defined or null if not available.
     */
    public function getOriginBranchId() {
        if (!empty($this->originBranchId)) {
            return $this->originBranchId;
        }
        return null;
    }

    /**
     * Set related branch id.
     *
     * @param integer $branch_id
     *   Related branch id.
     */
    public function setOriginBranchId($branch_id) {
        $this->originBranchId =  $branch_id;
    }

    /**
     * Identifies a blob type from a blob.
     *
     * @param string $blob
     *   A raw blob.
     *
     * @throws Exception
     *   Cannot identify the blob correctly.
     *
     * @return array
     *   An array with the metadata. Contains the following keys:
     *   - 'blob_type': The wrapper plugin machine name.
     *   - 'origin_branch_id': The branch where the blob was generated.
     */
    public static function identifyBlobMetadata($blob) {
        $position = strpos($blob, ':');
        if ($position === FALSE) {
            throw new Exception('blob identify: Cannot find ":" on the blob.');
        }
        $blob_metadata = substr($blob, 0, $position);
        if ($blob_metadata === FALSE) {
            throw new Exception('blob identify: Cannot extract correctly the blob metadata.');
        }
        list($blob_type, $origin_branch_id) = explode(',', $blob_metadata);

        return array('blob_type' => $blob_type, 'origin_branch_id' => $origin_branch_id);
    }

    /**
     * Wraps the transactions storing it on the blob data member.
     *
     * @throws WrapException
     *   On any error.
     */
    public function wrap() {
        if (!($this->state & self::STATE_OPEN)) {
            throw new WrapException('Trying to wrap an evelope without transactions.');
        }
        try {
            $this->prepare();
            $this->blob = sprintf('%s,%d:%s', $this->wrapperPlugin->getMachineName(), $this->getOriginBranchId(), $this->wrapperPlugin->wrap($this->transactions));
            $this->state |= self::STATE_CLOSED;
        }
        catch (Exception $exception) {
            throw new WrapException('Unable to wrap correctly the transactions. ' . $exception->getMessage());
        }
    }

    /**
     * Unwraps the transactions storing them on the transactions data member.
     *
     * @throws WrapException
     *   On any error.
     */
    public function unwrap() {
        if (!($this->state & self::STATE_CLOSED)) {
            throw new UnwrapException('Trying to unwrap an evelope without a blob.');
        }
        try {
            $blob_metadata = self::identifyBlobMetadata($this->blob);
            // Extract the plugin name before passing the blob to the plugin.
            $position = strpos($this->blob, ':');
            if ($position === FALSE) {
                throw new UnwrapException('Invalid blob format passed: No colon.');
            }
            $blob = substr($this->blob, $position + 1);
            if ($blob === FALSE) {
                throw new UnwrapException('Invalid blob format passed: Empty content.');
            }
            $this->transactions = $this->wrapperPlugin->unwrap($blob, $blob_metadata);
            $this->state |= self::STATE_OPEN;
        }
        catch (Exception $exception) {
            throw new UnwrapException('Unable to unwrap correctly the envelope. ' . $exception->getMessage());
        }
    }

    /**
     * Prepares the transactions to be wrapped.
     *
     * @throws WrapException
     *   On any error.
     */
    protected function prepare() {
        $error_messages = '';

        foreach ($this->transactions as $transaction) {
            try {
                $transaction->export();
            } catch (Exception $export_exception) {
                $error_messages .= sprintf("%s: %s\n", $transaction->id, $export_exception->getMessage());
            }
        }
        if (!empty($error_messages)) {
            throw new WrapException(sprintf("Failed to prepare some transactions:\n%s", $error_messages));
        }
    }

    /**
     * Retrieves the names of for the received evelopes possible status.
     *
     * @return array
     *   A list containing the names keyed by status id.
     */
    public static function getStatusNames() {
        // @todo Translate.
        return array(
            self::RECEIVED_TO_BE_IMPORTED => 'Pending to import',
            self::RECEIVED_IMPORTED => 'Imported',
        );
    }

}
