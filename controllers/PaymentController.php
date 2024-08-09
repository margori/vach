<?php

namespace app\controllers;

use app\models\BuyModel;
use app\models\Payment;
use app\models\Stock;
use app\models\Transaction;
use app\models\User;
use Exception;
use Yii;
use yii\httpclient\Client;
use yii\web\Response;

class PaymentController extends BaseController {
    public $layout = 'inner';
    public $enableCsrfValidation = false;

    public function beforeAction($action) {
        if ($action->id == 'confirmation'
            || $action->id == 'response'
            || $action->id == 'init') {
            return true;
        }
        return parent::beforeAction($action);
    }

    public function actionIndex() {
        $models = Payment::browse();

        return $this->render('index', [
            'models' => $models,
        ]);
    }

    public function actionView($id) {
        $model = Payment::findOne(['id' => $id]);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionSent() {
        return $this->render('sent');
    }

    public function actionSelect($referenceCode) {
        $payment = Payment::findOne(['uuid' => $referenceCode]);

        $model = BuyModel::fromPayment($payment);

        $action = Yii::$app->request->post('selection-button');
        if ($model->load(Yii::$app->request->post())) {
            if ($action == 'send') {
                return $this->sendThirdParty($model, $payment->coach->email);
            } else {
                return $this->redirect(['payment/init', 'referenceCode' => $model->referenceCode]);
            }
        }

        return $this->render('select', [
            'model' => $model,
        ]);
    }

    private function sendThirdParty($model, $email) {
        if (!$model->payerEmail) {
            $model->addError('payerEmail', \Yii::t('stock', 'Email required to send link'));

            return $this->render('select', [
                'model' => $model,
            ]);
        } else {
            $paymentLink = Yii::$app->urlManager->createAbsoluteUrl(['payment/init', 'referenceCode' => $model->referenceCode]);

            Yii::$app->mailer->compose('payment_send', [
                'paymentLink' => $paymentLink,
            ])
                ->setSubject(\Yii::t('stock', Yii::$app->params['app']['name'] . ': licences payment link'))
                ->setFrom(Yii::$app->params['senderEmail'])
                ->setTo($model->payerEmail)
                ->setCc($email)
                ->send();

            return $this->redirect(['/payment/sent']);
        }
    }

    public function actionInit($referenceCode) {
        $this->layout = Yii::$app->user->isGuest ? 'printable' : 'inner';

        $payment = Payment::findOne(['uuid' => $referenceCode]);

        if (!$payment) {
            return $this->goHome();
        }

        if ($payment->status != Payment::STATUS_PENDING) {
            switch ($payment->status) {
            case Payment::STATUS_PAID:
                return $this->render('response_success');
            default:
                return $this->render('response_declined');
            }
        }

        $transaction = $payment->newTransaction();

        $buyModel = BuyModel::fromTransaction($transaction);

        if (isset(Yii::$app->params['payu'])) {
            echo "s";
            return $this->render('/payment/payu/redirect', [
                'buyModel' => $buyModel,
            ]);
        } else if (isset(Yii::$app->params['paypal'])) {
            return $this->render('/payment/paypal/index', [
                'buyModel' => $buyModel,
            ]);
        }
    }

    public function actionResponse() {
        $this->layout = Yii::$app->user->isGuest ? 'printable' : 'inner';

        $referenceCode = Yii::$app->request->get('referenceCode');
        $lapTransactionState = Yii::$app->request->get('lapTransactionState');

        $model = Transaction::findOne(['uuid' => $referenceCode]);

        $responses = [
            'APPROVED' => [
                Payment::STATUS_PENDING => 'wait',
                Payment::STATUS_PAID => 'success',
                Payment::STATUS_REJECTED => 'wait',
                Payment::STATUS_ERROR => 'wait',
            ],
            'DECLINED' => [
                Payment::STATUS_PENDING => 'declined',
                Payment::STATUS_PAID => 'error',
                Payment::STATUS_REJECTED => 'error',
                Payment::STATUS_ERROR => 'declined',
            ],
            'EXPIRED' => [
                Payment::STATUS_PENDING => 'declined',
                Payment::STATUS_PAID => 'error',
                Payment::STATUS_REJECTED => 'error',
                Payment::STATUS_ERROR => 'declined',
            ],
            'PENDING' => [
                Payment::STATUS_PENDING => 'pending',
                Payment::STATUS_PAID => 'error',
                Payment::STATUS_REJECTED => 'pending',
                Payment::STATUS_ERROR => 'pending',
            ],
            'ERROR' => [
                Payment::STATUS_PENDING => 'error',
                Payment::STATUS_PAID => 'error',
                Payment::STATUS_REJECTED => 'error',
                Payment::STATUS_ERROR => 'error',
            ],
        ];

        switch ($responses[$lapTransactionState][$model->status]) {
        case 'success':
            return $this->render('response_success', [
                'referenceCode' => $referenceCode,
            ]);
        case 'wait':
            return $this->render('response_wait');
        case 'pending':
            return $this->render('response_pending');
        case 'declined':
            return $this->render('response_declined');
        default:
            $this->notifyError($referenceCode);
            return $this->render('response_error');
        }
    }

    // PAYPAL

    public function actionInvoice($referenceCode) {
        $client = new Client();
        $paypalApiUrl = Yii::$app->params['paypal']['api_url'];

        $transaction = Transaction::findOne(['uuid' => $referenceCode]);

        if (!$transaction) {
            return $this->goHome();
        }
        if ($transaction->status != Payment::STATUS_PAID) {
            return $this->goHome();
        }

        $accessToken = $this->generateAccessToken();
        $payment = $transaction->payment;

        // create invoice
        $invoicer = Yii::$app->params['invoicer'];
        $logo = Yii::$app->urlManager->hostInfo . Yii::getAlias('@web/images/logo.png');
        $coach = $transaction->payment->coach;
        $quantity = Stock::find()
            ->where(['payment_id' => $payment->id])
            ->count();
        $price = $transaction->amount / $quantity;

        $invoicePayload = [
            "detail" => [
                "currency_code" => $transaction->currency,
                "reference" => $transaction->uuid,
            ],
            "invoicer" => [
                "name" => [
                    "given_name" => $invoicer['name'],
                    "surname" => $invoicer['surname'],
                ],
                "address" => [
                    "address_line_1" => $invoicer['address_line_1'],
                    "address_line_2" => $invoicer['address_line_2'],
                    "admin_area_1" => $invoicer['admin_area_1'],
                    "admin_area_2" => $invoicer['admin_area_2'],
                    "postal_code" => $invoicer['postal_code'],
                    "country_code" => $invoicer['country_code'],
                ],
                //"logo_url" => $logo,
            ],
            "primary_recipients" => [
                [
                    "billing_info" => [
                        "name" => [
                            "given_name" => $coach->name,
                            "surname" => $coach->surname,
                        ],
                        "email_address" => $coach->email,
                    ],
                ],
            ],
            "items" => [
                [
                    "name" => $payment->concept,
                    "quantity" => $quantity,
                    "unit_amount" => [
                        "currency_code" => $transaction->currency,
                        "value" => $price,
                    ],
                ],
            ],
        ];

        Yii::debug($invoicePayload);

        $response = $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod('post')
            ->setUrl($paypalApiUrl . '/v2/invoicing/invoices')
            ->setHeaders([
                "Authorization" => 'Bearer ' . $accessToken,
                "Content-Type" => 'application/json',
                // "PayPal-Mock-Response": '{"mock_application_codes": "MISSING_REQUIRED_PARAMETER"}'
                // "PayPal-Mock-Response": '{"mock_application_codes": "PERMISSION_DENIED"}'
                // "PayPal-Mock-Response": '{"mock_application_codes": "INTERNAL_SERVER_ERROR"}'
            ])
            ->setContent(json_encode($invoicePayload))
            ->send();

        Yii::debug($response->data);

        $invoiceUrl = $response->data['href'];
        $invoiceUrlParts = explode("/", $invoiceUrl);
        $invoiceId = end($invoiceUrlParts);
        $transaction->external_id = $invoiceId;
        $transaction->save();

        // send invoice
        // $sendInvoicePayload = [
        //     "send_to_recipient" => false,
        //     "send_to_invoicer" => false
        // ];

        // $response = $client->createRequest()
        //     ->setFormat(Client::FORMAT_JSON)
        //     ->setMethod('post')
        //     ->setUrl($paypalApiUrl . "/v2/invoicing/invoices/$invoiceId/send")
        //     ->setHeaders([
        //         "Authorization" => 'Bearer ' . $accessToken,
        //         "Content-Type" => 'application/json',
        //         // "PayPal-Mock-Response": '{"mock_application_codes": "MISSING_REQUIRED_PARAMETER"}'
        //         // "PayPal-Mock-Response": '{"mock_application_codes": "PERMISSION_DENIED"}'
        //         // "PayPal-Mock-Response": '{"mock_application_codes": "INTERNAL_SERVER_ERROR"}'
        //     ])
        //     ->setContent(json_encode($sendInvoicePayload))
        //     ->send();
        // Yii::debug($response->data);

        // mark invoice as payed
        $today = (new \DateTime('today'))->format('Y-m-d');
        $paymentPayload = [
            "method" => "PAYPAL",
            "payment_date" => $today,
            "amount" => [
                "currency_code" => $transaction->currency,
                "value" => $price,
            ],
        ];
        Yii::debug($paymentPayload);

        $response = $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod('post')
            ->setUrl($paypalApiUrl . "/v2/invoicing/invoices/$invoiceId/payments")
            ->setHeaders([
                "Authorization" => 'Bearer ' . $accessToken,
                "Content-Type" => 'application/json',
                // "PayPal-Mock-Response": '{"mock_application_codes": "MISSING_REQUIRED_PARAMETER"}'
                // "PayPal-Mock-Response": '{"mock_application_codes": "PERMISSION_DENIED"}'
                // "PayPal-Mock-Response": '{"mock_application_codes": "INTERNAL_SERVER_ERROR"}'
            ])
            ->setContent(json_encode($paymentPayload))
            ->send();
        Yii::debug($response->data);

        $paypalWebUrl = Yii::$app->params['paypal']['web_url'];
        $redirectUrl = $paypalWebUrl . "/invoice/p/#$invoiceId";
        return $this->redirect($redirectUrl);
    }

    public function actionCreateOrder($referenceCode) {
        $client = new Client();
        $paypalApiUrl = Yii::$app->params['paypal']['api_url'];
        $transaction = Transaction::findOne(['uuid' => $referenceCode]);

        if (!$transaction) {
            Yii::$app->response->statusCode = 404;
            return 'Not found';
        }

        $accessToken = $this->generateAccessToken();

        $payload = [
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "reference_id" => $referenceCode,
                    "amount" => [
                        "currency_code" => $transaction->currency,
                        "value" => $transaction->amount,
                    ],
                ],
            ],
        ];

