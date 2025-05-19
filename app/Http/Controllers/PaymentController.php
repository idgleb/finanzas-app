<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

// 1) Configuración
use MercadoPago\MercadoPagoConfig;

// 2) Cliente de preferencias
use MercadoPago\Client\Preference\PreferenceClient;

// 3) Cliente de pagos
use MercadoPago\Client\Payment\PaymentClient;

// 4) Excepción de la API
use MercadoPago\Exceptions\MPApiException;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Configurar el token de acceso de Mercado Pago desde .env
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));
    }

    public function index()
    {
        return view('payments.index');
    }

    public function process(Request $request)
    {
        try {
            Log::info('Iniciando proceso de pago', [
                'user_id' => Auth::id(),
                'access_token' => substr(config('services.mercadopago.access_token'), 0, 10) . '...' // Solo mostramos los primeros 10 caracteres por seguridad
            ]);

            $plan = Plan::getProPlan();

            if (!$plan) {
                Log::error('Plan PRO no encontrado en la base de datos');
                return redirect()->route('home')->with('error', 'El plan PRO no está disponible en este momento.');
            }

            // Asegurar que el precio sea válido
            if (!is_numeric($plan->price) || $plan->price <= 0) {
                Log::error('Precio del plan inválido', ['price' => $plan->price]);
                return redirect()->route('payments.pro-required')
                    ->with('error', 'El precio del plan no es válido. Por favor, contacte al administrador.');
            }

            //  Armar los ítems
            $items = [[
                'title' => $plan->name . ' - Finanzas App',
                'quantity' => 1,
                'unit_price' => (float)$plan->price,
                'currency_id' => 'ARS',
            ]];

            //  URLs de retorno - Asegurar HTTPS
            $backUrls = [
                "success" => secure_url(route('payments.success', [], false)),
                "failure" => secure_url(route('payments.failure', [], false)),
                "pending" => secure_url(route('payments.pending', [], false)),
            ];

            //  Payload de la preferencia
            $preferenceData = [
                'items' => $items,
                'back_urls' => $backUrls,
                'auto_return' => 'approved',
                'notification_url' => secure_url(route('payments.webhook', [], false)),
                'external_reference' => Auth::id(),
                'expires' => true,
                'expiration_date_from' => now()->toIso8601String(),
                'expiration_date_to' => now()->addHour()->toIso8601String(),
            ];

            Log::info('Creando preferencia de pago', [
                'preference_data' => $preferenceData
            ]);

            try {
                //  Crear la preferencia usando el cliente
                $client = new PreferenceClient();
                $preference = $client->create($preferenceData);

                Log::info('Preferencia creada exitosamente', [
                    'preference_id' => $preference->id,
                    'init_point' => $preference->init_point
                ]);

                //  Redirigir al punto de pago
                return redirect($preference->init_point);
            } catch (MPApiException $e) {
                Log::error('Error de API de Mercado Pago', [
                    'error' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'trace' => $e->getTraceAsString(),
                    'preference_data' => $preferenceData
                ]);

                return redirect()->route('payments.pro-required')
                    ->with('error', 'Error al procesar el pago. Por favor, intente nuevamente más tarde.');
            }
        } catch (\Exception $e) {
            Log::error('Error inesperado en el proceso de pago', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('payments.pro-required')
                ->with('error', 'Ha ocurrido un error inesperado. Por favor, intente nuevamente más tarde.');
        }
    }

    public function success(Request $request)
    {
        Log::info('Pago exitoso - Redirección', [
            'user_id' => Auth::id(),
            'query_params' => $request->query()
        ]);

        // Verificar el estado del pago
        $paymentId = $request->query('payment_id');
        if ($paymentId) {
            try {
                $client = new PaymentClient();
                $payment = $client->get($paymentId);

                if ($payment->status === 'approved') {
                    // Actualizar el plan del usuario a PRO
                    $user = Auth::user();
                    $planPro = Plan::getProPlan();
                    
                    if ($planPro) {
                        $user->plan_id = $planPro->id;
                        $user->save();

                        Log::info('Plan actualizado a PRO desde success', [
                            'user_id' => $user->id,
                            'plan_id' => $planPro->id,
                            'payment_id' => $paymentId
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error al verificar pago en success', [
                    'error' => $e->getMessage(),
                    'payment_id' => $paymentId
                ]);
            }
        }

        return view('payments.success');
    }

    public function failure(Request $request)
    {
        Log::info('Pago fallido - Redirección', [
            'user_id' => Auth::id(),
            'query_params' => $request->query()
        ]);
        return view('payments.failure');
    }

    public function pending(Request $request)
    {
        Log::info('Pago pendiente - Redirección', [
            'user_id' => Auth::id(),
            'query_params' => $request->query()
        ]);
        return view('payments.pending');
    }

    public function webhook(Request $request)
    {
        Log::info('Webhook recibido de Mercado Pago', [
            'payload' => $request->all(),
            'headers' => $request->headers->all(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip()
        ]);

        try {
            $paymentId = null;
            $notificationType = $request->input('type');
            $topic = $request->input('topic');

            Log::info('Tipo de notificación recibida', [
                'type' => $notificationType,
                'topic' => $topic
            ]);

            // Obtener el ID del pago de diferentes formatos de notificación
            if ($notificationType === 'payment') {
                $paymentId = $request->input('data.id');
                Log::info('Payment ID obtenido de notificación tipo payment', ['payment_id' => $paymentId]);
            } elseif ($topic === 'payment') {
                $paymentId = $request->input('id');
                Log::info('Payment ID obtenido de notificación topic payment', ['payment_id' => $paymentId]);
            } elseif ($topic === 'merchant_order') {
                $merchantOrderId = $request->input('id');
                if ($merchantOrderId) {
                    try {
                        Log::info('Obteniendo información de merchant_order', ['merchant_order_id' => $merchantOrderId]);

                        $client = new \MercadoPago\Client\MerchantOrder\MerchantOrderClient();
                        $order = $client->get($merchantOrderId);

                        if ($order && !empty($order->payments)) {
                            $paymentId = $order->payments[0]->id;
                            Log::info('Payment ID obtenido de merchant_order', [
                                'merchant_order_id' => $merchantOrderId,
                                'payment_id' => $paymentId
                            ]);
                        } else {
                            Log::warning('Merchant order no tiene pagos asociados', [
                                'merchant_order_id' => $merchantOrderId
                            ]);
                            return response()->json([
                                'status' => 'warning',
                                'message' => 'La orden no tiene pagos asociados'
                            ], 200);
                        }
                    } catch (\Exception $e) {
                        Log::error('Error al obtener merchant_order', [
                            'merchant_order_id' => $merchantOrderId,
                            'error' => $e->getMessage()
                        ]);
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Error al obtener información de la orden: ' . $e->getMessage()
                        ], 500);
                    }
                }
            }

            if (!$paymentId) {
                Log::info('Notificación ignorada - no se pudo obtener payment_id', [
                    'type' => $notificationType,
                    'topic' => $topic,
                    'payload' => $request->all()
                ]);
                return response()->json([
                    'status' => 'ignored',
                    'message' => 'No se pudo obtener el ID del pago'
                ], 200);
            }

            try {
                Log::info('Obteniendo información del pago', ['payment_id' => $paymentId]);

                $client = new PaymentClient();
                $payment = $client->get($paymentId);

                Log::info('Información del pago obtenida', [
                    'payment_id' => $paymentId,
                    'status' => $payment->status,
                    'external_reference' => $payment->external_reference,
                    'payment_method_id' => $payment->payment_method_id,
                    'payment_type_id' => $payment->payment_type_id
                ]);

                if ($payment->status === 'approved') {
                    $user = User::find($payment->external_reference);
                    $planPro = Plan::getProPlan();

                    if ($user && $planPro) {
                        if (!$user->isPro()) {
                            $user->plan_id = $planPro->id;
                            $user->save();

                            Log::info('Plan actualizado a PRO para usuario', [
                                'user_id' => $user->id,
                                'email' => $user->email,
                                'plan_id' => $planPro->id,
                                'payment_id' => $paymentId
                            ]);
                        } else {
                            Log::info('Usuario ya tiene plan PRO', [
                                'user_id' => $user->id,
                                'email' => $user->email,
                                'payment_id' => $paymentId
                            ]);
                        }
                    } else {
                        Log::error('Usuario o plan no encontrado para el pago', [
                            'external_reference' => $payment->external_reference,
                            'payment_id' => $paymentId
                        ]);
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Usuario o plan no encontrado'
                        ], 404);
                    }
                } else {
                    Log::info('Pago no aprobado', [
                        'status' => $payment->status,
                        'payment_id' => $paymentId
                    ]);
                    return response()->json([
                        'status' => 'pending',
                        'message' => 'El pago aún no ha sido aprobado'
                    ], 200);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Proceso completado exitosamente'
                ]);
            } catch (MPApiException $e) {
                Log::error('Error de API de Mercado Pago al obtener información del pago', [
                    'error' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'payment_id' => $paymentId
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error al obtener información del pago: ' . $e->getMessage()
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error inesperado en webhook de Mercado Pago', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }

    public function proRequired()
    {
        $plan = Plan::getProPlan();

        if (!$plan) {
            return redirect()->route('home')->with('error', 'El plan PRO no está disponible en este momento.');
        }

        return view('categories.pro-required', compact('plan'));
    }
}
