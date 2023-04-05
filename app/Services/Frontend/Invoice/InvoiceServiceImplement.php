<?php

namespace App\Services\Frontend\Invoice;

use App\Repository\Frontend\Invoice\InvoiceRepository;

use App\Services\Frontend\Payment\Coda\CodaGatewayService;
use App\Services\Frontend\Payment\Gocpay\GocpayGatewayService;
use App\Services\Frontend\Payment\GudangVoucher\GudangVoucherGatewayService;
use App\Services\Frontend\Payment\Motionpay\MotionpayGatewayService;
use App\Services\Frontend\Payment\Razer\RazerGatewayService;
use App\Services\Frontend\Payment\Unipin\UnipinGatewayService;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;

class InvoiceServiceImplement implements InvoiceService
{
  private $_expireInvoiceTimeMinute = 10;

  public function __construct(
    private InvoiceRepository $_invoiceRepository,

    private CodaGatewayService $_codaGateWayService,
    private MotionpayGatewayService $_motionpayGateWayService,
    private GudangVoucherGatewayService $_gudangVoucherGatewayService,
    private GocpayGatewayService $_gocpayGatewayService,
    private RazerGatewayService $_razerGateWayService,
    private UnipinGatewayService $_unipinGatewayService,
  ) {
  }

  public function getInvoice(string $id)
  {
    try {
      $dataTransaction = $this->_invoiceRepository->getTransactionById($id);

      if (!$dataTransaction) throw new Exception('No data', 404);

      // $now = Carbon::createFromTimeString(Carbon::now());
      // $expireInvoice = Carbon::createFromFormat('Y-m-d H:i:s', $dataTransaction['date'])->addMinutes($this->_expireInvoiceTimeMinute);

      // if ($now >= $expireInvoice->toDateTimeString()) throw new Exception('No data', 404);

      $dataPayment = $this->_invoiceRepository->getDetailPrice($dataTransaction->price_id)->toArray();

      $result['invoice'] = $dataTransaction->toArray();
      $result['game'] = $this->_invoiceRepository->getGameInfo($dataTransaction->game_id);
      $result['payment'] = $dataPayment;
      $result['payment']['invoice'] = $dataTransaction->invoice;
      $result['payment']['user'] = $dataTransaction->id_player;
      $result['payment']['email'] = $dataTransaction->email;
      $result['payment']['phone'] = $dataTransaction->phone;
      $result['payment']['ppn'] = $this->_invoiceRepository->getAllDataPpn()[0]['ppn'];
      $result['payment']['total_price'] = $dataTransaction->total_price;

      // dd($result);

      if ($dataTransaction['status'] == 0) {
        $result['attribute'] = $this->_getPaymentAttribute($result['payment'], $result['game']);
        return $result;
      }

      return $result;
    } catch (\Exception $error) {
      throw new Exception('No data', 404);
    }
  }

  public function redirectToPayment(string $codePayment = null, array $dataParse = null)
  {
    if (empty($dataParse)) return 'Prosess can not be continued, no value.';

    switch (Str::upper(($codePayment))) {
      case env("CODA_CODE_PAYMENT"):
        // dd($this->_codaGateWayService->urlRedirect($dataParse));
        return $this->_codaGateWayService->urlRedirect($dataParse);
        break;
      case env("RAZOR_CODE_PAYMENT"):
        return $this->_razerGateWayService->urlRedirect($dataParse);
        break;
      case env("UNIPIN_CODE_PAYMENT"):
        return $this->_unipinGatewayService->urlRedirect($dataParse);
        break;
      default:
        echo 'No code payment';
        break;
    }
  }

  public function confrimInfo(array $dataRequest)
  {
    $data = [
      'message' => 'No info'
    ];

    if ($dataRequest['trans_id']) {
      $data['message'] = ($dataRequest['status_desc'] == 'failed') ? 'Payment ' . $dataRequest['status_desc'] . ', please try again.' : 'Payment success, thanks';
      $data['payment'] = 'Motionpay';
      return $data;
    }

    return $data;
  }

  private function _getPaymentAttribute(array $dataPayment = null, array $dataGame = null)
  {
    if (empty($dataPayment) || empty($dataGame)) return 'data is null';

    switch (Str::upper($dataPayment['code_payment'])) {
      case env('CODA_CODE_PAYMENT'):
        $dataAttribute = $this->_codaGateWayService->generateDataParse($dataPayment);
        // dd($dataAttribute);

        return json_encode($dataAttribute);
        break;

      case env('GOC_CODE_PAYMENT'):
        $dataAttribute = $this->_gocpayGatewayService->generateDataParse($dataPayment);

        return json_encode($dataAttribute);
        break;

      case env('GV_CODE_PAYMENT'):
        $dataAttribute = $this->_gudangVoucherGatewayService->generateDataParse($dataPayment);

        return json_encode($dataAttribute);
        break;

      case env('MOTIONPAY_CODE_PAYMENT'):
        $dataAttribute = $this->_motionpayGateWayService->generateDataParse($dataPayment);

        return $dataAttribute;
        break;

      case env('UNIPIN_CODE_PAYMENT'):
        $dataAttribute = $this->_unipinGatewayService->generateDataParse($dataPayment, $dataGame);

        return json_encode($dataAttribute);
        break;

      case env('RAZOR_CODE_PAYMENT'):
        $dataAttribute = $this->_razerGateWayService->generateDataParse($dataPayment);

        return json_encode($dataAttribute);
        break;

      default:
        return abort(404, 'Payment can\'t find.');
        break;
    }
  }
}
