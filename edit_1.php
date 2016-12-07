<?php
/**
 * Class Message
 *
 * A class defining the logic around a message for use in SQS. The message is stored as JSON in the queue.
 
 */

namespace Gaw508\PhpSqsTutorial;

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use Gaw508\PhpSqsTutorial\Queue;

use Imagick;
use ImagickDraw;

class Edit
{
    /**
     * The path of the uploaded file to be processed
     *
     * @var string
     */
    public $input_file_path;

    /**
     * The path to output the processed file
     *
     * @var string
     */
    public $output_file_path;

    /**
     * The receipt handle from SQS, used to identify the message when interacting with the queue
     *
     * @var string
     */
    public $receipt_handle;

    /**
     * Construct the object with message data and optional receipt_handle if relevant
     *
     * @param string|array $data  JSON String or an assoc array containing the message data
     * @param string $receipt_handle  The sqs receipt handle of the message
     */
    public function __construct($data, $receipt_handle = '')
        {
            // If data is a json string, decode it into an assoc array
            if (is_string($data)) {
                $data = json_decode($data, true);
            }

            // Assign the data values and receipt handle to the object
            $this->input_file_path = $data['input_file_path'];
            $this->output_file_path = $data['output_file_path'];
            $this->receipt_handle = $receipt_handle;
        }

        /**
         * Returns the data of the message as a JSON string
         *
         * @return string  JSON message data
         */
    public function asJson()
    {
        return json_encode(array(
            'input_file_path' => $this->input_file_path,
            'output_file_path' => $this->output_file_path
        ));
    }

        /**
         * Processes an image given in the input file path, and outputs it in the output file path
         *
         * Takes the input image, creates a 300x300px thumbnail and overlays a text watermark.
         * Then deletes the input image.
         */
    public function process()
    {
           
        $im1 = new Imagick($this->input_file_path);
        $im2 = new Imagick("/var/www/html/swtichonarex.jpg");
        $imTotal = new Imagick();
        $im1->cropthumbnailimage(300, 300);
        $im2->cropthumbnailimage(80, 80);

        $imTotal->newimage(300, 300, '#ffffffff');

        $imTotal->compositeimage($im1, Imagick::COMPOSITE_DEFAULT, 0, 0);
        $imTotal->compositeimage($im2, Imagick::COMPOSITE_DEFAULT, 100, 100);

        $imTotal->setImageFormat('jpg');

        // Output the processed image to the output path (as .jpg)
        $output_path = explode('.', $this->output_file_path);
        array_pop($output_path);
        $output_path = implode($output_path) . '.jpg';
        $imTotal->writeImage($output_path);

        // Delete the input image
        unlink($this->input_file_path);
    }
    }
    class Queue
    {
        /**
         * The name of the SQS queue
         *
         * @var string
         */
        private $name;

        /**
         * The url of the SQS queue
         *
         * @var string
         */
        private $url;

        /**
         * The array of credentials used to connect to the AWS API
         *
         * @var array
         */
        private $aws_credentials;

        /**
         * A SqsClient object from the AWS SDK, used to connect to the AWS SQS API
         *
         * @var SqsClient
         */
        private $sqs_client;

        /**
         * Constructs the wrapper using the name of the queue and the aws credentials
         *
         * @param $name
         * @param $aws_credentials
         */
        public function __construct($name, $aws_credentials)
        {
            try {
                // Setup the connection to the queue
                $this->name = $name;
                $this->aws_credentials = $aws_credentials;
                $this->sqs_client = new SqsClient($this->aws_credentials);

                // Get the queue URL
                $this->url = $this->sqs_client->getQueueUrl(array('QueueName' => $this->name))->get('QueueUrl');
                } catch (Exception $e) {
                echo 'Error getting the queue url ' . $e->getMessage();
                }
                }

                    /**
                     * Sends a message to SQS using a JSON output from a given Message object
                     *
                     * @param Message $message  A message object to be sent to the queue
                     * @return bool  returns true if message is sent successfully, otherwise false
                     */
        public function send(Message $message)
                {
            try {
                    // Send the message
                $this->sqs_client->sendMessage(array(
                    'QueueUrl' => $this->url,
                    'MessageBody' => $message->asJson()
                ));

                return true;
                    } catch (Exception $e) {
                echo 'Error sending message to queue ' . $e->getMessage();
                return false;
                    }
                    }

                        /**
                         * Receives a message from the queue and puts it into a Message object
                         *
                         * @return bool|Message  Message object built from the queue, or false if there is a problem receiving message
                         */
        public function receive()
                    {
            try {
                        // Receive a message from the queue
                $result = $this->sqs_client->receiveMessage(array(
                    'QueueUrl' => $this->url
                ));

                if ($result['Messages'] == null) {
                    // No message to process
                    return false;
                }

                // Get the message and return it
                $result_message = array_pop($result['Messages']);
                return new Message($result_message['Body'], $result_message['ReceiptHandle']);
            } catch (Exception $e) {
                echo 'Error receiving message from queue ' . $e->getMessage();
                return false;
            }
        }

        /**
         * Deletes a message from the queue
         *
         * @param Message $message
         * @return bool  returns true if successful, false otherwise
         */
        public function delete(Message $message)
            {
                try {
                    // Delete the message
                    $this->sqs_client->deleteMessage(array(
                        'QueueUrl' => $this->url,
                        'ReceiptHandle' => $message->receipt_handle
                    ));

                    return true;
                } catch (Exception $e) {
                    echo 'Error deleting message from queue ' . $e->getMessage();
                    return false;
                }
            }

            /**
             * Releases a message back to the queue, making it visible again
             *
             * @param Message $message
             * @return bool  returns true if successful, false otherwise
             */
        public function release(Message $message)
            {
                try {
                    // Set the visibility timeout to 0 to make the message visible in the queue again straight away
                    $this->sqs_client->changeMessageVisibility(array(
                        'QueueUrl' => $this->url,
                        'ReceiptHandle' => $message->receipt_handle,
                        'VisibilityTimeout' => 0
                    ));

                    return true;
                        } catch (Exception $e) {
                    echo 'Error releasing job back to queue ' . $e->getMessage();
                    return false;
                        }
                        }

            <?php




                            // Instantiate queue with aws credentials from config.
        $queue = new Queue(QUEUE_NAME, unserialize(AWS_CREDENTIALS));

                            // Continuously poll queue for new messages and process them.
        while (true) {
            $message = $queue->receive();
            if ($message) {
                try {
                    $message->process();
                    $queue->delete($message);
                        } catch (Exception $e) {
                    $queue->release($message);
                    echo $e->getMessage();
                        }
                        } else {
                            // Wait 20 seconds if no jobs in queue to minimise requests to AWS API
                sleep(20);
                        }
                        }
