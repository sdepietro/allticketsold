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
                      class="block py-3 pl-4 font-semibold text-red-600 border-l-4 border-red-600 border-emerald-100"
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
                <div class="col-span-12 md:col-span-9">
                  <div class="space-y-5 containerTableMisCompras">
                    <h2 class="text-3xl font-medium">Mis Compras</h2>
                    <table class="w-full overflow-hidden rounded-lg">
                    <thead>
                    <tr class="bg-dark-blue-700">
					<th class="p-4 font-medium text-left text-heading">Id Transacción</th>
                    <th class="p-4 font-medium text-left text-heading">Evento</th>
                    <th class="p-4 font-medium text-left text-heading">Funcion</th>
                    <th class="p-4 font-medium text-left text-heading">Estado</th>
                    <th class="p-4 font-medium text-left text-heading">Boletos</th><th class="p-4 font-medium text-left text-heading">Total</th>
                  </tr>
                  </thead>
                    <tbody class="divide-y divide-dark-blue-700">
					@foreach($orders as $order)
					<tr class="hover:bg-dark-blue-700">
					<td class="px-4 py-4 text-left underline">
                        <p class="text-sm">{{ $order->business_address_line_one }}</p>
                      </td>
                      <td class="px-4 py-4 text-left underline">
                        <a href="{{ route('personas.detalles', ['id' => $order->id]) }}" class="text-sm">#{{ $order->order_reference }}</a>
                      </td><td class="px-4 py-4 text-left ">
					  @if($order->event)
                                <div class="font-medium">{{ $order->event->title ?? 'Sin nombre' }}</div> <!-- Muestra el nombre del evento -->
                            @else
                                <div class="font-medium">Sin detalles</div>
                            @endif

                      </td>
                      <td class="px-4 py-4 text-left ">
                        <div class="text-xs font-medium">
                        <div class="inline-flex items-center px-2 leading-5 text-green-100 bg-green-500 rounded-lg ">
                          <span>{{$order->orderStatus->name}}</span>
                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="w-4 h-4 ml-1"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        </div>
                      </div>
                    </td>
						@php
							$totalQuantity = 0; // Inicializamos la variable
						@endphp
						@foreach($order->orderItems as $order_item)
							@php
								$totalQuantity += $order_item->quantity; // Sumamos la cantidad
							@endphp
						@endforeach
                      <td class="px-4 py-4 text-left ">{{ $totalQuantity }}</td>
                      <td class="px-4 py-4 text-left ">{{ $order->getOrderAmount()->display() }}</td>
                    </tr>
					@endforeach

                      </tbody>
                        </table>
                        <div class="px-4 py-4 border-t border-dark-blue-400 ">
                          <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between text-gray-300">
                            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                            <div>
                              <p class="leading-5">Mostrando <span class="font-medium">1</span> a <span class="font-medium">10</span> de <span class="font-medium">700</span> resultados</p>
                            </div>
                            </div>
                              <div>
                                <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between space-x-2 ">
                                <span class="px-4 py-2 text-sm transition-colors opacity-20">Anterior</span>
                                <a class="px-4 py-2 text-sm font-medium transition hover:text-white">Siguiente</a>
                            </nav>
                            </div>
                          </nav>
                          </div>
                          </div>
                          </div>
              </div>
            </div>
        </div>
@endsection
