<?php
namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

use yii\httpclient\Client;
use yii\httpclient\Request;
use yii\web\NotFoundHttpException;


/**
 * Site controller
 */
class SiteController extends Controller
{

    public $layout = '@app/views/layouts/xLayout.php';

    protected function checkResponse($response)
    {
        if (!$response->isOk && (!empty($response->data['message']))){
            $session = Yii::$app->session;
            $session->setFlash('danger', $response->data['message']);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionSeansesList()
    {
        $t=1;
        if (!\Yii::$app->request->isPost){
            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl('http://advanced.admin/v1/sale')
                // ->setData(['name' => 'John Doe', 'email' => 'johndoe@example.com'])
                ->send();
            $seansesList = $response->isOk ? $response->data : [];
            $result['data'] = $response->data;
            $result['code'] = $response->headers['http-code'];
            $result['headers'] = $response->headers;
            return $this->render('seansesList',[
                'seansesList' => $seansesList,
                'response' => $response,
                'result' => $result,
            ]);
        } else {
            $_post = \Yii::$app->request->post();
            if (isset($_post['seansId'])){
                $seansId = $_post['seansId'];
                return $this->redirect(['/site/choise-seats', 'seansId' =>$seansId]);
            } else {
                throw new NotFoundHttpException('Сеанс не найден');
            }
        }

    }

    public function actionChoiseSeats($seansId)
    {
        $t=1;
        if (!\Yii::$app->request->isPost){
            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl('http://advanced.admin/v1/sale/get-seans')
                ->setData(['id' => $seansId])
                ->send();
            $seans = $response->isOk ? $response->data : [];
            $result['data'] = $response->data;
            $result['code'] = $response->headers['http-code'];
            $result['headers'] = $response->headers;
            return $this->render('seans',[
                'seans' => $seans,
                'response' => $response,
                'result' => $result,
            ]);

        } else {
            $_post = \Yii::$app->request->post();
            if (isset($_post['reservation'])){
                $datas = json_decode($_post['reservation'], true);
                if (!empty($datas)){
                    return $this->redirect(['/site/make-reservation',
                        'seansId' => $seansId,
                        'reservation' => $_post['reservation'],
                    ]);
                }
                return $this->redirect('/site/seanses-list');
            } else {
                throw new NotFoundHttpException('Сеанс не найден');
            }
        }
    }

    public function actionMakeReservation($seansId, $reservation)
    {
        $datas = json_decode($reservation, true);
        foreach ($datas as $data){
            $buf = json_decode($data, true);
            $myReservation[] = [
                'rowNumber' => $buf['rowNumber'],
                'seatNumber' => $buf['seatNumber'],
                'persona' => 'lokoko',
            ];
        }
        if (!empty($myReservation)){
            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl('http://advanced.admin/v1/sale/get-reservation')
                ->setData(['seansId' => $seansId, 'reservation' => $myReservation] )
                ->send();
            $this->checkResponse($response);
        //    return $this->render('debug' , ['response' => $response] );
            if ($response->isOk){
                return $this->render('seansSuccessMessage', ['reservation' => $response->data]);
            } else {
                return $this->redirect(['/site/choise-seats', 'seansId' => $seansId]);
            }
        } else {
            throw new NotFoundHttpException('Данные пусты');
        }

    }






    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }


}