        $response = $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod('post')
            ->setUrl($paypalApiUrl . '/v2/checkout/orders')
            ->setHeaders([
                "Authorization" => 'Bearer ' . $accessToken,
                "Content-Type" => 'application/json',
                // "PayPal-Mock-Response": '{"mock_application_codes": "MISSING_REQUIRED_PARAMETER"}'
                // "PayPal-Mock-Response": '{"mock_application_codes": "PERMISSION_DENIED"}'
                // "PayPal-Mock-Response": '{"mock_application_codes": "INTERNAL_SERVER_ERROR"}'
            ])

            ->setContent(json_encode($payload))
            ->send();

        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::debug($response->data);

        $transaction->external_id = $response->data['id'];
        $transaction->save();
        return $response->data;
    }

    public function actionCaptureOrder($orderId) {
        $client = new Client();
        $paypalApiUrl = Yii::$app->params['paypal']['api_url'];
        $transaction = Transaction::findOne(['external_id' => $orderId]);

        if (!$transaction) {
            Yii::$app->response->statusCode = 404;
            return 'Not found';
        }

        $accessToken = $this->generateAccessToken();

        $response = $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod('post')
            ->setUrl("$paypalApiUrl/v2/checkout/orders/$orderId/capture")
            ->setHeaders([
                "Authorization" => 'Bearer ' . $accessToken,
                "Content-Type" => 'application/json',
                // "PayPal-Mock-Response": '{"mock_application_codes": "MISSING_REQUIRED_PARAMETER"}'
                // "PayPal-Mock-Response": '{"mock_application_codes": "PERMISSION_DENIED"}'
                // "PayPal-Mock-Response": '{"mock_application_codes": "INTERNAL_SERVER_ERROR"}'
            ])
            ->send();
        Yii::debug($response);

        if (!$response->isOk) {
            throw new Exception("Error: fail to capture payment in PayPal");
        }

        $this->setTransactionPayed($transaction);

        return "OK";
    }

    private function generateAccessToken() {
        $PAYPAL_CLIENT_ID = Yii::$app->params['paypal']['CLIENT_ID'];
        $PAYPAL_CLIENT_SECRET = Yii::$app->params['paypal']['CLIENT_SECRET'];

        if (!$PAYPAL_CLIENT_ID || !$PAYPAL_CLIENT_SECRET) {
            throw new Exception("MISSING_PAYPAL_CREDENTIALS");
        }

        $auth = base64_encode($PAYPAL_CLIENT_ID . ":" . $PAYPAL_CLIENT_SECRET);

        $client = new Client();

        $paypalApiUrl = Yii::$app->params['paypal']['api_url'];

        $response = $client->createRequest()
            ->setMethod('post')
            ->setUrl($paypalApiUrl . '/v1/oauth2/token')
            ->setHeaders([
                "Authorization" => 'Basic ' . $auth,
                "Content-Type" => 'application/x-www-form-urlencoded',
            ])
            ->setContent('grant_type=client_credentials')
            ->send();

        if (!$response->isOk) {
            throw new Exception("Error: fail to get access token from PayPal");
        }

        $accessToken = $response->data['access_token'];
        return $accessToken;
    }

    // PAYU

    public function actionConfirmation() {
        try {
            $referenceCode = Yii::$app->request->post('reference_sale');

            $transaction = Transaction::findOne(['uuid' => $referenceCode]);

            if ($transaction->status == Payment::STATUS_PAID) {
                return 'OK';
            }

            $transaction->external_id = Yii::$app->request->post('reference_pol');
            $transaction->external_data = serialize($_POST);

            $state_pol = Yii::$app->request->post('state_pol');

            switch ($state_pol) {
            case 4:
                $this->setTransactionPayed($transaction);
                break;
            case 7:
                $transaction->status = Payment::STATUS_PENDING;
                $transaction->save();
                break;
            case 5:
            case 6:
                $transaction->status = Payment::STATUS_REJECTED;
                $transaction->save();

                $payment = $transaction->payment;
                $stocks = $payment->stocks;

                foreach ($stocks as $stock) {
                    $stock->status = Stock::STATUS_INVALID;
                    $stock->save();
                }
                break;
            default:
                if ($transaction->status != Payment::STATUS_PAID) {
                    $transaction->status = Payment::STATUS_ERROR;
                    $transaction->save();
                }

                $payment = $transaction->payment;
                $stocks = $payment->stocks;

                if ($payment->status != Payment::STATUS_PAID) {
                    $payment->status = Payment::STATUS_ERROR;
                    $payment->save();
                    foreach ($stocks as $stock) {
                        $stock->status = Stock::STATUS_ERROR;
                        $stock->save();
                    }
                }
                break;
            }
        } catch (Exception $e) {

        }

        return 'OK';
    }

    private function setTransactionPayed($transaction) {
        $payment = $transaction->payment;
        $stocks = $payment->stocks;

        $transaction->status = Payment::STATUS_PAID;
        $transaction->rate = Yii::$app->request->post('exchange_rate') ?? 0;
        $transaction->commision = Yii::$app->request->post('commision_pol') ?? 0;
        $transaction->commision_currency = Yii::$app->request->post('commision_pol_currency') ?? "";
        if (!$transaction->save()) {
            Yii::debug($transaction->getErrorSummary(false));
            throw new Exception("Fail to save transaction");
        };

        $payment->status = Payment::STATUS_PAID;
        $payment->rate = Yii::$app->request->post('exchange_rate') ?? 0;
        $payment->commision = Yii::$app->request->post('commision_pol') ?? 0;
        $payment->commision_currency = Yii::$app->request->post('commision_pol_currency') ?? "";
        $payment->save();

        foreach ($stocks as $stock) {
            $stock->status = Stock::STATUS_VALID;
            $stock->save();
        }

        $this->notifyPayed($transaction->uuid);
    }

    private function notifyError($referenceCode) {
        Yii::$app->mailer->compose('payment_error', [
            'referenceCode' => $referenceCode,
        ])
            ->setSubject('Payment with issues')
            ->setFrom(Yii::$app->params['senderEmail'])
            ->setTo(User::getAdminEmails())
            ->send();
    }

    private function notifyPayed($referenceCode) {
        $transaction = Transaction::findOne(['uuid' => $referenceCode]);
        $payment = $transaction->payment;

        Yii::$app->mailer->compose('payment_success', [
            'model' => $payment,
        ])
            ->setSubject('Payment successful')
            ->setFrom(Yii::$app->params['senderEmail'])
            ->setTo($payment->coach->email)
            ->setBcc(User::getAdminEmails())
            ->send();
    }
}
