<?php


namespace app;


use app\model\command\CancelPrize;
use app\model\command\ConvertMoney2Bonuses;
use app\model\command\SendPrize;
use app\model\Prize;
use app\prizeGenerators\PrizeGenerator;

class Endpoints
{
    private int $httpCode = 200;

    private $response;

    private function setMessage($message)
    {
        $this->response = $message;
    }

    private function setCode(int $code)
    {
        $this->httpCode = $code;
    }

    public function process(string $path)
    {
        $route = explode('/', $path);
        switch ($route[1]) {
            case 'ping':
                $this->setMessage('pong');
                break;
            case 'get-prize':
                $this->getPrize();
                break;
            case 'send-prize':
                $this->sendPrize();
                break;
            case 'money-2-bonuses':
                $this->convertMoney2Bonuses();
                break;
            case 'cancel-prize':
                $this->cancelPrize();
                break;
        }

        header_remove();
        http_response_code($this->httpCode);
        if (isset($this->response)) {
            if (is_string($this->response)) {
                echo $this->response;
            } else {
                header("Content-type: application/json; charset=utf-8");
                echo json_encode($this->response, JSON_UNESCAPED_UNICODE);
            }
        }
    }

    private function loadPrize(string $email): ?Prize
    {
        if (empty($email)) {
            $this->setCode(400);
            $this->setMessage("No email provided");
            return null;
        }
        return App::$prizeRepository->get($email);
    }

    public function getPrize(): void
    {
        $email = $_POST['email'];
        $prize = $this->loadPrize($email);
        if (is_null($prize)) {
            $pg = new PrizeGenerator(App::prizesConfiguration());
            App::log("Generating a prize for the email '$email'");
            $pg->generate($email);
        }
        $this->setMessage(App::$prizeRepository->get($email));
    }

    public function sendPrize(): void
    {
        $prize = $this->loadPrize($_POST['email']);
        if (is_null($prize)) {
            return;
        }
        App::$commandBus->dispatch(new SendPrize(['email' => $prize->email]));
        $this->setMessage("Sent");
    }

    public function convertMoney2Bonuses(): void
    {
        $prize = $this->loadPrize($_POST['email']);
        if (is_null($prize)) {
            return;
        }
        App::$commandBus->dispatch(new ConvertMoney2Bonuses(['email' => $prize->email]));
        $this->setMessage("Converted");
    }

    public function cancelPrize(): void
    {
        $prize = $this->loadPrize($_POST['email']);
        if (is_null($prize)) {
            return;
        }
        if ($prize->sent) {
            $this->setCode(409);
            $this->setMessage('Cannot cancel sent prize');
            return;
        }
        if ($prize->cancelled) {
            $this->setCode(409);
            $this->setMessage('Prize is already canceled');
            return;
        }
        App::$commandBus->dispatch(new CancelPrize(['email' => $prize->email]));
        $this->setMessage("Cancelled");
    }

}