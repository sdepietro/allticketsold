@extends('personas.layouts.app')
@section('content')
    <div class="containerEdit bgMainObras">

        <div class="my-12">
            <div class="container text-white py-section bgCardObra">
              <div class="grid grid-cols-12 md:gap-4 gap-y-10">
                <div class="col-span-12 md:col-span-3">
                  <h3 class="text-3xl font-bold font-primary">
                    Mi cuenta
                  </h3>
                  <div class="flex flex-col mt-6">
                    <a
                      class="block py-3 pl-4 font-semibold border-l-4 hover:border-red-600 hover:text-red-600"
                      href="{{ route('personas.editarperfil') }}"
                      >Detalles de cuenta</a
                    >
                    <a
                      class="block py-3 pl-4 font-semibold border-l-4 border-emerald-100 hover:border-red-600 hover:text-red-600"
                      href="{{ route('personas.miscompras') }}"
                      >Mis Compras</a
                    >
                    <a
                      class="block py-3 pl-4 font-semibold border-l-4 border-emerald-100 hover:border-red-600 hover:text-red-600"
                      href="{{ route('personas.cambiarpass') }}"
                      >Cambiar contraseña</a
                    >
                  </div>
                  <div class="mt-6">
                    <form action="{{ route('personas.logout') }}" method="POST">
        	@csrf
       		 <button class="px-4 py-2 text-sm font-bold leading-none text-red-600 uppercase border border-red-600 rounded hover:bg-red-600 hover:text-white disabled:opacity-25"
 type="submit">Cerrar sesión</button>
   		 </form>
                  </div>
                </div>
				@foreach($orders as $order)
               <div class="col-span-12 md:col-span-9">
                <div class="space-y-5">
                  <h2 class="text-3xl font-medium">Codigo: #{{ $order->order_reference }}</h2>
                  <div class="grid grid-cols-3 gap-4"><div>
                  <h3 class="text-xl font-medium">Datos de la Funcion</h3>
                  <div class="mt-2 space-y-3 "><div>
                    <span class="block mr-3 text-xs  text-emerald-500">Nombre</span>
                    <div class="text-sm">{{ $order->event->title ?? 'Sin nombre' }}.</div>
                  </div>
                    <div>
                      <span class="block mr-3 text-xs  text-emerald-500">Duracion</span>
                      <div class="text-sm">{{ $order->event->location_post_code }} mins</div>
                    </div>
                      <div>
                        <span class="block mr-3 text-xs  text-emerald-500">Direccion</span>
                        <div class="text-sm">
                        <div>{{ $order->event->teatro->nombre }}</div>
                      </div>
                      </div>
                        <div>

                        </div>
                      </div>
                      </div>
                      <div>
                          <h3 class="text-xl font-medium">Datos de Pago</h3>
                          <div class="mt-2 space-y-3 ">
                            <div>
							@php

								$date =  $order->created_at;
								$formattedDate = \Carbon\Carbon::parse($date)->locale('es')->translatedFormat('l, d \d\e M \d\e Y, H:i:s');
							@endphp
                            <span class="block mr-3 text-xs  text-emerald-500">Fecha de compra</span>
                            <div class="text-sm">{{ $formattedDate }}</div>
                          </div>
                          <div>
                              <span class="block mr-3 text-xs  text-emerald-500">Estado del Pago</span>
                              <div class="text-sm">
                                <div class="flex items-center mt-2 gap-x-2">
                                <div class="text-xs font-medium">
                                  <div class="inline-flex items-center px-2 leading-5 text-green-100 bg-green-500 rounded-lg ">
                                  <span>Aprobado</span>
                                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="w-4 h-4 ml-1">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd">

                                    </path></svg>
                                  </div>
                                </div>
                                <div>

                                </div>
                              </div>
                              </div>
                              </div>

                                <div>
                                  <a class="btn btn-primary" target="_blank"
                   href="{{route('showOrderTickets', ['order_reference' => $order->id])}}?download=1"><span class="block px-4 py-2 mr-3 text-sm font-bold text-white bg-red-600 rounded-full  w-max">Descargar Boletos</span></a>
                                </div>
								<div>
								<a class="inline-flex items-center px-4 py-2 text-sm font-bold leading-none text-green-600 uppercase border border-green-600 rounded hover:bg-green-600 hover:text-white disabled:opacity-25" style="
    padding: 7px;
    margin-bottom: 10px;
    border-radius: 13px;
    font-size: 0.875rem;
    line-height: 1.25rem;
    font-weight: 700;
    border-width: medium;
" href="{{ route('personas.verentradas', $order->order_reference) }}">Editar Asistentes</a>
								</div>
                                </div>
                                </div>
                                </div>
                                  <div>
                                    <table class="w-full overflow-hidden rounded-lg table-fixed">
                                      <thead>
                                        <tr class="bg-dark-blue-700"><th class="p-3 font-semibold text-left text-heading">Tipos Boletos</th>
                                          <th class="p-3 font-semibold text-left text-heading">Monto</th>
                                        </tr>
                                      </thead>
                                        <tbody class="divide-y divide-dark-blue-700">
										@foreach($order->orderItems as $order_item)
                                          <tr>
                                            <td class="p-3">{{$order_item->quantity}} x {{$order_item->title}}</td>
                                            <td class="p-3">{{money(($order_item->unit_price) * ($order_item->quantity), $order->event->currency)}}</td>
                                          </tr>
										  @endforeach
										  @php
												$total_booking_fee = 0;
											@endphp
										  @foreach($order->orderItems as $order_item)
											@php
												$total_booking_fee += ($order_item->unit_booking_fee * $order_item->quantity);
											@endphp
										  @endforeach

                                          <tr>
                                            <td class="p-3">Servicio</td>
                                            <td class="p-3">{{money($total_booking_fee, $order->event->currency)}}</td>
                                          </tr>

                                          <tr class="italic font-semibold bg-dark-blue-700">
                                            <td class="p-3 ">Subtotal</td>
                                            <td class="p-3">{{money($order->total_amount, $order->event->currency)}}</td>
                                          </tr>

										  @if($order->event->organiser->charge_tax)
											   <tr class="italic font-semibold bg-dark-blue-700">
                                            <td class="p-3 ">Tarifa {{$order->event->organiser->tax_name}}</td>
                                            <td class="p-3">{{ $order->getOrderTaxAmount()->format() }}</td>
											</tr>
											@endif


                                      <tr class="text-lg font-bold bg-dark-blue-700">
                                        <td class="p-3 ">Total</td>
                                        <td class="p-3">{{ $order->getOrderAmount()->add($order->getOrderTaxAmount())->format() }}</td>
                                      </tr>
                                      </tbody>
                                    </table>
                                    </div>
                                  </div>
                                  </div>

								  @endforeach
              </div>
            </div>


    </div>
@endsection
